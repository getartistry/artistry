<?php
/**
 * Collects leads and subscribe to Infusionsoft
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the GetResponse API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Infusionsoft extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'infusionsoft';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Mailchimp
	 * has already defined. When Mailchimp releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array(
		'Address1Type',
		'Address2Street1',
		'Address2Street2',
		'BillingInformation',
		'Birthday',
		'City',
		'Company',
		'Country',
		'EmailAddress2',
		'EmailAddress3',
		'Fax1',
		'FirstName',
		'JobTitle',
		'Language',
		'LastName',
		'MiddleName',
		'Nickname',
		'Phone1',
		'PostalCode',
		'SpouseName',
		'State',
		'Suffix',
		'TimeZone',
		'Title',
		'Website',
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
	 * @param string $api_key A valid API key.
	 * @param string $app_id A valid Application ID.
	 * @return object The API instance.
	 */
	public function get_api( $api_key, $app_id ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( ! class_exists( 'CPRO_iSDK' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/infusionsoft/isdk.php';
		}

		try {
			$this->api_instance = new CPRO_iSDK;
		} catch ( CPRO_iSDKException $ex ) {
			$this->api_instance = null;
		}

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

		// Make sure we have an API key.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		}

		// Make sure we have an API key.
		if ( ! isset( $fields['app_id'] ) || empty( $fields['app_id'] ) ) {
			$response['error'] = __( 'Error: You must provide an App key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			try {

				$api = $this->get_api( $fields['api_key'], $fields['app_id'] );
				$api->cfgCon( $fields['app_id'], $fields['api_key'], 'on' );
				$response['data'] = array(
					'api_key' => $fields['api_key'],
					'app_id'  => $fields['app_id'],
				);

			} catch ( CPRO_iSDKException $ex ) {
				$response['error'] = $ex->getMessage();
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
				'help'  => '',
			)
		);

		ConvertPlugHelper::render_input_html(
			'app_id', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'App Name', 'convertpro-addon' ),
				'help'  => '',
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
			'app_id'  => $auth_meta['app_id'],
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
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		try {
			$api = $this->get_api( $account_data['api_key'], $account_data['app_id'] );
			$res = $api->cfgCon( $account_data['app_id'], $account_data['api_key'], 'on' );

			$need_request = true;
			$page         = 0;
			$campaigns    = array();

			while ( true == $need_request ) {
				$error_message = 'success';
				$lists_data    = $api->dsQuery(
					'ContactGroup',
					1000,
					$page,
					array(
						'Id' => '%',
					),
					array( 'Id', 'GroupName' )
				);
				$campaigns     = array_merge( $campaigns, $lists_data );

				if ( 1000 > count( $lists_data ) ) {
					$need_request = false;
				} else {
					$page ++;
				}
			}

			if ( count( $campaigns ) > 0 ) {
				$lists = array();
				foreach ( $campaigns as $offset => $cm ) {
					if ( isset( $cm['GroupName'] ) ) {
						$lists[ $cm['Id'] ] = $cm['GroupName'];
					}
				}
			}
		} catch ( CPRO_iSDKException $ex ) {
			$response['error'] = $ex->getMessage();
		}

		if ( ! $lists ) {
			$response['error'] = __( 'Error: Please check your API key.', 'convertpro-addon' );
		} else {
			$response['html'] = $this->render_list_field( $lists, $post_data );
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

		$options = array();
		$default = '';

		foreach ( $lists as $id => $data ) {
			$options[ $id ] = $data;
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['infusionsoft_tags'] ) ) ? $settings['default']['infusionsoft_tags'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'infusionsoft_tags', array(
				'class'   => '',
				'type'    => 'multi-select',
				'label'   => __( 'Select Tags', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $options,
			)
		);

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['infusionsoft_action'] ) ) ? $settings['default']['infusionsoft_action'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'infusionsoft_action', array(
				'class'   => '',
				'type'    => 'text-wrap',
				'label'   => __( 'Action ID', 'convertpro-addon' ),
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
	 * Subscribe an email address to MailChimp.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 * @throws CPRO_iSDKException Error Message.
	 */
	public function subscribe( $settings, $email ) {

		$account_data = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$response     = array(
			'error' => false,
		);
		$result       = array();

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Infusionsoft! The account is no longer connected.', 'convertpro-addon' );
		} else {

			try {

				$api = $this->get_api( $account_data['api_key'], $account_data['app_id'] );
				$res = $api->cfgCon( $account_data['app_id'], $account_data['api_key'], 'on' );

				$contact_details = array(
					'Email' => $email,
				);

				foreach ( $settings['param'] as $key => $p ) {
					if ( 'email' != $key && 'date' != $key ) {
						if ( 'Name' == $settings['meta'][ $key ] ) {
							$name = $p;
						} elseif ( 'custom_field' != $settings['meta'][ $key ] ) {
							$contact_details[ $settings['meta'][ $key ] ] = $p;
						} else {
							$contact_details[ '_' . $settings['meta'][ $key . '-input' ] ] = $p;
						}
					}
				}
				$new_contact_id = $api->addWithDupCheck( $contact_details, 'Email' );

				if ( is_int( $new_contact_id ) ) {

					$api->optIn( $contact_details['Email'] );
					if ( count( $settings['infusionsoft_tags'] ) > 0 ) {
						foreach ( $settings['infusionsoft_tags'] as $tag ) {
							if ( -1 != $tag ) {
								$v = $api->grpAssign( $new_contact_id, $tag );
								if ( ! $v ) {
									$result[] = $tag;
								}
							}
						}
					}
					$action_run_as = ( '' != $settings['infusionsoft_action'] ) ? $api->runAS( $new_contact_id, $settings['infusionsoft_action'] ) : '';
				} else {
					$error = $new_contact_id;
					throw new CPRO_iSDKException( $error );
				}
			} catch ( CPRO_iSDKException $ex ) {
				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to Infusionsoft! %s', 'convertpro-addon' ),
					$ex->getMessage()
				);
			}
		}

		return $response;
	}
}
