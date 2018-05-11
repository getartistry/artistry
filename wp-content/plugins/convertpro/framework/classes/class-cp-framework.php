<?php
/**
 * Core Framework Class.
 *
 * @package ConvertPro
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Framework Root
 */
if ( ! defined( 'CP_FRAMEWORK_DIR' ) ) {
	define( 'CP_FRAMEWORK_DIR', CP_V2_BASE_DIR . 'framework/' );
}

/**
 * Framework URI
 */
if ( ! defined( 'CP_FRAMEWORK_URI' ) ) {
	define( 'CP_FRAMEWORK_URI', CP_V2_BASE_URL . 'framework/' );
}

/*
* Framework Starts from here.
*/
if ( ! class_exists( 'Cp_Framework' ) ) {
	/**
	 * Class Cp_Framework.
	 */
	class Cp_Framework {

		/**
		 * Options.
		 *
		 * @var options
		 */
		public static $options = array();

		/**
		 * Addon List.
		 *
		 * @var addon_list
		 */
		public static $addon_list = array();

		/**
		 * Design Types.
		 *
		 * @var types
		 */
		public static $types = array();

		/**
		 * Border Options.
		 *
		 * @var border_options
		 */
		public static $border_options = array();

		/**
		 * Icon Options.
		 *
		 * @var icon_options
		 */
		public static $icon_options = array();

		/**
		 * Close Link Options.
		 *
		 * @var close_link_opts
		 */
		public static $close_link_opts = array();

		/**
		 * Countdown Element Options.
		 *
		 * @var cp_countdown_opts
		 */
		public static $cp_countdown_opts = array();

		/**
		 * Close Image Options.
		 *
		 * @var close_image_opts
		 */
		public static $close_image_opts = array();

		/**
		 * Shape Options.
		 *
		 * @var cp_shapes_opts
		 */
		public static $cp_shapes_opts = array();

		/**
		 * Image Options.
		 *
		 * @var cp_image_opts
		 */
		public static $cp_image_opts = array();

		/**
		 * Custom HTML Options.
		 *
		 * @var cp_custom_html_opts
		 */
		public static $cp_custom_html_opts = array();

		/**
		 * Paragraph Options.
		 *
		 * @var cp_paragraph_opts
		 */
		public static $cp_paragraph_opts = array();

		/**
		 * Sub heading Options.
		 *
		 * @var cp_subheading_opts
		 */
		public static $cp_subheading_opts = array();

		/**
		 * Heading Options.
		 *
		 * @var cp_heading_opts
		 */
		public static $cp_heading_opts = array();

		/**
		 * Form - Email Options.
		 *
		 * @var cp_form_email_opts
		 */
		public static $cp_form_email_opts = array();

		/**
		 * Form - Name Options.
		 *
		 * @var cp_form_name_opts
		 */
		public static $cp_form_name_opts = array();

		/**
		 * Form - Phone Options.
		 *
		 * @var cp_form_phone_opts
		 */
		public static $cp_form_phone_opts = array();

		/**
		 * Form - Dropdown Options.
		 *
		 * @var cp_form_dropdown_opts
		 */
		public static $cp_form_dropdown_opts = array();

		/**
		 * Form - Textarea Options.
		 *
		 * @var cp_form_textarea_opts
		 */
		public static $cp_form_textarea_opts = array();

		/**
		 * Form - Radio Options.
		 *
		 * @var cp_form_radio_opts
		 */
		public static $cp_form_radio_opts = array();

		/**
		 * Form - Checkbox Options.
		 *
		 * @var cp_form_checkbox_opts
		 */
		public static $cp_form_checkbox_opts = array();

		/**
		 * Form - Hidden Input Options.
		 *
		 * @var cp_form_hiddeninput_opts
		 */
		public static $cp_form_hiddeninput_opts = array();

		/**
		 * Button - Flat Button Options.
		 *
		 * @var cp_button_flatbtn_opts
		 */
		public static $cp_button_flatbtn_opts = array();

		/**
		 * Button - Gradient Button Options.
		 *
		 * @var cp_button_gradientbtn_opts
		 */
		public static $cp_button_gradientbtn_opts = array();

		/**
		 * Video Options.
		 *
		 * @var cp_video_options
		 */
		public static $cp_video_options = array();

		/**
		 * Fields Directory.
		 *
		 * @var fields_dir
		 */
		public $fields_dir;

		/**
		 * Fonts.
		 *
		 * @var fonts
		 */
		public static $fonts;

		/**
		 * Parameters.
		 *
		 * @var params
		 */
		public static $params = array();

		/**
		 * Constructor function that initializes required actions and hooks
		 *
		 * @since 0.0.1
		 */
		function __construct() {

			$this->fields_dir = CP_FRAMEWORK_DIR . '/fields/';

			// Load options.
			add_action( 'current_screen', array( $this, 'load_framework_functions' ) );

			add_filter( 'cp_update_design_options', array( $this, 'cp_update_field_options' ), 10, 1 );

			$cpfonts = new CP_V2_Fonts();
			$fonts   = $cpfonts::cp_get_fonts();

			self::$fonts = $fonts;
		}

		/**
		 * Calls hook for attribute type
		 *
		 * @since 0.0.1
		 *
		 * @param string $name                  - name.
		 * @param string $type                  - type.
		 * @param string $input_type_settings  - input_type_settings.
		 * @param string $input_type_params    - input_type_params.
		 * @param string $input_value           - input_value.
		 * @param string $default_value     - default_value.
		 * @return mixed|string         - returns html which will be render in hook
		 */
		function render_input_type( $name, $type, $input_type_settings, $input_type_params, $input_value, $default_value = null ) {

			if ( isset( self::$params[ $type ]['callback'] ) ) {

				$param_callaback = self::$params[ $type ]['callback'];

				if ( is_callable( $param_callaback ) ) {
					if ( '' !== $input_type_params ) {
						return $param_callaback( $name, $input_type_settings, $input_type_params, $input_value, $default_value );
					} else {
						return $param_callaback( $name, $input_type_settings, $input_value, $default_value );
					}
				}
			}
			return '';
		}

		/**
		 * Helper function to register options and their respective settings
		 *
		 * @param array $settings - extra settings for option.
		 * @since 0.0.1
		 */
		function cp_framework_add_options( $settings ) {

			$this->cp_store_data( $settings );
		}

		/**
		 * Call hook for input type html.
		 *
		 * @param string $name                  - name.
		 * @param string $type                  - type.
		 * @param string $input_type_settings   - input_type_settings.
		 * @param string $input_type_params     - input_type_params.
		 * @param string $input_value           - input_value.
		 * @param string $default_value         - default_value.
		 * @return mixed|string         - returns html which will be render in hook
		 * @since 0.0.1
		 */
		function do_input_type_settings_field( $name, $type, $input_type_settings, $input_type_params, $input_value, $default_value = null ) {

			return $this->render_input_type( $name, $type, $input_type_settings, $input_type_params, $input_value, $default_value );
		}

		/**
		 * Call hook for update default value for a setting.
		 *
		 * @param string $class     - module class name.
		 * @param string $style     - style name, where the option is located.
		 * @param string $name      - setting name to update default option.
		 * @param string $value     - new default value to be set for the $name setting.
		 * @since 0.0.1
		 */
		public static function cp_update_default( $class, $style, $name, $value ) {

			self::cp_update_value( $class, $style, $name, $value );
		}

		/**
		 * Remove field options.
		 *
		 * @param string $fields    - fields.
		 * @param string $options   - options.
		 * @param string $settings  - settings.
		 * @since 0.0.1
		 */
		public static function cp_remove_field_options( $fields, $options, $settings ) {

			$settings = self::cp_remove_options( $fields, $options, $settings );
			return $settings;
		}

		/**
		 * Load and initialize
		 *
		 * @param string $current_screen    - current_screen.
		 * @since 0.0.1
		 */
		function load_framework_functions( $current_screen ) {

			if ( ( ( 'edit' == $current_screen->base || 'post' == $current_screen->base ) && CP_CUSTOM_POST_TYPE == $current_screen->post_type ) ) {

				if ( ( isset( $_GET['post'] ) && isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) || 'add' == $current_screen->action ) {

					// Load style customizer.
					require_once( CP_V2_BASE_DIR . 'framework/style-customizer.php' );

					// Load default input types from the directory "lib/fields".
					foreach ( glob( $this->fields_dir . '/*/*.php' ) as $module ) {
						require_once( $module );
					}
				}
			}

		}

		/**
		 * Retrieve and store data into the static variable $options
		 *
		 * @param string $settings  - settings.
		 * @since 1.0
		 */
		function cp_store_data( $settings ) {

			$result = false;
			if ( ! empty( $settings ) ) {
				self::$options = $settings;
				$result        = true;
			}
			return $result;
		}

		/**
		 * Retrieve and update default value in stored data into the static variable $options
		 *
		 * @param string $class     - class.
		 * @param string $style     - style.
		 * @param string $name      - name.
		 * @param string $value     - value.
		 * @since 1.0
		 */
		public static function cp_update_value( $class, $style, $name, $value ) {

			$result       = false;
			$new_settings = '';
			if ( '' !== $name ) {
				$settings = $class::$options[ $style ]['options'];
				foreach ( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if ( $opt_name == $name ) {
						$settings[ $key ]['opts']['value'] = $value;
					}
				}
				$class::$options[ $style ]['options'] = $settings;
				$result                               = true;
			}
			return $result;
		}

		/**
		 * Removes option from configuration section
		 *
		 * @param string $options_to_remove - options_to_remove.
		 * @param string $options           - options.
		 * @since 0.0.1
		 */
		public function cp_remove_configuration_options( $options_to_remove, $options ) {

			foreach ( $options['options'] as $option_key => $option ) {
				if ( in_array( $option['name'], $options_to_remove ) ) {
					unset( $options['options'][ $option_key ] );
				}
			}

			return $options;
		}

		/**
		 * Removes panel from options
		 *
		 * @param string $panels    - panels.
		 * @param string $options   - options.
		 * @since 0.0.1
		 */
		public function cp_remove_panel( $panels, $options ) {
			foreach ( $options['options'] as $option_key => $option ) {
				if ( isset( $option['panel'] ) ) {
					if ( in_array( $option['panel'], $panels ) ) {
						unset( $options['options'][ $option_key ] );
					}
				}
			}

			return $options;
		}

		/**
		 * Remove field option
		 *
		 * @param string $fields    - fields.
		 * @param string $options   - options.
		 * @param string $settings  - settings.
		 * @since 0.0.1
		 */
		public static function cp_remove_options( $fields, $options, $settings ) {

			foreach ( $settings['options'] as $section_key => $setting ) {

				if ( ! empty( $fields ) && ! in_array( $setting['type'], $fields ) ) {
					continue;
				}

				if ( isset( $setting['sections'] ) ) {
					$sections = $setting['sections'];

					foreach ( $sections as $key => $section ) {

						if ( isset( $section['params'] ) ) {
							$params = $section['params'];

							foreach ( $options as $option ) {
								$params = wp_list_filter(
									$params, array(
										'name' => $option,
									), 'NOT'
								);
							}

							// Reindex array.
							$params = array_values( $params );

							$settings['options'][ $section_key ]['sections'][ $key ]['params'] = $params;
						}
					}
				}
			}

			return $settings;
		}

		/**
		 * Add popup type
		 *
		 * @param string $slug      - slug.
		 * @param string $setting   - setting.
		 * @since 0.0.1
		 */
		public static function cp_add_popup_type( $slug, $setting ) {

			$result = false;
			if ( '' != $slug ) {
				self::$types[ $slug ] = $setting;
				$result               = true;
			}
			return $result;
		}

		/**
		 * Generate dependecy attributes string for fields
		 *
		 * @param string $name      - name.
		 * @param string $array     - array.
		 * @since 0.0.1
		 */
		public static function cp_framework_create_dependency( $name, $array ) {

			if ( is_array( $array ) ) {
				$dependency = '';
				$element    = $array['name'];
				$operator   = $array['operator'];
				$value      = $array['value'];
				$type       = isset( $array['type'] ) ? $array['type'] : '';

				if ( 'media' == $type ) {
					$uid     = $_SESSION[ $element ];
					$element = $element . '_' . $uid;
				}

				$dependency = 'data-name="' . $element . '" data-element="' . $name . '" data-operator="' . $operator . '" data-value="' . $value . '"';

				return $dependency;
			} else {
				return false;
			}
		}

		/**
		 * Get panel content
		 *
		 * @param int    $style_id         - style_id.
		 * @param string $properties    - properties.
		 * @param string $panel_slug    - panel_slug.
		 * @param string $display_title - display_title.
		 * @param string $section_slug  - section_slug.
		 * @since 0.0.1
		 */
		function cp_framework_get_panel_content( $style_id, $properties, $panel_slug, $display_title, $section_slug ) {

			$html               = '';
			$hidden_fields_html = '';
			$name               = $properties['name'];
			$type               = $properties['type'];
			$is_global          = isset( $properties['opts']['global'] ) ? $properties['opts']['global'] : true;
			$tags               = isset( $properties['opts']['tags'] ) ? $properties['opts']['tags'] : false;
			$input_value        = cpro_get_style_settings( $style_id, $section_slug, $name );
			$default_value      = '';

			if ( isset( $properties['opts']['value'] ) && ! is_array( $properties['opts']['value'] ) ) {
				$default_value = urldecode( $properties['opts']['value'] );
			} elseif ( isset( $properties['opts']['value'] ) && is_array( $properties['opts']['value'] ) ) {
				$default_value = $properties['opts']['value'];
			}

			if ( '' == $input_value ) {
				$input_value = $default_value;
			}

			$properties['opts']['type'] = $type;
			$dependency                 = isset( $properties['dependency'] ) ? $properties['dependency'] : '';
			$dependency                 = self::cp_framework_create_dependency( $name, $dependency );

			$hidden_class = ( 'cp_hidden' == $type ) ? 'cp-hidden' : 'cp-element-container';

			$hidden_class .= ( 'connect' == $type ) ? ' skip-search' : '';
			$has_presets   = isset( $properties['opts']['presets'] ) ? true : false;

			if ( $has_presets ) {
				$hidden_class .= ' has-preset';
			}

			$hidden_class .= isset( $properties['show_on_mobile'] ) && $properties['show_on_mobile'] ? ' cp-mobile-show ' : '';

			$hidden_class .= isset( $properties['opts']['show_on_mobile'] ) && $properties['opts']['show_on_mobile'] ? ' cp-mobile-show ' : '';

			if ( 'cp_hidden' == $type ) {

				$hidden_fields_html .= '<div data-tags="' . $tags . '" data-panel="' . $panel_slug . '" class="' . $hidden_class . '" ' . $dependency . '>';

			} else {

				$html .= '<div data-global="' . $is_global . '" data-tags="' . $tags . '" data-panel="' . $panel_slug . '" class="' . $hidden_class . '" ' . $dependency . '>';
			}

			if ( 'section' !== $type && 'google_fonts' !== $type ) {

				if ( isset( $properties['has_params'] ) && ! $properties['has_params'] ) {
					$display_title = true;
				}

				if ( isset( $properties['has_params'] ) && $properties['has_params'] ) {
					$display_title = false;
				}

				if ( isset( $properties['opts']['title'] ) && '' !== trim( $properties['opts']['title'] ) && $display_title ) {
					$html .= '<label for="cp_' . $name . '">' . $properties['opts']['title'] . '</label>';
				}

				if ( isset( $properties['opts']['description'] ) && '' !== $properties['opts']['description'] ) {
					$html .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="' . $properties['opts']['description'] . '" style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
				}
			}

			if ( isset( $properties['sections'] ) ) {
				$sections = $properties['sections'];
			} else {
				$sections = '';
			}

			if ( 'cp_hidden' == $type ) {

				$hidden_fields_html .= $this->do_input_type_settings_field( $name, $type, $properties['opts'], $sections, $input_value, $default_value );
				$hidden_fields_html .= '</div>';

			} else {

				$html .= $this->do_input_type_settings_field( $name, $type, $properties['opts'], $sections, $input_value, $default_value );
				$html .= '</div>';
			}

			$result = array(
				'html'               => $html,
				'hidden_fields_html' => $hidden_fields_html,
			);

			return $result;

		}

		/**
		 * Update field options
		 *
		 * @param string $settings - settings.
		 * @since 0.0.1
		 */
		function cp_update_field_options( $settings ) {

			$text_section = array(
				array(
					'title'  => __( 'General', 'convertpro' ),
					'params' => array(),
				),
				array(
					'title'  => __( 'Text', 'convertpro' ),
					'params' => array(
						array(
							'id'            => 'title',
							'name'          => 'title',
							'type'          => 'text',
							'suffix'        => '',
							'label'         => __( 'Button Text', 'convertpro' ),
							'default_value' => __( 'Button Default Text', 'convertpro' ),
							'map'           => array(
								'attr'   => 'value',
								'target' => '.cp-target',
							),
						),
						array(
							'id'            => 'font_family',
							'name'          => 'font_family',
							'type'          => 'font',
							'label'         => __( 'Font Family', 'convertpro' ),
							'default_value' => 'inherit:inherit',
							'map_style'     => array(
								'parameter' => 'font-family',
								'target'    => '.cp-target',
							),
						),
						array(
							'id'             => 'font_size',
							'name'           => 'font_size',
							'type'           => 'number',
							'suffix'         => 'px',
							'step'           => 1,
							'label'          => __( 'Font Size', 'convertpro' ),
							'default_value'  => '14px',
							'map_style'      => array(
								'parameter' => 'font-size',
								'target'    => '.cp-target',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'line_height',
							'name'           => 'line_height',
							'type'           => 'slider',
							'suffix'         => 'em',
							'label'          => __( 'Line Height', 'convertpro' ),
							'min'            => 1,
							'max'            => 3,
							'step'           => 0.01,
							'default_value'  => 1.6,
							'map_style'      => array(
								'parameter' => 'line-height',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'letter_spacing',
							'name'           => 'letter_spacing',
							'type'           => 'slider',
							'suffix'         => 'px',
							'label'          => __( 'Letter Spacing', 'convertpro' ),
							'min'            => 0,
							'max'            => 20,
							'step'           => 0.01,
							'default_value'  => 0,
							'map_style'      => array(
								'parameter' => 'letter-spacing',
								'unit'      => 'px',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'btn_text_align',
							'name'           => 'btn_text_align',
							'type'           => 'text_align',
							'label'          => __( 'Text Alignment', 'convertpro' ),
							'options'        => array(
								'center'  => 'center',
								'left'    => 'left',
								'right'   => 'right',
								'justify' => 'justify',
							),
							'default_value'  => 'center',
							'map_style'      => array(
								'parameter' => 'text-align',
								'target'    => '.cp-target',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'            => 'text_color',
							'name'          => 'text_color',
							'type'          => 'colorpicker',
							'label'         => __( 'Color', 'convertpro' ),
							'default_value' => '#555',
							'map_style'     => array(
								'parameter' => 'color',
							),
						),
						array(
							'id'            => 'text_hover_color',
							'name'          => 'text_hover_color',
							'type'          => 'colorpicker',
							'label'         => __( 'Title Hover Color', 'convertpro' ),
							'default_value' => '',
							'map_style'     => array(
								'parameter' => 'color',
								'onhover'   => true,
							),
						),
					),
				),
			);

			$background_section = array(
				array(
					'title'  => __( 'Colors', 'convertpro' ),
					'params' => array(
						array(
							'id'            => 'back_color',
							'name'          => 'back_color',
							'type'          => 'colorpicker',
							'label'         => __( 'Background Color', 'convertpro' ),
							'default_value' => '#337ab7',
							'map_style'     => array(
								'parameter' => 'background',
							),
						),
						array(
							'id'            => 'back_color_hover',
							'name'          => 'back_color_hover',
							'type'          => 'colorpicker',
							'label'         => __( 'Background Hover Color', 'convertpro' ),
							'default_value' => '',
							'map_style'     => array(
								'parameter' => 'background',
								'onhover'   => true,
							),
						),
					),
				),
			);

			$action_section = array(
				array(
					'title'  => __( 'Action', 'convertpro' ),
					'params' => array(
						array(
							'id'             => 'field_action',
							'name'           => 'field_action',
							'type'           => 'dropdown',
							'label'          => __( 'Field Action', 'convertpro' ),
							'default_value'  => 'none',
							'hide_on_mobile' => true,
							'options'        => array(
								'none'               => __( 'None', 'convertpro' ),
								'submit'             => __( 'Submit', 'convertpro' ),
								'submit_n_goto_step' => __( 'Submit & Go to Step', 'convertpro' ),
								'submit_n_goto_url'  => __( 'Submit & Go to URL', 'convertpro' ),
								'submit_n_close'     => __( 'Submit & Close', 'convertpro' ),
								'goto_url'           => __( 'Go to URL', 'convertpro' ),
								'goto_step'          => __( 'Go to Step', 'convertpro' ),
								'close'              => __( 'Close', 'convertpro' ),
								'close_tab'          => __( 'Close Page', 'convertpro' ),
							),
							'map'            => array(
								'attr'   => 'button-type',
								'target' => '.cp-target',
							),
						),
						array(
							'id'            => 'submit_message',
							'name'          => 'submit_message',
							'type'          => 'textarea',
							'label'         => __( 'Successful Submission Message', 'convertpro' ),
							'default_value' => __( 'Thank You for Subscribing!', 'convertpro' ),
							'dependency'    => array(
								'relation' => 'OR',
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit_n_close',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit_n_goto_url',
								),
							),
							'map'           => array(
								'attr'   => 'data-submit-message',
								'target' => '.cp-target',
							),
						),
						array(
							'id'             => 'get_parameter',
							'name'           => 'get_parameter',
							'type'           => 'switch',
							'default_value'  => false,
							'label'          => __( 'Pass through GET parameter', 'convertpro' ),
							'description'    => __( 'Turn on this option if you wish to pass the form values as GET parameter.', 'convertpro' ),
							'hide_on_mobile' => true,
							'options'        => array(
								'on'  => __( 'Yes', 'convertpro' ),
								'off' => __( 'No', 'convertpro' ),
							),
							'map'            => array(
								'attr'   => 'click-event',
								'target' => '.cp-field-html-data',
							),
							'dependency'     => array(
								'name'     => 'field_action',
								'operator' => '==',
								'value'    => 'submit_n_goto_url',
							),
						),
						array(
							'id'             => 'btn_url',
							'name'           => 'btn_url',
							'type'           => 'text',
							'suffix'         => '',
							'label'          => __( 'Enter URL here', 'convertpro' ),
							'default_value'  => '',
							'hide_on_mobile' => true,
							'dependency'     => array(
								'relation' => 'OR',
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'goto_url',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit_n_goto_url',
								),
							),
						),
						array(
							'id'             => 'btn_url_target',
							'name'           => 'btn_url_target',
							'type'           => 'dropdown',
							'suffix'         => '',
							'label'          => __( 'Enter URL Target', 'convertpro' ),
							'default_value'  => '_self',
							'hide_on_mobile' => true,
							'options'        => array(
								'_self'  => 'Same Window',
								'_blank' => 'New Tab',
							),
							'dependency'     => array(
								'relation' => 'OR',
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'goto_url',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit_n_goto_url',
								),
							),
						),
						array(
							'id'             => 'btn_url_follow',
							'name'           => 'btn_url_follow',
							'type'           => 'dropdown',
							'suffix'         => '',
							'label'          => __( 'Enter URL Follow', 'convertpro' ),
							'default_value'  => '_self',
							'hide_on_mobile' => true,
							'options'        => array(
								'no_follow' => 'No Follow',
								'do_follow' => 'Do Follow',
							),
							'dependency'     => array(
								'name'     => 'field_action',
								'operator' => '==',
								'value'    => 'goto_url',
							),
						),
						array(
							'id'             => 'btn_step',
							'name'           => 'btn_step',
							'type'           => 'dropdown',
							'hide_on_mobile' => true,
							'label'          => __( 'Select Step', 'convertpro' ),
							'default_value'  => '1',
							'options'        => array(
								'1' => '1',
							),
							'map'            => array(),
							'dependency'     => array(
								'relation' => 'OR',
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'goto_step',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'submit_n_goto_step',
								),
							),
						),
						array(
							'id'             => 'count_as_conversion',
							'name'           => 'count_as_conversion',
							'type'           => 'switch',
							'default_value'  => false,
							'label'          => __( 'Count as a conversion', 'convertpro' ),
							'hide_on_mobile' => true,
							'options'        => array(
								'on'  => __( 'YES', 'convertpro' ),
								'off' => __( 'NO', 'convertpro' ),
							),
							'dependency'     => array(
								'relation' => 'OR',
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'goto_url',
								),
								array(
									'name'     => 'field_action',
									'operator' => '==',
									'value'    => 'goto_step',
								),
							),
						),
					),
				),
			);

			$advance_section = array(
				array(
					'title'  => __( 'Advanced', 'convertpro' ),
					'params' => array(
						array(
							'id'    => 'label_animation',
							'name'  => 'label_animation',
							'type'  => 'label',
							'label' => __( 'Animation', 'convertpro' ),
						),
						array(
							'id'             => 'field_animation',
							'name'           => 'field_animation',
							'type'           => 'dropdown',
							'label'          => __( 'Animation', 'convertpro' ),
							'default_value'  => 'none',
							'hide_on_mobile' => true,
							'options'        => apply_filters( 'cp_entry_animations', array() ),
							'map'            => array(
								'attr'   => 'data-anim-class',
								'target' => '.cp-field-html-data',
							),
							'map_style'      => array(
								'parameter' => 'removeAnimClass',
								'target'    => '.cp-field-html-data',
								'unit'      => 'data-anim-class',
							),
						),
						array(
							'id'            => 'field_animation_delay',
							'name'          => 'field_animation_delay',
							'type'          => 'number',
							'suffix'        => 'ms,s',
							'label'         => __( 'Animation Delay', 'convertpro' ),
							'default_value' => '0ms',
							'min'           => 0,
							'dependency'    => array(
								'name'     => 'field_animation',
								'operator' => '!=',
								'value'    => 'cp-none',
							),
							'map'           => array(
								'attr'   => 'data-anim-delay',
								'target' => '.cp-field-html-data',
							),
						),
						array(
							'id'            => 'field_animation_duration',
							'name'          => 'field_animation_duration',
							'type'          => 'number',
							'suffix'        => 'ms,s',
							'label'         => __( 'Animation Duration', 'convertpro' ),
							'default_value' => '1000ms',
							'min'           => 0,
							'dependency'    => array(
								'name'     => 'field_animation',
								'operator' => '!=',
								'value'    => 'cp-none',
							),
							'map'           => array(
								'attr'   => 'data-anim-duration',
								'target' => '.cp-field-html-data',
							),
						),
						array(
							'id'    => 'label_border',
							'name'  => 'label_border',
							'type'  => 'label',
							'label' => __( 'Border', 'convertpro' ),
						),
						array(
							'id'             => 'border_style',
							'name'           => 'border_style',
							'type'           => 'dropdown',
							'hide_on_mobile' => true,
							'label'          => __( 'Border Style', 'convertpro' ),
							'default_value'  => 'none',
							'options'        => array(
								'solid'  => __( 'Solid', 'convertpro' ),
								'dotted' => __( 'Dotted', 'convertpro' ),
								'dashed' => __( 'Dashed', 'convertpro' ),
								'none'   => __( 'None', 'convertpro' ),
							),
							'map_style'      => array(
								'parameter' => 'border-style',
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow',
							),
						),
						array(
							'id'             => 'border_color',
							'name'           => 'border_color',
							'type'           => 'colorpicker',
							'hide_on_mobile' => true,
							'label'          => __( 'Border Color', 'convertpro' ),
							'default_value'  => '#757575',
							'map_style'      => array(
								'parameter' => 'border-color',
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow',
							),
							'dependency'     => array(
								'name'     => 'border_style',
								'operator' => '!=',
								'value'    => 'none',
							),
						),
						array(
							'id'             => 'border_hover_color',
							'name'           => 'border_hover_color',
							'type'           => 'colorpicker',
							'hide_on_mobile' => true,
							'suffix'         => '',
							'label'          => __( 'Border Hover Color', 'convertpro' ),
							'default_value'  => '',
							'map_style'      => array(
								'parameter' => 'border-color',
								'onhover'   => true,
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow',
							),
							'dependency'     => array(
								'name'     => 'border_style',
								'operator' => '!=',
								'value'    => 'none',
							),
						),
						array(
							'id'             => 'border_width',
							'name'           => 'border_width',
							'type'           => 'multiinput',
							'hide_on_mobile' => true,
							'label'          => __( 'Border Width', 'convertpro' ),
							'suffix'         => 'px',
							'default_value'  => '1|1|1|1|px',
							'map_style'      => array(
								'parameter' => 'border-width',
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow',
							),
							'dependency'     => array(
								'name'     => 'border_style',
								'operator' => '!=',
								'value'    => 'none',
							),
						),
						array(
							'id'             => 'border_radius',
							'name'           => 'border_radius',
							'type'           => 'multiinput',
							'hide_on_mobile' => true,
							'label'          => __( 'Border Radius', 'convertpro' ),
							'suffix'         => 'px',
							'default_value'  => '0|0|0|0|px',
							'map_style'      => array(
								'parameter' => 'border-radius',
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow,.cp-target > .cp-close-link,.cp-target > .cp-close-image',
							),
						),
						array(
							'id'    => 'label_box_shadow',
							'name'  => 'label_box_shadow',
							'type'  => 'label',
							'label' => __( 'Box Shadow', 'convertpro' ),
						),
						array(
							'id'             => 'field_box_shadow',
							'name'           => 'field_box_shadow',
							'type'           => 'box_shadow',
							'hide_on_mobile' => true,
							'suffix'         => '',
							'label'          => __( 'Box Shadow', 'convertpro' ),
							'default_value'  => 'type:none|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
							'options'        => array(
								'none'   => __( 'None', 'convertpro' ),
								'inset'  => __( 'Inset', 'convertpro' ),
								'outset' => __( 'Outset', 'convertpro' ),
							),
							'map_style'      => array(
								'parameter' => 'box_shadow',
								'target'    => '.cp-target,.cp-target ~ .cp-field-shadow',
							),
						),
						array(
							'id'    => 'label_position',
							'name'  => 'label_position',
							'type'  => 'label',
							'label' => __( 'Position', 'convertpro' ),
						),
						array(
							'id'             => 'respective_to',
							'name'           => 'respective_to',
							'type'           => 'switch',
							'default_value'  => false,
							'label'          => __( 'Field Respective To', 'convertpro' ),
							'hide_on_mobile' => true,
							'options'        => array(
								'on'  => __( 'Overlay', 'convertpro' ),
								'off' => __( 'Panel', 'convertpro' ),
							),
							'map'            => array(
								'attr'   => 'data-overlay-respective',
								'target' => '.cp-field-html-data',
							),
						),
						array(
							'id'             => 'is_outside_hide',
							'name'           => 'is_outside_hide',
							'type'           => 'switch',
							'default_value'  => false,
							'hide_on_mobile' => true,
							'label'          => __( 'Hide Area Outside Canvas', 'convertpro' ),
							'options'        => array(
								'on'  => __( 'YES', 'convertpro' ),
								'off' => __( 'NO', 'convertpro' ),
							),
							'dependency'     => array(
								'name'     => 'respective_to',
								'operator' => '!=',
								'value'    => 'true',
							),
							'map'            => array(),
						),
						array(
							'id'            => 'respective_to_panel',
							'name'          => 'respective_to_panel',
							'type'          => 'hidden',
							'default_value' => '',
							'map'           => array(),
						),
						array(
							'id'            => 'respective_to_overlay',
							'name'          => 'respective_to_overlay',
							'type'          => 'hidden',
							'default_value' => '',
							'map'           => array(),
						),
						array(
							'id'            => 'hide_on_mobile',
							'name'          => 'hide_on_mobile',
							'type'          => 'hidden',
							'default_value' => 'no',
							'map'           => array(
								'attr'   => 'invisible-class',
								'target' => '.cp-field-html-data',
							),
						),
						array(
							'id'    => 'behaviour',
							'name'  => 'behaviour',
							'type'  => 'label',
							'label' => __( 'Behavior', 'convertpro' ),
						),
						array(
							'id'             => 'non_clickable',
							'name'           => 'non_clickable',
							'type'           => 'switch',
							'default_value'  => false,
							'label'          => __( 'Disable Click Event', 'convertpro' ),
							'description'    => __( 'Turn on this option if you wish to disable mouse click event on your element.', 'convertpro' ),
							'hide_on_mobile' => true,
							'options'        => array(
								'on'  => __( 'Yes', 'convertpro' ),
								'off' => __( 'No', 'convertpro' ),
							),
							'map'            => array(
								'attr'   => 'click-event',
								'target' => '.cp-field-html-data',
							),
						),
						array(
							'id'             => 'label_layout',
							'name'           => 'label_layout',
							'type'           => 'label',
							'label'          => __( 'Layout', 'convertpro' ),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'width',
							'name'           => 'width',
							'label'          => 'Width',
							'type'           => 'number',
							'default_value'  => 180,
							'min'            => 1,
							'max'            => 800,
							'step'           => 1,
							'suffix'         => 'px',
							'map_style'      => array(
								'parameter' => 'width',
								'unit'      => 'px',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'height',
							'name'           => 'height',
							'type'           => 'number',
							'label'          => 'Height',
							'default_value'  => 50,
							'min'            => 1,
							'max'            => 200,
							'step'           => 1,
							'suffix'         => 'px',
							'map_style'      => array(
								'parameter' => 'height',
								'unit'      => 'px',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'rotate_field',
							'name'           => 'rotate_field',
							'type'           => 'number',
							'label'          => __( 'Rotation Angle', 'convertpro' ),
							'default_value'  => 0,
							'suffix'         => 'deg',
							'map_style'      => array(
								'parameter' => 'transform',
								'target'    => '.cp-field-html-data',
								'unit'      => '',
							),
							'show_on_mobile' => true,
						),
						array(
							'id'             => 'field_padding',
							'name'           => 'field_padding',
							'type'           => 'multiinput',
							'label'          => __( 'Padding', 'convertpro' ),
							'suffix'         => 'px',
							'default_value'  => '0|15|0|15|px',
							'map_style'      => array(
								'parameter' => 'padding',
								'target'    => '.cp-target',
							),
							'show_on_mobile' => false,
						),
						array(
							'id'            => 'field_custom_class',
							'name'          => 'field_custom_class',
							'type'          => 'text',
							'label'         => __( 'Custom Class', 'convertpro' ),
							'default_value' => '',
							'map'           => array(
								'attr'   => 'custom-class',
								'target' => '.cp-target',
							),
						),
					),
				),
			);

			$sections = array_merge( $text_section, $background_section, $action_section, $advance_section );

			foreach ( $settings as $parent_key => $setting ) {

				if ( ! isset( $setting['has_params'] ) ) {
					foreach ( $sections as $section_key => $section ) {

						$new_section    = true;
						$param_settings = $section;
						$title          = strtolower( $section['title'] );

						if ( isset( $setting['sections'] ) ) {
							foreach ( $setting['sections'] as $field_min => $field_settings ) {
								$section_title = $field_settings['title'];

								if ( strtolower( $section_title ) == $title ) {

									$new_section = false;
									$params      = isset( $field_settings['params'] ) ? $field_settings['params'] : array();

									if ( is_array( $params ) && ! empty( $params ) ) {
										foreach ( $params as $child_key => $param ) {
											$flag     = true;
											$param_id = $param['id'];
											foreach ( $param_settings['params'] as $key => $value ) {

												// If param id match, unset default values.
												if ( $value['id'] == $param['id'] ) {
													$param_settings['params'][ $key ] = $param;
													$flag                             = false;
												}
											}
											if ( $flag ) {
												$param_settings['params'][ $key + 1 ] = $param;
											}
										}
									}

									$setting['sections'][ $field_min ] = $param_settings;
								}
							}
						}

						if ( $new_section ) {
							$setting['sections'][] = $param_settings;
						}
					}

					$settings[ $parent_key ]['sections'] = $setting['sections'];

				}
			}

			return $settings;
		}
	}
	new Cp_Framework;
}
