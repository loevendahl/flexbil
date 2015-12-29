<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ADMIN MENUS
 *
 * output admin menus
 * @since 7.0
 */
function bizzthemes_add_admin() {
    global $menu, $wpdb, $themename;
	
	// Re-define theme name
	if ( isset($GLOBALS['opt']['bizzthemes_branding_back_name']['value']) && $GLOBALS['opt']['bizzthemes_branding_back_name']['value'] != '' )
		$themename = $GLOBALS['opt']['bizzthemes_branding_back_name']['value']; #custom
	else
		$themename = $themename; #default
	
	// SHOW layout engine?
	$adminmenu_layout 	= ( isset($GLOBALS['opt']['bizzthemes_adminmenu_layout']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_layout']['value'] : '' ;
	$menu_function 		= ( $adminmenu_layout != 'true' ) ? 'bizz-layout' : 'bizzthemes' ;
	$menu_options 		= ( $adminmenu_layout != 'true' ) ? 'bizzthemes_layout' : 'bizzthemes_options' ;
	
	// REDIRECT on activation
	if ( isset($_GET['page']) && $_GET['page'] == 'bizz-layout' || isset($_GET['activated']) ) {
		if ( isset($_GET['activated']) ) {
			bizz_maybe_set_defaults( true );
			wp_redirect(admin_url("admin.php?page=$menu_function"));
			// header("Location: admin.php?page=$menu_function");
		}
    }
	
	// RESET options
	if( isset($_REQUEST['bizz_save']) && $_REQUEST['bizz_save'] == 'reset' && $_GET['page'] == 'bizzthemes' ) { #theme options
		$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'bizzthemes_options' OR option_name LIKE '%pag_exclude%' OR option_name LIKE '%pst_exclude%' ";
		$wpdb->query($query);
		wp_redirect(admin_url("admin.php?page=bizzthemes&reset=true"));
		// header("Location: admin.php?page=bizzthemes&reset=true");
		die;
	} 
	elseif( isset($_REQUEST['bizz_save']) && $_REQUEST['bizz_save'] == 'reset' && $_GET['page'] == 'bizz-design' ) { #theme design
		$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'bizzthemes_design' ";
		$wpdb->query($query);
		wp_redirect(admin_url("admin.php?page=bizz-design&reset=true"));
		// header("Location: admin.php?page=bizz-design&reset=true");
		die;
	}
	
	// Initiate main theme options menu
	add_menu_page('Theme Options', $themename, 'edit_theme_options', $menu_function, $menu_options, BIZZ_FRAME_IMAGES . '/bizzthemes-32.png', 31);
	// add_theme_page( 'Theme Options', $themename, 'edit_theme_options', $menu_function, $menu_options, $themeicon, 31 );
	
}
add_action('admin_menu', 'bizzthemes_add_admin');

/**
 * ADMIN SUBMENUS SEPARATOR
 *
 * output admin options panel separator
 * @since 7.6.4
 */
function bizzthemes_add_menu_sep() {
    global $menu;
	
	if (version_compare(get_bloginfo('version'), '2.9', '>='))
		$menu[30] = array('', 'read', 'separator-bizzthemes', '', 'wp-menu-separator');
	
}
add_action('init', 'bizzthemes_add_menu_sep');


/**
 * ADMIN SUBMENUS
 *
 * output admin submenus
 * @since 7.0
 */
function bizzthemes_add_submenus() {
	global $themeid, $themename, $frameversion, $builder_hook, $frame_hook, $design_hook, $editor_hook, $license_hook, $tools_hook, $update_hook;
	
	// Re-define theme name
	if ( isset($GLOBALS['opt']['bizzthemes_branding_back_name']['value']) && $GLOBALS['opt']['bizzthemes_branding_back_name']['value'] != '' )
		$themename = $GLOBALS['opt']['bizzthemes_branding_back_name']['value']; #custom
	else
		$themename = $themename; #default
	
	$theme_api = bizzthemes_license_api();
	$pending_license = (!isset($theme_api) || $theme_api['valid'] != 'true') ? '&#33;' : '' ;
	
	// theme version
	if ( ( function_exists('wp_get_theme') ) ) {
		$this_theme_data = wp_get_theme();
		$this_theme_version = $this_theme_data->Version;
	}
	else {
		$this_theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
		$this_theme_version = $this_theme_data['Version'];
	}
	$remote_theme_version = get_transient('remote_t_version_' . $themeid);
	$this_theme_version = trim(str_replace('.','',$this_theme_version));
	$remote_theme_version = trim(str_replace('.','',$remote_theme_version));
	if(strlen($this_theme_version) == 2) { $this_theme_version = $this_theme_version . '0'; }
	if(strlen($remote_theme_version) == 2) { $remote_theme_version = $remote_theme_version . '0'; }
	
	// framework version
	$localversion = $frameversion;
	$remoteversion = get_transient('remote_f_version_' . $themeid);
	$fw_remote_changelog = 'http://www.bizzthemes.com/framework/changelog.txt';
	$localversion = trim(str_replace('.','',$localversion));
	$remoteversion = trim(str_replace('.','',$remoteversion));
	if(strlen($localversion) == 2){$localversion = $localversion . '0'; }
	if(strlen($remoteversion) == 2){$remoteversion = $remoteversion . '0'; }
	
	$pending_update = ( ($this_theme_version < $remote_theme_version || $localversion < $remoteversion) && $remote_theme_version != 'Currently Unavailable' ) ? '1' : '' ;
	
	$adminmenu_layout = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_layout']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_layout']['value'] : '' ;
	$adminmenu_design = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_design']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_design']['value'] : '' ;
	$adminmenu_license = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_license']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_license']['value'] : '' ;
	$adminmenu_editor = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_editor']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_editor']['value'] : '' ;
	$adminmenu_version = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_version']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_version']['value'] : '' ;
	$adminmenu_tools = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_tools']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_tools']['value'] : '' ;
	
	$menu_function = ( $adminmenu_layout != 'true' ) ? 'bizz-layout' : 'bizzthemes' ;
	
	if ( $adminmenu_layout != 'true' )
		$builder_hook = add_submenu_page($menu_function, $themename, __('Builder', 'bizzthemes'), 'manage_options', 'bizz-layout','bizzthemes_layout');
		
	$frame_hook = add_submenu_page($menu_function, $themename, __('Framework', 'bizzthemes'), 'manage_options', 'bizzthemes','bizzthemes_options');
	
	if ( $adminmenu_design != 'true' )
	    $design_hook = add_submenu_page($menu_function, $themename, __('Design', 'bizzthemes'), 'manage_options', 'bizz-design','bizzthemes_options');
		
	if ( $adminmenu_editor != 'true' )
	    $editor_hook = add_submenu_page($menu_function, $themename, __('Editor', 'bizzthemes'), 'manage_options', 'bizz-editor', array('bizz_custom_editor', 'bizzthemes_editor'));
	
	if ( $adminmenu_license != 'true' && ( isset($_GET['page']) && $_GET['page'] == 'bizz-license' || $pending_license != '' ) )
	    $license_hook = add_submenu_page($menu_function, $themename, sprintf(__('License<span id="awaiting-mod" class="update-plugins"><span class="pending-count update-count">%s</span></span>', 'bizzthemes'), $pending_license), 'manage_options', 'bizz-license','bizzthemes_license_page');
	
	if ( $adminmenu_tools != 'true' && isset($_GET['page']) && $_GET['page'] == 'bizz-tools' )
	    $tools_hook = add_submenu_page($menu_function, $themename, __('Backup', 'bizzthemes'), 'manage_options', 'bizz-tools', array('bizz_custom_tools', 'bizzthemes_tools'));
	
	if ( $adminmenu_version != 'true' && ( isset($_GET['page']) && $_GET['page'] == 'bizz-update' || $pending_update != '' ) && ! is_child_theme() )
	    $update_hook = add_submenu_page($menu_function, $themename, sprintf(__('Update<span id="awaiting-mod" class="update-plugins"><span class="pending-count update-count">%s</span></span>', 'bizzthemes'), $pending_update), 'manage_options', 'bizz-update','bizzthemes_framework_update_page');

}
add_action('admin_menu', 'bizzthemes_add_submenus');


