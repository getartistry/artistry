<?php
/**
 * Admin helper functions.
 *
 * @package ConvertPro
 */

/**
 * Function Name: cp_generate_map_inline_style.
 * Function Description: cp generate map inline style.
 *
 * @param string $style string parameter.
 * @param string $value string parameter.
 * @param string $unit string parameter.
 */
function cp_generate_map_inline_style( $style, $value, $unit = '' ) {

	if ( '' != $unit ) {

		$value .= $unit;
	}
	$grad_prop       = '';
	$btn_gradient    = '';
	$gd_angle        = '';
	$style_parameter = $style['parameter'];
	if ( 'border-width' == $style_parameter || 'border-radius' == $style_parameter || 'padding' == $style_parameter ) {
		$style = cp_generate_multi_input_result( $style_parameter, $value );
	} elseif ( 'box-shadow' == $style_parameter || 'panel-box-shadow' == $style_parameter ) {
		$style = cp_generate_box_shadow( $value );
	} elseif ( 'btn-gradient-angle' == $style_parameter ) {
		$style     = '';
		$grad_prop = explode( '|', $value );

		if ( isset( $grad_prop ) ) {
			$lighter_color    = $grad_prop[0];
			$darker_color     = $grad_prop[1];
			$lighter_location = $grad_prop[2];
			$darker_location  = $grad_prop[3];
			$gd_type          = $grad_prop[4];
			$gd_rad_dir       = $grad_prop[5];

			if ( isset( $grad_prop[6] ) ) {
				$gd_angle = $grad_prop[6];
			}
		}

		if ( 'lineargradient' == $gd_type ) {
			$style = cp_apply_gradient_bg( $lighter_color, $lighter_location, $darker_color, $darker_location, $gd_angle );
		} elseif ( 'radialgradient' == $gd_type ) {
			$style = cp_apply_gradient_bg_rad( $gd_rad_dir, $lighter_color, $lighter_location, $darker_color, $darker_location );
		}
	} else {
			$style = $style_parameter . ':' . $value . ';';
	}
	return $style;

}

/**
 * Function Name: cp_render_presets.
 * Function Description: cp render presets.
 *
 * @param string $type string parameter.
 * @param string $title string parameter.
 * @param string $presets string parameter.
 * @param bool   $tags bool parameter.
 * @param int    $resize int parameter.
 */
