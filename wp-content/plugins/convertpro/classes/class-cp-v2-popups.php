<?php
/**
 * Popup Class.
 *
 * @package ConvertPro
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Class CP_V2_Popups.
 */
final class CP_V2_Popups {

	/**
	 * The unique instance of the plugin.
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Current post variable.
	 *
	 * @var current_post
	 */
	private $current_post;

	/**
	 * Gets an instance of our plugin.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create a popup.
	 *
	 * @param string $style_id style_id.
	 * @param string $style_name style_name.
	 * @param string $meta_data meta_data.
	 * @param string $post_status post_status.
	 * @param string $module_type module_type.
	 * @param string $parent_id parent_id.
	 * @param string $current_step current_step.
	 * @access public
	 * @since 0.0.1
	 * @return int $post_id
	 */
	public function create( $style_id = '', $style_name, $meta_data, $post_status, $module_type, $parent_id = '', $current_step = 1 ) {

		$style_settings = $meta_data;
		$display        = '';
		$is_preview     = false;

		if ( 'auto-preview' == $post_status ) {
			$is_preview = true;
		}

		// Gather post data.
		$cp_popup_post = array(
			'post_title'   => $style_name,
			'post_content' => '',
			'post_status'  => $post_status,
			'post_type'    => CP_CUSTOM_POST_TYPE,
			'type'         => $module_type,
		);

		if ( '' !== $style_id && null != $style_id && false !== $style_id ) {
			$cp_popup_post['ID'] = $style_id;
		}

		if ( '' != $parent_id ) {
			$cp_popup_post['post_parent'] = $parent_id;
		}

		// Insert the post into the database.
		$style_id = wp_insert_post( $cp_popup_post );

		if ( 0 == $style_id ) {

			$data = $style_id;
			wp_send_json_error( $data );
		}

		// Add active ab test key.
		update_post_meta( $style_id, 'has_active_ab_test', false );

		if ( '' != $parent_id ) {
			update_post_meta( $parent_id, 'preview_post_id', $style_id );
		}

		$sections         = array();
		$skip_meta_values = array(
			'cp-save-ajax-nonce',
			'cp-preview-ajax-nonce',
		);

		if ( is_array( $style_settings ) ) {
			foreach ( $style_settings as $key => $value ) {

				if ( isset( $value['value'] ) ) {
					if ( ! isset( $value['section'] ) ) {

						$meta_key   = $value['name'];
						$meta_value = $value['value'];

						if ( ! in_array( $meta_key, $skip_meta_values ) ) {
							update_post_meta( $style_id, $meta_key, $meta_value );
						}
					} else {

						// For meta groups.
						if ( ! isset( $sections[ $value['section'] ] ) ) {
							$sections[ $value['section'] ] = array();
						}

						if ( 'configure' == $value['section'] ) {
							$exclude_configure = array(
								'autoload_on_duration',
								'load_on_duration',
								'modal_exit_intent',
								'autoload_on_scroll',
								'load_after_scroll',
								'inactivity',
								'enable_after_post',
								'enable_custom_scroll',
								'enable_scroll_class',
								'enable_custom_class',
								'custom_class',
								'display_on_first_load',
								'enable_referrer',
								'display_to',
								'hide_from',
								/* Scheduler */
								'enable_scheduler',
								'start_date',
								'end_date',
							);

							if ( ! in_array( $value['name'], $exclude_configure ) ) {
								$sections[ $value['section'] ][ $value['name'] ] = $value['value'];
							}
						} else {
							$field = array(
								$value['name'] => $value['value'],
							);
							array_push( $sections[ $value['section'] ], $field );
						}
					}
				} else {
					foreach ( $value as $f_data ) {

						if ( ! isset( $sections[ $f_data['section'] ] ) ) {
							$sections[ $f_data['section'] ] = array();
						}

						if ( 'configure' == $f_data['section'] ) {
							$sections[ $f_data['section'] ][0][ $f_data['name'] ][] = $f_data['value'];
						}
					}
				}
			}
		}

		// Update meta values for all fields.
		foreach ( $sections as $section => $value ) {
			update_post_meta( $style_id, $section, $value );
		}

		$tax = cpro_get_style_settings( $style_id, 'connect', 'cp_connect_settings' );
		$tax = json_decode( $tax );

		if ( 'inline' == $module_type || 'widget' == $module_type || 'before_after' == $module_type ) {
			$display = 'inline';
		}

		if ( is_array( $tax ) ) {
			foreach ( $tax as $key => $t ) {
				if ( 'cp-integration-account-slug' == $t->name ) {
					$taxonomy = wp_set_post_terms( $style_id, $t->value, CP_CONNECTION_TAXONOMY );
				}
			}
		}

		$output = $this->render( $style_id, $is_preview, $current_step, $module_type, $display, '' );

		$output = str_replace( '{{http_url}}', 'http://', $output );
		$output = str_replace( '{{https_url}}', 'https://', $output );

		$output_formattted = htmlspecialchars( $output );

		update_post_meta( $style_id, 'html_data', $output_formattted );

		return $style_id;
	}

