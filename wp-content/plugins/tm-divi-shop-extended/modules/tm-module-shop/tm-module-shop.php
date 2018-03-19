<?php

	if (class_exists('ET_Builder_Module')){

		// Front-end scripts and styles
		function tm_load_assets() {

			$plugin_dir_uri = plugin_dir_url( __FILE__ );

		    // Slick Lib
		    wp_enqueue_style( 'tm-slick-css', $plugin_dir_uri .  'assets/slick/slick.css');
		    wp_enqueue_style( 'tm-slick-theme-css', $plugin_dir_uri . 'assets/slick/slick-theme.css');
		    wp_enqueue_script( 'tm-slick-js', $plugin_dir_uri . 'assets/slick/slick.min.js');

		    // Custom
		    wp_enqueue_script( 'tm-module-shop-js', $plugin_dir_uri . 'assets/js/tm-module-shop.js' );
		    wp_enqueue_style( 'tm-module-shop-css', $plugin_dir_uri . 'assets/css/tm-module-shop.css');
		    wp_enqueue_style( 'tm-module-shop-font-awesome', $plugin_dir_uri . 'assets/css/font-awesome.min.css' );
		}

		// Admin panel styles
		function tm_load_admin_assets() {

		  $plugin_dir_uri = plugin_dir_url( __FILE__ ); 
		  wp_enqueue_style(
		      'tm-module-shop-admin-css',
		      $plugin_dir_uri . 'assets/css/tm-module-shop-admin.css',
		      array() 
		  );

		  // Dynamic JS vars (admin) 
		  wp_enqueue_script('tm-admin-js-dinamyc-vars', 
		    $plugin_dir_uri . 'assets/js/tm-module-shop-admin.js',
		    array(),
		    false,
		    true
		  );
		}

		if (!class_exists('ET_Builder_Module_TantoShop')){
			
			class ET_Builder_Module_TantoShop extends ET_Builder_Module {
					
					function init() {
						add_action( 'wp_enqueue_scripts', 'tm_load_assets' );
						add_action( 'admin_enqueue_scripts', 'tm_load_admin_assets' );
						$this->name       = esc_html__( 'Tanto Shop', 'et_builder' );
						$this->slug       = 'et_pb_shop_tm';
						$this->fb_support = true;
						$this->whitelisted_fields = array(
							'type',
							'grid_mode',
							'posts_number',
							'columns_number',
							'include_categories',
							'orderby',
							'admin_label',
							'module_id',
							'module_class',
							'sale_badge_color',
							'hover_overlay_color',
							'hover_icon',
							'icon_color',
							'icon_hover_color',
							'button_bg_color',
							'button_bg_hover_color',
							'buttons_right',
							'slider_arrows_color',
							'product_desc',
							'filter_active',
							'filter_font_color',
							'filter_bg_color',
							'filter_font_color_hover',
							'filter_bg_color_hover',
							'filter_border_color',
							'filter_border_color_hover',
						);
						$this->fields_defaults = array(
							'type'           => array( 'recent' ),
							'posts_number'   => array( '12', 'add_default_setting' ),
							'columns_number' => array( '0' ),
							'orderby'        => array( 'menu_order' ),
							'grid_mode'		 => array('carrousel'),
						);
						$this->main_css_element = '%%order_class%%.et_pb_shop_tm';
						$this->options_toggles = array(

							'general'  => array(
								'toggles' => array(
									'main_content' => esc_html__( 'Content', 'et_builder' ),
								),
							),

							'advanced' => array(
								'toggles' => array(
									'overlay' => esc_html__( 'Overlay', 'et_builder' ),
									'badge'   => esc_html__( 'Sale Badge', 'et_builder' ),
									'slider'   => esc_html__( 'Carrousel', 'et_builder' ),
									'filter'   => esc_html__( 'Category Filter', 'et_builder' ),
								),
							),
						);



						$this->advanced_options = array(
							'fonts' => array(
								'title' => array(
									'label'    => esc_html__( 'Title', 'et_builder' ),
									'css'      => array(
										'main' => "{$this->main_css_element} .woocommerce ul.products li.product h3, {$this->main_css_element} .woocommerce ul.products li.product h1, {$this->main_css_element} .woocommerce ul.products li.product h2, {$this->main_css_element} .woocommerce ul.products li.product h4, {$this->main_css_element} .woocommerce ul.products li.product h5, {$this->main_css_element} .woocommerce ul.products li.product h6",
										'important' => 'plugin_only',
									),
									'font_size' => array(
									'default' => '16px',
									),

									'line_height' => array(
										'range_settings' => array(
											'min'  => '1',
											'max'  => '100',
											'step' => '1',
										),

										'default' => '1em'
									),
								),

								'price' => array(
									'label'    => esc_html__( 'Price', 'et_builder' ),
									'css'      => array(
										'main' => "{$this->main_css_element} .woocommerce ul.products li.product .price, {$this->main_css_element} .woocommerce ul.products li.product .price .amount",
									),

									'line_height' => array(
										'range_settings' => array(
											'min'  => '1',
											'max'  => '100',
											'step' => '1',
										),
										'default' => '26px'
									),

									'font_size' => array(
									'default' => '14px',
									),
								),

								'product_desc' => array(
									'label'    => esc_html__( 'Product Description', 'et_builder' ),
									'css'      => array(
										'main' => "{$this->main_css_element} .woocommerce ul.products li.product p",
									),

									'line_height' => array(
										'range_settings' => array(
											'min'  => '1',
											'max'  => '100',
											'step' => '1',
										),
										'default' => '26px'
									),

									'font_size' => array(
									'default' => '14px',
									),
								),
							),
						);

						$this->custom_css_options = array(

							'product' => array(
								'label'    => esc_html__( 'Product', 'et_builder' ),
								'selector' => 'li.product',
							),

							'onsale' => array(
								'label'    => esc_html__( 'Onsale', 'et_builder' ),
								'selector' => 'li.product .onsale',
							),

							'image' => array(
								'label'    => esc_html__( 'Image', 'et_builder' ),
								'selector' => '.et_shop_image',
							),

							'overlay' => array(
								'label'    => esc_html__( 'Overlay', 'et_builder' ),
								'selector' => '.tm-backlayer',
							),

							'title' => array(
								'label'    => esc_html__( 'Title', 'et_builder' ),
								'selector' => $this->get_title_selector(),
							),

							'rating' => array(
								'label'    => esc_html__( 'Rating', 'et_builder' ),
								'selector' => '.star-rating',
							),

							'price' => array(
								'label'    => esc_html__( 'Price', 'et_builder' ),
								'selector' => 'li.product .price',
							),

							'product_desc' => array(
								'label'    => esc_html__( 'Product Description', 'et_builder' ),
								'selector' => 'li.product p.tm-short-desc',
							),

							'price_old' => array(
								'label'    => esc_html__( 'Old Price', 'et_builder' ),
								'selector' => 'li.product .price del span.amount',
							),

							);
						}



					function get_fields() {

					$fields = array(

						'type' => array(
							'label'           => esc_html__( 'Type', 'et_builder' ),
							'type'            => 'select',
							'option_category' => 'basic_option',
							'options'         => array(
								'recent'  => esc_html__( 'Recent Products', 'et_builder' ),
								'featured' => esc_html__( 'Featured Products', 'et_builder' ),
								'sale' => esc_html__( 'Sale Products', 'et_builder' ),
								'best_selling' => esc_html__( 'Best Selling Products', 'et_builder' ),
								'top_rated' => esc_html__( 'Top Rated Products', 'et_builder' ),
								'product_category' => esc_html__( 'Product Category', 'et_builder' ),
							),

							'description'      => esc_html__( 'Choose which type of products you would like to display.', 'et_builder' ),
							'toggle_slug'      => 'main_content',
							'computed_affects' => array(
								'__shop',
							),
						),

						'grid_mode' => array(
							'label'           => esc_html__( 'Layout', 'et_builder' ),
							'type'            => 'select',
							'option_category' => 'basic_option',
							'options'         => array(
								'carrousel'  => esc_html__( 'Carrousel', 'et_builder' ),
								'grid' => esc_html__( 'Grid', 'et_builder' ),
							),
							'description'      => esc_html__( 'Activate the grid mode? (Otherwise, the carrousel mode is set as default).' ),
							'toggle_slug'      => 'main_content',
							'computed_affects' => array(
								'__shop',
							),
						),

						'posts_number' => array(
							'label'             => esc_html__( 'Product Count', 'et_builder' ),
							'type'              => 'text',
							'option_category'   => 'configuration',
							'description'       => esc_html__( 'Control how many products are displayed.', 'et_builder' ),
							'computed_affects'  => array(
								'__shop',
							),
							'toggle_slug'       => 'main_content',
						),

						'include_categories'   => array(
							'label'            => esc_html__( 'Include Categories', 'et_builder' ),
							'type'             => 'basic_option',
							'renderer'         => 'et_builder_include_categories_shop_option',
							'renderer_options' => array(
								'use_terms'    => true,
								'term_name'    => 'product_cat',
							),

							'description'      => esc_html__( 'Choose which categories you would like to include.', 'et_builder' ),
							'taxonomy_name'    => 'product_category',
							'toggle_slug'      => 'main_content',
							'computed_affects' => array(
								'__shop',
							),
						),

						'columns_number' => array(
							'label'             => esc_html__( 'Columns Number', 'et_builder' ),
							'type'              => 'select',
							'option_category'   => 'layout',
							'options'           => array(
								'0' => esc_html__( 'default', 'et_builder' ),
								'6' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '6' ) ),
								'5' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '5' ) ),
								'4' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '4' ) ),
								'3' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '3' ) ),
								'2' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '2' ) ),
								'1' => esc_html__( '1 Column', 'et_builder' ),
							),

							'description'       => esc_html__( 'Choose how many columns to display (Minimum 3 for carroussel).', 'et_builder' ),
							'computed_affects'  => array(
								'__shop',
							),
							'toggle_slug'       => 'main_content',
						),

						'orderby' => array(

							'label'             => esc_html__( 'Order By', 'et_builder' ),

							'type'              => 'select',

							'option_category'   => 'configuration',

							'options'           => array(

								'menu_order'  => esc_html__( 'Default Sorting', 'et_builder' ),

								'popularity' => esc_html__( 'Sort By Popularity', 'et_builder' ),

								'rating' => esc_html__( 'Sort By Rating', 'et_builder' ),

								'date' => esc_html__( 'Sort By Date: Oldest To Newest', 'et_builder' ),

								'date-desc' => esc_html__( 'Sort By Date: Newest To Oldest', 'et_builder' ),

								'price' => esc_html__( 'Sort By Price: Low To High', 'et_builder' ),

								'price-desc' => esc_html__( 'Sort By Price: High To Low', 'et_builder' ),

							),

							'description'       => esc_html__( 'Choose how your products should be ordered.', 'et_builder' ),

							'computed_affects'  => array(

								'__shop',

							),

							'toggle_slug'       => 'main_content',

						),

						'sale_badge_color' => array(

							'label'             => esc_html__( 'Sale Badge Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'badge',

						),

						'icon_color' => array(

							'label'             => esc_html__( 'Icon Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						),

						'icon_hover_color' => array(

							'label'             => esc_html__( 'Icon Hover Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						),

						'button_bg_color' => array(

							'label'             => esc_html__( 'Icon Background Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						),

						'button_bg_hover_color' => array(

							'label'             => esc_html__( 'Icon Hover Background Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						),

						'buttons_right' => array(

							'label' => esc_html('Icons On The Right', 'et_builder' ),

							'type'              => 'yes_no_button',

							'options' => array(

								'off' => esc_html__( "No", 'et_builder' ),

								'on'  => esc_html__( 'Yes', 'et_builder' ),

							),

							'description' => esc_html__( 'Show icons on the right hand side.', 'et_builder' ),

							'computed_affects'  => array(

								'__shop',

							),

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						), 



						'slider_arrows_color' => array(

							'label'             => esc_html__( 'Carrousel Arrows Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'slider',

						),



						'hover_overlay_color' => array(

							'label'             => esc_html__( 'Hover Overlay Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'overlay',

						),



						'filter_active' => array(

							'label' => esc_html('Show Filter', 'et_builder' ),

							'type'              => 'yes_no_button',

							'options' => array(

								'off' => esc_html__( "No", 'et_builder' ),

								'on'  => esc_html__( 'Yes', 'et_builder' ),

							),

							'description' => esc_html__( 'Show category filter above products.', 'et_builder' ),

							'computed_affects'  => array(

								'__shop',

							),

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						), 



						'filter_font_color' => array(

							'label'             => esc_html__( 'Font Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),

						'filter_font_color_hover' => array(

							'label'             => esc_html__( 'Hover Font Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),

						'filter_bg_color' => array(

							'label'             => esc_html__( 'Background Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),

						'filter_bg_color_hover' => array(

							'label'             => esc_html__( 'Hover Background Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),

						'filter_border_color' => array(

							'label'             => esc_html__( 'Border Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),

						'filter_border_color_hover' => array(

							'label'             => esc_html__( 'Hover Border Color', 'et_builder' ),

							'type'              => 'color-alpha',

							'custom_color'      => true,

							'tab_slug'          => 'advanced',

							'toggle_slug'       => 'filter',

						),



						'disabled_on' => array(

							'label'           => esc_html__( 'Disable on', 'et_builder' ),

							'type'            => 'multiple_checkboxes',

							'options'         => array(

								'phone'   => esc_html__( 'Phone', 'et_builder' ),

								'tablet'  => esc_html__( 'Tablet', 'et_builder' ),

								'desktop' => esc_html__( 'Desktop', 'et_builder' ),

							),

							'additional_att'  => 'disable_on',

							'option_category' => 'configuration',

							'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),

							'tab_slug'        => 'custom_css',

							'toggle_slug'     => 'visibility',

						),

						'admin_label' => array(

							'label'       => esc_html__( 'Admin Label', 'et_builder' ),

							'type'        => 'text',

							'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),

							'toggle_slug' => 'admin_label',

						),

						'module_id' => array(

							'label'           => esc_html__( 'CSS ID', 'et_builder' ),

							'type'            => 'text',

							'option_category' => 'configuration',

							'tab_slug'        => 'custom_css',

							'toggle_slug'     => 'classes',

							'option_class'    => 'et_pb_custom_css_regular',

						),

						'module_class' => array(

							'label'           => esc_html__( 'CSS Class', 'et_builder' ),

							'type'            => 'text',

							'option_category' => 'configuration',

							'tab_slug'        => 'custom_css',

							'toggle_slug'     => 'classes',

							'option_class'    => 'et_pb_custom_css_regular',

						),

						'__shop' => array(

							'type'                => 'computed',

							'computed_callback'   => array( 'ET_Builder_Module_Shop', 'get_shop_html' ),

							'computed_depends_on' => array(

								'type',

								'grid_mode',

								'include_categories',

								'posts_number',

								'orderby',

								'columns_number',

								

							),

							'computed_minimum' => array(

								'posts_number',

							),

							),

						);

						return $fields;
					}



					function add_product_class_name( $classes ) {

						$classes[] = 'product';
						return $classes;
					}

					function get_shop() {
						$type               = $this->shortcode_atts['type'];
						$include_categories = $this->shortcode_atts['include_categories'];
						$posts_number       = $this->shortcode_atts['posts_number'];
						$orderby            = $this->shortcode_atts['orderby'];
						$columns            = $this->shortcode_atts['columns_number'];
						$filter_active 		= $this->shortcode_atts['filter_active'];

						$woocommerce_shortcodes_types = array(

							'recent'           => 'recent_products',
							'featured'         => 'featured_products',
							'sale'             => 'sale_products',
							'best_selling'     => 'best_selling_products',
							'top_rated'        => 'top_rated_products',
							'product_category' => 'product_category',

						);

						/* TESTING */
						$choosed_categories = explode ( ",", $include_categories );
						$swap_categories = array();
						foreach ($choosed_categories as $value) {
							if( $term = get_term_by( 'id', $value, 'product_cat' ) ){
							    array_push($swap_categories, $term->name);
							}
						}
						$include_categories = implode(',', $swap_categories);


						/**

						 * Actually, orderby parameter used by WooCommerce shortcode is equal to orderby parameter used by WP_Query

						 * Hence customize WooCommerce' product query via modify_woocommerce_shortcode_products_query method

						 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/#section-5

						 */

						$modify_woocommerce_query = 'best_selling' !== $type && in_array( $orderby, array( 'menu_order', 'price', 'price-desc', 'date', 'date-desc', 'rating', 'popularity' ) );



						if ( $modify_woocommerce_query ) {

							add_filter( 'woocommerce_shortcode_products_query', array( $this, 'modify_woocommerce_shortcode_products_query' ), 10, 2 );

						}



						do_action( 'et_pb_shop_before_print_shop' );



						// Adds Add to cart button to products with hook

						add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 20 );



						if(!function_exists('tm_custom_add_to_cart_text')){

							function tm_custom_add_to_cart_text(){

								return __('', 'woocommerce');

							}

						}

						if(!function_exists('tm_category_name')){

							function tm_category_name(){

								global $product;

								

								$prod_cats = wc_get_product_category_list( $product->get_id() );
								$cat_list = wc_get_product_category_list( $product->get_id(),',' );
								
								//$cat_list = '<span class="tm-product-categories" data-cat-list="'.strtolower(strip_tags( $cat_list )).'">'.strtolower(strip_tags( $cat_list )).'</span>';
								//echo strtolower(strip_tags( $cat_list ));
								//echo $product->get_categories( ',' , '<span class="tm-product-categories">','</span>');		
								$cat_list = '<span class="tm-product-categories">'.$cat_list.'</span>';
								echo $cat_list;
							}

						}

						if(!function_exists('woo_show_excerpt_shop_page')){

							/*Old $product->post->post_excerpt*/

							function woo_show_excerpt_shop_page() {

								global $product;

								echo '<p class="tm-short-desc">'.html_entity_decode( substr($product->get_short_description(), 0, 30) ).'</p><a

								href="https://www.facebook.com/sharer/sharer.php?u='.$product->get_permalink().'" class="button tm-share-product" title="Share"></a>';

							}

						}


						add_filter('woocommerce_product_add_to_cart_text', 'tm_custom_add_to_cart_text');

						add_action( 'woocommerce_after_shop_loop_item', 'woo_show_excerpt_shop_page', 5 );

						add_action( 'woocommerce_after_shop_loop_item', 'tm_category_name', 6 );




						
						$shop = do_shortcode(

							sprintf( '[%1$s limit="%2$s" orderby="%3$s" columns="%4$s" category="%5$s"]',

								esc_html( $woocommerce_shortcodes_types[$type] ),

								esc_attr( $posts_number ),

								esc_attr( $orderby ),

								esc_attr( $columns ),

								esc_attr( $include_categories )

							)

						);
						
						

						/*$shop = do_shortcode(
							sprintf( '[%1$s per_page="%2$s" orderby="%3$s" columns="%4$s" category="%5$s"]',
								esc_html( $woocommerce_shortcodes_types[ $type ] ),
								esc_attr( $posts_number ),
								esc_attr( $orderby ),
								esc_attr( $columns ),
								esc_attr( explode ( ",", $include_categories ))
							)
						);*/

						/*if(!function_exists('free_for_all')){
							function free_for_all(){
								print_r($include_categories);
							}
						}
						add_action('et_pb_shop_after_print_shop');
						*/

						// Remove function add to cart button to products from hook

						remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 20 );

						remove_action( 'woocommerce_after_shop_loop_item', 'woo_show_excerpt_shop_page');

						remove_action( 'woocommerce_after_shop_loop_item', 'tm-product-categories');

						remove_filter('woocommerce_product_add_to_cart_text', 'tm_custom_add_to_cart_text');



						do_action( 'et_pb_shop_after_print_shop' );



						/**

						 * Remove modify_woocommerce_shortcode_products_query method after being used

						 */

						if ( $modify_woocommerce_query ) {

							remove_filter( 'woocommerce_shortcode_products_query', array( $this, 'modify_woocommerce_shortcode_products_query' ) );



							if ( function_exists( 'WC' ) ) {

								WC()->query->remove_ordering_args(); // remove args added by woocommerce to avoid errors in sql queries performed afterwards

							}

						}



						return $shop;

					}



					/**

					 * Get shop HTML for shop module

					 *

					 * @param array   arguments that affect shop output

					 * @param array   passed conditional tag for update process

					 * @param array   passed current page params

					 * @return string HTML markup for shop module

					 */

					static function get_shop_html( $args = array(), $conditional_tags = array(), $current_page = array() ) {

						$shop = new self();



						do_action( 'et_pb_get_shop_html_before' );



						$shop->shortcode_atts = $args;



						// Force product loop to have 'product' class name. It appears that 'product' class disappears

						// when $this->get_shop() is being called for update / from admin-ajax.php

						add_filter( 'post_class', array( $shop, 'add_product_class_name' ) );



						// Get product HTML

						$output = $shop->get_shop();



						// Remove 'product' class addition to product loop's post class

						remove_filter( 'post_class', array( $shop, 'add_product_class_name' ) );



						do_action( 'et_pb_get_shop_html_after' );



						return $output;

					}





					// WooCommerce changed the title tag from h3 to h2 in 3.0.0

					function get_title_selector() {

						$title_selector = 'li.product h3';



						if ( class_exists( 'WooCommerce' ) ) {

							global $woocommerce;



							if ( version_compare( $woocommerce->version, '3.0.0', '>=' ) ) {

								$title_selector = 'li.product h2';

							}

						}



						return $title_selector;

					}



					function shortcode_callback( $atts, $content = null, $function_name ) {

						$data_icon = false;



						$module_id               = $this->shortcode_atts['module_id'];

						$module_class            = $this->shortcode_atts['module_class'];

						$type                    = $this->shortcode_atts['type'];

						$include_categories      = $this->shortcode_atts['include_categories'];

						$posts_number            = $this->shortcode_atts['posts_number'];

						$orderby                 = $this->shortcode_atts['orderby'];

						$columns                 = $this->shortcode_atts['columns_number'];

						$sale_badge_color        = $this->shortcode_atts['sale_badge_color'];

						$hover_overlay_color     = $this->shortcode_atts['hover_overlay_color'];

						$grid_mode				 = $this->shortcode_atts['grid_mode'];



						$icon_color        		 = $this->shortcode_atts['icon_color'];

						$icon_hover_color		 = $this->shortcode_atts['icon_hover_color'];

						$button_bg_color 		 = $this->shortcode_atts['button_bg_color'];

						$button_bg_hover_color 	 = $this->shortcode_atts['button_bg_hover_color'];

						$buttons_right 	 		 = $this->shortcode_atts['buttons_right'];

						$slider_arrows_color	 = $this->shortcode_atts['slider_arrows_color'];



						$filter_active 			 = $this->shortcode_atts['filter_active'];

						$filter_font_color 		 = $this->shortcode_atts['filter_font_color'];

						$filter_bg_color 		 = $this->shortcode_atts['filter_bg_color'];

						$filter_font_color_hover = $this->shortcode_atts['filter_font_color_hover'];

						$filter_bg_color_hover   = $this->shortcode_atts['filter_bg_color_hover'];

						$filter_border_color 	 = $this->shortcode_atts['filter_border_color'];

						$filter_border_color_hover 	 = $this->shortcode_atts['filter_border_color_hover'];


						/* TESTING */
						$choosed_categories = explode ( ",", $include_categories );
						$swap_categories = array();
						foreach ($choosed_categories as $value) {
							if( $term = get_term_by( 'id', $value, 'product_cat' ) ){
							    array_push($swap_categories, $term->name);
							}
						}
						$include_categories = implode(',', $swap_categories);




						$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );



						if ( '' !== $sale_badge_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% span.onsale, div.remodal span.onsale',

								'declaration' => sprintf(

									'background-color: %1$s !important;',

									esc_html( $sale_badge_color )

								),

							) );

						}



						// TM Icon color



						if ( '' !== $icon_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .button:before, %%order_class%% .added_to_cart:before',

								'declaration' => sprintf(

									'color: %1$s !important;',

									esc_html( $icon_color )

								),

							) );

						}



						if ( '' !== $icon_hover_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% ul.products li.product a.button:hover::before, %%order_class%%  ul.products li.product .added_to_cart:hover::before',

								'declaration' => sprintf(

									'color: %1$s !important;',

									esc_html( $icon_hover_color )

								),

							) );

						}



						if ( '' !== $button_bg_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .button',

								'declaration' => sprintf(

									'background-color: %1$s !important;',

									esc_html( $button_bg_color )

								),

							) );

						}



						if ( '' !== $button_bg_hover_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .button:hover',

								'declaration' => sprintf(

									'background-color: %1$s !important;',

									esc_html( $button_bg_hover_color )

								),

							) );

						}



						if ( '' !== $slider_arrows_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .slick-prev:before, %%order_class%% .slick-next:before',

								'declaration' => sprintf(

									'color: %1$s !important;',

									esc_html( $slider_arrows_color )

								),

							) );

						}



						if ( '' !== $hover_overlay_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-backlayer',

								'declaration' => sprintf(

									'background-color: %1$s !important;

									border-color: %1$s;',

									esc_html( $hover_overlay_color )

								),

							) );

						}



						if ( '' !== $hover_overlay_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-backlayer',

								'declaration' => sprintf(

									'background-color: %1$s !important;

									border-color: %1$s;',

									esc_html( $hover_overlay_color )

								),

							) );

						}



						if ( '' !== $filter_font_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button',

								'declaration' => sprintf(

									'color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_font_color )

								),

							) );

						}



						if ( '' !== $filter_font_color_hover ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button:hover',

								'declaration' => sprintf(

									'color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_font_color_hover )

								),

							) );

						}



						if ( '' !== $filter_bg_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button',

								'declaration' => sprintf(

									'background-color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_bg_color )

								),

							) );

						}



						if ( '' !== $filter_bg_color_hover ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button:hover',

								'declaration' => sprintf(

									'background-color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_bg_color_hover )

								),

							) );

						}



						if ( '' !== $filter_border_color ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button',

								'declaration' => sprintf(

									'border-color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_border_color )

								),

							) );

						}



						if ( '' !== $filter_border_color_hover ) {

							ET_Builder_Element::set_style( $function_name, array(

								'selector'    => '%%order_class%% .tm-cat-button:hover',

								'declaration' => sprintf(

									'border-color: %1$s !important;

									transition:0.4s;',

									esc_html( $filter_border_color_hover )

								),

							) );

						}



						/**Productos**/

							echo "<script>var slider_columns = ".$columns."; </script>";

						/**Productos**/

						$include_categories = $this->shortcode_atts['include_categories'];

						/* TESTING */
						$choosed_categories = explode ( ",", $include_categories );
						$swap_categories = array();
						foreach ($choosed_categories as $value) {
							if( $term = get_term_by( 'id', $value, 'product_cat' ) ){
							    array_push($swap_categories, $term->name);
							}
						}
						$include_categories = implode(',', $swap_categories);

						//Generate category filter buttons
						$categories_array = explode(',',$include_categories);
						$category_filter_html = '';
						$category_filter_html .= '<div class="tm-cat-filter"><span class="tm-cat-button tm-cat-all tm-cat-button-active" data-cat="all">All</span>';
						foreach ($categories_array as $key => $value) {
							if( $value && ($value !== '') ){
								global $wpdb;
								$result = $wpdb->get_results('SELECT name FROM '.$wpdb->prefix.'terms WHERE slug="'.$value.'" LIMIT 1');
								//echo $value.'<br>';
								//print_r($result);
								//echo '<br>'.var_dump($result);
								//echo '<br>==============<br>';

								
								//$cat = get_category_by_slug( $value );
								//echo $cat;
								//$category_filter_html .= '<span class="tm-cat-button" data-cat="'.$value.'" >'.$value.'</span>';
								if( isset($result[0]) ){
									$category_filter_html .= '<span class="tm-cat-button" data-cat="'.$result[0]->name.'" >'.$result[0]->name.'</span>';
								}
							}
						}
						$category_filter_html .= '</div>';
						
						
						$output = sprintf(

						       '<div%2$s class="et_pb_module et_pb_shop_tm et_pb_shop%3$s%4$s %6$s %7$s %8$s"%5$s data-cats="%9$s" data-columns="%10$s">
							   	%11$s
						        %1$s
						
						        <span class="tm-filter-categories" style="display:none;" data-categories="%9$s"></span>
								
						       </div>',
						
						       $this->get_shop(),
						       ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
						       ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
						       '0' === $columns ? ' et_pb_shop_grid' : '',
						       $data_icon,
						       'carrousel' === $grid_mode ? 'tm_carrousel_active' : '',
						       'on' === $buttons_right ? 'tm_buttons_right' : '',
						       (('on' === $filter_active) && ( 'carrousel' !== $grid_mode)) ? 'tm-filter-active' : '',
						       $include_categories,
						       $columns,
							  ( ('on' === $filter_active) && ( 'carrousel' !== $grid_mode) && (count($categories_array) > 0 ) )? $category_filter_html : ''
      						);


						return $output;

					}



					/**

					 * Modifying WooCommerce' product query filter based on $orderby value given

					 * @see WC_Query->get_catalog_ordering_args()

					 */

					function modify_woocommerce_shortcode_products_query( $args, $atts ) {



						if ( function_exists( 'WC' ) ) {

							// Default to ascending order

							$orderby = $this->shortcode_atts['orderby'];

							$order   = 'ASC';



							// Switch to descending order if orderby is 'price-desc' or 'date-desc'

							if ( in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ) {

								$order = 'DESC';

							}

							// Supported orderby arguments (as defined by WC_Query->get_catalog_ordering_args() ): rand | date | price | popularity | rating | title

							$orderby = in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ? str_replace( '-desc', '', $orderby ) : $orderby;

							// Get arguments for the given non-native orderby

							$query_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

							// Confirm that returned argument isn't empty then merge returned argument with default argument

							if( is_array( $query_args ) && ! empty( $query_args ) ) {

								$args = array_merge( $args, $query_args );

							}

						}



						return $args;

					}

			}

		}

		new ET_Builder_Module_TantoShop();

		$et_builder_module_shop_tm = new ET_Builder_Module_TantoShop();

		add_shortcode( 'et_pb_shop_tm', array($et_builder_module_shop_tm, '_shortcode_callback') );

	}



?>