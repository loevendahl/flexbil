<?php

/*

  FILE STRUCTURE:

	- Custom post type icons
	- Custom Post Types Init
	- Columns for post types
	- Custom Post Type Filters
	- Custom Post Type Metabox Setup
	- Custom Post Type Taxonomy Setup
	- Custom Taxonomy Columns Setup

*/

/* Custom post type init */
/*------------------------------------------------------------------*/
function bizz_coupons_post_types_init() {

	register_post_type( 'bizz_coupons',
        array(
        	'label' 				=> __('Coupons', 'bizzthemes'),
			'labels' 				=> array(	
				'name' 					=> __('Coupons', 'bizzthemes'),
				'singular_name' 		=> __('Coupons', 'bizzthemes'),
				'add_new' 				=> __('Add New', 'bizzthemes'),
				'add_new_item' 			=> __('Add New Coupon', 'bizzthemes'),
				'edit' 					=> __('Edit', 'bizzthemes'),
				'edit_item' 			=> __('Edit Coupon', 'bizzthemes'),
				'new_item' 				=> __('New Coupon', 'bizzthemes'),
				'view_item'				=> __('View Coupon', 'bizzthemes'),
				'search_items' 			=> __('Search Coupons', 'bizzthemes'),
				'not_found' 			=> __('No Coupons found', 'bizzthemes'),
				'not_found_in_trash' 	=> __('No Coupons found in trash', 'bizzthemes'),
				'parent' 				=> __('Parent Coupon', 'bizzthemes'),
			),
            'description' => __( 'This is where you can create new coupons for your site.', 'bizzthemes' ),
            'public' => false,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => false,
			'menu_icon' => 'dashicons-tag',
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'coupon_slug', 'coupon'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => true,
            'supports' => array(	
				'title',
				// 'custom-fields'
			)
        )
    );

}
add_action( 'init', 'bizz_coupons_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_coupons_edit_columns($columns){
	$columns['cb'] 						= '<input type=\'checkbox\' />';
	$columns['title'] 					= __('Coupon Code', 'bizzthemes');
	$columns['bizz_coupon_type'] 		= __('Coupon Type', 'bizzthemes');
	$columns['bizz_coupon_amount'] 		= __('Coupon Amount', 'bizzthemes');
	$columns['bizz_coupon_limit'] 		= __('Usage Limit', 'bizzthemes');
	$columns['bizz_coupon_expiry'] 		= __('Expiry Date', 'bizzthemes');
	
	return $columns;
}
add_filter('manage_edit-bizz_coupons_columns','bizz_coupons_edit_columns');

function bizz_coupons_custom_columns($column){
	global $post;

	$custom = get_post_custom();
	switch ($column){
		case "bizz_coupon_type":
			$column = ( isset($custom["bizzthemes_coupon_type"][0]) ) ? $custom["bizzthemes_coupon_type"][0] : '/';
			if ( $column != '' ) { echo $column; }
		break;
		case "bizz_coupon_amount":
			$column = ( isset($custom["bizzthemes_coupon_amount"][0]) ) ? $custom["bizzthemes_coupon_amount"][0] : '/';
			if ( $column != '' ) { echo $column; }
		break;
		case "bizz_coupon_limit":
			$column = ( isset($custom["bizzthemes_coupon_limit"][0]) ) ? $custom["bizzthemes_coupon_limit"][0] : __('Unlimited', 'bizzthemes');
			if ( $column != '' ) { echo $column; }
		break;
		case "bizz_coupon_expiry":
			$column = ( isset($custom["bizzthemes_coupon_expiry"][0]) ) ? $custom["bizzthemes_coupon_expiry"][0] : __('No expiry', 'bizzthemes');
			if ( $column != '' ) { echo $column; }
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_coupons_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

/* Remove links from post actions */
/*------------------------------------------------------------------*/
add_filter( 'page_row_actions', 'coupon_remove_row_actions', 10, 2 );
function coupon_remove_row_actions( $actions, $post ) {
	global $current_screen;
	
	if( isset( $current_screen->post_type ) && $current_screen->post_type == 'bizz_coupons' ) {
		// unset( $actions['edit'] );
		unset( $actions['view'] );
		// unset( $actions['trash'] );
		unset( $actions['inline hide-if-no-js'] );
		//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit', 'bizzthemes' );
	}

	return $actions;
}

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_meta_boxes', 'bizz_coupons_metaboxes' );
function bizz_coupons_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	// cars
	$car_args = array( 'post_type' => 'bizz_cars', 'suppress_filters' => false, 'numberposts' => -1 );
	$car_posts = get_posts( $car_args );
	$car_options = array();
	if ($car_posts) {
		foreach ($car_posts as $car_post) {
			$car_options[$car_post->ID] = $car_post->post_title;
		}
	}
	
	// extras
	$extras_terms = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );
	$extras_options = array();
	foreach ($extras_terms as $extras_term) {
		$extras_options[$extras_term->term_id] = $extras_term->name;
	}
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_coupons_meta',
		'title' => __('Coupon Details', 'bizzthemes'),
		'pages' => array( 'bizz_coupons' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Discount Type', 'bizzthemes'),
				'id' => $prefix . 'coupon_type',
				'type' => 'select',
				'options' => apply_filters( 'bizz_car_coupon_type', array(
					array( 'value' => 'percentage', 'name' => __( 'Percentage of total (i.e. -10%)', 'bizzthemes' ) ),
					array( 'value' => 'fixed_unit', 'name' => __( 'Fixed amount per unit ( i.e. -$10/day)', 'bizzthemes' ) ),
					array( 'value' => 'fixed_block', 'name' => __( 'Fixed amount per block ( i.e. -$10/booking)', 'bizzthemes' ) ),
				) )
			),
			array(
				'name' => __('Discount Amount', 'bizzthemes'),
				'id' => $prefix . 'coupon_amount',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Usage Limit', 'bizzthemes'),
				'desc' => __('Leave empty for unlimited usage.', 'bizzthemes'),
				'id' => $prefix . 'coupon_limit',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Vehicles', 'bizzthemes'),
				'id' => $prefix . 'coupon_car',
				'type' => 'multicheck',
				'options' => $car_options
			),
			array(
				'name' => __('Extras', 'bizzthemes'),
				'id' => $prefix . 'coupon_extra',
				'type' => 'multicheck',
				'options' => $extras_options
			),
			array(
				'name' => __('Expiry Date', 'bizzthemes'),
				'id' => $prefix . 'coupon_expiry',
				'type' => 'text_date'
			),
		)
	);
		
	return $meta_boxes;
}


