<?php
/**
 * Ajax actions.
 *
 * @package convertpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

add_action( 'wp_ajax_cp_update_style_settings', 'cp_update_style_settings' );
add_action( 'wp_ajax_cp_update_style_status', 'cp_update_style_status' );
add_action( 'wp_ajax_cpro_get_posts_by_query', 'cpro_get_posts_by_query' );
add_action( 'wp_ajax_cp_refresh_html', 'cp_refresh_html' );

/**
* Function to accept ajax call for updating style settings
 *
* @since 1.0
*/
if ( ! function_exists( 'cp_update_style_settings' ) ) {
	/**
	 * Function Name: cp_update_style_settings.
	 * Function Description: cp update style settings.
	 */
	function cp_update_style_settings() {

		check_ajax_referer( 'cp-save-ajax-req-nonce', 'security' );

		if ( ! current_user_can( 'edit_cp_popup' ) ) {
			$data = array(
				'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
			);
			wp_send_json_error( $data );
		}

		$post_action = 'new';
		$meta_data   = $_POST['settings'];

		$cp_mobile_responsive = esc_attr( $_POST['cp_mobile_responsive'] );
		$cp_mobile_generated  = esc_attr( $_POST['cp_mobile_generated'] );

		$style_name = isset( $_POST['style_name'] ) ? sanitize_text_field( $_POST['style_name'] ) : '';
		$style_id   = (int) $_POST['style_id'];

		$module_type = isset( $_POST['module_type'] ) ? sanitize_text_field( $_POST['module_type'] ) : 'modal_popup';

		if ( isset( $_POST['post_action'] ) && 'update' == $_POST['post_action'] ) {
			$post_action = 'update';
		}

		update_post_meta( $style_id, 'cp_mobile_responsive', $cp_mobile_responsive );
		update_post_meta( $style_id, 'cp_mobile_generated', $cp_mobile_generated );

		$cp_popup = CP_V2_Popups::get_instance();

		$post_id = $cp_popup->create( $style_id, $style_name, $meta_data, 'publish', $module_type );

		$template_category = $_POST['template_category'];
		$is_template_live  = esc_attr( $_POST['is_template_live'] );

		if ( 'false' != $template_category && is_array( $template_category ) ) {

			$remove_key = array_search( 'all', $template_category );

			if ( false !== $remove_key ) {
				unset( $template_category[ $remove_key ] );
			}

			update_post_meta( $style_id, 'popup_categories', $template_category );

			$term_taxonomy_ids = wp_set_object_terms( $post_id, $template_category, CP_POPUP_CATEGORY );
		}

		if ( 'false' != $is_template_live ) {
			update_post_meta( $style_id, 'is_template_live', $is_template_live );
		}

		$data = array(
			'message'        => 'Post created',
			'post_action'    => $post_action,
			'style_id'       => $post_id,
			'post_edit_link' => admin_url( 'post.php?post=' . $post_id . '&action=edit&type=' . $module_type . '&popup_title=' . $style_name ),
		);

		if ( class_exists( 'CP_V2_AB_Test' ) ) {
			$ab_test_inst = CP_V2_AB_Test::get_instance();
			$ab_test_inst->update_child_configuration( $post_id );
		}

		wp_send_json_success( $data );
		die();
	}
}

/**
* Function to create a campaign
 *
* @since 0.0.1
*/
if ( ! function_exists( 'cp_create_campaign' ) ) {

	/**
	 * Function Name: cp_create_campaign.
	 * Function Description: cp create campaign.
	 *
	 * @param string $cat_name string parameter.
	 */
	function cp_create_campaign( $cat_name ) {

		if ( ! current_user_can( 'manage_cp_popup_terms' ) ) {
			$data = array(
				'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
			);
			wp_send_json_error( $data );
		}

		$term = term_exists( $cat_name, CP_CAMPAIGN_TAXONOMY );

		if ( 0 !== $term && null !== $term ) {

			$data = array(
				'message' => __( 'You already have a campaign with the same name.', 'convertpro' ),
			);

			wp_send_json_error( $data );
		}

		$cat_slug = sanitize_title( $cat_name );

		$category_id = wp_insert_category(
			array(
				'cat_name'             => $cat_name,
				'category_description' => CPRO_BRANDING_NAME . ' Campaign',
				'category_nicename'    => $cat_slug,
				'taxonomy'             => CP_CAMPAIGN_TAXONOMY,
			)
		);

		return $category_id;

	}
}


