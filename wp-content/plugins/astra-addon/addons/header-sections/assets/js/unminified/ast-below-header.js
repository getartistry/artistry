/**
 * Below Header Styling
 *
 * @package Astra Addon
 * @since 1.0.0
 */

(function() {

	var menu_toggle     	= document.querySelector( '.main-header-menu-toggle' ),
		below_header        = document.querySelector( '.ast-below-header' ),
		below_header_nav 	= document.querySelector( '.ast-below-header-actual-nav' ),
		top_menu_toggle     = document.querySelector( '.menu-above-header-toggle' ),
		below_header_toggle = document.querySelector( '.menu-below-header-toggle' );


	var __main_header_all 		= document.querySelectorAll( '.ast-below-header' );
	var menu_toggle_all 		= document.querySelectorAll( '.menu-below-header-toggle' );
	var below_header_nav_all 	= document.querySelectorAll( '.ast-below-header-actual-nav' );

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
					case 'below-header':
							toggleClass( __main_header_all[event_index], 'toggle-on' );
							toggleClass( menu_toggle_all[event_index], 'toggled' );

							if ( __main_header_all[event_index].classList.contains( 'toggle-on' ) ) {
								//__main_header_all[event_index].style.display = 'block';
								below_header_nav_all[event_index].style.display = 'block';
							} else {		
								//__main_header_all[event_index].style.display = '';		
								below_header_nav_all[event_index].style.display = '';		
							}
						break;
				}
		    }, false);
			
			var parentList = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu li' );
			AstraNavigationMenu( parentList );
		 	
		 	var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.ast-below-header-menu .ast-menu-toggle' );
			AstraToggleMenu( astra_menu_toggle );


		};


	} else{
		var __primary_menu = document.querySelectorAll( '.main-header-menu' );
		var __below_main_header_all = document.querySelectorAll( '.ast-below-header-menu-items' );
		var below_menu_toggle_all 	= document.querySelectorAll( '.main-header-menu-toggle' );

		if ( below_menu_toggle_all.length > 0 && __below_main_header_all.length > 0  && __primary_menu.length == 0 ) {

			for (var i = 0; i < below_menu_toggle_all.length; i++) {
				var parentListBelowHeader = __below_main_header_all[i].querySelectorAll( 'li' );
				AstraNavigationMenu( parentListBelowHeader );
			 	
			 	var astra_menu_toggle_below_header = __below_main_header_all[i].querySelectorAll( '.ast-menu-toggle' );
				AstraToggleMenu( astra_menu_toggle_below_header );

			};

		}
	}

	/* Below Header Menu Toggle */
	if ( null != below_header_toggle ) {

		/* Main Menu toggle click */
		if ( null != menu_toggle && null != below_header_nav ) {
			menu_toggle.addEventListener( 'click', function( event ) {

				below_header.classList.remove( 'toggle-on' );
				//below_header.style.display = 'none';
				below_header_nav.style.display = 'none';
				if ( null != top_menu_toggle ){
					top_menu_toggle.classList.remove( 'toggled' );
				}
				if ( null != below_header_toggle ) {
					below_header_toggle.classList.remove( 'toggled' );
				}
			}, false);
		}

			below_header_toggle.addEventListener( 'click', function( event ) {
				event.preventDefault();

				if ( null != menu_toggle ) {
					menu_toggle.classList.remove( 'toggled' );
				}
				if ( null != top_menu_toggle ) {
					top_menu_toggle.classList.remove( 'toggled' );
				}
				var ast_above_header 	 = document.querySelector( '.ast-above-header' );

				if ( null != ast_above_header ) {
					ast_above_header.classList.remove( 'toggle-on' );

					var ast_above_header_nav = document.querySelector( '.ast-above-header-navigation' );
					if ( null != ast_above_header_nav ) {
						ast_above_header_nav.style.display = '';
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

	container = document.getElementById( 'ast-below-header-navigation' );
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

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	( function( container ) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		if ( 'ontouchstart' in window ) {
			touchStartFn = function( e ) {
				var menuItem = this.parentNode, i;

				if ( ! menuItem.classList.contains( 'focus' ) ) {
					e.preventDefault();
					for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
						if ( menuItem === menuItem.parentNode.children[i] ) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove( 'focus' );
					}
					menuItem.classList.add( 'focus' );
				} else {
					menuItem.classList.remove( 'focus' );
				}
			};

			for ( i = 0; i < parentLink.length; ++i ) {
				parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
			}
		}
	}( container ) );	

})();
