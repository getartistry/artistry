<?php
/**
 * ConverPlug Service Mailster
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Mailster.
 *
 * @since 1.0.0
 */
final class CPRO_Service_MyMail extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'mymail';

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		add_filter( 'cp_static_account_service', array( $this, 'cp_static_account_service' ) );
	}

	/**
	 * Filter callback function.
	 *
	 * @param array $string String.
	 * @since 1.0.0
	 * @return string $string
	 */
	public function cp_static_account_service( $string ) {
		$string = $this->$id;
		return $string;
	}

	/**
	 * Default Custom field array.
	 * This is predefined custom fields array that mailster
	 * has already defined. When mailster releases the new
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
		if ( ! class_exists( 'Mailster' ) ) {
			$response['error'] = __( 'Error: Mailster integration requires Mailster Newsletter plugin installed and activated.', 'convertpro-addon' );
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
	 * @param @type string $auth_meta valid credentials.
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

		$account_data = ConvertPlugServices::get_account_data( $account );
		if ( ! class_exists( 'Mailster' ) ) {
			$response = array(
				'error' => __( 'Error: The Mailster connects addon requires Mailster Newsletter plugin installed and activated.', 'convertpro-addon' ),
			);
			return $response;
		}
		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);
		if ( class_exists( 'MailsterLists' ) ) {
			$list_obj = new MailsterLists;
			$lists    = $list_obj->get();
			if ( null == $lists ) {
				$response['error'] = __( 'No lists found in your Mailster account.', 'convertpro-addon' );
			} else {
				$response['html'] = $this->render_list_field( $lists, $settings );
			}
		}
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

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mailster_list_id'] ) ) ? $settings['default']['mailster_list_id'] : '' ) : '';
		}
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		foreach ( $lists as $list ) {
			$options[ $list->ID ] = $list->name;
		}
		ConvertPlugHelper::render_input_html(
			'mailster_list_id', array(
				'multiple' => 'multiple',
				'class'    => 'cpro-select',
				'type'     => 'select',
				'label'    => _x( 'List', 'An email list from a third party provider.', 'convertpro-addon' ),
				'default'  => $default,
				'options'  => $options,
			), $settings
		);

		if ( $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['mailster_double_optin'] ) ) ? $settings['default']['mailster_double_optin'] : '' ) : '';
		}

		ConvertPlugHelper::render_input_html(
			'mailster_double_optin', array(
				'class'   => '',
				'type'    => 'checkbox',
				'label'   => __( 'Enable Double Opt-in', 'convertpro-addon' ),
				'help'    => '',
				'default' => $default,
				'options' => $options,
			)
		);
		return ob_get_clean();
	}

	/**
	 * Subscribe an email address to Mailster.
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

		if ( ! class_exists( 'Mailster' ) ) {
			$response['error'] = __( 'Error: Mailster integration requires Mailster Newsletter plugin installed and activated.', 'convertpro-addon' );
		} else {

			$subs = new MailsterSubscribers();

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] && '' != $p ) {
						$custom_field              = $settings['meta'][ $key . '-input' ];
						$userdata[ $custom_field ] = $p;
					} else {
						if ( '' != $p ) {
							$userdata[ $settings['meta'][ $key ] ] = $p;
						}
					}
				}
			}

			if ( true == $settings['mailster_double_optin'] ) {
				$optin = 0;
			} elseif ( null == $settings['mailster_double_optin'] ) {
				$optin = 1;
			}

			$userdata['status'] = $optin;
			$userdata['email']  = $email;

			if ( '-1' != $settings['mailster_list_id'] ) {
				$lists = array(
					'0' => $settings['mailster_list_id'],
				);
			}
			if ( is_array( $userdata ) && ! empty( $userdata ) ) {
				$subscriber_id = $subs->add( $userdata );
			}

			if ( empty( $subscriber_id ) ) {
				$response['error'] = __( 'Something went wrong! Please try again.', 'convertpro-addon' );
			} else {
				$list_res = $subs->assign_lists( $subscriber_id, $lists );
			}

			if ( empty( $list_res ) ) {
				$response['error'] = __( 'Something went wrong! Subscriber was not added to the list.', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
