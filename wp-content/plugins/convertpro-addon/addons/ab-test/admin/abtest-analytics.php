<?php
/**
 * Convert Pro Addon A/B Test Google Analytics file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
?>

<div id="cp-ga-abtest-modal" class="cp-dashboard-modal cp-common-modal cp-ga-style-analytics">
	<div class="cp-md-content cp-save-animate-container cp-animated">
		<div class="cp-close-wrap"><i class="dashicons dashicons-no-alt"></i></div>
		<div class="cp-modal-header">   
			<h3 class="cp-md-modal-title"><?php _e( 'Google Analytics', 'convertpro-addon' ); ?></h3>
		</div>
		<div class="cp-ga-modal-content">
			<div class="cp_ga_chart_wrap">
				<div class="edit-screen-overlay" style="overflow: hidden;background: #FCFCFC;width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999;">
					<div class="cp-absolute-loader" style="visibility: visible;overflow: hidden;">
						<div class="cp-loader">
							<h2 class="cp-loader-text"><?php _e( 'Loading...', 'convertpro-addon' ); ?></h2>
							<div class="cp-loader-wrap">
								<div class="cp-loader-bar">
									<div class="cp-loader-shadow"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="cp_ga_chart_div" ></div>
			</div>
		</div><!-- End Wrapper -->
	</div>
</div>
