<?php

/*

  FILE STRUCTURE:

- Custom Post Type icons
- Custom Post Type init
- Columns for post types
- Post type demo posts
- Custom Post Type Metabox Setup

*/

/* Custom Post Type init */
/*------------------------------------------------------------------*/
function bizz_slides_post_types_init() {

	register_post_type( 'bizz_slides',
        array(
        	'label' 				=> __('Slides', 'bizzthemes'),
			'labels' 				=> array(	
				'name' 					=> __('Slides', 'bizzthemes'),
				'singular_name' 		=> __('Slides', 'bizzthemes'),
				'add_new' 				=> __('Add New', 'bizzthemes'),
				'add_new_item' 			=> __('Add New Slide', 'bizzthemes'),
				'edit' 					=> __('Edit', 'bizzthemes'),
				'edit_item' 			=> __('Edit Slide', 'bizzthemes'),
				'new_item' 				=> __('New Slide', 'bizzthemes'),
				'view_item'				=> __('View Slide', 'bizzthemes'),
				'search_items' 			=> __('Search Slides', 'bizzthemes'),
				'not_found' 			=> __('No Slides found', 'bizzthemes'),
				'not_found_in_trash' 	=> __('No Slides found in trash', 'bizzthemes'),
				'parent' 				=> __('Parent Slide', 'bizzthemes' ),
			),
            'description' => __( 'This is where you can create new slides for your site.', 'bizzthemes' ),
            'public' => true,
            'show_ui' => true,
			'menu_icon' => 'dashicons-slides',
            'capability_type' => 'post',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => false,
            'rewrite' => array( 'slug' => 'slides', 'with_front' => false ),
            'query_var' => true,
            'has_archive' => 'slides',
            'supports' => array(	
				'title', 'page-attributes'
			),
        )
    );

}
add_action( 'init', 'bizz_slides_post_types_init' );

/* Columns for post types */
/*------------------------------------------------------------------*/
function bizz_slides_edit_columns($columns){
	$columns['cb'] 						= '<input type=\'checkbox\' />';
	$columns['title'] 					= __('Slide Title', 'bizzthemes');
	
	return $columns;
}
add_filter('manage_edit-bizz_slides_columns','bizz_slides_edit_columns');

function bizz_slides_custom_columns($column){
	global $post;
	switch ($column){
		// empty
	}
}
add_action('manage_posts_custom_column', 'bizz_slides_custom_columns', 2);

