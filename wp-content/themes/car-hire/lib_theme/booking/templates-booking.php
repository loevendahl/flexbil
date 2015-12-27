<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* Custom templates: Vehicles */
/*------------------------------------------------------------------*/
// remove the sidebars
add_action( 'bizz_head_grid', 'bizz_remove_sidebars' );
function bizz_remove_sidebars( $grid ) {
		
	if ( is_page_template( 'template-vehicles.php' ) ) {
	
		bizz_replace_grids('main_area', array(
			'id' => 'main_area',
			'name' => __('Main Area', 'bizzthemes'),
			'container' => 'container',
			'before_container' => '',
			'after_container' => '',
			'before_container_tree' => '<div class="row">',
			'after_container_tree' => '</div>',
			'show' => 'true',
			'grids' => array(
				'main_one' => array(
					'name' => __('Content', 'bizzthemes'),
					'class' => 'col-md-12 alpha',
					'columns' => '12',
					'before_grid' => '',
					'after_grid' => '',
					'tree' => ''
				)
			)
		));
	
	}
	
}

// list all vehicles
class my_vehicles extends bizz_custom_loop {
	function page() {
		global $booking_settings;
		// get booking settings
		$opt_s  = $booking_settings->get_settings();
		?>
		<div class="headline_area">
			<h1 class="title"><?php the_title(); ?></h1>
		</div>
		<div class="wrapper">
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
						<option value="-1"><?php _e('Latest first', 'bizzthemes'); ?></option>
						<option value="price-low" <?php selected( $opt_s['vehicle_sort'], 'price-asc' ); ?>><?php _e('Price: low to high', 'bizzthemes'); ?></option>
						<option value="price-high" <?php selected( $opt_s['vehicle_sort'], 'price-desc' ); ?>><?php _e('Price: high to low', 'bizzthemes'); ?></option>
						<option value="name-asc" <?php selected( $opt_s['vehicle_sort'], 'name-asc' ); ?>><?php _e('Name: ascending', 'bizzthemes'); ?></option>
						<option value="name-desc" <?php selected( $opt_s['vehicle_sort'], 'name-desc' ); ?>><?php _e('Name: descending', 'bizzthemes'); ?></option>
					</select>
				</div>
			</div>
			<ul id="car_list" class="templatelist clist">
<?php
			// car posts
			$args = apply_filters( 'bizz_car_return', array( 
				'post_type' => 'bizz_cars', 
				'numberposts' => -1,
				'suppress_filters' => false
			) );
			$car_posts = get_posts( $args );
			// pricing posts
			$args = array( 
				'post_type' => 'bizz_pricing',
				'numberposts' => -1,
				'fields' => 'ids',
				'suppress_filters' => false
			);
			$pricing_posts = get_posts( $args );
			$car_options["cars"] = array();
			foreach ( $car_posts as $car_post ) {
				$custom = get_post_custom($car_post->ID);
				$car_img = ( isset($custom["bizzthemes_car_image"][0]) ) ? $custom["bizzthemes_car_image"][0] : get_template_directory_uri() . '/lib_theme/images/no-img.jpg';
				// from
				$price_from = '<span class="from">' . __('from', 'bizzthemes') . '</span>';
				// range
				$price_range = ( isset($opt_s['pay_pricerange']) && $opt_s['pay_pricerange'] == 'perhour'  ) ? __('per hour', 'bizzthemes') : __('per day', 'bizzthemes');
				$price_range = '<span class="range">' . $price_range . '</span>';
				// pricing
				$pricing = array();
				$car_pricing = get_post_meta( $car_post->ID, 'bizzthemes_car_pricing', true );
				foreach ($pricing_posts as $post_id) {
					$post_custom = get_post_custom( $post_id );
					$queried_post = get_post( $post_id );
					// skip other car types
					if ( $car_pricing && ! in_array( $queried_post->post_name, $car_pricing ) ) {
						continue;
					}
					else if ( ! $car_pricing && isset( $post_custom['bizzthemes_price_type'][0] ) && ( $custom["bizzthemes_car_type"][0] != $post_custom['bizzthemes_price_type'][0] ) ) {
						continue;
					}	
					$post_price = currencystr_to_float($post_custom['bizzthemes_price_daily'][0], $opt_s);
					$pricing[] = $post_price;		
				}
				if ( !empty($pricing) ) {
					// tax percentage
					$tax_percentage = ( isset($opt_s['pay_tax']) ) ? currencystr_to_float($opt_s['pay_tax'], $opt_s) / 100 : 1;
					$pricing = min($pricing); #take lowest
					$pricing = $pricing+($pricing * $tax_percentage);
					$price = $price_from . '<br/>' . float_to_currencystr($pricing, true, $opt_s) . '<br/>' . $price_range;
				}
				else {
					$pricing = '';
					$price = '';
				}
				
				// build car options array
				$car_options["cars"][] = array(
					'id' => $car_post->ID,
					'post_name' => $car_post->post_name,
					'name' => $car_post->post_title,
					'description' => do_shortcode($custom["bizzthemes_car_description"][0]),
					'edit' => get_edit_post_link( $car_post->ID ),
					'picture_src' => $car_img,
					'type' => $custom["bizzthemes_car_type"],
					'currency' => get_bizz_currency($opt_s['pay_currency']),
					'cost' => ($price=="") ? 'not-set' : $price,
					'cost_val' => ($pricing=="") ? 'not-set' : $pricing,
					'availability' => true,
					'equipment' => array(
						'seats' => $custom["bizzthemes_car_seats"][0],
						'doors' => $custom["bizzthemes_car_doors"][0],
						'transmission' => $custom["bizzthemes_car_transmission"][0]
					),
				);
			}
			
			// sort the same as on booking widget
			$car_options["cars"] = apply_filters( 'bizz_car_sort', bizz_list_sort( $car_options["cars"], 'availability' ), $car_options["cars"] );
			
			foreach ( $car_options["cars"] as $car_post ) {
			
?>
				<li class="clearfix" id="li_car_<?php echo $car_post['id']; ?>">
					<div class="data_wrapper">
						<div class="picture left">
							<img class="car_image img-polaroid" src="<?php echo $car_post['picture_src']; ?>" width="100" alt="" />
						</div>
						<div class="details clearfix">
							<h2 class="car_name"><?php echo $car_post['name']; ?></h2>
							<input type="hidden" class="car_id" value="<?php echo $car_post['id']; ?>" />
							<input type="hidden" class="car_type" value="<?php echo implode(",", $car_post["type"]); ?>" />
							<input type="hidden" class="car_transmission" value="<?php echo $car_post["equipment"]["transmission"]; ?>" />
							<input type="hidden" class="car_val" value="<?php echo $car_post['cost_val']; ?>" />
							<ul class="car_properties">
								<li class="seats"><span class="eq_value"><?php echo $car_post["equipment"]["seats"]; ?></span></li>
								<li class="doors"><span class="eq_value"><?php echo $car_post["equipment"]["doors"]; ?></span></li>
								<li class="transmission"><span class="eq_value"><?php echo $car_post["equipment"]["transmission"]; ?></span></li>
							</ul>
							<a href="#" class="toggled car_details"><?php _e('Details', 'bizzthemes'); ?></a>
							<?php if ( current_user_can( 'manage_options' ) ) { ?>
								<a href="<?php echo get_edit_post_link( $car_post['id'] ); ?>" class="car_edit"><?php _e('(Edit)', 'bizzthemes'); ?></a>
							<?php } ?>
							<div class="car_details_tooltip"><?php echo wpautop($car_post["description"]); ?></div>
						</div>
					</div>
					<div class="price_wrapper">
						<!-- Button to trigger modal -->
						<button data-target="#bookingmodal" role="button" class="btn btn-danger btn-bookingmodal" data-toggle="modal"><?php _e('Book Now', 'bizzthemes'); ?></button>
						<span class="car_price"><?php echo $car_post['cost']; ?></span>
					</div>
				</li>
			<?php } ?>
			</ul>
		</div>
<?php
	}
}
add_action( 'bizz_sidebar_grid_before', 'bizz_template_vehicles' );
function bizz_template_vehicles( $grid ) {
	if ( is_page_template( 'template-vehicles.php' ) ) {
		$my_custom_loop = new my_vehicles;
		add_action( 'wp_footer', 'bizz_modal_booking' );
	}
}

// Modal booking in footer
function bizz_modal_booking() {
?>
	<div id="bookingmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bookNow" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="bookNow"><?php _e('Book Now', 'bizzthemes'); ?></h4>
				</div>
				<div class="modal-body">
					<?php echo do_shortcode("[car_booking]"); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}