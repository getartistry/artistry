<?php
namespace ElementorExtras\Modules\Devices\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

use DomDocument;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Devices
 *
 * @since 0.1.0
 */
class Devices extends Extras_Widget {

	public function get_name() {
		return 'devices-extended';
	}

	public function get_title() {
		return __( 'Devices', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-mobile';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.1.0
	 **/
	public function get_script_depends() {
		return [
			'video-player',
			'iphone-inline-video',
			'jquery-appear'
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Device', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'device_type',
				[
					'label' 		=> __( 'Type', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'phone',
					'options' 		=> [
						'phone' 		=> [
							'title' => __( 'Phone', 'elementor-extras' ),
							'icon' 	=> 'fa fa-mobile-phone',
						],
						'tablet' 	=> [
							'title' => __( 'Tablet', 'elementor-extras' ),
							'icon' 	=> 'fa fa-tablet',
						],
						'laptop' 	=> [
							'title' => __( 'Laptop', 'elementor-extras' ),
							'icon' 	=> 'fa fa-laptop',
						],
						'desktop' 	=> [
							'title' => __( 'Desktop', 'elementor-extras' ),
							'icon' 	=> 'fa fa-desktop',
						],
						'window' 	=> [
							'title' => __( 'Window', 'elementor-extras' ),
							'icon' 	=> 'nicon nicon-window',
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'device_media_type',
				[
					'label' 		=> __( 'Media Type', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'image',
					'options' 		=> [
						'image'  	=> __( 'Image', 'elementor-extras' ),
						'video'  	=> __( 'Video', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'device_orientation',
				[
					'label' 		=> __( 'Orientation', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'portrait',
					'options' 		=> [
						'portrait' 	=> [
							'title' => __( 'Portrait', 'elementor-extras' ),
							'icon' 	=> 'nicon nicon-mobile-portrait',
						],
						'landscape' => [
							'title' => __( 'Landscape', 'elementor-extras' ),
							'icon' 	=> 'nicon nicon-mobile-landscape',
						],
					],
					'prefix_class'	=> 'elementor-device-orientation-',
					'condition'		=> [
						'device_type'					=> [ 'phone', 'tablet' ],
						'device_media_type'				=> [ 'image' ],
					]
				]
			);

			$this->add_control(
				'device_orientation_control',
				[
					'label' 		=> __( 'Orientation Control', 'elementor-extras' ),
					'description'	=> __( 'Show orientation swticher ', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'		=> [
						'device_type'					=> [ 'phone', 'tablet' ],
						'device_media_type'				=> [ 'image' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'device_align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'center',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'device_width',
				[
					'label' 		=> __( 'Maximum Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 		=> [
						'size' 		=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1920,
							'step' 	=> 10,
						],
						'%' => [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-device-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
						'{{WRAPPER}} .elementor-device' => 'width: 100%;',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_screenshot',
			[
				'label' 	=> __( 'Screen', 'elementor-extras' ),
				'condition'	=> [
					'device_media_type'				=> [ 'image' ],
				]
			]
		);

			$this->start_controls_tabs( 'tabs_media' );

			$this->start_controls_tab(
				'tab_media_portrait',
				[
					'label' => __( 'Default', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'media_portrait_screenshot',
					[
						'label' => __( 'Choose Screenshot', 'elementor-extras' ),
						'type' 	=> Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'condition'	=> [
							'device_media_type'				=> [ 'image' ],
						]
					]
				);

				$this->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 			=> 'media_portrait_screenshot',
						'label' 		=> __( 'Screenshot Size', 'elementor-extras' ),
						'default' 		=> 'large',
						'condition'		=> [
							'media_portrait_screenshot[url]!'	=> '',
							'device_media_type'					=> [ 'image' ],
						]
					]
				);

				$this->add_control(
					'media_portrait_screenshot_scrollable',
					[
						'label' 		=> __( 'Scrollable', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'no',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'scrollable',
						'prefix_class'	=> 'elementor-device-portrait-',
						'condition'		=> [
							'media_portrait_screenshot[url]!'	=> '',
							'device_media_type'					=> [ 'image' ],
							'device_type!'						=> [ 'window' ],
						]
					]
				);

				$this->add_responsive_control(
					'media_portrait_screenshot_align',
					[
						'label' 		=> __( 'Vertical Align', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default'		=> 'flex-start',
						'options' 		=> [
							'flex-start' 		=> [
								'title' => __( 'Top', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-top',
							],
							'center' 	=> [
								'title' => __( 'Middle', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-center',
							],
							'flex-end' 	=> [
								'title' => __( 'Bottom', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-bottom',
							],
							'initial' 	=> [
								'title' => __( 'Custom', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-custom',
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen-image' => 'align-items: {{VALUE}};',
							'{{WRAPPER}} .elementor-device-media-screen-image .elementor-device-media-screen-inner' => 'top: auto;',
						],
						'condition'		=> [
							'media_portrait_screenshot_scrollable!' => 'scrollable',
							'media_portrait_screenshot[url]!'		=> '',
							'device_media_type'						=> [ 'image' ],
							'device_type!'							=> [ 'window' ],
						]
					]
				);

				$this->add_control(
					'media_portrait_screenshot_position',
					[
						'label' 		=> __( 'Offset Top (%)', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 		=> [
							'size' 		=> 0,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step' 	=> 1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen-image .elementor-device-media-screen-inner' => 'transform: translateY(-{{SIZE}}%);',
						],
						'condition'		=> [
							'media_portrait_screenshot_scrollable!' => 'scrollable',
							'media_portrait_screenshot_align'		=> 'initial',
							'media_portrait_screenshot[url]!'		=> '',
							'device_media_type'						=> [ 'image' ],
							'device_type!'							=> [ 'window' ],
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_media_landscape',
				[
					'label' => __( 'Landscape', 'elementor-extras' ),
					'condition'	=> [
						'device_orientation_control' 	=> 'yes',
						'device_type'					=> [ 'phone', 'tablet' ],
						'device_media_type'				=> [ 'image' ],
					]
				]
			);

				$this->add_control(
					'media_landscape_screenshot',
					[
						'label' 		=> __( 'Choose Screenshot', 'elementor-extras' ),
						'type' 			=> Controls_Manager::MEDIA,
						'condition'		=> [
							'device_orientation_control' 	=> 'yes',
							'device_type'					=> [ 'phone', 'tablet' ],
							'device_media_type'				=> [ 'image' ],
						],
					]
				);

				$this->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 		=> 'media_landscape_screenshot', // Actually its `image_size`
						'label' 	=> __( 'Screenshot Size', 'elementor-extras' ),
						'default' 	=> 'large',
						'condition'	=> [
							'device_orientation_control' 		=> 'yes',
							'device_type'						=> [ 'phone', 'tablet' ],
							'device_media_type'					=> [ 'image' ],
							'media_landscape_screenshot[url]!'	=> '',
						]
					]
				);

				$this->add_control(
					'media_landscape_screenshot_scrollable',
					[
						'label' 		=> __( 'Scrollable', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'no',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'scrollable',
						'prefix_class'	=> 'elementor-device-landscape-',
						'condition'	=> [
							'device_orientation_control' 		=> 'yes',
							'device_type'						=> [ 'phone', 'tablet' ],
							'device_media_type'					=> [ 'image' ],
							'media_landscape_screenshot[url]!'	=> ''
						]
					]
				);

				$this->add_responsive_control(
					'media_landscape_screenshot_align',
					[
						'label' 		=> __( 'Vertical Align', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default'		=> 'flex-start',
						'options' 		=> [
							'flex-start' 		=> [
								'title' => __( 'Top', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-top',
							],
							'center' 	=> [
								'title' => __( 'Middle', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-center',
							],
							'flex-end' 	=> [
								'title' => __( 'Bottom', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-bottom',
							],
							'initial' 	=> [
								'title' => __( 'Custom', 'elementor-extras' ),
								'icon' 	=> 'nicon nicon-mobile-screen-custom',
							],
							'parallax' 	=> [
								'title' => __( 'Parallax', 'elementor-extras' ),
								'icon' 	=> 'eicon-parallax',
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen.elementor-device-media-screen-landscape' => 'align-items: {{VALUE}};',
							'{{WRAPPER}} .elementor-device-media-screen-landscape .elementor-device-media-screen-inner' => 'top: auto;',
						],
						'condition'	=> [
							'media_landscape_screenshot_scrollable!' 	=> 'scrollable',
							'device_orientation_control' 				=> 'yes',
							'device_type'								=> [ 'phone', 'tablet' ],
							'device_media_type'							=> [ 'image' ],
							'media_landscape_screenshot[url]!'			=> '',
						]
					]
				);

				$this->add_control(
					'media_landscape_screenshot_position',
					[
						'label' 		=> __( 'Offset Top (%)', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 		=> [
							'size' 		=> 0,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step' 	=> 1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen-landscape .elementor-device-media-screen-inner' => 'transform: translateY(-{{SIZE}}%);',
						],
						'condition'		=> [
							'media_landscape_screenshot_scrollable!' 	=> 'scrollable',
							'media_landscape_screenshot_align'			=> 'initial',
							'device_orientation_control' 				=> 'yes',
							'device_type'								=> [ 'phone', 'tablet' ],
							'device_media_type'							=> [ 'image' ],
							'media_landscape_screenshot[url]!'			=> ''
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video',
			[
				'label' 	=> __( 'Video', 'elementor-extras' ),
				'condition'	=> [
					'device_media_type'	=> [ 'video' ],
				]
			]
		);

			$this->start_controls_tabs( 'tabs_video' );

			$this->start_controls_tab(
				'tab_video',
				[
					'label' => __( 'Video', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'video_url',
					[
						'label' 		=> __( 'MP4 Source', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'placeholder' 	=> esc_url( home_url( '/' ) ) . 'path/to/video',
						'description' 	=> __( 'Insert URL to local .mp4 video file', 'elementor-extras' ),
						'label_block' 	=> true,
						'default' 		=> '',
						'condition'		=> [
							'device_media_type'				=> [ 'video' ],
						],
					]
				);

				$this->add_control(
					'video_url_m4v',
					[
						'label' 		=> __( 'M4V Source', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'placeholder' 	=> esc_url( home_url( '/' ) ) . 'path/to/video',
						'description' 	=> __( 'Insert URL to local .m4v video file', 'elementor-extras' ),
						'label_block' 	=> true,
						'default' 		=> '',
						'condition'		=> [
							'device_media_type'				=> [ 'video' ],
						],
					]
				);

				$this->add_control(
					'video_url_ogg',
					[
						'label' 		=> __( 'OGG Source', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'placeholder' 	=> esc_url( home_url( '/' ) ) . 'path/to/video',
						'description' 	=> __( 'Insert URL to local .ogg video file', 'elementor-extras' ),
						'label_block' 	=> true,
						'default' 		=> '',
						'condition'		=> [
							'device_media_type'				=> [ 'video' ],
						],
					]
				);

				$this->add_control(
					'video_url_webm',
					[
						'label' 		=> __( 'WebM Source', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'placeholder' 	=> esc_url( home_url( '/' ) ) . 'path/to/video',
						'description' 	=> __( 'Insert URL to local .webm video file', 'elementor-extras' ),
						'label_block' 	=> true,
						'default' 		=> '',
						'condition'		=> [
							'device_media_type'				=> [ 'video' ],
						],
					]
				);

				$this->add_control(
					'video_show_buttons',
					[
						'label' 		=> __( 'Show Buttons', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'show',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_show_bar',
					[
						'label' 		=> __( 'Show Bar', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_show_time',
					[
						'label' 		=> __( 'Show Time', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'show',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_show_bar!'		=> '',
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_show_duration',
					[
						'label' 		=> __( 'Show Duration', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_show_bar!'		=> '',
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_show_volume',
					[
						'label' 		=> __( 'Show Volume', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_show_bar!'		=> '',
							'video_url!'			=> ''
						]
					]
				);

				$this->add_responsive_control(
					'video_volume',
					[
						'label' 	=> __( 'Initial Volume', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.8,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 1,
								'min' 	=> 0,
								'step' 	=> 0.01,
							],
						],
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> '',
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'video_show_fs',
					[
						'label' 		=> __( 'Show Fullscreen', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'show',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_show_bar!'		=> '',
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_show_rewind',
					[
						'label' 		=> __( 'Show Rewind', 'elementor-extras' ),
						'description' 	=> __( 'Shown only when video is paused.', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'show',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> '',
						]
					]
				);

				$this->add_control(
					'video_autoplay',
					[
						'label' 		=> __( 'Auto Play', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'autoplay',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> ''
						]
					]
				);

				$this->add_control(
					'video_play_viewport',
					[
						'label' 		=> __( 'Play in Viewport', 'elementor-extras' ),
						'description' 	=> __( 'Auto Play video when the device is in viewport', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> '',
							'video_autoplay'		=> 'autoplay',
						],
						'frontend_available'		=> true,
					]
				);

				$this->add_control(
					'video_stop_viewport',
					[
						'label' 		=> __( 'Stop on leave', 'elementor-extras' ),
						'description' 	=> __( 'Stop video when the player has left the viewport', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'condition'	=> [
							'video_url!'			=> '',
							'video_autoplay'		=> 'autoplay',
							'video_play_viewport'	=> 'yes',
						],
						'frontend_available'		=> true,
					]
				);

				$this->add_control(
					'video_restart_on_pause',
					[
						'label' 		=> __( 'Restart on pause', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'condition'	=> [
							'device_media_type'	=> [ 'video' ],
							'video_url!'		=> ''
						],
						'frontend_available'	=> true,
					]
				);

				$this->add_control(
					'video_loop',
					[
						'label' 		=> __( 'Loop', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'loop',
						'condition'	=> [
							'device_media_type'		=> [ 'video' ],
							'video_url!'			=> ''
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_cover',
				[
					'label' => __( 'Cover', 'elementor-extras' ),
					'condition'	=> [
						'device_media_type'		=> [ 'video' ],
					]
				]
			);

				$this->add_control(
					'video_cover',
					[
						'label' 		=> __( 'Choose Cover', 'elementor-extras' ),
						'type' 			=> Controls_Manager::MEDIA,
						'condition'		=> [
							'device_media_type'				=> [ 'video' ],
						]
					]
				);

				$this->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 		=> 'video_cover',
						'label' 	=> __( 'Cover Size', 'elementor-extras' ),
						'default' 	=> 'large',
						'condition'	=> [
							'device_media_type'	=> [ 'video' ],
							'video_cover[url]!'		=> '',
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_device_style',
			[
				'label' => __( 'Device', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'device_override_style',
				[
					'label' 		=> __( 'Override Style', 'elementor-extras' ),
					'description'	=> __( 'Override default device style', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'device_skin',
				[
					'label' 		=> __( 'Skin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'jetblack',
					'options' 		=> [
						'jetblack'  => __( 'Jet black', 'elementor-extras' ),
						'black'  	=> __( 'Black', 'elementor-extras' ),
						'silver'  	=> __( 'Silver', 'elementor-extras' ),
						'gold'  	=> __( 'Gold', 'elementor-extras' ),
						'rosegold'  => __( 'Rose Gold', 'elementor-extras' ),
						],
					'prefix_class'	=> 'elementor-device-skin-',
					'condition'		=> [
						'device_override_style!'	=> 'yes',
						'device_type!'				=> [ 'laptop', 'desktop' ]
					],
				]
			);

			$this->add_control(
				'device_frame_background',
				[
					'label' 	=> __( 'Device Background', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-device-wrapper svg .back-shape' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .elementor-device-wrapper svg .side-shape' => 'fill: {{VALUE}}',
					],
					'condition'		=> [
						'device_override_style'	=> 'yes'
					],
				]
			);

			$this->add_control(
				'device_overlay_tone',
				[
					'label'       	=> __( 'Tone', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'light',
					'options' 		=> [
						'light'  	=> __( 'Light', 'elementor-extras' ),
						'dark'  	=> __( 'Dark', 'elementor-extras' ),
						],
					'prefix_class'	=> 'elementor-device-controls-tone-',
					'condition'		=> [
						'device_override_style'	=> 'yes',
					],
				]
			);

			$this->add_control(
				'device_overlay_opacity',
				[
					'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 0.2,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 0.4,
							'min' 	=> 0.1,
							'step' 	=> 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-device-wrapper svg .overlay-shape' => 'fill-opacity: {{SIZE}};',
					],
					'condition'		=> [
						'device_override_style'	=> 'yes'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_screen_style',
			[
				'label' => __( 'Screen', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'device_media_type'		=> [ 'image' ],
					'device_type'			=> [ 'window' ],
				],
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'device_screen_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .elementor-device-wrapper .elementor-device-media-screen figure',
					'condition'		=> [
						'device_type'			=> [ 'window' ],
						'device_media_type'		=> [ 'image' ],
					],
				]
			);

			$this->add_control(
				'device_screen_radius',
				[
					'label' 			=> __( 'Border Radius', 'elementor-extras' ),
					'type' 					=> Controls_Manager::DIMENSIONS,
					'size_units' 			=> [ 'px', '%' ],
					'allowed_dimensions'	=> [ 'bottom', 'left' ],
					'selectors' 			=> [
						'{{WRAPPER}} .elementor-device-wrapper .elementor-device-media-screen figure' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'				=> [
						'device_type'			=> [ 'window' ],
						'device_media_type'		=> [ 'image' ],
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => __( 'Video', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'device_media_type'		=> [ 'video' ],
					'video_url!'			=> '',
				],
			]
		);

			$this->add_control(
				'video_cover_style',
				[
					'label' 		=> __( 'Cover', 'elementor-extras' ),
					'description' 	=> __( 'Forces the video cover the whole screen', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'loop',
					'selectors'		=> [
						'{{WRAPPER}} .elementor-extras-html5-video__source' => 'object-fit: cover; height: 100%;',
					],
					'condition'	=> [
						'device_media_type'		=> [ 'video' ],
						'video_url!'			=> ''
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_overlay',
			[
				'label' => __( 'Video Overlay', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'video_overlay_background',
				[
					'label' 	=> __( 'Background', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '#000000',
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__cover::after' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'video_overlay_opacity',
				[
					'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 0.8,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 1,
							'min' 	=> 0,
							'step' 	=> 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__cover::after' => 'opacity: {{SIZE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Video Interface', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'conditions' => [
					'terms' 	=> [
						[
							'name' 		=> 'video_show_buttons',
							'operator' 	=> '==',
							'value' 	=> 'show',
						],
						[
							'name' 		=> 'video_show_bar',
							'operator' 	=> '==',
							'value' 	=> 'show',
							'relation' 	=> 'or',
						],
					],
				],
			]
		);

			$this->add_control(
				'video_controls_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'default' 		=> [
						'top' 		=> 100,
						'right' 	=> 100,
						'bottom' 	=> 100,
						'left' 		=> 100,
						'unit' 		=> 'px'
					],
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
						{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar .control--progress' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar .control--progress__inner' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;'
					],
				]
			);

			$this->start_controls_tabs( 'tabs_controls_style' );

			$this->start_controls_tab(
				'video_controls',
				[
					'label' => __( 'Default', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'video_controls_background',
					[
						'label' 	=> __( 'Controls Background', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '#FFFFFF',
						'selectors' => [
							'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'video_controls_foreground',
					[
						'label' 	=> __( 'Controls Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '#000000',
						'selectors' => [
							'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'color: {{VALUE}}',

							'{{WRAPPER}} .elementor-extras-html5-video__controls .control--progress__inner' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'video_controls_opacity',
					[
						'label' 	=> __( 'Controls Opacity (%)', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 0.9,
						],
						'range' 	=> [
							'px' 	=> [
								'max' 	=> 1,
								'min' 	=> 0,
								'step' 	=> 0.01,
							],
						],
						'selectors' => [
							'
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'opacity: {{SIZE}}',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'video_controls_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=>
							'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'video_controls_shadow',
						'selector' 	=>
							'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'video_controls_hover',
				[
					'label' => __( 'Hover', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'video_controls_background_hover',
					[
						'label' 	=> __( 'Controls Background', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control:hover,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'video_controls_foreground_hover',
					[
						'label' 	=> __( 'Controls Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control:hover,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover' => 'color: {{VALUE}}',

							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover .control--progress__inner' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'video_controls_opacity_hover',
					[
						'label' 	=> __( 'Controls Opacity (%)', 'elementor-extras' ),
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
							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control:hover,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover' => 'opacity: {{SIZE}}',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'video_controls_border_hover',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=>
							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control:hover,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'video_controls_shadow_hover',
						'selector' 	=>
							'(desktop+){{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control:hover,
							{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar:hover',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			[
				'label' => __( 'Video Buttons', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'video_show_buttons!' => '',
				],
			]
		);

			$this->add_responsive_control(
				'video_buttons_size',
				[
					'label' => __( 'Size (%)', 'elementor-extras' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 60,
					],
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .control' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;',
					],
					'condition'		=> [
						'video_show_buttons!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'video_buttons_spacing',
				[
					'label' => __( 'Controls Spacing', 'elementor-extras' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__overlay .controls__rewind' => 'margin-right: {{SIZE}}px;',
					],
					'condition'		=> [
						'video_show_buttons!' => '',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_bar_style',
			[
				'label' => __( 'Video Bar', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'video_show_bar!' => '',
				],
			]
		);

			$this->add_responsive_control(
				'video_bar_padding',
				[
					'label' 	=> __( 'Padding', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 72,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'padding: {{SIZE}}px',
					],
					'condition'		=> [
						'video_show_bar!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'video_bar_margin',
				[
					'label' 	=> __( 'Distance', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 72,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar-wrapper' => 'padding: 0 {{SIZE}}px {{SIZE}}px {{SIZE}}px',
					],
					'condition'		=> [
						'video_show_bar!' => '',
					],
				]
			);

			$this->add_control(
				'video_bar_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'		=> [
						'video_show_bar!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'video_bar_zoom',
				[
					'label' 	=> __( 'Controls Zoom', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 36,
							'min' 	=> 12,
							'step' 	=> 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar' => 'font-size: {{SIZE}}px',
						'{{WRAPPER}} .elementor-extras-html5-video__controls .controls__bar .control--progress' => 'height: {{SIZE}}px',
					],
					'condition'		=> [
						'video_show_bar!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'video_bar_spacing',
				[
					'label' 	=> __( 'Controls Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 24,
							'min' 	=> 3,
							'step' 	=> 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-extras-html5-video__controls .control--indicator,
						{{WRAPPER}} .elementor-extras-html5-video__controls .control--icon' => 'padding: 0 {{SIZE}}px',
						'{{WRAPPER}} .elementor-extras-html5-video__controls .control--progress' => 'margin: 0 {{SIZE}}px',
					],
					'condition'		=> [
						'video_show_bar!' => '',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		// Default to phone
		$device_type = 'phone';

		// Only assign device type if selected
		if ( ! empty( $settings['device_type'] ) ) {
			$device_type = $settings['device_type'];
		}

		$this->add_render_attribute('device-wrapper', 'class', [
			'elementor-device-wrapper',
			'elementor-device-type-' . $device_type
		] );

		$this->add_render_attribute('device', 'class', 'elementor-device');

		if ( 'yes' === $settings['device_orientation_control'] && 'image' === $settings['device_media_type'] ) {
			$this->add_render_attribute('device', 'class', 'has--orientation-control');
		}

		$this->add_render_attribute('device-orientation', 'class', 'elementor-device-orientation nicon nicon-mobile-landscape');

		$this->add_render_attribute('device-shape', 'class', 'elementor-device-shape');

		$this->add_render_attribute('device-media', 'class', 'elementor-device-media');
		$this->add_render_attribute('device-media-inner', 'class', 'elementor-device-media-inner');

		$this->add_render_attribute('device-media-screen', 'class', [
			'elementor-device-media-screen',
			'elementor-device-media-screen-' . $settings['device_media_type']
		] );

		$this->add_render_attribute('device-media-screen-landscape', 'class', [
			'elementor-device-media-screen',
			'elementor-device-media-screen-landscape'
		]);

		$this->add_render_attribute('device-media-screen-controls', 'class', [
			'elementor-device-media-screen',
			'elementor-device-media-screen-controls'
		] );

		$this->add_render_attribute('device-media-screen-inner', 'class', 'elementor-device-media-screen-inner');

		$after_shape 	= '';
		$before_shape 	= '';
		$after_shape 	= '';

		$before_shape .= '<div ' . $this->get_render_attribute_string('device-wrapper') . '>';
		$before_shape .= '<div ' . $this->get_render_attribute_string('device') . '>';

		if ( 'yes' === $settings['device_orientation_control'] && 'image' === $settings['device_media_type'] ) {
			$before_shape .= '<div ' . $this->get_render_attribute_string('device-orientation') . '></div>';
		}

		$before_shape .= '<div ' . $this->get_render_attribute_string('device-shape') . '>';

		echo $before_shape;

		include ELEMENTOR_EXTRAS_PATH . 'assets/shapes/' . $device_type . '.svg';

		$after_shape .= '</div><!-- .elementor-device-shape -->';

		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-inner') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-inner') . '>';

		if ( $settings['device_media_type'] === 'image' ) {
			if ( ! empty( $settings['media_portrait_screenshot']['url'] ) )
				$after_shape .= '<figure>' . Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_portrait_screenshot' ) . '</figure>';
		} else {
			if ( $settings['video_url'] || $settings['video_url_ogg'] || $settings['video_url_webm'] ) {

				$video_settings = [];

				if( $settings['video_autoplay'] === 'autoplay' && 'yes' !== $settings['video_play_viewport'] ) {
					$video_settings[] = 'autoplay';
				}

				if( $settings['video_loop'] === 'loop' ) {
					$video_settings[] = 'loop';
				}

				$video_settings = implode( ' ', $video_settings );

				$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-extras-html5-video' );
				$this->add_render_attribute( 'video-cover', 'class', 'elementor-extras-html5-video__cover' );
				$this->add_render_attribute( 'video-cover', 'class', 'video__overlay' );
				$this->add_render_attribute( 'video-image', 'class', 'elementor-extras-html5-video__image' );
				$this->add_render_attribute( 'video-controls', 'class', 'elementor-extras-html5-video__controls' );
				$this->add_render_attribute( 'video-controls', 'class', 'controls' );

				$this->add_render_attribute( 'video', 'class', 'elementor-extras-html5-video__source' );
				// $this->add_render_attribute( 'video', 'preload', 'yes' );
				$this->add_render_attribute( 'video', 'playsinline', 'true' );
				$this->add_render_attribute( 'video', 'width', '100%' );
				$this->add_render_attribute( 'video', 'height', '100%' );

				// Video wrapper

				$after_shape .= '<div ' . $this->get_render_attribute_string( 'video-wrapper' ) . '>';

					// Video

					$after_shape .= '<video ' . $this->get_render_attribute_string( 'video' ) . ' ' . $video_settings . '>';

					if ( $settings['video_url'] ) {
						$this->add_render_attribute( 'source', 'src', $settings['video_url'] );
						$this->add_render_attribute( 'source', 'type', 'video/mp4' );

						$after_shape .= '<source ' . $this->get_render_attribute_string( 'source' ) . '>';
					}

					if ( $settings['video_url_m4v'] ) {
						$this->add_render_attribute( 'source_m4v', 'src', $settings['video_url_m4v'] );

						$after_shape .= '<source ' . $this->get_render_attribute_string( 'source_m4v' ) . '>';
					}

					if ( $settings['video_url_ogg'] ) {
						$this->add_render_attribute( 'source_ogg', 'src', $settings['video_url_ogg'] );
						$this->add_render_attribute( 'source_ogg', 'type', 'video/ogg' );

						$after_shape .= '<source ' . $this->get_render_attribute_string( 'source_ogg' ) . '>';
					}

					if ( $settings['video_url_webm'] ) {
						$this->add_render_attribute( 'source_webm', 'src', $settings['video_url_webm'] );
						$this->add_render_attribute( 'source_webm', 'type', 'video/webm' );

						$after_shape .= '<source ' . $this->get_render_attribute_string( 'source_webm' ) . '>';
					}

					$after_shape .= '</video>';

					// Video Cover

					$after_shape .= '<div ' . $this->get_render_attribute_string( 'video-cover' ) . '>';

					$after_shape .= '</div><!-- .elementor-extras-html5-video__cover -->';

					$after_shape .= '<div ' . $this->get_render_attribute_string('video-controls') . '>';

						if ( 'show' === $settings['video_show_buttons'] ) {
							$after_shape .= '<ul class="controls__overlay video__overlay">';

							if ( 'show' === $settings['video_show_rewind'] ) {
								$after_shape .= '<li class="control controls__rewind nicon nicon-rewind"></li>';
							}

							$after_shape .= '<li class="control controls__play nicon nicon-play"></li>';
							$after_shape .= '</ul>';
						}

						if ( 'show' === $settings['video_show_bar'] ) {

							$after_shape .= '<div class="controls__bar-wrapper">';
								$after_shape .= '<div class="controls__bar">';

									if ( 'show' === $settings['video_show_rewind'] ) {
										$after_shape .= '<div class="control controls__rewind control--icon nicon nicon-rewind"></div>';
									}

									$after_shape .= '<div class="control controls__play control--icon nicon nicon-play"></div>';

									if ( 'show' === $settings['video_show_time'] ) {
										$after_shape .= '<div class="control controls__time control--indicator">00:00</div>';
									}

									$after_shape .= '<div class="control controls__progress control--progress">';

										$after_shape .= '<div class="control controls__progress-time control--progress__inner"></div>';
										$after_shape .= '<div class="control controls__progress-track control--progress__inner control--progress__track"></div>';

									$after_shape .= '</div>';

									if ( 'show' === $settings['video_show_duration'] ) {
										$after_shape .= '<div class="control controls__duration control--indicator">00:00</div>';
									}

									if ( 'show' === $settings['video_show_volume'] ) {
										$after_shape .= '<div class="control controls__volume">';

											$after_shape .= '<div class="control controls__volume-icon control--icon nicon nicon-volume"></div>';
											$after_shape .= '<div class="control controls__volume-bar control--progress">';

												$after_shape .= '<div class="control controls__volume-bar__amount control--progress__inner"></div>';
												$after_shape .= '<div class="control controls__volume-bar__track control--progress__inner control--progress__track"></div>';

											$after_shape .= '</div>';

										$after_shape .= '</div>';
									}

									if ( 'show' === $settings['video_show_fs'] ) {
										$after_shape .= '<div class="control controls__fs control--icon nicon nicon-expand"></div>';
									}

								$after_shape .= '</div><!-- .controls__bar -->';
							$after_shape .= '</div><!-- .controls__bar-wrapper -->';

						}

					$after_shape .= '</div><!-- .elementor-extras-html5-video__controls -->';

				$after_shape .= '</div>';
			}
		}

		$after_shape .= '</div><!-- .elementor-device-media-screen-inner -->';
		$after_shape .= '</div><!-- .elementor-device-media-screen -->';

		if ( $settings['device_media_type'] === 'image' ) {

			if ( ! empty( $settings['media_landscape_screenshot']['url'] ) ) {
				$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-landscape') . '>';
				$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-inner') . '>';

				$after_shape .= '<figure>' . Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_landscape_screenshot' ) . '</figure>';

				$after_shape .= '</div><!-- .elementor-device-media-screen-inner -->';
				$after_shape .= '</div><!-- .elementor-device-media-screen -->';
			}

		}

		$after_shape .= '</div><!-- .elementor-device-media-inner -->';
		$after_shape .= '</div><!-- .elementor-device-media -->';
		$after_shape .= '</div><!-- .elementor-device -->';
		$after_shape .= '</div><!-- .elementor-device-wrapper -->';

		echo $after_shape;
	}

	protected function _content_template() {
		?>
		<#

		var device_type = 'phone';

		if ( settings.device_type ) {
			device_type = settings.device_type;
		}

		var device_classes = 'elementor-device';

		if ( settings.device_orientation_control === 'yes' && settings.device_media_type === 'image' ) {
			device_classes += ' has--orientation-control';
		}

		var device_wrapper_classes = 'elementor-device-wrapper elementor-device-type-' + device_type;

		var device_orientation_classes = 'elementor-device-orientation nicon nicon-mobile-landscape';
		var device_shape_classes = 'elementor-device-shape';
		var device_media_classes = 'elementor-device-media';
		var device_media_inner_classes = 'elementor-device-media-inner';

		var device_media_screen_classes = 'elementor-device-media-screen elementor-device-media-screen-' + settings.device_media_type;
		var device_media_screen_inner_classes = 'elementor-device-media-screen-inner';

		var output = '';

		output += '<div class="' + device_wrapper_classes + '">';
		output += '<div class="' + device_classes + '">';

		if ( settings.device_orientation_control === 'yes' && settings.device_media_type === 'image' ) {
			output += '<div class="' + device_orientation_classes + '"></div>';
		}

		output += '<div class="' + device_shape_classes + '"></div>';

		output += '<div class="' + device_media_classes + '">';
		output += '<div class="' + device_media_inner_classes + '">';
		output += '<div class="' + device_media_screen_classes + '">';
		output += '<div class="' + device_media_screen_inner_classes + '">';

		if ( settings.device_media_type === 'image' ) {
			if ( '' !== settings.media_portrait_screenshot.url ) {

				var portrait_screenshot = {
					id 			: settings.media_portrait_screenshot.id,
					url 		: settings.media_portrait_screenshot.url,
					size 		: settings.media_portrait_screenshot_size,
					dimension 	: settings.media_portrait_screenshot_custom_dimension,
					model: editModel
				};

				var portrait_screenshot_url = elementor.imagesManager.getImageUrl( portrait_screenshot );

				if ( ! portrait_screenshot_url ) {
					return;
				}

				output += '<figure><img src="' + portrait_screenshot_url + '" /></figure>';
			}

		} else {

			if ( '' !== settings.video_url || '' !== settings.video_url_ogg || '' !== settings.video_url_webm ) {

				var video_settings 	= '';

				if ( settings.video_autoplay === 'autoplay' && 'yes' !== settings.video_play_viewport ) {
					video_settings += ' autoplay';
				}

				if ( settings.video_loop === 'loop' ) {
					video_settings += ' loop';
				}

				if ( '' !== settings.video_cover.url ) {

					var video_cover = {
						id: settings.video_cover.id,
						url: settings.video_cover.url,
						size: settings.video_cover_size,
						dimension: settings.video_cover_custom_dimension,
						model: editModel
					};
					
					video_settings += ' poster="' + settings.video_cover.url + '"';
				}

				output += '<div class="elementor-extras-html5-video">';

					output += '<video preload="yes" class="elementor-extras-html5-video__source" src="' + settings.video_url + '" ' + video_settings + '>';

					if ( '' !== settings.video_url ) {
						output += '<source src="' + settings.video_url + '" type="video/mp4">';
					}

					if ( '' !== settings.video_url_m4v ) {
						output += '<source src="' + settings.video_url_m4v + '">';
					}

					if ( '' !== settings.video_url_ogg ) {
						output += '<source src="' + settings.video_url_ogg + '" type="video/ogg">';
					}

					if ( '' !== settings.video_url_webm ) {
						output += '<source src="' + settings.video_url_webm + '" type="video/webm">';
					}
					
					output += '</video>';

					output += '<div class="elementor-extras-html5-video__cover video__overlay">';

					output += '</div><!-- .elementor-extras-html5-video__cover -->';

					output += '<div class="elementor-extras-html5-video__controls video__overlay controls">';

						if ( 'show' === settings.video_show_buttons ) {
							output += '<ul class="controls__overlay">';

							if ( 'show' === settings.video_show_rewind ) {
								output += '<li class="control controls__rewind nicon nicon-rewind"></li>'; }
								output += '<li class="control controls__play nicon nicon-play"></li>';

							output += '</ul><!-- .controls__overlay -->';
						}

						if ( 'show' === settings.video_show_bar ) {

							output += '<div class="controls__bar-wrapper">';
								output += '<div class="controls__bar">';

									if ( 'show' === settings.video_show_rewind ) {
										output += '<div class="control controls__rewind control--icon nicon nicon-rewind"></div>';
									}

									output += '<div class="control controls__play control--icon nicon nicon-play"></div>';

									if ( 'show' === settings.video_show_time ) {
										output += '<div class="control controls__time control--indicator">00:00</div>';
									}

									output += '<div class="control controls__progress control--progress">';

										output += '<div class="control controls__progress-time control--progress__inner"></div>';
										output += '<div class="control controls__progress-track control--progress__inner control--progress__track"></div>';

									output += '</div>';

									if ( 'show' === settings.video_show_duration ) {
										output += '<div class="control controls__duration control--indicator">00:00</div>';
									}

									if ( 'show' === settings.video_show_volume ) {
										output += '<div class="control controls__volume">';

											output += '<div class="control controls__volume-icon control--icon nicon nicon-volume"></div>';
											output += '<div class="control controls__volume-bar control--progress">';

												output += '<div class="control controls__volume-bar__amount control--progress__inner"></div>';
												output += '<div class="control controls__volume-bar__track control--progress__inner control--progress__track"></div>';

											output += '</div>';

										output += '</div>';
									}

									if ( 'show' === settings.video_show_fs ) {
										output += '<div class="control controls__fs control--icon nicon nicon-expand"></div>';
									}

								output += '</div><!-- .controls__bar -->';
							output += '</div><!-- .controls__bar-wrapper -->';

						}

					output += '</div><!-- .elementor-extras-html5-video__controls -->';

				output += '</div><!-- .elementor-extras-html5-video-wrapper -->';
			}
		}

		output += '</div><!-- .elementor-device-media-screen-inner -->';
		output += '</div><!-- .elementor-device-media-screen -->';

		if ( settings.device_media_type === 'image' ) {

			if ( '' !== settings.media_landscape_screenshot.url ) {

				var landscape_screenshot = {
					id 			: settings.media_landscape_screenshot.id,
					url 		: settings.media_landscape_screenshot.url,
					size 		: settings.media_landscape_screenshot_size,
					dimension 	: settings.media_landscape_screenshot_custom_dimension,
					model: editModel
				};

				var landscape_screenshot_url = elementor.imagesManager.getImageUrl( landscape_screenshot );

				output += '<div class="' + device_media_screen_classes + ' elementor-device-media-screen-landscape">';
				output += '<div class="' + device_media_screen_inner_classes + '">';

				output += '<figure><img src="' + landscape_screenshot_url + '" /></figure>';

				output += '</div><!-- .elementor-device-media-screen-inner -->';
				output += '</div><!-- .elementor-device-media-screen-landscape -->';
			}

		}

		output += '</div><!-- .elementor-device-media-inner -->';
		output += '</div><!-- .elementor-device-media -->';
		output += '</div><!-- .elementor-device -->';
		output += '</div><!-- .elementor-device-wrapper -->';

		print( output );

		#><?php
	}
}
