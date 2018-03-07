<?php

namespace AutomateWoo;

/**
 * @class Wishlist
 * @since 2.9.9
 */
class Wishlist {

	public $id;
	public $integration;
	public $owner_id;
	public $items;


	/**
	 * @return int
	 */
	function get_id() {
		return absint( $this->id );
	}


	/**
	 * @return int
	 */
	function get_user_id() {
		return absint( $this->owner_id );
	}


	/**
	 * @return array
	 */
	function get_items() {

		if ( isset( $this->items ) ) {
			return $this->items;
		}

		$this->items = [];

		if ( $this->integration === 'yith' ) {

			$products = YITH_WCWL()->get_products([
				'wishlist_id' => $this->get_id(),
				'user_id' => $this->get_user_id()
			]);

			if ( ! empty( $products ) ) {
				foreach( $products as $product ) {
					$this->items[] = $product['prod_id'];
				}
			}
		}
		elseif ( $this->integration == 'woothemes' ) {

			$products = get_post_meta( $this->get_id(), '_wishlist_items', true );

			if ( $products ) {
				foreach ( $products as $product ) {
					$this->items[] = $product['product_id'];
				}
			}
		}

		$this->items = array_unique( $this->items );

		return $this->items;
	}


	/**
	 * @return string
	 */
	function get_link() {
		if ( $this->integration === 'yith' ) {
			return YITH_WCWL()->get_wishlist_url();
		}
		elseif ( $this->integration === 'woothemes' ) {
			if ( class_exists( 'WC_Wishlists_Pages' ) ) {
				return add_query_arg( [ 'wlid' => $this->get_id() ], \WC_Wishlists_Pages::get_url_for('view-a-list') );
			}
		}
		return '';
	}

}
