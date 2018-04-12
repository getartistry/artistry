(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var InfusionsoftService = {

		parentObj : '',
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		init: function( parentObj ) {
			this.parentObj = parentObj;
		},

		/**
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=infusionsoft_tags] option:selected' ),
				err 	= false;

			if( list.length < 1 ) {
				err = cp_services.valid_tag;
			}

			InfusionsoftService.parentObj._setisValidated( err );
		},

	};

	$ ( function() {
		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			InfusionsoftService.init( obj );
			InfusionsoftService._validateOptions();
		});
	});

})( jQuery );