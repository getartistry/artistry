<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "mailer".
if ( function_exists( 'cp_add_input_type' ) ) {

	cp_add_input_type( 'connect', 'connect_settings_field' );
}

/**
 * Function Name: connect_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function connect_settings_field( $name, $settings, $value ) {
	ob_start();

	if ( class_exists( 'Cp_V2_Services_Loader' ) && class_exists( 'CP_Addon_Loader' ) ) {

		$input_name   = $name;
		$type         = isset( $settings['type'] ) ? $settings['type'] : '';
		$class        = isset( $settings['class'] ) ? $settings['class'] : '';
		$services     = ConvertPlugServices::get_services_data();
		$img_src      = '';
		$term_name    = '';
		$account_name = '';
		$show_mapping = false;
		$service_name = '';
		$has_test     = false;

		$connection_meta = get_post_meta( esc_attr( $_GET['post'] ), 'connect' );

		$meta = ( ! empty( $connection_meta ) ) ? call_user_func_array( 'array_merge', call_user_func_array( 'array_merge', $connection_meta ) ) : array();

		if ( ! empty( $meta ) && isset( $meta['cp_connect_settings'] ) && isset( $meta['cp_mapping'] ) ) {
			$show_mapping = ( -1 != $meta['cp_connect_settings'] || -1 != $meta['cp_mapping'] ) ? true : false;
		}

		if ( ! empty( $meta ) && isset( $meta['cp_connect_settings'] ) && isset( $meta['cp_mapping'] ) ) {

			$cp_connect_settings = ( -1 != $meta['cp_connect_settings'] ) ? ConvertPlugHelper::get_decoded_array( $meta['cp_connect_settings'] ) : array();

			$cp_mapping = ( -1 != $meta['cp_mapping'] ) ? ConvertPlugHelper::get_decoded_array( $meta['cp_mapping'] ) : array();

			if ( ! empty( $cp_connect_settings ) ) {
				$img_src      = $cp_connect_settings['cp-integration-service'];
				$service_name = isset( $services[ $img_src ]['name'] ) ? $services[ $img_src ]['name'] : '';

				$has_test = isset( $services[ $img_src ]['has_test_connection'] ) ? $services[ $img_src ]['has_test_connection'] : false;

				$term         = get_term_by( 'slug', $cp_connect_settings['cp-integration-account-slug'], CP_CONNECTION_TAXONOMY );
				$term_name    = isset( $term->name ) ? $term->name : '';
				$account_name = $cp_connect_settings['cp-integration-account-slug'];
			}
		}
			?>
			<div class="cp-connect-integration-meta <?php echo ( ! $show_mapping ) ? 'cp-hidden' : ''; ?>">				
				<span class="cp-active-icon"><?php _e( 'Active', 'convertpro' ); ?></span>				
				<div class="cp-meta-wrap">
					<img src="<?php echo ( '' != $img_src ) ? CP_SERVICES_BASE_URL . 'assets/images/' . $img_src . '.png' : ''; ?>">
				</div>
				<div class="cp-action-wrap">
					<div class="cp-active-title"><?php echo $term_name; ?></div>
					<a href="javascript:void(0);" class="cp-btn-default cp-trans-button cp-change-account" data-service-title="<?php echo $service_name; ?>" data-account="<?php echo $account_name; ?>" data-service="<?php echo $img_src; ?>"><?php _e( 'Edit', 'convertpro' ); ?></a>
					<a href="javascript:void(0);" class="cp-btn-default cp-primary-button cp-remove-account" data-account="<?php echo $account_name; ?>"><?php _e( 'Remove', 'convertpro' ); ?></a>

					<?php do_action( 'cpro_after_connect_action_links', $account_name, $service_name, $has_test ); ?>
				</div>
			</div>
		<div class="cp-connect-integration-wrap <?php echo ( $show_mapping ) ? 'cp-hidden' : ''; ?>">
			<div class="cp-connect-integration">
			<?php
			if ( ! empty( $services ) ) {
				foreach ( $services as $key => $service ) {
					if ( 'mailpoet' == $key && ( ! class_exists( 'WYSIJA' ) && ! ( defined( 'MAILPOET_INITIALIZED' ) && MAILPOET_INITIALIZED ) ) ) {
						continue;
					}

					if ( 'mymail' == $key && ! defined( 'MAILSTER_VERSION' ) ) {
						continue;
					}
			?>
				<div class="cp-connects-fields cp-element-container cp-md-trigger" data-modal="cp-md-modal-1" data-tags="<?php echo $key; ?>">
					<a href="javascript:void(0);" class="cp-connect-service-list cp-connect-service-<?php echo $key; ?>" data-service="<?php echo $key; ?>"><img src="<?php echo CP_SERVICES_BASE_URL . 'assets/images/' . $key . '.png'; ?>">
						<div class="cp-services-title" data-title="<?php echo $service['name']; ?>"><?php echo $service['name']; ?></div>
					</a>
				</div>
			<?php
				}
			}
			?>
			</div>
		</div>
		<?php

		$connect_settings = ( ! empty( $meta ) && isset( $meta['cp_connect_settings'] ) ) ? $meta['cp_connect_settings'] : '-1';

		$mapping_settings = ( ! empty( $meta ) && isset( $meta['cp_mapping'] ) ) ? $meta['cp_mapping'] : '-1';

		?>
		<input type="hidden" name="cp_connect_settings" value='<?php echo esc_attr( $connect_settings ); ?>'>
		<input type="hidden" name="cp_mapping" value='<?php echo esc_attr( $mapping_settings ); ?>'>
	<?php
	} else {
	?>
	<div class="cp-services-error">
		<?php
		$link = CP_V2_Tab_Menu::get_page_url( 'general-settings' ) . '#addons';
		echo '<p>' . __( 'You cannot connect with third party services now!', 'convertpro' ) . '</p>';
		if ( ! class_exists( 'CP_Addon_Loader' ) ) {
			echo '<p>';
			/* translators: %s Convert Pro add on link */
			echo sprintf( __( 'Please make sure the %1$s – Addon is installed and activated. You can do that <a target="_blank" rel="noopener" href="%2$s">here</a>.', 'convertpro' ), CPRO_BRANDING_NAME, $link );
			echo '</p>';
		} elseif ( ! class_exists( 'Cp_V2_Services_Loader' ) ) {
			echo '<p>';
			/* translators: %s connect add on link */
			echo sprintf( __( 'Please make sure you have activated the <strong>Connects</strong> addon. You can do that <a rel="noopener" target="_blank" href="%2$s">here</a>.', 'convertpro' ), CPRO_BRANDING_NAME, $link );
			echo '</p>';
		}
		?>
	</div>
	<?php
	}

	$output = ob_get_clean();

	return $output;
}
