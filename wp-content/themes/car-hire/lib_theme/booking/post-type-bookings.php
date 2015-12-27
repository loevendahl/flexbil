<?php



/*



  FILE STRUCTURE:



- Custom post type icons

- Custom Post Types Init

- Columns for post types

- Custom Post Type Filters

- Custom Post Type Metabox Setup



*/



/* Custom post type init */

/*------------------------------------------------------------------*/

add_action( 'init', 'bizz_bookings_post_types_init' );

function bizz_bookings_post_types_init() {



	register_post_type( 'bizz_bookings',

		array(

			'label' 				=> __('Bookings', 'bizzthemes'),

			'labels' 				=> array(

				'name_admin_bar' 		=> __('Booking', 'bizzthemes'),

				'name' 					=> __('Bookings', 'bizzthemes'),

				'singular_name' 		=> __('Bookings', 'bizzthemes'),

				'add_new' 				=> __('Add New', 'bizzthemes'),

				'add_new_item' 			=> __('Add New booking', 'bizzthemes'),

				'edit' 					=> __('Edit', 'bizzthemes'),

				'edit_item' 			=> __('Edit booking', 'bizzthemes'),

				'new_item' 				=> __('New booking', 'bizzthemes'),

				'view_item'				=> __('View booking', 'bizzthemes'),

				'search_items' 			=> __('Search bookings', 'bizzthemes'),

				'not_found' 			=> __('No bookings found', 'bizzthemes'),

				'not_found_in_trash' 	=> __('No bookings found in trash', 'bizzthemes'),

				'parent' 				=> __('Parent booking', 'bizzthemes'),

			),

			'description' => __('This is where you can create new bookings for your site.', 'bizzthemes'),

			'public' => false,

			'show_ui' => true,

			'show_in_nav_menus' => false,

			'menu_icon' => 'dashicons-calendar',

			'capability_type' => 'post',

			'publicly_queryable' => false,

			'exclude_from_search' => true,

			'hierarchical' => false,

			'rewrite' => array( 'slug' => apply_filters( 'booking_slug', 'booking'), 'with_front' => false ),

			'query_var' => true,

			'has_archive' => true,

			'supports' => array( 

				'title',

				// 'custom-fields'

			),

		)

	);

  

}



/* Manage columns name */

/*------------------------------------------------------------------*/

add_filter('manage_edit-bizz_bookings_columns','bizz_bookings_edit_columns');

function bizz_bookings_edit_columns($columns){

	$columns['cb'] 						= '<input type=\'checkbox\' />';

	$columns['title'] 					= __('Title', 'bizzthemes');

	$columns['bizz_book_track'] 		= __('Tracking ID', 'bizzthemes');

	$columns['bizz_book_customer'] 		= __('Customer', 'bizzthemes');

	$columns['bizz_book_start'] 		= __('Start Date', 'bizzthemes');

	$columns['bizz_book_return'] 		= __('Return Date', 'bizzthemes');

	$columns['bizz_book_car'] 			= __('Vehicle', 'bizzthemes');

	$columns['bizz_book_status'] 		= __('Status', 'bizzthemes');

	// $columns['bizz_book_payment'] 	= __('Payment', 'bizzthemes');

	

	return $columns;

}



/* Manage columns search by custom field */

/*------------------------------------------------------------------*/

add_action( 'load-edit.php', 'bizz_bookings_load' );

function bizz_bookings_load() {

	// Extend search to include 'description' field

	if ( isset( $_GET['post_type'] ) && $_GET['post_type']=='bizz_bookings' && isset( $_GET['s'] ) ) {

		add_filter( 'posts_join',      'pd_description_search_join' );

		add_filter( 'posts_where',     'pd_description_search_where' );

		add_filter( 'posts_groupby',   'pd_search_dupe_fix' );

	}

}



function pd_description_search_join ($join){

	global $wpdb;

	

	$join .='LEFT JOIN '.$wpdb->postmeta. ' as pdpm ON '. $wpdb->posts . '.ID = pdpm.post_id ';

	

	return $join;

}



function pd_description_search_where( $where ){

	global $wpdb;

	

	$where = preg_replace(

	"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",

	"(".$wpdb->posts.".post_title LIKE $1) OR (pdpm.meta_value LIKE $1)", $where );

	

	return $where;

}



function pd_search_dupe_fix($groupby) {

	global $wpdb;



	$groupby = "$wpdb->posts.ID";



	return $groupby;

}



/* Manage columns content */

/*------------------------------------------------------------------*/

add_action('manage_posts_custom_column', 'bizz_bookings_custom_columns', 2);

function bizz_bookings_custom_columns($column){

  global $post;

  switch ($column){

    case "bizz_book_track":

      $custom = get_post_custom();

      $custom = ( isset($custom["bizzthemes_bookings_track"][0]) ) ? $custom["bizzthemes_bookings_track"][0] : '';

      if ($custom != '') { echo $custom; }

    break;

    case "bizz_book_customer":

      $custom = get_post_custom();

      $custom1 = $custom["bizzthemes_bookings_fname"][0];

      $custom2 = $custom["bizzthemes_bookings_lname"][0];

      if ($custom != '') { echo $custom1.' '.$custom2; }

    break;

    case "bizz_book_start":

      $custom = get_post_custom();

      $custom1 = $custom["bizzthemes_bookings_date1"][0];

      $custom2 = $custom["bizzthemes_bookings_date1_time"][0];

      if ($custom1 != '') { echo date(get_option('date_format', 'y-m-d'), strtotime($custom1)); }

      if ($custom2 != '') { echo ', '.date(get_option('time_format', 'H:i'), strtotime($custom2)); }

    break;

    case "bizz_book_return":

      $custom = get_post_custom();

      $custom1 = $custom["bizzthemes_bookings_date2"][0];

      $custom2 = $custom["bizzthemes_bookings_date2_time"][0];

      if ($custom1 != '') { echo date(get_option('date_format', 'y-m-d'), strtotime($custom1)); }

      if ($custom2 != '') { echo ', '.date(get_option('time_format', 'H:i'), strtotime($custom2)); }

    break;

    case "bizz_book_car":

      $custom = get_post_custom();

      $custom = $custom["bizzthemes_bookings_car"][0];

      if ($custom != '') { echo '<a href="post.php?post='.$custom.'&action=edit" title="'.get_the_title($custom).'">'.get_the_title($custom).'</a>'; }

    break;

    case "bizz_book_status":

      $custom = get_post_custom();

      $custom = ( isset($custom["bizzthemes_bookings_status"][0]) ) ? $custom["bizzthemes_bookings_status"][0] : 'pending';

      if ($custom == 'pending')

        echo "<span style='color: #FF8000;'>".__('Pending', 'bizzthemes')."</span>";

      elseif ($custom == 'approved')

        echo "<span style='color: green;'>".__('Approved', 'bizzthemes')."</span>";

      elseif ($custom == 'completed')

        echo "<span style='color: green;'>".__('Approved', 'bizzthemes')."</span>";

      elseif ($custom == 'cancelled')

        echo "<span style='color: red;'>".__('Cancelled', 'bizzthemes')."</span>";

      elseif ($custom == 'refunded')

        echo "<span style='color: red;'>".__('Refunded', 'bizzthemes')."</span>";

    break;

    

  }

}



