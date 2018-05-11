;(function ( $, window, document, undefined ) {
  'use strict'; 
 // function to initialise slider on click of edit
    $.fn.cp_slider = function() {  
	var slider_input = $(".cp-slider");
	$.each(slider_input,function(index,obj){
		var $this 		= $(this);
		var slider_id 	= $this.attr('id').replace("cp_","slider_");
		var input_id 	= $this.attr('id');
		var val 		= $this.val();
		var minimum 	= $this.data('min');
		var maximum 	= $this.data('max');
		var step 		= $this.data('step');
        var value 		= $this.val();
        var name 		= $this.attr('name');

		$( '#'+input_id ).on('keyup change', function() {
			value = $(this).val();
			$( '#'+slider_id ).slider('value', value);
			var leftMarginToSlider = $( '#'+slider_id ).find('.ui-slider-handle').css('left');
			$( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

            var element_id 		= $( '#'+input_id );                
            //  Trigger
            $(document).trigger('cp-slider-change', [ element_id, value, name ]);
		});

		$( '#'+slider_id ).slider({
			value : val,
			min   : minimum,
			max   : maximum,
			step  : step,
			slide : function( event, ui ) {
				$( '#'+input_id ).val(ui.value).trigger("change"); 
				var leftMarginToSlider = $( '#'+slider_id ).find('.ui-slider-handle').css('left');
				$( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

                var element_id 			= $( '#'+input_id );                  
                //  Trigger
                $(document).trigger('cp-slider-slide', [element_id, value]);
			}
		});
		
		$( '#'+input_id ).val( $( '#'+slider_id ).slider( "value" ) );		
		var leftMarginToSlider = $( '#'+slider_id ).find('.ui-slider-handle').css('left');
		$( '#'+slider_id ).find('.range-quantity').css('width',leftMarginToSlider);

	});
    }

    $(".cp-slider").cp_slider();

})( jQuery, window, document );
