<?php
namespace ElementPack\Modules\Offcanvas\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Repeater;
use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Offcanvas Widget
 * @since 1.2.0
 */
class Offcanvas extends Widget_Base {

	public function get_name() {
		return 'bdt-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Offcanvas', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Select Source', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sidebar',
				'options' => [
					'sidebar'   => esc_html__( 'Sidebar', 'bdthemes-element-pack' ),
					'elementor' => esc_html__( 'Elementor Template', 'bdthemes-element-pack' ),
					'anywhere'  => esc_html__( 'AE Template', 'bdthemes-element-pack' ),
				],				
			]
		);

		$this->add_control(
			'template_id',
			[
				'label'       => __( 'Choose Template', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_et_options(),
				'label_block' => 'true',
				'condition'   => ['source' => 'elementor'],
			]
		);

		$this->add_control(
			'sidebars',
			[
				'label'       => esc_html__( 'Choose Sidebar', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_sidebar_options(),
				'label_block' => 'true',
				'condition'   => ['source' => 'sidebar'],
			]
		);


		$this->add_control(
			'anywhere_id',
			[
				'label'       => esc_html__( 'Choose Template', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => element_pack_ae_options(),
				'label_block' => 'true',
				'condition'   => ['source' => 'anywhere'],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'custom_content_before_switcher',
			[
				'label' => esc_html__( 'Custom Content Before', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'custom_content_after_switcher',
			[
				'label' => esc_html__( 'Custom Content After', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'offcanvas_overlay',
			[
				'label'        => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'offcanvas_animations',
			[
				'label'     => esc_html__( 'Animations', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide',
				'options'   => [
					'slide'  => esc_html__( 'Slide', 'bdthemes-element-pack' ),
					'push'   => esc_html__( 'Push', 'bdthemes-element-pack' ),
					'reveal' => esc_html__( 'Reveal', 'bdthemes-element-pack' ),
					'none'   => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'offcanvas_flip',
			[
				'label'        => esc_html__( 'Flip', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'offcanvas_close_button',
			[
				'label'   => esc_html__( 'Close Button', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);


		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_custom_before',
			[
				'label'     => esc_html__( 'Custom Content Before', 'bdthemes-element-pack' ),
				'condition' => [
					'custom_content_before_switcher' => 'yes',
				]
			]
		);

		$this->add_control(
			'custom_content_before',
			[
				'label'   => esc_html__( 'Custom Content Before', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'This is your custom content for before of your offcanvas.', 'bdthemes-element-pack' ),
			]
		);
		
		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_custom_after',
			[
				'label'     => esc_html__( 'Custom Content After', 'bdthemes-element-pack' ),
				'condition' => [
					'custom_content_after_switcher' => 'yes',
				]
			]
		);


		$this->add_control(
			'custom_content_after',
			[
				'label'   => esc_html__( 'Custom Content After', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'This is your custom content for after of your offcanvas.', 'bdthemes-element-pack' ),
			]
		);
		
		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_offcanvas_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Offcanvas', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Offcanvas', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'   => esc_html__( 'Button Alignment', 'bdthemes-element-pack' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			]
		);

		$this->add_responsive_control(
			'button_offset',
			[
				'label' => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -150,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button' => 'transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => __( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => element_pack_button_sizes(),
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'       => esc_html__( 'Button Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-bars',
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
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
				'label'   => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .bdt-offcanvas-button .bdt-offcanvas-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-offcanvas-button .bdt-offcanvas-button-icon.elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas_content',
			[
				'label' => esc_html__( 'Offcanvas', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'offcanvas_content_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_content_link_color',
			[
				'label'     => esc_html__( 'Link Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar a'   => 'color: {{VALUE}};',
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar a *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_content_link_hover_color',
			[
				'label'     => esc_html__( 'Link Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar a:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'offcanvas_content_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'offcanvas_content_box_shadow',
				'selector'  => '#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'offcanvas_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas_widget',
			[
				'label'     => esc_html__( 'Widget', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source' => 'sidebar',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'offcanvas_widget_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar .widget',
				'separator'   => 'before',
			]
		);

		$this->add_responsive_control(
			'widget_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar .widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_widget_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar .widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'offcanvas_vertical_spacing',
			[
				'label'     => esc_html__( 'Vertical Spacing', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'#bdt-offcanvas{{ID}}.bdt-offcanvas .bdt-offcanvas-bar .widget:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_offcanvas_button_style' );

		$this->start_controls_tab(
			'tab_offcanvas_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'offcanvas_button_text_color',
			[
				'label'     => esc_html__( 'Button Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_button_background_color',
			[
				'label'     => esc_html__( 'Button Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'offcanvas_button_box_shadow',
				'selector'  => '{{WRAPPER}} .bdt-offcanvas-button',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'offcanvas_button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-offcanvas-button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'offcanvas_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-offcanvas-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-offcanvas-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'offcanvas_button_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .bdt-offcanvas-button',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_offcanvas_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'offcanvas_button_hover_color',
			[
				'label'     => esc_html__( 'Button Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_button_background_hover_color',
			[
				'label'     => esc_html__( 'Button Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'offcanvas_button_hover_border_color',
			[
				'label'     => esc_html__( 'Button Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'offcanvas_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-offcanvas-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Button Animation', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		$id = $this->get_id();
		$offcanvas_settings = [];

		$offcanvas_settings['bdt-offcanvas'] = json_encode(array_filter([
			'mode'    => $settings['offcanvas_animations'],
			'overlay' => $settings['offcanvas_overlay'],
			'flip'    => $settings['offcanvas_flip'],
	    ]));

	    $offcanvas_settings['class'] = ['bdt-offcanvas'];

		$this->add_render_attribute( 'bdt-offcanvas-wrapper', 'class', 'bdt-offcanvas-button-wrapper' );

		$this->add_render_attribute( 'button', 'class', ['bdt-offcanvas-button', 'elementor-button'] );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$this->add_render_attribute( 'button', 'bdt-toggle', 'target: #bdt-offcanvas' . esc_attr($id) );
		$this->add_render_attribute( 'button', 'href', 'javascript:void(0)' );


		?>

		<div <?php echo $this->get_render_attribute_string( 'bdt-offcanvas-wrapper' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?> >
				<?php $this->render_text(); ?>
			</a>
		</div>

		
	    <div id="bdt-offcanvas<?php echo esc_attr($id); ?>" <?php echo \element_pack_helper::attrs($offcanvas_settings); ?>>
	        <div class="bdt-offcanvas-bar">
				
				<?php if ($settings['offcanvas_close_button']) : ?>
	        		<button class="bdt-offcanvas-close" type="button" bdt-close></button>
	        	<?php endif; ?>

	        	
				<?php if ($settings['custom_content_before_switcher'] or $settings['custom_content_after_switcher'] or !empty( $settings['source'] )) : ?>
		        	<?php if ($settings['custom_content_before_switcher'] === 'yes' and !empty($settings['custom_content_before'])) : ?>
		        	<div class="bdt-offcanvas-custom-content-before widget">
		            	<?php echo wp_kses_post($settings['custom_content_before']); ?>		        		
		        	</div>
		        	<?php endif; ?>

		            <?php 
		            	if ( 'sidebar' == $settings['source'] and !empty( $settings['sidebars'] ) ) {
		            		dynamic_sidebar( $settings['sidebars'] );
		            	} elseif ('elementor' == $settings['source'] and !empty( $settings['template_id'] )) {
		            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['template_id'] );
		            	} elseif ('anywhere' == $settings['source'] and !empty( $settings['anywhere_id'] )) {
		            		echo Element_Pack_Loader::elementor()->frontend->get_builder_content_for_display( $settings['anywhere_id'] );
		            	}
		            ?>

	            	<?php if ($settings['custom_content_after_switcher'] === 'yes' and !empty($settings['custom_content_after'])) : ?>
	            	<div class="bdt-offcanvas-custom-content-after widget">
	                	<?php echo wp_kses_post($settings['custom_content_after']); ?>		        		
	            	</div>
	            	<?php endif; ?>
	            <?php else: ?>
					<div class="bdt-offcanvas-custom-content-after widget">
						<div class="bdt-alert-warning" bdt-alert><?php esc_html_e('Ops you don\'t select or enter any content! Add your offcanvas content from editor.', 'bdthemes-element-pack'); ?></div>
					</div>
	            <?php endif; ?>
	        </div>
	    </div>
		<?php
	}

	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['button_icon_align'] );
		$this->add_render_attribute( 'icon-align', 'class', 'bdt-offcanvas-button-icon elementor-button-icon' );

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
