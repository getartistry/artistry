(function( $ ) {
	'use strict';

	$(document).ready(function(){
		if(window.location.hash) {
			setTab($(window.location.hash));
		}
		$("#ui-tabs a").click(function(){

			setTab($(this));
			return false;
		});


		var send_method = $('.send_with').val();


		$('.wsi_test_email').on('click',function(e){
			e.preventDefault();
			$('#sending').fadeIn();
			$.post( ajaxurl, {action : 'wsi_test_email'}, function(response){

				if( response != '' )
				{
					$('#sending').html('<div class="error">'+response+'</div>');
				}
				else
				{
					$('#sending').html('<div class="updated">'+ wsivar.l18n.test_sent_to + wsivar.admin_email+'</div>');
				}

			} );

			return false;
		});

		$('.wsi_delete_logs').on('click',function(e){
			e.preventDefault();
			$('#deleting').fadeIn();
			$.post( ajaxurl, {action : 'wsi_delete_logs'}, function(response){
				if( response.logs_deleted ) {
					$('.logs').html('');
					$('#deleting').html('Logs deleted')
				}
				$('#deleting').fadeOut();
			} , 'json');

			return false;
		});
		//counters
		chars_left($('#char_left_lk'), $('#lk_message'), 200);
		chars_left($('#char_left_tw'), $('#tw_message'), 140);
	})

	/**
	 * Changes settings tabs
	 * @param what
	 */
	function setTab(what) {
		$("#ui-tabs a").removeClass("nav-tab-active");
		what.addClass("nav-tab-active");
		$('.ui-tabs-panel').removeClass('panel-active').hide();
		var href = what.attr('href');
		$(href).addClass('panel-active').fadeIn();
	}
    

	function chars_left(counter, input, total){
		counter.css('color','green');
		counter.text( total - input.val().length );
		input.on('keyup',function(){

			counter.text( total - input.val().length );
			if( input.val().length > 120 )
			{
				counter.css('color','red');
			} else {
				counter.css('color','green');
			}
		});
	}
})( jQuery );
