<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*
Originally developed by: 	Andrew Norcross (@norcross / andrewnorcross.com)
							Jared Atchison (@jaredatch / jaredatchison.com)
							Bill Erickson (@billerickson / billerickson.net)
Evaluated from Version: 	0.9.2
*/

/**
 * Initiate all meta boxes
 */

add_action( 'init', 'metaboxes_admin_init' );
function metaboxes_admin_init() {
	$meta_boxes = array();
	$meta_boxes = apply_filters ( 'bizz_meta_boxes' , $meta_boxes );
	foreach ( $meta_boxes as $meta_box ) {
		$my_box = new Bizz_Meta_Box( $meta_box );
	}
}

/**
 * Validate value of meta fields
 * Define ALL validation methods inside this class and use the names of these 
 * methods in the definition of meta boxes (key 'validate_func' of each field)
 */

class Bizz_Meta_Box_Validate {
	function check_text( $text ) {
		if ($text != 'hello') {
			return false;
		}
		return true;
	}
}

/**
 * Create meta boxes
 */

class Bizz_Meta_Box {
	protected $_meta_box;

	function __construct( $meta_box ) {
		if ( !is_admin() ) return;

		$this->_meta_box = $meta_box;

		$upload = false;
		foreach ( $meta_box['fields'] as $field ) {
			if ( $field['type'] == 'file' || $field['type'] == 'file_list' ) {
				$upload = true;
				break;
			}
		}
		
		global $pagenow;
		if ( $upload && in_array( $pagenow, array( 'page.php', 'page-new.php', 'post.php', 'post-new.php' ) ) ) {
			add_action( 'admin_head', array( &$this, 'add_post_enctype' ) );
		}

		add_action( 'admin_menu', array(&$this, 'add') );
		add_action( 'save_post', array(&$this, 'save') );

		add_filter( 'bizz_show_on', array(&$this, 'add_for_id' ), 10, 2 );
		add_filter( 'bizz_show_on', array(&$this, 'add_for_page_template' ), 10, 2 );
	}

