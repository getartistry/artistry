<?php
namespace ElementPack\Modules\LayerSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Layer_Slider extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-layer-slider';
	}

	public function get_title() {
		return esc_html__( 'Layer Slider', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}


	protected function layer_slider_list() {
        if(shortcode_exists("layerslider")){
            $output = '';
            $sliders = \LS_Sliders::find(array('limit' => 100));

            foreach($sliders as $item) {
            	$name = empty($item['name']) ? 'Unnamed' : htmlspecialchars($item['name']);
            	$output[$item['id']] = $name;
            }

            return $output;
        }
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);


		$slider_list = $this->layer_slider_list();

		$this->add_control(
			'slider_name',
			[
				'label'     => esc_html__( 'Select Slider', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $slider_list,
			]
		);

		$this->add_control(
			'firstslide',
			[
				'label'       => esc_html__( 'First Slide', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'Which slide you want to show first?', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				
			]
		);

		
		$this->end_controls_section();

	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = [
			'id'         => $settings['slider_name'],
			'firstslide' => $settings['firstslide'],
		];

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = [];
		$shortcode[] = sprintf( '[layerslider %s]', $this->get_render_attribute_string( 'shortcode' ) );

		return implode("", $shortcode);
	}

	public function render() {
		echo do_shortcode( $this->get_shortcode() );
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}
}
