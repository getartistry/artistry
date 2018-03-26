( function( $ ) {
	'use strict';
	$( '.eael-get-pro' ).on( 'click', function() {
		swal({
	  		title: '<h2>Install Essential Addons for Elementor</h2>',
	  		type: 'warning',
	  		html:
	    		'It\'s free! You can <a href="https://wordpress.org/plugins/essential-addons-for-elementor-lite/" rel="nofollow" target="_blank">download</a> it from WordPress.org',
	  		showCloseButton: true,
	  		showCancelButton: false,
	  		focusConfirm: true,
		});
	} );


} )( jQuery );
