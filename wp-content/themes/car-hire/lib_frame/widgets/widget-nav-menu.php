<?php
/**
 * Nav Menu Widget Class
 *
 * The nav menu widget was created to give users the ability to show nav menus created from the 
 * Menus screen, by the theme, or by plugins using the wp_nav_menu() function.  It replaces the default
 * WordPress navigation menu class.
 *
 * @since 0.8
 * @link http://themebizz.com/themes/bizz/widgets
 *
 * @package Bizz
 * @subpackage Classes
 */

// WIDGET CLASS
class Bizz_Widget_Nav_Menu extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Nav_Menu() {
		$widget_ops = array( 'classname' => 'nav-menu', 'description' => __('Control the output of your menus.', 'bizzthemes') );
		$control_ops = array( 'width' => 525, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-nav-menu" );
		$this->WP_Widget( "widgets-reloaded-bizz-nav-menu", __('Navigation Menu', 'bizzthemes'), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );

		$args = array();

		$args['menu'] = isset( $instance['menu'] ) ? $instance['menu'] : '';
		$args['container'] = $instance['container'];
		$args['container_id'] = $instance['container_id'];
		$args['container_class'] = $instance['container_class'];
		$args['menu_id'] = $instance['menu_id'];
		$args['menu_class'] = $instance['menu_class'] . ' clearfix';
		$args['link_before'] = $instance['link_before'];
		$args['link_after'] = $instance['link_after'];
		$args['before'] = $instance['before'];
		$args['after'] = $instance['after'];
		$args['depth'] = intval( $instance['depth'] );
		$args['fallback_cb'] = $instance['fallback_cb'];
		$args['walker'] = $instance['walker'];
		$args['use_desc_for_title'] = isset( $instance['use_desc_for_title'] ) ? $instance['use_desc_for_title'] : false;
		$args['vertical'] = isset( $instance['vertical'] ) ? $instance['vertical'] : false;
		$args['echo'] = false;

		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
			
		if ( $instance['vertical'] ){
			$args['container_class'] = $instance['container_class'];
			$args['container_class'] .= ' vertical';
		}
			
		if ( $instance['use_desc_for_title'] ){
		    $args['walker'] = new description_walker();
			echo str_replace( array( "\r", "\n", "\t" ), '', wp_nav_menu( $args ) );
		}
		else {
		    echo str_replace( array( "\r", "\n", "\t" ), '', wp_nav_menu( $args ) );
		}
		
		echo '<div class="fix"><!----></div>';

		echo $after_widget;
		
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
		$instance['container_id'] = strip_tags( $new_instance['container_id'] );
		$instance['container_class'] = strip_tags( $new_instance['container_class'] );
		$instance['menu_id'] = strip_tags( $new_instance['menu_id'] );
		$instance['menu_class'] = strip_tags( $new_instance['menu_class'] );
		$instance['fallback_cb'] = strip_tags( $new_instance['fallback_cb'] );
		$instance['walker'] = strip_tags( $new_instance['walker'] );
		$instance['use_desc_for_title'] = ( isset( $new_instance['use_desc_for_title'] ) ? 1 : 0 );
		$instance['vertical'] = ( isset( $new_instance['vertical'] ) ? 1 : 0 );

		return $instance;
	}
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array(
			'title' => '',
			'menu' => '',
			'container_id' => '',
			'container_class' => '',
			'menu_id' => '',
			'menu_class' => '',
			'walker' => '',
			'container' => 'div',
			'format' => 'div',
			'menu_class' => 'nav-menu',
			'depth' => 0,
			'before' => '',
			'after' => '',
			'link_before' => '',
			'link_after' => '',
			'fallback_cb' => 'wp_page_menu',
			'use_desc_for_title' => false,
			'vertical' => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$container = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
		?>

		<div class="bizz-widget-controls columns-2">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bizzthemes'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu' ); ?>"><?php _e('Select a menu', 'bizzthemes'); ?> <a href="<?php echo esc_url('nav-menus.php'); ?>"><?php _e( 'edit menus', 'bizzthemes' ); ?></a></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'menu' ); ?>" name="<?php echo $this->get_field_name( 'menu' ); ?>">
				<?php foreach ( wp_get_nav_menus() as $menu ) { ?>
					<option value="<?php echo $menu->term_id; ?>" <?php selected( $instance['menu'], $menu->term_id ); ?>><?php echo $menu->name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'container' ); ?>"><?php _e('Container', 'bizzthemes'); ?></label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'container' ); ?>" name="<?php echo $this->get_field_name( 'container' ); ?>">
				<?php foreach ( $container as $option ) { ?>
					<option value="<?php echo $option; ?>" <?php selected( $instance['container'], $option ); ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'container_id' ); ?>"><?php _e('Container ID', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'container_id' ); ?>" name="<?php echo $this->get_field_name( 'container_id' ); ?>" value="<?php echo $instance['container_id']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'container_class' ); ?>"><?php _e('Container class', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'container_class' ); ?>" name="<?php echo $this->get_field_name( 'container_class' ); ?>" value="<?php echo $instance['container_class']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu_id' ); ?>"><?php _e('Menu ID', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'menu_id' ); ?>" name="<?php echo $this->get_field_name( 'menu_id' ); ?>" value="<?php echo $instance['menu_id']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu_class' ); ?>"><?php _e('Menu class', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'menu_class' ); ?>" name="<?php echo $this->get_field_name( 'menu_class' ); ?>" value="<?php echo $instance['menu_class']; ?>" />
		</p>
		</div>

		<div class="bizz-widget-controls columns-2 column-last">
		<p>
			<label for="<?php echo $this->get_field_id( 'depth' ); ?>"><?php _e('Menu depth', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php echo $this->get_field_name( 'depth' ); ?>" value="<?php echo $instance['depth']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'before' ); ?>"><?php _e('Before menu', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" value="<?php echo $instance['before']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'after' ); ?>"><?php _e('After menu', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" value="<?php echo $instance['after']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_before' ); ?>"><?php _e('Before link', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'link_before' ); ?>" name="<?php echo $this->get_field_name( 'link_before' ); ?>" value="<?php echo $instance['link_before']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_after' ); ?>"><?php _e('After lin', 'bizzthemes'); ?>k</label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'link_after' ); ?>" name="<?php echo $this->get_field_name( 'link_after' ); ?>" value="<?php echo $instance['link_after']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fallback_cb' ); ?>"><?php _e('Fallback CB', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'fallback_cb' ); ?>" name="<?php echo $this->get_field_name( 'fallback_cb' ); ?>" value="<?php echo $instance['fallback_cb']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'walker' ); ?>"><?php _e('Walker', 'bizzthemes'); ?></label>
			<input type="text" class="smallfat code" id="<?php echo $this->get_field_id( 'walker' ); ?>" name="<?php echo $this->get_field_name( 'walker' ); ?>" value="<?php echo $instance['walker']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['use_desc_for_title'], true ); ?> id="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>" name="<?php echo $this->get_field_name( 'use_desc_for_title' ); ?>" /> <?php _e( 'Display descriptions?', 'bizzthemes' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vertical' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['vertical'], true ); ?> id="<?php echo $this->get_field_id( 'vertical' ); ?>" name="<?php echo $this->get_field_name( 'vertical' ); ?>" /> <?php _e( 'Display vertically?', 'bizzthemes' ); ?></label>
		</p>
		</div>
		<div class="clear">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Nav_Menu' );