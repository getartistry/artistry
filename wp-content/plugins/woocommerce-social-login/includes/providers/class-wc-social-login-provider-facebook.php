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
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-social-login/ for more information.
 *
 * @package   WC-Social-Login/Providers
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Facebook social login provider class
 *
 * @since 1.0.0
 */
class WC_Social_Login_Provider_Facebook extends WC_Social_Login_Provider {


	/**
	 * Facebook constructor.
	 *
	 * @since 1.0.0
	 * @param string $base_auth_path Base authentication path.
	 */
	public function __construct( $base_auth_path ) {

		$this->id          = 'facebook';
		$this->title       = __( 'Facebook', 'woocommerce-social-login' );
		$this->color       = '#3b5998';
		$this->require_ssl = false;

		/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
		$this->description = sprintf( __( 'Need help setting up and configuring Facebook? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woocommerce.com/document/woocommerce-social-login-create-social-apps#facebook">', '</a>' );

		parent::__construct( $base_auth_path );
	}


	/**
	 * Get the description
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string
	 */
	public function get_description() {
		/* translators: Placeholders: %1$s - opening HTML <a> tag, %2$s - closing HTML </a> tag */
		return sprintf( __( 'Need help setting up and configuring Facebook? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woocommerce.com/document/woocommerce-social-login-create-social-apps#facebook">', '</a>' );
	}


	/**
	 * Return the providers HybridAuth config
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_hybridauth_config() {

		/**
		 * Filter provider's HybridAuth configuration.
		 *
		 * @since 2.0.0
		 * @param array $config See http://hybridauth.sourceforge.net/userguide/Configuration.html
		 */
		return apply_filters( 'wc_social_login_' . $this->get_id() . '_hybridauth_config', array(
			'enabled' => true,
			'keys'    => array(
				'id'     => $this->get_client_id(),
				'secret' => $this->get_client_secret(),
			),
			'wrapper' => array(
				'path'  => wc_social_login()->get_plugin_path() . '/includes/hybridauth/class-sv-hybrid-providers-facebook.php',
				'class' => 'SV_Hybrid_Providers_Facebook',
			),
			'scope'   => 'public_profile, email', // user_location is excluded as it requires approval from Facebook
		) );
	}


	/**
	 * Override the default form fields to tweak the title for the client ID/secret
	 * so it matches Facebook's UI
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['id']['title']     = __( 'App ID', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'App Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {
		return __( 'Log in with Facebook', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {
		return __( 'Link your account to Facebook', 'woocommerce-social-login' );
	}


	/**
	 * Get notices.
	 *
	 * @since 2.0.4
	 * @return array
	 */
	public function get_notices() {
		return array(
			'account_linked'         => __( 'Your Facebook account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'Facebook was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This Facebook account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this Facebook account already exists.', 'woocommerce-social-login' ),
		);
	}


}
