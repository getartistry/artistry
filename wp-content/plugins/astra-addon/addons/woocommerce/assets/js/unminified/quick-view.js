jQuery(document).ready(function($){
	"use strict";

	if( typeof astra === 'undefined' ) {
        return;
    }

	var ast_qv_loader_url 		= astra.qv_loader,
		ast_quick_view_bg    	= $(document).find( '.ast-quick-view-bg' ),
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

		var quick_view = $(document).find('#ast-quick-view-content'),
			img_height = quick_view.find( '.product .ast-qv-image-slider' ).first().height(),
			summary    = quick_view.find('.product .summary.entry-summary'),
			content    = summary.css('content');


		if ( 'undefined' != typeof content && 544 == content.replace( /[^0-9]/g, '' ) ) {
			summary.css('height', img_height );
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

			if ( ast_qv_loader_url ) {

				if( ! ast_qv_modal.hasClass( 'loading' ) ) {
					ast_qv_modal.addClass('loading');
				}

				if ( ! ast_quick_view_bg.hasClass( 'ast-quick-view-bg-ready' ) ) {
					ast_quick_view_bg.addClass( 'ast-quick-view-bg-ready' );
				}

				// stop loader
				$(document).trigger( 'ast_quick_view_loading' );
			}

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

				// Variation Form
				var form_variation = ast_qv_content.find('.variations_form');

				form_variation.trigger( 'check_variations' );
				form_variation.trigger( 'reset_image' );

				if (!ast_qv_modal.hasClass('open')) {
					
					ast_qv_modal.removeClass('loading').addClass('open');

					var scrollbar_width = ast_get_scrollbar_width();
					var $html = $('html');

					$html.css( 'margin-right', scrollbar_width );
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
			}
		});
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

	/* Add to cart ajax */
	/**
	 * ast_add_to_cart_ajax class.
	 */
	var ast_add_to_cart_ajax = function() {
		$( document.body )
			.on( 'click', '#ast-quick-view-content .single_add_to_cart_button', this.onAddToCart )
			.on( 'added_to_cart', this.updateButton );
	};
	
	/**
	 * Handle the add to cart event.
	 */
	ast_add_to_cart_ajax.prototype.onAddToCart = function( e ) {

		e.preventDefault();

		var $thisbutton = $( this ),
			product_id = $(this).val(),
			variation_id = $('input[name="variation_id"]').val() || '',
			quantity = $('input[name="quantity"]').val();

		if ( $thisbutton.is( '.single_add_to_cart_button' ) ) {

			$thisbutton.removeClass( 'added' );
			$thisbutton.addClass( 'loading' );

			// Ajax action.
			if ( variation_id != '') {
				jQuery.ajax ({
					url: astra.ajax_url,
					type:'POST',
					data:'action=astra_add_cart_single_product&product_id=' + product_id + '&variation_id=' + variation_id + '&quantity=' + quantity,

					success:function(results) {
						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).trigger( 'added_to_cart', [ $thisbutton ] );
					}
				});
			} else {
				jQuery.ajax ({
					url: astra.ajax_url,
					type:'POST',
					data:'action=astra_add_cart_single_product&product_id=' + product_id + '&quantity=' + quantity,

					success:function(results) {
						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).trigger( 'added_to_cart', [ $thisbutton ] );
					}
				});
			}
		}
	};

	/**
	 * Update cart page elements after add to cart events.
	 */
	ast_add_to_cart_ajax.prototype.updateButton = function( e, button ) {
		button = typeof button === 'undefined' ? false : button;

		if ( $(button) ) {
			$(button).removeClass( 'loading' );
			$(button).addClass( 'added' );

			// View cart text.
			if ( ! astra.is_cart && $(button).parent().find( '.added_to_cart' ).length === 0  && astra.is_single_product) {
				$(button).after( ' <a href="' + astra.cart_url + '" class="added_to_cart wc-forward" title="' +
					astra.view_cart + '">' + astra.view_cart + '</a>' );
			}


		}
	};


	window.addEventListener("resize", function(event) {
		ast_update_summary_height();
	});

	// START
	ast_qv_btn();
	ast_qv_close_modal();
	ast_update_summary_height();

	/**
	 * Init ast_add_to_cart_ajax.
	 */
	new ast_add_to_cart_ajax();
});
