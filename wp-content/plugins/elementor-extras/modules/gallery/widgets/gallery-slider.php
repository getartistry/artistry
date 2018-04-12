<?php
namespace ElementorExtras\Modules\Gallery\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Transition;
use ElementorExtras\Modules\Gallery\Module;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Gallery_Slider
 *
 * @since 0.1.0
 */
class Gallery_Slider extends Extras_Widget {

	public function get_name() {
		return 'gallery-slider';
	}

	public function get_title() {
		return __( 'Gallery Slider', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-slider-gallery';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'jquery-slick',
			'jquery-resize',
		];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Gallery', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'wp_gallery',
				[
					'label' 	=> __( 'Add Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::GALLERY,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumbnails',
			[
				'label' => __( 'Thumbnails', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'thumbnail',
					'label'		=> __( 'Thumbnails Size', 'elementor-extras' ),
					'exclude' 	=> [ 'custom' ],
				]
			);

			$this->add_responsive_control(
				'columns',
				[
					'label' 	=> __( 'Columns', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '3',
					'tablet_default' 	=> '6',
					'mobile_default' 	=> '4',
					'options' 			=> [
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
					],
					'prefix_class'	=> 'ee-grid-columns%s-',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'gallery_rand',
				[
					'label' 	=> __( 'Ordering', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'' 		=> __( 'Default', 'elementor-extras' ),
						'rand' 	=> __( 'Random', 'elementor-extras' ),
					],
					'default' 	=> '',
				]
			);

			$this->add_control(
				'thumbnails_caption_type',
				[
					'label' 	=> __( 'Caption', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 				=> __( 'None', 'elementor-extras' ),
						'title' 		=> __( 'Title', 'elementor-extras' ),
						'caption' 		=> __( 'Caption', 'elementor-extras' ),
						'description' 	=> __( 'Description', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'view',
				[
					'label' 	=> __( 'View', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HIDDEN,
					'default' 	=> 'traditional',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_preview',
			[
				'label' => __( 'Preview', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'preview',
					'label'		=> __( 'Preview Size', 'elementor-extras' ),
					'default'	=> 'full',
					'exclude' 	=> [ 'custom' ],
				]
			);

			$this->add_control(
				'link_to',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'none',
					'options' 	=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'file' 		=> __( 'Media File', 'elementor-extras' ),
						'custom' 	=> __( 'Custom URL', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'link',
				[
					'label' 		=> 'Link to',
					'type' 			=> Controls_Manager::URL,
					'placeholder' 	=> __( 'http://your-link.com', 'elementor-extras' ),
					'condition' 	=> [
						'link_to' 	=> 'custom',
					],
					'show_label' 	=> false,
				]
			);

			$this->add_control(
				'open_lightbox',
				[
					'label' 	=> __( 'Lightbox', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'default',
					'options' 	=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'yes' 		=> __( 'Yes', 'elementor-extras' ),
						'no' 		=> __( 'No', 'elementor-extras' ),
					],
					'condition' => [
						'link_to' => 'file',
					],
				]
			);

			$this->add_control(
				'preview_stretch',
				[
					'label' 	=> __( 'Image Stretch', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'no' 	=> __( 'No', 'elementor-extras' ),
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'caption_type',
				[
					'label' 	=> __( 'Caption', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'caption',
					'options' 	=> [
						'' 				=> __( 'None', 'elementor-extras' ),
						'title' 		=> __( 'Title', 'elementor-extras' ),
						'caption' 		=> __( 'Caption', 'elementor-extras' ),
						'description' 	=> __( 'Description', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'pause_on_hover',
				[
					'label' 	=> __( 'Pause on Hover', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label' 	=> __( 'Autoplay', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay_speed',
				[
					'label' 	=> __( 'Autoplay Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> 5000,
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'infinite',
				[
					'label' 	=> __( 'Infinite Loop', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'adaptive_height',
				[
					'label' 	=> __( 'Adaptive Height', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect',
				[
					'label' 	=> __( 'Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'slide',
					'options' 	=> [
						'slide' 	=> __( 'Slide', 'elementor-extras' ),
						'fade' 		=> __( 'Fade', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'speed',
				[
					'label' 	=> __( 'Animation Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> 500,
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'direction',
				[
					'label' 	=> __( 'Direction', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'ltr',
					'options' 	=> [
						'ltr' 	=> __( 'Left', 'elementor-extras' ),
						'rtl' 	=> __( 'Right', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_preview',
			[
				'label' 	=> __( 'Preview', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs( 'preview_tabs' );

				$this->start_controls_tab( 'preview_layout', [ 'label' => __( 'Layout', 'elementor-extras' ) ] );

					$this->add_control(
						'preview_position',
						[
							'label' 	=> __( 'Position', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SELECT,
							'default' 	=> 'left',
							'tablet_default' 	=> 'top',
							'mobile_default' 	=> 'top',
							'options' 	=> [
								'top' 		=> __( 'Top', 'elementor-extras' ),
								'right' 	=> __( 'Right', 'elementor-extras' ),
								'left' 		=> __( 'Left', 'elementor-extras' ),
							],
							'prefix_class'	=> 'ee-gallery-slider--'
						]
					);

					$this->add_control(
						'preview_stack',
						[
							'label' 	=> __( 'Stack on', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SELECT,
							'default' 	=> 'tablet',
							'tablet_default' 	=> 'top',
							'mobile_default' 	=> 'top',
							'options' 	=> [
								'tablet' 	=> __( 'Tablet', 'elementor-extras' ),
								'mobile' 	=> __( 'Mobile', 'elementor-extras' ),
							],
							'prefix_class'	=> 'ee-gallery-slider--stack-',
						]
					);

					$this->add_responsive_control(
						'preview_width',
						[
							'label' 	=> __( 'Width (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'min' => 0,
									'max' => 100,
								],
							],
							'default' 	=> [
								'size' 	=> 70,
							],
							'condition'	=> [
								'preview_position!' => 'top',
							],
							'selectors'		=> [
								'{{WRAPPER}}.ee-gallery-slider--left .ee-gallery-slider__preview' => 'width: {{SIZE}}%',
								'{{WRAPPER}}.ee-gallery-slider--right .ee-gallery-slider__preview' => 'width: {{SIZE}}%',
								'{{WRAPPER}}.ee-gallery-slider--left .ee-gallery-slider__gallery' => 'width: calc(100% - {{SIZE}}%)',
								'{{WRAPPER}}.ee-gallery-slider--right .ee-gallery-slider__gallery' => 'width: calc(100% - {{SIZE}}%)',
							],
						]
					);

					$preview_horizontal_margin = is_rtl() ? 'margin-right' : 'margin-left';
					$preview_horizontal_padding = is_rtl() ? 'padding-right' : 'padding-left';

					$this->add_responsive_control(
						'preview_spacing',
						[
							'label' 	=> __( 'Spacing', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'min' => 0,
									'max' => 200,
								],
							],
							'default' 	=> [
								'size' 	=> 24,
							],
							'selectors' => [
								'{{WRAPPER}}.ee-gallery-slider--left .ee-gallery-slider > *,
								 {{WRAPPER}}.ee-gallery-slider--right .ee-gallery-slider > *' => $preview_horizontal_padding . ': {{SIZE}}{{UNIT}};',

								'{{WRAPPER}}.ee-gallery-slider--left .ee-gallery-slider,
								 {{WRAPPER}}.ee-gallery-slider--right .ee-gallery-slider' => $preview_horizontal_margin . ': -{{SIZE}}{{UNIT}};',

								'{{WRAPPER}}.ee-gallery-slider--top .ee-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',

								'(tablet){{WRAPPER}}.ee-gallery-slider--stack-tablet .ee-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',
								'(mobile){{WRAPPER}}.ee-gallery-slider--stack-mobile .ee-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'preview_images', [ 'label' => __( 'Images', 'elementor-extras' ) ] );

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'preview_border',
							'label' 	=> __( 'Image Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .slick-slider',
						]
					);

					$this->add_control(
						'preview_border_radius',
						[
							'label' 		=> __( 'Border Radius', 'elementor-extras' ),
							'type' 			=> Controls_Manager::DIMENSIONS,
							'size_units' 	=> [ 'px', '%' ],
							'selectors' 	=> [
								'{{WRAPPER}} .slick-slide' 	=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'preview_box_shadow',
							'selector' 	=> '{{WRAPPER}} .slick-slider',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_preview_captions',
			[
				'label' 	=> __( 'Preview Captions', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_type!' => '',
				],
			]
		);

			$this->add_control(
				'preview_vertical_align',
				[
					'label' 	=> __( 'Vertical Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'top' 	=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
					],
					'default' 		=> 'bottom',
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'preview_horizontal_align',
				[
					'label' 	=> __( 'Horizontal Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 		=> [
							'title' 	=> __( 'Justify', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'default' 		=> 'justify',
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'preview_align',
				[
					'label' 	=> __( 'Text Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'default' 	=> 'center',
					'selectors' => [
						'{{WRAPPER}} .ee-carousel__media__caption' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'preview_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-carousel__media__caption',
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'preview_text_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-carousel__media__caption' 	=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'preview_text_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-carousel__media__caption' 	=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 	=> [
						'caption_type!' => '',
					],
					'separator'		=> 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'preview_text_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-carousel__media__caption',
					'separator' => '',
					'condition'	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'preview_text_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-carousel__media__caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_preview_hover_effects',
			[
				'label' 	=> __( 'Preview Hover Effects', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_type!' => '',
				],
			]
		);

			$this->add_control(
				'hover_preview_captions_heading',
				[
					'label' 	=> __( 'Captions', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						'caption_type!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'preview_caption',
					'selector' 		=> '{{WRAPPER}} .ee-carousel__media__content,
										{{WRAPPER}} .ee-carousel__media__caption',
					'condition' 	=> [
						'caption_type!' => '',
					],
				]
			);

			$this->update_control( 'preview_caption_transition', array(
				'default' => 'custom',
			));

			$this->add_control(
				'preview_caption_effect',
				[
					'label' 	=> __( 'Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' => [
						''					=> __( 'None', 'elementor-extras' ),
						'fade-in'			=> __( 'Fade In', 'elementor-extras' ),
						'fade-out'			=> __( 'Fade Out', 'elementor-extras' ),
						'from-top'			=> __( 'From Top', 'elementor-extras' ),
						'from-right'		=> __( 'From Right', 'elementor-extras' ),
						'from-bottom'		=> __( 'From Bottom', 'elementor-extras' ),
						'from-left'			=> __( 'From Left', 'elementor-extras' ),
						'fade-from-top'		=> __( 'Fade From Top', 'elementor-extras' ),
						'fade-from-right'	=> __( 'Fade From Right', 'elementor-extras' ),
						'fade-from-bottom'	=> __( 'Fade From Bottom', 'elementor-extras' ),
						'fade-from-left'	=> __( 'Fade From Left', 'elementor-extras' ),
						'to-top'			=> __( 'To Top', 'elementor-extras' ),
						'to-right'			=> __( 'To Right', 'elementor-extras' ),
						'to-bottom'			=> __( 'To Bottom', 'elementor-extras' ),
						'to-left'			=> __( 'To Left', 'elementor-extras' ),
						'fade-to-top'		=> __( 'Fade To Top', 'elementor-extras' ),
						'fade-to-right'		=> __( 'Fade To Right', 'elementor-extras' ),
						'fade-to-bottom'	=> __( 'Fade To Bottom', 'elementor-extras' ),
						'fade-to-left'		=> __( 'Fade To Left', 'elementor-extras' ),
					],
					'condition' 	=> [
						'caption_type!' => '',
						'preview_caption_transition!' => '',
					],
				]
			);

			$this->start_controls_tabs( 'preview_caption_style' );

				$this->start_controls_tab( 'preview_caption_style_default', [
					'label' 	=> __( 'Default', 'elementor-extras' ),
					'condition' 	=> [
						'caption_type!' => '',
					],
				] );

					$this->add_control(
						'preview_text_color',
						[
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-carousel__media__caption' => 'color: {{VALUE}};',
							],
							'condition' 	=> [
								'caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' 		=> 'preview_text_background',
							'types' 	=> [ 'classic', 'gradient' ],
							'selector' 	=> '{{WRAPPER}} .ee-carousel__media__caption',
							'default'	=> 'classic',
							'condition' => [
								'caption_type!' => '',
							],
							'exclude'	=> [
								'image',
							]
						]
					);

					$this->add_control(
						'preview_text_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-carousel__media__caption' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'preview_text_box_shadow',
							'selector' 	=> '{{WRAPPER}} .ee-carousel__media__caption',
							'separator'	=> '',
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'preview_caption_style_hover', [
					'label' 	=> __( 'Hover', 'elementor-extras' ),
					'condition'	=> [
						'caption_type!' => '',
					],
				] );

					$this->add_control(
						'preview_text_color_hover',
						[
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-carousel__media:hover .ee-carousel__media__caption' => 'color: {{VALUE}};',
							],
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' 		=> 'preview_text_background_hover',
							'types' 	=> [ 'classic', 'gradient' ],
							'selector' 	=> '{{WRAPPER}} .ee-carousel__media:hover .ee-carousel__media__caption',
							'default'	=> 'classic',
							'condition' => [
								'caption_type!' => '',
							],
							'exclude'	=> [
								'image',
							]
						]
					);

					$this->add_control(
						'preview_text_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-carousel__media:hover .ee-carousel__media__caption' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'preview_text_border_color_hover',
						[
							'label' 	=> __( 'Border Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-carousel__media:hover .ee-carousel__media__caption' => 'border-color: {{VALUE}};',
							],
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'preview_text_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .ee-carousel__media:hover .ee-carousel__media__caption',
							'separator'	=> '',
							'condition'	=> [
								'caption_type!' => '',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_thumbnails',
			[
				'label' 	=> __( 'Thumbnails', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'image_align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'prefix_class'		=> 'ee-grid-halign--',
				]
			);
			
			$this->add_control(
				'image_vertical_align',
				[
					'label' 		=> __( 'Vertical Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'top'    		=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'prefix_class'		=> 'ee-grid-align--',
				]
			);

			$this->add_responsive_control(
				'image_stretch_ratio',
				[
					'label' 	=> __( 'Image Size Ratio', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> '100'
						],
					'range' 	=> [
						'px' 	=> [
							'min'	=> 10,
							'max' 	=> 200,
						],
					],
					'condition' => [
						'image_vertical_align' 	=> 'stretch',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__media:before' => 'padding-bottom: {{SIZE}}%;',
					],
				]
			);

			$columns_horizontal_margin = is_rtl() ? 'margin-right' : 'margin-left';
			$columns_horizontal_padding = is_rtl() ? 'padding-right' : 'padding-left';

			$this->add_responsive_control(
				'image_horizontal_spacing',
				[
					'label' 	=> __( 'Horizontal spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'default' 	=> [
						'size' 	=> 0,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__item' => $columns_horizontal_padding . ': {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-gallery' 		=> $columns_horizontal_margin . ': -{{SIZE}}{{UNIT}};',

						'(desktop){{WRAPPER}} .ee-gallery__item' 	=> 'max-width: calc( 100% / {{columns.SIZE}} );',
						'(tablet){{WRAPPER}} .ee-gallery__item' 	=> 'max-width: calc( 100% / {{columns_tablet.SIZE}} );',
						'(mobile){{WRAPPER}} .ee-gallery__item' 	=> 'max-width: calc( 100% / {{columns_mobile.SIZE}} );',
					],
				]
			);

			$this->add_responsive_control(
				'image_vertical_spacing',
				[
					'label' 	=> __( 'Vertical spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'default' 	=> [
						'size' 	=> '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__item' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-gallery' 		=> 'margin-bottom: -{{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'image_border',
					'label' 	=> __( 'Image Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__thumbnail',
					'separator' => '',
				]
			);

			$this->add_control(
				'image_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-gallery__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_captions',
			[
				'label' 	=> __( 'Thumbnails Captions', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'thumbnails_caption_type!' => '',
				],
			]
		);

			$this->add_control(
				'vertical_align',
				[
					'label' 	=> __( 'Vertical Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'top' 	=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
					],
					'default' 		=> 'bottom',
					'condition' 	=> [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'horizontal_align',
				[
					'label' 	=> __( 'Horizontal Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 		=> [
							'title' 	=> __( 'Justify', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'default' 		=> 'justify',
					'condition' 	=> [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'align',
				[
					'label' 	=> __( 'Text Align', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'default' 	=> 'center',
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__media__caption' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__caption',
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-gallery__media__caption' 	=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'text_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-gallery__media__caption' 	=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
					'separator'		=> 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'text_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__caption',
					'separator' => '',
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_control(
				'text_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-gallery__media__caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumbnails_hover_effects',
			[
				'label' 	=> __( 'Thumbnails Hover Effects', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'hover_thubmanils_images_heading',
				[
					'label' 	=> __( 'Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 		=> 'image',
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media,
									{{WRAPPER}} .ee-gallery__media__thumbnail,
									{{WRAPPER}} .ee-gallery__media__thumbnail img',
					'separator'	=> '',
				]
			);

			$this->update_control( 'image_transition', array(
				'default' => 'custom',
			));

			$this->start_controls_tabs( 'image_style' );

				$this->start_controls_tab( 'image_style_default', [ 'label' => __( 'Default', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__thumbnail img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_responsive_control(
						'image_scale',
						[
							'label' 		=> __( 'Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'range' 		=> [
								'px' 		=> [
									'min' => 1,
									'max' => 2,
									'step'=> 0.01,
								],
							],
							'selectors' 	=> [
								'{{WRAPPER}} .ee-gallery__media__thumbnail img' => 'transform: scale({{SIZE}});',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'image_style_hover', [ 'label' 	=> __( 'Hover', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color_hover',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__thumbnail img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_responsive_control(
						'image_scale_hover',
						[
							'label' 		=> __( 'Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'range' 		=> [
								'px' 		=> [
									'min' => 1,
									'max' => 2,
									'step'=> 0.01,
								],
							],
							'selectors' 	=> [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__thumbnail img' => 'transform: scale({{SIZE}});',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media:hover',
							'separator'	=> '',
						]
					);

					$this->add_control(
						'image_border_color_hover',
						[
							'label' 	=> __( 'Border Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__thumbnail' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'image_style_active', [ 'label' 	=> __( 'Active', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color_active',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity_active',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__thumbnail img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_responsive_control(
						'image_scale_active',
						[
							'label' 		=> __( 'Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'range' 		=> [
								'px' 		=> [
									'min' => 1,
									'max' => 2,
									'step'=> 0.01,
								],
							],
							'selectors' 	=> [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__thumbnail img' => 'transform: scale({{SIZE}});',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow_active',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media',
							'separator'	=> '',
						]
					);

					$this->add_control(
						'image_border_color_active',
						[
							'label' 	=> __( 'Border Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__thumbnail' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'hover_thubmanils_captions_heading',
				[
					'label' 	=> __( 'Captions', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' 	=> [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'caption',
					'selector' 		=> '{{WRAPPER}} .ee-gallery__media__content,
										{{WRAPPER}} .ee-gallery__media__caption',
					'condition' 	=> [
						'thumbnails_caption_type!' => '',
					],
				]
			);

			$this->update_control( 'caption_transition', array(
				'default' => 'custom',
			));

			$this->add_control(
				'caption_effect',
				[
					'label' 	=> __( 'Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' => [
						''					=> __( 'None', 'elementor-extras' ),
						'fade-in'			=> __( 'Fade In', 'elementor-extras' ),
						'fade-out'			=> __( 'Fade Out', 'elementor-extras' ),
						'from-top'			=> __( 'From Top', 'elementor-extras' ),
						'from-right'		=> __( 'From Right', 'elementor-extras' ),
						'from-bottom'		=> __( 'From Bottom', 'elementor-extras' ),
						'from-left'			=> __( 'From Left', 'elementor-extras' ),
						'fade-from-top'		=> __( 'Fade From Top', 'elementor-extras' ),
						'fade-from-right'	=> __( 'Fade From Right', 'elementor-extras' ),
						'fade-from-bottom'	=> __( 'Fade From Bottom', 'elementor-extras' ),
						'fade-from-left'	=> __( 'Fade From Left', 'elementor-extras' ),
						'to-top'			=> __( 'To Top', 'elementor-extras' ),
						'to-right'			=> __( 'To Right', 'elementor-extras' ),
						'to-bottom'			=> __( 'To Bottom', 'elementor-extras' ),
						'to-left'			=> __( 'To Left', 'elementor-extras' ),
						'fade-to-top'		=> __( 'Fade To Top', 'elementor-extras' ),
						'fade-to-right'		=> __( 'Fade To Right', 'elementor-extras' ),
						'fade-to-bottom'	=> __( 'Fade To Bottom', 'elementor-extras' ),
						'fade-to-left'		=> __( 'Fade To Left', 'elementor-extras' ),
					],
					'condition' 	=> [
						'thumbnails_caption_type!' 	=> '',
						'caption_transition!' 		=> '',
					],
				]
			);

			$this->start_controls_tabs( 'caption_style' );

				$this->start_controls_tab( 'caption_style_default', [
					'label' 	=> __( 'Default', 'elementor-extras' ),
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				] );

					$this->add_control(
						'text_color',
						[
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__caption' => 'color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_background_color',
						[
							'label' 	=> __( 'Background', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__caption' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__caption' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'thumbnails_caption_type!' => '',
								'tilt_enable' => 'yes',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'text_box_shadow',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media__caption',
							'separator'	=> '',
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'caption_style_hover', [
					'label' 	=> __( 'Hover', 'elementor-extras' ),
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				] );

					$this->add_control(
						'text_color_hover',
						[
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption' => 'color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_background_color_hover',
						[
							'label' 	=> __( 'Background', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'thumbnails_caption_type!' => '',
								'tilt_enable' => 'yes',
							],
						]
					);

					$this->add_control(
						'text_border_color_hover',
						[
							'label' 	=> __( 'Border Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption' => 'border-color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'text_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption',
							'separator'	=> '',
							'condition'	=> [
								'thumbnails_caption_type!' => '',
								'tilt_enable' => 'yes',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'caption_style_active', [
					'label' 	=> __( 'Active', 'elementor-extras' ),
					'condition' => [
						'thumbnails_caption_type!' => '',
					],
				] );

					$this->add_control(
						'text_color_active',
						[
							'label' 	=> __( 'Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__caption' => 'color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_background_color_active',
						[
							'label' 	=> __( 'Background', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__caption' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_control(
						'text_opacity_active',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__caption' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'thumbnails_caption_type!' => '',
								'tilt_enable' => 'yes',
							],
						]
					);

					$this->add_control(
						'text_border_color_active',
						[
							'label' 	=> __( 'Border Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default' 	=> '',
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__caption' => 'border-color: {{VALUE}};',
							],
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'text_box_shadow_active',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__caption',
							'separator'	=> '',
							'condition' => [
								'thumbnails_caption_type!' => '',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'hover_thubmanils_overlay_heading',
				[
					'label' 	=> __( 'Overlay', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 		=> 'overlay',
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__overlay',
					'separator'	=> 'after',
				]
			);

			$this->update_control( 'overlay_transition', array(
				'default' => 'custom',
			));

			$this->start_controls_tabs( 'overlay_style' );

				$this->start_controls_tab( 'overlay_style_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' 		=> 'overlay_background',
							'types' 	=> [ 'classic', 'gradient' ],
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media__overlay',
							'default'	=> 'classic',
							'exclude'	=> [
								'image',
							]
						]
					);

					$this->add_control(
						'overlay_blend',
						[
							'label' 		=> __( 'Blend mode', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SELECT,
							'default' 		=> 'normal',
							'options' => [
								'normal'			=> __( 'Normal', 'elementor-extras' ),
								'multiply'			=> __( 'Multiply', 'elementor-extras' ),
								'screen'			=> __( 'Screen', 'elementor-extras' ),
								'overlay'			=> __( 'Overlay', 'elementor-extras' ),
								'darken'			=> __( 'Darken', 'elementor-extras' ),
								'lighten'			=> __( 'Lighten', 'elementor-extras' ),
								'color'				=> __( 'Color', 'elementor-extras' ),
								'color-dodge'		=> __( 'Color Dodge', 'elementor-extras' ),
								'hue'				=> __( 'Hue', 'elementor-extras' ),
							],
							'selectors' 	=> [
								'{{WRAPPER}} .ee-gallery__media__overlay' => 'mix-blend-mode: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'overlay_blend_notice',
						[
							'type' 				=> Controls_Manager::RAW_HTML,
							'raw' 				=> sprintf( __( 'Please check blend mode support for your browser %1$s here %2$s', 'elementor-extras' ), '<a href="https://caniuse.com/#search=mix-blend-mode" target="_blank">', '</a>' ),
							'content_classes' 	=> 'ee-raw-html ee-raw-html__warning',
							'condition' 		=> [
								'overlay_blend!' => 'normal'
							],
						]
					);

					$this->add_responsive_control(
						'overlay_margin',
						[
							'label' 	=> __( 'Margin', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 48,
									'min' 	=> 0,
									'step' 	=> 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__overlay' => 'top: {{SIZE}}px; right: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__overlay' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media__overlay',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'overlay_style_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' 		=> 'overlay_background_hover',
							'types' 	=> [ 'classic', 'gradient' ],
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__overlay',
							'default'	=> 'classic',
							'exclude'	=> [
								'image',
							]
						]
					);

					$this->add_responsive_control(
						'overlay_margin_hover',
						[
							'label' 	=> __( 'Margin', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 48,
									'min' 	=> 0,
									'step' 	=> 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__overlay' => 'top: {{SIZE}}px; right: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__overlay' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border_hover',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__overlay',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'overlay_style_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' 		=> 'overlay_background_active',
							'types' 	=> [ 'classic', 'gradient' ],
							'selector' 	=> '{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__overlay',
							'default'	=> 'classic',
							'exclude'	=> [
								'image',
							]
						]
					);

					$this->add_responsive_control(
						'overlay_margin_active',
						[
							'label' 	=> __( 'Margin', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 48,
									'min' 	=> 0,
									'step' 	=> 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__overlay' => 'top: {{SIZE}}px; right: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity_active',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__overlay' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border_active',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .ee-gallery__item.is--active .ee-gallery__media__overlay',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'ee-gallery-slider' );

		$this->add_render_attribute( 'preview', 'class', [
			'ee-gallery-slider__preview',
			'elementor-slick-slider',
		] );

		$this->add_render_attribute( 'gallery-wrapper', 'class', 'ee-gallery-slider__gallery' );
		
		$this->add_render_attribute( 'gallery', 'class', [
			'ee-gallery',
			'ee-grid',
			'ee-grid--gallery',
			'ee-gallery__gallery',
			'ee-media-align--' . $settings['vertical_align'],
			'ee-media-align--' . $settings['horizontal_align'],
			'ee-media-effect__content--' . $settings['caption_effect'],
		] );

		$this->add_render_attribute( 'slider', 'class', [
			'elementor-image-carousel',
			'ee-carousel',
			'ee-gallery-slider__carousel',
			'ee-media-align--' . $settings['preview_vertical_align'],
			'ee-media-align--' . $settings['preview_horizontal_align'],
			'ee-media-effect__content--' . $settings['preview_caption_effect'],
		] );

		if ( $settings['columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['columns'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}

		$this->add_render_attribute( 'gallery-thumbnail', 'class', [
			'ee-media__thumbnail',
			'ee-gallery__media__thumbnail',
		] );

		$this->add_render_attribute( 'gallery-overlay', 'class', [
			'ee-media__overlay',
			'ee-gallery__media__overlay',
		] );

		$this->add_render_attribute( 'gallery-content', 'class', [
			'ee-media__content',
			'ee-gallery__media__content',
		] );

		$this->add_render_attribute( 'gallery-caption', 'class', [
			'wp-caption-text',
			'ee-media__content__caption',
			'ee-gallery__media__caption',
		] );

		$this->add_render_attribute( 'gallery-item', 'class', [
			'ee-gallery__item',
			'ee-grid__item',
		] );

		if ( 'yes' === $settings['preview_stretch'] ) {
			$this->add_render_attribute( 'slider', 'class', 'slick-image-stretch' );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'preview' ); ?> dir="<?php echo $settings['direction']; ?>">
				<div <?php echo $this->get_render_attribute_string( 'slider' ); ?>>
					<?php echo $this->render_carousel(); ?>
				</div>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'gallery-wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>
					<?php echo $this->render_wp_gallery(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {}

	protected function render_wp_gallery() {

		$settings 			= $this->get_settings();
		$gallery 			= $settings['wp_gallery'];
		$counter 			= 0;
		$media_tag 			= 'figure';

		foreach ( $gallery as $item ) {

			$image = Module::get_image_info( $item['id'], $item['url'], $settings['thumbnail_size'] );

			$this->add_render_attribute( 'gallery-media-' . $counter, 'class', [
				'ee-media',
				'ee-gallery__media',
			] );

			if ( empty( $image ) )
				continue;

			?>

			<div <?php echo $this->get_render_attribute_string( 'gallery-item' ); ?>>

				<<?php echo $media_tag; ?> <?php echo $this->get_render_attribute_string( 'gallery-media-' . $counter ); ?>>

					<?php $this->render_image_thumbnail( $image ); ?>

					<?php $this->render_image_overlay(); ?>

					<?php $this->render_image_caption( $image, $settings ); ?>

				</<?php echo $media_tag; ?>>
				
			</div>

		<?php

		$counter++;

		}
	}

	protected function render_image_thumbnail( $image ) {
		?><div <?php echo $this->get_render_attribute_string( 'gallery-thumbnail' ); ?>>
			<?php echo $image['image']; ?>
		</div><?php
	}

	protected function render_image_caption( $attachment, $settings ) {

		?><figcaption <?php echo $this->get_render_attribute_string( 'gallery-content' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'gallery-caption' ); ?>>
				<?php echo Module::get_image_caption( $attachment, $settings['thumbnails_caption_type'] );; ?>
			</div>
		</figcaption><?php
	}

	protected function render_image_overlay() {
		?><div <?php echo $this->get_render_attribute_string( 'gallery-overlay' ); ?>></div><?php
	}

	private function render_carousel() {

		$settings = $this->get_settings();

		$slides = [];

		foreach ( $settings['wp_gallery'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'preview', $settings );

			$image_html = '<img class="slick-slide-image" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '" />';

			$link = Module::get_link_url( $attachment, $settings );

			if ( $link ) {
				$link_key = 'link_' . $index;

				$this->add_render_attribute( $link_key, [
					'href' 								=> $link['url'],
					'class' 							=> 'elementor-clickable',
					'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
					'data-elementor-lightbox-slideshow' => $this->get_id(),
					'data-elementor-lightbox-index' 	=> $index,
				] );

				if ( ! empty( $link['is_external'] ) ) {
					$this->add_render_attribute( $link_key, 'target', '_blank' );
				}

				if ( ! empty( $link['nofollow'] ) ) {
					$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
				}

				$image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a>';
			}

			$image_caption = Module::get_image_caption( $attachment, $settings['caption_type'] );

			$slide_html = '<div class="slick-slide">';
			$slide_html .= '<figure class="slick-slide-inner ee-media ee-carousel__media">';
			$slide_html .= '<div class="ee-media__thumbnail ee-carousel__media__thumbnail">' . $image_html . '</div>';

			if ( ! empty( $image_caption ) ) {
				$slide_html .= '<div class="ee-media__content ee-carousel__media__content"><figcaption class="ee-media__content__caption ee-carousel__media__caption">' . $image_caption . '</figcaption></div>';
			}

			$slide_html .= '</figure></div>';

			$slides[] = $slide_html;

		}

		echo implode( '', $slides );

	}
}
