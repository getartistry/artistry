<?php
/**
 * Defined popup types and settings.
 *
 * @package convertpro
 */

/**
 * Class CP_Welcome_Mat.
 */
class CP_Welcome_Mat extends cp_Framework {

	/**
	 * Options
	 *
	 * @var options
	 */
	public static $options = array();

	/**
	 * Slug
	 *
	 * @var slug
	 */
	public static $slug = 'welcome_mat';

	/**
	 * Settings
	 *
	 * @var settings
	 */
	public static $settings = array();

	/**
	 * Constructor
	 */
	function __construct() {
		self::$settings = array(
			'title'       => __( 'Convert Mat', 'convertpro' ),
			'description' => __( 'A Light-box overlay that covers the entire screen and appears like a welcome gate on a webpage.', 'convertpro' ),
		);
		parent::cp_add_popup_type( self::$slug, self::$settings );

		add_filter( 'cp_button_flatbtn_options', array( $this, 'remove_field_actions' ) );
		add_filter( 'cp_button_gradientbtn_options', array( $this, 'remove_field_actions' ) );
		add_filter( 'cp_shapes_options', array( $this, 'remove_field_actions' ) );
	}

	/**
	 * Function Name: hide_enable_onload_option.
	 * Function Description: This will modify configuration options.
	 *
	 * @param Array $options Options array.
	 */
	function hide_enable_onload_option( $options ) {

		foreach ( $options as $key => $option ) {

			// Set field type hidden for display inline option.
			if ( 'autoload_on_duration' == $option['name'] ) {

				$option['type']                = 'cp_hidden';
				$option['opts']['title']       = '';
				$option['opts']['description'] = '';
			}

			// Remove dependency for display inline position.
			if ( 'load_on_duration' == $option['name'] ) {
				$option['type']                = 'cp_hidden';
				$option['opts']['title']       = '';
				$option['opts']['description'] = '';
			}

			$options[ $key ] = $option;
		}

		return $options;

	}

	/**
	 * Function Name: get_options.
	 * Function Description: get options.
	 */
	function get_options() {
		$options = parent::$options;

		// This will remove Display Inline & Shortcode options.
		$options = parent::cp_remove_configuration_options(
			array(
				'enable_display_inline',
				'inline_position',
				'inline_shortcode',
				'autoload_on_scroll',
				'load_after_scroll',
				'modal_exit_intent',
				'enable_custom_scroll',
				'custom_cls_text_head',
				'custom_cls_text',
				'copy_link_code_button',
				'enable_scroll_class',
				'inactivity',
				'copy_link_cls_code_button',
				'on_scroll_txt',
				'inactivity_link',
				'enable_after_post',
				'enable_custom_class',
				'custom_class',
			),
			$options
		);

		$design_options = $this->get_design_options();

		$design_options = apply_filters( 'cp_after_design_fields', $design_options, self::$slug );

		$options['options'] = array_merge( $design_options, $options['options'] );

		$options = parent::cp_remove_field_options(
			array(
				'cp_text',
				'cp_number',
				'cp_dropdown',
				'cp_email',
				'cp_textarea',
				'cp_close_link',
				'cp_custom_html',
				'cp_video',
			),
			array(
				'field_action',
				'btn_url',
				'btn_url_target',
				'btn_url_follow',
				'submit_message_text_color',
				'submit_message_bg_color',
				'submit_message_layout',
				'count_as_conversion',
				'btn_step',
				'submit_message',
				'label_box_shadow',
				'submit_message_font_size',
			),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_heading',
				'cp_sub_heading',
				'cp_paragraph',
			),
			array( 'back_color', 'back_color_hover', 'title', 'text_hover_color', 'field_box_shadow', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'label_box_shadow', 'submit_message_font_size', 'label_border', 'border_style', 'border_width', 'border_color', 'border_hover_color', 'border_radius', 'field_padding', 'count_as_conversion', 'get_parameter' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_countdown',
			),
			array( 'back_color', 'back_color_hover', 'title', 'label_layout_clink', 'btn_text_align', 'text_hover_color', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'line_height', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'label_box_shadow', 'submit_message_font_size', 'count_as_conversion', 'get_parameter', 'field_padding', 'border_radius', 'border_hover_color', 'border_width', 'border_color', 'border_style', 'label_border', 'text_color', 'font_size' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_shape',
				'cp_dual_color_shape',
			),
			array( 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'back_color', 'back_color_hover', 'letter_spacing', 'btn_text_align', 'line_height', 'label_border', 'border_style', 'border_radius', 'border_color', 'border_hover_color', 'border_width', 'field_padding' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_image',
			),
			array( 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'back_color', 'back_color_hover', 'letter_spacing', 'btn_text_align', 'line_height', 'field_padding' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_text',
				'cp_number',
				'cp_dropdown',
				'cp_email',
				'cp_textarea',
			),
			array( 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'input_text_padding', 'border_style', 'border_color', 'border_width', 'border_radius', 'field_box_shadow', 'field_padding' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_gradient_button',
			),
			array( 'back_color', 'back_color_hover', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'btn_text_align', 'letter_spacing', 'line_height', 'label_position', 'respective_to', 'is_outside_hide' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_custom_html',
				'cp_video',
			),
			array( 'text_hover_color', 'back_color', 'back_color_hover', 'title', 'label_action', 'text_hover_color', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'submit_message_font_size', 'field_padding' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_video',
			),
			array( 'font_family', 'font_size', 'line_height', 'letter_spacing', 'btn_text_align', 'text_color', 'rotate_field', 'respective_to', 'is_outside_hide', 'non_clickable', 'label_position', 'behaviour' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_close_text',
				'cp_close_image',
			),
			array( 'back_color', 'back_color_hover', 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'label_layout_clink', 'btn_text_align', 'letter_spacing', 'line_height', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'label_box_shadow', 'submit_message_font_size', 'field_padding', 'get_parameter', 'count_as_conversion' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_button',
			),
			array( 'line_height', 'text_color', 'text_hover_color', 'back_color', 'back_color_hover', 'label_position', 'respective_to', 'is_outside_hide' ),
			$options
		);