/**
 * ADMIN OPTIONS
 *
 * output admin options
 * @since 7.0
 */
function bizzthemes_options() {
	global $options, $design, $frame;
	
	// declare options variables
	if ( $_GET['page'] == 'bizzthemes' ){
	    $options = $options;	
		$bizz_page = __('Framework Settings', 'bizzthemes');
	} 
	elseif ( $_GET['page'] == 'bizz-design' ){
	    $options = $design;
		$bizz_page = __('Design Options', 'bizzthemes');
	}
	
	// options header
	bizzthemes_options_header( $bizz_page, $toggle = true );

	foreach ( $options as $value ) {
	
		if ( isset( $value['type'] ) ) {
		
		switch ( $value['type'] ) {
		
			case 'text':
				option_wrapper_header($value);
				
				$std = $value['std']['value'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<input class="text_input" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value" type="<?php echo $value['type']; ?>" value="<?php echo stripslashes(stripslashes($val)); ?>" />
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'select':
				option_wrapper_header($value);
				
				$std = $value['std']['value'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<select class="select_input" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value">
<?php 
							if (isset($value['show_option_none']) && $value['show_option_none'] == true)
								echo "<option value=''>".__('-- None --', 'bizzthemes')."</option>\n";

							foreach ($value['options'] as $option) {
								if($val == $option){ $selected = 'selected="selected"'; } else { $selected = ''; }
								echo "<option ".$selected." value=\"" . $option . "\">" . $option . "</option>\n";
							}
?>
					</select>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'select_by_id':
				option_wrapper_header($value);
				
				$std = $value['std']['value'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<select class="select_input" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value">
<?php 
							if ($value['show_option_none'] == true) 
								echo "<option value=''>".__('-- None --', 'bizzthemes')."</option>\n";
							elseif ($value['show_option_all'] == true)
								echo "<option value=''>".__('-- All --', 'bizzthemes')."</option>\n";
							
							foreach ($value['options'] as $key=>$option) {
								if($val == $option){ $selected = 'selected="selected"'; } else { $selected = ''; }
								echo "<option ".$selected." value=\"" . $option . "\">" . $key . "</option>\n";
							}
?>
					</select>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'menu_select':
				option_wrapper_header($value);
							
				$nav_menus = wp_get_nav_menus();	
				$nav_menu_selected_id = $nav_menus[0]->term_id;
							
				$std = $value['std']['value'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<select class="select_input" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value">
<?php 
						foreach( (array) $nav_menus as $key => $_nav_menu ) :
						
						$_nav_menu->truncated_name = trim( wp_html_excerpt( $_nav_menu->name, 40 ) );
						if ( $_nav_menu->truncated_name != $_nav_menu->name )
							$_nav_menu->truncated_name .= '&hellip;';
						$nav_menus[$key]->truncated_name = $_nav_menu->truncated_name; 
						if($val == esc_attr($_nav_menu->term_id)){ $selected = 'selected="selected"'; } else { $selected = ''; }
?>
								<option value="<?php echo esc_attr($_nav_menu->term_id); ?>" <?php echo $selected; ?>>
									<?php echo esc_html( $_nav_menu->truncated_name ); ?>
								</option>
<?php 
						endforeach; 
?>
					</select>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'upload':
				option_wrapper_header($value);
					
				$id = $value['id'];
				$std = $value['std']['value'];	
				$uploader = '';		
				$val = '';
				
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<div id="upload-wrap">
						<div class="upload_button" id="<?php echo $id; ?>"><?php _e('Choose File', 'bizzthemes'); ?></div>
						<input class="upload_text_input" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value" type="text" value="<?php echo $val; ?>" />
<?php 
						if (!empty($val)) { 
?>
							<a class="img-preview" href="<?php echo $val; ?>">
							<img id="image_<?php echo $id; ?>" src="<?php echo $val; ?>" width="10" height="10" title="Image Preview" alt="Image Preview" />
							</a>
<?php 
						} 
?>
						<div class="clear"><!----></div>
					</div>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'textarea':
				option_wrapper_header($value);
				
				if (isset($value['wysiwyg']) && $value['wysiwyg'] <> ''){ $wysiwyg=$value['wysiwyg']; } else { $wysiwyg=$value['id']; }
				if (isset($value['cols']) && $value['cols'] <> ''){ $cols=$value['cols']; } else { $cols='50'; }
				if (isset($value['rows']) && $value['rows'] <> ''){ $rows=$value['rows']; } else { $rows='8'; }
				
				$std = $value['std']['value'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<textarea name="<?php echo $value['id']; ?>[value]" class="<?php echo $wysiwyg; ?>" id="<?php echo $wysiwyg; ?>" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>"><?php echo stripslashes($val); ?></textarea>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;

			case "radio":
				option_wrapper_header($value);
				
				$std = $value['std']['value'];	
				$sav = ( isset($GLOBALS['opt'][$value['id']]['value']) ) ? $GLOBALS['opt'][$value['id']]['value'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['value']) ) ? $GLOBALS['optd'][$value['id']]['value'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
				
					$counting = 0;
					foreach ($value['options'] as $key=>$option) { 
					
						$counting++;
						$checked = '';
						if($val == $key){ $checked = ' checked'; } else { $checked = ''; }
?>
					<input class="input_checkbox" type="radio" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']['value'].'_'.$counting; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />&nbsp;
					<label for="<?php echo $value['id']['value'].'_'.$counting; ?>"><?php echo $option; ?></label><br />
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php 
					}
				option_wrapper_footer($value);
			break;
			
			case "checkbox":
				option_wrapper_header($value);
										
				if( 
				(isset($GLOBALS['opt'][$value['id']]['value']) && $GLOBALS['opt'][$value['id']]['value']) || 
				(isset($GLOBALS['optd'][$value['id']]['value']) && $GLOBALS['optd'][$value['id']]['value']) || 
				(isset($value['std']['value']) && $value['std']['value'] && isset($GLOBALS['opt'][$value['id']]['value'])) 
				)
					$val = 'true';
				else
					$val = '';
				
				$checked = '';
				$checked = ($val == 'true') ? ' checked' : '' ;
				$disabled = ( isset($value['disabled']) ) ? $value['disabled'] : '' ;
?>
					<input <?php echo $disabled; ?> class="input_checkbox" type="checkbox" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value" value="true" <?php echo $checked; ?> />
					<label for="<?php echo $value['id']; ?>_value"><?php echo $value['label']; ?></label><br />
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case "checkbox2": 
				option_wrapper_header($value);
				
					if( isset($GLOBALS['opt'][$value['id']]['value']) ){
						$checked = "checked=\"checked\"";
					} elseif ( isset($GLOBALS['optd'][$value['id']]['value']) ){
						$checked = "checked=\"checked\"";
					} else {
						$checked = "";
					}
					$disabled = ( isset($value['disabled']) ) ? $value['disabled'] : '' ;
?>
					<input <?php echo $disabled; ?> class="input_checkbox" type="checkbox" name="<?php echo $value['id']; ?>[value]" id="<?php echo $value['id']; ?>_value" value="true" <?php echo $checked; ?> />&nbsp;
					<label for="<?php echo $value['id']; ?>_value"><?php echo $value['label']; ?></label><br />
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
							
			case "multisort":
					$vid = $value['id']['value'];
					
					if ( $GLOBALS['opt'][$value['id']]['value'] <> '' ) {
					
						$array1 = $GLOBALS['opt'][$value['id']]['value'];
						$array2 = $value['options'];
						
						$sort_array1 = array_intersect_key($array1, $array2);
						$sort_array2 = array_diff_key($array1, $array2);
						
						$count_a1 = count($sort_array1); // count same array keys
						$count_a2 = count($array2); // count std arrays
						
						if ( $count_a1==$count_a2 ){
							$sort_array = $sort_array1;
							$opto = 'false';
						} else {
							$sort_array = $array2;
							$opto = 'true';
						}
					
					} else {
					
						$sort_array = $value['options'];
						$opto = 'true';
						
					}

					foreach ($sort_array as $key=>$value) { 
						$pn_key = $vid . '_' . $key;
						$chk_std = $value['show'];
						
						$chk_sav = ( isset($GLOBALS['opt'][$vid . '_' . $key]) ) ? true : '' ;
						$chk_sav2 = ( isset($GLOBALS['opt'][$vid . '_' . $key]) ) ? true : '' ;

						$checked = '';
						if(!empty($chk_sav)) {
							if($chk_sav == 'true') { $checked = "checked=\"checked\""; } else { $checked = ''; }
						} elseif(!empty($chk_sav2)) {
							if($chk_sav == 'true') { $checked = "checked=\"checked\""; } else { $checked = ''; }
						} elseif ( $chk_std == 'true') {
							$checked = "checked=\"checked\"";
						} else {
							$checked = '';
						}
						$opt_name = $value; // get option full name
						if ($opto == 'true') { $opt_name = $value['name']; } else { $opt_name = $value; } // get option full name
?>
					<div class="list_item">
					<input class="input_checkbox" type="checkbox" name="<?php echo $pn_key; ?>" id="<?php echo $pn_key; ?>" value="true" <?php echo $checked; ?> />&nbsp;
					<label for="<?php echo $pn_key; ?>"><?php echo $opt_name; ?>&nbsp;&nbsp;<small style='color:#aaaaaa'>id=<?php echo $key; ?></small></label><br />
					<input type="hidden" name="<?php echo $vid; ?>[<?php echo $key; ?>]" value="<?php echo $opt_name; ?>" />
					</div>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php 
					} // end foreach
			break;
			
			case "typography":
				option_wrapper_header($value);
			
				// font-family
				$font_stacks = bizz_get_fonts();
				$std = $value['std']['font-family'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['font-family']) ) ? $GLOBALS['opt'][$value['id']]['font-family'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['font-family']) ) ? $GLOBALS['optd'][$value['id']]['font-family'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q6" name="<?php echo $value['id']; ?>[font-family]" id="<?php echo $value['id']; ?>_font-family">
<?php
							$selected = (!$std) ? ' selected="selected"' : '';
							echo "<option$selected value=\"\">Inherit</option>\n";
							
							foreach ($font_stacks as $font_key => $font) {
								if($val == $font_key){ $selected = 'selected="selected"'; } else { $selected = ''; }
								$web_safe = ($font['web_safe']) ? ' *' : '';
								$goog_font = ($font['google']) ? ' <small>G</small>' : '';
								echo "<option ".$selected." value=\"" . $font_key . "\">" . $font['name'] . $web_safe .''. $goog_font . "</option>\n";	
							}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// font-weight
				$options_weight = array("normal","bold","bolder","lighter","100","200","300","400","500","600","700","800","900");
				$std = $value['std']['font-weight'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['font-weight']) ) ? $GLOBALS['opt'][$value['id']]['font-weight'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['font-weight']) ) ? $GLOBALS['optd'][$value['id']]['font-weight'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q7" name="<?php echo $value['id']; ?>[font-weight]" id="<?php echo $value['id']; ?>_font-weight">
<?php
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- weight --</option>\n";
					
					foreach ($options_weight as $weight) {
						if($val == $weight){ $selected = 'selected="selected"'; } else { $selected = ''; }
						echo "<option ".$selected." value=\"" . $weight . "\">" . $weight . "</option>\n";	
					}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// font-size
				$std = $value['std']['font-size'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['font-size']) ) ? $GLOBALS['opt'][$value['id']]['font-size'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['font-size']) ) ? $GLOBALS['optd'][$value['id']]['font-size'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q3" name="<?php echo $value['id']; ?>[font-size]" id="<?php echo $value['id']; ?>_font-size">
<?php 						
							$selected = (!$std) ? ' selected="selected"' : '';
							echo "<option$selected value=\"\">-- size --</option>\n";
							
							for ($i = 7; $i < 71; $i++){
								($val == $i) ? $selected = ' selected="selected"' : $selected = '';
								echo "<option$selected value=\"" . $i . "px\">" . $i . "px</option>\n";
							} 
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
				<span class="opt-spacing"><!----></span>
<?php
				// font-variant
				$options_variant = array("normal","small-caps");
				$std = $value['std']['font-variant'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['font-variant']) ) ? $GLOBALS['opt'][$value['id']]['font-variant'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['font-variant']) ) ? $GLOBALS['optd'][$value['id']]['font-variant'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q2" name="<?php echo $value['id']; ?>[font-variant]" id="<?php echo $value['id']; ?>_font-variant">
<?php
							$selected = (!$std) ? ' selected="selected"' : '';
							echo "<option$selected value=\"\">-- variant --</option>\n";
							
							foreach ($options_variant as $variant) {
								if($val == $variant){ $selected = 'selected="selected"'; } else { $selected = ''; }
								echo "<option ".$selected." value=\"" . $variant . "\">" . $variant . "</option>\n";	
							}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// font-style
				$options_style = array("normal","italic","oblique");
				$std = $value['std']['font-style'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['font-style']) ) ? $GLOBALS['opt'][$value['id']]['font-style'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['font-style']) ) ? $GLOBALS['optd'][$value['id']]['font-style'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q3" name="<?php echo $value['id']; ?>[font-style]" id="<?php echo $value['id']; ?>_font-style">
<?php
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- style --</option>\n";
					
					foreach ($options_style as $style) {
						if($val == $style){ $selected = 'selected="selected"'; } else { $selected = ''; }
						echo "<option ".$selected." value=\"" . $style . "\">" . $style . "</option>\n";	
					}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// font-color
				$std = $value['std']['color'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['color']) ) ? $GLOBALS['opt'][$value['id']]['color'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['color']) ) ? $GLOBALS['optd'][$value['id']]['color'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<input class="text_q color {hash:true,caps:false,required:false}" type="text" name="<?php echo $value['id']; ?>[color]" id="<?php echo $value['id']; ?>" value="<?php echo $val; ?>" />
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case "border":
				option_wrapper_header($value);

				// border-color
				$position = $value['std']['border-position'];
				$std = $value['std']['border-color'];
				$sav = ( isset($GLOBALS['opt'][$value['id']][$position.'-color']) ) ? $GLOBALS['opt'][$value['id']][$position.'-color'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']][$position.'-color']) ) ? $GLOBALS['optd'][$value['id']][$position.'-color'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<input class="text_q q8 color {hash:true,caps:false,required:false}" name="<?php echo $value['id']; ?>[<?php echo $position; ?>-color]" id="<?php echo $value['id']; ?>_color" type="text" value="<?php echo $val; ?>" />
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// border-width
				$position = $value['std']['border-position'];
				$std = $value['std']['border-width'];
				$sav = ( isset($GLOBALS['opt'][$value['id']][$position.'-width']) ) ? $GLOBALS['opt'][$value['id']][$position.'-width'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']][$position.'-width']) ) ? $GLOBALS['optd'][$value['id']][$position.'-width'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q3" name="<?php echo $value['id']; ?>[<?php echo $position; ?>-width]" id="<?php echo $value['id']; ?>_border-width">
<?php 						
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- width --</option>\n";
					
					for ($i = 0; $i < 30; $i++){
						($val == $i && $val != '') ? $selected = ' selected="selected"' : $selected = '';
						echo "<option$selected value=\"" . $i . "px\">" . $i . "px</option>\n";
					} 
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// border-style
				$position = $value['std']['border-position'];
				$options_style = array("solid","dashed","dotted","double","groove","ridge","inset","outset");
				$std = $value['std']['border-style'];
				$sav = ( isset($GLOBALS['opt'][$value['id']][$position.'-style']) ) ? $GLOBALS['opt'][$value['id']][$position.'-style'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']][$position.'-style']) ) ? $GLOBALS['optd'][$value['id']][$position.'-style'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q9" name="<?php echo $value['id']; ?>[<?php echo $position; ?>-style]" id="<?php echo $value['id']; ?>-style">
<?php 
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- style --</option>\n";
					
					foreach ($options_style as $style) {
						if($val == $style){ $selected = 'selected="selected"'; } else { $selected = ''; }
						echo "<option ".$selected." value=\"" . $style . "\">" . $style . "</option>\n";	
					}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case "bgproperties":
				option_wrapper_header($value);
				
				// bg-image	
				$id = $value['id'];
				$std = $value['std']['background-image'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['background-image']) ) ? $GLOBALS['opt'][$value['id']]['background-image'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['background-image']) ) ? $GLOBALS['optd'][$value['id']]['background-image'] : '' ;
				
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
					<div id="upload-wrap">
						<div class="upload_button" id="<?php echo $id; ?>"><?php _e('Choose File', 'bizzthemes'); ?></div>
						<input class="upload_text_input" name="<?php echo $value['id']; ?>[background-image]" id="<?php echo $value['id']; ?>_value" type="text" value="<?php echo $val; ?>" />
						<?php if (!empty($val)) { ?>
							<a class="img-preview" href="<?php echo $val; ?>">
							<img id="image_<?php echo $id; ?>" src="<?php echo $val; ?>" width="10" height="10" title="Image Preview" alt="Image Preview" />
							</a>
						<?php } ?>
						<div class="clear"><!----></div>
					</div>
					<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
					
				<span class="opt-spacing"><!----></span>
<?php
				// bg-color
				$std = $value['std']['background-color'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['background-color']) ) ? $GLOBALS['opt'][$value['id']]['background-color'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['background-color']) ) ? $GLOBALS['optd'][$value['id']]['background-color'] : '' ;
				
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<input class="text_q q8 color {hash:true,caps:false,required:false}" type="text" name="<?php echo $value['id']; ?>[background-color]" id="<?php echo $value['id']; ?>" value="<?php echo $val; ?>" />
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php		
				// bg-repeat
				$options_style = array("repeat","repeat-x","repeat-y","no-repeat");
				$std = $value['std']['background-repeat'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['background-repeat']) ) ? $GLOBALS['opt'][$value['id']]['repeat'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['background-repeat']) ) ? $GLOBALS['optd'][$value['id']]['background-repeat'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q3" name="<?php echo $value['id']; ?>[background-repeat]" id="<?php echo $value['id']; ?>_repeat">
<?php 
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- repeat --</option>\n";
					
					foreach ($options_style as $repeat) {
						if($val == $repeat){ $selected = 'selected="selected"'; } else { $selected = ''; }
						echo "<option ".$selected." value=\"" . $repeat . "\">" . $repeat . "</option>\n";	
					}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				// bg-position
				$options_style = array("top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");
				$std = $value['std']['background-position'];
				$sav = ( isset($GLOBALS['opt'][$value['id']]['background-position']) ) ? $GLOBALS['opt'][$value['id']]['background-position'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['background-position']) ) ? $GLOBALS['optd'][$value['id']]['background-position'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<select class="select_q q9" name="<?php echo $value['id']; ?>[background-position]" id="<?php echo $value['id']; ?>_position">
<?php 
					$selected = (!$std) ? ' selected="selected"' : '';
					echo "<option$selected value=\"\">-- position --</option>\n";
					
					foreach ($options_style as $position) {
						if($val == $position){ $selected = 'selected="selected"'; } else { $selected = ''; }
						echo "<option ".$selected." value=\"" . $position . "\">" . $position . "</option>\n";	
					}
?>
				</select>
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case "color":
				option_wrapper_header($value);
				
				$std = $value['std']['color'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['color']) ) ? $GLOBALS['opt'][$value['id']]['color'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['color']) ) ? $GLOBALS['optd'][$value['id']]['color'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<input class="text_q q8 color {hash:true,caps:false,required:false}" type="text" name="<?php echo $value['id']; ?>[color]" id="<?php echo $value['id']; ?>_color"  value="<?php echo $val; ?>" />
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case "background-color":
				option_wrapper_header($value);
				
				$std = $value['std']['background-color'];			
				$sav = ( isset($GLOBALS['opt'][$value['id']]['background-color']) ) ? $GLOBALS['opt'][$value['id']]['background-color'] : '' ;
				$sav2 = ( isset($GLOBALS['optd'][$value['id']]['background-color']) ) ? $GLOBALS['optd'][$value['id']]['background-color'] : '' ;
					
				if ( $sav != "") { $val = $sav; } elseif ( $sav2 != "") { $val = $sav2; } else { $val = $std; }
?>
				<input class="text_q q8 color {hash:true,caps:false,required:false}" type="text" name="<?php echo $value['id']; ?>[background-color]" id="<?php echo $value['id']; ?>_background-color" value="<?php echo $val; ?>" />
				<input class="text_input" name="<?php echo $value['id']; ?>[css]" id="<?php echo $value['id']; ?>_css" type="hidden" value="<?php echo stripslashes(stripslashes($value['std']['css'])); ?>" />
<?php
				option_wrapper_footer($value);
			break;
			
			case 'help':
				option_wrapper_header3($value);
					echo '<div><!----></div>'."\n";
				option_wrapper_footer3($value);
			break;
			
			case "heading":
				echo '<div class="box-title">'. $value['name'] .'</div>'."\n";
				echo '<div class="fr submit submit-title">'."\n";
					echo '<input name="save" class="button" type="submit" value="'.__('Save changes', 'bizzthemes').'" />'."\n";
					// echo '<input type="hidden" name="bizz_save" value="save" />'."\n";
				echo '</div>'."\n";
			break;
			
			case "subheadingtop":
				echo '<div class="feature-box">'."\n";
				echo '<div class="subheading">'."\n";
					if ($value['toggle'] <> "") {
						echo '<a class="toggle" href="" title="Show/hide additional information"><span class="pos">&nbsp;</span><span class="neg">&nbsp;</span>'. $value['name'] .'</a>';
					} 
				echo '</div>'."\n";
				echo '<div class="options-box">'."\n";
			break;
			
			case "subheadingbottom":
				echo '</div>'."\n"; // end options-box
				echo '</div>'."\n"; // end feature-box
			break;
			
			case "wraptop":
				echo '<div class="table-row"><div class="text"><div class="wrap-dropdown">'."\n";
			break;
			case "wrapbottom":
				echo '</div></div></div>'."\n";
			break;
			
			case "upc_top":
				echo '<div class="table-row upc-top"><div class="text"><div class="upc-wrap">'."\n";
			break;
			case "upc_bottom":
				echo '</div></div></div>'."\n";
			break;
			case "upc_addremove":
				echo '<div class="addremove"><span class="add" title="Add new item">Add [&#43;]</span> <span class="remove" title="Remove this item">Remove [&#45;]</span></div>'."\n";
			break;
			
			case "sorttop":
				echo '<div class="table-row"><div class="text"><div id="sortme" class="wrap-dropdown sortable">'."\n";
			break;
			case "sortbottom":
				echo '</div></div></div>'."\n";
			break;
			
			case "maintabletop":
				echo '<div class="maintable">'."\n";
			break;
			case "maintablebottom":
				echo '</div>'."\n";
			break;
			case "maintablebreak":
				echo '<div class="break"><!----></div>'."\n";
			break;
			
			default:
			break;
			
		} #end switch
		
		}
	} #foreach

    // options footer
	bizzthemes_options_footer();

} #admin options

