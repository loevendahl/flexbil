<div class="step_wrapper hidden" data-step="2">
	<div class="row">
		<div class="filter fil-1 col-md-6">
			<label for="car_type"><?php _e('Type:', 'bizzthemes'); ?></label>
			<select id="car_type" class="form-control car_type">
				<option value="-1"><?php _e('Any', 'bizzthemes'); ?></option>
				<?php
				$type_terms = get_terms( 'bizz_cars_type', array( 'hide_empty' => 0 ) );
				$type_options = array();
				foreach ($type_terms as $type_term) {
					echo '<option value="'.$type_term->slug.'">'.$type_term->name.'</option>';
				}
				?>
			</select>
		</div>
		<div class="filter fil-3 col-md-6">
			<label for="car_sort"><?php _e('Sort:', 'bizzthemes'); ?></label>
			<select id="car_sort" class="form-control car_sort">
				<?php
				// get booking settings
				global $booking_settings;
				$opt_s  = $booking_settings->get_settings();
				?>
				<option value="-1"><?php _e('Latest first', 'bizzthemes'); ?></option>
				<option value="price-low" <?php selected( $opt_s['vehicle_sort'], 'price-asc' ); ?>><?php _e('Price: low to high', 'bizzthemes'); ?></option>
				<option value="price-high" <?php selected( $opt_s['vehicle_sort'], 'price-desc' ); ?>><?php _e('Price: high to low', 'bizzthemes'); ?></option>
				<option value="name-asc" <?php selected( $opt_s['vehicle_sort'], 'name-asc' ); ?>><?php _e('Name: ascending', 'bizzthemes'); ?></option>
				<option value="name-desc" <?php selected( $opt_s['vehicle_sort'], 'name-desc' ); ?>><?php _e('Name: descending', 'bizzthemes'); ?></option>
			</select>
		</div>
	</div>
	<div class="list_wrapper">
		<ul id="car_list" class="carlist clist">
			<li class="dummy clearfix">
				<div class="data_wrapper">
					<div class="picture left">
						<img class="car_image img-polaroid" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="100" alt="" />
					</div>
					<div class="details clearfix">
						<h2 class="car_name"><?php _e('Vehicle Name', 'bizzthemes'); ?></h2>
						<span class="avail no text-danger hidden"><?php _e('Not Available', 'bizzthemes'); ?></span>
						<input type="hidden" class="car_id" value="" />
						<input type="hidden" class="car_type" value="" />
						<input type="hidden" class="car_transmission" value="" />
						<input type="hidden" class="car_cost" value="" />
						<input type="hidden" class="car_val" value="" />
						<ul class="car_properties">
							<li class="seats"><span class="eq_value"></span></li>
							<li class="doors"><span class="eq_value"></span></li>
							<li class="transmission"><span class="eq_value"></span></li>
						</ul>
						<a href="#" class="toggled car_details"><?php _e('Details', 'bizzthemes'); ?></a>
						<?php if ( current_user_can( 'manage_options' ) ) { ?>
							<a href="#" class="car_edit"><?php _e('(Edit)', 'bizzthemes'); ?></a>
						<?php } ?>
						<div class="car_details_tooltip"></div>
					</div>
				</div>
				<div class="price_wrapper">
					<input class="btn btn-danger button_select_car" type="button" value="<?php _e('Book Now', 'bizzthemes'); ?>" />
					<span class="car_price"></span>
					<span class="car_price_int"></span>
					<span class="car_count"></span>
					<div class="clearfix"></div>
					<div class="car_availability"></div> 
				</div>
			</li>
		</ul>
	</div>
</div>