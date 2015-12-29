<?php
if ( ! defined( 'ABSPATH' ) ) exit;

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
function bizz_cars_post_types_init() {

	register_post_type( 'bizz_cars',
        array(
        	'label' 				=> __('Vehicles', 'bizzthemes'),
			'labels' 				=> array(	
				'name' 					=> __('Vehicles', 'bizzthemes'),
				'singular_name' 		=> __('Vehicles', 'bizzthemes'),
				'add_new' 				=> __('Add New', 'bizzthemes'),
				'add_new_item' 			=> __('Add New Vehicle', 'bizzthemes'),
				'edit' 					=> __('Edit', 'bizzthemes'),
				'edit_item' 			=> __('Edit Vehicle', 'bizzthemes'),
				'new_item' 				=> __('New Vehicle', 'bizzthemes'),
				'view_item'				=> __('View Vehicle', 'bizzthemes'),
				'search_items' 			=> __('Search Vehicles', 'bizzthemes'),
				'not_found' 			=> __('No Vehicles found', 'bizzthemes'),
				'not_found_in_trash' 	=> __('No Vehicles found in trash', 'bizzthemes'),
				'parent' 				=> __('Parent Vehicle', 'bizzthemes'),
			),
            'description' => __('This is where you can create new cars for your site.', 'bizzthemes'),
            'public' => true,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => true,
			'menu_icon' => 'dashicons-performance',
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'car_slug', 'car'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => true,
            'supports' => array(	
				'title','bizz_car_type_select','bizz_car_seats'
			)
        )
    );

}
add_action( 'init', 'bizz_cars_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_cars_edit_columns($columns){
	$columns['cb'] 						= '<input type=\'checkbox\' />';
	$columns['title'] 					= __('Vehicle Name', 'bizzthemes');
	$columns['bizz_car_type_select'] 	= __('Type', 'bizzthemes');
	$columns['bizz_car_seats'] 			= __('Seats', 'bizzthemes');
	$columns['bizz_car_doors'] 			= __('Doors', 'bizzthemes');
	$columns['bizz_car_transmission'] 	= __('Transmission', 'bizzthemes');
	//$columns['bizz_car_location'] 		= __('Location', 'bizzthemes');
	$columns['bizz_car_stock'] 			= __('Stock', 'bizzthemes');
	$columns['bizz_car_image'] 			= __('Image', 'bizzthemes');
		
	return $columns;
}
add_filter('manage_edit-bizz_cars_columns','bizz_cars_edit_columns');

