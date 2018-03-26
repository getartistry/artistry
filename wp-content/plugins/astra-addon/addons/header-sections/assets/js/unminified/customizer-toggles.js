/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		ASTCustomizerToggles['astra-settings[below-header-layout]'] = [
			/* All Layout */
			{
				controls: [
					'astra-settings[below-header-separator]',
					'astra-settings[below-header-height]',
					'astra-settings[below-header-color-bg-content-divider]',
					'astra-settings[below-header-bg-color]',
					'astra-settings[divider-below-header-typography-content]',
					'astra-settings[below-header-layout-section-1-divider]',
					'astra-settings[below-header-layout-options-separator-divider]',
					'astra-settings[below-header-section-1]',
					'astra-settings[below-header-menu-align]',
					'astra-settings[below-header-mobile-menu-divider]',
				],
				callback: function( val ) {

					if ( val != 'disabled' ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[below-header-section-1-html]'
				],
				callback: function( val ) {

					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();

					if ( val != 'disabled' && section_1 == 'text-html' ) {

						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[below-header-section-2]',
					'astra-settings[below-header-layout-section-2-divider]',
				],
				callback: function( val ) {

					if ( val == 'below-header-layout-1' ) {

						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[below-header-section-2-html]'
				],
				callback: function( val ) {

					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( val == 'below-header-layout-1' && section_2 == 'text-html' ) {

						return true;
					}
					return false;
				}
			},
			/* Navigation */
			{
				controls: [
					/* Colors */
					'astra-settings[below-header-color-bg-primary-menu-divider]',
					'astra-settings[below-header-menu-text-color]',
					'astra-settings[below-header-menu-text-hover-color]',
					'astra-settings[below-header-menu-bg-hover-color]',
					'astra-settings[below-header-current-menu-text-color]',
					'astra-settings[below-header-current-menu-bg-color]',

					'astra-settings[below-header-color-bg-dropdown-menu-divider]',
					'astra-settings[below-header-submenu-text-color]',
					'astra-settings[below-header-submenu-bg-color]',
					'astra-settings[below-header-submenu-hover-color]',
					'astra-settings[below-header-submenu-bg-hover-color]',
					'astra-settings[below-header-submenu-active-color]',
					'astra-settings[below-header-submenu-active-bg-color]',
					'astra-settings[below-header-submenu-border]',

					/* Typography */
					'astra-settings[divider-below-header-typography-primary-menu]',
					'astra-settings[font-family-below-header-primary-menu]',
					'astra-settings[font-weight-below-header-primary-menu]',
					'astra-settings[font-size-below-header-primary-menu]',
					'astra-settings[text-transform-below-header-primary-menu]',

					'astra-settings[divider-below-header-typography-dropdown-menu]',
					'astra-settings[font-family-below-header-dropdown-menu]',
					'astra-settings[font-weight-below-header-dropdown-menu]',
					'astra-settings[font-size-below-header-dropdown-menu]',
					'astra-settings[text-transform-below-header-dropdown-menu]',
				],
				callback: function( val ) {

					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls : [
					'astra-settings[below-header-submenu-border-color]',
				],
				callback : function( val ) {
					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();
					var submenu_border = api( 'astra-settings[below-header-submenu-border]' ).get();

					if ( ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Text/Search */
			{
				controls: [
					'astra-settings[below-header-color-bg-content-divider]',
					'astra-settings[below-header-text-color]',
					'astra-settings[below-header-link-color]',
					'astra-settings[below-header-link-hover-color]',
					'astra-settings[divider-below-header-typography-content]',
					'astra-settings[font-family-below-header-content]',
					'astra-settings[font-weight-below-header-content]',
					'astra-settings[font-size-below-header-content]',
					'astra-settings[text-transform-below-header-content]',
				],
				callback: function( val ) {

					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 || 'search' == section_2 || 'text-html' == section_2 || 'widget' == section_2 ) ) ||
						( 'below-header-layout-2' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 ) ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[below-header-bottom-border-color]',
				],
				callback: function( val ) {

					var border_width = api( 'astra-settings[below-header-separator]' ).get();

					if ( 'disabled' != val && 0 < border_width ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[below-header-merge-menu]',
					'astra-settings[below-header-menu-label]',
				],
				callback: function( val ) {

					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) ) ||
						 ( 'below-header-layout-2' == val && 'menu' == section_1 ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		/* Border */
		ASTCustomizerToggles['astra-settings[below-header-separator]'] = [
			{
				controls: [
					'astra-settings[below-header-bottom-border-color]'
				],
				callback: function( border_width ) {

					var val = api( 'astra-settings[below-header-layout]' ).get();

					if ( 'disabled' != val && 0 < border_width ) {
						return true;
					}

					return false;
				}
			},
		];

		/* Section 1 */
		ASTCustomizerToggles['astra-settings[below-header-section-1]'] = [

			{
				controls: [
					'astra-settings[below-header-section-1-html]'
				],
				callback: function( val ) {

					var layout = api( 'astra-settings[below-header-layout]' ).get();

					if ( layout != 'disabled' && val == 'text-html' ) {

						return true;
					}
					return false;
				}
			},
			/* Navigation */
			{
				controls: [
					/* Colors */
					'astra-settings[below-header-color-bg-primary-menu-divider]',
					'astra-settings[below-header-menu-text-color]',
					'astra-settings[below-header-menu-text-hover-color]',
					'astra-settings[below-header-menu-bg-hover-color]',
					'astra-settings[below-header-current-menu-text-color]',
					'astra-settings[below-header-current-menu-bg-color]',

					'astra-settings[below-header-color-bg-dropdown-menu-divider]',
					'astra-settings[below-header-submenu-text-color]',
					'astra-settings[below-header-submenu-bg-color]',
					'astra-settings[below-header-submenu-hover-color]',
					'astra-settings[below-header-submenu-bg-hover-color]',
					'astra-settings[below-header-submenu-active-color]',
					'astra-settings[below-header-submenu-active-bg-color]',
					'astra-settings[below-header-submenu-border]',

					/* Typography */
					'astra-settings[divider-below-header-typography-primary-menu]',
					'astra-settings[font-family-below-header-primary-menu]',
					'astra-settings[font-weight-below-header-primary-menu]',
					'astra-settings[font-size-below-header-primary-menu]',
					'astra-settings[text-transform-below-header-primary-menu]',

					'astra-settings[divider-below-header-typography-dropdown-menu]',
					'astra-settings[font-family-below-header-dropdown-menu]',
					'astra-settings[font-weight-below-header-dropdown-menu]',
					'astra-settings[font-size-below-header-dropdown-menu]',
					'astra-settings[text-transform-below-header-dropdown-menu]',
				],
				callback: function( section_1 ) {

					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls : [
					'astra-settings[below-header-submenu-border-color]',
				],
				callback : function( section_1 ) {
					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();
					var submenu_border = api( 'astra-settings[below-header-submenu-border]' ).get();

					if ( ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Text/Search */
			{
				controls: [
					'astra-settings[below-header-color-bg-content-divider]',
					'astra-settings[below-header-text-color]',
					'astra-settings[below-header-link-color]',
					'astra-settings[below-header-link-hover-color]',
					'astra-settings[divider-below-header-typography-content]',
					'astra-settings[font-family-below-header-content]',
					'astra-settings[font-weight-below-header-content]',
					'astra-settings[font-size-below-header-content]',
					'astra-settings[text-transform-below-header-content]',
				],
				callback: function( section_1 ) {

					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 || 'search' == section_2 || 'text-html' == section_2 || 'widget' == section_2 ) ) ||
						( 'below-header-layout-2' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 ) ) ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[below-header-merge-menu]',
					'astra-settings[below-header-menu-label]',
				],
				callback: function( val ) {

					var header_layout 	= api( 'astra-settings[below-header-layout]' ).get();
					var section_2 		= api( 'astra-settings[below-header-section-2]' ).get();

					if ( ( 'below-header-layout-1' == header_layout && ( 'menu' == val || 'menu' == section_2 ) ) ||
						 ( 'below-header-layout-2' == header_layout && 'menu' == val ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		/* Layout 3 Left Section */
		ASTCustomizerToggles['astra-settings[below-header-section-2]'] = [
			{
				controls: [
					'astra-settings[below-header-section-2-html]'
				],
				callback: function( val ) {

					var layout = api( 'astra-settings[below-header-layout]' ).get();

					if ( layout == 'below-header-layout-1' && val == 'text-html' ) {

						return true;
					}
					return false;
				}
			},
			/* Navigation */
			{
				controls: [
					/* Colors */
					'astra-settings[below-header-color-bg-primary-menu-divider]',
					'astra-settings[below-header-menu-text-color]',
					'astra-settings[below-header-menu-text-hover-color]',
					'astra-settings[below-header-menu-bg-hover-color]',
					'astra-settings[below-header-current-menu-text-color]',
					'astra-settings[below-header-current-menu-bg-color]',

					'astra-settings[below-header-color-bg-dropdown-menu-divider]',
					'astra-settings[below-header-submenu-text-color]',
					'astra-settings[below-header-submenu-bg-color]',
					'astra-settings[below-header-submenu-hover-color]',
					'astra-settings[below-header-submenu-bg-hover-color]',
					'astra-settings[below-header-submenu-active-color]',
					'astra-settings[below-header-submenu-active-bg-color]',
					'astra-settings[below-header-submenu-border]',

					/* Typography */
					'astra-settings[divider-below-header-typography-primary-menu]',
					'astra-settings[font-family-below-header-primary-menu]',
					'astra-settings[font-weight-below-header-primary-menu]',
					'astra-settings[font-size-below-header-primary-menu]',
					'astra-settings[text-transform-below-header-primary-menu]',

					'astra-settings[divider-below-header-typography-dropdown-menu]',
					'astra-settings[font-family-below-header-dropdown-menu]',
					'astra-settings[font-weight-below-header-dropdown-menu]',
					'astra-settings[font-size-below-header-dropdown-menu]',
					'astra-settings[text-transform-below-header-dropdown-menu]',
				],
				callback: function( section_2 ) {

					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls : [
					'astra-settings[below-header-submenu-border-color]',
				],
				callback : function( section_2 ) {
					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var submenu_border = api( 'astra-settings[below-header-submenu-border]' ).get();

					if ( ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Text/Search */
			{
				controls: [
					'astra-settings[below-header-color-bg-content-divider]',
					'astra-settings[below-header-text-color]',
					'astra-settings[below-header-link-color]',
					'astra-settings[below-header-link-hover-color]',
					'astra-settings[divider-below-header-typography-content]',
					'astra-settings[font-family-below-header-content]',
					'astra-settings[font-weight-below-header-content]',
					'astra-settings[font-size-below-header-content]',
					'astra-settings[text-transform-below-header-content]',
				],
				callback: function( section_2 ) {

					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();

					if ( ( 'below-header-layout-1' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 || 'search' == section_2 || 'text-html' == section_2 || 'widget' == section_2 ) ) ||
						( 'below-header-layout-2' == val && ( 'search' == section_1 || 'text-html' == section_1 || 'widget' == section_1 ) ) ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[below-header-merge-menu]',
					'astra-settings[below-header-menu-label]',
				],
				callback: function( val ) {

					var header_layout 	= api( 'astra-settings[below-header-layout]' ).get();
					var section_1 		= api( 'astra-settings[below-header-section-1]' ).get();

					if ( ( 'below-header-layout-1' == header_layout && ( 'menu' == section_1 || 'menu' == val ) ) ||
						 ( 'below-header-layout-2' == header_layout && 'menu' == section_1 ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		ASTCustomizerToggles['astra-settings[below-header-submenu-border]'] = [
			{
				controls : [
					'astra-settings[below-header-submenu-border-color]',
				],
				callback : function( submenu_border ) {
					var val = api( 'astra-settings[below-header-layout]' ).get();
					var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[below-header-section-2]' ).get();
					
					if ( ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			}
		];

		//Above Header
		ASTCustomizerToggles['astra-settings[above-header-layout]'] = [

			/* All Layout */
			{
				controls: [
					'astra-settings[above-header-divider]',
					'astra-settings[above-header-height]',
					'astra-settings[above-header-layout-section-1-divider]',
					'astra-settings[section-ast-above-header-border]',

					/* Typography */
					'astra-settings[above-header-font-family]',
					'astra-settings[above-header-font-weight]',
					'astra-settings[above-header-font-size]',
					'astra-settings[above-header-text-transform]',

					'astra-settings[above-header-bg-color]',

					'astra-settings[above-header-section-1]',
					'astra-settings[above-header-mobile-menu-divider]',
					'astra-settings[above-header-menu-align]',
				],
				callback: function( layout ) {

					if ( layout != 'disabled' ) {
						return true;
					}

					return false;
				}
			},
			/* Layout 1 */
			{
					controls: [
						'astra-settings[above-header-layout-section-2-divider]',
						'astra-settings[above-header-section-2]',
					],
					callback: function( layout ) {

						if ( layout == 'above-header-layout-1' ) {
							return true;
						}

						return false;
					}
			},
			{
				controls: [
					'astra-settings[above-header-section-1-html]'
				],
				callback: function( val ) {

					var left_section = api( 'astra-settings[above-header-section-1]' ).get();

					if ( val != 'disabled' && left_section == 'text-html' ) {

						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[above-header-section-2-html]'
				],
				callback: function( val ) {

					var right_section = api( 'astra-settings[above-header-section-2]' ).get();

					if ( val == 'above-header-layout-1' && right_section == 'text-html' ) {

						return true;
					}

					return false;
				}
			},
			{
			
				controls : [
					'astra-settings[above-header-submenu-border-color]',
				],
				callback : function( val ) {
					var section_1 = api( 'astra-settings[above-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[above-header-section-2]' ).get();
					var submenu_border = api( 'astra-settings[above-header-submenu-border]' ).get();

					if ( ( ( 'above-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'above-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-menu-color-divider]',
					'astra-settings[above-header-menu-color]',
					'astra-settings[above-header-menu-h-color]',
					'astra-settings[above-header-menu-h-bg-color]',
					'astra-settings[above-header-menu-active-color]',
					'astra-settings[above-header-menu-active-bg-color]',

					'astra-settings[above-header-color-bg-dropdown-menu-divider]',
					'astra-settings[above-header-submenu-text-color]',
					'astra-settings[above-header-submenu-bg-color]',
					'astra-settings[above-header-submenu-hover-color]',
					'astra-settings[above-header-submenu-bg-hover-color]',
					'astra-settings[above-header-submenu-active-color]',
					'astra-settings[above-header-submenu-active-bg-color]',
					'astra-settings[above-header-submenu-border]',
				],
				callback: function( val ) {

					var left_section = api( 'astra-settings[above-header-section-1]' ).get();
					var right_section = api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},
			/* Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-content-color-divider]',
					'astra-settings[above-header-text-color]',
					'astra-settings[above-header-link-color]',
					'astra-settings[above-header-link-h-color]',
				],
				callback: function( val ) {

					var left_section = api( 'astra-settings[above-header-section-1]' ).get();
					var right_section = api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section != 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section != 'menu' || right_section != 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},

			/* Border Enabled */
			{
				controls: [
					'astra-settings[above-header-divider-color]',
				],
				callback: function( layout ) {

					var border = api( 'astra-settings[above-header-divider]' ).get();

					if ( layout == 'above-header-layout-1' && border > 0 ) {
						return true;
					}

					return false;
				}
			},

			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[above-header-merge-menu]',
					'astra-settings[above-header-menu-label]',
				],
				callback: function( val ) {

					var section_1 = api( 'astra-settings[above-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( 'above-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) ) ||
						 ( 'above-header-layout-2' == val && 'menu' == section_1 ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		/* Layout 1 Left Section */
		ASTCustomizerToggles['astra-settings[above-header-section-1]'] = [
			{
				controls: [
					'astra-settings[above-header-section-1-html]'
				],
				callback: function( val ) {

					var layout = api( 'astra-settings[above-header-layout]' ).get();

					if ( layout != 'disabled' && val == 'text-html' ) {

						return true;
					}

					return false;
				}
			},
			{
				controls : [
					'astra-settings[above-header-submenu-border-color]',
				],
				callback : function( section_1 ) {
					var val = api( 'astra-settings[above-header-layout]' ).get();
					var section_2 = api( 'astra-settings[above-header-section-2]' ).get();
					var submenu_border = api( 'astra-settings[above-header-submenu-border]' ).get();

					if ( ( ( 'above-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'above-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-menu-color-divider]',
					'astra-settings[above-header-menu-color]',
					'astra-settings[above-header-menu-h-color]',
					'astra-settings[above-header-menu-h-bg-color]',
					'astra-settings[above-header-menu-active-color]',
					'astra-settings[above-header-menu-active-bg-color]',

					'astra-settings[above-header-color-bg-dropdown-menu-divider]',
					'astra-settings[above-header-submenu-text-color]',
					'astra-settings[above-header-submenu-bg-color]',
					'astra-settings[above-header-submenu-hover-color]',
					'astra-settings[above-header-submenu-bg-hover-color]',
					'astra-settings[above-header-submenu-active-color]',
					'astra-settings[above-header-submenu-active-bg-color]',
					'astra-settings[above-header-submenu-border]',
				],
				callback: function( left_section ) {

					var val = api( 'astra-settings[above-header-layout]' ).get();
					var right_section = api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},
			/* Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-content-color-divider]',
					'astra-settings[above-header-text-color]',
					'astra-settings[above-header-link-color]',
					'astra-settings[above-header-link-h-color]',
				],
				callback: function( left_section ) {

					var val = api( 'astra-settings[above-header-layout]' ).get();
					var right_section = api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section != 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section != 'menu' || right_section != 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},
			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[above-header-merge-menu]',
					'astra-settings[above-header-menu-label]',
				],
				callback: function( val ) {

					var header_layout 	= api( 'astra-settings[above-header-layout]' ).get();
					var section_2 		= api( 'astra-settings[above-header-section-2]' ).get();

					if ( ( 'above-header-layout-1' == header_layout && ( 'menu' == val || 'menu' == section_2 ) ) ||
						 ( 'above-header-layout-2' == header_layout && 'menu' == val ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		/* Layout 1 Right Section */
		ASTCustomizerToggles['astra-settings[above-header-section-2]'] = [
			{
				controls: [
					'astra-settings[above-header-section-2-html]'
				],
				callback: function( val ) {

					var layout = api( 'astra-settings[above-header-layout]' ).get();

					if ( layout == 'above-header-layout-1' && val == 'text-html' ) {

						return true;
					}

					return false;
				}
			},
			{
				controls : [
					'astra-settings[above-header-submenu-border-color]',
				],
				callback : function( section_2 ) {
					var val = api( 'astra-settings[above-header-layout]' ).get();
					var section_1 = api( 'astra-settings[above-header-section-1]' ).get();
					var submenu_border = api( 'astra-settings[above-header-submenu-border]' ).get();

					if ( ( ( 'above-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'above-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			},
			/* Menu Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-menu-color-divider]',
					'astra-settings[above-header-menu-color]',
					'astra-settings[above-header-menu-h-color]',
					'astra-settings[above-header-menu-h-bg-color]',
					'astra-settings[above-header-menu-active-color]',
					'astra-settings[above-header-menu-active-bg-color]',

					'astra-settings[above-header-color-bg-dropdown-menu-divider]',
					'astra-settings[above-header-submenu-text-color]',
					'astra-settings[above-header-submenu-bg-color]',
					'astra-settings[above-header-submenu-hover-color]',
					'astra-settings[above-header-submenu-bg-hover-color]',
					'astra-settings[above-header-submenu-active-color]',
					'astra-settings[above-header-submenu-active-bg-color]',
					'astra-settings[above-header-submenu-border]',					
				],
				callback: function( right_section ) {

					var val = api( 'astra-settings[above-header-layout]' ).get();
					var left_section = api( 'astra-settings[above-header-section-1]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},
			/* Colors Dependencies */
			{
				controls: [
					'astra-settings[above-header-content-color-divider]',
					'astra-settings[above-header-text-color]',
					'astra-settings[above-header-link-color]',
					'astra-settings[above-header-link-h-color]',
				],
				callback: function( right_section ) {

					var val = api( 'astra-settings[above-header-layout]' ).get();
					var left_section = api( 'astra-settings[above-header-section-1]' ).get();

					if ( ( val == 'above-header-layout-2' && left_section != 'menu' ) ||
						( val == 'above-header-layout-1' && ( left_section != 'menu' || right_section != 'menu' ) ) ) {

						return true;
					}

					return false;
				}
			},
			/* Menu Mobile option */
			{
				controls: [
					'astra-settings[above-header-merge-menu]',
					'astra-settings[above-header-menu-label]',
				],
				callback: function( val ) {

					var header_layout 	= api( 'astra-settings[above-header-layout]' ).get();
					var section_1 		= api( 'astra-settings[above-header-section-1]' ).get();

					if ( ( 'above-header-layout-1' == header_layout && ( 'menu' == section_1 || 'menu' == val ) ) ||
						 ( 'above-header-layout-2' == header_layout && 'menu' == section_1 ) 
						) {
						return true;
					}

					return false;
				}
			},
		];

		/* Border Enabled & Above Header Enabled */
		ASTCustomizerToggles['astra-settings[above-header-divider]'] = [
			{
				controls: [
					'astra-settings[above-header-divider-color]'
				],
				callback: function( border ) {

					var layout = api( 'astra-settings[above-header-layout]' ).get();

					if ( layout != 'disabled' && border > 0 ) {
						return true;
					}

					return false;
				}
			}
		];

		ASTCustomizerToggles['astra-settings[above-header-submenu-border]'] = [
			{
				controls : [
					'astra-settings[above-header-submenu-border-color]',
				],
				callback : function( submenu_border ) {
					var val = api( 'astra-settings[above-header-layout]' ).get();
					var section_1 = api( 'astra-settings[above-header-section-1]' ).get();
					var section_2 = api( 'astra-settings[above-header-section-2]' ).get();
					
					if ( ( ( 'above-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
						 || ( 'above-header-layout-2' == val && 'menu' == section_1 ) ) && submenu_border ) {
						return true;
					}

					return false;
				}
			}
		];
	});
})( jQuery );
