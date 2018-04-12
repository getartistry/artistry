<?php
namespace ElementorExtras\Modules\Video\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor HTML5_Video
 *
 * @since 0.1.0
 */
class HTML5_Video extends Extras_Widget {

	public function get_name() {
		return 'html5-video';
	}

	public function get_title() {
		return __( 'HTML5 Video', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-video';
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
			'jquery-appear',
			'iphone-inline-video',
		];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' 	=> __( 'Content', 'elementor-extras' ),
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
				]
			);

			$this->add_control(
				'video_cover',
				[
					'label' 		=> __( 'Choose Cover', 'elementor-extras' ),
					'type' 			=> Controls_Manager::MEDIA,
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'video_cover',
					'label' 	=> __( 'Cover Size', 'elementor-extras' ),
					'default' 	=> 'large',
					'condition'	=> [
						'video_cover[url]!'		=> '',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
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
						'video_url!'			=> ''
					]
				]
			);

			$this->add_control(
				'video_show_bar',
				[
					'label' 		=> __( 'Show Bar', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'show',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'show',
					'condition'	=> [
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
					'default' 		=> 'show',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'show',
					'condition'	=> [
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
					'default' 		=> 'show',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'show',
					'condition'	=> [
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
					'default' 		=> 'show',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'show',
					'condition'	=> [
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
						'video_url!'			=> ''
					]
				]
			);

			$this->add_control(
				'video_play_viewport',
				[
					'label' 		=> __( 'Play in Viewport', 'elementor-extras' ),
					'description' 	=> __( 'Autoplay video when the player is in viewport', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'	=> [
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
						'video_url!' => ''
					]
				]
			);

			$this->add_control(
				'video_end_at_last_frame',
				[
					'label' 		=> __( 'End at last frame', 'elementor-extras' ),
					'description' 	=> __( 'End the video at the last frame instead of showing the first one.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'	=> [
						'video_url!'	=> '',
						'video_loop'	=> '',
					],
					'frontend_available'		=> true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => __( 'Video', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'video_width',
				[
					'label' 		=> __( 'Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'%' 	=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 600,
						],
					],
					'size_units' => [ '%', 'px' ],
					'selectors'		=> [
						'{{WRAPPER}} .elementor-extras-html5-video' => 'max-width: {{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->add_responsive_control(
				'video_align',
				[
					'label' => __( 'Alignment', 'elementor-extras' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' => 'eicon-h-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'video_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .elementor-extras-html5-video',
				]
			);

			$this->add_control(
				'video_border_radius',
				[
					'label' 			=> __( 'Border Radius', 'elementor-extras' ),
					'type' 					=> Controls_Manager::DIMENSIONS,
					'size_units' 			=> [ 'px', '%' ],
					'selectors' 			=> [
						'{{WRAPPER}} .elementor-extras-html5-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'video_box_shadow',
					'selector' 	=> '{{WRAPPER}} .elementor-extras-html5-video',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_overlay',
			[
				'label' => __( 'Overlay', 'elementor-extras' ),
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
				'label' => __( 'Interface', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' 	=> 'or',
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
				'label' => __( 'Buttons', 'elementor-extras' ),
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
				'label' => __( 'Bar', 'elementor-extras' ),
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

		$output = '';

		if ( empty ( $settings['video_url'] ) && empty( $settings['video_url_ogg'] ) && empty( $settings['video_url_webm'] ) )
			return;

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
		$this->add_render_attribute( 'video-controls', 'class', 'elementor-extras-html5-video__controls' );
		$this->add_render_attribute( 'video-controls', 'class', 'controls' );

		$this->add_render_attribute( 'video', 'class', 'elementor-extras-html5-video__source' );
		// $this->add_render_attribute( 'video', 'preload', 'yes' );
		$this->add_render_attribute( 'video', 'playsinline', 'true' );
		$this->add_render_attribute( 'video', 'width', '100%' );
		$this->add_render_attribute( 'video', 'height', '100%' );

		if ( ! empty( $settings['video_cover']['url'] ) ) {
			$this->add_render_attribute( 'video', 'poster', $settings['video_cover']['url'] );
		}

		// Wrapper 

		$output .= '<div ' . $this->get_render_attribute_string( 'video-wrapper' ) . '>';

			// Video

			$output .= '<video ' . $this->get_render_attribute_string( 'video' ) . ' ' . $video_settings . '>';

			if ( $settings['video_url'] ) {
				$this->add_render_attribute( 'source', 'src', $settings['video_url'] );
				$this->add_render_attribute( 'source', 'type', 'video/mp4' );

				$output .= '<source ' . $this->get_render_attribute_string( 'source' ) . '>';
			}

			if ( $settings['video_url_m4v'] ) {
				$this->add_render_attribute( 'source_m4v', 'src', $settings['video_url_m4v'] );

				$output .= '<source ' . $this->get_render_attribute_string( 'source_m4v' ) . '>';
			}

			if ( $settings['video_url_ogg'] ) {
				$this->add_render_attribute( 'source_ogg', 'src', $settings['video_url_ogg'] );
				$this->add_render_attribute( 'source_ogg', 'type', 'video/ogg' );

				$output .= '<source ' . $this->get_render_attribute_string( 'source_ogg' ) . '>';
			}

			if ( $settings['video_url_webm'] ) {
				$this->add_render_attribute( 'source_webm', 'src', $settings['video_url_webm'] );
				$this->add_render_attribute( 'source_webm', 'type', 'video/webm' );

				$output .= '<source ' . $this->get_render_attribute_string( 'source_webm' ) . '>';
			}

			$output .= '</video>';

			// Video cover

			$output .= '<div ' . $this->get_render_attribute_string( 'video-cover' ) . '>';

			$output .= '</div><!-- .elementor-extras-html5-video__cover -->';

			$output .= '<div ' . $this->get_render_attribute_string('video-controls') . '>';

				if ( 'show' === $settings['video_show_buttons'] ) {
					$output .= '<ul class="controls__overlay video__overlay">';

					if ( 'show' === $settings['video_show_rewind'] ) {
						$output .= '<li class="control controls__rewind nicon nicon-rewind"></li>';
					}

					$output .= '<li class="control controls__play nicon nicon-play"></li>';
					$output .= '</ul>';
				}

				if ( 'show' === $settings['video_show_bar'] ) {

					$output .= '<div class="controls__bar-wrapper">';
						$output .= '<div class="controls__bar">';

							if ( 'show' === $settings['video_show_rewind'] ) {
								$output .= '<div class="control controls__rewind control--icon nicon nicon-rewind"></div>';
							}

							$output .= '<div class="control controls__play control--icon nicon nicon-play"></div>';

							if ( 'show' === $settings['video_show_time'] ) {
								$output .= '<div class="control controls__time control--indicator">00:00</div>';
							}

							$output .= '<div class="control controls__progress control--progress">';

								$output .= '<div class="control controls__progress-time control--progress__inner"></div>';
								$output .= '<div class="control controls__progress-track control--progress__inner control--progress__track"></div>';

							$output .= '</div>';

							if ( 'show' === $settings['video_show_duration'] ) {
								$output .= '<div class="control controls__duration control--indicator">00:00</div>';
							}

							if ( 'show' === $settings['video_show_volume'] ) {
								$output .= '<div class="control controls__volume">';

									$output .= '<div class="control controls__volume-icon control--icon nicon nicon-volume"></div>';
									$output .= '<div class="control controls__volume-bar control--progress">';

										$output .= '<div class="control controls__volume-bar__amount control--progress__inner"></div>';
										$output .= '<div class="control controls__volume-bar__track control--progress__inner control--progress__track"></div>';

									$output .= '</div>';

								$output .= '</div>';
							}

							if ( 'show' === $settings['video_show_fs'] ) {
								$output .= '<div class="control controls__fs control--icon nicon nicon-expand"></div>';
							}

						$output .= '</div><!-- .controls__bar -->';
					$output .= '</div><!-- .controls__bar-wrapper -->';

				}

			$output .= '</div><!-- .elementor-extras-html5-video__controls -->';

		$output .= '</div>';

		echo $output;

	}

	protected function _content_template() {
		?>

		<#

		if ( '' !== settings.video_url || '' !== settings.video_url_ogg || '' !== settings.video_url_webm ) {

			var video_settings 	= '',
				output = '';

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

			output += '</div><!-- .elementor-extras-html5-video -->';

			print( output );

		} #><?php
	}
}
