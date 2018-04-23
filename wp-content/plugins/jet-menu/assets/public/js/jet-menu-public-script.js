(function( $ ){
	"use strict";

	var jetMenu = {

		init: function() {
			var rollUp                   = false,
				jetMenuMouseleaveDelay   = 500,
				jetMenuMegaWidthType     = 'container',
				jetMenuMegaWidthSelector = '';

			if ( window.jetMenuPublicSettings && window.jetMenuPublicSettings.menuSettings ) {
				rollUp = ( 'true' === jetMenuPublicSettings.menuSettings.jetMenuRollUp ) ? true : false;
				jetMenuMouseleaveDelay = jetMenuPublicSettings.menuSettings.jetMenuMouseleaveDelay || 500;
				jetMenuMegaWidthType = jetMenuPublicSettings.menuSettings.jetMenuMegaWidthType || 'container';
				jetMenuMegaWidthSelector = jetMenuPublicSettings.menuSettings.jetMenuMegaWidthSelector || '';
			}

			$( '.jet-menu-container' ).JetMenu( {
				enabled: rollUp,
				mouseLeaveDelay: +jetMenuMouseleaveDelay,
				megaWidthType: jetMenuMegaWidthType,
				megaWidthSelector: jetMenuMegaWidthSelector
			} );

		},

	};

	jetMenu.init();

}( jQuery ));
