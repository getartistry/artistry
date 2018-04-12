<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Add_Listing extends Widget_Base {

	public function get_name() {
		return 'case27-add-listing-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Add Listing Form', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-form-horizontal';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		// $traits->header();
		// $traits->content();

		$this->start_controls_section(
			'the_listing_feed',
			['label' => esc_html__( 'Add Listing Form', 'my-listing' ),]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'my-listing' ),
				'default' => __( 'Add listing', 'my-listing' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Card Size', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'medium',
				'options' => [
					'small' => __( 'Small', 'my-listing' ),
					'medium' => __( 'Medium', 'my-listing' ),
					'large' => __( 'Large', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'listing_types',
			[
				'label' => __( 'Listing Type(s)', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'listing_type',
						'label' => __( 'Listing Type', 'my-listing' ),
						'type' => Controls_Manager::SELECT2,
						'options' => c27()->get_posts_dropdown_array([
							'post_type' => 'case27_listing_type',
							'posts_per_page' => -1,
							], 'post_name'),
					],
					[
						'name' => 'color',
						'label' => __( 'Color', 'my-listing' ),
						'type' => Controls_Manager::COLOR,
					]
				],
				'title_field' => '{{{ listing_type.toUpperCase() }}}',
			]
		);

		$this->end_controls_section();

		$traits->sizing();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('add-listing', [
			'title' => $this->get_settings('title'),
			'listing_types' => $this->get_settings('listing_types'),
			'size' => $this->get_settings('size'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Add_Listing() );
