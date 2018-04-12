<?php
/**
 * Convert Pro Addon A/B Test Helper file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * ConvertPlug AB Test helper file.
 *
 * @since 1.0.0
 */
final class CPRO_ABTest_Helper {

	/**
	 * Get A/B Test row
	 *
	 * @since 1.0.0
	 * @param string $test A/B Test.
	 * @param string $styles_data Styles.
	 * @return string
	 */
	public static function cp_get_ab_test_row( $test, $styles_data ) {

		ob_start();
		$ab_test_inst = CP_V2_AB_Test::get_instance();
		$test_id      = $test->term_id;
		$ab_page_url  = CP_V2_Tab_Menu::get_page_url( 'ab-testing' );
		$ab_page_url  = $ab_page_url . '&view=info&test=' . $test_id;

		$sdate           = get_term_meta( $test_id, 'start_date', true );
		$edate           = get_term_meta( $test_id, 'end_date', true );
		$cp_parent_style = get_term_meta( $test_id, 'cp_parent_style', true );
		$cp_winner_check = get_term_meta( $test_id, 'cp_winner_check', true );

		$formatted_sdate = str_replace( '/', '-', $sdate );
		$formatted_edate = str_replace( '/', '-', $edate );

		$ab_styles = $ab_test_inst->get_styles_by_test_id( $test_id );

		$styles       = array();
		$style_titles = array();

		foreach ( $ab_styles as $style ) {
			$styles[]       = $style->ID;
			$style_titles[] = $style->post_title;
		}

		$test_data = array(
			'name'         => $test->name,
			'start_date'   => $sdate,
			'end_date'     => $edate,
			'styles'       => $styles_data,
			'sel_styles'   => $styles,
			'parent_style' => $cp_parent_style,
			'winner_style' => $cp_winner_check,
		);

		$ab_sepration  = '';
		$ab_sepration .= '<span>' . implode( ', ', $style_titles ) . '</span>';

		$test_data = json_encode( $test_data );

		$styles       = implode( ' VS ', $style_titles );
		$style_status = get_term_meta( $test_id, 'status', true );
	?> 
	   
		<div class="cp-row cp-popup-row cp-ab-test-row" data-test-id="<?php echo $test_id; ?>" data-props="<?php echo htmlspecialchars( $test_data ); ?>" data-test-status="<?php echo $style_status; ?>">
			<div class="cp-acc-4 cp-column-title">
				<div class="cp-style-title">
					<a href="javascript:void(0);"><?php echo $test->name; ?></a>
					<input type="hidden" name="cp-delete-test-nonce" id="cp-delete-test-nonce" value="<?php echo wp_create_nonce( 'cp-delete-test-nonce' ); ?>" />
				</div>
			</div>
			<div class="cp-col-8">
				<div class="cp-accordion-block">
					<div class="cp-ab-test-groups-block">
						<div class="cp-style-compare"><?php echo $ab_sepration; ?></div>
					</div>
					<div class="cp-ab-test-groups-block cp-display-none">
						<div class="cp-interval-date">
							<div><?php echo date( 'd M Y', strtotime( $formatted_sdate ) ); ?>
							</div> 
							<div>To</div> 
							<div>
							<?php echo date( 'd M Y', strtotime( $formatted_edate ) ); ?>
							</div>

						</div>
					</div>
					<div class="cp-ab-test-groups-block">
					<div class="cp-view-analytics-icon">
						<span class="has-tip" data-position="bottom" title="<?php echo date( 'd M Y', strtotime( $formatted_sdate ) ) . ' To ' . date( 'd M Y', strtotime( $formatted_edate ) ); ?>"><i class="dashicons dashicons-clock"></i></span>
						<?php
						do_action( 'cpro_ab_test_actions', $test->slug );
						?>
					</div>
					</div>

					<div class="cp-ab-test-groups-block cp-lead-groups-block cp-style-status">
						<div class="cp-switch-wrapper">

							<?php
							$btn_id     = uniqid();
							$input_name = 'ab_test_status_' . $test_id;

							$checked = ( '1' == $style_status ) ? 'checked="checked"' : '';
							$uniq    = uniqid();
							if ( 2 == $style_status ) {
								_e( '<label>Completed</label>', 'convertpro-addon' );
							} else {
							?>
							<input type="text" id="cp_<?php echo $input_name; ?>" class="form-control cp-input cp-switch-input" name="<?php echo $input_name; ?>" data-test="<?php echo $test_id; ?>" value="<?php echo $style_status; ?>" />

							<input type="checkbox" <?php echo $checked; ?> id="cp_<?php echo $input_name; ?>_btn_<?php echo $uniq; ?>" class="ios-toggle cp-input cp-switch-input switch-checkbox cp-switch" value="<?php echo $style_status; ?>"   >

							<label class="cp-switch-btn checkbox-label" data-on="<?php _e( 'Live', 'convertpro-addon' ); ?>"  data-off="<?php _e( 'Pause', 'convertpro-addon' ); ?>" data-id="cp_<?php echo $input_name; ?>" for="cp_<?php echo $input_name; ?>_btn_<?php echo $uniq; ?>">
							</label>
							<?php
							}
							?>
						</div>
					</div>
					<div class="cp-ab-edit-settings" data-completed="<?php echo $style_status; ?>" data-ab-test="false"></div>
				</div>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}

	/**
	 * Get A/B Test row header
	 *
	 * @since 1.0.0
	 * @return string html.
	 */
	public static function cp_get_ab_test_row_header() {

		ob_start();
	?>
		<div class="cp-acc-4">
			<label><?php _e( 'Test Name', 'convertpro-addon' ); ?></label>
		</div>
		<div class="cp-col-8">
			<div class="cp-accordion-block">
				<div class="cp-ab-test-groups-block">
					<label><?php _e( 'Under Test', 'convertpro-addon' ); ?></label>
				</div>
				<div class="cp-ab-test-groups-block cp-display-none">
					<label><?php _e( 'Interval', 'convertpro-addon' ); ?></label>
				</div>
				<div class="cp-ab-test-groups-block">
					<label><?php _e( 'Insight', 'convertpro-addon' ); ?></label>
				</div>
				<div class="cp-ab-test-groups-block cp-lead-groups-block cp-style-status">
					<label><?php _e( 'Status', 'convertpro-addon' ); ?></label>
				</div>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}
}
