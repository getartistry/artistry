<?php
/**
 * WC_PB_CP_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.14.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Composite Products Compatibility.
 *
 * @version  5.7.8
 */
class WC_PB_CP_Compatibility {

	private static $current_component = false;

	/**
	 * Add hooks.
	 */
	public static function init() {

		/*--------------------*/
		/*  Cart Permalinks   */
		/*--------------------*/

		add_filter( 'woocommerce_composite_cart_permalink_args', array( __CLASS__, 'composited_bundle_permalink_args' ), 10, 3 );

		/*--------------------*/
		/*  Prices            */
		/*--------------------*/

		// Price calculations.
		add_filter( 'woocommerce_get_composited_product_price', array( __CLASS__, 'composited_bundle_price' ), 10, 3 );

		/*--------------------*/
		/*  Templates         */
		/*--------------------*/

		// Show bundle type products using the bundle-product.php composited product template.
		add_action( 'woocommerce_composite_show_composited_product_bundle', array( __CLASS__, 'composite_show_product_bundle' ), 10, 3 );

		/*--------------------*/
		/*  Cart and Orders   */
		/*--------------------*/

		// Validate bundle type component selections.
		add_filter( 'woocommerce_composite_component_add_to_cart_validation', array( __CLASS__, 'composite_validate_bundle_data' ), 10, 8 );

		// Add bundle identifier to composited item stamp.
		add_filter( 'woocommerce_composite_component_cart_item_identifier', array( __CLASS__, 'composite_bundle_cart_item_stamp' ), 10, 2 );

		// Apply component prefix to bundle input fields.
		add_filter( 'woocommerce_product_bundle_field_prefix', array( __CLASS__, 'bundle_field_prefix' ), 10, 2 );

		// Hook into composited product add-to-cart action to add bundled items since 'woocommerce-add-to-cart' action cannot be used recursively.
		add_action( 'woocommerce_composited_add_to_cart', array( __CLASS__, 'add_bundle_to_cart' ), 10, 6 );

		// Link bundled cart/order items with composite.
		add_filter( 'woocommerce_cart_item_is_child_of_composite', array( __CLASS__, 'bundled_cart_item_is_child_of_composite' ), 10, 5 );
		add_filter( 'woocommerce_order_item_is_child_of_composite', array( __CLASS__, 'bundled_order_item_is_child_of_composite' ), 10, 4 );

		// Tweak the appearance of bundle container items in various templates.
		add_filter( 'woocommerce_cart_item_name', array( __CLASS__, 'composited_bundle_in_cart_item_title' ), 9, 3 );
		add_filter( 'woocommerce_cart_item_name', array( __CLASS__, 'composited_bundle_remove_in_cart_item_title' ), 11, 3 );
		add_filter( 'woocommerce_cart_item_quantity', array( __CLASS__, 'composited_bundle_in_cart_item_quantity' ), 11, 2 );
		add_filter( 'woocommerce_composited_cart_item_quantity_html', array( __CLASS__, 'composited_bundle_checkout_item_quantity' ), 10, 2 );
		add_filter( 'woocommerce_order_item_visible', array( __CLASS__, 'composited_bundle_order_item_visible' ), 10, 2 );
		add_filter( 'woocommerce_order_item_name', array( __CLASS__, 'composited_bundle_order_table_item_title' ), 9, 2 );
		add_filter( 'woocommerce_composited_order_item_quantity_html', array( __CLASS__, 'composited_bundle_order_table_item_quantity' ), 11, 2 );

		// Disable edit-in-cart feature if part of a composite.
		add_filter( 'woocommerce_bundle_is_editable_in_cart', array( __CLASS__, 'composited_bundle_not_editable_in_cart' ), 10, 3 );

		// Value & weight aggregation in packages.
		add_filter( 'woocommerce_composited_package_item', array( __CLASS__, 'composited_bundle_container_package_item' ), 10, 3 );
	}

