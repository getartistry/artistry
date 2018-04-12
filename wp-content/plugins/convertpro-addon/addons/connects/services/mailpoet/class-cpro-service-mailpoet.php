<?php
/**
 * ConverPlug Service MailPoet
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the MailPoet API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_MailPoet extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mailpoet';

	/**
	 * The version for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $is_mailpoet3 = false;

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		add_filter( 'cp_static_account_service', array( $this, 'cp_static_account_service' ) );
		$this->is_mailpoet3 = false;

		if ( defined( 'MAILPOET_INITIALIZED' ) && MAILPOET_INITIALIZED ) {
			$this->is_mailpoet3   = true;
			self::$mapping_fields = array( 'first_name', 'last_name' );
		}
	}

	/**
	 * Filter callback function.
	 *
	 * @param string $string MailPoet slug.
	 * @since 1.0.0
	 * @return string $string MailPoet slug.
	 */
	public function cp_static_account_service( $string ) {
		$string = $this->$id;
		return $string;
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
	public static $mapping_fields = array( 'firstname', 'lastname' );

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields A valid API credentials.
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

		if ( ! class_exists( 'WYSIJA' ) && ! ( defined( 'MAILPOET_INITIALIZED' ) && MAILPOET_INITIALIZED ) ) {

			$response['error'] = __( 'Error: MailPoet connects addon requires MailPoet Newsletter plugin installed and activated.', 'convertpro-addon' );
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
		return ob_get_clean();
	}

	/**
	 * Returns boolean.
	 *
	 * @since 1.0.0
	 * @param @type string $auth_meta A valid API credentials.
	 * @return bool
	 */
	public function render_auth_meta( $auth_meta ) {
		return true;
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

		if ( ! class_exists( 'WYSIJA' ) && ! ( defined( 'MAILPOET_INITIALIZED' ) && MAILPOET_INITIALIZED ) ) {

			$response = array(
				'error' => __( 'Error: MailPoet connects addon requires MailPoet Newsletter plugin installed and activated.', 'convertpro-addon' ),
			);
			return $response;
		}

		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		if ( $this->is_mailpoet3 ) {
			$lists = \MailPoet\API\API::MP( 'v1' )->getLists();
		} else {
			$model_list = WYSIJA::get( 'list', 'model' );
			$lists      = $model_list->get(
				array( 'name', 'list_id' ), array(
					'is_enabled' => 1,
				)
			);
		}

		if ( count( $lists ) == 0 ) {
			$response['error'] = __( 'No lists found in your MailPoet account.', 'convertpro-addon' );
		}
		$response['html']  = $this->render_list_field( $lists, $settings );
		$response['html'] .= $this->render_optin_field( $settings );
			return $response;
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
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		$default = '';
		foreach ( $lists as $list ) {
			if ( $this->is_mailpoet3 ) {
				$options[ $list['id'] ] = $list['name'];
			} else {
				$options[ $list['list_id'] ] = $list['name'];
			}
		}

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['list_id'] ) ) ? $settings['default']['list_id'] : '' ) : '';
		}
		ConvertPlugHelper::render_input_html(
			'list_id', array(
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
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array $settings Posted data.
	 * @return string The markup for the list field.
	 * @access private
	 */
	private function render_optin_field( $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mailpoet_double_optin'] ) ) ? $settings['default']['mailpoet_double_optin'] : '' ) : '';
		}

		ob_start();

		ConvertPlugHelper::render_input_html(
			'mailpoet_double_optin', array(
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
	 * Check if list id is present in given list
	 *
	 * @since 1.0.0
	 * @param int   $list_id list id.
	 * @param array $list Optional. The full name of the person subscribing.
	 * @return boolean
	 */
	public function check_if_mailpoet_list_exists( $list_id = 0, $list = array() ) {
		if ( ! empty( $list ) && 0 != $list_id ) {
			foreach ( $list as $l ) {

				$l_id = ( $this->is_mailpoet3 ) ? $l['id'] : $l['list_id'];

				if ( $l_id == $list_id ) {
					return true;
				}
			}
		}
		return false;
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
	 * Subscribe an email address to MailPoet.
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

		$response = array(
			'error' => false,
		);

		if ( ! class_exists( 'WYSIJA' ) && ! ( defined( 'MAILPOET_INITIALIZED' ) && MAILPOET_INITIALIZED ) ) {

			$response['error'] = __( 'Error: MailPoet connects addon requires MailPoet Newsletter plugin installed and activated.', 'convertpro-addon' );
			return $response;
		}

		$list_id = $settings['list_id'];

		if ( ! $list_id ) {
			$response['error'] = __( 'There was an error subscribing to MailPoet! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$response = array(
				'error' => false,
			);

			$merge_arr     = array();
			$custom_fields = array();
			$data_fields   = array();

			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$merge_arr[ $settings['meta'][ $key ] ] = $p;
					} else {

						$merge_arr[ $settings['meta'][ $key . '-input' ] ]     = $p;
						$custom_fields[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			$user_fields = array(
				'email' => $email,
			);

			// Map default fields.
			$default_fields = self::$mapping_fields;
			foreach ( $default_fields as $val ) {

				if ( isset( $merge_arr[ $val ] ) ) {

					$user_fields[ $val ] = $merge_arr[ $val ];
				}
			}

			$data = array(
				'user'      => $user_fields,
				'user_list' => array(
					'list_ids' => array( $list_id ),
				),
			);

			if ( $this->is_mailpoet3 ) {
				$mailpoet_lists = \MailPoet\API\API::MP( 'v1' )->getLists();
			} else {
				$model_list     = WYSIJA::get( 'list', 'model' );
				$mailpoet_lists = $model_list->get(
					array( 'name', 'list_id' ), array(
						'is_enabled' => 1,
					)
				);
			}
			$list_exist = $this->check_if_mailpoet_list_exists( $list_id, $mailpoet_lists );

			if ( $this->is_mailpoet3 ) {
				$mp_fields = \MailPoet\API\API::MP( 'v1' )->getSubscriberFields();
			} else {
				$mp_fields = WJ_Field::get_all();
			}

			foreach ( $mp_fields as $f ) {
				$fid   = ( $this->is_mailpoet3 ) ? $f['id'] : $f->id;
				$fname = ( $this->is_mailpoet3 ) ? $f['name'] : $f->name;

				if ( isset( $custom_fields[ $fname ] ) ) {
					$data_fields[ 'cf_' . $fid ] = $custom_fields[ $fname ];
				}
			}

			$subscriber_id = null;
			if ( $list_exist ) {

				if ( $this->is_mailpoet3 ) {
					try {
						// List should be in array format.
						$lists   = array( $list_id );
						$double  = ( isset( $settings['mailpoet_double_optin'] ) ) ? true : false;
						$options = array(
							'send_confirmation_email' => $double, // Default value is true.
							'schedule_welcome_email'  => $double, // Default value is true.
						);

						try {
							$subscriber_data = \MailPoet\API\API::MP( 'v1' )->getSubscriber( $user_fields['email'] );
						} catch ( Exception $e ) {
							// New subscriber.
						}

						if ( is_array( $subscriber_data ) && isset( $subscriber_data['id'] ) ) {
							$subscriber_id = $subscriber_data['id'];
						}

						// New user.
						if ( null == $subscriber_id ) {
							$subscriber_id = \MailPoet\API\API::MP( 'v1' )->addSubscriber( $user_fields, $lists, $options );
						} else { // Existing user.

							$subscriber_id = \MailPoet\API\API::MP( 'v1' )->subscribeToLists( $subscriber_id, $lists );
						}
					} catch ( Exception $e ) {
						$response['error'] = $e->getMessage();
					}
				} else {
					$subscriber_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data );
				}
			}
			if ( ! $subscriber_id ) {
				$response['error'] = __( 'Something went wrong! Please try again.', 'convertpro-addon' );
			} else {
				// update custom subscriber fields.
				if ( ! empty( $data_fields ) ) {
					WJ_FieldHandler::handle_all( $data_fields, $subscriber_id );
				}
			}
		}
		return $response;
	}
}
