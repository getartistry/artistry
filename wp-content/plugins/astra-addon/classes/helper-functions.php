<?php
/**
 * Astra Theme Extension
 *
 * @package Astra Addon
 */

/**
 * Contrasting Color
 */
if ( ! function_exists( 'astra_contrasting_color' ) ) :

	/**
	 * Contrasting Color
	 *
	 * @since 1.0.0
	 * @param  string $hexcolor Color code in HEX format.
	 * @param  string $dark     Darker color in HEX format.
	 * @param  string $light    Light color in HEX format.
	 * @return string           Contrasting Color.
	 */
	function astra_contrasting_color( $hexcolor, $dark = '#000000', $light = '#FFFFFF' ) {
		return ( hexdec( $hexcolor ) > 0xffffff / 2 ) ? $dark : $light;
	}

endif;

/**
 * Color conversion from HEX to RGB or RGBA.
 */
if ( ! function_exists( 'astra_hex2rgba' ) ) :

	/**
	 * Color conversion from HEX to RGB or RGBA.
	 *
	 * @since 1.0.0
	 * @param  string $hex   Color code in HEX format.
	 * @param  string $alpha Color code alpha value for RGBA conversion.
	 * @return string        Return RGB or RGBA color code.
	 */
	function astra_hex2rgba( $hex, $alpha = '' ) {
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = $r . ',' . $g . ',' . $b;

		if ( '' === $alpha ) {
			return 'rgb(' . $rgb . ')';
		} else {
			$alpha = floatval( $alpha );

			return 'rgba(' . $rgb . ',' . $alpha . ')';
		}
	}

endif;

/**
 * Convert colors from HEX to RGBA
 */
