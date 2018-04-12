<?php
/**
 * ConverPlug Service Total Send
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Total Send API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_TotalSend extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'totalsend';

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		if ( ! class_exists( 'CPPro_TotalSendPHP' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/totalsend/TotalSendPHP.php';
		}
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that TotalSend
	 * has already defined. When TotalSend releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array();

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
		if ( ! isset( $fields['api_uname'] ) || empty( $fields['api_uname'] ) ) {
			$response['error'] = __( 'Error: You must provide an API User Name.', 'convertpro-addon' );
		} // Make sure we have an access token.
		elseif ( ! isset( $fields['api_pass'] ) || empty( $fields['api_pass'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Password.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$auth['api_user_name'] = $fields['api_uname'];
			$auth['api_password']  = $fields['api_pass'];

			$totalsend_obj = new CPPro_TotalSendPHP( $auth );
			$lists         = $totalsend_obj->cptsGetConnect();

			if ( false == $lists ) {
				$response['error'] = __( 'Access denied: Invalid credentials (Username and/or Password key).', 'convertpro-addon' );
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
			'api_uname', array(
				'class' => 'cpro-input',
				'type'  => 'text',
				'label' => __( 'API Username', 'convertpro-addon' ),
				'help'  => __( 'Your TotalSend API Username', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'api_pass', array(
				'class'       => 'cpro-input',
				'type'        => 'text',
				'label'       => __( 'API Password', 'convertpro-addon' ),
				'help'        => __( 'Your TotalSend API Password', 'convertpro-addon' ),
				/* translators: %s: Link */
				'description' => sprintf( __( 'Please see <a%1$s rel="noopener">Getting an API details</a> for complete instructions.', 'convertpro-addon' ), ' href="https://app.totalsend.com/app/user/integration/wordpress/" target="_blank"' ),
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
			'api_uname' => $auth_meta['api_uname'],
			'api_pass'  => $auth_meta['api_pass'],
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

		$auth['api_user_name'] = $account_data['api_uname'];
		$auth['api_password']  = $account_data['api_pass'];

		$totalsend_obj = new CPPro_TotalSendPHP( $auth );
		$lists         = $totalsend_obj->cptsGetConnect();

		$response         = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$response['html'] = $this->render_list_field( $lists, $settings );
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
		foreach ( $lists['Lists'] as $list ) {

			$options[ $list['ListID'] ] = $list['Name'];
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
	 * Subscribe an email address to TotalSend.
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

		$data = array(
			'ListID'       => $settings['list_id'],
			'EmailAddress' => $email,
		);

		$auth['api_user_name'] = $account_data['api_uname'];
		$auth['api_password']  = $account_data['api_pass'];

		$fields        = array();
		$custom_fields = array();
		$cust_fields   = array();

		$totalsend_obj      = new CPPro_TotalSendPHP( $auth );
		$customfields_param = $totalsend_obj->getCustomField( $list_id );
		$customfields_param = json_decode( $customfields_param, true );

		foreach ( $settings['param'] as $key => $p ) {

			if ( 'custom_field' == $settings['meta'][ $key ] ) {

				if ( 'email' != $key && 'user_id' != $key && 'date' != $key ) {

					if ( false != $customfields_param['CustomFields'] ) {

						foreach ( $customfields_param['CustomFields'] as $offset => $c_field ) {

							if ( $c_field['FieldName'] == $key || $c_field['FieldName'] == $settings['meta'][ $key . '-input' ] ) {
								$data[ 'CustomField' . $c_field['CustomFieldID'] ] = $p;
							}
						}
					}
				}
			}
		}
		$result = $totalsend_obj->cptsSubscribe( $data );

		$result = json_decode( $result, true );
		if ( false == $result['Success'] ) {
			if ( 1 == $result['ErrorCode'] ) {
				$response['error'] = __( 'The target subscriber list ID is missing.', 'convertpro-addon' );
			} elseif ( 2 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Email address is missing.', 'convertpro-addon' );
			} elseif ( 3 == $result['ErrorCode'] ) {
				$response['error'] = __( 'The IP address of subscriber is missing.', 'convertpro-addon' );
			} elseif ( 4 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Invalid subscriber list ID', 'convertpro-addon' );
			} elseif ( 5 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Invalid email address', 'convertpro-addon' );
			} elseif ( 6 == $result['ErrorCode'] ) {
				$response['error'] = __( 'One of the provided custom fields is empty. Custom field ID and title is provided as an additional output parameter', 'convertpro-addon' );
			} elseif ( 7 == $result['ErrorCode'] ) {
				$response['error'] = __( 'A provided custom field value already exists in the database.', 'convertpro-addon' );
			} elseif ( 8 == $result['ErrorCode'] ) {
				$response['error'] = __( 'A provided custom field value failed validation test. Custom field ID and title is provided as an additional output parameter', 'convertpro-addon' );
			} elseif ( 9 == $result['ErrorCode'] ) {
				// Already exists. Do not send any error code.
			} elseif ( 10 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Unknown error occurred!', 'convertpro-addon' );
			} elseif ( 11 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Invalid user information!', 'convertpro-addon' );
			} elseif ( 99998 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Authentication failed or session expired!', 'convertpro-addon' );
			} elseif ( 99999 == $result['ErrorCode'] ) {
				$response['error'] = __( 'Not enough privileges', 'convertpro-addon' );
			} else {
				$response['error'] = __( 'There was an error subscribing to TotalSend!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
