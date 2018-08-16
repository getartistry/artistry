jQuery(document).ready(function($){
	"use strict";

	if( typeof astra === 'undefined' ) {
        return;
    }

	var ast_quick_view_bg    	= $(document).find( '.ast-quick-view-bg' ),
		ast_qv_modal    	= $(document).find( '#ast-quick-view-modal' ),
		ast_qv_content  	= ast_qv_modal.find( '#ast-quick-view-content' ),
		ast_qv_close_btn 	= ast_qv_modal.find( '#ast-quick-view-close' ),
		ast_qv_wrapper  	= ast_qv_modal.find( '.ast-content-main-wrapper'),
		ast_qv_wrapper_w 	= ast_qv_wrapper.width(),
		ast_qv_wrapper_h 	= ast_qv_wrapper.height();

	var	ast_qv_center_modal = function() {
		
		ast_qv_wrapper.css({
			'width'     : '',
			'height'    : ''
		});

		ast_qv_wrapper_w 	= ast_qv_wrapper.width(),
		ast_qv_wrapper_h 	= ast_qv_wrapper.height();

		var window_w = $(window).width(),
			window_h = $(window).height(),
			width    = ( ( window_w - 60 ) > ast_qv_wrapper_w ) ? ast_qv_wrapper_w : ( window_w - 60 ),
			height   = ( ( window_h - 120 ) > ast_qv_wrapper_h ) ? ast_qv_wrapper_h : ( window_h - 120 );

		ast_qv_wrapper.css({
			'left' : (( window_w/2 ) - ( width/2 )),
			'top' : (( window_h/2 ) - ( height/2 )),
			'width'     : width + 'px',
			'height'    : height + 'px'
		});
	};

	var ast_update_summary_height = function() {
		var quick_view 		   = $(document).find('#ast-quick-view-content'),
			img_height 		   = quick_view.find( '.product .ast-qv-image-slider' ).first().height(),
			summary    		   = quick_view.find('.product .summary.entry-summary'),
			content    		   = summary.css('content'),
			summary_content_ht = quick_view.find( '.summary-content' ).height();

		if ( 'undefined' != typeof content && 544 == content.replace( /[^0-9]/g, '' ) && 0 != summary_content_ht && null !== summary_content_ht ) {
			summary.css('height', summary_content_ht );
		} else {
			summary.css('height', '' );
		}
	};

	var ast_qv_btn = function() {

		var on_img_click_els = $('.ast-qv-on-image-click .astra-shop-thumbnail-wrap .woocommerce-LoopProduct-link');

		if ( on_img_click_els.length > 0 ) {

			on_img_click_els.each(function(e) {
				$(this).attr('href', 'javascript:void(0)' );
			});
		}

        $(document).off( 'click', '.ast-quick-view-button, .ast-quick-view-text, .ast-qv-on-image-click .astra-shop-thumbnail-wrap .woocommerce-LoopProduct-link' ).on( 'click', '.ast-quick-view-button, .ast-quick-view-text, .ast-qv-on-image-click .astra-shop-thumbnail-wrap .woocommerce-LoopProduct-link', function(e){
			e.preventDefault();

			var $this       = $(this),
				wrap 		= $this.closest('li.product');
				
			
				if ( wrap.hasClass( 'ast-qv-on-image-click' )  ) {
					var product_id  = wrap.find('.ast-quick-view-data').data( 'product_id' );
				}else{
					var product_id  = $this.data( 'product_id' );
				}

				if( ! ast_qv_modal.hasClass( 'loading' ) ) {
					ast_qv_modal.addClass('loading');
				}

				if ( ! ast_quick_view_bg.hasClass( 'ast-quick-view-bg-ready' ) ) {
					ast_quick_view_bg.addClass( 'ast-quick-view-bg-ready' );
				}

				// stop loader
				$(document).trigger( 'ast_quick_view_loading' );

			ast_qv_ajax_call( $this, product_id );
		});
	};

	var ast_qv_ajax_call = function( t, product_id ) {

		$.ajax({
            url: astra.ajax_url,
			data: {
				action: 'ast_load_product_quick_view',
				product_id: product_id
			},
			dataType: 'html',
			type: 'POST',
			success: function (data) {

				ast_qv_content.html(data);
				ast_qv_content_height();
			}
		});
	};

	var ast_qv_content_height = function() {

		// Variation Form
		var form_variation = ast_qv_content.find('.variations_form');

		form_variation.trigger( 'check_variations' );
		form_variation.trigger( 'reset_image' );

		if (!ast_qv_modal.hasClass('open')) {
			
			ast_qv_modal.removeClass('loading').addClass('open');

			var modal_height = ast_qv_modal.find( '#ast-quick-view-content' ).outerHeight();
			var window_height = $(window).height();
			var scrollbar_width = ast_get_scrollbar_width();
			var $html = $('html');

			if( modal_height > window_height ) {
				$html.css( 'margin-right', scrollbar_width );
			} else {
				$html.css( 'margin-right', '' );
				$html.find( '.ast-sticky-active, .ast-header-sticky-active, .ast-custom-footer' ).css( 'max-width', '100%' );
			}

			$html.addClass('ast-quick-view-is-open');
		}

		var var_form = ast_qv_modal.find('.variations_form');

		if ( var_form.length > 0 ) {
			var_form.wc_variation_form();
			var_form.find('select').change();
		}

			var image_slider_wrap = ast_qv_modal.find('.ast-qv-image-slider');

			if ( image_slider_wrap.find('li').length > 1 ) {
				image_slider_wrap.flexslider({
				animation: "slide"
				});
			}

			setTimeout(function() {
				ast_update_summary_height();
			}, 100);
		// stop loader
		$(document).trigger('ast_quick_view_loader_stop');
	};

	var ast_qv_close_modal = function() {

		// Close box by click overlay
		$('.ast-content-main-wrapper').on( 'click', function(e){
			
			if ( this === e.target ) {
				ast_qv_close();
			} 
		});
        
		// Close box with esc key
		$(document).keyup(function(e){
			if( e.keyCode === 27 ) {
				ast_qv_close();
			}
		});

		// Close box by click close button
		ast_qv_close_btn.on( 'click', function(e) {
			e.preventDefault();
			ast_qv_close();
		});

		var ast_qv_close = function() {
			ast_quick_view_bg.removeClass( 'ast-quick-view-bg-ready' );
			ast_qv_modal.removeClass('open').removeClass('loading');
			$('html').removeClass('ast-quick-view-is-open');
			$('html').css( 'margin-right', '' );

			setTimeout(function () {
				ast_qv_content.html('');
			}, 600);
		}
	};

	var ast_get_scrollbar_width = function () { 
		
		var div = $('<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div>'); 
		// Append our div, do our calculation and then remove it 
		$('body').append(div); 
		var w1 = $('div', div).innerWidth(); 
		div.css('overflow-y', 'scroll'); 
		var w2 = $('div', div).innerWidth(); 
		$(div).remove();

		return (w1 - w2); 
	}

	window.addEventListener("resize", function(event) {
		ast_update_summary_height();
	});

	// START
	ast_qv_btn();
	ast_qv_close_modal();
	ast_update_summary_height();

});
