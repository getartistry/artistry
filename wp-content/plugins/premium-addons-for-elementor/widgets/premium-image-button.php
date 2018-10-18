<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Image_Button extends Widget_Base {
    public function get_name() {
        return 'premium-addon-image-button';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Image Button';
	}
    
    public function getTemplateInstance() {
		return $this->templateInstance = premium_Template_Tags::getInstance();
	}

    public function get_icon() {
        return 'pa-image-button';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium image button
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /*Start Button Content Section */
        $this->start_controls_section('premium_image_button_general_section',
                [
                    'label'         => esc_html__('Button', 'premium-addons-for-elementor'),
                    ]
                );
        
        /*Button Text*/ 
        $this->add_control('premium_image_button_text',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Click Me','premium-addons-for-elementor'),
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_link_selection', 
                [
                    'label'         => esc_html__('Link Type', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'url'   => esc_html__('URL', 'premium-addons-for-elementor'),
                        'link'  => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'url',
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_link',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::URL,
                    'default'       => [
                            'url'   => '#',
                        ],
                    'placeholder'   => 'https://premiumaddons.com/',
                    'label_block'   => true,
                    'separator'     => 'after',
                    'condition'     => [
                        'premium_image_button_link_selection' => 'url'
                    ]
                ]
                );
        
        $this->add_control('premium_image_button_existing_link',
                [
                    'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT2,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                        'premium_image_button_link_selection'     => 'link',
                    ],
                    'multiple'      => false,
                    'separator'     => 'after',
                    'label_block'   => true,
                ]
                );
        
        /*Button Hover Effect*/
        $this->add_control('premium_image_button_hover_effect', 
                [
                    'label'         => esc_html__('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'none',
                    'options'       => [
                        'none'          => esc_html__('None'),
                        'style1'        => esc_html__('Background Slide'),
                        'style3'        => esc_html__('Diagonal Slide'),
                        'style4'        => esc_html__('Icon Slide'),
                        'style5'        => esc_html__('Overlap'),
                        ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_image_button_style1_dir', 
                [
                    'label'         => esc_html__('Slide Direction', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'bottom',
                    'options'       => [
                        'bottom'       => esc_html__('Top to Bottom'),
                        'top'          => esc_html__('Bottom to Top'),
                        'left'         => esc_html__('Right to Left'),
                        'right'        => esc_html__('Left to Right'),
                        ],
                    'condition'     => [
                        'premium_image_button_hover_effect' => 'style1',
                        ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_image_button_style3_dir', 
                [
                    'label'         => esc_html__('Slide Direction', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'bottom',
                    'options'       => [
                        'top'          => esc_html__('Bottom Left to Top Right'),
                        'bottom'       => esc_html__('Top Right to Bottom Left'),
                        'left'         => esc_html__('Top Left to Bottom Right'),
                        'right'        => esc_html__('Bottom Right to Top Left'),
                        ],
                    'condition'     => [
                        'premium_image_button_hover_effect' => 'style3',
                        ],
                    'label_block'   => true,
                    ]
                );

        $this->add_control('premium_image_button_style4_dir', 
                [
                    'label'         => esc_html__('Slide Direction', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'bottom',
                    'options'       => [
                        'top'          => esc_html__('Bottom to Top'),
                        'bottom'       => esc_html__('Top to Bottom'),
                        'left'         => esc_html__('Left to Right'),
                        'right'        => esc_html__('Right to Left'),
                        ],
                    'condition'     => [
                        'premium_image_button_hover_effect' => 'style4',
                        ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_image_button_style5_dir', 
                [
                    'label'         => esc_html__('Overlap Direction', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'horizontal',
                    'options'       => [
                        'horizontal'          => esc_html__('Horizontal'),
                        'vertical'       => esc_html__('Vertical'),
                        ],
                    'condition'     => [
                        'premium_image_button_hover_effect' => 'style5',
                        ],
                    'label_block'   => true,
                    ]
                );
        
        /*Button Icon Switcher*/
        $this->add_control('premium_image_button_icon_switcher',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'condition'     => [
                        'premium_image_button_hover_effect!'  => 'style4'
                    ],
                    'description'   => esc_html__('Enable or disable button icon','premium-addons-for-elementor'),
                ]
                );

        /*Button Icon Selection*/ 
        $this->add_control('premium_image_button_icon_selection',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::ICON,
                    'default'       => 'fa fa-bars',
                    'condition'     => [
                        'premium_image_button_icon_switcher' => 'yes',
                        'premium_image_button_hover_effect!'  =>  'style4'
                    ],
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_style4_icon_selection',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::ICON,
                    'default'       => 'fa fa-bars',
                    'condition'     => [
                        'premium_image_button_hover_effect'  => 'style4'
                    ],
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_icon_position', 
                [
                    'label'         => esc_html__('Icon Position', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'before',
                    'options'       => [
                        'before'        => esc_html__('Before'),
                        'after'         => esc_html__('After'),
                        ],
                    'condition'     => [
                        'premium_image_button_icon_switcher' => 'yes',
                        'premium_image_button_hover_effect!'  =>  'style4'
                    ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_image_button_icon_before_size',
                [
                    'label'         => esc_html__('Icon Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_image_button_icon_switcher' => 'yes',
                        'premium_image_button_hover_effect!'  => 'style4'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-text-icon-wrapper i' => 'font-size: {{SIZE}}px',
                    ]
                ]
                );
        
        $this->add_control('premium_image_button_icon_style4_size',
                [
                    'label'         => esc_html__('Icon Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_image_button_hover_effect'  => 'style4'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-style4-icon-wrapper i' => 'font-size: {{SIZE}}px',
                    ]
                ]
                );
        
        $this->add_control('premium_image_button_icon_before_spacing',
                [
                    'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_image_button_icon_switcher' => 'yes',
                        'premium_image_button_icon_position' => 'before',
                        'premium_image_button_hover_effect!' => 'style4'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-text-icon-wrapper i' => 'margin-right: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
                );
        
        $this->add_control('premium_image_button_icon_after_spacing',
                [
                    'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_image_button_icon_switcher' => 'yes',
                        'premium_image_button_icon_position' => 'after',
                        'premium_image_button_hover_effect!' => 'style4'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-text-icon-wrapper i' => 'margin-left: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
                );
        
        /*Button Size*/
        $this->add_control('premium_image_button_size', 
                [
                    'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'lg',
                    'options'       => [
                            'sm'            => esc_html__('Small'),
                            'md'            => esc_html__('Medium'),
                            'lg'            => esc_html__('Large'),
                            'block'         => esc_html__('Block'),
                        ],
                    'label_block'   => true,
                    'separator'     => 'before',
                    ]
                );
        
        /*Button Align*/
        $this->add_responsive_control('premium_image_button_align',
			[
				'label'             => esc_html__( 'Alignment', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::CHOOSE,
				'options'           => [
					'left'    => [
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-container' => 'text-align: {{VALUE}}',
                ],
				'default' => 'center',
			]
		);
        
        $this->add_control('premium_image_button_event_switcher', 
                [
                    'label'         => esc_html__('onclick Event', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'separator'     => 'before',
                    ]
                );
        
        $this->add_control('premium_image_button_event_function', 
                [
                    'label'         => esc_html__('Example: myFunction();', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXTAREA,
                    'condition'     => [
                        'premium_image_button_event_switcher' => 'yes',
                        ],
                    ]
                );
        
        /*End Image Button General Section*/
        $this->end_controls_section();

        /*Start Styling Section*/
        $this->start_controls_section('premium_image_button_style_section',
            [
                'label'             => esc_html__('Button', 'premium-addons-for-elementor'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'premium_image_button_typo',
                'scheme'            => Scheme_Typography::TYPOGRAPHY_1,
                'selector'          => '{{WRAPPER}} .premium-image-button',
            ]
            );
        
        $this->start_controls_tabs('premium_image_button_style_tabs');
        
        $this->start_controls_tab('premium_image_button_style_normal',
            [
                'label'             => esc_html__('Normal', 'premium-addons-for-elementor'),
            ]
            );

        $this->add_control('premium_image_button_text_color_normal',
            [
                'label'             => esc_html__('Text Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button .premium-image-button-text-icon-wrapper'   => 'color: {{VALUE}};',
                ]
            ]);
        
        $this->add_control('premium_image_button_icon_color_normal',
            [
                'label'             => esc_html__('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper i'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_icon_switcher'  => 'yes',
                    'premium_image_button_hover_effect!'   => 'style4'
                ]
            ]);
        
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_image_button_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-image-button',
                    ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_image_button_border_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_image_button_border_radius_normal',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Icon Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__('Icon Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_icon_shadow_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button-text-icon-wrapper i',
                    'condition'         => [
                        'premium_image_button_icon_switcher'  => 'yes',
                        'premium_image_button_hover_effect!'  => 'style4'
                    
                        ]
                    ]
                );
        
        /*Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__('Text Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_text_shadow_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button-text-icon-wrapper span',
                    ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Button Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_box_shadow_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_image_button_margin_normal',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_image_button_padding_normal',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button, {{WRAPPER}} .premium-image-button-effect-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_image_button_style_hover',
            [
                'label'             => esc_html__('Hover', 'premium-addons-for-elementor'),
            ]
            );

        $this->add_control('premium_image_button_text_color_hover',
            [
                'label'             => esc_html__('Text Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect!'   => 'style4'
                ]
            ]);
        
        $this->add_control('premium_image_button_icon_color_hover',
            [
                'label'             => esc_html__('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper i'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_icon_switcher'  => 'yes',
                    'premium_image_button_hover_effect!'  => 'style4'
                ]
            ]);

            $this->add_control('premium_image_button_style4_icon_color',
            [
                'label'             => esc_html__('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-style4-icon-wrapper'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style4'
                ]
            ]);

            $this->add_control('premium_image_button_diagonal_overlay_color',
            [
                'label'             => esc_html__('Overlay Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-diagonal-effect-top:before, {{WRAPPER}} .premium-image-button-diagonal-effect-bottom:before, {{WRAPPER}} .premium-image-button-diagonal-effect-left:before, {{WRAPPER}} .premium-image-button-diagonal-effect-right:before'   => 'background-color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style3'
                ]
            ]);


            $this->add_control('premium_image_button_overlap_overlay_color',
            [
                'label'             => esc_html__('Overlay Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-overlap-effect-horizontal:before, {{WRAPPER}} .premium-image-button-overlap-effect-vertical:before'   => 'background-color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style5'
                ]
            ]);
            
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_image_button_background_hover',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-image-button-none:hover, {{WRAPPER}} .premium-image-button-style4-icon-wrapper,{{WRAPPER}} .premium-image-button-style1-top:before,{{WRAPPER}} .premium-image-button-style1-bottom:before,{{WRAPPER}} .premium-image-button-style1-left:before,{{WRAPPER}} .premium-image-button-style1-right:before,{{WRAPPER}} .premium-image-button-diagonal-effect-right:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-top:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-left:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-bottom:hover,{{WRAPPER}} .premium-image-button-overlap-effect-horizontal:hover, {{WRAPPER}} .premium-image-button-overlap-effect-vertical:hover',
                    ]
                );
        
        /*Overlay Color*/
        $this->add_control('premium_image_button_overlay_color',
                [
                    'label'         => esc_html__('Overlay Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'condition'     => [
                        'premium_image_button_overlay_switcher' => 'yes'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-squares-effect:before, {{WRAPPER}} .premium-image-button-squares-effect:after,{{WRAPPER}} .premium-image-button-squares-square-container:before, {{WRAPPER}} .premium-image-button-squares-square-container:after' => 'background-color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_image_button_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_image_button_border_radius_hover',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Icon Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__('Icon Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_icon_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper i',
                    'condition'         => [
                        'premium_image_button_icon_switcher'  => 'yes',
                        'premium_image_button_hover_effect!'     => 'style4'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__('Icon Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_style4_icon_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover .premium-image-button-style4-icon-wrapper i',
                    'condition'         => [
                        'premium_image_button_hover_effect'     => 'style4'
                        ]
                    ]
                );
        
        /*Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__('Text Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_text_shadow_hover',
                    'selector'      => '{{WRAPPER}}  .premium-image-button:hover .premium-image-button-text-icon-wrapper span',
                    'condition'         => [
                       'premium_image_button_hover_effect!'   => 'style4'
                        ]
                    ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Button Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_box_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover',
                ]
                );
        
        
        /*Button Margin*/
        $this->add_responsive_control('premium_image_button_margin_hover',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_image_button_padding_hover',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        /*End Labels Settings Section*/
        $this->end_controls_section();
    }

    protected function render() {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes( 'premium_image_button_text' );
        
        if($settings['premium_image_button_link_selection'] == 'url'){
            $image_link = $settings['premium_image_button_link']['url'];
        } else {
            $image_link = get_permalink($settings['premium_image_button_existing_link']);
        }
        
        $button_text = $settings['premium_image_button_text'];
        
        $button_size = 'premium-image-button-' . $settings['premium_image_button_size'];
        
        $button_icon = $settings['premium_image_button_icon_selection'];
        
        $image_event = $settings['premium_image_button_event_function'];
        
        if ($settings['premium_image_button_hover_effect'] == 'none'){
            $style_dir = 'premium-image-button-none';
        }    elseif($settings['premium_image_button_hover_effect'] == 'style1'){
            $style_dir = 'premium-image-button-style1-' . $settings['premium_image_button_style1_dir'];
        }   elseif($settings['premium_image_button_hover_effect'] == 'style3'){
            $style_dir = 'premium-image-button-diagonal-effect-' . $settings['premium_image_button_style3_dir'];
        }   elseif($settings['premium_image_button_hover_effect'] == 'style4'){
            $style_dir = 'premium-image-button-style4-' . $settings['premium_image_button_style4_dir'];
        }   elseif($settings['premium_image_button_hover_effect'] == 'style5'){
            $style_dir = 'premium-image-button-overlap-effect-' . $settings['premium_image_button_style5_dir'];
        }

    ?>
    <div class="premium-image-button-container">
        <a class="premium-image-button <?php echo esc_attr( $button_size ); ?> <?php echo esc_attr( $style_dir ); ?>" 
            <?php if( ! empty( $image_link ) ) : ?> href="<?php echo esc_url( $image_link ); ?>" <?php endif; ?>
            <?php if( ! empty( $settings['premium_image_button_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?>
            <?php if( ! empty( $settings['premium_image_button_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>
            <?php if( ! empty( $settings['premium_image_button_event_function'] ) && $settings['premium_image_button_event_switcher'] ) : ?> onclick="<?php echo $image_event; ?>"<?php endif ?>>
        <div class="premium-image-button-text-icon-wrapper">
            <?php if( $settings['premium_image_button_icon_switcher'] &&
                $settings['premium_image_button_hover_effect'] != 'style4' &&
                $settings['premium_image_button_icon_position'] == 'before' &&
                ! empty( $settings['premium_image_button_icon_selection'] ) ) :
            ?>
                <i class="fa <?php echo esc_attr( $button_icon ); ?>"></i>
            <?php endif; ?>
                <span <?php echo $this->get_render_attribute_string( 'premium_image_button_text' ); ?>>
                    <?php echo $button_text; ?>
                </span>
            <?php if( $settings['premium_image_button_icon_switcher'] &&
                $settings['premium_image_button_hover_effect'] != 'style4' && 
                $settings['premium_image_button_icon_position'] == 'after' &&
                ! empty( $settings['premium_image_button_icon_selection'] ) ) :
            ?>
                <i class="fa <?php echo esc_attr( $button_icon ); ?>"></i>
            <?php endif; ?>
        </div>
        <?php if( $settings['premium_image_button_hover_effect'] == 'style4') : ?>
            <div class="premium-image-button-style4-icon-wrapper <?php echo esc_attr( $settings['premium_image_button_style4_dir'] ); ?>">
                <i class="fa <?php echo esc_attr($settings['premium_image_button_style4_icon_selection']); ?>"></i>
            </div>
        <?php endif; ?>
        </a>
    </div>
    
    <?php
    }
    
    protected function _content_template() {
        ?>
        <#
        
        view.addInlineEditingAttributes( 'premium_image_button_text' );
        
        var buttonText = settings.premium_image_button_text,
            buttonUrl,
            styleDir,
            slideIcon,
            buttonSize = 'premium-image-button-' + settings.premium_image_button_size,
            buttonEvent = settings.premium_image_button_event_function,
            buttonIcon = settings.premium_image_button_icon_selection;
        
        if( 'url' == settings.premium_image_button_link_selection ) {
            buttonUrl = settings.premium_image_button_link.url;
        } else {
            buttonUrl = settings.premium_image_button_existing_link;
        }
        
        if ( 'none' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-button-none';
        } else if( 'style1' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-style1-' + settings.premium_image_button_style1_dir;
        } else if ( 'style3' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-diagonal-effect-' + settings.premium_image_button_style3_dir;
        } else if ( 'style4' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-style4-' + settings.premium_image_button_style4_dir;
            slideIcon = settings.premium_image_button_style4_icon_selection;
        } else if ( 'style5' == settings.premium_image_button_hover_effect ){
            styleDir = 'premium-image-button-overlap-effect-' + settings.premium_image_button_style5_dir;
        }
        
        #>
        
        <div class="premium-image-button-container">
            <a class="premium-image-button  {{ buttonSize }} {{ styleDir }}" href="{{ buttonUrl }}" onclick="{{ buttonEvent }}">
                <div class="premium-image-button-text-icon-wrapper">
                    <# if( settings.premium_image_button_icon_switcher && 'before' == settings.premium_image_button_icon_position &&  'style4' != settings.premium_image_button_hover_effect && '' != settings.premium_image_button_icon_selection ) { #>
                        <i class="fa {{ buttonIcon }}"></i>
                    <# } #>
                    <span {{{ view.getRenderAttributeString('premium_image_button_text') }}}>{{{ buttonText }}}</span>
                    <# if( settings.premium_image_button_icon_switcher && 'after' == settings.premium_image_button_icon_position &&  'style4' != settings.premium_image_button_hover_effect && '' != settings.premium_image_button_icon_selection ) { #>
                        <i class="fa {{ buttonIcon }}"></i>
                    <# } #>
                </div>
                <# if( 'style4' == settings.premium_image_button_hover_effect ) { #>
                    <div class="premium-image-button-style4-icon-wrapper {{ settings.premium_image_button_style4_dir }}">
                        <i class="fa {{ slideIcon }}"></i>
                    </div>
                <# } #>
            </a>
        </div>
        
        <?php
    }
}