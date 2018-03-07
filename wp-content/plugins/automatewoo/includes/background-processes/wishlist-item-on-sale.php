<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Wishlists;
use AutomateWoo\Triggers;
use AutomateWoo\Clean;
use AutomateWoo\Customer_Factory;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for the Wishlist Item On Sale trigger
 */
class Wishlist_Item_On_Sale extends Base {

	/** @var string  */
	public $action = 'wishlist_item_on_sale';


	/**
	 * @param array $data
	 * @return bool
	 */
	protected function task( $data ) {

		$wishlist = isset( $data['wishlist_id'] ) ? Wishlists::get_wishlist( Clean::id( $data['wishlist_id'] ) ) : false;
		$sale_product_ids = isset( $data['product_ids'] ) ? Clean::ids( $data['product_ids'] ) : false;
		$trigger = Triggers::get( 'wishlist_item_goes_on_sale' );

		if ( ! $wishlist || ! $sale_product_ids || ! $trigger ) {
			return false;
		}

		foreach( $wishlist->get_items() as $product_id ) {

			if ( in_array( $product_id, $sale_product_ids ) ) {

				$customer = Customer_Factory::get_by_user_id( $wishlist->get_user_id() );

				$trigger->maybe_run([
					'customer' => $customer,
					'product' => wc_get_product( $product_id ),
					'wishlist' => $wishlist
				]);
			}
		}

		return false;
	}

}

return new Wishlist_Item_On_Sale();
