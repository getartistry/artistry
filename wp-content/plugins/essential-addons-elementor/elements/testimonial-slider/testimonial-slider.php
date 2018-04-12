<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Testimonial_Slider extends Widget_Base {

	public function get_name() {
		return 'eael-testimonial-slider';
	}

	public function get_title() {
		return esc_html__( 'EA Testimonial Slider', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-comments-o';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}


	protected function _register_controls() {


  		$this->start_controls_section(
  			'eael_section_testimonial_content',
  			[
  				'label' => esc_html__( 'Testimonial Content', 'essential-addons-elementor' )
  			]
  		);


		$this->add_control(
			'eael_testimonial_slider_item',
			[
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'eael_testimonial_name' => 'John Doe',
					],
					[
						'eael_testimonial_name' => 'Jane Doe',
					],

				],
				'fields' => [

					[
						'name' => 'eael_testimonial_enable_avatar',
						'label' => esc_html__( 'Display Avatar?', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'yes',
					],
					[
						'name' => 'eael_testimonial_image',
						'label' => esc_html__( 'Testimonial Avatar', 'essential-addons-elementor' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'condition' => [
							'eael_testimonial_enable_avatar' => 'yes',
						],
					],
					[
						'name' => 'eael_testimonial_name',
						'label' => esc_html__( 'User Name', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'John Doe', 'essential-addons-elementor' ),
					],
					[
						'name' => 'eael_testimonial_company_title',
						'label' => esc_html__( 'Company Name', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'Codetic', 'essential-addons-elementor' ),
					],
					[
						'name' => 'eael_testimonial_description',
						'label' => esc_html__( 'Testimonial Description', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXTAREA,
						'default' => esc_html__( 'Add testimonial description here. Edit and place your own text.', 'essential-addons-elementor' ),
					],

					[
						'name' => 'eael_testimonial_enable_rating',
						'label' => esc_html__( 'Display Rating?', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'yes',
					],

				   [
					     'name' => 'eael_testimonial_rating_number',
					     'label'       => __( 'Rating Number', 'your-plugin' ),
					     'type' => Controls_Manager::SELECT,
					     'default' => 'rating-five',
					     'options' => [
					     	'rating-one'  => __( '1', 'essential-addons-elementor' ),
					     	'rating-two' => __( '2', 'essential-addons-elementor' ),
					     	'rating-three' => __( '3', 'essential-addons-elementor' ),
					     	'rating-four' => __( '4', 'essential-addons-elementor' ),
					     	'rating-five'   => __( '5', 'essential-addons-elementor' ),
					     ],
						'condition' => [
							'eael_testimonial_enable_rating' => 'yes',
						],
				   ],


				],
				'title_field' => 'Testimonial Item',
			]
		);



		$this->end_controls_section();



		$this->start_controls_section(
			'eael_section_testimonial_slider_settings',
			[
				'label' => esc_html__( 'Testimonial Slider Settings', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
		  'eael_testimonial_max_item',
		  [
		     'label'   => __( 'Max Visible Item', 'essential-addons-elementor' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 1,
		     'min'     => 1,
		     'max'     => 100,
		     'step'    => 1,
		  ]
		);

		$this->add_control(
		  'eael_testimonial_slide_item',
		  [
		     'label'   => __( 'Slide to Scroll', 'essential-addons-elementor' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 1,
		     'min'     => 1,
		     'max'     => 100,
		     'step'    => 1,
		  ]
		);

		$this->add_control(
		  'eael_testimonial_max_tab_item',
		  [
		     'label'   => __( 'Max Visible Items for Tablet', 'essential-addons-elementor' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 1,
		     'min'     => 1,
		     'max'     => 100,
		     'step'    => 1,
		  ]
		);

		$this->add_control(
		  'eael_testimonial_max_mobile_item',
		  [
		     'label'   => __( 'Max Visible Items for Mobile', 'essential-addons-elementor' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 1,
		     'min'     => 1,
		     'max'     => 100,
		     'step'    => 1,
		  ]
		);

		$this->add_control(
		  'eael_testimonial_slide_speed',
		  [
		     'label'   => __( 'Slide Speed', 'essential-addons-elementor' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 300,
		     'min'     => 100,
		     'max'     => 3000,
		     'step'    => 100,
		  ]
		);


		$this->add_control(
			'eael_testimonial_slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'eael_testimonial_slider_infinite',
			[
				'label' => esc_html__( 'Infinite Loop?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => 'false',
			]
		);

		$this->add_control(
			'eael_testimonial_slider_pause_hover',
			[
				'label' => esc_html__( 'Pause on Hover?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => 'false',
			]
		);

		$this->add_control(
			'eael_testimonial_slide_draggable',
			[
				'label' => esc_html__( 'Draggable?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'eael_testimonial_slide_variable_width',
			[
				'label' => esc_html__( 'Variable Width?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => 'false',
			]
		);

		$this->add_control(
			'eael_testimonial_slider_navigation',
			[
				'label' => esc_html__( 'Navigation & Pagination', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dots',
				'options' => [
					'none' => esc_html__( 'None', 'essential-addons-elementor' ),
					'dots' => esc_html__( 'Dots Only', 'essential-addons-elementor' ),
					'prev-next' => esc_html__( 'Prev/Next Only', 'essential-addons-elementor' ),
					'dots-nav' => esc_html__( 'Dots & Prev/Next', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_testimonial_slider_navigation_position',
			[
				'label' => esc_html__( 'Navigation & Pagination', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'nav-left-right',
				'options' => [
					'nav-left-right' => esc_html__( 'Normal (Left Right)', 'essential-addons-elementor' ),
					'nav-top-left' => esc_html__( 'Navigation Top Left', 'essential-addons-elementor' ),
					'nav-top-right' => esc_html__( 'Navigation Top Right', 'essential-addons-elementor' ),
				],
				'condition' => [
					'eael_testimonial_slider_navigation!' => 'none',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_testimonial_styles_general',
			[
				'label' => esc_html__( 'Testimonial Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_testimonial_background',
			[
				'label' => esc_html__( 'Testimonial Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_testimonial_alignment',
			[
				'label' => esc_html__( 'Set Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'eael-testimonial-align-default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
					'eael-testimonial-align-left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'eael-testimonial-align-centered' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'eael-testimonial-align-right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'eael-testimonial-align-centered',
			]
		);

		$this->add_control(
			'eael_testimonial_user_display_block',
			[
				'label' => esc_html__( 'Display User & Company Block?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);


		$this->add_responsive_control(
			'eael_testimonial_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'description' => 'Need to refresh the page to see the change properly',
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_testimonial_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-testimonial-item',
			]
		);

		$this->add_control(
			'eael_testimonial_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_testimonial_image_styles',
			[
				'label' => esc_html__( 'Testimonial Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_image_width',
			[
				'label' => esc_html__( 'Image Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 150,
					'unit' => 'px',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'eael_testimonial_image_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_image_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_testimonial_image_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-testimonial-image img',
			]
		);

		$this->add_control(
			'eael_testimonial_image_rounded',
			[
				'label' => esc_html__( 'Rounded Avatar?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'testimonial-avatar-rounded',
				'default' => '',
			]
		);


		$this->add_control(
			'eael_testimonial_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition' => [
					'eael_testimonial_image_rounded!' => 'testimonial-avatar-rounded',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_testimonial_typography',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_testimonial_name_heading',
			[
				'label' => __( 'User Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_name_color',
			[
				'label' => esc_html__( 'User Name Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_name_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user',
			]
		);

		$this->add_control(
			'eael_testimonial_company_heading',
			[
				'label' => __( 'Company Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);


		$this->add_control(
			'eael_testimonial_company_color',
			[
				'label' => esc_html__( 'Company Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user-company' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_position_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user-company',
			]
		);

		$this->add_control(
			'eael_testimonial_description_heading',
			[
				'label' => __( 'Testimonial Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_description_color',
			[
				'label' => esc_html__( 'Testimonial Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_description_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-text',
			]
		);

		$this->add_control(
			'eael_testimonial_quotation_heading',
			[
				'label' => __( 'Quotation Mark', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_quotation_color',
			[
				'label' => esc_html__( 'Quotation Mark Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.15)',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-quote' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_quotation_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-quote',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_testimonial_navigation_style',
			[
				'label' => esc_html__( 'Navigation/Pagination Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_testimonial_navigation_color',
			[
				'label' => esc_html__( 'Navigation Color (Arrows & Bullets)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-slider .slick-prev::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-testimonial-slider .slick-next::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_testimonial_navigation_bg',
			[
				'label' => esc_html__( 'Navigation Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-slider .slick-dots li button::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-testimonial-slider .slick-dots li.slick-active button::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-testimonial-slider .slick-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-testimonial-slider .slick-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_slider_bullet_size',
			[
				'label' => esc_html__( 'Bullet Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-slider .slick-dots li button::before' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'eael_testimonial_slider_active_bullet_size',
			[
				'label' => esc_html__( 'Active Bullet Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-slider .slick-dots li.slick-active button::before' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


	}


	protected function render( ) {

      $settings = $this->get_settings();
	  $testimonial_classes = $this->get_settings('eael_testimonial_image_rounded') . " " . $this->get_settings('eael_testimonial_alignment');
	  $navigation_type = $this->get_settings('eael_testimonial_slider_navigation');


	// Pagination type
	switch ( $navigation_type ) {
	  case 'dots':
	    $dots = 'true';
	    $nav  = 'false';
	    break;

	  case 'prev-next':
	    $dots = 'false';
	    $nav  = 'true';
	    break;

	  case 'dots-nav':
	    $dots = 'true';
	    $nav  = 'true';
	    break;

	  default: // NONE
	    $nav  = 'false';
	    $dots = 'false';
	    break;
	}

	$auto_play 		  = ( ($settings['eael_testimonial_slider_autoplay'] 	  == 'true') ? "true" : "false" );
	$infinite    	  = ( ($settings['eael_testimonial_slider_infinite']   	  == 'true') ? "true" : "false" );
	$pause_hover 	  = ( ($settings['eael_testimonial_slider_pause_hover']   == 'true') ? "true" : "false" );
	$draggable   	  = ( ($settings['eael_testimonial_slide_draggable'] 	  == 'true') ? "true" : "false" );
	$variable_width   = ( ($settings['eael_testimonial_slide_variable_width'] == 'true') ? "true" : "false" );


	?>

	<div id="eael-testimonial-<?php echo esc_attr($this->get_id()); ?>" class="eael-testimonial-slider <?php echo $settings['eael_testimonial_slider_navigation_position'];?>">
		<?php foreach ( $settings['eael_testimonial_slider_item'] as $item ) : ?>
		<div class="eael-testimonial-item clearfix <?php echo $testimonial_classes; ?>">

			<?php if ( $item['eael_testimonial_enable_avatar'] == 'yes' ) : ?>
			<div class="eael-testimonial-image">
				<span class="eael-testimonial-quote"></span>
				<figure>
  					<?php $image = $item['eael_testimonial_image']; ?>
  					<img src="<?php echo $image['url'];?>" alt="<?php echo esc_attr( $item['eael_testimonial_name'] ); ?>">
				</figure>
			</div>
			<?php endif; ?>
			<div class="eael-testimonial-content <?php echo $item['eael_testimonial_rating_number'] ?>" <?php if ( $item['eael_testimonial_enable_avatar'] == '' ) : ?> style="width: 100%;" <?php endif; ?>>
				<span class="eael-testimonial-quote"></span>
				<p class="eael-testimonial-text"><?php echo $item['eael_testimonial_description']; ?></p>
				<?php if ( ! empty($item['eael_testimonial_enable_rating'] ) ) : ?>
				<ul class="testimonial-star-rating">
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
				</ul>
				<?php endif;?>
				<p class="eael-testimonial-user" <?php if ( ! empty( $settings['eael_testimonial_user_display_block'] ) ) : ?> style="display: block; float: none;"<?php endif;?>><?php echo esc_attr( $item['eael_testimonial_name'] ); ?></p>
				<p class="eael-testimonial-user-company"><?php echo esc_attr( $item['eael_testimonial_company_title'] ); ?></p>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

<script type="text/javascript">

jQuery(document).ready(function($) {
    $("#eael-testimonial-<?php echo esc_attr($this->get_id()); ?>").slick({
      	autoplay: <?php echo $auto_play;?>,
      	infinite: <?php echo $infinite;?>,
      	speed: <?php echo $settings['eael_testimonial_slide_speed'];?>,
      	slidesToShow: <?php echo $settings['eael_testimonial_max_item'];?>,
      	slidesToScroll: <?php echo $settings['eael_testimonial_slide_item'];?>,
      	arrows: <?= $nav ?>,
      	dots: <?= $dots ?>,
      	pauseOnHover: <?php echo $pause_hover;?>,
      	draggable: <?php echo $draggable;?>,
      	variableWidth: <?php echo $variable_width;?>,
      	responsive: [
	    	{
	      		breakpoint: 1024,
	      		settings: {
	        		slidesToShow: <?php echo $settings['eael_testimonial_max_tab_item'];?>,
	        		slidesToScroll: 1
	      		}
	    	},
	    	{
	      		breakpoint: 768,
	      		settings: {
	        		slidesToShow: <?php echo $settings['eael_testimonial_max_mobile_item'];?>,
	        		slidesToScroll: 1
	      		}
	    	}
  		]
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


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Testimonial_Slider() );