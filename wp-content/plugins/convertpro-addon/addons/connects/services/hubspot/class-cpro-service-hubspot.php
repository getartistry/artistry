<?php
/**
 * Collects leads and subscribe to HubSpot
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Hubspot.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Hubspot extends CPRO_Service {
	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'hubspot';

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		require_once CP_SERVICES_BASE_DIR . 'includes/vendor/hubspot/class.lists.php';
		require_once CP_SERVICES_BASE_DIR . 'includes/vendor/hubspot/class.contacts.php';
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that HubSpot
	 * has already defined. When HubSpot releases the new
	 * set of fields, we need to update this array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array( 'firstname', 'lastname', 'phone' );

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
		} else {

			try {
				$lists_obj = new CPRO_HubSpot_Lists( $fields['api_key'] );
				$lists     = $lists_obj->get_static_lists( null );

				if ( 'error' != $lists->status ) {
					$response['data'] = array(
						'api_key' => $fields['api_key'],
					);
				} else {
					$response['error'] = $lists->message;
				}
			} catch ( Exception $ex ) {
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
				'help'  => __( 'Your API key can be found in your HubSpot account under Account settings > API Keys.', 'convertpro-addon' ),
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

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$response['html'] .= $this->render_list_field( $account_data, $settings );
		return $response;
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
		$default   = '';
		$api_key   = $account_data['api_key'];

		try {
			$lists_obj = new CPRO_HubSpot_Lists( $api_key );
			$lists     = $lists_obj->get_static_lists( null );
		} catch ( Exception $ex ) {
			return array();
		}

		if ( isset( $lists->status ) ) {
			if ( 'error' == $lists->status ) {
				return array();
			}
		} else {
			ob_start();
			$options = array(
				'-1' => __( 'Choose...', 'convertpro-addon' ),
			);
			foreach ( $lists->lists as $offset => $list ) {
				// @codingStandardsIgnoreStart
				$options[ $list->listId ] = $list->name;
				// @codingStandardsIgnoreEnd
			}
		}

		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['hubspot_list_id'] ) ) ? $settings['default']['hubspot_list_id'] : '' ) : '';
		}

		// Render the list field.
		ConvertPlugHelper::render_input_html(
			'hubspot_list_id', array(
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
	 * Subscribe an email address to Hubspot.
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
			$response['error'] = __( 'There was an error subscribing to HubSpot! The account is no longer connected.', 'convertpro-addon' );
		} else {

				$data = array(
					'email' => $email,
				);
			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$data[ $settings['meta'][ $key ] ] = $p;
					} else {
						$data[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			try {

				$contacts        = new CPRO_HubSpot_Contacts( $account_data['api_key'] );
				$created_contact = $contacts->create_contact( $data );

				if ( isset( $created_contact->{'status'} ) && 'error' == $created_contact->{'status'} ) {

					// @codingStandardsIgnoreStart
					$contact_profile = isset( $created_contact->identityProfile ) ? $created_contact->identityProfile : '';
					$contact_id = isset( $contact_profile->vid ) ? $contact_profile->vid : '';
					// @codingStandardsIgnoreEnd

					if ( '' != $contact_id ) {
						$contacts->update_contact( $contact_id, $data );
					} else {

						$response['error'] = __( 'There was an error subscribing to HubSpot!', 'convertpro-addon' );
					}
				} else {
					// @codingStandardsIgnoreStart
					$contact_id = $created_contact->{'vid'};
					// @codingStandardsIgnoreEnd
				}

				$lists           = new CPRO_HubSpot_Lists( $account_data['api_key'] );
				$contacts_to_add = array( $contact_id );

				$add_res = $lists->add_contacts_to_list( $contacts_to_add, $settings['hubspot_list_id'] );
				$add_res = json_decode( $add_res );

				if ( isset( $add_res->status ) ) {
					if ( 'error' == $add_res->status ) {
						$response['error'] = __( 'There was an error subscribing to HubSpot.', 'convertpro-addon' );
					}
				}
			} catch ( Exception $e ) {

				$response['error'] = __( 'There was an error subscribing to HubSpot.', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
