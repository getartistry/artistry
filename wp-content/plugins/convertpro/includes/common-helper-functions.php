<?php
/**
 * Common helper function file.
 *
 * @package ConvertPro
 */

add_filter( 'body_class', 'cp_credit_link_front_end' );
add_filter( 'admin_body_class', 'cp_credit_link_back_end' );

/**
 * Function Name: cp_credit_link_front_end.
 * Function Description: cp_credit_link_front_end.
 *
 * @param string $classes string parameter.
 */
function cp_credit_link_front_end( $classes ) {
	$affiliate_enable = esc_attr( get_option( 'cp_credit_option' ) );
	if ( 1 != $affiliate_enable && '' != $affiliate_enable ) {
		$classes[] = 'cp_aff_false';
	}
	return $classes;
}

/**
 * Function Name: cp_credit_link_back_end.
 * Function Description: cp_credit_link_back_end.
 *
 * @param string $classes string parameter.
 */
function cp_credit_link_back_end( $classes ) {
	$affiliate_enable = esc_attr( get_option( 'cp_credit_option' ) );
	if ( 1 != $affiliate_enable && '' != $affiliate_enable ) {
		$classes .= ' cp_aff_false';
	}
	return $classes;
}


$cp_pro_filesystem = null;

/**
 * Function Name: cp_load_filesystem.
 * Function Description: cp_load_filesystem.
 */
function cp_load_filesystem() {

	global $cp_pro_filesystem;

	if ( null === $cp_pro_filesystem ) {

		require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';

		$cp_pro_filesystem = new WP_Filesystem_Direct( array() );

		if ( ! defined( 'FS_CHMOD_FILE' ) ) {
			define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );
		}
	}
}

/**
 * Function Name: add_input_type.
 * Function Description: Get template file for field.
 *
 * @param string $field string parameter.
 */
function cp_get_field_template( $field ) {

	$contents  = '';
	$field_dir = CP_V2_BASE_DIR . 'framework/fields/' . $field . '/template.html';

	cp_load_filesystem();

	global $cp_pro_filesystem;

	if ( file_exists( $field_dir ) ) {
		$contents = $cp_pro_filesystem->get_contents(
			$field_dir,
			FS_CHMOD_FILE
		);
	}

	return $contents;
}

/**
 * Function Name: add_input_type.
 * Function Description: function to add new input field into $params array.
 *
 * @param string $type string parameter.
 * @param string $input_field_callback string parameter.
 */
function add_input_type( $type, $input_field_callback ) {

	$result = false;
	if ( ! empty( $type ) && ! empty( $input_field_callback ) ) {
		Cp_Framework::$params[ $type ] = array(
			'callback' => $input_field_callback,
		);
		$result                        = true;
	}
	return $result;
}

/**
 * Function Name: cp_add_input_type.
 * Function Description: Helper function to register new input type hook.
 *
 * @param string $type string parameter.
 * @param string $input_field_callback string parameter.
 */
function cp_add_input_type( $type, $input_field_callback ) {

	return add_input_type( $type, $input_field_callback );
}

/**
 * Function Name: cp_get_all_campaigns.
 * Function Description: Get all campaigns.
 */
function cp_get_all_campaigns() {

	$campaign_terms = true;

	if ( false !== $campaign_terms ) {

		$terms = get_terms(
			array(
				'taxonomy'   => CP_CAMPAIGN_TAXONOMY,
				'hide_empty' => true,
			)
		);

		set_transient( '_cp_campaign_taxonomy', $terms, 30 * DAY_IN_SECONDS );
		$campaign_terms = $terms;
	}

	return $campaign_terms;
}

/**
 * Function Name: cpro_get_style_settings.
 * Function Description: cpro_get_style_settings.
 *
 * @param string $style_id string parameter.
 * @param string $section string parameter.
 * @param string $settings_name string parameter.
 */
function cpro_get_style_settings( $style_id, $section, $settings_name ) {

	$setting_value = '';
	$data          = get_post_meta( $style_id, $section, true );

	if ( is_array( $data ) ) {
		if ( 'configure' == $section ) {

			$rulsets = array();

			if ( isset( $data['rulesets'] ) ) {
				$rulsets = json_decode( $data['rulesets'], true );
			}

			if ( isset( $rulsets[0][ $settings_name ] ) ) {

				$setting_value = $rulsets[0][ $settings_name ];
			} elseif ( isset( $data[ $settings_name ] ) ) {

				$setting_value = $data[ $settings_name ];
			}
		} else {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					if ( array_key_exists( $settings_name, $value ) ) {
						$setting_value = $value[ $settings_name ];
						break;
					}
				}
			}
		}
	}

	return $setting_value;
}

if ( ! function_exists( 'cp_generate_multi_input_result' ) ) {
	/**
	 * Function Name: cp_generate_multi_input_result.
	 * Function Description: Generate Border, Padding, Border Radius, Margin.
	 *
	 * @param string $property string parameter.
	 * @param string $string string parameter.
	 */
	function cp_generate_multi_input_result( $property, $string ) {
		$pairs  = explode( '|', $string );
		$result = '';
		$unit   = ( isset( $pairs[4] ) && '' != $pairs[4] ) ? $pairs[4] : 'px';

		$result .= $property . ':' . $pairs[0] . $unit . ' ' . $pairs[1] . $unit . ' ' . $pairs[2] . $unit . ' ' . $pairs[3] . $unit . ';';

		return $result;
	}
}

/**
 * Function Name: cp_apply_gradient_bg.
 * Function Description: Linear Gradient background.
 *
 * @param string $lighter_color string parameter.
 * @param string $location_1 string parameter.
 * @param string $darker_color string parameter.
 * @param string $location_2 string parameter.
 * @param string $angle string parameter.
 */
function cp_apply_gradient_bg( $lighter_color, $location_1, $darker_color, $location_2, $angle ) {

	$gradient_style  = '';
	$gradient_style .= 'background : ' . $lighter_color . ';';
	$gradient_style .= 'background : -webkit-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -moz-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -ms-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -o-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';

	return $gradient_style;
}

/**
 * Function Name: cp_overlay_gradient_bg.
 * Function Description: Overlay Linear Gradient background.
 *
 * @param string $style_id string parameter.
 * @param array  $style_array array parameter.
 */
function cp_overlay_gradient_bg( $style_id, $style_array ) {

	$lighter_color = ( isset( $style_array->overlay_lighter_color ) && '' !== $style_array->overlay_lighter_color ) ? $style_array->overlay_lighter_color : '#fff';
	$location_1    = ( isset( $style_array->overlay_lighter_location ) && '' !== $style_array->overlay_lighter_location ) ? $style_array->overlay_lighter_location : '#ddd';
	$darker_color  = ( isset( $style_array->overlay_darker_color ) && '' !== $style_array->overlay_darker_color ) ? $style_array->overlay_darker_color : 'rgba(221,221,221,0.9)';
	$location_2    = ( isset( $style_array->overlay_darker_location ) && '' !== $style_array->overlay_darker_location ) ? $style_array->overlay_darker_location : '100';
	$angle         = ( isset( $style_array->overlay_gradient_angle ) && '' !== $style_array->overlay_gradient_angle ) ? $style_array->overlay_gradient_angle : '180';

	$gradient_style  = '';
	$gradient_style .= '.cp_style_' . $style_id . ' .cpro-overlay{';
	$gradient_style .= 'background : ' . $lighter_color . ';';
	$gradient_style .= 'background : -webkit-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -moz-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -ms-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : -o-linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= 'background : linear-gradient(' . $angle . 'deg, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . '  ' . $location_2 . '%);';
	$gradient_style .= '}';

	return $gradient_style;
}

/**
 * Function Name: cp_apply_gradient_bg_rad.
 * Function Description: Radial Gradient background.
 *
 * @param string $style_id string parameter.
 * @param array  $style_array array parameter.
 */
function cp_overlay_gradient_bg_rad( $style_id, $style_array ) {

	$radial_gadient_type = ( isset( $style_array->radial_overlay_gradient_direction ) && '' !== $style_array->radial_overlay_gradient_direction ) ? $style_array->radial_overlay_gradient_direction : 'center_center';

	$lighter_color = ( isset( $style_array->overlay_lighter_color ) && '' !== $style_array->overlay_lighter_color ) ? $style_array->overlay_lighter_color : '#fff';

	$location_1 = ( isset( $style_array->overlay_lighter_location ) && '' !== $style_array->overlay_lighter_location ) ? $style_array->overlay_lighter_location : '#ddd';

	$darker_color = ( isset( $style_array->overlay_darker_color ) && '' !== $style_array->overlay_darker_color ) ? $style_array->overlay_darker_color : 'rgba(221,221,221,0.9)';

	$location_2 = ( isset( $style_array->overlay_darker_location ) && '' !== $style_array->overlay_darker_location ) ? $style_array->overlay_darker_location : '100';

	$gradient_style  = '';
	$gradient_style .= '.cp_style_' . $style_id . ' .cpro-overlay{';
	switch ( $radial_gadient_type ) {
		case 'center_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'center_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'center_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

	}
	$gradient_style .= '}';
	return $gradient_style;
}

/**
 * Function Name: cp_apply_gradient_bg_rad.
 * Function Description: Radial Gradient background.
 *
 * @param string $radial_gadient_type string parameter.
 * @param string $lighter_color string parameter.
 * @param string $location_1 string parameter.
 * @param string $darker_color string parameter.
 * @param string $location_2 string parameter.
 */
function cp_apply_gradient_bg_rad( $radial_gadient_type, $lighter_color, $location_1, $darker_color, $location_2 ) {
	$gradient_style = '';
	switch ( $radial_gadient_type ) {
		case 'center_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'center_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'center_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at center right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'top_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at top right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_center':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom center, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_left':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom left, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

		case 'bottom_right':
			$gradient_style .= 'background : ' . $lighter_color . ';';
			$gradient_style .= 'background : -webkit-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -moz-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : -o-radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			$gradient_style .= 'background : radial-gradient( at bottom right, ' . $lighter_color . ' ' . $location_1 . '%, ' . $darker_color . ' ' . $location_2 . '%);';
			break;

	}
	return $gradient_style;
}

if ( ! function_exists( 'cp_pro_get_form_hidden_fields' ) ) {
	/**
	 * Function Name: cp_pro_get_form_hidden_fields.
	 * Function Description: cp_pro_get_form_hidden_fields.
	 *
	 * @param string $style_id string parameter.
	 */
	function cp_pro_get_form_hidden_fields( $style_id ) {
		/** = Form options
		 *  Mailer - We will also optimize this by filter. If in any style we need the form then apply filter otherwise nope.
		 *-----------------------------------------------------------*/

		$on_success_action = '';
		$on_success        = '';
		$on_redirect       = '';
		$mailer_id         = '';
		$list_id           = '';
		$data_option       = '';
		$api_connection    = cpro_get_style_settings( $style_id, 'connect', 'api_connection' );
		$action            = 'cp_v2_add_subscriber';
		$connect_meta      = get_post_meta( $style_id, 'connect' );
		$mailer            = '';
		$mailer_name       = '';
		$meta              = '';

		if ( class_exists( 'Cp_V2_Services_Loader' ) ) {
			if ( is_array( $connect_meta ) && ! empty( $connect_meta ) ) {
				$meta = call_user_func_array( 'array_merge', call_user_func_array( 'array_merge', get_post_meta( $style_id, 'connect' ) ) );
			}

			if ( is_array( $meta ) && ! empty( $meta ) ) {
				foreach ( $meta as $key => $m ) {
					$meta[ $key ] = json_decode( $m );
				}
			}

			if ( isset( $meta['cp_connect_settings'] ) && is_array( $meta['cp_connect_settings'] ) ) {
				foreach ( $meta['cp_connect_settings'] as $key => $setting ) {
					if ( 'cp-integration-account-slug' == $setting->name ) {
						$mailer_name = $setting->value;
						$mailer      = ConvertPlugHelper::get_connection_data( $setting->value );
					}
				}
			}
		}

		if ( '' == $mailer ) {
			$action = 'cp_v2_notify_admin';
		}

		ob_start();
		$uid = md5( uniqid( rand(), true ) );

		global $wp;
		$current_url = home_url( add_query_arg( array(), $wp->request ) );
		?>		
		<input type="hidden" name="param[date]" value="{{current_date}}" />
		<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
		<input type="hidden" name="style_id" value="<?php echo esc_attr( $style_id ); ?>" />
		<?php
		$html = ob_get_clean();
		echo $html;
	}
}

add_action( 'cp_pro_form_hidden_fields', 'cp_pro_get_form_hidden_fields', 10, 1 );

/**
 * Function Name: cp_v2_is_style_visible.
 * Function Description: Check if style is visible here or not.
 *
 * @param string $style_id string parameter.
 */
function cp_v2_is_style_visible( $style_id ) {
	global $post;

	$old_post = $post;
	wp_reset_postdata();
	$post_id = ( ! is_404() && ! is_search() && ! is_archive() && ! is_home() ) ? $post->ID : false;
	$post    = $old_post;

	$show_popup = false;
	$display_on = cpro_get_style_settings( $style_id, 'configure', 'target_rule_display' );
	$exclude_on = cpro_get_style_settings( $style_id, 'configure', 'target_rule_exclude' );

	$show_for_logged_in = cpro_get_style_settings( $style_id, 'configure', 'show_for_logged_in' );

	/* Parse Display On Condition */
	$is_display = cp_v2_parse_condition( $post_id, $display_on );
	/* Parse Exclude On Condition */
	$is_exclude = cp_v2_parse_condition( $post_id, $exclude_on );

	if ( $is_display && ! $is_exclude ) {

		$show_popup = true;

		if ( is_user_logged_in() && ! $show_for_logged_in ) {
			$show_popup = false;
		}
	}

	// filter target page settings.
	$show_popup = apply_filters( 'cp_pro_target_page_settings', $show_popup, $style_id );

	return $show_popup;
}

/**
 * Function Name: cp_v2_parse_condition.
 * Function Description: parse target rule conditions.
 *
 * @param string $post_id ID of post.
 * @param array  $rules target rules.
 */
function cp_v2_parse_condition( $post_id, $rules ) {

	$rules      = json_decode( $rules );
	$show_popup = false;

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
					$show_popup = true;
					break;

				case 'basic-singulars':
					if ( is_singular() ) {
						$show_popup = true;
					}
					break;

				case 'basic-archives':
					if ( is_archive() ) {
						$show_popup = true;
					}
					break;

				case 'special-404':
					if ( is_404() ) {
						$show_popup = true;
					}
					break;

				case 'special-search':
					if ( is_search() ) {
						$show_popup = true;
					}
					break;

				case 'special-blog':
					if ( is_home() ) {
						$show_popup = true;
					}
					break;

				case 'special-front':
					if ( is_front_page() ) {
						$show_popup = true;
					}
					break;

				case 'special-date':
					if ( is_date() ) {
						$show_popup = true;
					}
					break;

				case 'special-author':
					if ( is_author() ) {
						$show_popup = true;
					}
					break;

				case 'all':
					$rule_data = explode( '|', $rule->type );

					$post_type     = isset( $rule_data[0] ) ? $rule_data[0] : false;
					$archieve_type = isset( $rule_data[2] ) ? $rule_data[2] : false;
					$taxonomy      = isset( $rule_data[3] ) ? $rule_data[3] : false;

					if ( false === $archieve_type ) {

						$current_post_type = get_post_type( $post_id );

						if ( false !== $post_id && $current_post_type == $post_type ) {

							$show_popup = true;
						}
					} else {

						if ( is_archive() ) {

							$current_post_type = get_post_type();
							if ( $current_post_type == $post_type ) {
								if ( 'archive' == $archieve_type ) {
									$show_popup = true;
								} elseif ( 'taxarchive' == $archieve_type ) {

									$obj              = get_queried_object();
									$current_taxonomy = '';
									if ( '' !== $obj && null !== $obj ) {
										$current_taxonomy = $obj->taxonomy;
									}

									if ( $current_taxonomy == $taxonomy ) {
										$show_popup = true;
									}
								}
							}
						}
					}
					break;

				case 'specifics':
					if ( isset( $rule->specific ) && is_array( $rule->specific ) ) {

						foreach ( $rule->specific as $specific_page ) {

							$specific_data      = explode( '-', $specific_page );
							$specific_post_type = isset( $specific_data[0] ) ? $specific_data[0] : false;

							if ( 'post' == $specific_post_type ) {

								$specific_post_id = isset( $specific_data[1] ) ? $specific_data[1] : false;

								if ( $specific_post_id == $post_id ) {
									$show_popup = true;
								}
							} elseif ( 'tax' == $specific_post_type ) {

								$tax_slug = isset( $specific_data[3] ) ? $specific_data[3] : false;

								if ( $tax_slug ) {

									$tax_id     = isset( $specific_data[1] ) ? (int) $specific_data[1] : false;
									$apply_type = isset( $specific_data[2] ) ? $specific_data[2] : false;

									if ( 'single' === $apply_type && is_singular() ) {

										$current_terms = get_the_terms( $post_id, $tax_slug );

										if ( $tax_id && is_array( $current_terms ) && ! empty( $current_terms ) ) {
											foreach ( $current_terms as $key => $term_data ) {
												if ( $tax_id === $term_data->term_id ) {
													$show_popup = true;
												}
											}
										}
									} elseif ( 'archive' === $apply_type && is_archive() && ( is_category() || is_tag() || is_tax() ) ) {

										$q_obj = get_queried_object();

										if ( is_object( $q_obj ) && $tax_id === $q_obj->term_id ) {
											$show_popup = true;
										}
									}
								}
							}
						}
					}
					break;

				default:
					break;
			}

			if ( $show_popup ) {
				break;
			}
		}
	}

	return $show_popup;
}

