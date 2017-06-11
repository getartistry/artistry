<?php
/**
 * WooCommerce Social Login
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Social Login to newer
 * versions in the future. If you wish to customize WooCommerce Social Login for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-social-login/ for more information.
 *
 * @package     WC-Social-Login/Template
 * @author      SkyVerge
 * @copyright   Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * Social Login Global Functions
 *
 * @version 1.1.0
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'woocommerce_social_login_buttons' ) ) :

/**
 * Pluggable function to render social login buttons
 *
 * @since 1.0
 * @param string $return_url Return url, defaults to the current url
 */
function woocommerce_social_login_buttons( $return_url = null ) {

	if ( is_user_logged_in() ) {
		return;
	}

	// If no return_url, use the current URL
	if ( ! $return_url ) {
		$return_url = home_url( add_query_arg( array() ) );
	}

	/**
	 * Filter the return URL
	 *
	 * @since 1.6.0
	 * @param string $return_url Return url, defaults to the current url
	 */
	$return_url = apply_filters( 'wc_social_login_buttons_return_url', $return_url );

	// Enqueue styles and scripts
	wc_social_login()->frontend->load_styles_scripts();

	// load the template
	wc_get_template(
		'global/social-login.php',
		array(
			'providers'  => wc_social_login()->get_available_providers(),
			'return_url' => $return_url,
			'login_text' => get_option( 'wc_social_login_text' ),
		),
		'',
		wc_social_login()->get_plugin_path() . '/templates/'
	);
}

endif;


if ( ! function_exists( 'woocommerce_social_login_link_account_buttons' ) ) :

	/**
	 * Pluggable function to render social login "link your account" buttons
	 *
	 * @since 1.1.0
	 * @param string $return_url Return url, defaults my account page
	 */
	function woocommerce_social_login_link_account_buttons( $return_url = null ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		// If no return_url, use the my account page
		if ( ! $return_url ) {
			$return_url = wc_get_page_permalink( 'myaccount' );
		}

		// Enqueue styles and scripts
		wc_social_login()->frontend->load_styles_scripts();

		$available_providers = array();

		// determine available providers for user
		foreach ( wc_social_login()->get_available_providers() as $provider ) {

			if ( ! get_user_meta( get_current_user_id(), '_wc_social_login_' . $provider->get_id() . '_profile', true ) ) {
				$available_providers[] = $provider;
			}
		}

		// load the template
		wc_get_template(
			'global/social-login-link-account.php',
			array(
				'available_providers'  => $available_providers,
				'return_url'           => $return_url,
			),
			'',
			wc_social_login()->get_plugin_path() . '/templates/'
		);
	}

endif;
