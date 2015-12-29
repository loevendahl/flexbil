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
function bizz_locations_post_types_init() {

	register_post_type( 'bizz_locations',
        array(
        	'label' 				=> __('Locations', 'bizzthemes'),
			'labels' 				=> array(	
				'name' 					=> __('Locations', 'bizzthemes'),
				'singular_name' 		=> __('Locations', 'bizzthemes'),
				'add_new' 				=> __('Add New', 'bizzthemes'),
				'add_new_item' 			=> __('Add New Location', 'bizzthemes'),
				'edit' 					=> __('Edit', 'bizzthemes'),
				'edit_item' 			=> __('Edit Location', 'bizzthemes'),
				'new_item' 				=> __('New Location', 'bizzthemes'),
				'view_item'				=> __('View Location', 'bizzthemes'),
				'search_items' 			=> __('Search Locations', 'bizzthemes'),
				'not_found' 			=> __('No Locations found', 'bizzthemes'),
				'not_found_in_trash' 	=> __('No Locations found in trash', 'bizzthemes'),
				'parent' 				=> __('Parent Location', 'bizzthemes'),
			),
            'description' => __( 'This is where you can create new locations for your site.', 'bizzthemes' ),
            'public' => true,
            'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=bizz_bookings',
			'show_in_nav_menus' => true,
			'menu_icon' => 'dashicons-location-alt',
            'capability_type' => 'page',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => apply_filters( 'location_slug', 'location'), 'with_front' => false ),
            'query_var' => true,
            'has_archive' => true,
            'supports' => array(	
				'title',
				'page-attributes',
				// 'custom-fields'
			)
        )
    );

}
add_action( 'init', 'bizz_locations_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_locations_edit_columns($columns){
	$columns['cb'] 						= '<input type=\'checkbox\' />';
	$columns['title'] 					= __('Location Title', 'bizzthemes');
	$columns['bizz_location_address'] 	= __('Location Address', 'bizzthemes');
	$columns['bizz_location_city'] 		= __('City', 'bizzthemes');
	$columns['bizz_location_email'] 	= __('Contact Email', 'bizzthemes');
	$columns['bizz_location_phone'] 	= __('Contact Phone', 'bizzthemes');
	$columns['bizz_location_cars'] 		= __('Available Vehicles', 'bizzthemes');
	$columns['bizz_location_dealer'] 		= __('Dealer ID', 'bizzthemes');
	
	return $columns;
}
add_filter('manage_edit-bizz_locations_columns','bizz_locations_edit_columns');

function bizz_locations_custom_columns($column){
	global $post;

	$custom = get_post_custom();
	switch ($column){
		case "bizz_location_address":
			$address = $custom["bizzthemes_location_address"][0];
			if ($address != '') { echo $address; }
		break;
		case "bizz_location_city":
			$city = ( isset($custom["bizzthemes_location_city"][0]) ) ? $custom["bizzthemes_location_city"][0] : '';
			if ($city != '') { echo $city; }
		break;
		case "bizz_location_email":
			$email = $custom["bizzthemes_location_email"][0];
			if ($email != '') { echo $email; }
		break;
		case "bizz_location_phone":
			$phone = $custom["bizzthemes_location_phone"][0];
			if ($phone != '') { echo $phone; }
		break;
		case "bizz_location_dealer":
			$dealerid =( isset( $custom["bizzthemes_location_dealer"][0]) ) ? $custom["bizzthemes_location_dealer"][0] : '';
			if ($dealerid != '') { echo $dealerid; }
		break;
		case "bizz_location_cars":
			$args = array(
				'post_type' => 'bizz_cars',
				'numberposts' => -1,
				'meta_query' => array(
					array(
						'key' => 'bizzthemes_car_location',
						'value' => $post->post_name,
					)
				)
			 );
			$return_cars = get_posts( $args );
			echo count($return_cars);
		break;
	}
}
add_action('manage_pages_custom_column', 'bizz_locations_custom_columns', 1); # 'manage_pages_custom_column' for pages, 'manage_posts_custom_column' for posts