/**
 * Function Name: cp_v2_get_taxonomy_by_id.
 * Function Description: cp v2 get taxonomy by id.
 *
 * @param int $tax_id int parameter.
 */
function cp_v2_get_taxonomy_by_id( $tax_id ) {

	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	$output     = 'objects';
	$operator   = 'and';
	$taxonomies = get_taxonomies( $args, $output, $operator );

	if ( is_array( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {

			$terms = get_terms(
				$taxonomy->name, array(
					'orderby'    => 'count',
					'hide_empty' => 0,
				)
			);

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( $tax_id == $term->term_id ) {
						return $term->name;
					}
				}
			}
		}
	}

	$args = array(
		'public'   => true,
		'_builtin' => true,
	);

	$taxonomies = get_taxonomies( $args, $output, $operator );

	if ( is_array( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {

			$terms = get_terms(
				$taxonomy->name, array(
					'orderby'    => 'count',
					'hide_empty' => 0,
				)
			);

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( $tax_id == $term->term_id ) {
						return $term->name;
					}
				}
			}
		}
	}

	return false;
}


if ( ! function_exists( 'cpro_get_posts_by_query' ) ) {

	/**
	 * Function Name: cpro_get_posts_by_query.
	 * Function Description: cp get posts by query.
	 */
	function cpro_get_posts_by_query() {

		if ( ! current_user_can( 'access_cp_pro' ) ) {
			$data = array(
				'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
			);
			wp_send_json_error( $data );
		}

		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$is_singular   = ( isset( $_POST['module_type'] ) && 'before_after' == $_POST['module_type'] ) ? true : false;
		$data          = array();
		$result        = array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output     = 'names';
		$operator   = 'and';
		$post_types = get_post_types( $args, $output, $operator );

		$post_types['Posts'] = 'post';
		if ( ! $is_singular ) {
			$post_types['Pages'] = 'page';
		}

		foreach ( $post_types as $key => $post_type ) {

			$data = array();

			// filter to search within post title only.
			add_filter( 'posts_search', 'cpro_search_only_titles', 10, 2 );

			$query = new WP_Query(
				array(
					's'              => $search_string,
					'post_type'      => $post_type,
					'posts_per_page' => -1,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$title  = get_the_title();
					$title .= ( 0 != $query->post->post_parent ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : '';
					$id     = get_the_id();
					$data[] = array(
						'id'   => 'post-' . $id,
						'text' => $title,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $key,
					'children' => $data,
				);
			}
		}

		$data = array();

		wp_reset_postdata();

		remove_filter( 'posts_search', 'cpro_search_only_titles', 10 );

		$args = array(
			'public' => true,
		);

		$output     = 'objects';
		$operator   = 'and';
		$taxonomies = get_taxonomies( $args, $output, $operator );

		foreach ( $taxonomies as $taxonomy ) {

			$terms = get_terms(
				$taxonomy->name, array(
					'orderby'    => 'count',
					'hide_empty' => 0,
					'name__like' => $search_string,
				)
			);

			$data = array();

			$label = ucwords( $taxonomy->label );

			if ( ! empty( $terms ) ) {

				foreach ( $terms as $term ) {

					$term_taxonomy_name = ucfirst( str_replace( '_', ' ', $taxonomy->name ) );

					$data[] = array(
						'id'   => 'tax-' . $term->term_id . '-single-' . $taxonomy->name,
						'text' => $term->name . ' (' . $term_taxonomy_name . ') - Single',
					);

					if ( ! $is_singular ) {

						$data[] = array(
							'id'   => 'tax-' . $term->term_id . '-archive-' . $taxonomy->name,
							'text' => $term->name . ' (' . $term_taxonomy_name . ') - Archive',
						);
					}
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $label,
					'children' => $data,
				);
			}
		}

		// return the result in json.
		echo json_encode( $result );
		die();
	}
}

/**
 * Change style status
 *
 * @since 0.0.1
 */
function cp_update_style_status() {

	if ( ! current_user_can( 'edit_cp_popup' ) ) {
		$data = array(
			'message' => __( 'You are not authorized to perform this action.', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}

	$style_id     = isset( $_POST['style_id'] ) ? esc_attr( $_POST['style_id'] ) : '';
	$style_status = isset( $_POST['style_status'] ) ? esc_attr( $_POST['style_status'] ) : '';

	if ( '' !== $style_id ) {
		$result = update_post_meta( (int) $style_id, 'live', $style_status );

		wp_send_json_success();

	}

	wp_send_json_error();
}

/**
 * Function Name: cp_migrate_meta_keys.
 * Function Description: cp_migrate_meta_keys.
 */
function cp_migrate_meta_keys() {

	$query_args = array(
		'post_type'      => CP_CUSTOM_POST_TYPE,
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'post_status'    => 'publish',
	);

	$popups  = new WP_Query( $query_args );
	$designs = $popups->posts;
	wp_reset_postdata();

	if ( is_array( $designs ) && ! empty( $designs ) ) {
		foreach ( $designs as $key => $design_id ) {

			$combined_configuration_data = array();
			$combined_design_data        = array();
			$old_configure_meta_keys     = array(
				'launch',
				'target',
				'embed',
				'cookies',
				'pages',
				'visitors',
				'schedule',
			);

			$old_design_meta_keys = array( 'panel', 'form' );

			// Combine all configruation meta keys.
			foreach ( $old_configure_meta_keys as $value ) {

				$meta_data = get_post_meta( $design_id, $value, true );
				if ( '' !== $meta_data ) {
					$combined_configuration_data = array_merge( $combined_configuration_data, $meta_data );
				}
			}

			// Combine all design meta keys.
			foreach ( $old_design_meta_keys as $value ) {

				$meta_data = get_post_meta( $design_id, $value, true );
				if ( '' !== $meta_data ) {
					$combined_design_data = array_merge( $combined_design_data, $meta_data );
				}
			}

			// Add new meta keys for style.
			update_post_meta( $design_id, 'design', $combined_design_data );
			update_post_meta( $design_id, 'configure', $combined_configuration_data );

			$meta_keys = array_merge( $old_design_meta_keys, $old_configure_meta_keys );

			// Delete all old meta keys.
			foreach ( $meta_keys as $meta_key ) {

				delete_post_meta( $design_id, $meta_key );
			}
		}
	}

	wp_send_json_success();

}

/**
 * Function Name: cp_refresh_html.
 * Function Description: cp_refresh_html.
 */
function cp_refresh_html() {

	if ( ! current_user_can( 'access_cp_pro' ) ) {
		die( '-1' );
	}

	check_ajax_referer( 'cp_ajax_nonce', 'cp_nonce' );

	$query_args = array(
		'post_type'      => CP_CUSTOM_POST_TYPE,
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'post_status'    => 'publish',
	);

	$popups  = new WP_Query( $query_args );
	$designs = $popups->posts;
	wp_reset_postdata();

	$styles_list = array();
	$data        = array(
		'message' => __( 'All styles are refreshed', 'convertpro' ),
	);

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

			$design_name = get_the_title( $design );

			$styles_list[] = $design_name;
		}

		$data['styles'] = $styles_list;

		wp_send_json_success( $data );

	} else {

		$data = array(
			'message' => __( 'No styles found to refresh', 'convertpro' ),
		);
		wp_send_json_error( $data );
	}
}

/**
 * Return search results only by post title.
 *
 * @param  (string)   $search   Search SQL for WHERE clause.
 * @param  (WP_Query) $wp_query The current WP_Query object.
 *
 * @return (string) The Modified Search SQL for WHERE clause.
 */
function cpro_search_only_titles( $search, $wp_query ) {
	if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
		global $wpdb;

		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';

		$search = array();

		foreach ( (array) $q['search_terms'] as $term ) {
			$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
		}

		if ( ! is_user_logged_in() ) {
			$search[] = "$wpdb->posts.post_password = ''";
		}

		$search = ' AND ' . implode( ' AND ', $search );
	}

	return $search;
}
