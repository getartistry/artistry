<?php
/**
 * Collects leads and subscribe to Sendlane
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Sendlane API.
 *
 * @since 1.0.2
 */
final class CPRO_Service_Sendlane extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.2
	 * @var string $id
	 */
	public $id = 'sendlane';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Sendlane
	 * has already defined. When Sendlane releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.2
	 * @var string $id
	 */
	public static $mapping_fields = array( 'first_name', 'last_name' );

	/**
	 * Store API instance
	 *
	 * @since 1.0.2
	 * @var object $api_instance
	 * @access private
	 */
	private $api_instance = null;

	/**
	 * Get an instance of the API.
	 *
	 * @since 1.0.2
	 * @param string $credentials A valid API key.
	 * @return object The API instance.
	 */
	public function get_api( $credentials ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/sendlane/cpro-sendlane.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/sendlane/cpro-sendlane.php';
		}

		if ( class_exists( 'CPRO_Sendlane' ) ) {
			$this->api_instance = new CPRO_Sendlane( $credentials );
		}

		return $this->api_instance;
	}
	/**
	 * Test the API connection.
	 *
	 * @since 1.0.2
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
		elseif ( ! isset( $fields['hash_key'] ) || empty( $fields['hash_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a Hash key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		elseif ( ! isset( $fields['domain_url'] ) || empty( $fields['domain_url'] ) ) {
			$response['error'] = __( 'Error: You must provide a Domain URL.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$api = $this->get_api( $fields );

			try {
				$connected = $api->connect();

				if ( false != $connected['error'] ) {
					$response['error'] = $connected['error'];
				}

				$response['data'] = array(
					'api_key'    => $fields['api_key'],
					'hash_key'   => $fields['hash_key'],
					'domain_url' => $fields['domain_url'],
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
	 * @since 1.0.2
	 * @return string The connection settings markup.
	 */
	public function render_connect_settings() {
		ob_start();

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API key can be found in your Sendlane account under Account Settings > Security Credentials.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'hash_key', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Hash Key', 'convertpro-addon' ),
				'help'  => __( 'Your Hash key can be found in your Sendlane account under Account Settings > Security Credentials.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'domain_url', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Domain URL', 'convertpro-addon' ),
				'help'  => __( 'Your Domain URL can be found in your Sendlane account under Account Settings > Security Credentials.', 'convertpro-addon' ),
			)
		);
		return ob_get_clean();
	}

	/**
	 * Returns the api_key in array format
	 *
	 * @since 1.0.2
	 * @param string $auth_meta $api_key A valid API key.
	 * @return array Array of api_key
	 */
	public function render_auth_meta( $auth_meta ) {
		return array(
			'api_key'    => $auth_meta['api_key'],
			'hash_key'   => $auth_meta['hash_key'],
			'domain_url' => $auth_meta['domain_url'],
		);
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.2
	 * @param string $account The name of the saved account.
	 * @param object $post_data Posted data.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 *      @type array $mapping_fields The field mapping array for sendlane.
	 * }
	 */
	public function render_fields( $account, $post_data ) {

		$account_data = ConvertPlugServices::get_account_data( $account );

		$api                 = $this->get_api( $account_data );
		$response            = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$post_data['isEdit'] = ( isset( $post_data['isEdit'] ) ) ? $post_data['isEdit'] : null;

		// Lists field.
		try {

			$resp              = $api->getList();
			$lists             = $resp['lists'];
			$response['html'] .= $this->render_list_field( $lists, $post_data );
			$resp              = $api->getTags();
			$tags              = $resp['tags'];
			$response['html'] .= $this->render_tags_field( $tags, $post_data );

		} catch ( Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		return $response;
	}

	/**
	 * Render markup for the list field.
	 *
	 * @since 1.0.2
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

		foreach ( $lists as $list ) {
			if ( 'Active' == $list->status ) {
				$options[ $list->list_id ] = $list->list_name;
			}
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendlane_list'] ) ) ? $settings['default']['sendlane_list'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'sendlane_list', array(
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
	 * Render markup for the list field.
	 *
	 * @since 1.0.2
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_optin_field( $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendlane_double_optin'] ) ) ? $settings['default']['sendlane_double_optin'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'sendlane_double_optin', array(
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
	 * Render markup for the Tag field.
	 *
	 * @since 1.0.2
	 * @param array  $tags tags array.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the tags field.
	 * @access private
	 */
	private function render_tags_field( $tags, $settings ) {

		$options = array();
		$default = '';

		foreach ( $tags as $id => $data ) {
			$options[ $data->tag_id ] = $data->tag_name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendlane_tags'] ) ) ? $settings['default']['sendlane_tags'] : '' ) : '';
		}
		ob_start();

		ConvertPlugHelper::render_input_html(
			'sendlane_tags', array(
				'class'   => '',
				'type'    => 'multi-select',
				'label'   => __( 'Select Tags', 'convertpro-addon' ),
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
	 * @since 1.0.2
	 */
	public function render_mapping() {
		return self::$mapping_fields;
	}

	/**
	 * Subscribe an email address to Sendlane.
	 *
	 * @since 1.0.2
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
			$response['error'] = __( 'There was an error subscribing to Sendlane! The account is no longer connected.', 'convertpro-addon' );
		} else {
			$api  = $this->get_api( $account_data );
			$data = array();

			$fields = array();

			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( isset( $settings['meta'][ $key ] ) ) {
						if ( 'custom_field' != $settings['meta'][ $key ] ) {
							$data[ $settings['meta'][ $key ] ] = $p;
						} else {
							$data[ $settings['meta'][ $key . '-input' ] ] = $p;
						}
					}
				}
			}

			$data['email']   = $email;
			$data['list_id'] = (int) $settings['sendlane_list'];

			if ( isset( $settings['sendlane_tags'] ) && ! empty( $settings['sendlane_tags'] ) ) {
				$data['tag_ids'] = implode( ',', $settings['sendlane_tags'] );
			}

			// Subscribe.
			try {
				$response = $api->subscribe( $data );
			} catch ( Exception $e ) {
				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to Sendlane! %s', 'convertpro-addon' ),
					$e->getMessage()
				);
			}
		}

		return $response;
	}
}