	/**
	 * Aggregate value and weight of bundled items in shipping packages when a bundle is composited.
	 *
	 * @param  array   $cart_item_data
	 * @param  string  $cart_item_key
	 * @param  string  $container_cart_item_key
	 * @return array
	 */
	public static function composited_bundle_container_package_item( $cart_item_data, $cart_item_key, $container_cart_item_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item_data ) ) {

			if ( $cart_item_data[ 'data' ]->meta_exists( '_wc_cp_composited_value' ) ) {

				$composited_bundle_value  = $cart_item_data[ 'data' ]->get_meta( '_wc_cp_composited_value', true );
				$composited_bundle_weight = $cart_item_data[ 'data' ]->get_meta( '_wc_cp_composited_weight', true );

				$bundle     = unserialize( serialize( $cart_item_data[ 'data' ] ) );
				$bundle_qty = $cart_item_data[ 'quantity' ];

				// Aggregate weights.

				$bundled_weight = 0;

				// Aggregate prices.

				$bundled_value = 0;

				$bundle_totals = array(
					'line_subtotal'     => $cart_item_data[ 'line_subtotal' ],
					'line_total'        => $cart_item_data[ 'line_total' ],
					'line_subtotal_tax' => $cart_item_data[ 'line_subtotal_tax' ],
					'line_tax'          => $cart_item_data[ 'line_tax' ],
					'line_tax_data'     => $cart_item_data[ 'line_tax_data' ]
				);

				foreach ( wc_pb_get_bundled_cart_items( $cart_item_data, WC()->cart->cart_contents, true ) as $child_item_key ) {

					$child_cart_item_data   = WC()->cart->cart_contents[ $child_item_key ];
					$bundled_product        = $child_cart_item_data[ 'data' ];
					$bundled_product_qty    = $child_cart_item_data[ 'quantity' ];
					$bundled_product_value  = $bundled_product->get_meta( '_wc_pb_bundled_value', true );
					$bundled_product_weight = $bundled_product->get_meta( '_wc_pb_bundled_weight', true );

					// Aggregate price.
					if ( $bundled_product_value ) {

						$bundled_value += $bundled_product_value * $bundled_product_qty;

						$bundle_totals[ 'line_subtotal' ]     += $child_cart_item_data[ 'line_subtotal' ];
						$bundle_totals[ 'line_total' ]        += $child_cart_item_data[ 'line_total' ];
						$bundle_totals[ 'line_subtotal_tax' ] += $child_cart_item_data[ 'line_subtotal_tax' ];
						$bundle_totals[ 'line_tax' ]          += $child_cart_item_data[ 'line_tax' ];

						$child_item_line_tax_data = $child_cart_item_data[ 'line_tax_data' ];

						$bundle_totals[ 'line_tax_data' ][ 'total' ]    = array_merge( $bundle_totals[ 'line_tax_data' ][ 'total' ], $child_item_line_tax_data[ 'total' ] );
						$bundle_totals[ 'line_tax_data' ][ 'subtotal' ] = array_merge( $bundle_totals[ 'line_tax_data' ][ 'subtotal' ], $child_item_line_tax_data[ 'subtotal' ] );
					}

					// Aggregate weight.
					if ( $bundled_product_weight ) {
						$bundled_weight += $bundled_product_weight * $bundled_product_qty;
					}
				}

				$cart_item_data = array_merge( $cart_item_data, $bundle_totals );

				if ( $bundled_value > 0 ) {
					$bundle->add_meta_data( '_wc_cp_composited_value', (double) $composited_bundle_value + $bundled_value / $bundle_qty, true );
				}

				if ( $bundled_weight > 0 ) {
					$bundle->add_meta_data( '_wc_cp_composited_weight', (double) $composited_bundle_weight + $bundled_weight / $bundle_qty, true );
				}

				$cart_item_data[ 'data' ] = $bundle;
			}
		}

		return $cart_item_data;
	}

	/**
	 * Bundles are not directly editable in cart if part of a composite.
	 * They inherit the setting of their container and can only be edited within that scope of their container - @see 'composited_bundle_permalink_args()'.
	 *
	 * @param  boolean            $editable
	 * @param  WC_Product_Bundle  $bundle
	 * @param  array              $cart_item
	 * @return boolean
	 */
	public static function composited_bundle_not_editable_in_cart( $editable, $bundle, $cart_item ) {
		if ( is_array( $cart_item ) && wc_cp_is_composited_cart_item( $cart_item ) ) {
			$editable = false;
		}
		return $editable;
	}

	/**
	 * Add some contextual info to bundle validation messages.
	 *
	 * @param  string $message
	 * @return string
	 */
	public static function component_bundle_error_message_context( $message ) {

		if ( false !== self::$current_component ) {
			$message = sprintf( __( 'Please check your &quot;%1$s&quot; configuration: %2$s', 'woocommerce-composite-products' ), self::$current_component->get_title( true ), $message );
		}

		return $message;
	}

	/**
	 * Add permalink data for composited bundles to support CP cart editing.
	 *
	 * @param   array                 $args
	 * @param   array                 $cart_item
	 * @param   WC_Product_Composite  $composite
	 * @return  array
	 *
	 */
	public static function composited_bundle_permalink_args( $args, $cart_item, $composite ) {

		if ( ! empty( $args ) ) {
			if ( isset( $cart_item[ 'composite_data' ] ) && is_array( $cart_item[ 'composite_data' ] ) ) {

				$composite_config_data = $cart_item[ 'composite_data' ];

				foreach ( $composite_config_data as $component_id => $component_config_data ) {
					if ( isset( $component_config_data[ 'type' ] ) && $component_config_data[ 'type' ] === 'bundle' && ! empty( $component_config_data[ 'stamp' ] ) && is_array( $component_config_data[ 'stamp' ] ) ) {

						$stamp = $component_config_data[ 'stamp' ];

						foreach ( $stamp as $bundled_item_id => $bundled_item_stamp_data ) {

							if ( isset( $bundled_item_stamp_data[ 'optional_selected' ] ) ) {
								if ( $bundled_item_stamp_data[ 'optional_selected' ] === 'yes' ) {
									$args[ 'component_' . $component_id . '_bundle_selected_optional_' . $bundled_item_id ] = $bundled_item_stamp_data[ 'optional_selected' ];
								} else {
									continue;
								}
							}

							if ( isset( $bundled_item_stamp_data[ 'quantity' ] ) ) {
								$args[ 'component_' . $component_id . '_bundle_quantity_' . $bundled_item_id ] = $bundled_item_stamp_data[ 'quantity' ];
							}

							if ( isset( $bundled_item_stamp_data[ 'variation_id' ] ) ) {
								$args[ 'component_' . $component_id . '_bundle_variation_id_' . $bundled_item_id ] = $bundled_item_stamp_data[ 'variation_id' ];
							}

							if ( isset( $bundled_item_stamp_data[ 'attributes' ] ) && is_array( $bundled_item_stamp_data[ 'attributes' ] ) ) {
								foreach ( $bundled_item_stamp_data[ 'attributes' ] as $tax => $val ) {
									$args[ 'component_' . $component_id . '_bundle_' . $tax . '_' . $bundled_item_id ] = sanitize_title( $val );
								}
							}
						}
					}
				}
			}
		}

		return $args;
	}

	/**
	 * Composited bundle price.
	 *
	 * @param  double         $price
	 * @param  arrat          $args
	 * @param  WC_CP_Product  $composited_product
	 * @return double
	 */
	public static function composited_bundle_price( $price, $args, $composited_product ) {

		$product = $composited_product->get_product();

		if ( 'bundle' === $product->get_type() ) {

			$composited_product->add_filters();

			$price = $product->calculate_price( $args );

			if ( '' === $price ) {
				if ( $product->contains( 'priced_individually' ) && isset( $args[ 'min_or_max' ] ) && 'max' === $args[ 'min_or_max' ] && INF === $product->get_max_raw_price() ) {
					$price = INF;
				} else {
					$price = 0.0;
				}
			}

			$composited_product->remove_filters();
		}

		return $price;
	}

	/**
	 * Hook into 'woocommerce_composite_show_composited_product_bundle' to show bundle type product content.
	 *
	 * @param  WC_Product  $product
	 * @param  string      $component_id
	 * @param  WC_Product  $composite
	 * @return void
	 */
	public static function composite_show_product_bundle( $product, $component_id, $composite ) {

		if ( $product->contains( 'subscriptions' ) ) {

			?><div class="woocommerce-error"><?php
				echo __( 'This item cannot be purchased at the moment.', 'woocommerce-product-bundles' );
			?></div><?php

			return false;
		}

		$product_id   = $product->get_id();
		$composite_id = $composite->get_id();

		WC_PB_Compatibility::$compat_product = $product;
		WC_PB_Compatibility::$bundle_prefix  = $component_id;

		$component          = $composite->get_component( $component_id );
		$composited_product = $component->get_option( $product_id );
		$quantity_min       = $composited_product->get_quantity_min();
		$quantity_max       = $composited_product->get_quantity_max( true );
		$availability       = $composited_product->get_availability();
		$tax_ratio          = WC_PB_Product_Prices::get_tax_ratios( $product );

		/** Filter documented in CP file 'includes/wc-cp-template-functions.php'. */
		$custom_data = apply_filters( 'woocommerce_composited_product_custom_data', array( 'price_tax' => $tax_ratio, 'image_data' => $composited_product->get_image_data() ), $product, $component_id, $component, $composite );

 		wc_get_template( 'composited-product/bundle-product.php', array(
			'product_id'         => $product_id,
			'product'            => $product,
			'composite_id'       => $composite_id,
			'quantity_min'       => $quantity_min,
			'quantity_max'       => $quantity_max,
			'custom_data'        => $custom_data,
			'bundle_price_data'  => $product->get_bundle_price_data(),
			'bundled_items'      => $product->get_bundled_items(),
			'component_id'       => $component_id,
			'availability'       => $availability,
			'composited_product' => $composited_product,
			'composite_product'  => $composite
		), false, WC_PB()->plugin_path() . '/templates/' );

		WC_PB_Compatibility::$compat_product = '';
		WC_PB_Compatibility::$bundle_prefix  = '';
	}

	/**
	 * Hook into 'woocommerce_composite_component_add_to_cart_validation' to validate composited bundles.
	 *
	 * @param  boolean               $result
	 * @param  int                   $composite_id
	 * @param  string                $component_id
	 * @param  int                   $bundle_id
	 * @param  int                   $quantity
	 * @param  array                 $cart_item_data
	 * @param  WC_Product_Composite  $composite
	 * @return boolean
	 */
	public static function composite_validate_bundle_data( $result, $composite_id, $component_id, $bundle_id, $quantity, $cart_item_data, $composite, $component_configuration ) {

		// Get product type.
		$product_type = WC_Product_Factory::get_product_type( $bundle_id );

		if ( 'bundle' === $product_type && isset( $component_configuration[ 'quantity' ] ) && $component_configuration[ 'quantity' ] > 0 ) {

			// Present only when re-ordering.
			if ( isset( $cart_item_data[ 'composite_data' ][ $component_id ][ 'stamp' ] ) ) {
				$cart_item_data [ 'stamp' ] = $cart_item_data[ 'composite_data' ][ $component_id ][ 'stamp' ];
			}

			WC_PB_Compatibility::$bundle_prefix = $component_id;

			add_filter( 'woocommerce_bundle_before_validation', array( __CLASS__, 'disallow_bundled_item_subs' ), 10, 2 );

			add_filter( 'woocommerce_add_error', array( __CLASS__, 'component_bundle_error_message_context' ) );
			self::$current_component = $composite->get_component( $component_id );

			$result = WC_PB()->cart->validate_add_to_cart( true, $bundle_id, $quantity, '', array(), $cart_item_data );

			remove_filter( 'woocommerce_add_error', array( __CLASS__, 'component_bundle_error_message_context' ) );
			self::$current_component = false;

			remove_filter( 'woocommerce_bundle_before_validation', array( __CLASS__, 'disallow_bundled_item_subs' ), 10, 2 );

			WC_PB_Compatibility::$bundle_prefix = '';

			// Add filter to return stock manager items from bundle.
			if ( class_exists( 'WC_CP_Stock_Manager' ) ) {
				add_filter( 'woocommerce_composite_component_associated_stock', array( __CLASS__, 'associated_bundle_stock' ), 10, 5 );
			}
		}

		return $result;
	}

	/**
	 * Bundles with subscriptions can't be composited.
	 *
	 * @param  boolean     $passed
	 * @param  WC_Product  $bundle
	 * @return boolean
	 */
	public static function disallow_bundled_item_subs( $passed, $bundle ) {

		if ( $bundle->contains( 'subscriptions' ) ) {
			wc_add_notice( sprintf( __( 'The configuration you have selected cannot be added to the cart. &quot;%s&quot; cannot be purchased.', 'woocommerce-product-bundles' ), $bundle->get_title() ), 'error' );
			$passed = false;
		}

		return $passed;
	}

	/**
	 * Hook into 'woocommerce_composite_component_associated_stock' to append bundled items to the composite stock data object.
	 *
	 * @param  WC_PB_Stock_Manager   $items
	 * @param  int                   $composite_id
	 * @param  string                $component_id
	 * @param  int                   $bundled_product_id
	 * @param  int                   $quantity
	 * @return WC_PB_Stock_Manager
	 */
	public static function associated_bundle_stock( $items, $composite_id, $component_id, $bundled_product_id, $quantity ) {

		if ( ! empty( WC_PB_Compatibility::$stock_data ) ) {

			$items = WC_PB_Compatibility::$stock_data;

			WC_PB_Compatibility::$stock_data = '';
			remove_filter( 'woocommerce_composite_component_associated_stock', array( __CLASS__, 'associated_bundle_stock' ), 10, 5 );
		}

		return $items;
	}

	/**
	 * Hook into 'woocommerce_composite_component_cart_item_identifier' to add stamp data for bundles.
	 *
	 * @param  array   $composited_item_identifier
	 * @param  string  $component_id
	 * @return array
	 */
	public static function composite_bundle_cart_item_stamp( $composited_item_identifier, $component_id ) {

		if ( isset( $composited_item_identifier[ 'type' ] ) && $composited_item_identifier[ 'type' ] === 'bundle' ) {

			WC_PB_Compatibility::$bundle_prefix = $component_id;

			$bundle_cart_data = WC_PB()->cart->add_cart_item_data( array(), $composited_item_identifier[ 'product_id' ] );

			$composited_item_identifier[ 'stamp' ] = $bundle_cart_data[ 'stamp' ];

			WC_PB_Compatibility::$bundle_prefix = '';
		}

		return $composited_item_identifier;
	}

	/**
	 * Sets a prefix for unique bundles.
	 *
	 * @param  string  $prefix
	 * @param  int     $product_id
	 * @return string
	 */
	public static function bundle_field_prefix( $prefix, $product_id ) {

		if ( ! empty( WC_PB_Compatibility::$bundle_prefix ) ) {
			$prefix = 'component_' . WC_PB_Compatibility::$bundle_prefix . '_';
		}

		return $prefix;
	}

	/**
	 * Hook into 'woocommerce_composited_add_to_cart' to trigger 'WC_PB()->cart->bundle_add_to_cart()'.
	 *
	 * @param  string  $cart_item_key
	 * @param  int     $product_id
	 * @param  int     $quantity
	 * @param  int     $variation_id
	 * @param  array   $variation
	 * @param  array   $cart_item_data
	 */
	public static function add_bundle_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		WC_PB()->cart->bundle_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data );
	}

	/**
	 * Used to link bundled order items with the composite container product.
	 *
	 * @param  boolean   $is_child
	 * @param  array     $order_item
	 * @param  array     $composite_item
	 * @param  WC_Order  $order
	 * @return boolean
	 */
	public static function bundled_order_item_is_child_of_composite( $is_child, $order_item, $composite_item, $order ) {

		if ( $parent = wc_pb_get_bundled_order_item_container( $order_item, $order ) ) {
			if ( isset( $parent[ 'composite_parent' ] ) && $parent[ 'composite_parent' ] === $composite_item[ 'composite_cart_key' ] ) {
				$is_child = true;
			}
		}

		return $is_child;
	}

	/**
	 * Used to link bundled cart items with the composite container product.
	 *
	 * @param  boolean  $is_child
	 * @param  string   $cart_item_key
	 * @param  array    $cart_item_data
	 * @param  string   $composite_key
	 * @param  array    $composite_data
	 * @return boolean
	 */
	public static function bundled_cart_item_is_child_of_composite( $is_child, $cart_item_key, $cart_item_data, $composite_key, $composite_data ) {

		if ( $parent = wc_pb_get_bundled_cart_item_container( $cart_item_data ) ) {
			if ( isset( $parent[ 'composite_parent' ] ) && $parent[ 'composite_parent' ] === $composite_key ) {
				$is_child = true;
			}
		}

		return $is_child;
	}

	/**
	 * Edit composited bundle container cart title.
	 *
	 * @param  string  $content
	 * @param  array   $cart_item_values
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public static function composited_bundle_in_cart_item_title( $content, $cart_item_values, $cart_item_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item_values ) && wc_cp_is_composited_cart_item( $cart_item_values ) ) {

			$bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item_values );

			if ( empty( $bundled_cart_items ) && $cart_item_values[ 'data' ]->get_price() == 0 ) {
				$content = __( 'No selection', 'woocommerce-product-bundles' );
			}
		}

		return $content;
	}

	/**
	 * Hide composited bundle container cart title.
	 *
	 * @param  string  $content
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public static function composited_bundle_remove_in_cart_item_title( $title, $cart_item, $cart_item_key ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item ) && wc_cp_is_composited_cart_item( $cart_item ) ) {

			$bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item );

			if ( ! empty( $bundled_cart_items ) ) {

				$hide_title = false;

				if ( ! empty( $cart_item[ 'composite_data' ] ) && ! empty( $cart_item[ 'composite_item' ] ) ) {

					$component_id = $cart_item[ 'composite_item' ];

					if ( isset( $cart_item[ 'composite_data' ][ $component_id ][ 'static' ] ) && $cart_item[ 'composite_data' ][ $component_id ][ 'static' ] === 'yes' ) {
						$hide_title = true;
					}
				}

				if ( 'none_composited' === $cart_item[ 'data' ]->get_group_mode() ) {
					$hide_title = true;
				}

				/**
				 * 'woocommerce_composited_bundle_container_cart_item_hide_title' filter.
				 *
				 * @param  boolean  $hide_title
				 * @param  array    $cart_item
				 * @param  string   $cart_item_key
				 */
				if ( apply_filters( 'woocommerce_composited_bundle_container_cart_item_hide_title', $hide_title, $cart_item, $cart_item_key ) ) {
					ob_start();

					wc_get_template( 'component-item.php', array(
						'component_data' => array(
							'key'   => $cart_item[ 'composite_data' ][ $component_id ][ 'title' ],
							'value' => ''
						)
					), '', WC_CP()->plugin_path() . '/templates/' );

					$title = ob_get_clean();
				}
			}
		}

		return $title;
	}

	/**
	 * Edit composited bundle container cart qty.
	 *
	 * @param  int     $quantity
	 * @param  string  $cart_item_key
	 * @return int
	 */
	public static function composited_bundle_in_cart_item_quantity( $quantity, $cart_item_key ) {

		if ( isset( WC()->cart->cart_contents[ $cart_item_key ] ) ) {

			$cart_item = WC()->cart->cart_contents[ $cart_item_key ];

			if ( wc_pb_is_bundle_container_cart_item( $cart_item ) && wc_cp_is_composited_cart_item( $cart_item ) ) {

				$bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item );

				if ( empty( $bundled_cart_items ) && $cart_item[ 'data' ]->get_price() == 0 ) {

					$quantity = '';

				} else {

					$hide_qty = false;

					if ( ! empty( $cart_item[ 'composite_data' ] ) && ! empty( $cart_item[ 'composite_item' ] ) ) {

						$component_id = $cart_item[ 'composite_item' ];

						if ( isset( $cart_item[ 'composite_data' ][ $component_id ][ 'static' ] ) && $cart_item[ 'composite_data' ][ $component_id ][ 'static' ] === 'yes' && $cart_item[ 'composite_data' ][ $component_id ][ 'quantity_min' ] == 1 && $cart_item[ 'composite_data' ][ $component_id ][ 'quantity_max' ] == 1 ) {
							$hide_qty = true;
						}
					}

					/**
					 * 'woocommerce_composited_bundle_container_cart_item_hide_quantity' filter.
					 *
					 * @param  boolean  $hide_qty
					 * @param  array    $cart_item
					 * @param  string   $cart_item_key
					 */
					if ( apply_filters( 'woocommerce_composited_bundle_container_cart_item_hide_quantity', $hide_qty, $cart_item, $cart_item_key ) ) {
						$quantity = '';
					}
				}
			}
		}

		return $quantity;
	}

	/**
	 * Edit composited bundle container cart qty.
	 *
	 * @param  int     $quantity
	 * @param  string  $cart_item_values
	 * @param  string  $cart_item_key
	 * @return int
	 */
	public static function composited_bundle_checkout_item_quantity( $quantity, $cart_item_values, $cart_item_key = false ) {

		if ( wc_pb_is_bundle_container_cart_item( $cart_item_values ) && wc_cp_is_composited_cart_item( $cart_item_values ) ) {

			$bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item_values );

			if ( empty( $bundled_cart_items ) && $cart_item_values[ 'data' ]->get_price() == 0  ) {
				$quantity = '';
			} else {

				$hide_qty = false;

				if ( ! empty( $cart_item_values[ 'composite_data' ] ) && ! empty( $cart_item_values[ 'composite_item' ] ) ) {

					$component_id = $cart_item_values[ 'composite_item' ];

					if ( isset( $cart_item_values[ 'composite_data' ][ $component_id ][ 'static' ] ) && $cart_item_values[ 'composite_data' ][ $component_id ][ 'static' ] === 'yes' && $cart_item_values[ 'composite_data' ][ $component_id ][ 'quantity_min' ] == 1 && $cart_item_values[ 'composite_data' ][ $component_id ][ 'quantity_max' ] == 1 ) {
						$hide_qty = true;
					}
				}

				/** Documented in method 'composited_bundle_in_cart_item_quantity'. */
				if ( apply_filters( 'woocommerce_composited_bundle_container_cart_item_hide_quantity', $hide_qty, $cart_item_values, $cart_item_key ) ) {
					$quantity = '';
				}
			}
		}

		return $quantity;
	}

	/**
	 * Visibility of composited bundle container in orders.
	 * Hide containers without children and a zero price (all optional).
	 *
	 * @param  boolean  $visible
	 * @param  array    $order_item
	 * @return boolean
	 */
	public static function composited_bundle_order_item_visible( $visible, $order_item ) {

		if ( wc_pb_is_bundle_container_order_item( $order_item ) && wc_cp_maybe_is_composited_order_item( $order_item ) ) {

			$bundled_items = maybe_unserialize( $order_item[ 'bundled_items' ] );

			if ( empty( $bundled_items ) && $order_item[ 'line_subtotal' ] == 0  ) {
				$visible = false;
			}
		}

		return $visible;
	}

	/**
	 * Edit composited bundle container order item title.
	 *
	 * @param  string  $content
	 * @param  array   $order_item
	 * @return string
	 */
	public static function composited_bundle_order_table_item_title( $content, $order_item ) {

		if ( wc_pb_is_bundle_container_order_item( $order_item ) && wc_cp_maybe_is_composited_order_item( $order_item ) ) {

			$bundled_items = maybe_unserialize( $order_item[ 'bundled_items' ] );

			if ( empty( $bundled_items ) && $order_item[ 'line_subtotal' ] == 0 ) {
				$content = __( 'No selection', 'woocommerce-product-bundles' );

				if ( did_action( 'wc_pip_header' ) ) {
					$content = '<span class="product product-name composited-product">' . $content . '</span>';
				}
			}

			if ( ! empty( $bundled_items ) ) {

				$hide_title = false;

				if ( ! empty( $order_item[ 'composite_data' ] ) && ! empty( $order_item[ 'composite_item' ] ) ) {

					$component_id   = $order_item[ 'composite_item' ];
					$composite_data = maybe_unserialize( $order_item[ 'composite_data' ] );

					if ( isset( $composite_data[ $component_id ][ 'static' ] ) && $composite_data[ $component_id ][ 'static' ] === 'yes' && $composite_data[ $component_id ][ 'quantity_min' ] == 1 && $composite_data[ $component_id ][ 'quantity_max' ] == 1 ) {
						$hide_title = true;
					}
				}

				if ( ! empty( $order_item[ 'bundle_group_mode' ] ) && 'none_composited' === $order_item[ 'bundle_group_mode' ] ) {
					$hide_title = true;
				}

				/**
				 * 'woocommerce_composited_bundle_container_order_item_hide_title' filter.
				 *
				 * @param  boolean  $hide_title
				 * @param  array    $order_item
				 */
				if ( apply_filters( 'woocommerce_composited_bundle_container_order_item_hide_title', $hide_title, $order_item ) ) {
					$content = '';
				}
			}
		}

		return $content;
	}

	/**
	 * Edit composited bundle container order item qty.
	 *
	 * @param  string  $content
	 * @param  array   $order_item
	 * @return string
	 */
	public static function composited_bundle_order_table_item_quantity( $quantity, $order_item ) {

		if ( wc_pb_is_bundle_container_order_item( $order_item ) && wc_cp_maybe_is_composited_order_item( $order_item ) ) {

			$bundled_items = maybe_unserialize( $order_item[ 'bundled_items' ] );

			if ( empty( $bundled_items ) && $order_item[ 'line_subtotal' ] == 0  ) {
				$quantity = '';
			} else {

				$hide_qty = false;

				if ( ! empty( $order_item[ 'composite_data' ] ) && ! empty( $order_item[ 'composite_item' ] ) ) {

					$component_id   = $order_item[ 'composite_item' ];
					$composite_data = maybe_unserialize( $order_item[ 'composite_data' ] );

					if ( isset( $composite_data[ $component_id ][ 'static' ] ) && $composite_data[ $component_id ][ 'static' ] === 'yes' && $composite_data[ $component_id ][ 'quantity_min' ] == 1 && $composite_data[ $component_id ][ 'quantity_max' ] == 1 ) {
						$hide_qty = true;
					}
				}

				if ( ! empty( $order_item[ 'bundle_group_mode' ] ) && 'none_composited' === $order_item[ 'bundle_group_mode' ] ) {
					$hide_qty = true;
				}

				/**
				 * 'woocommerce_composited_bundle_container_order_item_hide_quantity' filter.
				 *
				 * @param  boolean  $hide_qty
				 * @param  array    $order_item
				 */
				if ( apply_filters( 'woocommerce_composited_bundle_container_order_item_hide_quantity', $hide_qty, $order_item ) ) {
					$quantity = '';
				}
			}
		}

		return $quantity;
	}

}

WC_PB_CP_Compatibility::init();
