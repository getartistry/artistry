<?php
/**
 * Google Analytics Settings View
 *
 * @package convertpro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$analytics_data   = get_option( 'cp_ga_analytics_data' );
$cp_ga_identifier = get_option( 'cp-ga-identifier' ) ? esc_attr( get_option( 'cp-ga-identifier' ) ) : '';

$cp_ga_auth_type = get_option( 'cp-ga-auth-type' ) ? esc_attr( get_option( 'cp-ga-auth-type' ) ) : 'universal-ga';
?>

<div class="cp-gen-set-content">
	<div class="cp-settings-container">
		<h3 class="cp-gen-set-title"><?php _e( 'Step 1 - Enable website tracking', 'convertpro-addon' ); ?></h3>
		<p>
		<?php
		/* translators: %s Product name */
			echo sprintf( __( '%s needs a Universal Google Analytics tracking code on your website for tracking impressions & conversions. Please select a method to add the code.', 'convertpro-addon' ), CPRO_BRANDING_NAME );
		?>
		</p>
		<form method="post" class="cp-settings-form">
			<div class="debug-section cp-access-roles">
				<table class="cp-postbox-table form-table">
					<tr class="cp-settings-row">
						<th scope="row">
							<label for="cp-ga-auth-type"><?php _e( 'Tracking Code Info', 'convertpro-addon' ); ?>
							</label>
						</th>
						<td>
							<select name="cp-ga-auth-type" id="cp-ga-auth-type">
								<option value="universal-ga" <?php selected( $cp_ga_auth_type, 'universal-ga' ); ?>><?php _e( 'Already added Universal Google Analytics code', 'convertpro-addon' ); ?></option>
								<option value="gtm-code" <?php selected( $cp_ga_auth_type, 'gtm-code' ); ?>><?php _e( 'Already added Google Tag Manager code', 'convertpro-addon' ); ?></option>
								<option value="manual" <?php selected( $cp_ga_auth_type, 'manual' ); ?>><?php _e( 'Add Google Analytics Tracking ID', 'convertpro-addon' ); ?></option>
							</select>
						</td>
					</tr>
					<tr class="cp-settings-row" data-dep-element='cp-ga-auth-type' data-dep-val='gtm-code'>
						<th scope="row">
							<label for="cp-ga-identifier"><?php _e( 'Tag Manager Configurations', 'convertpro-addon' ); ?>
							</label>
						</th>
						<td>
							<span>
								<?php
								$doc_link = 'https://www.convertplug.com/pro/docs/setup-convert-pro-events-google-tag-manager/';
								/* translators: %s Link */
								echo sprintf( __( 'Please follow the steps to <a target="_blank" rel="noopener" href="%1$s">Setup %2$s Events in Google Tag Manager</a>. This is a must when you want to integrate with Google Analytics.', 'convertpro-addon' ), esc_url( $doc_link ), CPRO_BRANDING_NAME );
								?>
							</span>
						</td>

					</tr>
					<tr class="cp-settings-row" data-dep-element='cp-ga-auth-type' data-dep-val='manual'>
						<th scope="row">
							<label for="cp-ga-identifier"><?php _e( 'Google Analytics Tracking ID', 'convertpro-addon' ); ?>
							</label>
						</th>
						<td>
							<input type='text' name="cp-ga-identifier" value="<?php echo $cp_ga_identifier; ?>" id="cp-ga-identifier">
							<span class="help-link" style="
									margin-left: 15px;
								"><a target='_blank' rel="noopener" href='https://support.google.com/analytics/answer/1008080?hl=en#trackingID'><?php _e( 'Where Can I find this?', 'convertpro-addon' ); ?></a>
							</span>
						</td>
					</tr>	
				</table>
			</div>
			<p class="submit">
				<input type="hidden" name="curr_tab" value="1">
				<input type="hidden" name="cp-update-settings-nonce" id="cp-update-settings-nonce" value="<?php echo wp_create_nonce( 'cp-update-settings-nonce' ); ?>" />
				<button type="submit" class="cp-btn-primary cp-md-btn cp-button-style button-update-settings cp-submit-settings"><?php _e( 'Save Settings', 'convertpro-addon' ); ?></button>
			</p>
		</form>
	</div>
	<div class="cp-ga-auth-container">
		<h3 class="cp-gen-set-title">
		<?php
		/* translators: %s Product Name */
					echo sprintf( __( 'Step 2 - Authorize %s to view Google Analytics data', 'convertpro-addon' ), CPRO_BRANDING_NAME );
					?>
					</h3>

		<?php if ( false === $analytics_data ) { ?>
			<div class="cp-modal-content">
				<div class="cp-ga-code-container">
					<p>
					<?php
					/* translators: %s Product Name */
					echo sprintf( __( 'Allow %s to fetch analytics data from your Google Analytics account.', 'convertpro-addon' ), CPRO_BRANDING_NAME );

					$ga_details_nonce = wp_create_nonce( 'cp-auth-ga-access-action' );
					$ga_inst          = new CP_V2_GA();
					$auth_url         = $ga_inst->generate_auth_url();
					/* translators: %s Link */
					echo sprintf( __( " Get a Google Analytics access code from <a target='_blank' rel='noopener' href='%s'>here</a>, and paste it below.", 'convertpro-addon' ), esc_url( $auth_url ) );
					?>
					</p>
					<div class="cp-form-error cp-notification-message">
						<label class="cp-error"></label>
					</div>
					<table class="cp-postbox-table form-table auth-input-box">
						<tbody>
							<tr class="cp-settings-row">
								<th scope="row">
									<label for="cp-ga-access-code"><?php _e( 'Authorization Code', 'convertpro-addon' ); ?></label>
								</th>
								<td>
									<input type="textbox" class="cp-ga-access-code" name="cp-ga-access-code" id="cp-ga-access-code" placeholder="<?php _e( 'Enter access code here', 'convertpro-addon' ); ?>">
									<input type="hidden" id="cp-ga-save-nonce" value="<?php echo $ga_details_nonce; ?>">
								</td>
							</tr>
							<tr class="cp-settings-row accounts-option" style="display: none;">
								<th scope="row">
									<label for="cp-ga-access-code"><?php _e( 'Select Profile/View', 'convertpro-addon' ); ?></label>
								</th>
								<td>
									<select name="cp-ga-profile" id="cp-ga-profile">
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="cp-modal-button cp-action-row">
						<button class="cp-auth-ga-access cp-md-btn cp-button-style cp-btn-primary"><?php _e( 'NEXT', 'convertpro-addon' ); ?></button>
						<button class="cp-save-ga-details cp-md-btn cp-button-style cp-btn-primary" style="display: none;" data-inprogress="<?php _e( 'Saving...', 'convertpro-addon' ); ?>" data-title="<?php _e( 'Save', 'convertpro-addon' ); ?>"><?php _e( 'Save', 'convertpro-addon' ); ?></button>
					</div>
				</div>    
			</div><!-- End Wrapper -->
		<?php
} else {
	$ga_profile     = get_option( '_cpro_ga_profile' );
	$ga_credentials = get_option( 'cp_ga_credentials' );
	$profile        = '';
	$profile_view   = isset( $ga_credentials['profile'] ) ? str_replace( 'ga:', '', $ga_credentials['profile'] ) : '';
	$timezone       = isset( $ga_credentials['timezone'] ) ? $ga_credentials['timezone'] : '';

	if ( false !== $ga_profile && '' != $ga_profile ) {
		$profile = '<b>' . $ga_profile . '</b>\'s ';
	}
	?>
	<p>
		<?php
		/* translators: %s Profile Email ID */
		echo sprintf( __( 'You have authenticated with %sGoogle Analytics account.', 'convertpro-addon' ), $profile );

		?>
	</p>

	<?php if ( '' != $profile_view ) { ?>

				<span class="cpro-profile-view">
				<?php
					/* translators: %s Profile view ID */
					echo sprintf( __( '<b>View ID:</b> %s', 'convertpro-addon' ), $profile_view );
				?>
				</span>
			<?php
}

if ( '' != $timezone ) {
		?>
			<span class="cpro-ga-timezone" style="display: block; margin: 20px 0 20px">
			<?php
			/* translators: %s Timezone */
			echo sprintf( __( '<b>Timezone:</b> %s', 'convertpro-addon' ), $timezone );
			?>
			</span>
			<?php } ?>

			<span class="cp-ga-delete-wrap">
				<a href="javascript:void(0);" class="cp-delete-ga-integration">
				<?php _e( 'Remove Google Analytics Integration', 'convertpro-addon' ); ?>
				</a>
			</span>

		<?php } ?>

	</div>
</div>
