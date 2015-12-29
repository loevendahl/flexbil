<?php 
				
// loop
	function bizz_hook_before_content($args = '') { 
		do_action( 'bizz_hook_before_content', $args ); 
	}
	function bizz_hook_after_content($args = '') { 
		do_action( 'bizz_hook_after_content', $args ); 
	}
	function bizz_hook_before_post_box($args = '') { 
		do_action( 'bizz_hook_before_post_box', $args ); 
	}
	function bizz_hook_post_box_top($args = '') { 
		do_action( 'bizz_hook_post_box_top', $args ); 
	}
	function bizz_hook_post_box_bottom($args = '') { 
		do_action( 'bizz_hook_post_box_bottom', $args ); 
	}
	function bizz_hook_after_post_box($args = '') { 
		do_action( 'bizz_hook_after_post_box', $args ); 
	}
	function bizz_hook_after_headline($args = '') { 
		do_action( 'bizz_hook_after_headline', $args ); 
	}
	function bizz_hook_query_after_headline($args = '') { 
		do_action( 'bizz_hook_query_after_headline', $args ); 
	}
	function bizz_hook_loop_content($args = '') { 
		do_action( 'bizz_hook_loop_content', $args ); 
	}
	function bizz_hook_query_content($args ='') {
		do_action( 'bizz_hook_query_content', $args ); 
	}
	function bizz_hook_before_post($post_count = false) {
	    do_action('bizz_hook_before_post', $post_count);
	}
	function bizz_hook_after_post($post_count = false) {
	    do_action('bizz_hook_after_post', $post_count);
	}
	
// query

	add_action( 'bizz_hook_query_content', 'bizz_post_meta_query' );
	function bizz_post_meta_query($args) {
		bizz_post_meta($args);
	}
	add_action( 'bizz_hook_loop_content', 'bizz_post_meta_loop' );
	function bizz_post_meta_loop($args) {
		bizz_post_meta($args);
	}
	add_action( 'bizz_hook_query_content', 'bizz_query_content' );
	function bizz_query_content($args) {
		bizz_post_content_query($args);
	}
	add_action( 'bizz_hook_loop_content', 'bizz_loop_content' );
	function bizz_loop_content($args) {
		bizz_post_content($args);
	}

	