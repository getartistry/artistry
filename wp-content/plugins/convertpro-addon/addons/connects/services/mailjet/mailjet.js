(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var MailJetService = {

		parentObj : '',
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		init: function( parentObj )
		{
			this.parentObj = parentObj;
			parentObj.listAccountFrm 	= $( '.cp-account-list-form' );
		},

		/**
		 * Validates selected list.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=list_id]' ),
				err 	= false;

			if( list.val() == -1 ) {
				err = cp_services.valid_list;
			}
 
			MailJetService.parentObj._setisValidated( err );
		},
	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			MailJetService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			MailJetService._validateOptions();
		});
	});

})( jQuery );