<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * POST CONTENT
 *
 * output post content, based on predefined arguments
 * @since 7.0
 */
function bizz_post_content( $args = '', $post_count = false ) {
	global $wp_query, $post, $opt;
	
	bizz_hook_before_post($post_count); #hook
		
	echo "<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
	
	$selflink = (isset($args['thumb_selflink']) && $args['thumb_selflink'] == true) ? true : false;
	$cropp = (isset($args['thumb_cropp']) && $args['thumb_cropp'] != '') ? $args['thumb_cropp'] : 'c';
	if ( isset($opt['bizzthemes_thumb_show']['value']) && $args['thumb_display'] && !is_page() ) {
	    if ( is_single() ) { # is single post
		    if ( $args['thumb_single'] ) # show
				bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
		} 
		else
			bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
	}
		
	if ( $args['remove_posts'] == '1' && (is_archive() || $wp_query->is_posts_page || is_search() || is_home()) )
		echo '';
	else {	
		if ($args['full_posts']=='0' && ( is_archive() || $wp_query->is_posts_page || is_search() || is_home() ) && !is_post_type_archive() ){
			the_excerpt();
			if ( $args['read_more'] )
				echo apply_filters('bizz_read_more', '<span class="read-more"><a href="' . get_permalink() . '" class="url fn" rel="nofollow">' . $args['read_more_text'] . '</a></span>');
		}
		else
			the_content($args['read_more_text']);
		
		wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );
	}
	
	echo '<div class="fix"><!----></div>';
	echo "</".apply_filters('bizz_html5_section', "div").">\n";

	bizz_hook_after_post($post_count); #hook
}

/**
 * POST CONTENT - query posts
 *
 * output post content for Query Posts widget, based on predefined arguments
 * @since 7.0
 */
function bizz_post_content_query($args = '', $post_count = false) {
	global $wp_query, $post, $opt;
	
	bizz_hook_before_post($post_count); #hook
	
	echo "<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
	
	$selflink = (isset($args['thumb_selflink']) && $args['thumb_selflink'] == true) ? true : false;
	$cropp = (isset($args['thumb_cropp']) && $args['thumb_cropp'] != '') ? $args['thumb_cropp'] : 'c';
	if (isset($opt['bizzthemes_thumb_show']['value']) && $args['thumb_display']){
		bizz_image('width='.$args['thumb_width'].'&height='.$args['thumb_height'].'&class=thumbnail '.$args['thumb_align'].'&cropp='.$cropp.'&selflink='.$selflink.'&filter='.$args['thumb_filter'].'&sharpen='.$args['thumb_sharpen'].'');
	}
		
	if ($args['remove_posts']=='0'){
		if ($args['full_posts']=='0' && ( is_archive() || $wp_query->is_posts_page || is_search() || is_home() )){
			the_excerpt();
			if ( $args['read_more'] )
				echo apply_filters('bizz_read_more', '<span class="read-more"><a href="' . get_permalink() . '" class="url fn" rel="nofollow">' . $args['read_more_text'] . '</a></span>');
		}
		else
			the_content($args['read_more_text']);
		
		wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );
	}
	
	echo '<div class="fix"><!----></div>';
	echo "</".apply_filters('bizz_html5_section', "div").">\n";

	bizz_hook_after_post($post_count); #hook
}

/**
 * HEADLINE AREA - archives
 *
 * output headlines for arhives, based on predefined arguments
 * @since 7.0
 */
function bizz_archive_headline() {
	global $wp_query; #wp
	$output = "<".apply_filters('bizz_html5_header', "div")." class=\"headline_area archive_headline\">\n";
		
	if ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) { #wp
		$headline = $wp_query->queried_object->name; #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', $headline) . "</h1>\n"; #filter
	}
	elseif ($wp_query->is_author) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_author_meta('display_name', $wp_query->query_vars['author'])) . "</h1>\n"; #wp
	elseif ($wp_query->is_day) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('l, F j, Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_month) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('F Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_year) #wp
		$output .= "\t<h1>" . apply_filters('bizz_archive_intro_headline', get_the_time('Y')) . "</h1>\n"; #wp
	elseif ($wp_query->is_search) #wp
		$output .= "\t<h1>" . __('Search:', 'bizzthemes') . ' ' . apply_filters('bizz_archive_intro_headline', esc_html($wp_query->query_vars['s'])) . "</h1>\n"; #wp

	$output .= "</".apply_filters('bizz_html5_header', "div").">\n";
	echo apply_filters('bizz_archive_headline', $output);
}

/**
 * HEADLINE AREA - main
 *
 * output main headlines, based on predefined arguments
 * @since 7.0
 */
