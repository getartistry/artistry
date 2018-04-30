<?php
namespace ElementPack\Modules\PostGrid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Grid extends Widget_Base {

	public function get_name() {
		return 'bdt-post-grid';
	}

	public function get_title() {
		return esc_html__( 'Post Grid', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-posts-group';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label'   => esc_html__( 'Column Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'small',
				'options' => [
					'small'    => esc_html__( 'Small', 'bdthemes-element-pack' ),
					'medium'   => esc_html__( 'Medium', 'bdthemes-element-pack' ),
					'large'    => esc_html__( 'Large', 'bdthemes-element-pack' ),
					'collapse' => esc_html__( 'Collapse', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_responsive_control(
			'primary_item_height',
			[
				'label'   => esc_html__( 'Primary Item Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min'  => 100,
						'max'  => 800,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-primary .bdt-post-grid-img-wrap a' => 'height: {{SIZE}}px',
				],
			]
		);

		$this->add_responsive_control(
			'secondary_item_height',
			[
				'label'   => esc_html__( 'Secondary Item Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
				],
				'range' => [
					'px' => [
						'min'  => 100,
						'max'  => 800,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-secondary .bdt-post-grid-img-wrap a' => 'height: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_query',
			[
				'label' => esc_html__( 'Query', 'bdthemes-element-pack' ),
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
			'section_content_additional',
			[
				'label' => esc_html__( 'Additional', 'bdthemes-element-pack' ),
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
			'show_author',
			[
				'label'   => esc_html__( 'Author', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'   => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label'   => esc_html__( 'Comments', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label'   => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Length', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_readmore',
			[
				'label' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label'       => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'condition'   => [
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
					'{{WRAPPER}} .bdt-post-grid .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-grid .bdt-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_animation',
			[
				'label'   => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scale-up',
				'options' => [
					'scale-up'            => esc_html__( 'Scale-Up', 'bdthemes-element-pack' ),
					'scale-down'          => esc_html__( 'Scale-Down', 'bdthemes-element-pack' ),
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
				'label'     => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'   => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-primary .bdt-post-grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-grid .bdt-secondary .bdt-post-grid-title' => 'margin-bottom: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-title a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_author',
			[
				'label' => esc_html__( 'Author', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#adb5bd',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-author' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-author',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#adb5bd',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'date_divider_color',
			[
				'label'     => esc_html__( 'Divider Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#adb5bd',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-subnav span + span:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-date',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_comments',
			[
				'label' => esc_html__( 'Comments', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$this->add_control(
			'comments_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-comments *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'comments_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-comments',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_category',
			[
				'label' => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_color',
			[
				'label'     => esc_html__( 'Category Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-category span a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-category span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-category span',
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
				'label'     => esc_html__( 'Excerpt Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label'   => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-excerpt' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_readmore',
			[
				'label' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore' => 'background-color: {{VALUE}};',
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
				'selector'    => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore',
				'separator'   => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'readmore_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore',
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_spacing',
			[
				'label'   => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore',
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
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-grid .bdt-post-grid-readmore:hover' => 'border-color: {{VALUE}};',
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

	public function render_image($image_id, $size) {

		$image_src = wp_get_attachment_image_src( $image_id, $size );

		if ( ! $image_src ) {
			return;
		}

		echo 
			'<div class="bdt-post-grid-img-wrap bdt-overflow-hidden">
				<a href="' . esc_url(get_permalink()) . '" class="bdt-transition-' . esc_attr($this->get_settings('image_animation')) . ' bdt-background-cover bdt-transition-opaque bdt-flex" title="' . esc_attr(get_the_title()) . '" style="background-image: url(' . esc_url($image_src[0]) . ')">
  				</a>
			</div>';
	}

	public function render_title() {

		if ( ! $this->get_settings('show_title') ) {
			return;
		}
		
		echo 
			'<h4 class="bdt-post-grid-title">
				<a href="' . esc_url(get_permalink()) . '" class="bdt-post-grid-link" title="' . esc_attr(get_the_title()) . '">
					' . esc_html(get_the_title())  . '
				</a>
			</h4>';
	}

	public function render_author() {

		if ( ! $this->get_settings('show_author') ) {
			return;
		}
		
		echo 
			'<span class="bdt-post-grid-author">'.get_the_author().'</span>';		
	}

	public function render_date() {

		if ( ! $this->get_settings('show_date') ) {
			return;
		}
		
		echo 
			'<span class="bdt-post-grid-date">'.esc_attr(get_the_date('M d, Y')).'</span>';		
	}

	public function render_comments() {

		if ( ! $this->get_settings('show_comments') ) {
			return;
		}
		
		echo 
			'<div class="bdt-post-grid-comments bdt-position-medium bdt-position-bottom-right"><span><i class="fa fa-comments-o" aria-hidden="true"></i> '.get_comments_number().'</span></div>';
	}

	public function render_category() {

		if ( ! $this->get_settings('show_category') ) {
			return;
		}
		
		echo 
			'<div class="bdt-post-grid-category bdt-position-top-left"><span>'.get_the_category_list(', ').'</span></div>';
	}

	public function render_excerpt() {

		if ( ! $this->get_settings('show_excerpt') ) {
			return;
		}
		
		echo 
			'<div class="bdt-post-grid-excerpt">' . wp_kses_post(get_the_excerpt()) . '</div>';
	}

	public function render_readmore() {

		if ( ! $this->get_settings('show_readmore') ) {
			return;
		}
		
		$animation = ($this->get_settings('readmore_hover_animation')) ? ' elementor-animation-'.$this->get_settings('readmore_hover_animation') : '';

		echo 
			'<a href="' . esc_url(get_permalink()) . '" class="bdt-post-grid-readmore bdt-display-inline-block' . esc_attr($animation) . '">
				' . esc_html($this->get_settings('readmore_text'));
				
				if ($this->get_settings('icon')) :
					echo
						'<span class="bdt-button-icon-align-' . esc_attr($this->get_settings('icon_align')) . '">
							<i class="' . esc_attr($this->get_settings('icon')) . '"></i>
						</span>';
				endif;
		echo
			'</a>';
	}

	protected function render() {
		$settings = $this->get_settings();
		
		global $post;
		$id      = $this->get_id();
		$classes = ['bdt-post-grid', 'bdt-post-grid-skin-default'];

		$args = array(
			'posts_per_page' => 5,
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
			<div id="bdt-post-grid-<?php echo esc_attr($id); ?>" class="<?php echo \element_pack_helper::acssc($classes); ?>">
		  		<div class="bdt-grid bdt-grid-<?php echo esc_attr($settings['column_gap']); ?>" bdt-grid>

					<?php $bdt_count = 0;
				
					while ($wp_query->have_posts()) :
						$wp_query->the_post();
							
			  			$bdt_count++;
			  			$bdt_grid_raw = ( $bdt_count <= 2) ? 2 : 3;
			  			$bdt_post_class = ( $bdt_count <= 2) ? ' bdt-primary' : ' bdt-secondary';
			  			?>

			  			<div class="bdt-width-1-<?php echo esc_attr($bdt_grid_raw); ?>@m<?php echo esc_attr($bdt_post_class); ?>">
				  			<div class="bdt-post-grid-item bdt-transition-toggle bdt-position-relative">
								
								<?php $this->render_image(get_post_thumbnail_id( $post->ID ), 'full'); ?>
								<div class="bdt-custom-overlay bdt-position-cover"></div>
						  		
						  		<div class="bdt-post-grid-desc bdt-position-medium bdt-position-bottom-left">

									<?php $this->render_title(); ?>

	            	            	<?php if ('yes' == $settings['show_date']) : ?>
	            						<div class="bdt-post-grid-meta bdt-subnav">
											<?php $this->render_author(); ?>
											<?php $this->render_date(); ?>
	            						</div>
	            					<?php endif; ?>

									<?php $this->render_excerpt(); ?>
									<?php $this->render_readmore(); ?>

								</div>

								<?php $this->render_category(); ?>
								<?php $this->render_comments(); ?>

							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		
	 		<?php 
			remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
			remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
			wp_reset_postdata();
		endif;
	}
}