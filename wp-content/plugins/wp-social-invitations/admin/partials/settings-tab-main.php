<?php
/**
 * Main settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
?>
<div id="wsi-main" class="ui-tabs-panel panel-active">
	<div class="info-box">
		<h2><?php printf(__('Welcome to <a href="%s">Wordpress Social Invitations</a>!','wsi'),'http://wp.timersys.com/wordpress-social-invitations/');?></h2>
		<p><?php printf(__('To start using the plugin you need to fill out the OAuth settings of the following providers. If you need help, please read the <a href="%s" target="_blank">documentation</a> or go to the <a href="%s" target="_blank">support forum</a>.','wsi'),'http://wp.timersys.com/wordpress-social-invitations/docs/','https://wordpress.org/support/plugin/wp-social-invitations');?></p>
		<p><?php _e('You can place the invitation widget on any page by using the following shortcode:','wsi');?></p>
		<code>[wsi-widget title="Invite your friends"]</code>
		<p>Or you can place it in your templates with:</p>
		<code>WP_Social_Invitations::widget('Some title');</code>

		<p><?php echo sprintf(__('If you have any question please carefully <a href="%s">read documentation</a> before opening a ticket','wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/');?></p>

	</div>
	<table class="form-table">
		<?php do_action( 'wsi/settings_page/main_tab_before' ); ?>



		<?php if(!get_option('users_can_register') ) : ?>
			<tr valign="top" class="">
				<th><label for="bypass"><?php _e( 'Bypass registration lock', 'wsi' ); ?></label></th>
				<td colspan="3">
					<select name="wsi_settings[bypass_registration_lock]" disabled>
						<option value="0" <?php selected($opts['bypass_registration_lock'],0);?>><?php _e( 'No', 'wsi');?></option>
					</select><p class="help"><?php _e( 'Your site is blocked for new registrations. Check here to bypass this on new Invitations', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
				</td>
			</tr>
		<?php endif;?>

		<tr valign="top" class="">
			<th><label for="redirect"><?php _e( '"Redirect to" URL', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" id="redirect" name="wsi_settings[redirect_url]" value="<?php  echo $opts['redirect_url'];?>" class="regular-text " />
				<p class="help"><?php _e( 'Users will be redirected to this url after they send invitations', 'wsi' ); ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label for="force_invites"><?php _e( 'Multiple Invites?', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[force_invites]" disabled>
					<option value="1" <?php selected($opts['force_invites'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['force_invites'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Users already invited can\'t be invited again unless this is set to "Yes". If set to "No", it will show "user already invited" (only works for logged users)' , 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>

		<?php if (wsi_is_active('buddypress/bp-loader.php')) : ?>
			<tr valign="top" class="">
				<th><label><?php _e( 'Buddypress', 'wsi' ); ?></label></th>
				<td colspan="3">
					<select name="wsi_settings[hook_buddypress]">
						<option value="1" <?php selected($opts['hook_buddypress'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
						<option value="0" <?php selected($opts['hook_buddypress'],0);?>><?php _e( 'No', 'wsi');?></option>
					</select>
					<p class="help"><?php _e( 'Show in buddypress after user activates his new account', 'wsi' ); ?></p>
				</td>
			</tr>
		<?php endif;?>

		<?php if (wsi_is_active('invite-anyone/invite-anyone.php')) : ?>

			<tr valign="top" class="">
				<th><label><?php _e( 'Invite Anyone Plugin', 'wsi' ); ?></label></th>
				<td colspan="3">
					<select name="wsi_settings[hook_invite_anyone]">
						<option value="1" <?php selected($opts['hook_invite_anyone'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
						<option value="0" <?php selected($opts['hook_invite_anyone'],0);?>><?php _e( 'No', 'wsi');?></option>
					</select>
					<p class="help"><?php _e( 'Hook into Invite Anyone Plugin', 'wsi' ); ?></p>
				</td>
			</tr>

		<?php endif; ?>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php _e('Providers' ,'wsi');?></h2></th>
		</tr>
		<tr valign="top" class="intro-text ">
			<td colspan="4"><p><?php _e('Please take your time and read documentation carefully.' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="facebook-heading wsi-providers"><h3>Facebook</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_facebook]">
					<option value="1" <?php selected($opts['enable_facebook'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_facebook'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Facebook Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Client ID', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[facebook_key]" value="<?php  echo $opts['facebook_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#facebook' ) ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Url to Share', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[facebook_share_url]">
					<option value="site_url" <?php selected($opts['facebook_share_url'], 'site_url');?>><?php _e( 'Site Url', 'wsi');?></option>
					<option value="registration" <?php selected($opts['facebook_share_url'],'registration');?>><?php _e( 'Registration Url', 'wsi');?></option>
					<option value="current" <?php selected($opts['facebook_share_url'],'current');?>><?php _e( 'Current Url', 'wsi');?></option>
					<option value="custom_url" <?php selected($opts['facebook_share_url'],'custom_url');?>><?php _e( 'Custom URL', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Which url you want to share. You can change this with filters.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="twitter-heading wsi-providers"><h3>Twitter</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_twitter]">
					<option value="1" <?php selected($opts['enable_twitter'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_twitter'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Twitter Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Consumer Key', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[twitter_key]" value="<?php  echo $opts['twitter_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#twitter' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Consumer Secret', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[twitter_secret]" value="<?php  echo $opts['twitter_secret'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#twitter' ) ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="google-heading wsi-providers"><h3>Google</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_google]">
					<option value="1" <?php selected($opts['enable_google'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_google'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Google Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Client ID', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[google_key]" value="<?php  echo $opts['google_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#google' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Client Secret', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[google_secret]" value="<?php  echo $opts['google_secret'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#google' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th colspan="4" class="linkedin-heading wsi-providers"><h3>Linkedin</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_linkedin]">
					<option value="1" <?php selected($opts['enable_linkedin'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_linkedin'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Linkedin Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'API key', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[linkedin_key]" value="<?php  echo $opts['linkedin_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#linkedin' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Secret Key', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[linkedin_secret]" value="<?php  echo $opts['linkedin_secret'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#linkedin' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th colspan="4" class="yahoo-heading wsi-providers"><h3>Yahoo</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_yahoo]">
					<option value="1" <?php selected($opts['enable_yahoo'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_yahoo'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Yahoo Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Consumer Key', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[yahoo_key]" value="<?php  echo $opts['yahoo_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#yahoo' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Consumer Secret', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[yahoo_secret]" value="<?php  echo $opts['yahoo_secret'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#yahoo' ) ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="foursquare-heading wsi-providers"><h3>Foursquare</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_foursquare]">
					<option value="1" <?php selected($opts['enable_foursquare'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_foursquare'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Foursquare Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Client Key', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[foursquare_key]" value="<?php  echo $opts['foursquare_key'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#foursquare' ) ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Client Secret', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[foursquare_secret]" value="<?php  echo $opts['foursquare_secret'];?>" class="regular-text " />
				<p class="help"><?php echo sprintf(__( '<a href="%s" target="_blank">Where do i get this info?</a>' , 'wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/configuration/#foursquare' ) ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="live-heading wsi-providers"><h3>Windows Live</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_live]">
					<option value="1" <?php selected($opts['enable_live'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_live'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable Windows Live Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4" class="mail-heading wsi-providers"><h3>Emails</h3></th>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Enabled', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[enable_mail]">
					<option value="1" <?php selected($opts['enable_mail'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['enable_mail'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable direct emails Invitations.', 'wsi' ); ?></p>
			</td>
		</tr>
	</table>
</div><!-- end main tab-->
