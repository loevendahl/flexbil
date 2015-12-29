<?php

/* LOOP CLASS (originally created by Chris Pearson, Bizz 1.8: http://www.diythemes.com) */
/*------------------------------------------------------------------*/

class bizz_loop {
	/*
	function bizz_loop($args='') { // PHP 4 constructor, grr...
		global $wp_query;
		$loop = ($wp_query->is_page) ? ((is_front_page()) ? 'front' : 'page') : (($wp_query->is_home) ? 'home' : (($wp_query->is_single) ? (($wp_query->is_attachment) ? 'attachment' : 'single') : (($wp_query->is_category) ? 'category' : (($wp_query->is_tag) ? 'tag' : (($wp_query->is_tax) ? 'tax' : (($wp_query->is_archive) ? (($wp_query->is_day) ? 'day' : (($wp_query->is_month) ? 'month' : (($wp_query->is_year) ? 'year' : (($wp_query->is_author) ? 'author' : 'archive')))) : (($wp_query->is_search) ? 'search' : (($wp_query->is_404) ? 'fourohfour' : 'nothing'))))))));
		call_user_func(apply_filters('bizz_custom_loop', array($this, $loop)), array($args));
	}
	*/
	
	function __construct($args='') { // PHP 5 constructor
		global $wp_query;
		$loop = ($wp_query->is_page) ? ((is_front_page()) ? 'front' : 'page') : (($wp_query->is_home) ? 'home' : (($wp_query->is_single) ? (($wp_query->is_attachment) ? 'attachment' : 'single') : (($wp_query->is_category) ? 'category' : (($wp_query->is_tag) ? 'tag' : (($wp_query->is_tax) ? 'tax' : (($wp_query->is_archive) ? (($wp_query->is_day) ? 'day' : (($wp_query->is_month) ? 'month' : (($wp_query->is_year) ? 'year' : (($wp_query->is_author) ? 'author' : 'archive')))) : (($wp_query->is_search) ? 'search' : (($wp_query->is_404) ? 'fourohfour' : 'nothing'))))))));
		call_user_func_array(apply_filters('bizz_custom_loop', array($this, $loop)), array($args));
	}
	