/**
 * Display style inline
 *
 * @since 1.0.0
 */
function cp_v2_display_style_inline() {

	$before_content_string = '';
	$after_content_string  = '';

	$style_arrays = cp_get_live_popups( 'inline' );

	if ( is_array( $style_arrays ) ) {

		foreach ( $style_arrays as $key => $style_id ) {

			$display          = false;
			$display_inline   = false;
			$settings_encoded = '';
			$style_settings   = array();
			$data             = get_post_meta( $style_id, 'cp_modal_data', true );

			$settings_array = $data;
			$meta_data      = get_metadata( 'post', $style_id );

			$enable_display_inline = cpro_get_style_settings( $style_id, 'configure', 'enable_display_inline' );
			$inline_position       = '';

			if ( $enable_display_inline ) {
				$display_inline  = true;
				$inline_position = cpro_get_style_settings( $style_id, 'configure', 'inline_position' );
				$display         = cp_v2_is_style_visible( $style_id );
			}

			if ( $display && $display_inline && $style_id ) {
				$step_id = '1';
				ob_start();

				if ( '' !== $style_id ) {
					echo do_shortcode( '[cp_popup style_id="' . $style_id . '" step_id = ' . $step_id . ' display="inline" ][/cp_popup]' );
				}

				switch ( $inline_position ) {
					case 'before_post':
						$before_content_string .= ob_get_contents();
						break;
					case 'after_post':
						$after_content_string .= ob_get_contents();
						break;
					case 'both':
						$after_content_string  .= ob_get_contents();
						$before_content_string .= ob_get_contents();
						break;
				}

				ob_end_clean();
			}
		}
	}

	$output_string = array( $before_content_string, $after_content_string );

	return $output_string;
}

/**
 * Function Name: cp_get_panel.
 * Function Description: Get panel's HTML and Style.
 *
 * @param string $properties string parameter.
 * @param string $panel_id string parameter.
 * @param string $style_id string parameter.
 */