	/**
	 * Get All Popups
	 *
	 * @since 0.0.1
	 * @return array $style_id
	 */
	public static function get_all() {

		$query_args = array(
			'post_type'      => CP_CUSTOM_POST_TYPE,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

		$popups = new WP_Query( $query_args );

		wp_reset_postdata();

		$custom_post_type = $popups->posts;

		return $custom_post_type;
	}

	/**
	 * Get Popup
	 *
	 * @since 0.0.1
	 * @param array $type type.
	 * @return array $style_id
	 */
	public function get( $type = 'all' ) {

		$meta_condition = array(
			'relation' => 'AND',
			array(
				'key'   => 'live',
				'value' => 1,
			),
			array(
				'key'   => 'has_active_ab_test',
				'value' => array( 0, false ),
			),
		);

		if ( 'inline' == $type || 'before_after' == $type ) {
			$meta_condition[] = array(
				'key'     => 'cp_module_type',
				'value'   => array( 'inline', 'before_after' ),
				'compare' => 'IN',
			);
		} elseif ( 'all' != $type && 'launch' != $type ) {
			$meta_condition[] = array(
				'key'     => 'cp_module_type',
				'value'   => array( $type ),
				'compare' => 'IN',
			);
		} elseif ( 'launch' == $type ) {
			$meta_condition[] = array(
				'key'     => 'cp_module_type',
				'value'   => array( 'modal_popup', 'info_bar', 'slide_in', 'welcome_mat', 'full_screen' ),
				'compare' => 'IN',
			);
		} else {
			$meta_condition = array(
				'relation' => 'OR',
				array(
					'key'   => 'live',
					'value' => 1,
				),
				array(
					'key'   => 'has_active_ab_test',
					'value' => true,
				),
			);
		}

		$query_args = array(
			'post_type'      => CP_CUSTOM_POST_TYPE,
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_status'    => 'publish',
			'meta_query'     => $meta_condition,
		);

		$this->cp_backup_post_data();

		$popups = new WP_Query( $query_args );

		$this->cp_restore_post_data();

		$custom_post_type = $popups->posts;

		return $custom_post_type;
	}

	/**
	 * Backup current post variable
	 *
	 * @since 0.0.1
	 */
	function cp_backup_post_data() {

		global $post;
		$this->current_post = $post;
	}

	/**
	 * Restore current post variable
	 *
	 * @since 0.0.1
	 */
	function cp_restore_post_data() {

		global $post;
		wp_reset_postdata();
		$post = $this->current_post;
	}

	/**
	 * Get popup by campaign ID
	 *
	 * @since 0.0.1
	 * @param int $campaign_id campaign_id.
	 * @return array $style_id
	 */
	function get_popups_by_campaign_id( $campaign_id ) {

		$posts_array = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CP_CUSTOM_POST_TYPE,
				'tax_query'      => array(
					array(
						'taxonomy' => CP_CAMPAIGN_TAXONOMY,
						'field'    => 'term_id',
						'terms'    => $campaign_id,
					),
				),
			)
		);

