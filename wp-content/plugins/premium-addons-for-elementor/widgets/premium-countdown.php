<?php
namespace Elementor;
if( !defined( 'ABSPATH' ) ) exit; // No access of directly access

class Premium_Counter_Down_Widget extends Widget_Base {
	public function get_name() {
		return 'premium-countdown-timer';
	}

	public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Countdown';
	}

	public function get_icon() {
		return 'pa-countdown';
	}
    
    public function is_reload_preview_required() {
        return true;
    }	
    
    public function get_script_depends() {
		return [ 'premium-addons-js','count-down-timer-js' ];
	}

	public function get_categories() {
		return [ 'premium-elements' ];
	}

    // Adding the controls fields for the premium countdown
    // This will controls the animation, colors and background, dimensions etc
	protected function _register_controls() {
		$this->start_controls_section(
			'premium_countdown_global_settings',
			[
				'label'		=> esc_html__( 'Countdown', 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_style',
		  	[
		     	'label'			=> esc_html__( 'Style', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'options' 		=> [
		     		'd-u-s' => esc_html__( 'Inline', 'premium-addons-for-elementor' ),
		     		'd-u-u' => esc_html__( 'Block', 'premium-addons-for-elementor' ),
		     	],
		     	'default'		=> 'd-u-u'
		  	]
		);

		$this->add_control(
			'premium_countdown_date_time',
		  	[
		     	'label'			=> esc_html__( 'Due Date', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::DATE_TIME,
		     	'picker_options'	=> [
		     		'format' => 'Ym/d H:m:s'
		     	],
		     	'default' => date( "Y/m/d H:m:s", strtotime("+ 1 Day") ),
				'description' => esc_html__( 'Date format is (yyyy/mm/dd). Time format is (hh:mm:ss). Example: 2020-01-01 09:30.', 'premium-addons-for-elementor' )
		  	]
		);

		$this->add_control(
			'premium_countdown_s_u_time',
			[
				'label' 		=> esc_html__( 'Time Zone', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> [
					'wp-time'			=> esc_html__('WordPress Default', 'premium-addons-for-elementor' ),
					'user-time'			=> esc_html__('User Local Time', 'premium-addons-for-elementor' )
				],
				'default'		=> 'wp-time',
				'description'	=> esc_html__('This will set the current time of the option that you will choose.', 'premium-addons-for-elementor')
			]
		);

		$this->add_control(
			'premium_countdown_units',
		  	[
		     	'label'			=> esc_html__( 'Time Units', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT2,
				'description' => esc_html__('Select the time units that you want to display in countdown timer.', 'premium-addons-for-elementor' ),
				'options'		=> [
					'Y'     => esc_html__( 'Years', 'premium-addons-for-elementor' ),
					'O'     => esc_html__( 'Month', 'premium-addons-for-elementor' ),
					'W'     => esc_html__( 'Week', 'premium-addons-for-elementor' ),
					'D'     => esc_html__( 'Day', 'premium-addons-for-elementor' ),
					'H'     => esc_html__( 'Hours', 'premium-addons-for-elementor' ),
					'M'     => esc_html__( 'Minutes', 'premium-addons-for-elementor' ),
					'S' 	=> esc_html__( 'Second', 'premium-addons-for-elementor' ),
				],
				'default' 		=> [
					'O',
                    'D',
					'H',
					'M',
					'S'
				],
				'multiple'		=> true,
				'separator'		=> 'after'
		  	]
		);
        
        $this->add_responsive_control(
            'premium_countdown_align',
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
                    'toggle'        => false,
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-countdown' => 'justify-content: {{VALUE}};',
                        ],
                    ]
                );

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_on_expire_settings',
			[
				'label' => esc_html__( 'Expire' , 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_expire_text_url',
			[
				'label'			=> esc_html__('Expire Type', 'premium-addons-for-elementor'),
				'label_block'	=> false,
				'type'			=> Controls_Manager::SELECT,
                'description'   => esc_html__('Choose whether if you want to set a message or a redirect link', 'premium-addons-for-elementor'),
				'options'		=> [
					'text'		=> esc_html__('Message', 'premium-addons-for-elementor'),
					'url'		=> esc_html__('Redirection Link', 'premium-addons-for-elementor')
				],
				'default'		=> 'text'
			]
		);

		$this->add_control(
			'premium_countdown_expiry_text_',
			[
				'label'			=> esc_html__('On expiry Text', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::WYSIWYG,
				'default'		=> esc_html__('Countdown is finished!','prmeium_elementor'),
				'condition'		=> [
					'premium_countdown_expire_text_url' => 'text'
				]
			]
		);

		$this->add_control(
			'premium_countdown_expiry_redirection_',
			[
				'label'			=> esc_html__('Redirect To', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::TEXT,
				'condition'		=> [
					'premium_countdown_expire_text_url' => 'url'
				],
				'default'		=> get_permalink( 1 )
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_transaltion',
			[
				'label' => esc_html__( 'Strings Translation' , 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_day_singular',
		  	[
		     	'label'			=> esc_html__( 'Day (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Day'
		  	]
		);

		$this->add_control(
			'premium_countdown_day_plural',
		  	[
		     	'label'			=> esc_html__( 'Day (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Days'
		  	]
		);

		$this->add_control(
			'premium_countdown_week_singular',
		  	[
		     	'label'			=> esc_html__( 'Week (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Week'
		  	]
		);

		$this->add_control(
			'premium_countdown_week_plural',
		  	[
		     	'label'			=> esc_html__( 'Weeks (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Weeks'
		  	]
		);


		$this->add_control(
			'premium_countdown_month_singular',
		  	[
		     	'label'			=> esc_html__( 'Month (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Month'
		  	]
		);


		$this->add_control(
			'premium_countdown_month_plural',
		  	[
		     	'label'			=> esc_html__( 'Months (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Months'
		  	]
		);


		$this->add_control(
			'premium_countdown_year_singular',
		  	[
		     	'label'			=> esc_html__( 'Year (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Year'
		  	]
		);


		$this->add_control(
			'premium_countdown_year_plural',
		  	[
		     	'label'			=> esc_html__( 'Years (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Years'
		  	]
		);


		$this->add_control(
			'premium_countdown_hour_singular',
		  	[
		     	'label'			=> esc_html__( 'Hour (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Hour'
		  	]
		);


		$this->add_control(
			'premium_countdown_hour_plural',
		  	[
		     	'label'			=> esc_html__( 'Hours (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Hours'
		  	]
		);


		$this->add_control(
			'premium_countdown_minute_singular',
		  	[
		     	'label'			=> esc_html__( 'Minute (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Minute'
		  	]
		);

		$this->add_control(
			'premium_countdown_minute_plural',
		  	[
		     	'label'			=> esc_html__( 'Minutes (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Minutes'
		  	]
		);

        $this->add_control(
			'premium_countdown_second_singular',
		  	[
		     	'label'			=> esc_html__( 'Second (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Second',
		  	]
		);
        
		$this->add_control(
			'premium_countdown_second_plural',
		  	[
		     	'label'			=> esc_html__( 'Seconds (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
		     	'default'		=> 'Seconds'
		  	]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_typhography',
			[
				'label' => esc_html__( 'Digits' , 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'premium_countdown_digit_color',
			[
				'label' 		=> esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_countdown_digit_typo',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
				'separator'		=> 'after'
			]
		);
        
        
        $this->add_control(
			'premium_countdown_timer_digit_bg_color',
			[
				'label' 		=> esc_html__( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'background-color: {{VALUE}};'
				]
			]
		);
        
        $this->add_responsive_control(
			'premium_countdown_digit_bg_size',
		  	[
		     	'label'			=> esc_html__( 'Background Size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 30
                ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					]
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'padding: {{SIZE}}px;'
				]
		  	]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'          => 'premium_countdown_digits_border',
                    'selector'      => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
                ]);

        $this->add_control('premium_countdown_digit_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_countdown_unit_style', 
            [
                'label'         => esc_html__('Units', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );

        $this->add_control(
			'premium_countdown_unit_color',
			[
				'label' 		=> esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_countdown_unit_typo',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period',
				'separator'		=> 'after'
			]
		);
        
            $this->add_responsive_control(
			'premium_countdown_separator_width',
			[
				'label'			=> esc_html__( 'Spacing in Between', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::SLIDER,
				'default' 		=> [
					'size' => 40,
				],
				'range' 		=> [
					'px' 	=> [
						'min' => 0,
						'max' => 200,
					]
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section' => 'margin-right: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render( ) {
		
      	$settings = $this->get_settings();

      	$target_date = str_replace('-', '/', $settings['premium_countdown_date_time'] );
        
      	$formats = $settings['premium_countdown_units'];
      	$format = implode('', $formats );
      	$time = str_replace('-', '/', current_time('mysql') );
      	$serverSync = '';
      	if( $settings['premium_countdown_s_u_time'] == 'wp-time' ) : 
			$sent_time = $time;
        else:
            $sent_time = '';
        endif;

		$redirect = !empty( $settings['premium_countdown_expiry_redirection_'] ) ? esc_url($settings['premium_countdown_expiry_redirection_']) : '';
        
      	// Singular labels set up
      	$y = !empty( $settings['premium_countdown_year_singular'] ) ? $settings['premium_countdown_year_singular'] : 'Year';
      	$m = !empty( $settings['premium_countdown_month_singular'] ) ? $settings['premium_countdown_month_singular'] : 'Month';
      	$w = !empty( $settings['premium_countdown_week_singular'] ) ? $settings['premium_countdown_week_singular'] : 'Week';
      	$d = !empty( $settings['premium_countdown_day_singular'] ) ? $settings['premium_countdown_day_singular'] : 'Day';
      	$h = !empty( $settings['premium_countdown_hour_singular'] ) ? $settings['premium_countdown_hour_singular'] : 'Hour';
      	$mi = !empty( $settings['premium_countdown_minute_singular'] ) ? $settings['premium_countdown_minute_singular'] : 'Minute';
      	$s = !empty( $settings['premium_countdown_second_singular'] ) ? $settings['premium_countdown_second_singular'] : 'Second';
      	$label = $y."," . $m ."," . $w ."," . $d ."," . $h ."," . $mi ."," . $s;

      	// Plural labels set up
      	$ys = !empty( $settings['premium_countdown_year_plural'] ) ? $settings['premium_countdown_year_plural'] : 'Years';
      	$ms = !empty( $settings['premium_countdown_month_plural'] ) ? $settings['premium_countdown_month_plural'] : 'Months';
      	$ws = !empty( $settings['premium_countdown_week_plural'] ) ? $settings['premium_countdown_week_plural'] : 'Weeks';
      	$ds = !empty( $settings['premium_countdown_day_plural'] ) ? $settings['premium_countdown_day_plural'] : 'Days';
      	$hs = !empty( $settings['premium_countdown_hour_plural'] ) ? $settings['premium_countdown_hour_plural'] : 'Hours';
      	$mis = !empty( $settings['premium_countdown_minute_plural'] ) ? $settings['premium_countdown_minute_plural'] : 'Minutes';
      	$ss = !empty( $settings['premium_countdown_second_plural'] ) ? $settings['premium_countdown_second_plural'] : 'Seconds';
      	$labels1 = $ys."," . $ms ."," . $ws ."," . $ds ."," . $hs ."," . $mis ."," . $ss;
      	
        $expire_text = $settings['premium_countdown_expiry_text_'];
        
      	$pcdt_style = $settings['premium_countdown_style'] == 'd-u-s' ? ' side' : ' down';
        
        if( $settings['premium_countdown_expire_text_url'] == 'text' ){
            $event = 'onExpiry';
            $text = $expire_text;
        }
        
        if( $settings['premium_countdown_expire_text_url'] == 'url' ){
            $event = 'expiryUrl';
            $text = $redirect;
        }
        $countdown_settings = [
            'label1'    => $label,
            'label2'    => $labels1,
            'until'     => $target_date,
            'format'    => $format,
            'event'     => $event,
            'text'      => $text,
            'serverSync'=> $sent_time,
        ];
        
      	?>
        <div id="countDownContiner-<?php echo esc_attr($this->get_id()); ?>" class="premium-countdown" data-settings='<?php echo wp_json_encode($countdown_settings); ?>'>
            <div id="countdown-<?php echo esc_attr( $this->get_id() ); ?>" class="premium-countdown-init countdown<?php echo $pcdt_style; ?>"></div>
        </div>
      	<?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Premium_Counter_Down_Widget() );