function bizz_headline_area() {

    if (apply_filters('bizz_show_headline_area', true)) {
	(is_paged()) ? $ispaged = ' paged' : $ispaged = '';
	
		$output = "<".apply_filters('bizz_html5_header', "div")." class='headline_area'>\n";

	    if (is_404())
		    $output .= "<h1 class='entry-title'>" . stripslashes(__('Error 404 | Nothing found!', 'bizzthemes')) . "</h1>\n";
		elseif (is_page())
		    $output .= (is_front_page()) ? "<h2 class='entry-title".$ispaged."'>" . get_the_title() . "</h2>\n" : "<h1 class='entry-title title'>" . get_the_title() . "</h1>\n";
		else {
		    if (is_single())
			    $output .= "<h1 class='entry-title title'>" . get_the_title() . "</h1>\n";
			else
			    $output .= "<h2 class='entry-title".$ispaged."'><a href='" . get_permalink() . "' rel='bookmark' title='" . get_the_title() . "'>" . get_the_title() . "</a></h2>\n";
		}
	
		$output .= "</".apply_filters('bizz_html5_header', "div").">\n";
		
		echo apply_filters('bizz_headline_area', $output);
	}

}

/**
 * 404 ERROR
 *
 * output 404 error message
 * @since 7.0
 */
function bizz_404_error() {

	$output = '
<h2>'.stripslashes(__('Sorry, but you are looking for something that is not here.', 'bizzthemes')).'</h2>
<p>'.__('Surfin&#8217; ain&#8217;t easy, and right now, you&#8217;re lost at sea. But don&#8217;t worry; simply pick an option from the list below, and you&#8217;ll be back out riding the waves of the Internet in no time.', 'bizzthemes').'</p>
<ul>
	<li>'.__('Hit the &#8220;back&#8221; button on your browser. It&#8217;s perfect for situations like this!', 'bizzthemes').'</li>
	<li>'.sprintf(__('Head on over to the <a href="%s" rel="nofollow">home page</a>.', 'bizzthemes'), home_url()).'</li>
	<li>'.__('You will find what you are looking for.', 'bizzthemes').'</li>
</ul>
	';
	
	echo apply_filters('bizz_404_output', $output);	
}

/**
 * POST META
 *
 * output post meta data, based on arguments
 * @since 7.0
 */
function bizz_post_meta($args = '') {
	
	$args = (isset($args[0])) ? $args[0] : $args; #[0] array level
	$show_meta = ( is_singular() && !is_singular('post') ) ? (( isset($args['post_meta']) && $args['post_meta'] ) ? true : false) : true;
	
	$return_meta = '';
	
	if ( $show_meta ) {

		$return_meta .= "<".apply_filters('bizz_html5_aside', "p")." class=\"headline_meta\">";
		
		if ($args['post_date'])
			$return_meta .= '<span class="date"><abbr class="published" title="' . get_the_time('Y-m-d') . '">' . get_the_time(get_option('date_format')) . '</abbr></span>';
		if ($args['post_author'])
			$return_meta .= '<span class="auth"><a href="' . get_author_posts_url(get_the_author_meta('ID') ) . '" class="auth" rel="nofollow">' . get_the_author() . '</a></span>';
		if ($args['post_comments']) {
			$return_meta .= '<span class="comm">';
			$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

			if ( comments_open() ) {
			
				if ( $num_comments == 0 )
					$comments = __('No Comments', 'bizzthemes');
				elseif ( $num_comments > 1 )
					$comments = sprintf(__('%d Comments', 'bizzthemes'), $num_comments);
				else
					$comments = __('One Comment', 'bizzthemes');

				$return_meta .= '<a href="' . get_comments_link() .'" rel="nofollow">'. $comments.'</a>';
			} 
			else
				$return_meta .=  __('Comments are closed.', 'bizzthemes');
			
			$return_meta .= '</span>';
		}
		if ($args['post_categories'])	
			$return_meta .= seo_post_cats();
		if ($args['post_tags'])		
			$return_meta .= seo_post_tags();
		if ($args['post_edit']) {	
			if (current_user_can('manage_options') && is_user_logged_in())
				$return_meta .= '<span class="edit">' . get_edit_post_link() . '</span>';
		}
		
		$return_meta .= "</".apply_filters('bizz_html5_aside', "p").">\n";
	}
	
	echo apply_filters('bizz_post_meta', $return_meta, $show_meta, $args);

}

/**
 * POST TAGS
 *
 * output post tags
 * @since 7.0
 */
