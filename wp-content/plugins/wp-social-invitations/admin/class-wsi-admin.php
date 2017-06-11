<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Wsi
 * @subpackage Wsi/admin
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5.0
	 * @var      string    $wsi       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wsi, $version ) {

		$this->wsi = $wsi;
		$this->version = $version;

		$this->custom_filters();
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_styles() {

		if( isset( $_GET['page'] ) && 'wsi' ==  $_GET['page'] ) {

			wp_enqueue_style( $this->wsi, plugin_dir_url( __FILE__ ) . 'css/wsi-admin.css', array(), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_scripts() {

		if( isset( $_GET['page'] ) && 'wsi' ==  $_GET['page'] ) {

			wp_enqueue_script( 'admin-wsi', plugin_dir_url( __FILE__ ) . 'js/wsi-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script('jquery-ui-sortable');
			wp_localize_script('admin-wsi', 'wsivar', array(
				'admin_email'   => get_bloginfo('admin_email'),
				'l18n'          => array(
					'test_sent_to' => __('Test email sent to ','wsi')
				)
			));
		}

	}

	/**
	 * Functions to register settings page menu of the plugin
	 */
	public function register_menu() {
		$admin_settings = new Wsi_Settings( $this->wsi, $this->version );
		add_menu_page( 'WP Social Invitations', 'WP Social Invitations', 'manage_options', $this->wsi ,array( $admin_settings, 'settings_page'), 'dashicons-megaphone'  );
	}



	/**
	 * Some custom filters for messages content
	 */
	private function custom_filters() {
		add_filter( 'wsi/message/content', 'wptexturize') ;
		add_filter( 'wsi/message/content', 'convert_smilies' );
		add_filter( 'wsi/message/content', 'convert_chars' );
		add_filter( 'wsi/message/content', 'wpautop' );
		add_filter( 'wsi/message/content', 'shortcode_unautop' );
		add_filter( 'wsi/message/content', 'do_shortcode', 11 );
	}

	/**
	 * Send a test email to admin
	 * @since 2.5.0
	 */
	function send_test_email(){

		$mailer = new Wsi_Mailer(null);

		$email_to = get_bloginfo('admin_email');
		$subject  = __('WSI email settings test', 'wsi');
		$content  = __('You email settings are working like a charm!', 'wsi');

		$message  = $mailer->get_email_content($subject, '', $content);

		$result   = $mailer->send_email($email_to, $subject, $message);

		echo $result;
		die();
	}
}
