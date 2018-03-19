jQuery( document ).ready( function( $ ) {

	var $img_source = $('div.woocommerce-product-gallery__image:nth-child(1) img').attr('src');
	var $img_width = $('div.woocommerce-product-gallery__image:nth-child(1) img').css('width');
	var $img_height = $('div.woocommerce-product-gallery__image:nth-child(1) img').css('height');
	console.log('height: '+parseInt($img_height) + ', width: '+parseInt($img_width));
	$('div.woocommerce-product-gallery__image:nth-child(1) img').attr('data-magnify-src', $img_source);
	$('.et_pb_module.et_pb_image div.woocommerce-product-gallery__image img').magnify({
		'magnifiedWidth': parseInt($img_width) + 200,
		'magnifiedHeight': parseInt($img_height) + 200
	});
    
	$('.et_pb_module.et_pb_image ol.flex-control-nav.flex-control-thumbs li').click(function(){
		$('.et_pb_module.et_pb_image div.woocommerce-product-gallery__image:nth-child(1) img').destroy();
		$('.et_pb_module.et_pb_image div.woocommerce-product-gallery__image:nth-child(1) img').magnify({
			'magnifiedWidth': parseInt($img_width) + 200,
			'magnifiedHeight': parseInt($img_height) + 200
		});
	});
	
});
