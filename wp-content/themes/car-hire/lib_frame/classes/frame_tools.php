<?php
/**
 * class bizz_custom_tools
 *
 * @package bizzthemes
 * @since 7.2.5
 */
class bizz_custom_tools {

	function manage_options() {
		global $bizzthemes_site, $wpdb, $themeid, $frameversion, $opt, $optd;
		
		if (isset($_POST['upload'])) {

			if ($_POST['upload'] == 'all') {
				check_admin_referer('bizzthemes-upload-all', '_wpnonce-bizzthemes-upload-all'); #wp				
				
				// DEFAULT OPTIONS
				$def_theme_id 			= $themeid;
				$def_frame_version 		= $frameversion;
				// UPLOADED OPTIONS
				$new_options 			= file_get_contents($_FILES['file']['tmp_name']);
				// remove BOM
				$new_options = mb_convert_encoding($new_options, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
				if( substr($new_options, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF) ) {
					$new_options = substr($new_options, 3);
				}
				// decode
				$new_options 			= json_decode($new_options, true);
				$new_theme_id 			= $new_options['theme_id'];
				$new_frame_version 		= $new_options['frame_version'];
				$new_options_id 		= $new_options['options_id'];
				
				if (function_exists('wp_cache_clean_cache')) { #wp
					global $file_prefix;
					wp_cache_clean_cache($file_prefix); #wp
				}
				
				// wrong file		
				$files_array = array('bizzthemes-layouts', 'bizzthemes-settings', 'bizzthemes-design');				
				$wrongfile = true;
				foreach ($files_array as $needle) {			
					if (strpos($_FILES['file']['name'], $needle)!==false)
						$wrongfile = false;			
				}				
				if ($wrongfile)
					wp_redirect(admin_url('admin.php?page=bizz-tools&type=Layouts&error=wrongfile')); #wp			
				// file error
				if ($_FILES['file']['error'] > 0)
					wp_redirect(admin_url('admin.php?page=bizz-tools&type=All&error=file')); #wp
				else { 
				// all fine
					if ($new_options['options_id'] == 'layouts') {
						if (version_compare($def_frame_version, $new_frame_version, '!='))
							wp_redirect(admin_url("admin.php?page=bizz-tools&type=Layouts&error=version&tried=$new_frame_version&yours=$def_frame_version")); #wp
						elseif ($def_theme_id != $new_theme_id)
							wp_redirect(admin_url("admin.php?page=bizz-tools&type=Layouts&error=theme&tried=$new_theme_id&yours=$def_theme_id")); #wp
						else {
							// read options
							$new_all_widgets 		= $new_options['options_value']['all_widgets'];
							$new_sidebars_widgets 	= $new_options['options_value']['sidebars_widgets'];
							// $new_sidebars_widgets 	= $new_options['options_value']['sidebars_widgets'][0]['option_value'];
							$new_widget_posts 		= ( isset($new_options['options_value']['widget_posts']) ) ? $new_options['options_value']['widget_posts'] : array();
							$new_grid_posts 		= ( isset($new_options['options_value']['grid_posts']) ) ? $new_options['options_value']['grid_posts'] : array();
							// reset old grids
							$query = "DELETE FROM $wpdb->posts WHERE post_type LIKE 'bizz_grid' OR post_type LIKE 'bizz_widget' AND post_content_filtered = '$themeid' ";
							$wpdb->query($query);
							// reset backed up widgets
							delete_option( $themeid . '_sidebars_widgets' );
							// update defaults option
							update_option('bizz_defaults_' . $themeid, true);
							// update widgets
							// bizz_update_widgets($new_sidebars_widgets, $new_all_widgets);
							// update posts
							bizzthemes_update_options( 'set_new', $new_all_widgets );
							bizzthemes_update_options( 'set_new', $new_sidebars_widgets );
							bizzthemes_insert_posts( 'set_new', array_merge($new_widget_posts, $new_grid_posts) );
							// redirect
							wp_redirect(admin_url('admin.php?page=bizz-tools&imported=true&type=Layouts')); #wp
						}
					}
					elseif ($new_options['options_id'] == 'settings') {
						if (version_compare($def_frame_version, $new_frame_version, '!='))
							wp_redirect(admin_url("admin.php?page=bizz-tools&type=Settings&error=version&tried=$new_frame_version&yours=$def_frame_version")); #wp
						else {
							// read options
							$new_options = $new_options['options_value'];
							// insert data
							update_option('bizzthemes_options', $new_options);
							// redirect
							wp_redirect(admin_url('admin.php?page=bizz-tools&imported=true&type=Settings')); #wp
						}
					}
					elseif ($new_options['options_id'] == 'design') {
						if (version_compare($def_frame_version, $new_frame_version, '!='))
							wp_redirect(admin_url("admin.php?page=bizz-tools&type=Design&error=version&tried=$new_frame_version&yours=$def_frame_version")); #wp
						elseif ($def_theme_id != $new_theme_id)
							wp_redirect(admin_url("admin.php?page=bizz-tools&type=Design&error=theme&tried=$new_theme_id&yours=$def_theme_id")); #wp
						else {
							// read options
							$new_options = $new_options['options_value'];
							// insert data
							update_option('bizzthemes_design', $new_options);
							// reset custom designs
							bizz_generate_css();
							// redirect
							wp_redirect(admin_url('admin.php?page=bizz-tools&imported=true&type=Design')); #wp
						}
					}
				}

			}
		}
		elseif (isset($_GET['download'])) {
			if ($_GET['download'] == 'layouts') {
				$widgets_array 			= bizz_get_active_widgets();
				$bizz_get_widget_posts 	= bizz_get_widget_posts();
				$bizz_get_grid_posts 	= bizz_get_grid_posts();
				$sidebars_widgets		= get_option('sidebars_widgets');
				check_admin_referer('bizzthemes-download-layouts'); #wp
				header( 'Content-Description: File Transfer' );
				header( 'Cache-Control: public, must-revalidate' );
				header( 'Pragma: hack' );
				header( 'Content-Type: text/plain' );
				header( 'Content-Disposition: attachment; filename="bizzthemes-layouts-' . date("Y-m-d") . '.json"' );
				$json_string = (json_encode(array(
					'theme_id' 			=> $themeid, 
					'frame_version' 	=> $frameversion, 
					'options_id'		=> 'layouts',
					'options_value' 	=> array(
						'all_widgets' 		=> $widgets_array,  
						'widget_posts'	 	=> $bizz_get_widget_posts,
						'grid_posts'		=> $bizz_get_grid_posts,
						'sidebars_widgets' 	=> array(
							'0'	=> array(
								'option_name' 	=> 'sidebars_widgets',
								'option_value' 	=> $sidebars_widgets,
								'type'		 	=> 'sidebars_widgets'
							)
						)
					)
				)));
				echo str_replace("'", "\\'", $json_string);
				exit();
			}
			elseif ($_GET['download'] == 'settings') {
				check_admin_referer('bizzthemes-download-settings'); #wp
				header( 'Content-Description: File Transfer' );
				header( 'Cache-Control: public, must-revalidate' );
				header( 'Pragma: hack' );
				header( 'Content-Type: text/plain' );
				header( 'Content-Disposition: attachment; filename="bizzthemes-settings-' . date("Y-m-d") . '.json"' );
				echo (json_encode(array(
					'theme_id' 			=> $themeid, 
					'frame_version' 	=> $frameversion,
					'options_id'		=> 'settings',
					'options_value' 	=> $opt
				)));
				exit();
			}
			elseif ($_GET['download'] == 'design') {
				check_admin_referer('bizzthemes-download-design'); #wp
				header( 'Content-Description: File Transfer' );
				header( 'Cache-Control: public, must-revalidate' );
				header( 'Pragma: hack' );
				header( 'Content-Type: text/plain' );
				header( 'Content-Disposition: attachment; filename="bizzthemes-design-' . date("Y-m-d") . '.json"' );
				echo (json_encode(array(
					'theme_id' 			=> $themeid, 
					'frame_version' 	=> $frameversion,
					'options_id'		=> 'design',
					'options_value' 	=> $optd
				)));
				exit();
			}
		}
		elseif (isset($_GET['restore'])) {
			if ($_GET['restore'] == 'layouts') {
				check_admin_referer('bizzthemes-restore-layouts'); #wp
				
				// fire the engine
				$default_action = 'set_defaults';
				bizzthemes_default_layouts( $default_action );
				
				wp_redirect(admin_url('admin.php?page=bizz-tools&restored=true&type=Layouts')); #wp
			}
			if ($_GET['restore'] == 'layouts-blank') {
				check_admin_referer('bizzthemes-restore-layouts'); #wp
				
				// fire the engine
				$default_action = 'reset';
				bizzthemes_default_layouts( $default_action );
				
				wp_redirect(admin_url('admin.php?page=bizz-tools&blank=true&type=Layouts')); #wp
			}
			elseif ($_GET['restore'] == 'settings') {
				check_admin_referer('bizzthemes-restore-settings'); #wp
				
				$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'bizzthemes_options' OR option_name LIKE '%pag_exclude%' OR option_name LIKE '%pst_exclude%' ";
				$wpdb->query($query);
		
				wp_redirect(admin_url('admin.php?page=bizz-tools&restored=true&type=Settings')); #wp
			}
			elseif ($_GET['restore'] == 'design') {
				check_admin_referer('bizzthemes-restore-design'); #wp
				
				$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'bizzthemes_design' ";
				$wpdb->query($query);
				bizz_generate_css();
				
				wp_redirect(admin_url('admin.php?page=bizz-tools&restored=true&type=Design')); #wp
			}
		}
	}
	
	static function status_check() {
		if (isset($_GET['error']) && $_GET['error'] == 'file') {
			echo "\t\t<div id=\"updated\" class=\"error\">\n";
			echo "\t\t\t<p>" . sprintf(__('<strong>Oh noez!</strong> There was an error with the file upload. Please try it again, or else download a new, valid %s Options file.', 'bizzthemes'), $_GET['type']) . "</p>\n";
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['error']) && $_GET['error'] == 'version') {
			global $bizzthemes_site;
			echo "\t\t<div id=\"updated\" class=\"error\">\n";
			echo "\t\t\t<p>" . sprintf(__('<strong>Whoa there!</strong> The %1$s you attempted to upload are from framework version <strong>%2$s</strong> and are not compatible with version <strong>%3$s.</strong>', 'bizzthemes'), $_GET['type'], $_GET['tried'], $_GET['yours']) . "</p>\n";
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['error']) && $_GET['error'] == 'theme') {
			global $bizzthemes_site;
			echo "\t\t<div id=\"updated\" class=\"error\">\n";
			echo "\t\t\t<p>" . sprintf(__('<strong>Whoa there!</strong> The %1$s you attempted to upload are from theme <strong>%2$s</strong> and are not compatible with theme <strong>%3$s.</strong>', 'bizzthemes'), $_GET['type'], $_GET['tried'], $_GET['yours']) . "</p>\n";
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['error']) && $_GET['error'] == 'wrongfile') {
			echo "\t\t<div id=\"updated\" class=\"error\">\n";
			echo "\t\t\t<p>" . sprintf(__('<strong>Whoops!</strong> The file you attempted to upload is not a valid %1$s file. Please try uploading the file again, or else download a new, valid %1$s Options file.', 'bizzthemes'), $_GET['type']) . "</p>\n";
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['blank']) && $_GET['blank']) {
			$options = ($_GET['type'] == 'All') ? 'All default bizzthemes options' : $_GET['type'] . '';
			echo "\t\t<div id=\"updated\" class=\"updated fade\">\n";
			echo "\t\t\t<p>" . sprintf(__('%1$s cleared! <a href="%2$s">Check out your site &rarr;</a>', 'bizzthemes'), $options, home_url()) . "</p>\n"; #wp
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['restored']) && $_GET['restored']) {
			$options = ($_GET['type'] == 'All') ? 'All default bizzthemes options' : 'Default ' . $_GET['type'] . '';
			echo "\t\t<div id=\"updated\" class=\"updated fade\">\n";
			echo "\t\t\t<p>" . sprintf(__('%1$s restored! <a href="%2$s">Check out your site &rarr;</a>', 'bizzthemes'), $options, home_url()) . "</p>\n"; #wp
			echo "\t\t</div>\n";
		}
		elseif (isset($_GET['imported']) && $_GET['imported']) {
			echo "\t\t<div id=\"updated\" class=\"updated fade\">\n";
			echo "\t\t\t<p>" . sprintf(__('%1$s imported! <a href="%2$s">Check out your site &rarr;</a>', 'bizzthemes'), $_GET['type'], home_url()) . "</p>\n"; #wp
			echo "\t\t</div>\n";
		}
	}

	static function bizzthemes_tools() {
		$rtl = (get_bloginfo('text_direction') == 'rtl') ? ' rtl' : ''; #wp
		echo "<div id=\"bizz_options\" class=\"wrap$rtl\">\n";
		
		// options header
		bizzthemes_options_header( $options_title = 'Tools', $toggle = false );
		
		echo "\t<h2>".__('Import &amp; Export Tools', 'bizzthemes')."</h2>\n";
		
		// check for errors
		bizz_custom_tools::status_check();		
?>	
	<p><?php _e('Howdy! Welcome to BizzThemes theme options exporter/importer tool.', 'bizzthemes'); ?></p>
	<p><?php _e('Once you have saved the .dat export file, you can use the Import options tool in another WordPress installation to import theme options.', 'bizzthemes'); ?></p>
	<p><?php printf( __('Notice: this tool does not export your site posts, pages, comments, custom fields, categories, and tags, use <a href="%s">WP Export</a> for that purpose.', 'bizzthemes'), 'export.php' ); ?></p>
	<div class="options_column">
		<h3><span><?php _e('Import options', 'bizzthemes'); ?></span></h3> 
		<div class="module_subsection">
			<h5><?php _e('Choose a file from your computer', 'bizzthemes'); ?></h5>
			<form method="post" enctype="multipart/form-data">
				<?php wp_nonce_field('bizzthemes-upload-all', '_wpnonce-bizzthemes-upload-all'); ?>
				<input type="hidden" name="upload" value="all" />
				<input type="file" class="text_input" name="file" id="all-options-file" />
				<p class="submit"><input type="submit" class="button-primary" value="Upload file and import" onclick="return confirm('<?php _e('Whoa there! Sure you want to override current options?', 'bizzthemes'); ?>');" /></p>
			</form>
		</div> 
	</div>
	<div class="options_column">
		<h3><span><?php _e('Export options', 'bizzthemes'); ?></span></h3> 
		<div class="module_subsection">
			<h5><?php _e('Builder', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;download=layouts'), 'bizzthemes-download-layouts'); ?>"><?php _e('Download layouts', 'bizzthemes'); ?></a></p>
		</div>
		<div class="module_subsection">
			<h5><?php _e('Framework', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;download=settings'), 'bizzthemes-download-settings'); ?>"><?php _e('Download settings', 'bizzthemes'); ?></a></p>
		</div>
		<div class="module_subsection">
			<h5><?php _e('Design', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;download=design'), 'bizzthemes-download-design'); ?>"><?php _e('Download design', 'bizzthemes'); ?></a></p>
		</div>
	</div>
	<div class="options_column last">
		<h3><span><?php _e('Set default options', 'bizzthemes'); ?></span></h3> 
		<div class="module_subsection">
			<h5><?php _e('Builder', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin">
			<a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;restore=layouts'), 'bizzthemes-restore-layouts'); ?>" onclick="return confirm('<?php _e('All current theme layouts will be lost! Click OK to reset.', 'bizzthemes'); ?>');"><?php _e('Set default layouts', 'bizzthemes'); ?></a>
			<a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;restore=layouts-blank'), 'bizzthemes-restore-layouts'); ?>" onclick="return confirm('<?php _e('All current theme layouts will be lost! Click OK to reset.', 'bizzthemes'); ?>');"><?php _e('Clear', 'bizzthemes'); ?></a>
			</p>
		</div>
		<div class="module_subsection">
			<h5><?php _e('Framework', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;restore=settings'), 'bizzthemes-restore-settings'); ?>" onclick="return confirm('<?php _e('All current theme settings will be lost! Click OK to reset.', 'bizzthemes'); ?>');"><?php _e('Set default settings', 'bizzthemes'); ?></a></p>
		</div>
		<div class="module_subsection">
			<h5><?php _e('Design', 'bizzthemes'); ?></h5>
			<p class="add_extra_margin"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;restore=design'), 'bizzthemes-restore-design'); ?>" onclick="return confirm('<?php _e('All current theme design options will be lost! Click OK to reset.', 'bizzthemes'); ?>');"><?php _e('Set default design', 'bizzthemes'); ?></a></p>
		</div>
	</div>
<?php		
		echo "</div>\n";
		echo "</div>\n";
	}
}

add_action('init', 'bizz_options_head', 11);
function bizz_options_head() {
	if (isset($_GET['page']) && $_GET['page'] == 'bizz-tools') {
		$manager = new bizz_custom_tools;
		$manager->manage_options();
	}
}

/* SET DEFAULT LAYOUTS or RESET */
/*------------------------------------------------------------------*/
function bizzthemes_default_layouts($default_action = '') {
    global $wpdb, $default_layouts_array, $themeid;
	      
    if ( isset($default_action) && $default_action == 'reset' ){ # RESET ALL
		// reset sidebars
		// update_option( 'sidebars_widgets', NULL );
		delete_option( $themeid . '_sidebars_widgets' );
		// updated defaults option
		delete_option('bizz_defaults_' . $themeid);
		// reset all option
		update_option('bizz_reset_' . $themeid, true);
		// delete pointer notice removal
		delete_user_setting( 'p_empty_widgets_' . str_replace('-','',$themeid) );
		
		// reset widgets & grids
		$query = "DELETE FROM $wpdb->posts WHERE post_type LIKE 'bizz_widget' OR post_type LIKE 'bizz_grid' AND post_content_filtered = '$themeid' ";
		$wpdb->query($query);
    }
    elseif ( isset($default_action) && $default_action == 'set_defaults' ){ # SET DEFAULT GRIDS and WIDGETS
		// read options
		$new_options 			= $default_layouts_array;
		// remove BOM
		$new_options = mb_convert_encoding($new_options, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
		if( substr($new_options, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF) ) {
			$new_options = substr($new_options, 3);
		}
		// decode
		$new_options 			= json_decode($new_options, true);
		$new_all_widgets 		= $new_options['options_value']['all_widgets'];
		$new_sidebars_widgets 	= $new_options['options_value']['sidebars_widgets'];
		// $new_sidebars_widgets 	= $new_options['options_value']['sidebars_widgets'][0]['option_value'];
		$new_widget_posts 		= ( isset($new_options['options_value']['widget_posts']) ) ? $new_options['options_value']['widget_posts'] : array();
		$new_grid_posts 		= ( isset($new_options['options_value']['grid_posts']) ) ? $new_options['options_value']['grid_posts'] : array();
		// reset old grids
		$query = "DELETE FROM $wpdb->posts WHERE post_type LIKE 'bizz_grid' OR post_type LIKE 'bizz_widget' AND post_content_filtered = '$themeid' ";
		$wpdb->query($query);
		// reset backed up widgets
		delete_option( $themeid . '_sidebars_widgets' );
		// update defaults option
		update_option('bizz_defaults_' . $themeid, true);
		// update widgets
		// bizz_update_widgets($new_sidebars_widgets, $new_all_widgets);
		// update posts
		bizzthemes_update_options( 'set_new', $new_all_widgets );
		bizzthemes_update_options( 'set_new', $new_sidebars_widgets );
		bizzthemes_insert_posts( 'set_new', array_merge($new_widget_posts, $new_grid_posts) );
		// update posts
		bizzthemes_insert_posts( 'set_new', array_merge($new_widget_posts, $new_grid_posts) );
							
    }
   
}

/* Insert default posts */
/*------------------------------------------------------------------*/
function bizzthemes_insert_posts($default_action = '', $default_array = '') {
    global $insert_post, $wpdb, $themeid;
		
	if ( !empty($default_array) )
		$insert_array = $default_array;
	else
		$insert_array = $insert_post;
	
	for( $i=0; $i<count($insert_array); $i++ ){
	    
		// check if title $my_post_name already exists in wpdb
		$my_post_name = $insert_array[$i]['post_title'];
		
		if($insert_array[$i]['type'] == 'posts' && $wpdb->get_row("SELECT post_title FROM wp_posts WHERE post_title = '" . $my_post_name . "'", 'ARRAY_A')) {
		    $my_post_exists = 'true'; 
		}
		else {
		    $my_post_exists = 'false'; 
		}
		
		// insert post
		if ( $insert_array[$i]['type'] != '' && $my_post_exists == 'false' ) {
			$post_content = $insert_array[$i]['post_content'];
			$post_content = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $post_content );
			$post_content_filtered = ( isset($insert_array[$i]['post_content_filtered']) ) ? $insert_array[$i]['post_content_filtered'] : $themeid;
			$post_parent = ( isset($insert_array[$i]['post_parent']) ) ? $insert_array[$i]['post_parent'] : 0;
			$post_widgetlogic = array(
			    'post_title'   			=> $insert_array[$i]['post_title'],
				'post_excerpt' 			=> $insert_array[$i]['post_excerpt'],
				'post_type'    			=> $insert_array[$i]['post_type'],
				'post_status'  			=> $insert_array[$i]['post_status'],
				'post_content_filtered' => $post_content_filtered,
				'post_content' 			=> $post_content,
				'post_parent' 			=> $post_parent
			);
			$post_id = wp_insert_post( $post_widgetlogic );
		}
		
	}

}

/* Update default options */
/*------------------------------------------------------------------*/
function bizz_update_widgets($sidebar_data = '', $widget_data = '') {

	foreach (order_sidebar_widgets($sidebar_data) as $sidebar_name=>$widget_list) {
		if (count($widget_list) == 0)
			continue;
			
		$sidebar_info = get_sidebar_info($sidebar_name);
		
		if ($sidebar_info) {
			foreach ($widget_list as $widget) {
				$widget_options = false;

				$widget_type = trim(substr($widget, 0, strrpos($widget, '-')));
				$widget_type_index = trim(substr($widget, strrpos($widget, '-') + 1));
				$option_name = 'widget_'.$widget_type;
				$widget_type_options = get_option_from_array($widget_type, $sidebar_data);
				if ($widget_type_options){
					$widget_title = isset($widget_type_options[$widget_type_index]['title']) ? $widget_type_options[$widget_type_index]['title'] : '';
					$widget_options = $widget_type_options[$widget_type_index];
				}
				$widgets[$widget_type] = $widget_type_index;
			}		
		}
	}

	foreach($sidebar_data as $title => $sidebar){
		$count = count($sidebar);
		for($i=0;$i<$count;$i++){
			$widget = array();
			$widget['type'] = trim(substr($sidebar[$i], 0, strrpos($sidebar[$i], '-')));
			$widget['type-index'] = trim(substr($sidebar[$i], strrpos($sidebar[$i], '-') + 1));
			if(!isset($widgets[$widget['type']][$widget['type-index']])){
				unset($sidebar_data[$title][$i]);
			}
		}
		$sidebar_data[$title] = $sidebar;
	}

	foreach($widgets as $widget_title=>$widget_value){
		$widgets[$widget_title][$widget_value] = $widget_data[$widget_title][$widget_value];
	}

	$sidebar_data = array_filter($sidebar_data);
	$new_array = array($sidebar_data, $widgets);
	$new_array = parse_import_data($new_array);
}

/* Update default options */
/*------------------------------------------------------------------*/
function bizzthemes_update_options($default_action = '', $default_array = '') {
    global $update_option;
	
	$options_array = ( isset($default_array) && $default_array != '' ) ? $default_array : $update_option;
			
	for( $i=0; $i<count($options_array); $i++ ) {
	    if ( isset($options_array[$i]['type']) && $options_array[$i]['type'] != '' ) {
		    $option_value = $options_array[$i]['option_value'];
			if ( !is_serialized( $option_value ) )
				$option_value = serialize($option_value);
			$option_value = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $option_value );
			$option_value = maybe_unserialize($option_value);
			$option_name  = $options_array[$i]['option_name'];
			update_option( $option_name, $option_value );
		}
	}

}

// LIST ALL ACTIVE WIDGETS
function bizz_get_active_widgets() {
	global $wp_registered_widgets;
	
	$avail_widgets = '';
	foreach ( $wp_registered_widgets as $widget ) { #get registered widgets
		$option_name 	= $widget['callback']['0'];
		$avail_widgets .= $option_name->option_name . ',';
	}
	$avail_widgets = substr_replace($avail_widgets ,"",-1);
	$avail_widgets = explode(",",$avail_widgets);
	$avail_widgets = array_unique($avail_widgets);
	foreach ( $avail_widgets as $widget ) { #put widgets into array
		$widgets_array[] = array(
			'option_name' 	=> $widget, 
			'option_value'	=> get_option( $widget ),
			'type'			=> 'widget'
		);
	}
	
	return $widgets_array;
}

// LIST ALL WIDGET POSTS
function bizz_get_widget_posts() {
	global $post, $themeid, $wp_version;
	
	$args = array(
		'post_type' 	=> 'bizz_widget',
		'numberposts' 	=> -1,
		'orderby' 		=> 'date',
		'order' 		=> 'DESC',
		'post_status' 	=> 'publish'
	);
	$layout_widgets = get_posts( $args );
	foreach ( $layout_widgets as $post ) {
		setup_postdata($post);
		
		if (version_compare($wp_version, '3.0.9', '>=')) {
			$post_content = esc_textarea($post->post_content);
			$post_content = str_replace('&quot;', '"', $post_content);
		}
		else
			$post_content = $post->post_content;
		
		$widget_posts_array[] = array(
			'post_title' 				=> $post->post_title, 
			'post_excerpt' 				=> $post->post_excerpt,
			'post_status' 				=> $post->post_status,
			'post_type' 				=> $post->post_type,
			'post_content' 				=> $post_content,
			'post_content_filtered' 	=> $themeid,
			'type'						=> 'widgets'
		);
	}
	
	return $widget_posts_array;
}

// LIST ALL GRID POSTS
function bizz_get_grid_posts() {
	global $post, $themeid;
	
	$args = array(
		'post_type' 	=> 'bizz_grid',
		'numberposts' 	=> -1,
		'orderby' 		=> 'date',
		'order' 		=> 'DESC',
		'post_status' 	=> 'publish'
	);
	$layout_grids = get_posts( $args );
	foreach ( $layout_grids as $post ) {
		setup_postdata($post);
		
		$grid_posts_array[] = array(
			'post_title' 				=> $post->post_title, 
			'post_excerpt' 				=> $post->post_excerpt,
			'post_status' 				=> $post->post_status,
			'post_type' 				=> $post->post_type,
			'post_content' 				=> $post->post_content,
			'post_content_filtered' 	=> $themeid,
			'type'						=> 'grids'
		);
	}
	if ( isset($grid_posts_array) )
		return $grid_posts_array;
}

// CLEAN DATA, EVEN ARRAYS: part 1
function bizz_sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val)
            $output[$var] = bizz_sanitize($val);
    }
    else {
        if (get_magic_quotes_gpc())
            $input = stripslashes($input);
        $output = htmlentities($input, ENT_QUOTES, "UTF-8");
    }
	if ( isset($output) )
		return $output;
}

