<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Eael_Post_List extends Widget_Base {

	public function get_name() {
		return 'eael-post-list';
	}

	public function get_title() {
		return __( 'EA Post List', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'eael_section_post_block_filters',
			[
				'label' => __( 'Post Settings', 'essential-addons-elementor' )
			]
		);

		$this->add_control(
            'eael_post_type',
            [
                'label' => __( 'Post Type', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_types(),
                'default' => 'post',

            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Categories', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => eael_post_type_categories(),
                'condition' => [
                       'eael_post_type' => 'post'
                ]
            ]
        );
        $this->add_control(
            'eael_posts_count',
            [
                'label' => __( 'Number of Posts', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '11'
            ]
        );
        $this->add_control(
            'eael_post_offset',
            [
                'label' => __( 'Post Offset', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );
        $this->add_control(
            'eael_post_orderby',
            [
                'label' => __( 'Order By', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_orderby_options(),
                'default' => 'date',

            ]
        );
        $this->add_control(
            'eael_post_order',
            [
                'label' => __( 'Order', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => 'desc',

            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
			'eael_section_post_list_layout',
			[
				'label' => __( 'Layout Settings', 'essential-addons-elementor' )
			]
		);
        $this->add_control(
			'eael_post_list_topbar',
			[
				'label' => __( 'Show Top Bar', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_topbar_title',
			[
				'label' => esc_html__( 'Title Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Recent Posts', 'essential-addons-elementor' ),
				'condition' => [
					'eael_post_list_topbar' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_post_list_topbar_term_all_text',
			[
				'label' => esc_html__( 'Change All Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'All', 'essential-addons-elementor' ),
				'condition' => [
					'eael_post_list_topbar' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_post_list_terms',
			[
				'label' => __( 'Show Category Filter', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'condition' => [
					'eael_post_list_topbar' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_post_list_pagination',
			[
				'label' => __( 'Show Navigation', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_pagination_prev_icon',
			[
				'label' => esc_html__( 'Prev Post Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-angle-left',
				'condition' => [
					'eael_post_list_pagination' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_post_list_pagination_next_icon',
			[
				'label' => esc_html__( 'Next Post Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-angle-right',
				'condition' => [
					'eael_post_list_pagination' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_post_list_featured_area',
			[
				'label' => __( 'Show Featured Post', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_post_list_featured_post_layout',
			[
				'label' => __( 'Featured Post Settings', 'essential-addons-elementor' ),
				'condition' => [
					'eael_post_list_featured_area' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'eael_post_list_featured_height',
			[
				'label' => esc_html__( 'Featured Post Min Height', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 450,
		        ],
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap' => 'min-height: {{SIZE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'eael_post_list_featured_width',
			[
				'label' => esc_html__( 'Featured Post Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 30,
		        ],
				'range' => [
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap' => 'flex: 0 0 {{SIZE}}%;',
				],
			]
		);
		$this->add_responsive_control(
			'eael_post_list_list_width',
			[
				'label' => esc_html__( 'List Area Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 70,
		        ],
				'range' => [
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-posts-wrap' => 'flex: 0 0 {{SIZE}}%;',
				],
			]
		);
		$this->add_control(
			'eael_post_list_featured_meta',
			[
				'label' => __( 'Show Meta', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_featured_title',
			[
				'label' => __( 'Show Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_featured_excerpt',
			[
				'label' => __( 'Show Excerpt', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
        $this->add_control(
            'eael_post_list_featured_excerpt_length',
            [
                'label' => __( 'Excerpt Words', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '8',
                'condition' => [
                    'eael_post_list_featured_excerpt' => 'yes',
                ]

            ]
        );
		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_post_list_post_layout',
			[
				'label' => __( 'List Post Settings', 'essential-addons-elementor' ),
			]
		);
		$this->add_control(
		  	'eael_post_list_columns',
		  	[
		   		'label'       	=> esc_html__( 'Post List Column(s)', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'col-2',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'col-1'	=> esc_html__( '1 Column', 'essential-addons-elementor' ),
		     		'col-2'	=> esc_html__( '2 Columns', 'essential-addons-elementor' ),
		     		'col-3'	=> esc_html__( '3 Columns', 'essential-addons-elementor' ),
		     	],
		     	'prefix_class' 	=> 'eael-post-list-'
		  	]
		);
		$this->add_control(
			'eael_post_list_post_feature_image',
			[
				'label' => __( 'Show Featured Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_post_meta',
			[
				'label' => __( 'Show Meta', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_post_title',
			[
				'label' => __( 'Show Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_post_list_post_excerpt',
			[
				'label' => __( 'Show Excerpt', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
        $this->add_control(
            'eael_post_list_post_excerpt_length',
            [
                'label' => __( 'Excerpt Words', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '12',
                'condition' => [
                    'eael_post_list_post_excerpt' => 'yes',
                ]

            ]
        );
		$this->end_controls_section();

        $this->start_controls_section(
            'eael_section_post_list_style',
            [
                'label' => __( 'EA Post List Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
			'eael_post_list_container_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'eael_post_list_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-post-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_post_list_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-post-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_post_list_container_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-post-list-container',
			]
		);
		$this->add_control(
			'eael_post_list_container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_post_list_container_shadow',
				'selector' => '{{WRAPPER}} .eael-post-list-container',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'eael_section_post_list_topbar_style',
            [
                'label' => __( 'Topbar Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                	'eael_post_list_topbar' => 'yes'
                ]
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_post_list_topbar_border',
				'label' => esc_html__( 'Topbar Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-post-list-header',
			]
		);
        $this->add_control(
	        'eael_section_post_list_topbar_tag_style',
	        [
	        	'label' => esc_html__( 'Title Tag', 'essential-addons-elementor' ),
	          	'type' => Controls_Manager::HEADING,
	          	'separator' => 'before'
	        ]
	    );
		$this->add_control(
			'eael_section_post_list_topbar_bg_color',
			[
				'label' => __( 'Title Tag Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e23a47',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .header-title .title' => 'background-color: {{VALUE}}',
				]

			]
		);
		$this->add_control(
			'eael_section_post_list_topbar_color',
			[
				'label' => __( 'Title Tag Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .header-title .title' => 'color: {{VALUE}}',
				]

			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_section_post_list_topbar_tag_typo',
				'label' => __( 'Tag Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-header .header-title .title',
			]
		);
		$this->add_control(
	        'eael_section_post_list_topbar_category_style',
	        [
	        	'label' => esc_html__( 'Category Filter', 'essential-addons-elementor' ),
	          	'type' => Controls_Manager::HEADING,
	          	'separator' => 'before'
	        ]
	    );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_section_post_list_topbar_category_typo',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-header .post-categories a',
			]
		);
	    $this->add_control(
			'eael_section_post_list_topbar_category_background_color',
			[
				'label' => __( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .post-categories a' => 'background-color: {{VALUE}}',
				]

			]
		);
	    $this->add_control(
			'eael_section_post_list_topbar_category_color',
			[
				'label' => __( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5a5a5a',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .post-categories a' => 'color: {{VALUE}}',
				]

			]
		);
		$this->add_control(
			'eael_section_post_list_topbar_category_active_background_color',
			[
				'label' => __( 'Active Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .post-categories a.active, {{WRAPPER}} .eael-post-list-header .post-categories a:hover' => 'background-color: {{VALUE}}',
				]

			]
		);
		$this->add_control(
			'eael_section_post_list_topbar_category_active_color',
			[
				'label' => __( 'Active Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F56A6A',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-header .post-categories a.active, {{WRAPPER}} .eael-post-list-header .post-categories a:hover' => 'color: {{VALUE}}',
				]

			]
		);
		$this->add_responsive_control(
	       	'eael_section_post_list_topbar_category_padding',
	        [
	        	'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
	         	'type' => Controls_Manager::DIMENSIONS,
	          	'size_units' => [ 'px', 'em', '%' ],
	          	'selectors' => [
	              	'{{WRAPPER}} .eael-post-list-header .post-categories a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	          	],
	        ]
	    );
		$this->add_responsive_control(
	       	'eael_section_post_list_topbar_category_margin',
	        [
	        	'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
	         	'type' => Controls_Manager::DIMENSIONS,
	          	'size_units' => [ 'px', 'em', '%' ],
	          	'selectors' => [
	              	'{{WRAPPER}} .eael-post-list-header .post-categories a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	          	],
	        ]
	    );
		$this->end_controls_section();

		$this->start_controls_section(
            'eael_section_post_list_navigation_style',
            [
                'label' => __( 'Navigation Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                	'eael_post_list_pagination' => 'yes'
                ]
            ]
        );
        $this->add_control(
			'eael_section_post_list_nav_icon_color',
			[
				'label' => __( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container .btn-next-post' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-post-list-container .btn-prev-post' => 'color: {{VALUE}}',
				]

			]
		);
		$this->add_control(
			'eael_section_post_list_nav_icon_bg_color',
			[
				'label' => __( 'Icon Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container .btn-next-post' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .eael-post-list-container .btn-prev-post' => 'background-color: {{VALUE}}',
				]
			]
		);
		$this->add_control(
			'eael_section_post_list_nav_icon_hover_color',
			[
				'label' => __( 'Icon Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container .btn-next-post:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-post-list-container .btn-prev-post:hover' => 'color: {{VALUE}}',
				]
			]
		);
		$this->add_control(
			'eael_section_post_list_nav_icon_hover_bg_color',
			[
				'label' => __( 'Icon Background Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-container .btn-next-post:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .eael-post-list-container .btn-prev-post:hover' => 'background-color: {{VALUE}}',
				]
			]
		);
		$this->add_responsive_control(
	        'eael_section_post_list_nav_icon_padding',
	        [
	          	'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
	          	'type' => Controls_Manager::DIMENSIONS,
	          	'size_units' => [ 'px', 'em', '%' ],
	          	'selectors' => [
	            	'{{WRAPPER}} .eael-post-list-container .btn-next-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	              	'{{WRAPPER}} .eael-post-list-container .btn-prev-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	          	],
	        ]
	    );
		$this->add_responsive_control(
	        'eael_section_post_list_nav_icon_margin',
	        [
	          	'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
	          	'type' => Controls_Manager::DIMENSIONS,
	          	'size_units' => [ 'px', 'em', '%' ],
	          	'selectors' => [
	            	'{{WRAPPER}} .eael-post-list-container .btn-next-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	              	'{{WRAPPER}} .eael-post-list-container .btn-prev-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	          	],
	        ]
	    );
	    $this->add_control(
	        'eael_section_post_list_nav_icon_border_radius',
	        [
	        	'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
	          	'type' => Controls_Manager::SLIDER,
	          	'default' => [
	            	'size' => 0,
	          	],
	          	'range' => [
	            	'px' => [
	              		'max' => 50,
	            	],
	          	],
	          	'selectors' => [
	            	'{{WRAPPER}} .eael-post-list-container .btn-next-post' => 'border-radius: {{SIZE}}px;',
	            	'{{WRAPPER}} .eael-post-list-container .btn-prev-post' => 'border-radius: {{SIZE}}px;',
	          	],
	        ]
	    );
		$this->end_controls_section();

        $this->start_controls_section(
            'eael_post_list_featured_typography',
            [
                'label' => __( 'Featured Post Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
		$this->add_control(
			'eael_post_list_featured_title_settings',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_featured_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title, {{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title a' => 'color: {{VALUE}};',
				]
			]
		);
        $this->add_control(
			'eael_post_list_featured_title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#92939b',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title:hover, {{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title a:hover' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_responsive_control(
			'eael_post_list_featured_title_alignment',
			[
				'label' => __( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title' => 'text-align: {{VALUE}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_featured_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title, {{WRAPPER}} .eael-post-list-featured-wrap .featured-content .eael-post-list-title a',
			]
		);
		$this->add_control(
			'eael_post_list_featured_excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_featured_excerpt_color',
			[
				'label' => __( 'Excerpt Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content p' => 'color: {{VALUE}};',
				]
			]
		);
        $this->add_responsive_control(
			'eael_post_list_featured_excerpt_alignment',
			[
				'label' => __( 'Excerpt Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content p' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_featured_excerpt_typography',
				'label' => __( 'Excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-featured-wrap .featured-content p',
			]
		);
		$this->add_control(
			'eael_post_list_featured_meta_style',
			[
				'label' => __( 'Meta Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_featured_meta_color',
			[
				'label' => __( 'Meta Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .meta' => 'color: {{VALUE}};',
				]
			]
		);
        $this->add_responsive_control(
			'eael_post_list_featured_meta_alignment',
			[
				'label' => __( 'Meta Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .meta' => 'text-align: {{VALUE}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_featured_meta_typography',
				'label' => __( 'Meta Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-featured-wrap .featured-content .meta',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
            'eael_post_list_typography',
            [
                'label' => __( 'List Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
		$this->add_control(
			'eael_post_list_title_settings',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#222',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content .eael-post-list-title, {{WRAPPER}} .eael-post-list-content .eael-post-list-title a' => 'color: {{VALUE}};',
				]

			]
		);
        $this->add_control(
			'eael_post_list_title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#e65a50',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content .eael-post-list-title:hover, {{WRAPPER}} .eael-post-list-content .eael-post-list-title a:hover' => 'color: {{VALUE}};',
				]

			]
		);
		$this->add_responsive_control(
			'eael_post_list_title_alignment',
			[
				'label' => __( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content .eael-post-list-title' => 'text-align: {{VALUE}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-post-list-content .eael-post-list-title, {{WRAPPER}} .eael-post-list-content .eael-post-list-title a',
			]
		);
		$this->add_control(
			'eael_post_list_excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_excerpt_color',
			[
				'label' => __( 'Excerpt Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content p' => 'color: {{VALUE}};',
				]
			]
		);
        $this->add_responsive_control(
			'eael_post_list_excerpt_alignment',
			[
				'label' => __( 'Excerpt Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content p' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_excerpt_typography',
				'label' => __( 'Excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-content p',
			]
		);
		$this->add_control(
			'eael_post_list_meta_style',
			[
				'label' => __( 'Meta Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'eael_post_list_meta_color',
			[
				'label' => __( 'Meta Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#aaa',
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content .meta' => 'color: {{VALUE}};',
				]
			]
		);
        $this->add_responsive_control(
			'eael_post_list_meta_alignment',
			[
				'label' => __( 'Meta Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-post-list-content .meta' => 'text-align: {{VALUE}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_list_meta_typography',
				'label' => __( 'Meta Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-post-list-content .meta',
			]
		);
		$this->end_controls_section();

	}

	protected function render( ) {
		global $wp_query;
        $settings = $this->get_settings();
        $post_args = eael_get_post_settings($settings);
        $posts = eael_get_post_data($post_args);
        /* Get Post Categories */
        $post_categories = $this->get_settings( 'category' );
        if( !empty( $post_categories ) ) {
        	foreach ( $post_categories as $key=>$value ) {
	        	$categories[] = $value;
	        }
	        $categories_id_string = implode( ',' , $categories );

	        /* Get All Post Count */
	        $total_post = 0;
	        foreach( $categories as $cat ) {
	        	$category = get_category( $cat );
	        	$total_post = $total_post + $category->category_count;
	        }
        }else {
        	$categories_id_string = '';
        	$total_post = wp_count_posts( $settings['eael_post_type'] )->publish;
        }
	?>
<div class="eael-post-list-container">
	<?php if( $settings['eael_post_list_topbar'] === 'yes' ) : ?>
	<div class="eael-post-list-header">
		<div class="header-title">
			<h2 class="title"><?php echo esc_html__( $settings['eael_post_list_topbar_title'], 'essential-addons-elementor' ); ?></h2>
		</div>
		<div class="post-categories">
			<?php if( $settings['eael_post_list_terms'] === 'yes' ) : ?>
			<?php if( !empty($categories) ) : ?>
				<a href="javascript:;" class="post-list-cat-<?php echo $this->get_id(); ?> active" data-cat-id="<?php echo $categories_id_string; ?>"><?php echo $settings['eael_post_list_topbar_term_all_text']; ?></a>
				<?php foreach( $categories as $cat_id ) : $category = get_category($cat_id);?>
				<a href="javascript:;" data-cat-id="<?php echo $cat_id; ?>" class="post-list-cat-<?php echo $this->get_id(); ?>"><?php echo $category->name; ?></a>
			<?php endforeach; endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="eael-post-list-wrap" id="eael-post-list-post-appender-<?php echo $this->get_id(); ?>">
		<?php if( $settings['eael_post_list_featured_area'] === 'yes' ) : ?>
			<?php if(count($posts)) : global $post; $counter = 0;?>
				<?php foreach( $posts as $post ) : setup_postdata( $post ); if($counter < 1) :?>
					<div class="eael-post-list-featured-wrap">
						<div class="eael-post-list-featured-inner" style="background-image: url('<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>')">
							<div class="featured-content">
								<?php if( $settings['eael_post_list_featured_meta'] === 'yes' ) : ?>
								<div class="meta">
									<span><i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta ( 'user_nicename' ) ); ?>"><?php the_author(); ?></a></span>
									<span><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></span>
								</div>
								<?php endif; ?>
								<?php if( $settings['eael_post_list_featured_title'] === 'yes' ) : ?>
									<h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php endif; ?>
								<?php if( $settings['eael_post_list_featured_excerpt'] === 'yes' ) : ?>
								<p><?php echo eael_get_excerpt_by_id( get_the_ID(), $settings['eael_post_list_featured_excerpt_length'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; $counter++; endforeach; wp_reset_postdata(); ?>
			<?php endif; ?>
		<?php endif; ?>
		<div class="eael-post-list-posts-wrap">
			<?php if( count($posts) ) : global $post; $i = 0; ?>
				<?php foreach( $posts as $post ) : setup_postdata( $post ); if( $i >= 1 ): ?>
					<div class="eael-post-list-post" >
						<?php if( $settings['eael_post_list_post_feature_image'] === 'yes' ) : ?>
						<div class="eael-post-list-thumbnail<?php if( empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?> eael-empty-thumbnail<?php endif; ?>"><?php if( !empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?><img src="<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>" alt="<?php the_title(); ?>"><?php endif; ?></div> <?php endif; ?>
						<div class="eael-post-list-content">
							<?php if( $settings['eael_post_list_post_title'] === 'yes' ) : ?>
							<h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php endif; ?>
							<?php if( $settings['eael_post_list_post_meta'] === 'yes' ) : ?>
							<div class="meta">
								<span><?php echo get_the_date(); ?></span>
							</div>
							<?php endif; ?>
							<?php if( $settings['eael_post_list_post_excerpt'] === 'yes' ) : ?>
							<p><?php echo eael_get_excerpt_by_id( get_the_ID(), $settings['eael_post_list_post_excerpt_length'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; $i++; endforeach; wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php if( $settings['eael_post_list_pagination'] === 'yes' ) : ?>
		<button class="btn btn-prev-post" id="post-nav-prev-<?php echo $this->get_id(); ?>"><span class="<?php echo esc_attr( $settings['eael_post_list_pagination_prev_icon'] ); ?>"></span></button>
		<button class="btn btn-next-post" id="post-nav-next-<?php echo $this->get_id(); ?>" ><span class="<?php echo esc_attr( $settings['eael_post_list_pagination_next_icon'] ); ?>"></span></button>
	<?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
	'use strict';
	var eael_post_list_settings = {
		ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
		postType: '<?php echo $settings['eael_post_type']; ?>',
		perPage: parseInt( <?php echo $settings['eael_posts_count'] ?>, 10 ),
		postOrder: '<?php echo $settings['eael_post_order'] ?>',
		categories: '<?php echo $categories_id_string; ?>',
		offset: '<?php echo $settings['eael_post_offset']; ?>',
		totalPosts: <?php echo $total_post; ?>,
		queryVars: <?php echo json_encode( $wp_query->query ); ?>,
		nextBtn: '#post-nav-next-<?php echo $this->get_id(); ?>',
		prevBtn: '#post-nav-prev-<?php echo $this->get_id(); ?>',
		postAppender: '#eael-post-list-post-appender-<?php echo $this->get_id(); ?>',
		postCategoryClass: '.post-list-cat-<?php echo $this->get_id(); ?>',
		listFeatureImage: '<?php echo $settings['eael_post_list_post_feature_image']; ?>',
		postExcerpt: '<?php echo $settings['eael_post_list_post_excerpt']; ?>',
		postExcerptLength: '<?php echo $settings['eael_post_list_post_excerpt_length']; ?>',
		postMeta: '<?php echo $settings['eael_post_list_post_meta']; ?>',
		postTitle: '<?php echo $settings['eael_post_list_post_title']; ?>',
		featuredPostMeta: '<?php echo $settings['eael_post_list_featured_meta']; ?>',
		featuredPostTitle: '<?php echo $settings['eael_post_list_featured_title']; ?>',
		featuredPostExcerpt: '<?php echo $settings['eael_post_list_featured_excerpt']; ?>',
		featuredExcerptLength: '<?php echo $settings['eael_post_list_featured_excerpt_length']; ?>'
	}
	eaelLoadMorePostList(eael_post_list_settings);
});
</script>
        <?php

	}

	protected function content_template() {
		?>

		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Post_List() );
add_action( 'wp_ajax_load_more_post_list', array( Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Post_List() ), 'eael_load_more_post_list' ) );