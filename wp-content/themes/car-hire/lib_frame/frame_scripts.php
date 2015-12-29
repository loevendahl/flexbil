<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ADMIN SCRIPTS
 *
 * print admin scripts
 * @since 7.0
 */
add_action('admin_enqueue_scripts', 'bizz_print_admin_scripts');
function bizz_print_admin_scripts( $currpage ) {
    global $pagenow;
			
	// default js
	if ( bizz_is_admin() ) {
		wp_deregister_script( 'jquery-ui' ); #deregister current jquery ui
		wp_enqueue_script( 'jquery-ui-core', '', '', '', true ); #footer
		wp_enqueue_script( 'jquery-ui-tabs', '', '', '', true ); #footer
		wp_enqueue_script( 'jquery-ui-draggable', '', '', '', true ); #footer
		wp_enqueue_script( 'jquery-ui-droppable', '', '', '', true ); #footer
		wp_enqueue_script( 'jquery-ui-sortable', '', '', '', true ); #footer
	}
	
	// bizz admin js
	if ( bizz_is_admin() ) {
		if ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '<' ) ) {
			wp_enqueue_script( 'bizz-frame', BIZZ_FRAME_ROOT . '/legacy/frame.all.min.js', array( 'jquery' ), false, true ); #footer
		} else {
			wp_enqueue_script( 'bizz-frame', BIZZ_FRAME_SCRIPTS . '/frame.all.min.js', array( 'jquery' ), false, true ); #footer
			#wp_enqueue_script( 'bizz-admin', BIZZ_FRAME_SCRIPTS . '/admin.dev.js', array( 'jquery' ), '', true ); #footer
			#wp_enqueue_script( 'bizz-widgets', BIZZ_FRAME_SCRIPTS . '/widgets.dev.js', array( 'jquery' ), '', true ); #footer
			wp_enqueue_script( 'jscolor', BIZZ_FRAME_SCRIPTS . '/jscolor/jscolor.js', null, false, true ); #footer
		}
	}
	
	// bizz admin css
	if ( bizz_is_admin() ) {
		if ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '<' ) ) {
			wp_enqueue_style( 'bizz_style', BIZZ_FRAME_ROOT .'/legacy/admin_style.css' ); #header
		} else {
			wp_enqueue_style( 'bizz_style', BIZZ_FRAME_CSS .'/admin_style_dev.css' ); #header
		}
	}
	
	// all pages css & js
	wp_enqueue_style( 'bizz_menu', BIZZ_FRAME_CSS .'/admin_menu.css' ); #header

}

/**
 * FEATURE POINTERS
 *
 * print admin pointers
 * @since 7.6.5
 */
add_action( 'admin_enqueue_scripts', 'bizz_pointers_scripts' );
function bizz_pointers_scripts() {
	
	if( !is_admin() || version_compare(get_bloginfo('version'), '3.2.3', '<=') )
		return;
		
	// Probably already included, but we want to be safe.
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );
	
	// Function to call pointers content
	add_action( 'admin_print_footer_scripts', 'bizz_pointers_content' );
	
}

