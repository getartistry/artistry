<?php

namespace CASE27\Integrations\ProductVendors;

class ProductVendors {
	use \CASE27\Traits\Instantiatable;

	private $providers = [
		'simple-products' => 'simple-products/simple-products.php',
		'wc-vendors' => 'wc-vendors/wc-vendors.php',
	];

	public function __construct()
	{
		require_once trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/product-vendors/providers/provider-interface.php';
	}

	public function activate()
	{
		if ( ! c27()->get_setting( 'product_vendors_enable', false ) ) {
			return false;
		}

		$provider_name = c27()->get_setting('product_vendors_provider', 'simple-products');

		if ( in_array( $provider_name, array_keys( $this->providers ) ) ) {
			$provider = $this->get_provider( $provider_name );
			$provider->activate();
		}
	}

	public function get_provider( $provider_name )
	{
		$path = trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/product-vendors/providers/';

		return require_once $path . $this->providers[ $provider_name ];
	}
}

ProductVendors::instance()->activate();
