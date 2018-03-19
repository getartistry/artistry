jQuery(document).ready(function ($) {
	var $form  = $('.divi-shop-ex-form');

	function static_vars(){};

	// Loop each option
	$form.find('.epanel-box').each(function(){
		var $field = $(this),
			type = $field.attr('data-type'),
			$input,
			$reset_button,
			et_divi_ex_js_params;

		switch( type ) {
			case "color":
				$input = $field.find('input.colorpicker');

				$reset_button = $field.find( '.reset-color' );
				/*
				// Setup colorpicker
				$input.iris({
					hide : false,
					width : 350,
					palettes : true
				});
				*/
				// Reset color
				$reset_button.click( function(e) {
					e.preventDefault();
					$(this).next('input').val('');
				});
				
				break;
			case "select":
				$input = $field.find( 'select' );

				// Update preview whenever select is changed
				$input.change( function() {
					var $select          = $(this),
						$selected_option = $select.find('option:selected'),
						selected_value   = $selected_option.val();
				});
				break;
			case "checkbox":
				var $input       = $field.find( 'select' ),
					$toggle      = $field.find( '.et_pb_yes_no_button' );

				$toggle.on( 'click', '.et_pb_value_text, .et_pb_button_slider', function(){
					var input_value  = $input.find('option:selected').val() === 'on' ? 'on' : 'off',
						toggle_state = input_value === 'on',
						toggle_new_state = toggle_state ? false : true,
						input_new_value  = toggle_new_state ? 'on' : 'off',
						toggle_state_class = 'et_pb_' + input_new_value + '_state';

					$input.val( input_new_value );
					$toggle.removeClass('et_pb_on_state et_pb_off_state').addClass( toggle_state_class );
				});

				break;
		}
	});

	// Top button
	$('#epanel-save-top').click(function(e){
		e.preventDefault();

		$('#epanel-save').trigger('click');
	});

	// Help box
	$(".box-description").click(function(){
		var descheading = $(this).parent('.epanel-box').find(".box-title h3").html();
		var desctext = $(this).parent('.epanel-box').find(".box-title .box-descr").html();
		static_vars.help_label = $(this).attr('label-name');
		console.log($(this));

		$('body').append("<div id='custom-lbox'><div class='box-desc'><div class='box-desc-top'>"+ static_vars.help_label +"</div><div class='box-desc-content'><h3>"+descheading+"</h3>"+desctext+"<div class='lightboxclose'></div> </div> <div class='box-desc-bottom'></div>	</div></div>");

		$( '.lightboxclose' ).click( function() {
			et_pb_close_modal( $( '#custom-lbox' ) );
		});
	});

	function et_pb_close_modal( $overlay, no_overlay_remove ) {
		var $modal_container = $overlay;

		// add class to apply the closing animation to modal
		$modal_container.addClass( 'et_pb_modal_closing' );

		//remove the modal with overlay when animation complete
		setTimeout( function() {
			if ( 'no_remove' !== no_overlay_remove ) {
				$modal_container.remove();
			}
		}, 600 );
	}

	$('.tab-content').hide();
	$('.tab-active').show();

	$('.ui-tabs').click(function(){
		$('.ui-tabs').removeClass('ui-tabs-active');
		$(this).addClass('ui-tabs-active');
		
		var content = $(this).attr('content');
		
		console.log( 'content: '+content );
		
		$('.tab-content').hide();
		$('.tab-content').removeClass('tab-active');

		$(this).parent().parent().find('.tab-content.' + content).show();
		$(this).parent().parent().find('.tab-content.' + content).addClass('tab-active');
		
		console.log( $(this).parent().parent().find('.tab-content.' + content) );
	});

	$('#epanel-save-top').click(function(){
		$('#shop-ex-form').submit();
	});

});

function updateTextOut (val,id) {
	var text_out = id+'_text_out';
    document.getElementById(text_out).innerHTML=val+'px'; 
}

function updateBittuInput(button_id){
	var field_id = button_id+'_input';
    var input_field = document.getElementById(field_id);
    var button = document.getElementById(button_id);

    if(button.getAttribute('down') == "true"){
        button.setAttribute("down","false");
        input_field.value = "false"; 
    }else{
        button.setAttribute("down","true");;
        input_field.value = "true";
    }
    console.log('value input:'+input_field.value);
}