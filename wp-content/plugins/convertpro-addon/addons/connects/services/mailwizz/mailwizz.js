(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.3
	 */

	var MailWizzService = {

		parentObj : '',
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 1.0.3
		 */
		init: function( parentObj )
		{
			this.parentObj = parentObj;
			parentObj.listAccountFrm 	= $( '.cp-account-list-form' );			
		},

		/**
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.3
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=mailwizz_lists]' ),
				err 	= false;

			if( list.val() == -1 ) {
				err = cp_services.valid_list;
			}

			MailWizzService.parentObj._setisValidated( err );
		},		

	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			MailWizzService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			MailWizzService._validateOptions();
		});
	});

})( jQuery );