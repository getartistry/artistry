/**
 * Astra Addon Common functions
 *
 * @package Astra Addon
 * @since  1.0.0
 */

/**
 * Convert HEX to RGBA
 *
 * @param  {string} hex   HEX color code.
 * @param  {number} alpha Alpha number for RGBA.
 * @return {string}       Return RGBA or RGB.
 */
function astra_hex2rgba( hex, alpha ) {

	hex = hex.replace( '#', '' );
	var r = g = b = '';

	if ( hex.length == 3 ) {
		r = get_hexdec( hex.substring( 0, 1 ) + hex.substring( 0, 1 ) );
		g = get_hexdec( hex.substring( 1, 1 ) + hex.substring( 1, 1 ) );
		b = get_hexdec( hex.substring( 2, 1 ) + hex.substring( 2, 1 ) );
	} else {
		r = get_hexdec( hex.substring( 0, 2 ) );
		g = get_hexdec( hex.substring( 2, 4 ) );
		b = get_hexdec( hex.substring( 4, 6 ) );
	}

	var rgb = r + ',' + g + ',' + b;

	if ( '' == alpha ) {
		return 'rgb(' + rgb + ')';
	} else {
		alpha = parseFloat( alpha );

		return 'rgba(' + rgb + ',' + alpha + ')';
	}

}