/* Manage columns sorting */

/*------------------------------------------------------------------*/

add_filter("manage_edit-bizz_bookings_sortable_columns", 'bizz_bookings_columns_sort');

function bizz_bookings_columns_sort($columns) {

	$custom = array(

		'bizz_book_start'		=> 'bizz_book_start',

		'bizz_book_return'		=> 'bizz_book_return',

		'bizz_book_customer'	=> 'bizz_book_customer',

		'bizz_book_car'			=> 'bizz_book_car',

	);

	return wp_parse_args($custom, $columns);

}



add_filter( 'posts_clauses', 'manage_wp_posts_be_qe_posts_clauses', 1, 2 );

function manage_wp_posts_be_qe_posts_clauses( $pieces, $query ) {

	global $wpdb;

	

	if( ! is_admin() ) {

		return $pieces;

	}



	if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

		$order = strtoupper( $query->get( 'order' ) );

		if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {

			$order = 'ASC';

		}

		switch( $orderby ) {

			case 'bizz_book_start':

				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta as wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'bizzthemes_bookings_date1'";

				$pieces[ 'orderby' ] = "STR_TO_DATE( wp_rd.meta_value, '%Y-%m-%d' ) $order, " . $pieces[ 'orderby' ];

			break;

			case 'bizz_book_return':

				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta as wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'bizzthemes_bookings_date2'";

				$pieces[ 'orderby' ] = "STR_TO_DATE( wp_rd.meta_value, '%Y-%m-%d' ) $order, " . $pieces[ 'orderby' ];

			break;

			case 'bizz_book_customer':

				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta as wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'bizzthemes_bookings_fname'";

				$pieces[ 'orderby' ] = "wp_rd.meta_value $order, " . $pieces[ 'orderby' ];

			break;

			case 'bizz_book_car':

				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta as wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'bizzthemes_bookings_car'";

				$pieces[ 'orderby' ] = "wp_rd.meta_value $order, " . $pieces[ 'orderby' ];

			break;

		}

	}



	return $pieces;

}



/* Remove links from post actions */

/*------------------------------------------------------------------*/

add_filter( 'post_row_actions', 'booking_remove_row_actions', 10, 2 );

function booking_remove_row_actions( $actions, $post ) {



  if( $post->post_type == 'bizz_bookings' ) {

    // unset( $actions['edit'] );

    unset( $actions['view'] );

    // unset( $actions['trash'] );

    unset( $actions['inline hide-if-no-js'] );

    //$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit', 'bizzthemes' );

  }

  

  return $actions;

}



/* Hook into save post action */

/*------------------------------------------------------------------*/

add_action('post_updated', 'booking_save_post');

function booking_save_post($id) {

    global $post;



	$post_type = ( isset($post)  ? get_post_type( $post->ID ) : '');



	if ( $post_type == 'bizz_bookings' ) {

	

		$status = $_POST['bizzthemes_bookings_status'];

		$saved_status = get_post_meta( $post->ID, 'bizzthemes_bookings_status', true );

		$status_change = ( $status != $saved_status  ? true : false);



		if ( $status_change && ( $status == 'approved' || $status == 'cancelled' || $status == 'refunded' ) ) {

			booking_send_notification( $status, $_POST);

		}



		if ( $status_change && ( $status == 'approved' ) ) {

			booking_send_notification( 'reminder', $_POST );

		}



	}



}



/* Send notification */

/*------------------------------------------------------------------*/

