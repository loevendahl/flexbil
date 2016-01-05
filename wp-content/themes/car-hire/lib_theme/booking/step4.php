 <body><?php //body_class(); 

 global $current_user;

      get_currentuserinfo();



 ?>

<div class="step_wrapper hidden" data-step="4">

<?php 

// get booking settings

global $booking_settings;

$book_opts = $booking_settings->get_settings();

$price_range = ( $book_opts['pay_pricerange'] == 'perhour'  ) ? __('Hours', 'bizzthemes') : __('Days', 'bizzthemes');

?>



	<form action="#" method="post" id="check_form" role="form">

	

	<div class="checkout_form row">

	<div class="left_check col-md-6">

		<div class="form-horizontal">

		<h3><?php _e('Your Personal Information', 'bizzthemes'); ?></h3>

		<hr/>

			<?php if ( $book_opts['f_customer_title'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="customer_title"><?php _e('Customer Title', 'bizzthemes'); ?><?php if ( $book_opts['f_customer_title'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<select id="customer_title" name="customer_title" class="form-control <?php echo ( $book_opts['f_customer_title'] == 'yes-req' ) ? ' validate' : ''; ?>">

					<!--	<option value=""><?php // _e('-- Select --', 'bizzthemes'); ?></option> -->

						<option value="mr"><?php _e('Mr', 'bizzthemes'); ?></option>

						<option value="mrs"><?php _e('Mrs', 'bizzthemes'); ?></option>

						<option value="miss"><?php _e('Miss', 'bizzthemes'); ?></option>

						<option value="dr"><?php _e('Dr', 'bizzthemes'); ?></option>

						<option value="prof"><?php _e('Prof', 'bizzthemes'); ?></option>

						<option value="rev"><?php _e('Rev', 'bizzthemes'); ?></option>

					</select>

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_first_name'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="first_name"><?php _e('First Name', 'bizzthemes'); ?><?php if ( $book_opts['f_first_name'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="first_name" name="first_name" class="form-control <?php echo ( $book_opts['f_first_name'] == 'yes-req' ) ? ' validate' : '';  ?>" value="<?php echo $current_user->user_firstname ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_last_name'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="last_name"><?php _e('Last Name', 'bizzthemes'); ?><?php if ( $book_opts['f_last_name'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="last_name" name="last_name" class="form-control <?php echo ( $book_opts['f_last_name'] == 'yes-req' ) ? ' validate' : ''; ?>" value="<?php echo $current_user->user_lastname ?>"/>

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_email'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="email"><?php _e('Email', 'bizzthemes'); ?><?php if ( $book_opts['f_email'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="email" name="email" class="form-control <?php echo ( $book_opts['f_email'] == 'yes-req' ) ? ' validate' : ''; ?>" value="<?php echo $current_user->user_email ?>"/>

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_phone'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="phone"><?php _e('Phone', 'bizzthemes'); ?><?php if ( $book_opts['f_phone'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="phone" name="phone" class="form-control col-md-12<?php echo ( $book_opts['f_phone'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_contact_option'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="contact_option"><?php _e('Contact Option', 'bizzthemes'); ?><?php if ( $book_opts['f_contact_option'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<select id="contact_option" name="contact_option" class="form-control col-md-12<?php echo ( $book_opts['f_contact_option'] == 'yes-req' ) ? ' validate' : ''; ?>">

						<option value=""><?php _e('-- Select --', 'bizzthemes'); ?></option>

						<option value="email"><?php _e('Email', 'bizzthemes'); ?></option>

						<option value="sms"><?php _e('Phone (SMS)', 'bizzthemes'); ?></option>

						<option value="call"><?php _e('Phone (Call)', 'bizzthemes'); ?></option>

					</select>

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_address'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="address"><?php _e('Address', 'bizzthemes'); ?><?php if ( $book_opts['f_address'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="address" name="address" class="form-control col-md-12<?php echo ( $book_opts['f_address'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_postcode_zip'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="postcode"><?php _e('Postcode/ZIP', 'bizzthemes'); ?><?php if ( $book_opts['f_postcode_zip'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="postcode" name="postcode" class="form-control col-md-12<?php echo ( $book_opts['f_postcode_zip'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_city'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="city"><?php _e('City', 'bizzthemes'); ?><?php if ( $book_opts['f_city'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="city" name="city" class="form-control col-md-12<?php echo ( $book_opts['f_city'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_state_province'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="state_or_province"><?php _e('State/Province', 'bizzthemes'); ?><?php if ( $book_opts['f_state_province'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="state_or_province" name="state_or_province" class="form-control col-md-12<?php echo ( $book_opts['f_state_province'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_country'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="countries"><?php _e('Country', 'bizzthemes'); ?><?php if ( $book_opts['f_country'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<select id="countries" name="countries" class="form-control col-md-12<?php echo ( $book_opts['f_country'] == 'yes-req' ) ? ' validate' : ''; ?>">

						<option value=""><?php _e('-- Select --', 'bizzthemes'); ?></option>

<?php

						$countries = bizz_country_list();

						foreach ( $countries as $country) {

							echo '<option Selected value="DK">Denmark</option>';

							echo '<option value="'.$country['value'].'">'.$country['name'].'</option>';

						}

?>

					</select>

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_flight_number'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="flight"><?php _e('Flight Number (like BA2244)', 'bizzthemes'); ?><?php if ( $book_opts['f_flight_number'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="flight" name="flight" class="form-control col-md-12<?php echo ( $book_opts['f_flight_number'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_age'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_age"><?php _e('Age of Driver', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_age'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_age" name="driver_age" class="form-control col-md-12<?php echo ( $book_opts['f_driver_age'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_birth'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_birth"><?php _e('Date of Birth', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_birth'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_birth" name="driver_birth" class="form-control col-md-12<?php echo ( $book_opts['f_driver_birth'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_license'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_license"><?php _e('Driving Licence Number', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_license'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_license" name="driver_license" class="form-control col-md-12<?php echo ( $book_opts['f_driver_license'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_country'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_country"><?php _e('Country / State of issue', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_country'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_country" name="driver_country" class="form-control col-md-12<?php echo ( $book_opts['f_driver_country'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_issue'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_issue"><?php _e('Issue Date', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_issue'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_issue" name="driver_issue" class="form-control col-md-12<?php echo ( $book_opts['f_driver_issue'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_expiry'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_expiry"><?php _e('Expiry Date', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_expiry'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="driver_expiry" name="driver_expiry" class="form-control col-md-12<?php echo ( $book_opts['f_driver_expiry'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_driver_accidents'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="driver_accidents"><?php _e('Accidents, claims or motoring convictions over the past 3 years?', 'bizzthemes'); ?><?php if ( $book_opts['f_driver_accidents'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<select id="driver_accidents" name="driver_accidents" class="form-control col-md-12<?php echo ( $book_opts['f_driver_accidents'] == 'yes-req' ) ? ' validate' : ''; ?>">

						<option value=""><?php _e('-- Select --', 'bizzthemes'); ?></option>

						<option value="no"><?php _e('No', 'bizzthemes'); ?></option>

						<option value="yes"><?php _e('Yes (please provide details in Comments box below)', 'bizzthemes'); ?></option>

					</select>

				</div>

			</div>

			<?php } ?>			

			<?php if ( $book_opts['f_number_passengers'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="number_passengers"><?php _e('Number of Passengers', 'bizzthemes'); ?><?php if ( $book_opts['f_number_passengers'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<input type="text" id="number_passengers" name="number_passengers" class="form-control col-md-12<?php echo ( $book_opts['f_number_passengers'] == 'yes-req' ) ? ' validate' : ''; ?>" />

				</div>

			</div>

			<?php } ?>

			<?php if ( $book_opts['f_comments_question'] != 'no' ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="comms"><?php _e('Comments/Questions', 'bizzthemes'); ?><?php if ( $book_opts['f_comments_question'] == 'yes-req' ) { ?><span class="req">*</span><?php } ?></label>

				<div class="col-sm-7">

					<textarea rows="10" id="comms" name="comms" class="form-control col-md-12<?php echo ( $book_opts['f_comments_question'] == 'yes-req' ) ? ' validate' : ''; ?>"></textarea>

				</div>

			</div>

			<?php } ?>

		</div>

		<?php do_action( 'step4_hook' ); ?>

		<?php if ( ( $book_opts['pay_allow'] && $book_opts['pay_paypal'] != '' ) || $book_opts['pay_credit'] || $book_opts['pay_cod'] || $book_opts['pay_banktransfer'] ) { ?>

		<div class="form-horizontal">

			<h3><?php _e('Payment', 'bizzthemes'); ?></h3>

			<hr/>

			<div class="form-group">

				<label class="col-sm-5 control-label" for="payment_method"><?php _e('Payment Method', 'bizzthemes'); ?><span class="req">*</span></label>

				<div class="col-sm-7">

					<select id="payment_method" name="payment_method" class="form-control col-md-12 validate">

						<option value=""><?php _e('-- Select --', 'bizzthemes'); ?></option>

						<?php if ( $book_opts['pay_allow'] && $book_opts['pay_paypal'] != '' ) { ?>

						<option value="paypal"><?php _e('PayPal', 'bizzthemes'); ?></option>

						<?php } ?>

						<?php if ( $book_opts['pay_credit'] ) { ?>

						<option value="creditcard"><?php _e('Credit Card', 'bizzthemes'); ?></option>

						<?php } ?>

						<?php if ( $book_opts['pay_cod'] ) { ?>

						<option value="cod"><?php _e('Cash on Delivery', 'bizzthemes'); ?></option>

						<?php } ?>

                        <?php if ( $book_opts['pay_banktransfer'] ) { ?>

						<option value="banktransfer"><?php _e('Bank Transfer', 'bizzthemes'); ?></option>

						<?php } ?>

						<?php if ( $book_opts['pay_mollie'] ) { ?>

						<option value="mollie"><?php echo $book_opts['pay_mollie_label']; ?></option>

						<?php } ?>

					</select>

				</div>

			</div>
<div class="form-horizontal">

			<?php if ( $book_opts['terms_conditions'] != '' ) { ?>

			<div class="form-group">

				<div class="col-sm-7 col-sm-offset-5">

					<label class="checkbox">

						<input type="checkbox" id="terms" name="terms" value="" class="validate" rev="<?php _e('You have to agree to the Booking Conditions', 'bizzthemes'); ?>">

						<?php _e( 'I agree to the <a class="accordion-toggle" data-toggle="collapse" href="#terms_conditions">Terms and Conditions</a>.', 'bizzthemes' ); ?>

					</label>

					<div id="terms_conditions" class="accordion-body collapse">

						<?php echo wpautop( wp_specialchars_decode( $book_opts['terms_conditions'] ) ); ?>

					</div>

				</div>

			</div>

			<?php } ?>



			<div class="credit-card-payment">

				<div class="form-group">
 <!--add QP -->
					<div class="quickpay-payment" style="padding-right:10px; text-align:right; display: none">

						<input class="btn btn-lg btn-danger" type="button" value="<?php _e('Checkout with Quickpay', 'bizzthemes'); ?>" id="submit_quickpay" />



						<div class="loading"><!----></div>

					</div>
                      <!--add QP end-->
                      
                      			</div>

		</div>
        </div>
		<?php } ?>

		


			<div class="form-group">

				<div class="col-sm-7 col-sm-offset-5">

					<?php if ( $book_opts['pay_allow'] && $book_opts['pay_paypal'] != '' ) { ?>

					<div class="paypal-payment">

						<input class="btn btn-lg btn-danger" type="submit" value="<?php _e('Checkout with PayPal', 'bizzthemes'); ?>" id="submit_paypal" />

						<!-- PayPal Logo -->

						<a href="#" class="paypal-button" onClick="javascript:window.open('https://www.paypal.com/uk/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=500');">

							<img src="https://www.paypal.com/en_GB/Marketing/i/logo/PayPal_logo_80x35.gif" alt="PayPal Standard Logo">

						</a>

						<!-- PayPal Logo -->

						<div class="loading"><!----></div>

					</div>

					<?php } ?>

					<div class="no-payment">

						<input class="btn btn-danger btn-lg" type="submit" value="<?php _ex('Checkout', 'checkout button', 'bizzthemes'); ?>" id="submit_checkout" />

						<div class="loading"><!----></div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="right_check col-md-6">

		<div class="form-horizontal">

			<h3><?php _e('Location / Time', 'bizzthemes'); ?> <small><a href="#" class="chng" data-rel="1"><?php _e('Change', 'bizzthemes'); ?></a></small></h3>

			<hr/>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Pickup location', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="pickup_location"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Pickup date', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="pickup_date"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Return location', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="return_location"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Return date', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="return_date"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php echo $price_range; ?></label>

				<div class="col-sm-7">

					<div id="days_details"></div>

				</div>

			</div>

		</div>

		<div class="form-horizontal">

			<h3><?php _e('Vehicle', 'bizzthemes'); ?> <small><a href="#" class="chng" data-rel="2"><?php _e('Change', 'bizzthemes'); ?></a></small></h3>

			<hr/>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Image', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<img class="car_image img-polaroid" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="75" alt="" />

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Name', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="car_name"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Quantity', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="car_count"></div>

				</div>

			</div>

		</div>

		<div class="form-horizontal">

			<h3><?php _e('Extras', 'bizzthemes'); ?> <small><a href="#" class="chng" data-rel="3"><?php _e('Change', 'bizzthemes'); ?></a></small></h3>

			<hr/>

			<div class="selected_extras">

				<div class="form-group dummy">

					<label class="col-sm-5 control-label extra_name"></label>

					<div class="col-sm-7">

						<div class="extra_cost"></div>

					</div>

				</div>

			</div>

		</div>

		<div class="form-horizontal">

			<h3><?php _e('Estimated Charge', 'bizzthemes'); ?></h3>

			<hr/>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Vehicle(s)', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="car_pay"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Extras', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="extras_pay"></div>

				</div>

			</div>

			<?php if ( $book_opts['pay_tax'] != 0 ) { ?>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Tax', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="tax_pay"></div>

				</div>

			</div>

			<?php } ?>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Total', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="total_pay"></div>

				</div>

			</div>

			<div class="form-group">

				<label class="col-sm-5 control-label"><?php _e('Deposit', 'bizzthemes'); ?></label>

				<div class="col-sm-7">

					<div id="deposit_pay"></div>

				</div>

			</div>

		</div>

	</div>

	</div>



	</form>

<?php 

	if ( $book_opts['pay_allow'] && $book_opts['pay_paypal'] != '' ) {

	$url = TEST_MODE ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

	

	// Remove WPML home_url filter

	global $sitepress;

	remove_filter( 'home_url', array( $sitepress, 'home_url' ), 1, 4 );

?>

	<form action="<?php echo $url; ?>" method="post" style="display: none" id="crPaypal" name="crPaypal">

		<input type="hidden" name="cmd" value="_xclick" />

		<input type="hidden" name="business" value="<?php echo $book_opts['pay_paypal']; ?>" />

		<input type="hidden" name="item_name" value="<?php _e('Deposit', 'bizzthemes'); ?>" />

		<input type="hidden" name="item_number" value="1" />

		<input type="hidden" name="amount" value="" />

		<input type="hidden" name="invoice" value="" />

		<input type="hidden" name="no_shipping" value="1" />

		<input type="hidden" name="no_note" value="1" />

		<input type="hidden" name="currency_code" value="<?php echo $book_opts['pay_currency']; ?>" />

		<input type="hidden" name="return" value="<?php echo $book_opts['pay_thankyou']; ?>" />

		<input type="hidden" name="notify_url" value="<?php echo trailingslashit( home_url() ) . '?paypalListener=paypal_standard_IPN'; ?>" />

		<input type="hidden" name="custom" value="" />

		<input type="hidden" name="lc" value="US" />

		<input type="hidden" name="rm" value="2" />

		<input type="hidden" name="bn" value="PP-BuyNowBF" />

		<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />

	</form>

<?php

	// Add back WPML home_url filter

	if ( class_exists( 'SitePress' ) ) {

		add_filter( 'home_url', array( $sitepress, 'home_url' ), 1, 4 );

	}

	}

?>



</div>

</body>