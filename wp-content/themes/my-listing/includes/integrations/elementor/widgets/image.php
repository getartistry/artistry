<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Image extends Widget_Base {

	public function get_name() {
		return 'case27-image-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Image', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-image-before-after';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		// $traits->header();
		// $traits->content();

		$this->start_controls_section(
			'the_image_section',
			['label' => esc_html__( 'Image', 'my-listing' ),]
		);


		$this->add_control(
			'the_image',
			[
				'label' => __( 'Choose Image', 'my-listing' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
				'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'the_style',
			[
				'label' => __( 'Image Style', 'my-listing' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __( 'Style 1', 'my-listing' ),
					'2' => __( 'Style 2', 'my-listing' ),
				],
			]
		);


		$this->end_controls_section();

		// $traits->sizing();

		// $traits->footer();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('image', [
			'image' => $this->get_settings('the_image'),
			'style' => $this->get_settings('the_style'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Image() );
