<?php
/**
 * Dashboard Page.
 *
 * @package ConvertPro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// load popup types.
$types_dir = CP_FRAMEWORK_DIR . 'types/';

$types = array(
	'modal_popup',
	'info_bar',
	'slide_in',
	'before_after',
	'inline',
	'widget',
	'welcome_mat',
	'full_screen',
);

foreach ( $types as $type ) {
	$file_path = str_replace( '_', '-', $type );
	$file_path = 'class-cp-' . $file_path;
	if ( file_exists( $types_dir . $file_path . '.php' ) ) {
		require_once( $types_dir . $file_path . '.php' );
	}
}

?>

<div class="wrap about-wrap cp-insights-page about-cp bend">
	<?php
	// Get all campaigns list.
	$campaigns       = cp_get_all_campaigns();
	$create_page_url = CP_V2_Tab_Menu::get_page_url( 'create-new' );
	do_action( 'cp_after_insights_header' );

	?>

	<div class="cp-analytics-wraper">
		<!-- Analytics Impression Data -->

		<!-- Insights accordion  -->

		<?php do_action( 'cp_before_design_list' ); ?>

		<?php

		// Get all campaigns list.
		$campaigns  = cp_get_all_campaigns();
		$option     = '';
		$post_count = wp_count_posts( CP_CUSTOM_POST_TYPE );

		$comp_count = is_object( $post_count ) && isset( $post_count->publish ) ? $post_count->publish : 0;

		$message        = '';
		$status         = '';
		$campaign_count = 0;

		if ( isset( $_REQUEST['message'] ) ) {
			if ( 'success' == $_REQUEST['message'] && 'duplicate' == $_REQUEST['action'] ) {

				$style_id = isset( $_REQUEST['style_id'] ) ? esc_attr( $_REQUEST['style_id'] ) : '';

				if ( '' !== $style_id ) {
					$style_name = get_the_title( (int) $style_id );
					/* translators: %s percentage */
					$message = sprintf( __( 'The call to action was duplicated successfully. The duplicated call to action has the name "%s" ', 'convertpro' ), $style_name );
					$status  = 'success';
				}
			} elseif ( 'error' == $_REQUEST['message'] && 'duplicate' == $_REQUEST['action'] ) {
				$message = __( 'Unable to duplicate style.', 'convertpro' );
				$status  = 'error';
			} elseif ( 'success' == $_REQUEST['message'] && 'delete' == $_REQUEST['action'] ) {
				$message = __( 'The Call to action was deleted successfully!', 'convertpro' );
				$status  = 'success';
			} elseif ( 'success' == $_REQUEST['message'] && 'delete-campaign' == $_REQUEST['action'] ) {
				$message = __( 'The Campaign was deleted successfully!', 'convertpro' );
				$status  = 'success';
			} elseif ( 'error' == $_REQUEST['message'] && 'delete-campaign' == $_REQUEST['action'] ) {
				$message = __( 'Unable to delete campaign.', 'convertpro' );
				$status  = 'error';
			}
		}

		if ( '' !== $message ) {
			$status_class = ( 'error' == $status ) ? 'notice-error' : 'notice-success';
		?>
		<div id="message" class="<?php echo $status_class; ?> notice is-dismissible">
			<p><?php echo $message; ?></p>
		</div>

		<?php } ?>
		<div class="cp-flex-center">
			<div class="cp-button-row cp-camp-head">
				<h2 class="cp-sub-head"><?php _e( 'Call-to-action', 'convertpro' ); ?></h2>
				<span class="title-count theme-count"><?php echo $comp_count; ?></span>
			</div>
			<?php echo apply_filters( 'cp_import_option', $option ); ?>
			<div class="cp-design-btn">
				<a href="<?php echo esc_url( $create_page_url ); ?>" class="cp-md-btn cp-button-style cp-btn-primary"><?php _e( 'Create New', 'convertpro' ); ?></a>
			</div>
		</div>
		<div class="cp-style-container">
			<div id="cp-edit-dropdown" class="cp-edit-content cp-edit-above">
				<a class="cp-rename-action" href="#">
					<span class="cp-question-icon"><i class="dashicons dashicons-editor-spellcheck"></i></span>
					<span class="cp-question-title"><?php _e( 'Rename', 'convertpro' ); ?></span>
				</a>
				<a class="cp-edit-action" href="#">
					<span class="cp-question-icon"><i class="dashicons dashicons-edit"></i></span>
					<span class="cp-question-title"><?php _e( 'Edit', 'convertpro' ); ?></span>
				</a>
				<a class="cp-duplicate-action" href="#">
					<span class="cp-question-icon"><i class="dashicons dashicons-admin-page"></i></span>
					<span class="cp-question-title"><?php _e( 'Duplicate', 'convertpro' ); ?></span>
				</a>
				<a class="cp-campaign-action" href="#">
					<span class="cp-question-icon"><i class="dashicons dashicons-welcome-add-page"></i></span>
					<span class="cp-question-title"><?php _e( 'Group', 'convertpro' ); ?></span>
				</a>
				<?php echo apply_filters( 'cp_export_option', $option ); ?>
				<a class="cp-delete-action" href="#">
					<span class="without-has-tip">
						<span class="cp-question-icon"><i class="dashicons dashicons-trash"></i></span>
						<span class="cp-question-title"><?php _e( 'Delete', 'convertpro' ); ?></span>
					</span>
					<span class="has-tip" data-position="left" title="<?php _e( 'This call to action is part of an A/B test. Please delete the test to be able to delete this call to action.', 'convertpro' ); ?>">
						<span class="cp-question-icon"><i class="dashicons dashicons-trash"></i></span>
						<span class="cp-question-title"><?php _e( 'Delete', 'convertpro' ); ?></span>
					</span>
				</a>
			</div>
			<div class="cp-accordion">
				<?php
				$cp_popups_inst = CP_V2_Popups::get_instance();
				foreach ( $campaigns as $key => $campaign ) {

					$active_acc_class         = '';
					$active_acc_content_class = '';
					$styles                   = $cp_popups_inst->get_popups_by_campaign_id( $campaign->term_id );

					$styles = $cp_popups_inst->get_sorted_styles( $styles );

					if ( 0 == $campaign_count ) {
						$active_acc_class         = 'active';
						$active_acc_content_class = 'open';
					}

					$campaign_name = ucfirst( $campaign->name );

					if ( 'Your Designs' == $campaign_name ) {
						$campaign_name = 'Your Call-to-actions';
					}

					?>
					<div class="cp-accordion-section" data-term-slug="<?php echo $campaign->slug; ?>" data-term="<?php echo $campaign->term_id; ?>">
						<div class="cp-accordion-section-title <?php echo $active_acc_class; ?>" data-title="#cp-accordion-<?php echo $campaign->term_id; ?>">

							<div class="cp-acc-title cp-campaign-title-wrap">
								<span class="cp-campaign-edit-link" data-id="<?php echo $campaign->term_id; ?>">
									<i class="dashicons dashicons-edit"></i>               
								</span>
								<span class="cp-campaign-name" ><?php echo $campaign_name; ?></span>
								<span class="cp-edit-campaign-title">
									<input type="text" value="<?php echo $campaign->name; ?>" class="cp-edit-campaign-text">
								</span>
							</div>

							<div class="cp-switch-wrap">
								<?php
								$delete_notice = __( 'Are you sure you want to delete this campaign?', 'convertpro' );

								$delete_campaign_nonce = wp_create_nonce( 'delete-campaign-' . $campaign->term_id );

								$delete_campaign_url = admin_url( 'admin-post.php?action=cp_delete_campaign&campaign_id=' . $campaign->term_id . '&_wpnonce=' . $delete_campaign_nonce );
								?>
								<?php if ( 'your-designs' != $campaign->slug && cp_is_test_running( $styles ) ) { ?>
										<a class="cp-delete-campaign" href="<?php echo esc_url( $delete_campaign_url ); ?>" data-notice="<?php echo $delete_notice; ?>" title="<?php _e( 'Delete campaign', 'convertpro' ); ?>" data-camaign="<?php echo $campaign->term_id; ?>" >
											<i class="dashicons dashicons-trash"></i>
										</a>
										<?php } ?>
									<span class="cp-close-accordion" title="<?php _e( 'View details', 'convertpro' ); ?>">
									<i class="dashicons dashicons-arrow-down-alt2"></i>
									</span>

							</div>
						</div>
						<div id="cp-accordion-<?php echo $campaign->term_id; ?>" class="cp-accordion-section-content <?php echo $active_acc_content_class; ?>">

						<?php

						$cp_design_table_cols = array(
							'insight'      => array(
								'label' => __( 'Insight', 'convertpro' ),
							),
							'type'         => array(
								'label' => __( 'Type', 'convertpro' ),
							),
							'style_status' => array(
								'label' => __( 'Status', 'convertpro' ),
							),
						);

						$cp_design_table_cols = apply_filters( 'cp_design_list_columns', $cp_design_table_cols );

						?>

							<!-- Accordion labels row -->
							<div class="cp-row cp-insights-label-row cp-row-width-<?php echo count( $cp_design_table_cols ); ?>">
								<div class="cp-acc-4">
									<label><?php _e( 'Name', 'convertpro' ); ?></label>
								</div>
								<div class="cp-col-8 cp-insight-col-<?php echo count( $cp_design_table_cols ); ?>">
									<div class="cp-accordion-block">    
										<?php foreach ( $cp_design_table_cols as $key => $value ) { ?>
											<div class="cp-lead-groups-block cp-<?php echo $key; ?>">                                            
												<label class="cp-label-wrap"><?php echo $value['label']; ?></label>
											</div>                                        
										<?php } ?>
									</div>
								</div>
							</div>
							<!-- Accordion labels row -->
							<!-- Lead Groups Data Row -->
							<?php
							foreach ( $styles as $key => $style ) {
								echo cp_get_insights_row( $style );
							}
							?>
						</div><!--end .accordion-section-content-->
					</div><!--end .accordion-section-->
					<?php
					$campaign_count++; }
					$create_page_url = CP_V2_Tab_Menu::get_page_url( 'create-new' );
					?>
					<p class="cp-no-design <?php echo ( is_array( $campaigns ) && ! empty( $campaigns ) ) ? 'cp-hidden' : ''; ?>">
														<?php
														/* translators: %s percentage */
														echo sprintf( __( "You have not yet created a call-to-action! <a href='%s'>Create one</a> quickly!", 'convertpro' ), esc_url( $create_page_url ) );
					?>
					</p>
			</div>
			<!-- quick View Information -->
			<!-- Modal content-->
			<div id="cp-dashboard-modal" class="cp-info-dashboard-modal cp-common-modal">
				<div class="cp-md-content cp-save-animate-container"> 
					<div class="cp-close-wrap"><i class="dashicons dashicons-no-alt"></i></div>   
					<div class="cp-quick-view-content">                               
						<div class="cp-info-section">                                    
						</div>                     
					</div>
				</div>
			</div><!-- end of modal -->
		</div><!-- Analytics container -->
		<div class="cp-md-overlay"></div> <!-- All Modal Overlay -->

		<div id="cp-dashboard-modal" class="cp-common-modal cp-edit-action-modal">
			<div class="cp-md-content cp-save-animate-container cp-animated">    
				<div class="campaign-action"> <!--Campaign Action-->
					<div class="cp-form-error">
						<label class="cp-error"><?php _e( 'Display Title cannot be empty.', 'convertpro' ); ?></label>
					</div><!-- Error Message -->
					<div class="cp-modal-header">
						<h3 class="cp-md-modal-title"><?php _e( 'Select / Create a Group', 'convertpro' ); ?></h3>
					</div>

										<div class="cp-modal-content">
						<div class="cp-flex-center cp-campaign-select-wrap">
							<div class="cp-campaign-selector">
								<a data-id="data-show" class="cp-select-campaign active"><?php _e( 'Use Existing', 'convertpro' ); ?></a>
								<a data-id="data-show" class="cp-create-campaign"><?php _e( 'Create New', 'convertpro' ); ?></a>
							</div>
						</div>
						<?php
							$campaign_exists = false;
							$categories      = get_terms(
								array(
									'taxonomy'   => CP_CAMPAIGN_TAXONOMY,
									'hide_empty' => false,
								)
							);

							if ( ! is_wp_error( $categories ) ) {

								if ( is_array( $categories ) && ! empty( $categories ) ) {

									$campaign_exists = true;
						?>

						<div id="cp-campaign-list" class="cp-dash-txt-field">
							<div class="cp-form-input">
								<select name='select-campaign' class="select-campaign">

									<?php
									foreach ( $categories as $category ) {
										echo "<option value='" . $category->term_id . "'>" . $category->name . ' </option>';
									}
									?>
								</select>
								<label class="cp-label-select"><?php echo __( 'Select Group', 'convertpro' ); ?></label> 
							</div>
						</div>
						<?php
								}
							}

							$hidden_class = '';
							if ( $campaign_exists ) {
								$hidden_class = 'cp-hidden';
							}
						?>
						<div class="cp-dash-txt-field cp-campaign-title-section <?php echo $hidden_class; ?>">
							<div class="cp-form-input">
								<input type="text" name="cp_campaign_name" id="cp_campaign_name" value=""/>
								<label class="cp-field-label"><?php _e( 'Group Name', 'convertpro' ); ?></label>
							</div>
						</div><!-- .cp-dash-txt-field -->
						<!-- Footer Buttons -->
						<div class="cp-modal-button cp-action-row">                            
							<button class="cp-cancel-campaign-btn cp-sm-btn cp-button-style"><?php _e( 'Cancel', 'convertpro' ); ?></button>
							<button class="cp-save-campaign-btn cp-sm-btn cp-button-style cp-btn-primary"><?php _e( 'Save', 'convertpro' ); ?></button>
						</div>
					</div>
				</div> <!--Campaign Action End-->
				<div class="rename-action"> <!--Rename Action-->
					<div class="cp-form-error">
							<label class="cp-error"></label>
						</div><!-- Error Message -->
					<div class="cp-modal-header">
						<h3 class="cp-md-modal-title"><?php _e( 'Rename Your Call-to-action', 'convertpro' ); ?></h3>
					</div>
					<div class="cp-modal-content">
						<div class="cp-save-style-content">
							<div class="cp-dash-txt-field">
								<div class="cp-form-input has-input">
									<input type="text" name="cp_style_title" id="cp_style_title" required="" value="">
									<label class="cp-field-label"><?php _e( 'Call-to-action Name', 'convertpro' ); ?></label>
								</div>
							</div><!-- .cp-dash-txt-field -->                            
						</div>
						<!-- Footer Buttons -->
						<div class="cp-modal-button cp-action-row">                            
							<button class="cp-cancel-rename-btn cp-sm-btn cp-button-style"><?php _e( 'Cancel', 'convertpro' ); ?></button>
							<button class="cp-save-rename-btn cp-sm-btn cp-button-style cp-btn-primary"><?php _e( 'Save', 'convertpro' ); ?></button>
						</div>
					</div>
				</div> <!--Rename Action End-->

				<div class="duplicate-action">
					<div class="cp-form-error">
							<label class="cp-error"></label>
						</div><!-- Error Message -->
					<div class="cp-modal-header">
						<h3 class="cp-md-modal-title"><?php _e( 'Duplicate Your Call-to-action', 'convertpro' ); ?></h3>
					</div>
					<div class="cp-modal-content">
						<div class="cp-save-style-content">
							<div class="cp-dash-txt-field">
								<div class="cp-form-input has-input">
									<input type="text" name="cp_dup_style_title" id="cp_dup_style_title" required="" value="">
									<label class="cp-field-label"><?php _e( 'Call-to-action Name', 'convertpro' ); ?></label>
								</div>
							</div><!-- .cp-dash-txt-field -->                            
						</div>
						<!-- Footer Buttons -->
						<div class="cp-modal-button cp-action-row">                            
							<button class="cp-cancel-rename-btn cp-sm-btn cp-button-style"><?php _e( 'Cancel', 'convertpro' ); ?></button>
							<button class="cp-duplicate-btn cp-sm-btn cp-button-style cp-btn-primary"><?php _e( 'Duplicate', 'convertpro' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- Modal Popup -->
	</div> <!-- End Wrapper -->
</div>
