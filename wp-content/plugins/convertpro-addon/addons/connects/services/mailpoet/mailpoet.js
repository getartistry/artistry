(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var MailPoetService = {

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

			var mappingFooter	= $( '.cp-md-modal-footer' );
			mappingFooter.off( 'click', 'button.cp-next-connects' );
			mappingFooter.on( 'click', 'button.cp-next-connects', this._selectListButton );
		},

		/**
		 * Validates selected list.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_selectListButton: function( parentObj ) {
 
			var list 	= $( 'select[name=list_id]' ),
				err 	= false;

			if( list.length > 0 ) {
				if ( list.val() == -1 ) {
					err = cp_services.valid_list;
				}
			}

			MailPoetService.parentObj._setisValidated( err );
		 },
	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			MailPoetService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'button.cp-next-connects', function( argument, obj ) {
			MailPoetService._selectListButton();
		});
	});

})( jQuery );