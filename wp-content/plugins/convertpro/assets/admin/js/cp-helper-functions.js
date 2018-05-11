var step_id = 0,
	step_count = 0,
	resize_handlers = '<div class="ui-resizable-handle ui-resizable-n"></div><div class="ui-resizable-handle ui-resizable-e"></div><div class="ui-resizable-handle ui-resizable-s"></div><div class="ui-resizable-handle ui-resizable-w"></div><div class="ui-resizable-handle ui-resizable-ne"></div><div class="ui-resizable-handle ui-resizable-se"></div><div class="ui-resizable-handle ui-resizable-sw"></div><div class="ui-resizable-handle ui-resizable-nw"></div>',
	step_count = parseInt( jQuery( "#cp_step_count" ).val() ),
	ConvertProHelper = '';
	ConvertProColor  = '';

( function( $ ) {

	/**
	 * JavaScript class for Helper Functions.
	 *
	 * @since 1.0.0
	 */

	ConvertProHelper = {

		/**
	     * Initializes the all class variables.
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    init: function( e ) {

	    	var step_param = ConvertProHelper._getURLVar( "step" );
				step_param = typeof step_param !== 'undefined' ? step_param.split("#") : 0;

				step_id = ( typeof step_param !== 0 && jQuery("#panel-" + step_param[0] ).length > 0 ) ? parseInt( step_param[0] ) - 1 : 0;

			$( document ).on( "cpro_customizer_loaded", this._customizerLoaded );

			String.prototype.replaceAll = function (find, replace) {
			    var str = this;
			    if( find !== 0 ) {
			    	return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
			    }
			    return '';
			};

			jQuery.fn.center = function ( parent, timeout ) {
				
				var timeout = typeof timeout !== 'undefined' ? timeout : 300;
				var module_type = jQuery("#cp_module_type").val();
				var $this = $(this);
				var element_id = $this.attr("id");

				setTimeout(function() {
					jQuery( "#style-" + element_id ).remove();

					if( 'full_screen' == module_type || 'welcome_mat' == module_type ) {

						if( 
							! $("html").hasClass('cp-mobile-device') ) {

								var top_pos = Math.max(0, (($(parent).height() - $this.find( '.panel-content-wrapper' ).outerHeight()) / 2) + 
							                                                $(parent).scrollTop()) + "px";

								var left_pos = Math.max(0, (($(parent).width() - $this.find( '.panel-content-wrapper' ).outerWidth()) / 2) + 
							                                                $(parent).scrollLeft()) + "px";

							    var modal_style = "<style type='text/css' id='style-" + element_id + "' class='cp_modal_style' > .cp-full_screen .panel-content-wrapper."+ element_id +"-content-wrapper { left: " + left_pos + "; top: " + top_pos + "; transform: none; } </style>";

					    }

					} else if( 'info_bar' != module_type && 'slide_in' != module_type ) {

						var top_pos = Math.max(0, (($(parent).height() - $this.outerHeight()) / 2) + 
					                                                $(parent).scrollTop()) + "px";

						var left_pos = Math.max(0, (($(parent).width() - $this.outerWidth()) / 2) + 
					                                                $(parent).scrollLeft()) + "px";

					    var modal_style = "<style type='text/css' id='style-" + element_id + "' class='cp_modal_style' > #"+ element_id +" .panel-content-wrapper { left: " + left_pos + "; top: " + top_pos + "; transform: none; } </style>"; 

					}

					jQuery("head").append( modal_style );

				}, timeout );

			    return this;
			}
	    },

	    _applyCountdown: function( el, destroy ) {

	    	if ( el.length < 1 ) {
				return;
			}
			var edit_id = el.attr( 'id' ),
				targetCountdown = el.find( '.cp-target.cp-countdown' ),
				timezone_offset = '',
				timer_labels = cp_customizer_vars.timer_labels,
				timer_labels_singular = cp_customizer_vars.timer_labels_singular;

			targetCountdown.countdown( 'destroy' );

			var timer_type = bmodel.getModalValue( edit_id, step_id, 'timer_type' ),
				untilTime = false,
				hideClasses = '',
				timerFormat = 'ODHMS',
				timer_timezone = bmodel.getModalValue( edit_id, step_id, 'timer_timezone' ),
				timezone_offset_arr = [];

			if ( timer_type == 'evergreen' ) {
				
				var el_day = bmodel.getModalValue( edit_id, step_id, 'ever_day' ),
					el_hrs = bmodel.getModalValue( edit_id, step_id, 'ever_hrs' ),
					el_min = bmodel.getModalValue( edit_id, step_id, 'ever_min' ),
					el_sec = bmodel.getModalValue( edit_id, step_id, 'ever_sec' ),
					currdate = '',
					timevar = 0;

				timevar = parseFloat(el_day*24*60*60) + parseFloat(el_hrs*60*60) + parseFloat(el_min*60) + parseFloat( el_sec );

				untilTime = '+' + timevar;
				timerFormat = 'DHMS'
				
			} else {

				if( 'undefined' != typeof timer_timezone ) {
					timezone_offset_arr = timer_timezone.split( "#" );

					if( 'undefined' != timezone_offset_arr[1] ) {
						timezone_offset = timezone_offset_arr[1];
					}
				}

				var el_year = bmodel.getModalValue( edit_id, step_id, 'fixed_year' );
				var el_month = bmodel.getModalValue( edit_id, step_id, 'fixed_month' );
				var el_day = bmodel.getModalValue( edit_id, step_id, 'fixed_day' );
				var el_hrs = bmodel.getModalValue( edit_id, step_id, 'fixed_hrs' );
				var el_min = bmodel.getModalValue( edit_id, step_id, 'fixed_min' );

				untilTime = new Date( el_year, el_month - 1, el_day, el_hrs, el_min );
				timerFormat = 'ODHMS';
			}

			if ( untilTime == 'Invalid Date' ) {
				return;
			}

			if( '' == timezone_offset ) {
				var curr_date = new Date();
				timezone_offset = ( ( -1 * curr_date.getTimezoneOffset() ) / 60 );
			}
			
			targetCountdown.countdown({
				until: untilTime,
				format: timerFormat,
				timeSeparator: ':',
				timezone: timezone_offset,
				labels: timer_labels.split(","),
				labels1: timer_labels_singular.split(","),
			    layout:
			    	'<div class="cp-countdown-holding">'
						+ '<div class="cp-countdown-digit-wrap">'
							+ '<span class="cp-countdown-digit">{onn}</span>'
						+ '</div>'
						+ '<div class="cp-countdown-unit-wrap">'
							+ '<span class="cp-countdown-unit">{ol}</span>'
						+ '</div>'
					+ '</div>'

					+'<div class="cp-countdown-holding">'
						+ '<div class="cp-countdown-digit-wrap">'
							+ '<span class="cp-countdown-digit">{dnn}</span>'
						+ '</div>'
						+ '<div class="cp-countdown-unit-wrap">'
							+ '<span class="cp-countdown-unit">{dl}</span>'
						+ '</div>'
					+ '</div>'

					+ '<div class="cp-countdown-holding">'
						+ '<div class="cp-countdown-digit-wrap">'
							+ '<span class="cp-countdown-digit">{hnn}</span>'
						+ '</div>'
						+ '<div class="cp-countdown-unit-wrap">'
							+ '<span class="cp-countdown-unit">{hl}</span>'
						+ '</div>'
					+ '</div>'

					+ '<div class="cp-countdown-holding">'
						+ '<div class="cp-countdown-digit-wrap">'
							+ '<span class="cp-countdown-digit">{mnn}</span>'
						+ '</div>'
						+ '<div class="cp-countdown-unit-wrap">'
							+ '<span class="cp-countdown-unit">{ml}</span>'
						+ '</div>'
					+ '</div>'

					+ '<div class="cp-countdown-holding">'
						+ '<div class="cp-countdown-digit-wrap">'
							+ '<span class="cp-countdown-digit">{snn}</span>'
						+ '</div>'
						+ '<div class="cp-countdown-unit-wrap">'
							+ '<span class="cp-countdown-unit">{sl}</span>'
						+ '</div>'
					+ '</div>'
			});
	    },

	    _customizerLoaded: function( e ) {
	    	var elements = $(".cp-cust-form .cp-input");
			$.each(elements,function(i,v){

				// On input changes handle dependency
				$(this).on( 'change', ConvertProSidePanel._handleDependencies );
				$(this).on( 'keyup', ConvertProSidePanel._handleDependencies );
			});

			ConvertProHelper._updateLeftPanel();
			ConvertProHelper._applySlideinToggle();
			ConvertProHelper._applyInfobarToggle();
	    },

	    _getURLVar: function( name ) {
	    	var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for( var i = 0; i < hashes.length; i++ ) {
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
			}
			return vars[name];
	    },

	    _removeUrlParameter: function( url, parameter ) {

	    	var urlParts = url.split( '?' );

			if ( urlParts.length >= 2 ) {
				// Get first part, and remove from array
				var urlBase = urlParts.shift();

				// Join it back up
				var queryString = urlParts.join('?');

				var prefix = encodeURIComponent(parameter) + '=';
				var parts = queryString.split(/[&;]/g);

				// Reverse iteration as may be destructive
				for (var i = parts.length; i-- > 0; ) {
					// Idiom for string.startsWith
					if (parts[i].lastIndexOf(prefix, 0) !== -1) {
						parts.splice(i, 1);
					}
				}

				url = urlBase + '?' + parts.join('&');
			}

			return url;
	    },

	    _setResizeHandlerPosition: function( width, height, element ) {
	    	element.find('.ui-resizable-n').css('left', (width/2-4)+'px');
			element.find('.ui-resizable-e').css('top', (height/2-4)+'px');
			element.find('.ui-resizable-s').css('left', (width/2-4)+'px');
			element.find('.ui-resizable-w').css('top', (height/2-4)+'px');
	    },

	    _loadJS: function( src ) {
	    	if( $('script[src="'+src+'"]').length === 0 ) {
		     	var jsLink = $("<script type='text/javascript' src='"+src+"'>");
		     	$("head").append(jsLink);
		    }
	    },

	    _loadStyles: function( src ) {
	    	if( $('link[href="'+src+'"]').length === 0 ) {
		     	var styleLink = $("<link rel='stylesheet' type='text/css' href='"+src+"'>");
		     	$("head").append(styleLink);
		    }
	    },

	    _generateMultiInputResult: function( property, string ) {
	    	var result = '';

			if( 'undefined' !== typeof string ) {
				var pairs  = string.split("|");
				var result = {};

				var unit = ( typeof pairs[4] != 'undefined' && pairs[4] != '' ) ? pairs[4] : 'px';
				
				result[property] = pairs[0]+unit +' '+ pairs[1]+unit +' '+ pairs[2]+unit +' '+ pairs[3]+unit;
			}

			return result;
	    },

	    _generateBoxShadow: function( string ) {
	    	var box_val        = string.split("|");
			var result         = {};		
			var box_shadow_Arr = {};
			var res            = '';

			if( box_val.length > 0 && box_val !=='' ) {
				$.each( box_val, function(index, val) {
					var values = val.split(":");					
					result[values[0]] = values[1];
				});
			}	

			if ( result['type'] !== '' && result['type'] !== 'outset' ){
				res += result['type'] + ' ';
			}

			res += result['horizontal'] + 'px ';
			res += result['vertical'] + 'px ';
			res += result['blur'] + 'px ';
			res += result['spread'] + 'px ';
			res += result['color'];

			if( result['type'] !== 'none' ) {
				box_shadow_Arr['-webkit-box-shadow'] = res;
				box_shadow_Arr['-moz-box-shadow'] = res;
				box_shadow_Arr['box-shadow'] = res;
			} else {
				box_shadow_Arr['box-shadow']='none';
			}
			
			return box_shadow_Arr;
	    },

	    _generateDropShadow: function( string ) {
	    	var box_val        = string.split("|");
			var result         = {};		
			var drop_shadow_Arr = {};
			var res            = '';

			if( box_val.length > 0 && box_val !=='' ) {
				$.each( box_val, function(index, val) {
					var values = val.split(":");					
					result[values[0]] = values[1];
				});
			}	

			var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		    rgb_color_string = result['color'].replace(shorthandRegex, function(m, r, g, b) {
		        return r + r + g + g + b + b;
		    });

		    var rgb_color_obj = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(rgb_color_string);
		    var rgb_color = rgb_color_obj ? "rgb( " + parseInt(rgb_color_obj[1], 16) + "," + parseInt(rgb_color_obj[2], 16) + "," +
		       parseInt(rgb_color_obj[3], 16) + ")" : null;

			res += 'drop-shadow(';
			res += rgb_color == null ? result['color'] : rgb_color;
			res += result['horizontal'] + 'px ';
			res += result['vertical'] + 'px ';
			res += result['blur'] + 'px ';
			res += ')';

			if( result['type'] !== 'none' ) {
				drop_shadow_Arr['-webkit-filter'] = res;
				drop_shadow_Arr['filter'] = res;
				drop_shadow_Arr['box-shadow'] = 'none';
			}
			else {
				drop_shadow_Arr['-webkit-filter'] = 'none';
				drop_shadow_Arr['filter'] = 'none';
			}
			return drop_shadow_Arr;
	    },

	    _createItemID: function( prefix, type ) {
	    	var length = 0;
			$('.panel-wrapper').find(".cp-panel-item").each(function(i,item){
				if($(item).attr('data-type') == type) {
					length++;
				}
			});
			length++;

			var id = ConvertProHelper._recheckItemID(prefix, length);

			return id;
	    },

	    _recheckItemID: function( prefix, type ) {
	    	var new_id = prefix+'-'+length;
			if( $('#'+new_id).length === 0 ) {
				return new_id;
			} else {
				length = parseInt(length)+1;
				return ConvertProHelper._recheckItemID(prefix, length);
			}
	    },

	    _computeGuidesForElement: function( elem, pos, w, h ) {

			var h_center = 'no';
			var v_center = 'no';

			if( elem !== null ){
		        var $t = $(elem);
		        pos = $t.position();
		        w = $t.width() - 1;
		        h = $t.height() - 1;
		        
		        if ( $t.hasClass( 'default-cp-panel-item' ) ) {
		        	h_center = 'h-center';
					v_center = 'v-center';
		        }
		    }


		    return [
		        { type: "h", left: pos.left, top: pos.top, view: 'no' },
		        { type: "h", left: pos.left, top: pos.top + h, view: 'no' },
		        { type: "v", left: pos.left, top: pos.top, view: 'no' },
		        { type: "v", left: pos.left + w, top: pos.top, view: 'no' },
		        // you can add _any_ other guides here as well (e.g. a guide 10 pixels to the left of an element)
		        { type: "h", left: pos.left, top: pos.top + h/2, view: h_center },
		        { type: "v", left: pos.left + w/2, top: pos.top, view: v_center }
		    ];
		},

		_computeGuidesForElementResize: function( elem, pos, w, h ) {

			if( elem !== null ){
		        var $t = $(elem);
		        pos = $t.position();
		        w = $t.width();
		        h = $t.height();
		    }

		    return [
		        { type: "h", left: pos.left, top: pos.top, position: 'top' },
		        { type: "h", left: pos.left, top: pos.top + h, position: 'bottom' },
		        { type: "v", left: pos.left, top: pos.top, position: 'left' },
		        { type: "v", left: pos.left + w, top: pos.top, position: 'right' },
		        // you can add _any_ other guides here as well (e.g. a guide 10 pixels to the left of an element)
		    ];
		},

		_loadGoogleFonts: function( fonts ) {

			var font_list = {};
			fonts = JSON.parse(fonts);

			$.each( fonts , function(index, val) {
			  	var font_family = val.family;
			  	var font_weight = [];
			  	font_weight.push(val.weight);

			  	if( typeof font_list[font_family] !== 'undefined' ) {
			  		var wt = font_list[font_family];
			  		wt.push( val.weight );

			  		font_list[font_family] = wt;
			  	} else {
			  		font_list[font_family] = font_weight;
			  	}
			});

			var font_string = '';

			// Generate font string to pass to google font APIs
			$.each( font_list , function(index, val) {
				var weights = val;
				var family = index;

				if( weights == 'Inherit' ) {
					font_string += family + "|";
				} else {
					font_string += family + ":" + weights.join(",") + "|";	
				}

			});	

			$("#cp_google_font_preview").remove();
			
			if ( font_string != '' ) {
				var google_font_url = "//fonts.googleapis.com/css?family="+ font_string;
				$("head").append("<link id='cp_google_font_preview' type='text/css' rel='stylesheet' href='"+google_font_url+"' /> ");
			};
		},

		_getSelectedImage: function( val, src, parameter, for_edit, current_step ) {
			var cnt 	= step_id + 1,
				img_src = src;	

			if( typeof val === 'string' ){
				img_src = val; 
			}

			if( img_src.includes("|") ) {
				img_src = img_src.split("|");

				if( img_src[0] == '0' ) {
					img_src = cp_admin_ajax.assets_url + img_src[1];
				} else {
					img_src = img_src[1];
				}
			}

			var for_edit_step_id = parseInt( for_edit.replace( "panel-", "" ) );

			if( for_edit_step_id === ( current_step + 1 ) ) {

				switch(parameter){
					case 'panel_bg_image' :						
					case 'background-image' :
					case 'background_type':	

						$(".panel_bg_image").parents(".cp-element-container").find(".cp-media-container").html('<img src="' + img_src + '"/>');	
						$('#' + for_edit).css( "background-image", "url(" + img_src + ")" );
					break;
					case 'default':
					break;
				}
			}

			return false;
		},

		_setPanelPosition: function( position ) {
			var toggle_ht = jQuery(".toggle_height.cp-input").val();
			var popup_container = jQuery(".cp-popup-content");
			var toggle_type = jQuery(".toggle_type.cp-input").val();
			var toggle_container = jQuery('.cp-open-toggle');
			var el_panel_height = bmodel.getModalValue( 'panel-1', 0, "panel_height" );

			if ( toggle_type == 'sticky' ) {
				popup_container.css({ 
					"bottom": '',
					"left"  : '',
					"top"   : '',
					"right" : ''
				});

				toggle_container.css({ 
					"bottom": '',
					"top"   : ''
				});

				switch( position ) {
					case "top-center":
					case "top-left":
					case "top-right":
						toggle_container.css({ 
							"top"    : el_panel_height + 'px',
						});	
					break;
					case "bottom-center":
					case "bottom-left":
					case "bottom-right":
						toggle_container.css({
							"bottom" : el_panel_height + 'px',
						});
					break;
					case "center-left":

						var slide_in_width = bmodel.getModalValue( 'panel-1', step_id, "panel_width" );
						var left_pos_val  = parseInt( slide_in_width ) + 22.5 + 'px';

						toggle_container.css({
							"left" : left_pos_val
						});

					break;
					case "center-right":

						var slide_in_width = bmodel.getModalValue( 'panel-1', step_id, "panel_width" );
						var right_pos_val  = parseInt( slide_in_width ) + 22.5 + 'px';

						toggle_container.css({
							"right" : right_pos_val
						});
					break;

				}
				
			} else {

				toggle_container.css({ 
					"top"    : '',
					"bottom" : ''
				});	

				popup_container.css({ 
					"bottom": '',
					"left"  : '',
					"top"   : ''
				});

				switch( position ) {
					case "top-center":
						popup_container.css({ 
							"top"    : toggle_ht + 'px',
							"left"   : '50%'
						});
					break;
					case "top-left":
						popup_container.css({ 
							"top"    : toggle_ht + 'px',
							"left"   : '20px'
						});
					break;
					case "top-right":
						popup_container.css({ 
							"top"    : toggle_ht + 'px',
							"right"  : '20px'
						});	
					break;
					case "bottom-center":
						popup_container.css( "left", '50%' );
						popup_container.css( "bottom", toggle_ht + 'px' );
					break;
					case "bottom-left":
						popup_container.css( "left", '20px' );
						popup_container.css( "bottom", toggle_ht + 'px' );
					break;
					case "bottom-right":

						popup_container.css({
							"bottom" : toggle_ht + 'px',
							"right"  : '20px'
						});
					break;
					case "center-left":
						popup_container.css({ 
							"top"  : '50%',
							"left" : toggle_ht + 'px'
						});
							
						var left_pos_toggle = toggle_ht / 2 + "px";

						jQuery("#cp-open-toggle").css( "left", left_pos_toggle );

					break;
					case "center-right":
						popup_container.css({ 
							"top": '50%',
							"right": toggle_ht + 'px'
						});

						var right_pos_toggle = toggle_ht / 2 + "px";

						jQuery("#cp-open-toggle").css( "right", right_pos_toggle );
					break;
				}
			}
		},

		_toggle: function( for_edit, value, module_type ) {
			if( for_edit.indexOf('panel-') !== -1 ) {
				
				var current_panel = step_id + 1;

				if( module_type == 'info_bar' ) {
					toggle_id = 'cp-open-infobar-toggle';
				} else {
					toggle_id = 'cp-open-toggle';
				}

				$( '#' + toggle_id ).remove();
				
				if ( value == '1' ) {
					var cp_panel_position = ' cp-toggle-' + $('#cp_panel_position').val();
					var toggle_html = '';

					if( module_type == 'info_bar' ) {
						toggle_html = '<div id="cp-open-infobar-toggle" class="cp-open-infobar-toggle ' + cp_panel_position + '"><span class="cp-open-infobar-toggle-content">Click Here</span><span class="cp-toggle-infobar-icon cp-icon-arrow"></span></div>';
					} else {
						toggle_html = '<div id="cp-open-toggle" class="cp-open-toggle ' + cp_panel_position + '"><span class="cp-open-toggle-content">Click Here</span><span class="cp-toggle-icon cp-icon-arrow"></span></div>';
					}
					
					$('.panel-wrapper' ).after( toggle_html );

					if( module_type == 'info_bar' ) {
						ConvertProHelper._applyInfobarToggle();
					} else {
						ConvertProHelper._applySlideinToggle();
					}
					
				} else {
					$(".cp-popup-content").css({
						'top'    : '',
						'left'   : '',
						'bottom' : '',
					});
				}
			}
		},

		isJsonString: function( str ) {
			try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		},

		_decodeHtmlEntity: function( str ) {
			var textArea = document.createElement('textarea');
		    textArea.innerHTML = str;
		    return textArea.value;
		},

		_setPlaceholderStyle: function( element, style, parameter, value ) {
			var element_id = jQuery( element ).attr('id');

			jQuery("#cp_" + element_id + '_placeholder' ).remove();

			var style_string  = '<style id="cp_' + element_id + '_placeholder" >';
			style_string      += "#" + element_id + ' ::-webkit-input-placeholder { ' + parameter + ': ' + value + ' !important; }';
			style_string      += '</style>';

			jQuery('head').append( style_string );
		},

		_renderVideo: function( for_edit, video_source, video_url ) {
			jQuery( "#cp_video_frame_" + for_edit ).remove();
			video_url = 'undefined' == typeof video_url ? 'y1kV8iW8aDk' : video_url;
			var iframe_src = video_url;

			switch( video_source ) {

				case 'youtube':
					iframe_src = 'https://www.youtube.com/embed/' + video_url;						
				break;

				case "vimeo":
					iframe_src = 'https://player.vimeo.com/video/' + video_url;
				break;

			}

			if( 'custom_url' == video_source ) {

				$('<video />', {
				    id: 'cp_video_frame_' + for_edit,
				    src: iframe_src,
				    type: 'video/mp4',
				    controls: false
				}).appendTo( jQuery( "#" + for_edit + " .cp-target" ) );

			} else {

				$('<iframe>', {
				   src: iframe_src,
				   id:  'cp_video_frame_' + for_edit,
				   frameborder: 0,
				   scrolling: 'no'
				}).appendTo( jQuery( "#" + for_edit + " .cp-target" ) );
			}

			var video_html = jQuery( "#cp_video_frame_" + for_edit ).html();
			var html = jQuery( "#" + for_edit ).find('.cp-target').html();
			html = html.replace( "{{video_html}}", video_html );
			jQuery( "#" + for_edit ).find('.cp-target').html( html );
		},

		_setPositionTooltip: function( event, type, val1, val2 ) {
			if ( type == 'resize' ) {
				var tooltip_text = "W:" + val1 + " H:" + val2;
			} else {
				var tooltip_text = "L:" + val1 + " T:" + val2;
			}
						
			$( ".tooltip-wrapper" ).css( {
				'left' : ( event.pageX + 10 ) + 'px',
				'top' : ( event.pageY + 10 ) + 'px'
			} );

			$( ".tooltip-wrapper" ).show().find( "span" ).html( tooltip_text );
		},

		_setGroupElPosition: function() {
			var id = step_id + 1;

		    var parentCpGhost = jQuery('#panel-'+ id + " #cp-big-ghost.cp-grouping-inprogress");
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
		},

		_applyPanelBackgroundProperty: function() {
			// apply panel and form field options
			$( ".cp-accordion-content[data-acc-class='background'] .cp-input" ).each( function( event ) {

				var element     = jQuery(this);
				var map_style   = element.data("mapstyle");
				var name        = element.attr('name');

				var current_panel_id = step_id + 1;
				var is_inherit = bmodel.getModalValue( "panel-" + current_panel_id, step_id, "inherit_bg_prop" );
				is_inherit     = typeof is_inherit == 'undefined' ? "1" : is_inherit; 

				var value = bmodel.getModalValue( "panel-" + current_panel_id, step_id, name );
				
				if( is_inherit == '1' ) {
					value = bmodel.getModalValue( "panel-1", 0, name );
				}

				if( typeof name !== 'undefined' && typeof map_style != 'undefined' ) {
					var parameter = (typeof map_style.parameter === 'undefined' || map_style.parameter === '') ? false : map_style.parameter.replace(/_/g, '-');
					var onhover = (typeof map_style.onhover === 'undefined' || map_style.onhover === '') ? false : map_style.onhover;
					var target = (typeof map_style.target === 'undefined' || map_style.target === '') ? false : map_style.target;
					var unit = (typeof map_style.unit === 'undefined' || map_style.unit === '') ? '' : map_style.unit;
					if( parameter !== false ) {

						ConvertProHelper._applySettings( "panel-" + current_panel_id, parameter, value, unit, onhover, '.cp-target', step_id );
						
					}
				}
			});
		},

		_applySlideinToggle: function() {
			var field_elm = jQuery("#cp-open-toggle");

			if( jQuery("#cp_panel_toggle").val() == '1' ) {

				$( ".cp-accordion-content[data-acc-class='toggle'] .cp-input" ).each( function( event ) {
					var field_name = jQuery(this).attr("name");
					var current_panel_id = step_id + 1;
					var panel_field_id = "toggle";
					var field_value = bmodel.getModalValue( panel_field_id, step_id, field_name );
					field_value = 'undefined' == typeof field_value ? jQuery(this).val() : field_value;

					if( typeof field_value !== 'undefined' ) {

						switch( field_name ) {
							case "toggle_text": 
								field_elm.find(".cp-open-toggle-content").html( field_value );
							break;
							case "toggle_font_size":
								field_elm.css( "font-size", field_value );
							break;
							case "toggle_height":
							case "toggle_width":
							case "toggle_text_color": 
							case "toggle_bg_color":
								var map_style = jQuery(this).data("mapstyle");
								var parameter = map_style['parameter'];
								var unit      = map_style['unit'];
								var value     = field_value;

								field_elm.css( parameter, value );

								if( 'toggle_height' == field_name ) {

									field_elm.css( "line-height", value + unit );
									var panel_id = "panel-" + ( step_id + 1 );
									var panel_position = bmodel.getModalValue( panel_id, 0, 'panel_position' );

									ConvertProHelper._setPanelPosition( panel_position );
								}

							break;
							case "toggle_type":
								field_elm.addClass( field_value );
								if( field_value == 'hide_on_click' ) {
									field_elm.find(".cp-toggle-icon").hide();
								} else {
									field_elm.find(".cp-toggle-icon").show();
								}

								var panel_position = bmodel.getModalValue( panel_field_id, 0, 'panel_position' );
								ConvertProHelper._setPanelPosition( panel_position );	
							break;

							case "toggle_minimizer":

								if( field_value == '1' && jQuery("#cp_toggle_type").val() == 'sticky' ) {
									field_elm.find(".cp-toggle-icon").show();
								} else {
									field_elm.find(".cp-toggle-icon").hide();
								}
							break;
						}
					}
				});
			}
		},

		_applyInfobarToggle: function() {
			var field_elm = jQuery("#cp-open-infobar-toggle");

			if( jQuery("#cp_panel_toggle_infobar").val() == '1' ) {

				$( ".cp-accordion-content[data-acc-class='toggle'] .cp-input" ).each( function( event ) {
					var field_name = jQuery(this).attr("name");
					var current_panel_id = step_id + 1;
					var panel_field_id = "toggle";
					var field_value = bmodel.getModalValue( panel_field_id, step_id, field_name );

					if( typeof field_value !== 'undefined' ) {

						switch( field_name ) {
							case "toggle_infobar_text": 
								field_elm.find(".cp-open-infobar-toggle-content").html( field_value );
							break;
							case "toggle_infobar_font_size":
								field_elm.css( "font-size", field_value );
							break;
							case "toggle_infobar_height":
							case "toggle_infobar_width":
							case "toggle_infobar_text_color": 
							case "toggle_infobar_bg_color":
								var map_style = jQuery(this).data("mapstyle");
								var parameter = map_style['parameter'];
								var unit      = map_style['unit'];
								var value     = field_value;

								field_elm.css( parameter, value );

								if( 'toggle_infobar_width' == field_name ) {
									var m_left = field_value/2;
									field_elm.css( "margin-left", '-' + m_left + 'px' );							
								}

								if( 'toggle_infobar_height' == field_name ) {

									field_elm.css( "line-height", value + unit );
									var panel_id = "panel-" + ( step_id + 1 );
									var panel_position = bmodel.getModalValue( panel_id, 0, 'panel_position' );

									ConvertProHelper._setInfoBarPanelPosition( panel_position );
								}

							break;
						}
					}
				});
			}
		},

		_setInfoBarPanelPosition: function( position ) {
			var toggle_ht = $(".toggle_infobar_height.cp-input").val();
			var popup_container = $(".cp-module-info_bar .cp-popup-content.cp-" + position );
			var toggle_container = $('.cp-open-infobar-toggle');

			setTimeout( 
		    	function(){ 
			    	if ( toggle_container ) {

						toggle_container.css({ 
							"top"    : '',
							"bottom" : ''
						});	

						popup_container.css({ 
							"bottom": 'auto',
							"left"  : 'auto',
							"top"   : 'auto'
						});


						switch( position ) {
							case "top":
								popup_container.css( "top", toggle_ht + 'px' );
							break;
							case "bottom":
								popup_container.css( "bottom", toggle_ht + 'px' );
							break;
						}
					}
		    	},
			    0
			);
		},

		_setHoverStyle: function( target, StyleSelector, parameter, value ) {
			var ExistingSelector = target + " {",
				ExistingStyleHtml = $("#"+StyleSelector).html(),
				updated = false;
			
			if( typeof ExistingStyleHtml != 'undefined' ) {
				
				var NewArr = ExistingStyleHtml.split('}');
				for( var i = 0; i < NewArr.length; i++ ) {
					if( $.trim(NewArr[i]) !== '' && NewArr[i].indexOf(ExistingSelector) !== -1 ) {
						
						var strNew = NewArr[i].replace(ExistingSelector,'');
						
						var ProperiesArr = strNew.split(' !important;');
						if( ProperiesArr.length > 0 ) {
							$.each(ProperiesArr, function( index, StrValue ) {
								var subArr = StrValue.split(':');
								if( subArr.length > 1 && $.trim(subArr[0]) == parameter ) {
									subArr[1] = value;
									ProperiesArr[index] = subArr.join(':');
									updated = true;
								}
							});
							NewArr[i] = ExistingSelector + ProperiesArr.join(' !important;');
						}
					}
				}
			
			
				if( updated ) {
					$("#"+StyleSelector).html(NewArr.join('}'));
				} else {
					var ExistingStyleHtmlArr = ExistingStyleHtml.split(ExistingSelector);

					if( ExistingStyleHtmlArr.length > 1 ) {
						var NewStyleHTML = ExistingStyleHtmlArr[0]+ExistingSelector,
							ExistingHtmlSubArr = ExistingStyleHtmlArr[1].split('}');
							
						ExistingHtmlSubArr[0] = ExistingHtmlSubArr[0] + parameter+":"+value+" !important;";
						NewStyleHTML += ExistingHtmlSubArr.join('}');
						$("#"+StyleSelector).html(NewStyleHTML);

					} else {
						var str = ExistingSelector + parameter + ":" + value + " !important; }";
						$( "#"+StyleSelector ).append(str);
					}
				}
			}
		},

		_getHoverSelector: function( NewParameter ) {
			var NewParameterArr = NewParameter.split(' ');
			for( var j = 0; j < NewParameterArr.length; j++ ) {
				
				if( NewParameterArr[j].indexOf(".cp-target") >= 0 ) {
					NewParameterArr[j] += ':hover';
					break;
				}
			}
			return NewParameterArr.join(' ');
		},

		_rotateField: function( element, angle ) {
			// Rotation 
			var params = {

				handle: element.find(".cp-rotate").attr( 'src', cp_customizer_vars.admin_img_url + '/rotate.png' ),

				//snaps to step in degrees
				snap: false,

				angle: ( angle * ( Math.PI / 180 ) ),

				// rotate by mouse
				wheelRotate: false,

				// Callback fired on rotation start.
				start: function(event, ui) {
					$(this).parent().find(".ui-resizable-handle").removeClass("show");
					$(this).parent().removeClass('selected');
					$(document).trigger('cpro_close_edit_panel');
				},
				// Callback fired during rotation.
				rotate: function(event, ui) {
				},
				// Callback fired on rotation end.
				stop: function(event, ui) {

					var $this 			     = $(this);
					var calc_rotation_value  = ConvertProHelper._getRotationAngle( $this );

					$this.parent().find(".ui-resizable-handle").addClass("show");
					$this.parent().addClass('selected');
					var rotate_value = ui.angle.degrees;
					var current_step = step_id;
					var element = ui.element[0];

					var is_respective_overlay = jQuery(element).closest('.cp-field-html-data').attr("data-overlay-respective");
					var for_edit = jQuery(element).closest(".cp-field-html-data").attr("id");
					var name = 'rotate_field';

					if( is_respective_overlay == 'true' || for_edit.indexOf('cp_toggle-') > -1 ) {
						current_step = 'common';
					}

					/// make sure rotation value is a number 
					if( ! isNaN( calc_rotation_value ) ) {

						// if angle is negative, convert degree value to negative
						if( ui.angle.current < 0 ) {
							rotate_value = rotate_value - ( rotate_value * 2 );
						}

						rotate_value = rotate_value.toFixed(2);

						bmodel.setElementID( current_step, for_edit );
						bmodel.setModalValue( for_edit, current_step, name, rotate_value );
					}

					ConvertProResize._initCPResizable();
				},
			};

			setTimeout(function() {
				element.rotatable(params);
			}, 800 );
		},

		_getRotationAngle: function( element ) {

			var transform_prop  = element.css( 'transform' );

			var values = transform_prop.split('(')[1],
			    values = values.split(')')[0],
			    values = values.split(',');

			var a = values[0]; // 0.866025
			var b = values[1]; // 0.5
			var c = values[2]; // -0.5
			var d = values[3]; // 0.866025

			var scale = Math.sqrt( a * a + b * b );
			var rotate_value_cal = Math.round( Math.asin(b) * ( 180 / Math.PI ) );

			return rotate_value_cal;

		},

		_applyPanelOptions: function( element, set_options, is_switch_step ) {
			var map_style   = element.data("mapstyle");
			var name        = element.attr('name');

			if( typeof map_style != 'undefined' ) {
				var parameter = (typeof map_style.parameter === 'undefined' || map_style.parameter === '') ? false : map_style.parameter.replace(/_/g, '-');
				var onhover = (typeof map_style.onhover === 'undefined' || map_style.onhover === '') ? false : map_style.onhover;
				var target = (typeof map_style.target === 'undefined' || map_style.target === '') ? false : map_style.target;
				var unit = (typeof map_style.unit === 'undefined' || map_style.unit === '') ? '' : map_style.unit;
				if( parameter !== false ) {

					var current_panel_id = step_id + 1;
					var panel_field_id = "panel-" + current_panel_id;

					if( 'toggle' == target ) {
						panel_field_id = 'toggle';
					}
						
					var value = element.val();

					if( typeof is_switch_step !== 'undefined' && is_switch_step == true ) {
						value = bmodel.getModalValue( panel_field_id, step_id, name );
					}

					if( typeof set_options !== 'undefined' && set_options == true ) {
						bmodel.setModalValue( panel_field_id, step_id, name, value );	
						bmodel.setModalStyleValue( panel_field_id, name, parameter, step_id, unit, onhover, target );
					}

					var is_inherit = bmodel.getModalValue( panel_field_id, step_id, 'inherit_bg_prop' );
					is_inherit     = typeof is_inherit == 'undefined' ? "1" : is_inherit;

					var is_bg_property = element.closest( ".cp-accordion-content" ).data("acc-class") == 'background' ? true : false;

					if( is_inherit == '1' && is_bg_property ) {
						value = bmodel.getModalValue( "panel-1", 0, name );
					}

					if( typeof set_options !== 'undefined' && set_options == false ) {
						value = typeof value == 'undefined' ? element.val() : value;
					}

					if( typeof target == 'undefined' || ( typeof target != 'undefined' 
						&& target != 'toggle' ) ) {
						ConvertProHelper._applySettings( panel_field_id, parameter, value, unit, false, '', step_id, is_switch_step );
					} else {
						ConvertProHelper._applyInfobarToggle();
						ConvertProHelper._applySlideinToggle();
					}
				}
			}
		},

		_applyFormFields: function( element, set_options ) {
			var map_style   = element.data("mapstyle");
			var value       = element.val();
			var name        = element.attr('name');

			if( typeof map_style != 'undefined' ) {
				var parameter = ( typeof map_style.parameter === 'undefined' || map_style.parameter === '') ? false : map_style.parameter.replace(/_/g, '-');
				var onhover = ( typeof map_style.onhover === 'undefined' || map_style.onhover === '') ? false : map_style.onhover;
				var target = ( typeof map_style.target === 'undefined' || map_style.target === '') ? false : map_style.target;
				var unit = ( typeof map_style.unit === 'undefined' || map_style.unit === '') ? '' : map_style.unit;

				if( parameter !== false ) {

					var current_panel_id = step_id + 1;

					jQuery("#panel-" + current_panel_id ).find(".cp-form-field").each( function() {
						var field_id = jQuery(this).closest(".cp-field-html-data").attr("id");	
						ConvertProHelper._applySettings( field_id, parameter, value, unit, onhover, target, step_id );
					});
				}
				
				if( set_options ) {
					bmodel.setModalValue( "form_field", step_id, name, value );
					bmodel.setModalStyleValue( "form_field", name, parameter, step_id, unit, onhover, target );
				}
			}
		},

		_collision : function( $div1, $div2 ) {
			var x1 = $div1.offset().left;
			var y1 = $div1.offset().top;
			var h1 = $div1.outerHeight(true);
			var w1 = $div1.outerWidth(true);
			var b1 = y1 + h1;
			var r1 = x1 + w1;
			var x2 = $div2.offset().left;
			var y2 = $div2.offset().top;
			var h2 = $div2.outerHeight(true);
			var w2 = $div2.outerWidth(true);
			var b2 = y2 + h2;
			var r2 = x2 + w2;

			if (b1 < y2 || y1 > b2 || r1 < x2 || x1 > r2) return false;
			return true;
		},

		_updateLeftPanel: function() {
			var is_inherit = bmodel.getModalValue( "panel-" + ( step_id + 1 ), step_id, "inherit_bg_prop" );
			
			// apply panel and form field options
			$( ".cp-element-container[data-panel='panel'] .cp-input, .cp-element-container[data-panel='form'] .cp-input" ).each( function( event ) {

				var $this = jQuery(this);
				var field_type = $this.data("type");
				var field_name = $this.attr("name");
				var option_value = '';
				var map_style = $this.data("mapstyle");
				var data_panel = $this.closest(".cp-element-container").data("panel");

				if( typeof field_type !== 'undefined'  ) {

					if( $this.closest(".cp-element-container").data("panel") == 'panel' ) {

						if( map_style['target'] == 'toggle' ) {
							option_value = bmodel.getModalValue( "toggle", step_id, field_name );
						} else {
							var panel_id = step_id + 1;
							option_value = bmodel.getModalValue( "panel-" + panel_id, step_id, field_name );
						}

					} else {

						if( map_style['target'] == 'toggle' ) {
							option_value = bmodel.getModalValue( "toggle", step_id, field_name );
						} else {
							option_value = bmodel.getModalValue( "form_field", step_id, field_name );
						}
					}

					if( typeof option_value == 'undefined' && field_name !== 'inherit_bg_prop' ) {
						option_value = bmodel.getModalValue( "panel-1", 0, field_name );
					}

					// if it is a form field option
					if( 'form' == data_panel && 'undefined' == typeof option_value ) {
						option_value = bmodel.getModalValue( "form_field", 0, field_name );
					}

					if ( 'inherit_bg_prop' == field_name ) {
						option_value = typeof is_inherit == 'undefined' ? "1" : is_inherit;
					}

					if( typeof option_value !== 'undefined' ) {

						$this.val(option_value);

						switch( field_type ) {	

							case "switch":
								$this.attr( "value", option_value );
								
								if( 'inherit_bg_prop' == field_name ) {
									
									if( option_value == '1' ) {
										$this.siblings(".cp-switch-input.switch-checkbox").attr( "checked", "checked" );
									} else {
										$this.siblings(".cp-switch-input.switch-checkbox").removeAttr( "checked" );
									}
								}

							break;

							case "colorpicker":
								$('.cs-wp-color-picker').cs_wpColorPicker();
								$this.closest(".wp-picker-container").find(".wp-color-result").css( "background-color", option_value );
							break;

							case "slider": 
								$(".cp-slider").cp_slider();	
								if( $('html').hasClass('cp-mobile-device') ) {
									var mobile_max_width  = $(document).find('.form-control.cp-input.cp-slider.panel_width.slider').data('mobile-max');
									var mobile_max_height = $(document).find('.form-control.cp-input.cp-slider.panel_height.slider').data('mobile-max');
									$(document).find( '.form-control.cp-input.cp-slider.panel_width.slider').data('max', mobile_max_width );
									$(document).find( '.form-control.cp-input.cp-slider.panel_height.slider').data('max', mobile_max_height );
								}
								if( $('html').hasClass('cp-desktop-device') ) {
									var mobile_max_width  = $(document).find('.form-control.cp-input.cp-slider.panel_width.slider').attr('max');
									var mobile_max_height = $(document).find('.form-control.cp-input.cp-slider.panel_height.slider').attr('max');
									$(document).find( '.form-control.cp-input.cp-slider.panel_width.slider').data('max', mobile_max_width );
									$(document).find( '.form-control.cp-input.cp-slider.panel_height.slider').data('max', mobile_max_height );
								}
							break;

							case "numberfield":
								$('.cp-numberfield-container').cp_numberfield();
							break;

							case "multiinput":
								var trigger_event = false; 
								$('.cp-multiinput-container').cp_multiinput_param( trigger_event );
							break;

							case "text-align":
								$(".cp-text-align-field-container").cp_text_align();
									
								$this.closest(".cp-element-container").find(".cp-text-align-holder-field").each(function(){
									if( jQuery(this).find(".cp-input").val() == option_value ) {
										jQuery(this).addClass("selected-text");
										jQuery(this).find(".cp-input").prop( "checked", true );
									} else {
										jQuery(this).removeClass("selected-text");
										jQuery(this).find(".cp-input").prop( "checked", false );
									}
								});

							break;

							case "background_image":
								
								var image_sizes_option = field_name + "_sizes"; 
								var panel_id = step_id + 1;
								var image_sizes = bmodel.getModalValue( "panel-" + panel_id, step_id, image_sizes_option );
								var image_sizes_selector_id = "cp_" + field_name + "_size";
								var image_size_element = jQuery("#" + image_sizes_selector_id );
								var image_props    = ( 'undefined' != typeof option_value && option_value.length > 0 ) ? option_value.split( "|" ) : [];
								var sel_image_size = typeof image_props[2] !== 'undefined' ? image_props[2] : 'full'; 

								if ( typeof image_sizes == 'string' && ConvertProHelper.isJsonString( image_sizes ) ) {
									image_sizes = JSON.parse( ConvertProHelper._decodeHtmlEntity( image_sizes ) );
								}
								
								if( typeof image_sizes !== 'undefined' && 'object' === typeof image_sizes ) {
									
									image_size_element.html('');
									
									for( size in image_sizes ) {

										title = size.replace( "-", "" );
										title = title.charAt(0).toUpperCase() + title.slice(1);

										img_title = title + ' ' + image_sizes[size].width + ' x ' + image_sizes[size].height;
											
										image_size_element.append(jQuery("<option/>", {
											value: image_sizes[size].url,
											text: img_title,
										}));

										image_size_element.find( "option[value='" + image_sizes[size].url +   "']" ).data( "size", size );
									}
								}

								if( image_sizes && typeof image_sizes[sel_image_size] !== 'undefined' ) {
									image_size_element.val( image_sizes[sel_image_size].url );
									image_size_element.trigger('change');
								}
							
							break;

							case "bg_properties":

								var bg_img_container = jQuery(this).closest(".cp-element-container").find(".cp-bgimage-container");
								var bg_prop     = option_value.split('|');	

								var bg_repeat   = bg_prop[0];
								var bg_position = bg_prop[1];
								var bg_size     = bg_prop[2];

								bg_img_container.find("#cp_opt_bg_rpt").val( bg_repeat );
								bg_img_container.find("#cp_opt_bg_pos").val( bg_position );
								bg_img_container.find("#cp_opt_bg_size").val( bg_size );

							break;

							case "box_shadow":

								var box_shadow_container = jQuery(this).closest(".cp-element-container");
								var shadow_prop     = option_value.split('|');
								var settings = {};

								if( shadow_prop.length > 0 && shadow_prop !=='' ) {
									jQuery.each( shadow_prop, function(index, val) {
										var values = val.split(":");					
										settings[values[0]] = values[1];
									});
								}

								var horizontal = settings['horizontal'];
								var blur       = settings['blur'];
								var vertical   = settings['vertical'];
								var spread     = settings['spread'];
								var color      = settings['color'];
								var type       = settings['type'];

								box_shadow_container.find("#blur-radius").val( blur );
								box_shadow_container.find("#spread-field").val( spread );
								box_shadow_container.find("#horizontal-length").val( horizontal );
								box_shadow_container.find("#vertical-length").val( vertical );
								box_shadow_container.find("#cp_shadow_type").val( type );
								box_shadow_container.find("#shadow-color").closest(".wp-picker-container").find(".wp-color-result").css( "background-color", color );

								$('.cs-wp-color-picker').cs_wpColorPicker();
								$(".cp-slider").cp_slider();
							break;
						}
					}
				}

			});

			is_inherit     = typeof is_inherit == 'undefined' ? "1" : is_inherit;

			if( is_inherit == '1' && step_id !== 0 ) {
				jQuery("#cp_inherit_bg_prop").closest(".cp-accordion-content").find(".cp-element-container").hide();
				jQuery("#cp_inherit_bg_prop").closest(".cp-element-container").show();
			} else {
				jQuery("#cp_inherit_bg_prop").closest(".cp-accordion-content").find(".cp-element-container").show();
			}

			if( step_id == 0 ) {
				jQuery("#cp_inherit_bg_prop").closest(".cp-element-container").addClass("cp-hidden");
			} else {
				jQuery("#cp_inherit_bg_prop").closest(".cp-element-container").removeClass("cp-hidden");
			}

			ConvertProSidePanel._handleDependencies();
		},

		_applyMapValues: function( for_edit, map, value, applyPosition, save_panel_position, applyRespective, setModalObject, current_step ) {

			if( map.length === 0 )
				return;

			if( typeof map.attr == 'undefined' || map.attr === '' )
				return;

			if ( typeof applyRespective === 'undefined' ) {
				applyRespective = true;
		    }

			var target = ( typeof map.target == 'undefined' || map.target === '' ) ? false : map.target,
				multiple_target = target.split(',');

			for ( var i = 0; i < multiple_target.length; i++ ) {
				var element = $( '#' + for_edit ),
					attr_call = map.attr;
				
				if( $.trim( multiple_target[i] ) !== false && '.cp-field-html-data' != multiple_target[i] ) {
					element = element.find( $.trim( multiple_target[i] ) );
				}
				
				switch( attr_call ) {
					case "value":
						$(element).val(value);
					break;

					case "text":
						$(element).text(value);		
					break;

					case "src":
						if( typeof value !== 'undefined' && value !== '' ) {
							var image_src = bmodel.getModalValue( for_edit, current_step, 'close_image_type' ),
								canAssign = false,
								val = ( typeof jQuery("#close_image_type").val() !== 'undefined' ) ? jQuery("#close_image_type").val() : 'upload',
								alt = '';

							image_src = typeof image_src !== 'undefined' ? image_src : val;

							// if close image
							if( $( element ).hasClass( 'cp-close-image' ) ) {
								if( image_src == 'upload' ) {
									canAssign = true;
								}
							} else {
								canAssign = true;
							}

							if ( canAssign ) {
								var imagesource = value.split('|');
								var image = ( imagesource[0] == '0' ) ? cp_admin_ajax.assets_url + imagesource[1] : imagesource[ 1 ];
								$(element).attr( attr_call, image );
							}
						}
						
					break;

					case "required":
						if( value == 'true' ) {
							$(element).attr('required', 'required');
						} else {
							$(element).removeAttr('required');
						}
					break;

					case "placeholder":

		                var lbl_as_plceholder = bmodel.getModalValue( for_edit, current_step, 'label_as_placeholder');
						var lbl_text = bmodel.getModalValue( for_edit, current_step, 'input_text_placeholder');

						if( typeof lbl_as_plceholder == 'undefined' ) {
							lbl_as_plceholder = jQuery( '#label_as_placeholder[for=' + for_edit + ']' ).val();
						}

						if( typeof lbl_text == 'undefined' ) {
							lbl_text = jQuery( '#input_text_placeholder[for=' + for_edit + ']' ).val();
						}

						$(element).attr( 'data-placeholder', lbl_text );

						if( $(element).is( "select" ) ) {
							if( typeof lbl_as_plceholder != 'undefined' && lbl_as_plceholder != 'false' ) {
								$(element).find("option[value='-1']").remove();

								$( '<option value="-1" selected="selected">' + lbl_text + '</option>' ).prependTo( $(element) );
								$(element).attr( 'placeholder', lbl_text );
							} else {
								$(element).find("option[value='-1']").remove();
							}
							break;
						} else {
							if( typeof lbl_as_plceholder != 'undefined' && lbl_as_plceholder == 'false' ) {
								$(element).attr( attr_call, '' );
								break;
							}
						}
						
						$(element).attr(attr_call, value);
					break;

					case "label-as-placeholder":

						var lbl_text = bmodel.getModalValue( for_edit, current_step, 'input_text_placeholder');
						var email_text = bmodel.getModalValue( for_edit, current_step, 'email_text_placeholder');

						if( typeof lbl_text == 'undefined' ) {
							lbl_text = jQuery( '#input_text_placeholder[for=' + for_edit + ']' ).val();
						}

						if( typeof email_text == 'undefined' ) {
							email_text = jQuery( '#email_text_placeholder[for=' + for_edit + ']' ).val();
						}

						if( $(element).is( "select" ) ) {
							if( value == 'true' ) {
								$( '<option value="-1" selected="selected">' + lbl_text + '</option>' ).prependTo( $(element) );
							} else {
								$(element).find("option[value='-1']").remove();
							}
							$(element).attr( 'placeholder', lbl_text );

						} else {
							
							( value == 'true' ) ? $(element).attr( 'placeholder', lbl_text ) : $(element).attr( 'placeholder', '');

							if( $( element ).attr( 'type' ) == 'email' ) {
								( value == 'true' ) ? $(element).attr( 'placeholder', email_text ) : $(element).attr( 'placeholder', '');
							}
						}

					break;

					case "button-type":

						if( $(element).hasClass('cp-button-field') ) {
							if( value == 'submit' || value == 'submit_n_goto_step' || value == 'submit_n_close' ) {
								$(element).attr('type', 'submit');
							} else {
								$(element).attr('type', 'button');
							}
						}
					break;

					case "invisible-class":

						if ( value == 'yes' ) {
							$(element).addClass('cp-invisible-on-mobile');
						}
					break;

					case "change-date":
						ConvertProHelper._applyCountdown( $('#' + for_edit), true );
					break;

					case "show-hide-countdown":
						setTimeout( function() {
							var show_months = bmodel.getModalValue( for_edit, current_step, 'show_months'),
								show_days = bmodel.getModalValue( for_edit, current_step, 'show_days'),
								show_mins = bmodel.getModalValue( for_edit, current_step, 'show_mins'),
								show_seconds = bmodel.getModalValue( for_edit, current_step, 'show_seconds'),
								show_hours = bmodel.getModalValue( for_edit, current_step, 'show_hours'),
								targetEle = $('#' + for_edit).find( '.cp-target' );

							if( 'true' == show_months || 'undefined' == typeof show_months ) {
								targetEle.removeClass( 'cpro-show_months' );
							} else {
								targetEle.addClass( 'cpro-show_months' );
							}

							if( 'true' == show_days || 'undefined' == typeof show_days ) {
								targetEle.removeClass( 'cpro-show_days' );
							} else {
								targetEle.addClass( 'cpro-show_days' );
							}

							if( 'true' == show_mins || 'undefined' == typeof show_mins ) {
								targetEle.removeClass( 'cpro-show_mins' );
							} else {
								targetEle.addClass( 'cpro-show_mins' );
							}

							if( 'true' == show_seconds || 'undefined' == typeof show_seconds ) {
								targetEle.removeClass( 'cpro-show_seconds' );
							} else {
								targetEle.addClass( 'cpro-show_seconds' );
							}

							if( 'true' == show_hours || 'undefined' == typeof show_hours ) {
								targetEle.removeClass( 'cpro-show_hours' );
							} else {
								targetEle.addClass( 'cpro-show_hours' );
							}
						}, 200 );

					break;
					
					case "change-class":
						
						var change_class = 'cp-countdown-'+ value;
						
						if ( value == 'block' ) {
							$(element).removeClass( 'cp-countdown-inline' );
						}else{
							$(element).removeClass( 'cp-countdown-block' );
						}
						
						$(element).addClass( change_class );
					break;

					default:
						$(element).attr(attr_call, value);		

						if( save_panel_position ) {
							if ( "data-overlay-respective" == attr_call ) {
								var panel_container_id = current_step + 1;
									
								if ( applyRespective ) {
									
									var cacheElement = $(element);
									var elementJs = cacheElement[0];
									var idToSave = cacheElement.attr('id');
									var respectivePositions = {};
									
									respectivePositions['top'] = elementJs.style.top;
									respectivePositions['left'] = elementJs.style.left;
									
									if( value == 'true' ) {
										
										var previousPos = bmodel.getModalValue( idToSave, 'common', 'respective_to_overlay' )
										
										bmodel.setElementID( 'common', idToSave );
										bmodel.setModalValue( idToSave, 'common', 'respective_to_panel', respectivePositions, false );

										$('#panel-'+ panel_container_id).after( cacheElement );

										if ( typeof previousPos == 'object' ) {
											$(element).css({
												'top' : previousPos.top,
												'left' : previousPos.left,
											});

										} else {
											$(element).css({
												'top' : '15%',
												'left' : '15%',
											});
										}
									} else {

										var previousPos = bmodel.getModalValue( idToSave, current_step, 'respective_to_panel' );
										var is_outside_hide = bmodel.getModalValue( idToSave, current_step, 'is_outside_hide' );

										bmodel.setElementID( current_step, idToSave );
										bmodel.setModalValue( idToSave, current_step, 'respective_to_overlay', respectivePositions, false );

										$('#panel-'+ panel_container_id + ' .panel-content-wrapper' ).append( cacheElement );
										
										if ( typeof previousPos == 'object' ) {
											$(element).css({
												'top' : previousPos.top,
												'left' : previousPos.left,
											});
										}
									}
									
									if( setModalObject ) {

										ConvertProDragDrop._savePanelItemPosition( element, applyPosition, false );
									}
								}

							}
						}
					break;
				}
			}
		},

		_applyCountdownSettings: function( for_edit, value, current_step ) {
			if( value == 'outside' ) {
				var cls_name = '.cp-countdown-holding';
			} else {
				var cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
			}

			var bg_color = bmodel.getModalValue( for_edit, current_step, 'bg_color' );
			var br_color = bmodel.getModalValue( for_edit, current_step, 'countdown_border_color' );
			var br_style = bmodel.getModalValue( for_edit, current_step, 'countdown_border_style' );
			var br_width = bmodel.getModalValue( for_edit, current_step, 'countdown_border_width' );
			var br_radius = bmodel.getModalValue( for_edit, current_step, 'countdown_border_radius' );
			var c_padding = bmodel.getModalValue( for_edit, current_step, 'countdown_field_padding' );

			$( '#cp-countdown-border-style-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-border-style-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { border-style: ' + br_style + ' }</style>' );

			$( '#cp-countdown-border-color-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-border-color-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { border-color: ' + br_color + ' }</style>' );

			var bval = ConvertProHelper._generateMultiInputResult( 'border-width', br_width );
			var op_str = '';	
			$.each( bval, function(index, val) {
				op_str += ' ' + index + ':' + val + ';';
			});

			$( '#cp-countdown-border-width-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-border-width-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { ' + op_str + ' }</style>' );

			var bval = ConvertProHelper._generateMultiInputResult( 'border-radius', br_radius );
			var op_str = '';	
			$.each( bval, function(index, val) {
				op_str += ' ' + index + ':' + val + ';';
			});

			$( '#cp-countdown-border-radius-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-border-radius-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { ' + op_str + ' }</style>' );
			
			$( '#cp-countdown-bg-style-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-bg-style-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { background-color: ' + bg_color + ' }</style>' );

			var bval = ConvertProHelper._generateMultiInputResult( 'padding', c_padding );
			var op_str = '';	
			$.each( bval, function(index, val) {
				op_str += ' ' + index + ':' + val + ';';
			});

			$( '#cp-countdown-padding-' + for_edit ).remove();
			$( 'head' ).append( '<style id="cp-countdown-padding-' + for_edit + '" type="text/css">#' + for_edit + ' ' + cls_name + ' { ' + op_str + ' }</style>' );
		},

		_applySettings: function( for_edit, parameter, value, unit, onhover, target, current_step, is_switch_step, is_switch_to_mobile, is_onload, on_option_change ) {
			current_step = parseInt( current_step );

			var outputHtml  	  = '',
				background_type   = '',
				panel_data        = '',
				panel_data_string = '',
				angle             = '' ,
				lighter_color     = '',
				module_type       = $("#cp_module_type").val();

			if( typeof for_edit === 'undefined' || for_edit === '' ) {
				var current_panel_id = current_step + 1;
				for_edit = current_panel_id;
			}

			if( unit !== null && unit !== '' ) {
				value += unit;
			}

			if( typeof target == 'undefined' || target === '' || false === target ) {
				target = '.cp-target';
			}

			var multiple_target = target.split(','),
				StyleSelector = for_edit + "_cp-target",
				for_edit_selector = $( '#' + for_edit ),
				prevLength = for_edit_selector.find( '.cp-target' ).prev( 'style#' + StyleSelector ).length;

			if( for_edit != 'panel-1' && prevLength === 0 ) {
				$( "<style type='text/css' id='"+StyleSelector+"'></style>" ).insertBefore( for_edit_selector.find('.cp-target') );
			}

			for ( var i = 0; i < multiple_target.length; i++ ) {
				
				var NewParameter = multiple_target[i].split('|');

				if( NewParameter[0].indexOf("::before") > 0 || NewParameter[0].indexOf("::after") > 0 ) {
					if( onhover ) {

						NewParameter[0] = ConvertProHelper._getHoverSelector( NewParameter[0] );
						if( typeof NewParameter[1] == 'undefined' || NewParameter[1] === '' ) {
							ConvertProHelper._setHoverStyle( '#'+for_edit + " " + $.trim(NewParameter[0]), StyleSelector, parameter, value );
						} else {
							ConvertProHelper._setHoverStyle( '#'+for_edit + " " + $.trim(NewParameter[0]), StyleSelector, NewParameter[1], value );
						}
					} else {
						if( typeof NewParameter[1] == 'undefined' || NewParameter[1] === '' ) {
							ConvertProHelper._setHoverStyle( '#'+for_edit + " " + $.trim(NewParameter[0]), StyleSelector, parameter, value );
						} else {
							ConvertProHelper._setHoverStyle( '#'+for_edit + " " + $.trim(NewParameter[0]), StyleSelector, NewParameter[1], value );
						}
					}
					continue;
				}

				if( $.trim( NewParameter[0] ) == '.cp-field-html-data' ) {
					target = $('#'+for_edit);
				} else {
					if( target != 'toggle' && for_edit.indexOf('panel-') == -1 && 'placeholder' !== target ) {
						target = $('#'+for_edit).find( $.trim( NewParameter[0]) );
					} else if( 'placeholder' == target ) {

						ConvertProHelper._setPlaceholderStyle( '#'+for_edit, StyleSelector, parameter, value );

					} else {

						if( for_edit.indexOf('panel-') > -1 && target !== 'toggle' ) {
							target = $('#'+for_edit);
						} else {
							target = $('#cp-open-toggle');
						}
					}
				}
				
				if( typeof NewParameter[1] != 'undefined' && NewParameter[1] !== '' ) {
					parameter = NewParameter[1];
				}

				$( document ).trigger( 'cpro_before_apply_settings', [parameter, target, value, for_edit, current_step] ); 


				switch(parameter){
					case 'class':
						target.removeClass( unit );
						target.addClass( value.replace( unit, '' ) );
					break;

					case 'removeAnimClass':
						target.removeClass( target.attr(unit) );
					break;

					case 'inner-html':
					case 'inner_html':
						target.html(value);
					break;

					case 'loader-position' :
						outputHtml = target.html();
						if( value == 'left' ) {
							outputHtml = outputHtml.replace( '{{left-icon}}', '<i class="cp-icon-loading" data-icon-position="left"></i><div class="cp-loader-container" data-icon-position="left"><div class="cp-btn-loader"></div></div>' ).replace( '{{right-icon}}', '' );
						} else if( value == 'right' ) {
							outputHtml = outputHtml.replace( '{{right-icon}}', '<i class="cp-icon-loading" data-icon-position="right"></i><div class="cp-loader-container" data-icon-position="right"><div class="cp-btn-loader"></div></div>' ).replace( '{{left-icon}}', '' );
						} else {
							outputHtml = outputHtml.replace( '{{left-icon}}', '' ).replace( '{{right-icon}}', '' );
						}
						target.html(outputHtml);
					break;

					case 'icon-space' : 
						if( target.data('icon-position') == 'left' ) {
							target.css( 'margin-right', value );
						} else if(target.data('icon-position') == 'right') {
							target.css( 'margin-left', value );
						}
					break;

					case 'padding' :
					case 'border-width' :
					case 'border-radius' :
						
						if( parameter == 'border-radius' || parameter == 'border-width' ) {
							if( target.hasClass( 'cp-radio-field' ) || target.hasClass( 'cp-checkbox-field' ) ) {
								break;
							}
						}

						var paddingval = ConvertProHelper._generateMultiInputResult( parameter, value );		
						if( onhover ) {
							$.each( paddingval, function(index, val) {	
								var newSelector = ConvertProHelper._getHoverSelector( target.selector );
								ConvertProHelper._setHoverStyle( newSelector, StyleSelector, index, val );
							});
						} else {

							$.each( paddingval, function(index, val) {	
								target.css(index, val);
							});
						}
					break;

					case 'box-shadow' : 

						if( !( target.hasClass( 'cp-radio-field' ) || target.hasClass( 'cp-checkbox-field' ) ) ) {
							var boxshadow = ConvertProHelper._generateBoxShadow(value);
							if( onhover ) {
								$.each( boxshadow, function(index, val) {
									var newSelector = ConvertProHelper._getHoverSelector( target.selector );
									ConvertProHelper._setHoverStyle( newSelector, StyleSelector, parameter, value );
								});
							} else {
								$.each( boxshadow, function(index, val) {
									target.css(index, val);

									var box_val        = value.split("|");
									var result         = {};

									if( box_val.length > 0 && box_val !=='' ) {
										$.each( box_val, function(index, val) {
											var values = val.split(":");
											result[values[0]] = values[1];
										});
									}

									if( result['type'] == 'inset' ) {
										target.addClass('cp-shadow-inset');
									}
								});
							}
						}

						if( target.closest('.cp-field-html-data').attr('data-type') == 'cp_shape' ) {
							var dropshadow = ConvertProHelper._generateDropShadow( value );
							$.each( dropshadow, function(ind, val) {
								target.closest('.cp-field-html-data').css(ind, val);
								target.closest('.cp-field-html-data').find('svg').css('box-shadow','none');
							});
						}

					break;
					
					case 'dropdown-options' :
						var output_html = '';
						if( value !== '' ) {
							var options_arr = value.split("\n");
							var options_arr_length = options_arr.length;
							var lbl_as_plceholder = bmodel.getModalValue( for_edit, current_step, 'label_as_placeholder');
							var input_text_placeholder = bmodel.getModalValue( for_edit, current_step, 'input_text_placeholder');

							if( typeof lbl_as_plceholder == 'undefined' ) {
								lbl_as_plceholder = jQuery( '#label_as_placeholder[for=' + for_edit + ']' ).val();
							}

							if( typeof input_text_placeholder == 'undefined' ) {
								input_text_placeholder = jQuery( '#input_text_placeholder[for=' + for_edit + ']' ).val();
							}

							if( typeof lbl_as_plceholder != undefined && lbl_as_plceholder != 'false' ) {
								output_html += '<option value="-1">'+ input_text_placeholder +'</option>';
							}
							for ( var options_index = 0; options_index < options_arr_length; options_index++ ) {
								output_html += '<option value="'+ options_arr[options_index] +'">'+ options_arr[options_index] +'</option>';
							}
						}
						target.html(output_html);
					break;

					case 'radio-options' :

						var output_html = '';
						
						if( value !== '' ) {
							var options_arr = value.split("\n");
							var options_arr_length = options_arr.length;

							for ( var options_index = 0; options_index < options_arr_length; options_index++ ) {
								output_html += '<div class="cp-radio-wrap"><label><input type="radio" name="cp-radio" value="'+ options_arr[options_index] +'">'+ options_arr[options_index] + '</label></div>';
							}
						}
						target.html(output_html);
					break;

					case 'radio-orientation':

						if( 'undefined' == typeof is_switch_to_mobile && true == on_option_change ) {

							target.css( 'width', 'auto' );

							var wd = target.outerWidth();
							wd = wd + 10;

							target.css( 'width', wd );
							bmodel.setModalValue( for_edit, current_step, 'width', wd );

						}

						if( value == 'cp-horizontal-orien' ) {
							target.removeClass( 'cp-vertical-orien' );
						} else {
							target.removeClass( 'cp-horizontal-orien' );
						}
						target.addClass( value );
						break;


					case 'radio-image':
						var image_src = bmodel.getModalValue( for_edit, current_step, 'close_image_type' );
						image_src = typeof image_src !== 'undefined' ? image_src : jQuery("#close_image_type").val();
						
						value = cp_admin_ajax.assets_url + value;

						if( image_src == 'predefined' ) {
							$( '#' + for_edit ).find( 'img.cp-close-image' ).attr( 'src', value );
						}
						break;

					case 'close-image-type':

						var pimage = bmodel.getModalValue( for_edit, current_step, 'module_radio_image' );
						var uimage = bmodel.getModalValue( for_edit, current_step, 'module_image' );
						
						if( value == 'predefined' ) {
							pimage = cp_admin_ajax.assets_url + pimage;

							$( '#' + for_edit ).find( 'img.cp-close-image' ).attr( 'src', pimage );
						} else {
							if( typeof uimage != 'undefined' ) {
								var imagesource = uimage.split('|');
								var image = imagesource[ imagesource[0] ];

								// default image case
								if( imagesource[0] == '0' ) {
									image = cp_admin_ajax.assets_url + imagesource[1];
								}

								var alt = imagesource[3];
								$( '#' + for_edit ).find( 'img.cp-close-image' ).attr( 'src', image );
								$( '#' + for_edit ).find( 'img.cp-close-image' ).attr( 'alt', alt );
							}
						}
						break;

					case 'checkbox-orientation':

						if( typeof is_switch_to_mobile == 'undefined' && true == on_option_change ) { 
							target.css( 'width', 'auto' );

							var wd = target.outerWidth();
							wd = wd + 10;
							
							target.css( 'width', wd );
							bmodel.setModalValue( for_edit, current_step, 'width', wd );
						}

						if( value == 'cp-horizontal-orien' ) {
							target.removeClass( 'cp-vertical-orien' );
						} else {
							target.removeClass( 'cp-horizontal-orien' );
						}
						target.addClass( value );
						break;

					case 'radio-size':
						target.css( 'width', value );
						target.css( 'height', value );
						target.closest( '.cp-target' ).find( '.cp-radio-wrap' ).css( 'line-height', value + 'px' );

						$('#radio-size-before-css').remove();

						var before_val = ( ( value - 10 ) < 1 ) ? 6 : ( value - 10 );

						$( "<style id='radio-size-before-css'>#" + for_edit + " input[type=radio]::before { width:" + before_val + "px; height:" + before_val + "px; }</style>" ).appendTo( "head" );
					break;

					case 'checkbox-options' :
						var output_html = '';
						
						if( value !== '' ) {
							var options_arr = value.split("\n");
							var options_arr_length = options_arr.length;

							for ( var options_index = 0; options_index < options_arr_length; options_index++ ) {
								var optons_key = options_arr[options_index].trim().replace(/ /g, '_').replace(/[^a-z0-9_]+/gi, '');
								
								output_html += '<div class="cp-checkbox-wrap"><label><input type="checkbox" value="'+ optons_key +'">'+ options_arr[options_index] + '</label></div>';
							}
						}
						target.html(output_html);
					break;

					case 'hidden-input':
						var tr = target.closest( '.cp-field-html-data' ).html();
						target.closest( '.cp-field-html-data' ).html( tr.replace( '{{backend_view}}', '<p> ' + cp_pro.hidden_field_text + ' </p>' ) );
						var hidden_input_name = bmodel.getModalValue( for_edit, current_step, 'hidden_input_name' );
						hidden_input_name = typeof hidden_input_name == 'undefined' ? target.attr( 'name' ) : hidden_input_name;

						target.attr( 'placeholder', hidden_input_name );
						target.attr( 'data-placeholder', hidden_input_name );
					break;

					case 'hidden-input-name':
						target.attr( 'placeholder', value );
						target.attr( 'data-placeholder', value );
					break;

					case 'class-name':		
						var prev = $('#'+for_edit).data("custom-class");	
						$('#'+for_edit).removeClass(prev);		
						$('#'+for_edit).addClass(value);				
						$('#'+for_edit).data("custom-class",value);
					break;

					case 'background-image':
						var img = '' ;
						var img_src = '';
						var angle = '';
						var background_type = '';
						var gadient_type  = '';

						background_type = bmodel.getModalValue( "panel-" + (  current_step + 1 ), current_step, "background_type" );
						panel_data = bmodel.get("panel_data");
						panel_data_string = panel_data[current_step];

						var is_inherit = bmodel.getModalValue( "panel-" + ( current_step + 1 ), current_step, "inherit_bg_prop" );
						is_inherit = typeof is_inherit == 'undefined' ? "1" : is_inherit;

						if( is_inherit == '1' ) {
							panel_data_string = panel_data[0];
						}

						$.each( panel_data_string, function( obj_index, obj_val) {
							$.each( obj_val, function( key, val) {	
								
								val = bmodel.getDeviceValue( val, key );

								if( key == 'panel_bg_image' ){
									img = val;
								}
								if( key == 'panel_background_color' ){
									background_color = val;
								}
								if( key == 'background_type' ) {
									background_type = val ;
								}
								if( key == 'panel_gradient_type' ){
									gadient_type = val;
								}
								if( key == 'gradient_angle' ) {
									angle = val ;
								}
								if( key == 'opt_bg') {
									opt_bg = val;
								}
							});
						});

						if( is_inherit == '1' ) {
							value = img;
						}

						opt_bg = typeof opt_bg == 'undefined' ? bmodel.getModalValue( "panel-1", 0, "opt_bg" ) : opt_bg;
						var bg_option = opt_bg.split("|");
					
						if( bg_option.length > 0 ) {
							var	bg_repeat = bg_option[0];
							var	bg_pos    = bg_option[1];
							var	bg_size   = bg_option[2];
						}
						
						if( background_type == 'image' ) {

							var panel_img_bg = $(".panel_img_overlay_color").val();
							ConvertProHelper._getSelectedImage( value, img, 'background-image', for_edit, current_step );
							$('#'+for_edit).css( "background-color", panel_img_bg );
							
							$('#'+for_edit).css({
								'background-repeat': bg_repeat,
								'background-position': bg_pos,
								'background-size': bg_size,
							});
						}

					break;

					case 'panel-img-overlay-color':

						var panel_id = current_step + 1;
						var bg_type = bmodel.getModalValue( "panel-" +  panel_id, current_step, "background_type" );	

						var is_inherit = bmodel.getModalValue( "panel-" + panel_id, current_step, "inherit_bg_prop" );
						is_inherit = typeof is_inherit == 'undefined' ? "1" : is_inherit;

						if( is_inherit == '1' ) {
							bg_type = bmodel.getModalValue( "panel-1", 0, "background_type" );	
						}

						if( bg_type == 'image' ) {
							$('#panel-img-after-css').remove();

							$('#'+for_edit).css( "background-color", value );

							$( "<style id='panel-img-after-css'>#" + for_edit + "::before { background-color:" + value + "; }</style>" ).appendTo( "head" );
						}

					break;
					
					case 'background-type':	

						var panel_id = current_step + 1;
						var background_color = '';
						var background_image_src = '';
						//retrive value for background color				
						panel_data = bmodel.get("panel_data");
						panel_data_string = panel_data[current_step];
					
						var is_inherit = bmodel.getModalValue( "panel-" + panel_id, current_step, "inherit_bg_prop" );
						is_inherit = typeof is_inherit == 'undefined' ? "1" : is_inherit;

						if( is_inherit == '1' ) {
							panel_data_string = panel_data[0];
						}

						$.each( panel_data_string, function( obj_index, obj_val) {
							$.each( obj_val, function( key, val) {	

								val = bmodel.getDeviceValue( val, key );
								
								if( key == 'panel_background_color' ){
									background_color = val;
								}
								if( key == 'panel_bg_image' ){
									background_image = val;
								}
								if( key == 'panel_gradient_type' ){
									gadient_type = val;
								}
								if( key == 'gradient_angle' ) {
									angle = val ;
								}

								if( key == 'background_type' ) {
									bg_type = val;
								}
							});
						});

						bg_type = ( typeof bg_type == 'undefined' || is_inherit == '0' ) ? value : bg_type;

						/* Remove overaly color */
						$('#panel-img-after-css').remove();

						if( bg_type == 'color' ) {
							
							$('#'+for_edit).css( "background", "" );
							$('#'+for_edit).css( "background", background_color );

						} else if( bg_type == 'image' ) {

							var opt_bg = bmodel.getModalValue( "panel-" +  panel_id, current_step, "opt_bg" );

							if( is_inherit == '1' ) {
								opt_bg =  bmodel.getModalValue( "panel-1", 0, "opt_bg" );
							}

							opt_bg = typeof opt_bg == 'undefined' ? bmodel.getModalValue( "panel-1", 0, "opt_bg" ) : opt_bg;
							var bg_option = opt_bg.split("|");
					
							if( bg_option.length > 0 ) {
								var	bg_repeat = bg_option[0];
								var	bg_pos    = bg_option[1];
								var	bg_size   = bg_option[2];

								$('#'+for_edit).css({
									'background-repeat': bg_repeat,
									'background-position': bg_pos,
									'background-size': bg_size,
								});
							}

							var panel_img_bg = $(".panel_img_overlay_color").val();
							$('#'+for_edit).css( "background-color", panel_img_bg );

							$( "<style id='panel-img-after-css'>#" + for_edit + "::before { background-color:" + panel_img_bg + "; }</style>" ).appendTo( "head" );

							ConvertProHelper._getSelectedImage( background_image, background_image, 'background_type', for_edit, current_step );	

						} else {

							darker_color    = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'panel_darker_color' );
							lighter_color   = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'panel_lighter_color' );
							location_1		= bmodel.getModalValue( "panel-" +  panel_id, current_step, 'gradient_lighter_location' );
							location_2		= bmodel.getModalValue( "panel-" +  panel_id, current_step, 'gradient_darker_location' );
							angle 			= bmodel.getModalValue( "panel-" +  panel_id, current_step, 'gradient_angle' );
							gradient_type 	= bmodel.getModalValue( "panel-" +  panel_id, current_step, 'panel_gradient_type' );
							gradient_dir 	= bmodel.getModalValue( "panel-" +  panel_id, current_step, 'radial_panel_gradient_direction' );

							//apply graidient color to div
							if( for_edit.indexOf('panel-') > -1 ) {

								if( gradient_type == 'radialgradient' ){
									//apply graidient color to div
									ConvertProColor._applyRadialGradientColor( gradient_dir, for_edit, lighter_color, location_1, darker_color, location_2 );
								}
						
								if( gradient_type == 'lineargradient' ){
									//apply graidient color to div
									ConvertProColor._applyGradientColor( for_edit, lighter_color, location_1 , darker_color, location_2, angle);
								}
							}
						}
					break;

					case 'radial-gradient-direction':			
					case 'gradient-darker-location':
					case 'gradient-lighter-location':
					case 'lighten-color':
					case 'darken-color':
					case 'gradient-type':
					case 'gradient-angle':
					case 'panel-gradient-type':

						var panel_id = current_step + 1;
						panel_data = bmodel.get("panel_data");
						panel_data_string = panel_data[current_step];
						var background_image = '',
							gadient_type     = '',
							lighter_color    = '',
							location_lighter = '',
							location_darker	 = '',
							darker_color     = '';

						var is_inherit = bmodel.getModalValue( "panel-" + panel_id, current_step, "inherit_bg_prop" );
						is_inherit = typeof is_inherit == 'undefined' ? "1" : is_inherit;

						if( is_inherit == '1' ) {
							panel_data_string = panel_data[0];
						}

						$.each( panel_data_string, function( obj_index, obj_val) {
							$.each( obj_val, function( key, val) {	
								
								val = bmodel.getDeviceValue( val, key );

								if( key == 'panel_bg_image' ){
									background_image = val;
								}
								if( key == 'background_type' ) {
									background_type = val;
								}
								if( key == 'panel_lighter_color' ) {
									lighter_color = val;
								}
								if( key == 'panel_darker_color' ) {
									darker_color = val;
								}
								if( key == 'gradient_angle' ) {
									angle = val;
								}
							});
						});

						if( background_type == 'gradient' ) {	

							gadient_type	 = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'panel_gradient_type' );
							location_darker	 = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'gradient_darker_location' );
							location_lighter = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'gradient_lighter_location' );
							radial_gradient_direction = bmodel.getModalValue( "panel-" +  panel_id, current_step, 'radial_panel_gradient_direction' );

							if( gadient_type == 'radialgradient' ){

								//apply graidient color to div
								ConvertProColor._applyRadialGradientColor( radial_gradient_direction, for_edit, lighter_color, location_lighter, darker_color, location_darker );
							}
						
							if( gadient_type == 'lineargradient' ){
								//apply graidient color to div

								ConvertProColor._applyGradientColor( for_edit, lighter_color, location_lighter , darker_color, location_darker, angle );
							}
						}
					break;			

					case 'overlay-gradient-type':
					case 'overlay-color':
					case 'overlay-lighter-color':
					case 'overlay-lighter-location':
					case 'overlay-darker-color':
					case 'overlay-darker-location':
					case 'overlay-panel-gradient-type':
					case 'radial-overlay-gradient-direction':
					case 'overlay-gradient-angle':

						panel_data = bmodel.get("panel_data");
						panel_data_string = panel_data[current_step];
						var background_color = '',
							gradient_type    = '',
							lighter_color    = '',
							darker_location  = '',
							lighter_location = '',
							darker_color     = '';

						$.each( panel_data_string, function( obj_index, obj_val) {
							$.each( obj_val, function( key, val) {

								val = bmodel.getDeviceValue( val, key );

								if( key == 'overlay_lighter_color' ) {
									lighter_color = val;
								}
								if( key == 'overlay_darker_color' ) {
									darker_color = val;
								}
								if( key == 'overlay_gradient_type' ) {
									overlay_gradient_type = val;
								}
								if( key == 'panel_overlay_color' ) {
									panel_overlay_color = val;
								}
							});

							if( typeof overlay_gradient_type !== 'undefined' && overlay_gradient_type == 'gradient' ){
								
								angle = $(".overlay_gradient_angle").val();
								darker_location	 = $(".overlay_darker_location").val();
								lighter_location = $(".overlay_lighter_location").val();
								gradient_direction = $(".radial_overlay_gradient_direction").val();
								gradient_type = $(".overlay_panel_gradient_type").val();

								if( gradient_type == 'radialgradient' ){
									//apply graidient color to div
									ConvertProColor._applyRadialOverlayGradientColor( gradient_direction, for_edit, lighter_color, lighter_location, darker_color, darker_location );
								}
							
								if( gradient_type == 'lineargradient' ){
									//apply graidient color to div
									ConvertProColor._applyOverlayGradientColor( for_edit, lighter_color, lighter_location , darker_color, darker_location, angle );
								}
							} else if ( typeof overlay_gradient_type !== 'undefined' && overlay_gradient_type == 'color' ) {

								$( '#'+for_edit ).parents( ".cp-popup-wrapper" ).css( "background", panel_overlay_color );
							}  
							
						});
					break;

					//button gradient background
					case 'btn-gradient-type-hover':
					case 'btn-gradient-angle-hover':
					case 'btn-gradient-bg1-hover':
					case 'btn-gradient-bg2-hover':
					case 'btn-gradient-loc-1-hover':
					case 'btn-gradient-loc-2-hover':
					case 'btn-gradient-radial-dir-hover':
					case 'btn-gradient-hover-options':
						target.attr('data-'+parameter, value);
						ConvertProColor._getHoverGradientDependentValue( target, for_edit );
					break;

					//button gradient background
					case 'btn-gradient-type':
					case 'btn-gradient-angle':
					case 'btn-gradient-bg1':
					case 'btn-gradient-bg2':
					case 'btn-gradient-loc-1':
					case 'btn-gradient-loc-2':
					case 'btn-gradient-radial-dir':
						target.attr('data-'+parameter, value);
						ConvertProColor._getGradientDependentValue( target, for_edit );	
					break;
					
					case "font-family":

						if( 'undefined' !== typeof value ) {
							var font_family = value.split(":");

							var font_weight = '';
							if( font_family.length > 0 ) {
								value = font_family[0];
								font_weight = font_family[1];
								target.css(parameter, value);
								target.css('font-weight', font_weight);
							}
						}
					break;
						  
					case 'background-opt':
					
						var bg_option = value.split("|");
						var panel_id = "panel-" + ( current_step + 1 );  
						var bg_type = bmodel.getModalValue( panel_id, current_step, "background_type" );
						
						if( bg_type == 'image' ) {
							if( bg_option.length > 0 ) {
								var	bg_repeat = bg_option[0];
								var	bg_pos    = bg_option[1];
								var	bg_size   = bg_option[2];
												
								$('#'+for_edit).css({
									'background-repeat': bg_repeat,
									'background-position': bg_pos,
									'background-size': bg_size,
								});
							}
						}

					break;

					case 'entry-animation':	

						if( typeof is_switch_step !== 'undefined' && is_switch_step == true ) {
							continue;
						}

						var popup_wrapper = $('.cp-popup-wrapper');
						var anim_container = $('#'+for_edit).parents(".cpro-animate-container");
						
						popup_wrapper.css({
							'overflow': 'hidden'
						});
						anim_container.addClass( 'cp-animated '+ value );

						setTimeout(function() {
							anim_container.removeClass('cp-animated');
							anim_container.removeClass( value );
							popup_wrapper.css({
								'overflow': ''
							})						
						}, 600);
					
					break;

					case 'position':	
						
						if ( 'info_bar' == module_type || 'slide_in' == module_type ) {
							var pos_array = ['cp-top', 'cp-bottom', 'cp-top-left', 'cp-top-right', 'cp-top-center', 'cp-bottom-left', 'cp-bottom-right', 'cp-bottom-center', 'cp-center-left' ,'cp-center-right' ];
							
							$.each( pos_array, function( index, classname ) {
								$('#'+for_edit).removeClass(classname);
							});

							$('#'+for_edit).addClass( 'cp-'+value );
							var panel_toggle = jQuery("#cp_panel_toggle").val();
							var panel_info_toggle = jQuery("#cp_panel_toggle_infobar").val();

							if( panel_toggle == '1' ) {
								ConvertProHelper._setPanelPosition( value );
							}

							if ( panel_info_toggle == '1' ) {
								ConvertProHelper._setInfoBarPanelPosition( value );
							}
							
							if( module_type == 'info_bar' || module_type == 'slide_in' ) {
								ConvertProHelper._toggle( for_edit, $('#cp_panel_toggle').val(), module_type );
							}
						}

					break;

					case 'transform':
						if( for_edit.indexOf('panel-') == -1 ) {
							$('#'+for_edit +" .cp-rotate-wrap").css( "transform", "rotate("+ value +  "deg)" );
						}
					break;

					case 'toggle':
						if( module_type == 'info_bar' || module_type == 'slide_in' ) {
							ConvertProHelper._toggle( for_edit, value, module_type );
						}
					break;

					case 'fill':
						if( target.length > 0 && value != '' ) {
							if( onhover == true ) {
								var StyleSelector = for_edit + '_cp-target';
								var target = target.selector + ':hover';
								ConvertProHelper._setHoverStyle( target, StyleSelector, parameter, value );
							} else {
								var preset = target.closest('.cp-panel-field').data( 'preset' );
								if( typeof preset != 'undefined' ) {
									if( preset == 'square01' ) {
										target.find('rect').css( 'stroke', value );
									} else if( preset == 'line05' || preset == 'line06' || preset == 'line07' ) {
										target.find('line').css( 'stroke', value );
									} else {
										target.css( parameter, value );
									}
								} else {
									target.css( parameter, value );	
								}
							}
						}
						
					break;

					case 'video-source': 

						var video_source = value;
						var video_url = bmodel.getModalValue( for_edit, current_step, 'video_url' );

						switch( video_source ) {
							case 'youtube':
							case 'vimeo':
								video_url  = bmodel.getModalValue( for_edit, current_step, 'video_id' );
							break;
						}

						ConvertProHelper._renderVideo( for_edit, video_source, video_url );

					break;

					case 'video-url':

						var video_source = bmodel.getModalValue( for_edit, current_step, 'video_source' );
						video_source = 'undefined' !== typeof video_source ? video_source : 'youtube';
						var video_url = value;

						if( 'custom_url' != video_source ) {
							video_url = bmodel.getModalValue( for_edit, current_step, 'video_id' );
						}

						ConvertProHelper._renderVideo( for_edit, video_source, video_url );

					break;

					case 'video-id': 

						var video_source = bmodel.getModalValue( for_edit, current_step, 'video_source' );
						video_source = 'undefined' !== typeof video_source ? video_source : 'youtube';
						var video_url = bmodel.getModalValue( for_edit, current_step, 'video_url' );

						if( 'custom_url' != video_source ) {
							video_url = value;	
						}

						ConvertProHelper._renderVideo( for_edit, video_source, video_url );

					break;

					case 'stroke-width':
						if( target.length > 0 && value != '' ) {
							var preset = target.closest('.cp-panel-field').data( 'preset' );
							if( typeof preset != 'undefined' ) {
								if( preset == 'square01' ) {
									target.find('rect').css( 'stroke-width', value );
								} else if( preset == 'line05' ) {
									target.find('line').css( 'stroke-width', value );
									target.css( 'height', value );
								} else if( preset == 'line06' ) {
									target.find('line').css( 'stroke-width', value );
									target.find('line').attr( 'stroke-dasharray', ( value * 3 ) + ', ' + ( value * 3 ) );
									target.css( 'height', value );
								} else if( preset == 'line07' ) {
									target.find('line').css( 'stroke-width', value );
									target.find('line').attr( 'stroke-dasharray', '1,' + ( value * 2 ) );
									target.find('line').attr( 'x1', ( value / 2 ) );
									target.css( 'height', value );
								} else {
									target.css( parameter, value );
								}
							} else {
								target.css( parameter, value );	
							}
						}
					break;

					case 'countdown-background':
						
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var bg_color = '#' + for_edit + ' ' + cls_name + ' { background-color: ' + value + ' }';

						$( '#cp-countdown-bg-style-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-bg-style-' + for_edit + '" type="text/css">' + bg_color + '</style>' );
						break;

					case 'countdown-text-color':
						var txt_color = '#' + for_edit + ' .cp-countdown-unit-wrap .cp-countdown-unit { color: ' + value + ' }';

						$( '#cp-countdown-text-color-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-text-color-' + for_edit + '" type="text/css">' + txt_color + '</style>' );
						break;

					case 'countdown-number-color':
						var digit_color = '#' + for_edit + ' .cp-countdown-digit-wrap .cp-countdown-digit { color: ' + value + ' }';

						$( '#cp-countdown-number-color-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-number-color-' + for_edit + '" type="text/css">' + digit_color + '</style>' );
						break;

					case 'inside-outside':
						ConvertProHelper._applyCountdownSettings( for_edit, value, current_step );
						break;

					case 'text-space':
						var text_space = '#' + for_edit + ' .cp-countdown-unit-wrap { margin-top: ' + value + ' }';
						$( '#cp-countdown-text-space-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-text-space-' + for_edit + '" type="text/css">' + text_space + '</style>' );
						break;

					case 'countdown-number-font-size':
						var number_font_size = '#' + for_edit + ' .cp-countdown-digit, #' + for_edit + ' .cp-target { font-size: ' + value + ' }';
						$( '#cp-countdown-number-font-size-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-number-font-size-' + for_edit + '" type="text/css">' + number_font_size + '</style>' );
						break;

					case 'countdown-text-font-size':
						var text_font_size = '#' + for_edit + ' .cp-countdown-unit { font-size: ' + value + ' }';
						$( '#cp-countdown-text-font-size-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-text-font-size-' + for_edit + '" type="text/css">' + text_font_size + '</style>' );
						break;

					case 'countdown-border-style':
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var style = '#' + for_edit + ' ' + cls_name + ' { border-style: ' + value + ' }';

						$( '#cp-countdown-border-style-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-border-style-' + for_edit + '" type="text/css">' + style + '</style>' );
						break;

					case 'countdown-border-color':
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var style = '#' + for_edit + ' ' + cls_name + ' { border-color: ' + value + ' }';

						$( '#cp-countdown-border-color-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-border-color-' + for_edit + '" type="text/css">' + style + '</style>' );
						break;

					case 'countdown-border-width':
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var bval = ConvertProHelper._generateMultiInputResult( 'border-width', value );
						var output_str = '';	
						$.each( bval, function(index, val) {
							output_str += ' ' + index + ':' + val + ';';
						});

						var style = '#' + for_edit + ' ' + cls_name + ' { ' + output_str + ' }';

						$( '#cp-countdown-border-width-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-border-width-' + for_edit + '" type="text/css">' + style + '</style>' );
						break;

					case 'countdown-border-radius':
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var bval = ConvertProHelper._generateMultiInputResult( 'border-radius', value );
						var output_str = '';	
						$.each( bval, function(index, val) {
							output_str += ' ' + index + ':' + val + ';';
						});

						var style = '#' + for_edit + ' ' + cls_name + ' { ' + output_str + ' }';

						$( '#cp-countdown-border-radius-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-border-radius-' + for_edit + '" type="text/css">' + style + '</style>' );
						break;

					case 'countdown-padding':
						var in_out = bmodel.getModalValue( for_edit, current_step, 'inside_outside' );
						var cls_name = '.cp-countdown-holding';

						if( in_out == 'inside' ) {
							cls_name = '.cp-countdown-holding .cp-countdown-digit-wrap';
						}

						var bval = ConvertProHelper._generateMultiInputResult( 'padding', value );
						var output_str = '';	
						$.each( bval, function(index, val) {
							output_str += ' ' + index + ':' + val + ';';
						});

						var style = '#' + for_edit + ' ' + cls_name + ' { ' + output_str + ' }';

						$( '#cp-countdown-padding-' + for_edit ).remove();
						$( 'head' ).append( '<style id="cp-countdown-padding-' + for_edit + '" type="text/css">' + style + '</style>' );
						break;

					default:

						if( for_edit.indexOf('panel-') > -1 ) {

							if ( parameter == 'max-width' || parameter == 'width' ) {

								var maxWidth = parseInt( value );

								if( module_type == 'info_bar' || module_type == 'welcome_mat' ||  module_type == 'full_screen' ) {
									$('#'+for_edit).find(".panel-content-wrapper").css({ "max-width": maxWidth + "px", "width": maxWidth });
									$('#'+for_edit).css({ "min-width":"100%" });
								} else {

									$('#'+for_edit).css({
										"max-width": maxWidth + "px", 
										"width"    : maxWidth 
									});
								}

								if( module_type == 'info_bar' && jQuery("#cp_panel_toggle_infobar").val() == '1' ) {
									ConvertProHelper._applyInfobarToggle();
								}

								if( module_type == 'slide_in' && jQuery("#cp_panel_toggle").val() == '1' ) {
									ConvertProHelper._applySlideinToggle();
								}

							} else {

								var panel_id = "panel-" + ( current_step + 1 );
								if( parameter == 'background-color' ) {

									var is_inherit = bmodel.getModalValue( panel_id, current_step, "inherit_bg_prop" );
									is_inherit     = typeof is_inherit == 'undefined' ? true : is_inherit;

									var bg_type = bmodel.getModalValue( panel_id, current_step, "background_type" );

									if( '1' == is_inherit ) {
										bg_type = bmodel.getModalValue( "panel-1", 0, "background_type" );
									}

									if( bg_type == 'color' ) {
										target.css( parameter, value );
									}
								} else {
									target.css( parameter, value );
								}
							}
							
							// set height for svg 
							if( parameter == 'height' ) {

								if( module_type == 'welcome_mat' || module_type == 'full_screen' ) {
									var maxHeight = parseInt( value );
									$('#'+for_edit).find(".panel-content-wrapper").css({ "max-height": maxHeight + "px", "height": maxHeight });
									$('#'+for_edit).css({ "min-height":"100%" });
								}

								$('#'+for_edit).find('svg').attr( "height", value );

								if( module_type == 'info_bar' && jQuery("#cp_panel_toggle_infobar").val() == '1' ) {
									ConvertProHelper._applyInfobarToggle();
								}

								if( module_type == 'slide_in' && jQuery("#cp_panel_toggle").val() == '1' ) {
									ConvertProHelper._applySlideinToggle();
								}
							}
						} else {
							if( onhover ) {
								var newSelector = ConvertProHelper._getHoverSelector( target.selector );
								ConvertProHelper._setHoverStyle(newSelector, StyleSelector, parameter, value);
							} else {	

								if( target.length > 0 ) {
									if( parameter == 'border-style' || parameter == 'background-color' ) {

										if( parameter == 'background-color' ) {
											if( !( target.hasClass( 'cp-radio-field' ) || target.hasClass( 'cp-checkbox-field' ) ) ) {
												target.css( parameter, value );
											} else {
												var radio = jQuery(target).find('input[type="radio"]');
												var checkbox = jQuery(target).find('input[type="checkbox"]');

												radio.css( "background-color", value );
												checkbox.css( "background-color", value );
											}
										}

										if( !( target.hasClass( 'cp-radio-field' ) || target.hasClass( 'cp-checkbox-field' ) ) ) {
											target.css( parameter, value );
										}

									} else {	

										if( 'placeholder' !== target ) {
											target.css( parameter, value );
										}
									}
								}
							}
						}

						if ( parameter == 'letter-spacing' ) {
							target.css( "letter-spacing", value + 'px' );
						}

						// auto image size
						if( parameter == 'width' || parameter == 'height' ) {
							var WidthHeight = parseInt( value );
							if( WidthHeight == 0 ) {
								
								if( for_edit.indexOf('cp_close_link-') > -1 ) {

									$('#'+for_edit).find('.cp-close-field').css( parameter, 'auto' );
								}
							}
							if( parameter == 'height' ) {
								if( target.closest( '.cp-panel-item' ).hasClass( 'cp-shapes-wrap' ) ) {
									var shape_type = target.closest( '.cp-panel-item' ).data('type'),
										shape_preset = target.closest( '.cp-panel-item' ).data('preset');
									if( typeof shape_type != 'undefined' && shape_type == 'cp_shape' ) {
										if( typeof shape_preset != 'undefined' && ( shape_preset == 'line05' || shape_preset == 'line06' || shape_preset == 'line07' ) ) {
											var wd = bmodel.getModalValue( for_edit, current_step, 'shape_width' );
											target.css( parameter, wd );
										}
									}
								}
							}
						}
					break;
				}
			}
		},
	}

	ConvertProHelper.init();

	ConvertProColor = {

		/**
	     * Initializes the all class variables.
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    init: function( e ) {
	    },

		_applyGradientColor: function( for_edit, lighter_color, location_1, darker_color, location_2, angle ) {
			$('#'+for_edit).css({
				'background': lighter_color,
		        'background':'-webkit-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-moz-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-ms-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-o-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
			});
		},

		_applyHoverGradientColor: function( for_edit, lighter_color, location_1, darker_color, location_2, angle, imp ) {
			var gradient_hover = '';

			gradient_hover = '#'+for_edit+' .cp-target:hover { ' +
				'background: ' + lighter_color + imp + ';' +
		        'background: -webkit-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
		        'background: -moz-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
		        'background: -ms-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
		        'background: -o-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
		        'background: linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
			'}';

			$('#cp-gradient-hover-style-'+for_edit).remove()
			$('head').append('<style id="cp-gradient-hover-style-'+for_edit+'" type="text/css">' + gradient_hover + '</style>');
		},

		_applyOverlayGradientColor: function( for_edit, lighter_color, location_1, darker_color, location_2, angle ) {
			$('#'+for_edit).parents(".cp-popup-wrapper").css({
				'background': lighter_color,
		        'background':'-webkit-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-moz-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-ms-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'-o-linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
		        'background':'linear-gradient('+angle+'deg, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',  
			});
		},

		_applyRadialGradientColor: function( radial_gadient_type, for_edit, lighter_color, location_1, darker_color, location_2 ) {
			switch( radial_gadient_type ){
				case 'center_center':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': '-o-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': '-moz-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': 'radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
			    	});
				break;

				case 'center_left':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'center_right':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_center':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_left':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_right':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_center':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_left':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_right':
					$('#'+for_edit).css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;
			}
		},

		_applyHoverRadialGradientColor: function( radial_gadient_type, for_edit, lighter_color, location_1, darker_color, location_2, imp ) {
			var gradient_hover = '';

			switch( radial_gadient_type ) {

				case 'center_center':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'center_left':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'center_right':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'top_center':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'top_left':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'top_right':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'bottom_center':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'bottom_left':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;

				case 'bottom_right':
					gradient_hover = '#'+for_edit+' .cp-target:hover { '+
						'background: ' + lighter_color + imp + ';' +
						'background: -webkit-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -o-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: -moz-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
						'background: radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%) '+imp+';' +
			    	'}';
				break;
			}
			$('#cp-gradient-hover-style-'+for_edit).remove()
			$('head').append('<style id="cp-gradient-hover-style-'+for_edit+'" type="text/css">' + gradient_hover + '</style>');
		},

		_applyRadialOverlayGradientColor: function( radial_gadient_type, for_edit, lighter_color, location_1, darker_color, location_2 ) {
			switch( radial_gadient_type ){
				case 'center_center':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': '-o-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': '-moz-radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
						'background': 'radial-gradient( at center center, '+lighter_color+' '+location_1+'%, '+darker_color+' '+location_2+'%)',
			    	});
				break;

				case 'center_left':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at center left, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'center_right':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at center right, '+lighter_color+' '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_center':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_left':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'top_right':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at top right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_center':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom center, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_left':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom left, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;

				case 'bottom_right':
					$('#'+for_edit).parents(".cp-popup-wrapper").css({
						'background': lighter_color,
						'background': '-webkit-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-o-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': '-moz-radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
						'background': 'radial-gradient( at bottom right, '+lighter_color+'  '+location_1+'%, '+darker_color+'  '+location_2+'%)',
			    	});
				break;
			}
		},

		_getGradientDependentValue: function( target, for_edit ) {
			var gradient_angle     = target.attr('data-btn-gradient-angle'),
				lighter_color      = target.attr('data-btn-gradient-bg1'),
				darker_color       = target.attr('data-btn-gradient-bg2'),
				btn_gradient_type  = target.attr('data-btn-gradient-type'),
				lighter_location   = target.attr('data-btn-gradient-loc-1'),
				darker_location    = target.attr('data-btn-gradient-loc-2'),
				radial_direction   = target.attr('data-btn-gradient-radial-dir');

			if( lighter_color == '' ) {
				lighter_color = 'transparent';
			}
			
			if( darker_color == '' ) {
				darker_color = 'transparent';
			}

			if( btn_gradient_type == 'radialgradient' ){
				//apply graidient color to button
				ConvertProColor._applyRadialGradientColor( radial_direction, for_edit+' .cp-target', lighter_color, lighter_location, darker_color, darker_location );
			}
			if( btn_gradient_type == 'lineargradient' ){
				//apply graidient color to button
				ConvertProColor._applyGradientColor( for_edit+' .cp-target', lighter_color, lighter_location , darker_color, darker_location, gradient_angle );
			}
		},

		_getHoverGradientDependentValue: function( target, for_edit ) {
			var gradient_angle     = target.attr('data-btn-gradient-angle-hover'),
				lighter_color      = target.attr('data-btn-gradient-bg1-hover'),
				darker_color       = target.attr('data-btn-gradient-bg2-hover'),
				btn_gradient_type  = target.attr('data-btn-gradient-type-hover'),
				lighter_location   = target.attr('data-btn-gradient-loc-1-hover'),
				darker_location    = target.attr('data-btn-gradient-loc-2-hover'),
				radial_direction   = target.attr('data-btn-gradient-radial-dir-hover')
				display   		   = target.attr('data-btn-gradient-hover-options');

			if( lighter_color == '' ) {
				lighter_color = 'transparent';
			}
			
			if( darker_color == '' ) {
				darker_color = 'transparent';
			}

			if( display == 'true' ) {
				if( btn_gradient_type == 'radialgradient' ){
					//apply gradient color to button
					ConvertProColor._applyHoverRadialGradientColor( radial_direction, for_edit, lighter_color, lighter_location, darker_color, darker_location, ' ! important' );
				}
				if( btn_gradient_type == 'lineargradient' ){
					//apply gradient color to button		
					ConvertProColor._applyHoverGradientColor( for_edit, lighter_color, lighter_location , darker_color, darker_location, gradient_angle, '! important' );
				}
			} else {

				if( btn_gradient_type == 'radialgradient' ){
					//apply gradient color to button
					ConvertProColor._applyHoverRadialGradientColor( radial_direction, for_edit, lighter_color, lighter_location, darker_color, darker_location );
				}
				if( btn_gradient_type == 'lineargradient' ){
					//apply gradient color to button
					ConvertProColor._applyHoverGradientColor( for_edit, lighter_color, lighter_location , darker_color, darker_location, gradient_angle );
				}

			}
		},

	}

	ConvertProColor.init();

	/*
	 * This is fallback function for Convert Pro Addon version less than 1.0.0-rc.11
	 * Removed in Convert Pro version 1.0.0
	 *
	*/
	cpGetUrlVar = function( name ) {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for( var i = 0; i < hashes.length; i++ ) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars[name];
	}

})( jQuery );