	function front($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'page')), $args);
	}

	function page($args) {
		global $post;
		if (isset($args[0])) $args = $args[0]; #[0] array level
		while (have_posts()) { #wp
			the_post(); #wp
			
			$classes = 'post_box top';
			$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
			bizz_hook_before_post_box($args); #hook
			echo "\t\t\t<".apply_filters('bizz_html5_article', "div")." class=\"" . $post_classes . "\" id=\"post-" . get_the_ID() . "\">\n";
			apply_filters('bizz_html5_article', "div");
			bizz_hook_post_box_top($args); #hook
			bizz_headline_area($args);
			bizz_hook_after_headline($args); #hook
			bizz_hook_loop_content($args); #hook
			bizz_hook_post_box_bottom($args); #hook
			echo "\t\t\t</".apply_filters('bizz_html5_article', "div").">\n";
			bizz_hook_after_post_box($args); #hook

		}
	}

	function single($args) {
		global $post;
		if (isset($args[0])) $args = $args[0]; #[0] array level
		while (have_posts()) { #wp
			the_post(); #wp
			
			$classes = 'post_box top';
			$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
			bizz_hook_before_post_box($args); #hook
			echo "\t\t\t<".apply_filters('bizz_html5_article', "div")." class=\"" . $post_classes . "\" id=\"post-" . get_the_ID() . "\">\n";
			bizz_hook_post_box_top($args); #hook
			bizz_headline_area($args);
			bizz_hook_after_headline($args); #hook
			bizz_hook_loop_content($args); #hook
			bizz_hook_post_box_bottom($args); #hook
			echo "\t\t\t</".apply_filters('bizz_html5_article', "div").">\n";
			bizz_hook_after_post_box($args); #hook

		}
		
	}
	
	function archive($args) {
		if (is_paged()) $is_paged = true;
		if (isset($args[0])) $args = $args[0]; #[0] array level
		bizz_archive_headline();
		
		$post_count = 0;
		while (have_posts()) { #wp
			the_post(); #wp
			$post_count++;
			
			$e_o = ($post_count % $args['post_columns']) ? '' : ' last';
			$classes = 'post_box top bsize-' . $args['post_columns'] . $e_o;
			$post_classes = implode(' ', get_post_class($classes, get_the_ID()));
			bizz_hook_before_post_box($args); #hook
			echo "\t\t\t<".apply_filters('bizz_html5_article', "div")." class=\"" . $post_classes . "\" id=\"post-" . get_the_ID() . "\">\n";
			bizz_hook_post_box_top($args); #hook
			bizz_headline_area($args);
			bizz_hook_after_headline($args); #hook
			bizz_hook_loop_content($args); #hook
			bizz_hook_post_box_bottom($args); #hook
			echo "\t\t\t</".apply_filters('bizz_html5_article', "div").">\n";
			if ($e_o != '')
			    echo "\t\t\t\t<div class='single-sep fix'><!----></div>\n";
			bizz_hook_after_post_box($args); #hook

		}
		if (function_exists('bizz_wp_pagenavi') && $args['enable_pagination']) {
			bizz_wp_pagenavi();
		}
		
	}

	function image($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'single')), $args);
	}

	function attachment($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'single')), $args);
	}

	function category($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function tag($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function tax($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}
	
	function home($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function day($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function month($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function year($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function author($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
	}

	function search($args) {
		if (have_posts())
			call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'archive')), $args);
		else {
			global $wp_query;
			
		echo "\t<".apply_filters('bizz_html5_article', "div")." class=\"post_box top\">\n";
		echo "\t\t<".apply_filters('bizz_html5_header', "div")." class=\"headline_area\">\n";
		echo "\t\t\t<h2>".esc_html($wp_query->query_vars['s'])."</h2>\n";
		echo "\t\t</".apply_filters('bizz_html5_header', "div").">\n";
		echo "\t\t<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
		echo "\t\t\t"._e('Sorry, but no results were found.', 'bizzthemes')."\n";
		echo "\t\t</".apply_filters('bizz_html5_section', "div").">\n";
		echo "\t</".apply_filters('bizz_html5_article', "div").">\n";

		}
	}

	function fourohfour($args) {
	
		echo "\t<".apply_filters('bizz_html5_article', "div")." class=\"post_box top\">\n";
		bizz_headline_area();
		echo "\t\t<".apply_filters('bizz_html5_section', "div")." class=\"format_text\">\n";
		bizz_404_error();
		echo "\t\t</".apply_filters('bizz_html5_section', "div").">\n";
		echo "\t</".apply_filters('bizz_html5_article', "div").">\n";

	}

	function nothing($args) {
		call_user_func(apply_filters('bizz_custom_loop', array('bizz_loop', 'single')), $args);
	}
}

/**
 * class bizz_custom_loop
 *
 * Simple API for über-minimal custom loops. Supported class extension methods (which
 * correspond with potential $loop values) are: front, page, home, single, image,
 * attachment, category, tag, day, month, year, author, archive, search, fourohfour,
 * and nothing.
 * 
 * @since 1.8
 * @uses $loop a pre-calculated array from Bizz specifying which loop to run
 */
class bizz_custom_loop {
	/*
	function bizz_custom_loop() { // PHP 4 constructor
		add_filter('bizz_custom_loop', array($this, 'loop'));
	}
	*/
	
	function __construct() { // PHP 5 constructor
		add_filter('bizz_custom_loop', array($this, 'loop'));
	}

	function loop($loop) {
		return (method_exists($this, $loop[1])) ? array($this, $loop[1]) : $loop;
	}
}