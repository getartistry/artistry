(function ( $, window, undefined ) {

	var is_form_validated = true;

	$(window).ready(function(){

		var styleId = $('.cpro-form input[name="style_id"]').val();
		styleIdSelctor = 'cp_popup_style_'+styleId;

		/* Field Action on Click */
		$(document).on('click', '.cp-target.cp-button-field', function() {
			var self = $(this),
			 action  = self.closest(".cp-field-html-data").data('action');
			$('.cp-current-clicked-btn').removeClass('cp-current-clicked-btn');
			self.closest(".cp-field-html-data").addClass('cp-current-clicked-btn');
		});

		/* Field Action on Click */
		$(document).on('click', '.cp-field-html-data.cp-shapes-wrap', function() {
			var self = $(this),
			 action  = self.closest(".cp-field-html-data").data('action');

			if ( action == 'submit' || action ==  'submit_n_close' || action ==  'submit_n_goto_step' || action == 'submit_n_goto_url' ) {
			 	if( $('.cp_shape_submit_hidden').length == 0) {
			 		self.find('.cp_shape_submit_label').append('<input type="submit" class="cp_shape_submit_hidden">');
			 	}
			}

			$('.cp-current-clicked-shape').removeClass('cp-current-clicked-shape');
			self.closest(".cp-field-html-data").addClass('cp-current-clicked-shape');
		});

		//handled form submission
		jQuery( document ).on( "submit", "form.cpro-form" , function(e) {
			e.preventDefault();
			var is_success = false;
			var invalid_email = false;
			var thisForm = jQuery( this );
			jQuery(document).trigger( "cp_before_form_submit", [jQuery(this)] );
			
			var checkboxFlag = true;

			jQuery( this ).find( '.cpro-checkbox-required' ).each( function( index, elem ) {
				var checkThis = jQuery(this);
				setTimeout(function() { checkThis.find("input[type=checkbox]").removeAttr( 'required' ); }, 2000);
				var checked = jQuery(this).find("input[type=checkbox]:checked").length;
				if( checked == 0 ) {
					jQuery(this).find("input[type=checkbox]:first").attr( 'required', 'required' );
					thisForm[0].reportValidity();
					checkboxFlag = false;
					return false;
				}
			} );
			
			if( checkboxFlag ) {
				is_form_validated = true;
			} else {
				is_form_validated = false;
			}

			if( is_form_validated ) {

				var form         = jQuery(this),
					style_id     = form.closest( ".cp-popup-container" ).data("style").replace( "cp_style_", "" ),
					style_slug   = form.closest( ".cp-popup-container" ).data("styleslug"),
					btn_old_text      = '';

				var	data 				= form.serialize(),
					form_container  	= form.closest(".cpro-form-container");
					redirectdata 		= form.data("redirect-lead-data");
					currentBtn 			= form.find('.cp-current-clicked-btn'),
					currentShape		= form.find('.cp-current-clicked-shape'),
					currentBtnAction	= currentBtn.closest('.cp-field-html-data').data('action'),
					success_message		= currentBtn.find('.cp-button-field').data('success-message'),
					currentShpAction	= currentShape.closest('.cp-field-html-data').data('action'),
					success_message_shp	= currentShape.data('success-message'),
					loader_style        = 'loader_1',
					currObj 			= '',
					btn_old_text        = currentBtn.find('.cp-button-field').html(),
					shape_old_text      = currentShape.html();

				var button_field = currentBtn.find('.cp-button-field');

				if( currentBtn.attr( 'data-type' ) == 'cp_button' || currentBtn.attr( 'data-type' ) == 'cp_gradient_button' ) {

					currObj = currentBtn;
					
					button_field.removeClass('cp-tooltip cp-tooltip-top cp-tooltip-bottom cp-loader');
					button_field.find('.cp_loader_container').removeClass('cp_success_loader_container');

					// Loader Button
					if( !currentBtn.hasClass( "cp-state-loading" ) && !currentBtn.hasClass( "cp-state-success" ) && !currentBtn.hasClass( "cp-state-error" ) ) {

						currentBtn.addClass("cp-state-loading");
						button_field.addClass('cp-loader cp-button-loading');
						currentBtn.find('.cp-loader').css('text-align', 'center');

						button_field.html("<div class='cp_loader_container'><i class='cp-button-loader-style draw " + loader_style + "'></i></div>");

						if( currentBtnAction == 'submit_n_close' || currentBtnAction == 'submit' || currentBtnAction == 'submit_n_goto_url') {
							button_field.addClass('cp-button-tooltip');
						}
					}
				}

				if( currentShape.attr( 'data-type' ) == 'cp_shape' ) {

					currObj = currentShape;
					
					currentShape.find('.cp-shape-container').removeClass('cp-tooltip-top cp-tooltip-bottom');
					currentShape.removeClass('cp-shape-submit-loading cp-state-success cp-error-tooltip');
					if( !currentShape.hasClass( "cp-state-loading" ) && !currentShape.hasClass( "cp-state-success" ) && !currentShape.hasClass( "cp-state-error" ) ) {
						
						currentShape.addClass('cp-shape-submit-loading');

						if( currentShpAction == 'submit_n_close' || currentShpAction == 'submit' || currentShpAction == 'submit_n_goto_url' ) {
							currentShape.find('.cp-shape-submit-loading').addClass('cp-shapes-tooltip');
						}
					}
				}

				if( typeof cp_ajax.ajax_nonce !== 'undefined' ) {
					data += '&_nonce='+  cp_ajax.ajax_nonce;
				}

				close_span = '<span class="cp-tooltip-close">&times;</span>';

				jQuery.ajax({
					url: cp_ajax.url,
					data: data,
					type: 'POST',
					dataType: 'json',
					success: function( response ) {

						// console.log( response );

						var id      = currentBtn.closest(".cp-popup-wrapper").find('input[name=style_id]').val();
						var modal   = $( '.cpro-onload[data-class-id=' + id + ']' );
						var button_field = currentBtn.find('.cp-button-field');

						button_field.find('.cp_loader_container').addClass('cp_success_loader_container');
						button_field.find('.cp-button-loader-style').removeClass(loader_style).addClass('success-loader-style');
						result = response.data;
						error_msg = cp_ajax.not_connected_to_mailer;

						if ( currentBtn.find('.cp-target').hasClass('cp-button-field') ) {

							var curPos = currentBtn.find('.cp-button-field').offset().top - $( window ).scrollTop();
							if( curPos > 90 ) {
							currentBtn.find('.cp-button-field').addClass('cp-tooltip-top');
							} else {
								currentBtn.find('.cp-button-field').addClass('cp-tooltip-bottom');
							} 
							currentBtn.removeClass('cp-state-loading').addClass('cp-state-success').attr( 'style', 'z-index: 999 !important' );

						} else if( currentShape.hasClass('cp-shapes-wrap') ) {

							var curPos = currentShape.offset().top - $( window ).scrollTop();
							if( curPos > 90 ) {
								currentShape.find('.cp-shape-container').addClass('cp-tooltip-top');
							} else {
								currentShape.find('.cp-shape-container').addClass('cp-tooltip-bottom');
							}
							currentShape.removeClass('cp-state-loading').addClass('cp-state-success').attr( 'style', 'z-index: 999 !important' );
						}

						if( response === 0 ) {

							is_success = false;

							if( currentBtn.find('.cp-target').hasClass('cp-button') ) {
								currentBtn.removeClass('cp-current-clicked-btn').addClass('cp-error-tooltip');
								currentBtn.find( '.cp-btn-tooltip' ).html('<div class="cp-error-tip-content">' + error_msg + close_span + '</div>' );
							} else if ( currentShape.hasClass('cp-shapes-wrap') ) {	
								currentShape.removeClass('cp-current-clicked-shape').addClass('cp-error-tooltip');
								currentShape.find('.cp-shape-tooltip').html('<div class="cp-error-tip-content">' + error_msg + close_span + '</div>' );
							}

						} else {

							if( false != result.error ) {

								if( 'Invalid email address.' == result.error ) {
									error_msg = 'Invalid Email Address';
									invalid_email = true;
								}

								is_success = false;
								if( currentBtn.find('.cp-target').hasClass('cp-button') ) {

									currentBtn.removeClass('cp-current-clicked-btn').removeClass( 'cp-state-success' ).addClass('cp-error-tooltip');

									currentBtn.find( '.cp-button-field' ).find( '.cp_success_loader_container' ).remove(); 

									jQuery('<div/>', {
									    addClass: 'cp_loader_container cp_error_loader_container',
									}).appendTo( currentBtn.find( '.cp-button-field' ) );

									currentBtn.find( '.cp_error_loader_container' ).append( "<i class='dashicons-no-alt dashicons'></i>" );

									jQuery('<div/>', {
									    class: 'cp-error-tip-content',
									}).appendTo( currentBtn.find( '.cp-btn-tooltip' ) );

									currentBtn.find( '.cp-error-tip-content' ).append( error_msg + close_span );


								} else if ( currentShape.hasClass('cp-shapes-wrap') ) {

									currentShape.removeClass('cp-current-clicked-shape').removeClass( 'cp-state-success' ).addClass('cp-error-tooltip');
									currentShape.find('.cp-shape-tooltip').html('<div class="cp-error-tip-content">' + error_msg + close_span + '</div>' );
									currentShape.find('.cp-button-field').html("<div class='cp_loader_container cp_error_loader_container'><i class='dashicons-no-alt dashicons'></i></div>");
								}

							} else {

								is_success = true;
								if( result.error == false ) {
									if( currentBtn.find('.cp-target').hasClass('cp-button') ) {
										currentBtn.removeClass('cp-error-tooltip').addClass('cp-state-success');
										currentBtn.find('.cp-button-field').attr('data-content', success_message );
										currentBtn.find('.cp-button-field').attr('disabled', true);
										
										switch( currentBtnAction ) {

											case "submit_n_close": 
		        								setTimeout(function() {
													jQuery(document).trigger('closePopup',[modal,id]);
												}, 1200 );
											break;

											case "submit_n_goto_step":

												var step_number  = currentBtn.closest('.cp-field-html-data').data("step");
												var current_step = currentBtn.closest('.cp-popup-content').data("step");
												if( current_step != step_number ) {

													setTimeout(function() {
														cp_move_to_next_step( currentBtn, current_step, step_number );
													}, 1200 );
												}
											break;

											case "submit_n_goto_url":

												var redirect_url  = currentBtn.closest('.cp-field-html-data').data("redirect");
												var redirect_target = currentBtn.closest('.cp-field-html-data').data("redirect-target");
												var get_param = currentBtn.find( '.cp-target' ).data( "get-param" );
												var param = currentBtn.closest(".cpro-form").serializeArray();

												for ( param_index = 0; param_index < param.length; ++param_index ) {

													// Remove parameters with blank value
													if( '' == param[param_index].value || 
														'param[date]' == param[param_index].name || 
														'action' == param[param_index].name ||
														'style_id' == param[param_index].name ) {
														delete param[param_index];
													} else {

														var new_name = param[param_index].name.replace( 'param[', '' );
														new_name = new_name.substring( 0, new_name.length - 1 ); 
														param[param_index].name = new_name;
													}
												}

												// Remove empty paramters 
												var param = param.filter(function(v){ return v !== '' } );
												param = jQuery.param( param );

												if( true == get_param ) {
													var arr = redirect_url.split('?');
													if( arr.length == 1 ) {
													  	redirect_url = redirect_url + '?' + param
													} else {
														redirect_url = redirect_url + '&' + param
													}
												}

												setTimeout(function() {

													if( typeof redirect_target == 'undefined' || redirect_target == ''){
														redirect_target ='_self';
													}
													if( redirect_url !== '' ) {
														if( '_self' !== redirect_target ) {
															window.open( redirect_url, redirect_target );
														} else {
															window.location = redirect_url;
														}
														
														// close popup if target is new window
														if( '_blank' == redirect_target ) {
															jQuery(document).trigger( 'closePopup', [modal,id] );
														}
													}
												}, 1200 );
												
											break;
										}
									} else if ( currentShape.hasClass('cp-shapes-wrap') ) {

										currentShape.removeClass('cp-error-tooltip').removeClass('cp-shape-submit-loading').addClass('cp-state-success');
										currentShape.attr( 'style', 'z-index: 35 !important' );
										currentShape.find('.cp-shape-container').attr('data-content', success_message_shp );

										switch( currentShpAction ) {

											case "submit_n_close": 

												var id      = currentShape.closest(".cp-popup-wrapper").find('input[name=style_id]').val();
		        								var modal   = $( '.cpro-onload[data-class-id=' + id + ']' );

		        								setTimeout(function() {
													jQuery(document).trigger('closePopup',[modal,id]);
												}, 1200 );
											break;

											case "submit_n_goto_step":

												currentShape.find('.cp-shape-container').removeClass('cp-tooltip-top').removeClass('cp-tooltip-bottom');
												var step_number  = currentShape.closest('.cp-field-html-data').data("step");
												var current_step = currentShape.closest('.cp-popup-content').data("step");
												if( current_step != step_number ) {

													setTimeout(function() {
														cp_move_to_next_step( currentShape, current_step, step_number );
													}, 1200 );
												}
											break;

											case 'submit_n_goto_url':
												var redirect_url  = currentShape.closest('.cp-field-html-data').data("redirect");
												var redirect_target = currentShape.closest('.cp-field-html-data').data("redirect-target");

												var get_param = currentShape.find( '.cp-target' ).data( "get-param" );
												var param = currentShape.closest('.cpro-form').serializeArray();

												for ( param_index = 0; param_index < param.length; ++param_index ) {

													// Remove parameters with blank value
													if( '' == param[param_index].value || 
														'param[date]' == param[param_index].name || 
														'action' == param[param_index].name ||
														'style_id' == param[param_index].name ) {
														delete param[param_index];
													} else {

														var new_name = param[param_index].name.replace( 'param[', '' );
														new_name = new_name.substring( 0, new_name.length - 1 ); 
														param[param_index].name = new_name;
													}
												}

												// Remove empty paramters 
												var param = param.filter(function(v){ return v !== '' } );
												param = jQuery.param( param );

												if( true == get_param ) {
													var arr = redirect_url.split('?');
													if( arr.length == 1 ) {
													  	redirect_url = redirect_url + '?' + param
													} else {
														redirect_url = redirect_url + '&' + param
													}
												}

												setTimeout(function() {
													if( typeof redirect_target == 'undefined' || redirect_target == ''){
														redirect_target ='_self';
													}
													if( redirect_url !== '' ) {

														if( '_self' !== redirect_target ) {
															window.open( redirect_url, redirect_target );
														} else {
															window.location = redirect_url;
														}

														// close popup if target is new window
														if( '_blank' == redirect_target ) {
															jQuery(document).trigger( 'closePopup', [modal,id] );
														}
													}
												}, 1200 );
											break;
										}

									} else {
										if( currentBtn.find('.cp-target').hasClass('cp-button') ) {
											currentBtn.addClass('cp-error-tooltip');
											currentBtn.find('.cp-button-field').attr('data-content', result.error );	
										} else if ( currentShape.hasClass('cp-shapes-wrap') ) {
											currentShape.removeClass('cp-current-clicked-shape').addClass('cp-error-tooltip');
											currentShape.find('.cp-shape-container').attr('data-content', result.error );
										}						
									}
								}

								var convertPopupObj = new ConvertProPopup;
								convertPopupObj._setCookie( currObj );
							}
						}

						setTimeout(function() {
							if( invalid_email ) {

								if( currentBtn.length > 0 ) {
									currentBtn.find('.cp-button-field').html( btn_old_text );
									currentBtn.find('.cp-button-field').removeClass('cp-tooltip-top cp-tooltip-bottom');
									currentBtn.closest('.cp-field-html-data').find( '.cp-error-tip-content' ).remove();
								}

								if( currentShape.length > 0 ) {
									currentShape.find('.cp-shape-container').html( shape_old_text );
									currentShape.find('.cp-shape-container').removeClass('cp-tooltip-top cp-tooltip-bottom');

									currentShape.closest('.cp-field-html-data').find( '.cp-error-tip-content' ).remove();
								}
							}

						}, 1500 );

						setTimeout(function() {

							if ( currentBtn.find('.cp-target .cp_loader_container').hasClass('cp_success_loader_container') ) {
								currentBtn.find('.cp-button-field').removeClass('cp-tooltip-top cp-tooltip-bottom');
							} else if( currentShape.hasClass('cp-shapes-wrap') && !currentShape.hasClass( 'cp-error-tooltip' ) ){
								currentShape.find('.cp-shape-container').removeClass('cp-tooltip-top cp-tooltip-bottom');
							}
							
						}, 5000 );

						currentBtn.removeClass('cp-current-clicked-btn');
						currentShape.removeClass('cp-current-clicked-shape');

						if( ! invalid_email ) {
							jQuery(document).trigger( "cp_after_form_submit", [jQuery(this), response, style_slug] );
						}

					},
					error: function(data){
						is_success = false;
						currentBtn.find('.cp-button-field').attr('data-content', data );
			        }
				});
			}

			if( ! invalid_email ) {
				jQuery(document).trigger( "cp_after_submit_action", [jQuery(this), style_id, is_success] );
			}

			e.preventDefault();

		});

		$( document ).on( 'click', '.cp-tooltip-close', function( event ) {
				var $this       = $( this );
				var form 		= $this.closest( 'form.cpro-form' ),
				currentBtn 		= form.find('.cp-current-clicked-btn'),
				currentShape 	= form.find('.cp-current-clicked-shape');

				$this.closest( '.cp-error-tip-content' ).remove();
				form.find('.cp-button-field').removeClass('cp-button-tooltip');
				form.find('.cp-shape-container').removeClass('cp-tooltip-top')
		} );

		/* Button Scripts */
		$(document).on( 'click', '.cp-field-html-data', function() {

			var self = $(this),
				dataAction = self.data( 'action' ),
				formValidate = true;
			
			fieldActions( self, dataAction );

			if( jQuery(this).data("type") !== 'cp_button' && jQuery(this).data("type") !== 'cp_gradient_button' ) {
				self.removeClass('cp-current-clicked-btn');
			}

			if( jQuery(this).data("type") !== 'cp_shape' ) {
				self.removeClass('cp-current-clicked-shape');
			}
		});

		/**
		 * Button Actions
		 */
		function fieldActions( btn, dataAction ) {

			var current_step     = btn.closest('.cp-popup-content').data("step");
			var id               = btn.closest(".cp-popup-wrapper").find('input[name=style_id]').val();
            var modal            = $( '.cpro-onload[data-class-id=' + id + ']' );
            var count_conversion = btn.hasClass('cpro_count_conversion');

            $(document).trigger( 'cpro_before_field_actions', [btn, id] );

            // count button click as conversion
            if( count_conversion ) {

				var category = "Convert Pro";
			    var action   = 'conversion';
			    var label    =  btn.closest( ".cp-popup-container" ).data("styleslug");

			    if( 'function' === typeof cpCreateGoogleAnalyticEvent ) {
			    	cpCreateGoogleAnalyticEvent( category, action, label );
			    }

			    var convertPopupObj = new ConvertProPopup;

			    // Set conversion cookie
				convertPopupObj._setCookie( btn );
			}

			/* Button Actions without Submit */
			switch( dataAction ) {
				case 'close':
					$(document).trigger( 'closePopup', [modal,id] );
					break;

				case 'close_tab':
					window.top.close();
					break;

				case 'goto_step':

					var step_number  = btn.closest('.cp-field-html-data').data("step");
					if( current_step != step_number ) {
						var all_inputs = btn.closest('.cpro-form-container').find('input, select, textarea');

						btn.closest('.cpro-form-container').find( '.cpro-checkbox-required' ).each( function( index, elem ) {

							var checkThis = jQuery(this),
								checked = jQuery(this).find("input[type=checkbox]:checked").length;

							setTimeout( function( event ) {
								checkThis.find("input[type=checkbox]").removeAttr( 'required' );
								}, 2000 );

							if( checked == 0 ) {
								var firstcheckbox = jQuery(this).find("input[type=checkbox]:first");

								if( 'undefined' != typeof firstcheckbox ) {
									firstcheckbox.attr( 'required', 'required' );
									firstcheckbox[0].reportValidity();
									return false;
								}
							}
						} );

						if( all_inputs.length > 0 ) {
							var proceed_to_next_step = true;
							if( current_step < step_number ) {
								$.each( all_inputs, function( index, value ) {
									if( ! value.checkValidity() ) {
										proceed_to_next_step = false;
										value.reportValidity();
										return false;
									}
								} );
							}
							if( proceed_to_next_step ) {
								cp_move_to_next_step( btn, current_step, step_number );
							}
						} else {
							cp_move_to_next_step( btn, current_step, step_number );
						}
					}
					
				break;

				case "goto_url":
					var redirect_url = btn.data("redirect"),
						target 		 = btn.data("redirect-target");
					if( typeof target == 'undefined' || target == '' ) {
						target ='_self';
					}
					if( redirect_url !== '' ) {
						 window.open( redirect_url,target );
					}
				break;
			}
		}

		/**
		 * Move to Next Step Funtion
		 */
		function cp_move_to_next_step( obj, step_id, step_number ) {
			if( obj.closest( '.cp-popup' ).find( '.cp-popup-content.cp-panel-'+step_number ).length > 0 ) {
				obj.closest( '.cp-popup' ).find( '.cp-popup-content.cp-panel-'+step_id ).removeClass('cpro-active-step');
				obj.closest( '.cp-popup' ).find( '.cp-popup-content.cp-panel-'+step_number ).addClass('cpro-active-step');
			}
		}

	});

}(jQuery, window));