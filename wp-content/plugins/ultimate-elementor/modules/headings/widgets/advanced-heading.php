<?php
/**
 * UAEL Advanced Heading.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Headings\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Advanced_Heading.
 */
class Advanced_Heading extends Common_Widget {

	/**
	 * Retrieve Advanced Heading Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Advanced_Heading' );
	}

	/**
	 * Retrieve Advanced Heading Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Advanced_Heading' );
	}

	/**
	 * Retrieve Advanced Heading Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Advanced_Heading' );
	}

	/**
	 * Register Advanced Heading controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_separator_content_controls();
		$this->register_style_content_controls();
		$this->register_imgicon_content_controls();
		$this->register_typo_content_controls();
	}

	/**
	 * Register Advanced Heading General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => __( 'General', 'uael' ),
			]
		);
		$this->add_control(
			'heading_title',
			[
				'label'   => __( 'Heading', 'uael' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => '2',
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Design is a funny word', 'uael' ),
			]
		);
		$this->add_control(
			'heading_link',
			[
				'label'       => __( 'Link', 'uael' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'uael' ),
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
			]
		);
		$this->add_control(
			'heading_description',
			[
				'label'     => __( 'Description', 'uael' ),
				'type'      => Controls_Manager::TEXTAREA,
				'dynamic'   => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Advanced Heading Separator Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_separator_content_controls() {
		$this->start_controls_section(
			'section_separator_field',
			[
				'label' => __( 'Separator', 'uael' ),
			]
		);
		$this->add_control(
			'heading_separator_style',
			[
				'label'       => __( 'Style', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => [
					'none'       => __( 'None', 'uael' ),
					'line'       => __( 'Line', 'uael' ),
					'line_icon'  => __( 'Line With Icon', 'uael' ),
					'line_image' => __( 'Line With Image', 'uael' ),
					'line_text'  => __( 'Line With Text', 'uael' ),
				],
			]
		);
		$this->add_control(
			'heading_separator_position',
			[
				'label'       => __( 'Separator Position', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'label_block' => false,
				'options'     => [
					'center' => __( 'Between Heading & Description', 'uael' ),
					'top'    => __( 'Top', 'uael' ),
					'bottom' => __( 'Bottom', 'uael' ),
				],
				'condition'   => [
					'heading_separator_style!' => 'none',
				],
			]
		);

		/* Separator line with Icon */
		$this->add_control(
			'heading_icon_fields',
			[
				'label'     => __( 'Icon Basics', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_separator_style' => 'line_icon',
				],
			]
		);
		$this->add_control(
			'heading_icon',
			[
				'label'     => __( 'Select Icon', 'uael' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-star',
				'condition' => [
					'heading_separator_style' => 'line_icon',
				],
			]
		);
		$this->add_responsive_control(
			'heading_icon_size',
			[
				'label'      => __( 'Size', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 30,
					'unit' => 'px',
				],
				'condition'  => [
					'heading_separator_style' => 'line_icon',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-icon-wrap .uael-icon i' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .uael-icon-wrap .uael-icon' => ' height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		/* Separator line with Image */
		$this->add_control(
			'heading_image_fields',
			[
				'label'     => __( 'Image Basics', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_separator_style' => 'line_image',
				],
			]
		);
		$this->add_control(
			'heading_image_type',
			[
				'label'       => __( 'Photo Source', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'media',
				'label_block' => false,
				'options'     => [
					'media' => __( 'Media Library', 'uael' ),
					'url'   => __( 'URL', 'uael' ),
				],
				'condition'   => [
					'heading_separator_style' => 'line_image',
				],
			]
		);
		$this->add_control(
			'heading_image',
			[
				'label'     => __( 'Photo', 'uael' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'heading_separator_style' => 'line_image',
					'heading_image_type'      => 'media',
				],
			]
		);
		$this->add_control(
			'heading_image_link',
			[
				'label'         => __( 'Photo URL', 'uael' ),
				'type'          => Controls_Manager::URL,
				'default'       => [
					'url' => '',
				],
				'show_external' => false, // Show the 'open in new tab' button.
				'condition'     => [
					'heading_separator_style' => 'line_image',
					'heading_image_type'      => 'url',
				],
			]
		);
		$this->add_responsive_control(
			'heading_image_size',
			[
				'label'      => __( 'Size', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 2000,
					],
				],
				'default'    => [
					'size' => 50,
					'unit' => 'px',
				],
				'condition'  => [
					'heading_separator_style' => 'line_image',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-image .uael-photo-img'   => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		/* Separator line with text */
		$this->add_control(
			'heading_line_text_fields',
			[
				'label'     => __( 'Text', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_separator_style' => 'line_text',
				],
			]
		);
		$this->add_control(
			'heading_line_text',
			[
				'label'     => __( 'Enter Text', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Ultimate', 'uael' ),
				'condition' => [
					'heading_separator_style' => 'line_text',
				],
				'dynamic'   => [
					'active' => true,
				],
				'selector'  => '{{WRAPPER}} .uael-divider-text',
			]
		);

		$this->add_responsive_control(
			'heading_icon_position',
			[
				'label'          => __( 'Position', 'uael' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%' ],
				'range'          => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'        => [
					'size' => 50,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'condition'      => [
					'heading_separator_style' => [ 'line_icon', 'line_image', 'line_text' ],
				],
				'selectors'      => [
					'{{WRAPPER}} .uael-side-left'  => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-side-right' => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_separator_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => [
					'heading_separator_style' => 'line_text',
				],
				'selector'  => '{{WRAPPER}} .uael-divider-text',
			]
		);

		$this->add_responsive_control(
			'heading_icon_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'bottom'   => '0',
					'left'     => '10',
					'right'    => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'condition'  => [
					'heading_separator_style' => [ 'line_icon', 'line_image', 'line_text' ],
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-divider-content' => 'Padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);
		$this->add_control(
			'heading_line',
			[
				'label'     => __( 'Line Style', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_separator_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_line_style',
			[
				'label'       => __( 'Style', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'solid'  => __( 'Solid', 'uael' ),
					'dashed' => __( 'Dashed', 'uael' ),
					'dotted' => __( 'Dotted', 'uael' ),
					'double' => __( 'Double', 'uael' ),
				],
				'condition'   => [
					'heading_separator_style!' => 'none',
				],
				'selectors'   => [
					'{{WRAPPER}} .uael-separator, {{WRAPPER}} .uael-separator-line > span' => 'border-top-style: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_line_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'condition' => [
					'heading_separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-separator, {{WRAPPER}} .uael-separator-line > span, {{WRAPPER}} .uael-divider-text' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_line_thickness',
			[
				'label'      => __( 'Thickness', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 2,
					'unit' => 'px',
				],
				'condition'  => [
					'heading_separator_style!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-separator, {{WRAPPER}} .uael-separator-line > span ' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_line_width',
			[
				'label'          => __( 'Width', 'uael' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'        => [
					'size' => 20,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'label_block'    => true,
				'condition'      => [
					'heading_separator_style!' => 'none',
				],
				'selectors'      => [
					'{{WRAPPER}} .uael-separator, {{WRAPPER}} .uael-separator-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Advanced Heading Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label'        => __( 'Overall Alignment', 'uael' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors'    => [
					'{{WRAPPER}} .uael-heading,{{WRAPPER}} .uael-subheading, {{WRAPPER}} .uael-subheading *, {{WRAPPER}} .uael-separator-parent' => 'text-align: {{VALUE}};',
				],
				'prefix_class' => 'uael%s-heading-align-',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Advanced Heading Image/Icon Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_imgicon_content_controls() {
		$this->start_controls_section(
			'section_imgicon_style',
			[
				'label'     => __( 'Icon/Image Style', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'heading_separator_style' => [ 'line_icon', 'line_image' ],
				],
			]
		);
		$this->add_control(
			'heading_imgicon_style_options',
			[
				'label'       => __( 'Style', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'simple',
				'label_block' => false,
				'options'     => [
					'simple' => __( 'Simple', 'uael' ),
					'custom' => __( 'Design your own', 'uael' ),
				],
				'condition'   => [
					'heading_separator_style' => [ 'line_icon', 'line_image' ],
				],
			]
		);
		$this->add_control(
			'headings_icon_color',
			[
				'label'     => __( 'Icon Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'condition' => [
					'heading_imgicon_style_options' => 'simple',
					'heading_separator_style'       => 'line_icon',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-icon-wrap .uael-icon i'  => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'headings_icon_hover_color',
			[
				'label'     => __( 'Icon Hover Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'heading_imgicon_style_options' => 'simple',
					'heading_separator_style'       => 'line_icon',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover i'  => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'headings_icon_animation',
			[
				'label'     => __( 'Hover Animation', 'uael' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'heading_imgicon_style_options' => 'simple',
					'heading_separator_style'       => [ 'line_icon', 'line_image' ],
				],
			]
		);

		$this->start_controls_tabs( 'heading_imgicon_style' );

			$this->start_controls_tab(
				'heading_imgicon_normal',
				[
					'label'     => __( 'Normal', 'uael' ),
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
				]
			);

			$this->add_control(
				'heading_icon_color',
				[
					'label'     => __( 'Icon Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => 'line_icon',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon i'  => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'heading_icon_bgcolor',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'heading_icon_bg_size',
				[
					'label'      => __( 'Background Size', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'default'    => [
						'size' => '0',
						'unit' => 'px',
					],
					'condition'  => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content' => 'padding: {{SIZE}}{{UNIT}}; display:inline-block; box-sizing:content-box;',
					],
					'separator'  => 'before',
				]
			);

			$this->add_control(
				'heading_icon_border',
				[
					'label'       => __( 'Border Style', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'none',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'uael' ),
						'solid'  => __( 'Solid', 'uael' ),
						'double' => __( 'Double', 'uael' ),
						'dotted' => __( 'Dotted', 'uael' ),
						'dashed' => __( 'Dashed', 'uael' ),
					],
					'condition'   => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
					'selectors'   => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content' => 'border-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'heading_icon_border_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
						'heading_icon_border!'          => 'none',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'heading_icon_border_size',
				[
					'label'      => __( 'Border Width', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '1',
						'bottom' => '1',
						'left'   => '1',
						'right'  => '1',
						'unit'   => 'px',
					],
					'condition'  => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
						'heading_icon_border!'          => 'none',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
					],
				]
			);
			$this->add_control(
				'heading_icon_border_radius',
				[
					'label'      => __( 'Rounded Corners', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 1000,
						],
					],
					'default'    => [
						'size' => 20,
						'unit' => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content'   => 'border-radius: {{SIZE}}{{UNIT}};',
					],
					'condition'  => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
				]
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'heading_imgicon_hover',
				[
					'label'     => __( 'Hover', 'uael' ),
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
				]
			);
			$this->add_control(
				'heading_icon_hover_color',
				[
					'label'     => __( 'Icon Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => 'line_icon',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover i'  => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'infobox_icon_hover_bgcolor',
				[
					'label'     => __( 'Background Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover, {{WRAPPER}} .uael-image-content:hover' => 'background-color: {{VALUE}};',

					],
				]
			);
			$this->add_control(
				'heading_icon_hover_border',
				[
					'label'     => __( 'Border Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
						'heading_icon_border!'          => 'none',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover, {{WRAPPER}} .uael-image-content:hover ' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'heading_icon_animation',
				[
					'label'     => __( 'Hover Animation', 'uael' ),
					'type'      => Controls_Manager::HOVER_ANIMATION,
					'condition' => [
						'heading_imgicon_style_options' => 'custom',
						'heading_separator_style'       => [ 'line_icon', 'line_image' ],
					],
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Advanced Heading Typography Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_typo_content_controls() {
		$this->start_controls_section(
			'section_typography',
			[
				'label' => __( 'Typography', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'heading_typo',
			[
				'label'     => __( 'Heading', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'heading_tag',
			[
				'label'   => __( 'HTML Tag', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'uael' ),
					'h2' => __( 'H2', 'uael' ),
					'h3' => __( 'H3', 'uael' ),
					'h4' => __( 'H4', 'uael' ),
					'h5' => __( 'H5', 'uael' ),
					'h6' => __( 'H6', 'uael' ),
				],
				'default' => 'h2',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .uael-heading, {{WRAPPER}} .uael-heading a',
			]
		);
		$this->add_control(
			'heading_color_type',
			[
				'label'        => __( 'Fill', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'color'    => __( 'Color', 'uael' ),
					'gradient' => __( 'Background', 'uael' ),
				],
				'default'      => 'color',
				'prefix_class' => 'uael-heading-fill-',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .uael-heading-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'heading_color_type' => 'color',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'heading_color_gradient',
				'types'          => [ 'gradient', 'classic' ],
				'selector'       => '{{WRAPPER}} .uael-heading-text',
				'fields_options' => [
					'background' => [
						'scheme' => [
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
					],
				],
				'condition'      => [
					'heading_color_type' => 'gradient',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'heading_shadow',
				'selector' => '{{WRAPPER}} .uael-heading-text',
			]
		);
		$this->add_control(
			'heading_margin',
			[
				'label'      => __( 'Heading Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'bottom'   => '15',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);
		$this->add_control(
			'heading_desc_typo',
			[
				'label'     => __( 'Description', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_description!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_desc_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .uael-subheading',
				'condition' => [
					'heading_description!' => '',
				],
			]
		);
		$this->add_control(
			'heading_desc_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default'   => '',
				'condition' => [
					'heading_description!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-subheading' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_desc_margin',
			[
				'label'      => __( 'Description Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '15',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'condition'  => [
					'heading_description!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-subheading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);
		$this->end_controls_section();
	}


	/**
	 * Display Separator.
	 *
	 * @since 0.0.1
	 * @access public
	 * @param object $pos for position of separator.
	 * @param object $settings for settings.
	 */
	public function render_separator( $pos, $settings ) {
		if ( 'none' != $settings['heading_separator_style'] && $pos == $settings['heading_separator_position'] ) {
			?>
			<div class="uael-module-content uael-separator-parent">
				<?php if ( 'line_icon' == $settings['heading_separator_style'] || 'line_image' == $settings['heading_separator_style'] || 'line_text' == $settings['heading_separator_style'] ) { ?>
				<div class="uael-separator-wrap">
					<div class="uael-separator-line uael-side-left">
						<span></span>
					</div>
					<div class="uael-divider-content">
						<?php $this->render_image( $settings ); ?>
						<?php
						if ( 'line_text' == $settings['heading_separator_style'] ) {
								echo '<span class="uael-divider-text elementor-inline-editing" data-elementor-setting-key="heading_line_text" data-elementor-inline-editing-toolbar="basic">' . $settings['heading_line_text'] . '</span>';
						}
						?>

					</div>
					<div class="uael-separator-line uael-side-right">
						<span></span>
					</div>
				</div>
			<?php } ?>
				<?php if ( 'line' == $settings['heading_separator_style'] ) { ?>
					<div class="uael-separator"></div>
				<?php } ?>
			</div>
		<?php
		}
	}

	/**
	 * Display Separator image/icon.
	 *
	 * @since 0.0.1
	 * @access public
	 * @param object $settings for settings.
	 */
	public function render_image( $settings ) {
		if ( 'line_icon' == $settings['heading_separator_style'] || 'line_image' == $settings['heading_separator_style'] ) {
			$anim_class = '';
			if ( 'simple' == $settings['heading_imgicon_style_options'] ) {
				$anim_class = $settings['headings_icon_animation'];
			} elseif ( 'custom' == $settings['heading_imgicon_style_options'] ) {
				$anim_class = $settings['heading_icon_animation'];
			}
		?>
			<div class="uael-module-content uael-imgicon-wrap elementor-animation-<?php echo $anim_class; ?>"><?php /* Module Wrap */ ?>
				<?php /*Icon Html */ ?>
				<?php if ( 'line_icon' == $settings['heading_separator_style'] ) { ?>
					<div class="uael-icon-wrap">
						<span class="uael-icon">
							<i class="<?php echo $settings['heading_icon']; ?>"></i>
						</span>
					</div>
				<?php } // Icon Html End. ?>

				<?php /* Photo Html */ ?>
				<?php
				if ( 'line_image' == $settings['heading_separator_style'] ) {
					if ( 'media' == $settings['heading_image_type'] ) {
						if ( ! empty( $settings['heading_image']['url'] ) ) {
							$this->add_render_attribute( 'heading_image', 'src', $settings['heading_image']['url'] );
							$this->add_render_attribute( 'heading_image', 'alt', Control_Media::get_image_alt( $settings['heading_image'] ) );

							$image_html = '<img class="uael-photo-img" ' . $this->get_render_attribute_string( 'heading_image' ) . '>';
						}
					}
					if ( 'url' == $settings['heading_image_type'] ) {
						if ( ! empty( $settings['heading_image_link'] ) ) {

							$this->add_render_attribute( 'heading_image_link', 'src', $settings['heading_image_link']['url'] );

							$image_html = '<img class="uael-photo-img" ' . $this->get_render_attribute_string( 'heading_image_link' ) . '>';
						}
					}
					?>
					<div class="uael-image" itemscope itemtype="http://schema.org/ImageObject">
						<div class="uael-image-content">
							<?php echo $image_html; ?>
						</div>
					</div>
				<?php } // Photo Html End. ?>
			</div>
		<?php
		}
	}

	/**
	 * Render Heading output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {
		$html     = '';
		$settings = $this->get_settings();

		$this->add_inline_editing_attributes( 'heading_title', 'basic' );
		$this->add_inline_editing_attributes( 'heading_description', 'advanced' );

		if ( empty( $settings['heading_title'] ) ) {
			return;
		}

		if ( ! empty( $settings['heading_link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', $settings['heading_link']['url'] );

			if ( $settings['heading_link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['heading_link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}
			$link = $this->get_render_attribute_string( 'url' );
		}
		?>

		<div class="uael-module-content uael-heading-wrapper">
			<?php $this->render_separator( 'top', $settings ); ?>

			<<?php echo $settings['heading_tag']; ?> class="uael-heading">
				<?php if ( ! empty( $settings['heading_link']['url'] ) ) { ?>
					<a <?php echo $link; ?> >
				<?php } ?>
					<span class="uael-heading-text elementor-inline-editing" data-elementor-setting-key="heading_title" data-elementor-inline-editing-toolbar="basic" ><?php echo $settings['heading_title']; ?></span>
				<?php if ( ! empty( $settings['heading_link']['url'] ) ) { ?>
					</a>
				<?php } ?>
			</<?php echo $settings['heading_tag']; ?>>

			<?php $this->render_separator( 'center', $settings ); ?>

			<?php if ( '' != $settings['heading_description'] ) { ?>
				<div class="uael-subheading elementor-inline-editing" data-elementor-setting-key="heading_description" data-elementor-inline-editing-toolbar="advanced" >
					<?php echo $settings['heading_description']; ?>
				</div>
				<?php } ?>

				<?php $this->render_separator( 'bottom', $settings ); ?>
		</div> 
		<?php
	}

	/**
	 * Render Heading widgets output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
	?>
		<#
		function render_separator( pos ) {
			if ( 'none' != settings.heading_separator_style && pos == settings.heading_separator_position ) {
			#>
				<div class="uael-module-content uael-separator-parent">
					<# if ( 'line_icon' == settings.heading_separator_style || 'line_image' == settings.heading_separator_style || 'line_text' == settings.heading_separator_style ) { #>
						<div class="uael-separator-wrap">
							<div class="uael-separator-line uael-side-left">
								<span></span>
							</div>
							<div class="uael-divider-content">
								<#
								render_image();
								if ( 'line_text' == settings.heading_separator_style ) { #>
									<span class="uael-divider-text elementor-inline-editing" data-elementor-setting-key="heading_line_text" data-elementor-inline-editing-toolbar="basic">{{{ settings.heading_line_text }}}</span>
								<# } #>
							</div>
							<div class="uael-separator-line uael-side-right">
									<span></span>
							</div>
						</div>
					<# } #>
					<# if ( 'line' == settings.heading_separator_style ) { #>
						<div class="uael-separator"></div>
					<# } #>
				</div>
			<#
			}
		}
		#>


		<#
		function render_image() {
			if ( 'line_icon' == settings.heading_separator_style || 'line_image' == settings.heading_separator_style ) {

				view.addRenderAttribute( 'anim_class', 'class', 'uael-module-content uael-imgicon-wrap' );

				if ( 'simple' == settings.heading_imgicon_style_options ) {
					view.addRenderAttribute( 'anim_class', 'class', 'elementor-animation-' + settings.headings_icon_animation );
				}
				else if ( 'custom' == settings.heading_imgicon_style_options ) {
					view.addRenderAttribute( 'anim_class', 'class', 'elementor-animation-' + settings.heading_icon_animation );
				}
				#>
				<div {{{ view.getRenderAttributeString( 'anim_class' ) }}} >
					<# if ( 'line_icon' == settings.heading_separator_style ) { #>
						<div class="uael-icon-wrap">
							<span class="uael-icon">
								<i class="{{{ settings.heading_icon }}}"></i>
							</span>
						</div>
					<# } #>
					<# if ( 'line_image' == settings.heading_separator_style ) { #>
						<div class="uael-image" itemscope itemtype="http://schema.org/ImageObject">
							<div class="uael-image-content">
								<#
								if ( 'media' == settings.heading_image_type ) {
									if ( '' != settings.heading_image.url ) {
										view.addRenderAttribute( 'heading_image', 'src', settings.heading_image.url );
										#>
										<img class="uael-photo-img" {{{ view.getRenderAttributeString( 'heading_image' ) }}}>
										<#
									}
								}
								if ( 'url' == settings.heading_image_type ) {
									if ( '' != settings.heading_image_link ) {
										view.addRenderAttribute( 'heading_image_link', 'src', settings.heading_image_link.url );
										#>
										<img class="uael-photo-img" {{{ view.getRenderAttributeString( 'heading_image_link' ) }}}>
										<#
									}
								} #>
							</div>
						</div>
					<# } #>
				</div>
			<#
			}
		}
		#>



		<#
		if ( '' == settings.heading_title ) {
			return;
		}
		if ( '' != settings.heading_link.url ) {
			view.addRenderAttribute( 'url', 'href', settings.heading_link.url );
		}
		#>
		<div class="uael-module-content uael-heading-wrapper">
			<# render_separator( 'top' ); #>
			<{{{ settings.heading_tag }}} class="uael-heading">
				<# if ( '' != settings.heading_link.url ) { #>
					<a {{{ view.getRenderAttributeString( 'url' ) }}} >
				<# } #>
				<span class="uael-heading-text elementor-inline-editing" data-elementor-setting-key="heading_title" data-elementor-inline-editing-toolbar="basic" >{{{ settings.heading_title }}}</span>
				<# if ( '' != settings.heading_link.url ) { #>
					</a>
				<# } #>
			</{{{ settings.heading_tag }}}>

			<# render_separator( 'center' ); #>

			<# if ( '' != settings.heading_description ) { #>
				<div class="uael-subheading elementor-inline-editing" data-elementor-setting-key="heading_description" data-elementor-inline-editing-toolbar="basic" >
					{{{ settings.heading_description }}}
				</div>
			<# } #>
			<# render_separator( 'bottom' ); #>
		</div>		
	<?php
	}

}
