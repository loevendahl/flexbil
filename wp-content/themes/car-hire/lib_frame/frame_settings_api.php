<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Bizz Settings API Class
 *
 * A settings API (wrapping the WordPress Settings API).
 * Original class author: WooThemes (WooSlider script)
 *
 */
class Bizz_Settings_API {
	public $token;
	public $page_slug;
	public $name;
	public $menu_label;
	public $settings;
	public $sections;
	public $fields;
	public $errors;
	public $has_tabs;
	private $tabs;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct () {
		$this->token = 'bizz-settings';
		$this->page_slug = 'bizz-settings-api';
		$this->sections = array();
		$this->fields = array();
		$this->remaining_fields = array();
		$this->errors = array();
		$this->has_tabs = false;
		$this->tabs = array();
	}
	
	/**
	 * setup_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setup_settings () {
		add_action( 'admin_menu', array( &$this, 'register_settings_screen' ) );
		add_action( 'admin_init', array( &$this, 'settings_fields' ) );
		
		$this->init_sections();
		$this->init_fields();
		$this->get_settings();
		if ( $this->has_tabs == true ) {
			$this->create_tabs();
		}
	}
	
	/**
	 * init_sections function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init_sections () {
		// Override this function in your class and assign the array of sections to $this->sections.
		_e( 'Override init_sections() in your class.', 'bizzthemes' );
	}
	
	/**
	 * init_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init_fields () {
		// Override this function in your class and assign the array of sections to $this->fields.
		_e( 'Override init_fields() in your class.', 'bizzthemes' );
	}
	
	/**
	 * settings_tabs function.
	 * 
	 * @access public
	 * @since  1.1.0
	 * @return void
	 */
	public function settings_tabs () {
		if ( ! $this->has_tabs ) { return; }

		if ( count( $this->tabs ) > 0 ) {
			$html = '';
			
			$html .= '<ul id="settings-sections" class="subsubsub hide-if-no-js">' . "\n";

			$sections = array(
						'all' => array( 'href' => '#all', 'name' => __( 'All', 'wooslider' ), 'class' => 'current all tab' )
					);
					
			foreach ( $this->tabs as $k => $v ) {
				$sections[$k] = array( 'href' => '#' . esc_attr( $k ), 'name' => esc_attr( $v['name'] ), 'class' => 'tab' );
			}
			
			$count = 1;
			foreach ( $sections as $k => $v ) {
				$count++;
				$html .= '<li><a href="' . $v['href'] . '"';
				if ( isset( $v['class'] ) && ( $v['class'] != '' ) ) { $html .= ' class="' . esc_attr( $v['class'] ) . '"'; }
				$html .= '>' . esc_attr( $v['name'] ) . '</a>';
				if ( $count <= count( $sections ) ) { $html .= ' | '; }
				$html .= '</li>' . "\n";
			}

			$html .= '</ul><div class="clear"></div>' . "\n";

			echo $html;
		}
	}

	/**
	 * create_tabs function.
	 * 
	 * @access private
	 * @since  1.1.0
	 * @return void
	 */
	private function create_tabs () {
		if ( count( $this->sections ) > 0 ) {
			$tabs = array();
			foreach ( $this->sections as $k => $v ) {
				$tabs[$k] = $v;
			}

			$this->tabs = $tabs;
		}
	}

	/**
	 * create_sections function.
	 * 
	 * @access public
	 * @return void
	 */
	public function create_sections () {
		if ( count( $this->sections ) > 0 ) {
			foreach ( $this->sections as $k => $v ) {
				add_settings_section( $k, $v['name'], array( &$this, 'section_description' ), $this->token );
			}
		}
	}
	
