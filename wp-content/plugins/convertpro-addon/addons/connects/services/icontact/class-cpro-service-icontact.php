<?php
/**
 * Collects leads and subscribe to iContact
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the iContact API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_IContact extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'icontact';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'prefix', 'firstName', 'lastName', 'suffix', 'street', 'street2', 'city', 'state', 'postalCode', 'business', 'phone', 'fax' );

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
	 * @param array $data {.
	 *      @type string $username A valid username.
	 *      @type string $app_id A valid app ID.
	 *      @type string $app_password A valid app password.
	 * }
	 * @return object The API instance.
	 */
	public function get_api( $data ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/icontact/iContactApi.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/icontact/iContactApi.php';
		}

		if ( class_exists( 'CPRO_iContactApi' ) ) {
			CPRO_iContactApi::getInstance()->setConfig( $data );
			$this->api_instance = CPRO_iContactApi::getInstance();
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type string $username A valid username.
	 *      @type string $app_id A valid App ID.
	 *      @type string $app_password A valid App Password.
	 * }
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

		// Make sure we have a username.
		if ( ! isset( $fields['username'] ) || empty( $fields['username'] ) ) {
			$response['error'] = __( 'Error: You must provide a Username.', 'convertpro-addon' );
		} // Make sure we have an app ID.
		elseif ( ! isset( $fields['app_id'] ) || empty( $fields['app_id'] ) ) {
			$response['error'] = __( 'Error: You must provide an App ID.', 'convertpro-addon' );
		} // Make sure we have an app password.
		elseif ( ! isset( $fields['app_password'] ) || empty( $fields['app_password'] ) ) {
			$response['error'] = __( 'Error: You must provide an App Password.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api(
				array(
					'apiUsername' => $fields['username'],
					'appId'       => $fields['app_id'],
					'apiPassword' => $fields['app_password'],
				)
			);

			try {
				$api->getLists();
				$response['data'] = array(
					'username'     => $fields['username'],
					'app_id'       => $fields['app_id'],
					'app_password' => $fields['app_password'],
				);
			} catch ( Exception $e ) {
				$errors = $api->getErrors();
				/* translators: %s Error Message */
				$response['error'] = sprintf( __( 'Error: Could not connect to iContact. %s', 'convertpro-addon' ), $errors[0] );
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
			'username', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'Username', 'convertpro-addon' ),
				'help'  => __( 'Your iContact username', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'app_id', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'App ID', 'convertpro-addon' ),
				'help'  => __( 'Your iContact App ID', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'app_password', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'App Password', 'convertpro-addon' ),
				'help'  => __( 'Your iContact App Password', 'convertpro-addon' ),
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
			'username'     => $auth_meta['username'],
			'app_id'       => $auth_meta['app_id'],
			'app_password' => $auth_meta['app_password'],
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
		$api          = $this->get_api(
			array(
				'apiUsername' => $account_data['username'],
				'appId'       => $account_data['app_id'],
				'apiPassword' => $account_data['app_password'],

			)
		);
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		try {
			$lists = $api->getLists();
			if ( ! isset( $_POST['seg_list_id'] ) ) {
				$response['html'] = $this->render_list_field( $lists, $settings );
			}
		} catch ( Exception $e ) {
			$errors = $api->getErrors();
			/* translators: %s Error Message */
			$response['error'] = sprintf( __( 'Error: Could not connect to iContact. %s', 'convertpro-addon' ), $errors[0] );
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

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['icontact_list_id'] ) ) ? $settings['default']['icontact_list_id'] : '' ) : '';
		}
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists as $id => $list ) {
			// @codingStandardsIgnoreStart
			$options[ $list->listId ] = $list->name;
			// @codingStandardsIgnoreEnd
		}

		ConvertPlugHelper::render_input_html(
			'icontact_list_id', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'An email list from a third party provider.', 'convertpro-addon' ),
				'default' => $default,
				'options' => $options,
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
	 * Subscribe an email address to iContact.
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
			$response['error'] = __( 'There was an error subscribing to iContact! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$data = array(
				'email' => $email,
			);
			$api  = $this->get_api(
				array(
					'apiUsername' => $account_data['username'],
					'appId'       => $account_data['app_id'],
					'apiPassword' => $account_data['app_password'],
				)
			);

			try {

				$merge_arr  = array();
				$custom_arr = array();
				foreach ( $settings['param'] as $key => $p ) {
					if ( 'email' != $key && 'date' != $key ) {
						if ( 'custom_field' == $settings['meta'][ $key ] ) {
							$custom_field_key          = $settings['meta'][ $key . '-input' ];
							$custom_field_value        = $p;
							$data[ $custom_field_key ] = $custom_field_value;
						} else {
							$data[ $settings['meta'][ $key ] ] = $p;
						}
					}
				}
				if ( ! empty( $data ) ) {
					$result = $api->addContact( $data, 'normal' );
				}
				// subcribe and add to Selected list.
				if ( isset( $settings['icontact_list_id'] ) ) {
					// @codingStandardsIgnoreStart
					$api->subscribeContactToList( $result->contactId, $settings['icontact_list_id'] );
					// @codingStandardsIgnoreEnd
				}
			} catch ( Exception $e ) {
				$errors = $api->getErrors();
				/* translators: %s Error Message */
				$response['error'] = sprintf( __( 'There was an error subscribing to iContact! %s', 'convertpro-addon' ), $errors[0] );
			}
		}
		return $response;
	}
}
