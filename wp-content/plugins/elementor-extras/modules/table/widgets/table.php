<?php
namespace ElementorExtras\Modules\Table\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Transition;

// Elementor Classes
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Table
 *
 * @since 0.1.0
 */
class Table extends Extras_Widget {

	public function get_name() {
		return 'table';
	}

	public function get_title() {
		return __( 'Table', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-table';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.1.0
	 **/
	public function get_script_depends() {
		return [ 'tablesorter' ];
	}

	protected function _register_cell_controls( $repeater, $condition = array() ) {

		$repeater->add_control(
			'cell_content',
			[
				'label' 		=> __( 'Cell Content', 'elementor-extras' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> 'text',
				'options' 	=> [
					'text' 		=> __( 'Text', 'elementor-extras' ),
				],
				'condition'	=> array_merge( $condition, [] ),
			]
		);

		$repeater->add_control(
			'cell_text',
			[
				'label' 		=> __( 'Cell Text', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'condition'		=> array_merge(
					$condition, [
						'cell_content' 	=> 'text',
					]
				),
			]
		);

		$repeater->add_control(
			'cell_icon',
			[
				'label' 		=> __( 'Icon', 'elementor-extras' ),
				'type' 			=> Controls_Manager::ICON,
				'label_block' 	=> false,
				'default' 		=> '',
				'condition'		=> array_merge(
					$condition, [
						'cell_content' 	=> 'text',
					]
				),
			]
		);

		$repeater->add_control(
			'cell_icon_align',
			[
				'label' 	=> __( 'Icon Position', 'elementor-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'left',
				'options' 	=> [
					'left' 		=> __( 'Before', 'elementor-extras' ),
					'right' 	=> __( 'After', 'elementor-extras' ),
				],
				'condition'		=> array_merge(
					$condition, [
						'cell_content' 	=> 'text',
						'cell_icon!' 	=> '',
					]
				),
			]
		);

		$repeater->add_control(
			'cell_icon_indent',
			[
				'label' 	=> __( 'Icon Spacing', 'elementor-extras' ),
				'type' 		=> Controls_Manager::NUMBER,
				'min'		=> 0,
				'max'		=> 48,
				'default'	=> '',
				'step'		=> 1,
				'condition'		=> array_merge(
					$condition, [
						'cell_content' 	=> 'text',
						'cell_icon!' 	=> '',
					]
				),
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .ee-table__text .ee-align-icon--right' => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .ee-table__text .ee-align-icon--left' => 'margin-right: {{SIZE}}px;',
				],
			]
		);

		$repeater->add_control(
			'cell_span',
			[
				'label'   		=> __( 'Column Span', 'elementor-extras' ),
				'title' 		=> __( 'How many columns should this column span across.', 'elementor-extras' ),
				'type'    		=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'max'     		=> 20,
				'step'    		=> 1,
				'condition'		=> array_merge( $condition, [] ),
			]
		);

		$repeater->add_control(
			'cell_row_span',
			[
				'label'   		=> __( 'Row Span', 'elementor-extras' ),
				'title' 		=> __( 'How many rows should this column span across.', 'elementor-extras' ),
				'type'    		=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'max'     		=> 20,
				'step'    		=> 1,
				'condition'		=> array_merge( $condition, [] ),
				'separator'		=> 'below',
			]
		);

		$repeater->add_control(
			'_item_id',
			[
				'label' 		=> __( 'CSS ID', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '',
				'label_block' 	=> false,
				'title' 		=> __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-extras' ),
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' 		=> __( 'CSS Classes', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '',
				'prefix_class' 	=> '',
				'label_block' 	=> false,
				'title' 		=> __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-extras' ),
			]
		);

	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_header',
			[
				'label' => __( 'Header', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'sortable',
				[
					'label' 		=> __( 'Sortable', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'description'   => __( 'Enables sorting rows by clicking on header cells.', 'elementor-extras' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'responsive',
				[
					'label' 		=> __( 'Responsive', 'elementor-extras' ),
					'description'   => __( 'Converts the header row into individual headers for each cell on mobile.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'responsive',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'responsive',
					'prefix_class'	=> 'ee-table--'
				]
			);

			$this->add_control(
				'mobile_headers_hide',
				[
					'label' 		=> __( 'Hide on Mobile', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'hide',
					'description'   => __( 'Hide headers completely on mobile.', 'elementor-extras' ),
					'frontend_available' => true,
					'prefix_class'	=> 'ee-table-mobile-header--',
					'condition'		=> [
						'responsive'			=> 'responsive',
					],
				]
			);

			$this->add_control(
				'mobile_headers_auto',
				[
					'label' 		=> __( 'Auto Mobile Headers', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'default'		=> 'yes',
					'description'   => __( 'Try to automatically fetch corresponding headers content on mobile. Works only when column span values are not used.', 'elementor-extras' ),
					'frontend_available' => true,
					'condition'		=> [
						'responsive'			=> 'responsive',
						'mobile_headers_hide!' 	=> 'hide',
					],
				]
			);

			$this->add_control(
				'mobile_headers_display',
				[
					'label' 		=> __( 'Mobile Display', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'row',
					'options' 		=> [
						'row'    	=> [
							'title' 	=> __( 'Column', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-inline',
						],
						'column' 		=> [
							'title' 	=> __( 'Row', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-block',
						],
					],
					'condition'		=> [
						'responsive'			=> 'responsive',
						'mobile_headers_hide!' 	=> 'hide',
					],
					'label_block'	=> false,
					'prefix_class'	=> 'ee-table-mobile-header--'
				]
			);

			$repeater_header = new Repeater();

			$this->_register_cell_controls( $repeater_header, [] );

			$this->add_control(
				'header_cells',
				[
					'label' 	=> __( 'Rows', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'cell_text' 	=> __( 'First header', 'elementor-extras' ),
						],
						[
							'cell_text' 	=> __( 'Second header', 'elementor-extras' ),
						],
						[
							'cell_text' 	=> __( 'Third header', 'elementor-extras' ),
						],
					],
					'prevent_empty'		=> true,
					'fields' 			=> array_values( $repeater_header->get_controls() ),
					'title_field' 		=> '{{{ cell_text }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-extras' ),
			]
		);

			$repeater_elements = new Repeater();

			$repeater_elements->add_control(
				'type',
				[
					'label'		=> __( 'Element', 'elementor-extras' ),
					'type'		=> Controls_Manager::SELECT,
					'default'	=> 'cell',
					'options' 	=> [
						'row' 		=> __( 'Row', 'elementor-extras' ),
						'cell' 		=> __( 'Cell', 'elementor-extras' ),
					],

				]
			);

			$repeater_elements->add_control(
				'cell_type',
				[
					'label' 		=> __( 'Cell Type', 'elementor-extras' ),
					'type'		=> Controls_Manager::SELECT,
					'default'	=> 'td',
					'options' 	=> [
						'td' 	=> __( 'Default', 'elementor-extras' ),
						'th' 	=> __( 'Header', 'elementor-extras' ),
					],
					'condition'	=> [
						'type'		=> 'cell',
					]
				]
			);

			$repeater_elements->add_control(
				'cell_header',
				[
					'label' 		=> __( 'Mobile Header', 'elementor-extras' ),
					'description'	=> __( 'Overrides value set by Auto Mobile Header option.', 'elementor-extras' ),
					'title' 		=> __( 'Specify the header text for this cell to appear on mobile', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'condition'	=> [
						'type'		=> 'cell',
					]
				]
			);

			$this->_register_cell_controls( $repeater_elements, [ 'type' => 'cell' ] );

			$repeater_elements->add_control(
				'link',
				[
					'label' 		=> __( 'Link', 'elementor-extras' ),
					'type' 			=> Controls_Manager::URL,
					'label_block'	=> false,
					'placeholder' 	=> esc_url( home_url( '/' ) ),
					'condition'	=> [
						'type'		=> 'cell',
					]
				]
			);

			$this->add_control(
				'rows',
				[
					'label' 	=> __( 'Rows', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'type' 		=> 'row',
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'First column', 'elementor-extras' ),
							'cell_type'		=> 'td',
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'Second column', 'elementor-extras' ),
							'cell_type'		=> 'td',
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'Third column', 'elementor-extras' ),
							'cell_type'		=> 'td',
						],
						[
							'type' 			=> 'row',
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'First column', 'elementor-extras' ),
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'Second column', 'elementor-extras' ),
						],
						[
							'type' 			=> 'cell',
							'cell_text' 	=> __( 'Third column', 'elementor-extras' ),
						],
					],
					'fields' 			=> array_values( $repeater_elements->get_controls() ),
					'title_field' 		=> 'Start {{ type }}: {{{ cell_text }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_style',
			[
				'label' 	=> __( 'Table', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'size',
				[
					'label' => __( 'Maximum Size', 'elementor-extras' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 100,
						'unit' => '%',
					],
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 1200,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-widget-container' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'center',
					'options' 		=> [
						'flex-start' 		=> [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-center',
						],
						'flex-end' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}}' => 'justify-content: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'table',
					'selector' 		=> '{{WRAPPER}} .ee-table__row,
										{{WRAPPER}} .ee-table__cell',
				]
			);

			$this->update_control( 'table_transition', array(
				'default' => 'custom',
			));

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rows_style',
			[
				'label' 	=> __( 'Rows', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'row_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-table__row',
				]
			);

			$this->add_control(
				'row_alternate',
				[
					'label'		=> __( 'Alternate', 'elementor-extras' ),
					'type'		=> Controls_Manager::SELECT,
					'default'	=> 'even',
					'options' 	=> [
						'even' 	=> __( 'Even', 'elementor-extras' ),
						'odd' 	=> __( 'Odd', 'elementor-extras' ),
					],

				]
			);

			$this->start_controls_tabs( 'tabs_row_style' );

			$this->start_controls_tab( 'tab_row_default_style', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'row_style_heading',
					[
						'label'		=> __( 'Default', 'elementor-extras' ),
						'type'		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'row_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table__row .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'row_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table__row' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'row_style_hover_heading',
					[
						'label'		=> __( 'Hover', 'elementor-extras' ),
						'type'		=> Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'row_hover_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table__row:hover .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'row_hover_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table__row:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_row_alternate_style', [ 'label' => __( 'Alternate', 'elementor-extras' ) ] );

				$this->add_control(
					'row_style_alternate_heading',
					[
						'label'		=> __( 'Default', 'elementor-extras' ),
						'type'		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'row_alternate_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table--odd .ee-table__row:nth-child(odd) .ee-table__text,
							 {{WRAPPER}} .ee-table--even .ee-table__row:nth-child(even) .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'row_alternate_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table--odd .ee-table__row:nth-child(odd),
							 {{WRAPPER}} .ee-table--even .ee-table__row:nth-child(even)' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'row_style_alternate_hover_heading',
					[
						'label'		=> __( 'Hover', 'elementor-extras' ),
						'type'		=> Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'row_alternate_hover_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table--odd .ee-table__row:nth-child(odd):hover .ee-table__text,
							 {{WRAPPER}} .ee-table--even .ee-table__row:nth-child(even):hover .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'row_alternate_hover_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table--odd .ee-table__row:nth-child(odd):hover,
							 {{WRAPPER}} .ee-table--even .ee-table__row:nth-child(even):hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cells_style',
			[
				'label' 	=> __( 'Cells', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'cell_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} td.ee-table__cell',
				]
			);

			$this->start_controls_tabs( 'tabs_cell_colors' );

			$this->start_controls_tab( 'tab_cell_colors', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'cell_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table__cell .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'cell_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-table__cell' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_cell_hover_colors', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'cell_hover_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table__cell:hover .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cell_hover_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(desktop+){{WRAPPER}} .ee-table__cell:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->start_controls_tabs( 'tabs_cell_style' );

			$this->start_controls_tab( 'tab_cell_default_style', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'cell_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'cell_align',
					[
						'label' 		=> __( 'Align Text', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default' 		=> '',
						'options' 		=> [
							'flex-start'    		=> [
								'title' 	=> __( 'Left', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-left',
							],
							'center' 		=> [
								'title' 	=> __( 'Center', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-center',
							],
							'flex-end' 		=> [
								'title' 	=> __( 'Right', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-right',
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} .ee-table__text' 	=> 'justify-content: {{VALUE}};',
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'cell_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '{{WRAPPER}} .ee-table__cell',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_cell_first_style', [ 'label' => __( 'First', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'cell_first_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-table__cell:first-child .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'cell_first_align',
					[
						'label' 		=> __( 'Align Text', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default' 		=> '',
						'options' 		=> [
							'flex-start'    		=> [
								'title' 	=> __( 'Left', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-left',
							],
							'center' 		=> [
								'title' 	=> __( 'Center', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-center',
							],
							'flex-end' 		=> [
								'title' 	=> __( 'Right', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-right',
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} .ee-table__cell:first-child .ee-table__text' => 'justify-content: {{VALUE}};',
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'cell_first_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '(tablet+){{WRAPPER}} .ee-table__cell:first-child',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_cell_last_style', [ 'label' => __( 'Last', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'cell_last_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-table__cell:last-child .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'cell_last_align',
					[
						'label' 		=> __( 'Align Text', 'elementor-extras' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default' 		=> '',
						'options' 		=> [
							'flex-start'    		=> [
								'title' 	=> __( 'Left', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-left',
							],
							'center' 		=> [
								'title' 	=> __( 'Center', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-center',
							],
							'flex-end' 		=> [
								'title' 	=> __( 'Right', 'elementor-extras' ),
								'icon' 		=> 'fa fa-align-right',
							],
						],
						'selectors'		=> [
							'{{WRAPPER}} .ee-table__cell:last-child .ee-table__text' => 'justify-content: {{VALUE}};',
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'cell_last_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=> '(tablet+){{WRAPPER}} .ee-table__cell:last-child',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label' 	=> __( 'Headers', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'mobile_headers_size',
				[
					'label' => __( 'Mobile Width (%)', 'elementor-extras' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 50,
					],
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 90,
							'step'=> 10,
						],
					],
					'condition' => [
						'mobile_headers_hide!' => 'hide',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-table__cell[data-title]:before' => 'flex-basis: {{SIZE}}%;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'header_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} th.ee-table__cell',
				]
			);

			$this->start_controls_tabs( 'tabs_header_colors' );

			$this->start_controls_tab( 'tab_header_colors', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'header_cell_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} th.ee-table__cell .ee-table__text' => 'color: {{VALUE}};',
							'{{WRAPPER}} .ee-table__cell[data-title]:before' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'header_cell_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} th.ee-table__cell' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .ee-table__cell[data-title]:before' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_header_hover_colors', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'header_cell_hover_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(tablet+){{WRAPPER}} th.ee-table__cell:hover .ee-table__text' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'header_cell_hover_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'(tablet+){{WRAPPER}} th.ee-table__cell:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->start_controls_tabs( 'tabs_header_style' );

			$this->start_controls_tab( 'tab_header_default_style', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'header_cell_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} th.ee-table__cell .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .ee-table__cell[data-title]:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'header_cell_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=>
							'{{WRAPPER}} th.ee-table__cell, {{WRAPPER}} .ee-table__cell[data-title]:before',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_header_first_style', [ 'label' => __( 'First', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'header_cell_first_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} th.ee-table__cell:first-child .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .ee-table__cell:first-child[data-title]:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'header_cell_first_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=>
							'(tablet+){{WRAPPER}} th.ee-table__cell:first-child, {{WRAPPER}} .ee-table__cell:first-child[data-title]:before',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_header_last_style', [ 'label' => __( 'Last', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'header_cell_last_padding',
					[
						'label' 		=> __( 'Padding', 'elementor-extras' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} th.ee-table__cell:last-child .ee-table__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .ee-table__cell:last-child[data-title]:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 		=> 'header_cell_last_border',
						'label' 	=> __( 'Border', 'elementor-extras' ),
						'selector' 	=>
							'(tablet+){{WRAPPER}} th.ee-table__cell:last-child, {{WRAPPER}} .ee-table__cell:last-child[data-title]:before',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_columns_style',
			[
				'label' 	=> __( 'Columns', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$repeater_columns = new Repeater();

			$repeater_columns->add_control(
				'span',
				[
					'label' 		=> __( 'Span', 'elementor-extras' ),
					'title'			=> __( 'Rule applies to this number of columns starting after the previous rule.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::NUMBER,
					'default' 		=> 1,
					'min' 			=> 1,
					'step' 			=> 1,
					'label_block' 	=> false,
				]
			);

			$repeater_columns->add_control(
				'column_background',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'(tablet+){{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
					],
				]
			);

			$repeater_columns->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'column_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '(tablet+){{WRAPPER}} {{CURRENT_ITEM}}',
				]
			);

			$repeater_columns->add_control(
				'column_size',
				[
					'label' => __( 'Width', 'elementor-extras' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 1200,
						],
					],
					'selectors' => [
						'(tablet+){{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'rules',
				[
					'label' 			=> __( 'Column Rules', 'elementor-extras' ),
					'type' 				=> Controls_Manager::REPEATER,
					'fields' 			=> array_values( $repeater_columns->get_controls() ),
					'prevent_empty'		=> true,
					'title_field' 		=> 'Column',
					'default'			=> [
						[
							'span'		=> 1
						]
					]
				]
			);

		$this->end_controls_section();
		
	}

	protected function is_invalid_first_row() {
		$settings = $this->get_settings();

		if ( 'row' === $settings['rows'][0]['type'] )
			return false;

		return true;
	}

	protected function render_rules() {
		$settings = $this->get_settings();

		if ( $settings['rules'] ) { ?>
			<colgroup>
				<?php foreach( $settings['rules'] as $rule ) { ?>
				<col span="<?php echo $rule['span']; ?>" class="elementor-repeater-item-<?php echo $rule['_id']; ?>">
				<?php } ?>
			</colgroup>
		<?php }

	}

	protected function render_header() {
		$settings = $this->get_settings();

		$counter 	= 1;
		$output 	= '';

		if ( $settings['header_cells'] ) {

			$this->add_render_attribute( 'row', 'class', 'ee-table__row' );

			?>

			<thead>
				<tr <?php echo $this->get_render_attribute_string( 'row' ); ?>>

				<?php foreach ( $settings['header_cells'] as $index => $row ) {

					$header_text_key = $this->get_repeater_setting_key( 'cell_text', 'header_cells', $index );
					
					$this->add_render_attribute( 'header-' . $counter, 'class', 'ee-table__cell' );
					$this->add_render_attribute( 'header-' . $counter, 'class', 'elementor-repeater-item-' . $row['_id'] );

					$this->add_render_attribute( 'header-text-' . $counter, 'class', 'ee-table__text' );
					$this->add_render_attribute( $header_text_key, 'class', 'ee-table__text-inner' );
					$this->add_inline_editing_attributes( $header_text_key, 'basic' );

					if ( $row['_item_id'] )
						$this->add_render_attribute( 'header-' . $counter, 'id', $row['_item_id'] );

					if ( $row['css_classes'] )
						$this->add_render_attribute( 'header-' . $counter, 'class', $row['css_classes'] );

					if ( $row['cell_span'] > 1 )
						$this->add_render_attribute( 'header-' . $counter, 'colspan', $row['cell_span'] );

					if ( $row['cell_row_span'] > 1 )
						$this->add_render_attribute( 'header-' . $counter, 'rowspan', $row['cell_row_span'] );

					// Output header contents
					$output .= '<th ' . $this->get_render_attribute_string( 'header-' . $counter ) . '>';
					$output .= '<span ' . $this->get_render_attribute_string( 'header-text-' . $counter ) . '>';

					if ( 'text' === $row['cell_content'] && '' !== $row['cell_icon'] ) {

						$this->add_render_attribute( 'icon-' . $counter, 'class', 'ee-align-icon--' . $row['cell_icon_align'] );

						$output .= '<span ' . $this->get_render_attribute_string( 'icon-' . $counter ) . '>';
							$output .= '<i class="' . esc_attr( $row['cell_icon'] ) . '"></i>';
						$output .= '</span>';
					}

					$output .= '<span ' . $this->get_render_attribute_string( $header_text_key ) . '>' . $row['cell_text'] . '</span>';

					if ( 'yes' === $settings['sortable'] ) {
						$output .= '<span class="nicon nicon-sort-up-down"></span>';
						$output .= '<span class="nicon nicon-sort-up"></span>';
						$output .= '<span class="nicon nicon-sort-down"></span>';
					}

					$output .= '</span>';
					$output .= '</th>';

				}

			echo $output; ?>

				</tr>

			</thead>

		<?php }
	}

	protected function render() {
		$settings = $this->get_settings();

		$counter 		= 1;
		$cell_counter 	= 0;
		$output 		= '';
		$row_count 		= count( $settings['rows'] );

		$this->add_render_attribute( 'table', 'class', 'ee-table' );
		$this->add_render_attribute( 'row', 'class', 'ee-table__row' );
		$this->add_render_attribute( 'table', 'class', 'ee-table--' . $settings['row_alternate'] );

		if ( $settings['sortable'] ) {
			$this->add_render_attribute( 'table', 'class', 'ee-table--sortable' );
		}

		if ( $settings['rules'] ) {
			$this->add_render_attribute( 'table', 'class', 'ee-table--rules' );
		}

		?>
		
		<table <?php echo $this->get_render_attribute_string( 'table' ); ?>>

			<?php $this->render_rules(); ?>

			<?php $this->render_header(); ?>

			<?php if ( $settings['rows'] ) { ?>

				<tbody>

					<?php if ( $this->is_invalid_first_row() ) { ?>
						<tr <?php echo $this->get_render_attribute_string( 'row' ); ?>>
					<?php } ?>

					<?php foreach ( $settings['rows'] as $index => $row ) {

						$text_tag 		= 'span';
						$header_text 	= $row['cell_header'];
						$cell_text_key = $this->get_repeater_setting_key( 'cell_text', 'rows', $index );

						if ( ! empty( $row['link']['url'] ) ) {

							$text_tag = 'a';

							$this->add_render_attribute( 'text-' . $counter, 'href', $row['link']['url'] );

							if ( $row['link']['is_external'] ) {
								$this->add_render_attribute( 'text-' . $counter, 'target', '_blank' );
							}

							if ( ! empty( $row['link']['nofollow'] ) ) {
								$this->add_render_attribute( 'text-' . $counter, 'rel', 'nofollow' );
							}
						}
						
						if ( $row['type'] === 'cell' ) {

							if ( 'hide' !== $settings['mobile_headers_hide'] ) {
								if ( 'yes' === $settings['mobile_headers_auto'] ) {

									// Fetch corresponding header cell text
									if ( isset( $settings['header_cells'][ $cell_counter ] ) && '' === $row['cell_header'] ) {
										$header_text = $settings['header_cells'][ $cell_counter ]['cell_text'];
									}

									// Increment to next cell
									$cell_counter++;
								}
							}

							$this->add_render_attribute( 'cell-' . $counter, 'class', 'ee-table__cell' );
							$this->add_render_attribute( 'cell-' . $counter, 'class', 'elementor-repeater-item-' . $row['_id'] );

							$this->add_render_attribute( 'text-' . $counter, 'class', 'ee-table__text' );
							$this->add_render_attribute( $cell_text_key, 'class', 'ee-table__text-inner' );
							$this->add_inline_editing_attributes( $cell_text_key, 'basic' );

							if ( $row['_item_id'] )
								$this->add_render_attribute( 'cell-' . $counter, 'id', $row['_item_id'] );

							if ( $row['css_classes'] )
								$this->add_render_attribute( 'cell-' . $counter, 'class', $row['css_classes'] );

							if ( $header_text )
								$this->add_render_attribute( 'cell-' . $counter, 'data-title', $header_text );

							if ( $row['cell_span'] > 1 )
								$this->add_render_attribute( 'cell-' . $counter, 'colspan', $row['cell_span'] );

							if ( $row['cell_row_span'] > 1 )
								$this->add_render_attribute( 'cell-' . $counter, 'rowspan', $row['cell_row_span'] );

							// Output cell contents
							$output .= '<' . $row['cell_type'] . ' ' . $this->get_render_attribute_string( 'cell-' . $counter ) . '>';
							$output .= '<' . $text_tag . ' ' . $this->get_render_attribute_string( 'text-' . $counter ) . '>';

							if ( 'text' === $row['cell_content'] && '' !== $row['cell_icon'] ) {

								$this->add_render_attribute( 'icon-' . $counter, 'class', 'ee-align-icon--' . $row['cell_icon_align'] );

								$output .= '<span ' . $this->get_render_attribute_string( 'icon-' . $counter ) . '>';
									$output .= '<i class="' . esc_attr( $row['cell_icon'] ) . '"></i>';
								$output .= '</span>';
							}

							$output .= '<span ' . $this->get_render_attribute_string( $cell_text_key ) . '>' . $row['cell_text'] . '</span>';
							$output .= '</' . $text_tag . '>';
							$output .= '</' . $row['cell_type'] . '>';

						} else {

							$this->add_render_attribute( 'row-' . $counter, 'class', 'ee-table__row' );
							$this->add_render_attribute( 'row-' . $counter, 'class', 'elementor-repeater-item-' . $row['_id'] );

							if ( $row['_item_id'] )
								$this->add_render_attribute( 'row-' . $counter, 'id', $row['_item_id'] );

							if ( $row['css_classes'] )
								$this->add_render_attribute( 'row-' . $counter, 'class', $row['css_classes'] );

							if ( $counter > 1 && $counter < $row_count ) {

								// Break into new row
								$output .= '</tr><tr ' . $this->get_render_attribute_string( 'row-' . $counter ) . '>';

							} else if ( $counter === 1 && false === $this->is_invalid_first_row() ) {
								$output .= '<tr ' . $this->get_render_attribute_string( 'row-' . $counter ) . '>';
							}

							$cell_counter = 0;
						}

						$counter++;

					}

					echo $output; ?>
					</tr>

				</tbody>

			<?php } ?>

		</table>

		<?php
	}

	protected function _rules_template() {
		?>
		
		<# if ( settings.rules ) { #>
			<colgroup>
				<# _.each( settings.rules, function( rule ) { #>
					<col span="{{ rule.span }}" class="elementor-repeater-item-{{ rule._id }}">
				<# }); #>
			</colgroup>
		<# } #>

		<?php
	}

	protected function _header_template() {
		?>

		<# var counter = 1;

		if ( settings.header_cells ) { #>

		<thead>

			<tr class="ee-table__row">

			<# _.each( settings.header_cells, function( row ) { #>

				<th id="{{ row._item_id }}" class="ee-table__cell elementor-repeater-item-{{ row._id }} {{ row.css_classes }}" rowspan="{{ row.cell_row_span }}" colspan="{{ row.cell_span }}"">
					<span class="ee-table__text">

						<# if ( 'text' === row.cell_content && '' !== row.cell_icon ) { #>
						<span class="ee-align-icon--{{ row.cell_icon_align }}">
							<i class="{{ row.cell_icon }}"></i>
						</span>
						<# } #>

						<span class="ee-table__text-inner elementor-inline-editing" data-elementor-setting-key="header_cells.{{ counter - 1 }}.cell_text" data-elementor-inline-editing-toolbar="basic">{{{ row.cell_text }}}</span>

						<# if ( 'yes' === settings.sortable ) { #>
							<span class="nicon nicon-sort-up-down"></span>
							<span class="nicon nicon-sort-up"></span>
							<span class="nicon nicon-sort-down"></span>
						<# } #>

					</span>
				</th>

			<# counter++;

			}); counter = 1; #>

			</tr>

		</thead>

		<# } #>

		<?php
	}

	protected function _content_template() {
		?>

		<#

		var counter 				= 1,
			cell_counter 			= 0,
			row_count 				= settings.rows.length,
			is_invalid_first_row 	= false,
			table_classes 			= '';

		if ( '' !== settings.sortable ) {
			table_classes += 'ee-table--sortable';
		}

		if ( 'row' !== settings.rows[0].type ) {
			is_invalid_first_row = true;
		}

		if ( settings.rules ) {
			table_classes += ' ee-table--rules';
		} #>

		<table class="ee-table ee-table--{{ settings.row_alternate }} {{ table_classes }} ">

		<?php $this->_rules_template(); ?>

		<?php $this->_header_template(); ?>

		<# if ( settings.rows ) { #>

			<tbody>

				<# if ( is_invalid_first_row ) { #>
				<tr class="ee-table__row">
				<# }

				_.each( settings.rows, function( row ) {

					var text_tag 	= 'span',
						text_link 	= '',
						data_header_text = '',
						header_text = row.cell_header;

					if ( '' !== row.link.url ) {
						text_tag = 'a';
						text_link = 'href="' + row.link.url + '"';
					}

					if ( row.type === 'cell' ) {

						if ( 'hide' !== settings.mobile_headers_hide ) {
							if ( 'yes' === settings.mobile_headers_auto ) {

								if ( undefined !== settings.header_cells[ cell_counter ] && '' === row.cell_header ) {
									header_text = settings.header_cells[ cell_counter ].cell_text;
								}

								cell_counter++;
							}
						}

						if ( '' !== header_text ) {
							data_header_text = 'data-title="' + header_text + '"';
						}
					#>

						<{{ row.cell_type }} id="{{ row._item_id }}" class="ee-table__cell elementor-repeater-item-{{ row._id }} {{ row.css_classes }}" rowspan="{{ row.cell_row_span }}" colspan="{{ row.cell_span }}" {{{ data_header_text }}}>

							<{{ text_tag }} {{ text_link }} class="ee-table__text">
								<# if ( 'text' === row.cell_content && '' !== row.cell_icon ) { #>
								<span class="ee-align-icon--{{ row.cell_icon_align }}">
									<i class="{{ row.cell_icon }}"></i>
								</span>
								<# } #>
								<span class="ee-table__text-inner elementor-inline-editing" data-elementor-setting-key="rows.{{ counter - 1 }}.cell_text" data-elementor-inline-editing-toolbar="basic">{{{ row.cell_text }}}</span>
							</{{ text_tag }}>

						</{{ row.cell_type }}>

					<# } else {

						if ( counter > 1 && counter < row_count ) { #>

							</tr><tr class="ee-table__row elementor-repeater-item-{{ row._id }} {{ row.css_classes }}" id="{{ row._item_id }}">

						<# } else if ( 1 === counter && ! is_invalid_first_row ) { #>
							<tr class="ee-table__row elementor-repeater-item-{{ row._id }} {{ row.css_classes }}" id="{{ row._item_id }}">
						<# }

						cell_counter = 0;
					}

				counter++;

				}); #>

				</tr>

			</tbody>

		<# } #>

		</table>

		<?php
	}
}