	/**
	 * create_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	public function create_fields () {
		if ( count( $this->sections ) > 0 ) {
			// $this->parse_fields( $this->fields );
			
			foreach ( $this->fields as $k => $v ) {
				$method = $this->determine_method( $v, 'form' );
				$name = $v['name'];
				if ( $v['type'] == 'info' ) { $name = ''; }
				add_settings_field( $k, $name, $method, $this->token, $v['section'], array( 'key' => $k, 'data' => $v ) );

				// Let the API know that we have a colourpicker field.
				if ( $v['type'] == 'range' && $this->has_range == false ) { $this->has_range = true; }
			}
		}
	}
	
	/**
	 * determine_method function.
	 * 
	 * @access protected
	 * @param array $data
	 * @return array or string
	 */
	protected function determine_method ( $data, $type = 'form' ) {
		$method = '';
		
		if ( ! in_array( $type, array( 'form', 'validate', 'check' ) ) ) { return; }
		
		// Check for custom functions.
		if ( isset( $data[$type] ) ) {
			if ( function_exists( $data[$type] ) ) {
				$method = $data[$type];
			}
			
			if ( $method == '' && method_exists( $this, $data[$type] ) ) {
				if ( $type == 'form' ) {
					$method = array( &$this, $data[$type] );
				} else {
					$method = $data[$type];
				}
			}
		}
		
		if ( $method == '' && method_exists ( $this, $type . '_field_' . $data['type'] ) ) {
			if ( $type == 'form' ) {
				$method = array( &$this, $type . '_field_' . $data['type'] );
			} else {
				$method = $type . '_field_' . $data['type'];
			}
		}
		
		if ( $method == '' && function_exists ( $this->token . '_' . $type . '_field_' . $data['type'] ) ) {
			$method = $this->token . '_' . $type . '_field_' . $data['type'];
		}
		
		if ( $method == '' ) {
			if ( $type == 'form' ) {
				$method = array( &$this, $type . '_field_text' );
			} else {
				$method = $type . '_field_text';
			}
		}
		
		return $method;
	}
	
	/**
	 * parse_fields function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $fields
	 * @return void
	 */
	public function parse_fields ( $fields ) {
		foreach ( $fields as $k => $v ) {
			if ( isset( $v['section'] ) && ( $v['section'] != '' ) && ( isset( $this->sections[$v['section']] ) ) ) {
				if ( ! isset( $this->sections[$v['section']]['fields'] ) ) {
					$this->sections[$v['section']]['fields'] = array();
				}
				
				$this->sections[$v['section']]['fields'][$k] = $v;
			} else {
				$this->remaining_fields[$k] = $v;
			}
		}
	}
	
	/**
	 * register_settings_screen function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings_screen () {
		$hook = add_submenu_page( 'edit.php?post_type=bizzy', $this->name, $this->menu_label, 'manage_options', $this->page_slug, array( &$this, 'settings_screen' ) );
		$this->hook = $hook;

		if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->page_slug ) ) {
			add_action( 'admin_notices', array( &$this, 'settings_errors' ) );
			add_action( 'admin_print_scripts', array( &$this, 'enqueue_scripts' ) );
		}
	}
	
	/**
	 * settings_screen function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settings_screen () {
?>
<div id="bizzsettings" class="wrap <?php echo esc_attr( $this->token ); ?>">
	<?php screen_icon( 'options-general' ); ?>
	<h2><?php echo esc_html( $this->name ); ?></h2>
	
	<form action="options.php" method="post">
		<?php $this->settings_tabs(); ?>
		<?php settings_fields( $this->token ); ?>
		<?php do_settings_sections( $this->token ); ?>
		<?php submit_button(); ?>
	</form>
</div>
<?php
	}
	
	/**
	 * get_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_settings () {
		if ( ! is_array( $this->settings ) ) {
			$this->settings = get_option( $this->token, array() );
		}
		
		foreach ( $this->fields as $k => $v ) {
			if ( ! isset( $this->settings[$k] ) && isset( $v['default'] ) ) {
				$this->settings[$k] = $v['default'];
			}
			if ( $v['type'] == 'checkbox' && $this->settings[$k] != true ) {
				$this->settings[$k] = 0;
			}
		}
		
		return $this->settings;
	}
	
	/**
	 * settings_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settings_fields () {
		register_setting( $this->token, $this->token, array( &$this, 'validate_fields' ) );
		$this->create_sections();
		$this->create_fields();
	}
	
	/**
	 * settings_errors function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function settings_errors () {
		echo settings_errors( $this->token . '-errors' );
	}
	
	/**
	 * section_description function.
	 * 
	 * @access public
	 * @return void
	 */
	public function section_description ( $section ) {
		if ( isset( $this->sections[$section['id']]['description'] ) ) {
			echo wpautop( esc_html( $this->sections[$section['id']]['description'] ) );
		}
	}
	
