<?php
/*	
	
	WIDGET FILTERS:

    widget_title			- widget title
	widget_small_meta	 	- small_meta link
	widget_email	 		- email link
	widget_large_meta	 	- large_meta link
	widget_phone	 		- phone link
	widget_linkedin	 		- linkedin link
	widget_google	 		- google link
	widget_dribbble	 		- dribbble link
	widget_tumblr	 		- tumblr link
	
*/

/*---------------------------------------------------------------------------------*/
/* Address Widget */
/*---------------------------------------------------------------------------------*/
class Bizz_Cinfo extends WP_Widget {

	function Bizz_Cinfo() {
		$widget_ops = array('classname' => 'widget_cinfo', 'description' => __('Share random contact info, like phone, email or Skype','bizzthemes'));
		$this->WP_Widget('bizz_cinfo', __('Contact Info','bizzthemes'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$small_meta = apply_filters( 'widget_small_meta', $instance['small_meta'], $instance );
		$small_link = apply_filters( 'widget_small_link', $instance['small_link'], $instance );
		$small = apply_filters( 'widget_email', $instance['small'], $instance );
		$large_meta = apply_filters( 'widget_large_meta', $instance['large_meta'], $instance );
		$large_link = apply_filters( 'widget_large_link', $instance['large_link'], $instance );
		$large = apply_filters( 'widget_phone', $instance['large'], $instance );		

		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
?>
			<div class="contact-info">
				<div class="cblock c1">
<?php 
					if ( !empty( $small_meta ) ) { echo '<span class="pmeta">'.$small_meta.'</span>'; }
					if ( !empty( $small ) ) {
						echo ( $small_link != '' ) ? '<a href="'.$small_link.'">' : '';
						echo '<span class="psmall">'.$small.'</span>';
						echo ( $small_link != '' ) ? '</a>' : '';
					} 
?>
				</div>
				<div class="cblock c2">
<?php 
					if ( !empty( $large_meta ) ) { echo '<span class="pmeta">'.$large_meta.'</span>'; }
					if ( !empty( $large ) ) {
						echo ( $large_link != '' ) ? '<a href="'.$large_link.'">' : '';
						echo '<span class="plarge">'.$large.'</span>';
						echo ( $large_link != '' ) ? '</a>' : '';
					} 
?>
				</div>
			</div>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['small_meta'] = strip_tags($new_instance['small_meta']);
		$instance['small_link'] = strip_tags($new_instance['small_link']);
		$instance['small'] = strip_tags($new_instance['small']);
		$instance['large_meta'] = strip_tags($new_instance['large_meta']);
		$instance['large_link'] = strip_tags($new_instance['large_link']);
		$instance['large'] = strip_tags($new_instance['large']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '',
			'small_meta' => '', 
			'small_link' => '', 
			'small' => '',
			'large_meta' => '',
			'large_link' => '', 
			'large' => ''
		));
		$title = strip_tags($instance['title']);
		$small_meta = format_to_edit($instance['small_meta']);
		$small_link = format_to_edit($instance['small_link']);
		$email = format_to_edit($instance['small']);
		$large_meta = format_to_edit($instance['large_meta']);
		$large_link = format_to_edit($instance['large_link']);
		$phone = format_to_edit($instance['large']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<hr class="div" />
		
		<p><label for="<?php echo $this->get_field_id('small_meta'); ?>"><?php _e('Small tag:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('small_meta'); ?>" name="<?php echo $this->get_field_name('small_meta'); ?>" type="text" value="<?php echo esc_attr($small_meta); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('small'); ?>"><?php _e('Small text:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('small'); ?>" name="<?php echo $this->get_field_name('small'); ?>" type="text" value="<?php echo esc_attr($email); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('small_link'); ?>"><?php _e('Small Link:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('small'); ?>" name="<?php echo $this->get_field_name('small_link'); ?>" type="text" value="<?php echo esc_attr($small_link); ?>" /></p>

		<hr class="div" />
		
		<p><label for="<?php echo $this->get_field_id('large_meta'); ?>"><?php _e('Large tag:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('large_meta'); ?>" name="<?php echo $this->get_field_name('large_meta'); ?>" type="text" value="<?php echo esc_attr($large_meta); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('large'); ?>"><?php _e('Large text:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('large'); ?>" name="<?php echo $this->get_field_name('large'); ?>" type="text" value="<?php echo esc_attr($phone); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('large_link'); ?>"><?php _e('Large Link:'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('large_link'); ?>" name="<?php echo $this->get_field_name('large_link'); ?>" type="text" value="<?php echo esc_attr($large_link); ?>" /></p>

		<div style="clear:both;">&nbsp;</div>
<?php
	}
}

register_widget('Bizz_Cinfo');