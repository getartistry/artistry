<?php
/**
 * WC_PB_Product_Prices class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Price functions and hooks.
 *
 * @class    WC_PB_Product_Prices
 * @version  5.7.8
 */
class WC_PB_Product_Prices {

	/**
	 * Bundled item whose prices are currently being filtered.
	 *
	 * @var WC_Bundled_Item
	 */
	public static $bundled_item;

	/**
	 * Returns the incl/excl tax coefficients for calculating prices incl/excl tax on the client side.
	 *
	 * @since  5.7.6
	 *
	 * @param  WC_Product  $product
	 * @return array
	 */
	public static function get_tax_ratios( $product ) {

		WC_PB_Product_Prices::extend_price_display_precision();

		$ref_price      = 1000.0;
		$ref_price_incl = wc_get_price_including_tax( $product, array( 'qty' => 1, 'price' => $ref_price ) );
		$ref_price_excl = wc_get_price_excluding_tax( $product, array( 'qty' => 1, 'price' => $ref_price ) );

		WC_PB_Product_Prices::reset_price_display_precision();

		return array(
			'incl' => $ref_price_incl / $ref_price,
			'excl' => $ref_price_excl / $ref_price
		);
	}

	/**
	 * Filters the 'woocommerce_price_num_decimals' option to use the internal WC rounding precision.
	 */
	public static function extend_price_display_precision() {
		add_filter( 'option_woocommerce_price_num_decimals', array( 'WC_PB_Core_Compatibility', 'wc_get_rounding_precision' ) );
	}

	/**
	 * Reset applied filters to the 'woocommerce_price_num_decimals' option.
	 */
	public static function reset_price_display_precision() {
		remove_filter( 'option_woocommerce_price_num_decimals', array( 'WC_PB_Core_Compatibility', 'wc_get_rounding_precision' ) );
	}

	/**
	 * Calculates product prices.
	 *
	 * @since  5.5.0
	 *
	 * @param  WC_Product  $product
	 * @param  array       $args
	 * @return mixed
	 */
	public static function get_product_price( $product, $args ) {

		$defaults = array(
			'price' => '',
			'qty'   => 1,
			'calc'  => ''
		);

		$args  = wp_parse_args( $args, $defaults );
		$price = $args[ 'price' ];
		$qty   = $args[ 'qty' ];
		$calc  = $args[ 'calc' ];

		if ( $price ) {

			if ( 'display' === $calc ) {
				$calc = 'excl' === get_option( 'woocommerce_tax_display_shop' ) ? 'excl_tax' : 'incl_tax';
			}

			if ( 'incl_tax' === $calc ) {
				$price = wc_get_price_including_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
			} elseif ( 'excl_tax' === $calc ) {
				$price = wc_get_price_excluding_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
			} else {
				$price = $price * $qty;
			}
		}

		return $price;
	}

	/**
	 * Discounted bundled item price precision. Defaults to the price display precision, a.k.a. wc_get_price_decimals.
	 *
	 * @since  5.7.8
	 *
	 * @return int
	 */
	public static function get_discounted_price_precision() {
		return apply_filters( 'woocommerce_bundled_item_discounted_price_precision', wc_get_price_decimals() );
	}

	/**
	 * Discounted price getter.
	 *
	 * @param  mixed  $price
	 * @param  mixed  $discount
	 * @return mixed
	 */
	public static function get_discounted_price( $price, $discount ) {

		$discounted_price = $price;

		if ( ! empty( $price ) && ! empty( $discount ) ) {
			$discounted_price = round( ( double ) $price * ( 100 - $discount ) / 100, self::get_discounted_price_precision() );
		}

		return $discounted_price;
	}

