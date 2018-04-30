<?php
namespace ElementPack\Modules\Member\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

use ElementPack\Modules\Member\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Member extends Widget_Base {
	public function get_name() {
		return 'bdt-member';
	}

	public function get_title() {
		return __( 'Member', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Phaedra( $this ) );
		$this->add_skin( new Skins\Skin_Calm( $this ) );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => __( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'photo',
			[
				'label'   => __( 'Choose Photo', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => BDTEP_ASSETS_URL.'images/member.svg',
				],
			]
		);

		$this->add_control(
			'name',
			[
				'label'       => __( 'Name', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'John Doe', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Member Name', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'role',
			[
				'label'       => __( 'Role', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Managing Director', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Member Role', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'description_text',
			[
				'label'       => __( 'Description', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Type here some info about this team member, the man very important person of our company.', 'bdthemes-element-pack' ),
				'placeholder' => __( 'Member Description', 'bdthemes-element-pack' ),
				'rows'        => 10,
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_social_link',
			[
				'label' => __( 'Social Icon', 'bdthemes-element-pack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_link_title',
			[
				'label'   => __( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Facebook',
			]
		);

		$repeater->add_control(
			'social_link',
			[
				'label'   => __( 'Link', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'http://www.facebook.com/bdthemes/', 'bdthemes-element-pack' ),
			]
		);

		$repeater->add_control(
			'social_icon',
			[
				'label'   => __( 'Choose Icon', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::ICON,
				'default' => 'fa fa-facebook',
			]
		);

		$repeater->add_control(
			'icon_background',
			[
				'label'     => __( 'Icon Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icons {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icons {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_link_list',
			[
				'type'    => Controls_Manager::REPEATER,
				'fields'  => array_values( $repeater->get_controls() ),
				'default' => [
					[
						'social_link'       => __( 'http://www.facebook.com/bdthemes/', 'bdthemes-element-pack' ),
						'social_icon'       => 'fa fa-facebook',
						'social_link_title' => 'Facebook',
					],
					[
						'social_link'       => __( 'http://www.twitter.com/bdthemes/', 'bdthemes-element-pack' ),
						'social_icon'       => 'fa fa-twitter',
						'social_link_title' => 'Twitter',
					],
					[
						'social_link'       => __( 'http://www.google-plus.com/bdthemes/', 'bdthemes-element-pack' ),
						'social_icon'       => 'fa fa-google-plus',
						'social_link_title' => 'Google-Plus',
					],
				],
				'title_field' => '{{{ social_link_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Member', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'   => __( 'Text Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_padding',
			[
				'label'      => __( 'Description Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-member .bdt-member-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_style_photo',
			[
				'label'     => __( 'Photo', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_photo_style');

		$this->start_controls_tab(
			'tab_photo_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'photo_background',
			[
				'label'     => __( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'photo_border',
				'label'       => __( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-member .bdt-member-photo',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'photo_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'photo_opacity',
			[
				'label'   => __( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'photo_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_photo_hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'photo_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'photo_hover_opacity',
			[
				'label'   => __( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-photo:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'photo_hover_animation',
			[
				'label'   => __( 'Animation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''     =>'None',
					'up'   => 'Scale Up',
					'down' => 'Scale Down',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_name',
			[
				'label' => __( 'Name', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .bdt-member .bdt-member-name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->add_responsive_control(
			'name_bottom_space',
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
					'{{WRAPPER}} .bdt-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_role',
			[
				'label' => __( 'Role', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'role_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-role' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'role_bottom_space',
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
					'{{WRAPPER}} .bdt-member .bdt-member-role' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'role_typography',
				'selector' => '{{WRAPPER}} .bdt-member .bdt-member-role',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label'     => __( 'Text', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .bdt-member .bdt-member-text',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_social_icon',
			[
				'label' => __( 'Social Icon', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_content_background',
			[
				'label'     => __( 'Icons Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icons' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'social_icon_content_padding',
			[
				'label'      => __( 'Icons Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-member .bdt-member-icons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->start_controls_tabs( 'tabs_social_icon_style' );

		$this->start_controls_tab(
			'tab_social_icon_normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_background',
			[
				'label'     => __( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icons_top_border_color',
			[
				'label'     => __( 'Top Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icons' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'social_icon_border',
				'label'       => __( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector' => '{{WRAPPER}} .bdt-member .bdt-member-icon',
			]
		);

		$this->add_control(
			'social_icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_icon_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_size',
			[
				'label'     => __( 'Icon Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon i'        => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-member .bdt-member-icon i:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_indent',
			[
				'label'     => __( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon + .bdt-member-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_tooltip',
			[
				'label'   => __( 'Tooltip', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_icon_hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_hover_background',
			[
				'label'     => __( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'social_icon_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-icon:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		?>
		<div class="bdt-member bdt-member-skin-default bdt-transition-toggle">
			
			<?php if ( ! empty( $settings['photo']['url'] ) ) :
				$photo_hover_animation = ( '' != $settings['photo_hover_animation'] ) ? ' bdt-transition-scale-'.$settings['photo_hover_animation'] : '';
			?>

				<div class="bdt-member-photo-wrapper">
					<div class="bdt-member-photo">
						<div class="<?php echo ($photo_hover_animation); ?>">
							<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'photo' ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="bdt-member-description">
				<?php if ( ! empty( $settings['name'] ) ) : ?>
					<span class="bdt-member-name"><?php echo $settings['name']; ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['role'] ) ) : ?>
					<span class="bdt-member-role"><?php echo $settings['role']; ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['description_text'] ) ) : ?>
					<div class="bdt-member-text bdt-content-wrap"><?php echo $settings['description_text']; ?></div>
				<?php endif; ?>
			</div>

			<div class="bdt-member-icons">
				<?php 
				foreach ( $settings['social_link_list'] as $link ) :
					$tooltip = ( 'yes' == $settings['social_icon_tooltip'] ) ? ' title="'.esc_attr( $link['social_link_title'] ).'" bdt-tooltip' : '';
				?>
					<a href="<?php echo esc_url( $link['social_link'] ); ?>" class="bdt-member-icon elementor-repeater-item-<?php echo $link['_id']; ?>" target="_blank"<?php echo $tooltip; ?>>
						<i class="<?php echo esc_attr( $link['social_icon'] ); ?>" aria-hidden="true"></i>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
