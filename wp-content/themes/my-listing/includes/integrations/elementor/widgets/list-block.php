<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_List_Block extends Widget_Base {

	public function get_name() {
		return 'case27-list-block-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > List Block', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-bullet-list';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'section_content_block',
			[
				'label' => esc_html__( 'Content', 'my-listing' ),
			]
		);

		$this->add_control(
			'the_icon',
			[
			'label' => __( 'Icon', 'my-listing' ),
			'type' => Controls_Manager::ICON,
			]
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
			'the_items',
			[
				'label' => __( 'Content', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'type',
						'label' => __( 'Type', 'my-listing' ),
						'type' => Controls_Manager::SELECT2,
						'default' => 'plain_text',
						'options' => [
							'plain_text' => __( 'Plain Text', 'my-listing' ),
							'link' => __( 'Link', 'my-listing' ),
						],
					],
					[
						'name' => 'title',
						'label' => __( 'Title', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'icon',
						'label' => __( 'Icon', 'my-listing' ),
						'type' => Controls_Manager::ICON,
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'my-listing' ),
						'type' => Controls_Manager::URL,
						'default' => [
							'url' => 'http://',
							'is_external' => false,
						],
						'show_external' => true,
						'condition' => [
							'type' => 'link',
						]
					],
					[
						'name' => 'link_hover_color',
						'label' => __( 'Icon Hover Color', 'my-listing' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$traits->block_styles();
	}


	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('list-block', [
			'icon' => $this->get_settings('the_icon'),
			'icon_style' => $this->get_settings('the_icon_style'),
			'title' => $this->get_settings('the_title'),
			'items' => $this->get_settings('the_items'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_List_Block() );
