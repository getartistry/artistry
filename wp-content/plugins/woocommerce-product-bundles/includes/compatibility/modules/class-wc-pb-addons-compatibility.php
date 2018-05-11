<?php
/**
 * WC_PB_Addons_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.11.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Addons Compatibility.
 *
 * @version  5.6.0
 */
class WC_PB_Addons_Compatibility {

	public static function init() {

		// Support for Product Addons.
		add_action( 'woocommerce_bundled_product_add_to_cart', array( __CLASS__, 'addons_support' ), 10, 2 );
		add_action( 'woocommerce_bundled_single_variation', array( __CLASS__, 'addons_support' ), 15, 2 );

		// Prefix form fields.
		add_filter( 'product_addons_field_prefix', array( __CLASS__, 'addons_cart_prefix' ), 10, 2 );

		// Validate add to cart Addons.
		add_filter( 'woocommerce_bundled_item_add_to_cart_validation', array( __CLASS__, 'validate_bundled_item_addons' ), 10, 5 );

		// Add addons identifier to bundled item stamp.
		add_filter( 'woocommerce_bundled_item_cart_item_identifier', array( __CLASS__, 'bundled_item_addons_stamp' ), 10, 2 );

		// Add option to disable Addons at component level.
		add_action( 'woocommerce_bundled_product_admin_advanced_html', array( __CLASS__, 'display_addons_disable_option' ), 15, 4 );

		// Save option to disable Addons at component level.
		add_filter( 'woocommerce_bundles_process_bundled_item_admin_data', array( __CLASS__, 'process_addons_disable_option' ), 10, 4 );

		// Before and after add-to-cart handling.
		add_action( 'woocommerce_bundled_item_before_add_to_cart', array( __CLASS__, 'before_bundled_add_to_cart' ), 10, 5 );
		add_action( 'woocommerce_bundled_item_after_add_to_cart', array( __CLASS__, 'after_bundled_add_to_cart' ), 10, 5 );

		// Load child Addons data from the parent cart item data array.
		add_filter( 'woocommerce_bundled_item_cart_data', array( __CLASS__, 'get_bundled_cart_item_data_from_parent' ), 10, 2 );

	}

	/**
	 * Show option to disable bundled product addons.
	 *
	 * @param  int    $loop
	 * @param  int    $product_id
	 * @param  array  $item_data
	 * @param  int    $post_id
	 * @return void
	 */
	public static function display_addons_disable_option( $loop, $product_id, $item_data, $post_id ) {

		$disable_addons = isset( $item_data[ 'disable_addons' ] ) && 'yes' === $item_data[ 'disable_addons' ];

		?><div class="disable_addons">
			<div class="form-field">
				<label for="disable_addons"><?php echo __( 'Disable Add-Ons', 'woocommerce-product-bundles' ) ?></label>
				<input type="checkbox" class="checkbox"<?php echo ( $disable_addons ? ' checked="checked"' : '' ); ?> name="bundle_data[<?php echo $loop; ?>][disable_addons]" <?php echo ( $disable_addons ? 'value="1"' : '' ); ?>/>
				<?php echo wc_help_tip( __( 'Check this option to disable any Product Add-Ons associated with this bundled product.', 'woocommerce-product-bundles' ) ); ?>
			</div>
		</div><?php
	}

	/**
	 * Save option that disables bundled product addons.
	 *
	 * @param  array  $item_data
	 * @param  array  $data
	 * @param  mixed  $item_id
	 * @param  mixed  $post_id
	 */
	public static function process_addons_disable_option( $item_data, $data, $item_id, $post_id ) {

		if ( isset( $data[ 'disable_addons' ] ) ) {
			$item_data[ 'disable_addons' ] = 'yes';
		} else {
			$item_data[ 'disable_addons' ] = 'no';
		}

		return $item_data;
	}

	/**
	 * Support for bundled item addons.
	 *
	 * @param  int              $product_id
	 * @param  WC_Bundled_Item  $item
	 * @return void
	 */
	public static function addons_support( $product_id, $item ) {

		global $Product_Addon_Display, $product;

		if ( ! empty( $Product_Addon_Display ) ) {

			if ( $item->get_product()->is_type( 'variable' ) && doing_action( 'woocommerce_bundled_product_add_to_cart' ) ) {
				return;
			}

			$item_data      = $item->get_data();
			$disable_addons = ! empty( $item_data ) && isset( $item_data[ 'disable_addons' ] ) && 'yes' === $item_data[ 'disable_addons' ];

			if ( $disable_addons ) {
				return;
			}

			$product_bak = isset( $product ) ? $product : false;
			$product     = $item->get_product();

			WC_PB_Compatibility::$addons_prefix          = $item->get_id();
			WC_PB_Compatibility::$compat_bundled_product = $item->get_product();

			$Product_Addon_Display->display( $product_id, false );

			WC_PB_Compatibility::$addons_prefix = WC_PB_Compatibility::$compat_bundled_product = '';

			if ( $product_bak ) {
				$product = $product_bak;
			}
		}
	}

	/**
	 * Sets a unique prefix for unique add-ons. The prefix is set and re-set globally before validating and adding to cart.
	 *
	 * @param  string   $prefix         unique prefix
	 * @param  int      $product_id     the product id
	 * @return string                   a unique prefix
	 */
	public static function addons_cart_prefix( $prefix, $product_id ) {

		if ( ! empty( WC_PB_Compatibility::$addons_prefix ) ) {
			$prefix = WC_PB_Compatibility::$addons_prefix . '-';
		}

		if ( ! empty( WC_PB_Compatibility::$bundle_prefix ) ) {
			$prefix = WC_PB_Compatibility::$bundle_prefix . '-' . WC_PB_Compatibility::$addons_prefix . '-';
		}

		return $prefix;
	}

