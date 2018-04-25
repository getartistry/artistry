<?php
namespace ElementPack\Modules\ScrollButton\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Scroll Button Widget
 */
class Scroll_Button extends Widget_Base {

	//protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-scroll-button';
	}

	public function get_title() {
		return esc_html__( 'Scroll Button', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-select';
	}

	public function get_categories() {
	 	return [ 'element-pack' ];
 	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_scroll_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => esc_html__( 'Duration', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 1000,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 5000,
						'step' => 50,
					],
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 10,
					],
				],
			]
		);

		$this->add_control(
			'scroll_button_text',
			[
				'label' => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Scroll Up', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Scroll Up', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'section_id',
			[
				'label' => esc_html__( 'Section ID', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'my-header',
				'description' => "By clicking this scroll button, to which section in your page you want to go? Just write that's section ID here such 'my-header'. N.B: No need to add '#'.",
			]
		);

		$this->add_responsive_control(
			'scroll_button_align',
			[
				'label' => esc_html__( 'Button Alignment', 'bdthemes-element-pack' ),
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
				'default' => 'center',
			]
		);

		$this->add_control(
			'scroll_button_icon',
			[
				'label' => esc_html__( 'Button Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => 'fa fa-angle-up',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'scroll_button_icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
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
					'scroll_button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button .bdt-scroll-button-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-scroll-button .bdt-scroll-button-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_scroll_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_scroll_button_style' );

		$this->start_controls_tab(
			'tab_scroll_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'scroll_button_text_color',
			[
				'label' => esc_html__( 'Button Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_button_background_color',
			[
				'label' => esc_html__( 'Button Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'scroll_button_border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-scroll-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'scroll_button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-scroll-button',
			]
		);

		$this->add_control(
			'scroll_button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'scroll_button_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-scroll-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_scroll_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'scroll_button_hover_color',
			[
				'label' => esc_html__( 'Button Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_button_background_hover_color',
			[
				'label' => esc_html__( 'Button Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_button_hover_border_color',
			[
				'label' => esc_html__( 'Button Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'scroll_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scroll-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_button_hover_animation',
			[
				'label' => esc_html__( 'Button Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$scroll_settings = [];
		$scroll_settings['bdt-scroll'] = json_encode(array_filter([
			'duration' => $settings['duration']['size'],
			'offset'   => $settings['offset']['size'],
	    ]));

	    $scroll_settings['class'] = ['bdt-scroll-button bdt-button bdt-button-primary'];

		if ( $settings['scroll_button_hover_animation'] ) {
			$scroll_settings['class'][] = 'elementor-animation-'.esc_attr($settings['scroll_button_hover_animation']);
		}

		$this->add_render_attribute( 'bdt-scroll-wrapper', 'class', 'bdt-scroll-button-wrapper' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'bdt-scroll-wrapper' ); ?>>
			<a href="#<?php echo $settings['section_id']; ?>" <?php echo \element_pack_helper::attrs($scroll_settings); ?>>
				<?php $this->render_text(); ?>
			</a>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="bdt-scroll-button-wrapper">
			<a class="bdt-scroll-button bdt-button bdt-button-primary elementor-animation-{{ settings.scroll_button_hover_animation }}" href="#{{ settings.section_id }}" >
				<span class="bdt-scrollr-button-content-wrapper">
					<# if ( settings.scroll_button_icon ) { #>
					<span class="bdt-scroll-button-icon bdt-scroll-button-align-icon-{{ settings.icon_align }}">
						<i class="{{ settings.scroll_button_icon }}"></i>
					</span>
					<# } #>
					<span class="bdt-scroll-button-text">{{{ settings.scroll_button_text }}}</span>
				</span>
			</a>
		</div>
		<?php
	}

	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'bdt-scroll-button-content-wrapper' );
		$this->add_render_attribute( 'text', 'class', 'bdt-scroll-button-text' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['scroll_button_icon'] ) ) : ?>
			<span class="bdt-scroll-button-align-icon-<?php echo $settings['icon_align']; ?>">
				<i class="<?php echo esc_attr( $settings['scroll_button_icon'] ); ?>"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['scroll_button_text']; ?></span>
		</span>
		<?php
	}
}