	/**
	 * form_field_text function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_text ( $args ) {
		$options = $this->get_settings();

		echo '<input id="' . esc_attr( $args['key'] ) . '" name="' . $this->token . '[' . esc_attr( $args['key'] ) . ']" size="40" type="text" value="' . esc_attr( $options[$args['key']] ) . '" />' . "\n";
		if ( isset( $args['data']['description'] ) ) {
			echo '<span class="description">' . $args['data']['description'] . '</span>' . "\n";
		}
	}
	
	/**
	 * form_field_checkbox function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_checkbox ( $args ) {
		$options = $this->get_settings();

		$has_description = false;
		if ( isset( $args['data']['description'] ) ) {
			$has_description = true;
			echo '<label for="' . esc_attr( $args['key'] ) . '">' . "\n";
		}
		echo '<input id="' . $args['key'] . '" name="' . $this->token . '[' . esc_attr( $args['key'] ) . ']" type="checkbox" value="1"' . checked( esc_attr( $options[$args['key']] ), '1', false ) . ' />' . "\n";
		if ( $has_description ) {
			echo $args['data']['description'] . '</label>' . "\n";
		}
	}
	
	/**
	 * form_field_textarea function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_textarea ( $args ) {
		$options = $this->get_settings();

		echo '<textarea id="' . esc_attr( $args['key'] ) . '" name="' . $this->token . '[' . esc_attr( $args['key'] ) . ']" cols="42" rows="5">' . esc_html( $options[$args['key']] ) . '</textarea>' . "\n";
		if ( isset( $args['data']['description'] ) ) {
			echo '<p><span class="description">' . esc_html( $args['data']['description'] ) . '</span></p>' . "\n";
		}
	}
	
	/**
	 * form_field_select function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_select ( $args ) {
		$options = $this->get_settings();
		
		if ( isset( $args['data']['options'] ) && ( count( (array)$args['data']['options'] ) > 0 ) ) {
			$html = '';
			$html .= '<select id="' . esc_attr( $args['key'] ) . '" name="' . esc_attr( $this->token ) . '[' . esc_attr( $args['key'] ) . ']">' . "\n";
				foreach ( $args['data']['options'] as $k => $v ) {
					$html .= '<option value="' . esc_attr( $k ) . '"' . selected( esc_attr( $options[$args['key']] ), $k, false ) . '>' . $v . '</option>' . "\n";
				}
			$html .= '</select>' . "\n";
			echo $html;
			
			if ( isset( $args['data']['description'] ) ) {
				echo '<p><span class="description">' . esc_html( $args['data']['description'] ) . '</span></p>' . "\n";
			}
		}
	}
	
	/**
	 * form_field_radio function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_radio ( $args ) {
		$options = $this->get_settings();
		
		if ( isset( $args['data']['options'] ) && ( count( (array)$args['data']['options'] ) > 0 ) ) {
			$html = '';
			foreach ( $args['data']['options'] as $k => $v ) {
				$html .= '<input type="radio" name="' . $this->token . '[' . esc_attr( $args['key'] ) . ']" value="' . esc_attr( $k ) . '"' . checked( esc_attr( $options[$args['key']] ), $k, false ) . ' /> ' . $v . '<br />' . "\n";
			}
			echo $html;
			
			if ( isset( $args['data']['description'] ) ) {
				echo '<span class="description">' . esc_html( $args['data']['description'] ) . '</span>' . "\n";
			}
		}
	}
	
	/**
	 * form_field_multicheck function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_multicheck ( $args ) {
		$options = $this->get_settings();
		
		if ( isset( $args['data']['options'] ) && ( count( (array)$args['data']['options'] ) > 0 ) ) {
			$html = '<div class="multicheck-container" style="height: 100px; overflow-y: auto;">' . "\n";
			foreach ( $args['data']['options'] as $k => $v ) {
				$checked = '';

				if ( in_array( $k, (array)$options[$args['key']] ) ) { $checked = ' checked="checked"'; }
				$html .= '<input type="checkbox" name="' . esc_attr( $this->token ) . '[' . esc_attr( $args['key'] ) . '][]" class="multicheck multicheck-' . esc_attr( $args['key'] ) . '" value="' . esc_attr( $k ) . '"' . $checked . ' /> ' . $v . '<br />' . "\n";
			}
			$html .= '</div>' . "\n";
			echo $html;
			
			if ( isset( $args['data']['description'] ) ) {
				echo '<span class="description">' . esc_html( $args['data']['description'] ) . '</span>' . "\n";
			}
		}
	}

	/**
	 * form_field_info function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $args
	 * @return void
	 */
	public function form_field_info ( $args ) {
		$class = '';
		if ( isset( $args['data']['class'] ) ) {
			$class = ' ' . esc_attr( $args['data']['class'] );
		}
		$html = '<div id="' . $args['key'] . '" class="info-box' . $class . '">' . "\n";
		if ( isset( $args['data']['name'] ) && ( $args['data']['name'] != '' ) ) {
			$html .= '<h3 class="title">' . esc_html( $args['data']['name'] ) . '</h3>' . "\n";
		}
		if ( isset( $args['data']['description'] ) && ( $args['data']['description'] != '' ) ) {
			$html .= '<p>' . esc_html( $args['data']['description'] ) . '</p>' . "\n";
		}
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * validate_fields function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $input
	 * @uses $this->parse_errors()
	 * @return array $options
	 */
	public function validate_fields ( $input ) {
		$options = $this->get_settings();
		
		foreach ( $this->fields as $k => $v ) {
			// Make sure checkboxes are present even when false.
			if ( $v['type'] == 'checkbox' && ! isset( $input[$k] ) ) { $input[$k] = false; }
			
			if ( isset( $input[$k] ) ) {
				// Perform checks on required fields.
				if ( isset( $v['required'] ) && ( $v['required'] == true ) ) {
					if ( in_array( $v['type'], $this->get_array_field_types() ) && ( count( (array) $input[$k] ) <= 0 ) ) {
						$this->add_error( $k, $v );
						continue;
					} else {
						if ( $input[$k] == '' ) {
							$this->add_error( $k, $v );
							continue;
						}
					}
				}

				$value = $input[$k];

				// Check if the field is valid.
				$method = $this->determine_method( $v, 'check' );

				if ( function_exists ( $method ) ) {
					$is_valid = $method( $value );
				} else {
					if ( method_exists( $this, $method ) ) {
						$is_valid = $this->$method( $value );
					}
				}

				if ( ! $is_valid ) {
					$this->add_error( $k, $v );
					continue;
				}

				$method = $this->determine_method( $v, 'validate' );

				if ( function_exists ( $method ) ) {
					$options[$k] = $method( $value );
				} else {
					if ( method_exists( $this, $method ) ) {
						$options[$k] = $this->$method( $value );
					}
				}
			}
		}
		
		// Parse error messages into the Settings API.
		$this->parse_errors();
		return $options;
	}
	
	/**
	 * validate_field_text function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $input
	 * @return string
	 */
	public function validate_field_text ( $input ) {
		return trim( esc_attr( $input ) );
	}
	
	/**
	 * validate_field_checkbox function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $input
	 * @return string
	 */
	public function validate_field_checkbox ( $input ) {
		if ( ! isset( $input ) ) {
			return 0;
		} else {
			return (bool)$input;
		}
	}
	
	/**
	 * validate_field_multicheck function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $input
	 * @return string
	 */
	public function validate_field_multicheck ( $input ) {
		$input = (array) $input;
		
		$input = array_map( 'esc_attr', $input );
		
		return $input;
	}

	/**
	 * validate_field_url function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $input
	 * @return string
	 */
	public function validate_field_url ( $input ) {
		return trim( esc_url( $input ) );
	}

	/**
	 * check_field_text function.
	 * @param  string $input String of the value to be validated.
	 * @since  1.1.0
	 * @return boolean Is the value valid?
	 */
	public function check_field_text ( $input ) {
		$is_valid = true;

		return $is_valid;
	}

	/**
	 * add_error function.
	 * 
	 * @access protected
	 * @since 1.0.0
	 * @param string $key
	 * @param array $data
	 * @return void
	 */
	protected function add_error ( $key, $data ) {
		if ( isset( $data['error_message'] ) ) {
			$message = $data['error_message'];
		} else {
			$message = sprintf( __( '%s is a required field', 'bizzthemes' ), $data['name'] );
		}
		$this->errors[$key] = $message;
	}
	
	protected function parse_errors () {
		if ( count ( $this->errors ) > 0 ) {
			foreach ( $this->errors as $k => $v ) {
				add_settings_error( $this->token . '-errors', $k, $v, 'error' );
			}
		} else {
			$message = sprintf( __( '%s updated', 'bizzthemes' ), $this->name );
			add_settings_error( $this->token . '-errors', $this->token, $message, 'updated' );
		}
	}
	
	/**
	 * get_array_field_types function.
	 *
	 * @description Return an array of field types expecting an array value returned.
	 * @access protected
	 * @since 1.0.0
	 * @return void
	 */
	protected function get_array_field_types () {
		return array( 'multicheck' );
	}
	
	/**
	 * enqueue_scripts function.
	 *
	 * @description Load in JavaScripts where necessary.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts () {
		if ( $this->has_tabs ) {
			wp_enqueue_script( 'bizzsettings-tabs', BIZZ_FRAME_SCRIPTS . '/settings.tabs.js', array( 'jquery' ), '1.0.0' );
		}
	}

}