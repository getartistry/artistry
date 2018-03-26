/**
 *  Scroll To Top
 *
 * @package Astra Addon
 * @since  1.0.0
 */

(function ($) {
	jQuery( document ).ready( function($) {

		var masthead        = document.querySelector( '#page header' );
		if ( jQuery( '#ast-scroll-top' ) && jQuery( '#ast-scroll-top' ).length ) {
				ast_scroll_top = function () {
					
					var ast_scroll_top = jQuery( '#ast-scroll-top' ),
						content = ast_scroll_top.css('content'),
						device  = ast_scroll_top.data('on-devices');
						content = content.replace( /[^0-9]/g, '' );

					if( 'both' == device || ( 'desktop' == device && '769' == content ) || ( 'mobile' == device && '' == content ) ) {

						// Get current window / document scroll.
						var  scrollTop = window.pageYOffset || document.body.scrollTop;
						// If masthead found.
						if( masthead && masthead.length ){
							if (scrollTop > masthead.offsetHeight + 100) {
					            ast_scroll_top.show();
							} else {
					            ast_scroll_top.hide();
							}
						}
						else{
							// If there is no masthead set default start scroll
							if ( jQuery( window ).scrollTop() > 300 ) {
					            ast_scroll_top.show();
							} else {
					            ast_scroll_top.hide();
							}	
						}
					} else {
						ast_scroll_top.hide();
					}
				};
			ast_scroll_top();
			jQuery(window).on('scroll', function () {
			    ast_scroll_top();
			});
			jQuery('#ast-scroll-top').on('click', function (e) {
			    e.preventDefault();
			    jQuery('html,body').animate({
			        scrollTop: 0
			    }, 200);
			});
		}
	});

})(jQuery);