function bizz_cars_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	$custom_car = get_post_custom($post->ID);
	$car_current_location = ( isset($custom_car["bizzthemes_car_location"][0]) ) ? $custom_car["bizzthemes_car_location"][0] : '';
	$car_current_location = get_page_by_path( $car_current_location, 'OBJECT', 'bizz_locations' );
	$car_current_location = ($car_current_location) ? $car_current_location->ID : null;
	switch ($column){
		case "bizz_car_type_select":
			$name = $custom["bizzthemes_car_type"][0];
			$terms = get_terms( 'bizz_cars_type', 'hide_empty=0' );
			foreach ( $terms as $term ) {
				if (!is_wp_error( $name ) && !empty( $name ) && !strcmp( $term->slug, $name ) ) {
					echo $term->name;
				}
			}
		break;
		case "bizz_car_seats":
			$seats = $custom["bizzthemes_car_seats"][0];
			if ($seats != '') { echo $seats; }
		break;
		case "bizz_car_doors":
			$doors = $custom["bizzthemes_car_doors"][0];
			if ($doors != '') { echo $doors; }
		break;
		case "bizz_car_transmission":
			$transmission = $custom["bizzthemes_car_transmission"][0];
			if ($transmission != '') { echo $transmission; }
		break;
		/*
		case "bizz_car_location":
			if ($car_current_location != '') { echo get_the_title($car_current_location); } else { echo '/'; }
		break;
		*/
		case "bizz_car_stock":
			echo ( isset($custom["bizzthemes_car_stock"][0]) ) ? $custom["bizzthemes_car_stock"][0] : 1;
		break;
		case "bizz_car_image":
			$car_img = ( isset($custom["bizzthemes_car_image"][0]) ) ? $custom["bizzthemes_car_image"][0] : get_template_directory_uri() . '/lib_theme/images/no-img.jpg';
			if ($car_img != '') { echo '<a href="'.$car_img.'"><img height="75" alt="" src="'.$car_img.'"></a>'; }
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_cars_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

/* Custom Post Type Taxonomy Setup */
/*------------------------------------------------------------------*/
function bizz_cars_taxonomy_init() {
	
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => __( 'Vehicle Types', 'bizzthemes' ),
		'singular_name' => __( 'Type', 'bizzthemes' ),
		'search_items' =>  __( 'Search Types', 'bizzthemes' ),
		'popular_items' => __( 'Popular Types', 'bizzthemes' ),
		'all_items' => __( 'All Types', 'bizzthemes' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Type', 'bizzthemes' ), 
		'update_item' => __( 'Update Type', 'bizzthemes' ),
		'add_new_item' => __( 'Add New Type', 'bizzthemes' ),
		'new_item_name' => __( 'New Type Name', 'bizzthemes' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'bizzthemes' ),
		'add_or_remove_items' => __( 'Add or remove types', 'bizzthemes' ),
		'choose_from_most_used' => __( 'Choose from the most used types', 'bizzthemes' ),
		'menu_name' => __( 'Types', 'bizzthemes' ),
	); 
	register_taxonomy('bizz_cars_type', array('bizz_cars', 'bizz_bookings'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_tagcloud' => false,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'type' ),
	));
	
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => __( 'Vehicle Extras', 'bizzthemes' ),
		'singular_name' => __( 'Extra', 'bizzthemes' ),
		'search_items' =>  __( 'Search Extras', 'bizzthemes' ),
		'popular_items' => __( 'Popular Extras', 'bizzthemes' ),
		'all_items' => __( 'All Extras', 'bizzthemes' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Extra', 'bizzthemes' ), 
		'update_item' => __( 'Update Extra', 'bizzthemes' ),
		'add_new_item' => __( 'Add New Extra', 'bizzthemes' ),
		'new_item_name' => __( 'New Extra Name', 'bizzthemes' ),
		'separate_items_with_commas' => __( 'Separate extras with commas', 'bizzthemes' ),
		'add_or_remove_items' => __( 'Add or remove extras', 'bizzthemes' ),
		'choose_from_most_used' => __( 'Choose from the most used extras', 'bizzthemes' ),
		'menu_name' => __( 'Extras', 'bizzthemes' ),
	); 
	register_taxonomy('bizz_cars_extra', array('bizz_cars', 'bizz_bookings'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_tagcloud' => false,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'extra' ),
	));

}
add_action( 'init', 'bizz_cars_taxonomy_init', 9 );

/* Modify multicheck field for pricing */
/*------------------------------------------------------------------*/
// CREATE
add_action( 'bizz_render_car_pricing', 'custom_bizz_render_car_pricing', 10, 2 );
function custom_bizz_render_car_pricing( $field, $meta ) {
	global $post;

	// search for type prices if price for vehicle is not saved
	$existing_type = get_post_meta($post->ID, 'bizzthemes_car_type', true);
	$args = array(
		'post_type' => 'bizz_pricing',
		'numberposts' => -1,
		'meta_query' => array(
			array(
				'key' => 'bizzthemes_price_type' ,
				'value' => $existing_type,
				'compare' => '=='
			),
		)
	);
	$pricing_posts = get_posts( $args );
	if( empty( $meta ) && $pricing_posts ) {
		foreach ( $pricing_posts as $pricing ) {
			$meta[] = $pricing->post_name;
		}
	}
	
	if ( ! is_array( $meta ) ) {
		$meta = array();
	}
	
	// Stop here if no pricing posts found
	if ( ! is_array( $field['options'] ) ) {
		return;
	}
		
	// output
	echo '<table>';
	echo '<tr>';
	echo '<td style="padding: 0 15px 0 0;">';
	echo '<ul>';
	$i = 1;
	foreach ( $field['options'] as $value => $name ) {
		// Append `[]` to the name to get multiple values
		// Use in_array() to check whether the current option should be checked
		echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], $i, '" value="', $value, '"', in_array( $value, $meta ) ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $name, '</label></li>';
		$i++;
	}
	echo '</ul>';
	echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
}

