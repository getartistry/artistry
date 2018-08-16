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

/**
 * Check the color is HEX or not
 *
 * @param  {string} hex | rgba | rgb  HEX | RGBA | RGB color code.
 * @return {bool}       Return true | false.
 */
function astraIsHexColor( string ){
	isHex = false;
	regexp = /^[0-9a-fA-F]+$/;

	if ( regexp.test( string ) ) {
		isHex = true;
	}
	return isHex;
}

/**
 * Trim A From RGBA color scheme.
 *
 * @param  {string} rgba | rgb   RGBA | RGB color code.
 * @return {string}       Return string
 */
function astraTrimAlpha( string ) {
	return string.replace(/^\s+|\s+$/gm,'');
}

/**
 * Convert RGBA to HEX
 *
 * @param  {string} hex | rgba | rgb  HEX | RGBA | RGB color code.
 * @return {string}       Return HEX color.
 */
function astraRgbaToHex( string ) {
	if ( '' !== string ) {
		if ( ! astraIsHexColor( string.replace( '#', '' ) ) ) {

	    	var parts = string.substring(string.indexOf("(")).split(","),
			r = parseInt(astraTrimAlpha(parts[0].substring(1)), 10),
			g = parseInt(astraTrimAlpha(parts[1]), 10),
			b = parseInt(astraTrimAlpha(parts[2]), 10),
			a = parseFloat(astraTrimAlpha(parts[3].substring(0, parts[3].length - 1))).toFixed(2);
			string =  ('#' + r.toString(16) + g.toString(16) + b.toString(16) + (a * 255).toString(16).substring(0,2));
		}
	}
    return string;
}

/**
 * Apply CSS for the element
 */
function astra_color_responsive_css( addon, control, css_property, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( value ) {
			if ( value.desktop || value.mobile || value.tablet ) {
				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control + '-' + addon ).remove();

				var DeskVal = '',
					TabletFontVal = '',
					MobileVal = '';

				if ( '' != value.desktop ) {
					DeskVal = css_property + ': ' + value.desktop;
				}
				if ( '' != value.tablet ) {
					TabletFontVal = css_property + ': ' + value.tablet;
				}
				if ( '' != value.mobile ) {
					MobileVal = css_property + ': ' + value.mobile;
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '-' + addon + '">'
					+ selector + '	{ ' + DeskVal + ' }'
					+ '@media (max-width: 768px) {' + selector + '	{ ' + TabletFontVal + ' } }'
					+ '@media (max-width: 544px) {' + selector + '	{ ' + MobileVal + ' } }'
					+ '</style>'
				);

			} else {
				wp.customize.preview.send( 'refresh' );
				jQuery( 'style#' + control + '-' + addon ).remove();
			}

		} );
	} );
}


/**
 * Generate responsive background_obj CSS
 */
function astra_responsive_background_obj_css( wp_customize, bg_obj, ctrl_name, style, device ) {

	if( '' === bg_obj[device] || undefined === bg_obj[device] ){
		return;
	}

	var gen_bg_css 	= '';
	var bg_img		= bg_obj[device]['background-image'];
	var bg_color	= bg_obj[device]['background-color'];

		if ( '' !== bg_img && '' !== bg_color) {
			if ( undefined !== bg_color ) {
				gen_bg_css = 'background-image: linear-gradient(to right, ' + bg_color + ', ' + bg_color + '), url(' + bg_img + ');';
			}
		}else if ( '' !== bg_img ) {
			gen_bg_css = 'background-image: url(' + bg_img + ');';
		}else if ( '' !== bg_color ) {
			gen_bg_css = 'background-color: ' + bg_color + ';';
			// gen_bg_css += 'background-image: none;';
		}
		
		if ( '' !== bg_img ) {

			gen_bg_css += 'background-repeat: ' + bg_obj[device]['background-repeat'] + ';';
			gen_bg_css += 'background-position: ' + bg_obj[device]['background-position'] + ';';
			gen_bg_css += 'background-size: ' + bg_obj[device]['background-size'] + ';';
			gen_bg_css += 'background-attachment: ' + bg_obj[device]['background-attachment'] + ';';
		}
		if ( 'desktop' == device ) {
			var dynamicStyle = style.replace( "{{css}}", gen_bg_css );
		}
		if ( 'tablet' == device ) {
			var dynamicStyle = '@media (max-width: 768px) {' + style.replace( "{{css}}", gen_bg_css ) + '};';
		}
		if ( 'mobile' == device ) {
			var dynamicStyle = '@media (max-width: 544px) {' + style.replace( "{{css}}", gen_bg_css ) + '};';
		}

		astra_add_dynamic_css( ctrl_name +'-'+ device, dynamicStyle );
}

/**
 * Customizer refresh for empty value for all responsive empty color or background image
 */
function astra_responsive_background_obj_refresh( bg_obj ) {
	if ( undefined !== bg_obj['desktop'] && undefined !== bg_obj['tablet'] && undefined !== bg_obj['mobile'] ) {
		if ( 
				( '' === bg_obj['desktop']['background-color'] )
				&&
				( '' === bg_obj['tablet']['background-color'] )
				&&
				( '' === bg_obj['mobile']['background-color'] )
			) {
				wp.customize.preview.send( 'refresh' );
		}
	}
}