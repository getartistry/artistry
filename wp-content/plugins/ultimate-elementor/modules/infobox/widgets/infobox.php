<?php
/**
 * UAEL Infobox.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Infobox\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Infobox.
 */
class Infobox extends Common_Widget {

	/**
	 * Retrieve Infobox Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Infobox' );
	}

	/**
	 * Retrieve Infobox Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Infobox' );
	}

	/**
	 * Retrieve Infobox Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Infobox' );
	}

	/**
	 * Register Infobox controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_imgicon_content_controls();
		$this->register_separator_content_controls();
		$this->register_cta_content_controls();
		$this->register_typo_content_controls();
		$this->register_margin_content_controls();
	}

	/**
	 * Register Infobox General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {
		$this->start_controls_section(
			'section_general_field',
			[
				'label' => __( 'General', 'uael' ),
			]
		);

		$this->add_control(
			'infobox_title_prefix',
			[
				'label'    => __( 'Title Prefix', 'uael' ),
				'type'     => Controls_Manager::TEXT,
				'dynamic'  => [
					'active' => true,
				],
				'selector' => '{{WRAPPER}} .uael-infobox-title-prefix',
			]
		);
		$this->add_control(
			'infobox_title',
			[
				'label'    => __( 'Title', 'uael' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .uael-infobox-title',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => __( 'Info Box', 'uael' ),
			]
		);
		$this->add_control(
			'infobox_description',
			[
				'label'    => __( 'Description', 'uael' ),
				'type'     => Controls_Manager::TEXTAREA,
				'selector' => '{{WRAPPER}} .uael-infobox-text',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => __( 'Enter description text here.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​', 'uael' ),
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Register Infobox Image/Icon Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_imgicon_content_controls() {
		$this->start_controls_section(
			'section_image_field',
			[
				'label' => __( 'Image/Icon', 'uael' ),
			]
		);

		$this->add_control(
			'infobox_image_position',
			[
				'label'       => __( 'Select Position', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'above-title',
				'label_block' => false,
				'options'     => [
					'above-title' => __( 'Above Heading', 'uael' ),
					'below-title' => __( 'Below Heading', 'uael' ),
					'left-title'  => __( 'Left of Heading', 'uael' ),
					'right-title' => __( 'Right of Heading', 'uael' ),
					'left'        => __( 'Left of Text and Heading', 'uael' ),
					'right'       => __( 'Right of Text and Heading', 'uael' ),
				],
			]
		);

		$this->add_responsive_control(
			'infobox_align',
			[
				'label'     => __( 'Overall Alignment', 'uael' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'condition' => [
					'uael_infobox_image_type' => [ 'icon', 'photo' ],
					'infobox_image_position'  => [ 'above-title', 'below-title' ],
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox,  {{WRAPPER}} .uael-separator-parent' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'infobox_img_mob_view',
			[
				'label'       => __( 'Responsive Support', 'uael' ),
				'description' => __( 'Choose on what breakpoint the Infobox will stack.', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'tablet',
				'options'     => [
					'none'   => __( 'No', 'uael' ),
					'tablet' => __( 'For Tablet & Mobile ', 'uael' ),
					'mobile' => __( 'For Mobile Only', 'uael' ),
				],
				'condition'   => [
					'uael_infobox_image_type' => [ 'icon', 'photo' ],
					'infobox_image_position'  => [ 'left', 'right' ],
				],
			]
		);

		$this->add_control(
			'infobox_image_valign',
			[
				'label'       => __( 'Vertical Alignment', 'uael' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'top'    => [
						'title' => __( 'Top', 'uael' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'uael' ),
						'icon'  => 'eicon-v-align-middle',
					],
				],
				'default'     => 'top',
				'condition'   => [
					'uael_infobox_image_type' => [ 'icon', 'photo' ],
					'infobox_image_position'  => [ 'left-title', 'right-title', 'left', 'right' ],
				],
			]
		);

		$this->add_responsive_control(
			'infobox_overall_align',
			[
				'label'     => __( 'Overall Alignment', 'uael' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'condition' => [
					'uael_infobox_image_type!' => [ 'icon', 'photo' ],
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox,  {{WRAPPER}} .uael-separator-parent' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'infobox_imgicon_style',
			[
				'label'        => __( 'Image/Icon Style', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'normal',
				'label_block'  => false,
				'options'      => [
					'normal' => __( 'Normal', 'uael' ),
					'circle' => __( 'Circle Background', 'uael' ),
					'square' => __( 'Square / Rectangle Background', 'uael' ),
					'custom' => __( 'Design your own', 'uael' ),
				],
				'condition'    => [
					'uael_infobox_image_type!' => '',
				],
				'prefix_class' => 'uael-imgicon-style-',
			]
		);
		$this->add_control(
			'uael_infobox_image_type',
			[
				'label'   => __( 'Image Type', 'uael' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'photo' => [
						'title' => __( 'Image', 'uael' ),
						'icon'  => 'fa fa-picture-o',
					],
					'icon'  => [
						'title' => __( 'Font Icon', 'uael' ),
						'icon'  => 'fa fa-info-circle',
					],
				],
				'default' => 'icon',
				'toggle'  => true,
			]
		);
		$this->add_control(
			'infobox_icon_basics',
			[
				'label'     => __( 'Icon Basics', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'uael_infobox_image_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'infobox_select_icon',
			[
				'label'     => __( 'Select Icon', 'uael' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-star',
				'condition' => [
					'uael_infobox_image_type' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_icon_size',
			[
				'label'      => __( 'Size', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 40,
					'unit' => 'px',
				],
				'condition'  => [
					'uael_infobox_image_type' => 'icon',
					'infobox_select_icon!'    => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-icon-wrap .uael-icon i' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .uael-icon-wrap .uael-icon' => ' height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'infobox_icon_rotate',
			[
				'label'     => __( 'Rotate', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-icon-wrap .uael-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'uael_infobox_image_type' => 'icon',
					'infobox_select_icon!'    => '',
				],
			]
		);
		$this->add_control(
			'infobox_image_basics',
			[
				'label'     => __( 'Image Basics', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'uael_infobox_image_type' => 'photo',
				],
			]
		);
		$this->add_control(
			'uael_infobox_photo_type',
			[
				'label'       => __( 'Photo Source', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'media',
				'label_block' => false,
				'options'     => [
					'media' => __( 'Media Library', 'uael' ),
					'url'   => __( 'URL', 'uael' ),
				],
				'condition'   => [
					'uael_infobox_image_type' => 'photo',
				],
			]
		);
		$this->add_control(
			'infobox_image',
			[
				'label'     => __( 'Photo', 'uael' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'uael_infobox_image_type' => 'photo',
					'uael_infobox_photo_type' => 'media',

				],
			]
		);
		$this->add_control(
			'infobox_image_link',
			[
				'label'         => __( 'Photo URL', 'uael' ),
				'type'          => Controls_Manager::URL,
				'default'       => [
					'url' => '',
				],
				'show_external' => false, // Show the 'open in new tab' button.
				'condition'     => [
					'uael_infobox_image_type' => 'photo',
					'uael_infobox_photo_type' => 'url',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => [
					'uael_infobox_image_type' => 'photo',
					'uael_infobox_photo_type' => 'media',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_image_size',
			[
				'label'      => __( 'Width', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 2000,
					],
				],
				'default'    => [
					'size' => 150,
					'unit' => 'px',
				],
				'condition'  => [
					'uael_infobox_image_type' => 'photo',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-image img.uael-photo-img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

			$this->add_responsive_control(
				'infobox_icon_bg_size',
				[
					'label'      => __( 'Background Size', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'default'    => [
						'size' => 20,
						'unit' => 'px',
					],
					'condition'  => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style!'  => 'normal',
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon, {{WRAPPER}} .uael-image .uael-image-content img' => 'padding: {{SIZE}}{{UNIT}}; display:inline-block; box-sizing:content-box;',
					],
				]
			);

		$this->start_controls_tabs( 'infobox_tabs_icon_style' );

			$this->start_controls_tab(
				'infobox_icon_normal',
				[
					'label'     => __( 'Normal', 'uael' ),
					'condition' => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style!'  => 'normal',
					],
				]
			);
			$this->add_control(
				'infobox_icon_color',
				[
					'label'     => __( 'Icon Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'condition' => [
						'uael_infobox_image_type' => 'icon',
						'infobox_select_icon!'    => '',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'infobox_icons_hover_color',
				[
					'label'     => __( 'Icon Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'uael_infobox_image_type' => 'icon',
						'infobox_select_icon!'    => '',
						'infobox_imgicon_style'   => 'normal',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover > i, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-infobox-content .uael-imgicon-wrap i, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'infobox_icon_bgcolor',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'default'   => '',
					'condition' => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style!'  => 'normal',
					],
					'selectors' => [
						'{{WRAPPER}}.uael-imgicon-style-normal .uael-icon, {{WRAPPER}}.uael-imgicon-style-normal .uael-image .uael-image-content img' => 'background-color: none;',
						'{{WRAPPER}}.uael-imgicon-style-circle .uael-icon, {{WRAPPER}}.uael-imgicon-style-circle .uael-image .uael-image-content img, {{WRAPPER}}.uael-imgicon-style-square .uael-icon, {{WRAPPER}}.uael-imgicon-style-square .uael-image .uael-image-content img, {{WRAPPER}}.uael-imgicon-style-custom .uael-icon, {{WRAPPER}}.uael-imgicon-style-custom .uael-image .uael-image-content img' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'infobox_icon_border',
				[
					'label'       => __( 'Border Style', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'none',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'uael' ),
						'solid'  => __( 'Solid', 'uael' ),
						'double' => __( 'Double', 'uael' ),
						'dotted' => __( 'Dotted', 'uael' ),
						'dashed' => __( 'Dashed', 'uael' ),
					],
					'condition'   => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style'   => 'custom',
					],
					'selectors'   => [
						'{{WRAPPER}}.uael-imgicon-style-custom .uael-icon-wrap .uael-icon, {{WRAPPER}}.uael-imgicon-style-custom .uael-image .uael-image-content img' => 'border-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'infobox_icon_border_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'condition' => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style'   => 'custom',
						'infobox_icon_border!'    => 'none',
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}}.uael-imgicon-style-custom .uael-icon-wrap .uael-icon, {{WRAPPER}}.uael-imgicon-style-custom .uael-image .uael-image-content img' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'infobox_icon_border_size',
				[
					'label'      => __( 'Border Width', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '1',
						'bottom' => '1',
						'left'   => '1',
						'right'  => '1',
						'unit'   => 'px',
					],
					'condition'  => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style'   => 'custom',
						'infobox_icon_border!'    => 'none',
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-imgicon-style-custom .uael-icon-wrap .uael-icon, {{WRAPPER}}.uael-imgicon-style-custom .uael-image .uael-image-content img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
					],
				]
			);

			$this->add_responsive_control(
				'infobox_icon_border_radius',
				[
					'label'      => __( 'Rounded Corners', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'default'    => [
						'top'    => '5',
						'bottom' => '5',
						'left'   => '5',
						'right'  => '5',
						'unit'   => 'px',
					],
					'condition'  => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style!'  => [ 'normal', 'circle', 'square' ],
					],
					'selectors'  => [
						'{{WRAPPER}}.uael-imgicon-style-custom .uael-icon-wrap .uael-icon, {{WRAPPER}}.uael-imgicon-style-custom .uael-image .uael-image-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
					],
				]
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'infobox_icon_hover',
				[
					'label'     => __( 'Hover', 'uael' ),
					'condition' => [
						'uael_infobox_image_type' => [ 'icon', 'photo' ],
						'infobox_imgicon_style!'  => 'normal',
					],
				]
			);
				$this->add_control(
					'infobox_icon_hover_color',
					[
						'label'     => __( 'Icon Hover Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => [
							'uael_infobox_image_type' => 'icon',
							'infobox_select_icon!'    => '',
						],
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover > i, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-infobox-content .uael-imgicon-wrap i, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap i' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'infobox_icon_hover_bgcolor',
					[
						'label'     => __( 'Background Hover Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'condition' => [
							'uael_infobox_image_type' => [ 'icon', 'photo' ],
							'infobox_imgicon_style!'  => 'normal',
						],
						'selectors' => [
							'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover, {{WRAPPER}} .uael-image-content img:hover, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-infobox-content .uael-imgicon-wrap .uael-icon, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap .uael-icon, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-image .uael-image-content img, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap img' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'infobox_icon_hover_border',
					[
						'label'     => __( 'Border Hover Color', 'uael' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => [
							'uael_infobox_image_type' => [ 'icon', 'photo' ],
							'infobox_icon_border!'    => 'none',
							'infobox_imgicon_style!'  => 'normal',
						],
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .uael-icon-wrap .uael-icon:hover, {{WRAPPER}} .uael-image-content img:hover,  {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-infobox-content .uael-imgicon-wrap .uael-icon, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap .uael-icon, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-image .uael-image-content img, {{WRAPPER}} .uael-infobox-link-type-module .uael-infobox-module-link:hover ~ .uael-imgicon-wrap img ' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'infobox_imgicon_animation',
					[
						'label'     => __( 'Hover Animation', 'uael' ),
						'type'      => Controls_Manager::HOVER_ANIMATION,
						'condition' => [
							'uael_infobox_image_type' => [ 'icon', 'photo' ],
						],
					]
				);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		// End of section for Image Background color if custom design enabled.
		$this->end_controls_section();
	}

	/**
	 * Register Infobox Separator Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_separator_content_controls() {

		$this->start_controls_section(
			'section_separator_field',
			[
				'label' => __( 'Separator', 'uael' ),
			]
		);

		$this->add_control(
			'infobox_separator',
			[
				'label'        => __( 'Separator', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'infobox_separator_style',
			[
				'label'       => __( 'Style', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'solid'  => __( 'Solid', 'uael' ),
					'dashed' => __( 'Dashed', 'uael' ),
					'dotted' => __( 'Dotted', 'uael' ),
					'double' => __( 'Double', 'uael' ),
				],
				'condition'   => [
					'infobox_separator' => 'yes',
				],
				'selectors'   => [
					'{{WRAPPER}} .uael-separator' => 'border-top-style: {{VALUE}}; display: inline-block;',
				],
			]
		);

		$this->add_control(
			'infobox_separator_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'condition' => [
					'infobox_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-separator' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'infobox_separator_thickness',
			[
				'label'      => __( 'Thickness', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 3,
					'unit' => 'px',
				],
				'condition'  => [
					'infobox_separator' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_separator_width',
			[
				'label'          => __( 'Width', 'uael' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'        => [
					'size' => 30,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'label_block'    => true,
				'condition'      => [
					'infobox_separator' => 'yes',
				],
				'selectors'      => [
					'{{WRAPPER}} .uael-separator' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Infobox CTA Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_cta_content_controls() {
		$this->start_controls_section(
			'section_cta_field',
			[
				'label' => __( 'Call To Action', 'uael' ),
			]
		);

		$this->add_control(
			'infobox_cta_type',
			[
				'label'       => __( 'Type', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'uael' ),
					'link'   => __( 'Text', 'uael' ),
					'button' => __( 'Button', 'uael' ),
					'module' => __( 'Complete Box', 'uael' ),
				],
			]
		);

		$this->add_control(
			'infobox_link_text',
			[
				'label'     => __( 'Text', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More', 'uael' ),
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'infobox_cta_type' => 'link',
				],
			]
		);

		$this->add_control(
			'infobox_button_text',
			[
				'label'     => __( 'Text', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Click Here', 'uael' ),
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->add_control(
			'infobox_text_link',
			[
				'label'         => __( 'Link', 'uael' ),
				'type'          => Controls_Manager::URL,
				'default'       => [
					'url'         => '#',
					'is_external' => '',
				],
				'dynamic'       => [
					'active' => true,
				],
				'show_external' => true, // Show the 'open in new tab' button.
				'condition'     => [
					'infobox_cta_type!' => 'none',
				],
				'selector'      => '{{WRAPPER}} a.uael-infobox-cta-link',
			]
		);

		$this->add_control(
			'infobox_button_size',
			[
				'label'     => __( 'Size', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => [
					'xs' => __( 'Extra Small', 'uael' ),
					'sm' => __( 'Small', 'uael' ),
					'md' => __( 'Medium', 'uael' ),
					'lg' => __( 'Large', 'uael' ),
					'xl' => __( 'Extra Large', 'uael' ),
				],
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'infobox_button_border',
				'default'   => [
					'left'   => '1',
					'right'  => '1',
					'top'    => '1',
					'bottom' => '1',
					'unit'   => 'px',
				],
				'selector'  => '{{WRAPPER}} .elementor-button',
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->add_control(
			'infobox_button_animation',
			[
				'label'     => __( 'Hover Animation', 'uael' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'infobox_cta_type' => 'button',
				],
				'selector'  => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'infobox_icon_structure',
			[
				'label'     => __( 'Icon', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_cta_type' => [ 'button', 'link' ],
				],
			]
		);
		$this->add_control(
			'infobox_button_icon',
			[
				'label'     => __( 'Select Icon', 'uael' ),
				'type'      => Controls_Manager::ICON,
				'default'   => '',
				'condition' => [
					'infobox_cta_type' => [ 'button', 'link' ],
				],
			]
		);
		$this->add_control(
			'infobox_button_icon_position',
			[
				'label'       => __( 'Icon Position', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'label_block' => false,
				'options'     => [
					'right' => __( 'After Text', 'uael' ),
					'left'  => __( 'Before Text', 'uael' ),
				],
				'condition'   => [
					'infobox_cta_type'     => [ 'button', 'link' ],
					'infobox_button_icon!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_icon_spacing',
			[
				'label'     => __( 'Icon Spacing', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'   => [
					'size' => '5',
					'unit' => 'px',
				],
				'condition' => [
					'infobox_cta_type'     => [ 'button', 'link' ],
					'infobox_button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'infobox_button_colors',
			[
				'label'     => __( 'Colors', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->start_controls_tabs( 'infobox_tabs_button_style' );

			$this->start_controls_tab(
				'infobox_button_normal',
				[
					'label'     => __( 'Normal', 'uael' ),
					'condition' => [
						'infobox_cta_type' => 'button',
					],
				]
			);
			$this->add_control(
				'infobox_button_text_color',
				[
					'label'     => __( 'Text Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'infobox_cta_type' => 'button',
					],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'           => 'btn_background_color',
					'label'          => __( 'Background Color', 'uael' ),
					'types'          => [ 'classic', 'gradient' ],
					'selector'       => '{{WRAPPER}} .elementor-button',
					'separator'      => 'before',
					'condition'      => [
						'infobox_cta_type' => 'button',
					],
					'fields_options' => [
						'color' => [
							'scheme' => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
						],
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'infobox_button_hover',
				[
					'label'     => __( 'Hover', 'uael' ),
					'condition' => [
						'infobox_cta_type' => 'button',
					],
				]
			);
			$this->add_control(
				'infobox_button_hover_color',
				[
					'label'     => __( 'Text Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'infobox_cta_type' => 'button',
					],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'           => 'infobox_button_hover_bgcolor',
					'label'          => __( 'Background Hover Color', 'uael' ),
					'types'          => [ 'classic', 'gradient' ],
					'selector'       => '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover',
					'separator'      => 'before',
					'condition'      => [
						'infobox_cta_type' => 'button',
					],
					'fields_options' => [
						'color' => [
							'scheme' => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
						],
					],
				]
			);

			$this->add_control(
				'infobox_button_border_hover_color',
				[
					'label'     => __( 'Border Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'infobox_cta_type' => 'button',
					],
					'selectors' => [
						'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'infobox_button_radius',
			[
				'label'      => __( 'Rounded Corners', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'infobox_cta_type' => 'button',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'infobox_button_box_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button',
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_button_custom_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'infobox_cta_type' => 'button',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Infobox Typography Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_typo_content_controls() {
		$this->start_controls_section(
			'section_typography_field',
			[
				'label' => __( 'Typography', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'infobox_prefix_typo',
			[
				'label'     => __( 'Title Prefix', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_title_prefix!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_prefix_tag',
			[
				'label'     => __( 'Prefix Tag', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'  => __( 'H1', 'uael' ),
					'h2'  => __( 'H2', 'uael' ),
					'h3'  => __( 'H3', 'uael' ),
					'h4'  => __( 'H4', 'uael' ),
					'h5'  => __( 'H5', 'uael' ),
					'h6'  => __( 'H6', 'uael' ),
					'div' => __( 'div', 'uael' ),
					'p'   => __( 'p', 'uael' ),
				],
				'default'   => 'h5',
				'condition' => [
					'infobox_title_prefix!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'prefix_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'selector'  => '{{WRAPPER}} .uael-infobox-title-prefix',
				'condition' => [
					'infobox_title_prefix!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_prefix_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'separator' => 'after',
				'default'   => '',
				'condition' => [
					'infobox_title_prefix!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox-title-prefix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'infobox_title_typo',
			[
				'label'     => __( 'Title', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'infobox_title!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_title_tag',
			[
				'label'     => __( 'Title Tag', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'  => __( 'H1', 'uael' ),
					'h2'  => __( 'H2', 'uael' ),
					'h3'  => __( 'H3', 'uael' ),
					'h4'  => __( 'H4', 'uael' ),
					'h5'  => __( 'H5', 'uael' ),
					'h6'  => __( 'H6', 'uael' ),
					'div' => __( 'div', 'uael' ),
					'p'   => __( 'p', 'uael' ),
				],
				'default'   => 'h3',
				'condition' => [
					'infobox_title!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .uael-infobox-title',
				'condition' => [
					'infobox_title!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_title_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '',
				'condition' => [
					'infobox_title!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'infobox_desc_typo',
			[
				'label'     => __( 'Description', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_description!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'desc_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .uael-infobox-text',
				'condition' => [
					'infobox_description!' => '',
				],
			]
		);
		$this->add_control(
			'infobox_desc_color',
			[
				'label'     => __( 'Description Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default'   => '',
				'condition' => [
					'infobox_description!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'infobox_link_typo',
			[
				'label'     => __( 'CTA Link Text', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_cta_type' => 'link',
				],
			]
		);

		$this->add_control(
			'infobox_button_typo',
			[
				'label'     => __( 'CTA Button Text', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'infobox_cta_type' => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'cta_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'selector'  => '{{WRAPPER}} .uael-infobox-cta-link, {{WRAPPER}} .elementor-button, {{WRAPPER}} a.elementor-button',
				'condition' => [
					'infobox_cta_type' => [ 'link', 'button' ],
				],
			]
		);
		$this->add_control(
			'infobox_cta_color',
			[
				'label'     => __( 'Link Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .uael-infobox-cta-link' => 'color: {{VALUE}};',
				],
				'condition' => [
					'infobox_cta_type' => 'link',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Infobox Margin Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_margin_content_controls() {
		$this->start_controls_section(
			'section_margin_field',
			[
				'label' => __( 'Margins', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'infobox_title_margin',
			[
				'label'      => __( 'Title Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'bottom'   => '10',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-infobox-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'infobox_title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'title_prefix_margin',
			[
				'label'      => __( 'Prefix Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '0',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'size_units' => [ 'px' ],
				'condition'  => [
					'infobox_title_prefix!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-infobox-title-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_responsive_imgicon_margin',
			[
				'label'      => __( 'Image/Icon Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'condition'  => [
					'uael_infobox_image_type' => [ 'icon', 'photo' ],
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-imgicon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_desc_margin',
			[
				'label'      => __( 'Description Margins', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'condition'  => [
					'infobox_description!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-infobox-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'infobox_separator_margin',
			[
				'label'      => __( 'Separator Margins', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '20',
					'bottom'   => '20',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'condition'  => [
					'infobox_separator' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);
		$this->add_responsive_control(
			'infobox_cta_margin',
			[
				'label'      => __( 'CTA Margin', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '10',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-infobox-cta-link-style, {{WRAPPER}} .uael-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'infobox_cta_type' => [ 'link', 'button' ],
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Display Infobox Icon/Image.
	 *
	 * @since 0.0.1
	 * @access public
	 * @param object $position for Icon/Image position.
	 * @param object $settings for settings.
	 */
	public function render_image( $position, $settings ) {

		$set_pos = '';
		if ( 'icon' == $settings['uael_infobox_image_type'] || 'photo' == $settings['uael_infobox_image_type'] ) {
			$set_pos = $settings['infobox_image_position'];
		}
		if ( $position == $set_pos ) {
			if ( 'icon' == $settings['uael_infobox_image_type'] || 'photo' == $settings['uael_infobox_image_type'] ) { ?>
				<div class="uael-module-content uael-imgicon-wrap "><?php /* Module Wrap */ ?>
					<?php /*Icon Html */ ?>
					<?php if ( 'icon' == $settings['uael_infobox_image_type'] ) { ?>
						<div class="uael-icon-wrap elementor-animation-<?php echo $settings['infobox_imgicon_animation']; ?>">
							<span class="uael-icon">
								<i class="<?php echo $settings['infobox_select_icon']; ?>"></i>
							</span>
						</div>
					<?php } // Icon Html End. ?>

					<?php /* Photo Html */ ?>
					<?php
					if ( 'photo' == $settings['uael_infobox_image_type'] ) {
						if ( 'media' == $settings['uael_infobox_photo_type'] ) {
							if ( ! empty( $settings['infobox_image']['url'] ) ) {
								$image_url = $this->get_image_src();
								$this->add_render_attribute( 'infobox_image', 'src', $image_url );
								$this->add_render_attribute( 'infobox_image', 'alt', Control_Media::get_image_alt( $settings['infobox_image'] ) );

								$image_html = '<img class="uael-photo-img" ' . $this->get_render_attribute_string( 'infobox_image' ) . '>';
							}
						}
						if ( 'url' == $settings['uael_infobox_photo_type'] ) {
							if ( ! empty( $settings['infobox_image_link'] ) ) {

								$this->add_render_attribute( 'infobox_image_link', 'src', $settings['infobox_image_link']['url'] );

								$image_html = '<img class="uael-photo-img" ' . $this->get_render_attribute_string( 'infobox_image_link' ) . '>';
							}
						}
						?>
						<div class="uael-image" itemscope itemtype="http://schema.org/ImageObject">
							<div class="uael-image-content elementor-animation-<?php echo $settings['infobox_imgicon_animation']; ?>">
								<?php echo $image_html; ?>
							</div>
						</div>

					<?php } // Photo Html End. ?>
				</div>
			<?php
			}
		}
	}

