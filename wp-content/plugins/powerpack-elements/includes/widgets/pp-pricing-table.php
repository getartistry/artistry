<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table Widget
 */
class PP_Pricing_Table_Widget extends Widget_Base {
    
    /**
	 * Retrieve pricing table widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-pricing-table';
    }

    /**
	 * Retrieve pricing table widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Pricing Table', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the pricing table widget belongs to.
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
	 * Retrieve pricing table widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eicon-price-table power-pack-admin-icon';
    }

    /**
	 * Register pricing table widget controls.
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
         * Content Tab: Header
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_header',
            [
                'label'             => __( 'Header', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'none'        => [
						'title'   => esc_html__( 'None', 'power-pack' ),
						'icon'    => 'fa fa-ban',
					],
					'icon'        => [
						'title'   => esc_html__( 'Icon', 'power-pack' ),
						'icon'    => 'fa fa-info-circle',
					],
					'image'       => [
						'title'   => esc_html__( 'Image', 'power-pack' ),
						'icon'    => 'fa fa-picture-o',
					],
				],
				'default'               => 'none',
			]
		);

        $this->add_control(
            'table_icon',
            [
                'label'             => __( 'Icon', 'power-pack' ),
                'type'              => Controls_Manager::ICON,
				'condition'         => [
					'icon_type' => 'icon',
				],
            ]
        );
        
        $this->add_control(
            'icon_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'icon_type'  => 'image',
				],
            ]
        );

        $this->add_control(
            'table_title',
            [
                'label'             => __( 'Title', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __( 'Title', 'power-pack' ),
                'title'             => __( 'Enter table title', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_subtitle',
            [
                'label'             => __( 'Subtitle', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __( 'Subtitle', 'power-pack' ),
                'title'             => __( 'Enter table subtitle', 'power-pack' ),
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Pricing
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_pricing',
            [
                'label'             => __( 'Pricing', 'power-pack' ),
            ]
        );

		$this->add_control(
			'currency_symbol',
			[
				'label'             => __( 'Currency Symbol', 'power-pack' ),
				'type'              => Controls_Manager::SELECT,
				'options'           => [
					''             => __( 'None', 'power-pack' ),
					'dollar'       => '&#36; ' . __( 'Dollar', 'power-pack' ),
					'euro'         => '&#128; ' . __( 'Euro', 'power-pack' ),
					'baht'         => '&#3647; ' . __( 'Baht', 'power-pack' ),
					'franc'        => '&#8355; ' . __( 'Franc', 'power-pack' ),
					'guilder'      => '&fnof; ' . __( 'Guilder', 'power-pack' ),
					'krona'        => 'kr ' . __( 'Krona', 'power-pack' ),
					'lira'         => '&#8356; ' . __( 'Lira', 'power-pack' ),
					'peseta'       => '&#8359 ' . __( 'Peseta', 'power-pack' ),
					'peso'         => '&#8369; ' . __( 'Peso', 'power-pack' ),
					'pound'        => '&#163; ' . __( 'Pound Sterling', 'power-pack' ),
					'real'         => 'R$ ' . __( 'Real', 'power-pack' ),
					'ruble'        => '&#8381; ' . __( 'Ruble', 'power-pack' ),
					'rupee'        => '&#8360; ' . __( 'Rupee', 'power-pack' ),
					'indian_rupee' => '&#8377; ' . __( 'Rupee (Indian)', 'power-pack' ),
					'shekel'       => '&#8362; ' . __( 'Shekel', 'power-pack' ),
					'yen'          => '&#165; ' . __( 'Yen/Yuan', 'power-pack' ),
					'won'          => '&#8361; ' . __( 'Won', 'power-pack' ),
					'custom'       => __( 'Custom', 'power-pack' ),
				],
				'default'           => 'dollar',
			]
		);

        $this->add_control(
            'table_price',
            [
                'label'             => __( 'Price', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '49',
            ]
        );
        
        $this->add_control(
            'discount',
            [
                'label'             => __( 'Discount', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => '',
                'label_on'          => __( 'On', 'power-pack' ),
                'label_off'         => __( 'Off', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->add_control(
            'table_original_price',
            [
                'label'             => __( 'Original Price', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '69',
				'condition'         => [
					'discount' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_duration',
            [
                'label'             => __( 'Duration', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __( 'per month', 'power-pack' ),
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Features
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_features',
            [
                'label'             => __( 'Features', 'power-pack' ),
            ]
        );

		$this->add_control(
			'table_features',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'feature_text' => __( 'Feature #1', 'power-pack' ),
						'feature_icon' => 'fa fa-check',
					],
					[
						'feature_text' => __( 'Feature #2', 'power-pack' ),
						'feature_icon' => 'fa fa-check',
					],
					[
						'feature_text' => __( 'Feature #3', 'power-pack' ),
						'feature_icon' => 'fa fa-check',
					],
				],
				'fields'            => [
					[
						'name'        => 'feature_text',
						'label'       => __( 'Text', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Feature', 'power-pack' ),
						'default'     => __( 'Feature', 'power-pack' ),
					],
                    [
                        'name'        => 'exclude',
                        'label'             => __( 'Exclude', 'power-pack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => '',
                        'label_on'          => __( 'Yes', 'power-pack' ),
                        'label_off'         => __( 'No', 'power-pack' ),
                        'return_value'      => 'yes',
                    ],
					[
						'name'        => 'feature_icon',
						'label'       => __( 'Icon', 'power-pack' ),
						'type'        => Controls_Manager::ICON,
				        'default'     => 'fa fa-check',
					],
					[
						'name'        => 'feature_icon_color',
						'label'       => __( 'Icon Color', 'power-pack' ),
						'type'        => Controls_Manager::COLOR,
				        'default'     => '',
                        'selectors'   => [
                            '{{WRAPPER}} {{CURRENT_ITEM}} .fa' => 'color: {{VALUE}}',
                        ],
					],
					[
						'name'        => 'feature_text_color',
						'label'       => __( 'Text Color', 'power-pack' ),
						'type'        => Controls_Manager::COLOR,
				        'default'     => '',
                        'selectors'   => [
                            '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                        ],
					],
					[
						'name'        => 'feature_bg_color',
						'label'       => __( 'Background Color', 'power-pack' ),
						'type'        => Controls_Manager::COLOR,
				        'default'     => '',
                        'selectors'   => [
                            '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                        ],
					],
				],
				'title_field'       => '{{{ feature_text }}}',
			]
		);

        $this->end_controls_section();

        /**
         * Content Tab: Footer
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_footer',
            [
                'label'             => __( 'Footer', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_button_text',
            [
                'label'             => __( 'Button Text', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __( 'Get Started', 'power-pack' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'             => __( 'Link', 'power-pack' ),
                'type'              => Controls_Manager::URL,
                'placeholder'       => 'https://www.your-link.com',
                'default'           => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'table_additional_info',
            [
                'label'             => __( 'Additional Info', 'power-pack' ),
                'type'              => Controls_Manager::TEXTAREA,
                'default'           => __( 'Enter additional info here', 'power-pack' ),
                'title'             => __( 'Additional Info', 'power-pack' ),
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Alignment
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_alignment',
            [
                'label'             => __( 'Alignment', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'table_align',
			[
				'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'           => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'           => '',
                'prefix_class'      => 'pp-pricing-table-align-'
			]
		);
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Header
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_header_style',
            [
                'label'             => __( 'Header', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_title_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_3,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-head' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'table_header_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'condition'         => [
					'table_button_text!' => '',
				],
				'selector'          => '{{WRAPPER}} .pp-pricing-table-head',
			]
		);

		$this->add_responsive_control(
			'table_title_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'table_title_icon',
            [
                'label'             => __( 'Icon', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'icon_type!' => 'none',
				],
            ]
        );
        
        $this->add_responsive_control(
            'table_icon_size',
            [
                'label'             => __( 'Size', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px', 'em' ],
				'condition'         => [
                    'icon_type'   => 'icon',
					'table_icon!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'table_icon_image_width',
            [
                'label'             => __( 'Width', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px', '%' ],
				'condition'         => [
                    'icon_type'   => 'image',
					'table_icon!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-icon' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'table_icon_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
                    'icon_type!' => 'none',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_icon_color',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'icon_type'   => 'icon',
					'table_icon!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'table_icon_margin',
			[
				'label'             => __( 'Margin', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'condition'         => [
					'icon_type!' => 'none',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_icon_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'condition'         => [
					'icon_type!' => 'none',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'table_icon_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'condition'         => [
					'icon_type!' => 'none',
				],
				'selector'          => '{{WRAPPER}} .pp-pricing-table-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'condition'         => [
					'icon_type!' => 'none',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-icon, {{WRAPPER}} .pp-pricing-table-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'table_title_heading',
            [
                'label'             => __( 'Title', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'table_title_color',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'table_title_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-pricing-table-title',
            ]
        );
        
        $this->add_control(
            'table_subtitle_heading',
            [
                'label'             => __( 'Sub Title', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'table_subtitle!' => '',
				],
            ]
        );

        $this->add_control(
            'table_subtitle_color',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
				'condition'         => [
					'table_subtitle!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'table_subtitle_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
				'condition'         => [
					'table_subtitle!' => '',
				],
                'selector'          => '{{WRAPPER}} .pp-pricing-table-subtitle',
            ]
        );
        
        $this->add_responsive_control(
            'table_subtitle_spacing',
            [
                'label'             => __( 'Spacing', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px', '%' ],
				'condition'         => [
					'table_subtitle!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-subtitle' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Pricing
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_pricing_style',
            [
                'label'             => __( 'Pricing', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'table_pricing_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-pricing-table-price',
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'table_price_color_normal',
            [
                'label'             => __( 'Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_price_bg_color_normal',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#e6e6e6',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'price_border_normal',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-pricing-table-price',
			]
		);

		$this->add_control(
			'pricing_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'table_pricing_width',
            [
                'label'             => __( 'Width', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    '%' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    'px' => [
                        'min'   => 25,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px', '%' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'table_price_margin',
			[
				'label'             => __( 'Margin', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_price_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pa_logo_wrapper_shadow',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-price',
			]
		);
        
        $this->add_control(
            'table_curreny_heading',
            [
                'label'             => __( 'Currency Symbol', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

		$this->add_control(
			'currency_vertical_position',
			[
				'label'             => __( 'Vertical Position', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'           => [
					'top'       => [
						'title' => __( 'Top', 'power-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle'    => [
						'title' => __( 'Middle', 'power-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'power-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'           => 'top',
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-price-prefix' => 'align-self: {{VALUE}}',
				],
			]
		);
        
        $this->add_control(
            'table_duration_heading',
            [
                'label'             => __( 'Duration', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_control(
          'duration_position',
          [
             'label'                => __( 'Duration Position', 'power-pack' ),
             'type'                 => Controls_Manager::SELECT,
             'default'              => 'nowrap',
             'options'              => [
                'nowrap'    => __( 'Same Line', 'power-pack' ),
                'wrap'      => __( 'Next Line', 'power-pack' ),
             ],
            'prefix_class' => 'pp-pricing-table-price-duration-'
          ]
        );

        $this->add_control(
            'duration_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price-duration' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'duration_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'selector'          => '{{WRAPPER}} .pp-pricing-table-price-duration',
            ]
        );
        
        $this->add_responsive_control(
            'duration_spacing',
            [
                'label'             => __( 'Spacing', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}}.pp-pricing-table-price-duration-wrap .pp-pricing-table-price-duration' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
				'condition'         => [
					'duration_position' => 'wrap',
				],
            ]
        );
        
        $this->add_control(
            'table_original_price_style_heading',
            [
                'label'             => __( 'Original Price', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'discount' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_original_price_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'discount' => 'yes',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price-original' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'table_original_price_text_size',
            [
                'label'             => __( 'Font Size', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px', 'em' ],
				'condition'         => [
					'discount' => 'yes',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-price-original' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Features
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_features_style',
            [
                'label'             => __( 'Features', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'table_features_align',
			[
				'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'           => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-features'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'table_features_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_features_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'table_features_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'table_features_margin',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'table_features_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-pricing-table-features',
                'separator'         => 'before',
            ]
        );
        
        $this->add_control(
            'table_features_icon_heading',
            [
                'label'             => __( 'Icon', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'table_features_icon_spacing',
            [
                'label'             => __( 'Spacing', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-fature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'table_features_rows_heading',
            [
                'label'             => __( 'Rows', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'table_features_spacing',
            [
                'label'             => __( 'Spacing', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'table_features_alternate',
            [
                'label'             => __( 'Striped Rows', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => '',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

		$this->add_responsive_control(
			'table_features_rows_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_features_style' );

        $this->start_controls_tab(
            'tab_features_even',
            [
                'label'             => __( 'Even', 'power-pack' ),
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_features_bg_color_even',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_features_text_color_even',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'color: {{VALUE}}',
                ],
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_features_odd',
            [
                'label'             => __( 'Odd', 'power-pack' ),
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_features_bg_color_odd',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_features_text_color_odd',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'color: {{VALUE}}',
                ],
				'condition'         => [
					'table_features_alternate' => 'yes',
				],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_control(
            'table_divider_heading',
            [
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'table_feature_divider',
				'label'             => __( 'Divider', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-pricing-table-features li',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Footer
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_footer_style',
            [
                'label'             => __( 'Footer', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_footer_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-footer' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'table_footer_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'table_button_heading',
            [
                'label'             => __( 'Button', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'table_button_text!' => '',
				],
            ]
        );

		$this->add_control(
			'table_button_size',
			[
				'label'             => __( 'Size', 'power-pack' ),
				'type'              => Controls_Manager::SELECT,
				'default'           => 'md',
				'options'           => [
					'xs' => __( 'Extra Small', 'power-pack' ),
					'sm' => __( 'Small', 'power-pack' ),
					'md' => __( 'Medium', 'power-pack' ),
					'lg' => __( 'Large', 'power-pack' ),
					'xl' => __( 'Extra Large', 'power-pack' ),
				],
				'condition'         => [
					'table_button_text!' => '',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'             => __( 'Normal', 'power-pack' ),
				'condition'         => [
					'table_button_text!' => '',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_button_text!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_button_text!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-button' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'button_border_normal',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'condition'         => [
					'table_button_text!' => '',
				],
				'selector'          => '{{WRAPPER}} .pp-pricing-table-button',
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'button_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
				'condition'         => [
					'table_button_text!' => '',
				],
                'selector'          => '{{WRAPPER}} .pp-pricing-table-button',
            ]
        );

		$this->add_responsive_control(
			'table_button_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'condition'         => [
					'table_button_text!' => '',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'condition'         => [
					'table_button_text!' => '',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'pa_pricing_table_button_shadow',
				'condition'         => [
					'table_button_text!' => '',
				],
				'selector'          => '{{WRAPPER}} .pp-pricing-table-button',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'             => __( 'Hover', 'power-pack' ),
				'condition'         => [
					'table_button_text!' => '',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_button_text!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_button_text!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'button_border_hover',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'condition'         => [
					'table_button_text!' => '',
				],
				'selector'          => '{{WRAPPER}} .pp-pricing-table-button:hover',
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label'             => __( 'Animation', 'power-pack' ),
				'type'              => Controls_Manager::HOVER_ANIMATION,
				'condition'         => [
					'table_button_text!' => '',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_control(
            'table_additional_info_heading',
            [
                'label'             => __( 'Additional Info', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'table_additional_info!' => '',
				],
            ]
        );

        $this->add_control(
            'additional_info_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_additional_info!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-additional-info' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'additional_info_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
				'condition'         => [
					'table_additional_info!' => '',
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-additional-info' => 'background: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'additional_info_margin',
            [
                'label'             => __( 'Margin Top', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-pricing-table-additional-info' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
				'condition'         => [
					'table_additional_info!' => '',
				],
            ]
        );

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'condition'         => [
					'table_additional_info!' => '',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'additional_info_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
				'condition'         => [
					'table_additional_info!' => '',
				],
                'selector'          => '{{WRAPPER}} .pp-pricing-table-additional-info',
            ]
        );
        
        $this->end_controls_section();

    }

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar'         => '&#36;',
			'euro'           => '&#128;',
			'franc'          => '&#8355;',
			'pound'          => '&#163;',
			'ruble'          => '&#8381;',
			'shekel'         => '&#8362;',
			'baht'           => '&#3647;',
			'yen'            => '&#165;',
			'won'            => '&#8361;',
			'guilder'        => '&fnof;',
			'peso'           => '&#8369;',
			'peseta'         => '&#8359',
			'lira'           => '&#8356;',
			'rupee'          => '&#8360;',
			'indian_rupee'   => '&#8377;',
			'real'           => 'R$',
			'krona'          => 'kr',
		];
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

    /**
	 * Render pricing table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
		$symbol = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}
        
        $this->add_inline_editing_attributes( 'table_title', 'none' );
        $this->add_render_attribute( 'table_title', 'class', 'pp-pricing-table-title' );
        
        $this->add_inline_editing_attributes( 'table_subtitle', 'none' );
        $this->add_render_attribute( 'table_subtitle', 'class', 'pp-pricing-table-subtitle' );
        
        $this->add_inline_editing_attributes( 'table_price', 'none' );
        $this->add_render_attribute( 'table_price', 'class', 'pp-pricing-table-price-value' );
        
        $this->add_inline_editing_attributes( 'table_duration', 'none' );
        $this->add_render_attribute( 'table_duration', 'class', 'pp-pricing-table-price-duration' );
        
        $this->add_inline_editing_attributes( 'table_additional_info', 'none' );
        $this->add_render_attribute( 'table_additional_info', 'class', 'pp-pricing-table-additional-info' );
        
        $this->add_render_attribute( 'pricing-table', 'class', 'pp-pricing-table' );
        
        $this->add_render_attribute( 'feature-list-item', 'class', '' );
        
        $this->add_inline_editing_attributes( 'table_button_text', 'none' );
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'table_button_text', 'class', $settings['link']['url'] );
        }
        
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'table_button_text', 'href', $settings['link']['url'] );

            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'table_button_text', 'target', '_blank' );
            }
        }
        
        $this->add_render_attribute( 'pricing-table-duration', 'class', 'pp-pricing-table-price-duration' );
		if ( $settings['duration_position'] == 'wrap' ) {
            $this->add_render_attribute( 'pricing-table-duration', 'class', 'next-line' );
        }
        
        $this->add_render_attribute( 'table_button_text', 'class', [
				'pp-pricing-table-button',
				'elementor-button',
				'elementor-size-' . $settings['table_button_size'],
			]
		);
        
        if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'table_button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
        ?>
        <div <?php echo $this->get_render_attribute_string( 'pricing-table' ); ?>>
            <div class="pp-pricing-table-head">
                <?php if ( $settings['icon_type'] != 'none' ) { ?>
                    <div class="pp-pricing-table-icon-wrap">
                        <?php if ( $settings['icon_type'] == 'icon' ) { ?>
                            <?php if ( ! empty( $settings['table_icon'] ) ) { ?>
                                <span class="pp-pricing-table-icon <?php echo esc_attr( $settings['table_icon'] ); ?>" aria-hidden="true"></span>
                            <?php } ?>
                        <?php } else if ( $settings['icon_type'] == 'image' ) { ?>
                            <?php $image = $settings['icon_image'];
                            if ( $image['url'] ) { ?>
                                <span class="pp-pricing-table-icon pp-pricing-table-icon-image">
                                    <img src="<?php echo esc_url( $image['url'] ); ?>">
                                </span>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="pp-pricing-table-title-wrap">
                    <?php if ( ! empty( $settings['table_title'] ) ) { ?>
                        <h3 <?php echo $this->get_render_attribute_string( 'table_title' ); ?>>
                            <?php echo esc_attr( $settings['table_title'] ); ?>
                        </h3>
                    <?php } ?>
                    <?php if ( ! empty( $settings['table_subtitle'] ) ) { ?>
                        <h4 <?php echo $this->get_render_attribute_string( 'table_subtitle' ); ?>>
                            <?php echo $settings['table_subtitle']; ?>
                        </h4>
                    <?php } ?>
                </div>
            </div>
            <?php if ( ! empty( $settings['table_price'] ) ) { ?>
                <div class="pp-pricing-table-price-wrap">
                    <div class="pp-pricing-table-price">
                        <?php if ( $settings['discount'] == 'yes' && ! empty( $settings['table_original_price'] ) ) { ?>
                            <span class="pp-pricing-table-price-original">
                                <?php
                                    echo $symbol . esc_attr( $settings['table_original_price'] );
                                ?>
                            </span>
                        <?php } ?>
                        <?php if ( ! empty( $symbol ) ) { ?>
                            <span class="pp-pricing-table-price-prefix">
                                <?php echo $symbol; ?>
                            </span>
                        <?php } ?>
                        <span <?php echo $this->get_render_attribute_string( 'table_price' ); ?>>
                            <?php echo intval( $settings['table_price'] ); ?>
                        </span>
                        <?php if ( ! empty( $settings['table_duration'] ) ) { ?>
                            <span <?php echo $this->get_render_attribute_string( 'table_duration' ); ?>>
                                <?php echo esc_attr( $settings['table_duration'] ); ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <ul class="pp-pricing-table-features">
                <?php foreach ( $settings['table_features'] as $index => $item ) : ?>
                    <?php
                        $feature_key = $this->get_repeater_setting_key( 'feature_text', 'table_features', $index );
                        $this->add_render_attribute( $feature_key, 'class', 'pp-pricing-table-feature-text' );
                        $this->add_inline_editing_attributes( $feature_key, 'none' );

                        $pa_class = '';
        
                        if ( $item['exclude'] == 'yes' ) {
                            $pa_class .= ' excluded';
                        } else {
                            $pa_class .= '';
                        }
                    ?>
                    <li class="elementor-repeater-item-<?php echo $item['_id'] . $pa_class; ?>">
                        <?php
                            if ( $item['feature_icon'] ) :
                                echo '<span class="pp-pricing-table-fature-icon ' . esc_attr( $item['feature_icon'] ) . '"></span>';
                            endif;
                        ?>
                        <?php if ( $item['feature_text'] ) { ?>
                            <span <?php echo $this->get_render_attribute_string( $feature_key ); ?>>
                                <?php echo esc_attr( $item['feature_text'] ); ?>
                            </span>
                        <?php } ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="pp-pricing-table-footer">
                <?php if ( ! empty( $settings['table_button_text'] ) ) { ?>
                    <a <?php echo $this->get_render_attribute_string( 'table_button_text' ); ?>>
                        <?php echo esc_attr( $settings['table_button_text'] ); ?>
                    </a>
                <?php } ?>
                <?php if ( ! empty( $settings['table_additional_info'] ) ) { ?>
                    <div <?php echo $this->get_render_attribute_string( 'table_additional_info' ); ?>>
                        <?php echo esc_attr( $settings['table_additional_info'] ); ?>
                    </div>
                <?php } ?>
            </div>
        </div><!-- .pp-pricing-table -->
        <?php
    }

    /**
	 * Render pricing table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            var buttonClasses = 'pp-pricing-table-button elementor-button elementor-size-' + settings.table_button_size + ' elementor-animation-' + settings.button_hover_animation;
           
            var $i = 1,
			    symbols = {
                    dollar: '&#36;',
                    euro: '&#128;',
                    franc: '&#8355;',
                    pound: '&#163;',
                    ruble: '&#8381;',
                    shekel: '&#8362;',
                    baht: '&#3647;',
                    yen: '&#165;',
                    won: '&#8361;',
                    guilder: '&fnof;',
                    peso: '&#8369;',
                    peseta: '&#8359;',
                    lira: '&#8356;',
                    rupee: '&#8360;',
                    indian_rupee: '&#8377;',
                    real: 'R$',
                    krona: 'kr'
                },
                symbol = '';

			if ( settings.currency_symbol ) {
				if ( 'custom' !== settings.currency_symbol ) {
					symbol = symbols[ settings.currency_symbol ] || '';
				} else {
					symbol = settings.currency_symbol_custom;
				}
			}
        #>
        <div class="pp-pricing-table">
            <div class="pp-pricing-table-head">
                <# if ( settings.icon_type != 'none' ) { #>
                    <div class="pp-pricing-table-icon-wrap">
                        <# if ( settings.icon_type == 'icon' ) { #>
                            <# if ( settings.table_icon ) { #>
                                <span class="pp-pricing-table-icon {{ settings.table_icon }}" aria-hidden="true"></span>
                            <# } #>
                        <# } else if ( settings.icon_type == 'image' ) { #>
                            <span class="pp-pricing-table-icon pp-pricing-table-icon-image">
                                <# if ( settings.icon_image.url != '' ) { #>
                                    <img src="{{ settings.icon_image.url }}">
                                <# } #>
                            </span>
                        <# } #>
                    </div>
                <# } #>
                <div class="pp-pricing-table-title-wrap">
                    <# if ( settings.table_title ) { #>
                        <h3 class="pp-pricing-table-title elementor-inline-editing" data-elementor-setting-key="table_title" data-elementor-inline-editing-toolbar="none">
                            {{{ settings.table_title }}}
                        </h3>
                    <# } #>
                    <# if ( settings.table_subtitle ) { #>
                        <h4 class="pp-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
                            {{{ settings.table_subtitle }}}
                        </h4>
                    <# } #>
                </div>
            </div>
            <# if ( settings.table_price ) { #>
                <div class="pp-pricing-table-price-wrap">
                    <div class="pp-pricing-table-price">
                        <# if ( settings.discount === 'yes' && ! _.isEmpty( settings.table_original_price ) ) { #>
                            <span class="pp-pricing-table-price-original">
                                {{{ symbol }}}{{{ settings.table_original_price }}}
                            </span>
                        <# } #>
                        <# if (  ! _.isEmpty( symbol ) ) { #>
                            <span class="pp-pricing-table-price-prefix">{{{ symbol }}}</span>
                        <# } #>
                        <span class="pp-pricing-table-price-value elementor-inline-editing" data-elementor-setting-key="table_price" data-elementor-inline-editing-toolbar="none">
                            {{{ settings.table_price }}}
                        </span>
                        <# if ( settings.table_duration ) { #>
                            <span class="pp-pricing-table-price-duration elementor-inline-editing" data-elementor-setting-key="table_duration" data-elementor-inline-editing-toolbar="none">
                                {{{ settings.table_duration }}}
                            </span>
                        <# } #>
                    </div>
                </div>
            <# } #>
            <ul class="pp-pricing-table-features">
                <# _.each( settings.table_features, function( item ) { #>
                    <li class="elementor-repeater-item-{{ item._id }} <# if ( item.table_exclude == 'yes' ) { #> excluded <# } #>">
                        <# if ( item.feature_icon ) { #>
                            <span class="pp-pricing-table-fature-icon {{{ item.feature_icon }}}"></span>
                        <# } #>
                        <# if ( item.feature_text ) { #>
                            <span class="pp-pricing-table-feature-text elementor-inline-editing" data-elementor-setting-key="table_features.{{ $i - 1 }}.feature_text" data-elementor-inline-editing-toolbar="none">
                                {{{ item.feature_text }}}
                            </span>
                        <# } #>
                    </li>
                <# } ); #>
            </ul>
            <div class="pp-pricing-table-footer">
                <# if ( settings.table_button_text ) { #>
                    <a href="{{ settings.link.url }}" class="{{ buttonClasses }} elementor-inline-editing" data-elementor-setting-key="table_button_text" data-elementor-inline-editing-toolbar="none">{{{ settings.table_button_text }}}</a>
                <# } #>
                <# if ( settings.table_additional_info ) { #>
                    <div class="pp-pricing-table-additional-info elementor-inline-editing" data-elementor-setting-key="table_additional_info" data-elementor-inline-editing-toolbar="none">
                        {{{ settings.table_additional_info }}}
                    </div>
                <# } #>
            </div>
        </div><!-- .pp-pricing-table -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new PP_Pricing_Table_Widget() );
