<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

// HTML OUTPUT
function bizz_html_index() {
	
	do_action('bizz_header_grid'); #hook #IMPORTANT
	do_action('bizz_body_grid'); #hook #IMPORTANT
	do_action('bizz_footer_grid'); #hook #IMPORTANT
	
}

// BUILD HEADER STRUCTURE
add_action('bizz_header_grid', 'bizz_html_header');
function bizz_html_header() {
	global $post;
	
	echo apply_filters('bizz_doctype', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">') . "\n"; #filter

?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<?php
	
	// HEAD
	echo "<head" . apply_filters('bizz_head_profile', ' profile="http://gmpg.org/xfn/11"') . ">\n"; #filter
	echo '<meta http-equiv="Content-Type" content="' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') . '" />' . "\n"; #wp
	if (!is_admin()){ if ( is_singular() && comments_open() && (get_option('thread_comments') == 1)) wp_enqueue_script( 'comment-reply' ); } #wp
	do_action( 'bizz_head_before' ); #hook
	do_action( 'bizz_head_title' ); #hook
	do_action( 'bizz_head_meta' ); #hook
	do_action( 'bizz_head_stylesheets' ); #hook
	do_action( 'bizz_head_links' ); #hook
	do_action( 'bizz_head_scripts' ); #hook
	wp_head(); #wp #hook
	do_action( 'bizz_head_after' ); #hook
	echo '<link rel="pingback" href="' . get_bloginfo('pingback_url') . '" />' . "\n"; #wp
	echo "</head>\n"; #END <head> tag
		
	// BODY
	echo "<body " . apply_filters('bizz_body_class', 'class="'.implode(' ', get_body_class()).'"') . ">\n"; #filter
	do_action( 'bizz_body_after' ); #hook
	do_action( 'bizz_head_grid' ); #hook
}

// BUILD FOOTER STRUCTURE
add_action('bizz_footer_grid', 'bizz_html_footer');
function bizz_html_footer() {
	do_action( 'bizz_foot_grid' ); #hook
	do_action( 'bizz_foot_before' ); #hook
	wp_footer(); #wp #hook
	do_action( 'bizz_foot_after' ); #hook
	echo "</body>\n</html>"; #END <body> and <html> tags
}

// BUILD CONTAINER STRUCTURE
add_action('bizz_body_grid', 'bizz_html_build');
function bizz_html_build( $output=false, $return=false, $default_grid_logic=false, $default_widget_logic=false ) {
	
	$condition_logic = bizz_html_condition_logic();
	$grid_logic = ($default_grid_logic) ? $default_grid_logic : bizz_html_grid_logic($condition_logic); #grid logic
	$widget_logic = ($default_widget_logic) ? $default_widget_logic :  bizz_html_widget_logic($condition_logic); // widget logic
	$widget_filter = apply_filters('bizz_custom_widgets_filter', '');
	$widget_logic = array_merge( (array) $widget_logic, (array) $widget_filter); #push custom defined widgets
			
	foreach ( $grid_logic as $container => $registered_container ) {
	
		$container_content = bizz_capture('bizz_html_grid_tree', $registered_container['grids'], false, $widget_logic); #capture 'bizz_html_grid_tree' function output
		$container_empty = trim(strip_tags($container_content, '<input><form><textarea><li><ol><ul><table><span><p><a><img>')); #check if container is empty
										
		if ( $registered_container['show'] != 'false' && !empty($container_empty) ) { #only containers with content
			
			if ( isset($registered_container['before_container']) && $registered_container['before_container'] != '' )
				$output .= $registered_container['before_container'];
				
			if ( isset($registered_container['id']) && $registered_container['id'] != '' )
				$output .= "<".apply_filters('bizz_html5_section', "div")." id=\"".$registered_container['id']."\" class=\"clearfix\">\n";
			
			if ( isset($registered_container['container']) && $registered_container['container'] != '' )
				$output .= "<div class=\"".$registered_container['container']."\">\n";
				
			if ( isset($registered_container['before_container_tree']) && $registered_container['before_container_tree'] != '' )
				$output .= $registered_container['before_container_tree'];
				
			// captured content
			$output .= $container_content;
			
			if ( isset($registered_container['after_container_tree']) && $registered_container['after_container_tree'] != '' )
				$output .= $registered_container['after_container_tree'];
			
			if ( isset($registered_container['container']) && $registered_container['container'] != '' )
				$output .= "</div>\n"; #END .container_id
				
			if ( isset($registered_container['id']) && $registered_container['id'] != '' )
				$output .= "</".apply_filters('bizz_html5_section', "div").">\n"; #END .container_css
			
			if ( isset($registered_container['after_container']) && $registered_container['after_container'] != '' )
				$output .= $registered_container['after_container'];

		}
	}
	
	// Return or echo the output
	if ( $return == TRUE )
		return $output;
	else 
		echo $output; // Done  
	
}

// BUILD GRID TREE STRUCTURE
function bizz_html_grid_tree( $grid_array='', $tree=false, $widget_logic='', $check=false ){
    global $wp_registered_sidebars;
	
	// loop through all grids
	foreach ( $grid_array as $grid => $registered_grid ) {

		if ( isset($registered_grid['before_grid']) && $registered_grid['before_grid'] != '' )
			echo $registered_grid['before_grid']; #before grid
						
		if ( isset($registered_grid['class']) && !empty($registered_grid['class']) && $check == false )
			echo "<div class=\"".$registered_grid['class']."\">\n";

			// loop through all sidebars	
			foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
				if ( 'bizz_inactive_widgets' == $sidebar )
					continue;
				if ($grid == $registered_sidebar['grid']) {
					
					// add widgets only when active sidebar
					if (is_active_sidebar( $sidebar ))
						add_action( 'bizz_sidebar_grid', 'bizz_dynamic_sidebar', 10, 3 ); # $tag, $function_to_add, $priority, $accepted_args
											
					if ( isset($registered_sidebar['class']) && $check == false )
						echo "<div class=\"".$registered_sidebar['class']."\">\n";
						
					do_action( 'bizz_sidebar_grid_before', $grid, $sidebar ); #hook
					do_action( 'bizz_sidebar_grid', $grid, $sidebar, $widget_logic ); #hook
					do_action( 'bizz_sidebar_grid_after', $grid, $sidebar ); #hook
						
					if ( isset($registered_sidebar['class']) && $check == false )
						echo "</div>\n"; #END .sidebar class

				}
			}
			
			if (isset($registered_grid['tree']) && is_array($registered_grid['tree']))
				bizz_html_grid_tree($registered_grid['tree'], true, $widget_logic); #nested sidebars
		
		if ( isset($registered_grid['class']) && !empty($registered_grid['class']) && $check == false )
			echo "</div>\n"; #END .grid class
			
		if ( isset($registered_grid['after_grid']) && $registered_grid['after_grid'] != '' )
			echo $registered_grid['after_grid']; #after grid
			
	}
	
}

