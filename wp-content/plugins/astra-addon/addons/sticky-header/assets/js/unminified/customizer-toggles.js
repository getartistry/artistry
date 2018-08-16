/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {

	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		/* Layout select */
		ASTCustomizerToggles ['astra-settings[header-above-stick]'] = [
			{
				controls: [
					'astra-settings[sticky-header-on-devices]',
					'astra-settings[sticky-hide-on-scroll]',
				],
				callback: function( val ) {
					main_header_stick     = api( 'astra-settings[header-main-stick]' ).get();
					below_header_stick     = (typeof api( 'astra-settings[header-below-stick]' ) != 'undefined') ? api( 'astra-settings[header-below-stick]' ).get() : '';
					if ( val == 1 || main_header_stick == 1 || below_header_stick == 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-style]',
				],
				callback: function( val ) {
					hide_on_scroll     = (typeof api( 'astra-settings[sticky-hide-on-scroll]' ) != 'undefined') ? api( 'astra-settings[sticky-hide-on-scroll]' ).get() : '';
					main_stick         = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';
					below_header_stick = (typeof api( 'astra-settings[header-below-stick]' ) != 'undefined') ? api( 'astra-settings[header-below-stick]' ).get() : '';
					if ( ( val == 1 || main_stick == 1 || below_header_stick == 1 ) && hide_on_scroll != 1  ) {
						return true;
					}

					return false;
				}
			},
		];
		ASTCustomizerToggles ['astra-settings[header-below-stick]'] = [
			{
				controls: [
					'astra-settings[sticky-header-on-devices]',
					'astra-settings[sticky-hide-on-scroll]',
				],
				callback: function( val ) {
					main_header_stick     = api( 'astra-settings[header-main-stick]' ).get();
					above_header_stick     = (typeof api( 'astra-settings[header-above-stick]' ) != 'undefined') ? api( 'astra-settings[header-above-stick]' ).get() : '';
					if ( val == 1 || main_header_stick == 1 || above_header_stick == 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-style]',
				],
				callback: function( val ) {
					hide_on_scroll     = (typeof api( 'astra-settings[sticky-hide-on-scroll]' ) != 'undefined') ? api( 'astra-settings[sticky-hide-on-scroll]' ).get() : '';
					main_stick         = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';
					above_header_stick = (typeof api( 'astra-settings[header-above-stick]' ) != 'undefined') ? api( 'astra-settings[header-above-stick]' ).get() : '';
					if ( ( val == 1 || main_stick == 1 || above_header_stick == 1 ) && hide_on_scroll != 1  ) {
						return true;
					}

					return false;
				}
			},
		];
		ASTCustomizerToggles ['astra-settings[header-main-stick]'] = [
			{
				controls: [
					'astra-settings[sticky-header-on-devices]',
					// 'astra-settings[sticky-header-style]',
					'astra-settings[sticky-hide-on-scroll]',
				],
				callback: function( val ) {
					above_header_stick     = (typeof api( 'astra-settings[header-above-stick]' ) != 'undefined') ? api( 'astra-settings[header-above-stick]' ).get() : '';
					below_header_stick     = (typeof api( 'astra-settings[header-below-stick]' ) != 'undefined') ? api( 'astra-settings[header-below-stick]' ).get() : '';
					if ( val == 1 || above_header_stick == 1 || below_header_stick == 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-style]',
				],
				callback: function( val ) {
					hide_on_scroll 		= (typeof api( 'astra-settings[sticky-hide-on-scroll]' ) != 'undefined') ? api( 'astra-settings[sticky-hide-on-scroll]' ).get() : '';
					above_header_stick  = (typeof api( 'astra-settings[header-above-stick]' ) != 'undefined') ? api( 'astra-settings[header-above-stick]' ).get() : '';
					below_header_stick  = (typeof api( 'astra-settings[header-below-stick]' ) != 'undefined') ? api( 'astra-settings[header-below-stick]' ).get() : '';
					if ( ( val == 1 || above_header_stick == 1 || below_header_stick == 1 ) && hide_on_scroll != 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[header-main-shrink]',
				],
				callback: function( val ) {
					if ( val == 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[different-sticky-logo]',
				],
				callback: function( val ) {

					if ( val == 1 ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[different-sticky-retina-logo]',
				],
				callback: function( val ) {

					diff_sticky_logo = (typeof api( 'astra-settings[different-sticky-logo]' ) != 'undefined') ? api( 'astra-settings[different-sticky-logo]' ).get() : '';

					if ( val == 1 && diff_sticky_logo ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-retina-logo]',
				],
				callback: function( val ) {

					diff_sticky_logo = (typeof api( 'astra-settings[different-sticky-logo]' ) != 'undefined') ? api( 'astra-settings[different-sticky-logo]' ).get() : '';
					diff_retina_logo = (typeof api( 'astra-settings[different-sticky-retina-logo]' ) != 'undefined') ? api( 'astra-settings[different-sticky-retina-logo]' ).get() : '';


					if ( val == 1 && diff_sticky_logo && diff_retina_logo ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-logo]',
					'astra-settings[sticky-header-logo-width]',
				],
				callback: function( val ) {

					inherit_logo = (typeof api( 'astra-settings[different-sticky-logo]' ) != 'undefined') ? api( 'astra-settings[different-sticky-logo]' ).get() : '';

					if ( val == 1 && inherit_logo ) {
						return true;
					}

					return false;
				}
			}
		];

		/**
		 * Above Header
		 */
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-layout]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[above-header-layout]'].push(
				{
					controls: [
						'astra-settings[header-above-stick]',

						// All Sticky Above Header Colors
						'astra-settings[sticky-above-header-color-divider]',
						'astra-settings[sticky-above-header-menu-color-divider]',
						'astra-settings[sticky-above-header-submenu-color-divider]',
						'astra-settings[divider-sticky-above-header-content-section]',

						'astra-settings[sticky-above-header-bg-color-responsive]',
						'astra-settings[sticky-above-header-menu-bg-color-responsive]',
						'astra-settings[sticky-above-header-menu-color-responsive]',
						'astra-settings[sticky-above-header-menu-h-color-responsive]',
						'astra-settings[sticky-above-header-menu-h-a-bg-color-responsive]',
						'astra-settings[sticky-above-header-submenu-bg-color-responsive]',
						'astra-settings[sticky-above-header-submenu-color-responsive]',
						'astra-settings[sticky-above-header-submenu-h-color-responsive]',
						'astra-settings[sticky-above-header-submenu-h-a-bg-color-responsive]',
						'astra-settings[sticky-above-header-content-section-text-color-responsive]',
						'astra-settings[sticky-above-header-content-section-link-color-responsive]',
						'astra-settings[sticky-above-header-content-section-link-h-color-responsive]',
					],
					callback: function( val ) {

						if ( val != 'disabled' ) {
							return true;
						}

						return false;
					}
				}
			);
		} else {
			$( '#customize-control-astra-settings-header-above-stick' ).css( 'display', 'none' );
			$( '#accordion-section-section-colors-sticky-above-header' ).css( 'display', 'none' );
		}

		/**
		 * Below Header
		 */
		if ( typeof ASTCustomizerToggles['astra-settings[below-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-layout]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[below-header-layout]'].push(
				{
					controls: [
						'astra-settings[header-below-stick]',

						// All Sticky Below Header Colors
						'astra-settings[sticky-below-header-color-divider]',
						'astra-settings[sticky-below-header-menu-color-divider]',
						'astra-settings[sticky-below-header-submenu-color-divider]',
						'astra-settings[divider-sticky-below-header-content-section]',

						'astra-settings[sticky-below-header-bg-color-responsive]',
						'astra-settings[sticky-below-header-menu-bg-color-responsive]',
						'astra-settings[sticky-below-header-menu-color-responsive]',
						'astra-settings[sticky-below-header-menu-h-color-responsive]',
						'astra-settings[sticky-below-header-menu-h-a-bg-color-responsive]',
						'astra-settings[sticky-below-header-submenu-bg-color-responsive]',
						'astra-settings[sticky-below-header-submenu-color-responsive]',
						'astra-settings[sticky-below-header-submenu-h-color-responsive]',
						'astra-settings[sticky-below-header-submenu-h-a-bg-color-responsive]',
						'astra-settings[sticky-below-header-content-section-text-color-responsive]',
						'astra-settings[sticky-below-header-content-section-link-color-responsive]',
						'astra-settings[sticky-below-header-content-section-link-h-color-responsive]',
					],
					callback: function( val ) {

						if ( val != 'disabled' ) {
							return true;
						}

						return false;
					}
				}
			);
		} else {
			$( '#customize-control-astra-settings-header-below-stick' ).css( 'display', 'none' );
			$( '#accordion-section-section-colors-sticky-below-header' ).css( 'display', 'none' );
		}


		/**
		 * Hide on Scroll
		 */
		ASTCustomizerToggles ['astra-settings[sticky-hide-on-scroll]'] = [
			{
				controls: [
					'astra-settings[sticky-header-style]',
				],
				callback: function( val ) {
					main_stick = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';
					above_header_stick = (typeof api( 'astra-settings[header-above-stick]' ) != 'undefined') ? api( 'astra-settings[header-above-stick]' ).get() : '';
					below_header_stick = (typeof api( 'astra-settings[header-below-stick]' ) != 'undefined') ? api( 'astra-settings[header-below-stick]' ).get() : '';
					if ( val != 1 && ( main_stick == 1 || above_header_stick == 1 || below_header_stick == 1) ) {
						return true;
					}

					return false;
				}
			},
		];

		ASTCustomizerToggles ['astra-settings[different-sticky-logo]'] = [
			{
				controls: [
					'astra-settings[sticky-header-logo]',
					'astra-settings[different-sticky-retina-logo]',
					'astra-settings[sticky-header-logo-width]'
				],
				callback: function( val ) {
					
					primary_logo = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';

					if( val && primary_logo ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[sticky-header-retina-logo]',
				],
				callback: function( val ) {
					
					primary_logo = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';
					diff_retina_logo = (typeof api( 'astra-settings[different-sticky-retina-logo]' ) != 'undefined') ? api( 'astra-settings[different-sticky-retina-logo]' ).get() : '';

					if( val && primary_logo && diff_retina_logo ) {
						return true;
					}

					return false;
				}
			},
		];

		ASTCustomizerToggles ['astra-settings[different-sticky-retina-logo]'] = [
			{
				controls: [
					'astra-settings[sticky-header-retina-logo]',
				],
				callback: function( val ) {
					
					primary_logo = (typeof api( 'astra-settings[header-main-stick]' ) != 'undefined') ? api( 'astra-settings[header-main-stick]' ).get() : '';
		
					if( val && primary_logo ) {
						return true;
					}

					return false;
				}
			},
		];


		/* Site Identity */
		ASTCustomizerToggles ['astra-settings[display-site-title]'].push(
			{
				controls: [
					'astra-settings[sticky-header-color-site-title-responsive]',
					'astra-settings[sticky-header-color-h-site-title-responsive]',
					
				],
				callback: function( title ) {

					if ( title ) {
						return true;
					}
					return false;
				}
			}
		);

		/* Site Identity */
		ASTCustomizerToggles ['astra-settings[display-site-tagline]'].push(
			{
				controls: [
					'astra-settings[sticky-header-color-site-tagline-responsive]',
				],
				callback: function( tagline ) {

					if ( tagline ) {
						return true;
					}
					return false;
				}
			}
		);

		/* Custom Menu Item */
		ASTCustomizerToggles ['astra-settings[header-main-rt-section]'].push(
			{
				controls: [
					'astra-settings[divider-sticky-header-content-section]',
					'astra-settings[sticky-header-content-section-text-color-responsive]',
					'astra-settings[sticky-header-content-section-link-color-responsive]',
					'astra-settings[sticky-header-content-section-link-h-color-responsive]',
				],
				callback: function( val ) {
					 display_outside_menu     = api( 'astra-settings[header-display-outside-menu]' ).get();
					 custom_menu_item     = api( 'astra-settings[header-main-rt-section]' ).get();
					 console.log(display_outside_menu);
					if ( 'none' != val && true === display_outside_menu ) {
						return true;
					}
					return false;
				}
			}
		);

		/* Custom Menu Item Outside */
		if ( typeof ASTCustomizerToggles['astra-settings[header-display-outside-menu]'] != 'undefined' && ASTCustomizerToggles['astra-settings[header-display-outside-menu]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[header-display-outside-menu]'].push(
				{
					controls: [
						'astra-settings[divider-sticky-header-content-section]',
						'astra-settings[sticky-header-content-section-text-color-responsive]',
						'astra-settings[sticky-header-content-section-link-color-responsive]',
						'astra-settings[sticky-header-content-section-link-h-color-responsive]',
					],
					callback: function( val ) {
						 display_outside_menu     = api( 'astra-settings[header-display-outside-menu]' ).get();
						 custom_menu_item     = api( 'astra-settings[header-main-rt-section]' ).get();
						 console.log(display_outside_menu);
						if ( 'none' != val && true === display_outside_menu ) {
							return true;
						}
						return false;
					}
				}
			);
		} else {
			ASTCustomizerToggles['astra-settings[header-display-outside-menu]']= [
				{
					controls: [
						'astra-settings[divider-sticky-header-content-section]',
						'astra-settings[sticky-header-content-section-text-color-responsive]',
						'astra-settings[sticky-header-content-section-link-color-responsive]',
						'astra-settings[sticky-header-content-section-link-h-color-responsive]',
					],
					callback: function( val ) {
						 display_outside_menu     = api( 'astra-settings[header-display-outside-menu]' ).get();
						 custom_menu_item     = api( 'astra-settings[header-main-rt-section]' ).get();
						 console.log(display_outside_menu);
						if ( 'none' != val && true === display_outside_menu ) {
							return true;
						}
						return false;
					}
				}
			];
		}

	});

})( jQuery );
