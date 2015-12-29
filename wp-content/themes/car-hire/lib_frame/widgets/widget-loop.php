<?php
/**
 * Loop Widget Class
 *
 * Loop widget displays your posts inside bizz loop.
 *
 */

// WIDGET CLASS
class Bizz_Widget_Loop extends WP_Widget {

	function Bizz_Widget_Loop() {
		$widget_ops = array( 'classname' => 'loop', 'description' => __( 'Default content widget displays your posts content for current page or archive.', 'bizzthemes' ) );
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-loop" );
		$this->WP_Widget( "widgets-reloaded-bizz-loop", __( 'Default Content', 'bizzthemes' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $widget_id;
		
		extract( $args, EXTR_SKIP );

		$args = array();

		// general args
		$args['post_author'] = isset( $instance['post_author'] ) ? $instance['post_author'] : false;
		$args['post_date'] = isset( $instance['post_date'] ) ? $instance['post_date'] : false;
		$args['post_comments'] = isset( $instance['post_comments'] ) ? $instance['post_comments'] : false;
		$args['post_categories'] = isset( $instance['post_categories'] ) ? $instance['post_categories'] : false;
		$args['post_tags'] = isset( $instance['post_tags'] ) ? $instance['post_tags'] : false;
		$args['post_edit'] = isset( $instance['post_edit'] ) ? $instance['post_edit'] : false;
		$args['post_meta'] = isset( $instance['post_meta'] ) ? $instance['post_meta'] : false;
		$args['thumb_display'] = isset( $instance['thumb_display'] ) ? $instance['thumb_display'] : false;
		$args['thumb_single'] = isset( $instance['thumb_single'] ) ? $instance['thumb_single'] : false;
		$args['thumb_selflink'] = isset( $instance['thumb_selflink'] ) ? $instance['thumb_selflink'] : false;
		$args['thumb_width'] = intval( $instance['thumb_width'] );
		$args['thumb_height'] = intval( $instance['thumb_height'] );
		$args['thumb_align'] = $instance['thumb_align'];
		$args['thumb_cropp'] = isset( $instance['thumb_cropp'] ) ? $instance['thumb_cropp'] : 'c';
		$args['thumb_filter'] = isset( $instance['thumb_filter'] ) ? $instance['thumb_filter'] : '';
		$args['thumb_sharpen'] = isset( $instance['thumb_sharpen'] ) ? $instance['thumb_sharpen'] : '';
		// archive args
		$args['post_columns'] = isset( $instance['post_columns'] ) ? $instance['post_columns'] : '0';
		$args['remove_posts'] = isset( $instance['remove_posts'] ) ? $instance['remove_posts'] : false;
		$args['full_posts'] = isset( $instance['full_posts'] ) ? $instance['full_posts'] : false;
		$args['read_more'] = isset( $instance['read_more'] ) ? $instance['read_more'] : false;
		$args['read_more_text'] = isset( $instance['read_more_text'] ) ? $instance['read_more_text'] : 'Read more';
		$args['enable_pagination'] = isset( $instance['enable_pagination'] ) ? $instance['enable_pagination'] : false;

		echo $before_widget;
			
		echo "<div id=\"content_area_" . preg_replace("/[^0-9\.]/", '', $widget_id) . "\" class=\"content_area clearfix\">\n";
		
		bizz_hook_before_content(); #hook
		$loop = new bizz_loop($args);
		bizz_hook_after_content(); #hook
		
		echo "</div>\n";
			
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;

		// general instances
		$instance['post_author'] = ( isset( $new_instance['post_author'] ) ? 1 : 0 );
		$instance['post_date'] = ( isset( $new_instance['post_date'] ) ? 1 : 0 );
		$instance['post_comments'] = ( isset( $new_instance['post_comments'] ) ? 1 : 0 );
		$instance['post_categories'] = ( isset( $new_instance['post_categories'] ) ? 1 : 0 );
		$instance['post_tags'] = ( isset( $new_instance['post_tags'] ) ? 1 : 0 );
		$instance['post_edit'] = ( isset( $new_instance['post_edit'] ) ? 1 : 0 );
		$instance['post_meta'] = ( isset( $new_instance['post_meta'] ) ? 1 : 0 );
		$instance['thumb_display'] = ( isset( $new_instance['thumb_display'] ) ? 1 : 0 );
		$instance['thumb_single'] = ( isset( $new_instance['thumb_single'] ) ? 1 : 0 );
		$instance['thumb_selflink'] = ( isset( $new_instance['thumb_selflink'] ) ? 1 : 0 );
		$instance['thumb_width'] = strip_tags( $new_instance['thumb_width'] );
		$instance['thumb_height'] = strip_tags( $new_instance['thumb_height'] );
		$instance['thumb_align'] = $new_instance['thumb_align'];
		$instance['thumb_cropp'] = $new_instance['thumb_cropp'];
		$instance['thumb_filter'] = $new_instance['thumb_filter'];
		$instance['thumb_sharpen'] = $new_instance['thumb_sharpen'];
		// archive instances
		$instance['post_columns'] = $new_instance['post_columns'];
		$instance['remove_posts'] = ( isset( $new_instance['remove_posts'] ) ? 1 : 0 );
		$instance['full_posts'] = ( isset( $new_instance['full_posts'] ) ? 1 : 0 );
		$instance['read_more'] = ( isset( $new_instance['read_more'] ) ? 1 : 0 );		
		$instance['read_more_text'] = strip_tags( $new_instance['read_more_text'] );
		$instance['enable_pagination'] = ( isset( $new_instance['enable_pagination'] ) ? 1 : 0 );

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array(
			'post_author' => false,
			'post_date' => true,
			'post_comments' => true,
			'post_categories' => false,
			'post_tags' => false,
			'post_edit' => false,
			'post_meta' => false,
			'thumb_display' => false,
			'thumb_single' => false,
			'thumb_selflink' => false,
			'thumb_width' => 150,
			'thumb_height' => 150,
			'thumb_align' => 'alignright',
			'thumb_cropp' => 'c',
			'thumb_filter' => '',
			'thumb_sharpen' => '',
			'post_columns' => 1,
			'remove_posts' => false,
			'full_posts' => false,
			'read_more' => true,
			'read_more_text' => 'Continue reading',
			'enable_pagination' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$number_posts = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");
		$thumb_align = array("alignleft" => "Left","aligncenter" => "Center","alignright" => "Right");
		$post_columns = array("1" => "One Column", "2" => "Two Columns", "3" => "Three Columns", "4" => "Four Columns");
		$thumb_cropp = array("c" => "Center", "t" => "Top", "tr" => "Top Right", "tl" => "Top Left", "b" => "Bottom", "bl" => "Bottom Left", "br" => "Bottom Right", "l" => "Left", "r" => "Right");
		?>

		<div class="bizz-widget-controls columns-2">
		<small class="section"><?php _e('Meta Settings', 'bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_author' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_author'], true ); ?> id="<?php echo $this->get_field_id( 'post_author' ); ?>" name="<?php echo $this->get_field_name( 'post_author' ); ?>" /> <?php _e('Post Author', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_date' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_date'], true ); ?> id="<?php echo $this->get_field_id( 'post_date' ); ?>" name="<?php echo $this->get_field_name( 'post_date' ); ?>" /> <?php _e('Post Date', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_comments' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_comments'], true ); ?> id="<?php echo $this->get_field_id( 'post_comments' ); ?>" name="<?php echo $this->get_field_name( 'post_comments' ); ?>" /> <?php _e('Post Comments', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_categories' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_categories'], true ); ?> id="<?php echo $this->get_field_id( 'post_categories' ); ?>" name="<?php echo $this->get_field_name( 'post_categories' ); ?>" /> <?php _e('Post Categories (posts only)', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_tags' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_tags'], true ); ?> id="<?php echo $this->get_field_id( 'post_tags' ); ?>" name="<?php echo $this->get_field_name( 'post_tags' ); ?>" /> <?php _e('Post Tags (posts only)', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_edit' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_edit'], true ); ?> id="<?php echo $this->get_field_id( 'post_edit' ); ?>" name="<?php echo $this->get_field_name( 'post_edit' ); ?>" /> <?php _e('Edit Post (visible to administrators)', 'bizzthemes'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_meta' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_meta'], true ); ?> id="<?php echo $this->get_field_id( 'post_meta' ); ?>" name="<?php echo $this->get_field_name( 'post_meta' ); ?>" /> <?php _e('Display post meta on pages', 'bizzthemes'); ?></label>
		</p>
		<small class="section"><?php _e('Archives Specific Options', 'bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_columns' ); ?>"><?php _e('How many Columns?', 'bizzthemes'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_columns' ); ?>" name="<?php echo $this->get_field_name( 'post_columns' ); ?>">
				<?php foreach ( $post_columns as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['post_columns'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'remove_posts' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['remove_posts'], true ); ?> id="<?php echo $this->get_field_id( 'remove_posts' ); ?>" name="<?php echo $this->get_field_name( 'remove_posts' ); ?>" /> <?php _e('Remove Post Content from Archives', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'full_posts' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['full_posts'], true ); ?> id="<?php echo $this->get_field_id( 'full_posts' ); ?>" name="<?php echo $this->get_field_name( 'full_posts' ); ?>" /> <?php _e('Display Full Posts', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'read_more' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['read_more'], true ); ?> id="<?php echo $this->get_field_id( 'read_more' ); ?>" name="<?php echo $this->get_field_name( 'read_more' ); ?>" /> <?php _e('Display <em>Read More...</em> text', 'bizzthemes'); ?></label>
		</p>
		<p>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'read_more_text' ); ?>" name="<?php echo $this->get_field_name( 'read_more_text' ); ?>" value="<?php echo $instance['read_more_text']; ?>" />
		</p>
		</div>
		<div class="bizz-widget-controls columns-2 column-last">
		<small class="section"><?php _e('Pagination', 'bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'enable_pagination' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['enable_pagination'], true ); ?> id="<?php echo $this->get_field_id( 'enable_pagination' ); ?>" name="<?php echo $this->get_field_name( 'enable_pagination' ); ?>" /> <?php _e('Enable pagination', 'bizzthemes'); ?></label><br/>
		</p>
		<small class="section"><?php _e('Featured Image', 'bizzthemes'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_display' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumb_display'], true ); ?> id="<?php echo $this->get_field_id( 'thumb_display' ); ?>" name="<?php echo $this->get_field_name( 'thumb_display' ); ?>" /> <?php _e('Display featured images?', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'thumb_single' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumb_single'], true ); ?> id="<?php echo $this->get_field_id( 'thumb_single' ); ?>" name="<?php echo $this->get_field_name( 'thumb_single' ); ?>" /> <?php _e('Display featured images on Single Posts', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'thumb_selflink' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumb_selflink'], true ); ?> id="<?php echo $this->get_field_id( 'thumb_selflink' ); ?>" name="<?php echo $this->get_field_name( 'thumb_selflink' ); ?>" /> <?php _e('Link featured images to themselves?', 'bizzthemes'); ?></label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Image Width', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" />
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'thumb_height' ); ?>"><?php _e('Image Height', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'thumb_height' ); ?>" name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" value="<?php echo $instance['thumb_height']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_align' ); ?>"><?php _e('Image Alignment', 'bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'thumb_align' ); ?>" name="<?php echo $this->get_field_name( 'thumb_align' ); ?>">
				<?php foreach ( $thumb_align as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['thumb_align'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_cropp' ); ?>"><?php _e('Image Crop Position', 'bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'thumb_cropp' ); ?>" name="<?php echo $this->get_field_name( 'thumb_cropp' ); ?>">
				<?php foreach ( $thumb_cropp as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['thumb_cropp'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		</div>
		<div class="clear">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Loop' );