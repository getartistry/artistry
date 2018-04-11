<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Team Member Carousel Widget
 */
class PP_Team_Member_Carousel_Widget extends Widget_Base {
    
    /**
	 * Retrieve team member carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-team-member-carousel';
    }

    /**
	 * Retrieve team member carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Team Member Carousel', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the team member carousel widget belongs to.
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
	 * Retrieve team member carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'fa fa-user-o power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the team member carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'jquery-swiper',
            'pp-scripts'
        ];
    }

    /**
	 * Register team member carousel widget controls.
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
         * Content Tab: Team Members
         */
        $this->start_controls_section(
            'section_team_member',
            [
                'label'             => __( 'Team Members', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'team_member_details',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'team_member_name'        => 'Team Member #1',
						'team_member_position'    => 'WordPress Developer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
					[
						'team_member_name'        => 'Team Member #2',
						'team_member_position'    => 'Web Designer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
					[
						'team_member_name'        => 'Team Member #3',
						'team_member_position'    => 'Testing Engineer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
				],
				'fields'            => [
					[
						'name'        => 'team_member_name',
						'label'       => __( 'Name', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'default'     => __( 'John Doe', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_position',
						'label'       => __( 'Position', 'power-pack' ),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => __( 'WordPress Developer', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_description',
						'label'       => __( 'Description', 'power-pack' ),
                        'type'        => Controls_Manager::TEXTAREA,
                        'default'     => __( 'Enter member description here which describes the position of member in company', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_image',
						'label'       => __( 'Image', 'power-pack' ),
                        'type'        => Controls_Manager::MEDIA,
                        'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
                    [
                        'name'        => 'social_links_heading',
                        'label'       => __('Social Links', 'power-pack'),
                        'type'        => Controls_Manager::HEADING,
                        'separator'   => 'before',
                    ],
					[
						'name'        => 'facebook_url',
						'label'       => __( 'Facebook', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Facebook page or profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'twitter_url',
						'label'       => __( 'Twitter', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Twitter profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'google_plus_url',
						'label'       => __( 'Google+', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Google+ profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'linkedin_url',
						'label'       => __( 'Linkedin', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Linkedin profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'instagram_url',
						'label'       => __( 'Instagram', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Instagram profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'youtube_url',
						'label'       => __( 'YouTube', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter YouTube profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'pinterest_url',
						'label'       => __( 'Pinterest', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Pinterest profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'dribbble_url',
						'label'       => __( 'Dribbble', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'description' => __( 'Enter Dribbble profile URL of team member', 'power-pack' ),
					],
				],
				'title_field'       => '{{{ team_member_name }}}',
			]
		);
        
        $this->add_control(
            'member_social_links',
            [
                'label'             => __( 'Show Social Icons', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Team Member Settings
         */
        $this->start_controls_section(
            'section_member_box_settings',
            [
                'label'             => __( 'Team Member Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'name_html_tag',
            [
                'label'                => __( 'Name HTML Tag', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'h4',
                'options'              => [
                    'h1'     => __( 'H1', 'power-pack' ),
                    'h2'     => __( 'H2', 'power-pack' ),
                    'h3'     => __( 'H3', 'power-pack' ),
                    'h4'     => __( 'H4', 'power-pack' ),
                    'h5'     => __( 'H5', 'power-pack' ),
                    'h6'     => __( 'H6', 'power-pack' ),
                    'div'    => __( 'div', 'power-pack' ),
                    'span'   => __( 'span', 'power-pack' ),
                    'p'      => __( 'p', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'position_html_tag',
            [
                'label'                => __( 'Position HTML Tag', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'div',
                'options'              => [
                    'h1'     => __( 'H1', 'power-pack' ),
                    'h2'     => __( 'H2', 'power-pack' ),
                    'h3'     => __( 'H3', 'power-pack' ),
                    'h4'     => __( 'H4', 'power-pack' ),
                    'h5'     => __( 'H5', 'power-pack' ),
                    'h6'     => __( 'H6', 'power-pack' ),
                    'div'    => __( 'div', 'power-pack' ),
                    'span'   => __( 'span', 'power-pack' ),
                    'p'      => __( 'p', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'social_links_position',
            [
                'label'                => __( 'Social Icons Position', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'after_desc',
                'options'              => [
                    'before_desc'      => __( 'Before Description', 'power-pack' ),
                    'after_desc'       => __( 'After Description', 'power-pack' ),
                ],
				'condition'         => [
					'member_social_links' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'overlay_content',
            [
                'label'                => __( 'Overlay Content', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'none',
                'options'              => [
                    'none'             => __( 'None', 'power-pack' ),
                    'social_icons'     => __( 'Social Icons', 'power-pack' ),
                    'all_content'      => __( 'Content + Social Icons', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'member_title_divider',
            [
                'label'             => __( 'Divider after Name', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'separator'         => 'before',
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'member_position_divider',
            [
                'label'             => __( 'Divider after Position', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'hide',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'member_description_divider',
            [
                'label'             => __( 'Divider after Description', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'hide',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Slider Settings
         */
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label'             => __( 'Slider Settings', 'power-pack' ),
            ]
        );
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 3 ],
                'tablet_default'        => [ 'size' => 2 ],
                'mobile_default'        => [ 'size' => 1 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_responsive_control(
            'margin',
            [
                'label'             => __( 'Margin', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'default'           => [ 'size' => 10 ],
                'tablet_default'        => [ 'size' => 10 ],
                'mobile_default'        => [ 'size' => 10 ],
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => '',
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'             => __( 'Autoplay', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2000 ],
                'range'                 => [
                    'px' => [
                        'min'   => 500,
                        'max'   => 5000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'         => [
                    'autoplay'      => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'             => __( 'Infinite Loop', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'power-pack' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'name_navigation_heading',
            [
                'label'             => __( 'Navigation', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'             => __( 'Arrows', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'             => __( 'Dots', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Box Style
         */
        $this->start_controls_section(
            'section_member_box_style',
            [
                'label'             => __( 'Box Style', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'member_box_alignment',
            [
                'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .pp-tm' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_member_content_style',
            [
                'label'             => __( 'Content', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'member_box_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-content-normal' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_box_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'separator'         => 'before',
				'selector'          => '{{WRAPPER}} .pp-tm-content-normal',
			]
		);

		$this->add_control(
			'member_box_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_box_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'pa_member_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-tm-content-normal',
				'separator'         => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_member_overlay_style',
            [
                'label'             => __( 'Overlay', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
				'condition'         => [
					'overlay_content!' => 'none',
				],
            ]
        );
        
        $this->add_responsive_control(
            'overlay_alignment',
            [
                'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .pp-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'         => [
					'overlay_content!' => 'none',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'overlay_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-tm-overlay-content-wrap:before',
				'condition'         => [
					'overlay_content!' => 'none',
				],
			]
		);
        
        $this->add_control(
			'overlay_opacity',
			[
				'label'             => __( 'Opacity', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'range'             => [
					'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				],
				'condition'         => [
					'overlay_content!' => 'none',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_member_image_style',
            [
                'label'             => __( 'Image', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_image_width',
			[
				'label'             => __( 'Image Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ '%', 'px' ],
				'range'             => [
					'px' => [
						'max' => 1200,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_image_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-tm-image img',
			]
		);

		$this->add_control(
			'member_image_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-image img, {{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'member_image_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Name
         */
        $this->start_controls_section(
            'section_member_name_style',
            [
                'label'             => __( 'Name', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_name_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-name',
            ]
        );

        $this->add_control(
            'member_name_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'member_name_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'name_divider_heading',
            [
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_title_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'name_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'name_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'name_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'name_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'name_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Position
         */
        $this->start_controls_section(
            'section_member_position_style',
            [
                'label'             => __( 'Position', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_position_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-position',
            ]
        );

        $this->add_control(
            'member_position_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-position' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'member_position_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'position_divider_heading',
            [
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_position_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'position_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'position_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'position_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'position_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'position_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Description
         */
        $this->start_controls_section(
            'section_member_description_style',
            [
                'label'             => __( 'Description', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_description_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-description',
            ]
        );

        $this->add_control(
            'member_description_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'member_description_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'description_divider_heading',
            [
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_description_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'description_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'description_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'description_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'description_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'description_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Social Icons
         */
        $this->start_controls_section(
            'section_member_social_links_style',
            [
                'label'             => __( 'Social Icons', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_icons_gap',
			[
				'label'             => __( 'Icons Gap', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [ 'size' => 10 ],
				'size_units'        => [ '%', 'px' ],
				'range'             => [
					'px' => [
						'max' => 60,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-social-links li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'member_icon_size',
			[
				'label'                 => __( 'Icon Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'max' => 30,
					],
				],
				'default'    => [
					'size' => '14',
					'unit' => 'px',
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_links_style' );

        $this->start_controls_tab(
            'tab_links_normal',
            [
                'label'             => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color',
            [
                'label'             => __( 'Icons Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_links_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'separator'         => 'before',
				'selector'          => '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap',
			]
		);

		$this->add_control(
			'member_links_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_links_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'separator'         => 'before',
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_links_hover',
            [
                'label'             => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color_hover',
            [
                'label'             => __( 'Icons Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color_hover',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_border_color_hover',
            [
                'label'             => __( 'Border Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => true,
                'default'               => 'fa fa-angle-right',
                'options'               => [
                    'fa fa-angle-right'             => __( 'Angle', 'power-pack' ),
                    'fa fa-angle-double-right'      => __( 'Double Angle', 'power-pack' ),
                    'fa fa-chevron-right'           => __( 'Chevron', 'power-pack' ),
                    'fa fa-chevron-circle-right'    => __( 'Chevron Circle', 'power-pack' ),
                    'fa fa-arrow-right'             => __( 'Arrow', 'power-pack' ),
                    'fa fa-long-arrow-right'        => __( 'Long Arrow', 'power-pack' ),
                    'fa fa-caret-right'             => __( 'Caret', 'power-pack' ),
                    'fa fa-caret-square-o-right'    => __( 'Caret Square', 'power-pack' ),
                    'fa fa-arrow-circle-right'      => __( 'Arrow Circle', 'power-pack' ),
                    'fa fa-arrow-circle-o-right'    => __( 'Arrow Circle O', 'power-pack' ),
                    'fa fa-toggle-right'            => __( 'Toggle', 'power-pack' ),
                    'fa fa-hand-o-right'            => __( 'Hand', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '22' ],
                'range'                 => [
                    'px' => [
                        'min'   => 15,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'                 => __( 'Align Left Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'                 => __( 'Align Right Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'dots'      => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'inside'     => __( 'Inside', 'power-pack' ),
                   'outside'    => __( 'Outside', 'power-pack' ),
                ],
                'default'               => 'outside',
            ]
        );
        
        $this->add_responsive_control(
            'dots_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 2,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_dot_color_normal',
            [
                'label'                 => __( 'Active Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
                'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
        $image = $this->get_settings( 'member_image' );

        $this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap pp-team-member-carousel-wrap' );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
        }
        
        $this->add_render_attribute( 'team-member-carousel', 'class', 'swiper-container pp-tm-wrapper pp-tm-carousel' );
        $this->add_render_attribute( 'team-member-carousel', 'id', 'swiper-container-'.esc_attr($this->get_id()) );
        $this->add_render_attribute( 'team-member-carousel', 'data-pagination', '.swiper-pagination-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'team-member-carousel', 'data-arrow-next', '.swiper-button-next-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'team-member-carousel', 'data-arrow-prev', '.swiper-button-prev-'.esc_attr( $this->get_id() ) );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'team-member-carousel', 'class', 'pp-tm-carousel-dots-' . $settings['dots_position'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'team-member-carousel-wrap' ); ?>>
            <div
                 <?php echo $this->get_render_attribute_string( 'team-member-carousel' ); ?>
                 <?php
                    echo 'data-id="swiper-container-'.esc_attr($this->get_id()).'"';
                    if ( ! empty( $settings['items']['size'] ) ) {
                        echo 'data-items="' . $settings['items']['size'] . '"';
                    }
                    if ( ! empty( $settings['items_tablet']['size'] ) ) {
                        echo 'data-items-tablet="' . $settings['items_tablet']['size'] . '"';
                    }
                    if ( ! empty( $settings['items_mobile']['size'] ) ) {
                        echo 'data-items-mobile="' . $settings['items_mobile']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin']['size'] ) ) {
                        echo 'data-margin="' . $settings['margin']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin_tablet']['size'] ) ) {
                        echo 'data-margin-tablet="' . $settings['margin_tablet']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin_mobile']['size'] ) ) {
                        echo 'data-margin-mobile="' . $settings['margin_mobile']['size'] . '"';
                    }
                    if ( ! empty( $settings['slider_speed']['size'] ) ) {
                        echo 'data-speed="' . $settings['slider_speed']['size'] . '"';
                    }
                    if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
                        echo 'data-autoplay="' . $settings['autoplay_speed']['size'] . '"';
                    } else {
                        echo 'data-autoplay="0"';
                    }
                    if ( $settings['infinite_loop'] == 'yes' ) {
                        echo 'data-loop="1"';
                    }
                    if ( $settings['grab_cursor'] == 'yes' ) {
                        echo 'data-grab-cursor="1"';
                    }
                    if ( $settings['arrows'] == 'yes' ) {
                        echo 'data-arrows="1"';
                    }
                    if ( $settings['dots'] == 'yes' ) {
                        echo 'data-dots="1"';
                    }
                 ?>
                 >
                    <div class="swiper-wrapper">
                    <?php foreach ( $settings['team_member_details'] as $index => $item ) : ?>
                    <div class="swiper-slide">
                        <div class="pp-tm">
                            <div class="pp-tm-image"> 
                                <?php echo '<img src="' . $item['team_member_image']['url'] . '">'; ?>

                                <?php if ( $settings['overlay_content'] == 'social_icons' ) { ?>
                                    <div class="pp-tm-overlay-content-wrap">
                                        <div class="pp-tm-content">
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    $this->member_social_links( $item );
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ( $settings['overlay_content'] == 'all_content' ) { ?>
                                    <div class="pp-tm-overlay-content-wrap">
                                        <div class="pp-tm-content">
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    if ( $settings['social_links_position'] == 'before_desc' ) {
                                                        $this->member_social_links( $item );
                                                    }
                                                }
                                            ?>
                                            <?php $this->render_description( $item ); ?>
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    if ( $settings['social_links_position'] == 'after_desc' ) {
                                                        $this->member_social_links( $item );
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ( $settings['overlay_content'] == 'all_content' ) { ?>
                                <div class="pp-tm-content pp-tm-content-normal">
                                    <?php
                                        // Name
                                        $this->render_name( $item );

                                        // Position
                                        $this->render_position( $item );
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ( $settings['overlay_content'] != 'all_content' ) { ?>
                                <div class="pp-tm-content pp-tm-content-normal">
                                    <?php
                                        $this->render_name( $item );
                                    ?>
                                    <?php $this->render_position( $item ); ?>
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                            if ( $settings['social_links_position'] == 'before_desc' ) {
                                                $this->member_social_links( $item );
                                            }
                                        }
                                    ?>
                                    <?php $this->render_description( $item ); ?>
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                            if ( $settings['social_links_position'] == 'after_desc' ) {
                                                $this->member_social_links( $item );
                                            }
                                        }
                                    ?>
                                </div><!-- .pp-tm-content -->
                            <?php } ?>
                        </div><!-- .pp-tm -->
                    </div><!-- .swiper-slide -->
                <?php endforeach; ?>
                </div>
            </div>
            <?php
                $this->render_dots();

                $this->render_arrows();
            ?>
        </div>
        <?php
    }
    
    protected function render_name( $item ) {
        $settings = $this->get_settings();

        if ( $item['team_member_name'] != '' ) {
                printf( '<%1$s class="pp-tm-name">%2$s</%1$s>', $settings['name_html_tag'], $item['team_member_name'] );
            }
        ?>
        <?php if ( $settings['member_title_divider'] == 'yes' ) { ?>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
        <?php }
    }
    
    protected function render_position( $item ) {
        $settings = $this->get_settings();
        
        if ( $item['team_member_position'] != '' ) {
                printf( '<%1$s class="pp-tm-position">%2$s</%1$s>', $settings['position_html_tag'], $item['team_member_position'] );
            }
        ?>
        <?php if ( $settings['member_position_divider'] == 'yes' ) { ?>
            <div class="pp-tm-position-divider-wrap">
                <div class="pp-tm-divider pp-tm-position-divider"></div>
            </div>
        <?php }
    }
    
    protected function render_description( $item ) {
        $settings = $this->get_settings();
        if ( $item['team_member_description'] != '' ) { ?>
            <div class="pp-tm-description">
                <?php echo esc_attr( $item['team_member_description'] ); ?>
            </div>
        <?php } ?>
        <?php if ( $settings['member_description_divider'] == 'yes' ) { ?>
            <div class="pp-tm-description-divider-wrap">
                <div class="pp-tm-divider pp-tm-description-divider"></div>
            </div>
        <?php }
    }
    
    private function member_social_links( $item ) {
        
        $facebook_url       = $item['facebook_url'];
        $twitter_url        = $item['twitter_url'];
        $google_plus_url    = $item['google_plus_url'];
        $linkedin_url       = $item['linkedin_url'];
        $instagram_url      = $item['instagram_url'];
        $youtube_url        = $item['youtube_url'];
        $pinterest_url      = $item['pinterest_url'];
        $dribbble_url       = $item['dribbble_url'];
        ?>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <?php
                    if ( $facebook_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-facebook"></span></span></a></li>', esc_url( $facebook_url )  );
                    }
                    if ( $twitter_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-twitter"></span></span></a></li>', esc_url( $twitter_url )  );
                    }
                    if ( $google_plus_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-google-plus"></span></span></a></li>', esc_url( $google_plus_url )  );
                    }
                    if ( $linkedin_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-linkedin"></span></span></a></li>', esc_url( $linkedin_url )  );
                    }
                    if ( $instagram_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-instagram"></span></span></a></li>', esc_url( $instagram_url )  );
                    }
                    if ( $youtube_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-youtube"></span></span></a></li>', esc_url( $youtube_url )  );
                    }
                    if ( $pinterest_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-pinterest"></span></span></a></li>', esc_url( $pinterest_url )  );
                    }
                    if ( $dribbble_url ) {
                        printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-dribbble"></span></span></a></li>', esc_url( $dribbble_url )  );
                    }
                ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render team member carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_dots() {
        $settings = $this->get_settings();

        if ( $settings['dots'] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
        <?php }
    }

    /**
	 * Render team member carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_arrows() {
        $settings = $this->get_settings();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                if ( $settings['arrow'] ) {
                    $pa_next_arrow = $settings['arrow'];
                    $pa_prev_arrow = str_replace("right","left",$settings['arrow']);
                }
                else {
                    $pa_next_arrow = 'fa fa-angle-right';
                    $pa_prev_arrow = 'fa fa-angle-left';
                }
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
        <?php }
    }
    
    protected function _name_template() {
        ?>
        <# if ( item.team_member_name != '' ) { #>
            <{{ settings.name_html_tag }} class="pp-tm-name">
                {{ item.team_member_name }}
            </{{ settings.name_html_tag }}>
        <# } #>
        
        <# if ( settings.member_title_divider == 'yes' ) { #>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
        <# } #>
        <?php
    }

    protected function _position_template() {
        ?>
        <# if ( item.team_member_position != '' ) { #>
            <{{ settings.position_html_tag }} class="pp-tm-position">
                {{ item.team_member_position }}
            </{{ settings.position_html_tag }}>
        <# } #>
        <# if ( settings.member_position_divider == 'yes' ) { #>
            <div class="pp-tm-position-divider-wrap">
                <div class="pp-tm-divider pp-tm-position-divider"></div>
            </div>
        <# } #>
        <?php
    }
    
    protected function _description_template() {
        ?>
        <# if ( item.team_member_description != '' ) { #>
            <div class="pp-tm-description">
                {{ item.team_member_description }}
            </div>
        <# } #>
        <# if ( settings.member_description_divider == 'yes' ) { #>
            <div class="pp-tm-description-divider-wrap">
                <div class="pp-tm-divider pp-tm-description-divider"></div>
            </div>
        <# } #>
        <?php
    }
    
    protected function _member_social_links_template() {
        ?>
        <#
        var facebook_url       = item.facebook_url,
            twitter_url        = item.twitter_url,
            google_plus_url    = item.google_plus_url,
            linkedin_url       = item.linkedin_url,
            instagram_url      = item.instagram_url,
            youtube_url        = item.youtube_url,
            pinterest_url      = item.pinterest_url,
            dribbble_url       = item.dribbble_url;
        #>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <# if ( facebook_url != '' ) { #>
                    <li>
                        <a href="{{ facebook_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-facebook"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( twitter_url != '' ) { #>
                    <li>
                        <a href="{{ twitter_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-twitter"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( google_plus_url != '' ) { #>
                    <li>
                        <a href="{{ google_plus_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-google-plus"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( linkedin_url != '' ) { #>
                    <li>
                        <a href="{{ linkedin_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-linkedin"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( instagram_url != '' ) { #>
                    <li>
                        <a href="{{ instagram_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-instagram"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( youtube_url != '' ) { #>
                    <li>
                        <a href="{{ youtube_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-youtube"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( pinterest_url != '' ) { #>
                    <li>
                        <a href="{{ pinterest_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-pinterest"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( dribbble_url != '' ) { #>
                    <li>
                        <a href="{{ dribbble_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-dribbble"></span></span>
                        </a>
                    </li>
                <# } #>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render team member carousel dots widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _dots_template() {
        ?>
        <# if ( settings.dots == 'yes' ) { #>
            <div class="swiper-pagination"></div>
        <# } #>
        <?php
    }

    /**
	 * Render team member carousel arrows widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _arrows_template() {
        ?>
        <# if ( settings.arrows == 'yes' ) { #>
            <#
                if ( settings.arrow != '' ) {
                    var pp_next_arrow = settings.arrow;
                    var pp_prev_arrow = pp_next_arrow.replace('right', "left");
                }
                else {
                    var pp_next_arrow = 'fa fa-angle-right';
                    var pp_prev_arrow = 'fa fa-angle-left';
                }
            #>
            <div class="swiper-button-next">
                <i class="{{ pp_next_arrow }}"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="{{ pp_prev_arrow }}"></i>
            </div>
        <# } #>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
            var i               = 1;
            var items           = ( settings.items.size != '' ) ? settings.items.size : '3';
            var items_tablet    = ( settings.items_tablet.size != '' ) ? settings.items_tablet.size : '2';
            var items_mobile    = ( settings.items_mobile.size != '' ) ? settings.items_mobile.size : '1';
            var margin          = ( settings.margin.size != '' ) ? settings.margin.size : '';
            var margin_tablet   = ( settings.margin_tablet.size != '' ) ? settings.margin_tablet.size : '';
            var margin_mobile   = ( settings.margin_mobile.size != '' ) ? settings.margin_mobile.size : '';
            var loop            = ( settings.infinite_loop == 'yes' ) ? '1' : '';
            var grab_cursor     = ( settings.grab_cursor == 'yes' ) ? settings.grab_cursor : '';
            var arrows          = ( settings.arrows == 'yes' ) ? settings.arrows : '';
            var dots            = ( settings.dots == 'yes' ) ? settings.dots : '';
        #>
        <div class="swiper-container-wrap pp-team-member-carousel-wrap swiper-container-wrap-dots-{{ settings.dots_position }}">
            <div class="swiper-container pp-tm-wrapper pp-tm-carousel" data-items="{{ items }}" data-items-tablet="{{ items_tablet }}" data-items-mobile="{{ items_mobile }}" data-margin="{{ margin }}" data-margin-tablet="{{ margin_tablet }}" data-margin-mobile="{{ margin_mobile }}" data-grab-cursor="{{ grab_cursor }}" data-loop="{{ loop }}" data-arrows="{{ arrows }}" data-dots="{{ dots }}">
                <div class="swiper-wrapper">
                    <# _.each( settings.team_member_details, function( item ) { #>
                        <div class="swiper-slide">
                            <div class="pp-tm">
                                <div class="pp-tm-image">
                                    <# if ( item.team_member_image.url != '' ) { #>
                                        <img src="{{ item.team_member_image.url }}">
                                    <# } #>

                                    <# if ( settings.overlay_content == 'social_icons' ) { #>
                                        <div class="pp-tm-overlay-content-wrap">
                                            <div class="pp-tm-content">
                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <?php $this->_member_social_links_template(); ?>
                                                <# } #>
                                            </div>
                                        </div>
                                    <# } #>

                                    <# if ( settings.overlay_content == 'all_content' ) { #>
                                        <div class="pp-tm-overlay-content-wrap">
                                            <div class="pp-tm-content">
                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <# if ( settings.social_links_position == 'before_desc' ) { #>
                                                        <?php $this->_member_social_links_template(); ?>
                                                    <# } #>
                                                <# } #>

                                                <?php $this->_description_template(); ?>

                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <# if ( settings.social_links_position == 'after_desc' ) { #>
                                                        <?php $this->_member_social_links_template(); ?>
                                                    <# } #>
                                                <# } #>
                                            </div>
                                        </div>
                                    <# } #>
                                </div>
                                <# if ( settings.overlay_content == 'all_content' ) { #>
                                    <div class="pp-tm-content pp-tm-content-normal">
                                        <?php
                                            // Name
                                            $this->_name_template();

                                            // Position
                                            $this->_position_template();
                                        ?>
                                    </div>
                                <# } #>
                                <# if ( settings.overlay_content != 'all_content' ) { #>
                                    <div class="pp-tm-content pp-tm-content-normal">
                                        <?php $this->_name_template(); ?>
                                        <?php $this->_position_template(); ?>
                                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                                            <# if ( settings.social_links_position == 'before_desc' ) { #>
                                                <?php $this->_member_social_links_template(); ?>
                                            <# } #>
                                        <# } #>

                                        <?php $this->_description_template(); ?>

                                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                                            <# if ( settings.social_links_position == 'after_desc' ) { #>
                                                <?php $this->_member_social_links_template(); ?>
                                            <# } #>
                                        <# } #>
                                    </div><!-- .pp-tm-content -->
                                <# } #>
                            </div><!-- .pp-tm -->
                        </div><!-- .swiper-slide -->
                    <# i++ } ); #>
                </div>
            </div>
            <?php
                $this->_dots_template();

                $this->_arrows_template();
            ?>
        </div>    
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new PP_Team_Member_Carousel_Widget() );