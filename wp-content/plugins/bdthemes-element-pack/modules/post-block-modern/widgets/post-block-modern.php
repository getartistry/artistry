<?php
namespace ElementPack\Modules\PostBlockModern\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Block_Modern extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-post-block-modern';
	}

	public function get_title() {
		return esc_html__( 'Post Block Modern', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_layout_post_block_modern',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source',
			[
				'label' => _x( 'Source', 'Posts Query Control', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Show All', 'bdthemes-element-pack' ),
					'by_name' => esc_html__( 'Manual Selection', 'bdthemes-element-pack' ),
				],
				'label_block' => true,
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
				'label_block' => true,
				'multiple'    => true,
				'condition'   => [
					'source'    => 'by_name',
				],
			]
		);

		$this->add_control(
			'posts_limit',
			[
				'label' => esc_html__( 'Posts Limit', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
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

		$this->add_control(
			'title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'     => esc_html__( 'Meta Data', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'     => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'   => esc_html__( 'Excerpt Length', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 15,
				'condition' => [
					'show_excerpt'   => 'yes',
				],
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'     => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);		
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_read_more',
			[
				'label' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
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
				'label' => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .bdt-post-block-modern .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-block-modern .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_left_part',
			[
				'label' => esc_html__( 'Left Part', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'left_part_date_heading',
			[
				'label' => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'left_part_date_color',
			[
				'label' => esc_html__( 'Date Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-meta span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'left_part_date_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-meta span',
			]
		);

		$this->add_control(
			'left_part_category_heading',
			[
				'label' => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'left_part_category_color',
			[
				'label' => esc_html__( 'Category Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'left_part_category_bg_color',
			[
				'label' => esc_html__( 'Category Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-meta a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'left_part_category_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-meta a',
			]
		);

		$this->add_control(
			'left_part_title_category',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'left_part_title_color',
			[
				'label' => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'left_part_title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-title',
			]
		);

		$this->add_control(
			'left_part_excerpt_category',
			[
				'label' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'left_part_excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'left_part_excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .left-part .bdt-post-block-modern-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_right_part',
			[
				'label' => esc_html__( 'Right Part', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'right_part_date_heading',
			[
				'label' => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'right_part_date_color',
			[
				'label' => esc_html__( 'Date Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'right_part_date_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta span',
			]
		);

		$this->add_control(
			'right_part_category_heading',
			[
				'label' => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'right_part_category_color',
			[
				'label' => esc_html__( 'Category Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'right_part_category_bg_color',
			[
				'label' => esc_html__( 'Category Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_border_radius',
			[
				'label' => esc_html__( 'Category Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'right_part_category_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-meta a',
			]
		);

		$this->add_control(
			'right_part_title_category',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'right_part_title_color',
			[
				'label' => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'right_part_title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-title',
			]
		);

		$this->add_control(
			'right_part_excerpt_category',
			[
				'label' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'right_part_excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'right_part_excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .right-part .bdt-post-block-modern-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_read_more',
			[
				'label' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_read_more_style' );

		$this->start_controls_tab(
			'tab_read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'read_more_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'read_more_border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_read_more_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'read_more_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'read_more_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'read_more_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'read_more_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more',
			]
		);

		$this->add_control(
			'read_more_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-read-more',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-meta' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-block-modern .bdt-post-block-modern-desc' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function filter_excerpt_length() {
		return $this->get_settings( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	public function render() {
		$settings = $this->get_settings();
		
		global $post;
		$id      = uniqid('bdtpbm_');
		$classes = ['bdt-post-block-modern', 'bdt-grid', 'bdt-grid-match'];

		$animation = ($settings['read_more_hover_animation']) ? ' elementor-animation-'.$settings['read_more_hover_animation'] : '';

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

			<div id="<?php echo esc_attr($id); ?>" class="<?php echo \element_pack_helper::acssc($classes); ?>">

				<?php $count = 0;
			
				while ( $wp_query->have_posts() ) : $wp_query->the_post();

					$count++;

			  		$post_thumbnail= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );

				  	if( $count == 1) : ?>

				  		<div class="bdt-width-3-5@m">
				  			<div class="bdt-post-block-modern-item left-part bdt-position-relative" style="background-image: url(<?php echo esc_url($post_thumbnail[0]); ?>)">
						  		
						  		<div class="bdt-post-block-modern-desc bdt-position-bottom-center bdt-position-z-index bdt-width-2-3@m ">

					            	<?php if ('yes' == $settings['show_meta']) : ?>

										<?php $meta_list = '<li><span>'.esc_attr(get_the_date('d F Y')).'</span></li><li>'.get_the_category_list(', ').'</li>'; ?>

										<ul class="bdt-post-block-modern-meta bdt-subnav bdt-flex-center"><?php echo wp_kses_post($meta_list); ?></ul>

									<?php endif ?>

									<?php if ('yes' == $settings['title']) : ?>
										<h4 class="bdt-post-block-modern-title"><a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-link-reset" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a></h4>
									<?php endif ?>

									<?php if ('yes' == $settings['show_excerpt']) : ?>
										<div class="bdt-post-block-modern-excerpt"><?php echo wp_kses_post(the_excerpt()); ?></div>
									<?php endif ?>

						  		</div>
						  		<div class="bdt-position-cover bdt-overlay-gradient"></div>

							</div>
						</div>

				  		<div class="bdt-width-2-5@m">
					<?php else : ?>
			  			<div class="bdt-post-block-modern-item right-part">
					  		<div class="bdt-post-block-modern-desc">

								<?php if ('yes' == $settings['show_meta']) : ?>
									<?php $meta_list = '<li><span>'.esc_attr(get_the_date('d F Y')).'</span></li><li>'.get_the_category_list(', ').'</li>'; ?>
									
									<ul class="bdt-post-block-modern-meta bdt-subnav"><?php echo wp_kses_post($meta_list); ?></ul>
								<?php endif ?>

								<?php if ('yes' == $settings['title']) : ?>
									<h4 class="bdt-post-block-modern-title"><a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-link-reset" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a></h4>
								<?php endif ?>

								<?php if ('yes' == $settings['show_excerpt']) : ?>
									<div class="bdt-post-block-modern-excerpt"><?php echo wp_kses_post(the_excerpt()); ?></div>
								<?php endif ?>

								<?php if ('yes' == $settings['show_read_more']) : ?>
									<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-block-modern-read-more bdt-link-reset<?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['read_more_text']); ?>
										
										<?php if ($settings['icon']) : ?>
											<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
												<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
											</span>
										<?php endif; ?>

									</a>
								<?php endif ?>

					  		</div>

						</div>
					<?php endif; ?>
			  
				<?php endwhile; ?>

				</div>
		
			</div>
		
		 	<?php 
				remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
				remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );

				wp_reset_postdata(); 
			?>

 		<?php endif;
	}
}