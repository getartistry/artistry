<?php

namespace CASE27\Classes\Conditions;

class Conditions {
	private $field, $conditions, $listing, $package_id;

	public function __construct( $field, $listing = null ) {
		$this->field = $field;
		$this->conditions = ! empty( $field['conditions'] ) ? $field['conditions'] : [];
		$this->conditional_logic = isset( $field['conditional_logic'] ) ? $field['conditional_logic'] : false;
		$this->listing = $listing;
		$this->package_id = $this->get_package_id();
	}

	public function passes() {
		$results = [];

		// If there's no conditional logic, show the field.
		if ( ! $this->conditional_logic ) {
			return true;
		}

		// Title and Description need to always be visible.
		if ( in_array( $this->field['slug'], [ 'job_title', 'job_description' ] ) ) {
			return true;
		}

		$this->conditions = array_filter( $this->conditions );

		// Return true if there isn't any condition set.
		if ( empty( $this->conditions ) ) {
			return true;
		}

		// Loop through the condition blocks.
		// First level items consists of arrays related as "OR".
		// Second level items consists of conditions related as "AND".
		// dump( sprintf( 'Looping through %s condition groups...', $this->field['slug'] ) );
		foreach ( $this->conditions as $conditionGroup ) {
			if ( empty( $conditionGroup ) ) {
				continue;
			}

			foreach ( $conditionGroup as $condition ) {
				if ( $condition['key'] == '__listing_package' ) {
					if ( ! ( $package_id = $this->package_id ) ) {
						// dump( 'Condition failed (package id not found).' );
						$results[] = false;
						continue(2);
					}

					if ( ! $this->compare( $condition, $package_id ) ) {
						// dump( 'Condition failed.', $condition );
						$results[] = false;
						continue(2);
					}

					// dump( 'Condition passed.' );
				}
			}

			$results[] = true;
		}

		// Return true if any of the condition groups is true.
		return in_array( true, $results );
	}

	public function compare( $condition, $value ) {
		if ( $condition['compare'] == '==' ) {
			return $condition['value'] == $value;
		}

		if ( $condition['compare'] == '!=' ) {
			return $condition['value'] != $value;
		}

		return false;
	}

	/**
	 * Get WC Product ID Related to Listing.
	 * This is used to check the visibility fields using product ID.
	 *
	 * @since unknown
	 *
	 * @return int|false
	 */
	public function get_package_id() {

		// In editing listing.
		if ( $this->listing && ! in_array( $this->listing->post_status, [ 'preview', 'pending_payment' ] ) ) {
			return self::get_package_product_id( $this->listing->_user_package_id );
		}

		// WPJM WC Paid Listing Submission: Package selected.
		if ( isset( $_POST['job_package'] ) && ! empty( $_POST['job_package'] ) ) {

			// Is a WC Product.
			if ( is_numeric( $_POST['job_package'] ) ) {
				return absint( $_POST['job_package'] );
			}

			// Not numeric. User Package.
			return self::get_package_product_id( absint( substr( $_POST['job_package'], 5 ) ) );
		}

		// C27 Paid Listing Module.
		if ( isset( $_POST['listing_package'] ) && ! empty( $_POST['listing_package'] ) ) {
			$post = get_post( $_POST['listing_package'] );
			if ( ! $post ) {
				return false;
			}

			// Is WC Product.
			if ( 'product' === $post->post_type ) {
				return $post->ID;
			}

			// Is (User) Payment Package.
			return self::get_package_product_id( $post->ID );
		}

		// Use WC Cookie Sets (other WPJM WC Paid Listing Submission Flow).
		if ( isset( $_COOKIE['chosen_package_id'] ) && isset( $_COOKIE['chosen_package_is_user_package'] ) ) {
			$package_id = absint( $_COOKIE['chosen_package_id'] );
			$is_user_package = absint( $_COOKIE['chosen_package_is_user_package'] ) === 1;

			// User package.
			if ( $is_user_package ) {
				return self::get_package_product_id( $package_id );
			}

			return $package_id ? absint( $package_id ) : false;
		}

		// Package not found.
		return false;
	}

	/**
	 * Get Product ID From User Package.
	 * Use WPJM WC Paid Listing or Internal/C27 Paid Listing Package.
	 *
	 * @since unknown
	 *
	 * @param int $package_id User Package ID.
	 * @return int|false Product ID or false.
	 */
	public static function get_package_product_id( $user_package_id ) {
		$package = false;

		// Bail early if not set.
		if ( ! $user_package_id || ! is_numeric( $user_package_id ) ) {
			return false;
		}

		// WPJM WC Paid Listing.
		if ( function_exists( 'wc_paid_listings_get_user_package' ) ) {
			$package = wc_paid_listings_get_user_package( $user_package_id );
		} elseif ( function_exists( 'case27_paid_listing_get_package' ) ) { // C27 Paid Listing Module.
			$package = case27_paid_listing_get_package( $user_package_id );
		}

		// Return product ID if found.
		if ( $package && method_exists( $package, 'has_package' ) && method_exists( $package, 'get_product_id' ) ) {
			return $package->has_package() ? absint( $package->get_product_id() ) : false;
		}

		return false;
	}
}
