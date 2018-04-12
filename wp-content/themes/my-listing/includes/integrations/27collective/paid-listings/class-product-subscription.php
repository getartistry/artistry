<?php
/**
 * Listing Package Product Subscriptions Object
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * Listing Package Product Object
 *
 * @since 1.0.0
 * @extends WC_Product_Subscription
 */
class Product_Subscription extends \WC_Product_Subscription {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param int|WC_Product|object $product Product ID, post object, or product object
	 */
	public function __construct( $product ) {
		parent::__construct( $product );
		$this->product_type = 'job_package_subscription';
	}

	/**
	 * Compatibility function for `get_id()` method
	 *
	 * @return int
	 */
	public function get_id() {
		return parent::get_id();
	}

	/**
	 * Get product id
	 *
	 * @return int
	 */
	public function get_product_id() {
		return $this->get_id();
	}

	/**
	 * Compatibility function to retrieve product meta.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get_product_meta( $key ) {
		return $this->get_meta( '_' . $key );
	}

	/**
	 * Get product type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'job_package_subscription';
	}

	/**
	 * Checks the product type.
	 *
	 * Backwards compat with downloadable/virtual.
	 *
	 * @access public
	 * @param mixed $type Array or string of types
	 * @return bool
	 */
	public function is_type( $type ) {
		return ( 'job_package_subscription' == $type || ( is_array( $type ) && in_array( 'job_package_subscription', $type ) ) ) ? true : parent::is_type( $type );
	}

	/**
	 * We want to sell jobs one at a time
	 *
	 * @return boolean
	 */
	public function is_sold_individually() {
		return true;
	}
	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @access public
	 * @return string
	 */
	public function add_to_cart_url() {
		$url = $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );

		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
	}

	/**
	 * Jobs are always virtual
	 *
	 * @return boolean
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * Return job listing duration granted
	 *
	 * @return int
	 */
	public function get_duration() {
		$job_listing_duration = $this->get_job_listing_duration();
		if ( 'listing' === $this->get_package_subscription_type() ) {
			return false;
		} elseif ( $job_listing_duration ) {
			return $job_listing_duration;
		} else {
			return get_option( 'job_manager_submission_duration' );
		}
	}

	/**
	 * Return job listing limit
	 *
	 * @return int 0 if unlimited
	 */
	public function get_limit() {
		$job_listing_limit = $this->get_job_listing_limit();
		if ( $job_listing_limit ) {
			return $job_listing_limit;
		} else {
			return 0;
		}
	}

	/**
	 * Return if featured
	 *
	 * @return bool true if featured
	 */
	public function is_job_listing_featured() {
		return 'yes' === $this->get_job_listing_featured();
	}

	/**
	 * Get job listing featured flag
	 *
	 * @return string
	 */
	public function get_job_listing_featured() {
		return $this->get_product_meta( 'job_listing_featured' );
	}

	/**
	 * Get job listing limit
	 *
	 * @return int
	 */
	public function get_job_listing_limit() {
		return $this->get_product_meta( 'job_listing_limit' );
	}

	/**
	 * Get job listing duration
	 *
	 * @return int
	 */
	public function get_job_listing_duration() {
		return $this->get_product_meta( 'job_listing_duration' );
	}

	/**
	 * Get package subscription type
	 *
	 * @return string
	 */
	public function get_package_subscription_type() {
		return $this->get_product_meta( 'package_subscription_type' );
	}

	/**
	 * Is Featured Listing
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_listing_featured() {
		$featured = $this->get_product_meta( 'job_listing_featured' );
		return 'yes' === $featured ? true : false;
	}

	/**
	 * Is Featured Listing
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_use_for_claims() {
		$use_for_claims = $this->get_product_meta( 'use_for_claims' );
		return 'yes' === $use_for_claims ? true : false;
	}
}