function booking_send_notification( $status = '', $bookopts = '', $convert = false ) {



	if ( $convert ) {

		$bookopts['bizzthemes_bookings_track'] 				= $bookopts['tracking_id'];

		$bookopts['bizzthemes_car_pay_total'] 				= $bookopts['pay_total'];

		$bookopts['bizzthemes_car_pay_deposit'] 			= $bookopts['pay_deposit'];

		$bookopts['bizzthemes_car_pay_car'] 				= $bookopts['pay_car'];

		$bookopts['bizzthemes_car_pay_extras'] 				= $bookopts['pay_extras'];

		$bookopts['bizzthemes_car_pay_tax'] 				= $bookopts['pay_tax'];

		$bookopts['bizzthemes_bookings_car'] 				= $bookopts['car'];

		$bookopts['bizzthemes_bookings_dealer_email_id']	= $bookopts['car_dealer_email_id'];

		$bookopts['bizzthemes_bookings_car_count'] 			= $bookopts['car_count'];

		$bookopts['bizzthemes_bookings_extras'] 			= $bookopts['extras'];

		$bookopts['bizzthemes_bookings_pickup'] 			= $bookopts['pickup_location'];

		$bookopts['bizzthemes_bookings_return'] 			= $bookopts['return_location'];

		$bookopts['bizzthemes_bookings_date1'] 				= $bookopts['pickup_date'];

		$bookopts['bizzthemes_bookings_date1_time'] 		= $bookopts['pickup_hour'];

		$bookopts['bizzthemes_bookings_date2'] 				= $bookopts['return_date'];

		$bookopts['bizzthemes_bookings_date2_time'] 		= $bookopts['return_hour'];

		$bookopts['bizzthemes_bookings_flight'] 			= $bookopts['flight'];

		$bookopts['bizzthemes_bookings_ctitle'] 			= $bookopts['customer_title'];

		$bookopts['bizzthemes_bookings_fname'] 				= $bookopts['customer_fname'];

		$bookopts['bizzthemes_bookings_lname'] 				= $bookopts['customer_lname'];

		$bookopts['bizzthemes_bookings_email'] 				= $bookopts['customer_email'];

		$bookopts['bizzthemes_bookings_phone'] 				= $bookopts['customer_phone'];

		$bookopts['bizzthemes_bookings_scontact'] 			= $bookopts['customer_contact_option'];

		$bookopts['bizzthemes_bookings_country'] 			= $bookopts['customer_country'];

		$bookopts['bizzthemes_bookings_state'] 				= $bookopts['customer_state'];

		$bookopts['bizzthemes_bookings_city'] 				= $bookopts['customer_city'];

		$bookopts['bizzthemes_bookings_zip'] 				= $bookopts['customer_zip'];

		$bookopts['bizzthemes_bookings_address'] 			= $bookopts['customer_address'];

		$bookopts['bizzthemes_bookings_driver_age'] 		= $bookopts['customer_driver_age'];

		$bookopts['bizzthemes_bookings_driver_birth'] 		= $bookopts['customer_driver_birth'];

		$bookopts['bizzthemes_bookings_driver_license'] 	= $bookopts['customer_driver_license'];

		$bookopts['bizzthemes_bookings_driver_country'] 	= $bookopts['customer_driver_country'];

		$bookopts['bizzthemes_bookings_driver_issue'] 		= $bookopts['customer_driver_issue'];

		$bookopts['bizzthemes_bookings_driver_expiry'] 		= $bookopts['customer_driver_expiry'];

		$bookopts['bizzthemes_bookings_driver_accidents'] 	= $bookopts['customer_driver_accidents'];

		$bookopts['bizzthemes_bookings_number_passengers'] 	= $bookopts['customer_number_passengers'];

		$bookopts['bizzthemes_bookings_comm_que'] 			= $bookopts['customer_comments'];

	}

	else {

		// get location ID by slug

		$bookopts['bizzthemes_bookings_pickup'] 			= get_page_by_path( $bookopts['bizzthemes_bookings_pickup'], OBJECT, 'bizz_locations' );

		$bookopts['bizzthemes_bookings_return'] 			= get_page_by_path( $bookopts['bizzthemes_bookings_return'], OBJECT, 'bizz_locations' );

	}

	  

	// EMAIL NOTIFICATION

	if ( $status == 'approved' || $status == 'cancelled' || $status == 'refunded' || $status == 'customer' ) {

		booking_send_notification_email( $status, $bookopts );

	}

	

	// Canel EMAIL REMINDER

	if ( $status == 'cancelled' || $status == 'refunded' ) {

		wp_clear_scheduled_hook( 'bizz_send_prior_email', array( $bookopts['bizzthemes_bookings_track'] ) );

		wp_clear_scheduled_hook( 'bizz_send_after_email', array( $bookopts['bizzthemes_bookings_track'] ) );

	}

  

	// EMAIL REMINDER

	if ( $status == 'reminder' ) {

		

		// get booking settings

		global $booking_settings;

		$opt_b = $booking_settings->get_settings();

  

		// Convert start time from local time to GMT since WP Cron sends based on GMT

		$start_time_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $bookopts['bizzthemes_bookings_date1'] .' '. $bookopts['bizzthemes_bookings_date1_time'] ) ) ) . ' GMT' );

		$end_time_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $bookopts['bizzthemes_bookings_date2'] .' '. $bookopts['bizzthemes_bookings_date2_time'] ) ) ) . ' GMT' );



		// Set reminder time for 1 day before event start time

		$time_prior_event = apply_filters('bizz_reminder_time_prior', 24 * 60 * 60); // 24 hours * 60 minutes * 60 seconds

		$reminder_time_prior = $start_time_gmt - $time_prior_event;

				

		// Set reminder time for 1 day after event start time

		$time_after_event = apply_filters('bizz_reminder_time_after', 24 * 60 * 60); // 24 hours * 60 minutes * 60 seconds

		$reminder_time_after = $end_time_gmt - $time_after_event;

		

		/* this works:

		$startDate = strtotime( $bookopts['bizzthemes_bookings_date1'] .' '. $bookopts['bizzthemes_bookings_date1_time'] );

		$startDate = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $startDate ) ) . ' GMT' );

		$time_prior_event = apply_filters('bizz_reminder_time_prior', 24 * 60 * 60); // 24 hours * 60 minutes * 60 seconds

		$startDate = $startDate - $time_prior_event;

		wp_schedule_single_event( $reminder_time_prior, 'bizz_test_prior', array( $bookopts['bizzthemes_bookings_track'] ) );	

		*/



		// Schedule the reminder

		if ( $opt_b['customer_notifications_prior'] ) {

			wp_clear_scheduled_hook( 'bizz_send_prior_email', array( $bookopts['bizzthemes_bookings_track'] ) );

			wp_schedule_single_event( $reminder_time_prior, 'bizz_send_prior_email', array( $bookopts['bizzthemes_bookings_track'] ) );

		}

			

		if ( $opt_b['customer_notifications_after'] ) {

			wp_clear_scheduled_hook( 'bizz_send_after_email', array( $bookopts['bizzthemes_bookings_track'] ) );

			wp_schedule_single_event( $reminder_time_after, 'bizz_send_after_email', array( $bookopts['bizzthemes_bookings_track'] ) );

		}

	

	}



}



// Hook our function, bizz_send_event_reminder_email(), into the action bizz_send_reminder_email

// Use http://wordpress.org/plugins/wp-crontrol/ to visually controll the event reminder schedule

add_action( 'bizz_send_prior_email', 'bizz_send_event_prior_email' );

function bizz_send_event_prior_email( $tracking_id = '' ) {



	if ( ! empty( $tracking_id ) ) {

	

		$args = array(

			'post_type' => 'bizz_bookings',

			'numberposts' => 1,

			'fields' => 'ids',

			'meta_key' => 'bizzthemes_bookings_track',

			'meta_value' => $tracking_id,

		);

		$booking_posts = get_posts( $args );

		$booking_custom = get_post_custom( $booking_posts[0] );

		foreach ( $booking_custom as $key => $value ) {

			$bookopts[$key] = $value[0];

		}

		

		booking_send_notification_email( 'prior', $bookopts );

	

	}

	

}



// Hook our function, bizz_send_event_reminder_email(), into the action bizz_send_reminder_email

// Use http://wordpress.org/plugins/wp-crontrol/ to visually controll the event reminder schedule

add_action( 'bizz_send_after_email', 'bizz_send_event_after_email' );

function bizz_send_event_after_email( $tracking_id = '' ) {

	if ( ! empty( $tracking_id ) ) {

	

		$args = array(

			'post_type' => 'bizz_bookings',

			'numberposts' => 1,

			'fields' => 'ids',

			'meta_key' => 'bizzthemes_bookings_track',

			'meta_value' => $tracking_id,

		);

		$booking_posts = get_posts( $args );

		$booking_custom = get_post_custom( $booking_posts[0] );

		foreach ( $booking_custom as $key => $value ) {

			$bookopts[$key] = $value[0];

		}

		

		booking_send_notification_email( 'after', $bookopts );

	

	}

}



