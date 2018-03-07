(function($){
	$.fn.usinColorSelect = function(options) {

		var defaults        = {
			//set the default options (can be overwritten from the calling function)
			colors : ['cccccc']
		},
		o            = $.extend(defaults, options),
		//define some variables that will be used globally within the script
		$input  = this,
		$container,
		$selectedBox;

		/**
		 * Inits the main functionality.
		 */

		function init() {
			$container = $('<div/>', {'class':'usin-color-select-container'})
				.insertAfter($input);
				
			addColorBoxes();
			$container.on('click', '.usin-color-box', doOnBoxSelected);
			
			//make the first box selected
			var savedColor = $input.attr('value'),
				$box;
				
			if(savedColor){
				$box = $container.find('.usin-color-box').filter(function(){
					return $(this).data('color') == savedColor;
				}).eq(0);
			}else{
				$box = $container.find('.usin-color-box:first');
			}
			doOnBoxSelected.call($box);
		}
		
		function addColorBoxes(){
			for(var i in o.colors){
				var color = o.colors[i];
				$container.append($('<div/>', {'class':'usin-color-box'})
					.css({backgroundColor:'#'+color})
					.data('color', color)
				);
			}
		}
		
		function doOnBoxSelected(){
			var $box = $(this),
				color = $box.data('color'),
				selectedClass = 'usin-selected';
			
			$selectedBox && $selectedBox.removeClass(selectedClass);
			$box.addClass(selectedClass);
			
			$input.attr("value", color);
			
			$selectedBox = $box;
		}
		
		if($input.length){
			init();
		}
		
	};
		
}(jQuery));


jQuery(document).ready(function($){
	
	$('.usin-color-select').each(function(){
		var colors = $(this).data('colors').split(',');
		$(this).usinColorSelect({colors:colors});
	});
});