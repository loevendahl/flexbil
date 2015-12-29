<?php
/**
 * Query Posts Widget.
 * Adds a widget with numerous options using the query_posts() function.
 * In 0.2, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package QueryPosts
 */

/**
 * Output of the Query Posts widget.
 *
 * @since 0.2
 */

// WIDGET CLASS
class Bizz_Widget_Query_Posts extends WP_Widget {

	function Bizz_Widget_Query_Posts() {
		$widget_ops = array( 'classname' => 'posts', 'description' => __( 'Display all post types however you want. Create Blogs, News, FAQs etc.', 'bizzthemes' ) );
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => 'bizz-query-posts' );
		$this->WP_Widget( 'bizz-query-posts', __( 'Query Posts', 'bizz-query-posts' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $wp_version, $widget_id;
		
		extract( $args, EXTR_SKIP );

		/* Arguments for the query. */
		$args = array();
		
		// general args
		$args['post_author'] = isset( $instance['post_author'] ) ? $instance['post_author'] : false;
		$args['post_date'] = isset( $instance['post_date'] ) ? $instance['post_date'] : false;
		$args['post_comments'] = isset( $instance['post_comments'] ) ? $instance['post_comments'] : false;
		$args['post_categories'] = isset( $instance['post_categories'] ) ? $instance['post_categories'] : false;
		$args['post_tags'] = isset( $instance['post_tags'] ) ? $instance['post_tags'] : false;
		$args['post_edit'] = isset( $instance['post_edit'] ) ? $instance['post_edit'] : false;
		$args['thumb_display'] = isset( $instance['thumb_display'] ) ? $instance['thumb_display'] : false;
		$args['thumb_selflink'] = isset( $instance['thumb_selflink'] ) ? $instance['thumb_selflink'] : false;
		$args['thumb_width'] = intval( $instance['thumb_width'] );
		$args['thumb_height'] = intval( $instance['thumb_height'] );
		$args['thumb_align'] = $instance['thumb_align'];
		$args['thumb_cropp'] = $instance['thumb_cropp'];
		$args['thumb_filter'] = $instance['thumb_filter'];
		$args['thumb_sharpen'] = $instance['thumb_sharpen'];
		
		// archive args
		$args['post_columns'] = $instance['post_columns'];
		$args['remove_posts'] = isset( $instance['remove_posts'] ) ? $instance['remove_posts'] : false;
		$args['full_posts'] = isset( $instance['full_posts'] ) ? $instance['full_posts'] : false;
		$args['read_more'] = isset( $instance['read_more'] ) ? $instance['read_more'] : false;
		$args['read_more_text'] = apply_filters( 'widget_read_more_text', $instance['read_more_text'] );

		/* Widget title and things not in query arguments. */
		$show_entry_title = $instance['show_entry_title'] ? true : false;
		$wp_link_pages = $instance['wp_link_pages'] ? true : false;

		/* Sticky posts. */
		if (version_compare($wp_version, '3.0.9', '>='))
		    $args['ignore_sticky_posts'] = $instance['caller_get_posts'] ? '1' : '0';
		else
		    $args['caller_get_posts']    = $instance['caller_get_posts'] ? '1' : '0';

		/* Posts (by post type). */
		$post_types = get_post_types( array( 'publicly_queryable' => true ), 'names' );
		$post__in = array();
		foreach ( $post_types as $type ) {
			if ( isset( $instance[$type] ) && !empty( $instance[$type] ) ) {
				$post__in_new = explode( ',', $instance[$type] );
				$post__in = array_merge( $post__in, $post__in_new );
			}
		}
		if ( !empty($post__in) )
			$args['post__in'] = $post__in;

		/* Taxonomies. */
		$taxonomies = query_posts_get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			if ( 'category' == $taxonomy && !empty( $instance[$taxonomy] ) )
				$args['category__in'] = explode(",", $instance[$taxonomy]);
			elseif ( 'post_tag' == $taxonomy && !empty( $instance[$taxonomy] ) )
				$args['tag__in'] = explode(",", $instance[$taxonomy]);
			elseif ( !empty( $instance[$taxonomy] ) ) {
				$the_tax = get_taxonomy( $taxonomy );
				$the_term = get_terms( $the_tax->query_var, array( 'include' => $instance[$taxonomy], 'fields' => 'names' ) );
				$args[$the_tax->query_var] = implode(",", $the_term);
			}
		}
	
		/* Post type. */
		$post_type = (array) $instance['post_type'];
		if ( in_array( 'any', $post_type ) )
			$args['post_type'] = 'any';
		else
			$args['post_type'] = $instance['post_type'];
			
		/* Post mime type. */
		if ( !empty( $instance['post_mime_type'] ) )
			$args['post_mime_type'] = (array) $instance['post_mime_type'];

		/* Post status. */
		if ( $instance['post_status'] )
			$args['post_status'] = join( ', ', (array) $instance['post_status'] );

		/* Ordering and such. */
		if ( $instance['offset'] ){
			$args['offset'] = absint( $instance['offset'] );
			/* fix for offset with pagination */
			global $myoffset;
			$myoffset = $args['offset'];
		}
		if ( $instance['posts_per_page'] ){
			$args['posts_per_page'] = intval( $instance['posts_per_page'] );
			/* fix for offset with pagination */
			global $mypppage;
			$mypppage = $args['posts_per_page'];
		}
		if ( $instance['post_parent'] )
			$args['post_parent'] = absint( $instance['post_parent'] );

		/* Paged. */
		if ($instance['enable_pagination']){
		    $args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
			global $paged;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
		else {
		    $args['nopaging'] = true;
			$args['paged'] = 1;
			global $paged;
			$paged = 1;
		}

		/* Order and orderby. */
		$args['order'] = $instance['order'];
		$args['orderby'] = $instance['orderby'];

		/* Author arguments. */
		if ( $instance['author'] )
			$args['author'] = $instance['author'];

		/* Time arguments. */
		if ( $instance['hour'] )
			$args['hour'] = absint( $instance['hour'] );
		if ( $instance['minute'] )
			$args['minute'] = absint( $instance['minute'] );
		if ( $instance['second'] )
			$args['second'] = absint( $instance['second'] );
		if ( $instance['day'] )
			$args['day'] = absint( $instance['day'] );
		if ( $instance['monthnum'] )
			$args['monthnum'] = absint( $instance['monthnum'] );
		if ( $instance['year'] )
			$args['year'] = absint( $instance['year'] );
		if ( $instance['w'] )
			$args['w'] = absint( $instance['w'] );

		/* Meta arguments. */
		$args['meta_key'] = ( $instance['meta_key'] ) ? strip_tags( $instance['meta_key'] ) : '';
		$args['meta_value'] = ( $instance['meta_value'] ) ? $instance['meta_value'] : '';
		$args['meta_compare'] = ( $instance['meta_compare'] ) ? $instance['meta_compare'] : '';

		/* Open the output of the widget. */
		echo $before_widget;
				
		echo "<div id=\"content_area_" . preg_replace("/[^0-9\.]/", '', $widget_id) . "\" class=\"content_area clearfix\">\n";

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		if ( $instance['title'] ){
			echo "<div class=\"query_headline\">\n";
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
			echo "</div>\n";
		}

		/* Filter posts to allow offset for pagination */
		add_filter('post_limits', 'bizz_post_limit');
				
		/* Ignite the query */
		query_posts( apply_filters( 'query_posts_args',  $args ) );

		/* If posts were found, let's loop through them. */
		if ( have_posts() ) {
		    if (is_paged()) $is_paged = true;

			/* Open wrapper. */
			if ( 'ol' == $instance['entry_container'] || 'ul' == $instance['entry_container'] )
				echo "<{$instance['entry_container']}>";

			$post_count = 0;
			while ( have_posts() ) {
				
				the_post();
				$post_count++;

				/* Post container. */
				$e_o = ($post_count % $args['post_columns']) ? '' : ' last';
				$classes = 'post_box top bsize-' . $args['post_columns'] . $e_o;
				$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
				
				bizz_hook_before_post_box($args); #hook
				echo "\t\t\t<".apply_filters('bizz_html5_article', "div")." class=\"" . $post_classes . "\" id=\"post-" . get_the_ID() . "\">\n"; #wp
				bizz_hook_post_box_top($args); #hook
				if ( $instance['entry_title'] && $show_entry_title && apply_filters('bizz_show_headline_area', true) ){
				    echo "\t\t\t\t<".apply_filters('bizz_html5_header', "div")." class=\"headline_area\">\n";
				    if ($wp_link_pages){
					    the_title( "<{$instance['entry_title']} class='entry-title'><a href='" . get_permalink() . "' title='" . the_title_attribute( 'echo=0' ) . "' rel='bookmark'>", "</a></{$instance['entry_title']}>" );
					} else {
					    the_title( "<{$instance['entry_title']} class='entry-title'>", "</{$instance['entry_title']}>" );
					}
					echo "\t\t\t\t</".apply_filters('bizz_html5_header', "div").">\n";
				}
				bizz_hook_query_after_headline($args); #hook
				bizz_hook_query_content($args); #hook
				bizz_hook_post_box_bottom($args); #hook
				echo "\t\t\t</".apply_filters('bizz_html5_article', "div").">\n";
				bizz_hook_after_post_box($args); #hook
				if ($e_o != '') {
				    echo "\t\t\t\t<div class='single-sep fix'><!----></div>\n";
				}
			
			}
			
			/* Pagination */
			if (function_exists('bizz_wp_pagenavi') && $instance['enable_pagination']) {
				bizz_wp_pagenavi();
			}

			/* Close wrapper. */
			if ( 'ol' == $instance['entry_container'] || 'ul' == $instance['entry_container'] )
				echo "</{$instance['entry_container']}>";
		}

		/* If no posts were found and there is a custom error message. */
		elseif ( $instance['error_message'] ) {

			$classes = 'post_box top';
			$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
			bizz_hook_before_post_box($args); #hook
			echo "\t\t\t<".apply_filters('bizz_html5_article', "div")." class=\"" . $post_classes . "\" id=\"post-" . get_the_ID() . "\">\n"; #wp
			bizz_hook_post_box_top($args); #hook
			
				/* Output error message. */
				echo '<div class="entry-content"><p>' . do_shortcode( apply_filters( 'widget_error_message', $instance['error_message'] ) ) . '</p></div>';
				
			bizz_hook_post_box_bottom($args); #hook
			echo "\t\t\t</".apply_filters('bizz_html5_article', "div").">\n";
			bizz_hook_after_post_box($args); #hook

		}

		/* Reset query. */
		wp_reset_query();
			
		/* Remove pagination filter */
		remove_filter('post_limits', 'bizz_post_limit');
		
		echo "</div>\n";
		
		/* Close the output of the widget. */
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
		$instance['thumb_display'] = ( isset( $new_instance['thumb_display'] ) ? 1 : 0 );
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
		$instance['read_more_text'] = strip_tags( apply_filters( 'widget_read_more_text', $instance['read_more_text'] ) );

		/* Strip tags from elements that don't need them. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['offset'] = strip_tags( $new_instance['offset'] );
		$instance['enable_pagination'] = ( isset( $new_instance['enable_pagination'] ) ? 1 : 0 );
		$instance['posts_per_page'] = strip_tags( $new_instance['posts_per_page'] );
		$instance['post_parent'] = strip_tags( $new_instance['post_parent'] );
		$instance['p'] = strip_tags( $new_instance['p'] );
		$instance['year'] = strip_tags( $new_instance['year'] );

		/* Checkboxes. */
		$instance['caller_get_posts'] = ( isset( $new_instance['caller_get_posts'] ) ? 1 : 0 );
		$instance['show_entry_title'] = ( isset( $new_instance['show_entry_title'] ) ? 1 : 0 );
		$instance['wp_link_pages'] = ( isset( $new_instance['wp_link_pages'] ) ? 1 : 0 );

		/* If individual posts are widgets, disable the widget title. */
		if ( 'widget' == $new_instance['entry_container'] )
			$instance['enable_widget_title'] = false;
			
		return $instance;
	}
	
	function form( $instance ) {

		/* Set up the defaults. */
		$defaults = array(
			'title' => '',
			'display' => 'ul',
			'post_status' => array( 'publish' ),
			'post_type' => array( 'post' ),
			'post_mime_type' => array( '' ),
			'order' => 'DESC',
			'orderby' => 'date',
			'caller_get_posts' => true,
			'enable_pagination' => true,
			'posts_per_page' => get_option( 'posts_per_page' ),
			'offset' => '0',
			'author' => '',
			'wp_reset_query' => true,
			'meta_compare' => '',
			'meta_key' => '',
			'meta_value' => '',
			'year' => '',
			'monthnum' => '',
			'w' => '',
			'day' => '',
			'hour' => '',
			'minute' => '',
			'second' => '',
			'post_parent' => '',
			'entry_container' => 'div',
			'show_entry_title' => true,
			'entry_title' => 'h2',
			'wp_link_pages' => true,
			'error_message' => __( 'Apologies, but no results were found.', 'bizzthemes' ),
			'post_author' => false,
			'post_date' => true,
			'post_comments' => true,
			'post_categories' => false,
			'post_tags' => false,
			'post_edit' => false,
			'thumb_display' => false,
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
			'read_more_text' => __( 'Continue reading', 'bizzthemes' )
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$number_posts = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");
		$thumb_align = array("alignleft" => "Left","aligncenter" => "Center","alignright" => "Right");
		$post_columns = array("1" => "One Column", "2" => "Two Columns", "3" => "Three Columns", "4" => "Four Columns");
		$thumb_cropp = array("c" => "Center", "t" => "Top", "tr" => "Top Right", "tl" => "Top Left", "b" => "Bottom", "bl" => "Bottom Left", "br" => "Bottom Right", "l" => "Left", "r" => "Right");
?>

		<div class="bizz-widget-controls" style="float:left;width:23%;"><?php

		/* Widget title. */
		query_posts_input_text( 'Title:', $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );

		echo '<small class="section">Select Posts</small>';
		
		/* Post statuses. */
		$stati = get_post_stati( '', 'objects' );
		foreach ( $stati as $status )
			$statuses[$status->name] = $status->label;
		$statuses['inherit'] = __( 'Inherit', 'bizzthemes' );
		query_posts_select_multiple( 'Post status:', $this->get_field_id( 'post_status' ), $this->get_field_name( 'post_status' ), $instance['post_status'], $statuses, false );

		/* Post types. */
		$post_types = array( 'any' => __( 'Any', 'bizzthemes' ) );
		foreach ( get_post_types( array( 'publicly_queryable' => true ), 'objects' ) as $post_type ) {
			$type_label = ( ( isset($post_type->singular_label) && $post_type->singular_label ) ? $post_type->singular_label : $post_type->label );
			$post_types[$post_type->name] = $type_label;
		}
		query_posts_select_multiple( 'Post type:', $this->get_field_id( 'post_type' ), $this->get_field_name( 'post_type' ), $instance['post_type'], $post_types, false );

		/* Post mime type. */
		$post_mime_types = get_available_post_mime_types();
		foreach ( $post_mime_types as $post_mime_type )
			$mime_types[$post_mime_type] = $post_mime_type;
		$mime_types = ( isset($mime_types) ) ? $mime_types : '';
		query_posts_select_multiple( 'Post mime type:', $this->get_field_id( 'post_mime_type' ), $this->get_field_name( 'post_mime_type' ), $instance['post_mime_type'], $mime_types, false );
		
		/* Meta key. */
		foreach ( get_meta_keys() as $meta )
			$meta_keys[$meta] = $meta;
		query_posts_select_single( 'Meta key:', $this->get_field_id( 'meta_key' ), $this->get_field_name( 'meta_key' ), $instance['meta_key'], $meta_keys, true );

		/* Meta value. */
		query_posts_input_text( 'Meta value:', $this->get_field_id( 'meta_value' ), $this->get_field_name( 'meta_value' ), $instance['meta_value'] );

		/* Meta compare. */
		$operators = array( '=' => '=', '!=' => '!=', '>' => '>', '>=' => '>=', '<' => '<', '<=' => '<=' );
		query_posts_select_single( 'Meta compare:', $this->get_field_id( 'meta_compare' ), $this->get_field_name( 'meta_compare' ), $instance['meta_compare'], $operators, true );

		?></div>

		<div class="bizz-widget-controls" style="float:left;width:23%;margin-left:2%;"><?php
		
		/* Order. */
		query_posts_select_single( 'Order:', $this->get_field_id( 'order' ), $this->get_field_name( 'order' ), $instance['order'], array( 'ASC' => __( 'Ascending', 'bizzthemes' ), 'DESC' => __( 'Descending', 'bizzthemes' ) ), false );

		/* Order By. */
		$orderby_options = array( 'author' => __( 'Author', 'bizzthemes' ), 'comment_count' => __( 'Comment Count', 'bizzthemes' ), 'date' => __( 'Date', 'bizzthemes' ), 'ID' => __( 'ID', 'bizzthemes' ), 'menu_order' => __( 'Menu Order', 'bizzthemes' ), 'meta_value' => __( 'Meta Value', 'bizzthemes' ), 'modified' => __( 'Modified', 'bizzthemes' ), 'none' => __( 'None', 'bizzthemes' ), 'parent' => __( 'Parent', 'bizzthemes' ), 'rand' => __( 'Random', 'bizzthemes' ), 'title' => __( 'Title', 'bizzthemes' ) );
		query_posts_select_single( 'Order by:', $this->get_field_id( 'orderby' ), $this->get_field_name( 'orderby' ), $instance['orderby'], $orderby_options, false );
		
		echo '<small class="section">Pagination</small>';
		
		/* Enable pagination. */
		query_posts_input_checkbox( __( 'Enable pagination', 'bizzthemes' ), $this->get_field_id( 'enable_pagination' ), $this->get_field_name( 'enable_pagination' ), checked( $instance['enable_pagination'], true, false ) );
		
		/* Posts per page. */
		query_posts_input_text( 'Posts per page:', $this->get_field_id( 'posts_per_page' ), $this->get_field_name( 'posts_per_page' ), $instance['posts_per_page'] );

		/* Offset. */
		query_posts_input_text( 'Offset:', $this->get_field_id( 'offset' ), $this->get_field_name( 'offset' ), $instance['offset'] );
		
		echo '<small class="section">Date Filter</small>';
		
		/* Year. */
		query_posts_input_text_small( 'Years:', $this->get_field_id( 'year' ), $this->get_field_name( 'year' ), $instance['year'] );

		/* Months. */
		query_posts_select_single( 'Month:', $this->get_field_id( 'monthnum' ), $this->get_field_name( 'monthnum' ), $instance['monthnum'], range( 1, 12 ), true, 'smallfat', 'float:right;' );

		/* Weeks. */
		query_posts_select_single( 'Week:', $this->get_field_id( 'w' ), $this->get_field_name( 'w' ), $instance['w'], range( 1, 53 ), true, 'smallfat', 'float:right;' );

		/* Days. */
		query_posts_select_single( 'Day:', $this->get_field_id( 'day' ), $this->get_field_name( 'day' ), $instance['day'], range( 1, 31 ), true, 'smallfat', 'float:right;' );

		/* Hours. */
		query_posts_select_single( 'Hour:', $this->get_field_id( 'hour' ), $this->get_field_name( 'hour' ), $instance['hour'], range( 1, 23 ), true, 'smallfat', 'float:right;' );

		/* Minutes. */
		query_posts_select_single( 'Minute;', $this->get_field_id( 'minute' ), $this->get_field_name( 'minute' ), $instance['minute'], range( 1, 60 ), true, 'smallfat', 'float:right;' );

		/* Seconds. */
		query_posts_select_single( 'Second:', $this->get_field_id( 'second' ), $this->get_field_name( 'second' ), $instance['second'], range( 1, 60 ), true, 'smallfat', 'float:right;' );
		
		?></div>

		<div class="bizz-widget-controls" style="float:left;width:23%;margin-left:2%;"><?php
		
		echo '<small class="section">Post Container</small>';
		
		/* Stickies. */
		query_posts_input_checkbox( __( 'Disable sticky posts', 'bizzthemes' ), $this->get_field_id( 'caller_get_posts' ), $this->get_field_name( 'caller_get_posts' ), checked( $instance['caller_get_posts'], true, false ) );
		
		/* Post container. */
		$containers = array( 'widget' => 'widget', 'div' => 'div', 'ul' => 'ul', 'ol' => 'ol' );
		query_posts_select_single( 'Entry container', $this->get_field_id( 'entry_container' ), $this->get_field_name( 'entry_container' ), $instance['entry_container'], $containers, true );
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_columns' ); ?>">Columns?</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_columns' ); ?>" name="<?php echo $this->get_field_name( 'post_columns' ); ?>">
				<?php foreach ( $post_columns as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['post_columns'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
		
		/* Error message. */
		query_posts_textarea( 'Error message', $this->get_field_id( 'error_message' ), $this->get_field_name( 'error_message' ), $instance['error_message'] );
		
		echo '<small class="section">Post Title</small>';
		
		/* Entry title. */
		query_posts_input_checkbox( __( 'Enable entry titles', 'bizzthemes' ), $this->get_field_id( 'show_entry_title' ), $this->get_field_name( 'show_entry_title' ), checked( $instance['show_entry_title'], true, false ) );
		
		/* Page links wp_link_pages(). */
		query_posts_input_checkbox( __( 'Enable title links', 'bizzthemes' ), $this->get_field_id( 'wp_link_pages' ), $this->get_field_name( 'wp_link_pages' ), checked( $instance['wp_link_pages'], true, false ) );
		
		/* Entry title markup. */
		$elements = array( 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6', 'p' => 'p', 'div' => 'div', 'span' => 'span' );
		query_posts_select_single( 'Entry title', $this->get_field_id( 'entry_title' ), $this->get_field_name( 'entry_title' ), $instance['entry_title'], $elements, true );
		
		echo '<small class="section">Post Content</small>';
		
		/* Remove post content. */
		query_posts_input_checkbox( __( 'Remove post content', 'bizzthemes' ), $this->get_field_id( 'remove_posts' ), $this->get_field_name( 'remove_posts' ), checked( $instance['remove_posts'], true, false ) );
		
		/* Display full posts. */
		query_posts_input_checkbox( __( 'Display full content', 'bizzthemes' ), $this->get_field_id( 'full_posts' ), $this->get_field_name( 'full_posts' ), checked( $instance['full_posts'], true, false ) );

		/* Enable read more text. */
		query_posts_input_checkbox( __( 'Read More... text', 'bizzthemes' ), $this->get_field_id( 'read_more' ), $this->get_field_name( 'read_more' ), checked( $instance['read_more'], true, false ) );

		/* Read more text. */
		query_posts_input_text( '', $this->get_field_id( 'read_more_text' ), $this->get_field_name( 'read_more_text' ), $instance['read_more_text'] );
		
		?></div>

		<div class="bizz-widget-controls" style="float:left;width:23%;margin-left:2%;">
		<small class="section">Post Meta</small>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_author' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_author'], true ); ?> id="<?php echo $this->get_field_id( 'post_author' ); ?>" name="<?php echo $this->get_field_name( 'post_author' ); ?>" /> <?php _e( 'Post Author', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_date' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_date'], true ); ?> id="<?php echo $this->get_field_id( 'post_date' ); ?>" name="<?php echo $this->get_field_name( 'post_date' ); ?>" /> <?php _e( 'Post Date', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_comments' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_comments'], true ); ?> id="<?php echo $this->get_field_id( 'post_comments' ); ?>" name="<?php echo $this->get_field_name( 'post_comments' ); ?>" /> <?php _e( 'Post Comments', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_categories' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_categories'], true ); ?> id="<?php echo $this->get_field_id( 'post_categories' ); ?>" name="<?php echo $this->get_field_name( 'post_categories' ); ?>" /> <?php _e( 'Post Categories', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_tags' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_tags'], true ); ?> id="<?php echo $this->get_field_id( 'post_tags' ); ?>" name="<?php echo $this->get_field_name( 'post_tags' ); ?>" /> <?php _e( 'Post Tags', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'post_edit' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['post_edit'], true ); ?> id="<?php echo $this->get_field_id( 'post_edit' ); ?>" name="<?php echo $this->get_field_name( 'post_edit' ); ?>" /> <?php _e( 'Edit Post', 'bizzthemes'); ?></label>
		</p>
		<small class="section">Featured Image</small>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_display' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumb_display'], true ); ?> id="<?php echo $this->get_field_id( 'thumb_display' ); ?>" name="<?php echo $this->get_field_name( 'thumb_display' ); ?>" /> <?php _e( 'Featured images?', 'bizzthemes'); ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'thumb_selflink' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumb_selflink'], true ); ?> id="<?php echo $this->get_field_id( 'thumb_selflink' ); ?>" name="<?php echo $this->get_field_name( 'thumb_selflink' ); ?>" /> <?php _e( 'Link to images?', 'bizzthemes'); ?></label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>">Image Width</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" />
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'thumb_height' ); ?>">Image Height</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'thumb_height' ); ?>" name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" value="<?php echo $instance['thumb_height']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_align' ); ?>">Image Align</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumb_align' ); ?>" name="<?php echo $this->get_field_name( 'thumb_align' ); ?>">
				<?php foreach ( $thumb_align as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['thumb_align'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_cropp' ); ?>">Image Crop</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumb_cropp' ); ?>" name="<?php echo $this->get_field_name( 'thumb_cropp' ); ?>">
				<?php foreach ( $thumb_cropp as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['thumb_cropp'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_filter' ); ?>">Image Filter (from 1 to 13)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'thumb_filter' ); ?>" name="<?php echo $this->get_field_name( 'thumb_filter' ); ?>" value="<?php echo $instance['thumb_filter']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_sharpen' ); ?>">Image Sharpen (1 or 2)</label>
			<input type="text" class="widefat code" id="<?php echo $this->get_field_id( 'thumb_sharpen' ); ?>" name="<?php echo $this->get_field_name( 'thumb_sharpen' ); ?>" value="<?php echo $instance['thumb_sharpen']; ?>" />
		</p>
		
		</div>

		<div class="bizz-widget-controls" style="clear: left;"><?php
		
		echo '<small class="section">Filter by IDs</small><div style="clear: both;"></div>';
		
		/* Posts by post_type. */
		$post_types = get_post_types( '', 'names' );
		$i = 0;

		foreach ( $post_types as $type ) {
			echo '<div style="float:left;width:23%;margin-right:' . ( ++$i % 4 ? '2' : '0' ) . '%;">';
			$instance[$type] = ( isset($instance[$type]) ) ? $instance[$type] : '';
			query_posts_input_text( $type . ' (IDs):', $this->get_field_id( $type ), $this->get_field_name( $type ), $instance[$type] );
			echo '</div>';
		}

		/* Taxonomies. */
		$taxonomies = query_posts_get_taxonomies();
		$i = 0;
		foreach ( $taxonomies as $taxonomy ) {
			echo '<div style="float:left;width:23%;margin-right:' . ( ++$i % 4 ? '2' : '0' ) . '%;">';
			$instance[$taxonomy] = ( isset($instance[$taxonomy]) ) ? $instance[$taxonomy] : '';
			query_posts_input_text( $taxonomy . ' (IDs):', $this->get_field_id( $taxonomy ), $this->get_field_name( $taxonomy ), $instance[$taxonomy] );
			echo '</div>';
		}
		
		/* Parent. */
		echo '<div style="float:left;width:23%;margin-right:2%;">';
		query_posts_input_text( 'Parent (ID):', $this->get_field_id( 'post_parent' ), $this->get_field_name( 'post_parent' ), $instance['post_parent'] );
		echo '</div>';
		
		/* Authors. */
		echo '<div style="float:left;width:23%;margin-right:2%;">';
		query_posts_input_text( 'Authors (IDs):', $this->get_field_id( 'author' ), $this->get_field_name( 'author' ), $instance['author'] );
		echo '</div>';

		?>
		</div>
		<div class="clear">&nbsp;</div>
	<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Query_Posts' );

// WIDGET FUNCTIONS

/**
 * Make filter to allow offset with pagination
 */
function bizz_post_limit($limit) {
	global $paged, $myoffset, $mypppage;
	if (empty($paged)) {
		$paged = 1;
	}
	$postperpage = intval($mypppage);
	$pgstrt = ((intval($paged) -1) * $postperpage) + $myoffset . ', ';
	$limit = 'LIMIT '.$pgstrt.$postperpage;
	return $limit;
} //end function my_post_limit


/**
 * Check if specific shortcodes exist. If not, create them.
 *
 * @since 0.1
 */
function query_posts_shortcodes() {
	global $shortcode_tags;

	if ( !is_array( $shortcode_tags ) )
		return;

	if ( !array_key_exists( 'entry-author', $shortcode_tags ) )
		add_shortcode( 'entry-author', 'query_posts_entry_author_shortcode' );

	if ( !array_key_exists( 'entry-terms', $shortcode_tags ) )
		add_shortcode( 'entry-terms', 'query_posts_entry_terms_shortcode' );

	if ( !array_key_exists( 'entry-comments-link', $shortcode_tags ) )
		add_shortcode( 'entry-comments-link', 'query_posts_entry_comments_link_shortcode' );

	if ( !array_key_exists( 'entry-published', $shortcode_tags ) )
		add_shortcode( 'entry-published', 'query_posts_entry_published_shortcode' );

	if ( !array_key_exists( 'entry-edit-link', $shortcode_tags ) )
		add_shortcode( 'entry-edit-link', 'query_posts_entry_edit_link_shortcode' );
}

/**
 * Displays the edit link for an individual post.
 *
 * @since 0.3
 * @param array $attr
 */
function query_posts_entry_edit_link_shortcode( $attr ) {
	global $post;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( "edit_{$post_type->capability_type}", $post->ID ) )
		return '';

	$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );

	return $attr['before'] . '<span class="edit"><a class="post-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . sprintf( __( 'Edit %1$s', 'bizzthemes' ), $post->post_type ) . '">' . __( 'Edit', 'bizzthemes' ) . '</a></span>' . $attr['after'];
}

