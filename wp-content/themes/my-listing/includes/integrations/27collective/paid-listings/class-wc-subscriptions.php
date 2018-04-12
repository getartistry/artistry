<?php
/**
 * WC SUbscriptions Integrations Setup.
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * WC Subscriptions Integrations.
 *
 * @since 1.0.0
 */
class WC_Subscriptions {

	/**
	 * Use singleton instance.
	 */
	use \CASE27\Traits\Instantiatable;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 */
	public function __construct() {

		// Add listing as valid subscription.
		add_filter( 'woocommerce_is_subscription', array( $this, 'woocommerce_is_subscription' ), 10, 2 );

		// Add product type.
		add_filter( 'woocommerce_subscription_product_types', array( $this, 'add_subscription_product_types' ) );
		add_filter( 'product_type_selector', array( $this, 'add_product_type_selector' ) );

		// Product Class.
		add_filter( 'woocommerce_product_class' , array( $this, 'set_product_class' ), 10, 3 );

		// Add to cart.
		add_action( 'woocommerce_job_package_subscription_add_to_cart', '\WC_Subscriptions::subscription_add_to_cart', 30 );
	}

	/**
	 * Is this a subscription product?
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_subscription Is package a subscription.
	 * @param int  $product_id      WC Product ID.
	 * @return bool
	 */
	public function woocommerce_is_subscription( $is_subscription, $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product && $product->is_type( array( 'job_package_subscription' ) ) ) {
			$is_subscription = true;
		}
		return $is_subscription;
	}

	/**
	 * Types for subscriptions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $types Subscription types.
	 * @return array
	 */
	public function add_subscription_product_types( $types ) {
		$types[] = 'job_package_subscription';
		return $types;
	}

	/**
	 * Add the product type selector.
	 *
	 * @since 1.0.0
	 *
	 * @param array $types Subscription types.
	 * @return array
	 */
	public function add_product_type_selector( $types ) {
		$types['job_package_subscription'] = __( 'Listing Subscription', 'my-listing' );
		return $types;
	}

	/**
	 * Set Product Class to Load.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classname Current classname found.
	 * @param string $product_type Current product type.
	 * @return string $classname
	 */
	public function set_product_class( $classname, $product_type ) {
		if ( 'job_package_subscription' === $product_type ) {
			return 'CASE27\Integrations\Paid_Listings\Product_Subscription';
		}

		return $classname;
	}

}

WC_Subscriptions::instance();
