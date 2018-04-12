(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.2
	 */

	var SendlaneService = {

		parentObj : '',
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 1.0.2
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
		 * @since 1.0.2
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=sendlane_list]' ),
				err 	= false;

			if( list.val() == -1 ) {
				err = cp_services.valid_list;
			}

			SendlaneService.parentObj._setisValidated( err );
		},

	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			SendlaneService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			SendlaneService._validateOptions();
		});
	});

})( jQuery );