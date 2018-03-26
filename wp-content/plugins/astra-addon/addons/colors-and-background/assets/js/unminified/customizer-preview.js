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
	wp.customize( 'astra-settings[header-bg-color]', function( value ) {
		value.bind( function( color ) {

			if( '' != color ) {

				var dynamicStyle = '';
				/**
				 * Transparent Color tweak
				 */
				dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-sections-navigation { background-color: ' + color + '; } ';
				if ( jQuery( 'body' ).hasClass( 'ast-transparent-header' ) ) {
						var break_point = astra.break_point;
						
					dynamicStyle += '@media ( min-width: '+ break_point +'px ) { ';
					dynamicStyle += '.main-header-bar, .ast-header-break-point .main-header-menu, .ast-header-sections-navigation { background-color: ' + color + '; }';
					dynamicStyle += '}';
				}

				dynamicStyle += '.main-header-bar .ast-search-menu-icon form { background-color: ' + color + ' } ';
				dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field { background-color: ' + color + ' } ';
				dynamicStyle += '.ast-masthead-custom-menu-items .slide-search .search-field:focus { background-color: ' + color + ' }';

				astra_add_dynamic_css( 'header-bg-color', dynamicStyle );
				
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Primary Menu + Custom Menu Items
	 */
	wp.customize( 'astra-settings[primary-menu-color]', function( value ) {
		value.bind( function( color ) {

			if( '' != color ) {
				dynamicStyle   = '.main-header-menu, .main-header-menu a,.ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-header-sections-navigation a, .ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a{ color: ' + color + ';}';
				dynamicStyle  += '.ast-masthead-custom-menu-items .slide-search .search-submit { background: ' + color + '; border-color: ' + color + '; }';
				dynamicStyle  += '.ast-masthead-custom-menu-items .ast-inline-search form { border-color: ' + color + '; border-color: ' + color + '; }';

				astra_add_dynamic_css( 'primary-menu-color', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Content background color
	 */
	wp.customize( 'astra-settings[content-bg-color]', function( value ) {
		value.bind( function( color ) {
			if( '' != color ) {
				if( jQuery( 'body' ).hasClass( 'ast-separate-container' ) && jQuery( 'body' ).hasClass( 'ast-two-container' )){
					var dynamicStyle   = '.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3,.ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts, .ast-separate-container .comments-area, .ast-separate-container .comments-count-wrapper, .ast-separate-container.ast-two-container #secondary .widget{ background-color: ' + color + ';}';
					astra_add_dynamic_css( 'content-bg-color', dynamicStyle );
				}
				else if ( jQuery( 'body' ).hasClass( 'ast-separate-container' ) ) {
					var dynamicStyle   = '.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3,.ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts, .ast-separate-container .comments-area, .ast-separate-container .comments-count-wrapper{ background-color: ' + color + ';}';
					astra_add_dynamic_css( 'content-bg-color', dynamicStyle );
				}
				else if ( jQuery( 'body' ).hasClass( 'ast-plain-container' ) && ( jQuery( 'body' ).hasClass( 'ast-box-layout' ) || jQuery( 'body' ).hasClass( 'ast-padded-layout' ) ) ) {
					var dynamicStyle   = '.ast-box-layout.ast-plain-container .site-content, .ast-padded-layout.ast-plain-container .site-content { background-color: ' + color + ';}';
					astra_add_dynamic_css( 'content-bg-color', dynamicStyle );
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
	astra_css( 'astra-settings[primary-menu-bg-color]', 	 'background-color', 	'.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation, .ast-above-header-menu-items, .ast-below-header-menu-items' );
	astra_css( 'astra-settings[primary-menu-a-bg-color]', 	'background-color', 	'.main-header-menu li.current-menu-item > a, .main-header-menu li.current-menu-ancestor > a, .main-header-menu li.current_page_item > a,.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' );
	astra_css( 'astra-settings[primary-menu-a-color]', 		'color',				'.main-header-menu li.current-menu-item > a, .main-header-menu li.current-menu-ancestor > a, .main-header-menu li.current_page_item > a,.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' );
	astra_css( 'astra-settings[primary-menu-h-bg-color]', 	'background-color', 	'.main-header-menu a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-sections-navigation li.hover > a,.ast-header-sections-navigation li.focus > a' );
	astra_css( 'astra-settings[primary-menu-h-color]', 		'color', 				'.main-header-menu a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a,  .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle, .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-header-sections-navigation li.current-menu-item > a' );

	/**
	 * Primary Submenu
	 */
	astra_css( 'astra-settings[primary-submenu-color]', 	 'color', 				'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a, .ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' );
	astra_css( 'astra-settings[primary-submenu-h-color]', 	 'color', 				'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a,.main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a, .main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' );
	astra_css( 'astra-settings[primary-submenu-h-bg-color]', 'background-color', 	'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a,.main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a, .ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' );
	astra_css( 'astra-settings[primary-submenu-a-color]', 	 'color', 				'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a, .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' );
	astra_css( 'astra-settings[primary-submenu-a-bg-color]', 'background-color', 	'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a, .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' );
	astra_css( 'astra-settings[primary-submenu-bg-color]', 	 'background-color', 	'.main-navigation ul ul, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' );
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

	/**
	 * Footer
	 */
	astra_css( 'astra-settings[footer-color]', 		  'color',					'.ast-small-footer' );
	astra_css( 'astra-settings[footer-link-color]',   'color',					'.ast-small-footer a' );
	astra_css( 'astra-settings[footer-link-h-color]', 'color',					'.ast-small-footer a:hover' );
	astra_css( 'astra-settings[footer-bg-rep]', 	  'background-repeat', 		'.ast-small-footer' );
	astra_css( 'astra-settings[footer-bg-size]', 	  'background-size', 		'.ast-small-footer' );
	astra_css( 'astra-settings[footer-bg-pos]', 	  'background-position', 	'.ast-small-footer' );
	astra_css( 'astra-settings[footer-bg-atch]', 	  'background-attachment',  '.ast-small-footer' );

	/**
	 * Footer background color opacity
	 */
	wp.customize( 'astra-settings[footer-bg-color-opc]', function( setting ) {
		setting.bind( function( bg_color_opac ) {
			if ( bg_color_opac == '' ) {
				wp.customize.preview.send( 'refresh' );

			} else {
				var bg_color     = wp.customize( 'astra-settings[footer-bg-color]' ).get();
				var dynamicStyle = '.ast-small-footer > .ast-footer-overlay {background-color: ' + astra_hex2rgba( bg_color, bg_color_opac ) + '}';
				astra_add_dynamic_css( 'footer-bg-color-opc', dynamicStyle );
			}

		} );
	} );

	wp.customize( 'astra-settings[footer-bg-img]', function( setting ) {
		setting.bind( function( bg_img ) {

			if (bg_img == '') {
				wp.customize.preview.send( 'refresh' );
			} else {

				var bg_color      = wp.customize( 'astra-settings[footer-bg-color]' ).get();
				var bg_color_opac = (typeof wp.customize( 'astra-settings[footer-bg-color-opc]' ) != 'undefined') ? wp.customize( 'astra-settings[footer-bg-color-opc]' ).get() : '';
				if ( bg_color_opac && bg_color ) {
					var dynamicStyle  = '.ast-small-footer > .ast-footer-overlay { background-color: ' + astra_hex2rgba( bg_color,bg_color_opac ) + ';}';
				}
				else{
					var dynamicStyle  = '.ast-small-footer > .ast-footer-overlay { background-color: ' + bg_color + ';}';
				}
					dynamicStyle += '.ast-small-footer { background-image: url(' + bg_img + '); }';
				astra_add_dynamic_css( 'footer-bg-img', dynamicStyle );
			}

		} );
	} );


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
	wp.customize( 'astra-settings[primary-menu-color]', function( setting ) {
		setting.bind( function( menu_link_text ) {
			if ( menu_link_text != '' ) {
				var dynamicStyle = '#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a{ color: ' + menu_link_text + '}';
				astra_add_dynamic_css( 'sticky-header-primary-menu-link-color', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		});
	});

	/**
	 * Sticky Header Primary Menu Active Link  color
	 */
	wp.customize( 'astra-settings[primary-menu-a-color]', function( setting ) {
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
	wp.customize( 'astra-settings[primary-menu-a-bg-color]', function( setting ) {
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
	wp.customize( 'astra-settings[primary-menu-h-color]', function( setting ) {
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
	wp.customize( 'astra-settings[primary-menu-h-bg-color]', function( setting ) {
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


	/* Transparent Header Colors */
	
	/**
	 * Transparent Header Bg Color
	 */
	wp.customize( 'astra-settings[transparent-header-bg-color]', function( value ) {
		value.bind( function( color ) {

			if( '' != color ) {

				var dynamicStyle = '';
				/**
				 * Transparent Color tweak
				 */
				dynamicStyle += '.ast-theme-transparent-header .main-header-bar { background-color: ' + color + '; } ';
				dynamicStyle += '.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form { background-color: ' + color + ' } ';
				dynamicStyle += '.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field { background-color: ' + color + ' } ';
				dynamicStyle += '.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field:focus { background-color: ' + color + ' }';

				astra_add_dynamic_css( 'transparent-header-bg-color', dynamicStyle );
				
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/* Site Title */
	astra_css( 'astra-settings[transparent-header-color-site-title]', 'color', '.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited, .ast-theme-transparent-header .site-header .site-description' );
	astra_css( 'astra-settings[transparent-header-color-h-site-title]', 'color', '.ast-theme-transparent-header .site-header .site-title a:hover' );

	/* Primary Menu */
	astra_css( 'astra-settings[transparent-menu-bg-color]', 'background-color', '.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-menu, .ast-theme-transparent-header .ast-masthead-custom-menu-items' );
	astra_css( 'astra-settings[transparent-menu-color]', 'color', '.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a, .ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a' );
	astra_css( 'astra-settings[transparent-menu-h-color]', 'color', '.ast-theme-transparent-header .main-header-menu li.current-menu-item > a, .ast-theme-transparent-header .main-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu li.current_page_item > a, .ast-theme-transparent-header .main-header-menu a:hover, .ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li.focus > a, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li.focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover' );
} )( jQuery );
