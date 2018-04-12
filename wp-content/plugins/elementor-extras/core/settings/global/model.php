<?php
namespace ElementorExtras\Core\Settings\General;

use Elementor\Controls_Manager;
use ElementorExtras\Group_Control_Tooltip;
use Elementor\Core\Settings\Base\Model as BaseModel;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Model extends BaseModel {

	/**
	 * @since 1.6.0
	 * @access public
	 */
	public function get_name() {
		return 'extras-settings';
	}

	/**
	 * @since 1.6.0
	 * @access public
	 */
	public function get_css_wrapper_selector() {
		return '';
	}

	/**
	 * @since 1.6.0
	 * @access public
	 */
	public function get_panel_page_settings() {
		return [
			'title' => __( 'Extras Settings', 'elementor-extras' ),
			'menu' => [
				'icon' => 'nicon nicon-extras',
				'beforeItem' => 'elementor-settings',
			],
		];
	}

	/**
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function get_controls_list() {

		return [
			Manager::PANEL_TAB_SETTINGS => [
				'settings' => [
					'label' => __( 'Tooltips', 'elementor' ),
					'controls' => [
						'ee_tooltips_note' => [
							'type' 				=> Controls_Manager::RAW_HTML,
							'raw' 				=> __( 'Changes to these settings can be previewed in the editor only after refreshing the page.', 'elementor-extras' ),
							'content_classes' 	=> 'ee-raw-html ee-raw-html__warning',
						],
						'ee_tooltips_position' => [
							'label'			=> __( 'Position', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SELECT,
							'default' 	=> 'bottom',
							'options' 	=> [
								'bottom' 	=> __( 'Bottom', 'elementor-extras' ),
								'left' 		=> __( 'Left', 'elementor-extras' ),
								'top' 		=> __( 'Top', 'elementor-extras' ),
								'right' 	=> __( 'Right', 'elementor-extras' ),
							],
							'frontend_available' => true
						],
						'ee_tooltips_delay_in' => [
							'label' 		=> __( 'Delay in (s)', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0,
							],
							'range' 	=> [
								'px' 	=> [
									'min' 	=> 0,
									'max' 	=> 1,
									'step'	=> 0.1,
								],
							],
							'frontend_available' => true
						],
						'ee_tooltips_delay_out' => [
							'label' 		=> __( 'Delay out (s)', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0,
							],
							'range' 	=> [
								'px' 	=> [
									'min' 	=> 0,
									'max' 	=> 1,
									'step'	=> 0.1,
								],
							],
							'frontend_available' => true
						],
						'ee_tooltips_duration' => [
							'label' 		=> __( 'Duration', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0.2,
							],
							'range' 	=> [
								'px' 	=> [
									'min' 	=> 0,
									'max' 	=> 2,
									'step'	=> 0.1,
								],
							],
							'frontend_available' => true
						],
					],
				],
			],
			Controls_Manager::TAB_STYLE => [
				'tooltips' => [
					'label' => __( 'Tooltips', 'elementor' ),
					'controls' => [
						'ee_tooltips_distance' => [
							'label' 		=> __( 'Distance', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'min' 	=> 0,
									'max' 	=> 100,
								],
							],
							'selectors'		=> [
								'.ee-tooltip.to--top' 		=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
								'.ee-tooltip.to--bottom' 	=> 'transform: translateY({{SIZE}}{{UNIT}});',
								'.ee-tooltip.to--left' 		=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
								'.ee-tooltip.to--right' 	=> 'transform: translateX({{SIZE}}{{UNIT}});',
							]
						],
						'ee_tooltips_width' => [
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
							'selectors'		=> [
								'.ee-tooltip' => 'max-width: {{SIZE}}{{UNIT}};',
							]
						],
						'ee_tooltips_align' => [
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
								'.ee-tooltip' => 'text-align: {{VALUE}};',
							],
						],
						'ee_tooltips_padding' => [
							'label' 		=> __( 'Padding', 'elementor-extras' ),
							'type' 			=> Controls_Manager::DIMENSIONS,
							'size_units' 	=> [ 'px', 'em', '%' ],
							'selectors' 	=> [
								'.ee-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						],
						'ee_tooltips_border_radius' => [
							'label' 		=> __( 'Border Radius', 'elementor-extras' ),
							'type' 			=> Controls_Manager::DIMENSIONS,
							'size_units' 	=> [ 'px', '%' ],
							'selectors' 	=> [
								'.ee-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						],
						'ee_tooltips_background_color' => [
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'scheme' 	=> [
							    'type' 	=> Scheme_Color::get_type(),
							    'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'.ee-tooltip' 					=> 'background-color: {{VALUE}};',
								'.ee-tooltip.to--top:after' 	=> 'border-top-color: {{VALUE}};',
								'.ee-tooltip.to--left:after' 	=> 'border-left-color: {{VALUE}};',
								'.ee-tooltip.to--right:after' 	=> 'border-right-color: {{VALUE}};',
								'.ee-tooltip.to--bottom:after' 	=> 'border-bottom-color: {{VALUE}};',
							],
						],
						'ee_tooltips_color' => [
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'scheme' 	=> [
							    'type' 	=> Scheme_Color::get_type(),
							    'value' => Scheme_Color::COLOR_1,
							],
							'selectors' => [
								'.ee-tooltip' => 'color: {{VALUE}};',
							],
						],
					],
				],
			],
		];
	}

	/**
	 * @since 1.6.0
	 * @access protected
	 */
	protected function _register_controls() {
		$controls_list = self::get_controls_list();

		foreach ( $controls_list as $tab_name => $sections ) {

			foreach ( $sections as $section_name => $section_data ) {

				$this->start_controls_section(
					$section_name, [
						'label' => $section_data['label'],
						'tab' => $tab_name,
					]
				);

				foreach ( $section_data['controls'] as $control_name => $control_data ) {
					$this->add_control( $control_name, $control_data );
				}

				$this->end_controls_section();
			}
		}
	}
}
