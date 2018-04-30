<?php
namespace ElementPack\Modules\RevolutionSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Revolution_Slider extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-revolution-slider';
	}

	public function get_title() {
		return esc_html__( 'Revolution Slider', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);


		$this->add_control(
			'slider_name',
			[
				'label'   => esc_html__( 'Select Slider', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => element_pack_rev_slider_options(),
			]
		);

		
		$this->end_controls_section();

	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = [
			'alias'             => $settings['slider_name'],
		];

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = [];
		$shortcode[] = sprintf( '[rev_slider %s]', $this->get_render_attribute_string( 'shortcode' ) );

		return implode("", $shortcode);
	}

	public function render() {
		echo do_shortcode( $this->get_shortcode() );
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}
}
