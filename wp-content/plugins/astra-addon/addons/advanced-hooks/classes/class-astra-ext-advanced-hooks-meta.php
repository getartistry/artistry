<?php
/**
 * Astra Advanced Hooks Bar Post Meta Box
 *
 * @package   Astra Pro
 */

/**
 * Meta Boxes setup
 */
if ( ! class_exists( 'Astra_Ext_Advanced_Hooks_Meta' ) ) {

	/**
	 * Meta Boxes setup
	 */
	class Astra_Ext_Advanced_Hooks_Meta {


		/**
		 * Instance
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Meta Option
		 *
		 * @var $meta_option
		 */
		private static $meta_option;

		/**
		 * Theme Layouts hooks.
		 *
		 * @var $astra_layouts
		 */
		public static $astra_layouts = array();

		/**
		 * Theme Action hooks.
		 *
		 * @var $astra_hooks
		 */
		public static $astra_hooks = array();

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			/**
			 * Filter for the 'Layouts' in Custom Layouts selection.
			 *
			 * @since 1.5.0
			 */
			self::$astra_layouts = apply_filters(
				'astra_custom_layouts_layout',
				array(
					'header'   => array(
						'title' => __( 'Header', 'astra-addon' ),
					),
					'footer'   => array(
						'title' => __( 'Footer', 'astra-addon' ),
					),
					'404-page' => array(
						'title' => __( '404 Page', 'astra-addon' ),
					),
					'hooks'    => array(
						'title' => __( 'Hooks', 'astra-addon' ),
					),
				)
			);

			/**
			 * Filter for the 'Hooks' in Custom Layouts selection.
			 *
			 * @since 1.5.0
			 */
			self::$astra_hooks = apply_filters(
				'astra_custom_layouts_hooks',
				array(
					'head'    => array(
						'title' => __( 'Head', 'astra-addon' ),
						'hooks' => array(
							'astra_html_before' => array(
								'title'       => 'html_before',
								'description' => __( 'astra_html_before - Action to add your content or snippet just before the opening of <html> tag.', 'astra-addon' ),
							),
							'astra_head_top'    => array(
								'title'       => 'head_top',
								'description' => __( 'astra_head_top - Action to add your content or snippet at top of the <head> tag.', 'astra-addon' ),
							),
							'astra_head_bottom' => array(
								'title'       => 'head_bottom',
								'description' => __( 'astra_head_bottom - Action to add your content or snippet at bottom of the <head> tag.', 'astra-addon' ),
							),
							'wp_head'           => array(
								'title'       => 'wp_head',
								'description' => __( 'wp_head - Action to add custom style, script and meta at the bottom of <head> tag.', 'astra-addon' ),
							),
						),
					),
					'header'  => array(
						'title' => __( 'Header', 'astra-addon' ),
						'hooks' => array(
							'astra_body_top'               => array(
								'title'       => 'body_top',
								'description' => __( 'astra_body_top - Action to add your content or snippet at top of the <body> tag.', 'astra-addon' ),
							),
							'astra_header_before'          => array(
								'title'       => 'header_before',
								'description' => __( 'astra_header_before - Action to add your content or snippet just before the opening <header> tag.', 'astra-addon' ),
							),
							'astra_masthead_top'           => array(
								'title'       => 'masthead_top',
								'description' => __( 'astra_masthead_top - Action to add your content or snippet at  top of the <header> tag.', 'astra-addon' ),
							),
							'astra_main_header_bar_top'    => array(
								'title'       => 'main_header_bar_top',
								'description' => __( 'astra_main_header_bar_top - Action to add your content or snippet at top of the Main header.', 'astra-addon' ),
							),
							'astra_masthead_content'       => array(
								'title'       => 'masthead_content',
								'description' => __( 'astra_masthead_content - Action to add your content or snippet in <header> tag.', 'astra-addon' ),
							),
							'astra_masthead_toggle_buttons_before' => array(
								'title'       => 'masthead_toggle_buttons_before',
								'description' => __( 'astra_masthead_toggle_buttons_before - Action to add your content or snippet before responsive menu toggle button.', 'astra-addon' ),
							),
							'astra_masthead_toggle_buttons_after' => array(
								'title'       => 'masthead_toggle_buttons_after',
								'description' => __( 'astra_masthead_toggle_buttons_after - Action to add your content or snippet after responsive menu toggle button.', 'astra-addon' ),
							),
							'astra_main_header_bar_bottom' => array(
								'title'       => 'main_header_bar_bottom',
								'description' => __( 'astra_main_header_bar_bottom - Action to add your content or snippet after at bottom of the Main header.', 'astra-addon' ),
							),
							'astra_masthead_bottom'        => array(
								'title'       => 'masthead_bottom',
								'description' => __( 'astra_masthead_bottom - Action to add your content or snippet at  bottom of the <header> tag.', 'astra-addon' ),
							),
							'astra_header_after'           => array(
								'title'       => 'header_after',
								'description' => __( 'astra_header_after - Action to add your content or snippet after the closing <header> tag.', 'astra-addon' ),
							),
						),
					),
					'content' => array(
						'title' => __( 'Content', 'astra-addon' ),
						'hooks' => array(
							'astra_content_before'         => array(
								'title'       => 'content_before',
								'description' => __( 'astra_content_before - Action to add your content or snippet before main content.', 'astra-addon' ),
							),
							'astra_content_top'            => array(
								'title'       => 'content_top',
								'description' => __( 'astra_content_top - Action to add your content or snippet at top of main content.', 'astra-addon' ),
							),
							'astra_primary_content_top'    => array(
								'title'       => 'primary_content_top',
								'description' => __( 'astra_primary_content_top - Action to add your content or snippet at top of the primary content.', 'astra-addon' ),
							),
							'astra_content_loop'           => array(
								'title'       => 'content_loop',
								'description' => __( 'astra_content_loop - Action to add your content or snippet at top of the primary content loop.', 'astra-addon' ),
							),
							'astra_template_parts_content_none' => array(
								'title'       => 'template_parts_content_none',
								'description' => __( 'astra_template_parts_content_none - Action to add your content or snippet at top of the primary content.', 'astra-addon' ),
							),
							'astra_content_while_before'   => array(
								'title'       => 'content_while_before',
								'description' => __( 'astra_content_while_before - Action to add your content or snippet before loop start.', 'astra-addon' ),
							),
							'astra_template_parts_content_top' => array(
								'title'       => 'template_parts_content_top',
								'description' => __( 'astra_template_parts_content_top - Action to add your content or snippet at top of the primary template content.', 'astra-addon' ),
							),
							'astra_template_parts_content' => array(
								'title'       => 'template_parts_content',
								'description' => __( 'astra_template_parts_content - Action to add your content or snippet at top of the primary template content.', 'astra-addon' ),
							),
							'astra_entry_before'           => array(
								'title'       => 'entry_before',
								'description' => __( 'astra_entry_before - Action to add your content or snippet before <article> tag.', 'astra-addon' ),
							),
							'astra_entry_top'              => array(
								'title'       => 'entry_top',
								'description' => __( 'astra_entry_top - Action to add your content or snippet at top of the <article> tag.', 'astra-addon' ),
							),
							'astra_single_header_before'   => array(
								'title'       => 'single_header_before',
								'description' => __( 'astra_single_header_before - Action to add your content or snippet before single post header.', 'astra-addon' ),
							),
							'astra_single_header_top'      => array(
								'title'       => 'single_header_top',
								'description' => __( 'astra_single_header_top - Action to add your content or snippet at top of the single post header.', 'astra-addon' ),
							),
							'astra_single_header_bottom'   => array(
								'title'       => 'single_header_bottom',
								'description' => __( 'astra_single_header_bottom - Action to add your content or snippet at bottom of the single post header.', 'astra-addon' ),
							),
							'astra_single_header_after'    => array(
								'title'       => 'single_header_after',
								'description' => __( 'astra_single_header_after - Action to add your content or snippet after single post header.', 'astra-addon' ),
							),
							'astra_entry_content_before'   => array(
								'title'       => 'entry_content_before',
								'description' => __( 'astra_entry_content_before - Action to add your content or snippet before post content.', 'astra-addon' ),
							),
							'astra_entry_content_after'    => array(
								'title'       => 'entry_content_after',
								'description' => __( 'astra_entry_content_after - Action to add your content or snippet after post content', 'astra-addon' ),
							),
							'astra_entry_bottom'           => array(
								'title'       => 'entry_bottom',
								'description' => __( 'astra_entry_bottom - Action to add your content or snippet at bottom of the <article> tag.', 'astra-addon' ),
							),
							'astra_entry_after'            => array(
								'title'       => 'entry_after',
								'description' => __( 'astra_entry_after - Action to add your content or snippet after closing <article> tag.', 'astra-addon' ),
							),
							'astra_template_parts_content_bottom' => array(
								'title'       => 'template_parts_content_bottom',
								'description' => __( 'astra_template_parts_content_bottom - Action to add your content or snippet after closing <article> tag.', 'astra-addon' ),
							),
							'astra_primary_content_bottom' => array(
								'title'       => 'primary_content_bottom',
								'description' => __( 'astra_primary_content_bottom - Action to add your content or snippet at bottom of the primary content.', 'astra-addon' ),
							),
							'astra_content_while_after'    => array(
								'title'       => 'content_while_after',
								'description' => __( 'astra_content_while_after - Action to add your content or snippet after loop end.', 'astra-addon' ),
							),
							'astra_content_bottom'         => array(
								'title'       => 'content_bottom',
								'description' => __( 'astra_content_bottom - Action to add your content or snippet at bottom of the main content.', 'astra-addon' ),
							),
							'astra_content_after'          => array(
								'title'       => 'content_after',
								'description' => __( 'astra_content_after - Action to add your content or snippet after main content.', 'astra-addon' ),
							),
						),
					),
					'comment' => array(
						'title' => __( 'Comment', 'astra-addon' ),
						'hooks' => array(
							'astra_comments_before' => array(
								'title'       => 'comments_before',
								'description' => __( 'astra_comments_before - Action to add your content or snippet before opening of comment start.', 'astra-addon' ),
							),
							'astra_comments_after'  => array(
								'title'       => 'comments_after',
								'description' => __( 'astra_comments_after - Action to add your content or snippet after closing of comment wrapper.', 'astra-addon' ),
							),
						),
					),
					'sidebar' => array(
						'title' => __( 'Sidebar', 'astra-addon' ),
						'hooks' => array(
							'astra_sidebars_before' => array(
								'title'       => 'sidebars_before',
								'description' => __( 'astra_sidebars_before - Action to add your content or snippet before opening of sidebar wrapper.', 'astra-addon' ),
							),
							'astra_sidebars_after'  => array(
								'title'       => 'sidebars_after',
								'description' => __( 'astra_sidebars_after - Action to add your content or snippet after closing of sidebar wrapper.', 'astra-addon' ),
							),
						),
					),
					'footer'  => array(
						'title' => __( 'Footer', 'astra-addon' ),
						'hooks' => array(
							'astra_footer_before'         => array(
								'title'       => 'footer_before',
								'description' => __( 'astra_footer_before - Action to add your content or snippet before the opening <footer> tag.', 'astra-addon' ),
							),
							'astra_footer_content_top'    => array(
								'title'       => 'footer_content_top',
								'description' => __( 'astra_footer_content_top - Action to add your content or snippet at top of the <footer> tag.', 'astra-addon' ),
							),
							'astra_footer_inside_container_top' => array(
								'title'       => 'footer_inside_container_top',
								'description' => __( 'astra_footer_inside_container_top - Action to add your content or snippet at top of the footer container.', 'astra-addon' ),
							),
							'astra_footer_inside_container_bottom' => array(
								'title'       => 'footer_inside_container_bottom',
								'description' => __( 'astra_footer_inside_container_bottom - Action to add your content or snippet at bottom of the footer container.', 'astra-addon' ),
							),
							'astra_footer_content_bottom' => array(
								'title'       => 'footer_content_bottom',
								'description' => __( 'astra_footer_content_bottom - Action to add your content or snippet at bottom of the <footer> tag.', 'astra-addon' ),
							),
							'astra_footer_after'          => array(
								'title'       => 'footer_after',
								'description' => __( 'astra_footer_after - Action to add your content or snippet after the closing <footer> tag.', 'astra-addon' ),
							),
							'astra_body_bottom'           => array(
								'title'       => 'body_bottom',
								'description' => __( 'astra_body_bottom - Action to add your content or snippet at bottom of the <body> tag.', 'astra-addon' ),
							),
							'wp_footer'                   => array(
								'title'       => 'wp_footer',
								'description' => __( 'wp_footer - Action to add your content or snippet at end of the document.', 'astra-addon' ),
							),
						),
					),
				)
			);

			// If plugin - 'WooCommerce'.
			if ( class_exists( 'WooCommerce' ) ) {
				self::$astra_hooks['woo-global'] = array(
					'title' => __( 'WooCommerce - Global', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_before_main_content'  => array(
							'title'       => 'before_main_content',
							'description' => __( 'Action to add your content before the WooCommerce main content.', 'astra-addon' ),
						),
						'woocommerce_after_main_content'   => array(
							'title'       => 'after_main_content',
							'description' => __( 'Action to add your content after the WooCommerce main content.', 'astra-addon' ),
						),
						'woocommerce_sidebar'              => array(
							'title'       => 'sidebar',
							'description' => __( 'Action to add your content on WooCommerce sidebar action.', 'astra-addon' ),
						),
						'woocommerce_breadcrumb'           => array(
							'title'       => 'breadcrumb',
							'description' => __( 'Action to add your content on WooCommerce breadcrumb action.', 'astra-addon' ),
						),
						'woocommerce_before_template_part' => array(
							'title'       => 'before_template_part',
							'description' => __( 'Action to add your content before WooCommerce template part.', 'astra-addon' ),
						),
						'woocommerce_after_template_part'  => array(
							'title'       => 'after_template_part',
							'description' => __( 'Action to add your content after WooCommerce template part.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-shop'] = array(
					'title' => __( 'WooCommerce - Shop', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_archive_description'  => array(
							'title'       => 'archive_description',
							'description' => __( 'Action to add your content on archive_description action.', 'astra-addon' ),
						),
						'woocommerce_before_shop_loop'     => array(
							'title'       => 'before_shop_loop',
							'description' => __( 'Action to add your content before WooCommerce shop loop.', 'astra-addon' ),
						),
						'woocommerce_before_shop_loop_item_title' => array(
							'title'       => 'before_shop_loop_item_title',
							'description' => __( 'Action to add your content before WooCommerce shop loop item.', 'astra-addon' ),
						),
						'woocommerce_after_shop_loop_item_title' => array(
							'title'       => 'after_shop_loop_item_title',
							'description' => __( 'Action to add your content after WooCommerce shop loop item.', 'astra-addon' ),
						),
						'astra_woo_shop_category_before'   => array(
							'title'       => 'woo_shop_category_before',
							'description' => __( 'Action to add your content before WooCommerce shop category.', 'astra-addon' ),
						),
						'astra_woo_shop_category_after'    => array(
							'title'       => 'woo_shop_category_after',
							'description' => __( 'Action to add your content after WooCommerce shop category.', 'astra-addon' ),
						),
						'astra_woo_shop_title_before'      => array(
							'title'       => 'woo_shop_title_before',
							'description' => __( 'Action to add your content before WooCommerce shop title.', 'astra-addon' ),
						),
						'astra_woo_shop_title_after'       => array(
							'title'       => 'woo_shop_title_after',
							'description' => __( 'Action to add your content after WooCommerce shop title.', 'astra-addon' ),
						),
						'astra_woo_shop_rating_before'     => array(
							'title'       => 'woo_shop_rating_before',
							'description' => __( 'Action to add your content before WooCommerce shop rating.', 'astra-addon' ),
						),
						'astra_woo_shop_rating_after'      => array(
							'title'       => 'woo_shop_rating_after',
							'description' => __( 'Action to add your content after WooCommerce shop rating.', 'astra-addon' ),
						),
						'astra_woo_shop_price_before'      => array(
							'title'       => 'woo_shop_price_before',
							'description' => __( 'Action to add your content before WooCommerce shop price.', 'astra-addon' ),
						),
						'astra_woo_shop_price_after'       => array(
							'title'       => 'woo_shop_price_after',
							'description' => __( 'Action to add your content after WooCommerce shop price.', 'astra-addon' ),
						),
						'astra_woo_shop_add_to_cart_before' => array(
							'title'       => 'woo_shop_add_to_cart_before',
							'description' => __( 'Action to add your content before WooCommerce shop cart.', 'astra-addon' ),
						),
						'astra_woo_shop_add_to_cart_after' => array(
							'title'       => 'woo_shop_add_to_cart_after',
							'description' => __( 'Action to add your content after WooCommerce shop cart.', 'astra-addon' ),
						),
						'woocommerce_after_shop_loop'      => array(
							'title'       => 'after_shop_loop',
							'description' => __( 'Action to add your content after WooCommerce shop loop.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-product'] = array(
					'title' => __( 'WooCommerce - Product', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_before_single_product' => array(
							'title'       => 'before_single_product',
							'description' => __( 'Action to add your content before WooCommerce single product.', 'astra-addon' ),
						),
						'woocommerce_before_single_product_summary' => array(
							'title'       => 'before_single_product_summary',
							'description' => __( 'Action to add your content before WooCommerce single product summary.', 'astra-addon' ),
						),

						'woocommerce_single_product_summary' => array(
							'title'       => 'single_product_summary',
							'description' => __( 'Action to add your content on WooCommerce single product summary action.', 'astra-addon' ),
						),
						'woocommerce_after_single_product_summary' => array(
							'title'       => 'after_single_product_summary',
							'description' => __( 'Action to add your content after WooCommerce single product summary.', 'astra-addon' ),
						),
						'woocommerce_simple_add_to_cart'   => array(
							'title'       => 'simple_add_to_cart',
							'description' => __( 'Action to add your content on simple add to cart action.', 'astra-addon' ),
						),
						'woocommerce_before_add_to_cart_form' => array(
							'title'       => 'before_add_to_cart_form',
							'description' => __( 'Action to add your content before WooCommerce add to cart form.', 'astra-addon' ),
						),
						'woocommerce_before_add_to_cart_button' => array(
							'title'       => 'before_add_to_cart_button',
							'description' => __( 'Action to add your content before WooCommerce add to cart button.', 'astra-addon' ),
						),
						'woocommerce_before_add_to_cart_quantity' => array(
							'title'       => 'before_add_to_cart_quantity',
							'description' => __( 'Action to add your content before WooCommerce add to cart quantity.', 'astra-addon' ),
						),
						'woocommerce_after_add_to_cart_quantity' => array(
							'title'       => 'after_add_to_cart_quantity',
							'description' => __( 'Action to add your content after WooCommerce add to cart quantity.', 'astra-addon' ),
						),
						'woocommerce_after_add_to_cart_button' => array(
							'title'       => 'after_add_to_cart_button',
							'description' => __( 'Action to add your content after WooCommerce add to cart button.', 'astra-addon' ),
						),
						'woocommerce_after_add_to_cart_form' => array(
							'title'       => 'after_add_to_cart_form',
							'description' => __( 'Action to add your content after WooCommerce add to cart form.', 'astra-addon' ),
						),
						'woocommerce_product_meta_start'   => array(
							'title'       => 'product_meta_start',
							'description' => __( 'Action to add your content on WooCommerce product meta start action.', 'astra-addon' ),
						),
						'woocommerce_product_meta_end'     => array(
							'title'       => 'product_meta_end',
							'description' => __( 'Action to add your content on WooCommerce product meta end action.', 'astra-addon' ),
						),
						'woocommerce_share'                => array(
							'title'       => 'share',
							'description' => __( 'Action to add your content on share action.', 'astra-addon' ),
						),
						'woocommerce_after_single_product' => array(
							'title'       => 'after_single_product',
							'description' => __( 'Action to add your content after WooCommerce single product.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-cart'] = array(
					'title' => __( 'WooCommerce - Cart', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_check_cart_items'     => array(
							'title'       => 'check_cart_items',
							'description' => __( 'Action to add your content on check cart items action.', 'astra-addon' ),
						),
						'woocommerce_cart_reset'           => array(
							'title'       => 'cart_reset',
							'description' => __( 'Action to add your content on cart reset.', 'astra-addon' ),
						),
						'woocommerce_cart_updated'         => array(
							'title'       => 'cart_updated',
							'description' => __( 'Action to add your content on cart update.', 'astra-addon' ),
						),
						'woocommerce_cart_is_empty'        => array(
							'title'       => 'cart_is_empty',
							'description' => __( 'Action to add your content on check cart is empty.', 'astra-addon' ),
						),
						'woocommerce_before_calculate_totals' => array(
							'title'       => 'before_calculate_totals',
							'description' => __( 'Action to add your content before WooCommerce calculate totals.', 'astra-addon' ),
						),
						'woocommerce_cart_calculate_fees'  => array(
							'title'       => 'cart_calculate_fees',
							'description' => __( 'Action to add your content on cart calculate fees.', 'astra-addon' ),
						),
						'woocommerce_after_calculate_totals' => array(
							'title'       => 'after_calculate_totals',
							'description' => __( 'Action to add your content after WooCommerce calculate totals.', 'astra-addon' ),
						),
						'woocommerce_before_cart'          => array(
							'title'       => 'before_cart',
							'description' => __( 'Action to add your content before WooCommerce cart.', 'astra-addon' ),
						),
						'woocommerce_before_cart_table'    => array(
							'title'       => 'before_cart_table',
							'description' => __( 'Action to add your content before WooCommerce cart table.', 'astra-addon' ),
						),
						'woocommerce_before_cart_contents' => array(
							'title'       => 'before_cart_contents',
							'description' => __( 'Action to add your content before WooCommerce cart contents.', 'astra-addon' ),
						),
						'woocommerce_cart_contents'        => array(
							'title'       => 'cart_contents',
							'description' => __( 'Action to add your content on cart contents.', 'astra-addon' ),
						),
						'woocommerce_after_cart_contents'  => array(
							'title'       => 'after_cart_contents',
							'description' => __( 'Action to add your content after WooCommerce cart contents.', 'astra-addon' ),
						),
						'woocommerce_cart_coupon'          => array(
							'title'       => 'cart_coupon',
							'description' => __( 'Action to add your content on cart coupon.', 'astra-addon' ),
						),
						'woocommerce_cart_actions'         => array(
							'title'       => 'cart_actions',
							'description' => __( 'Action to add your content on cart actions.', 'astra-addon' ),
						),
						'woocommerce_after_cart_table'     => array(
							'title'       => 'after_cart_table',
							'description' => __( 'Action to add your content after WooCommerce cart table.', 'astra-addon' ),
						),
						'woocommerce_cart_collaterals'     => array(
							'title'       => 'cart_collaterals',
							'description' => __( 'Action to add your content on cart collaterals.', 'astra-addon' ),
						),
						'woocommerce_before_cart_totals'   => array(
							'title'       => 'before_cart_totals',
							'description' => __( 'Action to add your content before WooCommerce cart totals.', 'astra-addon' ),
						),
						'woocommerce_cart_totals_before_order_total' => array(
							'title'       => 'cart_totals_before_order_total',
							'description' => __( 'Action to add your content before WooCommerce order total.', 'astra-addon' ),
						),
						'woocommerce_cart_totals_after_order_total' => array(
							'title'       => 'cart_totals_after_order_total',
							'description' => __( 'Action to add your content after WooCommerce order total.', 'astra-addon' ),
						),
						'woocommerce_proceed_to_checkout'  => array(
							'title'       => 'proceed_to_checkout',
							'description' => __( 'Action to add your content on proceed to checkout action.', 'astra-addon' ),
						),
						'woocommerce_after_cart_totals'    => array(
							'title'       => 'after_cart_totals',
							'description' => __( 'Action to add your content after WooCommerce cart totals.', 'astra-addon' ),
						),
						'woocommerce_after_cart'           => array(
							'title'       => 'after_cart',
							'description' => __( 'Action to add your content after WooCommerce cart.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-checkout'] = array(
					'title' => __( 'WooCommerce - Checkout', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_before_checkout_form' => array(
							'title'       => 'before_checkout_form',
							'description' => __( 'Action to add your content before WooCommerce checkout form.', 'astra-addon' ),
						),
						'woocommerce_checkout_before_customer_details' => array(
							'title'       => 'checkout_before_customer_details',
							'description' => __( 'Action to add your content before WooCommerce customer details.', 'astra-addon' ),
						),
						'woocommerce_checkout_after_customer_details' => array(
							'title'       => 'checkout_after_customer_details',
							'description' => __( 'Action to add your content after WooCommerce customer details.', 'astra-addon' ),
						),
						'woocommerce_checkout_billing'     => array(
							'title'       => 'checkout_billing',
							'description' => __( 'Action to add your content on checkout billing.', 'astra-addon' ),
						),
						'woocommerce_before_checkout_billing_form' => array(
							'title'       => 'before_checkout_billing_form',
							'description' => __( 'Action to add your content before WooCommerce checkout billing form.', 'astra-addon' ),
						),
						'woocommerce_after_checkout_billing_form' => array(
							'title'       => 'after_checkout_billing_form',
							'description' => __( 'Action to add your content after WooCommerce checkout billing form.', 'astra-addon' ),
						),
						'woocommerce_before_order_notes'   => array(
							'title'       => 'before_order_notes',
							'description' => __( 'Action to add your content before WooCommerce order notes.', 'astra-addon' ),
						),
						'woocommerce_after_order_notes'    => array(
							'title'       => 'after_order_notes',
							'description' => __( 'Action to add your content after WooCommerce order notes.', 'astra-addon' ),
						),
						'woocommerce_checkout_shipping'    => array(
							'title'       => 'checkout_shipping',
							'description' => __( 'Action to add your content on checkout shipping action.', 'astra-addon' ),
						),
						'woocommerce_checkout_before_order_review' => array(
							'title'       => 'checkout_before_order_review',
							'description' => __( 'Action to add your content before WooCommerce checkout order review.', 'astra-addon' ),
						),
						'woocommerce_checkout_order_review' => array(
							'title'       => 'checkout_order_review',
							'description' => __( 'Action to add your content on checkout order review action.', 'astra-addon' ),
						),
						'woocommerce_review_order_before_cart_contents' => array(
							'title'       => 'review_order_before_cart_contents',
							'description' => __( 'Action to add your content before WooCommerce review order cart contents.', 'astra-addon' ),
						),
						'woocommerce_review_order_after_cart_contents' => array(
							'title'       => 'review_order_after_cart_contents',
							'description' => __( 'Action to add your content after WooCommerce review order cart contents.', 'astra-addon' ),
						),
						'woocommerce_review_order_before_order_total' => array(
							'title'       => 'review_order_before_order_total',
							'description' => __( 'Action to add your content before WooCommerce review order total.', 'astra-addon' ),
						),
						'woocommerce_review_order_after_order_total' => array(
							'title'       => 'review_order_after_order_total',
							'description' => __( 'Action to add your content after WooCommerce review order total.', 'astra-addon' ),
						),
						'woocommerce_review_order_before_payment' => array(
							'title'       => 'review_order_before_payment',
							'description' => __( 'Action to add your content before WooCommerce order payment.', 'astra-addon' ),
						),
						'woocommerce_review_order_before_submit' => array(
							'title'       => 'review_order_before_submit',
							'description' => __( 'Action to add your content before WooCommerce order submit.', 'astra-addon' ),
						),
						'woocommerce_review_order_after_submit' => array(
							'title'       => 'review_order_after_submit',
							'description' => __( 'Action to add your content after WooCommerce order submit.', 'astra-addon' ),
						),
						'woocommerce_review_order_after_payment' => array(
							'title'       => 'review_order_after_payment',
							'description' => __( 'Action to add your content after WooCommerce order payment.', 'astra-addon' ),
						),
						'woocommerce_checkout_after_order_review' => array(
							'title'       => 'checkout_after_order_review',
							'description' => __( 'Action to add your content after WooCommerce checkout order review.', 'astra-addon' ),
						),
						'woocommerce_after_checkout_form'  => array(
							'title'       => 'after_checkout_form',
							'description' => __( 'Action to add your content after WooCommerce checkout form.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-distraction-checkout'] = array(
					'title' => __( 'WooCommerce - Distraction Free Checkout', 'astra-addon' ),
					'hooks' => array(
						'astra_woo_checkout_masthead_top' => array(
							'title'       => 'woo_checkout_masthead_top',
							'description' => __( 'Action to add your content on WooCommerce checkout masthead top.', 'astra-addon' ),
						),
						'astra_woo_checkout_main_header_bar_top' => array(
							'title'       => 'astra_woo_checkout_main_header_bar_top',
							'description' => __( 'Action to add your content on WooCommerce checkout main header bar top.', 'astra-addon' ),
						),
						'astra_woo_checkout_main_header_bar_bottom' => array(
							'title'       => 'astra_woo_checkout_main_header_bar_bottom',
							'description' => __( 'Action to add your content on WooCommerce checkout main header bar bottom.', 'astra-addon' ),
						),
						'astra_woo_checkout_masthead_bottom' => array(
							'title'       => 'woo_checkout_masthead_bottom',
							'description' => __( 'Action to add your content on WooCommerce checkout masthead bottom.', 'astra-addon' ),
						),
						'astra_woo_checkout_footer_content_top' => array(
							'title'       => 'woo_checkout_footer_content_top',
							'description' => __( 'Action to add your content on WooCommerce checkout footer content top.', 'astra-addon' ),
						),
						'astra_woo_checkout_footer_content_bottom' => array(
							'title'       => 'woo_checkout_footer_content_bottom',
							'description' => __( 'Action to add your content on WooCommerce checkout footer content bottom.', 'astra-addon' ),
						),
					),
				);

				self::$astra_hooks['woo-account'] = array(
					'title' => __( 'WooCommerce - Account', 'astra-addon' ),
					'hooks' => array(
						'woocommerce_before_account_navigation' => array(
							'title'       => 'before_account_navigation',
							'description' => __( 'Action to add your content before WooCommerce account navigation.', 'astra-addon' ),
						),
						'woocommerce_account_navigation' => array(
							'title'       => 'account_navigation',
							'description' => __( 'Action to add your content on WooCommerce account navigation.', 'astra-addon' ),
						),
						'woocommerce_after_account_navigation' => array(
							'title'       => 'after_account_navigation',
							'description' => __( 'Action to add your content after WooCommerce account navigation.', 'astra-addon' ),
						),
					),
				);
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_head', array( $this, 'menu_highlight' ) );
			add_action( 'admin_body_class', array( $this, 'admin_body_class' ) );
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
			add_filter( 'astra_location_rule_post_types', array( $this, 'location_rule_post_types' ) );
		}

		/**
		 * Enqueue admin Scripts and Styles.
		 *
		 * @since  1.0.3
		 */
		public function admin_scripts() {

			global $pagenow;
			global $post;

			$screen = get_current_screen();

			if ( ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) && ASTRA_ADVANCED_HOOKS_POST_TYPE == $screen->post_type ) {

				if ( ! function_exists( 'wp_enqueue_code_editor' ) ) {
					return;
				}

				$settings = wp_enqueue_code_editor(
					array(
						'type'       => 'application/x-httpd-php',
						'codemirror' => array(
							'indentUnit' => 2,
							'tabSize'    => 2,
						),
					)
				);

				wp_add_inline_script(
					'code-editor',
					sprintf(
						'jQuery( function() { wp.codeEditor.initialize( "ast-advanced-hook-php-code", %s ); } );',
						wp_json_encode( $settings )
					)
				);
			}

		}


		/**
		 * Filter location rule post types to skip current post type.
		 *
		 * @param array $post_types Array of all public post types.
		 * @return array
		 */
		function location_rule_post_types( $post_types ) {
			global $pagenow;

			$screen = get_current_screen();

			if ( ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) && ASTRA_ADVANCED_HOOKS_POST_TYPE == $screen->post_type && isset( $post_types[ ASTRA_ADVANCED_HOOKS_POST_TYPE ] ) ) {
				unset( $post_types[ ASTRA_ADVANCED_HOOKS_POST_TYPE ] );
			}
			return $post_types;
		}

		/**
		 * Init Metabox
		 */
		public function init_metabox() {
			add_action( 'add_meta_boxes', array( $this, 'setup_meta_box' ) );
			add_action( 'edit_form_after_title', array( $this, 'enable_php_markup' ), 1, 1 );
			add_action( 'edit_form_after_editor', array( $this, 'php_editor_markup' ), 10, 1 );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );

			/**
			 * Set metabox options
			 *
			 * @see http://php.net/manual/en/filter.filters.sanitize.php
			 */
			self::$meta_option = apply_filters(
				'astra_advanced_hooks_meta_box_options',
				array(
					'ast-advanced-hook-location'  => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-exclusion' => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-layout'    => array(
						'default'  => '',
						'sanitize' => 'FILTER_DEFAULT',
					),

					'ast-advanced-hook-action'    => array(
						'default'  => '',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-priority'  => array(
						'default'  => '',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-with-php'  => array(
						'default'  => '',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-php-code'  => array(
						'default'  => '<!-- Add your snippet here. -->',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-users'     => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-padding'   => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-header'    => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-advanced-hook-footer'    => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-404-page'                => array(
						'default'  => array(),
						'sanitize' => 'FILTER_DEFAULT',
					),
				)
			);

		}

		/**
		 * Filter admin body class
		 *
		 * @since 1.0.0
		 *
		 * @param string $classes List of Admin Classes.
		 * @return string
		 */
		public function admin_body_class( $classes ) {

			global $pagenow;
			global $post;

			$screen = get_current_screen();

			if ( ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) && ASTRA_ADVANCED_HOOKS_POST_TYPE == $screen->post_type ) {
				$with_php = get_post_meta( $post->ID, 'ast-advanced-hook-with-php', true );
				if ( 'enabled' == $with_php ) {
					$classes = ' astra-php-snippt-enabled';
				}
			}
			return $classes;
		}

		/**
		 * Keep the Astra menu open when editing the advanced headers.
		 * Highlights the wanted admin (sub-) menu items for the CPT.
		 *
		 * @since  1.0.0
		 */
		public function menu_highlight() {
			global $parent_file, $submenu_file, $post_type;
			if ( ASTRA_ADVANCED_HOOKS_POST_TYPE == $post_type ) :
				$parent_file  = 'themes.php';
				$submenu_file = 'edit.php?post_type=' . ASTRA_ADVANCED_HOOKS_POST_TYPE;

				/* Same display rule assign notice */
				$option = array(
					'layout'    => 'ast-advanced-hook-layout',
					'location'  => 'ast-advanced-hook-location',
					'exclusion' => 'ast-advanced-hook-exclusion',
					'users'     => 'ast-advanced-hook-users',
				);

				self::hook_same_display_on_notice( ASTRA_ADVANCED_HOOKS_POST_TYPE, $option );
			endif;

		}

		/**
		 * Same display_on notice.
		 *
		 * @since  1.0.0
		 * @param  int   $post_type Post Type.
		 * @param  array $option meta option name.
		 */
		static public function hook_same_display_on_notice( $post_type, $option ) {
			global $wpdb;
			global $post;

			$all_rules        = array();
			$already_set_rule = array();

			$layout   = isset( $option['layout'] ) ? $option['layout'] : '';
			$location = isset( $option['location'] ) ? $option['location'] : '';

			$all_headers = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT p.ID, p.post_title, pm.meta_value FROM {$wpdb->postmeta} as pm
				INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID
				WHERE pm.meta_key = %s
				AND p.post_type = %s
				AND p.post_status = 'publish'",
					$location,
					$post_type
				)
			);

			foreach ( $all_headers as $header ) {

				$layout_data = get_post_meta( $header->ID, $layout, true );

				if ( 'hooks' === $layout_data ) {
					continue;
				};

				$location_rules = unserialize( $header->meta_value );

				if ( is_array( $location_rules ) && isset( $location_rules['rule'] ) ) {

					foreach ( $location_rules['rule'] as $key => $rule ) {

						if ( ! isset( $all_rules[ $rule ] ) ) {
							$all_rules[ $rule ] = array();
						}

						if ( 'specifics' == $rule && isset( $location_rules['specific'] ) && is_array( $location_rules['specific'] ) ) {

							foreach ( $location_rules['specific'] as $s_index => $s_value ) {

								$all_rules[ $rule ][ $s_value ][ $header->ID ] = array(
									'ID'     => $header->ID,
									'name'   => $header->post_title,
									'layout' => $layout_data,
								);
							}
						} else {
							$all_rules[ $rule ][ $header->ID ] = array(
								'ID'     => $header->ID,
								'name'   => $header->post_title,
								'layout' => $layout_data,
							);
						}
					}
				}
			}

			if ( empty( $post ) ) {
				return;
			}
			$current_post_data   = get_post_meta( $post->ID, $location, true );
			$current_post_layout = get_post_meta( $post->ID, $layout, true );

			if ( is_array( $current_post_data ) && isset( $current_post_data['rule'] ) ) {

				foreach ( $current_post_data['rule'] as $c_key => $c_rule ) {

					if ( ! isset( $all_rules[ $c_rule ] ) ) {
						continue;
					}

					if ( 'specifics' === $c_rule ) {

						foreach ( $current_post_data['specific'] as $s_index => $s_id ) {
							if ( ! isset( $all_rules[ $c_rule ][ $s_id ] ) ) {
								continue;
							}

							foreach ( $all_rules[ $c_rule ][ $s_id ] as $p_id => $data ) {

								if ( $p_id == $post->ID ) {
									continue;
								}

								if ( '0' !== $data['layout'] && $current_post_layout === $data['layout'] ) {

									$already_set_rule[] = $data['name'];
								}
							}
						}
					} else {

						foreach ( $all_rules[ $c_rule ] as $p_id => $data ) {

							if ( $p_id == $post->ID ) {
								continue;
							}

							if ( '0' !== $data['layout'] && $current_post_layout === $data['layout'] ) {
								$already_set_rule[] = $data['name'];
							}
						}
					}
				}
			}

			if ( ! empty( $already_set_rule ) ) {
				add_action(
					'admin_notices',
					function() use ( $already_set_rule, $current_post_layout ) {

						$rule_set_titles = '<strong>' . implode( ',', $already_set_rule ) . '</strong>';
						$layout          = '<strong>' . ucfirst( $current_post_layout ) . '</strong>';

						/* translators: %s layout. */
						$notice = sprintf( __( 'Another %s Layout is selected for the same display rules.', 'astra-addon' ), $layout );

						echo '<div class="error">';
						echo '<p>' . $notice . '</p>';
						echo '</div>';

					}
				);
			}
		}
		/**
		 *  Setup Metabox
		 */
		function setup_meta_box() {

			// Get all posts.
			$post_types = get_post_types();

			if ( ASTRA_ADVANCED_HOOKS_POST_TYPE == get_post_type() ) {
				// Enable for all posts.
				foreach ( $post_types as $type ) {

					if ( 'attachment' !== $type ) {
						add_meta_box(
							'advanced-hook-settings',                // Id.
							__( 'Custom Layout Settings', 'astra-addon' ), // Title.
							array( $this, 'markup_meta_box' ),      // Callback.
							$type,                                  // Post_type.
							'normal',                               // Context.
							'high'                                  // Priority.
						);
					}
				}
			}
		}

		/**
		 * Markup for checkbox for execute php snippet.
		 *
		 * @since 1.0.0
		 * @param object $post Post Object.
		 */
		function enable_php_markup( $post ) {

			// Get all posts.
			$post_type = get_post_type();

			if ( ASTRA_ADVANCED_HOOKS_POST_TYPE == $post_type ) {

				wp_nonce_field( basename( __FILE__ ), ASTRA_ADVANCED_HOOKS_POST_TYPE );
				$stored = get_post_meta( $post->ID );

				// Set stored and override defaults.
				foreach ( $stored as $key => $value ) {
					self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
				}

				// Get defaults.
				$meta = self::get_meta_option();

				/**
				 * Get options
				 */
				$with_php = ( isset( $meta['ast-advanced-hook-with-php']['default'] ) ) ? $meta['ast-advanced-hook-with-php']['default'] : '';

				$enable_label  = __( 'Enable Code Editor', 'astra-addon' );
				$disable_label = __( 'Enable Classic Editor', 'astra-addon' );

				$icon      = 'dashicons-editor-code';
				$label     = $enable_label;
				$btn_class = '';
				if ( 'enabled' == $with_php ) {
					$label     = $disable_label;
					$icon      = 'dashicons-edit';
					$btn_class = 'ast-active';
				}
				?>
				<div class="ast-advanced-hook-enable-php-wrapper">
					<button type="button" class="ast-advanced-hook-enable-php-btn button button-primary button-large <?php echo esc_attr( $btn_class ); ?>" data-enable-text="<?php echo esc_attr( $enable_label ); ?>" data-disable-text="<?php echo esc_attr( $disable_label ); ?>" >
						<i class="dashicons <?php echo esc_attr( $icon ); ?>"></i>
						<span class="ast-btn-title"><?php echo esc_html( $label ); ?></span>
						<input type="hidden" class="ast-advanced-hook-with-php" name="ast-advanced-hook-with-php" value="<?php echo esc_attr( $with_php ); ?>" />
					</button>
				</div>
				<?php
			}
		}

		/**
		 * Markup PHP snippt editor.
		 *
		 * @since 1.0.0
		 * @param object $post Post Object.
		 */
		function php_editor_markup( $post ) {

			// Get all posts.
			$post_type = get_post_type();

			if ( ASTRA_ADVANCED_HOOKS_POST_TYPE == $post_type ) {

				wp_nonce_field( basename( __FILE__ ), ASTRA_ADVANCED_HOOKS_POST_TYPE );
				$stored = get_post_meta( $post->ID );

				// Set stored and override defaults.
				foreach ( $stored as $key => $value ) {
					self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
				}

				// Get defaults.
				$meta = self::get_meta_option();

				/**
				 * Get options
				 */
				$content = ( isset( $meta['ast-advanced-hook-php-code']['default'] ) ) ? $meta['ast-advanced-hook-php-code']['default'] : "<?php\n	// Add your snippet here.\n?>";
				?>
				<div class="wp-editor-container astra-php-editor-container">
					<textarea id="ast-advanced-hook-php-code" name="ast-advanced-hook-php-code" class="wp-editor-area ast-advanced-hook-php-content"><?php echo $content; ?></textarea>
				</div>
				<?php
			}
		}

		/**
		 * Get metabox options
		 */
		public static function get_meta_option() {
			return self::$meta_option;
		}

		/**
		 * Metabox Markup
		 *
		 * @param  object $post Post object.
		 * @return void
		 */
		function markup_meta_box( $post ) {

			wp_nonce_field( basename( __FILE__ ), ASTRA_ADVANCED_HOOKS_POST_TYPE );
			$stored = get_post_meta( $post->ID );

			$advanced_hooks_meta = array( 'ast-advanced-hook-location', 'ast-advanced-hook-exclusion', 'ast-advanced-hook-users', 'ast-advanced-hook-padding', 'ast-advanced-hook-header', 'ast-advanced-hook-footer', 'ast-404-page' );

			// Set stored and override defaults.
			foreach ( $stored as $key => $value ) {
				if ( in_array( $key, $advanced_hooks_meta ) ) {
					self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? maybe_unserialize( $stored[ $key ][0] ) : '';
				} else {
					self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
				}
			}

			// Get defaults.
			$meta = self::get_meta_option();

			/**
			 * Get options
			 */
			$display_locations = ( isset( $meta['ast-advanced-hook-location']['default'] ) ) ? $meta['ast-advanced-hook-location']['default'] : '';
			$exclude_locations = ( isset( $meta['ast-advanced-hook-exclusion']['default'] ) ) ? $meta['ast-advanced-hook-exclusion']['default'] : '';
			$layout            = ( isset( $meta['ast-advanced-hook-layout']['default'] ) ) ? $meta['ast-advanced-hook-layout']['default'] : '';
			$action            = ( isset( $meta['ast-advanced-hook-action']['default'] ) ) ? $meta['ast-advanced-hook-action']['default'] : '';
			$priority          = ( isset( $meta['ast-advanced-hook-priority']['default'] ) ) ? $meta['ast-advanced-hook-priority']['default'] : '';
			$user_roles        = ( isset( $meta['ast-advanced-hook-users']['default'] ) ) ? $meta['ast-advanced-hook-users']['default'] : '';
			$padding           = ( isset( $meta['ast-advanced-hook-padding']['default'] ) ) ? $meta['ast-advanced-hook-padding']['default'] : array();
			$header            = ( isset( $meta['ast-advanced-hook-header']['default'] ) ) ? $meta['ast-advanced-hook-header']['default'] : array();
			$footer            = ( isset( $meta['ast-advanced-hook-footer']['default'] ) ) ? $meta['ast-advanced-hook-footer']['default'] : array();
			$footer            = ( isset( $meta['ast-advanced-hook-footer']['default'] ) ) ? $meta['ast-advanced-hook-footer']['default'] : array();
			$layout_404        = ( isset( $meta['ast-404-page']['default'] ) ) ? $meta['ast-404-page']['default'] : array();

			$ast_advanced_hooks = array(
				'include-locations' => $display_locations,
				'exclude-locations' => $exclude_locations,
				'layout'            => $layout,
				'action'            => $action,
				'priority'          => $priority,
				'user_roles'        => $user_roles,
				'padding'           => $padding,
				'header'            => $header,
				'footer'            => $footer,
				'layout-404'        => $layout_404,
			);
			do_action( 'astra_advanced_hooks_settings_markup_before', $meta );
			$this->page_header_tab( $ast_advanced_hooks );
			do_action( 'astra_advanced_hooks_settings_markup_after', $meta );
		}

		/**
		 * Metabox Save
		 *
		 * @param  number $post_id Post ID.
		 * @return void
		 */
		function save_meta_box( $post_id ) {

			// Checks save status.
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			$is_valid_nonce = ( isset( $_POST[ ASTRA_ADVANCED_HOOKS_POST_TYPE ] ) && wp_verify_nonce( $_POST[ ASTRA_ADVANCED_HOOKS_POST_TYPE ], basename( __FILE__ ) ) ) ? true : false;

			// Exits script depending on save status.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return;
			}

			/**
			 * Get meta options
			 */
			$post_meta = self::get_meta_option();
			foreach ( $post_meta as $key => $data ) {
				if ( in_array( $key, array( 'ast-advanced-hook-users', 'ast-advanced-hook-padding' ) ) ) {
					$index = array_search( '', $_POST[ $key ] );
					if ( false !== $index ) {
						unset( $_POST[ $key ][ $index ] );
					}
					$meta_value = array_map( 'esc_attr', $_POST[ $key ] );
				} elseif ( in_array( $key, array( 'ast-advanced-hook-header', 'ast-advanced-hook-footer', 'ast-404-page' ) ) ) {
					if ( isset( $_POST[ $key ] ) ) {
						$meta_value = array_map( 'esc_attr', $_POST[ $key ] );
					}
				} elseif ( in_array( $key, array( 'ast-advanced-hook-location', 'ast-advanced-hook-exclusion' ) ) ) {
					$meta_value = Astra_Target_Rules_Fields::get_format_rule_value( $_POST, $key );
				} else {
					// Sanitize values.
					$sanitize_filter = ( isset( $data['sanitize'] ) ) ? $data['sanitize'] : 'FILTER_DEFAULT';

					switch ( $sanitize_filter ) {

						case 'FILTER_SANITIZE_STRING':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
							break;

						case 'FILTER_SANITIZE_URL':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
							break;

						case 'FILTER_SANITIZE_NUMBER_INT':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
							break;

						default:
							$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
							break;
					}
				}

				// Store values.
				if ( '' != $meta_value ) {
					update_post_meta( $post_id, $key, $meta_value );
				} else {
					delete_post_meta( $post_id, $key );
				}
			}

			// Correct the target rules for 404-page layout.
			$this->target_rules_404_layout();
		}

		/**
		 * Force target rules for 404 page layout.
		 *
		 * 404 Page layout will always the target rules of special-404.
		 *
		 * @since  1.2.1
		 * @return null
		 */
		private function target_rules_404_layout() {
			$layout = get_post_meta( get_the_ID(), 'ast-advanced-hook-layout', true );

			// bail if current layout is not 404 Page.
			if ( '404-page' !== $layout ) {
				return;
			}

			$target_rule_404 = array(
				'rule'     => array(
					0 => 'special-404',
				),
				'specific' => array(),
			);

			update_post_meta( get_the_ID(), 'ast-advanced-hook-location', $target_rule_404 );
		}

		/**
		 * Page Header Tabs
		 *
		 * @param  array $options Post meta.
		 */
		public function page_header_tab( $options ) {
			$include_locations = $options['include-locations'];
			$exclude_locations = $options['exclude-locations'];
			$users             = $options['user_roles'];
			$padding           = $options['padding'];
			$header            = $options['header'];
			$footer            = $options['footer'];
			$layout_404        = $options['layout-404'];

			$padding_top       = isset( $padding['top'] ) ? $padding['top'] : '';
			$padding_bottom    = isset( $padding['bottom'] ) ? $padding['bottom'] : '';
			$header_sticky     = isset( $header['sticky'] ) ? $header['sticky'] : '';
			$header_shrink     = isset( $header['shrink'] ) ? $header['shrink'] : '';
			$header_on_devices = isset( $header['sticky-header-on-devices'] ) ? $header['sticky-header-on-devices'] : '';
			$footer_sticky     = isset( $footer['sticky'] ) ? $footer['sticky'] : '';
			$footer_on_devices = isset( $footer['sticky-footer-on-devices'] ) ? $footer['sticky-footer-on-devices'] : '';
			$disable_header    = isset( $layout_404['disable_header'] ) ? $layout_404['disable_header'] : '';
			$disable_footer    = isset( $layout_404['disable_footer'] ) ? $layout_404['disable_footer'] : '';
			?>
			<table class="ast-advanced-hook-table widefat">

				<tr class="ast-advanced-hook-row">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Layout', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
						<?php
						$astra_layouts = self::$astra_layouts;
						?>
						<select id="ast-advanced-hook-layout" name="ast-advanced-hook-layout" style="width: 50%;" >
							<option value="0"><?php printf( '&mdash; %s &mdash;', esc_html__( 'Select', 'astra-addon' ) ); ?></option>
							<?php if ( is_array( $astra_layouts ) && ! empty( $astra_layouts ) ) : ?>
								<?php foreach ( $astra_layouts as $key => $layout ) : ?>

									<option <?php selected( $key, $options['layout'] ); ?> value="<?php echo $key; ?>" ><?php echo esc_html( $layout['title'] ); ?></option>

								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</td>
				</tr>

				<!-- 404 Layout -->
				<tr class="ast-advanced-hook-row ast-404-layout-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Disable Primary Header', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="checkbox" name="ast-404-page[disable_header]"
								value="enabled" <?php checked( $disable_header, 'enabled' ); ?> />
					</td>
				</tr>

				<tr class="ast-advanced-hook-row ast-404-layout-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Disable Footer Bar', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="checkbox" name="ast-404-page[disable_footer]"
								value="enabled" <?php checked( $disable_footer, 'enabled' ); ?> />
					</td>
				</tr>

				<!-- Header Layout -->
				<!-- Sticky Header -->
				<tr class="ast-advanced-hook-row ast-layout-header-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Stick', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="checkbox" name="ast-advanced-hook-header[sticky]"
								value="enabled" <?php checked( $header_sticky, 'enabled' ); ?> />
					</td>
				</tr>
				<!-- Shrink Header -->
				<tr class="ast-advanced-hook-row ast-layout-header-sticky-required ast-layout-header-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Shrink', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="checkbox" name="ast-advanced-hook-header[shrink]"
							value="enabled" <?php checked( $header_shrink, 'enabled' ); ?> />
					</td>
				</tr>
				<!-- Display On -->
				<tr class="ast-advanced-hook-row ast-layout-header-sticky-required ast-layout-header-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Stick On', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">

						<select name="ast-advanced-hook-header[sticky-header-on-devices]" style="width:50%;">
							<option value="desktop"><?php esc_html_e( 'Desktop', 'astra-addon' ); ?></option>
							<option value="mobile" <?php selected( $header_on_devices, 'mobile' ); ?> > <?php esc_html_e( 'Mobile', 'astra-addon' ); ?></option>
							<option value="both" <?php selected( $header_on_devices, 'both' ); ?> > <?php esc_html_e( 'Desktop + Mobile', 'astra-addon' ); ?></option>
						</select>
					</td>
				</tr>

				<!-- Footer Layout -->
				<!-- Sticky Footer -->
				<tr class="ast-advanced-hook-row ast-layout-footer-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Stick', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="checkbox" name="ast-advanced-hook-footer[sticky]"
								value="enabled" <?php checked( $footer_sticky, 'enabled' ); ?> />
					</td>
				</tr>
				<!-- Display On -->
				<tr class="ast-advanced-hook-row ast-layout-footer-sticky-required ast-layout-footer-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Stick On', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">

						<select name="ast-advanced-hook-footer[sticky-footer-on-devices]" style="width:50%;">
							<option value="desktop"><?php esc_html_e( 'Desktop', 'astra-addon' ); ?></option>
							<option value="mobile" <?php selected( $footer_on_devices, 'mobile' ); ?> > <?php esc_html_e( 'Mobile', 'astra-addon' ); ?></option>
							<option value="both" <?php selected( $footer_on_devices, 'both' ); ?> > <?php esc_html_e( 'Desktop + Mobile', 'astra-addon' ); ?></option>
						</select>
					</td>
				</tr>


				<tr class="ast-advanced-hook-row ast-layout-hooks-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Action', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
						<?php
						$astra_hooks = self::$astra_hooks;
						$description = '';
						?>
						<select id="ast-advanced-hook-action" name="ast-advanced-hook-action" style="width: 50%;" >
							<option value="0"><?php printf( '&mdash; %s &mdash;', esc_html__( 'Select', 'astra-addon' ) ); ?></option>
							<?php if ( is_array( $astra_hooks ) && ! empty( $astra_hooks ) ) : ?>
								<?php foreach ( $astra_hooks as $hook_cat ) : ?>
								<optgroup label="<?php echo esc_attr( $hook_cat['title'] ); ?>" >
									<?php if ( is_array( $hook_cat['hooks'] ) && ! empty( $hook_cat['hooks'] ) ) : ?>
										<?php foreach ( $hook_cat['hooks'] as $key => $hook ) : ?>
											<?php
											if ( $key == $options['action'] ) {
												$description = $hook['description'];
											}
											?>
										<option <?php selected( $key, $options['action'] ); ?> value="<?php echo $key; ?>" data-desc="<?php echo esc_attr( $hook['description'] ); ?>"><?php echo esc_html( $hook['title'] ); ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</optgroup>
							<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<p class="description ast-advanced-hook-action-desc <?php echo ( '' == $description ) ? 'ast-no-desc' : ''; ?>"><?php echo esc_html( $description ); ?></p>
					</td>
				</tr>
				<tr class="ast-advanced-hook-row ast-layout-hooks-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Priority', 'astra-addon' ); ?></label>
					</td>
					<td class="ast-advanced-hook-row-content">
					<input type="number" name="ast-advanced-hook-priority" value="<?php echo esc_attr( $options['priority'] ); ?>" placeholder="10" style="width: 50%;"/>
					</td>
				</tr>
				<tr class="ast-advanced-hook-row ast-layout-hooks-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Spacing', 'astra-addon' ); ?></label>
						<i class="ast-advanced-hook-heading-help dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Spacing can be given any positive number with or without units as &quot;5&quot; or &quot;5px&quot;. Default unit is &quot;px&quot;', 'astra-addon' ); ?>"></i>
					</td>
					<td class="ast-advanced-hook-row-content">
						<div class="ast-advanced-hook-padding-top-wrap">
							<input type="text" id="ast-advanced-hook-padding-top" class="ast-advanced-hook-padding ast-advanced-hook-padding-top" name="ast-advanced-hook-padding[top]" value="<?php echo esc_attr( $padding_top ); ?>" placeholder="0" style="width: 35%;"/>
							<label for="ast-advanced-hook-padding-top"><?php esc_html_e( 'Top Spacing', 'astra-addon' ); ?></label>
						</div>
						<div class="ast-advanced-hook-padding-bottom-wrap" >
							<input type="text" id="ast-advanced-hook-padding-bottom" class="ast-advanced-hook-padding ast-advanced-hook-padding-bottom" name="ast-advanced-hook-padding[bottom]" value="<?php echo esc_attr( $padding_bottom ); ?>" placeholder="0" style="width: 35%;"/>
							<label for="ast-advanced-hook-padding-bottom"><?php esc_html_e( 'Bottom Spacing', 'astra-addon' ); ?></label>
						</div>
					</td>
				</tr>

				<tr class="ast-advanced-hook-row ast-target-rules-display ast-layout-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Display On', 'astra-addon' ); ?></label>
						<i class="ast-advanced-hook-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'Add locations for where this Custom Layout should appear.', 'astra-addon' ); ?>"></i>
					</td>
					<td class="ast-advanced-hook-row-content">
					<?php
						Astra_Target_Rules_Fields::target_rule_settings_field(
							'ast-advanced-hook-location',
							array(
								'title'          => __( 'Display Rules', 'astra-addon' ),
								'value'          => '[{"type":"basic-global","specific":null}]',
								'tags'           => 'site,enable,target,pages',
								'rule_type'      => 'display',
								'add_rule_label' => __( 'Add Display Rule', 'astra-addon' ),
							),
							$include_locations
						);
					?>
					</td>
				</tr>
				<tr class="ast-advanced-hook-row ast-target-rules-exclude ast-layout-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'Do Not Display On', 'astra-addon' ); ?></label>
						<i class="ast-advanced-hook-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'This Custom Layout will not appear at these locations.', 'astra-addon' ); ?>"></i>
					</td>
					<td class="ast-advanced-hook-row-content">
					<?php
						Astra_Target_Rules_Fields::target_rule_settings_field(
							'ast-advanced-hook-exclusion',
							array(
								'title'          => __( 'Exclude On', 'astra-addon' ),
								'value'          => '[]',
								'tags'           => 'site,enable,target,pages',
								'add_rule_label' => __( 'Add Exclusion Rule', 'astra-addon' ),
								'rule_type'      => 'exclude',
							),
							$exclude_locations
						);
					?>
					</td>
				</tr>
				<tr class="ast-advanced-hook-row ast-target-rules-user ast-layout-required">
					<td class="ast-advanced-hook-row-heading">
						<label><?php esc_html_e( 'User Roles', 'astra-addon' ); ?></label>
						<i class="ast-advanced-hook-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'Target header based on user role.', 'astra-addon' ); ?>"></i>
					</td>
					<td class="ast-advanced-hook-row-content">
					<?php
						Astra_Target_Rules_Fields::target_user_role_settings_field(
							'ast-advanced-hook-users',
							array(
								'title'          => __( 'Users', 'astra-addon' ),
								'value'          => '[]',
								'tags'           => 'site,enable,target,pages',
								'add_rule_label' => __( 'Add User Rule', 'astra-addon' ),
							),
							$users
						);
					?>
					</td>
				</tr>
			</table>

			<?php
		}


	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Advanced_Hooks_Meta::get_instance();
