<?php

if ( ! defined( 'ABSPATH' ) ) exit;



/* LOAD and REGISTER ALL WIDGETS from WIDGETS FOLDER */

/*------------------------------------------------------------------*/

add_action( 'widgets_init', 'bizz_load_widgets' );

	

function bizz_load_widgets() {



	/* Uregister default widgets. */

	unregister_widget( 'Bizz_Widget_Nav_Menu' );



	/* Load each widget file. */

	locate_template( 'lib_theme/widgets/widget-slider.php', true );

	locate_template( 'lib_theme/widgets/widget-ads.php', true );

	locate_template( 'lib_theme/widgets/widget-social.php', true );

	locate_template( 'lib_theme/widgets/widget-contact-info.php', true );

	locate_template( 'lib_theme/widgets/widget-nav-menu.php', true );



}



/* REGISTER WIDGETIZED GRID */

/*------------------------------------------------------------------*/

if ( function_exists('bizz_register_grids') ){
	bizz_register_grids(array(

		'id' => 'top_area',

		'name' => __('Top Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'top_full' => array(

				'name' => __('Top Full', 'bizzthemes'),

				'class' => '',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 head_area head_top">',

				'after_grid' => '</div>',

				'tree' => ''

			)
			)
			));
	bizz_register_grids(array(

		'id' => 'headline_area',

		'name' => __('Headline Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'headline_full' => array(

				'name' => __('headline', 'bizzthemes'),

				'class' => '',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 headline">',

				'after_grid' => '</div>',

				'tree' => ''

			)
			)
			));
			
	bizz_register_grids(array(

		'id' => 'header_area',

		'name' => __('Header Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

 

			'header_full' => array(

				'name' => __('Full Header Area', 'bizzthemes'),

				'class' => 'row',

				'columns' => '12',

				'before_grid' => '<div class="col-sm-12 head_area">',

				'after_grid' => '</div>',

				'show' => 'false',

				'tree' => array(
    

					'logo_area' => array(

						'name' => __('Header 1', 'bizzthemes'),

						'class' => 'col-sm-3',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'contact_area' => array(

						'name' => __('Header 2', 'bizzthemes'),

						'class' => 'col-sm-9',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					)

				)

			),

			'tnav_full' => array(

				'name' => __('Navigation Area', 'bizzthemes'),

				'class' => '',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 tnav_area">',

				'after_grid' => '</div>',

				'tree' => ''

			)

		)

	));

	bizz_register_grids(array(

		'id' => 'featured_area',

		'name' => __('Featured Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'featured_full' => array(

				'name' => __('Full Featured Area', 'bizzthemes'),

				'class' => 'row',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 feat_area">',

				'after_grid' => '</div>',

				'show' => 'false',

				'tree' => array(

					'book_area' => array(

						'name' => __('Featured 1', 'bizzthemes'),

						'class' => 'col-md-5',

						'columns' => '5',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'slide_area' => array(

						'name' => __('Featured 2', 'bizzthemes'),

						'class' => 'col-md-7',

						'columns' => '7',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					)

				)

			),

		)

	));

	bizz_register_grids(array(

		'id' => 'main_area',

		'name' => __('Main Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'main_full' => array(

				'name' => __('Main Full', 'bizzthemes'),

				'class' => '',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 main_area">',

				'after_grid' => '</div>',

				'tree' => ''

			),

			'main_col' => array(

				'name' => __('Main Col', 'bizzthemes'),

				'class' => 'row',

				'columns' => '12',

				'before_grid' => '<div class="col-md-12 col_area">',

				'after_grid' => '</div>',

				'show' => 'false',

				'tree' => array(

					'main_one' => array(

						'name' => __('Content', 'bizzthemes'),

						'class' => 'col-md-9',

						'columns' => '9',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'main_two' => array(

						'name' => __('Sidebar', 'bizzthemes'),

						'class' => 'col-md-3',

						'columns' => '3',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					)

				)

			)

		)

	));

//footer 1
	bizz_register_grids(array(

		'id' => 'footer1_area',

		'name' => __('Footer1 Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'footer1_full' => array(

				'name' => __('Footer1 Full', 'bizzthemes'),

				'class' => 'row',

				'columns' => '12',

				'before_grid' => '<div class="col-sm-12 foot_area foot_area1">',

				'after_grid' => '</div>',

				'show' => 'false',

				'tree' => array(

					'footer1_one' => array(

						'name' => __('Footer1 1', 'bizzthemes'),

						'class' => 'col-sm-2',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer1_two' => array(

						'name' => __('Footer1 2', 'bizzthemes'),

						'class' => 'col-sm-2',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer1_three' => array(

						'name' => __('Footer1 3', 'bizzthemes'),

						'class' => 'col-sm-2',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer1_four' => array(

						'name' => __('Footer1 4', 'bizzthemes'),

						'class' => 'col-sm-6',

						'columns' => '6',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					)

				)

			)

		)

	));
	
	bizz_register_grids(array(

		'id' => 'footer_area',

		'name' => __('Footer Area', 'bizzthemes'),

		'container' => 'container',

		'before_container' => '',

		'after_container' => '',

		'before_container_tree' => '<div class="row">',

		'after_container_tree' => '</div>',

		'show' => 'true',

		'grids' => array(

			'footer_full' => array(

				'name' => __('Footer Full', 'bizzthemes'),

				'class' => 'row',

				'columns' => '12',

				'before_grid' => '<div class="col-sm-12 foot_area">',

				'after_grid' => '</div>',

				'show' => 'false',

				'tree' => array(

					'footer_one' => array(

						'name' => __('Footer 1', 'bizzthemes'),

						'class' => 'col-sm-2',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer_two' => array(

						'name' => __('Footer 2', 'bizzthemes'),

						'class' => 'col-sm-0',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer_three' => array(

						'name' => __('Footer 3', 'bizzthemes'),

						'class' => 'col-sm-4',

						'columns' => '2',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					),

					'footer_four' => array(

						'name' => __('Footer 4', 'bizzthemes'),

						'class' => 'col-sm-6',

						'columns' => '6',

						'before_grid' => '',

						'after_grid' => '',

						'tree' => ''

					)

				)

			)

		)

	));

	

}

