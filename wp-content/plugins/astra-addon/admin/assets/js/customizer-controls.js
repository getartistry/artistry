/**
 * Astra Addon Customizer JS
 *
 * @package Astra Addon
 * @since  1.4.0
 */

(function( $ ) {


	ASTExtAdmin = {

		init: function() {
			$(document).on('click', ".ast-customizer-internal-link",ASTExtAdmin.navigate_section );
		},

		navigate_section: function() {
			$this = jQuery( this );
			var sectionToNavigate = $this.data('ast-customizer-section') || '';
			var section = wp.customize.section( sectionToNavigate );
			section.expand();
		},

	}

	$( document ).ready(function() {
		ASTExtAdmin.init();
	});

})( jQuery );
