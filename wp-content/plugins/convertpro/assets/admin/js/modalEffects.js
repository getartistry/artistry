/**
 * modalEffects.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
var ModalEffects = (function() {

	function init() {

		var overlay = jQuery( '.cp-md-overlay' );

		jQuery(".cp-md-trigger").each( function( el,i ) {

			var	$this 		= jQuery(this);
			var modal 		= jQuery( "#" + $this.data('modal') );
			var	close 		= modal.find( '.cp-md-close' );

			function removeModalHandler() {
				if( close.closest( '.cp-customizer-wrapper' ).length == 0 ) {
					modal.removeClass( 'cp-md-show' );
				}
			}

			$this.on( 'click', function( ev ) {
				modal.addClass( 'cp-md-show' );
				overlay.on( 'click', removeModalHandler );
			});

			close.on( 'click', function( ev ) {
				ev.preventDefault();
				removeModalHandler();
			});

		} );
	}

	init();
})();