/* Modify multicheck box for locations */
/*------------------------------------------------------------------*/
// CREATE
add_action( 'bizz_render_location_multicheck', 'custom_bizz_render_multicheck_locations', 10, 2 );
function custom_bizz_render_multicheck_locations( $field, $meta ) {
	// Stop here if no pricing posts found
	if ( ! is_array( $field['options'] ) ) {
		return;
	}
	
	// locations (only for backwards compatibility)
	$locations_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1, 'suppress_filters' => false ) );
	$locations_options = array();
	foreach ($locations_posts as $location_post) {
		$locations_options[$location_post->ID] = $location_post->post_name;
	}
	
	// output
	if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
	echo '<ul>';
	$i = 1;
	foreach ( $field['options'] as $value => $name ) {
		$alt = isset( $locations_options[$value] ) ? $locations_options[$value] : '';
		echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], $i, '" value="', $value, '"', (in_array( $value, $meta ) || in_array( $alt, $meta )) ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $name, '</label></li>';
		$i++;
	}
	echo '</ul>';
	echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
}

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_meta_boxes', 'bizz_cars_metaboxes' );
function bizz_cars_metaboxes( $meta_boxes ) {
	global $booking_settings;
	
	// prefix
	$prefix = 'bizzthemes_';
	
	// get booking settings
	$opt_s  = $booking_settings->get_settings();

	// pricing
	$price_range = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('hour', 'bizzthemes') : __('day', 'bizzthemes');
	$price_range_s = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('hour', 'bizzthemes') : __('day', 'bizzthemes');
	$price_range_p = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('hours', 'bizzthemes') : __('days', 'bizzthemes');
	$pricing_posts = get_posts( array( 'post_type' => 'bizz_pricing', 'numberposts' => -1, 'suppress_filters' => false ) );
	$pricing_options = array();
	foreach ($pricing_posts as $pricing_post) {
		$daily_price = get_post_meta( $pricing_post->ID, 'bizzthemes_price_daily', true );
		$range_from = get_post_meta( $pricing_post->ID, 'bizzthemes_price_range_from', true );
		$range_to = get_post_meta( $pricing_post->ID, 'bizzthemes_price_range_to', true );
		$season_from = get_post_meta( $pricing_post->ID, 'bizzthemes_price_season_from', true );
		$season_to = get_post_meta( $pricing_post->ID, 'bizzthemes_price_season_to', true );
		$price_label = '';
		if ( $daily_price )
			$price_label .= '<big><b>'. get_bizz_currency($opt_s['pay_currency']) . $daily_price .' / '. $price_range .'</b></big>';
		if ( $range_from )
			$price_label .= '&nbsp;&nbsp;( ' . sprintf( _n( '%d %2$s', '%d %3$s', $range_from, 'bizzthemes' ), $range_from, $price_range_s, $price_range_p );
		if ( $range_to )
			$price_label .= ' - ' . sprintf( _n( '%d %2$s', '%d %3$s', $range_to, 'bizzthemes' ), $range_to, $price_range_s, $price_range_p ) . ' )';
		if ( $season_from )
			$price_label .= '&nbsp;&nbsp;&nbsp;' . date( get_option('date_format', 'y-m-d'), strtotime( $season_from ) );
		if ( $season_to )
			$price_label .= ' - ' . date( get_option('date_format', 'y-m-d'), strtotime( $season_to ) ) . '&nbsp;&nbsp;|';
		if ( $pricing_post->post_title )
			$price_label .= '&nbsp;&nbsp;&nbsp;'. $pricing_post->post_title;
		if ( $pricing_post->ID )
			$price_label .= '&nbsp;&nbsp;&nbsp;<small><a href="post.php?post='. $pricing_post->ID .'&action=edit">'. __('edit', 'bizzthemes') .'</a></small>';		
		// spit out the pricing label
		$pricing_options[$pricing_post->post_name] = $price_label;
	}
	
	// extras
	$extras_terms = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );
	$extras_options = array();
	foreach ($extras_terms as $extras_term) {
		$extras_options[$extras_term->slug] = $extras_term->name;
	}

	// locations
	$locations_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1, 'suppress_filters' => false ) );
	$locations_options = array();
	foreach ($locations_posts as $location_post) {
		$address = get_post_meta($location_post->ID, 'bizzthemes_location_address', true);
		$locations_options[$location_post->ID] = $location_post->post_title . (($address) ? ', ' . $address : '');
	}
	
	// types
	$type_terms = get_terms( 'bizz_cars_type', array( 'hide_empty' => 0 ) );
	$type_options = array();
	foreach ($type_terms as $type_term) {
		$type_options[$type_term->slug] = $type_term->name;
	}
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_cars_meta',
		'title' => __('Vehicle Details', 'bizzthemes'),
		'pages' => array( 'bizz_cars' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Vehicle Image', 'bizzthemes'),
				'desc' => __('Upload an image or enter an URL.', 'bizzthemes'),
				'id' => $prefix . 'car_image',
				'type' => 'file'
			),
			array(
				'name' => __('Vehicle Description', 'bizzthemes'),
				'id' => $prefix . 'car_description',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
			array(
				'name' => '<a href="edit.php?post_type=bizz_locations">'.__('Pickup Locations', 'bizzthemes').'</a>',
				'desc' => '<a href="post-new.php?post_type=bizz_locations">'.__('Add new', 'bizzthemes').'</a>',
				'id'      => $prefix . 'car_location',
				'type'    => 'location_multicheck',
				'multiple' => true,
				'options' => $locations_options,
			),
			array(
				'name' => '<a href="edit.php?post_type=bizz_locations">'.__('Return Locations', 'bizzthemes').'</a>',
				'desc' => __( 'When empty, same as Pickup Locations', 'bizzthemes' ),
				'id'      => $prefix . 'car_location_return',
				'type'    => 'location_multicheck',
				'multiple' => true,
				'options' => $locations_options,
			),
			array(
				'name' => '<a href="edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings">'.__('Vehicle Types', 'bizzthemes').'</a>',
				'desc' => '<a href="edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings">'.__('Add new', 'bizzthemes').'</a>',
				'id' => $prefix . 'car_type',
				'type' => 'multicheck',
				'options' => $type_options
			),
			array(
				'name' => __('Seats', 'bizzthemes'),
				'id' => $prefix . 'car_seats',
				'type' => 'radio_inline',
				'options' => apply_filters( 'bizz_car_seats', array(
					array('name' => '1', 'value' => '1'),
					array('name' => '2', 'value' => '2'),
					array('name' => '3', 'value' => '3'),
					array('name' => '4', 'value' => '4'),
					array('name' => '5', 'value' => '5'),
					array('name' => '6', 'value' => '6'),
					array('name' => '7', 'value' => '7'),
					array('name' => '8', 'value' => '8'),
					array('name' => '9', 'value' => '9'),
					array('name' => '10', 'value' => '10')
				) )
			),
			array(
				'name' => __('Doors', 'bizzthemes'),
				'id' => $prefix . 'car_doors',
				'type' => 'radio_inline',
				'options' => apply_filters( 'bizz_car_doors', array(
					array('name' => '2', 'value' => '2'),
					array('name' => '4', 'value' => '4'),
					array('name' => '5', 'value' => '5')					
				) )
			),
			array(
				'name' => __('Transmission', 'bizzthemes'),
				'id' => $prefix . 'car_transmission',
				'type' => 'radio_inline',
				'options' => array(
					array('name' => __('Manual', 'bizzthemes'), 'value' => 'manual'),
					array('name' => __('Automatic', 'bizzthemes'), 'value' => 'automatic')
				)
			),
			/*array(
				'name'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Available Vehicle Extras', 'bizzthemes').'</a>',
				'desc'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Add more', 'bizzthemes').'</a>',
				'id'      => $prefix . 'car_extras',
				'type'    => 'multicheck',
				'options' => $extras_options
			),*/
			array(
				'name' => __('Registration number', 'bizzthemes'),
				'id' => $prefix . 'car_registration',
				'type' => 'text_small'
			),
			array(
				'name' => __('Stock', 'bizzthemes'),
				'desc' => __('available vehicles for booking', 'bizzthemes'),
				'id' => $prefix . 'car_stock',
				'type' => 'select',
				'std' => '1',
				'options' => apply_filters( 'bizz_car_stock', array(
					array('name' => __('Out of stock', 'bizzthemes'), 'value' => 'out'),
					array('name' => '1', 'value' => '1'),
					array('name' => '2', 'value' => '2'),
					array('name' => '3', 'value' => '3'),
					array('name' => '4', 'value' => '4'),
					array('name' => '5', 'value' => '5'),
					array('name' => '6', 'value' => '6'),
					array('name' => '7', 'value' => '7'),
					array('name' => '8', 'value' => '8'),
					array('name' => '9', 'value' => '9'),
					array('name' => '10', 'value' => '10'),
					array('name' => '11', 'value' => '11'),
					array('name' => '12', 'value' => '12'),
					array('name' => '13', 'value' => '13'),
					array('name' => '14', 'value' => '14'),
					array('name' => '15', 'value' => '15'),
					array('name' => '16', 'value' => '16'),
					array('name' => '17', 'value' => '17'),
					array('name' => '18', 'value' => '18'),
					array('name' => '19', 'value' => '19'),
					array('name' => '20', 'value' => '20')
				) )
			),
			array(
				'name' => __('Vehicle deposit', 'bizzthemes'),
				'desc' => __('per rental', 'bizzthemes'),
				'id' => $prefix . 'car_deposit',
				'std' => $opt_s['pay_deposit'],
				'currency' => get_bizz_currency($opt_s['pay_currency']),
				'type' => 'text_money',
			),
			array(
				'name'    => '<a href="edit.php?post_type=bizz_pricing">'.__('Pricing', 'bizzthemes').'</a>',
				'desc'    => '<a href="edit.php?post_type=bizz_pricing">'.__('Add more', 'bizzthemes').'</a>',
				'id'      => $prefix . 'car_pricing',
				'type'    => 'car_pricing',
				'options' => $pricing_options
			),
		)
	);
	
	// deposit
	if ( isset( $opt_s['pay_deposit_select'] ) && ( $opt_s['pay_deposit_select'] != 'fixed_vehicle' ) ) {
		if ( isset( $meta_boxes[2]['fields'][9] ) ) {
			unset ( $meta_boxes[2]['fields'][9] );
		}
	}
		
	return $meta_boxes;
}

