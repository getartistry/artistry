<?php
/**
 * Collects leads and subscribe to VerticalResponse
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the VerticalResponse API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_VerticalResponse extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'verticalresponse';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that VerticalResponse
	 * has already defined. When VerticalResponse releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array(
		'first_name',
		'last_name',
		'birthdate',
		'gender',
		'marital_status',
		'company',
		'title',
		'website',
		'street_address',
		'extended_address',
		'city',
		'region',
		'postal_code',
		'country',
		'home_phone',
		'mobile_phone',
		'work_phone',
		'fax',
	);

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
	 */
	public function get_api( $credentials ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( ! class_exists( 'CPRO_VerticalResponseAPI' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/verticalresponse/verticalresponse_api.php';
		}

		$this->api_instance = new CPRO_VerticalResponseAPI( $credentials );

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

		// Make sure we have an authorization code.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} elseif ( ! isset( $fields['secret_key'] ) || empty( $fields['secret_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a Secret Key.', 'convertpro-addon' );
		} else {
			try {
				$api              = $this->get_api( $fields );
				$response['data'] = $api->connect( $fields, $_POST['currentUrl'] );
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
				'class' => 'verticalresponse_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'API Key.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'secret_key', array(
				'class' => 'verticalresponse_secret_key',
				'type'  => 'text',
				'label' => __( 'Secret Key', 'convertpro-addon' ),
				'help'  => __( 'Secret Key.', 'convertpro-addon' ),
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
			'public_key' => $auth_meta['api_key'],
			'secret_key' => $auth_meta['secret_key'],
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
	 *      @type array $mapping_fields The field mapping array for VerticalResponse.
	 * }
	 */
	public function render_fields( $account, $post_data ) {

		$account_data = ConvertPlugServices::get_account_data( $account );

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		try {

			$api              = $this->get_api( $account_data );
			$lists            = $api->getLists();
			$response['html'] = $this->render_list_field( $lists['items'], $post_data );

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

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['verticalresponse_list'] ) ) ? $settings['default']['verticalresponse_list'] : '' ) : '';
		}

		foreach ( $lists as $list ) {
			$id             = (string) $list->attributes->id;
			$options[ $id ] = $list->attributes->name;
		}
		ob_start();

		ConvertPlugHelper::render_input_html(
			'verticalresponse_list', array(
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
	 * Subscribe an email address to VerticalResponse.
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
			$response['error'] = __( 'There was an error subscribing to VerticalResponse! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$data = array(
				'email' => $email,
			);

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$data[ $settings['meta'][ $key ] ] = $p;
					} else {
						$data[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			$verticalresponse_list = $settings['verticalresponse_list'];

			try {

				$api      = $this->get_api( $account_data );
				$response = $api->subscribe( $verticalresponse_list, $data );

			} catch ( Exception $e ) {
				$response['error'] = $e->getMessage();
			}
		}

		return $response;
	}
}