function cp_get_panel( $properties, $panel_id, $style_id ) {

	$data             = array();
	$class            = '';
	$mobile_break_pt  = cpro_get_style_settings( $style_id, 'design', 'cp_mobile_br_point' );
	$module_type      = get_post_meta( $style_id, 'cp_module_type', true );
	$is_inline_module = false;
	if ( 'inline' == $module_type || 'before_after' == $module_type || 'widget' == $module_type ) {
		$is_inline_module = true;
	}

	$mobile_resp = get_post_meta( $style_id, 'cp_mobile_responsive', true ) != false ? get_post_meta( $style_id, 'cp_mobile_responsive', true ) : 'no';

	if ( strpos( $panel_id, 'panel' ) !== false ) {
		$properties->type = 'panel';
	}

	if ( isset( $properties->type ) ) {

		$template_data = cp_get_field_template( $properties->type );

		$template_data = apply_filters( 'cp_get_field_template', $template_data, $properties->type );

		$button_type = 'button';

		$template_data = str_replace( '{{id}}', $panel_id . '-' . $style_id, $template_data );
		$template_data = str_replace( '{{type}}', $properties->type, $template_data );

		if ( isset( $properties->btn_url_follow ) ) {
			$template_data = str_replace( '{{data-url-follow}}', $properties->btn_url_follow, $template_data );
		}

		$template_data = str_replace(
			array(
				'data-tags="{{field_tags}}"',
				'data-resize="{{resize}}"',
				'cp-panel-item',
				'contenteditable="{{contenteditable}}"',
				'data-field-title="Flat Button"',
				'data-loader-style="{{button-loader-style}}"',
				'data-preset="{{field_preset}}"',
				'data-field-title="Image"',
				'data-field-title="Email"',
				'data-field-title="Shape"',
			),
			'',
			$template_data
		);

		$template_data = preg_replace( '/<div class=\"cp-rotate-container\">.*?<\/div>/', '', $template_data );

		$label_as_placeholder = ( isset( $properties->label_as_placeholder ) ) ? $properties->label_as_placeholder : null;

		switch ( $properties->type ) {

			case 'cp_button':
			case 'cp_gradient_button':
				$template_data = str_replace( '{{value}}', $properties->title, $template_data );
				$template_data = str_replace( '{{name}}', $panel_id, $template_data );

				if ( isset( $properties->field_action ) && ( 'submit' == $properties->field_action || 'submit_n_goto_step' == $properties->field_action || 'submit_n_close' == $properties->field_action || 'submit_n_goto_url' == $properties->field_action ) ) {
					$button_type = 'submit';
				}

				if ( isset( $properties->button_loader ) ) {
					$template_data = str_replace( '{{button-loader-style}}', $properties->button_loader, $template_data );
				}

				$template_data = str_replace( '{{button-successs-message}}', $properties->submit_message, $template_data );

				if ( isset( $properties->get_parameter ) ) {
					$template_data = str_replace( '{{get-param}}', $properties->get_parameter, $template_data );
				}

				if ( isset( $properties->btn_preset ) ) {
					$template_data = str_replace( '{{field_preset}}', $properties->btn_preset, $template_data );
				}

				$template_data = str_replace( '{{button-type}}', $button_type, $template_data );

				break;

			case 'cp_hidden_input':
				$template_data = str_replace( '{{backend_view}}', '', $template_data );

				if ( isset( $properties->hidden_input_name ) ) {
					$template_data = str_replace( '{{name}}', $properties->hidden_input_name, $template_data );
				}

				if ( isset( $properties->hidden_input_value ) ) {
					$template_data = str_replace( '{{value}}', $properties->hidden_input_value, $template_data );
				}

				break;

			case 'cp_email':
			case 'cp_text':
				if ( isset( $properties->input_text_name ) && '' != $properties->input_text_name ) {
					$template_data = str_replace( '{{name}}', $properties->input_text_name, $template_data );
				} else {
					$template_data = str_replace( '{{name}}', '_BLANK_NAME', $template_data );
				}

				if ( isset( $properties->input_text_placeholder ) ) {
					$placeholdertext = $properties->input_text_placeholder;
					if ( null != $label_as_placeholder && 'false' == $label_as_placeholder ) {
						$placeholdertext = '';
					}
					$template_data = str_replace( '{{placeholder}}', $placeholdertext, $template_data );
				}

				if ( isset( $properties->email_text_placeholder ) ) {
					$placeholdertext = $properties->email_text_placeholder;
					if ( null != $label_as_placeholder && 'false' == $label_as_placeholder ) {
						$placeholdertext = '';
					}
					$template_data = str_replace( '{{placeholder}}', $placeholdertext, $template_data );
				}

				if ( isset( $properties->required ) ) {
					$required      = ( 'true' == $properties->required ) ? 'required="required"' : '';
					$template_data = str_replace( '{{required}}', $required, $template_data );
				}

				if ( isset( $properties->email_error_msg ) ) {
					$template_data = str_replace( '{{email-error}}', $properties->email_error_msg, $template_data );
				}

				$template_data = str_replace( 'readonly="{{readonly}}"', '', $template_data );

				break;

			case 'cp_textarea':
				if ( isset( $properties->input_text_name ) && '' != $properties->input_text_name ) {
					$template_data = str_replace( '{{name}}', $properties->input_text_name, $template_data );
				} else {
					$template_data = str_replace( '{{name}}', '_BLANK_NAME', $template_data );
				}

				if ( isset( $properties->input_text_placeholder ) ) {
					$placeholdertext = $properties->input_text_placeholder;
					if ( null != $label_as_placeholder && 'false' == $label_as_placeholder ) {
						$placeholdertext = '';
					}
					$template_data = str_replace( '{{placeholder}}', $placeholdertext, $template_data );
				}

				if ( isset( $properties->required ) ) {
					$required      = ( 'true' == $properties->required ) ? 'required="required"' : '';
					$template_data = str_replace( '{{required}}', $required, $template_data );
				}

				break;

			case 'cp_dropdown':
				if ( isset( $properties->dropdown_name ) ) {
					$template_data = str_replace( '{{name}}', $properties->dropdown_name, $template_data );
				}

				if ( isset( $properties->dropdown_options ) ) {
					$output_html = '';
					if ( '' != $properties->dropdown_options ) {
						$optons_arr = explode( "\n", $properties->dropdown_options );
						if ( null != $label_as_placeholder && 'false' != $label_as_placeholder ) {
							$output_html .= '<option value="">' . $properties->input_text_placeholder . '</option>';
						}

						foreach ( $optons_arr as $key => $value ) {
							$output_html .= '<option value="' . $value . '">' . $value . '</option>';
						}
					}
					$template_data = str_replace( '{{options}}', $output_html, $template_data );
				}
				if ( isset( $properties->required ) ) {
					$required = ( 'true' == $properties->required ) ? 'required="required"' : '';

					$template_data = str_replace( '{{required}}', $required, $template_data );
				}

				if ( isset( $properties->input_text_placeholder ) ) {
					$placeholdertext = $properties->input_text_placeholder;
					if ( null != $label_as_placeholder && 'false' == $label_as_placeholder ) {
						$placeholdertext = '';
					}
					$template_data = str_replace( '{{placeholder}}', $placeholdertext, $template_data );
				}

				break;

			case 'cp_radio':
				if ( isset( $properties->radio_options ) ) {
					$output_html = '';
					if ( '' != $properties->radio_options ) {
						$optons_arr = explode( "\n", $properties->radio_options );

						foreach ( $optons_arr as $key => $value ) {
							if ( isset( $properties->required ) ) {
								$required = ( 'true' == $properties->required ) ? 'required="required"' : '';
							}
							$output_html .= '<div class="cp-radio-wrap"><label class="cp_radio_label"><input type="radio" name="param[' . $properties->radio_name . ']" ' . $required . ' value="' . $value . '">' . $value . '</label></div>';
						}
					}
					$template_data = str_replace( '{{radio_options}}', $output_html, $template_data );
				}
				break;

			case 'cp_checkbox':
				if ( isset( $properties->checkbox_options ) ) {
					$output_html = '';
					if ( '' != $properties->checkbox_options ) {
						$optons_arr = explode( "\n", $properties->checkbox_options );

						foreach ( $optons_arr as $key => $value ) {

							if ( isset( $properties->required ) && 'true' == $properties->required ) {
								$template_data = str_replace( '{{checkbox_required}}', 'cpro-checkbox-required', $template_data );
							}

							$output_html .= '<div class="cp-checkbox-wrap"><label class="cp_checkbox_label"><input type="checkbox" name="param[' . $properties->checkbox_name . '-' . $key . ']" value="' . esc_html( $value ) . '">' . $value . '</label></div>';
						}
					}
					$template_data = str_replace( '{{checkbox_options}}', $output_html, $template_data );
				}

				break;

			case 'cp_shape':
				cp_load_filesystem();

				global $cp_pro_filesystem;

				if ( isset( $properties->shape_preset ) ) {

					$field_dir_preset = CP_V2_BASE_DIR . 'framework/fields/' . $properties->type . '/presets/' . str_replace( '-', '_', $properties->shape_preset ) . '.svg';
					$preset_contents  = '';

					if ( file_exists( $field_dir_preset ) ) {
						$preset_contents = $cp_pro_filesystem->get_contents(
							$field_dir_preset,
							FS_CHMOD_FILE
						);
					}

					$template_data = str_replace( '{{svg_content}}', $preset_contents, $template_data );
				}
				if ( isset( $properties->shape_color ) ) {
					$template_data = str_replace( '{{shape_color}}', $properties->shape_color, $template_data );
				}

				if ( isset( $properties->shape_width ) ) {
					$template_data = str_replace( '{{stroke_width}}', $properties->shape_width . 'px', $template_data );
					$template_data = str_replace( '{{stroke_half_width}}', ( $properties->shape_width / 2 ) . 'px', $template_data );
					$template_data = str_replace( '{{stroke_dasharray}}', ( 2 * $properties->shape_width ), $template_data );
					$template_data = str_replace( '{{stroke_dasharray_06}}', ( 3 * $properties->shape_width ) . ', ' . ( 3 * $properties->shape_width ), $template_data );
				}

				if ( isset( $properties->sec_shape_color ) ) {
					$template_data = str_replace( '{{sec_shape_color}}', $properties->sec_shape_color, $template_data );
				}

				if ( isset( $properties->submit_message ) ) {
					$template_data = str_replace( '{{shape-successs-message}}', $properties->submit_message, $template_data );
				}

				if ( isset( $properties->field_action ) ) {
					if ( 'submit' == $properties->field_action || 'submit_n_close' == $properties->field_action || 'submit_n_goto_step' == $properties->field_action || 'submit_n_goto_url' == $properties->field_action ) {
						$template_data = str_replace( '<div class="cp-shape-input-container"></div>', '<input type="submit" class="cp_shape_submit_hidden">', $template_data );
						$template_data = str_replace( '{{cp_shape_submit_label}}', 'cp_shape_submit_label', $template_data );
					} else {
						$template_data = str_replace( '<div class="cp-shape-input-container"></div>', '', $template_data );
						$template_data = str_replace( '{{cp_shape_submit_label}}', 'cp-shape-label', $template_data );
					}
				}

				break;

			case 'cp_custom_html':
				if ( isset( $properties->custom_html_content ) ) {
					$template_data = str_replace( '{{value}}', urldecode( $properties->custom_html_content ), $template_data );

				} else {
					$template_data = str_replace( '{{value}}', '', $template_data );
				}

				break;

			case 'cp_heading':
				$template_data = str_replace( '{{name}}', $panel_id, $template_data );
				$template_data = str_replace( 'data-field-title="Heading"', '', $template_data );

				break;

			case 'cp_image':
			case 'cp_close_image':
				$img_src      = '';
				$module_image = '';
				$img_alt      = '';
				$module_image = explode( '|', $properties->module_image );

				// if not default image.
				if ( '0' !== $module_image[0] ) {
					$img_src = isset( $module_image[1] ) ? $module_image[1] : '';
					$img_alt = isset( $module_image[3] ) ? $module_image[3] : '';
				}

				// if close image.
				if ( isset( $properties->close_image_type ) ) {
					if ( 'predefined' == $properties->close_image_type ) {
						$img_src = CP_V2_BASE_URL . 'assets/' . $properties->module_radio_image;
					} else {
						if ( '0' == $module_image[0] ) {
							// if image is default image.
							$img_src = CP_V2_BASE_URL . 'assets/' . $module_image[1];
						}
					}
				} elseif ( isset( $module_image ) && isset( $module_image[0] ) && '0' == $module_image[0] ) {

					// if image is default image.
					$img_src = CP_V2_BASE_URL . 'assets/' . $module_image[1];
				}

				// $default_img_src = CP_V2_BASE_URL . 'assets/modules/img/cp.png';
				$default_img_src = '';

				$template_data = str_replace( '{{img_src}}', $img_src, $template_data );
				$template_data = str_replace( '{{value}}', $default_img_src, $template_data );
				$template_data = str_replace( '{{name}}', $panel_id, $template_data );
				$template_data = str_replace( array( '{{alt}}', '{{alt_text}}' ), $img_alt, $template_data );

				break;

			case 'cp_close_link':
			case 'cp_close_text':
				if ( isset( $properties->close_image ) ) {
					$close_image   = explode( '|', $properties->close_image );
					$imag_src      = isset( $close_image[ $close_image[0] ] ) ? $close_image[ $close_image[0] ] : '';
					$img_alt       = isset( $close_image[3] ) ? $close_image[3] : '';
					$template_data = str_replace( '{{close-src}}', $imag_src, $template_data );
					$template_data = str_replace( '{{alt}}', $img_alt, $template_data );
				}

				if ( isset( $properties->close_link_title ) ) {
					$template_data = str_replace( '{{close-title}}', $properties->close_link_title, $template_data );
				}

				$template_data = str_replace( '{{name}}', $panel_id, $template_data );
				$template_data = str_replace( '{{hide_class}}', '', $template_data );

				break;

			case 'cp_countdown':
				if ( isset( $properties->timer_type ) ) {
					$template_data = str_replace( '{{timer-type}}', $properties->timer_type, $template_data );
				}

				if ( isset( $properties->fixed_timer_action ) ) {
					$template_data = str_replace( '{{fixed-action}}', $properties->fixed_timer_action, $template_data );
				}

				$show_hide_cls = '';
				if ( isset( $properties->show_months ) && 'true' != $properties->show_months ) {
					$show_hide_cls .= ' cpro-show_months';
				}
				if ( isset( $properties->show_days ) && 'true' != $properties->show_days ) {
					$show_hide_cls .= ' cpro-show_days';
				}
				if ( isset( $properties->show_mins ) && 'true' != $properties->show_mins ) {
					$show_hide_cls .= ' cpro-show_mins';
				}
				if ( isset( $properties->show_seconds ) && 'true' != $properties->show_seconds ) {
					$show_hide_cls .= ' cpro-show_seconds';
				}
				if ( isset( $properties->show_hours ) && 'true' != $properties->show_hours ) {
					$show_hide_cls .= ' cpro-show_hours';
				}

				$template_data = str_replace( '{{show_hide_countdown}}', $show_hide_cls, $template_data );

				$timezone_offset = '';
				if ( isset( $properties->timer_timezone ) ) {
					$timezone        = explode( '#', $properties->timer_timezone );
					$timezone_offset = ( is_array( $timezone ) && isset( $timezone[1] ) ) ? $timezone[1] : '';
					$template_data   = str_replace( '{{timezone}}', $timezone_offset, $template_data );
				}

				if ( isset( $properties->evergreen_timer_action ) ) {
					$template_data = str_replace( '{{evergreen-action}}', $properties->evergreen_timer_action, $template_data );
				}

				/* Fixed data */
				$fixed_timer_data = $properties->fixed_year . '|' . $properties->fixed_month . '|' . $properties->fixed_day . '|' . $properties->fixed_hrs . '|' . $properties->fixed_min;

				$template_data = str_replace( '{{fixed-timer}}', $fixed_timer_data, $template_data );

				/* Evergreen data */
				$evergreen_timer_data = $properties->ever_day . '|' . $properties->ever_hrs . '|' . $properties->ever_min . '|' . $properties->ever_sec;

				$template_data = str_replace( '{{evergreen-timer}}', $evergreen_timer_data, $template_data );

				if ( isset( $properties->display_as ) ) {
					$class .= ' cp-countdown-' . $properties->display_as . ' ';
				}
				break;

			case 'cp_video':
				$video_id     = $properties->video_id;
				$custom_url   = $properties->video_url;
				$vid_autoplay = $properties->video_autoplay;

				if ( 'true' == $vid_autoplay && $is_inline_module ) {

					$video_html = '<iframe class="cpro-video-iframe" id="cp-vid-' . $panel_id . '-' . $style_id . '" src="' . $custom_url . '" ></iframe>';
				} else {

					$video_html = '<video class="cpro-video-iframe" id="cp-vid-' . $panel_id . '-' . $style_id . '" controls><source src="' . $custom_url . '" type="video/mp4"></video>';
				}

				switch ( $properties->video_source ) {
					case 'youtube':
						$yt_source = 'https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&autoplay=0';

						$video_start_at = isset( $properties->video_start_at ) ? $properties->video_start_at : 0;

						$yt_source .= '&start=' . str_replace( 'sec', '', $video_start_at );

						if ( 'true' == $vid_autoplay && $is_inline_module ) {
							$yt_source .= '&autoplay=1';
						}

						if ( isset( $properties->video_controls ) && 'true' != $properties->video_controls ) {
							$yt_source .= '&showinfo=0&controls=0';
						}

						$video_html = '<iframe class="cpro-yt-iframe" id="cp-vid-' . $panel_id . '-' . $style_id . '" src="' . $yt_source . '" allowfullscreen></iframe>';
						break;

					case 'vimeo':
						$vimeo_src = 'https://player.vimeo.com/video/' . $video_id;

						if ( 'true' == $vid_autoplay && $is_inline_module ) {
							$vimeo_src .= '?autoplay=1';
						}

						$video_html = '<iframe src="' . $vimeo_src . '" allowfullscreen></iframe>';
						break;
				}

				$template_data = str_replace( '{{video_html}}', $video_html, $template_data );
				$template_data = str_replace( '{{data-autoplay}}', $vid_autoplay, $template_data );
				$template_data = str_replace( '{{video_source}}', $properties->video_source, $template_data );
				break;

		}

		$template_data = apply_filters( 'cp_after_template_data', $template_data, $properties );

		if ( isset( $properties->field_animation ) ) {

			$anim_class = 'cp-none';

			if ( 'cp-none' != $properties->field_animation ) {

				$anim_class = 'cp-animation';
			}

			$template_data = str_replace( '{{animation}}', $anim_class, $template_data );

		}

		if ( isset( $properties->field_animation ) && 'cp-none' == $properties->field_animation ) {
			$template_data = str_replace(
				array(
					'data-anim-duration="{{anim-duration}}"',
					'data-animation="{{animation-type}}"',
					'data-anim-delay="{{anim-delay}}"',
				),
				'',
				$template_data
			);
		} else {

			if ( isset( $properties->field_animation ) ) {
				$template_data = str_replace( '{{animation-type}}', $properties->field_animation, $template_data );
			}

			if ( isset( $properties->field_animation_duration ) ) {
				$template_data = str_replace( '{{anim-duration}}', $properties->field_animation_duration, $template_data );
			}

			if ( isset( $properties->field_animation_delay ) ) {
				$template_data = str_replace( '{{anim-delay}}', $properties->field_animation_delay, $template_data );
			}
		}

		if ( isset( $properties->field_custom_class ) ) {
			$template_data = str_replace( '{{custom_class}}', $properties->field_custom_class, $template_data );
		}

		if ( isset( $properties->text_content ) ) {
			$properties->text_content = str_replace( '+', '%2B', $properties->text_content );
			$template_data            = str_replace( '{{value}}', urldecode( $properties->text_content ), $template_data );
		} else {
			$template_data = str_replace( '{{value}}', '', $template_data );
		}

		$step_number = isset( $properties->btn_step ) ? $properties->btn_step : '';

		$template_data = str_replace( '{{data-step}}', $step_number, $template_data );

		if ( isset( $properties->field_action ) ) {
			if ( 'goto_url' !== $properties->field_action && 'submit_n_goto_url' !== $properties->field_action ) {

				$template_data = str_replace(
					array(
						'data-redirect="{{data-redirect}}"',
						'data-redirect-target="{{data-redirect-target}}"',
					),
					'',
					$template_data
				);

			} else {

				$btn_url       = isset( $properties->btn_url ) ? esc_url( $properties->btn_url ) : '';
				$template_data = str_replace( '{{data-redirect}}', $btn_url, $template_data );

				$btn_url_target = isset( $properties->btn_url_target ) ? $properties->btn_url_target : '';
				$template_data  = str_replace( '{{data-redirect-target}}', $btn_url_target, $template_data );
			}
		}

		if ( isset( $properties->field_action ) ) {
			$template_data = str_replace( '{{field_action}}', $properties->field_action, $template_data );
		}

		if ( isset( $properties->hide_on_mobile ) && 'yes' == $properties->hide_on_mobile ) {
			$class .= ' cp-invisible-on-mobile ';
		}

		if ( isset( $properties->non_clickable ) && 'true' == $properties->non_clickable ) {
			$class .= ' cp-disable-click ';
		}

		if ( isset( $properties->respective_to ) && 'true' == $properties->respective_to ) {
			$class .= ' cpro-overlay-field ';
		}

		if ( isset( $properties->radio_orientation ) ) {
			$class .= $properties->radio_orientation;
		}

		if ( isset( $properties->checkbox_orientation ) ) {
			$class .= $properties->checkbox_orientation;
		}

		if ( isset( $properties->count_as_conversion ) && 'true' == $properties->count_as_conversion ) {
			$class .= ' cpro_count_conversion';
		}

		$template_data = str_replace( '{{class}}', $class, $template_data );
		$template_data = str_replace( '{{contenteditable}}', 'false', $template_data );
		$template_data = str_replace( '{{custom_class}}', '', $template_data );

		if ( isset( $template_data ) ) {

			$style = cp_generate_style( $properties, $panel_id, $style_id, $properties->type, 'desktop' );

			if ( 'yes' == $mobile_resp ) {

				$style .= '@media ( max-width: ' . $mobile_break_pt . 'px ) {';

				$style .= cp_generate_style( $properties, $panel_id, $style_id, $properties->type, 'mobile' );
				$style .= '}';
			}
			$data = array(
				'html'  => $template_data,
				'style' => $style,
			);
		}
	}

	return $data;

}

