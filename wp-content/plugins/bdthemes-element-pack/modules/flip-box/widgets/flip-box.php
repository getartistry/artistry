<?php
namespace ElementPack\Modules\FlipBox\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Flip_Box extends Widget_Base {

	public function get_name() {
		return 'bdt-flip-box';
	}

	public function get_title() {
		return __( 'Flip Box', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-flip-box';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_side_a_content',
			[
				'label' => __( 'Front', 'bdthemes-element-pack' ),
			]
		);

		$this->start_controls_tabs( 'front_content_tabs' );

		$this->start_controls_tab( 'front_content_tab', [ 'label' => __( 'Content', 'bdthemes-element-pack' ) ] );

		$this->add_control(
			'graphic_element',
			[
				'label'   => __( 'Graphic Element', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-ban',
					],
					'image' => [
						'title' => __( 'Image', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-picture-o',
					],
					'icon' => [
						'title' => __( 'Icon', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-star',
					],
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => __( 'Choose Image', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image', // Actually its `image_size`
				'label'     => __( 'Image Size', 'bdthemes-element-pack' ),
				'default'   => 'thumbnail',
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => __( 'Icon', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-heart',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label'   => __( 'View', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'bdthemes-element-pack' ),
					'stacked' => __( 'Stacked', 'bdthemes-element-pack' ),
					'framed'  => __( 'Framed', 'bdthemes-element-pack' ),
				],
				'default'   => 'default',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label'   => __( 'Shape', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'bdthemes-element-pack' ),
					'square' => __( 'Square', 'bdthemes-element-pack' ),
				],
				'default'   => 'circle',
				'condition' => [
					'icon_view!'      => 'default',
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_title_text',
			[
				'label'       => __( 'Title & Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'This is the heading', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Your Title', 'bdthemes-element-pack' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'front_description_text',
			[
				'label'       => __( 'Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Your Description', 'bdthemes-element-pack' ),
				'title'       => __( 'Input image text here', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'front_background_tab', [ 'label' => __( 'Background', 'bdthemes-element-pack' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'front_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bdt-flip-box-front',
			]
		);

		$this->add_control(
			'front_background_overlay',
			[
				'label'     => __( 'Background Overlay', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'front_background_image[id]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back_content',
			[
				'label' => __( 'Back', 'bdthemes-element-pack' ),
			]
		);

		$this->start_controls_tabs( 'back_content_tabs' );

		$this->start_controls_tab( 'back_content_tab', [ 'label' => __( 'Content', 'bdthemes-element-pack' ) ] );

		$this->add_control(
			'back_title_text',
			[
				'label'       => __( 'Title & Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'This is the heading', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Your Title', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'back_description_text',
			[
				'label'       => __( 'Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Your Description', 'bdthemes-element-pack' ),
				'title'       => __( 'Input image text here', 'bdthemes-element-pack' ),
				'separator'   => 'none',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     => __( 'Button Text', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Click Here', 'bdthemes-element-pack' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'http://your-link.com', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link_click',
			[
				'label'   => __( 'Apply Link On', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'box'    => __( 'Whole Box', 'bdthemes-element-pack' ),
					'button' => __( 'Button Only', 'bdthemes-element-pack' ),
				],
				'default'   => 'button',
				'condition' => [
					'link[url]!' => '',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
					'xs' => __( 'Extra Small', 'bdthemes-element-pack' ),
					'sm' => __( 'Small', 'bdthemes-element-pack' ),
					'md' => __( 'Medium', 'bdthemes-element-pack' ),
					'lg' => __( 'Large', 'bdthemes-element-pack' ),
					'xl' => __( 'Extra Large', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'back_background_tab', [ 'label' => __( 'Background', 'bdthemes-element-pack' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'back_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bdt-flip-box-back',
			]
		);

		$this->add_control(
			'back_background_overlay',
			[
				'label' => __( 'Background Overlay', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'back_background_image[id]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_settings',
			[
				'label' => __( 'Settings', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-layer, {{WRAPPER}} .bdt-flip-box-layer-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'flip_effect',
			[
				'label'   => __( 'Flip Effect', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
					'flip'     => __( 'Flip', 'bdthemes-element-pack' ),
					'slide'    => __( 'Slide', 'bdthemes-element-pack' ),
					'push'     => __( 'Push', 'bdthemes-element-pack' ),
					'zoom-in'  => __( 'Zoom In', 'bdthemes-element-pack' ),
					'zoom-out' => __( 'Zoom Out', 'bdthemes-element-pack' ),
					'fade'     => __( 'Fade', 'bdthemes-element-pack' ),
				],
				'prefix_class' => 'bdt-flip-box-effect-',
			]
		);

		$this->add_control(
			'flip_direction',
			[
				'label'   => __( 'Flip Direction', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => __( 'Left', 'bdthemes-element-pack' ),
					'right' => __( 'Right', 'bdthemes-element-pack' ),
					'up'    => __( 'Up', 'bdthemes-element-pack' ),
					'down'  => __( 'Down', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'flip_effect!' => [
							'fade',
							'zoom-in',
							'zoom-out',
						],
				],
				'prefix_class' => 'bdt-flip-box-direction-',
			]
		);

		$this->add_control(
			'flip_3d',
			[
				'label'        => __( '3D Depth', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'prefix_class' => 'bdt-flip-box-3d-',
				'condition' => [
					'flip_effect' => 'flip',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_front',
			[
				'label' => __( 'Front', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => __( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'front_alignment',
			[
				'label' => __( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_vertical_position',
			[
				'label' => __( 'Vertical Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'bdthemes-element-pack' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'bdthemes-element-pack' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'bdthemes-element-pack' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
		$this->start_controls_tabs( 'front_style_tabs' );

		$this->start_controls_tab(
			'front_image_style_tab',
			[
				
				'label'     => __( 'Image', 'bdthemes-element-pack' ),
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label'      => __( 'Size (%)', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'   => __( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-image' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'label'     => __( 'Image Border', 'bdthemes-element-pack' ),
				'selector'  => '{{WRAPPER}} .bdt-flip-box-image img',
				'condition' => [
					'graphic_element' => 'image',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
		'front_icon_style_tab',
			[ 
				'label' => __( 'Icon', 'bdthemes-element-pack' ),
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => __( 'Icon Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => __( 'Secondary Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Icon Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => __( 'Icon Rotate', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => __( 'Border Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'front_title_style_tab',
			[ 
				'label' => __( 'Title', 'bdthemes-element-pack' ),
				'condition' => [
					'front_title_text!' => '',
				],
			]
		);

		$this->add_control(
			'front_title_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_description_text!' => '',
				],
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' => __( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-title' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'front_title_typography',
				'label'    => __( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'front_description_style_tab',
			[ 
				'label' => __( 'Description', 'bdthemes-element-pack' ),
				'condition' => [
					'front_description_text!' => '',
				],
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-desc' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'front_description_typography',
				'label'    => __( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .bdt-flip-box-front .bdt-flip-box-layer-desc',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'front_border',
				'selector'  => '{{WRAPPER}} .bdt-flip-box-front',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_back',
			[
				'label' => __( 'Back', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => __( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'back_alignment',
			[
				'label' => __( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .bdt-flip-box-button' => 'margin-{{VALUE}}: 0',
				],
			]
		);

		$this->add_control(
			'back_vertical_position',
			[
				'label'       => __( 'Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'top' => [
						'title' => __( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);


		$this->start_controls_tabs( 'back_style_tabs' );

		$this->start_controls_tab(
		'back_title_style_tab',
			[ 
				'label' => __( 'Title', 'bdthemes-element-pack' ),
				'condition' => [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_title_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-title' => 'color: {{VALUE}}',

				],
				'condition' => [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'back_title_typography',
				'label'     => __( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-title',
				'condition' => [
					'back_title_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'back_description_style_tab',
			[ 
				'label' => __( 'Description', 'bdthemes-element-pack' ),
				'condition' => [
					'back_description_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_description_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => __( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-desc' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'description_typography_b',
				'label'     => __( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .bdt-flip-box-back .bdt-flip-box-layer-desc',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'back_border',
				'selector'  => '{{WRAPPER}} .bdt-flip-box-back',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_text!' => '',
				],
			]
		);


		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-flip-box-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-flip-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-flip-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-flip-box-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-flip-box-button:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-flip-box-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings    = $this->get_settings();
		$animation   = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		$wrapper_tag = 'div';
		$button_tag  = 'a';
		$link_url    = empty( $settings['link']['url'] ) ? '#' : $settings['link']['url'];
		$this->add_render_attribute( 'button', 'class', [
				'bdt-flip-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
				$animation,
			]
		);

		$this->add_render_attribute( 'wrapper', 'class', 'bdt-flip-box-layer bdt-flip-box-back' );
		if ( 'box' === $settings['link_click'] ) {
			$wrapper_tag = 'a';
			$button_tag = 'button';
			$this->add_render_attribute( 'wrapper', 'href', $link_url );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'wrapper', 'target', '_blank' );
			}
		} else {
			$this->add_render_attribute( 'button', 'href', $link_url );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}
		}

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon-wrapper' );
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-view-' . $settings['icon_view'] );
			if ( 'default' != $settings['icon_view'] ) {
				$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-shape-' . $settings['icon_shape'] );
			}
			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}

		?>
		<div class="bdt-flip-box">
			<div class="bdt-flip-box-layer bdt-flip-box-front">
				<div class="bdt-flip-box-layer-overlay">
					<div class="bdt-flip-box-layer-inner">
						<?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) : ?>
							<div class="bdt-flip-box-image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
							</div>
						<?php elseif ( 'icon' === $settings['graphic_element'] && ! empty( $settings['icon'] ) ) : ?>
							<div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
								<div class="elementor-icon">
									<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['front_title_text'] ) ) : ?>
							<h3 class="bdt-flip-box-layer-title">
								<?php echo $settings['front_title_text']; ?>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['front_description_text'] ) ) : ?>
							<div class="bdt-flip-box-layer-desc">
								<?php echo $settings['front_description_text']; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div class="bdt-flip-box-layer-overlay">
					<div class="bdt-flip-box-layer-inner">
						<?php if ( ! empty( $settings['back_title_text'] ) ) : ?>
							<h3 class="bdt-flip-box-layer-title">
								<?php echo $settings['back_title_text']; ?>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['back_description_text'] ) ) : ?>
							<div class="bdt-flip-box-layer-desc">
								<?php echo $settings['back_description_text']; ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['button_text'] ) ) : ?>
							<<?php echo $button_tag; ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
								<?php echo $settings['button_text']; ?>
							</<?php echo $button_tag; ?>>
						<?php endif; ?>
					</div>
				</div>
			</<?php echo $wrapper_tag; ?>>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
			var btnClasses = 'bdt-flip-box-button elementor-button elementor-size-' + settings.button_size + ' elementor-animation-' + settings.button_hover_animation;

			if ( 'image' === settings.graphic_element && '' !== settings.image.url ) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.image_size,
					dimension: settings.image_custom_dimension,
					model: editModel
				};

				var imageUrl = elementor.imagesManager.getImageUrl( image );
			}

			var wrapperTag = 'div',
				buttonTag = 'a';

			if ( 'box' === settings.link_click ) {
				wrapperTag = 'a';
				buttonTag = 'button';
			}

			if ( 'icon' === settings.graphic_element ) {
				var iconWrapperClasses = 'elementor-icon-wrapper';
					iconWrapperClasses += ' elementor-view-' + settings.icon_view;
				if ( 'default' !== settings.icon_view ) {
					iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
				}
			}
		#>

		<div class="bdt-flip-box">
			<div class="bdt-flip-box-layer bdt-flip-box-front">
				<div class="bdt-flip-box-layer-overlay">
					<div class="bdt-flip-box-layer-inner">
						<# if ( 'image' === settings.graphic_element && '' !== settings.image.url ) { #>
							<div class="bdt-flip-box-image">
								<img src="{{ imageUrl }}">
							</div>
						<#  } else if ( 'icon' === settings.graphic_element && settings.icon ) { #>
							<div class="{{ iconWrapperClasses }}" >
								<div class="elementor-icon">
									<i class="{{ settings.icon }}"></i>
								</div>
							</div>
						<# } #>

						<# if ( settings.front_title_text ) { #>
							<h3 class="bdt-flip-box-layer-title">{{{ settings.front_title_text }}}</h3>
						<# } #>

						<# if ( settings.front_description_text ) { #>
							<div class="bdt-flip-box-layer-desc">{{{ settings.front_description_text }}}</div>
						<# } #>
					</div>
				</div>
			</div>
			<{{ wrapperTag }} class="bdt-flip-box-layer bdt-flip-box-back">
				<div class="bdt-flip-box-layer-overlay">
					<div class="bdt-flip-box-layer-inner">
						<# if ( settings.back_title_text ) { #>
							<h3 class="bdt-flip-box-layer-title">{{{ settings.back_title_text }}}</h3>
						<# } #>

						<# if ( settings.back_description_text ) { #>
							<div class="bdt-flip-box-layer-desc">{{{ settings.back_description_text }}}</div>
						<# } #>

						<# if ( settings.button_text ) { #>
							<{{ buttonTag }} href="#" class="{{ btnClasses }}">{{{ settings.button_text }}}</{{ buttonTag }}>
						<# } #>
					</div>
				</div>
			</{{ wrapperTag }}>
		</div>
		<?php
	}
}
