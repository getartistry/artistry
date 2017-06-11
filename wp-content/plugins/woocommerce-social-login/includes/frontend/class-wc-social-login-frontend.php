<?php
/**
 * WooCommerce Social Login
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Social Login to newer
 * versions in the future. If you wish to customize WooCommerce Social Login for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-social-login/ for more information.
 *
 * @package     WC-Social-Login/Classes
 * @author      SkyVerge
 * @copyright   Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend class
 *
 * @since 1.0
 */
class WC_Social_Login_Frontend {


	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// render login buttons on the login form
		add_action( 'woocommerce_login_form_end', array( $this, 'render_social_login_buttons' ) );

		// optional login/link buttons on checkout / thank you pages
		add_action( 'woocommerce_before_template_part', array( $this, 'maybe_render_social_buttons' ) );

		// render social login profile on my account page
		add_action( 'woocommerce_before_my_account', array( $this, 'render_social_login_profile' ) );

		// inject social login buttons to "Have an account? Login..." notice at checkout
		add_filter( 'woocommerce_add_notice', array( $this, 'checkout_social_login_message' ) );

		// setup shortcode
		add_shortcode( 'woocommerce_social_login_buttons', array( $this, 'social_login_shortcode' ) );

		// Add buttons to Sensei login form
		add_action( 'sensei_login_form_inside_after', array( $this, 'add_buttons_to_sensei_login' ) );
	}


	/**
	 * Whether social login buttons are displayed on the provided page
	 *
	 * @since 1.0
	 * @param string $handle Exampe: `my_account`
	 * @return bool True if displayed, false otherwise
	 */
	public function is_displayed_on( $handle ) {

		/**
		 * Filter where social login buttons should be displayed.
		 *
		 * @since 1.0
		 * @param array $places
		 */
		return in_array( $handle, apply_filters( 'wc_social_login_display', (array) get_option( 'wc_social_login_display', array() ) ) );
	}


	/**
	 * Render social login buttons on frontend
	 *
	 * @since 1.0
	 */
	public function render_social_login_buttons() {

		if ( ! is_checkout() && ! is_account_page() ) {
			return;
		}

		if ( is_checkout() && ! $this->is_displayed_on( 'checkout' ) ) {
			return;
		}

		if ( is_account_page() && ! $this->is_displayed_on( 'my_account' ) ) {
			return;
		}

		$return_url = is_checkout() ? WC()->cart->get_checkout_url() : wc_get_page_permalink( 'myaccount' );

		woocommerce_social_login_buttons( $return_url );
	}


	/**
	 * Maybe render social buttons in two places:
	 *
	 * 1) a separate notice on the checkout page with "login in with..." buttons
	 *
	 * 2) a notice on the thank you page with the "link your account" buttons
	 *
	 * @since 1.1.0
	 * @param string $template_name template being loaded by WC
	 */
	public function maybe_render_social_buttons( $template_name ) {

		// separate notice at checkout
		if ( 'checkout/form-login.php' === $template_name && $this->is_displayed_on( 'checkout_notice' ) && ! is_user_logged_in() ) {

			wc_print_notice( $this->get_login_buttons_html( WC()->cart->get_checkout_url() ), 'notice' );

		} elseif ( 'checkout/thankyou.php' === $template_name && 'yes' === get_option( 'wc_social_login_display_link_account_thank_you' ) && is_user_logged_in() ) {

			// notice on thank you page

			$message = '<p>' . esc_html__( 'Save time next time you checkout by linking your account to your favorite social network. No need to remember another username and password.', 'woocommerce-social-login' ) . '</p>';

			wc_print_notice( $message . $this->get_link_account_buttons_html(), 'notice' );
		}
	}



	/**
	 * Render social login profile on frontend
	 *
	 * @since 1.0
	 */
	public function render_social_login_profile() {

		// Return URL after successful login
		$return_url = wc_get_page_permalink( 'myaccount' );

		// Enqueue styles and scripts
		$this->load_styles_scripts();

		// load the template
		wc_get_template(
			'myaccount/social-profiles.php',
			array(
				'linked_profiles'     => wc_social_login()->get_user_social_login_profiles(),
				'available_providers' => wc_social_login()->get_available_providers(),
				'return_url'          => $return_url,
			),
			'',
			wc_social_login()->get_plugin_path() . '/templates/'
		);
	}


	/**
	 * Loads frontend styles and scripts on checkout page
	 *
	 * @since 1.0
	 */
	public function load_styles_scripts() {

		// frontend CSS
		wp_enqueue_style( 'wc-social-login-frontend', wc_social_login()->get_plugin_url() . '/assets/css/frontend/wc-social-login.min.css', array(), WC_Social_Login::VERSION );

		$script_deps = array( 'jquery' );

		if ( is_checkout() ) {
			$script_deps[] = 'wc-checkout';
		}

		// frontend scripts
		wp_enqueue_script( 'wc-social-login-frontend', wc_social_login()->get_plugin_url() . '/assets/js/frontend/wc-social-login.min.js', $script_deps, WC_Social_Login::VERSION );

		// customize button colors
		wp_add_inline_style( 'wc-social-login-frontend', wc_social_login()->get_button_colors_css() );
	}


	/**
	 * Filter the woocommerce_checkout_login message and
	 * append the social login message to it
	 *
	 * @since 1.0
	 * @param string $message
	 * @return string
	 */
	public function checkout_social_login_message( $message ) {

		if ( is_checkout() && $this->is_displayed_on( 'checkout' ) && strpos( $message, '<a href="#" class="showlogin">' ) !== false && count( wc_social_login()->get_available_providers() ) > 0 ) {
			$message .= '. <br/>' . get_option( 'wc_social_login_text' ) . ' <a href="#" class="js-show-social-login">' . esc_html__( 'Click here to login', 'woocommerce-social-login' ) .'</a>';
		}

		return $message;
	}


	/**
	 * Social Login buttons shortcode. Renders the buttons.
	 *
	 * @since 1.0
	 * @param array $atts associative array of shortcode parameters
	 * @return string shortcode content
	 */
	public function social_login_shortcode( $atts ) {

		$return_url = isset( $atts['return_url'] ) ? $atts['return_url'] : '';

		return $this->get_login_buttons_html( $return_url );
	}


	/**
	 * Get the social login buttons HTML
	 *
	 * @since 1.1.0
	 * @param string $return_url
	 * @return string
	 */
	public function get_login_buttons_html( $return_url = '' ) {

		if ( ! $return_url ) {
			$return_url = wc_get_page_permalink( 'myaccount' );
		}

		ob_start();

		woocommerce_social_login_buttons( $return_url );

		return ob_get_clean();
	}


	/**
	 * Get the "link your account" buttons HTML
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_link_account_buttons_html() {

		ob_start();

		woocommerce_social_login_link_account_buttons();

		return ob_get_clean();
	}


	/**
	 * Add social login buttons to Sensei
	 *
	 * @since 1.1.0
	 */
	public function add_buttons_to_sensei_login() {
		global $woothemes_sensei;

		if ( isset( $woothemes_sensei->settings->settings['my_course_page'] ) ) {

			$return_url = get_permalink( absint( $woothemes_sensei->settings->settings['my_course_page'] ) );

		} else {

			$return_url = wc_get_page_permalink( 'myaccount' );
		}

		woocommerce_social_login_buttons( $return_url );
	}


} // end \WC_Social_Login_Frontend class
