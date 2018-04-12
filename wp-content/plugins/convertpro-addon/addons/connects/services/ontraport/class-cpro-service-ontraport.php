<?php
/**
 * ConverPlug Service SimplyCast
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Ontraport API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Ontraport extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'ontraport';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'First Name', 'Last Name', 'Title', 'Company', 'Address', 'Address 2', 'City', 'State', 'Zip Code', 'Country', 'Fax', 'SMS Number', 'Office Phone', 'Birthday', 'Website' );

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
	 * @param string $app_id A valid APP Id.
	 * @return object The API instance.
	 */
	public function get_api( $api_key, $app_id ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( ! class_exists( 'CPRO_Ontraport_API_Class' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/ontraport/api.class.php';
		}

		$this->api_instance = new CPRO_Ontraport_API_Class( $api_key, $app_id );

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields The fields.
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 * @throws Exception Error Message.
	 */
	public function connect( $fields = array() ) {

		$response = array(
			'error' => false,
			'data'  => array(),
		);

		// Make sure we have an app id.
		if ( ! isset( $fields['app_id'] ) || empty( $fields['app_id'] ) ) {
			$response['error'] = __( 'Error: You must provide an App ID.', 'convertpro-addon' );
		} // Make sure we have an api key.
		elseif ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			$api             = $this->get_api( $fields['api_key'], $fields['app_id'] );
			$ontraport_lists = $this->get_ontraport_lists( $fields['api_key'], $fields['app_id'] );

			if ( ! $ontraport_lists['success'] ) {
				$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
			} else {
				$response['data'] = array(
					'api_key' => $fields['api_key'],
					'app_id'  => $fields['app_id'],
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
			'app_id', array(
				'class' => 'cp_ontraport_app_id',
				'type'  => 'text',
				'label' => __( 'APP ID', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your ONTRAPORT account under Settings > Administration  > ONTRAPORT API Instructions and Key Manager.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => 'cp_ontraport_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your ONTRAPORT account under Settings > Administration  > ONTRAPORT API Instructions and Key Manager.', 'convertpro-addon' ),
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
			'app_id'  => $authmeta['app_id'],
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
		$api          = $this->get_api( $account_data['api_key'], $account_data['app_id'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$ontraport_lists = $this->get_ontraport_lists( $account_data['api_key'], $account_data['app_id'] );

		if ( ! $ontraport_lists['success'] ) {
			$response['error'] = __( 'There was an error connecting to ONTRAPORT. Please try again.', 'convertpro-addon' );
		} else {
			$response['html']  = $this->render_seqs_field( $api, $settings );
			$response['html'] .= $this->render_tags_field( $api, $settings );
		}
		return $response;
	}

	/**
	 * Render markup for the Tag field.
	 *
	 * @since 1.0.0
	 * @param array  $api API data.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the tags field.
	 * @access private
	 */
	private function render_tags_field( $api, $settings ) {

		ob_start();

		$options = array();
		$tags    = $api->getTags();
		$default = '';

		foreach ( $tags['result'] as $id => $name ) {
			$options[ $name ] = $name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['ontraport_tags'] ) ) ? $settings['default']['ontraport_tags'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'ontraport_tags', array(
				'class'   => '',
				'type'    => 'multi-select',
				'label'   => __( 'Tags', 'convertpro-addon' ),
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
	 * @since 1.0.0
	 * @param array  $api API data.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the sequence field.
	 * @access private
	 */
	private function render_seqs_field( $api, $settings ) {

		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';

		$seqs = $api->getSequences();

		foreach ( $seqs['result'] as $id => $name ) {
			$options[ $id ] = $name;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['ontraport_seqs'] ) ) ? $settings['default']['ontraport_seqs'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'ontraport_seqs', array(
				'class'   => '',
				'type'    => 'select',
				'label'   => __( 'Sequence', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $options,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Get ontraport Mailer Campaign list
	 *
	 * @since 1.0.0
	 * @param array  $ontraport_api_key API Key.
	 * @param object $ontraport_app_id App ID.
	 * @return string The markup for the sequence field.
	 * @access private
	 */
	function get_ontraport_lists( $ontraport_api_key, $ontraport_app_id ) {
		if ( '' != $ontraport_api_key && '' != $ontraport_app_id ) {
			try {
				$obj  = $this->get_api( $ontraport_api_key, $ontraport_app_id );
				$seqs = $obj->getSequences();
				$tags = $obj->getTags();

				if ( '' != $obj->http_error_code ) {
					return array(
						'success' => false,
						'lists'   => array(),
					);
				}
			} catch ( Exception $ex ) {
				return array(
					'success' => false,
					'lists'   => array(),
				);
			}
			if ( count( $seqs['result'] ) > 0 ) {
				$lists = array();
				foreach ( $seqs['result'] as $key => $cm ) {
					$lists[ $cm ] = $cm;
				}
				return array(
					'success' => true,
					'lists'   => $lists,
				);
			} else {
				return array(
					'success' => false,
					'lists'   => array(),
				);
			}
		}
		return array(
			'success' => false,
			'lists'   => array(),
		);
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
	 * Subscribe an email address to Ontraport.
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
			$response['error'] = __( 'There was an error subscribing to ONTRAPORT! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api  = $this->get_api( $account_data['api_key'], $account_data['app_id'] );
			$data = array();

			$subscriber = array(
				'Email' => $email,
			);

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

			$result = $api->listAddContacts( $subscriber, implode( ',', $settings['ontraport_tags'] ), $settings['ontraport_seqs'] );

			if ( 0 == $result ) {
				$response['error'] = __( 'There was an error subscribing to ONTRAPORT! Please try again.', 'convertpro-addon' );
			}
		}

		return $response;
	}
}