// DYNAMIC SIDEBAR
function bizz_dynamic_sidebar( $grid='', $index=1, $widget_logic='' ) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	
	if ( is_int($index) )
		$index = "sidebar-$index";
	else {
		$index = sanitize_title($index);
		foreach ( (array) $wp_registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}

	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( empty( $sidebars_widgets ) )
		return false;
	if ( empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
		return false;
	$sidebar = $wp_registered_sidebars[$index];
	$did_one = false;
	foreach ( (array) $sidebars_widgets[$index] as $id ) {
		// only show available widgets
		if (in_array($id, (array) $widget_logic)){ #custom code
		
		    if ( !isset($wp_registered_widgets[$id]) ) continue;
			$params = array_merge(
			    array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
				(array) $wp_registered_widgets[$id]['params']
			);
			// Substitute HTML id and class attributes into before_widget
			$classname_ = '';
			foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
			    if ( is_string($cn) )
				    $classname_ .= '_' . $cn;
				elseif ( is_object($cn) )
				    $classname_ .= '_' . get_class($cn);
			}
			$classname_ = ltrim($classname_, '_');
			$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
			$params = apply_filters( 'dynamic_sidebar_params', $params );
			$callback = $wp_registered_widgets[$id]['callback'];
			do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );
			if ( is_callable($callback) ) {
			    call_user_func_array($callback, $params);
				$did_one = true;
			}
		
		}
	}

	return $did_one;
}