function parse_import_data($import_array){
	$sidebars_data = $import_array[0];
	$widget_data = $import_array[1];	
	$current_sidebars = get_option('sidebars_widgets');
	$new_widgets = array();

	foreach($sidebars_data as $import_sidebar => $import_widgets){
		
		if ( !is_array($import_widgets) )
			continue;		
		
		foreach ($import_widgets as $import_widget){
			//if the sidebar exists
			if (isset($current_sidebars[$import_sidebar])){
				$title = trim(substr($import_widget, 0, strrpos($import_widget, '-')));
				$index = trim(substr($import_widget, strrpos($import_widget, '-') + 1));
				$current_widget_data = get_option('widget_'.$title);
				$new_widget_name = get_new_widget_name($title, $index);
				$new_index = trim(substr($new_widget_name, strrpos($new_widget_name, '-') + 1));

				if(is_array($new_widgets[$title])) {
					while(array_key_exists($new_index, $new_widgets[$title]))
						$new_index++;
				}
				$current_sidebars[$import_sidebar][] = $title.'-'.$new_index;
				if(array_key_exists($title, $new_widgets)){
					$new_widgets[$title][$new_index] = $widget_data[$title][$index];
					$multiwidget = $new_widgets[$title]['_multiwidget'];
					unset($new_widgets[$title]['_multiwidget']);
					$new_widgets[$title]['_multiwidget'] = $multiwidget;
				} 
				else {
					$current_widget_data[$new_index] = $widget_data[$title][$index];
					$current_multiwidget = $current_widget_data['_multiwidget'];
					$new_multiwidget = $widget_data[$title]['_multiwidget'];
					$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
					unset($current_widget_data['_multiwidget']);
					$current_widget_data['_multiwidget'] = $multiwidget;
					$new_widgets[$title] = $current_widget_data;
				}

				//Going to use for future functionality
			//if the sidebar does not exist, put the widget in the in-active
//				else :
//
//					if(isset($sidebars_data['wp_inactive_widgets'])){ //if wp_inactive_widgets is set on the import
//						foreach($sidebars_data[$import_sidebar] as $widget){ // just append all that sidebars widgets to the array
//							$sidebars_data['wp_inactive_widets'][] = $widget;
//					}
//					} else { // if the wp_inactive_widets is not defined
//						$sidebars_data['wp_inactive_widgets'] = $sidebars_data[$import_sidebar]; // just set the old array as the wp_inactive_widgets array
//					}
//					unset($sidebars_data[$import_sidebar]);  // remove old sidebar array in the import data

			}
		}
	}

	if(isset($new_widgets) && isset($current_sidebars)){
		var_dump($current_sidebars);
		echo '<br/>+++++<br/>';
		// update_option('sidebars_widgets', $current_sidebars);
		foreach($new_widgets as $title => $content){
			print_r('widget_'.$title);
			echo '<br/>';
			print_r($content);
			echo '<br/>------<br/>';
			// update_option('widget_'.$title, $content);
		}

		return true;
	} 
	else
		return false;

}

