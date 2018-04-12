<?php
/**
 * Collects leads and subscribe to Customer.io
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Customer.io API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Customerio extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'customerio';

	/**
	 * The Base URL for the API.
	 *
	 * @since 1.0.0
	 * @var string $root
	 */
	public $root = 'https://track.customer.io/api/v1/';

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that Customer.io
	 * has already defined. When Customer.io releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'first_name', 'last_name', 'phone_number', 'title', 'organization', 'city', 'country', 'zip' );

	/**
	 * Store API instance
	 *
	 * @since 1.0.0
	 * @var object $api_instance
	 * @access private
	 */
	private $api_instance = null;

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
		if ( ! isset( $fields['site_id'] ) || empty( $fields['site_id'] ) ) {
			$response['error'] = __( 'Error: You must provide a Site ID.', 'convertpro-addon' );
		} elseif ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {

			$response['data'] = array(
				'site_id' => $fields['site_id'],
				'api_key' => $fields['api_key'],
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
			'site_id', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'Site ID', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your Customer.io account under Account > Settings > API Keys.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => '',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your Customer.io account under Account > Settings > API Keys.', 'convertpro-addon' ),
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
			'site_id' => $auth_meta['site_id'],
			'api_key' => $auth_meta['api_key'],
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
	 *      @type array $mapping_fields The field mapping array for customerio.
	 * }
	 */
	public function render_fields( $account, $post_data ) {

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$post_data['isEdit'] = ( isset( $post_data['isEdit'] ) ) ? $post_data['isEdit'] : null;

		// Lists field.
		try {
			$default = 'convertpro';

			if ( isset( $post_data['isEdit'] ) && $post_data['isEdit'] ) {
				$default = ( isset( $post_data['default'] ) ) ? ( ( isset( $post_data['default']['customerio_prefix'] ) ) ? $post_data['default']['customerio_prefix'] : 'convertpro' ) : 'convertpro';
			}

			ob_start();

			ConvertPlugHelper::render_input_html(
				'customerio_prefix', array(
					'class'   => '',
					'type'    => 'text-wrap',
					'label'   => __( 'Customer ID Prefix', 'convertpro-addon' ),
					/* translators: %s Product name */
					'note'    => sprintf( __( 'Customer.io works by segmenting your customers based on data passed while creating / updating the customer. By default any customer added to your Customer.io account through %1$s will be prefixed as <b>"convertpro"</b> and segmented as <b>"%2$s"</b> for segmentation.', 'convertpro-addon' ), CPRO_BRANDING_NAME, CPRO_BRANDING_NAME ),
					'default' => $default,
				)
			);

			$response['html'] .= ob_get_clean();
		} catch ( Exception $e ) {
			$response['error'] = $e->getMessage();
		}
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
	 * Subscribe an email address to Customer.io.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 * @throws \Exception Error Message.
	 */
	public function subscribe( $settings, $email ) {

		$account_data = ConvertPlugServices::get_account_data( $settings['api_connection'] );

		$response = array(
			'error' => false,
		);

		if ( ! $account_data ) {
			$response['error'] = __( 'There was an error subscribing to Customer.io! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$data          = array();
			$fields        = array();
			$custom_fields = array();
			$cust_fields   = array();

			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( isset( $settings['meta'][ $key ] ) ) {
						if ( 'custom_field' != $settings['meta'][ $key ] ) {

							$fields[ $settings['meta'][ $key ] ] = $p;

						} else {

							$fields[ $settings['meta'][ $key . '-input' ] ] = $p;
							$custom_fields                                  = array(
								'name'  => $settings['meta'][ $key . '-input' ],
								'value' => $p,
							);
							array_push( $cust_fields, $custom_fields );
						}
					}
				}
			}

			// Map fields and custom fields.
			$default_fields = self::$mapping_fields;
			foreach ( $default_fields as $val ) {

				if ( isset( $fields[ $val ] ) ) {

					$data[ $val ] = $fields[ $val ];
				}
			}

			if ( ! empty( $cust_fields ) ) {

				foreach ( $cust_fields as $key => $field_val ) {

					$data[ $field_val['name'] ] = $field_val['value'];
				}
			}

			$data['email']      = $email;
			$data['created_at'] = time();
			$data['segment']    = CPRO_BRANDING_NAME;
			$data['segment_id'] = CP_PRO_SLUG;

			// Subscribe.
			try {

				$settings['customerio_prefix'] .= '_' . rand();

				$session = curl_init();
				curl_setopt( $session, CURLOPT_URL, $this->root . 'customers/' . $settings['customerio_prefix'] );
				curl_setopt( $session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
				curl_setopt( $session, CURLOPT_HEADER, false );
				curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $session, CURLOPT_VERBOSE, 1 );
				curl_setopt( $session, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt( $session, CURLOPT_POSTFIELDS, http_build_query( $data ) );
				curl_setopt( $session, CURLOPT_USERPWD, $account_data['site_id'] . ':' . $account_data['api_key'] );
				curl_setopt( $session, CURLOPT_SSL_VERIFYPEER, false );

				$resp = curl_exec( $session );
				$resp = json_decode( $resp );
				if ( is_object( $resp ) && isset( $resp->meta->error ) ) {
					throw new \Exception( $resp->meta->error, 1 );
				}
			} catch ( Exception $e ) {
				$response['error'] = sprintf(
					/* translators: %s Error Message */
					__( 'There was an error subscribing to Customer.io! %s', 'convertpro-addon' ),
					$e->getMessage()
				);
			}
		}

		return $response;
	}
}
