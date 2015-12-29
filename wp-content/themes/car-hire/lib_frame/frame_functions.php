<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

// Theme options variables
add_action( 'init', 'bizz_define_options_vars' );
function bizz_define_options_vars() {
	global $other_entries, $other_entries2, $backgrounds, $alt_stylesheets, $alt_patterns, $pn_categories, $pne_pages;
	
	/**
	 * GLOBAL VARIABLES
	 *
	 * define global variables used across theme framework
	 * @since 6.0
	 */
	$other_entries = array("1","2","3","4","5","6","7","8","9","10");
	$other_entries2 = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");

	/**
	 * BACKGROUND ARRAY OPTIONS
	 *
	 * define background image options if available
	 * @since 6.0
	 */
	$background_path = BIZZ_LIB_THEME . '/images/stripes/'; 
	$backgrounds = array();
	if ( is_dir($background_path) ) {
		if ($background_dir = opendir($background_path) ) { 
			while ( ($background_file = readdir($background_dir)) !== false ) {
				if(stristr($background_file, ".gif") !== false) {
					$backgrounds[] = $background_file;
				}
			}
		}
	}

	/**
	 * ALT STYLESHEET ARRAY OPTIONS
	 *
	 * define theme skins
	 * @since 6.0
	 */
	$alt_stylesheet_path = BIZZ_LIB_THEME . '/skins/';
	$alt_stylesheets = array();
	if ( is_dir($alt_stylesheet_path) ) {
		if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
			while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
				if(stristr($alt_stylesheet_file, ".css") !== false) {
					$alt_stylesheets[] = $alt_stylesheet_file;
				}
			}
			sort($alt_stylesheets);		
		}
	}

	/**
	 * ALT PATTERNS ARRAY OPTIONS
	 *
	 * define theme skins
	 * @since 6.0
	 */
	$alt_pattern_path = BIZZ_LIB_THEME . '/skins/patterns/';
	$alt_patterns = array();
	if ( is_dir($alt_pattern_path) ) {
		if ($alt_pattern_dir = opendir($alt_pattern_path) ) { 
			while ( ($alt_pattern_file = readdir($alt_pattern_dir)) !== false ) {
				if(stristr($alt_pattern_file, ".png") !== false) {
					$alt_patterns[] = $alt_pattern_file;
				}
			}
			sort($alt_patterns);		
		}
	}

	/**
	 * OPTIONS ARRAY SETUP
	 *
	 * setup varius options arrays
	 * @since 6.0
	 */
	 
	// Categories array
	$pn_categories_obj = get_categories('sort=ASC');
	$pn_categories = array();

	// Categories Name Load
	foreach ($pn_categories_obj as $pn_cat) {
		$pn_categories[$pn_cat->name] = $pn_cat->term_id;
	}

	// Pages array
	$pne_pages_obj = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');
	$pne_pages = array();

	// Pages Exclude Load
	foreach ($pne_pages_obj as $pne_pag) {
		$pne_pages[$pne_pag->post_title] = $pne_pag->ID;
	}

}

/**
 * EXCLUDE CATEGORIES, PAGES, SEO, POSTS OPTIONS
 *
 * exclude by name
 * @since 6.0
 */
