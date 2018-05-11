jQuery(document).ready(function($){
	
	// this is the ajax search function for the search posts on the Curate Action page
	// this only searches published posts
	$('.ybi_curation_suite_post_search').keyup(function() {
		$('.rcp-ajax').show();
		var user_search = $(this).val();

		var url = $('#ybi_url').val();
		data = {
			action: 'ybi_curation_suite_search_posts',
			user_name: user_search,
			curate_url: url,
			curate_action_search_nonce: curate_action_vars.curate_action_search_nonce
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(search_response) {
				//alert(search_response.results);
				$('.rcp-ajax').hide();
				$('#ybi_curation_suite_post_search_results').html('');
				$(search_response.results).appendTo('#ybi_curation_suite_post_search_results');
			}
		});
	});
	
	// This function is the ajax function for the Add Link funcationality
	$('.ybi_add_link').click(function()
	{
		var title = $('#ybi_title').val();
		var url = $('#ybi_url').val();
		var add_new_link_bucket_category = $('#add_new_link_bucket_category').val();
		var link_notes = $('#link_notes').val();
		var close_options = $(this).attr('rel');	
		var linkcategories=[];
		$('input.linkcats').each( function() {
				if($(this).attr('checked')) {
					linkcategories.push($(this).attr('rel'));
				}
		} );
		data = {
			action: 'ybi_add_curation_suite_link',
			title: title,
			url: url,
			linkcategories: linkcategories,
			add_new_link_bucket_category: add_new_link_bucket_category,
			close_options: close_options,
			link_notes: link_notes,
			curate_action_add_link_nonce: curate_action_vars.curate_action_add_link_nonce
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(search_response) {
				if(search_response.close_options == 'close')
				    window.close();
				$('#add_link_message').html('');
				$('#add_link_message').html(search_response.results);
				// if a new category was added we then add a checkbox to the other checkboxes at the end... it also will be checked
				if (typeof search_response.add_term_array !== 'undefined' && search_response.add_term_array.length > 0)
				{
					$('.added_category_chkbox').html('<p><label class=""><input type="checkbox" value="'+search_response.add_term_array[0]+'" rel="'+search_response.add_term_array[0]+'" name="linkcategories" class="linkcats" id="link_category_'+search_response.add_term_array[0]+'" checked="checked" />'+search_response.add_term_array[1]+'</label></p>');

				}
			}
		});

	}); //	ybi_add_link

	$('.close_curate_window').click(function(){
		window.close();
	});
	
	
	$("#limit").css({
		height:$("#div").height()
	});
	$("#limit").animate({
		height:$("#div").height()
	},600);

});