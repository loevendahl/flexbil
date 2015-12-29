<div class="step_wrapper hidden" data-step="3">
	<div class="extras_list_wrapper">
		<ul id="extras_list" class="clist">
			<li class="clearfix dummy">
				<div class="checkbox_wrapper">
					<input type="checkbox" class="car_extras_check" />
					<input type="hidden" class="extras_price" value="" />
				</div>
				<div class="details_wrapper">
					<div class="picture left">
						<img class="extras_image img-polaroid" src="<?php echo get_template_directory_uri() . '/lib_theme/images/no-img.jpg'; ?>" width="100" alt="" />
					</div>
					<div class="details">
						<h2 class="extras_name"><?php _e('Extra Name', 'bizzthemes'); ?></h2>
						<input type="hidden" class="car_extras_id" value="" />
						<input type="hidden" class="car_extras_slug" value="" />
						<div class="extras_details"></div>
						<span class="extras_field"></span>
					</div>
				</div>
				<div class="cost_wrapper">
					<span class="extras_cost"></span>
					<span class="extras_cost_int"></span>
					<span class="extras_count"></span>
					<div class="extras_range"></div>
				</div>
			</li>
		</ul>
		<input type="hidden" id="selected_extras"/>
		<div class="ar">
			<input class="btn btn-danger" type="button" value="<?php _e('Proceed to checkout', 'bizzthemes'); ?>" id="submit_car_extras"/>
		</div>
	</div>	
</div>