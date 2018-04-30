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

class Skin_Dropdown extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-user-login/section_style/before_section_start', [ $this, 'register_dropdown_button_style_controls' ] );
		add_action( 'elementor/element/bdt-user-login/section_style/before_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/bdt-user-login/section_forms_additional_options/before_section_start', [ $this, 'register_dropdown_button_controls' ] );

	}

	public function get_id() {
		return 'bdt-dropdown';
	}

	public function get_title() {
		return __( 'Dropdown', 'bdthemes-element-pack' );
	}

	public function register_dropdown_button_controls() {
		$this->start_controls_section(
			'section_dropdown_button',
			[
				'label' => esc_html__( 'Dropdown Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dropdown_button_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Log In', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dropdown_button_size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => element_pack_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'dropdown_button_align',
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
			'dropdown_button_icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'dropdown_button_icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					$this->get_control_id( 'dropdown_button_icon!' ) => '',
				],
			]
		);

		$this->add_control(
			'dropdown_button_icon_indent',
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
					$this->get_control_id( 'dropdown_button_icon!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button-icon.elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_dropdown_button_style_controls() {
		$this->start_controls_section(
			'section_style_dropdown_button',
			[
				'label' => esc_html__( 'Dropdown Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_dropdown_button_style' );

		$this->start_controls_tab(
			'tab_dropdown_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dropdown_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-button-dropdown',
			]
		);

		$this->add_control(
			'dropdown_button_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'dropdown_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-button-dropdown',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'dropdown_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dropdown_button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dropdown_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button-dropdown:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'dropdown_button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'dropdown_button_hover_animation',
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
			'section_dropdown_style',
			[
				'label' => esc_html__( 'Dropdown Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dropdown_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'#dropdown{{ID}}.bdt-user-login .bdt-dropdown' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'dropdown_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#dropdown{{ID}}.bdt-user-login .bdt-dropdown',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'dropdown_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'#dropdown{{ID}}.bdt-user-login .bdt-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dropdown_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'#dropdown{{ID}}.bdt-user-login .bdt-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dropdown_offset',
			[
				'label' => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_control(
			'dropdown_position',
			[
				'label'   => esc_html__( 'Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom-right',
				'options' => element_pack_drop_position_options(),
			]
		);

		$this->add_control(
			'dropdown_mode',
			[
				'label'   => esc_html__( 'Mode', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__('Hover', 'bdthemes-element-pack'),
					'click' => esc_html__('Clicked', 'bdthemes-element-pack'),
				],
			]
		);

		$this->end_controls_section();
	}

	

	public function render() {
		$dropdown_id = 'dropdown' . $this->parent->get_id();
		$settings    = $this->parent->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->parent->get_id();

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}


		$dropdown_offset = $this->get_instance_value('dropdown_offset');
		if (Element_Pack_Loader::elementor()->editor->is_edit_mode()) {
				$dropdown_settings['bdt-dropdown'] = json_encode(array_filter([
					'mode'   => 'click',
					'pos'    => $this->get_instance_value('dropdown_position'),
					'offset' => $dropdown_offset['size'],
			    ]));
		} else {
				$dropdown_settings['bdt-dropdown'] = json_encode(array_filter([
					'mode'   => $this->get_instance_value('dropdown_mode'),
					'pos'    => $this->get_instance_value('dropdown_position'),
					'offset' => $dropdown_offset['size'],
			    ]));
		}


		$dropdown_button = [];
		$dropdown_button['class'] = ['elementor-button', 'bdt-button-dropdown'];
		$dropdown_button['class'][] = 'elementor-size-' . $this->get_instance_value('dropdown_button_size');

		if ($this->get_instance_value('dropdown_button_animation')) {
			$dropdown_button['class'][] = 'elementor-animation-' . $this->get_instance_value('dropdown_button_animation');
		}
		
		if ( is_user_logged_in() && ! Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				?>
				<div id="<?php echo esc_attr($dropdown_id); ?>" class="bdt-user-login bdt-user-login-skin-dropdown">
					<a <?php echo \element_pack_helper::attrs($dropdown_button); ?> href="<?php echo wp_logout_url( $current_url ); ?>"><?php $this->render_text(); ?></a>
				</div>
				<?php
			}

			return;
		}

		$this->parent->form_fields_render_attributes();

		?>
		<div id="<?php echo esc_attr($dropdown_id); ?>" class="bdt-user-login bdt-user-login-skin-dropdown">
			<a <?php echo \element_pack_helper::attrs($dropdown_button); ?> href="javascript:void(0)"><?php $this->render_text(); ?></a>

			<div <?php echo \element_pack_helper::attrs($dropdown_settings); ?>>

				<div class="elementor-form-fields-wrapper bdt-text-left">
					<?php $this->parent->user_login_form(); ?>
				</div>

			</div>
		</div>
		<?php

		$this->parent->user_login_ajax_script();
	}

	protected function render_text() {
		$dropdown_button_attr             = [];
		$dropdown_button_icon             = [];
		$dropdown_button_wrapper          = [];
		
		$dropdown_button_attr['class']    = ['elementor-button-text'];
		$dropdown_button_icon['class']    = ['bdt-dropdown-button-icon', 'elementor-button-icon', 'elementor-align-icon-' . $this->get_instance_value('dropdown_button_icon_align')];
		$dropdown_button_wrapper['class'] = ['elementor-button-content-wrapper'];

		if ( is_user_logged_in() && ! Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
			$button_text = esc_html__( 'Logout', 'bdthemes-element-pack' );
		} else {
			$button_text = $this->get_instance_value('dropdown_button_text');
		}
		
		?>

		<span <?php echo \element_pack_helper::attrs($dropdown_button_wrapper); ?>>
			<?php if ( ! empty( $this->get_instance_value('dropdown_button_icon') ) ) : ?>
			<span <?php echo \element_pack_helper::attrs($dropdown_button_icon); ?>>
				<i class="<?php echo esc_attr( $this->get_instance_value('dropdown_button_icon') ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo \element_pack_helper::attrs($dropdown_button_attr); ?>><?php echo esc_html($button_text); ?></span>
		</span>
		<?php
	}
}

