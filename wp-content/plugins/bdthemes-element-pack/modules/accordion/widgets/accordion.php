<?php
namespace ElementPack\Modules\Accordion\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Accordion extends Widget_Base {

	public function get_name() {
		return 'bdt-accordion';
	}

	public function get_title() {
		return esc_html__( 'Accordion', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Accordion', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'   => __( 'Accordion Items', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					[
						'tab_title'   => __( 'Accordion #1', 'bdthemes-element-pack' ),
						'tab_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'bdthemes-element-pack' ),
					],
					[
						'tab_title'   => __( 'Accordion #2', 'bdthemes-element-pack' ),
						'tab_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'bdthemes-element-pack' ),
					],
					[
						'tab_title'   => __( 'Accordion #3', 'bdthemes-element-pack' ),
						'tab_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'bdthemes-element-pack' ),
					],
				],
				'fields' => [
					[
						'name'        => 'tab_title',
						'label'       => __( 'Title & Content', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Accordion Title' , 'bdthemes-element-pack' ),
						'label_block' => true,
					],
					[
						'name'    => 'source',
						'label'   => esc_html__( 'Select Source', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'custom',
						'options' => [
							'custom'    => esc_html__( 'Custom', 'bdthemes-element-pack' ),
							"elementor" => esc_html__( 'Elementor Template', 'bdthemes-element-pack' ),
							'anywhere'  => esc_html__( 'AE Template', 'bdthemes-element-pack' ),
						],
					],
					[
						'name'       => 'tab_content',
						'label'      => __( 'Content', 'bdthemes-element-pack' ),
						'type'       => Controls_Manager::WYSIWYG,
						'default'    => __( 'Accordion Content', 'bdthemes-element-pack' ),
						'show_label' => false,
						'condition'  => ['source' => 'custom'],
					],
					[
						'name'        => 'template_id',
						'label'       => __( 'Content', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => '0',
						'options'     => element_pack_et_options(),
						'label_block' => 'true',
						'condition'   => ['source' => "elementor"],
					],
					[
						'name'        => 'anywhere_id',
						'label'       => esc_html__( 'Content', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => '0',
						'options'     => element_pack_ae_options(),
						'label_block' => 'true',
						'condition'   => ['source' => 'anywhere'],
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'   => __( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => element_pack_title_tags(),
				'default' => 'div',
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-plus',
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_active',
			[
				'label'       => __( 'Active Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-minus',
				'label_block' => true,
				'condition'   => [
					'icon!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => __( 'Additional', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'collapsible',
			[
				'label'   => __( 'Collapsible All Item', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'multiple',
			[
				'label' => __( 'Multiple Open', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'active_item',
			[
				'label' => __( 'Active Item No', 'elementor-pro' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'max'   => 20,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			[
				'label' => __( 'Item', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title'   => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label' => __( 'Item Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item + .bdt-accordion-item' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			[
				'label' => __( 'Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-accordion .bdt-accordion-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'title_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title',
			]
		);

		$this->add_control(
			'title_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_active',
			[
				'label' => __( 'Active', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'active_title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'active_title_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'active_title_shadow',
				'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'active_title_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
			]
		);

		$this->add_control(
			'active_title_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_icon',
			[
				'label'     => __( 'Icon', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Start', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'End', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => is_rtl() ? 'left' : 'right',
				'toggle'      => false,
				'label_block' => false,
				'condition'   => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title .bdt-accordion-icon .fa:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-icon.bdt-accordion-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-icon.bdt-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			[
				'label' => __( 'Active', 'elementor' ),
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon .fa:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_content',
			[
				'label'     => __( 'Content', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_background_color',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-accordion .bdt-accordion-content',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'color: {{VALUE}};',
				'separator' => 'before',
				],
			]
		);

		$this->add_control(
			'content_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-content',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings                            = $this->get_settings();
		$id                                  = $this->get_id();
		$accordion_settings                  = [];
		$accordion_settings['id'][]          = 'bdt-accordion-' . $id;
		$accordion_settings['class'][]       = 'bdt-accordion';
		$accordion_settings['bdt-accordion'] = wp_json_encode(array_filter([
			'collapsible' => ( 'yes' == $settings['collapsible']) ? 'true' : 'false',
			'multiple'    => ( 'yes' == $settings['multiple']) ? 'true' : 'false',
			'transition'  => 'ease-in-out',
		]));


		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="bdt-accordion-container">
			<div <?php echo \element_pack_helper::attrs($accordion_settings); ?>>
				<?php foreach ( $settings['tabs'] as $index => $item ) :
					$tab_count = $index + 1;

					$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

					$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

					$this->add_render_attribute( $tab_title_setting_key, [
						'class'         => [ 'bdt-accordion-title' ],
					] );

					$this->add_render_attribute( $tab_content_setting_key, [
						'class'           => [ 'bdt-accordion-content' ],
					] );

					$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					?>
					<div class="bdt-accordion-item<?php echo ($tab_count === $settings['active_item']) ? ' bdt-open' : ''; ?>">
						<<?php echo $settings['title_html_tag']; ?> <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?> href="#">
							<?php if ( $settings['icon'] ) : ?>
							<span class="bdt-accordion-icon bdt-accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>" aria-hidden="true">
								<i class="bdt-accordion-icon-closed <?php echo esc_attr( $settings['icon'] ); ?>"></i>
								<i class="bdt-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>
							</span>
							<?php endif; ?>
							<?php echo $item['tab_title']; ?>
						</<?php echo $settings['title_html_tag']; ?>>
						<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
							<?php 
				            	if ( 'custom' == $item['source'] and !empty( $item['tab_content'] ) ) {
				            		echo wp_kses_post( $item['tab_content'] );
				            	} elseif ("elementor" == $item['source'] and !empty( $item['template_id'] )) {
				            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $item['template_id'] );
				            	} elseif ('anywhere' == $item['source'] and !empty( $item['anywhere_id'] )) {
				            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $item['anywhere_id'] );
				            	}
				            ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
