<?php
namespace ElementPack\Modules\AdvancedHeading\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AdvancedHeading extends Widget_Base {

	public function get_name() {
		return 'bdt-advanced-heading';
	}

	public function get_title() {
		return esc_html__( 'Advanced Heading', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-type-tool';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'morphext' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_heading',
			[
				'label' => esc_html__( 'Heading', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label'       => esc_html__( 'Sub Heading', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your prefix title', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'SUB HEADING HERE', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'main_heading',
			[
				'label'       => esc_html__( 'Main Heading', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your main heading here', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'I am Advanced Heading', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => element_pack_title_tags(),
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
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
				'default' => 'center',
				'prefix_class' => 'elementor-align%s-',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_advanced_heading',
			[
				'label' => esc_html__( 'Advanced Heading', 'bdthemes-element-pack' ),
			]
		);
		$this->add_control(
			'advanced_heading',
			[
				'label'       => esc_html__( 'Advanced Heading', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your advanced heading', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'This heading will show as style as background and you can move and style many way.', 'bdthemes-element-pack' ),
				'default'     => esc_html__( "Advanced Heading", 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'advanced_heading_align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
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
			]
		);


		$this->add_control(
			'advanced_heading_x_position',
			[
				'label' => esc_html__( 'X Offset', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -800,
						'max' => 800,
					],
				],
				// 'selectors' => [
				// 	'{{WRAPPER}} .bdt-advanced-heading-content' => 'transform: translateX({{SIZE}}{{UNIT}});',
				// ],
			]
		);

		$this->add_control(
			'advanced_heading_y_position',
			[
				'label' => esc_html__( 'Y Offset', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -800,
						'max' => 800,
					],
				],
			]
		);

		$this->add_control(
			'advanced_heading_rotate',
			[
				'label' => esc_html__( 'Rotate', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 5,
					],
				],
			]
		);

		$this->add_control(
			'advanced_heading_origin',
			[
				'label'       => esc_html__( 'Rotate Origin', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'Origin work when you set rotate value', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'options'     => element_pack_position_options(),
			]
		);


		$this->add_control(
			'advanced_heading_hide',
			[
				'label'       => esc_html__( 'Hide at', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'Some cases you need to hide it because when you set heading at outer position mobile device can show wrong width in that case you can hide it at mobile or tablet device. if you set overflow hidden on section or body so you don\'t need it.', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'm',
				'options'     => [
					''  => esc_html__('Nothing', 'bdthemes-element-pack'),
					'm' => esc_html__('Tablet and Mobile ', 'bdthemes-element-pack'),
					's' => esc_html__('Mobile', 'bdthemes-element-pack'),
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_sub_heading',
			[
				'label'     => esc_html__( 'Sub Heading', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'sub_heading!' => '',
				]
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_heading_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'sub_heading_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading',
			]
		);

		$this->add_control(
			'sub_heading_style',
			[
				'label'       => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					''     => esc_html__('None', 'bdthemes-element-pack'),
					'line' => esc_html__('Line', 'bdthemes-element-pack'),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_heading_style_color',
			[
				'label' => esc_html__( 'Style Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading .line:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'sub_heading_style' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'sub_heading_style_width',
			[
				'label'   => __( 'Width', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading .line:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_heading_style' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'sub_heading_style_height',
			[
				'label'   => __( 'Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 48,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-sub-heading .line:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_heading_style' => 'line',
				],
			]
		);

		$this->add_control(
			'sub_heading_style_align',
			[
				'label' => esc_html__( 'Style Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'sub_heading_style' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'sub_heading_style_indent',
			[
				'label' => esc_html__( 'Style Spacing', 'bdthemes-element-pack' ),
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
					'sub_heading_style' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-advanced-heading .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main_heading',
			[
				'label' => esc_html__( 'Main Heading', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'main_heading!' => '',
				]
			]
		);

		$this->add_control(
			'main_heading_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-main-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'main_heading_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-main-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'main_heading_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-main-heading',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_advanced_heading',
			[
				'label' => esc_html__( 'Advanced Heading', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'advanced_heading_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'advanced_heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'advanced_heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'advanced_heading_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'advanced_heading_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'advanced_heading_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'advanced_heading_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'advanced_heading_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div',
			]
		);

		$this->add_control(
			'advanced_heading_opacity',
			[
				'label'   => __( 'Opacity', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0.05,
						'max'  => 1,
						'step' => 0.05,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-heading .bdt-advanced-heading-content > div' => 'opacity: {{SIZE}};',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings         = $this->get_settings();
		$id               = $this->get_id();
		$heading_html     = [];
		$advanced_heading = '';
		$sub_heading      = '';
		$main_heading     = '';

		if ( empty( $settings['sub_heading'] or $settings['advanced_heading'] or $settings['main_heading'] ) ) {
			return;
		}

		$this->add_render_attribute( 'heading', 'class', 'bdt-heading-tag' );


		if ($settings['sub_heading']) {
			$subh_style = '';
			if ('line' === $settings['sub_heading_style']) {
				$subh_style = '<div class="line bdt-button-icon-align-'.$settings['sub_heading_style_align'].'"></div>';
			}

			$sub_heading = '<div class="bdt-sub-heading"><div class="bdt-sub-heading-content">'.esc_attr($settings['sub_heading']).'</div>'.$subh_style.'</div> ';
		}

		if ($settings['advanced_heading']) {
			$avd_hclass   = ['bdt-advanced-heading-content'];
			$avd_hclass[] = ($settings['advanced_heading_align']) ? 'bdt-text-'.$settings['advanced_heading_align'] : '';
			$avd_hclass[] = ($settings['advanced_heading_hide']) ? 'bdt-visible@'. $settings['advanced_heading_hide'] : '';
			$avd_hcclass  = ($settings['advanced_heading_origin']) ? 'bdt-transform-origin-'.$settings['advanced_heading_origin'] : '';
			
			$avd_hstyle   = ['transform:'];
			$avd_hstyle[] = ($settings['advanced_heading_x_position']['size']) ? 'translateX('.$settings['advanced_heading_x_position']['size'].'px)' : '';
			$avd_hstyle[] = ($settings['advanced_heading_y_position']['size']) ? 'translateY('.$settings['advanced_heading_y_position']['size'].'px)' : '';
			$avd_hstyle[] = ($settings['advanced_heading_rotate']['size']) ? 'rotate('.$settings['advanced_heading_rotate']['size'].'deg)' : '';
			$avd_hstyle[] = ';';


	   		$advanced_heading = '<div class="'.implode(" ", $avd_hclass).'"><div class="'.$avd_hcclass.'" style="'.implode(" ", $avd_hstyle).'">' .$settings['advanced_heading']. '</div></div>';
		}

		if ($settings['main_heading']) :
			$main_heading = '<div class="bdt-main-heading">'.esc_attr($settings['main_heading']).'</div>';
		endif;


		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			$main_heading = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $main_heading );
		}

		$heading_html[] = '<div id ="'.$id.'" class="bdt-advanced-heading">';
		
		
		$heading_html[] = $advanced_heading;
		$heading_html[] = $sub_heading;
		$heading_html[] = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'heading' ), $main_heading );
		
		$heading_html[] = '</div>';

		echo implode("", $heading_html);

	}


	protected function _content_template() {
		?>
		<#
		var main_heading = settings.main_heading;
		var avdhx_transform = avdhy_transform = avdh_rotate = subh_style = '';

		if ( '' !== settings.link.url ) {
			main_heading = '<a href="' + settings.link.url + '">' + main_heading + '</a>';
		}

		if ('' !== settings.advanced_heading_x_position.size) {
			avdhx_transform = 'translateX(' + settings.advanced_heading_x_position.size + 'px)';
		}
		if ('' !== settings.advanced_heading_y_position.size) {
			avdhy_transform = 'translateY(' + settings.advanced_heading_y_position.size + 'px)';
		}
		if ('' !== settings.advanced_heading_rotate.size) {
			avdh_rotate = 'rotate(' + settings.advanced_heading_rotate.size + 'deg)';
		}	

		view.addRenderAttribute( 'main_heading', 'class', [ 'bdt-heading-tag', 'elementor-size-' + settings.size ] );

		view.addInlineEditingAttributes( 'main_heading' );

		view.addRenderAttribute('advanced_heading_content', 'class', ['bdt-advanced-heading-content'] );

		view.addRenderAttribute('advanced_heading', 'class', 'bdt-transform-origin-' + settings.advanced_heading_origin );
		

		if ('' !== settings.advanced_heading_align) {
			view.addRenderAttribute('advanced_heading_content', 'class', 'bdt-text-' + settings.advanced_heading_align );
		}

		view.addRenderAttribute('advanced_heading', 'style', 'transform:' + avdhx_transform + avdhy_transform + avdh_rotate + ';' );
		view.addInlineEditingAttributes( 'advanced_heading' );

		var avdh_content_print = view.getRenderAttributeString( 'advanced_heading_content' );
		var avdh_transform_print = view.getRenderAttributeString( 'advanced_heading' );

		if ( 'line' === settings.sub_heading_style ) {
			subh_align = ' bdt-button-icon-align-' + settings.sub_heading_style_align;
			subh_style = '<div class="line'+subh_align+'"></div>';
		}

		#>
		<div class="bdt-advanced-heading">
			<div <# print(avdh_content_print) #> >
				<div <# print(avdh_transform_print) #>>
					<# print(settings.advanced_heading) #>
				</div>
			</div>

			<div class="bdt-sub-heading">
				<div class="bdt-sub-heading-content">
					<# print(settings.sub_heading); #>
				</div>
				<# print(subh_style); #>
			</div>

			<{{settings.header_size}} <# print(view.getRenderAttributeString( 'main_heading' )) #> >
				<div class="bdt-main-heading"> {{main_heading}} </div>
			</{{settings.header_size}}>
		</div>

		
		<?php
	}


}