function cp_render_presets( $type, $title, $presets, $tags = false, $resize = 1 ) {

	ob_start();
	$template = cp_get_field_template( $type );

	foreach ( $presets as $key => $preset ) {

		$btn_gd          = '';
		$preset_name     = isset( $preset['title']['value'] ) ? $preset['title']['value'] : '';
		$preset_template = $template;
		$field_style     = '';
		$hover_style     = '';
		$prog_btn        = '';
		$load_icon_1     = '';
		$load_icon_2     = '';

		foreach ( $preset as $prop_key => $prop ) {

			if ( isset( $prop['map_style'] ) && '' != $prop['map_style'] ) {

				$map_style = $prop['map_style'];
				$value     = $prop['value'];
				$unit      = isset( $prop['unit'] ) ? $prop['unit'] : '';
				$class     = 'cp-preset-field cp-element-container draggable';

				if ( isset( $prop['onhover'] ) && true == $prop['onhover'] ) {
					$hover_style .= cp_generate_map_inline_style( $map_style, $value, $unit );
				} else {
					switch ( $map_style['parameter'] ) {
						case 'icon-position':
							if ( 'left' == $value ) {
								$preset_template = str_replace( '{{left-icon}}', '<i class="{{btn-icon}}" {{left-margin}}></i>', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '', $preset_template );
							} elseif ( 'right' == $value ) {
								$preset_template = str_replace( '{{left-icon}}', '', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '<i class="{{btn-icon}}" {{right-margin}}></i>', $preset_template );
							} else {
								$preset_template = str_replace( '{{left-icon}}', '', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '', $preset_template );
							}
							break;

						case 'loader-position':
							if ( 'left' == $value ) {
								$preset_template = str_replace( '{{left-icon}}', '<i class="cp-icon-loading" {{left-margin}}></i><div class="cp-loader-container"><div class="cp-btn-loader"></div></div>', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '', $preset_template );
							} elseif ( 'right' == $value ) {
								$preset_template = str_replace( '{{left-icon}}', '', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '<i class="cp-icon-loading" {{right-margin}}></i><div class="cp-loader-container"><div class="cp-btn-loader"></div></div>', $preset_template );
							} else {
								$preset_template = str_replace( '{{left-icon}}', '', $preset_template );
								$preset_template = str_replace( '{{right-icon}}', '', $preset_template );
							}
							break;

						case 'icon':
							$preset_template = str_replace( '{{btn-icon}}', $value, $preset_template );
							break;

						case 'icon-space':
							$preset_template = str_replace( '{{left-margin}}', 'style="position:relative; right:' . $value . 'px"', $preset_template );
							$preset_template = str_replace( '{{right-margin}}', 'style="position:relative; left:' . $value . 'px"', $preset_template );
							break;

						case 'inner-html':
							$preset_template = str_replace( $map_style['replace'], $value, $preset_template );
							break;

						case 'btn-gradient-bg1':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-bg2':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-loc1':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-loc2':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-type':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-rad-dir':
							$btn_gd .= $value . '|';
							break;

						case 'btn-gradient-angle':
							$btn_gd      .= $value;
							$field_style .= cp_generate_map_inline_style( $map_style, $btn_gd, $unit );
							break;

						case 'inner-html':
							$preset_template = str_replace( $map_style['replace'], $value, $preset_template );
							break;

						default:
							if ( 'color' == $map_style['parameter'] ) {
								$button_text_color = $value;
							}

							if ( 'inner_html' !== $map_style['parameter'] ) {

								$field_style .= cp_generate_map_inline_style( $map_style, $value, $unit );
							}
							break;
					}
				}
			}
		}

		$title = isset( $preset['title'] ) ? $preset['title'] : $title;

		if ( is_array( $title ) ) {
			$title = $title['value'];
		}

		$preset_template = str_replace( '{{field_tags}}', $tags, $preset_template );
		$preset_template = str_replace( 'id="{{id}}"', '', $preset_template );
		$preset_template = str_replace( '{{class}}', $class, $preset_template );
		$preset_template = str_replace( '{{value}}', $preset_name, $preset_template );
		$preset_template = str_replace( '{{title}}', $title, $preset_template );
		$preset_template = str_replace( '{{type}}', $type, $preset_template );
		$preset_template = str_replace( '{{field_preset}}', $key, $preset_template );
		$preset_template = str_replace( '{{resize}}', $resize, $preset_template );
		$preset_template = str_replace( '{{button-type}}', 'button', $preset_template );

		echo '<style type="text/css">';

		if ( '' != trim( $field_style ) ) {
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target {' . $field_style . '}';
		}

		if ( '' != trim( $button_text_color ) ) {
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target .cp_loader_container { border-left-color:' . $button_text_color . '}';
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target .cp_success_loader_container { border-color:' . $button_text_color . '}';
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target .cp-button-loader-style:after { border-right-color:' . $button_text_color . '}';
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target .cp-button-loader-style:after { border-top-color:' . $button_text_color . '}';
		}

		if ( '' != trim( $hover_style ) ) {
			echo '.cp-preset-field[data-type="' . $type . '"][data-preset="' . $key . '"] .cp-target:not(.cp-loading-in-progress):hover {' . $hover_style . '}';
		}
		echo '</style>';

		if ( 'cp_heading' == $type ) {

			$preset_template = '<div class="cp_element_drager_wrap ' . $type . '">' . $preset_template . '</div>';
		}

		echo $preset_template;
	}

	$content = ob_get_clean();

	return $content;

}

/**
 * Function Name: cp_duplicate_popup.
 * Function Description: cp duplicate popup.
 *
 * @param int    $popup_id int parameter.
 * @param string $title title for duplicated design parameter.
 */
function cp_duplicate_popup( $popup_id = '', $title ) {

	if ( ! current_user_can( 'edit_cp_popup' ) ) {

		return new WP_Error( 'broke', __( 'You do not have permissions to perform this action', 'convertpro' ) );
	}

	if ( '' !== $popup_id ) {

		$post = get_post( (int) $popup_id );

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if post data exists, create the post duplicate
		 */
		if ( isset( $post ) && null != $post ) {

			$post_id = (int) $popup_id;

			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'publish',
				'post_title'     => $title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");.

			foreach ( $taxonomies as $taxonomy ) {

				if ( CP_AB_TEST_TAXONOMY != $taxonomy ) {
					$post_terms = wp_get_object_terms(
						$post_id, $taxonomy, array(
							'fields' => 'slugs',
						)
					);
					wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
				}
			}

			// Copy post metadata.
			$data = get_post_custom( $post_id );

			$exclude_meta_keys = array(
				'design',
				'configure',
				'connect',
			);

			if ( ! empty( $data ) ) {
				foreach ( $data as $meta_key => $values ) {

					if ( ! in_array( $meta_key, $exclude_meta_keys ) ) {

						foreach ( $values as $meta_value ) {

							$filtered_val = addslashes( $meta_value );

							$meta_value = ( 'html_data' == $meta_key ) ? str_replace( $post_id, $new_post_id, $filtered_val ) : $filtered_val;

							$meta_value = ( 'live' == $meta_key ) ? '' : $meta_value;

							add_post_meta( $new_post_id, $meta_key, $meta_value );
						}
					}
				}
			}

			foreach ( $exclude_meta_keys as $meta_key ) {
				$meta_data = get_post_meta( $post_id, $meta_key, true );
				update_post_meta( $new_post_id, $meta_key, $meta_data );
			}

			$data = array(
				'message'  => 'Successfully duplicated',
				'popup_id' => $new_post_id,
			);

		} else {
			$data = array(
				'message' => 'error',
			);
		}
	} else {
		$data = array(
			'message' => 'error',
		);
	}

	return $data;
}

