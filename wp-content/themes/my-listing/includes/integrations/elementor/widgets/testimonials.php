<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Testimonials extends Widget_Base {

	public function get_name() {
		return 'case27-testimonials-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Testimonials', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-slider-device';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		// $traits->header();
		// $traits->content();

		$this->start_controls_section(
			'the_testimonials_section',
			['label' => esc_html__( 'Testimonials', 'my-listing' ),]
		);

		$this->add_control(
			'the_testimonials',
			[
				'label' => __( 'Testimonials', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'author',
						'label' => __( 'Author', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'author_image',
						'label' => __( 'Author Image', 'my-listing' ),
						'type' => Controls_Manager::MEDIA,
					],
					[
						'name' => 'company',
						'label' => __( 'Company', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'content',
						'label' => __( 'Content', 'my-listing' ),
						'type' => Controls_Manager::TEXTAREA,
					],
				],
				'title_field' => '{{{ author }}}',
			]
		);


		$this->end_controls_section();

		$traits->sizing();

		// $traits->footer();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('testimonials', [
			'testimonials' => $this->get_settings('the_testimonials'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Testimonials() );