/* Send notification email */

/*------------------------------------------------------------------*/

function booking_send_notification_email( $status = '', $bookopts = '' ) {

	global $booking_settings;

	

	// get booking settings

	$opt_b = $booking_settings->get_settings();

	

	if ( ! empty( $status ) ) {

	

		// Locale date and time strings			

		$date_format = get_option( 'date_format', 'Y-m-d' );

		$time_format = get_option( 'time_format', 'H:i' );



		// Date Time Format

		$pickup_date_format = date(get_option( 'date_format' ), strtotime($bookopts['bizzthemes_bookings_date1']));

		$pickup_time_format = date(get_option( 'time_format' ), strtotime($bookopts['bizzthemes_bookings_date1_time']));

		$return_date_format = date(get_option( 'date_format' ), strtotime($bookopts['bizzthemes_bookings_date2']));

		$return_time_format = date(get_option( 'time_format' ), strtotime($bookopts['bizzthemes_bookings_date2_time']));

		

		// Duration

		$duration    = $bookopts['duration'];

		$price_range = ( $opt_b['pay_pricerange'] == 'perhour'  ) ? __('hours', 'bizzthemes') : __('days', 'bizzthemes');



		// Extras

		$extras_all = $bookopts['bizzthemes_bookings_extras'];

		$extras_qty = isset( $bookopts['bizzthemes_bookings_extras_qty'] ) ? $bookopts['bizzthemes_bookings_extras_qty'] : 1;

		$extras_field = isset( $bookopts['bizzthemes_bookings_extras_field'] ) ? $bookopts['bizzthemes_bookings_extras_field'] : '';

		$extras = '';

		// customer notification

		if ( ( $status == 'customer' ) && is_array( $bookopts['bizzthemes_bookings_extras'] ) ) {

			foreach ( $bookopts['bizzthemes_bookings_extras'] as $key => $value ) {

				if ( isset($value[1]) ) {

					$extras .= '

					<tr><td>'.__('Extra', 'bizzthemes').' </td><td>'.$value[1].' x '.$value[5].'</td></tr>

					';

				}

			}

		}

		// approved, cancelled, ... notification

		elseif ( ( $status != 'customer' ) && is_array( $bookopts['bizzthemes_bookings_extras'] ) ) {

			foreach ( $bookopts['bizzthemes_bookings_extras'] as $key ) {

				if ( isset( $key ) ) {

					$extra = get_term_by( 'slug', $key, 'bizz_cars_extra' );

					$extra_qty = ( isset( $extras_qty[$key] ) ) ? $extras_qty[$key][0] : 1;

					$extras .= '

					<tr><td>'.__('Extra', 'bizzthemes').' </td><td>'.$extra->name.' x '.$extra_qty.'</td></tr>

					';

				}

			}

		

		}

		// no extras

		else {

			$extras .= '

			<tr><td colspan="2">'.__( 'No extras selected.', 'bizzthemes' ).'</td></tr>

			';

		}



		// get booking settings

		$admin_email = $bookopts['bizzthemes_bookings_dealer_email_id']; //$opt_b['admin_email'];

		$admin_name = $opt_b['admin_name'];

		$approved_subject = $opt_b['approved_email_subject'];

		$approved_content = $opt_b['approved_email_body'];

		$cancelled_subject = $opt_b['cancelled_email_subject'];

		$cancelled_content = $opt_b['cancelled_email_body'];

		$customer_subject = $opt_b['customer_email_subject'];

		$customer_content = $opt_b['customer_email_body'];

		$refunded_subject = $opt_b['refunded_email_subject'];

		$refunded_content = $opt_b['refunded_email_body'];

		$prior_subject = $opt_b['prior_email_subject'];

		$prior_content = $opt_b['prior_email_body'];

		$after_subject = $opt_b['after_email_subject'];

		$after_content = $opt_b['after_email_body'];

		$customer_email = $bookopts['bizzthemes_bookings_email'];

		$replacements  = array(

			'[ADMIN_NAME]' => $admin_name,

			'[ADMIN_EMAIL]' => $admin_email,

			'[TRACKING_ID]' => $bookopts['bizzthemes_bookings_track'], 

			'[CURRENCY]' => get_bizz_currency($opt_b['pay_currency']),

			'[PAY_TOTAL]' => get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_total'],

			'[PAY_TOTAL_NC]' => $bookopts['bizzthemes_car_pay_total'],

			'[PAY_DEPOSIT]' => get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_deposit'],

			'[PAY_DEPOSIT_NC]' => $bookopts['bizzthemes_car_pay_deposit'], 

			'[PAY_CAR]' => get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_car'],

			'[PAY_CAR_NC]' => $bookopts['bizzthemes_car_pay_car'],

			'[PAY_EXTRAS]' => get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_extras'],

			'[PAY_EXTRAS_NC]' => $bookopts['bizzthemes_car_pay_extras'],

			'[PAY_TAX]' => get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_tax'],

			'[PAY_TAX_NC]' => $bookopts['bizzthemes_car_pay_tax'],

			'[CAR]' => get_the_title($bookopts['bizzthemes_bookings_car']),

			'[CAR_COUNT]' => $bookopts['bizzthemes_bookings_car_count'], 

			'[PICKUP_LOCATION]' => get_the_title($bookopts['bizzthemes_bookings_pickup']), 

			'[RETURN_LOCATION]' => get_the_title($bookopts['bizzthemes_bookings_return']), 

			'[PICKUP_DATE]' => $pickup_date_format, 

			'[PICKUP_HOUR]' => $pickup_time_format,

			'[RETURN_DATE]' => $return_date_format, 

			'[RETURN_HOUR]' => $return_time_format,

			'[DURATION]' => $duration, 

			'[FLIGHT]' => $bookopts['bizzthemes_bookings_flight'],

			'[EXTRAS]' => $extras, 

			'[CUSTOMER_TITLE]' => $bookopts['bizzthemes_bookings_ctitle'],

			'[CUSTOMER_FNAME]' => $bookopts['bizzthemes_bookings_fname'],

			'[CUSTOMER_LNAME]' => $bookopts['bizzthemes_bookings_lname'],

			'[CUSTOMER_FULLNAME]' => $bookopts['bizzthemes_bookings_fname'].' '.$bookopts['bizzthemes_bookings_lname'],

			'[CUSTOMER_EMAIL]' => $bookopts['bizzthemes_bookings_email'],

			'[CUSTOMER_PHONE]' => $bookopts['bizzthemes_bookings_phone'],

			'[CUSTOMER_CONTACT_OPTION]' => $bookopts['bizzthemes_bookings_scontact'],

			'[CUSTOMER_COUNTRY]' => $bookopts['bizzthemes_bookings_country'],

			'[CUSTOMER_CITY]' => $bookopts['bizzthemes_bookings_city'],

			'[CUSTOMER_STATE]' => $bookopts['bizzthemes_bookings_state'],

			'[CUSTOMER_ZIP]' => $bookopts['bizzthemes_bookings_zip'],

			'[CUSTOMER_ADDRESS]' => $bookopts['bizzthemes_bookings_address'],

			'[CUSTOMER_COMMENTS]' => $bookopts['bizzthemes_bookings_comm_que'],

			'[CUSTOMER_DRIVER_AGE]' => $bookopts['bizzthemes_bookings_driver_age'],

			'[CUSTOMER_DRIVER_BIRTH]' => $bookopts['bizzthemes_bookings_driver_birth'],

			'[CUSTOMER_DRIVER_LICENSE]' => $bookopts['bizzthemes_bookings_driver_license'],

			'[CUSTOMER_DRIVER_COUNTRY]' => $bookopts['bizzthemes_bookings_driver_country'],

			'[CUSTOMER_DRIVER_ISSUE]' => $bookopts['bizzthemes_bookings_driver_issue'],

			'[CUSTOMER_DRIVER_EXPIRY]' => $bookopts['bizzthemes_bookings_driver_expiry'],

			'[CUSTOMER_DRIVER_ACCIDENTS]' => $bookopts['bizzthemes_bookings_driver_accidents'],

			'[CUSTOMER_NUMBER_PASSENGERS]' => $bookopts['bizzthemes_bookings_number_passengers'],

			'[BOOK_DETAILS]' => '

			<table rules="all" style="border-color:#dddddd;" cellpadding="10">

			<tr><td colspan="2"><strong>'.__('Customer?', 'bizzthemes').'</strong> </td></tr>

			<tr><td>'.__('Tracking ID', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_track'].'</td></tr>

			<tr><td>'.__('Customer Title', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_ctitle'].'</td></tr>

			<tr><td>'.__('First Name', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_fname'].'</td></tr>

			<tr><td>'.__('Last Name', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_lname'].'</td></tr>

			<tr><td>'.__('Email', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_email'].'</td></tr>

			<tr><td>'.__('Phone', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_phone'].'</td></tr>

			<tr><td>'.__('Contact Option', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_scontact'].'</td></tr>

			<tr><td>'.__('Country', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_country'].'</td></tr>

			<tr><td>'.__('State/Province', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_state'].'</td></tr>

			<tr><td>'.__('City', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_city'].'</td></tr>

			<tr><td>'.__('Postcode/ZIP', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_zip'].'</td></tr>

			<tr><td>'.__('Address', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_address'].'</td></tr>

			<tr><td>'.__('Age of Driver', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_age'].'</td></tr>	

			<tr><td>'.__('Date of Birth', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_birth'].'</td></tr>

			<tr><td>'.__('Driving Licence Number', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_license'].'</td></tr>

			<tr><td>'.__('Country / State of issue', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_country'].'</td></tr>

			<tr><td>'.__('Issue Date', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_issue'].'</td></tr>

			<tr><td>'.__('Expiry Date', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_expiry'].'</td></tr>

			<tr><td>'.__('Accidents, claims or motoring convictions over the past 3 years?', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_driver_accidents'].'</td></tr>

			<tr><td>'.__('Number of Passengers', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_number_passengers'].'</td></tr>

			<tr><td>'.__('Comments/Questions', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_comm_que'].'</td></tr>

			<tr><td colspan="2"><strong>'.__('Vehicle?', 'bizzthemes').'</strong> </td></tr>

			<tr><td>'.__('Vehicle Name', 'bizzthemes').' </td><td>'.get_the_title($bookopts['bizzthemes_bookings_car']).'</td></tr>

			<tr><td>'.__('Quantity', 'bizzthemes').' </td><td>'.$bookopts['bizzthemes_bookings_car_count'].'</td></tr>

			<tr><td colspan="2"><strong>'.__('Extras?', 'bizzthemes').'</strong> </td></tr>

			' . $extras . '

			<tr><td colspan="2"><strong>'.__('When and Where?', 'bizzthemes').'</strong> </td></tr>

			<tr><td>'.__('Pickup Location', 'bizzthemes').' </td><td>'.get_the_title($bookopts['bizzthemes_bookings_pickup']).'</td></tr>

			<tr><td>'.__('Return Location', 'bizzthemes').' </td><td>'.get_the_title($bookopts['bizzthemes_bookings_return']).'</td></tr>

			<tr><td>'.__('Start Date and Time', 'bizzthemes').' </td><td>'.$pickup_date_format.' @ '.$pickup_time_format.'</td></tr>

			<tr><td>'.__('Return Date and Time', 'bizzthemes').' </td><td>'.$return_date_format.' @ '.$return_time_format.'</td></tr>

			<tr><td>'.__('Duration', 'bizzthemes').' </td><td>'.$duration.' '.$price_range.'</td></tr>

			<tr><td colspan="2"><strong>'.__('Payment?', 'bizzthemes').'</strong> </td></tr>

			<tr><td>'.__('Vehicle', 'bizzthemes').' </td><td>'.get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_car'].'</td></tr>

			<tr><td>'.__('Extras', 'bizzthemes').' </td><td>'.get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_extras'].'</td></tr>

			<tr><td>'.__('Tax', 'bizzthemes').' </td><td>'.get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_tax'].'</td></tr>

			<tr><td>'.__('Total', 'bizzthemes').' </td><td>'.get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_total'].'</td></tr>

			<tr><td>'.__('Deposit', 'bizzthemes').' </td><td>'.get_bizz_currency($opt_b['pay_currency']).$bookopts['bizzthemes_car_pay_deposit'].'</td></tr>

			</table>

			'

		);

		

		// subject and content

		$subject = str_replace(array_keys($replacements), $replacements, ${$status.'_subject'}); // use the curly braces to dynamically define variable name: http://docstore.mik.ua/orelly/webprog/pcook/ch05_05.htm

		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );

		$content = str_replace(array_keys($replacements), $replacements, ${$status.'_content'});

		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );



		// headers

		$headers = "MIME-Version: 1.0" . "\r\n";

		$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
       //$headers .= "From: ".$admin_name." <".$admin_email.">" . "\r\n";
		//$headers .= 'From: "'.$admin_name.'" <'.$admin_email.'>' . "\r\n";
      //  $headers .= 'To: "'.$bookopts['bizzthemes_bookings_fname'].'" <'.$customer_email.'>' . "\r\n";


		// body

		$body = '<html><body>';

		$body .= $content;

		$body .= "</body></html>";

		

		// shortcut the notification

		//do_action( 'bizzthemes_send_notification_shortcut', $status, $customer_email, $subject, $body, $headers );
    //   mail( $bookopts['bizzthemes_bookings_email'],$subject, json_encode($bookopts),$headers );
		wp_mail( $customer_email, $subject, $body, $headers );

		//wp_mail( $customer_email, $subject, $body, $headers ); //email

		

	}

}



