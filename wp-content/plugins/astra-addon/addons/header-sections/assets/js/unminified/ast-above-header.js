/**
 * Above Header Header Styling
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function() {

	var above_header 	= document.querySelector( '.ast-above-header' ),
	above_header_nav 	= document.querySelector( '.ast-above-header-navigation' ),
	top_menu_toggle 	= document.querySelector( '.menu-above-header-toggle' ),
	main_menu_toggle 	= document.querySelector( '.main-header-menu-toggle' ),
	below_header_toggle = document.querySelector( '.menu-below-header-toggle' );

	var html                 = document.querySelector( 'html' );
	var __main_header_all    = document.querySelectorAll( '.ast-above-header' );
	var menu_toggle_all      = document.querySelectorAll( '.menu-above-header-toggle' );
	var above_header_nav_all = document.querySelectorAll( '.ast-above-header-navigation' );

	if ( menu_toggle_all.length > 0 ) {

		for (var i = 0; i < menu_toggle_all.length; i++) {
			
			menu_toggle_all[i].setAttribute('data-index', i);

			menu_toggle_all[i].addEventListener( 'click', function( event ) {
		    	event.preventDefault();

		    	var event_index = this.getAttribute( 'data-index' );

		    	var menuHasChildren = __main_header_all[event_index].querySelectorAll( '.menu-item-has-children, .page_item_has_children' );
				for ( var i = 0; i < menuHasChildren.length; i++ ) {
					menuHasChildren[i].classList.remove( 'ast-submenu-expanded' );
					var menuHasChildrenSubMenu = menuHasChildren[i].querySelectorAll( '.sub-menu, .children' );		
					for (var j = 0; j < menuHasChildrenSubMenu.length; j++) {		
						menuHasChildrenSubMenu[j].style.display = 'none';		
					};
				}

				var rel = this.getAttribute( 'rel' ) || '';
				switch ( rel ) {
					case 'above-header':
							toggleClass( __main_header_all[event_index], 'toggle-on' );
							toggleClass( menu_toggle_all[event_index], 'toggled' );
							if ( __main_header_all[event_index].classList.contains( 'toggle-on' ) ) {
								//__main_header_all[event_index].style.display = 'block';
								above_header_nav_all[event_index].style.display = 'block';
								html.classList.add( 'above-header-toggle-on' );
							} else {		
								//__main_header_all[event_index].style.display = '';
								above_header_nav_all[event_index].style.display = '';
								html.classList.remove( 'above-header-toggle-on' );
							}

							document.body.classList.add( "ast-above-header-nav-open" );	
						break;
				}

				var elm = document.querySelector( '.ast-above-header-navigation' );
				var rect = elm.getBoundingClientRect();
				var vph = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

				elm.style.maxHeight = Math.abs( vph - rect.top ) + 'px';

		    }, false);
			
			var parentList = __main_header_all[i].querySelectorAll( 'ul.ast-above-header-menu li' );
			AstraNavigationMenu( parentList );
		 	
			if ( document.querySelector("header.site-header").classList.contains("ast-menu-toggle-link") ) {
			 	var astra_menu_toggle = __main_header_all[i].querySelectorAll( '.ast-header-break-point .ast-above-header-menu .ast-menu-toggle, .ast-header-break-point .ast-above-header-menu .menu-item-has-children > a' );
			} else {
               	var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.ast-above-header-menu .ast-menu-toggle' );
			}

			AstraToggleMenu( astra_menu_toggle );
		};
	} else{
		var __primary_menu = document.querySelectorAll( '.main-header-menu' );
		var __above_main_header_all = document.querySelectorAll( '.ast-above-header-menu-items' );
		var above_menu_toggle_all 	= document.querySelectorAll( '.main-header-menu-toggle' );

		if ( above_menu_toggle_all.length > 0 && __above_main_header_all.length > 0 && __primary_menu.length == 0  ) {

			for (var i = 0; i < above_menu_toggle_all.length; i++) {
				var parentListAboveHeader = __above_main_header_all[i].querySelectorAll( 'li' );
				AstraNavigationMenu( parentListAboveHeader );
			 	
			 	var astra_menu_toggle_above_header = __above_main_header_all[i].querySelectorAll( '.ast-menu-toggle' );
				AstraToggleMenu( astra_menu_toggle_above_header );

			};

		}
	}

	/* Above Header Menu toggle click */
	if ( null != top_menu_toggle ) {

			/* Main Menu toggle click */
		if ( null != main_menu_toggle && null != above_header_nav ) {
			main_menu_toggle.addEventListener( 'click', function( event ) {

				above_header.classList.remove( 'toggle-on' );
				//above_header.style.display = 'none';
				above_header_nav.style.display = 'none';
				if ( null != top_menu_toggle ){
					top_menu_toggle.classList.remove( 'toggled' );
				}
				if ( null != below_header_toggle ) {
					below_header_toggle.classList.remove( 'toggled' );	
				}

			}, false);
		}

		top_menu_toggle.addEventListener( 'click', function( event ) {
			event.preventDefault();

			if ( null != main_menu_toggle ) {
				main_menu_toggle.classList.remove( 'toggled' );
			}

			if ( null != below_header_toggle ){
				below_header_toggle.classList.remove( 'toggled' );
			}

			var ast_below_header = document.querySelector( '.ast-below-header' );
			if ( null != ast_below_header ) {

				var ast_below_header_nav = document.querySelector( '.ast-below-header-actual-nav' );

				ast_below_header.classList.remove( 'toggle-on' );
				//ast_below_header.style.display = '';
				
				if ( null != ast_below_header_nav ) {
					ast_below_header_nav.style.display = '';
				}
			}

			var main_header_bar = document.querySelector( '.main-header-bar-navigation' );
			if ( null != main_header_bar ) {
				main_header_bar.classList.remove( 'toggle-on' );
				main_header_bar.style.display = '';
			}
		}, false);
}


	/**
	 * Navigation Keyboard Navigation.
	 */
	var container, button, menu, links, subMenus, i, len;

	container = document.getElementById( 'ast-above-header-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		console.log('yes4: ');
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );
	subMenus = menu.getElementsByTagName( 'ul' );


	// Set menu items with submenus to aria-haspopup="true".
	for ( i = 0, len = subMenus.length; i < len; i++ ) {
		subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
	}

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

})();