if ( ! function_exists( 'astra_hex_to_rgba' ) ) :

	/**
	 * Convert colors from HEX to RGBA
	 *
	 * @param  string  $color   Color code in HEX.
	 * @param  boolean $opacity Color code opacity.
	 * @return string           Color code in RGB or RGBA.
	 */
	function astra_hex_to_rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided.
		if ( '#' == $color[0] ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values.
		if ( 6 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		// Convert HEX to RGB.
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(RGBA or RGB).
		if ( $opacity ) {
			if ( 1 < abs( $opacity ) ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return RGB(a) color string.
		return $output;
	}

endif;

/**
 * Function to get Supported Custom Posts
 */
if ( ! function_exists( 'astra_get_supported_posts' ) ) :

	/**
	 * Function to get Supported Custom Posts
	 *
	 * @param  boolean $with_tax Post has taxonomy.
	 * @return array
	 */
	function astra_get_supported_posts( $with_tax = false ) {

		/**
		 * Dynamic Sidebars
		 *
		 * Generate dynamic sidebar for each post type.
		 */
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		$supported_types     = array();
		$supported_types_tax = array();

		foreach ( $post_types as $slug => $post_type ) {

			// Avoid post types.
			if ( 'attachment' === $slug || 'page' === $slug || 'post' === $slug ) {
				continue;
			}

			// Add to supported post type.
			$supported_types[ $slug ] = $post_type->label;

			// Add the taxonomies for the post type.
			$taxonomies = get_object_taxonomies( $slug, 'objects' );
			$another    = array();
			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

				if ( ! $taxonomy->public || ! $taxonomy->show_ui || 'post_format' == $taxonomy_slug ) {
					continue;
				}

				$another[] = $taxonomy->label;
			}

			// Add to supported post type.
			if ( count( $another ) ) {
				$supported_types_tax[] = $slug;
			}
		}

		if ( $with_tax ) {
			return $supported_types_tax;
		} else {
			return $supported_types;
		}
	}

endif;

/**
 * Function to check if it is Internet Explorer
 */
if ( ! function_exists( 'astra_check_is_ie' ) ) :

	/**
	 * Function to check if it is Internet Explorer.
	 *
	 * @return true | false boolean
	 */
	function astra_check_is_ie() {

		$is_ie = false;

		$ua = htmlentities( $_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8' );
		if ( strpos( $ua, 'Trident/7.0' ) !== false ) {
			$is_ie = true;
		}

		return $is_ie;
	}

endif;


if ( ! function_exists( 'astra_check_is_bb_themer_layout' ) ) :

	/**
	 * Check if layout is bb themer's layout
	 */
	function astra_check_is_bb_themer_layout() {

		$is_layout = false;

		$post_type = get_post_type();
		$post_id   = get_the_ID();

		if ( 'fl-theme-layout' === $post_type && $post_id ) {

			$is_layout = true;
		}

		return $is_layout;
	}

endif;


if ( ! function_exists( 'astra_rgba2hex' ) ) :

	/**
	 * Color conversion from RGBA / RGB to HEX.
	 *
	 * @since 1.0.0
	 * @param  string $string   Color code in RGBA / RGB format.
	 * @param  string $include_alpha   Color code in RGBA / RGB format.
	 * @return string           Return HEX color code.
	 */
	function astra_rgba2hex( $string, $include_alpha = false ) {

		$hex_color = $string;

		if ( ! astra_check_is_hex( $string ) ) {

			$rgba  = array();
			$regex = '#\((([^()]+|(?R))*)\)#';
			if ( preg_match_all( $regex, $string, $matches ) ) {
				$rgba = explode( ',', implode( ' ', $matches[1] ) );
			} else {
				$rgba = explode( ',', $string );
			}

			$rr = dechex( $rgba['0'] );
			$gg = dechex( $rgba['1'] );
			$bb = dechex( $rgba['2'] );
			$aa = '';

			if ( $include_alpha && array_key_exists( '3', $rgba ) ) {
				$aa = dechex( $rgba['3'] * 255 );
			}

			$hex_color = strtoupper( "#$aa$rr$gg$bb" );
		}

		return $hex_color;
	}

endif;

if ( ! function_exists( 'astra_check_is_hex' ) ) :

	/**
	 * Check if color code is HEX.
	 *
	 * @since 1.0.0
	 * @param  string $string   Color code any format.
	 * @return boolean          Return true | false.
	 */
	function astra_check_is_hex( $string ) {

		$is_hex = false;
		$regex  = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';

		if ( preg_match_all( $regex, $string, $matches ) ) {

			$is_hex = true;
		}

		return $is_hex;
	}

endif;


if ( ! function_exists( 'astra_get_addon_name' ) ) :

	/**
	 * Get addon name.
	 *
	 * @return string Addon Name.
	 */
	function astra_get_addon_name() {

		$addon_name = __( 'Astra Pro', 'astra-addon' );

		return apply_filters( 'astra_addon_name', $addon_name );
	}

endif;

if ( ! function_exists( 'astra_return_content_layout_page_builder' ) ) :

	/**
	 * String for content layout - page-builder
	 *
	 * @since  1.2.1
	 * @return String page-builder string used for filter `astra_get_content_layout`
	 */
	function astra_return_content_layout_page_builder() {
		return 'page-builder';
	}

endif;


if ( ! function_exists( 'astra_return_page_layout_no_sidebar' ) ) :

	/**
	 * String for sidebar Layout - no-sidebar
	 *
	 * @since  1.2.1
	 * @return String no-sidebar string used for filter `astra_page_layout`
	 */
	function astra_return_page_layout_no_sidebar() {
		return 'no-sidebar';
	}

endif;


if ( ! function_exists( 'astar' ) ) :

	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * Provide a default value if you want to return a specific value if the property is not set.
	 *
	 * @since  1.4.0
	 * @access public
	 * @author Gravity Forms - Easiest Tool to Create Advanced Forms for Your WordPress-Powered Website.
	 * @link  https://www.gravityforms.com/
	 *
	 * @param array  $array   Array from which the property's value should be retrieved.
	 * @param string $prop    Name of the property to be retrieved.
	 * @param string $default Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 */
	function astar( $array, $prop, $default = null ) {

		if ( ! is_array( $array ) && ! ( is_object( $array ) && $array instanceof ArrayAccess ) ) {
			return $default;
		}

		if ( isset( $array[ $prop ] ) ) {
			$value = $array[ $prop ];
		} else {
			$value = '';
		}

		return empty( $value ) && null !== $default ? $default : $value;
	}

endif;
