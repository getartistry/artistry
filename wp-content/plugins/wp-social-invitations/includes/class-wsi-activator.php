<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.5.0
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.5.0
	 *
	 * @param $network_wide
	 */
	public static function activate( $network_wide ) {

		global $wpdb;

		if ( is_multisite() && $network_wide ) {
			// store the current blog id
			$current_blog = $wpdb->blogid;

			// Get all blogs in the network and activate plugin on each one
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::create_tables();
				restore_current_blog();
			}
		} else {
			self::create_tables();
		}


		wp_schedule_event( time(), 'wsi_one_min', 'wsi_queue_cron' );

		$upgrader = new Wsi_Upgrader( 'wsi', WSI_VERSION);
		$upgrader->upgrade_plugin();

		update_option('wsi_version', WSI_VERSION);

		do_action( 'wsi/activate' );
	}


	/**
	 * @param $blog_id
	 * @param $user_id
	 * @param $domain
	 * @param $path
	 * @param $site_id
	 * @param $meta
	 */
	public static function on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network( WSI_PLUGIN_HOOK ) ) {
			switch_to_blog( $blog_id );
			self::create_tables();
			restore_current_blog();
		}
	}

	/**
	 * Creates the actual tables
	 */
	private static function create_tables() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$stats_table = $wpdb->prefix.'wsi_stats';
		$stats = "CREATE TABLE IF NOT EXISTS $stats_table (
				  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index ID',
				  wsi_obj_id int(10),
				  provider varchar(32) NOT NULL COMMENT 'Provider Name',
				  user_id INT NULL COMMENT 'User''s ID',
				  quantity INT NULL COMMENT 'Quantity of friends invited',
				  queue_id INT NULL COMMENT 'original id from queue',
				  i_datetime datetime NOT NULL,
				  display_name varchar(120) COMMENT 'Display name in provider',
			  PRIMARY KEY  (id),
			  INDEX (i_datetime, user_id, queue_id, provider)
			) ENGINE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";
		dbDelta($stats);

		$logs_table = $wpdb->prefix.'wsi_logs';
		$logs = "CREATE TABLE IF NOT EXISTS $logs_table (
				  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index ID',
				  date datetime NOT NULL,
				  message TEXT COMMENT 'log errors',
			  PRIMARY KEY  (id),
			  INDEX (date)
			) ENGINE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";
		dbDelta($logs);

		$invites_table = $wpdb->prefix.'wsi_invites';
		$invites = "CREATE TABLE IF NOT EXISTS $invites_table (
				  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index ID',
				  user_id INT NULL COMMENT 'User''s ID',
				  provider varchar(32) NOT NULL COMMENT 'Provider Name',
				  friends TEXT COMMENT 'log errors',
				  date_added datetime NOT NULL,
			  PRIMARY KEY  (id),
			  INDEX (user_id,provider)
			) ENGINE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";
		dbDelta($invites);

		$accepted_invites_table = $wpdb->prefix.'wsi_accepted_invites';
		$accepted_invites = "CREATE TABLE IF NOT EXISTS $accepted_invites_table (
				  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index ID',
				  user_id INT NULL COMMENT 'Inviter User''s ID',
				  provider varchar(32) NOT NULL COMMENT 'Provider Name',
				  invited_id INT NULL COMMENT 'Invited User''s ID',
				  date_added datetime NOT NULL,
			  PRIMARY KEY  (id),
			  INDEX (user_id,invited_id,provider)
			) ENGINE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";
		dbDelta($accepted_invites);

		$queue_table = $wpdb->prefix.'wsi_queue';
		$queue = "CREATE TABLE IF NOT EXISTS $queue_table (
				  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index ID',
				  provider varchar(32) NOT NULL COMMENT 'Provider Name',
				  user_id INT NULL COMMENT 'User''s ID',
				  sdata text COMMENT 'hybrid session data',
				  wsi_obj_id int(10),
				  friends text COMMENT 'serialized array of emails or facebook ids etc',
				  i_count INT NULL COMMENT 'Quantity of Invitations',
				  date_added datetime NOT NULL,
				  display_name varchar(120) COMMENT 'Display name in provider',
				  subject text COMMENT 'message subject',
				  message text COMMENT 'message',
				  send_at int(10) COMMENT 'When to send invitation',
			  PRIMARY KEY  (id),
			  KEY (provider),
			  INDEX (date_added, user_id)
			) ENGINE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";

		dbDelta($queue);

	}

}
