<?php
/*	
	
	WIDGET FILTERS:

    widget_title			- widget title
	adspace_adcode			- ad code for the widget
	
*/

/*---------------------------------------------------------------------------------*/
/* AdSpace */
/*---------------------------------------------------------------------------------*/

class Bizz_AdSpace extends WP_Widget {

	function Bizz_AdSpace() {
		$widget_ops = array( 'classname' => 'widget_adspace', 'description' => __('Add advertising code or banners','bizzthemes') );
		parent::WP_Widget(false, __( 'Ad Space', 'bizzthemes' ),$widget_ops);      
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$adcode = apply_filters( 'adspace_adcode', empty($instance['adcode']) ? '' : $instance['adcode'], $instance, $this->id_base);
		$adalign = ( isset($instance['adalign']) ) ? '<div class="'.$instance['adalign'].'">' : '<div class="alignleft">';
		$image = $instance['image'];
		$href = $instance['href'];
		$alt = $instance['alt'];

        /* Open the output of the widget. */
		echo $before_widget . $adalign;
		
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

		if( $adcode != '' )
			echo $adcode;
		elseif( $image != '' ) { 
			echo ( $href != '' ) ? '<a href="'.$href.'">' : '';
			echo '<img src="'.$image.'" alt="'.$alt.'" />';
			echo ( $href != '' ) ? '</a>' : '';
		}
		
		/* Close the output of the widget. */
		echo $after_widget . '</div>';

	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		//Defaults
		$defaults = array(
			'title' => '',
			'adalign' => 'left',
			'adcode' => '',
			'image' => '',
			'href' => '',
			'alt' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$adalign = array("alignleft" => "Left","aligncenter" => "Center","alignright" => "Right");
?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'bizzthemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'adalign' ); ?>"><?php _e( 'Ad Alignment:', 'bizzthemes' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'adalign' ); ?>" name="<?php echo $this->get_field_name( 'adalign' ); ?>">
				<?php foreach ( $adalign as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['adalign'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'adcode' ); ?>"><?php _e( 'Ad Code:', 'bizzthemes' ); ?></label>
            <textarea name="<?php echo $this->get_field_name( 'adcode' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'adcode' ); ?>"><?php echo $instance['adcode']; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'or Upload banner image:', 'bizzthemes' ); ?></label>
			<div class="wid_upload_button" id="<?php echo $this->get_field_id('image'); ?>">Choose File</div>
			<input type="text" class="widefat wid_upload_input" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $instance['image']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e( 'Target URL:', 'bizzthemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php echo $instance['href']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'alt' ); ?>"><?php _e( 'Alt text:', 'bizzthemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'alt' ); ?>" value="<?php echo $instance['alt']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'alt' ); ?>" />
        </p>
		<div style="clear:both;">&nbsp;</div>
<?php
	}
} 

register_widget( 'Bizz_AdSpace' );