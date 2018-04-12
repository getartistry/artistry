<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Section_Heading extends Widget_Base {

	public function get_name() {
		return 'case27-section-heading-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Section Heading', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-animation-text';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'the_section_heading_controls',
			['label' => esc_html__( 'Section Heading', 'my-listing' ),]
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
    		'title_color',
    		[
    		    'label' => __( 'Title Color', 'my-listing' ),
    		    'type' => Controls_Manager::COLOR,
    		    'default' => '#000000',
    		    'scheme' => [
    		        'type' => Scheme_Color::get_type(),
    		        'value' => Scheme_Color::COLOR_1,
    		    ],
    		    'selectors' => [
    		        '{{WRAPPER}} .i-section .section-title h2' => 'color: {{VALUE}}',
    		    ],
    		]
		);


		$this->add_control(
			'the_subtitle',
			[
				'label' => __( 'Subtitle', 'my-listing' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
    		'subtitle_color',
    		[
    		    'label' => __( 'Subtitle Color', 'my-listing' ),
    		    'type' => Controls_Manager::COLOR,
    		    'default' => c27()->get_setting('general_brand_color', '#f24286'),
    		    'scheme' => [
    		        'type' => Scheme_Color::get_type(),
    		        'value' => Scheme_Color::COLOR_1,
    		    ],
    		    'selectors' => [
    		        '{{WRAPPER}} .i-section .section-title p' => 'color: {{VALUE}}',
    		    ],
    		]
		);


		$this->add_control(
			'the_content',
			[
				'label' => __( 'Content', 'my-listing' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
			]
		);

		$this->add_control(
			'section_inner_spacing',
			[
			   	'label'   => __( 'Inner Padding', 'my-listing' ),
			   	'type'    => Controls_Manager::DIMENSIONS,
			   	'allowed_dimensions' => ['top', 'bottom'],
			   	'default' => ['top' => 65, 'bottom' => 65],
			   	'selectors' => [
    		        '{{WRAPPER}} .i-section' => 'padding: {{TOP}}px 0 {{BOTTOM}}px',
    		    ],
			]
		);

		$traits->choose_overlay(__( 'Set an overlay', 'my-listing' ), '27_overlay');

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('section-heading', [
			'title' => $this->get_settings('the_title'),
			'subtitle' => $this->get_settings('the_subtitle'),
			'content' => $this->get_settings('the_content'),
			'overlay_type' => $this->get_settings('27_overlay'),
			'overlay_gradient' => $this->get_settings('27_overlay__gradient'),
			'overlay_solid_color' => $this->get_settings('27_overlay__solid_color'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Section_Heading() );
