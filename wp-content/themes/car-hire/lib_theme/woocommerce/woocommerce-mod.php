<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* WooCommerce installation notice */
/*------------------------------------------------------------------*/
/* Declare support */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/* Disable all if WooCommerce not active
/*------------------------------------------------------------------*/
if (!class_exists("Woocommerce"))
	return false;

/* Register my own styles, remove wootheme stylesheet
/*------------------------------------------------------------------*/
/* Disable WooCommerce styles */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/* Load custom WooCommerce scripts */
add_action( 'wp_enqueue_scripts', 'bizzwoo_theme_scripts' );
function bizzwoo_theme_scripts( ) {
	wp_enqueue_style( 'bizz-woocommerce-css', get_bloginfo('template_url').'/lib_theme/woocommerce/woocommerce-mod.css');
	wp_enqueue_script( 'bizz-woocommerce-js', get_bloginfo('template_url').'/lib_theme/woocommerce/woocommerce-mod.js', array('jquery'), '', true);
}

/* Output the WooCommerce Breadcrumb
/*------------------------------------------------------------------*/
add_filter( 'woocommerce_breadcrumb_defaults', 'bizz_wc_breadcrumb_filter' );
function bizz_wc_breadcrumb_filter( $defaults = array() ) {

	$defaults = array(
		'delimiter'  => '',
		'wrap_before'  => '<ol class="breadcrumb">',
		'wrap_after' => '</ol>',
		'before'   => '<li>',
		'after'   => '</li>',
		'home'    => _x( 'Home', 'breadcrumb', 'bizzthemes' )
	);
	
	return $defaults;
}

/* Products */
/*------------------------------------------------------------------*/
// Number of columns on product archives
add_filter( 'loop_shop_columns', 'wooframework_loop_columns' );
if ( ! function_exists( 'wooframework_loop_columns' ) ) {
	function wooframework_loop_columns() {
		global $opt;
		
		if ( ! isset( $opt['bizzthemes_product_columns']['value'] ) ) {
			$cols = 3;
		} else {
			$cols = $opt['bizzthemes_product_columns']['value'];
		}
		return $cols;
	}
}

// Number of products per page
add_filter( 'loop_shop_per_page', 'wooframework_products_per_page' );
if ( ! function_exists( 'wooframework_products_per_page' ) ) {
	function wooframework_products_per_page() {
		global $opt;
		
		if ( isset( $opt['bizzthemes_products_per_page']['value'] ) ) {
			return $opt['bizzthemes_products_per_page']['value'];
		}
	}
}