function car_remove_meta_boxes() {
	remove_meta_box( 'tagsdiv-bizz_cars_type', 'bizz_cars', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_type', 'bizz_bookings', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_extra', 'bizz_cars', 'side' );
	remove_meta_box( 'tagsdiv-bizz_cars_extra', 'bizz_bookings', 'side' );
}
add_action( 'admin_menu', 'car_remove_meta_boxes' );

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_tax_boxes', 'bizz_cars_taxboxes' );
function bizz_cars_taxboxes( $tax_boxes ) {
	global $booking_settings;
	
	// prefix
	$prefix = 'bizz_';
	
	// get booking settings
	$opt_s  = $booking_settings->get_settings();
	
	// range
	$price_range = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('per hour', 'bizzthemes') : __('per day', 'bizzthemes');
	
	// image
	$tax_boxes[] = array(
		'id' => 'bizzthemes_cars_type',
		'title' => __('Vehicle Types', 'bizzthemes'),
		'taxonomies' => array( 'bizz_cars_type' ), // taxonomies
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Type image', 'bizzthemes'),
				'desc' => '',
				'id' => $prefix . 'type_image',
				'type' => 'file'
			),
		)
	);
	
	// location
	$location_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1, 'suppress_filters' => false ) );
	$location_options = array();
	foreach ($location_posts as $location_post) {
		$address = get_post_meta($location_post->ID, 'bizzthemes_location_address', true);
		$location_options[] = array(
            'name' => $location_post->post_title . (($address) ? ', ' . $address : ''),
            'value' => $location_post->post_name
        );
	}
	
	$tax_boxes[] = array(
		'id' => 'bizzthemes_cars_extra',
		'title' => __('Vehicle Extras', 'bizzthemes'),
		'taxonomies' => array( 'bizz_cars_extra' ), // taxonomies
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Price', 'bizzthemes'),
				'desc' => __('per extra (without currency symbol)', 'bizzthemes'),
				'id' => $prefix . 'extra_price',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Max Cost', 'bizzthemes'),
				'desc' => __('per booking (without currency symbol)', 'bizzthemes'),
				'id' => $prefix . 'max_extra_price',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Quantity', 'bizzthemes'),
				'desc' => __('available extras per booking', 'bizzthemes'),
				'id' => $prefix . 'extra_count',
				'type' => 'select',
				'options' => array(
					array('name' => 1, 'value' => 1),
					array('name' => 2, 'value' => 2),
					array('name' => 3, 'value' => 3),
					array('name' => 4, 'value' => 4),
					array('name' => 5, 'value' => 5),
					array('name' => 6, 'value' => 6),
					array('name' => 7, 'value' => 7),
					array('name' => 8, 'value' => 8),
					array('name' => 9, 'value' => 9),
					array('name' => 10, 'value' => 10),
				)
			),
			array(
				'name' => __('Required', 'bizzthemes'),
				'desc' => __('this extra is required, will be auto-selected', 'bizzthemes'),
				'id' => $prefix . 'extra_required',
				'type' => 'checkbox'
			),
			array(
				'name' => __('Text field', 'bizzthemes'),
				'desc' => __('add additonal text field for user entry', 'bizzthemes'),
				'id' => $prefix . 'extra_field',
				'type' => 'checkbox'
			),
			array(
				'name' => __('Text field placeholder', 'bizzthemes'),
				'desc' => __('this text will only display, if text field is enabled', 'bizzthemes'),
				'id' => $prefix . 'extra_field_placeholder',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Bind to location', 'bizzthemes'),
				'desc' => __('will only be displayed with selected Pickup location', 'bizzthemes'),
				'id' => $prefix . 'extra_location',
				'type' => 'multicheck',
				'options' => $location_options
			),
			array(
				'name' => '',
				'desc' => __('will only be displayed with selected Return location', 'bizzthemes'),
				'id' => $prefix . 'extra_location_return',
				'type' => 'multicheck',
				'options' => $location_options
			),
			array(
				'name' => '',
				'desc' => __('only when Pickup location is different from Return location', 'bizzthemes'),
				'id' => $prefix . 'extra_location_diff',
				'type' => 'checkbox'
			),
			array(
				'name' => __('Price range', 'bizzthemes'),
				'desc' => '',
				'id' => $prefix . 'extra_price_s',
				'type' => 'radio_inline',
				'std' => 'rental',
				'options' => array(
					array('name' => __('per rental', 'bizzthemes'), 'value' => 'rental'),
					array('name' => $price_range, 'value' => 'day')				
				)
			),
			array(
				'name' => __('Extra image', 'bizzthemes'),
				'desc' => '',
				'id' => $prefix . 'extra_image',
				'type' => 'file'
			)
		)
	);
		
	return $tax_boxes;
}

