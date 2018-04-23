<?php
/**
 * UAEL Buttons.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Buttons\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Repeater;
use Elementor\Widget_Button;
use Elementor\Group_Control_Background;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Buttons.
 */
class Buttons extends Common_Widget {

	/**
	 * Retrieve Buttons Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Buttons' );
	}

	/**
	 * Retrieve Buttons Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Buttons' );
	}

	/**
	 * Retrieve Buttons Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Buttons' );
	}

	/**
	 * Retrieve Button sizes.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Button Sizes.
	 */
	public static function get_button_sizes() {
		return Widget_Button::get_button_sizes();
	}


	/**
	 * Register Buttons controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		// Content Tab.
		$this->register_buttons_content_controls();

		// Style Tab.
		$this->register_styling_style_controls();
		$this->register_color_content_controls();
		$this->register_spacing_content_controls();
	}

	/**
	 * Register Buttons General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_buttons_content_controls() {

		$this->start_controls_section(
			'section_buttons',
			[
				'label' => __( 'Button', 'uael' ),
			]
		);

			$repeater = new Repeater();

				$repeater->start_controls_tabs( 'button_repeater' );

					$repeater->start_controls_tab(
						'button_general',
						[
							'label' => __( 'Genaral', 'uael' ),
						]
					);

					$repeater->add_control(
						'text',
						[
							'label'       => __( 'Text', 'uael' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => __( 'Click Me', 'uael' ),
							'placeholder' => __( 'Click Me', 'uael' ),
							'dynamic'     => [
								'active' => true,
							],
						]
					);

					$repeater->add_control(
						'link',
						[
							'label'    => __( 'Link', 'uael' ),
							'type'     => Controls_Manager::URL,
							'default'  => [
								'url'         => '#',
								'is_external' => '',
							],
							'dynamic'  => [
								'active' => true,
							],
							'selector' => '',
						]
					);

					$repeater->add_control(
						'icon',
						[
							'label'       => __( 'Icon', 'uael' ),
							'type'        => Controls_Manager::ICON,
							'label_block' => true,
							'default'     => '',
						]
					);

					$repeater->add_control(
						'icon_align',
						[
							'label'     => __( 'Icon Position', 'uael' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => 'left',
							'options'   => [
								'left'  => __( 'Before', 'uael' ),
								'right' => __( 'After', 'uael' ),
							],
							'condition' => [
								'icon!' => '',
							],
						]
					);

					$repeater->add_control(
						'icon_indent',
						[
							'label'     => __( 'Icon Spacing', 'uael' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => [
								'px' => [
									'max' => 50,
								],
							],
							'condition' => [
								'icon!' => '',
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$repeater->add_control(
						'css_id',
						[
							'label'       => __( 'CSS ID', 'uael' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => '',
							'label_block' => true,
							'title'       => __( 'Add your custom id WITHOUT the # key.', 'uael' ),
						]
					);

					$repeater->add_control(
						'css_classes',
						[
							'label'       => __( 'CSS Classes', 'uael' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => '',
							'label_block' => true,
							'title'       => __( 'Add space separated custom classes WITHOUT the dot.', 'uael' ),
						]
					);

				$repeater->end_controls_tab();

				$repeater->start_controls_tab(
					'button_design',
					[
						'label' => __( 'Design', 'uael' ),
					]
				);

					$repeater->add_control(
						'html_message',
						[
							'type'            => Controls_Manager::RAW_HTML,
							'raw'             => __( 'Set custom styles that will only affect this specific button.', 'uael' ),
							'content_classes' => 'elementor-control-field-description',
						]
					);

					$repeater->add_control(
						'color_options',
						[
							'label'     => __( 'Normal', 'uael' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$repeater->add_control(
						'btn_text_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button' => 'color: {{VALUE}};',
							],
						]
					);

					$repeater->add_control(
						'btn_background_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button' => 'background-color: {{VALUE}};',
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name'      => 'btn_border',
							'label'     => __( 'Border', 'uael' ),
							'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button',
							'separator' => 'before',
						]
					);

					$repeater->add_control(
						'btn_border_radius',
						[
							'label'      => __( 'Border Radius', 'uael' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%' ],
							'selectors'  => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$repeater->add_control(
						'hover_options',
						[
							'label' => __( 'Hover', 'uael' ),
							'type'  => Controls_Manager::HEADING,
						]
					);

					$repeater->add_control(
						'btn_text_hover_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$repeater->add_control(
						'btn_background_hover_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button:hover' => 'background-color: {{VALUE}};',
							],
						]
					);

					$repeater->add_control(
						'btn_border_hover_color',
						[
							'label'     => __( 'Border Hover Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-button:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name'     => 'btn_typography',
							'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
							'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} a.elementor-button',
						]
					);

				$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'buttons',
				[
					'label'       => __( 'Buttons', 'uael' ),
					'type'        => Controls_Manager::REPEATER,
					'show_label'  => true,
					'fields'      => array_values( $repeater->get_controls() ),
					'title_field' => '{{{ text }}}',
					'default'     => [
						[
							'text' => __( 'Click Me #1', 'uael' ),
						],
						[
							'text' => __( 'Click Me #2', 'uael' ),
						],
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Buttons Spacing Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_spacing_content_controls() {
		$this->start_controls_section(
			'general_spacing',
			[
				'label' => __( 'Spacing', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'gap',
				[
					'label'      => __( 'Space between buttons', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 1000,
						],
					],
					'default'    => [
						'size' => 10,
						'unit' => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-dual-button-wrap .uael-button-wrapper' => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2) ; margin-left: calc( {{SIZE}}{{UNIT}} / 2);',
						'{{WRAPPER}} .uael-dual-button-wrap' => 'margin-right: calc( -{{SIZE}}{{UNIT}} / 2) ; margin-left: calc( -{{SIZE}}{{UNIT}} / 2);',
						'(desktop){{WRAPPER}}.uael-button-stack-desktop .uael-dual-button-wrap .uael-button-wrapper'  => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2 ); margin-top: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: 0; margin-left: 0;',
						'(desktop){{WRAPPER}}.uael-button-stack-desktop .uael-dual-button-wrap .uael-button-wrapper:last-child' => 'margin-bottom: 0;',
						'(desktop){{WRAPPER}}.uael-button-stack-desktop .uael-dual-button-wrap .uael-button-wrapper:first-child' => 'margin-top: 0;',
						'(tablet){{WRAPPER}}.uael-button-stack-tablet .uael-dual-button-wrap .uael-button-wrapper'        => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2 ); margin-top: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: 0; margin-left: 0;',
						'(tablet){{WRAPPER}}.uael-button-stack-tablet .uael-dual-button-wrap .uael-button-wrapper:last-child' => 'margin-bottom: 0;',
						'(tablet){{WRAPPER}}.uael-button-stack-tablet .uael-dual-button-wrap .uael-button-wrapper:first-child' => 'margin-top: 0;',
						'(mobile){{WRAPPER}}.uael-button-stack-mobile .uael-dual-button-wrap .uael-button-wrapper'        => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2 ); margin-top: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: 0; margin-left: 0;',
						'(mobile){{WRAPPER}}.uael-button-stack-mobile .uael-dual-button-wrap .uael-button-wrapper:last-child' => 'margin-bottom: 0;',
						'(mobile){{WRAPPER}}.uael-button-stack-mobile .uael-dual-button-wrap .uael-button-wrapper:first-child' => 'margin-top: 0;',
					],
				]
			);

			$this->add_control(
				'stack_on',
				[
					'label'        => __( 'Stack', 'uael' ),
					'description'  => __( 'Choose on what breakpoint where the buttons will stack.', 'uael' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => '',
					'options'      => [
						''        => __( 'None', 'uael' ),
						'desktop' => __( 'Desktop', 'uael' ),
						'tablet'  => __( 'Tablet', 'uael' ),
						'mobile'  => __( 'Mobile', 'uael' ),
					],
					'prefix_class' => 'uael-button-stack-',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Buttons Colors Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_color_content_controls() {

		$this->start_controls_section(
			'general_colors',
			[
				'label' => __( 'Styling', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs( '_button_style' );

				$this->start_controls_tab(
					'_button_normal',
					[
						'label' => __( 'Normal', 'uael' ),
					]
				);

					$this->add_control(
						'all_text_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} a.elementor-button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'all_background_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} a.elementor-button' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name'     => 'all_border',
							'label'    => __( 'Border', 'uael' ),
							'selector' => '{{WRAPPER}} .elementor-button',
						]
					);

					$this->add_control(
						'all_border_radius',
						[
							'label'      => __( 'Border Radius', 'uael' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%' ],
							'selectors'  => [
								'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'     => 'all_button_box_shadow',
							'selector' => '{{WRAPPER}} .elementor-button',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'all_button_hover',
					[
						'label' => __( 'Hover', 'uael' ),
					]
				);

					$this->add_control(
						'all_hover_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.elementor-button:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'all_background_hover_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} a.elementor-button:hover' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'all_border_hover_color',
						[
							'label'     => __( 'Border Hover Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} a.elementor-button:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'      => 'all_button_hover_box_shadow',
							'selector'  => '{{WRAPPER}} .elementor-button:hover',
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Buttons Styling Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_styling_style_controls() {

		$this->start_controls_section(
			'section_styling',
			[
				'label' => __( 'Design', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'size',
				[
					'label'       => __( 'Size', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'sm',
					'label_block' => true,
					'options'     => self::get_button_sizes(),
				]
			);

			$this->add_responsive_control(
				'padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'all_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.elementor-button',
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label'        => __( 'Alignment', 'uael' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => __( 'Justify', 'uael' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'default'      => 'center',
					'toggle'       => false,
					'prefix_class' => 'uael%s-button-halign-',
				]
			);

			$this->add_control(
				'hover_animation',
				[
					'label' => __( 'Hover Animation', 'uael' ),
					'type'  => Controls_Manager::HOVER_ANIMATION,
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Render button widget text.
	 *
	 * @param array $button The item settings array.
	 * @param int   $i The index id.
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_button_text( $button, $i ) {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute( 'icon-align_' . $i, 'class', 'elementor-align-icon-' . $button['icon_align'] );
		$this->add_render_attribute( 'icon-align_' . $i, 'class', 'elementor-button-icon' );

		$this->add_render_attribute( 'btn-text_' . $i, 'class', 'elementor-button-text' );
		$this->add_render_attribute( 'btn-text_' . $i, 'class', 'elementor-inline-editing' );

		$text_key = $this->get_repeater_setting_key( 'text', 'buttons', $i );
		$this->add_inline_editing_attributes( $text_key, 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $button['icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align_' . $i ); ?>>
				<i class="<?php echo esc_attr( $button['icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'btn-text_' . $i ); ?> data-elementor-setting-key="<?php echo $text_key; ?>" data-elementor-inline-editing-toolbar="none"><?php echo $button['text']; ?></span>
		</span>
		<?php
	}

	/**
	 * Render Buttons output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		$node_id  = $this->get_id();
		ob_start();
		?>
		<div class="uael-dual-button-outter-wrap">
			<div class="uael-dual-button-wrap">
				<?php
				for ( $i = 0; $i < count( $settings['buttons'] ); $i++ ) :
					if ( ! is_array( $settings['buttons'][ $i ] ) ) {
						continue;
					}
					$button = $settings['buttons'][ $i ];

					$this->add_render_attribute( 'button_wrap_' . $i, 'class', 'uael-button-wrapper elementor-button-wrapper uael-dual-button' );
					$this->add_render_attribute( 'button_wrap_' . $i, 'class', 'elementor-repeater-item-' . $button['_id'] );
					$this->add_render_attribute( 'button_wrap_' . $i, 'class', 'uael-dual-button-' . $i );

					$this->add_render_attribute( 'button_' . $i, 'class', 'elementor-button-link elementor-button' );
					$this->add_render_attribute( 'button_' . $i, 'class', 'elementor-size-' . $settings['size'] );
					$this->add_render_attribute( 'button_' . $i, 'class', $button['css_classes'] );
					$this->add_render_attribute( 'button_' . $i, 'id', $button['css_id'] );

					if ( ! empty( $button['link']['url'] ) ) {
						$this->add_render_attribute( 'button_' . $i, 'href', $button['link']['url'] );
						$this->add_render_attribute( 'button_' . $i, 'class', 'elementor-button-link' );

						if ( $button['link']['is_external'] ) {
							$this->add_render_attribute( 'button_' . $i, 'target', '_blank' );
						}

						if ( $button['link']['nofollow'] ) {
							$this->add_render_attribute( 'button_' . $i, 'rel', 'nofollow' );
						}
					}

					if ( $settings['hover_animation'] ) {
						$this->add_render_attribute( 'button_' . $i, 'class', 'elementor-animation-' . $settings['hover_animation'] );
					}
				?>
				<div <?php echo $this->get_render_attribute_string( 'button_wrap_' . $i ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'button_' . $i ); ?>>
						<?php $this->render_button_text( $button, $i ); ?>
					</a>
				</div>
				<?php endfor; ?>
			</div>
		</div>
	<?php
		$html = ob_get_clean();
		echo $html;
	}

	/**
	 * Render Dual Color Heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="uael-dual-button-outter-wrap">
			<div class="uael-dual-button-wrap">
				<#
				if ( settings.buttons ) {

					var counter = 1;

					_.each( settings.buttons, function( item ) {

						var button_wrap = 'elementor-repeater-item-' + item._id + ' uael-dual-button-' + counter;

						var button_class = 'elementor-size-' + settings.size + ' ' + item.css_classes;

						button_wrap += ' elementor-animation-' + settings.hover_animation;
				#>
				<div class="uael-button-wrapper elementor-button-wrapper uael-dual-button {{ button_wrap }}">
					<a id="{{ item.css_id }}" href="{{ item.link.url }}" class="elementor-button-link elementor-button {{ button_class }}">
						<span class="elementor-button-content-wrapper">
							<#
							if ( '' != item.icon ) {
								var icon_align = 'elementor-align-icon-' + item.icon_align;
							#>
							<span class="elementor-button-icon {{ icon_align }}">
								<i class="{{ item.icon }}" aria-hidden="true"></i>
							</span>
							<# } #>
							<span class="elementor-button-text elementor-inline-editing" data-elementor-setting-key="settings.buttons.{{ counter - 1 }}.text" data-elementor-inline-editing-toolbar="none">{{ item.text }}</span>
						</span>
					</a>
				</div>
				<#
					counter++;
					});
				}
				#>
			</div>
		</div>
		<?php
	}

}

