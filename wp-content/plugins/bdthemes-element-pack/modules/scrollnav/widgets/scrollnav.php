<?php
namespace ElementPack\Modules\Scrollnav\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Scrollnav extends Widget_Base {
	public function get_name() {
		return 'bdt-scrollnav';
	}

	public function get_title() {
		return esc_html__( 'Scrollnav', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content_scrollnav',
			[
				'label' => esc_html__( 'Scrollnav', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'navs',
			[
				'label' => esc_html__( 'Nav Items', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'nav_title' => esc_html__( 'Nav #1', 'bdthemes-element-pack' ),
						'nav_link'  => [
							'url' => esc_html__( '#section-1', 'bdthemes-element-pack' ),
						] 
					],
					[
						'nav_title'   => esc_html__( 'Nav #2', 'bdthemes-element-pack' ),
						'nav_link'  => [
							'url' => esc_html__( '#section-2', 'bdthemes-element-pack' ),
						]
					],
					[
						'nav_title'   => esc_html__( 'Nav #3', 'bdthemes-element-pack' ),
						'nav_link'  => [
							'url' => esc_html__( '#section-3', 'bdthemes-element-pack' ),
						]
					],
					[
						'nav_title'   => esc_html__( 'Nav #4', 'bdthemes-element-pack' ),
						'nav_link'  => [
							'url' => esc_html__( '#section-4', 'bdthemes-element-pack' ),
						]
					],
					[
						'nav_title'   => esc_html__( 'Nav #5', 'bdthemes-element-pack' ),
						'nav_link'  => [
							'url' => esc_html__( '#section-5', 'bdthemes-element-pack' ),
						]
					],
				],
				'fields' => [
					[
						'name'    => 'nav_title',
						'label'   => esc_html__( 'Nav Title', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::TEXT,
						'default' => esc_html__( 'Nav Title' , 'bdthemes-element-pack' ),
					],
					[
						'name'    => 'nav_link',
						'label'   => esc_html__( 'Link', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::URL,
						'default' => [ 'url' => '#' ],
						'description' => 'Add your section id WITH the # key. e.g: #my-id also you can add internal/external URL',
					],
				],
				'title_field' => '{{{ nav_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'       => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
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
				'condition' => [
					'fixed_nav!' => 'yes',
				]
			]
		);

		$this->add_control(
			'nav_style',
			[
				'label'   => esc_html__( 'Nav Style', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'dot'     => esc_html__( 'Dots', 'bdthemes-element-pack' ),
				]
			]
		);

		$this->add_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Tooltip Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'top'          => esc_html__('Top', 'bdthemes-element-pack') ,
					'top-left'     => esc_html__('Top-Left', 'bdthemes-element-pack') ,
					'top-right'    => esc_html__('Top-Right', 'bdthemes-element-pack') ,
					'bottom'       => esc_html__('Bottom', 'bdthemes-element-pack') ,
					'bottom-left'  => esc_html__('Bottom-Left', 'bdthemes-element-pack') ,
					'bottom-right' => esc_html__('Bottom-Right', 'bdthemes-element-pack') ,
					'left'         => esc_html__('Left', 'bdthemes-element-pack') ,
					'right'        => esc_html__('Right', 'bdthemes-element-pack') ,
				],
				'condition' => [
					'nav_style' => 'dot',
				]
			]
		);

		$this->add_control(
			'vertical_nav',
			[
				'label' => esc_html__( 'Vertical Nav', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'fixed_nav',
			[
				'label'        => esc_html__( 'Fixed Nav', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'bdt-scrollnav-fixed-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'nav_position',
			[
				'label'     => esc_html__( 'Nav Position', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center-left',
				'options'   => element_pack_position_options(),
				'condition' => [
					'fixed_nav' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'nav_offset',
			[
				'label'   => esc_html__( 'Nav Offset', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 250,
						'step' => 5,
					],
				],
				'condition' => [
					'fixed_nav' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav div[class*="bdt-navbar"]' => 'margin: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_spacing',
			[
				'label' => __( 'Nav Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'condition' => [
					'nav_style' => 'default',
				],
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
					'icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_indent',
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
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-scrollnav .bdt-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_nav',
			[
				'label'     => esc_html__( 'Default Nav', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'nav_style' => 'default',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style' );

		$this->start_controls_tab(
			'tab_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'nav_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-scrollnav ul li > a',
			]
		);

		$this->add_responsive_control(
			'nav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-scrollnav ul li > a',
			]
		);

		$this->add_responsive_control(
			'nav_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_margin',
			[
				'label'      => esc_html__( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'nav_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-scrollnav ul li > a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'nav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li > a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_active',
			[
				'label' => esc_html__( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'nav_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li.bdt-active > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_active_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li.bdt-active > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'nav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav ul li.bdt-active > a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_dot_nav',
			[
				'label'     => esc_html__( 'Dot Nav', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'nav_style' => 'dot',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style_dot' );

		$this->start_controls_tab(
			'tab_dot_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dot_nav_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'dot_nav_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a',
			]
		);

		$this->add_responsive_control(
			'dot_nav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'dot_nav_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a',
			]
		);

		$this->add_responsive_control(
			'dot_nav_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dot_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dot_nav_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dot_nav_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'dot_nav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li > a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dot_nav_active',
			[
				'label' => esc_html__( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'dot_nav_active_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li.bdt-active > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dot_nav_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'dot_nav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-scrollnav .bdt-dotnav > li.bdt-active > a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function render_loop_nav_list($list) {
		$settings  = $this->get_settings();
		$target    = ($list['nav_link']['is_external']) ? '_blank' : '_self';
		$rel       = ($list['nav_link']['nofollow']) ? 'rel="nofollow"' : '';
		$tooltip   = [];
		$tooltip[] = ( 'dot' == $settings['nav_style'] ) ?  ' title="'. esc_html($list["nav_title"]) .'"' : '';
		$tooltip[] = ( 'dot' == $settings['nav_style'] ) ?  ' bdt-tooltip="pos: '. esc_attr($settings["tooltip_position"]) .'"' : '';

		?>
	    <li>
			<a href="<?php echo esc_attr($list['nav_link']['url']); ?>" target="<?php echo esc_attr($target); ?>" <?php echo esc_attr($rel); ?> <?php echo implode(" ", $tooltip); ?>><?php echo esc_attr($list['nav_title']); ?>
				<?php if ($settings['icon']) : ?>
					<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
						<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
					</span>
				<?php endif; ?>
			</a>
		</li>
		<?php
	}

	public function render() {
		$settings          = $this->get_settings();
		$fixed_nav_class   = [];
		$nav_class         = [];
		$fixed_nav_class[] = ( 'yes' == $settings['fixed_nav'] ) ? 'bdt-position-'.esc_attr($settings['nav_position']).' bdt-position-z-index' : '';

		if ( 'dot' !== $settings['nav_style'] ) :
			$nav_class[] = ( 'yes' == $settings['vertical_nav'] ) ? 'bdt-nav bdt-nav-default' : 'bdt-navbar-nav';
		else :
			$nav_class[] = ( 'yes' == $settings['vertical_nav'] ) ? 'bdt-dotnav bdt-dotnav-vertical' : 'bdt-dotnav';
		endif;

		?>
		<div class="bdt-scrollnav bdt-navbar-container bdt-navbar-transparent <?php echo esc_attr(implode(" ", $fixed_nav_class)) ?>" bdt-navbar>
			<div class="bdt-navbar-<?php echo esc_attr($settings['alignment']); ?>">
				<ul class="<?php echo esc_attr(implode(" ", $nav_class)) ?>" bdt-scrollspy-nav="closest: li; scroll: true;">
					<?php
					foreach ($settings['navs'] as $key => $nav) : 
						$this->render_loop_nav_list($nav);
					endforeach;
					?>
				</ul>
			</div>
		</div>
	    <?php
	}
}
