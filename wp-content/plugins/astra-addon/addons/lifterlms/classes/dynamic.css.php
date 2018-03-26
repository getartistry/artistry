<?php
/**
 * Typography - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_lifterlms_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_lifterlms_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	/**
	 * Set font sizes
	 */
	$css_output = array();

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	return $dynamic_css . $css_output;
}

