( function( $ ) {
	"use strict";

	$( document ).on( 'ready', function() {

		// Show/hide title options
		var titleField 		= $( '#butterbean-control-op_portfolio_title .buttonset-input' ),
			titleSettings   = $( '#butterbean-control-op_portfolio_title_tag, #butterbean-control-op_portfolio_title_color, #butterbean-control-op_portfolio_title_hover_color' );

		if ( $( '#butterbean-control-op_portfolio_title #butterbean_op_portfolio_settings_setting_op_portfolio_title_off' ).is( ':checked' ) ) {
			titleSettings.hide();
		} else {
			titleSettings.show();
		}

		titleField.change( function () {

			if ( $( this ).val() === 'off' ) {
				titleSettings.hide();
			} else {
				titleSettings.show();
			}

		} );

		// Show/hide pagination option
		var paginationField 	= $( '#butterbean-control-op_portfolio_pagination .buttonset-input' ),
			paginationSetting 	= $( '#butterbean-control-op_portfolio_pagination_position' );

		if ( $( '#butterbean-control-op_portfolio_pagination #butterbean_op_portfolio_settings_setting_op_portfolio_pagination_off' ).is( ':checked' ) ) {
			paginationSetting.hide();
		} else {
			paginationSetting.show();
		}

		paginationField.change( function () {

			if ( $( this ).val() === 'off' ) {
				paginationSetting.hide();
			} else {
				paginationSetting.show();
			}

		} );

		// Show/hide filter options
		var filterField 		= $( '#butterbean-control-op_portfolio_filter .buttonset-input' ),
			filterSettings 		= $( '#butterbean-control-op_portfolio_all_filter, #butterbean-control-op_portfolio_filter_position, #butterbean-control-op_portfolio_filter_taxonomy, #butterbean-control-op_portfolio_responsive_filter_links, #butterbean-control-op_portfolio_filter_margin,  #butterbean-control-op_portfolio_filter_links_margin,  #butterbean-control-op_portfolio_filter_links_padding,  #butterbean-control-op_portfolio_filter_links_bg,  #butterbean-control-op_portfolio_filter_links_color,  #butterbean-control-op_portfolio_filter_active_link_bg,  #butterbean-control-op_portfolio_filter_active_link_color,  #butterbean-control-op_portfolio_filter_hover_links_bg,  #butterbean-control-op_portfolio_filter_hover_links_color' );

		if ( $( '#butterbean-control-op_portfolio_filter #butterbean_op_portfolio_settings_setting_op_portfolio_filter_off' ).is( ':checked' ) ) {
			filterSettings.hide();
		} else {
			filterSettings.show();
		}

		filterField.change( function () {

			if ( $( this ).val() === 'off' ) {
				filterSettings.hide();
			} else {
				filterSettings.show();
			}

		} );

		// Show/hide responsive filter links options
		var filterLinks 				= $( '#butterbean-control-op_portfolio_responsive_filter_links select' ),
			filterLinksVal 				= filterLinks.val(),
			filterLinksCustomSettings 	= $( '#butterbean-control-op_portfolio_responsive_filter_links_custom' );

		filterLinksCustomSettings.hide();

		if ( $( '#butterbean-control-op_portfolio_filter #butterbean_op_portfolio_settings_setting_op_portfolio_filter_on' ).is( ':checked' )
			&& filterLinksVal === 'custom' ) {
			filterLinksCustomSettings.show();
		} else {
			filterLinksCustomSettings.hide();
		}

		filterField.change( function () {

			var filterLinksVal 	= filterLinks.val();

			if ( $( this ).val() === 'on'
				&& filterLinksVal === 'custom' ) {
				filterLinksCustomSettings.show();
			} else {
				filterLinksCustomSettings.hide();
			}

		} );

		filterLinks.change( function () {

			var filterLinksVal 	= filterLinks.val();

			if ( filterLinksVal === 'custom' ) {
				filterLinksCustomSettings.show();
			} else {
				filterLinksCustomSettings.hide();
			}

		} );

		// Show/hide image options
		var imageSize 			= $( '#butterbean-control-op_portfolio_size select' ),
			imageSizeVal 		= imageSize.val(),
			imageCustomSettings = $( '#butterbean-control-op_portfolio_img_width, #butterbean-control-op_portfolio_img_height' );

		imageCustomSettings.hide();

		if ( imageSizeVal === 'custom' ) {
			imageCustomSettings.show();
		} else {
			imageCustomSettings.hide();
		}

		imageSize.change( function () {

			var imageSizeVal = imageSize.val();

			if ( imageSizeVal === 'custom' ) {
				imageCustomSettings.show();
			} else {
				imageCustomSettings.hide();
			}

		} );

		// Show/hide overlay option
		var overlayField 			= $( '#butterbean-control-op_portfolio_img_overlay_icons .buttonset-input' ),
			overlaySettings 		= $( '#butterbean-control-op_portfolio_img_overlay_icons_width, #butterbean-control-op_portfolio_img_overlay_icons_height, #butterbean-control-op_portfolio_img_overlay_icons_size, #butterbean-control-op_portfolio_img_overlay_icons_bg, #butterbean-control-op_portfolio_img_overlay_icons_hover_bg, #butterbean-control-op_portfolio_img_overlay_icons_color, #butterbean-control-op_portfolio_img_overlay_icons_hover_color, #butterbean-control-op_portfolio_img_overlay_icons_border_radius, #butterbean-control-op_portfolio_img_overlay_icons_border_width, #butterbean-control-op_portfolio_img_overlay_icons_border_style, #butterbean-control-op_portfolio_img_overlay_icons_border_color, #butterbean-control-op_portfolio_img_overlay_icons_hover_border_color' );

		if ( $( '#butterbean-control-op_portfolio_img_overlay_icons #butterbean_op_portfolio_settings_setting_op_portfolio_img_overlay_icons_off' ).is( ':checked' ) ) {
			overlaySettings.hide();
		} else {
			overlaySettings.show();
		}

		overlayField.change( function () {

			if ( $( this ).val() === 'off' ) {
				overlaySettings.hide();
			} else {
				overlaySettings.show();
			}

		} );

		// Show/hide outside options
		var outsideField 			= $( '#butterbean-control-op_portfolio_title_cat_position .buttonset-input' ),
			outsideSettings 		= $( '#butterbean-control-op_portfolio_outside_content_padding, #butterbean-control-op_portfolio_outside_content_bg' );

		if ( $( '#butterbean-control-op_portfolio_title_cat_position #butterbean_op_portfolio_settings_setting_op_portfolio_title_cat_position_inside' ).is( ':checked' ) ) {
			outsideSettings.hide();
		} else {
			outsideSettings.show();
		}

		outsideField.change( function () {

			if ( $( this ).val() === 'inside' ) {
				outsideSettings.hide();
			} else {
				outsideSettings.show();
			}

		} );

		// Show/hide category options
		var categoryField 		= $( '#butterbean-control-op_portfolio_category .buttonset-input' ),
			categorySettings   	= $( '#butterbean-control-op_portfolio_category_color, #butterbean-control-op_portfolio_category_hover_color' );

		if ( $( '#butterbean-control-op_portfolio_category #butterbean_op_portfolio_settings_setting_op_portfolio_category_off' ).is( ':checked' ) ) {
			categorySettings.hide();
		} else {
			categorySettings.show();
		}

		categoryField.change( function () {

			if ( $( this ).val() === 'off' ) {
				categorySettings.hide();
			} else {
				categorySettings.show();
			}

		} );

	} );

} ) ( jQuery );