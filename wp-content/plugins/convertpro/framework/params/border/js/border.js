/**
 *
 * Border Param
 *
 */
;(function ( $, window, document, undefined ) {
  'use strict';   
	$.fn.cp_border_param = function() { 
		
		var container = jQuery(this);
		var val = container.find(".cp-border").val();
		var pairs = val.split("|");
		var settings = {};
		if( pairs.length > 0 && pairs !=='' ) {
			jQuery.each( pairs, function(index, val) {
				var values = val.split(":");					
				settings[values[0]] = values[1];
			});
		}

		//set default values to all filed of border
		var br_type           = ( settings['br_type'] !=='' && typeof settings['br_type'] !=='undefined' ) ? settings['br_type']  : 1;
		var topLeft           = ( settings['br_tl'] !=='' ) ? settings['br_tl']    : 10;
		var topRight          = ( settings['br_tr'] !=='' ) ? settings['br_tr']    : 10;
		var bottomLeft        = ( settings['br_bl'] !=='' ) ? settings['br_bl']    : 10;
		var bottomRight       = ( settings['br_br'] !=='' ) ? settings['br_br']    : 10;
		var borderStyle       = ( settings['style'] !=='' ) ? settings['style']    : 'solid';
		var borderColor       = ( settings['color'] !=='' ) ? settings['color']    : 'rgb(68,68,68)';
		var bw_type           = ( settings['bw_type'] !=='' &&  typeof settings['bw_type'] !=='undefined') ? settings['bw_type'] : 1;
		var top               = ( settings['bw_t'] !=='' ) ? settings['bw_t']     : 0;
		var left              = ( settings['bw_l'] !=='' ) ? settings['bw_l']     : 0;
		var right             = ( settings['bw_r'] !=='' ) ? settings['bw_r']     : 0;
		var bottom            = ( settings['bw_b'] !=='' ) ? settings['bw_b']     : 0;
		
		var display_class ='';
		if( borderStyle == 'none' ){
			display_class = 'cp-hidden';
			container.find(".cp-setting-block:not(:first)").addClass(display_class);
			container.find(".cp-setting-block:last-child").removeClass(display_class);
		}else{
			container.find(".cp-setting-block:not(:first)").removeClass(display_class);
			container.find(".cp-setting-block:last-child").removeClass(display_class);
		}

		if( bw_type == 0 ) {
			container.find(".cp-border-width-btn").addClass('cp-linked');	
		}

		if( br_type == 0 ) {
			container.find(".cp-border-radius-btn").addClass('cp-linked');	
		}
		
		container.find("#br-color").val(borderColor);
		container.find(".wp-color-result").css('background-color', borderColor );
		container.find("#width-top").val(top);
		container.find("#width-left").val(left);
		container.find("#width-right").val(right);
		container.find("#width-bottom").val(bottom);
		container.find("#cp_border_radius_type").val(br_type);
		container.find("#cp_border_width_type").val(bw_type);
		container.find("#top-left").val(topLeft);
		container.find("#top-right").val(topRight);
		container.find("#bottom-left").val(bottomLeft);
		container.find("#bottom-right").val(bottomRight);

		//function to get values form panel
		function _cp_getAllValuesFromPanelBorder() {
			var options 		= {};
			// options['br_all'] 	= parseFloat(container.find("#all-corners").val());
			options['br_tl'] 	= parseFloat(container.find("#top-left").val());
			options['br_tr'] 	= parseFloat(container.find('#top-right').val());
			options['br_bl'] 	= parseFloat(container.find('#bottom-left').val());
			options['br_br'] 	= parseFloat(container.find('#bottom-right').val());
			options['style'] 	= container.find('#border-style :selected').val();
			options['color'] 	= container.find('#br-color').val();
			options['br_type'] 	= container.find('#cp_border_radius_type').val();
			options['bw_t'] 	= parseFloat(container.find('#width-top').val());
			options['bw_l'] 	= parseFloat(container.find('#width-left').val());
			options['bw_r'] 	= parseFloat(container.find('#width-right').val());
			options['bw_b'] 	= parseFloat(container.find('#width-bottom').val());
			options['bw_type'] 	= container.find('#cp_border_width_type').val();
			return options;
		}

		function CP_Border (options) {
			this.br_tl   = ( typeof options['br_tl'] !== 'undefined' ? options['br_tl'] : 10 );
			this.br_tr   = ( typeof options['br_tr'] !== 'undefined' ? options['br_tr'] : 10 );
			this.br_bl   = ( typeof options['br_bl'] !== 'undefined' ? options['br_bl'] : 10 );
			this.br_br   = ( typeof options['br_br'] !== 'undefined' ? options['br_br'] : 10 );
			this.style   = options['style'] || 'none';
			this.color   = options['color'] ||'#000000';
			this.br_type = options['br_type'] || 0;
			this.bw_type = options['bw_type'] || 0;
			this.bw_t    = ( typeof options['bw_t'] !== 'undefined' ? options['bw_t'] : 0 );
			this.bw_l    = ( typeof options['bw_l'] !== 'undefined' ? options['bw_l'] : 0 );
			this.bw_r    = ( typeof options['bw_r'] !== 'undefined' ? options['bw_r'] : 0 );
			this.bw_b    = ( typeof options['bw_b'] !== 'undefined' ? options['bw_b'] : 0 );
			return this;
		}
		
		CP_Border.prototype.refresh = function () {
			var inputCode = 'br_type:'+this.br_type+'|';
			inputCode += 'br_tl:'+this.br_tl+'|';
			inputCode += 'br_tr:'+this.br_tr+'|';
			inputCode += 'br_br:'+this.br_br+'|';
			inputCode += 'br_bl:'+this.br_bl+'|';
			inputCode += 'style:'+this.style+'|';
			inputCode += 'color:'+this.color+'|';
			inputCode += 'bw_type:'+this.bw_type+'|';
			inputCode += 'bw_t:'+this.bw_t+'|';
			inputCode += 'bw_l:'+this.bw_l+'|';
			inputCode += 'bw_r:'+this.bw_r+'|';
			inputCode += 'bw_b:'+this.bw_b;

			var id = container.find(".cp-border").attr('id');			
			container.find(".cp-border").val(inputCode);
			jQuery("#"+id).trigger('change');			
		}

		function _cp_getFromFieldBorder(value, min, max, elem) {
			var val = parseFloat(value);
			if (isNaN(val) || val < min) {
				val = 0;
			} else if (val > max) {
				val = max;
			}
		
			if (elem)
				elem.val(val);
		
			return val;
		}

		/* On change trigger */
		jQuery('.cp-edit-panel-field').find('.cp-border-param-fields').on( 'input', function () {
			var val = jQuery(this).val(),
				parent = jQuery(this).closest('.cp-setting-block');

			if( parent.find('.cp-border-type').val() == 1 ) {
				parent.find('.cp-border-param-fields').val( val );
			}
			
			cp_set_border_values(); // set field values for border		
		});

		/* On change trigger */
		jQuery('.cp-edit-panel-field').find('.cp-border-toggle').on( 'click', function () {
			var parent = jQuery(this).closest('.cp-setting-block');
			
			var common_value = '';
			parent.find('.cp-border-param-fields').each(function(i,e){
				var newVal = $(this).val();
				if( newVal != '' && ! isNaN(newVal) ) {
					common_value = newVal;
					return false;
				}
			});

			if( parent.find('.cp-border-type').val() == 1 ) {
				jQuery(this).addClass('cp-linked');
				parent.find('.cp-border-type').val(0);
			} else {
				jQuery(this).removeClass('cp-linked');
				parent.find('.cp-border-type').val(1);
				parent.find('.cp-border-param-fields').val( common_value );
			}

			cp_set_border_values(); // set field values for border		
		});
		
		/* Border Style */
		jQuery('.cp-edit-panel-field').find('#border-style').on('change', function () {
			var val = jQuery(this).val();
			if( val == 'none' ){
				display_class = 'cp-hidden';
				container.find(".cp-setting-block:not(:first)").addClass(display_class);
				container.find(".cp-setting-block:last-child").removeClass(display_class);
			}else{
				container.find(".cp-setting-block:not(:first)").removeClass(display_class);
				container.find(".cp-setting-block:last-child").removeClass(display_class);
			}
			//change value for border style
			container.find('#border-style').val(val);
			cp_set_border_values(); // set field values for border		
		});

		/* Color (Border and background) */
		jQuery('.cp-edit-panel-field').find('#br-color').on('change', function () {
			var v = jQuery(this).val();			
			//change value for border color 
			container.find("#br-color").attr('value',v);			
			container.find(".wp-color-result").css('background-color', v );
			
			cp_set_border_values(); // set field values for border
		});

		// set and refresh field values for border
		function cp_set_border_values(){
			var opts   = _cp_getAllValuesFromPanelBorder();
			var border = new CP_Border(opts);
			border.refresh();
		}

	}
})( jQuery, window, document );