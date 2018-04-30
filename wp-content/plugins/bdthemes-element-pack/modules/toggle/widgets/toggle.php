<?php
namespace ElementPack\Modules\Toggle\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Toggle extends Widget_Base {

	public function get_name() {
		return 'bdt-toggle';
	}

	public function get_title() {
		return esc_html__( 'Toggle', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-v-align-stretch';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Toggle', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'toggle_title',
			[
				'label'       => esc_html__( 'Normal Title', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Show All' , 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'toggle_open_title',
			[
				'label'       => esc_html__( 'Opened Title', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Collapse' , 'bdthemes-element-pack' ),
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
			'toggle_content',
			[
				'label'      => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => esc_html__( 'Toggle Content', 'bdthemes-element-pack' ),
				'show_label' => false,
				'condition'  => ['source' => 'custom'],
			]
		);

		$this->add_control(
			'toggle_icon_show',
			[
				'label'   => esc_html__( 'Toggle Icon', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => esc_html__( 'Additional', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shadow_height',
			[
				'label' => esc_html__( 'Shadow Height', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-toggle-container .bdt-accordion .bdt-accordion-item .bdt-accordion-title:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_normal',
			[
				'label'       => esc_html__( 'Normal Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-plus',
				'label_block' => true,
				'condition'   => [
					'toggle_icon_show!' => '',
				],
			]
		);

		$this->add_control(
			'icon_active',
			[
				'label'       => esc_html__( 'Active Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-minus',
				'label_block' => true,
				'condition'   => [
					'toggle_icon_show!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'color: {{VALUE}};',
				],
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
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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


		$this->add_control(
			'shadow_color',
			[
				'label'     => esc_html__( 'Shadow Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-toggle-container .bdt-accordion .bdt-accordion-item .bdt-accordion-title:before' => 'background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, {{VALUE}} 100%);',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_active',
			[
				'label' => esc_html__( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'active_title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_content_style',
			[
				'label'     => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_icon',
			[
				'label'     => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'toggle_icon_show' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-title .bdt-accordion-icon .fa:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-icon'  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			[
				'label' => esc_html__( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon .fa:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings                            = $this->get_settings();
		$id                                  = $this->get_id();
		$accordion_settings                  = [];
		$accordion_settings['id'][]          = 'bdt-accordion-' . $id;
		$accordion_settings['class'][]       = 'bdt-accordion';
		$accordion_settings['bdt-accordion'] = wp_json_encode(array_filter([
			'collapsible' => 'true',
			'transition'  => 'ease-in-out',
		]));

		?>
		<div class="bdt-toggle-container">
			<div <?php echo \element_pack_helper::attrs($accordion_settings); ?>>
				<?php 
					$this->add_render_attribute( 'tab_title', [ 'class' => [ 'bdt-accordion-title' ], ] );
					$this->add_render_attribute( 'toggle_content', [ 'class' => [ 'bdt-accordion-content' ], ] );
					$this->add_inline_editing_attributes( 'toggle_content', 'advanced' );
				?>

				<div class="bdt-accordion-item">
					
					<div <?php echo $this->get_render_attribute_string( 'toggle_content' ); ?>>
						<?php 
			            	if ( 'custom' == $settings['source'] and !empty( $settings['toggle_content'] ) ) {
			            		echo wp_kses_post( $settings['toggle_content'] );
			            	} elseif ("elementor" == $settings['source'] and !empty( $settings['template_id'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id'] );
			            	} elseif ('anywhere' == $settings['source'] and !empty( $settings['anywhere_id'] )) {
			            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id'] );
			            	}
			            ?>
					</div>

					<a <?php echo $this->get_render_attribute_string( 'tab_title' ); ?> href="#">
						<span class="bdt-toggle-open"><?php echo $settings['toggle_title']; ?></span>
						<span class="bdt-toggle-close"><?php echo $settings['toggle_open_title']; ?></span> 
						
						<?php if ( 'yes' === $settings['toggle_icon_show'] ) : ?>
						<span class="bdt-accordion-icon" aria-hidden="true">
							<i class="bdt-accordion-icon-closed <?php echo esc_attr( $settings['icon_normal'] ); ?>"></i>
							<i class="bdt-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>
						</span>
						<?php endif; ?>
						
					</a>
					
				</div>
			</div>
		</div>
		<?php
	}
}
