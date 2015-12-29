<?php
/**
 * Comments Loop Widget
 *
 * @since 0.1
 */

// WIDGET CLASS
class Bizz_Widget_Comments_Loop extends WP_Widget {
	
	function Bizz_Widget_Comments_Loop() {
		$widget_ops = array( 'classname' => 'bizz-comments-loop', 'description' => __( 'Display Comments', 'bizzthemes' ) );
		$control_ops = array( 'width' => 500, 'height' => 350, 'id_base' => 'bizz-comments-loop' );
		$this->WP_Widget( 'bizz-comments-loop', __('Comments', 'bizzthemes'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		global $wpdb, $wp_query, $post, $user_ID, $comment, $overridden_bizzpage;
		
		$form_args = array_merge( $args, $instance );
    
		// If the comments have been modified by a third-party plugin (there is a filter hook on comments_template) then call the WordPress default comments
		if ( has_filter('comments_template') && !class_exists('Woocommerce') ):  

			// call the WordPress default comment template
			comments_template();
			
		// show the bizz comments
		else:
		  
			// Will not display the comments template if not on single post or page.
			if ( !is_singular() || empty($post) )
				return false;
				
			// Will not display the comments loop if the post does not have comments.
			if ( '0' != $post->comment_count ) {

				// Comment author information fetched from the comment cookies.
				$commenter = wp_get_current_commenter();

				// The name of the current comment author escaped for use in attributes.
				$comment_author = $commenter['comment_author']; // Escaped by sanitize_comment_cookies()

				// The email address of the current comment author escaped for use in attributes.
				$comment_author_email = $commenter['comment_author_email'];  // Escaped by sanitize_comment_cookies()

				// The url of the current comment author escaped for use in attributes.
				$comment_author_url = esc_url($commenter['comment_author_url']);

				// allow widget to override $post->ID
				$post_id = $post->ID;	

				// Grabs the comments for the $post->ID from the db.
				if ( $user_ID )
					$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND (comment_approved = '1' OR ( user_id = %d AND comment_approved = '0' ) )  ORDER BY comment_date_gmt", $post_id, $user_ID));
				else if ( empty($comment_author) )
					$comments = get_comments( array('post_id' => $post_id, 'status' => 'approve', 'order' => 'ASC') );
				else
					$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND ( comment_approved = '1' OR ( comment_author = %s AND comment_author_email = %s AND comment_approved = '0' ) ) ORDER BY comment_date_gmt", $post_id, wp_specialchars_decode($comment_author,ENT_QUOTES), $comment_author_email));

				// Adds the comments retrieved from the db into the main $wp_query
				$wp_query->comments = apply_filters( 'comments_array', $comments, $post_id );

				// keep $comments for legacy's sake
				$comments = &$wp_query->comments;

				// Set the comment count
				$wp_query->comment_count = count($wp_query->comments);

				// Update the cache
				update_comment_cache( $wp_query->comments );

				// Paged comments
				$overridden_bizzpage = FALSE;
				if ( '' == get_query_var('bizzpage') && get_option('page_comments') ) {
					set_query_var( 'bizzpage', 'newest' == get_option('default_comments_page') ? get_comment_pages_count() : 1 );
					$overridden_bizzpage = TRUE;
				}

				/**//**/// All the preliminary work is complete. Let's get down to business...
				$wp_list_comments_args = array();
				$wp_list_comments_args['type'] = $instance['type'];
				$wp_list_comments_args['reply_text'] = (string) apply_filters( 'widget_reply_text', $instance['reply_text'], $instance );
				$wp_list_comments_args['login_text'] = (string) apply_filters( 'widget_login_text', $instance['login_text'], $instance );
				$wp_list_comments_args['max_depth'] = (int) $instance['max_depth'];
				$wp_list_comments_args['enable_reply'] = $instance['enable_reply'];
				$wp_list_comments_args['comment_meta'] = $instance['comment_meta'];
				$wp_list_comments_args['comment_moderation'] = apply_filters( 'widget_comment_moderation', $instance['comment_moderation'], $instance );
				$wp_list_comments_args['callback'] = 'bizz_comments_loop_callback';
				$wp_list_comments_args['password_text'] = apply_filters( 'widget_password_text', $instance['password_text'], $instance );
				$wp_list_comments_args['pass_protected_text'] = apply_filters( 'widget_password_text', $instance['password_text'], $instance );
				$wp_list_comments_args['sing_comment_text'] = apply_filters( 'widget_sing_comment_text', $instance['sing_comment_text'], $instance );
				$wp_list_comments_args['plu_comment_text'] = apply_filters( 'widget_plu_comment_text', $instance['plu_comment_text'], $instance );
				$wp_list_comments_args['sing_trackback_text'] = apply_filters( 'widget_sing_trackback_text', $instance['sing_trackback_text'], $instance );
				$wp_list_comments_args['plu_trackback_text'] = apply_filters( 'widget_plu_trackback_text', $instance['plu_trackback_text'], $instance );
				$wp_list_comments_args['sing_pingback_text'] = apply_filters( 'widget_sing_pingback_text', $instance['sing_pingback_text'], $instance );
				$wp_list_comments_args['plu_pingback_text'] = apply_filters( 'widget_plu_pingback_text', $instance['plu_pingback_text'], $instance );
				$wp_list_comments_args['sing_ping_text'] = apply_filters( 'widget_sing_ping_text', $instance['sing_ping_text'], $instance );
				$wp_list_comments_args['plu_ping_text'] = apply_filters( 'widget_plu_ping_text', $instance['plu_ping_text'], $instance );
				$wp_list_comments_args['no_text'] = apply_filters( 'widget_no_text', $instance['no_text'], $instance );
				$wp_list_comments_args['to_text'] = apply_filters( 'widget_to_text', $instance['to_text'], $instance );
				$paginate_comments_links = paginate_comments_links( array('echo' => false) );
				$wp_list_comments_args['reverse_top_level'] = $instance['reverse_top_level'];
				$comment_type = 'all' == $instance['type'] ? 'comment' : $instance['type'];
				$type_plural = 'pings' == $comment_type ? $comment_type : "{$comment_type}s";
				$type_singular = 'pings' == $comment_type ? 'ping' : $comment_type;

				($type_plural=='comments') ? $type_plural=$wp_list_comments_args['plu_comment_text'] : $type_plural=$type_plural;
				($type_plural=='trackbacks') ? $type_plural=$wp_list_comments_args['plu_trackback_text'] : $type_plural=$type_plural;
				($type_plural=='pingbacks') ? $type_plural=$wp_list_comments_args['plu_pingback_text'] : $type_plural=$type_plural;
				($type_plural=='pings') ? $type_plural=$wp_list_comments_args['plu_ping_text'] : $type_plural=$type_plural;

				($type_singular=='comment') ? $type_singular=$wp_list_comments_args['sing_comment_text'] : $type_singular=$type_singular;
				($type_singular=='trackback') ? $type_singular=$wp_list_comments_args['sing_trackback_text'] : $type_singular=$type_singular;
				($type_singular=='pingback') ? $type_singular=$wp_list_comments_args['sing_pingback_text'] : $type_singular=$type_singular;
				($type_singular=='ping') ? $type_singular=$wp_list_comments_args['sing_ping_text'] : $type_singular=$type_singular;
				  
				// Check to see if post is password protected
				if ( post_password_required() ) {
					echo "<{$instance['comment_header']}>".$wp_list_comments_args['password_text']."</{$instance['comment_header']}>";
					echo '<p class="post_password_required">'. $wp_list_comments_args['pass_protected_text'] .'</p>';
					return false;
				}

				echo '<div id="bizz-comments-loop-'. $post_id .'" class="widget-bizz-comments-loop">';

				/* Open the output of the widget. */
				echo $args['before_widget'];

				// If we have comments
				if ( have_comments() ) :

					do_action( "before_{$comment_type}_div" );

					$div_id = ( 'comment' == $comment_type ) ? 'comments' : $comment_type;
					echo '<div id="'. $div_id .'">'; // div#comments

					$title = the_title( '&#8220;', '&#8221;', false );
					$local_comments = $comments;
					$_comments_by_type = separate_comments( $local_comments );

					echo "<{$instance['comment_header']} id=\"comments-number\" class=\"comments-header\">";
					bizz_comments_number( "".$wp_list_comments_args['no_text']." $type_plural ".$wp_list_comments_args['to_text']." $title", "1 $type_singular ".$wp_list_comments_args['to_text']." $title", "% $type_plural ".$wp_list_comments_args['to_text']." $title", $instance['type'], $_comments_by_type );
					echo "</{$instance['comment_header']}>";

					unset( $local_comments, $_comments_by_type );
?>
					<?php if ( $instance['enable_pagination'] and get_option( 'page_comments' ) and $paginate_comments_links ) : ?>
					<div class="comment-navigation paged-navigation">
					  <?php echo $paginate_comments_links; ?>
					  <?php do_action( "{$comment_type}_pagination" ); ?>
					</div><!-- .comment-navigation -->
					<?php endif; ?>

					<?php do_action( "before_{$comment_type}_list" ); ?>

					<ol class="commentlist">
					<?php wp_list_comments( $wp_list_comments_args ); ?>
					</ol>

					<?php do_action( "after_{$comment_type}_list" ); ?>

					<?php if ( $instance['enable_pagination'] and get_option( 'page_comments' ) and $paginate_comments_links ) : ?>
					<div class="comment-navigation paged-navigation">
					  <?php echo $paginate_comments_links; ?>
					  <?php do_action( "{$comment_type}_pagination" ); ?>
					</div><!-- .comment-navigation -->
					<?php endif; ?>
<?php
					echo '</div>'; // div#comments

					do_action( "after_{$comment_type}_div" );

				endif;

				/* Close the output of the widget. */
				echo $args['after_widget'];
				
				echo '</div>';
			
			}

			/* Load Comments Form. */
			bizz_comment_form( $form_args );
		  
		endif;  
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;
		
		// Checkboxes
		$instance['enable_pagination'] = $new_instance['enable_pagination'] ? true : false;
		$instance['enable_reply'] = $new_instance['enable_reply'] ? true : false;
		$instance['reverse_top_level'] = $new_instance['reverse_top_level'] ? true : false;
		$instance['comments_closed'] = esc_html( $new_instance['comments_closed'] );
		$instance['req'] = $new_instance['req'] ? true : false;
				
		// update $instance with $new_instance;
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array( 
		    'max_depth' => get_option('thread_comments_depth'), 
			'type' => 'all',
			'enable_pagination' => true, 
			'enable_reply' => true, 
			'comment_header' => 'h3', 
			'reverse_top_level' => false, //'reverse_children' => false,
			'reply_text' => __( 'Reply', 'bizzthemes' ),
			'login_text' => __( 'Log in to Reply', 'bizzthemes' ), 
			'comment_meta' => '[author] [date before="&middot; "] [link before="&middot; "] [edit before="&middot; "]',
			'comment_moderation' => __( 'Your comment is awaiting moderation.', 'bizzthemes' ),
			'password_text' => __( 'Password Protected', 'bizzthemes' ),
			'pass_protected_text' => __( 'This post is password protected. Enter the password to view comments.', 'bizzthemes' ),
			'sing_comment_text' => __( 'comment', 'bizzthemes' ),
			'plu_comment_text' => __( 'comments', 'bizzthemes' ),
			'sing_trackback_text' => __( 'trackback', 'bizzthemes' ),
			'plu_trackback_text' => __( 'trackbacks', 'bizzthemes' ),
			'sing_pingback_text' => __( 'pingback', 'bizzthemes' ),
			'plu_pingback_text' => __( 'pingbacks', 'bizzthemes' ),
			'sing_ping_text' => __( 'ping', 'bizzthemes' ),
			'plu_ping_text' => __( 'pings', 'bizzthemes' ),
			'no_text' => __( 'No', 'bizzthemes' ),
			'to_text' => __( 'to', 'bizzthemes' ),
			'req' => true, 
			'req_str' => __( '(required)', 'bizzthemes' ), 
			'name' => __( 'Name', 'bizzthemes' ), 
			'email' => __( 'Mail (will not be published)', 'bizzthemes' ), 
			'url' => __( 'Website', 'bizzthemes' ),
			'must_log_in' => __( 'You must be <a href="%s">logged in</a> to post a comment.', 'bizzthemes' ),
			'logged_in_as' => __( 'Logged in as <a href="%s">%s</a>. <a href="%s" title="Log out of this account">Log out &raquo;</a>', 'bizzthemes' ),
			'title_reply' => __( 'Leave a Reply', 'bizzthemes' ), 
			'title_reply_to' => __( 'Leave a Reply to %s', 'bizzthemes' ), 
			'cancel_reply_link' => __( 'Click here to cancel reply.', 'bizzthemes' ), 
			'label_submit' => __( 'Submit Comment', 'bizzthemes' ),
			'title_reply_tag' => 'h3',
			'comments_closed' => __( 'Comments are closed.', 'bizzthemes' )
		);
		$instance = wp_parse_args( $instance, $defaults );
		$tags = array( 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6', 'p' => 'p', 'span' => 'span', 'div' => 'div' );
		$type = array( 'all' => 'All', 'comment' => 'Comments', 'trackback' => 'Trackbacks', 'pingback' => 'Pingbacks', 'pings' => 'Trackbacks and Pingbacks' );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Comment type', 'bizzthemes' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<?php foreach ( $type as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['type'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comment_header' ); ?>"><?php _e( 'Comments headline tag', 'bizzthemes' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'comment_header' ); ?>" name="<?php echo $this->get_field_name( 'comment_header' ); ?>">
				<?php foreach ( $tags as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['comment_header'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title_reply_tag' ); ?>"><?php _e( 'Reply headline tag', 'bizzthemes' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'title_reply_tag' ); ?>" name="<?php echo $this->get_field_name( 'title_reply_tag' ); ?>">
				<?php foreach ( $tags as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['title_reply_tag'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comment_meta' ); ?>"><?php _e( 'Comment meta', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'comment_meta' ); ?>" name="<?php echo $this->get_field_name( 'comment_meta' ); ?>" value="<?php echo esc_attr($instance['comment_meta']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'max_depth' ); ?>"><?php _e( 'Max depth', 'bizzthemes' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'max_depth' ); ?>" name="<?php echo $this->get_field_name( 'max_depth' ); ?>" value="<?php echo $instance['max_depth']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'req' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['req'], true ); ?> id="<?php echo $this->get_field_id( 'req' ); ?>" name="<?php echo $this->get_field_name( 'req' ); ?>" /> <?php _e('Require name and email', 'bizzthemes'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'enable_pagination' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['enable_pagination'], true ); ?> id="<?php echo $this->get_field_id( 'enable_pagination' ); ?>" name="<?php echo $this->get_field_name( 'enable_pagination' ); ?>" /> <?php _e('Enable pagination', 'bizzthemes'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'enable_reply' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['enable_reply'], true ); ?> id="<?php echo $this->get_field_id( 'enable_reply' ); ?>" name="<?php echo $this->get_field_name( 'enable_reply' ); ?>" /> <?php _e('Enable comment reply', 'bizzthemes'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'reverse_top_level' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['reverse_top_level'], true ); ?> id="<?php echo $this->get_field_id( 'reverse_top_level' ); ?>" name="<?php echo $this->get_field_name( 'reverse_top_level' ); ?>" /> <?php _e('Reverse the comment order', 'bizzthemes'); ?></label>
		</p>
		<p>
			<br/><label><span class="translate"><?php _e('Translations', 'bizzthemes'); ?></span></label>
		</p>
		<p>
			<label class="spb tog"><?php _e('comment list', 'bizzthemes'); ?></label>
		</p>
		<p>
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'comment_moderation' ); ?>" name="<?php echo $this->get_field_name( 'comment_moderation' ); ?>" value="<?php echo $instance['comment_moderation']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'reply_text' ); ?>" name="<?php echo $this->get_field_name( 'reply_text' ); ?>" value="<?php echo $instance['reply_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'login_text' ); ?>" name="<?php echo $this->get_field_name( 'login_text' ); ?>" value="<?php echo $instance['login_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'password_text' ); ?>" name="<?php echo $this->get_field_name( 'password_text' ); ?>" value="<?php echo $instance['password_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'pass_protected_text' ); ?>" name="<?php echo $this->get_field_name( 'pass_protected_text' ); ?>" value="<?php echo $instance['pass_protected_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'sing_comment_text' ); ?>" name="<?php echo $this->get_field_name( 'sing_comment_text' ); ?>" value="<?php echo $instance['sing_comment_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'plu_comment_text' ); ?>" name="<?php echo $this->get_field_name( 'plu_comment_text' ); ?>" value="<?php echo $instance['plu_comment_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'sing_trackback_text' ); ?>" name="<?php echo $this->get_field_name( 'sing_trackback_text' ); ?>" value="<?php echo $instance['sing_trackback_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'plu_trackback_text' ); ?>" name="<?php echo $this->get_field_name( 'plu_trackback_text' ); ?>" value="<?php echo $instance['plu_trackback_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'sing_pingback_text' ); ?>" name="<?php echo $this->get_field_name( 'sing_pingback_text' ); ?>" value="<?php echo $instance['sing_pingback_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'plu_pingback_text' ); ?>" name="<?php echo $this->get_field_name( 'plu_pingback_text' ); ?>" value="<?php echo $instance['plu_pingback_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'sing_ping_text' ); ?>" name="<?php echo $this->get_field_name( 'sing_ping_text' ); ?>" value="<?php echo $instance['sing_ping_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'plu_ping_text' ); ?>" name="<?php echo $this->get_field_name( 'plu_ping_text' ); ?>" value="<?php echo $instance['plu_ping_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'no_text' ); ?>" name="<?php echo $this->get_field_name( 'no_text' ); ?>" value="<?php echo $instance['no_text']; ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'to_text' ); ?>" name="<?php echo $this->get_field_name( 'to_text' ); ?>" value="<?php echo $instance['to_text']; ?>" />
		</p>
		<p>
			<label class="spb tog"><?php _e('comment form', 'bizzthemes'); ?></label>
		</p>
		<p>
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo esc_attr($instance['name']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo esc_attr($instance['email']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo esc_attr($instance['url']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'req_str' ); ?>" name="<?php echo $this->get_field_name( 'req_str' ); ?>" value="<?php echo esc_attr($instance['req_str']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'label_submit' ); ?>" name="<?php echo $this->get_field_name( 'label_submit' ); ?>" value="<?php echo esc_attr($instance['label_submit']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'must_log_in' ); ?>" name="<?php echo $this->get_field_name( 'must_log_in' ); ?>" value="<?php echo esc_attr($instance['must_log_in']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'logged_in_as' ); ?>" name="<?php echo $this->get_field_name( 'logged_in_as' ); ?>" value="<?php echo esc_attr($instance['logged_in_as']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'title_reply' ); ?>" name="<?php echo $this->get_field_name( 'title_reply' ); ?>" value="<?php echo esc_attr($instance['title_reply']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'title_reply_to' ); ?>" name="<?php echo $this->get_field_name( 'title_reply_to' ); ?>" value="<?php echo esc_attr($instance['title_reply_to']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'cancel_reply_link' ); ?>" name="<?php echo $this->get_field_name( 'cancel_reply_link' ); ?>" value="<?php echo esc_attr($instance['cancel_reply_link']); ?>" />
			<input type="text" class="widefat spb tog" id="<?php echo $this->get_field_id( 'comments_closed' ); ?>" name="<?php echo $this->get_field_name( 'comments_closed' ); ?>" value="<?php echo esc_attr($instance['comments_closed']); ?>" />
		</p>
		<div class="clear">&nbsp;</div>
		<?php
	}
}

// INITIATE WIDGET
register_widget( 'Bizz_Widget_Comments_Loop' );

// CUSTOM COMMENTS FUNCTIONS
/**
 * Custom Loop callback for wp_list_comments()
 *
 * @since 0.1
 **/
 
// Callback function
function bizz_comments_loop_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
?>
	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
<?php 
	do_action( "before_{$args['type']}" );
	do_action( "comment_container", $comment, $args, $depth );
	do_action( "after_{$args['type']}" ); 

}

// Custom comment loop hook
add_action('comment_container', 'bizz_comment_container', 10, 3);
function bizz_comment_container( $comment, $args, $depth ) {

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<div id="div-comment-<?php comment_ID(); ?>" class="comment-container">
	    <div class="avatar-wrap">
			<?php echo get_avatar( $comment, 48, BIZZ_THEME_IMAGES .'/gravatar.png' ); ?>
		</div><!-- /.meta-wrap -->
		<div class="text-right">
			<div class="comm-reply<?php if (1 == $comment->user_id) echo " authcomment"; ?>">
				<?php echo bizz_comment_meta( $args['comment_meta'] ); ?>
				<?php if ( $args['enable_reply'] ): ?>
				    <span class="fr">
				    <?php comment_reply_link( array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?>
					</span>
				<?php endif; ?>
			</div><!-- /.comm-reply -->
			<div class="comment-entry">
			    <?php comment_text() ?>
				<?php if ( '0' == $comment->comment_approved ) : ?>
				    <p class="comment-moderation"><?php echo $args['comment_moderation']; ?></p>
				<?php endif; ?>
			</div><!-- /.comment-entry -->
		</div><!-- /.text-right -->
	</div><!-- /.comment-container -->
<?php
}

/**
 * Display the language string for the number of comments the current post has.
 *
 * @since 0.71
 * @uses $id
 * @uses apply_filters() Calls the 'comments_number' hook on the output and number of comments respectively.
 *
 * @param string $zero Text for no comments
 * @param string $one Text for one comment
 * @param string $more Text for more than one comment
 * @param string $type Comment Type
 * @param array $comments Comments by type
 */
function bizz_comments_number( $zero = false, $one = false, $more = false, $type = 'all', $comments ) {
	$number = ( 'all' == $type ) ? count( $comments['comment'] ) + count( $comments['pings'] ) : count($comments[$type]);

	if ( $number > 1 )
		$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', 'bizzthemes') : $more);
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __('No Comments', 'bizzthemes') : $zero;
	else // must be one
		$output = ( false === $one ) ? __('One Comment', 'bizzthemes') : $one;

	echo apply_filters('comments_number', $output, $number);
}

/**
 * Process any shortcodes applied to $content
 *
 * @since 0.1
 **/
function bizz_comment_meta( $content ) {
	$content = preg_replace( '/\[(.+?)\]/', '[comment_$1]', $content );
	return apply_filters( 'bizz_comment_meta', do_shortcode( $content ) );
}

/**
 * Displays the author's avatar and the author's name/link
 *
 * @since 0.1
 **/
function bizz_comment_author_avatar( $comment, $args ) { 
?>
	<div class="comment-author vcard">
<?php 
	if ( $args['avatar_size'] != 0 )
		echo get_avatar( $comment, $args['avatar_size'] );
?>
		<cite class="fn"><?php echo bizz_comment_author(); ?></cite>
	</div>
<?php
}

/**
 * Returns the author's name/link microformatted
 *
 * @since 0.1
 **/
function bizz_comment_author( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$author = esc_html( get_comment_author() );
	$url = esc_url( get_comment_author_url() );

	/* Display link and cite if URL is set. Also, properly cites trackbacks/pingbacks. */
	if ( $url )
		$output = '<cite class="fn" title="' . $url . '"><a href="' . $url . '" title="' . $author . '" class="url" rel="external nofollow">' . $author . '</a></cite>';
	else
		$output = '<cite class="fn">' . $author . '</cite>';

	$output = '<span class="comment-author vcard">' . apply_filters( 'get_comment_author_link', $output ) . '</span>';

	return apply_filters( 'bizz_comment_author', $before . $output . $after );
}

/**
 * Displays the comment date
 *
 * @since 0.1
 */
function bizz_comment_date( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output = '<abbr class="comment-date" title="' . get_comment_date(get_option('date_format')) . '">' . get_comment_date() . '</abbr>';
	
	return apply_filters( 'bizz_comment_date', $before . $output . $after );
}

/**
 * Displays the comment time
 *
 * @since 0.1
 */
function bizz_comment_time( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output = '<span class="comment-time"><abbr title="' . get_comment_date( __( 'g:i a', 'bizzthemes' ) ) . '">' . get_comment_time() . '</abbr></span>';
	
	return apply_filters( 'bizz_comment_time', $before . $output . $after );
}

/**
 * Displays the comment count
 *
 * @since 0.1
 **/
function bizz_comment_count( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	global $comment_count;
	
	if ( !isset($comment_count) )
		$comment_count = 1;
	
	$comment_type = get_comment_type();
	
	$output = "<span class=\"$comment_type-count\">$comment_count</span>";
	
	$comment_count++;
	
	return apply_filters( 'bizz_comment_count', $before . $output . $after );
}

/**
 * Displays a list of comma seperated tags
 *
 * @since 0.1
 **/
function bizz_comment_link( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'label' => __( 'Permalink', 'bizzthemes' ) );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );

	$output = '<span class="comment-permalink"><a href="' . esc_url(get_comment_link()) . '" title="' . sprintf( __( 'Permalink to %1$s %2$s', 'bizzthemes' ), get_comment_type(), get_comment_ID() ) . '">' . $label . '</a></span>';

	return apply_filters( 'bizz_comment_link', $before . $output . $after );
}

/**
 * Comment Reply link
 *
 * @since 0.1
 */
function bizz_comment_reply( $atts = array() ) {
	$defaults = array(
		'reply_text' => __( 'Reply', 'bizzthemes' ),
		'login_text' => __( 'Log in to reply.', 'bizzthemes' ),
		'depth' => $GLOBALS['comment_depth'],
		'max_depth' => get_option( 'thread_comments_depth' ),
		'before' => '',
		'after' => ''
	);
	$args = shortcode_atts( $defaults, $args );

	if ( !get_option( 'thread_comments' ) || 'comment' !== get_comment_type() )
		return '';

	return get_comment_reply_link( $args );
}

/**
 * Comment Edit link
 *
 * @since 0.1
 **/
function bizz_comment_edit( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'label' => __( 'Edit', 'bizzthemes' ) );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$edit_link = get_edit_comment_link( get_comment_ID() );

	if ( !$edit_link )
		return '';

	$output = '<span class="comment-edit"><a href="' . $edit_link . '" title="' . $label . '">' . $label . '</a></span>';
	
	return apply_filters( 'bizz_comment_edit', $before . $output . $after );
}

// Shortcodes
add_shortcode( 'comment_author', 'bizz_comment_author' );
add_shortcode( 'comment_date', 'bizz_comment_date' );
add_shortcode( 'comment_time', 'bizz_comment_time' );
add_shortcode( 'comment_count', 'bizz_comment_count' );
add_shortcode( 'comment_link', 'bizz_comment_link' );
add_shortcode( 'comment_reply', 'bizz_comment_reply' );
add_shortcode( 'comment_edit', 'bizz_comment_edit' );

/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comments_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comments_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0 
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function bizz_comment_form( $form_args = array(), $post_id = null ) {
	global $user_identity, $id;
	
	$form_args['comments_closed'] = ( isset($form_args['comments_closed']) ) ? apply_filters( 'widget_comments_closed', $form_args['comments_closed'], $form_args ) :__( 'Comments are closed.', 'bizzthemes' );
	$form_args['req'] = ( isset($form_args['req']) ) ? apply_filters( 'widget_req', $form_args['req'], $form_args ) : true;
	$form_args['req_str'] = ( isset($form_args['req_str']) ) ? apply_filters( 'widget_req_str', $form_args['req_str'], $form_args ) : __( '(required)', 'bizzthemes' );
	$form_args['name'] = ( isset($form_args['name']) ) ? apply_filters( 'widget_name', $form_args['name'], $form_args ) : __( 'Name', 'bizzthemes' );
	$form_args['email'] = ( isset($form_args['email']) ) ? apply_filters( 'widget_email', $form_args['email'], $form_args ) : __( 'Mail (will not be published)', 'bizzthemes' );
	$form_args['url'] = ( isset($form_args['url']) ) ? apply_filters( 'widget_url', $form_args['url'], $form_args ) : __( 'Website', 'bizzthemes' );
	$form_args['must_log_in'] = ( isset($form_args['must_log_in']) ) ? apply_filters( 'widget_must_log_in', $form_args['must_log_in'], $form_args ) : __( 'You must be <a href="%s">logged in</a> to post a comment.', 'bizzthemes' );
	$form_args['logged_in_as'] = ( isset($form_args['logged_in_as']) ) ? apply_filters( 'widget_logged_in_as', $form_args['logged_in_as'], $form_args ) : __( 'Logged in as <a href="%s">%s</a>. <a href="%s" title="Log out of this account">Log out &raquo;</a>', 'bizzthemes' );
	$form_args['title_reply_tag'] = ( isset($form_args['title_reply_tag']) ) ? $form_args['title_reply_tag'] : 'h3';
	$form_args['title_reply'] = ( isset($form_args['title_reply']) ) ? apply_filters( 'widget_title_reply', $form_args['title_reply'], $form_args ) : __( 'Leave a Reply', 'bizzthemes' );
	$form_args['title_reply_to'] = ( isset($form_args['title_reply_to']) ) ? apply_filters( 'widget_title_reply_to', $form_args['title_reply_to'], $form_args ) : __( 'Leave a Reply to %s', 'bizzthemes' );
	$form_args['cancel_reply_link'] = ( isset($form_args['cancel_reply_link']) ) ? apply_filters( 'widget_cancel_reply_link', $form_args['cancel_reply_link'], $form_args ) : __( 'Click here to cancel reply.', 'bizzthemes' );
	$form_args['label_submit'] = ( isset($form_args['label_submit']) ) ? apply_filters( 'widget_label_submit', $form_args['label_submit'], $form_args ) : __( 'Submit Comment', 'bizzthemes' );
	
	$post_id = ( null === $post_id ) ? $id : $post_id;
	$commenter = wp_get_current_commenter();
	$req = $form_args['req'];
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$req_str  = ( $req ? ' ' . $form_args['req_str'] : '' );
	
	$args = array( 
	    'fields' => apply_filters( 
		    'comment_form_default_fields', array( 
		        'author' => '<input type="text" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" size="22" tabindex="1"' . $aria_req . ' /> <label for="author"><small>' . $form_args['name'] . $req_str . '</small></label>', 
				'email'  => '<input type="text" name="email" id="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="22" tabindex="2"' . $aria_req . ' /> <label for="email"><small>' . $form_args['email'] . $req_str . '</small></label>', 
				'url'    => '<input type="text" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="22" tabindex="3" /> <label for="url"><small>' . $form_args['url'] . '</small></label>' 
		    ) 
		),
		'comment_field' => '<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>', 
		'must_log_in' => '<p class="must_log_in">' .  sprintf( $form_args['must_log_in'], wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>', 
		'logged_in_as' => '<p class="logged_in_as">' . sprintf( $form_args['logged_in_as'], admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>', 
		'id_form' => 'commentform', 
		'id_submit' => 'submit', 
		'title_reply' => $form_args['title_reply'], 
		'title_reply_to' => $form_args['title_reply_to'], 
		'cancel_reply_link' => $form_args['cancel_reply_link'], 
		'label_submit' => $form_args['label_submit'],
	);
	
	if ( comments_open() ) :
		echo '<div id="comments-form-'. $post_id .'" class="widget-comments-form">';
			do_action( 'comment_form_before' );				
?>
			<div id="respond">
<?php
			echo $form_args['before_widget'];
			
			// Title				
			echo "<{$form_args['title_reply_tag']} class=\"title-reply\">";
			comment_form_title( $args['title_reply'], $args['title_reply_to'] );
			echo "</{$form_args['title_reply_tag']}>";
?>
			<div class="cancel-comment-reply">
				<small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small>
			</div>
			
			<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
				<?php echo $args['must_log_in']; ?>
				<?php do_action( 'comment_form_must_log_in_after' ); ?>
			<?php else : ?>
				<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
					<?php do_action( 'comment_form_top' ); ?>
					<?php if ( is_user_logged_in() ) : ?>
						<?php echo $args['logged_in_as']; ?>
						<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
					<?php else : ?>
						<?php
						do_action( 'comment_form_before_fields' );
						foreach ( (array) $args['fields'] as $name => $field ) {
							echo '<p class="commpadd">' . apply_filters( "comment_form_field_{$name}", $field ) . "</p>\n";
						}
						do_action( 'comment_form_after_fields' );
						?>
					<?php endif; ?>
					<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
					<p>
						<button class="btn" name="submit" type="submit" id="submit" tabindex="<?php echo ( count( $args['fields'] ) + 2 ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>"><span><span><?php echo stripslashes(__('Add Comment', 'bizzthemes')); ?></span></span></button>
						<?php comment_id_fields(); ?>
					</p>
					<?php do_action( 'comments_form', $post_id ); ?>
				</form>
			<?php endif; ?>
			
			<?php echo $form_args['after_widget']; ?>
			
			</div>
			
			<?php 
				do_action( 'comment_form' );
				do_action( 'comment_form_after' );
		echo '</div>';

	else : // comments are closed
	
		echo '<!-- If comments are closed. -->';
		if ( $form_args['comments_closed'] ){
			echo $form_args['before_widget'];
			echo '<p class="comments-closed">'. $form_args['comments_closed'] .'</p>';
			echo $form_args['after_widget'];
		}

		do_action( 'comment_form_comments_closed' );
		
	endif;
}
