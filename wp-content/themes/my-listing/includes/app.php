<?php

class MyListing {
	use \CASE27\Traits\Instantiatable;

	private $classes;

	public function register( $name, $instance ) {
		$this->classes[ $name ] = $instance;
	}

	public function __call( $method, $params ) {
		if ( isset( $this->classes[ $method ] ) ) {
			return $this->classes[ $method ];
		}

		return null;
	}
}

function mylisting() {
	return MyListing::instance();
}
