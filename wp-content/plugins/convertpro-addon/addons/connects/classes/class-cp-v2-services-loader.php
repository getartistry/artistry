<?php
/**
 * Convert Pro Addon loader file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cp_V2_Services_Loader' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class Cp_V2_Services_Loader {

		/**
		 * Store instance
		 *
		 * @since 1.0.0
		 * @var object $instance
		 * @access private
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {

			$this->define_constants();
			$this->load_files();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_loaded', array( $this, 'render' ), 999 );
			add_action( 'cp_after_email_template_setting', array( $this, 'email_template_setting' ) );
			add_action( 'wp_footer', __CLASS__ . '::covertfox_script' );
		}


		/**
		 * Add Convert Fox script in the footer.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function covertfox_script() {
		?>	
			<script type="text/javascript">

				jQuery(document).on( "cp_after_form_submit", function( e, element, response
					, style_slug ) {

					if( false == response.data.error ) {

						if( 'undefined' !== typeof response.data['cfox_data']  ) {
							var form_data = JSON.parse( response.data['cfox_data']  );

							if( 'undefined' !== typeof convertfox ) {
								convertfox.identify( form_data );
							}
						}
					}

				});


			</script>
		<?php
		}

		/**
		 * Email Template Setting page.
		 */
		public function email_template_setting() {
			$email_template     = get_option( 'cp_failure_email_template' );
			$email_template_sbj = get_option( 'cp_failure_email_subject' );

			if ( isset( $email_template_sbj ) && '' != $email_template_sbj ) {
				$subject = $email_template_sbj;
			} else {
				/* translators: %s Product name */
				$subject = sprintf( __( 'Important Notification! - [SITE_NAME] - %s [MAILER_SERVICE_NAME] configuration error', 'convertpro-addon' ), CPRO_BRANDING_NAME );
			}

			if ( isset( $email_template ) && '' != $email_template ) {
				$template = $email_template;
			} else {
				$template = 'The design <strong>[DESIGN_NAME]</strong> integrated with <strong>[MAILER_SERVICE_NAME]</strong> is not working! The following error occured when a user tried to subscribe - \n\n[ERROR_MESSAGE]\n\nPlease check <a href="[DESIGN_LINK]" target="_blank" rel="noopener">configuration</a> settings ASAP.\n\n ----- \n\n The details of the subscriber are given below.\n\n [FORM_SUBMISSION_DATA] \n\n ----- \n\n [ [SITE_NAME] - [SITE_URL] ]';
				$template = str_replace( '\n', "\n", $template );
			}

			ob_start();
			?>
			<h3 class="cp-gen-set-title cp-error-services-title"><?php _e( 'Error Notification', 'convertpro-addon' ); ?></h3>
			<p>
			<?php
			_e( 'This is an email that will be sent to you every time a user subscribes through a form and some error is encountered. You can customize the email subject and body in the fields below. ', 'convertpro-addon' );
			_e( '<strong>Note:</strong> This is applicable when you integrate with some mailer service.', 'convertpro-addon' );
			?>
			</p>
			<table class="cp-postbox-table form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="option-admin-menu-subject-page"><?php _e( 'Template Subject', 'convertpro-addon' ); ?></label>
						</th>
						<td>
							<input type="text" id="cp_failure_email_subject" name="cp_failure_email_subject" value="<?php echo stripslashes( $subject ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="option-admin-menu-template-page"><?php _e( 'Template', 'convertpro-addon' ); ?></label>
						</th>
						<td>
							<textarea id="cp_failure_email_template" name="cp_failure_email_template" rows="10" cols="50" ><?php echo stripslashes( $template ); ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			echo ob_get_clean();
		}

		/**
		 * Renders an admin scripts.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function admin_scripts() {

			$dev_mode = get_option( 'cp_dev_mode' );

			if ( '1' == $dev_mode ) {
				wp_register_script( 'cp-services', CP_SERVICES_BASE_URL . '/assets/js/cp-services.js', array( 'jquery' ), time(), true );
			} else {
				wp_register_script( 'cp-services', CP_SERVICES_BASE_URL . '/assets/js/cp-services.min.js', array( 'jquery' ), time(), true );
			}
			wp_enqueue_style( 'css-services', CP_SERVICES_BASE_URL . 'assets/css/cp-services.css' );
			wp_enqueue_script( 'cp-services' );
			wp_localize_script(
				'cp-services', 'cp_services',
				array(
					'image_base_url'     => CP_SERVICES_BASE_URL . 'assets/images/',
					'url'                => admin_url( 'admin-ajax.php' ),
					'wrong'              => __( 'Oh sorry! Something went wrong!', 'convertpro-addon' ),
					'cant_delete'        => __( 'You cannot delete this account. It is already associated with a design.', 'convertpro-addon' ),
					'confirm_delete'     => __( 'This "##account_name##" account will be deleted permanently. Do you wish to continue?', 'convertpro-addon' ),
					'confirm_remove'     => __( 'Are you sure you want to remove this?', 'convertpro-addon' ),
					'select_account'     => __( 'Please select a valid account. Or go ahead and create a new account.', 'convertpro-addon' ),
					'placeholder'        => __( 'Enter "Name" of the field', 'convertpro-addon' ),
					'no_email'           => __( 'Oops! You did not add an email field! We will not be able to pass values without an email field.', 'convertpro-addon' ),
					'only_email'         => __( 'All done! Please save the changes & publish.', 'convertpro-addon' ),
					'no_input'           => __( 'Oops! Your design do not have form fields. You don\'t need integration.', 'convertpro-addon' ),
					'custom_field'       => __( 'Custom Field', 'convertpro-addon' ),
					'select_option'      => __( '--- Select Value ---', 'convertpro-addon' ),
					'valid_list'         => __( 'Please select a valid list.', 'convertpro-addon' ),
					'valid_form'         => __( 'Please select a valid form.', 'convertpro-addon' ),
					'list_or_form'       => __( 'Please select the kind of integration you want; A list or a form.', 'convertpro-addon' ),
					'valid_drip_account' => __( 'Please select a valid Drip account.', 'convertpro-addon' ),
					'valid_sequence'     => __( 'Please select a valid sequence.', 'convertpro-addon' ),
					'valid_list_id'      => __( 'Please enter a valid list ID.', 'convertpro-addon' ),
					'valid_client'       => __( 'Please select a client from the dropdown menu.', 'convertpro-addon' ),
					'valid_tag'          => __( 'Please select at least a single tag.', 'convertpro-addon' ),
					/* translators: %s Product name */
					'cp_fields'          => sprintf( __( '%s Fields', 'convertpro-addon' ), CPRO_BRANDING_NAME ),
					'mailer_fields'      => __( 'Mailer Fields', 'convertpro-addon' ),
					'mapping_notice'     => __( 'The following fields are not mapped with your <b>##mailer_name##</b> account. Please map those fields from <b>Connect > ##account_name##</b>. <br><br><i>If left unmapped, you will miss out the data entered in these fields.</i>', 'convertpro-addon' ),
				)
			);
		}

		/**
		 * Define constants.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function define_constants() {

			define( 'CP_SERVICES_BASE_DIR', CP_ADDON_DIR . 'addons/connects/' );
			define( 'CP_SERVICES_BASE_URL', CP_ADDON_URL . 'addons/connects/' );
			define( 'CP_API_CONNECTION_SERVICE', '_cp_api_connection_service' );
			define( 'CP_API_CONNECTION_SERVICE_AUTH', '_cp_api_connection_service_auth' );
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static private function load_files() {
			/* Classes */
			require_once CP_SERVICES_BASE_DIR . 'classes/class-convertplugservices.php';
			require_once CP_SERVICES_BASE_DIR . 'classes/class-cpro-ajax.php';
			require_once CP_SERVICES_BASE_DIR . 'classes/class-convertplughelper.php';
		}


		/**
		 * Callback frunction to API call.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function render() {
			$get_data     = $_GET;
			$redirect_url = '';

			if ( isset( $get_data['action'] ) && 'convertpro-mautic' == $get_data['action'] ) {
				$_mautic_credentials = get_option( '_cp_service_mautic_credentials' );

				if ( ! isset( $_mautic_credentials['baseUrl'] ) ) {
					return;
				}

				$url  = trailingslashit( $_mautic_credentials['baseUrl'] ) . 'oauth/v2/token';
				$body = array(
					'client_id'     => $_mautic_credentials['clientKey'],
					'client_secret' => $_mautic_credentials['clientSecret'],
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $_mautic_credentials['callback'],
					'sslverify'     => false,
					'code'          => sanitize_text_field( $get_data['code'] ),
				);

				// Request to get access token.
				$curl_response = wp_remote_post(
					$url, array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => $body,
						'cookies'     => array(),
					)
				);

				$response_body                = wp_remote_retrieve_body( $curl_response );
				$access_details               = json_decode( $response_body );
				$expiration                   = time() + $access_details->expires_in;
				$credentials                  = $_mautic_credentials;
				$credentials['access_token']  = $access_details->access_token;
				$credentials['expires_in']    = $expiration;
				$credentials['access_code']   = sanitize_text_field( $get_data['code'] );
				$credentials['refresh_token'] = $access_details->refresh_token;
				$credentials['is_form']       = 'api';

				update_option( '_cp_service_mautic_credentials', $credentials );

				$account = $_mautic_credentials['service_account'];
				$service = 'mautic';

				$response = array(
					'error' => false,
					'html'  => '',
				);

				if ( '' != $account ) {
					$response = $this->add_term( $account, 'mautic' );
				} else {
					$response = array(
						'error'   => __( 'Account Name should not be blank.', 'convertpro-addon' ),
						'html'    => '',
						'term_id' => -1,
					);
				}

				if ( ! $response['error'] ) {
					$redirect_url = urldecode( $get_data['redirect_url'] ) . '&open_connects=true&service=mautic&account=' . $response['term_id'] . '#connect';
					wp_redirect( $redirect_url );
					exit;
				}
				exit;
			} elseif ( isset( $get_data['code'] ) && '' != $get_data['code'] ) {

				$_verticalresponse_credentials = get_option( '_cp_service_verticalresponse_credentials' );

				if ( ! isset( $_verticalresponse_credentials['api_key'] ) ) {
					return;
				}
				$credentials         = $_verticalresponse_credentials;
				$credentials['code'] = $get_data['code'];

				$url  = $_verticalresponse_credentials['root_url'] . 'v1/oauth/access_token';
				$url .= '?client_id=' . $_verticalresponse_credentials['api_key'];
				$url .= '&client_secret=' . $_verticalresponse_credentials['secret_key'];
				$url .= '&redirect_uri=' . admin_url( '&code=' . sanitize_text_field( $get_data['code'] ) );

				// Request to get access token.
				$curl_response  = wp_remote_get( $url );
				$response_body  = wp_remote_retrieve_body( $curl_response );
				$access_details = json_decode( $response_body );

				if ( ! empty( $access_details ) ) {
					$credentials['user_id']      = $access_details->user_id;
					$credentials['access_token'] = $access_details->access_token;
					$credentials['token_type']   = $access_details->token_type;
					update_option( '_cp_service_verticalresponse_credentials', $credentials );

					$account = $_verticalresponse_credentials['service_account'];
					$service = 'verticalresponse';

					$response = array(
						'error' => false,
						'html'  => '',
					);

					if ( '' != $account ) {
						$response = $this->add_term( $account, 'verticalresponse' );
					} else {
						$response = array(
							'error'   => __( 'Account Name should not be blank.', 'convertpro-addon' ),
							'html'    => '',
							'term_id' => -1,
						);
					}

					if ( ! $response['error'] ) {
						$redirect_url = $_verticalresponse_credentials['redirect_url'] . '&open_connects=true&service=verticalresponse&account=' . $response['term_id'] . '#connect';
						wp_redirect( $redirect_url );
						exit;
					}
				}
				exit;
			}
		}

		/**
		 * Adds term taxonomy.
		 *
		 * @since 1.0.0
		 * @param array  $account Account.
		 * @param string $service Service slug.
		 * @return array $response Responce array
		 */
		public function add_term( $account, $service ) {
			$response = array(
				'error' => false,
				'html'  => '',
			);

			$term = wp_insert_term( $account, CP_CONNECTION_TAXONOMY );

			if ( ! is_wp_error( $term ) ) {

				$newterm = update_term_meta( $term['term_id'], CP_API_CONNECTION_SERVICE, $service );

				$auth_meta = get_option( '_cp_service_' . $service . '_credentials' );

				update_term_meta( $term['term_id'], CP_API_CONNECTION_SERVICE_AUTH, $auth_meta );
				$t                   = get_term( $term['term_id'], CP_CONNECTION_TAXONOMY );
				$response['term_id'] = $t->slug;

			} else {
				$response = array(
					'error'   => $term->get_error_message(),
					'html'    => '',
					'term_id' => -1,
				);
			}

			return $response;
		}
	}

	$service_loader = Cp_V2_Services_Loader::get_instance();
}
