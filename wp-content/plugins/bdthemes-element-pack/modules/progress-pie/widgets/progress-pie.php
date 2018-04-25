<?php
namespace ElementPack\Modules\ProgressPie\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Progress_Pie extends Widget_Base {

	protected $_has_template_content = true;

	public function get_name() {
		return 'bdt-progress-pie';
	}

	public function get_title() {
		return esc_html__( 'Progress Pie', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-counter-circle';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'aspieprogress' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'percent',
			[
				'label'       => esc_html__( 'Percent', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 75,
			]
		);

		$this->add_control(
			'duration',
			[
				'label'       => esc_html__( 'Duration', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
			]
		);

		$this->add_control(
			'delay',
			[
				'label'       => esc_html__( 'Delay', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
			]
		);

		$this->add_control(
			'step',
			[
				'label'       => esc_html__( 'Steps', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Progress Pie Title', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your title text here', 'bdthemes-element-pack' ),
				'default' => esc_html__( 'Progress Pie Title', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'hide_title_divider',
			[
				'label'        => esc_html__( 'Hide Title Divider', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => ' bdt-no-divider',
				'condition' => [
					'title!' => '',
				]
			]
		);

		$this->add_control(
			'before',
			[
				'label'       => esc_html__( 'Before Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your before text here', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => esc_html__( 'Middle Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your middle text here', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'after',
			[
				'label'       => esc_html__( 'After Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your after text here', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'progress_pie',
			[
				'label' => esc_html__( 'Progress Pie', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'progress_background',
			[
				'label' => esc_html__( 'Pie Fill Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie svg ellipse' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'progress_color',
			[
				'label' => esc_html__( 'Pie Bar Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'before_text_color',
			[
				'label' => esc_html__( 'Before Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'before!' => '',
				],
			]
		);

		$this->add_control(
			'middle_text_color',
			[
				'label' => esc_html__( 'Middle Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'text!' => '',
				],

			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Percentage Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-number' => 'color: {{VALUE}};',
				],
				'condition' => [
					'text' => '',
				],

			]
		);

		$this->add_control(
			'after_text_color',
			[
				'label' => esc_html__( 'After Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-after' => 'color: {{VALUE}};',
				],
				'condition' => [
					'after!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'progress_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_width',
			[
				'label'       => esc_html__( 'Line Width', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 8,
			]
		);


		$this->add_control(
			'line_cap',
			[
				'label'     => esc_html__( 'Line Cap', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'round',
				'options'   => [
					'round' => esc_html__( 'Rounded', 'bdthemes-element-pack' ),
					'square'  => esc_html__( 'Square', 'bdthemes-element-pack' ),
					'butt'    => esc_html__( 'Butt', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'progress_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'title_background',
			[
				'label' => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie-title' => 'background-color: {{VALUE}};  border-top: none;',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-progress-pie-wrapper .bdt-progress-pie-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'condition' => [
					'title!' => '',
				],
			]
		);


		$this->end_controls_section();

	}

	public function render() {
		$id                           = $this->get_id();
		$settings                     = $this->get_active_settings();
		$before_txt                   = $mdl_text = $after_txt = $title = '';
		$pp_settings                  = [];
		$pp_settings['class']         = ['bdt-progress-pie', 'bdt-pp-lc-'.$settings['line_cap']];		
		$pp_settings['class'][]       = (!$settings['text']) ? 'bdt-pp-percent' : '';		
		$pp_settings['id']            = [$id];
		$pp_settings['role']          = ['progressbar'];
		$pp_settings['data-goal']     = [$settings['percent']];
		$pp_settings['aria-valuemin'] = ['0'];
		$pp_settings['data-step']     = [$settings['step']];
		$pp_settings['data-speed']    = [$settings['duration']*15];
		$pp_settings['data-delay']    = [$settings['delay']*1000];
		$pp_settings['data-barsize']  = [intval($settings['line_width'])];
		$pp_settings['aria-valuemax'] = ['100'];


		?>
				<div id="<?php echo esc_attr($id); ?>_container" class="bdt-progress-pie-wrapper">
					<div <?php echo \element_pack_helper::attrs($pp_settings); ?>>
				    	<div class="bdt-progress-pie-label">
					       <?php if ($settings['before'] !== '') : ?>
							    <div class="bdt-progress-pie-before"><?php echo esc_html($settings['before']); ?></div>
							<?php endif; ?>

					       <?php if ($settings['text'] !== '') : ?>
					       		    <div class="bdt-progress-pie-text"><?php echo esc_html($settings['text']); ?></div>
			       		   <?php else : ?>
					            <div class="bdt-progress-pie-number"></div>
				        	<?php endif; ?>
					        <?php if ($settings['after'] !== '') : ?>
			        		    <div class="bdt-progress-pie-after"><?php echo esc_html($settings['after']); ?></div>
			        		<?php endif; ?>
					    </div>
					</div>
						<?php if ($settings['title'] !== '') : ?>
						    <h4 class="bdt-progress-pie-title<?php echo esc_attr($settings['hide_title_divider']); ?>"><?php echo esc_html($settings['title']); ?></h4>
						<?php endif; ?>
	 					
				</div>

				<script>
					jQuery(document).ready(function($) {
			    		"use strict";
				        var progressPie = "#<?php echo esc_attr($id); ?>";

				        $(progressPie).asPieProgress({
				            namespace: "pieProgress",
				            classes: {
				                svg: "bdt-progress-pie-svg",
				                number: "bdt-progress-pie-number",
				                content: "bdt-progress-pie-content"
				            }
				        });
				        $(progressPie).asPieProgress("start");
					});
				</script>
		<?php
	}
}