function get_new_widget_name($widget_name, $widget_index){
	$current_sidebars = get_option('sidebars_widgets');
	$all_widget_array = array();
	foreach($current_sidebars as $sidebar=>$widgets){
		if(!empty($widgets) && is_array($widgets) && $sidebar != 'wp_inactive_widgets') {
			foreach($widgets as $w)
				$all_widget_array[] = $w;
		}
	}
	while(in_array($widget_name.'-'.$widget_index, $all_widget_array))
		$widget_index++;

	$new_widget_name = $widget_name.'-'.$widget_index;
	return $new_widget_name;
}

function get_option_from_array($option_name, $array_options) {
	foreach ($array_options as $name=>$option){
		if ($name == $option_name)
			return $option;
	}

	return false;
}

function get_sidebar_info($sidebar_id) {
	global $wp_registered_sidebars;

	if ($sidebar_id == 'wp_inactive_widgets') #since wp_inactive_widget is only used in widgets.php
		return array('name'=>'Inactive Widgets', 'id'=>'wp_inactive_widgets');

	foreach ($wp_registered_sidebars as $sidebar){
		if (isset ($sidebar['id']) && $sidebar['id'] == $sidebar_id)
			return $sidebar;
	}

	return false;
}

function get_widget_info($widget){
	global $wp_registered_widgets;
	if (isset($wp_registered_widgets[$widget]))
		return true;
	else
		return false;
}

function order_sidebar_widgets($sidebar_widgets) {
	$inactive_widgets = false;

	if (isset($sidebar_widgets['wp_inactive_widgets'])){ #seperate inactive widget sidebar from other sidebars so it can be moved to the end of the array, if it exists
		$inactive_widgets = $sidebar_widgets['wp_inactive_widgets'];
		unset($sidebar_widgets['wp_inactive_widgets']);
		$sidebar_widgets['wp_inactive_widgets'] = $inactive_widgets;
	}

	return $sidebar_widgets;
}
