<?php
/**
 * Switch User Package
 * Allow user to switch package of a listing.
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;
use \CASE27\Classes\Conditions\Conditions;

/**
 * Switch Package.
 *
 * @since 1.0.0
 */
class Switch_Package {

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

		// Add custom form fields template so WPJM can recognize it.
		add_filter( 'job_manager_locate_template', array( $this, 'add_payment_package_field' ) , 10, 3 );

		// Add switch package field.
		add_filter( 'submit_job_form_fields', array( $this, 'add_switch_package_field' ), 30 );
		add_filter( 'submit_job_form_fields_get_job_data', array( $this, 'add_switch_package_field_data' ), 10, 2 );

		// Save/Switch the Package.
		add_action( 'job_manager_update_job_data', array( $this, 'switch_package' ), 10, 2 );
	}

	/**
	 * Add Payment Package Field
	 *
	 * @since 1.0.0
	 *
	 * @param string $template      Found template path.
	 * @param string $template_name Loaded template name.
	 * @param string $template_path Template Path.
	 * @return string
	 */
	public function add_payment_package_field( $template, $template_name, $template_path ) {
		if ( 'form-fields/payment-package-field.php' === $template_name && ! $template ) {
			return trailingslashit( CASE27_INTEGRATIONS_DIR ) . 'wp-job-manager/templates/' . $template_name;
		}
		return $template;
	}

	/**
	 * Add Switch Package Field in Edit Form
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Fields.
	 * @return array
	 */
	function add_switch_package_field( $fields ) {
		// Bail if not in edit form.
		if ( ! isset( $_GET['action'], $_GET['job_id'] ) || 'edit' !== $_GET['action'] || is_admin() ) {
			return $fields;
		}

		$fields['job']['case27_user_package'] = array(
			'label'             => __( 'Switch Payment Package', 'my-listing' ),
			'type'              => 'switch-package', // "form-fields/switch-package-field.php".
			'slug'              => 'switch-package', // "form-fields/switch-package-field.php".
			'required'          => false,
			'placeholder'       => '',
			'priority'          => 1000,
			'default'           => '',
			'value'             => '',
			'payment_package'   => false,
			'payment_packages'  => array(),
			'listing'           => false,
		);

		return $fields;
	}

	/**
	 * Add Switch Package Field Data.
	 * The "submit_job_form_fields" hook is too early, data is not yet set after submitting the form.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $fields  Job fields.
	 * @param object $listing Listing object.
	 * @return array
	 */
	public function add_switch_package_field_data( $fields, $listing ) {
		if ( ! isset( $fields['job']['case27_user_package'] ) ) {
			return $fields;
		}

		// You can only switch if previously have package.
		if ( ! $listing || ! $listing->_user_package_id || ! $listing->post_author ) {
			unset( $fields['job']['case27_user_package'] );
			return $fields;
		}

		// Conditional.
		foreach ( $fields as $group_key => $group ) {
			foreach( $group as $k => $field ) {
				$conditions = new Conditions( $field, $listing );
				if ( ! $conditions->passes() ) {
					unset( $fields[ $group_key ][ $k ] );
				}
			}
		}

		// Get user packages.
		$_packages = case27_paid_listing_get_user_packages( array(
			'post__not_in' => array( $listing->_user_package_id ),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => '_user_id',
					'value'   => $listing->post_author,
					'compare' => 'IN',
				),
			),
		) );

		// No user package available for switching, remove switch option.
		if ( ! $_packages ) {
			unset( $fields['job']['case27_user_package'] );
			return $fields;
		}

		// Allowed Products.
		$allowed_products = array();
		$_listing = new \CASE27\Classes\Listing( $listing );
		if ( $_listing->type ) {
			foreach ( $_listing->type->get_packages() as $allowed_package ) {
				$pid = isset( $allowed_package['package'] ) ? $allowed_package['package'] : false;
				if ( $pid ) {
					$allowed_products[] = $pid;
				}
			}
		}

		$packages = array();
		foreach ( $_packages as $package_id ) {
			$package_object = case27_paid_listing_get_package( $package_id );
			if ( ! $package_object->has_package() ) {
				continue;
			}
			if ( $allowed_products && $product_id = $package_object->get_product_id() ) {
				if ( in_array( $product_id, $allowed_products ) ) {
					$packages[ $package_id ] = $package_object;
				}
			} else {
				$packages[ $package_id ] = $package_object;
			}
		}

		if ( ! $packages ) {
			unset( $fields['job']['case27_user_package'] );
			return $fields;
		}

		// Current package.
		$current_package = case27_paid_listing_get_package( $listing->_user_package_id );
		if ( $current_package->has_package() ) {
			$fields['job']['case27_user_package']['payment_package'] = $current_package;
		}

		// Available packages to switch.
		$fields['job']['case27_user_package']['payment_packages'] = $packages;

		// Listing data.
		$fields['job']['case27_user_package']['listing'] = $listing;

		return $fields;
	}

	/**
	 * Switch Package When Saving Listing
	 *
	 * @since 1.0.0
	 *
	 * @param int   $post_id Listing ID
	 * @param array $values  Form values.
	 */
	public function switch_package( $post_id, $values ) {
		// Check if payment package is submitted.
		if ( ! isset( $_POST['payment-package'] ) || ! absint( $_POST['payment-package'] ) ) {
			return;
		}

		$listing = get_post( $post_id );

		$current_package_id = absint( $listing->_user_package_id );
		$new_package_id = absint( $_POST['payment-package'] );

		// Bail. No switch.
		if ( $current_package_id === $new_package_id ) {
			return;
		}

		// New Package.
		$package = case27_paid_listing_get_package( $new_package_id );

		// Check if new package applicable.
		if ( ! $package->has_package() || 'publish' !== $package->get_status() || ! $package->get_user_id() || absint( $listing->post_author ) !== absint( $package->get_user_id() ) ) {
			return;
		}

		// Process Switch.
		update_post_meta( $listing->ID, '_user_package_id', $new_package_id );

		// Update package count & status.
		$count = absint( absint( $package->get_count() ) + 1 );
		update_post_meta( $package->get_id(), '_count', $count ? absint( $count ) : '' );
		$status = case27_paid_listing_get_proper_status( $package->get_id() );
		if ( $status && $package->get_status() !== $status ) {
			wp_update_post( array(
				'ID'          => $package->get_id(),
				'post_status' => $status,
			) );
		}

		// Update listing based on package.
		update_post_meta( $listing->ID, '_job_duration', absint( $package->get_duration() ) );
		update_post_meta( $listing->ID, '_featured', $package->is_featured() ? 1 : 0 );
		$expire_time = \calculate_job_expiry( $listing->ID );
		if ( $expire_time ) {
			update_post_meta( $listing->ID, '_job_expires', $expire_time );
		}
	}

}

Switch_Package::instance();
