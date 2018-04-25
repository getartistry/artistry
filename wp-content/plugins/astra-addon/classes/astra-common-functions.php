<?php
/**
 * Astra Theme & Addon Common function.
 *
 * @package Astra Addon
 */

/**
 * Get Font Size value
 */
if ( ! function_exists( 'astra_responsive_font' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * @param  array  $font    CSS value.
	 * @param  string $device  CSS device.
	 * @param  string $default Default value.
	 * @return mixed
	 */
	function astra_responsive_font( $font, $device = 'desktop', $default = '' ) {
		$css_val = '';

		if ( isset( $font[ $device ] ) && isset( $font[ $device . '-unit' ] ) ) {
			if ( '' != $default ) {
				$font_size = astra_get_css_value( $font[ $device ], $font[ $device . '-unit' ], $default );
			} else {
				$font_size = astra_get_font_css_value( $font[ $device ], $font[ $device . '-unit' ] );
			}
		} elseif ( is_numeric( $font ) ) {
			$font_size = astra_get_css_value( $font );
		} else {
			$font_size = ( ! is_array( $font ) ) ? $font : '';
		}

		return $font_size;
	}
}

if ( function_exists( 'astra_do_action_deprecated' ) ) {

	// Depreciating astra_woo_qv_product_summary filter.
	add_action( 'astra_woo_quick_view_product_summary', 'deprecated_astra_woo_quick_view_product_summary', 10 );

	/**
	 * Astra Color Palettes
	 *
	 * @since 1.1.2
	 */
	function deprecated_astra_woo_quick_view_product_summary() {

		astra_do_action_deprecated( 'astra_woo_qv_product_summary', array(), '1.0.22', 'astra_woo_quick_view_product_summary', '' );
	}
}

/**
 * Get Responsive Spacing
 */
if ( ! function_exists( 'astra_responsive_spacing' ) ) {

	/**
	 * Get Spacing value
	 *
	 * @param  array  $option    CSS value.
	 * @param  string $side  top | bottom | left | right.
	 * @param  string $device  CSS device.
	 * @param  string $default Default value.
	 * @return mixed
	 */
	function astra_responsive_spacing( $option, $side = '', $device = 'desktop', $default = '' ) {

		if ( isset( $option[ $device ][ $side ] ) && isset( $option[ $device . '-unit' ] ) ) {
			$spacing = astra_get_css_value( $option[ $device ][ $side ], $option[ $device . '-unit' ], $default );
		} elseif ( is_numeric( $option ) ) {
			$spacing = astra_get_css_value( $option );
		} else {
			$spacing = ( ! is_array( $option ) ) ? $font : '';
		}

		return $spacing;
	}
}

/**
 * Adjust the background obj.
 */
if ( ! function_exists( 'astra_get_background_obj' ) ) {

	/**
	 * Adjust Brightness
	 *
	 * @param  array $bg_obj   Color code in HEX.
	 *
	 * @return array         Color code in HEX.
	 */
	function astra_get_background_obj( $bg_obj ) {

		$gen_bg_css = array();

		$bg_img   = isset( $bg_obj['background-image'] ) ? $bg_obj['background-image'] : '';
		$bg_color = isset( $bg_obj['background-color'] ) ? $bg_obj['background-color'] : '';

		if ( '' !== $bg_img && '' !== $bg_color ) {
			$gen_bg_css = array(
				'background-image' => 'linear-gradient(to right, ' . esc_attr( $bg_color ) . ', ' . esc_attr( $bg_color ) . '), url(' . esc_url( $bg_img ) . ')',
			);
		} elseif ( '' !== $bg_img ) {
			$gen_bg_css = array( 'background-image' => 'url(' . esc_url( $bg_img ) . ')' );
		} elseif ( '' !== $bg_color ) {
			$gen_bg_css = array( 'background-color' => esc_attr( $bg_color ) );
		}

		if ( '' !== $bg_img ) {
			if ( isset( $bg_obj['background-repeat'] ) ) {
				$gen_bg_css['background-repeat'] = esc_attr( $bg_obj['background-repeat'] );
			}

			if ( isset( $bg_obj['background-position'] ) ) {
				$gen_bg_css['background-position'] = esc_attr( $bg_obj['background-position'] );
			}

			if ( isset( $bg_obj['background-size'] ) ) {
				$gen_bg_css['background-size'] = esc_attr( $bg_obj['background-size'] );
			}

			if ( isset( $bg_obj['background-attachment'] ) ) {
				$gen_bg_css['background-attachment'] = esc_attr( $bg_obj['background-attachment'] );
			}
		}

		return $gen_bg_css;
	}
}
