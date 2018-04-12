<?php
/**
 * Listing Package Product Object
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * Listing Package Product Object
 *
 * @since 1.0.0
 * @extends WC_Product_Simple
 */
class Product extends \WC_Product_Simple {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param int|WC_Product|object $product Product ID, post object, or product object
	 */
	public function __construct( $product ) {
		$this->product_type = 'job_package';
		parent::__construct( $product );
	}

	/**
	 * Get product type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'job_package';
	}

	/**
	 * Compatibility function to retrieve product meta.
	 * Simpler than using WC 3 Getter/Setter Method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Meta Key.
	 * @return mixed
	 */
	public function get_product_meta( $key ) {
		return $this->get_meta( '_' . $key );
	}


	/**
	 * Listing package is only sold individually.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_sold_individually() {
		return true;
	}

	/**
	 * Always purchaseable.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_purchasable() {
		return true;
	}

	/**
	 * Is a virtual product. No shipping.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * Return job listing duration granted
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_duration() {
		$duration = $this->get_product_meta( 'job_listing_duration' );
		return absint( $duration ? $duration : get_option( 'job_manager_submission_duration', 30 ) );
	}

	/**
	 * Return job listing limit
	 *
	 * @since 1.0.0
	 *
	 * @return int 0 if unlimited
	 */
	public function get_limit() {
		$limit = $this->get_product_meta( 'job_listing_limit' );
		return absint( $limit ? $limit : 0 );
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
