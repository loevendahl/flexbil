<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* TEST MODE */
/*------------------------------------------------------------------*/
if (!defined("TEST_MODE")) define("TEST_MODE", false);
if (!defined("DEBUG_MODE")) define("DEBUG_MODE", false);

/* BOOKING: Custom Post Types */
/*------------------------------------------------------------------*/
locate_template( 'lib_theme/booking/post-type-cars.php', true );
locate_template( 'lib_theme/booking/post-type-locations.php', true );
locate_template( 'lib_theme/booking/post-type-pricing.php', true );
locate_template( 'lib_theme/booking/post-type-bookings.php', true );
locate_template( 'lib_theme/booking/post-type-coupons.php', true );
locate_template( 'lib_theme/booking/post-type-settings.php', true );
locate_template( 'lib_theme/booking/shortcodes-booking.php', true );
locate_template( 'lib_theme/booking/templates-booking.php', true );

/* BOOKING: Widgets */
/*------------------------------------------------------------------*/
add_action( 'widgets_init', 'bizz_booking_widgets' );
function bizz_booking_widgets() {
	locate_template( 'lib_theme/booking/widget-booking.php', true );
}

/* BOOKINGS: Scripts */
/*------------------------------------------------------------------*/

// Add Theme Javascript
add_action( 'wp_enqueue_scripts', 'bizz_book_javascript' );
function bizz_book_javascript() {
	global $booking_settings, $wp_locale;
	
	// get booking settings
	$opt_s = $booking_settings->get_settings();

	// css
	wp_enqueue_style( 'jquery_ui_style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/blitzer/jquery-ui.css'); #header
	
	// js
	wp_enqueue_script( 'jquery-ui-datepicker' ); #header
	wp_enqueue_script( 'booking-js', get_stylesheet_directory_uri() .'/lib_theme/booking/booking.js', array( 'jquery' ) ); # header
	wp_localize_script( 'booking-js', 'bizzlang', array(
		'menu_select' 		=> __( 'Select a page', 'bizzthemes' ),
		'book_empty' 		=> __( 'Select a location first, then pick a date and time.', 'bizzthemes' ),
		'book_closed' 		=> __( 'We are closed on this date, pick another one.', 'bizzthemes' ),
		'book_past' 		=> __( 'We cannot book you for the past date, pick another one.', 'bizzthemes' ),
		'book_success' 		=> __( 'Thanks, your booking has been received. Expect confirmation email shortly after someone reviews your booking.', 'bizzthemes' ),
		'book_nocars' 		=> __( 'No vehicles found.', 'bizzthemes' ),
		'book_noextra' 		=> __( 'No extras selected.', 'bizzthemes' ),
		'book_noextras' 	=> __( 'No extras available for this vehicle.', 'bizzthemes' ),
		'book_required' 	=> __( 'Required.', 'bizzthemes' ),
		'email_required' 	=> __( 'Email is not valid.', 'bizzthemes' ),
		'thankyou_page' 	=> $opt_s['pay_thankyou'],
		'price_not_defined' => __( 'Not Available', 'bizzthemes' ),
		'free' 				=> __( 'Free', 'bizzthemes' )
	));
	
	// datepicker
	// read: http://jquery-ui.googlecode.com/svn/tags/latest/ui/i18n/ for all language translations
    wp_localize_script( 'booking-js', 'objectL10n', apply_filters( 'bizz_calendar_lang', array(
		'minDateP'          => 0,
		'minDateR'          => 1,
		'closeText'         => __( 'Done', 'bizzthemes' ),
		'currentText'       => __( 'Today', 'bizzthemes' ),
		'monthNames'        => strip_array_indices( $wp_locale->month ),
		'monthNamesShort'   => strip_array_indices( $wp_locale->month_abbrev ),
		'monthStatus'       => __( 'Show a different month', 'bizzthemes' ),
		'dayNames'          => strip_array_indices( $wp_locale->weekday ),
		'dayNamesShort'     => strip_array_indices( $wp_locale->weekday_abbrev ),
		'dayNamesMin'       => strip_array_indices( $wp_locale->weekday_initial ),
		'dateFormat'        => dateformatsyntax_php2js( get_option( 'date_format', 'y-m-d') ), # set the date format to match the WP general date settings
		'firstDay'          => get_option( 'start_of_week' ), # get the start of week from WP general setting
		'isRTL'             => ( isset( $wp_locale->is_rtl ) ) ? $wp_locale->is_rtl : false, # is Right to left language? default is false
	)));

}

