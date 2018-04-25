<?php
namespace ElementPack\Modules\AdvancedButton\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AdvancedButton extends Widget_Base {
	public function get_name() {
		return 'bdt-advanced-button';
	}

	public function get_title() {
		return __( 'Advanced Button', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-button';
	}	

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_style_depends() {
		return [ 'bdt-advanced-button' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click me', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Click me', 'bdthemes-element-pack' ),
			]
		);


		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bdthemes-element-pack' ),
				'default'     => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => __( 'Button Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => [
					'xs' => __( 'Extra Small', 'bdthemes-element-pack' ),
					'sm' => __( 'Small', 'bdthemes-element-pack' ),
					'md' => __( 'Medium', 'bdthemes-element-pack' ),
					'lg' => __( 'Large', 'bdthemes-element-pack' ),
					'xl' => __( 'Extra Large', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'onclick',
			[
				'label'   => esc_html__( 'OnClick', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'onclick_event',
			[
				'label'       => __( 'OnClick Event', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'myFunction()',
				'description' => sprintf( __('For details please look <a href="%s" target="_blank">here</a>'), 'https://www.w3schools.com/jsref/event_onclick.asp' ),
				'condition' => [
					'onclick' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::CHOOSE,
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
				'options'      => [
					'left'    => [
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
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => __( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'   => __( 'Left', 'bdthemes-element-pack' ),
					'right'  => __( 'Right', 'bdthemes-element-pack' ),
					'top'    => __( 'Top', 'bdthemes-element-pack' ),
					'bottom' => __( 'Bottom', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => __( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
					'default' => [
						'size' => 8,
					],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button .bdt-button-icon-align-right'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-advanced-button .bdt-button-icon-align-left'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-advanced-button .bdt-button-icon-align-top'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-advanced-button .bdt-button-icon-align-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_effect',
			[
				'label'   => __( 'Effect', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'a',
				'options' => [
					'a' => __( 'Effect A', 'bdthemes-element-pack' ),
					'b' => __( 'Effect B', 'bdthemes-element-pack' ),
					'c' => __( 'Effect C', 'bdthemes-element-pack' ),
					'd' => __( 'Effect D', 'bdthemes-element-pack' ),
					'e' => __( 'Effect E', 'bdthemes-element-pack' ),
					'f' => __( 'Effect F', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->start_controls_tabs( 'tabs_advanced_button_style' );

		$this->start_controls_tab(
			'tab_advanced_button_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced_button_text_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666',
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-advanced-button',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_border_style',
			[
				'label'   => __( 'Border Style', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none'   => __( 'None', 'bdthemes-element-pack' ),
					'solid'  => __( 'Solid', 'bdthemes-element-pack' ),
					'dotted' => __( 'Dotted', 'bdthemes-element-pack' ),
					'dashed' => __( 'Dashed', 'bdthemes-element-pack' ),
					'groove' => __( 'Groove', 'bdthemes-element-pack' ),
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => __( 'Border Width', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666',
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_style!' => 'none'
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'advanced_button_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'advanced_button_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-button',
			]
		);

		$this->add_responsive_control(
			'advanced_button_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'advanced_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-advanced-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_advanced_button_hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced_button_hover_text_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_hover_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-advanced-button:after',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_hover_border_style',
			[
				'label'   => __( 'Border Style', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none'   => __( 'None', 'bdthemes-element-pack' ),
					'solid'  => __( 'Solid', 'bdthemes-element-pack' ),
					'dotted' => __( 'Dotted', 'bdthemes-element-pack' ),
					'dashed' => __( 'Dashed', 'bdthemes-element-pack' ),
					'groove' => __( 'Groove', 'bdthemes-element-pack' ),
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button:hover' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_hover_border_width',
			[
				'label' => __( 'Border Width', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_hover_border_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_hover_border_style!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'advanced_button_hover_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'advanced_button_hover_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-button:hover',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label'     => __( 'Icon', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_advanced_button_icon_style' );

		$this->start_controls_tab(
			'tab_advanced_button_icon_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced_button_icon_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'advanced_button_icon_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'advanced_button_icon_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after',
			]
		);

		$this->add_control(
			'advanced_button_icon_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'advanced_button_icon_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'advanced_button_icon_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'advanced_button_icon_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .bdt-advanced-button .bdt-advanced-button-icon:after',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_advanced_button_icon_hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced_button_icon_hover_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button:hover .bdt-advanced-button-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'advanced_button_icon_hover_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-advanced-button:hover .bdt-advanced-button-icon:after',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'icon_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-button:hover .bdt-advanced-button-icon:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'bdt-advanced-button-content-wrapper' );
		$this->add_render_attribute( 'content-wrapper', 'class', ( 'top' == $settings['icon_align'] ) ? 'bdt-flex bdt-flex-column' : '' );
		$this->add_render_attribute( 'content-wrapper', 'class', ( 'bottom' == $settings['icon_align'] ) ? 'bdt-flex bdt-flex-column-reverse' : '' );

		$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align'] );
		$this->add_render_attribute( 'icon-align', 'class', 'bdt-advanced-button-icon' );

		$this->add_render_attribute( 'text', 'class', 'bdt-advanced-button-text' );
		$this->add_inline_editing_attributes( 'text', 'none' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) ) : ?>
				<div class="bdt-advanced-button-icon bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				</div>
			<?php endif; ?>
			<div <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></div>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'wrapper', 'class', 'bdt-advanced-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'advanced_button', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'advanced_button', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'advanced_button', 'rel', 'nofollow' );
			}

		}

		if ( $settings['link']['nofollow'] ) {
			$this->add_render_attribute( 'advanced_button', 'rel', 'nofollow' );
		}

		if ( 'yes' === $settings['onclick'] ) {
			$this->add_render_attribute( 'advanced_button', 'onclick', $settings['onclick_event'] );
		}

		$this->add_render_attribute( 'advanced_button', 'class', 'bdt-advanced-button' );		
		$this->add_render_attribute( 'advanced_button', 'class', 'bdt-advanced-button-effect-' . esc_attr($settings['button_effect']) );
		$this->add_render_attribute( 'advanced_button', 'class', 'bdt-advanced-button-size-' . esc_attr($settings['button_size']) );

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'advanced_button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'advanced_button' ); ?>>
				<?php $this->render_text(); ?>
			</a>
		</div>
		<?php
	}


	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'text', 'class', 'bdt-advanced-button-text' );

		view.addInlineEditingAttributes( 'text', 'none' );
		
		
		view.addRenderAttribute( 'button', 'onclick', settings.onclick_event );

		#>
		<div class="elementor-button-wrapper">
			<a class="bdt-advanced-button bdt-advanced-button-effect-{{ settings.button_effect }} bdt-advanced-button-size-{{ settings.button_size }} elementor-animation-{{ settings.hover_animation }}" href="{{ settings.link.url }}" role="button" {{{ view.getRenderAttributeString( 'button' ) }}}>
				<div class="elementor-button-content-wrapper">
					<# if ( settings.icon ) { #>
					<div class="bdt-advanced-button-icon bdt-button-icon-align-{{ settings.icon_align }}">
						<i class="{{ settings.icon }}" aria-hidden="true"></i>
					</div>
					<# } #>
					<div {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</div>
				</div>
			</a>
		</div>
		<?php
	}
}
