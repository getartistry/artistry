(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var ActiveCampaignService = {

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
			
			// ActiveCampaign Events
			parentObj.listAccountFrm.off( 'change', 'select[name=activecampaign_type]' );
			parentObj.listAccountFrm.on( 'change', 'select[name=activecampaign_type]', this._activeCampaignListChange );
		},

		/* Active Campaign
		----------------------------------------------------------*/
		
		/**
		 * Fires when the Active Campaign list select is changed.
		 *
		 * @return void
		 * @since 1.6.0
		 */
		_activeCampaignListChange: function()
		{

			var account     = ActiveCampaignService.parentObj.listAccountFrm.find( 'input[name=cp-integration-account-slug]' ),
				list        = $( this );

			ActiveCampaignService.parentObj._startSettingsLoading();	
			if ( '' === list.val() ) {
				return;
			}			

			$( '.cp-activecampaign_tags-wrap' ).remove();

			if ( list.val() == 'list' ) {
				$( '.cp-activecampaign_forms-wrap' ).remove();
			}
			if ( list.val() == 'form' ) {
				$( '.cp-activecampaign_lists-wrap' ).remove();
			}
			if ( list.val() == '-1' ) {
				$( '.cp-activecampaign_lists-wrap' ).remove();
				$( '.cp-activecampaign_forms-wrap' ).remove();
			}


			ActiveCampaignService.parentObj.ajaxCall( {
				action  : 'cppro_render_service_fields',
				account : account.val(),
				list_id : list.val()
			}, ActiveCampaignService._activeCampaignListChangeComplete );
		},
		
		/**
		 * AJAX callback for when the Active Campaign list select is changed.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.6.0
		 */
		_activeCampaignListChangeComplete: function( response )
		{
			var data    = JSON.parse( response ),
				selectWrap  = ActiveCampaignService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
				
			$( '.cp-activecampaign_lists-wrap' ).remove();
			$( '.cp-activecampaign_forms-wrap' ).remove();
			$( '.cp-activecampaign_tags-wrap' ).remove();

			if( !data.error ) {
				ActiveCampaignService.parentObj._updateErrorMsg( '' );

				selectWrap.append( data.html );
			} else {
				ActiveCampaignService.parentObj._updateErrorMsg( data.error );
			}
			ActiveCampaignService.parentObj._finishSettingsLoading();
		},

		/**
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var list_type 	= $( 'select[name=activecampaign_type]' ),
				list 		= $( 'select[name=activecampaign_lists]' ),
				form 		= $( 'select[name=activecampaign_forms]' ),
				err 	= false;

			if( list_type.val() == -1 ) {
				err = cp_services.list_or_form;
			}

			if( list.val() == -1 ) {
				err = cp_services.valid_list;
			}

			if( form.val() == -1 ) {
				err = cp_services.valid_form;
			}

			ActiveCampaignService.parentObj._setisValidated( err );
		},
	};


	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			ActiveCampaignService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			ActiveCampaignService._validateOptions();
		});
	});

})( jQuery );