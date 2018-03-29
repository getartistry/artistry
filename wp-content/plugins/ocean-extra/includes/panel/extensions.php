<?php
/**
 * Extensions
 *
 * @package 	Ocean_Extra
 * @category 	Core
 * @author 		OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class Ocean_Extra_Extensions {

	/**
	 * Start things up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ), 9999 );
		add_action( 'admin_init', array( $this, 'create_admin_page' ) );
	}

	/**
	 * Add sub menu page
	 *
	 * @since 1.0.0
	 */
	public function add_page() {
		// If no premium extensions
		if ( apply_filters( 'oceanwp_licence_tab_enable', false ) ) {
			return;
		}

		add_submenu_page(
			'oceanwp-panel',
			esc_html__( 'Extensions', 'ocean-extra' ),
			'<span class="dashicons dashicons-star-filled" style="font-size: 16px; color: #ec4848;"></span> <span style="color: #ec4848">' . esc_html__( 'Extensions', 'ocean-extra' ) . '</span>',
			'manage_options',
			'oceanwp-extensions',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Redirect to the extensions page
	 *
	 * @since 1.0.0
	 */
	public function create_admin_page() {

		// Get link
		$url = 'https://oceanwp.org/extensions/';

		// If affiliate ref
		$ref_url = '';
		$aff_ref = apply_filters( 'ocean_affiliate_ref', $ref_url );

		// Add & is has referal link
		if ( $aff_ref ) {
			$if_ref = '&';
		} else {
			$if_ref = '?';
		}

		// Add source
		$utm = $if_ref . 'utm_source=wp-menu&utm_campaign=extensions&utm_medium=wp-dash';

		if ( isset( $_GET['page'] ) && 'oceanwp-extensions' === $_GET['page'] ) {
			wp_redirect( $url . $aff_ref . $utm );
			die;
		}			
	}
}
new Ocean_Extra_Extensions();