/**
 * Function Name: hex_to_rgb.
 * Function Description: hex_to_rgb.
 *
 * @param string $hex string parameter.
 */
function hex_to_rgb( $hex ) {
	return array(
		'r' => hexdec( substr( $hex, 0, 2 ) ),
		'g' => hexdec( substr( $hex, 2, 2 ) ),
		'b' => hexdec( substr( $hex, 4, 2 ) ),
	);
}


/**
 * Function Name: cp_get_device_value.
 * Function Description: cp_get_device_value.
 *
 * @param string $style_array string parameter.
 * @param string $device string parameter.
 */
function cp_get_device_value( $style_array, $device ) {

	$include_arr = Cp_V2_Model::$mobile_include_opt;

	$device_index = 0;

	if ( 'mobile' == $device ) {
		$device_index = 1;
	}

	$style_array_d = clone $style_array;

	foreach ( $style_array_d as $key => $value ) {
		if ( in_array( $key, $include_arr ) && is_array( $value ) ) {
			if ( isset( $value[ $device_index ] ) ) {
				$style_array_d->$key = $value[ $device_index ];
			} else {
				$style_array_d->$key = $value[0];
			}
		}
	}

	return $style_array_d;
}

/**
 * Function Name: cp_generate_style.
 * Function Description: Generate CSS style for particular field.
 *
 * @param string $style_array string parameter.
 * @param string $panel_id string parameter.
 * @param string $style_id string parameter.
 * @param string $type string parameter.
 * @param string $device_index string parameter.
 */
