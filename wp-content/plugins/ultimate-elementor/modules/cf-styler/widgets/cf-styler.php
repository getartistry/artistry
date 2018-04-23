<?php
/**
 * UAEL CF7 Styler.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\CfStyler\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Cf7_Styler.
 */
class CfStyler extends Common_Widget {

	/**
	 * Retrieve CF7 Styler Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'CfStyler' );
	}

	/**
	 * Retrieve CF7 Styler Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'CfStyler' );
	}

	/**
	 * Retrieve CF7 Styler Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'CfStyler' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-frontend-script' ];
	}

	/**
	 * Function to integrate CF7 Forms.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_cf7_forms() {

		$field_options = array();

		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			$args               = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);
			$forms              = get_posts( $args );
			$field_options['0'] = 'Select';
			if ( $forms ) {
				foreach ( $forms as $form ) {
					$field_options[ $form->ID ] = $form->post_title;
				}
			}
		}

		if ( empty( $field_options ) ) {
			$field_options = array(
				'-1' => __( 'You have not added any Contact Form 7 yet.', 'uael' ),
			);
		}
		return $field_options;
	}

	/**
	 * Function to get CF7 Forms id.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_cf7_form_id() {
		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			$args  = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);
			$forms = get_posts( $args );

			if ( $forms ) {
				foreach ( $forms as $form ) {
					return $form->ID;
				}
			}
		}
		return -1;
	}


	/**
	 * Register CF7 Styler controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_input_style_controls();
		$this->register_radio_content_controls();

		$this->register_button_content_controls();
		$this->register_error_content_controls();

		// Style Tab.
		$this->register_typography_style_controls();

		$this->register_helpful_information();
	}

	/**
	 * Register CF7 Styler General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_general_field',
			[
				'label' => __( 'General', 'uael' ),
			]
		);
			$this->add_control(
				'select_form',
				[
					'label'   => __( 'Select Form', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $this->get_cf7_forms(),
					'default' => '0',
					'help'    => __( 'Choose the form that you want for this page for styling', 'uael' ),
				]
			);

			$this->add_control(
				'cf7_style',
				[
					'label'        => __( 'Field Style', 'uael' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'box',
					'options'      => [
						'box'       => __( 'Box', 'uael' ),
						'underline' => __( 'Underline', 'uael' ),
					],
					'prefix_class' => 'uael-cf7-style-',
				]
			);

			$this->add_control(
				'input_size',
				[
					'label'        => __( 'Field Size', 'uael' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'sm',
					'options'      => [
						'xs' => __( 'Extra Small', 'uael' ),
						'sm' => __( 'Small', 'uael' ),
						'md' => __( 'Medium', 'uael' ),
						'lg' => __( 'Large', 'uael' ),
						'xl' => __( 'Extra Large', 'uael' ),
					],
					'prefix_class' => 'uael-cf7-input-size-',
				]
			);

			$this->add_responsive_control(
				'cf7_input_padding',
				[
					'label'      => __( 'Field Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input:not([type="submit"]), {{WRAPPER}} .uael-cf7-style select,{{WRAPPER}} .uael-cf7-style textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type="checkbox"] + span:before,{{WRAPPER}} .uael-cf7-style input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-style-underline input[type="checkbox"] + span:before,{{WRAPPER}} .uael-cf7-style-underline input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline input[type="checkbox"]:checked + span:before' => 'font-size: calc({{BOTTOM}}{{UNIT}} / 1.2);',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-lower' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-upper' => 'font-size: {{BOTTOM}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'cf7_input_bgcolor',
				[
					'label'     => __( 'Field Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fafafa',
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]), {{WRAPPER}} .uael-cf7-style select, {{WRAPPER}} .uael-cf7-style textarea, {{WRAPPER}} .uael-cf7-style .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .uael-cf7-style .wpcf7-radio input[type="radio"] + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}} .uael-cf7-style input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-lower,{{WRAPPER}} .uael-cf7-style input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-upper,{{WRAPPER}} .uael-cf7-style input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_label_color',
				[
					'label'     => __( 'Label Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style .wpcf7 form.wpcf7-form:not(input)' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_input_color',
				[
					'label'     => __( 'Input Text / Placeholder Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style .wpcf7 input:not([type=submit]),{{WRAPPER}} .uael-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .uael-cf7-style .wpcf7 select, {{WRAPPER}} .uael-cf7-style .wpcf7 textarea, {{WRAPPER}} .uael-cf7-style .wpcf7 textarea::placeholder,{{WRAPPER}} .uael-cf7-style .uael-cf7-select-custom:after' => 'color: {{VALUE}};',
						'{{WRAPPER}}.elementor-widget-uael-cf7-styler .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.elementor-widget-uael-cf7-styler .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-style-box .wpcf7-radio input[type="radio"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{cf7_input_bgcolor.VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'input_border_style',
				[
					'label'       => __( 'Border Style', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'Default', 'uael' ),
						'solid'  => __( 'Solid', 'uael' ),
						'double' => __( 'Double', 'uael' ),
						'dotted' => __( 'Dotted', 'uael' ),
						'dashed' => __( 'Dashed', 'uael' ),
					],
					'condition'   => [
						'cf7_style' => 'box',
					],
					'selectors'   => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]), {{WRAPPER}} .uael-cf7-style select,{{WRAPPER}} .uael-cf7-style textarea,{{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'input_border_size',
				[
					'label'      => __( 'Border Width', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '1',
						'bottom' => '1',
						'left'   => '1',
						'right'  => '1',
						'unit'   => 'px',
					],
					'condition'  => [
						'cf7_style'           => 'box',
						'input_border_style!' => 'none',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]), {{WRAPPER}} .uael-cf7-style select,{{WRAPPER}} .uael-cf7-style textarea,{{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'input_border_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'cf7_style'           => 'box',
						'input_border_style!' => 'none',
					],
					'default'   => '#eaeaea',
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]), {{WRAPPER}} .uael-cf7-style select,{{WRAPPER}} .uael-cf7-style textarea,{{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_border_bottom',
				[
					'label'      => __( 'Border Size', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 20,
						],
					],
					'default'    => [
						'size' => '2',
						'unit' => 'px',
					],
					'condition'  => [
						'cf7_style' => 'underline',
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.uael-cf7-style-underline select,{{WRAPPER}}.uael-cf7-style-underline textarea' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
						'{{WRAPPER}}.uael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
					],
				]
			);

			$this->add_control(
				'cf7_border_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'cf7_style' => 'underline',
					],
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}}.uael-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.uael-cf7-style-underline select,{{WRAPPER}}.uael-cf7-style-underline textarea, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style-underline input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_ipborder_active',
				[
					'label'     => __( 'Border Active Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style .wpcf7 form input:not([type=submit]):focus, {{WRAPPER}} .uael-cf7-style select:focus, {{WRAPPER}} .uael-cf7-style .wpcf7 textarea:focus, {{WRAPPER}} .uael-cf7-style .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}} .uael-cf7-style .wpcf7-radio input[type="radio"]:checked + span:before' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_input_radius',
				[
					'label'      => __( 'Rounded Corners', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input:not([type="submit"]), {{WRAPPER}} .uael-cf7-style select, {{WRAPPER}} .uael-cf7-style textarea, {{WRAPPER}} .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '0',
						'bottom' => '0',
						'left'   => '0',
						'right'  => '0',
						'unit'   => 'px',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_text_align',
				[
					'label'     => __( 'Field Alignment', 'uael' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style .wpcf7, {{WRAPPER}} .uael-cf7-style input:not([type=submit]),{{WRAPPER}} .uael-cf7-style textarea' => 'text-align: {{VALUE}};',
						' {{WRAPPER}} .uael-cf7-style select' => 'text-align-last:{{VALUE}};',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register CF7 Styler Radio Input & Checkbox Input Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_radio_content_controls() {

		$this->start_controls_section(
			'cf7_radio_check_style',
			[
				'label' => __( 'Radio & Checkbox', 'uael' ),
			]
		);

			$this->add_control(
				'cf7_radio_check_adv',
				[
					'label'        => __( 'Override Current Style', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'uael' ),
					'label_off'    => __( 'No', 'uael' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'prefix_class' => 'uael-cf7-check-',
				]
			);

			$this->add_control(
				'cf7_radio_check_size',
				[
					'label'      => _x( 'Size', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'default'    => [
						'unit' => 'px',
						'size' => 20,
					],
					'range'      => [
						'px' => [
							'min' => 15,
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-moz-range-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-ms-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-moz-range-track' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-ms-fill-lower' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-ms-fill-upper' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'cf7_radio_check_bgcolor',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fafafa',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}}.uael-cf7-check-yes input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-ms-fill-lower,{{WRAPPER}}.uael-cf7-check-yes input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-ms-fill-upper,{{WRAPPER}}.uael-cf7-check-yes input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_selected_color',
				[
					'label'     => __( 'Selected Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{cf7_radio_check_bgcolor.VALUE}};',
						'{{WRAPPER}}.uael-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .uael-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_radio_label_color',
				[
					'label'     => __( 'Label Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .uael-cf7-style input[type="checkbox"] + span, .uael-cf7-style input[type="radio"] + span' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#eaeaea',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_width',
				[
					'label'      => __( 'Border Width', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 20,
						],
					],
					'default'    => [
						'size' => '1',
						'unit' => 'px',
					],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_radius',
				[
					'label'      => __( 'Checkbox Rounded Corners', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.uael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '0',
						'bottom' => '0',
						'left'   => '0',
						'right'  => '0',
						'unit'   => 'px',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register CF7 Styler Button Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_button_content_controls() {

		$this->start_controls_section(
			'cf7_submit_button',
			[
				'label' => __( 'Submit Button', 'uael' ),
			]
		);

			$this->add_responsive_control(
				'cf7_button_align',
				[
					'label'        => __( 'Button Alignment', 'uael' ),
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
							'title' => __( 'Justified', 'uael' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'default'      => 'left',
					'prefix_class' => 'uael%s-cf7-button-',
					'toggle'       => false,
				]
			);

			$this->add_control(
				'btn_size',
				[
					'label'        => __( 'Size', 'uael' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'sm',
					'options'      => [
						'xs' => __( 'Extra Small', 'uael' ),
						'sm' => __( 'Small', 'uael' ),
						'md' => __( 'Medium', 'uael' ),
						'lg' => __( 'Large', 'uael' ),
						'xl' => __( 'Extra Large', 'uael' ),
					],
					'prefix_class' => 'uael-cf7-btn-size-',
				]
			);

			$this->add_responsive_control(
				'cf7_button_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'after',
				]
			);
			$this->start_controls_tabs( 'tabs_button_style' );

				$this->start_controls_tab(
					'tab_button_normal',
					[
						'label' => __( 'Normal', 'uael' ),
					]
				);

					$this->add_control(
						'button_text_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .uael-cf7-style input[type="submit"]' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name'           => 'btn_background_color',
							'label'          => __( 'Background Color', 'uael' ),
							'types'          => [ 'classic', 'gradient' ],
							'fields_options' => [
								'color' => [
									'scheme' => [
										'type'  => Scheme_Color::get_type(),
										'value' => Scheme_Color::COLOR_4,
									],
								],
							],
							'selector'       => '{{WRAPPER}} .uael-cf7-style input[type="submit"]',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name'        => 'btn_border',
							'label'       => __( 'Border', 'uael' ),
							'placeholder' => '1px',
							'default'     => '1px',
							'selector'    => '{{WRAPPER}} .uael-cf7-style input[type="submit"]',
						]
					);

					$this->add_control(
						'btn_border_radius',
						[
							'label'      => __( 'Border Radius', 'uael' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%' ],
							'selectors'  => [
								'{{WRAPPER}} .uael-cf7-style input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'     => 'button_box_shadow',
							'selector' => '{{WRAPPER}} .uael-cf7-style input[type="submit"]',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_button_hover',
					[
						'label' => __( 'Hover', 'uael' ),
					]
				);

					$this->add_control(
						'btn_hover_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-cf7-style input[type="submit"]:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_hover_border_color',
						[
							'label'     => __( 'Border Hover Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-cf7-style input[type="submit"]:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name'     => 'button_background_hover_color',
							'label'    => __( 'Background Color', 'uael' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .uael-cf7-style input[type="submit"]:hover',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register CF7 Styler Error Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_error_content_controls() {

		$this->start_controls_section(
			'cf7_error_field',
			[
				'label' => __( 'Success / Error Message', 'uael' ),
			]
		);

			$this->add_control(
				'cf7_field_message',
				[
					'label'     => __( 'Field Validation', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

				$this->add_control(
					'cf7_highlight_style',
					[
						'label'        => __( 'Message Position', 'uael' ),
						'type'         => Controls_Manager::SELECT,
						'default'      => 'default',
						'options'      => [
							'default'      => __( 'Default', 'uael' ),
							'bottom_right' => __( 'Bottom Right Side of Field', 'uael' ),
						],
						'prefix_class' => 'uael-cf7-highlight-style-',
					]
				);

				$this->add_control(
					'cf7_message_color',
					[
						'label'     => __( 'Message Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_highlight_style' => 'default',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style span.wpcf7-not-valid-tip' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_message_highlight_color',
					[
						'label'     => __( 'Message Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'condition' => [
							'cf7_highlight_style' => 'bottom_right',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style span.wpcf7-not-valid-tip' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_message_bgcolor',
					[
						'label'     => __( 'Message Background Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => 'rgba(255, 0, 0, 0.6)',
						'condition' => [
							'cf7_highlight_style' => 'bottom_right',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style span.wpcf7-not-valid-tip' => 'background-color: {{VALUE}}; padding: 0.1em 0.8em;',
						],
					]
				);

				$this->add_control(
					'cf7_highlight_border',
					[
						'label'        => __( 'Highlight Borders', 'uael' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => __( 'Yes', 'uael' ),
						'label_off'    => __( 'No', 'uael' ),
						'return_value' => 'yes',
						'default'      => '',
						'prefix_class' => 'uael-cf7-highlight-',
					]
				);

				$this->add_control(
					'cf7_highlight_border_color',
					[
						'label'     => __( 'Highlight Border Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_highlight_border' => 'yes',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7-form-control.wpcf7-not-valid, {{WRAPPER}} .uael-cf7-style .wpcf7-form-control.wpcf7-not-valid .wpcf7-list-item-label:before' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'cf7_message_typo',
						'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
						'selector' => '{{WRAPPER}} .uael-cf7-style span.wpcf7-not-valid-tip',
					]
				);

			$this->add_control(
				'cf7_validation_message',
				[
					'label'     => __( 'Form Success / Error Validation', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

				$this->add_control(
					'cf7_success_message_color',
					[
						'label'     => __( 'Success Message Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7-mail-sent-ok' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_success_message_bgcolor',
					[
						'label'     => __( 'Success Message Background Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7-mail-sent-ok' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_success_border_color',
					[
						'label'     => __( 'Success Border Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#008000',
						'condition' => [
							'cf7_valid_border_size!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7-mail-sent-ok' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_message_color',
					[
						'label'     => __( 'Error Message Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_message_bgcolor',
					[
						'label'     => __( 'Error Message Background Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_border_color',
					[
						'label'     => __( 'Error Border Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_valid_border_size!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_border_size',
					[
						'label'      => __( 'Border Size', 'uael' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'default'    => [
							'top'    => '2',
							'bottom' => '2',
							'left'   => '2',
							'right'  => '2',
							'unit'   => 'px',
						],
						'selectors'  => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_message_radius',
					[
						'label'      => __( 'Rounded Corners', 'uael' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng, {{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_message_padding',
					[
						'label'      => __( 'Message Padding', 'uael' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng, {{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'cf7_validation_typo',
						'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
						'selector' => '{{WRAPPER}} .uael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .uael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .uael-cf7-style .wpcf7-mail-sent-ok,{{WRAPPER}} .uael-cf7-style .wpcf7-acceptance-missing',
					]
				);

		$this->end_controls_section();
	}


	/**
	 * Register CF7 Styler Input Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_input_style_controls() {

		$this->start_controls_section(
			'cf7_input_spacing',
			[
				'label' => __( 'Spacing', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'cf7_input_margin_top',
				[
					'label'      => __( 'Between Label & Input', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 60,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .uael-cf7-style select, {{WRAPPER}} .uael-cf7-style textarea, {{WRAPPER}} .uael-cf7-style span.wpcf7-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_input_margin_bottom',
				[
					'label'      => __( 'Between Fields', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 60,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .uael-cf7-style select, {{WRAPPER}} .uael-cf7-style textarea, {{WRAPPER}} .uael-cf7-style span.wpcf7-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}


	/**
	 * Register CF7 Styler Input Typography Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_typography_style_controls() {

		$this->start_controls_section(
			'cf7_typo',
			[
				'label' => __( 'Typography', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'cf7_label_typo',
				[
					'label'     => __( 'Form Label', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'form_label_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .uael-cf7-style .wpcf7 form.wpcf7-form label',
				]
			);

			$this->add_control(
				'cf7_input_typo',
				[
					'label'     => __( 'Input Text / Placeholder', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .uael-cf7-style .wpcf7 input:not([type=submit]), {{WRAPPER}} .uael-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .wpcf7 select,{{WRAPPER}} .uael-cf7-style .wpcf7 textarea, {{WRAPPER}} .uael-cf7-style .wpcf7 textarea::placeholder, {{WRAPPER}} .uael-cf7-style input[type=range]::-webkit-slider-thumb,{{WRAPPER}} .uael-cf7-style .uael-cf7-select-custom',
				]
			);

			$this->add_control(
				'btn_typography_label',
				[
					'label'     => __( 'Button', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'btn_typography',
					'label'    => __( 'Typography', 'uael' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .uael-cf7-style input[type=submit]',
				]
			);

			$this->add_control(
				'cf7_radio_check_typo',
				[
					'label'     => __( 'Radio Button & Checkbox', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'radio_check_typography',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selector'  => '{{WRAPPER}} .uael-cf7-style input[type="checkbox"] + span, .uael-cf7-style input[type="radio"] + span',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_helpful_information() {

		if ( parent::is_internal_links() ) {
			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => __( 'Helpful Information', 'uael' ),
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s Doc Link */
					'raw'             => sprintf( __( '%1$s Display input fields in column » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/how-to-set-multiple-column-fields-in-contact-form-7-styler-of-uael/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_2',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s Doc Link */
					'raw'             => sprintf( __( '%1$s Unable to see Checkbox / Radio / Acceptance control » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/unable-to-style-checkbox-radio-buttons-acceptance-control-using-contact-form-7-styler-of-uael/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Render Editor Script. Which will show error at editor.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_editor_script() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false ) {
			return;
		}

		$pre_url = wpcf7_get_request_uri();

		if ( strpos( $pre_url, 'admin-ajax.php' ) === false ) {
			return;
		}

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.uael-cf7-container' ).each( function() {

					var $node_id 	= '<?php echo $this->get_id(); ?>';
					var	scope 		= $( '[data-id="' + $node_id + '"]' );
					var selector 	= $(this);

					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

					if ( selector.find( 'div.wpcf7 > form' ).length < 1 ) {
						return;
					}

					selector.find( 'div.wpcf7 > form' ).each( function() {
						var $form = $( this );
						wpcf7.initForm( $form );
					} );
				});
			});
		</script>
		<?php
	}

	/**
	 * Render CF7 Styler output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return;
		}

		$settings      = $this->get_settings();
		$node_id       = $this->get_id();
		$field_options = array();
		$classname     = '';

		$args = array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
		);

		$forms              = get_posts( $args );
		$field_options['0'] = __( 'select', 'uael' );
		if ( $forms ) {
			foreach ( $forms as $form ) {
				$field_options[ $form->ID ] = $form->post_title;
			}
		}

		$this->add_inline_editing_attributes( 'uael_cf7_title' );
		$forms = $this->get_cf7_forms();

		$html = '';

		if ( ! empty( $forms ) && ! isset( $forms[-1] ) ) {
			if ( 0 == $settings['select_form'] ) {
				$html = __( 'Please select a Contact Form 7.', 'uael' );
			} else {
				?>
				<div class = "uael-cf7-container">
						<div class = "uael-cf7 uael-cf7-style elementor-clickable">
						<?php
						if ( $settings['select_form'] ) {
							echo do_shortcode( '[contact-form-7 id=' . $settings['select_form'] . ']' );
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			$html = __( 'You have not added any Contact Form 7 yet.', 'uael' );
		}
		echo $html;

		$this->render_editor_script();
	}

}