// CONDITION LOGIC
function bizz_html_condition_logic(){
    global $wp_query;
	
	$condition_logic 		= array();
	$this_item 				= $wp_query->get_queried_object_id();
	$bizz_item 				= ($this_item=='0') ? ($this_item='all') : ($this_item=$this_item);
	$is_post_type_index 	= get_query_var('post_type');
	$get_post_type 			= (isset($wp_query->post)) ? get_post_type( $wp_query->post->ID ) : null;
			
	// define variables
	$bizz_tab 					= '';
	$bizz_subtab 				= '';
	$bizz_subtabsub 			= '';
	$bizz_condition 			= '';
	$bizz_item 					= '';
	$bizz_template_condition 	= '';
	$bizz_template_item 		= '';
			
	if($wp_query->is_posts_page){
	    $bizz_tab = 'is_index'; $bizz_subtab = 'all'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_index'; $bizz_item = 'all';
	}
	elseif($wp_query->is_home){
	    $bizz_tab = 'is_front_page'; $bizz_subtab = 'all'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_front_page'; $bizz_item = 'all';
	}
	elseif($wp_query->is_single || $wp_query->is_page){ 
		$bizz_tab = 'is_single';
		if($this_item != '0'){
		    $bizz_subtab = get_post_type( $this_item ); $bizz_subtabsub = 'is_singular'; $bizz_condition = 'is_single'; $bizz_item = $this_item;
		}
		else {
		    $bizz_subtab = 'all'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_single'; $bizz_item = 'all';
		}
		// page template
		if(is_page_template()){
			$this_template = get_page_template_slug( $this_item );
			if($this_template != 'all'){
				$bizz_template_condition = 'is_page_template'; $bizz_template_item = $this_template;
			}
			else {
				$bizz_template_condition = 'is_page_template'; $bizz_template_item = 'all';
			}
		}
	}
	elseif($wp_query->is_archive){
		$bizz_tab = 'is_archive'; 
		if($wp_query->is_date){
		    $bizz_subtab = 'date'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_date'; $bizz_item = $this_item;
		}
		elseif($wp_query->is_author){
		    $bizz_subtab = 'author'; $bizz_subtabsub = 'is_author'; $bizz_condition = 'is_author'; $bizz_item = $this_item;
		}
		elseif($wp_query->is_category){
		    $bizz_subtab = 'category'; $bizz_subtabsub = 'is_category'; $bizz_condition = 'is_category'; $bizz_item = $this_item;
		}
		if($wp_query->is_tag){
		    $bizz_subtab = 'tag'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_tag'; $bizz_item = $this_item;
		}
		if (
		    isset( $get_post_type )           &&
			$get_post_type != 'revision'      && 
			$get_post_type != 'nav_menu_item' && 
			$get_post_type != 'post'          && 
			$get_post_type != 'page'          && 
			$get_post_type != 'attachment'    && 
			$get_post_type != 'bizz_widget'   && 
			$get_post_type != 'bizz_grid'
		){
			$bizz_subtab = $get_post_type;
			$bizz_subtabsub = 'is_pt_archive'; 
			
			if($is_post_type_index != ''){
			    $bizz_condition = 'is_pt_index'; $bizz_item = $get_post_type;
			}
			elseif($wp_query->is_tax){
				$term_id = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
				$this_tax = get_query_var('taxonomy');
				$bizz_condition = 'is_tax'; $bizz_item = $this_tax;
			}
		}
	}
	elseif($wp_query->is_search){
	    $bizz_tab = 'is_search'; $bizz_subtab = 'all'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_search'; $bizz_item = 'all';
	}
	elseif($wp_query->is_404){
	    $bizz_tab = 'is_404'; $bizz_subtab = 'all'; $bizz_subtabsub = 'all'; $bizz_condition = 'is_404'; $bizz_item = 'all';
	}
	
	$condition_logic['bizz_tab']        			= $bizz_tab;
	$condition_logic['bizz_subtab']     			= $bizz_subtab;
	$condition_logic['bizz_subtabsub']  			= $bizz_subtabsub;
	$condition_logic['bizz_condition']  			= $bizz_condition;
	$condition_logic['bizz_item']       			= $bizz_item;
	$condition_logic['bizz_template_condition'] 	= $bizz_template_condition;
	$condition_logic['bizz_template_item'] 			= $bizz_template_item;
	
	return $condition_logic;
	
}

