<?php

namespace AutomateWoo;

/**
 * @class Frontend_Endpoints
 * @since 2.8.6
 */
class Frontend_Endpoints {


	static function handle() {

		$action = sanitize_key( aw_request( 'aw-action' ) );

		switch ( $action ) {

			case 'restore-cart':
				self::restore_cart();
				break;

			case 'unsubscribe':
				Emails::catch_unsubscribe_url();
				break;

			case 'click':
				Emails::catch_click_track_url();
				break;

			case 'open':
				Emails::catch_open_track_url();
				break;

			case 'reorder':
				self::reorder();
				break;

		}
	}


	static function restore_cart() {

		$token = Clean::string( aw_request( 'token' ) );
		$redirect = Clean::string( aw_request( 'redirect' ) );
		$restored = Carts::restore_cart( $token );

		// preserve other URL params such as utm_source or apply_coupon
		$url_params = aw_get_query_args( [ 'aw-action', 'token', 'redirect' ] );

		$redirect_options = [ 'cart', 'checkout' ];

		if ( ! in_array( $redirect, $redirect_options ) ) {
			$redirect = 'cart';
		}

		if ( $restored ) {
			wc_add_notice( __( 'Your cart has been restored.', 'automatewoo' ) );
			$url_params['aw-cart-restored'] = 'success';
			wp_redirect(add_query_arg( $url_params, wc_get_page_permalink( $redirect ) ) );
		}
		else {
			wc_add_notice( __( 'Your cart could not be restored, it may have expired.', 'automatewoo' ), 'notice' );
			$url_params['aw-cart-restored'] = 'fail';
			wp_redirect(add_query_arg( $url_params, wc_get_page_permalink( $redirect ) ) );
		}

		exit;
	}



	/**
	 * Similar to WC_Form_Handler:order_again()
	 */
	static function reorder() {

		$order_id = wc_get_order_id_by_order_key( Clean::string( aw_request( 'aw-order-key' ) ) );
		$order = wc_get_order( absint( $order_id ) );

		if ( ! $order ) {
			wc_add_notice( __( 'The previous order could not be found.', 'automatewoo' ) );
			return;
		}

		WC()->cart->empty_cart();

		// Copy products from the order to the cart
		foreach ( $order->get_items() as $item ) {
			// Load all product info including variation data
			$product_id   = (int) apply_filters( 'woocommerce_add_to_cart_product_id', Compat\Order_Item::get_product_id( $item ) );
			$quantity     = Compat\Order_Item::get_quantity( $item );
			$variation_id = Compat\Order_Item::get_variation_id( $item );
			$variations   = [];
			$cart_item_data = apply_filters( 'woocommerce_order_again_cart_item_data', [], $item, $order );

			if ( version_compare( WC()->version, '3.0', '<' ) ) {
				foreach ( $item['item_meta'] as $meta_name => $meta_value ) {
					if ( taxonomy_is_product_attribute( $meta_name ) ) {
						$variations[ $meta_name ] = $meta_value[0];
					} elseif ( meta_is_product_attribute( $meta_name, $meta_value[0], $product_id ) ) {
						$variations[ $meta_name ] = $meta_value[0];
					}
				}
			}
			else {
				foreach ( $item->get_meta_data() as $meta ) {
					if ( taxonomy_is_product_attribute( $meta->meta_key ) ) {
						$variations[ $meta->meta_key ] = $meta->meta_value;
					} elseif ( meta_is_product_attribute( $meta->meta_key, $meta->meta_value, $product_id ) ) {
						$variations[ $meta->meta_key ] = $meta->meta_value;
					}
				}
			}


			// Add to cart validation
			if ( ! apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations, $cart_item_data ) ) {
				continue;
			}

			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations, $cart_item_data );
		}

		do_action( 'woocommerce_ordered_again', Compat\Order::get_id( $order ) );

		// Redirect to cart
		wc_add_notice( __( 'The cart has been filled with the items from your previous order.', 'automatewoo' ) );
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}

}