function seo_post_tags($before='', $after='') {

    global $post;
	$post_tags = get_the_tags();
		
	if ($post_tags) {
	
		$return = '<span class="tag">' . $before;
		$num_tags = count($post_tags);
		$tag_count = 1;
		
		if ( isset($GLOBALS['opt']['bizzthemes_nofollow_tags']['value']) ) { $nofollow = ' nofollow'; } else { $nofollow = ''; }

		foreach ($post_tags as $tag) {			
			$html_before = '<a href="' . get_tag_link($tag->term_id) . '" rel="tag' . $nofollow . '">';
			$html_after = '</a>';
			
			if ($tag_count < $num_tags)
				$sep = ', ' . "\n";
			elseif ($tag_count == $num_tags)
				$sep = "\n";
			
			$return .= $html_before . $tag->name . $html_after . $sep;
			$tag_count++;
		}
		$return .= $after . '</span>';
		
		return apply_filters('bizz_post_tags', $return);
		
	}
		
}

/**
 * POST CATEGORIES
 *
 * output post categories, seo opzimized
 * @since 7.0
 */
function seo_post_cats($before='', $after='') {
    
	global $post;
	$post_cats = get_the_category();
		
	if ($post_cats) {
		    
		$return = '<span class="cat">' . $before;
		$num_cats = count($post_cats);
		$cat_count = 1;
			
		if ( isset($GLOBALS['opt']['bizzthemes_nofollow_cats']['value']) ) { $nofollow = ' nofollow'; } else { $nofollow = ''; }

		foreach ($post_cats as $cat) {			
			$html_before = '<a href="' . get_category_link($cat->term_id) . '" rel="cat' . $nofollow . '">';
			$html_after = '</a>';
				
			if ($cat_count < $num_cats)
				$sep = ', ' . "\n";
			elseif ($cat_count == $num_cats)
				$sep = "\n";
				
			$return .= $html_before . $cat->name . $html_after . $sep;
			$cat_count++;
		}
		$return .= $after . '</span>';
		
		
		return apply_filters('bizz_post_cats', $return);
		
	}
	
}

/**
 * PAGINATION
 *
 * output custom pagination
 * @Original Author: Lester 'GaMerZ' Chan, 2.50
 * @since 6.0
 */
function bizz_wp_pagenavi($before = '', $after = '') {
    global $wpdb, $wp_query;
	
	// custom filter
	$args = apply_filters('bizz_filter_pagination', null);
	
    if (!is_single()) {
		// custom variables
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $pagenavi_options = get_option('pagenavi_options');
        $pagenavi_options = wp_parse_args( (array) $args, $pagenavi_options );
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
		$container_before = ( isset($args['container_before']) ) ? $args['container_before'] : "<div class='fix'><!----></div>\n<div class='pagination_area clearfix'>\n";
		$container_after = ( isset($args['container_after']) ) ? $args['container_after'] : "<div class='pagination_loading'><!----></div>\n</div>\n";
		$ul_class = ( isset($args['ul_class']) ) ? $args['ul_class'] : "lpag";
		$active_class = ( isset($args['active_class']) ) ? $args['active_class'] : "current";
		$prev_link = ( isset($args['prev_link']) ) ? $args['prev_link'] : "<li>\n".get_previous_posts_link($pagenavi_options['prev_text'])."</li>\n";
		$next_link = ( isset($args['next_link']) ) ? $args['next_link'] : "<li>\n".get_next_posts_link($pagenavi_options['next_text'], $max_page)."</li>\n";
						
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $pages_to_show_minus_1 = $pages_to_show-1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
        if($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
            echo $container_before;
			echo $before.'<ul class="'.$ul_class.'">'."\n";
            switch(intval($pagenavi_options['style'])) {
                case 1:                   
                    if ($start_page >= 2 && $pages_to_show < $max_page) {
                        $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), '&laquo; '.stripslashes(__('First', 'bizzthemes')));
                        echo '<li><a href="'.esc_url(get_pagenum_link()).'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
                        if(!empty($pagenavi_options['dotleft_text'])) {
                            echo '<li>'.$pagenavi_options['dotleft_text'].'</li>';
                        }
                    }
					// Previous
					echo $prev_link;
					
                    for($i = $start_page; $i  <= $end_page; $i++) {                        
                        if($i == $paged) {
                            $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                            echo '<li class="'.$active_class.'"><span>'.$current_page_text.'</span></li>';
                        } else {
                            $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                            echo '<li><a href="'.esc_url(get_pagenum_link($i)).'" title="'.$page_text.'">'.$page_text.'</a></li>';
                        }
                    }
					// Next
					echo $next_link;
					
                    if ($end_page < $max_page) {
                        if(!empty($pagenavi_options['dotright_text'])) {
                            echo '<li>'.$pagenavi_options['dotright_text'].'</li>';
                        }
                        $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), ''.stripslashes(__('Last', 'bizzthemes')).' &raquo;');
                        echo '<li><a href="'.esc_url(get_pagenum_link($max_page)).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
                    }
                    break;
                case 2;
                    echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">'."\n";
                    echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">'."\n";
                    for($i = 1; $i  <= $max_page; $i++) {
                        $page_num = $i;
                        if($page_num == 1) {
                            $page_num = 0;
                        }
                        if($i == $paged) {
                            $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                            echo '<option value="'.esc_url(get_pagenum_link($page_num)).'" selected="selected" class="'.$active_class.'">'.$current_page_text."</option>\n";
                        } else {
                            $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                            echo '<option value="'.esc_url(get_pagenum_link($page_num)).'">'.$page_text."</option>\n";
                        }
                    }
                    echo "</select>\n";
                    echo "</form>\n";
                    break;
            }
            echo '</ul>'.$after."\n";
			echo $container_after;
        }
    }
}

