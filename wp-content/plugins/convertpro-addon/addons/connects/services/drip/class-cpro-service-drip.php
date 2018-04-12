<?php
/**
 * Collects leads and subscribe to Drip
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Drip API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Drip extends CPRO_Service {
	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'drip';

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		if ( ! class_exists( 'CPRO_Drip_Api' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/drip/Drip_API.class.php';
		}
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Drip
	 * has already defined. When Drip releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array();

	/**
	 * API instance
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
	 * @param string $api_token A valid API token.
	 * @return object The API instance.
	 */
	public function get_api( $api_token ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		$this->api_instance = new CPRO_Drip_Api( $api_token );

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param @type string $fields A valid API token.
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

		// Make sure we have an API Token.
		if ( ! isset( $fields['api_token'] ) || empty( $fields['api_token'] ) ) {
			$response['error'] = __( 'Error: You must provide an API token.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$drip = $this->get_api( $fields['api_token'] );

			$accounts = $drip->get_accounts();

			$info = $drip->get_request_info();

			if ( isset( $info['status'] ) && 1 == $info['status'] ) {
				$response['data'] = array(
					'api_token' => $fields['api_token'],
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
			'api_token', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API key can be found in your Drip account under Account settings > API Keys.', 'convertpro-addon' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Returns the api_token in array format
	 *
	 * @since 1.0.0
	 * @param @type string $auth_meta A valid auth data.
	 * @return array Array of api_token
	 */
	public function render_auth_meta( $auth_meta ) {
		return array(
			'api_token' => $auth_meta['api_token'],
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

		$drip   = $this->get_api( $account_data['api_token'] );
		$result = $drip->get_accounts();

		$info = $drip->get_request_info();

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( isset( $info['status'] ) && 1 == $info['status'] ) {

			if ( isset( $post_data['client'] ) ) {

				$response['html'] .= $this->render_list_field( $account_data, $settings );
				$response['html'] .= $this->render_tags_field( $settings );
			} else {

				$response['html'] .= $this->render_client_field( $result, $settings );
				if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
					$response['html'] .= $this->render_list_field( $account_data, $settings );
					$response['html'] .= $this->render_tags_field( $settings );
				}
			}
			$response['html'] .= $this->render_double_optin( $settings );
		} else {
			$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
		}
		return $response;
	}

	/**
	 * Render markup for the client field.
	 *
	 * @since 1.0.0
	 * @param array  $accounts client data from the API.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_client_field( $accounts, $settings ) {
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $accounts as $acc ) {

			$options[ $acc['id'] ] = $acc['name'];
		}
		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['drip_account_id'] ) ) ? $settings['default']['drip_account_id'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'drip_account_id', array(
				'class'   => 'cpro-client-select',
				'type'    => 'select',
				'label'   => _x( 'Select Drip Account', 'A client account in Drip.', 'convertpro-addon' ),
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
			$client_id = isset( $settings['default']['drip_account_id'] ) ? $settings['default']['drip_account_id'] : '';
		} else {
			return '';
		}

		$params = array(
			'account_id' => $client_id,
		);

		// Get the campaign data.
		$drip = $this->get_api( $account_data['api_token'] );

		$campaigns = $drip->get_campaigns( $params );

		// render the list field.
		ob_start();

		$default = '';
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $campaigns as $list ) {
			$options[ $list['id'] ] = $list['name'];
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['drip_list_id'] ) ) ? $settings['default']['drip_list_id'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'drip_list_id', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'Campaign ( Optional )', 'An email list from a third party provider.', 'convertpro-addon' ),
				'options' => $options,
				'default' => $default,
			), $settings
		);

		return ob_get_clean();
	}

	/**
	 * Render markup for the tags field.
	 *
	 * @since 1.0.0
	 * @param object $settings Saved module settings.
	 * @return string The markup for the tags field.
	 * @access private
	 */
	public function render_tags_field( $settings ) {
		ob_start();

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['drip_tags'] ) ) ? $settings['default']['drip_tags'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'drip_tags', array(
				'class'   => 'cpro-tags',
				'type'    => 'text-wrap',
				'label'   => _x( 'Tags ( Optional )', 'A contact tags.', 'convertpro-addon' ),
				'default' => '',
				'help'    => __( 'Please separate tags with a comma.', 'convertpro-addon' ),
				'default' => $default,
			), $settings
		);

		return ob_get_clean();
	}

	/**
	 * Render markup for the double optin field.
	 *
	 * @since 1.0.0
	 * @param object $settings Saved module settings.
	 * @return string The markup for the double optin field.
	 * @access private
	 */
	public function render_double_optin( $settings ) {
		ob_start();

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['drip_double_optin'] ) ) ? $settings['default']['drip_double_optin'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'drip_double_optin', array(
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
	 * Subscribe an email address to Drip.
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

		$merge_arr            = array();
		$member               = array();
		$list_campaign        = $settings['drip_list_id'];
		$drip_tags            = $settings['drip_tags'];
		$member['account_id'] = $settings['drip_account_id'];
		$member['email']      = $email;

		$d_optin                = ( isset( $settings['drip_double_optin'] ) ) ? $settings['drip_double_optin'] : false;
		$member['double_optin'] = $d_optin;
		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Drip! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$drip = $this->get_api( $account_data['api_token'] );

			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$merge_arr[ $settings['meta'][ $key ] ] = $p;
					} else {
						$merge_arr[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			if ( ! empty( $drip_tags ) ) {
				$tags           = $drip_tags;
				$member['tags'] = explode( ',', $tags );
			}

			if ( ! empty( $merge_arr ) ) {
				unset( $merge_arr['date'] );
				$member['custom_fields'] = (object) $merge_arr;
			}
			// Add subscriber to Campaign.
			if ( '-1' == $list_campaign ) {

				$res = $drip->create_or_update_subscriber( $member );
			} else {
				$member['campaign_id'] = $list_campaign;
				$res                   = $drip->subscribe_subscriber( $member );
			}
			$info = $drip->get_request_info();
			if ( isset( $info['http_code'] ) && 201 !== $info['http_code'] && 200 !== $info['http_code'] && 422 !== $info['http_code'] ) {
				$response['error'] = ( isset( $info['buffer'] ) ) ? $info['buffer'] : __( 'There was an error subscribing to Drip!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