/**
 * ADMIN HEADER OPTIONS
 *
 * output admin header options
 * @since 7.0
 */
function bizzthemes_options_header( $options_title = 'options', $toggle = false ) {
    global $options, $design, $bloghomeurl, $themeid;
			
	$return  = '';
	$return .= '<div class="wrap">'."\n";
	$return .= '<div class="bizzadmin">'."\n";
	$return .= '<div id="icon-bizzthemes" class="icon32"><br></div>';
	
	$adminmenu_layout = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_layout']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_layout']['value'] : '' ;
	$adminmenu_design = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_design']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_design']['value'] : '' ;
	$adminmenu_license = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_license']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_license']['value'] : '' ;
	$adminmenu_editor = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_editor']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_editor']['value'] : '' ;
	$adminmenu_version = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_version']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_version']['value'] : '' ;
	$adminmenu_tools = ( isset($GLOBALS['opt']['bizzthemes_adminmenu_tools']['value']) ) ? $GLOBALS['opt']['bizzthemes_adminmenu_tools']['value'] : '' ;
	
	$tabs_array = array(
		'builder' => array(
			'name' 		=> __('Builder', 'bizzthemes'),
			'page'		=> 'bizz-layout',
			'hide'		=> $adminmenu_layout
		),
		'settings' => array(
			'name' 		=> __('Framework', 'bizzthemes'),
			'page'		=> 'bizzthemes',
			'hide'		=> ''
		),
		'design' => array(
			'name' 		=> __('Design', 'bizzthemes'),
			'page'		=> 'bizz-design',
			'hide'		=> $adminmenu_design
		),
		'editor' => array(
			'name' 		=> __('Editor', 'bizzthemes'),
			'page'		=> 'bizz-editor',
			'hide'		=> $adminmenu_editor
		),
		'more' => array(
			'name' 		=> __('&#43;', 'bizzthemes'),
			'page'		=> 'bizz-more',
			'hide'		=> '',
			'more'		=> array(
				'license' => array(
					'name' 		=> __('License', 'bizzthemes'),
					'page'		=> 'bizz-license',
					'hide'		=> $adminmenu_license
				),
				'tools' => array(
					'name' 		=> __('Backup', 'bizzthemes'),
					'page'		=> 'bizz-tools',
					'hide'		=> $adminmenu_tools
				),
				'update' => array(
					'name' 		=> __('Update', 'bizzthemes'),
					'page'		=> 'bizz-update',
					'hide'		=> $adminmenu_version
				),
			),
		),
	);
	
	$return .= '<h2 class="nav-tab-wrapper">'."\n";
	foreach ($tabs_array as $tab ) {
		if ( $tab['hide'] )
			continue;
		if ( isset($tab['more']) ) {
			$hide_more = ( $tab['more']['license']['hide'] ) && ( $tab['more']['tools']['hide'] ) && ( $tab['more']['update']['hide'] );
			if ( $hide_more )
				continue;
			$return .= '<div class="drop-container">';
			$active = ( $_GET['page'] == $tab['more']['license']['page'] ) || ( $_GET['page'] == $tab['more']['tools']['page'] ) || ( $_GET['page'] == $tab['more']['update']['page'] );
			$active = ( $active ) ? ' nav-tab-active' : '';
			$return .= '<a href="#" class="nav-tab dropdown-toggle'.$active.'">'.$tab['name'].'</a>'."\n";
			$return .= '<ul class="dropdown-menu">';
			foreach ( $tab['more'] as $drop_tab ) {
				if ( $drop_tab['hide'] )
					continue;
				$return .= '<li><a href="admin.php?page='.$drop_tab['page'].'" class="sub-tab">'.$drop_tab['name'].'</a></li>';
			}
			$return .= '</ul>';
			$return .= '</div>';
		}
		else {
			$active = ( $_GET['page'] == $tab['page'] ) ? ' nav-tab-active' : '';
			$return .= '<a href="admin.php?page='.$tab['page'].'" class="nav-tab'.$active.'">'.$tab['name'].'</a>';
		}
	}
	$return .= '<a href="#" class="feedback"  onclick="window.open(\'http://bizzthemes.wufoo.com/forms/m7x4a3/\',  null, \'height=560, width=670, toolbar=0, location=0, status=1, scrollbars=1,resizable=1\'); return false" title="'.__('Give your Feedback', 'bizzthemes').'">&nbsp;'.__('Report a Bug', 'bizzthemes').'&nbsp;</a>'."\n";
	$return .= '</h2>'."\n";
	
	$return .= '<div class="clear"><!----></div>'."\n";
	$return .= '<div id="saved"><!----></div>'."\n";
	
	if (file_exists(TEMPLATEPATH . '/custom-sample') && !file_exists(TEMPLATEPATH . '/custom') && is_writable(TEMPLATEPATH . '/custom-sample')) #rename custom-sample to custom
	    rename( TEMPLATEPATH . '/custom-sample', TEMPLATEPATH . '/custom' );
	if (!file_exists(TEMPLATEPATH . '/custom')) #Custom file alert
	    $return .= '<div id="message" class="updated">'.__('<p>Rename your <code>custom-sample</code> folder to <code>custom</code>, otherwise theme will not work properly.</p>', 'bizzthemes').'</div>'."\n";
	if (file_exists(TEMPLATEPATH . '/custom') && !is_writable(TEMPLATEPATH . '/custom'))
	    $return .= '<div id="message" class="updated">'.__('<p>Warning: Your <code>custom</code> folder is not writeable. Please <a href="http://codex.wordpress.org/Changing_File_Permissions">CHMOD</a> all custom folder files to 777 restrictions, otherwise theme will not work properly.</p>', 'bizzthemes').'</div>'."\n";
    if ( isset($_REQUEST['reset']) ) #Settings reset alert
	    $return .= '<div id="message" class="updated">'.sprintf(__('<p>Settings reset!&nbsp; <a href="%1$s">Check out your website &rarr;</a></p>', 'bizzthemes'), $bloghomeurl).'</div>'."\n";
		
	if ( $toggle == true ) {
		$return .= '<div class="switch">'."\n";
		$return .= '<a id="master_switch" href="" title="'.__('Show/Hide All Options', 'bizzthemes').'"><span class="pos">&nbsp;</span><span class="neg">&nbsp;</span> '.__('Show/Hide All Options', 'bizzthemes').'</a>'."\n";
		$return .= '</div>'."\n";
	}
	
	$return .= '<div class="clear"><!----></div>'."\n";
	$return .= '<div id="bizz-popup-save" class="save-popup">'.__('Changes Saved', 'bizzthemes').'</div>'."\n";
	$return .= '<div id="ajax-loading" class="loading-popup"><!----></div>'."\n";
	
	if ( $_GET['page'] == 'bizz-design' )
	    $return .= '<form action="" enctype="multipart/form-data" id="bizz_form_design">'."\n";
	elseif ( $_GET['page'] == 'bizzthemes' )
        $return .= '<form action="" enctype="multipart/form-data" id="bizz_form">'."\n";
	
	echo $return;
	
}

