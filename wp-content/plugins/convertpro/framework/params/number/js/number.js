/**
 *
 * Border Param
 *
 */
;(function ( $, window, document, undefined ) {
  'use strict';   
	$.fn.cp_number_param = function() { 
		var self = $(this);
		self.each(function(i,e) {
			var container = $( self[i] ),
				id = container.data('id'),
				numberVal = $( '#'+id ).val(),
				units = container.data('units').split(',');

			var currentUnit = getCurrentParamUnit( numberVal );

			if( currentUnit != '' ) {
				var newUnit = ( currentUnit == '%' ) ? 'per' : currentUnit;
				container.find( '.cp-fields-param-units .cp-unit-'+newUnit ).addClass('cp-unit-current');
			} else {
				container.find( '.cp-fields-param-units .cp-unit-px' ).addClass('cp-unit-current');
			}

			container.find('.cp-number-param-temp').val( numberVal.replace( currentUnit,'' ) );

			/* Units */
			for ( var i = 0; i < units.length; i++ ) {
				var unitStr = ( units[i] != '%' ) ? units[i] : 'per';
				container.find('.cp-fields-param-units .cp-unit-'+unitStr).css('display','inline').data( 'current-unit', units[i] );
			}

			container.find('.cp-number-param-temp').on('input', function() {
				var newVal 			= $(this).val(),
					parentConatiner = $(this).closest('.cp-number-container'),
					newId 			= parentConatiner.data('id'),
					oldVal			= parentConatiner.find("#"+newId).val(),
					currentUnit 	= getCurrentParamUnit( oldVal );

				parentConatiner.find("#"+newId).val( newVal+currentUnit );
				parentConatiner.find("#"+newId).trigger('change');
			});

			/* Unit Click */
			container.find('.cp-fields-param-units').on( 'click', '.cp-units', function() {
				var parentConatiner = $(this).closest('.cp-number-container'),
					newId 			= parentConatiner.data('id'),
					oldVal			= parentConatiner.find("#"+newId).val(),
					newUnit 		= $(this).data('current-unit');

				parentConatiner.find('.cp-fields-param-units .cp-units').removeClass('cp-unit-current');
				$(this).addClass('cp-unit-current');

				parentConatiner.find("#"+newId).val( oldVal.replace( getCurrentParamUnit( oldVal ), '' ) +newUnit );
				parentConatiner.find("#"+newId).trigger('change');
			});

			function getCurrentParamUnit( number ) {
				
				if( number.indexOf('px') >= 0 ) {
					return 'px';
				} else if( number.indexOf('em') >= 0 ) {
					return 'em';
				} else if( number.indexOf('%') >= 0 ) {
					return '%';
				} else if( number.indexOf('ms') >= 0 ) {
					return 'ms';
				} else if( number.indexOf('sec') >= 0 ) {
					return 'sec';
				} else if( number.indexOf('deg') >= 0 ) {
					return 'deg';
				} else if( number.indexOf('s') >= 0 ) {
					return 's';
				} else {
					return '';
				}
			}
		});
	}
})( jQuery, window, document );