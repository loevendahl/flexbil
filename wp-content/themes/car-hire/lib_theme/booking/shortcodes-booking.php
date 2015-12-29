<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('init', 'bizz_booking_shortcodes');
function bizz_booking_shortcodes() {
	
	/* Deprecated */
	/*------------------------------------------------------------------*/
	if ( has_filter( 'bizz_location_time_selected' ) ) {
		add_filter( 'bizz_default_pickup_time', 'deprecated_location_time_selected' );
		add_filter( 'bizz_default_return_time', 'deprecated_location_time_selected' );
	}
	function deprecated_location_time_selected( $return = '12:00' ) {
		return apply_filters( 'bizz_location_time_selected', $return );
	}
	
	add_shortcode( 'car_booking', 'bizz_sc_car_booking' );
} 

function bizz_sc_car_booking($atts, $content = null) {      
  	extract(shortcode_atts(array(
		'title' 	=> ''
	), $atts));
	
	ob_start();
?>
			<div id="booktop"><!----></div>
			<div class="bspr top"><!----></div>
				<?php if ( !empty( $title ) ) { echo $title; } ; ?>
				<div class="steps_tabs_container clearfix">
					<ul class="nav nav-tabs steps_tabs">
						<li class="step1_tab tablink active" data-rel="1">
							<a href="#">
								<span class="number">1.</span>
								<span class="text"><?php _e('Date', 'bizzthemes'); ?></span>
							</a>
						</li>
						<li class="step2_tab tablink disabled" data-rel="2">
							<a href="#">
								<span class="number">2.</span>
								<span class="text"><?php _e('Vehicle', 'bizzthemes'); ?></span>
							</a>
						</li>
						<li class="step3_tab tablink disabled" data-rel="3">
							<a href="#">
								<span class="number">3.</span>
								<span class="text"><?php _e('Extras', 'bizzthemes'); ?></span>
							</a>
						</li>
						<li class="step4_tab tablink disabled" data-rel="4">
							<a href="#">
								<span class="number">4.</span>
								<span class="text"><?php _e('Checkout', 'bizzthemes'); ?></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="bookwrap navbar-inner">
					<div class="messages"><!----></div>
<?php
					locate_template( 'lib_theme/booking/step1.php', true );
					locate_template( 'lib_theme/booking/step2.php', true );
					locate_template( 'lib_theme/booking/step3.php', true );
					locate_template( 'lib_theme/booking/step4.php', true );
?>
					<div class="loading_wrapper clearfix"><!----></div>
				</div><!-- /.bookingwrap -->
			<div class="bspr bottom"><!----></div>
<?php    
    $html = ob_get_clean();

	return $html;
}

/*---------------------------------------------------------------------------------*/
/* Detect Ajax */
/*---------------------------------------------------------------------------------*/
if (!function_exists('bizz_is_ajax')) {
	function bizz_is_ajax() {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
		return false;
	}
}

/*---------------------------------------------------------------------------------*/
/* Validate Dates */
/*---------------------------------------------------------------------------------*/

//for logged-in users
add_action('wp_ajax_booking_time_action', 'bizz_booking_process_time');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_time_action', 'bizz_booking_process_time');	
	
