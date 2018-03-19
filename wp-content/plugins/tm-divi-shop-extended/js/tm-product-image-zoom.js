jQuery( document ).ready( function( $ ) {

	var $selector = $('.woocommerce-product-gallery .woocommerce-product-gallery__image img');

	var $img_source = $selector.attr('src');
	var $img_width = $selector.css('width');
	var $img_height = $selector.css('height');
	
	$selector.magnify({
		'magnifiedWidth': parseInt($img_width) + 200,
		'magnifiedHeight': parseInt($img_height) + 200
	});
    
    $selector = $('.woocommerce-product-gallery .woocommerce-product-gallery__image img');
	
});