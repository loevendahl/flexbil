<div class="step_wrapper" data-step="1">

	<form action="#" method="post" id="book_form" class="form-horizontal" role="form">

	<div class="table_group">

		<div class="form-group pickup_group">

			<label class="col-sm-5 control-label lbl"><?php _e('Pickup Location', 'bizzthemes'); ?><span class="req">*</span></label>

			<div class="col-sm-7">

				<?php				

				$sposts = get_posts( apply_filters( 'bizz_pickup_location_args', array(

					'post_type' => 'bizz_locations',

					'post_status' => 'publish',

					'orderby' => 'menu_order',

					'order' => 'ASC',

					'numberposts' => -1,

					'suppress_filters' => false

				) ) );

				if ( count( $sposts ) != 1 ) {

					echo '<select name="location_pickup" class="form-control location_p location" required="required">' . "\n";

					echo '<option value="">' . __('-- select pick-up location --','bizzthemes') . '</option>' . "\n";

					foreach ($sposts as $key => $post) {

						$address = get_post_meta($post->ID, 'bizzthemes_location_address', true).", ".get_post_meta($post->ID, 'bizzthemes_location_city', true);

						echo '<option value="' . $post->ID . '">' . $post->post_title . (($address) ? ', ' . $address : '') . '</option>' . "\n";

					}

					echo '</select>' . "\n";

					echo '<input type="hidden" name="pickup_l" class="location_input pickup_l" value="" />' . "\n";

				}

				elseif ( count( $sposts ) == 1 ) {

					$address = get_post_meta( $sposts[0]->ID, 'bizzthemes_location_address', true );

					echo '<div class="help-block">' . $sposts[0]->post_title . (($address) ? ', ' . $address : '') . '</div>' . "\n";

					echo '<input type="hidden" name="pickup_l" class="location_input pickup_l" value="' . $sposts[0]->ID . '" />' . "\n";

				}

				wp_reset_postdata();

				?>

			</div>

		</div>

		<?php 

		$sposts = get_posts( apply_filters( 'bizz_return_location_args', array(

			'post_type' => 'bizz_locations',

			'post_status' => 'publish',

			'orderby' => 'menu_order',

			'order' => 'ASC',

			'numberposts' => -1,

			'suppress_filters' => false

		) ) );

		if ( count( $sposts ) != 1 ) { ?>

		<div class="form-group return_checkbox">

			<div class="col-sm-offset-5 col-sm-7">

				<div class="checkbox">

					<label class="chk">

						<input type="checkbox" name="return_l_checkbox" class="location_checkbox return_l_c"> <?php _e('Return to a different location', 'bizzthemes'); ?>

					</label>

				</div>

			</div>

		</div>

		<?php } ?>

		<div class="form-group return_group return_group_location hidden">

			<label class="col-sm-5 control-label lbl"><?php _e('Return Location', 'bizzthemes'); ?></label>

			<div class="col-sm-7">

				<?php

				if ( count( $sposts ) != 1 ) {

					echo '<select name="location_return" class="form-control location_r location">' . "\n";

					echo '<option value="">' . __('-- select return location --','bizzthemes') . '</option>' . "\n";

					foreach ($sposts as $key => $post) {

						$address = get_post_meta($post->ID, 'bizzthemes_location_address', true);

						echo '	<option value="' . $post->ID . '">' . $post->post_title . (($address) ? ', ' . $address : '') . '</option>' . "\n";

					}

					echo '</select>' . "\n";

					echo '<input type="hidden" name="return_l" class="location_input return_l" value="" />' . "\n";

				}

				elseif ( count( $sposts ) == 1 ) {

					$address = get_post_meta( $sposts[0]->ID, 'bizzthemes_location_address', true );

					echo '<div class="help-block">' . $sposts[0]->post_title . (($address) ? ', ' . $address : '') . '</div>' . "\n";

					echo '<input type="hidden" name="return_l" class="location_input return_l" value="' . $sposts[0]->ID . '" />' . "\n";

				}

				wp_reset_postdata();

				?>

			</div>

		</div>

		<div class="form-group pickup_group">

			<label class="col-sm-5 control-label lbl"><?php _e('Pickup Date', 'bizzthemes'); ?><span class="req">*</span></label>

			<div class="col-sm-7">

				<div class="input-group grp">

					<input class="form-control booking_date" type="text" name="date_pickup" size="10" required="required" <?php echo ( count( $sposts ) != 1 ) ? 'disabled' : ''; ?> />

					<select class="form-control time_field" name="time_pickup" required="required" <?php echo ( count( $sposts ) != 1 ) ? 'disabled' : ''; ?>>

						<?php

						echo '<option value="">' . __('-- select --','bizzthemes') . '</option>' . "\n";

						$times = bizz_create_time_range( '00:00', '23:30', apply_filters( 'bizz_location_time_interval', '30 mins' ) );

						foreach ($times as $key => $time) {

							echo '<option value="' . date('H:i', $time) . '" ' . selected( date('H:i', $time), apply_filters( 'bizz_default_pickup_time', '12:00' ), false ) . '>' . date(get_option('time_format', 'H:i'), $time) . '</option>';

						}

						?>

					</select>

					<input type="hidden" name="pickup_d" class="pickup_d" value="" />

					<input type="hidden" name="pickup_m" class="pickup_m" value="" />

					<input type="hidden" name="pickup_y" class="pickup_y" value="" />

					<input type="hidden" name="pickup_dn" class="pickup_dn" value="" />

				</div>

			</div>

		</div>

		<div class="form-group return_group">

			<label class="col-sm-5 control-label lbl"><?php _e('Return Date', 'bizzthemes'); ?><span class="req">*</span></label>

			<div class="col-sm-7">

				<div class="input-group grp">

					<input class="form-control booking_date" type="text" name="date_return" size="10" required="required" <?php echo ( count( $sposts ) != 1 ) ? 'disabled' : ''; ?> />

					<select class="form-control time_field" name="time_return" required="required" <?php echo ( count( $sposts ) != 1 ) ? 'disabled' : ''; ?>>

						<?php

						echo '<option value="">' . __('-- select --','bizzthemes') . '</option>' . "\n";

						$times = bizz_create_time_range( '00:00', '23:30', apply_filters( 'bizz_location_time_interval', '30 mins' ) );

						foreach ($times as $key => $time) {

							echo '<option value="' . date('H:i', $time) . '" ' . selected( date('H:i', $time), apply_filters( 'bizz_default_return_time', '12:00' ), false ) . '>' . date(get_option('time_format', 'H:i'), $time) . '</option>';

						}

						?>

					</select>

					<input type="hidden" name="return_d" class="return_d" value="" />

					<input type="hidden" name="return_m" class="return_m" value="" />

					<input type="hidden" name="return_y" class="return_y" value="" />

					<input type="hidden" name="return_dn" class="return_dn" value="" />

				</div>

			</div>

		</div>

		<?php 

		$sposts = get_posts( apply_filters( 'bizz_coupon_args', array(

			'post_type' => 'bizz_coupons',

			'post_status' => 'publish',

			'orderby' => 'menu_order',

			'order' => 'ASC',

			'numberposts' => -1,

			'suppress_filters' => false

		) ) );

		if ( count( $sposts ) ) { ?>

		<div class="form-group return_checkbox">

			<div class="col-sm-offset-5 col-sm-7">

				<div class="checkbox">

					<label class="chk">

						<input type="checkbox" name="coupon_checkbox" class="coupon_checkbox"> <?php _e('Use a coupon code', 'bizzthemes'); ?>

					</label>

				</div>

			</div>

		</div>

		<?php } ?>

		<div class="form-group coupon_group hidden">

			<label class="col-sm-5 control-label lbl"><?php _e('Coupon Code', 'bizzthemes'); ?></label>

			<div class="col-sm-7">

				<input class="form-control coupon_code" type="text" name="coupon_code" size="10" />

			</div>

		</div>

		<?php wp_reset_postdata(); ?>

	</div>

	<div class="sbmt ar">

		<input type="text" name="is_spam" id="is_spam" class="spamprevent" />

		<div class="loading"><!----></div>

		<button class="btn btn-danger btn-lg" name="submit" type="submit" value="<?php _e('Submit', 'bizzthemes'); ?>"><?php _e('Submit', 'bizzthemes'); ?></button>

		<button class="btn btn-link" name="reset" type="reset" value="<?php _e('Reset', 'bizzthemes'); ?>"><?php _e('Reset', 'bizzthemes'); ?></button>

	</div>

	</form>

</div>