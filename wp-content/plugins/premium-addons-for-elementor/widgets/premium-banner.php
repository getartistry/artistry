<?php 
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Banner extends Widget_Base {

	protected $templateInstance;

	public function getTemplateInstance() {
		return $this->templateInstance = premium_Template_Tags::getInstance();
	}

	public function get_name() {
		return 'premium-addon-banner';
	}

	public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Banner';
	}

	public function get_icon() {
		return 'pa-banner';
	}
    
	public function get_categories() {
		return [ 'premium-elements' ];
	}
    
    public function get_script_depends()
    {
        return ['premium-addons-js'];
    }

	// Adding the controls fields for the premium banner
	// This will controls the animation, colors and background, dimensions etc
	protected function _register_controls() {

		$this->start_controls_section(
			'premium_banner_global_settings',
			[
				'label' 		=> esc_html__( 'Image', 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control(
			'premium_banner_image',
			[
				'label'			=> esc_html__( 'Upload Image', 'premium-addons-for-elementor' ),
				'description'	=> esc_html__( 'Select an image for the Banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
				'default'		=> [
					'url'	=> Utils::get_placeholder_image_src()
				],
				'show_external'	=> true
			]
		);
        
        $this->add_control('premium_banner_link_url_switch',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );

		$this->add_control(
			'premium_banner_image_link_switcher',
			[
				'label'			=> esc_html__( 'Custom Link', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> '',
				'description'	=> esc_html__( 'Add a custom link to the banner', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes',
                ],
			]
		);
        
        $this->add_control(
			'premium_banner_image_custom_link',
			[
				'label'			=> esc_html__( 'Set custom Link', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::URL,
				'description'	=> esc_html__( 'What custom link you want to set to banner?', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_image_link_switcher' => 'yes',
                    'premium_banner_link_url_switch'    => 'yes',
				],
				'show_external' => false,
			]
		);

		$this->add_control(
			'premium_banner_image_existing_page_link',
			[
				'label'			=> esc_html__( 'Existing Page', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT2,
				'description'	=> esc_html__( 'Link the banner with an existing page', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_image_link_switcher!' => 'yes',
                    'premium_banner_link_url_switch'    => 'yes',
				],
                'multiple'      => false,
				'options'		=> $this->getTemplateInstance()->get_all_post()
			]
		);

		$this->add_control(
			'premium_banner_image_link_open_new_tab',
			[
				'label'			=> esc_html__( 'New Tab', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> '',
				'description'	=> esc_html__( 'Choose if you want the link be opened in a new tab or not', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes',
                ],
			]
		);

		$this->add_control(
			'premium_banner_image_link_add_nofollow',
			[
				'label'			=> esc_html__( 'Nofollow Option', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> '',
				'description'	=> esc_html__('if you choose yes, the link will not be counted in search engines', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes',
                ],
			]
		);
        
        $this->add_control(
			'premium_banner_image_animation',
			[
				'label'			=> esc_html__( 'Effect', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'premium_banner_animation1',
				'description'	=> esc_html__( 'Choose a hover effect for the banner', 'premium-addons-for-elementor' ),
				'options'		=> [
					'premium_banner_animation1'		=> 'Effect 1',
					'premium_banner_animation5'		=> 'Effect 2',
					'premium_banner_animation13'	=> 'Effect 3',
					'premium_banner_animation2'		=> 'Effect 4',
					'premium_banner_animation4'		=> 'Effect 5',
					'premium_banner_animation6'		=> 'Effect 6'
				]
			]
		);
        
        $this->add_control(
			'premium_banner_active',
			[
				'label'			=> esc_html__( 'Always Hovered', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'description'	=> esc_html__( 'Choose if you want the effect to be always triggered', 'premium-addons-for-elementor' ),
				
			]
		);
        
        $this->add_control(
            'premium_banner_hover_effect',
                [
                    'label'         => esc_html__('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'none'          => esc_html__('None', 'premium-addons-for-elementor'),
                        'zoomin'        => esc_html__('Zoom In', 'premium-addons-for-elementor'),
                        'zoomout'       => esc_html__('Zoom Out', 'premium-addons-for-elementor'),
                        'scale'         => esc_html__('Scale', 'premium-addons-for-elementor'),
                        'grayscale'     => esc_html__('Grayscale', 'premium-addons-for-elementor'),
                        'blur'          => esc_html__('Blur', 'premium-addons-for-elementor'),
                        'bright'        => esc_html__('Bright', 'premium-addons-for-elementor'),
                        'sepia'         => esc_html__('Sepia', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'none',
                ]
                );
        
        $this->add_control(
			'premium_banner_height',
			[
				'label'			=> esc_html__( 'Height', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
                'options'		=> [
					'default'		=> 'Default',
					'custom'		=> 'Custom',
				],
				'default'		=> 'default',
				'description'	=> esc_html__( 'Choose if you want to set a custom height for the banner or keep it as it is', 'premium-addons-for-elementor' ),
				
			]
		);
        
		$this->add_responsive_control(
			'premium_banner_custom_height',
			[
				'label'			=> esc_html__( 'Min Height', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Set a minimum height value in pixels', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_height' => 'custom'
				],
				'selectors'		=> [
					'{{WRAPPER}} .premium_addons-banner-ib' => 'height: {{VALUE}}px;'
				]
			]
		);
        
        $this->add_responsive_control(
			'premium_banner_img_vertical_align',
			[
				'label'			=> esc_html__( 'Vertical Align', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'condition'		=> [
					'premium_banner_height' => 'custom'
				],
                'options'		=> [
					'flex-start'	=> esc_html__('Top', 'premium-addons-for-elementor'),
                    'center'		=> esc_html__('Middle', 'premium-addons-for-elementor'),
					'flex-end'		=> esc_html__('Bottom', 'premium-addons-for-elementor'),
                    'inherit'		=> esc_html__('Full', 'premium-addons-for-elementor'),
				],
                'default'       => 'flex-start',
				'selectors'		=> [
					'{{WRAPPER}} .premium_addons-banner-img-wrap' => 'align-items: {{VALUE}}; -webkit-align-items: {{VALUE}};'
				]
			]
		);
     
		$this->add_control(
			'premium_banner_extra_class',
			[
				'label'			=> esc_html__( 'Extra Class', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'description'	=> esc_html__( 'Add extra class name that will be applied to the banner, and you can use this class for your customizations.', 'premium-addons-for-elementor' ),
			]
		);

		
		$this->end_controls_section();

		$this->start_controls_section(
  			'premium_banner_image_section',
  			[
  				'label' => esc_html__( 'Content', 'premium-addons-for-elementor' )
  			]
  		);
        
        $this->add_control(
			'premium_banner_title',
			[
				'label'			=> esc_html__( 'Title', 'premium-addons-for-elementor' ),
				'placeholder'	=> esc_html__( 'Give a title to this banner', 'premium-addons-for-elementor' ),
				'description'	=> esc_html__( 'Give a title to this banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
				'default'		=> esc_html__( 'Premium Banner', 'premium-addons-for-elementor' ),
				'label_block'	=> false
			]
		);
        
        $this->add_control(
			'premium_banner_title_tag',
			[
				'label'			=> esc_html__( 'HTML Tag', 'premium-addons-for-elementor' ),
				'description'	=> esc_html__( 'Select a heading tag for the title. Headings are defined with H1 to H6 tags', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'h3',
				'options'       => [
                        'h1'    => 'H1',
                        'h2'    => 'H2',
                        'h3'    => 'H3',
                        'h4'    => 'H4',
                        'h5'    => 'H5',
                        'h6'    => 'H6',
                        ],
				'label_block'	=> true,
			]
		);
        
        
        $this->add_control(
			'premium_banner_description_hint',
			[
				'label'			=> esc_html__( 'Description', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::HEADING,
			]
		);
        
        $this->add_control(
			'premium_banner_description',
			[
				'label'			=> esc_html__( 'Description', 'premium-addons-for-elementor' ),
				'description'	=> esc_html__( 'Give the description to this banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
				'default'		=> esc_html__( 'Premium Banner gives you a wide range of styles and options that you will definitely fall in love with', 'premium-addons-for-elementor' ),
				'label_block'	=> true
			]
		);
        
        $this->add_control(
            'premium_banner_link_switcher',
            [
                'label'         => esc_html__('Button', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );

        
        $this->add_control(
            'premium_banner_more_text', 
            [
                'label'         => esc_html__('Text','premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Click Here',
                'condition'     => [
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );
        
        $this->add_control(
            'premium_banner_link_selection', 
            [
                'label'         => esc_html__('Link Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => esc_html__('URL', 'premium-addons-for-elementor'),
                    'link'  => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'     => [
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );

        $this->add_control(
            'premium_banner_link',
            [
                'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'default'       => [
                    'url'   => '#',
                    ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_banner_link_selection' => 'url',
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );
        
        $this->add_control(
            'premium_banner_existing_link',
            [
                'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'multiple'      => false,
                'condition'     => [
                    'premium_banner_link_selection'     => 'link',
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ],
                'label_block'   => true,
            ]
        );
        
        
        $this->add_control('premium_banner_title_text_align', 
            [
                'label'         => esc_html__('Alignment', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'  => [
                        'title'     => esc_html__('Left', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'  => [
                        'title'     => esc_html__('Center', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'  => [
                        'title'     => esc_html__('Right', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'default'       => 'left',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium_addons-banner-ib-title, {{WRAPPER}} .premium_addons-banner-ib-content, {{WRAPPER}} .premium-banner-read-more'   => 'text-align: {{VALUE}};',
                ]
            ]
            ); 
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_banner_responsive_section',
            [
                'label'         => esc_html__('Responsive', 'premium-addons-for-elementor'),
            ]);
        
        $this->add_control('premium_banner_responsive_switcher',
            [
                'label'         => esc_html__('Responsive Controls', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__('If the description text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'premium-addons-for-elementor')
            ]);
        
        $this->add_control('premium_banner_min_range', 
            [
                'label'     => esc_html__('Minimum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> esc_html__('Note: minimum size for extra small screens is 1px.','premium-addons-for-elementor'),
                'default'   => 1,
                'condition' => [
                    'premium_banner_responsive_switcher'    => 'yes'
                ],
            ]);

        $this->add_control('premium_banner_max_range', 
            [
                'label'     => esc_html__('Maximum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> esc_html__('Note: maximum size for extra small screens is 767px.','premium-addons-for-elementor'),
                'default'   => 767,
                'condition' => [
                    'premium_banner_responsive_switcher'    => 'yes'
                ],
            ]);

		$this->end_controls_section();
        
        $this->start_controls_section(
			'premium_banner_opacity_style',
			[
				'label' 		=> esc_html__( 'Image', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_control(
			'premium_banner_image_bg_color',
			[
				'label' 		=> esc_html__( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .premium_addons-banner-ib' => 'background: {{VALUE}};',
				]
			]
		);
        
		$this->add_control(
			'premium_banner_image_opacity',
			[
				'label' => esc_html__( 'Image Opacity', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
		                'min' => 0,
		                'max' => 1,
		                'step' => .1,
		            ]
				],
				'selectors' => [
		            '{{WRAPPER}} .premium_addons-banner-ib .premium_addons-banner-ib-img' => 'opacity: {{SIZE}};',
		        ],
			]
		);


		$this->add_control(
			'premium_banner_image_hover_opacity',
			[
				'label' => esc_html__( 'Hover Opacity', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
		                'min' => 0,
		                'max' => 1,
		                'step' => .1,
		            ]
				],
				'selectors' => [
		            '{{WRAPPER}} .premium_addons-banner-ib .premium_addons-banner-ib-img.active' => 'opacity: {{SIZE}};',
		        ],
			]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_banner_image_border',
                    'selector'      => '{{WRAPPER}} .premium_addons-banner-ib',
                ]
                );

		$this->add_responsive_control(
			'premium_banner_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units'    => ['px', '%' ,'em'],
				'selectors' => [
		            '{{WRAPPER}} .premium_addons-banner-ib' => 'border-radius: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_banner_title_style',
			[
				'label' 		=> esc_html__( 'Title', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'premium_banner_color_of_title',
			[
				'label' => esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .premium_addons-banner-ib-desc .premium_banner_title' => 'color: {{VALUE}};'
				],
			]
		);
        
        $this->add_control(
			'premium_banner_style2_title_bg',
			[
				'label'			=> esc_html__( 'Title Background', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::COLOR,
				'default'       => '#f2f2f2',
				'description'	=> esc_html__( 'Choose a background color for the title', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_image_animation' => 'premium_banner_animation5'
				],
				'selectors'     => [
				    '{{WRAPPER}} .premium_banner_animation5 .premium_addons-banner-ib-desc'    => 'background: {{VALUE}};',
			    ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_banner_title_typography',
				'selector' => '{{WRAPPER}} .premium_addons-banner-ib-desc .premium_banner_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_title_shadow',
                'selector'          => '{{WRAPPER}} .premium_addons-banner-ib-desc .premium_banner_title',
            ]
            );

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_banner_styles_of_content',
			[
				'label' 		=> esc_html__( 'Description', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'premium_banner_color_of_content',
			[
				'label' => esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .premium_banner .premium_banner_content' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_banner_content_typhography',
				'selector'      => '{{WRAPPER}} .premium_banner .premium_banner_content',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_description_shadow',
                'selector'          => '{{WRAPPER}} .premium_banner .premium_banner_content',
            ]
            );

		$this->end_controls_section();
        
        $this->start_controls_section(
			'premium_banner_styles_of_button',
			[
				'label' 		=> esc_html__( 'Button', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_banner_link_switcher'   => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
			]
		);

		$this->add_control(
			'premium_banner_color_of_button',
			[
				'label' => esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .premium_banner .premium-banner-link' => 'color: {{VALUE}};'
				],
			]
		);
        
        $this->add_control(
			'premium_banner_hover_color_of_button',
			[
				'label' => esc_html__( 'Hover Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .premium_banner .premium-banner-link:hover' => 'color: {{VALUE}};'
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_banner_button_typhography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_3,
				'selector'      => '{{WRAPPER}} .premium_banner .premium-banner-link',
			]
		);
        
        $this->add_control(
			'premium_banner_backcolor_of_button',
			[
				'label' => esc_html__( 'Background Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .premium_banner .premium-banner-link' => 'background-color: {{VALUE}};'
				],
			]
		);
        
        $this->add_control(
			'premium_banner_hover_backcolor_of_button',
			[
				'label' => esc_html__( 'Hover Background Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .premium_banner .premium-banner-link:hover' => 'background-color: {{VALUE}};'
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_banner_button_border',
                    'selector'      => '{{WRAPPER}} .premium_banner .premium-banner-link',
                ]
                );
        
        $this->add_control('premium_banner_button_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium_banner .premium-banner-link' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_button_shadow',
                'selector'          => '{{WRAPPER}} .premium_banner .premium-banner-link',
            ]
            );
        
        $this->add_responsive_control('premium_banner_button_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium_banner .premium-banner-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);

		$this->end_controls_section();

	}


	protected function render() {
			$settings 	= $this->get_settings_for_display(); // All the settings values stored in $settings varaiable
            $this->add_inline_editing_attributes('premium_banner_title');
            $this->add_inline_editing_attributes('premium_banner_description', 'advanced');

			$title_tag 	= $settings[ 'premium_banner_title_tag' ];
			$title 		= $settings[ 'premium_banner_title' ];
			$full_title = '<'. $title_tag . ' class="premium_addons-banner-ib-title ult-responsive premium_banner_title"><div '. $this->get_render_attribute_string('premium_banner_title') .'>' .$title. '</div></'.$title_tag.'>';

			$link = isset( $settings['premium_banner_image_link_switcher'] ) && $settings['premium_banner_image_link_switcher'] != '' ? $settings['premium_banner_image_custom_link']['url'] : get_permalink( $settings['premium_banner_image_existing_page_link'] );

			$link_title = $settings['premium_banner_image_link_switcher'] != 'yes' ? get_the_title( $settings['premium_banner_image_existing_page_link'] ) : '';
            
			$open_new_tab = $settings['premium_banner_image_link_open_new_tab'] == 'yes' ? ' target="_blank"' : '';
            $nofollow_link = $settings['premium_banner_image_link_add_nofollow'] == 'yes' ? ' rel="nofollow"' : '';
			$full_link = '<a class="premium_addons-banner-ib-link" href="'. $link .'" title="'. $link_title .'"'. $open_new_tab . $nofollow_link . '></a>';
			$animation_class = $settings['premium_banner_image_animation'];
            $hover_class = ' ' . $settings['premium_banner_hover_effect'];
			$extra_class = isset( $settings['premium_banner_extra_class'] ) && $settings['premium_banner_extra_class'] != '' ? ' '. $settings['premium_banner_extra_class'] : '';
			$active = $settings['premium_banner_active'] == 'yes' ? ' active' : '';
			$full_class = $animation_class.$hover_class.$extra_class.$active;
            $min_size = $settings['premium_banner_min_range'].'px';
            $max_size = $settings['premium_banner_max_range'].'px';


            $banner_url = 'url' == $settings['premium_banner_link_selection'] ? $settings['premium_banner_link']['url'] : get_permalink($settings['premium_banner_existing_link']);

			ob_start();
		?>
            <div class="premium_banner" id="premium-banner-<?php echo esc_attr($this->get_id()); ?>">
				<div class="premium_addons-banner-ib <?php echo $full_class; ?> premium-banner-min-height">
					<?php if( isset(  $settings['premium_banner_image']['url'] ) &&  $settings['premium_banner_image']['url'] != '' ): ?>
                    <?php if($settings['premium_banner_height'] == 'custom' ): ?>
                    <div class="premium_addons-banner-img-wrap">
                    <?php endif; ?>
                        <img class="premium_addons-banner-ib-img" alt="null" src="<?php echo $settings['premium_banner_image']['url']; ?>">
                    <?php if($settings['premium_banner_height'] == 'custom' ): ?>
                    </div>
                    <?php endif; ?>
					<?php endif; ?>
					<div class="premium_addons-banner-ib-desc">
						<?php echo $full_title; ?>
						<div class="premium_addons-banner-ib-content premium_banner_content">
							<div <?php echo $this->get_render_attribute_string('premium_banner_description'); ?>><?php echo $settings[ 'premium_banner_description' ]; ?></div>
						</div>
                    <?php if( 'yes' == $settings['premium_banner_link_switcher'] && !empty( $settings['premium_banner_more_text'] ) ) : ?>
                        
                            <div class ="premium-banner-read-more">
                                <a class = "premium-banner-link" <?php if( !empty( $banner_url ) ) : ?> href="<?php echo esc_url( $banner_url ); ?>"<?php endif;?><?php if( !empty( $settings['premium_banner_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?><?php if( !empty($settings['premium_banner_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>><?php echo esc_html( $settings['premium_banner_more_text'] ); ?></a>
                            </div>
                        
                    <?php endif; ?>
					</div>
					<?php 
						if( $settings['premium_banner_link_url_switch'] == 'yes' && (!empty( $settings['premium_banner_image_custom_link']['url'] ) || !empty($settings['premium_banner_image_existing_page_link'] )) ) {
							echo $full_link;
						}
					 ?>
				</div>
                <?php if($settings['premium_banner_responsive_switcher'] == 'yes') : ?>
                <style>
                    @media(min-width: <?php echo $min_size; ?> ) and (max-width:<?php echo $max_size; ?>){
                    #premium-banner-<?php echo esc_attr($this->get_id()); ?> .premium_addons-banner-ib-content {
                        display: none;
                        }  
                    }
                </style>
                <?php endif; ?>

			</div>
		<?php $output = ob_get_clean();
		echo $output;
	}

	protected function content_template() {
	
	?>
	

	<?php
	}
}