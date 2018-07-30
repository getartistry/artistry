<?php 
namespace Elementor;
if( !defined( 'ABSPATH' ) ) exit; // No access of directly access


class Premium_Counter_Widget extends Widget_Base {

	public function get_name() {
		return 'premium-counter';
	}

	public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Counter';
	}

	public function get_icon() {
		return 'pa-counter';
	}

	public function get_script_depends() {
		return [ 'waypoints','premium-addons-js','counter-up-js' ];
	}

	public function get_categories() {
		return [ 'premium-elements' ];
	}

    // Adding the controls fields for the premium counter
	// This will controls the animation, colors and background, dimensions etc
	protected function _register_controls() {
		$this->start_controls_section('premium_counter_global_settings',
			[
				'label'         => esc_html__( 'Counter', 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control('premium_counter_title',
			[
				'label'			=> esc_html__( 'Title', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
				'description'	=> esc_html__( 'Enter title for stats counter block', 'premium-addons-for-elementor'),
			]
		);
        
		$this->add_control('premium_counter_value',
			[
				'label'			=> esc_html__( 'Value', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Enter Counter Value', 'premium-addons-for-elementor' ),
				'default'		=> 500
			]
		);

		$this->add_control('premium_counter_t_separator',
			[
				'label'			=> esc_html__( 'Thousands Separator', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
				'description'	=> esc_html__( 'Separate coverts 125000 into 125,000', 'premium-addons-for-elementor' ),
				'default'		=> ','
			]
		);

		$this->add_control('premium_counter_d_separator',
			[
				'label'			=> esc_html__( 'Decimal Point', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
				'description'	=> esc_html__( 'Set a decimal number (Eg - 12.76). The decimal point '.' will be replaced with the value that you will enter above', 'premium-addons-for-elementor' ),
				'default'		=> '.'
			]
		);

		$this->add_control('premium_counter_d_after',
			[
				'label'			=> esc_html__( 'Digits After Decimal Point', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 0
			]
		);

		$this->add_control('premium_counter_preffix',
			[
				'label'			=> esc_html__( 'Value Prefix', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
				'description'	=> esc_html__( 'Enter prefix for counter value', 'premium-addons-for-elementor' )
			]
		);

		$this->add_control('premium_counter_suffix',
			[
				'label'			=> esc_html__( 'Value suffix', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
				'description'	=> esc_html__( 'Enter suffix for counter value', 'premium-addons-for-elementor' )
			]
		);

		$this->add_control('premium_counter_speed',
			[
				'label'			=> esc_html__( 'Rolling Time', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'How long should it take to complete the digit?', 'premium-addons-for-elementor' ),
				'default'		=> 3
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_counter_display_options',
			[
				'label'         => esc_html__( 'Display Options', 'premium-addons-for-elementor' )
			]
		);

		$this->add_control('premium_counter_icon_image',
		  	[
		     	'label'			=> esc_html__( 'Icon Type', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
                'description'   => esc_html__('Use a font awesome icon or upload a custom image', 'premium-addons-for-elementor'),
		     	'options'		=> [
		     		'icon'  => esc_html__('Font Awesome', 'premium-addons-for-elementor'),
		     		'custom'=> esc_html__( 'Custom Image', 'premium-addons-for-elementor')
		     	],
		     	'default'		=> 'icon'
		  	]
		);

		$this->add_control('premium_counter_icon',
		  	[
		     	'label'			=> esc_html__( 'Select an Icon', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::ICON,
                'default'       => 'fa fa-clock-o',
			  	'condition'		=> [
			  		'premium_counter_icon_image' => 'icon'
			  	]
		  	]
		);

		$this->add_control('premium_counter_image_upload',
		  	[
		     	'label'			=> esc_html__( 'Upload Image', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::MEDIA,
			  	'condition'			=> [
			  		'premium_counter_icon_image' => 'custom'
			  	],
			  	'default'		=> [
			  		'url' => Utils::get_placeholder_image_src(),
			  	]
		  	]
		);
        
        $this->add_control('premium_counter_icon_position',
			[
				'label'			=> esc_html__( 'Icon Position', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
                'description'	=> esc_html__( 'Choose a position for your icon', 'premium-addons-for-elementor'),
				'default'		=> 'no-animation',
				'options'		=> [
					'top'   => esc_html__( 'Top', 'premium-addons-for-elementor' ),
					'right' => esc_html__( 'Right', 'premium-addons-for-elementor' ),
					'left'  => esc_html__( 'Left', 'premium-addons-for-elementor' ),
					
				],
				'default' 		=> 'top',
				'separator' 	=> 'after'
			]
		);
        
        $this->add_control('premium_counter_icon_animation', 
            [
                'label'         => esc_html__('Animations', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::ANIMATION,
            ]
            );
        
		
		$this->end_controls_section();
        
        $this->start_controls_section('premium_counter_icon_style_tab',
			[
				'label'         => esc_html__( 'Icon' , 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_control('premium_counter_icon_color',
		  	[
				'label'         => esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon .icon i' => 'color: {{VALUE}};'
				],
			  	'condition'     => [
			  		'premium_counter_icon_image' => 'icon'
			  	]
			]
		);
        
        $this->add_responsive_control('premium_counter_icon_size',
		  	[
		     	'label'			=> esc_html__( 'Size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
		     	'default' => [
					'size' => 70,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon .icon' => 'font-size: {{SIZE}}{{UNIT}};'
				],
			  	'condition'     => [
			  		'premium_counter_icon_image' => 'icon'
			  	]
		  	]
		);

		$this->add_responsive_control('premium_counter_image_size',
		  	[
		     	'label'			=> esc_html__( 'Size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
		     	'default' => [
					'size' => 60,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon img.custom-image' => 'width: {{SIZE}}%;'
				],
			  	'condition'     => [
			  		'premium_counter_icon_image' => 'custom'
			  	]
		  	]
		);

		$this->add_control('premium_counter_icon_style',
		  	[
				'label' 		=> esc_html__( 'Style', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::SELECT,
                'description'   => esc_html__('We are giving you three quick preset if you are in a hurry. Otherwise, create your own with various options', 'premium-addons-for-elementor'),
				'options'		=> [
					'simple'=> esc_html__( 'Simple', 'premium-addons-for-elementor' ),
					'circle'=> esc_html__( 'Circle Background', 'premium-addons-for-elementor' ),
					'square'=> esc_html__( 'Square Background', 'premium-addons-for-elementor' ),
					'design'=> esc_html__( 'Design Your Own', 'premium-addons-for-elementor' )
				],
				'default' 		=> 'simple'
			]
		);

		$this->add_control('premium_counter_icon_bg',
			[
				'label' 		=> esc_html__( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_counter_icon_style!' => 'simple'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon .icon-bg' => 'background: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control('premium_counter_icon_bg_size',
		  	[
		     	'label'			=> esc_html__( 'Background size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
		     	'default' => [
					'size' => 150,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 600,
					]
				],
				'condition'		=> [
					'premium_counter_icon_style!' => 'simple'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon span.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
		  	]
		);

		$this->add_responsive_control('premium_counter_icon_v_align',
		  	[
		     	'label'			=> esc_html__( 'Vertical Alignment', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
		     	'default' => [
					'size' => 150,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 600,
					]
				],
				'condition'		=> [
					'premium_counter_icon_style!' => 'simple'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-counter-area .premium-counter-icon span.icon' => 'line-height: {{SIZE}}{{UNIT}};'
				]
		  	]
		);
        
        
        $this->add_group_control(
        Group_Control_Border::get_type(),
            [
                'name'          => 'premium_icon_border',
                'selector'      => '{{WRAPPER}} .premium-counter-area .premium-counter-icon .design',
                'condition'		=> [
					'premium_counter_icon_style' => 'design'
				]
            ]
            );

        $this->add_control('premium_icon_border_radius',
                [
                    'label'     => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'      => Controls_Manager::SLIDER,
                    'size_units'=> ['px', '%' ,'em'],
                    'default'   => [
                        'unit'      => 'px',
                        'size'      => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .premium-counter-area .premium-counter-icon .design' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ],
                    'condition'	=> [
					'premium_counter_icon_style' => 'design'
				]
                ]
                );
        
        $this->end_controls_section();
        
        
		$this->start_controls_section('premium_counter_title_style',
			[
				'label'         => esc_html__( 'Title' , 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control('premium_counter_title_color',
			[
				'label' 		=> esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} .premium-counter-area .premium-counter-title' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_counter_title_typho',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
				'selector'      => '{{WRAPPER}} .premium-counter-area .premium-counter-title',
				'separator'		=> 'after'
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_counter_value_style',
            [
                'label'         => esc_html__('Value', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );
        
		$this->add_control('premium_counter_value_color',
			[
				'label' 		=> esc_html__( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} .premium-counter-area .premium-counter-init' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_counter_value_typho',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
				'selector'      => '{{WRAPPER}} .premium-counter-area .premium-counter-init',
				'separator'		=> 'after'
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_counter_suffix_prefix_style',
            [
                'label'         => esc_html__('Prefix & Suffix', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );
        
        $this->add_control('premium_counter_prefix_color',
			[
				'label' 		=> esc_html__( 'Prefix Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors' 	=> [
					'{{WRAPPER}} .premium-counter-area span#prefix' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_counter_prefix_typo',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
				'selector'      => '{{WRAPPER}} .premium-counter-area span#prefix',
                'separator'     => 'after',
			]
		);

		$this->add_control('premium_counter_suffix_color',
			[
				'label' 		=> esc_html__( 'Suffix Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors' 	=> [
					'{{WRAPPER}} .premium-counter-area span#suffix' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_counter_suffix_typo',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
				'selector'      => '{{WRAPPER}} .premium-counter-area span#suffix',
                'separator'     => 'after',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes('premium_counter_title');
        
		if( $sep = $settings['premium_counter_t_separator'] ) {
			$separator = $sep;
		}
		if( $dec = $settings['premium_counter_t_separator'] ) {
			$decimal = $dec;
		}
    
        if( $settings['premium_counter_icon_image'] == 'icon' ) {
			$icon_image = '<i class="' . $settings['premium_counter_icon'] .'"></i>';
		} else {
			$icon_image = '<img class="custom-image" src="'.$settings['premium_counter_image_upload']['url'] . '" alt="">';
		}

		$icon_style = $settings['premium_counter_icon_style'] != 'simple' ? ' icon-bg ' . $settings['premium_counter_icon_style'] : '';
        
		$animation = $settings['premium_counter_icon_animation'];

		$position = $settings['premium_counter_icon_position'];
		
        if($position == 'top') {
            $center = ' center';
        } else {
            $center = '';
        }
        if($position == 'left'){
            $left = ' left';
        } else {
            $left = '';
        }
		
		$d_after 		= intval( $settings['premium_counter_d_after'] );
 		$d_s = $settings['premium_counter_d_separator'];
 		$t_s = $settings['premium_counter_t_separator'];
 		$exact_value = number_format( $settings['premium_counter_value'] , $d_after, $d_s, $t_s );
 		$flex_width = '';
 		if( $settings['premium_counter_icon_image'] == 'custom' && $settings['premium_counter_icon_style'] == 'simple' ) {
 			$flex_width = ' flex-width ';
 		}

 		$counter_settings = [
            'id'            => $this->get_id(),
            'value'			=> $settings['premium_counter_value'],
            'digits_after' 	=> $d_after,
            'speed'			=> $settings['premium_counter_speed'],
            'separator'		=> $separator,
            'decimal'		=> $decimal,
 		];

		?>
			<div id="counter-wrapper-<?php echo esc_attr($this->get_id()); ?>" class="premium-counter premium-counter-area<?php echo $center; ?>" data-settings='<?php echo wp_json_encode($counter_settings); ?>'>
				<?php if( $settings['premium_counter_icon_position'] == 'right' ) : ?>
					<div class="premium-init-wrapper <?php echo $settings['premium_counter_icon_position']; ?>">
                        
						<?php if (!empty( $settings['premium_counter_preffix'] ) ) : ?><span id="prefix" class="counter-su-pre"><?php echo $settings['premium_counter_preffix']; ?></span><?php endif; ?>
                        
						<span class="premium-counter-init" id="counter-<?php echo esc_attr($this->get_id()); ?>"><?php echo $exact_value; ?></span>
                        
						<?php if (!empty( $settings['premium_counter_suffix'] ) ) : ?><span id="suffix" class="counter-su-pre"><?php echo $settings['premium_counter_suffix']; ?></span><?php endif; ?>
                        
						<?php if (!empty( $settings['premium_counter_title'] ) ) : ?><h4 class="premium-counter-title"><div <?php echo $this->get_render_attribute_string('premium_counter_title'); ?>><?php echo $settings['premium_counter_title'];?></div></h4><?php endif; ?>
					</div>

					<?php if( !empty( $settings['premium_counter_icon'] ) || !empty( $settings['premium_counter_image_upload'] ) ) : ?>
						<div class="premium-counter-icon <?php echo $settings['premium_counter_icon_position']; ?>">
							<span data-animation="<?php echo $animation; ?>" class="icon<?php echo $flex_width; ?><?php echo $icon_style; ?>"><?php echo $icon_image; ?></span>
						</div>
					<?php endif; ?>

				<?php else: ?>

					<?php if( !empty( $settings['premium_counter_icon'] ) || !empty( $settings['premium_counter_image_upload'] ) ) : ?>
                    <div class="premium-counter-icon<?php echo $left; ?>">
							<span data-animation="<?php echo $animation; ?>" class="icon<?php echo $flex_width; ?><?php echo $icon_style; ?>"><?php echo $icon_image; ?></span>
						</div>		
					<?php endif; ?>

					<div class="premium-init-wrapper<?php echo $left; ?>">
                        
						<?php if (!empty( $settings['premium_counter_preffix'] ) ) : ?><span id="prefix" class="counter-su-pre"><?php echo $settings['premium_counter_preffix']; ?></span><?php endif; ?>
                        
						<span class="premium-counter-init" id="counter-<?php echo esc_attr($this->get_id()); ?>"><?php echo $exact_value; ?></span>
						
                        <?php if (!empty( $settings['premium_counter_suffix'] ) ) : ?><span id="suffix" class="counter-su-pre"><?php echo $settings['premium_counter_suffix']; ?></span><?php endif; ?>
                        
						<?php if (!empty( $settings['premium_counter_title'] ) ) : ?><h4 class="premium-counter-title"><div <?php echo $this->get_render_attribute_string('premium_counter_title'); ?>><?php echo $settings['premium_counter_title'];?></div></h4><?php endif; ?>
					</div>

				<?php endif; ?>
				
			</div>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Premium_Counter_Widget() );