// Mini cart
jQuery( document ).ready( function( $ ) {
	$('.nav-search-content').hide();

	$("#et-top-navigation #top-menu").append('<div class="nav-search-toggler"><span class="icon"></span></div>');

	//Insert background shadow div
	$("body").append('<div id="tm-background-shadow"></div>');




	$("#tm-background-shadow").hide();

	$('#et-top-navigation #top-menu .nav-search-toggler').click(function(){
		$("#tm-background-shadow").toggle();
		$('.nav-search-content').toggle("slow");
	});
	$('#tm-background-shadow').click(function(){
		$("#tm-background-shadow").toggle();
		$('.nav-search-content').toggle("slow");
	});

	//$('#et-top-navigation .et-cart-info').replaceWith($('.tm-mini-cart'));
	//$('#et-top-navigation .tm-mini-cart .mini_cart_content').hide();

});