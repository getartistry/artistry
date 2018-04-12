<?php

namespace CASE27\Integrations\Claims;

class Claims {
	use \CASE27\Traits\Instantiatable;

	private $providers = [
		'wpjm-claim-listing' => 'wpjm-claim-listing/wpjm-claim-listing.php',
	];

	public function __construct()
	{
		require_once trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/claims/providers/provider-interface.php';
	}

	public function activate()
	{
		$provider_name = 'wpjm-claim-listing';

		if ( in_array( $provider_name, array_keys( $this->providers ) ) ) {
			$provider = $this->get_provider( $provider_name );
			$provider->activate();
		}
	}

	public function get_provider( $provider_name )
	{
		$path = trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/claims/providers/';

		return require_once $path . $this->providers[ $provider_name ];
	}
}

Claims::instance()->activate();
