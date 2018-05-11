<?php
/**
 * WC_PB_Product_Import class
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
 * WooCommerce core Product Importer support.
 *
 * @class    WC_PB_Product_Import
 * @version  5.6.2
 */
class WC_PB_Product_Import {

	/**
	 * Hook in.
	 */
	public static function init() {

		// Map custom column titles.
		add_filter( 'woocommerce_csv_product_import_mapping_options', array( __CLASS__, 'map_columns' ) );
		add_filter( 'woocommerce_csv_product_import_mapping_default_columns', array( __CLASS__, 'add_columns_to_mapping_screen' ) );

		// Parse bundled items.
		add_filter( 'woocommerce_product_importer_parsed_data', array( __CLASS__, 'parse_bundled_items' ), 10, 2 );

		// Set bundle-type props.
		add_filter( 'woocommerce_product_import_pre_insert_product_object', array( __CLASS__, 'set_bundle_props' ), 10, 2 );
	}

	/**
	 * Register the 'Custom Column' column in the importer.
	 *
	 * @param  array  $options
	 * @return array  $options
	 */
	public static function map_columns( $options ) {

		$options[ 'wc_pb_bundled_items' ]             = __( 'Bundled Items (JSON-encoded)', 'woocommerce-product-bundles' );
		$options[ 'wc_pb_layout' ]                    = __( 'Bundle Layout', 'woocommerce-product-bundles' );
		$options[ 'wc_pb_group_mode' ]                = __( 'Bundle Group Mode', 'woocommerce-product-bundles' );
		$options[ 'wc_pb_editable_in_cart' ]          = __( 'Bundle Cart Editing', 'woocommerce-product-bundles' );
		$options[ 'wc_pb_sold_individually_context' ] = __( 'Bundle Sold Individually', 'woocommerce-product-bundles' );

		return $options;
	}

	/**
	 * Add automatic mapping support for custom columns.
	 *
	 * @param  array  $columns
	 * @return array  $columns
	 */
	public static function add_columns_to_mapping_screen( $columns ) {

		$columns[ __( 'Bundled Items (JSON-encoded)', 'woocommerce-product-bundles' ) ] = 'wc_pb_bundled_items';
		$columns[ __( 'Bundle Layout', 'woocommerce-product-bundles' ) ]                = 'wc_pb_layout';
		$columns[ __( 'Bundle Group Mode', 'woocommerce-product-bundles' ) ]            = 'wc_pb_group_mode';
		$columns[ __( 'Bundle Cart Editing', 'woocommerce-product-bundles' ) ]          = 'wc_pb_editable_in_cart';
		$columns[ __( 'Bundle Sold Individually', 'woocommerce-product-bundles' ) ]     = 'wc_pb_sold_individually_context';

		// Always add English mappings.
		$columns[ 'Bundled Items (JSON-encoded)' ] = 'wc_pb_bundled_items';
		$columns[ 'Bundle Layout' ]                = 'wc_pb_layout';
		$columns[ 'Bundle Group Mode' ]            = 'wc_pb_group_mode';
		$columns[ 'Bundle Cart Editing' ]          = 'wc_pb_editable_in_cart';
		$columns[ 'Bundle Sold Individually' ]     = 'wc_pb_sold_individually_context';

		return $columns;
	}

	/**
	 * Decode bundled data items and parse relative IDs.
	 *
	 * @param  array                    $parsed_data
	 * @param  WC_Product_CSV_Importer  $importer
	 * @return array
	 */
	public static function parse_bundled_items( $parsed_data, $importer ) {

		if ( ! empty( $parsed_data[ 'wc_pb_bundled_items' ] ) ) {

			$bundled_data_items = json_decode( $parsed_data[ 'wc_pb_bundled_items' ], true );

			unset( $parsed_data[ 'wc_pb_bundled_items' ] );

			if ( is_array( $bundled_data_items ) ) {

				$parsed_data[ 'wc_pb_bundled_items' ] = array();

				foreach ( $bundled_data_items as $bundled_data_item_key => $bundled_data_item ) {

					$bundled_product_id = $bundled_data_items[ $bundled_data_item_key ][ 'product_id' ];

					$parsed_data[ 'wc_pb_bundled_items' ][ $bundled_data_item_key ]                 = $bundled_data_item;
					$parsed_data[ 'wc_pb_bundled_items' ][ $bundled_data_item_key ][ 'product_id' ] = $importer->parse_relative_field( $bundled_product_id );
				}
			}
		}

		return $parsed_data;
	}

	/**
	 * Set bundle-type props.
	 *
	 * @param  array  $parsed_data
	 * @return array
	 */
	public static function set_bundle_props( $product, $data ) {

		if ( is_a( $product, 'WC_Product' ) && $product->is_type( 'bundle' ) ) {

			$props = array();

			if ( isset( $data[ 'wc_pb_bundled_items' ] ) ) {
				$props[ 'bundled_data_items' ] = ! empty( $data[ 'wc_pb_bundled_items' ] ) ? $data[ 'wc_pb_bundled_items' ] : array();
			}

			if ( isset( $data[ 'wc_pb_editable_in_cart' ] ) ) {
				$props[ 'editable_in_cart' ] = 1 === intval( $data[ 'wc_pb_editable_in_cart' ] ) ? 'yes' : 'no';
			}

			if ( isset( $data[ 'wc_pb_layout' ] ) ) {
				$props[ 'layout' ] = strval( $data[ 'wc_pb_layout' ] );
			}

			if ( isset( $data[ 'wc_pb_group_mode' ] ) ) {
				$props[ 'group_mode' ] = strval( $data[ 'wc_pb_group_mode' ] );
			}

			if ( isset( $data[ 'wc_pb_sold_individually_context' ] ) ) {
				$props[ 'sold_individually_context' ] = strval( $data[ 'wc_pb_sold_individually_context' ] );
			}

			if ( ! empty( $props ) ) {
				$product->set_props( $props );
			}
		}

		return $product;
	}
}

WC_PB_Product_Import::init();
