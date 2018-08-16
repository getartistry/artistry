/**
 * Above Header Header Styling
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function() {

	var toggle_menu_style = document.querySelectorAll( '.ast-no-toggle-below-menu-enable' ) || 0;

	if( ! toggle_menu_style.length ) {
		return;
	}

	var __main_header_all = document.querySelectorAll( '.ast-below-header' );
	var menu_toggle_all   = document.querySelectorAll( '.ast-below-header' );

	for (var i = 0; i < menu_toggle_all.length; i++) {

		var parentList = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu li' );
		AstraNavigationMenu( parentList );

	 	var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu .ast-menu-toggle' );
		AstraToggleMenu( astra_menu_toggle );

		var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu > li > .ast-menu-toggle' );
		BelowMenuNoToggle( astra_menu_toggle );

	}

	function BelowMenuNoToggle( astra_menu_toggle ) {
		if( parseInt( window.innerWidth ) <= 480 ) {
			for (var i = 0; i < astra_menu_toggle.length; i++) {

				astra_menu_toggle[i].addEventListener( 'click', function ( event ) {
					event.preventDefault();

					var position = this.nextElementSibling.getBoundingClientRect();
					var is_set = this.nextElementSibling.getAttribute('data-set');
					if( null === is_set ) {

						this.nextElementSibling.setAttribute('data-set', true);
						this.nextElementSibling.style.width = 'auto';

						left = '-' + parseFloat( position.left ) + 'px';

						this.nextElementSibling.style.left  = left;
						var li_width = document.documentElement.clientWidth;
						
						// set width of submenu to full screen.
						this.nextElementSibling.style.width = li_width + 'px';
					}
				});
			}
		}
	}

	window.addEventListener("resize", function() {
		var all_sub_menu = document.querySelectorAll( '.ast-below-header .sub-menu, .ast-below-header .children' );
		for (var k = 0; k < all_sub_menu.length; k++) {		
			all_sub_menu[k].removeAttribute("style");
		};
		var all_sub_menu = document.querySelectorAll( '.ast-below-header li' );
		for (var k = 0; k < all_sub_menu.length; k++) {
			all_sub_menu[k].classList.remove( 'ast-submenu-expanded' );
		};

		var __main_header_all = document.querySelectorAll( '.ast-below-header' );
		var menu_toggle_all   = document.querySelectorAll( '.ast-below-header' );

		for (var i = 0; i < menu_toggle_all.length; i++) {
			var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu > li > .ast-menu-toggle' );

			for ( var i = 0; i < astra_menu_toggle.length; i++ ) {
				astra_menu_toggle[i].nextElementSibling.removeAttribute( 'data-set' );
			}

			BelowMenuNoToggle( astra_menu_toggle );
		}

	});
})();