add_action('init', 'bizz_wp_pagenavi_init');
function bizz_wp_pagenavi_init() {
    // Add Options
    $pagenavi_options = array();
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = __('&laquo; First','bizzthemes');
    $pagenavi_options['last_text'] = __('Last &raquo;','bizzthemes');
    $pagenavi_options['next_text'] = __('&raquo;','bizzthemes');
    $pagenavi_options['prev_text'] = __('&laquo;','bizzthemes');
    $pagenavi_options['dotright_text'] = __('...','bizzthemes');
    $pagenavi_options['dotleft_text'] = __('...','bizzthemes');
    $pagenavi_options['style'] = 1;
    $pagenavi_options['num_pages'] = 5;
    $pagenavi_options['always_show'] = 0;
	add_option('pagenavi_options', $pagenavi_options);
}

/**
 * VIDEO EMBED
 *
 * output custom video embed
 * @Original Addon Author: WooThemes
 * @since 7.4.8
 */
function bizz_embed($args) {
	
	// Defaults
	$key 	= 'embed';
	$width 	= null;
	$height = null;
	$class	= 'video';
	$id 	= null;

	if ( !is_array( $args ) )
		parse_str( $args, $args );

	extract( $args );

	if ( empty( $id ) ) {
		global $post;
		$id = $post->ID;
	}

	// Cast $width and $height to integer
	$width 	= intval( $width );
	$height = intval( $height );
	
	$custom_field = get_post_meta( $id, $key, true );
	$custom_field = wp_filter_nohtml_kses( $custom_field );
	
	if ($custom_field) :
		$custom_field = html_entity_decode( $custom_field ); // Decode HTML entities.

		$org_width 			= $width;
		$org_height 		= $height;
		$calculated_height 	= '';
		$embed_width 		= '';
		$embed_height 		= '';

		// Get custom width and height
		$custom_width 		= esc_html( get_post_meta( $id, 'width', true ) );
		$custom_height 		= esc_html( get_post_meta( $id, 'height', true ) );

		//Dynamic Height Calculation
		if ($org_height == '' && $org_width != '') {
			$raw_values = explode(  ' ', $custom_field);
			foreach ( $raw_values as $raw ) {
				$embed_params = explode( '=', $raw );
				if ( $embed_params[0] == 'width' )
					$embed_width = preg_replace( '/[^0-9]/', '', $embed_params[1]);
				elseif ( $embed_params[0] == 'height' )
					$embed_height = preg_replace( '/[^0-9]/', '', $embed_params[1]);
			}

			$float_width 		= floatval( $embed_width );
			$float_height 		= floatval( $embed_height );
			@$float_ratio 		= $float_height / $float_width;
			$calculated_height 	= intval( $float_ratio * $width );
		}

		// Set values: width="XXX", height="XXX"
		if ( ! $custom_width ) $width = 'width="' . esc_attr( $width ) . '"'; else $width = 'width="' . esc_attr( $custom_width ) . '"';
		if ( $height == '' ) { $height = 'height="' . esc_attr( $calculated_height ) . '"'; } else { if ( ! $custom_height ) { $height = 'height="' . esc_attr( $height ) . '"'; } else { $height = 'height="' . esc_attr( $custom_height ) . '"'; } }
		$custom_field = stripslashes($custom_field);
		$custom_field = preg_replace( '/width="([0-9]*)"/' , $width , $custom_field );
		$custom_field = preg_replace( '/height="([0-9]*)"/' , $height, $custom_field );

		// Set values: width:XXXpx, height:XXXpx
		if ( ! $custom_width ) $width = 'width:' . esc_attr( $org_width ) . 'px'; else $width = 'width:' . esc_attr( $custom_width ) . 'px';
		if (  $height == '' ) { $height = 'height:' . esc_attr( $calculated_height ) . 'px'; } else { if ( ! $custom_height ) { $height = 'height:' . esc_attr( $org_height ) . 'px'; } else { $height = 'height:' . esc_attr( $custom_height ) . 'px'; } }
		$custom_field = stripslashes($custom_field);
		$custom_field = preg_replace( '/width:([0-9]*)px/' , $width , $custom_field );
		$custom_field = preg_replace( '/height:([0-9]*)px/' , $height, $custom_field );

		// Suckerfish menu hack
		$custom_field = str_replace( '<embed ', '<param name="wmode" value="transparent"></param><embed wmode="transparent" ', $custom_field );
		$custom_field = str_replace( '<iframe ', '<iframe wmode="transparent" ', $custom_field );
		$custom_field = str_replace( '" frameborder="', '?wmode=transparent" frameborder="', $custom_field );

		// Find and sanitize video URL. Add "wmode=transparent" to URL.
		$video_url = preg_match( '/src=["\']?([^"\' ]*)["\' ]/is', $custom_field, $matches );
		if ( isset( $matches[1] ) ) {
			$custom_field = str_replace( $matches[0], 'src="' . esc_url( add_query_arg( 'wmode', 'transparent', $matches[1] ) ) . '"', $custom_field );
		}

		$output = '';
		$output .= '<div class="'. esc_attr( $class ) .'">' . $custom_field . '</div>';

		return apply_filters( 'bizz_embed', $output );
	else :
		return false;
	endif;
}

