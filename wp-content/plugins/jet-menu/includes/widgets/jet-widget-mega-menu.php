<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * HTML Widget
 */
class Jet_Widget_Mega_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jet-mega-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Mega Menu', 'jet-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Menu', 'jet-menu' ),
			)
		);

		$parent = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( $parent ) {
			$this->add_control(
				'menu_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => esc_html__( 'This module can\'t be used inside Mega Menu content. Please, use it to show selected Mega Menu on specific page.', 'jet-menu' )
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'label'   => esc_html__( 'Select Menu', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_available_menus(),
				)
			);

			do_action( 'jet-menu/widgets/mega-menu/controls', $this );

		}


		$this->end_controls_section();

	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
		$parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
			unset( $menus[ $parent ] );
		}

		return $menus;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ( ! $settings['menu'] ) {
			return;
		}

		$args = array(
			'menu' => $settings['menu'],
		);

		$preset = isset( $settings['preset'] ) ? absint( $settings['preset'] ) : 0;

		if ( 0 !== $preset ) {
			$preset_options = get_post_meta( $preset, jet_menu_options_presets()->settings_key, true );
			jet_menu_option_page()->pre_set_options( $preset_options );
		} else {
			jet_menu_option_page()->pre_set_options( false );
		}

		$args = array_merge( $args, jet_menu_public_manager()->get_mega_nav_args( $preset ) );

		jet_menu_public_manager()->set_elementor_mode();
		wp_nav_menu( $args );
		jet_menu_public_manager()->reset_elementor_mode();

		if ( $this->is_css_required() ) {
			$dynamic_css = jet_menu()->dynamic_css();
			add_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
			$dynamic_css::$collector->print_style();
			remove_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
		}

	}

	/**
	 * Check if need to insert custom CSS
	 * @return boolean [description]
	 */
	public function is_css_required() {

		$allowed_actions = array( 'elementor_render_widget', 'elementor' );

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $allowed_actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Fix preview styles
	 *
	 * @return void
	 */
	public function fix_preview_css( $data ) {

		if ( ! empty( $data['css'] ) ) {
			printf( '<style>%s</style>', html_entity_decode( $data['css'] ) );
		}

		return $data;
	}

}
