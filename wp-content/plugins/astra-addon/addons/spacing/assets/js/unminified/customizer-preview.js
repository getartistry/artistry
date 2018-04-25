/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	/**
	 * Site Identity Spacing
	 */
	astra_responsive_spacing( 'astra-settings[site-identity-spacing]','.site-header .ast-site-identity', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Header Spacing
	 */
	astra_responsive_spacing( 'astra-settings[header-spacing]','.main-header-bar, .ast-header-break-point .main-header-bar, .ast-header-break-point .header-main-layout-2 .main-header-bar', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[header-spacing]','#masthead .ast-container, .main-header-bar .ast-container', 'padding', ['right', 'left' ] );	

	// Remove padding bottom to header elements if padding bottom is given to header.
	wp.customize( 'astra-settings[header-spacing]', function( value ) {
		value.bind( function( padding ) {

			if( padding.desktop.bottom != '' || padding.tablet.bottom != '' || padding.mobile.bottom != '' ) {
				var dynamicStyle = '';
				dynamicStyle += '.ast-header-break-point .site-branding, .ast-header-break-point .ast-mobile-menu-buttons, .ast-header-break-point.ast-header-custom-item-outside .ast-masthead-custom-menu-items, .ast-header-break-point .header-main-layout-2 .ast-mobile-menu-buttons { padding-bottom: 0px;} ';
				dynamicStyle +=  '@media (max-width: 768px) { .ast-header-break-point .main-header-bar .main-header-bar-navigation { padding-top:' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';} }';
				dynamicStyle +=  '@media (max-width: 544px) { .ast-header-break-point .main-header-bar .main-header-bar-navigation { padding-top:' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';} }';
				console.log( dynamicStyle );
				astra_add_dynamic_css( 'remove-add-header-spacing', dynamicStyle );
			}

		} );
	} );	

	/**
	 * Primary Menu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[primary-menu-spacing]', '.main-navigation ul li a, .ast-header-break-point .main-navigation ul li a, .ast-header-break-point li.ast-masthead-custom-menu-items, li.ast-masthead-custom-menu-items', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Primary Submenu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[primary-submenu-spacing]', '.main-header-menu ul a, .ast-header-break-point .main-navigation ul.sub-menu li a, .ast-header-break-point .main-navigation ul.children li a', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Below Header Spacing
	 */
	astra_responsive_spacing( 'astra-settings[below-header-spacing]','.ast-below-header, .ast-header-break-point .ast-below-header', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[below-header-spacing]','.ast-below-header .ast-container', 'padding', ['right', 'left' ] );
	/**
	 * Below Header Menu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[below-header-menu-spacing]', '.ast-below-header-menu a, .below-header-nav-padding-support .below-header-section-1 .below-header-menu > li > a, .below-header-nav-padding-support .below-header-section-2 .below-header-menu > li > a, .ast-header-break-point .ast-below-header-actual-nav > ul > li > a', 'padding', ['top', 'right', 'bottom', 'left' ] );
	/**
	 * Below Header Submenu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[below-header-submenu-spacing]', '.ast-below-header-menu ul a, .ast-header-break-point .ast-below-header-actual-nav > ul ul li a', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Above Header Spacing
	 */
	astra_responsive_spacing( 'astra-settings[above-header-spacing]','.ast-above-header', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[above-header-spacing]','.ast-above-header .ast-container', 'padding', ['right', 'left' ] );

	/**
	 * Above Header Menu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[above-header-menu-spacing]', '.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li > a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu > li > a,.above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:first-child > a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:last-child > a', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Above Header Subenu Spacing
	 */
	astra_responsive_spacing( 'astra-settings[above-header-submenu-spacing]', '.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu li ul a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu li ul a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu li ul.sub-menu a,.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li:first-child .sub-menu li a', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Container Outside Spacing
	 */
	astra_responsive_spacing( 'astra-settings[container-outside-spacing]','.ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary, .ast-plain-container #primary', 'margin',  ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[container-outside-spacing]','.ast-left-sidebar #primary, .ast-right-sidebar #primary, .ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary', 'padding',  ['left', 'right' ] );
	// Remove padding top to container if padding top is given to Container Outer Spacing.
	wp.customize( 'astra-settings[container-outside-spacing]', function( value ) {
		value.bind( function( padding ) {

			var dynamicStyle = '';
			if( padding.desktop.top != '' || padding.tablet.top != '' || padding.mobile.top != '' ) {
				dynamicStyle += '.ast-separate-container #primary { padding-top: 0px;} ';
			}
			if( padding.desktop.bottom != '' || padding.tablet.bottom != '' || padding.mobile.bottom != '' ) {
				dynamicStyle += '.ast-separate-container #primary { padding-bottom: 0px;} ';
			}
			astra_add_dynamic_css( 'remove-header-spacing', dynamicStyle );

		} );
	} );
	/**
	 * Boxed Content Spacing
	 */
	astra_responsive_spacing( 'astra-settings[container-inside-spacing]','.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond, .single.ast-separate-container .ast-author-details, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container', 'padding', [ 'top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[container-inside-spacing]','.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single,.ast-separate-container .comments-count-wrapper, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond,.ast-separate-container .related-posts-title-wrapper,.ast-separate-container .related-posts-title-wrapper, .single.ast-separate-container .ast-author-details, .single.ast-separate-container .about-author-title-wrapper, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container', 'padding', [ 'left', 'right' ] );
	// Remove Featured Image Padding for single posty
	wp.customize( 'astra-settings[container-inside-spacing]', function( setting ) {
		setting.bind( function( padding ) {

			if ( padding.desktop.top  || padding.desktop.left || padding.desktop.right || padding.tablet.top || padding.tablet.left || padding.tablet.right || padding.mobile.top || padding.mobile.left || padding.mobile.right ) {
				var dynamicStyle =  '.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child { margin-top: -' + padding['desktop']['top'] + padding['desktop-unit'] + ';} .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content{ margin-left: -' + padding['desktop']['left'] + padding['desktop-unit'] + '; margin-right: -' + padding['desktop']['right'] + padding['desktop-unit'] + ';}';
				dynamicStyle +=  '@media (max-width: 768px) { .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child { margin-top: -' + padding['tablet']['top'] + padding['tablet-unit'] + ';} .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content{ margin-left: -' + padding['tablet']['left'] + padding['tablet-unit'] + '; margin-right: -' + padding['tablet']['right'] + padding['tablet-unit'] + ';} }';
				dynamicStyle +=  '@media (max-width: 544px) { .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child { margin-top: -' + padding['mobile']['top'] + padding['mobile-unit'] + ';} .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content{ margin-left: -' + padding['mobile']['left'] + padding['mobile-unit'] + '; margin-right: -' + padding['mobile']['right'] + padding['mobile-unit'] + ';} }';
				astra_add_dynamic_css( 'single-post-featured-image-spacing', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Sidebar Spacing Plain/One Boxed Content
	 */
	astra_responsive_spacing( 'astra-settings[sidebar-outside-spacing]','.ast-plain-container #secondary,.ast-separate-container #secondary, .ast-page-builder-template #secondary', 'margin', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[sidebar-outside-spacing]','.ast-right-sidebar #secondary, .ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-right-sidebar #secondary, .ast-separate-container.ast-right-sidebar #secondary, .ast-separate-container.ast-left-sidebar #secondary', 'padding', ['left', 'right' ] );
	// Container - Boxed layout is selected then remove individual Sidebar widget margin bottom.
	astra_responsive_spacing( 'astra-settings[sidebar-outside-spacing]','.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget', 'margin', [ 'bottom' ] );

	/**
	 * Sidebar Spacing Plain/One Boxed Content
	 */
	astra_responsive_spacing( 'astra-settings[sidebar-inside-spacing]','.ast-two-container.ast-right-sidebar #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[sidebar-inside-spacing]','.ast-two-container.ast-right-sidebar #secondary .widget, .ast-two-container.ast-left-sidebar #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget', 'padding', ['left', 'right' ] );

	/**
	 * Footer Spacing
	 */
	astra_responsive_spacing( 'astra-settings[footer-sml-spacing]','.ast-footer-overlay', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[footer-sml-spacing]','.ast-small-footer .ast-container', 'padding', ['left', 'right' ] );

	/**
	 * Footer Menu Spacing
	 */
	 astra_responsive_spacing( 'astra-settings[footer-menu-spacing]', '.ast-small-footer .nav-menu a, .footer-sml-layout-2 .ast-small-footer-section-1 .menu-item a, .footer-sml-layout-2 .ast-small-footer-section-2 .menu-item a', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Blog Grid Spacing
	 */
	 // Apply margin only if grid is selected 1 column or Blog Layout is selected as 2 or 3.
	if ( ! ast_preview.blog_pro_enabled || jQuery( 'body' ).hasClass( 'ast-blog-grid-1' ) || jQuery( 'body' ).hasClass( 'ast-blog-layout-2' ) || jQuery( 'body' ).hasClass( 'ast-blog-layout-3' ) ) {
		astra_responsive_spacing( 'astra-settings[blog-post-outside-spacing]', '.ast-separate-container .ast-article-post, .ast-separate-container .ast-separate-posts.ast-article-post', 'margin', ['top', 'right', 'bottom', 'left' ] );
		astra_responsive_spacing( 'astra-settings[blog-post-inside-spacing]', '.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post', 'padding', ['top', 'right', 'bottom', 'left' ] );
	} else{
		// Blog Grid Outside Spacing.
		astra_responsive_spacing( 'astra-settings[blog-post-outside-spacing]', '.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+1)', 'padding', ['top', 'right', 'bottom', 'left' ] );
		// Reset Masonary.
		wp.customize( 'astra-settings[blog-post-outside-spacing]', function( setting ) {
			setting.bind( function( margin ) {
				var dynamicStyle = '';
				if( margin.desktop.bottom != '' || margin.tablet.bottom != '' || margin.mobile.bottom != '' ) {
					dynamicStyle += '.ast-separate-container .ast-separate-posts.ast-article-post{ margin-bottom: 0px;} ';
				}
				astra_add_dynamic_css( 'remove-blog-outside-spacing', dynamicStyle );

				var gird_layout     = (typeof ( wp.customize._value['astra-settings[blog-grid]'] ) != 'undefined') ? wp.customize._value['astra-settings[blog-grid]']._value : '';
				if ( 1 != gird_layout ) {
					masonaryLaoyoutReset();
				}
			} );
		} );

		// Blog Grid Inside Spacing.
		astra_responsive_spacing( 'astra-settings[blog-post-inside-spacing]', '.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3', 'padding', ['top', 'right', 'bottom', 'left' ] );
		// Reset Masonary.
		wp.customize( 'astra-settings[blog-post-inside-spacing]', function( setting ) {
			setting.bind( function( padding ) {
				var gird_layout     = (typeof ( wp.customize._value['astra-settings[blog-grid]'] ) != 'undefined') ? wp.customize._value['astra-settings[blog-grid]']._value : '';
				if ( 1 != gird_layout ) {
					masonaryLaoyoutReset();
				}
			} );
		} );
	}

	// Remove Margin / Padding around featured iamge, date box Masonary.
	// Remove Featured Image Padding.
	wp.customize( 'astra-settings[blog-post-inside-spacing]', function( setting ) {
		setting.bind( function( padding ) {

			if ( padding.desktop.top  || padding.desktop.left || padding.desktop.right || padding.tablet.top || padding.tablet.left || padding.tablet.right || padding.mobile.top || padding.mobile.left || padding.mobile.right ) {
				var dynamicStyle =  '.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on { margin-top: -' + padding['desktop']['top'] + padding['desktop-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['desktop']['left'] + padding['desktop-unit'] + '; margin-right: -' + padding['desktop']['right'] + padding['desktop-unit'] + ';}';
				dynamicStyle +=  '@media (max-width: 768px) { .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on{ margin-top: -' + padding['tablet']['top'] + padding['tablet-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['tablet']['left'] + padding['tablet-unit'] + '; margin-right: -' + padding['tablet']['right'] + padding['tablet-unit'] + ';} }';
				dynamicStyle +=  '@media (max-width: 544px) { .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on{ margin-top: -' + padding['mobile']['top'] + padding['mobile-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['mobile']['left'] + padding['mobile-unit'] + '; margin-right: -' + padding['mobile']['right'] + padding['mobile-unit'] + ';} }';
				astra_add_dynamic_css( 'blog-post-inside-spacing', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );
	
	/**
	 * Blog Post pagination Spacing
	 */
	 astra_responsive_spacing( 'astra-settings[blog-post-pagination-spacing]', '.ast-pagination', 'padding', [ 'top', 'right', 'bottom', 'left' ] );
	/**
	 * Reset Masonary for custommizer preview scree
	 */
	function masonaryLaoyoutReset(){

		// Internet Explorer 6-11
		isIE = /*@cc_on!@*/false || !!document.documentMode;

		// Edge 20+
		isEdge = !isIE && !!window.StyleMedia;

		var masonryEnabled  = astra.masonryEnabled || false;
		var blogMasonryBreakPoint = astra.blogMasonryBreakPoint;

		var blogMasonryBp = window.getComputedStyle( jQuery('#content')[0] ).content;

		// Edge/Explorer header break point.
		if( isEdge || isIE || blogMasonryBp === 'normal' ) {
			if( window.innerWidth >= blogMasonryBreakPoint ) {
				blogMasonryBp = blogMasonryBreakPoint;
			}
		} else {
			blogMasonryBp = blogMasonryBp.replace( /[^0-9]/g, '' );
			blogMasonryBp = parseInt( blogMasonryBp );
		}

		var container = jQuery( '.search.blog-masonry #main > div, .blog.blog-masonry #main > div, .archive.blog-masonry #main > div' );

		if ( blogMasonryBp == blogMasonryBreakPoint ) {
			if (masonryEnabled) {

				if ( typeof container != 'undefined' &&  container.length > 0 ) {

					var hasMasonry = container.data('masonry') ? true : false

					if ( hasMasonry ) {
						container.masonry('reload');
					}else{
						container.imagesLoaded(container, function () {
							container.masonry({
								itemSelector: '#primary article',
							});
						});
					}
				}
			}
		} else{
			if (  masonryEnabled ) {
				if ( typeof container != 'undefined' &&  container.length > 0 ) {
					container.masonry().masonry( 'destroy' );
				}
			}
		}
	}
} )( jQuery );
