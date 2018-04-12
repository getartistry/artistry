(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 1.0.0
	 */

	var mapping_fields 		= '',
		mapping 			= new Array(),
		currentService 		= '',
		custom_field 		= new Array(),
		authenticateBtn		= '',
		newAccountBtn		= '',
		existingAccountBtn 	= '',
		removeAccountBtn	= '',
		nextBtn				= '',
		backBtn				= '',
		saveBtn				= '',
		errorWrap			= '',
		customizer_form 	= '',
		newAccountFrm 		= '',
		listAccountFrm 		= '',
		selectAccountFrm 	= '';
		allSteps			= '';
		currentStep 		= '';
		currentStepIndex 	= '';
		loader 				= '';
		isValidated			= false;
		isEmailFieldAdded	= false;
		style_id 			= 0,
		noMapping			= false,
		modal 				= '',
		whr_to_find			= '',
		insyncSource		= '';

	var ConvertPlugServices = {
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		init: function()
		{
			var body 			= $('body'),
				customizer_form = $( '.cp-api-integration-form' );


			authenticateBtn	= $( '.cp-md-modal-content button.cp-authenticate-connects' );
			newAccountBtn	= $( '.cp-md-modal-content button.cp-add-new-account' );
			existingAccountBtn = $( '.cp-md-modal-content button.cp-use-existing-account' );
			nextBtn			= $( '.cp-md-modal-content button.cp-next-connects' );
			backBtn			= $( '.cp-md-modal-content button.cp-back-connects' );
			saveBtn			= $( '.cp-md-modal-content button.cp-save-connects' );
			errorWrap		= $( '.cp-md-content .cp-response-wrap' );
			newAccountFrm 	= $( '.cp-add-new-account-form' );
			listAccountFrm 	= $( '.cp-account-list-form' );
			selectAccountFrm = $( '.cp-api-integration-form' );
			mappingFrm 		= $( '.cp-account-mapping-form' );
			allSteps		= $( '.cp-md-modal-content .cp-md-steps' );
			currentStep		= $( '.cp-md-modal-content .cp-md-steps li.cp-present-step' );
			loader 			= $( '.cp-md-modal' ).find( '.cp-md-loader' );
			isValidated		= false;
			isEdit 			= false;
			style_id 		= $( '#cp_style_id' ).val();
			modal 			= $( '#cp-md-modal-1' );
			whr_to_find		= $( '.cp-md-info-wrap' );

			whr_to_find.css( 'visibility', 'hidden' );

			// Standard Events
			body.delegate( '.cp-md-trigger', 'click', this._serviceSelected );
			body.delegate( 'select[name=cp_select_account]', 'cp-invoke', this._serviceChange );
			body.delegate( '.cp-remove-account', 'click', this._removeAccount );
			body.delegate( '.cp-change-account', 'click', this._changeAccount );
			body.delegate( '.cp-edit-account', 'click', this._changeAccount );
			body.delegate( '.cp-customizer-remove-account', 'click', this._deleteAccount );

			body.delegate( '.cp-md-close', 'click', this._removeAssets );


			newAccountBtn.on( 'click', this._addAccount );
			existingAccountBtn.on( 'click', this._useExistingAccount );
			authenticateBtn.on( 'click', this._authenticateCredentials );
			backBtn.on( 'click', this._backStepButton );
			nextBtn.on( 'click', this._nextStepButton );
			saveBtn.on( 'click', this._saveMeta );

			$( document ).on( 'change', '.cp-is_form-wrap select[name=is_form]', this._changeMauticForm );

			$( document ).on( 'click', '.cp-save', this._checkMapping );
			$( document ).on( 'click', '.cp-mapping-notice .error-close', this._closeWarning );

			/* Mapping Field changed to custom */
			$( document ).on( 'change', '.cp-mapping-fields select', this._mappingFieldChange );
			this._openPopup();
		},

		_closeWarning: function() {
			$( '.cp-mapping-notice' ).html( '' );
		},

		_checkMapping: function() {

			var cp_mapping_value 	= $( '#connect input[name=cp_mapping]' ).val(),
				cp_mapping_obj		= '',
				input_array 		= new Array(),
				unmapped 			= new Array(),
				unmapped_inputs		= new Array(),
				cp_mapping_arr 		= new Array();

			$( '.cp-mapping-notice' ).html( '' );

			if( '-1' != cp_mapping_value ) {

				cp_mapping_obj = JSON.parse( cp_mapping_value );

				// Get all present input fields.
				$( '.cp-form-input-field' ).each( function( i, val ) {
					if ( ! ( $( this ).hasClass( 'cp-email' ) ) ) {
						input_array.push( $( this ).attr( 'name' ) );
					}
				});

				unmapped = input_array;

				// Get mapped input fields.
				$.each( cp_mapping_obj, function( key, map_value ) {
					var obj_name = map_value.name;
					if ( obj_name.indexOf( '{input}' ) === -1 ) {
						cp_mapping_arr.push( obj_name );
					}
				});

				unmapped_inputs = cp_mapping_arr;
				
				// Check which field is not yet mapped.
				for ( var i = 0; i < input_array.length; i++ ) {
					for ( var j = 0; j < cp_mapping_arr.length; j++ ) {

						var obj_name = cp_mapping_arr[j];

						_name = obj_name.replace( 'cp_mapping{', '' );
						_name = _name.replace( '}', '' );

						if( _name == input_array[i] ) {

							index = unmapped.indexOf( input_array[i] );
							if ( index > -1 ) {
							    unmapped.splice( index, 1 );
							}
						}
					}
				}

				if( unmapped.length > 0 ) {
					var str = '',
						name = '',
						text_str = cp_services.mapping_notice,
						mailer_name = $( '.cp-connect-integration-meta .cp-change-account' ).data( 'service-title' ),
						account_name = $( '.cp-connect-integration-meta .cp-active-title' ).text();

					for ( var i = 0; i < unmapped.length; i++ ) {
						name = $( '.cp-form-input-field[name=' + unmapped[i] + ']' ).data( 'placeholder' );
						str += '<div class="cp-error-wrap"><span class="cp-error-field">' + name + '</span></div>';
					}

					text_str = text_str.replace( '##mailer_name##', mailer_name );
					text_str = text_str.replace( '##account_name##', account_name );

					$( '.cp-mapping-notice' ).html(
						$( '<div>', {
							class: 'cp-mapping-notice-wrap'
						} )
					);

					$( '.cp-mapping-notice-wrap' ).html(
						$( '<div>', {
							class: 'cp-mapping-notice-head'
						} ).append(
							$( '<span>' ).html( text_str )
						)
					);

					$( '.cp-mapping-notice-wrap' ).append(
						$( '<div>' ).append( str + '<span class="error-close">&times;<span>' )
					);
				}
			}
		},

		_changeMauticForm: function() {
			var val = $( this ).val();
			if( val != '' && val != '-1' ) {
				if( val == 'api' ) {
					$( '.cp-public_key-wrap' ).show();
					$( '.cp-secret_key-wrap' ).show();
					$( '.cp-form_id-wrap' ).hide();
				} else {
					$( '.cp-public_key-wrap' ).hide();
					$( '.cp-secret_key-wrap' ).hide();
					$( '.cp-form_id-wrap' ).show();
				}
			} else {
				$( '.cp-public_key-wrap' ).hide();
				$( '.cp-secret_key-wrap' ).hide();
				$( '.cp-form_id-wrap' ).hide();
			}
		},

		_openPopup: function() {
			var open_connects 	= ConvertPlugServices._getParameterByName( 'open_connects' ),
				account_id 		= ConvertPlugServices._getParameterByName( 'account' ),
				url_serv 		= ConvertPlugServices._getParameterByName( 'service' );

			if( open_connects == 'true' && account_id != 'null' ) {
				if( url_serv == 'mautic' ) {
					$( '.cp-connect-service-mautic' ).trigger( 'click' );
				}
				if( url_serv == 'verticalresponse' ) {
					$( '.cp-connect-service-verticalresponse' ).trigger( 'click' );
				}
				setTimeout( function() {
					ConvertPlugServices._startSettingsLoading();
					selectAccountFrm.find( 'input[type=radio][value=' + account_id + ']' ).prop('checked', true);
					nextBtn.trigger( 'click' );
				}, 200 );
			}
		},

		_getParameterByName: function( name, url ) {

			if (!url) url = window.location.href;
		    name = name.replace(/[\[\]]/g, "\\$&");
		    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		        results = regex.exec(url);
		    if (!results) return null;
		    if (!results[2]) return '';
		    return decodeURIComponent(results[2].replace(/\+/g, " "));

		},

		_serviceChange: function() {
			noMapping = true;
			ConvertPlugServices._updateErrorMsg( '' );
			
			// Loader loads here.
			ConvertPlugServices._startSettingsLoading();
			isEdit = false;

			// Handle steps classes
			ConvertPlugServices._updateSteps( 1 );

			var service = $( this ).val(),
				serviceTitle = $( this ).find("option[value=" + service + "]").data('title'),
				imgSrc = cp_services.image_base_url + service + '.png';

			modal.find( '.cp-md-modal-header img' ).attr( 'src', imgSrc );
			modal.find('.cp-md-modal-title').text( serviceTitle + ' Integration Setup' );

			// Check if service is selected
			if ( -1 == service || undefined == service ) {
				ConvertPlugServices._updateErrorMsg( cp_services.wrong );
				return;
			}

			currentService = service;

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_get_assets_data',
				service : service
			}, ConvertPlugServices._assetsDataComplete );

			listAccountFrm.find( 'input[name=cp-integration-service]' ).val( currentService );

			if( service == 'mailpoet' || service == 'mymail' ) {
				// Form show/hide
				selectAccountFrm.hide();
				listAccountFrm.show();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();

				// Show only Next/Back btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.hide();
				saveBtn.hide();
				authenticateBtn.hide();

				ConvertPlugServices._getLists( service );
				return;
			}

			// Form show/hide
			selectAccountFrm.show();
			nextBtn.show();
			listAccountFrm.hide();
			newAccountFrm.hide();
			whr_to_find.css( 'visibility', 'hidden' );
			mappingFrm.hide();

			// Show only Add new account btn
			newAccountBtn.show();
			existingAccountBtn.hide();
			backBtn.hide();
			saveBtn.hide();
			authenticateBtn.hide();

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_accounts',
				service : service,
				selected: '',
			}, ConvertPlugServices._serviceSelectedComplete );

			// As per service - Renders input credentials field for service
			// In case of Mailchimp - API Key Input field is rendered
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_settings',
				service : service
			}, ConvertPlugServices._serviceInputFieldsComplete );
		},

		_removeAssets: function() {
			ConvertPlugServicesTrigger.removeHook( 'cp-service-getlist-click' );
			ConvertPlugServicesTrigger.removeHook( 'cp-service-list-validate' );
			$( 'script.cp-mailer-' + currentService + '-js' ).remove();
			$( 'script.cp-mailer-' + currentService + '-css' ).remove();
			modal.removeClass( 'cp-md-show' );
		},

		_getisValidated: function() {
			return isValidated;
		},

		_setisValidated: function( val ) {
			isValidated = val;
		},

		_updateSteps: function ( presentIndex ) {

			currentStepIndex = presentIndex;

			allSteps.find( 'li' ).removeClass( 'cp-present-step' );
			allSteps.find( 'li:nth-child(' + presentIndex + ')' ).removeClass( 'cp-past-step cp-future-step' ).addClass( 'cp-present-step' );


			allSteps.find( 'li:gt(' + ( presentIndex - 1 ) + ')' ).removeClass( 'cp-past-step' ).addClass( 'cp-future-step' );
			allSteps.find( 'li:lt(' + ( presentIndex - 1 ) + ')' ).removeClass( 'cp-future-step' ).addClass( 'cp-past-step' );

			$( '.cp-md-modal .cp-md-contents' ).removeClass( 'cp-step-1 cp-step-2 cp-step-3' );
			$( '.cp-md-modal .cp-md-contents' ).addClass( 'cp-step-' + presentIndex );
		},

		_changeAccount: function () {

			isEdit = true;
			var src = $( this ).data( 'source' );
			if( typeof src != 'undefined' ) {
				insyncSource = src;
				noMapping = true;
			}
			ConvertPlugServices._updateErrorMsg( '' );

			modal.addClass( 'cp-md-show' );
			// Loader loads here.
			ConvertPlugServices._startSettingsLoading();

			// Handle steps classes
			ConvertPlugServices._updateSteps( 1 );

			var service = $( this ).data( 'service' ),
				selectedAccount = $( this ).data( 'account' ),
				serviceTitle = $( this ).data( 'service-title' );

			currentService = service;

			listAccountFrm.find( 'input[name=cp-integration-service]' ).val( currentService );
			listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).val( selectedAccount );
			listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).data( 'account-title', selectedAccount );
			modal.find('.cp-md-modal-title').text( serviceTitle + ' Integration Setup' );
			modal.find('.cp-md-modal-header img').attr( 'src', cp_services.image_base_url + service + '.png' );

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_get_assets_data',
				service : service
			}, ConvertPlugServices._assetsDataComplete );

			if( selectedAccount == 'mailpoet' || selectedAccount == 'mymail' ) {

				// Form show/hide
				selectAccountFrm.hide();
				listAccountFrm.show();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();

				// Show only Next/Back btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.hide();
				saveBtn.hide();
				authenticateBtn.hide();
				ConvertPlugServices._getLists( selectedAccount );
	
				return;
			}
			
			// Form show/hide
			selectAccountFrm.show();
			listAccountFrm.hide();
			newAccountFrm.hide();
			whr_to_find.css( 'visibility', 'hidden' );
			mappingFrm.hide();

			// Show only Add new account btn
			newAccountBtn.show();
			existingAccountBtn.hide();
			backBtn.hide();
			saveBtn.hide();
			authenticateBtn.hide();
			nextBtn.show();

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_accounts',
				service : service,
				selected : selectedAccount
			}, ConvertPlugServices._serviceSelectedComplete );

			// As per service - Renders input credentials field for service
			// In case of Mailchimp - API Key Input field is rendered
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_settings',
				service : service
			}, ConvertPlugServices._serviceInputFieldsComplete );
		},

		_deleteAccount: function () {

			var $this = $( this);

			var account_name = $this.data( 'account-name' );
			var is_associated = $this.data('isassociated');
			var radioInputData = false;
			
			if ( is_associated > 0 ) {
				var errMsg = cp_services.cant_delete.replace( "##account_name##", account_name );
				ConvertPlugServices._updateErrorMsg( errMsg )
				return;
			}
			var confirm_msg = cp_services.confirm_delete.replace( "##account_name##", account_name );
			if( confirm( confirm_msg ) ) {

				ConvertPlugServices._startSettingsLoading();

				radioInputData = $this.closest('.cp-customizer-radio').find('input').attr('data-selected-account');
				var selectedAccount = $( this ).data( 'account-slug' );

				ConvertPlugServices.ajaxCall( {
					action  : 'cppro_delete_service_account',
					account : selectedAccount,
				}, function( response ) {
					
					var data = JSON.parse( response );
					if ( ! data.error ) {
						$this.closest('.cp-service_accounts-wrap').empty();
						
						// Renders all accounts associated with the service
						// Say all mailchimp accounts
						ConvertPlugServices.ajaxCall( {
							action  : 'cppro_render_service_accounts',
							service : currentService,
							selected: '',
						}, ConvertPlugServices._serviceSelectedComplete );
					} else {

					}
				});

			}
		},

		_removeAccount: function () {

			if( confirm( cp_services.confirm_remove ) ) {

				ConvertPlugServices._startSettingsLoading();

				$( '#connect input[name=cp_mapping]' ).val( -1 );
				$( '#connect input[name=cp_connect_settings]' ).val( -1 );

				// Save the values for connect against the style_id
				ConvertPlugServices.ajaxCall( {
					action  : 'cppro_save_meta_settings',
					style_id : style_id,
					cp_mapping : -1,
					cp_taxonomy : -1
				}, ConvertPlugServices._metaDeleteComplete );
			}
		},

		_metaDeleteComplete: function( response ) {
			var data = JSON.parse( response );

			if( ! data.error ) {

				$( '#connect .cp-connect-integration-meta' ).addClass( 'cp-hidden' );
				$( '#connect .cp-connect-integration-wrap' ).removeClass( 'cp-hidden' );
				ConvertPlugServices._finishSettingsLoading();
				$( '.cp-md-close' ).trigger( 'click' );

			} else {
				ConvertPlugServices._finishSettingsLoading();
				ConvertPlugServices._updateErrorMsg( data.error );
			}
		},

		_backStepButton: function () {
			
			ConvertPlugServices._updateErrorMsg( '' );

			ConvertPlugServices._startSettingsLoading();
			
			if ( currentStepIndex == 2 ) { 
				ConvertPlugServices._useExistingAccount();

				var accountLists = $('.cp-customizer-radio' ),
					selected = accountLists.find('input:checked').val();

				// Renders all accounts associated with the service
				// Say all mailchimp accounts
				ConvertPlugServices.ajaxCall( {
					action  : 'cppro_render_service_accounts',
					service : currentService,
					selected: ( typeof selected == undefined ) ? '' : selected,
				}, function( response ) {
						var data = JSON.parse( response );

						if( ! data.error ) {

							ConvertPlugServices._updateErrorMsg( '' );
							nextBtn.removeClass( 'cp-disable' );
							selectAccountFrm.find( '.cp-api-selection-list' ).html( data.html );

							if ( accountLists.length > 0 ) {
								var accountChecked = accountLists.find('input:checked');
								
								if ( accountChecked.length > 0 ) {
									accountChecked.attr('data-selected-account', 'active');
								}
							}

							// If only single account is available, keep it checked
							if ( accountLists.length == 1 ) {
								accountLists.find('input').attr( 'checked', 'checked' );
							}

						} else {
							if( data.account_count == 0 ) {
								ConvertPlugServices._addAccount();
								existingAccountBtn.hide();
							} else {
								nextBtn.addClass( 'cp-disable' );
								selectAccountFrm.find( '.cp-api-selection-list' ).html('');
							}
							ConvertPlugServices._updateErrorMsg( data.error );
						}
				} );

			} else {
				// Handle steps classes
				ConvertPlugServices._updateSteps( 2 );

				// Show list form and hide selection form
				listAccountFrm.show();
				selectAccountFrm.hide();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();

				// Show only Add new account btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.show();
				saveBtn.hide();
				authenticateBtn.hide();
			}

			//ConvertPlugServices._connectionChanged();
			ConvertPlugServices._finishSettingsLoading();
			return;
		},

		_nextStepButton: function () {

			if ( nextBtn.hasClass( 'cp-disable' ) ) {
				return;
			}
			
			ConvertPlugServices._updateErrorMsg( '' );

			( currentStepIndex == 1 ) ?	ConvertPlugServices._connectionChanged() : ConvertPlugServices._cpGenerateFieldsMapping();
			
			return;
		},

		_saveMeta: function() {

			if ( saveBtn.hasClass( 'cp-disable' ) ) {
				return;
			}

			ConvertPlugServices._startSettingsLoading();

			ConvertPlugServicesTrigger.removeHook( 'cp-service-getlist-click' );
			ConvertPlugServicesTrigger.removeHook( 'cp-service-list-validate' );

			var currURL = window.location.href,
				newURL = '',
				save_now = ConvertPlugServices._getParameterByName( 'open_connects' );
				
			if( save_now == 'true' ) {
				newURL = currURL.replace( "&open_connects=true", "" );
				setTimeout( function() { history.pushState({}, null, newURL); }, 200 );
			}

			if( noMapping == true ) {

				var cp_connection_values = JSON.stringify( listAccountFrm.serializeArray() );
				$( 'input[name=cp_connection_values]' ).val( cp_connection_values );

				$( 'select[name=cp_select_account]' ).addClass( 'cp-hidden' );

				ConvertPlugServices._saveInSync();

			} else {

				var cp_mapping = JSON.stringify( mappingFrm.serializeArray() ),
					cp_taxonomy = JSON.stringify( listAccountFrm.serializeArray() );


				$( '#connect input[name=cp_mapping]' ).val( cp_mapping );
				$( '#connect input[name=cp_connect_settings]' ).val( cp_taxonomy );

				// Save the values for connect against the style_id
				ConvertPlugServices.ajaxCall( {
					action  : 'cppro_save_meta_settings',
					style_id : style_id,
					cp_mapping : cp_mapping,
					cp_taxonomy : cp_taxonomy
				}, ConvertPlugServices._metaSavedComplete );
			}
		},

		_saveInSync: function() {

			var account_name = listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).val(),
				service_name = listAccountFrm.find( 'input[name=cp-integration-service]' ).val(),
				img_obj = $( '.cp-insync-content-wrap .cp_connection_row' ).find( '.cp-connect-integration-meta .cp-meta-wrap img' ),
				new_url = '',
				account_title = listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).data('account-title');

			if( service_name == 'mailpoet' || service_name == 'mymail' ) {
				account_title = ( service_name == 'mailpoet' ) ? 'MailPoet' : 'MyMail';
			}

			new_url = cp_services.image_base_url + service_name + '.png';

			img_obj.attr( 'src', new_url );

			$( '.cp-insync-content-wrap .cp_connection_row .cp-connect-integration-meta' ).removeClass( 'cp-hidden' );
			$( '.cp-insync-content-wrap .cp_connection_row .cp-connect-integration-wrap' ).addClass( 'cp-hidden' );
			$( '.cp-insync-content-wrap .cp_connection_row' ).find( '.cp-active-title' ).html( account_title );

			$( '.cp-insync-content-wrap .cp_connection_row .cp-change-account' ).attr( 'data-account', account_name );
			$( '.cp-insync-content-wrap .cp_connection_row .cp-change-account' ).attr( 'data-service', service_name );
			$( '.cp-insync-content-wrap .cp_connection_row .cp-change-account' ).attr( 'data-service-title', service_name );
			$( '.cp-insync-content-wrap .cp_connection_row .cp-disconnect-account' ).attr( 'data-account', account_name );

			ConvertPlugServices._finishSettingsLoading();
			$( '.cp-md-close' ).trigger( 'click' );
		},

		_metaSavedComplete: function( response ) {

			var data = JSON.parse( response );

			if( ! data.error ) {

				var account_name = listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).val(),
					service_name = listAccountFrm.find( 'input[name=cp-integration-service]' ).val(),
					img_obj = $( '#connect' ).find( '.cp-connect-integration-meta .cp-meta-wrap img' ),
					new_url = '',
					account_title = listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).data('account-title');

				if( account_title == "undefined" ) {

				}

				if( service_name == 'mailpoet' || service_name == 'mymail' ) {
					account_title = ( service_name == 'mailpoet' ) ? 'MailPoet' : 'MyMail';
				}

				new_url = cp_services.image_base_url + service_name + '.png';

				img_obj.attr( 'src', new_url );

				$( '#connect .cp-connect-integration-meta' ).removeClass( 'cp-hidden' );
				$( '#connect .cp-connect-integration-wrap' ).addClass( 'cp-hidden' );
				$( '#connect' ).find( '.cp-active-title' ).html( account_title );

				$( '#connect .cp-change-account' ).data( 'account', account_name );
				$( '#connect .cp-change-account' ).data( 'service', service_name );
				$( '#connect .cp-change-account' ).data( 'service-title', service_name );
				$( '#connect .cp-remove-account' ).data( 'account', account_name );

				ConvertPlugServices._finishSettingsLoading();
				$( '.cp-md-close' ).trigger( 'click' );

			} else {
				ConvertPlugServices._finishSettingsLoading();
				ConvertPlugServices._updateErrorMsg( data.error );
			}
			$('#connect').scrollTop(0);

		},

		_mappingFieldChange: function() {
			var val 		= $( this ).val(),
				inputField 	= $( this ).closest( 'tr' ).find( 'input[type=text]' );

			( val == 'custom_field' ) ? inputField.show() : inputField.hide();

		},

		/**
		 * AJAX callback for when the service is saved.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_serviceSaveComplete: function( response ) {
			var data = JSON.parse( response );

			if( ! data.error ) {
				
				ConvertPlugServices._startSettingsLoading();
				
				listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).val( data.term_id );

				listAccountFrm.find( 'input[name=cp-integration-service]' ).val( currentService );

				// Form show/hide
				selectAccountFrm.hide();
				listAccountFrm.show();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();

				// Show only Next/Back btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.show();
				saveBtn.hide();
				authenticateBtn.hide();
				ConvertPlugServices._updateSteps( 2 );
				ConvertPlugServices._getLists( data.term_id );
				
			} else {
				ConvertPlugServices._finishSettingsLoading();
				ConvertPlugServices._updateErrorMsg( data.error );
			}
		},

		_getLists: function( account ) {

			listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).val( account );

			// Gets lists of the account
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_fields',
				account : account,
				isEdit	: isEdit,
				style_id : style_id,
				noMapping: noMapping,
				src: insyncSource
			}, ConvertPlugServices._serviceFieldsComplete );
		},

		_authenticateCredentials: function() {

			ConvertPlugServices._updateErrorMsg( '' );
			var data = ConvertPlugServices.serializeFormJSON( newAccountFrm );

			ConvertPlugServices._startSettingsLoading();
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_connect_service',
				service : currentService,
				fields 	: data,
				currentUrl : window.location.href
			}, ConvertPlugServices._authenticateCredentialsComplete );
		},

		_authenticateCredentialsComplete: function( response ) {

			var data = JSON.parse( response ),
				serviceData = ConvertPlugServices.serializeFormJSON( newAccountFrm );
				account_title = newAccountFrm.find( 'input[name=service_account]' ).val();

			if( ! data.error ) {

				listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).data( 'account-title', account_title );

				// Handle steps classes
				nextBtn.removeClass( 'cp-disable' );

				if( currentService == 'mautic' ) {
					if( 'form' == newAccountFrm.find( '.cp-api-fields select[name=is_form]' ).val() ) {
						// Authenticated without errors,
						// Save the account
						ConvertPlugServices.ajaxCall( {
							action  	: 'cppro_save_service_settings',
							serviceData : serviceData,
							service : currentService
						}, ConvertPlugServices._serviceSaveComplete );

					} else {
						var url = data.data.redirect_url;
						window.location = url;
					}
				} else if(  currentService == 'verticalresponse'  ) {
					var url = data.data.url;
					window.location = url;
				} else {
					// Authenticated without errors,
					// Save the account
					ConvertPlugServices.ajaxCall( {
						action  	: 'cppro_save_service_settings',
						serviceData : serviceData,
						service : currentService
					}, ConvertPlugServices._serviceSaveComplete );
				}

			} else {
				ConvertPlugServices._finishSettingsLoading();
				ConvertPlugServices._updateErrorMsg( data.error );
				return false;
			}
		},

		_addAccount: function() {

			ConvertPlugServices._updateErrorMsg( '' );
			
			// Show Auth & Use Existing btn
			authenticateBtn.show();
			existingAccountBtn.show();
			nextBtn.hide();
			newAccountBtn.hide();
			saveBtn.hide();
			newAccountBtn.hide();

			// Form show/hide
			selectAccountFrm.hide();
			listAccountFrm.hide();
			newAccountFrm.show();
			whr_to_find.css( 'visibility', 'visible' );
			mappingFrm.hide();
		},

		_useExistingAccount: function() {
			
			// Loader loads here.
			ConvertPlugServices._startSettingsLoading();
			
			// Form show/hide
			selectAccountFrm.show();
			listAccountFrm.hide();
			newAccountFrm.hide();
			whr_to_find.css( 'visibility', 'hidden' );
			mappingFrm.hide();

			// Show only Add new account btn
			newAccountBtn.show();
			existingAccountBtn.hide();
			nextBtn.show();
			backBtn.hide();
			saveBtn.hide();
			authenticateBtn.hide();

			// Handle steps classes
			ConvertPlugServices._updateSteps( 1 );
			ConvertPlugServices._updateErrorMsg( '' );
			ConvertPlugServices._finishSettingsLoading();
		},

		_serviceSelected: function() {
			
			ConvertPlugServices._updateErrorMsg( '' );
			
			// Loader loads here.
			ConvertPlugServices._startSettingsLoading();
			isEdit = false;

			// Handle steps classes
			ConvertPlugServices._updateSteps( 1 );

			
			var service = $( this ).find( '.cp-connect-service-list' ).data( 'service' ),
				serviceTitle = $( this ).find( '.cp-services-title' ).data( 'title' ),
				imgSrc = $( this ).find( 'img' ).attr( 'src' );

			modal.find( '.cp-md-modal-header img' ).attr( 'src', imgSrc );
			modal.find('.cp-md-modal-title').text( serviceTitle + ' Integration Setup' );

			// Check if service is selected
			if ( -1 == service || undefined == service ) {
				ConvertPlugServices._updateErrorMsg( cp_services.wrong );
				return;
			}

			currentService = service;

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_get_assets_data',
				service : service
			}, ConvertPlugServices._assetsDataComplete );

			listAccountFrm.find( 'input[name=cp-integration-service]' ).val( currentService );

			if( service == 'mailpoet' || service == 'mymail' ) {
				// Form show/hide
				selectAccountFrm.hide();
				listAccountFrm.show();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();

				// Show only Next/Back btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.hide();
				saveBtn.hide();
				authenticateBtn.hide();

				ConvertPlugServices._getLists( service );
				return;
			}

			// Form show/hide
			selectAccountFrm.show();
			nextBtn.show();
			listAccountFrm.hide();
			newAccountFrm.hide();
			whr_to_find.css( 'visibility', 'hidden' );
			mappingFrm.hide();

			// Show only Add new account btn
			newAccountBtn.show();
			existingAccountBtn.hide();
			backBtn.hide();
			saveBtn.hide();
			authenticateBtn.hide();

			// Renders all accounts associated with the service
			// Say all mailchimp accounts
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_accounts',
				service : service,
				selected: '',
			}, ConvertPlugServices._serviceSelectedComplete );

			// As per service - Renders input credentials field for service
			// In case of Mailchimp - API Key Input field is rendered
			ConvertPlugServices.ajaxCall( {
				action  : 'cppro_render_service_settings',
				service : service
			}, ConvertPlugServices._serviceInputFieldsComplete );
		},

		_assetsDataComplete: function( response ) {
			var data = JSON.parse( response );
			
			if ( data.error == false ) {

				var html      = $( '<div>' + data.assets + '</div>' ),
					link      = html.find( 'link.cp-mailer-' + currentService + '-css' ),
					script    = html.find( 'script.cp-mailer-' + currentService + '-js' );

				if ( script.length > 0 ) {
					$( 'script.cp-mailer-' + currentService + '-js' ).remove();
					$( 'html' ).append(script);
				}
				
				if ( link.length > 0 ) {
					$( 'script.cp-mailer-' + currentService + '-css' ).remove();
					$( 'head' ).append(link);
				}
			}
		},

		_serviceSelectedComplete: function( response ) {

			var data = JSON.parse( response );
			$( '.cp-md-info-wrap a' ).attr( 'href', data.url );

			if( ! data.error ) {

				ConvertPlugServices._updateErrorMsg( '' );
				nextBtn.removeClass( 'cp-disable' );
				selectAccountFrm.find( '.cp-api-selection-list' ).html( data.html );

				var accountLists = $('.cp-customizer-radio' );

				if ( accountLists.length > 0 ) {
					var accountChecked = accountLists.find('input:checked');
					
					if ( accountChecked.length > 0 ) {
						accountChecked.attr('data-selected-account', 'active');
					}
				}

				// If only single account is available, keep it checked
				if ( accountLists.length == 1 ) {
					accountLists.find('input').attr( 'checked', 'checked' );
				}

			} else {
				if( data.account_count == 0 ) {
					ConvertPlugServices._addAccount();
					existingAccountBtn.hide();
				} else {
					nextBtn.addClass( 'cp-disable' );
					selectAccountFrm.find( '.cp-api-selection-list' ).html('');
				}
			}

			ConvertPlugServices._finishSettingsLoading();
		},

		/**
		 * Connection changed
		 *
		 * @return true/false
		 * @since 1.0.0
		 */
		_connectionChanged: function() {

			// Remove success message container
			mappingFrm.find( '.cp-success-msg' ).remove();
			
			// Loader loads here.
			ConvertPlugServices._startSettingsLoading();
			
			var account = $('.cp-api-integration-form').find('.cp-customizer-radio input:checked'),
				account_title = $('.cp-api-integration-form').find('.cp-customizer-radio input:checked').data( 'account-name' );
			
			if ( account.length < 1 ) {
				
				ConvertPlugServices._updateErrorMsg( cp_services.select_account );
				ConvertPlugServices._finishSettingsLoading();
				return;
			}

			var accountName = account.val();

			listAccountFrm.find( 'input[name=cp-integration-account-slug]' ).data( 'account-title', account_title );

			if( isEdit ) {

				if( noMapping ) {
					
					var old_account = $( '.cp-save-' + insyncSource + '-form .cp-edit-account' ).data( 'account' );

					if( old_account != accountName ) {
						isEdit = false;
					} else {
						isEdit = true;
					}
				} else {
					var old_account = $( '#connect .cp-change-account' ).data( 'account' );

					if( old_account != accountName ) {
						isEdit = false;
					} else {
						isEdit = true;
					}
				}
			}

			ConvertPlugServices._getLists( accountName );
		},

		_tooltipInit: function() {
			
			$('.bsf-has-tip, .has-tip').each(function(i,tip){
	            $tip = $(tip);
	            var attribute   = (typeof $tip.attr('data-attribute') != 'undefined') ? $tip.attr('data-attribute') : 'title';
	            var offset      = (typeof $tip.attr('data-offset') != 'undefined') ? $tip.attr('data-offset') : 10;
	            var position    = (typeof $tip.attr('data-position') != 'undefined') ? $tip.attr('data-position') : 'top';
	            var trigger     = (typeof $tip.attr('data-trigger') != 'undefined') ? $tip.attr('data-trigger') : 'hover,focus';
	            var className   = (typeof $tip.attr('data-classes') != 'undefined') ? 'tip '+$tip.attr('data-classes') : 'tip';
	            $tip.frosty({
	                className : className,
	                attribute: attribute,
	                offset: offset,
	                position: position,
	                trigger: trigger
	            });
	        });
		},

		/**
		 * AJAX callback for when the service is saved.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_serviceFieldsComplete: function( response ) {
			var data        = JSON.parse( response ),
				wrap        = listAccountFrm.find( '.cp-new-account-fields' );

			if( ! data.error ) {

				ConvertPlugServices._tooltipInit();
				ConvertPlugServicesTrigger.triggerHook( 'cp-service-getlist-click', ConvertPlugServices );

				ConvertPlugServices._updateErrorMsg( '' );

				// Handle steps classes
				ConvertPlugServices._updateSteps( 2 );

				// Show list form and hide selection form
				listAccountFrm.show();
				selectAccountFrm.hide();
				newAccountFrm.hide();
				whr_to_find.css( 'visibility', 'hidden' );
				mappingFrm.hide();
				// Show only Add new account btn
				newAccountBtn.hide();
				existingAccountBtn.hide();
				nextBtn.show();
				backBtn.show();
				saveBtn.hide();
				authenticateBtn.hide();

				wrap.html( data.html );
				mapping_fields = data.mapping_fields;

				if( currentService == 'mailpoet' || currentService == 'mymail' ) {
					backBtn.hide();
				}

				if( noMapping ) {
					saveBtn.show();
					nextBtn.hide();
				}

			} else {
				ConvertPlugServices._updateErrorMsg( data.error );
			}

			ConvertPlugServices._finishSettingsLoading();
		},

		_cpGenerateFieldsMapping: function() {

			// Remove success message container
			mappingFrm.find( '.cp-success-msg' ).remove();

			var j = 0,
				k = 0;

			isEmailFieldAdded = false;

			ConvertPlugServicesTrigger.triggerHook( 'cp-service-list-validate', ConvertPlugServices );

			if( isValidated ) {
				ConvertPlugServices._updateErrorMsg( isValidated );
				return;
			}		


			// Form show/hide
			selectAccountFrm.hide();
			listAccountFrm.hide();
			newAccountFrm.hide();
			whr_to_find.css( 'visibility', 'hidden' );
			mappingFrm.show();

			// Show only Save btn
			newAccountBtn.hide();
			existingAccountBtn.hide();
			nextBtn.hide();
			backBtn.show();
			saveBtn.show();
			authenticateBtn.hide();

			if( currentService == 'mailpoet' || currentService == 'mymail' ) {
				backBtn.hide();
			}

			mapping = new Array();
			custom_field = new Array();

			$( '.cp-form-input-field' ).each( function() {

				if ( $( this ).hasClass( 'cp-email' ) ) {
					isEmailFieldAdded = true;
				}
				var field 		= $( this ),
					nameAttr	= field.attr( 'name' ),
					lowerCase	= nameAttr.replace( /[^a-z0-9\s]/gi , '' ).toLowerCase(),
					isCustom	= true;

				for ( i = 0; i < mapping_fields.length; i++ ) {

					if( mapping_fields[i].toLowerCase() == lowerCase ) {

						mapping[k] = { 'name' : mapping_fields[i], 'value' : $( this ) };
						isCustom = false;
						k++;
					}
				}

				if( isCustom ) {

					if( lowerCase != 'paramemail' ) {
						custom_field[j] = { 'name' : 'custom_field', 'value' : $( this ) };
						j++;
					}
				}
				
			} );

			ConvertPlugServices._cpAllocateFieldsMapping();
			// Handle steps classes
			ConvertPlugServices._updateSteps( 3 );
		},

		_updateErrorMsg: function( err ) {

			errorWrap.html( err );
			( err != '' ) ?	errorWrap.addClass( 'cp-response-failure' ) : errorWrap.removeClass( 'cp-response-failure' );
		},

		_cpAllocateFieldsMapping: function() {

			var optionsStr	= '',
				totalCount 	= '',
				wrap 		= mappingFrm.find( '.cp-mapping-fields' ),
				frmTable 	= wrap.find( 'table tbody' );

			frmTable.html( '' );

			saveBtn.removeClass( 'cp-disable' );

			if( mapping.length > 0 || custom_field.length > 0 ) {

				frmTable.append('<tr><th>' + cp_services.cp_fields + '</th><th>' + cp_services.mailer_fields + '</th></tr>');

				for ( i = 0; i < mapping.length; i++ ) {
					frmTable.append( $('<tr>') );

					var nameAttr = mapping[i].value.attr( 'name' ),
						inputLabel = '<label>' + mapping[i].value.attr( 'data-placeholder' ) + '</label>',
						opt = '',
						trSelector	= frmTable.find( 'tr:eq(' + ( i + 1 )  + ')' );
					
					optionsStr = ConvertPlugServices._getMappingOptionsHTML( nameAttr );
						
					trSelector.append( $('<td>').append( inputLabel ) );
					trSelector.append( $('<td>').append( $( '<select>' ).attr( 'name', 'cp_mapping{' + nameAttr + '}' ).append( $( optionsStr ) ) ) );
					trSelector.append( $('<td>').append(
							$( '<input>', {
						        type: 'text',
						        val: '',
						        placeholder: cp_services.placeholder,
						        name: 'cp_mapping{' + nameAttr + '}{input}'
						    } )
						) );

				}

				totalCount = ( mapping.length + custom_field.length );

				for ( k = 0, j = i; j < totalCount; j++, k++ ) {

					frmTable.append( $('<tr>') );

					var nameAttr	= custom_field[k].value.attr( 'name' ),
						inputLabel = '<label>' + custom_field[k].value.attr( 'data-placeholder' ) + '</label>',
						trSelector	= frmTable.find( 'tr:eq(' + ( j + 1 ) + ')' );

					optionsStr = ConvertPlugServices._getMappingOptionsHTML( '' );

					trSelector.append( $('<td>').append( inputLabel ) );
					trSelector.append( $('<td>').append( $( '<select>' ).attr( 'name', 'cp_mapping{' + nameAttr + '}' ).append( $( optionsStr ) ) ) );
					trSelector.append( $('<td>').append(
							$( '<input>', {
						        type: 'text',
						        val: '',
						        class: '',
						        id: '',
						        name: 'cp_mapping{' + nameAttr + '}{input}'
						    } )
						) );

				}

				if( isEdit ) {
				
					var existingMapping = JSON.parse( $('#connect input[name=cp_mapping]').val() );

					for( m = 0; m < existingMapping.length; m++ ) {

						frmTable.find( "select[name='" + existingMapping[m].name + "']" ).val( existingMapping[m].value );

						frmTable.find( "input[name='" + existingMapping[m].name + "']" ).val( existingMapping[m].value );

						if( existingMapping[m].value == 'custom_field' ) {
							frmTable.find( "input[name='" + existingMapping[m].name + "{input}']" ).show();
						} else {
							frmTable.find( "input[name='" + existingMapping[m].name + "{input}']" ).hide();
						}

					}
				}

				if( ! isEmailFieldAdded ) {
					ConvertPlugServices._updateErrorMsg( cp_services.no_email );
				}

			} else {

				if ( $( '.cp-form-input-field' ).length == 1 ) {
					
					if( $( '.cp-form-input-field' ).hasClass( 'cp-email' ) ) {

						// Append success message after mapping field container
						mappingFrm.find( '.cp-mapping-fields' ).after(
						 	$('<div/>')
						    	.addClass("cp-success-msg")
						    	.append("<span/>")
						    	.text(cp_services.only_email)
						);

						saveBtn.removeClass( 'cp-disable' );

					} else {
						ConvertPlugServices._updateErrorMsg( cp_services.no_email );
						saveBtn.removeClass( 'cp-disable' );
					}

				} else {
					ConvertPlugServices._updateErrorMsg( cp_services.no_input );
					saveBtn.addClass( 'cp-disable' );
				}
			}

			if( currentService == 'convertkit' ) {
				var cust_opt = $( '.cp-mapping-fields select option[value=custom_field]' );
				if( cust_opt.length > 0 ) {
					cust_opt.remove();
				}
			}

		},

		_getMappingOptionsHTML: function( nameAttr ) {

			var optionsStr 	= '',
				x			= 0,
				selected 	= '';

			if( typeof nameAttr != 'undefined' ) {
				optionsStr += '<option value="-1">' + cp_services.select_option + '</option>';

				for ( x = 0; x < mapping_fields.length; x++ ) {
					selected = ( nameAttr.toLowerCase() == mapping_fields[x].toLowerCase() ) ? ' selected="selected" ' : '';

					optionsStr += '<option value="' + mapping_fields[x] + '" ' + selected + '>' + mapping_fields[x] + '</option>';
				}

				optionsStr += '<option value="custom_field">' + cp_services.custom_field + '</option>';
			}

			return optionsStr;

		},

		/**
		 * Serializes Form data to JSON.
		 *
		 * @return {Object}
		 * @since 1.0.0
		 */
		serializeFormJSON: function ( form ) {

	        var o = {};
	        var a = form.serializeArray();
	        $.each(a, function () {
	            if (o[this.name]) {
	                if (!o[this.name].push) {
	                    o[this.name] = [o[this.name]];
	                }
	                o[this.name].push(this.value || '');
	            } else {
	                o[this.name] = this.value || '';
	            }
	        });
	        return o;
	    },

		/**
		 * AJAX call to services.
		 *
		 * @param {Object} args Arguments to AJAX call.
		 * @param func: Callback function name.
		 * @return void
		 * @since 1.0.0
		 */
		ajaxCall: function( args, func ) {

			$.ajax( {
				data: args,
				action: args.action,
				url: cp_services.url,
				success: func,
				method: 'post',
				success  : func
			});

		},
		
		/**
		 * Show the lightbox loading graphic and remove errors.
		 *
		 * @param {Object} ele An element within the lightbox.
		 * @return void
		 * @since 1.0.0
		 */
		_startSettingsLoading: function( ele )
		{
			loader.removeClass('cp-hidden');
		},
		
		/**
		 * Remove the lightbox loading graphic.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		_finishSettingsLoading: function()
		{
			loader.addClass('cp-modal-loaded');
			setTimeout(function() {
				loader.addClass('cp-hidden');
				loader.removeClass('cp-modal-loaded');
			}, 300);
		},
		
		/**
		 * AJAX callback for when the service select changes.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 1.0.0
		 */
		_serviceInputFieldsComplete: function( response )
		{
			var data = JSON.parse( response ),
				wrap = newAccountFrm.find( '.cp-new-account-fields' );

			if( !data.error ) {
				wrap.html( data.html );
			} else {
				ConvertPlugServices._updateErrorMsg( data.error );
			}
		},

	};

	$ ( function() {
		ConvertPlugServices.init();
	});

})( jQuery );

(function($) {

	ConvertPlugServicesTrigger = {

		/**
		* Trigger a hook.
		*
		* @since 1.0.0
		* @method triggerHook
		* @param {String} hook The hook to trigger.
		* @param {Array} args An array of args to pass to the hook.
		*/
		triggerHook: function( hook, args ) {
			$( 'body' ).trigger( 'cp-services-trigger.' + hook, args );
		},

		/**
		* Add a hook.
		*
		* @since 1.0.0
		* @method addHook
		* @param {String} hook The hook to add.
		* @param {Function} callback A function to call when the hook is triggered.
		*/
		addHook: function( hook, callback ) {
			$( 'body' ).on( 'cp-services-trigger.' + hook, callback );
		},

		/**
		* Remove a hook.
		*
		* @since 1.0.0
		* @method removeHook
		* @param {String} hook The hook to remove.
		* @param {Function} callback The callback function to remove.
		*/
		removeHook: function( hook ) {
			$( 'body' ).off( 'cp-services-trigger.' + hook );
		},
	};

})(jQuery);