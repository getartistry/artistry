jQuery(document).ready(function($) {

	$('.next_selector' ).hide();
	$('.add_custom_selector' ).show();
	$('.new_selector_created' ).hide();
	$('.no_custom_selectors' ).show();
	$('.inside' ).show();		
	$('.opentrigger').show();
	$('.closedtrigger').hide();
	
	$('.custom_selector_edit').show();
	$('.next_selector_rename').hide();
	$('.rename_selector_text').hide();
	$('.remove_selector').hide();
	$('.rename_done').hide();
	
	$('.add_custom_selector' ).click(function(){
		var affected_id = this.parentNode.id;
		$('#'+affected_id).find('.next_selector' ).show();
		$('#'+affected_id).find('.next_selector_rename' ).show();
		$(this).hide();
		var next_selector = $('#'+affected_id).find('.next_selector' ).val();
		var data = {
			'action': 'custom_selectors_add',
			'custom_selector': next_selector,
			'dce_cs_add_nonce': dce_cs_vars.dce_cs_add_nonce,
		};
		$.post(ajaxurl, data, function() {
			$('#'+affected_id).find('.new_selector_created' ).show();
			$('#'+affected_id).find('.no_custom_selectors' ).hide();			
		});
	});

	$('.custom_selector_edit' ).click(function(){
		var affected_id = this.parentNode.id;
		$('#'+affected_id).addClass( 'highlight_selector' );
		$('#'+affected_id).find('.rename_selector_text' ).show();
		$('#'+affected_id).find('.remove_selector' ).show();
		$('#'+affected_id).find('.rename_done' ).show();
		$(this).hide();
		
		$('#'+affected_id).find('.rename_done' ).click(function(){
			var new_selector_name = $('#'+affected_id).find('.new_selector_name' ).val();
			var data = {
				'action': 'custom_selectors_rename',
				'custom_selector': affected_id,
				'new_name': new_selector_name,
				'dce_cs_rename_nonce': dce_cs_vars.dce_cs_rename_nonce,
			};				
			$.post(ajaxurl, data, function() {
				$('#'+affected_id).find('.custom_selector' ).val(new_selector_name);
				$('#'+affected_id).removeClass( 'highlight_selector' );
				$('#'+affected_id).find('.rename_selector_text' ).hide();
				$('#'+affected_id).find('.remove_selector' ).hide();
				$('#'+affected_id).find('.custom_selector_edit' ).show();
				$(this).hide();
			});
		});
		
		$('#'+affected_id).find('.remove_selector' ).click(function(){
			var new_selector_name = 'Selector Removed';
			var data = {
				'action': 'custom_selector_remove',
				'custom_selector': affected_id,
				'dce_cs_remove_nonce': dce_cs_vars.dce_cs_remove_nonce,
			};
			$.post(ajaxurl, data, function() {
				$('#'+affected_id).find('.custom_selector' ).val(new_selector_name);
				$('#'+affected_id).find('.custom_selector' ).addClass( 'selector_removed' );
				$('#'+affected_id).removeClass( 'highlight_selector' );
				$('#'+affected_id).find('.rename_selector_text' ).hide();
				$('#'+affected_id).find('.remove_selector' ).hide();
			});				
		});
		
	});
	
	$(".title_trigger").toggle(
		function() {
			$(this).next(".inside").hide();
			$(this).find(".opentrigger").hide();
			$(this).find('.closedtrigger').show();
		},
		function() {
			$(this).next(".inside").show();
			$(this).find('.opentrigger').show();
			$(this).find('.closedtrigger').hide();
		}		
	)

});