// Default video filter
add_filter( 'bizz_embed', 'do_shortcode' );

// Deprecated function
function bizz_get_embed($key = 'embed', $width, $height, $class = 'video', $id = null) {
	// Run new function
	return bizz_embed( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&id='.$id );

}

/**
 * THUMBNAILS
 *
 * output custom image thumbnails
 * @Original Addon Author: WooThemes
 * @since 6.0
 */
function bizz_image($args) {
	global $post;

	//Defaults
	$key 			= 'image'; 		// Custom field key eg. "image"
	$width 			= null; 		// Set width manually without using $type
	$height 		= null; 		// Set height manually without using $type
	$class 			= ''; 			// CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
	$quality 		= 90;			// Enter a quality between 80-100. Default is 90
	$id 			= null; 		// Assign a custom ID, if alternative is required.
	$link 			= 'src'; 		// Echo with image links ('src'), as image ('img'), as source ('source') or as resized source ('rsource').
	$repeat 		= 1; 			// Auto Img Function. Adjust amount of images to return for the post attachments.
	$offset 		= 0; 			// Auto Img Function. Offset the $repeat with assigned amount of objects.
	$before 		= ''; 			// Auto Img Function. Add Syntax before image output.
	$after 			= ''; 			// Auto Img Function. Add Syntax after image output.
	$single 		= false; 		// Auto Img Function Only. Forces "img" return on images, like on single.php template
	$force 			= false; 		// Force smaller images to not be effected with image width and height dimentions (proportions fix)
	$return 		= false; 		// Return results instead of echoing out.
	$is_auto_image 	= false; 		// A parameter that accepts a img url for resizing. (No anchor)
	$src 			= ''; 			// A parameter that accepts a img url for resizing. (No anchor)
	$meta 			= ''; 			// Add a custom meta text to the image and anchor of the image.
	$noheight 		= '';			// Responsive
	$alignment 		= '';			// Image alignment inside post
	$size 			= '';			// Featured image size, like 'thumbnail', 'large', 'full'
	$alt 			= '';			// Custom alt attribute
	$img_link 		= '';			// Custom image with whole source, like '<img src="..." />'
	$attachment_id 	= array();		// define array
	$attachment_src = array();		// define array
	
	$selflink 		= false; 		// Link image to itself
	$cropp 			= false; 		// Add crop position     
	/*  * a=position; example a=t (crop from the top)
	    * c : position in the center (this is the default)
		* t : align top
		* tr : align top right
		* tl : align top left
		* b : align bottom
		* br : align bottom right
		* bl : align bottom left
		* l : align left
		* r : align right)
	*/
	$filter 		= ''; 			// Add image filters
	$sharpen 		= ''; 			// Sharpen image filter
	$zc 			= '1'; 			// Add zoom crop position

	if ( ! is_array( $args ) )
		parse_str( $args, $args );

	extract( $args );

    // Set post ID
    if ( empty( $id ) )
		$id = $post->ID;
		
	// Get featured thumbnail meta
	$thumb_id = esc_html( get_post_meta( $id, '_thumbnail_id', true ) );

	// Set alignment
	if ( $alignment == '' )
		$alignment = esc_html( get_post_meta( $id, '_image_alignment', true ) );

	// Get standard sizes
	if ( ! $width && ! $height ) {
		$width 	= '100';
		$height = '100';
	}
	
	// Cast $width and $height to integer
	$width 	= intval( $width );
	$height = intval( $height );
	
	/* ------------------------------------------------------------------------- */
	/* FIND IMAGE TO USE */
	/* ------------------------------------------------------------------------- */

	// When a custom image is sent through
	if ( $src != '' ) {
		$custom_field = esc_url( $src );
		$link = 'img';
	}
	// WP 2.9 Post Thumbnail support
	elseif ( isset($GLOBALS['opt']['bizzthemes_thumb_show']['value']) && ! empty( $thumb_id ) ) {
		if ( isset($GLOBALS['opt']['bizzthemes_auto_img']['value']) ) {
			if ( 0 == $height ) {
				$img_data = wp_get_attachment_image_src( $thumb_id, array( intval( $width ), 9999 ) );
				$height = $img_data[2];
			}
			// Dynamically resize the post thumbnail
			$vt_crop = $GLOBALS['opt']['bizzthemes_resize']['value'];
			if ($vt_crop == 'true' ) $vt_crop = true; else $vt_crop = false;
			$vt_image = vt_resize( $thumb_id, '', $width, $height, $vt_crop, $cropp );
			// Set fields for output
			$custom_field = esc_url( $vt_image['url'] );
			$width = $vt_image['width'];
			$height = $vt_image['height'];
		} 
		else {
			// Use predefined size string
			$thumb_size = ( $size ) ? $size : array( $width, $height );
			$img_link = get_the_post_thumbnail( $id, $thumb_size, array( 'class' => 'bizz-image ' . esc_attr( $class ) ) );
		}
	}
	// Grab the image from custom field
	else
    	$custom_field = esc_url( get_post_meta( $id, $key, true ) );

	// Automatic Image Thumbs - get first image from post attachment
	if ( empty( $custom_field ) && isset($GLOBALS['opt']['bizzthemes_auto_img']['value']) && empty( $img_link ) && ! ( is_singular() && in_the_loop() && $link == 'src' ) ) {

        if( $offset >= 1 )
			$repeat = $repeat + $offset;

        $attachments = get_children( array(	
			'post_parent' => $id,
			'numberposts' => $repeat,
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'DESC',
			'orderby' => 'menu_order date'
		));

		// Search for and get the post attachment
		if ( ! empty( $attachments ) ) {
			$counter = -1;
			foreach ( $attachments as $att_id => $attachment ) {
				$counter++;
				if ( $counter < $offset )
					continue;

				if ( isset($GLOBALS['opt']['bizzthemes_thumb_show']['value']) && isset($GLOBALS['opt']['bizzthemes_auto_img']['value']) ) {
					// Dynamically resize the post thumbnail
					$vt_crop = $GLOBALS['opt']['bizzthemes_resize']['value'];
					if ( $vt_crop == 'true' ) $vt_crop = true; else $vt_crop = false;
					$vt_image = vt_resize( $att_id, '', $width, $height, $vt_crop, $cropp );

					// Set fields for output
					$custom_field = esc_url( $vt_image['url'] );
					$width = $vt_image['width'];
					$height = $vt_image['height'];
				}
				else {
					$src = wp_get_attachment_image_src( $att_id, 'large', true );
					$custom_field = esc_url( $src[0] );
					$attachment_id[] = $att_id;
					$src_arr[] = $custom_field;
				}
				$thumb_id = $att_id;
				$is_auto_image = true;
			}
		}
		// Get the first img tag from content
		else {
			$first_img = '';
			$post = get_post( $id );
			ob_start();
			ob_end_clean();
			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
			if ( !empty($matches[1][0]) ) {
				// Save Image URL
				$custom_field = esc_url( $matches[1][0] );
				// Search for ALT tag
				$output = preg_match_all( '/<img.+alt=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
				if ( !empty($matches[1][0]) )
					$alt = esc_attr( $matches[1][0] );
			}
		}
	}

	// Check if there is YouTube embed
	if ( empty( $custom_field ) && empty( $img_link ) ) {
		$embed = esc_html( get_post_meta( $id, 'embed', true ) );
		if ( $embed )
	    	$custom_field = esc_url( bizz_get_video_image( $embed ) );
	}
	
	// Return if there is no attachment or custom field set
	if ( empty( $custom_field ) && empty( $img_link ) )
		return;

	if(empty( $src_arr ) && empty( $img_link ) ) { $src_arr[] = $custom_field; }

	/* ------------------------------------------------------------------------- */
	/* BEGIN OUTPUT */
	/* ------------------------------------------------------------------------- */

    $output = '';

    // Set output height and width
    $set_width = ' width="' . esc_attr( $width ) . '" ';
    $set_height = '';

    if ( ! $noheight && 0 < $height )
    	$set_height = ' height="' . esc_attr( $height ) . '" ';

	// Set standard class
	if ( $class ) $class = 'bizz-image ' . esc_attr( $class ); else $class = 'bizz-image';

	// Do check to verify if images are smaller then specified.
	if($force == true){ $set_width = ''; $set_height = ''; }

	// WP Post Thumbnail
	if( !empty( $img_link ) ) {

		if( $link == 'img' ) {  // Output the image without anchors
			$output .= wp_kses_post( $before );
			$output .= $img_link;
			$output .= wp_kses_post( $after );
		} elseif( $link == 'url' ) {  // Output the large image
			$src = wp_get_attachment_image_src( $thumb_id, 'large', true );
			$custom_field = esc_url( $src[0] );
			$output .= $custom_field;
		} else {  // Default - output with link
			if ( ( is_single() || is_page() || $selflink == true ) && $single == false ) {
				$rel = 'rel="lightbox"';
				$href = false;
			} else {
				$href = get_permalink( $id );
				$rel = '';
			}

			$title = 'title="' . esc_attr( get_the_title( $id ) ) .'"';

			$output .= wp_kses_post( $before );
			if($href == false){
				$output .= $img_link;
			} else {
				$output .= '<a ' . $title . ' href="' . esc_url( $href ) . '" '. $rel .'>' . $img_link . '</a>';
			}

			$output .= wp_kses_post( $after );
		}
	}
	// No dynamic resizing
	else {
		foreach( $src_arr as $key => $custom_field ) {

			//Set the ID to the Attachment's ID if it is an attachment
			if( $is_auto_image == true && isset( $attachment_id[$key] ) ){
				$quick_id = $attachment_id[$key];
			} else {
			 	$quick_id = $id;
			}

			//Set custom meta
			if ($meta) {
				$alt = esc_attr( $meta );
				$title = 'title="'. esc_attr( $meta ) .'"';
			} else {
				if ($alt == '') $alt = esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) );
				$title = 'title="'. esc_attr( get_the_title( $quick_id ) ) .'"';
			}

			$img_link =  '<img src="'. esc_url( $custom_field ) . '" alt="' . esc_attr( $alt ) . '" ' . $set_width . $set_height . ' class="' . esc_attr( stripslashes( $class ) ) . '" />';

			if ( $link == 'img' ) {  // Just output the image
				$output .= wp_kses_post( $before );
				$output .= $img_link;
				$output .= wp_kses_post( $after );

			} elseif( $link == 'url' ) {  // Output the URL to original image
				if ( $vt_image['url'] || $is_auto_image ) {
					$src = wp_get_attachment_image_src( $thumb_id, 'full', true );
					$custom_field = esc_url( $src[0] );
				}
				$output .= $custom_field;

			} else {  // Default - output with link

				if ( ( is_single() || is_page() || $selflink == true ) && $single == false ) {

					// Link to the large image if single post
					if ( $vt_image['url'] || $is_auto_image ) {
						$src = wp_get_attachment_image_src( $thumb_id, 'full', true );
						$custom_field = esc_url( $src[0] );
					}

					$href = $custom_field;
					$rel = 'rel="lightbox"';
				} else {
					$href = get_permalink( $id );
					$rel = '';
				}

				$output .= wp_kses_post( $before );
				$output .= '<a href="' . esc_url( $href ) . '" ' . $rel . ' ' . $title . '>' . $img_link . '</a>';
				$output .= wp_kses_post( $after );
			}
		}
	}

	// Remove no height attribute - IE fix when no height is set
	$output = str_replace( 'height=""', '', $output );
	$output = str_replace( 'height="0"', '', $output );

	// Return or echo the output
	if ( $return == TRUE )
		return $output;
	else 
		echo $output; // Done

}

