<?php
/**
 * Settings Admin Page.
 *
 * @package ConvertPro
 */

?>
<div class="wrap about-wrap ab-test-cp bend">
	<div class="cp-gen-set-tabs">
		<nav class="cp-gen-set-menu">
			<?php
			$nav_menus = array(
				'general'        => array(
					'label' => __( 'General', 'convertpro' ),
					'icon'  => 'admin-tools',
				),
				'license'        => array(
					'label' => __( 'License', 'convertpro' ),
					'icon'  => 'awards',
				),
				'addons'         => array(
					'label' => __( 'Addons', 'convertpro' ),
					'icon'  => 'admin-plugins',
				),
				'email-template' => array(
					'label' => __( 'Email Notification', 'convertpro' ),
					'icon'  => 'email',
				),
				'advanced'       => array(
					'label' => __( 'Advanced', 'convertpro' ),
					'icon'  => 'admin-generic',
				),
				'branding'       => array(
					'label' => __( 'Branding', 'convertpro' ),
					'icon'  => 'tag',
				),
			);

			$hide_branding = get_option( 'cpro_hide_branding' );

			if ( '1' == $hide_branding ) {
				unset( $nav_menus['branding'] );
			}

			foreach ( $nav_menus as $slug => $nav_menu ) {
				do_action( 'cp_before_' . $slug . '_nav_menu' );
			?>
			<a href="#<?php echo $slug; ?>" class="cp-settings-nav selected"><span class="cp-gen-set-icon"><i class="dashicons dashicons-<?php echo $nav_menu['icon']; ?>"></i></span><?php echo $nav_menu['label']; ?></a>
			<?php
				do_action( 'cp_after_' . $slug . '_nav_menu' );
			}
			do_action( 'cp_general_set_navigation' );
			?>
		</nav>
		<div class="cp-gen-set-content visible">
			<div class="cp-settings-container">
				<h3 class="cp-gen-set-title"><?php _e( 'General Settings', 'convertpro' ); ?></h3>
				<form method="post" class="cp-settings-form">
				<?php
				$menu_position      = esc_attr( get_option( 'bsf_menu_position' ) );
				$menu_position      = ( ! $menu_position ) ? self::$default_menu_position : $menu_position;
				$dev_mode_option    = esc_attr( get_option( 'cp_dev_mode' ) );
				$beta_update_option = esc_attr( get_option( 'cpro_beta_updates' ) );
				$user_inactivity    = esc_attr( get_option( 'cp_user_inactivity' ) );
				$cp_access_roles    = get_option( 'cp_access_role' );
				$cp_credit_option   = esc_attr( get_option( 'cp_credit_option' ) );
				$image_on_ready     = esc_attr( get_option( 'cpro_image_on_ready' ) );
				?>
					<table class="cp-postbox-table form-table">
						<tbody>
						<?php
						// Get list of current General entries.
						$entries = array();
						foreach ( $GLOBALS['menu'] as $entry ) {
							if ( false !== strpos( $entry[2], '.php' ) ) {
								$entries[ $entry[2] ] = $entry[0];
							}
						}

						// Remove <span> elements with notification bubbles (e.g. update or comment count).
						if ( isset( $entries['plugins.php'] ) ) {
							$entries['plugins.php'] = preg_replace( '/ <span.*span>/', '', $entries['plugins.php'] );
						}
						if ( isset( $entries['edit-comments.php'] ) ) {
							$entries['edit-comments.php'] = preg_replace( '/ <span.*span>/', '', $entries['edit-comments.php'] );
						}

						$entries['top']    = __( 'Top-Level (top)', 'convertpro' );
						$entries['middle'] = __( 'Top-Level (middle)', 'convertpro' );
						$entries['bottom'] = __( 'Top-Level (bottom)', 'convertpro' );

						$select_box = '<select name="bsf_menu_position" >' . "\n";
						foreach ( $entries as $page => $entry ) {
							$select_box .= '<option ' . selected( $page, $menu_position, false ) . ' value="' . $page . '">' . $entry . "</option>\n";
						}
						$select_box       .= "</select>\n";
						$dmval             = ! $dev_mode_option ? 0 : 1;
						$image_on_readyval = ! $image_on_ready ? 0 : 1;
						$betaval           = ! $beta_update_option ? 0 : 1;
						$uniq              = uniqid();
						$is_checked        = ( $dmval ) ? ' checked="checked" ' : '';
						$crval             = ( ! $cp_credit_option || 0 == $cp_credit_option ) ? 0 : 1;
						$is_credit_checked = ( $crval ) ? ' checked="checked" ' : '';

						if ( '' == $user_inactivity ) {
							$user_inactivity = '60';
						}
						?>
							<tr>
								<th scope="row">
									<label for="option-admin-menu-global-font"><?php _e( 'Global Font ', 'convertpro' ); ?></label>
									<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'Controls font of your call-to-action. This font will be overwritten by individual element\'s typography option.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
								</th>
								<td>
								<?php
								$font_options     = CP_V2_Fonts::cp_get_fonts();
								$output           = '';
								$font_weights_arr = '';
								$cp_global_font   = Cp_V2_Model::get_cp_global_fonts();
								$sel_font_family  = $cp_global_font['family'];
								$sel_font_weight  = $cp_global_font['weight'];
								?>
									<div class="cp-global-font-field">
										<input type="hidden" id="cp_global_font" name="cp_global_font" class="cp-input" value="" >
										<select for="cp_global_font"  class="cp-font-family" >
								<?php foreach ( $font_options as $key => $font ) { ?>
											<optgroup label="<?php echo $key; ?>">
											<?php
											foreach ( $font as $font_family => $font_weights ) {
												$inherit_key = array_search( 'Inherit', $font_weights );
												unset( $font_weights[ $inherit_key ] );
												$selected = $sel_font_family == $font_family ? 'selected=selected' : '';
											?>
												<option value="<?php echo $font_family; ?>" <?php echo $selected; ?> data-weight="<?php echo implode( ',', $font_weights ); ?>"><?php echo ucfirst( $font_family ); ?></option>
											<?php
											if ( '' !== $selected ) {
												$font_weights_arr = $font_weights;
											}
											}
									?>
											</optgroup>
								<?php
}
								?>
										</select>
										<select for="cp_global_font" class="cp-font-weights">
								<?php
								if ( '' !== $font_weights_arr ) {
									foreach ( $font_weights_arr as $weight ) {
										$selected = $sel_font_weight == $weight ? 'selected=selected' : '';
										?>
											<option value="<?php echo $weight; ?>" <?php echo $selected; ?>><?php echo $weight; ?></option>
								<?php
									}
								}
								?>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="option-admin-menu-inactive-page"><?php _e( 'User Inactivity Time ', 'convertpro' ); ?></label>
									<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'This is the time considered to track user inactivity, when you activate the user inactivity trigger.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i>
									</span>
								</th>
								<td> 
									<input type="number" id="cp_user_inactivity" name="cp_user_inactivity" min="1" max="10000" value="<?php echo $user_inactivity; ?>"/> <span class="description"><?php _e( ' Seconds', 'convertpro' ); ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="cp_credit_option"><strong><?php _e( 'Show Credit Link', 'convertpro' ); ?></strong>
										<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'By enabling this, you agree to display a tiny credit link over the overlay when a popup is displayed.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
									</label>
								</th>
								<td>
									<div class="cp-switch-wrapper">
										<input type="text"  id="cp_credit_option" class="form-control cp-input cp-switch-input" name="cp_credit_option" value="<?php echo $crval; ?>" />
										<input type="checkbox" <?php echo $is_credit_checked; ?> id="cp_credit_option_btn_<?php echo $uniq; ?>"  class="ios-toggle cp-switch-input switch-checkbox" value="<?php echo $crval; ?>" >
										<label class="cp-switch-btn checkbox-label" data-on=<?php _e( 'ON', 'convertpro' ); ?>  data-off="<?php _e( 'OFF', 'convertpro' ); ?>" data-id="cp_credit_option" for="cp_credit_option_btn_<?php echo $uniq; ?>"></label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
					<input type="hidden" name="curr_tab" value="0">
					<input type="hidden" name="cp-update-settings-nonce" id="cp-update-settings-nonce" value="<?php echo wp_create_nonce( 'cp-update-settings-nonce' ); ?>" />
					<button type="submit" class="cp-btn-primary cp-md-btn cp-button-style button-update-settings cp-submit-settings"><?php _e( 'Save Settings', 'convertpro' ); ?></button>
				</form>
			</div>
		</div>
		<div class="cp-gen-set-content">
			<?php require_once( CP_V2_BASE_DIR . 'admin/license.php' ); ?>
		</div>
		<div class="cp-gen-set-content cp-addon-tab">
			<div class="cp-settings-container">
			<?php
				$addon_content = apply_filters( 'cp_general_addon_page', '' );
			if ( '' == $addon_content ) {
				require_once( CP_V2_BASE_DIR . 'admin/add-ons.php' );
			} else {
				echo $addon_content;
			}
			?>
			</div>
		</div>
		<?php do_action( 'cp_after_addons_content' ); ?>
		<div class="cp-gen-set-content">
			<?php require_once( CP_V2_BASE_DIR . 'admin/email-template.php' ); ?>
		</div>
		<?php
		do_action( 'cp_after_email_template_content' );

		$display_adv_settings = false;

		?>
		<div class="cp-gen-set-content">
			<div class="cp-settings-container">
				<?php
				if ( current_user_can( 'manage_options' ) ) {

					$display_adv_settings = true;
				?>
				<h3 class="cp-gen-set-title"><?php _e( 'Advanced Settings', 'convertpro' ); ?></h3>
				<form method="post" class="cp-settings-form">
					<div class="debug-section cp-access-roles">
						<table class="cp-postbox-table form-table">
							<tr>
								<th scope="row">
									<label for="option-admin-menu-parent-page"><?php _e( 'Admin Menu Position ', 'convertpro' ); ?>
										<?php ;/* translators: %s: Convert Pro Name */ ?>
										<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php echo sprintf( __( '%s will be listed under the menu you select here.', 'convertpro' ), CPRO_BRANDING_NAME ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
									</label>
								</th>
								<td><?php echo $select_box; ?></td>
							</tr>
							<tr>
								<th scope="row">
									<?php ;/* translators: %s percentage */ ?>
									<label for="cp-access-user-role"><strong><?php echo sprintf( __( 'Allow %s For', 'convertpro' ), CPRO_BRANDING_NAME ); ?></strong><?php ;/* translators: %s percentage */ ?>
										<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php echo sprintf( __( 'The site administrator has complete access to %s. Select the user roles you wish to grant access to.', 'convertpro' ), CPRO_BRANDING_NAME ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
									</label>
								</th>
								<td>
									<ul class="checkbox-grid">
									<?php
									// Get saved access roles.
									global $wp_roles;
									$roles = $wp_roles->get_names();

									unset( $roles['administrator'] );
									if ( ! $cp_access_roles ) {
										$cp_access_roles = array();
									}

									foreach ( $roles as $key => $role ) {
										$checked = ( in_array( $key, $cp_access_roles ) ) ? 'checked="checked"' : '';
									?>
										<li>
											<input type="checkbox" name="cp_access_role[]" <?php echo $checked; ?> value="<?php echo $key; ?>" id="<?php echo $key; ?>" />
											<label class="cp-role-label" for="<?php echo $key; ?>"><?php echo $role; ?></label>
										</li>
									<?php } ?>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="option-admin-menu-developer-page"><?php _e( 'Developer Mode ', 'convertpro' ); ?>
										<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'Enabling this will help you debug an issue with a particular design by viewing the respective CSS/JS file associated with it.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
									</label>
								</th>
								<td>
									<div class="cp-switch-wrapper">
										<input type="text"  id="cp_dev_mode" class="form-control cp-input cp-switch-input" name="cp_dev_mode" value="<?php echo $dmval; ?>" />
										<input type="checkbox" <?php echo $is_checked; ?> id="cp_dev_mode_btn_<?php echo $uniq; ?>"  class="ios-toggle cp-switch-input switch-checkbox" value="<?php echo $dmval; ?>" >
										<label class="cp-switch-btn checkbox-label" data-on=<?php _e( 'ON', 'convertpro' ); ?>  data-off="<?php _e( 'OFF', 'convertpro' ); ?>" data-id="cp_dev_mode" for="cp_dev_mode_btn_<?php echo $uniq; ?>"></label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="option-admin-menu-developer-page"><?php _e( 'Allow Beta Updates ', 'convertpro' ); ?>
										<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'Enable this option to receive update notifications for beta versions.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
									</label>
								</th>
								<td>
									<div class="cp-switch-wrapper">
										<input type="text"  id="cpro_beta_updates" class="form-control cp-input cp-switch-input" name="cpro_beta_updates" value="<?php echo $betaval; ?>" />
										<input type="checkbox" <?php echo ( $betaval ) ? ' checked="checked" ' : ''; ?> id="cpro_beta_updates_btn_<?php echo $uniq; ?>"  class="ios-toggle cp-switch-input switch-checkbox" value="<?php echo $betaval; ?>" >
										<label class="cp-switch-btn checkbox-label" data-on=<?php _e( 'ON', 'convertpro' ); ?>  data-off="<?php _e( 'OFF', 'convertpro' ); ?>" data-id="cpro_beta_updates" for="cpro_beta_updates_btn_<?php echo $uniq; ?>"></label>
									</div>
								</td>
							</tr>
							<?php if ( isset( $_GET['author'] ) ) { ?>
								<tr>
									<th scope="row">
										<label for="option-lazy-load"><?php _e( 'Allow images load on document ready ', 'convertpro' ); ?>
											<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php _e( 'Enable this option to load images on load of document.', 'convertpro' ); ?>"><i class="dashicons dashicons-editor-help"></i></span>
										</label>
									</th>
									<td>
										<div class="cp-switch-wrapper">
											<input type="text"  id="cpro_image_on_ready" class="form-control cp-input cp-switch-input" name="cpro_image_on_ready" value="<?php echo $image_on_readyval; ?>" />
											<input type="checkbox" <?php echo ( $image_on_readyval ) ? ' checked="checked" ' : ''; ?> id="cpro_image_on_ready_btn_<?php echo $uniq; ?>"  class="ios-toggle cp-switch-input switch-checkbox" value="<?php echo $image_on_readyval; ?>" >
											<label class="cp-switch-btn checkbox-label" data-on=<?php _e( 'ON', 'convertpro' ); ?>  data-off="<?php _e( 'OFF', 'convertpro' ); ?>" data-id="cpro_image_on_ready" for="cpro_image_on_ready_btn_<?php echo $uniq; ?>"></label>
										</div>
									</td>
								</tr>
							<?php } ?>
						</table>
					</div>
					<p class="submit">
						<input type="hidden" name="curr_tab" value="1">
						<input type="hidden" name="cp-update-settings-nonce" id="cp-update-settings-nonce" value="<?php echo wp_create_nonce( 'cp-update-settings-nonce' ); ?>" />
						<button type="submit" class="cp-btn-primary cp-md-btn cp-button-style button-update-settings cp-submit-settings"><?php _e( 'Save Settings', 'convertpro' ); ?></button>
					</p>
					<?php
				}
					?>
				</form>
				<div class="cp-cache-section cp-gen-set-content 
				<?php
				if ( $display_adv_settings ) {
					echo 'cp-border-top'; }
?>
">
					<h3 class="cp-gen-set-title"><?php _e( 'Cache', 'convertpro' ); ?></h3>
					<p><?php _e( 'HTML data of your call-to-action is dynamically generated and cached each time you create or edit a call-to-action. There might be chances that cache needs to be refreshed when you update to the latest version or migrate your site. If you are facing any issues, please try clearing the cache by clicking the button below.', 'convertpro' ); ?></p>
					<button class="cp-btn-primary cp-md-btn cp-button-style cp-refresh_html">
					<?php _e( 'Clear Cache', 'convertpro' ); ?></button>
				</div>
			</div>
		</div>
		<div class="cp-gen-set-content">
			<?php require_once( CP_V2_BASE_DIR . 'admin/branding.php' ); ?>
		</div>
		<?php do_action( 'cp_after_advanced_settings_content' ); ?>
		<?php do_action( 'cp_general_set_content' ); ?>
	</div>
</div> <!-- End Wrapper -->
