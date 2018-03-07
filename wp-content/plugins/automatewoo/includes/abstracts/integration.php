<?php

namespace AutomateWoo;

/**
 * @class Integration
 * @since 2.3
 */
abstract class Integration {

	/** @var string */
	public $integration_id;

	/** @var \WC_Logger */
	private $log;

	/** @var bool */
	public $log_errors = true;


	/**
	 * @param $message
	 */
	protected function log( $message ) {

		if ( ! $this->log_errors )
			return;

		if ( ! $this->log ) {
			$this->log = new \WC_Logger();
		}

		$this->log->add( 'automatewoo-integration-' . $this->integration_id, $message );
	}
}
