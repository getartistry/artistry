<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Person_Widget extends Widget_Base
{
    public function get_name() {
        return 'premium-addon-person';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Person';
	}

    public function get_icon() {
        return 'pa-person';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium person
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /*Start Premium Person Section*/
        $this->start_controls_section('premium_person_general_settings',
                [
                    'label'         => esc_html__('Image', 'premium-addons-for-elementor')
                    ]
                );
        
        /*Person Image*/ 
        $this->add_control('premium_person_image',
                [
                    'label'         => esc_html__('Image', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::MEDIA,
                    'default'       => [
                        'url'	=> Utils::get_placeholder_image_src()
                ],
                    'label_block'   => true
                ]
                );
        
        $this->add_responsive_control('premium_person_image_width',
                [
                    'label'         => esc_html__('Width', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'description'   => esc_html__('Enter image width in (PX, EM, %), default is 100%', 'premium-addons-for-elementor'),
                    'size_units'    => ['px', '%', 'em'],
                    'range'         => [
                        'px'    => [
                            'min'       => 1,
                            'max'       => 800,
                        ],
                        'em'    => [
                            'min'       => 1,
                            'max'       => 50,
                        ],
                    ],
                    'default'       => [
                        'unit'  => '%',
                        'size'  => '100',
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-image-container img' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_person_image_height',
                [
                    'label'         => esc_html__('Height', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'description'   => esc_html__('Choose image height in (PX, EM)', 'premium-addons-for-elementor'),
                    'range'         => [
                        'px'    => [
                            'min'       => 1,
                            'max'       => 900,
                        ],
                        'em'    => [
                            'min'       => 1,
                            'max'       => 55,
                        ],
                    ],
                    'size_units'    => ['px', "em"],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-image-container img' => 'height: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Hover Image Effect*/ 
        $this->add_control('premium_person_hover_image_effect',
                [
                    'label'         => esc_html__('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'none'  => esc_html__('None', 'premium-addons-for-elementor'),
                        'zoomin' => esc_html__('Zoom In', 'premium-addons-for-elementor'),
                        'zoomout'=> esc_html__('Zoom Out', 'premium-addons-for-elementor'),
                        'scale'  => esc_html__('Scale', 'premium-addons-for-elementor'),
                        'grayscale'=> esc_html__('Grayscale', 'premium-addons-for-elementor'),
                        'blur'   => esc_html__('Blur', 'premium-addons-for-elementor'),
                        'bright'        => esc_html__('Bright', 'premium-addons-for-elementor'),
                        'sepia'         => esc_html__('Sepia', 'premium-addons-for-elementor'),
                        'trans'         => esc_html__('Translate', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'zoomin',
                    'label_block'   => true
                ]
                );
        
        /*End Premium Person Section*/
        $this->end_controls_section();
        
        /*Start Person Details Section*/
        $this->start_controls_section('premium_person_person_details_section',
                [
                    'label'         => esc_html__('Person', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Person Name*/
        $this->add_control('premium_person_name',
                [
                    'label'         => esc_html__('Name', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('John Frank', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /*Name Tag*/
        $this->add_control('premium_person_name_heading',
                [
                    'label'         => esc_html__('HTML Tag', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'h2',
                    'options'       => [
                        'h1'    => 'H1',
                        'h2'    => 'H2',
                        'h3'    => 'H3',
                        'h4'    => 'H4',
                        'h5'    => 'H5',
                        'h6'    => 'H6',
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Person Title*/
        $this->add_control('premium_person_title',
                [
                    'label'         => esc_html__('Job Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Senior Developer', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /*Title Tag*/
        $this->add_control('premium_person_title_heading',
                [
                    'label'         => esc_html__('HTML Tag', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'h4',
                    'options'       => [
                        'h1' => esc_html__('H1'),
                        'h2' => esc_html__('H2'),
                        'h3' => esc_html__('H3'),
                        'h4' => esc_html__('H4'),
                        'h5' => esc_html__('H5'),
                        'h6' => esc_html__('H6')
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        $this->add_control('premium_person_content',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::WYSIWYG,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ullamcorper nulla non metus auctor fringilla','premium-addons-for-elementor'),
                ]
                );
        
        /*Text Align*/
        $this->add_responsive_control('premium_person_text_align',
                [
                    'label'         => esc_html__( 'Alignment', 'premium-addons-for-elementor' ),
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
                        '{{WRAPPER}} .premium-person-info' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        /*End Person Details Section*/
        $this->end_controls_section();
        
        /*Start Social Links Section*/
        $this->start_controls_section('premium_person_social_section',
                [
                    'label'         => esc_html__('Social Icons', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Person Facebook*/
        $this->add_control('premium_person_facebook',
                [
                    'label'         => esc_html__('Facebook', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Twitter*/
        $this->add_control('premium_person_twitter',
                [
                    'label'         => esc_html__('Twitter', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Linkedin*/
        $this->add_control('premium_person_linkedin',
                [
                    'label'         => esc_html__('LinkedIn', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Google*/
        $this->add_control('premium_person_google',
                [
                    'label'         => esc_html__('Google+', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Pinterest*/
        $this->add_control('premium_person_pinterest',
                [
                    'label'         => esc_html__('Pinterest', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Dribble*/
        $this->add_control('premium_person_dribbble',
                [
                    'label'         => esc_html__('Dribbble', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Dribble*/
        $this->add_control('premium_person_behance',
                [
                    'label'         => esc_html__('Behance', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*Person Google*/
        $this->add_control('premium_person_mail',
                [
                    'label'         => esc_html__('Email Address', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '#',
                    'label_block'   => true,
                    ]
                );
        
        /*End Social Links Section*/
        $this->end_controls_section();
        
        /*Start Name Style Section*/
         $this->start_controls_section('premium_person_name_style', 
                [
                    'label'         => esc_html__('Name', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
         
         
        /*Name Color*/
        $this->add_control('premium_person_name_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-name'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
         
        /*Name Typography*/ 
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'name_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-person-name',
                ]
                );
        
        /*End Name Style Section*/
        $this->end_controls_section();
        
        /*Start Title Style Section*/
        $this->start_controls_section('premium_person_title_style', 
                [
                    'label'         => esc_html__('Job Title', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Title Color*/
        $this->add_control('premium_person_title_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-title'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Title Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'title_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-person-title',
                ]
                );
        
        /*End Title Style Section*/
        $this->end_controls_section();
        
        /*Start Description Style Section*/
        $this->start_controls_section('premium_person_description_style', 
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Title Color*/
        $this->add_control('premium_person_description_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-content'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Title Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'description_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-person-content',
                ]
                );
        
        /*End Description Style Section*/
        $this->end_controls_section();
        
        /*Start Social Icon Style Section*/
        $this->start_controls_section('premium_person_social_icon_style', 
                [
                    'label'         => esc_html__('Social Icons', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Social Color*/
        $this->add_control('premium_person_social_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-social-list .premium-person-list-item i'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Social Hover Color*/
        $this->add_control('premium_person_social_hover_color',
                [
                    'label'         => esc_html__('Hover Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-social-list .premium-person-list-item:hover i'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*End Description Style Section*/
        $this->end_controls_section();
        
        /*Start Content Style Section*/
        $this->start_controls_section('premium_person_general_style', 
                [
                    'label'         => esc_html__('Content Background', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Content Background Color*/
        $this->add_control('premium_person_content_background_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'default'       => 'rgba(245,245,245,0.97)',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-info'  => 'background-color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Border Bottom Width*/
        $this->add_control('premium_person_border_bottom_width',
                [
                    'label'         => esc_html__('Height', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px'    => [
                            'min'   => 0,
                            'max'   => 700,
                        ]
                    ],
                    'default'       => [
                        'size'    => 20,
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-person-info' => 'bottom: {{SIZE}}px;',
                        ]
                    ]
                );
        
        /*End Content Style Section*/
        $this->end_controls_section();
        
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes('premium_person_name');
        
        $this->add_inline_editing_attributes('premium_person_title');
        
        $this->add_inline_editing_attributes('premium_person_content','advanced');
        
        $name_heading = $settings['premium_person_name_heading'];
        
        $title_heading = $settings['premium_person_title_heading'];
        
        $image_effect = $settings['premium_person_hover_image_effect'];
?>

<div class="premium-person-container <?php echo 'premium-person-' . $image_effect  . '-effect' ?>">
    <div class="premium-person-image-container">
        <img src="<?php echo $settings['premium_person_image']['url']; ?>" alt="<?php echo $settings['premium_person_name']; ?>">
    </div>
    <div class="premium-person-info">
        <div class="premium-person-info-container">
            <?php if( !empty( $settings['premium_person_name'] ) ) : ?><<?php echo $name_heading; ?> class="premium-person-name"><span <?php echo $this->get_render_attribute_string('premium_person_name'); ?>><?php echo $settings['premium_person_name']; ?></span></<?php echo $name_heading; ?>><?php endif; ?>
            <?php if( !empty( $settings['premium_person_title'] ) ) : ?><<?php echo $title_heading; ?> class="premium-person-title"><span <?php echo $this->get_render_attribute_string('premium_person_title'); ?>><?php echo $settings['premium_person_title']; ?></span></<?php echo $title_heading; ?>><?php endif; ?>
            <div class="premium-person-content">
                <div <?php echo $this->get_render_attribute_string('premium_person_content'); ?>>
                    <?php echo $settings['premium_person_content']; ?>
                </div>
            </div>
            <ul class="premium-person-social-list">
                <?php if( !empty( $settings['premium_person_facebook'] ) ) : ?><li class="premium-person-list-item premium-person-facebook"><a href="<?php echo $settings['premium_person_facebook']; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_twitter'] ) ) : ?><li class="premium-person-list-item premium-person-twitter"><a href="<?php echo $settings['premium_person_twitter']; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_linkedin'] ) ) : ?><li class="premium-person-list-item premium-person-linkedin"><a href="<?php echo $settings['premium_person_linkedin']; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_google'] ) ) : ?><li class="premium-person-list-item premium-person-google"><a href="<?php echo $settings['premium_person_google']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_pinterest'] ) ) : ?><li class="premium-person-list-item premium-person-pinterest"><a href="<?php echo $settings['premium_person_pinterest']; ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_dribbble'] ) ) : ?><li class="premium-person-list-item premium-person-dribbble"><a href="<?php echo $settings['premium_person_dribbble']; ?>" target="_blank"><i class="fa fa-dribbble"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_behance'] ) ) : ?><li class="premium-person-list-item premium-person-behance"><a href="<?php echo $settings['premium_person_behance']; ?>" target="_blank"><i class="fa fa-behance"></i></a></li><?php endif; ?>
                <?php if( !empty( $settings['premium_person_mail'] ) ) : ?><li class="premium-person-list-item premium-person-mail"><a href="<?php echo $settings['premium_person_mail']; ?>" target="_blank"><i class="fa fa-envelope"></i></a></li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>
    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Person_Widget());