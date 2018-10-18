<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Dual_Header extends Widget_Base {
    protected $templateInstance;

    public function getTemplateInstance(){
        return $this->templateInstance = premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-addon-dual-header';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Dual Heading';
	}

    
    public function get_icon() {
        return 'pa-dual-header';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium dual header
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /*Start General Section*/
        $this->start_controls_section('premium_dual_header_general_settings',
                [
                    'label'         => esc_html__('Dual Heading', 'premium-addons-for-elementor')
                    ]
                );
        
        /*First Header*/
        $this->add_control('premium_dual_header_first_header_text',
                [
                    'label'         => esc_html__('First Heading', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Premium', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /* First Wrapper*/
        /*$this->add_control('premium_dual_header_first_wrap',
                [
                    'label'         => esc_html__('Wrapper Tag', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'div',
                    'options'       => [
                        'div'   => 'div',
                        'span'  => 'span',
                        ],
                    'label_block'   =>  true,
                    ]
                );*/
        
        /*Second Header*/
        $this->add_control('premium_dual_header_second_header_text',
                [
                    'label'         => esc_html__('Second Heading', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Addons', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
         /*Title Tag*/
        $this->add_control('premium_dual_header_first_header_tag',
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
                        'p'     => 'p',
                        'span'  => 'span',
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Text Align*/
        $this->add_control('premium_dual_header_position',
                [
                    'label'         => esc_html__( 'Display', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'inline'=> esc_html__('Inline', 'premium-addons-for-elementor'),
                        'block' => esc_html__('Block', 'premium-addons-for-elementor'),
                        ],
                    'default'       => 'inline',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-container span, {{WRAPPER}} .premium-dual-header-second-container' => 'display: {{VALUE}};',
                        ],
                    'label_block'   => true
                    ]
                );
        
        $this->add_control('premium_dual_header_link_switcher',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'description'   => esc_html__('Enable or disable link','premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_dual_heading_link_selection', 
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
                        'premium_dual_header_link_switcher'     => 'yes',
                        ]
                    ]
                );
        
        $this->add_control('premium_dual_heading_link',
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
                        'premium_dual_header_link_switcher'     => 'yes',
                        'premium_dual_heading_link_selection'   => 'url'
                        ]
                    ]
                );
        
        $this->add_control('premium_dual_heading_existing_link',
                [
                    'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT2,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                        'premium_dual_header_link_switcher'         => 'yes',
                        'premium_dual_heading_link_selection'       => 'link',
                    ],
                    'multiple'      => false,
                    'separator'     => 'after',
                    'label_block'   => true,
                    ]
                );
        
        /*Text Align*/
        $this->add_responsive_control('premium_dual_header_text_align',
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
                        '{{WRAPPER}} .premium-dual-header-container' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        /*End General Settings Section*/
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_first_style',
                [
                    'label'         => esc_html__('First Heading', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*First Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'first_header_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-dual-header-first-span',
                    ]
                );
        
        $this->add_control('premium_dual_header_first_animated',
                [
                    'label'         => esc_html__('Animated Background', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    ]
                );
        
        /*First Coloring Style*/
        $this->add_control('premium_dual_header_first_back_clip',
                [
                    'label'         => esc_html__('Background Style', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'color',
                    'description'   => esc_html__('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                    'options'       => [
                        'color'         => esc_html__('Normal Background', 'premium-addons-for-elementor'),
                        'clipped'       => esc_html__('Clipped Background', 'premium-addons-for-elementor'),
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*First Color*/
        $this->add_control('premium_dual_header_first_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'condition'     => [
                      'premium_dual_header_first_back_clip' => 'color',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-span'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*First Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_first_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_first_back_clip'  => 'color',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-span',
                    ]
                );
        
        /*First Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_first_clipped_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_first_back_clip'  => 'clipped',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-span',
                    ]
                );
        
        /*First Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'first_header_border',
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-span',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_dual_header_first_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-span' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*First Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_dual_header_first_text_shadow',
                'selector'          => '{{WRAPPER}} .premium-dual-header-first-span',
            ]
            );
        
        /*First Margin*/
        $this->add_responsive_control('premium_dual_header_first_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_dual_header_first_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End First Header Styling Section*/
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_second_style',
                [
                    'label'         => esc_html__('Second Heading', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Second Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'second_header_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        $this->add_control('premium_dual_header_second_animated',
                [
                    'label'         => esc_html__('Animated Background', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    ]
                );
        
        /*Second Coloring Style*/
        $this->add_control('premium_dual_header_second_back_clip',
                [
                    'label'         => esc_html__('Background Style', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'color',
                    'description'   => esc_html__('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                    'options'       => [
                        'color'         => esc_html__('Normal Background', 'premium-addons-for-elementor'),
                        'clipped'       => esc_html__('Clipped Background', 'premium-addons-for-elementor'),
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Second Color*/
        $this->add_control('premium_dual_header_second_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'condition'     => [
                      'premium_dual_header_second_back_clip' => 'color',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Second Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_second_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_second_back_clip'  => 'color',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        /*Second Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_second_clipped_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_second_back_clip'  => 'clipped',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        /*Second Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'second_header_border',
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                ]
                );
        
        /*Second Border Radius*/
        $this->add_control('premium_dual_header_second_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Second Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_dual_header_second_text_shadow',
                'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
            ]
            );
        
        /*Second Margin*/
        $this->add_responsive_control('premium_dual_header_second_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*Second Padding*/
        $this->add_responsive_control('premium_dual_header_second_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End Second Header Styling Section*/
        $this->end_controls_section();
       
    }

    protected function render() {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes('premium_dual_header_first_header_text');

        $this->add_inline_editing_attributes('premium_dual_header_second_header_text');

        $first_title_tag = $settings['premium_dual_header_first_header_tag'];

        $first_title_text = $settings['premium_dual_header_first_header_text'] . ' ';

        $second_title_text = $settings['premium_dual_header_second_header_text'];

        $first_clip = '';

        $second_clip = '';

        if( $settings['premium_dual_header_first_back_clip'] === 'clipped' ) : $first_clip = "premium-dual-header-first-clip"; endif; 

        if( $settings['premium_dual_header_second_back_clip'] === 'clipped' ) : $second_clip = "premium-dual-header-second-clip"; endif; 
        
        $first_grad = $settings['premium_dual_header_first_animated'] === 'yes' ? ' gradient' : '';
        
        $second_grad = $settings['premium_dual_header_second_animated'] === 'yes' ? ' gradient' : '';
        
        $full_first_title_tag = '<' . $first_title_tag . ' class="premium-dual-header-first-header ' . $first_clip . $first_grad . '"><span class="premium-dual-header-first-span">'. $first_title_text . '</span><span class="premium-dual-header-second-header ' . $second_clip . $second_grad . '">'. $second_title_text . '</span></' . $settings['premium_dual_header_first_header_tag'] . '> ';
        
        if( $settings['premium_dual_header_link_switcher'] =='yes' && $settings['premium_dual_heading_link_selection'] == 'link' ) {
            $link = get_permalink($settings['premium_dual_heading_existing_link']);
        } elseif( $settings['premium_dual_header_link_switcher'] =='yes' && $settings['premium_dual_heading_link_selection'] == 'url' ){
            $link = $settings['premium_dual_heading_link']['url'];
        }
?>
    
<div class="premium-dual-header-container">
    <?php if( $settings['premium_dual_header_link_switcher'] == 'yes' && ( !empty( $settings['premium_dual_heading_link']['url'] ) || !empty( $settings['premium_dual_heading_existing_link'] ) ) ) : ?>
    <a <?php if( !empty( $link ) ) : ?> href="<?php echo esc_attr( $link ); ?>" <?php endif; ?> <?php if(!empty($settings['premium_dual_heading_link']['is_external'])) : ?> target="_blank" <?php endif; ?><?php if(!empty($settings['premium_dual_heading_link']['nofollow'])) : ?> rel="nofollow" <?php endif; ?>>
        <?php endif; ?>
        <div class="premium-dual-header-first-container"><?php echo $full_first_title_tag; ?></div>
    <?php if( $settings['premium_dual_header_link_switcher'] == 'yes' && ( !empty( $settings['premium_dual_heading_link']['url'] ) || !empty( $settings['premium_dual_heading_existing_link'] ) ) ) : ?>
    </a>
    <?php endif; ?>
</div>

    <?php
    }
    
    protected function _content_template()
    {
        ?>
        <#
        
            view.addInlineEditingAttributes('premium_dual_header_first_header_text');

            view.addInlineEditingAttributes('premium_dual_header_second_header_text');

            var firstTag = settings.premium_dual_header_first_header_tag,

            firstText = settings.premium_dual_header_first_header_text + ' ',

            secondText = settings.premium_dual_header_second_header_text,

            firstClip = '',

            secondClip = '';

            if( 'clipped' === settings.premium_dual_header_first_back_clip )
                firstClip = "premium-dual-header-first-clip"; 

            if( 'clipped' === settings.premium_dual_header_second_back_clip )
                secondClip = "premium-dual-header-second-clip";

            var firstGrad = 'yes' === settings.premium_dual_header_first_animated  ? ' gradient' : '',

                secondGrad = 'yes' === settings.premium_dual_header_second_animated ? ' gradient' : '';
            
                view.addRenderAttribute('first_title', 'class', ['premium-dual-header-first-header', firstClip, firstGrad ] );
                view.addRenderAttribute('second_title', 'class', ['premium-dual-header-second-header', secondClip, secondGrad ] );
        
            if( 'yes' == settings.premium_dual_header_link_switcher && 'link' == settings.premium_dual_heading_link_selection ) {
                var link = settings.premium_dual_heading_existing_link;
            } else if( 'yes' == settings.premium_dual_header_link_switcher && 'url' == settings.premium_dual_heading_link_selection ){
                var link = settings.premium_dual_heading_link.url;
            }
        
        #>
        
        <div class="premium-dual-header-container">
            <# if( 'yes' == settings.premium_dual_header_link_switcher && ( '' != settings.premium_dual_heading_link.url || '' != settings.premium_dual_heading_existing_link ) ) { #>
                <a href="{{ link }}">
            <# } #>
            <div class="premium-dual-header-first-container">
                <{{{firstTag}}} {{{ view.getRenderAttributeString('first_title') }}}>
                <span class="premium-dual-header-first-span">{{{ firstText }}}</span><span {{{ view.getRenderAttributeString('second_title') }}}>{{{ secondText }}}</span>
                </{{{firstTag}}}>
                
            </div>
            <# if( 'yes' == settings.premium_dual_header_link_switcher && ( '' != settings.premium_dual_heading_link.url || '' != settings.premium_dual_heading_existing_link ) ) { #>
                </a>
            <# } #>
        </div>
        
        <?php
    }
}