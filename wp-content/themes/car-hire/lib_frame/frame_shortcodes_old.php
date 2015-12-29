<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*

  FILE STRUCTURE:

- BUTTONS
- BOXES
  * Big Box
  * Boxes
- TOGGLE & TABS
- COLUMNS
- RANDOM
  * Highlights
  * Dividers
- PROGRAMMATIC
  * Blog
  * Popular FAQs
  * FAQ List
  * Sitemap

*/

// Enable shortcodes in widget areas
add_filter('widget_text', 'do_shortcode');

// Replace WP autop formatting
function bizz_remove_wpautop($content) { 
	$content = do_shortcode( shortcode_unautop( $content ) ); 
	$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
	return $content;
}
	
/* BUTTONS */
/*------------------------------------------------------------------*/

	/* Big Button

	- link: button link (e.g http://www.bizzthemes.com)
	- bgcolor: red, green, black, grey OR custom hex color (e.g #000000)
	- txtcolor: red, green, black, grey OR custom hex color (e.g #000000)
	- bordercolor: red, green, black, grey OR custom hex color (e.g #000000)
	/*------------------------------------------------------------------*/
	function bizz_button( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'link'      => '#',
			'bgcolor'      => '#ffffff',
			'txtcolor'      => '#555555',
			'bordercolor'      => '#C4C2BC',
		), $atts));
		$out = "
			<a href='". $link ."' class='big_button' style='background-color:". $bgcolor ."; color:". $txtcolor ."; border-color:". $bordercolor ."'><span>". do_shortcode($content) ."</span></a>
		";
		
		return $out;
	}
	add_shortcode('big_button', 'bizz_button');

	/* Alert Button

	- size: small, large
	- style: info, alert, tick, download, note
	- color: red, green, black, grey OR custom hex color (e.g #000000)
	- border: border color (e.g. red or #000000)
	- text: black (for light color background on button)
	- class: custom class
	- link: button link (e.g http://www.bizzthemes.com)
	/*------------------------------------------------------------------*/
	function bizz_shortcode_button($atts, $content = null) {
		extract(shortcode_atts(array(
			'size' => '',
			'style' => '',
			'color' => '',
			'border' => '',
			'text' => '',
			'class' => '',
			'link' => '#'
		), $atts));
		
		// Set custom background and border color
		if ( $color ) {
		
			if ( 	$color == "red" OR 
					$color == "orange" OR
					$color == "green" OR
					$color == "aqua" OR
					$color == "teal" OR
					$color == "purple" OR
					$color == "pink" OR
					$color == "silver"
					 ) {
				$class .= " ".$color;
				
				$color_output = '';
			
			} else {
				if ( $border ) 
					$border_out = $border;
				else
					$border_out = $color;
					
				$color_output = 'style="background:'.$color.';border-color:'.$border_out.'"';
				
				// add custom class
				$class .= " custom";
			}
		}
		else {
			$color_output = '';
		}

		$class_output = '';

		// Set text color
		if ( $text )
			$class_output .= ' dark';

		// Set class
		if ( $class )
			$class_output .= ' '.$class;

		// Set Size
		if ( $size )
			$class_output .= ' '.$size;
		
		
		$output = '<a href="'.$link.'"class="bizz-sc-button'.$class_output.'" '.$color_output.'><span class="bizz-'.$style.'">' . bizz_remove_wpautop( bizz_remove_wpautop($content) ) . '</span></a>';
		return $output;
	}
	add_shortcode('button', 'bizz_shortcode_button');

	/* Action Button

	- url: button link (e.g http://www.bizzthemes.com)
	- target: _blank, _self, _parent, _top
	- position: left, right
	- color: white, red, blue, green, black, grey, orange, purple
	/*------------------------------------------------------------------*/
	function bizz_action_button( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'url'     	 	=> '#',
			'target'     	=> '',
			'position'   	=> 'left',
			'color'			=> 'white'
		), $atts));
		
		$target = ( $target ) ? ' target="' .$target. '" ' : '';
			
		$out = "<span class=\"wrap-b\"><a href=\"" .$url. "\"".$target." class=\"buttons btn_" .$color. " " .$position. "\"><span class=\"left\">".do_shortcode( bizz_remove_wpautop($content) )."</span></a></span>";
		return $out;
	}
	add_shortcode('action_button', 'bizz_action_button');