// Exclude Categories by Name 1
function category_exclude($options) {
	$options[] = array(	"type" => "wraptop");														
	$cats = get_categories('sort=ASC');
	foreach ( (array) $cats as $cat) {	
	    if ($cat->cat_ID == '1') { $disabled = "disabled"; } else { $disabled = ""; };
			$options[] = array(	
						"name" 		=> "",
						"label" 	=> $cat->name . " (" . $cat->count . ") &nbsp;<small style='color:#aaaaaa'>id=" . $cat->cat_ID . "</small>",
						"id" 		=> "cat_exclude_".$cat->cat_ID,
						"disabled" 	=> "".$disabled."",
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);						
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Categories by Name 2
function category_exclude2($options) {
	$options[] = array(	"type" => "wraptop");														
	$cats = get_categories('sort=ASC');
	foreach ( (array) $cats as $cat) {	
	    if ($cat->cat_ID == '1') { $disabled = "disabled"; } else { $disabled = ""; };
			$options[] = array(	
						"name" 		=> "",
						"label" 	=> $cat->name . " (" . $cat->count . ") &nbsp;<small style='color:#aaaaaa'>id=" . $cat->cat_ID . "</small>",
						"id" 		=> "cat_exclude2_".$cat->cat_ID,
						"disabled" 	=> "".$disabled."",
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);						
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Categories by Name 3
function category_exclude3($options) {
	$options[] = array(	"type" => "wraptop");														
	$cats = get_categories('sort=ASC');
	foreach ( (array) $cats as $cat) {	
	    if ($cat->cat_ID == '1') { $disabled = "disabled"; } else { $disabled = ""; };
			$options[] = array(	
						"name" 		=> "",
						"label" 	=> $cat->name . " (" . $cat->count . ") &nbsp;<small style='color:#aaaaaa'>id=" . $cat->cat_ID . "</small>",
						"id" 		=> "cat_exclude3_".$cat->cat_ID,
						"disabled" 	=> "".$disabled."",
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);						
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Categories by Name 4
function category_exclude4($options) {
	$options[] = array(	"type" => "wraptop");														
	$cats = get_categories('sort=ASC');
	foreach ( (array) $cats as $cat) {	
	    if ($cat->cat_ID == '1') { $disabled = "disabled"; } else { $disabled = ""; };
			$options[] = array(	
						"name" 		=> "",
						"label" 	=> $cat->name . " (" . $cat->count . ") &nbsp;<small style='color:#aaaaaa'>id=" . $cat->cat_ID . "</small>",
						"id" 		=> "cat_exclude4_".$cat->cat_ID,
						"disabled" 	=> "".$disabled."",
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);						
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Pages by Name 1
function pages_exclude($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pags as $pag) {
	    $thumb = get_post_meta($pag->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small> ".$img_link."",
						"id" 		=> "pag_exclude_".$pag->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Pages by Name 2
function pages_exclude2($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pags as $pag) {
        $thumb = get_post_meta($pag->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small> ".$img_link."",
						"id" 		=> "pag_exclude2_".$pag->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);				
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Pages by Name 3
function pages_exclude3($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pags as $pag) {
        $thumb = get_post_meta($pag->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small> ".$img_link."",
						"id" 		=> "pag_exclude3_".$pag->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Pages by Name 4
function pages_exclude4($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pags as $pag) {	
	    $thumb = get_post_meta($pag->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small> ".$img_link."",
						"id" 		=> "pag_exclude4_".$pag->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude SEO
function pages_exclude_seo($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pags as $pag) {	
	    $thumb = get_post_meta($pag->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small> ".$img_link."",
						"id" 		=> "pag_exclude_seo_".$pag->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Posts by Name 1
function posts_exclude($options) {
	$options[] = array(	"type" => "wraptop");						
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1');	
	foreach ( (array) $psts as $pst) {	
	    $thumb = get_post_meta($pst->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pst->ID . "</small> ".$img_link."",
						"id" 		=> "pst_exclude_".$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Posts by Name 2
function posts_exclude2($options) {
	$options[] = array(	"type" => "wraptop");						
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1');	
	foreach ( (array) $psts as $pst) {	
	$thumb = get_post_meta($pst->ID, 'image', true);
	    $thumb = get_post_meta($pst->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pst->ID . "</small> ".$img_link."",
						"id" 		=> "pst_exclude2_".$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);				
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Posts by Name 3
function posts_exclude3($options) {
	$options[] = array(	"type" => "wraptop");						
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1');	
	foreach ( (array) $psts as $pst) {	
	$thumb = get_post_meta($pst->ID, 'image', true);
	    $thumb = get_post_meta($pst->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pst->ID . "</small> ".$img_link."",
						"id" 		=> "pst_exclude3_".$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Posts by Name 4
function posts_exclude4($options) {
	$options[] = array(	"type" => "wraptop");						
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1');	
	foreach ( (array) $psts as $pst) {	
	$thumb = get_post_meta($pst->ID, 'image', true);
	    $thumb = get_post_meta($pst->ID, 'image', true);
	    if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pst->ID . "</small> ".$img_link."",
						"id" 		=> "pst_exclude4_".$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

/**
 * LIST EXCLUDED OPTIONS
 *
 * list excluded options by name
 * @since 6.0
 */
// List excluded categories
function get_inc_categories($label) {	
	global $opt;
	
	$include = '';
	$counter = 0;
	$catsx = get_categories('hide_empty=0');	
	foreach ( (array) $catsx as $cat) {		
		$counter++;		
		if ( $opt[$label.$cat->cat_ID] ) {
			if ( $counter >= 1 ) { $include .= ','; }
			$include .= $cat->cat_ID;
			}	
	}	
	return $include;
}

// List excluded pages
function get_inc_pages($label) {	
	global $opt;
	
	$include = '';
	$counter = 0;
	$pagsx = get_posts('orderby=title&numberposts=-1&order=ASC&post_type=page');	
	foreach ( (array) $pagsx as $pag) {		
		$counter++;		
		if ( isset($opt[$label.$pag->ID]['true']) && $opt[$label.$pag->ID]['true'] ) {
			if ( $counter <> 1 ) { $include .= ','; }
			$include .= $pag->ID;
			}	
	}	
	return $include;
}

// List excluded posts
function get_inc_posts($label) {	
	global $opt;
	
	$include = '';
	$counter = 0;
	$pstsx = get_posts('sort_order=ASC&numberposts=-1');	
	foreach ( (array) $pstsx as $pst) {		
		$counter++;		
		if ( $opt[$label.$pst->ID] ) {
			if ( $counter <> 1 ) { $include .= ','; }
			$include .= $pst->ID;
			}	
	}	
	return $include;
}

// List excluded attachments
function get_inc_att($label) {	
	global $opt;
	
	$include = '';
	if ( isset($opt) && $opt <> '' ){
	foreach ( (array) $opt as $key => $value) {
		if(substr($key, 0, 10) == $label) {
		    $include .= ",";
			$include .= preg_replace("/[^0-9]/", '', $key);
		}
	}
	}
	return $include;
}

/**
 * SELECT MULTIPLE OPTIONS
 *
 * select multiple options, multiselect
 * @since 6.0
 */
// Select multiple images
function select_uploads($options,$selid) {
	global $bloghomeurl;
	
	$options[] = array(	"type" => "sorttop");	
	
	// get selected attachements in correct order
	$sliderpages = get_inc_att($selid);
	$sliderarray = explode(",",$sliderpages);
	$sliderarray = array_diff($sliderarray, array(""));
	foreach ( (array) $sliderarray as $featitem ) {
	    $pstsx = get_posts('post_type=attachment&post_mime_type=image&include='.$featitem.'');	
	    foreach ($pstsx as $pst) {	
	        $thumb = $pst->guid;
	        // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		    $edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/media.php?attachment_id='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);			
	    }
	}
	
	// get remaining attachements in alphabetic order
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1&post_type=attachment&post_mime_type=image&exclude='.get_inc_att($selid).'');	
	foreach ($psts as $pst) {	
	    $thumb = $pst->guid;
	    // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		$edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/media.php?attachment_id='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);
	}
	
	$options[] = array(	"type" => "sortbottom");		
	return $options;
}

// Select multiple pages
function select_pages($options,$selid) {
	global $bloghomeurl;
	
	$options[] = array(	"type" => "sorttop");	
	
	// get selected pages in correct order
	$sliderpages = get_inc_att($selid);
	$sliderarray = explode(",",$sliderpages);
	$sliderarray = array_diff($sliderarray, array(""));
	foreach ( (array) $sliderarray as $featitem ) {
	    $pstsx = get_posts('post_type=page&include='.$featitem.'');	
	    foreach ($pstsx as $pst) {	
	        $thumb = $pst->guid;
	        // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		    $edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/post.php?post='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);			
	    }
	}
	
	// get remaining pages in alphabetic order
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1&post_type=page&exclude='.get_inc_att($selid).'');	
	foreach ($psts as $pst) {	
	    $thumb = $pst->guid;
	    // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		$edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/post.php?post='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);
	}
	
	$options[] = array(	"type" => "sortbottom");		
	return $options;
}

// Select multiple posts
function select_posts($options,$selid) {
	global $bloghomeurl;
	
	$options[] = array(	"type" => "sorttop");	
	
	// get selected posts in correct order
	$sliderpages = get_inc_att($selid);
	$sliderarray = explode(",",$sliderpages);
	$sliderarray = array_diff($sliderarray, array(""));
	foreach ( (array) $sliderarray as $featitem ) {
	    $pstsx = get_posts('post_type=post&include='.$featitem.'');	
	    foreach ($pstsx as $pst) {	
	        $thumb = $pst->guid;
	        // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		    $edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/post.php?post='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);			
	    }
	}
	
	// get remaining posts in alphabetic order
	$psts = get_posts('orderby=title&order=ASC&numberposts=-1&post_type=post&exclude='.get_inc_att($selid).'');	
	foreach ($psts as $pst) {	
	    $thumb = $pst->guid;
	    // if ($thumb <> '') { $img_link = '<img src="'. $thumb .'" width="21" height="21" alt="" class="fr" />'; } else { $img_link = ''; }
		$edit_link = '<a style="font-size:10px" href="'.$bloghomeurl.'wp-admin/post.php?post='.$pst->ID.'&action=edit">edit</a>';
			$options[] = array(	
						"label" 	=> $pst->post_title . " &nbsp;<small style='color:#aaaaaa'>id=".$pst->ID."&nbsp;&nbsp;".$edit_link."</small>",
						"id" 		=> $selid.$pst->ID,
						"css" 		=> "",
						"type" 		=> "checkbox2"
			);					
	}
	
	$options[] = array(	"type" => "sortbottom");		
	return $options;
}

/**
 * MERGE ARRAY WITH UNIQUE VALUES
 *
 * extend default PHP4 aray_merge_recursive function
 * @since 7.0
 */
function bizz_array_merge_recursive_distinct($array1, $array2 = null){
  $merged = $array1;
  if (is_array($array2))
    foreach ($array2 as $key => $val)
      if (is_array($array2[$key]))
        $merged[$key] = is_array($merged[$key]) ? bizz_array_merge_recursive_distinct($merged[$key], $array2[$key]) : $array2[$key];
      else
        $merged[$key] = $val;
  return $merged;
}

/**
 * CUSTOM LOGIN LOGO
 *
 * select default WordPress login logo
 * @since 7.0
 */
function bizz_custom_login_logo() {
	global $opt;
	
	if ( isset($opt['bizzthemes_branding_login_logo']['value']) && $opt['bizzthemes_branding_login_logo']['value'] != '' ){
	    echo '<style type="text/css">';
		echo 'h1 a { background-image: url("'.$opt['bizzthemes_branding_login_logo']['value'].'") !important; background-position: center !important; }';
		echo '</style>';
	}
}
add_action('login_head', 'bizz_custom_login_logo');

/**
 * CUSTOM EXCERPT LENGTH
 *
 * select custom the_excerpt WP function length
 * @deprecated since 7.0
 * @since 6.0
 */
function bm_better_excerpt($length, $ellipsis) {
	$text = get_the_content();
	$text = strip_tags($text);
	$text = substr($text, 0, $length);
	$text = substr($text, 0, strrpos($text, " "));
	$text = $text.$ellipsis;
	return $text;
}

/**
 * RELATIVE DATES
 *
 * custom relative dates for comments and posts
 * @deprecated since 7.0
 * @since 6.0
 */
function relativeDate($posted_date) {
    $tz = 0;    // change this if your web server and weblog are in different timezones
    
    $month = substr($posted_date,4,2);
    
    if ($month == "02") { // february
    	// check for leap year
    	$leapYear = isLeapYear(substr($posted_date,0,4));
    	if ($leapYear) $month_in_seconds = 2505600; // leap year
    	else $month_in_seconds = 2419200;
    }
    else { // not february
    // check to see if the month has 30/31 days in it
    	if ($month == "04" or 
    		$month == "06" or 
    		$month == "09" or 
    		$month == "11")
    		$month_in_seconds = 2592000; // 30 day month
    	else $month_in_seconds = 2678400; // 31 day month;
    }
  
/* 
some parts of this implementation borrowed from:
http://maniacalrage.net/archives/2004/02/relativedatesusing/ 
*/
  
    $in_seconds = strtotime(substr($posted_date,0,8).' '.
                  substr($posted_date,8,2).':'.
                  substr($posted_date,10,2).':'.
                  substr($posted_date,12,2));
    $diff = time() - ($in_seconds + ($tz*3600));
    $months = floor($diff/$month_in_seconds);
    $diff -= $months*2419200;
    $weeks = floor($diff/604800);
    $diff -= $weeks*604800;
    $days = floor($diff/86400);
    $diff -= $days*86400;
    $hours = floor($diff/3600);
    $diff -= $hours*3600;
    $minutes = floor($diff/60);
    $diff -= $minutes*60;
    $seconds = $diff;

    if ($months>0) {
        // over a month old, just show date ("Month, Day Year")
        echo ''; the_time('F jS, Y');
    } else {
        if ($weeks>0) {
            // weeks and days
            $relative_date .= ($relative_date?', ':'').$weeks.' '.stripslashes(__('week', 'bizzthemes')).''.($weeks>1?''.stripslashes(__('s', 'bizzthemes')).'':'');
            $relative_date .= $days>0?($relative_date?', ':'').$days.' '.stripslashes(__('day', 'bizzthemes')).''.($days>1?''.stripslashes(__('s', 'bizzthemes')).'':''):'';
        } elseif ($days>0) {
            // days and hours
            $relative_date .= ($relative_date?', ':'').$days.' '.stripslashes(__('day', 'bizzthemes')).''.($days>1?''.stripslashes(__('s', 'bizzthemes')).'':'');
            $relative_date .= $hours>0?($relative_date?', ':'').$hours.' '.stripslashes(__('hour', 'bizzthemes')).''.($hours>1?''.stripslashes(__('s', 'bizzthemes')).'':''):'';
        } elseif ($hours>0) {
            // hours and minutes
            $relative_date .= ($relative_date?', ':'').$hours.' '.stripslashes(__('hour', 'bizzthemes')).''.($hours>1?''.stripslashes(__('s', 'bizzthemes')).'':'');
            $relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' '.stripslashes(__('minute', 'bizzthemes')).''.($minutes>1?''.stripslashes(__('s', 'bizzthemes')).'':''):'';
        } elseif ($minutes>0) {
            // minutes only
            $relative_date .= ($relative_date?', ':'').$minutes.' '.stripslashes(__('minute', 'bizzthemes')).''.($minutes>1?''.stripslashes(__('s', 'bizzthemes')).'':'');
        } else {
            // seconds only
            $relative_date .= ($relative_date?', ':'').$seconds.' '.stripslashes(__('minute', 'bizzthemes')).''.($seconds>1?''.stripslashes(__('s', 'bizzthemes')).'':'');
        }
        
        // show relative date and add proper verbiage
    	echo ''.stripslashes(__('Posted', 'bizzthemes')).' '.$relative_date.' '.stripslashes(__('ago', 'bizzthemes')).'';
    }
    
}
function isLeapYear($year) {
        return $year % 4 == 0 && ($year % 400 == 0 || $year % 100 != 0);
}
if(!function_exists('how_long_ago')){
	function how_long_ago($timestamp){
		$difference = time() - $timestamp;

		if($difference >= 60*60*24*365){        // if more than a year ago
			$int = intval($difference / (60*60*24*365));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('year', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} elseif($difference >= 60*60*24*7*5){  // if more than five weeks ago
			$int = intval($difference / (60*60*24*30));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('month', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} elseif($difference >= 60*60*24*7){        // if more than a week ago
			$int = intval($difference / (60*60*24*7));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('week', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} elseif($difference >= 60*60*24){      // if more than a day ago
			$int = intval($difference / (60*60*24));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('day', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} elseif($difference >= 60*60){         // if more than an hour ago
			$int = intval($difference / (60*60));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('hour', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} elseif($difference >= 60){            // if more than a minute ago
			$int = intval($difference / (60));
			$s = ($int > 1) ? ''.stripslashes(__('s', 'bizzthemes')).'' : '';
			$r = $int . ' '.stripslashes(__('minute', 'bizzthemes')).'' . $s . ' '.stripslashes(__('ago', 'bizzthemes')).'';
		} else {                                // if less than a minute ago
			$r = ''.stripslashes(__('moments', 'bizzthemes')).' '.stripslashes(__('ago', 'bizzthemes')).'';
		}

		return $r;
	}
}

/**
 * GET POST TAGS
 *
 * get specific post tags, array of objects
 * @deprecated since 7.0
 * @since 6.0
 */
function bizzthemes_get_post_tags($post_id = false) {
	if ($post_id) {
		$tags_objects = wp_get_post_tags($post_id);
		
		if ($tags_objects) {
			foreach ($tags_objects as $tag_object)
				$tags[] = $tag_object->name;
			
			return $tags;
		}
	}
}

/**
 * RELATED POSTS
 *
 * tutorial found on: http://curtishenson.com/write-your-own-related-posts-plugin/
 * @deprecated since 7.0
 * @since 6.0
 */
function bizz_get_related($post) {

    global $wpdb;
    
	$now = current_time('mysql', 1);
    $tags = wp_get_post_tags($post->ID);
    $show_date = 0;
    $limit = 3;
    $show_comments_count = 0;
            
    $taglist = "'" . $tags[0]->term_id. "'";
    $tagcount = count($tags);
    if ($tagcount > 1) {
        for ($i = 1; $i <= $tagcount; $i++) {
            $taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
        }
    }
                            
    $q = "SELECT p.ID, p.post_title, p.post_date, p.comment_count, count(t_r.object_id) as cnt FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r, $wpdb->posts p WHERE t_t.taxonomy ='post_tag' AND t_t.term_taxonomy_id = t_r.term_taxonomy_id AND t_r.object_id  = p.ID AND (t_t.term_id IN ($taglist)) AND p.ID != $post->ID AND p.post_status = 'publish' AND p.post_date_gmt < '$now' GROUP BY t_r.object_id ORDER BY cnt DESC, p.post_date_gmt DESC LIMIT $limit;";
    $related_posts = $wpdb->get_results($q);
	
        foreach ($related_posts as $related_post ){
            $output .= '<p class="rellist">&rsaquo;&nbsp;';
                    
                if ($show_date){
                    $dateformat = get_option('date_format');
                    $output .=   mysql2date($dateformat, $related_post->post_date) . " -- ";
                }
                    
                $output .=  '<a href="'.get_permalink($related_post->ID).'" title="'.wptexturize($related_post->post_title).'">'.wptexturize($related_post->post_title).'';
                    
                if ($show_comments_count){
                    $output .=  " (" . $related_post->comment_count . ")";
                }
                    
                $output .=  '</a></p>';
        }

    $output = '' . $output . '';
    return $output;   
}    

/**
 * LAST CLASS to CATEGORIES
 *
 * add LAST class to page and category lists
 * @deprecated since 7.0
 * @since 6.0
 */
function add_last_class($input) {
	if( !empty($input) ) {

		$pattern = '/<li class="(?!.*<li class=")/is';
		$replacement = '<li class="last ';

		$input = preg_replace($pattern, $replacement, $input);

		echo $input;
	}
}

/**
 * CUSTOM SEARCH FORM
 *
 * add custom search form
 * @deprecated since 7.0
 * @since 6.0
 */
function bizz_search_form() {
    
?>
<form method="get" class="search searchform" action="<?php home_url(); ?>">
    <div>
    <input type="text" class="field s" name="s" value="" />
    <button><span><!----></span></button>
    <input type="hidden" class="submit" name="submit" />
    </div>
</form>
<?php
	
}

/**
 * THEME FOOTER BRANDING
 *
 * add custom footer branding to theme footer
 * @since 6.0
 */
function bizz_footer_branding( $return = false ) {
	global $themeid, $opt;
	
	$layouts_active = get_option($themeid.'_sidebars_widgets');
	$args = array( 'post_type' => 'bizz_widget' ); 
	$layouts_active = get_posts( $args );
	if ( $layouts_active ){
    
		$blogo = ( isset($opt['bizzthemes_branding_front_logo']['value']) && $opt['bizzthemes_branding_front_logo']['value'] <> '') ? $opt['bizzthemes_branding_front_logo']['value'] : BIZZ_THEME_IMAGES .'/credits-trans.png';
		$blink = ( isset($opt['bizzthemes_branding_front_link']['value']) && $opt['bizzthemes_branding_front_link']['value'] <> '') ? $opt['bizzthemes_branding_front_link']['value'] : esc_url('bizzthemes.com');
		$btitle = ( isset($opt['bizzthemes_branding_front_logo']['value']) && $opt['bizzthemes_branding_front_logo']['value'] <> '') ? '' : ' title="Designed by BizzThemes, powered by WordPress"';
		$bimgmeta = ( isset($opt['bizzthemes_branding_front_logo']['value']) && $opt['bizzthemes_branding_front_logo']['value'] <> '') ? '' : ' alt="BizzThemes" height="28" width="115"';
		
		$credentials = '';

		if ( isset($opt['bizzthemes_branding_front_remove']['value']) && $opt['bizzthemes_branding_front_remove']['value'] == 'true') {} else {
			$credentials .= "<div class='powered'>";
			$credentials .= "<a href='$blink'$btitle>";
			$credentials .= "<img src='$blogo'$bimgmeta />";
			$credentials .= "</a>";    
			$credentials .= "</div>";
		}

		if ( $return )
			return $credentials;
		else
			echo $credentials;
	
	}
}

/**
 * RSS BUTTON
 *
 * rss feed icon that pulls feed link from frame/theme options
 * @since 6.0
 */
function bizz_feed_spot() {
	global $opt;
    
?>
    <div class="feed-spot">
<?php 
	if ( isset($opt['bizzthemes_img_rss']) )
	    $rss_icon = $opt['bizzthemes_img_rss'];
    else
	    $rss_icon = BIZZ_THEME_IMAGES .'/rss-small.png';
    
	if ( $opt['bizzthemes_feedburner_url']['value'] <> "" ) { ?> 
		<a class="rss-button" href="<?php echo stripslashes($opt['bizzthemes_feedburner_url']['value']); ?>"><img src="<?php echo $rss_icon; ?>" alt="RSS" /></a>
<?php 
    } else { 
?>
		<a class="rss-button" href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><img src="<?php echo $rss_icon; ?>" alt="RSS" /></a>
<?php 
    }
?>
	</div><!--/.feed-spot-->
<?php
	
}

/**
 * CONTACT FORM SCRIPT
 *
 * custom contact form script that may be used inside widget or as a function
 * @since 6.0
 */
function bizz_contact_form($wid_email,$wid_trans1,$wid_trans2,$wid_trans3,$wid_trans7,$wid_trans9,$wid_trans10,$wid_trans11,$wid_trans12,$wid_trans13,$wid_trans14,$wid_trans15,$wid_trans16,$wid_trans17,$wid_trans18,$wid_trans19) {

$wid_id = bizz_rand_sha1(3);

if( isset($_POST) )
	update_option( 'cformtest', $_POST );
	update_option( 'cformtest2', $wid_id );

// FORM SETTINGS: start
//If the form is submitted
if( isset($_POST["submitted"]) ) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError =  $wid_trans1; 
			$hasError = true;
		} 
		else {
			$name = trim($_POST['contactName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = $wid_trans1;
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = $wid_trans2;
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = $wid_trans1;
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(wpautop(trim($_POST['comments'])));
			} else {
				$comments = wpautop(trim($_POST['comments']));
			}
		}
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
		$headers .= 'From: <'.$email.'>' . "\r\n";
		$emailTo = $wid_email; 
		$subject = $wid_trans3.$name;
		$sendCopy = trim($_POST['sendCopy']);
		$body = '<html><body>';
		$body .= '<table rules="all" style="border-color:#dddddd;" cellpadding="10">';
		$body .= "<tr style='background: #eee;'><td><strong>$wid_trans14</strong> </td><td>$name</td></tr>";
		$body .= "<tr><td><strong>$wid_trans15</strong> </td><td>$email</td></tr>";
		$body .= "<tr><td><strong>$wid_trans16</strong> </td><td>$comments</td></tr>";
		$body .= "</table>";
		$body .= "</body></html>";
		
		// Load array with comment data.
		$commentx = array(
                'author' => $name,
                'email' => $email,
                'website' => '',
                'body' => $comments,
                'permalink' => ''
		);
		
		$hasSpam = 'false';
		
		// hidden field is not empty?
		if ( isset($_POST['checking']) && $_POST['checking'] == '' ) {
			$hasSpam = 'true';
		}
				
		// Send mail if there is no error and no spam
		if( !isset($hasError) && $hasSpam == 'false' ) { #If there is no error, send the email
			wp_mail($emailTo, $subject, $body, $headers);
			if($sendCopy == true) {
				$subject = $wid_trans7.$wid_email;
				wp_mail($email, $subject, $body, $headers);
			}
			$emailSent = true;
		}

	}
}

	$requested_url  = is_ssl() ? 'https://' : 'http://';
	$requested_url .= $_SERVER['HTTP_HOST'];
	$requested_url .= $_SERVER['REQUEST_URI'];

	echo '<input type="hidden" class="cform-id" name="cform-id" value="'. $wid_id .'" />';
	echo '<input type="hidden" class="cform-trans9" name="cform-trans9" value="'. $wid_trans9 .'" />';
	echo '<input type="hidden" class="cform-trans10" name="cform-trans10" value="'. $wid_trans10 .'" />';
	echo '<input type="hidden" class="cform-trans11" name="cform-trans11" value="'. $wid_trans11 .'" />';

// FORM SETTINGS: end
?>
<?php if(isset($hasError) ) { ?>
	<p class="alert"><?php echo $wid_trans12; ?></p>
<?php } ?>

<?php if ( $wid_email == '' ) { ?>
	<p class="alert"><?php echo $wid_trans13; ?></p>
<?php } ?>
<form action="<?php echo esc_url( $requested_url ); ?>" id="contactForm<?php echo $wid_id; ?>" method="post">
	<ol class="forms">
		<li>
			<label for="contactName"><?php echo $wid_trans14; ?></label>
			<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="txt requiredField" />
			<?php if(isset($nameError) && $nameError != '') { ?>
				<span class="error"><?php echo $nameError;?></span> 
			<?php } ?>
		</li>
		<li>
			<label for="email"><?php echo $wid_trans15; ?></label>
			<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="txt requiredField email" />
			<?php if(isset($emailError) && $emailError != '') { ?>
				<span class="error"><?php echo $emailError;?></span>
			<?php } ?>
		</li>
		<li class="textarea">
			<label for="commentsText"><?php echo $wid_trans16; ?></label>
			<textarea name="comments" id="commentsText" rows="20" cols="30" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
			<?php if(isset($commentError) && $commentError != '') { ?>
				<span class="error"><?php echo $commentError;?></span> 
			<?php } ?>
		</li>
		<li class="inline">
			<input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> />
			<label for="sendCopy"><?php echo $wid_trans17; ?></label>
		</li>
		<li class="screenReader">
			<label for="checking" class="screenReader"><?php echo $wid_trans18; ?></label>
			<input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
		</li>
		<li class="buttons">
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<input class="submit button" type="submit" value="<?php echo $wid_trans19; ?>" />
			<div class="load" style="display:none;"><?php _e('Sending...', 'bizzthemes'); ?></div>
		</li>
	</ol>
</form>
<?php

}

/**
 * CURRENT URL
 *
 * get current site url
 * @deprecated 7.0
 * @since 6.0
 */
function bizz_cur_URL(){
    $pageURL = 'http';
	if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) { $pageURL .= "s"; }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
	    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
	    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	
	return $pageURL;
}

/**
 * TABS - popular
 *
 * get popular posts for tabs
 * @since 7.0
 */
function bizz_tabs_popular( $posts = 5, $size = 35 ) {
	global $wpdb;
	
	$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , 5");
	foreach ($result as $post) {
	//setup_postdata($post);
	$postid = $post->ID;
?>
<li>
	<a title="<?php echo $post->post_title; ?>" href="<?php echo get_permalink($postid); ?>"><?php echo $post->post_title; ?></a>
	<span class="meta"><?php the_time('M j, Y'); ?></span>
	<div class="fix"></div>
</li>
<?php 
	}
}

/**
 * TABS - latest
 *
 * get latest posts for tabs
 * @since 7.0
 */
function bizz_tabs_latest( $posts = 5, $size = 35 ) {
	global $wp_version;
	if (version_compare($wp_version, '3.0.9', '>='))
		$the_query = new WP_Query('showposts='. $posts .'&ignore_sticky_posts=1&orderby=post_date&order=desc');
	else
		$the_query = new WP_Query('showposts='. $posts .'&caller_get_posts=1&orderby=post_date&order=desc');
			
	while ($the_query->have_posts()) : $the_query->the_post(); 
	
	$featured_exists = bizz_image('key=image&return=true');
?>
<li>
	<?php if ($size <> 0) bizz_image('key=image&width='.$size.'&height='.$size.'&class=thumbnail');; ?>
	<?php if ( isset($featured_exists) && !empty($featured_exists ) ) { ?>
		<div class="list-right">
	<?php } ?>
	<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
	<span class="meta"><?php the_time('M j, Y'); ?></span>
	<?php if ( isset($featured_exists) && !empty($featured_exists ) ) { ?>
		</div>
	<?php } ?>
	<div class="fix"></div>
</li>
<?php endwhile; 
}

/**
 * TABS - comments
 *
 * get latest comments for tabs
 * @since 7.0
 */
function bizz_tabs_comments( $posts = 5, $size = 35 ) {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
	comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
	comment_type,comment_author_url,
	SUBSTRING(comment_content,1,50) AS com_excerpt
	FROM $wpdb->comments
	LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
	$wpdb->posts.ID)
	WHERE comment_approved = '1' AND comment_type = '' AND
	post_password = ''
	ORDER BY comment_date_gmt DESC LIMIT ".$posts;
	
	$comments = $wpdb->get_results($sql);
	
	foreach ($comments as $comment) {
?>
	<li>
		<?php if ($size <> 0) echo get_avatar( $comment, $size ); ?>
		<div class="list-right">
		<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo $comment->post_title; ?>">
			<?php echo strip_tags($comment->comment_author); ?>: <?php echo strip_tags($comment->com_excerpt); ?>...
		</a>
		</div>
		<div class="fix"></div>
	</li>
<?php 
	}
}

/**
 * SEO OPTIONS
 *
 * all seo functions for theme framework
 * some parts of code used from Thesis 1.5 class 'Head' @Chris Pearson
 * @since 6.0
 */
 
// ADD BIZZ SEO
add_action('init', 'bizz_add_seo', 15);
function bizz_add_seo() {
	global $opt;
	
	// Remove Bizz SEO if applicable
	if ( isset($opt['bizzthemes_seo_remove']['value']) ) {
		
		// Remove title
		remove_filter( 'wp_title', 'bizzthemes_default_title', 10, 3 );
		
		// Remove default WP header actions
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'start_post_rel_link');
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		remove_action('wp_head', 'parent_post_rel_link');
		remove_action('wp_head', 'rel_canonical');
		remove_action('wp_head', 'wp_shortlink_wp_head');
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		
		// Remove capital P filter.
		foreach (array('the_content', 'the_title', 'comment_text') as $filter)
			remove_filter($filter, 'capital_P_dangit');
		
		// Add meta
		add_action('bizz_head_meta', 'bizz_seo_noindex');
		add_action('bizz_head_meta', 'bizz_seo_canonical');
		
	}
	
	//* Disable Bizznis <title> generation if SEO Title Tag is active
	if ( function_exists( 'seo_title_tag' ) ) {
		remove_filter( 'wp_title', 'bizzthemes_default_title', 10, 3 );
		remove_action( 'bizz_head_title', 'wp_title' );
		add_action( 'bizz_head_title', 'seo_title_tag' );
	}
	
}

// Title meta tag
add_action( 'bizz_head_title', 'wp_title' );
add_filter( 'wp_title', 'bizzthemes_default_title', 10, 3 );
function bizzthemes_default_title( $title, $sep, $seplocation ) {
	global $wp_query, $opt;

	if ( is_feed() )
		return trim( $title );

	$sep = '|';
	$seplocation = 'right';

	//* If viewing the home page
	if ( is_front_page() ) {
		//* Determine the doctitle
		$title = get_bloginfo( 'name' );

		//* Append site description, if necessary
		$title = isset($opt['bizzthemes_title_tagline']['value']) ? $title . " $sep " . get_bloginfo( 'description' ) : $title;
	}

	//* if viewing a post / page / attachment
	if ( is_singular() ) {
		//* The User Defined Title (Bizznis)
		if ( bizzthemes_get_custom_field( 'bizzthemes_title' ) )
			$title = bizzthemes_get_custom_field( 'bizzthemes_title' );
		//* All-in-One SEO Pack Title (latest, vestigial)
		elseif ( bizzthemes_get_custom_field( '_aioseop_title' ) )
			$title = bizzthemes_get_custom_field( '_aioseop_title' );
		//* Headspace Title (vestigial)
		elseif ( bizzthemes_get_custom_field( '_headspace_page_title' ) )
			$title = bizzthemes_get_custom_field( '_headspace_page_title' );
		//* Thesis Title (vestigial)
		elseif ( bizzthemes_get_custom_field( 'thesis_title' ) )
			$title = bizzthemes_get_custom_field( 'thesis_title' );
		//* SEO Title Tag (vestigial)
		elseif ( bizzthemes_get_custom_field( 'title_tag' ) )
			$title = bizzthemes_get_custom_field( 'title_tag' );
		//* All-in-One SEO Pack Title (old, vestigial)
		elseif ( bizzthemes_get_custom_field( 'title' ) )
			$title = bizzthemes_get_custom_field( 'title' );
	}

	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term  = $wp_query->get_queried_object();
		$title = ! empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}

	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term  = $wp_query->get_queried_object();
		$title = ! empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}

	if ( is_tax() ) {
		$term  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$title = ! empty( $term->meta['doctitle'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['doctitle'] ) ) : $title;
	}

	if ( is_author() ) {
		$user_title = get_the_author_meta( 'doctitle', (int) get_query_var( 'author' ) );
		$title      = $user_title ? $user_title : $title;
	}

	//* If we don't want site name appended, or if we're on the home page
	if ( ! isset($opt['bizzthemes_title_title']['value']) || is_front_page() )
		return esc_html( trim( $title ) );

	//* Else append the site name
	$title = 'right' == $seplocation ? $title . " $sep " . get_bloginfo( 'name' ) : get_bloginfo( 'name' ) . " $sep " . $title;
	
	return esc_html( trim( $title ) );
}

// filter WP title
add_filter( 'wp_title', 'bizzthemes_doctitle_wrap', 20 );
function bizzthemes_doctitle_wrap( $title ) {
	return is_feed() || is_admin() ? $title : sprintf( "<title>%s</title>\n", $title );
}

// Meta description
add_action( 'bizz_head_meta', 'bizznis_seo_meta_description' );
function bizznis_seo_meta_description() {
	global $wp_query, $opt;

	$description = '';

	//* If we're on the home page
	if ( is_front_page() )
		$description = isset($opt['bizzthemes_meta_description']['value']) ? $opt['bizzthemes_meta_description']['value'] : get_bloginfo( 'description' );

	//* If we're on a single post / page / attachment
	if ( is_singular() ) {
		//* Description is set via custom field
		if ( bizzthemes_get_custom_field( 'bizzthemes_description' ) )
			$description = bizzthemes_get_custom_field( 'bizzthemes_description' );
		//* All-in-One SEO Pack (latest, vestigial)
		elseif ( bizzthemes_get_custom_field( '_aioseop_description' ) )
			$description = bizzthemes_get_custom_field( '_aioseop_description' );
		//* Headspace2 (vestigial)
		elseif ( bizzthemes_get_custom_field( '_headspace_description' ) )
			$description = bizzthemes_get_custom_field( '_headspace_description' );
		//* Thesis (vestigial)
		elseif ( bizzthemes_get_custom_field( 'thesis_description' ) )
			$description = bizzthemes_get_custom_field( 'thesis_description' );
		//* All-in-One SEO Pack (old, vestigial)
		elseif ( bizzthemes_get_custom_field( 'description' ) )
			$description = bizzthemes_get_custom_field( 'description' );
	}

	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term = $wp_query->get_queried_object();
		$description = ! empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}

	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term = $wp_query->get_queried_object();
		$description = ! empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}

	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$description = ! empty( $term->meta['description'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['description'] ) ) : '';
	}

	if ( is_author() ) {
		$user_description = get_the_author_meta( 'meta_description', (int) get_query_var( 'author' ) );
		$description = $user_description ? $user_description : '';
	}

	//* Add the description if one exists
	if ( $description )
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";

}

// Link author if applicable
add_action( 'bizz_head_links', 'bizznis_rel_author' );
function bizznis_rel_author() {
	global $post;

	if ( is_singular() && isset( $post->post_author ) && $gplus_url = get_user_option( 'googleplus', $post->post_author ) ) {
		printf( '<link rel="author" href="%s" />' . "\n", esc_url( $gplus_url ) );
		return;
	}

	if ( is_author() && get_query_var( 'author' ) && $gplus_url = get_user_option( 'googleplus', get_query_var( 'author' ) ) ) {
		printf( '<link rel="author" href="%s" />' . "\n", esc_url( $gplus_url ) );
		return;
	}

}

// NOINDEX, NOFOLLOW tags: sections specified in theme admin
function bizz_seo_noindex() {	
	global $opt;
	
	if (get_option('blog_public') <> '0') {
		$current_page = get_query_var('paged');

		// Index the content? specified for secific page or post by meta tags
		if (is_page() || is_single()) {
			global $post;
			if (get_post_meta($post->ID, 'bizzthemes_noindex', true))
				$meta_noindex = '<meta name="robots" content="noindex, nofollow" />';
			
			$seopages = get_inc_pages("pag_exclude_seo_");
			$seoarr = explode(",",$seopages);
			$seoarr = array_diff($seoarr, array(""));
			foreach ( $seoarr as $seoitem ) {
				if (($post->ID == $seoitem) && !get_post_meta($post->ID, 'bizzthemes_noindex', true))
					$meta_noindex = '<meta name="robots" content="noindex, nofollow" />';
			}
				
		// Index the content? specified for global content in theme option panel
		} elseif (
			is_search() || is_404() || // search & 404 page get noindex and nofollow by default
			(is_category() && isset($opt['bizzthemes_noindex_category']['value']) ) ||
			(is_tag() && isset($opt['bizzthemes_noindex_tag']['value']) ) || 
			(is_author() && isset($opt['bizzthemes_noindex_author']['value']) ) || 
			(is_day() && isset($opt['bizzthemes_noindex_daily']['value']) ) || 
			(is_month() && isset($opt['bizzthemes_noindex_monthly']['value']) ) || 
			(is_year() && isset($opt['bizzthemes_noindex_yearly']['value']) ) || 
			$current_page > 1 // noindex and nofollow paged content
		)
			$meta_noindex = '<meta name="robots" content="noindex, nofollow" />';
			
		if (isset($meta_noindex))
			echo $meta_noindex . "\n";
			
		$nodir = array(); 
		if ( isset($opt['bizzthemes_noodp_meta']['value']) )
			$nodir[] = 'noodp';
		if ( isset($opt['bizzthemes_noydir_meta']['value']) )
			$nodir[] = 'noydir';
		if ( isset($nodir) )	
			echo $meta_nodir = '<meta name="robots" content="' . implode(', ', $nodir) . '" />' . "\n";
			
	}
}
	
// CANONICAL URLs
function bizz_seo_canonical() {	
	global $opt;
	
	if (!function_exists('yoast_canonical_link') && !class_exists('All_in_One_SEO_Pack') && isset($opt['bizzthemes_canonical_url']['value']) ) {
		if (is_single() || is_page()) {
			global $post;				
			$url = (is_page() && get_option('show_on_front') == 'page' && get_option('page_on_front') == $post->ID) ? trailingslashit(get_permalink()) : get_permalink();
		}
		elseif (is_author()) {
			$author = get_userdata(get_query_var('author'));
			$url = get_author_posts_url(get_query_var('author'));
		}
		elseif (is_category())
			$url = get_category_link(get_query_var('cat'));
		elseif (is_tag()) {
			$tag = get_term_by('slug', get_query_var('tag'), 'post_tag');

			if (!empty($tag->term_id))
				$url = get_tag_link($tag->term_id);
		}
		elseif (is_day())
			$url = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
		elseif (is_month())
			$url = get_month_link(get_query_var('year'), get_query_var('monthnum'));
		elseif (is_year())
			$url = get_year_link(get_query_var('year'));
		elseif (is_home())
			$url = (get_option('show_on_front') == 'page') ? trailingslashit(get_permalink(get_option('page_for_posts'))) : trailingslashit(home_url());
		elseif (is_search())
			$url = get_search_link(get_query_var('s'));
		else
			$url = trailingslashit(get_permalink());
		
		echo '<link rel="canonical" href="' . $url . '" />' . "\n";
	}
	
}

// 301 Redirect
function bizz_redirect() {
	global $wp_query;
	if ($wp_query->is_singular) {
		$redirect = get_post_meta($wp_query->post->ID, 'bizzthemes_redirect', true);
		if ($redirect) wp_redirect($redirect, 301);
	}
}
add_action('template_redirect', 'bizz_redirect');

/**
 * QUOTES FIX for SERIALIZED DATA
 *
 * fix all quatation marks for serialized data
 * @since 7.0
 */
function bizz_reverse_escape( $str ) {
	$search=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
	$replace=array("\\","\0","\n","\r","\x1a","'",'"');
	return str_replace( $search, $replace, $str );
}

/**
 * ONLINE CHECK
 *
 * check if user is currently connected to the internet
 * @since 7.0
 */
function bizz_is_online(){
    if ( !$sock = @fsockopen('www.google.com', 80, $num, $error, 0.1) )
		return false;
	else
		return true;
}

/**
 * SET DEFAULT LAYOUTS on THEME ACTIVATION or PREVIEW
 *
 * set default layout configuration when user activates or previews the theme
 * @since 7.2.5
 */
add_action('bizz_head_before', 'bizz_maybe_set_defaults');
function bizz_maybe_set_defaults( $run = false ){
	global $themeid;
	
	$themeid 				= $themeid;
	$saved_defaults 		= get_option('bizz_defaults_' . $themeid);
	$saved_reset		 	= get_option('bizz_reset_' . $themeid);
	$saved_themesidebars 	= get_option($themeid . '_sidebars_widgets');
	
	$args = array( 'post_type' => 'bizz_widget', 'numberposts' => -1 ); 
	$layout_posts = get_posts( $args );
	
/*	
	delete_option('bizz_defaults_' . $themeid);
	delete_option('bizz_reset_' . $themeid);
	delete_option($themeid . '_sidebars_widgets');
	bizzthemes_default_layouts('reset'); # set
	delete_option('bizz_reset_' . $themeid);
*/	

	if ( empty( $saved_defaults ) && empty( $saved_themesidebars ) && empty( $saved_reset ) && !$layout_posts ) { #security checks
		if ( is_preview() ) {
			bizzthemes_default_layouts('set_defaults'); # set
			$homeurl = trailingslashit( site_url() );
			$page    = "$homeurl?preview=1&template=$themeid&stylesheet=$themeid&preview_iframe=1&TB_iframe=true&stoprefresh=1";
			if( !isset($_GET['stoprefresh']) ) {
				header("Refresh: 1; url=$page");
				print_r('<br/><br/><center>Loading defaults...</center>');
			}
		}
		elseif ( $run )
			bizzthemes_default_layouts('set_defaults'); # set
	}

}

/**
 * PHP4 compatibility - array_intersect_key
 *
 * @since 7.2.5
 */
if (!function_exists('array_intersect_key')) {
    function array_intersect_key ($isec, $arr2) {
        $argc = func_num_args();
        for ($i = 1; !empty($isec) && $i < $argc; $i++){
            $arr = func_get_arg($i);
            foreach ($isec as $k => $v)
                if (!isset($arr[$k]))
                    unset($isec[$k]);
        }
        return $isec;
    }
}

/**
 * PHP4 compatibility - array_diff_key
 *
 * @since 7.2.5
 */
if (!function_exists('array_diff_key')) {
    function array_diff_key() {
        $arrs = func_get_args();
        $result = array_shift($arrs);
        foreach ($arrs as $array) {
            foreach ($result as $key => $v) {
                if (array_key_exists($key, $array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
   }
}

/**
 * PHP4 compatibility - array_combine
 *
 * @since 7.3.0
 */
if (!function_exists('array_combine')) {
    function array_combine($arr1,$arr2) {
		$out = array();
		foreach ($arr1 as $key1 => $value1) {
			$out[$value1] = $arr2[$key1];
		}
		return $out;
	}
}

/**
 * PHP4 compatibility - file_get_contents
 *
 * @since 7.3.3
 */
if (!function_exists('file_get_contents')){
	function file_get_contents($filename){
		$fhandle = fopen($filename, "r");
		$fcontents = fread($fhandle, filesize($filename));
		fclose($fhandle);
		return $fcontents;
	}
}

/**
 * POST ID by TITLE
 *
 * get post ID by querying post title
 * @since 7.3.5
 */
function bizz_post_by_title($page_title, $output = OBJECT) {
    global $wpdb;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s", $page_title ));
        if ( $post )
            return get_post($post, $output);

    return null;
}

/**
 * Captures output from a function
 *
 * @param $callback is the name of the function
 * @return the output of the function
 * @since 7.5.6
 */
function bizz_capture($callback) {
    $args = func_get_args();
    array_shift($args);
    ob_start();
    if (count($args))
        call_user_func_array($callback, $args);
    else
        call_user_func($callback);
    $output = ob_get_contents();
    ob_end_clean();
	
    return $output;
}

/**
 * Make WP Image Galleries open in Lightbox Gallery
 *
 * @since 7.5.8
 */
function bizz_init_rel() {
	add_filter( 'wp_get_attachment_link', 'bizz_add_rel', 10, 2 );
}
add_action('init', 'bizz_init_rel');

function bizz_add_rel( $link, $id ) {
	// 'rel' attribute exists?
	$atag = preg_match('#<a\s+(.*?)(rel=([\'"])(.*?)\3)(.*?)>(.*)#i', $link, $matches);
	if ($atag) {
		// Match found.
		$quot = $matches[3];
		$relval = $quot . $matches[4] . " gallery-{$id}" . $quot;
		$before = $matches[1];
		$after = $matches[5];
		$rest = $matches[6];
		$link = "<a {$before}rel={$relval}{$after}>{$rest}";
	} 
	else {
		$atag = preg_match('#<a\s+(.*?)>(.*)#i', $link, $matches);
		if ($atag) {
			// Match found.
			$innards = $matches[1];
			$rest = $matches[2];
			$relval = "gallery-{$id}";
			$link = "<a {$innards} rel='{$relval}'>{$rest}";
		}
	}
	return $link;
}

/**
 * Add menu items to the WordPress Admin Bar.
 *
 * @since 7.6.4
 */
add_action( 'admin_bar_menu', 'bizz_admin_bar_menu', 20 );
function bizz_admin_bar_menu () {
	global $wp_admin_bar, $current_user, $opt;
	
	if ( !is_super_admin() || !is_admin_bar_showing() || is_admin() )
      return;
	
	// SHOW layout engine?
	$adminmenu_layout = ( isset($opt['bizzthemes_adminmenu_layout']['value']) ) ? $opt['bizzthemes_adminmenu_layout']['value'] : '';
	$href = ( $adminmenu_layout != 'true' ) ? admin_url('admin.php?page=bizz-layout') : admin_url('admin.php?page=bizzthemes');
	
	// Current User
    $current_user_id = $current_user->user_login;
    $super_user = get_option( 'framework_bizz_super_user' );

	// Main Menu Item
	$wp_admin_bar->add_menu( array( 'id' => 'bizzthemes', 'title' => __( 'Theme Options', 'bizzthemes' ), 'href' => $href ) );
	
	$adminmenu_layout = ( isset($opt['bizzthemes_adminmenu_layout']['value']) ) ? $opt['bizzthemes_adminmenu_layout']['value'] : '' ;
	$adminmenu_design = ( isset($opt['bizzthemes_adminmenu_design']['value']) ) ? $opt['bizzthemes_adminmenu_design']['value'] : '' ;
	$adminmenu_license = ( isset($opt['bizzthemes_adminmenu_license']['value']) ) ? $opt['bizzthemes_adminmenu_license']['value'] : '' ;
	$adminmenu_editor = ( isset($opt['bizzthemes_adminmenu_editor']['value']) ) ? $opt['bizzthemes_adminmenu_editor']['value'] : '' ;
	$adminmenu_version = ( isset($opt['bizzthemes_adminmenu_version']['value']) ) ? $opt['bizzthemes_adminmenu_version']['value'] : '' ;
	$adminmenu_tools = ( isset($opt['bizzthemes_adminmenu_tools']['value']) ) ? $opt['bizzthemes_adminmenu_tools']['value'] : '' ;
	
	if ( $adminmenu_layout != 'true' )
		$wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-layout', 'title' => __( 'Builder', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-layout') ) );
		
	$wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-framework', 'title' => __( 'Framework', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizzthemes') ) );
	
	if ( $adminmenu_design != 'true' )
	    $wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-design', 'title' => __( 'Design', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-design') ) );
	
	if ( $adminmenu_license != 'true' )
	    $wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-license', 'title' => __( 'License', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-license') ) );
	
	if ( $adminmenu_editor != 'true' )
	    $wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-editor', 'title' => __( 'Editor', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-editor') ) );
	
	if ( $adminmenu_tools != 'true' )
	    $wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-backup', 'title' => __( 'Backup', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-tools') ) );
	
	if ( $adminmenu_version != 'true' )
	    $wp_admin_bar->add_menu( array( 'parent' => 'bizzthemes', 'id' => 'bizzthemes-update', 'title' => __( 'Update', 'bizzthemes' ), 'href' => admin_url('admin.php?page=bizz-update') ) );


}

/**
 * Remove WordPress Admin Bar.
 *
 * @since 7.6.4
 */
add_action( 'init', 'bizz_disable_admin_bar' , 11 );
function bizz_disable_admin_bar() {
	global $opt;
		
	// remove admin bar?
	if ( isset($opt['bizzthemes_admin_bar_remove']['value']) )
		add_filter( 'show_admin_bar', '__return_false' );

}

/**
 * Remove Theme Options from WordPress Admin Bar.
 *
 * @since 7.6.4
 */
add_action( 'wp_before_admin_bar_render', 'bizz_admin_bar_render' );
function bizz_admin_bar_render() {
    global $wp_admin_bar, $opt;
	
    // remove theme options?
	if ( isset($opt['bizzthemes_admin_bar_options_remove']['value']) )
		$wp_admin_bar->remove_menu('bizzthemes');
}

/**
 * Remove WordPress Update Notifications.
 *
 * @since 7.6.5
 */
add_action('admin_menu','bizz_hide_wp_update_notifications');
function bizz_hide_wp_update_notifications() {
	global $opt;
	
	// remove admin bar?
	if ( isset($opt['bizzthemes_wp_update_notice_remove']['value']) )
		remove_action( 'admin_notices', 'update_nag', 3 );

}

/**
 * Check if on theme admin pages.
 *
 * @since 7.7.2
 */
function bizz_is_admin() {
	global $pagenow;
	
	$options = array('bizz-layout', 'bizzthemes', 'bizz-design', 'bizz-editor', 'bizz-license', 'bizz-tools', 'bizz-update');
	
	if ( !is_admin() )
		return false;
	elseif ( isset($_GET['page']) && 'admin.php' == $pagenow && in_array($_GET['page'], $options) )
		return true;
		
	return false;
}

function bizz_remove_wp_theme_update() {
	# 2.8 to 3.0:
	remove_action( 'load-themes.php', 'wp_update_themes' );
	remove_action( 'load-update.php', 'wp_update_themes' );
	remove_action( 'admin_init', '_maybe_update_themes' );
	remove_action( 'wp_update_themes', 'wp_update_themes' );
	add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );

	# 3.0:
	remove_action( 'load-update-core.php', 'wp_update_themes' );
	add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
}
add_action('init', 'bizz_remove_wp_theme_update');

/**
 * Return country list
 *
 * @since 7.7.6
 */
function bizz_country_list() {
	$country_list = array(
		array("value" => "US", "name" => "United States"),
		array("value" => "GB", "name" => "United Kingdom"),
		array("value" => "AF", "name" => "Afghanistan"),
		array("value" => "AL", "name" => "Albania"),
		array("value" => "DZ", "name" => "Algeria"),
		array("value" => "AS", "name" => "American Samoa"),
		array("value" => "AD", "name" => "Andorra"),
		array("value" => "AO", "name" => "Angola"),
		array("value" => "AI", "name" => "Anguilla"),
		array("value" => "AQ", "name" => "Antarctica"),
		array("value" => "AG", "name" => "Antigua And Barbuda"),
		array("value" => "AR", "name" => "Argentina"),
		array("value" => "AM", "name" => "Armenia"),
		array("value" => "AW", "name" => "Aruba"),
		array("value" => "AU", "name" => "Australia"),
		array("value" => "AT", "name" => "Austria"),
		array("value" => "AZ", "name" => "Azerbaijan"),
		array("value" => "BS", "name" => "Bahamas"),
		array("value" => "BH", "name" => "Bahrain"),
		array("value" => "BD", "name" => "Bangladesh"),
		array("value" => "BB", "name" => "Barbados"),
		array("value" => "BY", "name" => "Belarus"),
		array("value" => "BE", "name" => "Belgium"),
		array("value" => "BZ", "name" => "Belize"),
		array("value" => "BJ", "name" => "Benin"),
		array("value" => "BM", "name" => "Bermuda"),
		array("value" => "BT", "name" => "Bhutan"),
		array("value" => "BO", "name" => "Bolivia"),
		array("value" => "BA", "name" => "Bosnia And Herzegowina"),
		array("value" => "BW", "name" => "Botswana"),
		array("value" => "BV", "name" => "Bouvet Island"),
		array("value" => "BR", "name" => "Brazil"),
		array("value" => "IO", "name" => "British Indian Ocean Territory"),
		array("value" => "BN", "name" => "Brunei Darussalam"),
		array("value" => "BG", "name" => "Bulgaria"),
		array("value" => "BF", "name" => "Burkina Faso"),
		array("value" => "BI", "name" => "Burundi"),
		array("value" => "KH", "name" => "Cambodia"),
		array("value" => "CM", "name" => "Cameroon"),
		array("value" => "CA", "name" => "Canada"),
		array("value" => "CV", "name" => "Cape Verde"),
		array("value" => "KY", "name" => "Cayman Islands"),
		array("value" => "CF", "name" => "Central African Republic"),
		array("value" => "TD", "name" => "Chad"),
		array("value" => "CL", "name" => "Chile"),
		array("value" => "CN", "name" => "China"),
		array("value" => "CX", "name" => "Christmas Island"),
		array("value" => "CC", "name" => "Cocos (Keeling) Islands"),
		array("value" => "CO", "name" => "Colombia"),
		array("value" => "KM", "name" => "Comoros"),
		array("value" => "CG", "name" => "Congo"),
		array("value" => "CD", "name" => "Congo, The Democratic Republic Of The"),
		array("value" => "CK", "name" => "Cook Islands"),
		array("value" => "CR", "name" => "Costa Rica"),
		array("value" => "CI", "name" => "Cote D'Ivoire"),
		array("value" => "HR", "name" => "Croatia (Local Name: Hrvatska)"),
		array("value" => "CU", "name" => "Cuba"),
		array("value" => "CY", "name" => "Cyprus"),
		array("value" => "CZ", "name" => "Czech Republic"),
		array("value" => "DK", "name" => "Denmark"),
		array("value" => "DJ", "name" => "Djibouti"),
		array("value" => "DM", "name" => "Dominica"),
		array("value" => "DO", "name" => "Dominican Republic"),
		array("value" => "TP", "name" => "East Timor"),
		array("value" => "EC", "name" => "Ecuador"),
		array("value" => "EG", "name" => "Egypt"),
		array("value" => "SV", "name" => "El Salvador"),
		array("value" => "GQ", "name" => "Equatorial Guinea"),
		array("value" => "ER", "name" => "Eritrea"),
		array("value" => "EE", "name" => "Estonia"),
		array("value" => "ET", "name" => "Ethiopia"),
		array("value" => "FK", "name" => "Falkland Islands (Malvinas)"),
		array("value" => "FO", "name" => "Faroe Islands"),
		array("value" => "FJ", "name" => "Fiji"),
		array("value" => "FI", "name" => "Finland"),
		array("value" => "FR", "name" => "France"),
		array("value" => "FX", "name" => "France, Metropolitan"),
		array("value" => "GF", "name" => "French Guiana"),
		array("value" => "PF", "name" => "French Polynesia"),
		array("value" => "TF", "name" => "French Southern Territories"),
		array("value" => "GA", "name" => "Gabon"),
		array("value" => "GM", "name" => "Gambia"),
		array("value" => "GE", "name" => "Georgia"),
		array("value" => "DE", "name" => "Germany"),
		array("value" => "GH", "name" => "Ghana"),
		array("value" => "GI", "name" => "Gibraltar"),
		array("value" => "GR", "name" => "Greece"),
		array("value" => "GL", "name" => "Greenland"),
		array("value" => "GD", "name" => "Grenada"),
		array("value" => "GP", "name" => "Guadeloupe"),
		array("value" => "GU", "name" => "Guam"),
		array("value" => "GT", "name" => "Guatemala"),
		array("value" => "GN", "name" => "Guinea"),
		array("value" => "GW", "name" => "Guinea-Bissau"),
		array("value" => "GY", "name" => "Guyana"),
		array("value" => "HT", "name" => "Haiti"),
		array("value" => "HM", "name" => "Heard And Mc Donald Islands"),
		array("value" => "VA", "name" => "Holy See (Vatican City State)"),
		array("value" => "HN", "name" => "Honduras"),
		array("value" => "HK", "name" => "Hong Kong"),
		array("value" => "HU", "name" => "Hungary"),
		array("value" => "IS", "name" => "Iceland"),
		array("value" => "IN", "name" => "India"),
		array("value" => "ID", "name" => "Indonesia"),
		array("value" => "IR", "name" => "Iran (Islamic Republic Of)"),
		array("value" => "IQ", "name" => "Iraq"),
		array("value" => "IE", "name" => "Ireland"),
		array("value" => "IL", "name" => "Israel"),
		array("value" => "IT", "name" => "Italy"),
		array("value" => "JM", "name" => "Jamaica"),
		array("value" => "JP", "name" => "Japan"),
		array("value" => "JO", "name" => "Jordan"),
		array("value" => "KZ", "name" => "Kazakhstan"),
		array("value" => "KE", "name" => "Kenya"),
		array("value" => "KI", "name" => "Kiribati"),
		array("value" => "KP", "name" => "Korea, Democratic People's Republic Of"),
		array("value" => "KR", "name" => "Korea, Republic Of"),
		array("value" => "KW", "name" => "Kuwait"),
		array("value" => "KG", "name" => "Kyrgyzstan"),
		array("value" => "LA", "name" => "Lao People's Democratic Republic"),
		array("value" => "LV", "name" => "Latvia"),
		array("value" => "LB", "name" => "Lebanon"),
		array("value" => "LS", "name" => "Lesotho"),
		array("value" => "LR", "name" => "Liberia"),
		array("value" => "LY", "name" => "Libyan Arab Jamahiriya"),
		array("value" => "LI", "name" => "Liechtenstein"),
		array("value" => "LT", "name" => "Lithuania"),
		array("value" => "LU", "name" => "Luxembourg"),
		array("value" => "MO", "name" => "Macau"),
		array("value" => "MK", "name" => "Macedonia, Former Yugoslav Republic Of"),
		array("value" => "MG", "name" => "Madagascar"),
		array("value" => "MW", "name" => "Malawi"),
		array("value" => "MY", "name" => "Malaysia"),
		array("value" => "MV", "name" => "Maldives"),
		array("value" => "ML", "name" => "Mali"),
		array("value" => "MT", "name" => "Malta"),
		array("value" => "MH", "name" => "Marshall Islands"),
		array("value" => "MQ", "name" => "Martinique"),
		array("value" => "MR", "name" => "Mauritania"),
		array("value" => "MU", "name" => "Mauritius"),
		array("value" => "YT", "name" => "Mayotte"),
		array("value" => "MX", "name" => "Mexico"),
		array("value" => "FM", "name" => "Micronesia, Federated States Of"),
		array("value" => "MD", "name" => "Moldova, Republic Of"),
		array("value" => "MC", "name" => "Monaco"),
		array("value" => "MN", "name" => "Mongolia"),
		array("value" => "ME", "name" => "Montenegro"),
		array("value" => "MS", "name" => "Montserrat"),
		array("value" => "MA", "name" => "Morocco"),
		array("value" => "MZ", "name" => "Mozambique"),
		array("value" => "MM", "name" => "Myanmar"),
		array("value" => "NA", "name" => "Namibia"),
		array("value" => "NR", "name" => "Nauru"),
		array("value" => "NP", "name" => "Nepal"),
		array("value" => "NL", "name" => "Netherlands"),
		array("value" => "AN", "name" => "Netherlands Antilles"),
		array("value" => "NC", "name" => "New Caledonia"),
		array("value" => "NZ", "name" => "New Zealand"),
		array("value" => "NI", "name" => "Nicaragua"),
		array("value" => "NE", "name" => "Niger"),
		array("value" => "NG", "name" => "Nigeria"),
		array("value" => "NU", "name" => "Niue"),
		array("value" => "NF", "name" => "Norfolk Island"),
		array("value" => "MP", "name" => "Northern Mariana Islands"),
		array("value" => "NO", "name" => "Norway"),
		array("value" => "OM", "name" => "Oman"),
		array("value" => "PK", "name" => "Pakistan"),
		array("value" => "PW", "name" => "Palau"),
		array("value" => "PA", "name" => "Panama"),
		array("value" => "PG", "name" => "Papua New Guinea"),
		array("value" => "PY", "name" => "Paraguay"),
		array("value" => "PE", "name" => "Peru"),
		array("value" => "PH", "name" => "Philippines"),
		array("value" => "PN", "name" => "Pitcairn"),
		array("value" => "PL", "name" => "Poland"),
		array("value" => "PT", "name" => "Portugal"),
		array("value" => "PR", "name" => "Puerto Rico"),
		array("value" => "QA", "name" => "Qatar"),
		array("value" => "RE", "name" => "Reunion"),
		array("value" => "RO", "name" => "Romania"),
		array("value" => "RU", "name" => "Russian Federation"),
		array("value" => "RW", "name" => "Rwanda"),
		array("value" => "KN", "name" => "Saint Kitts And Nevis"),
		array("value" => "LC", "name" => "Saint Lucia"),
		array("value" => "VC", "name" => "Saint Vincent And The Grenadines"),
		array("value" => "WS", "name" => "Samoa"),
		array("value" => "SM", "name" => "San Marino"),
		array("value" => "ST", "name" => "Sao Tome And Principe"),
		array("value" => "SA", "name" => "Saudi Arabia"),
		array("value" => "SN", "name" => "Senegal"),
		array("value" => "RS", "name" => "Serbia"),
		array("value" => "SC", "name" => "Seychelles"),
		array("value" => "SL", "name" => "Sierra Leone"),
		array("value" => "SG", "name" => "Singapore"),
		array("value" => "SK", "name" => "Slovakia (Slovak Republic)"),
		array("value" => "SI", "name" => "Slovenia"),
		array("value" => "SB", "name" => "Solomon Islands"),
		array("value" => "SO", "name" => "Somalia"),
		array("value" => "ZA", "name" => "South Africa"),
		array("value" => "GS", "name" => "South Georgia, South Sandwich Islands"),
		array("value" => "ES", "name" => "Spain"),
		array("value" => "LK", "name" => "Sri Lanka"),
		array("value" => "SH", "name" => "St. Helena"),
		array("value" => "PM", "name" => "St. Pierre And Miquelon"),
		array("value" => "SD", "name" => "Sudan"),
		array("value" => "SR", "name" => "Surivalue"),
		array("value" => "SJ", "name" => "Svalbard And Jan Mayen Islands"),
		array("value" => "SZ", "name" => "Swaziland"),
		array("value" => "SE", "name" => "Sweden"),
		array("value" => "CH", "name" => "Switzerland"),
		array("value" => "SY", "name" => "Syrian Arab Republic"),
		array("value" => "TW", "name" => "Taiwan"),
		array("value" => "TJ", "name" => "Tajikistan"),
		array("value" => "TZ", "name" => "Tanzania, United Republic Of"),
		array("value" => "TH", "name" => "Thailand"),
		array("value" => "TG", "name" => "Togo"),
		array("value" => "TK", "name" => "Tokelau"),
		array("value" => "TO", "name" => "Tonga"),
		array("value" => "TT", "name" => "Trinidad And Tobago"),
		array("value" => "TN", "name" => "Tunisia"),
		array("value" => "TR", "name" => "Turkey"),
		array("value" => "TM", "name" => "Turkmenistan"),
		array("value" => "TC", "name" => "Turks And Caicos Islands"),
		array("value" => "TV", "name" => "Tuvalu"),
		array("value" => "UG", "name" => "Uganda"),
		array("value" => "UA", "name" => "Ukraine"),
		array("value" => "AE", "name" => "United Arab Emirates"),
		array("value" => "UM", "name" => "United States Minor Outlying Islands"),
		array("value" => "UY", "name" => "Uruguay"),
		array("value" => "UZ", "name" => "Uzbekistan"),
		array("value" => "VU", "name" => "Vanuatu"),
		array("value" => "VE", "name" => "Venezuela"),
		array("value" => "VN", "name" => "Vietnam"),
		array("value" => "VG", "name" => "Virgin Islands (British)"),
		array("value" => "VI", "name" => "Virgin Islands (U.S.)"),
		array("value" => "WF", "name" => "Wallis And Futuna Islands"),
		array("value" => "EH", "name" => "Western Sahara"),
		array("value" => "YE", "name" => "Yemen"),
		array("value" => "ZM", "name" => "Zambia"),
		array("value" => "ZW", "name" => "Zimbabwe")
	);
	
	return apply_filters( 'bizz_country_list', $country_list );

}

/**
 * Add plugin widgets
 *
 * @since 7.8.3
 */
add_filter('bizz_custom_widgets_filter', 'bizz_add_custom_widgets_activate');
function bizz_add_custom_widgets_activate() {
	
	$array = array(
		'icl_lang_sel_widget',
		'icl_lang_sel_widget2'
	);
	
	return $array;
}

/**
 * Random string generator
 *
 * @since 7.8.3
 */
function bizz_rand_sha1($length) {
	$max = ceil($length / 40);
	$random = '';
	for ($i = 0; $i < $max; $i ++) {
		$random .= sha1(microtime(true).mt_rand(10000,90000));
	}
	return substr($random, 0, $length);
}

/**
 * Random string generator
 *
 * @since 7.9.3.3
 */
if (!function_exists('get_page_template_slug')) {
	function get_page_template_slug( $post_id = null ) {
		$post = get_post( $post_id );
		if ( 'page' != $post->post_type )
			return false;
		$template = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( ! $template || 'default' == $template )
			return '';
		return $template;
	}
}

/**
 * Get post meta
 *
 * @since 7.9.4.1
 */
function bizzthemes_get_custom_field( $field ) {

	if ( null === get_the_ID() )
		return '';

	$custom_field = get_post_meta( get_the_ID(), $field, true );

	if ( ! $custom_field )
		return '';

	//* Return custom field, slashes stripped, sanitized if string
	return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

}