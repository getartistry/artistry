<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Business Hours Widget
 */
class PP_Business_Hours_Widget extends Widget_Base {
    
    /**
	 * Retrieve business hours widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-business-hours';
    }

    /**
	 * Retrieve business hours widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Business Hours', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the business hours widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve business hours widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'fa fa-clock-o power-pack-admin-icon';
    }

    /**
	 * Register business hours widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Business Hours
         */
        $this->start_controls_section(
            'section_price_menu',
            [
                'label'             => __( 'Business Hours', 'power-pack' ),
            ]
        );

		$this->add_control(
			'business_hours',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
				],
				'fields'            => [
                    [
                        'name'        => 'day',
                        'label'                => __( 'Day', 'power-pack' ),
                        'type'                 => Controls_Manager::SELECT,
                        'default'              => 'Monday',
                        'options'              => [
                            'Monday'    => __( 'Monday', 'power-pack' ),
                            'Tuesday'   => __( 'Tuesday', 'power-pack' ),
                            'Wednesday' => __( 'Wednesday', 'power-pack' ),
                            'Thursday'  => __( 'Thursday', 'power-pack' ),
                            'Friday'    => __( 'Friday', 'power-pack' ),
                            'Saturday'  => __( 'Saturday', 'power-pack' ),
                            'Sunday'    => __( 'Sunday', 'power-pack' ),
                        ],
                    ],
                    [
                        'name'        => 'closed',
                        'label'             => __( 'Closed?', 'power-pack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'No', 'power-pack' ),
                        'label_off'         => __( 'Yes', 'power-pack' ),
                        'return_value'      => 'no',
                    ],
                    [
                        'name'        => 'opening_hours',
                        'label'                => __( 'Opening Hours', 'power-pack' ),
                        'type'                 => Controls_Manager::SELECT,
                        'default'              => '08:00',
                        'options'              => [
                            '00:00'    => '12:00 AM',
                            '00:30'    => '12:30 AM',
                            '01:00'    => '1:00 AM',
                            '01:30'    => '1:30 AM',
                            '02:00'    => '2:00 AM',
                            '02:30'    => '2:30 AM',
                            '03:00'    => '3:00 AM',
                            '03:30'    => '3:30 AM',
                            '04:00'    => '4:00 AM',
                            '04:30'    => '4:30 AM',
                            '05:00'    => '5:00 AM',
                            '05:30'    => '5:30 AM',
                            '06:00'    => '6:00 AM',
                            '06:30'    => '6:30 AM',
                            '07:00'    => '7:00 AM',
                            '07:30'    => '7:30 AM',
                            '08:00'    => '8:00 AM',
                            '08:30'    => '8:30 AM',
                            '09:00'    => '9:00 AM',
                            '09:30'    => '9:30 AM',
                            '10:00'    => '10:00 AM',
                            '10:30'    => '10:30 AM',
                            '11:00'    => '11:00 AM',
                            '11:30'    => '11:30 AM',
                            '12:00'    => '12:00 PM',
                            '12:30'    => '12:30 PM',
                            '13:00'    => '1:00 PM',
                            '13:30'    => '1:30 PM',
                            '14:00'    => '2:00 PM',
                            '14:30'    => '2:30 PM',
                            '15:00'    => '3:00 PM',
                            '15:30'    => '3:30 PM',
                            '16:00'    => '4:00 PM',
                            '16:30'    => '4:30 PM',
                            '17:00'    => '5:00 PM',
                            '17:30'    => '5:30 PM',
                            '18:00'    => '6:00 PM',
                            '18:30'    => '6:30 PM',
                            '19:00'    => '7:00 PM',
                            '19:30'    => '7:30 PM',
                            '20:00'    => '8:00 PM',
                            '20:30'    => '8:30 PM',
                            '21:00'    => '9:00 PM',
                            '21:30'    => '9:30 PM',
                            '22:00'    => '10:00 PM',
                            '22:30'    => '10:30 PM',
                            '23:00'    => '11:00 PM',
                            '23:30'    => '11:30 PM',
                            '24:00'    => '12:00 PM',
                            '24:30'    => '12:30 PM',
                        ],
                        'condition'         => [
                            'closed' => 'no',
                        ],
                    ],
                    [
                        'name'        => 'closing_hours',
                        'label'                => __( 'Closing Hours', 'power-pack' ),
                        'type'                 => Controls_Manager::SELECT,
                        'default'              => '19:00',
                        'options'              => [
                            '00:00'    => '12:00 AM',
                            '00:30'    => '12:30 AM',
                            '01:00'    => '1:00 AM',
                            '01:30'    => '1:30 AM',
                            '02:00'    => '2:00 AM',
                            '02:30'    => '2:30 AM',
                            '03:00'    => '3:00 AM',
                            '03:30'    => '3:30 AM',
                            '04:00'    => '4:00 AM',
                            '04:30'    => '4:30 AM',
                            '05:00'    => '5:00 AM',
                            '05:30'    => '5:30 AM',
                            '06:00'    => '6:00 AM',
                            '06:30'    => '6:30 AM',
                            '07:00'    => '7:00 AM',
                            '07:30'    => '7:30 AM',
                            '08:00'    => '8:00 AM',
                            '08:30'    => '8:30 AM',
                            '09:00'    => '9:00 AM',
                            '09:30'    => '9:30 AM',
                            '10:00'    => '10:00 AM',
                            '10:30'    => '10:30 AM',
                            '11:00'    => '11:00 AM',
                            '11:30'    => '11:30 AM',
                            '12:00'    => '12:00 PM',
                            '12:30'    => '12:30 PM',
                            '13:00'    => '1:00 PM',
                            '13:30'    => '1:30 PM',
                            '14:00'    => '2:00 PM',
                            '14:30'    => '2:30 PM',
                            '15:00'    => '3:00 PM',
                            '15:30'    => '3:30 PM',
                            '16:00'    => '4:00 PM',
                            '16:30'    => '4:30 PM',
                            '17:00'    => '5:00 PM',
                            '17:30'    => '5:30 PM',
                            '18:00'    => '6:00 PM',
                            '18:30'    => '6:30 PM',
                            '19:00'    => '7:00 PM',
                            '19:30'    => '7:30 PM',
                            '20:00'    => '8:00 PM',
                            '20:30'    => '8:30 PM',
                            '21:00'    => '9:00 PM',
                            '21:30'    => '9:30 PM',
                            '22:00'    => '10:00 PM',
                            '22:30'    => '10:30 PM',
                            '23:00'    => '11:00 PM',
                            '23:30'    => '11:30 PM',
                            '24:00'    => '12:00 PM',
                            '24:30'    => '12:30 PM',
                        ],
                        'condition'         => [
                            'closed' => 'no',
                        ],
                    ],
                    [
                        'name'        => 'closed_text',
                        'label'       => __( 'Closed Text', 'power-pack' ),
                        'type'        => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => __( 'Closed', 'power-pack' ),
                        'default'     => __( 'Closed', 'power-pack' ),
                        'conditions'  => [
                            'terms' => [
                                [
                                    'name' => 'closed',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ],
                            ],
                        ],
                    ],
                    [
                        'name'              => 'highlight',
                        'label'             => __( 'Highlight', 'power-pack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'Yes', 'power-pack' ),
                        'label_off'         => __( 'No', 'power-pack' ),
                        'return_value'      => 'yes',
                    ],
                    [
                        'name'              => 'highlight_bg',
                        'label'             => __( 'Background Color', 'power-pack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ],
                    [
                        'name'              => 'highlight_color',
                        'label'             => __( 'Text Color', 'power-pack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-day, {{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-timing' => 'color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ]
				],
				'title_field'       => '{{{ day }}}',
			]
		);
        
        $this->add_control(
          'hours_format',
            [
                'label'             => __( '24 Hours Format?', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'days_format',
            [
                'label'                => __( 'Days Format', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'long',
                'options'              => [
                    'long'      => __( 'Long', 'power-pack' ),
                    'short'     => __( 'Short', 'power-pack' ),
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Row Style
         */
        $this->start_controls_section(
            'section_rows_style',
            [
                'label'             => __( 'Rows Style', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_rows_style' );

        $this->start_controls_tab(
            'tab_row_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'row_bg_color_normal',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_row_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'row_bg_color_hover',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_control(
          'stripes',
            [
                'label'             => __( 'Striped Rows', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'separator'         => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_alternate_style' );

        $this->start_controls_tab(
            'tab_even',
            [
                'label'                 => __( 'Even Row', 'power-pack' ),
                'condition'             => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_even_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#f5f5f5',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_even_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(even)' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_odd',
            [
                'label'                 => __( 'Odd Row', 'power-pack' ),
                'condition'             => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_odd_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#ffffff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_odd_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(odd)' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'rows_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-business-hours .pp-business-hours-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'         => 'before',
			]
		);
        
        $this->add_responsive_control(
            'rows_margin',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'closed_row_heading',
            [
                'label'             => __( 'Closed Row', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'closed_row_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'closed_row_day_color',
            [
                'label'             => __( 'Day Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'closed_row_tex_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'divider_heading',
            [
                'label'             => __( 'Rows Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_control(
            'rows_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'none',
                'options'              => [
                    'none'      => __( 'None', 'power-pack' ),
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'groove'    => __( 'Groove', 'power-pack' ),
                    'ridge'     => __( 'Ridge', 'power-pack' ),
                ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'rows_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'         => [
                    'rows_divider_style!' => 'none',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'rows_divider_weight',
            [
                'label'             => __( 'Divider Weight', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'default'           => [ 'size' => 1 ],
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'rows_divider_style!' => 'none',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Business Hours
         */
        $this->start_controls_section(
            'section_pricing_table_style',
            [
                'label'             => __( 'Business Hours', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_hours_style' );

        $this->start_controls_tab(
            'tab_hours_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'title_heading',
            [
                'label'             => __( 'Day', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'day_color',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'title_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-business-hours .pp-business-day',
            ]
        );
        
        $this->add_control(
            'hours_heading',
            [
                'label'             => __( 'Hours', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'hours_color',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'hours_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-business-hours .pp-business-timing',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hours_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'day_color_hover',
            [
                'label'             => __( 'Day Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hours_color_hover',
            [
                'label'             => __( 'Hours Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
	 * Render business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();

        $this->add_render_attribute( 'business-hours', 'class', 'pp-business-hours' );
        $i = 1;
        ?>
        <div <?php echo $this->get_render_attribute_string( 'business-hours' ); ?>>
            <?php foreach ( $settings['business_hours'] as $index => $item ) : ?>
                <?php
                    $this->add_render_attribute( 'row' . $i, 'class', 'pp-business-hours-row clearfix elementor-repeater-item-' . esc_attr( $item['_id'] ) );
                    if ( $item['closed'] != 'no' ) {
                        $this->add_render_attribute( 'row' . $i, 'class', 'row-closed' );
                    }
                ?>
                <div <?php echo $this->get_render_attribute_string( 'row' . $i ); ?>>
                    <span class="pp-business-day">
                        <?php
                            if ( $settings['days_format'] == 'long' ) {
                                echo ucwords( esc_attr( $item['day'] ) );
                            } else {
                                echo ucwords( esc_attr( substr($item['day'], 0, 3) ) );
                            }
                        ?>
                    </span>
                    <span class="pp-business-timing">
                        <?php if ( $item['closed'] == 'no' ) { ?>
                            <span class="pp-opening-hours">
                                <?php
                                    if ( $settings['hours_format'] == 'yes' ) {
                                        echo esc_attr( $item['opening_hours'] );
                                    } else {
                                        echo esc_attr( date( "g:i A", strtotime( $item['opening_hours'] ) ) );
                                    }
                                ?>
                            </span>
                            -
                            <span class="pp-closing-hours">
                                <?php
                                    if ( $settings['hours_format'] == 'yes' ) {
                                        echo esc_attr( $item['closing_hours'] );
                                    } else {
                                        echo esc_attr( date( "g:i A", strtotime( $item['closing_hours'] ) ) );
                                    }
                                ?>
                            </span>
                        <?php } else { esc_attr_e( 'Closed', 'power-pack' ); } ?>
                    </span>
                </div>
            <?php $i++; endforeach; ?>
        </div>
        <?php
    }

    /**
	 * Render business hours widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            function pp_timeTo12HrFormat(time) {
                // Take a time in 24 hour format and format it in 12 hour format
                var time_part_array = time.split(":");
                var ampm = 'AM';

                if (time_part_array[0] >= 12) {
                    ampm = 'PM';
                }

                if (time_part_array[0] > 12) {
                    time_part_array[0] = time_part_array[0] - 12;
                }

                formatted_time = time_part_array[0] + ':' + time_part_array[1] + ' ' + ampm;

                return formatted_time;
            }
        #>
        <div class="pp-business-hours">
            <# _.each( settings.business_hours, function( item ) { #>
                <#
                    var closed = ( item.closed != 'no' ) ? 'row-closed' : '';
                #>
                <div class="pp-business-hours-row clearfix elementor-repeater-item-{{ item._id }} {{ closed }}">
                    <span class="pp-business-day">
                        <# if ( settings.days_format == 'long' ) { #>
                            {{ item.day }}
                        <# } else { #>
                            {{ item.day.substring(0,3) }}
                        <# } #>
                    </span>
                    <span class="pp-business-timing">
                        <# if ( item.closed == 'no' ) { #>
                            <span class="pp-opening-hours">
                                <# if ( settings.hours_format == 'yes' ) { #>
                                    {{ item.opening_hours }}
                                <# } else { #>
                                    {{ pp_timeTo12HrFormat( item.opening_hours ) }}
                                <# } #>
                            </span>
                            -
                            <span class="pp-closing-hours">
                                <# if ( settings.hours_format == 'yes' ) { #>
                                    {{ item.closing_hours }}
                                <# } else { #>
                                    {{ pp_timeTo12HrFormat( item.closing_hours ) }}
                                <# } #>
                            </span>
                        <# } else { #>
                            <?php esc_attr_e( 'Closed', 'power-pack' ); ?>
                        <# } #>
                    </span>
                </div>
            <# } ); #>
        </div>
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new PP_Business_Hours_Widget() );