/* Remove links from post actions */
/*------------------------------------------------------------------*/
add_filter( 'page_row_actions', 'location_remove_row_actions', 10, 2 );
function location_remove_row_actions( $actions, $post ) {
	global $current_screen;
	
	if( isset( $current_screen->post_type ) && $current_screen->post_type == 'bizz_locations' ) {
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
// CREATE
add_action( 'bizz_render_hours', 'custom_bizz_render_hours', 10, 2 );
function custom_bizz_render_hours( $field, $meta ) {
	global $post;
	
	$time = array('00:00', '00:30','01:00', '01:30','02:00', '02:30','03:00', '03:30','04:00', '04:30','05:00', '05:30','06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '23:00', '23:30');
	// default
	$default_open = (isset($field['open_default'])) ? $field['open_default'] : '09:00';
	$default_close = (isset($field['close_default'])) ? $field['close_default'] : '17:00';
	$default_closed = (isset($field['closed_default'])) ? $field['closed_default'] : false;
	// saved
	$existing_open = get_post_meta($post->ID, $field['id'].'_open', true);
		if ( !$existing_open ) update_post_meta($post->ID, $field['id'].'_open', $default_open);
	$existing_close = get_post_meta($post->ID, $field['id'].'_close', true);
		if ( !$existing_close ) update_post_meta($post->ID, $field['id'].'_close', $default_close);
	$existing_closed = get_post_meta($post->ID, $field['id'].'_closed', true);
		if ( !isset($existing_closed) ) update_post_meta($post->ID, $field['id'].'_closed', $default_closed);
	
	echo _e('Opening time') . '&nbsp;&nbsp;';
	echo '<select name="', $field['id'], '_open" id="', $field['id'], '">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_open) ? (($existing_open == $label) ? ' selected="selected"' : '') : (($default_open == $label) ? ' selected="selected"' : '');
		$time_format = date(get_option('time_format', 'H:i'), strtotime($label));
		echo '	<option value="' . $label . '" ' . $checked . ' />' . $time_format . '</option>' . "\n";
	}
	echo '</select>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo _e('Closing time') . '&nbsp;&nbsp;';
	echo '<select name="', $field['id'], '_close" id="', $field['id'], '">';
	foreach ($time as $option_value => $label) {
		$checked = ($existing_close) ? (($existing_close == $label) ? ' selected="selected"' : '') : (($default_close == $label) ? ' selected="selected"' : '');
		$time_format = date(get_option('time_format', 'H:i'), strtotime($label));
		echo '	<option value="' . $label . '" ' . $checked . ' />' . $time_format . '</option>' . "\n";
	}
	echo '</select>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo _e('Closed') . '&nbsp;&nbsp;';
	$checked_closed = ($existing_closed) ? ' checked="checked"' : ((!isset($existing_closed) && $default_closed) ? ' checked="checked"' : false);
	echo '<input type="checkbox" name="', $field['id'], '_closed" id="', $field['id'], '" ', $checked_closed, ' />';
	echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
}

add_action( 'bizz_render_offdates', 'custom_bizz_render_offdates', 10, 2 );
function custom_bizz_render_offdates( $field, $meta ) {	
	global $post;
	
	$repeatable_offdates = get_post_meta( $post->ID, $field['id'] . '_closed', true );
?>
	<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#add-row').on('click', function() {
		var row = $('.row-empty.screen-reader-text').clone(true);
		row.removeClass('row-empty screen-reader-text');
		row.find('.offdate_datepicker').datepicker("destroy");
		row.find('.offdate_datepicker').removeAttr('id');
		row.find('.offdate_datepicker').datepicker({ 
			firstDay: 1, 
			dateFormat: 'yy-mm-dd' 
		});
		row.insertBefore('.repeatable_offdates .row:last');
		return false;
	});
	$('.remove-row').on('click', function() {
		$(this).parents('.row').not('.row:first-child').remove();
		return false;
	});
	$('.offdate_datepicker').datepicker({ 
		firstDay: 1, 
		dateFormat: 'yy-mm-dd' 
	});
});
	</script>
<?php
	echo '<div class="repeatable_offdates">';
		if ( $repeatable_offdates ) {
			$i = 1;
			foreach ( $repeatable_offdates as $offdate ) {
				echo '<div class="row row-saved" style="border-bottom: 1px solid #e9e9e9; margin-bottom: 15px; padding-bottom: 15px;">';
				echo _e('Start date') . '&nbsp;&nbsp;';
				echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_start[]" id="', $field['id'] . $i, '_start" value="', '' !== $offdate['start'] ? $offdate['start'] : '', '" />';
				echo _e('End date') . '&nbsp;&nbsp;';
				echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_end[]" id="', $field['id'] . $i, '_end" value="', '' !== $offdate['end'] ? $offdate['end'] : '', '" />';
				echo '<a class="button remove-row" href="#">' . __('Remove', 'bizzthemes') . '</a>';
				echo '</div>';
				$i++;
			}
		} else {
			echo '<div class="row row-blank" style="border-bottom: 1px solid #e9e9e9; margin-bottom: 15px; padding-bottom: 15px;">';
			echo _e('Start date') . '&nbsp;&nbsp;';
			echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_start[]" value="" />';
			echo _e('End date') . '&nbsp;&nbsp;';
			echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_end[]" value="" />';
			echo '<a class="button remove-row" href="#">' . __('Remove', 'bizzthemes') . '</a>';
			echo '</div>';
		}
		//* empty hidden one for jQuery
		echo '<div class="row row-empty screen-reader-text" style="border-bottom: 1px solid #e9e9e9; margin-bottom: 15px; padding-bottom: 15px;">';
		echo _e('Start date') . '&nbsp;&nbsp;';
		echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_start[]" value="" />';
		echo _e('End date') . '&nbsp;&nbsp;';
		echo '<input class="bizz_text_small offdate_datepicker" type="text" name="', $field['id'], '_end[]" value="" />';
		echo '<a class="button remove-row" href="#">' . __('Remove', 'bizzthemes') . '</a>';
		echo '</div>';
		
		//* Action row
		echo '<div class="action">';
		echo '<a id="add-row" class="button" href="#">Add another</a>';
		echo '</div>';
	echo '</div>';
}