/* BOOKINGS: Feature Pointers */
/*------------------------------------------------------------------*/
add_action( 'admin_head', 'bizz_book_pointers' );
function bizz_book_pointers() {
  global $themeid, $wpdb;
  
  if( !is_admin() || version_compare(get_bloginfo('version'), '3.2.3', '<=') )
    return;

  // Using Pointers
  wp_enqueue_style( 'wp-pointer' );
  wp_enqueue_script( 'wp-pointer' );

  // step 1?
  $step_1 = '<h3>' . __( 'Add Vehicle Type', 'bizzthemes' ) . '</h3>';
  $step_1 .= '<p>' . sprintf(__('It appears you have no car types, which are required for booking to work properly.<br/><br/><a href="%1$s">Add car type</a>', 'bizzthemes'), wp_nonce_url(admin_url('edit-tags.php?taxonomy=bizz_cars_type&post_type=bizz_bookings'))) . '</p>';
  $step_1_hide = get_user_setting( 'b_step_1', 0 ); // check settings on user
  $step_1_count = wp_count_terms('bizz_cars_type');
  
  // step 2?
  $step_2 = '<h3>' . __( 'Add Vehicle Location', 'bizzthemes' ) . '</h3>';
  $step_2 .= '<p>' . sprintf(__('It appears you have no car locations, which are required for booking to work properly.<br/><br/><a href="%1$s">Add car location</a>', 'bizzthemes'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_locations'))) . '</p>';
  $step_2_hide = get_user_setting( 'b_step_2', 0 ); // check settings on user
  $step_2_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_locations'");
  
  // step 3?
  $step_3 = '<h3>' . __( 'Add Vehicles', 'bizzthemes' ) . '</h3>';
  $step_3 .= '<p>' . sprintf(__('It appears you have no cars, which are required for booking to work properly.<br/><br/><a href="%1$s">Add a car</a>', 'bizzthemes'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_cars'))) . '</p>';
  $step_3_hide = get_user_setting( 'b_step_3', 0 ); // check settings on user
  $step_3_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_cars'");
  
  // step 4?
  $step_4 = '<h3>' . __( 'Add Prices', 'bizzthemes' ) . '</h3>';
  $step_4 .= '<p>' . sprintf(__('It appears you have no prices set for your cars, which are required for booking to work properly.<br/><br/><a href="%1$s">Set a price</a>', 'bizzthemes'), wp_nonce_url(admin_url('post-new.php?post_type=bizz_pricing'))) . '</p>';
  $step_4_hide = get_user_setting( 'b_step_4', 0 ); // check settings on user
  $step_4_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'bizz_pricing'");
?>
  <script type="text/javascript">
  jQuery(document).ready(function(){
    <?php if ( !$step_1_hide && !$step_1_count && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
    jQuery('#menu-posts-bizz_bookings').pointer({
      content    : '<?php echo $step_1; ?>',
      position   : {
        edge: 'left',
        align: 'center'
      },
      close: function() {
        setUserSetting( 'b_step_1', '1' );
      }
    }).pointer('open');
    <?php } ?>
    <?php if ( !$step_2_hide && !$step_2_count && ($step_1_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
    jQuery('#menu-posts-bizz_bookings').pointer({
      content    : '<?php echo $step_2; ?>',
      position   : {
        edge: 'left',
        align: 'center'
      },
      close: function() {
        setUserSetting( 'b_step_2', '1' );
      }
    }).pointer('open');
    <?php } ?>
    <?php if ( !$step_3_hide && !$step_3_count && ($step_1_count && $step_2_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
    jQuery('#menu-posts-bizz_bookings').pointer({
      content    : '<?php echo $step_3; ?>',
      position   : {
        edge: 'left',
        align: 'center'
      },
      close: function() {
        setUserSetting( 'b_step_3', '1' );
      }
    }).pointer('open');
    <?php } ?>
    <?php if ( !$step_4_hide && !$step_4_count && ($step_1_count && $step_2_count && $step_3_count) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) { ?>
    jQuery('#menu-posts-bizz_bookings').pointer({
      content    : '<?php echo $step_4; ?>',
      position   : {
        edge: 'left',
        align: 'center'
      },
      close: function() {
        setUserSetting( 'b_step_4', '1' );
      }
    }).pointer('open');
    <?php } ?>
  });
  </script>
<?php
}

/* BOOKINGS: VALIDATE */
/*------------------------------------------------------------------*/
          
//for logged-in users
add_action('wp_ajax_validate_booking', 'bizz_booking_validate');

//for none logged-in users
add_action('wp_ajax_nopriv_validate_booking', 'bizz_booking_validate');
  
function bizz_booking_validate() {
	global $wpdb, $booking_settings;
	
	// get submited form parameters
	$params = $_GET["params"];
	
	// get booking settings
	$opt_s  = $booking_settings->get_settings();
	
	if (isset($_GET["step"])) {

		$qs_step = $_GET["step"];
		
		// get submited form parameters
		$params = apply_filters( 'bizz_filter_booking_step_params', $params, $qs_step, $opt_s );
		
		// Hook into each step
		do_action( 'booking_steps', $qs_step, $params, $opt_s );

		// step 2 : user inserts on form date, time & location of pickup and return --> create cookie with date, time & location selection and return available cars
		if ($qs_step == "2") {
		
			// Locale date and time strings			
			$date_format = get_option( 'date_format', 'Y-m-d' );
			$time_format = get_option( 'time_format', 'H:i' );
		  
			// Pickup
			$date_f = date( 'Y-m-d', strtotime( $params['pickup_y'] . '-' . $params['pickup_m'] . '-' . $params['pickup_d'] ) );
			//$date_f = new DateTime( $date_f );
			//$date_ff = date_format( $date_f, $date_format );
			$time_f = new DateTime( $params["hour_of_pickup"] );
			$time_ff = date_format( $time_f, 'H:i' );
			$pickup_stt = strtotime( $date_f . ' ' . $time_ff );
			
			// Return
			$date_t = date( 'Y-m-d', strtotime( $params['return_y'] . '-' . $params['return_m'] . '-' . $params['return_d'] ) );
			//$date_t = new DateTime( $date_t );
			//$date_tf = date_format( $date_t, $date_format );
			$time_t = new DateTime( $params["hour_of_return"] );
			$time_tf = date_format( $time_t, 'H:i' );
			$return_stt = strtotime( $date_t . ' ' . $time_tf );
			
			// Count Slots
			$days = bizz_count_slots($pickup_stt, $return_stt, false, $opt_s);
			$hours = bizz_count_slots($pickup_stt, $return_stt, true, $opt_s);
			$slots = bizz_count_slots($pickup_stt, $return_stt, $opt_s['pay_pricerange'] == 'perhour' ? true : false, $opt_s);
						
			// Page ID by page name
			$location_of_pickup = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$params["location_of_pickup"]."' AND post_type = 'bizz_locations'");
			$location_of_pickup = ( $location_of_pickup ) ? $location_of_pickup : $params["location_of_pickup"];
			$location_of_pickup_slug = get_post( $location_of_pickup );
			$location_of_pickup_slug = $location_of_pickup_slug->post_name;
			# choose pickup location if return is empty
			$params["location_of_return"] = ( $params["location_of_return"] && ( isset( $params["return_chk"] ) && $params["return_chk"] == "true" ) ) ? $params["location_of_return"] : $params["location_of_pickup"];
			$location_of_return = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$params["location_of_return"]."' AND post_type = 'bizz_locations'");
			$location_of_return = ( $location_of_return ) ? $location_of_return : $params["location_of_return"];
			$location_of_return_slug = get_post( $location_of_return );
			$location_of_return_slug = $location_of_return_slug->post_name;
			// Coupon code
			$coupon_code = ( isset( $params["coupon_code"] ) ) ? $params["coupon_code"] : '';

			// Locale Pickup		
			$date_ffl = $params['date_of_pickup'];
			$time_ffl = date_format( $time_f, $time_format );
			
			// Locale Return
			$date_tfl = $params['date_of_return'];
			$time_tfl = date_format( $time_t, $time_format );

			// address
			$address_p = get_post_meta($location_of_pickup, 'bizzthemes_location_address', true);
			$address_r = get_post_meta($location_of_return, 'bizzthemes_location_address', true);

			$carhire_cookie = array(
				"date_of_pickup" => $date_f,
				"date_of_pickup_d" => $params['pickup_d'],
				"date_of_pickup_m" => $params['pickup_m'],
				"date_of_pickup_y" => $params['pickup_y'],
				"date_of_pickup_dn" => $params['pickup_dn'],
				"date_of_pickup_locale" => $date_ffl,
				"hour_of_pickup" => $time_ff,
				"hour_of_pickup_locale" => $time_ffl,
				"date_of_return" => $date_t,
				"date_of_return_d" => $params['return_d'],
				"date_of_return_m" => $params['return_m'],
				"date_of_return_y" => $params['return_y'],
				"date_of_return_dn" => $params['return_dn'],
				"date_of_return_locale" => $date_tfl,
				"hour_of_return" => $time_tf,
				"hour_of_return_locale" => $time_tfl,
				"location_of_pickup" => $location_of_pickup,
				"location_of_pickup_slug" => $location_of_pickup_slug,
				"location_of_pickup_name" => get_the_title( $location_of_pickup ) . (($address_p) ? ', ' . $address_p : ''),
				"location_of_return" => $location_of_return,
				"location_of_return_slug" => $location_of_return_slug,
				"location_of_return_name" => get_the_title( $location_of_return ) . (($address_r) ? ', ' . $address_r : ''),
				"count_days" => $days,
				"count_hours" => $hours,
				"count_slots" => $slots,
				"currency" => get_bizz_currency( $opt_s['pay_currency'] ),
				"coupon" => $coupon_code
			);
			
			// loop booked days
			$slots_array = bizz_slot_array( $carhire_cookie['date_of_pickup'], $carhire_cookie['date_of_return'], $carhire_cookie['hour_of_pickup'], $carhire_cookie['hour_of_return'], $opt_s );
			
			// pricing posts
			$pricing_posts = bizz_get_pricing_posts( $carhire_cookie['count_slots'] );
			
			// booking posts
			$booking_posts = bizz_get_booking_posts();
			
			// coupon posts
			$coupon_post = apply_filters( 'bizz_coupon_filter', bizz_get_coupon( $coupon_code ), $carhire_cookie );
			
			// Step 2 cookie filter
			$carhire_cookie = apply_filters( 'step_'.$qs_step.'_cookie', $carhire_cookie, $params, $opt_s );

			// set cookie
			bizz_fill_booking_cookie( $carhire_cookie );

			print bizz_return_cars( $carhire_cookie, $slots_array, $pricing_posts, $booking_posts, $coupon_post, $opt_s );
		}
		// validating step 3 : user selects car --> update cookie with car selection and return selected car extras
		elseif ($qs_step == "3") {	
			$carhire_cookie = array();
			$carhire_cookie = json_decode( stripslashes( $_COOKIE['carhire'] ) );
			$carhire_cookie->car_id = $params["car_id"];
			$carhire_cookie->car_name = get_the_title( $params["car_id"] );
			$carhire_cookie->dealer_id = get_post_meta( $params["car_id"], 'bizzthemes_car_dealer', true );
			$carhire_cookie->dealer_email_id = get_post_meta( $params["car_id"], 'bizzthemes_car_dealer_email', true );
			$carhire_cookie->car_image = get_post_meta( $params["car_id"], 'bizzthemes_car_image', true );
			$carhire_cookie->car_count = $params["car_count"];
			
			// coupon posts
			$coupon_post = apply_filters( 'bizz_coupon_filter', bizz_get_coupon( $carhire_cookie->coupon ), $carhire_cookie );

			// Step 3 cookie filter
			$carhire_cookie = apply_filters( 'step_'.$qs_step.'_cookie', $carhire_cookie, $params, $opt_s );
			
			// set cookie
			bizz_fill_booking_cookie( $carhire_cookie );

			print bizz_return_car_extras( $params["car_id"], $coupon_post, $opt_s );
		}
		// validating step 4 : user selects extras --> update cookie with car extras selection and return checkout form 
		elseif ($qs_step == "4") {
			$carhire_cookie = array();
			$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));
			
			// coupon posts
			$coupon_post = apply_filters( 'bizz_coupon_filter', bizz_get_coupon( $carhire_cookie->coupon ), $carhire_cookie );

			// extras		
			$array_extras_qs = ( $params["car_extras"] ) ? explode("~", $params["car_extras"]) : array();			
			$array_extras = array();
			$extras_total = 0;
			$extras_total_disc = 0;
			for ($i = 0; $i < count($array_extras_qs); $i++) {
				$array_extras_inner = array();
				$extra_data = $array_extras_qs[$i];
				$extra_data = explode('|', $extra_data);
				$extra_id = $extra_data[0];
				$custom_extra = get_term($extra_id, 'bizz_cars_extra');        
				// slug
				$array_extras_inner[0] = $custom_extra->slug;
				// name
				$array_extras_inner[1] = $custom_extra->name;
				// calculate price for this extra
				$extra_price = bizz_extra_price( $extra_id, $carhire_cookie, false, '', $opt_s );
				$extra_price_disc = bizz_extra_price( $extra_id, $carhire_cookie, false, $coupon_post, $opt_s );
				$extras_total = $extras_total + ( $extra_price * $extra_data[1] );
				$extras_total_disc = $extras_total_disc + ( $extra_price_disc * $extra_data[1] );
				$array_extras_inner[2] = currencystr_to_float( $extra_price, true, $opt_s );
				$array_extras_inner[3] = currencystr_to_float( $extra_price_disc, true, $opt_s ); 
				// id
				$array_extras_inner[4] = $extra_id;
				// count
				$array_extras_inner[5] = $extra_data[1];
				// field
				$array_extras_inner[6] = $extra_data[2];
				// push into an array
				array_push($array_extras, $array_extras_inner);        
			}
			$carhire_cookie->car_extras = $array_extras;
			
			// loop booked days
			$slots_array = bizz_slot_array( $carhire_cookie->date_of_pickup, $carhire_cookie->date_of_return, $carhire_cookie->hour_of_pickup, $carhire_cookie->hour_of_return, $opt_s );
			
			// pricing posts
			$pricing_posts = bizz_get_pricing_posts( $carhire_cookie->count_slots );

			
			// calculate car pricing
			$car_cost = bizz_car_price_byid( $carhire_cookie->car_id, $carhire_cookie, $slots_array, $pricing_posts, '', $opt_s );
			$car_cost_disc = bizz_car_price_byid( $carhire_cookie->car_id, $carhire_cookie, $slots_array, $pricing_posts, $coupon_post, $opt_s );
			
			// calculate car pricing with quantity
			if ( isset( $carhire_cookie->car_count ) && $carhire_cookie->car_count > 1 ) {
				$car_cost = $car_cost * $carhire_cookie->car_count;
				$car_cost_disc = $car_cost_disc * $carhire_cookie->car_count;
			}
			
			// get car data
			$custom = get_post_custom( $carhire_cookie->car_id );
			
			// DEPRECATED: fixed deposit per type
			if ( isset( $custom['bizzthemes_car_type'] ) && count( $custom['bizzthemes_car_type'] ) == 1 ) {
				$car_type = $custom['bizzthemes_car_type'][0];
				$car_type_term = get_term_by('slug', $car_type, 'bizz_cars_type');
				$car_deposit = get_option('taxonomy_'.$car_type_term->term_id.'_bizz_type_deposit');
				$car_deposit_fixed = ( $car_deposit ) ? $car_deposit : $opt_s['pay_deposit'];
			}
			
			// fixed deposit per vehicle
			$car_deposit = ( isset( $custom['bizzthemes_car_deposit'][0] ) ) ? $custom['bizzthemes_car_deposit'][0] : $opt_s['pay_deposit'];
			$car_deposit_fixed = ( $car_deposit ) ? $car_deposit : $opt_s['pay_deposit'];
			
			// deposit times vehicles booked
			if ( isset( $carhire_cookie->car_count ) && $carhire_cookie->car_count > 1 ) {
				$car_deposit_fixed = $car_deposit * $carhire_cookie->car_count;
			}
			
			// tax and deposit
			$tax_percentage = currencystr_to_float($opt_s['pay_tax'], $opt_s) / 100;
			$tax_total = (($extras_total_disc + $car_cost_disc) * $tax_percentage);
			$deposit_percentage = currencystr_to_float($opt_s['pay_deposit'], $opt_s) / 100;
			$deposit_fixed = currencystr_to_float($car_deposit_fixed, $opt_s);
			$deposit_pct = ($extras_total_disc + $car_cost_disc + $tax_total) * $deposit_percentage;
			$deposit_total = ( isset( $opt_s['pay_deposit_select'] ) && ( $opt_s['pay_deposit_select'] == 'percentage' ) ) ? $deposit_pct : $deposit_fixed;
			if ( isset( $opt_s['pay_deposit_max'] ) && ( $opt_s['pay_deposit_max'] != 0 ) && ( $deposit_total > $opt_s['pay_deposit_max'] ) ) {
				$deposit_total = currencystr_to_float($opt_s['pay_deposit_max'], $opt_s); #deposit max
			}
			$total = $extras_total_disc + $car_cost_disc + $tax_total;

			// DO NOT USE FOR PAYMENT TRANSACTION, READ IT FROM DATABASE!!!
			$carhire_cookie->car_total_payment = array();
			$carhire_cookie->car_total_payment = array( 
				"car_total" => float_to_currencystr($car_cost, false, $opt_s),
				"car_total_disc" => float_to_currencystr($car_cost_disc, false, $opt_s),
				"extras_total" => float_to_currencystr($extras_total, false, $opt_s),
				"extras_total_disc" => float_to_currencystr($extras_total_disc, false, $opt_s), 
				"tax_percentage" => float_to_currencystr($tax_percentage, false, $opt_s),
				"deposit_percentage" => float_to_currencystr($deposit_percentage, false, $opt_s),
				"deposit_fixed" => float_to_currencystr($deposit_fixed, false, $opt_s),
				"tax_total" => float_to_currencystr($tax_total, false, $opt_s),
				"deposit" => float_to_currencystr($deposit_total, false, $opt_s),
				"deposit_paypal" => number_format($deposit_total, 2, '.', ''),
				"total" => float_to_currencystr($total, false, $opt_s),
			);
			$carhire_cookie->car_total_payment_output = array( 
				"car_total" => float_to_currencystr($car_cost, true, $opt_s),
				"car_total_disc" => float_to_currencystr($car_cost_disc, true, $opt_s),
				"extras_total" => float_to_currencystr($extras_total, true, $opt_s),
				"extras_total_disc" => float_to_currencystr($extras_total_disc, true, $opt_s),
				"tax_total" => float_to_currencystr($tax_total, true, $opt_s),
				"deposit" => float_to_currencystr($deposit_total, true, $opt_s),
				"total" => float_to_currencystr($total, true, $opt_s),
			);
			
			// Step 4 cookie filter
			$carhire_cookie = apply_filters( 'step_'.$qs_step.'_cookie', $carhire_cookie, $params, $opt_s );

			// set cookie
			bizz_fill_booking_cookie($carhire_cookie);

			print json_encode($carhire_cookie);
		}
		// validating step 5 : user checkouts to payment --> read cookie
		else if ($qs_step == "5") {
			global $wpdb;

			// read form
			foreach ( $params as $param )
				$form_data[$param['name']] = $param['value'];

			// read cookie
			$carhire_cookie = array();
			$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));

			// Create post object
			$count_bookings = wp_count_posts('bizz_bookings');
			$title_bookings = __('Booking #', 'bizzthemes') . ($count_bookings->publish + 1);
			$booking_post = array(
				'post_title' => $title_bookings,
				'post_status' => 'publish',
				'post_type' => 'bizz_bookings'
			);

			// Insert the post into the database
			$this_post_id = wp_insert_post( $booking_post );

			// Build variables array
			$bookopts['tracking_id'] = bizz_rand_sha1(9);
			$bookopts['pay_total'] = $carhire_cookie->car_total_payment->total;
			$bookopts['pay_deposit'] = $carhire_cookie->car_total_payment->deposit;
			$bookopts['pay_car'] = $carhire_cookie->car_total_payment->car_total_disc;
			$bookopts['pay_extras'] = $carhire_cookie->car_total_payment->extras_total_disc;
			$bookopts['pay_tax'] = $carhire_cookie->car_total_payment->tax_total;
			$bookopts['car'] = $carhire_cookie->car_id;
			$bookopts['dealer_id'] = $carhire_cookie->dealer_id;
			$bookopts['dealer_email_id'] = $carhire_cookie->dealer_email_id;
			$bookopts['car_count'] = $carhire_cookie->car_count;
			$bookopts['extras'] = $carhire_cookie->car_extras;
			$bookopts['pickup_location'] = $carhire_cookie->location_of_pickup;
			$bookopts['pickup_location_slug'] = $carhire_cookie->location_of_pickup_slug;
			$bookopts['pickup_location_name'] = $carhire_cookie->location_of_pickup_name;
			$bookopts['return_location'] = $carhire_cookie->location_of_return;
			$bookopts['return_location_slug'] = $carhire_cookie->location_of_return_slug;
			$bookopts['return_location_name'] = $carhire_cookie->location_of_return_name;
			$bookopts['pickup_date'] = $carhire_cookie->date_of_pickup;
			$bookopts['pickup_hour'] = $carhire_cookie->hour_of_pickup;
			$bookopts['return_date'] = $carhire_cookie->date_of_return;
			$bookopts['return_hour'] = $carhire_cookie->hour_of_return;
			$bookopts['duration'] = $carhire_cookie->count_slots;
			$bookopts['flight'] = isset( $form_data['flight'] ) ? $form_data['flight'] : '';
			$bookopts['customer_title'] = isset( $form_data['customer_title'] ) ? $form_data['customer_title'] : '';
			$bookopts['customer_fname'] = isset( $form_data['first_name'] ) ? $form_data['first_name'] : '';
			$bookopts['customer_lname'] = isset( $form_data['last_name'] ) ? $form_data['last_name'] : '';
			$bookopts['customer_fullname'] = ( isset( $form_data['first_name'] ) && isset( $form_data['last_name'] ) ) ? $form_data['first_name'].' '.$form_data['last_name'] : '';
			$bookopts['customer_email'] = isset( $form_data['email'] ) ? $form_data['email'] : '';
			$bookopts['customer_phone'] = isset( $form_data['phone'] ) ? $form_data['phone'] : '';
			$bookopts['customer_contact_option'] = isset( $form_data['contact_option'] ) ? $form_data['contact_option'] : '';
			$bookopts['customer_country'] = isset( $form_data['countries'] ) ? $form_data['countries'] : '';
			$bookopts['customer_state'] = isset( $form_data['state_or_province'] ) ? $form_data['state_or_province'] : '';
			$bookopts['customer_city'] = isset( $form_data['city'] ) ? $form_data['city'] : '';
			$bookopts['customer_zip'] = isset( $form_data['postcode'] ) ? $form_data['postcode'] : '';
			$bookopts['customer_address'] = isset( $form_data['address'] ) ? $form_data['address'] : '';
			$bookopts['customer_driver_age'] = isset( $form_data['driver_age'] ) ? $form_data['driver_age'] : '';
			$bookopts['customer_driver_birth'] = isset( $form_data['driver_birth'] ) ? $form_data['driver_birth'] : '';
			$bookopts['customer_driver_license'] = isset( $form_data['driver_license'] ) ? $form_data['driver_license'] : '';
			$bookopts['customer_driver_country'] = isset( $form_data['driver_country'] ) ? $form_data['driver_country'] : '';
			$bookopts['customer_driver_issue'] = isset( $form_data['driver_issue'] ) ? $form_data['driver_issue'] : '';
			$bookopts['customer_driver_expiry'] = isset( $form_data['driver_expiry'] ) ? $form_data['driver_expiry'] : '';
			$bookopts['customer_driver_accidents'] = isset( $form_data['driver_accidents'] ) ? $form_data['driver_accidents'] : '';			
			$bookopts['customer_number_passengers'] = isset( $form_data['number_passengers'] ) ? $form_data['number_passengers'] : '';
			$bookopts['customer_comments'] = isset( $form_data['comms'] ) ? $form_data['comms'] : '';
			$bookopts['coupon'] = $carhire_cookie->coupon;

			// Add post meta
			add_post_meta($this_post_id, 'bizzthemes_bookings_track', $bookopts['tracking_id']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_total', $bookopts['pay_total']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_deposit', $bookopts['pay_deposit']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_car', $bookopts['pay_car']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_extras', $bookopts['pay_extras']);
			add_post_meta($this_post_id, 'bizzthemes_car_pay_tax', $bookopts['pay_tax']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_car', $bookopts['car']); #car id car_dealer_email_id
			add_post_meta($this_post_id, 'bizzthemes_bookings_dealer_id', $bookopts['dealer_id']); #dealer_id
			add_post_meta($this_post_id, 'bizzthemes_bookings_dealer_email_id', $bookopts['dealer_email_id']); #dealer_email_id
			add_post_meta($this_post_id, 'bizzthemes_bookings_car_count', $bookopts['car_count']); #car id
			// save extras: START
			foreach ( (array) $bookopts['extras'] as $key => $value ) {
				if ( isset($value[0]) )
					add_post_meta($this_post_id, 'bizzthemes_bookings_extras', $value[0]); #extras
				if ( isset($value[5]) )
					$extras_qty[$value[0]][] = $value[5];
				if ( isset($value[6]) )
					$extras_field[$value[0]][] = $value[6];
			}
			if ( isset($extras_qty) )
				add_post_meta($this_post_id, 'bizzthemes_bookings_extras_qty', $extras_qty); #qty
			if ( isset($extras_field) )
				add_post_meta($this_post_id, 'bizzthemes_bookings_extras_field', $extras_field); #qty
			// save extras: END
			add_post_meta($this_post_id, 'bizzthemes_bookings_pickup', $bookopts['pickup_location_slug']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_return', $bookopts['return_location_slug']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date1', $bookopts['pickup_date']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date1_time', $bookopts['pickup_hour']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date2', $bookopts['return_date']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_date2_time', $bookopts['return_hour']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_flight', $bookopts['flight']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_ctitle', $bookopts['customer_title']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_fname', $bookopts['customer_fname']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_lname', $bookopts['customer_lname']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_email', $bookopts['customer_email']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_phone', $bookopts['customer_phone']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_scontact', $bookopts['customer_contact_option']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_country', $bookopts['customer_country']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_state', $bookopts['customer_state']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_city', $bookopts['customer_city']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_zip', $bookopts['customer_zip']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_address', $bookopts['customer_address']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_age', $bookopts['customer_driver_age']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_birth', $bookopts['customer_driver_birth']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_license', $bookopts['customer_driver_license']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_country', $bookopts['customer_driver_country']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_issue', $bookopts['customer_driver_issue']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_expiry', $bookopts['customer_driver_expiry']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_driver_accidents', $bookopts['customer_driver_accidents']);			
			add_post_meta($this_post_id, 'bizzthemes_bookings_number_passengers', $bookopts['customer_number_passengers']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_comm_que', $bookopts['customer_comments']);
		if ( $opt_s['pay_credit'] ) {
			add_post_meta($this_post_id, 'bizzthemes_bookings_cc_type', $form_data['cc_type']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_cc_number', $form_data['cc_number']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_cc_expiration_m', $form_data['cc_expiration_m']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_cc_expiration_y', $form_data['cc_expiration_y']);
			add_post_meta($this_post_id, 'bizzthemes_bookings_cc_security', $form_data['cc_security']);
		}
		if ( isset( $form_data['payment_method'] ) && $form_data['payment_method'] != '' ) {
			add_post_meta($this_post_id, 'bizzthemes_car_pay_method', $form_data['payment_method']);
		}
		if ( isset( $carhire_cookie->coupon ) && $carhire_cookie->coupon != '' ) {
			add_post_meta($this_post_id, 'bizzthemes_bookings_coupon', $carhire_cookie->coupon);
		}

			// Date Time Format
			$pickup_date_format = date(get_option( 'date_format' ), strtotime($bookopts['pickup_date']));
			$pickup_time_format = date(get_option( 'time_format' ), strtotime($bookopts['pickup_hour']));
			$return_date_format = date(get_option( 'date_format' ), strtotime($bookopts['return_date']));
			$return_time_format = date(get_option( 'time_format' ), strtotime($bookopts['return_hour']));
			
			// Extras
			$extras = '';
			if ( is_array( $bookopts['extras'] ) ) {
				foreach ( $bookopts['extras'] as $key => $value ) {
					if ( isset($value[1]) ) {
						$extras .= '
						<tr><td>'.__('Extra', 'bizzthemes').' </td><td>'.$value[1].' x '.$value[5].'</td></tr>
						';
					}
				}
			}
			else {
				$extras .= '
				<tr><td colspan="2">'.__( 'No extras selected.', 'bizzthemes' ).'</td></tr>
				';
			}
			
			// Remove WPML home_url filter
			global $sitepress;
			remove_filter( 'home_url', array( $sitepress, 'home_url' ), 1, 4 );

			// Send via email
			$your_email = $bookopts['car_dealer_email_id'];
			$customer_email = $bookopts['customer_email'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
			$headers .= 'From: "'.$bookopts['customer_fullname'].'" <'.$customer_email.'>' . "\r\n";
			$emailTo = $your_email; 
			$subject = html_entity_decode( $title_bookings, ENT_QUOTES, 'UTF-8' );
			$body = '<html><body>';
			$body .= '<table rules="all" style="border-color:#dddddd;" cellpadding="10">';
			$body .= "<tr><td colspan='2'><strong>".__('Customer?', 'bizzthemes')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Tracking ID', 'bizzthemes')." </td><td>".$bookopts['tracking_id']."</td></tr>";
			$body .= "<tr><td>".__('Customer Title', 'bizzthemes')." </td><td>".$bookopts['customer_title']."</td></tr>";
			$body .= "<tr><td>".__('First Name', 'bizzthemes')." </td><td>".$bookopts['customer_fname']."</td></tr>";
			$body .= "<tr><td>".__('Last Name', 'bizzthemes')." </td><td>".$bookopts['customer_lname']."</td></tr>";
			$body .= "<tr><td>".__('Email', 'bizzthemes')." </td><td>".$bookopts['customer_email']."</td></tr>";
			$body .= "<tr><td>".__('Phone', 'bizzthemes')." </td><td>".$bookopts['customer_phone']."</td></tr>";
			$body .= "<tr><td>".__('Contact Option', 'bizzthemes')." </td><td>".$bookopts['customer_contact_option']."</td></tr>";
			$body .= "<tr><td>".__('Country', 'bizzthemes')." </td><td>".$bookopts['customer_country']."</td></tr>";
			$body .= "<tr><td>".__('State/Province', 'bizzthemes')." </td><td>".$bookopts['customer_state']."</td></tr>";
			$body .= "<tr><td>".__('Postcode/ZIP', 'bizzthemes')." </td><td>".$bookopts['customer_zip']."</td></tr>";
			$body .= "<tr><td>".__('Address', 'bizzthemes')." </td><td>".$bookopts['customer_address']."</td></tr>";
			$body .= "<tr><td>".__('Comments/Questions', 'bizzthemes')." </td><td>".$bookopts['customer_comments']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Vehicle?', 'bizzthemes')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Vehicle Name', 'bizzthemes')." </td><td>".get_the_title($bookopts['car'])."</td></tr>";
			$body .= "<tr><td>".__('Quantity', 'bizzthemes')." </td><td>".$bookopts['car_count']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Extras?', 'bizzthemes')."</strong></td></tr>";
			$body .= $extras;
			$body .= "<tr><td colspan='2'><strong>".__('When and Where?', 'bizzthemes')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Pickup Location', 'bizzthemes')." </td><td>".$bookopts['pickup_location_name']."</td></tr>";
			$body .= "<tr><td>".__('Return Location', 'bizzthemes')." </td><td>".$bookopts['return_location_name']."</td></tr>";
			$body .= "<tr><td>".__('Start Date and Time', 'bizzthemes')." </td><td>".$pickup_date_format.' @ '.$pickup_time_format."</td></tr>";
			$body .= "<tr><td>".__('Return Date and Time', 'bizzthemes')." </td><td>".$return_date_format.' @ '.$return_time_format."</td></tr>";
			$body .= "<tr><td>".__('Duration', 'bizzthemes')." </td><td>".$bookopts['duration']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Payment?', 'bizzthemes')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Total', 'bizzthemes')." </td><td>".get_bizz_currency($opt_s['pay_currency']).$bookopts['pay_total']."</td></tr>";
			$body .= "<tr><td>".__('Deposit', 'bizzthemes')." </td><td>".get_bizz_currency($opt_s['pay_currency']).$bookopts['pay_deposit']."</td></tr>";
			$body .= "<tr><td>".__('Vehicle', 'bizzthemes')." </td><td>".get_bizz_currency($opt_s['pay_currency']).$bookopts['pay_car']."</td></tr>";
			$body .= "<tr><td>".__('Extras', 'bizzthemes')." </td><td>".get_bizz_currency($opt_s['pay_currency']).$bookopts['pay_extras']."</td></tr>";
			$body .= "<tr><td>".__('Tax', 'bizzthemes')." </td><td>".get_bizz_currency($opt_s['pay_currency']).$bookopts['pay_tax']."</td></tr>";
			$body .= "<tr><td colspan='2'><strong>".__('Next?', 'bizzthemes')."</strong> </td></tr>";
			$body .= "<tr><td>".__('Action')." </td><td><a href='".home_url('/')."wp-admin/post.php?post=".$this_post_id."&action=edit'>".__('Accept or Cancel this booking', 'bizzthemes')."</a></td></tr>";
			$body .= "</table>";
			$body .= "</body></html>";
			$body = apply_filters( 'admin_email_notification_body', $body, $this_post_id, $bookopts, $opt_s );
			$body = html_entity_decode( $body, ENT_QUOTES, 'UTF-8' );

			if ( $opt_s['admin_notifications'] != 1 )
				wp_mail($your_email, $subject, $body, $headers); //you

			// notification function inside post-type-bookings.php
			booking_send_notification( 'customer', $bookopts, true );
			
			// Add back WPML home_url filter
			if ( class_exists( 'SitePress' ) ) {
				add_filter( 'home_url', array( $sitepress, 'home_url' ), 1, 4 );
			}

			// Success			
			$carhire_cookie->process = 'success';
			$carhire_cookie->payment_method = ( isset($form_data['payment_method']) && $form_data['payment_method'] != '' ) ? $form_data['payment_method'] : 'manual';
			$carhire_cookie->book_id = $this_post_id;
			$carhire_cookie->track_id = $bookopts['tracking_id'];
			
			// Redirect (optional)
			$carhire_cookie->redirect = apply_filters( 'bizz_payment_redirect', false, $bookopts, $form_data, $this_post_id, $opt_s );
			
			// Hook into final payments step
			do_action( 'bizz_payment_complete', $bookopts, $form_data, $this_post_id, $opt_s );
			
			// Step 5 cookie filter
			$carhire_cookie = apply_filters( 'step_'.$qs_step.'_cookie', $carhire_cookie, $params, $opt_s );

			print json_encode($carhire_cookie);
		}
		else if ($qs_step == "dc") {
			bizz_clear_booking_cookie();
			print "cookie cleared";
		}
		else if ($qs_step == "so") { // selected special offer
			// nothing here yet
		}
		/*
		else {
			header("HTTP/1.0 400 Bad request");
			print "Bad request! (unknown step)";
		}
		*/
	}
	/*
	else {
	header("HTTP/1.0 400 Bad request");
	print "Bad request! (step not defined)";
	}
	*/

	exit();
}

/* COOKIE */
          
//for logged-in users
add_action('wp_ajax_booking_cookie', 'bizz_booking_cookie');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_cookie', 'bizz_booking_cookie');	
  
function bizz_booking_cookie() { 
	$cookie = ( isset( $_COOKIE['carhire'] ) ) ? json_decode(stripslashes($_COOKIE['carhire'])) : 'nocookie';

	print json_encode( $cookie );
	exit();
}

function bizz_fill_booking_cookie($_array_to_store_to_cookie) {
	if (!empty($_COOKIE['carhire']))
		setcookie("carhire", '', time()-28800, '/'); #8 hours
	setcookie("carhire", json_encode($_array_to_store_to_cookie), time()+28800, '/'); #8 hours
}

function bizz_clear_booking_cookie() {
	if (!empty($_COOKIE['carhire']))
		setcookie("carhire", '', time()-28800, '/'); #8 hours
}

// return available cars
function bizz_return_cars( $carhire_cookie='', $slots_array=array(), $pricing_posts=array(), $booking_posts=array(), $coupon_post='', $opt_s=array() ) {	
	$args = apply_filters( 'bizz_car_return', array( 
		'post_type' => 'bizz_cars', 
		'numberposts' => -1,
		'suppress_filters' => false
	) );
	$car_posts = get_posts( $args );
	$car_options["cars"] = array();
	foreach ($car_posts as $car_post) {
	
		// query
		$custom = get_post_custom( $car_post->ID );
		$available = bizz_availablity( $car_post->ID, $carhire_cookie, $booking_posts, $slots_array, $opt_s );
		
		// date ok?
		if ( empty( $available['date'] ) ) {
			$available['date'] = 'ok';
		}
		
		// location ok?
		if ( empty( $available['location'] ) ) {
			$available['location'] = 'ok';
		}
		
		// count
		$car_count = ( isset($custom["bizzthemes_car_stock"][0]) ) ? $custom["bizzthemes_car_stock"][0] : 1;
		if ( $car_count == 'out' ) { #out of stock match
			$available['date'] = __('Vehicle is out of stock', 'bizzthemes');
		}
		$car_count = ( $car_count == 'out' ) ? 0 : $car_count; #out of stock match
		$car_count = $car_count - $available['stock'];
		if ( $car_count >= 1 || ( $opt_s['skip_time'] == '1' ) ) {
			$available['date'] = 'ok';
		}
				
		// vars
		$availability = ( $available['date'] == 'ok' && $available['location'] == 'ok' ) ? 1 : 0;
		$cost = bizz_car_price( $car_post->ID, $custom["bizzthemes_car_type"], $carhire_cookie, 0, $slots_array, $pricing_posts, '', $opt_s );
		$cost_disc = bizz_car_price( $car_post->ID, $custom["bizzthemes_car_type"], $carhire_cookie, 0, $slots_array, $pricing_posts, $coupon_post, $opt_s );
		$car_img = ( isset($custom["bizzthemes_car_image"][0]) ) ? $custom["bizzthemes_car_image"][0] : get_template_directory_uri() . '/lib_theme/images/no-img.jpg';
		$tax_percentage = ( isset($opt_s['pay_tax']) ) ? currencystr_to_float($opt_s['pay_tax'], $opt_s) / 100 : 1;
		
		// from pricing
		$price_range = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('per hour', 'bizzthemes') : __('per day', 'bizzthemes');
		$pricing = $cost / $carhire_cookie['count_slots']; #divide cost by days
		$pricing_disc = $cost_disc / $carhire_cookie['count_slots']; #divide cost by days
		$price = float_to_currencystr(($pricing+($pricing * $tax_percentage)), true, $opt_s).' '.$price_range;
		$price_disc = float_to_currencystr(($pricing_disc+($pricing_disc * $tax_percentage)), true, $opt_s).' '.$price_range;
		
		// hide unavailable
		if ( ! $availability && $opt_s['hide_unavailable'] == '1' ) {
			continue;
		}
			
		$car_options["cars"][] = array(
			'id' => $car_post->ID,
			'post_name' => $car_post->post_name,
			'name' => $car_post->post_title,
			'description' => wpautop(do_shortcode($custom["bizzthemes_car_description"][0])),
			'edit' => get_edit_post_link( $car_post->ID ),
			'picture_src' => $car_img,
			'type' => $custom["bizzthemes_car_type"],
			'currency' => get_bizz_currency($opt_s['pay_currency']),
			'cost' => ($cost==0) ? 'not-set' : float_to_currencystr(($cost+($cost * $tax_percentage)), true, $opt_s),
			'cost_disc' => ($cost_disc==0) ? 'not-set' : float_to_currencystr(($cost_disc+($cost_disc * $tax_percentage)), true, $opt_s),
			'cost_val' => ($cost==0) ? 'not-set' : $cost,
			'cost_val_disc' => ($cost_disc==0) ? 'not-set' : $cost_disc,
			'cost_int' => ($price=="") ? 'not-set' : $price,
			'cost_int_disc' => ($price_disc=="") ? 'not-set' : $price_disc,
			'equipment' => array(
				'seats' => $custom["bizzthemes_car_seats"][0],
				'doors' => $custom["bizzthemes_car_doors"][0],
				'transmission' => $custom["bizzthemes_car_transmission"][0]
			),
			'availability' => $availability,
			'avail_date' => $available['date'],
			'avail_location' => $available['location'],
			'count' => $car_count,
		);
	}
	
	$car_options["cars"] = apply_filters( 'bizz_car_sort', bizz_list_sort( $car_options["cars"], 'availability' ), $car_options["cars"] );

	return json_encode( $car_options );
}

function bizz_list_sort( $a, $subkey, $order = 'desc' ) {
	
	$b = array();
	foreach( (array) $a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	
	if ( $order == 'desc' ) {
		arsort($b); // desc
	}
	else {
		asort($b); // asc
	}
	
	$c = array();
	foreach( (array) $b as $key=>$val) {
		$c[] = $a[$key];
	}

	return apply_filters( 'bizz_list_sort', $c );
}

// calculate car price by car id
function bizz_car_price_byid( $car_id, $carhire_cookie, $slots_array, $pricing_posts, $coupon_post='', $opt_s ) {

	// read posts
	$car_post = get_post( $car_id );
	if ( $car_post ) {
		$custom = get_post_custom( $car_id );
		$car_type = $custom['bizzthemes_car_type'];
		if ( isset( $car_type ) && $car_type != '' ) {
			$price = bizz_car_price( $car_id, $car_type, $carhire_cookie, 1, $slots_array, $pricing_posts, $coupon_post, $opt_s );
			return $price;
		} else
			return false; /* car type not found */
	} else
		return false; /* car not found */

}

// calculate the car price
function bizz_car_price( $car_id='', $car_type='', $carhire_cookie, $isobject=0, $slots_array, $pricing_posts, $coupon_post='', $opt_s ) {	
	// count days
	$count_slots = ( $isobject==1 ) ? $carhire_cookie->count_slots : $carhire_cookie['count_slots'];
	
	// coupon applicable?
	$coupon_works = apply_filters( 'coupon_filter', true, $car_id, $carhire_cookie, $slots_array, $pricing_posts, $coupon_post, $opt_s );
	
	// sum prices per each day
	$cost = 0;
	$i = 0;
	foreach ( (array) $slots_array as $day) { $i++;
		// chop off a day if less than 24 hours
		if ( $count_slots < $i )
			break;
		// sum the price
		$cost += bizz_car_day_price( $car_id, $car_type, $count_slots, $day, $pricing_posts, $opt_s );
		// apply coupon per unit
		if ( $coupon_post && ( in_array( $car_id, $coupon_post['car'] ) || in_array( 'all', $coupon_post['car'] ) ) && ( $coupon_post['type'] == 'fixed_unit' ) && $coupon_works ) {
			$cost = $cost - $coupon_post['amount'];
		}
	}
	
	// apply coupon per percantage
	if ( $coupon_post && ( in_array( $car_id, $coupon_post['car'] ) || in_array( 'all', $coupon_post['car'] ) ) && ( $coupon_post['type'] == 'percentage' ) && $coupon_works ) {
		$percentage = currencystr_to_float($coupon_post['amount'], $opt_s) / 100;
		$cost = $cost-($cost * $percentage);
	}
	
	// apply coupon per block
	if ( $coupon_post && ( in_array( $car_id, $coupon_post['car'] ) || in_array( 'all', $coupon_post['car'] ) ) && ( $coupon_post['type'] == 'fixed_block' ) && $coupon_works ) {
		$cost = $cost - $coupon_post['amount'];
	}

	return $cost;
}

function bizz_car_day_price( $car_id='', $car_type='', $count_slots='', $day, $pricing_posts, $opt_s ) {

	// only date, no time for seasonal pricing
	$day_data = explode(',', $day);
	$day = $day_data[0];
	
	// car pricing
	$car_pricing = get_post_meta( $car_id, 'bizzthemes_car_pricing', true );
	
	// get the price
	$price_season = array();
	$price = array();
	foreach ( $pricing_posts as $post_id ) {
		$post_custom = get_post_custom( $post_id );
		$queried_post = get_post( $post_id );
		
		// skip other car types
		if ( $car_pricing && ! in_array( $queried_post->post_name, $car_pricing ) ) {
			continue;
		}
		// DEPRECATED
		else if ( ! $car_pricing && isset( $post_custom['bizzthemes_price_type'][0] ) && ( $car_type[0] != $post_custom['bizzthemes_price_type'][0] ) ) {
			continue;
		}
			    
		$post_price_season_from = isset( $post_custom['bizzthemes_price_season_from'][0] ) ? $post_custom['bizzthemes_price_season_from'][0] : '';
		$post_price_season_to = isset( $post_custom['bizzthemes_price_season_to'][0] ) ? $post_custom['bizzthemes_price_season_to'][0] : '';
		$post_price = currencystr_to_float( ( isset( $post_custom['bizzthemes_price_daily'][0] ) ? $post_custom['bizzthemes_price_daily'][0] : 0 ), $opt_s );
			
		// seasonal pricing
		if
		  (
		  ( ( $post_price_season_from ) && ( $post_price_season_to ) && ( $post_price_season_from <= $day ) && ( $post_price_season_to >= $day ) ) #seasonal pricing, both from and to dates are set 
		  ||
		  ( ( $post_price_season_from ) && ( !$post_price_season_to ) && ( $post_price_season_from <= $day ) ) #seasonal pricing, only from is set
		  ||
		  ( ( !$post_price_season_from ) && ( $post_price_season_to ) && ( $post_price_season_to >= $day ) ) #seasonal pricing, only to is set
		  )
		{
			$price_season[] = $post_price; #vehicle price
		}

		// non-seasonal pricing
		if ( ( !$post_price_season_from ) && ( !$post_price_season_to ) ) {
			$price[] = $post_price;
		}
				
	}

	// price
	if ( !empty( $price_season ) ) 
		$pricing = min( $price_season ); #take lowest
	elseif ( !empty( $price ) )
		$pricing = min( $price ); #take lowest   
	else
		$pricing = 0;

	return $pricing;
}

function bizz_get_pricing_posts( $count_slots='' ) {
	// query pricing posts
	$args = array(
		'post_type' => 'bizz_pricing',
		'numberposts' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			// price range from
			array(
				'key' => 'bizzthemes_price_range_from' ,
				'value' => $count_slots,
				'compare' => '<=',
				'type' => 'NUMERIC'
			),
			// price range to
			array(
				'key' => 'bizzthemes_price_range_to' ,
				'value' => $count_slots,
				'compare' => '>=',
				'type' => 'NUMERIC'
			)
		)
	);
	$pricing_posts = get_posts( $args );
	
	return $pricing_posts;
}

function bizz_slot_array( $startDate, $endDate, $startTime, $endTime, $opt_s ){
	// Just to be sure - feel free to drop these if you're sure of the input
	$startDate = apply_filters( 'carhire_slot_array_atart', strtotime( $startDate .' '. $startTime ), $startDate, $endDate, $startTime, $endTime );
	$endDate   = apply_filters( 'carhire_slot_array_end', strtotime( $endDate .' '. $endTime ), $startDate, $endDate, $startTime, $endTime );

	// New Variables
	$dayArray  = array();

	// Loop until we have the Array
	if ( $opt_s['pay_pricerange'] == 'perhour' ) {
		do {
			$dayArray[] = date( 'Y-m-d, H:i', $startDate );
			$startDate = strtotime( '+1 hour', $startDate );
		} while( $startDate<=$endDate );
	}
	else {
		do {
			$dayArray[] = date( 'Y-m-d, H:i', $startDate );
			$startDate = strtotime( '+1 day', $startDate );
		} while( $startDate<=$endDate );
	}

	// Return the Array
	return $dayArray;
}

// return availability for each car
function bizz_availablity( $car_id = '', $carhire_cookie = '', $booking_posts = '', $slots_array = '', $opt_s = '' ) {	
	global $wpdb;
	
	// locale date and time strings			
	$date_format = get_option('date_format', 'Y-m-d');
	$time_format = get_option('time_format', 'H:i');
	
	// custom car post meta
	$custom_car = get_post_custom($car_id);
		
	// pickup locations
	$pickup_locations = isset( $custom_car["bizzthemes_car_location"] ) ? $custom_car["bizzthemes_car_location"] : array();
	$pickup_location_ids = array();
	foreach ( (array) $pickup_locations as $pickup_location ) {
		$pickup_location_o = get_page_by_path( $pickup_location, 'OBJECT', 'bizz_locations' );
		$pickup_location_ids[] = ( $pickup_location_o ) ? $pickup_location_o->ID : $pickup_location;
	}
	
	// return locations
	$return_locations = isset( $custom_car["bizzthemes_car_location_return"] ) ? $custom_car["bizzthemes_car_location_return"] : ( isset( $custom_car["bizzthemes_car_location"] ) ? $custom_car["bizzthemes_car_location"] : array() );
	$return_location_ids = array();
	foreach ( (array) $return_locations as $return_location ) {
		$return_location_o = get_page_by_path( $return_location, 'OBJECT', 'bizz_locations' );
		$return_location_ids[] = ( $return_location_o ) ? $return_location_o->ID : $return_location;
	}

	// strtotime
	$pickup_ctime = strtotime( $carhire_cookie['date_of_pickup'] . ', ' . $carhire_cookie['hour_of_pickup'] );
	$return_ctime = strtotime( $carhire_cookie['date_of_return'] . ', ' . $carhire_cookie['hour_of_return'] );
	  
	// read bookings
	$avail_error['date'] = false;
	$avail_error['location'] = '';
	$avail_error['stock'] = 0;
	$stock = 0;
	$date = false;
	if ( (array) $booking_posts ) {
		foreach ( $booking_posts as $post_id ) {
			
			// custom booking post meta
			$custom = get_post_custom($post_id);
			
			// check return location
			if ( ! empty( $return_location_ids ) && ! in_array( $carhire_cookie['location_of_return'], $return_location_ids ) && ( $opt_s['skip_location'] != '1' ) ) {
				$avail_error['location'] = __('Not available for selected return location', 'bizzthemes');
			}
						
			// check pickup location
			if ( ! empty( $pickup_location_ids ) && ! in_array( $carhire_cookie['location_of_pickup'], $pickup_location_ids ) && ( $opt_s['skip_location'] != '1' ) ) {
				$avail_error['location'] = __('Not available for selected pickup location', 'bizzthemes');
			}
			
			// skip other cars
			if ( $car_id != $custom['bizzthemes_bookings_car'][0] ) {
				continue;
			}
			
			$booking["status"] = (isset($custom["bizzthemes_bookings_status"][0])) ? $custom["bizzthemes_bookings_status"][0] : 'pending';
			$booking["date_of_pickup"] = (isset($custom["bizzthemes_bookings_date1"][0])) ? $custom["bizzthemes_bookings_date1"][0] : '';
			$booking["hour_of_pickup"] = (isset($custom["bizzthemes_bookings_date1_time"][0])) ? $custom["bizzthemes_bookings_date1_time"][0] : '';
			$booking["date_of_return"] = (isset($custom["bizzthemes_bookings_date2"][0])) ? $custom["bizzthemes_bookings_date2"][0] : '';
			$booking["hour_of_return"] = (isset($custom["bizzthemes_bookings_date2_time"][0])) ? $custom["bizzthemes_bookings_date2_time"][0] : '';
			$booking["location_of_pickup"] = (isset($custom["bizzthemes_bookings_pickup"][0])) ? $custom["bizzthemes_bookings_pickup"][0] : '';
			$booking["location_of_return"] = (isset($custom["bizzthemes_bookings_return"][0])) ? $custom["bizzthemes_bookings_return"][0] : '';
			$car_count = (isset($custom["bizzthemes_bookings_car_count"][0])) ? ( $custom["bizzthemes_bookings_car_count"][0] - 1 ) : 0;

			// strtotime
			$pickup_btime = strtotime( $booking["date_of_pickup"] . ', ' . $booking["hour_of_pickup"] );
			$return_btime = strtotime( $booking["date_of_return"] . ', ' . $booking["hour_of_return"] );

			// testing
			/*
			echo $car_id.'<br/>';
			echo 'pickup selected: '. $pickup_ctime . ' - ' . $carhire_cookie['date_of_pickup'] . ', ' . $carhire_cookie['hour_of_pickup'] . '<br/>';
			echo 'pickup booked: '. $pickup_btime . ' - ' . $booking["date_of_pickup"] . ', ' . $booking["hour_of_pickup"] . '<br/>';
			echo 'return selected: '. $return_ctime . ' - ' . $carhire_cookie['date_of_return'] . ', ' . $carhire_cookie['hour_of_return'] . '<br/>';
			echo 'return booked: '. $return_btime . ' - ' . $booking["date_of_return"] . ', ' . $booking["hour_of_return"] . '<br/>';
			echo 'status booked: '. $booking["status"] . '<br/><br/>';
			*/

			// check status
			$avail_error['status'] = ($booking["status"] == 'completed' || $booking["status"] == 'approved') ? true : false;
			
			// check all date intervals
			foreach ( (array) $slots_array as $day) {
				if ( ( ( strtotime($day) == $pickup_btime ) || ( strtotime($day) == $return_btime ) ) && $avail_error['status'] ) {
					$avail_error['date'] = sprintf(__('Vehicle already booked from %1$s, %2$s to %3$s, %4$s', 'bizzthemes'), date($date_format, strtotime($booking["date_of_pickup"])), date($time_format, strtotime($booking["hour_of_pickup"])), date($date_format, strtotime($booking["date_of_return"])), date($time_format, strtotime($booking["hour_of_return"])));
					break;
				}
				else {
					$avail_error['date'] = false;
				}
			}
			 
			// check only pickup and return dates
			if ( ( $pickup_ctime >= $pickup_btime ) && ( $pickup_ctime <= $return_btime ) && $avail_error['status'] ) { #pickup
				$avail_error['date'] = sprintf(__('Vehicle already booked from %1$s, %2$s to %3$s, %4$s', 'bizzthemes'), date($date_format, strtotime($booking["date_of_pickup"])), date($time_format, strtotime($booking["hour_of_pickup"])), date($date_format, strtotime($booking["date_of_return"])), date($time_format, strtotime($booking["hour_of_return"])));
			}
			elseif ( ( $return_ctime >= $pickup_btime ) && ( $return_ctime <= $return_btime ) && $avail_error['status'] ) { #return
				$avail_error['date'] = sprintf(__('Vehicle already booked from %1$s, %2$s to %3$s, %4$s', 'bizzthemes'), date($date_format, strtotime($booking["date_of_pickup"])), date($time_format, strtotime($booking["hour_of_pickup"])), date($date_format, strtotime($booking["date_of_return"])), date($time_format, strtotime($booking["hour_of_return"])));
			}
			elseif ( ( $pickup_ctime <= $pickup_btime ) && ( $return_ctime >= $return_btime ) && $avail_error['status'] ) { #between
				$avail_error['date'] = sprintf(__('Vehicle already booked from %1$s, %2$s to %3$s, %4$s', 'bizzthemes'), date($date_format, strtotime($booking["date_of_pickup"])), date($time_format, strtotime($booking["hour_of_pickup"])), date($date_format, strtotime($booking["date_of_return"])), date($time_format, strtotime($booking["hour_of_return"])));
			}
			else {
				$avail_error['date'] = false;
			}
			
			// stock checking			
			if ( $avail_error['date'] && $avail_error['status'] ) {
				$stock += $car_count + 1; # add -1 to stock quantity
				$date = $avail_error['date']; # add -1 to stock quantity
			}
			
		}
		
		// date overall
		$avail_error['date'] =  $date;
				
		// stock sum
		$avail_error['stock'] =  $stock;
		
	}
	else {
			
		// check return location
		if ( ! empty( $return_location_ids ) && ! in_array( $carhire_cookie['location_of_return'], $return_location_ids ) && ( $opt_s['skip_location'] != '1' ) ) {
			$avail_error['location'] = __('Not available for selected return location', 'bizzthemes');
		}
					
		// check pickup location
		if ( ! empty( $pickup_location_ids ) && ! in_array( $carhire_cookie['location_of_pickup'], $pickup_location_ids ) && ( $opt_s['skip_location'] != '1' ) ) {
			$avail_error['location'] = __('Not available for selected pickup location', 'bizzthemes');
		}

	}

	return $avail_error;
}

function bizz_get_booking_posts() {
	// query booking posts
	$args = array(
		'post_type' => 'bizz_bookings',
		'fields' => 'ids',
		'numberposts' => -1
	);
	$booking_posts = get_posts( $args );
	
	return $booking_posts;
}

// return all coupons
function bizz_get_coupons( $coupon_posts=array() ) {  
	// query booking posts
	$args = array(
		'post_type' => 'bizz_coupons',
		'fields' => 'ids',
		'numberposts' => -1
	);
	$coupon_posts = get_posts( $args );
	
	if ( (array) $coupon_posts ) {
		foreach ( $coupon_posts as $coupon_id ) {
			
			$coupon_posts['type'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_type', TRUE );
			$coupon_posts['name'] = get_the_title( $coupon_id );
			$coupon_posts['amount'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_amount', TRUE );
			$coupon_posts['car'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_car', FALSE );
			$coupon_posts['extra'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_extra', FALSE );
			
		}
	}
	
	return $coupon_posts;	
}

// return specific coupon
function bizz_get_coupon( $coupon_code='', $coupon_post='' ) {  

	// shortcut the coupon code
	do_action( 'bizz_get_coupon_filter', $coupon_code, $coupon_post );
	
	if ( ! $coupon_code ) {
		return '';
	}
	
	$coupon_posts = bizz_get_coupons();
	
	if ( (array) $coupon_posts ) {
		foreach ( $coupon_posts as $coupon_id ) {
			if ( $coupon_code != get_the_title( $coupon_id ) ) {
				continue;
			}
			$coupon_post['type'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_type', TRUE );
			$coupon_post['name'] = get_the_title( $coupon_id );
			$coupon_post['amount'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_amount', TRUE );
			$coupon_post['car'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_car', FALSE );
			$coupon_post['extra'] = get_post_meta( $coupon_id, 'bizzthemes_coupon_extra', FALSE );
			
		}
	}
	
	return $coupon_post;	
}

// return extras per each car
function bizz_return_car_extras( $car_id='', $coupon_post='', $opt_s='') {

	// read cookie
	$carhire_cookie = array();
	$carhire_cookie = json_decode(stripslashes($_COOKIE['carhire']));

	// list extras
	$extras = get_terms( 'bizz_cars_extra', apply_filters( 'bizz_cars_extra_args', array( 'hide_empty' => 0 ) ) );
	$count = count( $extras );
	$car_extras["car_extras"] = array();
	$custom = get_post_custom($car_id); #car id
	$car_extras = isset($custom["bizzthemes_car_extras"]) ? $custom["bizzthemes_car_extras"] : '';
	$car_extras = ( is_array($car_extras) ) ? $car_extras : array();
	
	if ($count > 0) {
	
		foreach ($extras as $extra) {
			if ( !in_array($extra->slug, $car_extras) ) #1476 for porsche
				continue;

			// thumbnail
			$extra_img = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_image');
			$extra_img = ( $extra_img ) ? $extra_img : get_template_directory_uri() . '/lib_theme/images/no-img.jpg';
			
			// count
			$extra_count = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_count');
			$extra_count = ( $extra_count ) ? $extra_count : 1;
			
			// required
			$extra_required = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_required');
			$extra_required = ( $extra_required ) ? $extra_required : 0;
			
			// field
			$extra_field = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_field');
			$extra_field = ( $extra_field ) ? $extra_field : 0;
			
			// field placeholder
			$extra_field_placeholder = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_field_placeholder');
			$extra_field_placeholder = ( $extra_field_placeholder ) ? $extra_field_placeholder : '';
			
			// bind to pickup location
			$extra_location = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_location');
			$extra_location = ( $extra_location ) ? $extra_location : array();
			$extra_location = ( is_array( $extra_location ) ) ? $extra_location : array ( $extra_location );
			
			// bind to return location
			$extra_location_return = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_location_return');
			$extra_location_return = ( $extra_location_return ) ? $extra_location_return : array();
			$extra_location_return = ( is_array( $extra_location_return ) ) ? $extra_location_return : array ( $extra_location_return );
			
			// bind to different location
			$extra_location_diff = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_location_diff');
			$extra_location_diff = ( $extra_location_diff ) ? $extra_location_diff : 0;
			
			// range
			$range = get_option('taxonomy_'.$extra->term_id.'_bizz_extra_price_s');
			$day_hour = ( $opt_s['pay_pricerange'] == 'perhour'  ) ? __('per hour', 'bizzthemes') : __('per day', 'bizzthemes');
			$extra_range = ( $range == 'rental'  ) ? __('per rental', 'bizzthemes') : $day_hour;
			$extra_price = float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, true, '', $opt_s, true ), true, $opt_s) . ' ' . $extra_range;
			$extra_price_disc = float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, true, $coupon_post, $opt_s, true ), true, $opt_s) . ' ' . $extra_range;
						
			// skip if extra bound to pickup location and pickup location is different and should be same
			if ( count( $extra_location ) >= 1 && count( array_intersect( array( $carhire_cookie->location_of_pickup, $carhire_cookie->location_of_pickup_slug ), $extra_location ) ) == 0 ) {
				continue;
			}
			
			// skip if extra bound to return location and return location is different and should be same
			if ( count( $extra_location_return ) >= 1 && count( array_intersect( array( $carhire_cookie->location_of_return, $carhire_cookie->location_of_return_slug ), $extra_location_return ) ) == 0 ) {
				continue;
			}
			
			// skip if extra bound to location and location is same, but should be different
			if ( $extra_location_diff && $carhire_cookie->location_of_pickup == $carhire_cookie->location_of_return ) {
				continue;
			}

			$car_extras["car_extras"][] = array(
				'id' => $extra->term_id,
				'slug' => $extra->slug,
				'name' => $extra->name,
				'description' => do_shortcode($extra->description),
				'cost' => float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, true, '', $opt_s ), true, $opt_s),
				'cost_disc' => float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, true, $coupon_post, $opt_s ), true, $opt_s),
				'cost_val' => float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, false, $coupon_post, $opt_s ), true, $opt_s),
				'cost_val_disc' => float_to_currencystr(bizz_extra_price( $extra->term_id, $carhire_cookie, false, '', $opt_s ), true, $opt_s),
				'cost_int' => $extra_price,
				'cost_int_disc' => $extra_price_disc,
				'cost_n' => bizz_extra_price( $extra->term_id, $carhire_cookie, false, $coupon_post, $opt_s ),
				'picture_src' => $extra_img,
				'currency' => get_bizz_currency($opt_s['pay_currency']),
				'count' => $extra_count,
				'required' => $extra_required,
				'availability' => 1,
				'field' => $extra_field,
				'field_placeholder' => $extra_field_placeholder,
			);
			
		}
		
		if ( isset( $car_extras["car_extras"] ) && ! class_exists( 'TheTaxonomySort' ) ) {
			$car_extras["car_extras"] = apply_filters( 'bizz_extras_sort', bizz_list_sort( $car_extras["car_extras"], 'required', 'asc' ), $car_extras["car_extras"] );
		}

	}

	return json_encode( $car_extras );
}

