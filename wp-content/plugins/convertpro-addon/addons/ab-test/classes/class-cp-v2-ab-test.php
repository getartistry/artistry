<?php
/**
 * Convert Pro Addon A/B Test Class file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CP_V2_AB_Test' ) ) {

	/**
	 * ConvertPlug AB Test Class file.
	 *
	 * @since 1.0.0
	 */
	final class CP_V2_AB_Test {

		/**
		 * Current class object.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var array $instance
		 */
		private static $instance;

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
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			add_action( 'wp_ajax_cp_update_ab_test', array( $this, 'cp_edit_ab_test' ) );
			add_action( 'wp_ajax_cp_create_ab_test', array( $this, 'cp_create_ab_test' ) );
			add_action( 'wp_ajax_cpro_update_configuration', array( $this, 'cp_update_configuration' ) );
			add_action( 'wp_ajax_cp_update_ab_test_status', array( $this, 'update_ab_test_status' ) );
			add_action( 'wp_ajax_cp_get_remaining_popups', array( $this, 'cp_get_remaining_popups' ) );
			add_action( 'wp_ajax_cp_del_ab_test', array( $this, 'del_ab_test' ) );
			add_action( 'wp_ajax_cp_get_ab_ga_data', array( $this, 'cp_get_ab_ga_data' ) );
		}

		/**
		 * Update Configuration of child styles with parent configuration
		 *
		 * @since 1.0.0
		 */
		public function cp_update_configuration() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$parent_id = isset( $_POST['parent_id'] ) ? esc_attr( $_POST['parent_id'] ) : '';

			// Check if post has ab test running.
			if ( has_term( '', CP_AB_TEST_TAXONOMY, $parent_id ) ) {

				// Get all ab test for this style.
				$tests        = wp_get_object_terms( array( $parent_id ), CP_AB_TEST_TAXONOMY );
				$cp_popup_obj = new CP_V2_Popups();

				foreach ( $tests as $test ) {
					$configure = get_post_meta( (int) $parent_id, 'configure', true );
					$test_id   = $test->term_id;
					$status    = get_term_meta( $test_id, 'status', true );

					// If test is active.
					if ( '1' == $status ) {
						$children = $this->get_styles_by_test_id( $test_id );
						foreach ( $children as $child ) {
							if ( $child->ID != $parent_id ) {
								update_post_meta( $child->ID, 'configure', $configure );

								$module_type = get_post_meta( $child->ID, 'cp_module_type', true );
								$display     = '';

								if ( 'inline' == $module_type || 'widget' == $module_type ) {
									$display = 'inline';
								}

								$output = $cp_popup_obj->render( $child->ID, false, '1', $module_type, $display, '' );
								$output = str_replace( array( 'http:', 'https:' ), '', $output );

								$output_formattted = htmlspecialchars( $output );

								update_post_meta( $child->ID, 'html_data', $output_formattted );
							}
						}
					}
				}
			}
		}

		/**
		 * Get not used designs.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function cp_get_remaining_popups() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$test_id = isset( $_POST['test_id'] ) ? esc_attr( $_POST['test_id'] ) : '';

			$return_array = $this->get_filtered_popups( $test_id );
			$ret          = array();

			if ( ! empty( $return_array ) ) {
				foreach ( $return_array as $i => $p ) {
					$ret[ $p->ID ] = $p->post_title;
				}
			}
			echo json_encode( $ret );
			die();
		}

		/**
		 * Get Filtered designs.
		 *
		 * @since 1.0.0
		 * @param string $test_id A/B Test ID.
		 * @return array An array designs.
		 */
		public function get_filtered_popups( $test_id = '' ) {

			$return_array     = array();
			$c_arr            = array();
			$completed_styles = array();

			$ab_styles = new WP_Query(
				array(
					'post_type'   => CP_CUSTOM_POST_TYPE,
					'numberposts' => -1,
					'tax_query'   => array(
						array(
							'taxonomy' => CP_AB_TEST_TAXONOMY,
							'operator' => 'NOT EXISTS',
						),
					),
					'meta_query'  => array(
						'relation' => 'OR',
						array(
							'key'     => 'cp_module_type',
							'value'   => 'modal_popup',
							'compare' => 'LIKE',
						),
						array(
							'key'     => 'cp_module_type',
							'value'   => 'info_bar',
							'compare' => 'LIKE',
						),
						array(
							'key'     => 'cp_module_type',
							'value'   => 'slide_in',
							'compare' => 'LIKE',
						),
					),
				)
			);

			$return_array = $ab_styles->posts;

			$completed_test = $this->get_all_tests( array( 2 ) );

			if ( ! empty( $completed_test ) ) {
				foreach ( $completed_test as $key => $value ) {
					$c_arr[] = $value->term_id;
				}

				$completed_styles = get_posts(
					array(
						'post_type'   => CP_CUSTOM_POST_TYPE,
						'numberposts' => -1,
						'tax_query'   => array(
							array(
								'taxonomy'         => CP_AB_TEST_TAXONOMY,
								'field'            => 'id',
								// Where term_id of Term 1 is "1".
								'terms'            => $c_arr,
								'include_children' => false,
							),
						),
					)
				);

				foreach ( $completed_styles as $key => $value ) {
					array_push( $return_array, $value );
				}
			}

			if ( '' != $test_id ) {

				$curr_test = $this->get_styles_by_test_id( $test_id );

				foreach ( $curr_test as $i => $val ) {
					array_push( $return_array, $val );
				}
			}

			return $return_array;
		}

		/**
		 * Get all A/B Tests.
		 *
		 * @since 1.0.0
		 * @param string $status A/B Test Status.
		 * @return array An array designs.
		 */
		public function get_all_tests( $status ) {

			$tests = get_terms(
				array(
					'taxonomy'   => CP_AB_TEST_TAXONOMY,
					'hide_empty' => false,
					'meta_key'   => 'status',
					'meta_value' => $status,
				)
			);

			return $tests;
		}

		/**
		 * Create an A/B Test.
		 *
		 * @since 1.0.0
		 * @param string $test_name A/B Test name.
		 * @return int A A/B Test ID.
		 */
		public function create( $test_name ) {

			$taxonomy = CP_AB_TEST_TAXONOMY;

			// Check if ab test already exists.
			$is_term_exists = term_exists( $test_name, $taxonomy );

			if ( ! $is_term_exists || null == $is_term_exists ) {

				// Create a new ab test as a term.
				$result  = wp_insert_term( $test_name, $taxonomy );
				$test_id = $result['term_id'];

				// Set status to live.
				update_term_meta( $test_id, 'status', 1 );

			} else {
				$test_id = $is_term_exists->term_id;
			}

			return $test_id;
		}

		/**
		 * Get all designs of a particular A/B Test.
		 *
		 * @since 1.0.0
		 * @param string $test_id A/B Test ID.
		 * @return array Design array.
		 */
		public function get_styles_by_test_id( $test_id ) {

			$ab_styles = get_posts(
				array(
					'post_type'   => CP_CUSTOM_POST_TYPE,
					'numberposts' => -1,
					'tax_query'   => array(
						array(
							'taxonomy'         => CP_AB_TEST_TAXONOMY,
							'field'            => 'id',
							'terms'            => $test_id, // Where term_id of Term 1 is "1".
							'include_children' => false,
						),
					),
				)
			);

			return $ab_styles;
		}

		/**
		 * Check if the given style is active in any A/B Test.
		 *
		 * @since 1.0.0
		 * @param string $style_id Design ID.
		 * @return boolean
		 */
		public function has_active_ab_test( $style_id ) {

			$data = array(
				'status'           => false,
				'completed_status' => false,
			);

			// Check if post has ab test running.
			if ( has_term( '', CP_AB_TEST_TAXONOMY, $style_id ) ) {

				// Get all ab test for this style.
				$tests = wp_get_object_terms( array( $style_id ), CP_AB_TEST_TAXONOMY );

				foreach ( $tests as $test ) {
					$test_id         = $test->term_id;
					$status          = get_term_meta( $test_id, 'status', true );
					$cp_parent_style = get_term_meta( $test_id, 'cp_parent_style', true );
					$is_parent       = ( $style_id == $cp_parent_style ) ? true : false;
					// If test is active.
					if ( '1' == $status ) {
						$data = array(
							'status'    => true,
							'test_id'   => $test_id,
							'is_parent' => $is_parent,
							'test_name' => $test->name,
						);
					}

					if ( '2' == $status || '0' == $status ) {
						$data['completed_status'] = true;
					}
				}
			}

			return $data;
		}

		/**
		 * Check if the given A/B Test is running.
		 *
		 * @since 1.0.0
		 * @param string $style_id Design ID.
		 * @return boolean
		 */
		public function has_abtest_running( $style_id ) {

			$data = array(
				'status' => false,
			);

			// Check if post has ab test running.
			if ( has_term( '', CP_AB_TEST_TAXONOMY, $style_id ) ) {

				// Get all ab test for this style.
				$tests = wp_get_object_terms( array( $style_id ), CP_AB_TEST_TAXONOMY );

				foreach ( $tests as $test ) {

					$test_id         = $test->term_id;
					$status          = get_term_meta( $test_id, 'status', true );
					$cp_parent_style = get_term_meta( $test_id, 'cp_parent_style', true );

					// If test is active.
					if ( '1' == $status ) {
						$data = array(
							'status'          => true,
							'cp_parent_style' => $cp_parent_style,
						);
					}
				}
			}

			return $data;
		}

		/**
		 * Update configuration settings of child designs in A/B test.
		 *
		 * @since 1.0.0
		 * @param string $style_id Design ID.
		 * @return void
		 */
		public function update_child_configuration( $style_id ) {

			$style_id = (int) $style_id;

			if ( has_term( '', CP_AB_TEST_TAXONOMY, $style_id ) ) {

				$configure = get_post_meta( $style_id, 'configure', true );

				$tests = wp_get_object_terms( array( $style_id ), CP_AB_TEST_TAXONOMY );

				foreach ( $tests as $test ) {
					$test_id = $test->term_id;

					$status = get_term_meta( $test_id, 'status', true );

					// If test is active.
					if ( '1' == $status ) {
						$args  = array(
							'post_type' => CP_CUSTOM_POST_TYPE,
							'tax_query' => array(
								array(
									'taxonomy' => CP_AB_TEST_TAXONOMY,
									'field'    => 'id',
									'terms'    => $test_id,
								),
							),
						);
						$query = new WP_Query( $args );
						if ( $query->post_count > 0 ) {
							foreach ( $query->posts as $key => $p ) {
								if ( $p->ID != $style_id ) {
									update_post_meta( $p->ID, 'configure', $configure );
								}
							}
						}
					}
				}
			}

		}

		/**
		 * Create A/B test.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function cp_create_ab_test() {

			$html = '';

			check_ajax_referer( 'cp-save-ab-test-nonce', 'security' );

			if ( ! current_user_can( 'access_cp_pro' ) ) {

				$message = __( 'You do not have permission to perform this action.', 'convertpro-addon' );

				$data = array(
					'success' => true,
					'message' => $message,
				);

				wp_send_json_error();
			}

			// Good idea to make sure things are set before using them.
			$styles = isset( $_POST['styles'] ) ? (array) $_POST['styles'] : array();

			// Sanitize all values from array.
			$styles = array_map( 'esc_attr', $styles );

			$test            = isset( $_POST['test_name'] ) ? esc_attr( $_POST['test_name'] ) : '';
			$cp_parent_style = isset( $_POST['cp_parent_style'] ) ? esc_attr( $_POST['cp_parent_style'] ) : '';

			$cp_winner_check = isset( $_POST['cp_winner_check'] ) ? esc_attr( $_POST['cp_winner_check'] ) : '';

			if ( '' !== $test ) {

				// Check if ab test already exists.
				$is_term_exists = term_exists( $test, CP_AB_TEST_TAXONOMY );

				if ( ! $is_term_exists || null == $is_term_exists ) {

					$ab_test_inst = CP_V2_AB_Test::get_instance();
					$test_id      = $ab_test_inst->create( $test );

					// Add date for ab test.
					$start_date = esc_attr( $_POST['start_date'] );
					$end_date   = esc_attr( $_POST['end_date'] );

					update_term_meta( $test_id, 'start_date', $start_date );
					update_term_meta( $test_id, 'end_date', $end_date );
					update_term_meta( $test_id, 'cp_parent_style', $cp_parent_style );
					update_term_meta( $test_id, 'cp_winner_check', $cp_winner_check );

					$configure = get_post_meta( $cp_parent_style, 'configure', true );
					update_post_meta( $cp_parent_style, 'live', 1 );

					$styles_data = array();

					// Attach ab test to all selected styles.
					foreach ( $styles as $style ) {
						wp_set_object_terms( $style, $test_id, CP_AB_TEST_TAXONOMY, true );
						update_post_meta( $style, 'has_active_ab_test', 1 );
						$style_id                 = $style;
						$styles_data[ $style_id ] = get_the_title( $style );

						update_post_meta( (int) $style, 'configure', $configure );
						update_post_meta( (int) $style, 'live', 1 );
					}

					$styles_data = json_encode( $styles_data );

					$message = __( 'A/B Test created successfully!', 'convertpro-addon' );
					$success = true;
					$html    = CPRO_ABTest_Helper::cp_get_ab_test_row( get_term( $test_id ), $styles_data );
				} else {
					$message = __( 'Oops! You already have an A/B Test with this name. Please try with a new name.', 'convertpro-addon' );
					$success = false;
					$html    = '';
				}

				$data = array(
					'success'     => $success,
					'message'     => $message,
					'styles'      => $styles,
					'html'        => $html,
					'header_html' => CPRO_ABTest_Helper::cp_get_ab_test_row_header(),
				);

				wp_send_json_success( $data );
			}
		}

		/**
		 * Get A/B test.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function cp_get_ab_ga_data() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$ga_inst = new CP_V2_GA();
			$test_id = isset( $_POST['test_id'] ) ? esc_attr( $_POST['test_id'] ) : '';

			if ( '' == $test_id ) {
				wp_send_json_error();
			}

			$data           = get_option( 'cp_ga_analytics_data' );
			$analytics_data = array();

			$details = get_term_by( 'slug', $test_id, CP_AB_TEST_TAXONOMY );

			if ( ! is_wp_error( $details ) ) {
				$term_id = $details->term_id;
				$sdate   = get_term_meta( $term_id, 'start_date', true );
				$edate   = get_term_meta( $term_id, 'end_date', true );

				$ab_test_inst = CP_V2_AB_Test::get_instance();

				$ab_styles = $ab_test_inst->get_styles_by_test_id( $term_id );

				$sdate = str_replace( '/', '-', $sdate );
				$edate = str_replace( '/', '-', $edate );

				$end_date       = strtotime( date( 'Y-m-d', strtotime( $edate ) ) );
				$start_date_val = strtotime( date( 'Y-m-d', strtotime( $sdate ) ) );

				$current_date = $start_date_val;
				while ( $current_date <= $end_date ) {

					$defaults               = array();
					$analytics_data['cols'] = array();
					$date_key               = date( 'Y-m-d', $current_date );
					$defaults[]             = $date_key;
					foreach ( $ab_styles as $style ) {
						$style_slug               = $style->post_name;
						$analytics_data['cols'][] = ( isset( $style->post_title ) ) ? $style->post_title : $style_slug;
						if ( strtotime( $date_key ) == $current_date ) {
							$defaults[] = ( isset( $data[ $style_slug ][ $date_key ]['conversions'] ) ) ? $data[ $style_slug ][ $date_key ]['conversions'] : 0;
						} else {
							$defaults[] = 0;
						}
					}

					$analytics_data['rows'][] = $defaults;
					$current_date             = ( $current_date + ( 86400 ) );
				}
			}
			$json_table = json_encode( $analytics_data );
			echo $json_table;
			die();
		}

		/**
		 * Update A/B test.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function cp_edit_ab_test() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			check_ajax_referer( 'cp-save-ab-test-nonce', 'security' );

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$test_name  = isset( $_POST['test_name'] ) ? esc_attr( $_POST['test_name'] ) : '';
			$test_id    = isset( $_POST['test_id'] ) ? esc_attr( (int) $_POST['test_id'] ) : '';
			$start_date = isset( $_POST['start_date'] ) ? esc_attr( $_POST['start_date'] ) : '';
			$end_date   = isset( $_POST['end_date'] ) ? esc_attr( $_POST['end_date'] ) : '';

			$cp_parent_style = isset( $_POST['cp_parent_style'] ) ? esc_attr( $_POST['cp_parent_style'] ) : '';

			$cp_winner_check = isset( $_POST['cp_winner_check'] ) ? esc_attr( $_POST['cp_winner_check'] ) : '';

			$styles = isset( $_POST['styles'] ) ? $_POST['styles'] : array();

			$configure = get_post_meta( $cp_parent_style, 'configure', true );

			// Update test name.
			if ( '' !== $test_name ) {
				wp_update_term(
					$test_id, CP_AB_TEST_TAXONOMY, array(
						'name' => $test_name,
						'slug' => sanitize_title( $test_name ),
					)
				);
			}

			if ( '' !== $start_date ) {
				update_term_meta( (int) $test_id, 'start_date', $start_date );
			}

			if ( '' !== $end_date ) {
				update_term_meta( (int) $test_id, 'end_date', $end_date );
			}

			if ( '' !== $cp_parent_style ) {
				update_term_meta( (int) $test_id, 'cp_parent_style', $cp_parent_style );
			}

			update_term_meta( (int) $test_id, 'cp_winner_check', $cp_winner_check );

			$existing_attached_styles = $this->get_styles_by_test_id( $test_id );

			foreach ( $existing_attached_styles as $style ) {
				// Remove test attached to existing styles.
				wp_remove_object_terms( $style->ID, (int) $test_id, CP_AB_TEST_TAXONOMY );
				update_post_meta( $style->ID, 'has_active_ab_test', 0 );
				update_post_meta( $style->ID, 'live', 0 );
			}
			// Attach ab test to all selected styles.
			$styles_data = array();
			foreach ( $styles as $style ) {
				wp_set_object_terms( (int) $style, (int) $test_id, CP_AB_TEST_TAXONOMY, true );
				$styles_data[ $style ] = get_the_title( (int) $style );

				update_post_meta( (int) $style, 'configure', $configure );
				update_post_meta( (int) $style, 'has_active_ab_test', 1 );
				update_post_meta( (int) $style, 'live', 1 );
			}
			$test = get_term_by( 'name', $test_name, CP_AB_TEST_TAXONOMY );
			$data = array(
				'success' => true,
				'message' => __( 'A/B test updated successfully!', 'convertpro-addon' ),
				'html'    => CPRO_ABTest_Helper::cp_get_ab_test_row( $test, $styles_data ),
			);

			wp_send_json_success( $data );
		}

		/**
		 * Update A/B test status.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function update_ab_test_status() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$test_id = isset( $_POST['test_id'] ) ? esc_attr( (int) $_POST['test_id'] ) : '';
			$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : '';

			if ( '' !== $test_id ) {
				$result = update_term_meta( $test_id, 'status', $status );

				$styles = $this->get_styles_by_test_id( $test_id );

				// Set active test meta key to false for styles.
				foreach ( $styles as $style ) {
					if ( '1' != $status ) {
						// Remove test attached to existing styles.
						update_post_meta( $style->ID, 'has_active_ab_test', 0 );
						update_post_meta( $style->ID, 'live', 0 );
					} else {
						// Remove test attached to existing styles.
						update_post_meta( $style->ID, 'has_active_ab_test', 1 );
						update_post_meta( $style->ID, 'live', 1 );
					}
				}

				if ( $result ) {
					wp_send_json_success();
				} else {
					wp_send_json_error();
				}
			}

			wp_send_json_error();
		}

		/**
		 * Delete A/B test.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function del_ab_test() {

			check_ajax_referer( 'cp-delete-test-nonce', 'security' );

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				wp_send_json_error();
			}

			$test_id = isset( $_POST['test'] ) ? esc_attr( $_POST['test'] ) : '';

			if ( '' !== $test_id ) {
				$deleted_test = wp_delete_term( (int) $test_id, CP_AB_TEST_TAXONOMY );

				if ( $deleted_test ) {

					$styles = $this->get_styles_by_test_id( $test_id );

					// Set active test meta key to false for styles.
					foreach ( $styles as $style ) {

						// Remove test attached to existing styles.
						update_post_meta( $style->ID, 'has_active_ab_test', 0 );
						update_post_meta( $style->ID, 'live', 0 );
					}

					$data = array(
						'success' => true,
						'test_id' => $test_id,
					);

					wp_send_json_success( $data );
				}
			} else {
				wp_send_json_error();
			}

		}

		/**
		 * Expire Inactive A/B Test via CRON job.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function inactive_expired_tests() {

			$active_tests = $this->get_all_tests( array( 0, 1 ) );

			if ( ! is_wp_error( $active_tests ) ) {

				foreach ( $active_tests as $test ) {

					$winner_array    = array();
					$test_id         = intval( $test->term_id );
					$end_date        = get_term_meta( $test_id, 'end_date', true );
					$cp_winner_check = get_term_meta( $test_id, 'cp_winner_check', true );
					$ga_data         = get_option( 'cp_ga_analytics_data' );
					$ids             = $this->get_styles_by_test_id( $test_id );
					$winner_id       = -1;

					if ( 'on' == $cp_winner_check ) {

						if ( ! empty( $ids ) && false != $ga_data ) {
							foreach ( $ids as $key => $p ) {

								foreach ( $ga_data as $key => $value ) {
									if ( $key == $p->post_name ) {
										$conv = 0;
										foreach ( $value as $i => $conversions ) {
											$conv = $conv + $conversions['conversions'];
										}
										$winner_array[ $p->ID ] = $conv;
									}
								}
							}
						}

						$winner = ( ! empty( $winner_array ) ) ? array_keys( $winner_array, max( $winner_array ) ) : array();
						if ( ! empty( $winner ) ) {
							$winner_id = $winner[0];
						}
					}

					if ( ! empty( $end_date ) ) {
						$formatted_end_date = date( 'Y-m-d', strtotime( str_replace( '/', '-', $end_date ) ) );
						$current_date       = date( 'Y-m-d' );

						if ( strtotime( $current_date ) > strtotime( $formatted_end_date ) ) {
							foreach ( $ids as $key => $p ) {
								if ( $p->ID != $winner_id ) {
									update_post_meta( $winner_id, 'live', 0 );
								}
							}

							if ( -1 != $winner_id ) {
								update_post_meta( $winner_id, 'live', 1 );
							}

							// Set status to 2 i.e. expired.
							update_term_meta( $test_id, 'status', 2 );
						}
					}
				}
			}
		}

		/**
		 * Returns all Modal Popups / Slide Ins / Info Bars.
		 *
		 * @since 1.0.0
		 * @return array Design array.
		 */
		public function get_launch_styles() {
			$launch_styles = new WP_Query(
				array(
					'post_type'   => CP_CUSTOM_POST_TYPE,
					'numberposts' => -1,
					'meta_query'  => array(
						'relation' => 'OR',
						array(
							'key'     => 'cp_module_type',
							'value'   => 'modal_popup',
							'compare' => 'LIKE',
						),
						array(
							'key'     => 'cp_module_type',
							'value'   => 'info_bar',
							'compare' => 'LIKE',
						),
						array(
							'key'     => 'cp_module_type',
							'value'   => 'slide_in',
							'compare' => 'LIKE',
						),
					),
				)
			);

			return $launch_styles->posts;
		}
	}
	CP_V2_AB_Test::get_instance();
}