/* Custom Taxonomy Columns Setup */
/*------------------------------------------------------------------*/
add_filter('manage_edit-bizz_cars_type_columns', 'add_bizz_cars_type_columns'); #add_filter( "manage_edit-{screen_id}_columns", "column_header_function" ) );  
function add_bizz_cars_type_columns($columns){
    global $booking_settings;
	
	$opt_s = $booking_settings->get_settings();
	$columns['image'] = __('Image', 'bizzthemes');
	$columns['cars'] = __('Available Vehicles', 'bizzthemes');
	
	if ( isset( $opt_s['pay_deposit_select'] ) && ( $opt_s['pay_deposit_select'] != 'fixed' ) )
		unset($columns['deposit']);
	
    return $columns;
}
add_action('manage_bizz_cars_type_custom_column', 'add_bizz_cars_type_column', 10, 3); # add_action( "manage_{tax_slug}_custom_column",  "populate_rows_function"), 10, 3  );
function add_bizz_cars_type_column( $value, $column, $term_id ){
	global $post, $booking_settings;
	
    switch ($column){
		case 'image':
			$term_img = get_option('taxonomy_'.$term_id.'_bizz_type_image');
			echo '<a href="'.$term_img.'"><img height="75" alt="" src="'.$term_img.'"></a>';
		break;
		case "cars":
			$term = get_term( $term_id, 'bizz_cars_type' );
			$args = array(
				'post_type' => 'bizz_cars',
				'meta_query' => array(
					array(
						'key' => 'bizzthemes_car_type',
						'value' => $term->slug,
					)
				)
			 );
			$return_cars = get_posts( $args );
			echo count($return_cars);
		break;
	}
}

