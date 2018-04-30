<?php
namespace ElementPack\Modules\Switcher\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Switcher extends Widget_Base {

	public function get_name() {
		return 'bdt-switcher';
	}

	public function get_title() {
		return esc_html__( 'Switcher', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_switcher_a_layout',
			[
				'label' => __( 'Switch A', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'switch_a_title',
			[
				'label'   => __( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Switch A' , 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'switch_a_icon',
			[
				'label' => __( 'Icon', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'source_a',
			[
				'label'   => esc_html__( 'Select Source', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom'    => esc_html__( 'Custom', 'bdthemes-element-pack' ),
					"elementor" => esc_html__( 'Elementor Template', 'bdthemes-element-pack' ),
					'anywhere'  => esc_html__( 'AE Template', 'bdthemes-element-pack' ),
				],				
			]
		);

		$this->add_control(
			'template_id_a',
			[
				'label'       => __( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_et_options(),
				'label_block' => 'true',
				'condition'   => ['source_a' => "elementor"],
			]
		);

		$this->add_control(
			'anywhere_id_a',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_ae_options(),
				'label_block' => 'true',
				'condition'   => ['source_a' => 'anywhere'],
			]
		);

		$this->add_control(
			'switch_a_content',
			[
				'label'      => __( 'Content', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Switch Content A', 'bdthemes-element-pack' ),
				'show_label' => false,
				'condition'  => ['source_a' => 'custom'],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_switcher_b_layout',
			[
				'label' => __( 'Switch B', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'switch_b_title',
			[
				'label'   => __( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Switch B' , 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'switch_b_icon',
			[
				'label' => __( 'Icon', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'source_b',
			[
				'label'     => esc_html__( 'Select Source', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'custom',
				'options'   => [
					'custom'    => esc_html__( 'Custom', 'bdthemes-element-pack' ),
					"elementor" => esc_html__( 'Elementor Template', 'bdthemes-element-pack' ),
					'anywhere'  => esc_html__( 'AE Template', 'bdthemes-element-pack' ),
				],				
			]
		);

		$this->add_control(
			'template_id_b',
			[
				'label'       => __( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_ae_options(),
				'label_block' => 'true',
				'condition'   => ['source_b' => "elementor"],
			]
		);

		$this->add_control(
			'anywhere_id_b',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_ae_options(),
				'label_block' => 'true',
				'condition'   => ['source_b' => 'anywhere'],
			]
		);

		$this->add_control(
			'switch_b_content',
			[
				'label'      => __( 'Content', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Switch Content B', 'bdthemes-element-pack' ),
				'show_label' => false,
				'condition'  => ['source_b' => 'custom'],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_switcher_addtional',
			[
				'label' => __( 'Switch Settings', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'tab_layout',
			[
				'label'   => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'bottom'  => esc_html__( 'Bottom', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item + .bdt-tabs-item' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tabs-container .bdt-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'switcher_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tabs-container .bdt-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'tab_transition',
			[
				'label'   => esc_html__( 'Transition', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => element_pack_transition_options(),
				'default' => ''
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 501,
						'step' => 50,
					],
				],
				'default' => [
					'size' => 200,
				],
			]
		);

		$this->add_control(
			'media',
			[
				'label'       => __( 'Turn On Horizontal mode', 'bdthemes-element-pack' ),
				'description' => __( 'It means that when switch to the horizontal tabs mode from vertical mode', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'960' => [
						'title' => __( 'On Tablet', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-tablet',
					],
					'768' => [
						'title' => __( 'On Mobile', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-mobile',
					],
				],
				'condition' => [
					'tab_layout' => ['left', 'right']
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			[
				'label' => __( 'Title Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'switcher_background',
			[
				'label'     => __( 'Switcher Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-tabs-container .bdt-tab' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-tabs-container .bdt-tab > .bdt-tabs-item a',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item-title' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .bdt-tab .bdt-tabs-item .bdt-tabs-item-title',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'title_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-tab .bdt-tabs-item .bdt-tabs-item-title',
			]
		);

		$this->add_control(
			'title_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item .bdt-tabs-item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-tab .bdt-tabs-item-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_active',
			[
				'label' => __( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'active_title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-tabs-container .bdt-tab > .bdt-tabs-item.bdt-active > a:before',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'active_title_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'active_title_shadow',
				'selector' => '{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'active_title_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title',
			]
		);

		$this->add_control(
			'active_title_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_content',
			[
				'label'     => __( 'Content', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-switchers ul' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-switchers ul.bdt-tab-bottom'  => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .bdt-switchers .bdt-switcher-item-content',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Start', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'End', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => is_rtl() ? 'right' : 'left',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-switchers .bdt-tabs-item-title .fa:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-switchers .bdt-tabs-item-title .bdt-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-switchers .bdt-tabs-item-title .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			[
				'label' => __( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-switchers .bdt-tabs-item.bdt-active .bdt-tabs-item-title .fa:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings                     = $this->get_settings();
		$id                           = $this->get_id();
		$switcher_settings            = [];
		$switcher_settings['class'][] = 'bdt-tab';
		$switcher_settings['class'][] = ( '' !== $settings['tab_layout'] ) ? 'bdt-tab-' . $settings['tab_layout'] : '';
		$switcher_settings['bdt-tab'] = wp_json_encode(array_filter([
			'connect'   => '#bdt-switcher-' .  esc_attr($id),
			'animation' => ($settings['tab_transition']) ? 'bdt-animation-'. $settings['tab_transition'] : '',
			'duration'  => ($settings['duration']) ? $settings['duration']['size'] : '',
			'media'     => ($settings['media']) ? $settings['media'] : '',
			'swiping'   => false,
		]));

		?>
		<div id="bdt-tabs-<?php echo esc_attr($id); ?>" class="bdt-switchers">

			<?php if ( 'bottom' == $settings['tab_layout'] ) : ?>			
				<div class="bdt-switcher-container">
					<div id="bdt-switcher-<?php echo esc_attr($id); ?>" class="bdt-switcher bdt-switcher-item-content">
						
						<div class="bdt-text-center"><div>

							<?php 
			            	if ( 'custom' == $settings['source_a'] and !empty( $settings['switch_a_content'] ) ) {
			            		echo wp_kses_post( $settings['switch_a_content'] );
			            	} elseif ("elementor" == $settings['source_a'] and !empty( $settings['template_id_a'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id_a'] );
			            	} elseif ('anywhere' == $settings['source_a'] and !empty( $settings['anywhere_id_a'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id_a'] );
			            	}
			            ?>

						</div></div>

						<div class="bdt-text-center"><div>

							<?php 
			            	if ( 'custom' == $settings['source_b'] and !empty( $settings['switch_b_content'] ) ) {
			            		echo wp_kses_post( $settings['switch_b_content'] );
			            	} elseif ("elementor" == $settings['source_b'] and !empty( $settings['template_id_b'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id_b'] );
			            	} elseif ('anywhere' == $settings['source_b'] and !empty( $settings['anywhere_id_b'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id_b'] );
			            	}
			            ?>

						</div></div>
						
					</div>
				</div>
			<?php endif; ?>

			<div class="bdt-tabs-container">
				<div <?php echo \element_pack_helper::attrs($switcher_settings); ?>>
					<?php 
						$tab_count   = 0;
						$tab_title_a = ($settings['switch_a_title']) ? '' : ' bdt-has-no-title';
						$tab_title_b = ($settings['switch_b_title']) ? '' : ' bdt-has-no-title';

						?>
						<div class="bdt-tabs-item<?php echo esc_attr($tab_title_a); ?>">
							<a class="bdt-tabs-item-title" href="#">
								<div class="bdt-tab-text-wrapper">
									<?php if ('' != $settings['switch_a_icon'] and 'left' == $settings['icon_align']) : ?>
										<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
											<i class="<?php echo esc_attr($settings['switch_a_icon']); ?>"></i>
										</span>
									<?php endif; ?>

									<?php if ($settings['switch_a_title']) : ?>
										<span class="bdt-tab-text"><?php echo esc_attr($settings['switch_a_title']); ?></span>
									<?php endif; ?>

									<?php if ('' != $settings['switch_a_icon'] and 'right' == $settings['icon_align']) : ?>
										<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
											<i class="<?php echo esc_attr($settings['switch_a_icon']); ?>"></i>
										</span>
									<?php endif; ?>
								</div>
							</a>
						</div>

						<div class="bdt-tabs-item<?php echo esc_attr($tab_title_b); ?>">
							<a class="bdt-tabs-item-title" href="#">
								<div class="bdt-tab-text-wrapper">
									<?php if ('' != $settings['switch_b_icon'] and 'left' == $settings['icon_align']) : ?>
										<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
											<i class="<?php echo esc_attr($settings['switch_b_icon']); ?>"></i>
										</span>
									<?php endif; ?>

									<?php if ($settings['switch_b_title']) : ?>
										<span class="bdt-tab-text"><?php echo esc_attr($settings['switch_b_title']); ?></span>
									<?php endif; ?>

									<?php if ('' != $settings['switch_b_icon'] and 'right' == $settings['icon_align']) : ?>
										<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
											<i class="<?php echo esc_attr($settings['switch_b_icon']); ?>"></i>
										</span>
									<?php endif; ?>
								</div>
							</a>
						</div>
					
				</div>
			</div>

			<?php if ( 'bottom' != $settings['tab_layout'] ) : ?>
				<div class="bdt-switcher-wrapper">
					<div id="bdt-switcher-<?php echo esc_attr($id); ?>" class="bdt-switcher bdt-switcher-item-content">

						<div class="bdt-text-center"><div>

							<?php 
			            	if ( 'custom' == $settings['source_a'] and !empty( $settings['switch_a_content'] ) ) {
			            		echo wp_kses_post( $settings['switch_a_content'] );
			            	} elseif ("elementor" == $settings['source_a'] and !empty( $settings['template_id_a'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id_a'] );
			            	} elseif ('anywhere' == $settings['source_a'] and !empty( $settings['anywhere_id_a'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id_a'] );
			            	}
			            ?>

						</div></div>

						<div class="bdt-text-center"><div>

							<?php 
			            	if ( 'custom' == $settings['source_b'] and !empty( $settings['switch_b_content'] ) ) {
			            		echo wp_kses_post( $settings['switch_b_content'] );
			            	} elseif ("elementor" == $settings['source_b'] and !empty( $settings['template_id_b'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id_b'] );
			            	} elseif ('anywhere' == $settings['source_b'] and !empty( $settings['anywhere_id_b'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id_b'] );
			            	}
			            ?>

						</div></div>
						
					</div>
				</div>
			<?php endif; ?>

		</div>

		<?php
	}
}