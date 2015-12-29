<?php

/* AJAX SAVE ACTION - bizz_ajax_callback */
/*------------------------------------------------------------------*/
function bizz_ajax_callback() {
    global $wpdb, $bizz_registered_grids, $themeid;

    switch ( $_POST['type'] ) {

		case 'bizz-grids':

			// update containers
			$data = $_POST['data'];
			parse_str($data,$output);
			print_r($output);
			
			// new grids
			$containers = array();
			foreach ( $output as $key => $value) {		
				$containers[$key] 	= $value;
				$_condition 		= $value['condition'];
				$_item 				= $value['item'];
			}
			$bizz_new_grid = $containers;
			$bizz_new_grid = serialize( $bizz_new_grid );
			$bizz_new_grid = mysql_real_escape_string( $bizz_new_grid );
			
			// saved grids
			$args = array(
				'post_type' 	=> 'bizz_grid',
				'numberposts' 	=> -1,
				'orderby' 		=> 'date',
				'order' 		=> 'DESC',
				'post_status' 	=> 'publish'
			);
			$bizz_old_grids = get_posts($args);
			foreach ($bizz_old_grids as $grids) :
				if($grids->post_excerpt==$_condition && $grids->post_title==$_item){
					$bizz_old_grid_id = $grids->ID;
					$bizz_old_grid_content = unserialize($grids->post_content);
				}
			endforeach;
			
			// manage grids options
			if(!empty($bizz_old_grid_id)){
				
				// default grid
				foreach ( $bizz_registered_grids as $key => $value ) {
					$default_grids[$key] = $value;
					unset($default_grids[$key]['container']);
					unset($default_grids[$key]['grids']);
				}
				// saved grid
				$bizz_saved_grids = $bizz_new_grid;
				$bizz_saved_grids = bizz_reverse_escape( $bizz_saved_grids );
				$bizz_saved_grids = unserialize( $bizz_saved_grids );
				foreach ( $bizz_saved_grids as $key => $value ) {
					$saved_grids[$key] = $value;
					unset($saved_grids[$key]['condition']);
					unset($saved_grids[$key]['item']);
				}
				
				// Update or delete
				if ( $default_grids === $saved_grids ) # delete post object
					wp_delete_post($bizz_old_grid_id);
				else { # update post object
					$update_post = array();
					$update_post['ID'] = $bizz_old_grid_id;
					$update_post['post_content'] = $bizz_new_grid;
					$update_post['post_content_filtered'] = $themeid;
					// Update the post inside database
					wp_update_post( $update_post );
				}
				
			} 
			else {
				// Create post object
				$args = array(
					'post_type' 				=> 'bizz_grid',
					'post_title' 				=> $_item,
					'post_excerpt' 				=> $_condition,
					'post_content' 				=> $bizz_new_grid,
					'post_content_filtered' 	=> $themeid,
					'ping_status' 				=> get_option('default_ping_status'), 
					'post_status' 				=> 'publish'
				);
				// Insert the post into database
				wp_insert_post( $args );

			}
				
		die;
		break;
		
		case 'bizz-widgetlogic-delete':

			// get ajax data
			$data = $_POST['data'];
			parse_str($data,$output);
			print_r($output);
			
			// new widgetlogic
			$widgetlogics = array();
			foreach ( $output as $key => $value) {
				if ($key=='widget-id'||$key=='condition'||$key=='item'||$key=='parent')
					$widgetlogics[$key] = $value;
			}
			$bizz_new_widget = $widgetlogics;
			
			// saved widgets
			$args = array(
				'post_type' 	=> 'bizz_widget',
				'numberposts' 	=> -1,
				'post_status' 	=> 'publish'
			);
			$bizz_old_widgets = get_posts($args);
			foreach ($bizz_old_widgets as $widgets) :
				$old_widget_id = unserialize($widgets->post_content);
				if($old_widget_id['widget-id']==$bizz_new_widget['widget-id']){
					// delete widget post
					wp_delete_post( $widgets->ID, true );
				}
			endforeach;
				
		die;
		break;
		
		case 'bizz-widgetlogic':

			// get ajax data
			$data = $_POST['data'];
			parse_str($data,$output);
			print_r($output);
			
			// new widgetlogic
			$widgetlogics = array();
			foreach ( $output as $key => $value) {		
				$_condition = $key['condition'];
				$_item = $key['item'];
				$_id = $key['widget-id'];
				if ($key=='widget-id'||$key=='condition'||$key=='item'||$key=='parent'||$key=='show')
					$widgetlogics[$key] = $value;
			}
			$bizz_new_widget = $widgetlogics;
			
			// saved widgets
			$args = array(
				'post_type' 	=> 'bizz_widget',
				'numberposts' 	=> -1,
				'orderby' 		=> 'date',
				'order' 		=> 'DESC',
				'post_status' 	=> 'publish'
			);
			$bizz_old_widgets = get_posts($args);
			foreach ($bizz_old_widgets as $widgets) :
				$old_widget_content = unserialize($widgets->post_content);
				if($old_widget_content['widget-id']==$bizz_new_widget['widget-id'] && $old_widget_content['condition']==$bizz_new_widget['condition'] && $old_widget_content['item']==$bizz_new_widget['item']){
					$bizz_old_widget_id = $widgets->ID;
					$bizz_old_widget_content = unserialize($widgets->post_content);
				}
			endforeach;
			
			// save widget post
			if(!empty($bizz_old_widget_id)){
				// Update post object
				$update_post = array();
				$update_post['ID'] = $bizz_old_widget_id;
				$update_post['post_content'] = serialize($bizz_new_widget);
				$update_post['post_content_filtered'] = $themeid;
				// Update the post inside database
				wp_update_post( $update_post );
			}
			else {
				// Create post object
				$args = array(
					'post_type' 				=> 'bizz_widget',
					'post_title' 				=> $bizz_new_widget['item'],
					'post_excerpt' 				=> $bizz_new_widget['condition'],
					'post_content' 				=> serialize($bizz_new_widget),
					'post_content_filtered' 	=> $themeid,
					'ping_status' 				=> get_option('default_ping_status'), 
					'post_status' 				=> 'publish'
				);
				// Insert the post into database
				wp_insert_post( $args );

			}
						
		die;
		break;
		
		case 'bizz-sidebars-backup':
			
			// backup sidebars_widgets
			$saved_sidebars = get_option('sidebars_widgets');
			update_option($themeid.'_sidebars_widgets', $saved_sidebars);
				
		die;
		break;
		
		case 'bizz-design':
						
			$opts = array();
			$opts['themeid'] = $themeid;
			$data = $_POST['data'];
			parse_str($data,$output);
			print_r($output);
			foreach ($output as $key => $value) {
				if ($value != ''){
					if ( is_string( $value ) ) 
						$opts[$key] = mysql_real_escape_string( $value );
					else
						$opts[$key] = $value;
				}
			} // end foreach
			
			// Update/SAVE Options into MySQL Array
			update_option('bizzthemes_design', $opts);
			bizz_generate_css(); // updates layout.css file
		
		die;
		break;
		
		case 'bizz-all':
						
			$opts = array();
			$opts['themeid'] = $themeid;
			$data = $_POST['data'];
			parse_str($data,$output);
			print_r($output);
			foreach ($output as $key => $value) {
				if ($value != ''){
					if ( is_string( $value ) ) 
						$opts[$key] = mysql_real_escape_string( $value );
					else
						$opts[$key] = $value;
				}
			} // end foreach
			
			// Update/SAVE Options into MySQL Array
			update_option('bizzthemes_options', $opts);
		
		die;
		break;
		
		case 'upload':
			
			$clickedID = $_POST['data']; // Acts as the name
			$filename = $_FILES[$clickedID];
			$override['test_form'] = false;
			$override['action'] = 'wp_handle_upload';
			$uploaded_file = wp_handle_upload($filename,$override);
			echo $uploaded_file['url'];
		
		die;
		break;
		
		case 'bizz-info-layout':
						
			$opts = array();
			$opts['themeid'] = $themeid;
			$opts['box']     = 'hidden';
			
			// Update/SAVE Options into MySQL Array
			update_option('bizzthemes_info_layout', $opts);
		
		die;
		break;
		
		case 'bizz-treeview':
			
			$data = $_POST['data'];
			parse_str($data,$output);
			foreach ($output as $key => $value) {
				$post_type = $key;
			}
			$single_posts = bizz_layout_single('is_single', $post_type);
			$can_paginate = bizz_layout_single('is_single', $post_type, 2);
			
			if ( !empty($single_posts) ) {
				$single_posts = bizz_tabs_list( $single_posts );
				print_r($single_posts);
				if ( !empty($can_paginate) ) {
					print_r('<div class="paginateme linkedp" rel="2" title="Click to load more posts">Page <span>2</span></div>');
					print_r('<div class="ajax-loader"><!----></div>');
				}
			}
			else
				print_r('<ul class="treeview"><li class="menu-tab last">'.__('No posts found.', 'bizzthemes').'</li></ul>');
		
		die;
		break;
		
		case 'bizz-treeview-paginateme':
						
			$single_posts = bizz_layout_single('is_single', $_POST['data'], ($_POST['paged']+1));
			$can_paginate = bizz_layout_single('is_single', $_POST['data'], ($_POST['paged']+1));
			
			if ( !empty($single_posts) ) {
				$single_posts = bizz_tabs_list( $single_posts );
				print_r($single_posts);
				if ( !empty($can_paginate) ){
					print_r('<div class="paginateme linkedp" rel="'.($_POST['paged']+1).'" title="Click to load more posts">Page <span>'.($_POST['paged']+1).'</span></div>');
					print_r('<div class="ajax-loader"><!----></div>');
				}
			}
			else
				print_r('<ul class="treeview"><li class="menu-tab last">'.__('No posts found.', 'bizzthemes').'</li></ul>');
		
		die;
		break;
		
		case 'bizz-info-recover':
			
			$saved_themesidebars = get_option($themeid . '_sidebars_widgets');
			$data = $_POST['data'];
						
			if ( isset($data) && $data == 'recover_yes' ) {
				update_option('sidebars_widgets', $saved_themesidebars);
				echo __('Your widgetized sidebars have been successfully recovered.', 'bizzthemes');
			}
			elseif ( isset($data) && $data == 'recover_no' ) {
				delete_option($themeid.'_sidebars_widgets');
				echo __('Your widgetized sidebars backup has been removed.', 'bizzthemes');
			}
		
		die;
		break;

    }

}

add_action('wp_ajax_bizz_ajax_post_action', 'bizz_ajax_callback');

