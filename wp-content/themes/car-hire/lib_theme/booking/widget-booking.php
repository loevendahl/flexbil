<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Bizz_Booking extends WP_Widget {

	function Bizz_Booking() {
		$widget_ops = array('classname' => 'widget_booking', 'description' => __('Add registration form for setting appointments with customers.','bizzthemes'));
		$this->WP_Widget('bizz_booking', __('Book Online','bizzthemes'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$widget_title = ( !empty( $title ) ) ? $before_title . $title . $after_title : '';
		
		echo $before_widget;
		echo do_shortcode("[car_booking title='$widget_title']");
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'Rent a car'
		));
		$title = strip_tags($instance['title']);
		$intro = format_to_edit($instance['intro']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bizzthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

register_widget('Bizz_Booking');