/**
 * ADMIN FOOTER OPTIONS
 *
 * output admin footer options
 * @since 7.0
 */
function bizzthemes_options_footer() {
	$return = '<div class="clear"></div>'."\n";
	
	if ( $_GET['page'] == 'bizz-design' || $_GET['page'] == 'bizzthemes' ){
		$return .= '<div class="reset_save">'."\n";
		$return .= '<input name="save" type="submit" class="button button-primary" value="'.__('Save changes', 'bizzthemes').'" />'."\n";
		$return .= '</div>'."\n";
	    $return .= '</form>'."\n";
	    $return .= '<form method="post">'."\n";
		$return .= '<div class="reset_save reset">'."\n";
		$return .= '<input name="reset" type="submit" class="button" value="'.__('Reset', 'bizzthemes').'" onclick="return confirm(\''.__('All theme settings will be lost! Click OK to reset.', 'bizzthemes').'\');" />'."\n";
		$return .= '<input type="hidden" name="bizz_save" value="reset" />'."\n";
		$return .= '</div>'."\n";
	    $return .= '</form>'."\n";
	}
	
	$return .= '</div>'."\n";
	$return .= '</div>'."\n";
		
	echo $return;
	
}

/**
 * ADMIN HELP OPTIONS
 *
 * output admin footer options
 * @since 7.0
 */
