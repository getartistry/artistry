<?php
/**
 * WC_PB_Product_Export class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce core Product Exporter support.
 *
 * @class    WC_PB_Product_Export
 * @version  5.5.0
 */
class WC_PB_Product_Export {

	/**
	 * Hook in.
	 */
	public static function init() {

		// Add CSV columns for exporting bundle data.
		add_filter( 'woocommerce_product_export_column_names', array( __CLASS__, 'add_columns' ) );
		add_filter( 'woocommerce_product_export_product_default_columns', array( __CLASS__, 'add_columns' ) );

		// "Bundled Items" column data.
		add_filter( 'woocommerce_product_export_product_column_wc_pb_bundled_items', array( __CLASS__, 'export_bundled_items' ), 10, 2 );
		add_filter( 'woocommerce_product_export_product_column_wc_pb_layout', array( __CLASS__, 'export_layout' ), 10, 2 );
		add_filter( 'woocommerce_product_export_product_column_wc_pb_group_mode', array( __CLASS__, 'export_group_mode' ), 10, 2 );
		add_filter( 'woocommerce_product_export_product_column_wc_pb_editable_in_cart', array( __CLASS__, 'export_editable_in_cart' ), 10, 2 );
		add_filter( 'woocommerce_product_export_product_column_wc_pb_sold_individually_context', array( __CLASS__, 'export_sold_individually_context' ), 10, 2 );
	}

	/**
	 * Add CSV columns for exporting bundle data.
	 *
	 * @param  array  $columns
	 * @return array  $columns
	 */
	public static function add_columns( $columns ) {

		$columns[ 'wc_pb_bundled_items' ]             = __( 'Bundled Items (JSON-encoded)', 'woocommerce-product-bundles' );
		$columns[ 'wc_pb_layout' ]                    = __( 'Bundle Layout', 'woocommerce-product-bundles' );
		$columns[ 'wc_pb_group_mode' ]                = __( 'Bundle Group Mode', 'woocommerce-product-bundles' );
		$columns[ 'wc_pb_editable_in_cart' ]          = __( 'Bundle Cart Editing', 'woocommerce-product-bundles' );
		$columns[ 'wc_pb_sold_individually_context' ] = __( 'Bundle Sold Individually', 'woocommerce-product-bundles' );

		return $columns;
	}

	/**
	 * Bundle data column content.
	 *
	 * @param  mixed       $value
	 * @param  WC_Product  $product
	 * @return mixed       $value
	 */
	public static function export_bundled_items( $value, $product ) {

		if ( $product->is_type( 'bundle' ) ) {

			$bundled_items = $product->get_bundled_data_items( 'edit' );

			if ( ! empty( $bundled_items ) ) {

				$data = array();

				foreach ( $bundled_items as $bundled_item ) {

					$bundled_item_id    = $bundled_item->get_id();
					$bundled_item_data  = $bundled_item->get_data();
					$bundled_product_id = $bundled_item->get_product_id();
					$bundled_product    = wc_get_product( $bundled_product_id );

					if ( ! $bundled_product ) {
						return $value;
					}

					// Not needed as we will be re-creating all bundled items during import.
					unset( $bundled_item_data[ 'bundled_item_id' ] );
					unset( $bundled_item_data[ 'bundle_id' ] );

					$bundled_product_sku = $bundled_product->get_sku( 'edit' );

					// Refer to exported products by their SKU, if present.
					$bundled_item_data[ 'product_id' ] = $bundled_product_sku ? $bundled_product_sku : 'id:' . $bundled_product_id;

					$data[ $bundled_item_id ] = $bundled_item_data;
				}

				$value = json_encode( $data );
			}
		}

		return $value;
	}

	/**
	 * "Bundle Layout" column content.
	 *
	 * @param  mixed       $value
	 * @param  WC_Product  $product
	 * @return mixed       $value
	 */
	public static function export_layout( $value, $product ) {

		if ( $product->is_type( 'bundle' ) ) {
			$value = $product->get_layout( 'edit' );
		}

		return $value;
	}

	/**
	 * "Bundle Group Mode" column content.
	 *
	 * @param  mixed       $value
	 * @param  WC_Product  $product
	 * @return mixed       $value
	 */
	public static function export_group_mode( $value, $product ) {

		if ( $product->is_type( 'bundle' ) ) {
			$value = $product->get_group_mode( 'edit' );
		}

		return $value;
	}

	/**
	 * "Bundle Cart Editing" column content.
	 *
	 * @param  mixed       $value
	 * @param  WC_Product  $product
	 * @return mixed       $value
	 */
	public static function export_editable_in_cart( $value, $product ) {

		if ( $product->is_type( 'bundle' ) ) {
			$value = $product->get_editable_in_cart( 'edit' ) ? 1 : 0;
		}

		return $value;
	}

	/**
	 * "Bundle Sold Individually" column content.
	 *
	 * @param  mixed       $value
	 * @param  WC_Product  $product
	 * @return mixed       $value
	 */
	public static function export_sold_individually_context( $value, $product ) {

		if ( $product->is_type( 'bundle' ) ) {
			$value = $product->get_sold_individually_context( 'edit' );
		}

		return $value;
	}
}

WC_PB_Product_Export::init();
