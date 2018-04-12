<?php
/**
 * Collects leads and subscribe to MailerLite
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the MailerLite API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_MailerLite extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mailerlite';

	/**
	 * The API URL
	 *
	 * @since 1.0.0
	 * @var string $api_url
	 */
	public $api_url = 'https://app.mailerlite.com/api/v2/';


	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailerlite
	 * has already defined. When Mailerlite releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var array $mapping_fields
	 */
	public static $mapping_fields = array( 'name', 'last_name', 'company', 'country', 'city', 'phone', 'state', 'zip' );

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
	 * @param string $api_key A valid API token.
	 * @return object The API instance.
	 */
	public function get_api( $api_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( ! class_exists( 'CPRO_ML_Rest' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/mailerlite/ML_Rest.php';
		}

		$this->api_instance = new CPRO_ML_Rest( $api_key );
		$this->api_instance->setUrl( $this->api_url );

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields A valid API credenetials.
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

		// Make sure we have an API token.
		if ( ! isset( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API token.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields['api_key'] );
			$api->setPath( 'groups' );
			$api->getAll();
			$get_api_response = $api->getResponseInfo();

			if ( 200 === $get_api_response['http_code'] ) {
				$response['data'] = array(
					'api_key' => $fields['api_key'],
				);
			} else {
				/* translators: %s Error code */
				$response['error'] = sprintf( __( 'Error: Could not connect to MailerLite. %s', 'convertpro-addon' ), $get_api_response['http_code'] );
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
				'help'  => __( 'Your API key can be found in your MailerLite account under Integrations > Developer API.', 'convertpro-addon' ),
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
		);
	}

	/**
	 * Renders the authentication details for the service.
	 *
	 * @param string $account account name.
	 * @since 1.0.0
	 * @return array The connection settings markup.
	 */
	public function get_account_data( $account ) {
		return unserialize( $account[ CP_API_CONNECTION_SERVICE_AUTH ][0] );
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
		$account_data = $this->get_account_data( $account );
		$api          = $this->get_api( $account_data['api_key'] );
		$api->setPath( 'groups' );
		$get_lists = json_decode( $api->getAll() );
		$lists     = array();

		if ( ! ( isset( $get_lists->error ) ) && $get_lists && count( $get_lists ) > 0 ) {
			$lists    = $get_lists;
			$response = array(
				'error'          => false,
				'html'           => $this->render_list_field( $lists, $settings ),
				'mapping_fields' => self::$mapping_fields,
			);
		} elseif ( isset( $get_lists->error ) ) {
			$response = array(
				'error'          => $get_lists->error->message,
				'html'           => '',
				'mapping_fields' => self::$mapping_fields,
			);
		} else {
			$response = array(
				'error'          => __( 'No lists found.', 'convertpro-addon' ),
				'html'           => '',
				'mapping_fields' => self::$mapping_fields,
			);
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
		$default = '';
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		if ( $lists ) {
			foreach ( $lists as $list ) {
				$options[ $list->id ] = $list->name;
			}
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
	 * Subscribe an email address to Drip.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @param string $name Optional. The full name of the person subscribing.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email, $name = '' ) {

		$account_data = $this->get_account_data( $settings['api_connection'] );
		$response     = array(
			'error'  => false,
			'status' => 'success',
		);

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to MailerLite! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api = $this->get_api( $account_data['api_key'] );

			$data['email'] = $email;
			$def_fields    = array();

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$def_fields[ $settings['meta'][ $key ] ] = $p;
					} else {
						$def_fields[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			if ( ! empty( $def_fields ) ) {

				$data['fields'] = $def_fields;
			}

			$api->setPath( 'groups/' . $settings['list_id'] . '/subscribers' );
			$api->add( $data );
			$result = $api->getResponseInfo();

			if ( 200 !== $result['http_code'] ) {
				/* translators: %s Error Code */
				$response['error'] = sprintf( __( 'There was an error subscribing to MailerLite! Code: %s', 'convertpro-addon' ), $result['http_code'] );
			}
		}

		return $response;
	}
}
