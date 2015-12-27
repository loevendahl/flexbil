<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Navgation menu widget
class Bizz_Navmenu extends WP_Widget {

	function Bizz_Navmenu() {
		$widget_ops = array( 'classname' => 'widget_navmenu', 'description' => __( 'Control the output of your menus.', 'bizzthemes' ) );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => "widgets-reloaded-bizz-nav-menu" );
		$this->WP_Widget( "widgets-reloaded-bizz-nav-menu", __('Navigation Menu', 'bizzthemes'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		
		 // Before widget
		echo $before_widget;
		
		// Widget title
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
			
		if ( ! wp_nav_menu( array( 'menu' => isset( $instance['menu'] ) ? $instance['menu'] : '', 'fallback_cb' => false, 'echo' => false ) ) )
			return;
		
		// Output vertical
		if ( isset( $instance['menu_vertical'] ) && $instance['menu_vertical'] ) {
			$output = '<div class="well">'; #responsive
			$output .= wp_nav_menu( array( 
				'menu' => isset( $instance['menu'] ) ? $instance['menu'] : '',
				'fallback_cb' => 'wp_page_menu',
				'container' => false,
				'menu_class' => 'nav nav-pills nav-stacked',
				'echo' => false			
			));
			$output .= '</div>';
		}
		// Output horizontal
		else {
			$output = '<div class="navbar navbar-inverse">';
			$output .= '<div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">'; #responsive
			$output .= '<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>';
			$output .= '</button></div>';
			$output .= '<div class="nav-collapse collapse navbar-collapse">'; #responsive
			$output .= wp_nav_menu( array( 
				'menu' => isset( $instance['menu'] ) ? $instance['menu'] : '',
				'fallback_cb' => 'wp_page_menu',
				'container' => false,
				'menu_class' => 'nav navbar-nav',
				'echo' => false,
				'walker' => new bizz_menu_walker()
			));
			$output .= '</div>';
			$output .= '</div>';
		}
		
		// Echo
		echo apply_filters( 'bizz_navmenu_widget', $output );
		
		// After widget
		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$new_instance['menu_vertical'] = ( isset( $new_instance['menu_vertical'] ) ? 1 : 0 );
		
		return $new_instance;
	}

	function form( $instance ) {
		//Defaults
		$defaults = array(
			'title' => '',
			'menu' => '',
			'menu_vertical' => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu' ); ?>"><?php _e( 'Select a menu:', 'bizzthemes' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'menu' ); ?>" name="<?php echo $this->get_field_name( 'menu' ); ?>">
				<?php foreach ( wp_get_nav_menus() as $menu ) { ?>
					<option value="<?php echo $menu->term_id; ?>" <?php selected( $instance['menu'], $menu->term_id ); ?>><?php echo $menu->name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu_vertical' ); ?>"><?php _e( 'Vertical menu?', 'bizzthemes'); ?>&nbsp;</label>
			<input class="checkbox" type="checkbox" <?php checked( $instance['menu_vertical'], true ); ?> id="<?php echo $this->get_field_id( 'menu_vertical' ); ?>" name="<?php echo $this->get_field_name( 'menu_vertical' ); ?>" />
		</p>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Navmenu' );

// ADJUST wp MENU OUTPUT FOR BOOTSTRAP
/**
 * Class Name: bizz_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @since 8.0.0
 */
if ( ! class_exists( 'bizz_menu_walker' ) ) {
	class bizz_menu_walker extends Walker_Nav_Menu {

		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul role=\"menu\" class=\"dropdown-menu\">\n";
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			/**
			 * Dividers, Headers or Disabled
			 * =============================
			 * Determine whether the item is a Divider, Header, Disabled or regular
			 * menu item. To prevent errors we use the strcasecmp() function to so a
			 * comparison that is not case sensitive. The strcasecmp() function returns
			 * a 0 if the strings are equal.
			 */
			if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {
				$class_names = $value = '';
				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				if ( $args->has_children )
						$class_names .= ' dropdown';
				if ( in_array( 'current-menu-item', $classes ) )
						$class_names .= ' active';
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
				$output .= $indent . '<li' . $id . $value . $class_names .'>';
				$atts = array();
				$atts['title']  = ! empty( $item->title )        ? $item->title      : '';
				$atts['target'] = ! empty( $item->target )       ? $item->target     : '';
				$atts['rel']    = ! empty( $item->xfn )          ? $item->xfn        : '';
				$atts['href']               = ! empty( $item->url ) ? $item->url : '';
				// If item has_children add atts to a.
				if ( $args->has_children && $depth === 0 ) {
						$atts['data-toggle']        = 'dropdown';
						$atts['class']              = 'dropdown-toggle';
				}

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
						if ( ! empty( $value ) ) {
								$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
								$attributes .= ' ' . $attr . '="' . $value . '"';
						}
				}

				$item_output = $args->before;

				/*
				 * Glyphicons
				 * ===========
				 * Since the the menu item is NOT a Divider or Header we check the see
				 * if there is a value in the attr_title property. If the attr_title
				 * property is NOT null we apply it as the class name for the glyphicon.
				 */
				if ( ! empty( $item->attr_title ) )
						$item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
				else
						$item_output .= '<a'. $attributes .'>';

				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
        }

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element )
				return;
			$id_field = $this->db_fields['id'];
			# Display this element.
			if ( is_object( $args[0] ) )
			   $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {
				extract( $args );
				$fb_output = null;
				if ( $container ) {
					$fb_output = '<' . $container;
					if ( $container_id )
						$fb_output .= ' id="' . $container_id . '"';
					if ( $container_class )
						$fb_output .= ' class="' . $container_class . '"';
					$fb_output .= '>';
				}
				$fb_output .= '<ul';
				if ( $menu_id )
					$fb_output .= ' id="' . $menu_id . '"';
				if ( $menu_class )
					$fb_output .= ' class="' . $menu_class . '"';
				$fb_output .= '>';
				$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
				$fb_output .= '</ul>';
				if ( $container )
					$fb_output .= '</' . $container . '>';
				echo $fb_output;
			}	
		}
	}
}
