<?php
/**
 * ConverPlug Service SendReach
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the SendReach API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_SendReach extends CPRO_Service {

	/**
	 * Initialize Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/MailWizzApi/Autoloader.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/MailWizzApi/Autoloader.php';
		}

		$this->components = array(
			'cache' => array(
				'class'     => 'MailWizzApi_Cache_File',
				'filesPath' => CP_SERVICES_BASE_DIR . 'includes\vendor\MailWizzApi\Cache\data\cache',
			),
		);
	}

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'sendreach';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array();

	/**
	 * API URL.
	 *
	 * @since 1.0.0
	 * @var object $api_url
	 * @access private
	 */
	private $api_url = 'https://dashboard.sendreach.com/api/index.php';

	/**
	 * Components.
	 *
	 * @since 1.0.0
	 * @var object $components
	 * @access private
	 */
	private $components = array();

	/**
	 * Object.
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
	 * @param string $pub_key A valid Public Key.
	 * @param string $priv_key A valid Private Key.
	 * @return object The API instance.
	 */
	public function get_api( $pub_key, $priv_key ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}
		if ( class_exists( 'MailWizzApi_Autoloader' ) ) {

			MailWizzApi_Autoloader::register();
			if ( class_exists( 'MailWizzApi_Config' ) ) {
				$this->api_instance = new MailWizzApi_Config(
					array(
						'apiUrl'     => $this->api_url,
						'publicKey'  => $pub_key,
						'privateKey' => $priv_key,
						'components' => $this->components,
					)
				);
			}
		}
		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type string $pub_key A valid public Key.
	 *      @type string $priv_key A valid Private Key.
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

		// Make sure we have an Public Key.
		if ( ! isset( $fields['pub_key'] ) || empty( $fields['pub_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a Public Key.', 'convertpro-addon' );
		} // Make sure we have an Private Key.
		elseif ( ! isset( $fields['priv_key'] ) || empty( $fields['priv_key'] ) ) {
			$response['error'] = __( 'Error: You must provide a Private Key.', 'convertpro-addon' );
		} // Try to connect and store the connection data.
		else {
			try {
				MailWizzApi_Autoloader::register();
				$config = new MailWizzApi_Config(
					array(
						'apiUrl'     => $this->api_url,
						'publicKey'  => $fields['pub_key'],
						'privateKey' => $fields['priv_key'],
						'components' => $this->components,
					)
				);
				MailWizzApi_Base::setConfig( $config );
				$endpoint = new MailWizzApi_Endpoint_Lists();
				$res      = $endpoint->getLists( 1, 1000 );
				$status   = $res->body->itemAt( 'status' );

				if ( 'success' != $status ) {
					$response['error'] = __( 'Error: Please check your Public Key and Private Key.', 'convertpro-addon' );
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
			'pub_key', array(
				'class' => 'cp_sendreach_pub_key',
				'type'  => 'text',
				'label' => __( 'Public Key', 'convertpro-addon' ),
				'help'  => __( 'Your Public Key can be found in your SendReach account under Dashboard > API Keys.', 'convertpro-addon' ),
			)
		);

		ConvertPlugHelper::render_input_html(
			'priv_key', array(
				'class' => 'cp_sendreach_priv_key',
				'type'  => 'text',
				'label' => __( 'Secret Key', 'convertpro-addon' ),
				'help'  => __( 'Your Private key can be found in your SendReach account under Dashboard > API Keys.', 'convertpro-addon' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @param object $authmeta Authentication meta.
	 * @since 1.0.0
	 * @return string The connection settings markup.
	 */
	public function render_auth_meta( $authmeta ) {
		return array(
			'pub_key'  => $authmeta['pub_key'],
			'priv_key' => $authmeta['priv_key'],
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
		$api          = $this->get_api( $account_data['pub_key'], $account_data['priv_key'] );
		$response     = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		MailWizzApi_Autoloader::register();
		$config = new MailWizzApi_Config(
			array(
				'apiUrl'     => $this->api_url,
				'publicKey'  => $account_data['pub_key'],
				'privateKey' => $account_data['priv_key'],
				'components' => $this->components,
			)
		);

		MailWizzApi_Base::setConfig( $config );

		$endpoint = new MailWizzApi_Endpoint_Lists();
		$res      = $endpoint->getLists( 1, 1000 );
		$status   = $res->body->itemAt( 'status' );

		if ( 'success' != $status ) {
			return array();
		}

		$campaigns = $res->body->itemAt( 'data' );
		if ( $campaigns['count'] > 0 ) {
			$lists = array();
			foreach ( $campaigns['records'] as $offset => $cm ) {
				$lists[ $cm['general']['list_uid'] ] = $cm['general']['name'];
			}
		}

		if ( null != $lists ) {
			$response['html'] .= $this->render_list_field( $lists, $settings );
		} else {
			$response['error'] .= __( 'Error: No lists found in your SendReach account.', 'convertpro-addon' );
		}
		return $response;
	}

	/**
	 * Render markup for the list field.
	 *
	 * @since 1.0.0
	 * @param array $lists List data from the API.
	 * @param array $settings Settings data from the API.
	 * @return string The markup for the list field.
	 */
	private function render_list_field( $lists, $settings ) {

		$default = '';
		if ( isset( $settings['isEdit'] ) && $settings['isEdit'] ) {
			$default = ( isset( $settings['default'] ) ) ? ( ( isset( $settings['default']['sendreach_lists'] ) ) ? $settings['default']['sendreach_lists'] : '' ) : '';
		}
		ob_start();

		$options = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);
		foreach ( $lists as $id => $value ) {
			$options[ $id ] = $value;
		}
		ConvertPlugHelper::render_input_html(
			'sendreach_lists', array(
				'class'   => 'cpro-select',
				'type'    => 'select',
				'label'   => _x( 'List', 'A list from your SendReach Account.', 'convertpro-addon' ),
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
	 * Subscribe an email address to SendReach.
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
		$api      = $this->get_api( $account['pub_key'], $account['priv_key'] );
		$response = array(
			'error' => false,
		);

		if ( ! $api ) {
			$response['error'] = __( 'There was an error subscribing to SendReach! The account is no longer connected.', 'convertpro-addon' );
		} else {
			$custom_fields = array(
				'EMAIL' => $email,
			);

			foreach ( $settings['param'] as $key => $p ) {
				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' == $settings['meta'][ $key ] ) {
						$custom_fields[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			MailWizzApi_Autoloader::register();
			$config = new MailWizzApi_Config(
				array(
					'apiUrl'     => $this->api_url,
					'publicKey'  => $account['pub_key'],
					'privateKey' => $account['priv_key'],
					'components' => $this->components,
				)
			);

			MailWizzApi_Base::setConfig( $config );
			// Add subscribers.
			$endpoint = new MailWizzApi_Endpoint_ListSubscribers();
			$resp     = $endpoint->createUpdate( $settings['sendreach_lists'], $custom_fields );
			$status   = $resp->body->itemAt( 'status' );
			if ( 'success' != $status ) {
				$response['error'] = __( 'Something went wrong! Please try again.', 'convertpro-addon' );
			}
		}
		return $response;
	}
}
