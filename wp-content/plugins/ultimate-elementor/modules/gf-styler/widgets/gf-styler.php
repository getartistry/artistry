<?php
/**
 * UAEL Gravity Forms Styler.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\GfStyler\Widgets;


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
use UltimateElementor\Classes\UAEL_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Gf_Styler.
 */
class GfStyler extends Common_Widget {

	/**
	 * Retrieve GForms Styler Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'GfStyler' );
	}

	/**
	 * Retrieve GForms Styler Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'GfStyler' );
	}

	/**
	 * Retrieve GForms Styler Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'GfStyler' );
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
	 * Render Gravity Form Script.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_gf_script() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false ) {
			return;
		}

		if ( class_exists( 'GFCommon' ) ) {

			$gf_forms = \RGFormsModel::get_forms( null, 'title' );

			foreach ( $gf_forms as $form ) {

				if ( '0' != $form->id ) {
					gravity_form_enqueue_scripts( $form->id );
				}
			};
		}

	}

	/**
	 * Returns all gravity forms with ids
	 *
	 * @since 0.0.1
	 * @return array Key Value paired array.
	 */
	protected function get_gravity_forms() {

		$field_options = array();

		if ( class_exists( 'GFForms' ) ) {
			$forms              = \RGFormsModel::get_forms( null, 'title' );
			$field_options['0'] = 'Select';
			if ( is_array( $forms ) ) {
				foreach ( $forms as $form ) {
					$field_options[ $form->id ] = $form->title;
				}
			}
		}

		if ( empty( $field_options ) ) {
			$field_options = array(
				'-1' => __( 'You have not added any Gravity Forms yet.', 'uael' ),
			);
		}

		return $field_options;
	}

	/**
	 * Returns gravity forms id
	 *
	 * @since 0.0.1
	 * @return integer Key id for Gravity Form.
	 */
	protected function get_gravity_form_id() {
		if ( class_exists( 'GFForms' ) ) {
			$forms = \RGFormsModel::get_forms( null, 'title' );

			if ( is_array( $forms ) ) {
				foreach ( $forms as $form ) {
					return $form->id;
				}
			}
		}

		return -1;
	}


