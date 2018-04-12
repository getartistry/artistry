<?php
/**
 * ConverPlug Service SimplyCast
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the simplycast API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_SimplyCast extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'simplycast';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'name', 'mobile', 'phone', 'address1', 'address2', 'city', 'state', 'zip', 'company', 'country', 'fax', 'website' );

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
	 * @param string $secret_key A valid Secret key.
	 * @return object The API instance.
	 */
	public function get_api( $api_key, $secret_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/simplycast/SimplyCastAPI.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/simplycast/SimplyCastAPI.php';
			$this->api_instance = new \SimplyCast\API( $api_key, $secret_key );
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type string $api_key A valid API Key.
	 *      @type string $secret_key A valid Secret Key.
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

		// Make sure we have an API url.
		if ( ! isset( $fields['api_key'] ) || empty( $fields['api_key'] ) ) {
			$response['error'] = __( 'Error: You must provide an API Key.', 'convertpro-addon' );
		} // Make sure we have an API Key.
		elseif ( ! isset( $fields['secret_key'] ) || empty( $fields['secret_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a Secret key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			try {
				$api              = $this->get_api( $fields['api_key'], $fields['secret_key'] );
				$cont             = $api->contactmanager->getLists();
				$response['data'] = array(
					'api_key'    => $fields['api_key'],
					'secret_key' => $fields['secret_key'],
				);
			} catch ( Exception $e ) {
				$response['error'] = __( 'Something went wrong! Please try again.', 'convertpro-addon' );
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
				'class' => 'cp_active_simplycast_api_key',
				'type'  => 'text',
				'label' => __( 'Public Key', 'convertpro-addon' ),
				'help'  => __( 'Your API URL can be found in your SimplyCast account under My Settings > API > New Key.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'secret_key', array(
				'class' => 'cp_active_simplycast_secret_key',
				'type'  => 'text',
				'label' => __( 'Secret Key', 'convertpro-addon' ),
				'help'  => __( 'Your API Key can be found in your SimplyCast account under My Settings > API > New Key.', 'convertpro-addon' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 1.0.0
	 * @param object $authmeta Authentication meta.
	 * @return array The connection settings markup.
	 */
	public function render_auth_meta( $authmeta ) {
		return array(
			'api_key'    => $authmeta['api_key'],
			'secret_key' => $authmeta['secret_key'],
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
		$api          = $this->get_api( $account_data['api_key'], $account_data['secret_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		$lists        = $api->contactmanager->getLists();
		if ( null == $lists['lists'] ) {
			$response['error'] .= __( 'Error: No lists found.', 'convertpro-addon' );
		} else {
			$response['html'] .= $this->render_list_field( $lists, $settings );
		}
		return $response;
	}

	/**
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array $lists List data from the API.
	 * @param array $settings Settings data.
	 * @return string The markup for the list field.
	 */
	private function render_list_field( $lists, $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['simplycast_lists'] ) ) ? $settings['default']['simplycast_lists'] : '' ) : '';
		}
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		if ( $lists['totalCount'] > 0 ) {
			foreach ( $lists['lists'] as $offset => $cm ) {
				$options[ $cm['id'] ] = $cm['name'];
			}
		}
		ConvertPlugHelper::render_input_html(
			'simplycast_lists', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'A simple list from SimplyCast.', 'convertpro-addon' ),
				'default' => $default,
				'options' => $options,
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
	 * Subscribe an email address to SimplyCast.
	 *
	 * @since 1.0.0
	 * @param object $settings A module settings object.
	 * @param string $email The email to subscribe.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 * }
	 */
	public function subscribe( $settings, $email ) {

		$response = array(
			'error' => false,
		);
		$account  = ConvertPlugServices::get_account_data( $settings['api_connection'] );
		$api      = $this->get_api( $account['api_key'], $account['secret_key'] );
		if ( ! $api ) {
			$response['error'] = __( 'There was an error subscribing to SimplyCast! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$my_contact[] = array(
				'id'    => 23,
				'value' => $email,
			);

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] ) {
						$columns_list[]                                    = $settings['meta'][ $key . '-input' ];
						$mycontact[ $settings['meta'][ $key . '-input' ] ] = $p;

					} elseif ( 'custom_field' != $settings['meta'][ $key ] ) {
						$columns_list[]                         = $settings['meta'][ $key ];
						$mycontact[ $settings['meta'][ $key ] ] = $p;
					}
				}
			}

			$columns = $api->contactmanager->getColumnsByName( $columns_list );
			foreach ( $columns['columns'] as $col ) {
				if ( array_key_exists( $col['name'], $mycontact ) ) {
					$my_contact[] = array(
						'id'    => $col['id'],
						'value' => $mycontact[ $col['name'] ],
					);
				}
			}

			$createdcontact = $api->contactmanager->createContact( $my_contact );
			$contactid      = $createdcontact['contact']['id'];
			$result         = $api->contactmanager->addContactsToList( $settings['simplycast_lists'], array( $contactid ) );
			if ( isset( $result->error ) ) {
				$response['error'] = __( 'There was an error subscribing to SimplyCast!', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
