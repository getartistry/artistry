  	function cleanText(inText)
	{
		//http://stackoverflow.com/questions/2116558/fastest-method-to-replace-all-instances-of-a-character-in-a-string
		//http://www.danshort.com/HTMLentities/
		//http://www.utexas.edu/learn/html/spchar.html
		// this has to be first to turn &amp into & signs
		if(inText === undefined)
			return '';

		inText = inText.replace(/&amp;/g, "&");
		//console.log("inText BEGIN: " + inText);
		inText = inText.replace(/&nbsp;/g, " ");
		// thin space character
		inText = inText.replace(/&thinsp;/g, " ");
		inText = inText.replace(/Â/g, " ");
		inText = inText.replace(/&Acirc;/g, "");
		inText = inText.replace(/&acirc;/g, "");
		inText = inText.replace(/��/g, "");

		inText = inText.replace(/&#0160;/g, " ");
		
		inText = inText.replace(/&quot;/g, "\"");
		inText = inText.replace(/&#34;/g, "\"");

		inText = inText.replace(/&#8211;/g, "–");

		inText = inText.replace(/&#8212;/g, "—");
		inText = inText.replace(/&mdash;/g, "—");

		inText = inText.replace(/&#8216;/g, "‘");
		inText = inText.replace(/&lsquo;/g, "‘");
		inText = inText.replace(/&#8217;/g, "’");	
		inText = inText.replace(/&rsquo;/g, "’");
		inText = inText.replace(/â/g, "’");

//		inText = inText.replace(/â/g, "'");
		inText = inText.replace(/“/g, "“");
		
		
		inText = inText.replace(/â/g, "“");
		inText = inText.replace(/&#8220;/g, "“");
		inText = inText.replace(/&ldquo;/g, "“");
		inText = inText.replace(/&#8221;/g, "”");
		inText = inText.replace(/&rdquo;/g, "”");

		inText = inText.replace(/…/g, "…");
		inText = inText.replace(/&#8230;/g, "…");
		inText = inText.replace(/&nbsp;/g, " ");
		//console.log("inText END: " + inText);
		inText = inText.replace(/^\s+|\s+$/g, "");

		return inText;
			
	}

jQuery(document).ready(function($)
{

	function cs_add_confirmation_message(type, message)
	{
		if(type== 'success') {

			$('#cs_selection_message',window.parent.document).css({"display":"block","color":"#fff","background":"#048005"});
			$('#cs_selection_message',window.parent.document).html(message);
			$('#cs_selection_message',window.parent.document).fadeOut( 2000, "linear" );
		}
		//error
		//background: #B22222;

	}

    var ajax_url = yb_cu_parse_page_vars.ajax_url;
	// this function will take the clicked image and add it to a hidden text field and the img div on the parent post page
	$(".select_image").click(function() {
		var elem = $(this);
		var theSRC = elem.attr('src')
		// below we set the image and the hidden input field
		$('#curated_thumbnail',window.parent.document).val(theSRC);
		$('#chosenthumbnail',window.parent.document).attr("src",theSRC);
		cs_add_confirmation_message('success','Image selected');
	})
	
	// this enhances the visual look of the curated content we can select
	$('.visual_paragraph').hover(
		function(){
		  var $this = $(this);
		  $this.data('bgcolor', $this.css('background-color')).css('background-color', '#FFC');
		},
		function(){
		  var $this = $(this);
		  $this.css('background-color', $this.data('bgcolor'));
		}
	); 
  
	$( ".visual_paragraph_add" ).click(function() {
		var elem = $(this);
		elem.select();
		//var theText = elem.attr('rel');
		var theName = elem.attr('name');
		if(elem.attr('data-content-type') == 'list') {
			elem = $(".visual_paragraph_" + theName);
			var theText = $.trim(elem.text());
		} else {
			var theText = $.trim(elem.text());
		}

		theText = cleanText(theText);
		theText = theText.replace(/^\s+|\s+$/g, "");

		var regex = /<br\s*[\/]?>/gi;
		theText.replace(regex, "\n");

		//theText = theText.replace(/<br ?\/?>/g, "\n");
		$('#summary_text_textarea',window.parent.document).val(theText);
		cs_add_confirmation_message('success','Content added.');
	});
	
	$( ".load_link" ).click(function() {
		var elem = $(this);
		var theText = elem.attr('rel');
		$('#source_url',window.parent.document).val(theText);
		parent.document.getElementById('load_content').click()
	});
	
	$( ".add_to_visual" ).click(function() {
		var elem = $(this);
		// which is really the number of the paragraph
		var theName = elem.attr('name');
		elem = $(".visual_paragraph_" + theName);
		//var theText = elem.attr('rel');
		var theText = elem.text();
		//theText = cleanText(theText);
		theText = theText.replace(/^\s+|\s+$/g, "");
		stageValue = '';
		
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';

		/*var regex = /<br\s*[\/]?>/gi;
		stageValue.replace(regex, "\n");*/

		$('#summary_text_textarea',window.parent.document).val(stageValue + theText);
		cs_add_confirmation_message('success','Content added.');
	});

	$( ".add_plaintext_link" ).click(function() {
		var elem = $(this);
		var theText = elem.attr('rel') + '\n'; // this is the link url
		stageValue = '';
		
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
		  
		$('#summary_text_textarea',window.parent.document).val(stageValue + theText);
	});

	$( ".meta_tags_add" ).click(function() {
		$(".meta_tag_added").css({"display":"block","visibility":"visible"});
		var elem = $(this);
		var theText = elem.attr('rel'); // this is the link url
		tagsValue = '';

		tagsValue = $('#new-tag-post_tag',window.parent.document).val();


		if(tagsValue.length > 0)
			tagsValue = tagsValue + ', ';
		  
		$('#new-tag-post_tag',window.parent.document).val(tagsValue + theText);

		$('.tagadd',window.parent.document).click();
		$('.meta_tag_added').hide(1000);
		cs_add_confirmation_message('success','Tags added.');
	});
	
	$( ".add_facebook_embed" ).click(function() {
		// below a shortcode is used because facebook embed is not implemented yet in WordPress
		// this also requires the JetPack plugin and the Embeds to be activated on users site
		var elem = $(this);
		var theEmbedLink = elem.attr('rel'); // this is the link url
		stageValue = '';
		var theText = '[facebook url="'+theEmbedLink+'"]';
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
		  
		$('#summary_text_textarea',window.parent.document).val(stageValue + theText);
	});

	
	$( ".add_google_plus_embed" ).click(function() {
		var elem = $(this);

		var theEmbedLink = elem.attr('rel'); // this is the link url
		stageValue = '';
		var theText = '<div class="g-post" data-href="'+theEmbedLink+'">Google+ Embeded Post</div>';
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
		  
		$('#summary_text_textarea',window.parent.document).val(stageValue + theText);
	});

	$( ".add_instagram_embed" ).click(function() {
		var elem = $(this);

		var theEmbedLink = elem.attr('rel'); // this is the link url
		stageValue = '';
		
   		var theText = '<iframe src="'+theEmbedLink+'" width="612" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
		  
		$('#summary_text_textarea',window.parent.document).val(stageValue + theText);
	});


	$( ".add_video" ).click(function() {

		var elem = $(this);
		// which is really the number of the paragraph
		var typeOfAd = elem.attr('name'); // this contains the video link
		var theVideoInfo = elem.attr('rel'); // this contains the iframe url which is sometimes different
		var stageValue = '';
		var videoText = '';
		if(typeOfAd == 'add_link')
			videoText = ' ' + theVideoInfo + '\n\n';
		else
		{
			if(typeOfAd == 'add_iframe_YouTube')
			 	videoText = '<iframe width="'+yb_cu_parse_page_vars.curation_suite_default_video_width+'" height="'+yb_cu_parse_page_vars.curation_suite_default_video_height+'" src="'+theVideoInfo+'" frameborder="0" allowfullscreen></iframe>';
			if(typeOfAd == 'add_iframe_Vimeo')
			 	videoText = '<iframe width="'+yb_cu_parse_page_vars.curation_suite_default_video_width+'" height="'+yb_cu_parse_page_vars.curation_suite_default_video_height+'" src="'+theVideoInfo+'" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			if(typeOfAd == 'add_iframe_other')
			 	videoText = '<iframe width="'+yb_cu_parse_page_vars.curation_suite_default_video_width+'" height="'+yb_cu_parse_page_vars.curation_suite_default_video_height+'" src="'+theVideoInfo+'" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0" allowfullscreen></iframe>';

		}
	
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
			
		$('#summary_text_textarea',window.parent.document).val(stageValue + videoText);
	});
	$( ".add_slideshare" ).click(function() {

		var elem = $(this);
		var typeOfAd = elem.attr('name')
		var theLink = elem.attr('rel');
		var stageValue = '';
		var addText = '';
		if(typeOfAd == 'add_link')
			addText = ' ' + theLink + '\n\n';
		else
		{
			 addText = '<iframe width="'+yb_cu_parse_page_vars.curation_suite_default_video_width+'" height="'+yb_cu_parse_page_vars.curation_suite_default_video_height+'" src="'+theLink+'" frameborder="0" allowfullscreen></iframe>';
		}
	
		stageValue = $('#summary_text_textarea',window.parent.document).val();
		if(stageValue.length > 0)
			stageValue = stageValue + '\n\n';
			
		$('#summary_text_textarea',window.parent.document).val(stageValue + addText);
	});

    $( ".add_image_to_post" ).click(function() {
        var elem = $(this);
        // which is really the number of the paragraph
        var image_number = elem.attr('data-id')
        var img_src = $('.found_image_'+image_number).attr('src');
        var sourceDomain = $('#sourceDomain').val();
        stageValue = $('#summary_text_textarea',window.parent.document).val();

        //$('#summary_text_textarea',window.parent.document).val(img_src);
        //$('#content',window.parent.document).val(img_src);
        //var elem = $(this);
        //elem.html('<i class="fa fa-spinner fa-spin"></i>');
	    var overall_text = ''; // this is the text we add
		overall_text = '<img src="'+img_src+'" alt="'+sourceDomain+'" />';

		add_content_to_post_box(overall_text);
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Added</span>'); // hide the indicator and note it was added
        //elem.contents().unwrap();
        var upload_image = $('#upload_quick_add_images').is(':checked');

        if(upload_image)
            do_image_upload(img_src, $("#post_ID",window.parent.document).val(), false);
    });

	$( ".add_tweet" ).click(function() {
		var elem = $(this);
		//var overall_text = elem.html();
		var overall_text = '<br />' + elem.attr('data-tweet-url');
		var twitter_status_id = elem.attr('data-tweet-id');
		elem.html('<i class="fa fa-spinner fa-spin"></i>');
		add_content_to_post_box(overall_text);
		//do_save_twitter_data_to_meta($("#post_ID").val(), twitter_status_id,twitter_user);
		elem.html('<span class="cqs_green"><i class="fa fa-plus embed-added"></i></span>'); // hide the indicator and note it was added
	});

	$( ".add_embed_content_to_post" ).click(function() {
		var elem = $(this);
		//var overall_text = elem.html();
		var overall_text = '<p>' + elem.attr('data-url') + '</p>';
		var twitter_status_id = elem.attr('data-tweet-id');
		elem.html('<i class="fa fa-spinner fa-spin"></i>');
		add_content_to_post_box(overall_text);
		//do_save_twitter_data_to_meta($("#post_ID").val(), twitter_status_id,twitter_user);
		elem.html('<span class="cqs_green"><i class="fa fa-plus embed-added"></i></span>'); // hide the indicator and note it was added
	});

	function add_content_to_post_box(in_content)
	{
		//$('#content-tmce',window.parent.document).click(); // ensure we are on visual
		//var original_text = window.parent.tinyMCE.activeEditor.getContent({format : 'raw'});
		//var ed = window.parent.tinyMCE.get('content');
		//ed.dom.add(ed.getBody(), 'p', '', '<img src="'+img_src+'">' );
		//$('#content-tmce').click(); // ensure we are on visual
		//var original_text = tinyMCE.activeEditor.getContent({format : 'raw'});
		//var ed = tinyMCE.get('content');
		//ed.dom.add(ed.getBody(), 'p', '', overall_text );
		//window.parent.tinymce.activeEditor.execCommand('mceInsertContent', false, overall_text);
		//$('#content-html',window.parent.document).click(); // ensure we are on visual
		$('#content-tmce',window.parent.document).click(); // ensure we are on visual
		window.parent.tinymce.activeEditor.execCommand('mceInsertContent', false, in_content);
		//window.parent.tinymce.activeEditor.execCommand('mceSetContent', false, in_content);

		//window.parent.tinymce.activeEditor.execCommand('mceRepaint');
		//$('#content-tmce',window.parent.document).click(); // ensure we are on visual+

		//$('#content-html',window.parent.document).click(); // ensure we are on visual
		//$('#content-tmce',window.parent.document).click(); // ensure we are on visual
	}


    $( ".set_image_featured" ).click(function() {
        var elem = $(this);
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        $("#postimagediv .inside",window.parent.document).html('<i class="fa fa-spinner fa-spin"></i>');
        // which is really the number of the paragraph
        var image_number = elem.attr('data-id')
        var img_src = $('.found_image_'+image_number).attr('src');
        do_image_upload(img_src, $("#post_ID",window.parent.document).val(), true);
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Featured Set</span>'); // hide the indicator and note it was added
        //$('#postimagediv .inside').html(img_src);
        //alert($('#postimagediv .inside').html());
        //$("#postimagediv .inside",window.parent.document).html(img_src);

    });

    // this function will send the image url to the ajax function to upload the chosen image
    // note: if the user has select a screenshot these are always uploaded
    function do_image_upload(theThumbnail, post_ID, set_featured)
    {
        return_img_url = '';
        data = {
            action: 'ybi_cu_upload_image',
            img_url: theThumbnail,
            post_ID: post_ID,
            set_featured: set_featured
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function(image_results) {
                // if the image could be or was uploaded then we replace it in the content editor
                if(image_results.meta_html != '') // if the meta html has been set then we add it to the meta box
                    $("#postimagediv .inside",window.parent.document).html(image_results.meta_html);

                if(image_results.status)
                {
                    var overall_text = "";
                    var original_text = window.parent.tinyMCE.activeEditor.getContent({format : 'raw'});
                    var ed = window.parent.tinyMCE.get('content');
                    var content = ed.getContent();
                    content = content.replace(image_results.og_img_url, image_results.upload_img_url);
                    ed.setContent(content);
                }
                else
                {
                    // if there was an error we display this error. Porbably should take the actual error from what is returned but this will do for now
                    //$(".content_add_error").css({"display":"block","visibility":"visible"});
                    //$(".content_add_error").html('<i class="fa fa-exclamation-triangle"></i> Due to security reasons the image you selected can\'t be uploaded to your site. This image was not uploaded and if displayed is pointing to the original source file.');
                }
            }
        });

    }
	
	$(".show_div").click(function() {
		var elem = jQuery(this);
		var theName = elem.attr('name')
		$(".selector_div").css({"display":"none","visibility":"hidden"});
		$("#"+theName).css({"display":"block","visibility":"visible"});
	});
	$("input[type='text']").on("click", function () {
	   $(this).select();
	});

	$(".add_raw_paragraph").click(function() {
		var elem = jQuery(this);
		var add_type = elem.attr('data-add-type');
		var paragraph_number = elem.attr('rel');
		var type_of_text = elem.attr('data-type-text');
		var text;
		if(type_of_text=='html') {
			text = $('.visual_paragraph_'+paragraph_number).html();
			//alert(text + ':' + type_of_text);
			text = text.replace(/\n/g, "<br />");
		} else {
			text = $('.visual_paragraph_'+paragraph_number).text();
		}

		if(add_type=='blockquote')
			text = '<br><blockquote>' + text + '</blockquote><p></p>';

		add_content_to_post_box(text);
		elem.html('<i class="fa fa-plus cqs_green"></i>');
	});

	$(".visual_paragraph").hover(function(){
		$('.raw_paragraph_actions').hide();
		var elem = jQuery(this);
		var paragraph_number = elem.attr('rel');
		$('#raw_paragraph_actions_'+paragraph_number).show();
	},function(){
		//$('.raw_paragraph_actions').hide();
	});

	$( "#reload_thumbnail" ).click(function() {

		//$("#contentiframe").contents().find('#content_thumbnail').css({"border-color":"red","border-size":"1px"});
		$("#reload_thumbnail_indicator").addClass('fa-spin');

		var elem = $(this);
		var theText = elem.attr('rel');
		$('#content_thumbnail').attr('src', theText);
		//$("#contentiframe").contents().find('#content_thumbnail').css({"border":"none"});
		//$("#reload_thumbnail_indicator").removeClass('fa-spin');
		window.setTimeout(function(){$("#reload_thumbnail_indicator").removeClass("fa-spin");}, 1000);
	});


});