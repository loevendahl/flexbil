<?php
/*	
	
	WIDGET FILTERS:

    widget_title			- widget title
	widget_facebook	 		- facebook link
	widget_twitter	 		- twitter link
	widget_flickr	 		- flickr link
	widget_youtube	 		- youtube link
	widget_linkedin	 		- linkedin link
	widget_google	 		- google link
	widget_dribbble	 		- dribbble link
	widget_tumblr	 		- tumblr link
	
*/

/*---------------------------------------------------------------------------------*/
/* Address Widget */
/*---------------------------------------------------------------------------------*/
class Bizz_Social extends WP_Widget {

	function Bizz_Social() {
		$widget_ops = array('classname' => 'widget_social', 'description' => __('Add links to you social profiles','bizzthemes'));
		$this->WP_Widget('bizz_social', __('Social Links','bizzthemes'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$facebook = apply_filters( 'widget_facebook', $instance['facebook'], $instance );
		$twitter = apply_filters( 'widget_twitter', $instance['twitter'], $instance );
		$flickr = apply_filters( 'widget_flickr', $instance['flickr'], $instance );
		$youtube = apply_filters( 'widget_youtube', $instance['youtube'], $instance );
		$linkedin = apply_filters( 'widget_linkedin', $instance['linkedin'], $instance );
		$google = apply_filters( 'widget_google', $instance['google'], $instance );
		$dribbble = apply_filters( 'widget_dribbble', $instance['dribbble'], $instance );
		$tumblr = apply_filters( 'widget_google', $instance['tumblr'], $instance );
		

		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
		?>
		
			<div class="socialwidget">
			<?php if ( !empty( $facebook ) ) { echo '<a href="'.$facebook.'" class="ico-facebook sico" title="'.__('Facebook','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $twitter ) ) { echo '<a href="'.$twitter.'" class="ico-twitter sico" title="'.__('Twitter','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $flickr ) ) { echo '<a href="'.$flickr.'" class="ico-flickr sico" title="'.__('Flickr','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $youtube ) ) { echo '<a href="'.$youtube.'" class="ico-youtube sico" title="'.__('YouTube','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $linkedin ) ) { echo '<a href="'.$linkedin.'" class="ico-linkedin sico" title="'.__('LinkedIn','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $google ) ) { echo '<a href="'.$google.'" class="ico-google sico" title="'.__('Google+','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $dribbble ) ) { echo '<a href="'.$dribbble.'" class="ico-dribbble sico" title="'.__('Dribble','bizzthemes') . '"><span></span></a>'; } ?>
			<?php if ( !empty( $tumblr ) ) { echo '<a href="'.$tumblr.'" class="ico-tumblr sico" title="'.__('Tumblr','bizzthemes') . '"><span></span></a>'; } ?>
			<div class="clear"></div>
			</div>
			
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['flickr'] = strip_tags($new_instance['flickr']);
		$instance['youtube'] = strip_tags($new_instance['youtube']);
		$instance['linkedin'] = strip_tags($new_instance['linkedin']);
		$instance['google'] = strip_tags($new_instance['google']);
		$instance['dribbble'] = strip_tags($new_instance['dribbble']);
		$instance['tumblr'] = strip_tags($new_instance['tumblr']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '',
			'facebook' => '', 
			'twitter' => '',
			'flickr' => '',
			'youtube' => '', 
			'linkedin' => '',
			'google' => '',
			'dribbble' => '',
			'tumblr' => ''
		));
		$title = strip_tags($instance['title']);
		$facebook = format_to_edit($instance['facebook']);
		$twitter = format_to_edit($instance['twitter']);
		$flickr = format_to_edit($instance['flickr']);
		$youtube = format_to_edit($instance['youtube']);
		$linkedin = format_to_edit($instance['linkedin']);
		$google = format_to_edit($instance['google']);
		$dribbble = format_to_edit($instance['dribbble']);
		$tumblr = format_to_edit($instance['tumblr']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<hr class="div" />
		
		<p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" type="text" value="<?php echo esc_attr($flickr); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo esc_attr($youtube); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('google'); ?>"><?php _e('Google+:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('google'); ?>" name="<?php echo $this->get_field_name('google'); ?>" type="text" value="<?php echo esc_attr($google); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('dribbble'); ?>"><?php _e('Dribbble:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('dribbble'); ?>" name="<?php echo $this->get_field_name('dribbble'); ?>" type="text" value="<?php echo esc_attr($dribbble); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('tumblr'); ?>"><?php _e('Tumblr:', 'bizzthemes'); ?></label>
		<input class="mediumfat" id="<?php echo $this->get_field_id('tumblr'); ?>" name="<?php echo $this->get_field_name('tumblr'); ?>" type="text" value="<?php echo esc_attr($tumblr); ?>" /></p>
		
		<div style="clear:both;">&nbsp;</div>
<?php
	}
}


register_widget('Bizz_Social');
?>