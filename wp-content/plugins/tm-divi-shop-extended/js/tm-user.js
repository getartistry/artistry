jQuery( document ).ready( function( $ ) {
	var ssubmit = $( ".woocommerce-product-search :submit" );
	if(ssubmit[0] != undefined){
		ssubmit[0].setAttribute('value','U');
		ssubmit[0].style.display = 'inline-block';
  	}
  	
  	// Hide quick view button out of shop module
  	$('div:not(.et_pb_shop_tm) .quick_view').hide();

  	if($('.et_pb_shop_tm').toArray().length >= 1){
  		$('.woocommerce-message').css('display','none');
  		$('.woocommerce-message').css('visibility','hidden');
  	}
});

 