<?php
/**
 * Insight actions file.
 *
 * @package ConvertPro
 */

add_action( 'wp_ajax_cp_update_campaign', 'cp_update_campaign' );
add_action( 'wp_ajax_cp_delete_popup', 'handle_cp_delete_popup_action' );
add_action( 'wp_ajax_cp_duplicate_popup', 'handle_cp_popup_duplicate_action' );
add_action( 'admin_post_cp_delete_campaign', 'handle_cp_delete_campaign_action' );
add_action( 'wp_ajax_cp_rename_popup', 'cp_rename_popup' );
add_action( 'wp_ajax_cp_rename_campaign', 'cp_rename_campaign' );
add_action( 'cp_get_insight_row_value', 'cp_render_insight_options', 10 );
add_action( 'cp_get_type_row_value', 'cp_render_style_type', 10 );
add_action( 'cp_get_style_status_row_value', 'cp_render_style_status', 10 );

/**
 * Display style status
 *
 * @param int $style parameter.
 * @since 0.0.1
 */
function cp_render_style_status( $style ) {

	$has_active_ab_test['status'] = false;

	if ( class_exists( 'CP_V2_AB_Test' ) ) {
		$ab_test_inst       = CP_V2_AB_Test::get_instance();
		$has_active_ab_test = $ab_test_inst->has_active_ab_test( $style->ID );
	}

	if ( false == $has_active_ab_test['status'] ) {  ?>
		<div class="cp-switch-wrapper">

			<?php

			$btn_id       = uniqid();
			$style_status = get_post_meta( $style->ID, 'live', true );
			$input_name   = 'style_status_' . $style->ID;
			$checked      = '1' == $style_status ? 'checked="checked"' : '';
			$uniq         = uniqid();

			?>

			<input type="text" id="cp_<?php echo $input_name; ?>" class="form-control cp-input cp-switch-input" name="<?php echo $input_name; ?>" data-style="<?php echo $style->ID; ?>" value="<?php echo $style_status; ?>" />

			<input type="checkbox" <?php echo $checked; ?> id="cp_<?php echo $input_name; ?>_btn_<?php echo $uniq; ?>" class="ios-toggle cp-input cp-switch-input switch-checkbox cp-switch" value="<?php echo $style_status; ?>"   >

			<label class="cp-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="cp_<?php echo $input_name; ?>" for="cp_<?php echo $input_name; ?>_btn_<?php echo $uniq; ?>">
			</label>

		</div>
	<?php } else { ?>
		<?php
		if ( isset( $has_active_ab_test['test_name'] ) ) {
			$test_name = substr( $has_active_ab_test['test_name'], 0, 13 ) . ( ( strlen( $has_active_ab_test['test_name'] ) > 13 ) ? '...' : '' );
			/* translators: %s Test name */
			$test_name = sprintf( __( 'A/B - %s', 'convertpro' ), $test_name );
		} else {
			$test_name = __( 'A/B Test is running.', 'convertpro' );
		}
		?>
		<a href="<?php echo admin_url( 'admin.php?page=' . CP_PRO_SLUG . '-ab-test' ); ?>" class="cp-prog-label"><?php echo $test_name; ?></a>
		<?php
}
}

/**
 * Renders insight actions for design
 *
 * @param int $style Style ID.
 * @since 0.0.1
 */
function cp_render_insight_options( $style ) {

	$settings            = array();
	$configure_meta_data = get_post_meta( $style->ID, 'configure', true );

	$data_string   = cp_get_style_info( $configure_meta_data, $style->ID, $style->post_title );
	$data_settings = $data_string;
	?>
	<div class="cp-view-analytics-icon">

		<span class="has-tip" data-position="bottom" title="<?php _e( 'Info', 'convertpro' ); ?>">
			<a href="javascript:void(0);" data-settings="<?php echo htmlspecialchars( $data_settings ); ?>" class="cp-info-popup"><i class="dashicons dashicons-info"></i></a>
		</span>

		<?php do_action( 'cp_after_insight_actions', $style ); ?>

	</div>
	<?php
}

