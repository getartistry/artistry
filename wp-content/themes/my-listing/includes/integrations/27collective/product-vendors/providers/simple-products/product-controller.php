<?php

namespace CASE27\Integrations\ProductVendors;

class ProductController extends \WC_REST_Products_Controller {
	public function c27_create_product( $data ) {

		$product = $this->prepare_object_for_database($data, true);

		if ( ! is_wp_error( $product ) ) {
			$product->save();
			return $product;
		}

		return false;
	}
}