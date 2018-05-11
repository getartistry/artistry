<?php
/**
 * Email Template Page.
 *
 * @package ConvertPro
 */

?>

<div class="cp-email-template-wrap">
	<form method="post" class="cp-settings-form">
	<?php
	$email_template     = get_option( 'cp_email_notification_template' );
	$email_template_sbj = get_option( 'cp_email_notification_subject' );

	$subject = ( isset( $email_template_sbj ) && '' != $email_template_sbj ) ? $email_template_sbj : '[SITE_NAME] - [DESIGN_NAME] Form Submission';

	$template = ( isset( $email_template ) && '' != $email_template ) ? $email_template : sprintf( __( "[FORM_SUBMISSION_DATA]\n\n -- \n\nThis e-mail was sent from a %1\$s call-to-action [DESIGN_NAME] on %2\$s (%3\$s)", 'convertpro' ), CPRO_BRANDING_NAME, get_bloginfo( 'name' ), site_url() );
	?>
		<h3 class="cp-gen-set-title" ><?php _e( 'Successful Submission', 'convertpro' ); ?></h3>
		<p>
		<?php
		_e( 'This is an email that will be sent to you every time a user subscribes through a form. You can customize the email subject and body in the fields below. ', 'convertpro' );
		_e( '<strong>Note:</strong> This is applicable when you enable the Email notification option for a particular call-to-action.', 'convertpro' );
		?>
		</p>
		<table class="cp-postbox-table form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="option-admin-menu-subject-page"><?php _e( 'Template Subject', 'convertpro' ); ?></label>
					</th>
					<td>
						<input type="text" id="cp_email_notification_subject" name="cp_email_notification_subject" value="<?php echo esc_attr( $subject ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="option-admin-menu-template-page"><?php _e( 'Template', 'convertpro' ); ?></label>
					</th>
					<td>
						<textarea id="cp_email_notification_template" name="cp_email_notification_template" rows="10" cols="50" ><?php echo esc_attr( $template ); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php do_action( 'cp_after_email_template_setting' ); ?>
		<label for="option-admin-menu-template-page"><?php _e( 'The shortcode in the template represents the following options:', 'convertpro' ); ?></label>
		<div class="link-title ">
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[FORM_SUBMISSION_DATA]'; ?></code><?php _e( ' : This will show the lead data submitted in the form', 'convertpro' ); ?></p>
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[DESIGN_NAME]'; ?></code><?php _e( ' : Name of the current call-to-action', 'convertpro' ); ?></p>
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[SITE_NAME]'; ?></code><?php _e( ' : Name of your website', 'convertpro' ); ?></p>
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[MAILER_SERVICE_NAME]'; ?></code><?php _e( ' : Name of the mailer service', 'convertpro' ); ?></p>
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[ERROR_MESSAGE]'; ?></code><?php _e( ' : Detailed error message', 'convertpro' ); ?></p>
			<p><code style="color:#800;margin:0;padding:0;"><?php echo '[SITE_URL]'; ?></code><?php _e( ' : URL of your website', 'convertpro' ); ?></p>
		</div>
		<p class="submit">
		<input type="hidden" name="curr_tab" value="0">
		<input type="hidden" name="cp-update-email-template-nonce" id="cp-update-email-template-nonce" value="<?php echo wp_create_nonce( 'cp-update-email-template-nonce' ); ?>" />
		<button type="submit" class="cp-btn-primary cp-md-btn cp-button-style button-update-settings cp-submit-settings"><?php _e( 'Save Settings', 'convertpro' ); ?></button>
	</form>
</div> <!-- End Wrapper -->
