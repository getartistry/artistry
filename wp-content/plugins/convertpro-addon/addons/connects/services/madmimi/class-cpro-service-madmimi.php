<?php
/**
 * Collects leads and subscribe to MadMimi
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the MailChimp API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_MadMimi extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'madmimi';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailchimp
	 * has already defined. When Mailchimp releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'FirstName', 'LastName', 'Title' );

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
	 * @param string $api_email A valid API Email ID.
	 * @param string $api_key A valid API key.
	 * @return object The API instance.
	 */
	public function get_api( $api_email, $api_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( ! class_exists( 'CPRO_MadMimi' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/madmimi/MadMimi.class.php';
		}

		$this->api_instance = new CPRO_MadMimi( $api_email, $api_key );

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

		// Make sure we have an email address.
		if ( ! isset( $fields['api_email'] ) || empty( $fields['api_email'] ) ) {
			$response['error'] = __( 'Error: You must provide an email address.', 'convertpro-addon' );
		} // Make sure we have an API key.
		elseif ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields['api_email'], $fields['api_key'] );

			libxml_use_internal_errors( true );

			if ( ! simplexml_load_string( $api->Lists() ) ) {
				$response['error'] = __( 'Unable to connect to Mad Mimi. Please check your credentials.', 'convertpro-addon' );
			} else {
				$response['data'] = array(
					'api_email' => $fields['api_email'],
					'api_key'   => $fields['api_key'],
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
			'api_email', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Email Address', 'convertpro-addon' ),
				'help'  => __( 'The email address associated with your Mad Mimi account.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API key can be found in your MailChimp account under Account > Extras > API Keys.', 'convertpro-addon' ),
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
			'api_email' => $auth_meta['api_email'],
			'api_key'   => $auth_meta['api_key'],
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
		$api          = $this->get_api( $account_data['api_email'], $account_data['api_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		libxml_use_internal_errors( true );

		$result = simplexml_load_string( $api->Lists() );

		if ( ! $result ) {
			$response['error'] = __( 'There was a problem retrieving your lists. Please check your API credentials.', 'convertpro-addon' );
		} else {
			$response['html'] = $this->render_list_field( $result, $post_data );
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
		$default = '';
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists->list as $list ) {
			$options[ (string) $list['id'] ] = $list['name'];
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['madmimi_list'] ) ) ? $settings['default']['madmimi_list'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'madmimi_list', array(
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
			$response['error'] = __( 'There was an error subscribing to Mad Mimi. The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api  = $this->get_api( $account_data['api_email'], $account_data['api_key'] );
			$data = array(
				'email'    => $email,
				'add_list' => $settings['madmimi_list'],
			);

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$data[ $settings['meta'][ $key ] ] = $p;
					} else {
						$data[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}
			ob_start();
			$api->AddUser( $data, true );
			$request = ob_get_clean();

			if ( stristr( $request, 'Unable to authenticate' ) ) {
				$response['error'] = __( 'There was an error subscribing to Mad Mimi. The account is no longer connected.', 'convertpro-addon' );
			}
		}

		return $response;
	}
}