		$options = apply_filters( 'cp_after_options', $options );

		return $options;
	}
	/**
	 * Function Name: get_design_options.
	 * Function Description: get design options.
	 */
	function get_design_options() {

		$icons_array = parent::$icon_options;

		// check if affiliate link is active.
		$panel_design_options = array(
			array(
				'type'           => 'number',
				'class'          => '',
				'name'           => 'panel_width',
				'opts'           => array(
					'title'       => __( 'Width', 'convertpro' ),
					'value'       => array( 800, 360 ),
					'min'         => 100,
					'max'         => array( 1500, 360 ),
					'step'        => 1,
					'suffix'      => 'px',
					'reset'       => 'true',
					'description' => __( 'Width for Panel', 'convertpro' ),
					'tags'        => 'size,width',
					'map_style'   => array(
						'parameter' => 'width',
						'unit'      => 'px',
					),
					'global'      => false,
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Size',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'number',
				'class'          => '',
				'name'           => 'panel_height',
				'opts'           => array(
					'title'         => __( 'Height', 'convertpro' ),
					'value'         => array( 550, 480 ),
					'default_value' => array( 550, 480 ),
					'min'           => 100,
					'max'           => array( 800, 600 ),
					'step'          => 1,
					'suffix'        => 'px',
					'reset'         => 'true',
					'description'   => __( 'Height for Panel', 'convertpro' ),
					'tags'          => 'size,height',
					'map_style'     => array(
						'parameter' => 'height',
						'unit'      => 'px',
					),
					'global'        => false,
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Size',
				'show_on_mobile' => true,
			),
			array(
				'type'         => 'switch',
				'class'        => '',
				'name'         => 'inherit_bg_prop',
				'opts'         => array(
					'title'          => __( 'Background Properties', 'convertpro' ),
					'value'          => true,
					'on'             => __( 'Inherit', 'convertpro' ),
					'off'            => __( 'Custom', 'convertpro' ),
					'description'    => __( 'If enabled, background properties will get inherited from first step', 'convertpro' ),
					'tags'           => 'inherit',
					'global'         => false,
					'map_style'      => array(
						'parameter' => 'inherit_bg',
					),
					'show_on_mobile' => true,
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Background',
			),
			array(
				'type'           => 'dropdown',
				'class'          => '',
				'name'           => 'background_type',
				'opts'           => array(
					'title'       => __( 'Background Type', 'convertpro' ),
					'value'       => 'color',
					'description' => '',
					'options'     => array(
						'color'    => __( 'Color', 'convertpro' ),
						'gradient' => __( 'Gradient', 'convertpro' ),
						'image'    => __( 'Image', 'convertpro' ),
					),
					'map_style'   => array(
						'parameter' => 'background_type',
					),
					'tags'        => 'background type,background',
					'global'      => false,
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'panel_lighter_color',
				'opts'           => array(
					'title'       => __( 'First Color', 'convertpro' ),
					'value'       => '#fff',
					'description' => '',
					'tags'        => 'background,linear,radial,gradient,linear gradient,radial gradient',
					'map_style'   => array(
						'parameter' => 'lighten_color',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'gradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'panel_darker_color',
				'opts'           => array(
					'title'       => __( 'Second Color', 'convertpro' ),
					'value'       => '#ddd',
					'description' => '',
					'tags'        => 'background,linear,radial,gradient,linear gradient,radial gradient',
					'map_style'   => array(
						'parameter' => 'darken-color',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'gradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'gradient_lighter_location',
				'opts'           => array(
					'title'       => __( 'Gradient Start Location', 'convertpro' ),
					'value'       => 0,
					'min'         => 0,
					'max'         => 100,
					'step'        => 1,
					'suffix'      => '%',
					'description' => '',
					'tags'        => 'background,linear,radial,gradient,linear gradient,radial gradient',
					'map_style'   => array(
						'parameter' => 'gradient_lighter_location',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'gradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'gradient_darker_location',
				'opts'           => array(
					'title'       => __( 'Gradient End Location', 'convertpro' ),
					'value'       => 100,
					'min'         => 0,
					'max'         => 100,
					'step'        => 1,
					'suffix'      => '%',
					'description' => '',
					'tags'        => 'background,linear,radial,gradient,linear gradient,radial gradient',
					'map_style'   => array(
						'parameter' => 'gradient_darker_location',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'gradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'dropdown',
				'class'          => '',
				'name'           => 'panel_gradient_type',
				'opts'           => array(
					'title'     => __( 'Type', 'convertpro' ),
					'value'     => 'lineargradient',
					'options'   => array(
						'lineargradient' => __( 'Linear', 'convertpro' ),
						'radialgradient' => __( 'Radial', 'convertpro' ),
					),
					'tags'      => 'background,linear,radial,gradient,gradient type,linear gradient,radial gradient',
					'map_style' => array(
						'parameter' => 'panel_gradient_type',
					),
					'global'    => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'gradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'dropdown',
				'class'          => '',
				'name'           => 'radial_panel_gradient_direction',
				'opts'           => array(
					'title'       => __( 'Gradient Direction', 'convertpro' ),
					'value'       => 'center_center',
					'description' => '',
					'options'     => array(
						'center_center' => __( 'Center Center', 'convertpro' ),
						'center_left'   => __( 'Center Left', 'convertpro' ),
						'center_right'  => __( 'Center Right', 'convertpro' ),
						'top_center'    => __( 'Top Center', 'convertpro' ),
						'top_left'      => __( 'Top Left', 'convertpro' ),
						'top_right'     => __( 'Top Right', 'convertpro' ),
						'bottom_center' => __( 'Bottom Center', 'convertpro' ),
						'bottom_left'   => __( 'Bottom Left', 'convertpro' ),
						'bottom_right'  => __( 'Bottom Right', 'convertpro' ),
					),
					'tags'        => 'background,radial,gradient,gradient direction,radial gradient',
					'map_style'   => array(
						'parameter' => 'radial_gradient_direction',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'panel_gradient_type',
					'operator' => '==',
					'value'    => 'radialgradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'gradient_angle',
				'opts'           => array(
					'title'       => __( 'Gradient Direction', 'convertpro' ),
					'value'       => 180,
					'min'         => 0,
					'max'         => 360,
					'step'        => 1,
					'suffix'      => 'deg',
					'description' => '',
					'tags'        => 'background,linear,gradient,angle,linear gradient',
					'map_style'   => array(
						'parameter' => 'gradient_angle',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'panel_gradient_type',
					'operator' => '==',
					'value'    => 'lineargradient',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'panel_background_color',
				'opts'           => array(
					'title'       => __( 'Background Color', 'convertpro' ),
					'value'       => '#fff',
					'description' => '',
					'tags'        => 'background,background color',
					'map_style'   => array(
						'parameter' => 'background-color',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'color',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'media',
				'class'          => '',
				'name'           => 'panel_bg_image',
				'opts'           => array(
					'title'       => __( 'Background Image', 'convertpro' ),
					'value'       => '0|modules/img/grey.png',
					'description' => __( "You can provide an image that would be appear behind the content in the modal box area. For this setting to work, the background color you've chosen must be transparent.", 'convertpro' ),
					'tags'        => 'background image,background',
					'map_style'   => array(
						'parameter' => 'background-image',
					),
					'global'      => false,
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'image',
				),
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'background',
				'class'          => '',
				'name'           => 'opt_bg',
				'opts'           => array(
					'title'     => '',
					'value'     => 'repeat|center|contain',
					'tags'      => 'background image,background,background repeat,background position,background size',
					'map_style' => array(
						'parameter' => 'background_opt',
					),
					'global'    => false,
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'image',
				),
				'category'       => 'Background',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'panel_img_overlay_color',
				'opts'           => array(
					'title'       => __( 'Background Overlay Color', 'convertpro' ),
					'value'       => 'rgba(0,0,0,0.12)',
					'description' => '',
					'tags'        => 'background,background overlay color',
					'map_style'   => array(
						'parameter' => 'panel_img_overlay_color',
					),
					'global'      => false,
				),
				'dependency'     => array(
					'name'     => 'background_type',
					'operator' => '==',
					'value'    => 'image',
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Background',
				'show_on_mobile' => true,
				'global'         => false,
			),
			array(
				'type'         => 'dropdown',
				'class'        => '',
				'name'         => 'panel_border_style',
				'opts'         => array(
					'title'     => __( 'Border Style', 'convertpro' ),
					'value'     => 'none',
					'options'   => parent::$border_options,
					'tags'      => 'border,border style',
					'map_style' => array(
						'parameter' => 'border-style',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Border',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'panel_border_color',
				'opts'         => array(
					'title'       => __( 'Border Color', 'convertpro' ),
					'value'       => '#e1e1e1',
					'description' => '',
					'tags'        => 'border,border color',
					'map_style'   => array(
						'parameter' => 'border-color',
					),
				),
				'dependency'   => array(
					'name'     => 'panel_border_style',
					'operator' => '!=',
					'value'    => 'none',
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Border',
			),
			array(
				'type'         => 'multiinput',
				'class'        => '',
				'name'         => 'panel_border_width',
				'opts'         => array(
					'title'     => __( 'Border Width', 'convertpro' ),
					'value'     => '1|1|1|1|px',
					'min'       => 0,
					'max'       => 50,
					'step'      => 1,
					'suffix'    => 'px',
					'tags'      => 'border,border width',
					'map_style' => array(
						'parameter' => 'border-width',
					),
				),
				'dependency'   => array(
					'name'     => 'panel_border_style',
					'operator' => '!=',
					'value'    => 'none',
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Border',
			),
			array(
				'type'         => 'multiinput',
				'class'        => '',
				'name'         => 'panel_border_radius',
				'opts'         => array(
					'title'     => __( 'Rounded Corners', 'convertpro' ),
					'value'     => '3|3|3|3|px',
					'min'       => 0,
					'max'       => 250,
					'step'      => 1,
					'suffix'    => 'px',
					'tags'      => 'border,rounded corners',
					'map_style' => array(
						'parameter' => 'border-radius',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Border',
			),
			array(
				'type'         => 'box_shadow',
				'class'        => '',
				'name'         => 'panel_box_shadow',
				'opts'         => array(
					'title'     => '',
					'value'     => 'type:none|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
					'tags'      => 'box shadow,shadow color,blur radius,spread radius,horizontal length,vertical length',
					'map_style' => array(
						'parameter' => 'box-shadow',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Shadow',
			),
			array(
				'type'         => 'slider',
				'class'        => '',
				'name'         => 'cp_mobile_br_point',
				'opts'         => array(
					'title'          => __( 'Mobile Breakpoint', 'convertpro' ),
					'value'          => 767,
					'min'            => 300,
					'max'            => 800,
					'step'           => 1,
					'suffix'         => 'px',
					'description'    => __( 'Responsive breakpoint for mobile.', 'convertpro' ),
					'tags'           => 'mobile,breakpoint',
					'show_on_mobile' => true,
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Mobile Breakpoint',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'credit_link_color',
				'opts'         => array(
					'title'       => __( 'Credit Link Color', 'convertpro' ),
					'value'       => '#fff',
					'description' => '',
					'tags'        => 'credit link,credit link color',
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Credit Link',
			),

		);

		$credit_enable = esc_attr( get_option( 'cp_credit_option' ) );
		if ( '' != $credit_enable && 0 == $credit_enable ) {
			foreach ( $panel_design_options as $key => $value ) {
				if ( 'credit_link_color' == $value['name'] ) {
					unset( $panel_design_options[ $key ] );
				}
			}
		}

		/*** Arr'y con'ains'Advance'design field options */
		$design_field_options = array(

			// Heading.
			parent::$cp_heading_opts,

			// Sub Heading.
			parent::$cp_subheading_opts,

			// Paragraph.
			parent::$cp_paragraph_opts,

			// Custom HTML.
			parent::$cp_custom_html_opts,

			// Image.
			parent::$cp_image_opts,

			// Close Image.
			array(
				'type'         => 'cp_close_image',
				'class'        => '',
				'name'         => 'cp_close_image',
				'opts'         => array(
					'title'          => __( 'Image', 'convertpro' ),
					'value'          => '',
					'description'    => '',
					'tags'           => 'close image,close',
					'resize'         => true,
					'show_on_mobile' => false,
				),
				'sections'     => array(
					array(
						'title'  => 'Image',
						'params' => array(
							array(
								'id'            => 'close_image_type',
								'name'          => 'close_image_type',
								'type'          => 'dropdown',
								'label'         => __( 'Image Source', 'convertpro' ),
								'default_value' => 'predefined',
								'options'       => array(
									'upload'     => __( 'Upload Image', 'convertpro' ),
									'predefined' => __( 'Predefined Icons', 'convertpro' ),
								),
								'map_style'     => array(
									'parameter' => 'close-image-type',
								),
							),
							array(
								'id'            => 'module_image',
								'name'          => 'module_image',
								'type'          => 'media',
								'label'         => __( 'Close Image', 'convertpro' ),
								'default_value' => '0|admin/img/close1.png',
								'map'           => array(
									'attr'   => 'src',
									'target' => '.cp-target',
								),
								'source'        => 'upload_img',
								'dependency'    => array(
									'name'     => 'close_image_type',
									'operator' => '==',
									'value'    => 'upload',
								),
							),
							array(
								'id'               => 'module_radio_image',
								'name'             => 'module_radio_image',
								'type'             => 'radio_image',
								'label'            => __( 'Module Image', 'convertpro' ),
								'map'              => array(
									'attr'   => 'src',
									'target' => '.cp-target',
								),
								'map_style'        => array(
									'parameter' => 'radio-image',
								),
								'default_value'    => 'admin/img/close1.png',
								'source'           => 'predefined',
								'default_alt_text' => '',
								'options'          => array(
									'1' => 'admin/img/close1.png',
									'2' => 'admin/img/close2.png',
									'3' => 'admin/img/close3.png',
									'4' => 'admin/img/close4.png',
									'5' => 'admin/img/close5.png',
									'6' => 'admin/img/close6.png',
								),
								'dependency'       => array(
									'name'     => 'close_image_type',
									'operator' => '==',
									'value'    => 'predefined',
								),
							),
						),
					),
					array(
						'title'  => 'Advanced',
						'params' => array(
							array(
								'id'             => 'width',
								'name'           => 'width',
								'label'          => __( 'Width', 'convertpro' ),
								'type'           => 'number',
								'default_value'  => 32,
								'min'            => 0,
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
								'label'          => __( 'Height', 'convertpro' ),
								'default_value'  => 32,
								'min'            => 0,
								'max'            => 800,
								'step'           => 1,
								'suffix'         => 'px',
								'map_style'      => array(
									'parameter' => 'height',
									'unit'      => 'px',
								),
								'show_on_mobile' => true,
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
								'default_value'  => true,
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
								'id'             => 'field_action',
								'name'           => 'field_action',
								'type'           => 'hidden',
								'label'          => 'Field Action',
								'default_value'  => 'close',
								'hide_on_mobile' => true,
								'options'        => array(
									'none'               => 'None',
									'submit'             => 'Submit',
									'submit_n_goto_step' => 'Submit & Go to Step',
									'submit_n_close'     => 'Submit & Close',
									'goto_url'           => 'Go to URL',
									'goto_step'          => 'Go to Step',
									'close'              => 'Close',
								),
								'map'            => array(
									'attr'   => 'button-type',
									'target' => '.cp-target',
								),
							),
						),
					),
				),
				'panel'        => 'Elements',
				'section'      => 'Design',
				'section_icon' => 'dashicons dashicons-screenoptions',
			),

			// Close Link.
			array(
				'type'         => 'cp_close_text',
				'class'        => '',
				'name'         => 'cp_close_text',
				'opts'         => array(
					'title'          => __( 'Close Link', 'convertpro' ),
					'value'          => '',
					'description'    => '',
					'tags'           => 'close, close link',
					'resize'         => true,
					'show_on_mobile' => false,
				),
				'sections'     => array(
					array(
						'title'  => 'Text',
						'params' => array(
							array(
								'id'            => 'close_type',
								'name'          => 'close_type',
								'type'          => 'hidden',
								'label'         => __( 'Type', 'convertpro' ),
								'default_value' => 'cp-close-type-text',
								'options'       => array(
									'cp-close-type-text'  => __( 'Text', 'convertpro' ),
									'cp-close-type-image' => __( 'Image', 'convertpro' ),
								),
								'map_style'     => array(
									'parameter' => 'class',
									'unit'      => 'cp-close-type-text cp-close-type-image',
								),
							),
							array(
								'id'            => 'close_link_title',
								'name'          => 'close_link_title',
								'type'          => 'text',
								'label'         => __( 'Enter Text', 'convertpro' ),
								'default_value' => __( 'Close', 'convertpro' ),
								'suffix'        => '',
								'map_style'     => array(
									'parameter' => 'inner_html',
									'target'    => '.cp-target .cp-close-link',
								),
							),
							array(
								'id'            => 'close_font_family',
								'name'          => 'close_font_family',
								'type'          => 'font',
								'label'         => 'Font Family',
								'default_value' => 'inherit:inherit',
								'map_style'     => array(
									'parameter' => 'font-family',
									'target'    => '.cp-target .cp-close-link',
								),
								'dependency'    => array(
									'name'     => 'close_type',
									'operator' => '==',
									'value'    => 'cp-close-type-text',
								),
							),
							array(
								'id'             => 'close_title_size',
								'name'           => 'close_title_size',
								'type'           => 'number',
								'suffix'         => 'px',
								'label'          => __( 'Font Size', 'convertpro' ),
								'default_value'  => '16px',
								'map_style'      => array(
									'parameter' => 'font-size',
									'target'    => '.cp-target .cp-close-link',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'             => 'close_line_height',
								'name'           => 'close_line_height',
								'type'           => 'slider',
								'suffix'         => 'em',
								'label'          => __( 'Line Height', 'convertpro' ),
								'min'            => 1,
								'max'            => 3,
								'step'           => 0.01,
								'default_value'  => 1.5,
								'map_style'      => array(
									'parameter' => 'line-height',
									'target'    => '.cp-target .cp-close-link',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'             => 'close_letter_spacing',
								'name'           => 'close_letter_spacing',
								'type'           => 'slider',
								'suffix'         => 'px',
								'label'          => __( 'Letter Spacing', 'convertpro' ),
								'min'            => 0,
								'max'            => 20,
								'step'           => 0.01,
								'default_value'  => 0,
								'map_style'      => array(
									'parameter' => 'letter-spacing',
									'target'    => '.cp-target .cp-close-link',
									'unit'      => 'px',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'             => 'close_text_align',
								'name'           => 'close_text_align',
								'type'           => 'text_align',
								'label'          => __( 'Text Alignment', 'convertpro' ),
								'default_value'  => 'center',
								'options'        => array(
									'center'  => __( 'center', 'convertpro' ),
									'left'    => __( 'left', 'convertpro' ),
									'right'   => __( 'right', 'convertpro' ),
									'justify' => __( 'justify', 'convertpro' ),
								),
								'map_style'      => array(
									'parameter' => 'text-align',
									'target'    => '.cp-target',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'            => 'close_link_color',
								'name'          => 'close_link_color',
								'type'          => 'colorpicker',
								'label'         => __( 'Color', 'convertpro' ),
								'default_value' => '#000',
								'map_style'     => array(
									'parameter' => 'color',
									'target'    => '.cp-target .cp-close-link',
								),
							),
							array(
								'id'            => 'close_back_color',
								'name'          => 'close_back_color',
								'type'          => 'colorpicker',
								'label'         => __( 'Background Color', 'convertpro' ),
								'default_value' => '',
								'map_style'     => array(
									'parameter' => 'background',
									'target'    => '.cp-target',
								),
							),
						),
					),
					array(
						'title'  => 'Advanced',
						'params' => array(
							array(
								'id'             => 'width',
								'name'           => 'width',
								'label'          => __( 'Width', 'convertpro' ),
								'type'           => 'number',
								'default_value'  => 60,
								'min'            => 0,
								'max'            => 800,
								'step'           => 1,
								'suffix'         => 'px',
								'map_style'      => array(
									'parameter' => 'width',
									'target'    => '.cp-target',
									'unit'      => 'px',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'             => 'height',
								'name'           => 'height',
								'label'          => __( 'Height', 'convertpro' ),
								'type'           => 'number',
								'default_value'  => 25,
								'min'            => 0,
								'max'            => 800,
								'step'           => 1,
								'suffix'         => 'px',
								'map_style'      => array(
									'parameter' => 'height',
									'target'    => '.cp-target',
									'unit'      => 'px',
								),
								'show_on_mobile' => true,
							),
							array(
								'id'    => 'label_position',
								'name'  => 'label_position',
								'type'  => 'hidden',
								'label' => __( 'Position', 'convertpro' ),
							),
							array(
								'id'             => 'respective_to',
								'name'           => 'respective_to',
								'type'           => 'switch',
								'default_value'  => true,
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
								'id'             => 'field_action',
								'name'           => 'field_action',
								'type'           => 'hidden',
								'label'          => 'Field Action',
								'default_value'  => 'close',
								'hide_on_mobile' => true,
								'options'        => array(
									'none'               => __( 'None', 'convertpro' ),
									'submit'             => __( 'Submit', 'convertpro' ),
									'submit_n_goto_step' => __( 'Submit & Go to Step', 'convertpro' ),
									'submit_n_close'     => __( 'Submit & Close', 'convertpro' ),
									'goto_url'           => __( 'Go to URL', 'convertpro' ),
									'goto_step'          => __( 'Go to Step', 'convertpro' ),
									'close'              => __( 'Close', 'convertpro' ),
								),
								'map'            => array(
									'attr'   => 'button-type',
									'target' => '.cp-target',
								),
							),
						),
					),
				),
				'panel'        => 'Elements',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-elements',
			),

			// Video.
			parent::$cp_video_options,

			// Countdown Element.
			parent::$cp_countdown_opts,

			// Form - Email Field.
			parent::$cp_form_email_opts,

			// Form - Name Field.
			parent::$cp_form_name_opts,

			// Form - Phone Field.
			parent::$cp_form_phone_opts,

			// Form - Dropdown Field.
			parent::$cp_form_dropdown_opts,

			// Form - Textarea Field.
			parent::$cp_form_textarea_opts,

			// Form - Radio Button Field.
			parent::$cp_form_radio_opts,

			// Form - Checkbox Field.
			parent::$cp_form_checkbox_opts,

			// Form - Hidden Input Field.
			parent::$cp_form_hiddeninput_opts,

			// Form - Typography Accordion.
			array(
				'type'         => 'font',
				'class'        => '',
				'name'         => 'form_field_font',
				'opts'         => array(
					'title'       => __( 'Font Family', 'convertpro' ),
					'value'       => 'inherit:inherit',
					'description' => '',
					'tags'        => 'field font,font family, font weight',
					'map_style'   => array(
						'parameter' => 'font-family',
					),
					'global'      => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'has_params'   => false,
				'category'     => 'Typography',
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'form_field_font_size',
				'opts'           => array(
					'title'       => __( 'Font Size', 'convertpro' ),
					'value'       => 13,
					'min'         => 1,
					'max'         => 72,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => '',
					'tags'        => 'field font,font,size,font size',
					'map_style'   => array(
						'parameter' => 'font-size',
						'unit'      => 'px',
					),
					'global'      => false,
				),
				'panel'          => 'Form',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-field',
				'has_params'     => false,
				'category'       => 'Typography',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'numberfield',
				'class'          => '',
				'name'           => 'form_field_letter_spacing',
				'opts'           => array(
					'title'     => __( 'Letter Spacing', 'convertpro' ),
					'value'     => '0',
					'suffix'    => 'px,em',
					'tags'      => 'letter,spacing,letter spacing,field font',
					'map_style' => array(
						'parameter' => 'letter-spacing',
					),
					'global'    => false,
				),
				'panel'          => 'Form',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-field',
				'has_params'     => false,
				'category'       => 'Typography',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'text-align',
				'class'          => '',
				'name'           => 'form_field_text_align',
				'opts'           => array(
					'title'     => __( 'Text Alignment', 'convertpro' ),
					'value'     => 'left',
					'suffix'    => 'px,em',
					'options'   => array(
						'center'  => __( 'center', 'convertpro' ),
						'left'    => __( 'left', 'convertpro' ),
						'right'   => __( 'right', 'convertpro' ),
						'justify' => __( 'justify', 'convertpro' ),
					),
					'tags'      => 'text,align,text alignment,field font',
					'map_style' => array(
						'parameter' => 'text-align',
					),
					'global'    => false,
				),
				'panel'          => 'Form',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-field',
				'has_params'     => false,
				'category'       => 'Typography',
				'show_on_mobile' => true,
			),

			// Form - Advanced Accordion.
			array(
				'type'         => 'label',
				'class'        => '',
				'name'         => 'form_field_color_label',
				'opts'         => array(
					'title'  => '',
					'label'  => __( 'Color', 'convertpro' ),
					'global' => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Styling',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'form_field_color',
				'opts'         => array(
					'title'       => __( 'Text Color', 'convertpro' ),
					'value'       => '#666',
					'description' => '',
					'tags'        => 'field color,text color',
					'map_style'   => array(
						'parameter' => 'color',
					),
					'global'      => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'has_params'   => false,
				'category'     => 'Styling',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'form_field_placeholder_color',
				'opts'         => array(
					'title'       => __( 'Placeholder Color', 'convertpro' ),
					'value'       => '#666',
					'description' => '',
					'tags'        => 'field color,text color',
					'map_style'   => array(
						'parameter' => 'color',
						'target'    => 'placeholder',
					),
					'global'      => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'has_params'   => false,
				'category'     => 'Styling',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'form_field_bg_color',
				'opts'         => array(
					'title'       => __( 'Background Color', 'convertpro' ),
					'value'       => '#fff',
					'description' => '',
					'tags'        => 'field color,background color',
					'map_style'   => array(
						'parameter' => 'background-color',
					),
					'global'      => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'has_params'   => false,
				'category'     => 'Styling',
			),
			array(
				'type'         => 'label',
				'class'        => '',
				'name'         => 'form_field_border_label',
				'opts'         => array(
					'title'  => '',
					'label'  => __( 'Border', 'convertpro' ),
					'global' => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'dropdown',
				'class'        => '',
				'name'         => 'form_field_border_style',
				'opts'         => array(
					'title'     => __( 'Border Style', 'convertpro' ),
					'value'     => 'solid',
					'options'   => cp_Framework::$border_options,
					'tags'      => 'field border,border style',
					'map_style' => array(
						'parameter' => 'border-style',
					),
					'global'    => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'has_params'   => false,
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'multiinput',
				'class'        => '',
				'name'         => 'form_field_border_width',
				'opts'         => array(
					'title'       => __( 'Border Width', 'convertpro' ),
					'value'       => '1|1|1|1|px',
					'min'         => 0,
					'max'         => 50,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => '',
					'tags'        => 'field border,border width',
					'map_style'   => array(
						'parameter' => 'border-width',
					),
					'global'      => false,
				),
				'dependency'   => array(
					'name'     => 'form_field_border_style',
					'operator' => '!=',
					'value'    => 'none',
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'multiinput',
				'class'        => '',
				'name'         => 'form_field_border_radius',
				'opts'         => array(
					'title'       => __( 'Border Radius', 'convertpro' ),
					'value'       => '1|1|1|1|px',
					'min'         => 0,
					'max'         => 250,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => '',
					'tags'        => 'field border,border radius',
					'map_style'   => array(
						'parameter' => 'border-radius',
					),
					'global'      => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'form_field_border_color',
				'opts'         => array(
					'title'     => __( 'Border Color', 'convertpro' ),
					'value'     => '#bbb',
					'tags'      => 'field border,border color',
					'map_style' => array(
						'parameter' => 'border-color',
					),
					'global'    => false,
				),
				'dependency'   => array(
					'name'     => 'form_field_border_style',
					'operator' => '!=',
					'value'    => 'none',
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'colorpicker',
				'class'        => '',
				'name'         => 'form_field_active_border_color',
				'opts'         => array(
					'title'       => __( 'Active Field Border Color', 'convertpro' ),
					'value'       => '#666',
					'description' => '',
					'tags'        => 'field border,active field border color',
					'map_style'   => array(
						'parameter' => 'active-border-color',
					),
					'global'      => false,
				),
				'dependency'   => array(
					'name'     => 'form_field_border_style',
					'operator' => '!=',
					'value'    => 'none',
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'label',
				'class'        => '',
				'name'         => 'form_field_box_shadow_label',
				'opts'         => array(
					'title'  => '',
					'label'  => __( 'Box Shadow', 'convertpro' ),
					'global' => false,
				),
				'panel'        => 'Form',
				'section'      => 'Design',
				'has_params'   => false,
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'         => 'box_shadow',
				'class'        => '',
				'name'         => 'form_field_box_shadow',
				'opts'         => array(
					'title'     => '',
					'value'     => 'type:none|horizontal:0|vertical:0|blur:5|spread:0|color:rgba(86,86,131,0.6)',
					'tags'      => 'field box shadow,shadow effect,shadow color,blur radius,spread radius,horizontal length,vertical length',
					'map_style' => array(
						'parameter' => 'box-shadow',
					),
					'global'    => false,
				),
				'panel'        => 'Form',
				'has_params'   => false,
				'section'      => 'Design',
				'section_icon' => 'cp-icon-field',
				'category'     => 'Advanced',
			),
			array(
				'type'           => 'label',
				'class'          => '',
				'name'           => 'form_field_padding_label',
				'opts'           => array(
					'title'  => '',
					'label'  => __( 'Padding', 'convertpro' ),
					'global' => false,
				),
				'panel'          => 'Form',
				'section'        => 'Design',
				'has_params'     => false,
				'section_icon'   => 'cp-icon-field',
				'category'       => 'Advanced',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'multiinput',
				'class'          => '',
				'name'           => 'form_field_padding',
				'opts'           => array(
					'title'       => __( 'Padding', 'convertpro' ),
					'value'       => '0|10|0|10|px',
					'min'         => 0,
					'max'         => 50,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => '',
					'tags'        => 'field padding,padding',
					'map_style'   => array(
						'parameter' => 'padding',
					),
					'global'      => false,
				),
				'panel'          => 'Form',
				'section'        => 'Design',
				'has_params'     => false,
				'section_icon'   => 'cp-icon-field',
				'category'       => 'Advanced',
				'show_on_mobile' => true,
			),

			// Button - Flat Button.
			parent::$cp_button_flatbtn_opts,

			// Button - Gradient Button.
			parent::$cp_button_gradientbtn_opts,

			// Shapes.
			parent::$cp_shapes_opts,
		);

		$design_field_options = apply_filters( 'cp_update_design_options', $design_field_options );
		$panel_design_options = array_merge( $design_field_options, $panel_design_options );

		return $panel_design_options;

	}

	/**
	 * Function Name: remove_btn_field_actions.
	 * Function Description: Modifies dropdown option for button action
	 *
	 * @param array $fields array parameter.
	 */
	function remove_field_actions( $fields ) {

		foreach ( $fields['sections'] as $section_key => $section ) {

			if ( 'Action' == $section['title'] ) {

				$params = $section['params'];

				foreach ( $params as $param_key => $param ) {

					if ( 'field_action' == $param['id'] ) {
						unset( $param['options']['submit_n_goto_step'] );
						unset( $param['options']['goto_step'] );
					}

					$params[ $param_key ] = $param;
				}

				$section['params']                  = $params;
				$fields['sections'][ $section_key ] = $section;
			}
		}

		return $fields;
	}
}

new CP_Welcome_Mat;
