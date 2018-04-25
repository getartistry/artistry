/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.3.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		/* Profile Link */
		ASTCustomizerToggles['astra-settings[learndash-profile-link-enabled]'] = [
			{
				controls: [
					'astra-settings[learndash-profile-link]'
				],
				callback: function( enabled_profile_link ) {

					if ( enabled_profile_link ) {
						return true;
					}

					return false;
				}
			},
		];
	});
})( jQuery );
