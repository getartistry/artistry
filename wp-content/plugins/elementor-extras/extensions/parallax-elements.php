<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Parallax extenstion
 *
 * Adds parallax on widgets and columns
 *
 * @since 1.1.3
 */
class Extension_Parallax_Elements extends Extension_Base {

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
			'parallax-element',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {
		return __( 'Adds options to move a column or a widget vertically asynchronously when scrolling the page. Can be found under Advanced &rarr; Extras &rarr; Parallax.', 'elementor-extras' );
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

		// Activate sections for columns
		add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

	}

	/**
	 * Add Actions
	 *
	 * @since 1.1.3
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_control(
			'parallax_element_enable', [
				'label'			=> __( 'Parallax', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elementor-extras' ),
				'label_off' 	=> __( 'No', 'elementor-extras' ),
				'return_value' 	=> 'yes',
				'separator'		=> 'before',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'parallax_element_type', [
				'label' 		=> __( 'Type', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'scroll',
				'options' 			=> [
					'scroll' 	=> __( 'Scroll', 'elementor-extras' ),
					'mouse' 	=> __( 'Mouse', 'elementor-extras' ),
				],
				'condition' => [
					'parallax_element_enable!' => '',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'parallax_element_relative', [
				'label' 		=> __( 'Relative to', 'elementor-extras' ),
				'description' 	=> __( 'Use "Start position" when the element is visible inside the viewport before scroll.', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'middle',
				'options' 			=> [
					'middle' 		=> __( 'Viewport middle', 'elementor-extras' ),
					'position' 		=> __( 'Start position', 'elementor-extras' ),
				],
				'condition' => [
					'parallax_element_enable!' 	=> '',
					'parallax_element_type'		=> 'scroll',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'parallax_element_pan_relative', [
				'label' 		=> __( 'Relative to', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'element',
				'options' 			=> [
					'element' 		=> __( 'Element Center', 'elementor-extras' ),
					'viewport' 		=> __( 'Viewport Center', 'elementor-extras' ),
				],
				'condition' => [
					'parallax_element_enable!' 	=> '',
					'parallax_element_type'		=> 'mouse',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'parallax_element_disable_on', [
				'label' 	=> __( 'Disable for', 'elementor-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'mobile',
				'options' 			=> [
					'none' 		=> __( 'None', 'elementor-extras' ),
					'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
					'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
				],
				'condition' => [
					'parallax_element_enable!' 	=> '',
					'parallax_element_type'		=> 'scroll',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'parallax_element_pan_axis',
			[
				'label' 	=> __( 'Axis', 'elementor-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default'	=> 'both',
				'options' 	=> [
					'both' 			=> __( 'Both', 'elementor-extras' ),
					'vertical' 		=> __( 'Vertical', 'elementor-extras' ),
					'horizontal' 	=> __( 'Horizontal', 'elementor-extras' ),
				],
				'frontend_available' => true,
				'condition' => [
					'parallax_element_enable!' 	=> '',
					'parallax_element_type'		=> 'mouse',
				],
			]
		);

		$element->add_control(
			'parallax_element_invert', [
				'label'			=> __( 'Invert Direction', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elementor-extras' ),
				'label_off' 	=> __( 'No', 'elementor-extras' ),
				'return_value' 	=> 'yes',
				'frontend_available' => true,
				'condition' => [
					'parallax_element_enable!' 	=> '',
				],
			]
		);

		$element->add_responsive_control(
			'parallax_element_pan_distance', [
				'label' 		=> __( 'Max Distance (px)', 'elementor-extras' ),
				'description' 	=> __( 'The maximum distance from the center of the element and the mouse pointer. Enter 0 or empty to disable.', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' 		=> [
						'min'	=> 0,
						'max' 	=> 500,
						'step'	=> 1,
					],
				],
				'condition' => [
					'parallax_element_enable!' 			=> '',
					'parallax_element_type'				=> 'mouse',
					'parallax_element_pan_relative'		=> 'element',
				],
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'parallax_element_speed', [
				'label' 		=> __( 'Amount', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'default'		=> [ 'size' => 0 ],
				'range' 		=> [
					'px' 		=> [
						'min'	=> 0.1,
						'max' 	=> 1,
						'step'	=> 0.01,
					],
				],
				'condition' => [
					'parallax_element_enable!' 	=> '',
				],
				'frontend_available' => true,
			]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 1.1.3
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate controls for columns
		add_action( 'elementor/element/column/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

	}

}