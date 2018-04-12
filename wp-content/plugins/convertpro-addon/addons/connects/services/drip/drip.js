(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var DripService = {

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
			
			parentObj.listAccountFrm	= $( '.cp-account-list-form' );
			
			// Drip account change
			parentObj.listAccountFrm.off( 'change', 'select[name=drip_account_id]' );
			parentObj.listAccountFrm.on( 'change', 'select[name=drip_account_id]', this._DripAccountChange );
		},

		/**
		 * Validates selected lists.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=drip_account_id]' ),
				err 	= false;

			if( list.val() == -1 ) {
				err = cp_services.valid_drip_account;
			}
			DripService.parentObj._setisValidated( err );
		},

		/* Drip
		----------------------------------------------------------*/
		
		/**
		 * Fires when the Drip account select is changed.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_DripAccountChange: function()
		{
			var wrap        = $( this ).closest( '.convert-plug-v2-service-settings' ),
				select      = wrap.find( '.convert-plug-v2-service-select' ),
				account     = DripService.parentObj.listAccountFrm.find( 'input[name=cp-integration-account-slug]' ),
				client      = $( this ),
				list        = wrap.find( '.convert-plug-v2-service-list-select' ),
				value       = client.val();

			if ( 0 !== list.length ) {
				selectWrap  = DripService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
				return;
			}
			if ( '' === value ) {
				return;
			}
			DripService.parentObj._startSettingsLoading( select );

			DripService.parentObj.ajaxCall( {
				action  : 'cppro_render_service_fields',
				service : 'drip',
				account : account.val(),
				client  : value
			}, DripService._DripAccountChangeComplete );
		},
		
		/**
		 * AJAX callback for when the Drip account select is changed.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_DripAccountChangeComplete: function( response )
		{
			var data    = JSON.parse( response ),
			selectWrap  = DripService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );

			selectWrap.find('div.cp-drip_list_id-wrap').hide();
			selectWrap.find('div.cp-drip_tags-wrap').hide();

			if( !data.error ) {
				DripService.parentObj._updateErrorMsg( '' );
				// selectWrap.find('div.cp-drip_account_id-wrap').remove();
				selectWrap.find( '.cp-drip_double_optin-wrap' ).remove();
				selectWrap.append( data.html );
			} else {
				DripService.parentObj._updateErrorMsg( data.error );
			}

			DripService.parentObj._finishSettingsLoading();
		},
	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			DripService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			DripService._validateOptions();
		});
	});

})( jQuery );