	/**
	 * Add addons identifier to bundled item stamp, in order to generate new cart ids for bundles with different addons configurations.
	 *
	 * @param  array   $bundled_item_stamp
	 * @param  string  $bundled_item_id
	 * @return array
	 */
	public static function bundled_item_addons_stamp( $bundled_item_stamp, $bundled_item_id ) {

		global $Product_Addon_Cart;

		// Store bundled item addons add-ons config in stamp to avoid generating the same bundle cart id.
		if ( ! empty( $Product_Addon_Cart ) ) {

			$addon_data = array();

			// Set addons prefix.
			WC_PB_Compatibility::$addons_prefix = $bundled_item_id;

			$bundled_product_id = $bundled_item_stamp[ 'product_id' ];

			$addon_data = $Product_Addon_Cart->add_cart_item_data( $addon_data, $bundled_product_id );

			// Reset addons prefix.
			WC_PB_Compatibility::$addons_prefix = '';

			if ( ! empty( $addon_data[ 'addons' ] ) ) {
				$bundled_item_stamp[ 'addons' ] = $addon_data[ 'addons' ];
			}
		}

		return $bundled_item_stamp;
	}

	/**
	 * Validate bundled item addons.
	 *
	 * @param  bool  $add
	 * @param  int   $product_id
	 * @param  int   $quantity
	 * @return bool
	 */
	public static function validate_bundled_item_addons( $add, $bundle, $bundled_item, $quantity, $variation_id ) {

		// Ordering again? When ordering again, do not revalidate addons.
		$order_again = isset( $_GET[ 'order_again' ] ) && isset( $_GET[ '_wpnonce' ] ) && wp_verify_nonce( $_GET[ '_wpnonce' ], 'woocommerce-order_again' );

		if ( $order_again  ) {
			return $add;
		}

		$bundled_item_id = $bundled_item->get_id();
		$product_id      = $bundled_item->get_product_id();

		// Validate add-ons.
		global $Product_Addon_Cart;

		if ( ! empty( $Product_Addon_Cart ) ) {

			$item_data      = $bundled_item->get_data();
			$disable_addons = ! empty( $item_data ) && isset( $item_data[ 'disable_addons' ] ) && 'yes' === $item_data[ 'disable_addons' ];

			WC_PB_Compatibility::$addons_prefix = $bundled_item_id;

			if ( false === $disable_addons && false === $Product_Addon_Cart->validate_add_cart_item( true, $product_id, $quantity ) ) {
				$add = false;
			}

			WC_PB_Compatibility::$addons_prefix = '';
		}

		return $add;
	}

	/**
	 * Runs before adding a bundled item to the cart.
	 *
	 * @param  int    $product_id
	 * @param  int    $quantity
	 * @param  int    $variation_id
	 * @param  array  $variations
	 * @param  array  $bundled_item_cart_data
	 * @return void
	 */
	public static function after_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data ) {

		global $Product_Addon_Cart;

		// Reset addons prefix.
		WC_PB_Compatibility::$addons_prefix = '';

		if ( ! empty ( $Product_Addon_Cart ) ) {
			add_filter( 'woocommerce_add_cart_item_data', array( $Product_Addon_Cart, 'add_cart_item_data' ), 10, 2 );
		}
	}

	/**
	 * Runs after adding a bundled item to the cart.
	 *
	 * @param  int    $product_id
	 * @param  int    $quantity
	 * @param  int    $variation_id
	 * @param  array  $variations
	 * @param  array  $bundled_item_cart_data
	 * @return void
	 */
	public static function before_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data ) {

		global $Product_Addon_Cart;

		// Set addons prefix.
		WC_PB_Compatibility::$addons_prefix = $bundled_item_cart_data[ 'bundled_item_id' ];

		// Add-ons cart item data is already stored in the composite_data array, so we can grab it from there instead of allowing Addons to re-add it.
		// Not doing so results in issues with file upload validation.

		if ( ! empty ( $Product_Addon_Cart ) ) {
			remove_filter( 'woocommerce_add_cart_item_data', array( $Product_Addon_Cart, 'add_cart_item_data' ), 10, 2 );
		}
	}

	/**
	 * Retrieve child cart item data from the parent cart item data array, if necessary.
	 *
	 * @param  array  $bundled_item_cart_data
	 * @param  array  $cart_item_data
	 * @return array
	 */
	public static function get_bundled_cart_item_data_from_parent( $bundled_item_cart_data, $cart_item_data ) {

		// Add-ons cart item data is already stored in the composite_data array, so we can grab it from there instead of allowing Addons to re-add it.
		if ( isset( $bundled_item_cart_data[ 'bundled_item_id' ] ) && isset( $cart_item_data[ 'stamp' ][ $bundled_item_cart_data[ 'bundled_item_id' ] ][ 'addons' ] ) ) {
			$bundled_item_cart_data[ 'addons' ] = $cart_item_data[ 'stamp' ][ $bundled_item_cart_data[ 'bundled_item_id' ] ][ 'addons' ];
		}

		return $bundled_item_cart_data;
	}
}

WC_PB_Addons_Compatibility::init();
