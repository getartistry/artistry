<?php
/**
 * WC_PB_Display class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.5.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Bundle display functions and filters.
 *
 * @class    WC_PB_Display
 * @version  5.7.8
 */
class WC_PB_Display {

	/**
	 * Indicates whether the bundled table item indent JS has already been enqueued.
	 * @var boolean
	 */
	private $enqueued_bundled_table_item_js = false;

	/**
	 * Workaround for $order arg missing from 'woocommerce_order_item_name' filter - set within the 'woocommerce_order_item_class' filter - @see 'order_item_class()'.
	 * @var boolean|WC_Order
	 */
	private $order_item_order = false;

	/**
	 * The single instance of the class.
	 * @var WC_PB_Display
	 *
	 * @since 5.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_PB_Display instance. Ensures only one instance of WC_PB_Display is loaded or can be loaded.
	 *
	 * @static
	 * @return WC_PB_Display
	 * @since  5.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/**
	 * Setup hooks and functions.
	 */
	protected function __construct() {

		// Single product template functions and hooks.
		require_once( 'wc-pb-template-functions.php' );
		require_once( 'wc-pb-template-hooks.php' );

		// Front end bundle add-to-cart script.
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 100 );

		/*
		 * Single-product.
		 */

		// Display info notice when editing a bundle from the cart. Notices are rendered at priority 10.
		add_action( 'woocommerce_before_single_product', array( $this, 'add_edit_in_cart_notice' ), 0 );

		// Modify structured data.
		add_filter( 'woocommerce_structured_data_product_offer', array( $this, 'structured_product_data' ), 10, 2 );

		/*
		 * Cart.
		 */

		// Filter cart item price.
		add_filter( 'woocommerce_cart_item_price', array( $this, 'cart_item_price' ), 10, 3 );

