<?php
namespace ElementPack\Modules\ImageCompare\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Image_Compare extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-image-compare';
	}

	public function get_title() {
		return esc_html__( 'Image Compare', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-image-before-after';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_style_depends() {
		return [ 'twentytwenty' ];
	}

	public function get_script_depends() {
		return [ 'eventmove', 'twentytwenty' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);


		$this->add_control(
			'before_image',
			[
				'label' => esc_html__( 'Before Image (Same Size of Both Image)', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
					'url' => BDTEP_ASSETS_URL.'images/before.svg',
				],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label' => esc_html__( 'After Image (Same Size of Both Image)', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
					'url' => BDTEP_ASSETS_URL.'images/after.svg',
				],
			]
		);

		$this->add_control(
			'before_label',
			[
				'label'       => esc_html__( 'Before Label', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Before Label', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'Before', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'after_label',
			[
				'label'       => esc_html__( 'After Label', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'After Label', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'After', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'   => esc_html__( 'Orientation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'bdthemes-element-pack' ),
					'vertical'   => esc_html__( 'Vertical', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_style',
			[
				'label' => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'before_background',
			[
				'label' => esc_html__( 'Before Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-before-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'before_color',
			[
				'label' => esc_html__( 'Before Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-before-label:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'after_background',
			[
				'label' => esc_html__( 'After Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-after-label:before' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'after_color',
			[
				'label' => esc_html__( 'After Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-after-label:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Bar Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-handle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-handle:before' => 'background-color: {{VALUE}}; -webkit-box-shadow: 0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5); box-shadow: 0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5);',
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-handle:after' => 'background-color: {{VALUE}}; -webkit-box-shadow: 0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5); box-shadow: 0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5);',
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-handle span.twentytwenty-left-arrow' => 'border-right-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-image-compare .twentytwenty-handle span.twentytwenty-right-arrow' => 'border-left-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	public function render() {
		$settings     = $this->get_settings();
		?>

		<div class="bdt-image-compare bdt-position-relative">
			<div class="twentytwenty-container">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'before_image' ); ?>
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'after_image' ); ?>
			</div>
		</div>

		<script>
			jQuery(document).ready(function($) {
				"use strict";
				$(".elementor-element-<?php echo esc_attr($this->get_id()); ?> .twentytwenty-container").twentytwenty({
					default_offset_pct: 0.7, 
					orientation: '<?php echo esc_attr($settings['orientation']); ?>',
					before_label: '<?php echo esc_html($settings['before_label']); ?>',
					after_label: '<?php echo esc_html($settings['after_label']); ?>',
				});
			});
		</script>

		<?php
	}
}
