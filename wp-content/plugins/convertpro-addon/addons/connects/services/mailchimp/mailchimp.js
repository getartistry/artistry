(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var MailchimpService = {

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
			
			// MailChimp Events
			parentObj.listAccountFrm.off( 'change', 'select[name=mailchimp_list]' );
			parentObj.listAccountFrm.on( 'change', 'select[name=mailchimp_list]', this._mailChimpListChange );
		},

		/**
		 * Validates selected lists/groups.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		_validateOptions: function( parentObj ) {

			var list 	= $( 'select[name=mailchimp_list]' ),
				err 	= false;

			if( list.val() == -1 ) {
				err = cp_services.valid_list;
			}

			MailchimpService.parentObj._setisValidated( err );
		},
		
		/**
		 * Fires when the MailChimp list select is changed.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_mailChimpListChange: function()
		{
			var account     = MailchimpService.parentObj.listAccountFrm.find( 'input[name=cp-integration-account-slug]' ),
				list        = $( this );
			
			if ( '' === list.val() ) {
				selectWrap  = MailchimpService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
				selectWrap.find('div.cp-mailchimp_groups-wrap').hide();
				return;
			}
			
			MailchimpService.parentObj._startSettingsLoading();
			
			MailchimpService.parentObj.ajaxCall( {
				action  : 'cppro_render_service_fields',
				account : account.val(),
				list_id : list.val()
			}, MailchimpService._mailChimpListChangeComplete );
		},
		
		/**
		 * AJAX callback for when the MailChimp list select is changed.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_mailChimpListChangeComplete: function( response )
		{
			var data    = JSON.parse( response ),
				selectWrap  = MailchimpService.parentObj.listAccountFrm.find( '.cp-new-account-fields' );
			
			if( !data.error ) {
				MailchimpService.parentObj._updateErrorMsg( '' );
				selectWrap.find('div.cp-mailchimp_groups-wrap').remove();
				selectWrap.find('div.cp-mailchimp_segments-wrap').remove();
				selectWrap.find('div.cp-mailchimp_double_optin-wrap').remove();
				selectWrap.append( data.html );
			} else {
				MailchimpService.parentObj._updateErrorMsg( data.error );
			}

			MailchimpService.parentObj._finishSettingsLoading();
		},

	};

	$ ( function() {

		ConvertPlugServicesTrigger.addHook( 'cp-service-getlist-click', function( argument, obj ) {
			MailchimpService.init( obj );
		});

		ConvertPlugServicesTrigger.addHook( 'cp-service-list-validate', function( argument, obj ) {
			MailchimpService._validateOptions();
		});
	});

})( jQuery );