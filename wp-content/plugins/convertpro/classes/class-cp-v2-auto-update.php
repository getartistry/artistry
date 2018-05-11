<?php
/**
 * Theme/Plugin auto version update & backward compatibility.
 *
 * @package     ConvertPro
 * @author      Brainstormforce
 * @link        http://convertplug.com/plus
 * @since       ConvertPro 1.0.0
 */

// Set current version.
define( 'CP_V2_VERSION', '1.1.6' );

if ( ! class_exists( 'CP_V2_Auto_Update' ) ) :

	/**
	 * CP_V2_Auto_Update initial setup
	 *
	 * @since 1.0.0
	 */
	class CP_V2_Auto_Update {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Theme Updates.
			add_action( 'init', __CLASS__ . '::init' );

		}

		/**
		 * Implement plugin auto update logic.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function init() {

			do_action( 'cp_pro_before_update' );

			// Get auto saved version number.
			$saved_version = get_option( 'cp-pro-auto-version' );

			// If equals then return.
			if ( version_compare( $saved_version, CP_V2_VERSION, '=' ) ) {
				return;
			}

			// Update to older version than 1.0.2 version.
			if ( version_compare( $saved_version, '1.0.2', '<' ) ) {
				self::update_configuration_meta();
			}

			// Update to older version than 1.0.0-rc.11 version.
			if ( version_compare( $saved_version, '1.0.0-rc.11', '<' ) ) {
				self::update_form_field_data();
			}

			// Update to older version than 1.0.2 version.
			if ( version_compare( $saved_version, '1.0.3', '<' ) ) {
				self::update_modal_http_data();
			}

			// Update to older version than 1.1.0 version.
			if ( version_compare( $saved_version, '1.1.0', '<' ) ) {
				self::update_ruleset_data();

				// Removed local templates.
				delete_site_option( '_cp_v2_template_styles' );
				delete_site_option( '_cp_v2_cloud_templates' );
				delete_site_option( '_cp_v2_template_categories' );
			}

			if ( version_compare( $saved_version, '1.1.3.1', '<' ) ) {
				self::refresh_html();
			}

			if ( version_compare( $saved_version, '1.1.4', '<' ) ) {
				self::refresh_html();
			}

			// Update auto saved version number.
			update_option( 'cp-pro-auto-version', CP_V2_VERSION );

			do_action( 'cp_pro_after_update' );

		}

		/**
		 * Function to get all design list
		 *
		 * @since 1.0.0-rc.10
		 */
		public static function get_designs() {

			$query_args = array(
				'post_type'      => CP_CUSTOM_POST_TYPE,
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			);

			$popups  = new WP_Query( $query_args );
			$designs = $popups->posts;
			wp_reset_postdata();

			return $designs;
		}

		/**
		 * Function to refresh html for styles if plugin version older than 1.0.0-rc.9
		 *
		 * @since 1.0.0-rc.9
		 */
		public static function refresh_html() {

			$designs      = self::get_designs();
			$cp_popup_obj = new CP_V2_Popups();

			if ( is_array( $designs ) && ! empty( $designs ) ) {

				foreach ( $designs as $design ) {

					$module_type = get_post_meta( $design, 'cp_module_type', true );
					$display     = '';

					if ( 'inline' == $module_type || 'widget' == $module_type ) {
						$display = 'inline';
					}

					$output = $cp_popup_obj->render( $design, false, '1', $module_type, $display, '' );
					$output = str_replace( array( 'http:', 'https:' ), '', $output );

					$output_formattted = htmlspecialchars( $output );

					update_post_meta( $design, 'html_data', $output_formattted );
				}
			}
		}

		/**
		 * Function to set placeholder key for existing form field data
		 *
		 * @since 1.0.0-rc.11
		 */
		public static function update_form_field_data() {

			$designs = self::get_designs();

			if ( is_array( $designs ) && ! empty( $designs ) ) {

				foreach ( $designs as $design ) {

					$modal_data         = get_post_meta( $design, 'cp_modal_data', true );
					$decoded_modal_data = json_decode( $modal_data );

					if ( ! empty( $decoded_modal_data ) ) {
						foreach ( $decoded_modal_data as $key => $value ) {

							if ( 'common' == $key ) {
								continue;
							}

							foreach ( $value as $nested_key => $nested_value ) {

								// Form field data.
								if ( false !== strpos( $nested_key, 'form_field' ) ) {

									$text_color = $nested_value->form_field_color;

									// Set placeholder color same as text color.
									$decoded                               = $decoded_modal_data->$key->$nested_key;
									$decoded->form_field_placeholder_color = $text_color;

									$old_map_style      = $nested_value->map_style;
									$index_count        = count( (array) $old_map_style );
									$new_index          = $index_count + 1;
									$map_style_property = (object) array(
										'name'      => 'form_field_placeholder_color',
										'parameter' => 'color',
										'unit'      => '',
										'onhover'   => false,
										'target'    => 'placeholder',
									);

									$decoded_index             = $decoded_modal_data->$key->$nested_key->map_style;
									$decoded_index->$new_index = $map_style_property;
								}
							}
						}
					}

					// Ignore the PHPCS warning about JSON_UNESCAPED_UNICODE parameter usage.
					// @codingStandardsIgnoreStart
					$modal_data = json_encode( $decoded_modal_data, JSON_UNESCAPED_UNICODE );
					// @codingStandardsIgnoreEnd

					// Update modal data.
					update_post_meta( $design, 'cp_modal_data', $modal_data );
				}
			}

			self::refresh_html();
		}

		/**
		 * Rulset data updation
		 *
		 * @since 1.0.0-rc.12
		 */
		public static function update_ruleset_data() {
			$query_args = array(
				'post_type'      => 'cp_popups',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			);

			$popups = new WP_Query( $query_args );

			wp_reset_postdata();

			$custom_post_type = $popups->posts;

			$rulesets_enabled = array(
				'autoload_on_duration',
				'modal_exit_intent',
				'autoload_on_scroll',
				'inactivity',
				'enable_after_post',
				'enable_custom_scroll',
				'enable_custom_class',
			);

			$rulset_fields = array(
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
				'enable_scheduler',
				'start_date',
				'end_date',
			);

			$rulset_rule_defaults = array(
				'name'                      => 'Ruleset 1',
				'autoload_on_duration'      => '0',
				'load_on_duration'          => '1',
				'modal_exit_intent'         => '0',
				'autoload_on_scroll'        => '0',
				'load_after_scroll'         => '75',
				'inactivity'                => '0',
				'inactivity_link'           => '',
				'enable_after_post'         => '0',
				'enable_custom_scroll'      => '0',
				'enable_scroll_class'       => '',
				'on_scroll_txt'             => '',

				'all_visitor_info'          => '',
				'enable_visitors'           => '',
				'visitor_type'              => 'first-time',
				'enable_referrer'           => '',
				'referrer_type'             => 'hide-from',
				'display_to'                => '',
				'hide_from'                 => '',

				'enable_scheduler'          => '0',
				'enable_scheduler_txt'      => '',
				'disabled_scheduler_txt'    => '',
				'start_date'                => '',
				'end_date'                  => '',
				'custom_cls_text_head'      => '',
				'enable_custom_class'       => '0',
				'copy_link_code_button'     => 'Copy Link Code',
				'copy_link_cls_code_button' => '',
				'custom_class'              => '',
				'custom_cls_text'           => '',
			);

			foreach ( $popups->posts as $post_data ) {

				$configure_rulsets = array();
				$configure_data    = get_post_meta( $post_data->ID, 'configure', true );

				// Back up configure data.
				update_post_meta( $post_data->ID, 'bckp_configure', $configure_data );

				if ( isset( $configure_data[0] ) && is_array( $configure_data[0] ) ) {

					$configure_data = $configure_data[0];

					$configure_common_data = array(
						'display_on_first_load' => '1',
						'enable_referrer'       => '',
						'display_to'            => '',
						'hide_from'             => '',
						'enable_scheduler'      => '',
						'start_date'            => '',
						'end_date'              => '',
					);

					foreach ( $configure_common_data as $com_key => $com_value ) {

						if ( isset( $configure_data[ $com_key ] ) ) {

							$configure_common_data[ $com_key ] = $configure_data[ $com_key ];

							unset( $configure_data[ $com_key ] );
						}
					}

					$counter = 1;

					foreach ( $configure_data as $c_key => $c_value ) {

						if ( in_array( $c_key, $rulesets_enabled ) ) {

							$temp_ruleset = $rulset_rule_defaults;

							if ( $counter > 1 ) {
								$temp_ruleset['name'] = 'Ruleset ' . $counter;
							}

							foreach ( $configure_common_data as $com_key => $com_value ) {

								if ( 'display_on_first_load' == $com_key || 'enable_referrer' == $com_key ) {

									switch ( $com_key ) {
										case 'display_on_first_load':
											if ( '1' != $com_value ) {

												$temp_ruleset['enable_visitors'] = '1';
												$temp_ruleset['visitor_type']    = 'returning';
											}

											break;
										case 'enable_referrer':
											if ( '1' == $com_value ) {

												if ( isset( $configure_common_data['display_to'] ) && '' !== $configure_common_data['display_to'] ) {

													$temp_ruleset['enable_referrer'] = '1';
													$temp_ruleset['referrer_type']   = 'display-to';
												}
											} else {

												if ( isset( $configure_common_data['hide_from'] ) && '' !== $configure_common_data['hide_from'] ) {

													$temp_ruleset['enable_referrer'] = '1';
													$temp_ruleset['referrer_type']   = 'hide-from';
												}
											}

											break;
									}
								} else {
									$temp_ruleset[ $com_key ] = $com_value;
								}
							}

							switch ( $c_key ) {
								case 'autoload_on_duration':
									if ( '1' == $c_value ) {

										$temp_ruleset['autoload_on_duration'] = $configure_data['autoload_on_duration'];
										$temp_ruleset['load_on_duration']     = $configure_data['load_on_duration'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['autoload_on_duration'] );
									unset( $configure_data['load_on_duration'] );

									break;
								case 'modal_exit_intent':
									if ( '1' == $c_value ) {

										$temp_ruleset['modal_exit_intent'] = $configure_data['modal_exit_intent'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['modal_exit_intent'] );

									break;
								case 'autoload_on_scroll':
									if ( '1' == $c_value ) {

										$temp_ruleset['autoload_on_scroll'] = $configure_data['autoload_on_scroll'];
										$temp_ruleset['load_after_scroll']  = $configure_data['load_after_scroll'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['autoload_on_scroll'] );
									unset( $configure_data['load_after_scroll'] );

									break;
								case 'inactivity':
									if ( '1' == $c_value ) {

										$temp_ruleset['inactivity'] = $configure_data['inactivity'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['inactivity'] );

									break;
								case 'enable_after_post':
									if ( '1' == $c_value ) {

										$temp_ruleset['enable_after_post'] = $configure_data['enable_after_post'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['enable_after_post'] );

									break;
								case 'enable_custom_scroll':
									if ( '1' == $c_value ) {

										$temp_ruleset['enable_custom_scroll'] = $configure_data['enable_custom_scroll'];
										$temp_ruleset['enable_scroll_class']  = $configure_data['enable_scroll_class'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['enable_custom_scroll'] );
									unset( $configure_data['enable_scroll_class'] );

									break;
								case 'enable_custom_class':
									if ( '1' == $c_value ) {

										$temp_ruleset['enable_custom_class'] = $configure_data['enable_custom_class'];
										$temp_ruleset['custom_class']        = $configure_data['custom_class'];

										$configure_rulsets[] = $temp_ruleset;
										$counter++;
									}

									unset( $configure_data['enable_custom_class'] );
									unset( $configure_data['custom_class'] );

									break;
							}
						}
					}

					if ( count( $configure_rulsets ) < 1 ) {
						$configure_rulsets = $rulset_rule_defaults;
					}

					$configure_data['rulesets'] = json_encode( $configure_rulsets );

					update_post_meta( $post_data->ID, 'configure', $configure_data );
				}
			}

			/* Update HTML */
			self::refresh_html();
		}

		/**
		 * Function to update configuration data
		 *
		 * @since 1.0.2
		 */
		public static function update_configuration_meta() {

			$designs = self::get_designs();

			if ( ! empty( $designs ) ) {

				foreach ( $designs as $design ) {

					$configuration_meta = get_post_meta( $design, 'configure', true );

					if ( ! empty( $configuration_meta ) ) {
						foreach ( $configuration_meta as $config_key => $meta ) {

							if ( isset( $meta['target_rule_display'] ) ) {
								$target_rules = $meta['target_rule_display'];

								$decoded_data = json_decode( $target_rules );

								foreach ( $decoded_data as $decode_key => $data_val ) {

									if ( isset( $data_val->type ) && 'specifics' == $data_val->type ) {

										$specifics = $data_val->specific;

										foreach ( $specifics as $key => $specific ) {

											if ( false !== strpos( $specific, 'tax-' ) ) {

												$term_id = str_replace( 'tax-', '', $specific );

												$taxonomy = self::get_taxonomy_by_term_id( $term_id );

												$new_value = 'tax-' . $term_id . '-archive-' . $taxonomy;

												$specifics[ $key ] = $new_value;
											}
										}

										$decoded_data[ $decode_key ]->specific = $specifics;
									}
								}

								$new_target_rules = json_encode( $decoded_data );

								$meta['target_rule_display'] = $new_target_rules;
							}
						}

						$configuration_meta[ $config_key ] = $meta;

						update_post_meta( $design, 'configure', $configuration_meta );

					}
				}
			}

			self::refresh_html();
		}

		/**
		 * Function to update configuration data
		 *
		 * @param int $term_id Term ID.
		 *
		 * @since 1.0.2
		 */
		public static function get_taxonomy_by_term_id( $term_id ) {

			global $wpdb;

			$taxonomy = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %s", $term_id
				)
			);

			return $taxonomy;

		}

		/**
		 * Function to replace http/https from URLs
		 *
		 * @since 1.0.2
		 */
		public static function update_modal_http_data() {

			$designs = self::get_designs();

			if ( is_array( $designs ) && ! empty( $designs ) ) {

				foreach ( $designs as $design ) {

					$modal_data = get_post_meta( $design, 'cp_modal_data', true );

					$modal_data = str_replace( '{{http_url}}', 'http://', $modal_data );
					$modal_data = str_replace( '{{https_url}}', 'https://', $modal_data );

					// Update modal data.
					update_post_meta( $design, 'cp_modal_data', $modal_data );
				}
			}

			self::refresh_html();
		}
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
CP_V2_Auto_Update::get_instance();
