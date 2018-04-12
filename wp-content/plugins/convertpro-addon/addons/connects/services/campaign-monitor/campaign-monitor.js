(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var CampaignMonitorService = {

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

			parentObj.listAccountFrm = $( '.cp-account-list-form' );

			parentObj.listAccountFrm.off( 'change', 'select[name=campaign_monitor_client_id]' );
			parentObj.listAccountFrm.on( 'change', 'select[name=campaign_monitor_client_id]', this._campaignMonitorClientChange );
		},

		/**
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var client 	= $( 'select[name=campaign_monitor_client_id]' ),
				list 	= $( 'select[name=campaign_monitor_list_id]' ),
				err 	= false;

			if( client.val() == -1 ) {
				err = cp_services.valid_client;
			}

			if( list.length > 0 ) {
				if ( list.val() == -1 ) {
					err = cp_services.valid_list;
				}
			}

			CampaignMonitorService.parentObj._setisValidated( err );
		},



		/* Campaign Monitor
		----------------------------------------------------------*/
		
		/**
		 * Fires when the Campaign Monitor client select is changed.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_campaignMonitorClientChange: function()
		{

			var nodeId      = $( '.convert-plug-v2-settings' ).data( 'node' ),
				wrap        = $( this ).closest( '.convert-plug-v2-service-settings' ),
				select      = wrap.find( '.convert-plug-v2-service-select' ),
				account     = CampaignMonitorService.parentObj.listAccountFrm.find( 'input[name=cp-integration-account-slug]' ),
				client      = $( this ),
				list        = wrap.find( '.convert-plug-v2-service-list-select' ),
				value       = client.val();

			if ( 0 !== list.length ) {
				selectWrap  = CampaignMonitorService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
				selectWrap.find('div.cp-campaign_monitor_list_id-wrap').hide();
				return;
			}
			if ( '' === value ) {
				return;
			}
			CampaignMonitorService.parentObj._startSettingsLoading( select );

			CampaignMonitorService.parentObj.ajaxCall( {
				action  : 'cppro_render_service_fields',
				service : 'campaign-monitor',
				account : account.val(),
				client  : value
			}, CampaignMonitorService._campaignMonitorClientChangeComplete );
		},
		
		/**
		 * AJAX callback for when the Campaign Monitor client select is changed.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_campaignMonitorClientChangeComplete: function( response )
		{

			var data    = JSON.parse( response ),
			selectWrap  = CampaignMonitorService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
			
			if( !data.error ) {
				CampaignMonitorService.parentObj._updateErrorMsg( '' );
				selectWrap.find('div.cp-campaign_monitor_list_id-wrap').remove();
				selectWrap.append( data.html );
			} else {
				CampaignMonitorService.parentObj._updateErrorMsg( data.error );
			}

			CampaignMonitorService.parentObj._finishSettingsLoading();
		},
	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			CampaignMonitorService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			CampaignMonitorService._validateOptions();
		});

	});

})( jQuery );