/**
 * Displays the published date of an individual post.
 *
 * @since 0.3
 * @param array $attr
 */
function query_posts_entry_published_shortcode( $attr ) {
	$attr = shortcode_atts( array( 'before' => '', 'after' => '', 'format' => get_option( 'date_format' ) ), $attr );

	$published = '<abbr class="published" title="' . sprintf( get_the_time( __( 'l, F jS, Y, g:i a', 'bizzthemes' ) ) ) . '">' . sprintf( get_the_time( $attr['format'] ) ) . '</abbr>';
	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays a post's number of comments wrapped in a link to the comments area.
 *
 * @since 0.3
 * @param array $attr
 */
function query_posts_entry_comments_link_shortcode( $attr ) {

	$number = get_comments_number();
	$attr = shortcode_atts( array( 'zero' => __( 'Leave a response', 'bizzthemes' ), 'one' => __( '1 Response', 'bizzthemes' ), 'more' => __( '%1$s Responses', 'bizzthemes' ), 'css_class' => 'comments-link', 'none' => '', 'before' => '', 'after' => '' ), $attr );

	if ( 0 == $number && !comments_open() && !pings_open() ) {
		if ( $attr['none'] )
			$comments_link = '<span class="' . esc_attr( $attr['css_class'] ) . '">' . $attr['none'] . '</span>';
	}
	elseif ( $number == 0 )
		$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_permalink() . '#respond" title="' . sprintf( __( 'Comment on %1$s', 'bizzthemes' ), the_title_attribute( 'echo=0' ) ) . '">' . $attr['zero'] . '</a>';
	elseif ( $number == 1 )
		$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', 'bizzthemes' ), the_title_attribute( 'echo=0' ) ) . '">' . $attr['one'] . '</a>';
	elseif ( $number > 1 )
		$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', 'bizzthemes' ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $attr['more'], $number ) . '</a>';

	if ( $comments_link )
		$comments_link = $attr['before'] . $comments_link . $attr['after'];

	return $comments_link;
}

/**
 * Displays an individual post's author with a link to his or her archive.
 *
 * @since 0.3
 * @param array $attr
 */
function query_posts_entry_author_shortcode( $attr ) {
	$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );
	$author = '<span class="author vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . get_the_author_meta( 'display_name' ) . '">' . get_the_author_meta( 'display_name' ) . '</a></span>';
	return $attr['before'] . $author . $attr['after'];
}

