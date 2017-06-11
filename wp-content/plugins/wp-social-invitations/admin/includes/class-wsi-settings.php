<?php
/**
 * Class Wsi_Settings
 * Class to handle settings page
 * @since 2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/includes
 */
class Wsi_Settings {
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

	}

	/**
	 * Show and save settings page
	 *
	 * @since   2.5.0
	 */
	public function settings_page() {

		global $wsi_plugin;

		if (  isset( $_POST['wsi_nonce'] ) && wp_verify_nonce( $_POST['wsi_nonce'], 'wsi_save_settings' ) ) {

			update_option( 'wsi_settings' , wsi_stripslashes_deep($_POST['wsi_settings']) );

		}
		$opts =  apply_filters('wsi/get_opts', get_option('wsi_settings') );

		include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/wsi-admin-display.php';
	}


	/**
	 * Ajax callback that Delete all plugin logs
	 * @since 2.5.0
	 */
	public function delete_logs() {
		global $wpdb;
		$wpdb->query( "TRUNCATE  {$wpdb->prefix}wsi_logs" );

		$hybrid_logs = WSI_PLUGIN_DIR . '/logs/hybrid.txt';
		if (file_exists($hybrid_logs)) {
			$fh = fopen( $hybrid_logs, 'w' );
			fclose($fh);
		}

		echo json_encode(array('logs_deleted' => true));
		die();
	}
}