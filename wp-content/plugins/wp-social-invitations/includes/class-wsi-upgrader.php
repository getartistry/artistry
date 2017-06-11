<?php
/**
 * Class to handle all plugin upgrades
 * @since      2.5
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */

class Wsi_Upgrader {
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5
	 * @var      string    $wsi       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wsi, $version ) {

		$this->wsi = $wsi;
		$this->version = $version;

	}

	public function upgrade_plugin() {
		global $wpdb, $wsi_plugin;

		$providers_order = get_option('wsi_widget_order',true);

		$current_version = get_option('wsi-version');

		if( is_array( $providers_order ) )
		{
			$missing_provider = array_diff( $wsi_plugin->get_providers() , $providers_order );

			if( !empty( $missing_provider) )
			{
				$providers_order = $providers_order + $missing_provider;
				update_option( 'wsi_widget_order' , $providers_order);
			}
		}

		if( empty($current_version) || version_compare( $current_version, '1.4', '<' ))
		{
			if( !$wpdb->get_var("SHOW COLUMNS FROM `".$wpdb->prefix."wsi_stats` LIKE 'display_name'") ) {
				$wpdb->query( "ALTER TABLE `" . $wpdb->prefix . "wsi_stats`  ADD COLUMN display_name varchar(120)" );
			}
			if( !$wpdb->get_var("SHOW COLUMNS FROM `".$wpdb->prefix."wsi_stats` LIKE 'queue_id'") ) {
				$wpdb->query( "ALTER TABLE `" . $wpdb->prefix . "wsi_stats`  ADD COLUMN queue_id INT(11)" );
			}
		}
		if( empty($current_version) || version_compare( $current_version, '2.4.2', '<' ))
		{
			if( !$wpdb->get_var("SHOW COLUMNS FROM `".$wpdb->prefix."wsi_queue` LIKE 'wsi_obj_id'") ){
				$wpdb->query("ALTER TABLE `".$wpdb->prefix."wsi_queue`  ADD COLUMN wsi_obj_id int(10)");
			}
			if( !$wpdb->get_var("SHOW COLUMNS FROM `".$wpdb->prefix."wsi_stats` LIKE 'wsi_obj_id'") ) {
				$wpdb->query( "ALTER TABLE `" . $wpdb->prefix . "wsi_stats`  ADD COLUMN wsi_obj_id int(10)" );
			}
		}

		if( version_compare( $current_version, '2.0.0', '<' ))
		{
			$this->upgrade_2_5_0_options();
		}
		// show feedback box if updating plugin
		if( !empty($current_version) && version_compare( $current_version, WSI_VERSION, '<' )) {
			update_option('wsi_plugin_updated', true);
		}
	}

	/**
	 * Change optios from true/false to new format
	 */
	protected function upgrade_2_5_0_options() {
		$old_options = get_option( 'wsi_settings' );
		$new_options = $old_options;

		if( !empty( $old_options ) ) {
			foreach( $old_options as $opt => $val ) {
				if( 'true' == $val ) {
					$new_options[$opt] = '1';
				}
				if( 'false' == $val ) {
					$new_options[$opt] = '0';
				}
			}
		}
		update_option('wsi_settings', $new_options);
	}
}