jQuery( document ).ready( function () {

	jQuery( '#itsec_password_generate' ).change( function () {

		if ( jQuery( '#itsec_password_generate' ).is( ':checked' ) ) {

			jQuery( '#password-settings-2' ).show();

		} else {

			jQuery( '#password-settings-2' ).hide();

		}

	} ).change();

	jQuery( '#itsec_password_expire' ).change( function () {

		if ( jQuery( '#itsec_password_expire' ).is( ':checked' ) ) {

			jQuery( '#password-settings-4' ).show();

		} else {

			jQuery( '#password-settings-4' ).hide();

		}

	} ).change();

} );
