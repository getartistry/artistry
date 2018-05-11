jQuery(document).ready(function($)
{
		var ajax_url = yb_scrape_vars.ajax_url;
	
	$(".load_text").click(function() {
//		alert('yes');
		load_scrap_sources();
	});
	
	$(".get_title").click(function() {
		$(".loading_action").html('<i class="fa fa-spinner fa-spin"></i>');
		get_url_title();
	});

	$(".source_action").click(function() {
		$("#source_listing_wrap").html('<i class="fa fa-spinner fa-spin"></i>');
		process_source_action();
		$('#url').val('');
		$('#scrape_name').val('');
		$('#source_type').val('');
	});

	$("#cu_scraping_feature_switch").click(function() {
		data = {
			action: 'ybi_toggle_scraping_feature',
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(results) {
				//$("#source_listing_wrap").html(results.html);
			}
		});	
	});





	function process_source_action()
	{
		var current_action = $('#current_action').val();
		var url = $('#url').val();
		var scrape_name = $('#scrape_name').val();
		var source_type = $('#source_type').val();
		var key = $('#edit_key').val();
		data = {
			action: 'ybi_source_action',
			current_action: current_action,
			url: url,
			source_type: source_type,
			scrape_name: scrape_name,
			key: key,
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(result) {
				$(".loading").html('');
				$('#current_action').val('new');
				$('#edit_key').val('');
				$('.source_action').html('Add Source');
				load_scrape_sources();
			}
		});		

	}	
	function load_scrape_sources()
	{
		data = {
			action: 'ybi_load_scrape_sources',
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(results) {
				$("#source_listing_wrap").html(results.html);
			}
		});		

	}
	
	function get_url_title()
	{
		var url = $('#url').val();
		data = {
			action: 'ybi_load_url_title',
			url: url
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(result) {
				$('#scrape_name').val(result.title);
				$(".loading_action").html('');
			}
		});		

	}
	
	function load_source_edit(inKey)
	{
		data = {
			action: 'ybi_load_scrape_single_source',
			key: inKey,
		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(results) {
				$('#current_action').val('edit');
				$('#edit_key').val(inKey);
				$('#url').val(results.url);
				$('#scrape_name').val(results.title);
				$('#source_type').val(results.source);
				$("#source_listing_wrap").html('<i class="fa fa-spinner fa-spin"></i>');
				$('.source_action').html('Update Source');
			}
		});		

	}
	
	$("#source_listing_wrap").delegate(".edit", "click", function(){
		// add loading icon to be replaced when score comes back below
		var key = $(this).attr('rel');
		load_source_edit(key);
	});
	$("#source_listing_wrap").delegate(".delete", "click", function(){
		// add loading icon to be replaced when score comes back below
		var key = $(this).attr('rel');
		$('#edit_key').val(key);
		$('#current_action').val('delete');
		$("#source_listing_wrap").html('<i class="fa fa-spinner fa-spin"></i>');
		process_source_action();
	});
	

	
}); // end of doc