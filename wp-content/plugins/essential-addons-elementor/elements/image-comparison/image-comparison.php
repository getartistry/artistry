<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Image_Comparison extends Widget_Base {
	

	public function get_name() {
		return 'eael-image-comparison';
	}

	public function get_title() {
		return esc_html__( 'EA Image Comparison', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-insert-image';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}


	protected function _register_controls() {

		// Content Controls
  		$this->start_controls_section(
  			'eael_image_comparison_images',
  			[
  				'label' => esc_html__( 'Images', 'essential-addons-elementor' )
  			]
  		);

		
		$this->add_control(
			'before_image',
			[
				'label' => __( 'Choose Before Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'before_image_alt',
			[
				'label' => __( 'Before Image ALT Tag', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'placeholder' => __( 'Enter alter tag for the image', 'essential-addons-elementor' ),
				'title' => __( 'Input image alter tag here', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'after_image',
			[
				'label' => __( 'Choose After Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'after_image_alt',
			[
				'label' => __( 'After Image ALT Tag', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'placeholder' => __( 'Enter alter tag for the image', 'essential-addons-elementor' ),
				'title' => __( 'Input image alter tag here', 'essential-addons-elementor' ),
			]
		);

		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_image_comparison_styles',
			[
				'label' => esc_html__( 'Image Container Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_image_container_width',
			[
				'label' => esc_html__( 'Set max width for the container?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'eael_image_container_width_value',
			[
				'label' => __( 'Container Max Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-img-comp-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'eael_image_container_width' => 'yes',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_img_comp_border',
				'selector' => '{{WRAPPER}} .eael-img-comp-container',
			]
		);
		
		
		$this->add_control(
			'eael_img_comp_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-img-comp-container' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_image_comparison_grabber_styles',
			[
				'label' => esc_html__( 'Comparison Line &amp; Grabber Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_image_comparison_line_color',
			[
				'label' => esc_html__( 'Comparison Line Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_image_comparison_grabber_color',
			[
				'label' => esc_html__( 'Comparison Grabber Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag::before' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		
	}


	protected function render( ) {
		
		
      $settings = $this->get_settings();
		
	  $before_image = $this->get_settings( 'before_image' );
	  $after_image = $this->get_settings( 'after_image' );
	  
	?>



  <div id="eael-image-comparison-<?php echo esc_attr($this->get_id()); ?>" class="eael-img-comp-container cocoen">
    <?php echo '<img class="eael-before-img" alt="'. $settings['before_image_alt'] . '" src="' . $before_image['url'] . '">'; ?>
    <?php echo '<img class="eael-after-img" alt="'. $settings['after_image_alt'] . '" src="' . $after_image['url'] . '">'; ?>
 </div>

<script type="text/javascript">

	jQuery(document).ready(function($) {
		'use strict';
	  		new Cocoen(document.querySelector('#eael-image-comparison-<?php echo esc_attr($this->get_id()); ?>'));
	});

</script>

	
	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Image_Comparison() );