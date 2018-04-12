<?php
/**
 * Collects leads and subscribe to GetResponse
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the GetResponse API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_GetResponse extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'getresponse';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailchimp
	 * has already defined. When Mailchimp releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'Name', 'age', 'birthdate', 'city', 'comment', 'company', 'country', 'fax', 'gender', 'home_phone', 'http_referer', 'mobile_phone', 'phone', 'postal_code', 'ref', 'state', 'street', 'url', 'work_phone' );

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
	 * @param string $api_key A valid API key.
	 * @return object The API instance.
	 */
	public function get_api( $api_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( ! class_exists( 'CPRO_GetResponse' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/getresponse/getresponse.php';
		}

		$this->api_instance = new CPRO_GetResponse( $api_key );

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
		} // Try to connect and store the connection data.
		else {

			$api  = $this->get_api( $fields['api_key'] );
			$ping = $api->ping();

			if ( ! $ping ) {
				$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
			} else {
				$response['data'] = array(
					'api_key' => $fields['api_key'],
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
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your GetResponse API key can be found in your GetResponse My Account section. > GetResponse API.', 'convertpro-addon' ),
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
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param string $account The name of the saved account.
	 * @param object $post_data Posted data.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 *      @type array $mapping_fields The field mapping array for mailchimp.
	 * }
	 */
	public function render_fields( $account, $post_data ) {
		$account_data = ConvertPlugServices::get_account_data( $account );
		$api          = $this->get_api( $account_data['api_key'] );
		$lists        = $api->getCampaigns();
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( ! $lists ) {
			$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
		} else {
			$response['html'] = $this->render_list_field( $lists, $post_data );
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
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';

		foreach ( $lists as $id => $data ) {
			$options[ $id ] = $data->name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['getresponse_list'] ) ) ? $settings['default']['getresponse_list'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'getresponse_list', array(
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
	 * Subscribe an email address to MailChimp.
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

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to GetResponse! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api = $this->get_api( $account_data['api_key'] );

			try {

				$name   = '';
				$custom = array();

				foreach ( $settings['param'] as $key => $p ) {
					if ( 'email' != $key && 'date' != $key ) {
						if ( 'Name' == $settings['meta'][ $key ] ) {
							$name = $p;
						} elseif ( 'custom_field' != $settings['meta'][ $key ] ) {
							$custom[ $settings['meta'][ $key ] ] = $p;
						} else {
							$custom[ $settings['meta'][ $key . '-input' ] ] = $p;
						}
					}
				}

				$result = $api->addContact( $settings['getresponse_list'], $name, $email, 'standard', 0, $custom );
			} catch ( Exception $e ) {
				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to GetResponse. %s', 'convertpro-addon' ),
					$e->getMessage()
				);
			}
		}

		return $response;
	}
}
