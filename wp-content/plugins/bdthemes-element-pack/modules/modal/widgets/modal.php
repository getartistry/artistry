<?php
namespace ElementPack\Modules\Modal\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modal extends Widget_Base {
	public function get_name() {
		return 'modal';
	}

	public function get_title() {
		return esc_html__( 'Modal', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-close';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Open Modal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bdthemes-element-pack' ),
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
				'label' => __( 'Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => element_pack_button_sizes(),
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'button_icon!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
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
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button-icon.elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_modal',
			[
				'label' => esc_html__( 'Modal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'header',
			[
				'label'       => esc_html__( 'Header', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is your modal header title', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Modal header title', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Select Source', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom'    => esc_html__( 'Custom', 'bdthemes-element-pack' ),
					"elementor" => esc_html__( 'Elementor Template', 'bdthemes-element-pack' ),
					'anywhere'  => esc_html__( 'AE Template', 'bdthemes-element-pack' ),
				],				
			]
		);

		$this->add_control(
			'content',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::WYSIWYG,
				'show_label'  => false,
				'condition'   => ['source' => 'custom'],
				'default'     => esc_html__( 'A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Modal content goes here', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'template_id',
			[
				'label'       => __( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_et_options(),
				'label_block' => 'true',
				'condition'   => ['source' => "elementor"],
			]
		);

		$this->add_control(
			'anywhere_id',
			[
				'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_ae_options(),
				'label_block' => 'true',
				'condition'   => ['source' => 'anywhere'],
			]
		);

		$this->add_control(
			'content_overflow',
			[
				'label' => __( 'Overflow Scroll', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'footer',
			[
				'label'       => esc_html__( 'Footer', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Modal footer goes here', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Modal footer goes here', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'close_button',
			[
				'label'   => esc_html__( 'Close Button', 'bdthemes-element-pack' ),
				'description' => esc_html__('When you set modal full screen make sure you don\'t set colse button outside', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'outside' => esc_html__( 'Outside', 'bdthemes-element-pack' ),
					'none'    => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'modal_size',
			[
				'label'        => esc_html__( 'Full screen', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'full',
				'condition'    => [
					'close_button!' => 'outside',
				],
			]
		);

		$this->add_control(
			'modal_center',
			[
				'label'        => esc_html__( 'Center Position', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => [
					'modal_size!' => 'full',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'heading_footer_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HEADING,
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
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button',
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
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-modal-wrapper .bdt-modal-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_modal',
			[
				'label'     => esc_html__( 'Modal', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_modal_content_style' );

		$this->start_controls_tab(
			'tab_content_header',
			[
				'label' => esc_html__( 'Header', 'bdthemes-element-pack' ),
				'condition' => [
					'header!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-header' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'header_align',
			[
				'label'       => esc_html__( 'Titlt Align', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'header_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-header',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'header_box_shadow',
				'selector' => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-header',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'condition' => [
					'content!' => '',
				],
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-body' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-body' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_footer',
			[
				'label' => esc_html__( 'Footer', 'bdthemes-element-pack' ),
				'condition' => [
					'footer!' => '',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'footer_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'footer_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'footer_align',
			[
				'label'       => esc_html__( 'Text Align', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'footer_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'footer_box_shadow',
				'selector' => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '#bdt-modal{{ID}}.bdt-modal .bdt-modal-footer',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function render() {
		$settings = $this->get_settings();
		$id       = $this->get_id();

        $this->add_render_attribute( 'button', 'class', ['bdt-modal-button', 'elementor-button'] );

        $this->add_render_attribute( 'modal', 'id', 'bdt-modal'.$id );
        $this->add_render_attribute( 'modal', 'bdt-modal', '' );

        if ( $settings['modal_size'] !== 'full' ) {
        	$this->add_render_attribute( 'modal', 'class', 'bdt-modal' );
        } else {
        	$this->add_render_attribute( 'modal', 'class', 'bdt-modal bdt-modal-full' );
        	$this->add_render_attribute( 'modal-body', 'bdt-height-viewport', 'offset-top: .bdt-modal-header; offset-bottom: .bdt-modal-footer' );
        }

        if ( $settings['modal_center'] === 'yes' ) {
        	$this->add_render_attribute( 'modal', 'class', 'bdt-flex-top' );
        }

        $this->add_render_attribute( 'modal-dialog', 'class', 'bdt-modal-dialog' );

        if ($settings['modal_center'] === 'yes' ) {
        	$this->add_render_attribute( 'modal-dialog', 'class', 'bdt-margin-auto-vertical' );
        }

        if ( ! empty( $settings['size'] ) ) {
        	$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
        }

        if ( $settings['hover_animation'] ) {
        	$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }


        $this->add_render_attribute( 'button', 'bdt-toggle', 'target: #bdt-modal'.$id );
        $this->add_render_attribute( 'button', 'href', 'javascript:void(0)' );

        $this->add_render_attribute( 'modal-body', 'class', 'bdt-modal-body' );
        if ( 'yes' === $settings['content_overflow'] ) {
        	$this->add_render_attribute( 'modal-body', 'bdt-overflow-auto', '' );
        }

        ?>

		<div class="bdt-modal-wrapper">
	        <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
	        	<?php $this->render_text(); ?>	
	        </a>

	        <div <?php echo $this->get_render_attribute_string( 'modal' ); ?>>
	            <div <?php echo $this->get_render_attribute_string( 'modal-dialog' ); ?>>
	                            
	                <?php if ( $settings['close_button'] != 'none' ) : ?>
	                	<button class="bdt-modal-close-<?php echo esc_attr($settings['close_button']); ?>" type="button" bdt-close></button>
	                <?php endif; ?>
	                
	                <?php if ( $settings['header'] ) : ?>
	                    <div class="bdt-modal-header bdt-text-<?php echo esc_attr($settings['header_align']); ?>">
	                    	<h3 class="bdt-modal-title"><?php echo wp_kses_post($settings['header']); ?></h3>
	                    </div>
	                <?php endif; ?>
	                
	                <div <?php echo $this->get_render_attribute_string( 'modal-body' ); ?>>
	                	<?php 
			            	if ( 'custom' == $settings['source'] and !empty( $settings['content'] ) ) {
			            		echo wp_kses_post( $settings['content'] );
			            	} elseif ("elementor" == $settings['source'] and !empty( $settings['template_id'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id'] );
			            	} elseif ('anywhere' == $settings['source'] and !empty( $settings['anywhere_id'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id'] );
			            	}
			            ?>
	                </div>

	                <?php if ( $settings['footer'] ) : ?>
	                    <div class="bdt-modal-footer bdt-text-<?php echo esc_attr($settings['header_align']); ?>">
	                    	<?php echo wp_kses_post($settings['footer']); ?>
	                    </div>
	                <?php endif; ?>
		        </div>
		    </div>
	    </div>
	    <?php
	}

	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['button_icon_align'] );
		$this->add_render_attribute( 'icon-align', 'class', 'bdt-modal-button-icon elementor-button-icon' );

		$this->add_render_attribute( 'text', 'class', 'elementor-button-text' );

		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['button_icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['button_text']; ?></span>
		</span>
		<?php
	}
}