// calculate the extra price
function bizz_extra_price( $extra_id='', $carhire_cookie='', $taxed=false, $coupon_post='', $opt_s='', $single=false ) {
	// tax percentage
	$tax_percentage = ( isset($opt_s['pay_tax']) ) ? currencystr_to_float($opt_s['pay_tax'], $opt_s) / 100 : 1;
	
	// price
	$price = get_option('taxonomy_'.$extra_id.'_bizz_extra_price');
	$price = currencystr_to_float($price, $opt_s);
	$max_price = get_option('taxonomy_'.$extra_id.'_bizz_max_extra_price');
	$max_price = currencystr_to_float($max_price, $opt_s);
	
	// apply coupon per unit
	if ( $coupon_post && ( in_array( $extra_id, $coupon_post['extra'] ) || in_array( 'all', $coupon_post['extra'] ) ) && ( $coupon_post['type'] == 'fixed_unit' ) ) {
		$price = $price - $coupon_post['amount'];
	}
	
	// tax
	if ( $taxed ) {
		$price = $price+($price * $tax_percentage);
	}
		
	// range
	$range = get_option('taxonomy_'.$extra_id.'_bizz_extra_price_s');
	
	// per rental or daily?
	$calculate = ( $range == 'rental' ) ? 1 : $carhire_cookie->count_slots;
	
	// price for all booked days
	$price = ( $price * $calculate );
	
	// apply coupon per percantage
	if ( $coupon_post && ( in_array( $extra_id, $coupon_post['extra'] ) || in_array( 'all', $coupon_post['extra'] ) ) && ( $coupon_post['type'] == 'percentage' ) ) {
		$percentage = currencystr_to_float($coupon_post['amount'], $opt_s) / 100;
		$price = $price-($price * $percentage);
	}
	
	// apply coupon per block
	if ( $coupon_post && ( in_array( $extra_id, $coupon_post['extra'] ) || in_array( 'all', $coupon_post['extra'] ) ) && ( $coupon_post['type'] == 'fixed_block' ) ) {
		$price = $price - $coupon_post['amount'];
	}
	
	// force single
	if ( $single ) {
		$price = ( $price / $calculate );
	}
	
	// max price?
	if ( $max_price && $max_price < $price ) {
		$price = $max_price;
	}

	return $price;
}

