<?php
/**
 * User Package Object
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * User Listing Package Object
 *
 * @since 1.0.0
 */
class Package {

	/**
	 * @var WP_Post
	 *
	 * @since 1.0.0
	 */
	private $package = '';

	/**
	 * @var WC_Product
	 *
	 * @since 1.0.0
	 */
	private $product = '';

	/**
	 * @var WP_User
	 *
	 * @since 1.0.0
	 */
	private $user = '';

	/**
	 * Constructor
	 *
	 * @param int $post_id Post ID.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $post_id ) {
		$this->package = get_post( $post_id );
	}

	/**
	 * Checks if package is set.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_package() {
		return ! empty( $this->package );
	}

	/**
	 * Get package ID.
	 * Old package ID is stored in "_package_id".
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id() {
		return absint( $this->has_package() ? $this->package->ID : false );
	}

	/**
	 * Get Status
	 *
	 * @since 1.0.0
	 *
	 * @return false|string
	 */
	public function get_status() {
		if ( $this->has_package() ) {
			return $this->package->post_status;
		}
		return false;
	}

	/**
	 * Get Product Object
	 *
	 * @since 1.0.0
	 *
	 * @return WC_Product
	 */
	public function get_product() {
		if ( empty( $this->product ) && $this->has_package() ) {
			$this->product = wc_get_product( $this->package->_product_id );
		}
		return $this->product;
	}

	/**
	 * Get Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_product_id() {
		return $this->get_product() ? $this->get_product()->get_id() : 0;
	}

	/**
	 * Get title: Use Product Name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->get_product() ? $this->get_product()->get_name() : '#' . $this->get_id();
	}

	/**
	 * Featured option.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_featured() {
		return $this->package ? ( $this->package->_featured ? true : false ) : false;
	}

	/**
	 * Use For Claims.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_use_for_claims() {
		return $this->package ? ( $this->package->_use_for_claims ? true : false ) : false;
	}

	/**
	 * Get Limit.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_limit() {
		return $this->package ? absint( $this->package->_limit ) : false;
	}

	/**
	 * Get Count.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_count() {
		return $this->package ? absint( $this->package->_count ) : false;
	}

	/**
	 * Get Remaining Count.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_remaining_count() {
		return absint( absint( $this->get_limit() ) - absint( $this->get_count() ) );
	}

	/**
	 * Get Duration.
	 *
	 * @since 1.0.0
	 *
	 * @return int|bool
	 */
	public function get_duration() {
		return $this->package ? absint( $this->package->_duration ) : false;
	}

	/**
	 * Get Order ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_order_id() {
		return $this->package ? absint( $this->package->_order_id ) : false;
	}

	/**
	 * Get User ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_user_id() {
		return $this->package ? absint( $this->package->_user_id ) : false;
	}
}