// VALIDATE and SAVE
add_filter( 'bizz_validate_hours', 'custom_bizz_validate_hours', 10, 3 );
function custom_bizz_validate_hours( $new, $post_id, $field ) {
    
	$array_name[] = $field['id'] . "_open";
	$array_name[] .= $field['id'] . "_close";
	$array_name[] .= $field['id'] . "_closed";
	
	foreach ( $array_name as $key => $name ) {
		
		$old = get_post_meta( $post_id, $name );
		$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

		if ( $new && $new != $old )
			update_post_meta( $post_id, $name, $new );
		elseif ( '' == $new && $old )
			update_post_meta( $post_id, $name, null );
		
	}

}

add_filter( 'bizz_validate_offdates', 'custom_bizz_validate_offdates', 10, 3 );
function custom_bizz_validate_offdates( $new, $post_id, $field ) {
	
	update_post_meta( $post_id, 'tttttt', $field['id'] );
	update_post_meta( $post_id, $field['id'] . '_closed', 'ddddd' );
	
	$old = get_post_meta( $post_id, $field['id'] . '_closed', true );
	$new = array();
	$start = $_POST[ $field['id'] . '_start' ];
	$end = $_POST[ $field['id'] . '_end' ];
	
	$count = count( $start );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $start[$i] != '' ) :
			$new[$i]['start'] = stripslashes( $start[$i] );
		if ( $end[$i] != '' )
			$new[$i]['end'] = stripslashes( $end[$i] );
		endif;
	}
	if ( $new && $new != $old ) {
		update_post_meta( $post_id, $field['id'] . '_closed', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_post_meta( $post_id, $field['id'] . '_closed', $old );
	}

}

add_filter( 'bizz_meta_boxes', 'bizz_locations_metaboxes' );
function bizz_locations_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_locations_meta',
		'title' => __('Location Details', 'bizzthemes'),
		'pages' => array( 'bizz_locations' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Country', 'bizzthemes'),
				'id' => $prefix . 'location_country',
				'type' => 'select',
				'options' => bizz_country_list()
			),
			array(
				'name' => __('State/Province/Region', 'bizzthemes'),
				'id' => $prefix . 'location_state',
				'type' => 'text_medium'
			),
			array(
				'name' => __('City', 'bizzthemes'),
				'id' => $prefix . 'location_city',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Postcode/ZIP', 'bizzthemes'),
				'id' => $prefix . 'location_zip',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Address', 'bizzthemes'),
				'id' => $prefix . 'location_address',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Email', 'bizzthemes'),
				'id' => $prefix . 'location_email',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Phone', 'bizzthemes'),
				'id' => $prefix . 'location_phone',
				'type' => 'text_medium'
			),
			array(
				'name' => __('Car dealer', 'bizzthemes'),
				'id' => $prefix . 'location_dealer',
				'type' => 'text_small',
				'std' => get_current_user_id()	
			 ),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_hours_meta',
		'title' => __('Business Hours', 'bizzthemes'),
		'pages' => array( 'bizz_locations' ), // post type
		'context' => 'normal',
		'priority' => 'default',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
	            'name' => __('Monday', 'bizzthemes'),
	            'id' => $prefix . 'hours_monday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Tuesday', 'bizzthemes'),
	            'id' => $prefix . 'hours_tuesday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Wednesday', 'bizzthemes'),
	            'id' => $prefix . 'hours_wednesday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Thursday', 'bizzthemes'),
	            'id' => $prefix . 'hours_thursday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Friday', 'bizzthemes'),
	            'id' => $prefix . 'hours_friday',
				'open_default' => '09:00',
				'close_default' => '22:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Saturday', 'bizzthemes'),
	            'id' => $prefix . 'hours_saturday',
				'open_default' => '09:00',
				'close_default' => '17:00',
				'closed_default' => false,
	            'type' => 'hours'
	        ),
			array(
	            'name' => __('Sunday', 'bizzthemes'),
	            'id' => $prefix . 'hours_sunday',
				'open_default' => '09:00',
				'close_default' => '13:00',
				'closed_default' => true,
	            'type' => 'hours'
	        ),
		)
	);
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_offdates_meta',
		'title' => __('Business Closed Dates', 'bizzthemes'),
		'pages' => array( 'bizz_locations' ), // post type
		'context' => 'normal',
		'priority' => 'default',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
	            'name' => __('Closed Dates', 'bizzthemes'),
	            'id' => $prefix . 'offdates',
	            'type' => 'offdates'
	        ),
		)
	);
		
	return $meta_boxes;
}

