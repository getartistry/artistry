(function($) {

	var CPFields_Inst = new CPFields('.list-group-item');

	function extend(obj, src) {
	    for (var key in src) {
	        if (src.hasOwnProperty(key)) obj[key] = src[key];
	    }
	    return obj;
	}

	// map modal data to item
	cpMapItem = function( type, id, panel, save_panel_position, is_undo, is_switch_to_mobile, current_step ) {

		var own_data = bmodel.getElementModalData(id,panel);
		var map_style = bmodel.getDeviceValue(own_data.map_style, 'map_style');
		var is_form_input_field = jQuery("#" + id ).find(".cp-target").hasClass("cp-form-input-field");

		if( typeof map_style != 'undefined' ) {
			$.each( map_style, function(i, style){

				var parameter = ( typeof style.parameter === 'undefined' || style.parameter === '' ) ? false : style.parameter.replace(/_/g, '-');
				var onhover = ( typeof style.onhover === 'undefined' || style.onhover === '' ) ? false : style.onhover;
				var target = ( typeof style.target === 'undefined' || style.target === '' ) ? false : style.target;
				var unit = ( typeof style.unit === 'undefined' || style.unit === '' ) ? '' : style.unit;
				var value = bmodel.getModalValue( id, panel, style.name );

				if( parameter !== false ) {

					if( ( is_undo == true || is_undo == false ) && ( parameter == 'entry-animation' ) ) {
						return;
					}

					ConvertProHelper._applySettings( id, parameter, value, unit, onhover, target, panel, false, is_switch_to_mobile, true );
				}
			});
		}

		if( is_form_input_field ) {

			var form_field_data = bmodel.getElementModalData( 'form_field', 0 );
			var form_map_style = bmodel.getDeviceValue( form_field_data.map_style, 'map_style' );

		 	if( typeof form_map_style != 'undefined' ) {
				$.each( form_map_style, function( i, style_data ){

					var parameter = ( typeof style_data.parameter === 'undefined' || style_data.parameter === '' ) ? false : style_data.parameter.replace(/_/g, '-');
					var onhover = ( typeof style_data.onhover === 'undefined' || style_data.onhover === '' ) ? false : style_data.onhover;
					var target = ( typeof style_data.target === 'undefined' || style_data.target === '' ) ? false : style_data.target;
					var unit = ( typeof style_data.unit === 'undefined' || style_data.unit === '' ) ? '' : style_data.unit;
					var value = bmodel.getModalValue( 'form_field', 0, style_data.name );

					if( parameter !== false ) {

						if( ( is_undo == true || is_undo == false ) && ( parameter == 'entry-animation' ) ) {
							return;
						}

						ConvertProHelper._applySettings( id, parameter, value, unit, onhover, target, panel );
					}
				});
			}
		}

		var maps = own_data.map;
		if( typeof maps != 'undefined' ) {
			$.each(maps, function(i, map){
				var value = bmodel.getDeviceValue( own_data[map.name] );
				ConvertProHelper._applyMapValues( id, map, value, false, save_panel_position, true, false, current_step );
			});
		}
	}

	/*
	 * Backbone model and methods
	 */

	// Initialize model
	BModel = Backbone.Model.extend({
	    defaults: {
	        'id': '1',
	        'editor_fonts': {},
	        'device': 'desktop'
	    },
	    r_model: {
	        'device' : 'desktop',
	        'syncId' : []
	    },
	    undoRedo : {
	        'history' : [],
	        'historyId' : [],
	        'action' : [],
			'historyIndex' : -1
	    },
	    includeArr : cp_admin_ajax.mobileIncludeOpt,
		cloneObject: function(obj) {
			var clone = {};
			for(var i in obj) {
				if( typeof(obj[i])=="object" ) {
					clone[i] = this.cloneObject(obj[i]);
				}
				else{
					clone[i] = obj[i];
				}
			}
			return clone;
		},
		resetUndo: function() {

			this.undoRedo.history = [];
			this.undoRedo.historyId = [];
			this.undoRedo.action = [];
			this.undoRedo.historyIndex = -1;

			this.setUndo( true, 'load_exist' );
			$('.cp-undo-button').addClass( 'cp-ur-disabled' );
			$('.cp-redo-button').addClass( 'cp-ur-disabled' );
		},
	    setUndo: function( id, action  ) {

			var current_panel = 'panel-' + ( step_id + 1 );

			if ( id == current_panel || id == 'form_field' ) {
				id = true;
				action = 'load_exist';
			}

	    	if ( typeof action == 'undefined' ) {
	    		action = 'default';
	    	}

	    	if ( action != 'load_exist' && ( typeof id == 'undefined' || id == '' ) ) {
	    		return;
	    	}

	    	var array = {};
	  		$.extend(true, array, this.get('panel_data'));
	  		
	  		var hIndex 		= this.undoRedo.historyIndex + 1;
	  		var hArrLength  = this.undoRedo.history.length;
	  		
	  		if ( hIndex < hArrLength ) {
	  			
	  			var tempArr = [];
	  			$.extend(true, tempArr, this.undoRedo.history);
	  			
	  			tempArr = tempArr.slice(0, hIndex);
	  			this.undoRedo.history = tempArr;

	  			var tempArrId = [];
	  			$.extend(true, tempArrId, this.undoRedo.historyId);
	  			
	  			tempArrId = tempArrId.slice(0, hIndex);
	  			this.undoRedo.historyId = tempArrId;

	  			var tempAction = [];
	  			$.extend(true, tempAction, this.undoRedo.action);
	  			
	  			tempAction = tempAction.slice(0, hIndex);
	  			this.undoRedo.action = tempAction;
	  		}

			this.undoRedo.history.push( array );
			this.undoRedo.historyId.push( id );
			this.undoRedo.action.push( action );

			if ( this.undoRedo.historyIndex < 15 ) {
				this.undoRedo.historyIndex++;
			}else{
				this.undoRedo.history.shift();
				this.undoRedo.historyId.shift();
				this.undoRedo.action.shift();
			}
	  		
	  		if ( this.undoRedo.historyIndex > 0 ) {
				$('.cp-undo-button').removeClass( 'cp-ur-disabled' );
			}

			$('.cp-redo-button').addClass( 'cp-ur-disabled' );
	    },
		applyUndo: function() {
			this.undoRedo.historyIndex--;

			var tempIndex = this.undoRedo.historyIndex;
			var tempArr = {};
	  		$.extend(true, tempArr, this.undoRedo.history[tempIndex]);

			this.set( 'panel_data', tempArr );
			this.loadExistingModal( false, this.undoRedo.historyId[tempIndex], this.undoRedo.action[tempIndex + 1], this.undoRedo.historyId[tempIndex + 1] );

			if ( this.undoRedo.historyIndex <= 0 ) {
				$('.cp-undo-button').addClass( 'cp-ur-disabled' );
			}
			$('.cp-redo-button').removeClass( 'cp-ur-disabled' );

			$(document).trigger('cpro_init_drop_on_panel');

			var panel_id = step_id + 1;

			$( document ).trigger( 'cpro_after_undo', [ panel_id ] );

			$( '#panel-' + panel_id).find(".cp-big-ghost").remove();
		},
		applyRedo: function() {
			this.undoRedo.historyIndex++;

			var tempIndex = this.undoRedo.historyIndex;
			var tempArr = {};
	  		$.extend(true, tempArr, this.undoRedo.history[tempIndex]);

			this.set( 'panel_data', tempArr );
			this.loadExistingModal( false, this.undoRedo.historyId[tempIndex], this.undoRedo.action[tempIndex] );

			if ( ( this.undoRedo.historyIndex + 1 ) == this.undoRedo.history.length ) {
				$('.cp-redo-button').addClass( 'cp-ur-disabled' );
			}
			$('.cp-undo-button').removeClass( 'cp-ur-disabled' );

			$(document).trigger('cpro_init_drop_on_panel');

			var panel_id = step_id + 1;

			$( document ).trigger( 'cpro_after_redo', [ panel_id ] );

			$( '#panel-' + panel_id).find(".cp-big-ghost").remove();
		},
		setDevice: function( device_name, sync_device_data ) {
			this.r_model.device = device_name;
			$html = $('html');

			var mobile_container = '<div class="cp-mobile-container">' +
				  '</div>';

			sync_device_data = ( typeof sync_device_data != 'undefined' ) ? sync_device_data : true;

			if ( device_name == 'mobile' ) {
				$html.removeClass( 'cp-desktop-device' );
				$html.addClass( 'cp-mobile-device' );
				$(mobile_container).insertBefore( ".panel-wrapper" );
			}else {				
				$(document).find('.cp-mobile-container').remove();
				$html.removeClass( 'cp-mobile-device' );
				$html.addClass( 'cp-desktop-device' );
			}

			if ( sync_device_data ) {
				/* Synchronize Device Data */
				this.syncDevice();
			}
		},
		setDeviceValue: function( prev_val, new_val, key, update_both ) {

			if ( jQuery.inArray(key, this.includeArr) == -1 ) {
				return new_val;
			}

			if ( typeof new_val == 'object' ) {
				var tempObj = {};
	  			$.extend(true, tempObj, new_val);
			}else{
				var tempObj = new_val;
			}

			if ( prev_val == undefined ) {
				var tempVal = [];
			} else {

				if ( prev_val.constructor === Array ) {
					var tempVal = prev_val;
				}else{
					var tempVal = [];
					tempVal[0] = prev_val;
				}
			}
			
			if ( this.r_model.device == 'desktop' ) {
				
				tempVal[0] = new_val;
				
				if ( update_both == true ) {
					tempVal[1] = tempObj;
				}
			} else {
				
				if ( update_both == true ) {
					tempVal[0] = tempObj;
				}
				
				tempVal[1] = new_val;
			}

			return tempVal;
		},
		syncDevice: function() {

			var this_obj 	= this;
			var json 		= this.get("panel_data");
			var syncId 		= false;

			$.each( json, function(index, val) {

				if ( step_id != index  ) {
					return;
				}

				if(!$.isEmptyObject(json)) {

					$.each(json[index], function(id, obj){

						if ( jQuery.inArray(id, this_obj.r_model.syncId) != -1 ) {
							return;
						}

						$.each(obj, function(key, value){
							json[index][id][key] = this_obj.syncDeviceValue( json[index][id][key], key );
						});

						this_obj.r_model.syncId.push( id );
					});
				}
			});
		},
		syncDeviceValue: function( prev_val, key ) {

			if ( jQuery.inArray(key, this.includeArr) == -1 ) {
				return prev_val;
			}
			
			if ( prev_val == undefined ) {
				return prev_val;
			}

			if ( this.r_model.device == 'desktop' ) {
				if ( prev_val[1] !== undefined && prev_val[0] === undefined ) {
					
					prev_val[0] = prev_val[1];
					
				}

			} else {
				
				if ( prev_val[0] !== undefined && prev_val[1] === undefined ) {
					
					prev_val[1] = prev_val[0];
					
				}
			}

			return prev_val;
		},
		getDeviceValue: function( value, key, device ) {

			if ( jQuery.inArray(key, this.includeArr) == -1 || typeof value === 'undefined' ) {
				return value;
			}

			var is_fake_device = typeof device != 'undefined' ? device : this.r_model.device;

			if ( value.constructor === Array ) {
				if ( is_fake_device == 'desktop' ) {
					if ( value[0] !== undefined ){
						return value[0];
					}
				}else{
					if ( value[1] !== undefined ) {
						return value[1];
					}else if ( value[0] !== undefined ){
						return value[0];
					}
				}
			}

			return value;
		},
		applyDeviceData: function( device_name ) {
			
			this.setDevice( device_name );
			this.loadExistingModal( true, false, false, false, true );
			this.resetUndo();

			$(document).trigger('cpro_init_drop_on_panel');

			var panel_id = step_id + 1;

			$( document ).trigger( 'cpro_after_apply_device_data', [ panel_id ] );
		},
	    setModal: function(id) {
	   		var array = $.extend( true, {}, this.get('panel_data') );

	   		if( typeof array[id] == 'undefined' ) {
				array[id] = {};
				if( 'common' !== id ) {
					array[id]['panel-'+ ( id + 1 ) ] = { 'type': 'panel' };
				}
				this.set( 'panel_data', array );
			}	
	    },
	    getModalValue: function(id, step_id, key, is_array ) {

	    	var array = {};
	  		$.extend( true, array, this.get('panel_data') );

	    	if( typeof array['common'] !== 'undefined' && typeof array['common'][id] !== 'undefined' ) {
	    		if( typeof array['common'][id][key] !== 'undefined' ) {
	    			if( typeof is_array !== 'undefined' && is_array ) {
						return array['common'][id][key];
	    			} else {
	    				return this.getDeviceValue( array['common'][id][key], key );
	    			}
	    		}
	    	}	

	    	if( typeof array[step_id] !== 'undefined' && typeof array[step_id][id] !== 'undefined' ) {
	    		if( typeof array[step_id][id][key] !== 'undefined' ) {
	    			if( typeof is_array !== 'undefined' && is_array ) {
						return array[step_id][id][key];
	    			} else {
	    				return this.getDeviceValue( array[step_id][id][key], key );
	    			}
	    		}	
	    	}

	    	return undefined;
			
		},
	    appendToModalData: function(element) {
			var id = $(element).attr('id');
			var array = _.clone(this.get('panel_data'));

			array[step_id][id] = {};
			this.set( 'panel_data', array );

		},
		removeFromModalData: function(element_id, step_id, is_undo) {
			var is_deleted = false;
			var array = _.clone(this.get('panel_data'));
			if( typeof array[step_id][element_id] != 'undefined' ) {
				delete array[step_id][element_id];
				this.set( 'panel_data', array );
				is_deleted = true;
				if ( is_undo != false ) {
					this.setUndo( element_id, 'delete' );
				}
			}
			return is_deleted;
		},
		setElementModalData: function(element_id, data) {

			var panel_data = this.get('panel_data');

			var tempArr = {};
	  		$.extend(true, tempArr, panel_data);

			tempArr[step_id][element_id] = data;
			this.set( 'panel_data', tempArr );
		},
		setAllModalValue: function( obj ) {	

			var this_obj = this;
			var array = _.clone(this.get('panel_data'));
			var save_id = '';
			$.each( obj, function(index, val) {

				save_id = val.for_edit; 
				var id = val.for_edit;
				var current_step = val.current_step;
				var key = val.name;
				var value = val.value;

				if( typeof array[current_step][id]  == 'undefined' ) {
					array[current_step][id] = {};
				}

				if( typeof key !== 'undefined' && typeof array[current_step][id] !== 'undefined' ) {
					
					array[current_step][id][key] = this_obj.setDeviceValue( array[current_step][id][key], value, key);
					
				}
			});
			
			this.set( 'panel_data', array );

			this.setUndo( save_id, 'drop' );

		},

	    setModalValue: function( id, current_step, key, value, is_undo, update_both, set_both ) {	

	    	var step_dependent_opts = cp_admin_ajax.stepdependentOpts;	
	    	var step_index = current_step;

	    	// if option is not step dependent
	    	if( $.inArray( key, step_dependent_opts ) == -1 ) {

	    		if( id.indexOf('panel') !== -1 || id.indexOf('form_field') !== -1 ) {
	    			step_index = 0;
	    		} 
	    		
	    		// if it is panel related option 
	    		if( id.indexOf('panel') !== -1 ) {
	    			id = 'panel-1';
	    		}
	    	}

	    	var array = {};
	  		$.extend( true, array, this.get('panel_data') );

	    	if( typeof array[step_index] !== 'undefined' && typeof array[step_index][id] == 'undefined' ) {
	    		array[step_index][id] = {};
	    	}

			if( typeof key !== 'undefined' && typeof array[step_index][id] !== 'undefined' ) {

				if( value !== null ) {
					if( typeof set_both !== 'undefined' && set_both ) {
						array[step_index][id][key] = value;
					} else {
						array[step_index][id][key] = this.setDeviceValue( array[step_index][id][key], value, key, update_both );
					}
				}

				this.set( 'panel_data', array );

				Cookies.set( 'cp-unsaved-changes', "1" );
			}

			if ( typeof is_undo == 'undefined' || is_undo == true ) {
				this.setUndo( id );
			}
		},
		setElementID: function( current_step, element_id ) {	
			var array = $.extend( true, {}, this.get('panel_data') );

			if( typeof array[current_step][element_id]  == 'undefined' ) {
				array[current_step][element_id] = {};
				this.set( 'panel_data', array );
			}
		},
		getElementModalData: function( element_id, panel ) {

			var array = _.clone(this.get('panel_data'));
			var data = false;

			if( panel != 'common' ) {
				panel = parseInt(panel);
			}

			if( typeof array[panel][element_id] != 'undefined' ) {
				data = array[panel][element_id];
			}
			return data;
		},
		loadExistingModal: function( is_undo, edit_id, action, remove_id, is_switch_to_mobile ) {
			var this_obj = this;
			var json = this.get("panel_data");
			var load_all = false;

			if ( is_undo == true ) {
				$('.panel-wrapper .cp-field-html-data.cp-panel-item').remove();

			} else if ( is_undo == false ) {
				
				if( edit_id != true ) {
					$('#' + edit_id ).remove();
					$('#' + remove_id ).remove();
				}
				
				if( edit_id == true || action == 'load_exist' ) {
					load_all = true;
					$('#panel-'+ ( step_id + 1 ) +'  .cp-field-html-data.cp-panel-item').remove();
				}
				else if ( action == 'drop' || action == 'clone' || action == 'delete' ) {
					if ( typeof remove_id != 'undefined' ) {
						$('#' + remove_id ).remove(); 
					}
				}
			}

			$.each( json, function(index, val) {

				if ( is_undo == false && ( step_id != index && index != 'common' ) ) {
					return;
				}

				if(!$.isEmptyObject(json)) {
					$.each(json[index], function(id, obj){
						
						if (
							 is_undo == false
							 && load_all != true
							 && edit_id != id
							 && remove_id != id
							 && edit_id != true
						) {
							return;
						}

						var type = obj.type;
						var respectiveTo = obj.respective_to;
						var is_outside_hide = typeof obj.is_outside_hide !== 'undefined' ? obj.is_outside_hide : false;

						if( typeof type == 'undefined' && id.indexOf("panel") !== '-1' ) {
							type = 'panel';
						}	

						if( type == 'panel' ) {
							var save_panel_position = false;
							cpMapItem( type, id, index, save_panel_position, is_undo, index );
							return;
						}

						var position = this_obj.getDeviceValue( obj.position, 'position' );
						var rotation_angle = this_obj.getDeviceValue( obj.rotate_field, 'rotate_field' );

						var fieldValue = '';
						var map = ( typeof obj.map != 'undefined' ) ? obj.map : false;
						if( map != false ) {
							$.each(map, function(key, map_data){
								if( map_data.attr == 'value' ) {
									fieldValue = obj[map_data.name];
									return;
								}
							});
						}

						var templateData = $("#field-template-" + type ).html();
						var fieldx = temp = {};

						fieldx = CPFields_Inst.getCPFieldByID(type);

						if( typeof fieldx !== 'undefined' ) {

							$.each( fieldx, function(k, v){
								temp[k] = v;
							});

							temp.id = id;
							temp.type = type;
							temp.name = type;
							temp.is_outside_hide = obj.is_outside_hide;
							var has_editor = temp.has_editor;
							var z_index = obj.layerindex;	
							var text_content = typeof obj.text_content !== 'undefined' ? obj.text_content : '';

							/* Update Shape SVG Template */
							if( type == 'cp_shape' ) {

								if( 'cc-paypal' == obj.shape_preset ) {
									obj.shape_preset = 'cc-ppl';
								}

								var shape_preset = obj.shape_preset;
								var data_content = $('.' + type.replace(/\_/g,'-') + 's.cp-preset-field.draggable[data-preset="'+shape_preset+'"]').data('content');
								templateData = templateData.replace( '{{svg_content}}', data_content );
								templateData = templateData.replace( '{{field_preset}}', shape_preset );
								templateData = templateData.replace( '{{stroke_width}}', '2px' );
								templateData = templateData.replace( '{{stroke_half_width}}', '1px' );
							}

							/* Update Preset data */
							if( typeof obj.btn_preset != "undefined" ) {
								templateData = templateData.replace( '{{field_preset}}', obj.btn_preset );
							}

							templateData = ConvertProEditPanel._renderTemplate( templateData, temp, fieldValue );

						  	var htmlData = $(templateData).children();
						  	var panel_container_id = parseInt(index) + 1;
					  		var position_unit = "px";

					  		if( respectiveTo != 'true' ) {
					  			$('.panel-'+ panel_container_id +'-content-wrapper').append(htmlData);
					  		} else {
					  			$('.panel-wrapper' ).append(htmlData);
					  			$('#'+id).addClass("cpro-overlay-field");
					  			position_unit = '%';
					  		}

						  	if( typeof position !== 'undefined' && position !== null ) {

						  		if( typeof position.right != 'undefined' && parseFloat( position.right ) < 50 && position.right !== 'no' && position.right !== null ) {
									$('#'+id).css({ right: position.right + position_unit, bottom: position.bottom + position_unit });					  			
						  		} else {

						  			if( parseFloat( position.y ) < 50 ) {
										$('#'+id).css({ top: position.y + position_unit, left: position.x + position_unit });
									} else {

										if( position.bottom !== 'no' && parseFloat( position.bottom ) < 50 && position.right !== null ) {
	 										$('#'+id).css({ bottom: position.bottom + position_unit, left: position.x + position_unit });
	 									} else {
											$('#'+id).css({ top: position.y + position_unit, left: position.x + position_unit });
	 									} 
									}
								}
						  	}	

						  	$('#'+id).find(".cp-target").attr( "id", id + "-content" );

						  	// load modal data to item
						  	cpMapItem( type, id, index, false, false, is_switch_to_mobile, index );
						  	var is_resize = ( typeof fieldx.resize != 'undefined' ) ? fieldx.resize : false;

						  	if( is_resize ) {
						  		$('#'+id).addClass('cp-resize-element');
						  	}

						  	if( has_editor ) {
								$("#"+id).addClass('cp_has_editor');					  		
						  	}

						  	if( typeof z_index != 'undefined' ) {
						  		$("#"+id).css( "z-index", z_index );
						  	}

						  	text_content = decodeURIComponent( text_content );

						  	if( text_content !== '' ) {
						  		$("#"+id).find(".cp-target").html(text_content);
						  	}
						  	$("#"+id).append(resize_handlers);

						  	ConvertProHelper._rotateField( $( '#'+id + " .cp-rotate-wrap" ), rotation_angle );
						}
					});
				}
			});

			$(document).trigger( "cpro_customizer_loaded" );

			if ( 'undefined' == typeof is_undo ) {
				this.setUndo( true, 'load_exist' );
			}
		},
		setModalStyleValue: function( id, name, parameter, step_index, unit, onhover, target ) {

			var this_obj = this;
			var modal_data =  this.get("panel_data");
			var map_position = false;

			var step_dependent_opts = cp_admin_ajax.stepdependentOpts;	

	    	// if option is not step dependent
	    	if( $.inArray( name, step_dependent_opts ) == -1 && step_index > 0 ) {
	    		return false;
	    	}

			if( typeof modal_data[step_index][id] == 'undefined' ) {
				modal_data[step_index][id] = {};
			}

			var styles = typeof modal_data[step_index][id].map_style != 'undefined' ? modal_data[step_index][id].map_style : {};
			var is_found = false;
			var newObject = $.extend(true, {}, modal_data);
			var styles_length = styles.length;
			var index_count = 0;
			var curr_index = 0;

			$.each( styles, function( i, style ) {
				
				if( style.name == name ) {
					is_found = true;
					curr_index = i;
				} 

				index_count = i;
			});

			var map_style_obj = {
				name: name,
				parameter: parameter,
				unit: unit
			};

			if( 'undefined' != typeof onhover ) {
				map_style_obj['onhover'] = onhover;
			}

			if( 'undefined' != typeof target ) {
				map_style_obj['target'] = target;
			}
			
			if( !is_found ) {
				var new_index = parseInt( index_count ) + 1;

				if( typeof newObject[step_index][id]['map_style'] == 'undefined' ) {
					newObject[step_index][id]['map_style'] = {};
				}

				if( typeof newObject[step_index][id]['map_style'] != 'undefined' ) {
					if( typeof newObject[step_index][id]['map_style'][new_index] == 'undefined' ) {

						newObject[step_index][id]['map_style'][new_index] = map_style_obj;
					}
				}
			} else {

				if( typeof newObject[step_index][id]['map_style'] != 'undefined' ) {
					if( typeof newObject[step_index][id]['map_style'][curr_index] != 'undefined' ) {

						newObject[step_index][id]['map_style'][curr_index] = map_style_obj;
					}
				}
			}

			this.set({ 'panel_data': newObject });				
		},
		setModalMapValue: function( id, name, attr, step_index, target ) {

			var this_obj = this;
			var modal_data =  this.get("panel_data");
			var map_position = false;

			var step_dependent_opts = cp_admin_ajax.stepdependentOpts;	

	    	// if option is not step dependent
	    	if( $.inArray( name, step_dependent_opts ) == -1 && step_index > 0 ) {
	    		return false;
	    	}

			if( typeof modal_data[step_index][id] == 'undefined' ) {
				modal_data[step_index][id] = {};
			}

			var styles = typeof modal_data[step_index][id].map != 'undefined' ? modal_data[step_index][id].map : {};
			var is_found = false;

			var newObject = $.extend(true, {}, modal_data);
			var styles_length = styles.length;
			var index_count = 0;

			$.each( styles, function( i, style ) {
				
				if( style.name == name ) {
					is_found = true;
				} 

				index_count = i;
			});

			if( !is_found ) {
				var new_index = parseInt( index_count ) + 1;

				if( typeof newObject[step_index][id]['map'] == 'undefined' ) {
					newObject[step_index][id]['map'] = {};
				}

				if( typeof newObject[step_index][id]['map'] != 'undefined' ) {
					if( typeof newObject[step_index][id]['map'][new_index] == 'undefined' ) {

						newObject[step_index][id]['map'][new_index] = {
							attr: attr,
							target: target
						};
					}
				}
			}

			this.set({ 'panel_data': newObject });				
		},
		getDefaultData: function() {

			var this_obj = this;
			var new_def_data = { type: 'panel' };
			var def_toggle_data = { type: 'toggle' };
			var form_data    = { type: 'form_field' };
			var param_index = 0;
			var form_field_map_index = 0;
			var map_style_data = {};
			var ff_map_style_data = {};
			var key = 0;
			var load_previous_data_json = {};
			var data = {};	
			var has_toggle_field = false;
			var exclude_panel_options = [ 'credit_link_color' ];

			jQuery(".cp-element-container[data-panel='panel'] .cp-input").each( function(e) {
				var field_name = jQuery(this).attr("name");
				var map_style  = jQuery(this).data("mapstyle");
				var is_toggle_option = false;

				if( typeof map_style !== 'undefined' && typeof map_style['target'] !== 'undefined' && map_style['target'] == 'toggle' ) {
					is_toggle_option = true;	
				}

				if( $.inArray( field_name, exclude_panel_options ) !== -1 ) {
					return;
				}

				if( !is_toggle_option ) {

					if ( $('#cp_module_type').val() == 'modal_popup' || $('#cp_module_type').val() == 'info_bar' || $('#cp_module_type').val() == 'slide_in' ||
						 $('#cp_module_type').val() == 'before_after' || $('#cp_module_type').val() == 'inline' || $('#cp_module_type').val() == 'widget' ) {
						if ( field_name == 'panel_height' ) {
							new_def_data[field_name] = [jQuery(this).val(), jQuery(this).data("default")];
						}

						if ( field_name == 'panel_width' ) {
							new_def_data[field_name] = [jQuery(this).val(), jQuery(this).data("default")];
						}
					}
					
					if( typeof field_name !== 'undefined' ) {
						
						var value = jQuery(this).val();
						new_def_data[field_name] = this_obj.setDeviceValue( new_def_data[field_name], value, field_name );

						if ( typeof new_def_data['map_style'] == 'undefined' ) {
							new_def_data['map_style'] = {};
						}

						if( typeof map_style !== 'undefined' &&  map_style !== '' ) {

							map_style.name = field_name;
							new_def_data['map_style'][param_index] = map_style;
							param_index++;
						}
					}
				} else {

					has_toggle_field = true;

					if( typeof field_name !== 'undefined' ) {
						
						var value = jQuery(this).val();
						def_toggle_data[field_name] = this_obj.setDeviceValue( def_toggle_data[field_name], value, field_name );

						if ( typeof def_toggle_data['map_style'] == 'undefined' ) {
							def_toggle_data['map_style'] = {};
						}

						if( typeof map_style !== 'undefined' &&  map_style !== '' ) {

							map_style.name = field_name;
							def_toggle_data['map_style'][param_index] = map_style;
							param_index++;
						}
					}
				}
			});	
			
			data['panel-1'] = new_def_data;

			if( has_toggle_field ) {
				data['toggle'] = def_toggle_data;
			}

			jQuery(".cp-element-container[data-panel='form'] .cp-input").each( function(e) {

				var field_name = jQuery(this).attr("name");
				var map_style  = jQuery(this).data("mapstyle");

				if( typeof field_name !== 'undefined' ) {
					var value = jQuery(this).val();
					form_data[field_name] = this_obj.setDeviceValue( form_data[field_name], value, field_name );;
					
					if ( typeof form_data['map_style'] == 'undefined' ) {
						form_data['map_style'] = {};
					}

					if( typeof map_style !== 'undefined' &&  map_style !== '' ) {

						map_style.name = field_name;
						// map_style.value = value;

						form_data['map_style'][form_field_map_index] = map_style;
						form_field_map_index++;
					}
				}

			});	

			data['form_field'] = form_data;
			load_previous_data_json[key] = data;
			return load_previous_data_json;
		}

	});

})(jQuery);