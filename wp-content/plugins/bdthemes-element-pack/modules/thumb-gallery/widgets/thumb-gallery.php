<?php
namespace ElementPack\Modules\ThumbGallery\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementPack\Modules\QueryControl\Module;

use ElementPack\Modules\ThumbGallery\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Thumb_Gallery extends Widget_Base {
	public $_query = null;

	public function get_name() {
		return 'bdt-thumb-gallery';
	}

	public function get_title() {
		return esc_html__( 'Thumb Gallery', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-thumbnails-down';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	public function on_export( $element ) {
		$element = Group_Control_Posts::on_export_remove_setting_from_element( $element, 'posts' );
		return $element;
	}

	public function get_query() {
		return $this->_query;
	}

	public function _register_skins() {
		$this->add_skin( new Skins\Skin_Custom( $this ) );
	}

	public function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'     => esc_html__( 'Limit', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'   => esc_html__( 'Content Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => element_pack_position_options(),
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'   => esc_html__( 'Content Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Show Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => element_pack_title_tags(),
				'default'   => 'h4',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Text Length', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 25,
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'   => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'slider_size_ratio',
			[
				'label'       => esc_html__( 'Size Ratio', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => 'Slider ratio to widht and height, such as 16:9',
			]
		);

		$this->add_control(
			'slider_min_height',
			[
				'label' => esc_html__( 'Minimum Height', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1024,
					],
				],
			]
		);

		$this->add_control(
			'slideshow_fullscreen',
			[
				'label' => esc_html__( 'Fullscreen', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label'     => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::ICON,
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
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'thumbnav',
				'options' => [
					'both'     => esc_html__( 'Arrows & Thumbnav', 'bdthemes-element-pack' ),
					'arrows'   => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
					'thumbnav' => esc_html__( 'Thumbnav', 'bdthemes-element-pack' ),
					'none'     => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
				
			]
		);

		$this->add_control(
			'hide_arrows',
			[
				'label'     => esc_html__( 'Hide arrows on mobile devices?', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'thumbnav_position',
			[
				'label'     => esc_html__( 'Thumbnav Position', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom-center',
				'options'   => element_pack_position_options(),
				'condition' => [
					'navigation' => ['thumbnav', 'both']
				],
			]
		);

		$this->add_control(
			'thumbnav_outside',
			[
				'label'     => esc_html__( 'Thumbnav Outside', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'thumbnav_position' => ['center-left', 'center-right']
				],
			]
		);

		$this->add_responsive_control(
			'thumbnav_width',
			[
				'label' => esc_html__( 'Thumbnav Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery-thumbnav a' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => ['thumbnav', 'both']
				],
			]
		);

		$this->add_responsive_control(
			'thumbnav_height',
			[
				'label' => esc_html__( 'Thumbnav Height', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery-thumbnav a' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => ['thumbnav', 'both']
				],
			]
		);

		$this->end_controls_section();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label'     => esc_html__( 'Query', 'bdthemes-element-pack' ),
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name'  => 'posts',
				'label' => esc_html__( 'Posts', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => esc_html__( 'Advanced', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date'  => esc_html__( 'Date', 'bdthemes-element-pack' ),
					'post_title' => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'menu_order' => esc_html__( 'Menu Order', 'bdthemes-element-pack' ),
					'rand'       => esc_html__( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'  => esc_html__( 'ASC', 'bdthemes-element-pack' ),
					'desc' => esc_html__( 'DESC', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label'     => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background',
			[
				'label' => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'content_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-content',
			]
		);

		$this->add_control(
			'content_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_transition',
			[
				'label'   => esc_html__( 'Content Transition', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => element_pack_transition_options(),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'title_background',
			[
				'label' => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label'     => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_text' => 'yes',
				]
			]
		);

		$this->add_control(
			'text_background',
			[
				'label' => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-text' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_space',
			[
				'label' => esc_html__( 'Space', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes'
				]
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
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_space',
			[
				'label' => esc_html__( 'Space', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			'hover_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-thumb-gallery-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'thumbnav', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Arrows Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-slidenav' => 'color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label' => esc_html__( 'Arrows Hover Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-slidenav:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => esc_html__( 'Arrows Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-slidenav-next'     => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-slidenav-previous' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Arrows Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 25,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery .bdt-slidenav svg' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_thumbnav',
			[
				'label'     => esc_html__( 'Thumbnav', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [ 'thumbnav', 'both' ],
				],
			]
		);

		$this->start_controls_tabs('tabs_thumbnav_style');

		$this->start_controls_tab(
			'tab_thumbnav_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
				'condition' => [
					'navigation' => [ 'thumbnav', 'both' ],
				],
			]
		);

		$this->add_control(
			'thumbnav_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery-thumbnav a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumbnav_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-thumb-gallery-thumbnav a',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'thumbnav_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-thumb-gallery-thumbnav a',
			]
		);

		$this->add_control(
			'thumbnav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-thumb-gallery-thumbnav a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'thumbnav_spacing',
			[
				'label' => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-thumbnav:not(.bdt-thumbnav-vertical) > *' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-thumbnav:not(.bdt-thumbnav-vertical)' => 'margin-left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-thumbnav-vertical > *' => 'padding-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-thumbnav-vertical' => 'margin-top: -{{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumbnav_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
				'condition' => [
					'navigation' => [ 'thumbnav', 'both' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumbnav_hover_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-thumb-gallery-thumbnav a:hover',
			]
		);

		$this->add_control(
			'thumbnav_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'thumbnav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-thumb-gallery-thumbnav a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'autoplay_interval',
			[
				'label' => esc_html__( 'Autoplay Interval', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 7000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'     => esc_html__( 'Pause on Hover', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_animations',
			[
				'label'     => esc_html__( 'Slider Animations', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => 'slide',
				'options'   => [
					'slide' => esc_html__( 'Slide', 'bdthemes-element-pack' ),
					'fade'  => esc_html__( 'Fade', 'bdthemes-element-pack' ),
					'scale' => esc_html__( 'Scale', 'bdthemes-element-pack' ),
					'push'  => esc_html__( 'Push', 'bdthemes-element-pack' ),
					'pull'  => esc_html__( 'Pull', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'kenburns_animation',
			[
				'label'     => esc_html__( 'Kenburns Animation', 'bdthemes-element-pack' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_section();
	}

	public function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	public function query_posts() {
		$query_args = Module::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_settings( 'posts_per_page' );

		$this->_query = new \WP_Query( $query_args );
	}

	public function render() {
		$this->query_posts();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 15 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 15 );

		$this->render_header();

		$this->render_post();
		
		$this->render_footer();

		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 15 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 15 );

		wp_reset_postdata();
	}

	public function filter_excerpt_length() {
		return $this->get_settings( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	public function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		$classes = ['bdt-thumb-gallery-title'];
		?>

		<<?php echo $tag ?> class="<?php echo implode(" ", $classes); ?>">
			<?php the_title() ?>
		</<?php echo $tag ?>>
		<?php
	}


	public function render_excerpt() {
		if ( ! $this->get_settings( 'show_text' ) ) {
			return;
		}

		?>
		<div class="bdt-thumb-gallery-text bdt-text-small">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

	public function render_button() {
		if ( ! $this->get_settings( 'show_button' ) ) {
			return;
		}

		$settings  = $this->get_settings();
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		
		?>
			<div>
				<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-thumb-gallery-button bdt-display-inline-block<?php echo esc_attr($animation); ?>">
					<?php echo esc_attr($settings['button_text']); ?>
				
					<?php if ($settings['icon']) : ?>
						<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
							<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
						</span>
					<?php endif; ?>
				</a>
			</div>
		<?php
	}

	public function render_header() {
		$id              = $this->get_id();
		$settings        = $this->get_settings();
		
		$slides_settings = [];

		$ratio = ($settings['slider_size_ratio']['width'] && $settings['slider_size_ratio']['height']) ? $settings['slider_size_ratio']['width'].":".$settings['slider_size_ratio']['height'] : '';

		$slider_settings['bdt-slideshow'] = json_encode(array_filter([
			'animation'         => $settings['slider_animations'],
			'ratio'             => $ratio,
			'min-height'        => $settings['slider_min_height']['size'],
			'autoplay'          => $settings['autoplay'],
			'autoplay-interval' => $settings['autoplay_interval'],
			'pause-on-hover'    => $settings['pause_on_hover'],
	    ]));

		?>
		<div id="bdt-thumb-gallery-<?php echo $id;?>" class="bdt-thumb-gallery">
			<div class="bdt-position-relative bdt-visible-toggle" <?php echo \element_pack_helper::attrs($slider_settings); ?>>
		<?php
	}



	public function render_footer() {
		?>
			</div>
		</div>
		<?php
	}

	public function render_loop_items() {
		$this->query_posts();
		$settings = $this->get_settings();
		$content_transition = ($settings['content_transition']) ? ' bdt-transition-' . $settings['content_transition'] : '';

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$fullscreen = ($settings['slideshow_fullscreen']) ? ' bdt-height-viewport="offset-top: true;"' : '';

		?>
		<ul class="bdt-slideshow-items"<?php echo $fullscreen; ?>>

		<?php
		
		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$gallery_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			?>
			<li class="bdt-slideshow-item">
				<div class="bdt-transition-toggle">

					<?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
						<div class="bdt-position-cover bdt-animation-kenburns bdt-animation-reverse bdt-transform-origin-center-left">
					<?php endif; ?>

						<img src="<?php echo esc_url($gallery_thumbnail[0]); ?>" alt="" bdt-cover>

					<?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
			            </div>
			        <?php endif; ?>

					<?php if (( 'yes' == $settings['show_title'] ) || ( 'yes' == $settings['show_text'] ) || ( 'yes' == $settings['show_button'] )) : ?>
						<div class="bdt-position-z-index bdt-position-<?php echo $settings['content_position']; ?> bdt-position-large bdt-text-<?php echo $settings['content_align']; ?>">
							<div class="bdt-thumb-gallery-content<?php echo esc_attr($content_transition); ?>">
					        	<?php $this->render_title(); ?>
					        	<?php $this->render_excerpt(); ?>
					        	<?php $this->render_button(); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</li>
			<?php
		}

		?>
    	</ul>
    	<?php
	}

	public function render_navigation() {
		if (( 'thumbnav' == $this->get_settings('navigation')) || ( 'none' == $this->get_settings('navigation') )) {
			return;
		}

		$hide_arrows = ( 'yes' == $this->get_settings('hide_arrows') ) ? ' bdt-visible@m' : '';

		?>
		<a class="bdt-position-center-left bdt-position-small bdt-hidden-hover<?php echo esc_attr($hide_arrows); ?>" href="#" bdt-slidenav-previous bdt-slideshow-item="previous"></a>
		<a class="bdt-position-center-right bdt-position-small bdt-hidden-hover<?php echo esc_attr($hide_arrows); ?>" href="#" bdt-slidenav-next bdt-slideshow-item="next"></a>
    	<?php
	}

	public function render_loop_pagination() {
		if (( 'arrows' == $this->get_settings('navigation')) || ( 'none' == $this->get_settings('navigation') )) {
			return;
		}
		$thumbnav_outside = '';
		$vertical_thumbnav = '';
		$this->query_posts();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}
		if  ( 'center-left' == $this->get_settings('thumbnav_position') || 'center-right' == $this->get_settings('thumbnav_position') ) {
			if ('yes' == $this->get_settings('thumbnav_outside')) {
				$thumbnav_outside = '-out';
			}
			$vertical_thumbnav = ' bdt-thumbnav-vertical';
		}

		?>
		<div class="bdt-thumbnav-wrapper bdt-position-<?php echo esc_attr($this->get_settings('thumbnav_position').$thumbnav_outside); ?> bdt-position-small">
        	<ul class="bdt-thumbnav<?php echo esc_attr($vertical_thumbnav); ?>">

		<?php		
		$bdt_counter = 0;
		      
		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$gallery_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' ); 
			echo '<li class="bdt-thumb-gallery-thumbnav" bdt-slideshow-item="'.$bdt_counter.'"><a class="bdt-overflow-hidden bdt-background-cover" href="#" style="background-image: url('.esc_url($gallery_thumbnail[0]).')"></a></li>';
			$bdt_counter++;
		}
		?>
        	</ul>
		</div>
        	<?php
	}

	public function render_post() {
		$this->render_loop_items();
		$this->render_navigation();
		$this->render_loop_pagination();
	}
}
