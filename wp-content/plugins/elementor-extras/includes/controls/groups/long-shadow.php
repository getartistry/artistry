<?php
namespace ElementorExtras;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom long shadow group control
 *
 * @since 0.1.0
 */
class Group_Control_Long_Shadow extends Group_Control_Base {

	protected static $fields;

	/**
	 * @since 0.1.0
	 * @access public
	 */
	public static function get_type() {
		return 'long-shadow';
	}

	/**
	 * @since 0.1.0
	 * @access protected
	 */
	protected function init_fields() {
		$controls = [];

		$controls['enable'] = [
			'label'			=> _x( 'Long Shadow', 'Long Shadow Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SWITCHER,
			'default' 		=> '',
			'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			'label_off' 	=> __( 'No', 'elementor-extras' ),
			'return_value' 	=> 'yes',
			'frontend_available' => true,
		];

		$controls['color'] = [
			'label'			=> _x( 'Color', 'Long Shadow Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::COLOR,
			'scheme' 	=> [
			    'type' 	=> Scheme_Color::get_type(),
			    'value' => Scheme_Color::COLOR_1,
			],
			'condition' => [
				'enable!' => ''
			],
			'frontend_available' => true,
		];

		$controls['size'] = [
			'label'			=> _x( 'Size', 'Long Shadow Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SLIDER,
			'default' 	=> [
				'size' 	=> 50,
			],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'condition' => [
				'enable!' => ''
			],
			'frontend_available' => true,
		];

		$controls['direction'] = [
			'label' 		=> _x( 'Direction', 'Long Shadow Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'options' 	=> [
				'top' 			=> __( 'Top', 'elementor-extras' ),
				'top-right' 	=> __( 'Top Right', 'elementor-extras' ),
				'right' 		=> __( 'Right', 'elementor-extras' ),
				'bottom-right' 	=> __( 'Bottom Right', 'elementor-extras' ),
				'bottom' 		=> __( 'Bottom', 'elementor-extras' ),
				'bottom-left' 	=> __( 'Bottom Left', 'elementor-extras' ),
				'left' 			=> __( 'Left', 'elementor-extras' ),
				'top-left' 		=> __( 'Top Left', 'elementor-extras' ),
			],
			'condition' => [
				'enable!' => ''
			],
			'default' 		=> 'bottom-right',
			'frontend_available' => true,
		];

		return $controls;
	}

	/**
	 * @since 0.1.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