// WIDGET LOGIC
function bizz_html_widget_logic($condition_logic){	
	global $themeid;
	
	$avail_widgets = array();
	
	// print_r($condition_logic);
		
	// query available widgets
	$args = array(
		'order' => 'ASC', 
		'orderby' => 'modified',
		'post_type' => 'bizz_widget',
		'nopaging' => true,
		'post_status' => 'publish',
		'output' => ARRAY_A,
		'nopaging' => true
	);
	$old_widgetlogics = get_posts($args);
		
	foreach ($old_widgetlogics as $grids) {
		$registered_theme 	= $grids->post_content_filtered;
		$current_theme		= $themeid;
		if ( ( isset($registered_theme) ) && ( $registered_theme == $themeid ) || ( $registered_theme == '' ) ) { # different theme?
			$_old_widgetlogic = bizz_reverse_escape( $grids->post_content );
			$old_widgetlogic[] = unserialize($_old_widgetlogic); # array of available widgets
		}
	}

	if (!empty($old_widgetlogic)){
		foreach ( $old_widgetlogic as $_key => $_value) {
			$_key = $_key.',';
			if (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_condition'] && $_value['item']==$condition_logic['bizz_item'] ){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='false,' : $_parent.='false,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_subtabsub'] && $_value['item']==$condition_logic['bizz_subtab']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_subtab'] && $_value['item']==$condition_logic['bizz_item']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_tab'] && $_value['item']==$condition_logic['bizz_item']){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_tab'] && $_value['item']=='all'){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_condition'] && $_value['item']=='all'){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			elseif (isset($_value['condition']) && $_value['condition']=='is_index' && $_value['item']=='all'){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='true,' : $_parent.='true,'; // parent widget?
			}
			// page templates
			if (isset($_value['condition']) && $_value['condition']==$condition_logic['bizz_template_condition'] && $_value['item']==$condition_logic['bizz_template_item'] ){
				(!isset($_widget)) ? $_widget=$_key : $_widget.=$_key;
				(!isset($_parent)) ? $_parent='false,' : $_parent.='false,'; // parent widget?
			}
		}		
		if(isset($_widget)){
			$_parent = substr_replace($_parent ,"",-1); // remove last comma
			$available_parents = explode(",",$_parent);
			$available_widgets = explode(",",$_widget);
			$available_widgets = array_diff($available_widgets, array(""));
			$available_widgets = array_combine($available_widgets,$available_parents);
			foreach ( $available_widgets as $key => $value) {
				if($old_widgetlogic[$key]['parent']=='true' && isset($old_widgetlogic[$key]['show']) && $old_widgetlogic[$key]['show']!='true')
					(!isset($hidden)) ? $hidden=$key.',' : $hidden.=$key.',';
			}
			if(isset($hidden)){
				$_hidden = substr_replace($hidden ,"",-1); // remove last comma
				$_hidden = explode(",",$_hidden);
				$flip_hidden = array_flip($_hidden);
				// get hidden widgets IDs
				foreach ( $old_widgetlogic as $key => $value) {
					if(isset($flip_hidden[$key]))
						(!isset($_excluded)) ? $_excluded = $value['widget-id'].',' : $_excluded .= $value['widget-id'].',';
				}
				$_excluded = substr_replace($_excluded ,"",-1); // remove last comma
				$_excluded = explode(",",$_excluded);
			}
			// spit out available widgets for this template
			foreach ( $old_widgetlogic as $key => $value) {
				if (isset($hidden) && in_array($value['widget-id'], $_excluded))
					$_exclude = $value['widget-id'];
				else
					$_exclude = '999999999'; // just some impossible number
				
				if (array_key_exists($key, $available_widgets) && $value['widget-id'] != $_exclude )
					$avail_widgets[] = $value['widget-id'];
				
			}
			return $avail_widgets;
		} else
			return false;
	} else
		return false;
	
}

