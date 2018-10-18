<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Fancytext extends Widget_Base {
    public function get_name() {
        return 'premium-addon-fancy-text';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Fancy Text';
	}

    public function get_icon() {
        return 'pa-fancy-text';
    }
    
    public function get_script_depends()
    {
        return ['premium-addons-js','typed-js','vticker-js'];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium fancy text
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /*Start Text Content Section*/
        $this->start_controls_section('premium_fancy_text_content',
                [
                    'label'         => esc_html__('Fancy Text', 'premium-addons-for-elementor'),
                    ]
                );
        
        /*Prefix Text*/ 
        $this->add_control('premium_fancy_prefix_text',
                [
                    'label'         => esc_html__('Prefix', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('This is', 'premium-addons-for-elementor'),
                    'description'   => esc_html__( 'Text before Fancy text', 'premium-addons-for-elementor' ),
                    'label_block'   => true,
                ]
                );
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_text_strings_text_field',
            [
                'label'       => esc_html__( 'Fancy String', 'premium-addons-for-elementor' ),
                'dynamic'     => [ 'active' => true ],
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        
        /*Fancy Text Strings*/
        $this->add_control('premium_fancy_text_strings',
                [
                    'label'         => esc_html__( 'Fancy Text', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::REPEATER,
                    'default'       => [
                        [
                            'premium_text_strings_text_field' => esc_html__( 'Designer', 'premium-addons-for-elementor' ),
                            ],
                        [
                            'premium_text_strings_text_field' => esc_html__( 'Developer', 'premium-addons-for-elementor' ),
                            ],
                        [
                            'premium_text_strings_text_field' => esc_html__( 'Awesome', 'premium-addons-for-elementor' ),
                            ],
                        ],
                    'fields'        => array_values( $repeater->get_controls() ),
                    'title_field'   => '{{{ premium_text_strings_text_field }}}',
                    ]
                );

		/*Prefix Text*/ 
        $this->add_control('premium_fancy_suffix_text',
                [
                    'label'         => esc_html__('Suffix', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => esc_html__('Text', 'premium-addons-for-elementor'),
                    'description'   => esc_html__( 'Text after Fancy text', 'premium-addons-for-elementor' ),
                    'label_block'   => true,
                ]
                );
        
        /*Front Text Align*/
        $this->add_responsive_control('premium_fancy_text_align',
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
                        '{{WRAPPER}} .premium-fancy-text-wrapper' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_fancy_additional_settings',
                [
                    'label'         => esc_html__('Additional Settings', 'premium-addons-for-elementor'),
                    ]
                );
        
        /*Text Effect*/
        $this->add_control('premium_fancy_text_effect', 
                [
                    'label'         => esc_html__('Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'typing'=> esc_html__('Typing'),
                        'slide' => esc_html__('Slide Up'),
                        ],
                    'default'       => 'typing',
                    'label_block'   => true,
                    ]
                );
        
        /*Type Speed*/
        $this->add_control('premium_fancy_text_type_speed',
                [
                    'label'         => esc_html__('Type Speed', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 30,
                    'description'   => esc_html__( 'Set typing effect speed in milliseconds.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Back Speed*/
        $this->add_control('premium_fancy_text_back_speed',
                [
                    'label'         => esc_html__('Back Speed', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 30,
                    'description'   => esc_html__( 'Set a speed for backspace effect in milliseconds.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Start Delay*/
        $this->add_control('premium_fancy_text_start_delay',
                [
                    'label'         => esc_html__('Start Delay', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 30,
                    'description'   => esc_html__( 'If you set it on 5000 milliseconds, the first word/string will appear after 5 seconds.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Back Delay*/
        $this->add_control('premium_fancy_text_back_delay',
                [
                    'label'         => esc_html__('Back Delay', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 30,
                    'description'   => esc_html__( 'If you set it on 5000 milliseconds, the word/string will remain visible for 5 seconds before backspace effect.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Type Loop*/
        $this->add_control('premium_fancy_text_type_loop',
                [
                    'label'         => esc_html__('Loop','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    ]
                );
        
        /*Show Cursor*/
        $this->add_control('premium_fancy_text_show_cursor',
                [
                    'label'         => esc_html__('Show Cursor','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    ]
                );
        
        /*Cursor Text*/
        $this->add_control('premium_fancy_text_cursor_text',
                [
                    'label'         => esc_html__('Cursor Mark', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => '|',
                    'condition'     => [
                        'premium_fancy_text_effect'     => 'typing',
                        'premium_fancy_text_show_cursor'=> 'yes',
                        ],
                    ]
                );
        
        /*Slide Up Speed*/
        $this->add_control('premium_slide_up_speed',
                [
                    'label'         => esc_html__('Animation Speed', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 200,
                    'description'   => esc_html__( 'Set a duration value in milliseconds for slide up effect.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Slide Up Pause Time*/
        $this->add_control('premium_slide_up_pause_time',
                [
                    'label'         => esc_html__('Pause Time (Milliseconds)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 3000,
                    'description'   => esc_html__( 'How long should the word/string stay visible? Set a value in milliseconds.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Slide Up Shown Items*/
        $this->add_control('premium_slide_up_shown_items',
                [
                    'label'         => esc_html__('Show Items', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 1,
                    'description'   => esc_html__( 'How many items should be visible at a time?', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                    'label_block'   => true,
                ]
                );
        
        /*Pause on Hover*/
        $this->add_control('premium_slide_up_hover_pause',
                [
                    'label'         => esc_html__('Pause on Hover','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'description'   => esc_html__( 'If you enabled this option, the slide will be paused when mouseover.', 'premium-addons-for-elementor' ),
                    'default'       => 'no',
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                    ]
                );
       
        $this->end_controls_section();
        
        /*Start Fancy Text Settings Tab*/
        $this->start_controls_section('premium_fancy_text_style_tab',
                [
                    'label'         => esc_html__('Fancy Text', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Fancy Text Color*/
        $this->add_control('premium_fancy_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-fancy-text' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Fancy Text Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'fancy_text_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-fancy-text',
                    ]
                );  
        
        /*Fancy Text Background Color*/
        $this->add_control('premium_fancy_text_background_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-fancy-text' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
      
        /*End Fancy Text Settings Tab*/
        $this->end_controls_section();

        /*Start Cursor Settings Tab*/
        $this->start_controls_section('premium_fancy_cursor_text_style_tab',
                [
                    'label'         => esc_html__('Cursor Text', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_fancy_text_cursor_text!'   => ''
                ]
            ]
        );
        
        /*Cursor Color*/
        $this->add_control('premium_fancy_text_cursor_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Cursor Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'fancy_text_cursor_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .typed-cursor',
                    ]
                );  
        
        /*Cursor Background Color*/
        $this->add_control('premium_fancy_text_cursor_background',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .typed-cursor' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
      
        /*End Fancy Text Settings Tab*/
        $this->end_controls_section();
        
        /*Start Prefix Suffix Text Settings Tab*/
        $this->start_controls_section('premium_prefix_suffix_style_tab',
                [
                    'label'         => esc_html__('Prefix & Suffix', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Prefix Suffix Text Color*/
        $this->add_control('premium_prefix_suffix_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Prefix Suffix Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'prefix_suffix_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text',
                ]
                );
        
        /*Prefix Suffix Text Background Color*/
        $this->add_control('premium_prefix_suffix_text_background_color',
                [
                    'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*End Prefix Suffix Text Settings Tab*/
        $this->end_controls_section();
    }

    protected function render( ) {
        // get our input from the widget settings.
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('premium_fancy_prefix_text');
        $this->add_inline_editing_attributes('premium_fancy_suffix_text');
        $cursor_text = addslashes($settings['premium_fancy_text_cursor_text']);
        
        if($settings['premium_fancy_text_effect'] == 'slide'){
            $this->add_render_attribute( 'premium_fancy_prefix_text', 'class', 'premium-fancy-text-span-align' );
            $this->add_render_attribute( 'premium_fancy_suffix_text', 'class', 'premium-fancy-text-span-align' );
        }
        
        if($settings['premium_fancy_text_effect'] == 'typing'){
            $show_cursor = (!empty($settings['premium_fancy_text_show_cursor'])) ? true : false;
            $loop = !empty( $settings['premium_fancy_text_type_loop'] ) ? true : false;
            $strings = array();
            foreach ( $settings['premium_fancy_text_strings'] as $item ) :
                if ( ! empty( $item['premium_text_strings_text_field'] ) ) :
                    array_push($strings, $item['premium_text_strings_text_field']);
                endif;
            endforeach;
            $fancytext_settings = [
                'effect'    => $settings['premium_fancy_text_effect'],
                'strings'   => $strings,
                'typeSpeed' => $settings['premium_fancy_text_type_speed'],
                'backSpeed' => $settings['premium_fancy_text_back_speed'],
                'startDelay'=> $settings['premium_fancy_text_start_delay'],
                'backDelay' => $settings['premium_fancy_text_back_delay'],
                'showCursor'=> $show_cursor,
                'cursorChar'=> $cursor_text,
                'loop'      => $loop,
            ];
        } else {
            $mause_pause = !empty( $settings['premium_slide_up_hover_pause'] ) ? true : false;
            $fancytext_settings = [
                'effect'        => $settings['premium_fancy_text_effect'],
                'speed'         => $settings['premium_slide_up_speed'],
                'showItems'     => $settings['premium_slide_up_shown_items'],
                'pause'         => $settings['premium_slide_up_pause_time'],
                'mousePause'    => $mause_pause
            ];
        }
        
?>
    

<div class="premium-fancy-text-wrapper" data-settings='<?php echo wp_json_encode($fancytext_settings); ?>'>
    <span class="premium-prefix-text"><span <?php echo $this->get_render_attribute_string('premium_fancy_prefix_text'); ?>><?php echo wp_kses( ( $settings['premium_fancy_prefix_text'] ), true ); ?></span></span>
    
    <?php if ( $settings['premium_fancy_text_effect'] === 'typing'  ) : ?><span id="premium_fancy_text_<?php echo esc_attr( $this->get_id() ); ?>" class="premium-fancy-text" ></span>
    <?php else : ?> 
    <div id="premium_fancy_text_<?php echo esc_attr( $this->get_id() ); ?>" class="premium-fancy-text" style=' display: inline-block; text-align: center;'>
	   <ul>
            <?php foreach ( $settings['premium_fancy_text_strings'] as $item ) : ?><?php if ( ! empty( $item['premium_text_strings_text_field'] ) ) : ?><?php echo "<li class='premium-fancy-list-items' >".esc_attr( $item['premium_text_strings_text_field'] )."</li>"; ?><?php endif; ?><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <span class="premium-suffix-text"><span <?php echo $this->get_render_attribute_string('premium_fancy_suffix_text'); ?>><?php echo wp_kses( ( $settings['premium_fancy_suffix_text'] ), true ); ?></span></span>
</div>
    <?php
    }
}