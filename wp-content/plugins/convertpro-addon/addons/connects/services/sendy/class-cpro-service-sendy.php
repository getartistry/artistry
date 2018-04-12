<?php
/**
 * ConverPlug Service Sendy
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Sendy API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Sendy extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'sendy';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'NAME' );

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
	 * @param string $sendy_ins_url A valid installation URL.
	 * @param string $sendy_api_key A valid API Key.
	 * @param string $sendy_list_id A valid list id.
	 * @return object The API instance.
	 */
	public function get_api( $sendy_ins_url, $sendy_api_key, $sendy_list_id = null ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		$auth['installation_url'] = $sendy_ins_url;
		$auth['api_key']          = $sendy_api_key;
		$auth['list_id']          = $sendy_list_id;

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/sendy/SendyPHP.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/sendy/SendyPHP.php';
		}

		if ( class_exists( 'CPRO_SendyPHP' ) ) {
			$this->api_instance = new CPRO_SendyPHP( $auth );
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type array $fields authentication fields.
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

		// Make sure we have an installation url.
		if ( ! isset( $fields['sendy_ins_url'] ) || empty( $fields['sendy_ins_url'] ) ) {
			$response['error'] = __( 'Error: You must provide an Installation URL.', 'convertpro-addon' );
		} // Make sure we have an API Key.
		elseif ( ! isset( $fields['sendy_api_key'] ) || empty( $fields['sendy_api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} else {
			$api              = $this->get_api( $fields['sendy_ins_url'], $fields['sendy_api_key'] );
			$result           = $api->connect_sendy();
			$response['data'] = array(
				'installation_url' => $fields['sendy_ins_url'],
				'api_key'          => $fields['sendy_api_key'],
			);
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
			'sendy_ins_url', array(
				'class' => 'cp_sendy_api_url',
				'type'  => 'text',
				'label' => __( 'Installation URL', 'convertpro-addon' ),
				'help'  => __( 'Your base URL.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'sendy_api_key', array(
				'class' => 'cp_sendy_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your Sendy account under Settings.', 'convertpro-addon' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Render markup for the list id field.
	 *
	 * @param object $settings settings data.
	 * @since 1.0.0
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_list_field( $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendy_list_id'] ) ) ? $settings['default']['sendy_list_id'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'sendy_list_id', array(
				'class'   => 'cp_sendy_api_list_id',
				'type'    => 'text-wrap',
				'label'   => __( 'List ID ', 'convertpro-addon' ),
				'default' => $default,
				'help'    => __( 'List ID can be found when you click on \'View all lists\'.', 'convertpro-addon' ),
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
			'api_url' => $authmeta['sendy_ins_url'],
			'api_key' => $authmeta['sendy_api_key'],
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
		$response          = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$response['html'] .= $this->render_list_field( $settings );

		return $response;
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
	 * Subscribe an email address to Sendy.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email ) {

		$account            = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$account['list_id'] = $settings['sendy_list_id'];
		$api                = $this->get_api( $account['api_url'], $account['api_key'], $account['list_id'] );
		$response           = array(
			'error' => false,
		);

		if ( ! $api ) {
			$response['error'] = __( 'There was an error subscribing to Sendy! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$custom_arr = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] ) {
						$custom_field [ $settings['meta'][ $key . '-input' ] ] = $p;
					} else {
						if ( 'NAME' == $settings['meta'][ $key ] ) {
							$name = $p;
						}
					}
				}
			}

			$result = $api->subscribe_sendy( $name, isset( $custom_field ) ? $custom_field : array(), $email );
			if ( 0 == $result['status'] && 'Already subscribed.' != $result['message'] ) {
				$response['error'] = $result['message'];
			}
		}
		return $response;
	}
}
