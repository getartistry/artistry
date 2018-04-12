<?php
/**
 * Helpers
 */

/**
 * Returns sticky logo setting
 *
 * @since 1.1.2
 */
if ( ! function_exists( 'osh_sticky_logo_setting' ) ) {

	function osh_sticky_logo_setting() {

		// Get setting
		$setting = get_theme_mod( 'osh_logo' );

		// Return setting
		return apply_filters( 'osh_sticky_logo', $setting );

	}

}

/**
 * Returns sticky retina logo setting
 *
 * @since 1.1.2
 */
if ( ! function_exists( 'osh_sticky_retina_logo_setting' ) ) {

	function osh_sticky_retina_logo_setting() {

		// Get setting
		$setting = get_theme_mod( 'osh_logo_retina' );

		// Return setting
		return apply_filters( 'osh_retina_sticky_logo', $setting );

	}

}

/**
 * Returns sticky header logo
 *
 * @since 1.0.7
 */
if ( ! function_exists( 'osh_header_sticky_logo' ) ) {

	function osh_header_sticky_logo() {

		// Return false if no logo
		if ( 'top' == oceanwp_header_style()
			|| '' == osh_sticky_logo_setting() ) {
			return false;
		}

		$html = '';
		$srcset = '';

		// Get logo
		$logo_url 		= osh_sticky_logo_setting();
		$retina_url 	= osh_sticky_retina_logo_setting();

		// Logo data
		$logo_data = array(
			'url'    	=> '',
			'width'  	=> '',
			'height' 	=> '',
			'alt' 		=> '',
		);

		if ( $logo_url ) {

			// Logo url
			$logo_data['url'] 			= $logo_url;

			// Logo data
			$logo_attachment_data 		= oceanwp_get_attachment_data_from_url( $logo_url );

			// Get logo data
			if ( $logo_attachment_data ) {
				$logo_data['width']  	= $logo_attachment_data['width'];
				$logo_data['height'] 	= $logo_attachment_data['height'];

				// If the logo alt attribute is empty, get the site title and explicitly
				if ( ! empty( $logo_attachment_data['alt'] ) ) {
					$logo_data['alt'] 	= $logo_attachment_data['alt'];
				} else {
					$logo_data['alt'] 	= get_bloginfo( 'name', 'display' );
				}
			}

			// Add srcset attr
			if ( $retina_url ) {
				$srcset = $logo_url . ' 1x, ' . $retina_url . ' 2x';
				$srcset = 'srcset="'. $srcset .'"';
			}

			// Output image
			$html = sprintf( '<a href="%1$s" class="sticky-logo-link" rel="home" itemprop="url"><img src="%2$s" class="sticky-logo" width="%3$s" height="%4$s" alt="%5$s" itemprop="url" %6$s/></a>',
				esc_url( home_url( '/' ) ),
				esc_url( $logo_data['url'] ),
				esc_attr( $logo_data['width'] ),
				esc_attr( $logo_data['height'] ),
				esc_attr( $logo_data['alt'] ),
				$srcset
			);

		}

		// Return logo
		return apply_filters( 'osh_header_sticky_logo', $html );

	}

}

/**
 * Echo sticky header logo
 *
 * @since 1.0.7
 */
if ( ! function_exists( 'the_custom_sticky_logo' ) ) {

	function the_custom_sticky_logo() {
		echo osh_header_sticky_logo();
	}

	add_action( 'ocean_after_logo_img', 'the_custom_sticky_logo' );

}