// Mini cart
jQuery( document ).ready( function( $ ) {
	$('.tm-mini-cart').hover(
		function(){
			$(this).children('.mini_cart_content').show();
		},
		
		function(){
			$(this).children('.mini_cart_content').hide();
		}

	);

	$('#et-top-navigation .et-cart-info').replaceWith($('.tm-mini-cart'));
	$('#et-top-navigation .tm-mini-cart .mini_cart_content').hide();

});