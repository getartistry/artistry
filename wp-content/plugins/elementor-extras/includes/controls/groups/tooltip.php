<?php
namespace ElementorExtras;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Group Control Tooltip
 *
 * @since 1.8.0
 */
class Group_Control_Tooltip extends Group_Control_Base {

	protected static $fields;

	/**
	 * @since 1.8.0
	 * @access public
	 */
	public static function get_type() {
		return 'ee-tooltip';
	}

	/**
	 * @since 1.8.0
	 * @access protected
	 */
	protected function init_fields() {
		$controls = [];

		$controls['content'] = [
			'label'			=> _x( 'Content', 'Tooltip Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'I am a tooltip', 'elementor-extras' ),
			'frontend_available'	=> true,
		];

		$controls['target'] = [
			'label'		=> __( 'Target', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> 'element',
			'options' 	=> [
				'element' 	=> __( 'Element', 'elementor-extras' ),
				'custom' 	=> __( 'Custom', 'elementor-extras' ),
			],
			'frontend_available' => true
		];

		$controls['selector'] = [
			'label'			=> _x( 'CSS Selector', 'Tooltip Control', 'elementor-extras' ),
			'description'	=> __( 'Use a CSS selector for any html element WITHIN this element.', 'elementor-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> '',
			'placeholder' 	=> __( '.css-selector', 'elementor-extras' ),
			'frontend_available'	=> true,
			'condition'	=> [
				'target' => 'custom',
			],
		];

		$controls['trigger'] = [
			'label'		=> __( 'Trigger', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> 'hover',
			'options' 	=> [
				'hover' 	=> __( 'Hover', 'elementor-extras' ),
				'click' 	=> __( 'Click', 'elementor-extras' ),
			],
			'frontend_available' => true
		];

		$controls['position'] = [
			'label'			=> _x( 'Position', 'Tooltip Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> '',
			'options' 	=> [
				'' 			=> __( 'Global', 'elementor-extras' ),
				'bottom' 	=> __( 'Bottom', 'elementor-extras' ),
				'left' 		=> __( 'Left', 'elementor-extras' ),
				'top' 		=> __( 'Top', 'elementor-extras' ),
				'right' 	=> __( 'Right', 'elementor-extras' ),
			],
			'frontend_available' => true
		];

		$controls['delay_in'] = [
			'label' 		=> _x( 'Delay in (s)', 'Tooltip Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SLIDER,
			'range' 	=> [
				'px' 	=> [
					'min' 	=> 0,
					'max' 	=> 1,
					'step'	=> 0.1,
				],
			],
			'frontend_available' => true
		];

		$controls['delay_out'] = [
			'label' 		=> _x( 'Delay out (s)', 'Tooltip Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SLIDER,
			'range' 	=> [
				'px' 	=> [
					'min' 	=> 0,
					'max' 	=> 1,
					'step'	=> 0.1,
				],
			],
			'frontend_available' => true
		];

		$controls['duration'] = [
			'label' 		=> _x( 'Duration', 'Tooltip Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SLIDER,
			'range' 	=> [
				'px' 	=> [
					'min' 	=> 0,
					'max' 	=> 2,
					'step'	=> 0.1,
				],
			],
			'frontend_available' => true
		];

		return $controls;
	}

	/**
	 * @since 1.8.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
