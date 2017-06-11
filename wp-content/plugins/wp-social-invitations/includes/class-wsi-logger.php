<?php

/**
 * Class for Mail messages
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/
 */

class Wsi_Logger{

	private static $_options;

	public static function log( $message)
	{
		global $wsi_plugin, $wpdb;

		self::$_options			= $wsi_plugin->get_opts();

		if( self::$_options['enable_dev'] ) {
			usleep(10000);
			$wpdb->insert(
				$wpdb->prefix.'wsi_logs',
				array(
					'message'  => $message,
					'date'     => current_time('mysql', 1),
				),
				array('%s', '%s')

			);
		}


	}

	public static function log_stat($provider, $user_id, $quantity, $queue_id, $display_name, $wsi_ob_id)
	{

		global $wpdb;

		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wsi_stats (provider, user_id, quantity, queue_id,display_name, i_datetime, wsi_obj_id) VALUES (%s, %d, %d, %d, %s, NOW(), %d)", array($provider, $user_id, $quantity, $queue_id, $display_name, $wsi_ob_id)));


	}

}