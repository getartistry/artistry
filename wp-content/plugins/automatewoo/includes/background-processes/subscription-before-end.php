<?php

namespace AutomateWoo\Background_Processes;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for the subscription before end trigger
 */
class Subscription_Before_End extends Subscription_Before_Renewal {

	/** @var string  */
	public $action = 'subscription_before_end';

}

return new Subscription_Before_End();