/* Set HTML type email */

/*------------------------------------------------------------------*/

add_filter( 'wp_mail_content_type','booking_set_content_type' );

function booking_set_content_type(){

    return "text/html";

}



/* Custom Post Type Metabox Setup */

/*------------------------------------------------------------------*/

// CREATE

add_action( 'bizz_render_extras', 'custom_bizz_render_extras', 10, 2 );

function custom_bizz_render_extras( $field, $meta ) {

	global $post;

	

	// default

	$default = (isset($field['std'])) ? $field['std'] : 1;

	

	// saved qty

	$saved = get_post_meta($post->ID, $field['id'].'_qty', true);

	

	// saved field

	$saved_field = get_post_meta($post->ID, $field['id'].'_field', true);

	

	// meta

	$meta = get_post_meta($post->ID, $field['id'], false);

	

	// extras list

	$extras_terms = get_terms( 'bizz_cars_extra', array( 'hide_empty' => 0 ) );

	echo '<ul>';

	$i = 1;

	foreach ($extras_terms as $extras_term) {

		// count

		$extra_count = get_option('taxonomy_'.$extras_term->term_id.'_bizz_extra_count');

		$extra_count = ( $extra_count ) ? $extra_count : 1;

		

		// field

		$extra_field = get_option('taxonomy_'.$extras_term->term_id.'_bizz_extra_field');

		$extra_field = ( $extra_field ) ? $extra_field : 0;

		

		echo '<li>';

		echo '<input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], $i, '" value="', $extras_term->slug, '"', in_array( $extras_term->slug, $meta ) ? ' checked="checked"' : '', ' />';

		echo '<label for="', $field['id'], $i, '">', $extras_term->name, '</label>';

		if ( $extra_count > 1 ) {

			echo ' &times; ';

			echo '<select name="', $field['id'], '_qty['.$extras_term->slug.'][]" id="', $field['id'], '">';

			for ($i = 1; $i <= $extra_count; $i++) {

				$existing = ( isset( $saved[$extras_term->slug][0] ) ) ? $saved[$extras_term->slug][0] : ''; #extra qty

				$selected = ($existing) ? (($existing == $i) ? ' selected="selected"' : '') : (($default == $i) ? ' selected="selected"' : '');

				echo '	<option value="' . $i . '" ' . $selected . ' />' . $i . '</option>' . "\n";

			}

			echo '</select>';

		}

		if ( $extra_field ) {

			$existing_field = ( isset( $saved_field[$extras_term->slug][0] ) ) ? $saved_field[$extras_term->slug][0] : ''; #extra field

			$value_field = ($existing_field) ? $existing_field : '';

			echo '&nbsp;&nbsp;<input type="text" class="bizz_text_medium" name="', $field['id'], '_field['.$extras_term->slug.'][]" id="', $field['id'], '_field' , '" value="', stripslashes(stripslashes($value_field)) , '" />';

		}

		echo '</li>';

		$i++;

	}

	echo '</ul>';

	echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';

	

}