		return $posts_array;

	}

	/**
	 * Render Popup
	 *
	 * @since 0.0.1
	 * @param int    $style_id style_id.
	 * @param bool   $is_preview is_preview.
	 * @param int    $default_step default_step.
	 * @param string $type type.
	 * @param bool   $display display.
	 * @param string $manual manual.
	 */
	public function render( $style_id, $is_preview, $default_step, $type, $display, $manual ) {

		ob_start();

		$active_class                 = '';
		$ifb_position                 = '';
		$slidein_position             = '';
		$is_ifb_sticky                = '';
		$user_inactivity_data         = '';
		$enable_after_post_class      = '';
		$display_class                = '';
		$toggle_fields                = '';
		$onload                       = '';
		$onload_style                 = '';
		$enable_scroll_class          = '';
		$box_val                      = '';
		$shadow_class                 = '';
		$common_field_html            = '';
		$form_field_focus_style       = '';
		$toggle_active_class          = '';
		$panel_toggle_enabled         = '';
		$toggle_type                  = '';
		$panel_infobar_toggle_enabled = '';
		$cp_open_class                = '';
		$panel_position               = '';
		$fix_position                 = '';
		$panel_shadow_type            = '';
		$toggle_minimizer             = '';
		$infobar_toggle_text          = '';
		$toggle_text                  = '';
		$inactive_settings            = '';
		$enable_after_post            = '';
		$enable_custom_scroll         = '';
		$custom_class                 = '';
		$in_content_position          = '';

		do_action( 'cp_before_popup' );

		$style_data = get_post( $style_id );
		$style_slug = $style_data->post_name;

		$data                = get_post_meta( $style_id, 'cp_modal_data', true );
		$style_title         = get_the_title( $style_id );
		$scheduled_data      = cp_generate_scheduled_attributes( $style_id );
		$design_meta_data    = get_post_meta( $style_id, 'design', true );
		$configure_meta_data = get_post_meta( $style_id, 'configure', true );

		if ( is_array( $design_meta_data ) && ! empty( $design_meta_data ) ) {
			foreach ( $design_meta_data as $key => $meta_value ) {
				if ( array_key_exists( 'panel_position', $meta_value ) ) {
					$panel_position = $meta_value['panel_position'];
				}

				if ( array_key_exists( 'fix_position', $meta_value ) ) {
					$fix_position = $meta_value['fix_position'];
				}

				if ( array_key_exists( 'panel_box_shadow', $meta_value ) ) {
					$panel_shadow_type = $meta_value['panel_box_shadow'];
				}

				if ( array_key_exists( 'panel_toggle', $meta_value ) ) {
					$panel_toggle_enabled = $meta_value['panel_toggle'];
				}

				if ( array_key_exists( 'toggle_type', $meta_value ) ) {
					$toggle_type = $meta_value['toggle_type'];
				}

				if ( array_key_exists( 'panel_toggle_infobar', $meta_value ) ) {
					$panel_infobar_toggle_enabled = $meta_value['panel_toggle_infobar'];
				}

				if ( array_key_exists( 'toggle_minimizer', $meta_value ) ) {
					$toggle_minimizer = $meta_value['toggle_minimizer'];
				}

				if ( array_key_exists( 'toggle_infobar_text', $meta_value ) ) {
					$infobar_toggle_text = $meta_value['toggle_infobar_text'];
				}

				if ( array_key_exists( 'toggle_text', $meta_value ) ) {
					$toggle_text = $meta_value['toggle_text'];
				}
			}
		}

		if ( $panel_position && 'info_bar' == $type ) {
			$ifb_position = 'cp-' . $panel_position;
		}

		if ( ! $fix_position && 'info_bar' == $type ) {
			$is_ifb_sticky = 'cp-ifb-scroll';
		}

		if ( $panel_position && 'slide_in' == $type ) {
			$slidein_position = $panel_position;
		}

		if ( $panel_position && ( 'inline' == $type || 'before_after' == $type ) ) {
			$in_content_position = 'cp-' . $panel_position;
		}

		$inactivity_val = esc_attr( get_option( 'cp_user_inactivity' ) );
		if ( ! $inactivity_val ) {
			$inactivity_val = '60';
		}

		$user_inactivity_data = "data-inactive-time='" . $inactivity_val . "'";

		// Custom class trigger.
		$cusom_class_arr      = explode( ',', $custom_class );
		$cusom_class_arr[]    = 'cp-custom-cls-manual_trigger_' . $style_id;
		$enable_custom_class  = 'cp-popup-global ';
		$infobar_toggle_class = '';

		if ( is_array( $cusom_class_arr ) && ! empty( $cusom_class_arr ) ) {
			foreach ( $cusom_class_arr as $key => $value ) {

				if ( false !== strpos( $value, '#' ) ) {
					$value = str_replace( '#', 'cp-custom-cls-', $value );
				}
				if ( false !== strpos( $value, '.' ) ) {
					$value = str_replace( '.', 'cp-custom-cls-', $value );
				}
				$value                = str_replace( ' ', '', $value );
				$enable_custom_class .= $value . ' ';
			}
		}

		if ( 'inline' == $display ) {
			$display_class .= 'cp-popup-inline ';
		}

		if ( ! $manual ) {
			$onload = 'cp-auto';
		} else {
			$onload              = 'cp-manual';
			$enable_custom_class = $enable_custom_class . 'cp-trigger-' . + $style_id;
		}

		$box_val = explode( '|', $panel_shadow_type );
		$result  = array();

		foreach ( $box_val as $pair ) {
			if ( ! empty( $pair ) ) {
				$pair = explode( ':', $pair );
				if ( ! empty( $pair ) ) {
					$result[ $pair[0] ] = $pair[1];
				}
			}
		}

		if ( isset( $result['type'] ) && 'inset' == $result['type'] ) {
			$shadow_class = 'cp-shadow-inset';
		}

		if ( 'inline' == $display ) {
			$onload        = 'cp-manual';
			$cp_open_class = 'cpro-open';
		}

		if ( 'inline' !== $display ) {
		?>
			<div class="cpro-onload <?php echo $enable_after_post_class; ?> <?php echo $enable_custom_class; ?>" <?php echo $enable_scroll_class; ?> data-class-id="<?php echo $style_id; ?>" <?php echo $user_inactivity_data; ?> ></div>
		<?php
		}

		if ( '1' == $panel_toggle_enabled ) {
			$toggle_active_class = 'cp_has_toggle cp_has_toggle_' . $toggle_type;
		}

		if ( '1' == $panel_infobar_toggle_enabled ) {
			$toggle_active_class  = 'cp_has_infobar_toggle';
			$infobar_toggle_class = 'cp_infobar_toggle';
		}

		$popup_container_classes = 'cp-' . $type . ' ' . $ifb_position . ' ' . $shadow_class . ' ' . $slidein_position . ' ' . $in_content_position . ' ' . $active_class;

		?>

		<div class="cp-popup-container cp-popup-live-wrap cp_style_<?php echo $style_id; ?> cp-module-<?php echo $type; ?> <?php echo $cp_open_class; ?> <?php echo $toggle_active_class; ?>" data-style="<?php echo 'cp_style_' . $style_id; ?>" data-module-type="<?php echo $type; ?>" data-class-id="<?php echo $style_id; ?>" data-styleslug="<?php echo esc_attr( $style_slug ); ?>">

			<?php if ( 'modal_popup' == $type ) { ?>

				<div class="cpro-overlay">
			<?php } ?>

			<div class="cp-popup-wrapper <?php echo $onload; ?> <?php echo $display_class; ?> <?php echo $is_ifb_sticky; ?>" <?php echo $scheduled_data; ?> >
				<!--- CP Popup Start -->
				<div class="cp-popup <?php echo $infobar_toggle_class; ?> cpro-animate-container <?php echo $ifb_position; ?>">

					<?php if ( 'full_screen' == $type || 'welcome_mat' == $type || 'info_bar' == $type ) { ?>
						<div class="cpro-fs-overlay"></div>
					<?php } ?>

				<?php
					$modal_panels        = json_decode( $data );
					$form_fields_array   = array(
						'cp_email',
						'cp_text',
						'cp_dropdown',
						'cp_textarea',
						'cp_radio',
						'cp_checkbox',
						'cp_hidden_input',
					);
					$show_form_tag_array = array();
					$show_form_tag       = true;
				foreach ( $modal_panels as $key => $attr ) {
					foreach ( $attr as $panelkey => $panelvalue ) {
						$str                   = explode( '-', $panelkey );
						$show_form_tag_array[] = in_array( $str[0], $form_fields_array );
					}
				}
					$show_form_tag = ( false === array_search( true, $show_form_tag_array ) ) ? false : true;
				if ( $show_form_tag ) {
				?>
				<form class="cpro-form" method="post">
				<?php
				}
				foreach ( $modal_panels as $key => $attr ) {

					$generate_hidden_fields = false;

					if ( 'common' === $key ) {

						foreach ( $attr as $panelkey => $panelvalue ) {
							$common_field_data = cp_get_panel( $panelvalue, $panelkey, $style_id );

							if ( isset( $common_field_data['style'] ) ) {
								$common_field_html .= '<style> ' . $common_field_data['style'] . '</style>';
							}

							if ( isset( $common_field_data['html'] ) ) {
								$common_field_html .= $common_field_data['html'];
							}
						}

						continue;
					}

					$anim_data              = '';
					$close_overlay          = '';
					$exit_animation         = '';
					$entry_animation        = '';
					$close_overlay_click    = '';
					$size_data              = '';
					$active_class           = '';
					$lazy_load_classes      = '';
					$lazy_load_bg_img       = '';
					$bg_data_attr           = '';
					$submit_container_class = '';
					$animation_class        = '';

					foreach ( $attr as $panelkey => $panelvalue ) {

						if ( strpos( $panelkey, 'panel' ) !== false ) {

							if ( isset( $panelvalue->panel_entry_animation ) ) {
								$entry_animation = $panelvalue->panel_entry_animation;
							}

							if ( isset( $panelvalue->close_overlay_click ) ) {
								$close_overlay_click = $panelvalue->close_overlay_click;
							} else {
								$close_overlay_click = '1';
							}

							if ( isset( $panelvalue->panel_width ) ) {
								$size_data .= ' data-width="' . $panelvalue->panel_width[0] . '"';

								if ( isset( $panelvalue->panel_width[1] ) ) {
									$size_data .= ' data-mobile-width="' . $panelvalue->panel_width[1] . '"';
								}
							}

							if ( isset( $panelvalue->panel_height ) ) {
								$size_data .= ' data-height="' . $panelvalue->panel_height[0] . '"';

								if ( isset( $panelvalue->panel_height[1] ) ) {
									$size_data .= ' data-mobile-height="' . $panelvalue->panel_height[1] . '"';
								}
							}

							if ( isset( $panelvalue->cp_mobile_br_point ) ) {
								$size_data .= ' data-mobile-break-pt="' . $panelvalue->cp_mobile_br_point . '"';
							} else {
								$size_data .= ' data-mobile-break-pt="767"';
							}

							if ( isset( $panelvalue->panel_position ) ) {
								$panel_position = str_replace( '-', ' ', $panelvalue->panel_position );
								$size_data     .= ' data-popup-position="' . $panel_position . '"';
							}

							if ( isset( $panelvalue->background_type ) ) {
								if ( is_array( $panelvalue->background_type ) && 'image' == $panelvalue->background_type[0] ) {
									$lazy_load_classes .= 'cp-img-lazy cp-bg-lazy';
								} elseif ( 'image' == $panelvalue->background_type ) {
									$lazy_load_classes .= 'cp-img-lazy cp-bg-lazy';
								}
							}

							$is_inherit_bg_prop = isset( $panelvalue->inherit_bg_prop ) ? $panelvalue->inherit_bg_prop : '1';

							if ( isset( $panelvalue->panel_bg_image ) ) {

								// if it is a first step?
								if ( '0' == $key ) {
									$inherited_bg_image = $panelvalue->panel_bg_image;
								}

								if ( is_array( $panelvalue->panel_bg_image ) && isset( $panelvalue->panel_bg_image ) ) {

									if ( '0' != $key && '1' == $is_inherit_bg_prop ) {

										$lazy_load_bg_img = $inherited_bg_image;
									} else {
										$lazy_load_bg_img = $panelvalue->panel_bg_image;
									}
								} elseif ( $panelvalue->panel_bg_image ) {

									// if it is a first step?
									if ( '0' == $key ) {
										$inherited_bg_image = $panelvalue->panel_bg_image;
									}

									if ( '0' != $key && '1' == $is_inherit_bg_prop ) {
										$lazy_load_bg_img = $inherited_bg_image;
									} else {
										$lazy_load_bg_img = $panelvalue->panel_bg_image;
									}
								}
							}

							if ( '' !== $lazy_load_classes && '' !== $lazy_load_bg_img ) {

								if ( is_array( $lazy_load_bg_img ) ) {
									$lazy_load_bg_img = json_encode( $lazy_load_bg_img );
								}

								$bg_data_attr = 'data-cp-src="' . htmlspecialchars( $lazy_load_bg_img ) . '"';
							}

							$size_data .= ' data-mobile-responsive="' . esc_attr( get_post_meta( $style_id, 'cp_mobile_responsive', true ) ) . '"';
						}
					}

					if ( 'inline' == $display ) {
						$entry_animation = '';
						$exit_animation  = '';
					}

					if ( '' !== $entry_animation ) {
						$anim_data .= 'data-entry-animation = "' . $entry_animation . '"';
					}

					if ( '' !== $exit_animation ) {
						$anim_data .= 'data-exit-animation ="cp-fadeOut"';
					}

					if ( '' !== $close_overlay_click ) {
						$close_overlay .= 'data-overlay-click ="' . $close_overlay_click . '"';
					}

					$step_id = (int) $key + 1;

					if ( $is_preview ) {

						if ( $default_step == $step_id ) {
							$active_class = 'cpro-active-step';
						}
					} else {

						if ( 0 == (int) $key ) {
							$active_class = 'cpro-active-step';
						}
					}

					if ( 0 == $key ) {
						$generate_hidden_fields = true;
					}

					$content = cp_get_form_content( $style_id, $attr, $generate_hidden_fields, $design_meta_data );

					?>

					<div class="cp-popup-content <?php echo $active_class; ?> <?php echo $lazy_load_classes; ?> <?php echo $popup_container_classes; ?> cp-panel-<?php echo $step_id; ?>" <?php echo $anim_data; ?> <?php echo $bg_data_attr; ?> <?php echo $close_overlay; ?> data-title="<?php echo sanitize_text_field( $style_title ); ?>" data-module-type="<?php echo $type; ?>"  data-step="<?php echo $step_id; ?>" <?php echo $size_data; ?>>
					<?php
					if ( 'slide_in' == $type && '1' == $panel_toggle_enabled && 'sticky' == $toggle_type ) {

						switch ( $slidein_position ) {
							case 'bottom-right':
							case 'bottom-left':
							case 'bottom-center':
								$animation_class .= ' cp-slideInUp';
								break;
							case 'top-left':
							case 'top-right':
							case 'top-left':
								$animation_class .= ' cp-slideInDown';
								break;
							case 'center-left':
								$animation_class .= ' cp-slideInDown';
								break;
							case 'center-right':
								$animation_class .= ' cp-slideInDown';
								break;
						}

							echo '<div class="cp-open-toggle-wrap cp-toggle-type-' . $toggle_type . ' ' . $slidein_position . '">';
								echo '<div class="cp-open-toggle cp-toggle-' . $slidein_position . ' " data-position="' . $slidein_position . '" data-type="' . $toggle_type . '">';
									echo '<span class="cp-open-toggle-content">' . $toggle_text . '</span>';

						if ( '1' == $toggle_minimizer ) {
							echo '<span class="cp-toggle-icon cp-icon-arrow"></span>';
						}
								echo '</div>';
							echo '</div>';
					}
?>
					<?php do_action( 'cp_before_popup_content', $style_id ); ?>

					<div class="cpro-form-container">                             
						<?php
						echo $content['output_html'];
						echo $content['custom_html'];
						?>
							</div>	            
							<?php do_action( 'cp_after_popup_content', $style_id ); ?>
							<?php if ( '' != $content['inner_wrap'] ) { ?>
							<div class="cp-inner-panel-wrap">
								<?php echo $content['inner_wrap']; ?>
							</div>
							<?php } ?>

						</div><!-- .cp-popup-content -->
					<?php
				}
					do_action( 'cp_pro_form_hidden_fields', $style_id );
				if ( $show_form_tag ) {
				?>
				</form>
				<?php
				}
					?>
				</div>
				<?php
				echo $common_field_html;
				?>

			</div><!-- .cp-popup-wrapper -->
			<?php

			if ( 'slide_in' == $type && '1' == $panel_toggle_enabled && 'sticky' != $toggle_type ) {

				$toggle_text     = cpro_get_style_settings( $style_id, 'design', 'toggle_text' );
				$animation_class = 'cp-animated';

				switch ( $slidein_position ) {
					case 'bottom-right':
					case 'bottom-left':
					case 'bottom-center':
						$animation_class .= ' cp-slideInUp';
						break;
					case 'top-left':
					case 'top-right':
					case 'top-left':
						$animation_class .= ' cp-slideInDown';
						break;
					case 'center-left':
						$animation_class .= ' cp-slideInLeft';
						break;
					case 'center-right':
						$animation_class .= ' cp-slideInRight';
						break;
				}

				echo '<div class="cp-open-toggle-wrap cp-toggle-type-' . $toggle_type . ' ' . $slidein_position . '">';
					echo '<div class="cp-open-toggle cp-toggle-' . $slidein_position . ' " data-position="' . $slidein_position . '" data-type="' . $toggle_type . '">';
						echo '<span class="cp-open-toggle-content">' . $toggle_text . '</span>';
					echo '</div>';
				echo '</div>';
			}
			?>

			<?php

			if ( 'info_bar' == $type && '1' == $panel_infobar_toggle_enabled ) {

				$animation_class = 'cp-animated';

				switch ( $panel_position ) {
					case 'bottom':
						$animation_class .= ' cp-slideInUp';
						break;
					case 'top':
						$animation_class .= ' cp-slideInDown';
						break;
				}

				echo '<div class="cp-open-infobar-toggle-wrap cp-' . $panel_position . '">';
					echo '<div class="cp-open-infobar-toggle cp-toggle-' . $panel_position . ' " data-position="' . $panel_position . '">';
						echo '<span class="cp-open-infobar-toggle-content">' . $infobar_toggle_text . '</span>';
						echo '<span class="cp-toggle-infobar-icon cp-icon-arrow cp-' . $position . '"></span>';
					echo '</div>';
				echo '</div>';
			}

			if ( 'modal_popup' == $type || 'welcome_mat' == $type || 'full_screen' == $type ) {

			?>
				</div><!-- Overlay -->
				{{cpro_credit_link}}

			<?php } ?>
		</div><!-- Modal popup container -->
			<?php

			$output_html = ob_get_clean();
			return $output_html;
	}

	/**
	 * Adds Slashes
	 *
	 * @param string $value value.
	 * @return array|string
	 */
	public static function addslashes( $value ) {
		$value = is_array( $value ) ?
			array_map( array( 'self', 'addslashes' ), $value ) :
			addslashes( $value );
		return $value;
	}

	/**
	 * Get panel settings
	 *
	 * @param int  $style_id style_id.
	 * @param int  $panel panel.
	 * @param bool $encoded encoded.
	 * @return array|string
	 */
	public function get_panel_settings( $style_id, $panel, $encoded = false ) {

		$post_meta = get_post_meta( $style_id, $panel, true );

		if ( $encoded ) {
			$post_meta = json_encode( $post_meta );
		}

		return $post_meta;
	}

	/**
	 * Function Name: cp_get_panel_hidden_fields.
	 * Function Description: Get panel hidden fields.
	 *
	 * @param int   $style_id style_id.
	 * @param array $meta_data meta_data.
	 */
	public function cp_get_panel_hidden_fields( $style_id, $meta_data ) {

		$sections = array(
			'configure',
		);

		foreach ( $sections as $key => $section ) {
			$panel_settings = $this->get_panel_settings( $style_id, $section, true );
			$panel_rulesets = json_encode( array() );

			$temp_panel_settings = json_decode( $panel_settings, true );

			if ( is_array( $temp_panel_settings ) ) {

				if ( isset( $temp_panel_settings['rulesets'] ) ) {
					$panel_rulesets = $temp_panel_settings['rulesets'];
					unset( $temp_panel_settings['rulesets'] );
				}

				unset( $temp_panel_settings['target_rule_display'] );
				unset( $temp_panel_settings['target_rule_display_on'] );
				unset( $temp_panel_settings['target_rule_exclude'] );
				unset( $temp_panel_settings['target_rule_exclude_on'] );

				$panel_settings = json_encode( $temp_panel_settings );
			}

			echo "<input type='hidden' class='panel-settings' data-style_id= '" . $style_id . "' data-section='" . $section . "' value='" . $panel_settings . "' >";
			echo "<input type='hidden' class='panel-rulesets' data-style_id= '" . $style_id . "' data-section='" . $section . "' value='" . $panel_rulesets . "' >";
		}

		$cp_settings          = get_option( 'convert_plug_debug' );
		$after_content_scroll = isset( $cp_settings['after_content_scroll'] ) ? $cp_settings['after_content_scroll'] : '50';
		echo "<input type='hidden'  id='cp_after_content_scroll' value='" . $after_content_scroll . "' >";

		$infobar_position_settings = array(
			'push_page_down',
		);

		$infobar_settings = '';
		$module_type      = get_post_meta( $style_id, 'cp_module_type', true );
		$info_position    = array();
		if ( 'info_bar' == $module_type ) {

			if ( is_array( $meta_data ) && ! empty( $meta_data ) ) {
				foreach ( $meta_data as $key => $meta_value ) {

					if ( array_key_exists( 'panel_position', $meta_value ) ) {
						$panel_position = $meta_value['panel_position'];
					}

					if ( array_key_exists( 'push_page_down', $meta_value ) ) {
						$page_down = $meta_value['push_page_down'];
					}

					if ( array_key_exists( 'panel_toggle_infobar', $meta_value ) ) {
						$panel_infobar_toggle_enabled = $meta_value['panel_toggle_infobar'];
					}
				}
			}

			if ( 'top' == $panel_position && '1' == $page_down ) {
				echo "<input type='hidden' class='infobar-settings' data-panel='push-down' data-style_id= '" . $style_id . "'  value=" . $page_down . ' >';
			}

			if ( $panel_infobar_toggle_enabled ) {
				echo "<input type='hidden' class='infobar-toggle-settings' data-panel='info-bar-toggle' data-style_id= '" . $style_id . "'  value=" . $panel_infobar_toggle_enabled . ' >';
			}
		}
	}

	/**
	 * Function Name: get_sorted_styles.
	 * Function Description: Sort styles
	 *
	 * @param array $styles styles.
	 *
	 * @return array
	 */
	public function get_sorted_styles( $styles ) {

		$child_styles = array();

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $key => $style ) {

				$style_id = $style->ID;

				if ( class_exists( 'CP_V2_AB_Test' ) ) {
					$ab_test_inst       = new CP_V2_AB_Test();
					$has_active_ab_test = $ab_test_inst->has_active_ab_test( $style_id );
					$ab_test_status     = $has_active_ab_test['status'];

					if ( $ab_test_status && isset( $has_active_ab_test['is_parent'] ) ) {

						$is_parent_style = $has_active_ab_test['is_parent'];

						if ( ! $is_parent_style ) {

							$test_id         = $has_active_ab_test['test_id'];
							$cp_parent_style = get_term_meta( $test_id, 'cp_parent_style', true );

							$has_parent_exists = $this->is_parent_exists( $styles, $cp_parent_style );
							if ( $has_parent_exists ) {
								unset( $styles[ $key ] );
							}

							if ( isset( $child_styles[ $cp_parent_style ] ) ) {
								$child_styles[ $cp_parent_style ][] = $style;
							} else {
								$child_styles[ $cp_parent_style ] = array( $style );
							}
						}
					}
				}
			}
		}

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $key => $style ) {

				$style_id = $style->ID;

				// If style is among childs.
				if ( array_key_exists( $style_id, $child_styles ) ) {

					// Insert A/B test child style just after parent.
					$child_styles_arr = $child_styles[ $style_id ];

					foreach ( $child_styles_arr as $child_style ) {

						$styles = $this->array_insert_after( $styles, $key, array( $child_style ) );
					}
					unset( $child_styles[ $style_id ] );
				}
			}
		}

		return $styles;

	}

	/**
	 * This function will check if A/B Test parent style exists in the array
	 *
	 * @param array $styles parameter.
	 * @param int   $parent_id parameter.
	 *
	 * @return bool
	 */
	public function is_parent_exists( $styles, $parent_id ) {

		$has_parent = false;
		foreach ( $styles as $style ) {

			$style_id = $style->ID;

			if ( $style_id == $parent_id ) {
				$has_parent = true;
			}
		}

		return $has_parent;
	}

	/**
	 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
	 * to the end of the array.
	 *
	 * @param array  $array parameter.
	 * @param string $key parameter.
	 * @param array  $new parameter.
	 *
	 * @return array
	 */
	function array_insert_after( array $array, $key, array $new ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys );
		$pos   = false === $index ? count( $array ) : $index + 1;
		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}
}