add_filter('manage_edit-bizz_cars_extra_columns', 'add_bizz_cars_extra_columns'); #add_filter( "manage_edit-{screen_id}_columns", "column_header_function" ) );  
function add_bizz_cars_extra_columns($columns){
	$columns['price'] = __('Price', 'bizzthemes');
	$columns['range'] = __('Price range', 'bizzthemes');
    $columns['image'] = __('Image', 'bizzthemes');
    return $columns;
}
add_action('manage_bizz_cars_extra_custom_column', 'add_bizz_cars_extra_column', 10, 3); # add_action( "manage_{tax_slug}_custom_column",  "populate_rows_function"), 10, 3  );
function add_bizz_cars_extra_column( $value, $column, $term_id ){
    switch ($column){
		case 'price':
			global $booking_settings;
			// get booking settings
			$opt_s = $booking_settings->get_settings();
			$term_price = get_option('taxonomy_'.$term_id.'_bizz_extra_price');
			if ( $term_price == '-' ) {
				_e( 'Free', 'bizzthemes' );
			} else {
				echo get_bizz_currency($opt_s['pay_currency']) . $term_price;
			}
		break;
		case 'range':
			global $booking_settings;
			// get booking settings
			$opt_s = $booking_settings->get_settings();
			$term_range = get_option('taxonomy_'.$term_id.'_bizz_extra_price_s');
			$day_hour = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('per hour', 'bizzthemes') : __('per day', 'bizzthemes');
			$term_range = ( $term_range == 'rental' ) ? __('per rental', 'bizzthemes') : $day_hour;
			echo $term_range;
		break;
		case 'image':
			$term_img = get_option('taxonomy_'.$term_id.'_bizz_extra_image');
			echo '<a href="'.$term_img.'"><img height="75" alt="" src="'.$term_img.'"></a>';
		break;
	}
}

