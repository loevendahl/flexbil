<?php
/**
 * Search Widget Class
 *
 * The Search widget replaces the default WordPress Search widget. This version gives total
 * control over the output to the user by allowing the input of various arguments that typically
 * represent a search form. It also gives the user the option of using the theme's search form
 * through the use of the get_search_form() function.
 *
 * @since 0.6
 * @link http://themehybrid.com/themes/hybrid/widgets
 *
 * @package Hybrid
 * @subpackage Classes
 */
 
// WIDGET CLASS 
class Bizz_Widget_Search extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Bizz_Widget_Search() {
		$widget_ops = array( 'classname' => 'search', 'description' => __('Control the output of your search form.', 'bizzthemes') );
		$this->WP_Widget( "widgets-reloaded-bizz-search", __('Search', 'bizzthemes'), $widget_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );

		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;


		global $search_form_num;

		$search_num = ( ( $search_form_num ) ? "-{$search_form_num}" : '' );
		$search_text = ( ( is_search() ) ? esc_attr( get_search_query() ) : esc_attr( apply_filters( 'widget_search_text', $instance['search_text'] ) ) );

		$search = '<form method="get" class="search" id="search-form' . $search_num . '" action="' . home_url() . '/"><div>';

		$search .= '<input type="text" class="field" name="s" id="search-text' . $search_num . '" value="' . esc_attr( $search_text ) . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';
		$search .= '<button class="main-submit search-sprite"><!----></button>';
		$search .= '<input type="hidden" class="submit" name="submit" />';

		$search .= '</div></form><!-- .search-form -->';

		echo $search;

		$search_form_num++;

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => '', 'search_text' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bizzthemes'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_text' ); ?>"><?php _e('Search Text:', 'bizzthemes'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'search_text' ); ?>" name="<?php echo $this->get_field_name( 'search_text' ); ?>" value="<?php echo $instance['search_text']; ?>" />
		</p>

		<div class="clear">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Search' );
