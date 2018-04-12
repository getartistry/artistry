<?php
namespace ElementorExtras;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom effect group control
 *
 * @since 1.4.0
 */
class Group_Control_Button_Effect extends Group_Control_Base {

	protected static $fields;

	private static $_types;
	private static $_directions;
	private static $_easings;
	private static $_entrances;
	private static $_shapes;
	private static $_filters;

	/**
	 * @since 1.4.0
	 * @access public
	 */
	public static function get_type() {
		return 'effect';
	}

	/**
	 * Retrieve the effect types
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array.  $_types The available array of effects
	 */
	public static function get_types() {
		if ( is_null( self::$_types ) ) {
			self::$_types = [
				'' 			=> __( 'None', 'elementor-extras' ),
				'clone' 	=> __( 'Clone', 'elementor-extras' ),
				'flip' 		=> __( 'Flip', 'elementor-extras' ),
				'back' 		=> __( 'Background', 'elementor-extras' ),
				'3d' 		=> __( '3D', 'elementor-extras' ),
				'cube' 		=> __( 'Cube', 'elementor-extras' ),
			];
		}

		return self::$_types;
	}

	/**
	 * Retrieve the filters array
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array.  $_filters The current array of filters
	 */
	public static function get_filters() {
		if ( is_null( self::$_filters ) ) {
			self::$_filters = [
				'displace' 	=> __( 'Displace', 'elementor-extras' ),
				'blur' 		=> __( 'Blur', 'elementor-extras' ),
			];
		}

		return self::$_filters;
	}

	/**
	 * Retrieve the entrance type
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array.  $_entrances The current array of entrance animation types
	 */
	public static function get_entrances() {
		if ( is_null( self::$_entrances ) ) {
			self::$_entrances = [
				'cover' 	=> __( 'Cover', 'elementor-extras' ),
				'move' 		=> __( 'Move', 'elementor-extras' ),
				'push' 		=> __( 'Push', 'elementor-extras' ),
			];
		}

		return self::$_entrances;
	}

	/**
	 * Retrieve the easings array
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array.  $_easings The current array of easing types
	 */
	public static function get_easings() {
		if ( is_null( self::$_easings ) ) {
			self::$_easings = [
				'linear' 		=> __( 'Linear', 'elementor-extras' ),
				'ease-in' 		=> __( 'Ease In', 'elementor-extras' ),
				'ease-out' 		=> __( 'Ease Out', 'elementor-extras' ),
				'ease-in-out' 	=> __( 'Ease In Out', 'elementor-extras' ),
			];
		}

		return self::$_easings;
	}