function bizz_booking_process_time() {
	global $wpdb;
	
	// Page ID by page name
	$location = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$_POST['location']."' AND post_type = 'bizz_locations'");
	$location = is_numeric( $_POST['location'] ) ? $_POST['location'] : $location;
	
	// Get location hours
	$hours = bizz_location_hours( $location, $_POST['day'] );
	
	/* format the date
	$dature = $_POST['dature'];
	$format = get_option( 'date_format', 'y-m-d');
	$selecteddate = date_create_from_format ($format, $dature);
	$today = new DateTime('now');
	
	$dature = ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) ? DateTime::createFromFormat( 'Y-m-d', $dature ) : new DateTime( $dature );
	$dature = date( $format, strtotime( $_POST['dature'] ) );
	*/
		
	// format the date
	$format = get_option( 'date_format', 'Y-m-d' );
	$selecteddate = date( 'Y-m-d', strtotime( $_POST['y'] . '-' . $_POST['m'] . '-' . $_POST['d'] ) );
	$today = new DateTime( 'now' );
	$today = date_format( $today, 'Y-m-d' );
	
	// var_dump( $selecteddate . '__' . $today );
	
	$pre = apply_filters( 'pre_booking_process_time', null, $location, $hours, $_POST );
	if ( null !== $pre ) {
		print_r( $pre );
		exit();
	}
	  
	// EMPTY
	if ( empty( $location ) || empty( $_POST['location'] ) ) {
	
		print_r('EMPTY');

	}
	// CLOSED
	elseif ( $hours['closed'] ) {
	
		print_r('CLOSED');
		
	}
	// PAST
	elseif ( strtotime($selecteddate) < strtotime($today) ) {
	
		print_r('PAST');
	
	}
	// OPENED
	else {
	
		$times = bizz_create_time_range( $hours['start'], $hours['end'], apply_filters( 'bizz_location_time_interval', '30 mins' ) );
		$return = '';
		foreach ($times as $key => $time) {
			$return .= '<option value="' . date('H:i', $time) . '" ' . selected( date('H:i', $time), apply_filters( 'bizz_location_time_selected', '' ) ) . '>' . date(get_option('time_format', 'H:i'), $time) . '</option>';
		}

		print_r($return);
		
	}

	exit();
	
}

/*---------------------------------------------------------------------------------*/
/* Validate Form */
/*---------------------------------------------------------------------------------*/

//for logged-in users
add_action('wp_ajax_booking_form_action', 'bizz_booking_process_form');

//for none logged-in users
add_action('wp_ajax_nopriv_booking_form_action', 'bizz_booking_process_form');	
	
