<?php
/**
 * Paid Listing Modules.
 *
 * @version 1.0.0
 * @author 27Collective
 *
 *    Copyright: 2018 27Collective
 *    License: GNU General Public License v3.0
 *    License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 *    Copyright: 2017 Astoundify
 *    License: GNU General Public License v3.0
 *    License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 *    Copyright: 2015 Automattic
 *    License: GNU General Public License v3.0
 *    License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
**/
namespace CASE27\Integrations\Paid_Listings;

/**
 * Paid Listing App Bootstrap.
 *
 * @since 1.0.0
 */
class Paid_Listings {

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
		// Do not load if Paid Listing/Listing Payments active.
		if ( class_exists( '\WC_Paid_Listings' ) || defined( 'ASTOUNDIFY_WPJMLP_VERSION' ) ) {
			return;
		}

		// Bail early if WooCommerce or WPJM is not active.
		if ( ! class_exists( '\WooCommerce' ) || ! class_exists( '\WP_Job_Manager' ) ) {
			return;
		}

		// Add settings to enable this.
		add_filter( 'job_manager_settings', function( $settings ) {
			$settings['job_submission'][1][] = array(
				'name'      => 'case27_paid_listings',
				'std'       => '1',
				'label'     => __( 'Paid Listings', 'my-listing' ),
				'cb_label'  => __( 'Enable Paid Listings.', 'my-listing' ),
				'desc'      => __( 'This feature will enable User Payment Packages using WooCommerce checkout.', 'my-listing' ),
				'type'      => 'checkbox',
			);
			$settings['job_submission'][1][] = array(
				'name'      => 'case27_claim_listings',
				'std'       => '1',
				'label'     => __( 'Claim Listings', 'my-listing' ),
				'cb_label'  => __( 'Enable Claim Listings.', 'my-listing' ),
				'desc'      => __( 'This feature will enable claim/verified listing functionality.', 'my-listing' ),
				'type'      => 'checkbox',
			);
			$settings['job_submission'][1][] = array(
				'name'      => 'case27_claim_requires_approval',
				'std'       => '1',
				'label'     => __( 'Claim Requires Approval', 'my-listing' ),
				'cb_label'  => __( 'Require admin approval of all new claim submissions.', 'my-listing' ),
				'desc'      => __( 'Sets all new claims to "pending." They will not implemented until an admin approves them.', 'my-listing' ),
				'type'      => 'checkbox',
			);
			return $settings;
		} );

		// Bail if not enabled.
		if ( ! get_option( 'case27_paid_listings' ) ) {
			return;
		}

		// Get current path.
		$path = get_template_directory() . '/includes/integrations/27collective/paid-listings/';

		// Load Functions.
		require_once( $path . 'functions.php' );

		// Migrate WPJM WC Paid Listing DB.
		if ( case27_paid_listing_need_migration() ) {
			require_once( $path . 'class-migrate-wcpl.php' );
		}

		// Package Object.
		require_once( $path . 'class-package.php' );

		// Product Object.
		require_once( $path . 'class-product.php' );

		// Load User Packages.
		require_once( $path . 'class-user-packages.php' );

		// Load Claim Listing.
		if ( ! defined( '\\WPJMCL_VERSION' ) || get_option( 'case27_claim_listings', true ) ) {
			require_once( $path . 'functions-claim.php' );
			require_once( $path . 'class-claim.php' );
		}

		// WP Job Manager.
		require_once( $path . 'class-wp-job-manager.php' );

		// WooCommerce.
		require_once( $path . 'class-woocommerce.php' );

		// Switch Package.
		if ( apply_filters( 'case27_paid_listing_allow_switch_package', true ) ) {
			require_once( $path . 'class-switch-package.php' );
		}

		// WC Subscriptions.
		if ( class_exists( '\WC_Subscriptions' ) ) {
			require_once( $path . 'class-product-subscription.php' );
			require_once( $path . 'class-wc-subscriptions.php' );
			require_once( $path . 'class-wc-subscriptions-payments.php' );
		}
	}
}

Paid_Listings::instance();
