<?php
/**
 * ConverPlug Service Constant Contact
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Constant Contact API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Constant_Contact extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'constant-contact';

	/**
	 * The api url for this service.
	 *
	 * @since 1.0.0
	 * @var string $api_url
	 */
	public $api_url = 'https://api.constantcontact.com/v2/';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Constant Contact
	 * has already defined. When Constant Contact releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'prefix_name', 'first_name', 'last_name', 'middle_name', 'job_title', 'home_phone', 'work_phone', 'cell_phone', 'company_name' );

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields A valid API credentials.
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

		// Make sure we have an API key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		} // Make sure we have an access token.
		elseif ( ! isset( $fields['access_token'] ) || empty( $fields['access_token'] ) ) {
			$response['error'] = __( 'Error: You must provide an access token.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$url     = $this->api_url . 'lists?api_key=' . $fields['api_key'] . '&access_token=' . $fields['access_token'];
			$request = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );

			if ( ! is_array( $request ) || ( isset( $request[0] ) && isset( $request[0]->error_message ) ) ) {
				/* translators: %s Error Message */
				$response['error'] = sprintf( __( 'Error: Could not connect to Constant Contact. %s', 'convertpro-addon' ), $request[0]->error_message );
			} else {
				$response['data'] = array(
					'api_key'      => $fields['api_key'],
					'access_token' => $fields['access_token'],
				);
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
			'api_key', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your Constant Contact API key.', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'access_token', array(
				'class'       => 'cpro-input',
				'type'        => 'text',
				'label'       => __( 'Access Token', 'convertpro-addon' ),
				'help'        => __( 'Your Constant Contact access token.', 'convertpro-addon' ),
				/* translators: %s Links */
				'description' => sprintf( __( 'You must register a <a%1$s rel="noopener">Developer Account</a> with Constant Contact to obtain an API key and access token. Please refer to: <a%1$s rel="noopener">Getting an API key</a> for further instructions.', 'convertpro-addon' ), ' href="https://constantcontact.mashery.com/member/register" target="_blank"', ' href="https://developer.constantcontact.com/home/api-keys.html" target="_blank"' ),
			)
		);
		return ob_get_clean();
	}

	/**
	 * Returns the api_key and access_token in array format
	 *
	 * @since 1.0.0
	 * @param @type string $auth_meta A valid API credentials.
	 * @return array Array of api_key
	 */
	public function render_auth_meta( $auth_meta ) {
		return array(
			'api_key'      => $auth_meta['api_key'],
			'access_token' => $auth_meta['access_token'],
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
		$api_key      = $account_data['api_key'];
		$access_token = $account_data['access_token'];

		$url = $this->api_url . 'lists?api_key=' . $api_key . '&access_token=' . $access_token;

		$request  = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( ! is_array( $request ) || ( isset( $request[0] ) && isset( $request[0]->error_message ) ) ) {
			/* translators: %s Error Message */
			$response['error'] = sprintf( __( 'Error: Could not connect to Constant Contact. %s', 'convertpro-addon' ), $request[0]->error_message );
		} else {
			$response['html'] = $this->render_list_field( $request, $settings );
		}

		return $response;
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
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';
		foreach ( $lists as $list ) {
			$options[ $list->id ] = $list->name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['list_id'] ) ) ? $settings['default']['list_id'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'list_id', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'An email list from a third party provider.', 'convertpro-addon' ),
				'options' => $options,
				'default' => $default,
			), $settings
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
	 * Subscribe an email address to Constant Contact.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @param string $name Optional. The full name of the person subscribing.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email, $name = false ) {

		$account_data = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$response     = array(
			'error' => false,
		);

		$list_id = $settings['list_id'];

		$fields        = array();
		$custom_fields = array();
		$cust_fields   = array();

		foreach ( $settings['param'] as $key => $p ) {
			if ( 'email' != $key && 'date' != $key ) {
				if ( 'custom_field' != $settings['meta'][ $key ] ) {
					$fields[ $settings['meta'][ $key ] ] = $p;
				} else {
					$fields[ $settings['meta'][ $key . '-input' ] ] = $p;
					$custom_fields                                  = array(
						'name'  => $settings['meta'][ $key . '-input' ],
						'value' => $p,
					);
					array_push( $cust_fields, $custom_fields );
				}
			}
		}

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Constant Contact! The account is no longer connected.', 'convertpro-addon' );
		} else {
			$api_key      = $account_data['api_key'];
			$access_token = $account_data['access_token'];
			$url          = $this->api_url . 'contacts?api_key=' . $api_key . '&access_token=' . $access_token . '&email=' . $email;
			$request      = wp_remote_get( $url );
			$contact      = json_decode( wp_remote_retrieve_body( $request ) );

			// This contact exists.
			if ( ! empty( $contact->results ) ) {

				$args = array();
				$data = $contact->results[0];

				// Check if already subscribed to this list.
				if ( ! empty( $data->lists ) ) {

					// Return early if already added.
					foreach ( $data->lists as $key => $list ) {
						if ( isset( $list->id ) && $list_id == $list->id ) {
							return $response;
						}
					}

					// Add an existing contact to the list.
					$new_list                             = new stdClass;
					$new_list->id                         = $list_id;
					$new_list->status                     = 'ACTIVE';
					$data->lists[ count( $data->lists ) ] = $new_list;
				} else {

					// Add an existing contact that has no list.
					$data->lists      = array();
					$new_list         = new stdClass;
					$new_list->id     = $list_id;
					$new_list->status = 'ACTIVE';
					$data->lists[0]   = $new_list;
				}

				$args['body']                      = json_encode( $data );
				$args['method']                    = 'PUT';
				$args['headers']['Content-Type']   = 'application/json';
				$args['headers']['Content-Length'] = strlen( $args['body'] );
				$url                               = $this->api_url . 'contacts/' . $contact->results[0]->id . '?api_key=' . $api_key . '&access_token=' . $access_token . '&action_by=ACTION_BY_VISITOR';
				$update                            = wp_remote_request( $url, $args );
				$res                               = json_decode( wp_remote_retrieve_body( $update ) );

				if ( isset( $res->error_key ) ) {
					/* translators: %s Error Message */
					$response['error'] = sprintf( __( 'There was an error subscribing to Constant Contact! %s', 'convertpro-addon' ), $res->error_key );
				}
			} // Add a new contact.
			else {

				$args                                 = array();
				$data                                 = array();
				$data['email_addresses']              = array();
				$data['email_addresses'][0]['id']     = $list_id;
				$data['email_addresses'][0]['status'] = 'ACTIVE';
				$data['email_addresses'][0]['confirm_status'] = 'CONFIRMED';
				$data['email_addresses'][0]['email_address']  = $email;
				$data['lists']                                = array();
				$data['lists'][0]['id']                       = $list_id;

				// Map fields and custom fields.
				$default_fields = self::$mapping_fields;
				foreach ( $default_fields as $val ) {

					if ( isset( $fields[ $val ] ) ) {

						$data[ $val ] = $fields[ $val ];
					}
				}

				if ( ! empty( $cust_fields ) ) {
					$data['custom_fields'] = array();
					foreach ( $cust_fields as $key => $field_val ) {

						$data['custom_fields'][ $key ]['name']  = $field_val['name'];
						$data['custom_fields'][ $key ]['value'] = $field_val['value'];
					}
				}

				$args['body']                      = json_encode( $data );
				$args['headers']['Content-Type']   = 'application/json';
				$args['headers']['Content-Length'] = strlen( json_encode( $data ) );
				$url                               = $this->api_url . 'contacts?api_key=' . $api_key . '&access_token=' . $access_token . '&action_by=ACTION_BY_VISITOR';
				$create                            = wp_remote_post( $url, $args );

				if ( isset( $create->error_key ) ) {
					/* translators: %s Error Message */
					$response['error'] = sprintf( __( 'There was an error subscribing to Constant Contact! %s', 'convertpro-addon' ), $create->error_key );
				}
			}
		}
		return $response;
	}
}
