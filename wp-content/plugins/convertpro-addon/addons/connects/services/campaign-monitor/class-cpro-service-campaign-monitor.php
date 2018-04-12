<?php
/**
 * Collects leads and subscribe to Campaign Monitor
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Campaign Monitor API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Campaign_Monitor extends CPRO_Service {
	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'campaign-monitor';

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		if ( ! class_exists( 'CS_REST_General' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/campaign-monitor/csrest_general.php';
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/campaign-monitor/csrest_clients.php';
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/campaign-monitor/csrest_lists.php';
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/campaign-monitor/csrest_subscribers.php';
		}
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that CampaignMonitor
	 * has already defined. When CampaignMonitor releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'Name', 'Location' );

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param @type string $fields A valid API key.
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

			$api    = new CS_REST_General(
				array(
					'api_key' => $fields['api_key'],
				)
			);
			$result = $api->get_clients();

			if ( $result->was_successful() ) {
				$response['data'] = array(
					'api_key' => $fields['api_key'],
				);
			} else {
				$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
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
				'help'  => __( 'Your API key can be found in your Campaign Monitor account under Account settings > API Keys.', 'convertpro-addon' ),
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
		$api          = new CS_REST_General( $account_data );
		$result       = $api->get_clients();
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( $result->was_successful() ) {

			if ( ! isset( $post_data['client'] ) ) {
				$response['html'] .= $this->render_client_field( $result, $settings );

				if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {

					$response['html'] .= $this->render_list_field( $account_data, $settings );
				}
			}
			if ( isset( $post_data['client'] ) ) {
				$response['html'] .= $this->render_list_field( $account_data, $settings );
			}
		} else {
			$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
		}

		return $response;
	}

	/**
	 * Render markup for the client field.
	 *
	 * @since 1.0.0
	 * @param array  $clients Client data from the API.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_client_field( $clients, $settings ) {
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';

		foreach ( $clients->response as $client ) {
			// @codingStandardsIgnoreStart
			$options[ $client->ClientID ] = $client->Name;
			// @codingStandardsIgnoreEnd
		}
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['campaign_monitor_client_id'] ) ) ? $settings['default']['campaign_monitor_client_id'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'campaign_monitor_client_id', array(
				'class'   => 'cpro-client-select',
				'type'    => 'select',
				'label'   => _x( 'Client', 'A client account in Campaign Monitor.', 'convertpro-addon' ),
				'options' => $options,
				'default' => $default,
			), $settings
		);
		return ob_get_clean();
	}

	/**
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array  $account_data Saved account data.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_list_field( $account_data, $settings ) {

		$post_data = ConvertPlugHelper::get_post_data();

		// Get the client ID. Return an empty string if we don't have one yet.
		if ( isset( $post_data['client'] ) ) {
			$client_id = $post_data['client'];
		} elseif ( isset( $settings['default'] ) ) {
			$client_id = isset( $settings['default']['campaign_monitor_client_id'] ) ? $settings['default']['campaign_monitor_client_id'] : '';
		} else {
			return '';
		}
		// Get the list data.
		$api   = new CS_REST_Clients( $client_id, $account_data );
		$lists = $api->get_lists();

		// Render the list field.
		ob_start();
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';
		foreach ( $lists->response as $list ) {
			// @codingStandardsIgnoreStart
			$options[ $list->ListID ] = $list->Name;
			// @codingStandardsIgnoreEnd
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['campaign_monitor_list_id'] ) ) ? $settings['default']['campaign_monitor_list_id'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'campaign_monitor_list_id', array(
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
	 * Subscribe an email address to Campaign Monitor.
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

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Campaign Monitor! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$api  = new CS_Rest_Subscribers( $settings['campaign_monitor_list_id'], $account_data );
			$data = array(
				'EmailAddress' => $email,
				'Resubscribe'  => true,
			);

			$merge_arr     = array();
			$custom_fields = array();
			$fields        = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$merge_arr[ $settings['meta'][ $key ] ] = $p;
					} else {
						$merge_arr[ $settings['meta'][ $key . '-input' ] ] = $p;

						$custom_fields = array(
							'Key'   => $settings['meta'][ $key . '-input' ],
							'Value' => $p,
						);
						array_push( $fields, $custom_fields );
					}
				}
			}

			if ( isset( $merge_arr['Name'] ) ) {
				$data['Name'] = $merge_arr['Name'];
			}

			$data['CustomFields'] = $fields;

			$result = $api->add( $data );
			if ( ! $result->was_successful() ) {
				$response['error'] = __( 'There was an error subscribing to Campaign Monitor!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
