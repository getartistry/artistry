<?php
namespace ElementPack\Modules\CustomCarousel\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Embed;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_Carousel extends Widget_Base {

	private $lightbox_slide_index;
	private $slide_prints_count = 0;

	public function get_name() {
		return 'bdt-custom-carousel';
	}

	public function get_title() {
		return esc_html__( 'Custom Carousel', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {


		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'skin',
			[
				'label'   => esc_html__( 'Skin', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'carousel'  => esc_html__( 'Carousel', 'bdthemes-element-pack' ),
					'coverflow' => esc_html__( 'Coverflow', 'bdthemes-element-pack' ),
				],
				'prefix_class' => 'bdt-custom-carousel-style-',
				'render_type'  => 'template',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Type', 'bdthemes-element-pack' ),
				'default' => 'image',
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-image',
					],
					'video' => [
						'title' => esc_html__( 'Video', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-video-camera',
					],
				],
				'label_block' => false,
				'toggle'      => false,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'image_link_to_type',
			[
				'label'   => esc_html__( 'Link to', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''       => esc_html__( 'None', 'bdthemes-element-pack' ),
					'file'   => esc_html__( 'Media File', 'bdthemes-element-pack' ),
					'custom' => esc_html__( 'Custom URL', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'image_link_to',
			[
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'http://your-link.com', 'bdthemes-element-pack' ),
				'condition'   => [
					'type' => 'image',
					'image_link_to_type' => 'custom',
				],
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'video',
			[
				'label'         => esc_html__( 'Video Link', 'bdthemes-element-pack' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'Enter your video link', 'bdthemes-element-pack' ),
				'description'   => esc_html__( 'Insert YouTube or Vimeo link', 'bdthemes-element-pack' ),
				'show_external' => false,
				'condition'     => [
					'type' => 'video',
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_fields(),
				'default' => $this->get_repeater_defaults(),
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type'           => Controls_Manager::SELECT,
				'label'          => esc_html__( 'Slides Per View', 'bdthemes-element-pack' ),
				'options'        => $slides_per_view,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Slides to Scroll', 'bdthemes-element-pack' ),
				'options'   => $slides_per_view,
				'default'   => '1',
				'condition' => [
					'skin' => 'carousel',
				]
			]
		);

		$this->add_control(
			'slides_per_column',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Slides Per Column', 'bdthemes-element-pack' ),
				'options'   => $slides_per_view,
				'default'   => '1',
				'condition' => [
					'skin' => 'carousel',
				]
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Height', 'bdthemes-element-pack' ),
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type'  => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'bdthemes-element-pack' ),
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
					'%' => [
						'min' => 50,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'default'      => 'yes',
				'label_off'    => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on'     => esc_html__( 'Show', 'bdthemes-element-pack' ),
				'prefix_class' => 'elementor-arrows-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'hide_arrows',
			[
				'label'     => esc_html__( 'Hide arrows on mobile devices?', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'show_arrows' => [ 'yes' ],
				],
			]
		);

		

		$this->add_control(
			'pagination',
			[
				'label'   => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					''         => esc_html__( 'None', 'bdthemes-element-pack' ),
					'bullets'  => esc_html__( 'Dots', 'bdthemes-element-pack' ),
					'fraction' => esc_html__( 'Fraction', 'bdthemes-element-pack' ),
					'progress' => esc_html__( 'Progress', 'bdthemes-element-pack' ),
				],
				'prefix_class' => 'elementor-pagination-type-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => esc_html__( 'Transition Duration', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'   => esc_html__( 'Infinite Loop', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label'     => esc_html__( 'Pause on Interaction', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);


		$this->add_control(
			'overlay',
			[
				'label' => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''     => esc_html__( 'None', 'bdthemes-element-pack' ),
					'text' => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'icon' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => esc_html__( 'Caption', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'caption' => esc_html__( 'Caption', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'overlay' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'plus-circle',
				'options' => [
					'search' => [
						'icon' => 'fa fa-search-plus',
					],
					'plus-circle' => [
						'icon' => 'fa fa-plus-circle',
					],
					'plus' => [
						'icon' => 'fa fa-plus',
					],
					'link' => [
						'icon' => 'fa fa-link',
					],
					'play-circle' => [
						'icon' => 'fa fa-play-circle-o',
					],
				],
				'condition' => [
					'overlay' => 'icon',
				],
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label'     => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => element_pack_transition_options(),
				'condition' => [
					'overlay!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'default'   => 'full',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_fit',
			[
				'label'   => esc_html__( 'Image Fit', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => esc_html__( 'Cover', 'bdthemes-element-pack' ),
					'contain' => esc_html__( 'Contain', 'bdthemes-element-pack' ),
					'auto'    => esc_html__( 'Auto', 'bdthemes-element-pack' ),
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-container .bdt-custom-carousel-thumbnail' => 'background-size: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => esc_html__( 'Slides', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'desktop_default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_size',
			[
				'label'     => esc_html__( 'Border Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_padding',
			[
				'label'     => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'slide_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-container .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
				'condition' => [
					'show_arrows' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 32,
				],
				'range' => [
					'px' => [
						'min' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel div[class*="bdt-custom-carousel-arrow"] svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
				'condition' => [
					'show_arrows' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Arrows Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel div[class*="bdt-custom-carousel-arrow"] polyline' => 'stroke: {{VALUE}}'
				],
				'condition' => [
					'show_arrows' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'heading_pagination',
			[
				'label'     => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label'   => esc_html__( 'Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => esc_html__( 'Outside', 'bdthemes-element-pack' ),
					'inside'  => esc_html__( 'Inside', 'bdthemes-element-pack' ),
				],
				'prefix_class' => 'elementor-pagination-position-',
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_size',
			[
				'label' => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progress' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet, {{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label'     => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'overlay!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel-item .bdt-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel-item .bdt-overlay' => 'color: {{VALUE}};',
				],
				'condition' => [
					'overlay' => 'text',
				],
			]
		);

		$this->add_control(
			'overlay_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel-item .bdt-overlay' => 'color: {{VALUE}};',
				],
				'condition' => [
					'overlay' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-carousel-item .bdt-overlay svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'overlay' => 'icon',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'caption_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .bdt-custom-carousel-item .bdt-overlay',
				'condition' => [
					'overlay' => 'text',
				],
			]
		);

		

		$this->end_controls_section();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => esc_html__( 'Lightbox', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label'     => esc_html__( 'UI Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_hover_color',
			[
				'label'     => esc_html__( 'UI Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_video_width',
			[
				'label'   => esc_html__( 'Video Width', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'units'   => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		
	}

	protected function get_default_slides_count() {
		return 5;
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array_fill( 0, $this->get_default_slides_count(), [
			'image' => [
				'url' => $placeholder_image_src,
			],
		] );
	}

	protected function get_image_caption( $slide ) {
		$caption_type = $this->get_settings( 'caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $slide['image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}
	}

	protected function get_image_link_to( $slide ) {
		if ( $slide['video']['url'] ) {
			return $slide['image']['url'];
		}

		if ( ! $slide['image_link_to_type'] ) {
			return '';
		}

		if ( 'custom' === $slide['image_link_to_type'] ) {
			return $slide['image_link_to']['url'];
		}

		return $slide['image']['url'];
	}

	protected function _print_slider( array $settings = null ) {
		$this->lightbox_slide_index = 0;

		$this->print_slider( $settings );
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		if ( ! empty( $settings['thumbs_slider'] ) ) {
			$settings['video_play_icon'] = false;
			$this->add_render_attribute( $element_key . '-image', 'class', 'elementor-fit-aspect-ratio' );
		}

		$this->add_render_attribute( $element_key . '-image', [
			'class' => 'bdt-custom-carousel-thumbnail',
			'style' => 'background-image: url(' . $this->get_slide_image_url( $slide, $settings ) . ')',
		] );

		$image_link_to = $this->get_image_link_to( $slide );

		if ( $image_link_to ) {
			$this->add_render_attribute( $element_key . '_link', 'href', $image_link_to );

			if ( 'custom' === $slide['image_link_to_type'] ) {
				if ( $slide['image_link_to']['is_external'] ) {
					$this->add_render_attribute( $element_key . '_link', 'target', '_blank' );
				}

				if ( $slide['image_link_to']['nofollow'] ) {
					$this->add_render_attribute( $element_key . '_link', 'nofollow', '' );
				}
			} else {
				$this->add_render_attribute( $element_key . '_link', [
					'class' => 'elementor-clickable',
					'data-elementor-lightbox-slideshow' => $this->get_id(),
					'data-elementor-lightbox-index' => $this->lightbox_slide_index,
				] );

				$this->lightbox_slide_index++;
			}

			if ( 'video' === $slide['type'] && $slide['video']['url'] ) {
				$embed_url_params = [
					'autoplay' => 1,
					'rel' => 0,
					'controls' => 0,
					'showinfo' => 0,
				];

				$this->add_render_attribute( $element_key . '_link', 'data-elementor-lightbox-video', Embed::get_embed_url( $slide['video']['url'], $embed_url_params ) );
			}

			echo '<a ' . $this->get_render_attribute_string( $element_key . '_link' ) . '>';
		}

		$this->print_slide_image( $slide, $element_key, $settings );

		if ( $image_link_to ) {
			echo '</a>';
		}
	}

	protected function print_slide_image( array $slide, $element_key, array $settings ) {
		$overlay_settings          = [];
		$overlay_settings['class'] = ['bdt-position-cover bdt-position-small bdt-overlay bdt-overlay-default bdt-flex bdt-flex-center bdt-flex-middle'];
		if ($settings['overlay_animation']) {
			$overlay_settings['class'][] = 'bdt-transition-'.$settings['overlay_animation'];
		}

		?>
		<div <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>></div>
		
		<?php if ( $settings['overlay'] ) : ?>
			<div <?php echo \element_pack_helper::attrs($overlay_settings); ?>>
				<?php if ( 'text' === $settings['overlay'] ) : ?>
					<?php echo $this->get_image_caption( $slide ); ?>
				<?php else : ?>
					<span class="bdt-icon" bdt-icon="icon: <?php echo esc_attr($settings['icon']); ?>"></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
	}

	protected function render() {
		$settings = $this->get_active_settings();

		$this->_print_slider();

		$this->carousel_script();	
		
	}

	protected function carousel_script() {
		$id            = $this->get_id();
		$settings      = $this->get_settings();
		
		$swiper_script = [
			'pagination'          => '#bdt-custom-carousel-'. $id . ' .swiper-pagination',
			'paginationType'      => $settings['pagination'],
			'paginationClickable' => true,
			'nextButton'          => '#bdt-custom-carousel-'. $id .' .bdt-custom-carousel-arrow-next',
			'prevButton'          => '#bdt-custom-carousel-'. $id .' .bdt-custom-carousel-arrow-prev',
			'loop'                => ($settings['loop'] === 'yes') ? true : false,
			'speed'               => $settings['speed'],
			'slidesPerView'       => $settings['slides_per_view'],
			'grabCursor'          => true,
			'effect'              => $settings['skin'],
			'spaceBetween'        => $settings['space_between']['size'],
		];

		if ($settings['autoplay'] === 'yes') {
			$swiper_script['autoplay'] = $settings['autoplay_speed'];
		}
		if ($settings['skin'] === 'carousel' and $settings['slides_per_column'] > 1) {
			$swiper_script['slidesPerColumn'] = $settings['slides_per_column'];
		}
		if ($settings['slides_to_scroll'] < 1) {
			$swiper_script['slidesPerGroup']  = $settings['slides_to_scroll'];
		}

		$swiper_script['breakpoints'] = [
		    '1024' => [
				'slidesPerView' => $settings['slides_per_view'],
				'spaceBetween'  => $settings['space_between']['size'],
		    ],
		    '768' => [
				'slidesPerView' => $settings['slides_per_view_tablet'],
				'spaceBetween'  => $settings['space_between']['size'],
		    ],
		    '640' => [
				'slidesPerView' => $settings['slides_per_view_mobile'],
				'spaceBetween'  => $settings['space_between']['size'],
		    ]
		];



		?>
		<script>
		jQuery(document).ready(function($) {
		    "use strict";				    
		    var swiper = new Swiper("#bdt-custom-carousel-<?php echo $id;?> .swiper-container", 
		    	<?php echo wp_json_encode( $swiper_script ); ?>
		    );
		});
		</script>
		<?php 
	}

	protected function print_slider( array $settings = null ) {
		$id = $this->get_id();
		
		if ( null === $settings ) {
			$settings = $this->get_active_settings();
		}

		$default_settings = [ 'video_play_icon' => true ];
		$settings         = array_merge( $default_settings, $settings );
		$slides_count     = count( $settings['slides'] );

		?>
		<div id="bdt-custom-carousel-<?php echo $id;?>" class="bdt-custom-carousel elementor-swiper">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide bdt-custom-carousel-item bdt-transition-toggle">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				
				<?php $this->print_navigation_control(); ?>

			</div>
		</div>
		<?php
	}

	protected function print_navigation_control() {

		$settings     = $this->get_settings();
		$slides_count = count( $settings['slides'] );
		$hide_arrows  = ( 'yes' == $settings['hide_arrows'] ) ? ' bdt-visible@m' : '';

		if ( 1 < $slides_count ) : ?>
			<?php if ( $settings['pagination'] ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ( $settings['show_arrows'] ) : ?>
				<div class="bdt-custom-carousel-arrow-next bdt-position-center-right bdt-position-z-index <?php echo esc_attr($hide_arrows); ?>">
					<a href="#" bdt-icon="icon: chevron-right;"></a>
				</div>
				<div class="bdt-custom-carousel-arrow-prev bdt-position-center-left bdt-position-z-index <?php echo esc_attr($hide_arrows); ?>">
					<a href="#" bdt-icon="icon: chevron-left;"></a>
				</div>
			<?php endif; ?>
		<?php endif;
	}

	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}
}