	/**
	 * Returns the recurring price component of a subscription product.
	 *
	 * @param  WC_Product  $product
	 * @return string
	 */
	public static function get_recurring_price_html_component( $product ) {

		$sync_date = $product->get_meta( '_subscription_payment_sync_date', true );

		$product->update_meta_data( '_subscription_payment_sync_date', 0 );

		$sub_price_html = WC_Subscriptions_Product::get_price_string( $product, array( 'price' => '%s', 'sign_up_fee' => false ) );

		$product->update_meta_data( '_subscription_payment_sync_date', $sync_date );

		return $sub_price_html;
	}

	/**
	 * Add price filters to modify child product prices depending on the bundled item pricing setup.
	 *
	 * @param  WC_Bundled_Item  $bundled_item
	 */
	public static function add_price_filters( $bundled_item ) {

		self::$bundled_item = $bundled_item;

		add_filter( 'woocommerce_product_get_price', array( __CLASS__, 'filter_get_price' ), 15, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( __CLASS__, 'filter_get_sale_price' ), 15, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( __CLASS__, 'filter_get_regular_price' ), 15, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( __CLASS__, 'filter_get_price' ), 15, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( __CLASS__, 'filter_get_sale_price' ), 15, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( __CLASS__, 'filter_get_regular_price' ), 15, 2 );

		add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'filter_get_price_html' ), 10, 2 );
		add_filter( 'woocommerce_get_children', array( __CLASS__, 'filter_children' ), 10, 2 );
		add_filter( 'woocommerce_get_variation_prices_hash', array( __CLASS__, 'filter_variation_prices_hash' ), 10, 2 );
		add_filter( 'woocommerce_variation_prices', array( __CLASS__, 'filter_get_variation_prices' ), 15, 2 );
		add_filter( 'woocommerce_show_variation_price', array( __CLASS__, 'filter_show_variation_price' ), 10, 3 );

		/**
		 * 'woocommerce_bundled_product_price_filters_added' hook.
		 *
		 * @param  WC_Bundled_Item  $bundled_item
		 */
		do_action( 'woocommerce_bundled_product_price_filters_added', $bundled_item );
	}

	/**
	 * Remove price filters after modifying child product prices depending on the bundled item pricing setup.
	 */
	public static function remove_price_filters() {

		remove_filter( 'woocommerce_product_get_price', array( __CLASS__, 'filter_get_price' ), 15, 2 );
		remove_filter( 'woocommerce_product_get_sale_price', array( __CLASS__, 'filter_get_sale_price' ), 15, 2 );
		remove_filter( 'woocommerce_product_get_regular_price', array( __CLASS__, 'filter_get_regular_price' ), 15, 2 );
		remove_filter( 'woocommerce_product_variation_get_price', array( __CLASS__, 'filter_get_price' ), 15, 2 );
		remove_filter( 'woocommerce_product_variation_get_sale_price', array( __CLASS__, 'filter_get_sale_price' ), 15, 2 );
		remove_filter( 'woocommerce_product_variation_get_regular_price', array( __CLASS__, 'filter_get_regular_price' ), 15, 2 );

		remove_filter( 'woocommerce_get_price_html', array( __CLASS__, 'filter_get_price_html' ), 10, 2 );
		remove_filter( 'woocommerce_get_children', array( __CLASS__, 'filter_children' ), 10, 2 );
		remove_filter( 'woocommerce_get_variation_prices_hash', array( __CLASS__, 'filter_variation_prices_hash' ), 10, 2 );
		remove_filter( 'woocommerce_variation_prices', array( __CLASS__, 'filter_get_variation_prices' ), 15, 2 );
		remove_filter( 'woocommerce_show_variation_price', array( __CLASS__, 'filter_show_variation_price' ), 10, 3 );

		/**
		 * 'woocommerce_bundled_product_price_filters_removed' hook.
		 *
		 * @param  WC_Bundled_Item  $bundled_item
		 */
		do_action( 'woocommerce_bundled_product_price_filters_removed', self::$bundled_item );

		self::$bundled_item = false;
	}

	/**
	 * Filter variation prices hash to load different prices for variable products with variation filters and/or discounts.
	 *
	 * @param  array                $hash
	 * @param  WC_Product_Variable  $product
	 * @return array
	 */
	public static function filter_variation_prices_hash( $hash, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			$discount                = $bundled_item->get_discount();
			$has_filtered_variations = $product->is_type( 'variable' ) && $bundled_item->has_filtered_variations();

			if ( $has_filtered_variations || ! empty( $discount ) ) {
				$hash[] = $bundled_item->data->get_id();
			}
		}

		return $hash;
	}

	/**
	 * Filter variable product children to exclude filtered out variations.
	 *
	 * @param  array                $children
	 * @param  WC_Product_Variable  $product
	 * @return array
	 */
	public static function filter_children( $children, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			if ( $bundled_item->has_filtered_variations() ) {

				$filtered_children = array();

				foreach ( $children as $variation_id ) {
					// Remove if filtered.
					if ( in_array( $variation_id, $bundled_item->get_filtered_variations() ) ) {
						$filtered_children[] = $variation_id;
					}
				}

				$children = $filtered_children;
			}
		}

		return $children;
	}

	/**
	 * Filter get_variation_prices() calls for bundled products to include discounts.
	 *
	 * @param  array                $prices_array
	 * @param  WC_Product_Variable  $product
	 * @return array
	 */
	public static function filter_get_variation_prices( $prices_array, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			$prices         = array();
			$regular_prices = array();
			$sale_prices    = array();

			$discount           = $bundled_item->get_discount();
			$priced_per_product = $bundled_item->is_priced_individually();

			// Filter regular prices.
			foreach ( $prices_array[ 'regular_price' ] as $variation_id => $regular_price ) {
				if ( $priced_per_product ) {
					$regular_prices[ $variation_id ] = $regular_price === '' ? $prices_array[ 'price' ][ $variation_id ] : $regular_price;
				} else {
					$regular_prices[ $variation_id ] = 0;
				}
			}

			// Filter prices.
			foreach ( $prices_array[ 'price' ] as $variation_id => $price ) {
				if ( $priced_per_product ) {
					if ( false === $bundled_item->is_discount_allowed_on_sale_price() ) {
						$regular_price = $regular_prices[ $variation_id ];
					} else {
						$regular_price = $price;
					}
					$price                   = empty( $discount ) ? $price : round( ( double ) $regular_price * ( 100 - $discount ) / 100, self::get_discounted_price_precision() );
					$prices[ $variation_id ] = apply_filters( 'woocommerce_bundled_variation_price', $price, $variation_id, $discount, $bundled_item );
				} else {
					$prices[ $variation_id ] = 0;
				}
			}

			// Filter sale prices.
			foreach ( $prices_array[ 'sale_price' ] as $variation_id => $sale_price ) {
				if ( $priced_per_product ) {
					$sale_prices[ $variation_id ] = empty( $discount ) ? $sale_price : $prices[ $variation_id ];
				} else {
					$sale_prices[ $variation_id ] = 0;
				}
			}

			if ( false === $bundled_item->is_discount_allowed_on_sale_price() ) {
				asort( $prices );
			}

			$prices_array = array(
				'price'         => $prices,
				'regular_price' => $regular_prices,
				'sale_price'    => $sale_prices
			);
		}

		return $prices_array;
	}

	/**
	 * Filter condition that allows WC to calculate variation price_html.
	 *
	 * @param  boolean               $show
	 * @param  WC_Product_Variable   $product
	 * @param  WC_Product_Variation  $variation
	 * @return boolean
	 */
	public static function filter_show_variation_price( $show, $product, $variation ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			$show = false;

			if ( $bundled_item->is_priced_individually() && $bundled_item->is_price_visible( 'product' ) ) {
				$show = true;
			}
		}

		return $show;
	}

	/**
	 * Filter get_price() calls for bundled products to include discounts.
	 *
	 * @param  double      $price
	 * @param  WC_Product  $product
	 * @return double
	 */
	public static function filter_get_price( $price, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			if ( $price === '' ) {
				return $price;
			}

			if ( ! $bundled_item->is_priced_individually() ) {
				return 0;
			}

			if ( false === $bundled_item->is_discount_allowed_on_sale_price() ) {
				$regular_price = $product->get_regular_price();
			} else {
				$regular_price = $price;
			}

			$discount = $bundled_item->get_discount();
			$price    = empty( $discount ) ? $price : self::get_discounted_price( $regular_price, $discount );

			$product->bundled_item_price = $price;

			/** Documented in 'WC_Bundled_Item::get_raw_price()'. */
			$price = apply_filters( 'woocommerce_bundled_item_price', $price, $product, $discount, $bundled_item );
		}

		return $price;
	}

	/**
	 * Filter get_regular_price() calls for bundled products to include discounts.
	 *
	 * @param  double      $price
	 * @param  WC_Product  $product
	 * @return double
	 */
	public static function filter_get_regular_price( $regular_price, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			if ( ! $bundled_item->is_priced_individually() ) {
				return 0;
			}

			if ( empty( $regular_price ) ) {
				self::$bundled_item = false;
				$regular_price = $product->get_price();
				self::$bundled_item = $bundled_item;
			}
		}

		return $regular_price;
	}

	/**
	 * Filter get_sale_price() calls for bundled products to include discounts.
	 *
	 * @param  double      $price
	 * @param  WC_Product  $product
	 * @return double
	 */
	public static function filter_get_sale_price( $sale_price, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			if ( ! $bundled_item->is_priced_individually() ) {
				return 0;
			}

			if ( '' === $sale_price || false === $bundled_item->is_discount_allowed_on_sale_price() ) {
				$regular_price = $product->get_regular_price();
			} else {
				$regular_price = $sale_price;
			}

			$discount   = $bundled_item->get_discount();
			$sale_price = empty( $discount ) ? $sale_price : self::get_discounted_price( $regular_price, $discount );

			/** Documented in 'WC_Bundled_Item::get_raw_price()'. */
			$sale_price = apply_filters( 'woocommerce_bundled_item_price', $sale_price, $product, $discount, $bundled_item );
		}

		return $sale_price;
	}

	/**
	 * Filter the html price string of bundled items to show the correct price with discount and tax - needs to be hidden when the bundled item is priced individually.
	 *
	 * @param  string      $price_html
	 * @param  WC_Product  $product
	 * @return string
	 */
	public static function filter_get_price_html( $price_html, $product ) {

		$bundled_item = self::$bundled_item;

		if ( $bundled_item ) {

			if ( ! $bundled_item->is_priced_individually() ) {
				return '';
			}

			if ( ! $bundled_item->is_price_visible( 'product' ) ) {
				return '';
			}

			$quantity = $bundled_item->get_quantity();

			/**
			 * 'woocommerce_bundled_item_price_html' filter.
			 *
			 * @param  string           $price_html
			 * @param  WC_Bundled_Item  $bundled_item
			 */
			$price_html = apply_filters( 'woocommerce_bundled_item_price_html', $quantity > 1 ? sprintf( __( '%1$s <span class="bundled_item_price_quantity">/ pc.</span>', 'woocommerce-product-bundles' ), $price_html, $quantity ) : $price_html, $price_html, $bundled_item );
		}

		return $price_html;
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated methods.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Calculates bundled product prices incl. or excl. tax depending on the 'woocommerce_tax_display_shop' setting.
	 *
	 * @deprecated  5.5.0
	 */
	public static function get_product_display_price( $product, $price, $qty = 1 ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', 'WC_PB_Product_Prices::get_product_price()' );
		return self::get_product_price( $product, array(
			'price' => $price,
			'qty'   => $qty,
			'calc'  => 'display'
		) );
	}
}
