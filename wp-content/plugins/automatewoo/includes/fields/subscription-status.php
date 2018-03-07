<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription_Status
 */
class Subscription_Status extends Select {

	protected $name = 'subscription_status';

	/**
	 * @param bool $allow_all
	 */
	function __construct( $allow_all = true ) {
		parent::__construct( true );

		$this->set_title( __( 'Subscription status', 'automatewoo' ) );

		if ( $allow_all ) {
			$this->set_placeholder( __( '[Any]', 'automatewoo' ) );
		}

		$this->set_options( wcs_get_subscription_statuses() );
	}

}
