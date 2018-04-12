<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;
use ElementorExtras\Group_Control_Sticky;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sticky Extension
 *
 * Adds sticky on scroll capability to widgets and sections
 *
 * @since 0.1.0
 */
class Extension_Sticky_Elements extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 1.8.0
	 * @access private
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 1.8.0
	 **/
	public function get_script_depends() {
		return [
			'sticky-kit',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {
		return __( 'Adds an option to make any widget or section sticky when scrolling to it\'s position. Can be found under Advanced &rarr; Extras &rarr; Sticky.', 'elementor-extras' );
	}

	/**
	 * Add common sections
	 *
	 * @since 1.8.0
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {

		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for sections
		add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

	}

	/**
	 * Add Controls
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$sticky_control_args = [ 'name' => 'sticky', 'render_type' ];

		if ( $element->get_name() === 'section' ) {
			
			$element->add_control(
				'sticky_warning',
				[
					'type' 					=> Controls_Manager::RAW_HTML,
					'raw' 					=> __( 'You cannot make this section sticky if the "Stretch Section" is enabled. To make it work, use a section within a section, make the outer section stretched and the inner section sticky.', 'elementor-extras' ),
					'content_classes' 		=> 'ee-raw-html ee-raw-html__danger',
					'condition'				=> [
						'stretch_section' 	=> 'section-stretched'
					]
				]
			);

			// $sticky_control_args['condition'] = [
			// 	'stretch_section!' => 'section-stretched',
			// ];
		}

		$element->add_group_control( Group_Control_Sticky::get_type(), $sticky_control_args );

	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate controls for sections
		add_action( 'elementor/element/section/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );
	}

}