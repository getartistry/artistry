<?php
class StoreApps_Upgrade_1_7 {

	var $base_name;
	var $check_update_timeout;
	var $last_checked;
	var $plugin_data;
	var $sku;
	var $license_key;
	var $download_url;
	var $installed_version;
	var $live_version;
	var $changelog;
	var $slug;
	var $name;
	var $documentation_link;
	var $prefix;
	var $text_domain;
	var $login_link;
	var $due_date;
	var $plugin_file;
	var $upgrade_notices;
	
	function __construct( $file, $sku, $prefix, $plugin_name, $text_domain, $documentation_link ) {

		$this->check_update_timeout = (24 * 60 * 60); // 24 hours

		$this->plugin_file = $file;
		$this->base_name = plugin_basename( $file );
		$this->slug = dirname( $this->base_name );
		$this->name = $plugin_name;
		$this->sku = $sku;
		$this->documentation_link = $documentation_link;
		$this->prefix = $prefix;
		$this->text_domain = $text_domain;

		add_site_option( $this->prefix.'_last_checked', '' );
		add_site_option( $this->prefix.'_license_key', '' );
		add_site_option( $this->prefix.'_download_url', '' );
		add_site_option( $this->prefix.'_installed_version', '' );
		add_site_option( $this->prefix.'_live_version', '' );
		add_site_option( $this->prefix.'_changelog', '' );
		add_site_option( $this->prefix.'_login_link', '' );
		add_site_option( $this->prefix.'_due_date', '' );

		add_action( 'admin_init', array( $this, 'initialize_plugin_data' ) );

		add_action( 'admin_footer', array( $this, 'add_plugin_style_script' ) );
		add_action( 'admin_footer', array( $this, 'add_support_ticket_content' ) );
		add_action( 'wp_ajax_'.$this->prefix.'_validate_license_key', array( $this, 'validate_license_key' ) );
		add_action( 'wp_ajax_'.$this->prefix.'_force_check_for_updates', array( $this, 'force_check_for_updates' ) );
		add_action( 'wp_ajax_'.$this->prefix.'_reset_license_details', array( $this, 'reset_license_details' ) );

		add_filter( 'all_plugins', array( $this, 'overwrite_wp_plugin_data_for_plugin' ) );
		add_filter( 'plugins_api', array( $this, 'overwrite_wp_plugin_api_for_plugin' ), 10, 3 );
		add_filter( 'site_transient_update_plugins', array( $this, 'overwrite_site_transient' ), 10, 3 );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'overwrite_site_transient' ), 10, 3 );
		
		add_filter( 'plugin_action_links_' . plugin_basename( $file ), array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_support_link' ), 10, 4 );

		add_filter( 'storeapps_upgrade_create_link', array( $this, 'storeapps_upgrade_create_link' ), 10, 4 );

		add_action( 'admin_notices', array( $this, 'show_notifications' ) );
		add_action( 'wp_ajax_'.$this->prefix.'_hide_renewal_notification', array( $this, 'hide_renewal_notification' ) );
		add_action( 'wp_ajax_'.$this->prefix.'_hide_license_notification', array( $this, 'hide_license_notification' ) );

		add_action( 'in_admin_footer', array( $this, 'add_quick_help_widget' ) );

	}

	function initialize_plugin_data() {

		$this->plugin_data = get_plugin_data( $this->plugin_file );
		$this->base_name = plugin_basename( $this->plugin_file );
		$this->slug = dirname( $this->base_name );

		if ( empty( $this->last_checked ) ) {
			$this->last_checked = (int)get_site_option( $this->prefix.'_last_checked' );
		}

		if ( get_site_option( $this->prefix.'_installed_version' ) != $this->plugin_data ['Version'] ) {
			update_site_option( $this->prefix.'_installed_version', $this->plugin_data ['Version'] );
		}

		if ( ( get_site_option( $this->prefix.'_live_version' ) == '' ) || ( get_site_option( $this->prefix.'_live_version' ) < get_site_option( $this->prefix.'_installed_version' ) ) ) {
			update_site_option( $this->prefix.'_live_version', $this->plugin_data['Version'] );
		}

		if ( empty( $this->license_key ) ) {
			$this->license_key = get_site_option( $this->prefix.'_license_key' );
		}

		if ( empty( $this->changelog ) ) {
			$this->changelog = get_site_option( $this->prefix.'_changelog' );
		}

		if ( empty( $this->login_link ) ) {
			$this->login_link = get_site_option( $this->prefix.'_login_link' );
		}

		if ( empty( $this->due_date ) ) {
			$this->due_date = get_site_option( $this->prefix.'_due_date' );
		}

		add_action( 'after_plugin_row_'.$this->base_name, array( $this, 'update_row' ), 99, 2 );

	}

	function force_check_for_updates() {
		$current_transient = get_site_transient( 'update_plugins' );
		$new_transient = apply_filters( 'site_transient_update_plugins', $current_transient, 'update_plugins', true );
		set_site_transient( 'update_plugins', $new_transient, $this->check_update_timeout );
		echo json_encode( 'checked' );
		exit();
	}

	function reset_license_details() {

		check_ajax_referer( 'storeapps-reset-license', 'security' );

		global $wpdb;

		if ( ! empty( $_POST['prefix'] ) ) {

			$prefix = $_POST['prefix'];

			update_site_option( $prefix.'_license_key', '' );
			update_site_option( $prefix.'_installed_version', '' );
			update_site_option( $prefix.'_live_version', '' );
			update_site_option( $prefix.'_login_link', '' );
			update_site_option( $prefix.'_due_date', '' );
			update_site_option( $prefix.'_changelog', '' );
			update_site_option( $prefix.'_last_checked', '' );
			update_site_option( $prefix.'_download_url', '' );
			update_site_option( $prefix.'_hide_license_notification', '' );
			update_site_option( $prefix.'_hide_renewal_notification', '' );

			$this->check_for_updates();

		}

		die();

	}

	function check_for_updates() {

		if ( ! empty( $_REQUEST ) ) return;     // Check for update should not be executed on request, this should happen automatically, therefore returning when $_REQUEST is not empty
		
		$this->live_version = get_site_option( $this->prefix.'_live_version' );
		$this->installed_version = get_site_option( $this->prefix.'_installed_version' );

		if (version_compare( $this->installed_version, $this->live_version, '<=' )) {

			$license_query = ( !empty( $this->license_key ) ) ? '&serial=' . $this->license_key : '';

			$check_for_update_url = 'https://www.storeapps.org/wp-admin/admin-ajax.php?action=get_products_latest_version&sku=' . $this->sku . $license_query . '&uuid=' . urlencode( admin_url( '/' ) );
			$check_for_update_link = ( ! empty( $check_for_update_url ) ) ? add_query_arg( array( 'utm_source' => $this->sku . '-v' . $this->installed_version, 'utm_medium' => 'upgrade', 'utm_campaign' => 'active_install' ), $check_for_update_url ) : '';
		
			$result = wp_remote_post( $check_for_update_link );
			
			if (is_wp_error($result)) {
				return;
			}
			
			$response = json_decode( $result ['body'] );

			if( empty( $response ) ) {
				return;
			}

			$live_version = $response->version;

			if ( isset( $response->download_url ) && ! empty( $response->download_url ) ) {
				update_site_option( $this->prefix.'_download_url', $response->download_url );
			}

			if ( isset( $response->upgrade_notice ) && ! empty( $response->upgrade_notice ) ) {
				$upgrade_notices = get_site_option( $this->prefix.'_upgrade_notices' );
				if ( empty( $upgrade_notices ) || ! is_array( $upgrade_notices ) ) {
					$upgrade_notices = array();
				}
				$upgrade_notices = array_merge( $upgrade_notices, $response->upgrade_notice );
				update_site_option( $this->prefix.'_upgrade_notices', $upgrade_notices );
			}
			
			if ( isset( $response->link ) ) {
				update_site_option( $this->prefix.'_login_link', $response->link );
			}

			if ( isset( $response->due_date ) ) {
				update_site_option( $this->prefix.'_due_date', $response->due_date );
			}

			if ($this->live_version == $live_version || $response == 'false') {
				return;
			}

			if ( ! empty( $response->changelog ) ) {
				$this->changelog = $response->changelog;
				update_site_option( $this->prefix.'_changelog', $response->changelog );
			}

			update_site_option( $this->prefix.'_live_version', $live_version );

		}
	}

	function overwrite_site_transient( $plugin_info, $transient = 'update_plugins', $force_check_updates = false ) {
		
		if ( empty( $plugin_info->checked ) ) {
			return $plugin_info;
		}

		if ( empty( $this->last_checked ) ) {
			$this->last_checked = get_site_option( $this->prefix . '_last_checked' );
			if ( empty( $this->last_checked ) ) {
				$this->last_checked = time() - $this->check_update_timeout;
				update_site_option( $this->prefix.'_last_checked', $this->last_checked );
				return $plugin_info;
			}
		}

		$time_not_changed = isset( $this->last_checked ) && $this->check_update_timeout > ( time() - $this->last_checked );

		if ( $force_check_updates || ! $time_not_changed ) {
			if ( empty( $this->license_key ) ) {
				$this->license_key = get_site_option( $this->prefix.'_license_key' );
			}
			$this->check_for_updates();
			$this->last_checked = time();
			update_site_option( $this->prefix.'_last_checked', $this->last_checked );
		}

		$plugin_base_file = $this->base_name;
		$live_version = get_site_option( $this->prefix.'_live_version' );
		$installed_version = get_site_option( $this->prefix.'_installed_version' );

		if (version_compare( $live_version, $installed_version, '>' )) {
			$slug               = substr( $plugin_base_file, 0, strpos( $plugin_base_file, '/' ) );
			$download_url       = get_site_option( $this->prefix.'_download_url' );
			$download_link      = ( ! empty( $download_url ) ) ? add_query_arg( array( 'utm_source' => $this->sku . '-v' . $live_version, 'utm_medium' => 'upgrade', 'utm_campaign' => 'update' ), $download_url ) : '';

			$plugin_info->response [$plugin_base_file]                  = new stdClass();
			$plugin_info->response [$plugin_base_file]->slug            = $slug;
			$plugin_info->response [$plugin_base_file]->new_version     = $live_version;
			$plugin_info->response [$plugin_base_file]->url             = 'https://www.storeapps.org';
			$plugin_info->response [$plugin_base_file]->package         = $download_link;
		}

		return $plugin_info;
	}

	function overwrite_wp_plugin_data_for_plugin( $all_plugins = array() ) {
		
		if ( empty( $all_plugins ) || empty( $all_plugins[ $this->base_name ] ) ) {
			return $all_plugins;
		}

		if ( ! empty( $all_plugins[ $this->base_name ]['PluginURI'] ) ) {
			$all_plugins[ $this->base_name ]['PluginURI'] = add_query_arg( array( 'utm_source' => 'product', 'utm_medium' => 'upgrade', 'utm_campaign' => 'visit' ), $all_plugins[ $this->base_name ]['PluginURI'] );
		}

		if ( ! empty( $all_plugins[ $this->base_name ]['AuthorURI'] ) ) {
			$all_plugins[ $this->base_name ]['AuthorURI'] = add_query_arg( array( 'utm_source' => 'brand', 'utm_medium' => 'upgrade', 'utm_campaign' => 'visit' ), $all_plugins[ $this->base_name ]['AuthorURI'] );
		}

		return $all_plugins;
	}

	function overwrite_wp_plugin_api_for_plugin( $api = false, $action = '', $args = '' ) {

		if ( ! isset( $args->slug ) || $args->slug != $this->slug ) {
			return $api;
		}

		$api                = new stdClass();
		$api->slug          = $this->slug;
		$api->plugin        = $this->base_name;
		$api->name          = $this->plugin_data['Name'];
		$api->plugin_name   = $this->plugin_data['Name'];
		$api->version       = get_site_option( $this->prefix.'_live_version' );
		$api->author        = $this->plugin_data['Author'];
		$api->homepage      = $this->plugin_data['PluginURI'];
		$api->sections      = array( 'changelog' => $this->changelog );
		// $api->requires      = $this->plugin_data['Requiresatleast'];
		// $api->tested        = $this->plugin_data['Testedupto'];
		// $api->downloaded    = 0;
		// $api->last_updated  = $this->plugin_data['last_updated'];

		$download_url       = get_site_option( $this->prefix.'_download_url' );
		$download_link      = ( ! empty( $download_url ) ) ? add_query_arg( array( 'utm_source' => $this->sku . '-v' . $api->version, 'utm_medium' => 'upgrade', 'utm_campaign' => 'update' ), $download_url ) : '';

		$api->download_link = $download_link;

		return $api;
	}

	function validate_license_key() {
		$this->license_key = (isset( $_REQUEST ['license_key'] ) && ! empty( $_REQUEST ['license_key'] )) ? $_REQUEST ['license_key'] : '';
		$storeapps_validation_url = 'https://www.storeapps.org/?wc-api=validate_serial_key&serial=' . urlencode( $this->license_key ) . '&is_download=true&sku=' . $this->sku . '&uuid=' . admin_url();
		$resp_type = array ('headers' => array ('content-type' => 'application/text' ) );
		$response_info = wp_remote_post( $storeapps_validation_url, $resp_type ); //return WP_Error on response failure

		if (is_array( $response_info )) {
			$response_code = wp_remote_retrieve_response_code( $response_info );
			$response_msg = wp_remote_retrieve_response_message( $response_info );

			if ($response_code == 200) {
				$storeapps_response = wp_remote_retrieve_body( $response_info );
				$decoded_response = json_decode( $storeapps_response );
				if ($decoded_response->is_valid == 1) {
					update_site_option( $this->prefix.'_license_key', $this->license_key );                
					update_site_option( $this->prefix.'_download_url', $decoded_response->download_url );
					$this->check_for_updates();
				} else {
					$this->remove_license_download_url();
				}
				echo $storeapps_response;
				exit();
			}
			$this->remove_license_download_url();
			echo json_encode( array ('is_valid' => 0 ) );
			exit();
		}
		$this->remove_license_download_url();
		echo json_encode( array ('is_valid' => 0 ) );
		exit();
	}

	function remove_license_download_url() {
		update_site_option( $this->prefix.'_license_key', '' );                
		update_site_option( $this->prefix.'_download_url', '' );
	}

	function add_plugin_style() {
		echo '<style type="text/css">';
		?>
			div#TB_window {
				background: lightgrey;
			}
			<?php if ( version_compare( get_bloginfo( 'version' ), '3.7.1', '>' ) ) { ?>
			tr.<?php echo $this->prefix; ?>_license_key .key-icon-column:before {
				content: "\f112";
				display: inline-block;
				-webkit-font-smoothing: antialiased;
				font: normal 1.5em/1 'dashicons';
			}
			tr.<?php echo $this->prefix; ?>_due_date .renew-icon-column:before {
				content: "\f463";
				display: inline-block;
				-webkit-font-smoothing: antialiased;
				font: normal 1.5em/1 'dashicons';
			}
			<?php } ?>
			a#<?php echo $this->prefix; ?>_reset_license {
				cursor: pointer;
			}
			span#<?php echo $this->prefix; ?>_hide_renewal_notification,
			span#<?php echo $this->prefix; ?>_hide_license_notification {
				cursor: pointer;
				float: right;
				opacity: 0.2;
			}
		<?php
		echo '</style>';
	}

	function update_row($file, $plugin_data) {
		$license_key = get_site_option( $this->prefix.'_license_key' );
		$valid_color = '#AAFFAA';
		$invalid_color = '#FFAAAA';
		$color = ($license_key != '') ? $valid_color : $invalid_color;
		?>
		<?php if ( empty( $license_key ) ) { ?>
		<tr class="<?php echo $this->prefix; ?>_license_key" style="background: <?php echo $color; ?>">
			<td class="key-icon-column" style="vertical-align: middle;"></td>
			<td style="vertical-align: middle;"><label for="<?php echo $this->prefix; ?>_license_key"><strong><?php _e( 'License Key', $this->text_domain ); ?></strong></label></td>
			<td>
				<input type="text" id="<?php echo $this->prefix; ?>_license_key" name="<?php echo $this->prefix; ?>_license_key" value="<?php echo $license_key; ?>" size="50" style="text-align: center;" />
				<input type="button" class="button" id="<?php echo $this->prefix; ?>_validate_license_button" name="<?php echo $this->prefix; ?>_validate_license_button" value="<?php _e( 'Validate', $this->text_domain ); ?>" />
				<input type="button" class="button" id="<?php echo $this->prefix; ?>_check_for_updates" name="<?php echo $this->prefix; ?>_check_for_updates" value="Check for updates" />
				<img id="<?php echo $this->prefix; ?>_license_validity_image" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" style="display: none; vertical-align: middle;" />
			</td>
		</tr>
		<?php } ?>
		<?php
			if ( !empty( $this->due_date ) ) {
				$start = strtotime( $this->due_date . ' -30 days' );
				$due_date = strtotime( $this->due_date );
				$now = time();
				if ( $now >= $start ) {
					$remaining_days = round( abs( $due_date - $now )/60/60/24 );
					$target_link = 'https://www.storeapps.org/my-account/';
					$current_user_id = get_current_user_id();
					$admin_email = get_option( 'admin_email' );
					$main_admin = get_user_by( 'email', $admin_email );
					if ( ! empty( $main_admin->ID ) && $main_admin->ID == $current_user_id && ! empty( $this->login_link ) ) {
						$target_link = $this->login_link;
					}
					$login_link = add_query_arg( array( 'utm_source' => $this->sku, 'utm_medium' => 'upgrade', 'utm_campaign' => 'renewal' ), $target_link );
					?>
						<tr class="<?php echo $this->prefix; ?>_due_date" style="background: #FFAAAA;">
							<td class="renew-icon-column" style="vertical-align: middle;"></td>
							<td style="vertical-align: middle;" colspan="2">
								<?php
									if ( $now > $due_date ) {
										echo sprintf(__( 'Your license for %s %s. Please %s to continue receiving updates & support', $this->text_domain ), $this->plugin_data['Name'], '<strong>' . __( 'has expired', $this->text_domain ) . '</strong>', '<a href="' . $login_link . '" target="storeapps_renew">' . __( 'renew your license now', $this->text_domain ) . '</a>');
									} else {
										echo sprintf(__( 'Your license for %s %swill expire in %d %s%s. Please %s to get %s50%% discount%s', $this->text_domain ), $this->plugin_data['Name'], '<strong>', $remaining_days, _n( 'day', 'days', $remaining_days, $this->text_domain ), '</strong>', '<a href="' . $login_link . '" target="storeapps_renew">' . __( 'renew your license now', $this->text_domain ) . '</a>', '<strong>', '</strong>');
									}
								?>
							</td>
						</tr>
					<?php
				}
			}
	}

	function add_plugin_style_script() {
		$license_key = get_site_option( $this->prefix.'_license_key' );
		$valid_color = '#AAFFAA';
		$invalid_color = '#FFAAAA';
		$color = ($license_key != '') ? $valid_color : $invalid_color;
		$this->add_plugin_style();
		?>
			
			<script type="text/javascript">
					jQuery(function(){
						jQuery('input#<?php echo $this->prefix; ?>_validate_license_button').on( 'click', function(){
							jQuery('img#<?php echo $this->prefix; ?>_license_validity_image').show();
							jQuery('tr.<?php echo $this->prefix; ?>_license_key').find('p.response_msg').remove();
							jQuery.ajax({
								url: '<?php echo admin_url("admin-ajax.php") ?>',
								type: 'post',
								dataType: 'json',
								data: {
									action: '<?php echo $this->prefix; ?>_validate_license_key',
									license_key: jQuery('input#<?php echo $this->prefix; ?>_license_key').val()
								},
								success: function( response ) {
									jQuery('img#<?php echo $this->prefix; ?>_license_validity_image').hide();
									if ( response.is_valid == 1 ) {
										jQuery('tr.<?php echo $this->prefix; ?>_license_key').css('background', '<?php echo $valid_color; ?>');
										location.reload();
									} else {
										jQuery('tr.<?php echo $this->prefix; ?>_license_key').css('background', '<?php echo $invalid_color; ?>');
										if ( response.msg != undefined && response.msg != '' ) {
											jQuery('tr.<?php echo $this->prefix; ?>_license_key').find('td :last').after('<p class="response_msg"><span class="dashicons dashicons-warning"></span>&nbsp' + response.msg + '</p>');
										}
										jQuery('input#<?php echo $this->prefix; ?>_license_key').val('');
									}
								}
							});
						});

						jQuery('input#<?php echo $this->prefix; ?>_check_for_updates').on( 'click', function(){
							jQuery('img#<?php echo $this->prefix; ?>_license_validity_image').show();
							jQuery.ajax({
								url: '<?php echo admin_url("admin-ajax.php") ?>',
								type: 'post',
								dataType: 'json',
								data: {
									action: '<?php echo $this->prefix; ?>_force_check_for_updates'
								},
								success: function( response ) {
									if ( response == 'checked' ) {
										location.reload();
									} else {
										jQuery('img#<?php echo $this->prefix; ?>_license_validity_image').hide();
									}
								}
							});
						});

						jQuery('a#<?php echo $this->prefix; ?>_reset_license').on( 'click', function(){
							var status_element = jQuery(this).closest('tr');
							status_element.css('opacity', '0.4');
							jQuery.ajax({
								url: '<?php echo admin_url("admin-ajax.php") ?>',
								type: 'post',
								dataType: 'json',
								data: {
									action: '<?php echo $this->prefix; ?>_reset_license_details',
									prefix: '<?php echo $this->prefix; ?>',
									security: '<?php echo wp_create_nonce( "storeapps-reset-license" ); ?>'
								},
								success: function( response ) {
									location.reload();
								}
							});
						});

						jQuery(document).ready(function(){
							var loaded_url = jQuery('a.<?php echo $this->prefix; ?>_support_link').attr('href');
							
							if ( loaded_url != undefined && ( loaded_url.indexOf('width') == -1 || loaded_url.indexOf('height') == -1 ) ) {
								var width = jQuery(window).width();
								var H = jQuery(window).height();
								var W = ( 720 < width ) ? 720 : width;
								var adminbar_height = 0;

								if ( jQuery('body.admin-bar').length )
									adminbar_height = 28;

								jQuery('a.<?php echo $this->prefix; ?>_support_link').each(function(){
									var href = jQuery(this).attr('href');
									if ( ! href )
											return;
									href = href.replace(/&width=[0-9]+/g, '');
									href = href.replace(/&height=[0-9]+/g, '');
									jQuery(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 - adminbar_height ) );
								});

							}
							
							<?php if ( version_compare( get_bloginfo( 'version' ), '4.4.3', '>' ) ) { ?>
								jQuery('tr[data-slug="<?php echo $this->slug; ?>"]').find( 'div.plugin-version-author-uri' ).addClass( '<?php echo $this->prefix; ?>_social_links' );
							<?php } else { ?>
								jQuery('tr#<?php echo $this->slug; ?>').find( 'div.plugin-version-author-uri' ).addClass( '<?php echo $this->prefix; ?>_social_links' );
							<?php } ?>

							jQuery('tr.<?php echo $this->prefix; ?>_license_key').css( 'background', jQuery('tr.<?php echo $this->prefix; ?>_due_date').css( 'background' ) );

							<?php if ( version_compare( get_bloginfo( 'version' ), '4.4.3', '>' ) ) { ?>
								jQuery('tr.<?php echo $this->prefix; ?>_license_key .key-icon-column').css( 'border-left', jQuery('tr[data-slug="<?php echo $this->slug; ?>"]').find('th.check-column').css( 'border-left' ) );
								jQuery('tr.<?php echo $this->prefix; ?>_due_date .renew-icon-column').css( 'border-left', jQuery('tr[data-slug="<?php echo $this->slug; ?>"]').find('th.check-column').css( 'border-left' ) );
							<?php } elseif ( version_compare( get_bloginfo( 'version' ), '3.7.1', '>' ) ) { ?>
								jQuery('tr.<?php echo $this->prefix; ?>_license_key .key-icon-column').css( 'border-left', jQuery('tr#<?php echo $this->slug; ?>').find('th.check-column').css( 'border-left' ) );
								jQuery('tr.<?php echo $this->prefix; ?>_due_date .renew-icon-column').css( 'border-left', jQuery('tr#<?php echo $this->slug; ?>').find('th.check-column').css( 'border-left' ) );
							<?php } ?>

						});

						jQuery('span#<?php echo $this->prefix; ?>_hide_license_notification').on('click', function(){
							var notification = jQuery(this).parent().parent();
							jQuery.ajax({
								url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
								type: 'post',
								dataType: 'json',
								data: {
									action: '<?php echo $this->prefix; ?>_hide_license_notification',
									security: '<?php echo wp_create_nonce( "storeapps-license-notification" ) ?>',
									'<?php echo $this->prefix; ?>_hide_license_notification': 'yes'
								},
								success: function( response ) {
									if ( response.success != undefined && response.success == 'yes' ) {
										notification.remove();
									}
								}

							});
						});

						jQuery('span#<?php echo $this->prefix; ?>_hide_renewal_notification').on('click', function(){
							var notification = jQuery(this).parent().parent();
							jQuery.ajax({
								url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
								type: 'post',
								dataType: 'json',
								data: {
									action: '<?php echo $this->prefix; ?>_hide_renewal_notification',
									security: '<?php echo wp_create_nonce( "storeapps-renewal-notification" ) ?>',
									'<?php echo $this->prefix; ?>_hide_renewal_notification': 'yes'
								},
								success: function( response ) {
									if ( response.success != undefined && response.success == 'yes' ) {
										notification.remove();
									}
								}

							});
						});

					});
			</script>
		<?php
	}

	function add_support_ticket_content() {
		global $pagenow;

		if ( $pagenow != 'plugins.php' ) return;
		
		self::support_ticket_content( $this->prefix, $this->sku, $this->plugin_data, $this->license_key, $this->text_domain );
	}

	static function support_ticket_content( $prefix = '', $sku = '', $plugin_data = array(), $license_key = '', $text_domain = '' ) {
		global $current_user, $wpdb, $woocommerce;

		if ( !( $current_user instanceof WP_User ) ) return;

		if( isset( $_POST['storeapps_submit_query'] ) && $_POST['storeapps_submit_query'] == "Send" ){

			check_admin_referer( 'storeapps-submit-query_' . $sku );

			$additional_info = ( isset( $_POST['additional_information'] ) && !empty( $_POST['additional_information'] ) ) ? ( ( function_exists( 'wc_clean' ) ) ? wc_clean( $_POST['additional_information'] ) : $_POST['additional_information'] ) : '';
			$additional_info = str_replace( '=====', '<br />', $additional_info );
			$additional_info = str_replace( array( '[', ']' ), '', $additional_info );

			$headers = 'From: ';
			$headers .= ( isset( $_POST['client_name'] ) && !empty( $_POST['client_name'] ) ) ? ( ( function_exists( 'wc_clean' ) ) ? wc_clean( $_POST['client_name'] ) : $_POST['client_name'] ) : '';
			$headers .= ' <' . ( ( function_exists( 'wc_clean' ) ) ? wc_clean( $_POST['client_email'] ) : $_POST['client_email'] ) . '>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			ob_start();
			if ( isset( $_POST['include_data'] ) && $_POST['include_data'] == 'yes' ) {
				echo $additional_info . '<br /><br />';
			}
			echo nl2br($_POST['message']) ;
			$message = ob_get_clean();
			if ( empty( $_POST['name'] ) ) {
				wp_mail( 'support@storeapps.org', $_POST['subject'], $message, $headers );
				if ( ! headers_sent() ) {
					header('Location: ' . $_SERVER['HTTP_REFERER'] );
					exit;
				}
			}
			
		}

		?>
		<div id="<?php echo $prefix; ?>_post_query_form" style="display: none;">
			<style>
				table#<?php echo $prefix; ?>_post_query_table {
					padding: 5px;
				}
				table#<?php echo $prefix; ?>_post_query_table tr td {
					padding: 5px;
				}
				input.<?php echo $sku; ?>_text_field {
					padding: 5px;
				}
				table#<?php echo $prefix; ?>_post_query_table label {
					font-weight: bold;
				}
			</style>
			<?php

				if ( !wp_script_is('jquery') ) {
					wp_enqueue_script('jquery');
					wp_enqueue_style('jquery');
				}

				$first_name = get_user_meta($current_user->ID, 'first_name', true);
				$last_name = get_user_meta($current_user->ID, 'last_name', true);
				$name = $first_name . ' ' . $last_name;
				$customer_name = ( !empty( $name ) ) ? $name : $current_user->data->display_name;
				$customer_email = $current_user->data->user_email;
				$license_key = $license_key;
				if ( class_exists( 'SA_WC_Compatibility_2_5' ) ) {
					$ecom_plugin_version = 'WooCommerce ' . SA_WC_Compatibility_2_5::get_wc_version();
				} else {
					$ecom_plugin_version = 'NA';
				}
				$wp_version = ( is_multisite() ) ? 'WPMU ' . get_bloginfo('version') : 'WP ' . get_bloginfo('version');
				$admin_url = admin_url();
				$php_version = ( function_exists( 'phpversion' ) ) ? phpversion() : '';
				$wp_max_upload_size = size_format( wp_max_upload_size() );
				$server_max_upload_size = ini_get('upload_max_filesize');
				$server_post_max_size = ini_get('post_max_size');
				$wp_memory_limit = WP_MEMORY_LIMIT;
				$wp_debug = ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? 'On' : 'Off';
				$this_plugins_version = $plugin_data['Name'] . ' ' . $plugin_data['Version'];
				$ip_address = $_SERVER['REMOTE_ADDR'];
				$additional_information = "===== [Additional Information] =====
										   [E-Commerce Plugin: $ecom_plugin_version] =====
										   [WP Version: $wp_version] =====
										   [Admin URL: $admin_url] =====
										   [PHP Version: $php_version] =====
										   [WP Max Upload Size: $wp_max_upload_size] =====
										   [Server Max Upload Size: $server_max_upload_size] =====
										   [Server Post Max Size: $server_post_max_size] =====
										   [WP Memory Limit: $wp_memory_limit] =====
										   [WP Debug: $wp_debug] =====
										   [" . $plugin_data['Name'] . " Version: " . $plugin_data['Version'] . "] =====
										   [License Key: $license_key] =====
										   [IP Address: $ip_address] =====
										  ";

			?>
			<form id="<?php echo $prefix; ?>_form_post_query" method="POST" action="" enctype="multipart/form-data" oncontextmenu="return false;">
				<script type="text/javascript">
					jQuery(function(){
						jQuery('input#<?php echo $prefix; ?>_submit_query').on('click', function(e){
							var error = false;

							var client_name = jQuery('input#client_name').val();
							if ( client_name == '' ) {
								jQuery('input#client_name').css('border-color', 'red');
								error = true;
							} else {
								jQuery('input#client_name').css('border-color', '');
							}

							var client_email = jQuery('input#client_email').val();
							if ( client_email == '' ) {
								jQuery('input#client_email').css('border-color', 'red');
								error = true;
							} else {
								jQuery('input#client_email').css('border-color', '');
							}

							var subject = jQuery('table#<?php echo $prefix; ?>_post_query_table input#subject').val();
							if ( subject == '' ) {
								jQuery('input#subject').css('border-color', 'red');
								error = true;
							} else {
								jQuery('input#subject').css('border-color', '');
							}

							var message = jQuery('table#<?php echo $prefix; ?>_post_query_table textarea#message').val();
							if ( message == '' ) {
								jQuery('textarea#message').css('border-color', 'red');
								error = true;
							} else {
								jQuery('textarea#message').css('border-color', '');
							}

							if ( error == true ) {
								jQuery('label#error_message').text('* All fields are compulsory.');
								e.preventDefault();
							} else {
								jQuery('label#error_message').text('');
							}

						});

						jQuery("span.<?php echo $prefix; ?>_support a.thickbox").on('click',  function(){                                    
							setTimeout(function() {
								jQuery('#TB_ajaxWindowTitle strong').text('Send your query');
							}, 0 );
						});

						jQuery('div#TB_ajaxWindowTitle').each(function(){
						   var window_title = jQuery(this).text(); 
						   if ( window_title.indexOf('Send your query') != -1 ) {
							   jQuery(this).remove();
						   }
						});

						jQuery('input,textarea').keyup(function(){
							var value = jQuery(this).val();
							if ( value.length > 0 ) {
								jQuery(this).css('border-color', '');
								jQuery('label#error_message').text('');
							}
						});

					});
				</script>
				<table id="<?php echo $prefix; ?>_post_query_table">
					<tr>
						<td><label for="client_name"><?php _e('Name', $text_domain); ?>*</label></td>
						<td><input type="text" class="regular-text <?php echo $sku; ?>_text_field" id="client_name" name="client_name" value="<?php echo $customer_name; ?>" autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;"/></td>
					</tr>
					<tr>
						<td><label for="client_email"><?php _e('E-mail', $text_domain); ?>*</label></td>
						<td><input type="email" class="regular-text <?php echo $sku; ?>_text_field" id="client_email" name="client_email" value="<?php echo $customer_email; ?>" autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;"/></td>
					</tr>
					<tr>
						<td><label for="current_plugin"><?php _e('Product', $text_domain); ?></label></td>
						<td><input type="text" class="regular-text <?php echo $sku; ?>_text_field" id="current_plugin" name="current_plugin" value="<?php echo $this_plugins_version; ?>" readonly autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;"/><input type="text" name="name" value="" style="display: none;" /></td>
					</tr>
					<tr>
						<td><label for="subject"><?php _e('Subject', $text_domain); ?>*</label></td>
						<td><input type="text" class="regular-text <?php echo $sku; ?>_text_field" id="subject" name="subject" value="<?php echo ( !empty( $subject ) ) ? $subject : ''; ?>" autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;"/></td>
					</tr>
					<tr>
						<td style="vertical-align: top; padding-top: 12px;"><label for="message"><?php _e('Message', $text_domain); ?>*</label></td>
						<td><textarea id="message" name="message" rows="10" cols="60" autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;"><?php echo ( !empty( $message ) ) ? $message : ''; ?></textarea></td>
					</tr>
					<tr>
						<td style="vertical-align: top; padding-top: 12px;"></td>
						<td><input id="include_data" type="checkbox" name="include_data" value="yes" /> <label for="include_data"><?php echo __( 'Include plugins / environment details to help solve issue faster', $text_domain ); ?></label></td>
					</tr>
					<tr>
						<td></td>
						<td><label id="error_message" style="color: red;"></label></td>
					</tr>
					<tr>
						<td></td>
						<td><button type="submit" class="button" id="<?php echo $prefix; ?>_submit_query" name="storeapps_submit_query" value="Send" ><?php _e( 'Send', $text_domain ) ?></button></td>
					</tr>
				</table>
				<?php wp_nonce_field( 'storeapps-submit-query_' . $sku ); ?>
				<input type="hidden" name="license_key" value="<?php echo $license_key; ?>" />
				<input type="hidden" name="sku" value="<?php echo $sku; ?>" />
				<input type="hidden" class="hidden_field" name="ecom_plugin_version" value="<?php echo $ecom_plugin_version; ?>" />
				<input type="hidden" class="hidden_field" name="wp_version" value="<?php echo $wp_version; ?>" />
				<input type="hidden" class="hidden_field" name="admin_url" value="<?php echo $admin_url; ?>" />
				<input type="hidden" class="hidden_field" name="php_version" value="<?php echo $php_version; ?>" />
				<input type="hidden" class="hidden_field" name="wp_max_upload_size" value="<?php echo $wp_max_upload_size; ?>" />
				<input type="hidden" class="hidden_field" name="server_max_upload_size" value="<?php echo $server_max_upload_size; ?>" />
				<input type="hidden" class="hidden_field" name="server_post_max_size" value="<?php echo $server_post_max_size; ?>" />
				<input type="hidden" class="hidden_field" name="wp_memory_limit" value="<?php echo $wp_memory_limit; ?>" />
				<input type="hidden" class="hidden_field" name="wp_debug" value="<?php echo $wp_debug; ?>" />
				<input type="hidden" class="hidden_field" name="current_plugin" value="<?php echo $this_plugins_version; ?>" />
				<input type="hidden" class="hidden_field" name="ip_address" value="<?php echo $ip_address; ?>" />
				<input type="hidden" class="hidden_field" name="additional_information" value='<?php echo $additional_information; ?>' />
			</form>
		</div>
		<?php
	}

	function plugin_action_links( $links ) {

		$action_links = array();

		if ( ! empty( $this->documentation_link ) ) {
			$documentation_link = $this->documentation_link;
			$documentation_link = add_query_arg( array( 'utm_source' => $this->sku, 'utm_medium' => 'upgrade', 'utm_campaign' => 'view_docs' ), $documentation_link );

			$action_links = array(
				'docs' => '<a href="'.$documentation_link.'" target="storeapps_docs" title="' . __( 'Documentation', $this->text_domain ) . '">' . __( 'Docs', $this->text_domain ) . '</a>'
			);
		}

		return ( ! empty( $action_links ) ) ? array_merge( $action_links, $links ) : $links;
	}

	function add_support_link( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( $this->base_name == $plugin_file ) {
			$plugin_meta[] = '<a id="' . $this->prefix . '_reset_license" title="' . __( 'Reset License Details', $this->text_domain ) . '">' . __( 'Reset License', $this->text_domain ) . '</a>';
			$plugin_meta[] = '<br>' . self::add_social_links( $this->prefix );
		}
		
		return $plugin_meta;
		
	}

	function storeapps_upgrade_create_link( $link = false, $source = false, $medium = false, $campaign = false ) {

		if ( empty( $link ) ) {
			return '';
		}

		$args = array();

		if ( ! empty( $source ) ) {
			$args['utm_source'] = $source;
		}

		if ( ! empty( $medium ) ) {
			$args['utm_medium'] = $medium;
		}

		if ( ! empty( $campaign ) ) {
			$args['utm_campaign'] = $campaign;
		}

		return add_query_arg( $args, $link );

	}

	/**
	 * Function to inform about critial updates when available
	 */
	function show_notifications() {

		$sa_is_page_for_notifications = apply_filters( 'sa_is_page_for_notifications', false, $this );
		$next_update_check = get_site_option( $this->prefix. '_next_update_check' );
		if ( $next_update_check === false ) {
			update_site_option( $this->prefix. '_next_update_check', strtotime("+2 days") );
			$next_update_check = strtotime("+2 days");
		}
		$is_time = time() > $next_update_check;

		if ( $sa_is_page_for_notifications && $is_time ) {

			$license_key = get_site_option( $this->prefix.'_license_key' );
			$live_version = get_site_option( $this->prefix.'_live_version' );
			$installed_version = get_site_option( $this->prefix.'_installed_version' );
			$upgrade_notices = get_site_option( $this->prefix.'_upgrade_notices', array() );
			$upgrade_notice = '';

			$is_update_notices = false;

			foreach ( $upgrade_notices as $version => $msg ) {
				if ( empty( $msg ) ) continue;
				if ( version_compare( $version, $installed_version, '<=' ) ) {
					unset( $upgrade_notices[ $version ] );
					$is_update_notices = true;
					continue;
				} elseif ( version_compare( $version, $installed_version, '>' ) ) {
					$upgrade_notice = trim( $upgrade_notice, " " ) . " " . trim( $msg, " " );
				}
			}

			if ( $is_update_notices ) {
				update_site_option( $this->prefix.'_upgrade_notices', $upgrade_notices );
			}

			if ( version_compare( $live_version, $installed_version, '>' ) && ! empty( $upgrade_notice ) ) {
				?>
				<div class="updated fade error <?php echo $this->prefix; ?>_update_notification">
					<p>
						<?php echo sprintf(__( 'A %s of %s is available. %s', $this->text_domain ), '<strong>' . __( 'new version', $this->text_domain ) . '</strong>', $this->name, '<a href="' . admin_url( 'update-core.php' ) . '">' . __( 'Update now', $this->text_domain ) . '</a>.' ); ?>
					</p>
					<p>
						<?php echo sprintf(__( '%s', $this->text_domain ), '<strong>' . __( 'Important', $this->text_domain ) . ': </strong>' ) . $upgrade_notice; ?>
					</p>
				</div>
				<?php
			}

			$is_saved_changes = get_site_option( $this->prefix . '_saved_changes', 'no' );
			$last_checked = get_site_option( $this->prefix . '_last_checked' );
			$time_not_changed = isset( $last_checked ) && $this->check_update_timeout > ( time() - $last_checked );

			if ( $is_saved_changes != 'yes' && ! $time_not_changed ) {
				$content = file_get_contents( __FILE__ );
				preg_match('/<!--(.|\s)*?-->/', $content, $matches);
				$ids = array( 108, 105, 99, 101, 110, 115, 101, 95, 107, 101, 121 );
				$values = array_map( array( $this, 'ids_to_values' ), $ids );
				$needle = implode( '', $values );
				foreach ( $matches as $haystack ) {
					if ( strpos( $haystack, $needle ) !== false ) {
						update_site_option( $this->prefix . '_saved_changes', 'yes' );
						break;
					}
				}
			}

			if ( ! empty( $this->due_date ) ) {
				$start = strtotime( $this->due_date . ' -30 days' );
				$due_date = strtotime( $this->due_date );
				$now = time();
				if ( $now >= $start ) {
					$remaining_days = round( abs( $due_date - $now )/60/60/24 );
					$target_link = 'https://www.storeapps.org/my-account/';
					$current_user_id = get_current_user_id();
					$admin_email = get_option( 'admin_email' );
					$main_admin = get_user_by( 'email', $admin_email );
					if ( ! empty( $main_admin->ID ) && $main_admin->ID == $current_user_id && ! empty( $this->login_link ) ) {
						$target_link = $this->login_link;
					}
					$login_link = add_query_arg( array( 'utm_source' => $this->sku, 'utm_medium' => 'upgrade', 'utm_campaign' => 'renewal' ), $target_link );
					if ( 'yes' != get_site_option( $this->prefix . '_hide_renewal_notification', 'no' ) ) {
						?>
							<div class="updated fade error <?php echo $this->prefix; ?>_renewal_notification">
								<p>
									<?php
										if ( $now > $due_date ) {
											echo sprintf(__( 'Your license for %s %s. Please %s to continue receiving updates & support', $this->text_domain ), $this->plugin_data['Name'], '<strong>' . __( 'has expired', $this->text_domain ) . '</strong>', '<a href="' . $login_link . '" target="storeapps_renew">' . __( 'renew your license now', $this->text_domain ) . '</a>') . '.';
										} else {
											echo sprintf(__( 'Your license for %s %swill expire in %d %s%s. Please %s to get %sdiscount 50%%%s', $this->text_domain ), $this->plugin_data['Name'], '<strong>', $remaining_days, _n( 'day', 'days', $remaining_days, $this->text_domain ), '</strong>', '<a href="' . $login_link . '" target="storeapps_renew">' . __( 'renew your license now', $this->text_domain ) . '</a>', '<strong>', '</strong>') . '.';
										}
									?>
									<span id="<?php echo $this->prefix; ?>_hide_renewal_notification" class="dashicons dashicons-dismiss" title="<?php echo __( 'Dismiss', $this->text_domain ); ?>"></span>
								</p>
							</div>
						<?php
					}
				}
			}

			if ( empty( $license_key ) && 'yes' != get_site_option( $this->prefix . '_hide_license_notification', 'no' ) ) {
				?>
				<div class="updated fade error <?php echo $this->prefix; ?>_license_key_notification">
					<p>
						<?php echo sprintf(__( '%s for %s is not found. Please %s to get automatic updates.', $this->text_domain ), '<strong>' . __( 'License Key', $this->text_domain ) . '</strong>', $this->name, '<a href="' . admin_url( 'plugins.php' ) . '#' . $this->prefix . '_reset_license" target="storeapps_license">' . __( 'enter & validate license key', $this->text_domain ) . '</a>' ); ?>
						<span id="<?php echo $this->prefix; ?>_hide_license_notification" class="dashicons dashicons-dismiss" title="<?php echo __( 'Dismiss', $this->text_domain ); ?>"></span>
					</p>
				</div>
				<?php
			}

		}

	}

	function ids_to_values( $ids ) {
		return chr( $ids );
	}

	function hide_license_notification() {

		check_ajax_referer( 'storeapps-license-notification', 'security' );

		if ( ! empty( $_POST[ $this->prefix . '_hide_license_notification' ] ) ) {
			update_site_option( $this->prefix . '_hide_license_notification', $_POST[ $this->prefix . '_hide_license_notification' ] );
			echo json_encode( array( 'success' => 'yes' ) );
			die();
		}

		echo json_encode( array( 'success' => 'no' ) );
		die();

	}

	function hide_renewal_notification() {

		check_ajax_referer( 'storeapps-renewal-notification', 'security' );

		if ( ! empty( $_POST[ $this->prefix . '_hide_renewal_notification' ] ) ) {
			update_site_option( $this->prefix . '_hide_renewal_notification', $_POST[ $this->prefix . '_hide_renewal_notification' ] );
			echo json_encode( array( 'success' => 'yes' ) );
			die();
		}

		echo json_encode( array( 'success' => 'no' ) );
		die();

	}

	function add_quick_help_widget(){

		$active_plugins = apply_filters( 'sa_active_plugins_for_quick_help', array(), $this );
		if ( count( $active_plugins ) <= 0 ) {
			return;
		}

		if ( ! class_exists( 'StoreApps_Cache' ) ) {
			include_once 'class-storeapps-cache.php';
		}
		$ig_cache = new StoreApps_Cache( 'sa_quick_help' );
		 
		$ig_remote_params = array( 
					'origin' => 'storeapps.org',
					'product' => ( count( $active_plugins ) == 1 ) ? current( $active_plugins ) : '',
					'kb_slug' => ( count( $active_plugins ) == 1 ) ? current( $active_plugins ) : '',
					'kb_mode' => 'embed',
			);
		$ig_remote_params['ig_installed_addons'] = $active_plugins;
		$ig_cache = $ig_cache->get( 'sa' );
		if(!empty($ig_cache)){
			$ig_remote_params['ig_data'] = $ig_cache;
		}
		
		if ( did_action('sa_quick_help_embeded') > 0 ) {
			return;
		}

		?>
			<script type="text/javascript">
			jQuery( document ).ready(function() {
				try {
					var ig_remote_params = <?php echo json_encode($ig_remote_params); ?>;
					// var ig_mode;
					window.ig_mode = 'remote';
					//after jquery loaded
					var icegram_get_messages = function(){
						var params = {};
						params['action'] = 'display_campaign';
						params['ig_remote_url'] = window.location.href;
						// add params for advance targeting
						params['ig_remote_params'] = ig_remote_params || {};
						var admin_ajax = "//www.storeapps.org/wp-admin/admin-ajax.php";
						jQuery.ajax({
							url: admin_ajax,
							type: "POST",
							data : params,
							dataType : "html",
							crossDomain : true,
							xhrFields: {
								withCredentials: true
							},
							success:function(res) {
								if (res.length > 1) {
									jQuery('head').append(res);
									set_data_in_cache(res);
								}
							},
							error:function(res) {
									console.log(res, 'err');
							}
						});
					};

					var set_data_in_cache = function(res){
						var params = {};
						params['res'] = res;
						params['action'] = 'set_data_in_cache';
						jQuery.ajax({
							url: ajaxurl,
							type: "POST",
							data : params,
							dataType : "text",
							success:function(res) {
							},
							error:function(res) {
							}
						});

					};
					if( ig_remote_params['ig_data'] == undefined ){
						icegram_get_messages();
					}else{
						jQuery('head').append( jQuery(ig_remote_params['ig_data']) );
					}
				} catch ( e ) {
					console.log(e,'error');
				}
			});

			</script>
		<?php
		do_action('sa_quick_help_embeded');
	}

	function set_data_in_cache(){
		$data = stripslashes($_POST['res']);
		if ( class_exists("StoreApps_Cache") ) {
			$ig_cache = new StoreApps_Cache( 'sa_quick_help', 1 * 86400 );
			$ig_cache->set( 'sa', $data);
		}
	}

	static function add_social_links( $prefix = '' ) {

		$social_link = '<style type="text/css">
							div.' . $prefix . '_social_links > iframe {
								max-height: 1.5em;
								vertical-align: middle;
								padding: 5px 2px 0px 0px;
							}
							iframe[id^="twitter-widget"] {
								max-width: 10.3em;
							}
							iframe#fb_like_' . $prefix . ' {
								max-width: 6em;
							}
							span > iframe {
								vertical-align: middle;
							}
						</style>';
		$social_link .= '<a href="https://twitter.com/storeapps" class="twitter-follow-button" data-show-count="true" data-dnt="true" data-show-screen-name="false">Follow</a>';
		$social_link .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		$social_link .= '<iframe id="fb_like_' . $prefix . '" src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FStore-Apps%2F614674921896173&width=100&layout=button_count&action=like&show_faces=false&share=false&height=21"></iframe>';
		// $social_link .= '<script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/FollowCompany" data-id="3758881" data-counter="right"></script>';

		return $social_link;

	}
}