<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "media".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'media', 'cp_v2_media_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'cp_v2_framework_media_admin_styles' );

/**
 * Function Name: cp_v2_framework_media_admin_styles.
 * Function Description: cp v2 framework media admin styles.
 *
 * @param string $hook string parameter.
 */
function cp_v2_framework_media_admin_styles( $hook ) {
	$cp_page  = strpos( $hook, 'plug_page' );
	$dev_mode = get_option( 'cp_dev_mode' );

	wp_enqueue_script( 'media-upload' );
	wp_enqueue_media();

	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-media-script', plugins_url( 'media.js', __FILE__ ), array(), '1.0.0', true );
		wp_enqueue_style( 'cp-media-style', plugins_url( 'media.css', __FILE__ ) );
	}

}

/**
 * Function Name: cp_v2_media_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_v2_media_settings_field( $name, $settings, $value, $default_value = null ) {

	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';

	$btn_label = '' !== $value ? __( 'Change Image', 'convertpro' ) : __( 'Select Image', 'convertpro' );
	$img_arr   = explode( '|', $value );
	$img_id    = isset( $img_arr[0] ) ? (int) $img_arr[0] : 0;
	$img_url   = isset( $img_arr[1] ) ? $img_arr[1] : 0;
	$img_data  = false;
	$map_style = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$display_size = false;
	if ( $img_id > 0 ) {
		$display_size = true;
	}

	if ( false === $display_size ) {
		$hide_size = 'hide-for-default';
	} else {
		$hide_size = '';
	}

	$img_src_html = ( '' != $img_url ) ? $img_url : '';

	if ( 0 == $img_id ) {
		$img_src_html = CP_V2_BASE_URL . 'assets/' . $img_url;
	}

	$data_default = explode( '|', $default_value );

	$img     = ( '' == $value ) ? '<p class="description">' . __( 'No Image Selected', 'convertpro' ) . '</p>' : '<img src="' . $img_src_html . '"/>';
	$display = ( '' !== $value ) ? 'style="display:block;"' : 'style="display:none;"';
	$uid     = uniqid();

	$_SESSION[ $input_name ] = $uid;

	$data_atts  = "data-img-id='" . $img_id . "'";
	$data_atts .= " data-img-url='" . $img_url . "'";

	$output  = '';
	$output .= '<div class="' . $input_name . '_' . $uid . '_container cp-media-container">' . $img . '</div>';

	$output .= '<input type="hidden" data-type="background_image" data-mapstyle="' . htmlspecialchars( $map_style ) . '" id="cp_' . $input_name . '_' . $uid . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '"  ' . $data_atts . ' />';

	$output  .= '<div class="cp-media-actions">';
	$rmv_btn  = ( '' == $value ) ? 'display:none;' : '';
	$dflt_btn = ( '' == $default_value ) ? 'display:none;' : '';

	if ( '' == $default_value ) {
		$output .= '<button style="' . $rmv_btn . '" id="remove_' . $input_name . '_' . $uid . '" ' . $display . ' class="button button-secondary cp-remove-media form-control cp-input cp-' . $type . '">' . __( 'Remove', 'convertpro' ) . '</button>';
	}

	$output .= '<button style="' . $dflt_btn . '" data-default="' . $data_default[1] . '" id="default_' . $input_name . '_' . $uid . '" ' . $display . ' class="button button-secondary cp-default-media form-control cp-input cp-' . $type . '">' . __( 'Default', 'convertpro' ) . '</button>';
	$output .= '<button id="' . $input_name . '_' . $uid . '" data-uid="' . $uid . '" class="button button-secondary cp-upload-media form-control cp-input cp-' . $type . '">' . $btn_label . '</button>';
	$output .= '</div>';

	$selected = '';

	$output .= '</div>';
	$output .= '<div data-global="1" class="cp-element-container cp-media-sizes ' . $hide_size . '" data-name="' . $input_name . '_' . $uid . '" data-element="cp-media-' . $uid . '" data-operator="!==" data-value="">';
	$output .= '<strong><label for="cp_' . $input_name . '_size">' . __( 'Select Size', 'convertpro' ) . '</label></strong>';
	$output .= '<p>';

	$output .= '<select id="cp_' . $input_name . '_size" class="cp-media-' . $uid . ' form-control cp-input cp-media-size" name="' . $input_name . '_size" data-id="' . $img_arr[0] . '" >';

	if ( false !== $display_size ) {

		$output .= '<option ' . $selected . ' value="">' . __( 'Full', 'convertpro' ) . '</option>';
	}

	$output .= '</select></p>';
	return $output;
}

/**
 * Function Name: cp_v2_get_all_image_sizes.
 * Function Description: cp_v2_get_all_image_sizes.
 *
 * @param string $id string parameter.
 */
function cp_v2_get_all_image_sizes( $id = '' ) {

	$default_image_sizes = array( 'thumbnail', 'medium', 'large' );

	if ( is_numeric( $id ) ) {
		$img_meta = wp_get_attachment_metadata( $id );
		$sizes    = $img_meta['sizes'];
		if ( isset( $sizes['edit-screen-thumbnail'] ) ) {
			unset( $sizes['edit-screen-thumbnail'] );
		}

		$default_image_sizes = array_keys( $sizes );
	}

	global $_wp_additional_image_sizes;
	$image_sizes['full'] = array();
	foreach ( $default_image_sizes as $size ) {
		if ( isset( $sizes ) ) {
			$image_sizes[ $size ]['width']  = $sizes[ $size ]['width'];
			$image_sizes[ $size ]['height'] = $sizes[ $size ]['height'];
		} else {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
		}
		$image_sizes[ $size ]['crop'] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
	}

	if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
		$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	}

	return $image_sizes;
}

if ( ! function_exists( 'cp_handle_upload_prefilter' ) ) {
	add_filter( 'wp_handle_upload_prefilter', 'cp_handle_upload_prefilter' );

	/**
	 * Function Name: cp_handle_upload_prefilter.
	 * Function Description: cp_v2_textalign_admin_scripts.
	 *
	 * @param string $file string parameter.
	 */
	function cp_handle_upload_prefilter( $file ) {
		$page = isset( $_POST['admin_page'] ) ? esc_attr( $_POST['admin_page'] ) : '';

		if ( isset( $page ) && 'customizer' == $page ) {

			$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

			if ( 'jpg' !== $ext && 'jpeg' !== $ext && 'png' !== $ext && 'gif' !== $ext && 'ico' !== $ext ) {
				/* translators: %s Popups */
				$file['error'] = sprintf( __( 'The uploaded %s file is not supported. Please upload a valid image file. e.g. .jpg, .jpeg, .gif, .png, .ico', 'convertpro' ), $ext );
			}
		}

		return $file;
	}
}
