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
					'astra-settings[header-color-site-title]',
					'astra-settings[header-color-h-site-title]',
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
					'astra-settings[header-color-site-tagline]',
				],
				callback: function( tagline ) {

					if ( tagline ) {
						return true;
					}
					return false;
				}
			}
		);

		/* Primary Submenu Border */
		ASTCustomizerToggles ['astra-settings[primary-submenu-border]'] = [
			{
				controls: [
					'astra-settings[primary-submenu-b-color]',
				],
				callback: function( val ) {

					if ( val ) {
						return true;
					}
					return false;
				}
			},
		];

		/* Footer */
		ASTCustomizerToggles ['astra-settings[footer-sml-layout]'].push(
			{
				controls: [
					'astra-settings[footer-bg-obj]',
					'astra-settings[footer-color]',
					'astra-settings[footer-link-color]',
					'astra-settings[footer-link-h-color]',
				],
				callback: function( val ) {

					if ( 'disabled' != val ) {
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
						'astra-settings[entry-title-color]',
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

	});

	/**
	 * Container Manager
	 */
	var site_content_layout       = [ 'site-content-layout' ],
		container_single  = astra.customizer.settings.container.single,
		container_archive = astra.customizer.settings.container.archive,
		merged_container  = jQuery.merge( site_content_layout, container_single ),
		merged_container  = jQuery.merge( merged_container, container_archive );

	jQuery.each( merged_container , function( sidebar_switch, content_layout ) {
		ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

			ASTCustomizerToggles[ 'astra-settings['+content_layout+']' ] =
			[
				{
					controls: [
						'astra-settings[content-bg-color]',
						'astra-settings[divider-bg-color]',
					],
					callback: function( content ) {
						var any_layout  = '';
						var content     = api( 'astra-settings[site-content-layout]' ).get();
						jQuery.each( merged_container, function( index, c_layout ) {
							var type   = api( 'astra-settings['+c_layout+']' ).get() || '';

							// Is plain-container?
							if( 'page-builder' != type && 'default' != type ) {
								any_layout = 'yes';
								return false;
							}
						});

						// Content Layout.
						if( 'page-builder' != content && 'default' != content ) {
							any_layout = 'yes';
						}

						if( any_layout ) {
							return true;
						} else {
							return false;
						}
					}
				},
			]
		});
	});

})( jQuery );
