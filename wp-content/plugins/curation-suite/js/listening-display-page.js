jQuery(document).ready(function($)
{
	var ajax_url = yb_cu_post_vars.ajax_url;

	$( '#ybi_cu_display_platform_content' ).masonry( { columnWidth: 250 } );
	var $container = $('#ybi_cu_display_platform_content');
	$container.imagesLoaded(function(){
    	$container.masonry({
	    itemsSelector: '.item_thumb',
    	isFitWidth: true
	    }).resize();
	});
	
}); // end of doc