<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*

  FILE STRUCTURE:

- DEFINE DIRECTORY CONSTANTS
- DEFINE THEME FILES

*/
		
/* DEFINE DIRECTORY CONSTANTS */
/*------------------------------------------------------------------*/
	define('BIZZ_THEME_CSS', get_template_directory_uri() . '/lib_theme/css');
	define('BIZZ_THEME_IMAGES', get_template_directory_uri() . '/lib_theme/images');
	define('BIZZ_THEME_JS', get_template_directory_uri() . '/lib_theme/js');
	define('BIZZ_THEME_SKINS', get_template_directory_uri() . '/lib_theme/skins');
	
/* DEFINE THEME FILES */
/*------------------------------------------------------------------*/
	require_once (BIZZ_LIB_THEME . '/theme_scripts.php');
	require_once (BIZZ_LIB_THEME . '/theme_widgets.php');
	require_once (BIZZ_LIB_THEME . '/theme_functions.php');
	require_once (BIZZ_LIB_THEME . '/theme_templates.php');
	require_once (BIZZ_LIB_THEME . '/mce-table-buttons/mce_table_buttons.php');

