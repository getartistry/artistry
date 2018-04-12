<?php
    $data = c27()->merge_options([
    	'listing' => false,
        ], $data);
?>

<!-- Modal - Report Listing -->
<div id="report-listing-modal" class="modal fade modal-27 " role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="sign-in-box element">
				<div class="title-style-1">
					<i class="material-icons">report_problem</i>
					<h5><?php _e( 'Report this Listing', 'my-listing' ) ?></h5>
				</div>
				<div class="report-wrapper" v-show="report.response.status != 'invalid_request' && report.response.status != 'success'">
					<div class="validation-message" v-show="report.response.status == 'validation_error' || report.response.status == 'unauthorized'"><em>{{ report.response.message }}</em></div>
					<div class="form-group">
						<textarea placeholder="<?php esc_attr_e( 'What\'s wrong with this listing?', 'my-listing' ) ?>" rows="7" v-model="report.content"></textarea>
					</div>

					<div class="form-group">
						<button type="submit" class="buttons button-2 full-width button-animated" name="login" value="Login" @click.prevent="reportListing" v-show="!report.loading">
							<?php _e( 'Submit Report', 'my-listing' ) ?> <i class="material-icons">keyboard_arrow_right</i>
						</button>

						<div v-show="report.loading">
							<br>
							<div class="loader-bg">
								<?php c27()->get_partial('spinner', [
									'color' => '#777',
									'classes' => 'center-vh',
									'size' => 28,
									'width' => 3,
									]); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="report-wrapper" v-show="report.response.status == 'invalid_request' || report.response.status == 'success'">
					<div class="submit-message"><em>{{ report.response.message }}</em></div>
				</div>
			</div>
		</div>
	</div>
</div>