/**
 * Displays a list of terms for a specific taxonomy.
 *
 * @since 0.3
 * @param array $attr
 */
function query_posts_entry_terms_shortcode( $attr ) {
	global $post;

	$attr = shortcode_atts( array( 'id' => $post->ID, 'taxonomy' => 'post_tag', 'separator' => ', ', 'before' => '', 'after' => '' ), $attr );

	$attr['before'] = '<span class="' . $attr['taxonomy'] . '">' . $attr['before'];
	$attr['after'] .= '</span>';

	return get_the_term_list( $attr['id'], $attr['taxonomy'], $attr['before'], $attr['separator'], $attr['after'] );
}

/**
 * Returns taxonomies that have $query_var set for the various post types of the current
 * WordPress installation.
 *
 * @since 0.3
 * @return array $out Array of available taxonomy names.
 */
function query_posts_get_taxonomies() {

	$post_types = get_post_types( array( 'public' => true ), 'names' );
	$post_type_taxonomies = array();
	$all_taxonomies = array();

	foreach ( $post_types as $post_type ) {
		$post_type_taxonomies = get_object_taxonomies( $post_type );
		if ( is_array( $post_type_taxonomies ) ) {
			foreach ( $post_type_taxonomies as $taxonomy ) {
				$tax = get_taxonomy( $taxonomy );
				if ( $tax->query_var || 'category' == $taxonomy || 'post_tag' == $taxonomy )
					$all_taxonomies[] = $taxonomy;
			}
		}
	}

	$out = array_unique( $all_taxonomies );

	return $out;
}

