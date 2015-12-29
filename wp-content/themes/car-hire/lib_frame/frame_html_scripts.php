<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * THEME JAVASCRIPTS
 *
 * print theme javascripts
 * @since 7.0
 */
add_action( 'wp_enqueue_scripts', 'bizzthemes_theme_scripts' );
function bizzthemes_theme_scripts( ) {

    // Compressed theme scripts
	#wp_enqueue_script( 'bizz-theme', BIZZ_FRAME_SCRIPTS . '/theme.dev.js', array( 'jquery' ), '', true ); #footer
	wp_enqueue_script( 'bizz-theme', BIZZ_FRAME_SCRIPTS .'/theme.all.min.js', array( 'jquery' ), '', true ); # footer
	wp_localize_script( 'bizz-theme', 'bizz_localize', localize_vars());
	
	if ( apply_filters('bizz_bootstrap', false) )
		wp_enqueue_script( 'bizz-bootstrap', BIZZ_FRAME_SCRIPTS .'/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '', true ); # footer
		
	if ( apply_filters('bizz_bootstrap3', false) )
		wp_enqueue_script( 'bizz-bootstrap3', BIZZ_FRAME_SCRIPTS .'/bootstrap3/js/bootstrap.min.js', array( 'jquery' ), '', true ); # footer

}
function localize_vars() {
    $return = array(
        'siteurl' 	=> home_url(),
		'ajaxurl' 	=> admin_url( 'admin-ajax.php' )
	);
	
	return $return;
}

/**
 * THEME STYLESHEETS
 *
 * print theme stylesheets
 * @since 7.0
 */
add_action('wp_enqueue_scripts', 'bizzthemes_theme_head_styles');
function bizzthemes_theme_head_styles( ) {
	
	// Bootstrap
	if ( apply_filters('bizz_bootstrap', false) ) {
		wp_register_style('bizz_bootstrap', BIZZ_FRAME_SCRIPTS .'/bootstrap/css/bootstrap.min.css');
		wp_enqueue_style('bizz_bootstrap');
	}
	
	// Bootstrap 3
	if ( apply_filters('bizz_bootstrap3', false) ) {
		wp_register_style('bizz_bootstrap3', BIZZ_FRAME_SCRIPTS .'/bootstrap3/css/bootstrap.min.css');
		wp_enqueue_style('bizz_bootstrap3');
	}
	
	// Main stylesheet
	$date_modified_style = filemtime(TEMPLATEPATH . '/style.css');
	wp_register_style('main_stylesheet', get_bloginfo('stylesheet_url') . '?' . date('mdy-Gis', $date_modified_style), '', '', 'screen, projection');
	wp_enqueue_style('main_stylesheet');
	
	// Shortcodes
	wp_register_style('css_shortcodes', BIZZ_FRAME_CSS .'/shortcodes.css');
	wp_enqueue_style('css_shortcodes');
	
	// Modified date
	if (file_exists(TEMPLATEPATH . '/custom')){
		$date_modified_custom = filemtime(BIZZ_LIB_CUSTOM . '/custom.css');
		$date_modified_layout = filemtime(BIZZ_LIB_CUSTOM . '/layout.css');
	}
	$stylesheet = $GLOBALS['optd']['bizzthemes_alt_stylesheet']['value'];
	
	if($stylesheet != ''){
	    // Skin stylesheet
	    wp_register_style('skinStylesheet', BIZZ_THEME_SKINS .'/'. $stylesheet);
	    wp_enqueue_style('skinStylesheet');
	}
		
	if ( !empty($GLOBALS['optd']['bizzthemes_layout_css']['value']) || !file_exists(TEMPLATEPATH . '/custom') ) {} else { // hide layout.css output
		// Layout stylesheet
		wp_register_style('layout_stylesheet',  get_stylesheet_directory_uri() .'/custom/layout.css?' . date('mdy-Gis', $date_modified_layout), '', '', 'screen, projection');
		wp_enqueue_style('layout_stylesheet');
	}
		
	if ( !empty($GLOBALS['optd']['bizzthemes_custom_css']['value']) || !file_exists(TEMPLATEPATH . '/custom') ) {} else { // hide custom.css output
		// Custom stylesheet
		wp_register_style('custom_stylesheet', get_stylesheet_directory_uri() .'/custom/custom.css?' . date('mdy-Gis', $date_modified_custom), '', '', 'screen, projection');
		wp_enqueue_style('custom_stylesheet');
	}

}

/**
 * THEME HEAD CODE
 *
 * print theme head code
 * @since 7.0
 */
add_action('wp_head', 'bizzthemes_theme_head');
function bizzthemes_theme_head() {
	
	// Custom theme Favicon
	if (isset($GLOBALS['opt']['bizzthemes_favicon']['value']) && $GLOBALS['opt']['bizzthemes_favicon']['value'] != '')
	    echo '<link rel="shortcut icon" href="'.$GLOBALS['opt']['bizzthemes_favicon']['value'].'"/>'."\n";
	
	// RSS Feed Settings
	echo '<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="';
	if ( isset($GLOBALS['opt']['bizzthemes_feedburner_url']['value']) && $GLOBALS['opt']['bizzthemes_feedburner_url']['value'] <> "" )
	    echo strip_tags(stripslashes($GLOBALS['opt']['bizzthemes_feedburner_url']['value']));
	else
	    echo get_bloginfo_rss('rss2_url');
	echo '" />'."\n";
	
	// Theme header scripts (like Mint tracking code)
	if (isset($GLOBALS['opt']['bizzthemes_scripts_header']['value']) && $GLOBALS['opt']['bizzthemes_scripts_header']['value'] <> "" )
	    echo stripslashes($GLOBALS['opt']['bizzthemes_scripts_header']['value']); 
	
	// Google Font Settings
	$font_stacks = bizz_get_fonts();
	$ffamily = array();
	if ( isset($GLOBALS['optd']) && $GLOBALS['optd'] <> '' ){
		foreach ($GLOBALS['optd'] as $key => $value){
			if ( isset( $GLOBALS['optd'][$key]['font-family'] ) && isset( $font_stacks[$GLOBALS['optd'][$key]['font-family']]['google'] ) && $font_stacks[$GLOBALS['optd'][$key]['font-family']]['google'] ){
				$face_id = $font_stacks[$GLOBALS['optd'][$key]['font-family']]['name'];
				$ffamily[$key] = $face_id;
			}
		}
	}
	$ffamily_u = array_unique($ffamily);	
	$ffamily_v = array_values( $ffamily_u );
	$ffamily_v = implode( "|", $ffamily_v );
	if ( isset($ffamily_v) && !empty($ffamily_v) )
		echo '<link href="https://fonts.googleapis.com/css?family='.$ffamily_v.'" rel="stylesheet" type="text/css" />'."\n";

}

/**
 * THEME BODY CODE
 *
 * print theme body code
 * @since 7.0
 */
add_action('bizz_body_after', 'bizzthemes_theme_body');
function bizzthemes_theme_body() {
		
	// Theme header scripts (like Mint tracking code)
	if (isset($GLOBALS['opt']['bizzthemes_scripts_body']['value']) && $GLOBALS['opt']['bizzthemes_scripts_body']['value'] <> "" )
	    echo stripslashes($GLOBALS['opt']['bizzthemes_scripts_body']['value']);
	
}

/**
 * THEME FOOTER CODE
 *
 * print theme footer code
 * @since 7.0
 */
add_action('wp_footer', 'bizzthemes_theme_foot');
function bizzthemes_theme_foot() { 
	
	// Theme footer scripts (like Google Analytics)
	if ( isset($GLOBALS['opt']['bizzthemes_google_analytics']['value']) && $GLOBALS['opt']['bizzthemes_google_analytics']['value'] <> "" )
	    echo stripslashes($GLOBALS['opt']['bizzthemes_google_analytics']['value']); 
	
}

