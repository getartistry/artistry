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
 * @since 1.2.0
 */
class Extension_Parallax_Background extends Extension_Base {

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 1.8.0
	 **/
	public function get_script_depends() {
		return [
			'parallax-background',
			'jquery-resize',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {
		return __( 'Adds parallax options for the background image of a section. Can be found under Style &rarr; Background &rarr; Extras if a background image is selected.', 'elementor-extras' );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.2.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_control(
			'extras_heading',
			[
				'type'		=> Controls_Manager::HEADING,
				'label' 	=> __( 'Extras', 'elementor-extras' ),
				'separator' => 'before',
				'condition'				=> [
					'background_background' 	=> [ 'classic' ],
					'background_image[url]!' 	=> '',
				]
			]
		);

		$element->add_control(
			'parallax_background_enable',
			[
				'label'					=> _x( 'Parallax Background', 'Parallax Background', 'elementor-extras' ),
				'type' 					=> Controls_Manager::SWITCHER,
				'default' 				=> '',
				'label_on' 				=> __( 'Yes', 'elementor-extras' ),
				'label_off' 			=> __( 'No', 'elementor-extras' ),
				'return_value' 			=> 'yes',
				'frontend_available' 	=> true,
				'condition'				=> [
					'background_background' 	=> [ 'classic' ],
					'background_image[url]!' 	=> '',
				]
			]
		);

		$element->add_control(
			'parallax_background_speed',
			[
				'responsive'	=> true,
				'label' 		=> _x( 'Parallax Speed', 'Parallax Control', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'default'		=> [
					'size'		=> 0.5
					],
				'range' 		=> [
					'px' 		=> [
						'min'	=> 0,
						'max' 	=> 1,
						'step'	=> 0.01,
					],
				],
				'condition' => [
					'parallax_background_enable!' => '',
					'background_background' 	=> [ 'classic' ],
					'background_image[url]!' 	=> '',
				],
				'frontend_available' => true,
			]
		);


		$element->add_control(
			'parallax_background_direction',
			[
				'label' 	=> _x( 'Parallax Direction', 'Parallax Control', 'elementor-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'down',
				'options' 			=> [
					'up' 	=> __( 'Up', 'elementor-extras' ),
					'down' 	=> __( 'Down', 'elementor-extras' ),
					'left' 	=> __( 'Left', 'elementor-extras' ),
					'right' => __( 'Right', 'elementor-extras' ),
				],
				'condition' => [
					'parallax_background_enable!' => '',
					'background_background' 	=> [ 'classic' ],
					'background_image[url]!' 	=> '',
				],
				'frontend_available' => true,
			]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 1.2.0
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate for widgets
		add_action( 'elementor/element/section/section_background/before_section_end', function( $element, $args ) {

			// Make the background image url available in the frontend
			$element->update_control( 'background_image', array(
				'selectors' => [
					'{{WRAPPER}} .ee-parallax__inner' 	=> 'background-image: url("{{URL}}");',
					'{{WRAPPER}}' 						=> 'background-image: url("{{URL}}");',
				],
				'frontend_available' => true,
			));

			$element->update_control( 'background_position', array(
				'selectors' => [
					'{{WRAPPER}} .ee-parallax__inner' 	=> 'background-position: {{VALUE}};',
					'{{WRAPPER}}' 						=> 'background-position: {{VALUE}};',
				],
			));

			$element->update_control( 'background_repeat', array(
				'selectors' => [
					'{{WRAPPER}} .ee-parallax__inner' 	=> 'background-repeat: {{VALUE}};',
					'{{WRAPPER}}' 						=> 'background-repeat: {{VALUE}};',
				],
			));

			$element->update_control( 'background_size', array(
				'selectors' => [
					'{{WRAPPER}} .ee-parallax__inner' 	=> 'background-size: {{VALUE}};',
					'{{WRAPPER}}' 						=> 'background-size: {{VALUE}};',
				],
			));

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate for columns

	}

}