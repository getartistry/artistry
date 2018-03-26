<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Label extends Widget_Base {

		public function get_name() {
			return 'label';
		}

		public function get_title() {
			return __( 'Label Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-label';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_label',
				[
					'label' => __( 'Label Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'text',
				[
					'label' => __( 'Text', 'elements-plus' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'On Sale', 'elements-plus' ),
					'placeholder' => __( 'On Sale', 'elements-plus' ),
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
				'icon',
				[
					'label' => __( 'Icon', 'elements-plus' ),
					'type' => Controls_Manager::ICON,
					'label_block' => true,
					'default' => '',
				]
			);

			$this->add_control(
				'icon_align',
				[
					'label' => __( 'Icon Position', 'elements-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left' => __( 'Before', 'elements-plus' ),
						'right' => __( 'After', 'elements-plus' ),
					],
					'condition' => [
						'icon!' => '',
					],
				]
			);

			$this->add_control(
				'icon_indent',
				[
					'label' => __( 'Icon Spacing', 'elements-plus' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'condition' => [
						'icon!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-label .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-label .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
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
					'label' => __( 'Label', 'elements-plus' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => __( 'Typography', 'elements-plus' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} div.elementor-label',
				]
			);

			$this->start_controls_tabs( 'tabs_label_style' );

			$this->add_control(
				'label_text_color',
				[
					'label' => __( 'Text Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'default' =>'#000',
					'selectors' => [
						'{{WRAPPER}} div.elementor-label' => 'color: {{VALUE}};',
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
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} div.elementor-label' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'elements-plus' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .elementor-label',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'elements-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} div.elementor-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'label_box_shadow',
					'selector' => '{{WRAPPER}} .elementor-label',
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label' => __( 'Text Padding', 'elements-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} div.elementor-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();

			$this->add_render_attribute( 'wrapper', 'class', 'elementor-label-wrapper' );

			$this->add_render_attribute( 'label', 'class', 'elementor-label' );

			$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-label-content-wrapper' );
			$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align'] );
			$this->add_render_attribute( 'icon-align', 'class', 'elementor-label-icon' );
			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'label' ); ?>>
					<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
						<?php if ( ! empty( $settings['icon'] ) ) : ?>
							<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
								<i class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>
							</span>
						<?php endif; ?>
						<span class="elementor-label-text"><?php echo $settings['text']; ?></span>
					</span>
				</div>
			</div>
			<?php
		}

		protected function _content_template() {
			?>
			<div class="elementor-label-wrapper">
				<div class="elementor-label">
					<span class="elementor-label-content-wrapper">
						<# if ( settings.icon ) { #>
						<span class="elementor-label-icon elementor-align-icon-{{ settings.icon_align }}">
							<i class="{{ settings.icon }}"></i>
						</span>
						<# } #>
						<span class="elementor-label-text">{{{ settings.text }}}</span>
					</span>
				</div>
			</div>
			<?php
		}

	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Label() );
	} );