	function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#post").attr("enctype", "multipart/form-data");
			jQuery("#post").attr("encoding", "multipart/form-data");
		});
		</script>';
	}

	// Add metaboxes
	function add() {
		$this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
		$this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
		$this->_meta_box['show_on'] = empty( $this->_meta_box['show_on'] ) ? array('key' => false, 'value' => false) : $this->_meta_box['show_on'];
		
		foreach ( $this->_meta_box['pages'] as $page ) {
			if( apply_filters( 'bizz_show_on', true, $this->_meta_box ) )
				add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']) ;
		}
	}
	
	/**
	 * Show On Filters
	 * Use the 'bizz_show_on' filter to further refine the conditions under which a metabox is displayed.
	 * Below you can limit it by ID and page template
	 */
	 
	// Add for ID 
	function add_for_id( $display, $meta_box ) {
		if ( 'id' !== $meta_box['show_on']['key'] )
			return $display;

		// If we're showing it based on ID, get the current ID					
		if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
		elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
		if( !isset( $post_id ) )
			return false;
		
		// If value isn't an array, turn it into one	
		$meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
		
		// If current page id is in the included array, display the metabox

		if ( in_array( $post_id, $meta_box['show_on']['value'] ) )
			return true;
		else
			return false;
	}
	
	// Add for Page Template
	function add_for_page_template( $display, $meta_box ) {
		if( 'page-template' !== $meta_box['show_on']['key'] )
			return $display;
			
		// Get the current ID
		if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
		elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
		if( !( isset( $post_id ) || is_page() ) ) return false;
			
		// Get current template
		$current_template = get_post_meta( $post_id, '_wp_page_template', true );
		
		// If value isn't an array, turn it into one	
		$meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];

		// See if there's a match
		if( in_array( $current_template, $meta_box['show_on']['value'] ) )
			return true;
		else
			return false;
	}
	
	// Show fields
	function show() {

		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
		echo '<table class="form-table bizz_metabox">';

		foreach ( $this->_meta_box['fields'] as $field ) {
			// Set up blank or default values for empty ones
			if ( !isset( $field['name'] ) ) $field['name'] = '';
			if ( !isset( $field['desc'] ) ) $field['desc'] = '';
			if ( !isset( $field['std'] ) ) $field['std'] = '';
			if ( !isset( $field['multiple'] ) ) $field['multiple'] = false;
			if ( 'multicheck' == $field['type'] ) $field['multiple'] = true;
			if ( 'file' == $field['type'] && !isset( $field['allow'] ) ) $field['allow'] = array( 'url', 'attachment' );
			if ( 'file' == $field['type'] && !isset( $field['save_id'] ) )  $field['save_id']  = false;
						
			$meta = get_post_meta( $post->ID, $field['id'], !$field['multiple'] /* If multicheck this can be multiple values */ );

			echo '<tr>';

			if ( $field['type'] == "title" ) {
				echo '<td colspan="2">';
			} else {
				if( $this->_meta_box['show_names'] == true ) {
					echo '<th style="width:18%"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				}
				echo '<td>';
			}
						
			switch ( $field['type'] ) {

				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" />','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'text_small':
					echo '<input class="bizz_text_small" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_medium':
					echo '<input class="bizz_text_medium" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_counter':
					echo '<input class="char_counter" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" style="width:97%" />';
					echo '<input readonly class="bizz_text_small counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen('' !== $meta ? $meta : $field['std']).'" />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_date':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_date_timestamp':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? date( 'm\/d\/Y', $meta ) : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'text_datetime_timestamp':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '[date]" id="', $field['id'], '_date" value="', '' !== $meta ? date( 'm\/d\/Y', $meta ) : $field['std'], '" />';
					echo '<input class="bizz_timepicker text_time" type="text" name="', $field['id'], '[time]" id="', $field['id'], '_time" value="', '' !== $meta ? date( 'h:i A', $meta ) : $field['std'], '" /><span class="bizz_metabox_description" >', $field['desc'], '</span>';
					break;
				case 'text_time':
					echo '<input class="bizz_timepicker text_time" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'date_time':
					echo '<input class="bizz_text_small bizz_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" />';
					
					$start_time = strtotime('00:00');
					$end_time = strtotime('23:30');
					
					$by = apply_filters('bizz_minutes_increment', '15') . ' mins';
					$current = time(); 
					$add_time = strtotime('+'.$by, $current); 
					$diff = $add_time-$current; 
					
					$options = array(); 
					while ($start_time < $end_time) { 
						$options[] = $start_time; 
						$start_time += $diff; 
					}
					$options[] = $start_time;
					
					$default = '12:00';
					$existing_value_time = get_post_meta($post->ID, $field['id'].'_time', true);
					echo '<select name="', $field['id'], '_time">';
					foreach ($options as $option) {
						$option_value = date('H:i', $option);
						$option_display = date(get_option('time_format', 'H:i'), $option);
						
						if ($existing_value_time)
							$checked = ($existing_value_time == $option_value) ? ' selected="selected"' : '';
						elseif ($option_value == $default)
							$checked = ' selected="selected"';
						else
							$checked = '';
						echo '<option value="', $option_value, '"', $checked, '>', $option_display, '</option>';
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'text_money':
					$currency = ( !empty($field['currency']) ) ? $field['currency'] : '$';
					echo $currency . ' <input class="bizz_text_money" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10">', '' !== $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_small':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4">', '' !== $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_code':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="10" class="bizz_textarea_code">', '' !== $meta ? $meta : $field['std'], '</textarea>','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'textarea_counter':
					echo '<textarea class="char_counter" name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="3" style="width:97%">', '' !== $meta ? $meta : $field['std'], '</textarea>';
					echo '<input readonly class="bizz_text_small counter" type="text" name="char_count" size="3" maxlength="3" value="'.strlen($meta ? $meta : $field['std']).'" />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'select':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'radio_inline':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<div class="bizz_radio_inline">';
					$i = 1;
					foreach ($field['options'] as $option) {
						echo '<div class="bizz_radio_inline_option"><input type="radio" name="', $field['id'], '" id="', $field['id'], $i, '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $option['name'], '</label></div>';
						$i++;
					}
					echo '</div>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'radio':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<ul>';
					$i = 1;
					foreach ($field['options'] as $option) {
						echo '<li><input type="radio" name="', $field['id'], '" id="', $field['id'], $i,'" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $option['name'].'</label></li>';
						$i++;
					}
					echo '</ul>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'multicheck':
					if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<ul>';
					$i = 1;
					foreach ( $field['options'] as $value => $name ) {
						// Append `[]` to the name to get multiple values
						// Use in_array() to check whether the current option should be checked
						echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], $i, '" value="', $value, '"', in_array( $value, $meta ) ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $name, '</label></li>';
						$i++;
					}
					echo '</ul>';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'title':
					echo '<h5 class="bizz_metabox_title">', $field['name'], '</h5>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'wysiwyg':
					wp_editor( $meta ? $meta : $field['std'], $field['id'], isset( $field['options'] ) ? $field['options'] : array() );
			        echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if (!is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
						} else {
							echo '<option value="' . $term->slug . '  ' , $meta == $term->slug ? $meta : ' ' ,'  ">' . $term->name . '</option>';
						}
					}
					echo '</select>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_radio':
					$names= wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					echo '<ul>';
					foreach ( $terms as $term ) {
						if ( !is_wp_error( $names ) && !empty( $names ) && !strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<li><input type="radio" name="', $field['id'], '" value="'. $term->slug . '" checked>' . $term->name . '</li>';
						} else {
							echo '<li><input type="radio" name="', $field['id'], '" value="' . $term->slug . '  ' , $meta == $term->slug ? $meta : ' ' ,'  ">' . $term->name .'</li>';
						}
					}
					echo '</ul>';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					break;
				case 'taxonomy_multicheck':
					echo '<ul>';
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ($terms as $term) {
						echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], '" value="', $term->name , '"';
						foreach ($names as $name) {
							if ( $term->slug == $name->slug ){ echo ' checked="checked" ';};
						}
						echo' /><label>', $term->name , '</label></li>';
					}
					echo '</ul>';
					echo '<span class="bizz_metabox_description">', $field['desc'], '</span>';
					break;
				case 'file_list':
					echo '<input class="bizz_upload_file" type="text" size="36" name="', $field['id'], '" value="" />';
					echo '<input class="bizz_upload_button button" type="button" value="Upload File" />';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
						$args = array(
								'post_type' => 'attachment',
								'numberposts' => null,
								'post_status' => null,
								'post_parent' => $post->ID
							);
							$attachments = get_posts($args);
							if ($attachments) {
								echo '<ul class="attach_list">';
								foreach ($attachments as $attachment) {
									echo '<li>'.wp_get_attachment_link($attachment->ID, 'thumbnail', 0, 0, 'Download');
									echo '<span>';
									echo apply_filters('the_title', '&nbsp;'.$attachment->post_title);
									echo '</span></li>';
								}
								echo '</ul>';
							}
					break;
				case 'file':
					echo '<div class="file_wrap" id="file_', $field['id'], '">';
					$input_type_url = "hidden";
					if ( 'url' == $field['allow'] || ( is_array( $field['allow'] ) && in_array( 'url', $field['allow'] ) ) )
						$input_type_url="text";
					echo '<input class="bizz_upload_file" type="' . $input_type_url . '" size="45" id="', $field['id'], '" name="', $field['id'], '" value="', $meta, '" />';
					echo '<input class="bizz_upload_button button" type="button" value="Upload File" />';
					echo '<input class="bizz_upload_file_id" type="hidden" id="', $field['id'], '_id" name="', $field['id'], '_id" value="', get_post_meta( $post->ID, $field['id'] . "_id",true), '" />';
					echo '<p class="bizz_metabox_description">', $field['desc'], '</p>';
					echo '<div id="', $field['id'], '_status" class="bizz_media_status">';
						if ( $meta != '' ) {
							$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta );
							if ( $check_image ) {
								echo '<div class="img_status">';
								echo '<img src="', $meta, '" alt="" />';
								echo '<a href="#" class="bizz_remove_file_button" rel="', $field['id'], '">Remove Image</a>';
								echo '</div>';
							} else {
								$parts = explode( '/', $meta );
								for( $i = 0; $i < count( $parts ); ++$i ) {
									$title = $parts[$i];
								}
								echo 'File: <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $meta, '" target="_blank" rel="external">Download</a> / <a href="#" class="bizz_remove_file_button" rel="', $field['id'], '">Remove</a>)';
							}
						}
					echo '</div>';
					?>
					<script type="text/javascript">
					jQuery(document).ready(function() {
						// Upload image
						jQuery('.bizz_upload_button').click( function( event ) {
							// Prevent default click action
							event.preventDefault();
							// Variables
							var button = jQuery(this);
							var file_frame;
							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}
							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title: '<?php _e( 'Choose an image', 'bizzthemes' ); ?>',
								button: {
									text: '<?php _e( 'Use image', 'bizzthemes' ); ?>',
								},
								multiple: false
							});
							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								attachment = file_frame.state().get('selection').first().toJSON();
								// Remove old
								jQuery(button).prev().val('');
								jQuery(button).parents('.file_wrap').find('.bizz_media_status').children().remove();
								// Add new
								jQuery(button).prev().val(attachment.url);
								jQuery(button).parents('.file_wrap').find('.bizz_media_status').append('<div class="img_status"><img src="'+attachment.url+'" alt="" /><a href="#" class="bizz_remove_file_button" rel="'+attachment.id+'"><?php _e( 'Remove Image', 'bizzthemes' ); ?></a></div>');
							});
							// Open modal
							file_frame.open(button);
							return false;
						});
						// Remove image
						jQuery('.bizz_remove_file_button').live('click', function( event ){
							// Prevent default click action
							event.preventDefault();
							// Variables
							var button = jQuery(this);
							// Clear
							jQuery(button).parents('.file_wrap').find('.bizz_upload_file').val('');
							jQuery(button).parent().remove();
							return false;
						});
					});
					</script>
					<?php
					echo '</div>';
					break;
				case 'oembed':
					echo '<input class="bizz_oembed" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" />','<p class="bizz_metabox_description">', $field['desc'], '</p>';
					echo '<p class="bizz-spinner spinner"></p>';
					echo '<div id="', $field['id'], '_status" class="bizz_media_status ui-helper-clearfix embed_wrap">';
						if ( $meta != '' ) {
							$check_embed = $GLOBALS['wp_embed']->run_shortcode( '[embed]'. esc_url( $meta ) .'[/embed]' );
							if ( $check_embed ) {
								echo '<div class="embed_status">';
								echo $check_embed;
								echo '<a href="#" class="bizz_remove_file_button" rel="', $field['id'], '">Remove Embed</a>';
								echo '</div>';
							} else {
								echo 'URL is not a valid oEmbed URL.';
							}
						}
					echo '</div>';
					break;
				default:
					do_action('bizz_render_' . $field['type'] , $field, $meta);
			}
			
			echo '</td>','</tr>';
		}
		echo '</table>';
	}

	// Save data from metabox
	function save( $post_id )  {
		
		// verify nonce
		if ( ! isset( $_POST['wp_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_meta_box_nonce'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// check autosave
		if ( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		
		// get the post types applied to the metabox group
		// and compare it to the post type of the content
		$post_type = get_post_type($post_id);
		$meta_type = $this->_meta_box['pages'];
		$type_comp = in_array($post_type, $meta_type) ? true : false;
		
		foreach ( $this->_meta_box['fields'] as $field ) {
			$name = $field['id'];

			if ( ! isset( $field['multiple'] ) )
				$field['multiple'] = ( 'multicheck' == $field['type'] ) ? true : false;

			$old = get_post_meta( $post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */ );
			$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;

			if ( $type_comp == true && in_array( $field['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ) ) )  {
				$new = wp_set_object_terms( $post_id, $new, $field['taxonomy'] );
			}

			if ( ($field['type'] == 'textarea') || ($field['type'] == 'textarea_small') ) {
				$new = htmlspecialchars( $new );
			}

			if ( ($field['type'] == 'textarea_code') ) {
				$new = htmlspecialchars_decode( $new );
			}

			if ( $type_comp == true && $field['type'] == 'text_date_timestamp' ) {
				$new = strtotime( $new );
			}

			if ( $type_comp == true && $field['type'] == 'text_datetime_timestamp' ) {
				$string = $new['date'] . ' ' . $new['time'];
				$new = strtotime( $string );
			}

			$new = apply_filters('bizz_validate_' . $field['type'], $new, $post_id, $field);

			// validate meta value
			if ( isset( $field['validate_func']) ) {
				$ok = call_user_func( array( 'Bizz_Meta_Box_Validate', $field['validate_func']), $new );
				if ( $ok === false ) { // pass away when meta value is invalid
					continue;
				}
			} elseif ( $field['multiple'] ) {
				delete_post_meta( $post_id, $name );
				if ( !empty( $new ) ) {
					foreach ( $new as $add_new ) {
						add_post_meta( $post_id, $name, $add_new, false );
					}
				}
			} elseif ( '' !== $new && $new != $old  ) {
				update_post_meta( $post_id, $name, $new );
			} elseif ( '' == $new ) {
				delete_post_meta( $post_id, $name );
			}

			if ( 'file' == $field['type'] ) {
				$name = $field['id'] . "_id";
				$old = get_post_meta( $post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */ );
				if ( isset( $field['save_id'] ) && $field['save_id'] ) {
					$new = isset( $_POST[$name] ) ? $_POST[$name] : null;
				} else {
					$new = "";
				}

				if ( $new && $new != $old ) {
					update_post_meta( $post_id, $name, $new );
				} elseif ( '' == $new && $old ) {
					delete_post_meta( $post_id, $name, $old );
				}
			}
			
			if ( 'date_time' == $field['type'] ) {
				$name = $field['id'] . "_time";
				$old = get_post_meta( $post_id, $name, 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
				$new = isset( $_POST[$name] ) ? $_POST[$name] : null;

				if ( $new && $new != $old )
					update_post_meta( $post_id, $name, $new );
				elseif ( '' == $new && $old )
					delete_post_meta( $post_id, $name, $old );
				
			}
		}
	}
}

/**
 * Adding scripts and styles
 */

add_action( 'admin_enqueue_scripts', 'bizz_scripts', 10 );
function bizz_scripts( $hook ) {
	global $wp_version;
	// only enqueue our scripts/styles on the proper pages
	if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
		// Scripts
		$bizz_script_array = array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'media-upload' );
		wp_register_script( 'bizz-metabox-scripts', BIZZ_FRAME_SCRIPTS . '/metaboxes.js', $bizz_script_array, '0.9.2' );
		wp_localize_script( 'bizz-metabox-scripts', 'bizz_ajax_data', array( 'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ), 'post_id' => get_the_ID() ) );
		wp_enqueue_script( 'bizz-metabox-scripts' );
		//Styles
		wp_register_style( 'bizz-metabox-styles', BIZZ_FRAME_CSS . '/admin_metabox.css' );
		wp_enqueue_style( 'bizz-metabox-styles' );
		wp_enqueue_style( 'bizz-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css' );
	}
}

/**
 * Handles our oEmbed ajax request
 */
add_action( 'wp_ajax_bizz_oembed_handler', 'bizz_oembed_ajax_results' );
function bizz_oembed_ajax_results() {

	// verify our nonce
	if ( ! ( isset( $_REQUEST['bizz_ajax_nonce'], $_REQUEST['oembed_url'] ) && wp_verify_nonce( $_REQUEST['bizz_ajax_nonce'], 'ajax_nonce' ) ) )
		die();

	// sanitize our search string
	$oembed_string = sanitize_text_field( $_REQUEST['oembed_url'] );

	if ( empty( $oembed_string ) ) {
		$return = '<p class="ui-state-error-text">'. __( 'Please Try Again', 'bizzthemes' ) .'</p>';
		$found = 'not found';
	} else {

		global $wp_embed;

		$oembed_url = esc_url( $oembed_string );
		// Post ID is needed to check for embeds
		if ( isset( $_REQUEST['post_id'] ) )
			$GLOBALS['post'] = get_post( $_REQUEST['post_id'] );
		// ping WordPress for an embed
		$check_embed = $wp_embed->run_shortcode( '[embed]'. $oembed_url .'[/embed]' );
		// fallback that WordPress creates when no oEmbed was found
		$fallback = $wp_embed->maybe_make_link( $oembed_url );

		if ( $check_embed && $check_embed != $fallback ) {
			// Embed data
			$return = '<div class="embed_status">'. $check_embed .'<a href="#" class="bizz_remove_file_button" rel="'. $_REQUEST['field_id'] .'">'. __( 'Remove Embed', 'bizzthemes' ) .'</a></div>';
			// set our response id
			$found = 'found';

		} else {
			// error info when no oEmbeds were found
			$return = '<p class="ui-state-error-text">'.sprintf( __( 'No oEmbed Results Found for %s. View more info at', 'bizzthemes' ), $fallback ) .' <a href="http://codex.wordpress.org/Embeds" target="_blank">codex.wordpress.org/Embeds</a>.</p>';
			// set our response id
			$found = 'not found';
		}
	}

	// send back our encoded data
	echo json_encode( array( 'result' => $return, 'id' => $found ) );
	die();
}

// End. That's it, folks! //

/* SAMPLE OPTIONS:

	https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/blob/master/example-functions.php
	
*/