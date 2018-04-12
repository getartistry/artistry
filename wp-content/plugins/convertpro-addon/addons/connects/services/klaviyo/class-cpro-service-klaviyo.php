<?php
/**
 * Collects leads and subscribe to Klaviyo
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Klaviyo API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Klaviyo extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'klaviyo';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Klaviyo
	 * has already defined. When Klaviyo releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'first_name', 'last_name', 'phone_number', 'title', 'organization', 'city', 'country', 'zip' );

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

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/klaviyo/klaviyo.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/klaviyo/klaviyo.php';
		}

		if ( class_exists( 'CPRO_Klaviyo' ) ) {
			$this->api_instance = new CPRO_Klaviyo( $api_key );
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
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields['api_key'] );

			try {
				$connected = $api->connect();

				if ( false != $connected['error'] ) {
					$response['error'] = $connected['error'];
				}

				$response['data'] = array(
					'api_key' => $fields['api_key'],
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
				'help'  => __( 'Your API Key can be found in your Klaviyo account under Account > Settings > API Keys.', 'convertpro-addon' ),
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
	 *      @type array $mapping_fields The field mapping array for klaviyo.
	 * }
	 */
	public function render_fields( $account, $post_data ) {
		$account_data = ConvertPlugServices::get_account_data( $account );

		$api                 = $this->get_api( $account_data['api_key'] );
		$response            = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$post_data['isEdit'] = ( isset( $post_data['isEdit'] ) ) ? $post_data['isEdit'] : null;
		// Lists field.
		try {
			$resp = $api->getList();

			if ( false == $resp['error'] ) {

				if ( ! empty( $resp['lists'] ) ) {

					$lists             = $resp['lists'];
					$response['html'] .= $this->render_list_field( $lists, $post_data );

					$response['html'] .= $this->render_optin_field( $post_data );

				} else {
					$response['error'] = __( 'No list added yet.', 'convertpro-addon' );
				}
			} else {
				$response['error'] = $resp['error'];
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

		$list_options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default      = '';

		foreach ( $lists as $list ) {
			$list_options[ $list->id ] = $list->name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['klaviyo_list'] ) ) ? $settings['default']['klaviyo_list'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'klaviyo_list', array(
				'class'   => '',
				'type'    => 'select',
				'label'   => __( 'Select a List', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $list_options,
			)
		);

		return ob_get_clean();

	}

	/**
	 * Render markup for the optin field.
	 *
	 * @since 1.0.0
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_optin_field( $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['klaviyo_double_optin'] ) ) ? $settings['default']['klaviyo_double_optin'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'klaviyo_double_optin', array(
				'class'   => '',
				'type'    => 'checkbox',
				'label'   => __( 'Enable Double Opt-in', 'convertpro-addon' ),
				'help'    => '',
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
	 * Subscribe an email address to Klaviyo.
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
			$response['error'] = __( 'There was an error subscribing to Klaviyo! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api = $this->get_api( $account_data['api_key'] );

			$data               = array();
			$data['properties'] = array();
			$fields             = array();
			$custom_fields      = array();
			$cust_fields        = array();

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

					$data['properties'][ $val ] = $fields[ $val ];
				}
			}

			if ( ! empty( $cust_fields ) ) {

				foreach ( $cust_fields as $key => $field_val ) {

					$data['properties'][ $field_val['name'] ] = $field_val['value'];
				}
			}

			$data['email']         = $email;
			$data['confirm_optin'] = ( isset( $settings['klaviyo_double_optin'] ) ) ? 'true' : 'false';

			// Subscribe.
			try {
				$response = $api->subscribe( $settings['klaviyo_list'], $email, $data );
			} catch ( Exception $e ) {
				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to Klaviyo! %s', 'convertpro-addon' ),
					$e->getMessage()
				);
			}
		}

		return $response;
	}
}
