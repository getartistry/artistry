<?php
/**
 * Messages settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
?>
<div id="wsi-messages" class="ui-tabs-panel">
	<div class="info-box">
		<p><?php _e('By default your users will be able to edit the default invitation message. Here you will be able to change the default message and forbid users to change it.', 'wsi' );?></p>
		<p><?php _e('Default messages are divided in several sections. Message for HTML providers, message for non HTML providers, message for twitter, non enditable section and footer.', 'wsi' );?></p>
		<?php if(!get_option('users_can_register') && empty($bp)) :?>
			<div class="wsi-error">
				<?php _e('Registration is not allowed. Go to settings -> General to enable it or %%ACCEPTURL%% won\'t work.', 'wsi' ); ?>
			</div>
		<?php endif;?>
		<p><?php _e('You can use the following placeholders on your message:', 'wsi' );?></p>
		<ul>
			<li><strong>%%INVITERNAME%%</strong>: <?php _e('Display name of the inviter', 'wsi' );?></li>
			<li><strong>%%SITENAME%%</strong>: <?php _e('Name of your website', 'wsi' );?> - <?php echo bloginfo('name');?></li>
			<li><strong>%%ACCEPTURL%%</strong>: <?php _e('Link that invited users can click to accept the invitation and register', 'wsi' );?></li>
			<li><strong>%%INVITERURL%%</strong>: <?php _e('If Buddypress is enabled, URL to the profile of the inviter', 'wsi' );?></li>
			<li><strong>%%CUSTOMURL%%</strong>: <?php _e('A custom URL that you can edit with a simple filter', 'wsi' );?></li>
			<li><strong>%%CURRENTURL%%</strong>: <?php _e('Prints the url where the widget was clicked', 'wsi' );?></li>
			<li><strong>%%CURRENTTITLE%%</strong>: <?php _e('Title of the post / page where the widget was clicked', 'wsi' );?></li>
		</ul>
		<p><?php echo sprintf(__('If you have any question please carefully <a href="%s">read the documentation</a> before opening a ticket', 'wsi' ), 'http://wp.timersys.com/wordpress-social-invitations/docs/defaults-messages/');?></p>
	</div>
		<table class="form-table">
		<?php do_action( 'wsi/settings_page/messages_tab_before' ); ?>

		<tr valign="top" class="">
			<th><label><?php _e( 'Custom Url', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[custom_url]" value="<?php  echo $opts['custom_url'];?>" class="regular-text" />
				<p class="help"><?php _e( 'Fill this field to use it with %%CUSTOMURL%%', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php  _e( 'Default HTML Message for emails.' , 'wsi');?></h2></th>
		</tr>
		<tr valign="top" class="intro-text ">
			<td colspan="4"><p><?php _e('Emails are used with Gmail, Yahoo, Live, Foursquare' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Subject', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[subject]" value="<?php  echo $opts['subject'];?>" class="regular-text " />
				<p class="help"><?php _e( 'Default Subject for invitations', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Editable', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[subject_editable]" disabled>
					<option value="1" <?php selected($opts['subject_editable'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['subject_editable'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable users to change the default subject. Facebook, Twitter and Linkedin don\'t use subject field', 'wsi' ); ?> <span style="color: red;">Premium Only</span> </p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'HTML Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<?php
				$args = array(
					'textarea_name' => 'wsi_settings[html_message]',
					'media_buttons' => false,
					'quicktags'     => true,
					'textarea_rows' => 15,
				);
				wp_editor(  html_entity_decode($opts['html_message'])  , 'html_message', $args); ?>
				<p class="help"><?php _e( 'Default Message for HTML Invitations. <strong>Only supported by (Gmail, Yahoo, Foursquare and Live).</strong>', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Editable', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[html_message_editable]" disabled>
					<option value="1" <?php selected($opts['html_message_editable'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['html_message_editable'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable users to change the default html message.', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Non editable Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<?php
				$args = array(
					'textarea_name' => 'wsi_settings[html_non_editable_message]',
					'media_buttons' => false,
					'quicktags'     => true,
					'textarea_rows' => 15
				);
				wp_editor( html_entity_decode( $opts['html_non_editable_message'] ), 'html_non_editable_message', $args); ?>
				<p class="help"><?php _e( 'This section will be added after normal message. It\'s not editable by users', 'wsi' ); ?></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Footer Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<?php
				$args = array(
					'textarea_name' => 'wsi_settings[footer]',
					'media_buttons' => false,
					'quicktags'     => true,
					'textarea_rows' => 15
				);
				wp_editor( html_entity_decode( $opts['footer'] ), 'footer', $args); ?>
				<p class="help"><?php _e( 'The footer it\'s only used by email providers. A good practice is to add your company address to avoid spam filters', 'wsi' ); ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php  _e( 'Linkedin Default Message.' , 'wsi');?></h2></th>
		</tr>
		<tr valign="top" class="intro-text ">
			<td colspan="4"><p><?php _e('Linkedin message don\'t allow HTML and needs to be shorter than 200 characters' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Subject', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[text_subject]" value="<?php  echo $opts['text_subject'];?>" class="regular-text " />
				<p class="help"><?php echo _e( 'Default Subject for Linkedin' , 'wsi') ?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Editable', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[text_subject_editable]" disabled>
					<option value="1" <?php selected($opts['text_subject_editable'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['text_subject_editable'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable users to change the default subject. Linkedin policy says that users must be able to edit this', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Text Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<textarea name="wsi_settings[message]" id="lk_message" class="regular-text"><?php  echo $opts['message'];?></textarea>
				<p class="help"><?php echo sprintf(__('Default plain text Message for Linkedin invitations.Keep it under 200 characters(%%ACCEPTURL%% will be converted to a 22 characters string) Characters left: %s (without converting placeholders)','wsi'),'<span id="char_left_lk">200</span>')?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Editable', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[message_editable]" disabled>
					<option value="1" <?php selected($opts['message_editable'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['message_editable'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable users to change the default message. Linkedin policy says that users must be able to edit this', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php  _e( 'Facebook Default Message.' , 'wsi');?></h2></th>
		</tr>
		<tr valign="top" class="intro-text ">
			<td colspan="4"><p><?php _e('Default plain text Message for Facebook. HTML is not allowed. May be override by other plugins using og:tags' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Facebook title', 'wsi' ); ?></label></th>
			<td colspan="3">
				<input type="text" name="wsi_settings[fb_title]" value="<?php  echo $opts['fb_title'];?>" class="regular-text"/>
				<p class="help"><?php _e('Default plain text title for Facebook. Not html allowed','wsi');?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Facebook Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<textarea name="wsi_settings[fb_message]"><?php  echo $opts['fb_message'];?></textarea>
				<p class="help"><?php _e('Default plain text message for Facebook. Not html allowed');?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th colspan="4"><h2><?php  _e( 'Twitter Default Message.' , 'wsi');?></h2></th>
		</tr>
		<tr valign="top" class="intro-text ">
			<td colspan="4"><p><?php _e('This is only used for twitter. HTML is not allowed and it needs to be shorter than 140 characters' ,'wsi');?></p></td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Twitter Message', 'wsi' ); ?></label></th>
			<td colspan="3">
				<textarea name="wsi_settings[tw_message]" id="tw_message"><?php  echo $opts['tw_message'];?></textarea>
				<p class="help"><?php echo sprintf(__('Default Message for Twitter. Keep it under 140 characters(%%ACCEPTURL%% will be converted to a 22 characters string) Characters left: %s (without converting placeholders)','wsi'),'<span id="char_left_tw">140</span>','wsi')?></p>
			</td>
		</tr>

		<tr valign="top" class="">
			<th><label><?php _e( 'Editable', 'wsi' ); ?></label></th>
			<td colspan="3">
				<select name="wsi_settings[tw_message_editable]" disabled>
					<option value="1" <?php selected($opts['tw_message_editable'], 1);?>><?php _e( 'Yes', 'wsi');?></option>
					<option value="0" <?php selected($opts['tw_message_editable'],0);?>><?php _e( 'No', 'wsi');?></option>
				</select>
				<p class="help"><?php _e( 'Enable / Disable users to change the default Twitter message.', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>
	</table>
</div><!-- end messages tab-->
