<?php
namespace ElementorExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handles license input and validation
 */
class Namogo_Licensing {

	/**
	 * @var string  The license key for this installation
	 */
	private $license_key;

	/**
	 * The product id (slug) used for this product on the License Manager site.
	 * Configured through the class's constructor.
	 *
	 * @var int     The product id of the related product in the license manager.
	 */
	private $product_id;

	/**
	 * The text domain of the plugin or theme using this class.
	 * Populated in the class's constructor.
	 *
	 * @var String  The text domain of the plugin / theme.
	 */
	private $text_domain;

	/**
	 * The name of the product using this class. Configured in the class's constructor.
	 *
	 * @var int     The name of the product (plugin / theme) using this class.
	 */
	private $product_name;

	/**
	 * Initializes the license manager client.
	 */
	public function __construct( $product_id, $product_name, $text_domain ) {
		// Store setup data
		$this->license_key 		= $this->get_license_key();
		$this->license_status 	= $this->get_license_status();
		$this->product_id 		= $product_id;
		$this->text_domain 		= $text_domain;
		$this->product_name 	= $product_name;

		// Init
		$this->add_actions();
	}

	/**
	 * Adds actions required for class functionality
	 */
	public function add_actions() {
		if ( is_admin() ) {

			// Add the menu screen for inserting license information
			add_action( 'admin_menu', 		array( $this, 'add_license_settings_page' ), 201 );
			add_action( 'admin_init', 		array( $this, 'register_license_settings' ) );
			add_action( 'admin_init', 		array( $this, 'activate_license' ) );
			add_action( 'admin_init', 		array( $this, 'deactivate_license' ) );
			add_action( 'admin_notices', 	array( $this, 'admin_notices' ) );
		}
	}

	/**
	 * Creates the settings items for entering license information (email + license key).
	 *
	 * NOTE:
	 * If you want to move the license settings somewhere else (e.g. your theme / plugin
	 * settings page), we suggest you override this function in a subclass and
	 * initialize the settings fields yourself. Just make sure to use the same
	 * settings fields so that Nmg_License_Manager_Client can still find the settings values.
	 */
	public function add_license_settings_page() {

		add_submenu_page(
			'',
			__( 'Extras License', $this->text_domain ),
			__( 'Extras License', $this->text_domain ),
			'manage_options',
			$this->get_settings_page_slug(),
			[ $this, 'render_licenses_page' ]
		);

	}

	/**
	 * Creates the settings fields needed for the license settings menu.
	 */
	function register_license_settings() {
		// creates our settings in the options table
		register_setting( $this->get_settings_page_slug(), $this->product_id . '_license_key', 'sanitize_license' );
	}

