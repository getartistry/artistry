<?php
/**
 * Collects leads and subscribe to Mailgun
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Mailgun API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Mailgun extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mailgun';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailchimp
	 * has already defined. When Mailchimp releases the new
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
	 * @param string $credentials A valid API key.
	 * @return object The API instance.
	 * @throws Exception Exeption handling.
	 */
	public function get_api( $credentials ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/mailgun/class-cpro-mailgun-api.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/mailgun/class-cpro-mailgun-api.php';
		} else {
			throw new Exception( __( 'Something went wrong with Mailgun API files', 'convertpro-addon' ) );
		}

		if ( class_exists( 'CPRO_Mailgun_API' ) ) {
			$this->api_instance = new CPRO_Mailgun_API( $credentials );
		} else {
			throw new Exception( __( 'Something went wrong with Mailgun API files', 'convertpro-addon' ) );
		}

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
		$response = array(
			'error' => false,
			'data'  => array(),
		);

		// Make sure we have an API key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		} elseif ( ! isset( $fields['domain'] ) || empty( $fields['domain'] ) ) {
			$response['error'] = __( 'Error: You must provide a Domain URL.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			try {

				$api = $this->get_api( $fields );
				// Issue the call to the client.
				$connected = $api->connect();

				if ( false != $connected['error'] ) {
					$response['error'] = $connected['error'];
				}

				$response['data'] = array(
					'api_key' => $fields['api_key'],
					'domain'  => $fields['domain'],
				);
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
			'api_key', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API key can be found in your Mailgun account under Account > Extras > API Keys.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'domain', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Domain URL', 'convertpro-addon' ),
				'help'  => __( 'Your API key can be found in your Mailgun account under Account > Extras > API Keys.', 'convertpro-addon' ),
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
			'api_key' => $auth_meta['api_key'],
			'domain'  => $auth_meta['domain'],
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
	 *      @type array $mapping_fields The field mapping array for mailgun.
	 * }
	 * @throws Exception Exeption handling.
	 */
	public function render_fields( $account, $post_data ) {

		$account_data        = ConvertPlugServices::get_account_data( $account );
		$response            = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$post_data['isEdit'] = ( isset( $post_data['isEdit'] ) ) ? $post_data['isEdit'] : null;
		// Lists field.
		try {

			$api  = $this->get_api( $account_data );
			$resp = $api->getLists();

			if ( false == $resp['error'] ) {

				if ( ! empty( $resp['lists'] ) ) {

					$lists = $resp['lists'];
					if ( isset( $lists['items'] ) && ! empty( $lists['items'] ) ) {
						$response['html'] .= $this->render_list_field( $lists, $post_data );
					} else {
						throw new Exception( __( 'No list added yet.', 'convertpro-addon' ) );
					}
				} else {
					throw new Exception( __( 'No list added yet.', 'convertpro-addon' ) );
				}
			} else {
				throw new Exception( $resp['error'] );
			}
		} catch ( Exception $e ) {
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

		foreach ( $lists['items'] as $list ) {
			$options[ $list['address'] ] = $list['name'];
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mailgun_list'] ) ) ? $settings['default']['mailgun_list'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'mailgun_list', array(
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
	 * Mapping fields.
	 *
	 * @since 1.0.0
	 */
	public function render_mapping() {
		return self::$mapping_fields;
	}

	/**
	 * Subscribe an email address to Mailgun.
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

		$response = array(
			'error' => false,
		);

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Mailgun! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$data          = array();
			$fields        = array();
			$custom_fields = array();
			$cust_fields   = array();

			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( isset( $settings['meta'][ $key ] ) ) {
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
			}

			// Map fields and custom fields.
			$default_fields = self::$mapping_fields;
			foreach ( $default_fields as $val ) {
				if ( isset( $fields[ $val ] ) ) {
					$data[ $val ] = $fields[ $val ];
				}
			}

			if ( ! empty( $cust_fields ) ) {
				foreach ( $cust_fields as $key => $field_val ) {
					$data[ $field_val['name'] ] = $field_val['value'];
				}
			}

			// Subscribe.
			try {

				$api      = $this->get_api( $account_data );
				$response = $api->subscribe( $settings['mailgun_list'], $email, $data );

			} catch ( Exception $e ) {

				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to Mailgun! %s', 'convertpro-addon' ),
					$e->getMessage()
				);
			}
		}

		return $response;
	}
}
