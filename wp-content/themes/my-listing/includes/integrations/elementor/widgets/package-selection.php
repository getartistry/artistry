<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Package_Selection extends Widget_Base {

	public function get_name() {
		return 'case27-package-selection-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Package Selection', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-price-table';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'the_package_selection',
			['label' => esc_html__( 'Package Selection', 'my-listing' ),]
		);

		$packages = \CASE27\Integrations\PaidListings\PaidListings::get_packages();
		$packagesFormatted = [];

		foreach ($packages as $pckg) {
			$packagesFormatted[$pckg->ID] = $pckg->post_title;
		}

		$this->add_control(
			'the_packages',
			[
				'label' => __( 'Select Packages', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'package',
						'label' => __( 'Choose package', 'my-listing' ),
						'type' => Controls_Manager::SELECT2,
						'options' => $packagesFormatted,
					],
					[
						'name' => 'featured',
						'label' => __( 'Featured?', 'my-listing' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_on' => __( 'Yes', 'my-listing' ),
						'label_off' => __( 'No', 'my-listing' ),
						'return_value' => 'yes',
					],
				],
				'title_field' => 'Package ID: {{{ package }}}',
			]
		);

		$this->add_control(
			'the_submit_page',
			[
				'label' => __( 'Submit to Page:', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'options' => c27()->get_posts_dropdown_array([
					'post_type' => 'page',
					'posts_per_page' => -1,
				]),
			]
		);

		$this->end_controls_section();

		$traits->sizing();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('package-selection', [
			'packages' => $this->get_settings('the_packages'),
			'submit_page' => $this->get_settings('the_submit_page'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Package_Selection() );
