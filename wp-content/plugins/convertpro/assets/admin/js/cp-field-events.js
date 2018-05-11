(function($) { 

// Initialize variables 

var cp_fields, // hold the fields which are use to build modal
	cp_params, // hold the params which are used in fields
	modal_data, // hold the actual modal data
	cp_params_url, // hold URL of params directory
	cp_params_url = $('#cp_params_url').val(),
	load_previous_data_json = {},
	currentDropItem,
	dropRespective_to_panel = false,
	cpDropItemProp = {},
	cp_drag_overlay_respctive = false,
	cp_ghost_dragging = false,
	cp_ghost_dragging_select = false,
	cp_move_field_timer,
	cp_tiny_timer;

panel_default_data = {};
var MIN_DISTANCE = 3; // minimum distance to "snap" to a guide
var guides = []; // no guides available ...	
var google_fonts_list = {};

// params array
var param_json = $('#cp_params').val();
cp_params = $.parseJSON(param_json);

$.each( cp_params, function(index, val) {	
	if( val.id == 'cp_font_par' ) {
		var font_options = val.options;	
		google_fonts_list = font_options.Google;
	}
});

// get fields
var CPFields_Inst = new CPFields('.list-group-item');

cp_fields = CPFields_Inst.getSimplyfyCPFields();

// Create new instance of panel model
bmodel = new BModel();

// Save params data in script tags 
$.each( cp_params, function(i, param){
	var type = param.type;
	var template = cp_params_url+type+'/template.html';
	$.get(template, function(response){
		$('body').append($('<script />', {
			html: response,
			type: 'text/template',
			id: 'param-template-'+type
		}));
	});
});

/**
 * JavaScript class for Convert Pro Field
 *
 * @since 1.0.0
 */

var ConvertProField = {

	/**
     * Initializes the all class variables and methods.
     *
     * @return void
     * @since 1.0.0
     */
    init: function() {
       	
       	// on click edit panel cancel
		$('#cp-cancel-modal-data').click(function(){
			$(document).trigger('cpro_close_edit_panel');
		});

		$(document)
			.on( 'click', '.panel-wrapper .cp-field-html-data', this._onFieldClick )
			.on( 'dblclick', '.panel-wrapper .cp-field-html-data', this._onFieldDblClick )
			.on( 'cpro_after_field_drag cpro_after_field_drop cp_field_dblclick_drop', this._applyFieldOptions )
			.on( 'change keyup input', '.cp-edit-modal-data', this._onOptionChange )
			.on( 'blur', '.cp-edit-modal-data', this._onOptionBlur )
			.trigger( 'cp_font_change', this._saveHeadingOptions )
			.on( 'cp_tmce_change', this._onTinyMceChange )

			.on( 'click', '.cp-clone-field', function() {
				var $element = jQuery('.cp-field-html-data.selected');
				$(document).trigger( 'clonePanelItem', [$element] );
			})

			.on( 'click', '.cp-delete-item', this._deleteField )

			// Delete panel item
			.on( 'removePanelItem', this._onDeleteField )

			.on( 'cp-datepicker-change', this._onDatePickerChange )

			.on( 'change', ".cp-rulsets-wrap #cp-accordion .cp-input", this._onRulesetInputChange )

			.on( 'cpro_remove_selected', function(){	
				$(".cp-field-html-data.selected").removeClass('selected')
			})

			// Clone panel item
			.on( 'clonePanelItem', this._cloneField );

		$( '.cp-edit-panel' )
			.on( 'change', 'select[name=exit_field_animation]', this._onAnimationChange )
			.on( 'change', 'select[name=field_animation]', this._onEntryAnimationChange );

    },

    _onRulesetInputChange: function( event ) {

    	var $this 			= $(this),
			field_name 		= $this.attr('name'),
			field_value 	= $this.val(),
			rulsets_wrap 	= $this.closest('.cp-rulsets-wrap'),
			active_rulset	= parseInt( rulsets_wrap.find('.cp-rulsets-active').attr('data-rulsets') ),
			rulesets_field	= rulsets_wrap.find('input[name=rulesets]');
			rulesets_value	= jQuery.parseJSON( rulesets_field.val() );

			rulesets_value[ active_rulset ][ field_name ] = field_value;
			rulesets_field.val( JSON.stringify( rulesets_value ) );

    },

    _onDatePickerChange: function( e, input, strTime ) {

    	if( input.selector == '#cp_start_date' || input.selector == '#cp_end_date' ) {

			var $this 			= input,
				field_name 		= $this.attr('name'),
				field_value 	= strTime,
				rulsets_wrap 	= $this.closest('.cp-rulsets-wrap'),
				active_rulset	= parseInt( rulsets_wrap.find('.cp-rulsets-active').attr('data-rulsets') ),
				rulesets_field	= rulsets_wrap.find('input[name=rulesets]');
				rulesets_value	= jQuery.parseJSON( rulesets_field.val() );

				rulesets_value[ active_rulset ][ field_name ] = field_value;
				rulesets_field.val( JSON.stringify( rulesets_value ) );
		}
	},

    _onOptionChange: function() {

    	var element = $(this);
		if( $(this).attr('name') != 'custom_html_content' ) {
			setTimeout(function() {
				ConvertProField._applyPanelProperties(element);
				ConvertProEditPanel._handleDependency();
			}, 300 );
		}
    },

    _onOptionBlur: function() {

    	var element = $(this);
		if( $(this).attr('name') == 'custom_html_content' ) {
			setTimeout(function() {
				ConvertProField._applyPanelProperties(element);
				ConvertProEditPanel._handleDependency();
			}, 300 );
		}
    },

    _onEntryAnimationChange: function() {

    	var selector 		= $(this).attr('for'),
			fieldSelector 	= $( '#'+selector );

		setTimeout(function(){
			entryAnim = 'cp-animated ' + fieldSelector.attr('data-anim-class');
			fieldSelector.css({'animation-delay':'0s'}).addClass( entryAnim );
		
			var duration = fieldSelector.attr('data-anim-duration'),
				durationInMS = parseInt(duration);

			if( duration.indexOf('ms') < 0 ) {
				durationInMS *= 1000;		
			}

			setTimeout(function(){
				fieldSelector.removeClass( entryAnim );
				fieldSelector.removeClass('cp-animated');
			}, durationInMS + 200 );

		}, 400);
    },

    _onAnimationChange: function() {

    	var selector 	  = $(this).attr('for'),
			fieldSelector = $( '#'+selector ),
			entryAnim 	  = fieldSelector.attr('data-anim-class'),
			exitAnim 	  = fieldSelector.attr('data-exit-anim-class');
				
		fieldSelector.removeClass(entryAnim);
		fieldSelector.removeClass(exitAnim);

		setTimeout(function(){
			exitAnim = fieldSelector.attr('data-exit-anim-class');
			fieldSelector.css({'animation-delay':'0s'}).addClass( exitAnim );
		
			var duration = fieldSelector.attr('data-anim-duration'),
				durationInMS = parseInt(duration);

			if( duration.indexOf('ms') < 0 ) {
				durationInMS *= 1000;		
			}

			setTimeout(function(){
				fieldSelector.removeClass( exitAnim );
			}, durationInMS + 200 );

		}, 400);

    },

    _cloneField: function( e, element ) {

    	var type = $(element).attr('data-type'); // get type of field
		var element_id = $(element).attr('id'); // get id of clone item
		var animationClass = $(element).attr('data-anim-class'); // get Animation Class of field
		var panel_id = step_id + 1;
		var dynamic_id = ConvertProHelper._createItemID(type); // create new ID for item
		var clone = $('#'+element_id).clone(); // clone panel item
		var cloneStyle = $(clone).find("style#"+element_id+"_cp-target");

		$(clone).attr('id', dynamic_id);
		$(clone).removeClass( animationClass ); // Do not Animate on Clone
		$(clone).find(".cp-target").attr('id', dynamic_id + "-content" );

		// Update Clone style Content & Id
		cloneStyle.attr('id', dynamic_id + "_cp-target" );
		var cloneStyleTemp = cloneStyle.html();

		cloneStyleTemp = cloneStyleTemp.replace( new RegExp('#'+element_id, 'g'), '#'+dynamic_id );
		cloneStyle.html( cloneStyleTemp );

		$('.panel-'+panel_id+'-content-wrapper').append(clone);

		// set clonned item next to current item
		var position = $(clone).position();
		var height = $(clone).height();

		var positionLeft = position.left
		var positionTop  = position.top;
		positionTop += 20;
		positionLeft += 20;

		$(clone).css( 'top', positionTop + 'px' );
		$(clone).css( 'left', positionLeft + 'px' );
		bmodel.appendToModalData(clone); // append clonned item to modal data

		// get modal data of clone item
		var element_modal_data = bmodel.getElementModalData(element_id, step_id);

		var temp_clone = {};
		var temp_clone_position = {};
		
		$.extend(true, temp_clone, element_modal_data);

		var replace_name_option = 'input_text_name';

		switch( temp_clone['type'] ) {

			case "cp_text":
				var input_name = 'textfield';
			break;
			case "cp_number":
				var input_name = 'numberfield';
			break;
			case "cp_dropdown":
				replace_name_option = 'dropdown_name';
				var input_name = 'dropdownfield';
			break;
			case "cp_textarea":
				var input_name = 'textarea';
			break;
			case "cp_radio":
				replace_name_option = 'radio_name';
				var input_name = 'radiofield';
			break;
			case "cp_checkbox":
				replace_name_option = 'checkbox_name';
				var input_name = 'checkboxfield';
			break;
		}

		var new_input_name = input_name + '_' + Math.floor(1000 + Math.random() * 9000); 

		if( typeof temp_clone[replace_name_option] !== 'undefined' ) {
			temp_clone[replace_name_option] = new_input_name;

			jQuery("#" + dynamic_id ).find(".cp-target").attr( "name", new_input_name );
		}

		bmodel.setElementModalData(dynamic_id, temp_clone);

		// save position later
		if( typeof position != 'undefined' ) {
			temp_clone_position['x'] = positionLeft;
			temp_clone_position['y'] = positionTop;
			bmodel.setModalValue( dynamic_id, step_id, 'position', temp_clone_position, false, true);
		}

		var zIndex = cpLayers.getMaxZIndex("cp-field-html-data");
		zIndex = parseInt( zIndex ) + 1;

		bmodel.setModalValue( dynamic_id, step_id, 'layerindex', zIndex, false );

		$(clone).css( 'z-index', zIndex );

		var rotation_angle 	= bmodel.getModalValue(dynamic_id, step_id, 'rotate_field');

		ConvertProHelper._rotateField( $( '#'+dynamic_id + " .cp-rotate-wrap" ), rotation_angle );

		$( element ).removeClass( 'selected cps-selected cp-resizable-active' );
		$( element ).find(".ui-resizable-handle").removeClass('show');

		$( '#'+dynamic_id).removeClass('edit-in-progress');

		$(document).trigger( 'cpro_open_edit_panel', [ $( '#'+dynamic_id), false ] );

		$( document ).trigger( 'cpro_after_clone_field' );

		bmodel.setUndo( dynamic_id, 'clone' );

    },

    _deleteField: function() {

    	if( jQuery(".cp-field-html-data.selected").length > 0 ) {
			var $element_id = jQuery(".cp-field-html-data.selected").attr("id");
			$(document).trigger('removePanelItem',[$element_id, 'delete_element']);
		}
		
		var selectedElement = $('.panel .cps-selected, .panel .cp-field-html-data.selected ');
		if ( selectedElement.length > 0 ) {
			selectedElement.each(function(i) {
				var $element_id = $(this).attr("id");
				$(document).trigger('removePanelItem',[$element_id, 'delete_element', false]);
			});

			var id = step_id + 1;
	        var parent = $('.panel-wrapper').find('.panel-'+id+'-content-wrapper');
			
			ConvertProGrouping._releaseBigGhost( parent );

			bmodel.setUndo( true, 'load_exist' );
		}

		var id = step_id + 1;
		$( '#panel-' + id).find(".cp-big-ghost").remove();

    },

    _onDeleteField: function( e, element, action, is_undo ) {

    	if( action == 'delete_element' ){
			var element_id = element;
		}
		
		if( jQuery(element).hasClass(".cp_has_editor") ) {
			var tiny_instance_id =  $("#"+element_id).find(".cp-target").attr('id');
			// remove TinyMCE instance 
			tinymce.remove("#"+tiny_instance_id);
		}

		var current_step = step_id;
		if( jQuery("#"+ element_id ).attr("data-overlay-respective") == 'true' ) {
			current_step = 'common';
		}

		$(document).trigger('beforeDeletePanelItem');
		
		var is_deleted = bmodel.removeFromModalData( element_id, current_step, is_undo );
		if( is_deleted == true || is_deleted == 'true' ) {
			if( $( '#' + element_id ).hasClass( 'cp-countdown-field' ) ) {
				$( '#' + element_id ).find( '.cp-target.cp-countdown' ).countdown( 'destroy' );
			}
			$( '#' + element_id ).remove();
		}

		$('.cp-layer-wrapper').addClass('cp-hidden');
		$(document).trigger('cpro_close_edit_panel');

    },

    _onFieldClick: function(e) {

    	$this = $( this );

		if( $this.hasClass("cp-tiny-active") ) {
			return;
		}

		// Remove text selection
		if ( window.getSelection ) {
			if ( window.getSelection().empty ) {  // Chrome
				window.getSelection().empty();
			} else if ( window.getSelection().removeAllRanges ) {  // Firefox
				window.getSelection().removeAllRanges();
			}
		} else if ( document.selection ) {  // IE?
			document.selection.empty();
		}
		
		$(".panel-wrapper").focus();

		e.stopPropagation();

		if ( e.metaKey || e.ctrlKey ) {
			
			$this.toggleClass('cps-selected');
			ConvertProGrouping._releaseBigGhost( $this.closest( '.panel-content-wrapper' ) );
			ConvertProGrouping._generateBigGhost( $this.closest( '.panel-content-wrapper' ), true );
		
		} else {

			// Remove multiselection
			$(".cps-selected").removeClass('cps-selected');
			ConvertProGrouping._releaseBigGhost( $this.closest( '.panel-content-wrapper' ) );

			$(".cp-panel-item").removeClass('selected');
			$this.addClass('selected');

			if ( $(this).hasClass('cp-invisible-on-mobile') && $('html').hasClass('cp-desktop-device') ) {
				$('.cp-layer-wrapper').addClass('invisible-mobile');
			} else {
				$('.cp-layer-wrapper').removeClass('invisible-mobile');
			}
			
			$('.cp-layer-wrapper').removeClass('cp-hidden');

			if( $this.hasClass("cp-resize-element") ) {

				// display resizable handlers 
				$(document).find(".ui-resizable-handle").removeClass('show');
				$this.addClass('cp-resizable-active');
				$this.find(".ui-resizable-handle").addClass('show');
				$this.addClass('cp-resizable-active');

				var id      = $this.attr("id"),
					width   = parseInt( $this.width() ),
					height  = parseInt( $this.height() ),
					tooltip_text = "W:"+width+" H:"+height;

				$this.find(".tooltip-wrapper").find("span").html(tooltip_text);
				
				// intialise resize handlers
				ConvertProHelper._setResizeHandlerPosition( width, height, $this );
				
				$(document).trigger('initEditPanel', [ id ]);

				$( ".cp-field-html-data.cp-tiny-active" ).each(function( index ) {

					$this = jQuery(this);
					var tinymce_selector_id = $this.find(".cp-target").attr("id");
					var has_existing_editor = tinymce.get( tinymce_selector_id );

					$this.removeClass("cp-tiny-active");

					if( has_existing_editor !== null ) {
						tinymce.remove( "#" + tinymce_selector_id );
					}
				});
			}

			$this.draggable('enable');
		}
		
		if( !$this.hasClass( 'edit-in-progress' ) && e.keyCode != 46 && e.keyCode != 8 ) {
			$(document).trigger( 'cpro_open_edit_panel', [ this, false ] );
		}

    },

    _onFieldDblClick: function(e) {

    	if( jQuery(this).hasClass("cp_has_editor") ) {

			e.preventDefault();

			jQuery(this).find(".cp-target").css( "cursor", "auto");
			var container_id = jQuery(this).find(".cp-target").attr("id");

			jQuery(this).draggable('disable');
			jQuery(this).addClass("cp-tiny-active");

			ConvertProField._initializeTinymce( container_id );

		}

    },

    /*
	 * Apply edit panel fields options 	
	*/
	_applyFieldOptions: function( e, applyPosition, applyRespective, setModalobject ) {

		var temp = {},
			map_value_setting = {},
			for_edit, type,
			panel_id = step_id + 1,
			current_step = step_id,
			is_respective_to_panel = false,
			obj_to_save = [];

		$('.cp-edit-modal-data').each(function(i, element){

			for_edit = $(element).attr('for');
			var name = $(element).attr('name');
			var value = $(element).val();		
			type = $('#'+for_edit).attr('data-type');	

			// for radio button type field
			if( name == 'btn_text_align' || name == 'close_text_align' ) {
				value = jQuery('input[name="' + name + '"]:checked').val();
			}	

			if( 'panel' != type ) {
				if( "font" == $(element).attr('data-type') ) {
					var font_family = $(element).siblings('.cp-font-weights').val();
					font_family = font_family == 'regular' ? "normal" : font_family;
					value = $(element).val() + ":" + font_family;
				}

				if( typeof type != 'undefined' && typeof for_edit !== 'undefined' ) {	

					// load current settings to appropriate element
					var map_style = CPFields_Inst.getMapStyle(type, name);
				
					if( typeof map_style != 'undefined' ) {
						temp[i] = {};
						var parameter = (typeof map_style.parameter === 'undefined' || map_style.parameter === '') ? false : map_style.parameter.replace(/_/g, '-');
						var onhover = (typeof map_style.onhover === 'undefined' || map_style.onhover === '') ? false : map_style.onhover;
						var target = (typeof map_style.target === 'undefined' || map_style.target === '') ? false : map_style.target;
						var unit = (typeof map_style.unit === 'undefined' || map_style.unit === '') ? '' : map_style.unit;
						
						if( ( parameter !== false && !setModalobject ) || typeof setModalobject == 'undefined' ) {
							ConvertProHelper._applySettings( for_edit, parameter, value, unit, onhover, target, step_id );
						}

						temp[i]['name'] = name;
						temp[i]['parameter'] = parameter;
						temp[i]['onhover'] = onhover;
						temp[i]['target'] = target;
						temp[i]['unit'] = unit;
					}

					var map = CPFields_Inst.getMapValue(type, name);

					if( typeof map != 'undefined' ) {
						map_value_setting[i] = {};

						if( setModalobject || typeof setModalobject == 'undefined' ) {
							var save_panel_position = false;
						}
						ConvertProHelper._applyMapValues( for_edit, map, value, applyPosition, true, applyRespective, setModalobject, step_id );

						map_value_setting[i]['name'] = name;
						map_value_setting[i]['attr'] = map.attr;
						map_value_setting[i]['target'] = map.target;
					}

					if( setModalobject || typeof setModalobject == 'undefined' ) {

						var fieldx = temp_field = {};
						fieldx = CPFields_Inst.getCPFieldByID(type);

						$.each(fieldx, function(k, v){
							temp_field[k] = v;
						});
						
						$.each( temp_field.sections, function( k, v ) {
							$.each( v.params, function( index, val ) {
								if( val.id == 'respective_to' ) {
									is_respective_to_panel = val.default_value;
								}
							});
						});
						
						if( is_respective_to_panel == true ) {
							current_step = 'common';
						}

						var temp_obj = {};
						temp_obj['for_edit'] = for_edit;
						temp_obj['current_step'] = current_step;
						temp_obj['name'] = name;
						temp_obj['value'] = value;
						obj_to_save.push(temp_obj);
					}
				}
			}

		});
		
		var is_form_input_field = jQuery("#" + for_edit ).find(".cp-target").hasClass("cp-form-input-field");

		if( is_form_input_field ) {

			var form_field_data = bmodel.getElementModalData( 'form_field', 0 );
			var form_map_style = form_field_data.map_style;

		 	if( typeof form_map_style != 'undefined' ) {
				$.each( form_map_style, function( i, style_data ){

					var parameter = ( typeof style_data.parameter === 'undefined' || style_data.parameter === '' ) ? false : style_data.parameter;
					var onhover = ( typeof style_data.onhover === 'undefined' || style_data.onhover === '' ) ? false : style_data.onhover;
					var target = ( typeof style_data.target === 'undefined' || style_data.target === '' ) ? false : style_data.target;
					var unit = ( typeof style_data.unit === 'undefined' || style_data.unit === '' ) ? '' : style_data.unit;
					var value = bmodel.getModalValue( 'form_field', 0, style_data.name );

					if( parameter !== false ) {

						ConvertProHelper._applySettings( for_edit, parameter, value, unit, onhover, '.cp-target', 0 );
					}
				});
			}
		}

		if( setModalobject || typeof setModalobject == 'undefined' ) {
			// save value in modal data
			bmodel.setModalValue( for_edit, current_step, 'map_style', temp, false );
			bmodel.setModalValue( for_edit, current_step, 'map', map_value_setting, false );
			bmodel.setModalValue( for_edit, current_step, 'type', type, false );
			bmodel.setAllModalValue( obj_to_save );

			if ( for_edit != false && $('#'+for_edit).attr('data-type') == 'cp_countdown' ) {
				ConvertProHelper._applyCountdown( $('#'+for_edit) );
			}
		}

	},

	/**
	 * Apply properties for specific element
	*/
	_applyPanelProperties: function( element ) {

		var temp = {};
		var map_value_setting = {};
		var for_edit, type;
		var panel_id = step_id + 1;
			
		for_edit = $(element).attr('for');
		var name = $(element).attr('name');
		var value = $(element).val();		
		type = $('#'+for_edit).attr('data-type');	

		if( name == 'input_text_placeholder' ) {
			if( $( element ).siblings().length > 0 && $.trim( $( element ).val() ) != '' ) {
				$( element ).siblings().remove();
			}
		}

		if( "font" == $(element).attr('data-type') ) {
			var font_family = $(element).siblings('.cp-font-weights').val();
			font_family = font_family == 'regular' ? "normal" : font_family;
			value = $(element).val() + ":" + font_family;
		}

		if( typeof type == 'undefined' ){			
			type = 'panel';		
			for_edit = "panel-"+panel_id; 					
		}

		// load current settings to appropriate element
		var map_style = CPFields_Inst.getMapStyle(type, name);			
		if( typeof map_style != 'undefined' ) {
			var parameter = (typeof map_style.parameter === 'undefined' || map_style.parameter === '') ? false : map_style.parameter.replace(/_/g, '-');
			var onhover = (typeof map_style.onhover === 'undefined' || map_style.onhover === '') ? false : map_style.onhover;
			var target = (typeof map_style.target === 'undefined' || map_style.target === '') ? false : map_style.target;
			var unit = (typeof map_style.unit === 'undefined' || map_style.unit === '') ? '' : map_style.unit;
			if( parameter !== false ) {
				ConvertProHelper._applySettings( for_edit, parameter, value, unit, onhover, target, step_id, null, undefined, null, true );
			}
		}
		
		if( name == 'respective_to' ) {

			var modal_data_array = _.clone(bmodel.get('panel_data'));

			if( value == 'true' ) {

				if( typeof modal_data_array['common'] == 'undefined' ) {
					modal_data_array['common'] = {};	
				} 

				modal_data_array['common'][for_edit] = modal_data_array[step_id][for_edit];
				delete modal_data_array[step_id][for_edit];

				jQuery("#" + for_edit).addClass("cpro-overlay-field");

			} else {

				if( typeof modal_data_array['common'] !== 'undefined' ) {
					if( typeof modal_data_array['common'][for_edit] !== 'undefined' ) {

						modal_data_array[step_id][for_edit] = modal_data_array['common'][for_edit];
					
						delete modal_data_array['common'][for_edit];
					}
				}

				jQuery("#" + for_edit).removeClass("cpro-overlay-field");
			}

			bmodel.set( 'panel_data', modal_data_array );
		}

		var map = CPFields_Inst.getMapValue(type, name);

		if( typeof map != 'undefined' ) {
			ConvertProHelper._applyMapValues( for_edit, map, value, true, true, true, true, step_id );
		}

		var current_step = step_id;

		if( jQuery("#" + for_edit ).attr("data-overlay-respective") == 'true' ) {
			current_step = 'common'; 
		}

		if ( type == 'cp_heading' || type == 'cp_sub_heading' || type == 'cp_image' || type == 'cp_close_image' ) {

			var el_width = $( '#'+for_edit ).outerWidth();
			var el_height = $( '#'+for_edit ).outerHeight();

			if ( ( type == 'cp_image' || type == 'cp_close_image' ) && 'width' != name ) {
				if( el_width > 700 ) {
					el_width = 700;	
					if( type == 'cp_image' ) {
						$('#'+for_edit + ' img.cp-image').css( 'width', el_width );
					} else {
						$('#'+for_edit + ' img.cp-close-image').css( 'width', el_width );
					}
				}
			}

			bmodel.setElementID( current_step, for_edit );
			bmodel.setModalValue( for_edit, current_step, 'width', el_width, false );
			bmodel.setModalValue( for_edit, current_step, 'height', el_height, false );
		}

		bmodel.setElementID( current_step, for_edit );
		
		// save value in modal data
		bmodel.setModalValue( for_edit, current_step, name, value );
		if( typeof map_style !== 'undefined' && typeof map_style.parameter !== 'undefined' ) {
			bmodel.setModalStyleValue( for_edit, name, parameter, current_step, unit, onhover, target );
		}

		if( typeof map !== 'undefined' && typeof map.attr !== 'undefined' ) {	
			bmodel.setModalMapValue( for_edit, name, map.attr, current_step, map.target );
		}

	},

	_initializeTinymce: function( container_id ) {
	
		tinymce.init({
			selector: "#" + container_id,
			// theme: 'inlite',
			plugins: 'lists link paste',
			paste_as_text: true,
			// insert_toolbar: 'quickimage quicktable',
			// event_root: ".panel-wrapper",
			// selection_toolbar: 'bold italic strikethrough underline | quicklink bullist numlist',
			toolbar1: 'bold italic underline strikethrough | bullist numlist | link',
			inline: true,
			relative_urls : false,
			remove_script_host : false,
			convert_urls : true,
			mode : "exact",
			paste_as_text: true,
			menubar: false,
			// auto_focus: 'editable',
			setup: function (editor) {
				editor.on('nodeChange', function (e) {
					var id = editor.id;
					ConvertProField._saveHeadingOptions( container_id );
				});
				editor.on('Paste', function (e) {
					var id = editor.id;
					ConvertProField._saveHeadingOptions( container_id );
				});
				editor.on('keyup', function (e) {
				  	var id = editor.id;
					if( e.key.length == 1 || e.which == 46 || e.which == 8 || e.which == 13 ) {
				    	ConvertProField._saveHeadingOptions( container_id );
					}
				});
				editor.on('PostProcess', function (e) {
					
				});
				editor.on('dblclick', function (e) {
				  	// on double click
				}); 
				editor.on('focus', function (e) {
				   	// on focus
				});  	        
			}
		});

		var has_focus = jQuery("#" + container_id).hasClass("mce-edit-focus");

		setTimeout(function() {

			if( !has_focus ) {
				var ele = document.getElementById(container_id);
				var range = document.createRange();
				range.selectNodeContents(ele);
				var sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			}

		}, 300 );
	},

	_saveHeadingOptions: function( container_id ) {

		clearTimeout( cp_tiny_timer );
		cp_tiny_timer = setTimeout(function() {
			var target_el = $( "#" + container_id );
			var html_el = target_el.closest(".cp-field-html-data");
			var el_id = html_el.attr("id");
			var current_step = step_id;
			var el_width = target_el.width();
			var el_height = target_el.height();
			bmodel.setModalValue( el_id, current_step, 'width', el_width, false );
			bmodel.setModalValue( el_id, current_step, 'height', el_height, false );
			$(document).trigger('cp_tmce_change',[container_id]);
		}, 200 );
	},

	_onTinyMceChange: function( event, element_id ) {

		var for_edit = element_id.replace( "-content", "" );
		var name = "text_content";

		if( typeof tinyMCE.activeEditor !== 'undefined' && tinyMCE.activeEditor !== null && tinyMCE.activeEditor.id == element_id ) {

			var value = tinyMCE.activeEditor.getContent();

			value = encodeURI( value );

			bmodel.setElementID( step_id, for_edit );
			// save value in modal data
			bmodel.setModalValue( for_edit, step_id, name, value );
		}

	}
}

ConvertProField.init();

})(jQuery);