// Deprecated
function bizz_get_image($key = 'image', $width = null, $height = null, $class = "thumbnail", $quality = 90,$id = null,$link = 'src',$repeat = 1,$offset = 0,$before = '', $after = '',$single = false, $force = false, $return = false) {
	// Run new function
	bizz_image( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&quality='.$quality.'&id='.$id.'&link='.$link.'&repeat='.$repeat.'&offset='.$offset.'&before='.$before.'&after='.$after.'&single='.$single.'&fore='.$force.'&return='.$return );
	return;
}

/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 *
 * <?php
 * $thumb = get_post_thumbnail_id();
 * $image = vt_resize( $thumb, '', 140, 110, true, false );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @param string $align
 * @return array
 */
if ( ! function_exists( 'vt_resize' ) ) {
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false, $align = false ) {
		global $wp_version;
		
		// Cast $width and $height to integer
		$width = intval( $width );
		$height = intval( $height );

		// this is an attachment, so we have the ID
		if ( $attach_id ) {
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$file_path = get_attached_file( $attach_id );
		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			$file_path = parse_url( esc_url( $img_url ) );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];

			$orig_size = getimagesize( $file_path );

			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}

		$file_info = pathinfo( $file_path );

		// check if file exists
		if ( !isset( $file_info['dirname'] ) && !isset( $file_info['filename'] ) && !isset( $file_info['extension'] )  )
			return;
		
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
			return;

		$extension = '.'. $file_info['extension'];

		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

		$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width ) {
			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

				$vt_image = array (
					'url' => $cropped_img_url,
					'width' => $width,
					'height' => $height
				);
				return $vt_image;
			}

			// $crop = false or no height set
			if ( $crop == false OR !$height ) {
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					return $vt_image;
				}
			}

			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];
			
			// Check if GD Library installed
			if ( ! function_exists ( 'imagecreatetruecolor' ) ) {
			    echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
			    return;
			}

			// no cache files - let's finally resize it
			if ( function_exists( 'wp_get_image_editor' ) ) {
				$image = wp_get_image_editor( $file_path );
				if ( !is_wp_error( $image ) ) {
					if ( isset( $wp_version ) && version_compare( $wp_version, '3.5' ) >= 0 ) {
						// Get the original image size
						$size = $image->get_size();
						$orig_width = $size['width'];
						$orig_height = $size['height'];
						
						// generate new w/h if not provided
						if ($width && !$height)
							$height = floor ($orig_height * ($width / $orig_width));
						elseif ($height && !$width)
							$width = floor ($orig_width * ($height / $orig_height));
						
						$src_x = $src_y = 0;
						$src_w = $orig_width;
						$src_h = $orig_height;

						if ( $crop ) {

							$cmp_x = $orig_width / $width;
							$cmp_y = $orig_height / $height;

							// Calculate x or y coordinate, and width or height of source
							if ( $cmp_x > $cmp_y ) {
								$src_w = round( $orig_width / $cmp_x * $cmp_y );
								$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
							}
							else if ( $cmp_y > $cmp_x ) {
								$src_h = round( $orig_height / $cmp_y * $cmp_x );
								$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
							}
						}
						
						// positional cropping!
						if ($align) {
							if (strpos ($align, 't') !== false)
								$src_y = 0;
							if (strpos ($align, 'b') !== false)
								$src_y = $height - $src_h;
							if (strpos ($align, 'l') !== false)
								$src_x = 0;
							if (strpos ($align, 'r') !== false)
								$src_x = $orig_width - $src_w;
						}

						// Time to crop the image!
						$image->crop( $src_x, $src_y, $src_w, $src_h, $width, $height );

						// Now let's save the image
						$save_data = $image->save();
					}
					else {
						$image->resize( $width, $height, $crop );
						$save_data = $image->save();
					}
					
					if ( isset( $save_data['path'] ) )
						$new_img_path = $save_data['path'];
				}
			} 
			else
				$new_img_path = image_resize( $file_path, $width, $height, $crop );	
			
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);

			return $vt_image;
		}

		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $width,
			'height' => $height
		);

		return $vt_image;
	}
}

