<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription_Items
 */
class Subscription_Items extends Order_Items {

	public $data_item = 'subscription';

	function init() {
		parent::init();

		$this->title = __( 'Subscription Items', 'automatewoo' );
		$this->group = __( 'Subscription', 'automatewoo' );
	}

}

return new Subscription_Items();