function bizz_booking_process_form() {
	global $wpdb, $booking_settings;
	
	// get booking settings
	$opt_s  = $booking_settings->get_settings();
	
	// parse data
	$data = $_POST['data'];
	parse_str( $data, $output );
	
	// field name
	$name['location_pickup'] = __('Pickup location', 'bizzthemes');
	$name['date_pickup'] = __('Pickup date', 'bizzthemes');
	$name['time_pickup'] = __('Pickup time', 'bizzthemes');
	$name['location_return'] = __('Return location', 'bizzthemes');
	$name['date_return'] = __('Return date', 'bizzthemes');
	$name['time_return'] = __('Return time', 'bizzthemes');
	$name['coupon_code'] = __('Coupon code', 'bizzthemes');
	
	// error string
	$error = '';
	
	// local timezone
	$timezone_string = ( get_option('timezone_string') != '' ) ? get_option('timezone_string') : 'US/Eastern';
	date_default_timezone_set($timezone_string);
		
	// EMPTY
	foreach ($output as $key => $value) {
	
		// skip spam
		if ( $key == 'is_spam' )
			continue;
			
		// skip return location
		if ( $key == 'location_return' && empty($value) && isset( $name['location_pickup'] )  )
			continue;
			
		// skip coupon code
		if ( $key == 'coupon_code' )
			continue;
			
		// emtpy?
		if ( isset( $name[$key] ) && empty($value) )
			$error .= $name[$key] . __(' field is empty.', 'bizzthemes') . '<br />';
			
	}
	
	// stop here if error occurs
	if ( !empty($error) ) {
		echo $error;
		exit();
	}
	
	$pickup_location = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$output['pickup_l']."' AND post_type = 'bizz_locations'");
	$pickup_location = is_numeric( $output['pickup_l'] ) ? $output['pickup_l'] : $pickup_location;
	$return_location = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$output['return_l']."' AND post_type = 'bizz_locations'");
	$return_location = is_numeric( $output['return_l'] ) ? $output['return_l'] : $return_location;
	
	$pickup_hours = bizz_location_hours( $pickup_location, $output['pickup_dn'] );
	$return_hours = bizz_location_hours( $return_location, $output['return_dn'] );
	
	$pickup = date( 'Y-m-d', strtotime( $output['pickup_y'] . '-' . $output['pickup_m'] . '-' . $output['pickup_d'] ) );
	$return = date( 'Y-m-d', strtotime( $output['return_y'] . '-' . $output['return_m'] . '-' . $output['return_d'] ) );
	
	$output['date_pickup'] = $pickup;
	$output['date_return'] = $return;
	
	$pickup_closed = bizz_location_closed( $pickup_location, $output['date_pickup'] );
	$return_closed = bizz_location_closed( $return_location, $output['date_return'] );
	
	// strtotime
	$pickup_dtime = strtotime( $output['date_pickup'] . $output['time_pickup'] );
	$return_dtime = strtotime( $output['date_return'] . $output['time_return'] );
	
	// Count Range
	$output['range'] = bizz_count_slots( $pickup_dtime, $return_dtime, $opt_s['pay_pricerange'] == 'perhour' ? true : false, $opt_s );
				
	// PAST TIME?
	if ( date('Y-m-d') == $output['date_pickup'] && date_i18n('H:i') > $output['time_pickup'] )
		$error .= sprintf(__('Today, you cannot book before %s.', 'bizzthemes'), date_i18n('H:i')) . '<br />';
	
	// CORRECT DATE?
	if ( ! empty($output['date_pickup']) && !empty($output['date_return']) && $pickup_dtime > $return_dtime )
		$error .= __('Your return date cannot be before the pickup date.', 'bizzthemes') . '<br />';
		
	// CLOSED DAY?
	if ( $pickup_hours['closed'] )		
		$error .= sprintf(__('We are closed on %s, pick another pickup date.', 'bizzthemes'), $output['date_pickup']) . '<br />';
	if ( $return_hours['closed'] )		
		$error .= sprintf(__('We are closed on %s, pick another return date.', 'bizzthemes'), $output['date_return']) . '<br />';
	
	// CLOSED DATES?
	if ( $pickup_closed['closed'] )		
		$error .= sprintf(__('We are closed on %s, pick another pickup date.', 'bizzthemes'), $output['date_pickup']) . '<br />';
	if ( $return_closed['closed'] )		
		$error .= sprintf(__('We are closed on %s, pick another return date.', 'bizzthemes'), $output['date_return']) . '<br />';
		
	// CLOSED HOUR?
	if ( $output['time_pickup'] < $pickup_hours['start'] || $output['time_pickup'] > $pickup_hours['end'] )
		$error .= sprintf(__('We are closed at %s, pick another pickup time.', 'bizzthemes'), $output['time_pickup']) . '<br />';
	if ( $output['time_return'] < $return_hours['start'] || $output['time_return'] > $return_hours['end'] )		
		$error .= sprintf(__('We are closed at %s, pick another return time.', 'bizzthemes'), $output['time_return']) . '<br />';
		
	// CORRECT COUPON?
	if ( ! empty( $output['coupon_code'] ) && isset( $output['coupon_checkbox'] ) ) {
		$validate_error = bizz_coupon_validate( $output['coupon_code'], $output['date_return'] );
		if ( $validate_error ) {
			$error .= $validate_error;
		}
	}
	
	do_action( 'step1_processing_form', $output, $pickup_location, $return_location, $opt_s );
		
	// ERROR?
	echo ( empty($error) ) ? 'SUCCESS' : $error;
	
	exit();
	
}

/** 
 * validate coupon code  
 *  
 * @access public 
 * @return void
 */ 
