<?php
/**
 * ConverPlug Service MailJet
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the MailJet API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_MailJet extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mailjet';


	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		require_once CP_SERVICES_BASE_DIR . 'includes/vendor/mailjet/api/cpmj-mailjet-api-v3.php';
		require_once CP_SERVICES_BASE_DIR . 'includes/vendor/mailjet/cpmj-api-class.php';
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailjet
	 * has already defined. When Mailjet releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'firstname', 'name', 'country' );

	/**
	 * API instance
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
	 * @param string $api_key A valid API key.
	 * @param string $secret_key A valid API token.
	 * @return object The API instance.
	 */
	public function get_api( $api_key, $secret_key ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		$this->api_instance = new CPRO_WP_Mailjet_Api( $api_key, $secret_key );

		return $this->api_instance;
	}

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
		elseif ( ! isset( $fields['secret_key'] ) || empty( $fields['secret_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a secret key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields['api_key'], $fields['secret_key'] );

			$resp = $api->getContactLists(
				array(
					'limit' => 0,
				)
			);
			$resp = json_decode( json_encode( $resp ), true );

			if ( isset( $resp['Status'] ) ) {

				if ( 'ERROR' == $resp['Status'] ) {
					$lists = false;
				}
			} else {
				$lists = $resp;
			}

			if ( false !== $lists ) {

				if ( count( $lists ) == 0 ) {
					$response['error'] = __( 'Error: Could not connect to Mailjet!', 'convertpro-addon' );
				}
				$response['data'] = array(
					'api_key'    => $fields['api_key'],
					'secret_key' => $fields['secret_key'],
				);
			} else {
				$response['error'] = __( 'Error: Could not connect to Mailjet!', 'convertpro-addon' );
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
				'help'  => __( 'Your MailJet API key', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'secret_key', array(
				'class'       => 'cpro-input',
				'type'        => 'text',
				'label'       => __( 'Secret Key', 'convertpro-addon' ),
				'help'        => __( 'Your Mailjet secret key', 'convertpro-addon' ),
				/* translators: %s link */
				'description' => sprintf( __( 'Please refer to the following article for more details: <a%1$s rel="noopener">Getting an API key</a>', 'convertpro-addon' ), ' href="https://app.mailjet.com/account/api_keys" target="_blank"' ),
			)
		);
		return ob_get_clean();
	}

	/**
	 * Returns the api_key and secret_key in array format
	 *
	 * @since 1.0.0
	 * @param @type string $auth_meta A valid API credentials.
	 * @return array Array of api_key
	 */
	public function render_auth_meta( $auth_meta ) {
		return array(
			'api_key'    => $auth_meta['api_key'],
			'secret_key' => $auth_meta['secret_key'],
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
		$secret_key   = $account_data['secret_key'];

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$api = $this->get_api( $api_key, $secret_key );

		$resp = $api->getContactLists(
			array(
				'limit' => 0,
			)
		);
		$resp = json_decode( json_encode( $resp ), true );

		if ( isset( $resp['Status'] ) ) {

			if ( 'ERROR' == $resp['Status'] ) {
				$lists             = false;
				$response['error'] = __( 'Error: Could not connect to Mailjet!', 'convertpro-addon' );
			}
		} else {

			$lists = $resp;
		}

		if ( count( $lists ) == 0 ) {
			$response['error'] = __( 'No lists found in your Mailjet account.', 'convertpro-addon' );
		}
		$response['html'] = $this->render_list_field( $lists, $settings );
		$response['data'] = array(
			'api_key'    => $account_data['api_key'],
			'secret_key' => $account_data['secret_key'],
		);
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
			$options[ $list['value'] ] = $list['label'];
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
	 * Subscribe an email address to Mailjet.
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

		$custom_fields = array();
		$cust_fields   = array();

		$data = array(
			'ListID' => $settings['list_id'],
			'Email'  => $email,
		);

		foreach ( $settings['param'] as $key => $p ) {

			if ( 'email' != $key && 'date' != $key ) {
				if ( 'custom_field' != $settings['meta'][ $key ] ) {

					$custom_fields = array(
						'Name'  => $settings['meta'][ $key ],
						'Value' => $p,
					);

				} else {
					$custom_fields = array(
						'Name'  => $settings['meta'][ $key . '-input' ],
						'Value' => $p,
					);
				}
				array_push( $cust_fields, $custom_fields );
			}
		}

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Mailjet! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api_key    = $account_data['api_key'];
			$secret_key = $account_data['secret_key'];

			$api = $this->get_api( $api_key, $secret_key );

			$result = $api->addContact( $data );
			// @codingStandardsIgnoreStart
			if ( isset( $result->Status ) && ( 'OK' == $result->Status || 'DUPLICATE' == $result->Status ) ) {
			// @codingStandardsIgnoreEnd

				if ( is_array( $cust_fields ) ) {

					$res = $api->updateContactData(
						array(
							'method' => 'JSON',
							'ID'     => $email,
							'Data'   => $cust_fields,
						)
					);
				}
			} else {

				$response['error'] = __( 'There was an error subscribing to Mailjet!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
