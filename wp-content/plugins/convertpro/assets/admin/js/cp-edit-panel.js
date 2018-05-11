var ConvertProEditPanel = '';

(function($) { 

	var CPFields_Inst = new CPFields('.list-group-item');
	cp_fields = CPFields_Inst.getSimplyfyCPFields();

	cp_params_url = $('#cp_params_url').val();

	var param_json = $('#cp_params').val();
	cp_params = $.parseJSON(param_json);
	var selected_timezone = '';

	ConvertProEditPanel = {

		init: function() {

			// panel edit actions click
			$( '.cp-edit-actions a' ).click( function( e ) {
				e.preventDefault();
				var type = $(this).attr('data-type');
				if( 'close' == type ) {
					$(document).trigger( 'cpro_close_edit_panel' );
				}
			});

			// Open edit panel
			$(document)
				.on( 'cpro_open_edit_panel', this._open_edit_panel )

				// CLose icon list on outside click in edit panel
				.on( 'click', '.cp-edit-panel-content .ui-tabs-panel:not(.change_icon)', function(e) {

					if( 'change_icon' !== $(event.target).attr('class') ) {
						$(this).find('.cp-icon-wrapper').next('.cp-icons-container').slideUp(150);	
					}
				})

				// Close edit panel
				.on( 'cpro_close_edit_panel', this._close_edit_panel )
				.on( 'cpro_after_edit_panel_open', this._handleDependency );

		},

	    /*
		 * Handle dependency for fields 	
		*/
		_handleDependency: function() {

			// iterate through all fields in edit panel 
			$(".cp-edit-panel-content").find(".cp-edit-panel-field").each(function(index, el) {
			
				var param_dependecy = $(this).attr('data-dependency');

				// if dependency is set for field
				if( typeof param_dependecy !== 'undefined' ) {

					param_dependecy = JSON.parse(param_dependecy);
					var dependency_relation = param_dependecy.relation;
					var display = false;	

					// Relational/Multiple dependency 
					if( typeof dependency_relation !== 'undefined' ) {

						for ( var property in param_dependecy ) {
						    if ( param_dependecy.hasOwnProperty(property) ) {
						        if( property !== 'relation' ) {
						        	var depen = param_dependecy[property];
						        	var field_name = depen.name;
						        	var compare_operator = depen.operator;
						        	var field_value = depen.value;

						        	if( dependency_relation == 'AND' ) {
							        	switch(compare_operator) {
							        		case "==": 	
							        			if( $( '[name='+field_name+']').val() == field_value  ) {
							        				display = true;
									        	} else {
									        		display = false;
									        	}
							        		break;	
							        		case "!=":
							        			if( $( '[name='+field_name+']').val() != field_value  ) {
							        				display = true;
									        	} else {
									        		display = false;
									        	}
							        		break;
							        	}

							        	if( display == false ) {
							        		break;
							        	}

							        } else {

							        	// OR dependency 
							        	switch(compare_operator) {
							        		case "==": 	
							        			if( $( '[name='+field_name+']').val() == field_value  ) {
							        				display = true;
							        				break;
									        	}
							        		break;	
							        		case "!=":
							        			if( $( '[name='+field_name+']').val() != field_value  ) {
							        				display = true;
							        				break;
									        	}
							        		break;
							        	}
							        }	
						        	
						        }
						    }
						}

					} else { // single dependency 
						
						var field_name = param_dependecy.name;
			        	var compare_operator = param_dependecy.operator;
			        	var field_value = param_dependecy.value;	

			        	switch(compare_operator) {
			        		case "==": 	
			        			if( $( '[name='+field_name+']').val() == field_value  ) {
			        				display = true;
					        	}
			        		break;	
			        		case "!=":
			        			if( $( '[name='+field_name+']').val() != field_value  ) {
			        				display = true;
					        	}
			        		break;
			        	}
					} 

					if( display ) {
						$(this).closest(".cp-param-inner").removeClass('cp-hidden');
					} else {
						$(this).closest(".cp-param-inner").addClass('cp-hidden');
					}
				}

			});
		},

		// Render template
		_renderTemplate: function( template, replace_object, value, optional_val ){

			var name =  '';
			var type = '';
			var max_width = '';	
			var field_classes = '';

			if( $.isEmptyObject(replace_object) )
				return;

			replace_object.value = value;
			
			template = '<div class="cp-edit-panel-field {{class}}">'+template+'</div>';

			$.each( replace_object, function( key, obj_value ) {
					
				var find = '{{'+key+'}}';	
				var replace = obj_value;			
				switch( replace_object.type ) {
					
					case "dropdown":

						if( '{{options}}' == find ) {

							var options_html = '';
							$.each( replace, function( i,value ) {
								if( i == replace_object.value ) {
									options_html += "<option selected='selected' value="+ i +">"+value+"</option>";	
								} else {
									options_html += "<option value="+ i +">"+value+"</option>";	
								}
							  	
							});
							template = template.replaceAll( find, options_html );
						} else {
							template = template.replaceAll( find, replace );	
						}
					break;

					case "timezone":
						if( '{{options}}' == find ) {
							selected_timezone = replace_object.value;
							template = template.replaceAll( find, replace_object.zones );
						} else {
							template = template.replaceAll( find, replace );
						}
					break;


					case "cp_button":

						if( '{{value}}' == find ) {

							if( 'undefined' == typeof replace_object['value'] ) {
								var presets = replace_object.presets;
								var current_preset = replace_object['preset'];

								if( typeof presets[current_preset] !== 'undefined' ) {
									var title = presets[current_preset]['title']['value'];
									template = template.replaceAll( find, title );
								}
							} else {
								template = template.replaceAll( find, replace );
							}

						} else {

							template = template.replaceAll( find, replace );	
						}
					break;	

					case "radio_image":	
						name = replace_object.name;
						type = replace_object.type;
						max_width = replace_object.img_width;
						if( '{{options}}' == find ) {
							var options_html = '';
							var cnt = 0;					
							$.each( replace, function(i,value) {
								var selected_str = '';
								var checked_str = ""
								if( value == replace_object.value ) {
									selected_str = 'selected';
									checked_str = "checked='true'"
								}

								options_html += "<div class='cp-radio-image-holder " + selected_str + "' >";
								options_html += "<input type='radio' name="+ name +" value="+ value +" data-id='cp_"+ name +"' class='form-control cp-input cp-radio_image "+ name +" "+ type +"' " + checked_str + "> ";
								options_html += "<label for='cp_"+i +"_"+cnt+"' class='cp-radio-control'><img class='cp-radio-control "+ name +"-"+ i +"' src=" + cp_admin_ajax.assets_url + value +" style='max-width:"+ max_width +"px' /></label>";
								options_html += "</div>";

								cnt ++ ;						  	
							});
							template = template.replaceAll( find, options_html );
						} else {
							template = template.replaceAll( find, replace );	
						}
					break;

					case "text_align":
						name = replace_object.name;
						type = replace_object.type;
						if( '{{options}}' == find ) {
							var options_html = '';
							var cnt = 0;
							$.each( replace, function(i,value) {

								if( 'justify' != value ) {
									dash_value = 'align' + value;
								} else {
									dash_value = value;
								}
								
								if( replace_object.value == null || replace_object.value == ''  ) {
									default_value = replace_object.default_value;
								} else {
									default_value = replace_object.value;
								}

								if( i == default_value ) {
									options_html += "<div class='cp-text-align-holder selected' >";
									options_html += "<input type='radio' name="+ name +" value="+ value +" data-id='cp_"+ name +"' class='form-control cp-input cp-text_align "+ name +" "+ type +"' checked= 'true'> ";
									options_html += "<label for='cp_"+i +"_"+cnt+"' class='cp-radio-control'><span class='cp-radio-control "+ name +"-"+ i +"'><i class='dashicons dashicons-editor-"+dash_value+"'></i></span></label>";
									options_html += "</div>";
								} else {
									options_html += "<div class='cp-text-align-holder'>";
									options_html += "<input type='radio' name="+ name +" value="+ value +" data-id='cp_"+ name +"' class='form-control cp-input cp-text_align "+ name +" "+ type +"'> ";
									options_html += "<label for='cp_"+i +"_"+cnt+"' class='cp-radio-control'><span class='cp-radio-control "+ name +"-"+ i +"'><i class='dashicons dashicons-editor-"+dash_value+"'></i></span></label>";
									options_html += "</div>";
								}
								cnt ++ ;
							});
							template = template.replaceAll( find, options_html );
						} else {
							template = template.replaceAll( find, replace );				}
					break;

					case "border":				
						if( '{{options}}' == find ) {
							var val = replace_object.value;
							val = val.split("style:");
							if( val.length > 0 ){
								val = val[1].split("|")[0];
							} else {
								val ='';
							}
							var options_html = '';
							$.each( replace, function(i,value) {							
								if( i == val ) {
									options_html += "<option selected='selected' value="+ i +">"+value+"</option>";	
								} else {
									options_html += "<option value="+ i +">"+value+"</option>";	
								}						  	
							});
							template = template.replaceAll( find, options_html );
						} else {
							template = template.replaceAll( find, replace );	
						}
					
					break;

					case "box_shadow":	
									
						if( '{{options}}' == find ) {
							var val = replace_object.value;
							val = val.split( "type:" );
							if( val.length > 0 ) {
								val = val[1].split("|")[0];
							} else {
								val ='';
							}
							var options_html = '';
							$.each( replace, function(i,value) {							
								if( i == val ) {
									options_html += "<option selected='selected' value="+ i +" class='cp_"+ i +"'>"+value+"</option>";	
								} else {
									options_html += "<option value="+ i +" class='cp_"+ i +"'>"+value+"</option>";	
								}						  	
							});
							template = template.replaceAll( find, options_html );
						} else {
							template = template.replaceAll( find, replace );	
						}				
					break;

					case "switch":	

						if( '{{options}}' == find ) {
							if( 'undefined' !== typeof replace ) {											 	
								var opt = "data-on='"+ replace['on'] +"' data-off='"+ replace['off'] +"'";
								template = template.replaceAll( find, opt );
							}
						} else {

							template = template.replaceAll( find, replace );	
						}				
					break;

					case "font":
						var cp_params_obj = $("#cp_params").val();
						var dropdown_options = '';
						var parsed_obj = JSON.parse(cp_params_obj);
						var font_value = replace_object.value.split(":");

						var selected_font = font_value[0];
						var sel_font_weight = font_value[1];
						var font_weight_options = '';

						$.each( parsed_obj, function(index, val) {	
							if( 'cp_font_par' == val.id ) {
								var font_options = val.options;	

								$.each( font_options, function(index, val) {
									
									dropdown_options += "<optgroup label='"+index+"'>";

									$.each( val , function( font_index, font_val ) {
										var font_weights = font_val.join();

										
										if ( 'inherit' == font_index ) {
											font_index_label = 'Inherit from Global Settings';
										}else{
											font_index_label = font_index;
										}
										
										if( font_index == selected_font ) {
											dropdown_options += "<option selected='selected' data-weight='"+ font_weights +"'  value='"+  font_index + "'>"+ font_index_label  +"</option>";
											font_weight_options = font_weights.split(",");									
										} else {	
											dropdown_options += "<option data-weight='"+ font_weights +"'  value='"+  font_index + "'>"+ font_index_label +"</option>";
										}
		 						  	});

		 						  	dropdown_options += "</optgroup>";
								});
							}
						});

						var font_weight_html = '';
						$.each( font_weight_options, function(index, val) {

							if ( val == 'Inherit'  ) {
								val_label = 'Inherit';
							}else{
								val_label = val;
							}

							if( val == sel_font_weight ) {
								font_weight_html += "<option selected='selected' value='"+ val +"'>"+val_label+"</option>";	
							} else {
								font_weight_html += "<option value='"+ val +"'>"+val_label+"</option>";	
							}
							
						});

						template = template.replaceAll( "{{weight_options}}", font_weight_html );
						template = template.replaceAll( "{{options}}", dropdown_options );
						template = template.replaceAll( find, replace );
					break;
					
					default:
						template = template.replaceAll(find, replace);							
					break;
				}
				
			});

			if( replace_object.is_preset_field ) {
				template = template.replaceAll( "{{field_preset}}", replace_object.preset );
				field_classes = "cp-panel-field cp-preset-fields";
			} else {
				field_classes = "cp-panel-field";
			}

			template = template.replaceAll( "{{class}}", field_classes );
			template = template.replaceAll( "{{contenteditable}}", true );

			return template;
		},

		_open_edit_panel: function( e, element, force_hide, apply_respective, field_name ) {

			var is_respective_to_panel = false;
			var for_edit = $(element).closest('.cp-field-html-data').attr('data-type');
			var current_step = step_id;
			var element_id = $(element).attr('id');
			var type = $(element).attr("data-type");
			var fieldx = temp = {};
			fieldx = CPFields_Inst.getCPFieldByID(type);
			var SettingLabel = $(element).closest('.cp-field-html-data').attr('data-field-title');

			if ( 'undefined' === typeof apply_respective ) {
				apply_respective = true;
		    }
			
			if( 'undefined' == typeof for_edit ) {
				console.log('data-type="{{type}}" is missing or blank in template.html for fields.');
			}

			if( 'cp_shape' == type ) {
				var preset = $(element).attr("data-preset");
				if( 'undefined' != typeof preset ) {
					if( 'square01' == preset ) {
						setTimeout( function(){
							$( '.cp-edit-panel' ).find( '#cp_shape_width' ).closest( '.cp-param-inner' ).show();
							$( '.cp-edit-panel' ).find( '#cp_height' ).closest( '.cp-param-inner' ).show();
						}, 100 );
					} else if( preset == 'line05' || preset == 'line06' || preset == 'line07' ) {
						setTimeout( function(){
							$( '.cp-edit-panel' ).find( '#cp_shape_width' ).closest( '.cp-param-inner' ).show();
							$( '.cp-edit-panel' ).find( '#cp_height' ).closest( '.cp-param-inner' ).hide();
						}, 100 );
					} else {
						setTimeout( function(){
							$( '.cp-edit-panel' ).find( '#cp_shape_width' ).closest( '.cp-param-inner' ).hide();
							$( '.cp-edit-panel' ).find( '#cp_height' ).closest( '.cp-param-inner' ).show();
						}, 100 );
					}
				}
			}

			if( typeof fieldx !== 'undefined' ) {
				$.each(fieldx, function(k, v){
					temp[k] = v;
				});
			}

			if ( apply_respective ) {
				if( typeof temp.sections !== 'undefined' ) {
					$.each( temp.sections, function( k, v ) {
						$.each( v.params, function( index, val ) {
							if( val.id == 'respective_to' ) {
								is_respective_to_panel = val.default_value;
							}
						});
					});
				}
			}

			if( is_respective_to_panel ) {
				current_step = 'common';
			}

			// Update Edit Panel Setting Label
			$( '.cp-edit-panel-wrapper > h3' ).html( SettingLabel + ' Settings' );

			// load content in edit panel
			var $content_area = $('.cp-edit-panel-content');
			$content_area.html('');

			for_edit = typeof field_name !== 'undefined' ? field_name : for_edit;
			$.each( cp_fields, function( i, field ) {

				if( 'undefined' !== typeof field ) {
					var id = field.id; 
					if( id == for_edit ) {
						
						if( !$.isEmptyObject(field.sections) ) {
							var sections = field.sections;
							var presets = field.hasOwnProperty('presets') && field.presets;  
							var is_preset_field = $(element).closest('.cp-field-html-data').hasClass('cp-preset-fields');
							var param_types = new Array();
							var dependency_elements = {};
							var preset_values = {};
							var step_value = '';

							if( ( field.id == "cp_shape" ) && typeof field.presets != 'undefined' ) {
								var shapePresets = field.presets,
									shapeNewPresets = {};						
								if( shapePresets.length > 0 ) {
									$.each( shapePresets, function( key, value ) {
										shapeNewPresets[ value['name'] ] = value['preset_setting'];
									});
									presets = shapeNewPresets;
								}
							}

							if( is_preset_field ) {
								var preset = $(element).closest('.cp-field-html-data').data('preset');
								preset = preset.replace(/-/g , "_");
								preset_values = presets[preset];	
							}

							$htmlData = "<div id='cp-accordion'>";

							$.each( sections, function(index, val) {

								if( val.params.length > 0 ) {

									var title_lcase = val.title.toLowerCase();
									$htmlData += "<h3 class='cp-accordion-title "+ title_lcase +"'>" + val.title + "</h3>";

									$htmlData += '<div class="cp-accordion-content '+ title_lcase +'" data-acc-class="'+title_lcase+'" >';

									var params = val.params,
										canProceed = true;
									$.each(params, function(i, param){	

										var type = param.type;
										var description = typeof param.description !== 'undefined' ? param.description : '';
										var extra_cls = ''; 
										if( type == 'hidden' ) {
											extra_cls += 'cp-hidden';
										}

										if ( param.show_on_mobile == true ) {
											extra_cls += ' cp-mobile-show';
										}

										$htmlData += '<div class="cp-param-inner '+ extra_cls +' cp-param-content-'+ param.type.toLowerCase() +'" >';

										var param_id = param.name;
										var default_value = param.default_value;

										if( param.id == 'input_text_name' || param.id == 'dropdown_name' || param.id == 'radio_name' || param.id == 'checkbox_name' ) {
											default_value = param.default_value + '_' + Math.floor(1000 + Math.random() * 9000);
										}

										var options = param.options;
										var js_script_url = cp_params_url + type + '/js/' + type + '.js';
										var styles_url = cp_params_url + type + '/css/' + type + '.css';
										var build_template_id = 'param-template-'+type;
										var param_dependecy = param.dependency;							

										// if param has tinymce editor, do not render it in edit panel
										if( typeof param.has_tiny_editor !== 'undefined' ) {	
											canProceed = false;
										}

										if( canProceed ) {
											if( $('#'+build_template_id).length != 0 ) {

												var template = $('#'+build_template_id).html();		
												var value = bmodel.getModalValue(element_id, current_step, param_id);	
												var selected = '';
												if( typeof value == 'undefined' ) {
													if( is_preset_field && typeof preset_values !== 'undefined' ) {
														if( preset_values.hasOwnProperty(param_id) ) {
															value = preset_values[param_id].value;
														} else {
															value = default_value;
														}
													} else {
														value = default_value;
													}
												}

												if( $.inArray( type, param_types ) == -1 ) {
													param_types.push(type);
												}

												var tooltip_html = '<span class="cp-tooltip-icon has-tip" data-position="right" title="' + description +  '" style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
												
												if( description !== '' ) {
													template = template.replaceAll( '{{tooltip}}', tooltip_html );									
												} else {
													template = template.replaceAll( '{{tooltip}}', '' );									
												}

												param.for = element_id;
												if( type !== 'icon' ) {
													template = ConvertProEditPanel._renderTemplate( template, param, value, options );
												} else {								
													$.each( cp_params, function(index, val) {
														var options = val.options;
														var type = val.type; 
														if( type == 'icon' ) {
															template = ConvertProEditPanel._renderTemplate( template, param, value , options );
														}									
													});
												}

												$htmlData += template;

												// load JS scripts for param
												$.each( cp_params, function(index, val) {

													if( val.type == type ) {
														if( val.scripts !== '' ) {										
															// load JS script for param
															ConvertProHelper._loadJS(js_script_url);
														}

														if( val.styles !== '' ) {
															// load JS script for param
															ConvertProHelper._loadStyles(styles_url);
														}
													}
												});

												if( typeof param_dependecy !== 'undefined' ) {
													var dep_string = JSON.stringify(param_dependecy);
													dependency_elements[param_id] = dep_string;
												}

												// for step dropdown
												if( param_id == 'btn_step' ) {
													if( value !== null ) {
														step_value = value;
													}
												}
											}

										}
										$htmlData += '</div>';

									});
									
									// Advanced section
									if( 'advanced' == title_lcase || 'hidden input' == title_lcase ) {

										if( 'undefined' !== typeof params['0'].for ) {

											var el_id = params['0'].for;
											var style_id = $("#cp_style_id").val();

											var id_html = "<div class='cp-param-inner'>";
											id_html += "<h2><label class='cpro_elm_id_label'>ID - </label><span class='cpro_elm_id'>" + el_id + "-" + style_id +"</span></h2>";
											id_html += "</div>";

											// Append ID info to HTML 
											$htmlData += id_html;
										}
									}

									$htmlData += '</div>';
								}
							});

							$htmlData += '</div>';

							$content_area.append( $htmlData );

							if( '' != selected_timezone && 'undefined' != typeof selected_timezone ) {
								$( '#timer_timezone option[value="' + selected_timezone + '"]' ).attr( "selected", "selected" );
							}
							
							if( 'cp_close_image' == field.id ) {
								jQuery('.cp-media-source').remove();
								jQuery('.cp-media-sizes').remove();
								jQuery('.custom-alt-container').remove();	
							}

							if( force_hide == false ) {

								ConvertProEditPanel._hideEditPanelOptions( $('.cp-edit-panel') );
								
								// add dependency data attribute
								$.each( dependency_elements , function(index, val) {

									if( $("#"+index).length > 0 ) {
										$("#"+index).closest(".cp-edit-panel-field").attr( "data-dependency", val  );
										
										if ( $('html').hasClass('cp-mobile-device') ) {
											$('.cp-edit-panel #cp-accordion h3.cp-mobile-show').first().addClass('active cp-active-link-color');
											$('.cp-edit-panel #cp-accordion h3.cp-mobile-show').first().next().slideDown('fast');
										}else{
											$('.cp-edit-panel #cp-accordion h3:first').addClass('active cp-active-link-color');
											$('.cp-edit-panel #cp-accordion h3:first').next().slideDown('fast');
										}

									} else if( 	$("[data-slider='"+ index +"']").length > 0 ) {
										$("[data-slider='"+ index +"']").closest(".cp-edit-panel-field").attr( "data-dependency", val  );
									}
								});

								$('#btn_step').html('');
								for ( var i = 1; i <= step_count; i++ ) {
								 	$('#btn_step').append( $('<option></option>').val(i).html(i) );
								}

								if( step_value !== '' ) {
									$('#btn_step').val(step_value);
								}

								// Load coloricker param
								$('.cs-wp-color-picker').cs_wpColorPicker("param");

								// Load slider param
								$(".cp-slider").cp_slider();

								// load shadow param 
								$('.cp-box-shadow-container').cp_box_shadow_param();

								$('.cp-edit-panel-wrapper .has-tip').each(function(i,tip){
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

								$.each( param_types, function(index, val) {
									 
									switch(val) {
									 		
									 	case "media":
									 		$('.cp-image-container').cp_image();
									 		break;
									 	case 'radio_image' :
											$('.cp-radio-image-wrapper').cp_radio_image();
											break;
										case 'text_align' :
											$('.cp-text-align-wrapper').cp_text_align();
											break;
										case 'icon' :
											$('.cp-icon-wrapper').cp_icon();
											break;
										case 'switch' :
											$('.cp-switch-container').cp_switch_param();
											break;
										case 'hidden' :
											$('.cp-hidden-param').cp_hidden();
											break;
										case 'border' :
											$('.cp-border-container').cp_border_param();
											break;
										case 'multiinput' :
											$('.cp-multiinput-container').cp_multiinput_param(true);
											break;	
										case 'number' :
											$('.cp-number-container').cp_number_param();
											break;	
									} 
								});
							}
						}
						return;
					}
				}
			});

			if( force_hide == false ) { // force_hide will be useful while dropping field
				$('.cp-field-html-data.edit-in-progress').removeClass('edit-in-progress');
				$('.cp-edit-panel').fadeIn(200);
				$(element).closest('.cp-field-html-data').addClass('edit-in-progress');
			}

			$(document).trigger( "cpro_after_edit_panel_open" );

		},

		_hideEditPanelOptions: function( element ) {
		
			element.find('.cp-accordion-content').each(function(i) {
				var hide_accordion = true;
				var $this = $(this);

				$this.find('.cp-param-inner').each(function() {
					var param_inner = $(this);

					if ( param_inner.hasClass('cp-mobile-show') ) {
						hide_accordion = false;
						return false;
					}

				});

				if ( hide_accordion == false ) {
					
					var acc_class =  $this.data('acc-class');
					
					element.find('.cp-accordion-title.' + acc_class).addClass('cp-mobile-show');
					$this.addClass('cp-mobile-show');
				}
			});
		},

		_close_edit_panel: function() {

			var editPanel = $('.cp-edit-panel'),
			inputPlaceholder = editPanel.find('#input_text_placeholder'),
			nextElement = inputPlaceholder.siblings();

			if( inputPlaceholder.length == 1 ) {
				if( inputPlaceholder.val() == '' ) {
					if( nextElement.length == 0 ) {
						inputPlaceholder.after( '<span class="cp-require-placeholder"></span>' );
					}
					return;
				}
			}
			editPanel.find('.cp-require-placeholder').remove();
			$(".cp-panel-item").removeClass('edit-in-progress');
			editPanel.fadeOut(200);

		}
	}

	ConvertProEditPanel.init();

})(jQuery);