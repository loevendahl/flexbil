<?php 

/*************************************************************
* Do not modify unless you know what you're doing, SERIOUSLY!
*************************************************************/

/* BizzThemes WordPress theme framework by Zeljan Topic */

//** DEFINE DIRECTORY CONSTANTS **//
	
	if ( ! defined( 'BIZZ_LIB_FRAME' ) )
		define('BIZZ_LIB_FRAME', TEMPLATEPATH . '/lib_frame');        // Framework Library
	if ( ! defined( 'BIZZ_LIB_THEME' ) )
		define('BIZZ_LIB_THEME', TEMPLATEPATH . '/lib_theme');        // Theme Library
	if ( ! defined( 'BIZZ_LIB_CUSTOM' ) )
		define('BIZZ_LIB_CUSTOM', TEMPLATEPATH . '/custom');          // Custom Library
	
//** DEFINE CSS FILE CONSTANTS **//

    if ( ! defined( 'BIZZ_STYLE_CSS' ) )
		define('BIZZ_STYLE_CSS', TEMPLATEPATH . '/style.css');        // General CSS styles
	if ( file_exists(TEMPLATEPATH . '/custom')){
		if ( ! defined( 'BIZZ_LAYOUT_CSS' ) )
			define('BIZZ_LAYOUT_CSS', BIZZ_LIB_CUSTOM . '/layout.css');   // Layout CSS styles (generated automatically)
		if ( ! defined( 'BIZZ_CUSTOM_CSS' ) )
			define('BIZZ_CUSTOM_CSS', BIZZ_LIB_CUSTOM . '/custom.css');   // Custom CSS styles (generated manually by user)
	}
	
//** DEFINE VARIABLE CONSTANTS **//

	require_once (BIZZ_LIB_THEME . '/theme_variables.php');       // THEME VARIABLES
    require_once (BIZZ_LIB_FRAME . '/frame_variables.php');       // FRAMEWORK VARIABLES	
	require_once (BIZZ_LIB_THEME . '/theme_constants.php');       // THEME CONSTANTS
	if (file_exists(TEMPLATEPATH . '/custom')) {
		include_once (BIZZ_LIB_CUSTOM . '/custom_functions.php');     // CUSTOM FILES
	}
	add_action('pre_get_posts', 'filter_posts_list');
	function filter_posts_list($query)
	{
			
			global $post_type;
 	        global $pagenow;
		    global $current_user;
		    get_currentuserinfo();
		    if(!current_user_can('administrator'))
	        {
			     if($query->is_main_query() ) 
		         {
		         	//$query->set('author', $current_user->ID); 
		         	if($post_type == 'bizz_bookings')
		         	{
		         			$query->set( 'meta_query', array( 
											            array(
											              'key' => 'bizzthemes_bookings_dealer_id',
											              'value' => $current_user->ID
											            )
			         							    )
			                   );
		         	} 
			        if($post_type == 'bizz_cars')
		         	{
				        $query->set( 'meta_query', array( 
												            array(
												              'key' => 'bizzthemes_car_dealer',
												              'value' => $current_user->ID
												            )
				         							    )
				                   );
				     }   
				     if($post_type == 'bizz_pricing')
		         	 {
				        $query->set( 'meta_query', array( 
												            array(
												              'key' => 'bizzthemes_price_dealer',
												              'value' => $current_user->ID
												            )
				         							    )
				                   );
				     } 
				     if($post_type == 'bizz_locations')
		         	 {
				        $query->set( 'meta_query', array( 
												            array(
												              'key' => 'bizzthemes_location_dealer',
												              'value' => $current_user->ID
												            )
				         							    )
				                   );
				     }     
			     } 
			}
	}	    
	
//** MAKE THEME TRANSLATABLE **//
	load_theme_textdomain( 'bizzthemes', BIZZ_LIB_CUSTOM . '/lang' );
	