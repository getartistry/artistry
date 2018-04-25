<?php
namespace ElementPack\Modules\Navbar\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use ElementPack\Modules\Navbar\ep_menu_walker;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Navbar extends Widget_Base {

	public function get_name() {
		return 'bdt-navbar';
	}

	public function get_title() {
		return esc_html__( 'Navbar', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_navbar_content',
			[
				'label' => esc_html__( 'Navbar', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'navbar',
			[
				'label'   => esc_html__( 'Select Menu', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => element_pack_get_menu(),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-container' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_offset',
			[
				'label' => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -150,
						'max' => 150,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-navbar-nav' => 'transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_height',
			[
				'label' => esc_html__( 'Height', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_parent_arrow',
			[
				'label'   => __( 'Parent Indicator', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'prefix_class' => 'bdt-navbar-parent-indicator-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dropdown_content',
			[
				'label' => esc_html__( 'Dropdown', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'dropdown_align',
			[
				'label'     => esc_html__( 'Dropdown Alignment', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_link_align',
			[
				'label'     => esc_html__( 'Item Alignment', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_padding',
			[
				'label'      => esc_html__( 'Dropdown Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-navbar-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_width',
			[
				'label' => esc_html__( 'Dropdown Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 350,
					],
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-dropdown' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dropdown_additional',
			[
				'label' => esc_html__( 'Additional', 'bdthemes-element-pack' ),
			]
		);

		//TODO
		// $this->add_control(
		// 	'dropdown_mode',
		// 	[
		// 		'label'   => esc_html__( 'Dropdown Mode', 'bdthemes-element-pack' ),
		// 		'type'    => Controls_Manager::SELECT,
		// 		'default' => 'hover',
		// 		'options' => [
		// 			'hover' => esc_html__('Hover', 'bdthemes-element-pack'),
		// 			'click' => esc_html__('Clicked', 'bdthemes-element-pack'),
		// 		],
		// 	]
		// );

		$this->add_control(
			'dropdown_delay_show',
			[
				'label' => esc_html__( 'Delay Show', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
			]
		);

		$this->add_control(
			'dropdown_delay_hide',
			[
				'label' => esc_html__( 'Delay Hide', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => ['size' => 800],
			]
		);

		$this->add_control(
			'dropdown_duration',
			[
				'label' => esc_html__( 'Dropdown Duration', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => ['size' => 200],
			]
		);

		$this->add_control(
			'dropdown_offset',
			[
				'label' => esc_html__( 'Dropdown Offset', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
			]
		);

		//TODO
		// $this->add_control(
		// 	'dropdown_dropbar',
		// 	[
		// 		'label'   => __( 'Dropdown as Dropbar', 'bdthemes-element-pack' ),
		// 		'type'    => Controls_Manager::SWITCHER,
		// 	]
		// );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_style',
			[
				'label' => esc_html__( 'Navbar', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->start_controls_tabs( 'menu_link_styles' );

		$this->start_controls_tab( 'menu_link_normal', [ 'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ) ] );


			$this->add_control(
				'menu_link_color',
				[
					'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-nav > li > a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'menu_link_background',
				[
					'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-nav > li > a' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'menu_spacing',
				[
					'label' => esc_html__( 'Gap', 'bdthemes-element-pack' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 25,
						],
					],
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-nav' => 'margin-left: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .bdt-navbar-nav > li' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'menu_border',
					'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
					'default'  => '1px',
					'selector' => '{{WRAPPER}} .bdt-navbar-nav > li > a',
				]
			);

			$this->add_control(
				'menu_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-navbar-nav > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'menu_typography_normal',
					'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .bdt-navbar-nav > li > a',
				]
			);

			$this->add_control(
				'menu_parent_arrow_color',
				[
					'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-nav > li.bdt-parent a:after' => 'color: {{VALUE}};',
					],
					'condition' => ['menu_parent_arrow' => 'yes'],
				]
			);

		$this->end_controls_tab();

		$this->start_controls_tab( 'menu_link_hover', [ 'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ) ] );

		$this->add_control(
			'menu_link_color_hover',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'link_background_hover',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-navbar-nav > li > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typography_hover',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-navbar-nav > li > a:hover',
			]
		);

		$this->add_control(
			'menu_parent_arrow_color_hover',
			[
				'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-nav > li.bdt-parent a:hover::after' => 'color: {{VALUE}};',
				],
				'condition' => ['menu_parent_arrow' => 'yes'],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'menu_link_active', [ 'label' => esc_html__( 'Active', 'bdthemes-element-pack' ) ] );

			$this->add_control(
				'menu_hover_color_active',
				[
					'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-nav > li.bdt-active > a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'menu_hover_background_color_active',
				[
					'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-nav > li.bdt-active > a' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'menu_border_active',
					'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
					'default'  => '1px',
					'selector' => '{{WRAPPER}} .bdt-navbar-nav > li.bdt-active > a',
				]
			);

			$this->add_control(
				'menu_border_radius_active',
				[
					'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-navbar-nav > li.bdt-active > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'menu_typography_active',
					'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .bdt-navbar-nav > li.bdt-active > a',
				]
			);

			$this->add_control(
				'menu_parent_arrow_color_active',
				[
					'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-nav > li.bdt-parent.bdt-active a:after' => 'color: {{VALUE}};',
					],
					'condition' => ['menu_parent_arrow' => 'yes'],
				]
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'dropdown_color',
			[
				'label' => esc_html__( 'Dropdown', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SECTION,
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dropdown_background',
			[
				'label'     => esc_html__( 'Dropdown Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-navbar-dropdown' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->start_controls_tabs( 'dropdown_link_styles' );

			$this->start_controls_tab( 'dropdown_link_normal', [ 'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ) ] );


				$this->add_control(
					'dropdown_link_color',
					[
						'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'dropdown_link_background',
					[
						'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'dropdown_link_spacing',
					[
						'label' => esc_html__( 'Gap', 'bdthemes-element-pack' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 25,
							],
						],
						'size_units' => [ 'px'],
						'selectors' => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'dropdown_link_padding',
					[
						'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name'     => 'dropdown_link_border',
						'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
						'default'  => '1px',
						'selector' => '{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a',
					]
				);

				$this->add_control(
					'dropdown_link_radius',
					[
						'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'dropdown_link_typography',
						'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
						'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a',
					]
				);

				$this->add_control(
					'dropdown_parent_arrow_color',
					[
						'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-dropdown-nav > li.bdt-parent a:after' => 'color: {{VALUE}};',
						],
						'condition' => ['menu_parent_arrow' => 'yes'],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'dropdown_link_hover', [ 'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ) ] );

			$this->add_control(
				'dropdown_link_hover_color',
				[
					'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a:hover' => 'color: {{VALUE}};',
					],
				]
			);


			$this->add_control(
				'dropdown_link_hover_bg_color',
				[
					'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'dropdown_border_hover_color',
				[
					'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'dropdown_radius_hover',
				[
					'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'dropdown_typography_hover',
					'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .bdt-navbar-dropdown-nav > li > a:hover',
				]
			);

			$this->add_control(
				'dropdown_parent_arrow_color_hover',
				[
					'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-dropdown-nav > li.bdt-parent a:hover::after' => 'color: {{VALUE}};',
					],
					'condition' => ['menu_parent_arrow' => 'yes'],
				]
			);


			$this->end_controls_tab();

			$this->start_controls_tab( 'dropdown_link_active', [ 'label' => esc_html__( 'Active', 'bdthemes-element-pack' ) ] );

				$this->add_control(
					'dropdown_active_color',
					[
						'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li.bdt-active > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'dropdown_active_bg_color',
					[
						'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li.bdt-active > a' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name'     => 'dropdown_active_border',
						'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
						'default'  => '1px',
						'selector' => '{{WRAPPER}} .bdt-navbar-dropdown-nav > li.bdt-active > a',
					]
				);

				$this->add_control(
					'dropdown_active_radius',
					[
						'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .bdt-navbar-dropdown-nav > li.bdt-active > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'dropdown_typography_active',
						'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
						'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .bdt-navbar-dropdown-nav > li.bdt-active > a',
					]
				);

				$this->add_control(
					'dropdown_parent_arrow_color_active',
					[
						'label'     => esc_html__( 'Parent Indicator Color', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}}.bdt-navbar-parent-indicator-yes .bdt-navbar-dropdown-nav > li.bdt-parent.bdt-active a:after' => 'color: {{VALUE}};',
						],
						'condition' => ['menu_parent_arrow' => 'yes'],
					]
				);


			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// $this->start_controls_section( //TODO
		// 	'dropbar_style',
		// 	[
		// 		'label' => esc_html__( 'Dropbar', 'bdthemes-element-pack' ),
		// 		'tab'   => Controls_Manager::TAB_STYLE,
		// 		'condition' => ['dropdown_dropbar' => 'yes'],
		// 	]
		// );

		// $this->add_control(
		// 	'dropbar_background',
		// 	[
		// 		'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
		// 		'type'      => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .bdt-navbar-dropbar' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );


		// $this->end_controls_section();
	}

	protected function render() {

		$settings      = $this->get_settings();
		$nav_menu      = ! empty( $settings['navbar'] ) ? wp_get_nav_menu_object( $settings['navbar'] ) : false;
		$navbar_attr   = [];
		if ( ! $nav_menu ) {
			return;
		}

		$nav_menu_args = array(
			'fallback_cb'    => false,
			'container'      => false,
			'menu_id'        => 'bdt-navmenu',
			'menu_class'     => 'bdt-navbar-nav',
			'theme_location' => 'default_navmenu', // creating a fake location for better functional control
			'menu'           => $nav_menu,
			'echo'           => true,
			'depth'          => 0,
			'walker'        => new ep_menu_walker
		);

		$navbar_attr['bdt-navbar'] = wp_json_encode(array_filter([
			'align'      => ($settings['dropdown_align']) ? $settings['dropdown_align'] : 'left',
			//'mode'       => $settings['dropdown_mode'], //TODO
			'delay-show' => ($settings['dropdown_delay_show']['size']) ? $settings['dropdown_delay_show']['size'] : false,
			'delay-hide' => ($settings['dropdown_delay_hide']['size']) ? $settings['dropdown_delay_hide']['size'] : false,
			'offset'     => ($settings['dropdown_offset']['size']) ? $settings['dropdown_offset']['size'] : false,
			'duration'   => ($settings['dropdown_duration']['size']) ? $settings['dropdown_duration']['size'] : false,
			//'dropbar'    => ('yes' === $settings['dropdown_dropbar']) ? true : false, //TODO

		]));

		?>
		<div id="bdt-navbar-<?php echo $this->get_id(); ?>" class="bdt-navbar-wrapper">
			<nav class="bdt-navbar-container bdt-navbar bdt-navbar-transparent" <?php echo \element_pack_helper::attrs($navbar_attr); ?>>
				<?php wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $settings ) ); ?>
			</nav>
		</div>
	<?php
	}
}