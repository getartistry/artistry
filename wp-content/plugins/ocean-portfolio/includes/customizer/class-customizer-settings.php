<?php
/**
 * Customizer Settings
 */

if ( ! class_exists( 'OceanWP_Portfolio_Customizer' ) ) {

	class OceanWP_Portfolio_Customizer {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
			add_action( 'customize_register', array( $this, 'customizer_options' ) );
			add_filter( 'ocean_head_css', array( $this, 'head_css' ) );
		}

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 *
		 * @since 1.0.0
		 */
		public static function customize_preview_js() {
			wp_enqueue_script( 'op_portfolio-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.0', true );
			wp_localize_script( 'op_portfolio-customizer', 'op_portfolio', array(
				'googleFontsUrl' 	=> '//fonts.googleapis.com',
				'googleFontsWeight' => '100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i',
			) );
		}

		/**
		 * Customizer options
		 *
		 * @since 1.0.0
		 */
		public static function customizer_options( $wp_customize ) {

			// Helpers functions
			require_once( OP_PATH .'includes/customizer/customizer-helpers.php' );
			require_once( OP_PATH .'/includes/admin/sanitize.php' );

			/**
			 * Panel
			 */
			$panel = 'op_portfolio';
			$wp_customize->add_panel( $panel , array(
				'title' 			=> esc_html__( 'Portfolio', 'ocean-portfolio' ),
				'priority' 			=> 210,
			) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_general', array(
				'title' 			=> esc_html__( 'General', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Portfolio Page
			 */
			$wp_customize->add_setting( 'op_portfolio_page', array(
				'default' 				=> '',
				'sanitize_callback' 	=> 'oceanwp_sanitize_dropdown_pages',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Dropdown_Pages( $wp_customize, 'op_portfolio_page', array(
				'label'	   				=> esc_html__( 'Portfolio Page', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'Select your portfolio page for the breadcrumb', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_page',
				'priority' 				=> 10,
			) ) );

			/**
			 * Portfolio Slug
			 */
			$wp_customize->add_setting( 'op_portfolio_slug', array(
				'transport' 			=> 'postMessage',
				'default'           	=> 'portfolio',
				'sanitize_callback' 	=> 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_slug', array(
				'label'	   				=> esc_html__( 'Portfolio Item Slug', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'You need to update your permalinks if you edit this field', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_slug',
				'priority' 				=> 10,
			) ) );

			/**
			 * Portfolio Categories Slug
			 */
			$wp_customize->add_setting( 'op_portfolio_category_slug', array(
				'transport' 			=> 'postMessage',
				'default'           	=> 'portfolio-category',
				'sanitize_callback' 	=> 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_category_slug', array(
				'label'	   				=> esc_html__( 'Portfolio Categories Slug', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'You need to update your permalinks if you edit this field', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_category_slug',
				'priority' 				=> 10,
			) ) );

			/**
			 * Portfolio Tags Slug
			 */
			$wp_customize->add_setting( 'op_portfolio_tag_slug', array(
				'transport' 			=> 'postMessage',
				'default'           	=> 'portfolio-tag',
				'sanitize_callback' 	=> 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_tag_slug', array(
				'label'	   				=> esc_html__( 'Portfolio Tags Slug', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'You need to update your permalinks if you edit this field', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_tag_slug',
				'priority' 				=> 10,
			) ) );

			/**
			 * Posts Per Page
			 */
			$wp_customize->add_setting( 'op_portfolio_posts_per_page', array(
				'default'           	=> '12',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_intval',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_posts_per_page', array(
				'label'	   				=> esc_html__( 'Posts Per Page', 'ocean-portfolio' ),
		        'description'   		=> esc_html__( 'Put -1 to display all portfolio items. Default is 12.', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_posts_per_page',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'step'   => 1,
			    ),
			) ) );

			/**
			 * Columns
			 */
			$wp_customize->add_setting( 'op_portfolio_columns', array(
				'default'           	=> '3',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_columns', array(
				'label'	   				=> esc_html__( 'Columns', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_columns',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'max'   => 10,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Masonry
			 */
			$wp_customize->add_setting( 'op_portfolio_masonry', array(
				'default'           	=> 'off',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_masonry', array(
				'label'	   				=> esc_html__( 'Masonry', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_masonry',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Title/Category Position
			 */
			$wp_customize->add_setting( 'op_portfolio_title_cat_position', array(
				'default'           	=> 'outside',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_title_cat_position', array(
				'label'	   				=> esc_html__( 'Title/Category Position', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_title_cat_position',
				'priority' 				=> 10,
				'choices' 				=> array(
					'inside' 	=> esc_html__( 'Inside', 'ocean-portfolio' ),
					'outside' 	=> esc_html__( 'Outside', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Display Title
			 */
			$wp_customize->add_setting( 'op_portfolio_title', array(
				'default'           	=> 'on',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_title', array(
				'label'	   				=> esc_html__( 'Display Title', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_title',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Title HTML Tag
			 */
			$wp_customize->add_setting( 'op_portfolio_title_tag', array(
				'default' 				=> 'h3',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_title_tag', array(
				'label'	   				=> esc_html__( 'Title HTML Tag', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_title_tag',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_title',
				'choices' 				=> array(
					'h1' 		=> esc_html__( 'H1', 'ocean-portfolio' ),
					'h2' 		=> esc_html__( 'H2', 'ocean-portfolio' ),
					'h3' 		=> esc_html__( 'H3', 'ocean-portfolio' ),
					'h4' 		=> esc_html__( 'H4', 'ocean-portfolio' ),
					'h5' 		=> esc_html__( 'H5', 'ocean-portfolio' ),
					'h6' 		=> esc_html__( 'H6', 'ocean-portfolio' ),
					'div' 		=> esc_html__( 'div', 'ocean-portfolio' ),
					'span' 		=> esc_html__( 'span', 'ocean-portfolio' ),
					'p' 		=> esc_html__( 'p', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Display Category
			 */
			$wp_customize->add_setting( 'op_portfolio_category', array(
				'default'           	=> 'on',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_category', array(
				'label'	   				=> esc_html__( 'Display Category', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_category',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Display Pagination
			 */
			$wp_customize->add_setting( 'op_portfolio_pagination', array(
				'default'           	=> 'off',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_pagination', array(
				'label'	   				=> esc_html__( 'Display Pagination', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_pagination',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Pagination Position
			 */
			$wp_customize->add_setting( 'op_portfolio_pagination_position', array(
				'default'           	=> 'center',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_pagination_position', array(
				'label'	   				=> esc_html__( 'Pagination Position', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_general',
				'settings' 				=> 'op_portfolio_pagination_position',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_pagination',
				'choices' 				=> array(
					'left' 		=> esc_html__( 'Left', 'ocean-portfolio' ),
					'center' 	=> esc_html__( 'Center', 'ocean-portfolio' ),
					'right' 	=> esc_html__( 'Right', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_filter_bar', array(
				'title' 			=> esc_html__( 'Filter Bar', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Display Filter
			 */
			$wp_customize->add_setting( 'op_portfolio_filter', array(
				'default'           	=> 'off',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_filter', array(
				'label'	   				=> esc_html__( 'Display Filter', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Display Link All
			 */
			$wp_customize->add_setting( 'op_portfolio_all_filter', array(
				'default'           	=> 'on',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_all_filter', array(
				'label'	   				=> esc_html__( 'Display Link All', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_all_filter',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Filter Position
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_position', array(
				'transport' 			=> 'postMessage',
				'default'           	=> 'center',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_filter_position', array(
				'label'	   				=> esc_html__( 'Filter Position', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_position',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
				'choices' 				=> array(
					'full' 		=> esc_html__( 'Full', 'ocean-portfolio' ),
					'left' 		=> esc_html__( 'Left', 'ocean-portfolio' ),
					'center' 	=> esc_html__( 'Center', 'ocean-portfolio' ),
					'right' 	=> esc_html__( 'Right', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Filter Taxonomy
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_taxonomy', array(
				'default'           	=> 'categories',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_filter_taxonomy', array(
				'label'	   				=> esc_html__( 'Taxonomy', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_taxonomy',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
				'choices' 				=> array(
					'categories' 	=> esc_html__( 'Categories', 'ocean-portfolio' ),
					'tags' 			=> esc_html__( 'Tags', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Responsive Filter Links
			 */
			$wp_customize->add_setting( 'op_portfolio_responsive_filter_links', array(
				'default' 				=> '480',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_responsive_filter_links', array(
				'label'	   				=> esc_html__( 'Responsive Filter Links', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'Choose the media query where you want the filter bar links to be full width.', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_responsive_filter_links',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
				'choices' 				=> array(
					'1280' 		=> esc_html__( 'From 1280px', 'ocean-portfolio' ),
					'1080' 		=> esc_html__( 'From 1080px', 'ocean-portfolio' ),
					'959' 		=> esc_html__( 'From 959px', 'ocean-portfolio' ),
					'767' 		=> esc_html__( 'From 767px', 'ocean-portfolio' ),
					'480' 		=> esc_html__( 'From 480px', 'ocean-portfolio' ),
					'320' 		=> esc_html__( 'From 320px', 'ocean-portfolio' ),
					'custom' 	=> esc_html__( 'Custom media query', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Custom Media Query
			 */
			$wp_customize->add_setting( 'op_portfolio_responsive_filter_links_custom', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_responsive_filter_links_custom', array(
				'label'	   				=> esc_html__( 'Custom Media Query', 'ocean-portfolio' ),
				'description'	   		=> esc_html__( 'Enter your custom media query where you want the filter bar links to be full width.', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_responsive_filter_links_custom',
				'active_callback' 		=> 'op_portfolio_cac_has_custom_responsive_filter_links',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Filter Bar Margin
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_margin', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_filter_margin', array(
				'label'	   				=> esc_html__( 'Filter Bar Margin', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_margin',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Links: Margin
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_links_margin', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_filter_links_margin', array(
				'label'	   				=> esc_html__( 'Filter Links: Margin', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_links_margin',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Links: Padding
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_links_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_filter_links_padding', array(
				'label'	   				=> esc_html__( 'Filter Links: Padding', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_links_padding',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Links: Background Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_links_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#f6f6f6',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_links_bg', array(
				'label'	   				=> esc_html__( 'Filter Links: Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_links_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Links: Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_links_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#444444',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_links_color', array(
				'label'	   				=> esc_html__( 'Filter Links: Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_links_color',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Active Link: Background Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_active_link_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#13aff0',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_active_link_bg', array(
				'label'	   				=> esc_html__( 'Filter Active Link: Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_active_link_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Active Link: Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_active_link_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#ffffff',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_active_link_color', array(
				'label'	   				=> esc_html__( 'Filter Active Link: Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_active_link_color',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Hover Links: Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_hover_links_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#13aff0',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_hover_links_bg', array(
				'label'	   				=> esc_html__( 'Filter Hover Links: Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_hover_links_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Hover Links: Color
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_hover_links_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#ffffff',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_filter_hover_links_color', array(
				'label'	   				=> esc_html__( 'Filter Hover Links: Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_filter_bar',
				'settings' 				=> 'op_portfolio_filter_hover_links_color',
				'active_callback' 		=> 'op_portfolio_cac_has_filter',
				'priority' 				=> 10,
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_images', array(
				'title' 			=> esc_html__( 'Images', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Image Size
			 */
			$wp_customize->add_setting( 'op_portfolio_size', array(
				'default'           	=> 'medium',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_size', array(
				'label'	   				=> esc_html__( 'Image Size', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_size',
				'priority' 				=> 10,
				'choices' 				=> op_portfolio_helpers( 'img_sizes' ),
			) ) );

			/**
			 * Image Width (px)
			 */
			$wp_customize->add_setting( 'op_portfolio_img_width', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_img_width', array(
				'label'	   				=> esc_html__( 'Image Width (px)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_width',
				'active_callback' 		=> 'op_portfolio_cac_has_image_custom_size',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Image Height (px)
			 */
			$wp_customize->add_setting( 'op_portfolio_img_height', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_img_height', array(
				'label'	   				=> esc_html__( 'Image Height (px)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_height',
				'active_callback' 		=> 'op_portfolio_cac_has_image_custom_size',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Overlay Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'rgba(0,0,0,0.4)',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_color', array(
				'label'	   				=> esc_html__( 'Overlay Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_color',
				'priority' 				=> 10,
			) ) );

			/**
			 * Display Overlay Icons
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons', array(
				'default'           	=> 'on',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'op_portfolio_img_overlay_icons', array(
				'label'	   				=> esc_html__( 'Display Overlay Icons', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons',
				'priority' 				=> 10,
				'choices' 				=> array(
					'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
					'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Overlay Icons Width
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_width', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '45',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_img_overlay_icons_width', array(
				'label'	   				=> esc_html__( 'Overlay Icons Width', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_width',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Overlay Icons Height
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_height', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '45',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_img_overlay_icons_height', array(
				'label'	   				=> esc_html__( 'Overlay Icons Height', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_height',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Overlay Icons Size
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_size', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '16',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_img_overlay_icons_size', array(
				'label'	   				=> esc_html__( 'Overlay Icons Size', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_size',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Overlay Icons Background Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'rgba(255,255,255,0.2)',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_bg', array(
				'label'	   				=> esc_html__( 'Overlay Icons Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Hover Background Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_hover_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'rgba(255,255,255,0.4)',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_hover_bg', array(
				'label'	   				=> esc_html__( 'Overlay Icons Hover Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_hover_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#ffffff',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_color', array(
				'label'	   				=> esc_html__( 'Overlay Icons Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_color',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Hover Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_hover_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#ffffff',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_hover_color', array(
				'label'	   				=> esc_html__( 'Overlay Icons Hover Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_hover_color',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Border Radius
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_border_radius', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_img_overlay_icons_border_radius', array(
				'label'	   				=> esc_html__( 'Overlay Icons Border Radius', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_border_radius',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Border Width
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_border_width', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '1px',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_img_overlay_icons_border_width', array(
				'label'	   				=> esc_html__( 'Overlay Icons Border Width', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_border_width',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Border Style
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_border_style', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'solid',
				'sanitize_callback' 	=> 'sanitize_key',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_img_overlay_icons_border_style', array(
				'label'	   				=> esc_html__( 'Overlay Icons Border Style', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_border_style',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
				'choices' 				=> array(
					'none' 		=> esc_html__( 'None', 'ocean-portfolio' ),
					'solid' 	=> esc_html__( 'Solid', 'ocean-portfolio' ),
					'double' 	=> esc_html__( 'Double', 'ocean-portfolio' ),
					'dashed' 	=> esc_html__( 'Dashed', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Overlay Icons Border Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_border_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'rgba(255,255,255,0.4)',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_border_color', array(
				'label'	   				=> esc_html__( 'Overlay Icons Border Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_border_color',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Overlay Icons Hover Border Color
			 */
			$wp_customize->add_setting( 'op_portfolio_img_overlay_icons_hover_border_color', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_img_overlay_icons_hover_border_color', array(
				'label'	   				=> esc_html__( 'Overlay Icons Hover Border Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_images',
				'settings' 				=> 'op_portfolio_img_overlay_icons_hover_border_color',
				'active_callback' 		=> 'op_portfolio_cac_has_image_overlay_icons',
				'priority' 				=> 10,
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_query', array(
				'title' 			=> esc_html__( 'Query', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Author
			 */
			$wp_customize->add_setting( 'op_portfolio_authors' );

			$wp_customize->add_control( new OceanWP_Customize_Multiple_Select_Control( $wp_customize, 'op_portfolio_authors', array(
				'label'	   				=> esc_html__( 'Author', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_authors',
				'choices' 				=> op_portfolio_helpers( 'authors' ),
				'priority' 				=> 10,
			) ) );

			/**
			 * Categories
			 */
			$wp_customize->add_setting( 'op_portfolio_category_ids' );

			$wp_customize->add_control( new OceanWP_Customize_Multiple_Select_Control( $wp_customize, 'op_portfolio_category_ids', array(
				'label'	   				=> esc_html__( 'Categories', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_category_ids',
				'choices' 				=> op_portfolio_helpers( 'category_ids' ),
				'priority' 				=> 10,
			) ) );

			/**
			 * Tags
			 */
			$wp_customize->add_setting( 'op_portfolio_tags' );

			$wp_customize->add_control( new OceanWP_Customize_Multiple_Select_Control( $wp_customize, 'op_portfolio_tags', array(
				'label'	   				=> esc_html__( 'Tags', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_tags',
				'choices' 				=> op_portfolio_helpers( 'tags' ),
				'priority' 				=> 10,
			) ) );

			/**
			 * Offset
			 */
			$wp_customize->add_setting( 'op_portfolio_offset', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_offset', array(
				'label'	   				=> esc_html__( 'Offset', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Number of item to displace (this setting breaks pagination).', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_offset',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Order By
			 */
			$wp_customize->add_setting( 'op_portfolio_orderby', array(
				'default' 				=> 'date',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_orderby', array(
				'label'	   				=> esc_html__( 'Order By', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Sort retrieved posts by parameter.', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_orderby',
				'priority' 				=> 10,
				'choices' 				=> array(
					'none' 				=> esc_html__( 'No Order', 'ocean-portfolio' ),
					'ID' 				=> esc_html__( 'ID', 'ocean-portfolio' ),
					'author' 			=> esc_html__( 'Author', 'ocean-portfolio' ),
					'title' 			=> esc_html__( 'Title', 'ocean-portfolio' ),
					'name' 				=> esc_html__( 'Slug', 'ocean-portfolio' ),
					'type' 				=> esc_html__( 'Post Type', 'ocean-portfolio' ),
					'date' 				=> esc_html__( 'Date', 'ocean-portfolio' ),
					'modified' 			=> esc_html__( 'Modified', 'ocean-portfolio' ),
					'parent' 			=> esc_html__( 'Parent', 'ocean-portfolio' ),
					'rand' 				=> esc_html__( 'Random', 'ocean-portfolio' ),
					'comment_count' 	=> esc_html__( 'Comment Count', 'ocean-portfolio' )
				),
			) ) );

			/**
			 * Order
			 */
			$wp_customize->add_setting( 'op_portfolio_order', array(
				'default' 				=> 'DESC',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_order', array(
				'label'	   				=> esc_html__( 'Order', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Designates the ascending or descending order.', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_order',
				'priority' 				=> 10,
				'choices' 				=> array(
					'ASC' 		=> esc_html__( 'Ascending Order', 'ocean-portfolio' ),
					'DESC' 		=> esc_html__( 'Descending Order', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Exclude Categories
			 */
			$wp_customize->add_setting( 'op_portfolio_exclude_category' );

			$wp_customize->add_control( new OceanWP_Customize_Multiple_Select_Control( $wp_customize, 'op_portfolio_exclude_category', array(
				'label'	   				=> esc_html__( 'Exclude Categories', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_query',
				'settings' 				=> 'op_portfolio_exclude_category',
				'choices' 				=> op_portfolio_helpers( 'category_ids' ),
				'priority' 				=> 10,
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_style', array(
				'title' 			=> esc_html__( 'Style', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Item Margin
			 */
			$wp_customize->add_setting( 'op_portfolio_item_margin', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '10px',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_item_margin', array(
				'label'	   				=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_margin',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Padding
			 */
			$wp_customize->add_setting( 'op_portfolio_item_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_item_padding', array(
				'label'	   				=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_padding',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Radius
			 */
			$wp_customize->add_setting( 'op_portfolio_item_border_radius', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_item_border_radius', array(
				'label'	   				=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_border_radius',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Width
			 */
			$wp_customize->add_setting( 'op_portfolio_item_border_width', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_item_border_width', array(
				'label'	   				=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_border_width',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Style
			 */
			$wp_customize->add_setting( 'op_portfolio_item_border_style', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'none',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_item_border_style', array(
				'label'	   				=> esc_html__( 'Item Border Style', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Designates the ascending or descending order.', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_border_style',
				'priority' 				=> 10,
				'choices' 				=> array(
					'none' 		=> esc_html__( 'None', 'ocean-portfolio' ),
					'solid' 	=> esc_html__( 'Solid', 'ocean-portfolio' ),
					'double' 	=> esc_html__( 'Double', 'ocean-portfolio' ),
					'dashed' 	=> esc_html__( 'Dashed', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Item Border Color
			 */
			$wp_customize->add_setting( 'op_portfolio_item_border_color', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_item_border_color', array(
				'label'	   				=> esc_html__( 'Item Border Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_border_color',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Background Color
			 */
			$wp_customize->add_setting( 'op_portfolio_item_bg', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_item_bg', array(
				'label'	   				=> esc_html__( 'Item Background Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_item_bg',
				'priority' 				=> 10,
			) ) );

			/**
			 * Outside Content Padding
			 */
			$wp_customize->add_setting( 'op_portfolio_outside_content_padding', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '25px',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_outside_content_padding', array(
				'label'	   				=> esc_html__( 'Outside Content Padding', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_outside_content_padding',
				'active_callback' 		=> 'op_portfolio_cac_has_outside_title_cat_position',
				'priority' 				=> 10,
			) ) );

			/**
			 * Outside Content Background
			 */
			$wp_customize->add_setting( 'op_portfolio_outside_content_bg', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#f9f9f9',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_outside_content_bg', array(
				'label'	   				=> esc_html__( 'Outside Content Background', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_outside_content_bg',
				'active_callback' 		=> 'op_portfolio_cac_has_outside_title_cat_position',
				'priority' 				=> 10,
			) ) );

			/**
			 * Title Color
			 */
			$wp_customize->add_setting( 'op_portfolio_title_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#333333',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_title_color', array(
				'label'	   				=> esc_html__( 'Title Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_title_color',
				'active_callback' 		=> 'op_portfolio_cac_has_title',
				'priority' 				=> 10,
			) ) );

			/**
			 * Title Hover Color
			 */
			$wp_customize->add_setting( 'op_portfolio_title_hover_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#13aff0',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_title_hover_color', array(
				'label'	   				=> esc_html__( 'Title Hover Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_title_hover_color',
				'active_callback' 		=> 'op_portfolio_cac_has_title',
				'priority' 				=> 10,
			) ) );

			/**
			 * Category Color
			 */
			$wp_customize->add_setting( 'op_portfolio_category_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#a7a7a7',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_category_color', array(
				'label'	   				=> esc_html__( 'Category Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_category_color',
				'active_callback' 		=> 'op_portfolio_cac_has_category',
				'priority' 				=> 10,
			) ) );

			/**
			 * Category Hover Color
			 */
			$wp_customize->add_setting( 'op_portfolio_category_hover_color', array(
				'transport' 			=> 'postMessage',
				'default' 				=> '#333333',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_category_hover_color', array(
				'label'	   				=> esc_html__( 'Category Hover Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_style',
				'settings' 				=> 'op_portfolio_category_hover_color',
				'active_callback' 		=> 'op_portfolio_cac_has_category',
				'priority' 				=> 10,
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_typography', array(
				'title' 			=> esc_html__( 'Typography', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Filter Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_filter_typo_font_family', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_font_weight', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_font_style',  	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_transform', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_filter_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_filter_typo', array(
				'label'	   				=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_typography',
	            'settings'    			=> array(
					'family'      	=> 'op_portfolio_filter_typo_font_family',
					'size'        	=> 'op_portfolio_filter_typo_font_size',
					'weight'      	=> 'op_portfolio_filter_typo_font_weight',
					'style'       	=> 'op_portfolio_filter_typo_font_style',
					'transform' 	=> 'op_portfolio_filter_typo_transform',
					'line_height' 	=> 'op_portfolio_filter_typo_line_height',
					'spacing' 		=> 'op_portfolio_filter_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Title Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_title_typo_font_family', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_font_weight', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_font_style',  	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_title_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_title_typo', array(
				'label'	   				=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_typography',
	            'settings'    			=> array(
					'family'      	=> 'op_portfolio_title_typo_font_family',
					'size'        	=> 'op_portfolio_title_typo_font_size',
					'weight'      	=> 'op_portfolio_title_typo_font_weight',
					'style'       	=> 'op_portfolio_title_typo_font_style',
					'transform' 	=> 'op_portfolio_title_typo_transform',
					'line_height' 	=> 'op_portfolio_title_typo_line_height',
					'spacing' 		=> 'op_portfolio_title_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Category Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_category_typo_font_family', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_font_weight', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_font_style',  	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_category_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_category_typo', array(
				'label'	   				=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_typography',
	            'settings'    			=> array(
					'family'      	=> 'op_portfolio_category_typo_font_family',
					'size'        	=> 'op_portfolio_category_typo_font_size',
					'weight'      	=> 'op_portfolio_category_typo_font_weight',
					'style'       	=> 'op_portfolio_category_typo_font_style',
					'transform' 	=> 'op_portfolio_category_typo_transform',
					'line_height' 	=> 'op_portfolio_category_typo_line_height',
					'spacing' 		=> 'op_portfolio_category_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_tablet_device', array(
				'title' 			=> esc_html__( 'Tablet Device', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Tablet Columns
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_columns', array(
				'default'           	=> '2',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_tablet_columns', array(
				'label'	   				=> esc_html__( 'Tablet Columns', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_tablet_device',
				'settings' 				=> 'op_portfolio_tablet_columns',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'max'   => 10,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Item Margin
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_item_margin', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_tablet_item_margin', array(
				'label'	   				=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_tablet_device',
				'settings' 				=> 'op_portfolio_tablet_item_margin',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Padding
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_item_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_tablet_item_padding', array(
				'label'	   				=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_tablet_device',
				'settings' 				=> 'op_portfolio_tablet_item_padding',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Radius
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_item_border_radius', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_tablet_item_border_radius', array(
				'label'	   				=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_tablet_device',
				'settings' 				=> 'op_portfolio_tablet_item_border_radius',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Width
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_item_border_width', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_tablet_item_border_width', array(
				'label'	   				=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_tablet_device',
				'settings' 				=> 'op_portfolio_tablet_item_border_width',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_filter_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_filter_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_filter_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_filter_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_tablet_filter_typo', array(
				'label'	   				=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_tablet_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_tablet_filter_typo_font_size',
					'transform' 	=> 'op_portfolio_tablet_filter_typo_transform',
					'line_height' 	=> 'op_portfolio_tablet_filter_typo_line_height',
					'spacing' 		=> 'op_portfolio_tablet_filter_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Title Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_title_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_title_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_title_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_title_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_tablet_title_typo', array(
				'label'	   				=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_tablet_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_tablet_title_typo_font_size',
					'transform' 	=> 'op_portfolio_tablet_title_typo_transform',
					'line_height' 	=> 'op_portfolio_tablet_title_typo_line_height',
					'spacing' 		=> 'op_portfolio_tablet_title_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Category Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_tablet_category_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_category_typo_transform', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_category_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_tablet_category_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_tablet_category_typo', array(
				'label'	   				=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_tablet_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_tablet_category_typo_font_size',
					'transform' 	=> 'op_portfolio_tablet_category_typo_transform',
					'line_height' 	=> 'op_portfolio_tablet_category_typo_line_height',
					'spacing' 		=> 'op_portfolio_tablet_category_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_mobile_device', array(
				'title' 			=> esc_html__( 'Mobile Device', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Mobile Columns
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_columns', array(
				'default'           	=> '2',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_mobile_columns', array(
				'label'	   				=> esc_html__( 'Mobile Columns', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_mobile_device',
				'settings' 				=> 'op_portfolio_mobile_columns',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'max'   => 10,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Item Margin
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_item_margin', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_mobile_item_margin', array(
				'label'	   				=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_mobile_device',
				'settings' 				=> 'op_portfolio_mobile_item_margin',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Padding
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_item_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_mobile_item_padding', array(
				'label'	   				=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_mobile_device',
				'settings' 				=> 'op_portfolio_mobile_item_padding',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Radius
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_item_border_radius', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_mobile_item_border_radius', array(
				'label'	   				=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_mobile_device',
				'settings' 				=> 'op_portfolio_mobile_item_border_radius',
				'priority' 				=> 10,
			) ) );

			/**
			 * Item Border Width
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_item_border_width', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_mobile_item_border_width', array(
				'label'	   				=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
				'description'   		=> esc_html__( 'Format: top/right/bottom/left.', 'ocean-portfolio' ),
				'type' 					=> 'text',
				'section'  				=> 'op_portfolio_mobile_device',
				'settings' 				=> 'op_portfolio_mobile_item_border_width',
				'priority' 				=> 10,
			) ) );

			/**
			 * Filter Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_filter_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_filter_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_filter_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_filter_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_mobile_filter_typo', array(
				'label'	   				=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_mobile_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_mobile_filter_typo_font_size',
					'transform' 	=> 'op_portfolio_mobile_filter_typo_transform',
					'line_height' 	=> 'op_portfolio_mobile_filter_typo_line_height',
					'spacing' 		=> 'op_portfolio_mobile_filter_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Title Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_title_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_title_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_title_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_title_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_mobile_title_typo', array(
				'label'	   				=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_mobile_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_mobile_title_typo_font_size',
					'transform' 	=> 'op_portfolio_mobile_title_typo_transform',
					'line_height' 	=> 'op_portfolio_mobile_title_typo_line_height',
					'spacing' 		=> 'op_portfolio_mobile_title_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Category Typography
			 */
			$wp_customize->add_setting( 'op_portfolio_mobile_category_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_category_typo_transform', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_category_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
			$wp_customize->add_setting( 'op_portfolio_mobile_category_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

			$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'op_portfolio_mobile_category_typo', array(
				'label'	   				=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_mobile_device',
	            'settings'    			=> array(
					'size'        	=> 'op_portfolio_mobile_category_typo_font_size',
					'transform' 	=> 'op_portfolio_mobile_category_typo_transform',
					'line_height' 	=> 'op_portfolio_mobile_category_typo_line_height',
					'spacing' 		=> 'op_portfolio_mobile_category_typo_spacing'
				),
				'priority' 				=> 10,
				'l10n'        			=> array(),
			) ) );

			/**
			 * Section
			 */
			$wp_customize->add_section( 'op_portfolio_single', array(
				'title' 			=> esc_html__( 'Single Portfolio Item', 'ocean-portfolio' ),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			) );

			/**
			 * Single Layout
			 */
			$wp_customize->add_setting( 'op_portfolio_single_layout', array(
				'default'           	=> 'full-width',
				'sanitize_callback' 	=> 'oceanwp_sanitize_select',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Radio_Image_Control( $wp_customize, 'op_portfolio_single_layout', array(
				'label'	   				=> esc_html__( 'Layout', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_layout',
				'priority' 				=> 10,
				'choices' 				=> oceanwp_customizer_layout(),
			) ) );

			/**
			 * Both Sidebars Style
			 */
			$wp_customize->add_setting( 'op_portfolio_single_both_sidebars_style', array(
				'default'           	=> 'scs-style',
				'sanitize_callback' 	=> 'oceanwp_sanitize_select',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_both_sidebars_style', array(
				'label'	   				=> esc_html__( 'Both Sidebars: Style', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_both_sidebars_style',
				'priority' 				=> 10,
				'choices' 				=> array(
					'ssc-style' 		=> esc_html__( 'Sidebar / Sidebar / Content', 'ocean-portfolio' ),
					'scs-style' 		=> esc_html__( 'Sidebar / Content / Sidebar', 'ocean-portfolio' ),
					'css-style' 		=> esc_html__( 'Content / Sidebar / Sidebar', 'ocean-portfolio' ),
				),
				'active_callback' 		=> 'op_portfolio_cac_has_single_bs_layout',
			) ) );

			/**
			 * Both Sidebars Content Width
			 */
			$wp_customize->add_setting( 'op_portfolio_single_both_sidebars_content_width', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_both_sidebars_content_width', array(
				'label'	   				=> esc_html__( 'Both Sidebars: Content Width (%)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_both_sidebars_content_width',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 100,
			        'step'  => 1,
			    ),
				'active_callback' 		=> 'op_portfolio_cac_has_single_bs_layout',
			) ) );

			/**
			 * Both Sidebars Sidebars Width
			 */
			$wp_customize->add_setting( 'op_portfolio_single_both_sidebars_sidebars_width', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_both_sidebars_sidebars_width', array(
				'label'	   				=> esc_html__( 'Both Sidebars: Sidebars Width (%)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_both_sidebars_sidebars_width',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 100,
			        'step'  => 1,
			    ),
				'active_callback' 		=> 'op_portfolio_cac_has_single_bs_layout',
			) ) );

			/**
			 * Add Container Featured Image In Page Header
			 */
			$wp_customize->add_setting( 'op_portfolio_single_featured_image_title', array(
				'default'           	=> false,
				'sanitize_callback' 	=> 'oceanwp_sanitize_checkbox',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_featured_image_title', array(
				'label'	   				=> esc_html__( 'Featured Image In Page Header', 'ocean-portfolio' ),
				'type' 					=> 'checkbox',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_featured_image_title',
				'priority' 				=> 10,
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Position
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_position', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'top center',
				'sanitize_callback' 	=> 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_title_bg_image_position', array(
				'label'	   				=> esc_html__( 'Position', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_position',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
				'choices' 				=> array(
					'initial' 			=> esc_html__( 'Default', 'ocean-portfolio' ),
					'top left' 			=> esc_html__( 'Top Left', 'ocean-portfolio' ),
					'top center' 		=> esc_html__( 'Top Center', 'ocean-portfolio' ),
					'top right'  		=> esc_html__( 'Top Right', 'ocean-portfolio' ),
					'center left' 		=> esc_html__( 'Center Left', 'ocean-portfolio' ),
					'center center' 	=> esc_html__( 'Center Center', 'ocean-portfolio' ),
					'center right' 		=> esc_html__( 'Center Right', 'ocean-portfolio' ),
					'bottom left' 		=> esc_html__( 'Bottom Left', 'ocean-portfolio' ),
					'bottom center' 	=> esc_html__( 'Bottom Center', 'ocean-portfolio' ),
					'bottom right' 		=> esc_html__( 'Bottom Right', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Attachment
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_attachment', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'initial',
				'sanitize_callback' 	=> 'oceanwp_sanitize_select',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_title_bg_image_attachment', array(
				'label'	   				=> esc_html__( 'Attachment', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_attachment',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
				'choices' 				=> array(
					'initial' 	=> esc_html__( 'Default', 'ocean-portfolio' ),
					'scroll' 	=> esc_html__( 'Scroll', 'ocean-portfolio' ),
					'fixed' 	=> esc_html__( 'Fixed', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Repeat
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_repeat', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'no-repeat',
				'sanitize_callback' 	=> 'oceanwp_sanitize_select',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_title_bg_image_repeat', array(
				'label'	   				=> esc_html__( 'Repeat', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_repeat',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
				'choices' 				=> array(
					'initial' 	=> esc_html__( 'Default', 'ocean-portfolio' ),
					'no-repeat' => esc_html__( 'No-repeat', 'ocean-portfolio' ),
					'repeat' 	=> esc_html__( 'Repeat', 'ocean-portfolio' ),
					'repeat-x' 	=> esc_html__( 'Repeat-x', 'ocean-portfolio' ),
					'repeat-y' 	=> esc_html__( 'Repeat-y', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Size
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_size', array(
				'transport' 			=> 'postMessage',
				'default' 				=> 'cover',
				'sanitize_callback' 	=> 'oceanwp_sanitize_select',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_single_title_bg_image_size', array(
				'label'	   				=> esc_html__( 'Size', 'ocean-portfolio' ),
				'type' 					=> 'select',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_size',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
				'choices' 				=> array(
					'initial' 	=> esc_html__( 'Default', 'ocean-portfolio' ),
					'auto' 		=> esc_html__( 'Auto', 'ocean-portfolio' ),
					'cover' 	=> esc_html__( 'Cover', 'ocean-portfolio' ),
					'contain' 	=> esc_html__( 'Contain', 'ocean-portfolio' ),
				),
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Height
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_height', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '400',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_single_title_bg_image_height', array(
				'label'	   				=> esc_html__( 'Page Header Height (px)', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_height',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 800,
			        'step'  => 1,
			    ),
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Overlay Opacity
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_overlay_opacity', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '0.5',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_single_title_bg_image_overlay_opacity', array(
				'label'	   				=> esc_html__( 'Overlay Opacity', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_overlay_opacity',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 1,
			        'step'  => 0.1,
			    ),
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
			) ) );

			/**
			 * Porfolio Single Page Header Background Image Overlay Color
			 */
			$wp_customize->add_setting( 'op_portfolio_single_title_bg_image_overlay_color', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#000000',
				'sanitize_callback' 	=> 'oceanwp_sanitize_color',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'op_portfolio_single_title_bg_image_overlay_color', array(
				'label'	   				=> esc_html__( 'Overlay Color', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_title_bg_image_overlay_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'op_portfolio_cac_has_single_title_bg_image',
			) ) );

			/**
			 * Porfolio Single Elements Positioning
			 */
			$wp_customize->add_setting( 'op_portfolio_single_elements_positioning', array(
				'default' 				=> array( 'featured_image', 'title', 'meta', 'content', 'tags', 'social_share', 'next_prev', 'related_portfolio', 'single_comments' ),
				'sanitize_callback' 	=> 'oceanwp_sanitize_multi_choices',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Sortable_Control( $wp_customize, 'op_portfolio_single_elements_positioning', array(
				'label'	   				=> esc_html__( 'Elements Positioning', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_elements_positioning',
				'priority' 				=> 10,
				'choices' 				=> op_portfolio_single_elements(),
			) ) );

			/**
			 * Porfolio Single Meta
			 */
			$wp_customize->add_setting( 'op_portfolio_single_meta', array(
				'default'           	=> array( 'author', 'date', 'categories', 'comments' ),
				'sanitize_callback' 	=> 'oceanwp_sanitize_multi_choices',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Sortable_Control( $wp_customize, 'op_portfolio_single_meta', array(
				'label'	   				=> esc_html__( 'Meta', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_single_meta',
				'priority' 				=> 10,
				'choices' 				=> apply_filters( 'op_portfolio_meta_choices', array(
					'author'     		=> esc_html__( 'Author', 'ocean-portfolio' ),
					'date'       		=> esc_html__( 'Date', 'ocean-portfolio' ),
					'categories' 		=> esc_html__( 'Categories', 'ocean-portfolio' ),
					'comments'   		=> esc_html__( 'Comments', 'ocean-portfolio' ),
				) ),
			) ) );

			/**
			 * Related Posts Count
			 */
			$wp_customize->add_setting( 'op_portfolio_related_count', array(
				'default' 				=> '3',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_related_count', array(
				'label'	   				=> esc_html__( 'Related Portfolio Count', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_related_count',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 2,
			        'max'   => 12,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Related Posts Columns
			 */
			$wp_customize->add_setting( 'op_portfolio_related_columns', array(
				'default' 				=> '3',
				'sanitize_callback' 	=> 'op_portfolio_sanitize_absint',
			) );

			$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'op_portfolio_related_columns', array(
				'label'	   				=> esc_html__( 'Related Posts Columns', 'ocean-portfolio' ),
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_related_columns',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 1,
			        'max'   => 6,
			        'step'  => 1,
			    ),
			) ) );

			/**
			 * Related Posts Image Width
			 */
			$wp_customize->add_setting( 'op_portfolio_related_img_width', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_related_img_width', array(
				'label'	   				=> esc_html__( 'Related Posts Image Width (px)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_related_img_width',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 800,
			    ),
			) ) );

			/**
			 * Related Posts Image Height
			 */
			$wp_customize->add_setting( 'op_portfolio_related_img_height', array(
				'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'op_portfolio_related_img_height', array(
				'label'	   				=> esc_html__( 'Related Posts Image Height (px)', 'ocean-portfolio' ),
				'type' 					=> 'number',
				'section'  				=> 'op_portfolio_single',
				'settings' 				=> 'op_portfolio_related_img_height',
				'priority' 				=> 10,
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 800,
			    ),
			) ) );

		}

		/**
		 * Get CSS
		 *
		 * @since 1.0.0
		 */
		public static function head_css( $output ) {

			// Styling vars
			$full_filter_links 						= get_theme_mod( 'op_portfolio_responsive_filter_links' );
			$full_filter_links 						= $full_filter_links ? $full_filter_links : '480';
			$custom_full_filter_links 				= get_theme_mod( 'op_portfolio_responsive_filter_links_custom' );
			$filter_margin 							= get_theme_mod( 'op_portfolio_filter_margin' );
			$filter_links_margin 					= get_theme_mod( 'op_portfolio_filter_links_margin' );
			$filter_links_padding 					= get_theme_mod( 'op_portfolio_filter_links_padding' );
			$filter_links_bg 						= get_theme_mod( 'op_portfolio_filter_links_bg', '#f6f6f6' );
			$filter_links_color 					= get_theme_mod( 'op_portfolio_filter_links_color', '#444444' );
			$filter_active_link_bg 					= get_theme_mod( 'op_portfolio_filter_active_link_bg', '#13aff0' );
			$filter_active_link_color 				= get_theme_mod( 'op_portfolio_filter_active_link_color', '#ffffff' );
			$filter_hover_links_bg 					= get_theme_mod( 'op_portfolio_filter_hover_links_bg', '#13aff0' );
			$filter_hover_links_color 				= get_theme_mod( 'op_portfolio_filter_hover_links_color', '#ffffff' );
			$img_overlay_color 						= get_theme_mod( 'op_portfolio_img_overlay_color', 'rgba(0,0,0,0.4)' );
			$img_overlay_icons_width 				= get_theme_mod( 'op_portfolio_img_overlay_icons_width', '45' );
			$img_overlay_icons_height 				= get_theme_mod( 'op_portfolio_img_overlay_icons_height', '45' );
			$img_overlay_icons_size 				= get_theme_mod( 'op_portfolio_img_overlay_icons_size', '16' );
			$img_overlay_icons_bg 					= get_theme_mod( 'op_portfolio_img_overlay_icons_bg', 'rgba(255,255,255,0.2)' );
			$img_overlay_icons_hover_bg 			= get_theme_mod( 'op_portfolio_img_overlay_icons_hover_bg', 'rgba(255,255,255,0.4)' );
			$img_overlay_icons_color 				= get_theme_mod( 'op_portfolio_img_overlay_icons_color', '#ffffff' );
			$img_overlay_icons_hover_color 			= get_theme_mod( 'op_portfolio_img_overlay_icons_hover_color', '#ffffff' );
			$img_overlay_icons_border_radius 		= get_theme_mod( 'op_portfolio_img_overlay_icons_border_radius' );
			$img_overlay_icons_border_width 		= get_theme_mod( 'op_portfolio_img_overlay_icons_border_width', '1px' );
			$img_overlay_icons_border_style 		= get_theme_mod( 'op_portfolio_img_overlay_icons_border_style', 'solid' );
			$img_overlay_icons_border_color 		= get_theme_mod( 'op_portfolio_img_overlay_icons_border_color', 'rgba(255,255,255,0.4)' );
			$img_overlay_icons_hover_border_color 	= get_theme_mod( 'op_portfolio_img_overlay_icons_hover_border_color' );
			$item_margin 							= get_theme_mod( 'op_portfolio_item_margin', '10px' );
			$item_padding 							= get_theme_mod( 'op_portfolio_item_padding' );
			$item_border_radius 					= get_theme_mod( 'op_portfolio_item_border_radius' );
			$item_border_width 						= get_theme_mod( 'op_portfolio_item_border_width' );
			$item_border_style 						= get_theme_mod( 'op_portfolio_item_border_style' );
			$item_border_color 						= get_theme_mod( 'op_portfolio_item_border_color' );
			$item_bg 								= get_theme_mod( 'op_portfolio_item_bg' );
			$outside_content_padding 				= get_theme_mod( 'op_portfolio_outside_content_padding', '25px' );
			$outside_content_bg 					= get_theme_mod( 'op_portfolio_outside_content_bg', '#f9f9f9' );
			$title_color 							= get_theme_mod( 'op_portfolio_title_color', '#333333' );
			$title_hover_color 						= get_theme_mod( 'op_portfolio_title_hover_color', '#13aff0' );
			$category_color 						= get_theme_mod( 'op_portfolio_category_color', '#a7a7a7' );
			$category_hover_color 					= get_theme_mod( 'op_portfolio_category_hover_color', '#333333' );

			// Typography
			$filter_font_family 					= get_theme_mod( 'op_portfolio_filter_typo_font_family' );
			$filter_font_size 						= get_theme_mod( 'op_portfolio_filter_typo_font_size' );
			$filter_font_weight 					= get_theme_mod( 'op_portfolio_filter_typo_font_weight' );
			$filter_font_style 						= get_theme_mod( 'op_portfolio_filter_typo_font_style' );
			$filter_text_transform 					= get_theme_mod( 'op_portfolio_filter_typo_transform' );
			$filter_line_height 					= get_theme_mod( 'op_portfolio_filter_typo_line_height' );
			$filter_letter_spacing 					= get_theme_mod( 'op_portfolio_filter_typo_spacing' );
			$title_font_family 						= get_theme_mod( 'op_portfolio_title_typo_font_family' );
			$title_font_size 						= get_theme_mod( 'op_portfolio_title_typo_font_size' );
			$title_font_weight 						= get_theme_mod( 'op_portfolio_title_typo_font_weight' );
			$title_font_style 						= get_theme_mod( 'op_portfolio_title_typo_font_style' );
			$title_text_transform 					= get_theme_mod( 'op_portfolio_title_typo_transform' );
			$title_line_height 						= get_theme_mod( 'op_portfolio_title_typo_line_height' );
			$title_letter_spacing 					= get_theme_mod( 'op_portfolio_title_typo_spacing' );
			$cat_font_family 						= get_theme_mod( 'op_portfolio_category_typo_font_family' );
			$cat_font_size 							= get_theme_mod( 'op_portfolio_category_typo_font_size' );
			$cat_font_weight 						= get_theme_mod( 'op_portfolio_category_typo_font_weight' );
			$cat_font_style 						= get_theme_mod( 'op_portfolio_category_typo_font_style' );
			$cat_text_transform 					= get_theme_mod( 'op_portfolio_category_typo_transform' );
			$cat_line_height 						= get_theme_mod( 'op_portfolio_category_typo_line_height' );
			$cat_letter_spacing 					= get_theme_mod( 'op_portfolio_category_typo_spacing' );

			// Tablet device
			$tablet_item_margin 					= get_theme_mod( 'op_portfolio_tablet_item_margin' );
			$tablet_item_padding 					= get_theme_mod( 'op_portfolio_tablet_item_padding' );
			$tablet_item_border_radius 				= get_theme_mod( 'op_portfolio_tablet_item_border_radius' );
			$tablet_item_border_width 				= get_theme_mod( 'op_portfolio_tablet_item_border_width' );
			$tablet_filter_font_size 				= get_theme_mod( 'op_portfolio_tablet_filter_typo_font_size' );
			$tablet_filter_text_transform 			= get_theme_mod( 'op_portfolio_tablet_filter_typo_transform' );
			$tablet_filter_line_height 				= get_theme_mod( 'op_portfolio_tablet_filter_typo_line_height' );
			$tablet_filter_letter_spacing 			= get_theme_mod( 'op_portfolio_tablet_filter_typo_spacing' );
			$tablet_title_font_size 				= get_theme_mod( 'op_portfolio_tablet_title_typo_font_size' );
			$tablet_title_text_transform 			= get_theme_mod( 'op_portfolio_tablet_title_typo_transform' );
			$tablet_title_line_height 				= get_theme_mod( 'op_portfolio_tablet_title_typo_line_height' );
			$tablet_title_letter_spacing 			= get_theme_mod( 'op_portfolio_tablet_title_typo_spacing' );
			$tablet_cat_font_size 					= get_theme_mod( 'op_portfolio_tablet_category_typo_font_size' );
			$tablet_cat_font_style 					= get_theme_mod( 'op_portfolio_tablet_category_typo_font_style' );
			$tablet_cat_text_transform 				= get_theme_mod( 'op_portfolio_tablet_category_typo_transform' );
			$tablet_cat_line_height 				= get_theme_mod( 'op_portfolio_tablet_category_typo_line_height' );
			$tablet_cat_letter_spacing 				= get_theme_mod( 'op_portfolio_tablet_category_typo_spacing' );

			// Mobile device
			$mobile_item_margin 					= get_theme_mod( 'op_portfolio_mobile_item_margin' );
			$mobile_item_padding 					= get_theme_mod( 'op_portfolio_mobile_item_padding' );
			$mobile_item_border_radius 				= get_theme_mod( 'op_portfolio_mobile_item_border_radius' );
			$mobile_item_border_width 				= get_theme_mod( 'op_portfolio_mobile_item_border_width' );
			$mobile_filter_font_size 				= get_theme_mod( 'op_portfolio_mobile_filter_typo_font_size' );
			$mobile_filter_text_transform 			= get_theme_mod( 'op_portfolio_mobile_filter_typo_transform' );
			$mobile_filter_line_height 				= get_theme_mod( 'op_portfolio_mobile_filter_typo_line_height' );
			$mobile_filter_letter_spacing 			= get_theme_mod( 'op_portfolio_mobile_filter_typo_spacing' );
			$mobile_title_font_size 				= get_theme_mod( 'op_portfolio_mobile_title_typo_font_size' );
			$mobile_title_text_transform 			= get_theme_mod( 'op_portfolio_mobile_title_typo_transform' );
			$mobile_title_line_height 				= get_theme_mod( 'op_portfolio_mobile_title_typo_line_height' );
			$mobile_title_letter_spacing 			= get_theme_mod( 'op_portfolio_mobile_title_typo_spacing' );
			$mobile_cat_font_size 					= get_theme_mod( 'op_portfolio_mobile_category_typo_font_size' );
			$mobile_cat_font_style 					= get_theme_mod( 'op_portfolio_mobile_category_typo_font_style' );
			$mobile_cat_text_transform 				= get_theme_mod( 'op_portfolio_mobile_category_typo_transform' );
			$mobile_cat_line_height 				= get_theme_mod( 'op_portfolio_mobile_category_typo_line_height' );
			$mobile_cat_letter_spacing 				= get_theme_mod( 'op_portfolio_mobile_category_typo_spacing' );

			// Both sidebars single product layout
			$single_layout 							= get_theme_mod( 'op_portfolio_single_layout', 'full-width' );
			$bs_single_content_width 				= get_theme_mod( 'op_portfolio_single_both_sidebars_content_width' );
			$bs_single_sidebars_width 				= get_theme_mod( 'op_portfolio_single_both_sidebars_sidebars_width' );

			// Define css var
			$css 						= '';
			$overlay_icons_css 			= '';
			$overlay_icons_hover_css 	= '';
			$border_css 				= '';
			$filter_typo_css 			= '';
			$title_typo_css 			= '';
			$cat_typo_css 				= '';
			$tablet_css 				= '';
			$tablet_filter_typo_css 	= '';
			$tablet_title_typo_css 		= '';
			$tablet_cat_typo_css 		= '';
			$mobile_css 				= '';
			$mobile_filter_typo_css 	= '';
			$mobile_title_typo_css 		= '';
			$mobile_cat_typo_css 		= '';

			// Add full width filter links in reponsive
			if ( ! empty( $full_filter_links ) ) {

				if ( 'custom' == $full_filter_links && ! empty( $custom_full_filter_links ) ) {
					$full_filter_links = $custom_full_filter_links;
				}

				$css .= '@media (max-width: '. $full_filter_links .'px) {.portfolio-entries .portfolio-filters li{width:100%;}}';
			}

			// Add filter margin
			if ( ! empty( $filter_margin ) ) {
				$css .= '.portfolio-entries .portfolio-filters{margin:'. $filter_margin .';}';
			}

			// Add filter links margin
			if ( ! empty( $filter_links_margin ) ) {
				$css .= '.portfolio-entries .portfolio-filters li{margin:'. $filter_links_margin .';}';
			}

			// Add filter links padding
			if ( ! empty( $filter_links_padding ) ) {
				$css .= '.portfolio-entries .portfolio-filters li a{padding:'. $filter_links_padding .';}';
			}

			// Add filter links background
			if ( ! empty( $filter_links_bg ) && '#f6f6f6' != $filter_links_bg ) {
				$css .= '.portfolio-entries .portfolio-filters li a{background-color:'. $filter_links_bg .';}';
			}

			// Add filter links color
			if ( ! empty( $filter_links_color ) && '#444444' != $filter_links_color ) {
				$css .= '.portfolio-entries .portfolio-filters li a{color:'. $filter_links_color .';}';
			}

			// Add filter active link background
			if ( ! empty( $filter_active_link_bg ) && '#13aff0' != $filter_active_link_bg ) {
				$css .= 'body .portfolio-entries .portfolio-filters li.active a{background-color:'. $filter_active_link_bg .';}';
			}

			// Add filter active link color
			if ( ! empty( $filter_active_link_color ) && '#ffffff' != $filter_active_link_color ) {
				$css .= 'body .portfolio-entries .portfolio-filters li.active a{color:'. $filter_active_link_color .';}';
			}

			// Add filter hover links background
			if ( ! empty( $filter_hover_links_bg ) && '#13aff0' != $filter_hover_links_bg ) {
				$css .= '.portfolio-entries .portfolio-filters li a:hover{background-color:'. $filter_hover_links_bg .';}';
			}

			// Add filter hover links color
			if ( ! empty( $filter_hover_links_color ) && '#ffffff' != $filter_hover_links_color ) {
				$css .= '.portfolio-entries .portfolio-filters li a:hover{color:'. $filter_hover_links_color .';}';
			}

			// Add images overlay color
			if ( ! empty( $img_overlay_color ) ) {
				$css .= '.portfolio-entries .portfolio-entry-thumbnail .overlay{background-color:'. $img_overlay_color .';}';
			}

			// Add images overlay icons style
			if ( ! empty( $img_overlay_icons_width ) && '45' != $img_overlay_icons_width ) {
				$overlay_icons_css .= 'width:' . $img_overlay_icons_width .'px;';
			}
			if ( ! empty( $img_overlay_icons_height ) && '45' != $img_overlay_icons_height ) {
				$overlay_icons_css .= 'height:' . $img_overlay_icons_height .'px;';
			}
			if ( ! empty( $img_overlay_icons_size ) && '16' != $img_overlay_icons_size ) {
				$overlay_icons_css .= 'font-size:' . $img_overlay_icons_size .'px;';
			}
			if ( ! empty( $img_overlay_icons_bg ) && 'rgba(255,255,255,0.2)' != $img_overlay_icons_bg ) {
				$overlay_icons_css .= 'background-color:' . $img_overlay_icons_bg .';';
			}
			if ( ! empty( $img_overlay_icons_color ) && '#ffffff' != $img_overlay_icons_color ) {
				$overlay_icons_css .= 'color:' . $img_overlay_icons_color .';';
			}
			if ( ! empty( $img_overlay_icons_border_radius ) ) {
				$overlay_icons_css .= 'border-radius:' . $img_overlay_icons_border_radius .';';
			}
			if ( ! empty( $img_overlay_icons_border_width ) && '1px' != $img_overlay_icons_border_width ) {
				$overlay_icons_css .= 'border-width:' . $img_overlay_icons_border_width .';';
			}
			if ( ! empty( $img_overlay_icons_border_style ) && 'solid' != $img_overlay_icons_border_style ) {
				$overlay_icons_css .= 'border-style:' . $img_overlay_icons_border_style .';';
			}
			if ( ! empty( $img_overlay_icons_border_color ) ) {
				$overlay_icons_css .= 'border-color:' . $img_overlay_icons_border_color .';';
			}
			if ( ! empty( $overlay_icons_css ) ) {
				$css .= '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a{'. $overlay_icons_css .'}';
			}

			if ( ! empty( $img_overlay_icons_hover_bg ) && 'rgba(255,255,255,0.4)' != $img_overlay_icons_hover_bg ) {
				$overlay_icons_hover_css .= 'background-color:' . $img_overlay_icons_hover_bg .';';
			}
			if ( ! empty( $img_overlay_icons_hover_color ) && '#ffffff' != $img_overlay_icons_hover_color ) {
				$overlay_icons_hover_css .= 'color:' . $img_overlay_icons_hover_color .';';
			}
			if ( ! empty( $img_overlay_icons_hover_border_color ) ) {
				$overlay_icons_hover_css .= 'border-color:' . $img_overlay_icons_hover_border_color .';';
			}
			if ( ! empty( $overlay_icons_hover_css ) ) {
				$css .= '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a:hover{'. $overlay_icons_hover_css .'}';
			}

			// Add item margin
			if ( ! empty( $item_margin ) && '10px' != $item_margin ) {
				$css .= '.portfolio-entries {margin: 0 -'. $item_margin .';}';
				$css .= '.portfolio-entries .portfolio-entry{padding:'. $item_margin .';}';
			}

			// Add padding
			if ( ! empty( $item_padding ) ) {
				$css .= '.portfolio-entries .portfolio-entry .portfolio-entry-inner{padding:'. $item_padding .';}';
			}

			// Add border radius
			if ( ! empty( $item_border_radius ) ) {
				$css .= '.portfolio-entries .portfolio-entry .portfolio-entry-inner{border-radius:'. $item_border_radius .';overflow: hidden;}';
			}

			// Add border
			if ( ! empty( $item_border_width ) ) {
				$border_css .= 'border-width:' . $item_border_width .';';
				if ( ! empty( $item_border_style ) && 'none' != $item_border_style ) {
					$border_css .= 'border-style:' . $item_border_style .';';
				}
				if ( ! empty( $item_border_color ) ) {
					$border_css .= 'border-color:' . $item_border_color .';';
				}
				$css .= '.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $border_css .'}';
			}

			// Add background color
			if ( ! empty( $item_bg ) ) {
				$css .= '.portfolio-entries .portfolio-entry .portfolio-entry-inner{background-color:'. $item_bg .';}';
			}

			// Add outside content background color
			if ( ! empty( $outside_content_padding ) && '25px' != $outside_content_padding ) {
				$css .= '.portfolio-entries .portfolio-content{padding:'. $outside_content_padding .';}';
			}
			
			if ( ! empty( $outside_content_bg ) && '#f9f9f9' != $outside_content_bg ) {
				$css .= '.portfolio-entries .portfolio-entry-thumbnail .triangle-wrap{border-bottom-color:'. $outside_content_bg .';}';
				$css .= '.portfolio-entries .portfolio-content{background-color:'. $outside_content_bg .';}';
			}

			// Add title color
			if ( ! empty( $title_color ) && '#333333' != $title_color ) {
				$css .= '.portfolio-entries .portfolio-entry-title a, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a{color:'. $title_color .';}';
			}

			if ( ! empty( $title_hover_color ) && '#13aff0' != $title_hover_color ) {
				$css .= '.portfolio-entries .portfolio-entry-title a:hover, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a:hover{color:'. $title_hover_color .';}';
			}

			// Add category color
			if ( ! empty( $category_color ) && '#a7a7a7' != $category_color ) {
				$css .= '.portfolio-entries .categories, .portfolio-entries .categories a, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a{color:'. $category_color .';}';
			}

			if ( ! empty( $category_hover_color ) && '#333333' != $category_hover_color ) {
				$css .= '.portfolio-entries .categories a:hover, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a:hover{color:'. $category_hover_color .';}';
			}

			// Add filter font family
			if ( ! empty( $filter_font_family ) ) {
				$filter_typo_css .= 'font-family:'. $filter_font_family .';';
			}

			// Add filter font size
			if ( ! empty( $filter_font_size ) ) {
				$filter_typo_css .= 'font-size:'. $filter_font_size .';';
			}

			// Add filter font weight
			if ( ! empty( $filter_font_weight ) ) {
				$filter_typo_css .= 'font-weight:'. $filter_font_weight .';';
			}

			// Add filter font style
			if ( ! empty( $filter_font_style ) ) {
				$filter_typo_css .= 'font-style:'. $filter_font_style .';';
			}

			// Add filter text transform
			if ( ! empty( $filter_text_transform ) ) {
				$filter_typo_css .= 'text-transform:'. $filter_text_transform .';';
			}

			// Add filter line height
			if ( ! empty( $filter_line_height ) ) {
				$filter_typo_css .= 'line-height:'. $filter_line_height .';';
			}

			// Add filter letter spacing
			if ( ! empty( $filter_letter_spacing ) ) {
				$filter_typo_css .= 'letter-spacing:'. $filter_letter_spacing .';';
			}

			// Filter typography css
			if ( ! empty( $filter_typo_css ) ) {
				$css .= '.portfolio-entries .portfolio-filters li a{'. $filter_typo_css .'}';
			}

			// Add title font family
			if ( ! empty( $title_font_family ) ) {
				$title_typo_css .= 'font-family:'. $title_font_family .';';
			}

			// Add title font size
			if ( ! empty( $title_font_size ) ) {
				$title_typo_css .= 'font-size:'. $title_font_size .';';
			}

			// Add title font weight
			if ( ! empty( $title_font_weight ) ) {
				$title_typo_css .= 'font-weight:'. $title_font_weight .';';
			}

			// Add title font style
			if ( ! empty( $title_font_style ) ) {
				$title_typo_css .= 'font-style:'. $title_font_style .';';
			}

			// Add title text transform
			if ( ! empty( $title_text_transform ) ) {
				$title_typo_css .= 'text-transform:'. $title_text_transform .';';
			}

			// Add title line height
			if ( ! empty( $title_line_height ) ) {
				$title_typo_css .= 'line-height:'. $title_line_height .';';
			}

			// Add title letter spacing
			if ( ! empty( $title_letter_spacing ) ) {
				$title_typo_css .= 'letter-spacing:'. $title_letter_spacing .';';
			}

			// Title typography css
			if ( ! empty( $title_typo_css ) ) {
				$css .= '.portfolio-entries .portfolio-entry-title{'. $title_typo_css .'}';
			}

			// Add category font family
			if ( ! empty( $cat_font_family ) ) {
				$cat_typo_css .= 'font-family:'. $cat_font_family .';';
			}

			// Add category font size
			if ( ! empty( $cat_font_size ) ) {
				$cat_typo_css .= 'font-size:'. $cat_font_size .';';
			}

			// Add category font weight
			if ( ! empty( $cat_font_weight ) ) {
				$cat_typo_css .= 'font-weight:'. $cat_font_weight .';';
			}

			// Add category font style
			if ( ! empty( $cat_font_style ) ) {
				$cat_typo_css .= 'font-style:'. $cat_font_style .';';
			}

			// Add category text transform
			if ( ! empty( $cat_text_transform ) ) {
				$cat_typo_css .= 'text-transform:'. $cat_text_transform .';';
			}

			// Add category line height
			if ( ! empty( $cat_line_height ) ) {
				$cat_typo_css .= 'line-height:'. $cat_line_height .';';
			}

			// Add category letter spacing
			if ( ! empty( $cat_letter_spacing ) ) {
				$cat_typo_css .= 'letter-spacing:'. $cat_letter_spacing .';';
			}

			// Category typography css
			if ( ! empty( $cat_typo_css ) ) {
				$css .= '.portfolio-entries .categories{'. $cat_typo_css .'}';
			}

			// Add tablet item margin
			if ( ! empty( $tablet_item_margin ) ) {
				$css .= '@media (max-width: 1023px) {.portfolio-entries {margin: 0 -'. $tablet_item_margin .';}}';
				$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-entry{padding:'. $tablet_item_margin .';}}';
			}

			// Add tablet padding
			if ( ! empty( $tablet_item_padding ) ) {
				$tablet_css .= 'padding:'. $tablet_item_padding .';';
			}

			// Add tablet border radius
			if ( ! empty( $tablet_item_border_radius ) ) {
				$tablet_css .= 'border-radius:'. $tablet_item_border_radius .';overflow: hidden;';
			}

			// Add tablet border
			if ( ! empty( $item_border_width ) && ! empty( $tablet_item_border_width ) ) {
				$tablet_css .= 'border-width:' . $tablet_item_border_width .';';
			}

			// Tablet css
			if ( ! empty( $tablet_css ) ) {
				$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $tablet_css .'}}';
			}

			// Add tablet filter font size
			if ( ! empty( $tablet_filter_font_size ) ) {
				$tablet_filter_typo_css .= 'font-size:'. $tablet_filter_font_size .';';
			}

			// Add tablet filter text transform
			if ( ! empty( $tablet_filter_text_transform ) ) {
				$tablet_filter_typo_css .= 'text-transform:'. $tablet_filter_text_transform .';';
			}

			// Add tablet filter line height
			if ( ! empty( $tablet_filter_line_height ) ) {
				$tablet_filter_typo_css .= 'line-height:'. $tablet_filter_line_height .';';
			}

			// Add tablet filter letter spacing
			if ( ! empty( $tablet_filter_letter_spacing ) ) {
				$tablet_filter_typo_css .= 'letter-spacing:'. $tablet_filter_letter_spacing .';';
			}

			// Tablet Typo css
			if ( ! empty( $tablet_filter_typo_css ) ) {
				$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a{'. $tablet_filter_typo_css .'}}';
			}

			// Add tablet title font size
			if ( ! empty( $tablet_title_font_size ) ) {
				$tablet_title_typo_css .= 'font-size:'. $tablet_title_font_size .';';
			}

			// Add tablet title text transform
			if ( ! empty( $tablet_title_text_transform ) ) {
				$tablet_title_typo_css .= 'text-transform:'. $tablet_title_text_transform .';';
			}

			// Add tablet title line height
			if ( ! empty( $tablet_title_line_height ) ) {
				$tablet_title_typo_css .= 'line-height:'. $tablet_title_line_height .';';
			}

			// Add tablet title letter spacing
			if ( ! empty( $tablet_title_letter_spacing ) ) {
				$tablet_title_typo_css .= 'letter-spacing:'. $tablet_title_letter_spacing .';';
			}

			// Tablet Typo css
			if ( ! empty( $tablet_title_typo_css ) ) {
				$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title{'. $tablet_title_typo_css .'}}';
			}

			// Add tablet category font size
			if ( ! empty( $tablet_cat_font_size ) ) {
				$tablet_cat_typo_css .= 'font-size:'. $tablet_cat_font_size .';';
			}

			// Add tablet category text transform
			if ( ! empty( $tablet_cat_text_transform ) ) {
				$tablet_cat_typo_css .= 'text-transform:'. $tablet_cat_text_transform .';';
			}

			// Add tablet category line height
			if ( ! empty( $tablet_cat_line_height ) ) {
				$tablet_cat_typo_css .= 'line-height:'. $tablet_cat_line_height .';';
			}

			// Add tablet category letter spacing
			if ( ! empty( $tablet_cat_letter_spacing ) ) {
				$tablet_cat_typo_css .= 'letter-spacing:'. $tablet_cat_letter_spacing .';';
			}

			// Tablet category typography css
			if ( ! empty( $tablet_cat_typo_css ) ) {
				$css .= '@media (max-width: 1023px) {.portfolio-entries .categories{'. $tablet_cat_typo_css .'}}';
			}

			// Add mobile item margin
			if ( ! empty( $mobile_item_margin ) ) {
				$css .= '@media (max-width: 767px) {.portfolio-entries {margin: 0 -'. $mobile_item_margin .';}}';
				$css .= '@media (max-width: 767px) {.portfolio-entries .portfolio-entry{padding:'. $mobile_item_margin .';}}';
			}

			// Add mobile padding
			if ( ! empty( $mobile_item_padding ) ) {
				$mobile_css .= 'padding:'. $mobile_item_padding .';';
			}

			// Add mobile border radius
			if ( ! empty( $mobile_item_border_radius ) ) {
				$mobile_css .= 'border-radius:'. $mobile_item_border_radius .';overflow: hidden;';
			}

			// Add mobile border
			if ( ! empty( $item_border_width ) && ! empty( $mobile_item_border_width ) ) {
				$mobile_css .= 'border-width:' . $mobile_item_border_width .';';
			}

			// Mobile css
			if ( ! empty( $mobile_css ) ) {
				$css .= '@media (max-width: 767px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $mobile_css .'}}';
			}

			// Add mobile filter font size
			if ( ! empty( $mobile_filter_font_size ) ) {
				$mobile_filter_typo_css .= 'font-size:'. $mobile_filter_font_size .';';
			}

			// Add mobile filter text transform
			if ( ! empty( $mobile_filter_text_transform ) ) {
				$mobile_filter_typo_css .= 'text-transform:'. $mobile_filter_text_transform .';';
			}

			// Add mobile filter line height
			if ( ! empty( $mobile_filter_line_height ) ) {
				$mobile_filter_typo_css .= 'line-height:'. $mobile_filter_line_height .';';
			}

			// Add mobile filter letter spacing
			if ( ! empty( $mobile_filter_letter_spacing ) ) {
				$mobile_filter_typo_css .= 'letter-spacing:'. $mobile_filter_letter_spacing .';';
			}

			// Mobile typo css
			if ( ! empty( $mobile_filter_typo_css ) ) {
				$css .= '@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a{'. $mobile_filter_typo_css .'}}';
			}

			// Add mobile title font size
			if ( ! empty( $mobile_title_font_size ) ) {
				$mobile_title_typo_css .= 'font-size:'. $mobile_title_font_size .';';
			}

			// Add mobile title text transform
			if ( ! empty( $mobile_title_text_transform ) ) {
				$mobile_title_typo_css .= 'text-transform:'. $mobile_title_text_transform .';';
			}

			// Add mobile title line height
			if ( ! empty( $mobile_title_line_height ) ) {
				$mobile_title_typo_css .= 'line-height:'. $mobile_title_line_height .';';
			}

			// Add mobile title letter spacing
			if ( ! empty( $mobile_title_letter_spacing ) ) {
				$mobile_title_typo_css .= 'letter-spacing:'. $mobile_title_letter_spacing .';';
			}

			// Mobile typo css
			if ( ! empty( $mobile_title_typo_css ) ) {
				$css .= '@media (max-width: 767px) {.portfolio-entries .portfolio-entry-title{'. $mobile_title_typo_css .'}}';
			}

			// Add mobile category font size
			if ( ! empty( $mobile_cat_font_size ) ) {
				$mobile_cat_typo_css .= 'font-size:'. $mobile_cat_font_size .';';
			}

			// Add mobile category text transform
			if ( ! empty( $mobile_cat_text_transform ) ) {
				$mobile_cat_typo_css .= 'text-transform:'. $mobile_cat_text_transform .';';
			}

			// Add mobile category line height
			if ( ! empty( $mobile_cat_line_height ) ) {
				$mobile_cat_typo_css .= 'line-height:'. $mobile_cat_line_height .';';
			}

			// Add mobile category letter spacing
			if ( ! empty( $mobile_cat_letter_spacing ) ) {
				$mobile_cat_typo_css .= 'letter-spacing:'. $mobile_cat_letter_spacing .';';
			}

			// Mobile category typography css
			if ( ! empty( $mobile_cat_typo_css ) ) {
				$css .= '@media (max-width: 767px) {.portfolio-entries .categories{'. $mobile_cat_typo_css .'}}';
			}

			// If single item Both Sidebars layout
			if ( 'both-sidebars' == $single_layout ) {

				// Both Sidebars layout single item content width
				if ( ! empty( $bs_single_content_width ) ) {
					$css .=
						'@media only screen and (min-width: 960px){
							body.single-ocean_portfolio.content-both-sidebars .content-area {width: '. $bs_single_content_width .'%;}
							body.single-ocean_portfolio.content-both-sidebars.scs-style .widget-area.sidebar-secondary,
							body.single-ocean_portfolio.content-both-sidebars.ssc-style .widget-area {left: -'. $bs_single_content_width .'%;}
						}';
				}

				// Both Sidebars layout single item sidebars width
				if ( ! empty( $bs_single_sidebars_width ) ) {
					$css .=
						'@media only screen and (min-width: 960px){
							body.single-ocean_portfolio.content-both-sidebars .widget-area{width:'. $bs_single_sidebars_width .'%;}
							body.single-ocean_portfolio.content-both-sidebars.scs-style .content-area{left:'. $bs_single_sidebars_width .'%;}
							body.single-ocean_portfolio.content-both-sidebars.ssc-style .content-area{left:'. $bs_single_sidebars_width * 2 .'%;}
						}';
				}

			}
				
			// Return CSS
			if ( ! empty( $css ) ) {
				$output .= '/* Portfolio CSS */'. $css;
			}

			// Return output css
			return $output;

		}

	}

}
new OceanWP_Portfolio_Customizer();