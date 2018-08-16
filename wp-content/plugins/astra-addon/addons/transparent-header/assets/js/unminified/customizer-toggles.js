/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		/* Layout select */
		ASTCustomizerToggles ['astra-settings[different-transparent-logo]'] = [

			{
				controls: [
					'astra-settings[transparent-header-logo]',
					'astra-settings[transparent-header-logo-width]',
					'astra-settings[different-transparent-retina-logo]',
				],
				callback: function( val ) {

					transparent_logo = (typeof api( 'astra-settings[transparent-header-enable]' ) != 'undefined') ? api( 'astra-settings[transparent-header-enable]' ).get() : '';

					if ( val && transparent_logo ) {
						return true;
					}

					return false;
				}
			},

			{
				controls: [
					'astra-settings[transparent-header-retina-logo]',
				],
				callback: function( val ) {

					transparent_logo = (typeof api( 'astra-settings[transparent-header-enable]' ) != 'undefined') ? api( 'astra-settings[transparent-header-enable]' ).get() : '';
					diff_retina_logo = (typeof api( 'astra-settings[different-transparent-retina-logo]' ) != 'undefined') ? api( 'astra-settings[different-transparent-retina-logo]' ).get() : '';

					if ( val && transparent_logo && diff_retina_logo ) {
						return true;
					}

					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[transparent-header-enable]'] = [

			{
				controls: [
					'astra-settings[transparent-header-disable-archive]',
					'astra-settings[different-transparent-logo]',
					'astra-settings[transparent-header-main-sep]',
					'astra-settings[transparent-header-main-sep-color]'
				],
				callback: function( val ) {

					if ( val ) {
						return true;
					}

					return false;
				}
			},

			{
				controls: [
					'astra-settings[different-transparent-retina-logo]',
				],
				callback: function( val ) {

					transparent_logo = (typeof api( 'astra-settings[transparent-header-enable]' ) != 'undefined') ? api( 'astra-settings[transparent-header-enable]' ).get() : '';

					if ( val && transparent_logo ) {
						return true;
					}

					return false;
				}
			},

			{
				controls: [
					'astra-settings[transparent-header-logo]',
					'astra-settings[transparent-header-logo-width]',
				],
				callback: function( val ) {

					diff_logo = (typeof api( 'astra-settings[different-transparent-logo]' ) != 'undefined') ? api( 'astra-settings[different-transparent-logo]' ).get() : '';

					if ( val && diff_logo ) {
						return true;
					}

					return false;
				}
			},

			{
				controls: [
					'astra-settings[transparent-header-retina-logo]',
				],
				callback: function( val ) {
					
					diff_logo = (typeof api( 'astra-settings[different-transparent-logo]' ) != 'undefined') ? api( 'astra-settings[different-transparent-logo]' ).get() : '';
					diff_retina_logo = (typeof api( 'astra-settings[different-transparent-retina-logo]' ) != 'undefined') ? api( 'astra-settings[different-transparent-retina-logo]' ).get() : '';

					if ( val && diff_logo && diff_retina_logo ) {
						return true;
					}

					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[different-transparent-retina-logo]'] = [

			{
				controls: [
					'astra-settings[transparent-header-retina-logo]',
				],
				callback: function( val ) {

					transparent_logo = (typeof api( 'astra-settings[transparent-header-enable]' ) != 'undefined') ? api( 'astra-settings[transparent-header-enable]' ).get() : '';
					diff_logo = (typeof api( 'astra-settings[different-transparent-logo]' ) != 'undefined') ? api( 'astra-settings[different-transparent-logo]' ).get() : '';

					if ( val && transparent_logo && diff_logo ) {
						return true;
					}

					return false;
				}
			}
		];

		/* Site Identity */
		ASTCustomizerToggles ['astra-settings[display-site-title]'].push(
			{
				controls: [
					'astra-settings[transparent-header-color-site-title-responsive]',
					'astra-settings[transparent-header-color-h-site-title-responsive]',
					
				],
				callback: function( title ) {

					if ( title ) {
						return true;
					}
					return false;
				}
			}
		);

	});

})( jQuery );
