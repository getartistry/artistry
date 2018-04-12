<?php
/**
 * ConverPlug Service Campayn
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Campayn API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Campayn extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'campayn';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'first_name', 'last_name', 'title', 'address', 'city', 'state', 'zip', 'company', 'country' );

	/**
	 * The HTTP protocol
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $api_protocol
	 */
	private $api_protocol = 'http';

	/**
	 * The API version
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $api_version
	 */
	private $api_version = 1;

	/**
	 * Request data from the third party API.
	 *
	 * @since 1.0.0
	 * @param string $base_url  Base URL where API is available.
	 * @param string $api_key   API Key provided by this service.
	 * @param string $endpoint  Method to request available from this service.
	 * @param array  $params    Data to be passed to API.
	 * @return array|object     The API response.
	 */
	private function get_api( $base_url, $api_key, $endpoint, $params = array() ) {
		// Exclude http:// from the user's input.
		$request_uri = $this->api_protocol . '://' . preg_replace( '#^https?://#', '', $base_url ) . '/api/v' . $this->api_version . $endpoint;

		$params['timeout'] = 60;
		$params['body']    = isset( $params['data'] ) && $params['data'] ? json_encode( $params['data'] ) : '';
		$params['headers'] = array(
			'Authorization' => 'TRUEREST apikey=' . $api_key,
		);
		$response          = wp_remote_get( $request_uri, $params );
		$response_code     = wp_remote_retrieve_response_code( $response );
		$response_message  = wp_remote_retrieve_response_message( $response );
		$get_response      = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_wp_error( $response ) || ( 200 != $response_code ) ) {

			if ( is_wp_error( $response ) ) {
				$data['error'] = $response->get_error_message();
			} else {
				$data['error'] = isset( $get_response['msg'] ) ? $get_response['msg'] : $response_code . ' - ' . $response_message;
			}
		} else {
			if ( $get_response ) {
				$data = $get_response;
			} else {
				$data = $response;
			}
		}
		return $data;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type string $api_host A valid Host.
	 *      @type string $api_key  A valid API key.
	 * }
	 * @throws Exception Error Message.
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 */
	public function connect( $fields = array() ) {
		$response = array(
			'error' => false,
			'data'  => array(),
		);

		// Make sure we have the Host.
		if ( ! isset( $fields['api_host'] ) || empty( $fields['api_host'] ) ) {
			$response['error'] = __( 'Error: You must provide a Host.', 'convertpro-addon' );
		} // Make sure we have an API key.
		elseif ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			try {
				$result = $this->get_api( $fields['api_host'], $fields['api_key'], '/lists.json' );

				if ( ! empty( $result ) ) {

					if ( ! isset( $result['error'] ) ) {
						$response['data'] = array(
							'api_host' => $fields['api_host'],
							'api_key'  => $fields['api_key'],
						);
					} else {
						/* translators: %s Error Message */
						$response['error'] = sprintf( __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again. %s', 'convertpro-addon' ), $result['error'] );
					}
				} else {
					throw new Exception( 'Error: There seems to be an error with the configuration' );
				}
			} catch ( Exception $e ) {
				$response['error'] = $e->getMessage();
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
			'api_host', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'Campayn Domain', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'Campayn API Key', 'convertpro-addon' ),
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
		return array(
			'api_host' => $auth_meta['api_host'],
			'api_key'  => $auth_meta['api_key'],
		);
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param string $account The name of the saved account.
	 * @param object $settings Saved module settings.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 * }
	 */
	public function render_fields( $account, $settings ) {
		$account_data = ConvertPlugServices::get_account_data( $account );
		$results      = $this->get_api( $account_data['api_host'], $account_data['api_key'], '/lists.json' );

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( isset( $results['error'] ) ) {
			/* translators: %s Error Message */
			$response['error'] = sprintf( __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again. %s', 'convertpro-addon' ), $results['error'] );
		} else {

			foreach ( $results as $list ) {
				$lists = $list;
			}

			if ( array_key_exists( 'id', $lists ) ) {
				$response['html'] = $this->render_list_field( $results, $settings );
			} else {
				$response['error'] .= __( 'Error: No lists found.', 'convertpro-addon' );
			}
		}

		return $response;
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
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array  $lists List data from the API.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_list_field( $lists, $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['campayn_list_id'] ) ) ? $settings['default']['campayn_list_id'] : '' ) : '';
		}

		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists as $list ) {
			$options[ $list['id'] ] = $list['list_name'];
		}

		ConvertPlugHelper::render_input_html(
			'campayn_list_id', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'An email list from third party provider.', 'convertpro-addon' ),
				'default' => $default,
				'options' => $options,
			), $settings
		);

		return ob_get_clean();
	}

	/**
	 * Subscribe an email address to Campayn.
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
		$response     = array(
			'error' => false,
		);
		$contact_id   = null;

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Campayn! The account is no longer connected.', 'convertpro-addon' );
		} else {

			// Build data array.
			$data = array(
				'email' => $email,
			);

			$custom_arr = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] && '' != $p ) {
						$data[ $settings['meta'][ $key ] ] = $p;
					}
				}
			}

			// Check if email already exists.
			$result = $this->get_api(
				$account_data['api_host'], $account_data['api_key'],
				"/lists/{$settings['campayn_list_id']}/contacts.json?filter[contact]=" . $email
			);

			// Already exists.
			if ( ! isset( $result['error'] ) && ( is_array( $result ) && isset( $result[0]['id'] ) ) ) {
				$contact_id = $result[0]['id'];
			}

			// Add the contact if it doesn't exist.
			if ( ! $contact_id ) {
				$endpoint = "/lists/{$settings['campayn_list_id']}/contacts.json";
				$method   = 'POST';
			} else {
				$endpoint   = "/contacts/{$contact_id}.json";
				$method     = 'PUT';
				$data['id'] = $contact_id;
			}

			$result = $this->get_api(
				$account_data['api_host'], $account_data['api_key'], $endpoint, array(
					'data'   => $data,
					'method' => $method,
				)
			);

			if ( isset( $result['error'] ) ) {
				/* translators: %s Error Message */
				$response['error'] = sprintf( __( 'There was an error subscribing to Campayn. %s', 'convertpro-addon' ), $result['error'] );
			}
		}
		return $response;
	}
}
