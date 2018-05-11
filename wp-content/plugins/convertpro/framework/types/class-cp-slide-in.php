<?php
/**
 * Defined popup types and settings.
 *
 * @package convertpro
 */

/**
 * Class CP_Slide_In.
 */
class CP_Slide_In extends cp_Framework {

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
	public static $slug = 'slide_in';

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
			'title'       => __( 'Slide-in', 'convertpro' ),
			'description' => __( 'A popup or an opt-in form that slides in politely from the corners/sides of the screen.', 'convertpro' ),
		);
		parent::cp_add_popup_type( self::$slug, self::$settings );
	}

	/**
	 * Function Name: get_options.
	 * Function Description: get options.
	 */
	function get_options() {
		$options        = parent::$options;
		$design_options = $this->get_design_options();

		$design_options = apply_filters( 'cp_after_design_fields', $design_options, self::$slug );

		$options['options'] = array_merge( $design_options, $options['options'] );

		// This will remove Display Inline & Shortcode options.
		$options = parent::cp_remove_configuration_options(
			array(
				'enable_display_inline',
				'inline_position',
				'inline_shortcode',
			),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_text',
				'cp_number',
				'cp_dropdown',
				'cp_email',
				'cp_textarea',
				'cp_close_link',
				'cp_custom_html',
			),
			array(
				'field_action',
				'btn_url',
				'btn_url_target',
				'btn_url_follow',
				'submit_message_text_color',
				'submit_message_bg_color',
				'submit_message_layout',
				'btn_step',
				'submit_message',
				'label_box_shadow',
				'count_as_conversion',
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
				'cp_shape',
				'cp_dual_color_shape',
			),
			array( 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'back_color', 'back_color_hover', 'letter_spacing', 'btn_text_align', 'line_height', 'label_border', 'border_style', 'border_radius', 'border_color', 'border_hover_color', 'border_width', 'field_padding', 'get_parameter' ),
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
				'cp_loader_button',
				'cp_gradient_button',
			),
			array( 'back_color', 'back_color_hover', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'btn_text_align' ),
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
				'cp_custom_html',
			),
			array( 'text_hover_color', 'back_color', 'back_color_hover', 'title', 'label_action', 'text_hover_color', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'submit_message_font_size', 'field_padding', 'get_parameter' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_close_text',
				'cp_close_image',
			),
			array( 'back_color', 'back_color_hover', 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'label_layout_clink', 'btn_text_align', 'letter_spacing', 'line_height', 'field_action', 'submit_message', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_padding', 'get_parameter', 'count_as_conversion' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_image',
			),
			array( 'title', 'font_family', 'font_size', 'text_color', 'text_hover_color', 'back_color', 'back_color_hover', 'letter_spacing', 'btn_text_align', 'line_height', 'field_padding', 'get_parameter' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_countdown',
			),
			array( 'back_color', 'back_color_hover', 'title', 'label_layout_clink', 'btn_text_align', 'text_hover_color', 'failed_message', 'submit_message_text_color', 'submit_message_bg_color', 'submit_message_layout', 'line_height', 'btn_url', 'btn_url_target', 'btn_url_follow', 'btn_step', 'field_action', 'submit_message', 'label_box_shadow', 'submit_message_font_size', 'count_as_conversion', 'get_parameter', 'field_padding', 'border_radius', 'border_hover_color', 'border_width', 'border_color', 'border_style', 'label_border' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_button',

			),
			array( 'line_height', 'back_color', 'back_color_hover', 'text_color', 'text_hover_color' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(
				'cp_gradient_button',
			),
			array( 'letter_spacing', 'line_height' ),
			$options
		);

		$options = parent::cp_remove_field_options(
			array(),
			array( 'respective_to' ),
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

		$panel_design_options = array(
			array(
				'type'           => 'number',
				'class'          => '',
				'name'           => 'panel_width',
				'opts'           => array(
					'title'       => __( 'Width', 'convertpro' ),
					'value'       => array( 350, 250 ),
					'min'         => 100,
					'max'         => array( 800, 360 ),
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
					'title'       => __( 'Height', 'convertpro' ),
					'value'       => array( 400, 300 ),
					'min'         => 100,
					'max'         => array( 800, 600 ),
					'step'        => 1,
					'suffix'      => 'px',
					'reset'       => 'true',
					'description' => __( 'Height for Panel', 'convertpro' ),
					'tags'        => 'size,height',
					'map_style'   => array(
						'parameter' => 'height',
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
					'value'       => '',
					'description' => '',
					'tags'        => 'background,background overlay color',
					'map_style'   => array(
						'parameter' => 'panel_img_overlay_color',
					),
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
			),
			array(
				'type'         => 'dropdown',
				'class'        => '',
				'name'         => 'panel_entry_animation',
				'opts'         => array(
					'title'       => __( 'Entry Animation Effect', 'convertpro' ),
					'value'       => 'cp-fadeIn',
					'description' => '',
					'options'     => apply_filters( 'cp_entry_animations', array() ),
					'tags'        => 'animation,entry animation effect,entry,effect',
					'map_style'   => array(
						'parameter' => 'entry_animation',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Animation',
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
					'map_style'      => array(
						'parameter' => 'mobile-breakpoint',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Mobile Breakpoint',
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
				'type'         => 'dropdown',
				'class'        => '',
				'name'         => 'panel_position',
				'opts'         => array(
					'title'       => __( 'Slide In Position', 'convertpro' ),
					'value'       => 'bottom-right',
					'description' => '',
					'options'     => array(
						'bottom-left'   => __( 'Bottom Left', 'convertpro' ),
						'bottom-right'  => __( 'Bottom Right', 'convertpro' ),
						'bottom-center' => __( 'Bottom Center', 'convertpro' ),
						'center-left'   => __( 'Center Left', 'convertpro' ),
						'center-right'  => __( 'Center Right', 'convertpro' ),
					),
					'tags'        => 'position',
					'map_style'   => array(
						'parameter' => 'position',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Position',
			),
			array(
				'type'           => 'switch',
				'class'          => '',
				'name'           => 'panel_toggle',
				'opts'           => array(
					'title'       => __( 'Slide In Toggle', 'convertpro' ),
					'value'       => false,
					'on'          => __( 'YES', 'convertpro' ),
					'off'         => __( 'NO', 'convertpro' ),
					'description' => __( 'Enable toggle at its position.', 'convertpro' ),
					'tags'        => 'toggle',
					'map_style'   => array(
						'parameter' => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'numberfield',
				'class'          => '',
				'name'           => 'toggle_font_size',
				'opts'           => array(
					'title'       => __( 'Font Size', 'convertpro' ),
					'value'       => '16px',
					'suffix'      => 'px',
					'description' => '',
					'tags'        => 'color,text',
					'map_style'   => array(
						'parameter' => 'font-size',
						'target'    => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'dependency'     => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'toggle_text_color',
				'opts'           => array(
					'title'       => __( 'Text Color', 'convertpro' ),
					'value'       => '#FFFFFF',
					'description' => '',
					'tags'        => 'color,text',
					'map_style'   => array(
						'parameter' => 'color',
						'target'    => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'dependency'     => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'colorpicker',
				'class'          => '',
				'name'           => 'toggle_bg_color',
				'opts'           => array(
					'title'       => __( 'Background Color', 'convertpro' ),
					'value'       => '#000000',
					'description' => '',
					'tags'        => 'color,background',
					'map_style'   => array(
						'parameter' => 'background-color',
						'target'    => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'dependency'     => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'show_on_mobile' => true,
			),
			array(
				'type'         => 'dropdown',
				'class'        => '',
				'name'         => 'toggle_type',
				'opts'         => array(
					'title'       => __( 'Type of Toggle', 'convertpro' ),
					'value'       => 'hide_on_click',
					'description' => __( 'Select the type of toggle', 'convertpro' ),
					'options'     => array(
						'sticky'        => __( 'Sticky', 'convertpro' ),
						'hide_on_click' => __( 'Hide on click', 'convertpro' ),
					),
					'tags'        => 'toggle type',
					'map_style'   => array(
						'parameter' => 'toggle_type',
						'target'    => 'toggle',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-embed',
				'dependency'   => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'category'     => 'Toggle',
			),
			array(
				'type'         => 'textfield',
				'class'        => '',
				'name'         => 'toggle_text',
				'opts'         => array(
					'title'       => __( 'Enter Toggle Text', 'convertpro' ),
					'value'       => 'Click Here',
					'description' => __( 'Enter text which will appear on toggle button.', 'convertpro' ),
					'tags'        => 'toggle text',
					'map_style'   => array(
						'parameter' => 'toggle_text',
						'target'    => 'toggle',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-embed',
				'dependency'   => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'category'     => 'Toggle',
			),
			array(
				'type'         => 'switch',
				'class'        => '',
				'name'         => 'toggle_minimizer',
				'opts'         => array(
					'title'       => __( 'Display Icon To Minimize Toggle', 'convertpro' ),
					'value'       => false,
					'on'          => __( 'YES', 'convertpro' ),
					'off'         => __( 'NO', 'convertpro' ),
					'description' => __( 'Enable icon to mimimize toggle.', 'convertpro' ),
					'tags'        => 'toggle,minimize',
					'map_style'   => array(
						'parameter' => 'toggle_minimizer',
						'unit'      => 'px',
						'target'    => 'toggle',
					),
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Toggle',
				'dependency'   => array(
					'name'     => 'toggle_type',
					'operator' => '==',
					'value'    => 'sticky',
				),
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'toggle_width',
				'opts'           => array(
					'title'       => __( 'Width', 'convertpro' ),
					'value'       => 250,
					'min'         => 100,
					'max'         => 800,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => __( 'Width for Toggle', 'convertpro' ),
					'tags'        => 'toggle,width',
					'map_style'   => array(
						'parameter' => 'width',
						'unit'      => 'px',
						'target'    => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'dependency'     => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'show_on_mobile' => true,
			),
			array(
				'type'           => 'slider',
				'class'          => '',
				'name'           => 'toggle_height',
				'opts'           => array(
					'title'       => __( 'Height', 'convertpro' ),
					'value'       => 50,
					'min'         => 30,
					'max'         => 150,
					'step'        => 1,
					'suffix'      => 'px',
					'description' => __( 'Height for toggle button', 'convertpro' ),
					'tags'        => 'toggle,height',
					'map_style'   => array(
						'parameter' => 'height',
						'unit'      => 'px',
						'target'    => 'toggle',
					),
				),
				'panel'          => 'Panel',
				'section'        => 'Design',
				'section_icon'   => 'cp-icon-panel',
				'category'       => 'Toggle',
				'dependency'     => array(
					'name'     => 'panel_toggle',
					'operator' => '==',
					'value'    => 'true',
				),
				'show_on_mobile' => true,
			),
		);

		/*** Array contains Advanced design field options */
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
			parent::$close_image_opts,

			// Close Link.
			parent::$close_link_opts,

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

new CP_Slide_In;
