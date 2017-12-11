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
 * @package   WC-Social-Login/Providers/HybridAuth
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */


/**
 * Linkedin provider for HybridAuth tailored for WP
 *
 * @since 2.0.0
 */
class SV_Hybrid_Providers_Linkedin extends Hybrid_Provider_Model_OAuth2 {


	/**
	 * Initialize the provider.
	 *
	 * This method is largely the same as in the original provider with the
	 * exception of using the WP_OAuth2_Client instead of the built-in OAuth2Client class
	 *
	 * @since 2.0.0
	 */
	public function initialize() {

		if ( ! $this->config['keys']['id'] || ! $this->config['keys']['secret'] ) {
			throw new Exception( "Your application id and secret are required in order to connect to {$this->providerId}.", 4 );
		}

		// override requested scope
		if ( isset( $this->config['scope'] ) && ! empty( $this->config['scope'] ) ) {
			$this->scope = $this->config['scope'];
		}

		// include OAuth2 client
		require_once( Hybrid_Auth::$config['path_libraries'] . 'OAuth/OAuth2Client.php' );
		require_once( wc_social_login()->get_plugin_path() . '/includes/hybridauth/class-wp-oauth2-client.php' );

		// create a new OAuth2 client instance
		$this->api = new WP_OAuth2_Client( $this->config['keys']['id'], $this->config['keys']['secret'], $this->endpoint, $this->compressed );

		$this->api->api_base_url    = 'https://api.linkedin.com/v1/';
		$this->api->authorize_url   = 'https://www.linkedin.com/oauth/v2/authorization';
		$this->api->token_url       = 'https://www.linkedin.com/oauth/v2/accessToken';

		// If we have an access token, set it
		if ( $this->token( 'access_token' ) ) {
			$this->api->access_token            = $this->token('access_token');
			$this->api->refresh_token           = $this->token('refresh_token');
			$this->api->access_token_expires_in = $this->token('expires_in');
			$this->api->access_token_expires_at = $this->token('expires_at');
			$this->api->request_headers = array( 'Authorization' => 'Bearer ' . $this->api->access_token );
		}

		if ( empty( $this->config['fields'] ) ) {
			$this->config['fields'] = array(
				'id',
				'first-name',
				'last-name',
				'public-profile-url',
				'picture-url',
				'email-address',
				'date-of-birth',
				'phone-numbers',
				'summary',
			);
		}
	}


	/**
	 * Load the user profile from the IDp API client
	 *
	 * @since 2.0.0
	 * @return array
	 * @throws Exception
	 */
	public function getUserProfile() {

		$data = $this->api->get( 'people/~:('. implode(',', $this->config['fields']) .')?format=json' );

		// if the provider identifier is not received, we assume the auth has failed
		if ( ! isset( $data->id ) ) {
			throw new Exception( "User profile request failed! {$this->providerId} api returned an invalid response: " . Hybrid_Logger::dumpData( $data ), 6 );
		}

		// # store the user profile.
		$this->user->profile->identifier    = ( property_exists ( $data, 'id' ) )               ? $data->id : '';
		$this->user->profile->firstName     = ( property_exists ( $data, 'firstName' ) )        ? $data->firstName : '';
		$this->user->profile->lastName      = ( property_exists ( $data, 'lastName' ) )         ? $data->lastName : '';
		$this->user->profile->profileURL    = ( property_exists ( $data, 'publicProfileUrl' ) ) ? $data->publicProfileUrl : '';
		$this->user->profile->email         = ( property_exists ( $data, 'emailAddress' ) )     ? $data->emailAddress : '';
		$this->user->profile->emailVerified = ( property_exists ( $data, 'emailAddress' ) )     ? $data->emailAddress : '';
		$this->user->profile->photoURL      = ( property_exists ( $data, 'pictureUrl' ) )       ? $data->pictureUrl : '';
		$this->user->profile->description   = ( property_exists ( $data, 'summary' ) )          ? $data->summary : '';
		$this->user->profile->country       = ( property_exists ( $data, 'country' ) )          ? strtoupper( $data->country ) : '';
		$this->user->profile->displayName   = trim( $this->user->profile->firstName . ' ' . $this->user->profile->lastName );

		if ( property_exists( $data, 'phoneNumbers' ) && property_exists( $data->phoneNumbers, 'phoneNumber' ) ) {
			$this->user->profile->phone = (string) $data->phoneNumbers->phoneNumber;
		} else {
			$this->user->profile->phone = null;
		}

		if ( property_exists( $data, 'dateOfBirth' ) ) {
			$this->user->profile->birthDay   = (string) $data->dateOfBirth->day;
			$this->user->profile->birthMonth = (string) $data->dateOfBirth->month;
			$this->user->profile->birthYear  = (string) $data->dateOfBirth->year;
		}

		return $this->user->profile;
	}


}
