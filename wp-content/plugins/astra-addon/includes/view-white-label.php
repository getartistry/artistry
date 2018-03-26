<?php
/**
 * White Lable Form
 *
 * @package Astra Addon
 */

$settings = Astra_Ext_White_Label_Markup::get_white_labels();
?>

<form method="post" class="wrap ast-clear" action="" >
<div class="wrap ast-addon-wrap ast-clear ast-container">
	<input type="hidden" name="action" value="ast_save_general_settings">
	<h1 class="screen-reader-text"><?php _e( 'White Label', 'astra-addon' ); ?></h1>

	<?php
		// Settings update message.
	if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
		?>
			<span id="message" class="notice notice-success is-dismissive astra-notice"><p> <?php esc_html_e( 'Settings saved successfully.', 'astra-addon' ); ?> </p></span>
			<?php
	}
	?>

	<div id="poststuff">
		<div id="post-body" class="columns-2">
			<div id="post-body-content">

				<div class="notice ast-white-label-notice"><p><span class="dashicons dashicons-info"></span><?php esc_html_e( 'White Label removes any links to Astra website and change the identity in the dashboard. This setting is mostly used by agencies and developers who are building websites for clients.', 'astra-addon' ); ?></p></div>

				<ul class="ast-branding-list">
					<li>
						<div class="branding-form postbox">
							<h2 class="hndle ast-normal-cusror ui-sortable-handle">
								<span><?php _e( 'Agency Details', 'astra-addon' ); ?></span>
							</h2>

							<div class="inside">
								<div class="form-wrap">
									<div class="form-field">
										<label for="ast-wl-agency-author"><?php _e( 'Agency Author:', 'astra-addon' ); ?></label>
										<input type="text" name="ast_white_label[astra-agency][author]" id="ast-wl-agency-author" class="placeholder placeholder-active" value="<?php echo esc_attr( $settings['astra-agency']['author'] ); ?>">
									</div>
									<div class="form-field">
										<label for="ast-wl-agency-author-url"><?php _e( 'Agency Author URL:', 'astra-addon' ); ?></label>
										<input type="url" name="ast_white_label[astra-agency][author_url]" id="ast-wl-agency-author-url" class="placeholder placeholder-active" value="<?php echo esc_url( $settings['astra-agency']['author_url'] ); ?>">
									</div>
									<div class="form-field">
										<label for="ast-wl-agency-lic"><?php _e( 'Agency Licence Link:', 'astra-addon' ); ?></label>
										<input type="url" name="ast_white_label[astra-agency][licence]" id="ast-wl-agency-lic" class="placeholder placeholder-active" value="<?php echo esc_url( $settings['astra-agency']['licence'] ); ?>">
										<p class="description"><?php esc_html_e( 'Get license link will be displayed in the license form when the purchase key is expired / not valid.', 'astra-addon' ); ?></p>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="branding-form postbox">
							<h2 class="hndle ast-normal-cusror ui-sortable-handle">
								<span><?php _e( 'Astra Theme Branding', 'astra-addon' ); ?></span>
							</h2>
							<div class="inside">
								<div class="form-wrap">
									<div class="form-field">
										<label for="ast-wl-theme-name"><?php _e( 'Theme Name:', 'astra-addon' ); ?></label>
										<input type="text" name="ast_white_label[astra][name]" id="ast-wl-theme-name" class="placeholder placeholder-active" value="<?php echo esc_attr( $settings['astra']['name'] ); ?>">
									</div>
									<div class="form-field">
										<label for="ast-wl-theme-desc"><?php _e( 'Theme Description:', 'astra-addon' ); ?></label>
										<textarea name="ast_white_label[astra][description]" id="ast-wl-theme-desc" class="placeholder placeholder-active" rows="3"><?php echo esc_attr( $settings['astra']['description'] ); ?></textarea>
									</div>
									<div class="form-field">
										<label for="ast-wl-theme-screenshot"><?php _e( 'Theme Screenshot URL:', 'astra-addon' ); ?></label>
										<input type="url" name="ast_white_label[astra][screenshot]" id="ast-wl-theme-screenshot" class="placeholder placeholder-active" value="<?php echo esc_url( $settings['astra']['screenshot'] ); ?>">
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="branding-form postbox">
							<h2 class="hndle ast-normal-cusror ui-sortable-handle">
								<span><?php _e( 'Astra Pro Branding', 'astra-addon' ); ?></span>
							</h2>

							<div class="inside">
								<div class="form-wrap">
									<div class="form-field">
										<label for="ast-wl-plugin-name"><?php _e( 'Plugin Name:', 'astra-addon' ); ?></label>
										<input type="text" name="ast_white_label[astra-pro][name]" id="ast-wl-plugin-name" class="placeholder placeholder-active" value="<?php echo esc_attr( $settings['astra-pro']['name'] ); ?>">
									</div>
									<div class="form-field">
										<label for="ast-wl-plugin-desc"><?php _e( 'Plugin Description:', 'astra-addon' ); ?></label>
										<textarea name="ast_white_label[astra-pro][description]" id="ast-wl-plugin-desc" class="placeholder placeholder-active" rows="2"><?php echo esc_attr( $settings['astra-pro']['description'] ); ?></textarea>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</li>
					<?php
					// Add form for white label with <li> element.
					do_action( 'astra_pro_white_label_add_form', $settings );
					?>
				</ul>
			</div>
			<div class="postbox-container" id="postbox-container-1">
				<div id="side-sortables">
					<div class="postbox">
						<h2 class="hndle ast-normal-cusror"><span><?php esc_html_e( 'White Label Settings', 'astra-addon' ); ?></span>
						</h2>
						<div class="inside">
							<div class="form-wrap">
								<div class="form-field">
									<p>
									<label for="ast-wl-hide-branding">
										<input type="checkbox" id="ast-wl-hide-branding" name="ast_white_label[astra-agency][hide_branding]" value="1" <?php checked( $settings['astra-agency']['hide_branding'], '1' ); ?>>
										<?php _e( 'Hide Branding', 'astra-addon' ); ?>
									</label>
									</p>
									<p class="admin-help"><?php _e( 'Enable this option to hide White Label settings. Re-activate the Astra Pro to enable this settings tab again.', 'astra-addon' ); ?></p>
								</div>
							</div>
						</div>
					</div>

					<?php submit_button( __( 'Save Changes', 'astra-addon' ), 'ast-white-label-save-btn button-primary button button-hero' ); ?>
					<?php if ( is_multisite() ) : ?>
						<p class="install-help"><strong><?php _e( 'Note:', 'astra-addon' ); ?></strong>  <?php _e( 'Whitelabel settings are applied to all the sites in the Network.', 'astra-addon' ); ?></p>
					<?php endif; ?>
					<?php wp_nonce_field( 'white-label', 'ast-white-label-nonce' ); ?>
				</div>
			</div>
		</div>
		<!-- /post-body -->
		<br class="clear">
	</div>
</div>
</form>
