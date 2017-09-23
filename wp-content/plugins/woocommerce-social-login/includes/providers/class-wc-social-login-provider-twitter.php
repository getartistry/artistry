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
 * Twitter social login provider class
 *
 * @since 1.0.0
 */
class WC_Social_Login_Provider_Twitter extends WC_Social_Login_Provider {


	/**
	 * Twitter constructor.
	 *
	 * @since 1.0.0
	 * @param string $base_auth_path Base authentication path.
	 */
	public function __construct( $base_auth_path ) {

		$this->id                = 'twitter';
		$this->title             = __( 'Twitter', 'woocommerce-social-login' );
		$this->color             = '#55acee';
		$this->internal_callback = 'oauth_callback';
		$this->require_ssl       = false;

		parent::__construct( $base_auth_path );

		// normalize profile
		add_filter( 'wc_social_login_' . $this->get_id() . '_profile', array( $this, 'normalize_profile' ) );
	}


	/**
	 * Get the provider's description
	 *
	 * Individual providers may override this to provide specific instructions,
	 * like displaying a callback URL
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string strategy class
	 */
	public function get_description() {

		/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
		$description = sprintf( __( 'Need help setting up and configuring Twitter? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woocommerce.com/document/woocommerce-social-login-create-social-apps#twitter">', '</a>');

		$callback_url_format = get_option( 'wc_social_login_callback_url_format' );

		/* translators: Placeholder: %s - a url */
		$description .= '<br/><br/>' . sprintf( __( 'The callback URL is %s', 'woocommerce-social-login' ), '<code>' . $this->get_callback_url() . '</code>' );

		if ( 'legacy' === $callback_url_format ) {

			$description .= ' <strong>' . __( '(Please update your Twitter app to use this URL)', 'woocommerce-social-login' ) . '</strong>';

			/* translators: Placeholder: %s - a url */
			$description .= '<br/><br/>' . sprintf( __( 'The legacy callback URL is %s', 'woocommerce-social-login' ), '<code>' . $this->get_callback_url( $callback_url_format ) . '</code>' );
		}

		return $description;
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
		 * @since 1.0.0
		 * @param array $config See http://hybridauth.sourceforge.net/userguide/Configuration.html
		 */
		return apply_filters( 'wc_social_login_' . $this->get_id() . '_hybridauth_config', array(
			'enabled' => true,
			'keys'    => array(
				'key'    => $this->get_client_id(),
				'secret' => $this->get_client_secret(),
			),
			'wrapper' => array(
				'path'  => wc_social_login()->get_plugin_path() . '/includes/hybridauth/class-sv-hybrid-providers-twitter.php',
				'class' => 'SV_Hybrid_Providers_Twitter',
			),
		) );
	}


	/**
	 * Override the default form fields to tweak the title for the client ID/secret
	 * so it matches Twitter's UI
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['id']['title']     = __( 'Consumer Key', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'Consumer Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {
		return __( 'Log in with Twitter', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {
		return __( 'Link your account to Twitter', 'woocommerce-social-login' );
	}


	/**
	 * Twitter returns a `name`, so try to map it to `first_name` & `last_name`
	 *
	 * @since 1.1.0
	 * @param array $profile twitter profile data
	 * @return array
	 */
	public function normalize_profile( $profile ) {

		// Twitter only provides the 'name' so we need to try to split this to 'first_name' & 'last_name'
		// but we do not want to overwrite the 'first_name' & 'last_name' if they are already set
		if ( isset( $profile['first_name'] ) ) {

			$name = explode( ' ', $profile['first_name'] );

			$profile['first_name'] = implode( ' ', array_slice( $name, 0, count( $name ) - 1 ) );

			if ( ! isset( $profile['last_name'] ) ) {

				// get the last element
				$profile['last_name'] = array_pop( $name );
			}
		}

		return $profile;
	}


	/**
	 * Get notices.
	 *
	 * @since 2.0.4
	 * @return array
	 */
	public function get_notices() {
		return array(
			'account_linked'         => __( 'Your Twitter account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'Twitter was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This Twitter account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this Twitter account already exists.', 'woocommerce-social-login' ),
		);
	}


}
