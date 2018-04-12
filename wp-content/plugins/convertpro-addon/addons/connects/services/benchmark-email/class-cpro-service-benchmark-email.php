<?php
/**
 * Collects leads and subscribe to Benchmark Email
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Benchmark Email API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Benchmark_Email extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'benchmark-email';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'FIRSTNAME', 'MIDDLENAME', 'LASTNAME' );

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
	 * @param string $api_key A valid API Key.
	 * @return object The API instance.
	 */
	public function get_api( $api_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/benchmark-email/api.class.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/benchmark-email/api.class.php';
		}

		if ( class_exists( 'CPPro_Benchmark_API_Class' ) ) {
			$this->api_instance = new CPPro_Benchmark_API_Class( $api_key );
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type string $api_key A valid API Key.
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

		// Make sure we have an API Key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			try {
				$api = $this->get_api( $fields['api_key'] );
				if ( ! empty( $api ) ) {
					$campaigns = $api->getLists();
					if ( empty( $campaigns['status'] ) ) {
						$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
					} else {
						$response['data'] = array(
							'api_key' => $fields['api_key'],
						);
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
			'api_key', array(
				'class' => 'cp_benchmark_email_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found under Account Settings in your Benchmark Email account.', 'convertpro-addon' ),
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
	 * @param object $settings Saved module settings.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 * }
	 */
	public function render_fields( $account, $settings ) {
		$post_data    = ConvertPlugHelper::get_post_data();
		$account_data = ConvertPlugServices::get_account_data( $account );
		$api          = $this->get_api( $account_data['api_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$lists = $api->getLists();

		if ( true == $lists['status'] ) {
			if ( ! empty( $lists['result'] ) ) {
				$response['html'] = $this->render_list_field( $lists, $settings );
			} else {
				$response['error'] = __( 'No lists created yet! Please login to your Benchmark Email account and create a list.', 'convertpro-addon' );
			}
		} else {
			$response['error'] = __( 'No lists created yet! Please login to your Benchmark Email account and create a list.', 'convertpro-addon' );
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
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['benchmark_email_lists'] ) ) ? $settings['default']['benchmark_email_lists'] : '' ) : '';
		}

		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists['result'] as $offset => $cm ) {
			$options[ $cm->id ] = $cm->listname;
		}

			ConvertPlugHelper::render_input_html(
				'benchmark_email_lists', array(
					'class'   => 'cpro-select',
					'type'    => 'select',
					'label'   => _x( 'List', 'An email list from Benchmark Email.', 'convertpro-addon' ),
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
	 * Subscribe an email address to Benchmark Email.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email ) {

		$account  = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$api      = $this->get_api( $account['api_key'] );
		$response = array(
			'error' => false,
		);

		if ( ! $api ) {
			$response['error'] = __( 'There was an error subscribing to Benchmark Email! The account is no longer connected.', 'convertpro-addon' );
		} else {
			$subscriber = array(
				'email' => $email,
			);
			$custom_arr = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] ) {
						$custom_field                = $settings['meta'][ $key . '-input' ];
						$subscriber[ $custom_field ] = $p;
					} else {
						$subscriber[ $settings['meta'][ $key ] ] = $p;
					}
				}
			}
			// Add contact to list.
			$result = $api->listAddContacts( $subscriber, $settings['benchmark_email_lists'], 1 );

			if (
				! is_object( $result['result'] )
				&& (
					1 != $result['result']
					|| 1 != $result['status']
					)
			) {
				$response['error'] = __( 'There was an error subscribing to Benchmark Email!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
