<?php
/**
 * Emails settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
?>
<div id="wsi-emails" class="ui-tabs-panel">
	<table class="form-table">
		<?php do_action( 'wsi/settings_page/emails_tab_before' ); ?>

		<tr valign="top" class="">
			<th><label><?php _e( 'Send With...', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[send_with]" class="send_with" disabled>
					<option value="own" <?php selected($opts['send_with'], 'own');?>><?php _e( 'Your own website', 'wsi');?></option>
					<option value="gmail" <?php selected($opts['send_with'],'gmail');?>><?php _e( 'Gmail', 'wsi');?></option>
					<option value="smtp" <?php selected($opts['send_with'], 'smtp');?>><?php _e( 'Third Party SMTP', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Choose how you want to send the emails to your users', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>
		<tr valign="top" class="emails_settings own_settings">
			<td colspan="4"><p><?php _e('The simplest solution for small lists. Your web host sets a daily email limit.' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Try yourself', 'wsi' ); ?></label></th>
			<td colspan="3">
				<a href="#" class="wsi_test_email button" onclick="javascript:return false"><?php _e( 'Send Test Email', 'wsi' ); ?></a>
				<div id="sending" style="display:none"><img src="<?php echo admin_url('/images/wpspin_light.gif');?>" alt=""/></div>
				<p class="help"><?php _e( 'Please save before sending the test email.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php  _e( 'Emails Limits' , 'wsi');?></h2></th>
		</tr>

		<tr valign="top" class="">
			<td colspan="4"><p><?php sprintf(__('Your hosting has limits. Find out more <a href="%s" target="_blank">here</a>' ,'wsi'), 'https://wp.timersys.com/wordpress-social-invitations/docs/emails-settings/' );?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Send...', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[emails_limit]" value="<?php  echo $opts['emails_limit'];?>" class="small-text " />
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Every...', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[emails_limit_time]" class="send_with">
					<option value="60" <?php selected($opts['emails_limit_time'],'60');?>><?php _e( 'Minute', 'wsi');?></option>
					<option value="120" <?php selected($opts['emails_limit_time'],'120');?>><?php _e( '2 Minutes', 'wsi');?></option>
					<option value="300" <?php selected($opts['emails_limit_time'],'300');?>><?php _e( '5 Minutes', 'wsi');?></option>
					<option value="600" <?php selected($opts['emails_limit_time'],'600');?>><?php _e( '10 Minutes', 'wsi');?></option>
					<option value="900" <?php selected($opts['emails_limit_time'],'900');?>><?php _e( '15 Minutes', 'wsi');?></option>
					<option value="1800" <?php selected($opts['emails_limit_time'],'1800');?>><?php _e( '30 Minutes', 'wsi');?></option>
					<option value="3600" <?php selected($opts['emails_limit_time'],'3600');?>><?php _e( 'Hour', 'wsi');?></option>
					<option value="7200" <?php selected($opts['emails_limit_time'],'7200');?>><?php _e( '2 Hours', 'wsi');?></option>
				</select>
			</td>
		</tr>

	</table>
</div><!-- end emails tab-->
