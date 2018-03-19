(function( $ ) {
    $(function() {
         
        $( '.tm-color-picker' ).wpColorPicker();
         
    });

	$('.button_icon').change(function (){
		
		alert('Enable Quick View Icon on pro versions only..!')
		$(this).attr('checked',false);
		
	});

})( jQuery );