<?php

namespace AutomateWoo;

/**
 * @class Preview_Data
 * @since 2.4.6
 */
class Preview_Data {

	/**
	 * @return array
	 */
	static function get_preview_data_layer() {

		$data_layer = [];


		/**
		 * User
		 */
		$data_layer['user'] = wp_get_current_user();

		$data_layer['customer'] = Customer_Factory::get_by_user_id( get_current_user_id() );


		$orders = wc_get_orders([
			'type' => 'shop_order',
			'limit' => 1,
			'return' => 'ids',
		]);

		if ( ! empty( $orders ) ) {

			$data_layer['order'] = wc_get_order( $orders[0] );

			if ( $data_layer['order'] ) {
				$data_layer['order_item'] = current( $data_layer['order']->get_items() );
				$data_layer['order_item']['id'] = current( array_keys( $data_layer['order']->get_items() ) );
			}
		}


		/**
		 * Product
		 */
		$product_query = new \WP_Query([
			'post_type' => 'product',
			'posts_per_page' => 4,
			//'orderby' => 'rand',
			'fields' => 'ids'
		]);
		$data_layer['product'] = wc_get_product( $product_query->posts[0] );


		/**
		 * Category
		 */
		$cats = get_terms([
			'taxonomy' => 'product_cat',
			'order' => 'count',
			'number' => 1
		]);

		$data_layer['category'] = current($cats);


		/**
		 * Cart
		 */
		$cart = new Cart();
		$cart->set_id( 1 );
		$cart->set_total( 100 );
		$cart->set_token();
		$cart->set_date_last_modified( new \DateTime() );

		$items = [];

		foreach ( $product_query->posts as $product_id ) {

			$product = wc_get_product( $product_id );

			if ( $product->is_type('variable') ) {
				$variations = $product->get_available_variations();
				$variation_id = $variations[0]['variation_id'];
				$variation = $variations[0]['attributes'];
			}
			else {
				$variation_id = 0;
				$variation = [];

			}

			$items[] = [
				'product_id' => $product_id,
				'variation_id' => $variation_id,
				'variation' => $variation,
				'quantity' => 1,
//				'line_total' => $product->get_price(),
				'line_subtotal' => $product->get_price(),
				'line_subtotal_tax' => Compat\Product::get_price_including_tax( $product ) - $product->get_price(),
			];




		}

		$cart->set_items( $items );

		$cart->set_coupons([
			'10off' => [
				'discount_incl_tax' => '10',
				'discount_excl_tax' => '9',
				'discount_tax' => '1'
			]
		]);

		$data_layer['cart'] = $cart;


		/**
		 * Wishlist
		 */
		$wishlist = new Wishlist();
		$wishlist->items = $product_query->posts;


		$data_layer['wishlist'] = $wishlist;


		$guest = new Guest();
		$guest->set_email( 'guest@example.com' );
		$data_layer['guest'] = $guest;


		if ( Integrations::subscriptions_enabled() ) {
			/**
			 * Subscription
			 */
			$subscriptions = wcs_get_subscriptions([
				'subscriptions_per_page' => 1
			]);

			$data_layer['subscription'] = current($subscriptions);
		}


		if ( Integrations::is_memberships_enabled() ) {

			$memberships = get_posts( [
				'post_type' => 'wc_user_membership',
				'post_status' => 'any',
				'posts_per_page' => 1,
			]);

			$data_layer['membership'] = wc_memberships_get_user_membership( current($memberships) );
		}


		return apply_filters( 'automatewoo/preview_data_layer', $data_layer );
	}
}