// GRID LOGIC
function bizz_html_grid_logic($condition_logic){
	global $bizz_registered_grids, $themeid;
	
	$args = array(
		'order' => 'ASC', 
		'orderby' => 'modified',
		'post_type' => 'bizz_grid',
		'nopaging' => true,
		'post_status' => 'publish',
		'output' => ARRAY_A,
		'nopaging' => true
	);
	$bizz_old_grids = get_posts($args);
	foreach ($bizz_old_grids as $grids) {
		(!isset($_condition)) ? $_condition=$grids->post_excerpt.',' : $_condition.=$grids->post_excerpt.',';
		(!isset($_item)) ? $_item=$grids->post_title.',' : $_item.=$grids->post_title.',';
	}
								
	if (!empty($bizz_old_grids)) {
		
		$_condition = substr_replace($_condition ,"",-1); // remove last comma
		$_condition = explode(",",$_condition);
		$_item = substr_replace($_item ,"",-1); // remove last comma
		$_item = explode(",",$_item);
		
		/* Define which templates are active: (array)conditions, (array)items, condition, item */
		$level_one = bizz_is_template($_condition, $_item, $condition_logic['bizz_condition'], $condition_logic['bizz_item']);
		$level_two_one = bizz_is_template($_condition, $_item, $condition_logic['bizz_subtabsub'], $condition_logic['bizz_item']);
		$level_two_two = bizz_is_template($_condition, $_item, $condition_logic['bizz_subtabsub'], $condition_logic['bizz_subtab']);
		$level_three = bizz_is_template($_condition, $_item, $condition_logic['bizz_subtab'], $condition_logic['bizz_item']);
		$level_four = bizz_is_template($_condition, $_item, $condition_logic['bizz_tab'], 'all');
		$level_five = in_array('is_index', $_condition) && in_array('all', $_item);
		
		// list condition and item for current template
		if ( $level_one ){
		    $this_condition  = $condition_logic['bizz_condition'];
			$this_item       = $condition_logic['bizz_item'];
			// print_r( 'level one<br/>' );
		}
		elseif ( $level_two_one ){
		    $this_condition  = $condition_logic['bizz_subtabsub'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_item)];
			// print_r( 'level two<br/>' );
		}
		elseif ( $level_two_two ){
		    $this_condition  = $condition_logic['bizz_subtabsub'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_item)];
			// print_r( 'level two<br/>' );
		}
		elseif ( $level_three ){
		    $this_condition  = $condition_logic['bizz_subtab'];
			$this_item       = $_item[array_search($condition_logic['bizz_subtab'], $_condition)];
			// print_r( 'level three<br/>' );
		}
		elseif ( $level_four ){
		    $this_condition  = $condition_logic['bizz_tab'];
			$this_item       = $_item[array_search($condition_logic['bizz_tab'], $_condition)];
			// print_r( 'level four<br/>' );
		}
		elseif ( $level_five ){
		    $this_condition  = 'is_index';
			$this_item       = $_item[array_search('is_index', $_condition)];
			// print_r( 'level five<br/>' );
		}
									
		if(isset($this_condition)){
			foreach ($bizz_old_grids as $grids) {
				if($grids->post_excerpt==$this_condition && $grids->post_title==$this_item){
					$_layout 	= bizz_reverse_escape( $grids->post_content );
					$_layout 	= unserialize($_layout);
					$_theme 	= $grids->post_content_filtered;
				}
			}
		}
		if(isset($_layout))
			$old_containers = $_layout;
		else
			$old_containers = $bizz_registered_grids;
							
		// unset customized & unregistered containers / areas
		$array_keys1 		= (isset($old_containers) && is_array($old_containers)) ? array_keys($old_containers) : '';
		$array_keys2 		= (isset($bizz_registered_grids) && is_array($bizz_registered_grids)) ? array_keys($bizz_registered_grids) : '';
		$array_keys_match 	= (is_array($array_keys1) && is_array($array_keys2)) ? array_diff($array_keys1, $array_keys2) : '';
		if ( !empty($array_keys_match) ){
			foreach ( (array) $array_keys_match as $value)
				unset( $old_containers[$value] ); #unset unregistered areas
		}
		
		// check if arrays match (different theme?)
		$registered_theme 	= ( isset($_theme) ) ? $_theme : '';
		$current_theme		= $themeid;
		if ( ( $registered_theme != $current_theme ) && ( $registered_theme != '' ) )
			$old_containers = $bizz_registered_grids;
									
	}

	if (empty($old_containers))
		$bizz_registered_grids = $bizz_registered_grids; // default grid
	else {
		$array1 = $bizz_registered_grids;
		$array2 = $old_containers;
		$array3 = bizz_array_merge_recursive_distinct($array1, $array2);
		$array4 = array_merge($array2,$array3);
		$bizz_registered_grids = $array4; // saved grid
	}
							
	return $bizz_registered_grids;
}