/**
 * Tidy up the image source url
 *
 * @since 6.0
 */
function cleanSource( $src ) {
	
	// remove slash from start of string
	if(strpos($src, "/") == 0)
		$src = substr($src, -(strlen($src) - 1));

	// Check if same domain so it doesn't strip external sites
	$host = str_replace( 'www.', '', $_SERVER['HTTP_HOST'] );
	if ( ! strpos( $src, $host ) )
		return $src;

	$regex = "/^((ht|f)tp(s|):\/\/)(www\.|)" . $host . "/i";
	$src = preg_replace ( $regex, '', $src );
	$src = htmlentities ( $src );

    // remove slash from start of string
    if ( strpos( $src, '/' ) === 0 )
        $src = substr ( $src, -( strlen( $src ) - 1 ) );

	return $src;
}

/**
 * RSS feed thumbnail image
 *
 * Show image in RSS feed
 * @Original Addon Author: Justin Tadlock http://justintadlock.com
 * @since 6.0
 */
if ( isset($GLOBALS['opt']['bizzthemes_image_rss']['value']) ) {
	if ( get_option( 'rss_use_excerpt' ) ) 
		add_filter( 'the_excerpt_rss', 'add_image_RSS' );
	else
		add_filter( 'the_content', 'add_image_RSS' );
}
function add_image_RSS( $content ) {

	global $post, $id;
	$blog_key = substr( md5( home_url( '/' ) ), 0, 16 );
	if ( ! is_feed() ) return $content;

	// Get the "image" from custom field
	//$image = get_post_meta($post->ID, 'image', $single = true);
	$image = bizz_image( 'return=true&link=url' ); 
	$image_width = '240';

	// If there's an image, display the image with the content
	if( $image != '' ) {
		$content = '<p style="float:right; margin:0 0 10px 15px; width:' . esc_attr( $image_width ) . 'px;">
		<img src="' . esc_url( $image ) . '" width="' . esc_attr( $image_width ) . '" />
		</p>' . $content;
		return $content;
	} else {
		// If there's not an image, just display the content
		$content = $content;
		return $content;
	}
}