add_action( 'bizz_render_remaining', 'custom_bizz_render_remaining', 10, 2 );

function custom_bizz_render_remaining( $field, $meta ) {

	global $post, $booking_settings;

	

	// currency

	$currency = ( !empty($field['currency']) ) ? $field['currency'] : '$';

	

	// get booking settings

	$opt_s  = $booking_settings->get_settings();

	

	// calculate

	$total = get_post_meta($post->ID, 'bizzthemes_car_pay_total', true);

	$paid = get_post_meta($post->ID, 'bizzthemes_car_pay_paid', true);

	$remaining = currencystr_to_float($total, $opt_s) - currencystr_to_float($paid, $opt_s);

	

	// output

	echo '<table>';

	echo '<tr>';

	

	echo '<td style="padding: 0;">';

	echo $currency . '&nbsp;' . $remaining . '<span class="bizz_metabox_description">', $field['desc'], '</span>';

	echo '</td>';

	

	echo '</tr>';

	echo '</table>';

	

}



// VALIDATE and SAVE

add_filter( 'bizz_validate_extras', 'custom_bizz_validate_extras', 10, 3 );

function custom_bizz_validate_extras( $new, $post_id, $field ) {	

	// only save quantity data

	$name_qty = $field['id'] . '_qty';

	$old_qty = get_post_meta( $post_id, $name_qty );

	$new_qty = isset( $_POST[$name_qty] ) ? $_POST[$name_qty] : null;

					

	if ( $new_qty && $new_qty != $old_qty ) {

		if ( !empty( $new_qty ) ) {

			update_post_meta( $post_id, $name_qty, $new_qty );

		}

	}

	elseif ( '' == $new_qty && $old_qty )

		update_post_meta( $post_id, $name_qty, null );

		

	// only save field data

	$name_field = $field['id'] . '_field';

	$old_field = get_post_meta( $post_id, $name_field );

	$new_field = isset( $_POST[$name_field] ) ? $_POST[$name_field] : null;

	

	if ( $new_field && $new_field != $old_field ) {

		if ( !empty( $new_field ) ) {

			update_post_meta( $post_id, $name_field, $new_field );

		}

	}

	elseif ( '' == $new_field && $old_field )

		update_post_meta( $post_id, $name_field, null );

	

	// return default values, skip quantity

	return $new;

}



/* Modify select box for locations */

/*------------------------------------------------------------------*/

// CREATE

add_action( 'bizz_render_location_select', 'custom_bizz_render_locations', 10, 2 );

function custom_bizz_render_locations( $field, $meta ) {

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

	echo '<select name="', $field['id'], '" id="', $field['id'], '">';

	foreach ($field['options'] as $option) {

		$alt = isset( $locations_options[$option['value']] ) ? $locations_options[$option['value']] : '';

		echo '<option value="', $option['value'], '"', ($meta == $option['value'] || $meta == $alt) ? ' selected="selected"' : '', '>', $option['name'], '</option>';

	}

	echo '</select>';

	echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';

}