/* Post type demo posts */
/*------------------------------------------------------------------*/
function bizz_slides_demo_posts() {
	
	if (get_option('bizz_slides_demo_complete') != 'true') {

		// INSERT POSTS
		$demo_post = array(
				"post_title"	=>	'Slider Example 3',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		At vero eos et accusamus et iusto odio dignissimos ducimus qui  blanditiis praesentium voluptatum deleniti atque corrupti quos dolores  et quas molestias excepturi sint occaecati cupiditate non provident,  similique sunt in culpa qui officia deserunt mollitia animi, id est  laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita  distinctio. Nam libero tempore, cum soluta nobis est eligendi optio  cumque nihil impedit quo minus id quod maxime placeat facere possimus,  omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem  quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet  ut et voluptates repudiandae sint et molestiae non recusandae. Itaque  earum rerum hic tenetur a sapiente delectus, ut aut reiciendis  voluptatibus maiores alias consequatur aut perferendis doloribus  asperiores repellat.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		At vero eos et accusamus et iusto odio dignissimos ducimus qui  blanditiis praesentium voluptatum deleniti atque corrupti quos dolores  et quas molestias excepturi sint occaecati cupiditate non provident,  similique sunt in culpa qui officia deserunt mollitia animi, id est  laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita  distinctio. Nam libero tempore, cum soluta nobis est eligendi optio  cumque nihil impedit quo minus id quod maxime placeat facere possimus,  omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem  quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet  ut et voluptates repudiandae sint et molestiae non recusandae. Itaque  earum rerum hic tenetur a sapiente delectus, ut aut reiciendis  voluptatibus maiores alias consequatur aut perferendis doloribus  asperiores repellat.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		$demo_post = array(
				"post_title"	=>	'Slider Example 2',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		Id ius dicam aeterno. Et graece saperet euripidis eum, tota labores luptatum eum eu. Usu te brute volutpat, ex scripta intellegebat pro. An per dictas omnium fastidii. Cu nam percipit forensibus.

		Cu has erat idque democritum. Eu his meis numquam, his in bonorum eloquentiam. Meliore vivendum explicari ius ea. His te integre meliore adolescens, sonet dolorem scriptorem ius id.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		Id ius dicam aeterno. Et graece saperet euripidis eum, tota labores luptatum eum eu. Usu te brute volutpat, ex scripta intellegebat pro. An per dictas omnium fastidii. Cu nam percipit forensibus.

		Cu has erat idque democritum. Eu his meis numquam, his in bonorum eloquentiam. Meliore vivendum explicari ius ea. His te integre meliore adolescens, sonet dolorem scriptorem ius id.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		$demo_post = array(
				"post_title"	=>	'Slider Example 1',
				"post_status"	=>	'publish',
				"post_type"	    =>	'bizz_slides',
				"post_content"	=>	'
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque sed felis. Aliquam sit amet felis. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Etiam augue pede, molestie eget, rhoncus at,  convallis ut, eros. Aliquam pharetra. Nulla in tellus eget odio  sagittis blandit. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		$add_demo_post = wp_insert_post( $demo_post );
		$post_meta_data = array(
				"meta_key"		=>	'bizzthemes_slide_textarea',
				"meta_value"	=>	'
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque sed felis. Aliquam sit amet felis. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Etiam augue pede, molestie eget, rhoncus at,  convallis ut, eros. Aliquam pharetra. Nulla in tellus eget odio  sagittis blandit. Mauris semper, velit semper laoreet dictum, quam diam dictum urna, nec placerat elit nisl in quam.

		Dicit altera efficiendi an duo. Vis no libris bonorum lobortis, facete bonorum nec et, ne enim eruditi sea. Sed audiam debitis an, dicta putant malorum vix et. No quo quod tractatos reprehendunt, mea mundi mollis accumsan ex, inani vivendo signiferumque te sed. Est perpetua reprimique ex, at dicit choro suscipiantur pri, ei vidisse eloquentiam quo.
				'
		);
		add_post_meta($add_demo_post, $post_meta_data['meta_key'], $post_meta_data['meta_value'], true);
		
		//installation complete
		update_option('bizz_slides_demo_complete', 'true');
	}
	
}
add_action( 'init', 'bizz_slides_demo_posts', 0 );

/* Custom Post Type Metabox Setup */
/*------------------------------------------------------------------*/
add_filter( 'bizz_meta_boxes', 'bizz_slides_metaboxes' );
function bizz_slides_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_slides_meta',
		'title' => __('Slide Details', 'bizzthemes'),
		'pages' => array( 'bizz_slides' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Full width', 'bizzthemes'),
				'desc' => __('Force full width for images and videos. Title and content will not be shown.', 'bizzthemes'),
				'id' => $prefix . 'slide_full',
				'type' => 'checkbox'
			),
			array(
				'name' => __('Upload slide image', 'bizzthemes'),
				'desc' => __('Upload image for this slide. It will be floated left from your slide content.', 'bizzthemes'),
				'id' => $prefix . 'slide_img',
				'type' => 'file'
			),
			array(
				'name' => 'Embed code for slide video',
				'desc' => 'Enter whole embed video code into this area. Video will be floated left from your slide content.',
				'id' => $prefix . 'slide_vid',
				'type' => 'textarea_small'
			),
			array(
				'name' => __('Slide content'),
				'desc' => __('Enter some content into this area. It will only appear if you do not upload slide image nor embed video code above.'),
				'id' => $prefix . 'slide_textarea',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 5,
				)
			),
		)
	);
		
	return $meta_boxes;
	
}

