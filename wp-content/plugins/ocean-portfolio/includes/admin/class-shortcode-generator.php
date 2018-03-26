<?php
/**
 * Shortcode Generator
 */

if ( ! class_exists( 'OceanWP_Portfolio_Shortcode_Generator' ) ) {

	class OceanWP_Portfolio_Shortcode_Generator {

		/**
		 * Start things up
		 */
		public function __construct() {
			// Capabilities
			$capabilities = apply_filters( 'ocean_main_metaboxes_capabilities', 'manage_options' );

			if ( current_user_can( $capabilities ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'metabox_scripts' ) );
				add_action( 'butterbean_register', array( $this, 'metabox' ), 10, 2 );
			}
			add_action( 'add_meta_boxes_portfolio_shortcodes', array( $this, 'add_meta_box' ) );
		}

		/**
		 * Load metabox scripts and styles
		 *
		 * @since 1.0.0
		 */
		public function metabox_scripts( $hook ) {

			// Only needed on these admin screens
			if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
				return;
			}

			// Get global post
			global $post;

			// Return if post is not object
			if ( ! is_object( $post ) ) {
				return;
			}

			// Return if wrong post type
			if ( 'portfolio_shortcodes' != $post->post_type ) {
				return;
			}

			// Enqueue scripts
			wp_enqueue_script( 'op-portfolio-metabox-script', plugins_url( '/assets/js/metabox.min.js', __FILE__ ), array( 'jquery' ), OP_VERSION, true );
			wp_enqueue_style( 'oceanwp-butterbean' );
			wp_enqueue_script( 'oceanwp-butterbean' );
			wp_enqueue_script( 'oceanwp-select2' );
			wp_enqueue_style( 'select2' );
			wp_enqueue_script( 'wp-color-picker-alpha' );

		}

		/**
		 * Register metabox
		 *
		 * @since 1.0.0
	 	 */
		public static function metabox( $butterbean, $post_type ) {

			if ( 'portfolio_shortcodes' !== $post_type ) {
				return;
			}

			require_once( OP_PATH .'/includes/admin/sanitize.php' );

			// Register managers, sections, controls, and settings here.
			$butterbean->register_manager(
		        'op_portfolio_settings',
		        array(
		            'label'     => esc_html__( 'Portfolio Settings', 'ocean-portfolio' ),
		            'post_type' => 'portfolio_shortcodes',
		            'context'   => 'normal',
		            'priority'  => 'high'
		        )
		    );
			
			$manager = $butterbean->get_manager( 'op_portfolio_settings' );
			
			$manager->register_section(
		        'op_portfolio_general',
		        array(
		            'label' => esc_html__( 'General', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-admin-tools'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_posts_per_page', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'number',
		            'label'   		=> esc_html__( 'Posts Per Page', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Put -1 to display all portfolio items. Default is 12.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'step' 	=> '1',
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_posts_per_page', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_intval',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_columns', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Columns', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your columns number.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_columns', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '3',
		        )
		    );
				
			$manager->register_control(
		        'op_portfolio_masonry', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Masonry', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the masonry.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_masonry', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'off',
		        )
		    );
				
			$manager->register_control(
		        'op_portfolio_title_cat_position', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Title/Category Position', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose the title and category position.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'inside' 	=> esc_html__( 'Inside', 'ocean-portfolio' ),
						'outside' 	=> esc_html__( 'Outside', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_title_cat_position', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'outside',
		        )
		    );
				
			$manager->register_control(
		        'op_portfolio_title', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Title', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the items title.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_title', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_title_tag', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Title HTML Tag', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select your heading tag.', 'ocean-portfolio' ),
					'choices' 		=> array(
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
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_title_tag', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
					'default' 			=> 'h3',
		        )
		    );
				
			$manager->register_control(
		        'op_portfolio_category', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Category', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the items category.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_category', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );
				
			$manager->register_control(
		        'op_portfolio_pagination', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Pagination', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the pagination.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_pagination', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'off',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_pagination_position', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_general',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Pagination Position', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose the pagination position.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'left' 		=> esc_html__( 'Left', 'ocean-portfolio' ),
						'center' 	=> esc_html__( 'Center', 'ocean-portfolio' ),
						'right' 	=> esc_html__( 'Right', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_pagination_position', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'center',
		        )
		    );

			$manager->register_section(
		        'op_portfolio_filter_bar',
		        array(
		            'label' => esc_html__( 'Filter Bar', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-category'
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Filter', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the filter bar.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );

			$manager->register_setting(
		        'op_portfolio_filter', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'off',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_all_filter', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Display Link All', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enable or disable the "All" link of the filter bar.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 		=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 		=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_all_filter', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_position', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Filter Position', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose the filter position.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'full' 		=> esc_html__( 'Full', 'ocean-portfolio' ),
						'left' 		=> esc_html__( 'Left', 'ocean-portfolio' ),
						'center' 	=> esc_html__( 'Center', 'ocean-portfolio' ),
						'right' 	=> esc_html__( 'Right', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_position', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'center',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_taxonomy', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Taxonomy', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Display the filter by categories or tags.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'categories' => esc_html__( 'Categories', 'ocean-portfolio' ),
						'tags' 		=> esc_html__( 'Tags', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_taxonomy', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'categories',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_responsive_filter_links', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Responsive Filter Links', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose the media query where you want the filter bar links to be full width.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'1280' 		=> esc_html__( 'From 1280px', 'ocean-portfolio' ),
						'1080' 		=> esc_html__( 'From 1080px', 'ocean-portfolio' ),
						'959' 		=> esc_html__( 'From 959px', 'ocean-portfolio' ),
						'767' 		=> esc_html__( 'From 767px', 'ocean-portfolio' ),
						'480' 		=> esc_html__( 'From 480px', 'ocean-portfolio' ),
						'320' 		=> esc_html__( 'From 320px', 'ocean-portfolio' ),
						'custom' 	=> esc_html__( 'Custom media query', 'ocean-portfolio' ),
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_responsive_filter_links', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
					'default' 			=> '480',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_responsive_filter_links_custom', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'number',
		            'label'   		=> esc_html__( 'Custom Media Query', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter your custom media query where you want the filter bar links to be full width.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_responsive_filter_links_custom', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_filter_margin', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Filter Bar Margin', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom margin for the filter bar. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_margin', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_filter_links_margin', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Filter Links: Margin', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom margin for the filter bar links. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_links_margin', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_filter_links_padding', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Filter Links: Padding', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom padding for the filter bar links. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_links_padding', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_links_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Filter Links: Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #f6f6f6', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_links_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_links_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Filter Links: Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #444444', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_links_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_active_link_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Filter Active Link: Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #13aff0', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_active_link_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_active_link_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Filter Active Link: Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_active_link_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_hover_links_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Filter Hover Links: Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #13aff0', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_hover_links_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_filter_hover_links_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_filter_bar',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Filter Hover Links: Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_filter_hover_links_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );
			
			$manager->register_section(
		        'op_portfolio_images',
		        array(
		            'label' => esc_html__( 'Images', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-format-image'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_size', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Image Size', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select the size of your images.', 'ocean-portfolio' ),
					'choices' 		=> op_portfolio_helpers( 'img_sizes' ),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_size', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
					'default' 			=> 'medium',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'number',
		            'label'   		=> esc_html__( 'Image Width (px)', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter your custom image width.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_img_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_height', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'number',
		            'label'   		=> esc_html__( 'Image Height (px)', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter your custom image height.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_img_height', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Overlay Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: rgba(0,0,0,0.4)', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Display Overlay Icons', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Display the overlay icons on image hover.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'on' 	=> esc_html__( 'On', 'ocean-portfolio' ),
						'off' 	=> esc_html__( 'Off', 'ocean-portfolio' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Overlay Icons Width', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your overlay icons width.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '45',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_height', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Overlay Icons Height', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your overlay icons height.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_height', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '45',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_size', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Overlay Icons Size', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your overlay icons size.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_size', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '16',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Overlay Icons Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: rgba(255,255,255,0.2)', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_hover_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Overlay Icons Hover Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: rgba(255,255,255,0.2)', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_hover_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Overlay Icons Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_hover_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'rgba-color',
		            'label'   		=> esc_html__( 'Overlay Icons Hover Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_hover_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_border_radius', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Overlay Icons Border Radius', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border radius for your overlay icons. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_border_radius', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_border_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Overlay Icons Border Width', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border for your overlay icons. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_border_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		            'default' 			=> '1px',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_img_overlay_icons_border_style', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Overlay Icons Border Style', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your border style for your overlay icons.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'none' 		=> esc_html__( 'None', 'ocean-portfolio' ),
						'solid' 	=> esc_html__( 'Solid', 'ocean-portfolio' ),
						'double' 	=> esc_html__( 'Double', 'ocean-portfolio' ),
						'dashed' 	=> esc_html__( 'Dashed', 'ocean-portfolio' ),
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_border_style', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
					'default' 			=> 'solid',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_border_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Overlay Icons Border Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_border_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_img_overlay_icons_hover_border_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_images',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Overlay Icons Hover Border Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #ffffff', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_img_overlay_icons_hover_border_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );
			
			$manager->register_section(
		        'op_portfolio_query',
		        array(
		            'label' => esc_html__( 'Query', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-admin-post'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_authors', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'multiple-select',
		            'label'   		=> esc_html__( 'Author', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Display items by author.', 'ocean-portfolio' ),
					'choices' 		=> op_portfolio_helpers( 'authors' ),
		        )
		    );
				
			$manager->register_setting(
				'op_portfolio_authors', // Same as control name.
				array(
		            'type' 				=> 'array',
				)
			);

		    $manager->register_control(
		        'op_portfolio_category_ids', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'multiple-select',
		            'label'   		=> esc_html__( 'Categories', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Display items by categories.', 'ocean-portfolio' ),
					'choices' 		=> op_portfolio_helpers( 'category_ids' ),
		        )
		    );
				
			$manager->register_setting(
				'op_portfolio_category_ids', // Same as control name.
				array(
		            'type' 				=> 'array',
				)
			);

		    $manager->register_control(
		        'op_portfolio_tags', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'multiple-select',
		            'label'   		=> esc_html__( 'Tags', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Display items by tags.', 'ocean-portfolio' ),
					'choices' 		=> op_portfolio_helpers( 'tags' ),
		        )
		    );
				
			$manager->register_setting(
				'op_portfolio_tags', // Same as control name.
				array(
		            'type' 				=> 'array',
				)
			);

		    $manager->register_control(
		        'op_portfolio_offset', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'number',
		            'label'   		=> esc_html__( 'Offset', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Number of item to displace (this setting breaks pagination).', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_offset', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_orderby', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Order By', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Sort retrieved posts by parameter.', 'ocean-portfolio' ),
					'choices' 		=> array(
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
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_orderby', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
					'default' 			=> 'date',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_order', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Order', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Designates the ascending or descending order.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'ASC' 		=> esc_html__( 'Ascending Order', 'ocean-portfolio' ),
						'DESC' 		=> esc_html__( 'Descending Order', 'ocean-portfolio' ),
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_order', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
					'default' 			=> 'DESC',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_exclude_category', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_query',
		            'type'    		=> 'multiple-select',
		            'label'   		=> esc_html__( 'Exclude Categories', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose the categories you want to exclude.', 'ocean-portfolio' ),
					'choices' 		=> op_portfolio_helpers( 'category_ids' ),
		        )
		    );
				
			$manager->register_setting(
				'op_portfolio_exclude_category', // Same as control name.
				array(
		            'type' 				=> 'array',
				)
			);
			
			$manager->register_section(
		        'op_portfolio_style',
		        array(
		            'label' => esc_html__( 'Style', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-admin-customizer'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_item_margin', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom margin between your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_margin', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		            'default' 			=> '10px',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_item_padding', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a padding for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_padding', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_item_border_radius', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border radius for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_border_radius', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_item_border_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_border_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_item_border_style', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Item Border Style', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your border style for your items.', 'ocean-portfolio' ),
					'choices' 		=> array(
						'none' 		=> esc_html__( 'None', 'ocean-portfolio' ),
						'solid' 	=> esc_html__( 'Solid', 'ocean-portfolio' ),
						'double' 	=> esc_html__( 'Double', 'ocean-portfolio' ),
						'dashed' 	=> esc_html__( 'Dashed', 'ocean-portfolio' ),
					),
		        )
		    );
				
			$manager->register_setting(
		        'op_portfolio_item_border_style', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
					'default' 			=> 'none',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_item_border_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Item Border Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #eaeaea', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_border_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_item_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Item Background Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #f6f6f6', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_item_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_outside_content_padding', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Outside Content Padding', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a custom padding for the outside content. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_outside_content_padding', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		            'default' 			=> '25px',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_outside_content_bg', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Outside Content Background', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #f9f9f9', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_outside_content_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_title_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Title Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #333333', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_title_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_title_hover_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Title Hover Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #666666', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_title_hover_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_category_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Category Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #333333', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_category_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'op_portfolio_category_hover_color', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_style',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Category Hover Color', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Select a hex color code, ex: #666666', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_category_hover_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );
			
			$manager->register_section(
		        'op_portfolio_typography',
		        array(
		            'label' => esc_html__( 'Typography', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-edit'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_filter_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_typography',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the filter.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'family'      	=> 'op_portfolio_filter_typo_font_family',
						'size'        	=> 'op_portfolio_filter_typo_font_size',
						'weight'      	=> 'op_portfolio_filter_typo_font_weight',
						'style'       	=> 'op_portfolio_filter_typo_font_style',
						'transform' 	=> 'op_portfolio_filter_typo_transform',
						'line_height' 	=> 'op_portfolio_filter_typo_line_height',
						'spacing' 		=> 'op_portfolio_filter_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_filter_typo_font_family', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_font_weight', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_font_style',  	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_transform', 		array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_filter_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_title_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_typography',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the title.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'family'      	=> 'op_portfolio_title_typo_font_family',
						'size'        	=> 'op_portfolio_title_typo_font_size',
						'weight'      	=> 'op_portfolio_title_typo_font_weight',
						'style'       	=> 'op_portfolio_title_typo_font_style',
						'transform' 	=> 'op_portfolio_title_typo_transform',
						'line_height' 	=> 'op_portfolio_title_typo_line_height',
						'spacing' 		=> 'op_portfolio_title_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_title_typo_font_family', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_title_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_title_typo_font_weight', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_title_typo_font_style',  	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_title_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_title_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_title_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_category_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_typography',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the category.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'family'      	=> 'op_portfolio_category_typo_font_family',
						'size'        	=> 'op_portfolio_category_typo_font_size',
						'weight'      	=> 'op_portfolio_category_typo_font_weight',
						'style'       	=> 'op_portfolio_category_typo_font_style',
						'transform' 	=> 'op_portfolio_category_typo_transform',
						'line_height' 	=> 'op_portfolio_category_typo_line_height',
						'spacing' 		=> 'op_portfolio_category_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_category_typo_font_family', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_category_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_category_typo_font_weight', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_category_typo_font_style',  	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_category_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_category_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_category_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );
			
			$manager->register_section(
		        'op_portfolio_tablet_device',
		        array(
		            'label' => esc_html__( 'Tablet Device', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-tablet'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_columns', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Tablet Columns', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your columns number for tablet device.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_tablet_columns', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '2',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_item_margin', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom margin between your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_tablet_item_margin', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_item_padding', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a padding for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_tablet_item_padding', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_item_border_radius', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border radius for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_tablet_item_border_radius', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_item_border_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_tablet_item_border_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_tablet_filter_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the filter.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_tablet_filter_typo_font_size',
						'transform' 	=> 'op_portfolio_tablet_filter_typo_transform',
						'line_height' 	=> 'op_portfolio_tablet_filter_typo_line_height',
						'spacing' 		=> 'op_portfolio_tablet_filter_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_tablet_filter_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_filter_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_tablet_filter_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_filter_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_tablet_title_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the title.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_tablet_title_typo_font_size',
						'transform' 	=> 'op_portfolio_tablet_title_typo_transform',
						'line_height' 	=> 'op_portfolio_tablet_title_typo_line_height',
						'spacing' 		=> 'op_portfolio_tablet_title_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_tablet_title_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_title_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_tablet_title_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_title_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_tablet_category_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_tablet_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the category.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_tablet_category_typo_font_size',
						'transform' 	=> 'op_portfolio_tablet_category_typo_transform',
						'line_height' 	=> 'op_portfolio_tablet_category_typo_line_height',
						'spacing' 		=> 'op_portfolio_tablet_category_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_tablet_category_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_category_typo_transform', 		array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_tablet_category_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_tablet_category_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );
			
			$manager->register_section(
		        'op_portfolio_mobile_device',
		        array(
		            'label' => esc_html__( 'Mobile Device', 'ocean-portfolio' ),
		            'icon'  => 'dashicons-smartphone'
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_columns', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Mobile Columns', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Choose your columns number for mobile device.', 'ocean-portfolio' ),
					'attr'    		=> array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_mobile_columns', // Same as control name.
		        array(
		            'sanitize_callback' => 'op_portfolio_sanitize_absint',
		            'default' 			=> '1',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_item_margin', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Margin', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Enter a custom margin between your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_mobile_item_margin', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_item_padding', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Padding', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a padding for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_mobile_item_padding', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_item_border_radius', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Radius', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border radius for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_mobile_item_border_radius', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_item_border_width', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Item Border Width', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Add a border for your items. Format: top/right/bottom/left.', 'ocean-portfolio' ),
		        )
		    );
			
			$manager->register_setting(
		        'op_portfolio_mobile_item_border_width', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'op_portfolio_mobile_filter_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Filter Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the filter.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_mobile_filter_typo_font_size',
						'transform' 	=> 'op_portfolio_mobile_filter_typo_transform',
						'line_height' 	=> 'op_portfolio_mobile_filter_typo_line_height',
						'spacing' 		=> 'op_portfolio_mobile_filter_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_mobile_filter_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_filter_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_mobile_filter_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_filter_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_mobile_title_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Title Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the title.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_mobile_title_typo_font_size',
						'transform' 	=> 'op_portfolio_mobile_title_typo_transform',
						'line_height' 	=> 'op_portfolio_mobile_title_typo_line_height',
						'spacing' 		=> 'op_portfolio_mobile_title_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_mobile_title_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_title_typo_transform', 	array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_mobile_title_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_title_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		    $manager->register_control(
		        'op_portfolio_mobile_category_typo', // Same as setting name.
		        array(
		            'section' 		=> 'op_portfolio_mobile_device',
		            'type'    		=> 'typography',
		            'label'   		=> esc_html__( 'Category Typography', 'ocean-portfolio' ),
		            'description'   => esc_html__( 'Typography for the category.', 'ocean-portfolio' ),
		            'settings'    	=> array(
						'size'        	=> 'op_portfolio_mobile_category_typo_font_size',
						'transform' 	=> 'op_portfolio_mobile_category_typo_transform',
						'line_height' 	=> 'op_portfolio_mobile_category_typo_line_height',
						'spacing' 		=> 'op_portfolio_mobile_category_typo_spacing'
					),
					'l10n'        	=> array(),
		        )
		    );
				
			$manager->register_setting( 'op_portfolio_mobile_category_typo_font_size',   	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_category_typo_transform', 		array( 'sanitize_callback' => 'sanitize_key', ) );
			$manager->register_setting( 'op_portfolio_mobile_category_typo_line_height', 	array( 'sanitize_callback' => 'sanitize_text_field', ) );
			$manager->register_setting( 'op_portfolio_mobile_category_typo_spacing', 		array( 'sanitize_callback' => 'sanitize_text_field', ) );

		}

		/**
		 * Add shorcode metabox
		 * The $this variable is not used to get the display_meta_box() function because it doesn't work on older PHP version.
		 *
		 * @since 1.0.0
		 */
		public static function add_meta_box( $post ) {

			add_meta_box(
				'op-shortcode-metabox',
				esc_html__( 'Shortcode', 'ocean-portfolio' ),
				array( 'OceanWP_Portfolio_Shortcode_Generator', 'display_meta_box' ),
				'portfolio_shortcodes',
				'side',
				'low'
			);

		}

		/**
		 * Add shorcode metabox
		 *
		 * @since 1.0.0
		 */
		public static function display_meta_box( $post ) { ?>

			<input type="text" class="widefat" value='[oceanwp_portfolio id="<?php echo $post->ID; ?>"]' readonly />

		<?php
		}

	}

}
new OceanWP_Portfolio_Shortcode_Generator();