	function sanitize_license( $new ) {
		$old = get_option( $this->product_id . '_license_key' );
		if ( $old && $old != $new ) {
			delete_option( $this->product_id . '_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}

	/**
	 * Renders the settings page for entering license information.
	 */
	public function render_licenses_page() {

		$license_key 	= $this->get_license_key();
		$status 		= $this->get_license_status();
		$title 			= sprintf( __( '%s License', $this->text_domain ), $this->product_name );

		?>
		<div class="wrap">
			<form method="post" action="options.php">

				<?php settings_fields( $this->get_settings_page_slug() ); ?>

				<h1><?php echo $title; ?></h1>

				<p><?php _e( 'On this page you can add your licensing information.', $this->text_domain ); ?></p>

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="<?php echo $this->product_id; ?>_license_key"><?php _e( 'License Key', $this->text_domain ); ?>:</label>
							</th>
							<td>
								<input <?php echo ( $status !== false && $status == 'valid' ) ? 'disabled' : ''; ?> id="<?php echo $this->product_id; ?>_license_key" name="<?php echo $this->product_id; ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( self::get_hidden_license_key() ); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top"></th>
							<td>
								<?php wp_nonce_field( $this->product_id . '_license_nonce', $this->product_id . '_license_nonce' ); ?>

								<?php if( $status !== false && $status == 'valid' ) { ?>
									<input type="hidden" name="<?php echo $this->product_id; ?>_license_deactivate" />
									<?php submit_button( __( 'Deactivate', $this->text_domain ), 'button-primary button-large', 'submit', false, array( 'class' => 'button button-primary' ) ); ?>
								<?php } else { ?>
									<input type="hidden" name="<?php echo $this->product_id; ?>_license_activate" />
									<?php submit_button( __( 'Activate', $this->text_domain ), 'button-primary button-large', 'submit', false, array( 'class' => 'button button-primary' ) ); ?>
								<?php } ?>
							</td>
						</tr>
					</tbody>
				</table>

			</form>
		</div>
	<?php
	}

	/**
	 * Renders the description for the settings section.
	 */
	public function render_settings_section() {
		printf( __( 'Insert your %s license information to enable future updates (including bug fixes and new features) and gain access to support.', $this->text_domain ), $this->product_name );
	}

	/**
	 * Renders the license key settings field on the license settings page.
	 */
	public function render_license_key_settings_field() {
		$settings_field_name = $this->get_settings_field_name();
		$options = get_option( $settings_field_name );
		?>
		<input type='text' name='<?php echo $settings_field_name; ?>[license_key]' value='<?php echo $options['license_key']; ?>' class='regular-text' />
	<?php
	}

	/**
	 * Renders the license key settings field on the license settings page.
	 */
	public function render_license_status_settings_field() {
		$settings_field_name = $this->get_settings_field_name();
		$options = get_option( $settings_field_name );
		$license_status = $options['license_status'];

		?>
		<!-- <input type="hidden" name="<?php echo $settings_field_name; ?>[license_status]" value='' -->

		<?php
		if ( $license_status !== false && $license_status === 'valid' ) { ?>
			<span class="title-count" style="background-color:#41DCAB"><?php _e( 'Active', $this->text_domain ); ?></span>
		<?php } else { ?>
			<span class="title-count" style="background-color:#d54e21"><?php _e( 'Inactive', $this->text_domain ); ?></span>
		<?php }
	}

	/**
	 * @return string   The slug id of the licenses settings page.
	 */
	protected function get_settings_page_slug() {
		return $this->product_id . '_license';
	}

	/**
	 * @return string   The name of the settings field storing all license manager settings.
	 */
	protected function get_settings_field_name() {
		return $this->product_id . '-license-settings';
	}

	/**
	 * Gets the currently set license key
	 *
	 * @return bool|string   The product license key, or false if not set
	 */
	public function get_license_key() {

		$license = get_option( $this->product_id . '_license_key' );

		if ( ! $license ) {
			// User hasn't saved the license to settings yet. No use making the call.
			return false;
		}

		return trim( $license );
	}

	/**
	 * Updates the license key option
	 *
	 * @return bool|string   The product license key, or false if not set
	 */
	public function set_license_key( $license_key ) {
		return update_option( $this->product_id . '_license_key', $license_key );
	}

	/**
	 * Gets the current license status
	 *
	 * @return bool|string   The product license key, or false if not set
	 */
	public function get_license_status() {

		$status = get_option( $this->product_id . '_license_status' );

		if ( ! $status ) {
			// User hasn't saved the license to settings yet. No use making the call.
			return false;
		}

		return trim( $status );
	}

	private function get_hidden_license_key() {
		$input_string = $this->get_license_key();

		$start = 5;
		$length = mb_strlen( $input_string ) - $start - 5;

		$mask_string = preg_replace( '/\S/', '*', $input_string );
		$mask_string = mb_substr( $mask_string, $start, $length );
		$input_string = substr_replace( $input_string, $mask_string, $start, $length );

		return $input_string;
	}

	/**
	 * Updates the license status option
	 *
	 * @return bool|string   The product license key, or false if not set
	 */
	public function set_license_status( $license_status ) {
		return update_option( $this->product_id . '_license_status', $license_status );
	}

	/**
	 * Validates the license and saves the license key in the database
	 *
	 * @return object|bool   The product data, or false if API call fails.
	 */
	public function activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[ $this->product_id . '_license_activate' ] ) ) {

			// run a quick security check
		 	if( ! check_admin_referer( $this->product_id . '_license_nonce', $this->product_id . '_license_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = $_POST[ $this->product_id . '_license_key' ];


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( NAMOGO_SL_ITEM_NAME ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( NAMOGO_STORE_URL,
				array(
					'timeout' 	=> 15,
					'sslverify' => false,
					'body' 		=> $api_params
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), NAMOGO_SL_ITEM_NAME );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}

				}

			}

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=' . $this->get_settings_page_slug() );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			$this->set_license_key( $license );
			$this->set_license_status( $license_data->license );

			wp_redirect( admin_url( 'admin.php?page=' . $this->get_settings_page_slug() ) );
			exit();
		}
	}

	/**
	 * Removed the license validation
	 *
	 * @return object|bool   The product data, or false if API call fails.
	 */
	function deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[ $this->product_id . '_license_deactivate' ] ) ) {

			// run a quick security check
		 	if( ! check_admin_referer( $this->product_id . '_license_nonce', $this->product_id . '_license_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = $this->get_license_key();


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( NAMOGO_SL_ITEM_NAME ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( NAMOGO_STORE_URL,
				array(
					'timeout' 	=> 15,
					'sslverify' => false,
					'body' 		=> $api_params
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'admin.php?page=' . $this->get_settings_page_slug() );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( $this->product_id . '_license_status' );
				delete_option( $this->product_id . '_license_key' );
			}

			wp_redirect( admin_url( 'admin.php?page=' . $this->get_settings_page_slug() ) );
			exit();

		}
	}

	/**
	 * Handles admin notices for errors and license activation
	 * 
	 * @since 0.1.0
	 */
	
	function admin_notices() {
		$status = $this->get_license_status();

		if ( $status === false || $status !== 'valid' ) {
			$msg = __( 'Please %1$sactivate your license%2$s key to enable updates for %3$s.', $this->text_domain );
			$msg = sprintf( $msg, '<a href="' . admin_url( 'admin.php?page=' . $this->get_settings_page_slug() ) . '">', '</a>', '<strong>' . $this->product_name . '</strong>' );
			?>
			<div class="notice notice-error">
				<p><?php echo $msg; ?></p>
			</div>
		<?php
		}

		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

			switch( $_GET['sl_activation'] ) {

				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

			}
		}
	}

}