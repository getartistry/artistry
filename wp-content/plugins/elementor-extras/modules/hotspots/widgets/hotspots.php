<?php
namespace ElementorExtras\Modules\Hotspots\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Transition;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hotspots
 *
 * @since 0.1.0
 */
class Hotspots extends Extras_Widget {

	public function get_name() {
		return 'hotspots';
	}

	public function get_title() {
		return __( 'Hotspots', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-hotspots';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'hotips',
			'resize',
		];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'image',
				[
					'label' => __( 'Choose Image', 'elementor-extras' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'image', // Actually its `image_size`
					'label' => __( 'Image Size', 'elementor-extras' ),
					'default' => 'large',
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elementor-extras' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' => 'eicon-h-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'elementor-extras' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hotspots',
			[
				'label' => __( 'Hotspots', 'elementor-extras' ),
				'condition'		=> [
					'image[url]!' => '',
				]
			]
		);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'hotspots_repeater' );

			$repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'elementor-extras' ) ] );

				$repeater->add_control(
					'hotspot',
					[
						'label'		=> __( 'Type', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'text',
						'options' 	=> [
							'text' 		=> __( 'Text', 'elementor-extras' ),
							'icon' 		=> __( 'Icon', 'elementor-extras' ),
						],
					]
				);

				$repeater->add_control(
					'text',
					[
						'default'	=> __( 'X', 'elementor-extras' ),
						'type'		=> Controls_Manager::TEXT,
						'label' 	=> __( 'Text', 'elementor-extras' ),
						'separator' => 'none',
						'condition'		=> [
							'hotspot'	=> 'text'
						]
					]
				);

				$repeater->add_control(
					'icon',
					[
						'label' 		=> __( 'Icon', 'elementor-extras' ),
						'type' 			=> Controls_Manager::ICON,
						'label_block' 	=> true,
						'default' 		=> '',
						'condition'		=> [
							'hotspot'	=> 'icon'
						],
						'frontend_available' => true,
					]
				);

				$repeater->add_control(
					'link',
					[
						'label' 		=> __( 'Link', 'elementor-extras' ),
						'description' 	=> __( 'Active only when tolltips\' Trigger is set to Hover', 'elementor-extras' ),
						'type' 			=> Controls_Manager::URL,
						'placeholder' 	=> esc_url( home_url( '/' ) ),
						'default' 		=> [
							'url' 		=> esc_url( home_url( '/' ) ),
						],
						'frontend_available' => true,
					]
				);

				$repeater->add_control(
					'content',
					[
						'label' 	=> __( 'Tooltip Content', 'elementor-extras' ),
						'type' 		=> Controls_Manager::WYSIWYG,
						'default' 	=> __( 'I am a tooltip for a hotspot', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'_item_id',
					[
						'label' 		=> __( 'CSS ID', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'default' 		=> '',
						'label_block' 	=> true,
						'title' 		=> __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'css_classes',
					[
						'label' 		=> __( 'CSS Classes', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'default' 		=> '',
						'prefix_class' 	=> '',
						'label_block' 	=> true,
						'title' 		=> __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-extras' ),
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'elementor-extras' ) ] );

				$repeater->add_control(
					'color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-hotspot__wrapper' => 'color: {{VALUE}};',
						],
					]
				);

				$repeater->add_control(
					'background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-hotspot__wrapper' 		=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-hotspot__wrapper:before' 	=> 'background-color: {{VALUE}};',
						],
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_position', [ 'label' => __( 'Position', 'elementor-extras' ) ] );

				$repeater->add_control(
					'_position_horizontal',
					[
						'label' 	=> __( 'Horizontal position (%)', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step'	=> 0.1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
						],
					]
				);

				$repeater->add_control(
					'_position_vertical',
					[
						'label' 	=> __( 'Vertical position (%)', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step'	=> 0.1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
						],
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
					]
				);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();


			$this->add_control(
				'hotspots',
				[
					'label' 	=> __( 'Hotspots', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'text' 	=> '1',
						],
						[
							'text' 	=> '2',
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ text }}}',
					'condition'		=> [
						'image[url]!' => '',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltips',
			[
				'label' => __( 'Tooltips', 'elementor-extras' ),
				'condition'		=> [
					'image[url]!' => '',
				]
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
					'condition'		=> [
						'image[url]!' => '',
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'trigger',
				[
					'label'		=> __( 'Trigger', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'hover',
					'options' 	=> [
						'hover' 	=> __( 'Hover', 'elementor-extras' ),
						'click' 	=> __( 'Click', 'elementor-extras' ),
					],
					'condition'		=> [
						'image[url]!' => '',
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'arrow',
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
					'condition'		=> [
						'image[url]!' => '',
					]
				]
			);

			$this->add_control(
				'distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'description' 	=> __( 'The distance between the tooltip and the hotspot. Defaults to 6px', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'condition'		=> [
						'image[url]!' => '',
					],
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}.to--top' 			=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom' 		=> 'transform: translateY({{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--left' 			=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--right' 		=> 'transform: translateX({{SIZE}}{{UNIT}});',
					]
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
					'condition'		=> [
						'image[url]!' => '',
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
					'condition'		=> [
						'image[url]!' => '',
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

			$this->add_responsive_control(
				'width',
				[
					'label' 		=> __( 'Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 200,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 500,
						],
					],
					'condition'		=> [
						'image[url]!' => '',
					],
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->add_control(
				'zindex',
				[
					'label'			=> __( 'zIndex', 'elementor-extras' ),
					'description'   => __( 'Adjust the z-index of the tooltips. Defaults to 999', 'elementor-extras' ),
					'type'			=> Controls_Manager::NUMBER,
					'default'		=> '999',
					'min'			=> -9999999,
					'step'			=> 1,
					'condition'		=> [
						'image[url]!' => '',
					],
					'selectors'		=> [
						'.ee-tooltip.ee-tooltip-{{ID}}' => 'z-index: {{SIZE}};',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'opacity',
				[
					'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 1,
							'min' 	=> 0.10,
							'step' 	=> 0.01,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-hotspots img' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'image_border',
					'label' 	=> __( 'Image Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-hotspots img',
				]
			);

			$this->add_control(
				'image_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-hotspots img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'image_box_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-hotspots img',
					'separator'	=> '',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_hotspots',
			[
				'label' => __( 'Hotspots', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'pulse',
				[
					'label' 		=> __( 'Disable Pulse Effect', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '""',
					'return_value' 	=> 'none',
					'selectors'		=> [
						'{{WRAPPER}} .ee-hotspot__wrapper:before' => 'content: {{VALUE}};'
					]
				]
			);

			$this->add_control(
				'hotspots_padding',
				[
					'label' 		=> __( 'Text Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-hotspot__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'hotspots_border_radius',
				[
					'label' 	=> __( 'Border Radius', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 100,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 100,
							'min' 	=> 0,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-hotspot__wrapper' => 'border-radius: {{SIZE}}px;',
						'{{WRAPPER}} .ee-hotspot__wrapper:before' => 'border-radius: {{SIZE}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'hotspots_typography',
					'selector' 	=> '{{WRAPPER}} .ee-hotspot__wrapper',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'separator'	=> 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'hotspots',
					'selector' 		=> '{{WRAPPER}} .ee-hotspot__wrapper,
										{{WRAPPER}} .ee-hotspot__wrapper:before',
				]
			);

			$this->start_controls_tabs( 'tabs_hotspots_style' );

			$this->start_controls_tab(
				'tab_hotspots_default',
				[
					'label' => __( 'Default', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'hotspots_opacity',
					[
						'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 1,
								'min' 	=> 0.10,
								'step' 	=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-hotspot__wrapper' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->add_control(
					'hotspots_size',
					[
						'label' 	=> __( 'Size', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 2,
								'min' 	=> 0.5,
								'step'	=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-hotspot__wrapper' => 'transform: scale({{SIZE}})',
						],
					]
				);

				$this->add_control(
					'hotspots_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-hotspot__wrapper' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'hotspots_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
						    'type' 	=> Scheme_Color::get_type(),
						    'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .ee-hotspot__wrapper' 			=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} .ee-hotspot__wrapper:before' 	=> 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'hotspots_border',
						'label' 	=> __( 'Text Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-hotspot__wrapper',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'hotspots_box_shadow',
						'selector' 	=> '{{WRAPPER}} .ee-hotspot__wrapper',
						'separator'	=> ''
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_hotspots_hover',
				[
					'label' => __( 'Hover', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'hotspots_hover_opacity',
					[
						'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 1,
								'min' 	=> 0.10,
								'step' 	=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->add_control(
					'hotspots_hover_size',
					[
						'label' 	=> __( 'Size', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 2,
								'min' 	=> 0.5,
								'step'	=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper' => 'transform: scale({{SIZE}})',
						],
					]
				);

				$this->add_control(
					'hotspots_hover_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'hotspots_hover_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
						    'type' 	=> Scheme_Color::get_type(),
						    'value' => Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper' 		=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper:before' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'hotspots_hover_border',
						'label' 	=> __( 'Text Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'hotspot_shover_box_shadow',
						'selector' 	=> '{{WRAPPER}} .ee-hotspot:hover .ee-hotspot__wrapper',
						'separator'	=> ''
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltips_style',
			[
				'label' => __( 'Tooltips', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'tooltips_align',
				[
					'label' 	=> __( 'Alignment', 'elementor-extras' ),
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

			$this->add_control(
				'tooltips_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'.ee-tooltip.ee-tooltip-{{ID}}' 					=> 'background-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--top:after' 		=> 'border-top-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--right:after' 	=> 'border-right-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--bottom:after' 	=> 'border-bottom-color: {{VALUE}};',
						'.ee-tooltip.ee-tooltip-{{ID}}.to--left:after' 		=> 'border-left-color: {{VALUE}};',
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
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'tooltips_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'tooltips_typography',
					'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'separator' => '',
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'tooltips_box_shadow',
					'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
					'separator'	=> '',
				]
			);

		$this->end_controls_section();
		
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['image']['url'] ) )
			return;

		$this->add_render_attribute( 'container', 'class', 'ee-hotspots__container' );
		$this->add_render_attribute( 'wrapper', 'class', 'ee-hotspots' );

		?><div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

			<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>

			<?php if ( $settings['hotspots'] ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
				<?php foreach ( $settings['hotspots'] as $index => $item ) {

					$_has_icon 				= false;

					$hotspot_tag 			= 'div';
					$hotspot_key 			= $this->get_repeater_setting_key( 'hotspot', 'hotspots', $index );
					$wrapper_key 			= $this->get_repeater_setting_key( 'wrapper', 'hotspots', $index );
					$icon_key 				= $this->get_repeater_setting_key( 'icon', 'hotspots', $index );
					$text_key 				= $this->get_repeater_setting_key( 'text', 'hotspots', $index );
					$tooltip_key 			= $this->get_repeater_setting_key( 'content', 'hotspots', $index );

					$content_id 			= $this->get_id() . '_' . $item['_id'];

					$this->add_render_attribute( $wrapper_key, 'class', 'ee-hotspot__wrapper' );
					$this->add_render_attribute( $text_key, 'class', 'ee-hotspot-text' );

					$this->add_render_attribute( $tooltip_key, [
						'class' => 'hotip-content',
						'id'	=> 'hotip-content-' . $content_id,
					] );

					$this->add_render_attribute( $hotspot_key, [
						'class' => [
							'elementor-repeater-item-' . $item['_id'],
							'hotip',
							'ee-hotspot',
						],
						'data-hotips-content' 	=> '#hotip-content-' . $content_id,
						'data-hotips-position' 	=> $item['tooltip_position'],
						'data-hotips-class' 	=> [
							'ee-global',
							'ee-tooltip',
							'ee-tooltip-' . $this->get_id(),
						]
					] );

					if ( 'icon' === $item['hotspot'] && ! empty( $item['icon'] ) ) {
						$_has_icon = true;

						$this->add_render_attribute( $icon_key, 'class', esc_attr( $item['icon'] ) );
					} else {
						$this->add_inline_editing_attributes( $text_key, 'none' );
					}
					
					if ( $item['_item_id'] ) {
						$this->add_render_attribute( $hotspot_key, 'id', $item['_item_id'] );
					}

					if ( $item['css_classes'] ) {
						$this->add_render_attribute( $hotspot_key, 'class', $item['css_classes'] );
					}

					if ( ! empty( $item['link']['url'] ) && $settings['trigger'] !== 'click' ) {

						$hotspot_tag = 'a';

						$this->add_render_attribute( $hotspot_key, 'href', $item['link']['url'] );

						if ( $item['link']['is_external'] ) {
							$this->add_render_attribute( $hotspot_key, 'target', '_blank' );
						}

						if ( ! empty( $item['link']['nofollow'] ) ) {
							$this->add_render_attribute( $hotspot_key, 'rel', 'nofollow' );
						}
					}

					?>

					<<?php echo $hotspot_tag; ?> <?php echo $this->get_render_attribute_string( $hotspot_key ); ?>>
						<span <?php echo $this->get_render_attribute_string( $wrapper_key ); ?>>
							<span <?php echo $this->get_render_attribute_string( $text_key ); ?>>

								<?php if ( $_has_icon ) { ?>
									<i <?php echo $this->get_render_attribute_string( $icon_key ); ?>></i>
								<?php } else {
									echo $item['text'];
								} ?>
							</span>
						</span>
					</<?php echo $hotspot_tag; ?>>

					<span <?php echo $this->get_render_attribute_string( $tooltip_key ); ?>>
						<?php echo $this->parse_text_editor( $item['content'] ); ?>
					</span>

				<?php } ?>
				</div>
			<?php } ?>
		
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<# if ( '' !== settings.image.url ) {

			var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.image_size,
					dimension: settings.image_custom_dimension,
					model: editModel
				},

				widgetId 		= view.$el.data('id');
				currentItem 	= ( editSettings.activeItemIndex > 0 ) ? editSettings.activeItemIndex : false;

				view.addRenderAttribute( 'wrapper', 'class', 'ee-hotspots' );
				view.addRenderAttribute( 'container', 'class', 'ee-hotspots__container' );

				view.addRenderAttribute( 'image', 'src', elementor.imagesManager.getImageUrl( image ) );

			#><div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
				<img {{{ view.getRenderAttributeString( 'image' ) }}} />

				<# if ( settings.hotspots ) { #>
					<div {{{ view.getRenderAttributeString( 'container' ) }}}>
					<# _.each( settings.hotspots, function( item, index ) {

						var _has_icon 		= false,
								
							hotspotTag 		= 'div',
							hotspotKey 		= view.getRepeaterSettingKey( 'hotspot', 'hotspots', index ),
							wrapperKey 		= view.getRepeaterSettingKey( 'wrapper', 'hotspots', index ),
							iconKey 		= view.getRepeaterSettingKey( 'icon', 'hotspots', index ),
							textKey 		= view.getRepeaterSettingKey( 'text', 'hotspots', index ),
							tooltipKey 		= view.getRepeaterSettingKey( 'content', 'hotspots', index ),
							contentId 		= widgetId + '_' + item._id;

						view.addRenderAttribute( wrapperKey, 'class', 'ee-hotspot__wrapper' );
						view.addRenderAttribute( textKey, 'class', 'ee-hotspot-text' );

						view.addRenderAttribute( tooltipKey, 'class', 'hotip-content' );
						view.addRenderAttribute( tooltipKey, 'id', 'hotip-content-' + contentId );

						view.addRenderAttribute( hotspotKey, 'class', [
							'elementor-repeater-item-' + item._id,
							'hotip',
							'ee-hotspot',
						] );

						view.addRenderAttribute( hotspotKey, 'data-hotips-content', '#hotip-content-' + contentId );
						view.addRenderAttribute( hotspotKey, 'data-hotips-position', item.tooltip_position );
						view.addRenderAttribute( hotspotKey, 'data-hotips-class', [
							'ee-tooltip',
							'ee-tooltip-' + widgetId,
						] );

						if ( 'icon' === item.hotspot && '' !== item.icon ) {
							_has_icon = true;

							view.addRenderAttribute( iconKey, 'class', item.icon );
						} else {
							view.addInlineEditingAttributes( textKey, 'none' );
						}
						
						if ( item._item_id ) {
							view.addRenderAttribute( hotspotKey, 'id', item._item_id );
						}

						if ( item.css_classes ) {
							view.addRenderAttribute( hotspotKey, 'class', item.css_classes );
						}

						if ( '' !== item.link.url && settings.trigger !== 'click' ) {

							hotspotTag = 'a';

							view.addRenderAttribute( hotspotKey, 'href', item.link.url );
						}
					#>
					
						<{{ hotspotTag }} {{{ view.getRenderAttributeString( hotspotKey ) }}}>
							<span {{{ view.getRenderAttributeString( wrapperKey ) }}}>
								<span {{{ view.getRenderAttributeString( textKey ) }}}>
									<# if ( _has_icon ) { #>
									<i {{{ view.getRenderAttributeString( iconKey ) }}}></i>
									<# } else { #>
										{{ item.text }}
									<# } #>
								</span>
							</span>
						</{{ hotspotTag }}>

						<span {{{ view.getRenderAttributeString( tooltipKey ) }}}>
							{{{ item.content }}}
						</span>
					<# }); #>

					</div>
				<# } #>

			</div>
		<# } #><?php
	}
}
