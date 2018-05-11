<?php
/**
 * Connect popup.
 *
 * @package convertpro
 */

	$account_name  = -1;
	$service       = -1;
	$account_title = -1;
	$meta          = ( isset( $style_id ) ) ? get_post_meta( $style_id, 'connect' ) : array();

	$meta = ( ! empty( $meta ) ) ? call_user_func_array( 'array_merge', call_user_func_array( 'array_merge', $meta ) ) : array();

if ( ! empty( $meta ) ) {

	$cp_connect_settings = ( isset( $meta['cp_connect_settings'] ) && -1 != $meta['cp_connect_settings'] ) ? ConvertPlugHelper::get_decoded_array( $meta['cp_connect_settings'] ) : array();

	if ( ! empty( $cp_connect_settings ) ) {
		$service      = $cp_connect_settings['cp-integration-service'];
		$account_name = $cp_connect_settings['cp-integration-account-slug'];
		$term_data    = get_term_by( 'slug', $account_name, CP_CONNECTION_TAXONOMY );
		if ( ! is_wp_error( $term_data ) ) {
			$account_title = isset( $term_data->name ) ? $term_data->name : '';
		}
	}
}

?>
<div class="cp-md-modal cp-md-effect-1" id="cp-md-modal-1">
	<div class="cp-md-content">  
		<div class="cp-response-wrap"></div>  
		<div class="cp-connect-screen-overlay cp-md-loader" style="overflow: hidden;background: #FCFCFC;position: fixed;width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999;">
			<div class="cp-absolute-loader" style="visibility: visible;overflow: hidden;">
				<div class="cp-modal-connect-loader">
					<h2 class="cp-modal-loader-text"><?php _e( 'Loading...', 'convertpro' ); ?></h2>
					<div class="cp-modal-loader-wrap">
						<div class="cp-modal-loader-bar">
							<div class="cp-modal-loader-shadow"></div>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="cp-md-modal-content">
			<div class="cp-md-modal-header">
				<img src=""><!-- Mailer image comming from JS -->
				<h3 class="cp-md-modal-title"></h3><!-- Title comming from JS -->
				<span class="cp-md-close"><i class="dashicons dashicons-no-alt"></i></span>
			</div>
			<div class="cp-md-modal-body" data-at="cp-md-modal_body">
				<div class="cp-md-form-integration cp-md-rightcolumn1">
					<div class="cp-md-steps-wrap">
						<ul class="cp-md-steps">
							<li class="cp-md-step cp-md-step-1 cp-present-step">
								<strong><?php _e( 'Step 1', 'convertpro' ); ?></strong>
							</li>
							<li class="cp-md-step cp-md-step-2 cp-future-step">
								<span class="cp-md-step-separator"></span>
								<strong><?php _e( 'Step 2', 'convertpro' ); ?></strong>
							</li>
							<?php if ( ! isset( $in_sync_flag ) ) { ?>
							<li class="cp-md-step cp-md-step-3 cp-future-step">
								<span class="cp-md-step-separator"></span>
								<strong><?php _e( 'Step 3', 'convertpro' ); ?></strong>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="cp-md-contents">
						<form class="cp-api-integration-form" method="post" action="">
							<div class="cp-integration-name">
							</div>
							<div class="cp-api-selection-list"></div>
						</form>
						<form class="cp-add-new-account-form" action="" method="post" style="display: none;">
							<div class="cp-new-account-fields" >
							</div>
						</form>
						<?php
						if ( ! isset( $in_sync_flag ) ) {
							$in_sync_flag = '';
						}
						?>
						<form class="cp-account-list-form" action="" method="post" style="display: none;">
							<input type="hidden" name="cp-integration-account-slug" value="<?php echo $account_name; ?>" data-account-title="<?php echo $account_title; ?>">
							<input type="hidden" name="cp-integration-service" value="<?php echo $service; ?>">
							<input type="hidden" name="cp-integration-source" value="<?php echo $in_sync_flag; ?>">
							<div class="cp-new-account-fields" >
							</div>
						</form>
						<form class="cp-account-mapping-form" action="" method="post" style="display: none;">
							<div class="cp-mapping-fields" ><table><tbody></tbody></table></div>
						</form>
					</div>
				</div>
			</div>
			<div class="cp-md-modal-footer">
				<div class="cp-md-info-wrap"><a href="#" rel="noopener" target="_blank"><?php _e( 'Where to find this?', 'convertpro' ); ?></a></div>
				<button type="button" class="cp-btn-default cp-trans-button cp-action-button cp-add-new-account"><?php _e( 'Add New Integration', 'convertpro' ); ?></button>
				<button type="button" class="cp-btn-default cp-trans-button cp-action-button cp-use-existing-account"><?php _e( 'Use Existing Integration', 'convertpro' ); ?></button>
				<button type="button" class="cp-btn-default cp-primary-button cp-action-button cp-authenticate-connects"><?php _e( 'Authenticate', 'convertpro' ); ?></button>
				<button type="button" class="cp-btn-default cp-trans-button cp-action-button cp-back-connects" data-back-step="0"><?php _e( 'Back', 'convertpro' ); ?></button>
				<button type="button" class="cp-btn-default cp-primary-button cp-action-button cp-next-connects" data-next-step="2"><?php _e( 'Next', 'convertpro' ); ?></button>
				<button type="button" class="cp-btn-default cp-primary-button cp-action-button cp-save-connects"><?php _e( 'Save', 'convertpro' ); ?></button>
			</div>
		</div>
	</div>
</div>
<div class="cp-md-overlay"></div>
