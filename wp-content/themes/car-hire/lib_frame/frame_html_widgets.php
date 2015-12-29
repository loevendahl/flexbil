<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * FRAME WIDGETS
 *
 * output frame widgets
 * @original author for some widgets Justin Tadlock
 * @since 7.0
 */

/* Unregisters the default widgets. */
	add_action( 'widgets_init', 'bizz_reloaded_unregister_widgets' );

/* Loads and registers the new widgets. */
	add_action( 'widgets_init', 'bizz_reloaded_load_widgets' );
	
function bizz_reloaded_unregister_widgets() {
	#unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Search' );
}

function bizz_reloaded_load_widgets() {

	/* Load each widget file. */
	locate_template( 'lib_frame/widgets/widget-comments.php', true );
	locate_template( 'lib_frame/widgets/widget-contact-form.php', true );
	locate_template( 'lib_frame/widgets/widget-logo.php', true );
	locate_template( 'lib_frame/widgets/widget-loop.php', true );
	locate_template( 'lib_frame/widgets/widget-nav-menu.php', true );
	locate_template( 'lib_frame/widgets/widget-query-posts.php', true );
	locate_template( 'lib_frame/widgets/widget-search.php', true );
	locate_template( 'lib_frame/widgets/widget-twitter.php', true );

}
