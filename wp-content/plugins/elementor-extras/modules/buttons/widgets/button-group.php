<?php
namespace ElementorExtras\Modules\Buttons\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Widget_Button;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

use ElementorExtras\Group_Control_Button_Effect;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Widget_Button_Group
 *
 * @since 0.1.0
 */
class Button_Group extends Extras_Widget {

	public function get_name() {
		return 'button-group';
	}

	public function get_title() {
		return __( 'Buttons', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-button-group';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public static function get_button_sizes() {
		return Widget_Button::get_button_sizes();
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.1.0
	 **/
	public function get_script_depends() {
		return [
			'hotips',
			'resize',
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => __( 'Buttons', 'elementor-extras' ),
			]
		);

			$repeater = new Repeater();

			$repeater->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-wrapper',
					'separator'	=> 'after',
					'condition' => [
						'button_custom_style!' => ''
					]
				]
			);

			$repeater->start_controls_tabs( 'buttons_repeater' );

			$repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'elementor-extras' ) ] );

				$repeater->add_control(
					'text',
					[
						'label' 		=> __( 'Text', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'default' 		=> __( 'Click me', 'elementor-extras' ),
						'placeholder' 	=> __( 'Click me', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'tooltip',
					[
						'label' 		=> __( 'Enable Tooltip', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'tooltip',
					]
				);

				$repeater->add_control(
					'tooltip_position',
					[
						'label'		=> __( 'Tooltip Position', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> '',
						'options' 	=> [
							'' 			=> __( 'Global', 'elementor-extras' ),
							'bottom' 	=> __( 'Bottom', 'elementor-extras' ),
							'left' 		=> __( 'Left', 'elementor-extras' ),
							'top' 		=> __( 'Top', 'elementor-extras' ),
							'right' 	=> __( 'Right', 'elementor-extras' ),
						],
						'condition'		=> [
							'tooltip!'	=> ''
						]
					]
				);

				$repeater->add_control(
					'tooltip_content',
					[
						'label' 		=> '',
						'type' 			=> Controls_Manager::TEXTAREA,
						'default' 		=> __( 'I am a tooltip for a button', 'elementor-extras' ),
						'placeholder' 	=> __( 'I am a tooltip for a button', 'elementor-extras' ),
						'title' 		=> __( 'Tooltip Content', 'elementor-extras' ),
						'rows' 			=> 5,
						'condition'		=> [
							'tooltip!'	=> ''
						]
					]
				);

				$repeater->add_control(
					'link',
					[
						'label' 		=> __( 'Link', 'elementor-extras' ),
						'type' 			=> Controls_Manager::URL,
						'placeholder' 	=> esc_url( home_url( '/' ) ),
						'default' 		=> [
							'url' 		=> esc_url( home_url( '/' ) ),
						],
						'label_block' 	=> false,
					]
				);

				$repeater->add_control(
					'icon',
					[
						'label' 		=> __( 'Icon', 'elementor-extras' ),
						'type' 			=> Controls_Manager::ICON,
						'label_block' 	=> false,
						'default' 		=> '',
					]
				);

				$repeater->add_control(
					'icon_align',
					[
						'label' 	=> __( 'Icon Position', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'left',
						'options' 	=> [
							'left' 		=> __( 'Before', 'elementor-extras' ),
							'right' 	=> __( 'After', 'elementor-extras' ),
						],
						'condition' => [
							'icon!' => '',
						],
					]
				);

				$repeater->add_control(
					'icon_indent',
					[
						'label' 	=> __( 'Icon Spacing', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'max' => 50,
							],
						],
						'condition' => [
							'icon!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--right' => 'margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--left' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$repeater->add_control(
					'view',
					[
						'label' 	=> __( 'View', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HIDDEN,
						'default' 	=> 'traditional',
					]
				);

				$repeater->add_control(
					'_element_id',
					[
						'label' 		=> __( 'CSS ID', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'default' 		=> '',
						'label_block' 	=> false,
						'title' 		=> __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'css_classes',
					[
						'label' 		=> __( 'CSS Classes', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'default' 		=> '',
						'label_block' 	=> false,
						'title' 		=> __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-extras' ),
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_layout', [ 'label' => __( 'Layout', 'elementor-extras' ) ] );

				$repeater->add_control(
					'size',
					[
						'label' 		=> __( 'Size', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'sm',
						'options' 		=> self::get_button_sizes(),
					]
				);

				$repeater->add_responsive_control(
					'label_min_width',
					[
						'label' 		=> __( 'Label Min Width', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 10,
								'max' 	=> 1000,
								'step'	=> 1,
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-text' => 'min-width: {{SIZE}}px;',
						]
					]
				);

				$repeater->add_responsive_control(
					'min_width',
					[
						'label' 		=> __( 'Button Min Width', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 10,
								'max' 	=> 1000,
								'step'	=> 1,
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button' => 'min-width: {{SIZE}}px;',
						]
					]
				);

				$repeater->add_control(
					'text_align',
					[
						'label' 		=> __( 'Align Text', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default' 		=> '',
						'options' 		=> [
							'left'    		=> [
								'title' 	=> __( 'Left', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-left',
							],
							'center' 		=> [
								'title' 	=> __( 'Center', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-center',
							],
							'right' 		=> [
								'title' 	=> __( 'Right', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-right',
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-text' => 'text-align: {{VALUE}};'
						]
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'elementor-extras' ) ] );

				$repeater->add_control(
					'button_custom_style',
					[
						'label' 		=> __( 'Custom', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'description'   => __( 'Set custom styles that will only affect this specific button.', 'elementor-extras' ),
					]
				);

				$repeater->add_group_control(
					Group_Control_Button_Effect::get_type(),
					[
						'name' 		=> 'button_effect',
						'label' 	=> __( 'Effect', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-wrapper',
						'condition' => [
							'button_custom_style!' => ''
						],
					]
				);

				$repeater->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'button_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} {{CURRENT_ITEM}} .ee-button',
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'border_radius',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> __( 'Border Radius', 'elementor-extras' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button,
							 {{WRAPPER}} {{CURRENT_ITEM}} .ee-effect--radius .ee-button:before,
							 {{WRAPPER}} {{CURRENT_ITEM}} .ee-effect--radius .ee-button:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'condition' => [
							'button_custom_style!' => '',
							'button_effect_type!' => '3d',
						]
					]
				);

				$repeater->add_control(
					'text_padding',
					[
						'label' 		=> __( 'Text Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-content-wrapper,
							{{WRAPPER}} {{CURRENT_ITEM}} .ee-button:after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						],
						'separator' => 'before',
					]
				);

				$repeater->add_control(
					'heading_style',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Default', 'elementor-extras' ),
						'separator' => 'before',
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'button_text_color',
					[
						'label' 	=> __( 'Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button' => 'color: {{VALUE}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'heading_hover_style',
					[
						'type'		=> Controls_Manager::HEADING,
						'label' 	=> __( 'Hover', 'elementor-extras' ),
						'separator' => 'before',
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'hover_color',
					[
						'label' 	=> __( 'Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-wrapper:hover .ee-button' => 'color: {{VALUE}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'button_background_hover_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-wrapper:hover .ee-button' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'button_hover_border_color',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'condition' => [
							'button_border_border!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-button-wrapper:hover .ee-button' => 'border-color: {{VALUE}};',
						],
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

				$repeater->add_control(
					'hover_animation',
					[
						'label' 	=> __( 'Animation', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HOVER_ANIMATION,
						'condition' => [
							'button_custom_style!' => ''
						]
					]
				);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'buttons',
				[
					'label' 	=> __( 'Buttons', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'text' 	=> __( 'Button #1', 'elementor-extras' )
						],
						[
							'text' 	=> __( 'Button #2', 'elementor-extras' )
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ text }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltips',
			[
				'label' => __( 'Tooltips', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'position',
				[
					'label'		=> __( 'Position', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'bottom',
					'options' 	=> [
						'bottom' 	=> __( 'Bottom', 'elementor-extras' ),
						'left' 		=> __( 'Left', 'elementor-extras' ),
						'top' 		=> __( 'Top', 'elementor-extras' ),
						'right' 	=> __( 'Right', 'elementor-extras' ),
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'delay_in',
				[
					'label' 		=> __( 'Delay in (s)', 'elementor-extras' ),
					'description' 	=> __( 'Time until tooltips appear.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 1,
							'step'	=> 0.1,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'delay_out',
				[
					'label' 		=> __( 'Delay out (s)', 'elementor-extras' ),
					'description' 	=> __( 'Time until tooltips dissapear.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 1,
							'step'	=> 0.1,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'duration',
				[
					'label' 		=> __( 'Duration', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 2,
							'step'	=> 0.1,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'tooltips_arrow',
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

			$this->add_control(
				'tooltips_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'description' 	=> __( 'The distance between the tooltip and the hotspot. Defaults to 6px', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 0,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}.to--top' 			=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom' 		=> 'transform: translateY({{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--left' 			=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--right' 		=> 'transform: translateX({{SIZE}}{{UNIT}});',
					]
				]
			);

			$this->add_responsive_control(
				'tooltips_width',
				[
					'label' 		=> __( 'Maximum Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 350,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 500,
						],
					],
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->add_control(
				'tooltips_zindex',
				[
					'label'			=> __( 'zIndex', 'elementor-extras' ),
					'description'   => __( 'Adjust the z-index of the tooltips. Defaults to 999', 'elementor-extras' ),
					'type'			=> Controls_Manager::NUMBER,
					'default'		=> '999',
					'min'			=> -9999999,
					'step'			=> 1,
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'z-index: {{SIZE}};',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' 	=> __( 'Buttons', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'vertical_align',
				[
					'label' 		=> __( 'Vertical Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'top'    		=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'prefix_class'		=> 'ee-button-group%s-valign-',
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'prefix_class'		=> 'ee-button-group%s-halign-'
				]
			);

			$this->add_control(
				'content_align',
				[
					'label' 		=> __( 'Align Content', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'justify',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'condition' => [
						'align' => 'justify',
					],
					'prefix_class'		=> 'ee-button-group-content-halign-'
				]
			);

			$this->add_control(
				'text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'condition' => [
						'align' 		=> 'justify',
						'content_align' => 'justify',
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-button-text' => 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_control(
				'gap',
				[
					'label' 		=> __( 'Buttons Gap', 'elementor-extras' ),
					'description' 	=> __( 'Select Custom to be able to specify a different gap for each breakpoint.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'default',
					'options' 		=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'no' 		=> __( 'No Gap', 'elementor-extras' ),
						'narrow' 	=> __( 'Narrow', 'elementor-extras' ),
						'extended' 	=> __( 'Extended', 'elementor-extras' ),
						'wide' 		=> __( 'Wide', 'elementor-extras' ),
						'wider' 	=> __( 'Wider', 'elementor-extras' ),
						'custom' 	=> __( 'Custom', 'elementor-extras' ),
					],
					'prefix_class'	=> 'ee-button-group-gap-',
				]
			);

			$this->add_control(
				'buttons_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-button,
						 {{WRAPPER}} .ee-effect--radius .ee-button:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_gap',
				[
					'label' 	=> __( 'Custom Gap', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min'	=> 1,
							'max' 	=> 100,
						],
					],
					'condition' => [
						'gap' => 'custom',
					],
					'selectors' => [
						// No stacking
						'{{WRAPPER}} .ee-button-group' 	=> 'margin-left: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-button-gap' => 'margin-left: {{SIZE}}{{UNIT}};',

						// Stacked
						'(desktop){{WRAPPER}}.ee-button-group-stack-desktop .ee-button-gap:not(:last-child)' 	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
						'(tablet){{WRAPPER}}.ee-button-group-stack-tablet .ee-button-gap:not(:last-child)' 		=> 'margin-bottom: {{SIZE}}{{UNIT}};',
						'(mobile){{WRAPPER}}.ee-button-group-stack-mobile .ee-button-gap:not(:last-child)' 		=> 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'after',
				]
			);

			$this->add_control(
				'stack',
				[
					'label' 		=> __( 'Stack', 'elementor-extras' ),
					'description'	=> __( 'Choose on what breakpoint should the buttons begin to stack.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> '',
					'options' 	=> [
						'' 			=> __( 'None', 'elementor-extras' ),
						'desktop' 	=> __( 'Desktop', 'elementor-extras' ),
						'tablet' 	=> __( 'Tablet', 'elementor-extras' ),
						'mobile' 	=> __( 'Mobile', 'elementor-extras' ),
					],
					'prefix_class'	=> 'ee-button-group-stack-',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-button',
				]
			);

			$this->start_controls_tabs( 'buttons_style' );

			$this->start_controls_tab( 'buttons_style_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'buttons_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-button',
					]
				);

				$this->add_control(
					'buttons_text_color',
					[
						'label' 	=> __( 'Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'buttons_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
							'type' 	=> Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .ee-button' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'buttons_box_shadow',
						'selector' 	=> '{{WRAPPER}} .ee-button',
						'separator'	=> '',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'buttons_style_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'buttons_hover_color',
					[
						'label' 	=> __( 'Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-button-wrapper:hover .ee-button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'buttons_background_hover_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
							'type' 	=> Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .ee-button-wrapper:hover .ee-button' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'buttons_hover_border_color',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'condition' => [
							'button_border_border!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .ee-button-wrapper:hover .ee-button' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'buttons_hover_box_shadow',
						'selector' 	=> '{{WRAPPER}} .ee-button-wrapper:hover .ee-button',
						'separator'	=> '',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltips_style',
			[
				'label' 	=> __( 'Tooltips', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'tooltips_align',
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

			$this->add_control(
				'tooltips_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'tooltips_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
				]
			);

			$this->add_control(
				'tooltips_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'tooltips_typography',
					'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'separator' => 'after',
				]
			);

			$this->add_control(
				'tooltips_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'.ee-tooltip.ee-tooltip-{{ID}}' 					=> 'background-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--top:after' 		=> 'border-top-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--left:after' 	=> 'border-left-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--right:after' 	=> 'border-right-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom:after' 	=> 'border-bottom-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tooltips_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'.ee-tooltip.ee-tooltip-{{ID}}' 		=> 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'tooltips_box_shadow',
					'selector' => '.ee-tooltip.ee-tooltip-{{ID}}',
					'separator'	=> '',
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['buttons'] )
			return;

		$this->add_render_attribute( 'group', 'class', 'ee-button-group' );

		?><ul <?php echo $this->get_render_attribute_string( 'group' ); ?>><?php

			foreach ( $settings['buttons'] as $index => $item ) {

				$_has_tooltip = false;
				$_has_icon = false;

				$button_text_clone = $item['text'];

				$gap_key 		= $this->get_repeater_setting_key( 'item', 'buttons', $index );
				$wrapper_key 	= $this->get_repeater_setting_key( 'wrapper', 'buttons', $index );
				$button_key 	= $this->get_repeater_setting_key( 'button', 'buttons', $index );
				$icon_key 		= $this->get_repeater_setting_key( 'icon', 'buttons', $index );
				$content_key 	= $this->get_repeater_setting_key( 'content', 'buttons', $index );
				$tooltip_key 	= $this->get_repeater_setting_key( 'tooltip', 'buttons', $index );
				$text_key		= $this->get_repeater_setting_key( 'text', 'buttons', $index );
				$content_id 	= $this->get_id() . '_' . $item['_id'];

				$this->add_render_attribute( $gap_key, 'class', [
					'ee-button-gap',
					'elementor-repeater-item-' . $item['_id']
				] );

				$this->add_render_attribute( $wrapper_key, 'class', 'ee-button-wrapper' );
				$this->add_render_attribute( $button_key, 'class', 'ee-button' );
				$this->add_render_attribute( $content_key, 'class', 'ee-button-content-wrapper' );

				$this->add_render_attribute( $text_key, 'class', 'ee-button-text' );
				$this->add_inline_editing_attributes( $text_key, 'none' );

				$this->add_render_attribute( $tooltip_key, [
					'class' => 'hotip-content',
					'id' 	=> 'hotip-content-' . $content_id,
				] );

				if ( ! empty( $item['icon'] ) ) {
					$this->add_render_attribute( $icon_key, 'class', [
						'ee-button-icon',
						'ee-icon--' . $item['icon_align'],
					] );

					$_has_icon = true;
				}

				if ( 'tooltip' === $item['tooltip'] && ! empty( $item['tooltip_content'] ) ) {
					$this->add_render_attribute( $wrapper_key, [
						'class' 				=> 'hotip',
						'data-hotips-content' 	=> '#hotip-content-' . $content_id,
						'data-hotips-class' 	=> [
							'ee-global',
							'ee-tooltip',
							'ee-tooltip-' . $this->get_id() ],
						'data-hotips-position' 	=> $item['tooltip_position'],
					] );

					$_has_tooltip = true;
				}

				if ( ! empty( $item['link']['url'] ) ) {

					$this->add_render_attribute( $button_key, 'class', 'ee-button-link' );
					$this->add_render_attribute( $wrapper_key, 'href', $item['link']['url'] );

					if ( ! empty( $item['link']['is_external'] ) ) {
						$this->add_render_attribute( $wrapper_key, 'target', '_blank' );
					}

					if ( ! empty( $item['link']['nofollow'] ) ) {
						$this->add_render_attribute( $wrapper_key, 'rel', 'nofollow' );
					}
				}

				if ( ! empty( $item['size'] ) ) {
					$this->add_render_attribute( $button_key, 'class', 'ee-size-' . $item['size'] );
				}

				if ( $item['hover_animation'] ) {
					$this->add_render_attribute( $wrapper_key, 'class', 'elementor-animation-' . $item['hover_animation'] );
				}

				if ( $item['css_classes'] ) {
					$this->add_render_attribute( $wrapper_key, 'class', $item['css_classes'] );
				}

				if ( $item['_element_id'] ) {
					$this->add_render_attribute( $wrapper_key, 'id', $item['_element_id'] );
				}

				if ( 'yes' === $item['button_custom_style'] ) {

					$this->add_render_attribute( $wrapper_key, 'class', [
						'ee-effect',
						'ee-effect-type--' . $item['button_effect_type']
					] );

					if ( in_array( $item['button_effect_type'], array( 'clone', 'back', '3d', 'flip', 'cube' )) && '' !== $item['button_effect_direction'] ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect-direction--' . $item['button_effect_direction']
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'back' )) && '' !== $item['button_effect_orientation'] && '' === $item['button_effect_direction'] ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect-orientation--' . $item['button_effect_orientation']
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'flip' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--radius'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone', 'back', 'flip', '3d', 'cube' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--background'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone', 'flip', 'cube' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--foreground'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'back' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--double-background'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'back' )) && '' !== $item['button_effect_double'] ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--double'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect-entrance--' . $item['button_effect_entrance']
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone', '3d', 'flip', 'cube' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect-zoom--' . $item['button_effect_zoom']
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone', 'back' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect-shape--' . $item['button_effect_shape']
						] );
					}

					if ( in_array( $item['button_effect_type'], array( '3d', 'flip', 'cube' )) ) {
						$this->add_render_attribute( $wrapper_key, 'class', [
							'ee-effect--perspective'
						] );
					}

					if ( in_array( $item['button_effect_type'], array( 'clone', 'flip', 'cube' )) && '' !== $item['button_effect_text'] ) {
						$button_text_clone = $item['button_effect_text'];
					}
				}

				$this->add_render_attribute( $button_key, 'data-label', $button_text_clone );

				?>
				<li <?php echo $this->get_render_attribute_string( $gap_key ); ?>>
					<a <?php echo $this->get_render_attribute_string( $wrapper_key ); ?>>

						<span <?php echo $this->get_render_attribute_string( $button_key ); ?>>
							<span <?php echo $this->get_render_attribute_string( $content_key ); ?>>

								<?php if ( $_has_icon ) : ?>
									<span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
										<i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
									</span>
								<?php endif; ?>

								<span <?php echo $this->get_render_attribute_string( $text_key ); ?>>
									<?php echo $item['text']; ?>
								</span>

								<?php if ( $_has_tooltip ) { ?>
								<span <?php echo $this->get_render_attribute_string( $tooltip_key ); ?>>
									<?php echo $this->parse_text_editor( $item['tooltip_content'] ); ?>
								</span>
								<?php } ?>

							</span>
						</span>

					</a>
				</li>
			<?php } ?>
		</ul>
		<?php
	}

	protected function _content_template() { ?><#

		var widgetId = view.$el.data('id');

		view.addRenderAttribute( 'group', 'class', 'ee-button-group' )

		if ( settings.buttons ) {

		#><ul {{{ view.getRenderAttributeString( 'group' ) }}}><#

			_.each( settings.buttons, function( item, index ) {

				var button_text_clone 	= item.text;

				var wrapperKey 		= view.getRepeaterSettingKey( 'wrapper', 'buttons', index ),
					gapKey 			= view.getRepeaterSettingKey( 'item', 'buttons', index ),
					buttonKey 		= view.getRepeaterSettingKey( 'button', 'buttons', index ),
					iconKey 		= view.getRepeaterSettingKey( 'icon', 'buttons', index ),
					contentKey 		= view.getRepeaterSettingKey( 'content', 'buttons', index ),
					tooltipKey 		= view.getRepeaterSettingKey( 'tooltip', 'buttons', index ),
					textKey			= view.getRepeaterSettingKey( 'text', 'buttons', index ),

					_has_icon 		= false,
					_has_tooltip 	= false;

				if ( item.tooltip == 'tooltip' && item.tooltip_content ) {
					view.addRenderAttribute( wrapperKey, 'class', 'hotip' );
				}

				view.addRenderAttribute( gapKey, 'class', [
					'ee-button-gap',
					'elementor-repeater-item-' + item._id
				] );

				view.addRenderAttribute( wrapperKey, 'class', 'ee-button-wrapper' );
				view.addRenderAttribute( buttonKey, 'class', 'ee-button' );
				view.addRenderAttribute( contentKey, 'class', 'ee-button-content-wrapper' );

				view.addRenderAttribute( textKey, 'class', 'ee-button-text' );
				view.addInlineEditingAttributes( textKey, 'none' );

				view.addRenderAttribute( tooltipKey, 'class', 'hotip-content' );
				view.addRenderAttribute( tooltipKey, 'id', 'hotip-content-' + widgetId + '_' + item._id );

				if ( '' !== item.icon ) {
					view.addRenderAttribute( iconKey, 'class', [
						'ee-button-icon',
						'ee-icon--' + item.icon_align,
					] );

					_has_icon = true;
				}

				if ( 'tooltip' === item.tooltip && '' !== item.tooltip_content ) {

					view.addRenderAttribute( wrapperKey, 'class', 'hotip' );
					view.addRenderAttribute( wrapperKey, 'data-hotips-content', '#hotip-content-' + widgetId + '_' + item._id );
					view.addRenderAttribute( wrapperKey, 'data-hotips-class', [
						'ee-global',
						'ee-tooltip'
					] );

					view.addRenderAttribute( wrapperKey, 'data-hotips-position', item.tooltip_position );

					_has_tooltip = true;
				}

				if ( '' !== item.link.url ) {
					view.addRenderAttribute( buttonKey, 'class', 'ee-button-link' );
					view.addRenderAttribute( wrapperKey, 'href', item.link.url );
				}

				if ( '' !== item.size ) {
					view.addRenderAttribute( buttonKey, 'class', 'ee-size-' + item.size );
				}

				if ( item.hover_animation ) {
					view.addRenderAttribute( wrapperKey, 'class', 'elementor-animation-' + item.hover_animation );
				}

				if ( item.css_classes ) {
					view.addRenderAttribute( wrapperKey, 'class', item.css_classes );
				}

				if ( item._element_id ) {
					view.addRenderAttribute( wrapperKey, 'id', item._element_id );
				}

				if ( 'yes' === item.button_custom_style ) {

					view.addRenderAttribute( wrapperKey, 'class', [
						'ee-effect',
						'ee-effect-type--' + item.button_effect_type,
					] );

					if ( [ 'clone', 'back', '3d', 'flip', 'cube' ].indexOf( item.button_effect_type ) > -1 && '' !== item.button_effect_direction ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect-direction--' + item.button_effect_direction );
					}

					if ( [ 'back' ].indexOf( item.button_effect_type ) > -1 && '' !== item.button_effect_orientation && '' === item.button_effect_direction  ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect-orientation--' + item.button_effect_orientation );
					}

					if ( [ 'flip' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--radius' );
					}

					if ( [ 'clone', 'back', 'flip', '3d', 'cube' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--background' );
					}

					if ( [ 'clone', 'flip', 'cube' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--foreground' );
					}

					if ( [ 'back' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--double-background' );
					}

					if ( [ 'back' ].indexOf( item.button_effect_type ) > -1 && '' !== item.button_effect_double ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--double' );
					}

					if ( [ 'clone' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect-entrance--' + item.button_effect_entrance );
					}

					if ( [ 'clone', '3d', 'flip', 'cube' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect-zoom--' + item.button_effect_zoom );
					}

					if ( [ 'clone', 'back' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect-shape--' + item.button_effect_shape );
					}

					if ( [ '3d', 'flip', 'cube' ].indexOf( item.button_effect_type ) > -1 ) {
						view.addRenderAttribute( wrapperKey, 'class', 'ee-effect--perspective' );
					}

					if ( [ 'clone', 'flip', 'cube' ].indexOf( item.button_effect_type ) > -1 && '' !== item.button_effect_text ) {
						button_text_clone = item.button_effect_text;
					}

					view.addRenderAttribute( buttonKey, 'data-label', button_text_clone );

				} #>

				<li {{{ view.getRenderAttributeString( gapKey ) }}}>
					<a {{{ view.getRenderAttributeString( wrapperKey ) }}}>

						<span {{{ view.getRenderAttributeString( buttonKey ) }}}>
							<span {{{ view.getRenderAttributeString( contentKey ) }}}>

								<# if ( _has_icon ) { #>
								<span {{{ view.getRenderAttributeString( iconKey ) }}}>
									<i class="{{ item.icon }}"></i>
								</span>
								<# } #>

								<span {{{ view.getRenderAttributeString( textKey ) }}}>
									{{{ item.text }}}
								</span>

								<# if ( _has_tooltip ) { #>
								<span {{{ view.getRenderAttributeString( tooltipKey ) }}}>
									{{{ item.tooltip_content }}}
								</span>
								<# } #>

							</span>
						</span>

					</a>
				</li>
			<# }); #>
		</ul><# } #><?php
	}
}