/**
 * Function Name: cp_get_insights_row.
 * Function Description: cp get insights row.
 *
 * @param string $style string parameter.
 */
function cp_get_insights_row( $style ) {
	ob_start();

	$duplicate_url                          = 'javascript:void(0);';
	$delete_url                             = 'javascript:void(0);';
	$has_active_ab_test['status']           = false;
	$has_active_ab_test['completed_status'] = false;

	$cp_moule_type = get_post_meta( $style->ID, 'cp_module_type', true );
	$cp_moule_type = ucwords( str_replace( '_', ' ', $cp_moule_type ) );

	if ( class_exists( 'CP_V2_AB_Test' ) ) {
		$ab_test_inst       = CP_V2_AB_Test::get_instance();
		$has_active_ab_test = $ab_test_inst->has_active_ab_test( $style->ID );
	}
	if ( isset( $has_active_ab_test['is_parent'] ) && '1' == $has_active_ab_test['is_parent'] ) {
		$tag_class = 'cp-test-parent-tag';
	} else {
		$tag_class = '';
	}

	$cp_insight_rows = array(
		'insight',
		'type',
		'style-status',
	);

	$cp_insight_rows = apply_filters( 'cp_design_list_rows', $cp_insight_rows );
	$is_parent       = false;
	$active_ab_test  = true;

	if ( isset( $has_active_ab_test['status'] ) && $has_active_ab_test['status']
		&& isset( $has_active_ab_test['is_parent'] ) && $has_active_ab_test['is_parent'] ) {
		$is_parent = true;
	} elseif ( isset( $has_active_ab_test['status'] ) && ! $has_active_ab_test['status'] ) {
		$active_ab_test = false;
	}

	?>

	<div class="cp-row cp-popup-row cp-row-<?php echo $style->ID; ?> cp-row-width-<?php echo count( $cp_insight_rows ); ?> <?php echo $tag_class; ?>" data-id="<?php echo $style->ID; ?>" data-name="<?php echo $style->post_title; ?>" data-ab-test="<?php echo $has_active_ab_test['status']; ?>">
		<div class="cp-acc-4 cp-column-title">
			<div class="cp-style-title">
				<?php edit_post_link( $style->post_title, ( ! $is_parent && $active_ab_test ) ? '&#8212; ' : '', '', $style->ID, 'cp_edit_post_link' ); ?>
				<span class="cp-edit-popup-title" id="cp-edit-title-<?php echo $style->ID; ?>">
					<input type="text" value="<?php echo $style->post_title; ?>" class="cp-edit-popup-text">
				</span>                     
				<span class="cp-hidden-action-panel">
					<input class="cp-hidden-edit-style cp-hidden-action-panel-<?php echo $style->ID; ?>" type="hidden" value="<?php echo get_edit_post_link( $style->ID ); ?>">
					<input class="cp-hidden-duplicate-style" type="hidden" value="<?php echo $duplicate_url; ?>">
					<?php
					if ( false != $has_active_ab_test['status'] ) {
						$delete_url = false;
					}
?>
					<input class="cp-hidden-delete-style" type="hidden" value="<?php echo $delete_url; ?>">
				</span>
			</div>
		</div>
		<div class="cp-col-8 cp-insight-col-<?php echo count( $cp_insight_rows ); ?>">
			<div class="cp-accordion-block">
				<?php foreach ( $cp_insight_rows as $row ) { ?>

					<div class="cp-lead-groups-block cp-view-<?php echo $row; ?> cp-<?php echo $row; ?>">
						<?php
						$action_slug = str_replace( '-', '_', $row );
						do_action( 'cp_get_' . $action_slug . '_row_value', $style );
						?>
					</div>				
				<?php
}

				$edit_data = 'data-ab-test="false"';

if ( $has_active_ab_test['status'] || $has_active_ab_test['completed_status'] ) {
	$edit_data = 'data-ab-test="true"';
}
?>
				<div class="cp-edit-settings" <?php echo $edit_data; ?>></div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Function Name: cp_is_test_running.
 * Function Description: cp is test running.
 *
 * @param array $styles array parameter.
 */
function cp_is_test_running( $styles ) {

	if ( class_exists( 'CP_V2_AB_Test' ) ) {

		foreach ( $styles as $key => $style ) {

			$ab_test_inst       = CP_V2_AB_Test::get_instance();
			$has_active_ab_test = $ab_test_inst->has_active_ab_test( $style->ID );

			if ( $has_active_ab_test['status'] ) {
				return false;
			}
		}

		return true;
	}

	return false;
}

/**
 * Function Name: cpro_build_timezones.
 * Function Description: Builds timezone array.
 *
 * @param array $selected_zone selected zone.
 */
function cpro_build_timezones( $selected_zone ) {

	$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific' );

	$zonen = array();
	foreach ( timezone_identifiers_list() as $zone ) {
		$zone = explode( '/', $zone );
		if ( ! in_array( $zone[0], $continents ) ) {
			continue;
		}

		$exists    = array(
			0 => ( isset( $zone[0] ) && $zone[0] ),
			1 => ( isset( $zone[1] ) && $zone[1] ),
			2 => ( isset( $zone[2] ) && $zone[2] ),
		);
		$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
		$exists[4] = ( $exists[1] && $exists[3] );
		$exists[5] = ( $exists[2] && $exists[3] );

		$zonen[] = array(
			'continent'   => ( $exists[0] ? $zone[0] : '' ),
			'city'        => ( $exists[1] ? $zone[1] : '' ),
			'subcity'     => ( $exists[2] ? $zone[2] : '' ),
			't_continent' => ( $exists[3] ? str_replace( '_', ' ', $zone[0] ) : '' ),
			't_city'      => ( $exists[4] ? str_replace( '_', ' ', $zone[1] ) : '' ),
			't_subcity'   => ( $exists[5] ? str_replace( '_', ' ', $zone[2] ) : '' ),
		);
	}
	usort( $zonen, '_wp_timezone_choice_usort_callback' );

	$structure = array();

	if ( empty( $selected_zone ) ) {
		$structure[] = '<option selected="selected" value="">' . __( 'Select a city', 'convertpro' ) . '</option>';
	}

	foreach ( $zonen as $key => $zone ) {
		// Build value in an array to join later.
		$value = array( $zone['continent'] );

		if ( empty( $zone['city'] ) ) {
			// It's at the continent level (generally won't happen).
			$display = $zone['t_continent'];
		} else {
			// It's inside a continent group.
			// Continent optgroup.
			if ( ! isset( $zonen[ $key - 1 ] ) || $zonen[ $key - 1 ]['continent'] !== $zone['continent'] ) {
				$label       = $zone['t_continent'];
				$structure[] = '<optgroup label="' . esc_attr( $label ) . '">';
			}

			// Add the city to the value.
			$value[] = $zone['city'];

			$display = $zone['t_city'];
			if ( ! empty( $zone['subcity'] ) ) {
				// Add the subcity to the value.
				$value[]  = $zone['subcity'];
				$display .= ' - ' . $zone['t_subcity'];
			}
		}

		// Build the value.
		$value        = join( '/', $value );
		$_time_zone   = new DateTimeZone( $value );
		$_time        = new DateTime( 'now', $_time_zone );
		$_time_offset = ( $_time_zone->getOffset( $_time ) ) / 3600;
		$selected     = '';
		if ( $value === $selected_zone ) {
			$selected = 'selected="selected" ';
		}
		$structure[] = '<option ' . $selected . 'value="' . esc_attr( $value . '#' . $_time_offset ) . '">' . esc_html( $display ) . '</option>';

		// Close continent optgroup.
		if ( ! empty( $zone['city'] ) && ( ! isset( $zonen[ $key + 1 ] ) || ( isset( $zonen[ $key + 1 ] ) && $zonen[ $key + 1 ]['continent'] !== $zone['continent'] ) ) ) {
			$structure[] = '</optgroup>';
		}
	}

	// Do UTC.
	$structure[] = '<optgroup label="' . esc_attr__( 'UTC', 'convertpro' ) . '">';
	$selected    = '';
	if ( 'UTC' === $selected_zone ) {
		$selected = 'selected="selected" ';
	}
	$structure[] = '<option ' . $selected . 'value="' . esc_attr__( 'UTC#0', 'convertpro' ) . '">' . __( 'UTC', 'convertpro' ) . '</option>';
	$structure[] = '</optgroup>';

	return join( "\n", $structure );
}
