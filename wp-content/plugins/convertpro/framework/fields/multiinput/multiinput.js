/**
 *
 * Multiinput Param
 *
 */
;(function ( $, window, document, undefined ) {
  'use strict';   
	$.fn.cp_multiinput_param = function( trigger_event ) { 
		var self = $(this);
		self.each(function(i,e) {
			var container = $(self[i]),
				id = container.data('id'),
				multiinputVal = $('#'+id).val(),
				units = container.data('units').split(','),
				css_prop  = $('#'+id).data("css-property"),
				css_selector = $('#'+id).data("css-selector");
			container.find('.cp-multiinput-toggle').removeClass('cp-linked');
			var multiinputValArr = multiinputVal.split('|');
			container.find( '.cp-multiinput-param-fields.multiinput-top' ).val( multiinputValArr[0] );
			container.find( '.cp-multiinput-param-fields.multiinput-right' ).val( multiinputValArr[1] );
			container.find( '.cp-multiinput-param-fields.multiinput-bottom' ).val( multiinputValArr[2] );
			container.find( '.cp-multiinput-param-fields.multiinput-left' ).val( multiinputValArr[3] );
			
			if( typeof multiinputValArr[5] == 'undefined' ) {
				if( jQuery.unique(multiinputValArr.slice(0,4)).length == 1 ) {
					$('#'+id).val(multiinputVal+'|1');
				} else {
					$('#'+id).val(multiinputVal+'|0');
				}
			}

			if( typeof multiinputValArr[5] != 'undefined' && multiinputValArr[5] == 0 ) {
				container.find('.cp-multiinput-toggle').addClass('cp-linked');
			} 

			if( typeof multiinputValArr[4] != 'undefined' && multiinputValArr[4] != '' ) {
				container.find('.cp-fields-param-units .cp-units').removeClass('cp-unit-current');
				var multiinputUnit = ( multiinputValArr[4] != '%' ) ? multiinputValArr[4] : 'per';
				container.find('.cp-fields-param-units .cp-unit-'+multiinputUnit).addClass('cp-unit-current');
			} else {
				container.find('.cp-fields-param-units .cp-unit-px').addClass('cp-unit-current');
			}
			
			/* Units */
			for ( var i = 0; i < units.length; i++ ) {
				var unitStr = ( units[i] != '%' ) ? units[i] : 'per';
				container.find('.cp-fields-param-units .cp-unit-'+unitStr).css('display','inline').data( 'current-unit', units[i] );
			}

			/* Update Value on Change */
			container.find( '.cp-multiinput-param-fields' ).on( 'input', function() {
				var current = $(this).data('multiinput'),
					updatedVal = $(this).val(),
					multiinputValOld = $('#'+id).val().split('|'),
					parent = $(this).closest('.cp-multiinput-container'); 

				if( typeof multiinputValOld[5] != 'undefined' && multiinputValOld[5] == 1 ) {
					
					parent.find( '.cp-multiinput-param-fields' ).val( updatedVal );
					multiinputValOld[0] = updatedVal;
					multiinputValOld[1] = updatedVal;
					multiinputValOld[2] = updatedVal;
					multiinputValOld[3] = updatedVal;

				} else {
					multiinputValOld[current] = updatedVal;
				}
				
				var id1 = parent.data('id');
				$('#'+id1).val( multiinputValOld.join('|') );

				if( typeof trigger_event !== 'undefined' && trigger_event ) {
					$('#'+id1).trigger( 'change' );
				}

				var final_val  = multiinputValOld.join('|');
				var input_name = id1 ;				
				jQuery(document).trigger('cp-multiinput-change', [$(this),final_val,input_name, css_selector, css_prop ] );
			});

			/* Toggle Click */
			container.find( '.cp-multiinput-toggle' ).on( 'click', function(e) {
				e.preventDefault();
				e.stopPropagation();

				e.count = ++e.count || 1
				if(e.count != 1) {
					return;
				}
				var a = 1;
				$(this).toggleClass('cp-linked');

				var parent = $(this).closest('.cp-multiinput-container'),
					id1 = $(this).closest('.cp-multiinput-container').data('id'),
					toggleField = '1',
					currentVal = $('#'+id1).val().split('|');

				if( $(this).hasClass('cp-linked') ) {
					toggleField = '0';
				}

				var commonVal = '';
				for (var i = 0; i < 4; i++ ) {
					if( typeof currentVal[i] != 'undefined' && currentVal[i] != '' ) {
						commonVal = currentVal[i];
						break;
					}
				}

				if( toggleField == '1' ) {
					currentVal[0] = commonVal;
					currentVal[1] = commonVal;
					currentVal[2] = commonVal;
					currentVal[3] = commonVal;
					parent.find('.cp-multiinput-param-fields').val( commonVal );
				}
				currentVal[5]  = toggleField;
				var final_val  = currentVal.join('|');
				var input_name = id1 ;
				$('#'+id1).val( final_val );
				$('#'+id1).trigger( 'change' );
				jQuery(document).trigger('cp-multiinput-change', [$(this),final_val,input_name, css_selector, css_prop ] );

			});

			/* Unit Click */
			container.find('.cp-fields-param-units').on( 'click', '.cp-units', function() {
				var parent = $(this).closest('.cp-multiinput-container'),
					id1 = $(this).closest('.cp-multiinput-container').data('id'),
					currentVal = $('#'+id).val().split('|'),
					newUnit = $(this).data('current-unit');

				parent.find('.cp-fields-param-units .cp-units').removeClass('cp-unit-current');
				$(this).addClass('cp-unit-current');

				currentVal[4] = newUnit;

				$('#'+id1).val( currentVal.join('|') );
				$('#'+id1).trigger( 'change' );

			});
		})
	}

	$(document).ready( function(){
	   $('.cp-multiinput-container').cp_multiinput_param( true );
	}); 

})( jQuery, window, document );