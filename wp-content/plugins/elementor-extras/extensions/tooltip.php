<?php

namespace ElementorExtras\Extensions;

// Elementor Extras classes
use ElementorExtras\Base\Extension_Base;
use ElementorExtras\Group_Control_Tooltip;

// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Tooltip Extension
 *
 * Adds tooltip capability to widgets
 *
 * @since 1.8.0
 */
class Extension_Tooltip extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 2.0.0
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
			'hotips',
			'resize',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {
		return __( 'Adds the option to show a tooltip for any widget and the ability to customise them globally. Can be found under Advanced &rarr; Extras &rarr; Tooltip for any widget.', 'elementor-extras' );
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

	}

	/**
	 * Add Controls
	 *
	 * @since 1.8.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_control(
			'tooltip_enable',
			[
				'label'			=> __( 'Tooltip', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elementor-extras' ),
				'label_off' 	=> __( 'No', 'elementor-extras' ),
				'return_value' 	=> 'yes',
				'separator'		=> 'before',
				'frontend_available'	=> true,
			]
		);

		$element->start_controls_tabs( 'tooltip' );

			$element->start_controls_tab( 'tooltip_settings', [
				'label' 	=> __( 'Settings', 'elementor-extras' ),
				'condition'	=> [
					'tooltip_enable!' => '',
				],
			] );

			$element->add_group_control(
				Group_Control_Tooltip::get_type(), [
					'name' 		=> 'tooltip',
					'condition'	=> [
						'tooltip_enable!' => '',
					],
				]
			);

			$element->update_control(
				'tooltip_target',
				[
					'options' => array(
						'element' => ucfirst( $element_type ),
					),
				],
				[
					'recursive' => true,
				]
			);

			$element->end_controls_tab();

			$element->start_controls_tab( 'tooltip_style', [
				'label' 	=> __( 'Style', 'elementor-extras' ),
				'condition'	=> [
					'tooltip_enable!' => '',
				],
			] );

				$element->add_control(
					'tooltip_width',
					[
						'label' 		=> __( 'Max Width', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> '',
						],
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 500,
							],
						],
						'label_block'	=> false,
						'selectors'		=> [
							'.ee-tooltip.ee-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
						]
					]
				);

				$element->add_control(
					'tooltip_distance',
					[
						'label' 		=> __( 'Distance', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'size_units' 	=> [ 'px' ],
						'label_block'	=> false,
						'selectors'		=> [
							'.ee-tooltip.ee-tooltip-{{ID}}.to--top' 		=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom' 		=> 'transform: translateY({{SIZE}}{{UNIT}});',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--left' 		=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--right' 		=> 'transform: translateX({{SIZE}}{{UNIT}});',
						]
					]
				);

				$element->add_control(
					'tooltip_arrow',
					[
						'label'		=> __( 'Arrow', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> '""',
						'options' 	=> [
							'""' 	=> __( 'Show', 'elementor-extras' ),
							'none' 	=> __( 'Hide', 'elementor-extras' ),
						],
						'selectors' => [
							'.ee-tooltip.ee-tooltip-{{ID}}:after' => 'content: {{VALUE}};',
						],
					]
				);

				$element->add_control(
					'tooltip_align',
					[
						'label' 	=> __( 'Text Align', 'elementor-extras' ),
						'type' 		=> Controls_Manager::CHOOSE,
						'options' 	=> [
							'left' 	=> [
								'title' 	=> __( 'Left', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-left',
							],
							'center' 	=> [
								'title' => __( 'Center', 'elementor-extras' ),
								'icon' 	=> 'fa fa-align-center',
							],
							'right' 	=> [
								'title' => __( 'Right', 'elementor-extras' ),
								'icon'	=> 'fa fa-align-right',
							],
						],
						'selectors' => [
							'.ee-tooltip.ee-tooltip-{{ID}}' => 'text-align: {{VALUE}};',
						],
					]
				);

				$element->add_control(
					'tooltip_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'.ee-tooltip.ee-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$element->add_control(
					'tooltip_border_radius',
					[
						'label' 		=> __( 'Border Radius', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'.ee-tooltip.ee-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$element->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 		=> 'tooltip_typography',
						'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
						'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
						'separator' => 'after',
					]
				);

				$element->add_control(
					'tooltip_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'.ee-tooltip.ee-tooltip-{{ID}}' 					=> 'background-color: {{VALUE}};',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--top:after' 		=> 'border-top-color: {{VALUE}};',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--left:after' 		=> 'border-left-color: {{VALUE}};',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--right:after' 	=> 'border-right-color: {{VALUE}};',
							'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom:after' 	=> 'border-bottom-color: {{VALUE}};',
						],
					]
				);

				$element->add_control(
					'tooltip_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'.ee-tooltip.ee-tooltip-{{ID}}' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$element->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'tooltip_box_shadow',
						'selector' => '.ee-tooltip.ee-tooltip-{{ID}}',
						'separator'	=> '',
					]
				);

			$element->end_controls_tab();

		$element->end_controls_tabs();

	}

	/**
	 * Add Actions
	 *
	 * @since 1.8.0
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );
	}

}