<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Pricing_Table_Widget extends Widget_Base
{
    protected $templateInstance;

    public function getTemplateInstance() {
        return $this->templateInstance = premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-pricing-table';
    }

    public function get_title() {
        return \PremiumAddons\Helper_Functions::get_prefix() . ' Pricing Table';
    }

    public function get_icon() {
        return 'pa-pricing-table';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }


    // Adding the controls fields for the premium pricing table
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {
        /*Title Content Section*/
        $this->start_controls_section('premium_pricing_table_icon_section',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_icon_switcher'  => 'yes',
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_table_icon_selection', 
                [
                    'label'         => esc_html__('Select an Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::ICON,
                    'default'       => 'fa fa-check'
                ]
                );
        
        $this->end_controls_section();
        
        /*Title Content Section*/
        $this->start_controls_section('premium_pricing_table_title_section',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_title_switcher'  => 'yes',
                        ]
                    ]
                );
        
        /*Header Text*/ 
        $this->add_control('premium_pricing_table_title_text',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'default'       => 'Pricing Table',
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                ]
                );
        
        /*Header Tag*/
        $this->add_control('premium_pricing_table_title_size',
                [
                    'label'         => esc_html__('HTML Tag', 'premium-addons-for-elementor'),
                    'description'   => esc_html__( 'Select HTML tag for the title', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'h3',
                    'options'       => [
                        'h1'    => 'H1',
                        'h2'    => 'H2',
                        'h3'    => 'H3',
                        'h4'    => 'H4',
                        'h5'    => 'H5',
                        'h6'    => 'H6',
                        ],
                    'label_block'   => true,
                    ]
                );
        
        $this->end_controls_section();
        
        
        /*Price Content Section*/
        $this->start_controls_section('premium_pricing_table_price_section',
                [
                    'label'         => esc_html__('Price', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_price_switcher'  => 'yes',
                        ]
                    ]
                );

        /*Price Value*/ 
        $this->add_control('premium_pricing_table_slashed_price_value',
                [
                    'label'         => esc_html__('Slashed Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
            );
        
        /*Price Currency*/ 
        $this->add_control('premium_pricing_table_price_currency',
                [
                    'label'         => esc_html__('Currency', 'premium-addons-for-elementor'),
                    'default'       => '$',
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
                );
        
        /*Price Value*/ 
        $this->add_control('premium_pricing_table_price_value',
                [
                    'label'         => esc_html__('Price', 'premium-addons-for-elementor'),
                    'default'       => '25',
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
                );
        
        /*Price Separator*/ 
        $this->add_control('premium_pricing_table_price_separator',
                [
                    'label'         => esc_html__('Divider', 'premium-addons-for-elementor'),
                    'default'       => '/',
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
                );
       
        /*Price Duration*/ 
        $this->add_control('premium_pricing_table_price_duration',
                [
                    'label'         => esc_html__('Duration', 'premium-addons-for-elementor'),
                    'default'       => 'm',
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
                );
        
        $this->end_controls_section();
        
        /*Icon List Content Section*/
        $this->start_controls_section('premium_pricing_table_list_section',
                [
                    'label'         => esc_html__('Icon List', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_list_switcher'  => 'yes',
                        ]
                    ]
                );
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_pricing_list_item_text',
            [
                'label'       => esc_html__( 'Text', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
            ]
        );
        
        $repeater->add_control('premium_pricing_list_item_icon',
            [
                'label'       => esc_html__( 'Icon', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::ICON,
            ]
        );
        
         $this->add_control('premium_fancy_text_list_items',
                [
                    'label'         => esc_html__( 'Features', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::REPEATER,
                    'default'       => [
                        [
                            'premium_pricing_list_item_icon'    => 'fa fa-check',
                            'premium_pricing_list_item_text' => esc_html__( 'List Item #1', 'premium-addons-for-elementor' ),
                            ],
                        [
                            'premium_pricing_list_item_icon'    => 'fa fa-check',
                            'premium_pricing_list_item_text' => esc_html__( 'List Item #2', 'premium-addons-for-elementor' ),
                            ],
                        [
                            'premium_pricing_list_item_icon'    => 'fa fa-check',
                            'premium_pricing_list_item_text' => esc_html__( 'List Item #3', 'premium-addons-for-elementor' ),
                            ],
                        ],
                    'fields'        => array_values( $repeater->get_controls() ),
                    ]
                );

         $this->add_responsive_control('premium_pricing_table_list_align',
            [
                'label'             => __( 'Alignment', 'premium-addons-for-elementor' ),
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
                    '{{WRAPPER}} .premium-pricing-list' => 'text-align: {{VALUE}}',
                ],
                'default' => 'center',
            ]
        );
        
        $this->end_controls_section();
        
        /*Description Content Section*/
        $this->start_controls_section('premium_pricing_table_description_section',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_description_switcher'  => 'yes',
                        ]
                    ]
                );
        
        
        /*Description Text*/
        $this->add_control('premium_pricing_table_description_text',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::WYSIWYG,
                    'default'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->end_controls_section();
        
        /*Button Content Section*/
        $this->start_controls_section('premium_pricing_table_button_section',
                [
                    'label'         => esc_html__('Button', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_button_switcher'  => 'yes',
                        ]
                    ]
                );
        
        
        /*Button Text*/ 
        $this->add_control('premium_pricing_table_button_text',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'default'       => esc_html__('Get Started' , 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                ]
                );
        
        /*Button Url Type*/ 
        $this->add_control('premium_pricing_table_button_url_type', 
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
        
        
        /*Button url*/ 
        $this->add_control('premium_pricing_table_button_link',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'condition'     => [
                        'premium_pricing_table_button_url_type'     => 'url',
                    ],
                    'label_block'   => true,
                ]
                );
        
        /*Button Link to existing content*/
        $this->add_control('premium_pricing_table_button_link_existing_content',
                [
                    'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT2,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                        'premium_pricing_table_button_url_type'     => 'link',
                    ],
                    'multiple'      => false,
                    'label_block'   => true,
                ]
                );
        
        
        /*Link Target*/ 
        $this->add_control('premium_pricing_table_button_link_target',
                [
                    'label'         => esc_html__('Link Target', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__( ' Where would you like the link be opened?', 'premium-addons-for-elementor' ),
                    'options'       => [
                        'blank'  => esc_html('Blank'),
                        'parent' => esc_html('Parent'),
                        'self'   => esc_html('Self'),
                        'top'    => esc_html('Top'),
                        ],
                    'default'       => esc_html__('blank','premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /*End Button Settings Section*/
        $this->end_controls_section();
        
        /*Button Content Section*/
        $this->start_controls_section('premium_pricing_table_bagde_section',
                [
                    'label'         => esc_html__('Badge', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_badge_switcher'  => 'yes',
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_table_badge_text',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'default'       => esc_html__('Popular', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                ]
                );
        
        $this->add_responsive_control('premium_pricing_table_badge_left_size', 
                    [
                        'label'     => esc_html__('Size', 'premium-addons-for-elementor'),
                        'type'      => Controls_Manager::SLIDER,
                        'range'     => [
                            'px'    => [
                                'min'   => 1,
                                'max'   => 300,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .premium-badge-left .corner' => 'border-top-width: {{SIZE}}px; border-bottom-width: {{SIZE}}px; border-right-width: {{SIZE}}px;'
                        ],
                        'condition' => [
                            'premium_pricing_table_badge_position'  => 'left'
                        ]
                    ]
                    );
                
                $this->add_control('premium_pricing_table_badge_right_size', 
                    [
                        'label'     => esc_html__('Size', 'premium-addons-for-elementor'),
                        'type'      => Controls_Manager::SLIDER,
                        'range'     => [
                            'px'    => [
                                'min'   => 1,
                                'max'   => 300,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .premium-badge-right .corner' => 'border-right-width: {{SIZE}}px; border-bottom-width: {{SIZE}}px; border-left-width: {{SIZE}}px;'
                        ],
                        'condition' => [
                            'premium_pricing_table_badge_position'  => 'right'
                        ]
                    ]
                    );
        
        $this->add_control('premium_pricing_table_badge_position',
                [
                    'label'         => esc_html__('Position', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'right' => esc_html__('Right', 'premium-addons-for-elementor'),
                        'left' => esc_html__('Left', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'right',
                ]
                );
        
        $this->end_controls_section();
        
        /* Start Title Settings Section */
        $this->start_controls_section('premium_pricing_table_title',
                [
                    'label'         => esc_html__('Display Options', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_pricing_table_icon_switcher',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_pricing_table_title_switcher',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->add_control('premium_pricing_table_price_switcher',
                [
                    'label'         => esc_html__('Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->add_control('premium_pricing_table_list_switcher',
                [
                    'label'         => esc_html__('Features', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->add_control('premium_pricing_table_description_switcher',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_pricing_table_button_switcher',
                [
                    'label'         => esc_html__('Button', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->add_control('premium_pricing_table_badge_switcher',
                [
                    'label'         => esc_html__('Badge', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->end_controls_section();
        
        /*Start Styling Section*/
        /*Start Icon Style Settings */
        $this->start_controls_section('premium_pricing_icon_style_settings',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_icon_switcher'  => 'yes',
                        ]
                ]
                );
        
        /*Icon Color*/
        $this->add_control('premium_pricing_icon_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container i'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_icon_size',
                [
                    'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size'  => 25,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container' => 'font-size: {{SIZE}}px',
                    ]
                ]
                );
        
        $this->add_control('premium_pricing_icon_back_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container i'  => 'background-color: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_pricing_icon_inner_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px','em'],
                    'default'       => [
                        'size'  => 10,
                        'unit'  => 'px'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container i' => 'padding: {{SIZE}}{{UNIT}};',
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_icon_inner_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-icon-container i',
                ]
                );
        
        $this->add_control('premium_pricing_icon_inner_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' , 'em'],
                    'default'       => [
                        'size'  => 100,
                        'unit'  => 'px'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container i' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                    'separator'     => 'after'
                ]
                );
        
        $this->add_control('premium_pricing_icon_container_heading',
                [
                    'label'         => esc_html__('Container', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Icon Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_icon_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-icon-container',
                    ]
                );
        
        /*Icon Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_icon_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-icon-container',
                ]
                );
        
        /*Icon Border Radius*/
        $this->add_control('premium_pricing_icon_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Icon Margin*/
        $this->add_responsive_control('premium_pricing_icon_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 50,
                        'right' => 0,
                        'bottom'=> 20,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        /*Icon Padding*/
        $this->add_responsive_control('premium_pricing_icon_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 0,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
          
        /*End Icon Style Settings */
        $this->end_controls_section();
        
        /*Start Title Style Settings */
        $this->start_controls_section('premium_pricing_title_style_settings',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_title_switcher'  => 'yes',
                        ]
                ]
                );
        
        /*Title Color*/
        $this->add_control('premium_pricing_title_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-title'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        /*Title Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'title_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-table-title',
                ]
                );
        
        /*Title Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_title_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-table-title',
                    ]
                );
        
        /*Title Margin*/
        $this->add_responsive_control('premium_pricing_title_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 0,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        /*Title Padding*/
        $this->add_responsive_control('premium_pricing_title_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 20,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
          
        /*End Title Style Settings */
        $this->end_controls_section();
        
        /*Start Price Style Settings */
        $this->start_controls_section('premium_pricing_price_style_settings',
                [
                    'label'         => esc_html__('Price', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_price_switcher'  => 'yes',
                        ]
                ]
                );

        $this->add_control('premium_pricing_slashed_price_heading',
                [
                    'label'         => esc_html__('Slashed Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Slashed Price Color*/
        $this->add_control('premium_pricing_slashed_price_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-slashed-price-value'  => 'color: {{VALUE}};'
                        ],
                    ]
                );
        
        /*Slashed Price Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'slashed_price_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-slashed-price-value',
                    ]
                );
        
        /*Slashed Price Margin*/
        $this->add_responsive_control('premium_pricing_slashed_price_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing--slashed-price-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_currency_heading',
                [
                    'label'         => esc_html__('Currency', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
       
        /*Currency Color*/
        $this->add_control('premium_pricing_currency_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-currency'  => 'color: {{VALUE}};'
                        ],
                    ]
                );
        
        /*Currency Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [   
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'currency_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-currency',
                    ]
                );
        
        $this->add_responsive_control('premium_pricing_currency_align',
                [
                    'label'         => esc_html__( 'Vertical Align', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'top'      => [
                            'title'=> esc_html__( 'Top', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-long-arrow-up',
                            ],
                        'unset'    => [
                            'title'=> esc_html__( 'Unset', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-justify',
                            ],
                        'bottom'     => [
                            'title'=> esc_html__( 'Bottom', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-long-arrow-down',
                            ],
                        ],
                    'default'       => 'unset',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-currency' => 'vertical-align: {{VALUE}};',
                        ],
                    'label_block'   => false
                    ]
                );
        
        $this->add_responsive_control('premium_pricing_currency_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'separator'     => 'after'
                ]
            ]      
        );
        
        
        $this->add_control('premium_pricing_price_heading',
                [
                    'label'         => esc_html__('Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Price Color*/
        $this->add_control('premium_pricing_price_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-value'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Price Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'price_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-value',
                    ]
                );
        
        $this->add_responsive_control('premium_pricing_price_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_sep_heading',
                [
                    'label'         => esc_html__('Divider', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Separator Color*/
        $this->add_control('premium_pricing_sep_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-separator'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Separator Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'separator_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-separator',
                ]
            );
        
        $this->add_responsive_control('premium_pricing_sep_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 20,
                        'left'  => -15,
                        'unit'  => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_dur_heading',
                [
                    'label'         => esc_html__('Duration', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Duration Color*/
        $this->add_control('premium_pricing_dur_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-duration'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Duration Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'duration_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-duration',
                ]
            );
        
        $this->add_responsive_control('premium_pricing_dur_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-duration' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'separator'     => 'after'
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_price_container_heading',
                [
                    'label'         => esc_html__('Container', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Price Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_price_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-price-container',
                    ]
                );
        
        /*Price Margin*/
        $this->add_responsive_control('premium_pricing_price_container_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 16,
                        'right'     => 0,
                        'bottom'    => 16,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        /*Price Padding*/
        $this->add_responsive_control('premium_pricing_price_padding',
                [
                    'label'             => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        /*End Price Style Settings */
        $this->end_controls_section();
        
        /*Start List Style Settings*/
        $this->start_controls_section('premium_pricing_list_style_settings',
                [
                    'label'         => esc_html__('Features', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_list_switcher'  => 'yes',
                        ]
                ]
                );
        
        $this->add_control('premium_pricing_features_text_heading',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        $this->add_control('premium_pricing_list_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list .premium-pricing-list-span'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'list_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-list .premium-pricing-list-span',
                ]
                );
        
        $this->add_control('premium_pricing_features_icon_heading',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Button Color*/
        $this->add_control('premium_pricing_list_icon_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list i'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_list_icon_size',
                [
                    'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list i' => 'font-size: {{SIZE}}px',
                    ]
                ]
                );
        
        $this->add_control('premium_pricing_list_icon_spacing',
                [
                    'label'         => esc_html__('Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size'  => 5
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list i' => 'margin-right: {{SIZE}}px',
                    ],
                ]
                );
        
        $this->add_control('premium_pricing_list_item_margin',
                [
                    'label'         => esc_html__('Vertical Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list li' => 'margin-bottom: {{SIZE}}px;'
                    ],
                    'separator'     => 'after'
                ]);
        
        $this->add_control('premium_pricing_features_container_heading',
                [
                    'label'         => esc_html__('Container', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_list_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-list-container',
                    ]
                );
        
        /*List Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_list_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-list-container',
                ]
                );
        
        /*List Border Radius*/
        $this->add_control('premium_pricing_list_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*List Margin*/
        $this->add_responsive_control('premium_pricing_list_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 30,
                        'right'     => 0,
                        'bottom'    => 30,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*List Padding*/
        $this->add_responsive_control('premium_pricing_list_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_section();
        
        /*Start Description Style Settings */
        $this->start_controls_section('premium_pricing_description_style_settings',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_description_switcher'  => 'yes',
                        ]
                ]
                );
        
        $this->add_control('premium_pricing_desc_text_heading',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Description Color*/
        $this->add_control('premium_pricing_desc_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-description-container'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        /*Description Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'description_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-description-container',
                ]
            );
        
        $this->add_control('premium_pricing_desc_container_heading',
                [
                    'label'         => esc_html__('Container', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Description Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_desc_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-description-container',
                    ]
                );
        
        /*Description Margin*/
        $this->add_responsive_control('premium_pricing_desc_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 16,
                        'right'     => 0,
                        'bottom'    => 16,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-description-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        /*Description Padding*/
        $this->add_responsive_control('premium_pricing_desc_padding',
                [
                    'label'             => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-description-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        /*End Description Style Settings */
        $this->end_controls_section();
        
        /*Start Button Style Settings */
        $this->start_controls_section('premium_pricing_button_style_settings',
                [
                    'label'         => esc_html__('Button', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_button_switcher'  => 'yes',
                        ]
                ]
                );
        
        /*Button Color*/
        $this->add_control('premium_pricing_button_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_button_hover_color',
                [
                    'label'         => esc_html__('Hover Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button:hover'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        /*Button Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'button_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
                ]
                );
        
        $this->start_controls_tabs('premium_pricing_table_button_style_tabs');
        
        $this->start_controls_tab('premium_pricing_table_button_style_normal',
                [
                    'label'         => esc_html__('Normal', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Button Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_button_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-price-button',
                    ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_table_button_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_pricing_table_box_button_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_pricing_table_button_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_pricing_button_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_pricing_button_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 20,
                        'right'     => 0,
                        'bottom'    => 20,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_pricing_table_button_style_hover',
        [
            'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
        ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_button_background_hover',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-price-button:hover',
                    ]
                );
        
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_table_button_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-button:hover',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_pricing_table_button_border_radius_hover',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%' ],                    
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_pricing_table_button_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-button:hover',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_pricing_button_margin_hover',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_pricing_button_padding_hover',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'default'           => [
                        'top'       => 20,
                        'right'     => 0,
                        'bottom'    => 20,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        /*End Button Style Section*/
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_badge_style',
                [
                    'label'         => esc_html__('Badge', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_pricing_table_badge_switcher'  => 'yes'
                    ]
                ]
            );
        
        $this->add_control('premium_pricing_badge_text_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-badge-container .corner span'  => 'color: {{VALUE}};'
                        ]
                    ]
                );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'badge_text_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-badge-container .corner span',
                ]
                );
        
        $this->add_responsive_control('premium_pricing_table_badge_right_top', 
                    [
                        'label'     => esc_html__('Vertical Distance', 'premium-addons-for-elementor'),
                        'type'      => Controls_Manager::SLIDER,
                        'range'     => [
                            'px'=> [
                                'min'   => 1,
                                'max'   => 200,
                            ]
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .premium-pricing-badge-container .corner span' => 'top: {{SIZE}}px;'
                        ],
                    ]
                    );
        
        $this->add_responsive_control('premium_pricing_table_badge_right_right', 
                    [
                        'label'     => esc_html__('Horizontal Distance', 'premium-addons-for-elementor'),
                        'type'      => Controls_Manager::SLIDER,
                        'range'     => [
                            'px'=> [
                                'min'   => 1,
                                'max'   => 170,
                            ]
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .premium-badge-right .corner span' => 'right: {{SIZE}}px;'
                        ],
                        'condition' => [
                            'premium_pricing_table_badge_position'  => 'right'
                        ]
                    ]
                    );
        
        $this->add_responsive_control('premium_pricing_table_badge_right_left', 
                    [
                        'label'     => esc_html__('Horizontal Distance', 'premium-addons-for-elementor'),
                        'type'      => Controls_Manager::SLIDER,
                        'selectors' => [
                            '{{WRAPPER}} .premium-badge-left .corner span' => 'left: {{SIZE}}px;'
                        ],
                        'condition' => [
                            'premium_pricing_table_badge_position'  => 'left'
                        ]
                    ]
                    );
        
        /*Badge Color*/
        $this->add_control('premium_pricing_badge_left_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-badge-left .corner'  => 'border-top-color: {{VALUE}};'
                        ],
                    'condition'     => [
                        'premium_pricing_table_badge_position'    => 'left'
                    ]
                    ]
                );
        
        $this->add_control('premium_pricing_badge_right_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-badge-right .corner'  => 'border-right-color: {{VALUE}};'
                        ],
                    'condition'     => [
                        'premium_pricing_table_badge_position'    => 'right'
                    ]
                    ]
                );
        
        $this->end_controls_section();
        
        /*Start Box Style Settings*/
        $this->start_controls_section('premium_pricing_box_style_settings',
                [
                    'label'         => esc_html__('Box Settings', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        $this->start_controls_tabs('premium_pricing_table_box_style_tabs');
        
        $this->start_controls_tab('premium_pricing_table_box_style_normal',
                [
                    'label'         => esc_html__('Normal', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Box Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_box_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-table-container',
                    ]
                );
        
        /*Box Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_table_box_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-table-container',
                ]
                );
        
        /*Box Border Radius*/
        $this->add_control('premium_pricing_table_box_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Box Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_pricing_table_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-pricing-table-container',
                ]
                );
        
        /*Box Margin*/
        $this->add_responsive_control('premium_pricing_box_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Box Padding*/
        $this->add_responsive_control('premium_pricing_box_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 40,
                        'right' => 0,
                        'bottom'=> 0,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_pricing_table_box_style_hover',
        [
            'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_box_background_hover',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-table-container:hover',
                    ]
                );
        
        
        /*Box Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_table_box_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-pricing-table-container:hover',
                ]
                );
        
        /*Box Border Radius*/
        $this->add_control('premium_pricing_table_box_border_radius_hover',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Box Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_pricing_table_box_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-pricing-table-container:hover',
                ]
                );
        
        /*Box Margin*/
        $this->add_responsive_control('premium_pricing_box_margin_hover',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Box Padding*/
        $this->add_responsive_control('premium_pricing_box_padding_hover',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 40,
                        'right' => 0,
                        'bottom'=> 0,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-table-container:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        /*End Box Style Settings*/
        $this->end_controls_section();
        
        
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('premium_pricing_table_title_text');
        $this->add_inline_editing_attributes('premium_pricing_table_description_text', 'advanced');
        $this->add_inline_editing_attributes('premium_pricing_table_button_text');
        $title_tag = $settings['premium_pricing_table_title_size'];
        $link_type = $settings['premium_pricing_table_button_url_type'];
        $badge_position = 'premium-badge-' .  $settings['premium_pricing_table_badge_position'];
        if($link_type == 'link'){
            $link_url = get_permalink($settings['premium_pricing_table_button_link_existing_content']);
        } elseif ($link_type == 'url') {
            $link_url = $settings['premium_pricing_table_button_link'];
        }
?>
    
<div class="premium-pricing-table-container">
    <?php if($settings['premium_pricing_table_badge_switcher']) : ?>
    <div class="premium-pricing-badge-container <?php echo esc_attr($badge_position); ?>">
          <div class="corner"><span><?php echo $settings['premium_pricing_table_badge_text']; ?></span></div>
        </div>
    <?php endif; ?>
    <?php if($settings['premium_pricing_table_icon_switcher'] == 'yes') : ?>
    <div class="premium-pricing-icon-container"><i class="<?php echo esc_attr( $settings['premium_pricing_table_icon_selection'] ); ?>"></i></div>
        <?php endif; ?>
    <?php if($settings['premium_pricing_table_title_switcher'] == 'yes') : ?>
    <<?php echo $title_tag;?> class="premium-pricing-table-title"><span <?php echo $this->get_render_attribute_string('premium_pricing_table_title_text'); ?>><?php echo $settings['premium_pricing_table_title_text'];?></span></<?php echo $title_tag;?>><?php endif; ?>
    <?php if($settings['premium_pricing_table_price_switcher'] == 'yes') : ?>
    <div class="premium-pricing-price-container">
        <strike class="premium-pricing-slashed-price-value">
            <?php echo $settings['premium_pricing_table_slashed_price_value']; ?>
        </strike>
        <span class="premium-pricing-price-currency">
            <?php echo $settings['premium_pricing_table_price_currency']; ?>
        </span>
        <span class="premium-pricing-price-value">
            <?php echo $settings['premium_pricing_table_price_value']; ?>
        </span>    
        <span class="premium-pricing-price-separator">
            <?php echo $settings['premium_pricing_table_price_separator']; ?>    
        </span>
        <span class="premium-pricing-price-duration">
            <?php echo $settings['premium_pricing_table_price_duration']; ?>
        </span>
    </div>
    <?php endif; ?>
    <?php if($settings['premium_pricing_table_list_switcher'] == 'yes') : ?>
    <div class="premium-pricing-list-container">
        <ul class="premium-pricing-list">
            <?php foreach($settings['premium_fancy_text_list_items'] as $item): echo '<li>' . '<i class="' . esc_attr($item['premium_pricing_list_item_icon']) . '">' . '</i>' . '<span class="premium-pricing-list-span">' . esc_attr($item['premium_pricing_list_item_text']) . '</span>' . '</li>';  ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php if($settings['premium_pricing_table_description_switcher'] == 'yes') : ?>
    <div class="premium-pricing-description-container">
        <div <?php echo $this->get_render_attribute_string('premium_pricing_table_description_text'); ?>>
        <?php echo $settings['premium_pricing_table_description_text']; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if($settings['premium_pricing_table_button_switcher'] == 'yes') : ?>
    <div class="premium-pricing-button-container">
        <a class="premium-pricing-price-button" target="_<?php echo esc_attr($settings['premium_pricing_table_button_link_target']); ?>" href="<?php echo esc_url($link_url); ?>">
            <span <?php echo $this->get_render_attribute_string('premium_pricing_table_button_text'); ?>><?php echo $settings['premium_pricing_table_button_text']; ?></span>
        </a>
    </div>
    <?php endif; ?>
</div>

    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Pricing_Table_Widget());