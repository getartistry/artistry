<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "target_rule".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'target_rule', 'cp_v2_target_rule_settings_field' );
	add_action( 'admin_enqueue_scripts', 'framework_target_rule_admin_styles' );
}

/**
 * Function Name: framework_group_filters_admin_styles.
 * Function Description: framework_group_filters_admin_styles.
 *
 * @param string $hook string parameter.
 */
function framework_target_rule_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-target_rule-script', plugins_url( 'target_rule.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	}
}

/**
 * Function Name: cp_v2_target_rule_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_target_rule_settings_field( $name, $settings, $value ) {
	$input_name     = $name;
	$type           = isset( $settings['type'] ) ? $settings['type'] : 'target_rule';
	$class          = isset( $settings['class'] ) ? $settings['class'] : '';
	$rule_type      = isset( $settings['rule_type'] ) ? $settings['rule_type'] : 'target_rule';
	$add_rule_label = isset( $settings['add_rule_label'] ) ? $settings['add_rule_label'] : __( 'Add Rule', 'convertpro' );
	$saved_values   = json_decode( $value, true );
	$output         = '';
	$is_singular    = apply_filters( 'cpro_target_rule_is_singular', false );

	$args = array(
		'public'   => true,
		'_builtin' => true,
	);

	$builtin_post_types = get_post_types( $args, 'objects' );
	unset( $builtin_post_types['attachment'] );

	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	$custom_post_type = get_post_types( $args, 'objects' );

	$selection_options = array(
		'basic'         => array(
			'label' => __( 'Basic', 'convertpro' ),
			'value' => array(
				'basic-global'    => __( 'Entire Website', 'convertpro' ),
				'basic-singulars' => __( 'All Singulars', 'convertpro' ),
				'basic-archives'  => __( 'All Archives', 'convertpro' ),
			),
		),

		'special-pages' => array(
			'label' => __( 'Special Pages', 'convertpro' ),
			'value' => array(
				'special-404'    => __( '404 Page', 'convertpro' ),
				'special-search' => __( 'Search Page', 'convertpro' ),
				'special-blog'   => __( 'Blog / Posts Page', 'convertpro' ),
				'special-front'  => __( 'Front Page', 'convertpro' ),
				'special-date'   => __( 'Date Archive', 'convertpro' ),
				'special-author' => __( 'Author Archive', 'convertpro' ),
			),
		),
	);

	/* Builtin post types */
	foreach ( $builtin_post_types as $post_type ) {

		$args = array(
			'public'      => true,
			'_builtin'    => true,
			'object_type' => array( $post_type->name ),
		);

		$builtin_taxonomies = get_taxonomies( $args, 'objects' );
		unset( $builtin_taxonomies['post_format'] );

		$post_opt = cp_v2_get_post_target_rule_options( $post_type, $builtin_taxonomies, $is_singular );

		$selection_options[ $post_opt['post_key'] ] = array(
			'label' => $post_opt['label'],
			'value' => $post_opt['value'],
		);
	}

	/* Custom post types */
	foreach ( $custom_post_type as $c_post_type ) {

		$args = array(
			'public'      => true,
			'_builtin'    => false,
			'object_type' => array( $c_post_type->name ),
		);

		$custom_taxonomies = get_taxonomies( $args, 'objects' );

		$post_opt = cp_v2_get_post_target_rule_options( $c_post_type, $custom_taxonomies, $is_singular );

		$selection_options[ $post_opt['post_key'] ] = array(
			'label' => $post_opt['label'],
			'value' => $post_opt['value'],
		);
	}

	$selection_options['specific-target'] = array(
		'label' => __( 'Specific Target', 'convertpro' ),
		'value' => array(
			'specifics' => __( 'Target Specifics', 'convertpro' ),
		),
	);

	if ( $is_singular ) {
		unset( $selection_options['special-pages'] );
		unset( $selection_options['pages'] );
		unset( $selection_options['basic']['value']['basic-archives'] );
		unset( $selection_options['basic']['value']['basic-archives'] );
	}

	/* WP Template Format */
	$output         .= '<script type="text/html" id="tmpl-cp-target-rule-condition">';
		$output     .= '<div class="cp-target-rule-condition cp-target-rule-{{data.id}}" data-rule="{{data.id}}" >';
			$output .= '<span class="target_rule-condition-delete dashicons dashicons-no-alt"></span>';
			/* Condition Selection */
			$output         .= '<div class="target_rule-condition-wrap" >';
				$output     .= '<select name="' . esc_attr( $input_name ) . '_on" class="target_rule-condition form-control cp-input">';
					$output .= '<option value="">' . __( 'Select', 'convertpro' ) . '</option>';

	foreach ( $selection_options as $group => $group_data ) {

			$output .= '<optgroup label="' . $group_data['label'] . '">';
		foreach ( $group_data['value'] as $opt_key => $opt_value ) {
			$output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
		}
		$output .= '</optgroup>';
	}
				$output .= '</select>';
			$output     .= '</div>';

			/* Specific page selection */
			$output     .= '<div class="target_rule-specific-page-wrap" style="display:none">';
				$output .= '<select name="' . esc_attr( $input_name ) . '_on_specifics_{{data.id}}" class="target-rule-select2 target_rule-specific-page form-control cp-input " multiple="multiple">';
				$output .= '</select>';
			$output     .= '</div>';
		$output         .= '</div>';
	$output             .= '</script>';

	/* Wrapper Start */
	$output     .= '<div class="cp-target-rule-wrapper cp-target-rule-' . $rule_type . '-on-wrap" data-type="' . $rule_type . '">';
		$output .= '<input type="hidden" class="form-control cp-input cp-target_rule-input" name="' . esc_attr( $input_name ) . '" value=' . $value . ' />';

		$output     .= '<div class="cp-target-rule-selector-wrapper cp-target-rule-' . $rule_type . '-on">';
			$output .= cp_v2_generate_target_rule_selector( $rule_type, $selection_options, $input_name, $saved_values, $add_rule_label );
		$output     .= '</div>';

	/* Wrapper end */
	$output .= '</div>';

	return $output;

	/* ======================================================================= */

	if ( isset( $settings['conditional_logic'] ) && $settings['conditional_logic'] ) {
		$checked       = ( ! empty( $selected_values ) ) ? 'checked="checked"' : '';
		$checked_class = ( empty( $selected_values ) ) ? 'display:none;' : '';
		$output       .= '<div class="checkbox cp-allow-specific-posts"><p><label><input type="checkbox" value="" id="" class="" name ="" ' . $checked . '> ' . $settings['conditional_label'] . '</label></p></div>';
	}

	$output .= '<div class="cp-select2-wrap" style="' . $checked_class . '"><select name="' . esc_attr( $input_name ) . '" id="cp_' . esc_attr( $input_name ) . '" class="select2-group_filters-dropdown form-control cp-input ' . esc_attr( 'cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class ) . '" multiple="multiple" style="width:260px;">';

	foreach ( $selected_values as $key => $sel_value ) {

		// posts.
		if ( strpos( $sel_value, 'post-' ) !== false ) {
			$post_id    = (int) str_replace( 'post-', '', $sel_value );
			$post_title = get_the_title( $post_id );
			$output    .= '<option value="post-' . $post_id . '" selected="selected" >' . $post_title . '</option>';
		}

			// taxonomy options.
		if ( strpos( $sel_value, 'tax-' ) !== false ) {
			$tax_id        = (int) str_replace( 'tax-', '', $sel_value );
			$term          = get_term( $tax_id );
			$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );
			$output       .= '<option value="tax-' . $tax_id . '" selected="selected" >' . $term->name . ' - ' . $term_taxonomy . '</option>';
		}

			// Special Pages.
			$spacial_pages = array(
				'blog'       => __( 'Blog / Posts Page', 'convertpro' ),
				'front_page' => __( 'Front Page', 'convertpro' ),
				'archive'    => __( 'Archive Page', 'convertpro' ),
				'author'     => __( 'Author Page', 'convertpro' ),
				'search'     => __( 'Search Page', 'convertpro' ),
				'404'        => __( '404 Page', 'convertpro' ),
			);

		foreach ( $spacial_pages as $page => $title ) {
			$selected = ( 'special-' . $page == $sel_value ) ? true : false;
			if ( $selected ) {
				$output .= "<option selected='selected' value='special-" . $page . "' >" . $title . '</option>';
			}
		}
	}

	$output .= '</select></div>';
	return $output;
}

