<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Modal_Box_Widget extends Widget_Base
{
    public function getTemplateInstance(){
        return $this->templateInstance = premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-addon-modal-box';
    }
    
    public function check_rtl(){
        return is_rtl();
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Modal Box';
	}

    public function get_icon() {
        return 'pa-modal-box';
    }

    public function get_script_depends()
    {
        return ['premium-addons-js','modal-js'];
    }
    
    public function get_categories() {
        return [ 'premium-elements' ];
    }


    // Adding the controls fields for the premium modal box
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {
        /* Start Box Content Section */
        
        $this->start_controls_section('premium_modal_box_selector_content_section', 
                [
                    'label'         => esc_html__('Content', 'premium-addons-for-elementor'),
                ]
                );
        
        $this->add_control('premium_modal_box_header_switcher',
                [
                    'label'         => esc_html__('Header', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => 'show',
                    'label_off'     => 'hide',
                    'default'       => 'yes',
                    'description'   => esc_html__('Enable or disable modal header','premium-addons-for-elementor'),
                ]
                );
        
        /*Icon To Display*/
        $this->add_control('premium_modal_box_icon_selection',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__('Use font awesome icon or upload a custom image', 'premium-addons-for-elementor'),
                    'options'       => [
                        'noicon'  => esc_html('None'),
                        'fonticon'=> esc_html('Font Awesome'),
                        'image'   => esc_html('Custom Image'),
                    ],
                    'default'       => 'noicon',
                    'condition'     => [
                        'premium_modal_box_header_switcher' => 'yes'
                    ],
                    'label_block'   => true
                ]
                );
        
        /*Font Awesome Icon*/
        $this->add_control('premium_modal_box_font_icon', 
                [
                    'label'         => esc_html__('Font Awesome', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::ICON,
                    'condition'     => [
                        'premium_modal_box_icon_selection'    => 'fonticon',
                        'premium_modal_box_header_switcher' => 'yes'
                    ],
                    'label_block'   => true,
                ]
                );
        
        /*Image Icon*/ 
        $this->add_control('premium_modal_box_image_icon',
                [
                    'label'         => esc_html__('Custom Image', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::MEDIA,
                    'default'       => [
                        'url'   => Utils::get_placeholder_image_src(),
                    ],
                    'condition'     => [
                        'premium_modal_box_icon_selection'    => 'image',
                        'premium_modal_box_header_switcher' => 'yes'
                    ],
                    'label_block'   => true,
                ]
                );

        /*Modal Box Title*/ 
        $this->add_control('premium_modal_box_title',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'description'   => esc_html__('Provide the modal box with a title', 'premium-addons-for-elementor'),
                    'default'       => 'Modal Box Title',
                    'condition'     => [
                        'premium_modal_box_header_switcher' => 'yes'
                    ],
                    'label_block'   => true,
                ]
                );
        
        /*Modal Box Content Heading*/
        $this->add_control('premium_modal_box_content_heading',
                [
                    'label'         => esc_html__('Content', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Modal Box Content Type*/
        $this->add_control('premium_modal_box_content_type',
                [
                    'label'         => esc_html__('Content to Show', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'editor'        => esc_html('Text Editor', 'premium-addons-for-elementor'),
                        'template'      => esc_html('Elementor Template', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'editor',
                    'label_block'   => true
                ]
                );
        
        /*Modal Box Elementor Template*/
        $this->add_control('premium_modal_box_content_temp',
                [
                    'label'			=> esc_html__( 'Content', 'premium-addons-for-elementor' ),
                    'description'	=> esc_html__( 'Modal content is a template which you can choose from Elementor library', 'premium-addons-for-elementor' ),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $this->getTemplateInstance()->get_elementor_page_list(),
                    'condition'     => [
                        'premium_modal_box_content_type'    => 'template',
                    ],
                ]
            );
        
        /*Modal Box Content*/
        $this->add_control('premium_modal_box_content',
                [
                    'type'          => Controls_Manager::WYSIWYG,
                    'default'       => 'Modal Box Content',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-body',
                    'dynamic'       => [ 'active' => true ],
                    'condition'     => [
                        'premium_modal_box_content_type'    => 'editor',
                    ],
                    'show_label'    => false,
                ]
                );

        /*Upper Close Button*/
        $this->add_control('premium_modal_box_upper_close',
                [
                    'label'         => esc_html__('Upper Close Button', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'premium_modal_box_header_switcher' => 'yes'
                    ]
                ]
                );
        
        /*Lower Close Button*/
        $this->add_control('premium_modal_box_lower_close',
                [
                    'label'         => esc_html__('Lower Close Button', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_modal_box_content_section',
                [
                    'label'         => esc_html__('Display Options', 'premium-addons-for-elementor'),
                    ]
                );
        
        /*Modal Box Display On*/
        $this->add_control('premium_modal_box_display_on',
                [
                    'label'         => esc_html__('Display Style', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__('Choose where would you like the modal box appear on', 'premium-addons-for-elementor'),
                    'options'       => [
                        'button'  => esc_html('Button','premium-elementor'),
                        'image'   => esc_html('Image','premium-elementor'),
                        'text'    => esc_html('Text','premium-elementor'),
                        'pageload'=> esc_html('Page Load','premium-elementor'),
                    ],
                    'label_block'   =>  true,
                    'default'       => 'button',  
                ]
                );
      
        /*Button Text*/ 
        $this->add_control('premium_modal_box_button_text',
                [
                    'label'         => esc_html__('Button Text', 'premium-addons-for-elementor'),
                    'default'       => esc_html__('Premium Modal Box','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                    'condition'     => [
                      'premium_modal_box_display_on'  => 'button'
                    ],
                ]
                );
        
        $this->add_control('premium_modal_box_icon_switcher',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'button'
                    ],
                    'description'   => esc_html__('Enable or disable button icon','premium-addons-for-elementor'),
                ]
                );

        $this->add_control('premium_modal_box_button_icon_selection',
                [
                    'label'         => esc_html__('Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::ICON,
                    'default'       => 'fa fa-bars',
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes'
                    ],
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_modal_box_icon_position', 
                [
                    'label'         => esc_html__('Icon Position', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'before',
                    'options'       => [
                        'before'        => esc_html__('Before'),
                        'after'         => esc_html__('After'),
                        ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes'
                    ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_modal_box_icon_before_size',
                [
                    'label'         => esc_html__('Icon Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector i '=> 'font-size: {{SIZE}}px',
                    ]
                ]
                );
        
        if(!$this->check_rtl()){
        $this->add_control('premium_modal_box_icon_before_spacing',
                [
                    'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'before'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector i' => 'margin-right: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
            );
        }
        
        if(!$this->check_rtl()){
        $this->add_control('premium_modal_box_icon_after_spacing',
                [
                    'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'after'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector i' => 'margin-left: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
            );
        }
        
        if($this->check_rtl()){
            $this->add_control('premium_modal_box_icon_rtl_before_spacing',
                    [
                        'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                        'type'          => Controls_Manager::SLIDER,
                        'condition'     => [
                            'premium_modal_box_display_on'      => 'button',
                            'premium_modal_box_icon_switcher'   => 'yes',
                            'premium_modal_box_icon_position'   => 'before'
                        ],
                        'default'       => [
                            'size'  => 15
                        ],
                        'selectors'     => [
                            '{{WRAPPER}} .premium-modal-box-button-selector i' => 'margin-left: {{SIZE}}px',
                        ],
                        'separator'     => 'after',
                    ]
                );
            }
        
        if($this->check_rtl()){
            $this->add_control('premium_modal_box_icon_rtl_after_spacing',
                    [
                        'label'         => esc_html__('Icon Spacing', 'premium-addons-for-elementor'),
                        'type'          => Controls_Manager::SLIDER,
                        'condition'     => [
                            'premium_modal_box_display_on'      => 'button',
                            'premium_modal_box_icon_switcher'   => 'yes',
                            'premium_modal_box_icon_position'   => 'after'
                        ],
                        'default'       => [
                            'size'  => 15
                        ],
                        'selectors'     => [
                            '{{WRAPPER}} .premium-modal-box-button-selector i' => 'margin-right: {{SIZE}}px',
                        ],
                        'separator'     => 'after',
                    ]
                );
            }
        
        /*Button Size*/
        $this->add_control('premium_modal_box_button_size',
                [
                    'label'         => esc_html__('Button Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'sm'    => esc_html('Small','premium-elementor'),
                        'md'    => esc_html('Medium','premium-elementor'),
                        'lg'    => esc_html('Large','premium-elementor'),
                        'block' => esc_html('Block','premium-elementor'),
                    ],
                    'label_block'   => true,
                    'default'       => 'lg',
                    'condition'     => [
                      'premium_modal_box_display_on'  => 'button'
                    ],
                ]
                );
        
        /*Image Source*/ 
        $this->add_control('premium_modal_box_image_src',
                [
                    'label'         => esc_html__('Image', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::MEDIA,
                    'default'       => [
                        'url'   => Utils::get_placeholder_image_src(),
                    ],
                    'condition'     => [
                        'premium_modal_box_display_on'    => 'image',
                    ],
                    'label_block'   => true,
                ]
                );
        
        /*Text Selector*/
        $this->add_control('premium_modal_box_selector_text',
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                    'default'       => esc_html__('Premium Modal Box', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'text',
                    ]
                ]
                );
        
        /*On Load Trigger Delay*/
        $this->add_control('premium_modal_box_popup_delay',
                [
                    'label'         => esc_html__('Delay in Popup Display (Sec)','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'description'   => esc_html__('When should the popup appear during page load? The value are counted in seconds', 'premium-addons-for-elementor'),
                    'default'       => 1,
                    'label_block'   => true,
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'pageload',
                    ]
                ]
                );
        
        
        /*Alignment*/
        $this->add_responsive_control('premium_modal_box_selector_align',
                [
                    'label' => __( 'Alignment', 'premium-addons-for-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                            'left'    => [
                                    'title' => __( 'Left', 'premium-addons-for-elementor' ),
                                    'icon' => 'fa fa-align-left',
                            ],
                            'center' => [
                                    'title' => __( 'Center', 'premium-addons-for-elementor' ),
                                    'icon' => 'fa fa-align-center',
                            ],
                            'right' => [
                                    'title' => __( 'Right', 'premium-addons-for-elementor' ),
                                    'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-selector-container' => 'text-align: {{VALUE}};',
                        ],
                    'condition'     => [
                        'premium_modal_box_display_on!' => 'pageload',
                    ],
                ]
            );
        
        /*End Box Content Section*/
        $this->end_controls_section();
        
        /*Selector Style*/
        $this->start_controls_section('premium_modal_box_selector_style_section',
                [
                    'label'         => esc_html__('Trigger', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_modal_box_display_on!'  => 'pageload',
                        ]
                ]
                );

        /*Button Text Color*/
        $this->add_control('premium_modal_box_button_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector, {{WRAPPER}} .premium-modal-box-text-selector' => 'color:{{VALUE}};',
                        ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text'],
                        ]
                    ]
                );
        
        $this->add_control('premium_modal_box_button_icon_color',
                [
                    'label'         => esc_html__('Icon Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector i' => 'color:{{VALUE}};',
                        ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button'],
                        ]
                    ]
                );

        /*Selector Text Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'selectortext',
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-modal-box-button-selector, {{WRAPPER}} .premium-modal-box-text-selector',
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button','text'],
                    ],
                ]
                );
        
        $this->start_controls_tabs('premium_modal_box_button_style');
        
        /*Button Color*/
        $this->start_controls_tab('premium_modal_box_tab_selector_normal',
                [
                    'label'         => esc_html__( 'Normal', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text','image'],
                        ]
                    ]
		);
        
        /*Button Background Color*/
        $this->add_control('premium_modal_box_selector_background',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector'   => 'background-color: {{VALUE}};',
                        ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'button',
                        ]
                    ]
                );

        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'selector_border',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-button-selector,{{WRAPPER}} .premium-modal-box-text-selector, {{WRAPPER}} .premium-modal-box-img-selector',
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text','image'],
                        ]
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_modal_box_selector_border_radius',
                [
                   'label'          => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'default'       => [
                            'size'  => 0
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector, {{WRAPPER}} .premium-modal-box-text-selector, {{WRAPPER}} .premium-modal-box-img-selector'     => 'border-radius:{{SIZE}}{{UNIT}};',
                    ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                        ],
                    'separator'     => 'after',
                    ]
                );
        
        /*Selector Padding*/
        $this->add_responsive_control('premium_modal_box_selector_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'default'       => [
                        'unit'  => 'px',
                        'top'   => 20,    
                        'right' => 30,
                        'bottom'=> 20,
                        'left'  => 30,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector, {{WRAPPER}} .premium-modal-box-text-selector' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ],
                    'condition'     => [
                            'premium_modal_box_display_on'  => ['button', 'text'],
                        ]
                    ]
                );
        
        /*Selector Box Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_modal_box_selector_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-button-selector, {{WRAPPER}} .premium-modal-box-img-selector',
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'image'],
                        ]
                ]
                );
        
        /*Selector Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'name'          => 'premium_modal_box_selector_text_shadow',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-text-selector',
                    'condition'     => [
                        'premium_modal_box_display_on'  => 'text',
                        ]
                ]
                );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_modal_box_tab_selector_hover',
                [
                    'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button','text','image'],
                        ]
                ]
                );
        
        /*Button Hover Background Color*/
        $this->add_control('premium_modal_box_selector_hover_background',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector:hover' => 'background: {{VALUE}};',
                        ],
                        'condition'     => [
                            'premium_modal_box_display_on'  => 'button',
                        ]
                    ]
                );

        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'selector_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-button-selector:hover,
                    {{WRAPPER}} .premium-modal-box-text-selector:hover, {{WRAPPER}} .premium-modal-box-img-selector:hover',
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                        ]
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_modal_box_selector_border_radius_hover',
                [
                   'label'          => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-button-selector:hover,{{WRAPPER}} .premium-modal-box-text-selector:hover, {{WRAPPER}} .premium-modal-box-img-selector:hover'     => 'border-radius:{{SIZE}}{{UNIT}};',
                    ],
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                        ]
                ]
                );
        
        /*Selector Box Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_modal_box_selector_box_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-button-selector:hover, {{WRAPPER}} .premium-modal-box-text-selector:hover, {{WRAPPER}} .premium-modal-box-img-selector:hover',
                    'condition'     => [
                        'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                        ]
                ]
                );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
                
        $this->end_controls_section();
        
        /*Start Header Seettings Section*/
        $this->start_controls_section('premium_modal_box_header_settings',
                [
                    'label'         => esc_html__('Heading', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_modal_box_header_switcher' => 'yes'
                        ],
                    ]
                );
        
        /*Header Text Color*/
        $this->add_control('premium_modal_box_header_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .premium-modal-box-modal-title' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Header Text Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'headertext',
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-title',
                ]
                );
        
        /*Header Background Color*/
        $this->add_control('premium_modal_box_header_background',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-header'  => 'background: {{VALUE}};',
                        ]
                    ]
                );
        
        /*End Header Settings Section*/
        $this->end_controls_section();
        
        
        /*Start Close Button Section*/
        $this->start_controls_section('premium_modal_box_upper_close_button_section',
                [
                    'label'         => esc_html__('Upper Close Button', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_modal_box_upper_close'   => 'yes',
                        'premium_modal_box_header_switcher' => 'yes'
                    ]
                ]
                );
        
        /*Close Button Size*/
        $this->add_responsive_control('premium_modal_box_upper_close_button_size',
                [
                    'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-header button' => 'font-size: {{SIZE}}{{UNIT}};',
                    ]
                ]
                );
        
        
        
        $this->start_controls_tabs('premium_modal_box_upper_close_button_style');
        
        /*Button Color*/
        $this->start_controls_tab('premium_modal_box_upper_close_button_normal',
                [
                    'label'         => esc_html__( 'Normal', 'premium-addons-for-elementor' ),
                    ]
                );
        
        /*Close Button Color*/
        $this->add_control('premium_modal_box_upper_close_button_normal_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close' => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_upper_close_button_background_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close' => 'background:{{VALUE}};',
                    ],
                ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_modal_upper_border',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-close',
                    ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_modal_upper_border_radius',
                [
                   'label'          => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close'     => 'border-radius:{{SIZE}}{{UNIT}};',
                        ],
                    'separator'     => 'after',
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_modal_box_upper_close_button_hover',
                [
                    'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Close Button Color*/
        $this->add_control('premium_modal_box_upper_close_button_hover_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_upper_close_button_background_color_hover',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close:hover' => 'background:{{VALUE}};',
                    ],
                ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_modal_upper_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-close:hover',
                    ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_modal_upper_border_radius_hover',
                [
                   'label'          => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close:hover'     => 'border-radius:{{SIZE}}{{UNIT}};',
                        ],
                    'separator'     => 'after',
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        /*Upper Close Padding*/
        $this->add_responsive_control('premium_modal_box_upper_close_button_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );

        /*End Upper Close Button Style Section*/
        $this->end_controls_section();
        
        /*Start Close Button Section*/
        $this->start_controls_section('premium_modal_box_lower_close_button_section',
                [
                    'label'         => esc_html__('Lower Close Button', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_modal_box_lower_close'   => 'yes',
                    ]
                ]
                );
        
        /*Close Button Text Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => esc_html__('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'lowerclose',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-lower-close',
                ]
                );
        
        /*Close Button Size*/
        $this->add_responsive_control('premium_modal_box_lower_close_button_width',
                [
                    'label'         => esc_html__('Width', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'range'         => [
                        'px'    => [
                            'min'   => 1,
                            'max'   => 500,
                        ],
                        'em'    => [
                            'min'   => 1,
                            'max'   => 30,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'min-width: {{SIZE}}{{UNIT}};',
                    ]
                ]
                );
        
        
        $this->start_controls_tabs('premium_modal_box_lower_close_button_style');
        
        /*Button Color*/
        $this->start_controls_tab('premium_modal_box_lower_close_button_normal',
                [
                    'label'         => esc_html__( 'Normal', 'premium-addons-for-elementor' ),
                    ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_lower_close_button_normal_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_lower_close_button_background_normal_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        /*Lower Close Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_modal_box_lower_close_border',
                    'selector'          => '{{WRAPPER}} .premium-modal-box-modal-lower-close',
                    ]
                );
        
        /*Lower Close Radius*/
        $this->add_control('premium_modal_box_lower_close_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ],
                    'separator'     => 'after',
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_modal_box_lower_close_button_hover',
                [
                    'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_lower_close_button_hover_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Close Button Background Color*/
        $this->add_control('premium_modal_box_lower_close_button_background_hover_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        /*Lower Close Hover Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_modal_box_lower_close_border_hover',
                    'selector'          => '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover',
                    ]
                );
        
        /*Lower Close Hover Border Radius*/
        $this->add_control('premium_modal_box_lower_close_border_radius_hover',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ],
                    'separator'     => 'after',
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        /*Upper Close Padding*/
        $this->add_responsive_control('premium_modal_box_lower_close_button_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End Lower Close Button Style Section*/
        $this->end_controls_section();
        
        $this->start_controls_section('premium_modal_box_style',
                [
                    'label'         => esc_html__('Modal Box', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Modal Size*/
        $this->add_control('premium_modal_box_modal_size',
                [
                    'label'         => esc_html__('Width', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'range'         => [
                        'px'    => [
                            'min'   => 50,
                            'max'   => 1000,
                        ]
                    ],
                    'label_block'   => true,
                ]
                );
        
        /*Modal Background Color*/
        $this->add_control('premium_modal_box_modal_background',
                [
                    'label'         => esc_html__('Overlay Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal'  => 'background:{{VALUE}};',
                        ]
                    ]
                );
        
        /*Content Background Color*/
        $this->add_control('premium_modal_box_content_background',
                [
                    'label'         => esc_html__('Content Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-body'  => 'background: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Footer Background Color*/
        $this->add_control('premium_modal_box_footer_background',
                [
                    'label'         => esc_html__('Footer Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-footer'  => 'background: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Content Box Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'          => 'contentborder',
                    'selector'      => '{{WRAPPER}} .premium-modal-box-modal-content',
                ]
                );
        
        /*Border Radius*/
        $this->add_control('premium_modal_box_border_radius',
                [
                   'label'          => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-content'     => 'border-radius: {{SIZE}}{{UNIT}};',
                    ]
                ]
                );
        
        /*Modal Box Margin*/
        $this->add_responsive_control('premium_modal_box_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-box-modal-dialog' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('premium_modal_box_selector_text');
      
        $button_icon = $settings['premium_modal_box_button_icon_selection'];
        
        
        $elementor_post_id = $settings['premium_modal_box_content_temp'];
        $premium_elements_frontend = new Frontend;
		$modal_settings = [
            'trigger'   => $settings['premium_modal_box_display_on'],
            'delay'     => $settings['premium_modal_box_popup_delay'],
        ];
?>


<div class="container premium-modal-box-container" data-settings='<?php echo wp_json_encode($modal_settings); ?>'>
  <!-- Trigger The Modal Box -->
  <div class="premium-modal-box-selector-container">
      <?php
      if ( $settings['premium_modal_box_display_on'] === 'button' ) : ?>
      <button type="button" class="premium-modal-box-button-selector btn btn-info <?php
      if( $settings['premium_modal_box_button_size'] === 'sm' ) : echo "premium-btn-sm";
      elseif( $settings['premium_modal_box_button_size'] === 'md' ) : echo "premium-btn-md";
      elseif( $settings['premium_modal_box_button_size'] === 'lg' ) : echo "premium-btn-lg";
      elseif( $settings['premium_modal_box_button_size'] === 'block' ) : echo "premium-btn-block"; endif; ?>" data-toggle="premium-modal" data-target="#premium-modal-<?php echo esc_attr( $this->get_id() ); ?>"><?php if($settings['premium_modal_box_icon_switcher'] && $settings['premium_modal_box_icon_position'] == 'before' && !empty($settings['premium_modal_box_button_icon_selection'])) : ?><i class="fa <?php echo esc_attr($button_icon); ?>"></i><?php endif; ?><span><?php echo $settings['premium_modal_box_button_text']; ?></span><?php if($settings['premium_modal_box_icon_switcher'] && $settings['premium_modal_box_icon_position'] == 'after' &&!empty($settings['premium_modal_box_button_icon_selection'])) : ?><i class="fa <?php echo esc_attr($button_icon); ?>"></i><?php endif; ?></button>
      <?php elseif ( $settings['premium_modal_box_display_on'] === 'image' ) : ?>
      <img class="premium-modal-box-img-selector" data-toggle="premium-modal" data-target="#premium-modal-<?php  echo esc_attr( $this->get_id() ); ?>" src="<?php echo $settings['premium_modal_box_image_src']['url'];?>">
      <?php elseif($settings['premium_modal_box_display_on'] === 'text') : ?>
      <span class="premium-modal-box-text-selector" data-toggle="premium-modal" data-target="#premium-modal-<?php  echo esc_attr( $this->get_id() ); ?>"><div <?php echo $this->get_render_attribute_string('premium_modal_box_selector_text'); ?>><?php echo $settings['premium_modal_box_selector_text'];?></div></span>
       <?php endif; ?>
  </div>
  
  <!-- Modal -->
  <div id="premium-modal-<?php echo  $this->get_id(); ?>"  class="premium-modal-box-modal premium-modal-fade" role="dialog">
    <div class="premium-modal-box-modal-dialog">
    
      <!-- Modal content-->
      <div class="premium-modal-box-modal-content">
          <?php if($settings['premium_modal_box_header_switcher'] == 'yes'): ?>
        <div class="premium-modal-box-modal-header">
            <?php if ( $settings['premium_modal_box_upper_close'] === 'yes' ) : ?>
            <div class="premium-modal-box-close-button-container">
                <button type="button" class="premium-modal-box-modal-close" data-dismiss="premium-modal">&times;</button>
            </div>
            <?php endif; ?>
            <h3 class="premium-modal-box-modal-title">
                <?php if($settings['premium_modal_box_icon_selection'] === 'fonticon') : ?>
                <i class="fa <?php echo $settings['premium_modal_box_font_icon'];?>"></i>
                    <?php elseif( $settings['premium_modal_box_icon_selection'] === 'image' ) : ?>
                <img src="<?php echo $settings['premium_modal_box_image_icon']['url'];?>">
                    <?php endif; ?><?php echo $settings['premium_modal_box_title'];?></h3>
        </div>
          <?php endif; ?>
          <div class="premium-modal-box-modal-body">
              <?php if($settings['premium_modal_box_content_type'] == 'editor') : echo $settings['premium_modal_box_content']; else: echo $premium_elements_frontend->get_builder_content($elementor_post_id, true); endif; ?>
          </div>
          <?php if ( $settings['premium_modal_box_lower_close'] === 'yes' ) : ?>
          <div class="premium-modal-box-modal-footer">
              <button type="button" class="btn premium-modal-box-modal-lower-close" data-dismiss="premium-modal">Close
              </button>
          </div>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<style>
    
    <?php if( !empty($settings['premium_modal_box_modal_size']['size'] ) ) :
        echo '@media (min-width:992px) {'; ?>
        #premium-modal-<?php echo  $this->get_id(); ?> .premium-modal-box-modal-dialog {
            width: <?php echo $settings['premium_modal_box_modal_size']['size'] . $settings['premium_modal_box_modal_size']['unit']; ?>
        } 
        <?php echo '}'; endif; ?>
    
</style>

    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Modal_Box_Widget());