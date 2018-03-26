/**
 * Canvas Sidebar
 *
 * @since 1.1.0
 */

(function ($) {

	var off_canvas_wrapper = $( '.astra-off-canvas-sidebar-wrapper');
	
	var ast_shop_offcanvas_filter_close = function() {
		$('html').css({
			'overflow': '',
			'margin-right': '' 
		});

		$('html').removeClass( 'astra-enabled-overlay' );
	};

	var trigger_class = 'astra-shop-filter-button';
	if( 'undefined' != typeof Astra_Off_Canvas && '' != Astra_Off_Canvas.off_canvas_trigger_class ) {
		trigger_class = Astra_Off_Canvas.off_canvas_trigger_class;
	}
	$(document).on( 'click', '.' + trigger_class, function(e) {
		e.preventDefault();

		var innerWidth = $('html').innerWidth();
		$('html').css( 'overflow', 'hidden' );
		var hiddenInnerWidth = $('html').innerWidth();
		$('html').css( 'margin-right', hiddenInnerWidth - innerWidth );

		$('html').addClass( 'astra-enabled-overlay' );
	});

	off_canvas_wrapper.on('click', function(e) {
		if ( e.target === this ) {
			ast_shop_offcanvas_filter_close();
		}
	});

	off_canvas_wrapper.find('.ast-shop-filter-close').on('click', function(e) {
		ast_shop_offcanvas_filter_close();
	});
})(jQuery);