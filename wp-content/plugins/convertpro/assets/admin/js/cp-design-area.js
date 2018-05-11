var ConvertProGrouping = '';
var ConvertProDragDrop = '';
var ConvertProResize   = '';
var ConvertProPanel    = '';

(function($) { 

	// get fields
	var CPFields_Inst = new CPFields('.list-group-item');

	cp_fields = CPFields_Inst.getSimplyfyCPFields();

	var currentDropItem,
		dropRespective_to_panel = false,
		cpDropItemProp = {},
		cp_drag_overlay_respctive = false,
		cp_ghost_dragging = false,
		cp_ghost_dragging_select = false,
		MIN_DISTANCE = 3, // minimum distance to "snap" to a guide
		guides = []; // no guides available ...

	ConvertProDragDrop = {

		init: function() {

			$( document )
				.ready( this._ready )
				.on( 'cpro_switch_panel cpro_after_clone_field', this._initPanelDrag )
				.on( 'cpro_customizer_loaded', this._inititializeDraggable )
				.on( 'cpro_after_undo cpro_after_redo cpro_after_apply_device_data cpro_after_clone_field cpro_switch_panel', this._initPanelItemDrag )

				.on('cpro_init_drag', function( event, selector, obj ){
					if( selector === null || selector === '' )
						return;
					obj.cancel = null;
					$( selector ).draggable(obj);
				})

				.on( 'cpro_init_drop_on_panel', function() {
					$(".panel-wrapper").droppable({
						accept: '.cp-droppable-item,.cp-preset-field',
						drop: function(event, ui) {
							ConvertProDragDrop._afterDropField( $(this), event, ui );
						}
					});
				})
		},

		// Init cp-panel-item drag
		_initPanelItemDrag: function() {

			var id = step_id + 1;
			var obj = {
				containment: $(".cp-live-design-area"),
				addClasses: 'in-moving',	
				cancel: 'please-drag',
				start: function( event, ui ) {
					
					$('.cp-field-html-data').removeClass('selected');
					
					var overlay_el = $(this).attr( 'data-overlay-respective' );

					if ( overlay_el == 'true' ) {
						cp_drag_overlay_respctive = true;
					}else{
						guides = $.map( $( '#panel-'+id+" .cp-panel-item" ).not( this ), ConvertProHelper._computeGuidesForElement );
					}
				},
				drag: function(event, ui) {

					$(this).removeClass('selected');
					jQuery(".ui-resizable-handle").removeClass("show");
					
					var $t = $(this);
					
					if ( !cp_drag_overlay_respctive ) {
						ConvertProDragDrop._iterateGuidelines( $t, ui );
						ConvertProHelper._setPositionTooltip( event, 'drag', ui.position.left, ui.position.top );
					}
				},
				stop: function( event, ui ){

					var $this = $(this);

					if ( !cp_drag_overlay_respctive ) {
						$( "#guide-v, #guide-h" ).hide();
					} else {
						cp_drag_overlay_respctive = false;
					}

					ConvertProDragDrop._savePanelItemPosition( $this, false );

					if ( ! $this.hasClass( 'edit-in-progress' ) ) {
			        	$this.trigger('click');
					} else {
						$this.addClass('selected');
						$this.find(".ui-resizable-handle").addClass('show');
					}

					$(".tooltip-wrapper").hide();
					$('.panel-wrapper').focus();

			   	}
			};

			$(document).trigger( 'cpro_init_drag', ['.cp-panel-field', obj] );
		},

		_ready: function() {

			$('.draggable').dblclick(function( e ){
				ConvertProDragDrop._dblclick_drop( $(this), e, ui = false );
		    });
		},

		_inititializeDraggable: function() {

			$(document).trigger('cpro_init_drop_on_panel');

			ConvertProDragDrop._initPanelDrag();

			var obj = {
				handle: ".cp-layer-draggable"
			}

			$(document).trigger('cpro_init_drag', ['.cp-layer-wrapper', obj]);

			obj = {
				handle: ".cp-multistep-draggable"
			}

			$(document).trigger('cpro_init_drag', ['.cp-steps-wrapper', obj]);

			// call init panel item
			ConvertProDragDrop._initPanelItemDrag();

		},

		// Init cp-panel-item drag
		_initPanelItemDrag: function() {

			var id = step_id + 1;
			var obj = {
				containment: $(".cp-live-design-area"),
				addClasses: 'in-moving',	
				cancel: 'please-drag',
				start: function( event, ui ) {
					
					$('.cp-field-html-data').removeClass('selected');
					
					var overlay_el = $(this).attr( 'data-overlay-respective' );

					if ( overlay_el == 'true' ) {
						cp_drag_overlay_respctive = true;
					} else {
						guides = $.map( $( '#panel-'+id+" .cp-panel-item" ).not( this ), ConvertProHelper._computeGuidesForElement );
					}
				},
				drag: function(event, ui) {

					$(this).removeClass('selected');
					jQuery(".ui-resizable-handle").removeClass("show");
					
					var $t = $(this);
					
					if ( !cp_drag_overlay_respctive ) {
						ConvertProDragDrop._iterateGuidelines( $t, ui );
						ConvertProHelper._setPositionTooltip( event, 'drag', ui.position.left, ui.position.top );
					}
				},
				stop: function( event, ui ){

					var $this = $(this);
					if ( !cp_drag_overlay_respctive ) {
						$( "#guide-v, #guide-h" ).hide();
					} else {
						cp_drag_overlay_respctive = false;
					}

					ConvertProDragDrop._savePanelItemPosition( $this, false );

					if ( ! $this.hasClass( 'edit-in-progress' ) ) {
			        	$this.trigger('click');
					} else {
						$this.addClass('selected');
						$this.find(".ui-resizable-handle").addClass('show');
					}

					$(".tooltip-wrapper").hide();
					$('.panel-wrapper').focus();
			   	}
			};
			$(document).trigger('cpro_init_drag', ['.cp-panel-field', obj]);
		},

		/**
		 * While dragging field
		*/
		_prepareDragPanelObject: function ( this_obj, event, ui ) {

			var dragObj = $(ui.helper);
			cpDropItemProp[ 'formField' ] = false;
			
			// collect field attributes
			var fieldType 				= dragObj.data('type'),
				fieldValue 				= dragObj.data('value'),
				fieldPreset 			= dragObj.data('preset'),
				fieldTitle 				= dragObj.data('field-title'),
				is_preset_field		 	= dragObj.hasClass("cp-preset-field"),
				is_respective_to_panel  = false,
				templateData            = $( "#field-template-" + fieldType ).html(),
				force_hide_edit_panel   = true,
				applyPosition 	        = false,
				applyRespective         = false;
				fieldx 					= {},
				temp 					= {};	

			if ( !dragObj.hasClass('cp-field-html-data') || !dragObj.hasClass('cp-shapes') ) {
				cpDropItemProp[ 'formField' ] = true;
				dragObj.addClass('cp-drag-adjust-css').removeClass('list-group-item');
			}

			/* Update Shape SVG Template */
			if( undefined !== fieldPreset && '' !== fieldPreset ) {
				var data_content = dragObj.data('content');
				templateData = templateData.replace( '{{svg_content}}', data_content );
			}

			fieldTypeName = typeof dragObj.data("name") !== 'undefined' ? dragObj.data("name") : fieldType;

			fieldx = CPFields_Inst.getCPFieldByID( fieldType );

			$.each( fieldx, function(k, v){
				temp[k] = v;
			});
			
			$.each( temp.sections, function( k, v ) {
				$.each( v.params, function( index, val ) {
					if( val.id == 'respective_to' ) {
						dropRespective_to_panel = val.default_value;
						is_respective_to_panel 	= val.default_value;
					}		
				});
			});

			var dynamic_id = ConvertProHelper._createItemID( fieldx.id, fieldType );

			temp.id    = dynamic_id;
			temp.type  = fieldType;
			temp.name  = fieldType;
			temp.title = fieldTitle;
			temp.is_preset_field = is_preset_field;	

			if( is_preset_field ) {
				temp.preset = fieldPreset.replace( '-', '_' );
			}

			templateData = ConvertProEditPanel._renderTemplate( templateData, temp, fieldValue );

		  	var htmlData = $(templateData).children();

			if ( true == cpDropItemProp.formField ) {
				dragObj.html(htmlData);
			} else {
				dragObj.replaceWith( htmlData );
			}

			$(document).trigger( 'cpro_open_edit_panel', [ jQuery("#"+dynamic_id), force_hide_edit_panel, applyRespective, fieldTypeName ] );
			$(document).trigger( 'cpro_after_field_drag', [ applyPosition, applyRespective, false ] );

			cpLayers.assignZIndex( $('#'+dynamic_id).parent(), 'cp-field-html-data', false );

			$('#'+dynamic_id).find(".cp-target").attr( "id", dynamic_id + "-content" );	

			if( is_respective_to_panel ) {
				$('#'+dynamic_id).addClass('cpro-overlay-field');			
			}

			if( fieldType == 'cp_shape' || fieldType == 'cp_dual_color_shape' ) {
				// Set preset
				cpLayers.assignShapePreset( $('#'+dynamic_id), fieldPreset );
			}

			currentDropItem = dragObj;
		},

		/**
		 * After drop of field on panel
		*/
		_afterDropField: function( this_obj, event, ui ) {

			var current_step 	    = step_id,
				current_panel       = $('.panel-' + ( current_step + 1 ) + '-content-wrapper' ),
				panel_container_id  = step_id + 1,
				applyPosition 	    = false,
				applyRespective     = false,
				dropItem 		    = currentDropItem,
				resize 			    = $(ui.draggable).data('resize'),
				temp_clone_position = {};

			if( dropRespective_to_panel ) {
				var $droppedElement     = $(".panel-wrapper");
				var $newPosX 		    = ui.position.left;
				var $newPosY 		    = ui.position.top;
			} else {

				var $droppedElement     = $( '.panel-content-wrapper.panel-' + ( current_step + 1 ) + '-content-wrapper' );
				var $newPosX 		    = ui.position.left - ( current_panel.position().left + parseInt(current_panel.css('border-left-width')) );
				var $newPosY 		    = ui.position.top - current_panel.position().top;
			}

			var percentLeft         = $newPosX;
			var precentTop          = $newPosY;

			// If it is a form field
			if ( true == cpDropItemProp.formField ) {
				dropItem = $(currentDropItem.html());
			}

			dropItem.addClass("cp-panel-item").removeClass("ui-draggable draggable");
			temp_clone_position['x'] = percentLeft;
			temp_clone_position['y'] = precentTop;

			dropItem.css({
				"left": temp_clone_position['x'] + "px",
				"top": temp_clone_position['y'] + "px"					
			});

			$droppedElement.append(dropItem);

			var dynamic_id  = dropItem.attr('id');

			this._setDroppedField( dropItem, temp_clone_position, current_step, resize );
			
		},

		_dblclick_drop: function( this_obj, event, ui ) {

			var current_step = step_id; 
			if ( 'dblclick' == event.type ) {
				var $newPosX = 20;
				var $newPosY = 20;
				ui = {};
				ui.draggable = this_obj;
			} else {
				var $newPosX = ui.offset.left - this_obj.offset().left;
				var $newPosY = ui.offset.top - this_obj.offset().top;
			}

			var percentLeft = $newPosX;
			var precentTop  = $newPosY;

			$droppedElement = $( '.panel-content-wrapper.panel-' + ( current_step + 1 ) + '-content-wrapper' );
			$(".panel .list-group-item").addClass("cp-panel-item");
			$(".cp-panel-item").removeClass("ui-draggable draggable list-group-item");
			
			// collect field attributes
			var fieldType    	= $(ui.draggable).data('type');
			var resize       	= $(ui.draggable).data('resize');
			var fieldValue   	= $(ui.draggable).data('value');
			var fieldPreset  	= $(ui.draggable).data('preset');
			var fieldTitle   	= $(ui.draggable).data('field-title');
			var is_preset_field = $(ui.draggable).hasClass("cp-preset-field");
			var templateData    = $("#field-template-" + fieldType ).html();

			/* Update Shape SVG Template */
			if( undefined !== fieldPreset && '' !== fieldPreset ) {
				var data_content = $(ui.draggable).data('content');
				templateData = templateData.replace( '{{svg_content}}', data_content );
			}

			var fieldx = temp = {};
			fieldx = CPFields_Inst.getCPFieldByID(fieldType);

			$.each(fieldx, function(k, v){
				temp[k] = v;
			});

			var is_respective_to_panel = false;

			$.each( temp.sections, function( k, v ) {
				$.each( v.params, function( index, val ) {
					if( 'respective_to' == val.id ) {
						is_respective_to_panel = val.default_value;
					}
				});
			});

			var temp_clone_position      = {};
				temp_clone_position['x'] = percentLeft;
				temp_clone_position['y'] = precentTop;
				dynamic_id               = ConvertProHelper._createItemID(fieldx.id, fieldType),
				current_step             = step_id;

			temp.id = dynamic_id;
			temp.type = fieldType;
			temp.name = fieldType;
			temp.title = fieldTitle;
			temp.position = temp_clone_position;
			temp.is_preset_field = is_preset_field;	

			if( is_preset_field ) {
				temp.preset = fieldPreset.replace( '-', '_' );
			}

			templateData = ConvertProEditPanel._renderTemplate( templateData, temp, fieldValue );

		  	var htmlData = $(templateData).children();
			
			$droppedElement.append(htmlData);

			if( fieldType == 'cp_shape' || fieldType == 'cp_dual_color_shape' ) {
				// Set preset
				cpLayers.assignShapePreset( $('#'+dynamic_id), fieldPreset );
			}

			if( true == is_respective_to_panel ) {
				current_step = 'common';
				bmodel.setModal( 'common' );
			}

			this._setDroppedField( $('#'+dynamic_id), temp_clone_position, current_step, resize );

		},

		_setDroppedField: function( field, field_temp_position, current_step, is_resize ) {

			var field_id = field.attr("id");

			ConvertProDragDrop._savePanelItemPosition( field, false, false, true );

			if( true === temp.has_editor ) {
				field.addClass('cp_has_editor');
			}

			field.append(resize_handlers);	

			var force_hide_edit_panel = true;

			// if dragged field is form field 
			if( field.find('.cp-target').hasClass("cp-form-field") ) {
				force_hide_edit_panel = false;
			}

			// display resize handlers 
			$(".ui-resizable-handle").removeClass( 'show' );
			$(".cp-field-html-data").removeClass( 'selected' );
			field.find(".ui-resizable-handle").addClass( 'show' );
			field.addClass( 'selected' );

			// call init panel item
			ConvertProDragDrop._initPanelItemDrag();

			// add resize class if true
			if( 'undefined' != typeof is_resize && true == is_resize ) {
				field.addClass('cp-resize-element');
			}

			$(document).trigger( 'cpro_open_edit_panel', [ field, force_hide_edit_panel ] );
			
			var applyPosition = false;

			field.find(".cp-target").attr( "id", field_id + "-content" );	

			// assign unique z index 
			cpLayers.assignZIndex( field, 'cp-field-html-data', true, false );				
			
			ConvertProHelper._rotateField( $( '#'+field_id + " .cp-rotate-wrap" ), 0 );

			// re init drag for panel items
			ConvertProDragDrop._initPanelItemDrag();

			if( field.hasClass("cp_has_editor") ) {

				var for_edit = field_id;	
				var name = "text_content";
				var value = field.find(".cp-target").html();
				value = encodeURI( value );	
				bmodel.setElementID( step_id, for_edit );

				// save value in modal data
				bmodel.setModalValue( for_edit, step_id, name, value );

			}

			$(document).trigger( 'cpro_after_field_drop', [ applyPosition, false ] );
			
			ConvertProHelper._rotateField( field.find('.cp-rotate-wrap'), 0 );

			$(document).trigger( 'cpro_open_edit_panel', [ field, false ] );
			$('.cp-layer-wrapper').removeClass('cp-hidden');
			$('.panel-wrapper').focus();

		},

		_initPanelDrag: function() {

			var panel_id = step_id + 1;
			var obj = {
				appendTo: '#panel-' + panel_id,
				cursorAt: { top: 10, left: 10 },
				helper: 'clone',
				start: function(event, ui) {
					$(document).trigger('cpro_close_edit_panel');	
					$('.cp-design-content').addClass('make-drag-overflow');
				 	ConvertProDragDrop._prepareDragPanelObject( $(this), event, ui );

					guides = $.map( $( '#panel-'+panel_id+" .cp-panel-item" ).not( this ), ConvertProHelper._computeGuidesForElement );
				},
				drag: function(event, ui) {
					$(document).trigger('cpro_close_edit_panel');
					ConvertProDragDrop._iterateGuidelines( currentDropItem.find('.cp-field-html-data'), ui );
				},
				stop: function(event, ui) {
			        $('.ps-container').removeClass('make-overflow-visible');
			        $( "#guide-v, #guide-h" ).hide();
					$('.cp-design-content').removeClass('make-drag-overflow');
			    }
			}

			$(document).trigger('cpro_init_drag', ['.draggable', obj]);
		},

		/**
		 * 
		 */
		_iterateGuidelines: function( obj, ui, grouping ) {

			if ( typeof grouping === 'undefined' ) {
				grouping = false;
			}

			var panel_id = step_id + 1;
			var $t = obj;

			// iterate all guides, remember the closest h and v guides
		    var chosenGuides = { top: { dist: MIN_DISTANCE+3 }, left: { dist: MIN_DISTANCE+3 } };
		    var pos = { top: ui.position.top, left: ui.position.left };
		    var w = $t.width() - 1;
		    var h = $t.height() - 1;

		    if ( !grouping ) {
		    	$t.css({'width':'auto', 'height':'auto'});
		    }

		    var elemGuides = ConvertProHelper._computeGuidesForElement( null, pos, w, h );

		    $.each( guides, function( i, guide ){
		        $.each( elemGuides, function( i, elemGuide ){

		            if( guide.type == elemGuide.type ){
		                var prop = guide.type == "h"? "top":"left";

		                var d = Math.abs( elemGuide[prop] - guide[prop] );
		                if( d < chosenGuides[prop].dist ){
		                    chosenGuides[prop].dist = d;
		                    chosenGuides[prop].offset = elemGuide[prop] - pos[prop];
		                    chosenGuides[prop].guide = guide;
		                }
		             }
		        });
		    });

		    var guide_h = $( "#panel-"+panel_id+" #guide-h" );
		    var guide_v = $( "#panel-"+panel_id+" #guide-v" );

			if( chosenGuides.top.dist <= MIN_DISTANCE ){

				if( chosenGuides.top.guide.view == 'h-center' ) {
					guide_h.addClass('center');
				}else{
					guide_h.removeClass('center');
				}
				
				guide_h.css( "top", chosenGuides.top.guide.top ).show();
				ui.position.top = chosenGuides.top.guide.top - chosenGuides.top.offset;
			} else {
				guide_h.hide();
				ui.position.top = pos.top;
			}

			if( chosenGuides.left.dist <= MIN_DISTANCE ){

				if( chosenGuides.left.guide.view == 'v-center' ) {
					guide_v.addClass('center');
				}else{
					guide_v.removeClass('center');
				}

			  	guide_v.css( "left", chosenGuides.left.guide.left ).show();
			  	ui.position.left = chosenGuides.left.guide.left - chosenGuides.left.offset;
			} else {
				guide_v.hide();
			  	ui.position.left = pos.left;
			}
			
			ui.position.right = ui.position.left + $t.outerWidth();
			ui.position.bottom = ui.position.top + $t.outerHeight();
		},

		_savePanelItemPosition: function( element, applyValues, is_undo, is_group ) {

			if( is_group == true && element.data('group-position') !== undefined ) {
			
				var target_element = element;
				var xPos = element.data('group-position').left;
				var yPos = element.data('group-position').top;

			} else if( typeof element.helper !== 'undefined' ) {
				var target_element = element.helper[0];
				var xPos = element.position.left;
				var yPos = element.position.top;

			} else {
				var target_element = element;
				var position = element.position();
				var xPos = position.left;
				var yPos = position.top;
			}
			
			var panel_id = step_id + 1;

			percentLeft = xPos;
			percentTop  = yPos;
			precentRight = 'no';
			precentBottom = 'no';

			// when field position is respective to panel
			if( $(target_element).hasClass('cpro-overlay-field') ) {
				
				var Overlay = $('.cp-live-design-area');
				
				right_pos     = Overlay.width() - ( xPos + $(target_element).width() ); 
				precentRight  = parseFloat( right_pos / Overlay.width() * 100 ).toFixed(2); 
				
				bottom_pos    = Overlay.height() - ( yPos + $(target_element).height() );
				precentBottom = parseFloat( bottom_pos / Overlay.height() * 100 ).toFixed(2); 

				percentLeft   = parseFloat( xPos / Overlay.width() * 100 ).toFixed(2); 
				percentTop    = parseFloat( yPos / Overlay.height() * 100 ).toFixed(2);
			}

			var id = $(target_element).attr('id');
			var temp = {};

			temp['x'] = percentLeft;
			temp['y'] = percentTop;
			temp['right'] = precentRight;
			temp['bottom'] = precentBottom;

			var current_step = step_id;
			
			if( jQuery("#" + id ).attr("data-overlay-respective") == 'true' ) {
				current_step = 'common';
				bmodel.setModal(current_step);	
			}

			bmodel.setElementID( current_step, id );
			bmodel.setModalValue( id, current_step, 'position', temp, is_undo );

		}

	}

	ConvertProDragDrop.init();

	ConvertProGrouping = {

		init: function() {

			$(window)
				.on("mouseup", this._selectElements )
				.on("mousemove", this._openSelector )

				.resize( function(e){
				    if ( cp_ghost_dragging ) {
				    	cp_ghost_dragging = false;
				    	$('.cp-ghost-select').removeClass('cp-ghost-active');
						$('.cp-ghost-select').width(0).height(0);
				    }
				});

			$( document ).ready( this._ready );
		},

		_ready: function() {

			ConvertProGrouping._groupingGrid();

		},

		_groupingGrid: function() {

			$( 'body' ).delegate( '.panel-wrapper', 'mousedown', function (e) { 
					
				if ( $(e.target).hasClass('cp-field-html-data') ||
					$(e.target).closest(".cp-field-html-data").length > 0 ) {
					return;
				}

				$( this ).focus();
				
		       	if ( 
		       			e.target != this &&
		       			$(e.target).closest('.cp-field-html-data').length > 0 ||
		       			$(e.target).hasClass('cp-field-html-data') ||
		       			$(e.target).hasClass('cp-big-ghost') || 
		       			$(e.target).closest('.panel-wrapper').length < 1 
		       		)
		       	{
		    		return;
		       	}

				e.preventDefault();

		       	$(".cps-selected").removeClass('cps-selected');
				
				$(document).trigger('cpro_close_edit_panel');

				var panel_wrapper = $('.panel-wrapper');
				var id = step_id + 1;
		        var parent = panel_wrapper.find('.panel-'+id+'-content-wrapper');
			    var parentTop = parent.offset().top;
			    var parentLeft = parent.offset().left;
		       	var pageX = e.pageX - parentLeft;
		       	var pageY = e.pageY - parentTop;
		        
		        ConvertProGrouping._releaseBigGhost( parent );
		        
		        parent.find(".cp-ghost-select").addClass("cp-ghost-active");
		        parent.find(".cp-ghost-select").css({
		            'left': pageX,
		            'top': pageY
		        });

		        initialW = pageX;
		        initialH = pageY;

		       	cp_ghost_dragging = true;
		    });

		},

		_selectElements: function(e) {

			if ( cp_ghost_dragging == false ) {
				return
			}

			cp_ghost_dragging = false;

		    var $this 		= $('.panel-wrapper');
		    var id = step_id + 1;
		    var parent = $('.panel-'+id+'-content-wrapper');
		    ConvertProGrouping._generateBigGhost( parent );
		},

		_releaseBigGhost: function(current_obj) {
			var parent 			= current_obj;
			var parentCpGhost 	= parent.find("#cp-big-ghost");

			$('.selected').removeClass('selected');
			$('.ui-resizable-handle').removeClass('show');

		    if ( parentCpGhost.length > 0 && parentCpGhost.hasClass('cp-grouping-inprogress') ) {
		    	var contentData = parentCpGhost.find( '.cp-field-html-data' );
		    	var innerWrapper = parent;
		    	var parentCpGhostPos = parentCpGhost.position();

		    	contentData.each(function (e) {
			    	var current_this = $(this);
			    	var currentPos = current_this.position();
			    	var current_top = currentPos.top + parentCpGhostPos.top;
			    	var current_left = currentPos.left + parentCpGhostPos.left;

			    	var current_pos = {
			    		'top': current_top,
			    		'left': current_left
			    	}

		    		current_this.data('group-position', current_pos );

			    	current_this.css({
			    		'top': current_top,
			    		'left': current_left
			    	});

			    	current_this.draggable({ disabled: false });
			    });

		    	contentData.appendTo( innerWrapper );

		   		$('.cp-layer-wrapper').addClass('cp-hidden').removeClass('cp-group-active cp-single-group-active cp-dual-group-active');
		    }
		    parent.find(".cp-big-ghost").remove();
		},

		_generateBigGhost: function( current_obj, is_selection ) {

			if ( typeof is_selection == 'undefined' ) {
				is_selection = false;
			}	
			
			var parent 		= current_obj;
			var parentPos 	= parent.offset();
		    var parentW 	= parent.width()
		    var parentH 	= parent.height();
		    var parentTop 	= parent.offset().top;
		    var parentLeft 	= parent.offset().left;

		    var maxX = 0;
		    var minX = 5000;
		    var maxY = 0;
		    var minY = 5000;
		    var totalElements = 0;
		    var elementArr = new Array();
		  	
		  	parent.append("<div id='cp-big-ghost' class='cp-big-ghost' x='" + Number(minX - 20) + "' y='" + Number(minY - 10) + "'></div>");
		    var cpBigGhost = parent.find("#cp-big-ghost");
		    
		    parent.find(".cp-field-html-data").each(function () {
		        var result = false;
		        var aElem = current_obj.find(".cp-ghost-select");
		        var bElem = $(this);
		        if ( is_selection ) {
		        	if ( bElem.hasClass( 'cps-selected' ) ) {
		        		result = true;	
		        	}
		        } else {
		        	result = ConvertProGrouping._doObjectsCollide( current_obj, aElem, bElem );
		        }

		        if (result == true) {
		        	if ( !cpBigGhost.hasClass( 'cp-grouping-inprogress' ) ) {
		        		cpBigGhost.addClass( 'cp-grouping-inprogress' );
		        	}

		        	bElem.draggable({ disabled: true });

		        	bElem.addClass( 'cps-selected' );
		        	var aElemPos = bElem.offset();
		            var bElemPos = bElem.offset();
		            var aW = bElem.width();
		            var aH = bElem.height();
		            var bW = bElem.width();
		            var bH = bElem.height();

		            var coords = ConvertProGrouping._checkMaxMinPos(parentPos, aElemPos, bElemPos, aW, aH, bW, bH, maxX, minX, maxY, minY);

		            maxX = coords.maxX;
		            minX = coords.minX;
		            maxY = coords.maxY;
		            minY = coords.minY;

		            var parent = bElem.parent();

		            if (bElem.css("left") === "auto" && bElem.css("top") === "auto") {
		                bElem.css({
		                    'left': parent.css('left'),
		                    'top': parent.css('top')
		                });
		            }
		        }
		      	
		    });

		    if ( cpBigGhost.hasClass( 'cp-grouping-inprogress' ) ) {
			    var allSelected = parent.find('.cp-field-html-data.cps-selected');
			    var allElements = allSelected;
			    var group_length = allElements.length;

		        parent.find("#cp-big-ghost").css({
		            'width': maxX - minX,
		            'height': maxY - minY,
		            'top': minY,
		            'left': minX
		        });

			    allSelected.each(function () {
			    	var current_this = $(this);
					var current_offset = current_this.position();
					current_this.css({
						'top': current_offset.top - minY,
		            	'left': current_offset.left - minX
					});
			    	
			    });

			    if ( group_length > 0 ) {
		    		$(document).trigger('cpro_close_edit_panel');
			    }

			    if ( group_length == 1 ) {
		    		setTimeout(function() {
		    			$('.cp-layer-wrapper').addClass('cp-single-group-active').removeClass('cp-hidden');
		    		}, 200);
			    }

			    if ( group_length == 2 ) {
		    		setTimeout(function() {
		    			$('.cp-layer-wrapper').addClass('cp-dual-group-active').removeClass('cp-hidden');
		    		}, 200);
			    }

			    if ( group_length > 2 ) {

		    		setTimeout(function() {
		    			$('.cp-layer-wrapper').addClass('cp-group-active').removeClass('cp-hidden');
		    		}, 200);
			    }
			    	
			    allElements.appendTo(cpBigGhost);
				var id = step_id + 1;
			    var obj = {
					containment: $(".cp-live-design-area"),
					addClasses: 'in-moving',	
					cancel: 'please-drag',
					start: function( event, ui ) {
						guides = $.map( $( '#panel-'+id+' .cp-panel-item' ).not( '.cps-selected' ), ConvertProHelper._computeGuidesForElement );
					},
					drag: function(event, ui) {
					   $t = $(this);
					   ConvertProDragDrop._iterateGuidelines( $t, ui, true );

					   ConvertProHelper._setPositionTooltip( event, 'drag', ui.position.left, ui.position.top );
					},
					stop: function( event, ui ){
				        $( "#guide-v, #guide-h" ).hide();

				        var parentCpGhost 	= parent.find("#cp-big-ghost");

						if ( parentCpGhost.length > 0 && parentCpGhost.hasClass('cp-grouping-inprogress') ) {
		    				var contentData = parentCpGhost.find( '.cp-field-html-data' );
		    		    	var parentCpGhostPos = parentCpGhost.position();

							contentData.each(function (e) {
			    				var current_this = $(this);
			    				var currentPos = current_this.position();
			    				var current_top = currentPos.top + parentCpGhostPos.top;
			    				var current_left = currentPos.left + parentCpGhostPos.left;

			    				var current_pos = {
			    					'top': current_top,
			    					'left': current_left
			    				}
			    	
		    					current_this.data('group-position', current_pos );

		    					ConvertProDragDrop._savePanelItemPosition( current_this, false, false, true );
			    			});
					
					    	bmodel.setUndo( true, 'load_exist' );

					    	$(".tooltip-wrapper").hide();
				   		}
				   	}
				};

				$(document).trigger('cpro_init_drag', [cpBigGhost, obj]);
		    } else {
		    	parent.find("#cp-big-ghost").css({
		            'width': maxX - minX,
		            'height': maxY - minY,
		            'top': minY,
		            'left': minX
		        });
		    }
		    
		    parent.find(".cp-ghost-select").removeClass("cp-ghost-active");
		    parent.find(".cp-ghost-select").width(0).height(0);
		},

		_openSelector: function(e) {

		    if (cp_ghost_dragging == false) {
		    	return;
		    }

			$this = $('.panel-wrapper');
			var id = step_id + 1;
		    var parent = $('.panel-'+id+'-content-wrapper');
		    var parentTop = parent.offset().top;
		    var parentLeft = parent.offset().left;
		    var pageX = e.pageX - parentLeft;
		   	var pageY = e.pageY - parentTop;
		    var w = Math.abs( initialW - pageX );
		    var h = Math.abs( initialH - pageY );

		    var ghostSelect = $this.find(".cp-ghost-select");
		    ghostSelect.css({
		        'width': w,
		        'height': h
		    });

		    if ( pageX <= initialW && pageY >= initialH ) {
		        ghostSelect.css({
		            'left': pageX
		        });
		    } else if ( pageY <= initialH && pageX >= initialW ) {
		        ghostSelect.css({
		            'top': pageY
		        });
		    } else if ( pageY < initialH && pageX < initialW ) {
		        ghostSelect.css({
		            'left': pageX,
		            "top": pageY
		        });
		    }
		},

		_doObjectsCollide: function( parent_obj, a, b ) { // a and b are your objects

		    var parent = parent_obj;
		    var parentTop = parent.offset().top;
		    var parentLeft = parent.offset().left;
		    var aTop = a.offset().top - parentTop;
		    var aLeft = a.offset().left - parentLeft;
		    var bTop = b.offset().top - parentTop;
		    var bLeft = b.offset().left - parentLeft;

		    return !(
		        (( aTop + a.height()) < (bTop) ) ||
		        ( aTop > (bTop + b.height()) ) ||
		        ( (aLeft + a.width()) < bLeft) ||
		        ( aLeft > ( bLeft + b.width() ) )
		    );
		},

		_checkMaxMinPos: function( parentPos, a, b, aW, aH, bW, bH, maxX, minX, maxY, minY ) {
		    
		    'use strict';

		    var pLeft	= parentPos.left;
		    var pTop	= parentPos.top;
		    var aLeft	= a.left - parentPos.left;
		    var aTop	= a.top - parentPos.top;
		    var bLeft	= b.left - parentPos.left;
		    var bTop	= b.top - parentPos.top;

		    if (aLeft < bLeft) {
		        if ( aLeft < minX ) {
		            minX = aLeft;
		        }
		    } else {
		        if ( bLeft < minX ) {
		            minX = bLeft;
		        }
		    }

		    if ( aLeft + aW > bLeft + bW ) {
		        if (aLeft > maxX) {
		            maxX = aLeft + aW;
		        }
		    } else {
		        if ( bLeft + bW > maxX ) {
		            maxX = bLeft + bW;
		        }
		    }
		    ////////////////////////////////
		    if ( aTop < bTop ) {
		        if (aTop < minY) {
		            minY = aTop;
		        }
		    } else {
		        if ( bTop < minY ) {
		            minY = bTop;
		        }
		    }

		    if ( aTop + aH > bTop + bH ) {
		        if (aTop > maxY) {
		            maxY = aTop + aH;
		        }
		    } else {
		        if ( bTop + bH > maxY ) {
		            maxY = bTop + bH;
		        }
		    }

		    return {
		        'maxX': maxX,
		        'minX': minX,
		        'maxY': maxY,
		        'minY': minY
		    };
		}

	}

	ConvertProGrouping.init();

	ConvertProResize = {

		init: function() {

			$( document )
				.on( 'cpro_after_field_drop cpro_customizer_loaded cpro_switch_panel cpro_after_undo cpro_after_redo cpro_after_apply_device_data cpro_after_clone_field', this._initCPResizable );
		},

		// Init resizable
		_initCPResizable: function() {
			
			var id = step_id + 1;
			
			// //set aspect ratio for image
			$( ".cp-image-ratio" ).resizable({
			  aspectRatio: true,
			  handles: {
				    'n':  '.ui-resizable-n', 
				    'e':  '.ui-resizable-e',
				    's':  '.ui-resizable-s',
				    'w':  '.ui-resizable-w',
				    'ne': '.ui-resizable-ne',
				    'se': '.ui-resizable-se',
				    'sw': '.ui-resizable-sw',
				    'nw': '.ui-resizable-nw'
				},
				create: function( event, ui ) {
				    var width = $(event.target).width();
				    var height = $(event.target).height();
				    ConvertProHelper._setResizeHandlerPosition( width, height, $(this) );
				},
				start: function( event, ui ) {
					guides = $.map( $( '#panel-'+id+" .cp-panel-item" ).not( this ), ConvertProHelper._computeGuidesForElementResize );

					$(this).removeClass('selected');
					//$(this).addClass('cp-promote-tooltip');
					jQuery(".ui-resizable-handle").removeClass("show");
				},
				resize : function(event, ui){		
					ConvertProResize._onResizeActions( event, jQuery(this), ui );
				},
				stop: function( event, ui ) {
					ConvertProResize._onResizeStop( event, jQuery(this), ui );	
			   	}
			});

			$('.cp-resize-element').resizable({
				handles: {
				    'n':  '.ui-resizable-n', 
				    'e':  '.ui-resizable-e',
				    's':  '.ui-resizable-s',
				    'w':  '.ui-resizable-w',
				    'ne': '.ui-resizable-ne',
				    'se': '.ui-resizable-se',
				    'sw': '.ui-resizable-sw',
				    'nw': '.ui-resizable-nw'
				},
				//handles: "ne, nw, se, sw", 
				create: function( event, ui ) {
				    var width = $(event.target).width();
				    var height = $(event.target).height();
				    ConvertProHelper._setResizeHandlerPosition( width, height, $(this) );
				},
				start: function( event, ui ) {
					guides = $.map( $( '#panel-'+id+" .cp-panel-item" ).not( this ), ConvertProHelper._computeGuidesForElementResize );

					$(this).removeClass('selected');
					jQuery(".ui-resizable-handle").removeClass("show");
				},
				resize : function(event, ui) {
					ConvertProResize._onResizeActions( event, jQuery(this), ui );
				},
				stop: function( event, ui ) {
					ConvertProResize._onResizeStop( event, jQuery(this), ui );	
			   	}
			});
		},

		_onResizeActions: function( event, element, ui ) {

			var id = step_id + 1;
			var direction = element.data('ui-resizable').axis;
			var for_edit = element.attr('id');
			var width = parseInt( Math.round( ui.size.width * 100 ) / 100 );
			var height = parseInt( Math.round( ui.size.height * 100 ) / 100 );

			// iterate all guides, remember the closest h and v guides
		    var chosenGuides = { top: { dist: MIN_DISTANCE+3 }, left: { dist: MIN_DISTANCE+3 } };
		    var $t = element;
		    var pos = { top: ui.position.top, left: ui.position.left };
		    var w = $t.width() - 1;
		    var h = $t.height() - 1;
		    var elemGuides = ConvertProHelper._computeGuidesForElementResize( null, pos, w, h );
			
		    ConvertProHelper._setResizeHandlerPosition( width, height, $(this) );
			ConvertProHelper._setPositionTooltip( event, 'resize', width, height );

			ConvertProHelper._applySettings( for_edit, 'width', width, 'px', false, '', step_id );
			ConvertProHelper._applySettings( for_edit, 'height', height, 'px', false, '', step_id );

		    $.each( guides, function( i, guide ) {
		        $.each( elemGuides, function( i, elemGuide ) {

		            if( guide.type == elemGuide.type ){
		                var prop = guide.type == "h" ? "top" : "left";

		                var d = Math.abs( elemGuide[prop] - guide[prop] );

		                if( d < chosenGuides[prop].dist ) {
		                	
		                	if( ( direction.indexOf('s') >= 0 && elemGuide.position == 'bottom' ) || 
		                        ( direction.indexOf('n') >= 0 && elemGuide.position == 'top'  ) ||
		                    	( direction.indexOf('w') >= 0 && elemGuide.position == 'left'  ) || 
								( direction.indexOf('e') >= 0 && elemGuide.position == 'right'  ) ) {

		                    	chosenGuides[prop].dist = d;
		                        chosenGuides[prop].offset = elemGuide[prop] - pos[prop];
		                        chosenGuides[prop].guide = guide;
		                    }
		                }
		            }
		        });
		    });

			if( chosenGuides.top.dist <= ( MIN_DISTANCE - 3 ) ){
				$( "#panel-"+id+" #guide-h" ).css( "top", chosenGuides.top.guide.top ).show();
			} else {
				$( "#panel-"+id+" #guide-h" ).hide();
			}

			if( chosenGuides.left.dist <= ( MIN_DISTANCE - 3 ) ){
			  	$( "#panel-"+id+" #guide-v" ).css( "left", chosenGuides.left.guide.left ).show();
			} else {
				$( "#panel-"+id+" #guide-v" ).hide();
			}	
		},

		_onResizeStop: function( event, element, ui ) {

		    var for_edit = element.attr('id');
			var $this = $(this);
			var width = parseInt( Math.round( ui.size.width * 100 ) / 100 );
			var height = parseInt( Math.round( ui.size.height * 100 ) / 100 );

		    $this.find(".panel-edit").show();
		    $this.find(".ui-resizable-handle").addClass("show");
		    $this.addClass('selected');
			
			$( "#guide-v, #guide-h" ).hide();
		    
		    $(".tooltip-wrapper").hide();

			ConvertProResize._setSizeOfElement( for_edit, width, height, false );

		    // on drag save position in modal
			ConvertProDragDrop._savePanelItemPosition( ui, false );
		    
		    setTimeout(function() {
		    	$(".panel-wrapper").focus();
		    }, 50);
		},

		// set width and height in modal data
		_setSizeOfElement: function(for_edit, width, height, is_undo ) {

			$('#'+for_edit).css({ width: 'auto', height: 'auto' });
			$('#'+for_edit).find('.cp-target').css({ width: width + 'px', height: height  + 'px' });

			var respective = $('#'+for_edit).attr("data-overlay-respective");
			
			step_data = step_id;
			
			if( 'true' == respective ) {
				step_data = 'common';
			}

			bmodel.setElementID( step_data, for_edit );
			bmodel.setModalValue( for_edit, step_data ,'width', width, false );
			bmodel.setModalValue( for_edit, step_data, 'height', height, false );

			if( false !== is_undo ) {
				bmodel.setUndo( for_edit );
			}
		}

	}

	ConvertProResize.init();

	/**
	 * JavaScript class for Convert Pro Panel
	 *
	 * @since 1.0.0
	 */
	ConvertProPanel = {

		/**
	     * Initializes the all class variables and methods.
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    init: function( e ) {

			$( document )
				.ready( this._ready )
				.on( 'click', '.panel-wrapper:not(.cp-resize-element)', this._onPanelClick )
				.on( 'click', '.cp-undo-button', this._undo )
				.on( 'click', '.cp-redo-button', this._redo )

				.on( 'click', '.cp-show-panel-settings', function(e){
					$('.cp-vertical-nav a[href="#panel"]').trigger('click');
					setTimeout(function() {
						$('.cp-panel-wrapper .cp-accordion-title.size').trigger('click');
					}, 200);
				})

				.on( 'click', '.panel-wrapper:not("#cp-group-grid")', function(e){
					if( $(e.target).hasClass('panel-wrapper') ) {
						$(document).trigger('cpro_close_edit_panel');
					}
				})

				.on( 'cpro_save_modal_data', function(){
					modal_data = bmodel.get("panel_data");

					var modal_data_string = JSON.stringify(modal_data);
					$('#cp_modal_data').val(modal_data_string);
				})

				.on( "change", ".cp-api-fields input", function(e) {
					var cp_input_check = jQuery(this).val() || 0;
					if( cp_input_check != 0 ) {
						jQuery(this).parents('.cp-api-fields').addClass('has-input');
						jQuery('.cp-api-fields input').addClass('cp-valid');
					} else {
						jQuery(this).parents('.cp-api-fields').removeClass('has-input');
					}
				});

			$('.panel-wrapper').bind( 'keydown', this._onPanelKeyDown );
			
			$( window )
				.on( 'resize', this._onResize )
				.on( 'beforeunload', this._beforeUnload )
				.bind( 'keydown', this._keyEvents );

			this._addShapeHeadings();

	    },

	    _beforeUnload: function( e ) {

	    	e.preventDefault();
		    var save_cookie = Cookies.get(); 
		    var msg = "save changes before exit";

		    // If unsaved changes are there, prevent user
		    if( save_cookie['cp-unsaved-changes'] === "1" ) {
		    	return msg;
		    }
	    },

	    _ready: function() {

	    	ConvertProPanel._loadPanelData();

			ConvertProPanel._modifyWindowURL();
			ConvertProPanel._loadGoogleFonts();

			$(".panel-wrapper").focus();

			$( '.design-content .form-fields .cp-element-container .cp-droppable-item[data-field-title=Phone]' ).find( 'i' ).removeClass( 'dashicons-editor-spellcheck' ).addClass( 'dashicons-phone' );
		
			ConvertProPanel._setPanelScroll();

			// vertical center design
			$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 0 );

	    },

	    _loadPanelData: function() {

	    	// get modal data from hidden input 
			var load_previous_data = $('#cp_modal_data').val();

			if( load_previous_data !== '' ) {
				load_previous_data_json = $.parseJSON(load_previous_data);
			} else {
				// get default data
				panel_default_data = bmodel.getDefaultData();
				load_previous_data_json = panel_default_data;
			}

			// Set panel data
			bmodel.set({ panel_data: load_previous_data_json });

			bmodel.loadExistingModal();

	    },

	    _keyEvents: function( event ) {

	    	if ( event.metaKey || event.ctrlKey ) {

				switch ( String.fromCharCode(event.which).toLowerCase() ) {
					// ctrl + s
					case 's':
						event.preventDefault();
						$(".cp-save").trigger('click');
						break;
					// ctrl + z
					case 'z':
						event.preventDefault();
						$('.cp-undo-button').trigger('click');
						break;
					// ctrl + y
					case 'y':
						event.preventDefault();
						$('.cp-redo-button').trigger('click');
					break;
					// ctrl + d
					case 'd':
						event.preventDefault();

						var $element = $(".cp-field-html-data.selected, .cp-field-html-data.cps-selected");
							
						if ( $element.length == 1 ) {
							$(document).trigger('clonePanelItem',[$element]);
						}
					break;
					// ctrl + a
					case 'a':

						if( $(".panel-wrapper").is(":focus") ){
							event.preventDefault();
							
							var panel_id 		= step_id + 1;
							var current_panel 	= $('#panel-' + panel_id);
							var all_elements 	= current_panel.find('.cp-field-html-data');

							if ( all_elements.length > 0 ) {
								all_elements.each(function(i){

									$this = $(this);
									$this.addClass('cps-selected');
									ConvertProGrouping._releaseBigGhost( $this.closest( '.panel-content-wrapper' ) );
									ConvertProGrouping._generateBigGhost( $this.closest( '.panel-content-wrapper' ), true );
								});
							}
						}
					break;
				}
			}

			return true;
	    },

	    _loadGoogleFonts: function() {

	    	var google_fonts = $("#cp_fonts_list").val();

			if( '' !== google_fonts ) { 
				ConvertProHelper._loadGoogleFonts( google_fonts );
			}
	    },

	    /*
		 * Add heading to a group of shapes 	
		*/
	    _addShapeHeadings: function() {

			var shapes_1 = '<div class="cp-custom-shapes-heading">' +
						    '<label class="cp-shapes-heading">Shapes</label> </div>';
			$(shapes_1).insertBefore( $('.cp_element_drager_wrap.cp_shape.shape' ).eq(0) );

			var shapes_2 = '<div class="cp-custom-shapes-heading cp-heading">' +
						    '<label class="cp-shapes-heading">Banners & Ribbons</label> </div>';
			$(shapes_2).insertBefore( $('.cp_element_drager_wrap.cp_shape.banner' ).eq(0) );

			var shapes_3 = '<div class="cp-custom-shapes-heading cp-heading">' +
						    '<label class="cp-shapes-heading">Social Media</label> </div>';
			$(shapes_3).insertBefore( $('.cp_element_drager_wrap.cp_shape.social-media' ).eq(0) );

			var shapes_4 = '<div class="cp-custom-shapes-heading cp-heading">' +
						    '<label class="cp-shapes-heading">Arrows & Directions</label> </div>';
			$(shapes_4).insertBefore( $('.cp_element_drager_wrap.cp_shape.arrow-shape' ).eq(0) );

			var shapes_5 = '<div class="cp-custom-shapes-heading cp-heading">' +
						    '<label class="cp-shapes-heading">Ecommerce & Payments</label> </div>';
			$(shapes_5).insertBefore( $('.cp_element_drager_wrap.cp_shape.ecommerce-payment' ).eq(0) );

			var shapes_6 = '<div class="cp-custom-shapes-heading cp-heading">' +
						    '<label class="cp-shapes-heading">Others</label> </div>';
			$(shapes_6).insertBefore( $('.cp_element_drager_wrap.cp_shape.others' ).eq(0) );

	    },

	    _modifyWindowURL: function() {

	    	var currURL = window.location.href,
				newURL = '',
				save_now = ConvertProHelper._getURLVar( 'save_now' );
		
			if( save_now == 'true' ) {
				ConvertProSidePanel._saveStyleSetting( 'save' );
				newURL = currURL.replace( '&save_now=true', '' );
				setTimeout( function() { 
					history.pushState( {}, null, newURL ); 
				}, 200 );
			}

	    },

	    _onPanelClick: function(e) {

	    	var $this 				= $(this); 
			var $tiny_container     = $(".mce-container-body");
			var target 				= $( e.target );
			var $cp_resize_element 	= $this.find('.cp-resize-element');
			var $cp_layer_wrapper 	= $('.cp-layer-wrapper');
			var $cp_panel_item		= $('.cp-panel-item');

			if( target.hasClass('cp-resizable-active') ) {
				return;
			}

			if( $tiny_container.find(e.target).length === 0 && $cp_layer_wrapper.find(e.target).length === 0 && $cp_panel_item.find(e.target).length === 0 ) {

				$isEdit = $this.find(".edit-in-progress").length;
				if( $isEdit != 1 ){
					$this.find(".ui-resizable-handle").removeClass('show');
					$cp_panel_item.removeClass('selected');
				}
				$cp_resize_element.removeClass('cp-resizable-active');
				$cp_layer_wrapper.addClass('cp-hidden');
			}


			if ( !$(e.target).hasClass('cp-field-html-data') && 
				$(e.target).closest(".cp-field-html-data").length == 0 ) {

				if ( document.selection ) {
			        document.selection.empty();
			    } else if ( window.getSelection ) {
			        window.getSelection().removeAllRanges();
			    }
			   	
			   	$( ".cp-field-html-data" ).each(function( index ) {

					$this = jQuery(this);
					var tinymce_selector_id = $this.find(".cp-target").attr("id");
					var has_existing_editor = tinymce.get( tinymce_selector_id );

					if(!$this.hasClass('cps-selected')){
						$this.draggable('enable');
						$this.find(".cp-target").css( "cursor", "move");
					}

					jQuery(this).removeClass("cp-tiny-active");

					if( has_existing_editor !== null ) {
						tinymce.remove( "#" + tinymce_selector_id );
					}
				});
			}

	    },

	    _onPanelKeyDown: function(e) {

	    	if (e.target !== this ){
				return;
		   	}

			if ( e.which == 8 || e.which == 46 ) {
				
				if ( $('html').hasClass( 'cp-mobile-device' ) ) {
					return false;
				}

				var selectedElement = $('.panel-wrapper .cps-selected, .panel-wrapper .cp-field-html-data.selected ').not('.tiny-active');
				if ( selectedElement.length > 0 ) {
					selectedElement.each(function(i) {
						var $element_id = $(this).attr("id");
						$(document).trigger('removePanelItem',[$element_id, 'delete_element', false]);
					});

					bmodel.setUndo( true, 'load_exist' );
				}

				var id = step_id + 1;
				$( '#panel-' + id).find(".cp-big-ghost").remove();
			}

			// Move fields using keyboard arrow keys
			else if ( e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40 ) {

		    	clearTimeout( cp_move_field_timer );
						
				var el_selected = $(".cp-field-html-data.selected");
				var el_in_group = el_selected.closest('.cp-big-ghost').length;

				var sel_element = el_selected;
				
				if ( el_in_group > 0 ) {
					sel_element = sel_element.closest('.cp-big-ghost');
				}else{
				
					if ( el_selected.length < 1 ) {
						sel_element = $('.cp-big-ghost.cp-grouping-inprogress');
					}
				}

				if ( sel_element.length == 1 && sel_element.find('.mce-edit-focus').length == 0 ) {

				    if (e.which == 37) {
				        sel_element.animate({left: "-=1px"}, 0);  
			        }
			        else if (e.which == 38) {
			            sel_element.animate({top: "-=1px"}, 0);  
			        }
			        else if (e.which == 39) {
			            sel_element.animate({left: "+=1px"}, 0);  
			        }
			        else if (e.which == 40) {
			            sel_element.animate({top: "+=1px"}, 0);  
			        }
					
					cp_move_field_timer = setTimeout( function() {

			            // save position in modal
						if ( sel_element.hasClass('cp-big-ghost') ) {
							ConvertProHelper._setGroupElPosition();
						}else{
							ConvertProDragDrop._savePanelItemPosition( sel_element, false );
						}
					}, 50);
				}
			}

	    },

	    _onResize: function() {

	    	if ( cp_ghost_dragging ) {
		    	cp_ghost_dragging = false;
		    	$('.cp-ghost-select').removeClass('cp-ghost-active');
				$('.cp-ghost-select').width(0).height(0);
		    }

		    // vertical center design
			$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 0 );

		    ConvertProPanel._setPanelScroll();

	    },

	    _undo: function() {

	    	if ( !$(this).hasClass( 'cp-ur-disabled' ) ) {
				bmodel.applyUndo();
				setTimeout( function() {
					var s_id = step_id + 1;
					var panel_fields = $( '#panel-' . s_id ).find( '.cp-field-html-data' );
					if( panel_fields.length == 0 ) {
						$('.cp-layer-wrapper').addClass('cp-hidden');
					}
				}, 100 );
				setTimeout( function() {
					// vertical center design
					$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 300 );
				}, 200 );
			}
	    },

	    _redo: function() {

	    	if ( !$(this).hasClass( 'cp-ur-disabled' ) ) {
				bmodel.applyRedo();
				setTimeout( function() {
					var s_id = step_id + 1;
					var panel_fields = $( '#panel-' . s_id ).find( '.cp-field-html-data' );
					if( panel_fields.length == 0 ) {
						$('.cp-layer-wrapper').addClass('cp-hidden');
					}
				}, 100 );
				setTimeout( function() {
					// vertical center design
					$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 300 );
				}, 200 );
			}

	    },

	    _setPanelScroll: function() {
			
			if( $( '#cp_module_type' ).val() != 'welcome_mat' && $( '#cp_module_type' ).val() != 'full_screen' ) {

				var current_step = step_id + 1;
				var current_panel = $( '#panel-'+ current_step +' .panel-content-wrapper');
				var cp_height = current_panel.innerHeight();
				var cp_width = current_panel.innerWidth();
				var panel_wrapper = $( '.panel-wrapper' );
				var popup_wrap = $('.cp-popup-container');
				var popup_wrap_ht = popup_wrap.innerHeight();
				var popup_wrap_wt = popup_wrap.innerWidth();
				
				if ( cp_height > ( popup_wrap_ht - 40 ) ) {
					
					popup_wrap.addClass( 'cp-popup-exceed-view exceed-height' );
				
				} else {
					
					popup_wrap.removeClass( 'cp-popup-exceed-view exceed-height' );

					panel_wrapper.css({
						'height' : ''
					});
				}

				if ( cp_width > popup_wrap_wt ) {
					
					popup_wrap.addClass( 'cp-popup-exceed-view exceed-width' );
					panel_wrapper.css({
						'width' : cp_width + 80
					});

				} else {
					
					popup_wrap.removeClass( 'cp-popup-exceed-view exceed-width' );

					panel_wrapper.css({
						'width' : ''
					});
				}
	    	}
		}

	}

	ConvertProPanel.init();

})(jQuery);