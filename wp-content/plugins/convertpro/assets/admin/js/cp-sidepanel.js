var ConvertProSidePanel = '';
var ConvertProFieldEvents = '';

(function($) {	

	/**
	 * JavaScript class for working with Side Panel in Customizer.
	 *
	 * @since 1.0.0
	 */

	var field_search_input = '',
		customizer_wrapper = '',
		search_section = '';

	ConvertProSidePanel = {

		/**
	     * Initializes the all class variables.
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    init: function( e ) {

	    	field_search_input = $( '#field-search' );
	    	customizer_wrapper = $( '.cp-customizer-wrapper' );
	    	search_section = $( '.cp-section-search' );

	    	$( window ).load( this._load );
	    	$( document ).ready( this._ready );
	    	$( window ).resize( this.updateCustomizerHeight );

	    	$( document ).on( 'click', '#cp-accordion h3', this._openAccordion );
	    	$( document ).on( 'click', '.cp-panel-link, #cp-accordion > h3', this._changeSize );
	    	$( document ).on( 'click', '.cp-horizontal-nav-top a', this._changeHorizontalPanel );
	    	$( document ).on( 'click', '.cp-vertical-nav-top a', this._changeVerticalPanel );
	    	$( document ).on( 'cp_panel_loaded', this._panelLoaded );
	    	$( document ).on( 'click', '.cp-customize-section > .cp-section', this._sectionClick );
	    	$( document ).on( 'click', '.customizer-collapse', this._collapse );
	    	$( document ).on( 'click', '.cp-panel-link', this._panelClick );
	    	$( document ).on( 'click', '.cp-save', this._save );
	    	$( document ).on( 'keyup', '#field-search', this._search );
	    	$( document ).on( 'click', this._documentClick );
	    	$( document ).on( 'click', '.cp-field-html-data', this._htmlClicked );

	    	$( document ).on( 'click', '.search-panel.search-close-icon', function() {
	    		field_search_input.val("");
	    		field_search_input.trigger('keyup');
	    	});

	    	$( document ).on( 'click', '.cp-horizontal-nav-bar:not("#cp-group-grid")', function(e){
				$( document ).trigger( 'cpro_close_edit_panel' );
			});

			$( document ).on( "click", ".cp-setting-menu", function(e) {
				$( "#cp-setting-panel" ).toggleClass( "open" );
				e.stopPropagation();
			});

			$( document ).on( "click", ".cp-close-menu", function(e) {
				$( "#cp-close-panel" ).toggleClass( "open" );
				e.stopPropagation();
			});

			$( document ).on( "click", ".cp-collapse-panel", function(e) {
				$( ".customizer-wrapper" ).toggleClass( "collapsed" );
				$( this ).parent().toggleClass( "open" );
			});

			$( document ).on( 'click', '.cp-customize-section', function(e) {
				var id = $( this ).data( 'section-id' );
				$( "#" + id + " .content" ).show();
			});

			$( document ).on( "click", ".cp-question-dropdown", function() {
				$( '.cp-question-dropdown .cp-question-dropbtn' ).toggleClass( 'cp-active-drop' );
				$( "#cp-question-dropdown" ).toggleClass( "cp-question-show" );
			});

			$( document ).on( 'click', '#cp_copy_link_code_button', this._copyLinkCode );

	    	this._updateAccordionContent();
	    	this._hideSidePanelOptions( $( '.cp-form-wrapper' ) );
			this._hideSidePanelOptions( $( '.cp-panel-wrapper' ) );

			Ps.initialize(document.getElementById('cp-edit-panel-contents'),{suppressScrollX:true});
			Ps.initialize(document.getElementById('design'),{suppressScrollX:true});
			Ps.initialize(document.getElementById('configure'),{suppressScrollX:true});
			Ps.initialize(document.getElementById('connect'),{suppressScrollX:true});

			if( jQuery("#cp-md-modal-1").length > 0 ) {
				Ps.initialize(document.getElementById('cp-md-modal-1'),{suppressScrollX:true});
			}
			
			$( window ).trigger( 'resize' );

			//Prevent any actions on enter key pressed 
			$(document).on( 'keyup keydown', '.cp-input', function(event) {

				var keycode = ( event.keyCode ? event.keyCode : event.which );
				if( '13' == keycode ) {
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			});
	    },

	    _htmlClicked: function () {

	    	var self = $(this),
				button = self.find('button'),
				formValidate = true;

			var dataAtion = jQuery('#field_action option:selected').text();
			self.addClass('cp-current-clicked-btn');

			/* Add Button Action to Parent Form */

			self.closest('form.cpro-form').data( 'action', dataAtion );

			if( self.attr( 'data-type' ) == 'cp_button'  || self.attr( 'data-type' ) == 'cp_gradient_button' ) {
				// Loader Button

				if ( self.hasClass('cp-preset-field') ) {
					if( !self.hasClass( "cp-state-loading" ) && !self.hasClass( "cp-state-success" ) && !self.hasClass( "cp-state-error" ) ) {
						self.addClass("cp-state-loading");

						var height = $(this).css("height");

						var btn_text = self.find('.cp-button-field').text();
						var loader_style = 'loader_1';
						var current_step  	= self.find('.cp-popup-content').data("step");
						var button_back_color_hover = self.find('.cp-button-field').css('background');
						self.find('.cp-button-field').addClass('cp-loading-in-progress');
						self.find('.cp-button-field').html("<div class='cp_loader_container'><i class='cp-button-loader-style draw " + loader_style + "'></i></div>");

						if( self.find('.cp_loader_container').hasClass('cp_success_loader_container') ){
							self.find('.cp-button-field').css( 'background', button_back_color_hover );
						}

						window.setTimeout(function() {
							var btn_class = 'cp-state-success',
								button = self;

							button.removeClass( 'cp-current-clicked-btn' );
							button.removeClass('cp-state-loading').addClass( btn_class );
							var button_field = button.find('.cp-button-field');
							button_field.find('.cp_loader_container').addClass('cp_success_loader_container');
							button.removeClass( btn_class );
							window.setTimeout(function(){
								button.find('.cp-button-field').html(btn_text);
								button.find('.cp-button-field').removeClass('cp-loading-in-progress');
							}, 3000);

						}, 3000);
					}
				}
			}
	    },

	    _handleDependencies: function () {
	    	
	    	var container = $(".active-customizer").find(".cp-element-container");

			$.each(container,function(index,element){
				var $this 		= $(this);
				var el_name 	= $this.data('name');
				var el_operator = $this.data('operator');
				var el_value 	= $this.data('value');
				var element 	= $this.data('element');
					element		= $(this).parents(".content").find("#cp_"+element);

				if( typeof el_name !== 'undefined' ){

					var el_id = $("#cp_"+el_name);			
					var value = el_id.val();		
					var displayProp = el_id.closest('.cp-element-container').css('display');			
					$this.hide();

					//	We check the #cp_EL_NAME value for dependency
					//	In [Radio Buttons] it does not works, Because It has different ID's
					//	So, We change the selector for radio button
					if( typeof value === 'undefined' ) {
						var el_id = $(this).parents(".content").find("input[type='radio'][name='"+el_name+"']:checked");
						var value = el_id.val();
						var displayProp = el_id.closest('.cp-element-container').css('display');
						$this.hide();
					}

					switch(el_operator){
						case '=':
							if( value = el_value && displayProp == 'block' ){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '>':
							if(value > el_value  && displayProp == 'block'){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '>=':
							if(value >= el_value  && displayProp == 'block'){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '<':
							if(value < el_value  && displayProp == 'block'){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '<=':
							if(value <= el_value  && displayProp == 'block'){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '==':
							if(value == el_value  && displayProp == 'block') {
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '!=':

							if( value != el_value  &&  ( displayProp == 'block' || displayProp == 'inline-block' ) ) {
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case '!==':
							if(value !== el_value  && displayProp == 'block'){
								$this.show();
							} else {
								$this.hide();
							}
							break;
						case 'is_contain':
							if( value.indexOf(el_value) >= 0 && displayProp == 'block' ){
								$this.show();
							} else {
								$this.hide();
							}
							break;
					}
					if( $this.hasClass("hide-for-default" ) ) {
						$this.hide();
					}
				}
			});
	    },

	    _documentClick: function ( e ) {
	    	
	    	if ( ! $( e.target ).hasClass( 'cp-question-dropbtn' ) &&  ! $( e.target ).hasClass( 'cp-icon-question' ) ) {
				$( '.cp-question-content' ).removeClass( 'cp-question-show' );
				$( '.cp-question-dropdown .cp-question-dropbtn' ).removeClass( 'cp-active-drop' );
		    } 
		    if ( $( e.target ).is( ".cp-setting-menu" ) === false ) {
		      $( '#cp-setting-panel' ).removeClass( 'open' );
		    }
		    if ( $( e.target ).is( ".cp-close-menu" ) === false ) {
		      $( '#cp-close-panel' ).removeClass( 'open' );
		    }
	    },

	    _save: function() {
	    	if( $( this ).hasClass( "cp-save" ) ) {
				$( ".cp-horizontal-nav-action-wrapper .cp-three-bounce" ).removeClass( "cp-hidden" );
			}
			ConvertProSidePanel._saveMapping();
			ConvertProSidePanel._saveStyleSetting( 'save' );
	    },

	    _saveMapping: function() {
	    	var map = new Array(),
				i = 0;

			$( '.cp-form-input-field' ).each( function() {

				if ( $( this ).hasClass( 'cp-email' ) ) {
					return;
				}

				var field 			= $( this ),
					nameAttr		= field.attr( 'name' ),
					placeholderAttr = field.attr( 'data-placeholder' );
					
				if( field.hasClass( 'cp-checkbox-field' ) ) {
					var count = field.find( '.cp-checkbox-wrap' ).length;
					for( j = 0; j < count; j++ ) {
						map[i] = { 'name' : nameAttr + '-' + j, 'value' : placeholderAttr };
						i++;
					}
				}
				map[i] = { 'name' : nameAttr, 'value' : placeholderAttr };
				i++;

			} );

			$( 'input[name=map_placeholder]' ).val( JSON.stringify( map ) );
	    },

	    _saveStyleSetting: function( status ) {

	    	// Menu Save Feature
			$( '.cp-save span' ).text( 'Saving...' );

			$( document ).trigger( "cpro_save_modal_data" );

			var save_btn = $( '#cp-save-settings' ),
				cp_save_btn = $( '.progress-btn' ),
				form 		= $( ".cp-cust-form" ),
				feature_image = $( "[name='featured_image']" ).siblings( ".cp-media-container" ).find('img').attr('src'),
				popup_category = $( "[name='popup_categories']" ).val(),
				template_live = $( "[name='template_live']" ).val(),
				new_format     = {},
				new_data     = form.serializeArray(),
				style_status = $("#cp_style_status").val(),
				template_category = false,
				is_template_live = false;

		    $.each( new_data, function() {

		        var section = $("[name='"+ this.name +"']").closest('.cp-customizer-tab').data("section");
		        
		        if( typeof section != 'undefined' ) {
		            this.section = section;
		        }    

		        if ( new_format[this.name] !== undefined ) {           	
		            if (!new_format[this.name].push) {
		                new_format[this.name] = [new_format[this.name]];
		            }
		            new_format[this.name].push( this );
		        } else {
		            new_format[this.name] = this;
		        }
		    });

			// set popup status
			new_format["live"] = { name: "live", value: style_status };

			// set featured image
			if( typeof feature_image !== 'undefined' ) {
				new_format["screenshot_image"] = { name: "screenshot_image", value: feature_image };
			}

			// set category
			if( typeof popup_category !== 'undefined' ) {
				template_category = popup_category;
			}

			// Template live option
			if( typeof template_live !== 'undefined' ) {
				is_template_live = template_live;
			}

			var action			= form.data('action'),
				style_name      = $("#cp_style_title").val(),
				style_id        = $("#cp_style_id").val(),
				module_type     = $("#cp_module_type").val(),
				security_nonce 	= $( '#cp-save-ajax-nonce' ).val(),
				cp_mobile_responsive = $( '#cp_mobile_responsive' ).val(),
				cp_mobile_generated = $( '#cp_mobile_generated' ).val(),
				post_status		= 'update';

			if ( status == 'new-save' ) {
				post_status = 'new';
			}

			$.ajax({
				url:ajaxurl,
				data: { 
					action:action, 
					settings: new_format,
					style_name: style_name, 
					style_id: style_id,
					module_type: module_type,
					security: security_nonce,
					post_action: post_status,
					template_category: template_category,
					is_template_live: is_template_live,
					cp_mobile_responsive: cp_mobile_responsive,
					cp_mobile_generated: cp_mobile_generated
				},
				type:'POST',
				dataType:'JSON',
				success:function(result){

					if( result.success && result.data.post_action !== 'undefined' ) {

						if ( result.data.post_action == 'new' ) {

							if( typeof result.data.style_id !== 'undefined' ) {
								var style_id = result.data.style_id;
								$("#cp_style_id").val(style_id);
							}

							var post_edit_link = result.data.post_edit_link;

							// Replace browser state to post edit page 

							window.history.pushState( 'post_edit_page', 'cp_popups', post_edit_link );		
						}

						// Remove cookie

						Cookies.remove( 'cp-unsaved-changes' );
						cp_save_btn.removeClass('active');

						$(".cp-saved-wrap").addClass("cp-animated cp-slideInRight").removeClass("cp-hidden");

						setTimeout(function() {
							// Menu Save Feature
							$('.cp-save span').html('<span class="cp-save-tick dashicons dashicons-yes"></span>');
							$('.cp-save').removeClass('cp-saving');					

					    }, 800);

					    setTimeout(function() {
					    	$('.cp-save span').text('Save');
					    	$( document ).trigger( 'cpro_after_design_save' );
					    }, 1300);

					    $(".cp-save").removeClass("cp-hidden");
						$(".cp-horizontal-nav-action-wrapper .cp-three-bounce").addClass("cp-hidden");
					}
				},
				error:function(err){
					console.log(err);
				}
			});
	    },

	    _search: function() {

	    	var search_key = $( this ).val(),
	    		search_key = search_key.toLowerCase(),
	    		active_section = $( ".cp-customizer-tabs-wrapper .cp-customizer-tab.active-section" ),
	    		current_panel = $( '.cp-vertical-nav .cp-section-container .active' ).data( 'panel' ),
	    		panel = active_section.find( ".cp-panel-content[data-panel=" + current_panel + "]" ),
	    		accordion = active_section.find( ".cp-accordion-content,h3" ),
	    		isPanel = panel.data('panel'),
	    		active_panel = $( ".cp-vertical-nav-top .cp-panel-link.active" ).data( 'panel' );

			accordion.hide();
			panel.hide();
			panel.find( '.cp-element-container' ).hide();

			if ( search_key.length >= 1 ) {
				search_section.find( '.dashicons.dashicons-search' ).removeClass('dashicons-search').addClass( 'dashicons-no-alt' );
				search_section.find( '.search-panel' ).addClass( 'search-close-icon' );
			} else {
				search_section.find( '.search-panel' ).removeClass( 'search-close-icon' );
				search_section.find( '.dashicons.dashicons-no-alt' ).removeClass( 'dashicons-no-alt' ).addClass( 'dashicons-search' );
			}

			if( search_key.length > 1 ) {

				if( isPanel == active_panel ){
					panel.hide();
					panel.find(".cp-element-container:not('.skip-search')").hide();
				}

				active_section.find(".cp-element-container").each(function(index, el) {

					var $this = $( this );
					var tags = $( this ).data( "tags" );
					var label = $( this ).find( 'label' ).html();

					if( 'undefined' !== typeof label && '' !== label ) {
						var labelLower = label.toLowerCase();
						var tags = tags + ',' + label + ',' + labelLower;
					}

					isShapes = false;
					isShapes = $this.parent().hasClass('cp_shape');
					if( isShapes ) {
						$this.parent().hide();
					}

					if( typeof tags !== 'undefined' && tags !== '' ) {

						var tags_array = tags.split(",");

						$.each( tags_array, function(index, val) {

							var index = val.indexOf(search_key);
							if( index !== -1 ) {

								if( $this.closest('.cp-panel-content').data( 'panel' ) != active_panel ) {
									return;
								}

								if( $this.closest(".fields-panel").closest('.cp-element-container').hasClass('has-preset') ) {

									$this.closest('.cp-panel-content').show();
									$this.closest(".fields-panel").closest(".cp-element-container.has-preset").show();
									$this.show();	

									// if field is shape, hide custom shape heading 
									if( $this.parent().hasClass('cp_shape') ) {
										$this.parent().show();
										panel.find('.cp-custom-shapes-heading').hide();
									}
								} else {

						  			// if form fields
						  			if( $this.parent().parent().hasClass('form-fields') ) {

						  				$this.closest('.cp-panel-content').show();
						  				$this.show();
						  				$this.closest('#cp-accordion').find(".cp-accordion-content").hide().removeClass('active');
						  				$this.closest('#cp-accordion').find("h3").hide().removeClass('active');
						  				$this.parent().parent().show();
						  				$this.parent().parent().prev('h3').addClass('active').show();
						  				panel.find('.cp-custom-shapes-heading').hide();
						  			} else if( !$this.parent().parent().hasClass('cp-accordion-content') ) {

							  			$this.show();
							  			$this.closest('.cp-panel-content').show();
							  			panel.find('.cp-custom-shapes-heading').hide();
							  		}
						  		}
						  	}
						});
					}
				});
			} else {

				accordion.show();
				panel.show();
				panel.find( '.cp-element-container' ).show();
				
				panel.hide();
				panel.find(".cp-element-container:not('.skip-search')").hide();
				$('.cp-custom-shapes-heading').show();
				var content_holder = $(".cp-panel-content[data-panel="+ active_panel +"]");
				content_holder.show().find('.cp-element-container').show();
				content_holder.find("h3").show().removeClass('active');
				content_holder.find(".cp-accordion-content").hide().removeClass('active');
				content_holder.find("h3:first").show().addClass('active');
				content_holder.find(".cp-accordion-content:first").show();
				$(".cp-panel-content").find('.cp-element-container').find(".cp_shape").show();
			}

			ConvertProSidePanel._handleDependencies();
	    },

	    _panelClick: function() {

	    	var panel = $( this ).data( "panel" ),
	    		section = $( this ).data( "section-id" ),
	    		currentPanel = $( "#" + section ).find( "[data-panel=" + panel + "]" );

			$( ".cp-panel-content" ).hide();
			$( ".cp-panel-link" ).removeClass( 'active cp-active-link-color' );
			currentPanel.fadeIn('slow');
			currentPanel.find( ".cp-element-container" ).each( function(index, el) {
				// Skip fields those have dependency
				if( typeof $(this).data("operator") == 'undefined' ) {
					$(this).fadeIn();
				}
			});

			if(
				panel == 'panel'
				|| panel == 'elements'
				|| panel == 'form'
				|| panel == 'button'
			) {
				search_section.hide();
			} else {
				if( section == 'design' ) {
					if( $( 'html' ).hasClass( 'cp-mobile-device' ) ) {
						search_section.hide();
					} else {
						search_section.show();
					}
				} else {
					search_section.hide();
				}
			}

			$("#"+ section).find("#cp-accordion").find("h3").show().removeClass("active cp-active-link-color");	
			currentPanel.find(".cp-accordion-content").each( function(index, el) {
				$(this).hide();
			});
			
			ConvertProSidePanel._handleDependencies();	

			$(".cp-customizer-tab").hide();	

			setTimeout(function() {
				$("#"+ section).show();
			}, 200 );

			$(this).addClass('active cp-active-link-color');
	    },

	    _collapse: function( event ) {

	    	event.preventDefault();
			event.stopPropagation();
			var wrapper 		= $(this).parents('.customizer-wrapper'),
				footer_actions	= wrapper.find( ".customize-footer-actions" ),
				section = $('.cp-section.active');

			wrapper.toggleClass( "collapsed" );
			footer_actions.toggleClass( "collapsed" );

			if( !footer_actions.hasClass('collapsed') ){
				section.trigger('click');
			}
	    },

	    _sectionClick: function() {

	    	var collapse 		= $('.customizer-collapse');
			var wrapper 		= collapse.parents('.customizer-wrapper');
			var footer_actions	= wrapper.find( ".customize-footer-actions" );
			var target          = $(this).data('section-id');

			$( "html" ).removeClass( 'cp-design-section-open cp-configure-section-open cp-connect-section-open' );
			$( "html" ).addClass( 'cp-'+ target + '-section-open' );

			if ( $( 'html' ).hasClass( 'cp-mobile-device') ) {
				return false;
			} else {
				if ( target == 'design' ) {
					$('.cp-view-wrap').removeClass( 'cp-hidden' );
				}else{
					$('.cp-view-wrap').addClass( 'cp-hidden' );
				}
			}
			
			customizer_wrapper.removeClass( "cp-configure-active cp-design-active cp-connect-active" );
			customizer_wrapper.addClass( "cp-" + target + "-active" );

			if( wrapper.hasClass( "collapsed" ) ) {
				wrapper.toggleClass( "collapsed" );
				footer_actions.toggleClass( "collapsed" );
			}

			$(this).closest('.cp-customize-section').find(".cp-section").removeClass('active cp-active-link-color');
			$(this).addClass('active cp-active-link-color');

			if( target == 'design' ) {
				search_section.show();
			} else {
				search_section.hide();
			}
			
			if ( ! $( this ).hasClass( 'cp-disable' ) ) {

				field_search_input.attr('placeholder', cp_pro.search_elements);

				$(".cp-customizer-tabs-wrapper").find("> .cp-customizer-tab").removeClass('active-section');
				$("#"+target).fadeIn().addClass('active-section');
				$(".cp-panel-link").addClass('cp-hidden');	
				
				$(document).find(".cp-panel-list").find( "[data-section-id="+ target +"]" ).removeClass('cp-hidden');
				var panel_first_ele = $(document).find(".cp-panel-list").find( "[data-section-id="+ target +"]" );

				panel_first_ele.each(function(i) {
					var el_first = $(this);
					if( el_first.is(':visible') ) {
					
						el_first.trigger('click');
						return false;
					}
				});
			}

			setTimeout( function() {
				// vertical center design
				$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 300 );
			}, 200 );
	    },

	    _navigateToSection: function () {

	    	var hash = window.location.hash;
			section_id = hash.replace( '#', '' );
			
			setTimeout(function() {
				// navigate to section on load 
				jQuery(".cp-section[data-section-id='" + section_id + "']").trigger("click");
			}, 300 );
	    },

	    _ready: function() {

	    	$( '.wp-admin.wp-core-ui' ).addClass( 'folded' );
	    	$( "#cp_design_iframe" ).css( "visibility", "visible" );
			$( ".design-area-loading" ).hide();
			//$( ".cp-section" ).first().trigger( 'click' );
			$( document ).trigger( "cp_panel_loaded" );
			$( ".cp-section" ).first().trigger( 'click' );

			ConvertProSidePanel._navigateToSection();
			ConvertProSidePanel._updateCustomizerHeight();
	    },

	    _load: function() {
	    	// Executes when complete page is fully loaded, including all frames, objects and images.
			$( 'html' ).addClass( 'cp-loaded' );
			setTimeout( function() { 
				$( ".edit-screen-overlay" ).fadeOut( 'fast' );
			}, 600 );
	    },

	    _panelLoaded: function() {

	    	customizer_wrapper.fadeIn('fast');
			customizer_wrapper.addClass('active-customizer');
			
			// Remove cookie.
			Cookies.remove( 'cp-unsaved-changes' );
	    },

	    _openAccordion: function( e ) {

	    	// Slide up all the link lists.

			$( '#cp-accordion .active' ).removeClass( 'active cp-active-link-color' );
			$( "#cp-accordion .cp-accordion-content" ).slideUp( 'fast' );

			// If accordion is background.
			if( $(this).hasClass('background') ) {

				var is_inherit = $(this).next().find( "#cp_inherit_bg_prop" ).val();

				// if inherit background properties and it is not first step 
				if( is_inherit == '1' && step_id !== 0 ) {

					/// hide background properties
					$("#cp_inherit_bg_prop").closest(".cp-accordion-content").find(".cp-element-container").hide();
					$("#cp_inherit_bg_prop").closest(".cp-element-container").show();
				} else {
					/// show background properties
					$("#cp_inherit_bg_prop").closest(".cp-accordion-content").find(".cp-element-container").show();
				}

				ConvertProSidePanel._handleDependencies();	
			}

			//slide down the link list below the h3 clicked - only if its closed
			if( ! $(this).next().is( ":visible" ) )
			{
				$(this).addClass( 'active cp-active-link-color' );
				$(this).next().slideDown( 'fast' );

				ConvertProSidePanel._updateCustomizerHeight();

				setTimeout( function() {
					Ps.update(document.getElementById( 'design' ));
					Ps.update(document.getElementById( 'configure' ));
				}, 300 );
			}
	    },

	    _updateAccordionContent: function() {

	    	$('.cp-accordion-content').each( function ( e ) {

	    		var $this 	= $( this ),
	    			panel 	= $this.closest( '.cp-panel-content' ).data( 'panel' ),
	    			wrap 	= $this.find( '.cp-switch-btn' ),
	    			id 		= wrap.data( 'id' ),
	    			switch_input 	= wrap.parents( '.cp-switch-wrapper' ).find( '#' + id ),
	    			value 			= switch_input.val();

				if( 'launch' == panel ) {
					if( '1' == value ) {
						var icon_span = "<span class='dashicons-yes dashicons'></span>";
						$this.prev( 'h3' ).append( icon_span );
					}
				}
			});
	    },

	    _hideSidePanelOptions: function( element ) {

	    	element.find( '.cp-accordion-content' ).each( function( i ) {

				var hide_accordion = true,
					$this 		= $(this),
					acc_class 	=  $this.data('acc-class');;

				if ( $this.find( '.cp-element-container.cp-mobile-show' ).length > 0 ) {
					element.find( '.cp-accordion-title.' + acc_class ).addClass( 'cp-mobile-show' );
					$this.addClass( 'cp-mobile-show' );
				}
			});
	    },

	    _changeSize: function() {

	    	setTimeout( function () {

				$( ".ps-scrollbar-y-rail" ).remove();

				var container = document.getElementById('design');
				// update scrollbars
				container.scrollTop = 0;
				Ps.update(container);
				$( window ).trigger( 'resize' );

			}, 150);
	    },

	    _changeHorizontalPanel: function() {
	    	$( '#cp-dragger' ).css("transform","translateY(0)");
	    },

	    _changeVerticalPanel: function() {
	    	
	    	var $this 				= $(this),
	    		cp_dragger_class 	= $this.data('panel'),
	    		t_position = "translateY(" + $this.position().top + "px)";

			$( '#cp-dragger' ).css({ "transform": t_position });
			field_search_input.val( '' );
			field_search_input.trigger( 'keyup' );

			// Function to open collapsed panel on click of any menu.
			if( customizer_wrapper.hasClass( 'collapsed' ) ) {
				customizer_wrapper.removeClass( 'collapsed' );
				$( ".cp-vertical-nav-bottom" ).removeClass( 'open' );
			}
	    },

	    _updateCustomizerHeight: function() {
		    var topOfDesignFields = $('#design').offset().top,
		    winHeight = $(window).innerHeight();

		    $('#design').css( 'height', winHeight - topOfDesignFields - 50 );
		    $('#configure').css( 'height', winHeight - topOfDesignFields - 50 );
		    $('#connect').css( 'height', winHeight - topOfDesignFields - 50 );
		},

		_copyLinkCode: function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this 		= $(this);
			var button_text = $this.text();
			var copy_input  = $('.cp_copy_link_code_input');
			
			if ( $('.cp_copy_link_code_input').length < 1 ) {
				var style_id 	= $('#cp_style_id').val();
				var code 		= '<a href=\'#\' class=\'manual_trigger_'+style_id+'\'>Click Me!</a>';
				var append_text = '<input class="cp_copy_link_code_input" type="text" value="'+code+'" readonly="" style="position: absolute; top: 0px; left: -9999px;">';
				
				$this.after( append_text );
			}
		    
		    copy_input.select();

		    try {
		        var status = document.execCommand('copy');
		        
		        if(!status){
		            $this.text( 'Unable to Copy.' );
		        }else{
		            $this.text( 'Copied!' );
		        }
		    } catch (err) {
				$this.text( 'Unable to Copy.' );
		        console.log('Unable to copy.');
		    }
			
			setTimeout(function() {
				$this.text( button_text );
			}, 3000);
		}
	}

	ConvertProSidePanel.init();

	/**
	 * JavaScript class for field events functionality.
	 *
	 * @since 1.0.0
	 */
	ConvertProFieldEvents = {

		/**
	     * Initializes the all class variables.
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    init: function( e ) {

	    	$( document )
	    		.on( 'change', ".cp-customizer-tab[data-section='design'] .cp-input", this._designTab )
	    		.on( 'change', '.cp-checkbox', this._checkboxChange )	
	    		.on( 'cpro_switch_change', this._switchChangeTrigger );

	    		/* Add Ruleset */
			$(document).on( 'click', '.cp-rulsets-wrap .cp-add-ruleset', ConvertProFieldEvents._addRuleSet );

	    	/* Change Ruleset */
			$(document).on( 'click', '.cp-rulsets-wrap .cp-rulset-text', ConvertProFieldEvents._changeRuleSet );

			/* Delete Ruleset */
			$(document).on( "click", ".cp-rulsets-wrap .cp-delete-ruleset", ConvertProFieldEvents._deleteRuleSet );
	    },

	    _checkboxChange: function( e ) {

	    	var val = "",
	    		inputID = $( this ).closest( '.cp-element-container' ).find( ".cp-input.form-control" ).attr( "id" ),
	    		input = $( "#" + inputID );

			$( this ).closest( ".cp-element-container" ).find( ".cp-checkbox" ).each( function() {
				var isChecked = $( this ).is(":checked");
				if( isChecked ) {
					val += $( this ).val() + "|";
				}
			});

			val = val.slice( 0, -1 );
			input.val( val );
			input.attr( "value", val );
	    },

	    _designTab: function( e ) {

	    	var field_name = $(this).attr('name');
			var is_form_field = field_name.indexOf("form_field");

			if( is_form_field != '-1' ) {
				ConvertProHelper._applyFormFields( $(this), true );
			} else {

				ConvertProHelper._applyPanelOptions( $(this), true );

				if( field_name == 'credit_link_color' ) {
					$( '.cp-credit-link' ).css( 'color', $(this).val() );
				} else if( field_name == 'panel_width' || field_name == 'panel_height' ) {
					$("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 200 );
				}
				
				/* Set scroll on panel size changes */
				setTimeout(function() {
					if ( field_name == 'panel_height' || field_name == 'panel_width' ) {
						ConvertProPanel._setPanelScroll();
					}
				}, 50);	
			}
	    },

	    _switchChangeTrigger: function( event, obj, input_name, val, property, action_on ) {
	    	if ( input_name == 'close_overlay_click' ) {

				var current_step = step_id + 1;
				bmodel.setModalValue( 'panel-' + current_step, step_id, input_name, val, false );
			}

			if( $(obj).closest('.cp-panel-content').data('panel') == 'launch' ) {
				switch_input = $(obj),
				value		= switch_input.val();
				$(obj).closest('.cp-accordion-content').prev('h3').find('span').remove();
				if( value == 1 || value == '1' ) {
					$(obj).closest('.cp-accordion-content').prev('h3').append("<span class='dashicons-yes dashicons'></span>");
				}
			}

			// toggle background properties on switch change
			if( 'inherit_bg_prop' == input_name ) {

				var $this = $(obj);

				if( val == '1' ) {
					$this.closest(".cp-accordion-content").find(".cp-element-container").hide();
					$this.closest(".cp-element-container").show();
				} else {
					$this.closest(".cp-accordion-content").find(".cp-element-container").show();
				}

				bmodel.setModalValue( "panel-" + ( step_id + 1 ), step_id, val, true );

				ConvertProHelper._applyPanelBackgroundProperty();
				ConvertProSidePanel._handleDependencies();
			}

			var indexof_style_status = input_name.indexOf("style_status");	
			if( indexof_style_status >= 0 ) { 

				var style_status = val;
				var style_id = $("#cp_style_id").val();
				
				$.ajax({
					url: ajaxurl,
					data: { 
						action: 'cp_update_style_status', 
						style_id: style_id,
						style_status: style_status
					},
					type: 'POST',
					dataType:'JSON',
					success:function(result) {
						console.log(result);		
					},
					error:function(err){
						console.log(err);
					}
				});
			}
	    },

	    _changeRuleSet: function() {

	    	var $this 			= $(this),
			cp_rulsets 		= $this.closest('.cp-rulsets'),
			rulsets_wrap 	= cp_rulsets.closest('.cp-rulsets-wrap'),
			ruleset_id 		= parseInt( cp_rulsets.attr('data-rulsets') );
		
			/* Active Current tab */
			cp_rulsets.addClass('cp-rulsets-active')
				 .siblings()
				 .removeClass('cp-rulsets-active');

			ConvertProFieldEvents._loadRuleset( ruleset_id );

	    },

	    _addRuleSet: function() {

	    	var $this 		= $(this),
			rulsets_wrap 	= $this.closest('.cp-rulsets-wrap'),
			btn_template 	= rulsets_wrap.find('#ruleset-button-template').html(),
			new_rulset_id 	= parseInt( $this.attr('data-rulsets') ) + 1,
			rulset_no 		= new_rulset_id + 1;

			ruleset_name 	= 'Ruleset ' + rulset_no;
		
			/* Get Saved Ruleset */
			var s_ruleset_input = rulsets_wrap.find('.input-hidden-ruleset'),
				s_ruleset 		= jQuery.parseJSON( s_ruleset_input.val() );

			/* Get Default Ruleset */
			var d_ruleset_input = rulsets_wrap.find('.input-hidden-default-ruleset'),
				d_ruleset 		= jQuery.parseJSON( d_ruleset_input.val() );

			s_ruleset[ new_rulset_id ] = d_ruleset[0];		
			s_ruleset[ new_rulset_id ]['name'] = ruleset_name;

			btn_template = btn_template.replace( /{{name}}/g, ruleset_name );
			btn_template = btn_template.replace( /{{ruleset}}/g, new_rulset_id );

			/* Append Button */
			var rulsets_button = rulsets_wrap.find( '.cp-rulsets-button' );

			rulsets_button.append( btn_template );
			rulsets_button.find( '.cp-rulsets[data-rulsets=' + new_rulset_id + ']' )
							.addClass('cp-rulsets-active')
							.siblings().removeClass('cp-rulsets-active');
			
			/* Update saved rules */
			s_ruleset_input.val( JSON.stringify( s_ruleset ) );

			/* Update Id */
			$this.attr( 'data-rulsets', new_rulset_id )

			ConvertProFieldEvents._loadRuleset( new_rulset_id );

	    },

	    _deleteRuleSet: function() {

	    	if( ! confirm( cp_pro.ruleset_delete_confirmation ) ) {
	    		return false;
	    	}

	    	var $this 			= $(this),
			rulsets_wrap 		= $this.closest('.cp-rulsets-wrap'),
			cp_ruleset 			= $this.closest('.cp-rulsets'),
			default_rulset_no 	= 0,
			delete_rulset_no 	= parseInt( cp_ruleset.attr('data-rulsets') );
		
			/* Get Saved Ruleset */
			var s_ruleset_input = rulsets_wrap.find('.input-hidden-ruleset'),
				s_ruleset 		= jQuery.parseJSON( s_ruleset_input.val() );

			// Removed selected step from panel object
			delete s_ruleset[ delete_rulset_no ];

			var new_rulset_obj = {};
			var obj_index = 0;

			$.each( s_ruleset, function( data_index, data_val ) {

				if ( undefined !=  data_val ) {
					new_rulset_obj[ obj_index ] = data_val;
					obj_index++;
				}
			});

			if ( cp_ruleset.hasClass( 'cp-rulsets-active' ) ) {

				rulsets_wrap.find( '.cp-rulsets[data-rulsets=0]' ).addClass('cp-rulsets-active');
				ConvertProFieldEvents._loadRuleset( 0 );
			}

			/* Remove Ruleset Button */
			cp_ruleset.remove();

			/* Update Value */
			s_ruleset_input.val( JSON.stringify( new_rulset_obj ) );

			var rulset_last_index = 0;

			rulsets_wrap.find('.cp-rulsets').each(function(i) {
				
				$(this).attr('data-rulsets', i);
				rulset_last_index = i;
			});

			rulsets_wrap.find('.cp-add-ruleset').attr('data-rulsets', rulset_last_index);
	    },

	    _loadRuleset: function( ruleset_id ) {
			/* Get Saved Ruleset */
			var rulesets_wrap	= $('.cp-rulsets-wrap'),
				s_ruleset_input = rulesets_wrap.find('.input-hidden-ruleset'),
				s_ruleset 		= jQuery.parseJSON( s_ruleset_input.val() );

			var ruleset_data 	= s_ruleset[ ruleset_id ];

			$.each(ruleset_data, function(rule, value) {
				
				var field 		= $( '[name=' + rule + ']' );

				if ( field.length === 0 ) {
					return;
				}

				var	field_val	= field.val(),
					field_type	= field.attr('data-type');

				switch(field_type) {
					case 'switch':
						var fval = field_val,
							rval = value;

						fval = ( '' == fval || 0 == fval || '0' == fval || false == fval ) ? 0 : 1;

						rval = ( '' == rval || 0 == rval || '0' == rval || false == rval ) ? 0 : 1;

						if ( fval != rval  ) {
							$( 'label[data-id=cp_' + rule ).trigger('click');
						}
						break;
					case 'slider':
						field.val( value );
						field.trigger('change');
						break;
					case 'number':
					case 'text':
						field.val( value );
						break;
				}
			});
		}
	}

	ConvertProFieldEvents.init();

})(jQuery);
