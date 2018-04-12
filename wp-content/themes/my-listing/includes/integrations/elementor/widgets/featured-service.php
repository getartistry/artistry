<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Featured_Service extends Widget_Base {

	public function get_name() {
		return 'case27-featured-service-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Featured Service', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-info-box';
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
			'the_content',
			[
				'label' => __( 'Image Style', 'my-listing' ),
				'type' => Controls_Manager::WYSIWYG,
			]
		);

		$this->add_control(
			'the_position',
			[
				'label' => __( 'Position', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'left',
				'options' => ['left' => __( 'Left', 'my-listing' ), 'right' => __( 'Right', 'my-listing' )],
			]
		);

		$this->end_controls_section();

		$traits->sizing();

		// $traits->footer();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('featured-service', [
			'image' => $this->get_settings('the_image'),
			'content' => $this->get_settings('the_content'),
			'position' => $this->get_settings('the_position'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Featured_Service() );
