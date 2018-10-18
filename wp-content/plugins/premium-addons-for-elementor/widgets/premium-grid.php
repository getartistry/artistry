<?php

namespace Elementor;

if( !defined( 'ABSPATH' ) ) exit;

class Premium_Grid extends Widget_Base {
    
    public function get_name(){
        return 'premium-img-gallery';
    }
    
    public function getTemplateInstance() {
		return $this->templateInstance = premium_Template_Tags::getInstance();
	}
    
    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Grid';
	}
    
    public function get_icon(){
        return 'pa-grid-icon';
    }
    
    public function get_script_depends(){
        return [
            'prettyPhoto-js',
            'isotope-js',
            'premium-addons-js'
            ];
    }
    
    public function is_reload_preview_required(){
        return true;
    }
    
    public function get_categories(){
        return ['premium-elements'];
    }
    
    protected function _register_controls(){
        
        $this->start_controls_section('premium_gallery_cats',
            [
                'label'     => esc_html__('Categories','premium-addons-for-elementor'),
            ]);
        
        $this->add_control( 'premium_gallery_first_cat_switcher', 
            [
                'label'     => esc_html__( 'First Category', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes'
            ]
        );
        
        $this->add_control( 'premium_gallery_first_cat_label', 
            [
                'label'     => esc_html__( 'First Category Label', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('All', 'premium-addons-for-elementor'),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'premium_gallery_first_cat_switcher'    => 'yes'
                ]
            ]
        );
        
        $repeater = new REPEATER();
        
        $repeater->add_control( 'premium_gallery_img_cat', 
            [
                'label'     => esc_html__( 'Category', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [ 'active' => true ],
            ]
        );
        
        $repeater->add_control( 'premium_gallery_img_cat_rotation',
            [
                'label'         => esc_html__('Rotation Degrees', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => esc_html__('Set rotation value in degress', 'premium-addons-for-elementor'),
                'min'           => -180,
                'max'           => 180,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '-webkit-transform: rotate({{VALUE}}deg); -moz-transform: rotate({{VALUE}}deg); -o-transform: rotate({{VALUE}}deg); transform: rotate({{VALUE}}deg);'
                ],
            ]
        );
        
        $this->add_control('premium_gallery_cats_content',
           [
               'label' => __( 'Categories', 'premium-addons-for-elementor' ),
               'type' => Controls_Manager::REPEATER,
               'default' => [
                   [
                       'premium_gallery_img_cat'   => 'Category 1',
                   ],
                   [
                       'premium_gallery_img_cat'   => 'Category 2',
                   ],
               ],
               'fields' => array_values( $repeater->get_controls() ) ,
               'title_field'   => '{{{ premium_gallery_img_cat }}}',
           ]
       );
        
        $this->add_control( 'premium_gallery_active_cat',
            [
                'label'         => esc_html__('Active Category Index', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => esc_html__('Put the index of the default active category, default is 1', 'premium-addons-for-elementor'),
                'default'       => 1,
                'condition'     => [
                    'premium_gallery_first_cat_switcher!'   => 'yes'
                ]
            ]
        );
    
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content',
            [
                'label'     => esc_html__('Images','premium-addons-for-elementor'),
            ]);
        
        $img_repeater = new REPEATER();
        
        $img_repeater->add_control('premium_gallery_img', 
            [
                'label' => esc_html__( 'Upload Image', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
            ]);
        
        $img_repeater->add_control('premium_gallery_img_name', 
            [
                'label' => esc_html__( 'Name', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_alt', 
            [
                'label' => esc_html__( 'Alt', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_desc', 
            [
                'label' => esc_html__( 'Description', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'label_block' => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_category', 
            [
                'label' => esc_html__( 'Category', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link_type', 
            [
                'label'         => esc_html__('Link Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => esc_html__('URL', 'premium-addons-for-elementor'),
                    'link'  => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link', 
            [
                'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_img_link_type'  => 'url'
                ]
            ]);
        
        $img_repeater->add_control('premium_gallery_img_existing', 
            [
                'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'premium_gallery_img_link_type'=> 'link',
                ],
                'multiple'      => false,
                'separator'     => 'after',
                'label_block'   => true,
            ]);
        
        $this->add_control('premium_gallery_img_content',
           [
               'label' => __( 'Images', 'premium-addons-for-elementor' ),
               'type' => Controls_Manager::REPEATER,
               'default' => [
                   [
                       'premium_gallery_img_name'   => 'Image #1',
                       'premium_gallery_img_category'   => 'Category 1',
                       'premium_gallery_img_alt'    => 'Premium Grid Image'
                   ],
                   [
                       'premium_gallery_img_name'   => 'Image #2',
                       'premium_gallery_img_category' => 'Category 2',
                       'premium_gallery_img_alt'    => 'Premium Grid Image'
                   ],
               ],
               'fields' => array_values( $img_repeater->get_controls() ),
               'title_field'   => '{{{ premium_gallery_img_name }}}' . ' / {{{ premium_gallery_img_category }}}',
           ]
       );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_grid_settings',
            [
                'label'     => esc_html__('Grid Settings','premium-addons-for-elementor'),
                
            ]);
        
        $this->add_responsive_control('premium_gallery_column_number',
			[
  				'label'                 => esc_html__( 'Columns', 'premium-addons-for-elementor' ),
				'label_block'           => true,
				'type'                  => Controls_Manager::SELECT,				
				'desktop_default'       => '50%',
				'tablet_default'        => '100%',
				'mobile_default'        => '100%',
				'options'               => [
					'100%'      => esc_html__( '1 Column', 'premium-addons-for-elementor' ),
					'50%'       => esc_html__( '2 Columns', 'premium-addons-for-elementor' ),
					'33.330%'   => esc_html__( '3 Columns', 'premium-addons-for-elementor' ),
					'25%'       => esc_html__( '4 Columns', 'premium-addons-for-elementor' ),
					'20%'       => esc_html__( '5 Columns', 'premium-addons-for-elementor' ),
					'16.66%'    => esc_html__( '6 Columns', 'premium-addons-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .premium-gallery-container .premium-gallery-item' => 'width: {{VALUE}};',
				],
				'render_type' => 'template'
			]
		);
        
        $this->add_control('premium_gallery_img_size_select',
                [
                    'label'             => esc_html__('Grid Layout', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SELECT,
                    'options'           => [
                        'one_size'  => esc_html__('Even', 'premium-addons-for-elementor'),
                        'original'  => esc_html__('Masonry', 'premium-addons-for-elementor'),
                    ],
                    'default'           => 'one_size',
                    ]
                );
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail', // Actually its `image_size`.
				'default'               => 'full',
                'condition'             => [
                    'premium_gallery_img_size_select'   => 'one_size'
                ]
			]
		);
        
        $this->add_responsive_control('premium_gallery_gap',
                [
                    'label'         => esc_html__('Image Gap', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', "em"],
                    'range'         => [
                        'px'    => [
                            'min'   => 1, 
                            'max'   => 200,
                            ],
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-item' => 'padding: {{SIZE}}{{UNIT}};'
                      ]
                    ]
                );
        
        $this->add_control('premium_gallery_img_style',
                [
                    'label'         => esc_html__('Skin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__('Choose a layout style for the gallery','premium-addons-for-elementor'),
                    'options'       => [
                        'default'           => esc_html__('Style 1', 'premium-addons-for-elementor'),
                        'style1'            => esc_html__('Style 2', 'premium-addons-for-elementor'),
                        'style2'            => esc_html__('Style 3', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'default',
                    'label_block'   => true
                ]
                );
        
        $this->add_responsive_control('premium_gallery_style1_border_border',
                [
                    'label'         => esc_html__('Height', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px'    => [
                            'min'   => 0,
                            'max'   => 700,
                        ]
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.style1 .premium-gallery-caption' => 'bottom: {{SIZE}}px;',
                        ],
                    'condition'     => [
                        'premium_gallery_img_style' => 'style1'
                    ]
                    ]
                );
        
        $this->add_control('premium_gallery_img_effect',
                [
                    'label'         => esc_html__('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__('Choose a hover effect for the image','premium-addons-for-elementor'),
                    'options'       => [
                        'none'          => esc_html__('None', 'premium-addons-for-elementor'),
                        'zoomin'        => esc_html__('Zoom In', 'premium-addons-for-elementor'),
                        'zoomout'       => esc_html__('Zoom Out', 'premium-addons-for-elementor'),
                        'scale'         => esc_html__('Scale', 'premium-addons-for-elementor'),
                        'gray'          => esc_html__('Grayscale', 'premium-addons-for-elementor'),
                        'blur'          => esc_html__('Blur', 'premium-addons-for-elementor'),
                        'bright'        => esc_html__('Bright', 'premium-addons-for-elementor'),
                        'sepia'         => esc_html__('Sepia', 'premium-addons-for-elementor'),
                        'trans'         => esc_html__('Translate', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'zoomin',
                    'label_block'   => true
                ]
                );
        
        $this->add_control('premium_gallery_filter',
                [
                    'label'         => esc_html__( 'Filter', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_control('premium_gallery_light_box',
                [
                    'label'         => esc_html__( 'Lightbox', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_responsive_control('premium_gallery_content_align',
                [
                    'label'         => esc_html__( 'Content Alignment', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_responsive_section',
            [
                'label'         => esc_html__('Responsive', 'premium-addons-for-elementor'),
            ]);
        
        $this->add_control('premium_gallery_responsive_switcher',
            [
                'label'         => esc_html__('Responsive Controls', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__('If the content text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'premium-addons-for-elementor')
            ]);
        
        $this->add_control('premium_gallery_min_range', 
            [
                'label'     => esc_html__('Minimum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> esc_html__('Note: minimum size for extra small screens is 1px.','premium-addons-for-elementor'),
                'default'   => 1,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

        $this->add_control('premium_gallery_max_range', 
            [
                'label'     => esc_html__('Maximum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> esc_html__('Note: maximum size for extra small screens is 767px.','premium-addons-for-elementor'),
                'default'   => 767,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

		$this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_general_style',
            [
                'label'     => esc_html__('General','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_general_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_general_border',
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_general_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_general_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-img-gallery',
            ]
            );
        
        $this->add_responsive_control('premium_gallery_general_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_gallery_general_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_img_style_section',
            [
                'label'     => esc_html__('Image','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_img_border',
                    'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_img_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_img_box_shadow',
                'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
                'condition'         => [
                    'premium_gallery_img_style!' => 'style1'
                ]
            ]
            );
        
        /*First Margin*/
        $this->add_responsive_control('premium_gallery_img_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_gallery_img_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content_style',
            [
                'label'     => esc_html__('Content','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_control('premium_gallery_title_heading',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        $this->add_control('premium_gallery_title_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Fancy Text Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_title_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a',
                    ]
                );
        
        $this->add_control('premium_gallery_description_heading',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                    'separator'     => 'before',
                ]
                );
        
        $this->add_control('premium_gallery_description_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_description_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_content_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    'separator'         => 'before',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_content_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_content_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*First Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_content_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_content_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        /*First Margin*/
        $this->add_responsive_control('premium_gallery_content_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_gallery_content_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_icons_style',
            [
                'label'     => esc_html__('Icons','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_responsive_control('premium_gallery_style1_icons_position',
                [
                    'label'         => esc_html__('Position', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'range'         => [
                        'px'    => [
                            'min'   => 0,
                            'max'   => 300,
                        ]
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.style1 .pa-gallery-icons-inner-container,{{WRAPPER}} .pa-gallery-img.default .pa-gallery-icons-inner-container' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    'condition'     => [
                        'premium_gallery_img_style!' => 'style2'
                        ]
                    ]
                );
        
        $this->start_controls_tabs('premium_gallery_icons_style_tabs');
        
        $this->start_controls_tab('premium_gallery_icons_style_normal',
                [
                    'label'         => esc_html__('Normal', 'premium-addons-for-elementor'),
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image i, {{WRAPPER}} .pa-gallery-img-link i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Icon Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_gallery_icons_style_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_gallery_icons_style_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_gallery_icons_style_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_gallery_icons_style_hover',
        [
            'label'         => esc_html__('Hover', 'premium-addons-for-elementor'),
        ]
        );
        
        $this->add_control('premium_gallery_icons_style_overlay',
                [
                    'label'         => esc_html__('Overlay Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.default:hover .pa-gallery-icons-wrapper, {{WRAPPER}} .pa-gallery-img .pa-gallery-icons-caption-container, {{WRAPPER}} .pa-gallery-img:hover .pa-gallery-icons-caption-container, {{WRAPPER}} .pa-gallery-img.style1:hover .pa-gallery-icons-wrapper' => 'background-color: {{VALUE}};',
                    ],
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_color_hover',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover i, {{WRAPPER}} .pa-gallery-img-link:hover i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background_hover',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border_hover',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_gallery_icons_style_border_radius_hover',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%' ],                    
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow_hover',
                    'selector'      => '{{WRAPPER}} {{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_gallery_icons_style_margin_hover',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_gallery_icons_style_padding_hover',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_filter_style',
            [
                'label'     => esc_html__('Filter','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_gallery_filter'    => 'yes'
                ]
            ]);
        
        $this->add_control('premium_gallery_filter_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_filter_active_color',
                [
                    'label'         => esc_html__('Active Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.active span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_filter_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                    ]
                );
        
        $this->add_control('premium_gallery_background',
                [
                    'label'         => esc_html__( 'Background', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_control('premium_gallery_background_color',
           [
               'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'default'       => '#6ec1e4',
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'background-color: {{VALUE}};',
               ],
               'condition' => [
                    'premium_gallery_background'    => 'yes'
                ]
           ]
       );
        
        $this->add_control('premium_gallery_background_active_color',
           [
               'label'         => esc_html__('Background Active Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'default'       => '#54595f',
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.active' => 'background-color: {{VALUE}};',
               ],
               'condition' => [
                    'premium_gallery_background'    => 'yes'
                ]
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_gallery_filter_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );

        /*Border Radius*/
        $this->add_control('premium_gallery_filter_border_radius',
                [
                    'label'             => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_gallery_filter_shadow',
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );
        
        $this->add_responsive_control('premium_gallery_filter_margin',
                [
                    'label'             => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        /*Front Icon Padding*/
        $this->add_responsive_control('premium_gallery_filter_padding',
                [
                    'label'             => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                'selectors'             => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
    }
    
    public function filter_cats( $string ) {
		$cat_filtered = strtolower( $string );
		$cat_filtered = preg_replace("/[\s_]/", "-", $cat_filtered);
        $cat_filtered = str_replace(',', ' ', $cat_filtered);
		return $cat_filtered;
	}
    
    protected function render(){
        $settings = $this->get_settings_for_display();
        $filter = $settings['premium_gallery_filter'];
        
        $number_columns = str_replace(array('%','.'),'', 'premium-grid-'.$settings['premium_gallery_column_number'] );
        
        $layout = $settings['premium_gallery_img_style'];
        $min_size = $settings['premium_gallery_min_range'].'px';
        $max_size = $settings['premium_gallery_max_range'].'px';
        
        $grid_settings = [
            'img_size'  => $settings['premium_gallery_img_size_select'],
            'filter'    => $settings['premium_gallery_filter'],
            'light_box' => $settings['premium_gallery_light_box']
        ];
        
        ?>
<div id="premium-img-gallery-<?php echo esc_attr($this->get_id()); ?>" class="premium-img-gallery">
    <?php if($filter == 'yes') : ?>
    <div class="premium-img-gallery-filter">
        <ul class="premium-gallery-cats-container">
            <?php if( 'yes' == $settings['premium_gallery_first_cat_switcher'] ) : ?>
            <li><a href="javascript:;" class="category active" data-filter="*"><span><?php echo $settings['premium_gallery_first_cat_label']; ?></span></a></li>
            <?php endif; ?>
            <?php foreach( $settings['premium_gallery_cats_content'] as $index => $category ) : ?>
            <?php if(!empty($category['premium_gallery_img_cat'] ) ) :
                
                $cat_filtered = $this->filter_cats($category['premium_gallery_img_cat']);
                $cat_list_key = 'premium_grid_category_' . $index;
                if( 'yes' != $settings['premium_gallery_first_cat_switcher'] && $settings['premium_gallery_active_cat'] == $index ) {
                    $this->add_render_attribute($cat_list_key,
                        'class',
                        'active'
                    );
                }
            
                $this->add_render_attribute($cat_list_key,
                    'class',
                    array(
                        'category',
                        'elementor-repeater-item-' . $category['_id']
                    )
                );
            
                ?>
            <li><a href="javascript:;" <?php echo $this->get_render_attribute_string($cat_list_key); ?> data-filter=".<?php echo esc_attr( $cat_filtered ); ?>"><span><?php echo esc_attr( $category['premium_gallery_img_cat'] ); ?></span></a></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="premium-gallery-container js-isotope <?php echo esc_attr($number_columns); ?>" data-settings='<?php echo wp_json_encode($grid_settings); ?>'>
        <?php foreach( $settings['premium_gallery_img_content'] as $image ) : ?>
        <div class="premium-gallery-item <?php echo esc_attr( $this->filter_cats( $image['premium_gallery_img_category'] ) ); ?>">
            <div class="pa-gallery-img <?php echo esc_attr($layout); ?>" onclick="">
                <div class="pa-gallery-img-container <?php echo esc_attr($settings['premium_gallery_img_effect']); ?>">
                    <?php if($settings['premium_gallery_img_size_select'] == 'one_size'):
                        $image_src = $image['premium_gallery_img'];
                        $image_src_size = Group_Control_Image_Size::get_attachment_image_src( $image_src['id'], 'thumbnail', $settings );
                        if( empty( $image_src_size ) ) : $image_src_size = $image_src['url']; else: $image_src_size = $image_src_size; endif;
                        ?>
                    <img src="<?php echo $image_src_size; ?>" class="pa-gallery-image" alt="<?php echo esc_attr($image['premium_gallery_img_alt']); ?>">
                    <?php else : ?>
                    <img src="<?php echo esc_url($image['premium_gallery_img']['url']); ?>" class="pa-gallery-image" alt="<?php echo esc_attr($image['premium_gallery_img_alt']); ?>">
                    <?php endif; ?>
                </div>
                <?php if($layout == 'default') : ?>
                <div class="pa-gallery-icons-wrapper">
                    <div class="pa-gallery-icons-inner-container">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>                            
                        </div>
                </div>
                <div class="premium-gallery-caption">
                            <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                            <?php endif; ?>
                            <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                            <?php endif; ?>
                </div>
                <?php elseif( $layout == 'style1' ) : ?>
                <div class="pa-gallery-icons-wrapper">
                    <div class="pa-gallery-icons-inner-container">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>                            
                    </div>
                </div>
                <div class="premium-gallery-caption">
                            <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                            <?php endif; ?>
                            <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                            <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="pa-gallery-icons-caption-container">
                    <div class="pa-gallery-icons-caption-cell">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                    <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>                            
                        <div class="premium-gallery-caption">    
                        <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                        <?php endif; ?>
                        <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
            <div class="premium-gallery-container js-isotope  <?php echo esc_attr($number_columns); ?>" data-settings='<?php echo wp_json_encode($grid_settings); ?>'>
        <?php foreach( $settings['premium_gallery_img_content'] as $image ) : ?>
        <div class="premium-gallery-item <?php echo esc_attr( $this->filter_cats( $image['premium_gallery_img_category'] ) ); ?>">
            <div class="pa-gallery-img <?php echo esc_attr($layout); ?>" onclick="">
                <div class="pa-gallery-img-container <?php echo esc_attr($settings['premium_gallery_img_effect']); ?>">
                    <?php if($settings['premium_gallery_img_size_select'] == 'one_size') :
                        $image_src = $image['premium_gallery_img'];
                        $image_src_size = Group_Control_Image_Size::get_attachment_image_src( $image_src['id'], 'thumbnail', $settings );
                        if( empty( $image_src_size ) ) : $image_src_size = $image_src['url']; else: $image_src_size = $image_src_size; endif;?>
                    <img src="<?php echo esc_url($image_src_size); ?>" class="pa-gallery-image" alt="<?php echo esc_attr($image['premium_gallery_img_alt']); ?>">
                    <?php else : ?>
                    <img src="<?php echo esc_url($image['premium_gallery_img']['url']); ?>" class="pa-gallery-image" alt="<?php echo esc_attr($image['premium_gallery_img_alt']); ?>">
                    <?php endif; ?>
                </div>
                <?php if($layout == 'default') : ?>
                <div class="pa-gallery-icons-wrapper">
                    <div class="pa-gallery-icons-inner-container">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>
                        </div>
                </div>
                <div class="premium-gallery-caption">
                            <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                            <?php endif; ?>
                            <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                            <?php endif; ?>
                </div>
                <?php elseif( $layout == 'style1' ) : ?>
                <div class="pa-gallery-icons-wrapper">
                    <div class="pa-gallery-icons-inner-container">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>                            
                        </div>
                </div>
                <div class="premium-gallery-caption">
                            <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                            <?php endif; ?>
                            <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                            <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="pa-gallery-icons-caption-container">
                    <div class="pa-gallery-icons-caption-cell">
                    <?php if( 'yes' == $settings['premium_gallery_light_box'] ) : ?> 
                    <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                    <?php endif; ?>
                        <?php if( $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                    <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php elseif( $image['premium_gallery_img_link_type'] == 'link') : 
                            $icon_link = get_permalink($image['premium_gallery_img_existing']);
                            ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                        <?php endif; ?>                            
                        <div class="premium-gallery-caption">    
                        <?php if(!empty($image['premium_gallery_img_name'])):?>
                            <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                        <?php endif; ?>
                        <?php if(!empty($image['premium_gallery_img_desc'])):?>
                            <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
        <?php if($settings['premium_gallery_responsive_switcher'] == 'yes') : ?>
        <style>
            @media(min-width: <?php echo $min_size; ?> ) and (max-width:<?php echo $max_size; ?>){
                #premium-img-gallery-<?php echo esc_attr($this->get_id()); ?> .premium-gallery-caption {
                    display: none;
                    }  
            }
        </style>
        <?php endif; ?>
    <?php }
}