		// Filter cart item subtotals.
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'cart_item_subtotal' ), 10, 3 );
		add_filter( 'woocommerce_checkout_item_subtotal', array( $this, 'cart_item_subtotal' ), 10, 3 );

		// Keep quantities in sync.
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'cart_item_quantity' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'cart_item_remove_link' ), 10, 2 );

		// Visibility.
		add_filter( 'woocommerce_cart_item_visible', array( $this, 'cart_item_visible' ), 10, 3 );
		add_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'cart_item_visible' ), 10, 3 );
		add_filter( 'woocommerce_checkout_cart_item_visible', array( $this, 'cart_item_visible' ), 10, 3 );

		// Modify titles.
		add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_item_title' ), 10, 3 );

		// Add table item classes.
		add_filter( 'woocommerce_cart_item_class', array( $this, 'cart_item_class' ), 10, 3 );

		// Filter cart item count.
		add_filter( 'woocommerce_cart_contents_count',  array( $this, 'cart_contents_count' ) );

		// Item data.
		add_filter( 'woocommerce_get_item_data', array( $this, 'cart_item_data' ), 10, 2 );

		// Hide thumbnail in cart when 'Hide thumbnail' option is selected.
		add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 3);

		// Filter cart widget items.
		add_filter( 'woocommerce_before_mini_cart', array( $this, 'add_cart_widget_filters' ) );
		add_filter( 'woocommerce_after_mini_cart', array( $this, 'remove_cart_widget_filters' ) );


		/*
		 * Orders.
		 */

		// Filter order item subtotals.
		add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_subtotal' ), 10, 3 );

		// Visibility.
		add_filter( 'woocommerce_order_item_visible', array( $this, 'order_item_visible' ), 10, 2 );

		// Modify titles.
		add_filter( 'woocommerce_order_item_name', array( $this, 'order_item_title' ), 10, 2 );

		// Add table item classes.
		add_filter( 'woocommerce_order_item_class', array( $this, 'order_item_class' ), 10, 3 );

		// Filter order item count.
		add_filter( 'woocommerce_get_item_count', array( $this, 'order_item_count' ), 10, 3 );

		// Indentation of bundled items in emails.
		add_action( 'woocommerce_email_styles', array( $this, 'email_styles' ) );

		/*
		 * Archives.
		 */

		// Allow ajax add-to-cart to work in WC 2.3/2.4.
		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'loop_add_to_cart_link' ), 10, 2 );

		/*
		 * Other.
		 */

		// Wishlists compatibility.
		add_filter( 'woocommerce_wishlist_list_item_price', array( $this, 'wishlist_list_item_price' ), 10, 3 );
		add_action( 'woocommerce_wishlist_after_list_item_name', array( $this, 'wishlist_after_list_item_name' ), 10, 2 );
	}

	/**
	 * Frontend scripts.
	 *
	 * @return void
	 */
	public function frontend_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-add-to-cart-bundle', WC_PB()->plugin_url() . '/assets/js/add-to-cart-bundle' . $suffix . '.js', array( 'jquery', 'wc-add-to-cart-variation' ), WC_PB()->version, true );

		wp_register_style( 'wc-bundle-css', WC_PB()->plugin_url() . '/assets/css/wc-pb-single-product.css', false, WC_PB()->version );
		wp_style_add_data( 'wc-bundle-css', 'rtl', 'replace' );

		wp_register_style( 'wc-bundle-style', WC_PB()->plugin_url() . '/assets/css/wc-pb-frontend.css', false, WC_PB()->version );
		wp_style_add_data( 'wc-bundle-style', 'rtl', 'replace' );

		wp_enqueue_style( 'wc-bundle-style' );

		/**
		 * 'woocommerce_bundle_front_end_params' filter.
		 *
		 * @param  array
		 */
		$params = apply_filters( 'woocommerce_bundle_front_end_params', array(
			'i18n_free'                           => __( 'Free!', 'woocommerce' ),
			'i18n_total'                          => __( 'Total: ', 'woocommerce-product-bundles' ),
			'i18n_subtotal'                       => __( 'Subtotal: ', 'woocommerce-product-bundles' ),
			'i18n_addons_total'                   => __( 'Grand total: ', 'woocommerce-product-bundles' ),
			'i18n_price_format'                   => sprintf( _x( '%1$s%2$s%3$s', '"Total/Subtotal" string followed by price followed by price suffix', 'woocommerce-product-bundles' ), '%t', '%p', '%s' ),
			'i18n_strikeout_price_string'         => sprintf( _x( '<del>%1$s</del> <ins>%2$s</ins>', 'Sale/strikeout price', 'woocommerce-product-bundles' ), '%f', '%t' ),
			'i18n_partially_out_of_stock'         => __( 'Insufficient stock', 'woocommerce-product-bundles' ),
			'i18n_partially_on_backorder'         => __( 'Available on backorder', 'woocommerce' ),
			'i18n_select_options'                 => __( 'To continue, please choose product options&hellip;', 'woocommerce-product-bundles' ),
			'i18n_select_options_for'             => __( 'To continue, please choose %s options&hellip;', 'woocommerce-product-bundles' ),
			'i18n_string_list_item'               => _x( '&quot;%s&quot;', 'string list item', 'woocommerce-product-bundles' ),
			'i18n_string_list_sep'                => sprintf( _x( '%1$s, %2$s', 'string list item separator', 'woocommerce-product-bundles' ), '%s', '%v' ),
			'i18n_string_list_last_sep'           => sprintf( _x( '%1$s and %2$s', 'string list item last separator', 'woocommerce-product-bundles' ), '%s', '%v' ),
			'i18n_qty_string'                     => _x( ' &times; %s', 'qty string', 'woocommerce-product-bundles' ),
			'i18n_optional_string'                => _x( ' &mdash; %s', 'suffix', 'woocommerce-product-bundles' ),
			'i18n_optional'                       => __( 'optional', 'woocommerce-product-bundles' ),
			'i18n_contents'                       => __( 'Contents', 'woocommerce-product-bundles' ),
			'i18n_title_meta_string'              => sprintf( _x( '%1$s &ndash; %2$s', 'title followed by meta', 'woocommerce-product-bundles' ), '%t', '%m' ),
			'i18n_title_string'                   => sprintf( _x( '%1$s%2$s%3$s%4$s', 'title, quantity, price, suffix', 'woocommerce-product-bundles' ), '<span class="item_title">%t</span>', '<span class="item_qty">%q</span>', '', '<span class="item_suffix">%o</span>' ),
			'i18n_unavailable_text'               => __( 'This product is currently unavailable.', 'woocommerce-product-bundles' ),
			'i18n_validation_alert'               => __( 'Please resolve all pending configuration issues before adding this product to your cart.', 'woocommerce-product-bundles' ),
			'i18n_zero_qty_error'                 => __( 'Please choose at least 1 item.', 'woocommerce-product-bundles' ),
			'currency_symbol'                     => get_woocommerce_currency_symbol(),
			'currency_position'                   => esc_attr( stripslashes( get_option( 'woocommerce_currency_pos' ) ) ),
			'currency_format_num_decimals'        => wc_get_price_decimals(),
			'currency_format_decimal_sep'         => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
			'currency_format_thousand_sep'        => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
			'currency_format_trim_zeros'          => false === apply_filters( 'woocommerce_price_trim_zeros', false ) ? 'no' : 'yes',
			'price_display_suffix'                => esc_attr( get_option( 'woocommerce_price_display_suffix' ) ),
			'prices_include_tax'                  => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
			'tax_display_shop'                    => esc_attr( get_option( 'woocommerce_tax_display_shop' ) ),
			'calc_taxes'                          => esc_attr( get_option( 'woocommerce_calc_taxes' ) ),
			'photoswipe_enabled'                  => current_theme_supports( 'wc-product-gallery-lightbox' ) ? 'yes' : 'no'
		) );

		wp_localize_script( 'wc-add-to-cart-bundle', 'wc_bundle_params', $params );
	}

	/**
	 * Enqeue js that wraps bundled table items in a div in order to apply indentation reliably.
	 * This obviously sucks but if you can find a CSS-only way to do it better that works reliably with any theme out there, drop us a line, will you?
	 *
	 * @return void
	 */
	private function enqueue_bundled_table_item_js() {

		/**
		 * 'woocommerce_bundled_table_item_js_enqueued' filter.
		 *
		 * Use this filter to get rid of this ugly hack:
		 * Return 'false' and add your own CSS to indent '.bundled_table_item' elements.
		 *
		 * @since  5.5.0
		 *
		 * @param  boolean  $is_enqueued
		 */
		$is_enqueued = apply_filters( 'woocommerce_bundled_table_item_js_enqueued', $this->enqueued_bundled_table_item_js );

		if ( ! $is_enqueued ) {

			wc_enqueue_js( "
				var wc_pb_wrap_bundled_table_item = function() {
					jQuery( '.bundled_table_item td.product-name' ).each( function() {
						var el = jQuery( this );
						if ( el.find( '.bundled-product-name' ).length === 0 ) {
							el.wrapInner( '<div class=\"bundled-product-name bundled_table_item_indent\"></div>' );
						}
					} );
				};

				jQuery( 'body' ).on( 'updated_checkout updated_cart_totals', function() {
					wc_pb_wrap_bundled_table_item();
				} );

				wc_pb_wrap_bundled_table_item();
			" );

			$this->enqueued_bundled_table_item_js = true;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Single-product.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Display info notice when editing a bundle from the cart.
	 */
	public function add_edit_in_cart_notice() {

		global $product;

		if ( $product->is_type( 'bundle' ) && isset( $_GET[ 'update-bundle' ] ) ) {
			$updating_cart_key = wc_clean( $_GET[ 'update-bundle' ] );
			if ( isset( WC()->cart->cart_contents[ $updating_cart_key ] ) ) {
				$notice = sprintf ( __( 'You are currently editing &quot;%1$s&quot;. When finished, click the <strong>Update Cart</strong> button.', 'woocommerce-product-bundles' ), $product->get_title() );
				wc_add_notice( $notice, 'notice' );
			}
		}
	}

	/**
	 * Modify structured data for bundle-type products.
	 *
	 * @param  array       $data
	 * @param  WC_Product  $product
	 * @return array
	 */
	public function structured_product_data( $data, $product ) {

		if ( is_object( $product ) && $product->is_type( 'bundle' ) ) {
			$data[ 'price' ] = $product->get_bundle_price();
		}

		return $data;
	}

	/*
	|--------------------------------------------------------------------------
	| Cart.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Outputs a formatted subtotal.
	 *
	 * @param  WC_Product  $product
	 * @param  string      $subtotal
	 * @return string
	 */
	public function format_subtotal( $product, $subtotal ) {

		$cart               = WC()->cart;
		$taxable            = $product->is_taxable();
		$formatted_subtotal = wc_price( $subtotal );

		if ( $taxable ) {

			$tax_subtotal = WC_PB_Core_Compatibility::is_wc_version_gte( '3.2' ) ? $cart->get_subtotal_tax() : $cart->tax_total;

			if ( 'excl' === get_option( 'woocommerce_tax_display_cart' ) ) {

				if ( wc_prices_include_tax() && $tax_subtotal > 0 ) {
					$formatted_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
				}

			} else {

				if ( ! wc_prices_include_tax() && $tax_subtotal > 0 ) {
					$formatted_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
				}
			}
		}

		return $formatted_subtotal;
	}

	/**
	 * Modify the front-end price of bundled items and container items depending on their pricing setup.
	 *
	 * @param  double  $price
	 * @param  array   $values
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_price( $price, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundled_item_id = $cart_item[ 'bundled_item_id' ];

			if ( $bundled_item = $bundle_container_item[ 'data' ]->get_bundled_item( $bundled_item_id ) ) {

				if ( false === $bundled_item->is_priced_individually() && $cart_item[ 'line_subtotal' ] == 0 ) {
					$price = '';
				} elseif ( false === $bundled_item->is_price_visible( 'cart' ) ) {
					$price = '';
				} elseif ( WC_Product_Bundle::group_mode_has( $bundle_container_item[ 'data' ]->get_group_mode(), 'aggregated_prices' ) ) {
					if ( WC_PB()->compatibility->is_composited_cart_item( $bundle_container_item ) ) {
						$price = '';
					} elseif ( $price ) {
						$price = '<span class="bundled_table_item_price">' . $price . '</span>';
					}
				} elseif ( WC_PB()->compatibility->is_composited_cart_item( $bundle_container_item ) ) {
					if ( $price && apply_filters( 'woocommerce_add_composited_cart_item_prices', true, $cart_item, $cart_item_key ) ) {
						$price = '<span class="bundled_table_item_price">' . $price . '</span>';
					}
				}
			}

		} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
			$price = $this->get_container_cart_item_price( $price, $cart_item );
		}

		return $price;
	}

	/**
	 * Aggregates parent + child cart item prices.
	 *
	 * @param  string  $price
	 * @param  array   $cart_item
	 * @return string
	 */
	private function get_container_cart_item_price( $price, $cart_item ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

			$aggregate_prices  = WC_Product_Bundle::group_mode_has( $cart_item[ 'data' ]->get_group_mode(), 'aggregated_prices' );
			$bundled_item_keys = wc_pb_get_bundled_cart_items( $cart_item, WC()->cart->cart_contents, true );

			if ( $aggregate_prices ) {

				$tax_display_cart    = get_option( 'woocommerce_tax_display_cart' );
				$bundled_items_price = 0.0;
				$calc_type           = 'excl' === $tax_display_cart ? 'excl_tax' : 'incl_tax';
				$bundle_price        = WC_PB_Product_Prices::get_product_price( $cart_item[ 'data' ], array( 'price' => $cart_item[ 'data' ]->get_price(), 'calc' => $calc_type ) );

				foreach ( $bundled_item_keys as $bundled_item_key ) {

					if ( ! isset( WC()->cart->cart_contents[ $bundled_item_key ] ) ) {
						continue;
					}

					$bundled_cart_item      = WC()->cart->cart_contents[ $bundled_item_key ];
					$bundled_item_id        = $bundled_cart_item[ 'bundled_item_id' ];
					$bundled_item_raw_price = $bundled_cart_item[ 'data' ]->get_price();

					if ( WC_PB()->compatibility->is_subscription( $bundled_cart_item[ 'data' ] ) ) {

						$bundled_item = $cart_item[ 'data' ]->get_bundled_item( $bundled_item_id );

						if ( $bundled_item ) {
							$bundled_item_raw_recurring_fee = $bundled_cart_item[ 'data' ]->get_price();
							$bundled_item_raw_sign_up_fee   = (double) WC_Subscriptions_Product::get_sign_up_fee( $bundled_cart_item[ 'data' ] );
							$bundled_item_raw_price         = $bundled_item->get_up_front_subscription_price( $bundled_item_raw_recurring_fee, $bundled_item_raw_sign_up_fee, $bundled_cart_item[ 'data' ] );
						}
					}

					$bundled_cart_item_qty = $bundled_cart_item[ 'data' ]->is_sold_individually() ? 1 : $bundled_cart_item[ 'quantity' ] / $cart_item[ 'quantity' ];
					$bundled_item_price    = WC_PB_Product_Prices::get_product_price( $bundled_cart_item[ 'data' ], array( 'price' => $bundled_item_raw_price, 'calc' => $calc_type, 'qty' => $bundled_cart_item_qty ) );
					$bundled_items_price  += (double) $bundled_item_price;
				}

				$price = wc_price( (double) $bundle_price + $bundled_items_price );

			} elseif ( sizeof( $bundled_item_keys ) && $cart_item[ 'data' ]->contains( 'priced_individually' ) && $cart_item[ 'line_subtotal' ] == 0 ) {
				$price = '';
			}
		}

		return $price;
	}

	/**
	 * Aggregates parent + child cart item subtotals.
	 *
	 * @param  string  $subtotal
	 * @param  array   $cart_item
	 * @return string
	 */
	private function get_container_cart_item_subtotal( $subtotal, $cart_item ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

			$aggregate_subtotals = WC_Product_Bundle::group_mode_has( $cart_item[ 'data' ]->get_group_mode(), 'aggregated_subtotals' );
			$bundled_item_keys   = wc_pb_get_bundled_cart_items( $cart_item, WC()->cart->cart_contents, true );

			if ( $aggregate_subtotals ) {

				$tax_display_cart    = get_option( 'woocommerce_tax_display_cart' );
				$bundled_items_price = 0.0;
				$calc_type           = 'excl' === $tax_display_cart ? 'excl_tax' : 'incl_tax';
				$bundle_price        = WC_PB_Product_Prices::get_product_price( $cart_item[ 'data' ], array( 'price' => $cart_item[ 'data' ]->get_price(), 'calc' => $calc_type, 'qty' => $cart_item[ 'quantity' ] ) );

				foreach ( $bundled_item_keys as $bundled_item_key ) {

					if ( ! isset( WC()->cart->cart_contents[ $bundled_item_key ] ) ) {
						continue;
					}

					$bundled_cart_item      = WC()->cart->cart_contents[ $bundled_item_key ];
					$bundled_item_id        = $bundled_cart_item[ 'bundled_item_id' ];
					$bundled_item_raw_price = $bundled_cart_item[ 'data' ]->get_price();

					if ( WC_PB()->compatibility->is_subscription( $bundled_cart_item[ 'data' ] ) ) {

						$bundled_item = $cart_item[ 'data' ]->get_bundled_item( $bundled_item_id );

						if ( $bundled_item ) {
							$bundled_item_raw_recurring_fee = $bundled_cart_item[ 'data' ]->get_price();
							$bundled_item_raw_sign_up_fee   = (double) WC_Subscriptions_Product::get_sign_up_fee( $bundled_cart_item[ 'data' ] );
							$bundled_item_raw_price         = $bundled_item->get_up_front_subscription_price( $bundled_item_raw_recurring_fee, $bundled_item_raw_sign_up_fee, $bundled_cart_item[ 'data' ] );
						}
					}

					$bundled_item_price    = WC_PB_Product_Prices::get_product_price( $bundled_cart_item[ 'data' ], array( 'price' => $bundled_item_raw_price, 'calc' => $calc_type, 'qty' => $bundled_cart_item[ 'quantity' ] ) );
					$bundled_items_price  += (double) $bundled_item_price;
				}

				$subtotal = $this->format_subtotal( $cart_item[ 'data' ], (double) $bundle_price + $bundled_items_price );

			} elseif ( sizeof( $bundled_item_keys ) && $cart_item[ 'data' ]->contains( 'priced_individually' ) && $cart_item[ 'line_subtotal' ] == 0 ) {
				$subtotal = '';
			}
		}

		return $subtotal;
	}

	/**
	 * Modifies line item subtotals in the 'cart.php' & 'review-order.php' templates.
	 *
	 * @param  string  $subtotal
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item_key = wc_pb_get_bundled_cart_item_container( $cart_item, WC()->cart->cart_contents, true ) ) {

			$bundle_container_item = WC()->cart->cart_contents[ $bundle_container_item_key ];
			$bundled_item_id       = $cart_item[ 'bundled_item_id' ];

			if ( $bundled_item = $bundle_container_item[ 'data' ]->get_bundled_item( $bundled_item_id ) ) {

				$hide_subtotal = false === $bundled_item->is_price_visible( 'cart' ) || ( false === $bundled_item->is_priced_individually() && $cart_item[ 'line_subtotal' ] == 0 ) || WC_PB()->compatibility->is_composited_cart_item( $bundle_container_item );

				if ( $hide_subtotal ) {

					$subtotal = '';

				} else {

					$show_subtotal_string = WC_Product_Bundle::group_mode_has( $bundle_container_item[ 'data' ]->get_group_mode(), 'aggregated_subtotals' );

					if ( $show_subtotal_string ) {
						$subtotal = '<span class="bundled_table_item_subtotal">' . sprintf( _x( '%1$s: %2$s', 'bundled product subtotal', 'woocommerce-product-bundles' ), __( 'Subtotal', 'woocommerce-product-bundles' ), $subtotal ) . '</span>';
					}
				}
			}

		} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
			$subtotal = $this->get_container_cart_item_subtotal( $subtotal, $cart_item );
		}

		return $subtotal;
	}

	/**
	 * Bundled item quantities can't be changed individually. When adjusting quantity for the container item, the bundled products must follow.
	 *
	 * @param  int     $quantity
	 * @param  string  $cart_item_key
	 * @return int
	 */
	public function cart_item_quantity( $quantity, $cart_item_key ) {

		$cart_item = WC()->cart->cart_contents[ $cart_item_key ];

		if ( $container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundled_item_id = $cart_item[ 'bundled_item_id' ];
			$bundled_item    = $container_item[ 'data' ]->get_bundled_item( $bundled_item_id );

			$min_quantity = $bundled_item->get_quantity( 'min' );
			$max_quantity = $bundled_item->get_quantity( 'max' );

			if ( $min_quantity === $max_quantity ) {

				$quantity = $cart_item[ 'quantity' ];

			} else {

				$parent_quantity = $container_item[ 'quantity' ];

				$min_qty = $parent_quantity * $min_quantity;
				$max_qty = '' !== $max_quantity ? $parent_quantity * $max_quantity : '';

				if ( ( $max_qty > $min_qty || '' === $max_qty ) && ! $cart_item[ 'data' ]->is_sold_individually() ) {

					$quantity = woocommerce_quantity_input( array(
						'input_name'  => "cart[{$cart_item_key}][qty]",
						'input_value' => $cart_item[ 'quantity' ],
						'min_value'   => $min_qty,
						'max_value'   => $max_qty,
						'step'        => $parent_quantity
					), $cart_item[ 'data' ], false );

				} else {
					$quantity = $cart_item[ 'quantity' ];
				}
			}
		}

		return $quantity;
	}

	/**
	 * Bundled items can't be removed individually from the cart - this hides the remove buttons.
	 *
	 * @param  string  $link
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_remove_link( $link, $cart_item_key ) {

		$cart_item = WC()->cart->cart_contents[ $cart_item_key ];

		if ( $bundle_container_item_key = wc_pb_get_bundled_cart_item_container( $cart_item, false, true ) ) {

			$bundle_container_item = WC()->cart->cart_contents[ $bundle_container_item_key ];

			$bundle = $bundle_container_item[ 'data' ];

			if ( false === WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'parent_item' ) ) {

				/*
				 * If it's the first child, show a button that relays the remove action to the parent.
				 * Here we assume that the first child is visible.
				 */
				$bundled_cart_item_keys = wc_pb_get_bundled_cart_items( $bundle_container_item, false, true );

				if ( empty( $bundled_cart_item_keys ) || current( $bundled_cart_item_keys ) !== $cart_item_key ) {
					return '';
				} else {
					$link = sprintf(
						'<a href="%s" class="remove remove_bundle" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
						esc_url( WC_PB_Core_Compatibility::wc_get_cart_remove_url( $bundle_container_item_key ) ),
						__( 'Remove this item', 'woocommerce' ),
						esc_attr( $bundle->get_id() ),
						esc_attr( $bundle->get_sku() )
					);
				}

			} else {
				return '';
			}
		}

		return $link;
	}

	/**
	 * Visibility of bundled item in cart.
	 *
	 * @param  boolean  $visible
	 * @param  array    $cart_item
	 * @param  string   $cart_item_key
	 * @return boolean
	 */
	public function cart_item_visible( $visible, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle          = $bundle_container_item[ 'data' ];
			$bundled_item_id = $cart_item[ 'bundled_item_id' ];

			if ( $bundled_item = $bundle->get_bundled_item( $bundled_item_id ) ) {
				if ( false === $bundled_item->is_visible( 'cart' ) ) {
					$visible = false;
				}
			}

		} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

			$bundle = $cart_item[ 'data' ];

			if ( false === WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'parent_item' ) ) {
				$visible = false;
			}
		}

		return $visible;
	}

	/**
	 * Override bundled item title in cart/checkout templates.
	 *
	 * @param  string  $content
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_title( $content, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle = $bundle_container_item[ 'data' ];

			if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'child_item_indent' ) ) {
				$this->enqueue_bundled_table_item_js();
			}

			if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'faked_parent_item' ) ) {

				$bundled_cart_item_keys = wc_pb_get_bundled_cart_items( $bundle_container_item, false, true );

				if ( ! empty( $bundled_cart_item_keys ) && current( $bundled_cart_item_keys ) === $cart_item_key ) {
					if ( function_exists( 'is_cart' ) && is_cart() && ! did_action( 'woocommerce_before_mini_cart' ) ) {
						if ( $bundle->is_editable_in_cart( $bundle_container_item ) ) {
							$content = sprintf( _x( '%1$s<br/><a class="edit_bundle_in_cart_text edit_in_cart_text" href="%2$s"><small>%3$s</small></a>', 'edit in cart text', 'woocommerce-product-bundles' ), $content, $bundle->get_permalink( $bundle_container_item ), __( '(click to edit)', 'woocommerce-product-bundles' ) );
						}
					}
				}
			}

		} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

			$bundle = $cart_item[ 'data' ];

			if ( function_exists( 'is_cart' ) && is_cart() && ! did_action( 'woocommerce_before_mini_cart' ) ) {
				if ( $bundle->is_editable_in_cart( $cart_item ) ) {
					$content = sprintf( _x( '%1$s<br/><a class="edit_bundle_in_cart_text edit_in_cart_text" href="%2$s"><small>%3$s</small></a>', 'edit in cart text', 'woocommerce-product-bundles' ), $content, $bundle->get_permalink( $cart_item ), __( '(click to edit)', 'woocommerce-product-bundles' ) );
				}
			}
		}

		return $content;
	}

	/**
	 * Change the tr class of bundled items in cart templates to allow their styling.
	 *
	 * @param  string  $classname
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_class( $classname, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle = $bundle_container_item[ 'data' ];

			if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'child_item_indent' ) ) {

				if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'faked_parent_item' ) ) {

					// Ensure this isn't the first child (shamelessly assuming that the first one is visible).
					$bundled_cart_item_keys = wc_pb_get_bundled_cart_items( $bundle_container_item, false, true );

					if ( empty( $bundled_cart_item_keys ) || current( $bundled_cart_item_keys ) !== $cart_item_key ) {
						$classname .= ' bundled_table_item';
					}

				} else {
					$classname .= ' bundled_table_item';
				}
			}

		} elseif ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
			$classname .= ' bundle_table_item';
		}

		return $classname;
	}

	/**
	 * Filters the reported number of cart items.
	 *
	 * @param  int  $count
	 * @return int
	 */
	public function cart_contents_count( $count ) {

		$cart     = WC()->cart->get_cart();
		$subtract = 0;

		foreach ( $cart as $cart_item_key => $cart_item ) {
			if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {

				$parent_item_visible = $this->cart_item_visible( true, $cart_item, $cart_item_key );

				if ( ! $parent_item_visible ) {
					$subtract += $cart_item[ 'quantity' ];
				}

				$bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item );

				foreach ( $bundled_cart_items as $bundled_item_key => $bundled_cart_item ) {
					if ( ! $parent_item_visible ) {
						if ( ! $this->cart_item_visible( true, $bundled_cart_item, $bundled_item_key ) ) {
							$subtract += $bundled_cart_item[ 'quantity' ];
						}
					} else {
						$subtract += $bundled_cart_item[ 'quantity' ];
					}
				}
			}
		}

		return $count - $subtract;
	}

	/**
	 * Add "Part of" and "Purchased with" cart item data to bundled items.
	 *
	 * @param  array  $data
	 * @param  array  $cart_item
	 * @return array
	 */
	public function cart_item_data( $data, $cart_item ) {

		if ( $container = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle = $container[ 'data' ];

			if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'child_item_meta' ) ) {
				$data[] = array(
					'key'   => __( 'Part of', 'woocommerce-product-bundles' ),
					'value' => $bundle->get_title()
				);
			}
		}

		return $data;
	}

	/**
	 * Hide thumbnail in cart when 'Hide thumbnail' option is selected.
	 *
	 * @param  string  $image
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */

	public function cart_item_thumbnail( $image, $cart_item, $cart_item_key ) {

		if ( $bundle_container_item = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundled_item_id = $cart_item[ 'bundled_item_id' ];

			if ( $bundled_item = $bundle_container_item[ 'data' ]->get_bundled_item( $bundled_item_id) ) {

				if ( false === $bundled_item->is_thumbnail_visible() ) {
					$image = '';
				}
			}
		}

		return $image;
	}


	/**
	 * Add cart widget filters.
	 *
	 * @return void
	 */
	public function add_cart_widget_filters() {
		add_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'cart_widget_item_visible' ), 10, 3 );
		add_filter( 'woocommerce_widget_cart_item_quantity', array( $this, 'cart_widget_item_qty' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_widget_container_item_name' ), 10, 3 );
	}

	/**
	 * Remove cart widget filters.
	 *
	 * @return void
	 */
	public function remove_cart_widget_filters() {
		remove_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'cart_widget_item_visible' ), 10, 3 );
		remove_filter( 'woocommerce_widget_cart_item_quantity', array( $this, 'cart_widget_item_qty' ), 10, 3 );
		remove_filter( 'woocommerce_cart_item_name', array( $this, 'cart_widget_container_item_name' ), 10, 3 );
	}

	/**
	 * Only show bundled items in the mini cart if their parent line item is hidden.
	 *
	 * @param  boolean  $show
	 * @param  array    $cart_item
	 * @param  string   $cart_item_key
	 * @return boolean
	 */
	public function cart_widget_item_visible( $show, $cart_item, $cart_item_key ) {

		if ( $container = wc_pb_get_bundled_cart_item_container( $cart_item ) ) {

			$bundle = $container[ 'data' ];

			if ( WC_Product_Bundle::group_mode_has( $bundle->get_group_mode(), 'parent_item' ) ) {
				$show = false;
			}
		}

		return $show;
	}

	/**
	 * Tweak bundle container qty.
	 *
	 * @param  bool    $qty
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return bool
	 */
	public function cart_widget_item_qty( $qty, $cart_item, $cart_item_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
			$qty = '<span class="quantity">' . apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $cart_item[ 'data' ], $cart_item[ 'quantity' ] ), $cart_item, $cart_item_key ) . '</span>';
		}

		return $qty;
	}

	/**
	 * Tweak bundle container name.
	 *
	 * @param  bool    $show
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return bool
	 */
	public function cart_widget_container_item_name( $name, $cart_item, $cart_item_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
			$name = WC_PB_Helpers::format_product_shop_title( $name, $cart_item[ 'quantity' ] );
		}

		return $name;
	}

	/*
	|--------------------------------------------------------------------------
	| Orders.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Modify the subtotal of order items depending on their pricing setup.
	 *
	 * @param  string         $subtotal
	 * @param  WC_Order_Item  $item
	 * @param  WC_Order       $order
	 * @return string
	 */
	public function order_item_subtotal( $subtotal, $item, $order ) {

		// If it's a bundled item...
		if ( $parent_item = wc_pb_get_bundled_order_item_container( $item, $order ) ) {

			$bundled_item_priced_individually = $item->get_meta( '_bundled_item_priced_individually', true );
			$bundled_item_price_hidden        = $item->get_meta( '_bundled_item_price_hidden', true );

			// Back-compat.
			if ( ! in_array( $bundled_item_priced_individually, array( 'yes', 'no' ) ) ) {
				$bundled_item_priced_individually = isset( $parent_item[ 'per_product_pricing' ] ) ? $parent_item[ 'per_product_pricing' ] : get_post_meta( $parent_item[ 'product_id' ], '_wc_pb_v4_per_product_pricing', true );
			}

			$hide_subtotal = ( 'no' === $bundled_item_priced_individually && $item->get_subtotal( 'edit' ) == 0 ) || 'yes' === $bundled_item_price_hidden || WC_PB()->compatibility->is_composited_order_item( $parent_item, $order );

			if ( $hide_subtotal ) {

				$subtotal = '';

			} elseif ( false === WC_PB()->compatibility->is_pip( 'invoice' ) ) {

				$group_mode = $parent_item->get_meta( '_bundle_group_mode', true );
				$group_mode = $group_mode ? $group_mode : 'parent';

				$show_subtotal_string = WC_Product_Bundle::group_mode_has( $group_mode, 'aggregated_subtotals' );

				if ( $show_subtotal_string ) {
					$subtotal = '<span class="bundled_table_item_subtotal">' . sprintf( _x( '%1$s: %2$s', 'bundled product subtotal', 'woocommerce-product-bundles' ), __( 'Subtotal', 'woocommerce-product-bundles' ), $subtotal ) . '</span>';
				}
			}
		}

		// If it's a bundle (parent item)...
		if ( wc_pb_is_bundle_container_order_item( $item ) ) {

			if ( ! isset( $item->child_subtotals_added ) ) {

				$group_mode = $item->get_meta( '_bundle_group_mode', true );
				$group_mode = $group_mode ? $group_mode : 'parent';

				$children            = wc_pb_get_bundled_order_items( $item, $order );
				$aggregate_subtotals = WC_Product_Bundle::group_mode_has( $group_mode, 'aggregated_subtotals' ) && false === WC_PB()->compatibility->is_pip( 'invoice' );

				// Aggregate subtotals if required the bundle's group mode. Important: Don't aggregate when rendering PIP invoices!
				if ( $aggregate_subtotals ) {

					if ( ! empty( $children ) ) {

						// Create a clone to ensure the original item will not be modified.
						$cloned_item = clone $item;

						foreach ( $children as $child ) {
							$cloned_item->set_subtotal( $cloned_item->get_subtotal( 'edit' ) + $child->get_subtotal( 'edit' ) );
							$cloned_item->set_subtotal_tax( $cloned_item->get_subtotal_tax( 'edit' ) + $child->get_subtotal_tax( 'edit' ) );
						}

						$cloned_item->child_subtotals_added = 'yes';

						$subtotal = $order->get_formatted_line_subtotal( $cloned_item );
					}

				} elseif ( sizeof( $children ) && $item->get_subtotal( 'edit' ) == 0 ) {
					$subtotal = '';
				}
			}
		}

		return $subtotal;
	}

	/**
	 * Visibility of bundled item in orders.
	 *
	 * @param  boolean  $visible
	 * @param  array    order_item
	 * @return boolean
	 */
	public function order_item_visible( $visible, $order_item ) {

		if ( wc_pb_maybe_is_bundled_order_item( $order_item ) ) {

			$bundled_item_hidden = $order_item->get_meta( '_bundled_item_hidden' );

			if ( ! empty( $bundled_item_hidden ) ) {
				$visible = false;
			}

		} elseif ( wc_pb_is_bundle_container_order_item( $order_item ) ) {

			$group_mode = $order_item->get_meta( '_bundle_group_mode', true );
			$group_mode = $group_mode ? $group_mode : 'parent';

			if ( false === WC_Product_Bundle::group_mode_has( $group_mode, 'parent_item' ) ) {
				$visible = false;
			}
		}

		return $visible;
	}

	/**
	 * Override bundled item title in order-details template.
	 *
	 * @param  string  $content
	 * @param  array   $order_item
	 * @return string
	 */
	public function order_item_title( $content, $order_item ) {

		if ( false !== $this->order_item_order && wc_pb_is_bundled_order_item( $order_item, $this->order_item_order ) ) {

			$this->order_item_order = false;

			$group_mode = $order_item->get_meta( '_bundle_group_mode', true );
			$group_mode = $group_mode ? $group_mode : 'parent';

			if ( WC_Product_Bundle::group_mode_has( $group_mode, 'child_item_indent' ) ) {
				if ( did_action( 'woocommerce_view_order' ) || did_action( 'woocommerce_thankyou' ) || did_action( 'before_woocommerce_pay' ) || did_action( 'woocommerce_account_view-subscription_endpoint' ) ) {
					$this->enqueue_bundled_table_item_js();
				}
			}
		}

		return $content;
	}

	/**
	 * Add class to bundled items in order templates.
	 *
	 * @param  string  $classname
	 * @param  array   $order_item
	 * @return string
	 */
	public function order_item_class( $classname, $order_item, $order ) {

		if ( $bundle_container_order_item = wc_pb_get_bundled_order_item_container( $order_item, $order ) ) {

			$group_mode = $bundle_container_order_item->get_meta( '_bundle_group_mode', true );
			$group_mode = $group_mode ? $group_mode : 'parent';

			if ( WC_Product_Bundle::group_mode_has( $group_mode, 'child_item_indent' ) ) {

				if ( WC_Product_Bundle::group_mode_has( $group_mode, 'faked_parent_item' ) ) {

					// Ensure this isn't the first child.
					$bundled_order_item_ids = wc_pb_get_bundled_order_items( $bundle_container_order_item, $order, true );

					if ( empty( $bundled_order_item_ids ) || current( $bundled_order_item_ids ) !== $order_item->get_id() ) {
						$classname .= ' bundled_table_item';
					}

				} else {
					$classname .= ' bundled_table_item';
				}
			}

			$this->order_item_order = $order;

		} elseif ( wc_pb_is_bundle_container_order_item( $order_item ) ) {
			$classname .= ' bundle_table_item';
		}

		return $classname;
	}

	/**
	 * Filters the reported number of order items.
	 *
	 * @param  int       $count
	 * @param  string    $type
	 * @param  WC_Order  $order
	 * @return int
	 */
	public function order_item_count( $count, $type, $order ) {

		$subtract = 0;

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {

			foreach ( $order->get_items() as $item ) {
				if ( wc_pb_is_bundle_container_order_item( $item, $order ) ) {

					$parent_item_visible = $this->order_item_visible( true, $item );

					if ( ! $parent_item_visible ) {
						$subtract += $item->get_quantity();
					}


					$bundled_order_items = wc_pb_get_bundled_order_items( $item, $order );

					foreach ( $bundled_order_items as $bundled_item_key => $bundled_order_item ) {
						if ( ! $parent_item_visible ) {
							if ( ! $this->order_item_visible( true, $bundled_order_item ) ) {
								$subtract += $bundled_order_item->get_quantity();
							}
						} else {
							$subtract += $bundled_order_item->get_quantity();
						}
					}
				}
			}
		}

		return $count - $subtract;
	}

	/**
	 * Indent bundled items in emails.
	 *
	 * @param  string  $css
	 * @return string
	 */
	public function email_styles( $css ) {
		$css = $css . ".bundled_table_item td:nth-child(1) { padding-left: 2.5em !important; } .bundled_table_item td { border-top: none; font-size: 0.875em; } #body_content table tr.bundled_table_item td ul.wc-item-meta { font-size: inherit; }";
		return $css;
	}

	/*
	|--------------------------------------------------------------------------
	| Archives.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Used to fix QuickView support when:
	 * - ajax add-to-cart is active and
	 * - QuickView operates without a separate button.
	 * Since WC 2.5+ this is (almost) a relic.
	 *
	 * @param  string      $link
	 * @param  WC_Product  $product
	 * @return string
	 */
	public function loop_add_to_cart_link( $link, $product ) {

		if ( $product->is_type( 'bundle' ) ) {

			if ( ! $product->is_in_stock() || $product->requires_input() ) {
				$link = str_replace( array( 'product_type_bundle', 'ajax_add_to_cart' ), array( 'product_type_bundle product_type_bundle_input_required', '' ), $link );
			}
		}

		return $link;
	}

	/*
	|--------------------------------------------------------------------------
	| Other.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Inserts bundle contents after main wishlist bundle item is displayed.
	 *
	 * @param  array  $item
	 * @param  array  $wishlist
	 * @return void
	 */
	public function wishlist_after_list_item_name( $item, $wishlist ) {

		if ( $item[ 'data' ]->is_type( 'bundle' ) && ! empty( $item[ 'stamp' ] ) ) {

			echo '<dl>';

			foreach ( $item[ 'stamp' ] as $bundled_item_id => $bundled_item_data ) {

				$bundled_product = wc_get_product( $bundled_item_data[ 'product_id' ] );

				if ( empty( $bundled_product ) ) {
					continue;
				}

				echo '<dt class="bundled_title_meta wishlist_bundled_title_meta">' . $bundled_product->get_title() . ' <strong class="bundled_quantity_meta wishlist_bundled_quantity_meta product-quantity">&times; ' . $bundled_item_data[ 'quantity' ] . '</strong></dt>';

				if ( ! empty ( $bundled_item_data[ 'attributes' ] ) ) {

					$attributes = '';

					foreach ( $bundled_item_data[ 'attributes' ] as $attribute_name => $attribute_value ) {

						$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $attribute_name ) ) );

						// If this is a term slug, get the term's nice name.
			            if ( taxonomy_exists( $taxonomy ) ) {

			            	$term = get_term_by( 'slug', $attribute_value, $taxonomy );

			            	if ( ! is_wp_error( $term ) && $term && $term->name ) {
			            		$attribute_value = $term->name;
			            	}

			            	$label = wc_attribute_label( $taxonomy );

			            // If this is a custom option slug, get the options name.
			            } else {

							$attribute_value    = apply_filters( 'woocommerce_variation_option_name', $attribute_value );
							$product_attributes = $bundled_product->get_attributes();

							if ( isset( $product_attributes[ str_replace( 'attribute_', '', $attribute_name ) ] ) ) {
								$label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $attribute_name ) ][ 'name' ] );
							} else {
								$label = $attribute_name;
							}
						}

						$attributes = $attributes . $label . ': ' . $attribute_value . ', ';
					}
					echo '<dd class="bundled_attribute_meta wishlist_bundled_attribute_meta">' . rtrim( $attributes, ', ' ) . '</dd>';
				}
			}
			echo '</dl>';
			echo '<p class="bundled_notice wishlist_component_notice">' . __( '*', 'woocommerce-product-bundles' ) . '&nbsp;&nbsp;<em>' . __( 'For accurate pricing details, please add the product to your cart.', 'woocommerce-product-bundles' ) . '</em></p>';
		}
	}

	/**
	 * Modifies wishlist bundle item price - the precise sum cannot be displayed reliably unless the item is added to the cart.
	 *
	 * @param  double  $price
	 * @param  array   $item
	 * @param  array   $wishlist
	 * @return string  $price
	 */
	public function wishlist_list_item_price( $price, $item, $wishlist ) {

		if ( $item[ 'data' ]->is_type( 'bundle' ) && ! empty( $item[ 'stamp' ] ) )
			$price = __( '*', 'woocommerce-product-bundles' );

		return $price;
	}

	/**
	 * Enhance price filter widget meta query to include results based on max '_wc_sw_max_price' meta.
	 *
	 * @param  array     $meta_query
	 * @param  WC_Query  $wc_query
	 * @return array
	 */
	public function price_filter_query_params( $meta_query, $wc_query ) {

		if ( isset( $meta_query[ 'price_filter' ] ) && isset( $meta_query[ 'price_filter' ][ 'price_filter' ] ) && ! isset( $meta_query[ 'price_filter' ][ 'sw_price_filter' ] ) ) {

			$min = isset( $_GET[ 'min_price' ] ) ? floatval( $_GET[ 'min_price' ] ) : 0;
			$max = isset( $_GET[ 'max_price' ] ) ? floatval( $_GET[ 'max_price' ] ) : 9999999999;

			$price_meta_query = $meta_query[ 'price_filter' ];
			$price_meta_query = array(
				'sw_price_filter' => true,
				'price_filter'    => true,
				'relation'        => 'OR',
				$price_meta_query,
				array(
					'relation' => 'AND',
					array(
						'key'     => '_price',
						'compare' => '<=',
						'type'    => 'DECIMAL',
						'value'   => $max
					),
					array(
						'key'     => '_wc_sw_max_price',
						'compare' => '>=',
						'type'    => 'DECIMAL',
						'value'   => $min
					)
				)
			);

			$meta_query[ 'price_filter' ] = $price_meta_query;
		}

		return $meta_query;
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated.
	|--------------------------------------------------------------------------
	*/

	public function order_table_item_title( $content, $order_item ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', __CLASS__ . '::order_item_title()' );
		return $this->order_item_title( $content, $order_item );
	}
	public function woo_bundles_loop_add_to_cart_link( $link, $product ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::loop_add_to_cart_link()' );
		return $this->loop_add_to_cart_link( $link, $product );
	}
	public function woo_bundles_in_cart_item_title( $content, $cart_item_values, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_title()' );
		return $this->cart_item_title( $content, $cart_item_values, $cart_item_key );
	}
	public function woo_bundles_order_table_item_title( $content, $order_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::order_item_title()' );
		return $this->order_item_title( $content, $order_item );
	}
	public function woo_bundles_table_item_class( $classname, $values ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::table_item_class()' );
		return false !== strpos( $classname, 'cart_item' ) ? $this->cart_item_class( $classname, $values, false ) : $this->order_item_class( $classname, $values, false );
	}
	public function woo_bundles_frontend_scripts() {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::frontend_scripts()' );
		return $this->frontend_scripts();
	}
	public function woo_bundles_cart_contents_count( $count ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_contents_count()' );
		return $this->cart_contents_count( $count );
	}
	public function woo_bundles_add_cart_widget_filters() {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::add_cart_widget_filters()' );
		return $this->add_cart_widget_filters();
	}
	public function woo_bundles_remove_cart_widget_filters() {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::remove_cart_widget_filters()' );
		return $this->remove_cart_widget_filters();
	}
	public function woo_bundles_order_item_visible( $visible, $order_item ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::order_item_visible()' );
		return $this->order_item_visible( $visible, $order_item );
	}
	public function woo_bundles_cart_item_visible( $visible, $cart_item, $cart_item_key ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::cart_item_visible()' );
		return $this->cart_item_visible( $visible, $cart_item, $cart_item_key );
	}
	public function woo_bundles_email_styles( $css ) {
		_deprecated_function( __METHOD__ . '()', '5.0.0', __CLASS__ . '::email_styles()' );
		return $this->email_styles( $css );
	}
}