	/**
	 * @since 1.4.0
	 * @access protected
	 */
	protected function init_fields() {
		$controls = [];

		$controls['heading'] = [
			'label'			=> _x( 'Effect', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::HEADING,
			'separator' 	=> 'before',
		];

		$controls['type'] = [
			'label'			=> _x( 'Type', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> '',
			'options'		=> self::get_types(),
		];

		$controls['entrance'] = [
			'label'			=> _x( 'Entrance', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'cover',
			'options'		=> self::get_entrances(),
			'condition' 	=> [
				'type' 		=> [ 'clone', 'icon' ]
			]
		];

		$controls['text'] = [
			'label'			=> _x( 'Text', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> '',
			'condition'		=> [
				'type' 		=> [ 'clone', 'flip', 'cube' ]
			]
		];

		$controls['direction'] = [
			'label'			=> _x( 'Direction', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::CHOOSE,
			'default' 		=> 'down',
			'options' => [
				'down' 		=> [
					'title' => __( 'Down', 'elementor-extras' ),
					'icon' 	=> 'eicon-v-align-bottom',
				],
				'up'    	=> [
					'title' => __( 'Up', 'elementor-extras' ),
					'icon' 	=> 'eicon-v-align-top',
				],
				'right' 	=> [
					'title' => __( 'Right', 'elementor-extras' ),
					'icon' 	=> 'eicon-h-align-right',
				],
				'left' 		=> [
					'title' => __( 'Left', 'elementor-extras' ),
					'icon' 	=> 'eicon-h-align-left',
				],
			],
			'label_block' 	=> false,
			'condition' 	=> [
				'type' 		=> [ 'clone', 'back', '3d', 'flip', 'cube' ]
			]
		];

		$controls['orientation'] = [
			'label'			=> _x( 'Orientation', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'horizontal',
			'options' => [
				'horizontal' 	=> __( 'Horizontal', 'elementor-extras' ),
				'vertical' 		=> __( 'Vertical', 'elementor-extras' ),
			],
			'condition' 	=> [
				'direction' => '',
				'type' 		=> [ 'back' ]
			]
		];

		$controls['shape'] = [
			'label'			=> _x( 'Shape', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::CHOOSE,
			'default' 		=> '',
			'options' => [
				''    	=> [
					'title' => __( 'Square', 'elementor-extras' ),
					'icon' 	=> 'nicon nicon-shape-square',
				],
				'round' 	=> [
					'title' => __( 'Round', 'elementor-extras' ),
					'icon' 	=> 'nicon nicon-shape-round',
				],
				'skewed' 	=> [
					'title' => __( 'Skewed', 'elementor-extras' ),
					'icon' 	=> 'nicon nicon-shape-skewed',
				],
			],
			'label_block' 	=> false,
			'condition' 	=> [
				'type' 		=> [ 'clone', 'back' ]
			]
		];

		$controls['double'] = [
			'label' 		=> __( 'Double', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SWITCHER,
			'default' 		=> '',
			'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			'label_off' 	=> __( 'No', 'elementor-extras' ),
			'return_value' 	=> 'double',
			'condition' 	=> [
				'type' 		=> [ 'back' ],
			]
		];

		$controls['color'] = [
			'label' 	=> __( 'Effect Color', 'elementor-extras' ),
			'type' 		=> Controls_Manager::COLOR,
			'default' 	=> '#FFFFFF',
			'selectors' => [
				'{{SELECTOR}}.ee-effect--foreground .ee-button:after' => 'color: {{VALUE}};',
			],
			'condition' 	=> [
				'type' 		=> [ 'clone', 'back', 'flip', 'cube' ]
			]
		];

		$controls['background_color'] = [
			'label' 	=> __( 'Effect Background', 'elementor-extras' ),
			'type' 		=> Controls_Manager::COLOR,
			'default' 	=> '#000000',
			'selectors' => [
				 '{{SELECTOR}}.ee-effect--background .ee-button:before,
				  {{SELECTOR}}.ee-effect--double-background .ee-button:after' => 'background-color: {{VALUE}};',
			],
			'condition' 	=> [
				'type' 		=> [ 'clone', 'back', '3d', 'flip', 'cube' ]
			]
		];

		$controls['zoom'] = [
			'label'			=> _x( 'Zoom', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> '',
			'options'		=> [
				'' 			=> __( 'Default', 'elementor-extras' ),
				'zoom-in' 	=> __( 'Zoom In', 'elementor-extras' ),
				'zoom-out' 	=> __( 'Zoom Out', 'elementor-extras' ),
			],
			'condition' 	=> [
				'type' 		=> [ 'clone', '3d', 'flip', 'cube' ]
			]
		];

		$controls['easing'] = [
			'label'			=> _x( 'Easing', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'ease-in-out',
			'options'		=> self::get_easings(),
			'selectors' => [
				'{{SELECTOR}} .ee-button:before,
				 {{SELECTOR}} .ee-button:after,
				 {{SELECTOR}} .ee-button,
				 {{SELECTOR}}.ee-effect-type--clone .ee-button-content-wrapper,
				 {{SELECTOR}}.ee-effect-type--flip .ee-button-content-wrapper' => 'transition-timing-function: {{VALUE}}',
			],
		];

		$controls['duration'] = [
			'label'			=> _x( 'Duration', 'Effect Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::NUMBER,
			'default' 		=> 0.2,
			'min' 			=> 0.05,
			'max' 			=> 2,
			'step' 			=> 0.05,
			'label_block' 	=> false,
			'selectors' 	=> [
				'{{SELECTOR}} .ee-button:before,
				 {{SELECTOR}} .ee-button:after,
				 {{SELECTOR}} .ee-button,
				 {{SELECTOR}}.ee-effect-type--clone .ee-button-content-wrapper,
				 {{SELECTOR}}.ee-effect-type--flip .ee-button-content-wrapper' => 'transition-duration: {{VALUE}}s;',
			],
			'separator' 	=> 'after',
		];

		return $controls;
	}

	/**
	 * @since 1.4.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
