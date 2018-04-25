<?php
namespace ElementPack\Modules\UserLogin\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

use Elementor\Skin_Base as Elementor_Skin_Base;
use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Modal extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-user-login/section_style/before_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/bdt-user-login/section_forms_additional_options/before_section_start', [ $this, 'register_modal_button_controls' ] );
		add_action( 'elementor/element/bdt-user-login/section_style/before_section_start', [ $this, 'register_modal_button_style_controls' ] );

	}

	public function get_id() {
		return 'bdt-modal';
	}

	public function get_title() {
		return __( 'Modal', 'bdthemes-element-pack' );
	}

	public function register_modal_button_controls(Widget_Base $widget) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_modal_button',
			[
				'label' => esc_html__( 'Modal Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'modal_button_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Log In', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'modal_button_size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => element_pack_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'modal_button_align',
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
				'default' => '',
			]
		);

		$this->add_control(
			'modal_button_icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'modal_button_icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					$this->get_control_id( 'modal_button_icon!' ) => '',
				],
			]
		);

		$this->add_control(
			'modal_button_icon_indent',
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
					$this->get_control_id( 'modal_button_icon!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal .bdt-modal-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-button-modal .bdt-modal-button-icon.elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_modal_button_style_controls(Widget_Base $widget) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_style_modal_button',
			[
				'label' => esc_html__( 'Modal Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_modal_button_style' );

		$this->start_controls_tab(
			'tab_modal_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'modal_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'modal_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-button-modal',
			]
		);

		$this->add_control(
			'modal_button_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'modal_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-button-modal',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'modal_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'modal_button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_modal_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'modal_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-modal:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'modal_button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'modal_button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->end_controls_section();
	}

	public function register_controls(Widget_Base $widget ) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_modal_style',
			[
				'label' => esc_html__( 'Modal Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'modal_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'#modal{{ID}} .bdt-modal-dialog' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'modal_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#modal{{ID}} .bdt-modal-dialog',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'modal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'#modal{{ID}} .bdt-modal-dialog' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'modal_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#modal{{ID}} .bdt-modal-dialog .bdt-modal-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'modal_close_button',
			[
				'label' => esc_html__( 'Close Button', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on' => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'modal_header',
			[
				'label' => esc_html__( 'Modal Header', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Hide', 'bdthemes-element-pack' ),
				'label_on' => esc_html__( 'Show', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$modal_id = 'modal' . $this->parent->get_id();
		$settings    = $this->parent->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->parent->get_id();

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}


		$modal_settings = [];
		$modal_id = 'modal' . $this->parent->get_id();

		$modal_button = [];
		$modal_button['class'] = ['elementor-button', 'bdt-button-modal'];
		$modal_button['class'][] = 'elementor-size-' . $this->get_instance_value('modal_button_size');

		if ($this->get_instance_value('modal_button_animation')) {
			$modal_button['class'][] = 'elementor-animation-' . $this->get_instance_value('modal_button_animation');
		}
		

		if ( is_user_logged_in() && ! Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user(); ?>
				<div id="<?php echo esc_attr($modal_id); ?>" class="bdt-user-login bdt-user-login-skin-dropdown">
					<a <?php echo \element_pack_helper::attrs($modal_button); ?> href="<?php echo wp_logout_url( $current_url ); ?>"><?php $this->render_text(); ?></a>
				</div>
				<?php
			}

			return;
		}


		$this->parent->form_fields_render_attributes();

		?>
		<div class="bdt-user-login bdt-user-login-skin-modal">

			<a href="javascript:void(0)" <?php echo \element_pack_helper::attrs($modal_button); ?> bdt-toggle="target: #<?php echo esc_attr($modal_id); ?>"><?php $this->render_text(); ?></a>
			<div id="<?php echo esc_attr($modal_id); ?>" class="bdt-flex-top bdt-user-login-modal" bdt-modal>
				<div class="bdt-modal-dialog bdt-margin-auto-vertical">
					<?php if ('yes' === $this->get_instance_value('modal_close_button')) : ?>
						<button class="bdt-modal-close-default" type="button" bdt-close></button>
					<?php endif; ?>
					<?php if ('yes' === $this->get_instance_value('modal_header')) : ?>
					<div class="bdt-modal-header">
			            <h2 class="bdt-modal-title"><span bdt-icon="user"></span> <?php esc_html_e('User Login!', 'bdthemes-element-pack'); ?></h2>
			        </div>
					<?php endif; ?>
					<div class="elementor-form-fields-wrapper bdt-modal-body">
						<?php $this->parent->user_login_form(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php

		$this->parent->user_login_ajax_script();
	}

	protected function render_text() {
		$modal_button_attr      = [];
		$modal_button_icon_attr = [];
		$modal_button_wrapper   = [];
		
		$modal_button_attr['class']    = ['elementor-button-text'];
		$modal_button_icon_attr['class']    = ['bdt-modal-button-icon', 'elementor-button-icon', 'elementor-align-icon-' . $this->get_instance_value('modal_button_icon_align')];
		$modal_button_wrapper['class'] = ['elementor-button-content-wrapper'];

		if ( is_user_logged_in() && ! Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
			$button_text = esc_html__( 'Logout', 'bdthemes-element-pack' );
		} else {
			$button_text = $this->get_instance_value('modal_button_text');
		}

		$modal_button_icon = $this->get_instance_value('modal_button_icon');
		
		?>

		<span <?php echo \element_pack_helper::attrs($modal_button_wrapper); ?>>
			<?php if ( ! empty( $modal_button_icon ) ) : ?>
			<span <?php echo \element_pack_helper::attrs($modal_button_icon_attr); ?>>
				<i class="<?php echo esc_attr( $modal_button_icon ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo \element_pack_helper::attrs($modal_button_attr); ?>><?php echo esc_html($button_text); ?></span>
		</span>
		<?php
	}

}

