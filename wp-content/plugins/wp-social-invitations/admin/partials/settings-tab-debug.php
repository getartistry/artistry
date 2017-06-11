<?php
/**
 * Debug settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
global $wpdb;
?>
<div id="wsi-debug" class="ui-tabs-panel">
	<div class="info-box">
		<p><?php echo sprintf(__('<b>Support is granted only</b> on the <a href="%s">support forums</a>.','wsi'),'https://wordpress.org/support/plugin/wp-social-invitations');?></p>
		<p><?php _e('You can do a simple test to see if you meet the <a href="http://wp.timersys.com/wordpress-social-invitations/docs/requirements/" target="_blank">requirements</a>','wsi');?>: <a class="button-primary" href="<?php echo WSI_PLUGIN_URL.'test.php';?>" target="_blank">WSI TEST</a></p>
		<p><?php _e('Before opening a support ticket check first <a href="http://wp.timersys.com/wordpress-social-invitations/docs/common-problems/" target="_blank">Common Problems section</a>','wsi');?></p>
		<p><?php echo sprintf(__('Enable DEV mode to see errors and log data below. Check hybridauth logs <a href="%s">here</a>.','wsi'), WSI_PLUGIN_URL . 'logs/hybrid.txt' );?></p>
		<p><?php _e('WSI Server Cron url:', 'wsi');?> <?php echo site_url('wp-cron.php').'?wsi_server_cron='.WSI_CRON_TOKEN;?>  <?php echo sprintf(__('<a href="%s" target="_blank">View instructions</a> for server cron jobs','wsi'),'http://wp.timersys.com/wordpress-social-invitations/docs/cron-jobs/');?></p>
		<p><?php _e('Unlock the Queue:', 'wsi');?>  <a href="<?php echo admin_url('admin.php?page=wsi&wsi_queue_unlock='.WSI_CRON_TOKEN.'#wsi-debug-tab');?>"><?php _e('Click here to unlock queue','wsi');?></a> - <?php _e('Use it only if the queue remains locked no matter what.', 'wsi');?></p>

		<p>
			<b>Important</b>:
		</p>
		<ul style="padding-left:15px;">
			<li><?php _e('Please include Debug Info and last 50 lines of Debug Logs when posting support requests. It will help me immensely to better understand any issues.','wsi');?></li>
		</ul>
	</div>
	<table class="form-table">
		<?php do_action( 'wsi/settings_page/debug_tab_before' ); ?>

		<tr valign="top" class="">
			<th><label><?php _e( 'Debug mode', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_dev]" class="enable_dev">
					<option value="0" <?php selected($opts['enable_dev'], '0');?>><?php _e( 'No', 'wsi');?></option>
					<option value="1" <?php selected($opts['enable_dev'],'1');?>><?php _e( 'Yes', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Development mode. Use it on dev site to log errors.', 'wsi' ); ?></p>
				<p class="help"><?php _e( 'Once you finish debugging <strong>it\'s important to delete logs and disable debug mode.</strong>', 'wsi' ); ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Debug logs', 'wsi' ); ?></label></th>
			<td colspan="3">
				<div class="logs">
					<?php
					$logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wsi_logs ORDER BY id DESC");
					foreach( $logs as $log ){
						echo '<p>['. $log->date . '] - ' . $log->message.'</p>';
					}
					?>
				</div>
				<p class="help"><?php _e( 'Logs will show only when dev mode is on.', 'wsi' ); ?> - <a href="" class="wsi_delete_logs">Delete Logs <span id="deleting" style="display:none"><img src="<?php echo admin_url('/images/wpspin_light.gif');?>" alt=""/></span></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Debug Info', 'wsi' ); ?></label></th>
			<td colspan="3">
<textarea readonly="readonly" style="height: 400px;overflow: auto;white-space: pre;width: 790px;">
	EMAILS IN QUEUE:		  <?php echo $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}wsi_queue WHERE provider = 'google' OR provider = 'yahoo' OR provider = 'live' OR provider = 'foursquare'"); echo "\n";?>
	TW IN QUEUE:			  <?php echo $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}wsi_queue WHERE provider = 'twitter'"); echo "\n";?>
	LINKEDIN IN QUEUE:		  <?php echo $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}wsi_queue WHERE provider = 'linkedin'"); echo "\n";?>
	FACEBOOK IN QUEUE:		  <?php echo $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}wsi_queue WHERE provider = 'facebook'"); echo "\n";?>
	FB LOCKED:				  <?php echo get_option('wsi-lock-fb') ? 'Yes' : 'No'; echo "\n";?>
	TW LOCKED:				  <?php echo get_option('wsi-lock-tw') ? 'Yes' : 'No'; echo "\n";?>
	LK LOCKED:				  <?php echo get_option('wsi-lock-lk')  ? 'Yes' : 'No'; echo "\n";?>
	EMAILS LOCKED:			  <?php echo get_option('wsi-lock-emails') ? 'Yes' : 'No'; echo "\n";?>

	SERVER_TIME:              <?php echo date('l jS \of F Y h:i:s A') . "\n"; ?>
	SITE_URL:                 <?php echo site_url() . "\n"; ?>
	PLUGIN_URL:               <?php echo plugins_url() . "\n"; ?>

	HTTP_HOST:                <?php echo $_SERVER['HTTP_HOST'] . "\n"; ?>
	SERVER_PORT:              <?php echo isset( $_SERVER['SERVER_PORT'] ) ? 'On (' . $_SERVER['SERVER_PORT'] . ')' : 'N/A'; echo "\n"; ?>
	HTTP_X_FORWARDED_PROTO:   <?php echo isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? 'On (' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . ')' : 'N/A'; echo "\n"; ?>

	MULTI-SITE:               <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

	WSI VERSION:              <?php echo  $this->version . "\n"; ?>
	WORDPRESS VERSION:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>

	PHP VERSION:              <?php echo PHP_VERSION . "\n"; ?>
	MYSQL VERSION:            <?php echo $wpdb->db_version() . "\n"; ?>
	WEB SERVER INFO:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

	SESSION:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; echo "\n"; ?>
	SESSION:NAME:             <?php echo esc_html( ini_get( 'session.name' ) ); echo "\n"; ?>

	COOKIE PATH:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); echo "\n"; ?>
	SAVE PATH:                <?php echo esc_html( ini_get( 'session.save_path' ) ); echo "\n"; ?>
	USE COOKIES:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>
	USE ONLY COOKIES:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>

	PHP/CURL:                 <?php echo function_exists( 'curl_init'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
<?php if( function_exists( 'curl_init' ) ): ?>
	PHP/CURL/VER:             <?php $v = curl_version(); echo $v['version']; echo "\n"; ?>
	PHP/CURL/SSL:             <?php $v = curl_version(); echo $v['ssl_version']; echo "\n"; ?>
<?php endif; ?>
	PHP/FSOCKOPEN:            <?php echo function_exists( 'fsockopen'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
	PHP/JSON:                 <?php echo function_exists( 'json_decode' ) ? "Supported" : "Not supported"; echo "\n"; ?>

	ACTIVE PLUGINS:

	<?php
	if( function_exists("get_plugins") ):
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_path => $plugin ):
			echo $plugin['Name']; echo $plugin['Name']; ?>: <?php echo $plugin['Version'] ."\n";
		endforeach;
	else:
		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $active_plugins as $plugin ):
			echo $plugin ."\n";
		endforeach;
	endif; ?>

	CURRENT THEME:

	<?php
	if ( get_bloginfo( 'version' ) < '3.4' ) {
		$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
		echo $theme_data['Name'] . ': ' . $theme_data['Version'];
	} else {
		$theme_data = wp_get_theme();
		echo $theme_data->Name . ': ' . $theme_data->Version;
	}
	?>


	# EOF</textarea>
			</td>
		</tr>
	</table>
</div><!-- end emails tab-->
