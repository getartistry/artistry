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

	/**
	 * Below Header Colors
	 */
	wp.customize( 'astra-settings[below-header-bg-obj]', function( value ) {
		value.bind( function( bg_obj ) {

			color = bg_obj['background-color'] || '';

			var dynamicStyleObj = '.ast-below-header { {{css}} }';			
			astra_background_obj_css( wp.customize, bg_obj, 'below-header-bg-obj', dynamicStyleObj );

			var dynamicStyle = '';

			/**
			 * Transparent Color tweak
			 */
			dynamicStyle += '.ast-below-header, .ast-below-header-wrap .ast-search-menu-icon .search-field, .ast-below-header .sub-menu { background-color: ' + color + ';}';
			if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
	        	// var alpha = wp.customize( 'astra-settings[transparent-header-opc]' ).get();
		        	// color = astra_hex2rgba( color, alpha ),
		        	var break_point = astra.break_point;

		        dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
				dynamicStyle += '.ast-below-header, .ast-below-header-wrap .ast-search-menu-icon .search-field, .ast-below-header .sub-menu { background-color: ' + color + ';}';
				dynamicStyle += '}';
			}

			astra_add_dynamic_css( 'below-header-bg-color', dynamicStyle );
		} );
	} );

	/**
	 * Height
	 */
	wp.customize( 'astra-settings[below-header-height]', function( value ) {
		value.bind( function( height ) {

			var max_height = '26px';
			var padding = '; padding-top: .8em; padding-bottom: .8em;';
			if ( height >= 30 ) {
				max_height = ( height - 8 ) + 'px';
			}
			if ( height < 60 ) {
				padding = '; padding-top: .35em; padding-bottom: .35em;';
			}

			dynamicStyle = '.ast-below-header { line-height: ' + height + 'px;}';
			dynamicStyle += '.ast-below-header-section-wrap { min-height: ' + height + 'px; }';
			dynamicStyle += '.below-header-user-select .ast-search-menu-icon .search-field { max-height: ' + max_height + ';' + padding + ' }';

			astra_add_dynamic_css( 'below-header-height', dynamicStyle );

			$( document ).trigger( 'masthead-height-changed' );
		} );
	} );

	astra_responsive_font_size( 'astra-settings[font-size-below-header-content]', '.below-header-user-select' );
	astra_responsive_font_size( 'astra-settings[font-size-below-header-primary-menu]', '.ast-below-header' );
	astra_responsive_font_size( 'astra-settings[font-size-below-header-dropdown-menu]', '.ast-below-header .sub-menu' );
	astra_css( 'astra-settings[below-header-separator]', 'border-bottom-width', '.ast-below-header', 'px' );
	astra_css( 'astra-settings[below-header-bottom-border-color]', 'border-bottom-color', '.ast-below-header' );

	astra_css( 'astra-settings[below-header-text-color]', 'color', '.below-header-user-select, .below-header-user-select .widget,.below-header-user-select .widget-title' );
	astra_css( 'astra-settings[below-header-link-color]', 'color', '.below-header-user-select a, .below-header-user-select .ast-search-menu-icon .search-submit, .below-header-user-select .widget a' );
	astra_css( 'astra-settings[below-header-link-hover-color]', 'color', '.below-header-user-select a:hover, .below-header-user-select .widget a:hover' );

	astra_css( 'astra-settings[below-header-menu-text-color]', 'color', '.ast-below-header, .ast-below-header a' );
	astra_css( 'astra-settings[below-header-menu-text-hover-color]', 'color', '.ast-below-header li:hover > a' );
	astra_css( 'astra-settings[below-header-current-menu-text-color]', 'color', '.ast-below-header li.current-menu-ancestor > a, .ast-below-header li.current-menu-item > a, .ast-below-header li.current-menu-ancestor > .ast-menu-toggle, .ast-below-header li.current-menu-item > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-item:hover > .ast-menu-toggle' );

	astra_css( 'astra-settings[below-header-submenu-text-color]', 'color', '.ast-below-header .sub-menu, .ast-below-header .sub-menu a' );
	astra_css( 'astra-settings[below-header-submenu-bg-color]', 'background-color', '.ast-below-header .sub-menu a' );
	astra_css( 'astra-settings[below-header-submenu-hover-color]', 'color', '.ast-below-header .sub-menu li:hover > a' );
	astra_css( 'astra-settings[below-header-submenu-bg-hover-color]', 'background-color', '.ast-below-header .sub-menu li:hover > a' );
	astra_css( 'astra-settings[below-header-submenu-active-color]', 'color', '.ast-below-header .sub-menu li.current-menu-ancestor > a, .ast-below-header .sub-menu li.current-menu-item > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a' );
	astra_css( 'astra-settings[below-header-submenu-active-bg-color]', 'background-color', '.ast-below-header .sub-menu li.current-menu-ancestor > a, .ast-below-header .sub-menu li.current-menu-item > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a' );

	astra_css( 'astra-settings[below-header-submenu-border-color]', 'border-color', '.ast-below-header .sub-menu, .ast-below-header .sub-menu a' );

	astra_css( 'astra-settings[text-transform-below-header-content]', 'text-transform', '.below-header-user-select' );
	astra_css( 'astra-settings[text-transform-below-header-primary-menu]', 'text-transform', '.ast-below-header' );
	astra_css( 'astra-settings[text-transform-below-header-dropdown-menu]', 'text-transform', '.ast-below-header .sub-menu' );

	/**
	 * Above Header Height
	 */
	wp.customize( 'astra-settings[above-header-height]', function( value ) {
		value.bind( function( height ) {

			var max_height = '26px';
			var padding = '; padding-top: .8em; padding-bottom: .8em;';
			if ( height >= 30 ) {
				max_height = ( height - 6 ) + 'px';
			}
			if ( height < 60 ) {
				padding = '; padding-top: .35em; padding-bottom: .35em;';
			}

			var dynamicStyle = '';
			dynamicStyle += '.ast-above-header { line-height: ' + height + 'px; } ';
			dynamicStyle += '.ast-above-header-section-wrap { min-height: ' + height + 'px; } ';
			dynamicStyle += '.ast-above-header .ast-search-menu-icon .search-field { max-height: ' + max_height + ';' + padding + ' }';

			astra_add_dynamic_css( 'above-header-height', dynamicStyle );

			$( document ).trigger( 'masthead-height-changed' );
		} );
	} );

	/**
	 * Background Color
	 */
	wp.customize( 'astra-settings[above-header-bg-obj]', function( value ) {
		value.bind( function( bg_obj ) {

			var dynamicStyleObj = '.ast-above-header { {{css}} }';			
			astra_background_obj_css( wp.customize, bg_obj, 'above-header-bg-obj', dynamicStyleObj );

			/**
	 		 * Transparent Color tweak
	 		 */
			var bg_color	= bg_obj['background-color'] || '';
	 		var dynamicStyle = '.ast-above-header, .ast-above-header .ast-search-menu-icon .search-field, .ast-above-header .ast-search-menu-icon .search-field:focus, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul { background-color: ' + bg_color + '; } ';
	 		if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
	         	var break_point = astra.break_point;

	 	        dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
	 			dynamicStyle += '.ast-above-header, .ast-above-header .ast-search-menu-icon .search-field, .ast-above-header .ast-search-menu-icon .search-field:focus { background-color: ' + bg_color + '; } ';
	 			dynamicStyle += '}';
	 		}
	 		astra_add_dynamic_css( 'above-header-bg-obj', dynamicStyle );

 		} );
 	} );

	/*
	 * Above header menu label
	 */
	wp.customize( 'astra-settings[above-header-menu-label]', function( setting ) {
		setting.bind( function( label ) {
			if( $('button.menu-above-header-toggle .mobile-menu-wrap .mobile-menu').length > 0 ) {
				if ( label != '' ) {
					$('button.menu-above-header-toggle .mobile-menu-wrap .mobile-menu').text(label);
				} else {
					$('button.menu-above-header-toggle .mobile-menu-wrap').remove();
				}
			} else {
				var html = $('button.menu-above-header-toggle').html();
				if( '' != label ) {
					html += '<div class="mobile-menu-wrap"><span class="mobile-menu">'+ label +'</span> </div>';
				}
				$('button.menu-above-header-toggle').html( html )
			}
		} );
	} );

	/*
	 * Below header menu label
	 */
	wp.customize( 'astra-settings[below-header-menu-label]', function( setting ) {
		setting.bind( function( label ) {
			if( $('button.menu-below-header-toggle .mobile-menu-wrap .mobile-menu').length > 0 ) {
				if ( label != '' ) {
					$('button.menu-below-header-toggle .mobile-menu-wrap .mobile-menu').text(label);
				} else {
					$('button.menu-below-header-toggle .mobile-menu-wrap').remove();
				}
			} else {
				var html = $('button.menu-below-header-toggle').html();
				if( '' != label ) {
					html += '<div class="mobile-menu-wrap"><span class="mobile-menu">'+ label +'</span> </div>';
				}
				$('button.menu-below-header-toggle').html( html )
			}
		} );
	} );

	astra_css( 'astra-settings[above-header-divider]', 'border-bottom-width', '.ast-above-header, .ast-header-break-point .ast-above-header-merged-responsive .ast-above-header', 'px' );
	astra_css( 'astra-settings[above-header-divider-color]', 'border-bottom-color', '.ast-above-header, .ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' );
	astra_css( 'astra-settings[above-header-text-color]', 'color', '.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' );
	astra_css( 'astra-settings[above-header-link-color]', 'color', '.ast-above-header-section .user-select a, .ast-above-header-section .ast-search-menu-icon .search-submit, .ast-above-header-section .widget a' );
	astra_css( 'astra-settings[above-header-link-h-color]', 'color', '.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' );
	astra_css( 'astra-settings[above-header-menu-color]', 'color', '.ast-above-header-navigation a' );
	astra_css( 'astra-settings[above-header-menu-h-color]', 'color', '.ast-above-header-navigation li:hover > a' );
	astra_css( 'astra-settings[above-header-menu-active-color]', 'color', '.ast-above-header-navigation li.current-menu-item > a' );

	astra_css( 'astra-settings[above-header-submenu-text-color]', 'color', '.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' );
	astra_css( 'astra-settings[above-header-submenu-bg-color]', 'background-color', '.ast-above-header-menu .sub-menu a' );
	astra_css( 'astra-settings[above-header-submenu-hover-color]', 'color', '.ast-above-header-menu .sub-menu li:hover > a' );
	astra_css( 'astra-settings[above-header-submenu-bg-hover-color]', 'background-color', '.ast-above-header-menu .sub-menu li:hover > a' );
	astra_css( 'astra-settings[above-header-submenu-active-color]', 'color', '.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a' );
	astra_css( 'astra-settings[above-header-submenu-active-bg-color]', 'background-color', '.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a' );

	astra_css( 'astra-settings[above-header-submenu-border-color]', 'border-color', '.ast-above-header .sub-menu, .ast-above-header .sub-menu a' );

	astra_responsive_font_size( 'astra-settings[above-header-font-size]', '.ast-above-header' );

	astra_css( 'astra-settings[above-header-text-transform]', 'text-transform', '.ast-above-header' );
} )( jQuery );
