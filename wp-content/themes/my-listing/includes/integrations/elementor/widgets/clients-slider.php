<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Logo_Slider extends Widget_Base {

	public function get_name() {
		return 'case27-logo-slider-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Clients Slider', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-carousel';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		// $traits->header();
		// $traits->content();

		$this->start_controls_section(
			'the_logo_slider_section',
			['label' => esc_html__( 'Clients Slider', 'my-listing' ),]
		);


		$this->add_control(
			'the_items',
			[
				'label' => __( 'Clients', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'client_name',
						'label' => __( 'Client Name', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'client_url',
						'label' => __( 'Client Website', 'my-listing' ),
						'type' => Controls_Manager::URL,
						'show_external' => true,
					],
					[
						'name' => 'client_logo',
						'label' => __( 'Client Logo', 'my-listing' ),
						'type' => Controls_Manager::MEDIA,
					],
				],
				'title_field' => '{{{ client_name }}}',
			]
		);

		$this->end_controls_section();

		$traits->sizing();

		// $traits->footer();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('clients-slider', [
			'items' => $this->get_settings('the_items'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Logo_Slider() );
