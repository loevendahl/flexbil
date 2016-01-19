<?php

if ( ! defined( 'ABSPATH' ) ) exit;



/* Include custom post types */

/*------------------------------------------------------------------*/

locate_template( 'lib_theme/cpt/post-type-slides.php', true );

locate_template( 'lib_theme/booking/booking-init.php', true );

locate_template( 'lib_theme/woocommerce/woocommerce-mod.php', true );

/* add excerpt theme support */

function wpcodex_add_excerpt_support_for_pages() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'wpcodex_add_excerpt_support_for_pages' );

/* Include Bootstrap. */

/*------------------------------------------------------------------*/

add_filter('bizz_bootstrap3', 'enable_bizz_bootstrap3');

function enable_bizz_bootstrap3() {

	return true;

}



/* Set the content width based on the theme's design and stylesheet. */

/*------------------------------------------------------------------*/

if ( ! isset( $content_width ) )

	$content_width = 630;



/* Additional FOOTER HTML elements. */

/*------------------------------------------------------------------*/

add_action( 'bizz_sidebar_grid_after', 'bizz_theme_footer_logo' ); # $tag, $function_to_add, $priority, $accepted_args

function bizz_theme_footer_logo( $grid ) {

	global $wp_query;

		

	if ( $grid == 'footer_four' )

		echo '<div class="foot-logo">'.apply_filters('bizz_footer_logo', bizz_footer_branding( true )).'</div>';

		

}



/* HTML5 conversion. */

/*------------------------------------------------------------------*/

add_filter('bizz_doctype', 'bizz_new_doctype');

function bizz_new_doctype() {

	return '<!DOCTYPE html>';

}

add_filter('bizz_head_profile', 'bizz_new_head_profile');

function bizz_new_head_profile() {

	return;

}

add_filter('bizz_html5_article', 'bizz_html5_article_return');

function bizz_html5_article_return() {

	return 'article';

}

add_filter('bizz_html5_section', 'bizz_html5_section_return');

function bizz_html5_section_return() {

	return 'section';

}

add_filter('bizz_html5_nav', 'bizz_html5_nav_return');

function bizz_html5_nav_return() {

	return 'nav';

}

add_filter('bizz_html5_header', 'bizz_html5_header_return');

function bizz_html5_header_return() {

	return 'header';

}

add_filter('bizz_html5_aside', 'bizz_html5_aside_return');

function bizz_html5_aside_return() {

	return 'aside';

}





/* Custom post meta. */

/*------------------------------------------------------------------*/

remove_action( 'bizz_hook_loop_content', 'bizz_post_meta_loop' );

remove_action( 'bizz_hook_query_content', 'bizz_post_meta_query' );

add_action( 'bizz_hook_after_headline', 'responsive_post_meta_loop' );

add_action( 'bizz_hook_query_after_headline', 'responsive_post_meta_loop' );

function responsive_post_meta_loop($args) {

	global $post;

	

	if (isset($args[0])) $args = $args[0]; #[0] array level

	

	$post_type = get_post_type( $post );

	

	if ( $post_type == 'post' ) {



		echo "<aside class=\"headline_meta\">";

		

		if ($args['post_author'])

			echo '<span class="auth"><a href="' . get_author_posts_url(get_the_author_meta('ID') ) . '" class="auth" rel="nofollow">' . get_the_author() . '</a></span>';

		if ($args['post_comments']) {

			echo '<span class="comm"><a href="' . get_permalink() . '#comments" rel="nofollow">';

			$num_comments = get_comments_number(); // get_comments_number returns only a numeric value



			if ( comments_open() ) {

			

				if ( $num_comments == 0 )

					$comments = __('No Comments', 'bizzthemes');

				elseif ( $num_comments > 1 )

					$comments = sprintf(__('%d Comments', 'bizzthemes'), $num_comments);

				else

					$comments = __('One Comment', 'bizzthemes');



				echo '<a href="' . get_comments_link() .'">'. $comments.'</a>';

			} 

			else

				echo __('Comments are closed.', 'bizzthemes');

			

			echo '</a></span>';

		}

		if ($args['post_categories'])	

			echo seo_post_cats();

		if ($args['post_tags'])		

			echo seo_post_tags();

		if ($args['post_edit']) {	

			if (current_user_can('manage_options') && is_user_logged_in())

				edit_post_link(__('Edit', 'bizzthemes'), '<span class="edit">', '</span>');

		}

				

		echo "</aside>\n";

	

	}



}

// post date

add_action( 'bizz_hook_post_box_top', 'responsive_date_meta_loop' );

