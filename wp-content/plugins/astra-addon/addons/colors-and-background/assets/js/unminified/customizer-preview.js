/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 *
 * Use function astra_css() to generate dynamic CSS
 *
 * E.g. astra_css( CONTROL, CSS_PROPERTY, SELECTOR, UNIT )
 * 
 * @package Astra Addon
 * @since  1.0.0
 */

( function( $ ) {

	/**
	 * Header + Custom Menu Items
	 */
	wp.customize( 'astra-settings[header-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {
				var desktopColor = ( undefined !== bg_obj['desktop'] ) ? bg_obj['desktop']['background-color'] : '';
				var tabletColor = ( undefined !== bg_obj['tablet'] ) ? bg_obj['tablet']['background-color'] : '';
				var mobileColor = ( undefined !== bg_obj['mobile'] ) ? bg_obj['mobile']['background-color'] : '';
				var break_point = astraCustomizer.headerBreakpoint;

				if( '' !== desktopColor ) {
					
					var dynamicStyle = '';
					
					// Transparent Color Tweak.
					dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-sections-navigation { background-color: ' + desktopColor + '; } ';
					if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
							
						dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
						dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-sections-navigation { background-color: ' + desktopColor + '; }';
						dynamicStyle += '}';
					}

					dynamicStyle += '.main-header-bar .ast-search-menu-icon form { background-color: ' + desktopColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field { background-color: ' + desktopColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field:focus { background-color: ' + desktopColor + ' }';

					astra_add_dynamic_css( 'header-bg-obj-transparent-desktop', dynamicStyle );
				}
				if( '' !== tabletColor ) {
					
					var dynamicStyle = '@media (max-width: 768px) {';
					
					// Transparent Color Tweak.
					dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-sections-navigation { background-color: ' + tabletColor + '; } ';
					if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
							
						dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
						dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-sections-navigation { background-color: ' + tabletColor + '; }';
						dynamicStyle += '}';
					}

					dynamicStyle += '.main-header-bar .ast-search-menu-icon form { background-color: ' + tabletColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field { background-color: ' + tabletColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field:focus { background-color: ' + tabletColor + ' }';
					dynamicStyle += '}';

					astra_add_dynamic_css( 'header-bg-obj-transparent-tablet', dynamicStyle );
				}
				if( '' !== mobileColor ) {
					var dynamicStyle = '@media (max-width: 544px) {';
					
					// Transparent Color Tweak.
					dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-sections-navigation { background-color: ' + mobileColor + '; } ';
					if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
							
						dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
						dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-sections-navigation { background-color: ' + mobileColor + '; }';
						dynamicStyle += '}';
					}

					dynamicStyle += '.main-header-bar .ast-search-menu-icon form { background-color: ' + mobileColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field { background-color: ' + mobileColor + ' } ';
					dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field:focus { background-color: ' + mobileColor + ' }';
					dynamicStyle += '}';

					astra_add_dynamic_css( 'header-bg-obj-transparent-mobile', dynamicStyle );
				}

				var headerBgStyle = ' .main-header-bar,.ast-header-break-point .main-header-bar { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'header-bg-obj-responsive', headerBgStyle, 'desktop' );
				var headerBgStyle = ' .ast-header-break-point .main-header-bar { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'header-bg-obj-responsive', headerBgStyle, 'tablet' );
				var headerBgStyle = ' .ast-header-break-point .main-header-bar { {{css}} }';
				astra_responsive_background_obj_css( wp.customize, bg_obj, 'header-bg-obj-responsive', headerBgStyle, 'mobile' );

				astra_responsive_background_obj_refresh( bg_obj );

		} );
	} );

	/**
	 * Primary Menu + Custom Menu Items
	 */
	wp.customize( 'astra-settings[primary-menu-color-responsive]', function( value ) {
		value.bind( function( color ) {

			var DeskVal = '',
					TabletFontVal = '',
					MobileVal = '',
					mobile_style = '',
					tablet_style = '';

			if ( '' != color.desktop ) {
				DeskVal = color.desktop;
			}
			if ( '' != color.tablet ) {
				TabletFontVal = color.tablet;
			}
			if ( '' != color.mobile ) {
				MobileVal = color.mobile;
			}

			if( '' != color ) {
				var dynamicStyle   = '.main-header-menu, .main-header-menu a,.ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-header-sections-navigation a, .ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a{ color: ' + DeskVal + ';}';

				// Sticky Header colors for Custom Menu.
				dynamicStyle   += '#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a{ color: ' + DeskVal + ';}';

				dynamicStyle  += '.ast-masthead-custom-menu-items .slide-search .search-submit { background: ' + DeskVal + '; border-color: ' + DeskVal + '; }';
				dynamicStyle  += '.ast-masthead-custom-menu-items .ast-inline-search form { border-color: ' + DeskVal + '; border-color: ' + DeskVal + '; }';

				if( '' != TabletFontVal ) {
					tablet_style  += '@media (max-width: 768px) { .main-header-menu, .main-header-menu a,.ast-header-break-point .main-header-menu a,.ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-header-sections-navigation a, .ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a{ color: ' + TabletFontVal + ';}';
					// Sticky Header colors for Custom Menu.
					tablet_style   += '#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a{ color: ' + TabletFontVal + ';}';

					tablet_style  += '.ast-masthead-custom-menu-items .slide-search .search-submit { background: ' + TabletFontVal + '; border-color: ' + TabletFontVal + '; }';
					tablet_style  += '.ast-masthead-custom-menu-items .ast-inline-search form { border-color: ' + TabletFontVal + '; border-color: ' + TabletFontVal + '; } }';
				}

				if( '' != MobileVal ) {
					mobile_style  += '@media (max-width: 544px ) { .main-header-menu, .main-header-menu a,.ast-header-break-point .main-header-menu a,.ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-header-sections-navigation a, .ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a{ color: ' + MobileVal + ';}';
					// Sticky Header colors for Custom Menu.
					mobile_style   += '#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a{ color: ' + MobileVal + ';}';

					mobile_style  += '.ast-masthead-custom-menu-items .slide-search .search-submit { background: ' + MobileVal + '; border-color: ' + MobileVal + '; }';
					mobile_style  += '.ast-masthead-custom-menu-items .ast-inline-search form { border-color: ' + MobileVal + '; border-color: ' + MobileVal + '; } }';
				}

				dynamicStyle += tablet_style + mobile_style;

				astra_add_dynamic_css( 'primary-menu-color-responsive', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Content background color
	 */
	wp.customize( 'astra-settings[content-bg-obj]', function( value ) {
		value.bind( function( bg_obj ) {
			if( '' != bg_obj ) {

				var content_bg_image = bg_obj['background-image'] || '';
				var content_bg_color = bg_obj['background-color'] || '';

				if( jQuery( 'body' ).hasClass( 'ast-separate-container' ) && jQuery( 'body' ).hasClass( 'ast-two-container' )){
					var dynamicStyle   = '.ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts, .ast-separate-container .comments-area, .ast-separate-container .comments-count-wrapper, .ast-separate-container.ast-two-container #secondary .widget { {{css}} }';
					astra_background_obj_css( wp.customize, bg_obj, 'content-bg-obj', dynamicStyle );
				}
				else if ( jQuery( 'body' ).hasClass( 'ast-separate-container' ) ) {
					var dynamicStyle   = '.ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts, .ast-separate-container .comments-area, .ast-separate-container .comments-count-wrapper { {{css}} }';
					astra_background_obj_css( wp.customize, bg_obj, 'content-bg-obj', dynamicStyle );
				}
				else if ( jQuery( 'body' ).hasClass( 'ast-plain-container' ) && ( jQuery( 'body' ).hasClass( 'ast-box-layout' ) || jQuery( 'body' ).hasClass( 'ast-padded-layout' ) ) ) {
					var dynamicStyle   = '.ast-box-layout.ast-plain-container .site-content, .ast-padded-layout.ast-plain-container .site-content { {{css}} }';
					astra_background_obj_css( wp.customize, bg_obj, 'content-bg-obj', dynamicStyle );
				}


				var blog_grid = (typeof ( wp.customize._value['astra-settings[blog-grid]'] ) != 'undefined') ? wp.customize._value['astra-settings[blog-grid]']._value : 1;
				var blog_layout = (typeof ( wp.customize._value['astra-settings[blog-layout]'] ) != 'undefined') ? wp.customize._value['astra-settings[blog-layout]']._value : 'blog-layout-1';

				if( 'blog-layout-1' == blog_layout && 1 != blog_grid )
				{
					var dynamicStyle   = '.ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3 { {{css}} }';
					astra_background_obj_css( wp.customize, bg_obj, 'content-bg-obj-post', dynamicStyle );
				} else {
					var dynamicStyle   = '.ast-separate-container .ast-article-post { {{css}} }';
					astra_background_obj_css( wp.customize, bg_obj, 'content-bg-obj-post', dynamicStyle );

					var dynamicStyle  = '.ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3 {';
						dynamicStyle += '	background-color: transparent;';
						dynamicStyle += '	background-image: none;';
						dynamicStyle += '}';
					astra_add_dynamic_css( 'content-bg-obj-blog-layouts', dynamicStyle );
				}

			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Body
	 */
	astra_css( 'astra-settings[archive-summary-box-bg-color]', 'background-color', '.ast-separate-container .ast-archive-description' );
	astra_css( 'astra-settings[archive-summary-box-title-color]', 'color', '.ast-archive-description .page-title' );
	astra_css( 'astra-settings[archive-summary-box-text-color]', 'color', '.ast-archive-description' );

	/**
	 * Content <h1> to <h6> headings
	 */
	astra_css( 'astra-settings[h1-color]', 'color', 'h1, .entry-content h1' );
	astra_css( 'astra-settings[h2-color]', 'color', 'h2, .entry-content h2' );
	astra_css( 'astra-settings[h3-color]', 'color', 'h3, .entry-content h3' );
	astra_css( 'astra-settings[h4-color]', 'color', 'h4, .entry-content h4' );
	astra_css( 'astra-settings[h5-color]', 'color', 'h5, .entry-content h5' );
	astra_css( 'astra-settings[h6-color]', 'color', 'h6, .entry-content h6' );

	/**
	 * Header
	 */
	astra_css( 'astra-settings[header-color-site-title]', 	'color', 			'.site-title a, .site-title a:focus, .site-title a:hover, .site-title a:visited' );
	astra_css( 'astra-settings[header-color-h-site-title]',	'color', 			'.site-header .site-title a:hover' );
	astra_css( 'astra-settings[header-color-site-tagline]',	'color', 			'.site-header .site-description' );

	/**
	 * Primary Menu
	 */
	/**
	 * Primary Menu Bg colors & image 
	 */
	wp.customize( 'astra-settings[primary-menu-bg-obj-responsive]', function( value ) {
		value.bind( function( bg_obj ) {
			// Primary Menu is disabled.
			// Selector only when Above or below Header is merged.
			var headersectionSelector = '';
			if( jQuery( 'body' ).hasClass('ast-primary-menu-disabled') ) {
				headersectionSelector = ',.ast-above-header-menu-items, .ast-below-header-menu-items';
			}
			var primaryMenuBgStyle = ' .main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation';
				primaryMenuBgStyle += headersectionSelector;
				primaryMenuBgStyle += ' { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'primary-menu-bg-obj-responsive', primaryMenuBgStyle, 'desktop' );
			var primaryMenuBgStyle = '.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation';
				primaryMenuBgStyle += headersectionSelector;
				primaryMenuBgStyle += ' { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'primary-menu-bg-obj-responsive', primaryMenuBgStyle, 'tablet' );
			var primaryMenuBgStyle = '.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation';
				primaryMenuBgStyle += headersectionSelector;
				primaryMenuBgStyle += ' { {{css}} }';
			astra_responsive_background_obj_css( wp.customize, bg_obj, 'primary-menu-bg-obj-responsive', primaryMenuBgStyle, 'mobile' );

			astra_responsive_background_obj_refresh( bg_obj );
		} );
	} );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-a-bg-color-responsive]', 	'background-color', 	'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a,.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-a-color-responsive]', 		'color',				'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a,.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-h-bg-color-responsive]', 	'background-color', 	'.main-header-menu a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-sections-navigation li.hover > a,.ast-header-sections-navigation li.focus > a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-h-color-responsive]', 		'color', 				'.main-header-menu a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a,  .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle, .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-header-sections-navigation li.current-menu-item > a' );

	/**
	 * Primary Submenu
	 */
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-color-responsive]', 	 'color', 				'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a, .ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-h-color-responsive]', 	 'color', 				'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a,.main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a, .main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-h-bg-color-responsive]', 'background-color', 	'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a,.main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a, .ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-a-color-responsive]', 	 'color', 				'.ast-below-header-menu-items .sub-menu li.current-menu-item > a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-a-bg-color-responsive]', 'background-color', 	'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a, .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' );
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-submenu-bg-color-responsive]', 	 'background-color', 	'.main-navigation ul ul, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' );

	astra_color_responsive_css( 'colors-background-submenu-a-color', 'astra-settings[primary-submenu-a-color-responsive]', 	 'color', 				'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle:hover, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle, .main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a, .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a' );

	astra_css( 'astra-settings[primary-submenu-b-color]', 	 'border-color', 		'.main-navigation ul ul, .main-navigation ul ul a' );

	/**
	 * Single Post / Page Title Color
	 */
	astra_css( 'astra-settings[entry-title-color]', 'color', '.ast-single-post .entry-title, .page-title' );

	/**
	 * Blog / Archive Title
	 */
	astra_css( 'astra-settings[page-title-color]', 'color', '.entry-title a' );

	/**
	 * Blog / Archive Meta
	 */
	astra_css( 'astra-settings[post-meta-color]', 		 'color', '.entry-meta, .entry-meta *' );
	astra_css( 'astra-settings[post-meta-link-color]', 	 'color', '.entry-meta a, .entry-meta a *, .read-more a' );
	astra_css( 'astra-settings[post-meta-link-h-color]', 'color', '.read-more a:hover, .entry-meta a:hover, .entry-meta a:hover *' );

	/**
	 * Sidebar
	 */
	astra_css( 'astra-settings[sidebar-widget-title-color]',	'color', '.secondary .widget-title, .secondary .widget-title *' );
	astra_css( 'astra-settings[sidebar-text-color]',			'color', '.secondary .widget' );
	astra_css( 'astra-settings[sidebar-link-h-color]',			'color', '.secondary a:hover' );
	wp.customize( 'astra-settings[sidebar-bg-obj]', function( value ) {
		value.bind( function( bg_obj ) {
			astra_background_obj_css( wp.customize, bg_obj, 'sidebar-bg-obj', ' .sidebar-main { {{css}} } ' );
		} );
	} );

	/**
	 * Footer
	 */
	astra_css( 'astra-settings[footer-color]', 		  'color',					'.ast-small-footer' );
	astra_css( 'astra-settings[footer-link-color]',   'color',					'.ast-small-footer a' );
	astra_css( 'astra-settings[footer-link-h-color]', 'color',					'.ast-small-footer a:hover' );

	/**
	 * Sticky Header Site Title color
	 */
	wp.customize( 'astra-settings[header-color-site-title]', function( setting ) {
		setting.bind( function( site_title ) {
			if ( site_title != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-bar .site-title a, #ast-fixed-header .main-header-bar .site-title a:focus, #ast-fixed-header .main-header-bar .site-title a:hover, #ast-fixed-header .main-header-bar .site-title a:visited, .main-header-bar.ast-sticky-active .site-title a, .main-header-bar.ast-sticky-active .site-title a:focus, .main-header-bar.ast-sticky-active .site-title a:hover, .main-header-bar.ast-sticky-active .site-title a:visited { color: ' + site_title + '}';
				astra_add_dynamic_css( 'sticky-header-site-title-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Site Title Hover color
	 */
	wp.customize( 'astra-settings[header-color-h-site-title]', function( setting ) {
		setting.bind( function( site_title_hover ) {
			if ( site_title_hover != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-bar .site-title a:hover, .main-header-bar.ast-sticky-active .site-title a:hover { color: ' + site_title_hover + '}';
				astra_add_dynamic_css( 'sticky-header-site-title-hover-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Site Tagline Hover color
	 */
	wp.customize( 'astra-settings[header-color-site-tagline]', function( setting ) {
		setting.bind( function( site_tagline_hover ) {
			if ( site_tagline_hover != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-bar .site-description, .main-header-bar.ast-sticky-active .site-description { color: ' + site_tagline_hover + '}';
				astra_add_dynamic_css( 'sticky-header-site-tagline-hover-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Primary Menu link / text color
	 */
	// wp.customize( 'astra-settings[primary-menu-color-responsive]', function( setting ) {
	// 	setting.bind( function( menu_link_text ) {
	// 		if ( menu_link_text != '' ) {
	// 			var dynamicStyle = '#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a{ color: ' + menu_link_text + '}';
	// 			astra_add_dynamic_css( 'sticky-header-primary-menu-link-color', dynamicStyle );
	// 		}
	// 		else{
	// 			wp.customize.preview.send( 'refresh' );
	// 		}
	// 	});
	// });

	/**
	 * Sticky Header Primary Menu Active Link  color
	 */
	wp.customize( 'astra-settings[primary-menu-a-color-responsive]', function( setting ) {
		setting.bind( function( menu_active_link ) {
			if ( menu_active_link != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-ancestor > a, .main-header-bar.ast-sticky-active .main-header-menu li.current_page_item > a{ color: ' + menu_active_link + '}';
				astra_add_dynamic_css( 'sticky-header-primary-menu-active-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Primary Menu Active Link  color
	 */
	wp.customize( 'astra-settings[primary-menu-a-bg-color-responsive]', function( setting ) {
		setting.bind( function( menu_active_link_bg ) {
			if ( menu_active_link_bg != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-ancestor > a, .main-header-bar.ast-sticky-active .main-header-menu li.current_page_item > a{ background-color: ' + menu_active_link_bg + '}';
				astra_add_dynamic_css( 'sticky-header-primary-menu-a-bg-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Primary Menu Link hover  color
	 */
	wp.customize( 'astra-settings[primary-menu-h-color-responsive]', function( setting ) {
		setting.bind( function( menu_link_hover ) {
			if ( menu_link_hover != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-menu a:hover, #ast-fixed-header .main-header-menu li:hover > a, #ast-fixed-header .main-header-menu li.focus > a, .main-header-bar.ast-sticky-active .main-header-menu li:hover > a, .main-header-bar.ast-sticky-active .main-header-menu li.focus > a{ color: ' + menu_link_hover + '}';
				astra_add_dynamic_css( 'sticky-header-primary-menu-hover-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Primary Menu Link hover bg  color
	 */
	wp.customize( 'astra-settings[primary-menu-h-bg-color-responsive]', function( setting ) {
		setting.bind( function( menu_link_hover_bg ) {
			if ( menu_link_hover_bg != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-menu a:hover, #ast-fixed-header .main-header-menu li:hover > a, #ast-fixed-header .main-header-menu li.focus > a, .main-header-bar.ast-sticky-active .main-header-menu li:hover > a, .main-header-bar.ast-sticky-active .main-header-menu li.focus > a{ background-color: ' + menu_link_hover_bg + '}';
				astra_add_dynamic_css( 'sticky-header-primary-menu-hover-bg-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

} )( jQuery );
