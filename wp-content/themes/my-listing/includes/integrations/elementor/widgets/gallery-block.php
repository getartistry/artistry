<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Gallery_Block extends Widget_Base {

	public function get_name() {
		return 'case27-gallery-block-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Gallery Block', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-thumbnails-down';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'section_gallery_block',
			[
				'label' => esc_html__( 'Content', 'my-listing' ),
			]
		);

		$this->add_control(
			'the_icon',
			[
			'label' => __( 'Icon', 'my-listing' ),
			'type' => Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'the_title',
			[
				'label' => __( 'Title', 'my-listing' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);


		$this->add_control(
			'the_gallery_type',
			[
				'label' => __( 'Gallery Type', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'carousel',
				'options' => [
					'carousel' => __( 'Carousel', 'my-listing' ),
					'carousel-with-preview' => __( 'Carousel with item preview', 'my-listing' ),
				],
			]
		);


		$this->add_control(
			'the_gallery_items',
			[
				'label' => __( 'Gallery Items', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'item',
						'label' => __( 'Choose Image', 'my-listing' ),
						'type' => Controls_Manager::MEDIA,
					],
				],
				'title_field' => __( 'Gallery Item', 'my-listing' ),
			]
		);


		$this->add_control(
			'the_items_per_row',
			[
				'label' => __( 'Items per row', 'my-listing' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'the_items_per_row_mobile',
			[
				'label' => __( 'Items per row (Mobile Devices)', 'my-listing' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
			]
		);

		$this->add_control(
			'the_height',
			[
				'label' => __( 'Height', 'my-listing' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 120,
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 750,
					],
					'vh' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-carousel .item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['the_gallery_type' => 'carousel'],
			]
		);

		$this->add_control(
			'the_preview_height',
			[
				'label' => __( 'Height', 'my-listing' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 750,
					],
					'vh' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gallerySlider .galleryPreview a' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['the_gallery_type' => 'carousel-with-preview'],
			]
		);

		$this->end_controls_section();

		$traits->block_styles();
	}


	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('gallery-block', [
			'icon' => $this->get_settings('the_icon'),
			'icon_style' => $this->get_settings('the_icon_style'),
			'title' => $this->get_settings('the_title'),
			'gallery_items' => $this->get_settings('the_gallery_items'),
			'items_per_row' => $this->get_settings('the_items_per_row'),
			'items_per_row_mobile' => $this->get_settings('the_items_per_row_mobile'),
			'gallery_type' => $this->get_settings('the_gallery_type'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			]);
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Gallery_Block() );
