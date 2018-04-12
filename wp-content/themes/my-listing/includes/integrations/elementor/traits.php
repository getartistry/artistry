<?php

class CASE27_Elementor_Traits {

	public $widget;

	public function __construct($widget)
	{
		$this->widget = $widget;
	}

	public function header()
	{
		$this->widget->start_controls_section(
			'the_header_controls',
			['label' => esc_html__( 'Section Header', 'my-listing' ),]
		);

		$this->widget->add_control(
			'the_title',
			[
				'label' => __( 'Title', 'my-listing' ),
				'type' => Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->widget->add_control(
			'the_description',
			[
				'label' => __( 'Description', 'my-listing' ),
				'type' => Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->widget->end_controls_section();
	}

	public function content()
	{
		$this->widget->start_controls_section(
			'the_content_controls',
			['label' => esc_html__( 'Section Content', 'my-listing' ),]
		);

		$this->widget->add_control(
			'the_content',
			[
				'label' => __( 'Content', 'my-listing' ),
				'type' => Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
			]
		);

		$this->widget->end_controls_section();
	}

	public function footer()
	{
		$this->widget->start_controls_section(
			'the_footer_controls',
			['label' => esc_html__( 'Section Footer', 'my-listing' ),]
		);
		$this->widget->add_control(
			'the_footer_content',
			[
				'label' => __( 'Footer Content', 'my-listing' ),
				'type' => Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
			]
		);

		$this->widget->end_controls_section();
	}

	public function sizing($selector = '.i-section', $default = 65)
	{
		// $this->widget->start_controls_section(
		// 	'the_sizing_controls',
		// 	['label' => esc_html__( 'Section Sizing', 'my-listing' ),]
		// );

		// $this->widget->add_control(
		// 	'section_inner_spacing',
		// 	[
		// 	   	'label'   => __( 'Inner Padding', 'my-listing' ),
		// 	   	'type'    => Elementor\Controls_Manager::DIMENSIONS,
		// 	   	'allowed_dimensions' => ['top', 'bottom'],
		// 	   	'default' => ['top' => $default, 'bottom' => $default],
		// 	   	'selectors' => [
  		//	        '{{WRAPPER}} ' . $selector => 'padding: {{TOP}}px 0 {{BOTTOM}}px',
  		//   	 ],
		// 	]
		// );

		// $this->widget->end_controls_section();
	}

	public function block_styles()
	{
		$this->widget->start_controls_section(
			'section_content_styling_block',
			[
				'label' => esc_html__( 'Styling', 'my-listing' ),
			]
		);

		$this->widget->add_control(
			'heading_block_heading_styles',
			[
			'label' => __( 'Block Heading', 'my-listing' ),
			'type' => Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'the_icon_style',
			[
			'label' => __( 'Icon Style', 'my-listing' ),
			'type' => Elementor\Controls_Manager::SELECT2,
			'options' => [
				1 => __( 'Default', 'my-listing' ),
				2 => __( 'Circular', 'my-listing' ),
				3 => __( 'No Icon', 'my-listing' ),
			],
			'default' => 1,
			]
		);

		$this->widget->add_control(
			'the_icon_color',
			[
			'label' => __( 'Icon Color', 'my-listing' ),
			'type' => Elementor\Controls_Manager::COLOR,
			'default' => '#c7cdcf',
			'selectors' => [
				'{{WRAPPER}} .title-style-1 i' => 'color: {{VALUE}}',
			],
			]
		);

		$this->widget->add_control(
			'the_icon_background',
			[
			'label' => __( 'Icon Background', 'my-listing' ),
			'type' => Elementor\Controls_Manager::COLOR,
			'default' => '#f4f4f4',
			'selectors' => [
				'{{WRAPPER}} .title-style-2 i' => 'background: {{VALUE}}',
			],
			'condition' => [
				'the_icon_style' => '2',
			],
			]
		);

		$this->widget->add_control(
			'the_title_color',
			[
			'label' => __( 'Title Color', 'my-listing' ),
			'type' => Elementor\Controls_Manager::COLOR,
			'default' => '#242429',
			'selectors' => [
				'{{WRAPPER}} .title-style-1 h5' => 'color: {{VALUE}}',
			],
			]
		);

		$this->widget->add_control(
			'heading_block_styles',
			[
			'label' => __( 'Block Styles', 'my-listing' ),
			'type' => Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'the_block_background',
			[
			'label' => __( 'Block Background', 'my-listing' ),
			'type' => Elementor\Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .element' => 'background: {{VALUE}}',
			],
			]
		);

		$this->widget->add_control(
			'the_border_style',
			[
			'label' => __( 'Border Style', 'my-listing' ),
			'type' => Elementor\Controls_Manager::SELECT2,
			'options' => [
				'solid' => __( 'Solid', 'my-listing' ),
				'none' => __( 'None', 'my-listing' ),
			],
			'selectors' => [
				'{{WRAPPER}} .element' => 'border-style: {{VALUE}}',
			],
			'default' => 'solid',
			]
		);

		$this->widget->add_control(
			'the_border_color',
			[
			'label' => __( 'Border Color', 'my-listing' ),
			'type' => Elementor\Controls_Manager::COLOR,
			'default' => '#e5e6e9 #dfe0e4 #d0d1d5',
			'selectors' => [
				'{{WRAPPER}} .element' => 'border-color: {{VALUE}}',
			],
			'condition' => [
				'the_border_style' => 'solid',
			],
			]
		);

		$this->widget->end_controls_section();
	}

	public function map_controls()
	{
		$this->widget->start_controls_section(
			'the_map_controls',
			['label' => esc_html__( 'Map Options', 'my-listing' ),]
		);

		$this->widget->add_control(
			'the_skin',
			[
				'label' => __( 'Map Skin', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'default' => 'skin',
				'options' => c27()->get_map_skins(),
			]
		);

		$this->widget->add_control(
			'the_zoom',
			[
				'label' => __( 'Zoom Level', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
			]
		);

		$this->widget->add_control(
			'height',
			[
				'label' => __( 'Height', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SLIDER,
		        'default' => ['size' => 500, 'unit' => 'px'],
		        'size_units' => [ 'px', 'vh' ],
				'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 2500,
		                'step' => 1,
		            ],
		            'vh' => [
		                'min' => 0,
		                'max' => 200,
		            ],
				],
				'selectors' => [
					'{{WRAPPER}} .contact-map' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .featured-section-type-map' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->widget->add_control(
			'the_cluster_markers',
			[
				'label' => __( 'Cluster Markers?', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SWITCHER,
				'default' => true,
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'return_value' => true,
			]
		);

		$this->widget->end_controls_section();

		$this->widget->start_controls_section(
			'the_map_locations_controls',
			['label' => esc_html__( 'Locations', 'my-listing' ),]
		);

		$this->widget->add_control(
			'the_map_items',
			[
				'label' => __( 'Map items', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'default' => 'custom-locations',
				'options' => [
					'custom-locations' => __( 'Custom Locations', 'my-listing' ),
					'listings' => __( 'Listings', 'my-listing' ),
				],
			]
		);

		$this->widget->add_control(
			'the_locations',
			[
				'label' => __( 'Locations', 'my-listing' ),
				'type' => Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'marker_lat',
						'label' => __( 'Latitude', 'my-listing' ),
						'placeholder' => '41.376',
						'type' => Elementor\Controls_Manager::NUMBER,
					],
					[
						'name' => 'marker_lng',
						'label' => __( 'Longitude', 'my-listing' ),
						'placeholder' => '2.114639',
						'type' => Elementor\Controls_Manager::NUMBER,
					],
					[
						'name' => 'marker_image',
						'label' => __( 'Marker Image', 'my-listing' ),
						'type' => Elementor\Controls_Manager::MEDIA,
					],
				],
				'title_field' => '{{{ marker_lat }}}, {{{ marker_lng }}}',
				'condition' => ['the_map_items' => 'custom-locations'],
			]
		);

		$this->widget->add_control(
			'27_listings_lat',
			[
				'label' => __( 'Latitude', 'my-listing' ),
				'placeholder' => '41.376',
				'type' => Elementor\Controls_Manager::NUMBER,
				'condition' => ['the_map_items' => 'listings'],
			]
		);

		$this->widget->add_control(
			'27_listings_lng',
			[
				'label' => __( 'Longitude', 'my-listing' ),
				'placeholder' => '2.114639',
				'type' => Elementor\Controls_Manager::NUMBER,
				'condition' => ['the_map_items' => 'listings'],
			]
		);

		$this->widget->add_control(
			'27_listings_radius',
			[
				'label' => __( 'Radius', 'my-listing' ),
				'default' => 250,
				'type' => Elementor\Controls_Manager::NUMBER,
				'condition' => ['the_map_items' => 'listings'],
			]
		);

		$this->widget->add_control(
			'27_listings_type',
			[
			'label' => __( 'Listing Type', 'my-listing' ),
			'type' => Elementor\Controls_Manager::SELECT2,
			'options' => c27()->get_posts_dropdown_array([
				'post_type' => 'case27_listing_type',
				'posts_per_page' => -1,
				], 'post_name'),
			'condition' => ['the_map_items' => 'listings'],
			]
		);

		$this->widget->add_control(
			'27_listings_count',
			[
				'label' => __( 'Count', 'my-listing' ),
				'description' => __( 'How many listings to show?', 'my-listing' ),
				'default' => c27()->get_setting( 'general_explore_listings_per_page', 9 ),
				'type' => Elementor\Controls_Manager::NUMBER,
				'condition' => ['the_map_items' => 'listings'],
			]
		);

		$this->widget->end_controls_section();
	}

	public function choose_overlay($control_label = '', $control_key = '27_overlay')
	{
		if ( ! $control_label ) {
			$control_label = __( 'Set an overlay', 'my-listing' );
		}

		$this->widget->add_control(
			$control_key,
			[
				'label' => __( 'Overlay type', 'my-listing' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => [
					'gradient' => __( 'Gradient', 'my-listing' ),
					'solid_color' => __( 'Solid Color', 'my-listing' ),
				],
			]
		);

		$gradients_html = '';
		foreach (c27()->get_gradients() as $gradient_name => $gradient) {
			$gradients_html .= "<div style=\"background: -webkit-linear-gradient(180deg, {$gradient['from']} 0%, {$gradient['to']} 100%);";
			$gradients_html .= "width: 33.33333%; height: 80px; display: inline-block; color: #fff;\">{$gradient_name}</div>";
		}

		$this->widget->add_control(
			$control_key . '__gradient_types',
			[
				'type'    => Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'Gradient Types ', 'my-listing' ) . "<br><br>" . $gradients_html,
				'content_classes' => 'your-class',
				'condition' => [$control_key => 'gradient'],
			]
		);

		$this->widget->add_control(
			$control_key . '__gradient',
			[
				'label' => $control_label,
				'type' => Elementor\Controls_Manager::SELECT2,
				'options' => array_combine(array_keys(c27()->get_gradients()), array_keys(c27()->get_gradients())),
				'condition' => [$control_key => 'gradient'],
			]
		);

		$this->widget->add_control(
			$control_key . '__solid_color',
			[
				'label' => $control_label,
				'type' => Elementor\Controls_Manager::COLOR,
				'condition' => [$control_key => 'solid_color'],
			]
		);
	}

	public function choose_columns( $control_label = '', $control_key = '27_column_count', $options = [] )
	{
		if (!$control_label) {
			$control_label = __( 'Column size', 'my-listing' );
		}

		$options = c27()->merge_options([
			'heading' => [
				'label' => $control_label,
				'type' => Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			],
			'general' => [
				'type' => Elementor\Controls_Manager::NUMBER,
				'default' => 3,
				'separator' => 'none',
			],
			'lg' => [], 'md' => [],
			'sm' => [], 'xs' => [],
			], $options);

		$breakpoints = [
			'lg' => __( 'Desktop', 'my-listing' ),
			'md' => __( 'Laptop', 'my-listing' ),
			'sm' => __( 'Tablet', 'my-listing' ),
			'xs' => __( 'Mobile', 'my-listing' ),
		];

		$this->widget->add_control(
			'more_options',
			$options['heading']
		);

		foreach ($breakpoints as $breakpoint => $label) {
			$this->widget->add_control(
				"{$control_key}__{$breakpoint}",
				array_merge([
					'label' => $label
				], $options['general'], $options[$breakpoint])
			);
		}
	}
}