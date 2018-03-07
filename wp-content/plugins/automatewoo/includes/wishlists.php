<?php

namespace AutomateWoo;

/**
 * @class Wishlists
 */
class Wishlists {

	/**
	 * @var array
	 */
	static $integration_options = [
		'yith' => 'YITH Wishlists',
		'woothemes' => 'WooCommerce Wishlists'
	];


	/**
	 * @return string|false
	 */
	static function get_integration() {
		if ( class_exists( 'WC_Wishlists_Plugin') ) {
			return 'woothemes';
		}
		elseif ( class_exists( 'YITH_WCWL') ) {
			return 'yith';
		}
		else {
			return false;
		}
	}


	/**
	 * @return string|false
	 */
	static function get_integration_title() {
		$integration = self::get_integration();

		if ( ! $integration )
			return false;

		return self::$integration_options[$integration];
	}


	/**
	 * Get wishlist by ID
	 *
	 * @param int $id
	 * @return bool|Wishlist
	 */
	static function get_wishlist( $id ) {

		$integration = self::get_integration();

		if ( ! $id || ! $integration )
			return false;

		if ( $integration == 'yith' ) {
			$wishlist = YITH_WCWL()->get_wishlist_detail( $id );
		}
		elseif ( $integration == 'woothemes' ) {
			$wishlist = get_post( $id );
		}
		else {
			return false;
		}

		return self::get_normalized_wishlist( $wishlist );
	}



	/**
	 * Convert wishlist objects from both integrations into the same format
	 * Returns false if wishlist is empty
	 *
	 * @param $wishlist \WP_Post|array
	 * @return Wishlist|false
	 */
	static function get_normalized_wishlist( $wishlist ) {

		$integration = self::get_integration();

		if ( ! $wishlist || ! $integration ) {
			return false;
		}

		$normalized_wishlist = new Wishlist();
		$normalized_wishlist->integration = $integration;


		if ( $integration == 'yith' ) {

			if ( ! is_array( $wishlist ) ) {
				return false;
			}

			$normalized_wishlist->id = $wishlist['ID'];
			$normalized_wishlist->owner_id = $wishlist['user_id'];
		}
		elseif ( $integration == 'woothemes' ) {

			if ( ! $wishlist instanceof \WP_Post ) {
				return false;
			}

			$normalized_wishlist->id = $wishlist->ID;
			$normalized_wishlist->owner_id = get_post_meta( $wishlist->ID, '_wishlist_owner', true );
		}

		return $normalized_wishlist;
	}

}