add_filter('contextual_help','bizz_contextual_help', 10, 3);
function bizz_contextual_help($help, $screen_id, $screen) {
	global $pagenow;
			
	$return = '';
	if ( is_admin() && ( 'admin.php' == $pagenow ) ) {
		$return .= '<br/>';
		//keep the existing help
		$return .= '<div class="metabox-prefs">';
		$return .= '<strong>WordPress</strong><br />';
		$return .= '<a href="http://codex.wordpress.org/" target="_blank">'.__('Documentation', 'bizzthemes').'</a><br />';
		$return .= '<a href="http://wordpress.org/support/" target="_blank">'.__('Support Forums', 'bizzthemes').'</a><br /><br />';
		$return .= '</div>';
		//add some new
		$return .= '<div class="metabox-prefs">';
		$return .= '<strong>BizzThemes</strong><br />';
		$return .= '<a class="optional" href="http://bizzthemes.com/documentation/" title="'.__('Read Theme Documentation & Installation Guide', 'bizzthemes').'">'.__('Theme Documentation', 'bizzthemes').'</a><br />';
		$return .= '<a class="optional" href="http://bizzthemes.com/forums/" title="'.__('Visit Support Forums', 'bizzthemes').'">'.__('Theme Support Forums', 'bizzthemes').'</a><br /><br />';
		$return .= '</div>';
	}
	else
		$return .= $help;
		
	return $return;
}