/**
 * Function Name: cp_v2_get_post_target_rule_options.
 * Function Description: Post type object options.
 *
 * @param object  $post_type post type parameter.
 * @param object  $taxonomies taxonomies.
 * @param boolean $is_singular Check is only singular posts are to be fetched.
 */
function cp_v2_get_post_target_rule_options( $post_type, $taxonomies, $is_singular ) {

	$post_key    = str_replace( ' ', '-', strtolower( $post_type->label ) );
	$post_label  = ucwords( $post_type->label );
	$post_name   = $post_type->name;
	$post_option = array();

	/* translators: %s percentage. */
	$all_posts = sprintf( __( 'All %s', 'convertpro' ), $post_label );
	/* translators: %s percentage. */
	$all_archive = sprintf( __( 'All %s Archive', 'convertpro' ), $post_label );

	$post_option[ $post_name . '|all' ] = $all_posts;

	if ( ! $is_singular ) {
		$post_option[ $post_name . '|all|archive' ] = $all_archive;
		foreach ( $taxonomies as $taxonomy ) {
			$tax_label = ucwords( $taxonomy->label );
			$tax_name  = $taxonomy->name;

			/* translators: %s percentage */
			$tax_archive = sprintf( __( 'All %s Archive', 'convertpro' ), $tax_label );

			$post_option[ $post_name . '|all|taxarchive|' . $tax_name ] = $tax_archive;
		}
	}

	$post_output['post_key'] = $post_key;
	$post_output['label']    = $post_label;
	$post_output['value']    = $post_option;

	return $post_output;
}