/**
 * Renders style type
 *
 * @param int $style Style ID.
 * @since 0.0.1
 */
function cp_render_style_type( $style ) {

	$title             = '';
	$cp_module_type    = get_post_meta( $style->ID, 'cp_module_type', true );
	$module_type       = explode( '_', $cp_module_type );
	$module_type       = array_map( 'ucfirst', $module_type );
	$module_class_name = 'CP_' . implode( '_', $module_type );

	if ( class_exists( $module_class_name ) ) {
		$module_settings = $module_class_name::$settings;
		$title           = $module_settings['title'];
	}
	?>

	<span class="cp-module-type-container"><?php echo $title; ?></span>
	<?php
}

/**
 * Function Name: cp_update_campaign.
 * Function Description: cp_update_campaign.
 */
function cp_update_campaign() {

	if ( ! current_user_can( 'edit_cp_popup_terms' ) ) {
		$data = array(
			'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}

	$post_id       = esc_attr( $_POST['post_id'] );
	$campaign_id   = esc_attr( $_POST['campaign_id'] );
	$campaign_name = esc_attr( $_POST['campaign_name'] );

	if ( 'false' != $campaign_id ) {
		$term = term_exists( (int) $campaign_id, CP_CAMPAIGN_TAXONOMY );
	} else {
		$term = wp_insert_term(
			$campaign_name,
			CP_CAMPAIGN_TAXONOMY
		);
	}

	if ( ! is_wp_error( $term ) ) {

		$post_id = (int) $post_id;
		$cat_id  = (int) $term['term_id'];

		$term_result = wp_set_object_terms( $post_id, $cat_id, CP_CAMPAIGN_TAXONOMY );

		if ( ! is_wp_error( $term_result ) ) {

			$data = array(
				'message' => 'Success',
			);
			wp_send_json_success( $data );

		} else {

			$data = array(
				'message' => 'Error',
			);
			wp_send_json_error( $data );
		}
	} else {
		$data = array(
			'message' => __( 'Campaign already exist', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}
}

/**
 * Function Name: handle_cp_delete_campaign_action.
 * Function Description: handle_cp_delete_campaign_action.
 */
function handle_cp_delete_campaign_action() {

	$campaign_id = esc_attr( $_GET['campaign_id'] );
	check_admin_referer( 'delete-campaign-' . $campaign_id );

	if ( ! current_user_can( 'manage_cp_popup_terms' ) ) {
		return new WP_Error( 'broke', __( 'You do not have permissions to perform this action', 'convertpro' ) );
	}

	$term = term_exists( 'your-designs', CP_CAMPAIGN_TAXONOMY );

	if ( 0 === $term || null === $term ) {
		$term = wp_insert_term(
			'Your Call-to-actions',
			CP_CAMPAIGN_TAXONOMY
		);
	}

	if ( ! is_wp_error( $term ) ) {
		$cp_popups_inst = CP_V2_Popups::get_instance();
		$popups         = $cp_popups_inst->get_popups_by_campaign_id( $campaign_id );

		if ( is_array( $popups ) && count( $popups ) > 0 ) {

			$cat_ids = array( $term['term_id'] );
			$cat_ids = array_map( 'intval', $cat_ids );

			foreach ( $popups as $popup ) {

				$term_taxonomy_ids = wp_set_object_terms( $popup->ID, $cat_ids, CP_CAMPAIGN_TAXONOMY );
			}
		}

		if ( intval( $term['term_id'] ) !== intval( $campaign_id ) ) {

			$result = wp_delete_term( (int) $campaign_id, CP_CAMPAIGN_TAXONOMY );
		}

		if ( ! is_wp_error( $result ) ) {

			$query = array(
				'message' => 'success',
				'action'  => 'delete-campaign',
			);

		} else {
			$message = $result->get_error_message();

			$query = array(
				'message' => 'error',
				'action'  => 'delete-campaign',
			);
		}
	} else {
		$message = $result->get_error_message();

		$query = array(
			'message' => 'error',
			'action'  => 'delete-campaign',
		);
	}

	$sendback = wp_get_referer();
	$sendback = remove_query_arg( array( 'action', 'message' ), $sendback );
	$sendback = add_query_arg( $query, $sendback );

	wp_redirect( $sendback );
	exit();
}

/**
 * Function Name: handle_cp_popup_duplicate_action.
 * Function Description: handle_cp_popup_duplicate_action.
 */
function handle_cp_popup_duplicate_action() {

	if ( ! current_user_can( 'edit_cp_popup' ) ) {
		$data = array(
			'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}

	$popup_id = esc_attr( $_POST['popup_id'] );
	$title    = esc_attr( $_POST['popup_name'] );

	$result = cp_duplicate_popup( $popup_id, $title );

	if ( is_wp_error( $result ) ) {
		$message = $result->get_error_message();

		$query = array(
			'message' => 'error',
			'action'  => 'duplicate',
			'html'    => '',
		);

	} else {

		if ( 'error' == $result['message'] ) {

			$query = array(
				'message' => 'error',
				'action'  => 'duplicate',
				'html'    => '',
			);

		} else {

			$popup_id    = isset( $result['popup_id'] ) ? $result['popup_id'] : '';
			$module_type = get_post_meta( $popup_id, 'cp_module_type', true );
			$module_type = str_replace( '_', ' ', $module_type );

			$query = array(
				'message'     => 'success',
				'style_id'    => $popup_id,
				'action'      => 'duplicate',
				'module_type' => ucwords( $module_type ),
			);

			$style = get_post( $popup_id );

			$html = cp_get_insights_row( $style );

			$query['html'] = $html;
		}
	}

	echo json_encode( $query );
	wp_die();
}

/**
 * Function Name: handle_cp_delete_popup_action.
 * Function Description: handle cp delete popup action.
 */
function handle_cp_delete_popup_action() {

	$popup_id = esc_attr( $_POST['popup_id'] );

	if ( current_user_can( 'delete_cp_popup', $popup_id ) ) {
		if ( ! wp_delete_post( $popup_id ) ) {

			$query = array(
				'message' => 'error',
				'action'  => 'delete',
			);

		} else {
			$query = array(
				'message' => 'success',
				'action'  => 'delete',
			);
		}
	} else {

		$query = array(
			'message' => 'error',
			'action'  => 'delete',
		);

	}

	echo json_encode( $query );
	wp_die();
}

/**
 * Function Name: cp_rename_popup.
 * Function Description: cp_rename_popup.
 */
function cp_rename_popup() {

	if ( ! current_user_can( 'edit_cp_popup' ) ) {
		$data = array(
			'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}

	$popup_id   = isset( $_POST['popup_id'] ) ? esc_attr( $_POST['popup_id'] ) : '';
	$popup_name = isset( $_POST['popup_name'] ) ? esc_attr( $_POST['popup_name'] ) : '';

	if ( '' !== $popup_id ) {
		// Update post.
		$popup = array(
			'ID'         => $popup_id,
			'post_title' => $popup_name,
		);

		// Update the post into the database.
		$result = wp_update_post( $popup );

		if ( ! is_wp_error( $result ) ) {
			$data = array(
				'success'   => true,
				'new_title' => $popup_name,
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error();
		}
	}
}

/**
 * Function Name: cp_rename_campaign.
 * Function Description: cp rename campaign.
 */
function cp_rename_campaign() {

	if ( ! current_user_can( 'edit_cp_popup_terms' ) ) {
		$data = array(
			'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}

	$campaign_id   = isset( $_POST['campaign_id'] ) ? esc_attr( $_POST['campaign_id'] ) : '';
	$campaign_name = isset( $_POST['campaign_name'] ) ? esc_attr( $_POST['campaign_name'] ) : '';

	if ( '' !== $campaign_id ) {

		// Update post.
		$campaign = array(
			'name' => $campaign_name,
		);

		// Update the post into the database.
		$result = wp_update_term( $campaign_id, CP_CAMPAIGN_TAXONOMY, $campaign );

		if ( ! is_wp_error( $result ) ) {
			$data = array(
				'success'   => true,
				'new_title' => $campaign_name,
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error();
		}
	}
}

/**
 * Function Name: cp_get_style_info.
 * Function Description: cp get style info.
 *
 * @param string $settings string parameter.
 * @param string $style_id string parameter.
 * @param string $title string parameter.
 */
function cp_get_style_info( $settings, $style_id, $title ) {

	ob_start();
	$cp_module_type = get_post_meta( $style_id, 'cp_module_type', true );
	$cp_module_type = ( ! empty( $cp_module_type ) && '' != $cp_module_type ) ? $cp_module_type : '';

	?>
	<div class="cp-modal-header">
		<h3 class="cp-md-modal-title"><?php _e( 'Behavior Quick View - ', 'convertpro' ); ?><span><?php echo $title; ?></span></h3>
		<span class="cp-info-id-wrap">
		<?php
		/* translators: %s percentage */
		$style_post = get_post( $style_id );
		$slug       = $style_post->post_name;
		/* translators: %1$s style ID */
		echo sprintf( __( '<strong>ID</strong>: %1$s | <strong>Slug</strong>: %2$s', 'convertpro' ), $style_id, $slug );
		?>
		</span>
	</div>
	<table>
		<?php
		if ( 'before_after' != $cp_module_type && 'inline' != $cp_module_type & 'widget' != $cp_module_type ) {
		?>
		<tr>
			<td><?php _e( 'When should this call-to-action appear?', 'convertpro' ); ?></td>
			<td class="cpro-rules-data">
			<?php

			$rulesets = isset( $settings['rulesets'] ) ? json_decode( $settings['rulesets'] ) : array();

			$user_inactivity = esc_attr( get_option( 'cp_user_inactivity' ) );
			$display_rules   = array();

			if ( ! empty( $rulesets ) ) {
				foreach ( $rulesets as $ruleset ) {

					$confi_rules = array();

					if ( ! $user_inactivity ) {
						$user_inactivity = '60';
					}

					if ( isset( $ruleset->modal_exit_intent ) && '1' == $ruleset->modal_exit_intent ) {
						$confi_rules[] = __( 'Exit Intent', 'convertpro' );
					}

					if ( isset( $ruleset->autoload_on_duration ) && '1' == $ruleset->autoload_on_duration ) {
						/* translators: %s seconds */
						$confi_rules[] = sprintf( __( 'After %s seconds', 'convertpro' ), $ruleset->load_on_duration );
					}

					if ( isset( $ruleset->autoload_on_scroll ) && '1' == $ruleset->autoload_on_scroll ) {
						/* translators: %s percentage */
						$confi_rules[] = sprintf( __( 'After user scrolls the %s%%', 'convertpro' ), $ruleset->load_after_scroll );
					}

					if ( isset( $ruleset->inactivity ) && '1' == $ruleset->inactivity ) {
						/* translators: %s seconds */
						$confi_rules[] = sprintf( __( 'Inactivitiy for %s Seconds', 'convertpro' ), $user_inactivity );
					}

					if ( isset( $ruleset->enable_after_post ) && '1' == $ruleset->enable_after_post ) {
						$confi_rules[] = __( 'After user reaches the end of a blog post.', 'convertpro' );
					}

					if ( isset( $ruleset->enable_display_inline ) && '1' == $ruleset->enable_display_inline ) {
						$confi_rules[] = __( 'Display Inline', 'convertpro' );
					}

					if ( isset( $ruleset->enable_custom_scroll ) && '1' == $ruleset->enable_custom_scroll ) {
						/* translators: %s enable scroll class option value */
						$confi_rules[] = sprintf( __( 'After user reaches the %s on page.', 'convertpro' ), $ruleset->enable_scroll_class );
					}

					$confi_rules_string = implode( ' and ', $confi_rules );

					echo '<li class="cpro-bb-cls">' . $confi_rules_string . '</li>';

					$referal_on = ( isset( $ruleset->enable_referrer ) && $ruleset->enable_referrer ) ? 'Enable' : 'Disabled ';

					if ( isset( $ruleset->enable_referrer ) && $ruleset->enable_referrer ) {
						$referal_display_key = 'Display Only To';
						$referal_display_val = $ruleset->display_to;
					} else {
						$referal_display_key = 'Hide Only To';
						$referal_display_val = isset( $ruleset->hide_from ) ? $ruleset->hide_from : '';
					}

					$visible_to = __( 'Visible to all', 'convertpro' );

					if ( isset( $ruleset->enable_visitors ) && '1' == $ruleset->enable_visitors ) {
						$visible_to = $ruleset->visitor_type;
					}

					$display_rules[] = array(
						'Referrer Detection' => $referal_on,
						$referal_display_key => $referal_display_val,
						'Visitor Type'       => str_replace( '-', ' ', $visible_to ),
					);
				}
			}
			?>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Where is this call-to-action enabled/disabled?', 'convertpro' ); ?>
			</td>
			<td>
				<?php

				$rules   = ( isset( $settings['target_rule_display'] ) ) ? $settings['target_rule_display'] : '';
				$display = cpro_get_design_visibility( $rules );

				$rules   = ( isset( $settings['target_rule_exclude'] ) ) ? $settings['target_rule_exclude'] : '';
				$exclude = cpro_get_design_visibility( $rules );

				$enabled = implode( ', ', $display );
				if ( '' != $enabled ) {
					echo '<li>' . __( 'Enabled on', 'convertpro' ) . ' - ' . $enabled . '</li>';
				}

				$disable = implode( ', ', $exclude );
				if ( '' != $disable ) {
					echo '<li>' . __( 'Disabled on', 'convertpro' ) . ' - ' . $disable . '</li>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td><?php _e( 'Who should see this call-to-action?', 'convertpro' ); ?></td>
			<td class="cpro-rules-data">
				<?php
				if ( isset( $settings['show_for_logged_in'] ) && '1' == $settings['show_for_logged_in'] ) {
				?>
					<li class="cpro-bb-cls"><?php _e( 'Everyone including logged in users.', 'convertpro' ); ?></li>
				<?php
				}

				if ( isset( $settings['display_on_first_load'] ) && '1' == $settings['display_on_first_load'] ) {
				?>
					<li class="cpro-bb-cls"><?php _e( 'Everyone including first time visitors.', 'convertpro' ); ?></li>
				<?php
				}

				if ( isset( $settings['hide_on_device'] ) && $settings['hide_on_device'] ) {
					$hide_devices = str_replace( '|', ', ', $settings['hide_on_device'] );
					?>
					<li class="cpro-bb-cls"><?php _e( 'Hide On Devices - ', 'convertpro' ); ?>
					<?php echo $hide_devices; ?></li>
				<?php
				}

				foreach ( $display_rules as $key => $rules ) {
					$incrementor = 0;
					$count       = count( $rules );
					$class       = '';

					echo '<h4>' . __( 'Ruleset', 'convertpro' ) . '&nbsp;' . ( $key + 1 ) . '</h4>';

					foreach ( $rules as $key => $value ) {

						if ( $incrementor == $count - 1 ) {
							$class = 'cpro-bb-cls';
						}

						if ( '' !== $value ) {
							echo '<li class="' . $class . '">' . $key . ' - ' . ucwords( $value ) . '</li>';
						}

						$incrementor++;
					}
				}
			?>
			</td>
		</tr>
		<tr>
			<td><?php _e( 'How frequently do you wish to show this call-to-action?', 'convertpro' ); ?></td>
			<td>
				<?php if ( isset( $settings['cookies_enabled'] ) && '1' == $settings['cookies_enabled'] ) { ?>				
					<li>
					<?php
					echo sprintf(
						/* translators: %1$s days */
						__(
							'Hide for - <br> %1$s days after conversion <br>
	                    %2$s days after closing', 'convertpro'
						), $settings['conversion_cookie'], $settings['closed_cookie']
					);
					?>
					</li>
				<?php } else { ?>
					<li><?php _e( 'It will appear every time a visitor arrives on your website.', 'convertpro' ); ?></li>
				<?php
}
				?>
			</td>
		</tr>
		<?php
		} elseif ( 'inline' == $cp_module_type ) {
		?>
		<tr>
			<td><?php _e( 'This is an inline form & will be displayed on post/pages you have added the short-code.', 'convertpro' ); ?></td>
		</tr>
		<?php
		} elseif ( 'before_after' == $cp_module_type ) {
		?>
		<tr>
			<td><?php _e( 'What is the call-to-action inline position?', 'convertpro' ); ?></td>
			<td>
			<?php
			$inline_position = __( 'Both Before and After the post.', 'convertpro' );
			if ( isset( $settings['inline_position'] ) ) {
				if ( 'before_post' == $settings['inline_position'] ) {
					$inline_position = __( 'Before the posts.', 'convertpro' );
				} elseif ( 'after_post' == $settings['inline_position'] ) {
					$inline_position = __( 'After the posts.', 'convertpro' );
				}
			?>
				<li><?php echo $inline_position; ?></li>
			<?php
			}
			?>
			</td>
		</tr>
		<tr>
			<td><?php _e( 'Where is this call-to-action enabled/disabled?', 'convertpro' ); ?></td>
			<td>
				<?php
				$rules   = ( isset( $settings['target_rule_display'] ) ) ? $settings['target_rule_display'] : '';
				$display = cpro_get_design_visibility( $rules );

				$rules   = ( isset( $settings['target_rule_exclude'] ) ) ? $settings['target_rule_exclude'] : '';
				$exclude = cpro_get_design_visibility( $rules );

				$enabled = implode( ', ', $display );
				if ( '' != $enabled ) {
					echo '<li>' . __( 'Enabled on', 'convertpro' ) . ' - ' . $enabled . '</li>';
				}

				$disable = implode( ', ', $exclude );
				if ( '' != $disable ) {
					echo '<li>' . __( 'Disabled on', 'convertpro' ) . ' - ' . $disable . '</li>';
				}
				?>
							</td>
		</tr>
		<tr>
			<td><?php _e( 'Who should see this call-to-action?', 'convertpro' ); ?></td>
			<td>
				<?php
				if ( isset( $settings['show_for_logged_in'] ) && '1' == $settings['show_for_logged_in'] ) {
				?>
					<li><?php _e( 'Everyone including logged in users.', 'convertpro' ); ?></li>
				<?php
				}
				if ( isset( $settings['display_on_first_load'] ) && '1' == $settings['display_on_first_load'] ) {
				?>
					<li><?php _e( 'Everyone including first time visitors.', 'convertpro' ); ?></li>
				<?php
				}
				if ( isset( $settings['hide_on_device'] ) && $settings['hide_on_device'] ) {
					$hide_devices = str_replace( '|', ', ', $settings['hide_on_device'] );
					?>
					<li><?php _e( 'Hide On Devices - ', 'convertpro' ); ?>
					<?php echo $hide_devices; ?></li>
				<?php
				}
			?>
			</td>
		</tr>
		<?php
		} elseif ( 'widget' == $cp_module_type ) {
		?>
		<tr>
			<td><?php _e( 'This is a widget form & will be displayed on post/pages you have added the widget.', 'convertpro' ); ?></td>
		</tr>
		<?php
		}
		?>
	</table>
	<?php
	$html_string = ob_get_clean();

	return $html_string;
}

/**
 * Function Name: cpro_get_design_visibility.
 * Function Description: Get design visibility.
 *
 * @param string $rules string parameter.
 */
function cpro_get_design_visibility( $rules ) {

	$rules = json_decode( $rules );
	$arr   = array();

	if ( is_array( $rules ) && ! empty( $rules ) ) {

		foreach ( $rules as $rule ) {
			if ( ! isset( $rule->type ) || ( isset( $rule->type ) && '' == $rule->type ) ) {
				break;
			}

			if ( strrpos( $rule->type, 'all' ) !== false ) {
				$rule_case = 'all';
			} else {
				$rule_case = $rule->type;
			}

			switch ( $rule_case ) {
				case 'basic-global':
					$show_popup = __( 'Entire Website', 'convertpro' );
					break;

				case 'basic-singulars':
					$show_popup = __( 'All Singulars', 'convertpro' );
					break;

				case 'basic-archives':
					$show_popup = __( 'All Archives', 'convertpro' );
					break;

				case 'special-404':
					$show_popup = __( '404 Page', 'convertpro' );
					break;

				case 'special-search':
					$show_popup = __( 'Search Page', 'convertpro' );
					break;

				case 'special-blog':
					$show_popup = __( 'Blog / Posts Page', 'convertpro' );
					break;

				case 'special-front':
					$show_popup = __( 'Front Page', 'convertpro' );
					break;

				case 'special-date':
					$show_popup = __( 'Date Archive', 'convertpro' );
					break;

				case 'special-author':
					$show_popup = __( 'Author Archive', 'convertpro' );
					break;

				case 'all':
					$show_popup = $rule->type;
					$rule_data  = explode( '|', $rule->type );

					$post_type     = isset( $rule_data[0] ) ? $rule_data[0] : false;
					$archieve_type = isset( $rule_data[2] ) ? $rule_data[2] : false;
					$taxonomy      = isset( $rule_data[3] ) ? $rule_data[3] : false;

					if ( false === $taxonomy ) {

						$obj = get_post_type_object( $post_type );
						$arc = ( false === $archieve_type ) ? '' : __( 'Archive', 'convertpro' );
						if ( isset( $obj->labels->name ) ) {
							/* translators: %s enable scroll class option value */
							$show_popup = sprintf( __( 'All %1$s %2$s', 'convertpro' ), ucwords( $obj->labels->name ), $arc );
						}
					} else {

						if ( false !== $taxonomy ) {

							$obj = get_taxonomy( $taxonomy );
							if ( isset( $obj->labels->name ) ) {
								/* translators: %s enable scroll class option value */
								$show_popup = sprintf( __( 'All %s Archive', 'convertpro' ), ucwords( $obj->labels->name ) );
							}
						}
					}
					break;

				case 'specifics':
					if ( isset( $rule->specific ) && is_array( $rule->specific ) ) {

						foreach ( $rule->specific as $specific_page ) {

							$specific_data      = explode( '-', $specific_page );
							$specific_post_type = isset( $specific_data[0] ) ? $specific_data[0] : false;
							$specific_post_id   = isset( $specific_data[1] ) ? $specific_data[1] : false;

							if ( 'post' == $specific_post_type ) {
								$names[] = get_the_title( $specific_post_id );
							} elseif ( 'tax' == $specific_post_type ) {
								$term    = get_term( $specific_post_id );
								$names[] = $term->name;
							}
						}
						$show_popup = implode( ', ', $names );
					}
					break;

				default:
					break;
			}

			if ( 'all' != $rule_case ) {
				$arr[ $rule_case ] = $show_popup;
			} else {
				$arr[] = $show_popup;
			}
		}
	}
	return $arr;
}
