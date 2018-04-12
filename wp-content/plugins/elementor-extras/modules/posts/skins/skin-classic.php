<?php
namespace ElementorExtras\Modules\Posts\Skins;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Classic extends Skin_Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Classic', 'elementor-extras' );
	}

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		// add_action( 'elementor/element/posts-extra/section_query/after_section_end', [ $this, 'register_parallax_controls' ] );
		add_action( 'elementor/element/posts-extra/section_query/after_section_end', [ $this, 'register_filters_controls' ] );
		add_action( 'elementor/element/posts-extra/section_query/after_section_end', [ $this, 'register_infinite_scroll_controls' ] );
		add_action( 'elementor/element/posts-extra/section_query/after_section_end', [ $this, 'register_pagination_controls' ] );

		add_action( 'elementor/element/posts-extra/section_style_terms/after_section_end', [ $this, 'register_filters_style_controls' ] );
		add_action( 'elementor/element/posts-extra/section_style_terms/after_section_end', [ $this, 'register_pagination_style_controls' ] );
		add_action( 'elementor/element/posts-extra/section_style_terms/after_section_end', [ $this, 'register_infinite_scroll_style_controls' ] );
	}

	public function register_layout_content_controls() {
		parent::register_layout_content_controls();

		$this->update_responsive_control(
			'grid_columns_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .ee-grid__item' => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ee-grid' => 'margin-left: -{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' 		=> __( 'Layout', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'default',
				'options' 		=> [
					'default' 		=> __( 'Default', 'elementor-extras' ),
					'masonry' 		=> __( 'Masonry', 'elementor-extras' ),
				],
				'condition'		=> [
					'columns!'	=> '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'filters',
			[
				'label' 				=> __( 'Enable Filters', 'elementor-extras' ),
				'type' 					=> Controls_Manager::SWITCHER,
				'default' 				=> '',
				'return_value' 			=> 'yes',
				'frontend_available' 	=> true,
			]
		);

		$this->add_control(
			'infinite_scroll',
			[
				'label' 		=> __( 'Infinite Scroll', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'return_value' 	=> 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' 		=> __( 'Show Pagination', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'return_value' 	=> 'yes',
				'condition'		=> [
					$this->get_control_id( 'infinite_scroll' ) => '',
				]
			]
		);
	}

	public function register_parallax_controls() {

		$this->start_controls_section(
			'section_parallax',
			[
				'label' => __( 'Parallax', 'elementor-extras' ),
				'condition' 	=> [
					$this->get_control_id( 'parallax!' ) => '',
				],
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
					'condition' 	=> [
						$this->get_control_id( 'parallax!' ) => '',
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
					'condition' 	=> [
						$this->get_control_id( 'parallax!' ) => '',
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();
	}

	public function register_infinite_scroll_controls() {

		$this->start_controls_section(
			'section_infinite_scroll',
			[
				'label' => __( 'Infinite Scroll', 'elementor-extras' ),
				'condition' 	=> [
					$this->get_control_id( 'infinite_scroll!' ) => '',
				],
			]
		);

			$this->add_control(
				'infinite_scroll_history',
				[
					'label' 		=> __( 'Enable History', 'elementor-extras' ),
					'description'	=> __( 'Change the browser history and URL when loading new posts.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'condition'		=> [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'layout' ) => 'default',
					]
				]
			);

			$this->add_control(
				'infinite_scroll_status_heading',
				[
					'separator'	=> 'before',
					'label' => __( 'Status and Loader', 'elementor-extras' ),
					'type' 	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'infinite_scroll_status',
				[
					'label' 		=> __( 'Show Statuses', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'condition' 	=> [
						$this->get_control_id( 'infinite_scroll!' ) => '',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_status_helper',
				[
					'label' 		=> __( 'Preview in Editor', 'elementor-extras' ),
					'description'	=> __( 'Preview loader and status texts in editor mode.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'return_value' 	=> 'on',
					'condition' 	=> [
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
						$this->get_control_id( 'infinite_scroll!' ) => '',
					],
					'prefix_class'	=> 'ee-load-status-helper-'
				]
			);

			$this->add_control(
				'infinite_scroll_loading_type',
				[
					'label' 		=> __( 'Loading Type', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'loader',
					'options' 		=> [
						'loader' 	=> __( 'Loader', 'elementor-extras' ),
						'text' 		=> __( 'Text', 'elementor-extras' ),
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_loading_loader',
				[
					'label' 		=> __( 'Loader', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'track',
					'options' 		=> [
						'track'    	=> [
							'title' 	=> __( 'Circle Track', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-loader-track',
						],
						'circle' 	=> [
							'title' 	=> __( 'Circle', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-loader-circle',
						],
						'bars-equal' => [
							'title' 	=> __( 'Equal Bars', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-loader-bars-equal',
						],
						'bars-flex' => [
							'title' 	=> __( 'Flexible Bars', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-loader-bars-flex',
						],
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
						$this->get_control_id( 'infinite_scroll_loading_type' ) => 'loader',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_loading_text',
				[
					'label' 		=> __( 'Loading Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'Loading', 'elementor-extras' ),
					'placeholder' 	=> __( 'Loading', 'elementor-extras' ),
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
						$this->get_control_id( 'infinite_scroll_loading_type' ) => 'text',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_last_text',
				[
					'label' 		=> __( 'Last Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'All articles loaded', 'elementor-extras' ),
					'placeholder' 	=> __( 'All articles loaded', 'elementor-extras' ),
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_error_text',
				[
					'label' 		=> __( 'Error Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'No more articles to load', 'elementor-extras' ),
					'placeholder' 	=> __( 'No more articles to load', 'elementor-extras' ),
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_button_heading',
				[
					'separator'	=> 'before',
					'label' => __( 'Load Button', 'elementor-extras' ),
					'type' 	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'infinite_scroll_button',
				[
					'label' 		=> __( 'Show Load Button', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'condition'		=> [
						$this->get_control_id( 'infinite_scroll!' ) => '',
					]
				]
			);

			$this->add_control(
				'infinite_scroll_button_text',
				[
					'label' 		=> __( 'Button Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'Load more', 'elementor-extras' ),
					'placeholder' 	=> __( 'Load more', 'elementor-extras' ),
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button' ) => 'yes',
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_filters_controls() {

		$this->start_controls_section(
			'section_filters',
			[
				'label' => __( 'Filters', 'elementor-extras' ),
				'condition' 	=> [
					$this->get_control_id( 'filters!' ) => '',
				],
			]
		);

			$taxonomies = $this->parent->get_taxonomies_options();

			$this->add_control(
				'filters_taxonomy',
				[
					'label' 		=> __( 'Taxonomy', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT2,
					'label_block' 	=> true,
					'options' 		=> $taxonomies,
					'condition' 	=> [
						$this->get_control_id( 'filters!' ) => '',
					],
				]
			);

			foreach ( $taxonomies as $name => $label ) {
				$terms = $this->parent->get_terms_options( $name );

				$this->add_control(
					'filters_taxonomy_' . str_replace( '-', '_', $name ),
					[
						'label' 		=> __( 'Default term', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SELECT,
						'label_block' 	=> true,
						'default'		=> '',
						'options' 		=> $terms,
						'condition' 	=> [
							$this->get_control_id( 'filters_taxonomy' ) => $name,
						],
					]
				);
			}

			$this->add_control(
				'filters_all_text',
				[
					'label' 		=> __( 'All Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'All', 'elementor-extras' ),
					'placeholder' 	=> __( 'All', 'elementor-extras' ),
					'condition' 	=> [
						$this->get_control_id( 'filters!' ) => '',
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'elementor-extras' ),
				'condition'		=> [
					$this->get_control_id( 'infinite_scroll' ) 	=> '',
					$this->get_control_id( 'pagination' ) 		=> 'yes',
				]
			]
		);

			$this->add_control(
				'pagination_numbers',
				[
					'label' 		=> __( 'Show Numbers', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'condition'		=> [
						$this->get_control_id( 'infinite_scroll' ) 	=> '',
						$this->get_control_id( 'pagination' ) 		=> 'yes',
					]
				]
			);

			$this->add_control(
				'pagination_prev_next',
				[
					'label' 		=> __( 'Show Prev Next', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'condition'		=> [
						$this->get_control_id( 'infinite_scroll' ) 	=> '',
						$this->get_control_id( 'pagination' ) 		=> 'yes',
					]
				]
			);

			$this->add_control(
				'pagination_page_limit',
				[
					'label' 		=> __( 'Page Limit', 'elementor-extras' ),
					'default' 		=> '5',
					'condition' 	=> [
						$this->get_control_id( 'pagination_numbers' ) 	=> 'yes',
						$this->get_control_id( 'infinite_scroll' ) 		=> '',
						$this->get_control_id( 'pagination' ) 			=> 'yes',
					],
				]
			);

			$this->add_control(
				'pagination_show_all',
				[
					'label' 		=> __( 'Show All Numbers', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'return_value' 	=> 'yes',
					'condition' 	=> [
						$this->get_control_id( 'pagination_numbers' ) 	=> 'yes',
						$this->get_control_id( 'infinite_scroll' ) 		=> '',
						$this->get_control_id( 'pagination' ) 			=> 'yes',
					],
				]
			);

			$this->add_control(
				'pagination_previous_label',
				[
					'label' 		=> __( 'Previous Label', 'elementor-extras' ),
					'default' 		=> __( '&larr; Previous', 'elementor-extras' ),
					'condition' 	=> [
						$this->get_control_id( 'pagination_prev_next' ) 	=> 'yes',
						$this->get_control_id( 'infinite_scroll' ) 			=> '',
						$this->get_control_id( 'pagination' ) 				=> 'yes',
					],
				]
			);

			$this->add_control(
				'pagination_next_label',
				[
					'label' 		=> __( 'Next Label', 'elementor-extras' ),
					'default' 		=> __( 'Next &rarr;', 'elementor-extras' ),
					'condition' 	=> [
						$this->get_control_id( 'pagination_prev_next' ) 	=> 'yes',
						$this->get_control_id( 'infinite_scroll' ) 			=> '',
						$this->get_control_id( 'pagination' ) 				=> 'yes',
					],
				]
			);

		$this->end_controls_section();
	}

	public function register_filters_style_controls() {

		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => __( 'Filters', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'filters!' ) => '',
				]
			]
		);

			$this->add_control(
				'filters_filters_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Filters', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'filters_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
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
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					],
					'prefix_class' 	=> 'ee-filters-align-',
				]
			);

			$this->add_responsive_control(
				'filters_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-filters' => 'margin-bottom: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'filters_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-filters__item',
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_control(
				'filters_filter_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Filter', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'filters_filter_spacing',
				[
					'label' 		=> __( 'Horizontal Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-filters' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-filters__item' => 'margin-left: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'filters_filter_vertical_spacing',
				[
					'label' 		=> __( 'Vertical Spacing', 'elementor-extras' ),
					'description'	=> __( 'If you have multuple lines of terms, this will help you distance them from one another.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-filters__item' => 'margin-bottom: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'filters_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-filters__item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->add_control(
				'filters_border_radius',
				[
					'separator'		=> 'after',
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-filters__item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'filters!' ) => '',
						$this->get_control_id( 'filters_taxonomy!' ) => '',
					]
				]
			);

			$this->start_controls_tabs( 'filters_tabs_hover' );

			$this->start_controls_tab( 'filters_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'filters_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a' => 'color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_control(
					'filters_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'filters_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-filters__item a',
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'filters_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'filters_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a:hover' => 'color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_control(
					'filters_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_control(
					'filters_border_color_hover',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a:hover' => 'border-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'filters_tab_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

				$this->add_control(
					'filters_color_active',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a.ee--active' => 'color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_control(
					'filters_background_color_active',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a.ee--active' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

				$this->add_control(
					'filters_border_color_active',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-filters__item a.ee--active' => 'border-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'filters!' ) => '',
							$this->get_control_id( 'filters_taxonomy!' ) => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function register_pagination_style_controls() {

		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => __( 'Pagination', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'pagination!' ) => '',
				]
			]
		);

			$this->add_control(
				'pagination_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Pagination', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
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
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					],
					'selectors'	=> [
						'{{WRAPPER}} .ee-pagination' => 'text-align: {{VALUE}};',
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
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-pagination' => 'margin-top: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'pagination_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-pagination .page-numbers',
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_control(
				'pagination_numbers_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Numbers', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_numbers_spacing',
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
						'{{WRAPPER}} .ee-pagination .page-numbers' => 'margin: 0 {{SIZE}}px',
					],
					'condition'		=> [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'pagination_numbers_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->add_control(
				'pagination_numbers_border_radius',
				[
					'separator'		=> 'after',
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'pagination!' ) => '',
					]
				]
			);

			$this->start_controls_tabs( 'pagination_numbers_tabs_hover' );

			$this->start_controls_tab( 'pagination_numbers_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'pagination_numbers_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'pagination_numbers_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'pagination_numbers_opacity',
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
							'{{WRAPPER}} .ee-pagination .page-numbers' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'pagination_numbers_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-pagination .page-numbers',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'pagination_numbers_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'pagination_numbers_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers[href]:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'pagination_numbers_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers[href]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'pagination_numbers_border_color_hover',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers[href]:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'pagination_numbers_opacity_hover',
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
							'{{WRAPPER}} .ee-pagination .page-numbers[href]:hover' => 'opacity: {{SIZE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'pagination_numbers_tab_current', [ 'label' => __( 'Current', 'elementor-extras' ) ] );

				$this->add_control(
					'pagination_numbers_color_current',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers.current' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'pagination_numbers_background_color_current',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'pagination_numbers_border_color_current',
					[
						'label' 	=> __( 'Border Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'pagination_numbers_opacity_current',
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
							'{{WRAPPER}} .ee-pagination .page-numbers.current' => 'opacity: {{SIZE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function register_infinite_scroll_style_controls() {

		$this->start_controls_section(
			'section_style_infinite_scroll',
			[
				'label' => __( 'Infinite Scroll', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'infinite_scroll!' ) => '',
				]
			]
		);

			$this->add_control(
				'infinite_scroll_status_style_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Status', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'infinite_scroll_status_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-load-status' => 'margin-top: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
					]
				]
			);

			$this->add_control(
				'infinite_scroll_loader_style_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Loader', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
						$this->get_control_id( 'infinite_scroll_loading_type' ) => 'loader',
					]
				]
			);

			$this->add_control(
				'infinite_scroll_loader_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-load-status__request svg *[fill]' => 'fill: {{VALUE}};',
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_status!' ) => '',
						$this->get_control_id( 'infinite_scroll_loading_type' ) => 'loader',
					]
				]
			);

			$this->add_control(
				'infinite_scroll_button_style_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Button', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'infinite_scroll_button_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-load-button' => 'margin-top: {{SIZE}}px',
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					]
				]
			);

			$this->add_responsive_control(
				'infinite_scroll_button_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'flex-start' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-load-button' => 'display: flex; justify-content: {{VALUE}};'
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					],
				]
			);

			$this->add_responsive_control(
				'infinite_scroll_button_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-load-button__trigger .ee-button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					],
				]
			);

			$this->add_control(
				'infinite_scroll_button_border_radius',
				[
					'separator'		=> 'after',
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}}  .ee-load-button__trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'infinite_scroll_button_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-load-button__trigger',
					'condition' => [
						$this->get_control_id( 'infinite_scroll!' ) => '',
						$this->get_control_id( 'infinite_scroll_button!' ) => '',
					],
				]
			);

			$this->start_controls_tabs( 'infinite_scroll_button_tabs_hover' );

			$this->start_controls_tab( 'infinite_scroll_button_tab_default', [
				'label' 	=> __( 'Default', 'elementor-extras' ),
				'condition' => [
					$this->get_control_id( 'infinite_scroll!' ) => '',
					$this->get_control_id( 'infinite_scroll_button!' ) => '',
				],
			] );

				$this->add_control(
					'infinite_scroll_button_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-load-button__trigger' => 'color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'infinite_scroll!' ) => '',
							$this->get_control_id( 'infinite_scroll_button!' ) => '',
						],
					]
				);

				$this->add_control(
					'infinite_scroll_button_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-load-button__trigger' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'infinite_scroll!' ) => '',
							$this->get_control_id( 'infinite_scroll_button!' ) => '',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'infinite_scroll_button_tab_hover', [
				'label' 	=> __( 'Hover', 'elementor-extras' ),
				'condition' => [
					$this->get_control_id( 'infinite_scroll!' ) => '',
					$this->get_control_id( 'infinite_scroll_button!' ) => '',
				],
			] );

				$this->add_control(
					'infinite_scroll_button_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-load-button__trigger:hover' => 'color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'infinite_scroll!' ) => '',
							$this->get_control_id( 'infinite_scroll_button!' ) => '',
						],
					]
				);

				$this->add_control(
					'infinite_scroll_button_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-load-button__trigger:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							$this->get_control_id( 'infinite_scroll!' ) => '',
							$this->get_control_id( 'infinite_scroll_button!' ) => '',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function before_loop() {
		$this->render_filters();
	}

	public function after_loop() {
		$this->render_pagination();
		$this->render_load_status();
		$this->render_load_button();
	}

	protected function render_post_start() {
		global $post;

		$settings = $this->parent->get_settings();
		$filter_classes = [];

		// Generate array with class names from filters
		if ( isset( $post->filters ) ) {
			foreach ( $post->filters as $filter ) {
				$filter_classes[] = 'ee-filter-' . $filter->term_id;
			}
		}

		$this->parent->add_render_attribute( 'grid-item' . get_the_ID(), [
			'class'	=> [
				'ee-grid__item',
				'ee-loop__item',
				'ee-u-1/' . $settings[ 'columns' ],
				'ee-u-1/' . $settings[ 'columns_tablet' ] . '@desktop',
				'ee-u-1/' . $settings[ 'columns_mobile' ] . '@mobile',
				implode( ' ', $filter_classes ),
			],
		] );

		$this->before_grid_item();

		$post_classes = [ 'ee-post' ];

		if ( 'yes' === $settings['post_media'] && in_array( $settings['columns'], array( 1, 2 ) ) && in_array( $settings[ 'post_media_position' ], array( 'left', 'right' ) ) ) {
			$post_classes[] = 'ee-post--horizontal';
			$post_classes[] = 'ee-post--horizontal__' . $settings[ 'post_media_position' ];
		}

		if ( is_sticky( $post->ID ) ) {
			$post_classes[] = 'sticky';
		}

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'grid-item' . get_the_ID() ); ?>>
			<article <?php post_class( $post_classes ); ?>>
		<?php
	}

	protected function render_filters() {

		$taxonomy = $this->get_instance_value( 'filters_taxonomy' );

		if ( '' === $this->get_instance_value( 'filters' ) || ! $taxonomy )
			return;
		
		$this->parent->set_filters( $taxonomy );
		
		$filters 					= $this->parent->get_filters();
		$default_filter_control_id 	= $this->get_control_id( 'filters_taxonomy_' . str_replace( '-', '_', $taxonomy ) );
		$default_filter 			= $this->parent->get_settings( $default_filter_control_id );

		if ( empty( $filters ) )
			return;

		$this->parent->add_render_attribute( 'filters', 'class', [
			'ee-filters',
			'ee-filters--' . $taxonomy,
		] );

		$this->parent->add_render_attribute( 'filter-all', 'class', [
			'ee-filters__item',
			'o-nav__item',
		] );

		if ( '' === $default_filter ) {
			$this->parent->add_render_attribute( $this->get_control_id( 'filters_all_text' ), 'class', 'ee--active' );
		}

		$this->parent->add_render_attribute( $this->get_control_id( 'filters_all_text' ), 'data-filter', '*' );

		?><ul <?php echo $this->parent->get_render_attribute_string( 'filters' ); ?>>

			<li <?php echo $this->parent->get_render_attribute_string( 'filter-all' ); ?>><a <?php echo $this->parent->get_render_attribute_string( $this->get_control_id( 'filters_all_text' ) ); ?>>
				<?php echo $this->get_instance_value( 'filters_all_text' ); ?>
			</a></li>

			<?php foreach ( $filters as $filter ) {

				$filter_term_key = 'filter-term-' . $filter->term_id;
				$filter_link_key = 'filter-link-' . $filter->term_id;

				$this->parent->add_render_attribute( $filter_term_key, 'class', [
					'ee-filters__item',
					'o-nav__item',
					'ee-term',
					'ee-term--' . $filter->slug,
				] );

				$this->parent->add_render_attribute( $filter_link_key, [
					'data-filter' 	=> '.ee-filter-' . $filter->term_id,
					'class' 		=> 'ee-term__link'
				] );

				if ( $filter->slug === $default_filter ) {
					$this->parent->add_render_attribute( $filter_link_key, 'class', 'ee--active' );
				}

				?>
				<li <?php echo $this->parent->get_render_attribute_string( $filter_term_key ); ?>>
					<a <?php echo $this->parent->get_render_attribute_string( $filter_link_key ); ?>><?php echo $filter->name; ?></a>
				</li>
			<?php } ?>
		</ul><?php
	}

	public function render_load_status() {

		if ( 'yes' !== $this->get_instance_value( 'infinite_scroll_status' ) )
			return;

		$this->parent->add_render_attribute( 'status', 'class', 'ee-load-status' );
		$this->parent->add_render_attribute( 'status-request', 'class', [
			'ee-load-status__request',
			'infinite-scroll-request'
		] );

		$this->parent->add_render_attribute( 'status-last', 'class', [
			'ee-load-status__last',
			'infinite-scroll-last'
		] );

		$this->parent->add_render_attribute( 'status-error', 'class', [
			'ee-load-status__error',
			'infinite-scroll-error'
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'status' ); ?>>
			<div <?php echo $this->parent->get_render_attribute_string( 'status-request' ); ?>>
				<?php
					if ( 'text' === $this->get_instance_value( 'infinite_scroll_loading_type' ) ) {
						echo $this->get_instance_value( 'infinite_scroll_loading_text' );
					} else if ( 'loader' === $this->get_instance_value( 'infinite_scroll_loading_type' ) ) {
						echo $this->render_loading_svg();
					}
				?>
			</div>
			<div <?php echo $this->parent->get_render_attribute_string( 'status-last' ); ?>>
				<?php echo $this->get_instance_value( 'infinite_scroll_last_text' ); ?>
			</div>
			<div <?php echo $this->parent->get_render_attribute_string( 'status-error' ); ?>>
				<?php echo $this->get_instance_value( 'infinite_scroll_error_text' ); ?>
			</div>
		</div><?php

	}

	public function render_load_button() {

		if ( '' === $this->get_instance_value( 'infinite_scroll' ) || '' === $this->get_instance_value( 'infinite_scroll_button' ) )
			return;

		$this->parent->add_render_attribute( 'load', 'class', [
			'ee-load-button',
		] );

		$this->parent->add_render_attribute( 'load-button', 'class', [
			'ee-load-button__trigger',
			'ee-button',
			'ee-size-sm',
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'load' ); ?>>
			<a href="" <?php echo $this->parent->get_render_attribute_string( 'load-button' ); ?>>
				<span class="ee-button-content-wrapper ">
					<span class="ee-button-text">
						<?php echo $this->get_instance_value( 'infinite_scroll_button_text' ); ?>
					</span>
				</span>
			</a>
		</div><?php

	}

	protected function render_loading_svg() {

		$loader_filename = 'track';

		if ( $this->get_instance_value( 'infinite_scroll_loading_loader' ) ) {
			$loader_filename = $this->get_instance_value( 'infinite_scroll_loading_loader' );
		}

		include ELEMENTOR_EXTRAS_PATH . 'assets/shapes/loader-' . $loader_filename . '.svg';
	}

	public function render_pagination() {

		if ( 'yes' !== $this->get_instance_value('pagination') && '' === $this->get_instance_value('infinite_scroll') )
			return;

		$limit = $this->parent->get_query()->max_num_pages;

		if ( '' !== $this->get_instance_value('pagination_page_limit')  ) {
			$limit = min( $this->get_instance_value('pagination_page_limit'), $limit );
		}

		if ( 2 > $limit ) {
			return;
		}

		$this->parent->add_render_attribute( 'pagination', [
			'class' 		=> 'ee-pagination',
			'role' 			=> 'navigation',
			'aria-label' 	=> __( 'Pagination', 'elementor-extras' ),
		] );

		if ( 'yes' === $this->get_instance_value( 'infinite_scroll' ) ) {
			$this->parent->add_render_attribute( 'pagination', 'class', 'ee-pagination--is' );
		}

		// Render page links
		$pagination = paginate_links( [
			'type'					=> 'plain',
			'total' 				=> $limit,
			'current' 				=> $this->parent->get_current_page(),
			'prev_next' 			=> false,
			'show_all' 				=> 'yes' === $this->get_instance_value('pagination_show_all'),
			'before_page_number' 	=> '<span class="elementor-screen-only">' . __( 'Page', 'elementor-extras' ) . '</span>',
		] );

		?>
		<nav <?php echo $this->parent->get_render_attribute_string( 'pagination' ); ?>>
			<?php

			if ( 'yes' === $this->get_instance_value('pagination_prev_next') && 'yes' !== $this->get_instance_value('infinite_scroll') ) {
				$this->parent->render_previous_nav_link();
			}
				
			if ( 'yes' === $this->get_instance_value('pagination_numbers') && 'yes' !== $this->get_instance_value('infinite_scroll') )
				echo $pagination;

			if ( 'yes' === $this->get_instance_value('infinite_scroll') || ( '' === $this->get_instance_value('infinite_scroll') && 'yes' === $this->get_instance_value('pagination_prev_next') ) ) {
				$this->parent->render_next_nav_link();
			}

			?>
		</nav>
		<?php
	}	

	public function render_scripts() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false )
			return;

		?><script type="text/javascript">
        	jQuery( document ).ready( function( $ ) {

				$( '.ee-loop' ).each( function() {

					var $scope_id = '<?php echo $this->parent->get_id(); ?>',
        				$scope = $( '[data-id="' + $scope_id + '"]' );

        			// Don't move forward if this is not our widget
        			if ( $(this).closest( $scope ).length < 1 ) {
        				return;
        			}

					var $loop 		= $(this),
						$filters 	= $loop.siblings('.ee-filters'),
						$triggers 	= $filters.find( '[data-filter]' ),

						_layout 	= '<?php echo $this->get_instance_value( 'layout' ); ?>',

						isotopeArgs = {
							itemSelector	: '.ee-loop__item',
							layoutMode 		: _layout,
			  				percentPosition : true,
			  				hiddenStyle 	: {
			  					opacity 	: 0,
			  				},
			  				masonry 		: {
								columnWidth	: '.ee-grid__item--sizer',
							},
						},

						filteryArgs = {
							wrapper : $loop,
							filterables : '.ee-loop__item',
							activeFilterClass : 'ee--active',
						};

					$loop.imagesLoaded( function() {

						if ( _layout !== 'default' ) {

							var $isotope = $loop.isotope( isotopeArgs );
							var isotopeInstance = $loop.data( 'isotope' );

							$loop.find('.ee-grid__item:last-child').resize( function() {
								$loop.isotope( 'layout' );
							});

							if ( $triggers.length ) {

								// Filter by default
								var $default_trigger = $triggers.filter('.ee--active');

								if ( $default_trigger.length ) {
									default_filter = $default_trigger.data('filter');
									$loop.isotope({ filter: default_filter });
								}

								// Filter by click
								$triggers.on( 'click', function() {
									var _filter = $(this).data('filter');

									$loop.isotope({ filter: _filter });

									$triggers.removeClass('ee--active');
									$(this).addClass('ee--active');
								});
							}

						} else {
							if ( $triggers.length ) {
								$filters.filtery( filteryArgs );
							}
						}
					});

				} );
				
        	} );
		</script><?php
	}
}