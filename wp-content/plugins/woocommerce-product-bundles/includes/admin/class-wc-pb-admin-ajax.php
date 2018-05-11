<?php
/**
 * WC_PB_Admin_Ajax class
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
 * Admin AJAX meta-box handlers.
 *
 * @class     WC_PB_Admin_Ajax
 * @version   5.5.0
 */
class WC_PB_Admin_Ajax {

	/**
	 * Hook in.
	 */
	public static function init() {

		// Ajax add bundled product.
		add_action( 'wp_ajax_woocommerce_add_bundled_product', array( __CLASS__, 'ajax_add_bundled_product' ) );

		// Ajax search bundled item variations.
		add_action( 'wp_ajax_woocommerce_search_bundled_variations', array( __CLASS__, 'ajax_search_bundled_variations' ) );
	}

	/**
	 * Ajax search for bundled variations.
	 */
	public static function ajax_search_bundled_variations() {

		if ( ! empty( $_GET[ 'include' ] ) ) {
			if ( $product = wc_get_product( absint( $_GET[ 'include' ] ) ) ) {
				$_GET[ 'include' ] = $product->get_children();
			} else {
				$_GET[ 'include' ] = array();
			}
		}

		add_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'tweak_variation_titles' ) );
		WC_AJAX::json_search_products( '', true );
		remove_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'tweak_variation_titles' ) );
	}

	/**
	 * Tweak variation titles for consistency across different WC versions.
	 *
	 * @param  array  $search_results
	 * @return array
	 */
	public static function tweak_variation_titles( $search_results ) {

		if ( ! empty( $search_results ) ) {

			$search_result_objects = array_map( 'wc_get_product', array_keys( $search_results ) );

			foreach ( $search_result_objects as $variation ) {
				if ( $variation && $variation->is_type( 'variation' ) ) {
					$variation_id                    = $variation->get_id();
					$search_results[ $variation_id ] = rawurldecode( WC_PB_Helpers::get_product_variation_title( $variation, 'flat' ) );
				}
			}
		}

		return $search_results;
	}

	/**
	 * Handles adding bundled products via ajax.
	 */
	public static function ajax_add_bundled_product() {

		check_ajax_referer( 'wc_bundles_add_bundled_product', 'security' );

		$loop       = intval( $_POST[ 'id' ] );
		$post_id    = intval( $_POST[ 'post_id' ] );
		$product_id = intval( $_POST[ 'product_id' ] );
		$item_id    = false;
		$toggle     = 'open';
		$tabs       = WC_PB_Meta_Box_Product_Data::get_bundled_product_tabs();
		$product    = wc_get_product( $product_id );
		$title      = $product->get_title();
		$sku        = $product->get_sku();
		$title      = WC_PB_Helpers::format_product_title( $title, $sku, '', true );
		$title      = sprintf( _x( '#%1$s: %2$s', 'bundled product admin title', 'woocommerce-product-bundles' ), $product_id, $title );

		$item_data         = array();
		$item_availability = '';

		$response          = array(
			'markup'  => '',
			'message' => ''
		);

		if ( $product ) {

			if ( in_array( $product->get_type(), array( 'simple', 'variable', 'subscription', 'variable-subscription' ) ) ) {

				if ( ! $product->is_in_stock() ) {
					$item_availability = '<mark class="outofstock">' . __( 'Out of stock', 'woocommerce' ) . '</mark>';
				}

				ob_start();
				include( 'meta-boxes/views/html-bundled-product-admin.php' );
				$response[ 'markup' ] = ob_get_clean();

			} else {
				$response[ 'message' ] = __( 'The selected product cannot be bundled. Please select a simple product, a variable product, or a simple/variable subscription.', 'woocommerce-product-bundles' );
			}

		} else {
			$response[ 'message' ] = __( 'The selected product is invalid.', 'woocommerce-product-bundles' );
		}

		wp_send_json( $response );
	}
}

WC_PB_Admin_Ajax::init();
