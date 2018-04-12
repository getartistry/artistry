<?php
/**
 * Collects leads and subscribe to AWeber
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the AWeber API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_AWeber extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'aweber';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that AWeber
	 * has already defined. When AWeber releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'name' );

	/**
	 * Store API instance
	 *
	 * @since 1.0.0
	 * @var object $api_instance
	 * @access private
	 */
	private $api_instance = null;

	/**
	 * Get an instance of the API.
	 *
	 * @since 1.0.0
	 * @param string $auth_code A valid API key.
	 * @return object The API instance.
	 */
	public function get_api( $auth_code ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( ! class_exists( 'AWeberAPI' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/aweber/aweber_api.php';
		}

		list( $auth_key, $auth_token, $req_key, $req_token, $oauth ) = explode( '|', $auth_code );

		$this->api_instance                     = new AWeberAPI( $auth_key, $auth_token );
		$this->api_instance->user->requestToken = $req_key;
		$this->api_instance->user->tokenSecret  = $req_token;
		$this->api_instance->user->verifier     = $oauth;

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields A valid API key.
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 */
	public function connect( $fields = array() ) {
		$response            = array(
			'error' => false,
			'data'  => array(),
		);
		$access_token        = '';
		$access_token_secret = '';

		// Make sure we have an authorization code.
		if ( ! isset( $fields['auth_code'] ) || empty( $fields['auth_code'] ) ) {
			$response['error'] = __( 'Error: You must provide an Authorization Code.', 'convertpro-addon' );
		} // Make sure we have a valid authorization code.
		elseif ( 6 != count( explode( '|', $fields['auth_code'] ) ) ) {
			$response['error'] = __( 'Error: Please enter a valid Authorization Code.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields['auth_code'] );
			// Get an access token from the API.
			try {
				list( $access_token, $access_token_secret ) = $api->getAccessToken();
			} catch ( AWeberException $e ) {
				$response['error'] = $e->getMessage();
			}

			// Make sure we can get the account.
			try {
				$account = $api->getAccount();
			} catch ( AWeberException $e ) {
				$response['error'] = $e->getMessage();
			}
			// Build the response data.
			if ( false == $response['error'] ) {

				$response['data'] = array(
					'auth_code'     => $fields['auth_code'],
					'access_token'  => $access_token,
					'access_secret' => $access_token_secret,
				);
				update_option( '_cp_v2_aweber_temp', $response['data'] );
			}
		}

		return $response;
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 1.0.0
	 * @return string The connection settings markup.
	 */
	public function render_connect_settings() {
		ob_start();

		ConvertPlugHelper::render_input_html(
			'auth_code', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Authorization Code', 'convertpro-addon' ),
				/* translators: %s Link */
				'desc'  => sprintf( __( 'Please register this website with AWeber to get your Authorization Code. <a%s rel="noopener">Register Now</a>', 'convertpro-addon' ), ' href="https://auth.aweber.com/1.0/oauth/authorize_app/87e41bb0" target="_blank"' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Returns the api_key in array format
	 *
	 * @since 1.0.0
	 * @param string $auth_meta $api_key A valid API key.
	 * @return array Array of api_key
	 */
	public function render_auth_meta( $auth_meta ) {

		$opt = get_option( '_cp_v2_aweber_temp' );
		delete_option( '_cp_v2_aweber_temp' );

		return array(
			'auth_code'     => $auth_meta['auth_code'],
			'access_token'  => $opt['access_token'],
			'access_secret' => $opt['access_secret'],
		);
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param string $account The name of the saved account.
	 * @param object $post_data Posted data.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 *      @type array $mapping_fields The field mapping array for AWeber.
	 * }
	 */
	public function render_fields( $account, $post_data ) {
		$account_data = ConvertPlugServices::get_account_data( $account );
		$api          = $this->get_api( $account_data['auth_code'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		try {
			$account           = $api->getAccount( $account_data['access_token'], $account_data['access_secret'] );
			$lists             = $account->loadFromUrl( '/accounts/' . $account->id . '/lists' );
			$response['html']  = $this->render_list_field( $lists->data, $post_data );
			$response['html'] .= $this->render_tags_field( $post_data );
		} catch ( AWeberException $e ) {
			$response['error'] = $e->getMessage();
		}

		return $response;
	}

	/**
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array $lists List data from the API.
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_list_field( $lists, $settings ) {
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['aweber_list'] ) ) ? $settings['default']['aweber_list'] : '' ) : '';
		}

		foreach ( $lists['entries'] as $list ) {
			$options[ $list['id'] ] = $list['name'];
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'aweber_list', array(
				'class'   => '',
				'type'    => 'select',
				'label'   => __( 'Select a List', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $options,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Render markup for the tag field.
	 *
	 * @since 1.0.0
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_tags_field( $settings ) {

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['aweber_tags'] ) ) ? $settings['default']['aweber_tags'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'aweber_tags', array(
				'class'   => 'cpro-tags',
				'type'    => 'text-wrap',
				'label'   => __( 'Tags', 'convertpro-addon' ),
				'help'    => __( 'Please separate tags with a comma.', 'convertpro-addon' ),
				'default' => $default,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Mapping fields.
	 *
	 * @since 1.0.0
	 */
	public function render_mapping() {
		return self::$mapping_fields;
	}

	/**
	 * Subscribe an email address to AWeber.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email ) {
		$account_data = ConvertPlugServices::get_account_data( $settings['api_connection'] );

		$response  = array(
			'error' => false,
		);
		$merge_arr = array();

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to AWeber! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api  = $this->get_api( $account_data['auth_code'] );
			$data = array(
				'ws.op'       => 'create',
				'email'       => $email,
				'add_notes'   => $this->_get_ip(),
				'ad_tracking' => CPRO_BRANDING_NAME,
			);

			if ( isset( $settings['aweber_tags'] ) ) {
				$data['tags'] = explode( ',', $settings['aweber_tags'] );
			}

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$merge_arr[ $settings['meta'][ $key ] ] = $p;
					} else {
						$merge_arr[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			if ( isset( $merge_arr['name'] ) ) {
				$data['name'] = $merge_arr['name'];
			}

			if ( ! empty( $merge_arr ) ) {
				$data['custom_fields'] = $merge_arr;
			}

			$aweber_list = $settings['aweber_list'];

			try {
				$account = $api->getAccount( $account_data['access_token'], $account_data['access_secret'] );

				$list        = $account->loadFromUrl( $account->url . "/lists/{$aweber_list}" );
				$subscribers = $list->subscribers;
				$result      = $subscribers->create( $data );
			} catch ( AWeberAPIException $e ) {
				if ( false !== strpos( 'already subscribed', $e->getMessage() ) ) {
					$response['error'] = sprintf(
						/* translators: %s Error Message */
						__( 'There was an error subscribing to AWeber! %s', 'convertpro-addon' ),
						$e->getMessage()
					);
				}
			}
		}

		return $response;
	}

	/**
	 * Get User's IP
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function _get_ip() {

		$exec     = exec( 'hostname' );
		$hostname = trim( $exec );
		$ip       = gethostbyname( $hostname );

		return $ip;
	}
}
