<?php

namespace AutomateWoo\Event_Helpers;

/**
 * @class Product_Goes_On_Sale
 */
class Products_On_Sale {


	static function init() {
		add_action( 'automatewoo_fifteen_minute_worker', [ __CLASS__, 'compare_on_sale_lists' ] );
	}


	static function compare_on_sale_lists() {

		$last_on_sale = get_option( 'automatewoo_products_last_on_sale' );
		$now_on_sale = wc_get_product_ids_on_sale();
		update_option( 'automatewoo_products_last_on_sale', $now_on_sale, false );

		if ( ! is_array( $last_on_sale ) ) {
			$last_on_sale = [];
		}

		$diff = array_diff( $now_on_sale, $last_on_sale );

		if ( $diff ) {
			do_action( 'automatewoo/products/gone_on_sale', $diff );
		}
	}

}
