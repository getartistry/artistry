<?php
namespace ElementorExtras\Modules\Posts\Skins;

// Elementor Extras Classes
use ElementorExtras\Group_Control_Transition;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Carousel extends Skin_Base {

	public function get_id() {
		return 'carousel';
	}

	public function get_title() {
		return __( 'Carousel', 'elementor-extras' );
	}

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/posts-extra/section_query/after_section_end', [ $this, 'register_carousel_controls' ] );
		add_action( 'elementor/element/posts-extra/section_style_posts/after_section_end', [ $this, 'register_carousel_style_controls' ] );
	}

	public function register_layout_content_controls() {
		$slides_per_column = range( 1, 4 );
		$slides_per_column = array_combine( $slides_per_column, $slides_per_column );

		$this->add_responsive_control(
			'slides_per_column',
			[
				'type' 					=> Controls_Manager::SELECT,
				'label' 				=> __( 'Rows', 'elementor-extras' ),
				'options' 				=> [ '' => __( 'Default', 'elementor-extras' ) ] + $slides_per_column,
				'frontend_available' 	=> true,
			]
		);

		$this->add_responsive_control(
				'slides_to_scroll',
				[
					'type' 			=> Controls_Manager::SELECT,
					'label' 		=> __( 'Slides to Scroll', 'elementor-extras' ),
					'options' 		=> [ '' => __( 'Default', 'elementor-extras' ) ] + $slides_per_column,
					'frontend_available' => true,
				]
			);

		parent::register_layout_content_controls();

		$this->update_control( 'grid_columns_spacing', [
			'label' => __( 'Grid Spacing', 'elementor-extras' ),
		] );

		$this->remove_control( 'grid_rows_spacing' );
	}

	public function register_carousel_controls() {

		$this->start_controls_section(
			'section_carousel',
			[
				'label' => __( 'Carousel', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'autoplay',
				[
					'label' 	=> __( 'Autoplay', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SWITCHER,
					'default' 	=> '',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay_speed',
				[
					'label' 	=> __( 'Autoplay Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> 5000,
					'condition' => [
						$this->get_control_id( 'autoplay' ) => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'pause_on_interaction',
				[
					'label' 		=> __( 'Disable on Interaction', 'elementor-extras' ),
					'description' 	=> __( 'Removes autoplay completely on the first interaction with the carousel.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'condition' 	=> [
						$this->get_control_id( 'autoplay' ) => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'loop',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Loop', 'elementor-extras' ),
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'separator'		=> 'before',
					'frontend_available' 	=> true,
				]
			);

			$this->add_control(
				'speed',
				[
					'label' 	=> __( 'Duration (ms)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 300,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 1500,
							'step'	=> 100,
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect',
				[
					'type' 			=> Controls_Manager::SELECT,
					'label' 		=> __( 'Effect', 'elementor-extras' ),
					'description' 	=> __( 'Automatically uses one slide per view (1 column) layout', 'elementor-extras' ),
					'default' 		=> 'slide',
					'options' 		=> [
						'slide' 	=> __( 'Slide', 'elementor-extras' ),
						'fade' 		=> __( 'Fade', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoheight',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Autoheight', 'elementor-extras' ),
					'description' 	=> __( 'Adjusts height of the carousel according to height of slides currently in view', 'elementor-extras' ),
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' 	=> true,
					'conditions'=> [
						'relation' 	=> 'or',
						'terms' 	=> [
							[
								'name' 		=> $this->get_control_id('slides_per_column'),
								'operator' 	=> '==',
								'value' 	=> '1',
							],
							[
								'name' 		=> $this->get_control_id('slides_per_column'),
								'operator' 	=> '==',
								'value' 	=> '',
							],
						]
					]
				]
			);

			$this->add_control(
				'resistance_ratio',
				[
					'label' 		=> __( 'Resistance', 'elementor-extras' ),
					'description'	=> __( 'Set the value for resistant bounds.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 		=> [
						'size' 		=> 0.25,
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1,
							'step'	=> 0.05,
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'free_mode',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Free Mode', 'elementor-extras' ),
					'description'	=> __( 'Disable fixed positions for slides.', 'elementor-extras' ),
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' 	=> true,
				]
			);

			$this->add_control(
				'free_mode_momentum',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Momentum', 'elementor-extras' ),
					'description'	=> __( 'Enable to keep slide moving for a while after you release it.', 'elementor-extras' ),
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'condition' => [
						$this->get_control_id( 'free_mode!' ) => '',
					],
				]
			);

			$this->add_control(
				'free_mode_sticky',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Snap to position', 'elementor-extras' ),
					'description'	=> __( 'Enable to snap slides to positions in free mode.', 'elementor-extras' ),
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' 	=> true,
					'condition' => [
						$this->get_control_id( 'free_mode!' ) => '',
					],
				]
			);

			$this->add_control(
				'arrow_heading',
				[
					'label' => __( 'Arrows', 'elementor-extras' ),
					'type' 	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'arrows',
				[
					'label' 		=> __( 'Arrows', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'on',
					'label_on' 		=> __( 'On', 'elementor-extras' ),
					'label_off' 	=> __( 'Off', 'elementor-extras' ),
					'return_value' 	=> 'on',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'arrow_pagination',
				[
					'label' => __( 'Pagination', 'elementor-extras' ),
					'type' 	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'pagination',
				[
					'label' 		=> __( 'Pagination', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'on',
					'label_on' 		=> __( 'On', 'elementor-extras' ),
					'label_off' 	=> __( 'Off', 'elementor-extras' ),
					'return_value' 	=> 'on',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'pagination_type',
				[
					'type' 			=> Controls_Manager::SELECT,
					'label' 		=> __( 'Type', 'elementor-extras' ),
					'default'		=> 'bullets',
					'options' 		=> [
						'bullets' 		=> __( 'Bullets', 'elementor-extras' ),
						'fraction' 		=> __( 'Fraction', 'elementor-extras' ),
					],
					'frontend_available' 	=> true,
				]
			);

			$this->add_control(
				'pagination_clickable',
				[
					'type' 			=> Controls_Manager::SWITCHER,
					'label' 		=> __( 'Clickable', 'elementor-extras' ),
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'condition' => [
						$this->get_control_id( 'pagination_type' ) => 'bullets',
					],
					'frontend_available' 	=> true,
				]
			);

		$this->end_controls_section();

	}

	public function register_carousel_style_controls() {

		$this->start_controls_section(
			'section_style_carousel',
			[
				'label' => __( 'Carousel', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'arrows_style_heading',
				[
					'label' 	=> __( 'Arrows', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->add_control(
				'arrows_position',
				[
					'type' 			=> Controls_Manager::SELECT,
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'default'		=> 'middle',
					'options' 		=> [
						'top' 		=> __( 'Top', 'elementor-extras' ),
						'middle' 	=> __( 'Middle', 'elementor-extras' ),
						'bottom' 	=> __( 'Bottom', 'elementor-extras' ),
					],
					'prefix_class' 	=> 'ee-swiper-arrows-position--',
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->add_responsive_control(
				'arrows_size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 12,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-swiper__button' => 'font-size: {{SIZE}}px;',
					],
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->add_responsive_control(
				'arrows_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1,
							'step'	=> 0.1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-swiper__button' => 'padding: {{SIZE}}em;',
					],
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->add_responsive_control(
				'arrows_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-swiper__button' => 'margin: {{SIZE}}px;',
						'{{WRAPPER}}.ee-swiper-arrows-position--middle .ee-swiper__button' => 'transform: translateY( calc(-50% - {{SIZE}}px ) )',
					],
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->add_responsive_control(
				'arrows_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-swiper__button' => 'border-radius: {{SIZE}}%;',
					],
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					],
					'separator'		=> 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'arrows',
					'selector' 		=> '{{WRAPPER}} .ee-swiper__button',
					'condition'		=> [
						$this->get_control_id('arrows!') => '',
					]
				]
			);

			$this->start_controls_tabs( 'arrows_tabs_hover' );

			$this->start_controls_tab( 'arrows_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'arrows_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-swiper__button i:before' => 'color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id('arrows!') => '',
						]
					]
				);

				$this->add_control(
					'arrows_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-swiper__button' => 'background-color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id('arrows!') => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'arrows_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-swiper__button:not(.ee-swiper__button--disabled):hover i:before' => 'color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id('arrows!') => '',
						]
					]
				);

				$this->add_control(
					'arrows_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-swiper__button:not(.ee-swiper__button--disabled):hover' => 'background-color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id('arrows!') => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_tab_disabled', [ 'label' => __( 'Disabled', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'arrows_opacity_disabled',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.05,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-swiper__button--disabled' => 'opacity: {{SIZE}};',
						],
						'condition'		=> [
							$this->get_control_id('arrows!') => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'pagination_style_heading',
				[
					'separator'	=> 'before',
					'label' 	=> __( 'Pagination', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_control(
				'pagination_position',
				[
					'type' 			=> Controls_Manager::SELECT,
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'default'		=> 'inside',
					'options' 		=> [
						'inside' 		=> __( 'Inside', 'elementor-extras' ),
						'outside' 		=> __( 'Outside', 'elementor-extras' ),
					],
					'frontend_available' 	=> true,
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'center',
					'options' 		=> [
						'left'    		=> [
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
					'selectors'		=> [
						'{{WRAPPER}} .ee-swiper__pagination' => 'text-align: {{VALUE}};',
					],
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-swiper__pagination--inside' => 'padding: 0 {{SIZE}}px {{SIZE}}px {{SIZE}}px;',
						'{{WRAPPER}} .ee-swiper__pagination--outside' => 'margin-top: {{SIZE}}px;',
					],
					'condition'		=> [
						$this->get_control_id('pagination!') => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_bullets_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}px',
					],
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
						$this->get_control_id( 'pagination_type' ) => 'bullets',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_bullets_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{SIZE}}px;',
					],
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
						$this->get_control_id( 'pagination_type' ) => 'bullets',
					],
					'separator'		=> 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'pagination',
					'selector' 		=> '{{WRAPPER}} .swiper-pagination-bullet',
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->start_controls_tabs( 'pagination_bullets_tabs_hover' );

			$this->start_controls_tab( 'pagination_bullets_tab_default', [
				'label' 		=> __( 'Default', 'elementor-extras' ),
				'condition'		=> [
					$this->get_control_id( 'pagination!' ) => '',
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				]
			] );

				$this->add_responsive_control(
					'pagination_bullets_size',
					[
						'label' 		=> __( 'Size', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 12,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_control(
					'pagination_bullets_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_responsive_control(
					'pagination_bullets_opacity',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.05,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'pagination_bullets_tab_hover',[
				'label' 		=> __( 'Hover', 'elementor-extras' ),
				'condition'		=> [
					$this->get_control_id( 'pagination!' ) => '',
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				]
			] );

				$this->add_responsive_control(
					'pagination_bullets_size_hover',
					[
						'label' 		=> __( 'Size', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 1,
								'max' => 1.5,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'transform: scale({{SIZE}});',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_control(
					'pagination_bullets_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_responsive_control(
					'pagination_bullets_opacity_hover',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.05,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'opacity: {{SIZE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'pagination_bullets_tab_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'pagination_bullets_size_active',
					[
						'label' 		=> __( 'Size', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 1,
								'max' => 1.5,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet-active' => 'transform: scale({{SIZE}});',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_control(
					'pagination_bullets_color_active',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

				$this->add_responsive_control(
					'pagination_bullets_opacity_active',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.05,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
						],
						'condition'		=> [
							$this->get_control_id( 'pagination!' ) => '',
							$this->get_control_id( 'pagination_type' ) => 'bullets',
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render_loop_start() {
		$this->parent->add_render_attribute( 'swiper', 'class', 'ee-swiper' );
		$this->parent->add_render_attribute( 'swiper-container', 'class', [
			'ee-swiper__container',
			'swiper-container',
		] );
		$this->parent->add_render_attribute( 'swiper-wrapper', 'class', [
			'ee-grid',
			'ee-swiper__wrapper',
			'swiper-wrapper',
		] );

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'swiper' ); ?>>
			<div <?php echo $this->parent->get_render_attribute_string( 'swiper-container' ); ?>>
				<div <?php echo $this->parent->get_render_attribute_string( 'swiper-wrapper' ); ?>>
		<?php
	}

	protected function render_loop_end() {
				?></div><!-- .ee-swiper__wrapper -->

				<?php echo $this->render_swiper_navigation(); ?>

			</div><!-- .ee-swiper__container -->
		</div><!-- .ee-swiper --><?php

		if ( 'outside' === $this->parent->get_settings( $this->get_control_id( 'pagination_position' ) ) ) {
			$this->parent->add_render_attribute( 'pagination', 'class', 'ee-swiper__pagination--outside' );
			$this->render_swiper_pagination();
		}
	}

	protected function render_swiper_navigation() {
		$this->parent->add_render_attribute( 'navigation', 'class', 'ee-swiper__navigation' );

		?><div <?php echo $this->parent->get_render_attribute_string( 'navigation' ); ?>><?php
			$this->render_swiper_arrows();
		?></div><?php

		if ( 'outside' !== $this->parent->get_settings( $this->get_control_id( 'pagination_position' ) ) ) {
			$this->parent->add_render_attribute( 'pagination', 'class', 'ee-swiper__pagination--inside' );
			$this->render_swiper_pagination();
		}
	}

	public function render_swiper_pagination() {
		if ( '' === $this->parent->get_settings( $this->get_control_id( 'pagination' ) ) )
			return;

		$this->parent->add_render_attribute( 'pagination', 'class', [
			'ee-swiper__pagination',
			'ee-arrows',
			'ee-swiper__pagination-' . $this->parent->get_id(),
			'swiper-pagination',
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'pagination' ); ?>></div><?php
	}

	protected function render_swiper_arrows() {
		if ( '' === $this->parent->get_settings( $this->get_control_id( 'arrows' ) ) )
			return;

		$this->parent->add_render_attribute( 'button-prev', 'class', [
			'ee-swiper__button',
			'ee-swiper__button--prev',
			'ee-arrow',
			'ee-arrow--prev',
			'ee-swiper__button--prev-' . $this->parent->get_id(),
		] );

		$this->parent->add_render_attribute( 'button-next', 'class', [
			'ee-swiper__button',
			'ee-swiper__button--next',
			'ee-arrow',
			'ee-arrow--next',
			'ee-swiper__button--next-' . $this->parent->get_id(),
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'button-prev' ); ?>><i class="eicon-chevron-left"></i></div>
		<div <?php echo $this->parent->get_render_attribute_string( 'button-next' ); ?>><i class="eicon-chevron-right"></i></div><?php
	}

	protected function render_post_start() {
		global $post;

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'slide' . get_the_ID(), [
			'class'	=> [
				'ee-grid__item',
				'ee-swiper__slide',
				'swiper-slide',
			],
		] );

		$post_classes = [
			'ee-post',
		];

		if ( 'yes' === $settings['post_media'] && in_array( $settings['columns'], array( '1', '2' ) ) && in_array( $settings[ 'post_media_position' ], array( 'left', 'right' ) ) ) {
			$post_classes[] = 'ee-post--horizontal';
			$post_classes[] = 'ee-post--horizontal__' . $settings[ 'post_media_position' ];
		}

		if ( is_sticky( $post->ID ) ) {
			$post_classes[] = 'sticky';
		}

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'slide' . get_the_ID() ); ?>>
			<article <?php post_class( $post_classes ); ?>>
		<?php
	}

	protected function render_sizer() {}
}