(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var OntraportService = {

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
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var seqs 	= $( 'select[name=ontraport_seqs]' ),
				err 	= false;

			if( seqs.val() == -1 ) {
				err = cp_services.valid_sequence;
			}

			OntraportService.parentObj._setisValidated( err );
		},		

	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			OntraportService.init( obj );
		});

		// ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
		// 	OntraportService._validateOptions();
		// });
	});

})( jQuery );