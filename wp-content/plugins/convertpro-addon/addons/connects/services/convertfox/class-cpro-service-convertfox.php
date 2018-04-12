<?php
/**
 * ConverPlug Service ConvertFox
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the ConvertFox API.
 *
 * @since 1.0.0
 */
final class CPRO_Service_Convertfox extends CPRO_Service {

	/**
	 * The ID for this service.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = 'convertfox';

	/**
	 * Default Custom field array.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public static $mapping_fields = array(
		'name',
		'first_name',
		'last_name',
		'username',
		'description',
		'phone',
		'company',
		'company_name',
		'company_position',
	);

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
	 * @param string $project_id Project ID.
	 * @return object The API instance.
	 */
	public function get_api( $project_id ) {
		if ( $this->api_instance ) {
			return $this->api_instance;
		}

		$auth['project_id'] = $project_id;

		if ( file_exists( CP_SERVICES_BASE_DIR . 'includes/vendor/convertfox/convertfox.php' ) ) {
			require_once CP_SERVICES_BASE_DIR . 'includes/vendor/convertfox/convertfox.php';
		}

		if ( class_exists( 'CPRO_ConvertFox' ) ) {
			$this->api_instance = new CPRO_ConvertFox( $auth );
		}

		return $this->api_instance;
	}

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields {.
	 *      @type array $fields authentication fields.
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

		if ( ! isset( $fields['project_id'] ) || empty( $fields['project_id'] ) ) {
			$response['error'] = __( 'Error: You must provide a Project ID.', 'convertpro-addon' );
		} else {
			$response['data'] = array(
				'project_id' => $fields['project_id'],
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
			'project_id', array(
				'class' => 'cp_project_id',
				'type'  => 'text',
				'label' => __( 'Project ID', 'convertpro-addon' ),
				'help'  => __( 'Your project ID can be found in your ConvertFox account under Settings.', 'convertpro-addon' ),
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
			'project_id' => $authmeta['project_id'],
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
		$response = array(
			'error'          => false,
			'html'           => '',
			'mapping_fields' => self::$mapping_fields,
		);

		$response['html'] .= __( '<div class="convertfox-nolist-wrap">Convertfox does not require list or tags, you can directly move to mapping fields by clicking next button.</div>', 'convertpro-addon' );

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
	 * Subscribe an email address to ConvertFox.
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
		$api      = $this->get_api( $account['project_id'] );
		$response = array(
			'error' => false,
		);

		if ( ! $account ) {
			$response['error'] = __( 'There was an error subscribing to ConvertFox! The account is no longer connected.', 'convertpro-addon' );
		} else {

			$custom_arr = array();
			foreach ( $settings['param'] as $key => $p ) {

				if ( 'email' != $key && 'date' != $key ) {
					if ( 'custom_field' != $settings['meta'][ $key ] ) {
						$custom_arr[ $settings['meta'][ $key ] ] = $p;
					} else {
						$custom_arr[ $settings['meta'][ $key . '-input' ] ] = $p;
					}
				}
			}

			$custom_arr['email'] = $email;

			$custom_arr            = json_encode( $custom_arr );
			$response['cfox_data'] = $custom_arr;

		}
		return $response;
	}
}