function cp_generate_style( $style_array, $panel_id, $style_id, $type, $device_index ) {

	$style_array         = cp_get_device_value( $style_array, $device_index );
	$step_dependent_opts = Cp_V2_Model::$step_dependent_options;
	$module_type         = get_post_meta( $style_id, 'cp_module_type', true );

	$is_inherit_bg    = isset( $style_array->inherit_bg_prop ) ? $style_array->inherit_bg_prop : '1';
	$bg_type          = ( isset( $style_array->background_type ) && '' !== $style_array->background_type ) ? $style_array->background_type : '';
	$opt_bg           = ( isset( $style_array->opt_bg ) && '' !== $style_array->opt_bg ) ? $style_array->opt_bg : '';
	$background_color = ( isset( $style_array->panel_background_color ) && '' !== $style_array->panel_background_color ) ? $style_array->panel_background_color : '';
	$gradient_arr     = array();

	$btn_gradient_types             = ( isset( $style_array->btn_gradient_types ) && '' !== $style_array->btn_gradient_types ) ? $style_array->btn_gradient_types : '';
	$btn_gradient_radial_dir        = ( isset( $style_array->btn_gradient_radial_dir ) && '' !== $style_array->btn_gradient_radial_dir ) ? $style_array->btn_gradient_radial_dir : '';
	$overlay_gradient_type          = ( isset( $style_array->overlay_panel_gradient_type ) && '' !== $style_array->overlay_panel_gradient_type ) ? $style_array->overlay_panel_gradient_type : 'lineargradient';
	$rad_overlay_gradient_direction = ( isset( $style_array->radial_overlay_gradient_direction ) && '' !== $style_array->radial_overlay_gradient_direction ) ? $style_array->radial_overlay_gradient_direction : 'center_center';

	/* Gradient buttton hover colors */
	$btn_gradient_types_hover = ( isset( $style_array->btn_gradient_types_hover ) && '' !== $style_array->btn_gradient_types_hover ) ? $style_array->btn_gradient_types_hover : '';

	$btn_gradient_radial_dir_hover = ( isset( $style_array->btn_gradient_radial_dir_hover ) && '' !== $style_array->btn_gradient_radial_dir_hover ) ? $style_array->btn_gradient_radial_dir_hover : '';

	$style                = '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target { ';
	$step_dependent_style = '';
	$bg_overlay_style     = '';

	// if panel properties style to render.
	if ( strpos( $panel_id, 'panel-' ) !== false ) {

		if ( 'info_bar' == $module_type || 'welcome_mat' == $module_type || 'full_screen' == $module_type ) {
			$style = '.cp_style_' . $style_id . ' .cp-popup{ ';

			$bg_overlay_style .= '.cp_style_' . $style_id . ' .cpro-fs-overlay{ ';

		} else {
			$style = '.cp_style_' . $style_id . ' .cp-popup-content{ ';
		}

		$step_dependent_style = '.cp_style_' . $style_id . ' .cp-popup-content { ';

		if ( 'panel-1' != $panel_id ) {
			$step_dependent_style = '.cp_style_' . $style_id . ' .cp-popup-content.cp-' . $panel_id . '{ ';
		}
	}

	$form_field_select_field_style   = '';
	$form_field_placeholder_webstyle = '';
	$form_field_placeholder_mozstyle = '';
	$form_field_focus_style          = '';

	if ( strpos( $panel_id, 'form_field' ) !== false ) {
		$style = '.cp_style_' . $style_id . ' .cp-popup .cpro-form .cp-form-input-field{ ';

		$form_field_select_field_style .= '.cp_style_' . $style_id . " .cp-popup .cpro-form .cp-form-input-field input[type='radio'], .cp_style_" . $style_id . " .cp-popup .cpro-form .cp-form-input-field input[type='checkbox'] {";

		$form_field_focus_style .= '.cp_style_' . $style_id . ' .cp-popup .cpro-form .cp-form-input-field:focus {';

		$form_field_placeholder_webstyle .= '.cp_style_' . $style_id . ' .cp-popup .cpro-form .cp-form-input-field::-webkit-input-placeholder {';

		$form_field_placeholder_mozstyle .= '.cp_style_' . $style_id . ' .cp-popup .cpro-form .cp-form-input-field::-moz-placeholder  {';
	}

	$extra_style       = '';
	$field_style       = '';
	$extra_hover_style = '';
	$hover_style       = '';

	$gradient_angle     = isset( $style_array->btn_gradient_linear_angle ) ? $style_array->btn_gradient_linear_angle : '';
	$btn_back_color     = isset( $style_array->btn_back_color ) ? $style_array->btn_back_color : '';
	$sec_btn_back_color = isset( $style_array->sec_btn_back_color ) ? $style_array->sec_btn_back_color : '';
	$btn_gradient_loc_1 = isset( $style_array->btn_gradient_loc_1 ) ? $style_array->btn_gradient_loc_1 : '';
	$btn_gradient_loc_2 = isset( $style_array->btn_gradient_loc_2 ) ? $style_array->btn_gradient_loc_2 : '';

	$exc_opts_arr = array( 'gradient-background', 'close-link', 'inner-html', 'class-name', 'class', 'dropdown-options', 'padding', 'overlay-color', 'background-type', 'background-opt', 'entry-animation', 'exit-animation', 'gradient-angle', 'gradient-type', 'position', 'btn-gradient-angle', 'btn-gradient-bg1', 'btn-gradient-bg2', 'overlay-gradient-type', 'lighten-color', 'gradient-lighter-location', 'darken-color', 'panel-gradient-type', 'gradient-darker-location', 'radial-gradient-direction', 'radial-gradient-direction', 'overlay-lighter-color', 'overlay-lighter-location', 'overlay-darker-color', 'overlay-darker-location', 'overlay-panel-gradient-type', 'radial-overlay-gradient-direction', 'overlay-gradient-angle', 'radio-options', 'panel-img-overlay-color', 'toggle-text', 'inherit-bg', 'btn-gradient-loc-1', 'btn-gradient-loc-2', 'btn-gradient-radial-dir', 'btn-gradient-bg1-hover', 'btn-gradient-bg2-hover', 'btn-gradient-loc-1-hover', 'btn-gradient-loc-2-hover', 'btn-gradient-type-hover', 'btn-gradient-radial-dir-hover', 'btn-gradient-angle-hover', 'removeAnimClass', 'margin', 'video-source', 'video-id', 'close-image-type', 'radio-image', 'btn-gradient-hover-options', 'radio-orientation', 'checkbox-options', 'checkbox-orientation', 'active-border-color', 'countdown-number-color', 'countdown-text-color', 'inside-outside', 'countdown-background', 'countdown-border-style', 'countdown-border-color', 'countdown-border-width', 'countdown-border-radius', 'countdown-padding', 'text-space', 'countdown-text-font-size', 'countdown-number-font-size' );

	if ( isset( $style_array->map_style ) ) {

		foreach ( $style_array->map_style as $style_prop ) {

			$map_style_value = '';
			if ( isset( $style_prop->name ) ) {
				$map_style_value = $style_array->{$style_prop->name};
			}

			if ( isset( $style_prop->parameter ) ) {
				$style_prop->parameter = str_replace( '_', '-', $style_prop->parameter );
			} else {
				$style_prop->parameter = '';
			}

			$unit   = ( isset( $style_prop->unit ) && '' !== $style_prop->unit ) ? $style_prop->unit : '';
			$target = ( isset( $style_prop->target ) && '' !== $style_prop->target ) ? $style_prop->target : '';

			if ( in_array( $style_prop->name, $step_dependent_opts ) &&
				! in_array( $style_prop->parameter, $exc_opts_arr ) ) {

				switch ( $style_prop->parameter ) {

					case 'background-color':
						$lightn_color = ( isset( $style_array->panel_lighter_color ) && '' !== $style_array->panel_lighter_color ) ? $style_array->panel_lighter_color : '#fff';

						$dark_color = ( isset( $style_array->panel_darker_color ) && '' !== $style_array->panel_darker_color ) ? $style_array->panel_darker_color : '';

						$img_overlay_bg = ( isset( $style_array->panel_img_overlay_color ) && '' !== $style_array->panel_img_overlay_color ) ? $style_array->panel_img_overlay_color : '';

						if ( $lightn_color !== $background_color ) {
							array_push( $gradient_arr, $lightn_color );
						} elseif ( $dark_color !== $background_color ) {
							array_push( $gradient_arr, $dark_color );
						} elseif ( $img_overlay_bg !== $background_color ) {
							array_push( $gradient_arr, $img_overlay_bg );
						}

						if ( '0' == $is_inherit_bg || 'panel-1' == $panel_id ) {
							if ( 'gradient' == $bg_type ) {

								$darker_location = ( isset( $style_array->gradient_darker_location ) && '' !== $style_array->gradient_darker_location ) ? $style_array->gradient_darker_location : '100';

								$lighter_location = ( isset( $style_array->gradient_lighter_location ) && '' !== $style_array->gradient_lighter_location ) ? $style_array->gradient_lighter_location : '0';

								$gradient_type = ( isset( $style_array->panel_gradient_type ) && '' !== $style_array->panel_gradient_type ) ? $style_array->panel_gradient_type : 'lineargradient';

								$angle = ( isset( $style_array->gradient_angle ) && '' !== $style_array->gradient_angle ) ? $style_array->gradient_angle : '0';

								$gradient_direction = ( isset( $style_array->radial_panel_gradient_direction ) && '' !== $style_array->radial_panel_gradient_direction ) ? $style_array->radial_panel_gradient_direction : 'center_center';

								if ( 'info_bar' !== $module_type && 'welcome_mat' != $module_type && 'full_screen' != $module_type ) {

									if ( 'lineargradient' == $gradient_type ) {
										$step_dependent_style .= cp_apply_gradient_bg( $lightn_color, $lighter_location, $dark_color, $darker_location, $angle );
									} elseif ( 'radialgradient' == $gradient_type ) {
										$step_dependent_style .= cp_apply_gradient_bg_rad( $gradient_direction, $lightn_color, $lighter_location, $dark_color, $darker_location );
									}
								} else {
									if ( 'lineargradient' == $gradient_type ) {
										$style .= cp_apply_gradient_bg( $lightn_color, $lighter_location, $dark_color, $darker_location, $angle );
									} elseif ( 'radialgradient' == $gradient_type ) {
										$style .= cp_apply_gradient_bg_rad( $gradient_direction, $lightn_color, $lighter_location, $dark_color, $darker_location );
									}
								}
							} elseif ( 'image' == $bg_type ) {

								if ( 'info_bar' == $module_type ) {
									$style            .= $style_prop->parameter . ':' . $img_overlay_bg . ';';
									$bg_overlay_style .= $style_prop->parameter . ':' . $img_overlay_bg . ';';

								} elseif ( 'welcome_mat' == $module_type || 'full_screen' == $module_type ) {
										$bg_overlay_style .= $style_prop->parameter . ':' . $img_overlay_bg . ';';
								} else {
										$step_dependent_style .= $style_prop->parameter . ':' . $img_overlay_bg . ';';
								}
							} else {

								if ( ! in_array( $val, $gradient_arr ) ) {

									if ( 'info_bar' == $module_type || 'welcome_mat' == $module_type || 'full_screen' == $module_type ) {

										$style .= $style_prop->parameter . ':' . $map_style_value . $unit . ';';
									} else {

										$step_dependent_style .= $style_prop->parameter . ':' . $map_style_value . $unit . ';';
									}
								}
							}
						}

						break;

					case 'background-image':
						if ( '0' == $is_inherit_bg || 'panel-1' == $panel_id ) {

							if ( 'info_bar' == $module_type || 'welcome_mat' == $module_type || 'full_screen' == $module_type ) {
								$style .= cp_generate_bg_style( $bg_type, $map_style_value, $style_prop->parameter, $opt_bg );
							} else {
								$step_dependent_style .= cp_generate_bg_style( $bg_type, $map_style_value, $style_prop->parameter, $opt_bg );
							}
						}
						break;

					default:
						$step_dependent_style .= $style_prop->parameter . ':' . $map_style_value . $unit . ';';
						break;
				}

				continue;
			}

			if ( isset( $btn_gradient_types_hover ) && '' !== $btn_gradient_types_hover ) {
				$parameter = isset( $new_parameter[1] ) ? $new_parameter[1] : $style_prop->parameter;

				if ( 'btn-gradient-type-hover' == $parameter ) {

					$btn_gradient_hover_options = ( isset( $style_array->btn_gradient_hover_options ) && '' !== $style_array->btn_gradient_hover_options ) ? $style_array->btn_gradient_hover_options : '';

					if ( 'true' == $btn_gradient_hover_options ) {

						$extra_hover_style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' button:hover { ';

						$btn_gradient_loc_1_hover = ( isset( $style_array->btn_gradient_loc_1_hover ) && '' !== $style_array->btn_gradient_loc_1_hover ) ? $style_array->btn_gradient_loc_1_hover : '';

						$btn_back_color_hover = ( isset( $style_array->btn_back_color_hover ) && '' !== $style_array->btn_back_color_hover ) ? $style_array->btn_back_color_hover : '';

						$sec_btn_back_color_hover = ( isset( $style_array->sec_btn_back_color_hover ) && '' !== $style_array->sec_btn_back_color_hover ) ? $style_array->sec_btn_back_color_hover : '';

						$btn_gradient_loc_2_hover = ( isset( $style_array->btn_gradient_loc_2_hover ) && '' !== $style_array->btn_gradient_loc_2_hover ) ? $style_array->btn_gradient_loc_2_hover : '';

						$btn_gradient_linear_angle_hover = ( isset( $style_array->btn_gradient_linear_angle_hover ) && '' !== $style_array->btn_gradient_linear_angle_hover ) ? $style_array->btn_gradient_linear_angle_hover : '';

						if ( 'lineargradient' == $btn_gradient_types_hover ) {
							$extra_hover_style .= cp_apply_gradient_bg( $btn_back_color_hover, $btn_gradient_loc_1_hover, $sec_btn_back_color_hover, $btn_gradient_loc_2_hover, $btn_gradient_linear_angle_hover );
						} else {
							$extra_hover_style .= cp_apply_gradient_bg_rad( $btn_gradient_radial_dir_hover, $btn_back_color_hover, $btn_gradient_loc_1_hover, $sec_btn_back_color_hover, $btn_gradient_loc_2_hover );
						}

						$extra_hover_style .= '}';
					}
				}
			}

			if ( isset( $style_prop->onhover ) && $style_prop->onhover ) {

				if ( isset( $target ) && '' != $target && trim( $target ) != '.cp-target' ) {

					$muliple_target = explode( ',', $target );
					foreach ( $muliple_target as $key => $value ) {

						$new_parameter     = explode( '|', $value );
						$new_parameter_arr = explode( ' ', $new_parameter[0] );

						foreach ( $new_parameter_arr as $key => $value ) {
							if ( strpos( $value, 'cp-target' ) >= 0 ) {
								$new_parameter_arr[ $key ] .= ':hover';
								break;
							}
						}

						$new_parameter[0] = implode( ' ', $new_parameter_arr );

						$extra_hover_style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' ' . $new_parameter[0] . ' { ';

						$parameter = isset( $new_parameter[1] ) ? $new_parameter[1] : $style_prop->parameter;

						$exc_arr = array( 'gradient-background', 'close-link', 'inner-html', 'class-name', 'class', 'overlay-color', 'background-type', 'padding' );

						$val = $map_style_value;

						if ( '' !== $val ) {

							if ( ! in_array( $parameter, $exc_arr ) ) {
								$extra_hover_style .= $parameter . ':' . $val . $unit . ';';
							}
						}

						$extra_hover_style .= '}';
					}
				} else {

					$exc_arr      = array( 'gradient-background', 'close-link', 'inner-html', 'class-name', 'class', 'dropdown-options', 'padding', 'overlay-color', 'background-type' );
					$val          = $map_style_value;
					$parameter    = $style_prop->parameter;
					$paenl_bg_css = '';

					if ( '' !== $val ) {

						if ( ! in_array( $parameter, $exc_arr ) ) {

							$hover_style .= $parameter . ':' . $map_style_value . $unit . ';';
						}
					}
				}
			} else {

				if ( isset( $target ) && '' != $target && trim( $target ) != '.cp-target' && trim( $target ) != '.cp-field-html-data' ) {

					$muliple_target = explode( ',', $target );
					foreach ( $muliple_target as $key => $value ) {

						$new_parameter = explode( '|', $value );

						if ( 'toggle' == $panel_id ) {
							if ( 'info_bar' == $module_type ) {
								$extra_style .= '.cp_style_' . $style_id . ' .cp-open-infobar-toggle{ ';
							} else {
								$extra_style .= '.cp_style_' . $style_id . ' .cp-open-toggle{ ';
							}
						} else {
							$extra_style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' ' . $new_parameter[0] . ' { ';
						}

						$parameter = isset( $new_parameter[1] ) ? $new_parameter[1] : $style_prop->parameter;

						$exc_arr = array( 'gradient-background', 'close-link', 'inner-html', 'class-name', 'class', 'overlay-color', 'background-type', 'padding', 'entry-animation', 'exit-animation', 'gradient-angle', 'gradient-type', 'position', 'btn-gradient-angle', 'btn-gradient-bg1', 'btn-gradient-bg2', 'overlay-gradient-type', 'toggle-type', 'toggle_minimizer', 'toggle-text', 'inherit-bg' );

						$val = $map_style_value;

						if ( '' !== $val ) {
							if ( 'border-width' == $parameter || 'border-radius' == $parameter || 'padding' == $parameter ) {

								$extra_style .= cp_generate_multi_input_result( $parameter, $val );

							} elseif ( 'box-shadow' == $parameter ) {

								if ( 'cp_shape' != $style_array->type ) {
									$extra_style .= cp_generate_box_shadow( $val );
								}
							} elseif ( 'btn-gradient-type' == $parameter ) {

								$lighter_color = $btn_back_color;
								$darker_color  = $sec_btn_back_color;

								if ( '' == $lighter_color ) {
									$lighter_color = 'transparent';
								}
								if ( '' == $darker_color ) {
									$darker_color = 'transparent';
								}

								if ( 'lineargradient' == $btn_gradient_types ) {
									$style .= cp_apply_gradient_bg( $lighter_color, $btn_gradient_loc_1, $darker_color, $btn_gradient_loc_2, $gradient_angle );
								} else {
									$style .= cp_apply_gradient_bg_rad( $btn_gradient_radial_dir, $lighter_color, $btn_gradient_loc_1, $darker_color, $btn_gradient_loc_2 );
								}
							} elseif ( 'overlay-gradient-type' == $parameter ) {

								if ( 'lineargradient' == $overlay_gradient_type ) {
									$style .= cp_overlay_gradient_bg( $style_id, $style_array );
								} elseif ( 'radialgradient' == $overlay_gradient_type ) {
									$style .= cp_overlay_gradient_bg_rad( $style_id, $style_array );
								}
							} elseif ( 'background-image' == $parameter ) {

								$extra_style .= cp_generate_bg_style( $bg_type, $image, $parameter, $opt_bg );

							} elseif ( 'font-family' == $parameter ) {
								$font         = explode( ':', $val );
								$font_family  = $font[0];
								$font_new     = ( isset( $font[1] ) && '' != $font[1] ) ? $font[1] : '';
								$font_weight  = ( 'regular' == $font_new ) ? 'normal' : $font_new;
								$extra_style .= $parameter . ':' . $font_family . ';';
								$extra_style .= 'font-weight' . ':' . $font_weight . ';';

							} elseif ( ! in_array( $parameter, $exc_arr ) ) {
								if ( 'height' == $parameter && 'toggle' == $target ) {
									$extra_style .= 'line-height' . ':' . $map_style_value . $unit . ';';
								}

								if ( 'placeholder' == $target ) {
									$form_field_placeholder_webstyle .= $parameter . ':' . $val . ';';
									$form_field_placeholder_mozstyle .= $parameter . ':' . $val . ';';

								}

								$extra_style .= $parameter . ':' . $val . $unit . ';';
							}
						}

						$extra_style .= '}';
					}
				} elseif ( trim( $target ) == '.cp-field-html-data' ) {

					$parameter = $style_prop->parameter;

					if ( 'transform' == $parameter ) {
						$field_style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-rotate-wrap{ ';
						$field_style .= $parameter . ':' . 'rotate( ' . $map_style_value . 'deg)' . ';';
						$field_style .= '}';
					}
					if ( 'cp_shape' == $style_array->type && isset( $style_array->field_box_shadow ) ) {
						$field_style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' { ';
						$field_style .= cp_generate_drop_shadow( $style_array->field_box_shadow );
						$field_style .= '}';
					}
				} else {

					$val           = isset( $map_style_value ) ? $map_style_value : '';
					$parameter     = $style_prop->parameter;
					$property_name = $style_prop->name;
					$paenl_bg_css  = '';

					if ( '' !== $val ) {

						if ( 'border-width' == $parameter || 'border-radius' == $parameter || 'padding' == $parameter ) {
							$style .= cp_generate_multi_input_result( $parameter, $val );
						} elseif ( 'box-shadow' == $parameter || 'panel-box-shadow' == $parameter ) {
							$style .= cp_generate_box_shadow( $val );
						} elseif ( 'btn-gradient-type' == $parameter ) {
							$lighter_color = $btn_back_color;
							$darker_color  = $sec_btn_back_color;

							if ( '' == $lighter_color ) {
								$lighter_color = 'transparent';
							}
							if ( '' == $darker_color ) {
								$darker_color = 'transparent';
							}

							if ( 'lineargradient' == $btn_gradient_types ) {
								$style .= cp_apply_gradient_bg( $lighter_color, $btn_gradient_loc_1, $darker_color, $btn_gradient_loc_2, $gradient_angle );
							} elseif ( 'radialgradient' == $btn_gradient_types ) {
								$style .= cp_apply_gradient_bg_rad( $btn_gradient_radial_dir, $lighter_color, $btn_gradient_loc_1, $darker_color, $btn_gradient_loc_2 );
							}
						} elseif ( 'overlay-gradient-type' == $parameter ) {
							$overlay_style = '';
							if ( 'lineargradient' == $overlay_gradient_type ) {
								$overlay_style .= cp_overlay_gradient_bg( $style_id, $style_array );
							} elseif ( 'radialgradient' == $overlay_gradient_type ) {
								$overlay_style .= cp_overlay_gradient_bg_rad( $style_id, $style_array );
							}
						} elseif ( 'background-image' == $parameter ) {

							$style .= cp_generate_bg_style( $bg_type, $map_style_value, $parameter, $opt_bg );

						} elseif ( 'background-color' == $parameter ) {

							if ( strpos( $panel_id, 'panel-' ) == false ) {

								if ( strpos( $panel_id, 'form_field' ) !== false ) {
									$form_field_select_field_style .= $parameter . ':' . $map_style_value . ';';
								}

								$style .= $parameter . ':' . $map_style_value . ';';
							}
						} elseif ( 'font-family' == $parameter ) {
							$font        = explode( ':', $val );
							$font_family = $font[0];
							$font_new    = ( isset( $font[1] ) && '' != $font[1] ) ? $font[1] : '';
							$font_weight = ( 'regular' == $font_new ) ? 'normal' : $font_new;
							$style      .= $parameter . ':' . $font_family . ';';
							$style      .= 'font-weight' . ':' . $font_weight . ';';

						} elseif ( ! in_array( $parameter, $exc_opts_arr ) ) {
							if ( 'height' == $parameter && 'cp_toggle' == $type ) {
								$style .= 'line-height' . ':' . $map_style_value . $unit . ';';
							}

							if ( 'height' == $parameter || 'width' == $parameter ) {

								if ( 0 == $map_style_value ) {
									$map_style_value = 'auto';
									$unit            = '';
								}
							}

							if ( strpos( $panel_id, 'form_field' ) !== false && 'color' == $parameter ) {
								$form_field_select_field_style .= $parameter . ':' . $map_style_value . ';';
							}

							if ( 'active-border-color' == $parameter && strpos( $panel_id, 'form_field' ) !== false ) {
								$form_field_focus_style .= 'border-color: ' . $map_style_value;
							}

							if ( 'active-border-color' == $parameter && strpos( $panel_id, 'form_field' ) !== false ) {
								$form_field_focus_style .= 'border-color: ' . $map_style_value;
							}

							if ( 'height' == $parameter && ( isset( $style_array->shape_preset ) ) && ( 'line05' == $style_array->shape_preset || 'line06' == $style_array->shape_preset || 'line07' == $style_array->shape_preset ) ) {
								$style .= 'height:' . $style_array->shape_width . 'px;';
							} else {
								$style .= $parameter . ':' . $map_style_value . $unit . ';';
							}
						}
					}
				}
			}
		}
	}

	$style .= '}';
	foreach ( $style_array->map_style as $style_prop ) {

		switch ( $style_prop->parameter ) {
			case 'radio-size':
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-radio-wrap input[type=radio] ' . ' { ';
				$style .= 'height' . ':' . $map_style_value . 'px;';
				$style .= 'width' . ':' . $map_style_value . 'px;';
				$style .= ' }';
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-radio-wrap' . ' { ';
				$style .= 'line-height' . ':' . $map_style_value . 'px;';
				$style .= ' }';
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-radio-wrap input[type=radio]::before ' . ' { ';

				$beforeval = ( ( $map_style_value - 10 ) < 1 ) ? 6 : ( $map_style_value - 10 );

				$style .= 'height' . ':' . $beforeval . 'px;';
				$style .= 'width' . ':' . $beforeval . 'px;';
				$style .= ' }';
				break;

			case 'countdown-background':
				$val = $style_array->{$style_prop->name};
				$cls = '';
				if ( 'inside' == $style_array->inside_outside ) {
					$cls = '.cp-countdown-digit-wrap';
				}
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-holding ' . $cls . ' { ';
				$style .= 'background-color' . ':' . $val;
				$style .= ' }';
				break;

			case 'countdown-border-style':
				$val = $style_array->{$style_prop->name};
				$cls = '';
				if ( 'inside' == $style_array->inside_outside ) {
					$cls = '.cp-countdown-digit-wrap';
				}
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-holding ' . $cls . ' { ';
				$style .= 'border-style' . ':' . $val;
				$style .= ' }';
				break;

			case 'countdown-padding':
				$val = $style_array->{$style_prop->name};
				$cls = '';
				if ( 'inside' == $style_array->inside_outside ) {
					$cls = '.cp-countdown-digit-wrap';
				}
				$op     = cp_generate_multi_input_result( 'padding', $val );
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-holding ' . $cls . ' { ';
				$style .= $op;
				$style .= ' }';
				break;

			case 'countdown-border-width':
				$val = $style_array->{$style_prop->name};
				$cls = '';
				if ( 'inside' == $style_array->inside_outside ) {
					$cls = '.cp-countdown-digit-wrap';
				}
				$op     = cp_generate_multi_input_result( 'border-width', $val );
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-holding ' . $cls . ' { ';
				$style .= $op;
				$style .= ' }';
				break;

			case 'countdown-border-radius':
				$val = $style_array->{$style_prop->name};
				$cls = '';
				if ( 'inside' == $style_array->inside_outside ) {
					$cls = '.cp-countdown-digit-wrap';
				}
				$op     = cp_generate_multi_input_result( 'border-radius', $val );
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-holding ' . $cls . ' { ';
				$style .= $op;
				$style .= ' }';
				break;

			case 'text-space':
				$val    = $style_array->{$style_prop->name};
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-unit-wrap { ';
				$style .= 'margin-top: ' . $val . 'px;';
				$style .= ' }';
				break;

			case 'countdown-number-font-size':
				$val    = $style_array->{$style_prop->name};
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-digit, #' . $panel_id . '-' . $style_id . ' .cp-target { ';
				$style .= 'font-size: ' . $val;
				$style .= ' }';
				break;

			case 'countdown-text-font-size':
				$val    = $style_array->{$style_prop->name};
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-unit { ';
				$style .= 'font-size: ' . $val;
				$style .= ' }';
				break;

			case 'countdown-number-color':
				$val    = $style_array->{$style_prop->name};
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-digit-wrap .cp-countdown-digit { ';
				$style .= 'color: ' . $val;
				$style .= ' }';
				break;

			case 'countdown-text-color':
				$val    = $style_array->{$style_prop->name};
				$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target .cp-countdown-unit-wrap .cp-countdown-unit { ';
				$style .= 'color: ' . $val;
				$style .= ' }';
				break;

			default:
				break;
		}
	}

	if ( strpos( $panel_id, 'form_field' ) !== false ) {
		$form_field_select_field_style   .= '}';
		$form_field_focus_style          .= '}';
		$form_field_placeholder_webstyle .= '}';
		$form_field_placeholder_mozstyle .= '}';
	}

	$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' .cp-target:hover { ';

	$style .= $hover_style;

	$style .= '}';

	$style .= $extra_style . $field_style . $extra_hover_style . $form_field_select_field_style . $form_field_focus_style . $form_field_placeholder_webstyle . $form_field_placeholder_mozstyle;

	$style .= '.cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . ' { ';

	$respective_to_overlay = isset( $style_array->respective_to ) ? $style_array->respective_to : false;

	foreach ( $style_array as $key => $style_prop ) {

		if ( 'position' == $key ) {

			$unit = 'px';
			if ( 'true' == $respective_to_overlay ) {
				$unit = '%';
			}

			if ( intval( $style_prop->x ) > 50 && isset( $style_prop->right ) && 'no' != $style_prop->right ) {
				$style .= 'right: ' . $style_prop->right . $unit . ';';
			} else {
				$style .= 'left: ' . $style_prop->x . $unit . ';';
			}

			if ( intval( $style_prop->y ) > 50 && isset( $style_prop->bottom ) && 'no' != $style_prop->bottom ) {
				$style .= 'bottom: ' . $style_prop->bottom . $unit . ';';
			} else {
				$style .= 'top: ' . $style_prop->y . $unit . ';';
			}
		}
	}

	if ( isset( $style_array->layerindex ) ) {
		$style .= 'z-index' . ':' . $style_array->layerindex . ';';
	}

	$style .= '}';

	/* Toggle field Admin Bar Enabled Style */
	$respective_to = isset( $style_array->respective_to ) ? $style_array->respective_to : '';
	if ( 'cp_toggle' == $style_array->type && 'true' == $respective_to && isset( $style_array->position->y ) && intval( $style_array->position->y ) < 50 ) {
		$style .= '.admin-bar:not(.wp-admin) .cp_style_' . $style_id . ' #' . $panel_id . '-' . $style_id . '{ ';
		$style .= 'top: calc(' . $style_array->position->y . '% + 32px );';
		$style .= '}';
	}

	// style for panel.
	if ( strpos( $panel_id, 'panel-' ) !== false ) {

		$overlay_type = ( isset( $style_array->overlay_gradient_type ) && '' !== $style_array->overlay_gradient_type ) ? $style_array->overlay_gradient_type : 'lineargradient';

		if ( 'color' == $overlay_type ) {

			if ( isset( $style_array->panel_overlay_color ) ) {
				$style .= '.cp_style_' . $style_id . ' .cpro-overlay{';
				$style .= 'background:' . $style_array->panel_overlay_color . ';';
				$style .= '}';
			}

			if ( isset( $style_array->panel_height ) ) {
				if ( 'full_screen' != $module_type ) {
					$style .= '.cp_style_' . $style_id . ' .cp-popup-wrapper .cpro-overlay {';
					$style .= 'height:' . ( $style_array->panel_height ) . 'px;';
					$style .= '}';
				} else {
					$style .= '.cp_style_' . $style_id . ' .cp-popup-wrapper .cp-panel-content {';
					$style .= 'height:' . ( $style_array->panel_height ) . 'px;';
					$style .= '}';
				}
			}
		} elseif ( 'gradient' == $overlay_type ) {
			if ( isset( $style_array->panel_overlay_color ) ) {
				$style .= $overlay_style;
			}
		}

		if ( isset( $style_array->panel_width ) && ( 'info_bar' == $module_type || 'welcome_mat' == $module_type || 'full_screen' == $module_type ) ) {

			$style .= '.cp_style_' . $style_id . ' .cp-popup-wrapper .cp-panel-content {';
			$style .= 'max-width:' . ( $style_array->panel_width ) . 'px;';
			$style .= '}';
		}

		if ( isset( $style_array->panel_margin ) ) {

			$style .= '.cp-popup-container.cp_style_' . $style_id . ' {';
			$style .= cp_generate_multi_input_result( 'margin', $style_array->panel_margin );
			$style .= '}';
		}
	}

	if ( '' !== $step_dependent_style ) {
		$step_dependent_style .= '}';
	}

	if ( '' !== $bg_overlay_style ) {
		$bg_overlay_style .= '}';
	}

	$style .= $bg_overlay_style;
	$style .= $step_dependent_style;

	return $style;
}

if ( ! function_exists( 'cp_render_popup' ) ) {
	/**
	 * Function Name: cp_render_popup.
	 * Function Description:cp_render_popup.
	 *
	 * @param string $atts string parameter.
	 * @param string $content string parameter.
	 */
	function cp_render_popup( $atts, $content = null ) {
		ob_start();
		$style = '';

		shortcode_atts(
			array(
				'style_id'      => '',
				'preview_class' => '',
				'type'          => 'modal_popup',
				'step_id'       => '1',
				'display'       => '',
				'manual'        => false,
				'only_preview'  => 'no',
			), $atts
		);

		$style_id = $atts['style_id'];

		$html = get_post_meta( $style_id, 'html_data', true );
		if ( strpos( $html, 'cp-countdown-field' ) ) {
			cpro_enqueue_countdown_script();
		}

		$cp_popups     = CP_V2_Popups::get_instance();
		$preview_class = '';
		if ( isset( $atts['preview_class'] ) && '' != $atts['preview_class'] ) {
			$preview_class = $atts['preview_class'];
		}

		$type = isset( $atts['type'] ) ? $atts['type'] : '';
		if ( 'undefined' == $type || '' == $type ) {
			$type = get_post_meta( $style_id, 'cp_module_type', true );
		}

		$display_class = '';
		if ( isset( $atts['display'] ) && '' != $atts['display'] ) {
			$display_class = $atts['display'];
		}

		$manual_class = '';
		if ( isset( $atts['manual'] ) && '' != $atts['manual'] ) {
			$manual_class = $atts['manual'];
		}

		$style_status       = get_post_meta( $style_id, 'live', true );
		$has_active_ab_test = get_post_meta( $style_id, 'has_active_ab_test', true );

		if ( ( isset( $atts['only_preview'] ) && 'yes' == $atts['only_preview'] )
			|| '1' == $style_status || '1' == $has_active_ab_test ) {

			cp_v2_enqueue_google_fonts( $style_id );
			$output           = get_post_meta( $style_id, 'html_data', true );
			$credit_link_html = '';

			/* translators: %s link */
			$txt                    = sprintf( __( 'Powered by %s', 'convertpro' ), CPRO_BRANDING_NAME );
			$credit_text            = apply_filters( 'cppro_credit_text', $txt );
			$link_color             = cpro_get_style_settings( $style_id, 'design', 'credit_link_color' );
			$is_display_credit_link = esc_attr( get_option( 'cp_credit_option' ) );

			if ( '0' !== $is_display_credit_link ) {

				$branding_agency_url = get_option( 'cpro_branding_plugin_author_url' );

				$powered_by_url = false == $branding_agency_url ? CP_POWERED_BY_URL : $branding_agency_url;

				$credit_link_html .= '<div class="cp-credit-link cp-responsive">
					<a class="cp-credit-link" style="color: ' . $link_color . '" href="' . $powered_by_url . '" target="_blank" rel="noopener"><span> ' . $credit_text . '</span></a>
				</div>';
			}

			$output = str_replace( '{{cpro_credit_link}}', $credit_link_html, $output );
			$output = str_replace( '{{current_date}}', esc_attr( date( 'j-n-Y' ) ), $output );

			echo do_shortcode( htmlspecialchars_decode( $output ) );

			do_action( 'after_shortcode_execute', $style_id );
		}

		return ob_get_clean();
	}
	add_shortcode( 'cp_popup', 'cp_render_popup' );
}

if ( ! function_exists( 'cp_get_live_popups' ) ) {
	/**
	 * Function Name: cp_get_live_popups.
	 * Function Description:Global functions to get live styles.
	 *
	 * @param string $type string parameter.
	 */
	function cp_get_live_popups( $type = 'all' ) {

		$popups = array();

		if ( class_exists( 'CP_V2_Popups' ) ) {
			$cp_popup = CP_V2_Popups::get_instance();
			$popups   = $cp_popup->get( $type );
		}

		return $popups;
	}
}

if ( ! function_exists( 'cp_generate_box_shadow' ) ) {
	/**
	 * Function Name: cp_generate_box_shadow.
	 * Function Description:Generate Box shadow.
	 *
	 * @param string $string string parameter.
	 */
	function cp_generate_box_shadow( $string ) {

		$box_val    = explode( '|', $string );
		$result     = array();
		$box_shadow = '';
		foreach ( $box_val as $pair ) {
			$pair               = explode( ':', $pair );
			$result[ $pair[0] ] = $pair[1];
		}

		$res = '';
		if ( 'none' !== $result['type'] ) {
			if ( isset( $result['type'] ) && 'outset' !== $result['type'] ) {
				$res .= $result['type'] . ' ';
			}

			$res .= $result['horizontal'] . 'px ';
			$res .= $result['vertical'] . 'px ';
			$res .= $result['blur'] . 'px ';
			$res .= $result['spread'] . 'px ';
			$res .= $result['color'];

			$box_shadow .= '-webkit-box-shadow:' . $res . ';';
			$box_shadow .= '-moz-box-shadow:' . $res . ';';
			$box_shadow .= 'box-shadow:' . $res . ';';
		}
		return $box_shadow;
	}
}

if ( ! function_exists( 'cp_generate_drop_shadow' ) ) {
	/**
	 * Function Name: cp_generate_drop_shadow.
	 * Function Description:Generate Drop shadow.
	 *
	 * @param string $string string parameter.
	 */
	function cp_generate_drop_shadow( $string ) {

		$box_val         = explode( '|', $string );
		$result          = array();
		$drop_shadow_arr = '';

		foreach ( $box_val as $pair ) {
			$pair               = explode( ':', $pair );
			$result[ $pair[0] ] = isset( $pair[1] ) ? $pair[1] : '';
		}

		$rgb_color = $result['color'];
		$res       = '';
		if ( 'none' !== $result['type'] ) {

			$res .= 'drop-shadow(';
			$res .= $result['horizontal'] . 'px ';
			$res .= $result['vertical'] . 'px ';
			$res .= $result['blur'] . 'px ';
			$res .= $rgb_color;
			$res .= ')';

			$drop_shadow_arr .= '-webkit-filter:' . $res . ';';
			$drop_shadow_arr .= 'filter:' . $res . ';';
			$drop_shadow_arr .= 'box-shadow:none;';
		}
		return $drop_shadow_arr;
	}
}

if ( ! function_exists( 'cp_is_modal_scheduled' ) ) {
	/**
	 * Function Name: cp_is_modal_scheduled.
	 * Function Description: Check schedule of modal.
	 *
	 * @param string $schedule string parameter.
	 * @param string $live string parameter.
	 */
	function cp_is_modal_scheduled( $schedule, $live ) {
		$op = '';
		if ( is_array( $schedule ) && '2' == $live ) {
			$op = ' data-scheduled="true" data-start="' . $schedule['start'] . '" data-end="' . $schedule['end'] . '" ';
		} else {
			$op = ' data-scheduled="false" ';
		}
		return $op;
	}
}

/**
 * Render popup shortcode
 *
 * @since 0.0.1
 */
function cp_load_popup_content() {

	$tests = array();

	// first get active ab tests.
	if ( class_exists( 'CP_V2_AB_Test' ) ) {

		$ab_tests        = CP_V2_AB_Test::get_instance();
		$active_ab_tests = $ab_tests->get_all_tests( array( 1 ) );

		if ( is_array( $active_ab_tests ) ) {
			foreach ( $active_ab_tests as $ab_test ) {

				$temp_arr = array();

				$styles = $ab_tests->get_styles_by_test_id( $ab_test->term_id );

				foreach ( $styles as $style ) {
					$temp_arr[] = $style->ID;
				}

				$tests[ $ab_test->term_id ] = $temp_arr;

			}
		}
	}

	$translation_array = array(
		'cp_v2_ab_tests_object' => $tests,
	);
	wp_localize_script( 'cp-popup-script', 'cp_v2_ab_tests', $translation_array );

	$popups = array();

	if ( class_exists( 'CP_V2_Popups' ) ) {
		$cp_popups = CP_V2_Popups::get_instance();
		$popups    = $cp_popups->get( 'launch' );
	}

	if ( ! empty( $tests ) ) {
		foreach ( $tests as $t ) {
			if ( is_array( $t ) ) {
				$popups = array_merge( $popups, $t );
			}
		}
	}

	$popups = ( is_array( $popups ) ) ? array_unique( $popups ) : $popups;

	$dev_mode = get_option( 'cp_dev_mode' );

	$is_inactive_needed = false;
	$is_device_needed   = false;
	$display            = true;

	$script_handlers = array(
		'cp-cookie-script',
		'cp-popup-mailer-script',
		'cp-submit-actions-script',
		'cp-video-api',
	);

	foreach ( $popups as $popup_id ) {
		if ( function_exists( 'cpro_get_style_settings' ) ) {
			$inactivity = cpro_get_style_settings( $popup_id, 'configure', 'inactivity' );
		}

		if ( 1 == $inactivity ) {
			$is_inactive_needed = true;
			array_push( $script_handlers, 'cp-ideal-timer-script' );
		}
	}

	array_push( $script_handlers, 'cp-popup-script' );

	// developer mode.
	if ( '1' == $dev_mode ) {

		$list = 'enqueued';

		foreach ( $script_handlers as $handler ) {
			if ( ! wp_script_is( $handler, $list ) ) {
				wp_enqueue_script( $handler );
			}
		}
	} else {

		if ( $is_inactive_needed ) {
			wp_enqueue_script( 'cp-ideal-timer-script' );
		}

		if ( $is_device_needed ) {
			wp_enqueue_script( 'cp-detect-device' );
		}

		if ( ! wp_script_is( 'cp-popup-script', 'enqueued' ) ) {
			wp_enqueue_script( 'cp-popup-script' );
		}
	}

	do_action( 'cp_front_scripts_loaded' );

	if ( ! empty( $popups ) ) {

		foreach ( $popups as $popup_id ) {

			if ( function_exists( 'cp_v2_is_style_visible' ) ) {
				$display = cp_v2_is_style_visible( $popup_id );
			}

			if ( $display ) {

				$hide_on_device = cpro_get_style_settings( $popup_id, 'configure', 'hide_on_device' );
				$html           = get_post_meta( $popup_id, 'html_data', true );
				if ( strpos( $html, 'cp-countdown-field' ) ) {
					cpro_enqueue_countdown_script();
				}

				if ( cpro_is_current_device( $hide_on_device ) ) {
					echo do_shortcode( '[cp_popup style_id = ' . $popup_id . ' step_id = "1" ][/cp_popup]' );
				}
			}
		}
	}
}

if ( ! function_exists( 'cpro_enqueue_countdown_script' ) ) {
	/**
	 * Function Name: cpro_enqueue_countdown_script.
	 * Function Description: Enqueue countdown script.
	 */
	function cpro_enqueue_countdown_script() {
		wp_enqueue_script( 'cpro-countdown-plugin-script', CP_V2_BASE_URL . 'framework/fields/cp_countdown/cp_countdown_plugin.min.js', array( 'cp-popup-script' ), '1.0.0', true );
		wp_enqueue_script( 'cpro-countdown', CP_V2_BASE_URL . 'framework/fields/cp_countdown/cp_countdown.min.js', array( 'cp-popup-script' ), '1.0.0', true );
		wp_enqueue_script( 'cpro-countdown-script', CP_V2_BASE_URL . 'framework/fields/cp_countdown/cp-countdown-script.js', array( 'cp-popup-script' ), '1.0.0', true );
		wp_enqueue_style( 'cpro-countdown-style', CP_V2_BASE_URL . 'framework/fields/cp_countdown/cp-countdown-style.css' );
	}
}

if ( ! function_exists( 'cp_get_wp_image_url_init' ) ) {
	/**
	 * Function Name: cp_get_wp_image_url_init.
	 * Function Description: Get WordPress attachment url.
	 *
	 * @param string $wp_image string parameter.
	 */
	function cp_get_wp_image_url_init( $wp_image = '' ) {

		if ( cpro_is_not_empty( $wp_image ) ) {
			$wp_image = explode( '|', $wp_image );

			if ( 0 == $wp_image[0] ) {
				$wp_image = CP_V2_BASE_URL . 'assets/' . $wp_image[1];
			} else {
				$wp_image = wp_get_attachment_image_src( $wp_image[0], $wp_image[1] );
				$wp_image = $wp_image[0];
			}
		}

		return $wp_image;
	}
}
add_filter( 'cp_get_wp_image_url', 'cp_get_wp_image_url_init' );

if ( ! function_exists( 'cpro_is_not_empty' ) ) {
	/**
	 * Function Name: cpro_is_not_empty.
	 * Function Description: Check values are empty or not.
	 *
	 * @param string $vl string parameter.
	 */
	function cpro_is_not_empty( $vl ) {
		if ( isset( $vl ) && '' != $vl ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Function Name: cp_v2_enqueue_google_fonts.
 * Function Description: Loads google fonts for style.
 *
 * @param string $style_id string parameter.
 */
function cp_v2_enqueue_google_fonts( $style_id ) {

	// Get google fonts list.
	$fonts_list       = get_post_meta( $style_id, 'cp_gfonts', true );
	$fonts            = json_decode( $fonts_list );
	$unique_font_list = array();

	/* Add Global Font to list */
	$cp_global_font = Cp_V2_Model::get_cp_global_fonts();

	if ( CP_V2_Fonts::is_google_font( $cp_global_font['family'] ) ) {

		$fonts->panel_global_font         = new stdClass();
		$fonts->panel_global_font->family = $cp_global_font['family'];
		$fonts->panel_global_font->weight = $cp_global_font['weight'];
	}

	if ( null !== $fonts && count( get_object_vars( $fonts ) ) > 0 ) {
		foreach ( $fonts as $key => $value ) {
			$font_family  = $value->family;
			$font_wt      = $value->weight;
			$font_weights = array();

			$font_weights[] = $font_wt;

			if ( isset( $unique_font_list[ $font_family ] ) ) {
				$weight                           = $unique_font_list[ $font_family ];
				$weight[]                         = $font_wt;
				$unique_font_list[ $font_family ] = $weight;
			} else {
				$unique_font_list[ $font_family ] = $font_weights;
			}
		}
	}

	$font_string = '';

	if ( null !== $unique_font_list && count( $unique_font_list ) > 0 ) {
		foreach ( $unique_font_list as $key => $font ) {
			$weight = implode( ',', $font );

			if ( 'Inherit' == $weight ) {
				$font_string .= $key . '|';
			} else {
				$font_string .= $key . ':' . $weight . '|';
			}
		}
	}

	// Get font list from editor.
	$editor_fonts = get_post_meta( $style_id, 'cp_editor_gfonts', true );
	$fonts        = json_decode( $editor_fonts );

	if ( null !== $fonts && count( get_object_vars( $fonts ) ) > 0 ) {
		foreach ( $fonts as $key => $font ) {
			$font_string .= urlencode( str_replace( '"', '', $key ) ) . ':regular|';
		}
	}

	if ( '' !== $font_string ) {
		$google_font_url = '//fonts.googleapis.com/css?family=' . $font_string;
		wp_enqueue_style( 'cp-google-fonts-' . $style_id, $google_font_url );
	}
}

if ( ! function_exists( 'cp_get_form_content' ) ) {
	/**
	 * Function Name: cp_get_form_content.
	 * Function Description: cp get form content.
	 *
	 * @param string $style_id string parameter.
	 * @param string $attr string parameter.
	 * @param bool   $generate_hidden_fields bool parameter.
	 * @param array  $meta_data meta data.
	 */
	function cp_get_form_content( $style_id, $attr, $generate_hidden_fields = true, $meta_data ) {

		$cp_popup = CP_V2_Popups::get_instance();

		if ( $generate_hidden_fields ) {
			$cp_popup->cp_get_panel_hidden_fields( $style_id, $meta_data );
		}

		$custom_html     = '';
		$output_html     = '';
		$toggle_fields   = '';
		$inner_wrap_html = '';

		$styles = '';

		foreach ( $attr as $panelkey => $panelvalue ) {

			$result = cp_get_panel( $panelvalue, $panelkey, $style_id );

			if ( ! empty( $result ) ) {
				if ( 'cp_toggle' == $panelvalue->type ) {

					ob_start();
					echo $result['html'];
					$toggle_fields .= ob_get_clean();
					$styles        .= $result['style'];

				} elseif ( 'cp_custom_html' != $panelvalue->type ) {

					ob_start();
					echo $result['html'];

					if ( isset( $panelvalue->is_outside_hide ) && 'true' == $panelvalue->is_outside_hide ) {
						$inner_wrap_html .= ob_get_clean();
					} else {
						$output_html .= ob_get_clean();
					}

					$styles .= $result['style'];

				} else {
					ob_start();
					echo $result['html'];
					$custom_html .= ob_get_clean();
					$styles      .= $result['style'];
				}
			}
		}

		$mobile_break_pt = cpro_get_style_settings( $style_id, 'design', 'cp_mobile_br_point' );
		$mobile_resp     = get_post_meta( $style_id, 'cp_mobile_responsive', true ) != false ? get_post_meta( $style_id, 'cp_mobile_responsive', true ) : 'no';

		if ( 'yes' == $mobile_resp ) {

			$styles .= '@media ( max-width: ' . $mobile_break_pt . 'px ) {';

				$styles     .= '.cp_style_' . $style_id . ' .cp-invisible-on-mobile {';
					$styles .= 'display: none !important;';
				$styles     .= '}';

			$styles .= '}';
		}

		/* Add GlObal Font */
		$cp_global_font  = Cp_V2_Model::get_cp_global_fonts();
		$sel_font_family = $cp_global_font['family'];
		$sel_font_weight = $cp_global_font['weight'];

		$font_style  = '.cp_style_' . $style_id . ' .cp-popup-content {';
		$font_style .= 'font-family:' . $sel_font_family . ';';
		$font_style .= 'font-weight:' . $sel_font_weight . ';';
		$font_style .= '}';

		$styles = $font_style . $styles;

		echo "<style id='cp_popup_style_" . $style_id . "' type='text/css'>" . $styles . '</style>';

		$content = array(
			'custom_html'   => $custom_html,
			'output_html'   => $output_html,
			'toggle_fields' => $toggle_fields,
			'inner_wrap'    => $inner_wrap_html,
		);

		return $content;

	}
}

if ( ! function_exists( 'cpro_get_offset_by_time_zone' ) ) {
	/**
	 * Function Name: cpro_get_offset_by_time_zone.
	 * Function Description: Returns offset by time zone.
	 *
	 * @param string $local_timezone string parameter.
	 */
	function cpro_get_offset_by_time_zone( $local_timezone ) {
		$time            = new DateTime( date( 'Y-m-d H:i:s' ), new DateTimeZone( $local_timezone ) );
		$timezone_offset = $time->format( 'P' );
		return $timezone_offset;
	}
}

/**
 * Function Name: cp_generate_scheduled_attributes.
 * Function Description: cp_generate_scheduled_attributes.
 *
 * @param string $style_id string parameter.
 */
function cp_generate_scheduled_attributes( $style_id ) {

	$scheduler_settings = cpro_get_style_settings( $style_id, 'configure', 'enable_scheduler' );
	$scheduler_start_on = cpro_get_style_settings( $style_id, 'configure', 'start_date' );
	$scheduler_end_on   = cpro_get_style_settings( $style_id, 'configure', 'end_date' );

	$scheduled_data = '';

	// Time Zone.
	$timezone = '';
	$timezone = get_option( 'timezone_string' );
	if ( '' == $timezone ) {
		$toffset  = get_option( 'gmt_offset' );
		$timezone = '' . $toffset . '';
	}

	$schedular_tmz_offset = get_option( 'gmt_offset' );
	if ( '' == $schedular_tmz_offset ) {
		$schedular_tmz_offset = cpro_get_offset_by_time_zone( get_option( 'timezone_string' ) );
	}

	// scheduler.
	if ( $scheduler_settings ) {
		$scheduled_data .= 'data-scheduled = "true"';
		$scheduled_data .= 'data-timezone = "' . $timezone . '"';
		$scheduled_data .= 'data-tz-offset = "' . $schedular_tmz_offset . '"';
		$scheduled_data .= 'data-start-date = "' . $scheduler_start_on . '"';
		$scheduled_data .= 'data-end-date = "' . $scheduler_end_on . '"';
	}

	return $scheduled_data;
}

/**
 * Function Name: cp_get_popup_categories.
 * Function Description: cp_get_popup_categories.
 *
 * @param bool $hide_empty bool parameter.
 */
function cp_get_popup_categories( $hide_empty = false ) {

	$terms = get_terms(
		array(
			'taxonomy'   => CP_POPUP_CATEGORY,
			'hide_empty' => $hide_empty,
		)
	);

	$categories = array(
		'all' => __( 'Select Your Goal', 'convertpro' ),
	);

	if ( count( $terms ) > 0 ) {

		foreach ( $terms as $term ) {
			$categories[ $term->slug ] = htmlspecialchars_decode( $term->name );
		}
	}

	return $categories;
}

/**
 * Function Name: cp_generate_bg_style.
 * Function Description: cp generate bg style.
 *
 * @param string $bg_type string parameter.
 * @param string $image string parameter.
 * @param string $parameter string parameter.
 * @param string $opt_bg string parameter.
 */
function cp_generate_bg_style( $bg_type, $image, $parameter, $opt_bg ) {

	$extra_style = '';

	if ( 'image' == $bg_type ) {

		if ( '' !== $opt_bg ) {
			$bg_option    = explode( '|', $opt_bg );
			$bg_repeat    = $bg_option[0];
			$bg_pos       = $bg_option[1];
			$bg_size      = $bg_option[2];
			$extra_style .= 'background-repeat :' . $bg_repeat . ';';
			$extra_style .= 'background-position :' . $bg_pos . ';';
			$extra_style .= 'background-size :' . $bg_size . ';';
		}
	}

	return $extra_style;
}

add_action( 'wp_ajax_nopriv_cp_v2_notify_admin', 'cp_v2_notify_admin' );
add_action( 'wp_ajax_cp_v2_notify_admin', 'cp_v2_notify_admin' );

/**
 * Function Name: cp_v2_notify_admin.
 * Function Description: cp v2 notify admin
 */
function cp_v2_notify_admin() {

	check_ajax_referer( 'cp_add_subscriber_nonce', '_nonce' );

	$response               = array(
		'error'      => false,
		'style_slug' => '',
	);
	$post_data              = sanitize_post_data( $_POST );
	$style_id               = isset( $post_data['style_id'] ) ? (int) esc_attr( $post_data['style_id'] ) : '';
	$post                   = get_post( (int) $style_id );
	$response['style_slug'] = $post->post_name;
	$email_meta             = get_post_meta( $style_id, 'connect', true );
	$user                   = wp_get_current_user();
	$can_user_see_errors    = true;

	if ( in_array( 'author', (array) $user->roles ) || in_array( 'editor', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
		$can_user_see_errors = true;
	} else {
		$can_user_see_errors = false;
	}

	$email_meta = ( ! empty( $email_meta ) ) ? call_user_func_array( 'array_merge', $email_meta ) : array();

	if ( ! empty( $email_meta ) && '1' == $email_meta['enable_notification'] ) {

		if ( cpro_notify_via_email( $post_data, $email_meta ) ) {
			wp_send_json_error( $response );
		} else {
			wp_send_json_success( $response );
		}
	}

	if ( $can_user_see_errors ) {
		$response['error'] = __( 'You are not connected to any service.', 'convertpro' );
	}

	wp_send_json_success( $response );
}

/**
 * Function Name: cpro_notify_via_email.
 * Function Description: Notifies admin about subscription
 *
 * @param array $post_data form post data.
 * @param array $email_meta email meta data.
 */
function cpro_notify_via_email( $post_data, $email_meta ) {

	$style_id   = isset( $post_data['style_id'] ) ? (int) esc_attr( $post_data['style_id'] ) : '';
	$settings   = $post_data;
	$style_name = get_the_title( $style_id );

	$admin_email            = get_option( 'admin_email' );
	$response               = array(
		'error'      => false,
		'style_slug' => '',
	);
	$post                   = get_post( (int) $style_id );
	$response['style_slug'] = $post->post_name;

	/* translators: %s site URL */
	$template = sprintf( __( "[FORM_SUBMISSION_DATA]\n\n -- \n\nThis e-mail was sent from a %1\$s call-to-action <strong>[DESIGN_NAME]</strong> on %2\$s (%3\$s)", 'convertpro' ), get_bloginfo( 'name' ), CPRO_BRANDING_NAME, site_url() );

	/* Translators: %s style name */
	$subject = sprintf( __( 'Call-to-action "%s" is not connected to any service.', 'convertpro' ), $style_name );

	$map = '';

	if ( ! empty( $email_meta ) ) {
		if ( '' != $email_meta['custom_email'] ) {
			$admin_email = $email_meta['custom_email'];
		}

		$template = get_option( 'cp_email_notification_template' );
		$subject  = get_option( 'cp_email_notification_subject' );

		$subject  = ( isset( $subject ) && false !== $subject ) ? $subject : '[SITE_NAME] - [DESIGN_NAME] Form Submission';
		$template = ( isset( $template ) && false !== $template ) ? $template : sprintf( __( "[FORM_SUBMISSION_DATA]\n\n -- \n\nThis e-mail was sent from a %1\$s call-to-action <strong>[DESIGN_NAME]</strong> on %2\$s (%3\$s)", 'convertpro' ), CPRO_BRANDING_NAME, get_bloginfo( 'name' ), site_url() );
	}

	if ( ! empty( $email_meta ) && '1' == $email_meta['enable_notification'] ) {
		$map = cpro_get_decoded_array( $email_meta['map_placeholder'] );

		$template = str_replace( '[DESIGN_NAME]', $style_name, $template );

		$blogname = mb_convert_encoding( get_bloginfo( 'name', 'display' ), 'UTF-8', 'HTML-ENTITIES' );

		$template = str_replace( '[SITE_NAME]', $blogname, $template );

		$subject = str_replace( '[SITE_NAME]', $blogname, $subject );

		$subject = str_replace( '[DESIGN_NAME]', $style_name, $subject );

		return cpro_send_email( $admin_email, $subject, $template, $settings, $map );
	}
}

/**
 * Sends E-Mail to admin when not connected to any service
 *
 * Called via the AJAX function.
 *
 * @param string $email email.
 * @param string $subject subject.
 * @param string $template template.
 * @param string $settings settings.
 * @param string $map map.
 * @since 0.0.1
 * @return void.
 */
function cpro_send_email( $email, $subject, $template, $settings, $map ) {

	$headers = array(
		'Reply-To: ' . get_bloginfo( 'name' ) . ' <' . $email . '>',
		'Content-Type: text/html; charset=UTF-8',
	);

	$param = '';

	if ( is_array( $settings['param'] ) && count( $settings['param'] ) ) {
		foreach ( $settings['param'] as $key => $value ) {
			$k      = isset( $map[ $key ] ) ? $map[ $key ] : $key;
			$param .= '<p>' . ucfirst( $k ) . ': ' . $value . '</p>';
		}
	}

	$template = str_replace( '[FORM_SUBMISSION_DATA]', $param, $template );
	wp_mail( $email, stripslashes( $subject ), stripslashes( $template ), $headers );
}

/**
 * Returns Sanitized $_POST data
 *
 * @param array $array post data array.
 * @since 0.0.1
 * @return array()
 */
function sanitize_post_data( &$array ) {

	if ( is_array( $array ) ) {

		foreach ( $array as &$value ) {

			if ( ! is_array( $value ) ) {

				// Sanitize if value is not an array.
				$value = sanitize_text_field( $value );

			} else {
				// Go inside this function again.
				sanitize_post_data( $value );
			}
		}
	}
	return $array;
}

/**
 * Returns an array of json decoded data
 *
 * @param array $mapping_array parameter.
 * @since 0.0.1
 * @return array
 */
function cpro_get_decoded_array( $mapping_array ) {
	$data       = json_decode( $mapping_array );
	$return_arr = array();
	$mailer     = '';

	if ( ! empty( $data ) ) {
		foreach ( $data as $key => $value ) {
			if ( 'cp-integration-service' == $value->name ) {
				$mailer = $value->value;
				break;
			}
			$return_arr[ $value->name ] = $value->value;
		}

		if ( 'infusionsoft' == $mailer ) {
			foreach ( $data as $key => $value ) {
				if ( 'infusionsoft_tags' == $value->name ) {
					$return_arr[ $value->name ][] = $value->value;
				} else {
					$return_arr[ $value->name ] = $value->value;
				}
			}
		} elseif ( 'ontraport' == $mailer ) {
			foreach ( $data as $key => $value ) {
				if ( 'ontraport_tags' == $value->name ) {
					$return_arr[ $value->name ][] = $value->value;
				} else {
					$return_arr[ $value->name ] = $value->value;
				}
			}
		} else {
			foreach ( $data as $key => $value ) {
				$return_arr[ $value->name ] = $value->value;
			}
		}
	}

	return $return_arr;
}

/**
 * Gives current device value
 *
 * @param string $device device value.
 * @return bool $is_current_device
 * @since 0.0.1
 */
function cpro_is_current_device( $device ) {

	$is_current_device = true;
	if ( '' != $device ) {
		$device_array = explode( '|', $device );

		if ( ! empty( $device_array ) ) {
			if ( in_array( 'mobile', $device_array ) ) {
				$is_current_device = ( ! cpro_is_medium_device() ) ? ! wp_is_mobile() : true;
				if ( ! $is_current_device ) {
					return $is_current_device;
				}
			}
			if ( in_array( 'tablet', $device_array ) ) {
				$is_current_device = ! cpro_is_medium_device();
				if ( ! $is_current_device ) {
					return $is_current_device;
				}
			}
			if ( in_array( 'desktop', $device_array ) ) {
				$is_current_device = ! cpro_is_desktop_device();
				if ( ! $is_current_device ) {
					return $is_current_device;
				}
			}
		}
	}

	return $is_current_device;
}

/**
 * Check if current device is medium device
 *
 * @since 0.0.1
 * @return bool $is_medium
 */
function cpro_is_medium_device() {

	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$is_medium = false;
	} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' ) !== false ) {
		$is_medium = true;
	} else {
		$is_medium = false;
	}

	return $is_medium;
}

/**
 * Check if current device is desktop device
 *
 * @since 0.0.1
 * @return bool $is_desktop
 */
function cpro_is_desktop_device() {

	$is_desktop = ( ! cpro_is_medium_device() && ! wp_is_mobile() ) ? true : false;

	return $is_desktop;
}

/**
 * Returns time difference between zones
 *
 * @since 1.0.4
 * @param string $time_zone Time zone.
 * @return string $offset.
 */
function cpro_get_gmt_difference( $time_zone ) {

	if ( ! empty( $time_zone ) ) {

		$time_zone_kolkata = new DateTimeZone( 'Asia/Kolkata' );
		$tzone             = new DateTimeZone( $time_zone );

		$time_kolkata = new DateTime( 'now', $time_zone_kolkata );

		$time_offset = $tzone->getOffset( $time_kolkata );

		return $time_offset / 3600;
	} else {
		return 'NULL';
	}
}
