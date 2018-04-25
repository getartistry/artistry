<?php
namespace ElementPack\Modules\TrailerBox\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Trailer_Box extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-trailer-box';
	}

	public function get_title() {
		return esc_html__( 'Trailer Box', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Trailer box title', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'Trailer Box Title', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'content',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Trailer box text' , 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'I am Trailer Box Description Text. You can change me anytime from settings.' , 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'origin',
			[
				'label'   => esc_html__( 'Origin', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom-left',
				'options' => element_pack_position_options(),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'description' => 'Use align for matching position',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Maximum Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Minimum Height', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1024,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-trailer-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'View Details', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'View Details', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'button',
			[
				'label' => esc_html__( 'link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-button-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-button-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
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
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-title',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-text' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Text Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-trailer-box .bdt-trailer-box-text',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} a.bdt-trailer-box-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} a.bdt-trailer-box-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.bdt-trailer-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.bdt-trailer-box-button',
			]
		);

		$this->end_controls_section();


		// Background Overlay
		$this->start_controls_section(
			'section_advanced_background_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
				'condition' => [
					'_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_background_overlay' );

		$this->start_controls_tab(
			'tab_background_overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'selector' => '{{WRAPPER}} .elementor-widget-container > .elementor-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_opacity',
			[
				'label' => esc_html__( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container > .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay_hover',
				'selector' => '{{WRAPPER}}:hover .elementor-widget-container > .elementor-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_hover_opacity',
			[
				'label' => esc_html__( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover .elementor-widget-container > .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_hover_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function render() {
		$settings   = $this->get_settings();

		$origin = ' bdt-position-'.$settings['origin'];
		$has_background_overlay = in_array( $settings['background_overlay_background'], [ 'classic', 'gradient' ] ) ||
								  in_array( $settings['background_overlay_hover_background'], [ 'classic', 'gradient' ] );
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		?>

		<?php if ( $has_background_overlay ) : ?>
			<div class="elementor-background-overlay"></div>
		<?php endif; ?>

		<div class="bdt-trailer-box bdt-position-relative">
			<div class="bdt-trailer-box-desc bdt-position-medium<?php echo esc_attr($origin); ?>">
				<div class="bdt-trailer-box-desc-inner">
					<?php if ( '' !== $settings['title'] ) : ?>
						<h3 class="bdt-trailer-box-title"><?php echo esc_attr($settings['title']); ?></h3>
					<?php endif; ?>

					<?php if ( '' !== $settings['content'] ) : ?>
						<div class="bdt-trailer-box-text"><?php echo wp_kses_post($settings['content']); ?></div>
					<?php endif; ?>

					<?php if (( '' !== $settings['button']['url'] ) and ('' !== $settings['button_text'] )) : ?>
						<a class="bdt-trailer-box-button <?php echo esc_html($animation); ?>" href="<?php echo esc_url($settings['button']['url']); ?>"><?php echo esc_html($settings['button_text']); ?>

							<?php if ($settings['icon']) : ?>
								<span class="bdt-trailer-box-button-icon-<?php echo esc_attr($settings['icon_align']); ?>">
									<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
								</span>
							<?php endif; ?>

						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php
	}
}
