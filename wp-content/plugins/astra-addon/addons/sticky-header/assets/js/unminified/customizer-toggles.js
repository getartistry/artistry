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
					'astra-settings[sticky-header-bg-opc]',
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
					'astra-settings[sticky-header-bg-opc]',
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
					'astra-settings[sticky-header-bg-opc]',
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
					'astra-settings[sticky-header-logo]',
					'astra-settings[sticky-header-retina-logo]',
					'astra-settings[sticky-header-logo-width]',
				],
				callback: function( val ) {
					if ( val == 1 ) {
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
					'astra-settings[header-above-stick]'
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
		}

		/**
		 * Below Header
		 */
		if ( typeof ASTCustomizerToggles['astra-settings[below-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-layout]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[below-header-layout]'].push(
				{
					controls: [
					'astra-settings[header-below-stick]'
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
		]
	});

})( jQuery );
