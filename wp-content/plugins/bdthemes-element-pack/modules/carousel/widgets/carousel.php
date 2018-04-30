<?php
namespace ElementPack\Modules\Carousel\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementPack\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Carousel
 */
class Carousel extends Widget_Base {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	//protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-carousel';
	}

	public function get_title() {
		return __( 'Carousel', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-carousel';
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

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_carousel_layout',
			[
				'label' => __( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => __( 'Columns', 'bdthemes-element-pack' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Limit', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_image',
			[
				'label' => __( 'Image', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'thumbnail_show',
			[
				'label'        => __( 'Thumbnail Show', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'image_position',
				'label'        => __( 'Thumbnail Size', 'bdthemes-element-pack' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'bdt-carousel-thumbnail-size-',
				'condition' => [
					'thumbnail_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'width: {{SIZE}}{{UNIT}};margin-left: auto;margin-right: auto;',
				],
				'condition' => [
					'thumbnail_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label' => __( 'Image Ratio', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% ); top: 0; left: 0; right: 0; bottom: 0;',
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail img' => 'height: 100%; width: auto; position: absolute; top: 50%; left: 50%;
					-webkit-transform: translate(-50%,-50%); transform: translate(-50%,-50%); font-size: {{SIZE}};',
				],
				'condition' => [
					'thumbnail_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_layout_title',
			[
				'label' => __( 'Title', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => __( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => element_pack_title_tags(),
				'default'   => 'h4',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_layout_meta',
			[
				'label' => __( 'Meta', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'meta_data',
			[
				'label'       => __( 'Meta Data', 'bdthemes-element-pack' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => [ 'date', 'comments' ],
				'multiple'    => true,
				'options'     => [
					'author'   => __( 'Author', 'bdthemes-element-pack' ),
					'date'     => __( 'Date', 'bdthemes-element-pack' ),
					'time'     => __( 'Time', 'bdthemes-element-pack' ),
					'comments' => __( 'Comments', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_layout_excerpt',
			[
				'label' => __( 'Excerpt', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'     => __( 'Excerpt', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => __( 'Excerpt Length', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_layout_button',
			[
				'label' => __( 'Readmore Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'     => __( 'Read More', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'       => __( 'Read More Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Read More', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => __( 'Button Size', 'bdthemes-element-pack' ),
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
					'read_more_text!' => '',
					'show_read_more!' => '',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Button Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'condition' => [
					'read_more_text!' => '',
					'show_read_more!' => '',
				],
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

		$this->add_responsive_control(
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
					'{{WRAPPER}} .bdt-carousel .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-carousel .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name' => 'posts',
				'label' => __( 'Posts', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => __( 'Advanced', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date' => __( 'Date', 'bdthemes-element-pack' ),
					'post_title' => __( 'Title', 'bdthemes-element-pack' ),
					'menu_order' => __( 'Menu Order', 'bdthemes-element-pack' ),
					'rand' => __( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'bdthemes-element-pack' ),
					'desc' => __( 'DESC', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => __( 'Carousel Settings', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'match_height',
			[
				'label'        => __( 'Item Match Height', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label'   => __( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'arrows',
				'options' => [
					'both'   => __( 'Arrows and Dots', 'bdthemes-element-pack' ),
					'arrows' => __( 'Arrows', 'bdthemes-element-pack' ),
					'dots'   => __( 'Dots', 'bdthemes-element-pack' ),
					'none'   => __( 'None', 'bdthemes-element-pack' ),
				],
				
			]
		);

		$this->add_control(
			'hide_arrows',
			[
				'label'     => esc_html__( 'Hide arrows on mobile devices?', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'   => __( 'Loop', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Animation Speed', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'min' => 100,
					'max' => 1000,
					'step' => 10,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => __( 'Items', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_item_style');

		$this->start_controls_tab(
			'tab_item_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'item_background',
			[
				'label' => __( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_box_shadow_switcher',
			[
				'label' => __( 'Box Shadow', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
		    'item_box_shadow',
		    [
		        'label' => __( 'Box Shadow', 'your-plugin' ),
		        'type' => Controls_Manager::BOX_SHADOW,
		        'default' => [
		            'color' => 'rgba(0, 0, 0, 0.08)',
		            'blur' => 8,
		            'vertical' => 2,
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .bdt-carousel .bdt-carousel-item' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
		        ],
		        'condition' => [
		        	'item_box_shadow_switcher' => 'yes',
		        ]
		    ]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'label'       => __( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-carousel .bdt-carousel-item',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_gap',
			[
				'label'   => __( 'Item Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'    => '40',
					'bottom' => '40',
					'left'   => '40',
					'right'  => '40',
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_item_hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'item_hover_box_shadow_switcher',
			[
				'label' => __( 'Box Shadow', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
		    'item_hover_box_shadow',
		    [
		        'label' => __( 'Box Shadow', 'your-plugin' ),
		        'type' => Controls_Manager::BOX_SHADOW,
		        'default' => [
		            'color' => 'rgba(0, 0, 0, 0.08)',
		            'blur' => 15,
		            'vertical' => 5,
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .bdt-carousel .bdt-carousel-item:hover' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
		        ],
		        'condition' => [
		        	'item_hover_box_shadow_switcher' => 'yes',
		        ]
		    ]
		);

		$this->add_control(
			'item_hover_border_color',
			[
				'label' => __( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'thumbnail_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_background',
			[
				'label' => __( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_opacity',
			[
				'label' => __( 'Hover Opacity (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-thumbnail'   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-title'   => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-carousel .bdt-carousel-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[
				'label'     => __( 'Meta', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_data!' => '',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-meta span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_hover_color',
			[
				'label'     => __( 'Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-meta span:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-meta'   => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-carousel .bdt-carousel-meta span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label'     => __( 'Excerpt', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .bdt-carousel-excerpt'   => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-carousel .bdt-carousel-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => __( 'Button', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'read_more_text!' => '',
					'show_read_more!' => '',
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
					'{{WRAPPER}} .bdt-carousel-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-carousel-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-carousel-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-carousel-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bdt-carousel-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-carousel-button',
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
					'{{WRAPPER}} .bdt-carousel-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-carousel-button:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel-button:hover' => 'border-color: {{VALUE}};',
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

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => __( 'Arrows', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_style',
			[
				'label' => __( 'Arrows Style', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => [
					'dark' => __( 'Dark', 'bdthemes-element-pack' ),
					'light' => __( 'Light', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => __( 'Arrows Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => -50,
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-carousel .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __( 'Arrows Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 25,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-button-prev, {{WRAPPER}} .bdt-carousel .swiper-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => __( 'Dots', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Dots Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'active_dot_color',
			[
				'label' => __( 'Active Dot Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => __( 'Dots Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -80,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => __( 'Dots Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-carousel .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_meta_data_controls() {
		
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_posts_tags() {
		$taxonomy = $this->get_settings( 'taxonomy' );

		foreach ( $this->_query->posts as $post ) {
			if ( ! $taxonomy ) {
				$post->tags = [];

				continue;
			}

			$tags = wp_get_post_terms( $post->ID, $taxonomy );

			$tags_slugs = [];

			foreach ( $tags as $tag ) {
				$tags_slugs[ $tag->term_id ] = $tag;
			}

			$post->tags = $tags_slugs;
		}
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

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		$this->get_posts_tags();

		$this->render_loop_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_loop_footer();

		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );

		wp_reset_postdata();
	}

	public function filter_excerpt_length() {
		return $this->get_settings( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	protected function render_thumbnail() {
		$settings = $this->get_settings();

		if ( 'yes' !== $settings['thumbnail_show'] ) {
			return;
		}

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size' );
		?>
		<div class="bdt-carousel-thumbnail">
			<a href="<?php echo get_permalink() ?>">
				<?php echo $thumbnail_html ?>
			</a>
		</div>
		<?php
	}

	protected function render_meta_data() {
		$settings = $this->get_settings( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}
		?>
		<ul class="bdt-carousel-meta bdt-subnav bdt-margin-small-top" bdt-margin>
			<?php
			if ( in_array( 'author', $settings ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings ) ) {
				$this->render_date();
			}

			if ( in_array( 'time', $settings ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings ) ) {
				$this->render_comments();
			}
			?>
		</ul>
		<?php
	}

	protected function render_author() {
		?>
		<li class="pc-author">
			<span><?php the_author(); ?></span>
		</li>
		<?php
	}

	protected function render_date() {
		?>
		<li class="pc-date">
			<span><?php echo apply_filters( 'the_date', get_the_date('M j, Y'), get_option( 'date_format' ), '', '' ); ?></span>
		</li>
		<?php
	}

	protected function render_time() {
		?>
		<li class="pc-time">
			<span><?php the_time(); ?></span>
		</li>
		<?php
	}

	protected function render_comments() {
		?>
		<li class="pc-avatar">
			<span><?php comments_number(); ?></span>
		</li>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		$classes = ['bdt-carousel-title bdt-margin-small-bottom', 'bdt-margin-remove-top']
		?>

		<<?php echo $tag ?> class="<?php echo implode(" ", $classes); ?>">
		<a href="<?php echo get_permalink() ?>">
			<?php the_title() ?>
		</a>
		</<?php echo $tag ?>>
		<?php
	}

	protected function render_excerpt() {
		if ( ! $this->get_settings( 'show_excerpt' ) ) {
			return;
		}
		?>
		<div class="bdt-carousel-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

	protected function render_read_more() {
		if ( ! $this->get_settings( 'show_read_more' ) ) {
			return;
		}

		$settings    = $this->get_settings();
		$animation   = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';

		?>
		<a class="bdt-carousel-button elementor-button elementor-size-<?php echo esc_attr($settings['button_size'] . $animation); ?>" href="<?php echo get_permalink(); ?>">
			<?php echo $this->get_settings( 'read_more_text' ); ?> 
			<?php if ($settings['icon']) : ?>
				<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
					<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
				</span>
			<?php endif; ?>
		</a>
		<?php
	}

	protected function render_post_header() {
		global $post;

		$tags_classes = array_map( function( $tag ) {
			return 'bdt-carousel-filter-' . $tag->term_id;
		}, $post->tags );

		$classes = [
			'bdt-carousel-item',
			'swiper-slide',
			implode( ' ', $tags_classes ),
		];

		?>
		<div <?php post_class( $classes ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</div>
		<?php
	}

	protected function render_overlay_header() {
		$classes = ['bdt-carousel-desc'];
		if ($this->get_settings('item_padding') == '') :
			$classes[] = 'bdt-margin-top';
		endif; ?>
		<div class="<?php echo implode(" ", $classes);?>">
		<?php
	}

	protected function render_overlay_footer() {
		?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		$id = $this->get_id();
		$settings = $this->get_settings();
		$match_height = ( 'yes' == $settings['match_height'] ) ? ' bdt-height-match="target: > div > div > .bdt-carousel-item"' : '';
		?>
		<div id="bdt-carousel-<?php echo $id;?>" class="bdt-carousel"<?php echo $match_height; ?>>
			<div class="swiper-container">
				<div class="swiper-wrapper">
		<?php
	}

	protected function render_loop_footer() {
		$id          = $this->get_id();
		$settings    = $this->get_settings();
		$hide_arrows = ( 'yes' == $settings['hide_arrows'] ) ? ' bdt-visible@m' : '';
		?>
				</div>
			</div>
			
			<?php if ( 'none' !== $settings['navigation'] ) : ?>
				<?php if ( 'arrows' !== $settings['navigation'] ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				
				<?php if ( 'dots' !== $settings['navigation'] ) : 
					$nav_style = ($settings['arrows_style'] == 'light') ? 'swiper-button-white' : 'swiper-button-black'; 
				?>
					<div class="swiper-button-next <?php echo esc_attr($nav_style.$hide_arrows); ?>"></div>
					<div class="swiper-button-prev <?php echo esc_attr($nav_style.$hide_arrows); ?>"></div>
				<?php endif; ?>
			<?php endif; ?>
			
			
		</div>

		<script>
			jQuery(document).ready(function($) {
			    "use strict";				    
			    var swiper = new Swiper("#bdt-carousel-<?php echo $id;?> .swiper-container", {
			        "pagination": "#bdt-carousel-<?php echo $id;?> .swiper-pagination",
			        "paginationClickable":true,
			        "nextButton": "#bdt-carousel-<?php echo $id;?> .swiper-button-next",
			        "prevButton": "#bdt-carousel-<?php echo $id;?> .swiper-button-prev",
			        "autoplay": <?php echo ($settings['autoplay'] == 'yes') ? $settings['autoplay_speed']['size']*100 : 'false'; ?>,
			        "loop": <?php echo ($settings['loop'] == 'yes') ? 'true' : 'false'; ?>,
			        "speed": <?php echo $settings['speed']['size']*10; ?>,
			        "slidesPerView": <?php echo $settings['columns']; ?>,
			        "spaceBetween":  <?php echo $settings['item_gap']['size']; ?>,
			        "breakpoints" : {
			            "1024" : {
			            	"slidesPerView": <?php echo $settings['columns']; ?>,
			            	"spaceBetween": <?php echo $settings['item_gap']['size']; ?>,
			            },
			            "768" : {
			            	"slidesPerView": <?php echo $settings['columns_tablet']; ?>,
			            	"spaceBetween": <?php echo $settings['item_gap']['size']; ?>,
			            },
			            "640" : {
			            	"slidesPerView": <?php echo $settings['columns_mobile']; ?>,
			            	"spaceBetween": <?php echo $settings['item_gap']['size']; ?>,
			            }
			        }
			    });
			});
		</script>
		<?php
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_overlay_header();
		$this->render_title();
		$this->render_meta_data();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_overlay_footer();
		$this->render_post_footer();
	}
}
