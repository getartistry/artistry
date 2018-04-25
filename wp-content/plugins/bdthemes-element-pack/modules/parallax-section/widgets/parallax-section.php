<?php
namespace ElementPack\Modules\ParallaxSection\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Parallax_Section extends Widget_Base {

	public function get_name() {
		return 'bdt-parallax-section';
	}

	public function get_title() {
		return esc_html__( 'Parallax Section', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'parallax' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Parallax Section', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'parallax_elements',
			[
				'label'   => __( 'Parallax Items', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					[
						'parallax_title'   => __( 'Parallax 01', 'bdthemes-element-pack' ),
						'parallax_depth'   => 0.3,
					],
					[
						'parallax_title'   => __( 'Parallax 02', 'bdthemes-element-pack' ),
						'parallax_depth'   => 0.2,
					],
					[
						'parallax_title'   => __( 'Parallax 03', 'bdthemes-element-pack' ),
						'parallax_depth'   => 0.1,
					],
				],
				'fields' => [
					[
						'name'        => 'parallax_title',
						'label'       => __( 'Title', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Parallax 1' , 'bdthemes-element-pack' ),
						'label_block' => true,
						'render_type' => 'ui',
					],
					[
						'name'    => 'parallax_content',
						'label'   => esc_html__( 'Content', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::CHOOSE,
						'default' => 'parallax_text',
						'options' => [
							'parallax_text' => [
								'title' => esc_html__( 'Text', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-paint-brush',
							],
							'parallax_image' => [
								'title' => esc_html__( 'Image', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-picture-o',
							],
						],
					],
					[
						'name'        => 'parallax_text',
						'label'       => __( 'Parallax Text', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'label_block' => true,
						'condition'   => [ 'parallax_content' => 'parallax_text' ],
					],
					[
						'name'      => 'parallax_image',
						'label'     => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::MEDIA,
						'condition' => [ 'parallax_content' => 'parallax_image' ],
					],
					[
						'name'    => 'parallax_depth',
						'label'   => __( 'Depth', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::NUMBER,
						'default' => 0.1,
						'min'     => 0,
						'max'     => 1,
						'step'    => 0.1,
					],
					[
						'name'      => 'parallax_text_position',
						'label'     => __( 'Position', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => element_pack_position_options(),
						'center'    => 'center',
						'condition' => [ 'parallax_content' => 'parallax_text' ],
					],				
					
				],
				'title_field' => '{{{ parallax_title }}}',
			]
		);

		$this->add_control(
			'align',
			[
				'label'   => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					''    => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'condition' => [
					'tab_layout' => ['default', 'bottom']
				],
			]
		);

		$this->add_responsive_control(
			'section_height',
			[
				'label' => __( 'Height', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1200,
					],
				],
				'default' => [
					'size' => 650,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-parallax-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => __( 'Additional', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'parallax_section_overflow',
			[
				'label'     => esc_html__( 'Overflow Hidden', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				//'separator' => 'before',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'parallax_mode',
			[
				'label'   => esc_html__( 'Parallax Mode', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''         => esc_html__( 'Relative', 'bdthemes-element-pack' ),
					'relative' => esc_html__( 'Relative', 'bdthemes-element-pack' ),
					'clip'     => esc_html__( 'Clip', 'bdthemes-element-pack' ),
					'hover'    => esc_html__( 'Hovar (Mobile also turn off)', 'bdthemes-element-pack' ),
				],
				'render_type'  => 'template',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			[
				'label' => __( 'Tab', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'selector'  => '{{WRAPPER}} .bdt-tab .bdt-tabs-item-title',
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

		$this->add_control(
			'active_style_color',
			[
				'label'     => __( 'Style Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'active_title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-tab .bdt-tabs-item.bdt-active .bdt-tabs-item-title',
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_background_color',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-tabs .bdt-switcher-item-content',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-tabs .bdt-switcher-item-content' => 'color: {{VALUE}};',
				'separator' => 'before',
				],
			]
		);

		$this->add_control(
			'content_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tabs .bdt-switcher-item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-tabs .bdt-switcher-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .bdt-tabs .bdt-tab'        => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-tabs .bdt-tab-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-tabs .bdt-tab-left'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-tabs .bdt-tab-right'  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .bdt-tabs .bdt-switcher-item-content',
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
					'{{WRAPPER}} .bdt-tabs .bdt-tabs-item-title .fa:before' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bdt-tabs .bdt-tabs-item-title .bdt-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-tabs .bdt-tabs-item-title .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .bdt-tabs .bdt-tabs-item.bdt-active .bdt-tabs-item-title .fa:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		$id       = $this->get_id();
		
		$this->add_render_attribute( 'parallax-container', 'class', 'bdt-parallax-container' );
		$this->add_render_attribute( 'parallax-container', 'class', 'bdt-position-relative' );
		if ( 'yes' === $settings['parallax_section_overflow']) {
			$this->add_render_attribute( 'parallax-container', 'class', 'bdt-overflow-hidden' );
		}

		$this->add_render_attribute( 'scene', 'class', 'scene' );

		if ( 'relative' === $settings['parallax_mode']) {
			$this->add_render_attribute( 'scene', 'data-relative-input', 'true' );
		} elseif ( 'clip' === $settings['parallax_mode']) {
			$this->add_render_attribute( 'scene', 'data-clip-relative-input', 'true' );
		} elseif ( 'hover' === $settings['parallax_mode']) {
			$this->add_render_attribute( 'scene', 'data-hover-only', 'true' );
		}

		?>


		<div <?php echo $this->get_render_attribute_string( 'parallax-container' ); ?>>
			<div id="bdt_scene<?php echo esc_attr($id); ?>" <?php echo $this->get_render_attribute_string( 'scene' ); ?>>
				<?php foreach ( $settings['parallax_elements'] as $index => $item ) : ?>
					<?php if (!empty($item['parallax_text']) and $item['parallax_content'] === 'parallax_text') :
					$parallax_text_position  = ($item['parallax_text_position']) ? ' bdt-position-' . $item['parallax_text_position'] : '';
					?>
						<div data-depth="<?php echo $item['parallax_depth']; ?>"><div class="bdt-parallax-text<?php echo esc_attr($parallax_text_position); ?>"><?php echo $item['parallax_text']; ?></div></div>
					<?php else : ?>
						<?php $image_src = wp_get_attachment_image_src( $item['parallax_image']['id'], 'full' ); ?>
						<div data-depth="<?php echo $item['parallax_depth']; ?>">
							<?php if ($image_src) : ?>
								<img src="<?php echo esc_url($image_src[0]); ?>" alt="">
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				
			</div>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
			    'use strict';
				// Pretty simple huh?
				var bdt_scene<?php echo esc_attr($id); ?> = document.getElementById('bdt_scene<?php echo esc_attr($id); ?>');
				var parallax = new Parallax(bdt_scene<?php echo esc_attr($id); ?>);
			});
		</script>

		<?php
	}
}