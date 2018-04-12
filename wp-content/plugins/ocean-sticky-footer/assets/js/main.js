// Sticky header
var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	// Sticky footer
	stickyFooter();
} );

/* ==============================================
STICKY FOOTER
============================================== */
function stickyFooter() {
	"use strict"

	$j( '#footer-bar .osf-btn a' ).on( 'click', function( e ) {
		e.preventDefault();

		$j( '.site-footer' ).toggleClass( 'opened' );
		$j( 'body' ).toggleClass( 'osf-opened' );

    } );

    // Close footer
	$j( '#main' ).on( 'click', function() {

		$j( '.site-footer' ).removeClass( 'opened' );
		$j( 'body' ).removeClass( 'osf-opened' );

	} );

	// Scroll footer
	if ( ! navigator.userAgent.match( /(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/ ) ) {
		$j( '#footer .footer-box' ).niceScroll( {
			autohidemode		: false,
			cursorborder		: 0,
			cursorborderradius	: 0,
			cursorcolor			: 'transparent',
			cursorwidth			: 0,
			horizrailenabled	: false,
			mousescrollstep		: 40,
			scrollspeed			: 60,
			zindex				: 9999,
		} );
	}

}