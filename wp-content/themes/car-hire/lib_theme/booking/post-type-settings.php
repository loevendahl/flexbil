<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Booking Settings Class
 *
 * All settings need for booking script to work properly
 *
 */
class Booking_Settings extends Bizz_Settings_API {
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct () {
		$this->name 		= __( 'Booking Settings', 'bizzthemes' );
		$this->menu_label	= __( 'Settings', 'bizzthemes' );
		$this->token 		= 'booking_options';
		$this->page_slug	= 'booking-settings';
		$this->has_tabs 	= true;
	}
	
	/**
	 * register_settings_screen function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings_screen () {
		$hook = add_submenu_page( 'edit.php?post_type=bizz_bookings', $this->name, $this->menu_label, 'manage_options', $this->page_slug, array( &$this, 'settings_screen' ) );
		$this->hook = $hook;

		if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->page_slug ) ) {
			add_action( 'admin_notices', array( &$this, 'settings_errors' ) );
			add_action( 'admin_print_scripts', array( &$this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * init_sections function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_sections () {
		$sections = array();
		
		$sections['payment-settings'] = array(
					'name' 			=> __( 'Payment', 'bizzthemes' )
				);
		$sections['locale-settings'] = array(
					'name' 			=> __( 'Locale', 'bizzthemes' )
				);
		$sections['vehicle-settings'] = array(
					'name' 			=> __( 'Vehicle Selection', 'bizzthemes' )
				);
		$sections['form-settings'] = array(
					'name' 			=> __( 'Booking Form', 'bizzthemes' )
				);
		$sections['admin-settings'] = array(
					'name' 			=> __( 'Administrator', 'bizzthemes' )
				);
		$sections['notification-settings'] = array(
					'name' 			=> __( 'Notifications', 'bizzthemes' )
				);
		$sections['terms-settings'] = array(
					'name' 			=> __( 'Terms and Conditions', 'bizzthemes' )
				);
		$sections['integrations'] = array(
					'name' 			=> __( 'Integrations', 'bizzthemes' )
				);
		
		$this->sections = $sections;
		
	} // End init_sections()
	
	/**
	 * init_fields function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses  WooSlider_Utils::get_slider_types()
	 * @return void
	 */
	public function init_fields () {
		global $pagenow;
		
	    $fields = array();

		// Payment settings
    	$fields['pay_tax'] = array(
								'name' 			=> __( 'Tax amount', 'bizzthemes' ), 
								'description' 	=> "%",
								'type' 			=> 'text', 
								'default'		=> 0, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_deposit_select'] = array(
								'name' 			=> __( 'Deposit amount', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'percentage', 
								'section' 		=> 'payment-settings',
								'options' => array( 
									'percentage' 		=> __( 'Percentage of total (%)', 'bizzthemes' ),
									'fixed_vehicle'		=> __( 'Fixed amount per individual vehicle', 'bizzthemes' ),
									'fixed' 			=> __( 'Fixed amount per vehicle type', 'bizzthemes' ),
								)
								);
		$fields['pay_deposit'] = array(
								'name' 			=> "",
								'description' 	=> __( 'default', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 20, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_deposit_max'] = array(
								'name' 			=> "",
								'description' 	=> __( 'maximum', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '', 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_thankyou'] = array(
								'name' 			=> __( 'Thank you page URL', 'bizzthemes' ), 
								'type' 			=> 'text', 
								'default'		=> get_bloginfo('wpurl'), 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_paypal'] = array(
								'name' 			=> __( 'Primary PayPal email address', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '', 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_allow'] = array(
								'name' 			=> __( 'Allow PayPal', 'bizzthemes' ),
								'description' 	=> __( 'Accept payments via PayPal', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_credit'] = array(
								'name' 			=> __( 'Allow credit cards', 'bizzthemes' ),
								'description' 	=> __( 'Collect credit card information', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_banktransfer'] = array(
								'name' 			=> __( 'Allow Bank Transfer', 'bizzthemes' ),
								'description' 	=> __( 'Accept Bank Transfers', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_cod'] = array(
								'name' 			=> __( 'Allow Cash on Delivery', 'bizzthemes' ),
								'description' 	=> __( 'Accept Cash on Delivery', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'payment-settings'
								);
		$fields['pay_pricerange'] = array(
								'name' 			=> __( 'Pricing Setup', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'perday',
								'section' 		=> 'payment-settings',
								'options' => array( 
									'perday' => __( 'per day', 'bizzthemes' ), 
									'perhour' => __( 'per hour', 'bizzthemes' ) 
								)
								);

		// Locale settings
    	$fields['pay_currency'] = array(
								'name' 			=> __( 'Currency', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'USD', 
								'section' 		=> 'locale-settings',
								'options' 		=> $this->get_currency_options()
								);
		$fields['decimal_places'] = array(
								'name' 			=> __( 'Number of decimal places to use', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '2', 
								'section' 		=> 'locale-settings'
								);
		$fields['decimal_point'] = array(
								'name' 			=> __( 'Decimal point symbol', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '&#44;', 
								'section' 		=> 'locale-settings'
								);
		$fields['thousands_separator'] = array(
								'name' 			=> __( 'Thousands separator symbol', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '&#46;', 
								'section' 		=> 'locale-settings'
								);
		$fields['currencysymbol_position'] = array(
								'name' 			=> __( 'Currency symbol position', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'before-nospace', 
								'section' 		=> 'locale-settings',
								'options' => array( 
									'before' => __( 'before amount', 'bizzthemes' ), 
									'before-nospace' => __( 'before amount without trailing space', 'bizzthemes' ),
									'after' => __( 'after amount', 'bizzthemes' ), 
									'after-nospace' => __( 'after amount without trailing space', 'bizzthemes' )
								)
								);
								
		// Vehicle selection
		$fields['hide_unavailable'] = array(
								'name' 			=> __( 'Hide unavailable', 'bizzthemes' ),
								'description' 	=> __( 'Hide vehicles that are not available for selected time period', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'vehicle-settings'
								);
		$fields['skip_location'] = array(
								'name' 			=> __( 'Location checking' ),
								'description' 	=> __( 'Skip location checking, do not check availability per location', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'vehicle-settings'
								);		
		$fields['skip_time'] = array(
								'name' 			=> __( 'Date/Time checking' ),
								'description' 	=> __( 'Skip date and time checking, do not check availability per time period', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'vehicle-settings'
								);
		
		$fields['vehicle_sort'] = array(
								'name' 			=> __( 'Default vehicle sorting', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'latest', 
								'section' 		=> 'vehicle-settings',
								'options' => array( 
									'latest' 		=> __( 'Latest first', 'bizzthemes' ), 
									'price-asc' 	=> __( 'Price: low to high', 'bizzthemes' ),
									'price-desc' 	=> __( 'Price: high to low', 'bizzthemes' ), 
									'name-asc'	 	=> __( 'Name: ascending', 'bizzthemes' ),
									'name-desc' 	=> __( 'Name: descending', 'bizzthemes' )
								)
								);
								
		// Form settings
		$fields['f_customer_title'] = array(
								'name' 			=> __( 'Customer Title', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_first_name'] = array(
								'name' 			=> __( 'First Name', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_last_name'] = array(
								'name' 			=> __( 'Last Name', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_email'] = array(
								'name' 			=> __( 'Email', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_phone'] = array(
								'name' 			=> __( 'Phone', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_contact_option'] = array(
								'name' 			=> __( 'Contact Option', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_country'] = array(
								'name' 			=> __( 'Country', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_state_province'] = array(
								'name' 			=> __( 'State/Province', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_city'] = array(
								'name' 			=> __( 'City', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_postcode_zip'] = array(
								'name' 			=> __( 'Postcode/ZIP', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_address'] = array(
								'name' 			=> __( 'Address', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes-req', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_flight_number'] = array(
								'name' 			=> __( 'Flight Number', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_age'] = array(
								'name' 			=> __( 'Age of Driver', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_birth'] = array(
								'name' 			=> __( 'Date of Birth', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_license'] = array(
								'name' 			=> __( 'Driving Licence Number', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_country'] = array(
								'name' 			=> __( 'Country / State of issue', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_issue'] = array(
								'name' 			=> __( 'Issue Date', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_expiry'] = array(
								'name' 			=> __( 'Expiry Date', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_driver_accidents'] = array(
								'name' 			=> __( 'Accidents, claims or motoring convictions over the past 3 years?', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_number_passengers'] = array(
								'name' 			=> __( 'Number of Passengers', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'no', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		$fields['f_comments_question'] = array(
								'name' 			=> __( 'Comments/Questions', 'bizzthemes' ),
								'type' 			=> 'select', 
								'default'		=> 'yes', 
								'section' 		=> 'form-settings',
								'options' => array( 
									'yes' 				=> __( 'Yes', 'bizzthemes' ), 
									'yes-req' 			=> __( 'Yes, required', 'bizzthemes' ),
									'no' 				=> __( 'No', 'bizzthemes' )
								)
								);
		
		// Admin settings
		$fields['admin_email'] = array(
								'name' 			=> __( 'Set Admin email', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> get_option('admin_email'), 
								'section' 		=> 'admin-settings'
								);
		$fields['admin_name'] = array(
								'name' 			=> __( 'Set Admin name', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> $this->get_admin_name(), 
								'section' 		=> 'admin-settings'
								);
		$fields['admin_phone'] = array(
								'name' 			=> __( 'Set Admin phone', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> '', 
								'section' 		=> 'admin-settings'
								);
		$fields['admin_notifications'] = array(
								'name' 			=> __( 'Admin notifications', 'bizzthemes' ),
								'description' 	=> __( 'Disable admin email notifications', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'admin-settings'
								);
								
		// Notification settings
		$fields['customer_email_subject'] = array(
								'name' 			=> __( 'Confirmation Email', 'bizzthemes' ),
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Sent', 
								'section' 		=> 'notification-settings'
								);
		$fields['customer_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Sent to customer when the booking has been submited.', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been submitted and is pendind approval.<br/>If your car is not available, we will get back to you, otherwise, expect a confirmation email with all the details.</p><p>Details: [BOOK_DETAILS]</p><p>In case you want to cancel it, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['approved_email_subject'] = array(
								'name' 			=> __( 'Approved Email', 'bizzthemes' ),
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Approved', 
								'section' 		=> 'notification-settings'
								);
		$fields['approved_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Sent to customer when the booking status is set to "Approved".', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been confirmed.</p><p>Details: [BOOK_DETAILS]</p><p>In case you want to cancel it, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['cancelled_email_subject'] = array(
								'name' 			=> __( 'Cancelled Email', 'bizzthemes' ),
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Cancelled', 
								'section' 		=> 'notification-settings'
								);
		$fields['cancelled_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Sent to customer when the booking status is set to "Cancelled".', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been cancelled, however, you can schedule your booking again at any other time.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['refunded_email_subject'] = array(
								'name' 			=> __( 'Refunded Email', 'bizzthemes' ),
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Refunded', 
								'section' 		=> 'notification-settings'
								);
		$fields['refunded_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Sent to customer when the booking status is set to "Refunded".', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking has been refunded.</p><p>Details: [BOOK_DETAILS]</p><p>In case you need more information, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['customer_notifications_prior'] = array(
								'name' 			=> __( 'Reminder Email', 'bizzthemes' ),
								'description' 	=> __( 'Notify prior to booking start schedule', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'notification-settings'
								);
								
		$fields['prior_email_subject'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Reminder', 
								'section' 		=> 'notification-settings'
								);
		$fields['prior_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Notify customer before the booking starts.', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking will start on [PICKUP_DATE], at [PICKUP_HOUR]</p><p>In case you need more information, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['customer_notifications_after'] = array(
								'name' 			=> __( 'Completed Email', 'bizzthemes' ),
								'description' 	=> __( 'Notify after the booking completion schedule', 'bizzthemes' ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'notification-settings'
								);
		$fields['after_email_subject'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Subject', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> 'Booking Successfully Completed', 
								'section' 		=> 'notification-settings'
								);
		$fields['after_email_body'] = array(
								'name' 			=> '',
								'description' 	=> __( 'Notify customer after the booking has ended.', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> "Hi "."[CUSTOMER_FULLNAME]"."<br/><p>Your booking was successfully completed.</p><p>In case you need more information, write us an email at [ADMIN_EMAIL] and reference Tracking ID: [TRACKING_ID] for your booking.</p><p>Thank you,</p><p>"."[ADMIN_NAME]"."</p>", 
								'section' 		=> 'notification-settings'
								);
		$fields['email_shortcodes'] = array(
								'name' 			=> '',
								'description'	=> __( 'Email shortcodes', 'bizzthemes' ) . ': ' . '[ADMIN_NAME], [ADMIN_EMAIL], [TRACKING_ID], [CURRENCY],
													[PAY_TOTAL], [PAY_TOTAL_NC], [PAY_DEPOSIT], [PAY_DEPOSIT_NC], [PAY_CAR], [PAY_CAR_NC],
													[PAY_EXTRAS], [PAY_EXTRAS_NC], [PAY_TAX], [PAY_TAX_NC], [CAR], [CAR_COUNT],
													[PICKUP_LOCATION], [RETURN_LOCATION], [PICKUP_DATE], [PICKUP_HOUR], [RETURN_DATE],
													[RETURN_HOUR], [DURATION], [FLIGHT], [EXTRAS], [CUSTOMER_TITLE], [CUSTOMER_FNAME],
													[CUSTOMER_LNAME], [CUSTOMER_FULLNAME], [CUSTOMER_EMAIL],
													[CUSTOMER_PHONE], [CUSTOMER_CONTACT_OPTION],
													[CUSTOMER_COUNTRY], [CUSTOMER_STATE], [CUSTOMER_CITY], [CUSTOMER_ZIP],
													[CUSTOMER_ADDRESS], [CUSTOMER_DRIVER_AGE], [CUSTOMER_DRIVER_BIRTH],
													[CUSTOMER_DRIVER_LICENSE], [CUSTOMER_DRIVER_COUNTRY], [CUSTOMER_DRIVER_ISSUE],
													[CUSTOMER_DRIVER_EXPIRY], [CUSTOMER_DRIVER_ACCIDENTS], [CUSTOMER_NUMBER_PASSENGERS],
													[CUSTOMER_COMMENTS], [BOOK_DETAILS]',
								'type' 			=> 'info', 
								'section' 		=> 'notification-settings'
								);
								
		// Terms and Conditions
		$fields['terms_conditions'] = array(
								'name' 			=> __( 'Text', 'bizzthemes' ),
								'description' 	=> __( 'Customer needs to approve them before booking is successfully completed.', 'bizzthemes' ),
								'type' 			=> 'textarea', 
								'default'		=> '', 
								'section' 		=> 'terms-settings'
								);
		
		// Integrations
		$fields['pay_mollie'] = array(
								'name' 			=> __( 'Allow Mollie Payments', 'bizzthemes' ),
								'description' 	=>sprintf(__( 'Accept <a href="%s">Mollie</a> Payment Modules (NL/BE/DE)', 'bizzthemes' ) , esc_url( 'https://www.mollie.com' ) ),
								'type' 			=> 'checkbox', 
								'default'		=> false, 
								'section' 		=> 'integrations'
								);
		$fields['pay_mollie_api'] = array(
								'name' 			=> __('Mollie Payment API key', 'bizzthemes'),
								'description' 	=> sprintf(__( '<a href="%s" target="_blank">How to find it?</a>', 'bizzthemes'), esc_url( 'https://www.jdideal.nl/software/installatie/135-waar-vind-ik-mijn-mollie-api-key' ) ),
								'type' 			=> 'text', 
								'default'		=> '', 
								'section' 		=> 'integrations'
								);
		$fields['pay_mollie_label'] = array(
								'name' 			=> __('Mollie Payment Label', 'bizzthemes'),
								'description' 	=> __( 'Add a label for your payment Modules', 'bizzthemes' ),
								'type' 			=> 'text', 
								'default'		=> __( 'iDEAL', 'bizzthemes' ),
								'section' 		=> 'integrations'
								);
		
		$this->fields = $fields;
	
	}
	
	/**
	 * Get currency options.
	 */
	private function get_currency_options () {
		$currencies = get_bizz_currency();
		$options = array();

		foreach ( $currencies as $k => $v ) {
			$options[$k] = $v['name'];
		}

		return $options;
	}
	
	/**
	 * Get admin email.
	 */
	private function get_admin_name () {
		$admin_user = get_user_by('email', get_option('admin_email'));

		return $admin_user->display_name;
	}

}

/* Init booking settings */
/*------------------------------------------------------------------*/
add_action( 'init', 'bizz_register_booking_settings', 5 );
function bizz_register_booking_settings() {
	global $booking_settings;
	
	$booking_settings = new Booking_Settings();
	$booking_settings->setup_settings();
}
