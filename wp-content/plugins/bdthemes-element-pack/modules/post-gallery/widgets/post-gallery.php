<?php
namespace ElementPack\Modules\PostGallery\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use ElementPack\Modules\QueryControl\Module;
use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;

use ElementPack\Modules\PostGallery\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Post_Gallery extends Widget_Base {
	private $_query = null;

	public $lightbox_slide_index;

	public function get_name() {
		return 'bdt-post-gallery';
	}

	public function get_title() {
		return esc_html__( 'Post Gallery', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'isotope', 'tilt' ];
	}

	public function _register_skins() {
		$this->add_skin( new Skins\Skin_Abetis( $this ) );
		$this->add_skin( new Skins\Skin_Fedara( $this ) );
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

	public function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'bdthemes-element-pack' ),
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
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'bdthemes-element-pack' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'bdt-post-gallery--thumbnail-size-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label'     => esc_html__( 'Masonry', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'Masonry will not work if you not set filter.', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'columns!' => '1',
				],
			]
		);

		$this->add_control(
			'item_ratio',
			[
				'label'   => esc_html__( 'Item Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 250,
				],
				'range' => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-gallery-thumbnail img' => 'height: {{SIZE}}px',
				],
				'condition' => [
					'masonry!' => 'yes',

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
					'asc' => esc_html__( 'ASC', 'bdthemes-element-pack' ),
					'desc' => esc_html__( 'DESC', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'     => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'condition' => [
					'posts_post_type!' => 'by_id',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'filter_bar',
			[
				'label' => esc_html__( 'Filter Bar', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_filter_bar',
			[
				'label'     => esc_html__( 'Show', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => $this->get_taxonomies(),
				'condition'   => [
					'show_filter_bar' => 'yes',
					'posts_post_type!' => 'by_id',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_additional',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label'   => esc_html__( 'Overlay Animation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => element_pack_transition_options(),
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
			'show_category',
			[
				'label'   => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_link',
			[
				'label'   => esc_html__( 'Show Link', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'post'     => esc_html__('Post Link', 'bdthemes-element-pack'),
					'lightbox' => esc_html__('Lightbox Link', 'bdthemes-element-pack'),
					'both'     => esc_html__('Both', 'bdthemes-element-pack'),
					'none'     => esc_html__('None', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'   => esc_html__( 'Link Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'bdthemes-element-pack'),
					'text' => esc_html__('Text', 'bdthemes-element-pack'),
				],
				'condition' => [
					'show_link!' => 'none',
				]
			]
		);

		$this->add_control(
			'tilt_show',
			[
				'label'   => esc_html__( 'Tilt Effect', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Items', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_gap',
			[
				'label'   => esc_html__( 'Item Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery' => 'margin: 0 -{{SIZE}}px',
					'(desktop){{WRAPPER}} .bdt-gallery-item' => 'width: calc( 100% / {{columns.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(tablet){{WRAPPER}} .bdt-gallery-item' => 'width: calc( 100% / {{columns_tablet.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(mobile){{WRAPPER}} .bdt-gallery-item' => 'width: calc( 100% / {{columns_mobile.SIZE}} ); border: {{SIZE}}px solid transparent',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-thumbnail, {{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'item_skin_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item'           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay'                     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
				'condition' => [
					'_skin!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_background',
			[
				'label'  => esc_html__( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item .bdt-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_gap',
			[
				'label'   => esc_html__( 'Overlay Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item .bdt-overlay' => 'margin: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'overlay_content_alignment',
			[
				'label'       => __( 'Overlay Content Alignment', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'bdt-custom-gallery-skin-fedara-style-',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_content_position',
			[
				'label'       => __( 'Overlay Content Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
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
				'default' => 'middle',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item .bdt-gallery-item-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
					'_skin'      => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-gallery-item .bdt-gallery-item-title',
				'condition' => [
					'show_title' => 'yes',
					'_skin'      => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_link!' => 'none',
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
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link svg'  => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
				'condition' => [
					'link_type' => 'text',
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
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link:hover svg'  => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link:hover span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link:hover span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_filter',
			[
				'label' => esc_html__( 'Filter Bar', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'color_filter',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-filter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color_filter_active',
			[
				'label' => esc_html__( 'Active Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-filter.bdt-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_filter',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-post-gallery-filter',
			]
		);

		$this->add_control(
			'filter_item_spacing',
			[
				'label' => esc_html__( 'Space Between', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-filter:not(:last-child)' => 'margin-right: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .bdt-post-gallery-filter:not(:first-child)' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_control(
			'filter_spacing',
			[
				'label' => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-filters' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
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

	public function get_posts_tags() {
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
		$settings = $this->get_settings();
		$this->query_posts();
		$this->lightbox_slide_index = 0;
		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->get_posts_tags();

		$this->render_loop_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_loop_footer();

		wp_reset_postdata();
		?>
	

		<?php

	}

	public function render_thumbnail() {
		$settings = $this->get_settings();

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size' );
		?>
		<div class="bdt-gallery-thumbnail">
			<?php echo $thumbnail_html ?>
		</div>
		<?php
	}

	public function render_filter_menu() {
		$taxonomy = $this->get_settings( 'taxonomy' );

		if ( ! $taxonomy ) {
			return;
		}

		$terms = [];

		foreach ( $this->_query->posts as $post ) {
			$terms += $post->tags;
		}

		if ( empty( $terms ) ) {
			return;
		}

		usort( $terms, function( $a, $b ) {
			return strcmp( $a->name, $b->name );
		} );

		?>
		<div class="bdt-text-center">
		<ul id="bdt-post-gallery-filters<?php echo $this->get_id(); ?>" class="bdt-post-gallery-filters" bdt-margin>
			<li class="bdt-post-gallery-filter bdt-active" data-filter="*"><?php esc_html_e( 'All', 'bdthemes-element-pack' ); ?></li>
			<?php foreach ( $terms as $term ) { ?>
				<li class="bdt-post-gallery-filter" data-filter=".<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
			<?php } ?>
		</ul>
		</div>
		<?php
	}

	public function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		?>
		<a href="<?php echo get_the_permalink();?>">
			<<?php echo $tag ?> class="bdt-gallery-item-title bdt-margin-remove">
				<?php the_title() ?>
			</<?php echo $tag ?>>
		</a>
		<?php
	}

	public function render_categories_names() {
		if ( ! $this->get_settings( 'show_category' ) ) {
			return;
		}
		global $post;

		if ( ! $post->tags ) {
			return;
		}

		$separator = '<span class="bdt-gallery-item-tag-separator"></span>';

		$tags_array = [];

		foreach ( $post->tags as $tag ) {
			$tags_array[] = '<span class="bdt-gallery-item-tag">' . $tag->name . '</span>';
		}

		?>
		<div class="bdt-gallery-item-tags">
			<?php echo implode( $separator, $tags_array ); ?>
		</div>
		<?php
	}

	public function render_overlay() {
		$settings                    = $this->get_settings();
		$overlay_settings            = [];
		$overlay_settings['class']   = ['bdt-position-cover bdt-overlay bdt-overlay-default'];
		if ($settings['overlay_animation']) {
			$overlay_settings['class'][] = 'bdt-transition-'.$settings['overlay_animation'];
		}

		?>
		<div <?php echo \element_pack_helper::attrs($overlay_settings); ?>>
			<div class="bdt-post-gallery-content">
				<div class="bdt-gallery-content-inner">
					<?php 
					$this->render_title(); 
					$this->render_categories_names();

					$lightbox_settings                                      = [];

					$lightbox_settings['class']                             = ['bdt-gallery-item-link'];
					$lightbox_settings['class'][]                             = ( 'icon' == $settings['link_type'] ) ? 'bdt-link-icon' : 'bdt-link-text';
					$lightbox_settings['class'][]                           = 'elementor-clickable';
					$lightbox_settings['data-elementor-lightbox-slideshow'] = $this->get_id();
					$lightbox_settings['data-elementor-lightbox-index']     = $this->lightbox_slide_index;
					$img_url                                                = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$lightbox_settings['href']                              = $img_url[0];
					$this->lightbox_slide_index++;
					
					?>
					<?php if ( 'none' !== $settings['show_link'])  : ?>
						<div class="bdt-flex-inline bdt-gallery-item-link-wrapper">
							<?php if (( 'lightbox' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] )) : ?>
								<a <?php echo \element_pack_helper::attrs($lightbox_settings); ?>>
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<span bdt-icon="icon: search"></span>
									<?php elseif ( 'text' == $settings['link_type'] ) : ?>
										<span><?php esc_html_e( 'ZOOM', 'bdthemes-element-pack' ); ?></span>
									<?php endif;?>
								</a>
							<?php endif;?>
							
							<?php if (( 'post' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] )) : ?>
								<?php $link_type_class =  ( 'icon' == $settings['link_type'] ) ? ' bdt-link-icon' : ' bdt-link-text'; ?>
								<a class="bdt-gallery-item-link<?php echo esc_attr($link_type_class); ?>" href="<?php echo esc_attr(get_permalink()); ?>">
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<span bdt-icon="icon: link"></span>
									<?php elseif ( 'text' == $settings['link_type'] ) : ?>
										<span><?php esc_html_e( 'VIEW', 'bdthemes-element-pack' ); ?></span>
									<?php endif;?>
								</a>
							<?php endif;?>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_loop_header($skin = 'default') {
		$settings = $this->get_settings();
		if ( $this->get_settings( 'show_filter_bar' ) ) {
			$this->render_filter_menu();
		}
		$masonry = ('yes' === $settings['masonry']) ? ' bdt-masonry-grid' : '';
		?>
		<div id="bdt-post-gallery<?php echo $this->get_id(); ?>" class="bdt-post-gallery filtr-container bdt-post-gallery-skin-<?php echo esc_attr($skin); ?><?php echo esc_attr($masonry); ?>">
		<?php
	}

	public function render_loop_footer() {
		$settings = $this->get_settings();
		?>
		</div>

		<?php if ('yes' === $settings['show_filter_bar'] and isset($settings['taxonomy']) ) : ?>

			<script type="text/javascript">
			    jQuery(document).ready(function($) {
	    			"use strict";
		        	var post_gallery = jQuery('#bdt-post-gallery<?php echo $this->get_id(); ?>').isotope({
		        		itemSelector: '.bdt-gallery-item',
		        	});
			        
			        $('#bdt-post-gallery-filters<?php echo $this->get_id(); ?> li').click(function() {
		        		$('#bdt-post-gallery-filters<?php echo $this->get_id(); ?> li').removeClass('bdt-active');
		        		$(this).addClass('bdt-active');

		        		var selector = $(this).attr('data-filter');
		        		$(post_gallery).isotope({
		        		  filter: selector
		        		});
		        		return false;
		        	});
			    });
			</script>

		<?php endif; ?>
		<?php
	}

	public function render_post() {
		global $post;

		$settings = $this->get_settings();
		$tilt     = ('yes' === $settings['tilt_show']) ? ' data-tilt' : '';

		$item_settigs = [];

		$item_settigs['class'] = [
			'bdt-gallery-item',
			'filtr-item',
			'bdt-transition-toggle',
			
		];

		$tags_classes = array_map( function( $tag ) {
			return $tag->slug;
		}, $post->tags );

		if ($this->get_settings('show_filter_bar')) {
			$item_settigs['class'][] = implode(',', $tags_classes);
		}

		?>
		<div <?php echo \element_pack_helper::attrs($item_settigs); ?><?php echo esc_attr($tilt); ?>>
			
			<?php
			$this->render_thumbnail();
			$this->render_overlay();
			?>

		</div>
		<?php
	}
}
