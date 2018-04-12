<?php
/**
 * ConverPlug Service SimplyCast
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the SendInBlue API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_SendinBlue extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'sendinblue';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'NAME', 'FIRSTNAME', 'SMS' );

	/**
	 * API object.
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
		if ( ! class_exists( 'CPRO_MAILIN' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/sendinblue/Mailin.php';
		}

		$this->api_instance = new CPRO_MAILIN( 'https://api.sendinblue.com/v2.0', $api_key );

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields  A valid API Key.
	 *
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

		// Make sure we have an api key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an Access Key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api    = $this->get_api( $fields['api_key'] );
			$result = $api->get_account();
			if ( ! is_array( $result ) ) {
				$response['error'] = __( 'There was an error connecting to SendinBlue! Please try again.', 'convertpro-addon' );
			} elseif ( isset( $result['code'] ) && 'failure' == $result['code'] ) {
				/* translators: %s Message */
				$response['error'] = sprintf( __( 'Error: Could not connect to SendinBlue. %s', 'convertpro-addon' ), $result['message'] );
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
				'class' => 'cp_sendinblue_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'The API Key can be found under API & Integration in your SendinBlue account. > Manager Your Keys > Version 2.0 > Access Key.', 'convertpro-addon' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @param object $authmeta Authentication meta.
	 * @since 1.0.0
	 * @return string The connection settings markup.
	 */
	public function render_auth_meta( $authmeta ) {
		return array(
			'api_key' => $authmeta['api_key'],
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
		$api          = $this->get_api( $account_data['api_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$result = $api->get_lists( 1, 50 );
		if ( ! is_array( $result ) ) {
			$response['error'] = __( 'There was an error connecting to SendinBlue! Please try again.', 'convertpro-addon' );
		} elseif ( isset( $result['code'] ) && 'failure' == $result['code'] ) {
			/* translators: %s Message */
			$response['error'] = sprintf( __( 'Error: Could not connect to SendinBlue. %s', 'convertpro-addon' ), $result['message'] );
		} else {
			$response['html'] = $this->render_list_field( $result['data']['lists'], $settings );
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
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendinblue_lists'] ) ) ? $settings['default']['sendinblue_lists'] : '' ) : '';
		}

		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists as $list ) {
			$options[ $list['id'] ] = $list['name'];
		}

		ConvertPlugHelper::render_input_html(
			'sendinblue_lists', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'An email list from SendinBlue.', 'convertpro-addon' ),
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
	 * Subscribe an email address to SendInBlue.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @param string $name The name to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email, $name = false ) {

		$account_data = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$response     = array(
			'error' => false,
		);

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to SendinBlue! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api  = $this->get_api( $account_data['api_key'] );
			$data = array();

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
			$result = $api->create_update_user( $email, $subscriber, 0, array( $settings['sendinblue_lists'] ), array(), 0 );

			if ( ! is_array( $result ) ) {
				$response['error'] = __( 'There was an error subscribing to SendinBlue! Please try again.', 'convertpro-addon' );
			} elseif ( isset( $result['code'] ) && 'failure' == $result['code'] ) {
				/* translators: %s Message */
				$response['error'] = sprintf( __( 'Error: Could not subscribe to SendinBlue. %s', 'convertpro-addon' ), $result['message'] );
			}
		}

		return $response;
	}
}