/**
 * Function Name: cp_v2_generate_target_rule_selector.
 * Function Description: Post type object options.
 *
 * @param object $type rule parameter.
 * @param object $selection_options options for selection.
 * @param object $input_name input name.
 * @param object $saved_values saved settings value.
 * @param object $add_rule_label label.
 */
function cp_v2_generate_target_rule_selector( $type, $selection_options, $input_name, $saved_values, $add_rule_label ) {

	$output = '<div class="target_rule-builder-wrap">';

	if ( ! is_array( $saved_values ) || ( is_array( $saved_values ) && empty( $saved_values ) ) ) {

		$saved_values    = array();
		$saved_values[0] = array(
			'type'     => '',
			'specific' => null,
		);
	}
	foreach ( $saved_values as $index => $data ) {

		$output .= '<div class="cp-target-rule-condition cp-target-rule-' . $index . '" data-rule="' . $index . '" >';
			/* Condition Selection. */
			$output         .= '<span class="target_rule-condition-delete dashicons dashicons-no-alt"></span>';
			$output         .= '<div class="target_rule-condition-wrap" >';
				$output     .= '<select name="' . esc_attr( $input_name ) . '_on" class="target_rule-condition form-control cp-input">';
					$output .= '<option value="">' . __( 'Select', 'convertpro' ) . '</option>';

		foreach ( $selection_options as $group => $group_data ) {

				$output .= '<optgroup label="' . $group_data['label'] . '">';
			foreach ( $group_data['value'] as $opt_key => $opt_value ) {

				// specific.
				$selected = '';

				if ( $data['type'] == $opt_key ) {
					$selected = 'selected="selected"';
				}

				$output .= '<option value="' . $opt_key . '" ' . $selected . '>' . $opt_value . '</option>';
			}
			$output .= '</optgroup>';
		}
				$output .= '</select>';
			$output     .= '</div>';

			/* Specific page selection */
			$output     .= '<div class="target_rule-specific-page-wrap" style="display:none">';
				$output .= '<select name="' . esc_attr( $input_name ) . '_on_specifics_' . $index . '" class="target-rule-select2 target_rule-specific-page form-control cp-input " multiple="multiple">';

		if ( null != $data['specific'] && is_array( $data['specific'] ) ) {

			foreach ( $data['specific'] as $data_key => $sel_value ) {
				// posts.
				if ( strpos( $sel_value, 'post-' ) !== false ) {
					$post_id    = (int) str_replace( 'post-', '', $sel_value );
					$post_title = get_the_title( $post_id );
					$output    .= '<option value="post-' . $post_id . '" selected="selected" >' . $post_title . '</option>';
				}

				// taxonomy options.
				if ( strpos( $sel_value, 'tax-' ) !== false ) {

					$tax_data = explode( '-', $sel_value );

					if ( isset( $tax_data[1] ) && isset( $tax_data[2] ) ) {

						$tax_id        = (int) $tax_data[1];
						$term          = get_term( $tax_id );
						$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );

						if ( 'single' === $tax_data[2] ) {
							$term_name = $term->name . ' (' . $term_taxonomy . ') - Single';
						} else {
							$term_name = $term->name . ' (' . $term_taxonomy . ') - Archive';
						}

						$output .= '<option value="' . $sel_value . '" selected="selected" >' . $term_name . '</option>';
					}
				}
			}
		}

				$output .= '</select>';
			$output     .= '</div>';
		$output         .= '</div>';

		$new_index = $index + 1;
	}

	$output .= '</div>';

	/* Add new rule */
	$output     .= '<div class="target_rule-add-rule-wrap">';
		$output .= '<a href="#" class="button" data-rule-id="' . $index . '" data-rule-type="' . $type . '">' . $add_rule_label . '</a>';
	$output     .= '</div>';

	if ( 'display' == $type ) {
		/* Add new rule */
		$output     .= '<div class="target_rule-add-exclusion-rule">';
			$output .= '<a href="#" class="button">' . __( 'Add Exclusion Rule', 'convertpro' ) . '</a>';
		$output     .= '</div>';
	}

	return $output;
}
