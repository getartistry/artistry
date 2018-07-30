<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Video_Box_Widget extends Widget_Base
{
    public function get_name() {
        return 'premium-addon-video-box';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Video Box';
	}

    public function get_icon() {
        return 'pa-video-box';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_script_depends() {
        return [ 'premium-addons-js' ];
    }

    // Adding the controls fields for the premium video box
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /* Start Image Settings Section */
        $this->start_controls_section('premium_video_box_image_settings',
                [
                    'label'         => esc_html__('Video Box', 'premium-addons-for-elementor'),
                ]
                );
        
        /*Video Box Image*/ 
        $this->add_control('premium_video_box_image',
                [
                    'label'         => esc_html__('Image', 'premium-addons-for-elementor'),
                    'description'   => esc_html__('Choose an image for the video box', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::MEDIA,
                    'default'       => [
                        'url'	=> Utils::get_placeholder_image_src()
                ],
                    'label_block'   => true,
                ]
                );
        
        /*Video Type*/
        $this->add_control('premium_video_box_video_type',
                [
                    'label'         => esc_html__('Video Type', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'youtube',
                    'options'       => [
                        'youtube'       => esc_html__('Youtube', 'premium-addons-for-elementor'),
                        'vimeo'         => esc_html__('Vimeo', 'premium-addons-for-elementor'),
                        ]
                    ]
                );
        
        /*Video Id or Link*/
        $this->add_control('premium_video_box_video_id_embed_selection',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'id',
                    'options'       => [
                        'id'    => esc_html__('ID', 'premium-addons-for-elementor'),
                        'embed' => esc_html__('Embed URL', 'premium-addons-for-elementor'),
                        ]
                    ]
                );
        
        /*Video Id*/
        $this->add_control('premium_video_box_video_id', 
                [
                    'label'         => esc_html__('Video ID', 'premium-addons-for-elementor'),
                    'description'   => esc_html__('Enter the numbers and letters after the equal sign which located in your YouTube video link or after the slash sign in your Vimeo video link. For example, z1hQgVpfTKU', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'condition'     => [
                        'premium_video_box_video_id_embed_selection' => 'id',
                        ]
                    ]
                );
        
        /*Video Link*/
        $this->add_control('premium_video_box_video_embed', 
                [
                    'label'         => esc_html__('Embed URL', 'premium-addons-for-elementor'),
                    'description'   => esc_html__('Enter your YouTube/Vimeo video link. For example, https://www.youtube.com/embed/z1hQgVpfTKU.', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'condition'     => [
                        'premium_video_box_video_id_embed_selection' => 'embed',
                        ]
                    ]
                );
        
        /*End Image Settings Section*/
        $this->end_controls_section();
        
        /*Start Play Icon Settings*/
        $this->start_controls_section('premium_video_box_play_icon_settings', 
                [
                    'label'         => esc_html__('Additional Options', 'premium-addons-for-elementor'),
                ]
                );
        
        
        /*Play Icon Switcher*/
        $this->add_control('premium_video_box_play_icon_switcher',
                [
                    'label'         => esc_html__('Play Icon', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_control('premium_video_box_icon_ver_position', 
                [
                    'label'         => esc_html__('Vertical Position (%)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'label_block'   => true,
                    'default'       => [
                        'size' => 50,
                        ],
                    'condition'     => [
                        'premium_video_box_play_icon_switcher'  => 'yes',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon-container' => 'top: {{SIZE}}%;',
                        ]
                    ]
                );
        
        $this->add_control('premium_video_box_icon_hor_position', 
                [
                    'label'         => esc_html__('Horizontal Position (%)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'label_block'   => true,
                    'default'       => [
                        'size' => 50,
                        ],
                    'condition'     => [
                        'premium_video_box_play_icon_switcher'  => 'yes',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon-container' => 'left: {{SIZE}}%;',
                        ]
                    ]
                );
        
        /*End Play Icon Settings*/
        $this->end_controls_section();
        
        /*Start Description Text Section*/
        $this->start_controls_section('premium_video_box_description_text_section', 
                [
                    'label'         => esc_html__('Video Text', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_video_box_video_text_switcher',
                [
                    'label'         => esc_html__('Video Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                ]
            );
        
        /*Description Text*/
        $this->add_control('premium_video_box_description_text', 
                [
                    'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXTAREA,
                    'default'       => esc_html__('Play Video','premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_video_box_video_text_switcher' => 'yes'
                    ],
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                ]
                );
        
        /*Description Top Position*/
        $this->add_control('premium_video_box_description_ver_position', 
                [
                    'label'         => esc_html__('Vertical Position (%)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'label_block'   => true,
                    'default'       => [
                        'size' => 60,
                        ],
                    'condition'     => [
                        'premium_video_box_video_text_switcher' => 'yes'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-description-container' => 'top: {{SIZE}}%;',
                        ]
                    ]
                );
        
         $this->add_control('premium_video_box_description_hor_position', 
                [
                    'label'         => esc_html__('Horizontal Position (%)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'label_block'   => true,
                    'default'       => [
                        'size' => 50,
                        ],
                    'condition'     => [
                        'premium_video_box_video_text_switcher' => 'yes'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-description-container' => 'left: {{SIZE}}%;',
                        ]
                    ]
                );
        
        /*End Description Text Section*/
        $this->end_controls_section();
        
        /*Start Text Below Icon Style Section*/
        $this->start_controls_section('premium_video_box_text_style_section', 
                [
                    'label'         => esc_html__('Video Box','premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Image Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'          => 'image_border',        
                    'selector'      => '{{WRAPPER}} .premium-video-box-image, {{WRAPPER}} .premium-video-box-video-container',
                ]
                );
        
        /*Image Border Radius*/
        $this->add_responsive_control('premium_video_box_image_border_radius', 
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-image, {{WRAPPER}} .premium-video-box-video-container'  => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Box Text Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-video-box-image, {{WRAPPER}} .premium-video-box-video-container iframe',
                ]
                );
        
        /*End Text Below Icon Style Section*/
        $this->end_controls_section();
        
        /*Start Play Icon Style Section*/
        $this->start_controls_section('premium_video_box_icon_style', 
                [
                    'label'         => esc_html__('Play Icon','premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_video_box_play_icon_switcher'  => 'yes',
                    ],
                ]
                );
        
        /*Play Icon Color*/
        $this->add_control('premium_video_box_play_icon_color', 
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Play Icon Color*/
        $this->add_control('premium_video_box_play_icon_color_hover', 
                [
                    'label'         => esc_html__('Hover Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon-container:hover .premium-video-box-play-icon'  => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Play Icon Size*/
        $this->add_control('premium_video_box_play_icon_size',
                [
                    'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 30,
                    ],
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Play Icon Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_video_box_play_icon_background_color',
                    'types'             => ['classic', 'gradient'],
                    'selector'          => '{{WRAPPER}} .premium-video-box-play-icon-container',
                ]
                );
        
        /*Play Icon Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'          => 'icon_border',   
                    'selector'      => '{{WRAPPER}} .premium-video-box-play-icon-container',
                ]
                );
        
        /*Play Icon Border Radius*/
        $this->add_control('premium_video_box_icon_border_radius', 
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 100,
                    ],
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon-container'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Icon Padding*/
        $this->add_responsive_control('premium_video_box_icon_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'default'       => [
                        'top'   => 40,
                        'right' => 40,
                        'bottom'=> 40,
                        'left'  => 40,
                        'unit'  => 'px'
                    ],
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*Enable Icon Hover Size Change*/
        $this->add_control('premium_video_box_icon_hover_animation',
                [
                    'label'         => esc_html__('Hover Animation', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'description'   => esc_html__('Hover animation works only when you set a background color or image for play icon', 'premium-addons-for-elementor'),
                    'default'       => 'yes',
                ]
                );
        
        /*Icon Padding*/
        $this->add_responsive_control('premium_video_box_icon_padding_hover',
                [
                    'label'         => esc_html__('Hover Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-play-icon:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ],
                    'condition'     => [
                        'premium_video_box_icon_hover_animation'    => 'yes',
                        ],
                    ]
                );
        
        
        /*End Play Icon style Section*/
        $this->end_controls_section();
       
        
        /*Start Video Text Style*/
        $this->start_controls_section('premium_video_box_text_style', 
                [
                    'label'         => esc_html__('Video Text', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_video_box_video_text_switcher' => 'yes'
                    ]
                ]
                );
        
        /*Text Color*/
        $this->add_control('premium_video_box_text_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-text'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Text Hover Color*/
        $this->add_control('premium_video_box_text_color_hover',
                [
                    'label'         => esc_html__('Hover Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-description-container:hover .premium-video-box-text'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Text Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'text_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-video-box-text',
                    ]
                );
        
        /*Text Hover Color*/
        $this->add_control('premium_video_box_text_background_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-description-container'   => 'background-color: {{VALUE}};',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_video_box_text_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-video-box-description-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ],
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_text_shadow',
                'selector'      => '.premium-video-box-text'
            ]
            );
        
        /*End Video Text Style*/
        $this->end_controls_section();
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes('premium_video_box_description_text');
        
        $video_type = $settings['premium_video_box_video_type'];
        
        $video_url_type = $settings['premium_video_box_video_id_embed_selection'];
        
        $video_id = $settings['premium_video_box_video_id'];
        
        $video_embed = $settings['premium_video_box_video_embed'];
?>

<div class="premium-video-box-container" id="premium-video-box-container-<?php echo esc_attr( $this->get_id() ); ?>">
    <div class="premium-video-box-image-container">
        <img class="premium-video-box-image" src="<?php echo $settings['premium_video_box_image']['url']; ?>">
    </div>
    <div class="premium-video-box-play-icon-container">
        <?php if($settings['premium_video_box_play_icon_switcher'] == 'yes') : ?>
        <i class="premium-video-box-play-icon fa fa-play fa-lg"></i>
        <?php endif; ?>
    </div>
    <?php if( $settings['premium_video_box_video_text_switcher'] == 'yes' && !empty( $settings['premium_video_box_description_text'] ) ) : ?>
    <div class="premium-video-box-description-container">
        <p class="premium-video-box-text"><span <?php echo $this->get_render_attribute_string('premium_video_box_description_text'); ?>><?php echo $settings['premium_video_box_description_text']; ?></span></p>
    </div>
    <?php endif; ?>
    <div class="premium-video-box-video-container">
        <?php if ( $video_type  === 'youtube'){ ?>
        <?php if ( $video_url_type === 'id' && !empty( $video_id ) ) : ?>
            <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen>
            </iframe>
        <?php elseif ( $video_url_type === 'embed' && !empty( $video_embed ) ) : ?>
            <iframe src="<?php echo $video_embed; ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen>
            </iframe>
        <?php endif; ?>
        <?php } elseif ( $video_type  === 'vimeo'){ ?>
        <?php if ( $video_url_type === 'id' && !empty( $video_id ) ) : ?>
            <iframe src="https://player.vimeo.com/video/<?php echo $video_id; ?>"  frameborder="0" title="Medicine" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" class="fluidvids-item" data-fluidvids="loaded">
            </iframe>
        <?php elseif ( $video_url_type  === 'embed' && !empty( $video_embed ) ) : ?>
            <iframe src="<?php echo $video_embed; ?>?byline=0&portrait=0" frameborder="0" top="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen>
            </iframe>
        <?php endif; ?>
        <?php } ?>
        
    </div>
</div>

    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Video_Box_Widget());