<?php
namespace ElementPack\Modules\CallOut\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Call_Out extends Widget_Base {

	//protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-call-out';
	}

	public function get_title() {
		return esc_html__( 'Call Out', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
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
			'title',
			[
				'label'       => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is your call to action title', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Call to action title', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Call to action description', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();


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
				'default' => esc_html__( 'Click Here', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => [
					'url'         => '#',
					'is_external' => '',
				],
			]
		);

		$this->add_control(
			'button_align',
			[
				'label'   => esc_html__( 'Align', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'   => esc_html__( 'Left', 'bdthemes-element-pack' ),
					'right'  => esc_html__( 'Right', 'bdthemes-element-pack' ),
					'center' => esc_html__( 'Center', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
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
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-callout .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-callout .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-callout .bdt-callout-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-callout .bdt-callout-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Description Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-callout .bdt-callout-description' => 'color: {{VALUE}};',
				],
				'condition' => [
					'description!' => '',
				],
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .bdt-callout .bdt-callout-description',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => [
					'description!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_footer_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-callout a.bdt-callout-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-callout a.bdt-callout-button',
				'condition' => [
					'button_text!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-callout a.bdt-callout-button',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-callout a.bdt-callout-button:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-callout a.bdt-callout-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
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

	public function render() {
		$settings = $this->get_settings();

		$external = ($settings['link']['is_external']) ? "_blank" : "_self";
		$link_url = empty( $settings['link']['url'] ) ? '#' : $settings['link']['url'];
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		?>
            <div class="bdt-callout bdt-grid bdt-grid-large bdt-flex-middle bdt-callout-button-align-<?php echo esc_html($settings['button_align']); ?>">
                <div class="bdt-width-expand bdt-first-column">
                	<?php if ($settings['title']) : ?>
                    	<h3 class="bdt-callout-title"><?php echo esc_html($settings['title']); ?></h3>
                	<?php endif; ?>
					<?php if ($settings['description']) : ?>
                    	<div class="bdt-callout-description"><?php echo strip_tags($settings['description']); ?></div>
					<?php endif; ?>
               </div>

                <div class="bdt-width-auto@m">
                    <a class="bdt-callout-button<?php echo esc_html($animation); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($external); ?>">
                    <?php echo esc_html( $settings['button_text'] ); ?>
                    	
                    	<?php if ($settings['icon']) : ?>
							<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
								<i class="<?php echo esc_html($settings['icon']); ?>"></i>
							</span>
						<?php endif; ?>

                    </a>
                </div>
            </div>

		<?php
	}

	public function _content_template() {
		?>

		<#
			var animation = (settings.button_hover_animation) ? ' elementor-animation-' + settings.button_hover_animation : '';
		#>

        <div class="bdt-callout bdt-grid bdt-grid-large bdt-flex-middle bdt-callout-button-align-{{ settings.button_align }}">
            <div class="bdt-width-expand bdt-first-column">
            	<# 
	            	if ('' !== settings.title) { 
	                	print('<h3 class="bdt-callout-title">' + settings.title +'</h3>');
	            	}
					if ('' !== settings.description) {
	                	print('<div class="bdt-callout-description">' + settings.description + '</div>');
					}
				#>
           </div>

            <div class="bdt-width-auto@m">
                <a class="bdt-callout-button{{animation}}" href="{{ settings.link }}" target="{{ settings.link.is_external }}">{{ settings.button_text }}

					<# if (settings.icon) { #>
						<span class="bdt-button-icon-align-{{settings.icon_align}}">
							<i class="{{settings.icon}}"></i>
						</span>
					<# } #>

                </a>
            </div>
        </div>

        <?php
	}
}
