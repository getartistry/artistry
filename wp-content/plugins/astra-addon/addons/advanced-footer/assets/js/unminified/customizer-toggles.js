/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		/* Layout select */
		ASTCustomizerToggles ['astra-settings[footer-adv]'] = [

			{
				controls: [
					'astra-settings[footer-adv-area-padding]',
					'astra-settings[footer-adv-bg-img]',
					'astra-settings[footer-adv-bg-color]',
					'astra-settings[footer-adv-wgt-title-color]',
					'astra-settings[footer-adv-text-color]',
					'astra-settings[footer-adv-link-color]',
					'astra-settings[footer-adv-link-h-color]',
					'astra-settings[footer-adv-typo-title]',
					'astra-settings[footer-adv-typo-content]',
					'astra-settings[footer-adv-background-divider]',
					'astra-settings[footer-adv-wgt-title-font-family]',
					'astra-settings[footer-adv-wgt-title-font-weight]',
					'astra-settings[footer-adv-wgt-title-font-size]',
					'astra-settings[footer-adv-wgt-title-text-transform]',
					'astra-settings[footer-adv-wgt-title-line-height]',
					'astra-settings[footer-adv-wgt-content-font-family]',
					'astra-settings[footer-adv-wgt-content-font-weight]',
					'astra-settings[footer-adv-wgt-content-font-size]',
					'astra-settings[footer-adv-wgt-content-text-transform]',
					'astra-settings[footer-adv-wgt-content-line-height]',
					'astra-settings[footer-adv-layout-width]',
				],
				callback: function( val ) {

					if ( val != 'disabled' ) {
						return true;
					}

					return false;
				}
		}
		];

		/* Background Image Properties */
		ASTCustomizerToggles ['astra-settings[footer-adv-bg-img]'] = [

			{
				controls: [
					'astra-settings[footer-adv-bg-repeat]',
					'astra-settings[footer-adv-bg-size]',
					'astra-settings[footer-adv-bg-attac]',
					'astra-settings[footer-adv-bg-pos]',
				],
				callback: function( bg_img ) {
					var layout = api( 'astra-settings[footer-adv]' ).get();

					if ( '' != bg_img && layout != 'disabled') {
						return true;
					}
					return false;
				}
		},
			{
				controls: [
					'astra-settings[footer-adv-bg-color-opac]',
				],
				callback: function( bg_img ) {
					var layout = api( 'astra-settings[footer-adv]' ).get();
					var bg_color = api( 'astra-settings[footer-adv-bg-color]' ).get();

					if ( '' != bg_img && '' != bg_color && layout != 'disabled' ) {
						return true;
					}
					return false;
				}
		}
		];

		/* Background color Properties */
		ASTCustomizerToggles ['astra-settings[footer-adv-bg-color]'] = [

			{
				controls: [
					'astra-settings[footer-adv-bg-color-opac]',
				],
				callback: function( val ) {
					var bg_color = api( 'astra-settings[footer-adv-bg-color]' ).get();
					var bg_img = api( 'astra-settings[footer-adv-bg-img]' ).get();
					var layout = api( 'astra-settings[footer-adv]' ).get();

					if ( '' != bg_img && '' != bg_color && layout != 'disabled'  ) {
						return true;
					}
					return false;
				}
		}
		];

	});
})( jQuery );
