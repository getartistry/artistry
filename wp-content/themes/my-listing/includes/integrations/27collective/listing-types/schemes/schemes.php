<?php

namespace CASE27\Integrations\ListingTypes\Schemes;

class Schemes {
	use \CASE27\Traits\Instantiatable;

	private $path, $schemes;

	public function __construct() {
        $this->path = trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/listing-types/schemes/';
	}

	public function get( $scheme ) {
		if ( isset( $this->schemes[ $scheme ] ) ) {
			return $this->schemes[ $scheme ];
		}

		if ( file_exists( trailingslashit( $this->path ) . $scheme . '.php' ) ) {
			$this->schemes[ $scheme ] = require_once trailingslashit( $this->path ) . $scheme . '.php';
			return $this->schemes[ $scheme ];
		}

		return false;
	}
}

mylisting()->register( 'schemes', Schemes::instance() );
