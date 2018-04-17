/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Addon
 * @since  1.0.0
 */

( function( $ ) {

	wp.customize( 'astra-settings[site-layout-box-width]', function( value ) {
		value.bind( function( width ) {
			/**
			 * Has sticky header?
			 */
			if ( jQuery( '*[data-stick-maxwidth]' ).length ) {
				jQuery( '*[data-stick-maxwidth]' ).find( '.ast-sticky-active, .ast-header-sticky-active, .ast-custom-footer' ).css( { 'max-width': width + 'px', 'transition': 'none' } );
				jQuery( '*[data-stick-maxwidth]' ).attr( 'data-stick-maxwidth', width );
			}
		} );
	} );

	wp.customize( 'astra-settings[site-layout-box-tb-margin]', function( value ) {
		value.bind( function( margin ) {

			header_top 			= (typeof ( wp.customize._value['astra-settings[above-header-layout]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[above-header-layout]']._value: '';
			header_below		= (typeof ( wp.customize._value['astra-settings[below-header-layout]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[below-header-layout]']._value: '';
			header_above_stick 	= (typeof ( wp.customize._value['astra-settings[header-above-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-above-stick]']._value: '';
			header_below_stick 	= (typeof ( wp.customize._value['astra-settings[header-below-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-below-stick]']._value: '';
			header_main_stick 	= (typeof ( wp.customize._value['astra-settings[header-main-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-main-stick]']._value: '';

			if( header_main_stick || ( header_top != 'disabled' && header_above_stick ) || ( header_below != 'disabled' && header_below_stick ) ) {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	wp.customize( 'astra-settings[site-layout-padded-pad]', function( value ) {
		value.bind( function( padding ) {

			header_top 			= (typeof ( wp.customize._value['astra-settings[above-header-layout]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[above-header-layout]']._value : '';
			header_below 		= (typeof ( wp.customize._value['astra-settings[below-header-layout]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[below-header-layout]']._value : '';
			header_above_stick 	= (typeof ( wp.customize._value['astra-settings[header-above-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-above-stick]']._value : '';
			header_below_stick 	= (typeof ( wp.customize._value['astra-settings[header-below-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-below-stick]']._value : '';
			header_main_stick 	= (typeof ( wp.customize._value['astra-settings[header-main-stick]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[header-main-stick]']._value : '';

			if( header_main_stick || ( header_top != 'disabled' && header_above_stick ) || ( header_below != 'disabled' && header_below_stick ) ) {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Sticky Header background color opacity
	 */
	wp.customize( 'astra-settings[sticky-header-bg-opc]', function( setting ) {
		setting.bind( function( alpha ) {

	        /**
	         * Colors
	         */
			var header_bg_color = '',
			primary_menu_bg_color = '',
			top_bg_color 	= '',
			sup_bg_color 	= '',
			primary_nav 	= '',
			dynamicStyle 	= '';

			/**
			 * Transparent Color Tweak
			 */

			if ( wp.customize._value.hasOwnProperty( "astra-settings[header-bg-color]" ) ) {
				header_bg_color = wp.customize._value['astra-settings[header-bg-color]']._value;
			}


			/**
	         * Is Primary Menu BG color
	         */
			if ( wp.customize._value.hasOwnProperty( "astra-settings[primary-menu-bg-color]" ) ) {
				primary_menu_bg_color = wp.customize._value['astra-settings[primary-menu-bg-color]']._value;
			}

	        /**
	         * Is Above Header color
	         */
			if ( wp.customize._value.hasOwnProperty( "astra-settings[above-header-bg-obj]" ) ) {
				var above_header_bg_obj = wp.customize._value['astra-settings[above-header-bg-obj]']._value;
				top_bg_color = above_header_bg_obj['background-color'] || '';
			}

	        /**
	         * Is Below Header color
	         */
			if ( wp.customize._value.hasOwnProperty( "astra-settings[below-header-bg-obj]" ) ) {
				sup_bg_obj = wp.customize._value['astra-settings[below-header-bg-obj]']._value || '';
				sup_bg_color = sup_bg_obj['background-color'] || '';
			}

			/**
	         * Disabled primary nav
	         */
	        if ( wp.customize._value.hasOwnProperty( "astra-settings[disable-primary-nav]" ) ) {
	        	primary_nav = wp.customize._value['astra-settings[disable-primary-nav]']._value;
	        }

			header_bg_color = ( header_bg_color != '' ) ? header_bg_color : '#ffffff';
			top_bg_color = ( top_bg_color != '' ) ? top_bg_color : '#ffffff';
			sup_bg_color = ( sup_bg_color != '' ) ? sup_bg_color : '#414042';

			/**
			 * Convert colors from HEX to RGBA
			 */
			header_bg_color       = astra_hex2rgba( header_bg_color, alpha );
			if ( '' != primary_menu_bg_color ) {
				primary_menu_bg_color = astra_hex2rgba( primary_menu_bg_color, alpha );
			}
			top_bg_color          = astra_hex2rgba( top_bg_color, alpha );
			sup_bg_color          = astra_hex2rgba( sup_bg_color, alpha );

			// Main Header.
			dynamicStyle += '.ast-transparent-header #ast-fixed-header .main-header-bar,';
			dynamicStyle += '#ast-fixed-header .main-header-bar,';
			dynamicStyle += '.ast-transparent-header .main-header-bar.ast-sticky-active,';
			dynamicStyle += '.main-header-bar.ast-sticky-active,';
			dynamicStyle += '.ast-stick-primary-below-wrapper.ast-sticky-active .main-header-bar,';
			dynamicStyle += '#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field,';
			dynamicStyle += '#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus { background-color: ' + header_bg_color + ' }';
			if ( '' != primary_menu_bg_color ) {
				// Primary Menu Bg Color
				dynamicStyle += '#ast-fixed-header .main-header-bar .main-header-menu, .main-header-bar.ast-sticky-active .main-header-menu, .ast-header-break-point #ast-fixed-header .main-header-menu, #ast-fixed-header .ast-masthead-custom-menu-items{ background-color: ' + primary_menu_bg_color + ' }';
				if( primary_nav ) {
					dynamicStyle += ' #ast-fixed-header .main-header-bar .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items { background-color: ' + primary_menu_bg_color + ' } ';
				}
			}
	        // Above Header.
            dynamicStyle += '#ast-fixed-header .ast-above-header, .ast-above-header.ast-sticky-active, #ast-fixed-header .ast-above-header .ast-search-menu-icon .search-field, .ast-above-header.ast-sticky-active .ast-search-menu-icon .search-field { background-color: ' + top_bg_color + ';}';

	        // Below Header.
	        dynamicStyle += '#ast-fixed-header .ast-below-header, .ast-below-header.ast-sticky-active, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-below-header, #ast-fixed-header .ast-below-header-wrap .ast-search-menu-icon .search-field, .ast-below-header-wrap .ast-sticky-active .ast-search-menu-icon .search-field { background-color: ' + sup_bg_color + ';}';

	        /**
	         * Add CSS
	         */
			astra_add_dynamic_css( 'sticky-header-bg-opc', dynamicStyle );

		} );
	} );

	/**
	 * Header background color
	 */
	wp.customize( 'astra-settings[header-bg-color]', function( setting ) {
		setting.bind( function( header_bg_color ) {

	        /**
	         * Colors
	         */
	        var alpha 			= '',
	        	dynamicStyle 	= '';

			/**
			 * Sticky background color opacity
			 */
			if ( wp.customize._value.hasOwnProperty( "astra-settings[sticky-header-bg-opc]" ) ) {
				alpha = wp.customize._value['astra-settings[sticky-header-bg-opc]']._value;
			}

			header_bg_color = ( header_bg_color != '' ) ? header_bg_color : '#ffffff';

			/**
			 * Convert colors from HEX to RGBA
			 */
			header_bg_color = astra_hex2rgba( header_bg_color, alpha );

			// Main Header.
			dynamicStyle += '.ast-transparent-header #ast-fixed-header .main-header-bar,';
			dynamicStyle += '#ast-fixed-header .main-header-bar,';
			dynamicStyle += '.ast-transparent-header .main-header-bar.ast-sticky-active,';
			dynamicStyle += '.main-header-bar.ast-sticky-active,';
			dynamicStyle += '.ast-stick-primary-below-wrapper.ast-sticky-active .main-header-bar,';
			dynamicStyle += '#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field,';
			dynamicStyle += '#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus { background-color: ' + header_bg_color + ' }';

	        /**
	         * Add CSS
	         */
			astra_add_dynamic_css( 'sticky-header-bg-color', dynamicStyle );

		} );
	} );

	/**
	 * Primary Menu background color
	 */
	wp.customize( 'astra-settings[primary-menu-bg-color]', function( setting ) {
		setting.bind( function( primary_menu_bg_color ) {

	        /**
	         * Colors
	         */
	        var alpha 			= '',
	        	dynamicStyle 	= '';

			/**
			 * Sticky background color opacity
			 */
			if ( wp.customize._value.hasOwnProperty( "astra-settings[sticky-header-bg-opc]" ) ) {
				alpha = wp.customize._value['astra-settings[sticky-header-bg-opc]']._value;
			}

			primary_menu_bg_color = ( primary_menu_bg_color != '' ) ? primary_menu_bg_color : '#ffffff';

			/**
			 * Convert colors from HEX to RGBA
			 */
			primary_menu_bg_color = astra_hex2rgba( primary_menu_bg_color, alpha );

			// Main Header.
			dynamicStyle += '#ast-fixed-header .main-header-bar .main-header-menu, .main-header-bar.ast-sticky-active .main-header-menu, .ast-header-break-point #ast-fixed-header .main-header-menu, #ast-fixed-header .ast-masthead-custom-menu-items{ background-color: ' + primary_menu_bg_color + ' }';

	        /**
	         * Add CSS
	         */
			astra_add_dynamic_css( 'sticky-primary-menu-bg-color', dynamicStyle );

		} );
	} );

	/**
	 * Sticky Above Header background color opacity
	 */
	wp.customize( 'astra-settings[above-header-bg-obj]', function( setting ) {
		setting.bind( function( top_bg_obj ) {

			top_bg_color = top_bg_obj['background-color'] || '';

	        /**
	         * Colors
	         */
	        var alpha = '',
	        	dynamicStyle 	= '';

			/**
			 * Sticky background color opacity
			 */

			if ( wp.customize._value.hasOwnProperty( "astra-settings[sticky-header-bg-opc]" ) ) {
				alpha = wp.customize._value['astra-settings[sticky-header-bg-opc]']._value;
			}

			top_bg_color = ( top_bg_color != '' ) ? top_bg_color : '#ffffff';

			/**
			 * Convert colors from HEX to RGBA
			 */
			top_bg_color    = astra_hex2rgba( top_bg_color, alpha );

	        // Above Header.
	        if ( wp.customize._value.hasOwnProperty( "astra-settings[above-header-bg-color]" ) ) {
	            dynamicStyle += '#ast-fixed-header .ast-above-header, .ast-above-header.ast-sticky-active,#ast-fixed-header .ast-above-header .ast-search-menu-icon .search-field, .ast-above-header.ast-sticky-active .ast-search-menu-icon .search-field { background-color: ' + top_bg_color + ';}';
	        }

	        /**
	         * Add CSS
	         */
			astra_add_dynamic_css( 'sticky-above-header-bg-color', dynamicStyle );

		} );
	} );

	/**
	 * Sticky Above Header background color opacity
	 */
	wp.customize( 'astra-settings[below-header-bg-obj]', function( setting ) {
		setting.bind( function( sup_bg_obj ) {

			sup_bg_color = sup_bg_obj['background-color'] || '';

	        /**
	         * Colors
	         */
	        var alpha = '',
	        	dynamicStyle 	= '';

			/**
			 * Sticky background color opacity
			 */

			if ( wp.customize._value.hasOwnProperty( "astra-settings[sticky-header-bg-opc]" ) ) {
				alpha = wp.customize._value['astra-settings[sticky-header-bg-opc]']._value;
			}

			sup_bg_color = ( sup_bg_color != '' ) ? sup_bg_color : '#414042';

			/**
			 * Convert colors from HEX to RGBA
			 */
			sup_bg_color    = astra_hex2rgba( sup_bg_color, alpha );

	        // Below Header.
	        if ( wp.customize._value.hasOwnProperty( "astra-settings[below-header-bg-obj]" ) ) {
	            dynamicStyle += '#ast-fixed-header .ast-below-header, .ast-below-header.ast-sticky-active, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-below-header, #ast-fixed-header .ast-below-header-wrap .ast-search-menu-icon .search-field, .ast-below-header-wrap .ast-sticky-active .ast-search-menu-icon .search-field { background-color: ' + sup_bg_color + ';}';
	        }

	        /**
	         * Add CSS
	         */
			astra_add_dynamic_css( 'sticky-below-header-bg-color', dynamicStyle );

		} );
	} );
	
	/**
	 * Sticky Above Header background color opacity
	 */
	wp.customize( 'astra-settings[sticky-header-logo-width]', function( setting ) {
		setting.bind( function( logo_width ) {
			if ( logo_width['desktop'] != '' || logo_width['tablet'] != '' || logo_width['mobile'] != '' ) {
				var dynamicStyle = '.site-logo-img .sticky-custom-logo img {max-width: ' + logo_width['desktop'] + 'px;} #masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg { width: ' + logo_width['desktop'] + 'px;} @media( max-width: 768px ) { .site-logo-img .sticky-custom-logo img {max-width: ' + logo_width['tablet'] + 'px;} #masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg { width: ' + logo_width['tablet'] + 'px;} } @media( max-width: 544px ) { .site-logo-img .sticky-custom-logo img {max-width: ' + logo_width['mobile'] + 'px;} #masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg { width: ' + logo_width['mobile'] + 'px;} }'
				astra_add_dynamic_css( 'sticky-header-logo-width', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

} )( jQuery );
