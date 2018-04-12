<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Instagram_Feed extends Widget_Base {

	public function get_name() {
		return 'eael-instafeed';
	}

	public function get_title() {
		return esc_html__( 'EA Instagram Feed', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-instagram';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_instafeed_settings_general',
  			[
  				'label' => esc_html__( 'Instagram Account Settings', 'essential-addons-elementor' )
  			]
  		);
		
		$this->add_control(
			'eael_instafeed_access_token',
			[
				'label' => esc_html__( 'Access Token', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '4507625822.ba4c844.2608ae40c33d40fe97bffdc9bed8c9c7', 'essential-addons-elementor' ),
				'description' => '<a href="http://instagramwordpress.rafsegat.com/docs/get-access-token/" class="eael-btn" target="_blank">Get Access Token</a>', 'essential-addons-elementor',
			]
		);
		
		$this->add_control(
			'eael_instafeed_user_id',
			[
				'label' => esc_html__( 'User ID', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '4507625822', 'essential-addons-elementor' ),
				'description' => '<a href="https://smashballoon.com/instagram-feed/find-instagram-user-id/" class="eael-btn" target="_blank">Get User ID</a>', 'essential-addons-elementor',
			]
		);

		
		$this->add_control(
			'eael_instafeed_client_id',
			[
				'label' => esc_html__( 'Client ID', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '09908b866b954358b028cc488171dadf', 'essential-addons-elementor' ),
				'description' => '<a href="https://www.instagram.com/developer/clients/manage/" class="eael-btn" target="_blank">Get Client ID</a>', 'essential-addons-elementor',
			]
		);



		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_instafeed_settings_content',
  			[
  				'label' => esc_html__( 'Instagram Feed Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_instafeed_source',
			[
				'label' => esc_html__( 'Instagram Feed Source', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'user',
				'options' => [
					'user' => esc_html__( 'User', 'essential-addons-elementor' ),
					'tagged' => esc_html__( 'Hashtag', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_instafeed_hashtag',
			[
				'label' => esc_html__( 'Hashtag', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'cars', 'essential-addons-elementor' ),
				'condition' => [
					'eael_instafeed_source' => 'tagged',
				],
				'description' => 'Place the hashtag', 'essential-addons-elementor',
			]
		);

		$this->add_control(
			'eael_instafeed_image_count',
			[
				'label' => esc_html__( 'Max Visible Images', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
			]
		);

		$this->add_control(
			'eael_instafeed_columns',
			[
				'label' => esc_html__( 'Number of Columns', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-col-4',
				'options' => [
					'eael-col-1' => esc_html__( 'Single Column', 'essential-addons-elementor' ),
					'eael-col-2' => esc_html__( 'Two Columns',   'essential-addons-elementor' ),
					'eael-col-3' => esc_html__( 'Three Columns', 'essential-addons-elementor' ),
					'eael-col-4' => esc_html__( 'Four Columns',  'essential-addons-elementor' ),
					'eael-col-5' => esc_html__( 'Five Columns',  'essential-addons-elementor' ),
					'eael-col-6' => esc_html__( 'Six Columns',   'essential-addons-elementor' ),
				],
			]
		);


		$this->add_control(
			'eael_instafeed_image_resolution',
			[
				'label' => esc_html__( 'Image Resolution', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'low_resolution',
				'options' => [
					'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'essential-addons-elementor' ),
					'low_resolution' => esc_html__( 'Low Res (306x306)',   'essential-addons-elementor' ),
					'standard_resolution' => esc_html__( 'Standard (612x612)', 'essential-addons-elementor' ),
				],
			]
		);


		$this->add_control(
			'eael_instafeed_sort_by',
			[
				'label' => esc_html__( 'Sort By', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'essential-addons-elementor' ),
					'most-recent' => esc_html__( 'Most Recent',   'essential-addons-elementor' ),
					'least-recent' => esc_html__( 'Least Recent', 'essential-addons-elementor' ),
					'most-liked' => esc_html__( 'Most Likes', 'essential-addons-elementor' ),
					'least-liked' => esc_html__( 'Least Likes', 'essential-addons-elementor' ),
					'most-commented' => esc_html__( 'Most Commented', 'essential-addons-elementor' ),
					'least-commented' => esc_html__( 'Least Commented', 'essential-addons-elementor' ),
					'random' => esc_html__( 'Random', 'essential-addons-elementor' ),
				],
			]
		);


		$this->add_control(
			'eael_instafeed_caption_heading',
			[
				'label' => __( 'Caption & Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_instafeed_caption',
			[
				'label' => esc_html__( 'Display Caption', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'show-caption',
				'default' => 'no-caption',
			]
		);

		$this->add_control(
			'eael_instafeed_link',
			[
				'label' => esc_html__( 'Enable Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'eael_instafeed_link_target',
			[
				'label' => esc_html__( 'Open in new window?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'default' => '_blank',
				'condition' => [
					'eael_instafeed_link' => 'yes',
				],
			]
		);



		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_instafeed_styles_general',
			[
				'label' => esc_html__( 'Instagram Feed Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_responsive_control(
			'eael_instafeed_spacing',
			[
				'label' => esc_html__( 'Padding Between Images', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-insta-feed-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_instafeed_box_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-insta-feed-wrap',
			]
		);

		$this->add_control(
			'eael_instafeed_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-insta-feed-wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_instafeed_styles_content',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_instafeed_overlay_color',
			[
				'label' => esc_html__( 'Hover Overlay Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0, .75)',
				'selectors' => [
					'{{WRAPPER}} .eael-insta-feed-wrap::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_instafeed_like_comments_heading',
			[
				'label' => __( 'Like & Comments Styles', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_instafeed_like_comments_color',
			[
				'label' => esc_html__( 'Like &amp; Comments Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fbd800',
				'selectors' => [
					'{{WRAPPER}} .eael-insta-likes-comments > p' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_instafeed_like_comments_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .eael-insta-likes-comments > p',
			]
		);

		$this->add_control(
			'eael_instafeed_caption_style_heading',
			[
				'label' => __( 'Caption Styles', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_instafeed_caption_color',
			[
				'label' => esc_html__( 'Caption Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-insta-info-wrap' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_instafeed_caption_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .eael-insta-info-wrap',
			]
		);		


		$this->end_controls_section();

	}


	protected function render( ) {
		
      $settings = $this->get_settings();

      $image_limit 	= $this->get_settings( 'eael_instafeed_image_count' ); 
	  $link_target  = ( ($settings['eael_instafeed_link_target'] == 'yes') ? "_blank" : "_self" );
	  $enable_link  = ( ($settings['eael_instafeed_link'] == 'yes') ? "<a href=\"{{link}}\" target=\"$link_target\"></a>" : "" );
	  $no_caption   = ( ($settings['eael_instafeed_caption'] == 'show-caption') ? "show-caption" : "no-caption" );
	  $show_caption = ( ($settings['eael_instafeed_caption'] == 'show-caption') ? '<p class="insta-caption">{{caption}}</p>' : "" );


	?>
	<div class="eael-instagram-feed <?php echo $no_caption; ?> <?php echo esc_attr($settings['eael_instafeed_columns'] ); ?>">
		<div id="eael-instagram-feed-<?php echo esc_attr($this->get_id()); ?>" class="eael-insta-grid">
		</div>
	</div>


	<script type="text/javascript">

	jQuery(document).ready(function($) {
	  var feed = new Instafeed({
	    get: '<?php echo esc_attr($settings['eael_instafeed_source'] ); ?>',
	    tagName: '<?php echo esc_attr($settings['eael_instafeed_hashtag'] ); ?>',
	    userId: <?php echo esc_attr($settings['eael_instafeed_user_id'] ); ?>,
	    clientId: '<?php echo esc_attr($settings['eael_instafeed_client_id'] ); ?>',
	    accessToken: '<?php echo esc_attr($settings['eael_instafeed_access_token'] ); ?>',
	    limit: '<?php echo $image_limit['size']; ?>',
	    resolution: '<?php echo esc_attr($settings['eael_instafeed_image_resolution'] ); ?>',
	    sortBy: '<?php echo esc_attr($settings['eael_instafeed_sort_by'] ); ?>',
	    target: 'eael-instagram-feed-<?php echo esc_attr($this->get_id()); ?>',
	    template: '<div class="eael-insta-feed eael-insta-box"><div class="eael-insta-feed-inner"><div class="eael-insta-feed-wrap"><div class="eael-insta-img-wrap"><img src="{{image}}" /></div><div class="eael-insta-info-wrap"><div class="eael-insta-likes-comments"><p> <i class="fa fa-heart-o" aria-hidden="true"></i> {{likes}}</p> <p><i class="fa fa-comment-o" aria-hidden="true"></i> {{comments}}</p> </div><?php echo $show_caption; ?></div><?php echo $enable_link; ?></div></div></div>',
	    after: function() {
	      var el = document.getElementById('eael-instagram-feed-<?php echo esc_attr($this->get_id()); ?>');
	      if (el.classList)
	        el.classList.add('show');
	      else
	        el.className += ' ' + 'show';
	    }
	  });
	  feed.run();
	  });

	</script>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		'use strict';
		  $(window).load(function(){

		    $('.eael-insta-grid').masonry({
		      itemSelector: '.eael-insta-feed',
		      percentPosition: true,
		      columnWidth: '.eael-insta-box'
		    });

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


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Instagram_Feed() );