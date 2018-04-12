<?php
/**
 * ConverPlug Service Mautic.
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Mautic.
 *
 * @package smile
 * @since 1.0.0
 */
final class CPRO_Service_Mautic extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mautic';

	/**
	 * API object.
	 *
	 * @since 1.0.0
	 * @var object $api_instance
	 * @access private
	 */
	private $api_instance = null;

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array(
		'tags1',
		'title',
		'companywebsite',
		'firstname',
		'companyannual_revenue',
		'lastname',
		'company',
		'companyfax',
		'position',
		'companynumber_of_employees',
		'email',
		'companycountry',
		'phone',
		'companyzipcode',
		'mobile',
		'companystate',
		'fax',
		'tagr',
		'companycity',
		'address1',
		'companyphone',
		'address2',
		'companyemail',
		'city',
		'companyaddress2',
		'state',
		'companyaddress1',
		'zipcode',
		'companyindustry',
		'country',
		'website',
		'twitter',
		'companydescription',
		'facebook',
		'companyname',
		'googleplus',
		'skype',
		'instagram',
		'foursquare',
		'attribution',
		'attribution_date',
		'preferred_locale',
	);

	/**
	 * Get an instance of the API.
	 *
	 * @param array $credentails account credentials.
	 * @since 1.0.0
	 * @return object The API instance.
	 */
	public function get_api( $credentails ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/mautic/class-cp-mautic-api.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/mautic/class-cp-mautic-api.php';
		}

		if ( class_exists( 'CPRO_Mautic_API' ) ) {
			$this->api_instance = new CPRO_Mautic_API( $credentails );
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields account fields.
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

		// Make sure we have a Base URL.
		if ( ! isset( $fields['base_url'] ) || empty( $fields['base_url'] ) ) {
			$response['error'] = __( 'Error: You must provide an Mautic Base URL.', 'convertpro-addon' );
			return $response;
		}

		if ( ! isset( $fields['is_form'] ) || '-1' == $fields['is_form'] ) {
			$response['error'] = __( 'Error: You must select Mautic Integration type', 'convertpro-addon' );
			return $response;
		} else {
			if ( 'form' == $fields['is_form'] ) {
				// Make sure we have an API key.
				if ( ! isset( $fields['form_id'] ) || empty( $fields['form_id'] ) ) {
					$response['error'] = __( 'Error: You must provide Mautic Form ID.', 'convertpro-addon' );
					return $response;
				}
			} else {
				// Make sure we have a Public Key.
				if ( ! isset( $fields['public_key'] ) || empty( $fields['public_key'] ) ) {
					$response['error'] = __( 'Error: You must provide Mautic Public Key.', 'convertpro-addon' );
					return $response;
				}

				// Make sure we have a Secret Key.
				if ( ! isset( $fields['secret_key'] ) || empty( $fields['secret_key'] ) ) {
					$response['error'] = __( 'Error: You must provide Mautic Secret Key.', 'convertpro-addon' );
					return $response;
				}
			}
		}

		$api              = $this->get_api( $fields );
		$response['data'] = $api->connect( $fields, $_POST['currentUrl'] );

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
			'base_url', array(
				'class' => 'mautic_baseurl',
				'type'  => 'text',
				'label' => __( 'Base URL', 'convertpro-addon' ),
				'help'  => __( 'Mautic Base Url.', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'is_form', array(
				'class'   => 'mautic_is_form',
				'type'    => 'select',
				'label'   => '',
				'default' => '-1',
				'help'    => __( 'Form / Segments', 'convertpro-addon' ),
				'options' => array(
					'-1'   => __( 'Select Integration Type', 'convertpro-addon' ),
					'form' => __( 'Mautic Form', 'convertpro-addon' ),
					'api'  => __( 'Mautic API', 'convertpro-addon' ),
				),
			)
		);
		ConvertPlugHelper::render_input_html(
			'public_key', array(
				'class' => 'mautic_public_key',
				'type'  => 'text',
				'label' => __( 'Public Key', 'convertpro-addon' ),
				'help'  => __( 'Mautic Public Key.', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'secret_key', array(
				'class' => 'mautic_secret_key',
				'type'  => 'text',
				'label' => __( 'Secret Key', 'convertpro-addon' ),
				'help'  => __( 'Mautic Secret Key.', 'convertpro-addon' ),
			)
		);
		ConvertPlugHelper::render_input_html(
			'form_id', array(
				'class' => 'mautic_formid',
				'type'  => 'text',
				'label' => __( 'Form ID', 'convertpro-addon' ),
				'help'  => __( 'Mautic Form ID.', 'convertpro-addon' ),
			)
		);
		return ob_get_clean();
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 1.0.0
	 * @param array $auth_meta The name of the saved account.
	 * @return string The connection settings markup.
	 */
	public function render_auth_meta( $auth_meta ) {

		return array(
			'base_url'   => $auth_meta['base_url'],
			'public_key' => $auth_meta['public_key'],
			'secret_key' => $auth_meta['secret_key'],
			'form_id'    => $auth_meta['form_id'],
			'is_form'    => $auth_meta['is_form'],
		);
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param string $account The name of the saved account.
	 * @param object $post_data post data.
	 * @return array $response Response array.
	 * @throws \Exception Error Message.
	 */
	public function render_fields( $account, $post_data ) {

		$account_data = $this->get_account_data( $account );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$fields       = unserialize( $account[ CP_API_CONNECTION_SERVICE_AUTH ][0] );
		if ( isset( $fields['is_form'] ) ) {

			if ( 'form' == $fields['is_form'] ) {
				/* translators: %s Error Message */
				$response['html'] .= sprintf( __( 'All set! You can now proceed to the next step to map all the fields. Make sure the field name in %s is the same as the one created in Mautic. The Email field is mapped by default.', 'convertpro-addon' ), CPRO_BRANDING_NAME );
			} else {

				try {

					$api      = $this->get_api( $fields );
					$segments = $api->getSegments();

					if ( isset( $segments['total'] ) ) {
						if ( ! empty( $segments['lists'] ) ) {

							$lists             = $segments['lists'];
							$response['html'] .= $this->render_segment_field( $lists, $post_data );

						} else {
							throw new \Exception( $segments['error'], 1 );
						}
					} else {
						throw new \Exception( $segments['error'], 1 );
					}

					$response['html'] .= $this->render_tags_field( $post_data );

				} catch ( Exception $e ) {
					$response['error'] = $e->getMessage();
				}
			}
		}
		return $response;
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param array  $lists The array of lists.
	 * @param object $settings post data.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 * }
	 */
	private function render_segment_field( $lists, $settings ) {

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';

		foreach ( $lists as $list ) {
			$options[ $list->id ] = $list->name;
		}

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mautic_segment'] ) ) ? $settings['default']['mautic_segment'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'mautic_segment', array(
				'class'   => '',
				'type'    => 'multi-select',
				'label'   => __( 'Select a segment', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $options,
			)
		);

		return ob_get_clean();

	}

	/**
	 * Render markup for the tag field.
	 *
	 * @since 1.0.0
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_tags_field( $settings ) {

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mautic_tags'] ) ) ? $settings['default']['mautic_tags'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'mautic_tags', array(
				'class'   => 'cpro-tags',
				'type'    => 'text-wrap',
				'label'   => __( 'Tags', 'convertpro-addon' ),
				'help'    => __( 'Please separate tags with a comma.', 'convertpro-addon' ),
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
	 * Add contact to Mautic.
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
			$response['error'] = __( 'There was an error subscribing to Mautic. The account is no longer connected.', 'convertpro-addon' );
		} else {

			$merge_arr = array();
			$form_data = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$merge_arr[ $settings['meta'][ $key ] ] = $p;
					} else {
						$merge_arr[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}
			$ip = $this->_get_ip();
			if ( 'form' == $account_data['is_form'] ) {

				$url                 = $account_data['base_url'];
				$form_id             = $account_data['form_id'];
				$form_data           = $merge_arr;
				$form_data['email']  = $email;
				$form_data['formId'] = $form_id;
				$form_data['return'] = get_home_url();
				$data                = array(
					'mauticform' => $form_data,
				);
				$url                 = path_join( $url, "form/submit?formId={$form_id}" );

				$result = wp_remote_post(
					$url,
					array(
						'method'  => 'POST',
						'timeout' => 45,
						'headers' => array(
							'X-Forwarded-For' => $ip,
						),
						'body'    => $data,
						'cookies' => array(),
					)
				);

				if ( is_wp_error( $result ) ) {
					$response['error'] = $result->get_error_message();
				}
				if ( isset( $result['response']['code'] ) && 200 != $result['response']['code'] ) {
					$response['error'] = $result['response']['message'];
				}
			} else {
				$api = $this->get_api( $account_data );

				$result = $api->subscribe( $settings['mautic_tags'], $settings['mautic_segment'], $email, $merge_arr, $ip );
				if ( is_wp_error( $result ) ) {
					$response['error'] = $result->get_error_message();
				}
				if ( isset( $result['response']['code'] ) && ! ( 201 == $result['response']['code'] || 200 == $result['response']['code'] ) ) {
					$res_body          = json_decode( $result['body'] );
					$response['error'] = ( isset( $res_body->error->message ) ) ? $res_body->error->message : $response['response']['message'];
				}
			}
		}
		return $response;
	}

	/**
	 * Get User's IP
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function _get_ip() {
		$ip      = '';
		$ip_list = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
		);
		foreach ( $ip_list as $key ) {
			if ( ! isset( $_SERVER[ $key ] ) ) {
				continue;
			}
			$ip = esc_attr( $_SERVER[ $key ] );
			if ( ! strpos( $ip, ',' ) ) {
				$ips = explode( ',', $ip );
				foreach ( $ips as &$val ) {
					$val = trim( $val );
				}
				$ip = end( $ips );
			}
			$ip = trim( $ip );
			break;
		}
		return $ip;
	}
}
