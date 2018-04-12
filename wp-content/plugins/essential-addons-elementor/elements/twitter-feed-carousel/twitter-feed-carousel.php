<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_twitter_feed_carousel_Carousel extends Widget_Base {

	public function get_name() {
		return 'eael-twitter-feed-carousel';
	}

	public function get_title() {
		return esc_html__( 'EA Twitter Feed Carousel', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-twitter';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
  			'eael_section_twitter_feed_carousel_acc_settings',
  			[
  				'label' => esc_html__( 'Account Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_twitter_feed_carousel_ac_name',
			[
				'label' => esc_html__( 'Account Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '@Codetic_',
				'label_block' => false,
				'description' => esc_html__( 'Use @ sign with your account name.', 'essential-addons-elementor' ),

			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_hashtag_name',
			[
				'label' => esc_html__( 'Hashtag Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'description' => esc_html__( 'Use # sign with your hashtag name.', 'essential-addons-elementor' ),

			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_consumer_key',
			[
				'label' => esc_html__( 'Consumer Key', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 'wwC72W809xRKd9ySwUzXzjkmS',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_consumer_secret',
			[
				'label' => esc_html__( 'Consumer Secret', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 'rn54hBqxjve2CWOtZqwJigT3F5OEvrriK2XAcqoQVohzr2UA8h',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
			]
		);

  		$this->end_controls_section();

		$this->start_controls_section(
  			'eael_section_twitter_feed_carousel_settings',
  			[
  				'label' => esc_html__( 'Layout Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_twitter_feed_carousel_content_length',
			[
				'label' => esc_html__( 'Content Length', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '400'
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_post_limit',
			[
				'label' => esc_html__( 'Post Limit', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 10
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_media',
			[
				'label' => esc_html__( 'Show Media Elements', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

  		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_twitter_feed_carousel_carousel_settings',
  			[
  				'label' => esc_html__( 'Carousel Settings', 'essential-addons-elementor' ),
  			]
  		);

  		$this->add_control(
			'eael_twitter_feed_carousel_carousel_dots',
			[
				'label' => esc_html__( 'Show Dots', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_carousel_pause_on_focus',
			[
				'label' => esc_html__( 'Pause On Focus', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_carousel_adaptive_height',
			[
				'label' => esc_html__( 'Adaptive Height', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_carousel_autoplay_speed',
			[
				'label' => esc_html__( 'Slide Speed', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '6000'
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_carousel_slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 3
			]
		);

  		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_twitter_feed_carousel_card_settings',
  			[
  				'label' => esc_html__( 'Card Settings', 'essential-addons-elementor' ),
  			]
  		);

  		$this->add_control(
			'eael_twitter_feed_carousel_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
            'eael_twitter_feed_carousel_avatar_style',
            [
                'label' => __( 'Avatar Style', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => 'Circle',
                    'square' => 'Square'
                ],
                'default' => 'circle',
                'prefix_class' => 'eael-social-feed-avatar-',
                'condition' => [
                	'eael_twitter_feed_carousel_show_avatar' => 'true'
                ],
            ]
        );

		$this->add_control(
			'eael_twitter_feed_carousel_show_date',
			[
				'label' => esc_html__( 'Show Date', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_show_read_more',
			[
				'label' => esc_html__( 'Show Read More', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

  		$this->end_controls_section();
  		/**
		 * -------------------------------------------
		 * Tab Style (Twitter Feed Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_twitter_feed_carousel_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-twitter-feed-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_twitter_feed_carousel_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-twitter-feed-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_twitter_feed_carousel_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-twitter-feed-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_twitter_feed_carousel_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-twitter-feed-wrapper',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-twitter-feed-wrapper' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_twitter_feed_carousel_shadow',
				'selector' => '{{WRAPPER}} .eael-twitter-feed-wrapper',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Twitter Feed Card Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_twitter_feed_carousel_card_style_settings',
			[
				'label' => esc_html__( 'Card Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_card_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_twitter_feed_carousel_card_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_twitter_feed_carousel_card_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_twitter_feed_carousel_card_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-social-feed-element .eael-content',
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_twitter_feed_carousel_card_shadow',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .eael-content',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Twitter Feed Typography Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_twitter_feed_carousel_card_typo_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .author-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_twitter_feed_carousel_title_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .author-title',
			]
		);
		// Content Style
		$this->add_control(
			'eael_twitter_feed_carousel_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .social-feed-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_twitter_feed_carousel_content_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .social-feed-text',
			]
		);

		// Content Link Style
		$this->add_control(
			'eael_twitter_feed_carousel_content_link_heading',
			[
				'label' => esc_html__( 'Link Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_content_link_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .text-wrapper a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_content_link_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .text-wrapper a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_twitter_feed_carousel_content_link_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .text-wrapper a',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Twitter Feed Preloader Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_twitter_feed_carousel_card_preloader_settings',
			[
				'label' => esc_html__( 'Preloader Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_twitter_feed_carousel_preloader_size',
			[
				'label' => esc_html__( 'Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-loading-feed .loader' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_section_twitter_feed_carousel_preloader_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3498db',
				'selectors' => [
					'{{WRAPPER}} .eael-loading-feed .loader' => 'border-top-color: {{VALUE}};',
				],
			]
		);


  		$this->end_controls_section();

	}


	protected function render( ) {

    	$settings = $this->get_settings();

      	$slides = $settings['eael_twitter_feed_carousel_carousel_slides_to_show'];
      	$slide_width = (100 / $slides);

	?>
		<div class="eael-twitter-feed-wrapper">
			<div id="eael-twitter-feed-<?php echo esc_attr($this->get_id()); ?>" class="eael-twitter-feed-container carousel-view"></div>
			<div class="eael-loading-feed"><div class="loader"></div></div>
		</div>
		<script>
		    jQuery( document ).ready( function($) {
		    	var loadingFeed = $( '.eael-loading-feed' );
		    	/**
		    	 * Twitter Feed Init
		    	 */
		    	function eael_twitter_feeds() {
		    		$( '.eael-twitter-feed-container' ).socialfeed({
			            // TWITTER
					    twitter:{
					       accounts: ['<?php echo $settings['eael_twitter_feed_carousel_ac_name']; ?>', '<?php echo $settings['eael_twitter_feed_carousel_hashtag_name']; ?>'],
					       limit: <?php echo $settings['eael_twitter_feed_carousel_post_limit']; ?>,
					       consumer_key: '<?php echo $settings['eael_twitter_feed_carousel_consumer_key']; ?>',
					       consumer_secret: '<?php echo $settings['eael_twitter_feed_carousel_consumer_secret']; ?>',
					    },

			            // GENERAL SETTINGS
			            length: <?php if( !empty( $settings['eael_twitter_feed_carousel_content_length'] ) ) : echo $settings['eael_twitter_feed_carousel_content_length']; else: echo '400'; endif; ?>,
			            show_media: <?php if( !empty( $settings['eael_twitter_feed_carousel_media'] ) ) : echo $settings['eael_twitter_feed_carousel_media']; else: echo 'false'; endif; ?>,
			            template: '<?php echo plugins_url( '/', __FILE__ ) . 'templates/carousel.php';  ?>',
			            callback: function() {
			            	eael_twitter_feed_carosuel();
			            }
			        });
		    	}

		    	/**
		    	 * Twitter Feed Carousel View
		    	 */
			    function eael_twitter_feed_carosuel() {

					$('#eael-twitter-feed-<?php echo esc_attr($this->get_id()); ?>').flickity({
		  				cellAlign: 'left',
		  				contain: false,
		  				imagesLoaded: true,
		  				pageDots: <?php if( !empty( $settings['eael_twitter_feed_carousel_carousel_dots'] ) ) : echo $settings['eael_twitter_feed_carousel_carousel_dots']; else: echo 'false'; endif; ?>,
		  				adaptiveHeight: <?php if( !empty( $settings['eael_twitter_feed_carousel_carousel_adaptive_height'] ) ) : echo $settings['eael_twitter_feed_carousel_carousel_adaptive_height']; else: echo 'false'; endif; ?>,
		  				autoPlay: <?php echo $settings['eael_twitter_feed_carousel_carousel_autoplay_speed']; ?>,
		  				pauseAutoPlayOnHover: <?php if( !empty( $settings['eael_twitter_feed_carousel_carousel_pause_on_focus'] ) ) : echo $settings['eael_twitter_feed_carousel_carousel_pause_on_focus']; else: echo 'false'; endif; ?>,
					});
				}

				$.ajax({
				   	url: eael_twitter_feeds(),
				   	beforeSend: function() {
				   		loadingFeed.addClass( 'show-loading' );
				   	},
				   	success: function() {
						setInterval( function() {
							loadingFeed.removeClass( 'show-loading' );

							eael_twitter_feed_carosuel();
						}, 6000 );
					},
					error: function() {
						console.log('error loading');
					}
				});

		    });
		</script>


		<?php
			echo '<style>';
			// Show Avatar
			if( $settings['eael_twitter_feed_carousel_show_avatar'] == 'true' ) {
				echo '.eael-social-feed-element .auth-img { display: block; }';
			}else {
				echo '.eael-social-feed-element .auth-img { display: none; }';
			}
			// Show Date
			if( $settings['eael_twitter_feed_carousel_show_date'] == 'true' ) {
				echo '.eael-social-feed-element .social-feed-date { display: block;  }';
			}else {
				echo '.eael-social-feed-element .social-feed-date { display: none;  }';
			}
			//  Show Read More
			if( $settings['eael_twitter_feed_carousel_show_read_more'] == 'true' ) {
			 	echo '.eael-social-feed-element .read-more-link { display: block }';
			}else {
			 	echo '.eael-social-feed-element .read-more-link { display: none !important; }';
			}
			//  Show Icon
			if( $settings['eael_twitter_feed_carousel_show_icon'] == 'true' ) {
			 	echo '.eael-social-feed-element .social-feed-icon { display: inline-block }';
			}else {
			 	echo '.eael-social-feed-element .social-feed-icon { display: none !important; }';
			 }

			echo '.eael-social-feed-element {width: '.$slide_width.'%}';

			echo '</style>';
		}

	protected function content_template() {''

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_twitter_feed_carousel_Carousel() );