/* Deprecated */
/*------------------------------------------------------------------*/
if ( has_filter( 'carhire_count_days' ) ) {
	add_filter( 'carhire_count_slots', 'deprecated_carhire_count_slots' );
}
function deprecated_carhire_count_slots( $count ) {
	return apply_filters( 'carhire_count_days', $count );
}

// return the number of days between the two dates passed in
function bizz_count_slots( $a, $b, $hours = false, $opt_s ) {
	// First we need to break these dates into their constituent parts:
    $gd_a = apply_filters( 'carhire_gd_a', getdate( $a ), getdate( $b ) );
    $gd_b = apply_filters( 'carhire_gd_b', getdate( $b ), getdate( $a ) );

    // Now recreate these timestamps, based upon noon on each day
    $a_new = mktime( $gd_a['hours'], $gd_a['minutes'], 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
    $b_new = mktime( $gd_b['hours'], $gd_b['minutes'], 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );

    // Subtract these two numbers and divide by the number of seconds in a
    //  day. Round the result since crossing over a daylight savings time
    //  barrier will cause this time to be off by an hour or two.
	if ( $hours  )
		$count = ceil( abs( $a_new - $b_new ) / (60*60) ); #hours
	else
		$count = ceil( abs( $a_new - $b_new ) / (60*60*24) ); #days
	
	# $count = ceil( abs( $a_new - $b_new ) / (30*60*60*24) ); #months
	# $count = ceil( abs( $a_new - $b_new ) / 60 ); #minutes
	$count = ( $count == 0 ) ? 1 : $count;
	
	return apply_filters( 'carhire_count_slots', $count );
}

// Currency symbol
function get_bizz_currency( $currency = '', $key = 'symbol' ) {

	$currency_symbol = array(
		'USD' => array( 'name' => __( 'US Dollars (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'EUR' => array( 'name' => __( 'Euros (&euro;)', 'bizzthemes' ), 'symbol' => '&euro;' ),
		'GBP' => array( 'name' => __( 'Pounds Sterling (&pound;)', 'bizzthemes' ), 'symbol' => '&pound;' ),
		'AUD' => array( 'name' => __( 'Australian Dollars (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'BRL' => array( 'name' => __( 'Brazilian Real (&#36;)', 'bizzthemes' ), 'symbol' => '&#82;&#36;' ),
		'CAD' => array( 'name' => __( 'Canadian Dollars (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'CZK' => array( 'name' => __( 'Czech Koruna (&#75;&#269;)', 'bizzthemes' ), 'symbol' => '&#75;&#269;' ),
		'DKK' => array( 'name' => __( 'Danish Krone', 'bizzthemes' ), 'symbol' => '&#107;&#114;' ),
		'HKD' => array( 'name' => __( 'Hong Kong Dollar (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'HUF' => array( 'name' => __( 'Hungarian Forint', 'bizzthemes' ), 'symbol' => '&#70;&#116;' ),
		'ILS' => array( 'name' => __( 'Israeli Shekel', 'bizzthemes' ), 'symbol' => '&#8362;' ),
		'RMB' => array( 'name' => __( 'Chinese Yuan (&yen;)', 'bizzthemes' ), 'symbol' => '&yen;' ),
		'INR' => array( 'name' => __( 'Indian National Rupee (&#8377;)', 'bizzthemes' ), 'symbol' => '&#8377;' ),
		'JPY' => array( 'name' => __( 'Japanese Yen (&yen;)', 'bizzthemes' ), 'symbol' => '&yen;' ),
		'MYR' => array( 'name' => __( 'Malaysian Ringgits (RM)', 'bizzthemes' ), 'symbol' => '&#82;&#77;' ),
		'MXN' => array(	'name' => __( 'Mexican Peso (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'NZD' => array( 'name' => __( 'New Zealand Dollar (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'NOK' => array( 'name' => __( 'Norwegian Krone', 'bizzthemes' ), 'symbol' => '&#107;&#114;' ),
		'PHP' => array( 'name' => __( 'Philippine Pesos', 'bizzthemes' ), 'symbol' => '&#8369;' ),
		'PLN' => array( 'name' => __( 'Polish Zloty', 'bizzthemes' ), 'symbol' => '&#122;&#322;' ),
		'SGD' => array( 'name' => __( 'Singapore Dollar (&#36;)', 'bizzthemes' ), 'symbol' => '&#36;' ),
		'SEK' => array( 'name' => __( 'Swedish Krona', 'bizzthemes' ), 'symbol' => '&#107;&#114;' ),
		'CHF' => array( 'name' => __( 'Swiss Franc', 'bizzthemes' ), 'symbol' => '&#67;&#72;&#70;' ),
		'TWD' => array( 'name' => __( 'Taiwan New Dollars', 'bizzthemes' ), 'symbol' => '&#78;&#84;&#36;' ),
		'THB' => array( 'name' => __( 'Thai Baht', 'bizzthemes' ), 'symbol' => '&#3647;' ),
		'TRY' => array( 'name' => __( 'Turkish Lira (TL)', 'bizzthemes' ), 'symbol' => '&#84;&#76;' ),
		'ZAR' => array( 'name' => __( 'South African rand (R)', 'bizzthemes' ), 'symbol' => '&#82;' ),
		'RON' => array( 'name' => __( 'Romanian Leu (RON)', 'bizzthemes' ), 'symbol' => 'lei' ),
	);
	$currency_symbol = apply_filters( 'bizzthemes_currency_symbol', $currency_symbol );

	// deprecated
	if ( $currency == '$' )
		$currency = 'USD';
	elseif ( $currency == '€' )
		$currency = 'EUR';

	// get symbol
	if ( $currency != '' )
		$currency_symbol = $currency_symbol[$currency][$key];

	return $currency_symbol;
}

// convert string to float, use set locale settings
function currencystr_to_float($n, $opt_s) {
	$res = $n;
	// delete the thousands separator
	$res = str_replace($opt_s['thousands_separator'], '', $res);
	// replace the decimal separator with dots
	$res = str_replace($opt_s['decimal_point'], '.', $res);
	// make float
	$res = floatval($res);
	
	return $res;
}

// convert float to string, properly formatted with locale settings
function float_to_currencystr ($n, $add_currency_symbol = true, $opt_s) {

	// set defaults
	$opt_s['decimal_places'] == '' ? $decimal_places=2 : $decimal_places=$opt_s['decimal_places'];
	$opt_s['decimal_point'] == '' ? $decimal_point='.' : $decimal_point=$opt_s['decimal_point'];
	$opt_s['thousands_separator'] == '' ? $thousands_separator=',' : $thousands_separator=$opt_s['thousands_separator'];
	$opt_s['currencysymbol_position'] == '' ? $currencysymbol_position='before-nospace' : $currencysymbol_position=$opt_s['currencysymbol_position'];

	// get symbol of currency
	$currency_symbol = get_bizz_currency($opt_s['pay_currency']);

	// format number
	$res = number_format( $n, $decimal_places, html_entity_decode ( $decimal_point ), html_entity_decode( $thousands_separator) );

	if ($add_currency_symbol) {
		switch ($currencysymbol_position) {
			case 'before':
				$res = $currency_symbol.' '.$res;
			break;
			case 'before-nospace':
				$res = $currency_symbol.$res;
			break;
			case 'after':
				$res = $res.' '.$currency_symbol;
			break;
			case 'after-nospace':
				$res = $res.$currency_symbol;
			break;
		}
	}

	return $res;
  
}

/* BOOKING: Custom function to transform PHP date format syntax to a JavaScript/DatePicker date format syntax */
/*------------------------------------------------------------------*/
/* Used rules:

PHP -> JavaScript
---
Day of the month:
d --> dd / day of month (two digit)
j --> d / day of month (no leading zero)
D --> D / day name short
l --> DD / day name long

Month:
m --> mm / month of year (two digit)
n --> m / month of year (no leading zero)
M --> M / month name short
F --> MM / month name long

Year:
y --> y / year (two digit)
Y --> yy / year (four digit)

*/
/*------------------------------------------------------------------*/
function dateformatsyntax_php2js ($phpdatesyntax) {

	$rules = array(
		/* day of the month */
		'd' => 'dd',
		'j' => 'd',
		'D' => 'D',
		'l' => 'DD',
		/* month */
		'm' => 'mm',
		'n' => 'm',
		'M' => 'M',
		'F' => 'MM',
		/* year */
		'y' => 'y',
		'Y' => 'yy'
	);

	$result = $phpdatesyntax;
	foreach ($rules as $rule_key => $rule_value) {
		$result = str_replace ($rule_key, $rule_value, $result);
	}
	return $result;
  
}
/*------------------------------------------------------------------*/

/**
 * Format array for the datepicker
 *
 * WordPress stores the locale information in an array with a alphanumeric index, and
 * the datepicker wants a numerical index. This function replaces the index with a number
 */
if( ! function_exists( 'strip_array_indices' ) ) {
	function strip_array_indices( $ArrayToStrip ) {
		foreach( $ArrayToStrip as $objArrayItem) {
			$NewArray[] =  $objArrayItem;
		}
		return( $NewArray );
	}
}

/* Default vehicle sorting */
/*------------------------------------------------------------------*/
add_filter( 'bizz_extras_sort', 'bizzthemes_car_sort' );
add_filter( 'bizz_car_sort', 'bizzthemes_car_sort' );
function bizzthemes_car_sort( $car_options ) {
	global $booking_settings;
	
	// get booking settings
	$opt_s = $booking_settings->get_settings();
	
	// stop here if nothing is selected
	if ( ! isset( $opt_s['vehicle_sort'] ) )
		return bizz_list_sort( $car_options, 'availability' );
	
	// change default sorting
	if ( $opt_s['vehicle_sort'] == 'latest' )
		return bizz_list_sort( $car_options, 'availability' );
		
	if ( $opt_s['vehicle_sort'] == 'price-asc' )
		return bizz_list_sort( $car_options, 'cost_val', 'asc' );
	
	if ( $opt_s['vehicle_sort'] == 'price-desc' )
		return bizz_list_sort( $car_options, 'cost_val', 'desc' );
		
	if ( $opt_s['vehicle_sort'] == 'name-asc' )
		return bizz_list_sort( $car_options, 'name', 'asc' );
		
	if ( $opt_s['vehicle_sort'] == 'name-desc' )
		return bizz_list_sort( $car_options, 'name', 'desc' );
}

/* Check IPN response */
/*------------------------------------------------------------------*/
if ( !class_exists('IpnListener') ) locate_template( 'lib_theme/booking/ipn-listener.php', true );

add_action( 'init', 'bizz_check_ipn_response' );
function bizz_check_ipn_response() {
	
	if (isset($_GET['paypalListener']) && $_GET['paypalListener'] == 'paypal_standard_IPN') :
	
		// variables
		$listener = new IpnListener();
		$listener->use_sandbox = TEST_MODE;
		$listener->use_ssl = true;
		$listener->use_curl = false;

		try {
			$listener->requirePostMethod();
			$verified = $listener->processIpn();
		} catch (Exception $e) {
			error_log($e->getMessage(), 3, 'error_log');
			exit(0);
		}

		if ($verified) {
			// Get transaction details
			$posted = $listener->getPostData();
			// Post ID
			$order_id = $posted['custom'];
			$order_key = $posted['invoice'];
			// Save details
			$r = '';
			foreach ($posted as $key => $value) {
				$r .= str_pad($key, 25)."$value<br/>";
			}
			$transaction_details = get_post_meta($order_id, 'bizzthemes_booking_paypal_details', 1);
			$transaction_details = ( !empty($transaction_details) ) ? ($transaction_details .'<br/><hr/><br/>'. $r) : $r;
			update_post_meta( $order_id, 'bizzthemes_booking_paypal_details', $transaction_details );
			
			// get booking meta
			$booking_custom = get_post_custom( $order_id );
			foreach ( $booking_custom as $key => $value ) {
				$bookopts[$key] = $value[0];
			}

			// Lowercase
			$posted['payment_status'] = strtolower($posted['payment_status']);
			$posted['txn_type'] = strtolower($posted['txn_type']);

			// Sandbox fix
			if ($posted['test_ipn']==1 && $posted['payment_status']=='pending') 
				$posted['payment_status'] = 'completed';

			// We are here so lets check status and do actions
			switch ($posted['payment_status']) :
				
				case 'completed' :
					// Check valid txn_type
					$accepted_types = array('cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money');
					if (!in_array($posted['txn_type'], $accepted_types)) {
						if (DEBUG_MODE) error_log('Invalid type:' . $posted['txn_type'], 3, 'error_log');
							exit;
					}
					// Saved booking
					$deposit_amount = get_post_meta($order_id, 'bizzthemes_car_pay_deposit', 1);
					$total_amount = get_post_meta($order_id, 'bizzthemes_car_pay_total', 1);
							
					// Validate Amount
					if ( round($deposit_amount, 2) != $posted['mc_gross'] ) {
						if ( DEBUG_MODE ) error_log('Payment error: Amounts do not match (gross ' . $posted['mc_gross'] . ', saved ' . round($deposit_amount, 2) . ')', 3, 'error_log');
						exit;
					}
					// Store PP Details					
					if ( !empty( $posted['payer_email'] ) )
						update_post_meta( $order_id, 'bizzthemes_bookings_email', $posted['payer_email'] );
					if ( !empty( $posted['txn_id'] ) )
						update_post_meta( $order_id, 'bizzthemes_car_transaction_id', $posted['txn_id'] );
					if ( !empty( $posted['first_name'] ) )
						update_post_meta( $order_id, 'bizzthemes_bookings_fname', $posted['first_name'] );
					if ( !empty( $posted['last_name'] ) )
						update_post_meta( $order_id, 'bizzthemes_bookings_lname', $posted['last_name'] );
					// Paid
					update_post_meta( $order_id, 'bizzthemes_car_pay_paid', round($posted['mc_gross'], 2) );
					// Completed?
					if ( round($total_amount, 2) == $posted['mc_gross'] )
						update_post_meta( $order_id, 'bizzthemes_bookings_status', 'approved' );
				break;
				case 'denied' :
				case 'expired' :
				case 'failed' :
				case 'voided' :
					// Only handle full refunds, not partial
					update_post_meta( $order_id, 'bizzthemes_bookings_status', 'cancelled' );
				break;
				case "refunded" :
					// Only handle full refunds, not partial
					update_post_meta( $order_id, 'bizzthemes_bookings_status', 'refunded' );
					update_post_meta( $order_id, 'bizzthemes_car_pay_paid', '0' );
					booking_send_notification( 'refunded', $bookopts );
				break;
				case "reversed" :
				case "chargeback" :
					// Mark order as refunded
					update_post_meta( $order_id, 'bizzthemes_bookings_status', 'refunded' );
					update_post_meta( $order_id, 'bizzthemes_car_pay_paid', '0' );
				break;
				default:
					// No action
				break;
				
			endswitch;
			exit;
			// error_log('getPaymentData: '.$r, 3, 'error_log');
			//error_log('getTextReport: '.$listener->getTextReport(), 3, 'error_log');
			// mail('admin@bizzartic.com', 'Verified IPN', $listener->getTextReport());
		}
		else {
			/* zapis v bazo o napačni transakciji */
			// mail('admin@bizzartic.com', 'Invalid IPN', $listener->getTextReport());
			error_log('getTextReport: '.$listener->getTextReport(), 3, 'error_log');
		}
	endif;
}

/* Process Mollie payment */
/*------------------------------------------------------------------*/

add_filter( 'bizz_payment_redirect', 'bizz_redirect_mollie', 10, 5 );
function bizz_redirect_mollie( $return, $bookopts, $form_data, $this_post_id, $opt_s ) {

	if ( isset($form_data['payment_method']) && $form_data['payment_method'] == 'mollie' ) {
	
		// Initialize
		require_once dirname(__FILE__) . "/mollie-api/src/Mollie/API/Autoloader.php";
		$mollie = new Mollie_API_Client;
		$api = ( $opt_s['pay_mollie_api'] != '' ) ? $opt_s['pay_mollie_api'] : 'test_W9vJrPAB2Jv4LGRZkGZgLmkznzDAs2';
		$mollie->setApiKey( $api );
		
		// Order ID
		$order_id = $bookopts['tracking_id'];
				
		/*
		 * Payment parameters:
		 *   amount        Amount in EUROs. This example creates a € 10,- payment.
		 *   description   Description of the payment.
		 *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
		 *   metadata      Custom metadata that is stored with the payment.
		 */
		$payment = $mollie->payments->create( apply_filters( 'bizz_mollie_call', array(
			"amount"       => currencystr_to_float($bookopts['pay_deposit'], $opt_s),
			"description"  => __('Deposit', 'bizzthemes'),
			"webhookUrl"   => trailingslashit( home_url() ) . "?mollie={$order_id}",
			"redirectUrl"  => $opt_s['pay_thankyou'],
			"metadata"     => array(
				"order_id" => $order_id,
				"post_id" => $this_post_id,
			),
		)));
		
		// Send the customer off to complete the payment.
		return $payment->getPaymentUrl();
	
	}

}

/* Check Mollie response */
/*------------------------------------------------------------------*/
add_action( 'init', 'bizz_check_mollie_response' );
function bizz_check_mollie_response() {
	
	if ( isset($_GET['mollie']) && $_GET['mollie'] != '' ) {
	
		// variables
		try
		{
			// Initialize
			require_once dirname(__FILE__) . "/mollie-api/src/Mollie/API/Autoloader.php";
			$mollie = new Mollie_API_Client;
			$api = ( $opt_s['pay_mollie_api'] != '' ) ? $opt_s['pay_mollie_api'] : 'test_W9vJrPAB2Jv4LGRZkGZgLmkznzDAs2';
			$mollie->setApiKey( $api );

			// Retrieve the payment's current state.
			$payment  = $mollie->payments->get($_POST["id"]);
			$order_id = $payment->metadata->order_id;
			$post_id = $payment->metadata->post_id;
			
			// get booking meta
			$booking_custom = get_post_custom( $post_id );
			foreach ( $booking_custom as $key => $value ) {
				$bookopts[$key] = $value[0];
			}

			// Update the order in the database.
			if ($payment->isPaid() == TRUE)
			{
				/*
				 * At this point you'd probably want to start the process of delivering the product to the customer.
				 */
				update_post_meta( $post_id, 'bizzthemes_bookings_status', 'approved' );
				booking_send_notification( 'approved', $bookopts);
			}
			elseif ($payment->isOpen() == FALSE)
			{
				/*
				 * The payment isn't paid and isn't open anymore. We can assume it was aborted.
				 */
				update_post_meta( $post_id, 'bizzthemes_bookings_status', 'cancelled' );
				booking_send_notification( 'cancelled', $bookopts);
			}
		}
		catch ( Mollie_API_Exception $e )
		{
			echo "API call failed: " . htmlspecialchars($e->getMessage());
		}
		
	}
	
}