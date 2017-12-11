<?php
/**
 * Plugin Name: SendinBlue Subscribe Form And WP SMTP
 * Plugin URI: https://www.sendinblue.com/?r=wporg
 * Description: Easily send emails from your WordPress blog using SendinBlue SMTP and easily add a subscribe form to your site
 * Version: 2.8.3
 * Author: SendinBlue
 * Author URI: https://www.sendinblue.com/?r=wporg
 * License: GPLv2 or later
 *
 * @package SIB
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Application entry point. Contains plugin startup class that loads on <i> sendinblue_init </i> action.
 */
if ( ! class_exists( 'Mailin' ) ) {
	require_once( 'inc/mailin.php' );
}
// For marketing automation.
if ( ! class_exists( 'Sendinblue' ) ) {
	require_once( 'inc/sendinblue.php' );
}

if ( ! class_exists( 'SIB_Manager' ) ) {
	register_deactivation_hook( __FILE__, array( 'SIB_Manager', 'deactivate' ) );
	register_activation_hook( __FILE__, array( 'SIB_Manager', 'install' ) );
	register_uninstall_hook( __FILE__, array( 'SIB_Manager', 'uninstall' ) );

	require_once( 'page/page-home.php' );
	require_once( 'page/page-form.php' );
	require_once( 'page/page-lists.php' );
	require_once( 'page/page-campaigns.php' );
	require_once( 'page/page-statistics.php' );
	require_once( 'page/page-scenarios.php' );
	require_once( 'widget/widget_form.php' );
	require_once( 'inc/table-forms.php' );
	require_once( 'inc/sib-api-manager.php' );
	require_once( 'model/model-forms.php' );
	require_once( 'model/model-users.php' );
	require_once( 'model/model-lang.php' );

	/**
	 * Class SIB_Manager
	 */
	class SIB_Manager {

		/** Main setting option name */
		const MAIN_OPTION_NAME = 'sib_main_option';

		/** Home setting option name */
		const HOME_OPTION_NAME = 'sib_home_option';

		/** Access token option name */
		const ACCESS_TOKEN_OPTION_NAME = 'sib_token_store';

		/** Plugin language notice option name */
		const LANGUAGE_OPTION_NAME = 'sib_language_notice_option';

		/** Temp list of Dopt option name */
		const TEMPLIST_OPTION_NAME = 'sib_temp_list';

		/** Form preview option name */
		const PREVIEW_OPTION_NAME = 'sib_preview_form';

		/** Request url of sendinblue api */
		const SENDINBLUE_API_URL = 'https://api.sendinblue.com/v2.0';

		/**
		 * API key
		 *
		 * @var $access_key
		 */
		public static $access_key;

		/**
		 * Store instance
		 *
		 * @var $instance
		 */
		public static $instance;

		/**
		 * Plugin directory path value. set in constructor
		 *
		 * @var $plugin_dir
		 */
		public static $plugin_dir;

		/**
		 * Plugin url. set in constructor
		 *
		 * @var $plugin_url
		 */
		public static $plugin_url;

		/**
		 * Plugin name. set in constructor
		 *
		 * @var $plugin_name
		 */
		public static $plugin_name;

		/**
		 * Check if wp_mail is declared
		 *
		 * @var $wp_mail_conflict
		 */
		static $wp_mail_conflict;

		/**
		 * Class constructor
		 * Sets plugin url and directory and adds hooks to <i>init</i>. <i>admin_menu</i>
		 */
		function __construct() {
			// get basic info.
			self::$plugin_dir = plugin_dir_path( __FILE__ );
			self::$plugin_url = plugins_url( '', __FILE__ );
			self::$plugin_name = plugin_basename( __FILE__ );

			self::$wp_mail_conflict = false;

			// api key for sendinblue.
			$general_settings = get_option( self::MAIN_OPTION_NAME, array() );
			self::$access_key = isset( $general_settings['access_key'] ) ? $general_settings['access_key'] : '';

			self::$instance = $this;

			add_action( 'admin_init', array( &$this, 'admin_init' ), 9999 );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9999 );

			add_action( 'wp_print_scripts', array( &$this, 'frontend_register_scripts' ), 9999 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'wp_head_ac' ), 999 );

			// create custom url for form preview.
			add_filter( 'query_vars', array( &$this, 'sib_query_vars' ) );
			add_action( 'parse_request', array( &$this, 'sib_parse_request' ) );

			add_action( 'wp_ajax_sib_validate_process', array( 'SIB_Page_Home', 'ajax_validation_process' ) );
			add_action( 'wp_ajax_sib_validate_ma', array( 'SIB_Page_Home', 'ajax_validate_ma' ) );
			add_action( 'wp_ajax_sib_activate_email_change', array( 'SIB_Page_Home', 'ajax_activate_email_change' ) );
			add_action( 'wp_ajax_sib_sender_change', array( 'SIB_Page_Home', 'ajax_sender_change' ) );
			add_action( 'wp_ajax_sib_send_email', array( 'SIB_Page_Home', 'ajax_send_email' ) );
			add_action( 'wp_ajax_sib_remove_cache', array( 'SIB_Page_Home', 'ajax_remove_cache' ) );
			add_action( 'wp_ajax_sib_sync_users', array( 'SIB_Page_Home', 'ajax_sync_users' ) );

			add_action( 'wp_ajax_sib_change_template', array( 'SIB_Page_Form', 'ajax_change_template' ) );
			add_action( 'wp_ajax_sib_get_lists', array( 'SIB_Page_Form', 'ajax_get_lists' ) );
			add_action( 'wp_ajax_sib_get_templates', array( 'SIB_Page_Form', 'ajax_get_templates' ) );
			add_action( 'wp_ajax_sib_get_attributes', array( 'SIB_Page_Form', 'ajax_get_attributes' ) );
			add_action( 'wp_ajax_sib_update_form_html', array( 'SIB_Page_Form', 'ajax_update_html' ) );
			add_action( 'wp_ajax_sib_copy_origin_form', array( 'SIB_Page_Form', 'ajax_copy_origin_form' ) );

			add_action( 'init', array( &$this, 'init' ) );

			add_action( 'wp_login', array( &$this, 'sib_wp_login_identify' ), 10, 2 );

			// change sib tables name on prior(2.6.9) versions.
			SIB_Model_Users::add_prefix();
			SIB_Forms::add_prefix();

			if ( self::is_done_validation() === true ) {
				add_shortcode( 'sibwp_form', array( &$this, 'sibwp_form_shortcode' ) );
				// register widget.
				add_action( 'widgets_init', array( &$this, 'sib_create_widget' ) );

				// check if updated into new configuration. to 2.x.x.
				$use_new_version = get_option( 'sib_use_new_version', '0' );
				if ( '1' === $use_new_version ) {
					update_option( 'sib_use_new_version', '2.7.2' );
					// create forms tables and create default form.
					SIB_Forms::createTable();
					// create users table.
					SIB_Model_Users::createTable();
					// create old form.
					$oldFormData = SIB_Forms::get_old_form();
					$oldFormID = SIB_Forms::addForm( $oldFormData );
					update_option( 'sib_old_form_id', $oldFormID );
				} elseif ( '2.7.2' !== $use_new_version ) {
					update_option( 'sib_use_new_version', '2.7.2' );
					SIB_Forms::alterTable();
					SIB_Forms::addTermsColumn();
				}
			}

			$use_api_version = get_option( 'sib_use_apiv2', '0' );
			if ( '0' === $use_api_version ) {
				self::uninstall();
				update_option( 'sib_use_apiv2', '1' );
			}

			// Wpml plugin part.
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			endif;
			if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				SIB_Forms_Lang::createTable();
				add_action( 'sib_language_sidebar', array( $this, 'sib_create_language_sidebar' ) );
			}

			/**
			 * Hook wp_mail to send transactional emails
			 */

			// check if wp_mail function is already declared by others.
			if ( function_exists( 'wp_mail' ) ) {
				self::$wp_mail_conflict = true;
			}
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME, array() );

			if ( 'yes' === $home_settings['activate_email'] && false === self::$wp_mail_conflict ) {
				/**
				 * Declare wp_mail function for SendinBlue SMTP module
				 *
				 * @param string $to - receiption email.
				 * @param string $subject - subject of email.
				 * @param string $message - message content.
				 * @param string $headers - header of email.
				 * @param array  $attachments - attachments.
				 * @return bool
				 */
				function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
					$message = str_replace( 'NF_SIB', '', $message );
					$message = str_replace( 'WC_SIB', '', $message );
					try {
						$sent = SIB_Manager::sib_email( $to, $subject, $message, $headers, $attachments );
						if ( is_wp_error( $sent ) || ! isset( $sent['code'] ) || 'success' !== $sent['code'] ) {
							return SIB_Manager::wp_mail_native( $to, $subject, $message, $headers, $attachments );
						}
						return true;
					} catch ( Exception $e ) {
						return SIB_Manager::wp_mail_native( $to, $subject, $message, $headers, $attachments );
					}
				}
			} else {
				add_action( 'admin_notices', array( &$this, 'wpMailNotices' ) );
				return;
			}
		}

		/**
		 * Add identify tag for login users
		 *
		 * @param string $user_login - user login name.
		 * @param array  $user - user.
		 */
		function sib_wp_login_identify( $user_login, $user ) {

			$userEmail = $user->user_email;
			$data = array(
				'email_id' => $userEmail,
				'name' => $user_login,
			);
			SIB_API_Manager::identify_user( $data );
		}

		/**
		 * Initialize method. called on <i>init</i> action
		 */
		function init() {
			// Sign up process.
			if ( isset( $_POST['sib_form_action'] ) && ( 'subscribe_form_submit' == $_POST['sib_form_action'] ) ) {
				$this->signup_process();
			}
			// Subscribe.
			if ( isset( $_GET['sib_action'] ) && ( 'subscribe' == $_GET['sib_action'] ) ) {
				SIB_API_Manager::subscribe();
				exit;
			}
			// Dismiss language notice.
			if ( isset( $_GET['dismiss_admin_lang_notice'] ) && '1' == $_GET['dismiss_admin_lang_notice'] ) {
				update_option( SIB_Manager::LANGUAGE_OPTION_NAME, true );
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}

			add_action( 'wp_head', array( &$this, 'install_ma_script' ) );
		}

		/**
		 * Hook admin_init
		 */
		function admin_init() {
			add_action( 'admin_action_sib_setting_subscription', array( 'SIB_Page_Form', 'save_setting_subscription' ) );
			add_action( 'admin_action_nopriv_sib_setting_subscription', array( 'SIB_Page_Form', 'save_setting_subscription' ) );
			SIB_Manager::LoadTextDomain();
			$this->register_scripts();
			$this->register_styles();
		}

		/**
		 * Hook admin_menu
		 */
		function admin_menu() {
			SIB_Manager::LoadTextDomain();
			new SIB_Page_Home();
			new SIB_Page_Form();
			new SIB_Page_Lists();
			new SIB_Page_Campaigns();
			new SIB_Page_Statistics();
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			if ( isset( $home_settings['activate_ma'] ) && 'yes' == $home_settings['activate_ma'] ) {
				new SIB_Page_Scenarios();
			}

		}

		/**
		 * Register script for admin page
		 */
		function register_scripts() {
			wp_register_script( 'sib-bootstrap-js', self::$plugin_url . '/js/bootstrap/js/bootstrap.min.js', array( 'jquery' ), null );
			wp_register_script( 'sib-admin-js', self::$plugin_url . '/js/admin.js', array( 'jquery' ), filemtime( self::$plugin_dir . '/js/admin.js' ) );
			wp_register_script( 'sib-chosen-js', self::$plugin_url . '/js/chosen.jquery.min.js', array( 'jquery' ), null );
		}

		/**
		 * Register stylesheet for admin page
		 */
		function register_styles() {
			wp_register_style( 'sib-bootstrap-css', self::$plugin_url . '/js/bootstrap/css/bootstrap.css', array(), null, 'all' );
			wp_register_style( 'sib-fontawesome-css', self::$plugin_url . '/css/fontawesome/css/font-awesome.css', array(), null, 'all' );
			wp_register_style( 'sib-chosen-css', self::$plugin_url . '/css/chosen.min.css' );
			wp_register_style( 'sib-admin-css', self::$plugin_url . '/css/admin.css', array(), filemtime( self::$plugin_dir . '/css/admin.css' ), 'all' );
		}

		/**
		 * Registers scripts for frontend
		 */
		function frontend_register_scripts() {

		}

		/**
		 * Enqueue script on front page
		 */
		function wp_head_ac() {
			wp_enqueue_script( 'sib-front-js', self::$plugin_url . '/js/mailin-front.js', array( 'jquery' ), filemtime( self::$plugin_dir . '/js/mailin-front.js' ), false );
			wp_localize_script(
				'sib-front-js', 'sibErrMsg', array(
					'invalidMail' => __( 'Please fill out valid email address', 'sib_lang' ),
					'requiredField' => __( 'Please fill out required fields', 'sib_lang' ),
					'invalidDateFormat' => __( 'Please fill out valid date format', 'sib_lang' ),
				)
			);
			wp_localize_script( 'sib-front-js', 'sib_ajax_nonce', wp_create_nonce( 'sib_front_ajax_nonce' ) );
		}

		/**
		 * Install method is called once install this plugin.
		 * create tables, default option ...
		 */
		static function install() {
			$general_settings = get_option( self::MAIN_OPTION_NAME, array() );
			$access_key = isset( $general_settings['access_key'] ) ? $general_settings['access_key'] : '';
			if ( '' === $access_key ) {
				// Default option when activate.
				$home_settings = array(
					'activate_email' => 'no',
					'activate_ma' => 'no',
				);
				update_option( self::HOME_OPTION_NAME, $home_settings );
			}
		}

		/**
		 * Uninstall method is called once uninstall this plugin
		 * delete tables, options that used in plugin
		 */
		static function uninstall() {
			$setting = array();
			update_option( SIB_Manager::MAIN_OPTION_NAME, $setting );

			$home_settings = array(
				'activate_email' => 'no',
				'activate_ma' => 'no',
			);
			update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );

			// Delete access_token.
			$token_settings = array();
			update_option( SIB_Manager::ACCESS_TOKEN_OPTION_NAME, $token_settings );

			// Empty tables.
			SIB_Model_Users::removeTable();
			SIB_Forms::removeTable();
			SIB_Forms_Lang::removeTable();

			// Remove all transient.
			SIB_API_Manager::remove_transients();
		}

		/**
		 * Deactivate method is called once deactivate this plugin
		 */
		static function deactivate() {
			update_option( SIB_Manager::LANGUAGE_OPTION_NAME, false );
			// Remove sync users option.
			delete_option( 'sib_sync_users' );
			// Remove all transient.
			SIB_API_Manager::remove_transients();
		}

		/**
		 * Check that have done validation process already.
		 */
		static function is_done_validation() {
			$general_settings = get_option( self::MAIN_OPTION_NAME, array() );
			$access_key = isset( $general_settings['access_key'] ) ? $general_settings['access_key'] : '';
			if ( '' !== $access_key ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Install marketing automation script in header
		 */
		function install_ma_script() {
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME, array() );
			if ( isset( $home_settings['activate_ma'] ) && 'yes' == $home_settings['activate_ma'] ) {
				$general_settings = get_option( SIB_Manager::MAIN_OPTION_NAME, array() );
				$ma_key = $general_settings['ma_key'];
				$output = '<script type="text/javascript">
            (function() {window.sib ={equeue:[],client_key:"'. $ma_key .'"};/* OPTIONAL: email for identify request*/window.sendinblue = {}; for (var j = [\'track\', \'identify\', \'trackLink\', \'page\'], i = 0; i < j.length; i++) { (function(k) { window.sendinblue[k] = function() { var arg = Array.prototype.slice.call(arguments); (window.sib[k] || function() { var t = {}; t[k] = arg; window.sib.equeue.push(t);})(arg[0], arg[1], arg[2]);};})(j[i]);}var n = document.createElement("script"),i = document.getElementsByTagName("script")[0]; n.type = "text/javascript", n.id = "sendinblue-js", n.async = !0, n.src = "https://sibautomation.com/sa.js?key=" + window.sib.client_key, i.parentNode.insertBefore(n, i), window.sendinblue.page();})();
            </script>';
				echo $output;
			}
		}

		/**
		 * Register widget
		 */
		function sib_create_widget() {
			register_widget( 'SIB_Widget_Subscribe' );
		}

		/**
		 * Display form on front page
		 *
		 * @param string $frmID - form ID.
		 * @param string $lang - form language.
		 */
		function generate_form_box( $frmID = '-1', $lang = '' ) {
			if ( 'oldForm' == $frmID ) {
				$frmID = get_option( 'sib_old_form_id' );
			} elseif ( '' != $lang ) {
				$trans_id = SIB_Forms_Lang::get_form_ID( $frmID, $lang );
				if ( null != $trans_id ) {
					$frmID = $trans_id;
				}
			}

			$formData = SIB_Forms::getForm( $frmID );

			if ( empty( $formData ) ) {
				return;
			}
			// Add Google recaptcha
			if( '0' != $formData['gCaptcha'] ) {
				if( '1' == $formData['gCaptcha'] ) {   // For old forms.
					$formData['html'] = preg_replace( '/([\s\S]*?)<div class="g-recaptcha"[\s\S]*?data-size="invisible"><\/div>/', '$1', $formData['html'] );
				}
				if ( '3' == $formData['gCaptcha'] )     // The case of using google recaptcha.
				{
					?>
					<script type="text/javascript">
						var gCaptchaSibWidget;
						var onloadSibCallback = function() {
							gCaptchaSibWidget = grecaptcha.render('sib_captcha',{
								'sitekey' : '<?php echo $formData["gCaptcha_site"] ?>'
							});
						};
					</script>
					<?php
				}
				else {                                  // The case of using google invisible recaptcha.
					?>
					<script type="text/javascript">
						var gCaptchaSibWidget;
						var onloadSibCallback = function() {
							var element = document.getElementsByClassName('sib-default-btn');
							gCaptchaSibWidget = grecaptcha.render(element[0],{
								'sitekey' : '<?php echo $formData["gCaptcha_site"] ?>',
								'callback' : sibVerifyCallback
							});
						};
					</script>
					<?php
				}
				?>
				<script src="https://www.google.com/recaptcha/api.js?onload=onloadSibCallback&render=explicit" async defer></script>
				<?php
			}

			?>
			<form id="sib_signup_form_<?php echo esc_attr( $frmID ); ?>" method="post" class="sib_signup_form">
				<div class="sib_loader" style="display:none;"><img
							src="<?php echo esc_url( includes_url() ); ?>/images/spinner.gif" alt="loader"></div>
				<input type="hidden" name="sib_form_action" value="subscribe_form_submit">
				<input type="hidden" name="sib_form_id" value="<?php echo esc_attr( $frmID ); ?>">
				<div class="sib_signup_box_inside_<?php echo esc_attr( $frmID ); ?>">
					<div style="/*display:none*/" class="sib_msg_disp">
					</div>
					<?php
					echo $formData['html'];
					?>
				</div>
			</form>
			<style>
				<?php

				if ( ! $formData['dependTheme'] ) {
					// Custom css.
					$formData['css'] = str_replace( '[form]', 'form#sib_signup_form_' . $frmID, $formData['css'] );
					echo $formData['css'];
				}
					$msgCss = str_replace( '[form]', 'form#sib_signup_form_' . $frmID, SIB_Forms::getDefaultMessageCss() );
					echo $msgCss;
				?>
			</style>
			<?php
		}

		/**
		 * Shortcode for sign up form
		 *
		 * @param array $atts - shortcode parameter.
		 * @return string
		 */
		function sibwp_form_shortcode( $atts ) {
			$pull_atts = shortcode_atts(
				array(
					'id' => 'oldForm', // We will return 'oldForm' for shortcode of old form.
				), $atts
			);
			$frmID = $pull_atts['id'];
			$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '';

			ob_start();
			$this->generate_form_box( $frmID, $lang );

			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}

		/**
		 * Sign up process
		 */
		function signup_process() {
			check_ajax_referer( 'sib_front_ajax_nonce', 'security' );
			$formID = isset( $_POST['sib_form_id'] ) ? sanitize_text_field( $_POST['sib_form_id'] ) : 1;
			if ( 'oldForm' == $formID ) {
				$formID = get_option( 'sib_old_form_id' );
			}
			$formData = SIB_Forms::getForm( $formID );

			if ( '0' != $formData['gCaptcha'] ) {
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					wp_send_json(
						array(
							'status' => 'gcaptchaEmpty',
							'msg' => 'Please click on the reCAPTCHA box.',
						)
					);
				}
				$secret = $formData['gCaptcha_secret'];

				$data = array(
					'secret' => $secret,
					'response' => sanitize_text_field( $_POST['g-recaptcha-response'] ),
				);

				$verify = curl_init();
				curl_setopt( $verify, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify' );
				curl_setopt( $verify, CURLOPT_POST, true );
				curl_setopt( $verify, CURLOPT_POSTFIELDS, http_build_query( $data ) );
				curl_setopt( $verify, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $verify, CURLOPT_RETURNTRANSFER, true );
				$response = curl_exec( $verify );
				$responseData = json_decode( $response );
				if ( ! $responseData->success ) {
					wp_send_json(
						array(
							'status' => 'gcaptchaFail',
							'msg' => 'Robot verification failed, please try again.',
						)
					);
				}
			}

			$listID = $formData['listID'];
			$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			if ( ! is_email( $email ) ) {
				return;
			}

			$isDoubleOptin = $formData['isDopt'];
			$isOptin = $formData['isOpt'];
			$redirectUrlInEmail = $formData['redirectInEmail'];
			$redirectUrlInForm = $formData['redirectInForm'];

			$info = array();
			$attributes = explode( ',', $formData['attributes'] ); // String to array.
			if ( isset( $attributes ) && is_array( $attributes ) ) {
				foreach ( $attributes as $attribute ) {
					$info[ $attribute ] = isset( $_POST[ $attribute ] ) ? sanitize_text_field( $_POST[ $attribute ] ) : '' ;
				}
			}
			$templateID = $formData['templateID'];

			if ( $isDoubleOptin ) {
				/*
				 * Double optin process
                 * 1. add/update user in SIB contacts
                 * 2. add record to db
                 * 3. send confirmation email with activate code
                 */
				// Create/updated subscriber.
				$result = SIB_API_Manager::create_subscriber( 'double-optin', $email, $listID, $info );
				// Send a double optin confirm email.
				if ( 'success' == $result ) {
					// Add a recode with activate code in db.
					$activateCode = $this->create_activate_code( $email, $info, $formID, $listID, $redirectUrlInEmail );
					SIB_API_Manager::send_comfirm_email( 'double-optin', $email, $templateID, $info, $activateCode );
				}
			} elseif ( $isOptin ) {
				$result = SIB_API_Manager::create_subscriber( 'confirm', $email, $listID, $info );
				if ( 'success' == $result ) {
					// Send a confirm email.
					SIB_API_Manager::send_comfirm_email( 'confirm', $email, $templateID, $info );
				}
			} else {
				$result = SIB_API_Manager::create_subscriber( 'simple', $email, $listID, $info );
			}
			$msg = array(
				'successMsg' => $formData['successMsg'],
				'errorMsg' => $formData['errorMsg'],
				'existMsg' => $formData['existMsg'],
				'invalidMsg' => $formData['invalidMsg'],
			);

			wp_send_json(
				array(
					'status' => $result,
					'msg' => $msg,
					'redirect' => $redirectUrlInForm,
				)
			);
		}

		/**
		 * Create activate code for Double optin
		 *
		 * @param string $email  - user email.
		 * @param array  $info  - info.
		 * @param string $formID - form ID.
		 * @param array  $listIDs - lists.
		 * @param string $redirectUrl - redirect url.
		 * @return string - activate code.
		 */
		function create_activate_code( $email, $info, $formID, $listIDs, $redirectUrl ) {
			$data = SIB_Model_Users::get_data_by_email( $email, $formID );
			if ( false == $data ) {
				$uniqid = uniqid();
				$data = array(
					'email' => $email,
					'code' => $uniqid,
					'info' => maybe_serialize( $info ),
					'frmid' => $formID,
					'listIDs' => maybe_serialize( $listIDs ),
					'redirectUrl' => $redirectUrl,
				);
				SIB_Model_Users::add_record( $data );
			} else {
				$uniqid = $data['code'];
			}
			return $uniqid;
		}

		/**
		 * Use SendinBlue SMTP to send all emails
		 *
		 * @param string $to - reception email.
		 * @param string $subject - subject of email.
		 * @param string $message - message of email.
		 * @param string $headers - header of email.
		 * @param array  $attachments - attachments.
		 */
		static function wp_mail_native( $to, $subject, $message, $headers = '', $attachments = array() ) {
			require self::$plugin_dir . '/inc/function.wp_mail.php';
		}

		/**
		 * To send the transactional email via Sendinblue
		 * hook wp_mail
		 *
		 * @param string $to - reception email.
		 * @param string $subject - subject of email.
		 * @param string $message - message of email.
		 * @param string $headers - header of email.
		 * @param array  $attachments - attachments
		 * @param array  $tags - tag.
		 * @param string $from_name - sender name.
		 * @param string $from_email - sender email.
		 * @return mixed|WP_Error
		 */
		static function sib_email( $to, $subject, $message, $headers = '', $attachments = array(), $tags = array(), $from_name = '', $from_email = '' ) {
			// Compact the input, apply the filters, and extract them back out.
			extract( apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) ) );

			if ( ! is_array( $attachments ) ) {
				$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
			}

			// From email and name.
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			if ( isset( $home_settings['sender'] ) ) {
				$from_name = $home_settings['from_name'];
				$from_email = $home_settings['from_email'];
			} else {
				$from_email = trim( get_bloginfo( 'admin_email' ) );
				$from_name = trim( get_bloginfo( 'name' ) );
			}
			$from_email = apply_filters( 'wp_mail_from', $from_email );
			$from_name = apply_filters( 'wp_mail_from_name', $from_name );

			// Headers.
			if ( empty( $headers ) ) {
				$headers = array();
				$reply = array();
				$bcc = array();
				$cc = array();
			} else {
				if ( ! is_array( $headers ) ) {
					// Explode the headers out, so this function can take both.
					// string headers and an array of headers.
					$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
				} else {
					$tempheaders = $headers;
				}
				$headers = array();
				$reply = array();
				$bcc = array();
				$cc = array();
				// If it's actually got contents.
				if ( ! empty( $tempheaders ) ) {
					// Iterate through the raw headers.
					foreach ( (array) $tempheaders as $header ) {
						if ( strpos( $header, ':' ) === false ) {
							if ( false !== stripos( $header, 'boundary=' ) ) {
								$parts = preg_split( '/boundary=/i', trim( $header ) );
								$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
							}
							continue;
						}
						// Explode them out.
						list($name, $content) = explode( ':', trim( $header ), 2 );

						// Cleanup crew.
						$name = trim( $name );
						$content = trim( $content );

						switch ( strtolower( $name ) ) {
							case 'content-type':
								$headers[ trim( $name ) ] = trim( $content );
								break;
							case 'x-mailin-tag':
								$headers[ trim( $name ) ] = trim( $content );
								break;
							case 'from':
								if ( strpos( $content, '<' ) !== false ) {
									// So... making my life hard again?
									$from_name = substr( $content, 0, strpos( $content, '<' ) - 1 );
									$from_name = str_replace( '"', '', $from_name );
									$from_name = trim( $from_name );

									$from_email = substr( $content, strpos( $content, '<' ) + 1 );
									$from_email = str_replace( '>', '', $from_email );
									$from_email = trim( $from_email );
								} else {
									$from_name = '';
									$from_email = trim( $content );
								}
								break;

							case 'bcc':
								$bcc[ trim( $content ) ] = '';
								break;
							case 'cc':
								$cc[ trim( $content ) ] = '';
								break;
							case 'reply-to':
								$reply[] = trim( $content );
								break;
							default:
								break;
						}
					}
				}
			}

			// Set destination addresses.
			if ( ! is_array( $to ) ) {
				$to = explode( ',', preg_replace( '/\s+/', '', $to ) ); // strip all whitespace.
			}

			$processed_to = array();
			foreach ( $to as $email ) {
				if ( is_array( $email ) ) {
					$processed_to[] = $email;
				} else {
					$processed_to[ $email ] = '';
				}
			}
			$to = $processed_to;

			// Attachments.
			$attachment_content = array();
			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					$content = self::getAttachmentStruct( $attachment );
					if ( ! is_wp_error( $content ) ) {
						$attachment_content = array_merge( $attachment_content, $content );
					}
				}
			}

			// Common transformations for the HTML part.
			// If it is text/plain, New line break found.
			if ( strpos( $message, '</table>' ) === false && strpos( $message, '</div>' ) === false ) {
				if ( strpos( $message, "\n" ) !== false ) {
					if ( is_array( $message ) ) {
						foreach ( $message as &$value ) {
							$value['content'] = preg_replace( '#<(https?://[^*]+)>#', '$1', $value['content'] );
							$value['content'] = nl2br( $value['content'] );
						}
					} else {
						$message = preg_replace( '#<(https?://[^*]+)>#', '$1', $message );
						$message = nl2br( $message );
					}
				}
			}
			// Sending...
			$data = array(
				'to' => $to,
				'from' => array( $from_email, $from_name ),
				'cc' => $cc,
				'bcc' => $bcc,
				'replyto' => $reply,
				'subject' => $subject,
				'headers' => $headers,
				'attachment' => $attachment_content,
				'html' => $message,
			);

			try {
				$sent = SIB_API_Manager::send_email( $data );
				return $sent;
			} catch ( Exception $e ) {
				return new WP_Error( $e->getMessage() );
			}
		}

		/**
		 * @param string $path - attachment file path
		 * @return array|WP_Error
		 */
		static function getAttachmentStruct( $path ) {

			$struct = array();

			try {

				if ( ! @is_file( $path ) ) {
					throw new Exception( $path . ' is not a valid file.' );
				}

				$filename = basename( $path );

				if ( ! function_exists( 'get_magic_quotes' ) ) {
					/**
					 * @return bool
					 */
					function get_magic_quotes() {
						return false;
					}
				}
				if ( ! function_exists( 'set_magic_quotes' ) ) {
					/**
					 * @param $value
					 * @return bool
					 */
					function set_magic_quotes( $value ) {
						return true;
					}
				}

				$isMagicQuotesSupported = version_compare( PHP_VERSION, '5.3.0', '<' )
					&& function_exists( 'get_magic_quotes_runtime' )
					&& function_exists( 'set_magic_quotes_runtime' );

				if ( $isMagicQuotesSupported ) {
					// Escape linters check.
					$getMagicQuotesRuntimeFunc = 'get_magic_quotes_runtime';
					$setMagicQuotesRuntimeFunc = 'set_magic_quotes_runtime';

					// Save magic quotes value.
					$magicQuotes = $getMagicQuotesRuntimeFunc();
					$setMagicQuotesRuntimeFunc (0);
				}

				$file_buffer = file_get_contents( $path );
				$file_buffer = chunk_split( base64_encode( $file_buffer ), 76, "\n" );

				if ( $isMagicQuotesSupported ) {
					// Restore magic quotes value.
					$setMagicQuotesRuntimeFunc($magicQuotes);
				}

				$struct[ $filename ] = $file_buffer;

			} catch ( Exception $e ) {
				return new WP_Error( 'Error creating the attachment structure: ' . $e->getMessage() );
			}

			return $struct;
		}

		/**
		 * Create custom page for form preview
		 *
		 * @param array $query_vars - query.
		 * @return array
		 */
		function sib_query_vars( $query_vars ) {
			$query_vars[] = 'sib_form';
			return $query_vars;
		}

		/**
		 * Parse request
		 *
		 * @param mixed $wp - object.
		 */
		function sib_parse_request( &$wp ) {
			if ( array_key_exists( 'sib_form', $wp->query_vars ) ) {
				include 'inc/sib-form-preview.php';
				exit();
			}
		}

		/**
		 * Load Text domain.
		 */
		static function LoadTextDomain() {
			// Load lang file.
			$i18n_file_name = 'sib_lang';
			$locale = apply_filters( 'plugin_locale', get_locale(), $i18n_file_name );
			// $locale = 'fr_FR';
			$filename = plugin_dir_path( __FILE__ ) . '/lang/' . $i18n_file_name . '-' . $locale . '.mo';
			load_textdomain( 'sib_lang', $filename );
		}

		/**
		 * Notice the language is difference than site's language
		 */
		static function language_admin_notice() {
			if ( ! get_option( SIB_Manager::LANGUAGE_OPTION_NAME ) ) {
				$lang_prefix = substr( get_bloginfo( 'language' ), 0, 2 );
				$lang = self::getLanguageName( $lang_prefix );
				$class = 'error';
				$message = sprintf( 'Please note that your SendinBlue account is in %s, but SendinBlue WordPress plugin is only available in English / French for now. Sorry for inconvenience.', $lang );
				if ( 'en' !== $lang_prefix && 'fr' !== $lang_prefix ) {
					echo ( "<div class=\"$class\" style='margin-left: 2px;margin-bottom: 4px;'> <p>$message<a class='' href='?dismiss_admin_lang_notice=1'> No problem...</a></p></div>" );
				}
			}
		}

		/**
		 * Notice wp_mail is not possible
		 */
		static function wpMailNotices() {
			if ( self::$wp_mail_conflict ) {
				echo ( '<div class="error"><p>' . __( 'You cannot use SendinBlue SMTP now because wp_mail has been declared by another process or plugin. ', 'sib_lang' ) . '</p></div>' );
			}
		}

		/**
		 * Names of languages.
		 *
		 * @param string $prefix - language.
		 * @return mixed
		 */
		public static function getLanguageName( $prefix = 'en' ) {
			$lang = array();
			$lang['de'] = 'Deutsch';
			$lang['en'] = 'English';
			$lang['zh'] = '中文';
			$lang['ru'] = 'Русский';
			$lang['fi'] = 'suomi';
			$lang['fr'] = 'Français';
			$lang['nl'] = 'Nederlands';
			$lang['sv'] = 'Svenska';
			$lang['it'] = 'Italiano';
			$lang['ro'] = 'Română';
			$lang['hu'] = 'Magyar';
			$lang['ja'] = '日本語';
			$lang['es'] = 'Español';
			$lang['vi'] = 'Tiếng Việt';
			$lang['ar'] = 'العربية';
			$lang['pt'] = 'Português';
			$lang['pb'] = 'Português do Brasil';
			$lang['pl'] = 'Polski';
			$lang['gl'] = 'galego';
			$lang['tr'] = 'Turkish';
			$lang['et'] = 'Eesti';
			$lang['hr'] = 'Hrvatski';
			$lang['eu'] = 'Euskera';
			$lang['el'] = 'Ελληνικά';
			$lang['ua'] = 'Українська';
			$lang['ko'] = '한국어';

			return $lang[ $prefix ];
		}

		/**
		 * Create language sidebar for wpml plugin.
		 */
		public function sib_create_language_sidebar() {
			$languages = apply_filters( 'wpml_active_languages', array() );
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
			$frmID = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
			$pID = isset( $_GET['pid'] ) ? sanitize_text_field( $_GET['pid'] ) : '';
			$parent = true;
			if ( '' !== $frmID && '' !== $pID ) {
				$lang = SIB_Forms_Lang::get_lang( $frmID, $pID );
				$parent = false;
			} else {
				$lang = ICL_LANGUAGE_CODE;
				if ( '' !== $frmID && '' === $pID ) {
					$pID = $frmID;

				}
			}

			if ( 'sib_page_form' === $page && 'edit' === $action ) {
				?>
				<div class="panel panel-default text-left box-border-box  small-content">
					<div class="panel-heading"><strong><?php esc_attr_e( 'About SendinBlue', 'sib_lang' ); ?></strong></div>
					<div class="panel-body">
						<p>
							<label for='sib_form_language'><?php esc_attr_e( 'Language of this form:', 'sib_lang' ); ?> </label>
							<select id="sib_form_lang" name="sib_form_lang" data-selected="">
								<?php
								foreach ( $languages as $language ) {
									$selected = ($language['code'] == $lang) ? 'selected' : '';
									if ( $language['code'] == $lang && true === $parent ) {
										$option_text = '<option value="" ' . $selected . '>' . $language['native_name'] . '</option>';
									} else {
										$exist = SIB_Forms_Lang::get_form_ID( $pID, $language['language_code'] );

										if ( null === $exist ) {
											continue;
										} else {
											$option_text = ( 'selected' === $selected ) ? sprintf( '<option value="" selected>%s</option>', $language['native_name'] ) : sprintf( '<option value="?page=%s&action=%s&pid=%s&lang=%s" %s >%s</option>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $pID ), $language['language_code'], $selected, $language['native_name'] );
										}
									}
									echo $option_text ;
								}
								?>
							</select>
						</p>
						<div class="sib_form_translate">
							<p>
								<label><?php esc_attr_e( 'Translate this form', 'sib_lang' ); ?></label>
							</p>
							<table width="100%" class="sib_form_trans_table" style="border: 1px solid #8cceea;">
								<tr>
									<?php
									foreach ( $languages as $language ) {
										if ( $language['code'] == $lang ) {
											continue;
										}
										?>
										<th style="text-align: center;"><img
													src="<?php echo esc_url( $language['country_flag_url'] ); ?>"></th>
										<?php
									}
									?>
								</tr>
								<tr style="background-color: #EFF8FC;">
									<?php
									foreach ( $languages as $language ) {
										if ( $language['code'] == $lang ) {
											continue;
										}
										if ( '' === $pID ) {
											$img_src = plugins_url( 'img/add_translation_disabled.png', __FILE__ );
											$td = '<img src="' . $img_src . '" style="margin:2px;">';
										} else {
											$exist = SIB_Forms_Lang::get_form_ID( $pID, $language['language_code'] );

											if ( null === $exist ) {
												$img_src = plugins_url( 'img/add_translation.png', __FILE__ );

												$href = sprintf( '<a class="sib-form-redirect" href="?page=%s&action=%s&pid=%s&lang=%s" style="width: 20px; text-align: center;padding: 2px 1px;">', esc_attr( $_REQUEST['page'] ), 'edit', absint( $pID ), $language['language_code'] );
												$td = $href . '<img src="' . $img_src . '" style="margin:2px;"></a>';
											} else {
												$img_src = plugins_url( 'img/edit_translation.png', __FILE__ );
												$href = sprintf( '<a class="sib-form-redirect" href="?page=%s&action=%s&id=%s&pid=%s&lang=%s" style="width: 20px; text-align: center;padding: 2px 1px;">', esc_attr( $_REQUEST['page'] ), 'edit', absint( $exist ), absint( $pID ), $language['language_code'] );
												$td = $href . '<img src="' . $img_src . '" style="margin:2px;"></a>';
											}
										}
										?>
										<td style="text-align: center;"><?php echo $td; ?></td>
										<?php
									}
									?>
								</tr>
							</table>
						</div>
						<?php if ( isset( $_GET['pid'] ) ) { ?>
							<div class="sib-form-duplicate">
								<button class="btn btn-default sib-duplicate-btn"><?php esc_attr_e( 'Copy content from origin form', 'sib_lang' ); ?></button>
								<span class="sib-spin"><i
											class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span>
								<i title="<?php echo esc_attr_e( 'Copy content from origin form', 'sib_lang' ); ?>"
								   data-container="body" data-toggle="popover" data-placement="left"
								   data-content="<?php echo esc_attr_e( 'You can copy contents from origin form. You need to translate the contents by this language.', 'sib_lang' ); ?>"
								   data-html="true" class="fa fa-question-circle popover-help-form"></i>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}
	}

	add_action( 'sendinblue_init', 'sendinblue_init' );
	add_filter( 'widget_text', 'do_shortcode' );

	/**
	 * Plugin entry point Process.
	 */
	function sendinblue_init() {
		SIB_Manager::LoadTextDomain();
		new SIB_Manager();
	}

	do_action( 'sendinblue_init' );
}