add_filter( 'bizz_meta_boxes', 'bizz_bookings_metaboxes' );

function bizz_bookings_metaboxes( $meta_boxes ) {

	global $post, $booking_settings;



	$prefix = 'bizzthemes_';

	$opt_s  = $booking_settings->get_settings();



	// cars

	$car_args = array( 'post_type' => 'bizz_cars', 'numberposts' => -1 );

	$car_posts = get_posts( $car_args );

	$car_options = array();

	$car_options[0] = array(

		'name' => __('-- Select --', 'bizzthemes'),

		'value' => ''

	);

	if ($car_posts) {

		foreach ($car_posts as $car_post) {

			$car_options[] = array(

				'name' => $car_post->post_title,

				'value' => $car_post->ID

			);

		}

	}

  

	// count

	for ($i = 1; $i <= 20; $i++) {

		$car_count[] = array(

			'name' => $i,

			'value' => $i

		);

	}



  

  // locations

  $location_posts = get_posts( array( 'post_type' => 'bizz_locations', 'numberposts' => -1 ) );

  $location_options = array();

  $location_options[0] = array(

            'name' => __('-- Select --', 'bizzthemes'),

            'value' => ''

  );

  foreach ($location_posts as $location_post) {

    $location_options[] = array(

            'name' => $location_post->post_title,

            'value' => $location_post->ID

	);

  }

  

  // months

  $months = array();

  $months[0] = array(

            'name' => __('-- Select --', 'bizzthemes'),

            'value' => ''

  );

  for ($i = 1; $i <= 12; $i++) {

	$months[] = array(

            'name' => date("F", mktime(0, 0, 0, $i, 10)),

            'value' => $i

	);

  }

  

  // years

  $years = array();

  $current_year = date("Y");

  $start_year = $current_year - 5;

  $final_year = $start_year + 16;

  $years[0] = array(

            'name' => __('-- Select --', 'bizzthemes'),

            'value' => ''

  );

  for ($i = $start_year; $i <= $final_year; $i++) {

	$years[] = array(

            'name' => $i,

            'value' => $i

	);

  }

    

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_status',

    'title' => __('Booking Status', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'normal',

    'priority' => 'high',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Status', 'bizzthemes'),

        'desc' => __('Updating booking status will automatically send email notification to the customer.', 'bizzthemes'),

        'id' => $prefix . 'bookings_status',

        'type' => 'select',

        'options' => array(

          array('name' => __('Pending', 'bizzthemes'), 'value' => 'pending'),

          array('name' => __('Approved', 'bizzthemes'), 'value' => 'approved'),

         // array('name' => __('Completed', 'bizzthemes'), 'value' => 'completed'),

          array('name' => __('Cancelled', 'bizzthemes'), 'value' => 'cancelled'),

          array('name' => __('Refunded', 'bizzthemes'), 'value' => 'refunded')			

        )

      ),

      array(

        'name' => __('Tracking ID', 'bizzthemes'),

        'desc' => __('Tracking number for this booking. Same as post ID.', 'bizzthemes'),

        'id' => $prefix . 'bookings_track',

        'type' => 'text_small'

      ),

    )

  );

  

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_car',

    'title' => __('Vehicle and Extras', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'normal',

    'priority' => 'high',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Vehicle', 'bizzthemes'),

        'id' => $prefix . 'bookings_car',

        'type' => 'select',

        'options' => $car_options

      ),

	  array(

        'name' => __('Quantity', 'bizzthemes'),

        'id' => $prefix . 'bookings_car_count',

        'type' => 'select',

        'options' => $car_count

      ),

	  array(

		'name'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Vehicle Extras', 'bizzthemes').'</a>',

        'desc'    => '<a href="edit-tags.php?taxonomy=bizz_cars_extra&post_type=bizz_bookings">'.__('Add more', 'bizzthemes').'</a>',

		'id' => $prefix . 'bookings_extras',

		'multiple' => true,

		'std' => '1',

		'type' => 'extras',

	  ),

	  array(

        'name' => __('Coupon Code', 'bizzthemes'),

        'desc' => __('Coupon code, used with this purchase.', 'bizzthemes'),

        'id' => $prefix . 'bookings_coupon',

        'type' => 'text_small'

      ),

    )

  );

  

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_date',

    'title' => __('Date and Location', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'normal',

    'priority' => 'high',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Pickup Location', 'bizzthemes'),

        'id' => $prefix . 'bookings_pickup',

        'type' => 'location_select',

        'options' => $location_options

      ),

      array(

        'name' => __('Return Location', 'bizzthemes'),

        'id' => $prefix . 'bookings_return',

        'type' => 'location_select',

        'options' => $location_options

      ),

      array(

        'name' => __('Start Date', 'bizzthemes'),

        'id' => $prefix . 'bookings_date1',

        'type' => 'date_time'

      ),

      array(

        'name' => __('Return Date', 'bizzthemes'),

        'id' => $prefix . 'bookings_date2',

        'type' => 'date_time'

      ),

      array(

        'name' => __('Flight Number', 'bizzthemes'),

        'desc' => __('If available, include both the carrier code and the flight number, like BA2244. This is vital to ensure your vehicle is available if your flight is delayed.', 'bizzthemes'),

        'id' => $prefix . 'bookings_flight',

        'type' => 'text_small'

      ),

    )

  );

  

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_customer',

    'title' => __('Customer Details', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'normal',

    'priority' => 'high',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Customer Title', 'bizzthemes'),

        'id' => $prefix . 'bookings_ctitle',

        'type' => 'select',

        'options' => array(

          array('name' => __('Mr', 'bizzthemes'), 'value' => 'mr'),

          array('name' => __('Mrs', 'bizzthemes'), 'value' => 'mrs'),

          array('name' => __('Miss', 'bizzthemes'), 'value' => 'miss'),

          array('name' => __('Dr', 'bizzthemes'), 'value' => 'dr'),

          array('name' => __('Prof', 'bizzthemes'), 'value' => 'prof'),

          array('name' => __('Rev', 'bizzthemes'), 'value' => 'rev')

        )

      ),

      array(

        'name' => __('First Name', 'bizzthemes'),

        'id' => $prefix . 'bookings_fname',

        'type' => 'text_medium'

      ),

      array(

        'name' => __('Last Name', 'bizzthemes'),

        'id' => $prefix . 'bookings_lname',

        'type' => 'text_medium'

      ),

      array(

        'name' => __('Email', 'bizzthemes'),

        'id' => $prefix . 'bookings_email',

        'type' => 'text_medium'

      ),

      array(

        'name' => __('Phone', 'bizzthemes'),

        'id' => $prefix . 'bookings_phone',

        'type' => 'text_medium'

      ),

      array(

        'name' => __('Contact Option', 'bizzthemes'),

        'id' => $prefix . 'bookings_scontact',

        'type' => 'select',

        'options' => array(

          array('name' => __('Email', 'bizzthemes'), 'value' => 'email'),

          array('name' => __('Phone (SMS)', 'bizzthemes'), 'value' => 'sms'),

          array('name' => __('Phone (Call)', 'bizzthemes'), 'value' => 'call')

        )

      ),

      array(

        'name' => __('Address', 'bizzthemes'),

        'id' => $prefix . 'bookings_address',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Postcode/ZIP', 'bizzthemes'),

        'id' => $prefix . 'bookings_zip',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('City', 'bizzthemes'),

        'id' => $prefix . 'bookings_city',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('State/Province', 'bizzthemes'),

        'id' => $prefix . 'bookings_state',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Country', 'bizzthemes'),

        'id' => $prefix . 'bookings_country',

        'type' => 'select',

        'options' => bizz_country_list()

      ),

	  array(

        'name' => __('Age of Driver', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_age',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Date of Birth', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_birth',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Driving Licence Number', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_license',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Country / State of issue', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_country',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Issue Date', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_issue',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Expiry Date', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_expiry',

        'type' => 'text_medium'

      ),

	  array(

        'name' => __('Accidents, claims or motoring convictions over the past 3 years?', 'bizzthemes'),

        'id' => $prefix . 'bookings_driver_accidents',

        'type' => 'select',

        'options' => array(

          array('name' => __('No', 'bizzthemes'), 'value' => 'noe'),

          array('name' => __('Yes (please provide details in Comments box below)', 'bizzthemes'), 'value' => 'yes')

        )

      ),			

	  array(

        'name' => __('Number of Passengers', 'bizzthemes'),

        'id' => $prefix . 'bookings_number_passengers',

        'type' => 'text_medium'

      ),

      array(

        'name' => __('Comments/Questions', 'bizzthemes'),

        'id' => $prefix . 'bookings_comm_que',

        'type' => 'textarea',

        'options' => array(

          'textarea_rows' => 5,

        )

      ),

    )

  );

  

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_invoice',

    'title' => __('Invoice', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'side',

    'priority' => 'low',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Vehicle', 'bizzthemes'),

        'desc' => __('enter the car amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_car',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

      array(

        'name' => __('Extras', 'bizzthemes'),

        'desc' => __('enter the extras amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_extras',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

      array(

        'name' => __('Tax', 'bizzthemes'),

        'desc' => __('enter the tax amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_tax',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

      array(

        'name' => __('Total', 'bizzthemes'),

        'desc' => __('enter the total amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_total',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

      array(

        'name' => __('Deposit', 'bizzthemes'),

        'desc' => __('Amount paid before rental', 'bizzthemes'),

        'id' => $prefix . 'car_pay_deposit',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

    )

  );

    

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_payment',

    'title' => __('Payment', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'side',

    'priority' => 'low',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

        'name' => __('Paid', 'bizzthemes'),

        // 'desc' => __('Details', 'bizzthemes'),

        'id' => $prefix . 'car_pay_paid',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'text_money'

      ),

      array(

        'name' => __('Remaining', 'bizzthemes'),

        // 'desc' => __('Remaining amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_remaining',

        'currency' => get_bizz_currency($opt_s['pay_currency']),

        'type' => 'remaining'

      ),

	  array(

        'name' => __('Payment method', 'bizzthemes'),

        // 'desc' => __('Remaining amount', 'bizzthemes'),

        'id' => $prefix . 'car_pay_method',

        'type' => 'select',

		'options' => array(

			array('name' => __('-- Select --', 'bizzthemes'), 		'value' => '0'),

			array('name' => __('PayPal', 'bizzthemes'), 			'value' => 'paypal'),

			array('name' => __('Credit Card', 'bizzthemes'), 		'value' => 'creditcard'),

			array('name' => __('Cash on Delivery', 'bizzthemes'), 	'value' => 'cod'),

			array('name' => __('Bank Transfer', 'bizzthemes'), 		'value' => 'banktransfer'),

			array('name' => __('Mollie Payment', 'bizzthemes'), 	'value' => 'mollie')

		)

      ),

      array(

        // 'name' =>  __('PayPal Details', 'bizzthemes'),

        'id' => $prefix . 'booking_paypal_details',

        'type' => 'hidden',

        'desc' => ''

      )

    )

  );

  

  if ( isset($opt_s['pay_credit']) && $opt_s['pay_credit'] ) {

  

  $meta_boxes[] = array(

    'id' => 'bizzthemes_bookings_cc',

    'title' => __('Credit Card', 'bizzthemes'),

    'pages' => array( 'bizz_bookings' ), // post type

    'context' => 'side',

    'priority' => 'low',

    'show_names' => true, // Show field names on the left

    'fields' => array(

      array(

		'name' => __('Type', 'bizzthemes'),

		'id' => $prefix . 'bookings_cc_type',

		'type' => 'select',

		'options' => array(

			array('name' => __('-- Select --', 'bizzthemes'), 'value' => '0'),

			array('name' => __('Visa', 'bizzthemes'), 'value' => 'visa'),

			array('name' => __('MasterCard', 'bizzthemes'), 'value' => 'mastercard'),

			array('name' => __('Maestro', 'bizzthemes'), 'value' => 'maestro'),

			array('name' => __('AmericanExpress', 'bizzthemes'), 'value' => 'amex')

		)

      ),

      array(

        'name' => __('Number', 'bizzthemes'),

        'id' => $prefix . 'bookings_cc_number',

        'type' => 'text_small'

      ),

	  array(

		'name' => __('Expiration Month', 'bizzthemes'),

		'id' => $prefix . 'bookings_cc_expiration_m',

		'type' => 'select',

		'options' => $months

      ),

	  array(

		'name' => __('Expiration Year', 'bizzthemes'),

		'id' => $prefix . 'bookings_cc_expiration_y',

		'type' => 'select',

		'options' => $years

      ),

      array(

        'name' => __('Security Code', 'bizzthemes'),

        'id' => $prefix . 'bookings_cc_security',

        'type' => 'text_small'

      ),

    )

  );



  }

    

  return $meta_boxes;

}