function responsive_date_meta_loop($args) {

	global $post;

	

	if (isset($args[0])) $args = $args[0]; #[0] array level

	

	$post_type = get_post_type( $post );

	

	if ( $args['post_date'] && $post_type == 'post' ) {

		echo "<div class=\"post_date\">";

		echo '<span class="month" title="' . get_the_time('Y-m-d') . '">' . get_the_time('M') . '</span>';

		echo '<span class="day" title="' . get_the_time('Y-m-d') . '">' . get_the_time('d') . '</span>';

		echo "</div>\n";

		echo "<div class=\"post_content\">";

	}



}

add_action( 'bizz_hook_post_box_bottom', 'responsive_close_post_content' );

function responsive_close_post_content($args) {

	global $post;

	

	$post_type = get_post_type( $post );

	

	if ( $args['post_date'] && $post_type == 'post' )

		echo "</div>\n";



}



/* Custom comments. */

/*------------------------------------------------------------------*/

add_action('init', 'remove_comments_rewrite');

function remove_comments_rewrite() {

    remove_action('comment_container', 'bizz_comment_container', 10, 3);

}

add_action('comment_container', 'custom_comment_container', 10, 3);

function custom_comment_container( $comment, $args, $depth ) {

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

			<?php echo get_avatar( $comment, 48 ); ?>

		</div><!-- /.meta-wrap -->

		<div class="text-right">

			<div class="comm-meta <?php if (1 == $comment->user_id) echo "authcomment"; ?>">

				<?php echo bizz_comment_meta( $args['comment_meta'] ); ?>

			</div><!-- /.comm-meta -->

			<div class="comment-entry">

			    <?php comment_text() ?>

				<?php if ( '0' == $comment->comment_approved ) : ?>

				    <p class="comment-moderation"><?php _e( $args['comment_moderation'], 'bizzthemes' ); ?></p>

				<?php endif; ?>

			</div><!-- /.comment-entry -->

			<?php if ( $args['enable_reply'] ): ?>

				<div class="comm-reply">

				<?php comment_reply_link( array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?>

				</div><!-- /.comm-reply -->

			<?php endif; ?>

		</div><!-- /.text-right -->

	</div><!-- /.comment-container -->

<?php

}

add_filter( 'cancel_comment_reply_link', 'custom_cancel_comment_reply_link', 10, 3 );

function custom_cancel_comment_reply_link($string, $link, $text) {

	$style = isset($_GET['replytocom']) ? '' : ' style="display:none;"';

	return "<a rel=\"nofollow\" id=\"cancel-comment-reply-link\" class=\"btn btn-danger\" href=\"$link\"$style>$text</a>";

}



/* Modify pagination output */

/*------------------------------------------------------------------*/

add_filter('bizz_filter_pagination', 'custom_filter_pagination');

function custom_filter_pagination() {

	global $wp_query;

	

	// custom options

	$max_page = $wp_query->max_num_pages;

	$pagenavi_options = get_option('pagenavi_options');

	

	$args['container_before'] = "<div class='pagination_area pagination-centered'>\n";

	$args['container_after'] = "</div>\n";

	$args['ul_class'] = "pagination pagination-lg";

	$args['active_class'] = "active";

	$args['prev_link'] = ( get_previous_posts_link() ) ? "<li>\n".get_previous_posts_link($pagenavi_options['prev_text'])."</li>\n" : "<li class=\"disabled\">\n<a href=\"#\">&laquo;</a></li>\n";

	$args['next_link'] = ( get_next_posts_link() ) ? "<li>\n".get_next_posts_link($pagenavi_options['next_text'], $max_page)."</li>\n" : "<li class=\"disabled\">\n<a href=\"#\">&raquo;</a></li>\n";

	$args['dotleft_text'] = "<a href='#'>...</a>\n";

	$args['dotright_text'] = "<a href='#'>...</a>\n";

	

	return $args;

}



/* WPML mods */

/*------------------------------------------------------------------*/

/*

add_filter( 'wp_nav_menu_items', 'new_nav_menu_items', 10, 2 );

function new_nav_menu_items( $items, $args ) {

	if ( function_exists( 'icl_get_languages' ) ) {

		$languages = icl_get_languages( 'skip_missing=0' );

		if ( 1 < count( $languages ) ){

			foreach ( $languages as $l ){

				if ( !$l['active'] ){

					$items = $items.'<li class="menu-item"><a href="'.$l['url'].'"><img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" /></a></li>';

				}

			}

		}

	}

	return $items;

}

*/



add_action( 'init', 'bizz_wpml_actions' );

function bizz_wpml_actions() {

	global $icl_language_switcher;

	if ( class_exists( 'SitePressLanguageSwitcher' ) ) {

		remove_filter('wp_nav_menu_items', array($icl_language_switcher, 'wp_nav_menu_items_filter'));

		add_filter('wp_nav_menu_items', 'bizz_nav_menu_items_filter', 10, 2);

	}

}

function bizz_nav_menu_items_filter($items, $args){

	global $sitepress_settings, $sitepress;



	// menu can be passed as integger or object

	if(isset($args->menu->term_id)) $args->menu = $args->menu->term_id;



	$abs_menu_id = icl_object_id($args->menu, 'nav_menu', false, $sitepress->get_default_language());



	if($abs_menu_id == $sitepress_settings['menu_for_ls']){

	

		$languages = $sitepress->get_ls_languages();

		

		if ( empty( $languages ) )

			return $items;



		$items .= '<li class="menu-item menu-item-language menu-item-language-current dropdown">';

		if(isset($args->before)){

			$items .= $args->before;

		}

		$items .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="return false">';

		if(isset($args->link_before)){

			$items .= $args->link_before;

		}

		if( $sitepress_settings['icl_lso_flags'] ){

			$items .= '<img class="iclflag" src="'.$languages[$sitepress->get_current_language()]['country_flag_url'].'" width="18" height="12" alt="'.$languages[$sitepress->get_current_language()]['translated_name'].'" title="' . esc_attr($languages[$sitepress->get_current_language()]['translated_name']) . '" />';

		}

		$items .= $languages[$sitepress->get_current_language()]['translated_name'] . ( ( count( $languages ) > 1 ) ? ' <b class="caret"></b>' : '' );

		if(isset($args->link_after)){

			$items .= $args->link_after;

		}

		$items .= '</a>';

		if(isset($args->after)){

			$items .= $args->after;

		}

                                            

		unset($languages[$sitepress->get_current_language()]);

		

		if( ! empty( $languages ) ){

			$items .= '<ul class="sub-menu submenu-languages dropdown-menu">'; 

			foreach($languages as $code => $lang){

				$items .= '<li class="menu-item menu-item-language menu-item-language-current">';

				$items .= '<a href="'.$lang['url'].'">';

				if( $sitepress_settings['icl_lso_flags'] ){

					$items .= '<img class="iclflag" src="'.$lang['country_flag_url'].'" width="18" height="12" alt="'.$lang['translated_name'].'" />';

				}

				if($sitepress_settings['icl_lso_native_lang']){

					$items .= $lang['native_name'];

				}

				if($sitepress_settings['icl_lso_display_lang'] && $sitepress_settings['icl_lso_native_lang']){

					$items .= ' (';

				}

				if($sitepress_settings['icl_lso_display_lang']){

					$items .= $lang['translated_name'];

				}

				if($sitepress_settings['icl_lso_display_lang'] && $sitepress_settings['icl_lso_native_lang']){

					$items .= ')';

				}                

				$items .= '</a>';

				$items .= '</li>';

			}

			$items .= '</ul>';

		}

		$items .= '</li>';

	}

	

	return $items;

}



/* header.php file */

/*------------------------------------------------------------------*/

add_action('header_html_build', 'add_header_html_build');

function add_header_html_build() {

	global $bizz_registered_grids;

	

	bizz_html_header();


	$grid_logic = array(

		'header_area' => $bizz_registered_grids['header_area']

	);



	echo bizz_html_build(false, false, $grid_logic);

	

	echo '

	<section id="main_area" class="clearfix">

	<div class="container clearfix">

		<div class="row">

	';

}



/* sidebar.php file */

/*------------------------------------------------------------------*/

add_action('sidebar_html_build', 'add_sidebar_html_build');

function add_sidebar_html_build() {



	$grid_logic = array(

		'sidebar_area' => array(

			'id' => '',

			'name' => '',

			'container' => '',

			'before_container' => '',

			'after_container' => '',

			'show' => 'true',

			'grids' => array(

				'main_two' => array(

					'name' => '',

					'class' => '',

					'before_grid' => '<div class="col-md-3">',

					'after_grid' => '</div>',

					'tree' => ''

				)

			)

		)

	);

	

	echo bizz_html_build(false, false, $grid_logic);

	

}



/* footer.php file */

/*------------------------------------------------------------------*/

add_action('footer_html_build', 'add_footer_html_build');

function add_footer_html_build() {

	global $bizz_registered_grids;

	

	echo '

			</div><!-- /.row -->

		</div><!-- /.container -->

	</section><!-- /#main_area -->

	';

	$grid_logic = array(

		'footer1_area' => $bizz_registered_grids['footer1_area']

	);



	echo bizz_html_build(false, false, $grid_logic);

	$grid_logic = array(

		'footer_area' => $bizz_registered_grids['footer_area']

	);



	echo bizz_html_build(false, false, $grid_logic);

	

	bizz_html_footer();

}



/* DEFAULT LAYOUT OPTIONS */

/*------------------------------------------------------------------*/



// set default layouts

$default_layouts_array = '{"theme_id":"car-hire","frame_version":"7.9.3.3","options_id":"layouts","options_value":{"all_widgets":[{"option_name":"","option_value":false,"type":"widget"},{"option_name":"widget_pages","option_value":{"2":{"title":"Pages","sortby":"post_title","exclude":""},"_multiwidget":1},"type":"widget"},{"option_name":"widget_meta","option_value":{"3":{"title":"Meta"},"_multiwidget":1},"type":"widget"},{"option_name":"widget_text","option_value":{"3":{"title":"About Us","text":"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.","filter":false},"_multiwidget":1},"type":"widget"},{"option_name":"widget_recent-comments","option_value":{"_multiwidget":1},"type":"widget"},{"option_name":"widget_rss","option_value":[],"type":"widget"},{"option_name":"widget_text_icl","option_value":false,"type":"widget"},{"option_name":"widget_bizz-comments-loop","option_value":{"2":{"type":"all","comment_header":"h3","comment_meta":"[author] [date before=\"\u00b7 \"] [link before=\"\u00b7 \"] [edit before=\"\u00b7 \"]","max_depth":"5","enable_pagination":true,"enable_reply":true,"comment_moderation":"Your comment is awaiting moderation.","reply_text":"Reply","login_text":"Log in to Reply","password_text":"Password Protected","pass_protected_text":"is password protected. Enter the password to view comments.","sing_comment_text":"comment","plu_comment_text":"comments","sing_trackback_text":"trackback","plu_trackback_text":"trackbacks","sing_pingback_text":"pingback","plu_pingback_text":"pingbacks","sing_ping_text":"ping","plu_ping_text":"pings","no_text":"No","to_text":"to","reverse_top_level":false,"comments_closed":"","title":false},"_multiwidget":1},"type":"widget"},{"option_name":"widget_widgets-reloaded-bizz-c-form","option_value":{"_multiwidget":1},"type":"widget"},{"option_name":"widget_widgets-reloaded-bizz-logo","option_value":{"2":{"custom_logo":"def_title","upload_logo":"","custom_link":"http:\/\/","title":false},"_multiwidget":1},"type":"widget"},{"option_name":"widget_widgets-reloaded-bizz-loop","option_value":{"2":{"post_date":1,"post_comments":1,"post_categories":1,"post_tags":1,"post_columns":"1","read_more":1,"read_more_text":"Continue reading","enable_pagination":1,"thumb_width":"150","thumb_height":"150","thumb_align":"alignright","thumb_cropp":"c","thumb_filter":"","thumb_sharpen":"","post_author":0,"post_edit":0,"post_meta":0,"thumb_display":0,"thumb_single":0,"thumb_selflink":0,"remove_posts":0,"full_posts":0,"ajax_pagination":0,"title":false},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz-query-posts","option_value":false,"type":"widget"},{"option_name":"widget_widgets-reloaded-bizz-search","option_value":{"_multiwidget":1},"type":"widget"},{"option_name":"widget_twitter","option_value":{"2":{"account":"BizzThemes","title":"Twitter Updates","show":5,"hidereplies":false,"beforetimesince":"","twitter_follow":"Follow Us on Twitter"},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz_slider","option_value":{"2":{"post_type":"bizz_slides","order":"ASC","orderby":"menu_order","number":"10","slidecontrols":1,"pausehover":1,"nextprev":1,"height":"365","start":"1","slidespeed":"7","animationspeed":"6","title":false,"include":[],"exclude":[]},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz_adspace","option_value":{"2":{"title":"Buy Car Hire","adalign":"alignleft","adcode":"","image":"http:\/\/demo.bizzthemes.com\/car-hire\/files\/2013\/04\/BizzThemes_banner_200x125-1.png","href":"http:\/\/bizzthemes.com\/amember\/signup.php?price_group=48","alt":"Buy Car Hire theme"},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz_social","option_value":{"2":{"title":"Social Links","facebook":"http:\/\/bizzthemes.com","twitter":"http:\/\/bizzthemes.com","flickr":"http:\/\/bizzthemes.com","youtube":"http:\/\/bizzthemes.com","linkedin":"http:\/\/bizzthemes.com","google":"http:\/\/bizzthemes.com","dribbble":"http:\/\/bizzthemes.com","tumblr":"http:\/\/bizzthemes.com"},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz_cinfo","option_value":{"2":{"title":false,"small_meta":"Email","small":"info@bizzthemes.com","large_meta":"Call Us","large":"+386 31 333 555","small_link":"mailto:info@bizzthemes.com","large_link":"tel:38631333555"},"_multiwidget":1},"type":"widget"},{"option_name":"widget_widgets-reloaded-bizz-nav-menu","option_value":{"2":{"title":false,"menu":"13","container":"div","container_id":"","container_class":"","menu_id":"","menu_class":"nav-menu","depth":"0","before":"","after":"","link_before":"","link_after":"","fallback_cb":"wp_page_menu","walker":"","use_desc_for_title":0,"vertical":0},"_multiwidget":1},"type":"widget"},{"option_name":"widget_bizz_booking","option_value":{"2":{"title":"Rent a car:","intro":"Even through walk-ins are ok, we encourage your to contact us via the form below to set your appointment. You will be contacted within 24 hours for confirmation."},"_multiwidget":1},"type":"widget"}],"widget_posts":[{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:7:\"pages-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:14:\"bizz_adspace-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:9:\"twitter-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:12:\"bizz_cinfo-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:6:\"text-3\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:28:\"widgets-reloaded-bizz-loop-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:33:\"widgets-reloaded-bizz-bookmarks-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:28:\"widgets-reloaded-bizz-logo-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:32:\"widgets-reloaded-bizz-nav-menu-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:6:\"meta-3\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:33:\"widgets-reloaded-bizz-bookmarks-3\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_index","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:13:\"bizz_social-2\";s:9:\"condition\";s:8:\"is_index\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_front_page","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:14:\"bizz_booking-2\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"all","post_excerpt":"is_front_page","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:13:\"bizz_slider-2\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"post","post_excerpt":"is_singular","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:20:\"bizz-comments-form-2\";s:9:\"condition\";s:11:\"is_singular\";s:4:\"item\";s:4:\"post\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"},{"post_title":"post","post_excerpt":"is_singular","post_status":"publish","post_type":"bizz_widget","post_content":"a:5:{s:9:\"widget-id\";s:20:\"bizz-comments-loop-2\";s:9:\"condition\";s:11:\"is_singular\";s:4:\"item\";s:4:\"post\";s:6:\"parent\";s:5:\"false\";s:4:\"show\";s:4:\"true\";}","post_content_filtered":"car-hire","type":"widgets"}],"grid_posts":[{"post_title":"all","post_excerpt":"is_front_page","post_status":"publish","post_type":"bizz_grid","post_content":"a:4:{s:11:\"header_area\";a:5:{s:2:\"id\";s:11:\"header_area\";s:4:\"name\";s:11:\"Header Area\";s:4:\"show\";s:4:\"true\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";}s:13:\"featured_area\";a:5:{s:2:\"id\";s:13:\"featured_area\";s:4:\"name\";s:13:\"Featured Area\";s:4:\"show\";s:4:\"true\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";}s:9:\"main_area\";a:5:{s:2:\"id\";s:9:\"main_area\";s:4:\"name\";s:9:\"Main Area\";s:4:\"show\";s:5:\"false\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";}s:11:\"footer_area\";a:5:{s:2:\"id\";s:11:\"footer_area\";s:4:\"name\";s:11:\"Footer Area\";s:4:\"show\";s:4:\"true\";s:9:\"condition\";s:13:\"is_front_page\";s:4:\"item\";s:3:\"all\";}}","post_content_filtered":"car-hire","type":"grids"}],"sidebars_widgets":[{"option_name":"sidebars_widgets","option_value":{"wp_inactive_widgets":["twitter-2","bizz_adspace-2"],"sidebar-1":["widgets-reloaded-bizz-logo-2"],"sidebar-2":["bizz_cinfo-2"],"sidebar-3":["widgets-reloaded-bizz-nav-menu-2"],"sidebar-4":["bizz_booking-2"],"sidebar-5":["bizz_slider-2"],"sidebar-6":[],"sidebar-7":["widgets-reloaded-bizz-loop-2","bizz-comments-loop-2"],"sidebar-8":["pages-2"],"sidebar-9":[],"sidebar-10":["meta-3"],"sidebar-11":["bizz_social-2"],"sidebar-12":["text-3"],"array_version":3},"type":"sidebars_widgets"}]}}';