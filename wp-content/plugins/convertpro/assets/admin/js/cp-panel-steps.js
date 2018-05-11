( function( $ ) {
    
   
    var panel_wrapper  = $(".panel-wrapper");
	var anim_container = $(".cpro-animate-container");
	var steps_wrapper  = $('.cp-steps-wrapper');


    /**
     * JavaScript class for multistep functionality.
     *
     * @since 1.0.0
     */
    var ConvertProMultiStep = {

    	/**
         * Initializes the all class variables.
         *
         * @return void
         * @since 1.0.0
         */
        init: function( e ) {

        	$( document ).on( 'click', '.cp_step_button', this._switchStep );
        	$( document ).on( 'click', '#cp-clone-step', this._cloneStep );
        	$( document ).on( 'click', '#cp-delete-step', this._deleteStep );
        	$( document ).on( 'cpro_switch_panel', this._after_panel_switch );
        	$( document ).on( 'click', '.cp-multisteps-setting', this._displayStepPanel );

        	$(document).on( 'click', '.cp-steps-wrapper', function(e){
				$(document).trigger( 'cpro_close_edit_panel' );
			});

        	$( document ).ready( this._ready );
	
        },

        _ready: function() {

        	var stepCount   = $("#cp_step_count").val();
			var module_type = $("#cp_module_type").val();
			
			if( parseInt( stepCount ) > 1 ) {
				
				// Display step wrapper if multistep
				$(".cp-multisteps-setting").toggleClass( 'cp-active-link-color' );
				$('.cp-steps-wrapper').toggleClass('cp-hidden');
				if( 'info_bar' == module_type ) {
					$('.cp-steps-wrapper').addClass( 'cp-ifb-step-wrapper' );
				} else {
					$('.cp-steps-wrapper').removeClass( 'cp-ifb-step-wrapper' );
				}
			}
        },

        /**
         * Switch steps
         *
         * @return void
         * @since 1.0.0
         */
        _switchStep: function ( e ) {

        	$this = $(this);
			var button_siblings 	= $this.siblings('.cp_step_button');
			var old_step 			= $this.siblings('.cp_step_button.cp-active-step').attr("data-step");
			var current_step 		= parseInt( $this.attr("data-step") );
			var out_animation_class = 'cp-slideOutRight';
			var in_animation_class 	= 'cp-slideInLeft';

			if ( $this.hasClass('disable') || $this.hasClass('cp-active-step') ) {
				return
			}

			button_siblings.addClass('disable').removeClass('cp-active-step');
			$this.addClass("cp-active-step");

			if ( old_step < current_step ) {
				out_animation_class 	= 'cp-slideOutLeft';
				in_animation_class 	= 'cp-slideInRight';
			}
			
			anim_container.addClass("cp-step-animation");
			anim_container.addClass( out_animation_class );

			setTimeout(function() {
				anim_container.removeClass( out_animation_class );
				panel_wrapper.find(".panel").hide();	
				panel_wrapper.find("#panel-" + current_step).show().removeClass('cp-hidden');
				anim_container.addClass( in_animation_class );
			}, 850 );

			setTimeout(function() {
				anim_container.removeClass( in_animation_class );
			}, 1700 );

			/* Store current step */
			step_id = current_step - 1;

			$(document).trigger('cpro_init_drop_on_panel');

			// call init panel item
			ConvertProDragDrop._initPanelItemDrag();

			//change panel settings
			$(document).trigger( 'cpro_switch_panel', [ step_id + 1, false, false, true ] );

			//Remove selected class
			$(document).trigger( 'cpro_remove_selected' );
			
			button_siblings.removeClass('disable');

        },

        /**
         * Clone a particular step and switch to cloned step
         *
         * @return void
         * @since 1.0.0
         */
        _cloneStep: function ( e ) {

			// increment step count 
			step_count++;

			var panel_id = parseInt( $( ".panel-step-list span.cp-active-step").attr("data-step") );
		    var new_panel_id = step_count;
			var modal_data_obj = bmodel.get("panel_data");
			var panel_index = panel_id - 1;
			var new_panel_index = step_count - 1; 
			var temp_obj = $.extend({}, modal_data_obj );
			var panel_clone = $( ".panel-wrapper #panel-"+ panel_id ).clone().prop( 'id', "panel-"+ new_panel_id );
			var old_panel_class = "panel-"+ panel_id  + "-content-wrapper";
			var new_panel_class = "panel-" + new_panel_id + "-content-wrapper";

			// updated current step id here
			step_id = step_count - 1;
        	
        	$('.cp-switch-screen-loader').addClass('cp-show');
			$(".cp_step_button").removeClass('cp-active-step');

		    $('<span/>', {
			    'class': 'cp_step_button cp-active-step',
			    'data-step': step_count,
			    'text': step_count,
			}).insertAfter('span.cp_step_button:last');

		    if( $("#panel-"+ new_panel_id ).length == 0 ) {
					
				panel_clone.find( ".panel-content-wrapper" ).addClass( new_panel_class ).removeClass( old_panel_class );

				// remove all fieds from step
				panel_clone.find(".cp-field-html-data").remove();
			}

			temp_obj[new_panel_index] = temp_obj[panel_index];
			bmodel.setModal( step_id );
		 
			// append new panel to panel wrapper
			panel_wrapper.append(panel_clone);	

			$.each( temp_obj[new_panel_index], function( obj_index, obj_val) {

				var field_type = obj_val.type;

				if( 'undefined' !== typeof field_type && 
					'panel' !== field_type && 'form_field' !== field_type ) {

					var new_id        = ConvertProHelper._createItemID(field_type);
					var field_clone   = $("#"+ obj_index).clone();
					var current_panel = $(".panel-wrapper #panel-" + step_count );
					var regex         = new RegExp(obj_index, "gi");

					// append field to new step panel
					current_panel.find( '.panel-content-wrapper' ).append(field_clone);

					// Modify id of field
					current_panel.find("#" + obj_index).attr( "id", new_id );

					var old_html      = current_panel.find("#" + new_id).html();

					if( 'undefined' !== typeof old_html ) {

						// Replace old element id with new element id 
						var newhtml = old_html.replace( regex, new_id );

						current_panel.find("#" + new_id).html(newhtml);					

						bmodel.setElementID( step_id, new_id );
						bmodel.setElementModalData( new_id, obj_val );
					}

				} else if( 'panel' == field_type ) {

					$.each( obj_val, function( panel_opt_index, panel_opt_value ) {
						bmodel.setModalValue( "panel-" + ( step_id + 1 ), step_id, panel_opt_index, panel_opt_value, false, false, true );
					});
				}				
			});	

			// add animation while switching steps
			anim_container.addClass("cp-step-animation cp-slideOutLeft");

			setTimeout( function() {
				panel_wrapper.find(".panel").hide();
				panel_wrapper.find("#panel-" + new_panel_id ).show().removeClass('cp-hidden');
				anim_container.removeClass("cp-slideOutLeft").addClass("cp-slideInRight");
			}, 850 );

			setTimeout(function() {
				anim_container.removeClass("cp-slideInRight");
			}, 1700 );

			$(document).trigger( 'cpro_switch_panel', [ step_id + 1, true, true, false ] );

			$(document).trigger( 'cpro_init_drop_on_panel' );

			if( step_count > 1 ) {
				$('.multisteps-panel-icon').find('.cp-icon-trash').removeClass('no-previous-step');
			}

			setTimeout( function() {
				$('.cp-switch-screen-loader').removeClass('cp-show');;
			}, 1900 );

        },

        /**
         * Delete single step and switch to previous step
         *
         * @return void
         * @since 1.0.0
         */
        _deleteStep: function ( e ) {

        	var current_step = step_id + 1;
        	var $this        = $(this);
			// get panel data
			var modal_data_obj = bmodel.get("panel_data");
			var common_fields  = modal_data_obj['common'];
			var obj_count = 0;
			var obj_index = 0;
			var temp_obj  = {};

			if ( window.confirm( cp_pro.step_delete_confirmation + '-' + current_step + '?' ) ) {
				
				// One step cannot be deleted
				if( step_count > 1 ) {

					var panel_id = step_id + 1;
					var switch_panel_id = panel_id - 1;

					// add animation while switching steps
					anim_container.addClass("cp-step-animation cp-slideOutRight");

					// if current step is first step 
					if( panel_id === 1 ) {
						switch_panel_id = panel_id;
					}

					$(".panel-wrapper #panel-" + panel_id ).remove();
					jQuery(".cp_step_button").remove();

					// refresh step list values
					$(".panel-wrapper > .panel[data-type='panel']").each(function(index, el) {
						
						var step_index = index + 1;
						var $this      = $(this);

						jQuery('<span/>', {
						    'class': 'cp_step_button',
						    'data-step': step_index,
						    'text': step_index,
						}).insertBefore('#cp-clone-step');

						// change panel IDs
				        $this.attr( "id", "panel-"+ step_index );    

				        $this.find(".panel-content-wrapper").removeClass (function (index, className) {
						    return (className.match (/(^|\s)panel-\S+/g) || []).join(' ');
						}).addClass("temp-content-wrap");

						$this.find(".temp-content-wrap").addClass( "panel-content-wrapper panel-" + step_index + "-content-wrapper" ).removeClass("temp-content-wrap");

					});

					if( step_id == '0' ) {
						temp_obj[obj_index] = {};
						temp_obj[obj_index]['form_field'] = modal_data_obj[obj_index]['form_field'];
						temp_obj[obj_index]['panel-1'] = modal_data_obj[obj_index]['panel-1'];
					}

					// Removed selected step from panel object
					delete modal_data_obj[step_id];	

					$.each( modal_data_obj, function( data_index, data_val ) {

						if( 'common' !== data_index ) {

							if( typeof temp_obj[obj_index] == 'undefined' ) {
						  		temp_obj[obj_index] = {};
							}

						  	$.each( data_val, function(index, val) {	  	  	

						  		// if deleting first step, skip form field data and panel data
						  		if( ( 'form_field' == index || 'panel-1' == index ) && 0 == step_id ) {
									return;	
								}

								new_index = index; 

								// if object is for panel then set panel keys as per object index
								if( '-1' != index.indexOf("panel") ) {
									new_index = 'panel-' + ( parseInt( obj_index ) + 1 );
								}
						  	  	
					  	  		// Modify panel indexes 		
					  	  		temp_obj[obj_index][new_index] = modal_data_obj[data_index][index];
						  	  	
						  	});
						  	
						  	obj_index++;
						}
					});

					if( 'undefined' !== typeof common_fields ) {
						temp_obj['common'] = common_fields;
					}

					// Set new object data
					bmodel.set({ panel_data : temp_obj });

					// switch to step 
					$('.cp_step_button[data-step="' + switch_panel_id + '"]').addClass("cp-active-step");

					// Modify step id 
					step_id = switch_panel_id - 1;
					
					setTimeout(function() {
						panel_wrapper.find(".panel").hide();	
						panel_wrapper.find("#panel-" + switch_panel_id ).show().removeClass('cp-hidden');
						anim_container.removeClass("cp-slideOutRight").addClass("cp-slideInLeft");

					}, 850 );

					setTimeout(function() {
						anim_container.removeClass("cp-slideInLeft");
					}, 1700 );

					$(document).trigger('cpro_init_drop_on_panel');
					
					// Decrement step count as we have deleted step 
					step_count--;		

					//change panel settings
					$(document).trigger( 'cpro_switch_panel', [ step_id + 1, true ] );

					//Remove selected class
					$(document).trigger( 'cpro_remove_selected' );

					if( 1 == step_count ) {
						$('.multisteps-panel-icon').find('.cp-icon-trash').addClass('no-previous-step');
					}
				}
			}
        },


        /**
         * Apply panel options 
         *
         * @return void
         * @since 1.0.0
         */
        _applyPanelOptions: function ( set_options, is_switch_step ) {

        	// apply panel options
			$( ".cp-customizer-tab[data-section='design'] .cp-input" ).each( function( event ) {

				var $this = $(this);
				var field_type = $this.data("type");
				var field_name = $this.attr("name");
				var is_form_field = typeof field_name !== 'undefined' ? field_name.indexOf("form_field") : 0;

				if( 'inherit_bg_prop' == field_name ) {
					return;
				}

				// if it is not a form field
				if( is_form_field == '-1' ) {
					ConvertProHelper._applyPanelOptions( $this, set_options, is_switch_step );
				} else {
					ConvertProHelper._applyFormFields( $this, set_options );
				}

			});
        },


        /**
         * This function will modify current url acording to step 
         *
         * @return void
         * @since 1.0.0
         */
        _modifyCurrentURL: function( e ) {

        	var currURL = window.location.href;
			currURL = currURL.split("#");

			// remove step parameter from URL 
			currURL[0] = ConvertProHelper._removeUrlParameter( currURL[0], "step" );

			// add new step paramter to url
			newURL = currURL[0] + "&step=" + ( step_id + 1 );

			if(  'undefined' !== typeof currURL[1] ) {
				newURL = newURL + "#" + currURL[1];
			}
			
			history.pushState({}, null, newURL); 

        },


        /**
         * Operations after switching step 
         *
         * @return void
         * @since 1.0.0
         */
        _after_panel_switch: function ( e, current_step, is_new_step, is_clone, is_switch_step ) {

			var set_options = false;

			if( typeof is_new_step != 'undefined' && is_new_step ) { 
				set_options = true;
			}

			if( ( typeof is_new_step != 'undefined' && is_new_step ) && typeof is_clone == 'undefined' ) { 

				var panel_field_id = "panel-" + current_step;
				bmodel.setModalValue( panel_field_id, step_id, 'inherit_bg_prop', '1' ); 
			}

			if( current_step != '1' ) {

				jQuery(".cp-panel-content").each( function(e) {

					// hide panel options which are global 
					jQuery(this).find( ".cp-element-container[data-panel='panel']" ).each( function(e) {
						if( jQuery(this).data("global") == '1' ) {
							jQuery(this).addClass("cp-hidden");
						}
					});
				});

				jQuery("#cp_inherit_bg_prop").closest(".cp-element-container").removeClass("cp-hidden");

				ConvertProMultiStep._hideLeftPanelOptions();
			} else {

				jQuery(".cp-customizer-tabs-wrapper").find('.cp-accordion-content').each(function(i) {

					jQuery(this).removeClass("cp-hidden");
					jQuery(this).prev("h3").removeClass("cp-hidden");

				});

				jQuery("#cp_inherit_bg_prop").closest(".cp-element-container").addClass("cp-hidden");
				jQuery( ".cp-element-container" ).removeClass("cp-hidden");
			}

			ConvertProMultiStep._applyPanelOptions( set_options, is_switch_step );

			// hide resize handlers for fields
			jQuery(".cp-field-html-data .ui-resizable-handle").removeClass("show");

			ConvertProHelper._updateLeftPanel();

			ConvertProMultiStep._modifyCurrentURL();

			bmodel.resetUndo();

			// vertical center design
			$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 0 );

        },

        _displayStepPanel: function() {

			var module_type = $("#cp_module_type").val();
        	
        	// Toggle class for multisteps button
			$( this ).toggleClass('cp-active-link-color');
			steps_wrapper.toggleClass('cp-hidden');
			if( 'info_bar' == module_type ){
				steps_wrapper.addClass('cp-ifb-step-wrapper');
			} else {
				steps_wrapper.removeClass('cp-ifb-step-wrapper');
			}
        },

        _hideLeftPanelOptions: function() {
	
			jQuery(".cp-customizer-tabs-wrapper").find('.cp-accordion-content').each(function(i) {
				var hide_accordion = true;
				var $this = $(this);

				if( ( $this.closest(".cp-panel-content").data("panel") == 'form' || 
					$this.closest(".cp-panel-content").data("panel") == 'panel' ) && !jQuery(this).hasClass("form-fields") ) {

					$this.find('.cp-element-container').each(function() {

						if ( ( typeof jQuery(this).data('global') == 'undefined' || jQuery(this).data('global') == '' )
							&& !jQuery(this).hasClass("cp-field-html-data") ) {
							hide_accordion = false;
							return false;
						}
					});

					if ( hide_accordion ) {

						$this.addClass('cp-hidden');
						$this.prev('h3').addClass('cp-hidden');
					}
				}
			});
		}
    }

    ConvertProMultiStep.init();

})( jQuery );