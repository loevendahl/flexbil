<?php

/*
Plugin Name: WooCommerce QuickPay
Plugin URI: http://wordpress.org/plugins/woocommerce-quickpay/
Description: Integrates your QuickPay payment getway into your WooCommerce installation.
Version: 4.3.5
Author: Perfect Solution
Text Domain: woo-quickpay
Author URI: http://perfect-solution.dk
*/

define( 'WCQP_VERSION', '4.3.5' );

add_action('plugins_loaded', 'init_quickpay_gateway', 0);

function init_quickpay_gateway() {

	if ( ! class_exists( 'WC_Payment_Gateway' )) { return; }
     
	// Import helper classes
    require_once( 'classes/api/woocommerce-quickpay-api.php' );
    require_once( 'classes/api/woocommerce-quickpay-api-transaction.php' );
    require_once( 'classes/api/woocommerce-quickpay-api-payment.php' );
    require_once( 'classes/api/woocommerce-quickpay-api-subscription.php' );
    require_once( 'classes/woocommerce-quickpay-exceptions.php' );
    require_once( 'classes/woocommerce-quickpay-log.php' );
	require_once( 'classes/woocommerce-quickpay-helper.php' );
	require_once( 'classes/woocommerce-quickpay-settings.php' );
	require_once( 'classes/woocommerce-quickpay-order.php' );
	
	// Main class
	class WC_QuickPay extends WC_Payment_Gateway
	{

	    /**
	    * $_instance
	    * @var mixed
	    * @access public
	    * @static
	    */
		public static $_instance = NULL;	
					
        public $log;
	    
		/**
	    * get_instance
	    * 
	    * Returns a new instance of self, if it does not already exist.
	    * 
	    * @access public
	    * @static
	    * @return object WC_QuickPay
	    */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
        


		/**
		* __construct function.
		*
		* The class construct
		*
		* @access public
		* @return void
		*/
		public function __construct() 
		{
		    $this->id			= 'quickpay';
		    $this->method_title = 'QuickPay';
		    $this->icon 		= '';
		    $this->has_fields 	= false;	

		    $this->supports = array( 
		    	'subscriptions', 
		    	'products', 
		    	'subscription_cancellation', 
		    	'subscription_reactivation', 
		    	'subscription_suspension' , 
		    	'subscription_amount_changes', 
		    	'subscription_date_changes',
		    	'subscription_payment_method_change',
                'refunds'
		    );
            
            $this->log = new WC_QuickPay_Log();

			// Load the form fields and settings
			$this->init_form_fields();
			$this->init_settings();

			// Get gateway variables
			$this->title = $this->s('title');
			$this->description = $this->s( 'description' );
			$this->instructions = $this->s( 'instructions' );
            $this->order_button_text = $this->s( 'checkout_button_text' );
		}
         
      
		/**
		* filter_load_instances function.
		*
		* Loads in extra instances of as separate gateways
		*
		* @access public static
		* @return void
		*/        
        public static function filter_load_instances( $methods ) {
            require_once( 'classes/instances/instance.php' );
            require_once( 'classes/instances/mobilepay.php' );
            require_once( 'classes/instances/paii.php' );
            require_once( 'classes/instances/viabill.php' );
            
            $methods[] = 'WC_QuickPay_MobilePay';
            $methods[] = 'WC_QuickPay_Paii';
            $methods[] = 'WC_QuickPay_ViaBill';
            
            return $methods;
        }


		/**
		* hooks_and_filters function.
		*
		* Applies plugin hooks and filters
		*
		* @access public
		* @return string
		*/
		public function hooks_and_filters() 
		{
		    add_action( 'init', 'WC_QuickPay_Helper::load_i18n' );
		    add_action( 'scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 3 );
		    add_action( 'woocommerce_api_wc_' . $this->id, array( $this, 'callback_handler' ) );    
		    add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
            add_action( 'woocommerce_order_status_completed', array( $this, 'woocommerce_order_status_completed' ) );   
                     
		    add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 2 );
		    add_action( 'cancelled_subscription_' . $this->id, array( $this, 'subscription_cancellation') )	;
            add_action( 'in_plugin_update_message-woocommerce-quickpay/woocommerce-quickpay.php', array( __CLASS__, 'in_plugin_update_message' ) );

			if( is_admin() ) {
			    add_action( 'admin_menu', 'WC_QuickPay_Helper::enqueue_stylesheet' );
			    add_action( 'admin_menu', 'WC_QuickPay_Helper::enqueue_javascript_backend' );
		    	add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		    	add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );	    
				add_action( 'wp_ajax_quickpay_manual_transaction_actions', array( $this, 'ajax_quickpay_manual_transaction_actions' ) );
				add_action( 'wp_ajax_quickpay_get_transaction_information', array( $this, 'ajax_quickpay_get_transaction_information' ) );
                add_action( 'in_plugin_update_message-woocommerce-quickpay/woocommerce-quickpay.php', array( __CLASS__, 'in_plugin_update_message' ) );

		    	add_filter( 'manage_shop_order_posts_custom_column', array( $this, 'apply_custom_order_data' ) );
			}	
          
            add_filter( 'woocommerce_gateway_icon', array( $this, 'apply_gateway_icons' ), 2, 3 );
		}
        

		/**
		* s function.
		*
		* Returns a setting if set. Introduced to prevent undefined key when introducing new settings.
		*
		* @access public
		* @return string
		*/
		public function s( $key ) 
		{
			if( isset( $this->settings[$key] ) ) {
				return $this->settings[$key];
			}

			return '';
		}


		/**
		* add_action_links function.
		*
		* Adds action links inside the plugin overview
		*
		* @access public static
		* @return array
		*/
		public static function add_action_links( $links ) 
		{
			$links = array_merge( array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_quickpay' ) . '">' . __( 'Settings', 'woo-quickpay' ) . '</a>',
			), $links );
		
			return $links;
		}


		/**
		* ajax_quickpay_manual_transaction_actions function.
		*
		* Ajax method taking manual transaction requests from wp-admin.
		*
		* @access public
		* @return void
		*/
		public function ajax_quickpay_manual_transaction_actions() 
		{
			if( isset( $_REQUEST['quickpay_action'] ) AND isset( $_REQUEST['post'] ) ) 
			{
				$param_action 	= $_REQUEST['quickpay_action'];
				$param_post		= $_REQUEST['post'];

				$order = new WC_QuickPay_Order( intval( $param_post ) );
				
				try 
				{	
					$transaction_id = $order->get_transaction_id();

                    // Subscription
                    if( $order->contains_subscription() ) 
                    {
                        $payment = new WC_QuickPay_API_Subscription();
                        $payment->get( $transaction_id );
                    }
                    // Payment
                    else
                    {
                        $payment = new WC_QuickPay_API_Payment();
                        $payment->get( $transaction_id );    
                    }

					$payment->get( $transaction_id );

					// Based on the current transaction state, we check if 
					// the requested action is allowed
					if( $payment->is_action_allowed( $param_action ) ) 
					{
						// Check if the action method is available in the payment class
						if( method_exists( $payment, $param_action ) ) {
							// Call the action method and parse the transaction id and order object
							call_user_func_array( array( $payment, $param_action ), array( $transaction_id, $order ) );
						} 
						else 
						{
							throw new QuickPay_API_Exception( sprintf( "Unsupported action: %s.", $param_action ) );
						}
					}
					// The action was not allowed. Throw an exception
					else {
						throw new QuickPay_API_Exception( sprintf( 
							"Action: \"%s\", is not allowed for order #%d, with type state \"%s\"", 
							$param_action, 
							$order->get_clean_order_number(), 
							$payment->get_current_type() 
						) );
					}
				}
				catch( QuickPay_Exception $e ) 
				{
					$e->write_to_logs();
				}
				catch( QuickPay_API_Exception $e ) 
				{
					$e->write_to_logs();
				}

			}
		}
        
        
		/**
		* ajax_quickpay_get_transaction_information function.
		*
		* Ajax method retrieving status information about a transaction
		*
		* @access public
		* @return json
		*/        
        public function ajax_quickpay_get_transaction_information() {
            try 
            {
                if( isset($_REQUEST['quickpay-transaction-id']) && isset($_REQUEST['quickpay-post-id']) ) {
                    $post_id = $_REQUEST['quickpay-post-id'];
                    $order = new WC_QuickPay_Order( $post_id );
                    $transaction_id = $_REQUEST['quickpay-transaction-id'];
                    
                    $data_transaction_id = $transaction_id;
                    $data_status = '';
                    $data_test = '';
                    $data_order = $order->get_transaction_order_id();
                    
                    // Subscription
                    if( $order->contains_subscription() ) 
                    {
                        $transaction = new WC_QuickPay_API_Subscription();
                        $transaction->get( $transaction_id );
                        $status = $transaction->get_current_type() . ' (' . __( 'subscription', 'woo-quickpay' ) . ')';
                    }
                    // Renewal failure
                    else if( $order->subscription_is_renewal_failure() )
                    {
                        $data_transaction_id .= ' <small>( ' . __( 'initial order transaction ID', 'woo-quickpay') . ')</small>';
                        $status          = __( 'Failed renewal', 'woo-quickpay' );
                    }
                    // Payment
                    else
                    {
                        $transaction = new WC_QuickPay_API_Payment();
                        $transaction->get( $transaction_id );
                        $status = $transaction->get_current_type();                           
                    }

                    if( isset( $transaction ) AND is_object( $transaction ) AND $transaction->is_test() ) 
                    {
                        $data_test = __( 'Test transaction', 'woo-quickpay' );
                    }		
                    
                    $response = array(
                        'id' => array(
                            'value' =>  sprintf( __('Transaction ID: %s', 'woo-quickpay'), $data_transaction_id )
                        ),
                        'order' => array(
                            'value' => empty( $data_order ) ? '' : sprintf( __('Transaction Order ID: %s', 'woo-quickpay' ), $data_order ) 
                        ),
                        'status' => array(
                            'value' => sprintf( __('Transaction state: %s', 'woo-quickpay'), $status),
                            'attr' => array(
                                'class' => 'woocommerce-quickpay-' . $status
                            )
                        ),
                        'test' => array(
                            'value' => $data_test,
                            'attr' => array(
                                'style' => empty($data_test) ? '' : 'color:red'   
                            )
                        ),
                    );
                    
                    echo json_encode( $response );
                    exit;
                }
            } 
            catch(QuickPay_API_Exception $e) 
            {
                $e->write_to_logs();
                
                $response = array(
                    'error' => array(
                        'value' => $e->getMessage()   
                    )
                );
                
                echo json_encode($response); 
                exit;
            }   
        }


		/**
		* prepare_extras function.
		*
		* Prepares extra data used to parse into the action router
		*
		* @access public
		* @param $action - the api action
		* @param $request - the POST request object
		* @return array
		*/
		public function prepare_extras( $action, $request ) {
			$extras = array();

			if( $action == 'splitcapture' ) {
				$extras['amount'] = $request['amount'];
				$extras['finalize'] = $request['finalize'];
			}

			return $extras;
		}


		/**
		* woocommerce_order_status_completed function.
		*
		* Captures one or several transactions when order state changes to complete.
		*
		* @access public
		* @return void
		*/
		public function woocommerce_order_status_completed( $post_id ) 
		{
            // Instantiate new order object
            $order = new WC_QuickPay_Order( $post_id );

            // Check the gateway settings. 
            if( WC_QuickPay_Helper::option_is_enabled( $this->s('quickpay_captureoncomplete') ) ) 
            {
                // Capture only non-subscription orders
                if( ! $order->contains_subscription() ) 
                {
                    $transaction_id = $order->get_transaction_id();
                    $payment = new WC_QuickPay_API_Payment();

                    // Check if there is a transaction ID
                    if( $transaction_id ) 
                    {
                        // Retrieve resource data about the transaction
                        $payment->get( $transaction_id );

                        // Check if the transaction can be captured
                        if( $payment->is_action_allowed( 'capture' ) ) 
                        {
                            // Capture the payment
                            $payment->capture( $transaction_id, $order );         
                        }
                    }	
                }
            }	
		}


		/**
		* payment_fields function.
		*
		* Prints out the description of the gateway. Also adds two checkboxes for viaBill/creditcard for customers to choose how to pay.
		*
		* @access public
		* @return void
		*/
		public function payment_fields() 
		{
			if ( $this->description) echo wpautop( wptexturize( $this->description ) );
		}


		/**
		* receipt_page function.
		*
		* Shows the recipt. This is the very last step before opening the payment window.
		*
		* @access public 
		* @return void
		*/	 
		public function receipt_page( $order ) 
		{	
			echo $this->generate_quickpay_form( $order );
		}
	
		public function process_payment( $order_id ) 
		{
			global $woocommerce;
            
            try {
                // Instantiate order object
                $order = new WC_QuickPay_Order( $order_id );
				
                // Instantiate API Payment object
                if( ! $order->contains_subscription() )
                {
                    $api_transaction = new WC_QuickPay_API_Payment();
                }
                else 
                {
                    $api_transaction = new WC_QuickPay_API_Subscription();
                }


                // Create a new object
                $payment = new stdClass();
                // If a payment ID exists, go get it
                $payment->id = $order->get_payment_id();
                // Create a payment link
                $link = new stdClass();
                // If a payment link exists, go get it
                $link->url = $order->get_payment_link();


                // If the order does not already have a payment ID,
                // we will create on an attach it to the order
                // We also check if a payment already exists. If a link exists, we don't
                // need to create a payment.
                if( empty($payment->id) && empty($link->url) ) 
                {
                    $payment = $api_transaction->create($order);
                    $order->set_payment_id( $payment->id );
                }


                // If the order does not already have a payment ID,
                // we will create on an attach it to the order
                if( empty($link->url) ) 
                {
                    $link = $api_transaction->create_link( $payment->id, $order );

                    if( WC_QuickPay_Helper::is_url($link->url) )
                    {
                        $order->set_payment_link( $link->url );
                    }
                }


                // Validate if the url is valid
                if( WC_QuickPay_Helper::is_url( $link->url ) ) 
                {
                    $woocommerce->cart->empty_cart();

                    return array(
                        'result' 	=> 'success',
                        'redirect'	=>  $link->url
                    );
                }
            }
            catch( Exception $e) 
            {
                $e->write_to_logs();   
            }
		}

        /**
         * Process refunds
         * WooCommerce 2.2 or later
         *
         * @param  int $order_id
         * @param  float $amount
         * @param  string $reason
         * @return bool|WP_Error
         */
        public function process_refund( $order_id, $amount = NULL, $reason = '' ) 
        {
            try 
            {
                $order = new WC_QuickPay_Order( $order_id );	

                $transaction_id	= $order->get_transaction_id();
                
                // Check if there is a transaction ID
                if( ! $transaction_id) {
                    throw new QuickPay_Exception( sprintf( __("No transaction ID for order: %s", 'woo-quickpay'), $order_id ) );   
                }
                
                // Create a payment instance and retrieve transaction information
                $payment = new WC_QuickPay_API_Payment();
                $payment->get( $transaction_id );
                
                // Check if the transaction can be refunded
                if( ! $payment->is_action_allowed( 'refund' ) ) {
                    throw new QuickPay_Exception( __( "Transaction state does not allow refunds.", 'woo-quickpay' ) );
                }
                
                // Perform a refund API request
                $payment->refund( $transaction_id, $order,  $amount );
                
                return TRUE;
            } 
            catch ( QuickPay_Exception $e ) 
            {
                $e->write_to_logs();
            }
            catch ( QuickPay_API_Exception $e ) 
            {
                $e->write_to_logs();
            }

            return FALSE;
        }
        
        
		/**
		* scheduled_subscription_payment function.
		*
		* Runs every time a scheduled renewal of a subscription is required
		*
		* @access public 
		* @return void
		*/	
		public function scheduled_subscription_payment( $amount_to_charge, $order, $product_id ) 
		{	
            $order = new WC_QuickPay_Order( $order->id );

            try 
            {   
                // Create subscription instance
                $subscription = new WC_QuickPay_API_Subscription();
                
                // Capture a recurring payment with fixed amount
                $recurring = $subscription->recurring( $order->get_transaction_id(), $order, $amount_to_charge );     

                // Process recurring response
                WC_QuickPay_API_Subscription::process_recurring_response( $recurring, $order );
            }
            catch ( QuickPay_Exception $e ) 
            {
                // Set the payment as failed
                WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
                
                // Write debug information to the logs
                $e->write_to_logs();
            }
            catch ( QuickPay_API_Exception $e ) 
            {
                // Set the payment as failed
                WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
                
                // Write debug information to the logs
                $e->write_to_logs();
            }
		}


		/**
		* subscription_cancellation function.
		*
		* Cancels a transaction when the subscription is cancelled
		*
		* @access public 
		* @param $order - WC_Order object
		* @return void
		*/	
		public function subscription_cancellation( $order ) 
		{
            try 
            {
                $order = new WC_QuickPay_Order( $order );
                $subscription = new WC_QuickPay_API_Subscription();
                $subscription->cancel( $order->get_transaction_id() );
            }
            catch ( QuickPay_Exception $e ) 
            {
                $e->write_to_logs();
            }
            catch ( QuickPay_API_Exception $e ) 
            {
                $e->write_to_logs();
            }
		}


		/**
		* on_order_cancellation function.
		*
		* Is called when a customer cancels the payment process from the QuickPay payment window.
		*
		* @access public 
		* @return void
		*/	
		public function on_order_cancellation( $order_id )
		{
			global $woocommerce;

			$order = new WC_Order( $order_id );

			// Redirect the customer to account page if the current order is failed
			if($order->status == 'failed') 
			{
				$payment_failure_text = printf( __( '<p><strong>Payment failure</strong> A problem with your payment on order <strong>#%i</strong> occured. Please try again to complete your order.</p>', 'woo-quickpay' ), $order->id );
				$woocommerce->add_error( '<p><strong>' . __( 'Payment failure', 'woo-quickpay' ) . '</strong>: '. $payment_failure_text . '</p>' );
				wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
			}	

			$order->add_order_note( __( 'QuickPay Payment', 'woo-quickpay' ) . ': ' . __( 'Cancelled during process', 'woo-quickpay' ) );
			$woocommerce->add_error( '<p><strong>' . __( 'Payment cancelled', 'woo-quickpay' ) . '</strong>: ' . __( 'Due to cancellation of your payment, the order process was not completed. Please fulfill the payment to complete your order.', 'woo-quickpay' ) .'</p>' );
		}


		/**
		* callback_handler function.
		*
		* Is called after a payment has been submitted in the QuickPay payment window.
		*
		* @access public 
		* @return void
		*/	
		public function callback_handler()
		{           
            $request_body = file_get_contents("php://input");
            
            $json = json_decode( $request_body );

       		$payment = new WC_QuickPay_API_Payment( $request_body );

            if( $payment->is_authorized_callback( $request_body ) ) {
						
                // Fetch order number;
				$order_number = WC_QuickPay_Order::get_order_id_from_callback( $json );
                
                // Instantiate order object
                $order = new WC_QuickPay_Order( $order_number );
                
                // Get last transaction in operation history
                $transaction = end( $json->operations );
                
                // Is the transaction accepted?
                if( $json->accepted ) 
                {
                    // Add order transaction fee
                    $order->add_transaction_fee( $transaction->amount );

                    // Perform action depending on the operation status type
                    try 
                    {
                        switch( $transaction->type )
                        {
                            //
                            // Cancel callbacks are currently not supported by the QuickPay API
                            //
                            case 'cancel' :
                                if( WC_QuickPay_Helper::subscription_is_active() ) 
                                {
                                    if( $order->contains_subscription() ) 
                                    {
                                        WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order->id );
                                    }
                                }
                                // Write a note to the order history
                                $order->note( __( 'Payment cancelled.', 'woo-quickpay' ) );
                                break; 

                            case 'capture' : 
                                // Write a note to the order history
                                $order->note( __( 'Payment captured.', 'woo-quickpay' ) );
                                break;

                            case 'refund' :
                                $order->note( sprintf( __( 'Refunded %s %s', 'woo-quickpay' ), WC_QuickPay_Helper::price_normalize( $transaction->amount ), $json->currency ) );
                                break;

                            case 'authorize' :
                                // Set the transaction ID 
                                $order->set_transaction_id( $json->id );
                            
                                // Set the transaction order ID
                                $order->set_transaction_order_id( $json->order_id );
                            
                                // Remove payment link
                                $order->delete_payment_link();
                            
                                // Remove payment ID, now we have the transaction ID
                                $order->delete_payment_id();

                                // Subscription authorization
                                if( isset( $json->type ) AND strtolower($json->type) == 'subscription' )
                                {
                                    // Create subscription instance
                                    $subscription = new WC_QuickPay_API_Subscription( $request_body );

                                    // Write log
                                    $order->note( sprintf( __( 'Subscription authorized. Transaction ID: %s', 'woo-quickpay' ), $json->id ) );   

                                    // If 'capture first payment on subscription' is enabled
                                    if( WC_QuickPay_Helper::option_is_enabled( $this->s( 'quickpay_autodraw_subscription' ) ) )
                                    {
                                        // Check if there is an initial payment on the subscription
                                        $subscription_initial_payment = WC_Subscriptions_Order::get_total_initial_payment( $order );

                                        // Only make an instant payment if there is an initial payment
                                        if( $subscription_initial_payment > 0 ) 
                                        {
                                            // New subscription instance
                                            $subscription = new WC_QuickPay_API_Subscription();
                              				
                              				// Perform API recurring payment request
                                        	$recurring = $subscription->recurring( $json->id, $order, $subscription_initial_payment );
        									
        									// Process the recurring response data
        									WC_QuickPay_API_Subscription::process_recurring_response( $recurring, $order );
                                        }							
                                    }                                
                                }

                                // Regular payment authorization
                                else
                                {
                                    // Write a note to the order history
                                    $order->note( sprintf( __( 'Payment authorized. Transaction ID: %s', 'woo-quickpay' ), $json->id ) );	
                                }

                                // Register the payment on the order
                                $order->payment_complete();	
                                break;           		
                        }
                    }
                    catch( QuickPay_API_Exception $e )
                    {
                        $e->write_to_logs();
                    }
                }
                
                // The transaction was not accepted.
                // Print debug information to logs
                else {
                    // Write debug information
                    $this->log->separator();
                    $this->log->add( sprintf( __( 'Transaction failed for #%s.', 'woo-quickpay'), $order_number ) );
                    $this->log->add( sprintf( __( 'QuickPay status code: %s.', 'woo-quickpay' ), $transaction->qp_status_code ) );
                    $this->log->add( sprintf( __( 'QuickPay status message: %s.', 'woo-quickpay' ), $transaction->qp_status_msg ) );
                    $this->log->add( sprintf( __( 'Acquirer status code: %s', 'woo-quickpay' ), $transaction->aq_status_code ) );
                    $this->log->add( sprintf( __( 'Acquirer status message: %s', 'woo-quickpay' ), $transaction->aq_status_msg ) );
                    $this->log->separator();
                    
                    // Update the order statuses
					if( $transaction->type == 'subscribe' OR $transaction->type == 'recurring' ) 
                    {
						WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
					} 
                    else 
                    {
						$order->update_status( 'failed' );
					}
                }   
            } else {
                $this->log->add( sprintf( __( 'Invalid callback body for order #%s.', 'woo-quickpay' ), $order_number ) );
            }
		}


		/**
		* init_form_fields function.
		*
		* Initiates the plugin settings form fields
		*
		* @access public
		* @return array
		*/
		public function init_form_fields()
		{
			$this->form_fields = WC_QuickPay_Settings::get_fields();
		}


		/**
		* admin_options function.
		*
		* Prints the admin settings form
		*
		* @access public
		* @return string
		*/
		public function admin_options()
		{
			echo "<h3>QuickPay - {$this->id}, v" . WCQP_VERSION . "</h3>";
			echo "<p>" . __('Allows you to receive payments via QuickPay.', 'woo-quickpay') . "</p>";
            
            do_action('woocommerce_quickpay_settings_table_before');
            
			echo "<table class=\"form-table\">";
						$this->generate_settings_html();
			echo "</table";
            
            do_action('woocommerce_quickpay_settings_table_after');
		}


		/**
		* add_meta_boxes function.
		*
		* Adds the action meta box inside the single order view.
		*
		* @access public
		* @return void
		*/
		public function add_meta_boxes()
		{
			 add_meta_box( 'quickpay-payment-actions', __( 'QuickPay Payment', 'woo-quickpay' ), array( &$this, 'meta_box_payment' ), 'shop_order', 'side', 'high' );
		}


		/**
		* meta_box_payment function.
		*
		* Inserts the content of the API actions meta box
		*
		* @access public
		* @return void
		*/
		public function meta_box_payment()
		{
			global $post, $woocommerce;	
			$order = new WC_QuickPay_Order( $post->ID );	
			
			$transaction_id	= $order->get_transaction_id();

			if( $transaction_id )
			{	
				try 
				{
                    // Subscription
                    if( $order->contains_subscription() ) 
                    {
                        $transaction = new WC_QuickPay_API_Subscription();
                        $transaction->get( $transaction_id );
                        $status = $transaction->get_current_type() . ' (' . __( 'subscription', 'woo-quickpay' ) . ')';
                    }
                    // Payment
                    else
                    {
                        $transaction = new WC_QuickPay_API_Payment();
                        $transaction->get( $transaction_id );    
                        $status = $transaction->get_current_type();
                    }
					

					echo "<p class=\"woocommerce-quickpay-{$status}\"><strong>" . __( 'Current payment state', 'woo-quickpay' ) . ": " . $status . "</strong></p>";

                    if( $transaction->is_action_allowed( 'standard_actions' ) ) 
                    {
                        echo "<h4><strong>" . __( 'Standard actions', 'woo-quickpay' ) . "</strong></h4>";
                        echo "<ul class=\"order_action\">";

                        if( $transaction->is_action_allowed( 'capture' ) ) {
                            echo "<li class=\"left\"><a class=\"button\" data-action=\"capture\" data-confirm=\"". __( 'You are about to CAPTURE this payment', 'woo-quickpay' ) . "\">" . __( 'Capture', 'woo-quickpay' ) . "</a></li>";
                        }

                        if( $transaction->is_action_allowed( 'cancel' ) ) {
                            echo "<li class=\"right\"><a class=\"button\" data-action=\"cancel\" data-confirm=\"". __( 'You are about to CANCEL this payment', 'woo-quickpay' ) . "\">" . __( 'Cancel', 'woo-quickpay' ) . "</a></li>";					
                        }

                        echo	"<li>&nbsp;</li>";
                        echo "</ul>";			


                        echo "<br />";
                    }

					if( WC_QuickPay_Helper::option_is_enabled( $this->s( 'quickpay_splitcapture' ) ) )
					{
						$currency = $this->get_gateway_currency( $order );

						if( $api->is_action_allowed( 'splitcapture', $state ) AND $balance < WC_QuickPay_Helper::price_multiply( $order->get_total() ) )
						{
							echo "<div class=\"quickpay-split-container\">";
								echo "<h4><strong>" . __( 'Split payment', 'woo-quickpay' ) . "</strong></h4>";
								echo "<div class=\"totals_groups\">";
									echo "<h4><span class=\"inline_total\">{$currency}</span>" . __( 'Currency', 'woo-quickpay' ) . "</h4>";
									echo "<h4><span class=\"quickpay-balance inline_total\">" . WC_QuickPay_Helper::price_normalize( $balance ) ."</span>" .  __( 'Balance', 'woo-quickpay' ) . "</h4>";
									echo "<h4><span class=\"quickpay-remaining inline_total\">" . WC_QuickPay_Helper::price_normalize( WC_QuickPay_Helper::price_multiply( $order->get_total() )  - $balance ) ."</span>" .  __( 'Remaining', 'woo-quickpay' ) . "</h4>";
									echo "<h4><span class=\"quickpay-remaining inline_total\"><input type=\"text\" style=\"width:50px;text-align:right;\" id=\"quickpay_split_amount\" name=\"quickpay_split_amount\" /></span><strong>" .  __( 'Amount to capture', 'woo-quickpay' ) . "</strong></h4>";
								echo "</div>";

								echo "<ul>
										<li>
											<p>
												<span><a id=\"quickpay_split_button\" data-action=\"split_capture\" style=\"display:none;\" class=\"button\" data-notify=\"", __( 'You are about to SPLIT CAPTURE this payment. This means that you will capture the amount stated in the input field. The payment state will remain open.', 'woo-quickpay' ), "\" href=\"" . admin_url( 'post.php?post={$post->ID}&action=edit&quickpay_action=splitcapture' ) . "\">" . __( 'Split Capture', 'woo-quickpay' ) . "</a></span>
												<span><a id=\"quickpay_split_finalize_button\" data-action=\"split_finalize\" style=\"display:none;\" class=\"button\" data-notify=\"", __( 'You are about to SPLIT CAPTURE and FINALIZE this payment. This means that you will capture the amount stated in the input field and that you can no longer capture money from this transaction.', 'woo-quickpay' ), "\" href=\"" . admin_url( 'post.php?post={$post->ID}&action=edit&quickpay_action=splitcapture&quickpay_finalize=yes' ) . "\">" . __( 'Split and finalize', 'woo-quickpay' ) . "</a></span>
											</p>
										</li>
									  </ul>
									";
							echo "</div>";
						}
					}

					printf('<p><small><strong>%s:</strong> %d</small>', __( 'Transaction ID', 'woo-quickpay'), $transaction_id );

					$transaction_order_id = $order->get_transaction_order_id();
					if( isset( $transaction_order_id ) && ! empty( $transaction_order_id) ) {
						printf('<p><small><strong>%s:</strong> %s</small>', __( 'Transaction Order ID', 'woo-quickpay'), $transaction_order_id);
					}
				}
				catch( QuickPay_API_Exception $e ) 
				{
					$e->write_to_logs();
					$e->write_standard_warning();
				}
			}

			// Show payment ID and payment link for orders that have not yet
			// been paid. Show this information even if the transaction ID is missing.
			$payment_id = $order->get_payment_id();
			if( isset( $payment_id ) && ! empty( $payment_id ) ) {
				printf('<p><small><strong>%s:</strong> %d</small>', __( 'Payment ID', 'woo-quickpay'), $payment_id);
			}

			$payment_link = $order->get_payment_link();
			if( isset( $payment_link ) && ! empty( $payment_link )) {
				printf('<p><small><strong>%s:</strong> <br /><input type="text" style="%s"value="%s" readonly /></small></p>', __( 'Payment Link', 'woo-quickpay'), 'width:100%', $payment_link);
			}
		}


		/**
		* email_instructions function.
		*
		* Adds custom text to the order confirmation email.
		*
		* @access public
		* @return boolean/string/void
		*/		
		public function email_instructions( $order, $sent_to_admin )
		{
			if ( $sent_to_admin || $order->status !== 'processing' && $order->status !== 'completed' || $order->payment_method !== 'quickpay' ) {
				return;
			}
				
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}		
		}
	

		/**
		* apply_custom_order_data function.
		*
		* Applies transaction ID and state to the order data overview
		*
		* @access public
		* @return void
		*/	
		public function apply_custom_order_data( $column )
		{
			global $post, $woocommerce;

			$order = new WC_QuickPay_Order( $post->ID );
			
			// ? ABOVE 2.1 : BELOW 2.1
			$check_column = version_compare( $woocommerce->version, '2.1', '>' ) ? 'shipping_address' : 'billing_address';

			// Show transaction ID on the overview
			if( $column == $check_column )
			{	
				// Insert transaction id and payment status if any
				$transaction_id = $order->get_transaction_id();

				if( $transaction_id )
				{
                    echo "<div data-quickpay-transaction-id=\"{$transaction_id}\" data-quickpay-post-id=\"{$post->ID}\" class=\"quickpay-loader\">";
                        echo "<small class=\"meta\" data-quickpay-show=\"id\"></small>";	
                        echo "<small class=\"meta\" data-quickpay-show=\"order\"></small>";
                        echo "<small class=\"meta\" data-quickpay-show=\"status\"></small>";
                        echo "<small class=\"meta\" data-quickpay-show=\"test\"></small>";          
                        echo "<small class=\"meta\" data-quickpay-show=\"error\"></small>";          
                    echo "</div>";
				}
			}		
		}

		/**
		* FILTER: apply_gateway_icons function.
		*
		* Sets gateway icons on frontend
		*
		* @access public
		* @return void
		*/	
		public function apply_gateway_icons( $icon, $id ) {
			if($id == $this->id) {
				$icon = '';
				$icons = $this->s('quickpay_icons');

				if( ! empty( $icons ) ) {
					$icons_maxheight = $this->gateway_icon_size();

					foreach( $icons as $key => $item ) {
						$icon .= $this->gateway_icon_create($item, $icons_maxheight);
					}
				}
			}

			return $icon;
		}
        
        
		/**
		* gateway_icon_create
		*
		* Helper to get the a gateway icon image tag
		*
		* @access protected
		* @return void
		*/	        
        protected function gateway_icon_create($icon, $max_height) {
            $icon_url = WC_HTTPS::force_https_url( plugin_dir_url( __FILE__ ) . 'assets/images/cards/' . $icon . '.png' );
            return '<img src="' . $icon_url . '" alt="' . esc_attr( $this->get_title() ) . '" style="max-height:' . $max_height . '"/>';
        }
        
        
		/**
		* gateway_icon_size
		*
		* Helper to get the a gateway icon image max height
		*
		* @access protected
		* @return void
		*/	 
        protected function gateway_icon_size() {
            $settings_icons_maxheight = $this->s( 'quickpay_icons_maxheight' );
            return ! empty( $settings_icons_maxheight ) ? $settings_icons_maxheight . 'px' : '20px';           
        }

        
		/**
		* 
		* get_gateway_currency
		*
		* Returns the gateway currency
		*
		* @access public
		* @return void
		*/	
		public function get_gateway_currency( $order ) {
			if( WC_QuickPay_Helper::option_is_enabled( $this->s( 'quickpay_currency_auto' ) ) ) {
				$currency = $order->get_order_currency();
			}
			else {
				$currency = $this->s( 'quickpay_currency' );
			}

			$currency = apply_filters( 'woocommerce_quickpay_currency', $currency, $order );

			return $currency;
		}


		/**
		* 
		* get_gateway_language
		*
		* Returns the gateway language
		*
		* @access public
		* @return void
		*/	
		public function get_gateway_language() {
			$language = apply_filters( 'woocommerce_quickpay_language', $this->s( 'quickpay_language' ) );
			return $language;
		}
     
                   
 		/**
		* 
		* in_plugin_update_message
		*
		* Show plugin changes. Code adapted from W3 Total Cache.
		*
		* @access public
		* @static
		* @return void
		*/	            
        public static function in_plugin_update_message( $args ) {
            $transient_name = 'wcqp_upgrade_notice_' . $args['Version'];
            if ( false === ( $upgrade_notice = get_transient( $transient_name ) ) ) {
                $response = wp_remote_get( 'https://plugins.svn.wordpress.org/woocommerce-quickpay/trunk/README.txt' );

                if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
                    $upgrade_notice = self::parse_update_notice( $response['body'] );
                    set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
                }
            }

            echo wp_kses_post( $upgrade_notice );  
        }
        
        
        /**
         *
         * parse_update_notice
         * 
         * Parse update notice from readme file.
         * @param  string $content
         * @return string
         */
        private static function parse_update_notice( $content ) {
            // Output Upgrade Notice
            $matches        = null;
            $regexp         = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( WCQP_VERSION ) . '\s*=|$)~Uis';
            $upgrade_notice = '';

            if ( preg_match( $regexp, $content, $matches ) ) {
                $version = trim( $matches[1] );
                $notices = (array) preg_split('~[\r\n]+~', trim( $matches[2] ) );

                if ( version_compare( WCQP_VERSION, $version, '<' ) ) {

                    $upgrade_notice .= '<div class="wc_plugin_upgrade_notice">';

                    foreach ( $notices as $index => $line ) {
                        $upgrade_notice .= wp_kses_post( preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) );
                    }

                    $upgrade_notice .= '</div> ';
                }
            }

            return wp_kses_post( $upgrade_notice );
        }
	}

	// Make the object available for later use
	function WC_QP() {
		return WC_QuickPay::get_instance();
	}
	
	// Instantiate
	WC_QP();
	WC_QP()->hooks_and_filters();
    
	// Add the gateway to WooCommerce
	function add_quickpay_gateway( $methods )
	{
		$methods[] = 'WC_QuickPay';
        
        return apply_filters('woocommerce_quickpay_load_instances', $methods);
	}
	add_filter('woocommerce_payment_gateways', 'add_quickpay_gateway' );
    add_filter('woocommerce_quickpay_load_instances', 'WC_QuickPay::filter_load_instances');
	add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'WC_QuickPay::add_action_links');
}

/**
 * Run installer / updater
 * @param string __FILE__ - The current file
 * @param function - Do the installer/update logic.
 */
register_activation_hook( __FILE__, function() { 
	require_once( 'classes/woocommerce-quickpay-install.php' );
	
	// Run the installer on the first install.
	if (WC_QuickPay_Install::is_first_install() )
	{
		WC_QuickPay_Install::install();
	}
	// The plugin has already been installed. Run updater instead.
	else
	{
		// Check if the current stored version is lower than the one we just installed.
		if ( version_compare( WC_QuickPay_Install::get_db_version(), WCQP_VERSION, '<' ) ) 
		{
			WC_QuickPay_Install::update();
		}
	}
} );

?>