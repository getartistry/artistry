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
	 * Below Header Responsive Background Image
	 */
	wp.customize( 'astra-settings[below-header-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {

			var break_point = astraCustomizer.headerBreakpoint;

			var belowHeaderBgStyle = '.ast-below-header { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-bg-obj-responsive', belowHeaderBgStyle, 'desktop' );
			var belowHeaderBgStyle = '.ast-header-break-point .ast-below-header { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-bg-obj-responsive', belowHeaderBgStyle, 'tablet' );
			var belowHeaderBgStyle = '.ast-header-break-point .ast-below-header { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-bg-obj-responsive', belowHeaderBgStyle, 'mobile' );


			var desktopColor = ( undefined !== bg_obj['desktop'] ) ? bg_obj['desktop']['background-color'] : '';
			var tabletColor = ( undefined !== bg_obj['tablet'] ) ? bg_obj['tablet']['background-color'] : '';
			var mobileColor = ( undefined !== bg_obj['mobile'] ) ? bg_obj['mobile']['background-color'] : '';

			if( '' !== desktopColor ) {
					
				var dynamicStyle = '';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu{ background-color: ' + desktopColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu { background-color: ' + desktopColor + '; }';
					dynamicStyle += '}';
				}
				astra_add_dynamic_css( 'below-header-bg-obj-transparent-desktop', dynamicStyle );
			}
			if( '' !== tabletColor ) {
					
				var dynamicStyle = '@media (max-width: 768px) {';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu{ background-color: ' + tabletColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu { background-color: ' + tabletColor + '; }';
					dynamicStyle += '}';
				}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'below-header-bg-obj-transparent-tablet', dynamicStyle );
			}
			if( '' !== mobileColor ) {
					
				var dynamicStyle = '@media (max-width: 544px) {';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu{ background-color: ' + mobileColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-below-header, .ast-below-header .sub-menu { background-color: ' + mobileColor + '; }';
					dynamicStyle += '}';
				}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'below-header-bg-obj-transparent-mobile', dynamicStyle );
			}

			astra_responsive_background_obj_refresh( bg_obj );

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

	/**
	 * Below Header Menu Bg colors & image 
	 */
	wp.customize( 'astra-settings[below-header-menu-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {
				var primaryMenuBgStyle = '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation, .ast-below-header-actual-nav, .ast-header-break-point .ast-below-header-actual-nav { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'desktop' );
				var primaryMenuBgStyle = '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation, .ast-below-header-actual-nav, .ast-header-break-point .ast-below-header-actual-nav { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'tablet' );
				var primaryMenuBgStyle = '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation, .ast-below-header-actual-nav, .ast-header-break-point .ast-below-header-actual-nav { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'below-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'mobile' );

				astra_responsive_background_obj_refresh( bg_obj );
		} );
	} );

	astra_responsive_font_size( 'astra-settings[font-size-below-header-content]', '.below-header-user-select' );
	astra_responsive_font_size( 'astra-settings[font-size-below-header-primary-menu]', '.ast-below-header' );
	astra_responsive_font_size( 'astra-settings[font-size-below-header-dropdown-menu]', '.ast-below-header .sub-menu' );
	astra_css( 'astra-settings[below-header-separator]', 'border-bottom-width', '.ast-below-header', 'px' );
	astra_css( 'astra-settings[below-header-bottom-border-color]', 'border-bottom-color', '.ast-below-header' );

	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-text-color-responsive]', 'color', '.below-header-user-select, .below-header-user-select .widget,.below-header-user-select .widget-title' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-link-color-responsive]', 'color', '.below-header-user-select a, .below-header-user-select .ast-search-menu-icon .search-submit, .below-header-user-select .widget a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-link-hover-color-responsive]', 'color', '.below-header-user-select a:hover, .below-header-user-select .widget a:hover' );

	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-menu-text-color-responsive]', 'color', '.ast-below-header, .ast-below-header a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-menu-text-hover-color-responsive]', 'color', '.ast-below-header li:hover > a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-menu-bg-hover-color-responsive]', 'background-color', '.ast-below-header-menu li:hover > a, .ast-below-header-menu li:focus > a, .ast-below-header-menu li.focus > a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-current-menu-text-color-responsive]', 'color', '.ast-below-header li.current-menu-ancestor > .ast-menu-toggle, .ast-below-header li.current-menu-ancestor > a, .ast-below-header li.current-menu-item > a, .ast-below-header li.current-menu-ancestor > .ast-menu-toggle, .ast-below-header li.current-menu-item > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-item:hover > .ast-menu-toggle' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-current-menu-bg-color-responsive]', 'background-color', '.ast-below-header li.current-menu-ancestor > a, .ast-below-header li.current-menu-item > a, .ast-below-header li.current-menu-ancestor > .ast-menu-toggle, .ast-below-header li.current-menu-item > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-below-header .sub-menu li.current-menu-item:hover > .ast-menu-toggle' );

	astra_color_responsive_css( 'mobile-below-header-no-toggle-current-bg-color', 'astra-settings[below-header-current-menu-bg-color-responsive]', 'background-color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu li.current-menu-item > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu li.current_page_item > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item:hover > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item:hover > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor:hover > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor:hover > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item:hover > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item:hover > .ast-menu-toggle' );

	astra_color_responsive_css( 'mobile-below-header-no-toggle-current-color', 'astra-settings[below-header-current-menu-text-color-responsive]', 'color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item:hover > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item > .ast-menu-toggle, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-item:hover > .ast-menu-toggle, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor:hover > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor > .ast-menu-toggle, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item:hover > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item > .ast-menu-toggle, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation li.current_page_item:hover > .ast-menu-toggle' );
	astra_color_responsive_css( 'mobile-below-header-no-toggle-color', 'astra-settings[below-header-menu-text-hover-color-responsive]', 'color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu li:hover > a, .ast-header-break-point .ast-below-header-menu li:hover > .ast-menu-toggle' );
	astra_color_responsive_css( 'mobile-below-header-no-toggle-bg-color', 'astra-settings[below-header-menu-bg-hover-color-responsive]', 'background-color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu li:hover > a, .ast-header-break-point .ast-below-header-menu li:hover > .ast-menu-toggle' );

	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-text-color-responsive]', 'color', '.ast-below-header .sub-menu, .ast-below-header .sub-menu a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-bg-color-responsive]', 'background-color', '.ast-below-header .sub-menu a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-hover-color-responsive]', 'color', '.ast-below-header .sub-menu li:hover > a, .ast-desktop .ast-below-header-navigation .astra-megamenu-li .sub-menu li a:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-bg-hover-color-responsive]', 'background-color', '.ast-below-header .sub-menu li:hover > a, .ast-desktop .ast-mega-menu-enabled.ast-below-header-menu .sub-menu li a:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-active-color-responsive]', 'color', '.ast-below-header .sub-menu li.current-menu-ancestor > a, .ast-below-header .sub-menu li.current-menu-item > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[below-header-submenu-active-bg-color-responsive]', 'background-color', '.ast-below-header .sub-menu li.current-menu-ancestor > a, .ast-below-header .sub-menu li.current-menu-item > a, .ast-below-header .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header .sub-menu li.current-menu-item:hover > a' );

	astra_color_responsive_css( 'mobile-below-header-no-toggle-submenu-hover-color', 'astra-settings[below-header-submenu-hover-color-responsive]', 'color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li:hover > a, .ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li:hover > .ast-menu-toggle' );
	astra_color_responsive_css( 'mobile-below-header-no-toggle-submenu-active-color', 'astra-settings[below-header-submenu-active-color-responsive]', 'color', '.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation .sub-menu li.current-menu-item > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation .sub-menu li.current-menu-item:hover > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation .sub-menu li.current_page_item > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation .sub-menu li.current_page_item:hover > a,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle,.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' );

	astra_css( 'astra-settings[below-header-submenu-border-color]', 'border-color', '.ast-below-header .sub-menu' );
	astra_css( 'astra-settings[below-header-submenu-item-b-color]', 'border-color', '.ast-desktop .ast-below-header-menu.submenu-with-border .sub-menu a' );

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
	 * Above Header Responsive Background Image
	 */
	wp.customize( 'astra-settings[above-header-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {

			var break_point = astraCustomizer.headerBreakpoint;

			var aboveHeaderBgStyle = '.ast-above-header, .ast-header-break-point .ast-above-header{ {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-bg-obj-responsive', aboveHeaderBgStyle, 'desktop' );
			var aboveHeaderBgStyle = '.ast-header-break-point .ast-above-header { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-bg-obj-responsive', aboveHeaderBgStyle, 'tablet' );
			var aboveHeaderBgStyle = '.ast-header-break-point .ast-above-header { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-bg-obj-responsive', aboveHeaderBgStyle, 'mobile' );


			var desktopColor = ( undefined !== bg_obj['desktop'] ) ? bg_obj['desktop']['background-color'] : '';
			var tabletColor = ( undefined !== bg_obj['tablet'] ) ? bg_obj['tablet']['background-color'] : '';
			var mobileColor = ( undefined !== bg_obj['mobile'] ) ? bg_obj['mobile']['background-color'] : '';

			if( '' !== desktopColor ) {
					
				var dynamicStyle = '';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-above-header, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul { background-color: ' + desktopColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-above-header { background-color: ' + desktopColor + '; }';
					dynamicStyle += '}';
				}
				astra_add_dynamic_css( 'above-header-bg-obj-transparent-desktop', dynamicStyle );
			}
			if( '' !== tabletColor ) {
					
				var dynamicStyle = '@media (max-width: 768px) {';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-above-header, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul { background-color: ' + tabletColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-above-header { background-color: ' + tabletColor + '; }';
					dynamicStyle += '}';
				}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'above-header-bg-obj-transparent-tablet', dynamicStyle );
			}
			if( '' !== mobileColor ) {
					
				var dynamicStyle = '@media (max-width: 544px) {';
				
				// Transparent Color Tweak.
				dynamicStyle += '.ast-above-header, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul { background-color: ' + mobileColor + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.ast-above-header { background-color: ' + mobileColor + '; }';
					dynamicStyle += '}';
				}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'above-header-bg-obj-transparent-mobile', dynamicStyle );
			}

			astra_responsive_background_obj_refresh( bg_obj );

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


	wp.customize( 'astra-settings[above-header-menu-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {
				var primaryMenuBgStyle = ' .ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'desktop' );
				var primaryMenuBgStyle = '.ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'tablet' );
				var primaryMenuBgStyle = '.ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'above-header-menu-bg-obj-responsive', primaryMenuBgStyle, 'mobile' );

				astra_responsive_background_obj_refresh( bg_obj );
		} );
	} );

	astra_css( 'astra-settings[above-header-divider]', 'border-bottom-width', '.ast-above-header, .ast-header-break-point .ast-above-header-merged-responsive .ast-above-header', 'px' );
	astra_css( 'astra-settings[above-header-divider-color]', 'border-bottom-color', '.ast-above-header, .ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' );

	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-text-color-responsive]', 'color', '.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-link-color-responsive]', 'color', '.ast-above-header-section .user-select a, .ast-above-header-section .ast-search-menu-icon .search-submit, .ast-above-header-section .widget a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-link-h-color-responsive]', 'color', '.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-menu-color-responsive]', 'color', '.ast-above-header-navigation a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-menu-h-color-responsive]', 'color', '.ast-above-header-navigation li:hover > a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-menu-h-bg-color-responsive]', 'background-color', '.ast-above-header-navigation li:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-menu-active-color-responsive]', 'color', '.ast-above-header-navigation li.current-menu-item > a,.ast-above-header-navigation li.current-menu-ancestor > a' );

	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-text-color-responsive]', 'color', '.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-bg-color-responsive]', 'background-color', '.ast-above-header-menu .sub-menu' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-hover-color-responsive]', 'color', '.ast-above-header-menu .sub-menu li:hover > a, .ast-above-header-menu .sub-menu li:focus > a, .ast-above-header-menu .sub-menu li.focus > a,.ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle, .ast-desktop .ast-above-header-navigation .ast-above-header-menu .astra-megamenu-li .sub-menu li a:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-bg-hover-color-responsive]', 'background-color', '.ast-above-header-menu .sub-menu li:hover > a, .ast-desktop .ast-mega-menu-enabled.ast-above-header-menu .sub-menu li a:hover' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-active-color-responsive]', 'color', '.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a' );
	astra_color_responsive_css( 'header-sections', 'astra-settings[above-header-submenu-active-bg-color-responsive]', 'background-color', '.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a' );

	astra_color_responsive_css( 'mobile-header-above-header-submenu-hover-color', 'astra-settings[above-header-submenu-hover-color-responsive]', 'color', '.ast-header-break-point .ast-above-header-menu .sub-menu li:hover > a, .ast-header-break-point .ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-header-break-point .ast-above-header-menu .sub-menu li:focus > a' );
	astra_color_responsive_css( 'mobile-header-above-header-submenu-active-color', 'astra-settings[above-header-submenu-active-color-responsive]', 'color', '.ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' );
	astra_css( 'astra-settings[above-header-submenu-border-color]', 'border-color', '.ast-above-header .sub-menu, .ast-above-header .sub-menu a' );
	astra_css( 'astra-settings[above-header-submenu-item-b-color]', 'border-color', '.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu a' );
	astra_responsive_font_size( 'astra-settings[above-header-font-size]', '.ast-above-header-menu, .ast-above-header .user-select' );
	astra_css( 'astra-settings[above-header-text-transform]', 'text-transform', '.ast-above-header-menu, .ast-above-header .user-select' );

	/**
	 * Above header submenu border
	 */
	wp.customize( 'astra-settings[above-header-submenu-border]', function( value ) {
		value.bind( function( border ) {
			if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {
				var dynamicStyle = '.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'  + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';
					dynamicStyle += '.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu .sub-menu';
					dynamicStyle += '{';
					dynamicStyle += 'top:-'   + border.top + 'px;';
					dynamicStyle += '}';
					// Submenu items goes outside?
					dynamicStyle += '@media (min-width: 769px){';
					dynamicStyle += '.ast-above-header-menu ul li.ast-left-align-sub-menu:hover > ul, .ast-above-header-menu ul li.ast-left-align-sub-menu.focus > ul';
					dynamicStyle += '{';
					dynamicStyle += 'margin-left:-'   + ( +border.left + +border.right ) + 'px;';
					dynamicStyle += '}';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'above-header-submenu-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Above header submenu item border
	 */
	wp.customize( 'astra-settings[above-header-submenu-item-border]', function( value ) {
		value.bind( function( border ) {
			var color = wp.customize( 'astra-settings[above-header-submenu-item-b-color]' ).get();
			if( true === border ) {
				var dynamicStyle  = '.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu a';
					dynamicStyle += '{';
					dynamicStyle += 'border-bottom-width:'   + ( (true === border) ? '1px;' : '0px;' );
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += 'border-color:'        + color + ';';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'above-header-submenu-item-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Below header submenu border
	 */
	wp.customize( 'astra-settings[below-header-submenu-border]', function( value ) {
		value.bind( function( border ) {
			if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {
				var dynamicStyle = '.ast-desktop .ast-below-header-menu.submenu-with-border .sub-menu';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'  + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';
					dynamicStyle += '.ast-desktop .ast-below-header-menu.submenu-with-border .sub-menu .sub-menu';
					dynamicStyle += '{';
					dynamicStyle += 'top:-'   + border.top + 'px;';
					dynamicStyle += '}';
					// Submenu items goes outside?
					dynamicStyle += '@media (min-width: 769px){';
					dynamicStyle += '.ast-below-header-menu ul li.ast-left-align-sub-menu:hover > ul, .ast-below-header-menu ul li.ast-left-align-sub-menu.focus > ul';
					dynamicStyle += '{';
					dynamicStyle += 'margin-left:-'   + ( +border.left + +border.right ) + 'px;';
					dynamicStyle += '}';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'below-header-submenu-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );
	/**
	 * below header submenu item border
	 */
	wp.customize( 'astra-settings[below-header-submenu-item-border]', function( value ) {
		value.bind( function( border ) {
			var color = wp.customize( 'astra-settings[below-header-submenu-item-b-color]' ).get();
			if( true === border ) {
				var dynamicStyle  = '.ast-desktop .ast-below-header-menu.submenu-with-border .sub-menu a';
					dynamicStyle += '{';
					dynamicStyle += 'border-bottom-width:'   + ( (true === border) ? '1px;' : '0px;' );
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += 'border-color:'        + color + ';';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'below-header-submenu-item-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	astra_responsive_font_size( 'astra-settings[font-size-above-header-dropdown-menu]', '.ast-above-header .sub-menu' );
	astra_css( 'astra-settings[text-transform-above-header-dropdown-menu]', 'text-transform', '.ast-above-header .sub-menu' );

} )( jQuery );