	/**
	 * Register GForms Styler controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_input_style_controls();
		$this->register_radio_content_controls();
		$this->register_button_content_controls();
		$this->register_error_style_controls();
		$this->register_spacing_controls();
		$this->register_typography_controls();
	}

	/**
	 * Register GForms Styler General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'General', 'uael' ),
			]
		);

		$this->add_control(
			'form_id',
			[
				'label'   => __( 'Select Form', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_gravity_forms(),
				'default' => '0',
				'help'    => __( 'Choose the form that you want for this page for styling', 'uael' ),

			]
		);

		$this->add_control(
			'form_ajax_option',
			[
				'label'        => __( 'Enable AJAX Form Submission', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'default'      => 'true',
				'label_block'  => false,
				'prefix_class' => '',
			]
		);

		$this->add_control(
			'mul_form_option',
			[
				'label'        => __( 'Keyboard Tab Key Support', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'default'      => 'no',
				'label_block'  => false,
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'form_tab_index_option',
			[
				'label'     => __( 'Set Tabindex Value', 'uael' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => [
					'mul_form_option' => 'yes',
				],
			]
		);
		if ( parent::is_internal_links() ) {

			$this->add_control(
				'help_doc_tabindex',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( __( 'You need to change above tabindex value if pressing tab on your keyboard not works as expected. Please read %1$s this article %2$s for more information.', 'uael' ), '<a href="https://uaelementor.com/docs/gravity-form-tab-index/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
					'condition'       => [
						'mul_form_option' => 'yes',
					],
				]
			);
		}

		$this->add_control(
			'form_title_option',
			[
				'label'       => __( 'Title & Description', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'yes',
				'label_block' => false,
				'options'     => [
					'yes'  => __( 'From Gravity Form', 'uael' ),
					'no'   => __( 'Enter Your Own', 'uael' ),
					'none' => __( 'None', 'uael' ),
				],
			]
		);

		$this->add_control(
			'form_title',
			[
				'label'     => __( 'Form Title', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'form_title_option' => 'no',
				],
				'dynamic'   => [
					'active' => true,
				],

			]
		);

		$this->add_control(
			'form_desc',
			[
				'label'     => __( 'Form Description', 'uael' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => [
					'form_title_option' => 'no',
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_responsive_control(
			'form_title_desc_align',
			[
				'label'     => __( 'Title & Description Alignment', 'uael' ),
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
				'default'   => 'left',
				'condition' => [
					'form_title_option!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-gf-form-desc,{{WRAPPER}} .uael-gf-form-title' => 'text-align: {{VALUE}};',
				],
				'toggle'    => false,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register GForms Styler Input Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_input_style_controls() {
		$this->start_controls_section(
			'form_input_style',
			[
				'label' => __( 'Form Fields', 'uael' ),
			]
		);

		$this->add_control(
			'gf_style',
			[
				'label'        => __( 'Field Style', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'box',
				'options'      => [
					'box'       => __( 'Box', 'uael' ),
					'underline' => __( 'Underline', 'uael' ),
				],
				'prefix_class' => 'uael-gf-style-',
			]
		);

		$this->add_control(
			'form_input_size',
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
				'prefix_class' => 'uael-gf-input-size-',
			]
		);

		$this->add_responsive_control(
			'form_input_padding',
			[
				'label'      => __( 'Field Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper form .gform_body input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]), {{WRAPPER}} .uael-gf-style .gform_wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .uael-gf-style .ginput_container select' => 'padding-top: calc( {{TOP}}{{UNIT}} - 2{{UNIT}} ); padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: calc( {{BOTTOM}}{{UNIT}} - 2{{UNIT}} ); padding-left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .uael-gf-check-style .gfield_checkbox input[type="checkbox"] + label:before, {{WRAPPER}} .uael-gf-check-style .gfield_radio input[type="radio"] + label:before' => 'height: {{BOTTOM}}{{UNIT}}; width: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .uael-gf-check-style .gfield_checkbox input[type="checkbox"]:checked + label:before'  => 'font-size: calc( {{BOTTOM}}{{UNIT}} / 1.2 );',
				],
			]
		);

		$this->add_control(
			'form_input_bgcolor',
			[
				'label'     => __( 'Field Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fafafa',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=email],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=text],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=url],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=tel],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=number],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=date],{{WRAPPER}} .uael-gf-style .gform_wrapper select,{{WRAPPER}} .uael-gf-style .gform_wrapper textarea, {{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}} .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-gf-style .gsection' => 'border-bottom-color:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_label_color',
			[
				'label'     => __( 'Label Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gfield_label,{{WRAPPER}} .uael-gf-style .gfield_checkbox li label,{{WRAPPER}} .uael-gf-style .gfield_radio li label,{{WRAPPER}} .uael-gf-style .gsection_title, {{WRAPPER}} .uael-gf-style .gfield_html, {{WRAPPER}} .uael-gf-style .ginput_product_price,{{WRAPPER}} .uael-gf-style .ginput_product_price_label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_input_color',
			[
				'label'     => __( 'Input Text / Placeholder Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]),{{WRAPPER}} .uael-gf-style .ginput_container select, {{WRAPPER}} .uael-gf-style .ginput_container textarea,{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield input::placeholder,{{WRAPPER}} .uael-gf-style .ginput_container textarea::placeholder,{{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"]:checked + label:before,{{WRAPPER}} .uael-gf-style .uael-gf-select-custom:after ' => 'color: {{VALUE}};',
					'{{WRAPPER}} .uael-gf-style .gfield_radio input[type="radio"]:checked + label:before' => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{form_input_bgcolor.VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_input_desc_color',
			[
				'label'     => __( 'Field Description Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield .gfield_description,{{WRAPPER}} .uael-gf-style .ginput_container_name input + label,{{WRAPPER}} .uael-gf-style .ginput_container_name select + label,{{WRAPPER}} .uael-gf-style .gfield_time_hour label, {{WRAPPER}} .uael-gf-style .gfield_time_hour label, {{WRAPPER}} .uael-gf-style .gfield_time_minute label, {{WRAPPER}} .uael-gf-style .ginput_container_address label, {{WRAPPER}} .uael-gf-style .ginput_container_total span, {{WRAPPER}} .uael-gf-style .ginput_shipping_price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_required_color',
			[
				'label'     => __( 'Required Asterisk Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield_required' => 'color: {{VALUE}};',
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
					'gf_style' => 'box',
				],
				'selectors'   => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=email],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=text],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=url],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=tel],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=number],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=date],{{WRAPPER}} .uael-gf-style .gform_wrapper select,{{WRAPPER}} .uael-gf-style .gform_wrapper textarea,{{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}} .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'border-style: {{VALUE}};',
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
					'gf_style'            => 'box',
					'input_border_style!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=email],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=text],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=url],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=tel],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=number],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=date],{{WRAPPER}} .uael-gf-style .gform_wrapper select,{{WRAPPER}} .uael-gf-style .gform_wrapper textarea,{{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}} .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label'     => __( 'Border Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'gf_style'            => 'box',
					'input_border_style!' => 'none',
				],
				'default'   => '#eaeaea',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=email],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=text],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=url],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=tel],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=number],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=date],{{WRAPPER}} .uael-gf-style .gform_wrapper select,{{WRAPPER}} .uael-gf-style .gform_wrapper textarea,{{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}} .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'gf_border_bottom',
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
					'gf_style' => 'underline',
				],
				'selectors'  => [
					'{{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=email], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=text], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=url], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=tel], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=number], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=date], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper select, {{WRAPPER}}.uael-gf-style-underline .gform_wrapper textarea' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
					'{{WRAPPER}}.uael-gf-style-underline .gfield_checkbox input[type="checkbox"] + label:before, {{WRAPPER}}.uael-gf-style-underline .gfield_radio input[type="radio"] + label:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
				],
			]
		);

		$this->add_control(
			'gf_border_color',
			[
				'label'     => __( 'Border Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'gf_style' => 'underline',
				],
				'default'   => '#c4c4c4',
				'selectors' => [
					'{{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=email], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=text], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=url], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=tel], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=number], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper input[type=date], {{WRAPPER}}.uael-gf-style-underline .gform_wrapper select, {{WRAPPER}}.uael-gf-style-underline .gform_wrapper textarea, {{WRAPPER}}.uael-gf-style-underline .gfield_checkbox input[type="checkbox"] + label:before, {{WRAPPER}}.uael-gf-style-underline .gfield_radio input[type="radio"] + label:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_border_radius',
			[
				'label'      => __( 'Rounded Corners', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=email],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=text],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=url],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=tel],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=number],{{WRAPPER}} .uael-gf-style .gform_wrapper input[type=date],{{WRAPPER}} .uael-gf-style .gform_wrapper select,{{WRAPPER}} .uael-gf-style .gform_wrapper textarea,{{WRAPPER}} .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Register GForms Styler Radio & Checkbox Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_radio_content_controls() {
		$this->start_controls_section(
			'gf_radio_check_style',
			[
				'label' => __( 'Radio & Checkbox', 'uael' ),
			]
		);

		$this->add_control(
			'gf_radio_check_custom',
			[
				'label'        => __( 'Override Current Style', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'uael-gf-check-',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'gf_radio_check_size',
			[
				'label'      => _x( 'Size', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'condition'  => [
					'gf_radio_check_custom!' => '',
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
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-check-style .gfield_checkbox input[type="checkbox"] + label:before, {{WRAPPER}}.uael-gf-check-yes .uael-gf-check-style .gfield_radio input[type="radio"] + label:before' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-check-style .gfield_checkbox input[type="checkbox"]:checked + label:before'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
				],
			]
		);

		$this->add_control(
			'gf_radio_check_bgcolor',
			[
				'label'     => __( 'Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'gf_radio_check_custom!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'background-color: {{VALUE}};',
				],
				'default'   => '#fafafa',
			]
		);

		$this->add_control(
			'gf_selected_color',
			[
				'label'     => __( 'Selected Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'gf_radio_check_custom!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox input[type="checkbox"]:checked + label:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_radio input[type="radio"]:checked + label:before'    => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{gf_radio_check_bgcolor.VALUE}};',
				],
			]
		);

		$this->add_control(
			'gf_select_color',
			[
				'label'     => __( 'Label Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'gf_radio_check_custom!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox li label,{{WRAPPER}} .uael-gf-style .gfield_radio li label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gf_check_border_color',
			[
				'label'     => __( 'Border Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'condition' => [
					'gf_radio_check_custom!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'gf_check_border_width',
			[
				'label'      => __( 'Border Width', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default'    => [
					'size' => '1',
					'unit' => 'px',
				],
				'condition'  => [
					'gf_radio_check_custom!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before,{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_radio input[type="radio"] + label:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'gf_check_border_radius',
			[
				'label'      => __( 'Checkbox Rounded Corners', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition'  => [
					'gf_radio_check_custom!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}}.uael-gf-check-yes .uael-gf-style .gfield_checkbox input[type="checkbox"] + label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * Register GForms Styler Button Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_button_content_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Submit Button', 'uael' ),
			]
		);
		$this->add_responsive_control(
			'button_align',
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
				'prefix_class' => 'uael%s-gf-button-',
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
				'prefix_class' => 'uael-gf-btn-size-',
			]
		);

		$this->add_responsive_control(
			'gf_button_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .uael-gf-style input[type="submit"]' => 'color: {{VALUE}};',
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
				'selector'       => '{{WRAPPER}} .uael-gf-style input[type="submit"]',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'btn_border',
				'label'       => __( 'Border', 'uael' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .uael-gf-style input[type="submit"]',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .uael-gf-style input[type="submit"]',
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
					'{{WRAPPER}} .uael-gf-style input[type="submit"]:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gf_button_hover_border_color',
			[
				'label'     => __( 'Border Hover Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover_color',
				'label'    => __( 'Background Color', 'uael' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .uael-gf-style input[type="submit"]:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register GForms Styler Error Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_error_style_controls() {
		$this->start_controls_section(
			'form_error_field',
			[
				'label' => __( 'Success / Error Message', 'uael' ),
			]
		);
		$this->add_control(
			'form_error',
			[
				'label'     => __( 'Field Validation', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'form_error_msg_color',
			[
				'label'     => __( 'Message Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield_description.validation_message' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'gf_message_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gform_wrapper .validation_message',
			]
		);

		$this->add_control(
			'form_error_field_background',
			[
				'label'        => __( 'Advanced Settings', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'uael-gf-error-',
			]
		);

			$this->add_control(
				'form_error_field_bgcolor',
				[
					'label'     => __( 'Field Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'form_error_field_background!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-gf-error-yes .gform_wrapper .gfield.gfield_error' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'form_error_border_color',
				[
					'label'     => __( 'Highlight Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ff0000',
					'condition' => [
						'form_error_field_background!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-gf-error-yes .gform_wrapper li.gfield_error input:not([type="submit"]):not([type="button"]):not([type="image"]), {{WRAPPER}}.uael-gf-error-yes .gform_wrapper .gfield_error .ginput_container select, {{WRAPPER}}.uael-gf-error-yes .gform_wrapper .gfield_error .ginput_container textarea,{{WRAPPER}}.uael-gf-error-yes .gform_wrapper li.gfield.gfield_error, {{WRAPPER}}.uael-gf-error-yes .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning, {{WRAPPER}}.uael-gf-error-yes li.gfield_error .gfield_checkbox input[type="checkbox"] + label:before, {{WRAPPER}}.uael-gf-error-yes li.gfield_error .gfield_radio input[type="radio"] + label:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}}.uael-gf-error-yes .gform_wrapper li.gfield_error input[type="text"]' =>
						'border: {{input_border_size.BOTTOM}}px {{input_border_style.VALUE}} {{VALUE}} !important;',
						'{{WRAPPER}}.uael-gf-style-underline.uael-gf-error-yes .gform_wrapper li.gfield_error input[type="text"]' =>
						'border-width: 0 0 {{gf_border_bottom.SIZE}}px 0 !important; border-style: solid; border-color:{{VALUE}};',
					],
				]
			);

		$this->add_control(
			'form_validation_message',
			[
				'label'     => __( 'Form Error Validation', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_valid_message_color',
			[
				'label'     => __( 'Error Message Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'form_valid_bgcolor',
			[
				'label'     => __( 'Error Message Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_valid_border_color',
			[
				'label'     => __( 'Error Message Border Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'form_border_size',
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
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'border-top: {{TOP}}{{UNIT}}; border-right: {{RIGHT}}{{UNIT}}; border-bottom: {{BOTTOM}}{{UNIT}}; border-left: {{LEFT}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'form_valid_border_radius',
			[
				'label'      => __( 'Rounded Corners', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_valid_message_padding',
			[
				'label'      => __( 'Message Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '10',
					'bottom' => '10',
					'left'   => '10',
					'right'  => '10',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cf7_error_validation_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gform_wrapper div.validation_error',
			]
		);

		$this->add_control(
			'form_success_message',
			[
				'label'     => __( 'Form Success Validation', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_success_message_color',
			[
				'label'     => __( 'Success Message Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-style .gform_confirmation_message'   => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cf7_success_validation_typo',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gform_confirmation_message',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register GForms Styler Spacing Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_spacing_controls() {

		$this->start_controls_section(
			'form_spacing',
			[
				'label' => __( 'Spacing', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'form_title_margin_bottom',
				[
					'label'      => __( 'Form Title Bottom Margin', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'condition'  => [
						'form_title_option!' => 'none',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-form-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'form_desc_margin_bottom',
				[
					'label'      => __( 'Form Description Bottom Margin', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-form-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
					'condition'  => [
						'form_title_option!' => 'none',
					],
				]
			);

			$this->add_responsive_control(
				'form_fields_margin',
				[
					'label'      => __( 'Between Two Fields', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-style .gform_wrapper li.gfield' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'form_label_margin_bottom',
				[
					'label'      => __( 'Label Bottom Spacing', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-style .gfield_label, {{WRAPPER}} .uael-gf-style .gsection_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'form_input_margin_top',
				[
					'label'      => __( 'Input Top Spacing', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-style .ginput_container' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'form_input_margin_bottom',
				[
					'label'      => __( 'Input Bottom Spacing', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-gf-style .ginput_container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register GForms Styler Typography Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_typography_controls() {

		$this->start_controls_section(
			'form_typo',
			[
				'label' => __( 'Typography', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'form_title_typo',
			[
				'label'     => __( 'Form Title', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'form_title_option!' => 'none',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'form_title_tag',
			[
				'label'     => __( 'HTML Tag', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'  => __( 'H1', 'uael' ),
					'h2'  => __( 'H2', 'uael' ),
					'h3'  => __( 'H3', 'uael' ),
					'h4'  => __( 'H4', 'uael' ),
					'h5'  => __( 'H5', 'uael' ),
					'h6'  => __( 'H6', 'uael' ),
					'div' => __( 'div', 'uael' ),
					'p'   => __( 'p', 'uael' ),
				],
				'condition' => [
					'form_title_option!' => 'none',
				],
				'default'   => 'h3',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .uael-gf-form-title',
				'condition' => [
					'form_title_option!' => 'none',
				],

			]
		);
		$this->add_control(
			'form_title_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'condition' => [
					'form_title_option!' => 'none',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-form-title' => 'color: {{VALUE}};',
				],

			]
		);

		$this->add_control(
			'form_desc_typo',
			[
				'label'     => __( 'Form Description', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'form_title_option!' => 'none',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'desc_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'selector'  => '{{WRAPPER}} .uael-gf-form-desc',
				'condition' => [
					'form_title_option!' => 'none',
				],
			]
		);
		$this->add_control(
			'form_desc_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'form_title_option!' => 'none',
				],
				'default'   => '',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .uael-gf-form-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_input_typo',
			[
				'label' => __( 'Form Fields', 'uael' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_label_typography',
				'label'    => 'Label Typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gfield_label,{{WRAPPER}} .uael-gf-style .gfield_checkbox li label,{{WRAPPER}} .uael-gf-style .gfield_radio li label, {{WRAPPER}} .uael-gf-style .gsection_title, {{WRAPPER}} .uael-gf-style .ginput_product_price,{{WRAPPER}} .uael-gf-style .ginput_product_price_label',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'label'    => 'Text Typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]), {{WRAPPER}} .uael-gf-style .ginput_container select,{{WRAPPER}} .uael-gf-style .ginput_container textarea, {{WRAPPER}} .uael-gf-style .uael-gf-select-custom',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_desc_typography',
				'label'    => 'Description Typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .uael-gf-style .gform_wrapper .gfield .gfield_description, {{WRAPPER}} .uael-gf-style .ginput_container_name input + label, {{WRAPPER}} .uael-gf-style .ginput_container_name select + label, {{WRAPPER}} .uael-gf-style .gfield_time_hour label, {{WRAPPER}} .uael-gf-style .gfield_time_hour label, {{WRAPPER}} .uael-gf-style .gfield_time_minute label, {{WRAPPER}} .uael-gf-style .ginput_container_address label, {{WRAPPER}} .uael-gf-style .ginput_container_total span, {{WRAPPER}} .uael-gf-style .ginput_shipping_price',
			]
		);

		$this->add_control(
			'btn_typography_label',
			[
				'label'     => __( 'Button Typography', 'uael' ),
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
				'selector' => '{{WRAPPER}} .uael-gf-style input[type=submit]',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render GForms Styler output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		ob_start();
		include 'template.php';
		$html = ob_get_clean();
		echo $html;

		$this->render_gf_script();
	}

}