/**
 * Creates a form label with the given parameters for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 */
function query_posts_label( $label, $id ) {
	echo "<label for='{$id}'>{$label}</label>";
}

/**
 * Creates a form checkbox for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param bool $checked
 */
function query_posts_input_checkbox( $label, $id, $name, $checked ) {
	echo "\n\t\t\t<p>";
	echo "<label for='{$id}' style='font-size:9px;'>";
	echo "<input type='checkbox' id='{$id}' name='{$name}' {$checked} /> ";
	echo "{$label}</label>";
	echo '</p>';
}

/**
 * Creates a textarea for use with the widget
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param string $value
 */
function query_posts_textarea( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	query_posts_label( $label, $id );
	echo "<textarea id='{$id}' name='{$name}' rows='4' cols='10' class='widefat' style='width:100%;height:8em;'>" . esc_attr( $value ) . "</textarea>";
	echo '</p>';
}

/**
 * Creates a form text input for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param string $value
 */
function query_posts_input_text( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	query_posts_label( $label, $id );
	echo "<input type='text' id='{$id}' name='{$name}' value='" . esc_attr( $value ) . "' class='widefat' />";
	echo '</p>';
}

/**
 * Creates a small text input for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param string $value
 */
function query_posts_input_text_small( $label, $id, $name, $value ) {
	echo "\n\t\t\t<p>";
	query_posts_label( $label, $id );
	echo "<input type='text' id='{$id}' name='{$name}' value='" . esc_attr( $value ) . "' size='6' style='float: right; width: 50px;' class='code' />";
	echo '</p>';
}