/**
 * ADMIN OPTIONS INCLUDES
 *
 * output admin includes for options
 * @since 7.0
 */
function option_wrapper_header2($values){
    echo '<div class="table-row">'."\n";
}
function option_wrapper_header3($values){
    echo '<div class="table-row">'."\n";
	    if ($values['name'] <> '') {
		echo '<div class="top-container">'."\n";
		    echo '<span class="name fl">'. $values['name'] .'</span>'."\n";
			if ( isset($values['desc']) ) {
		        // echo '<p class="description">'. $values['desc'] .'</p>'."\n";
			    echo '<div class="bubbleInfo fr">'."\n";
			    echo '<span class="trigger">'.__('help [&#43;]', 'bizzthemes').'</span>'."\n";
			    echo '<div class="popup">'. $values['desc'] .'</div>'."\n";
			    echo '</div>'."\n";
			}
		echo '<div class="clear"><!----></div'."\n";
		echo '</div'."\n";
		}
		echo '<div class="bottom-container">'."\n";
		
}
function option_wrapper_header($values){
    echo '<div class="table-row">'."\n";
	    if (isset($values['name']) && $values['name'] <> '') {
		echo '<div class="top-container">'."\n";
		    echo '<span class="name fl">'. $values['name'] .'</span>'."\n";
			if (isset($values['desc']) && $values['desc'] <> '') {
		        // echo '<p class="description">'. $values['desc'] .'</p>'."\n";
			    echo '<div class="bubbleInfo fr">'."\n";
			    echo '<span class="trigger">'.__('help [&#43;]', 'bizzthemes').'</span>'."\n";
			    echo '<div class="popup">'. $values['desc'] .'</div>'."\n";
			    echo '</div>'."\n";
			}
		echo '<div class="clear"><!----></div'."\n";
		echo '</div'."\n";
		}
		echo '<div class="bottom-container">'."\n";
		echo '<div class="text">'."\n";
		
}
function option_wrapper_footer($values){
        echo '</div>'."\n";
		echo '</div>'."\n";
	echo '</div>'."\n";
	
}
function option_wrapper_footer3($values){
        echo '</div>'."\n";
	echo '</div>'."\n";
}

