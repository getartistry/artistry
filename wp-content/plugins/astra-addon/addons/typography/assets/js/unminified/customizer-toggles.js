/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {

	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		/* Site Identity */
		ASTCustomizerToggles ['astra-settings[display-site-title]'].push(
			{
				controls: [
					'astra-settings[font-family-site-title]',
					'astra-settings[font-weight-site-title]',
					'astra-settings[text-transform-site-title]',
					'astra-settings[line-height-site-title]',
				],
				callback: function( display_tagline ) {

					if ( '1' == display_tagline ) {
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
					'astra-settings[font-family-site-tagline]',
					'astra-settings[font-weight-site-tagline]',
					'astra-settings[text-transform-site-tagline]',
					'astra-settings[line-height-site-tagline]',
				],
				callback: function( display_tagline ) {

					if ( '1' == display_tagline ) {
						return true;
					}
					return false;
				}
			}
		);

		if ( typeof ASTCustomizerToggles['astra-settings[title-bar-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[title-bar-layout]'].length > 0 ) {
			/* Title Bar Enabled */
			ASTCustomizerToggles ['astra-settings[title-bar-layout]'].push(
				{
					controls: [
						'astra-settings[font-family-entry-title]',
						'astra-settings[font-weight-entry-title]',
						'astra-settings[text-transform-entry-title]',
						'astra-settings[line-height-entry-title]',
					],
					callback: function( val ) {

						if ( val == 'none' ) {
							return true;
						}

						return false;
					}
				}
			);
		}

		/* Footer */
		ASTCustomizerToggles ['astra-settings[footer-sml-layout]'].push(
			{
				controls: [
					'astra-settings[font-family-footer-content]',
					'astra-settings[font-weight-footer-content]',
					'astra-settings[font-size-footer-content]',
					'astra-settings[text-transform-footer-content]',
					'astra-settings[line-height-footer-content]',
				],
				callback: function( val ) {

					if ( 'disabled' != val ) {
						return true;
					}
					return false;
				}
			}
		);

		if ( typeof ASTCustomizerToggles['astra-settings[header-display-outside-menu]'] != 'undefined' && ASTCustomizerToggles['astra-settings[header-display-outside-menu]'].length > 0 ) {

			ASTCustomizerToggles ['astra-settings[header-display-outside-menu]'].push(
				{
					controls: [
						'astra-settings[outside-menu-font-size]',
						'astra-settings[outside-menu-line-height]',
						'astra-settings[divider-section-outside-menu-typo-dropdown]'
					],
					callback: function( val ) {

						if ( val ) {
							return true;
						}

						return false;
					}
				}
			);
	    } else {

	    	ASTCustomizerToggles ['astra-settings[header-display-outside-menu]'] = [
	            {
	                controls: [
						'astra-settings[outside-menu-font-size]',
						'astra-settings[outside-menu-line-height]',
						'astra-settings[divider-section-outside-menu-typo-dropdown]'
					],
	                callback: function( val ) {

						if ( val ) {
							return true;
						}
						return false;
					}
	            },
	        ];
	    }

	});
})( jQuery );
