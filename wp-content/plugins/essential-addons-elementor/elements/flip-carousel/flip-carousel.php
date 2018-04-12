<?php 
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Flip_Carousel extends Widget_Base {

	public function get_name() {
		return 'eael-flip-carousel';
	}

	public function get_title() {
		return esc_html__( 'EA Flip Carousel', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-carousel';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

		/**
  		 * Flip Carousel Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_flip_carousel_settings',
  			[
  				'label' => esc_html__( 'Filp Carousel Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_flip_carousel_type',
		  	[
		   	'label'       	=> esc_html__( 'Carousel Type', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'coverflow',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'coverflow' => esc_html__( 'Cover-Flow', 'essential-addons-elementor' ),
		     		'carousel'  => esc_html__( 'Carousel', 'essential-addons-elementor' ),
		     		'flat'  	=> esc_html__( 'Flat', 'essential-addons-elementor' ),
		     		'wheel'  	=> esc_html__( 'Wheel', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_control( 
			'eael_flip_carousel_fade_in',
			[
				'label' => esc_html__( 'Fade In (ms)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 400,
			]
		);

		$this->add_control(
		  'eael_flip_carousel_start_from',
		  	[
				'label' => __( 'Item Starts From Center?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		/**
		 * Condition: 'eael_flip_carousel_start_from' => 'true'
		 */
		$this->add_control( 
			'eael_flip_carousel_starting_number',
			[
				'label' => esc_html__( 'Enter Starts Number', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 1,
				'condition' => [
					'eael_flip_carousel_start_from!' => 'true'
				]
			]
		);

		$this->add_control(
		  'eael_flip_carousel_loop',
		  	[
				'label' => __( 'Loop', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'false',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
		  'eael_flip_carousel_autoplay',
		  	[
				'label' => __( 'Autoplay', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'false',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		/**
		 * Condition: 'eael_flip_carousel_autoplay' => 'true'
		 */
		$this->add_control( 
			'eael_flip_carousel_autoplay_time',
			[
				'label' => esc_html__( 'Autoplay Timeout (ms)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 2000,
				'condition' => [
					'eael_flip_carousel_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
		  'eael_flip_carousel_pause_on_hover',
		  	[
				'label' => __( 'Pause On Hover', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
		  'eael_flip_carousel_click',
		  	[
				'label' => __( 'On Click Play?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
		  'eael_flip_carousel_scrollwheel',
		  	[
				'label' => __( 'On Scroll Wheel Play?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
		  'eael_flip_carousel_touch',
		 	[
				'label' => __( 'On Touch Play?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
		  'eael_flip_carousel_button',
		  	[
				'label' => __( 'Carousel Navigator', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		$this->add_control(
			'eael_flip_carousel_spacing',
			[
				'label' => esc_html__( 'Slide Spacing', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => -0.6
				],
				'range' => [
					'px' => [
						'min' => -1,
						'max' => 1,
						'step' => 0.1
					],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Filp Carousel Slides
		 */
		$this->start_controls_section( 
			'eael_fli_carousel_slides',
			[
				'label' => esc_html__( 'Flip Carousel Slides', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'eael_flip_carousel_slides',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
					[ 'eael_flip_carousel_slide' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png' ],
				],
				'fields' => [
					[
						'name' => 'eael_flip_carousel_slide',
						'label' => esc_html__( 'Slide', 'essential-addons-elementor' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => ESSENTIAL_ADDONS_EL_URL . 'assets/flip-carousel/img/slide.png',
						],
					],
					[
						'name' => 'eael_flip_carousel_slide_text',
						'label' => esc_html__( 'Slide Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '', 'essential-addons-elementor' )
					],
					[
						'name' => 'eael_flip_carousel_enable_slide_link',
						'label' => __( 'Enable Slide Link', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'false',
						'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
						'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
						'return_value' => 'true',
				  	],
				  	[
						'name' => 'eael_flip_carousel_slide_link',
						'label' => esc_html__( 'Slide Link', 'essential-addons-elementor' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'default' => [
		        			'url' => '#',
		        			'is_external' => '',
		     			],
		     			'show_external' => true,
		     			'condition' => [
		     				'eael_flip_carousel_enable_slide_link' => 'true'
		     			]
					]
				],
				'title_field' => '{{eael_flip_carousel_slide_text}}',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Carousel Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flip_carousel_style_settings',
			[
				'label' => esc_html__( 'Flip Carousel Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_flip_carousel_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-flip-carousel' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_flip_carousel_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-flip-carousel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_flip_carousel_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-flip-carousel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_flip_carousel_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-flip-carousel',
			]
		);

		$this->add_control(
			'eael_flip_carousel_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-flip-carousel' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_flip_carousel_shadow',
				'selector' => '{{WRAPPER}} .eael-flip-carousel',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Carousel Navigator Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_filp_carousel_custom_nav_settings',
			[
				'label' => esc_html__( 'Navigator Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
		  'eael_flip_carousel_custom_nav',
		  	[
				'label' => __( 'Custom Navigator', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'false',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
		  	]
		);

		/**
		 * Condition: 'eael_flip_carousel_custom_nav' => 'true'
		 */
		$this->add_control(
			'eael_flip_carousel_custom_nav_prev',
			[
				'label' => esc_html__( 'Previous Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-arrow-left',
				'condition' => [
					'eael_flip_carousel_custom_nav' => 'true'
				]
			]
		);

		/**
		 * Condition: 'eael_flip_carousel_custom_nav' => 'true'
		 */
		$this->add_control(
			'eael_flip_carousel_custom_nav_next',
			[
				'label' => esc_html__( 'Next Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-arrow-right',
				'condition' => [
					'eael_flip_carousel_custom_nav' => 'true'
				]
			]
		);

		$this->add_responsive_control(
			'eael_flip_carousel_custom_nav_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .flip-custom-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_control(
			'eael_flip_carousel_custom_nav_size',
			[
				'label' => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '30'
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .flip-custom-nav' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_flip_carousel_custom_nav_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .flip-custom-nav' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_flip_carousel_custom_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .flip-custom-nav' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_flip_carousel_custom_nav_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#42418e',
				'selectors' => [
					'{{WRAPPER}} .flip-custom-nav' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_flip_carousel_custom_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .flip-custom-nav' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_flip_carousel_custom_nav_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-custom-nav',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_flip_carousel_custom_navl_shadow',
				'selector' => '{{WRAPPER}} .flip-custom-nav',
			]
		);
		
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Carousel Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_filp_carousel_content_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_flip_carousel_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_filp_carousel_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .flip-carousel-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_flip_carousel_content_typography',
				'selector' => '{{WRAPPER}} .flip-carousel-text',
			]
		);
		
		$this->end_controls_section();



	}


	protected function render( ) {
		
   	$settings = $this->get_settings();
    $flipbox_image = $this->get_settings( 'eael_flipbox_image' );
	$flipbox_image_url = Group_Control_Image_Size::get_attachment_image_src( $flipbox_image['id'], 'thumbnail', $settings );	

	// Loop Value
	if( 'true' == $settings['eael_flip_carousel_loop'] ) : $eael_loop = 'true'; else: $eael_loop = 'false'; endif;
	// Autoplay Value
	if( 'true' == $settings['eael_flip_carousel_autoplay'] ) : $eael_autoplay = $settings['eael_flip_carousel_autoplay_time']; else: $eael_autoplay = 'false'; endif;
	// Pause On Hover Value
	if( 'true' == $settings['eael_flip_carousel_pause_on_hover'] ) : $eael_pause_hover = 'true'; else: $eael_pause_hover = 'false'; endif;
	// Click Value
	if( 'true' == $settings['eael_flip_carousel_click'] ) : $eael_click = 'true'; else: $eael_click = 'false'; endif;
	// Scroll Wheel Value
	if( 'true' == $settings['eael_flip_carousel_scrollwheel'] ) : $eael_wheel = 'true'; else: $eael_wheel = 'false'; endif;
	// Touch Play Value
	if( 'true' == $settings['eael_flip_carousel_touch'] ) : $eael_touch = 'true'; else: $eael_touch = 'false'; endif;
	// Navigator Value
	if( 'true' == $settings['eael_flip_carousel_button'] ) : $eael_buttons = 'true'; else: $eael_buttons = 'false'; endif;
	if( 'true' == $settings['eael_flip_carousel_custom_nav'] ) : $eael_custom_buttons = 'custom';else: $eael_custom_buttons = ''; endif; 
	// Start Value
	if( 'true' == $settings['eael_flip_carousel_start_from'] ) : $eael_start = 'center'; else: $eael_start = (int) $settings['eael_flip_carousel_starting_number']; endif;
	
	
	?>
	<div class="eael-flip-carousel flip-carousel-<?php echo esc_attr( $this->get_id() ); ?>">
	    <ul class="flip-items">
	    	<?php foreach( $settings['eael_flip_carousel_slides'] as $slides ) : 
	    	?>
		        <li>
		        	<?php if( 'true' == $slides['eael_flip_carousel_enable_slide_link'] ) : 
		        		$eael_slide_link = $slides['eael_flip_carousel_slide_link']['url']; 
		        		$target = $slides['eael_flip_carousel_slide_link']['is_external'] ? 'target="_blank"' : '';
		        		$nofollow = $slides['eael_flip_carousel_slide_link']['nofollow'] ? 'rel="nofollow"' : '';
		        		?>
						<a href="<?php echo esc_url($eael_slide_link); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><img src="<?php echo $slides['eael_flip_carousel_slide']['url'] ?>"></a>
		            	<?php if( $slides['eael_flip_carousel_slide_text'] !='' ) : ?>
		            		<p class="flip-carousel-text"><?php echo esc_html__( $slides['eael_flip_carousel_slide_text'] ); ?></p>
		        		<?php endif; ?>
		        	<?php else: ?>
						<img src="<?php echo $slides['eael_flip_carousel_slide']['url'] ?>">
		            	<?php if( $slides['eael_flip_carousel_slide_text'] !='' ) : ?>
		            		<p class="flip-carousel-text"><?php echo esc_html__( $slides['eael_flip_carousel_slide_text'] ); ?></p>
		        		<?php endif; ?>
		        	<?php endif; ?>
		            
		        </li>
	    	<?php endforeach; ?>
	    </ul>
	</div>

	<script>
		jQuery( document ).ready( function($) {
			$(".flip-carousel-<?php echo esc_attr( $this->get_id() ); ?>").flipster({
	      		style: '<?php echo esc_attr( $settings['eael_flip_carousel_type'] ); ?>',
	      		start: <?php if( !(int) $eael_start ) : ?>'<?php echo $eael_start; ?>'<?php else: echo $eael_start - 1; endif; ?>,
	      		fadeIn: <?php echo esc_attr( (int) $settings['eael_flip_carousel_fade_in'] ); ?>,
	      		loop: <?php echo $eael_loop; ?>,
	      		autoplay: <?php echo $eael_autoplay; ?>,
	      		pauseOnHover: <?php echo $eael_pause_hover; ?>,
	      		spacing: <?php echo esc_attr( $settings['eael_flip_carousel_spacing']['size'] ); ?>,
	      		click: <?php echo $eael_click; ?>,
	      		scrollwheel: <?php echo $eael_wheel; ?>,
	      		touch: <?php echo $eael_touch; ?>,
	      		<?php if( $eael_custom_buttons == '' ) : ?>
	      			buttons: <?php echo $eael_buttons; ?>,
	      		<?php elseif( $eael_custom_buttons == 'custom' ): ?>
	      			buttons: '<?php echo $eael_custom_buttons; ?>',
	      		<?php endif; ?>
	      		buttonPrev: '<i class="flip-custom-nav <?php echo esc_attr( $settings['eael_flip_carousel_custom_nav_prev'] ); ?>"></i>',
	      		buttonNext: '<i class="flip-custom-nav <?php echo esc_attr( $settings['eael_flip_carousel_custom_nav_next'] ); ?>"></i>',
	    	});
		});

		
	</script>

	<?php
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Flip_Carousel() );