/* BOXES */
/*------------------------------------------------------------------*/

	/* 
	   BIB BOX - big_box

	   Optional arguments:
	   - title:
	   - bgcolor: #ffffff, #000000 ...
	   - txtcolor: #ffffff, #000000 ...
	*/
	function bizz_big_box( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			'title'      => '',
			'bgcolor'      => '#ffffff',
			'txtcolor'      => '#555555',
		), $atts));
		$out = "
			<div class='big_box'>
				<h6 class='big_box_header' style='background-color:". $bgcolor ."; color:". $txtcolor ."'><span>". $title ."</span></h6>
				<div class='big_box_content'>". do_shortcode( bizz_remove_wpautop($content) ) ."</div> 
			</div>
		";
		
		return $out;

	}
	add_shortcode('big_box', 'bizz_big_box');

	function bizz_download_box( $atts, $content = null ) {
	   return '<div class="download_box">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('download_box', 'bizz_download_box');


	function bizz_warning_box( $atts, $content = null ) {
	   return '<div class="warning_box">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('warning_box', 'bizz_warning_box');


	function bizz_info_box( $atts, $content = null ) {
	   return '<div class="info_box">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('info_box', 'bizz_info_box');


	function bizz_notice_box( $atts, $content = null ) {
	   return '<div class="notice_box">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('notice_box', 'bizz_notice_box');

	/* 
	   BOX - box
	   
	   Optional arguments:
	   - type: info, alert, tick, download, note
	   - size: medium, large
	   - style: rounded
	   - border: none, full
	   - icon: none OR full URL to a custom icon 
	*/
	function bizz_shortcode_box($atts, $content = null) {
		extract(shortcode_atts(array(	
			'type' => 'normal',
			'size' => '',
			'style' => '',
			'border' => '',
			'icon' => ''
		), $atts)); 
		
		if ( $icon == "none" )  
			$custom = ' style="padding-left:15px;background-image:none;"';
		elseif ( $icon )  
			$custom = ' style="padding-left:50px;background-image:url('.$icon.'); background-repeat:no-repeat; background-position:20px 45%;"';
		else
			$custom = '';
			
		return '<p class="bizz-sc-box '.$type.' '.$size.' '.$style.' '.$border.'"'.$custom.'>' . bizz_remove_wpautop($content) . '</p>';
	}
	add_shortcode('box', 'bizz_shortcode_box');


	/* TOGGLE & TABS */
	/*------------------------------------------------------------------*/
	function bizz_toggle_content( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title'      => '',
		), $atts));
		
		$content = preg_replace('#<br\s?/?>#is', '', $content);
		
		$out = '<h3 class="toggle"><a href="#">' .$title. '</a></h3>';
		$out .= '<div class="toggle_content" style="display: none;">';
		$out .= '<div class="block">';
		$out .= do_shortcode( bizz_remove_wpautop($content) );
		$out .= '</div>';
		$out .= '</div>';
		
	   return $out;
	}
	add_shortcode('toggle', 'bizz_toggle_content');

	function bizz_tab( $atts, $content = null ) {
		
		$out = '<div class="tab-content">' .do_shortcode( bizz_remove_wpautop($content) ). '</div>';
		
		return $out;
	}
	add_shortcode('tab', 'bizz_tab');

	function bizz_tabs_content( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'tab1'      => '',
			'tab2'      => '',
			'tab3'      => '',
			'tab4'      => '',
			'tab5'      => '',
			'tab6'      => '',
			'tab7'      => '',
			'tab8'      => '',
			'tab9'      => '',
			'tab10'     => '',
		), $atts));
		
		$content = preg_replace('#<br\s?/?>#is', '', $content);
		
			// tab navigation items
			if ($tab1<>"") { $tabular = "<li><a href='#' class='current'><span>".$tab1."</span></a></li>"; }
			if ($tab2<>"") { $tabular .= "<li><a href='#'><span>".$tab2."</span></a></li>"; }
			if ($tab3<>"") { $tabular .= "<li><a href='#'><span>".$tab3."</span></a></li>"; }
			if ($tab4<>"") { $tabular .= "<li><a href='#'><span>".$tab4."</span></a></li>"; }
			if ($tab5<>"") { $tabular .= "<li><a href='#'><span>".$tab5."</span></a></li>"; }
			if ($tab6<>"") { $tabular .= "<li><a href='#'><span>".$tab6."</span></a></li>"; }
			if ($tab7<>"") { $tabular .= "<li><a href='#'><span>".$tab7."</span></a></li>"; }
			if ($tab8<>"") { $tabular .= "<li><a href='#'><span>".$tab8."</span></a></li>"; }
			if ($tab9<>"") { $tabular .= "<li><a href='#'><span>".$tab9."</span></a></li>"; }
			if ($tab10<>"") { $tabular .= "<li><a href='#'><span>".$tab10."</span></a></li>"; }
		
		$out = "
			<div class=\"tabs-container\">
				<ul class=\"tabs\">".$tabular."</ul>
				<div class=\"panes\">
				".do_shortcode( bizz_remove_wpautop($content) )."
				</div>
			</div>
		";
		
	   return $out;
	}
	add_shortcode('tabs_container', 'bizz_tabs_content');

	/* COLUMNS */
	/*------------------------------------------------------------------*/
	function bizz_one_third( $atts, $content = null ) {
	   return '<div class="one_third">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('one_third', 'bizz_one_third');


	function bizz_one_third_last( $atts, $content = null ) {
	   return '<div class="one_third last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('one_third_last', 'bizz_one_third_last');


	function bizz_two_third( $atts, $content = null ) {
	   return '<div class="two_third">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('two_third', 'bizz_two_third');


	function bizz_two_third_last( $atts, $content = null ) {
	   return '<div class="two_third last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('two_third_last', 'bizz_two_third_last');


	function bizz_one_half( $atts, $content = null ) {
	   return '<div class="one_half">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('one_half', 'bizz_one_half');


	function bizz_one_half_last( $atts, $content = null ) {
	   return '<div class="one_half last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('one_half_last', 'bizz_one_half_last');


	function bizz_one_fourth( $atts, $content = null ) {
	   return '<div class="one_fourth">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('one_fourth', 'bizz_one_fourth');


	function bizz_one_fourth_last( $atts, $content = null ) {
	   return '<div class="one_fourth last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('one_fourth_last', 'bizz_one_fourth_last');


	function bizz_three_fourth( $atts, $content = null ) {
	   return '<div class="three_fourth">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('three_fourth', 'bizz_three_fourth');


	function bizz_three_fourth_last( $atts, $content = null ) {
	   return '<div class="three_fourth last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('three_fourth_last', 'bizz_three_fourth_last');

	function bizz_one_sixth( $atts, $content = null ) {
	   return '<div class="one_sixth">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('one_sixth', 'bizz_one_sixth');

	function bizz_one_sixth_last( $atts, $content = null ) {
	   return '<div class="one_sixth last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('one_sixth_last', 'bizz_one_sixth_last');

	function bizz_five_sixth( $atts, $content = null ) {
	   return '<div class="five_sixth">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div>';
	}
	add_shortcode('five_sixth', 'bizz_five_sixth');

	function bizz_five_sixth_last( $atts, $content = null ) {
	   return '<div class="five_sixth last">' . do_shortcode( bizz_remove_wpautop($content) ) . '</div><div class="fix"></div>';
	}
	add_shortcode('five_sixth_last', 'bizz_five_sixth_last');


	/* RANDOM */
	/*------------------------------------------------------------------*/

	// Highlights
	function bizz_ihighlight( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			'title'      => '',
			'bgcolor'      => '#FFFCD2',
			'txtcolor'      => '#000000',
		), $atts));
		$out = "
			<span class='ihighlight' style='background-color:". $bgcolor ."; color:". $txtcolor ."'>". do_shortcode( bizz_remove_wpautop($content) ) ."</span>
		";
		
		return $out;

	}
	add_shortcode('ihighlight', 'bizz_ihighlight');

	// Dividers
	function bizz_divider( $atts, $content = null ) {
	   return '<div class="divider"></div>';
	}
	add_shortcode('divider', 'bizz_divider');


	function bizz_divider_top( $atts, $content = null ) {
	   return '<div class="divider topd"><a href="#">&#94;</a></div>';
	}
	add_shortcode('divider_top', 'bizz_divider_top');

	/* PROGRAMMATIC */
	/*------------------------------------------------------------------*/

	// Blog
	function bizz_short_blog($atts){
	
		// Atts
		extract(shortcode_atts(array(
			'posts_per_page' => get_option( 'posts_per_page' ),
			'post_author' => false,
			'post_date' => true,
			'post_comments' => true,
			'post_categories' => false,
			'post_tags' => false,
			'post_edit' => false,
			'thumb_display' => false,
			'thumb_width' => 150,
			'thumb_height' => 150,
			'thumb_align' => 'alignright',
			'post_columns' => 1,
			'full_posts' => false,
			'read_more' => true
		), $atts));
		
		// Start
		ob_start();
		
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
			'ajax_pagination' => false,
			'posts_per_page' => $posts_per_page,
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
			'post_author' => $post_author,
			'post_date' => $post_date,
			'post_comments' => $post_comments,
			'post_categories' => $post_categories,
			'post_tags' => $post_tags,
			'post_edit' => $post_edit,
			'thumb_display' => $thumb_display,
			'thumb_selflink' => false,
			'thumb_width' => $thumb_width,
			'thumb_height' => $thumb_height,
			'thumb_align' => $thumb_align,
			'thumb_cropp' => 'c',
			'thumb_filter' => '',
			'thumb_sharpen' => '',
			'post_columns' => $post_columns,
			'remove_posts' => false,
			'full_posts' => $full_posts,
			'read_more' => $read_more,
			'read_more_text' => __( 'Continue reading', 'bizzthemes' ),
		);
		the_widget('Bizz_Widget_Query_Posts', $defaults, array( 'before_widget' => '<div class="widget">', 'after_widget' => '</div>', 'before_title' => '<h1 class="title"><span>', 'after_title' => '</span></h1>' ));
		
		return ob_get_clean();
	}
	add_shortcode('blog', 'bizz_short_blog');

	// Popular FAQs
	function bizz_short_faq_popular( $atts, $content = null ) {

		ob_start();

		bizz_faqs_popular_list();

		$out = ob_get_contents();
		ob_end_clean();
		
		return $out;
	}
	add_shortcode('faq_popular', 'bizz_short_faq_popular');

	// FAQ List
	function bizz_short_faq_list( $atts, $content = null ) {
		
		ob_start();
	?>
		<ul class="faq-section">
			<?php bizz_faqs_list(); ?>
		</ul>
	<?php
		$out = ob_get_contents();
		ob_end_clean();
		
		return $out;
	}
	add_shortcode('faq_list', 'bizz_short_faq_list');

	// Sitemap
	function bizz_short_sitemap( $atts, $content = null ) {
		
		ob_start();
	?>
		<h3><?php _e('Pages:', 'bizzthemes') ?></h3>
		<ul><?php wp_list_pages('sort_column=menu_order&title_li=' ); ?></ul>
		<h3><?php _e('Categories:', 'bizzthemes') ?></h3>
		<ul><?php wp_list_categories('title_li=&show_count=1') ?></ul>
	<?php
		$out = ob_get_contents();
		ob_end_clean();
		
		return $out;
	}
	add_shortcode('sitemap', 'bizz_short_sitemap');

	/* SOCIAL */
	/*------------------------------------------------------------------*/

	/* Twitter button - twitter */
	/*

	Source: http://twitter.com/goodies/tweetbutton

	Optional arguments:
	 - style: vertical, horizontal, none ( default: vertical )
	 - url: specify URL directly 
	 - source: username to mention in tweet
	 - related: related account 
	 - text: optional tweet text (default: title of page)
	 - float: none, left, right (default: left)
	 - lang: fr, de, es, js (default: english)
	*/
	function bizz_shortcode_twitter($atts, $content = null) {
		extract(shortcode_atts(array(	'url' => '',
										'style' => 'vertical',
										'source' => '',
										'text' => '',
										'related' => '',
										'lang' => '',
										'float' => 'left'), $atts));
		$output = '';

		if ( $url )
			$output .= ' data-url="'.$url.'"';
			
		if ( $source )
			$output .= ' data-via="'.$source.'"';
		
		if ( $text ) 
			$output .= ' data-text="'.$text.'"';

		if ( $related ) 			
			$output .= ' data-related="'.$related.'"';

		if ( $lang ) 			
			$output .= ' data-lang="'.$lang.'"';
		
		$output = '<div class="bizz-sc-twitter '.$float.'"><a href="'.esc_url('twitter.com/share').'" class="twitter-share-button"'.$output.' data-count="'.$style.'">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>';	
		return $output;

	}
	add_shortcode('twitter', 'bizz_shortcode_twitter');

	/* Digg Button - digg */
	/*

	Source: http://about.digg.com/button

	Optional arguments:
	 - link: specify URL directly 
	 - title: specify a title (must add link also)
	 - style: medium, large, compact, icon (default: medium)
	 - float: none, left, right (default: left)
	 
	*/
	function bizz_shortcode_digg($atts, $content = null) {
		extract(shortcode_atts(array(	'link' => '',
										'title' => '',
										'style' => 'medium',
										'float' => 'left'), $atts));
		$output = "		
		<script type=\"text/javascript\">
		(function() {
		var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
		s.type = 'text/javascript';
		s.async = true;
		s.src = 'http://widgets.digg.com/buttons.js';
		s1.parentNode.insertBefore(s, s1);
		})();
		</script>		
		";
		
		// Add custom URL
		if ( $link ) {
			// Add custom title
			if ( $title ) 
				$title = '&amp;title='.urlencode( $title );
				
			$link = ' href="http://digg.com/submit?url='.urlencode( $link ).$title.'"';
		}
		
		if ( $style == "large" )
			$style = "Large";
		elseif ( $style == "compact" )
			$style = "Compact";
		elseif ( $style == "icon" )
			$style = "Icon";
		else
			$style = "Medium";		
			
		$output .= '<div class="bizz-digg '.$float.'"><a class="DiggThisButton Digg'.$style.'"'.$link.'></a></div>';
		return $output;

	}
	add_shortcode('digg', 'bizz_shortcode_digg');


	/* Facebook Like Button - fblike */
	/*

	Source: http://developers.facebook.com/docs/reference/plugins/like

	Optional arguments:
	 - style: standard, button (default: standard)
	 
	*/
	function bizz_shortcode_fblike($atts, $content = null) {
		extract(shortcode_atts(array( 'style' => 'standard' ), $atts));
		global $post;
			
		if ( $style == "button" )
			$style = "button_count";
		else
			$style = "standard";		
			
		$output = '
		<div class="bizz-fblike">
			<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=216075805092700&amp;xfbml=1"></script><fb:like href="'. urlencode(get_permalink($post->ID)) . '" send="true" width="450" layout="'.$style.'" show_faces="false" font=""></fb:like>
		</div>
		';
		return $output;

	}
	add_shortcode('fblike', 'bizz_shortcode_fblike');

	/* Facebook Share Button - fbshare */
	/*
	Optional arguments:
	 - style: button, icon_link, icon (button standard)
	 
	*/
	function bizz_shortcode_fbshare($atts, $content = null) {
		extract(shortcode_atts(array( 'type' => 'button'), $atts));
		global $post;
		$output = '
		<a name="fb_share" type="'.$type.'" share_url="'.urlencode(get_permalink($post->ID)).'" class="bizz-fbshare">' . bizz_remove_wpautop($content) . '</a> 
		<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>	
		';
		return $output;
	}
	add_shortcode('fbshare', 'bizz_shortcode_fbshare');

	/* Feddburner Feed Count - feedburner */
	/*
	Optional arguments:
	 - name: BizzThemes (name of your feedburner feed - http://feeds.feedburner.com/{your feed name})
	 
	*/
	function bizz_feedburner($atts, $content = null){
		extract(shortcode_atts(array(
			"name" => 'name'
		), $atts));
		$output = "<a href='http://feeds.feedburner.com/{$name}'><img src='http://feeds.feedburner.com/~fc/{$name}?bg=99CCFF&amp;fg=444444&amp;anim=0' height='26' width='88' style='border:0' alt='' />
	</a>";
		return $output;
	}
	add_shortcode('feedburner','bizz_feedburner');

	/* StumbleUpon - stumble */
	function bizz_stumble($atts, $content = null){
		$output = "<script src='http://www.stumbleupon.com/hostedbadge.php?s=5'></script>";
		return $output;
	}
	add_shortcode('stumble','bizz_stumble');

	/* Google Buzz - buzz */
	function bizz_buzz($atts, $content = null){
		$output = "<a title='Post to Google Buzz' class='google-buzz-button' href='http://www.google.com/buzz/post' data-button-style='normal-count'></a>
	<script type='text/javascript' src='http://www.google.com/buzz/api/button.js'></script>";
		return $output;
	}
	add_shortcode('buzz','bizz_buzz');
	
	/* ICONS */
	/*------------------------------------------------------------------*/
	
	/* Icon - tick */
	function bizz_icon_tick($atts, $content = null){
		$output = '<span class="ico-yesno ico-tick"><!----></span>';
		return $output;
	}
	add_shortcode('icon_tick','bizz_icon_tick');
	
	/* Icon - cross */
	function bizz_icon_cross($atts, $content = null){
		$output = '<span class="ico-yesno ico-cross"><!----></span>';
		return $output;
	}
	add_shortcode('icon_cross','bizz_icon_cross');

