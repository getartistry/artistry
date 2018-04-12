<?php
/**
 * Collects leads and subscribe to MailChimp
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the ActiveCampaign API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_ActiveCampaign extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'activecampaign';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'first_name', 'last_name', 'phone', 'orgname', 'name' );

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
	 * @param string $api_url A valid API URL.
	 * @param string $api_key A valid API Key.
	 * @return object The API instance.
	 */
	public function get_api( $api_url, $api_key ) {

		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/activecampaign/ActiveCampaign.class.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/activecampaign/ActiveCampaign.class.php';
		}

		if ( class_exists( 'CPPro_ActiveCampaign' ) ) {
			$this->api_instance = new CPPro_ActiveCampaign( $api_url, $api_key );
		}
		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields A valid API Key.
	 * @throws Exception Error Message.
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

		// Make sure we have an API URL.
		if ( ! isset( $fields['api_url'] ) || empty( $fields['api_url'] ) ) {
			$response['error'] = __( 'Error: You must provide an API URL.', 'convertpro-addon' );
		} // Make sure we have an API Key.
		elseif ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			try {
				$api = $this->get_api( $fields['api_url'], $fields['api_key'] );
				if ( ! empty( $api ) ) {
					if ( ! (int) $api->credentials_test() ) {
						$response['error'] = __( 'Oops! You\'ve entered the wrong API Key. Please enter the API key and try again.', 'convertpro-addon' );
					} else {
						$response['data'] = array(
							'api_url' => $fields['api_url'],
							'api_key' => $fields['api_key'],
						);
					}
				} else {
					throw new Exception( 'Error: There seems to be an error with the configuration' );
				}
			} catch ( Exception $e ) {
				$response['error'] = $e->getMessage();
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
			'api_url', array(
				'class' => 'cp_active_campaign_api_url',
				'type'  => 'text',
				'label' => __( 'API URL', 'convertpro-addon' ),
				'help'  => __( 'Your API URL can be found in your ActiveCampaign account under My Settings > Developer > API Access.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'api_key', array(
				'class' => 'cp_active_campaign_api_key',
				'type'  => 'text',
				'label' => __( 'API Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your ActiveCampaign account under My Settings > Developer > API Access.', 'convertpro-addon' ),
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
			'api_url' => $auth_meta['api_url'],
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
		$api          = $this->get_api( $account_data['api_url'], $account_data['api_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$settings['isEdit'] = ( isset( $settings['isEdit'] ) ) ? $settings['isEdit'] : null;

		$lists = $api->api( 'list/list?ids=all' );
		$forms = $api->api( 'form/getforms' );

		if ( 'false' == $settings['isEdit'] || null == $settings['isEdit'] ) {

			if ( ! isset( $post_data['list_id'] ) ) {
				$response['html'] = $this->render_list_type_field( $settings );
			}

			if ( isset( $post_data['list_id'] ) ) {

				$list_type = $post_data['list_id'];

				if ( 'list' == $list_type ) {
					if ( ! empty( $lists->error ) && '0' == $lists->result_code ) {
						$response['error'] .= __( 'Error: No lists found.', 'convertpro-addon' );
					} else {
						$response['html'] .= $this->render_list_field( $lists, $settings );
						$response['html'] .= $this->render_tags_field( $settings );
					}
				} elseif ( 'form' == $list_type ) {
					if ( ! empty( $forms->error ) && '0' == $forms->result_code ) {
						$response['error'] .= __( 'Error: No forms found.', 'convertpro-addon' );
					} else {
						$response['html'] .= $this->render_form_field( $forms, $settings );
						$response['html'] .= $this->render_tags_field( $settings );
					}
				}
			}
		} else {

			$response['html'] .= $this->render_list_type_field( $settings );

			if ( 'list' == $settings['default']['activecampaign_type'] ) {
				$response['html'] .= $this->render_list_field( $lists, $settings );
				$response['html'] .= $this->render_tags_field( $settings );

			}
			if ( 'form' == $settings['default']['activecampaign_type'] ) {
				$response['html'] .= $this->render_form_field( $forms, $settings );
				$response['html'] .= $this->render_tags_field( $settings );
			}
		}
		return $response;
	}

	/**
	 * Render markup for the list type.
	 *
	 * @since 1.0.0
	 * @param object $settings Saved module settings.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_list_type_field( $settings ) {

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['activecampaign_type'] ) ) ? $settings['default']['activecampaign_type'] : '' ) : '';
		}
		ob_start();
		ConvertPlugHelper::render_input_html(
			'activecampaign_type', array(
				'class'   => 'convert-plug-v2-list-type-select',
				'type'    => 'select',
				'label'   => _x( 'Type', 'Select the list type.', 'convertpro-addon' ),
				'default' => $default,
				'options' => array(
					'-1'   => __( 'Choose', 'convertpro-addon' ),
					'list' => __( 'List', 'convertpro-addon' ),
					'form' => __( 'Form', 'convertpro-addon' ),
				),
			), $settings
		);
		return ob_get_clean();
	}

	/**
	 * Render markup for the form field
	 *
	 * @since 1.0.0
	 * @param array  $forms Form data from the API.
	 * @param object $settings Saved module settings.
	 * @return string The markup for the form field.
	 * @access private
	 */
	private function render_form_field( $forms, $settings ) {

		$default = '';
		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['activecampaign_forms'] ) ) ? $settings['default']['activecampaign_forms'] : '' ) : '';
		}
		ob_start();
		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( (array) $forms as $form ) {
			if ( is_object( $form ) && isset( $form->id ) ) {
				$options[ $form->id ] = $form->name;
			}
		}
		ConvertPlugHelper::render_input_html(
			'activecampaign_forms', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'Form', 'Select a form a ActiveCampaign.', 'convertpro-addon' ),
				'default' => $default,
				'options' => $options,
			), $settings
		);
		return ob_get_clean();
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

		$default = '';
		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['activecampaign_lists'] ) ) ? $settings['default']['activecampaign_lists'] : '' ) : '';
		}
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( (array) $lists as $list ) {
			if ( is_object( $list ) && isset( $list->id ) ) {
				$options[ $list->id ] = $list->name;
			}
		}

		ConvertPlugHelper::render_input_html(
			'activecampaign_lists', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'An email list from ActiveCampaign.', 'convertpro-addon' ),
				'default' => $default,
				'options' => $options,
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
	private function render_tags_field( $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && '' != $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['activecampaign_tags'] ) ) ? $settings['default']['activecampaign_tags'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'activecampaign_tags', array(
				'class'   => 'cpro-tags',
				'type'    => 'text-wrap',
				'help'    => __( 'Please separate tags with a comma.', 'convertpro-addon' ),
				'label'   => __( 'Tags', 'convertpro-addon' ),
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
	 * Subscribe an email address to ActiveCampaign.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email ) {

		$account  = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$api      = $this->get_api( $account['api_url'], $account['api_key'] );
		$response = array(
			'error' => false,
		);

		if ( ! $api ) {
			$response['error'] = __( 'There was an error subscribing to ActiveCampaign! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$data['email'] = $email;
			if ( isset( $settings['activecampaign_type'] ) && 'form' == $settings['activecampaign_type'] ) {
				$data['form'] = $settings['activecampaign_forms']; // change form id.
			} else {
				$data['p']                 = array( $settings['activecampaign_lists'] => $settings['activecampaign_lists'] );
				$data['status']            = array( $settings['activecampaign_lists'] => 1 );
				$data['instantresponders'] = array( $settings['activecampaign_lists'] => 1 );
			}

			$custom_arr = array();
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] ) {
						$custom_field                               = $settings['meta'][ $key . '-input' ];
						$custom_arr[ '%' . $custom_field . '%, 0' ] = $p;
					} else {
						$data[ $settings['meta'][ $key ] ] = $p;
					}
				}
			}

			if ( ! empty( $custom_arr ) ) {
				$data['field'] = $custom_arr;
			}

			// Tags.
			if ( isset( $settings['activecampaign_tags'] ) && ! empty( $settings['activecampaign_tags'] ) ) {
				$data['tags'] = $settings['activecampaign_tags'];
			}

			// Subscribe.
			$result = $api->api( 'contact/sync', $data );

			if ( ! $result->success && isset( $result->error ) ) {

				if ( stristr( $result->error, 'access' ) ) {
					$response['error'] = __( 'There was an error subscribing to ActiveCampaign!', 'convertpro-addon' );
				} else {
					$response['error'] = $result->error;
				}
			}
		}
		return $response;
	}
}