/**
 * Creates a multiple slect box for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param string $value
 * @param array $options
 * @param book $blank_option
 */
function query_posts_select_multiple( $label, $id, $name, $value, $options, $blank_option ) {

	$value = (array) $value;

	if ( $blank_option && is_array( $options ) )
		$options = array_merge( array( '' ), $options );

	echo "\n\t\t\t<p>";
	query_posts_label( $label, $id );
	echo "<select id='{$id}' name='{$name}[]' multiple='multiple' size='4' style='width:100%;height:5.0em;'>";
	if ( isset($options) && $options != '' ) {
	foreach ( $options as $option_value => $option_label )
		echo "<option value='" . ( ( $option_value ) ? $option_value : $option_label ) . "'" . ( ( in_array( $option_value, $value ) || in_array( $option_label, $value ) ) ? " selected='selected'" : '' ) . ">{$option_label}</option>";
	}
	echo '</select>';
	echo '</p>';
}

/**
 * Creates a single slect box for use with the widget.
 *
 * @since 0.3
 * @param string $label
 * @param string|int $id
 * @param string $name
 * @param string $value
 * @param array $options
 * @param book $blank_option
 * @param string $class Optional.
 * @param string $style Optional.
 */
function query_posts_select_single( $label, $id, $name, $value, $options, $blank_option, $class = '', $style = '' ) {

	$style = ( ( $style ) ? $style . ' min-width: 50px;' : 'width:100%;' );
	$class = ( ( $class ) ? $class : 'widefat;' );

	if ( $blank_option )
		$options = array_merge( array( '' ), $options );

	echo "\n\t\t\t<p>";
	query_posts_label( $label, $id );
	echo "<select id='{$id}' name='{$name}' class='{$class}' style='{$style}'>";

	foreach ( $options as $option_value => $option_label ) {
		$option_value = (string) $option_value;
		$option_label = (string) $option_label;
		echo "<option value='" . ( ( $option_value ) ? $option_value : $option_label ) . "'" . ( ( $value == $option_value || $value == $option_label ) ? " selected='selected'" : '' ) . ">{$option_label}</option>";
	}

	echo '</select>';
	echo '</p>';
}
