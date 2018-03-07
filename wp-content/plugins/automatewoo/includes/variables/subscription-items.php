<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Items
 */
class Variable_Subscription_Items extends Variable_Abstract_Product_Display {


	public $supports_order_table = true;


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Displays a product listing of items in a subscription.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters array
	 * @param $workflow
	 * @return string
	 */
	function get_value( $subscription, $parameters, $workflow ) {

		$template = isset( $parameters['template'] ) ? $parameters['template'] : false;
		$items = $subscription->get_items();
		$products = [];

		foreach ( $items as $item ) {
			$products[] = Compat\Subscription::get_product_from_item( $subscription, $item );
		}

		$args = array_merge( $this->get_default_product_template_args( $workflow, $parameters ), [
			'products' => $products,
			'subscription' => $subscription,
			'order' => $subscription
		]);

		return $this->get_product_display_html( $template, $args );
	}

}

return new Variable_Subscription_Items();
