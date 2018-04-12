<?php
namespace ElementorExtras\Modules\Gallery\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Transition;
use ElementorExtras\Modules\Gallery\Module;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Gallery
 *
 * @since 0.1.0
 */
class Gallery extends Extras_Widget {

	public function get_name() {
		return 'gallery-extra';
	}

	public function get_title() {
		return __( 'Gallery Extra', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-image-gallery';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'tilt',
			'parallax-gallery',
			'jquery-resize',
			'isotope',
			'packery-mode',
			'imagesloaded',
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
				'gallery_type',
				[
					'label' 	=> __( 'Type', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'wordpress',
					'options' 	=> [
						'wordpress'	=> __( 'Wordpress', 'elementor-extras' ),
						'manual' 	=> __( 'Manual', 'elementor-extras' ),
					],
					'separator'	=> 'after',
				]
			);

			$this->add_control(
				'images_heading',
				[
					'label' 	=> __( 'Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition'	=> [
						'gallery_type'	=> 'wordpress',
					]
				]
			);

			$this->add_control(
				'wp_gallery',
				[
					'label' 	=> __( 'Add Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::GALLERY,
					'condition'	=> [
						'gallery_type'	=> 'wordpress',
					]
				]
			);

			$gallery_items = new Repeater();

			$gallery_items->add_control(
				'image',
				[
					'label' 	=> __( 'Choose Image', 'elementor-extras' ),
					'type' 		=> Controls_Manager::MEDIA,
					'default' 	=> [
						'url' 	=> Utils::get_placeholder_image_src(),
					],
				]
			);

			$gallery_items->add_control(
				'custom_size',
				[
					'label'			=> __( 'Custom Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
				]
			);

			$gallery_items->add_responsive_control(
				'height_ratio',
				[
					'label' 	=> __( 'Image Size Ratio', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min'	=> 10,
							'max' 	=> 200,
						],
					],
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ee-media--stretch:before' => 'padding-bottom: {{SIZE}}%;',
					],
					'condition' => [
						'custom_size!' => ''
					],
				]
			);

			$gallery_items->add_responsive_control(
				'width',
				[
					'label' 		=> __( 'Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> '',
					'options' 		=> [
						'' 			=> __( 'Default', 'elementor-extras' ),
						'100%' 		=> __( 'Full Width', 'elementor-extras' ),
						'50%' 		=> __( 'One Half', 'elementor-extras' ),
						'33.3333%' 	=> __( 'One Third', 'elementor-extras' ),
						'66.6666%' 	=> __( 'Two Thirds', 'elementor-extras' ),
						'25%' 		=> __( 'One Quarter', 'elementor-extras' ),
						'75%' 		=> __( 'Three Quarters', 'elementor-extras' ),
						'20%' 		=> __( 'One Fifth', 'elementor-extras' ),
						'40%' 		=> __( 'Two Fifths', 'elementor-extras' ),
						'60%' 		=> __( 'Three Fifths', 'elementor-extras' ),
						'80%' 		=> __( 'Four Fifths', 'elementor-extras' ),
						'16.6666%' 	=> __( 'One Sixth', 'elementor-extras' ),
						'83.3333%' 	=> __( 'Five Sixths', 'elementor-extras' ),
					],
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.ee-grid__item--custom-size' => 'width: {{VALUE}};',
					],
					'condition' => [
						'custom_size!' => ''
					],
				]
			);

			$gallery_items->add_control(
				'link',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'file',
					'options' 	=> [
						'file' 			=> __( 'Media File', 'elementor-extras' ),
						'attachment' 	=> __( 'Attachment Page', 'elementor-extras' ),
						'custom' 		=> __( 'Custom URL', 'elementor-extras' ),
						'' 				=> __( 'None', 'elementor-extras' ),
					],
				]
			);

			$gallery_items->add_control(
				'link_url',
				[
					'label' 		=> __( 'Link', 'elementor-extras' ),
					'type' 			=> Controls_Manager::URL,
					'placeholder' 	=> esc_url( home_url( '/' ) ),
					'default' 		=> [
						'url' 		=> esc_url( home_url( '/' ) ),
					],
					'condition'	=> [
						'link'	=> 'custom',
					]
				]
			);

			$this->add_control(
				'gallery',
				[
					'label' 	=> __( 'Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[],
						[],
						[],
						[],
						[],
						[],
					],
					'fields' 		=> array_values( $gallery_items->get_controls() ),
					'condition'		=> [
						'gallery_type'		=> 'manual',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'thumbnail',
					'default'	=> 'full',
				]
			);

			$this->add_responsive_control(
				'columns',
				[
					'label' 	=> __( 'Columns', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '3',
					'tablet_default' 	=> '2',
					'mobile_default' 	=> '1',
					'options' 			=> [
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					],
					'prefix_class'	=> 'ee-grid-columns%s-',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'gallery_link',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'file',
					'options' 	=> [
						'file' 			=> __( 'Media File', 'elementor-extras' ),
						'attachment' 	=> __( 'Attachment Page', 'elementor-extras' ),
						'' 				=> __( 'None', 'elementor-extras' ),
					],
					'condition'	=> [
						'gallery_type'	=> 'wordpress',
					]
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
						'gallery_link' => 'file',
					],
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
				'gallery_display_caption',
				[
					'label' 	=> __( 'Caption', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 		=> __( 'Show', 'elementor-extras' ),
						'none' 	=> __( 'Hide', 'elementor-extras' ),
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__media__caption' => 'display: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'gallery_caption',
				[
					'label' 	=> __( 'Caption Type', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'caption',
					'options' 	=> [
						'title' 		=> __( 'Title', 'elementor-extras' ),
						'caption' 		=> __( 'Caption', 'elementor-extras' ),
						'description' 	=> __( 'Description', 'elementor-extras' ),
					],
					'condition' => [
						'gallery_display_caption' 	=> '',
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

			$this->add_control(
				'parallax_enable',
				[
					'label'			=> __( 'Parallax', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'parallax_disable_on',
				[
					'label' 	=> __( 'Disable for', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'mobile',
					'options' 			=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
						'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
					],
					'condition' => [
						'parallax_enable' => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'parallax_speed',
				[
					'label' 	=> __( 'Parallax speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> 0.5
					],
					'tablet_default' => [
						'size'	=> 0.5
					],
					'mobile_default' => [
						'size'	=> 0.5
					],
					'range' 	=> [
						'px' 	=> [
							'min'	=> 0.05,
							'max' 	=> 1,
							'step'	=> 0.01,
						],
					],
					'condition' => [
						'parallax_enable' => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'image_distance',
				[
					'label' 	=> __( 'Parallax Distance (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' 	=> [
						'size' 	=> '10',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__item.is--3d .ee-gallery__media' => 'margin-left: calc({{SIZE}}%/2); margin-right: calc({{SIZE}}%/2);',
					],
					'condition' => [
						'parallax_enable' 		=> 'yes',
						'image_vertical_align!' => 'stretch',
					],
				]
			);

			$this->add_control(
				'masonry_enable',
				[
					'label'			=> __( 'Masonry', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'condition' => [
						'parallax_enable!' 		=> 'yes',
					],
				]
			);

			$this->add_control(
				'masonry_layout',
				[
					'label' 		=> __( 'Layout', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'columns',
					'options' 		=> [
						'columns'    	=> [
							'title' 	=> __( 'Columns', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-masonry-columns',
						],
						'mixed' 		=> [
							'title' 	=> __( 'Mixed', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-masonry-mixed',
						],
					],
					'label_block'	=> false,
					'condition' 	=> [
						'masonry_enable!' 		=> '',
						'parallax_enable!' 		=> 'yes',
					],
					'prefix_class'		=> 'ee-grid-masonry-layout--',
				]
			);

			$this->add_control(
				'masonry_mixed_notice',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'You can now specify the width and height ratio of each image individually under the Gallery section.', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html ee-raw-html__info',
					'condition'			=> [
						'masonry_layout' => 'mixed'
					]
				]
			);

			$this->add_control(
				'tilt_enable',
				[
					'label'			=> __( 'Tilt', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'separator'		=> 'before',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'tilt_axis',
				[
					'label'			=> __( 'Axis', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> '',
					'options' 			=> [
						'' 		=> __( 'Both', 'elementor-extras' ),
						'x' 	=> __( 'X Only', 'elementor-extras' ),
						'y' 	=> __( 'Y Only', 'elementor-extras' ),
					],
					'frontend_available' => true,
					'condition' => [
						'tilt_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'tilt_amount',
				[
					'label' 	=> __( 'Amount', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 10,
							'max' => 40,
						],
					],
					'default' 	=> [
						'size' 	=> 20,
					],
					'frontend_available' => true,
					'condition' => [
						'tilt_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'tilt_caption_depth',
				[
					'label' 	=> __( 'Depth', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' 	=> [
						'size' 	=> 20,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__tilt .ee-gallery__media__content' => 'transform: translateZ({{SIZE}}px);',
						'{{WRAPPER}} .ee-gallery__tilt .ee-gallery__media__overlay' => 'transform: translateZ(calc({{SIZE}}px / 2));',
					],
					'condition' => [
						'tilt_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'tilt_scale',
				[
					'label' 	=> __( 'Scale', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 1,
							'max' 	=> 1.5,
							'step'	=> 0.01,
						],
					],
					'default' 		=> [
						'size' 		=> 1.05,
					],
					'frontend_available' => true,
					'condition' => [
						'tilt_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'tilt_speed',
				[
					'label' 	=> __( 'Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 100,
							'max' 	=> 1000,
							'step'	=> 50,
						],
					],
					'default' 		=> [
						'size' 		=> 800,
					],
					'frontend_available' => true,
					'condition' => [
						'tilt_enable' => 'yes',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_layout',
			[
				'label' 	=> __( 'Layout', 'elementor-extras' ),
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
						// 'masonry_enable' 		=> '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__media:before' => 'padding-bottom: {{SIZE}}%;',
					],
				]
			);

			$columns_horizontal_margin = is_rtl() ? 'margin-left' : 'margin-right';
			$columns_horizontal_padding = is_rtl() ? 'padding-left' : 'padding-right';

			$this->add_control(
				'image_horizontal_space',
				[
					'label' 	=> __( 'Horizontal Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'custom',
					'options' 	=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'custom' 	=> __( 'Custom', 'elementor-extras' ),
						'overlap' 	=> __( 'Overlap', 'elementor-extras' ),
					],
				]
			);

			$this->add_responsive_control(
				'image_horizontal_spacing',
				[
					'label' 	=> __( 'Horizontal Spacing', 'elementor-extras' ),
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
						'{{WRAPPER}} .ee-gallery' 					=> $columns_horizontal_margin . ': -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-gallery__item' 			=> $columns_horizontal_padding . ': {{SIZE}}{{UNIT}};',
					],
					'condition'	=> [
						'image_horizontal_space' => 'custom'
					]
				]
			);

			$this->add_responsive_control(
				'image_overlap',
				[
					'label' 	=> __( 'Horizontal Overlap', 'elementor-extras' ),
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
						'{{WRAPPER}} .ee-gallery' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-gallery__media' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					],
					'condition'	=> [
						'image_horizontal_space' => 'overlap'
					]
				]
			);

			$this->add_responsive_control(
				'image_vertical_spacing',
				[
					'label' 	=> __( 'Vertical Spacing', 'elementor-extras' ),
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
						'{{WRAPPER}} .ee-gallery__media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_images',
			[
				'label' 	=> __( 'Thumbnails', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
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
						'{{WRAPPER}} .ee-gallery__media,
						 {{WRAPPER}} .ee-gallery__tilt .ee-gallery__media__thumbnail,
						 {{WRAPPER}} .ee-gallery__tilt .ee-gallery__media__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'image_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-gallery__media__thumbnail' => 'background-color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' 	=> __( 'Captions', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_display_caption' => '',
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
						'gallery_display_caption' => '',
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
					'prefix_class'	=> 'ee-media-align--',
					'condition' 	=> [
						'gallery_display_caption' => '',
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
					'prefix_class'	=> 'ee-media-align--',
					'condition' 	=> [
						'gallery_display_caption' => '',
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
						'gallery_display_caption' => '',
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
						'gallery_display_caption' => '',
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
						'gallery_display_caption' => '',
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
					'condition' => [
						'gallery_display_caption' => '',
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
						'gallery_display_caption' => '',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hover_effects',
			[
				'label' 	=> __( 'Hover Effects', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'hover_images_heading',
				[
					'label' 	=> __( 'Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 		=> 'image_transition',
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__thumbnail,
									{{WRAPPER}} .ee-gallery__media__thumbnail img',
					'separator'	=> '',
				]
			);

			$this->start_controls_tabs( 'image_style' );

				$this->start_controls_tab(
					'image_style_default',
					[
						'label' => __( 'Default', 'elementor-extras' ),
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
							'condition' 	=> [
								'tilt_enable!' => 'yes',
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

				$this->start_controls_tab(
					'image_style_hover',
					[
						'label' 	=> __( 'Hover', 'elementor-extras' ),
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
							'condition' 	=> [
								'tilt_enable!' => 'yes',
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

			$this->end_controls_tabs();

			$this->add_control(
				'hover_overlay_heading',
				[
					'label' 	=> __( 'Overlay', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator'	=> 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 		=> 'overlay_transition',
					'selector' 	=> '{{WRAPPER}} .ee-gallery__media__overlay',
				]
			);

			$this->start_controls_tabs( 'overlay_style' );

			$this->start_controls_tab( 'overlay_style_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

					$this->add_control(
						'overlay_background_color',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media__overlay' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'overlay_blend',
						[
							'label' 		=> __( 'Blend mode', 'elementor-extras' ),
							'description'	=> __( 'Using blend mode removes the impact of depth properties from the tilt effect.', 'elementor-extras' ),
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

					$this->add_control(
						'overlay_background_color_hover',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__overlay' => 'background-color: {{VALUE}};',
							],
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

			$this->end_controls_tabs();

			$this->add_control(
				'hover_captions_heading',
				[
					'label' 	=> __( 'Captions', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator'	=> 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'content',
					'selector' 		=> '{{WRAPPER}} .ee-gallery__media__content,
										{{WRAPPER}} .ee-gallery__media__caption',
					'condition' 	=> [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->update_control( 'content_transition', array(
				'default' => 'custom',
			));

			$this->add_control(
				'content_effect',
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
					'prefix_class'	=> 'ee-media-effect__content--',
					'condition' 	=> [
						'gallery_display_caption' 	=> '',
						'tilt_enable!' 				=> 'yes',
						'content_transition!' 		=> '',
					],
				]
			);

			$this->start_controls_tabs( 'caption_style' );

				$this->start_controls_tab( 'caption_style_default', [
					'label' 	=> __( 'Default', 'elementor-extras' ),
					'condition' => [
						'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'caption_style_hover', [
					'label' 	=> __( 'Hover', 'elementor-extras' ),
					'condition' => [
						'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
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
								'gallery_display_caption' => '',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' 		=> 'text_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .ee-gallery__media:hover .ee-gallery__media__caption',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'wrapper', 'class', 'ee-gallery-wrapper' );
		$this->add_render_attribute( 'gallery', 'class', [
			'ee-gallery',
			'ee-grid',
			'ee-grid--gallery',
			'ee-gallery__gallery',
		] );

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

		if ( 'yes' === $settings['tilt_enable'] ) {
			$this->add_render_attribute( 'gallery-tilt', 'class', 'ee-gallery__tilt' );
			$this->add_render_attribute( 'gallery-tilt-shadow', 'class', 'ee-gallery__tilt__shadow' );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>

				<?php

				$this->render_gallery_sizer();

				if ( 'manual' === $settings['gallery_type'] ) {
					$this->render_gallery_image();
				} else {
					$this->render_wp_gallery_image();
				}

				?>
			</div>
		</div>
		<?php

		$this->render_masonry_script();
	}

	protected function render_wp_gallery_image() {

		$settings 			= $this->get_settings();
		$gallery 			= $settings['wp_gallery'];
		$media_tag 			= 'figure';

		if ( ! empty( $settings['gallery_rand'] ) ) {
			shuffle( $gallery );
		}

		if ( '' !== $settings['gallery_link'] ) {
			$media_tag = 'a';
		}

		foreach ( $gallery as $index => $item ) {

			$gallery_media_key 	= 'gallery-media-' . $index;
			$gallery_item_key 	= 'gallery-item-' . $index;
			$item['image'] 		= Module::get_image_info( $item['id'], $item['url'], $settings['thumbnail_size'] );

			$this->add_render_attribute( $gallery_media_key, 'class', [
				'ee-media',
				'ee-gallery__media',
			] );

			$this->add_render_attribute( $gallery_item_key, 'class', [
				'ee-gallery__item',
				'ee-grid__item',
			] );

			if ( '' !== $settings['gallery_link'] ) {

				if ( 'file' === $settings['gallery_link'] ) {

					$item_link 	= wp_get_attachment_image_src( $item['id'], 'full' );
					$item_link	= $item_link[0];

					$this->add_render_attribute( $gallery_media_key, [
						'class' 							=> 'elementor-clickable',
						'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
						'data-elementor-lightbox-slideshow' => $this->get_id(),
					] );

				} else if ( 'attachment' === $settings['gallery_link'] ) {

					$item_link 	= get_attachment_link( $item['id'] );

				}

				$this->add_render_attribute( $gallery_media_key, 'href', $item_link );
			}

			?>

			<div <?php echo $this->get_render_attribute_string( $gallery_item_key ); ?>>

				<?php if ( 'yes' === $settings['tilt_enable'] ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'gallery-tilt' ); ?>>
				<?php } ?>

					<<?php echo $media_tag; ?> <?php echo $this->get_render_attribute_string( $gallery_media_key ); ?>>

						<?php $this->render_image_thumbnail( $item, $index ); ?>
						<?php $this->render_image_overlay(); ?>
						<?php $this->render_image_caption( $item, $index ); ?>

					</<?php echo $media_tag; ?>>

				<?php if ( 'yes' === $settings['tilt_enable'] ) { ?>
				</div>
				<?php } ?>
				
			</div><?php
		}
	}

	protected function render_gallery_image() {

		$settings 			= $this->get_settings();
		$gallery 			= $settings['gallery'];

		if ( ! empty( $settings['gallery_rand'] ) ) {
			shuffle( $gallery );
		}

		foreach ( $gallery as $index => $item ) {

			$media_tag 	= 'figure';
			$item_key 	= $this->get_repeater_setting_key( 'item', 'gallery', $index );
			$media_key 	= $this->get_repeater_setting_key( 'media', 'gallery', $index );

			$this->add_render_attribute( $item_key, 'class', [
				'ee-gallery__item',
				'ee-grid__item',
				'elementor-repeater-item-' . $item['_id'],
			] );

			if ( '' !== $item['width'] ) {
				$this->add_render_attribute( $item_key, 'class', 'ee-grid__item--' . $item['width'] );
			}

			$this->add_render_attribute( $media_key, 'class', [
				'ee-media',
				'ee-gallery__media',
			] );

			if ( 'yes' === $item['custom_size'] ) {
				$this->add_render_attribute( $media_key, 'class', 'ee-media--stretch' );
				$this->add_render_attribute( $item_key, 'class', 'ee-grid__item--custom-size' );
			}

			if ( '' !== $item['link'] ) {
				$media_tag = 'a';
			}

			if ( '' !== $item['link'] ) {

				if ( 'file' === $item['link'] ) {

					$item_link 	= $item['image']['url'];

					if ( $item['image']['id'] ) {
						$item_link 	= wp_get_attachment_image_src( $item['image']['id'], 'full' );
						$item_link	= $item_link[0];
					}

					$this->add_render_attribute( $media_key, [
						'class' 							=> 'elementor-clickable',
						'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
						'data-elementor-lightbox-slideshow' => $this->get_id(),
					] );

				} else if ( 'attachment' === $item['link'] ) {

					$item_link 	= get_attachment_link( $item['image']['id'] );

				} else if ( 'custom' === $item['link'] ) {

					if ( ! empty( $item['link_url']['url'] ) ) {

						$item_link = $item['link_url']['url'];

						if ( ! empty( $item['link_url']['is_external'] ) ) {
							$this->add_render_attribute( $media_key, 'target', '_blank' );
						}

						if ( ! empty( $item['link_url']['nofollow'] ) ) {
							$this->add_render_attribute( $media_key, 'rel', 'nofollow' );
						}
					}

				}

				$this->add_render_attribute( $media_key, 'href', $item_link );
			}

			?>

			<div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
				<?php if ( 'yes' === $settings['tilt_enable'] ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'gallery-tilt' ); ?>>
				<?php } ?>
					<<?php echo $media_tag; ?> <?php echo $this->get_render_attribute_string( $media_key ); ?>>

						<?php $this->render_image_thumbnail( $item, $index ); ?>
						<?php $this->render_image_overlay(); ?>
						<?php $this->render_image_caption( $item, $index ); ?>

					</<?php echo $media_tag; ?>>
				<?php if ( 'yes' === $settings['tilt_enable'] ) { ?>
				</div>
				<?php } ?>
			</div>

		<?php }
	}

	protected function render_gallery_sizer() {
		$settings = $this->get_settings();

		if ( 'yes' === $settings['masonry_enable'] && 'yes' !== $settings['parallax_enable'] ) {
			?><div class="ee-grid__item ee-grid__item--sizer"></div><?php
		}
	}

	protected function render_image_thumbnail( $item, $index ) {

		$settings 			= $this->get_settings();
		$thumbnail_url 		= $this->get_thumbnail_image_url( $item, $settings );
		$thumbnail_alt 		= $this->get_thumbnail_image_alt( $item );
		$thumbnail_title 	= $this->get_thumbnail_image_title( $item );
		$image_key 			= $this->get_repeater_setting_key( 'image', 'gallery', $index );

		$this->add_render_attribute( $image_key, 'src', $thumbnail_url );

		if ( '' !== $thumbnail_alt ) {
			$this->add_render_attribute( $image_key, 'alt', $thumbnail_alt );
		}

		if ( '' !== $thumbnail_title ) {
			$this->add_render_attribute( $image_key, 'title', $thumbnail_title );
		}

		?><div <?php echo $this->get_render_attribute_string( 'gallery-thumbnail' ); ?>>
			<img <?php echo $this->get_render_attribute_string( $image_key ); ?> />
		</div><?php
	}

	protected function render_image_caption( $item, $index ) {
		$caption = $this->get_image_caption( $item );

		if ( ! $caption )
			return;

		?><figcaption <?php echo $this->get_render_attribute_string( 'gallery-content' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'gallery-caption' ); ?>>
				<?php echo $caption; ?>
			</div>
		</figcaption><?php
	}

	protected function render_image_overlay() {
		?><div <?php echo $this->get_render_attribute_string( 'gallery-overlay' ); ?>></div><?php
	}

	protected function get_thumbnail_image_url( $item, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );

		if ( ! $image_url ) {
			$image_url = $item['image']['url'];
		}

		return $image_url;
	}

	protected function get_thumbnail_image_alt( $item ) {
		return trim( strip_tags( get_post_meta( $item['image']['id'], '_wp_attachment_image_alt', true) ) );
	}

	protected function get_thumbnail_image_title( $item ) {
		return trim( strip_tags( get_the_title( $item['image']['id'] ) ) );
	}

	protected function get_image_caption( $item ) {
		$caption_type = $this->get_settings( 'gallery_caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $item['image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	protected function _content_template() {}

	protected function render_masonry_script() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false )
			return;

		if ( 'yes' !== $this->get_settings( 'masonry_enable' ) || 'yes' === $this->get_settings( 'parallax_enable' ) )
			return;

		?><script type="text/javascript">
        	jQuery( document ).ready( function( $ ) {

				$( '.ee-gallery' ).each( function() {

					var $scope_id = '<?php echo $this->get_id(); ?>',
        				$scope = $( '[data-id="' + $scope_id + '"]' );

        			// Don't move forward if this is not our widget
        			if ( $(this).closest( $scope ).length < 1 ) {
        				return;
        			}

					var $gallery 		= $(this),
						isotopeArgs = {
							itemSelector	: '.ee-gallery__item',
			  				percentPosition : true,
			  				hiddenStyle 	: {
			  					opacity 	: 0,
			  				},
						};

					$gallery.imagesLoaded( function() {

						var $isotope = $gallery.isotope( isotopeArgs );
						var isotopeInstance = $gallery.data( 'isotope' );

						$gallery.find('.ee-gallery__item').resize( function() {
							$gallery.isotope( 'layout' );
						});

					});

				} );
				
        	} );
		</script><?php
	}
}
