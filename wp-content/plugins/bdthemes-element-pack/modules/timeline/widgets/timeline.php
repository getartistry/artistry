<?php
namespace ElementPack\Modules\Timeline\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

use ElementPack\Modules\Timeline\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Timeline extends Widget_Base {
	public function get_name() {
		return 'bdt-timeline';
	}

	public function get_title() {
		return esc_html__( 'Timeline', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function _register_skins() {
		$this->add_skin( new Skins\Skin_Custom( $this ) );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'   => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'   => esc_html__( 'Meta Data', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'   => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Length', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [
					'show_excerpt'   => 'yes',
				],
			]
		);

		$this->add_control(
			'show_readmore',
			[
				'label'   => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label'       => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => __( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
					'xs' => __( 'Extra Small', 'bdthemes-element-pack' ),
					'sm' => __( 'Small', 'bdthemes-element-pack' ),
					'md' => __( 'Medium', 'bdthemes-element-pack' ),
					'lg' => __( 'Large', 'bdthemes-element-pack' ),
					'xl' => __( 'Extra Large', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::ICON,
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'   => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-timeline .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);	
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_query',
			[
				'label'     => esc_html__( 'Query', 'bdthemes-element-pack' ),
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => _x( 'Source', 'Posts Query Control', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''        => esc_html__( 'Show All', 'bdthemes-element-pack' ),
					'by_name' => esc_html__( 'Manual Selection', 'bdthemes-element-pack' ),
				],
			]
		);

		$post_categories = get_terms( 'category' );

		$post_options = [];
		foreach ( $post_categories as $category ) {
			$post_options[ $category->slug ] = $category->name;
		}

		$this->add_control(
			'post_categories',
			[
				'label'       => esc_html__( 'Categories', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $post_options,
				'default'     => [],
				'multiple'    => true,
				'condition'   => [
					'source'    => 'by_name',
				],
			]
		);

		$this->add_control(
			'posts_limit',
			[
				'label'   => esc_html__( 'Posts Limit', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'     => esc_html__( 'Date', 'bdthemes-element-pack' ),
					'title'    => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'category' => esc_html__( 'Category', 'bdthemes-element-pack' ),
					'rand'     => esc_html__( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'Descending', 'bdthemes-element-pack' ),
					'ASC'  => esc_html__( 'Ascending', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			[
				'label' => esc_html__( 'Item', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f3f3',
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-item-main' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-arrow'     => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-item-main',
			]
		);

		$this->add_control(
			'timeline_line_color',
			[
				'label'     => esc_html__( 'Line Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-line span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'timeline_line_width',
			[
				'label' => __( 'Line Width', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-line span' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-timeline .bdt-timeline-item-main',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-item-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_animation',
			[
				'label'   => esc_html__( 'ScrollSpy Animation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span'
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 35,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_show',
			[
				'label'   => esc_html__( 'Show Icon', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-timeline-icon-',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 35,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span:after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_show' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span',
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span:hover:after' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span:hover:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-icon span:hover:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_background_color',
			[
				'label'     => esc_html__( 'background-Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f3f3;',
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-date span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-date span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'date_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-date span',
			]
		);

		$this->add_responsive_control(
			'date_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top'    => '10',
					'right'  => '15',
					'bottom' => '10',
					'left'   => '15',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-date span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'date_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-timeline .bdt-timeline-date span',
			]
		);

		$this->add_responsive_control(
			'date_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => '2',
					'right'  => '2',
					'bottom' => '2',
					'left'   => '2',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-date span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-date',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'bdthemes-element-pack' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'bdt-timeline-thumbnail-size-',
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label'   => esc_html__( 'Image Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 265,
				],
				'range' => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-thumbnail img' => 'height: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'     => esc_html__( 'Opacity', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-thumbnail img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top'    => '20',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-timeline .bdt-timeline-thumbnail',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-title a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#bbbbbb',
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-meta *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-meta *',
			]
		);

		$this->add_control(
			'meta_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-excerpt',
			]
		);

		$this->add_control(
			'excerpt_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-excerpt' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_readmore',
			[
				'label' => esc_html__( 'Readmore Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_readmore_style' );

		$this->start_controls_tab(
			'tab_readmore_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_background',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'readmore_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore',
			]
		);

		$this->add_control(
			'readmore_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'readmore_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore',
			]
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'readmore_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'readmore_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'readmore_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-timeline .bdt-timeline-readmore:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function filter_excerpt_length() {
		return $this->get_settings( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	public function render() {
		global $post;
		$settings = $this->get_settings();
		$id       = $this->get_id();
		$classes  = ['bdt-grid', 'bdt-grid-collapse'];

		$animation = ($settings['readmore_hover_animation']) ? ' elementor-animation-'.$settings['readmore_hover_animation'] : '';

		$args = array(
			'posts_per_page' => $settings['posts_limit'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'post_status'    => 'publish'
		);
		
		if ( 'by_name' === $settings['source'] ) :
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $settings['post_categories'],
			);
		endif;

		$wp_query = new \WP_Query($args);

		if( $wp_query->have_posts() ) :
			add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
			add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		?> 

			<div id="bdt-timeline-<?php echo esc_attr($id); ?>" class="bdt-timeline bdt-timeline-skin-default">
				<div class="<?php echo \element_pack_helper::acssc($classes); ?>">
					<?php
					$bdt_count = 0;
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
						$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
						$bdt_count++;
						$post_format    = get_post_format() ? : 'standard';
						$meta_date      = '';
						$item_part      = ($bdt_count%2 === 0) ? 'right' : 'left';
				  	?>
				  		
						<?php if( $bdt_count%2 === 0) : ?>
				  			<div class="bdt-timeline-item bdt-width-1-2@m bdt-visible@m">
						  		<div class="bdt-timeline-date bdt-text-right"><span><?php echo esc_attr(get_the_date('d F Y')); ?></span></div>
							</div>
						<?php endif; ?>

			  			<div class="bdt-width-1-2@m bdt-timeline-item <?php echo $item_part . '-part'; ?>">
				  			
				  			<div class="bdt-timeline-item-main-wrapper">
				  				<div class="bdt-timeline-line"><span bdt-parallax="opacity: 0,1;viewport: 0.2;"></span></div>
					  			<div class="bdt-timeline-item-main-container">
					  					<?php $item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-scale-up;"' : ''; ?>
					  				<div class="bdt-timeline-icon bdt-post-format-<?php echo esc_attr($post_format); ?>"<?php echo $item_scrollspy; ?>>
					  					<span></span>
					  				</div>
						  			<?php $item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-slide-'.$item_part.'-medium;"' : ''; ?>
						  			<div class="bdt-timeline-item-main"<?php echo $item_scrollspy; ?>>
						  				<span class="bdt-timeline-arrow"></span>

						  				<?php if ('yes' == $settings['show_image'] and isset($post_thumbnail[0])) : ?>
									  		<div class="bdt-timeline-thumbnail">
									  			<img src="<?php echo esc_url($post_thumbnail[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
									  		</div>
								  		<?php endif ?>
								  		<div class="bdt-timeline-desc bdt-padding">

											<?php if ('yes' == $settings['show_title']) : ?>
												<h4 class="bdt-timeline-title"><a href="<?php echo esc_url(get_permalink()); ?>" class="" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a></h4>
											<?php endif ?>

											<?php $meta_date = '<li class="bdt-hidden@m">'.esc_attr(get_the_date('d F Y')).'</li>'; ?>

											<?php if ('yes' == $settings['show_meta']) : ?>
												<?php $meta_list = '<li>'.get_the_category_list(', ').'</li>'; ?>
												<ul class="bdt-timeline-meta bdt-subnav"><?php echo wp_kses_post($meta_date); ?><?php echo wp_kses_post($meta_list); ?></ul>
											<?php endif ?>

											<?php if ('yes' == $settings['show_excerpt']) : ?>
												<div class="bdt-timeline-excerpt"><?php do_shortcode( the_excerpt() ); ?></div>
											<?php endif ?>

											<?php if ('yes' == $settings['show_readmore']) : ?>
												<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-timeline-readmore elementor-button elementor-size-<?php echo esc_attr($settings['button_size']); ?><?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['readmore_text']); ?>

													<?php if ($settings['icon']) : ?>
														<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
															<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
														</span>
													<?php endif; ?>
												</a>
											<?php endif ?>
								  		</div>
									</div>
								</div>
							</div>
						</div>

					  	<?php if( $bdt_count%2 === 1) : ?>
					  		<?php 
					  			$item_part = ($bdt_count%2 === 1) ? 'right' : 'left';
					  			$item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-slide-'.$item_part.'-medium;"' : ''; ?>
				  			<div class="bdt-timeline-item bdt-width-1-2@m bdt-visible@m">
						  		<div class="bdt-timeline-date"<?php echo $item_scrollspy; ?>><span><?php echo esc_attr(get_the_date('d F Y')); ?></span></div>
						  		
							</div>
						<?php endif; ?>

					<?php endwhile; ?>
				</div>
			</div>
		
		 	<?php 
		 	add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		 	add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		 	wp_reset_postdata(); ?>

 		<?php endif;
	}
}