function bizz_coupon_validate( $code='', $return='', $error='' ) { 
	
	// Get coupon post
	$coupon = get_page_by_title( $code, OBJECT, 'bizz_coupons' );
	
	// coupon doesn't exist
	if ( ! isset( $coupon->ID ) ) {
		return __('Coupon does not exist!', 'bizzthemes') . '<br />';
	}
	
	// coupon expired
	$coupon_expiry = get_post_meta( $coupon->ID, 'bizzthemes_coupon_expiry', TRUE );
	if ( $coupon_expiry && strtotime( $coupon_expiry ) <= strtotime( $return ) ) {
		return __('Coupon has expired!', 'bizzthemes') . '<br />';
	}
	
	// coupon limit exceeded
	$coupon_limit = get_post_meta( $coupon->ID, 'bizzthemes_coupon_limit', TRUE );
	$booking_posts = bizz_get_booking_posts();
	$booking["coupon"] = 0;
	if ( (array) $booking_posts ) {
		foreach ( $booking_posts as $post_id ) {
			$custom = get_post_custom( $post_id );
			$booking["coupon"] = ( isset($custom["bizzthemes_bookings_coupon"][0]) ) ? $booking["coupon"] += 1 : $booking["coupon"];
		}
	}
	if ( $coupon_limit && $coupon_limit <= $booking["coupon"] ) {
		return __('Coupon limit exceeded!', 'bizzthemes') . '<br />';
	}
	
    return $error; 
}

/** 
 * Check for location hours by location ID
 *
 * @access public 
 * @return void
 */ 
function bizz_location_hours( $location, $day ) {

	// Defaults
	$hours['start'] = '09:00';
	$hours['end'] = '22:00';
	$hours['closed'] = false;
		
    if ( $day == '1' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_monday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_monday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_monday_closed', true);
		
	} elseif ( $day == '2' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_tuesday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_tuesday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_tuesday_closed', true);
		
	} elseif ( $day == '3' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_wednesday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_wednesday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_wednesday_closed', true);
		
	} elseif ( $day == '4' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_thursday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_thursday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_thursday_closed', true);
		
	} elseif ( $day == '5' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_friday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_friday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_friday_closed', true);
		
	} elseif ( $day == '6' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_saturday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_saturday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_saturday_closed', true);
		
	} elseif ( $day == '0' ) {
		$hours['start'] = get_post_meta($location, 'bizzthemes_hours_sunday_open', true);
		$hours['end'] = get_post_meta($location, 'bizzthemes_hours_sunday_close', true);
		$hours['closed'] = get_post_meta($location, 'bizzthemes_hours_sunday_closed', true);
	}
	
    return $hours; 
}

/** 
 * Check for location closed dates by location ID
 *
 * @access public 
 * @return void
 */ 
function bizz_location_closed( $location, $day ) {
	
	$repeatable_offdates = get_post_meta( $location, 'bizzthemes_offdates_closed', true );

	// Defaults
	$dates['closed'] = false;
	
	if ( $repeatable_offdates ) {
		foreach ( $repeatable_offdates as $offdate ) {
			
			if ( ( $day >= $offdate['start'] ) && ( $day <= $offdate['end'] ) ) { #date
				$dates['closed'] = true;
			}
			elseif ( ( $day <= $offdate['start'] ) && ( $day >= $offdate['end'] ) ) { #between
				$dates['closed'] = true;
			}
			
		}
	}
	
    return $dates; 
}

/** 
 * create_time_range  
 *  
 * @param mixed $start start time, e.g., 9:30am or 9:30 
 * @param mixed $end   end time, e.g., 5:30pm or 17:30 
 * @param string $by   1 hour, 1 mins, 1 secs, etc. 
 * @access public 
 * @return void 
 */ 
function bizz_create_time_range($start, $end, $by='30 mins') { 

    $start_time = strtotime($start); 
    $end_time   = strtotime($end); 

    $current    = time(); 
    $add_time   = strtotime('+'.$by, $current); 
    $diff       = $add_time-$current; 

    $times = array(); 
    while ($start_time < $end_time) { 
        $times[] = $start_time; 
        $start_time += $diff; 
    } 
    $times[] = $start_time;
	
    return $times; 
}