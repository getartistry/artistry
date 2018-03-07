<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Types
 * @since 2.4.6
 */
class Data_Types extends Registry {

	/** @var array */
	static $includes;

	/** @var array  */
	static $loaded = [];


	/**
	 * @return array
	 */
	static function load_includes() {

		$path = AW()->path( '/includes/data-types/' );

		return apply_filters( 'automatewoo/data_types/includes', [
			'user' => $path . 'user.php',
			'order' => $path . 'order.php',
			'product' => $path . 'product.php',
			'category' => $path . 'category.php',
			'tag' => $path . 'tag.php',
			'wishlist' => $path . 'wishlist.php',
			'guest' => $path . 'guest.php',
			'order_note' => $path . 'order-note.php',
			'order_item' => $path . 'order-item.php',
			'cart' => $path . 'cart.php',
			'workflow' => $path . 'workflow.php',
			'subscription' => $path . 'subscription.php',
			'post' => $path . 'post.php',
			'comment' => $path . 'comment.php',
			'membership' => $path . 'membership.php',
			'customer' => $path . 'customer.php',
			'review' => $path . 'review.php'
		]);
	}


	/**
	 * @param $data_type_id
	 * @return Data_Type|false
	 */
	static function get( $data_type_id ) {
		return parent::get( $data_type_id );
	}


	/**
	 * @param string $data_type_id
	 * @param Data_Type $data_type
	 */
	static function after_loaded( $data_type_id, $data_type ) {
		$data_type->set_id( $data_type_id );
	}


	/**
	 * @return array
	 */
	static function get_non_stored_data_types() {
		return [ 'shop' ];
	}

}
