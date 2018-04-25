<?php
namespace ElementPack\Modules\UserRegister\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

use ElementPack\Modules\UserRegister\Skins;
use ElementPack\Element_Pack_Loader;

use ElementPack\Modules\UserRegister\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class User_Register extends Widget_Base {

	public function get_name() {
		return 'bdt-user-register';
	}

	public function get_title() {
		return esc_html__( 'User Register', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Dropdown( $this ) );
		$this->add_skin( new Skins\Skin_Modal( $this ) );
	}

	protected function _register_controls() {
		$this->register_layout_section_controls();
	}

	private function register_layout_section_controls() {
		$this->start_controls_section(
			'section_forms_layout',
			[
				'label' => esc_html__( 'Forms Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'labels_title',
			[
				'label' => esc_html__( 'Labels', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on' => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'fields_title',
			[
				'label' => esc_html__( 'Fields', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => esc_html__( 'Input Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'bdthemes-element-pack' ),
					'default' => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'large' => esc_html__( 'Large', 'bdthemes-element-pack' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'button_title',
			[
				'label' => esc_html__( 'Submit Button', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Log In', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'bdthemes-element-pack' ),
					''      => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'large' => esc_html__( 'Large', 'bdthemes-element-pack' ),
				],
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-button-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_forms_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'redirect_after_login',
			[
				'label'     => esc_html__( 'Redirect After Login', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'type'          => Controls_Manager::URL,
				'show_label'    => false,
				'show_external' => false,
				'separator'     => false,
				'placeholder'   => 'http://your-link.com/',
				'description'   => esc_html__( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'bdthemes-element-pack' ),
				'condition'     => [
					'redirect_after_login' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_lost_password',
			[
				'label'     => esc_html__( 'Lost your password?', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		if ( get_option( 'users_can_register' ) ) {
			$this->add_control(
				'show_register',
				[
					'label'     => esc_html__( 'Register', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
					'label_on'  => esc_html__( 'Show', 'bdthemes-element-pack' ),
				]
			);
		}

		$this->add_control(
			'show_remember_me',
			[
				'label'     => esc_html__( 'Remember Me', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_logged_in_message',
			[
				'label'     => esc_html__( 'Logged in Message', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label'     => esc_html__( 'Custom Label', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_label',
				[
				'label'     => esc_html__( 'Username Label', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Username or Email', 'bdthemes-element-pack' ),
				'condition' => [
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_placeholder',
			[
				'label'     => esc_html__( 'Username Placeholder', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Username or Email', 'bdthemes-element-pack' ),
				'condition' => [
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_label',
			[
				'label'     => esc_html__( 'Email Label', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Email', 'bdthemes-element-pack' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label'     => esc_html__( 'Email Placeholder', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		// $this->add_control(
		// 	'enable_recaptcha',
		// 	[
		// 		'label'   => esc_html__( 'Enable Recaptcha?', 'bdthemes-element-pack' ),
		// 		'type'    => Controls_Manager::SWITCHER,
		// 		'default' => '',
		// 	]
		// );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Form Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label'   => esc_html__( 'Rows Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => '15',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					//'#bdt-user-register{{ID}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'links_color',
			[
				'label'     => esc_html__( 'Links Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group > a' => 'color: {{VALUE}};',
					'#bdt-user-register{{ID}} .bdt-user-register-password a:not(:last-child):after' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label'     => esc_html__( 'Links Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group > a:hover' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label'     => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-form-label' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '#bdt-user-register{{ID}} .bdt-form-label',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Fields', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_field_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input' => 'color: {{VALUE}};',
					//'#bdt-user-register{{ID}} .bdt-field-group .bdt-checkbox:checked' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input::placeholder' => 'color: {{VALUE}};',
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input::-moz-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input, #bdt-user-register{{ID}} .bdt-field-group .bdt-checkbox' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'field_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#bdt-user-register{{ID}} .bdt-field-group .bdt-input',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'field_box_shadow',
				'selector' => '#bdt-user-register{{ID}} .bdt-field-group .bdt-input',
			]
		);

		$this->add_control(
			'field_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'field_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '#bdt-user-register{{ID}} .bdt-field-group .bdt-input',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_hover',
			[
				'label' => esc_html__( 'Focus', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'field_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'field_border_border!' => '',
				],
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-field-group .bdt-input:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button_style',
			[
				'label' => esc_html__( 'Submit Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '#bdt-user-register{{ID}} .bdt-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#bdt-user-register{{ID}} .bdt-button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'#bdt-user-register{{ID}} .bdt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-user-register{{ID}} .bdt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-user-register{{ID}} .bdt-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
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

		$this->end_controls_section();
	}


	public function form_fields_render_attributes() {
		$settings = $this->get_settings();
		$id       = $this->get_id();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'bdt-button-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
					],
				],
				'field-group' => [
					'class' => [
						'bdt-field-group',
						//'bdt-margin',
						'bdt-width-1-1',
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-type-submit',
						'bdt-field-group',
						'bdt-flex',
					],
				],

				'button' => [
					'class' => [
						'elementor-button',
						'bdt-button',
						'bdt-button-primary',
					],
					'name' => 'wp-submit',
				],
				'user_label' => [
					'for'   => 'user_login' . $id,
					'class' => [
						'bdt-form-label',
					]
				],
				'email_label' => [
					'for'   => 'user_email' . $id,
					'class' => [
						'bdt-form-label',
					]
				],
				'user_input' => [
					'type'        => 'text',
					'name'        => 'user_login',
					'id'          => 'user_login' . $id,
					'placeholder' => $settings['user_placeholder'],
					'class'       => [
						'bdt-input',
						'bdt-form-' . $settings['input_size'],
					],
				],
				'password_input' => [
					'type'        => 'email',
					'name'        => 'user_email',
					'id'          => 'user_email' . $id,
					'placeholder' => $settings['email_placeholder'],
					'class'       => [
						'bdt-input',
						'bdt-form-' . $settings['input_size'],
					],
				],
			]
		);

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
			->add_render_attribute( 'input', 'required', true )
			->add_render_attribute( 'input', 'aria-required', 'true' );

	}

	public function render() {
		$settings    = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->get_id();

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		if ( is_user_logged_in() && ! Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				echo '<div class="bdt-user-register">' .
					sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'bdthemes-element-pack' ), $current_user->display_name, wp_logout_url( $current_url ) ) .
					'</div>';
			}
			return;
		} elseif ( !get_option('users_can_register') ) {
			wp_redirect( site_url('wp-login.php?registration=disabled') );
			exit();
		}		

		$this->form_fields_render_attributes();

		?>
		<div class="bdt-user-register bdt-user-register-skin-default">
			<div class="elementor-form-fields-wrapper">
				<?php $this->user_login_form(); ?>
			</div>
		</div>

		<?php

		//$this->user_login_ajax_script();
		
	}


	public function user_login_form() {
		$settings    = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->get_id();
		$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);


		$user_login = '';
		$user_email = '';

		if ( $http_post ) {
			if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
				$user_login = $_POST['user_login'];
			}

			if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
				$user_email = wp_unslash( $_POST['user_email'] );
			}

			$errors = register_new_user($user_login, $user_email);
			if ( !is_wp_error($errors) ) {
				$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
				wp_safe_redirect( $redirect_to );
				exit();
			}
		}

		$registration_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
		/**
		 * Filters the registration redirect URL.
		 *
		 * @since 3.0.0
		 *
		 * @param string $registration_redirect The redirect destination URL.
		 */
		$redirect_to = apply_filters( 'registration_redirect', $registration_redirect );
		//login_header(__('Registration Form'), '<p class="message register">' . __('Register For This Site') . '</p>', $errors);











		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		?>

		<form name="registerform" id="bdt-user-register<?php echo esc_attr($id); ?>" class="bdt-form-stacked bdt-width-1-1" method="post" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>">
			<div class="bdt-user-register-status"></div>
			<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
				<?php
				if ( $settings['show_labels'] ) {
					echo '<label ' . $this->get_render_attribute_string( 'user_label' ) . '>' . $settings['user_label'] . '</label>';
				}
				echo '<div class="bdt-form-controls">';
				echo '<input ' . $this->get_render_attribute_string( 'user_input' ) . ' required>';
				echo '</div>';

				?>
			</div>

			<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
				<?php
				if ( $settings['show_labels'] ) :
					echo '<label ' . $this->get_render_attribute_string( 'email_label' ) . '>' . $settings['email_label'] . '</label>';
				endif;
				echo '<div class="bdt-form-controls">';
				echo '<input ' . $this->get_render_attribute_string( 'password_input' ) . ' required>';
				echo '</div>';
				?>
			</div>

			<?php do_action( 'register_form' ); ?>

			<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
				<div class="bdt-field-group bdt-remember-me">
					<label for="remember-me-<?php echo esc_attr($id); ?>" class="bdt-form-label">
						<input type="checkbox" id="remember-me-<?php echo esc_attr($id); ?>" class="bdt-checkbox" name="rememberme" value="forever"> 
						<?php esc_html_e( 'Remember Me', 'bdthemes-element-pack' ); ?>
					</label>
				</div>
			<?php endif; ?>

			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>" />
			
			<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
				<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<span><?php echo $settings['button_text']; ?></span>
					<?php endif; ?>
				</button>
			</div>

			<?php
			$show_lost_password = 'yes' === $settings['show_lost_password'];
			$show_register      = get_option( 'users_can_register' ) && 'yes' === $settings['show_register'];

			if ( $show_lost_password || $show_register ) : ?>
				<div class="bdt-field-group bdt-width-1-1 bdt-margin-remove-bottom bdt-user-register-password">
					   
					<?php if ( $show_lost_password ) : ?>
						<a class="bdt-lost-password" href="<?php echo wp_lostpassword_url( $redirect_url ); ?>">
							<?php esc_html_e( 'Lost your password?', 'bdthemes-element-pack' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $show_register ) : ?>
						<a class="bdt-register" href="<?php echo wp_registration_url(); ?>">
							<?php esc_html_e( 'Register', 'bdthemes-element-pack' ); ?>
						</a>
					<?php endif; ?>
					
				</div>
			<?php endif; ?>
			
			<?php 
				// if ('yes' === $settings['enable_recaptcha']) {
				// 	Module::get_recaptcha(); 
				// }
			?>
			
			
		</form>


		<?php
	}

}