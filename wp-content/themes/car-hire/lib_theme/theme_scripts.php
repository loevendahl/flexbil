<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*

  FILE STRUCTURE:

- THEME SCRIPTS

*/

/* THEME SCRIPTS */
/*------------------------------------------------------------------*/

// Add Theme Javascript
if (!is_admin()) add_action( 'wp_print_scripts', 'bizz_add_javascript' );
function bizz_add_javascript() {

	// offline
	wp_enqueue_script( 'theme-js', BIZZ_THEME_JS .'/theme.js', array( 'jquery' ) ); # header

}

// Add Theme Meta Tags
add_action('wp_head', 'bizzthemes_theme_head_meta');
function bizzthemes_theme_head_meta() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
	echo '<!--[if lt IE 9]>
	  <script src="'.BIZZ_THEME_JS.'/html5shiv.js"></script>
	  <script 
        src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js">
	</script>
	<![endif]-->';
}