	/**
	 * Render the Image URL as per source
	 *
	 * @since 1.0.0
	 */
	protected function get_image_src() {

		$url      = '';
		$settings = $this->get_settings();

		if ( 'photo' == $settings['uael_infobox_image_type'] ) {
			if ( 'media' == $settings['uael_infobox_photo_type'] ) {
				if ( '' != $settings['infobox_image']['id'] ) {
					$url = Group_Control_Image_Size::get_attachment_image_src( $settings['infobox_image']['id'], 'image', $settings );
				}
			}
		}
		return $url;
	}

	/**
	 * Display Infobox Title & Prefix.
	 *
	 * @since 0.0.1
	 * @access public
	 * @param object $settings for settings.
	 */
	public function render_title( $settings ) {
		$flag = false;
		if ( ( 'photo' == $settings['uael_infobox_image_type'] && 'left-title' == $settings['infobox_image_position'] ) || ( 'icon' == $settings['uael_infobox_image_type'] && 'left-title' == $settings['infobox_image_position'] ) ) {
			echo '<div class="left-title-image">';
			$flag = true;
		}
		if ( ( 'photo' == $settings['uael_infobox_image_type'] && 'right-title' == $settings['infobox_image_position'] ) || ( 'icon' == $settings['uael_infobox_image_type'] && 'right-title' == $settings['infobox_image_position'] ) ) {
			echo '<div class="right-title-image">';
			$flag = true;
		}
		$this->render_image( 'left-title', $settings );
		echo "<div class='uael-infobox-title-wrap'>";
		if ( '' != $settings['infobox_title_prefix'] ) {
			echo '<' . $settings['infobox_prefix_tag'] . ' class="uael-infobox-title-prefix elementor-inline-editing" data-elementor-setting-key="infobox_title_prefix" data-elementor-inline-editing-toolbar="basic" >' . $settings['infobox_title_prefix'] . '</' . $settings['infobox_prefix_tag'] . '>';
		}

		echo '<' . $settings['infobox_title_tag'] . ' class="uael-infobox-title elementor-inline-editing" data-elementor-setting-key="infobox_title" data-elementor-inline-editing-toolbar="basic" >';
		echo $settings['infobox_title'];
		echo '</' . $settings['infobox_title_tag'] . '>';
		echo '</div>';
		$this->render_image( 'right-title', $settings );

		if ( $flag ) {
			echo '</div>';
		}
	}
	/**
	 * Method render_link
	 *
	 * @since 0.0.1
	 * @access public
	 * @param object $settings for settings.
	 */
	public function render_link( $settings ) {

		$_nofollow = ( 'on' == $settings['infobox_text_link']['nofollow'] ) ? 'nofollow' : '';
		$_target   = ( 'on' == $settings['infobox_text_link']['is_external'] ) ? '_blank' : '';
		$_link     = ( isset( $settings['infobox_text_link']['url'] ) ) ? $settings['infobox_text_link']['url'] : '';
		if ( 'link' == $settings['infobox_cta_type'] ) {
			?>
			<div class="uael-infobox-cta-link-style">
				<a href="<?php echo $_link; ?>" rel="<?php echo $_nofollow; ?>" target="<?php echo $_target; ?>"  class="uael-infobox-cta-link"> 
					<?php
					if ( ! empty( $settings['infobox_button_icon'] ) && ( 'left' == $settings['infobox_button_icon_position'] ) ) {
						?>
						<i class="uael-infobox-link-icon uael-infobox-link-icon-before fa <?php echo $settings['infobox_button_icon']; ?>"></i>
					<?php } ?>
						<span class="elementor-inline-editing" data-elementor-setting-key="infobox_link_text" data-elementor-inline-editing-toolbar="basic"><?php echo $settings['infobox_link_text']; ?></span>
					<?php
					if ( ! empty( $settings['infobox_button_icon'] ) && 'right' == $settings['infobox_button_icon_position'] ) {
						?>
						<i class="uael-infobox-link-icon uael-infobox-link-icon-after fa <?php echo $settings['infobox_button_icon']; ?>"></i>
					<?php } ?>
				</a>
			</div>
		<?php
		} elseif ( 'button' == $settings['infobox_cta_type'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'uael-button-wrapper elementor-button-wrapper' );

			if ( ! empty( $settings['infobox_text_link']['url'] ) ) {
				$this->add_render_attribute( 'button', 'href', $settings['infobox_text_link']['url'] );
				$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );

				if ( $settings['infobox_text_link']['is_external'] ) {
					$this->add_render_attribute( 'button', 'target', '_blank' );
				}
				if ( $settings['infobox_text_link']['nofollow'] ) {
					$this->add_render_attribute( 'button', 'rel', 'nofollow' );
				}
			}
			$this->add_render_attribute( 'button', 'class', ' elementor-button' );

			if ( ! empty( $settings['infobox_button_size'] ) ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['infobox_button_size'] );
			}
			if ( $settings['infobox_button_animation'] ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['infobox_button_animation'] );
			}
			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<?php $this->render_button_text(); ?>
				</a>
			</div>
			<?php
		}
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_button_text() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['infobox_button_icon_position'] );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-button-icon' );

		$this->add_render_attribute( 'text', 'class', 'elementor-button-text' );
		$this->add_render_attribute( 'text', 'class', 'elementor-inline-editing' );

		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['infobox_button_icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['infobox_button_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>  data-elementor-setting-key="infobox_button_text" data-elementor-inline-editing-toolbar="none"><?php echo $settings['infobox_button_text']; ?></span>
		</span>
		<?php
	}

	/**
	 * Render Info Box output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {
		$html     = '';
		$settings = $this->get_settings();
		$node_id  = $this->get_id();
		ob_start();
		include 'template.php';
		$html = ob_get_clean();
		echo $html;

	}
	/**
	 * Render Info Box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		function render_title() {
			var flag = false;
			if ( ( 'photo' == settings.uael_infobox_image_type && 'left-title' == settings.infobox_image_position ) || ( 'icon' == settings.uael_infobox_image_type && 'left-title' == settings.infobox_image_position ) ) {
				#>
				<div class="left-title-image">
				<#
				flag = true;
			}
			if ( ( 'photo' == settings.uael_infobox_image_type && 'right-title' == settings.infobox_image_position ) || ( 'icon' == settings.uael_infobox_image_type && 'right-title' == settings.infobox_image_position ) ) {
				#>
				<div class="right-title-image">
				<#
				flag = true;
			} #>
			<# render_image( 'left-title' ); #>
			<div class='uael-infobox-title-wrap'>
				<# if ( '' != settings.infobox_title_prefix ) { #>
					<{{ settings.infobox_prefix_tag }} class="uael-infobox-title-prefix elementor-inline-editing" data-elementor-setting-key="infobox_title_prefix" data-elementor-inline-editing-toolbar="basic" > 
						{{{ settings.infobox_title_prefix }}} 
					</{{ settings.infobox_prefix_tag }}>
				<# } #>
				<{{ settings.infobox_title_tag }} class="uael-infobox-title elementor-inline-editing" data-elementor-setting-key="infobox_title" data-elementor-inline-editing-toolbar="basic" >
					{{{ settings.infobox_title }}}
				</{{ settings.infobox_title_tag }}>
			</div>
			<# render_image( 'right-title' ); #>
			<# if ( flag ) { #>
			</div>
			<# }
		} #>

		<# 
		function render_image( position ) {
			var set_pos = '';
			var media_img = '';
			if ( 'icon' == settings.uael_infobox_image_type || 'photo' == settings.uael_infobox_image_type ) {
				var set_pos = settings.infobox_image_position;
			}
			if ( position == set_pos ) {
				if ( 'icon' == settings.uael_infobox_image_type || 'photo' == settings.uael_infobox_image_type ) { #>
					<div class="uael-module-content uael-imgicon-wrap">
						<# if ( 'icon' == settings.uael_infobox_image_type ) { 
						view.addRenderAttribute( 'imgicon', 'class', 'uael-icon-wrap elementor-animation-' + settings.infobox_imgicon_animation );
						#>
						<div {{{ view.getRenderAttributeString( 'imgicon' ) }}} >
							<span class="uael-icon">
								<i class="{{ settings.infobox_select_icon }}"></i>
							</span>
						</div>
					<# } 
					if ( 'photo' == settings.uael_infobox_image_type ) { #>
						<div class="uael-image" itemscope itemtype="http://schema.org/ImageObject">
							<div class="uael-image-content elementor-animation-{{ settings.infobox_imgicon_animation }} ">
								<#
								if ( 'media' == settings.uael_infobox_photo_type ) {
									if ( '' != settings.infobox_image.url ) {

										var media_image = {
											id: settings.infobox_image.id,
											url: settings.infobox_image.url,
											size: settings.image_size,
											dimension: settings.image_custom_dimension,
											model: view.getEditModel()
										};
										media_img = elementor.imagesManager.getImageUrl( media_image );
										#>
										<img class="uael-photo-img" src="{{{ media_img }}}" >
										<#
									}
								}
								if ( 'url' == settings.uael_infobox_photo_type ) {
									if ( '' != settings.infobox_image_link ) {
										view.addRenderAttribute( 'infobox_image_link', 'src', settings.infobox_image_link.url );
										#>
										<img class="uael-photo-img" {{{ view.getRenderAttributeString( 'infobox_image_link' ) }}}>
										<#
									}
								} #>
							</div>
						</div>
					<# } #>
					</div>
				<# 
				}
			}
		} #>

		<#
		function render_link() {
			if ( 'link' == settings.infobox_cta_type ) {
				#>
				<div class="uael-infobox-cta-link-style">
					<a href="{{ settings.infobox_text_link }}" class="uael-infobox-cta-link">
						<# 
						if ( '' != settings.infobox_button_icon && 'left' == settings.infobox_button_icon_position ) {
						#>
							<i class="uael-infobox-link-icon uael-infobox-link-icon-before fa {{ settings.infobox_button_icon }}"></i>
						<# } #>
						<span class="elementor-inline-editing" data-elementor-setting-key="infobox_link_text" data-elementor-inline-editing-toolbar="basic">{{ settings.infobox_link_text }}</span>

						<# if ( '' != settings.infobox_button_icon && 'right' == settings.infobox_button_icon_position ) {
						#>
							<i class="uael-infobox-link-icon uael-infobox-link-icon-after fa {{ settings.infobox_button_icon }}"></i>
						<# } #>
					</a>
				</div>
			<# } 
			else if ( 'button' == settings.infobox_cta_type ) {

				view.addRenderAttribute( 'wrapper', 'class', 'uael-button-wrapper elementor-button-wrapper' );
				if ( '' != settings.infobox_text_link.url ) {
					view.addRenderAttribute( 'button', 'href', settings.infobox_text_link.url );
					view.addRenderAttribute( 'button', 'class', 'elementor-button-link' );	
				}
				view.addRenderAttribute( 'button', 'class', 'elementor-button' );

				if ( '' != settings.infobox_button_size ) {
					view.addRenderAttribute( 'button', 'class', 'elementor-size-' + settings.infobox_button_size );
				}

				if ( settings.infobox_button_animation ) {
					view.addRenderAttribute( 'button', 'class', 'elementor-animation-' + settings.infobox_button_animation );
				} #>
				<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
					<a  {{{ view.getRenderAttributeString( 'button' ) }}}>
						<#
						view.addRenderAttribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );

						view.addRenderAttribute( 'icon-align', 'class', 'elementor-align-icon-' + settings.infobox_button_icon_position );

						view.addRenderAttribute( 'icon-align', 'class', 'elementor-button-icon' );

						view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );

						view.addRenderAttribute( 'text', 'class', 'elementor-inline-editing' );

						#>
						<span {{{ view.getRenderAttributeString( 'content-wrapper' ) }}}>
							<# if ( '' != settings.infobox_button_icon ) { #>
							<span {{{ view.getRenderAttributeString( 'icon-align' ) }}}>
								<i class= "{{ settings.infobox_button_icon }}" aria-hidden="true"></i>
							</span>
							<# } #>
							<span {{{ view.getRenderAttributeString( 'text' ) }}} data-elementor-setting-key="infobox_button_text" data-elementor-inline-editing-toolbar="none">{{ settings.infobox_button_text }}</span>
						</span>
					</a>
				</div>				
			<#
			}
		}
		#>

		<#
			view.addRenderAttribute( 'classname', 'class', 'uael-module-content uael-infobox' );

			if ( 'icon' == settings.uael_infobox_image_type || 'photo' == settings.uael_infobox_image_type ) {
				if ( 'above-title' == settings.infobox_image_position || 'below-title' == settings.infobox_image_position ) {
					view.addRenderAttribute( 'classname', 'class', 'uael-infobox-' + settings.infobox_align );
				}
				if ( 'left-title' == settings.infobox_image_position || 'left' == settings.infobox_image_position ) {
					view.addRenderAttribute( 'classname', 'class', ' uael-infobox-left' );
				}
				if ( 'right-title' == settings.infobox_image_position || 'right' == settings.infobox_image_position ) {
					view.addRenderAttribute( 'classname', 'class', ' uael-infobox-right' );
				}
				if ( 'icon' == settings.uael_infobox_image_type ) {
					view.addRenderAttribute( 'classname', 'class', 'infobox-has-icon uael-infobox-icon-' + settings.infobox_image_position );
				}
				if ( 'photo' == settings.uael_infobox_image_type ) {
					view.addRenderAttribute( 'classname', 'class', 'infobox-has-photo uael-infobox-photo-' + settings.infobox_image_position );
				}
				if ( 'above-title' != settings.infobox_image_position && 'below-title' != settings.infobox_image_position ) {

					if ( 'middle' == settings.infobox_image_valign ) {
						view.addRenderAttribute( 'classname', 'class', ' uael-infobox-image-valign-middle' );
					} else {
						view.addRenderAttribute( 'classname', 'class', ' uael-infobox-image-valign-top' );
					}
				}
				if ( 'left' == settings.infobox_image_position || 'right' == settings.infobox_image_position ) {
					if ( 'tablet' == settings.infobox_img_mob_view ) {
							view.addRenderAttribute( 'classname', 'class', ' uael-infobox-stacked-tablet' );
					}
					if ( 'mobile' == settings.infobox_img_mob_view ) {
							view.addRenderAttribute( 'classname', 'class', ' uael-infobox-stacked-mobile' );
					}
				}
				if ( 'right' == settings.infobox_image_position ) {
					if ( 'tablet' == settings.infobox_img_mob_view ) {
							view.addRenderAttribute( 'classname', 'class', ' uael-reverse-order-tablet' );
					}
					if ( 'mobile' == settings.infobox_img_mob_view ) {
							view.addRenderAttribute( 'classname', 'class', ' uael-reverse-order-mobile' );
					}
				}
			} else {
				if ( 'left' == settings.infobox_overall_align || 'center' == settings.infobox_overall_align || 'right' == settings.infobox_overall_align ) {
					view.addRenderAttribute( 'classname', 'class', ' uael-infobox-' + settings.infobox_overall_align );
				}
			}

			view.addRenderAttribute( 'classname', 'class', 'uael-infobox-link-type-' + settings.infobox_cta_type );
		#>
			<div {{{ view.getRenderAttributeString( 'classname' ) }}}>
				<div class="uael-infobox-left-right-wrap">
					<#
					if ( 'module' == settings.infobox_cta_type && '' != settings.infobox_text_link ) {	
					#>
						<a href="{{ settings.infobox_text_link.url }}" class="uael-infobox-module-link"></a>
					<# } #>
					<# render_image( 'left' ); #>
					<div class="uael-infobox-content">
						<# render_image( 'above-title' ); #>
						<# render_title(); #>
						<# render_image( 'below-title' ); #>
						<# if ( 'yes' == settings.infobox_separator ) { #>
							<div class="uael-separator-parent">
								<div class="uael-separator"></div>		
							</div>
						<# } #>

						<div class="uael-infobox-text-wrap">
							<div class="uael-infobox-text elementor-inline-editing" data-elementor-setting-key="infobox_description" data-elementor-inline-editing-toolbar="advanced">
								{{{ settings.infobox_description }}}
							</div>
							<# render_link(); #>
						</div>
					</div>
					<# render_image( 'right' ); #>
				</div>
			</div>
		<?php
	}

}