function bizz_pointers_content() {
	global $themeid;
	
	$current_blog_id = get_current_blog_id();
	
	// license key?
	$license_what = '<h3>' . __( 'A License Key?', 'bizzthemes' ) . '</h3>';
	$license_what .= '<p>' . __( "Even though, themes are GPL licensed, we keep rights for issuing three versions of this theme: free, standard and agency. Whereas, free version does not require a license to function, standard and agency need license key, which you get after your theme purchase inside your BizzThemes account.", 'bizzthemes') . '</p>';
	$license_what_hide = get_user_setting( 'p_license_what', 0 ); // check settings on user
	
	// no widgets?
	$empty_widgets = '<h3>' . __( 'Your theme has no widgets!', 'bizzthemes' ) . '</h3>';
	$empty_widgets .= '<p>' . __('It appears that your theme has no widgets at the moment. This happens if you install a new theme or clear your layouts, but no worries, you can set default widgets for this theme with one click on the button below.', 'bizzthemes') . '</p>';
	$empty_widgets .= '<p>' . sprintf(__('<a href="%1$s" class="button" onclick="alert_confirm();">Set default widgets</a>', 'bizzthemes'), wp_nonce_url(admin_url('admin.php?page=bizz-tools&amp;restore=layouts'), 'bizzthemes-restore-layouts')) . '</p>';
	$empty_widgets_hide = get_user_setting( 'p_empty_widgets_' . str_replace('-','',$themeid) . '_' . $current_blog_id, 0 ); // check settings on user
	$wid_args = array(
		'post_type' 	=> 'bizz_widget',
		'numberposts' 	=> 450,
		'orderby' 		=> 'modified',
		'order' 		=> 'ASC',
		'post_status' 	=> 'publish'
	);
	$wid_posts = get_posts($wid_args);
	$wid_exists = false;
	if ($wid_posts) {
		foreach ( $wid_posts as $post ) {
			$test1 = ($post->post_content_filtered == $themeid) ? true : false;
			$test2 = (empty($post->post_content_filtered)) ? true : false;
			if ($test1 || $test2) {
				$wid_exists = true;
				break;
			}
		}
	}
	
	// manage templates?
	$manage_templates = '<h3>' . __( 'Manage your templates', 'bizzthemes' ) . '</h3>';
	$manage_templates .= '<p>' . __( "Select a template before adding widgets to your website. After selection, simply drag widgets from Available section to the dotted grids on the right hand side.", 'bizzthemes') . '</p>';
	$manage_templates .= '<p><b>' . __( "Edit home page widgets", 'bizzthemes') . '</b></p>';
	$manage_templates .= '<p>' . __( "Select Home template and add some widgets or edit existing ones. Voil&aacute;.", 'bizzthemes') . '</p>';
	$manage_templates .= '<p><b>' . __( "Widgets visible on all pages", 'bizzthemes') . '</b></p>';
	$manage_templates .= '<p>' . __( "To have widgets across all web pages, select Site-wide template and add them there.", 'bizzthemes') . '</p>';
	$manage_templates .= '<p><b>' . __( "Single or Archive?", 'bizzthemes') . '</b></p>';
	$manage_templates .= '<p>' . sprintf(__( 'Single templates are used to render a single post on one page, like an About page. Archive templates render index of single posts on one page. More about template hierarchy <a href=%1$s">here</a>.', 'bizzthemes'), esc_url('http://codex.wordpress.org/Template_Hierarchy')) . '</p>';
	$manage_templates .= '<p><b>' . __( "Change layout structure", 'bizzthemes') . '</b></p>';
	$manage_templates .= '<p>' . sprintf(__( 'To add, remove or edit layout areas, like Header Area or Footer Area, <a href=%1$s">read this tutorial</a>. You can also rearrange existing layout areas and disable them on selected templates.', 'bizzthemes'), esc_url('http://bizzthemes.com/support/topic/creating-and-modifying-bizzthemes-grid-structure-theme-layout')) . '</p>';
	$templates_info_hide = get_user_setting( 'p_templates_info', 0 ); // check settings on user
	
?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function(){
		var direction = ( jQuery('body').hasClass('rtl') ) ? 'right' : 'left';
		<?php if ( !$license_what_hide && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('#license-what').pointer({
			content    : '<?php echo $license_what; ?>',
			position   : {
				edge: 'left',
				align: 'center'
			},
			close: function() {
				setUserSetting( 'p_license_what', '1' );
			}
		}).pointer('open');
		<?php } ?>
		<?php if ( !$empty_widgets_hide && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) && !$wid_exists && current_user_can('edit_theme_options') ) { ?>
		jQuery('#icon-bizzthemes').pointer({
			content    : '<?php echo $empty_widgets; ?>',
			position   : {
				edge: 'top',
				align: 'left'
			},
			close: function() {
				setUserSetting( 'p_empty_widgets_<?php echo str_replace('-','',$themeid); ?>', '1' );
			}
		}).pointer('open');
		<?php } ?>
		<?php if ( !$templates_info_hide && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
		jQuery('.disabled .select-templates').pointer({
			content    		: '<?php echo $manage_templates; ?>',
			position   		: {
				edge: 'top',
				align: direction
			},
			pointerWidth 	: 312,
			pointerClass	: 'wp-pointer templates-pointer',
			close: function() {
				setUserSetting( 'p_templates_info', '1' );
			}
		}).pointer('open');
		<?php } ?>
	});
	function alert_confirm() {
		return confirm("Sure your want to do this! Click OK to proceed.");
	}
	function template_help_toggle() {
		var direction = ( jQuery('body').hasClass('rtl') ) ? 'right' : 'left';
		// open pointer
		if ( getUserSetting( 'p_templates_info' ) == 1 || jQuery('.templates-pointer').length == 0 ) {
			// set
			setUserSetting( 'p_templates_info', '0' );
			// pointer
			jQuery('.select-templates').pointer({
				content    		: '<?php echo $manage_templates; ?>',
				position   		: {
					edge: 'top',
					align: direction
				},
				pointerWidth 	: 312,
				pointerClass	: 'wp-pointer templates-pointer',
				close: function() {
					setUserSetting( 'p_templates_info', '1' );
				}
			}).pointer('open');
		}
		// close pointer
		else {
			// set
			setUserSetting( 'p_templates_info', '1' );
			// pointer
			jQuery('.select-templates').pointer('close');
		}
				
		// prevent url click
		return false;
	}
	//]]>
	</script>
<?php
}