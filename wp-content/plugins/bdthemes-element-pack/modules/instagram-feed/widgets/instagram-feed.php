<?php
namespace ElementPack\Modules\InstagramFeed\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Instagram_Feed extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-instagram-feed';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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

		/*$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'user',
				'options' => [
					'user'        => esc_html__( 'User', 'bdthemes-element-pack' ),
					'hashtag'     => esc_html__( 'Hashtag', 'bdthemes-element-pack' ),
					'location'    => esc_html__( 'Location', 'bdthemes-element-pack' ),
					'coordinates' => esc_html__( 'Coordinates', 'bdthemes-element-pack' ),
				],
			]
		);*/

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit (Upper Limit 33)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 12,
			]
		);

		$this->add_control(
			'cols',
			[
				'label' => esc_html__( 'Column (Upper Limit 10)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->add_control(
			'imageres',
			[
				'label'   => esc_html__( 'Image Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'auto'   => esc_html__( 'Auto', 'bdthemes-element-pack' ),
					'full'   => esc_html__( 'Full', 'bdthemes-element-pack' ),
					'medium' => esc_html__( 'Medium', 'bdthemes-element-pack' ),
					'thumb'  => esc_html__( 'Thumb', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'showheader',
			[
				'label'     => esc_html__( 'Show Header', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'showbutton',
			[
				'label'     => esc_html__( 'Show Button', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'buttontext',
			[
				'label'       => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Load More...', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'Load More...', 'bdthemes-element-pack' ),
				'label_block' => true,
				'condition' => [
					'showbutton' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'showfollow',
			[
				'label'     => esc_html__( 'Show Follow', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'followtext',
			[
				'label'       => esc_html__( 'Follow Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Follow on Instagram', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'Follow on Instagram', 'bdthemes-element-pack' ),
				'label_block' => true,
				'condition' => [
					'showfollow' => [ 'yes' ],
				],
			]
		);
	
		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_style',
			[
				'label' => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'imagepadding',
			[
				'label' => esc_html__( 'Image Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_control(
			'headercolor',
			[
				'label' => esc_html__( 'Header Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'showheader' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'buttoncolor',
			[
				'label' => esc_html__( 'Button Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'showbutton' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'buttontextcolor',
			[
				'label' => esc_html__( 'Button Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'showbutton' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'followcolor',
			[
				'label' => esc_html__( 'Follow Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'showfollow' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'followtextcolor',
			[
				'label' => esc_html__( 'Follow Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'showfollow' => [ 'yes' ],
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = [
			//'type'             => $settings['type'],
			'num'              => $settings['limit'],
			'cols'             => $settings['cols'],
			'imageres'         => $settings['imageres'],
			'imagepadding'     => $settings['imagepadding']['size'],
			'imagepaddingunit' =>'px',
			'showheader'       => ($settings['showheader'] =='yes') ? 'true' : 'false',
			'showbutton'       => ($settings['showbutton'] =='yes') ? 'true' : 'false',
			'showfollow'       => ($settings['showfollow'] =='yes') ? 'true' : 'false',
			'headercolor'      => $settings['headercolor'],
			'buttoncolor'      => $settings['buttoncolor'],
			'buttontextcolor'  => $settings['buttontextcolor'],
			'buttontext'       => $settings['buttontext'],
			'followcolor'      => $settings['followcolor'],
			'followtextcolor'  => $settings['followtextcolor'],
			'followtext'       => $settings['followtext'],
		];

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = [];
		$shortcode[] = sprintf( '[instagram-feed %s]', $this->get_render_attribute_string( 'shortcode' ) );

		return implode("", $shortcode);
	}

	public function render() {
		echo do_shortcode( $this->get_shortcode() );
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}
}
