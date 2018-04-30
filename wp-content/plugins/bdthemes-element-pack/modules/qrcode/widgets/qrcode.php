<?php
namespace ElementPack\Modules\Qrcode\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Qrcode extends Widget_Base {

	public function get_name() {
		return 'bdt-qrcode';
	}

	public function get_title() {
		return esc_html__( 'QR Code', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-editor-code';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'qrcode' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_qrcode',
			[
				'label' => esc_html__( 'QR Code', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => 'http://bdthemes.com',
				'default'     => 'http://bdthemes.com',
				'condition'   => ['site_link!' => 'yes'],
			]
		);

		$this->add_control(
			'site_link',
			[
				'label'        => esc_html__( 'This Page Link', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'label_type',
			[
				'label'   => esc_html__( 'Label Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'none'  => esc_html__( 'None', 'bdthemes-element-pack' ),
					'text'  => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'image' => esc_html__( 'Image', 'bdthemes-element-pack' ),
				]
			]
		);

		$this->add_control(
			'label',
			[
				'label'       => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'BDTHEMES',
				'default'     => 'BDTHEMES',
				'condition'   => [
					'label_type' => 'text',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => __( 'Choose Image', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'label_type' => 'image',
				],
				'default' => [
					'url' => BDTEP_ASSETS_URL.'images/no-image.jpg',
				],
			]
		);

		$this->add_control(
			'mode',
			[
				'label'   => esc_html__( 'Mode', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => [
					'1' => esc_html__( 'Strip', 'bdthemes-element-pack' ),
					'2' => esc_html__( 'Box', 'bdthemes-element-pack' ),
				],
				'condition'   => [
					'label_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'elementor-align%s-',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_qr_code_additional',
			[
				'label' => esc_html__( 'Additional', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min'  => 100,
						'max'  => 1000,
						'step' => 50,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mSize',
			[
				'label'   => esc_html__( 'Label Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 11,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 40,
						'step' => 1,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mPosX',
			[
				'label'   => esc_html__( 'Label POS X:', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mPosY',
			[
				'label'   => esc_html__( 'Label POS Y:', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'minVersion',
			[
				'label'   => esc_html__( 'Min Version', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 6,
				],
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'ecLevel',
			[
				'label'   => esc_html__( 'Error Correction Level', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'H',
				'options' => [
					'L' => esc_html__( 'Low (7%)', 'bdthemes-element-pack' ),
					'M' => esc_html__( 'Medium (15%)', 'bdthemes-element-pack' ),
					'Q' => esc_html__( 'Quartile (25%)', 'bdthemes-element-pack' ),
					'H' => esc_html__( 'High (30%)', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_style_qrcode',
			[
				'label' => esc_html__( 'QR Code', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'fill',
			[
				'label'   => esc_html__( 'Code Color', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333333',
			]
		);

		$this->add_control(
			'fontcolor',
			[
				'label'   => esc_html__( 'Label Color', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ff9818',
				'condition' => [
					'label_type' => 'text',
				],
			]
		);

		$this->add_control(
			'radius',
			[
				'label'   => esc_html__( 'Code Radius', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 10,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings();
		$id        = 'bdt-qrcode' . $this->get_id(); 
		$image_src = wp_get_attachment_image_src( $settings['image']['id'], 'full' );
		$image     =  ($image_src) ? $image_src[0] : BDTEP_ASSETS_URL.'images/no-image.jpg';

		$qr_content = $settings['text'];
		
		if ('yes' === $settings['site_link']) {
			$qr_content =  get_permalink();
		}

		 if( 'none' == $settings['label_type'] ){
			$mode      = 0;
		 } elseif( 'text' == $settings['label_type'] ){
		 	$mode      = $settings['mode'];
		 } elseif( '' != $settings['image'] ){
		 	 $mode      = $settings['mode'] + 2;
		 } else {
		 	$mode = 0;
		 }
		?>

		<div id="<?php echo ($id); ?>" class="bdt-qrcode"></div>

		<script>
			jQuery(document).ready(function($) {
			    'use strict';

			   $('#<?php echo esc_attr($id); ?>').qrcode({
					render: 'canvas',
					ecLevel: '<?php echo esc_attr($settings['ecLevel']); ?>',
					minVersion: <?php echo esc_attr($settings['minVersion']['size']); ?>,
					
					fill: '<?php echo esc_attr($settings['fill']); ?>',
					background: 'transparent',
					
					text: '<?php echo esc_attr($qr_content); ?>',
					size: <?php echo esc_attr($settings['size']['size']); ?>,
					radius: <?php echo esc_attr($settings['radius']['size']); ?> * 0.01,
					
					mode: <?php echo esc_attr($mode); ?>,
					
					mSize: <?php echo esc_attr($settings['mSize']['size']); ?> * 0.01,
					mPosX: <?php echo esc_attr($settings['mPosX']['size']); ?> * 0.01,
					mPosY: <?php echo esc_attr($settings['mPosY']['size']); ?> * 0.01,
					
					label: '<?php echo esc_attr($settings['label']); ?>',
					fontname: 'Ubuntu',
					fontcolor: '<?php echo esc_attr($settings['fontcolor']); ?>',
					
					image: $('#<?php echo esc_attr($id); ?>image')[0],
			       
			    });

			});

		</script>

		<?php if ('image' === $settings['label_type'] and !empty($image)) : ?>
			<img id="<?php echo esc_attr($id); ?>image" src="<?php echo esc_url($image); ?>" class="bdt-hidden" alt="">
		<?php endif; ?>

        <?php
    }
}