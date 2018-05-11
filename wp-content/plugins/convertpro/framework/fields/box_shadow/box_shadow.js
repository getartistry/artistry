/**
 *
 * Box Shadow Param
 *
 */
;(function ( $, window, document, undefined ) {
  'use strict';   
  	$.fn.cp_box_shadow_param = function() {   		
  	var self = $(this);
  	self.each(function(i,e) {
  		var container = $(self[i]);
		var val = container.find(".cp-box-shadow").val();

		/* set default values to all fields*/

		var pairs = val.split("|");
		var display_class ='cp-hidden';
		var settings = {};
		if( pairs.length > 0 && pairs !=='' ) {
			jQuery.each( pairs, function(index, val) {
				var values = val.split(":");					
				settings[values[0]] = values[1];
			});
		}
		
		var type       = ( settings['type'] !== '' ) 		? settings['type'] : 'none';
		var horizontal = ( settings['horizontal'] !== '' ) 	? settings['horizontal'] : 0;
		var vertical   = ( settings['vertical'] !== '' ) 	? settings['vertical'] 	: 0;
		var blur       = ( settings['blur'] !== '' ) 		? settings['blur'] 		: 0;
		var spread     = ( settings['spread'] !== '' ) 		? settings['spread'] 	: 0;
		var color      = ( settings['color'] !== '' ) 		? settings['color'] 	: 'rgba(0, 0, 0, 0.5)';
		
		container.find("#shadow-color").val(color);
		container.find(".wp-color-result").css('background-color', color );
		container.find("#horizontal-length").val(horizontal);
		container.find("#vertical-length").val(vertical);
		container.find("#blur-radius").val(blur);
		container.find("#spread-field").val(spread);
		container.find("#shadow-color").data('default-color',color);

		if( type == 'none'){
			container.find(".cp-shadow-options").addClass(display_class);
		} else {
			container.find(".cp-shadow-options").removeClass(display_class);
		}

		/* get form field value*/
		function _cp_getFromFieldShadow(value, min, max, elem) {
			var val, x;

			val = parseFloat(value);
			if (isNaN(val)) {
				val = 0;
			} else if (val < min) {
				val = min;
				value = min;
			} else if (val > max) {
				val = max;
				value = max;
			}
			elem.val(value);

			return val;
		}

		/* get all values from forms's input field */
		function _cp_getAllValuesFromPanelBoxShadow() {			
			var options = {};
			options['horizontal'] = parseFloat(container.find('#horizontal-length').val());
			options['vertical'] = parseFloat(container.find('#vertical-length').val());
			options['blur'] = parseFloat(container.find('#blur-radius').val());
			options['shadowColor'] = container.find('#shadow-color').val();
			options['opacity'] = parseFloat(container.find('#shadow-opacity').val());
			options['type'] = container.find('#cp_shadow_type').val();
			options['spread'] = parseFloat(container.find('#spread-field').val());
			return options;
		}

		function CP_BoxShadow (options) {					
			this.horizontal = options['horizontal'] || 0;
			this.vertical = options['vertical'] || 0;
			this.blur = options['blur'] || 0;
			this.spread = options['spread'] || 0;
			this.shadowColor = options['shadowColor'] || 'rgba(0, 0, 0, 0.5)';
			this.type = options['type'] || 'none';
			return this;
		}

		CP_BoxShadow.prototype.refresh = function ($str) {

			var code = '';
			code += 'type:'+this.type+'|';
			code += 'horizontal:'+this.horizontal+'|';
			code += 'vertical:'+this.vertical+'|';
			code += 'blur:'+this.blur+'|';
			code += 'spread:'+this.spread+'|';
			code += 'color:'+this.shadowColor;

			var id = container.find(".cp-box-shadow").attr('id');
			container.find(".cp-box-shadow").val(code);

			jQuery("#"+id).trigger('change');
			if( $str !== false && typeof $str !== false ){			
				jQuery(document).trigger('cp-box-shadow-change', [$(this),code,id] );
			}
		}

		cp_apply_box_shadow_container();

		function cp_apply_box_shadow_container(){			
			var opts = _cp_getAllValuesFromPanelBoxShadow();		
			CP_BoxShadow = new CP_BoxShadow(opts);
			var flag = false ;
			//CP_BoxShadow.refresh( flag );
		}

		/* Box Shadow- type change */

		container.find('#cp_shadow_type').on('change', function () {
			var type = jQuery(this).val();
			if( type != 'none' ) {
				container.find('.cp-shadow-options').slideDown(600);
				container.find(".cp-shadow-options").removeClass(display_class);
			} else {
				container.find('.cp-shadow-options').slideUp(600);
				container.find(".cp-shadow-options").addClass(display_class);

			}
			CP_BoxShadow.type = type;	
			CP_BoxShadow.refresh();

		});

		/* Box Shadow- color change */
		container.find('#shadow-color').on('change', function () {
			var color = jQuery(this).val();
			CP_BoxShadow.shadowColor = color;
			CP_BoxShadow.refresh();
		});

		/* Slider bars.horizontal-bs */
		container.find('#slider-horizontal-bs').slider({
			value: container.find('#horizontal-length').val(),
			min: -20,
			max: 20,
			step: 1,
			slide: function(event, ui) {
				var val = _cp_getFromFieldShadow(ui.value, -20, 20, container.find('#horizontal-length'));
				CP_BoxShadow.horizontal = val;

				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
				CP_BoxShadow.refresh();
			},			
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});

		/* Slider bars.vertical-bs */
		container.find('#slider-vertical-bs').slider({
			value: container.find('#vertical-length').val(),
			min: -20,
			max: 20,
			step: 1,
			slide: function(event, ui) {
				var val = _cp_getFromFieldShadow(ui.value, -20, 20, container.find('#vertical-length'));
				CP_BoxShadow.vertical = val;

				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			},			
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});

		/* Slider bars.blur-bs */
		container.find('#slider-blur-bs').slider({
			value: container.find('#blur-radius').val(),
			min: 0,
			max: 100,
			step: 1,
			slide: function(event, ui) {
				var val = _cp_getFromFieldShadow(ui.value, 0, 100, container.find('#blur-radius'));
				CP_BoxShadow.blur = val;
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			},
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});

		/* Slider bars.spread-bs */
		container.find('#slider-spread-field').slider({
			value: container.find('#spread-field').val(),
			min: -10,
			max: 10,
			step: 1,
			slide: function(event, ui) {
				var val = _cp_getFromFieldShadow(ui.value, -10, 10, container.find('#spread-field'));
				CP_BoxShadow.spread = val;
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			},			
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});

		/* Slider bars.opacity-bs */
		container.find('#slider-opacity-bs').slider({
			value: container.find('#shadow-opacity').val(),
			min: 0,
			max: 1,
			step: 0.01,
			slide: function(event, ui) {
				var val = _cp_getFromFieldShadow(ui.value, 0, 1, container.find('#shadow-opacity'));
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			},
			create: function( event, ui ){
				var leftMarginToSlider = jQuery( this ).find('.ui-slider-handle').css('left');
				jQuery( this ).find('.range-quantity').css('width',leftMarginToSlider);
			}
		});

		/* input change event */
		container.find('#horizontal-length').on('keyup change', function() {
			var val = _cp_getFromFieldShadow(jQuery(this).val(), -20, 20, container.find('#horizontal-length'));
			if (val !== false) {
				CP_BoxShadow.horizontal = val;
				container.find('#slider-horizontal-bs').slider('value', val);

				var leftMarginToSlider = container.find('#slider-horizontal-bs').find('.ui-slider-handle').css('left');
				container.find('#slider-horizontal-bs').find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			}
		});

		container.find('#vertical-length').on('keyup change', function () {
			var val = _cp_getFromFieldShadow(jQuery(this).val(), -20, 20, container.find('#vertical-length'));
			if (val !== false) {
				CP_BoxShadow.vertical = val;
				container.find('#slider-vertical-bs').slider('value', val);

				var leftMarginToSlider = container.find('#slider-vertical-bs').find('.ui-slider-handle').css('left');
				container.find('#slider-vertical-bs').find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			}
		});

		container.find('#blur-radius').on('keyup change', function() {
			var val = _cp_getFromFieldShadow(jQuery(this).val(), 0, 100, container.find('#blur-radius'));
			if (val !== false) {
				CP_BoxShadow.blur = val;
				container.find('#slider-blur-bs').slider('value', val);

				var leftMarginToSlider = container.find('#slider-blur-bs').find('.ui-slider-handle').css('left');
				container.find('#slider-blur-bs').find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			}
		});

		container.find('#spread-field').on('keyup change', function() {
			var val = _cp_getFromFieldShadow(jQuery(this).val(), -10, 10, container.find('#spread-field'));
			if (val !== false) {
				CP_BoxShadow.spread = val;
				jQuery('#slider-spread-field').slider('value', val);

				var leftMarginToSlider = container.find('#slider-spread-field').find('.ui-slider-handle').css('left');
				container.find('#slider-spread-field').find('.range-quantity').css('width',leftMarginToSlider);

				CP_BoxShadow.refresh();
			}
		});
	});

  	}

  	$(document).ready( function(){
  		$('.cp-field-box-shadow-container').cp_box_shadow_param();
  	});
  	
})( jQuery, window, document );