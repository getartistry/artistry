jQuery(document).ready(function($)
{
	var ajax_url = yb_cu_post_vars.ajax_url;
    var default_brick = yb_cu_post_vars.default_brick_width;

	$( '#ybi_curation_suite_listening_links' ).masonry( { columnWidth: 350 } );
	var $container = $('#ybi_curation_suite_listening_links');
	$container.imagesLoaded(function(){
    	$container.masonry({
	    itemsSelector: '.thumb',
    	isFitWidth: true
	    }).resize();
	});
	
	$("#ybi_curation_suite_listening_links").delegate(".commentary", "click", function(){
		var current_text = $(this).html();

		// replace the holding text with nothing.
		if(current_text == 'add commentary')
			current_text = '';
		
		$(this).removeClass('commentary');
		$(this).html('<textarea id="" class="commentary_text">'+current_text+'</textarea>'); // add the text to textarea
		
		// the next four lines is the only way we could find to have the cursor be at the end of the text in the textarea.
		$('.commentary_text').focus(); // focus the eleemnt
		var val = $('.commentary_text').val(); //get the text in the text area
		$('.commentary_text').val(''); // replace it with nothing
		$('.commentary_text').val(val); // add the text back
	});

	$("#ybi_curation_suite_listening_links").delegate(".commentary_text", "change", function(){

		var content_item_id = $(this).parent().attr('parameter_id');
		//var platform_id = $('#curated_platform_id').val();
        var platform_id = $('#cu_listening_platform_id').val();
		var commentary = $(this).val();

			data = {
				action: 'ybi_curation_suite_platform_display_commentary_action',
				platform_id: platform_id,
				content_item_id: content_item_id,
				commentary: commentary

			};
			$.ajax({
				type: "POST",
				data: data,
				dataType: "json",
				url: ajax_url,
				success: function(platform_response) {
                    //alert(platform_response.message);
                    commentary = platform_response.commentary;
				}
			});

			$(this).parent().addClass('commentary');
			$(this).parent().html(commentary);
			$(this).parent().fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100);
	});
	
	$("#ybi_curation_suite_listening_links").delegate(".cu_platform_display_action", "click", function(){
			var icon_html = $(this).html();
			$(this).html('<i class="fa fa-spinner fa-spin"></i>');
			var elem = $(this);
			var cur_action = elem.attr('cur_action');
			var content_item_id = elem.attr('parameter_id');
			var platform_id = $('#cu_listening_platform_id').val();
			var status = '';
			var featured = 0;
			var active = 0;
	
		data = {
			action: 'ybi_curation_suite_platform_display_action',
			platform_id: platform_id,
			cur_action: cur_action,
			content_item_id: content_item_id,
			icon_html: icon_html

		};
		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_url,
			success: function(platform_response) 
			{
				status = platform_response.status;
				featured = platform_response.featured;
				active = platform_response.active;
				if(platform_response.status == 'success')
				{

					if(featured == 1)
					{
						$('.cu_cid_row_'+content_item_id + ' .display_featured').html('<i class="fa fa-star"></i>');
						$('.cu_cid_row_'+content_item_id + ' .display_featured i').addClass('selected');
						$('.cu_cid_row_'+content_item_id + ' .display_featured i').parent().addClass('selected');
					}
					else
					{
						$('.cu_cid_row_'+content_item_id + ' .display_featured').html('<i class="fa fa-star"></i>');
						$('.cu_cid_row_'+content_item_id + ' .display_featured i').removeClass('selected');
						$('.cu_cid_row_'+content_item_id + ' .display_featured i').parent().removeClass('selected');
					}
					if(active == 1)
					{
						$('.cu_cid_row_'+content_item_id + ' .display_active').html('<i class="fa fa-th-large"></i>');
						$('.cu_cid_row_'+content_item_id + ' .display_active i').addClass('selected');
						$('.cu_cid_row_'+content_item_id + ' .display_active i').parent().addClass('selected');
                        $('.cu_cid_row_'+content_item_id).removeClass('available');
					}
					else
					{
						$('.cu_cid_row_'+content_item_id + ' .display_active').html('<i class="fa fa-th-large"></i>');
						$('.cu_cid_row_'+content_item_id + ' .display_active .fa').removeClass('selected');
						$('.cu_cid_row_'+content_item_id + ' .display_active .fa').parent().removeClass('selected');
                        $('.cu_cid_row_'+content_item_id).addClass('available');
					}
	
				}
			}
		});
	});
	
	$("#show_settings").click(function() {

			if($("#listening_settings").css('display') == 'block')
				$("#listening_settings").css({"display":"none","visibility":"hidden"});		
			else
				$("#listening_settings").css({"display":"block","visibility":"visible"});
	});


	$("#show_tutorials").click(function() {

		if($("#listening_tutorials").css('display') == 'block')
			$("#listening_tutorials").css({"display":"none","visibility":"hidden"});
		else
			$("#listening_tutorials").css({"display":"block","visibility":"visible"});
	});

	$("#ybi_curation_suite_listening_links").delegate(".show_topic_tutorials", "click", function(){
		if($(".listening_topic_tutorials").css('display') == 'block')
			$(".listening_topic_tutorials").css({"display":"none","visibility":"hidden"});
		else
			$(".listening_topic_tutorials").css({"display":"block","visibility":"visible"});
	});



	$(".cs_listening_option").change(function() {
			$('#settings_change_message').html('<i class="fa fa-spinner fa-spin"></i>');
			var elem = $(this);
			var current_value = '';
			var current_setting_type = 'checkbox';
			// check on what type of value this is, either checkbox, select, input
			if(elem.attr('type') == 'checkbox') {
				current_value = elem.is(':checked');
			} else {
				if(elem.is('select,input')) {
					current_value = elem.val();
					current_setting_type = 'text';
				}
			}
			var current_setting = elem.attr('rel');

			$('#settings_change_message').removeClass('remove_red');
			data = {
				action: 'ybi_curation_suite_platform_setting_change',
				current_value: current_value,
				current_setting: current_setting,
				current_setting_type: current_setting_type

			};
			$.ajax({
				type: "POST",
				data: data,
				dataType: "json",
				url: ajax_url,
				success: function(action_response) {
					//commentary = action_response.commentary;
					// default message
					var message = 'Setting has been saved';
					if(current_setting == 'ybi_cs_hide_platform_dropdown')
					{
						if(current_value)
						{
							$("#cu_listening_platform_id").css({"display":"none","visibility":"hidden"});
							message = 'Platform dropdown will be hidden';
						}
						else
						{
							message = 'Platform dropdown will be visible';
							$("#cu_listening_platform_id").css({"display":"inline-block","visibility":"visible"});
						}
					}
					if(current_setting == 'ybi_cs_hide_shortcut_sidebar')
					{
						if(current_value)
						{
							$("#cs_le_shortcuts").css({"display":"none","visibility":"hidden"});
						}
						else
						{
							$("#cs_le_shortcuts").css({"display":"inline-block","visibility":"visible"});
						}
					}
					if(action_response.current_setting == 'ybi_cs_hide_platform_display_features')
					{
						if(action_response.current_value)
						{
							message = 'Platform display options will be shown.';
						}
						else
						{
							message = 'Platform display options will be hidden.';
						}
					}

					if(action_response.current_setting == 'ybi_cs_click_draft_action_type') {
						message = 'Please reload page for curate behavior to take effect.';
					}
					$('#settings_change_message').addClass('remove_red');
					$('#settings_change_message').html(message);
				}
			});
	});

	$(".save_platform_defaults").click(function() {
		$('#settings_change_message').html('<i class="fa fa-spinner fa-spin"></i>');
		var platform_id = $('#cu_listening_platform_id').val();
		var topic_id = $('#cu_listening_topic_id').val();
		var time_frame = $('#cu_time_frame').val();
		var social_sort = $('#cu_social_sort').val();
		var video_sort = $('#cu_video_sort').val();
		var platform_sources = $('#cu_platform_sources').val();
			data = {
				action: 'ybi_curation_suite_platform_defaults_change',
				platform_id: platform_id,
				topic_id: topic_id,
				time_frame: time_frame,
				social_sort: social_sort,
				video_sort: video_sort,
				platform_sources: platform_sources
			};
			$.ajax({
				type: "POST",
				data: data,
				dataType: "json",
				url: ajax_url,
				success: function(platform_response) {
					$('#settings_change_message').html('Default search values have been saved.');
				}
			});
	});

    //$( "#cs_le_shortcuts" ).draggable();
    $("#cs_le_shortcuts").draggable({
        axis: "y",
        // Find original position of dragged image.
        start: function(event, ui) {
            // Show start dragged position of image.
            //var Startpos = $(this).position();
            //$("div#start").text("START: \nLeft: "+ Startpos.left + "\nTop: " + Startpos.top);
        },

        // Find position where image is dropped.
        stop: function(event, ui) {
            // Show dropped position.
            var Stoppos = $(this).position();
           // $("#cs_le_shortcuts_message").text("STOP: \nRight: "+ Stoppos.right + "\nTop: " + Stoppos.top);
            $("#cs_le_shortcuts").css({"right":"15px","left":""});
        }
    });



}); // end of doc