// WooCommerce feature check
add_action( 'wp_head', 'wooframework_feature_check' );
if ( ! function_exists( 'wooframework_feature_check' ) ) {
	function wooframework_feature_check() {
		global $opt;
				
		if ( !isset( $opt['bizzthemes_product_tabs']['value'] ) ) {
			add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
		}
		if ( !isset( $opt['bizzthemes_related_products']['value'] ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
		if ( !isset( $opt['bizzthemes_archives_add_to_cart']['value'] ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}
	}
}
function woo_remove_product_tabs( $tabs ) {
    $tabs = array();
    return $tabs;
}

// Display 3 related products unless specified otherwise in theme options
if (!function_exists('woocommerce_output_related_products')) {
	function woocommerce_output_related_products() {
		global $opt;
		
		$products_max = $opt['bizzthemes_related_products_maximum']['value'];
	    woocommerce_related_products( array(
			'posts_per_page' => $products_max,
			'columns'        => $opt['bizzthemes_product_columns']['value']
		) );
	
	}
}

// Replace the default upsell function with our own which displays 3x3 instead of 2x2
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerceframework_upsell_display', 15 );
if (!function_exists('woocommerceframework_upsell_display')) {
	function woocommerceframework_upsell_display() {
		global $opt;
				
	    woocommerce_upsell_display( -1, ( $opt['bizzthemes_product_columns']['value'] ) );
	}
}

// Custom place holder
add_filter( 'woocommerce_placeholder_img_src', 'wooframework_wc_placeholder_img_src' );
if ( ! function_exists( 'wooframework_wc_placeholder_img_src' ) ) {
	function wooframework_wc_placeholder_img_src( $src ) {
		global $opt;
		
		if ( isset( $opt['bizzthemes_placeholder_url']['value'] ) && '' != $opt['bizzthemes_placeholder_url']['value'] ) {
			$src = $opt['bizzthemes_placeholder_url'];
		}
		else {
			$src = get_template_directory_uri() . '/lib_theme/images/wc-placeholder.png';
		}
		return esc_url( $src );
	}
}


/* Layout */
/*------------------------------------------------------------------*/
// Adjust markup on all woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'woocommerce_theme_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'woocommerce_theme_after_content', 20 );
if ( ! function_exists( 'woocommerce_theme_before_content' ) ) {
	function woocommerce_theme_before_content() {
		global $opt;
				
		// columns
		if ( ! isset( $opt['bizzthemes_product_columns']['value'] ) ) {
			$columns = 'woocommerce-columns-3';
		} else {
			$columns = 'woocommerce-columns-' . ( $opt['bizzthemes_product_columns']['value'] );
		}
		
		// sidebar?
		if ( ( is_shop() || is_product_category() || is_product_tag() ) && !isset( $opt['bizzthemes_archives_fullwidth']['value'] ) ) {
			$addclass = 'col-md-9';
		}
		elseif ( is_product() && !isset( $opt[ 'bizzthemes_products_fullwidth' ]['value'] ) ) {
			$addclass = 'col-md-9';
		}
		else {
			$addclass = 'col-md-12 col';
		}
		?>
	    <div id="content" class="col-full clearfix <?php echo esc_attr( $columns ); ?>">
			<?php do_action( 'wc_bizz_sidebar_before' ); ?>
	        <div id="main" class="<?php echo esc_attr( $addclass ); ?>">
	    <?php
	}
}

if ( ! function_exists( 'woocommerce_theme_after_content' ) ) {
	function woocommerce_theme_after_content() {
		?>
			</div><!-- /#main -->
			<?php do_action( 'wc_bizz_sidebar_after' ); ?>
	    </div><!-- /#content -->
	    <?php
	}
}

// Cart
add_action( 'woocommerce_before_main_content', 'bizz_wc_cart', 21 );
function bizz_wc_cart() {
	global $woocommerce;
	
	echo '<div class="widget_bizzcart">';
	// get_product_search_form();
	?>
		<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'bizzthemes' ); ?>"><span class="price"><?php echo $woocommerce->cart->get_cart_total(); ?></span><span class="contents"><?php echo sprintf( _n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'bizzthemes' ), $woocommerce->cart->cart_contents_count );?></span></a>
	<?php
	echo '</div>';
}

/* Sidebar */
/*------------------------------------------------------------------*/
// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Add the WC sidebar in the right place and remove it from shop archives if specified
add_action( 'wc_bizz_sidebar_after', 'woocommerce_get_sidebar', 10 );
if ( ! function_exists( 'woocommerce_get_sidebar' ) ) {
	function woocommerce_get_sidebar() {
		global $opt;

		// Display the sidebar if full width option is disabled on archives
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( !isset( $opt['bizzthemes_archives_fullwidth']['value'] ) ) {
				get_sidebar();
			}
		}
		elseif ( is_product() ) {
			if ( !isset( $opt[ 'bizzthemes_products_fullwidth' ]['value'] ) ) {
				get_sidebar();
			}
		}

	}
}

// Add a class to the body if full width shop archives are specified
add_filter( 'body_class','wooframework_layout_body_class', 10 );		// Add layout to body_class output
if ( ! function_exists( 'wooframework_layout_body_class' ) ) {
	function wooframework_layout_body_class( $wc_classes ) {
		global $opt;

		$layout = '';

		// Add layout-full class to product archives if necessary
		if ( isset( $opt['bizzthemes_archives_fullwidth']['value'] ) && ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'product_brand' ) ) ) {
			$layout = 'layout-full';
		}
		// Add layout-full class to single product pages if necessary
		if ( isset( $opt['bizzthemes_products_fullwidth']['value'] ) && ( is_product() ) ) {
			$layout = 'layout-full';
		}

		// Add classes to body_class() output
		$wc_classes[] = $layout;
		return $wc_classes;
	}
}

// Product loop
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 25 );
add_action( 'woocommerce_before_shop_loop_item_title', 'bizztheme_product_defaults_wrap_open' , 20 ); //opener
add_action( 'woocommerce_after_shop_loop_item_title', 'bizztheme_product_defaults_wrap_close', 40); //closer
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 5 );
function bizztheme_product_defaults_wrap_open() {
	echo '<div class="product-details">';
}
function bizztheme_product_defaults_wrap_close() {
	echo '</div>';
}

// Product tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'bizz_woocommerce_output_data_tabs', 30 );
function bizz_woocommerce_output_data_tabs() {
	$tabs = apply_filters( 'woocommerce_product_tabs', array() );
	if ( ! empty( $tabs ) ) : ?>
		<div class="woocommerce-tabs">
			<ul class="tabs nav nav-tabs">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<li class="<?php echo $key ?>_tab">
						<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="panel entry-content" id="tab-<?php echo $key ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ) ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif;
}

/* Pagination / Search */
/*------------------------------------------------------------------*/
// Remove pagination (we're using the WooFramework default pagination)
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerceframework_pagination', 10 );
if ( ! function_exists( 'woocommerceframework_pagination' ) ) {
	function woocommerceframework_pagination() {
		if ( is_search() && is_post_type_archive() ) {
			add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
			add_filter( 'woo_pagination_args_defaults', 'woocommerceframework_woo_pagination_defaults', 10 );
		}
		bizz_wp_pagenavi();
	}
}

if ( ! function_exists( 'woocommerceframework_add_search_fragment' ) ) {
	function woocommerceframework_add_search_fragment ( $settings ) {
		$settings['add_fragment'] = '&post_type=product';
		return $settings;
	}
}

if ( ! function_exists( 'woocommerceframework_woo_pagination_defaults' ) ) {
	function woocommerceframework_woo_pagination_defaults ( $settings ) {
		$settings['use_search_permastruct'] = false;
		return $settings;
	}
}

/* Cart Fragments */
/*------------------------------------------------------------------*/
// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment' ) ) {
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		woo_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
if ( ! function_exists( 'woo_cart_link' ) ) {
	function woo_cart_link() {
		global $woocommerce;
		?>
			<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'bizzthemes' ); ?>"><span class="price"><?php echo $woocommerce->cart->get_cart_total(); ?></span><span class="contents"><?php echo sprintf( _n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'bizzthemes' ), $woocommerce->cart->cart_contents_count );?></span></a>
		<?php
	}
}

