<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Button_Plus extends Widget_Base {

		public function get_name() {
			return 'button_plus';
		}

		public function get_title() {
			return __( 'Button Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-button';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		public static function get_button_sizes() {
			return [
				'xs' => __( 'Extra Small', 'elements-plus' ),
				'sm' => __( 'Small', 'elements-plus' ),
				'md' => __( 'Medium', 'elements-plus' ),
				'lg' => __( 'Large', 'elements-plus' ),
				'xl' => __( 'Extra Large', 'elements-plus' ),
			];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_button',
				[
					'label' => __( 'Button Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'button_type',
				[
					'label' => __( 'Type', 'elements-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => __( 'Default', 'elements-plus' ),
						'info' => __( 'Info', 'elements-plus' ),
						'success' => __( 'Success', 'elements-plus' ),
						'warning' => __( 'Warning', 'elements-plus' ),
						'danger' => __( 'Danger', 'elements-plus' ),
					],
					'prefix_class' => 'elementor-button-',
				]
			);

			$this->add_control(
				'text',
				[
					'label' => __( 'Text Line 1', 'elements-plus' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'Click me', 'elements-plus' ),
					'placeholder' => __( 'Click me', 'elements-plus' ),
				]
			);

			$this->add_control(
				'text_2',
				[
					'label' => __( 'Text Line 2', 'elements-plus' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'More Text', 'elements-plus' ),
					'placeholder' => __( 'More Text', 'elements-plus' ),
				]
			);

			$this->add_control(
				'link',
				[
					'label' => __( 'Link', 'elements-plus' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'http://your-link.com',
					'default' => [
						'url' => '#',
					],
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elements-plus' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'elements-plus' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elements-plus' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elements-plus' ),
							'icon' => 'fa fa-align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'elements-plus' ),
							'icon' => 'fa fa-align-justify',
						],
					],
					'prefix_class' => 'elementor%s-align-',
					'default' => '',
				]
			);

			$this->add_control(
				'size',
				[
					'label' => __( 'Size', 'elements-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'sm',
					'options' => self::get_button_sizes(),
				]
			);

			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'elements-plus' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Button', 'elements-plus' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => __( 'Typography', 'elements-plus' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.elementor-button .elementor-button-text',
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab(
				'tab_button_normal',
				[
					'label' => __( 'Normal', 'elements-plus' ),
				]
			);

			$this->add_control(
				'button_text_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} a.elementor-button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'background_color',
				[
					'label' => __( 'Background Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_button_hover',
				[
					'label' => __( 'Hover', 'elements-plus' ),
				]
			);

			$this->add_control(
				'hover_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_background_hover_color',
				[
					'label' => __( 'Background Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_hover_border_color',
				[
					'label' => __( 'Border Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'hover_animation',
				[
					'label' => __( 'Animation', 'elements-plus' ),
					'type' => Controls_Manager::HOVER_ANIMATION,
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'elements-plus' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .elementor-button',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'elements-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .elementor-button',
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label' => __( 'Text Padding', 'elements-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_text_1',
				[
					'label' => __( 'Button Text Line 1', 'elements-plus' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography_1',
					'label' => __( 'Typography', 'elements-plus' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.elementor-button .elementor-button-text.text-1',
				]
			);

			$this->start_controls_tabs( 'tabs_line_1_style' );

			$this->start_controls_tab(
				'tab_line_1_normal',
				[
					'label' => __( 'Normal', 'elements-plus' ),
				]
			);

			$this->add_control(
				'line_1_text_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} a.elementor-button .elementor-button-text.text-1' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_line_1_hover',
				[
					'label' => __( 'Hover', 'elements-plus' ),
				]
			);

			$this->add_control(
				'line_1_hover_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover .elementor-button-text.text-1' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'section_text_2',
				[
					'label' => __( 'Button Text Line 2', 'elements-plus' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography_2',
					'label' => __( 'Typography', 'elements-plus' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.elementor-button .elementor-button-text.text-2',
				]
			);

			$this->start_controls_tabs( 'tabs_line_2_style' );

			$this->start_controls_tab(
				'tab_line_2_normal',
				[
					'label' => __( 'Normal', 'elements-plus' ),
				]
			);

			$this->add_control(
				'line_2_text_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} a.elementor-button .elementor-button-text.text-2' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_line_2_hover',
				[
					'label' => __( 'Hover', 'elements-plus' ),
				]
			);

			$this->add_control(
				'line_2_hover_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover .elementor-button-text.text-2' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();

			$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

			if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
				$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );

				if ( ! empty( $settings['link']['is_external'] ) ) {
					$this->add_render_attribute( 'button', 'target', '_blank' );
				}

				if ( $settings['link']['nofollow'] ) {
					$this->add_render_attribute( 'button', 'rel', 'nofollow' );
				}
			}

			$this->add_render_attribute( 'button', 'class', 'elementor-button' );

			if ( ! empty( $settings['size'] ) ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
			}

			if ( $settings['hover_animation'] ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
			}

			$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<span class="elementor-button-text button-plus text-1"><?php echo $settings['text']; ?></span>
					<span class="elementor-button-text button-plus text-2"><?php echo $settings['text_2']; ?></span>
				</span>
				</a>
			</div>
			<?php
		}

		protected function _content_template() {
			?>
			<div class="elementor-button-wrapper">
				<a class="elementor-button elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}" href="{{ settings.link.url }}">
				<span class="elementor-button-content-wrapper">
					<span class="elementor-button-text button-plus text-1">{{{ settings.text }}}</span>
					<span class="elementor-button-text button-plus text-2">{{{ settings.text_2 }}}</span>
				</span>
				</a>
			</div>
			<?php
		}
	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Button_Plus() );
	} );
