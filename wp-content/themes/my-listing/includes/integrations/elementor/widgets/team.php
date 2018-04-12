<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Team extends Widget_Base {

	public function get_name() {
		return 'case27-team-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Team', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-gallery-grid';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'section_content_block',
			[
				'label' => esc_html__( 'Content', 'my-listing' ),
			]
		);

		// Because Elementor doesn't support (yet) nested repeaters,
		// a simple workaround is to provide a fixed amount (e.g. 5) of rows to set social links.
		$social_links_list = [
			[
				'name' => 'select_social_networks',
				'label' => __( 'Links', 'my-listing' ),
				'type' => Controls_Manager::HEADING,
			]
		];

		for ($i = 1; $i <= 5; $i++) {
			$social_links_list[] = [
				'name' => 'social_network_icon__' . $i,
				'label' => __( 'Icon', 'my-listing' ),
				'type' => Controls_Manager::ICON,
			];

			$social_links_list[] = [
				'name' => 'social_network_link__' . $i,
				'label' => __( 'URL', 'my-listing' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => '',
					'is_external' => true,
				],
				'show_external' => false,
				'separator' => 'after',
			];
		}

		$this->add_control(
			'the_members',
			[
				'label' => __( 'Team Members', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => array_merge([
					[
						'name' => 'name',
						'label' => __( 'Name', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'position',
						'label' => __( 'Position', 'my-listing' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'image',
						'label' => __( 'Image', 'my-listing' ),
						'type' => Controls_Manager::MEDIA,
					],
				], $social_links_list),
				'title_field' => '{{{ name }}}',
			]
		);

		$traits->choose_overlay(__( 'Set an overlay', 'my-listing' ), '27_overlay');

		$this->end_controls_section();

		$traits->sizing();
	}


	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('team', [
			'members' => $this->get_settings('the_members'),
			'overlay_type' => $this->get_settings('27_overlay'),
			'overlay_gradient' => $this->get_settings('27_overlay__gradient'),
			'overlay_solid_color' => $this->get_settings('27_overlay__solid_color'),
			]);
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Team() );
