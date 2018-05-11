// Quick shortcuts to common used or changed areas (for search)
// $("#search_type").change - control for search parameters
//
var current_sidebar_view = 'hide';
function toggle_cs_sidebar(view) {
    if (view == 'show') {
        jQuery("#ybi_cu_content_actions_work_meta").css({"display": "block", "visibility": "visible"});
        jQuery('#main_toggle_arrow').html('<i class="fa fa-caret-right"></i>')
        current_sidebar_view = 'show';
        jQuery("#cs_go_top").css({"display": "block", "visibility": "visible"});

    } else {
        jQuery("#ybi_cu_content_actions_work_meta").css({"display": "none", "visibility": "hidden"});
        jQuery("#main_toggle").css({"right": -4, "position": "fixed", "top": "50%"});
        jQuery('#main_toggle_arrow').html('<i class="fa fa-caret-left"></i>');
        jQuery("#cs_go_top").css({"display": "none", "visibility": "hidden"});
        current_sidebar_view = 'hide';
    }
}

jQuery(document).ready(function ($) {
    var summaryText = '';
    var ajax_url = yb_cu_post_vars.ajax_url;
    var default_brick = yb_cu_post_vars.default_brick_width;
    var cs_reddit_sort_default = yb_cu_post_vars.cs_reddit_sort_default;
    var spinner = '<i class="fa fa-spinner fa-spin"></i>';
    var spinner_full = '<div class="loading_full">Loading <i class="fa fa-spinner fa-spin"></i></div>';
    var toggle_on = '<i class="fa fa-toggle-on cs_good"></i>';
    var toggle_off = '<i class="fa fa-toggle-off notice_red"></i>';
    $("#le_access_tabs").tabs();

    /**
     * This function exists to ensure the sidebar scrolls and maintains the right height.
     * Notice it's called below on the window.on scroll.
    * */
    function adjust_main_container_height(element) {

        // this makes sure the code below is only called if the mouse is over the sidebar. Otherwise it will screw up new changes release in WP 4.7.2 and how the TMCE buttons top menu behaves.
        if ($('#ybi_curation_suite_listening_links:hover').length != 0) {
            // this check is here because if it's not then the submenus in the WP dashboard float to the top.
            // This checks to see if the mouse is currently hovering over a wp-menu that has a submenu. No need to worry about this on normal menus.
            if ($('.wp-has-submenu').not(':hover')) {
                var height = $(element).height();
                height = height + 500;
                $('#wpwrap').css("height", height + 'px');
            }
        }
    }
    $(window).on('scroll', function () {
        adjust_main_container_height('#ybi_curation_suite_listening_links');
    });

    function post_notice_load_display(text)
    {
        $('#cs_modal_popup').show().delay(1000);
        $('#cs_modal_text').html('<p><i class="fa fa-spinner fa-spin"></i> ' + text + '</p>');
    }

    function post_notice_display(text)
    {
        $('#cs_modal_popup').show().delay(1000);
        $('#cs_modal_text').html('<p class="cs_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> ' + text + '</p>');
        $('#cs_modal_popup').hide(3000);
    }

    function get_spinner_full_with_text(text) {
        return '<div class="loading_full"><i class="fa fa-spinner fa-spin"></i> ' + text + '</div>';
    }

    // this will set the height when a tab is clicked
    $("#ybi_curation_suite_listening_links").delegate("#platform_setup_tabs li a", "click", function () {
        adjust_main_container_height('#platform_setup_results');
    });

    $(".show_div").click(function () {
        var elem = jQuery(this);
        var theName = elem.attr('name')
        $(".action_div").css({"display": "none", "visibility": "hidden"});
        $("#" + theName).css({"display": "block", "visibility": "visible"});

    });

    var quick_add_current_location = '';
    $(".show_content_div").click(function () {
        var elem = jQuery(this);
        var theName = elem.attr('name')
        $(".content_action_div").css({"display": "none", "visibility": "hidden"});
        $(".show_content_div").css({"background": "none repeat scroll 0 0 #333"});
        $("#" + theName).css({"display": "block", "visibility": "visible"});
        elem.css({"background": "#0074a2 no-repeat url(../wp-content/plugins/curation-suite/i/indicator.png) center bottom"});

        // these 2 blocks will switch the quick add elements back and forth. There is also jQuery that does this if the LE is loaded located in the cu-content-actions-meta.php
        if (theName == "content_search_block")
            $("#base_quick_add_block").detach().appendTo("#cod_quick_add_block");

        if (theName == "cu_listening_platform")
            $("#base_quick_add_block").detach().appendTo("#le_quick_add_block");

    });

    $("#toggle").click(function () {
        if (current_sidebar_view == 'hide')
            toggle_cs_sidebar('show');
        else
            toggle_cs_sidebar('hide');
    });

    function go_to_top() {
        $('html, body').animate({scrollTop: 0}, 'slow');
    }

    $(".go_top").click(function () {
        go_to_top();
    });

    function go_top_cs_sidebar() {
        $("#ybi_cu_content_actions_work_meta").animate({scrollTop: 0}, "fast");
    }

    $("#cs_go_top").click(function () {
        go_top_cs_sidebar();
    });

    function showNoticeMessage(inMessage) {
        $('.notice_message').html(inMessage).show().delay(1000).hide(100);
    }

    function cs_loading_show_text(action, show_loading_elements_arr, text) {
        if (action == 'show') {
            //$("#cs_le_loading").css({"display":"block","visibility":"visible"});
            for (index = 0; index < show_loading_elements_arr.length; index++) {
                $(show_loading_elements_arr[index]).css({"text-align": "center"});
                $(show_loading_elements_arr[index]).css({"display": "block", "visibility": "visible"});
                $(show_loading_elements_arr[index]).html(get_spinner_full_with_text(text));

            }
        } else {
            for (index = 0; index < show_loading_elements_arr.length; index++) {
                $(show_loading_elements_arr[index]).css({"display": "none", "visibility": "hidden"});
            }
        }
    }

    function cs_le_loading_show(action, show_loading_elements_arr) {
        //$("#cs_le_loading").toggle();
        if (action == 'show') {
            $("#cs_le_loading").css({"display": "block", "visibility": "visible"});
            for (index = 0; index < show_loading_elements_arr.length; index++) {
                //$(show_loading_elements_arr[index]).css({"text-align":"center"});
                $(show_loading_elements_arr[index]).html(spinner_full);
            }
        } else {
            $("#cs_le_loading").css({"display": "none", "visibility": "hidden"});
        }
    }

    function urlencode(str) {
        //       discuss at: http://phpjs.org/functions/urlencode/
        //      original by: Philip Peterson
        //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      improved by: Brett Zamir (http://brett-zamir.me)
        //      improved by: Lars Fischer
        //         input by: AJ
        //         input by: travc
        //         input by: Brett Zamir (http://brett-zamir.me)
        //         input by: Ratheous
        //      bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      bugfixed by: Joris
        // reimplemented by: Brett Zamir (http://brett-zamir.me)
        // reimplemented by: Brett Zamir (http://brett-zamir.me)
        //             note: This reflects PHP 5.3/6.0+ behavior
        //             note: Please be aware that this function expects to encode into UTF-8 encoded strings, as found on
        //             note: pages served as UTF-8
        //        example 1: urlencode('Kevin van Zonneveld!');
        //        returns 1: 'Kevin+van+Zonneveld%21'
        //        example 2: urlencode('http://kevin.vanzonneveld.net/');
        //        returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
        //        example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
        //        returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'

        str = (str + '').toString();

        // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
        // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
        return encodeURIComponent(str)
            .replace(/!/g, '%21')
            .replace(/'/g, '%27')
            .replace(/\(/g, '%28')
            .replace(/\)/g, '%29')
            .replace(/\*/g, '%2A')
            .replace(/%20/g, '+');
    }

    function listToAray(fullString, separator) {
        var fullArray = [];

        if (fullString !== undefined) {
            if (fullString.indexOf(separator) == -1) {
                fullArray.push(fullString);
            } else {
                fullArray = fullString.split(separator);
            }
        }

        return fullArray;
    }

    function unique(list) {
        var result = [];
        $.each(list, function (i, e) {
            if ($.inArray(e, result) == -1) result.push(e);
        });
        return result;
    }


    function loadContent(isRepull) {
        $('.loading').show();
        //if(!isRepull)
        clearAllElements(false);
        $(".total").html("");
        //$('html, body').animate({scrollTop: $("#ybi_curation_suite_meta").offset().top}, 1000);
        $(".content_action_div").css({"display": "none", "visibility": "hidden"});
        $(".show_content_div").css({"background": "none repeat scroll 0 0 #333"});
        $("#visual_editor").css({"display": "block", "visibility": "visible"});
        $("#ybi_cu_content_actions_work_meta").css({"height": "100vh"});
        $('#cu_visual_editor_tab_control').css({"background": "#0074a2 no-repeat url(../wp-content/plugins/curation-suite/i/indicator.png) center bottom"});
        var source_url = $("#source_url").val();
        // these are replaced because it causes some security rules to trip, so we mask http/s
        source_url = source_url.replace("http://", "");
        source_url = source_url.replace("https://", "xxxxs");
        //var encoded = encodeURIComponent(source_url);

        // if we use the plugin files then call the plugin file location adding the source_url as a parm and the homepath
        if (yb_cu_post_vars.use_plugin_files == 'yes')
            var get_url = yb_cu_post_vars.plugins_url + '/curation-suite/admin-files/parse-page-worker.php?homepath=' + yb_cu_post_vars.ybi_home_path + '&url=' + urlencode(source_url);
        else
            var get_url = 'parse-page-worker.php?url=' + urlencode(source_url);

        //alert(get_url);
        if (isRepull) {
            // we grab all the checkboxes
            var turn_off_sections = $('input[name=turn_off_sections]');
            var turn_off_url = '';
            var checkedNum = 1;
            // for each element that is checked we added it to the url seperated by a comma
            // in the php code we will explode this to get each element
            $(turn_off_sections).each(function () {
                if ($(this).prop('checked')) {
                    if (checkedNum > 1)
                        turn_off_url += ',';

                    turn_off_url += $(this).attr('id');
                    checkedNum++;
                }
            });

            // if we added elements then add the parameter and values and below it's added to the url
            if (turn_off_url != '')
                turn_off_url = '&turn_off=' + turn_off_url;

            // set the url and add repull to true
            get_url += "&repull=1" + turn_off_url;
        }
        //console.log("source_url = " + get_url);
        var default_image = yb_cu_post_vars.plugins_url + '/curation-suite/i/no-image-selected.png';
        $("#curated_thumbnail").val('');
        $("#chosenthumbnail").attr("src", default_image);
        $('#contentiframe').attr('src', get_url);
        //$('html, body').animate({scrollTop: $("#contentiframe").offset().top}, 1000);
        $("#contentiframe").css({"display": "block", "visibility": "visible"});
        $(".link_shortcuts").css({"display": "block", "visibility": "visible"});
        $("#link_domain_tools").css({"display": "block", "visibility": "visible"});
        $("#content_block_links").css({"display": "block", "visibility": "visible"});
    }

    $('#load_content').click(function () {
        // false means it's not a repull
        loadContent(false);
    });
    $('#repull').click(function () {
        // true means it is a repull
        loadContent(true);
    });

    $('.link_to_load').click(function () {
        var elem = $(this);
        var theName = elem.attr('name')
        $("#source_url").val(theName)
        loadContent(false);
    });


    function nl2br(str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        //return (str + '').replace(/([^>\n\n]?)(\n\n)/g, '$1'+ breakTag +'$2');
    }

    function nl2brSimple(value) {
        return value.replace(/\n\n/g, "<br />");
    }

    function clearAllElements(inClearUrl) {

        if (inClearUrl)
            $("#source_url").val('');

        $("#curated_link_text").val('');
        $("#curated_headline").val('');
        $("#curated_thumbnail").val('');
        $("#summary_text_textarea").val('');
        $("#chosenthumbnail").attr("src", "");
        $(".clear_thumbnail").trigger("click");
        $("#contentiframe").css({"display": "none", "visibility": "hidden"});
        $("#link_domain_tools").css({"display": "none", "visibility": "hidden"});
        $(".link_shortcuts").css({"display": "none", "visibility": "hidden"});
        $(".content_add_error").html('');
        $(".content_add_error").css({"display": "none", "visibility": "hidden"});
        $(".total").html("");
    }

    /**
     * This function will send the image url to the ajax function to upload the chosen image.
     * Note: if the user has select a screenshot these are always uploaded
     */
    function do_image_upload(theThumbnail, post_ID) {
        return_img_url = '';
        data = {
            action: 'ybi_cu_upload_image',
            img_url: theThumbnail,
            post_ID: post_ID
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (image_results) {
                // if the image could be or was uploaded then we replace it in the content editor
                if (image_results.status) {
                    var overall_text = "";
                    var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
                    var ed = tinyMCE.get('content');
                    var content = ed.getContent();
                    content = content.replace(image_results.og_img_url, image_results.upload_img_url);
                    ed.setContent(content);
                }
                else {
                    // if there was an error we display this error. Porbably should take the actual error from what is returned but this will do for now
                    $(".content_add_error").css({"display": "block", "visibility": "visible"});
                    $(".content_add_error").html('<i class="fa fa-exclamation-triangle"></i> Due to security reasons the image you selected can\'t be uploaded to your site. This image was not uploaded and if displayed is pointing to the original source file.');
                }
            }
        });

    }

    // when a user clicks an add to post action button we save the curated link to a meta element of the post
    // this calls the ajax function below
    function do_save_curated_link_to_meta(inURL, post_ID) {
        return_img_url = '';
        data = {
            action: 'ybi_cu_add_curated_url_to_meta',
            url: inURL,
            post_ID: post_ID
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                // do nothing, worker function
            }
        });

    }

    // This calls the ajax function and passes new possible tags and existing tags.
    // On success it will set the hidden value (curation_suite_saved_tags_hidden) and display it in the saved curated tags module.
    function do_saved_curated_tags(inExistingTags, inNewTags) {
        return_img_url = '';
        data = {
            action: 'ybi_cu_saved_tags',
            inExistingTags: inExistingTags,
            inNewTags: inNewTags
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (result) {
                $("#curation_suite_saved_tags").html(result.all_tags);
                // set the hidden value so these tags can be saved
                $("#curation_suite_saved_tags_hidden").val(result.all_tags);
            }
        });

    }


    /**
     **    This is the main function for adding to the post box
     ** it also fires all the other relevant actions that happen when an add to post action button is clicked.
     */
    $('.add_to_post_box').click(function () {
        //content_added
        $(".content_added").css({"display": "block", "visibility": "visible"});
        //$('.content_added').show(1000);
        var elem = $(this);
        // this will either be with the headline or without the headline
        var theTypeOfAdd = elem.attr('name'); // we use the name attr to hold this value

        var isBlockQuoteOn = $('#myimage_cu_blockquote_switch').is(':checked');
        var link_image = $('#cs_link_image').is(':checked');
        var attribution_link_location = $("#attribution_link_location").val();

        // switch the TinyMCE to visual
        $('#content-tmce').click();

        var stageValue = '';
        var stagingTextWithBreaks = '';
        var textWithBreaks = '';
        var theThumbnail = '';

        stageValue = $("#summary_text_textarea").val();
        // this first check is to see if this is the add to post button next to the curated content, if so we don't add anything else.
        if (theTypeOfAdd != 'add_to_post_curated_only') {
            var theLink = '';
            theLink = $("#source_url").val();
            // hide any errors
            $(".content_add_error").css({"display": "none", "visibility": "hidden"});

            var theImageAlign = elem.attr('rel'); // we use the rel to hold this value
            if (typeof theImageAlign === "undefined")
                theImageAlign = 'alignleft';
            if (theImageAlign == '')
                theImageAlign = 'alignleft';

            theThumbnail = $("#curated_thumbnail").val();
            var theThumbnailSize = $("#image_sizing").val();
            var thumbnailHTML = '';

            if (theThumbnailSize == '')
                theThumbnailSize = 200;
            if (theThumbnail != '') {
                thumbnailHTML = '<img src="' + theThumbnail + '" width="' + theThumbnailSize + '" class="' + theImageAlign + ' cs_cur_image" alt="' + theLink + '" />';
            }

            //after we get the value let's make sure the breaks stay in
            if (stageValue != '') {
                stagingTextWithBreaks = nl2br(stageValue);
                textWithBreaks = textWithBreaks + stagingTextWithBreaks;
            }
            var theLinkText = '';
            var isIframe = false;
            var isNoFollow = false;
            isNoFollow = yb_cu_post_vars.curation_suite_no_follow;
            isIframe = stageValue.search("<iframe ") >= 0;
            var isAddWithHealine = $('#myimage_cu_headline_switch').is(':checked'); //(theTypeOfAdd == 'add_headline' || theTypeOfAdd == 'add_headline_with_blockquote' );
            var isHeadlineLink = (attribution_link_location == 'link_headline');

            var linkWrap = '';
            if (isIframe)
                linkWrap = 'p';

            var theCuratedHeadline = $("#curated_headline").val();
            if (isHeadlineLink)
                theLinkText = theCuratedHeadline;
            else
                theLinkText = $("#curated_link_text").val();

            theLinkHTML = '';
            if (theLinkText != '') {
                if (linkWrap != '')
                    theLinkHTML = '<' + linkWrap + '>';
                //textWithBreaks = textWithBreaks + ' <a href="' + theLink + '" target="_blank" rel="nofollow">' + theLinkText + '</a>';

                theLinkHTML += '<a href="' + theLink + '" target="_blank" class="cs_link"';

                if (isNoFollow)
                    theLinkHTML += 'rel="nofollow"';

                if (link_image) {
                    thumbnailHTML = theLinkHTML + '>' + thumbnailHTML + '</a>';
                }

                theLinkHTML += '>' + theLinkText + '</a>';

                if (linkWrap != '')
                    theLinkHTML += '</' + linkWrap + '>';
            }

            // this is passed via localize, is what the headline should be wrapped in
            var headline_wrap = yb_cu_post_vars.headline_wrap;
            var theHeadlineHTML = '';
            if (isAddWithHealine || isHeadlineLink) {
                if (isHeadlineLink) {
                    theHeadlineHTML = '<' + headline_wrap + '>' + theLinkHTML + '</' + headline_wrap + '>';
                }
                else
                    theHeadlineHTML = '<' + headline_wrap + '>' + theCuratedHeadline + '</' + headline_wrap + '>';
            }


            if (attribution_link_location == 'link_above') // if the link is above we don't add it to the overall text, that's added seperately below
                textWithBreaks = theHeadlineHTML + thumbnailHTML + ' ' + textWithBreaks;
            else {
                if (attribution_link_location == 'link_before')
                    textWithBreaks = theHeadlineHTML + thumbnailHTML + theLinkHTML + ' ' + textWithBreaks;
                else {
                    if (attribution_link_location == 'link_headline') // if the link is the headline then we create the text to be added
                        textWithBreaks = theHeadlineHTML + thumbnailHTML + textWithBreaks;
                    else  // here the only option left is link after text
                        textWithBreaks = theHeadlineHTML + thumbnailHTML + textWithBreaks + ' ' + theLinkHTML;
                }
            }
            var title = $('#title').val();
            if (!title) {
                add_text_to_title(theCuratedHeadline);
            }

        } //if(theTypeOfAdd != 'add_to_post_curated_only')
        else {
            // here we add whatever is in the staging area to the post, pretty much raw
            if (stageValue != '') {
                stagingTextWithBreaks = nl2br(stageValue);
                textWithBreaks = textWithBreaks + stagingTextWithBreaks;
            }
        }

        var overall_text = "";
        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        var ed = tinyMCE.get('content');

        // here we add the curated content that was staged, plus wrap it in a blockquote with a class

        if (theTypeOfAdd == 'add_to_post_curated_only') {
            //ed.dom.add(ed.getBody(), 'p', '', textWithBreaks );
            textWithBreaks = textWithBreaks + '<p></p>';
            tinymce.activeEditor.execCommand('mceInsertContent', false, textWithBreaks);
        }
        else {

            // now we add the main content, either wrapped in blockquote or not. Also the textWithBreaks var has all the combined text
            // logic for if the link is within this text is handled above
            if (isBlockQuoteOn)
            //ed.dom.add(ed.getBody(), 'blockquote', {'class' : 'curated_content'}, textWithBreaks );
                textWithBreaks = '<blockquote class="curated_content">' + textWithBreaks + '</blockquote>'
            else
            //ed.dom.add(ed.getBody(), 'p', '', textWithBreaks );
                textWithBreaks = '<p class="curated_content">' + textWithBreaks + '</p>'

            // if the link was chosen to be added above we first add that seperately it wasn't added above
            if (attribution_link_location == 'link_above')
            //ed.dom.add(ed.getBody(), 'p', '', theLinkHTML );
                textWithBreaks = '<p>' + theLinkHTML + '</p>' + textWithBreaks;

            textWithBreaks = textWithBreaks + '<p></p>';
            tinymce.activeEditor.execCommand('mceInsertContent', false, textWithBreaks);
        }

        // we check if this is a thumbnail image, if it is then we upload it.
        isThumbnail = theThumbnail.search("wordpress.com/mshots") >= 0;
        //alert("isThumbnail:" + isThumbnail);
        // the user can choose to have all images uploaded
        // yb_cu_post_vars.curation_suite_upload_images - we used to pass this but now the user can choose on a curation by curation basis.
        // if the checkbox is checked or it's a thumbnail we now upload
        if ($('#myimage_uploads_switch').is(':checked') || isThumbnail) {
            if (theThumbnail != '') {
                do_image_upload(theThumbnail, $("#post_ID").val());
            }
        }

        // the following code is for saving tags
        // grab the current tags
        var all_current_tags = $("#curation_suite_saved_tags_hidden").val();
        // get the tags from the parse worker iframe
        var all_new_tags = $('#contentiframe').contents().find('#all_suggested_tags').attr('rel');
        // combine the existing tags and the new tags
        var combined_tags = all_current_tags;
        if (typeof all_new_tags === "undefined")
            all_new_tags = '';

        // if there are new tags then add call the ajax method that adds them to the saved curated tags module
        if (all_new_tags != '')
            do_saved_curated_tags(all_current_tags, all_new_tags);

        // save the curated link to the meta elements for this post
        do_save_curated_link_to_meta(theLink, $("#post_ID").val());

        if ($('#clear_when_add').is(':checked')) {
            clearAllElements(true);
        }
        $('.content_added').hide(2000);

        var delete_curated_link_on_add_id = $('#delete_curated_link_on_add_id').val();
        if (delete_curated_link_on_add_id != '')
            deleteCurationLink(delete_curated_link_on_add_id);

        // both of these are defaulted
        var curated_content_item_id = $('#curated_content_item_id').val(); // this will be 0 if it wasn't from listening platform
        var after_curation_action = $('#curated_content_item_id').attr('after-curation-action'); // this will be set to curated-content-item-curate unless user clicked curate/remove
        if (after_curation_action == '')
            after_curation_action = 'curate';

        doPlatformAction('add', 'curated-content-item', curated_content_item_id, theLink, after_curation_action, false);
    });

    function refreshEditor() {
        $('#content-tmce').click();
        $('#content-html').click();
        $('#content-tmce').click();
    }

    // below this is the function to add image credit text to the post pox
    // this binds the link_to_load links because they are loaded by ajax
    $("#ybi_cu_image_credit_actions").delegate(".add_raw_text_to_post", "click", function () {

        var elem = $(this);
        var overall_text = elem.attr('rel'); // this is the text we add
        var element_added = elem.attr('name'); // this is the element we are adding so we can show the indication
        $('.' + element_added).css({"display": "block", "visibility": "visible"});  // show the add indicator
        //' + element_added +'
        //var original_added_text = $('.' + element_added).html();
        //$('.' + element_added).html('<div><i class="fa fa-plus"></i> Content Added to Post</div>');
        $('#content-tmce').click(); // ensure we are on visual
        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        var ed = tinyMCE.get('content');
        var wrap_element = '';
        wrap_element = $("#curation_suite_image_credit_wrap_element").val();
        if (typeof(wrap_element) === "undefined" || wrap_element == '')
            wrap_element = 'none';

        var wrap_class = '';
        wrap_class = $("#curation_suite_image_credit_wrap_class").val();
        if (typeof wrap_class === "undefined" || wrap_class == '')
            wrap_class = 'cu_image_credit';

        if (wrap_element == 'none') {
            ed.dom.add(ed.getBody(), 'p', '', overall_text);
        }
        else {
            ed.dom.add(ed.getBody(), wrap_element, {'class': wrap_class}, overall_text);
        }

        $('.' + element_added).hide(2000); // hide the indicator
    });

    // this will add all tags in the saved curated tags module to the current post
    $("#add_all_saved_tags").click(function () {
        //$(".meta_tag_added").css({"display":"block","visibility":"visible"});

        var tagsValue = '';
        tagsValue = $('#curation_suite_saved_tags_hidden').val();
        //if(tagsValue.length > 0)
        //tagsValue = tagsValue + ', ';
        $('#new-tag-post_tag').val(tagsValue);

        $('.tagadd').click();
        $('.meta_tag_added').hide(1000);
    });

    $("#reload_thumbnail").click(function () {

        //$("#contentiframe").contents().find('#content_thumbnail').css({"border-color":"red","border-size":"1px"});
        $("#reload_thumbnail_indicator").addClass('fa-spin');

        var elem = $(this);
        var theText = elem.attr('rel');
        $("#contentiframe").contents().find('#content_thumbnail').attr('src', theText);
        //$("#contentiframe").contents().find('#content_thumbnail').css({"border":"none"});
        //$("#reload_thumbnail_indicator").removeClass('fa-spin');
        window.setTimeout(function () {
            $("#reload_thumbnail_indicator").removeClass("fa-spin");
        }, 1000);
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".change_link_text", "click", function () {
        // if link is checked then we reload the iframe
        // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
        var elem = $(this);
        var theName = elem.attr('name')
        var theText = '';

        theText = elem.html();
        if (theName == 'copy_headline')
            theText = $("#curated_headline").val();

        $('#curated_link_text').val(theText);
    });

    $(".change_image_sizing").click(function () {
        // if link is checked then we reload the iframe
        // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
        var elem = $(this);
        var theName = elem.attr('name')
        var theText = '';
        theText = elem.html();
        $('#image_sizing').val(theText);
    });


    $(".clear_element").click(function () {
        var elem = $(this);
        var theName = elem.attr('name')
        if (theName == 'CLEARALL')
            clearAllElements(true);
        else {
            $('#' + theName).val('');

            if (theName == 'source_url')
                $("#link_domain_tools").css({"display": "none", "visibility": "hidden"});
        }
    });
    $(".clear_thumbnail").click(function () {
        var default_image = yb_cu_post_vars.plugins_url + '/curation-suite/i/no-image-selected.png';
        $("#curated_thumbnail").val('');
        $("#chosenthumbnail").attr("src", default_image);
    });

    $("#curated_thumbnail").on("propertychange change keyup paste input", function () {
        $("#chosenthumbnail").attr("src", $("#curated_thumbnail").val());
    });

    // this is so we can keep the OG headline if the user clicks clean headline link
    var ogHeadline = '';
    $("#cleanHeadline").click(function (event) {

        // get headline
        ogHeadline = $("#curated_headline").val();
        // save to new so we have the og just in case we need it
        changedHeadline = ogHeadline;
        // find common way people name headlines

        // common headline seperators we check for
        var charArray = ['|', ' -', ' —', ' –', ' :'];
        $.each(charArray, function (intValue, currentElement) {
            // find current element location
            var n = changedHeadline.indexOf(currentElement);
            // create a substring
            changedHeadline = changedHeadline.substring(0, n != -1 ? n : changedHeadline.length);
            changedHeadline = $.trim(changedHeadline);
            // Do work with currentElement
        });


        // set the headline back and trim
        $("#curated_headline").val(changedHeadline);
    });
    $("#originalHeadline").click(function (event) {
        // get OG headline that is hidden
        var headline = $("#og_curated_headline").val()
        $("#curated_headline").val(headline);
    });

    function add_text_to_title(in_text) {
        $("#title").val(in_text);
        $("#title-prompt-text").addClass('screen-reader-text');
        post_notice_display('Added to Post Title');
    }

    $("#add_headline_to_title").click(function (event) {
        // get headline
        var headline = $("#curated_headline").val();
        add_text_to_title(headline);
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".add_to_title", "click", function () {
        var elem = $(this);
        var elem_class = elem.attr('rel');
        var text = $('.' + elem_class).html();
        add_text_to_title(text);
    });

    $(".show_div").click(function () {
        var elem = $(this);
        var theName = elem.attr('name')
        $("#contentiframe").contents().find(".selector_div").css({"display": "none", "visibility": "hidden"});
        $("#contentiframe").contents().find("#" + theName).css({"display": "block", "visibility": "visible"});
        // scroll to the top of the iframe
        $("#contentiframe").contents().scrollTop(0);
        //$('#contentiframe').css({'height':'auto'});
        $('#contentiframe').css({'height': $('#contentiframe').contents().height(), 'overflow': 'auto'});
    });

    $(".set_as_title").click(function () {
        var elem = $(this);
        var theName = elem.attr('name');
    });

    // this binds the link_to_load links because they are loaded by ajax
    $("#ybi_curation_suite_bucket_links").delegate(".link_to_load", "click", function () {
        var elem = $(this);
        var theURL = elem.attr('name');
        var delete_curated_link_on_add_id = elem.attr('rel');
        if (delete_curated_link_on_add_id != '')
            $('#delete_curated_link_on_add_id').val(delete_curated_link_on_add_id);

        $("#source_url").val(theURL);
        go_top_cs_sidebar();
        loadContent(false);
    });

    // this binds the link_to_load links because they are loaded by ajax
    $("#ybi_curation_suite_scrape_content").delegate(".link_to_load", "click", function () {
        var elem = $(this);
        var theURL = elem.attr('name');
        $("#source_url").val(theURL);
        loadContent(false);
    });

    // the simple add to post from a scrape search.
    $("#ybi_curation_suite_scrape_content").delegate(".add_scrape_to_post", "click", function () {
        var elem = $(this);
        var theRowClass = elem.attr('name');
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        var raw_html = $('.' + theRowClass).html();
        data = {
            action: 'ybi_curation_suite_get_mini_row_content',
            row_class: theRowClass,
            raw_html: raw_html
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (html) {
                //alert(html.overall_text);

                //var elem = $(this);

                //var overall_text = elem.attr('rel'); // this is the text we add
                $('#content-tmce').click(); // ensure we are on visual
                var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
                var ed = tinyMCE.get('content');
                ed.dom.add(ed.getBody(), 'p', '', html.overall_text);
                elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Added</span>'); // hide the indicator and note it was added
                elem.contents().unwrap();

            }
        });
    });

    // this is the load link for content searches
    $("#ybi_curation_suite_content_searches").delegate(".link_to_load", "click", function () {
        var elem = $(this);
        var theURL = elem.attr('name');
        //var delete_curated_link_on_add_id = elem.attr('rel');
        //if(delete_curated_link_on_add_id != '')
        //	$('#delete_curated_link_on_add_id').val(delete_curated_link_on_add_id);
        // get the source url
        $("#source_url").val(theURL);
        go_top_cs_sidebar();
        loadContent(false);
    });

    $('#bucket_search_term').keyup(function () {
        $('#cu_current_link_page').val(1);
        loadCurationLinks();
    });


    $(".show_bucket_links").click(function () {
        //$('.rcp-ajax').show();
        var isTextSearch = 'no';
        $('#cu_current_link_page').val(1);
        loadCurationLinks();
    });


    $("#ybi_curation_suite_bucket_links").delegate(".cu_link_change_page", "click", function () {

        var clicked_page = $(this).attr('rel');
        $('#cu_current_link_page').val(clicked_page);
        var isTextSearch = 'no';
        loadCurationLinks();
    });

    // this loads the curation links and displays them with relevant actions
    function loadCurationLinks() {
        // show the loading incon
        $('.rcp-ajax').show();
        // blank the content
        $('#ybi_curation_suite_bucket_links').html('');
        var search_query = $('#bucket_search_term').val();
        var current_page = $('#cu_current_link_page').val();
        //bucket_search_term bucket_category_id bucket_link_sort_order bucket_link_author_id
        var bucket_category_id = $('#bucket_category_id').val();
        var bucket_link_sort_order = $('#bucket_link_sort_order').val();
        var bucket_link_author_id = $('#bucket_link_author_id').val();
        data = {
            action: 'ybi_curation_suite_get_bucket_links',
            bucket_category_id: bucket_category_id,
            bucket_link_sort_order: bucket_link_sort_order,
            bucket_link_author_id: bucket_link_author_id,
            search_query: search_query,
            current_page: current_page
            //curate_action_search_nonce: curate_action_vars.curate_action_search_nonce
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //alert(search_response.results);
                $('.rcp-ajax').hide();
                // this returns an html tab with rows and all the action links
                $('#ybi_curation_suite_bucket_links').html(search_response.results);
                // we update the total span
                $('#total_posts_display').html(search_response.total_posts_display);

            }
        });
    }

    // this calls the function to delete a curation link
    function deleteCurationLink(curation_link_id) {
        var isTextSearch = 'no';
        data = {
            action: 'ybi_curation_suite_delete_bucket_link',
            curation_link_id: curation_link_id
            //curate_action_search_nonce: curate_action_vars.curate_action_search_nonce
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                $(search_response.results).appendTo('#ybi_curation_suite_bucket_links');
                loadCurationLinks();
            }
        });
    }

    $(".delete_curation_link").click(function () {
        // do nothing as it's from ajax right now
    });

    // this is a delegate function to fire the delete curation link
    $("#ybi_curation_suite_bucket_links").delegate(".delete_curation_link", "click", function () {
        $('.rcp-ajax').show();
        var curation_link_id = $(this).attr('name');
        deleteCurationLink(curation_link_id);
    });

    // this loads the socail media share actions
    $(".load_social_media_actions").click(function () {
        var post_content = "";
        $('.loading_social').show();
        $('#content-tmce').click();
        var title = $('#title').val();
        var post_id = $('#post_ID').val();
        var social_action_text_options = $('#social_action_text_options').val();
        var social_action_text = $('#social_action_text').val();
        var social_text_location = $('#social_text_location').val();
        var load_social_media_actions_nonce = $('#load_social_media_actions_nonce').val();

        var ignore_social_options = [];
        $('#ignore_social_options :checked').each(function () {
            ignore_social_options.push($(this).val());
        });
        var load_co_schedule = $('#load_co_schedule').is(':checked');
        // this is grabbing the current text in the editor
        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        //var post_content = tinyMCE.get('content');
        data = {
            action: 'ybi_curation_suite_social_meta_load',
            post_content: original_text,
            title: title,
            post_id: post_id,
            social_action_text_options: social_action_text_options,
            social_action_text: social_action_text,
            social_text_location: social_text_location,
            load_co_schedule: load_co_schedule,
            ignore_social_options: ignore_social_options,
            load_social_media_actions_nonce: load_social_media_actions_nonce
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                $('.loading_social').hide();
                // this returns a table with social media updates and short cut links
                $('#ybi_cu_social_media_actions').html(search_response.results);
            }
        });
    });

    // this takes the user values from the drop downs, fires the ajax function to create text for the image credit
    $(".load_image_credit_actions").click(function () {
        var post_content = "";
        $('.loading_social').show();
        $('#content-tmce').click();
        var title = $('#title').val();
        var post_id = $('#post_ID').val();
        var image_credit_value_one = $('#image_credit_value_one').val();
        var image_credit_value_two = $('#image_credit_value_two').val();
        var load_social_media_actions_nonce = $('#load_social_media_actions_nonce').val();
        $('#ybi_cu_image_credit_actions').html(spinner);
        var text_return_type = 'full';

        // this is grabbing the current text in the editor
        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        //$('#ybi_cu_image_credit_actions').html();
        //var post_content = tinyMCE.get('content');
        data = {
            action: 'ybi_curation_suite_image_credit_load',
            post_content: original_text,
            title: title,
            post_id: post_id,
            image_credit_value_one: image_credit_value_one,
            image_credit_value_two: image_credit_value_two,
            text_return_type: text_return_type,
            load_social_media_actions_nonce: load_social_media_actions_nonce
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                $('.loading_social').hide();
                $('#ybi_cu_image_credit_actions').html(search_response.results);
            }
        });
    });	//load_image_credit_actions

    // this is when the user clicks the button
    $(".content_keyword_search").click(function () {
        //$('.rcp-ajax').show();
        var isTextSearch = 'no';
        $('#cu_current_link_page').val(1);
        //alert('content_keyword_search');
        loadContentOnDemandSearch(0);

    });

    $(".scrape_load").click(function () {
        loadContentScraping(0);
    });

    function loadContentScraping(inStart) {
        $('.rcp-ajax').show();
        $('#ybi_curation_suite_scrape_content').html('');
        var content_scrape_id = $('#content_scrape_id').val();
        var load_direct_share = $('#load_direct_share_scrape').is(':checked');
        //alert(content_scrape_id);

        data = {
            action: 'ybi_curation_suite_get_scrape_content',
            content_scrape_id: content_scrape_id,
            load_direct_share: load_direct_share,
            start: inStart
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //alert('loaded');
                $('.rcp-ajax').hide();
                $('#ybi_curation_suite_scrape_content').html(search_response.results);
            }
        });
    }

    // this is when we add a keyword to the keyword list
    $("#cu_user_search_keywords").delegate(".find_content_keyword", "click", function () {
        $('#content_search_term').val($(this).html());
        //alert('cu_user_search_keywords');
        loadContentOnDemandSearch(0);
    });

    // this is when we move pages
    $("#ybi_curation_suite_content_searches").delegate(".move_page", "click", function () {
        var start_point = $(this).attr('rel');
        //alert(start_point);
        loadContentOnDemandSearch(start_point);
    });

    $("#ybi_curation_suite_listening_links").delegate(".content_search_add_to_post", "click", function () {
        var elem = $(this);
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        var theLink = elem.attr('rel');
        var overall_text = '<p> </p><p>' + theLink + '</p><p> </p>'; // this is the text we add
        $('#content-tmce').click(); // ensure we are on visual

        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        tinymce.activeEditor.execCommand('mceInsertContent', false, overall_text);

        var title = $('#title').val();
        if (!title) {
            var elem_class = elem.attr('data-link-no');
            var text = $('.link_lp' + elem_class).html();
            add_text_to_title(text);
        }

        //var ed = tinyMCE.get('content');
        //ed.dom.add(ed.getBody(), 'p', '', overall_text );
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Added</span>'); // hide the indicator and note it was added
        elem.contents().unwrap();
        refreshEditor();
        do_save_curated_link_to_meta(theLink, $("#post_ID").val());
    });


    $("#ybi_curation_suite_content_searches").delegate(".content_search_add_to_post", "click", function () {
        var elem = $(this);
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        var theLink = elem.attr('rel');
        var overall_text = '<p>' + theLink + '</p>'; // this is the text we add
        $('#content-tmce').click(); // ensure we are on visual

        var title = $('#title').val();

        if (!title) {
            var elem_class = elem.attr('data-link-no');
            var text = $('.link_lp' + elem_class).html();
            add_text_to_title(text);
        }

        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        tinymce.activeEditor.execCommand('mceInsertContent', false, overall_text);

        //var ed = tinyMCE.get('content');
        //ed.dom.add(ed.getBody(), 'p', '', overall_text );
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Added</span>'); // hide the indicator and note it was added
        elem.contents().unwrap();
        refreshEditor();
        do_save_curated_link_to_meta(theLink, $("#post_ID").val());
    });


    $("#ybi_curation_suite_listening_links").delegate(".embed_quick_add_link", "click", function () {
        var elem = $(this);
        //var overall_text = elem.html();
        var embed_type = elem.attr('data-type');
        var overall_text = '';
        var theLink = elem.attr('data-url');
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        $('#content-tmce').click(); // ensure we are on visual
        if (embed_type == 'twitter') {
            overall_text = '<p>' + theLink + '</p>';
            var twitter_user = elem.attr('data-tweet-user');
            var twitter_status_id = elem.attr('data-tweet-id');
            do_save_twitter_data_to_meta($("#post_ID").val(), twitter_status_id, twitter_user);
        } else {
            overall_text = '<p>' + elem.attr('data-url') + '</p>';
        }

        tinymce.activeEditor.execCommand('mceInsertContent', false, overall_text);
        elem.html('<span class="cqs_green"><i class="fa fa-plus embed-added"></i></span>'); // hide the indicator and note it was added
        do_save_curated_link_to_meta(theLink, $("#post_ID").val());
        //elem.contents().unwrap();
        //refreshEditor();
    });


    $("#ybi_curation_suite_content_searches").delegate(".embed_quick_add_link", "click", function () {
        var elem = $(this);
        //var overall_text = elem.html();
        var embed_type = elem.attr('data-type');
        var overall_text = '';
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        var theLink = elem.attr('data-url');
        $('#content-tmce').click(); // ensure we are on visual
        if (embed_type == 'twitter') {
            overall_text = '<p>' + elem.attr('data-url') + '</p>';
            var twitter_user = elem.attr('data-tweet-user');
            var twitter_status_id = elem.attr('data-tweet-id');
            do_save_twitter_data_to_meta($("#post_ID").val(), twitter_status_id, twitter_user);
        } else {
            overall_text = '<p>' + elem.attr('data-url') + '</p>';
        }

        tinymce.activeEditor.execCommand('mceInsertContent', false, overall_text);
        elem.html('<span class="cqs_green"><i class="fa fa-plus embed-added"></i></span>'); // hide the indicator and note it was added
        do_save_curated_link_to_meta(theLink, $("#post_ID").val());
        //elem.contents().unwrap();
        //refreshEditor();
    });

    // when a user clicks an add to post action button we save the curated link to a meta element of the post
    // this calls the ajax function below
    function do_save_twitter_data_to_meta(post_ID, inTweetStatusID, inTwitterUserName) {
        return_img_url = '';
        data = {
            action: 'ybi_cu_add_twitter_data_to_meta',
            tweet_status_id: inTweetStatusID,
            twitter_username: inTwitterUserName,
            post_ID: post_ID
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (image_results) {
                // do nothing, worker function
            }
        });

    }

// this sees if the user clicks enter in the search term box, if so then it interupts the default save form (which would be for the post) and it loads the search action.
    $('#content_search_term').keypress(function (e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            //alert('The enter key was pressed!');
            e.preventDefault();
            loadContentOnDemandSearch(0);
        }
    });

    // this search handles Google News, Google Blog, SlideShare, and Bing
    function loadContentOnDemandSearch(inStart) {
        //$('.rcp-ajax').show();

        $('#ybi_curation_suite_content_searches').html('');
        var search_query = $('#content_search_term').val();

        var current_page = $('#cu_current_link_page').val();
        var search_type = $('#search_type').val();
        var friendly_search_type = $("#search_type option:selected").text();  // get the text

        var loading_arr = [".rcp-ajax"];
        cs_loading_show_text('show', loading_arr, ' searching <strong>' + friendly_search_type + '</strong> for: <i>' + search_query + '</i>');

        var orderby = $('#orderby').val();
        var time_frame = $('#cs_search_time_frame').val();
        var search_ignore = $('#cs_search_ignore').val();
        var cs_total_results = $('#cs_total_results').val();
        var language = '';
        var ned_region = ''; //used for google news searches
        if (search_type == 'bing_news')
            language = $('#bing_languages').val();
        if (search_type == 'google_news') {
            language = $('#iso_search_language').val();
            ned_region = $('#google_news_blogs_language').val();
        }

        if (search_type == 'google_blog')
            language = $('#google_news_blogs_language').val();
        if (search_type == 'twitter')
            language = $('#iso_search_language').val(); // twitter users iso for lang
        if (search_type == 'youtube') {
            language = $('#iso_search_language').val(); // youtbue uses iso for lang
        }

        var show_player = $('#show_player').is(':checked');
        var load_direct_share = $('#load_direct_share_demand_search').is(':checked');
        var cs_highlight_curated_content = $('#cs_highlight_curated_content').is(':checked');
        var bucket_link_sort_order = $('#bucket_link_sort_order').val();
        data = {
            action: 'ybi_curation_suite_get_content_on_demand_search',
            search_type: search_type,
            bucket_link_sort_order: bucket_link_sort_order,
            search_query: search_query,
            orderby: orderby,
            time_frame: time_frame,
            search_ignore: search_ignore,
            cs_total_results: cs_total_results,
            language: language,
            ned_region: ned_region,
            show_player: show_player,
            load_direct_share: load_direct_share,
            cs_highlight_curated_content: cs_highlight_curated_content,
            start: inStart
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                $('.rcp-ajax').hide();
                $('#ybi_curation_suite_content_searches').html(search_response.results);
            }
        });
    }

    // When the search type drop down chaneges we also modify the additional search options (if they exist for that search type)
    $("#search_type").change(function () {
        // get the type of search
        var search_val = $(this).val();
        // we retain in case we want to use it but right now we aren't using it
        var orderby_val = $('#orderby').val();
        $('#orderby').empty(); //remove all child nodes
        // hide all language options and the right one will be displayed below
        $('.language_option').css({"display": "none", "visibility": "hidden"});
        $('#cs_total_results').css({"display": "none", "visibility": "hidden"});
        var newOption = '';

        // We default the most common elements and these are modified in the switch statement below
        var add_relevance = true;
        var add_published = true;
        var add_viewCount = true;
        var add_rating = true;
        var add_mostdownloaded = false; //this is false because it's only on one of the search types
        var add_most_recent = false;
        var add_total_shares = false;
        var add_trending = false;
        var add_facebook_total = false;
        var add_linkedin_shares = false;
        var add_googleplus_shares = false;
        var add_pinterest = false;
        var show_search_parms = true;
        var hide_load_player = false;
        var show_quick_add = true;
        var show_languages = false;
        var hide_order_by = false;
        var show_search_ignore = false;
        var show_total_results = false;
        var show_time_frame = false;
        var notice_text = '';
        switch (search_val) {
            case 'cs_le':
                show_search_parms = true;
                show_languages = false;
                hide_load_player = true;
                hide_order_by = false;

                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                $('#orderby').empty(); //remove all child nodes
                add_most_recent = true;
                add_total_shares = true;
                add_trending = true;
                add_facebook_total = true;
                add_linkedin_shares = true;
                add_googleplus_shares = true;
                add_pinterest = true;
                break;
            case 'curation_bot':
                show_search_parms = true;
                show_languages = false;
                hide_load_player = true;
                hide_order_by = false;

                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                $('#orderby').empty(); //remove all child nodes
                add_most_recent = true;
                add_total_shares = false;
                add_trending = false;
                add_facebook_total = true;
                add_linkedin_shares = true;
                add_googleplus_shares = true;
                add_pinterest = true;
                break;
            case 'reddit':
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                show_languages = false;
                show_quick_add = false;
                show_search_ignore = true;
                show_total_results = true;
                show_time_frame = true;
                hide_load_player = true;

                // (relevance, hot, top, new, comments)
                $('#orderby').empty(); //remove all child nodes
                newOption = $('<option value="new">New</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="hot">Hot</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="top">Top</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="comments">Comments</option>');
                $('#orderby').append(newOption);
                $('#orderby').val(cs_reddit_sort_default); // this is localized
                $("#cs_total_results").css({"display": "inline-block", "visibility": "visible"});

                $('#cs_search_ignore').empty(); //remove all child nodes
                newOption = $('<option value="threads">Show Threads</option>');
                $('#cs_search_ignore').append(newOption);

                $('#cs_search_ignore').empty(); //remove all child nodes
                newOption = $('<option value="ignore-threads">Ignore Threads</option>');
                $('#cs_search_ignore').append(newOption);
                newOption = $('<option value="show-threads">Show Threads</option>');
                $('#cs_search_ignore').append(newOption);
                break;
            case 'twitter':
                show_search_parms = true;
                show_quick_add = false;
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                hide_load_player = true;

                $('#orderby').empty(); //remove all child nodes
                newOption = $('<option value="recent">Most Recent</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="popular">Most Popular</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="mixed">Mixed Recent/Popular</option>');
                $('#orderby').append(newOption);
                // twitter uses iso search
                $("#iso_search_language").css({"display": "inline-block", "visibility": "visible"});
                break;
            case 'pocket':
                show_search_parms = true;
                show_languages = false;
                hide_load_player = true;
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                hide_order_by = false;
                show_total_results = true;
                show_search_ignore = true;

                $('#orderby').empty(); //remove all child nodes
                newOption = $('<option value="newest">Newest</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="oldest">Oldest</option>');
                $('#orderby').append(newOption);
                notice_text = "<p><i>* The Pocket search only searches titles. To load all your saved links don't enter a search keyword.</i></p>";

                $('#cs_search_ignore').empty(); //remove all child nodes
                newOption = $('<option value="all">All</option>');
                $('#cs_search_ignore').append(newOption);

                newOption = $('<option value="unread">Unread</option>');
                $('#cs_search_ignore').append(newOption);

                newOption = $('<option value="archive">Archived</option>');
                $('#cs_search_ignore').append(newOption);
                break;
            case 'imgur':
                //time | viral | top - defaults to time
                show_quick_add = false;
                show_search_parms = true;
                show_languages = false;
                hide_load_player = true;
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                hide_order_by = false;
                show_total_results = true;
                show_search_ignore = false;

                $('#orderby').empty(); //remove all child nodes
                newOption = $('<option value="time">Latest</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="viral">Viral</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="top">Top</option>');
                $('#orderby').append(newOption);
                break;
            case 'pinterest':
                //time | viral | top - defaults to time
                show_quick_add = false;
                show_search_parms = false;
                show_languages = false;
                hide_load_player = true;
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                hide_order_by = true;
                show_total_results = true;
                show_search_ignore = false;
                break;
            case 'instagram':
                //time | viral | top - defaults to time
                show_quick_add = true;
                show_search_parms = true;
                show_languages = false;
                hide_load_player = true;
                add_published = false;
                add_viewCount = false;
                add_rating = false;
                add_mostdownloaded = false;
                add_relevance = false;
                hide_order_by = false;
                show_total_results = true;
                show_search_ignore = false;

                $('#orderby').empty(); //remove all child nodes
                newOption = $('<option value="most_recent">Most Recent</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="top">Top</option>');
                $('#orderby').append(newOption);
                newOption = $('<option value="personal_feed">Personal Feed (no keyword search)</option>');
                $('#orderby').append(newOption);
                break;
            case 'google_news':
                show_search_parms = true;
                show_languages = true;
                hide_load_player = true;
                hide_order_by = true;
                $("#google_news_blogs_language").css({"display": "inline-block", "visibility": "visible"});  // this the ned or region search for google news
                $("#iso_search_language").css({"display": "inline-block", "visibility": "visible"}); // this is the return language
                break;
            case 'google_blog':
                show_search_parms = true;
                show_languages = true;
                hide_load_player = true;
                hide_order_by = true;
                $("#google_news_blogs_language").css({"display": "inline-block", "visibility": "visible"});
                break;
            case 'youtube':
                // do nothing because all the basic values above are default for youtube except quickadd
                show_quick_add = false;
                // youtube uses iso search
                $("#iso_search_language").css({"display": "inline-block", "visibility": "visible"});
                break;
            case 'dailymotion':
                // do nothing because all the basic values above are default for youtube except quickadd
                show_quick_add = false;
                break;
            case 'slideshare':
                add_rating = false;
                add_mostdownloaded = true;
                show_quick_add = false;
                break;
            case 'bing_news':
                add_viewCount = false;
                hide_order_by = true;
                add_rating = false;
                hide_load_player = true;
                show_search_parms = true;
                $("#bing_languages").css({"display": "inline-block", "visibility": "visible"});
                break;
            case 'yahoo_news':
                show_search_parms = false;
                hide_load_player = true;
                break;
            case 'giphy':
                show_search_parms = false;
                hide_load_player = true;
                show_quick_add = false;
                break;
        }

        if (show_search_parms) {
            // we display the additional search parms div
            $("#additional_search_parameters").css({"display": "block", "visibility": "visible"});
            // for some searches like YouTube or Slideshare we give an option to load a player, if that exists we display it or we can hide it
            if (hide_load_player)
                $(".load_player").css({"display": "none", "visibility": "hidden"});
            else
                $(".load_player").css({"display": "inline-block", "visibility": "visible"});

            if (hide_order_by)
                $("#orderby").css({"display": "none", "visibility": "hidden"});
            else
                $("#orderby").css({"display": "inline-block", "visibility": "visible"});
        }
        else
            $("#additional_search_parameters").css({"display": "none", "visibility": "hidden"});

        if (show_search_ignore) {
            $("#cs_search_ignore").css({"display": "block", "visibility": "visible"});
        } else {
            $("#cs_search_ignore").css({"display": "none", "visibility": "hidden"});
        }
        if (show_total_results) {
            $("#cs_total_results").css({"display": "block", "visibility": "visible"});
        } else {
            $("#cs_total_results").css({"display": "none", "visibility": "hidden"});
        }
        if (show_time_frame) {
            $("#cs_search_time_frame").css({"display": "block", "visibility": "visible"});
        } else {
            $("#cs_search_time_frame").css({"display": "none", "visibility": "hidden"});
        }
        if (show_quick_add) {
            $("#quick_add_parms").css({"display": "block", "visibility": "visible"});
        } else {
            $("#quick_add_parms").css({"display": "none", "visibility": "hidden"});
        }
        if (show_languages) {
            $("#cs_languages").css({"display": "inline-block", "visibility": "visible"});
        } else {
            $("#cs_languages").css({"display": "none", "visibility": "hidden"});
        }
        // now we go through each element and if it's true we add it to the additional search parms dropdown.
        if (add_most_recent) {
            newOption = $('<option value="most_recent"><i class="fa fa-sort-amount-asc most_recent"></i> Most Recent</option>');
            $('#orderby').append(newOption);
        }
        if (add_total_shares) {
            newOption = $('<option value="total_shares"><i class="fa fa-share-alt-square total_share"></i> Total Shares</option>');
            $('#orderby').append(newOption);
        }
        if (add_trending) {
            newOption = $('<option value="share_gravity"><i class="fa fa-signal share_gravity"></i> Trending</option>');
            $('#orderby').append(newOption);
        }
        if (add_facebook_total) {
            newOption = $('<option value="facebook_total"><i class="fa fa-facebook facebook"></i> Facebook</option>');
            $('#orderby').append(newOption);
        }
        if (add_linkedin_shares) {
            newOption = $('<option value="linkedin_shares"><i class="fa fa-linkedin linkedin"></i> LinkedIn</option>');
            $('#orderby').append(newOption);
        }
        if (add_googleplus_shares) {
            newOption = $('<option value="googleplus_shares"><i class="fa fa-google-plus googleplus"></i> Google+</option>');
            $('#orderby').append(newOption);
        }
        if (add_pinterest) {
            newOption = $('<option value="pinterest_shares"><i class="fa fa-pinterest pinterest"></i> Pinterest</option>');
            $('#orderby').append(newOption);
        }

        if (add_published) {
            newOption = $('<option value="published">Latest/Published Date</option>');
            $('#orderby').append(newOption);
        }
        if (add_relevance) {
            newOption = $('<option value="relevance">Relevance</option>');
            $('#orderby').append(newOption);
        }
        if (add_viewCount) {
            newOption = $('<option value="viewCount">View Count</option>');
            $('#orderby').append(newOption);
        }
        if (add_rating) {
            newOption = $('<option value="rating">Rating</option>');
            $('#orderby').append(newOption);
        }
        if (add_mostdownloaded) {
            newOption = $('<option value="mostdownloaded">Most Downloaded</option>');
            $('#orderby').append(newOption);
        }
        if (notice_text.length > 0) {
            $(".search_notice_text").css({"display": "inline-block", "visibility": "visible"});
            $('.search_notice_text').html(notice_text);
        } else {
            $(".search_notice_text").css({"display": "none", "visibility": "hidden"});
        }

    });

    // this will add the keyword the user has in the search box
    $(".add_keyword").click(function () {
        var search_query = $('#content_search_term').val();
        $('.user_keywords_list').html('<i class="fa fa-spinner fa-spin"></i>');
        LoadApplyUserKeywords(search_query, 'add');
    });
    // this will toggle the remove keywords option in the content search box, it will also reload the keywords and provide the user the ability to remove a single keyword.
    $(".turn_on_remove_keywords").click(function () {
        var search_query = '';
        var turn_off_delete = $(this).attr('rel');
        var current_action = 'load_delete_actions';
        if (turn_off_delete == 'off') {
            current_action = 'load_keywords';
            $(this).attr('rel', 'on');
            $(this).html('<i class="fa fa-minus-circle"></i> Remove Keywords');
        }
        else {
            $(this).attr('rel', 'off');
            $(this).html('Remove Keywords: On');
        }

        $('.user_keywords_list').html('<i class="fa fa-spinner fa-spin"></i>');
        LoadApplyUserKeywords(search_query, current_action);

    });

    // this function will send the keyword to the php function to delete the keyword
    $("#cu_user_search_keywords").delegate(".delete_content_keyword", "click", function () {
        var search_query = $(this).attr('rel');
        $('.user_keywords_list').html('<i class="fa fa-spinner fa-spin"></i>');
        LoadApplyUserKeywords(search_query, 'delete');
        $('.turn_on_remove_keywords').attr('rel', 'on');
        $('.turn_on_remove_keywords').html('<i class="fa fa-minus-circle"></i> Remove Keywords');
    });

    function LoadApplyUserKeywords(inKeyword, inAction) {
        //ybi_curation_suite_add_user_keyword
        data = {
            action: 'ybi_curation_suite_user_keyword_actions',
            keyword: inKeyword,
            keywordAction: inAction
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (reply_data) {
                // what is sent is the keyword html with action links
                $('.user_keywords_list').html(reply_data.keyword_html);

            }
        });

    }

    // this binds the link_to_load links because they are loaded by ajax
    $("#ybi_curation_suite_listening_links").delegate(".link_to_load", "click", function () {
        var elem = $(this);
        var theURL = elem.attr('name');
        // check to see if this link has a rel attribute, this attribute will be the content_item_id from the listening platform
        var curated_content_item_id = elem.attr('rel');
        if (curated_content_item_id != '')
            $('#curated_content_item_id').val(curated_content_item_id); // set the id to the hidden field

        var after_curation_action = elem.attr('after-curation-action'); // get attr from the link so we know what to do after this action
        // we only set it if it's set, because it's defaulted so we can communicate with the API
        if (after_curation_action != '')
            $('#curated_content_item_id').attr('after-curation-action', after_curation_action); // set the hidden feild so it's saved

        // we also save the platform id because this is a drop down and this has to correlate, there is a slight chance the user could change this after clicking curate
        var platform_id = $('#cu_listening_platform_id').val();
        $('#curated_platform_id').val(platform_id); // set the platform id to the hidden field

        $("#source_url").val(theURL);
        go_top_cs_sidebar();
        loadContent(false);
    });

    $("#ybi_curation_suite_listening_links").delegate(".source_ignore, .pohelp", "mouseenter", function () {
        $(".source_ignore").css({
            "z-index": "1110"
        });
        var this_id = $(this).attr('id');
        $(".pohelp_" + this_id).fadeIn();
        $(".pohelp_" + this_id).css({
            "z-index": "1110",
            "display": "inline-block",
            "overflow": "hidden",
            "position": "absolute"
        });
        var position = $(this).position();
        //  $(".cu_platform_action").append(": left=" + position.left + ", top=" + position.top );

        $(".pohelp_" + this_id).position({
            my: "left+5 top",
            at: "left bottom",
            of: $(this), // or $("#otherdiv)
            collision: "fit"
        });
    });
    $("#ybi_curation_suite_listening_links").delegate(".pohelp", "mouseleave", function () {
        var this_id = $(this).attr('id');
        $(".pohelp").fadeOut();
        $(".source_ignore").css({"z-index": "1095"});
    });

    function change_content_total(action_type, amount) {
        var cur_total = $('#ybi_lp_total').html();
        if (action_type == 'delete') {
            if (cur_total != '' || cur_total > 0)
                $('#ybi_lp_total').html(cur_total - amount);
        } else {
            $('#ybi_lp_total').html(cur_total + amount);
        }
    }

    $("#ybi_curation_suite_listening_links").delegate(".cu_platform_action", "click", function () {

        var elem = $(this);
        var cur_action = elem.attr('cur_action');
        var time_frame = elem.attr('data-time-frame');
        var type = elem.attr('type');
        var parameter_id = elem.attr('parameter_id');
        var platform_id = $('#cu_listening_platform_id').val();
        var cu_current_display_page = $('#cu_current_display_page').val();

        $('.cu_cid_row_' + parameter_id).hide(500);
        $('.cu_cid_row_' + parameter_id).remove();
        if (type == 'ignore-source' && cur_action == 'add')
            $('.source_ignore_' + parameter_id).html('<i class="fa fa-spinner fa-spin"></i>');
        else {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            if (cu_current_display_page == 'listening-page') {
                //if(platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item' && platform_response.cur_action != 'add') )
                if (type == 'ignore-content-item' || type == 'ignore-source' || (type == 'save-content-item')) {
                    $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                    $('#ybi_curation_suite_listening_links').masonry('reload');
                }
            }


        }
        data = {
            action: 'ybi_curation_suite_platform_action',
            platform_id: platform_id,
            cur_action: cur_action,
            type: type,
            parameter_id: parameter_id,
            time_frame: time_frame,
            cu_current_display_page: cu_current_display_page
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {

                    if (platform_response.type == 'ignore-content-item') {
                        $('.' + platform_response.hide_element).hide(500);
                        $('.' + platform_response.hide_element).remove();
                        change_content_total('delete', 1);
                    }
                    if (platform_response.type == 'ignore-source') {
                        var numItems = $('.' + platform_response.hide_element).length;
                        change_content_total('delete', numItems);
                        $('.' + platform_response.hide_element).hide(500);
                        $('.' + platform_response.hide_element).remove();
                    }
                    if (platform_response.type == 'ignore-keyword')
                        $('.' + platform_response.hide_element).hide(600);
                    if (platform_response.type == 'save-content-item') {
                        if (platform_response.cur_action == 'add') {
                            showNoticeMessage('Content Saved');
                            $('.cu_cid_row_' + platform_response.passed_parameter_id).hide(500);
                            $('.cu_cid_row_' + platform_response.passed_parameter_id).remove();
                            change_content_total('delete', 1);
                            //alert(platform_response.cu_current_display_page);
                            //$('.' + platform_response.hide_element).html('<i class="fa fa-bookmark"></i>'); // change to closed bookmark
                            //$('.' + platform_response.hide_element).css({'color':'blue'}); // update color
                            //alert('add action'+platform_response.passed_parameter_id);

                        }
                        else {
                            //alert('else action'+platform_response.passed_parameter_id);
                            $('.' + platform_response.hide_element).hide(500);
                            $('.' + platform_response.hide_element).remove();
                            $('.cu_cid_row_' + platform_response.passed_parameter_id).addClass('available'); // it's no longer available for bulk actoins
                        }
                    }

                    if (platform_response.cu_current_display_page == 'listening-page') {
                        //if(platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item' && platform_response.cur_action != 'add') )
                        if (platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item')) {
                            $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                            $('#ybi_curation_suite_listening_links').masonry('reload');
                        }
                    }
                }
            }
        });
    });

    function doPlatformAction(in_cur_action, in_type, in_parameter_id, in_curated_url, in_after_curation_action, inRefresh) {
        var platform_id = $('#curated_platform_id').val();
        data = {
            action: 'ybi_curation_suite_platform_action',
            platform_id: platform_id,
            cur_action: in_cur_action,
            type: in_type,
            parameter_id: in_parameter_id,
            curated_url: in_curated_url,
            after_curation_action: in_after_curation_action
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                // for debugging
                //alert(platform_response.url)
                $('.cu_cid_row_' + platform_response.passed_parameter_id).removeClass('available'); // it's no longer available for bulk actoins
                if (inRefresh) {
                    $('.' + platform_response.hide_element).hide(500);
                    $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                    $('#ybi_curation_suite_listening_links').masonry('reload');
                }

            }
        });

    }


    $(".cu_le_topic_sites_load").click(function () {
        $('#cs_le_topic_sites_list').html(spinner_full);
        var platform_id = $('#cu_listening_platform_id').val();
        var topic_id = $('#cu_le_website_topic_id').val();
        var sort = $('#cu_le_discover_sort').val();
        data = {
            action: 'cs_le_load_topic_sites',
            platform_id: platform_id,
            topic_id: topic_id,
            sort: sort
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                $('#cs_le_topic_sites_list').html(platform_response.html);
            }
        });
    });

    $(".cu_listening_load").click(function () {
        var cu_current_display_page = $('#cu_current_display_page').val();
        var cu_platform_sources = $('#cu_platform_sources').val();
        closeActionPopups();

        if (cu_platform_sources == 'platform_control') {
            loadPlatformControl(0);
        }
        else {
            if (cu_current_display_page == 'listening-page')
                loadListeningPageContent(0);
            else
                loadListeningContent(0);
        }
    });

    $("#ybi_curation_suite_listening_links").delegate(".move_page_data", "click", function () {
        var start_point = $(this).attr('rel');
        //alert(start_point);
        loadPlatformControl(start_point);
        refresh_display();
    });
    function loadPlatformControl(inStart) {
        $('#ybi_curation_suite_listening_links').html('');
        var platform_id = $('#cu_listening_platform_id').val();
        var sub_platform_control_item = $('#sub_platform_control_item').val();
        var cu_date_sort = $('#cu_date_sort').val();
        var cu_current_display_page = $('#cu_current_display_page').val();
        var current_page = $('#cu_current_page').val();
        var action = 'ybi_curation_suite_platform_control_load';
        if (sub_platform_control_item == 'curated_content' || sub_platform_control_item == 'shared_content' || sub_platform_control_item == 'ignored_content')
            action = 'ybi_curation_suite_platform_history_content_load';
        else if (sub_platform_control_item == 'platform_setup')
            action = 'cs_le_load_platform_setup';

        var friendly_loading_item = $("#sub_platform_control_item option:selected").text();  // get the text of the specific thing we are loading
        cs_loading_show_text('show', ['.rcp-ajax'], 'Loading ' + friendly_loading_item);
        $(".wrap").scrollTop(100);
        data = {
            action: action,
            platform_id: platform_id,
            sub_platform_control_item: sub_platform_control_item,
            cu_date_sort: cu_date_sort,
            cu_current_display_page: cu_current_display_page,
            current_page: current_page,
            start: inStart
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //alert('loaded');
                $('.rcp-ajax').hide();
                $('#ybi_curation_suite_listening_links').html(search_response.results);
                $("#platform_setup_results").tabs();
                adjust_main_container_height('#platform_setup_results');
                refresh_display();
            }
        });
    }

    // this is the function for the load in the post page
    function loadListeningContent(inStart) {
        //$('.rcp-ajax').show();
        cs_loading_show_text('show', ['.rcp-ajax'], 'Loading');
        $('#ybi_curation_suite_listening_links').html('');
        var platform_id = $('#cu_listening_platform_id').val();
        var topic_id = $('#cu_listening_topic_id').val();
        var time_frame = $('#cu_time_frame').val();
        var social_sort = $('#cu_social_sort').val();
        var platform_sources = $('#cu_platform_sources').val();
        var load_direct_share = $('#load_direct_share_scrape').is(':checked');
        var current_page = $('#cu_current_page').val();
        var cu_current_display_page = $('#cu_current_display_page').val();
        var ybi_cs_hide_quick_add = $('#ybi_cs_hide_quick_add').is(':checked');
        var cu_date_sort = $('#cu_date_sort').val();
        var load_video_player = $('#load_video_player').is(':checked');
        var show_articles = $('#show_article_checkbox').is(':checked');
        var show_videos = $('#show_video_checkbox').is(':checked');
        var video_sort = $('#cu_video_sort').val();
        var le_strict_date_limit = $('#le_strict_date_limit').is(':checked');
        if (!show_articles && !show_videos) {
            alert('At least one search type is required (article or video)');
            return;
        }
        data = {
            action: 'ybi_curation_suite_get_listening_content',
            platform_id: platform_id,
            topic_id: topic_id,
            time_frame: time_frame,
            social_sort: social_sort,
            platform_sources: platform_sources,
            load_direct_share: load_direct_share,
            start: inStart,
            cu_current_display_page: cu_current_display_page,
            ybi_cs_hide_quick_add: ybi_cs_hide_quick_add,
            cu_date_sort: cu_date_sort,
            video_sort: video_sort,
            load_video_player: load_video_player,
            show_articles: show_articles,
            show_videos: show_videos,
            le_strict_date_limit: le_strict_date_limit
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //alert('loaded');
                //$('.rcp-ajax').hide();
                cs_loading_show_text('hide', ['.rcp-ajax'], '');
                $('#ybi_curation_suite_listening_links').html(search_response.results);
            }
        });
    }

    // this is when we move pages
    $("#ybi_curation_suite_listening_links").delegate(".move_page", "click", function () {
        var start_point = $(this).attr('rel');
        //alert(start_point);
        loadListeningPageContent(start_point);
    });

    $(".cu_listening_page_load").click(function () {
        loadListeningPageContent(0);
    });

    function setListningEngineStatus()
    {
        var api_base_url = yb_cu_post_vars.api_base_url;
        if(api_base_url=='https://dev.curationwp.com/api/') {
            $('#cs_le_health_notice').html('DEV <i class="fa fa-circle cs_bad" aria-hidden="true"></i>');
        }
        if(api_base_url=='https://curationwp.com/api/') {
            $('#cs_le_health_notice').html('Live <i class="fa fa-circle cs_good" aria-hidden="true"></i>');
        }
        if(api_base_url=='http://localhost:8080/listening-platform/api/') {
            $('#cs_le_health_notice').html('Local <i class="fa fa-circle cs_bad" aria-hidden="true"></i>');
        }
    }

    function loadListeningPageContent(inStart) {
        cs_loading_show_text('show', ['.rcp-ajax'], 'Loading');
        $('#ybi_curation_suite_listening_links').html('');
        var platform_id = $('#cu_listening_platform_id').val();
        $('#curated_platform_id').val(platform_id); // we set the hidden value so we can use, in case the user changes the drop down but doesn't search, we might need to use this platform_id for actions
        var topic_id = $('#cu_listening_topic_id').val();
        var time_frame = $('#cu_time_frame').val();
        var social_sort = $('#cu_social_sort').val();
        var platform_sources = $('#cu_platform_sources').val();
        var load_direct_share = $('#load_direct_share_scrape').is(':checked');
        var load_video_player = $('#load_video_player').is(':checked');
        var show_articles = $('#show_article_checkbox').is(':checked');
        var show_videos = $('#show_video_checkbox').is(':checked');
        var video_sort = $('#cu_video_sort').val();
        var current_page = $('#cu_current_page').val();
        var cu_current_display_page = $('#cu_current_display_page').val();
        var cu_platform_display_parameters = $('#cu_platform_display_parameters').val();
        var cu_date_sort = $('#cu_date_sort').val();
        var le_strict_date_limit = $('#le_strict_date_limit').is(':checked');
        var action = '';
        if (platform_sources == 'saved_content_items')
            action = 'ybi_curation_suite_get_listening_saved_content_for_page';
        else if (platform_sources == 'platform_display')
            action = 'ybi_curation_suite_get_listening_platform_display_content_for_page';
        else
            action = 'ybi_curation_suite_get_listening_content_reading_page';

        data = {
            action: action,
            platform_id: platform_id,
            topic_id: topic_id,
            time_frame: time_frame,
            social_sort: social_sort,
            platform_sources: platform_sources,
            load_direct_share: load_direct_share,
            cu_platform_display_parameters: cu_platform_display_parameters,
            start: inStart,
            cu_current_display_page: cu_current_display_page,
            cu_date_sort: cu_date_sort,
            video_sort: video_sort,
            load_video_player: load_video_player,
            show_articles: show_articles,
            show_videos: show_videos,
            le_strict_date_limit: le_strict_date_limit
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //alert('loaded');
                //$('.rcp-ajax').hide();
                cs_loading_show_text('hide', ['.rcp-ajax'], '');
                $('#ybi_curation_suite_listening_links').html(search_response.results);
                refresh_display();
            }
        });
    }


    function process_control_change() {
        var ybi_cs_search_on_change = $('#ybi_cs_search_on_change').is(':checked');
        var cu_platform_sources = $('#cu_platform_sources').val();
        var cu_current_display_page = $('#cu_current_display_page').val();

        if (ybi_cs_search_on_change) {
            if (cu_platform_sources == 'platform_control') {
                loadPlatformControl(0);
            }
            else {
                if (cu_current_display_page == 'listening-page')
                    loadListeningPageContent(0);
                else
                    loadListeningContent(0);
            }
        }
    }

    $(".auto_search_on_change").change(function () {
        process_control_change();
    });

    function contentItemRemoveRefreshDisplay(content_item_id)
    {
        $('.cu_cid_row_' + content_item_id).hide(500);
        $('.cu_cid_row_' + content_item_id).remove();
        refresh_display();
    }

    function refresh_display() {
        $('#ybi_curation_suite_listening_links').masonry({columnWidth: 350});
        var $container = $('#ybi_curation_suite_listening_links');
        $container.imagesLoaded(function () {
            $container.masonry({
                itemsSelector: '.item_thumb',
                isFitWidth: true
            }).resize();
        });
        $('#ybi_curation_suite_listening_links').masonry('reloadItems');
        $('#ybi_curation_suite_listening_links').masonry('reload');
        adjust_main_container_height('#ybi_curation_suite_listening_links');
        setListningEngineStatus();
    }

    $("#cu_listening_platform_id").change(function () {
        // get the type of search
        var platform_id = $(this).val();

        $('#cu_listening_topic_id').empty(); //remove all child nodes for the topic drop down
        $('#cu_le_website_topic_id').empty(); //remove all child nodes for the topic sites discovered drop down
        // add All option
        var newOption = 'All';
        newOption = $('<option value="all">All</option>');
        $('#cu_listening_topic_id').append(newOption);

        // get all the hidden input boxes by class id (platform id is appeneded to class in listening-meta.php)
        $(".platform_topics_" + platform_id).each(function () {
            var topic_key = $(this).attr('cu_topic_key');
            // we have two new options below because for some reason it doesn't work when you use the same newOption in both dropdowns
            newOption = $('<option value="' + topic_key + '">' + $(this).val() + '</option>');
            $('#cu_le_website_topic_id').append(newOption);
            newOption = $('<option value="' + topic_key + '">' + $(this).val() + '</option>');
            $('#cu_listening_topic_id').append(newOption);
        });

        $('#cu_time_frame').empty(); //remove all child nodes for the timeframe drop down
        var hours_arr = [6, 12, 24, 48];
        $.each(hours_arr, function (i, val) {
            newOption = $('<option value="' + val + '-HOURS">Last ' + val + ' Hours</option>');
            $('#cu_time_frame').append(newOption);
        });
        var platform_max_timeframe = $(".platform_" + platform_id + "_max_timeframe_value").val();

        if (platform_max_timeframe == 0)
            platform_max_timeframe = 7;

        var days_arr = [3, 5, 7, 14, 30, 60, 90];
        $.each(days_arr, function (i, val) {
            if (val <= platform_max_timeframe) {
                newOption = $('<option value="' + val + '-DAYS">Last ' + val + ' Days</option>');
                $('#cu_time_frame').append(newOption);
            }
        });
        cs_le_load_platform_keywords(platform_id, false);
        cs_load_local_platform_websites(platform_id, false);
        cs_load_local_platform_user_search_terms(platform_id);
    });


    $("#cu_platform_sources").change(function () {
        // get the type of search
        var source_id = $(this).val();
        var current_page = $('#cu_current_display_page').val();
        var load_direct_share = $('#ybi_cs_hide_quick_add').is(':checked');
        if (source_id == 'saved_content_items') {
            //alert(load_direct_share);
            $('.sub_search_parameters').hide();
            $('#platform_diplay_parameters').hide();
            $('#sub_platform_control_parameters').hide();
            $('#date_sort_parameters').show();
            if (current_page != 'listening-page') {
                if (!load_direct_share)
                    $('.ybi_cs_hide_quick_add_block').show();

            }
            if (load_direct_share)
                $('.ybi_cs_hide_quick_add_block').hide();
        }
        else if (source_id == 'platform_display') {
            $('.sub_search_parameters').hide();
            $('#sub_platform_control_parameters').hide();
            $('#platform_diplay_parameters').show();

            if (load_direct_share)
                $('.ybi_cs_hide_quick_add_block').hide();
        }
        else if (source_id == 'platform_control') {
            $('.sub_search_parameters').hide();
            $('#platform_diplay_parameters').hide();
            $('#sub_platform_control_parameters').show();


            var sub_platform_control_item = $('#sub_platform_control_item').val();
            if (sub_platform_control_item == 'platform_setup')
                $('#date_sort_parameters').hide();
            else
                $('#date_sort_parameters').show();

            $('.ybi_cs_hide_quick_add_block').hide();

            //sub_platform_control
        }
        else {
            $('.sub_search_parameters').show();
            $('#platform_diplay_parameters').hide();
            $('#sub_platform_control_parameters').hide();
            $('#date_sort_parameters').hide();
            if (load_direct_share)
                $('.ybi_cs_hide_quick_add_block').hide();
        }
    });

    $("#sub_platform_control_item").change(function () {

        if ($(this).val() == 'platform_setup')
            $('#date_sort_parameters').hide();
        else
            $('#date_sort_parameters').show();
    });

    $(".cu_api_key_enter").click(function () {
        $('.api_message').html(spinner).css("font-size", "16px");
        $('#settings_change_message').html('<i class="fa fa-spinner fa-spin"></i>');
        var api_key = $('#cu_api_key').val();
        var which_button = $(this).attr('name'); // this tracks which ENTER API button the user is using, other wise we grab the wrong API
        if (which_button == 'cu_api_key_settings')
            api_key = $('#cu_api_key_settings').val(); // get the settings api

        data = {
            action: 'set_curation_suite_listeing_api',
            api_key: api_key,
            which_button: which_button
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (search_response) {
                //$('#cu_api_key_entry').append('<br>' + search_response.url);

                $('.api_message').html(search_response.message).addClass('cs_bad');
                $('#settings_change_message').html(search_response.message);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".quick_add_to_post_box", "click", function () {

        $('#content').click();
        $('#content-tmce').click();

        var overall_text = '';
        var elem = $(this);
        //elem.html('<i class="fa fa-spinner fa-spin"></i>');

        var theImageAlign = elem.attr('rel'); // this the image alignment
        var content_item_id = elem.attr('ci'); // this is the text we add

        $('.ci_msg' + content_item_id).html('<i class="fa fa-spinner fa-spin"></i>');

        var theThumbnail = $('.thumb_lp' + content_item_id).attr('src');
        var theLink = $('.link_lp' + content_item_id).attr('href');
        var theCuratedHeadline = $('.link_lp' + content_item_id).html();
        var textWithBreaks = $('.snippet_lp' + content_item_id).html();
        var theLinkText = $('#cu_quick_link_text').val();
        var ignoreImage = $('#ybi_cs_no_image_quick_add').is(':checked');
        var isBlockQuoteOn = $('#cu_quick_blockquote_switch').is(':checked');
        var removeFromSaved = $('#ybi_cs_remove_save_quick_add').is(':checked');
        var link_image = $('#cs_link_image_quick_add').is(':checked');
        var attribution_link_location = $("#quick_attribution_link_location").val();
        var theThumbnailSize = 250;
        var thumbnailHTML = '';
        // this !! checks if it's not defined or means not undefined
        if (!!theThumbnail) {
            if (typeof theImageAlign === "undefined")
                theImageAlign = 'alignleft';
            if (theImageAlign == '')
                theImageAlign = 'alignleft';

            if (theThumbnailSize == '')
                theThumbnailSize = 200;

            if (!ignoreImage && theThumbnail != '') {
                thumbnailHTML = '<img src="' + theThumbnail + '" width="' + theThumbnailSize + '" class="' + theImageAlign + '" alt="' + theLink + '" />';
            }
        }
        var isIframe = false;
        var isNoFollow = false;
        isNoFollow = yb_cu_post_vars.curation_suite_no_follow;
        //isIframe = stageValue.search("<iframe ") >= 0;
        var isAddWithHealine = $('#cu_quick_headline_switch').is(':checked'); //(theTypeOfAdd == 'add_headline' || theTypeOfAdd == 'add_headline_with_blockquote' );
        var isHeadlineLink = (attribution_link_location == 'link_headline');

        var linkWrap = '';
        if (isIframe)
            linkWrap = 'p';

        //var theCuratedHeadline = theLinkText;
        if (theLinkText == '')
            theLinkText = theCuratedHeadline;

        theLinkHTML = '';
        if (theLinkText != '') {
            if (linkWrap != '')
                theLinkHTML = '<' + linkWrap + '>';
            //textWithBreaks = textWithBreaks + ' <a href="' + theLink + '" target="_blank" rel="nofollow">' + theLinkText + '</a>';

            theLinkHTML += '<a href="' + theLink + '" target="_blank" class="cs_link"';

            if (isNoFollow)
                theLinkHTML += 'rel="nofollow"';

            if (link_image) {
                thumbnailHTML = theLinkHTML + '>' + thumbnailHTML + '</a>';
            }

            theLinkHTML += '>' + theLinkText + '</a>';

            if (linkWrap != '')
                theLinkHTML += '</' + linkWrap + '>';
        }

        // this is passed via localize, is what the headline should be wrapped in
        var headline_wrap = yb_cu_post_vars.headline_wrap;
        var theHeadlineHTML = '';
        if (isAddWithHealine || isHeadlineLink) {
            if (isHeadlineLink) {
                theHeadlineHTML = '<' + headline_wrap + '>' + theLinkHTML + '</' + headline_wrap + '>';
            }
            else
                theHeadlineHTML = '<' + headline_wrap + '>' + theCuratedHeadline + '</' + headline_wrap + '>';
        }

        if (attribution_link_location == 'link_above') // if the link is above we don't add it to the overall text, that's added seperately below
            textWithBreaks = '<p>' + theLinkHTML + '</p><blockquote>' + theHeadlineHTML + thumbnailHTML + ' ' + textWithBreaks + '</blockquote>';
        else {

            if (attribution_link_location == 'link_before')
                textWithBreaks = theHeadlineHTML + thumbnailHTML + theLinkHTML + ' ' + textWithBreaks;
            else {
                if (attribution_link_location == 'link_headline') // if the link is the headline then we create the text to be added
                    textWithBreaks = theHeadlineHTML + thumbnailHTML + textWithBreaks;
                else  // here the only option left is link after text
                    textWithBreaks = theHeadlineHTML + thumbnailHTML + textWithBreaks + ' ' + theLinkHTML;
            }
            if (isBlockQuoteOn)
                textWithBreaks = '<blockquote>' + textWithBreaks + '</blockquote>';
        }

        textWithBreaks = textWithBreaks + '<p></p>';
        tinymce.activeEditor.execCommand('mceInsertContent', false, textWithBreaks);

        var title = $('#title').val();
        if (!title) {
            add_text_to_title(theCuratedHeadline);
        }

        var after_curation_action = elem.attr('after-curation-action'); // this will be curate
        if (after_curation_action == '')
            after_curation_action = 'curate';

        // we also save the platform id because this is a drop down and this has to correlate, there is a slight chance the user could change this after clicking curate
        var platform_id = $('#cu_listening_platform_id').val();
        $('#curated_platform_id').val(platform_id); // set the platform id to the hidden field

        //alert('content_item_id' + content_item_id + 'theLink:' +  theLink+'after_curation_action'+after_curation_action);
        //alert('after_curation_action:'+after_curation_action + ' - ' + 'after_curation_action:'+after_curation_action);
        doPlatformAction('add', 'curated-content-item', content_item_id, theLink, after_curation_action, false);

        if (removeFromSaved)
            doPlatformAction('remove', 'save-content-item', content_item_id, theLink, after_curation_action, false);

        do_save_curated_link_to_meta(theLink, $("#post_ID").val());

        $('.ci_msg' + content_item_id).addClass('cqs_green');
        $('.ci_msg' + content_item_id).html('Content Added');

    });


    $(".cs_save_setting").change(function () {
        //$('#settings_change_message').html('<i class="fa fa-spinner fa-spin"></i>');
        var elem = $(this);
        var current_value = elem.is(':checked');
        var current_setting = elem.attr('rel');
        var selected_view = elem.attr('selected_view');
        //alert(current_value + current_setting);
        data = {
            action: 'ybi_curation_suite_settings_change',
            current_setting: current_setting,
            current_value: current_value,
            selected_view: selected_view
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                //$('#settings_change_message').html('Setting has been updated.');
                //alert(platform_response.current_value + platform_response.selected_view);
                if (platform_response.current_value && platform_response.selected_view == 'hide')
                    $('.' + platform_response.current_setting + '_block').css({"display": "none"});
                else {
                    $('.' + platform_response.current_setting + '_block').removeClass('ybi_hide');
                    $('.' + platform_response.current_setting + '_block').css({"display": "inline-block"});
                }
            }
        });
    });

    $(".wp-admin").delegate(".cs_meta_value_save", "click", function () {
        var elem = $(this);
        cs_save_meta_setting(elem);
    });

    $(".wp-admin").delegate("#cs_sub_headline_input", "change", function(){
        var elem = $(this);
        cs_save_meta_setting(elem);
    });
    function cs_save_meta_setting(elem)
    {
        var current_value;
        var current_value_type = 'text';
        if(elem.is(':checkbox')) {
            current_value = elem.is(':checked');
            current_value_type = 'checkbox';
        } else {
            current_value = elem.val();
        }

        var current_setting = elem.attr('rel');
        var post_id = $("#post_ID").val();
        alert(current_value + ' - ' + current_setting + ' postid: ' + post_id + ' is checkbox: ' + elem.is(':checkbox'));
        data = {
            action: 'ybi_curation_suite_meta_setting_change',
            current_setting: current_setting,
            current_value: current_value,
            current_value_type: current_value_type,
            post_id: post_id,
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (response) {

            }
        });

    }

    function ybi_cs_create_draft(elem, content_item_id, headline, image, attribution_link, cited_text, platform_id, category_id) {
        //elem.html('<i class="fa fa-spinner fa-spin"></i>');
        $('.cu_draft_icon_' + content_item_id).html(spinner);
        $('.cs_draft_cats_title').html(spinner);
        var content_type = 'article';
        if ($('.cu_cid_row_' + content_item_id).hasClass('cs_video')) {
            content_type = 'video';
        }
        //console.log(content_type);
        var feature_image = $('#ybi_cs_draft_image_feature').is(':checked');
        var wrap_blockquote_off = $('#ybi_cs_draft_blockquote_off_feature').is(':checked');
        var quick_post_publish_type = $('#ybi_cs_quick_post_publish_type').val();
        var ybi_cs_click_draft_video_actions = $('#ybi_cs_click_draft_video_actions').val();
        var ybi_cs_draft_link_text = $('#ybi_cs_draft_link_text').val();

        data = {
            action: 'ybi_cs_add_draft_post',
            platform_id: platform_id,
            content_item_id: content_item_id,
            headline: headline,
            image: image,
            attribution_link: attribution_link,
            cited_text: cited_text,
            category_id: category_id,
            feature_image: feature_image,
            wrap_blockquote_off: wrap_blockquote_off,
            ybi_cs_draft_link_text: ybi_cs_draft_link_text,
            quick_post_publish_type: quick_post_publish_type,
            ybi_cs_click_draft_video_actions: ybi_cs_click_draft_video_actions,
            content_type: content_type
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                $(".po_cats").fadeOut();
                $(".cu_create_draft").css({"z-index": "1095"});
                $('.' + platform_response.hide_element).hide(500);
                $('.' + platform_response.hide_element).remove();
                $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                $('#ybi_curation_suite_listening_links').masonry('reload');
                $('.cs_draft_cats_title').html('Choose Draft Category:');
                showNoticeMessage('Post created.');
                change_content_total('delete', 1);
            }
        });
    }


    $("#ybi_curation_suite_listening_links").delegate(".cu_cat_draft_item", "click", function () {
        var elem = $(this);
        var content_item_id = elem.attr('parameter_id');
        var headline = $('.cu_cid_row_' + content_item_id + ' h4 a').html();
        var image = $('.cu_cid_row_' + content_item_id + ' .thumb img').attr('src');
        var attribution_link = $('.cu_cid_row_' + content_item_id + ' h4 a').attr('href');
        var cited_text = $('.cu_cid_row_' + content_item_id + ' .snippet').html();
        var platform_id = $('#cu_listening_platform_id').val();
        var category_id = 0;
        ybi_cs_create_draft(elem, content_item_id, headline, image, attribution_link, cited_text, platform_id, category_id)

    });

    $(".po_cats").delegate(".cu_cat_draft_item", "click", function () {
        var elem = $(this);
        var content_item_id = elem.attr('parameter_id');
        var headline = $('.cu_cid_row_' + content_item_id + ' h4 a').html();
        var image = $('.cu_cid_row_' + content_item_id + ' .thumb img').attr('src');
        var attribution_link = $('.cu_cid_row_' + content_item_id + ' h4 a').attr('href');
        var cited_text = $('.cu_cid_row_' + content_item_id + ' .snippet').html();
        var platform_id = $('#cu_listening_platform_id').val();
        var category_id = elem.attr('category_id');
        ybi_cs_create_draft(elem, content_item_id, headline, image, attribution_link, cited_text, platform_id, category_id)

    });


    // notice that the action type is defaulted by a drop down in the setting screen in the settings on the reading page.
    // will either be click or mouseenter
    $("#ybi_curation_suite_listening_links").delegate(".cu_create_draft, .po_cats", $('#ybi_cs_click_draft_action_type').val(), function () {
        $(".cu_create_draft").css({
            "z-index": "1110"
        });
        var this_id = $(this).attr('id');
        $(".po_cats").fadeIn();
        $(".po_cats").css({
            "z-index": "1110",
            "display": "inline-block",
            "overflow": "hidden",
            "position": "absolute"
        });

        var position = $(this).position();
        //  $(".cu_platform_action").append(": left=" + position.left + ", top=" + position.top );

        $(".po_cats").position({
            my: "left+5 top",
            at: "left bottom",
            of: $(this), // or $("#otherdiv)
            collision: "fit"
        });

        var elem = $(this);
        var content_item_id = elem.attr('parameter_id');
        $('.cs_le_blog_cat_list').attr('parameter_id', content_item_id);

    });

    function closeActionPopups() {
        $(".po_cats").fadeOut();
        $(".cu_create_draft").css({"z-index": "1095"});
        $(".pohelp").fadeOut();
        $(".source_ignore").css({"z-index": "1095"});
    }

    // this is a helper function that closes all popup actions
    $("#listening-platform-display-page").mouseenter(function () {
        closeActionPopups();
    });

    $(".po_cats").mouseleave(function () {
        $(".po_cats").fadeOut();
        $(".cu_create_draft").css({"z-index": "1095"});
    });

    // this ignores all content
    $(".ignore_all_content").click(function () {

        var platform_id = $('#cu_listening_platform_id').val();
        var comma_string = '';
        // if any content shouldn't be ignored the 'available' class will be removed by that action
        $('.available').each(function (index, data) {
            if (comma_string != '')
                comma_string += ',';
            comma_string += $(this).attr('data-content-item-id');
        });
        $('.ignore_all_content').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.close').html('<i class="fa fa-spinner fa-spin"></i>');
        closeActionPopups();

        data = {
            action: 'ybi_cs_ignore_all_content',
            platform_id: platform_id,
            parameters: comma_string
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                $('.cu_listening_load').trigger('click');
                $('.ignore_all_content').html('<i class="fa fa-minus-circle"></i> Ignore All');
                go_to_top();
                refresh_display();
            }
        });
    });

    // this is functionality for the agency/site network publishing tool.
    $("#child_demand_searches").change(function () {

        $('.user_keywords_list').html('<i class="fa fa-spinner fa-spin"></i>');
        var child_site_id = $(this).val();
        if (child_site_id == 'choose') {
            LoadApplyUserKeywords('', 'add');
        }
        else {
            var demand_search_site_search = $('#demand_search_site_' + child_site_id).val();
            var keywords_arr = demand_search_site_search.split(',');
            var return_html = '';
            for (var i = 0; i < keywords_arr.length; i++) {
                if (keywords_arr[i] == '')
                    continue;

                if (i > 0)
                    return_html += ' | ';

                return_html += '<a class="find_content_keyword" href="javascript:;">' + $.trim(keywords_arr[i]) + '</a>';
            }

            if (return_html.length < 1)
                return_html = 'There are no keywords for this site.';

            $('.user_keywords_list').html(return_html);
        }

    });

    function add_image_to_post(img_src)
    {
        textWithBreaks = '<img src="' + img_src + '" alt="' + img_src + '">';
        tinymce.activeEditor.execCommand('mceInsertContent', false, textWithBreaks);
        var upload_image = $('#upload_quick_add_images').is(':checked');

        if (upload_image)
            do_image_upload(img_src, $("#post_ID").val(), false);
    }

    $("#ybi_cu_content_actions_work_meta").delegate(".add_image_to_post", "click", function () {
        var elem = $(this);
        // which is really the number of the paragraph
        var image_number = elem.attr('data-id');
        var img_src = $('.' + image_number).attr('src');
        add_image_to_post(img_src);
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".cs_add_image_option_to_post", "click", function () {
        var elem = $(this);
        // which is really the number of the paragraph
        var image_number = elem.attr('data-cs-image-number');
        var img_src = $('.cs_image_option_' + image_number).attr('href');
        add_image_to_post(img_src);
    });

    function set_image_featured(img_src)
    {
        do_image_upload(img_src, $("#post_ID").val(), true);
    }



    $("#ybi_cu_content_actions_work_meta").delegate(".set_image_featured", "click", function () {
        var elem = $(this);
        elem.html('<i class="fa fa-spinner fa-spin"></i>');
        $("#postimagediv .inside", window.parent.document).html('<i class="fa fa-spinner fa-spin"></i>');
        // which is really the number of the paragraph
        var image_number = elem.attr('data-id');
        var img_src = $('.' + image_number).attr('src');
        set_image_featured(img_src);
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Featured Set</span>'); // hide the indicator and note it was added
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".cs_set_featured_image_option_to_post", "click", function () {
        var elem = $(this);
        elem.html('<i class="fa fa-spinner fa-spin"></i>');

        // which is really the number of the paragraph
        var image_number = elem.attr('data-cs-image-number');
        var img_src = $('.cs_image_option_' + image_number).attr('href');
        set_image_featured(img_src);
        elem.html('<span class="cqs_green"><i class="fa fa-plus"></i> Featured Set</span>'); // hide the indicator and note it was added
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".cs_attribution_only_link", "click", function () {
        var elem = $(this);
        var elem_class = elem.attr('rel');
        $('.attribution_message_' + elem_class).html(spinner);
        var link = elem.attr('data-url');
        var text = $('.' + elem_class).html();
        isNoFollow = yb_cu_post_vars.curation_suite_no_follow;
        var theLinkHTML = '';
        theLinkHTML += '<a href="' + link + '" target="_blank" class="cs_link"';
        if (isNoFollow)
            theLinkHTML += 'rel="nofollow"';

        theLinkHTML += '>' + text + '</a>';
        add_content_to_post_box(theLinkHTML);
        $('.attribution_message_' + elem_class).html('<span class="cqs_green"><i class="fa fa-plus"></i> Link Added</span>');

    });

    // this function will send the image url to the ajax function to upload the chosen image
    // note: if the user has select a screenshot these are always uploaded
    function do_image_upload(theThumbnail, post_ID, set_featured) {
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
            success: function (image_results) {
                // if the image could be or was uploaded then we replace it in the content editor
                if (image_results.meta_html != '') // if the meta html has been set then we add it to the meta box
                    $("#postimagediv .inside", window.parent.document).html(image_results.meta_html);

                if (image_results.status) {
                    var overall_text = "";
                    var original_text = window.parent.tinyMCE.activeEditor.getContent({format: 'raw'});
                    var ed = window.parent.tinyMCE.get('content');
                    var content = ed.getContent();
                    content = content.replace(image_results.og_img_url, image_results.upload_img_url);
                    ed.setContent(content);
                }
                else {
                    // if there was an error we display this error. Porbably should take the actual error from what is returned but this will do for now
                    //$(".content_add_error").css({"display":"block","visibility":"visible"});
                    //$(".content_add_error").html('<i class="fa fa-exclamation-triangle"></i> Due to security reasons the image you selected can\'t be uploaded to your site. This image was not uploaded and if displayed is pointing to the original source file.');
                }
            }
        });

    }

    function cu_le_detail_search(elem) {
        $('.cu_le_detail_search').parent().removeClass('highlight');
        elem.parent().addClass('highlight');
        var search_type = elem.attr('search_type');
        var order_by = elem.attr('order_by');
        var platform_id = $('#cu_listening_platform_id').val();
        var platform_sources = $('#cu_platform_sources').val();
        var ybi_cs_hide_quick_add = $('#ybi_cs_hide_quick_add').is(':checked');
        var cu_current_display_page = $('#cu_current_display_page').val();
        if (search_type == 'user_search_term') {
            if(elem.hasClass('detail_sort_link')) {
                parameter_id = $('#le_content_search_term').val(); // if the user changed the search term then the search will be different, oh well
            } else {
                $('#le_content_search_term').val(elem.text());
            }
        }


        if(search_type=='user_search_term') {
            var parameter_id = $('#le_content_search_term').val();
        } else {
            var parameter_id = elem.attr('parameter_id');
        }

        var le_search_term_element = $('#le_search_term_element').val();
        var le_search_term_content_type = $('#le_search_term_content_type').val();
        //$("#ybi_curation_suite_listening_links").html('<i class="fa fa-spinner fa-spin"></i>');
        var loading_arr = ["#ybi_curation_suite_listening_links"];
        cs_le_loading_show('show', loading_arr);
        //go_to_top();

        //$('body').scrollTo('#detail_search_options');
        if (cu_current_display_page == 'listening-page') {
            $('html, body').animate({
                scrollTop: $("#ybi_curation_suite_listening_links").offset().top
            }, 1000);
        } else {
            $("#ybi_curation_suite_listening_links").animate({scrollTop: 0}, "fast");
        }

        data = {
            action: 'cs_le_detail_search',
            platform_id: platform_id,
            search_type: search_type,
            parameter_id: parameter_id,
            order_by: order_by,
            le_search_term_element: le_search_term_element,
            le_search_term_content_type: le_search_term_content_type,
            platform_sources: platform_sources,
            ybi_cs_hide_quick_add: ybi_cs_hide_quick_add,
            cu_current_display_page: cu_current_display_page
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {
                    $("#ybi_curation_suite_listening_links").html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    refresh_display();
                }
            }
        });
    }

    $("#listening-platform-display-page").delegate(".cu_le_detail_search", "click", function () {
        var elem = $(this);
        cu_le_detail_search(elem);
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".cu_le_detail_search", "click", function () {

        var elem = $(this);
        cu_le_detail_search(elem);
    });

    $('#le_content_search_term').keypress(function (e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            //alert('The enter key was pressed!');
            e.preventDefault();
            $(".cs_le_content_keyword_search").trigger('click');
        }
    });

    $(".cs_le_content_keyword_search").click(function () {
        var elem = $(this);
        var search_type = 'user_search_term';
        var platform_id = $('#cu_listening_platform_id').val();

        var parameter_id = $('#le_content_search_term').val();
        var le_search_term_element = $('#le_search_term_element').val();
        var le_search_term_content_type = $('#le_search_term_content_type').val();
        var order_by = 'most_recent';
        var cu_current_display_page = $('#cu_current_display_page').val();
        var platform_sources = $('#cu_platform_sources').val();
        var ybi_cs_hide_quick_add = $('#ybi_cs_hide_quick_add').is(':checked');
        var loading_arr = ["#ybi_curation_suite_listening_links"];
        cs_loading_show_text('show', loading_arr, ' searching Listening Engine for: <i>' + parameter_id + '</i>');
        data = {
            action: 'cs_le_detail_search',
            platform_id: platform_id,
            search_type: search_type,
            parameter_id: parameter_id,
            order_by: order_by,
            platform_sources: platform_sources,
            le_search_term_element: le_search_term_element,
            le_search_term_content_type: le_search_term_content_type,
            ybi_cs_hide_quick_add: ybi_cs_hide_quick_add,
            cu_current_display_page: cu_current_display_page
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {
                    $("#ybi_curation_suite_listening_links").html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    refresh_display();
                }
            }
        });

    });

    function cs_le_detail_search_save(elem) {
        var search_type = elem.attr('search_type');
        var parameter_id = elem.attr('parameter_id');
        var parameter_text = $('.detail_search_value_text').text();

        var order_by = elem.attr('order_by');
        var platform_id = $('#cu_listening_platform_id').val();
        var cu_current_display_page = $('#cu_current_display_page').val();
        //$("#cs_le_user_saved_"+search_type).html('<i class="fa fa-spinner fa-spin"></i>');
        var loading_arr = ["#cs_le_user_saved_" + search_type];
        cs_le_loading_show('show', loading_arr);

        data = {
            action: 'cs_le_detail_search_save_item',
            platform_id: platform_id,
            search_type: search_type,
            parameter_id: parameter_id,
            parameter_text: parameter_text,
            order_by: order_by,
            cu_current_display_page: cu_current_display_page
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {
                    $("#cs_le_user_saved_" + search_type).html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    showNoticeMessage(platform_response.message);
                } else {
                    $("#cs_le_user_saved_" + search_type).html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    showNoticeMessage(platform_response.message);
                }

            }
        });
    }

    function cs_le_load_platform_keywords(platform_id, force_repull) {
        var cu_current_display_page = $('#cu_current_display_page').val();
        //$("#cs_le_user_saved_keyword").html('<i class="fa fa-spinner fa-spin"></i>');

        var button_html = $('#cs_le_user_saved_keyword .reload_detail_search_item').html();
        $('#cs_le_user_saved_keyword .reload_detail_search_item').html(spinner);
        var loading_arr = ["#cs_le_user_saved_keyword .results"];
        cs_le_loading_show('show', loading_arr);
        data = {
            action: 'cs_load_local_platform_keywords',
            platform_id: platform_id,
            cu_current_display_page: cu_current_display_page,
            force_repull: force_repull
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {

                    $("#cs_le_user_saved_keyword .results").html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    $('#cs_le_user_saved_keyword .reload_detail_search_item').html(button_html);
                    //showNoticeMessage(platform_response.message);
                } else {
                    $("#cs_le_user_saved_keyword .results").html(platform_response.results);
                    $('#cs_le_user_saved_keyword .reload_detail_search_item').html(button_html);
                    //cs_le_user_saved_keyword(platform_response.message);
                }

            }
        });
    }

    function cs_load_local_platform_websites(platform_id, force_repull) {

        var cu_current_display_page = $('#cu_current_display_page').val();
        //$("#cs_le_user_saved_keyword").html('<i class="fa fa-spinner fa-spin"></i>');
        var button_html = $('#cs_le_user_saved_domain_name_id .reload_detail_search_item').html();
        $('#cs_le_user_saved_domain_name_id .reload_detail_search_item').html(spinner);
        var loading_arr = ["#cs_le_user_saved_domain_name_id .results"];
        cs_le_loading_show('show', loading_arr);
        data = {
            action: 'cs_load_local_platform_websites',
            platform_id: platform_id,
            cu_current_display_page: cu_current_display_page,
            force_repull: force_repull
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {
                    $("#cs_le_user_saved_domain_name_id .results").html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    $('#cs_le_user_saved_domain_name_id .reload_detail_search_item').html(button_html);
                    //showNoticeMessage(platform_response.message);
                } else {
                    $("#cs_le_user_saved_domain_name_id .results").html(platform_response.results);
                    $('#cs_le_user_saved_domain_name_id .reload_detail_search_item').html(button_html);
                    //showNoticeMessage(platform_response.message);
                }

            }
        });
    }

    function cs_load_local_platform_user_search_terms(platform_id) {

        var cu_current_display_page = $('#cu_current_display_page').val();
        //$("#cs_le_user_saved_keyword").html('<i class="fa fa-spinner fa-spin"></i>');
        var loading_arr = ["#cs_le_user_saved_user_search_term"];
        cs_le_loading_show('show', loading_arr);
        data = {
            action: 'cs_load_local_platform_user_search_terms',
            platform_id: platform_id,
            cu_current_display_page: cu_current_display_page
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (platform_response.status == 'success') {
                    $("#cs_le_user_saved_user_search_term").html(platform_response.results);
                    cs_le_loading_show('hide', loading_arr);
                    //showNoticeMessage(platform_response.message);
                } else {
                    $("#cs_le_user_saved_user_search_term").html(platform_response.results);
                    //showNoticeMessage(platform_response.message);
                }

            }
        });
    }

    function cs_le_delete_on_demand_items(platform_id, parameter_id) {
        data = {
            action: 'cs_le_detail_search_delete_item',
            platform_id: platform_id,
            parameter_id: parameter_id
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {

            }
        });
        return true;
    }

    $(".reload_detail_search_item").click(function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-search-type');
        $('#cs_le_user_saved_keyword_tab').trigger('click');
        var loading_arr = ["#cs_le_user_saved_" + parameter_id + " .results"];
        cs_le_loading_show('show', loading_arr);
        if (cs_le_delete_on_demand_items(platform_id, parameter_id)) {

            if (parameter_id == 'keyword')
                cs_le_load_platform_keywords(platform_id, true);

            if (parameter_id == 'feed')
                cs_load_local_platform_websites(platform_id, true);
        }
    });

    function cs_le_load_negative_keywords(platform_id) {
        $('#negative_keyword_wrap').html(spinner);
        var data = {
            action: 'cs_le_load_negative_keywords',
            platform_id: platform_id
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                $('#negative_keyword_wrap').html(platform_response.results);
            }
        });
    }


    $("#ybi_curation_suite_listening_links").delegate(".cs_le_negative_keyword_update_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-parameter-id');
        var search_term = $('#negative_keyword_search_term_no_' + parameter_id).html();
        var search_type = $('#negative_keyword_search_type_no_' + parameter_id).html();
        $('#input_negative_keyword_search_term').val(search_term);
        $('#le_negative_keyword_search_type').val(search_type);
        $('#negative_keyword_row_' + parameter_id).addClass('highlight');

        $('.cs_le_negative_keyword_btn').html('Update');
        $('.cs_le_negative_keyword_btn').attr('data-action-type', 'update');

        $('.cs_le_negative_keyword_btn').attr('data-parameter-id', parameter_id);
    });


    $("#ybi_curation_suite_listening_links").delegate(".cs_le_negative_keyword_delete_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-parameter-id');
        var search_term = $('#negative_keyword_search_term_no_' + parameter_id).html();
        var search_type = $('#negative_keyword_search_type_no_' + parameter_id).html();
        var current_action = elem.attr('data-action-type');
        $('#deletion_detail_item').html(search_term);
        $('#deletion_detail_item_type').html('Negative Keyword');
        $('#dialog-confirm').attr('title', 'Delete Negative Keyword?');
        $('#negative_keyword_row_' + parameter_id).addClass('highlight');
        var keyword_search_term = '';

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    elem.html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'cs_le_negative_keyword_action',
                        platform_id: platform_id,
                        search_type: search_type,
                        current_action: current_action,
                        parameter_id: parameter_id,
                        keyword_search_term: keyword_search_term
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            showNoticeMessage(platform_response.message);
                            $('#deletion_detail_item').html('');
                            $('#dialog-confirm').attr('title', '');
                            $('#negative_keyword_row_' + parameter_id).hide(300);
                            //cs_le_load_negative_keywords(platform_id);
                        }
                    });
                },
                Cancel: function () {
                    $(this).dialog("close");
                    $('#negative_keyword_row_' + parameter_id).removeClass('highlight');
                    return;
                }
            }
        });
    });


    $("#ybi_curation_suite_listening_links").delegate(".website_feeds_link", "click", function () {
        var elem = $(this);
        var id = elem.attr('data-id');
        var feed_list = $('.website_feeds_' + id);
        var isVisible = feed_list.is(':visible');
        feed_list.toggle();
    });

    $("#ybi_curation_suite_listening_links").delegate(".cs_le_negative_keyword_btn", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var keyword_search_term = $('#input_negative_keyword_search_term').val();
        var search_type = $('#le_negative_keyword_search_type').val();
        var current_action = elem.attr('data-action-type');
        var parameter_id = elem.attr('data-parameter-id');
        elem.html(spinner);
        var data = {
            action: 'cs_le_negative_keyword_action',
            platform_id: platform_id,
            search_type: search_type,
            current_action: current_action,
            parameter_id: parameter_id,
            keyword_search_term: keyword_search_term
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (platform_response) {
                if (current_action == 'update' || current_action == 'add') {
                    $('#input_negative_keyword_search_term').val('');
                    $('#le_negative_keyword_search_type').val('title');
                    $('.cs_le_negative_keyword_btn').html('Add');
                    $('.cs_le_negative_keyword_btn').attr('data-action-type', 'add');
                    $('.cs_le_negative_keyword_btn').attr('data-parameter-id', 0);
                    showNoticeMessage(platform_response.message);
                }

                cs_le_load_negative_keywords(platform_id);
            }
        });

    });


    $("#listening-platform-display-page").delegate(".cs_le_load_remote_keywords", "click", function () {

    });

    $("#listening-platform-display-page").delegate(".cs_le_detail_search_save", "click", function () {
        var elem = $(this);
        cs_le_detail_search_save(elem);
    });

    $("#ybi_cu_content_actions_work_meta").delegate(".cs_le_detail_search_save", "click", function () {
        var elem = $(this);
        cs_le_detail_search_save(elem);
    });

    function cs_le_refresh_single_topic_keywords(topic_id, platform_id) {
        $('#topic_' + topic_id + '_keywords_results').html(spinner_full);

        data = {
            action: 'cs_le_reload_topic_keywords',
            topic_id: topic_id,
            platform_id: platform_id
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                $('#topic_' + topic_id + '_keywords_results').html(results.html);
            }
        });
    }

    $("#ybi_curation_suite_listening_links").delegate(".cs_le_keyword_update_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-parameter-id');
        var current_action = elem.attr('data-action-type');
        var topic_id = elem.attr('data-topic-id');
        var keyword = $('#topic_' + topic_id + '_keyword_no_' + parameter_id).text();
        var search_term = $('#topic_' + topic_id + '_keyword_search_term_no_' + parameter_id).text();
        var search_type_text = $('#topic_' + topic_id + '_keyword_search_types_no_' + parameter_id).text();
        $('#keyword_add_keyword_' + topic_id).val(keyword);
        $('#search_term_' + topic_id).val(search_term);
        var search_type_arr = search_type_text.split(',');
        $.each(search_type_arr, function (key, value) {
            $('#search_term_' + value.trim() + '_' + topic_id).prop("checked", true);
        });

        $('.le_keyword_row').removeClass('highlight');
        $('#keyword_row_topic_' + topic_id + '_keyword_' + parameter_id).addClass('highlight');
        remove_message_indicator_color('#topic_' + topic_id + '_message');
        $('.cs_le_keyword_btn').html('Update');
        $('.cs_le_keyword_btn').attr('data-action-type', 'update');
        $('.cs_le_keyword_btn').attr('data-parameter-id', parameter_id);
    });


    function remove_message_indicator_color(in_element) {
        $(in_element).removeClass('cs_bad');
        $(in_element).removeClass('cs_bad_msg');
        $(in_element).removeClass('cs_good');
        $(in_element).removeClass('cs_good_msg');
        $(in_element).html('');
    }

    $("#ybi_curation_suite_listening_links").delegate(".cs_le_keyword_btn", "click", function () {
        var topic_id = $(this).attr('data-topic-id');
        var current_action = $(this).attr('data-action-type');
        var keyword_id = $(this).attr('data-parameter-id');
        var keyword = $('#keyword_add_keyword_' + topic_id).val();
        var search_term = $('#search_term_' + topic_id).val();
        var platform_id = $('#cu_listening_platform_id').val();
        var message_elem = '#topic_' + topic_id + '_message';
        var is_article = $('#search_term_article_' + topic_id).is(':checked');
        var is_video = $('#search_term_video_' + topic_id).is(':checked');

        if (!is_article && !is_video) {
            alert('At least one search type is required (article or video)');
            return;
        }
        remove_message_indicator_color(message_elem);
        $('#topic_' + topic_id + '_keywords_results').html(spinner_full);
        $('#topic_' + topic_id + '_message').html('');
        data = {
            action: 'cs_le_api_keyword_action',
            platform_id: platform_id,
            topic_id: topic_id,
            current_action: current_action,
            keyword: keyword,
            search_term: search_term,
            keyword_id: keyword_id,
            is_article: is_article,
            is_video: is_video
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                if (results.status == 'failure')
                    $('#topic_' + topic_id + '_message').addClass('cs_bad_msg');

                if (results.status == 'success')
                    $('#topic_' + topic_id + '_message').addClass('cs_good_msg').delay(5000).fadeOut('slow');

                cs_le_refresh_single_topic_keywords(topic_id, platform_id);
                $('#topic_' + topic_id + '_message').html(results.message);

                if (results.status == 'success') {
                    //$('#return_messages').html(results.message);
                    //displayActionMessage('success',results.message);
                    clear_keyword_add_elements();
                    if (current_action == 'add')
                        update_keywords_total('add', topic_id);

                    cs_le_load_platform_keywords(platform_id, true);

                    $('.le_keyword_row').removeClass('highlight');
                    $('.cs_le_keyword_btn').html('Add');
                    $('.cs_le_keyword_btn').attr('data-action-type', 'add');
                }
                else {
                    $('.loading').html(results.message);
                }
            }
        });

    });


    $("#ybi_curation_suite_listening_links").delegate(".cs_le_keyword_delete_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-parameter-id');
        var current_action = elem.attr('data-action-type');
        var topic_id = elem.attr('data-topic-id');
        var keyword = $('#topic_' + topic_id + '_keyword_no_' + parameter_id).text();

        $('#deletion_detail_item').html(keyword);
        $('#deletion_detail_item_type').html(' keyword');
        $('.ui-dialog-title').html('Delete Keyword?');
        $('#dialog-confirm').attr('title', 'Delete Keyword From Topic?');
        var message_elem = '#topic_' + topic_id + '_message';
        remove_message_indicator_color(message_elem);
        $(message_elem).html('');

        $('#keyword_row_topic_' + topic_id + '_keyword_' + parameter_id).addClass('highlight');
        var keyword_search_term = '';

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    elem.html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'cs_le_keyword_action',
                        platform_id: platform_id,
                        topic_id: topic_id,
                        current_action: current_action,
                        parameter_id: parameter_id
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            showNoticeMessage(platform_response.message);
                            //cs_le_refresh_single_topic_sources(topic_id);
                            $('#deletion_detail_item').html('');
                            $('#dialog-confirm').attr('title', '');
                            update_keywords_total('remove', topic_id);
                            $('#keyword_row_topic_' + topic_id + '_keyword_' + parameter_id).hide(300);
                            // force reload the keywords in the keywords tab
                            cs_le_load_platform_keywords(platform_id, true);
                        }
                    });
                },
                Cancel: function () {
                    $('#keyword_row_topic_' + topic_id + '_keyword_' + parameter_id).removeClass('highlight');
                    $(this).dialog("close");
                    //return;
                }
            }
        });
    });


    function clear_keyword_add_elements() {
        $(".keyword_add_element").val('');

        var html_elements = [];
        $.each(html_elements, function (index, value) {
            $("#" + value).html('');
        });
    }

    function update_keywords_total(type, topic_id) {
        topic_id = topic_id || 0;

        var total_feeds = $('.current_total_keywords').html();
        if (type == 'add') {
            total_feeds++;
        } else {
            total_feeds--;
        }
        $('.current_total_keywords').html(total_feeds);

        if (topic_id > 0) {
            total_feeds = $('.topic_' + topic_id + '_keyword_count').html();
            if (type == 'add') {
                total_feeds++;
            } else {
                total_feeds--;
            }
            $('.topic_' + topic_id + '_keyword_count').html(total_feeds);
        }
    }


    function update_feed_total(type, topic_id) {
        topic_id = topic_id || 0;

        var total_feeds = $('.current_total_feeds').html();
        if (type == 'add') {
            total_feeds++;
        } else {
            total_feeds--;
        }
        $('.current_total_feeds').html(total_feeds);

        if (topic_id > 0) {
            total_feeds = $('.topic_' + topic_id + '_feed_count').html();
            if (type == 'add') {
                total_feeds++;
            } else {
                total_feeds--;
            }
            $('.topic_' + topic_id + '_feed_count').html(total_feeds);
        }
    }


    $("#ybi_curation_suite_listening_links").delegate(".load_url_for_feeds", "click", function () {
        var url = $("#url").val();
        $(this).html(spinner);
        $('#feeds').html(spinner);
        $('#feed_return_message').html('');

        data = {
            action: 'cs_le_get_feed_links_from_url',
            url: url
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                if (results.status == 'success') {
                    $('#feeds').html(results.feeds_html);
                    $('#created_rss_links').html(results.created_rss_links_html);
                    $('#all_links').html(results.all_links_html);
                    $('#feed_title').val(results.returned_title);
                    $('#feed_url').val('');
                    $('.loading').html('');
                    $('#og_curated_headline').val(results.returned_title);
                    $('.domain_name_title').html(results.domain_name);
                    $('#feed_url').val(results.first_feed);
                    $('.load_url_for_feeds').html('Find Feed');
                }
                else {
                    //$('#return_messages').html(results.html);
                    $('.load_url_for_feeds').html('Find Feed');
                }
            }
        });

    });

    $("#ybi_curation_suite_listening_links").delegate(".check_selected_feed_now", "click", function () {
        var url = $('#feed_url').val();
        $('#feed_on_demand_results').html('<i class="fa fa-spinner fa-spin"></i>');
        data = {
            action: 'cs_le_check_feed_url_return_text',
            url: url
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                loading_value_text = results.html;
                $('#feed_on_demand_results').html('<ul>' + results.feed_html + '</ul>');
            }
        });
    });

    $("#ybi_curation_suite_listening_links").delegate(".check_feed_now", "click", function () {
        var elem = $(this);
        var url_class = $(this).attr('rel');
        // since we display the full url we just need to now grab which is the classes text element
        var url = $('.' + url_class).text();
        $('#feed_on_demand_results').html('<i class="fa fa-spinner fa-spin"></i>');
        elem.html(spinner);
        data = {
            action: 'cs_le_check_feed_url_return_text',
            url: url
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                loading_value_text = results.html;
                $('#feed_on_demand_results').html('<ul>' + results.feed_html + '</ul>');
                elem.html('check feed');
            }
        });
    });

    $("#ybi_curation_suite_listening_links").delegate(".add_feed", "click", function () {
        var link = $(this).html();
        $('#feed_url').val(link);
    });

    $("#ybi_curation_suite_listening_links").delegate(".add_feed_source", "click", function () {

        var topic_id = $("#le_topic_for_feed").val();
        var url = $('#url').val();
        var feed_url = $('#feed_url').val();
        var title = $('#feed_title').val();
        var platform_id = $('#cu_listening_platform_id').val();
        if (topic_id == '') {
            alert('You need to select a topic');
            return;
        }
        if (feed_url == '' || url == '') {
            alert('A main site URL and Feed URL is required.');
            return;
        }
        $(this).html(spinner);
        $('.loading').html(spinner);

        data = {
            action: 'cs_le_api_add_new_feed',
            platform_id: platform_id,
            topic_id: topic_id,
            url: url,
            feed_url: feed_url,
            title: title,
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                if (results.status == 'failure')
                    $('#feed_return_message').addClass('cs_bad');

                if (results.status == 'success')
                    $('#feed_return_message').addClass('cs_good');

                $('#feed_return_message').html(results.message);
                cs_le_refresh_single_topic_sources(topic_id);
                if (results.status == 'success') {
                    //$('#return_messages').html(results.message);
                    //displayActionMessage('success',results.message);
                    clear_feed_add_elements();
                    $('.loading').html('');
                    update_feed_total('add', topic_id);

                }
                else {
                    $('.loading').html(results.message);
                }
                $('.add_feed_source').html('Add New Feed');
            }
        });

    });

    $("#ybi_curation_suite_listening_links").delegate(".cs_le_feed_delete_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var parameter_id = elem.attr('data-parameter-id');
        var current_action = elem.attr('data-action-type');
        var topic_id = elem.attr('data-topic-id');
        var feed_title = $('#topic_' + topic_id + '_feed_no_' + parameter_id).text();

        $('#deletion_detail_item').html(feed_title);
        $('#deletion_detail_item_type').html(' feed');
        $('.ui-dialog-title').html('Delete Feed?');
        $('#dialog-confirm').attr('title', 'Delete Feed From Topic?');
        $('#feed_row_topic_' + topic_id + '_feed_' + parameter_id).addClass('highlight');
        var keyword_search_term = '';

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    elem.html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'cs_le_feed_action',
                        platform_id: platform_id,
                        topic_id: topic_id,
                        current_action: current_action,
                        parameter_id: parameter_id
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            showNoticeMessage(platform_response.message);
                            //cs_le_refresh_single_topic_sources(topic_id);
                            $('#deletion_detail_item').html('');
                            $('#dialog-confirm').attr('title', '');
                            update_feed_total('remove', topic_id);
                            $('#feed_row_topic_' + topic_id + '_feed_' + parameter_id).hide(300);
                            //cs_le_load_negative_keywords(platform_id);
                        }
                    });
                },
                Cancel: function () {
                    $(this).dialog("close");
                    $('#feed_row_topic_' + topic_id + '_feed_' + parameter_id).removeClass('highlight');
                    return;
                }
            }
        });

        //cs_le_load_negative_keywords(platform_id);
    });

    function clear_feed_add_elements() {
        $("#url").val('');
        $("#feed_title").val('');
        $("#feed_url").val('');
        var html_elements = ['created_rss_links', 'created_rss_links', 'created_rss_links', 'rss_source_info',
            'feed_on_demand_results', 'feeds', 'all_links', 'created_rss_links'];
        $.each(html_elements, function (index, value) {
            $("#" + value).html('');
        });
    }


    $("#ybi_curation_suite_listening_links").delegate(".clear_feed_elements", "click", function () {
        clear_feed_add_elements()
    });

    function cs_le_refresh_single_topic_sources(topic_id) {
        $('#topic_' + topic_id).html(spinner);
        data = {
            action: 'cs_le_reload_topic_sources',
            topic_id: topic_id
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                $('#topic_' + topic_id).html(results.html);
            }
        });
    }


    $("#ybi_curation_suite_listening_links").delegate(".cs_le_search_master_topics_btn", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var search_term = $('#le_search_master_topic_keyword').val();

        $('#cs_le_master_topic_search_results').html(spinner_full);
        //doLoading($(this));
        elem.html(spinner);
        data = {
            action: 'cs_le_search_master_topics',
            platform_id: platform_id,
            search_term: search_term
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                $('#cs_le_master_topic_search_results').html(results.html);
                elem.html('Find Topic');
                adjust_main_container_height('#platform_setup_results')
            }
        });
    });


    $("#ybi_curation_suite_listening_links").delegate(".edit_topic_name_btn", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var topic_id = elem.attr('data-topic-id');
        var topic_name = $('#topic_name_edit_' + topic_id).val();
        data = {
            action: 'cs_le_update_topic',
            platform_id: platform_id,
            topic_id: topic_id,
            topic_name: topic_name
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {

                if (results.status == 'success') {
                    showNoticeMessage('Topic Updated');
                    reset_topic_name(topic_name, topic_id);
                    $('.topic_name_tab_title_' + topic_id).html(topic_name);

                    $(".platform_topics_" + platform_id + "[cu_topic_key='" + topic_id + "']").val(topic_name);
                    // there are 3 topic dropdowns that need to be modified
                    $("#cu_listening_topic_id option[value='" + topic_id + "']").html(topic_name);
                    $("#cu_le_website_topic_id option[value='" + topic_id + "']").html(topic_name);
                    $("#le_topic_for_feed option[value='" + topic_id + "']").html(topic_name);
                    $('#topic_' + topic_id + '_message').html(results.message).addClass('cs_good_msg');
                    remove_message_indicator_color('#topic_' + topic_id + '_message');
                    $('#topic_' + topic_id + '_message').hide();
                } else {
                    $('#topic_' + topic_id + '_message').show();
                    $('#topic_' + topic_id + '_message').html(results.message).addClass('cs_bad_msg');
                }
            }
        });
    });

    function reset_topic_name(topic_name, topic_id) {
        $('.topic_name_title_' + topic_id).html(topic_name);
        $('#edit_topic_link_' + topic_id).removeClass('button').removeClass('action').removeClass('edit_topic_name_btn').addClass('edit_topic_name_link');
        $('#edit_topic_link_' + topic_id).html('<i class="fa fa-pencil-square-o"></i>edit name');
        $('.cancel_topic_change').hide();
        remove_message_indicator_color('#topic_' + topic_id + '_message');
    }

    $("#ybi_curation_suite_listening_links").delegate(".edit_topic_name_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var topic_id = elem.attr('data-topic-id');

        remove_message_indicator_color('#topic_' + topic_id + '_message');
        var topic_name = $('.topic_name_title_' + topic_id).text();
        $('.topic_name_title_' + topic_id).html('<input type="text" id="topic_name_edit_' + topic_id + '" value="' + topic_name + '">');
        $('#edit_topic_link_' + topic_id).addClass('button').addClass('action').addClass('edit_topic_name_btn').removeClass('edit_topic_name_link');
        $('#edit_topic_link_' + topic_id).parent().append('<a href="javascript:;" class="cancel_topic_change" data-topic-id="' + topic_id + '" data-topic-name="' + topic_name + '">cancel</a>');

        $('#edit_topic_link_' + topic_id).html('Update');
    });


    $("#ybi_curation_suite_listening_links").delegate(".cancel_topic_change", "click", function () {
        var elem = $(this);
        var topic_id = elem.attr('data-topic-id');
        var topic_name = elem.attr('data-topic-name');
        reset_topic_name(topic_name, topic_id);
    });

    function add_new_item_to_topic_dd(text, val) {
        $('#cu_listening_topic_id').append(new Option(text, val));
        $('#cu_le_website_topic_id').append(new Option(text, val));
        $('#le_topic_for_feed').append(new Option(text, val));

    }

    // adding a new blank topic
    $("#ybi_curation_suite_listening_links").delegate(".add_topic_btn", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var topic_name = $('#new_topic_name').val();
        remove_message_indicator_color('#add_new_topic_message');
        $('#add_new_topic_message').html('');
        //$('#deletion_detail_item_type').html(' topic: ' + topic_name);
        $('#dialog-confirm').attr('title', 'Add New Blank Topic?');
        $('#dialog-confirm').html('<p>Add blank topic: <strong>' + topic_name + '</strong></p>');

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    elem.html(spinner);

                    var data = {
                        action: 'cs_le_add_new_blank_topic',
                        platform_id: platform_id,
                        topic_name: topic_name
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            if (platform_response.status == 'failure') {
                                $('#add_new_topic_message').html(platform_response.message);
                                $('#add_new_topic_message').addClass('cs_bad');
                            } else {
                                showNoticeMessage(platform_response.message);
                                add_new_item_to_topic_dd(platform_response.topic_name, platform_response.topic_id);
                                load_platform_control();


                            }

                            $('#dialog-confirm').attr('title', '');
                            elem.html('Add New Blank Topic');

                        }
                    });
                },
                Cancel: function () {
                    $(this).dialog("close");
                    return;
                }
            }
        });
    });


    function update_topic_total(type, topic_id) {
        topic_id = topic_id || 0;
        var total = $('.current_total_topics').html();
        if (type == 'add') {
            total++;
        } else {
            total--;
        }
        $('.current_total_topics').html(total);

    }


    $("#ybi_curation_suite_listening_links").delegate(".delete_topic_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var current_action = elem.attr('data-action-type');
        var topic_id = elem.attr('data-topic-id');
        var topic_name = $('.topic_name_title_' + topic_id).text();
        $('#dialog-confirm').attr('title', 'Delete Topic?');
        $('.ui-dialog-title').html('Delete Topic?');
        $('#dialog-confirm').html('<p>Delete Topic: <strong>' + topic_name + '</strong>? <span class="cs_bad">This cannot be undone.</span></p>');

        var message_elem = '#topic_' + topic_id + '_message';
        remove_message_indicator_color(message_elem);
        $(message_elem).html('');


        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    elem.html(spinner);

                    var data = {
                        action: 'cs_le_delete_topic',
                        platform_id: platform_id,
                        topic_id: topic_id
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            $('#deletion_detail_item').html('');
                            $('#dialog-confirm').attr('title', '');
                            if (platform_response.status == 'success') {
                                showNoticeMessage(platform_response.message);
                                //cs_le_refresh_single_topic_sources(topic_id);
                                update_topic_total('remove', topic_id);
                                $('#topic_' + topic_id).hide(300);
                                $('.topic_tab_' + topic_id).hide(300);
                                $("#le_access_tabs").tabs("option", "active", 0);
                                //load_platform_control();
                                // force reload the keywords in the keywords tab
                            } else {
                                $(message_elem).addClass('cs_bad');
                                $(message_elem).html(platform_response.message);
                                elem.html('<i class="fa fa-minus-circle"></i> delete topic');
                            }
                        }
                    });
                },
                Cancel: function () {
                    $(this).dialog("close");

                    return;
                }
            }
        });
    });


    $("#ybi_curation_suite_listening_links").delegate(".add_master_topic_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var type_of_add = elem.attr('data-action-type'); //full, keywords, feeds
        var topic_id = elem.attr('data-topic-id'); //full, keywords, feeds
        var topic_name = $('#master_topic_search_topic_' + topic_id).text();
        remove_message_indicator_color('#add_new_topic_message');
        $('#add_new_topic_message').html('');
        //$('#deletion_detail_item_type').html(' topic: ' + topic_name);
        $('#dialog-confirm').attr('title', 'Add New Master Topic?');

        var type_of_add_message = '';
        if (type_of_add == 'full') {
            type_of_add_message = ' with both keywords and feeds'
        } else {
            type_of_add_message = ' with just ' + type_of_add;
        }

        $('#dialog-confirm').html('<p>Add Master topic: <strong>' + topic_name + '</strong>' + type_of_add_message + '?</p>');

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {

                    elem.html(spinner);
                    $('#ybi_curation_suite_listening_links').html(spinner_full);
                    go_to_top();
                    $(this).dialog("close");

                    var data = {
                        action: 'cs_le_add_new_master_topic',
                        platform_id: platform_id,
                        type_of_add: type_of_add,
                        topic_id: topic_id
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            if (platform_response.status == 'failure') {
                                $('#add_new_topic_message').html(platform_response.message);
                                $('#add_new_topic_message').addClass('cs_bad');
                            } else {
                                showNoticeMessage(platform_response.message);
                                add_new_item_to_topic_dd(platform_response.topic_name, platform_response.topic_id);
                                load_platform_control();
                            }

                            $('#dialog-confirm').attr('title', '');


                        }
                    });
                },
                Cancel: function () {
                    $(this).dialog("close");
                    return;
                }
            }
        });
    });


    $("#ybi_curation_suite_listening_links").delegate(".edit_platform_name_btn", "click", function () {
        var elem = $(this);
        $('#platform_edit_message').html('');
        remove_message_indicator_color('#platform_edit_message');
        elem.html(spinner);
        var platform_id = $('#cu_listening_platform_id').val();
        var platform_name = $('#platform_name_edit').val();
        data = {
            action: 'cs_le_update_platform',
            platform_id: platform_id,
            platform_name: platform_name
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {

                if (results.status == 'success') {
                    showNoticeMessage('Platform Updated');
                    reset_platform_name(platform_name, platform_id);
                    $("#cu_listening_platform_id option[value='" + platform_id + "']").html(platform_name);
                } else {
                    $('#platform_edit_message').html(results.message).addClass('cs_bad');
                    $('.edit_platform_name_btn').html('Update');

                }

            }
        });
    });

    function reset_platform_name(name, platform_id) {
        $('#platform_edit_message').html('');
        $('#current_platform_name_master').html(name);
        $('.current_platform_name').html(name);
        $('#edit_platform_name').removeClass('button').removeClass('action').removeClass('edit_platform_name_btn').addClass('edit_platform_name_link');
        $('#edit_platform_name').html('<i class="fa fa-pencil-square-o"></i>edit name');
        $('.cancel_platform_name_change').hide();
    }

    $("#ybi_curation_suite_listening_links").delegate(".edit_platform_name_link", "click", function () {
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();

        var platform_name = $('#current_platform_name_master').text();
        $('#current_platform_name_master').html('<input type="text" id="platform_name_edit" value="' + platform_name + '">');
        $('#edit_platform_name').addClass('button').addClass('action').addClass('edit_platform_name_btn').removeClass('edit_platform_name_link');
        $('#edit_platform_name').parent().append('<a href="javascript:;" class="cancel_platform_name_change" data-platform-id="' + platform_id + '" data-platform-name="' + platform_name + '">cancel</a>');

        $('.edit_platform_name_btn').html('Update');
    });


    $("#ybi_curation_suite_listening_links").delegate(".cancel_platform_name_change", "click", function () {
        var elem = $(this);
        var platform_id = elem.attr('data-platform-id');
        var platform_name = elem.attr('data-platform-name');
        reset_platform_name(platform_name, platform_id)
    });


    function load_platform_control() {
        $('#le_main_control_tab').trigger('click');
        $('#cu_platform_sources').val('platform_control');
        $("#le_access_tabs").tabs("option", "active", 0);
        $('#cu_platform_sources').trigger('change');
        $('#sub_platform_control_item').val('platform_setup');
        process_control_change();
    }

    function load_default_content() {
        $('#le_main_control_tab').trigger('click');
        $('#cu_platform_sources').val('all');
        $('#cu_platform_sources').trigger('change');
        process_control_change();
    }

    $("#show_content_shortcut").click(function () {
        var elem = $(this);
        //alert('y');
        load_default_content();
    });
    $("#saved_content_shortcut").click(function () {
        var elem = $(this);
        //alert('y');
        $('#le_main_control_tab').trigger('click');
        $('#cu_platform_sources').val('saved_content_items');
        $('#cu_platform_sources').trigger('change');


        var cu_current_display_page = $('#cu_current_display_page').val();
                if (cu_current_display_page == 'listening-page')
                    loadListeningPageContent(0);
                else
                    loadListeningContent(0);


    });

    // this sets and loads all the fields for loading the platform setup from the shortcut link
    $("#platform_setup_quicklink").click(function () {
        var elem = $(this);
        //alert('y');
        load_platform_control();
    });

    $("#ybi_curation_suite_listening_links").delegate(".platform_setup_quicklink_c", "click", function () {
        var elem = $(this);
        //alert('y');
        load_platform_control();
    });

    $("#ybi_curation_suite_listening_links").delegate(".cs_tutorial_popup", "click", function () {
        $(this).YouTubePopup({
            autoplay: 1, draggable: true, idAttribute: 'id', 'width': 640,
            'height': 360, 'clickOutsideClose': true
        });
    });
    $("#ybi_cu_content_actions_work_meta").delegate(".cs_tutorial_popup", "click", function () {
        $(".cs_tutorial_popup").YouTubePopup({
            autoplay: 1, draggable: true, idAttribute: 'id', 'width': 640,
            'height': 360, 'clickOutsideClose': true, 'cssClass': 'cs_youtube_tutorial_top'
        });
    });
    $("body").delegate(".cs_tutorial_popup", "click", function () {
        $(".cs_tutorial_popup").YouTubePopup({
            autoplay: 1, draggable: true, idAttribute: 'id', 'width': 640,
            'height': 360, 'clickOutsideClose': true, 'cssClass': 'cs_youtube_tutorial_top'
        });
    });

    function add_content_to_post_box(in_content) {
        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
        var ed_content = tinyMCE.get('content');
        var ed = tinyMCE.activeEditor;

        $('#content-tmce').click(); // ensure we are on visual
        tinyMCE.activeEditor.focus();
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, in_content);
    }

    $(".add_raw_link_attribution").click(function () {
        // get the link and the link text
        var theLink = $("#source_url").val();
        var theLinkText = $("#curated_link_text").val();
        // this is a passed CS setting for no follow
        isNoFollow = yb_cu_post_vars.curation_suite_no_follow;
        var theLinkHTML = '';
        theLinkHTML += '<p></p><p><a href="' + theLink + '" target="_blank" class="cs_link"';
        if (isNoFollow)
            theLinkHTML += 'rel="nofollow"';

        theLinkHTML += '>' + theLinkText + '</a></p>';
        add_content_to_post_box(theLinkHTML);

        do_save_curated_link_to_meta(theLink, $("#post_ID").val());
    });

    // this function will toggle a feature within the LE. It then changes the toggle switch.
    $("#ybi_curation_suite_listening_links").delegate(".cs_le_feature_toggle", "click", function () {
        var elem = $(this);
        var elem_html = elem.html();
        $('#platform_edit_message').html('');
        remove_message_indicator_color('#platform_edit_message');
        elem.html(spinner);
        var platform_id = $('#cu_listening_platform_id').val();
        var feature_name = elem.attr('data-feature-name');
        var feature_status = elem.attr('data-feature-status');
        data = {
            action: 'cs_le_toggle_platform_option',
            platform_id: platform_id,
            feature_name: feature_name
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                if (results.status == 'success') {
                    showNoticeMessage('Updated...');
                    if (feature_status == 'on') {
                        elem.html(toggle_off);
                        elem.attr('data-feature-status', 'off');
                    } else {
                        elem.html(toggle_on);
                        elem.attr('data-feature-status', 'on');
                    }
                } else {
                    $('#platform_edit_message').html(results.message).addClass('cs_bad');
                    $('.edit_platform_name_btn').html('Update');
                }
            }
        });
    });


    $("#listening_meta_control").delegate(".delete_detail_search_item", "click", function () {
        var platform_id = $('#cu_listening_platform_id').val();
        var elem = $(this);
        var elem_html = elem.html();
        // this is the type of search
        var search_type = elem.attr('data-search-type');
        // this is the item and the class
        var data_item = elem.attr('data-item');
        // get the text we use this to pass to the function to do a search and delete based on the raw text.
        var data_text = $('.'+data_item).text();
        var elem = $(this);
        var elem_html = elem.html();
        // this is the type of search
        var search_type = elem.attr('data-search-type');
        // this is the item and the class
        var data_item = elem.attr('data-item');
        // get the text we use this to pass to the function to do a search and delete based on the raw text.
        var data_text = $('.'+data_item).text();



        $('#deletion_detail_item').html(data_text);
        $('#deletion_detail_item_type').html(' ');
        $('.ui-dialog-title').html('Delete Search?');
        $('#dialog-confirm').attr('title', 'Delete Search Keyword?');

        $("#dialog-confirm").dialog({
            resizable: false,
            width: 340,
            modal: true,
            buttons: {
                "Confirm": function () {
                    $(this).dialog("close");
                    //elem.html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'cs_le_delete_detail_search_item',
                        platform_id: platform_id,
                        search_type: search_type,
                        data_item: data_item,
                        data_text: data_text
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            var loading_arr = ["#cs_le_user_saved_" + search_type];
                            cs_le_loading_show('show', loading_arr);
                            showNoticeMessage(platform_response.message);
                            //cs_le_refresh_single_topic_sources(topic_id);
                            $('#deletion_detail_item').html('');
                            $('#dialog-confirm').attr('title', '');
                            $("#cs_le_user_saved_" + search_type).html(platform_response.results);
                            cs_le_loading_show('hide', loading_arr);
                        }
                    });
                },
                Cancel: function () {
                    //$('#keyword_row_topic_' + topic_id + '_keyword_' + parameter_id).removeClass('highlight');
                    $(this).dialog("close");
                    //return;
                }
            }
        });
    });

    var current_narrative_keyword = 1;

    $("#dialog-add-narrative").delegate(".add_narrative_keyword", "click", function () {
        var elem = $(this);
        $('#narrative_keyword_'+current_narrative_keyword).val(elem.text());
        current_narrative_keyword++;
        display_narrative_selections();
    });

    function display_narrative_selections()
    {
        var narrative_keyword_1 = $('#narrative_keyword_1').val();
        var narrative_keyword_2 = $('#narrative_keyword_2').val();
        var narrative_keyword_3 = $('#narrative_keyword_3').val();
        var narrative_select_1 = $('#narrative_select_1').val();
        var narrative_select_2 = $('#narrative_select_2').val();
        var narrative_strength_select = $('#narrative_strength_select').val();
        var narrative_outline = '';
        var keywords_arr = [];
        var sort_combined = narrative_select_1 + '-' + narrative_select_2;

        if(narrative_keyword_1 != '') {
            switch (sort_combined) {
                case 'AND-AND':
                    narrative_outline = narrative_keyword_1;
                    if(narrative_keyword_2 != '') {
                        narrative_outline += ' ' + narrative_keyword_2;
                        if(narrative_keyword_3 != '') {
                            narrative_outline += ' ' + narrative_keyword_3;
                        }
                    }
                    keywords_arr.push(narrative_outline);
                    break;
                case 'OR-AND':
                    // just 2 keywords
                    if(narrative_keyword_2 != '' && narrative_keyword_3 == '') {
                        keywords_arr.push(narrative_keyword_1);
                        keywords_arr.push(narrative_keyword_2);

                    } else { // 3 keywords
                        keywords_arr.push(narrative_keyword_1 + ' ' + narrative_keyword_3);
                        keywords_arr.push(narrative_keyword_2 + ' ' + narrative_keyword_3);
                    }
                    if(narrative_keyword_1 == '' && narrative_keyword_3 == '') {
                        keywords_arr.push(narrative_keyword_1);
                    }

                    //keywords_arr.push(narrative_outline);
                    break;
                case 'AND-OR':

                    narrative_outline = narrative_keyword_1;
                    if(narrative_keyword_2 != '') {
                        narrative_outline += ' ' + narrative_keyword_2;
                        keywords_arr.push(narrative_outline);
                        if(narrative_keyword_3 != '') {
                            narrative_outline = narrative_keyword_1 + ' ' + narrative_keyword_3;
                            keywords_arr.push(narrative_outline);
                        }
                    } else {
                        keywords_arr.push(narrative_outline);
                    }

                    break;
                case 'OR-OR':
                    narrative_outline = narrative_keyword_1;
                    keywords_arr.push(narrative_outline);
                    if(narrative_keyword_2 != '') {
                        keywords_arr.push(narrative_keyword_2);
                        if(narrative_keyword_3 != '') {
                            keywords_arr.push(narrative_keyword_3);
                        }
                    }
                    break;
            }

        }
        var i;
        var narrative_html = '';
        for (i = 0; i < keywords_arr.length; ++i) {
            // do something with `substr[i]`
            narrative_html += '<p><i>block all stories that mention:</i> "<strong>' + keywords_arr[i] + '</strong>"</p>';
        }

        $('#narrative_result').html(narrative_html);
    }

    $(".narrative_item").bind('input propertychange', function () {
        display_narrative_selections();
    });
    $(".narrative_item").change(function () {
        display_narrative_selections();
    });

    function reset_narrative_items()
    {
        $('#narrative_keyword_1').val('');
        $('#narrative_keyword_2').val('');
        $('#narrative_keyword_3').val('');
        $('#narrative_select_1').val('AND');
        $('#narrative_select_2').val('AND');
        $('#narrative_result').html('');
        $('#narrative_strength_select').val('title');
        $('#narrative_time_frame').val('7-DAY');
        current_narrative_keyword = 1;
    }

    $("#ybi_curation_suite_listening_links").delegate(".new_narrative_block", "click", function () {
        reset_narrative_items();
    });

    $("#dialog-add-narrative").delegate("#reset_narrative_items", "click", function () {
        reset_narrative_items();
    });


    $("#ybi_curation_suite_listening_links").delegate(".cs_le_add_narrative", "click", function () {
        current_narrative_keyword = 1;
        var elem = $(this);
        var platform_id = $('#cu_listening_platform_id').val();
        var content_item_id = elem.attr('data-content-item-id');

        var title = $('.cu_cid_row_' + content_item_id + ' H4').text();
        var snippet = $('.cu_cid_row_' + content_item_id + ' .snippet').text();
        var current_action = elem.attr('data-action-type');
        $('#narrative_title_selection').html(spinner + ' Analyzing..');
        $('#narrative_snippet_selection').html('');
        var show_title = '';
        var show_snippet = '';
        data = {
            action: 'cs_le_get_narrative_elements',
            title: title,
            snippet: snippet
        };
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: ajax_url,
            success: function (results) {
                if (results.status == 'success') {
                    $('#narrative_keyword_1').val('');
                    $('#narrative_keyword_2').val('');
                    $('#narrative_keyword_3').val('');
                    $('#narrative_select_1').val('AND');
                    $('#narrative_select_2').val('AND');
                    show_title = results.title_html;
                    show_snippet = results.snippet_html;
                    $('#narrative_title_selection').html(show_title);
                    $('#narrative_snippet_selection').html(show_snippet);
                } else {

                }
            }
        });

        $('#dialog-add-narrative').attr('title', 'Add Narrative Block?');

        $("#dialog-add-narrative").dialog({
            resizable: false,
            width: 500,
            modal: true,
            buttons: {
                "Add Narrative Block": function () {

                    var narrative_keyword_1 = $('#narrative_keyword_1').val();
                    var narrative_keyword_2 = $('#narrative_keyword_2').val();
                    var narrative_keyword_3 = $('#narrative_keyword_3').val();
                    var narrative_select_1 = $('#narrative_select_1').val();
                    var narrative_select_2 = $('#narrative_select_2').val();
                    var narrative_strength_select = $('#narrative_strength_select').val();
                    var narrative_time_frame = $('#narrative_time_frame').val();
                    var buttons_html = $('.ui-dialog-buttonpane').html();

                    $('#narrative_title_selection').html('');
                    $('#narrative_snippet_selection').addClass('center').html(spinner + ' Adding...');
                    //elem.html('<i class="fa fa-spinner fa-spin"></i>');
                    var data = {
                        action: 'cs_le_add_narrative_action',
                        platform_id: platform_id,
                        narrative_keyword_1: narrative_keyword_1,
                        narrative_keyword_2: narrative_keyword_2,
                        narrative_keyword_3: narrative_keyword_3,
                        narrative_select_1: narrative_select_1,
                        narrative_select_2: narrative_select_2,
                        narrative_strength_select: narrative_strength_select,
                        narrative_time_frame: narrative_time_frame
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {

                            $('#dialog-add-narrative').dialog("close");
                            $('#narrative_snippet_selection').removeClass('center')
                            showNoticeMessage('Narrative Block Added');
                        }
                    });
                },
                Cancel: function () {
                    $('#dialog-add-narrative').dialog("close");
                    return;
                }
            }
        });
    });

    function reset_le_quick_editor()
    {
        $('.cs_le_qe_reset').val('');
    }
    function getPowerWord() {
        var power_words = [
            "admits", "analyzes", "announces", "argues", "asks",
            "believes",
            "calls", "calls out", "compares", "categorizes", "concludes", "covers",
            "declares", "defines", "describes", "details", "discovers", "discusses", "dissects",
            "educates", "encourages", "evaluates", "expands", "explains", "explores", "features", "finds out", "focuses",
            "gives", "gives you", "guides",
            "highlights","lays out","looks to","mentions","notes","notes that","opens up","outlines","praises","proposes",
            "provides","recaps","recommends","reflects","reveals","reviews","says","shares","sheds light on","showcases",
            "summarizes","talks about","tells","thinks","visualizes","warns"
        ];
        return power_words[Math.floor(Math.random() * power_words.length)];
    }

    function getStartPhrase(source_domain) {
        var startPhrase = [
            "This story from "+source_domain,
            "A recent story from "+ source_domain,
            "A must read story from "+ source_domain,
            "What is most likely to be an overlooked story from "+ source_domain,
            "Today I came across this story from "+source_domain+ " that",
            source_domain + " ",
            "I couldn't believe this story from " + source_domain + " that"
        ];
        return startPhrase[Math.floor(Math.random() * startPhrase.length)];
    }

    function getLeadInPhrase() {
        var leadInPhrase = ["a little known", "a surprising", "an interesting", "the truth about","things we don't talk about but", "how", "a revealing", "a hidden", "an obvious"];
        return leadInPhrase[Math.floor(Math.random() * leadInPhrase.length)];
    }

    var cs_qe_ran = false;

    $("body").delegate(".le_show_quick_editor", "click", function () {
        reset_le_quick_editor();
        var elem = $(this);
        elem.html(spinner);
        showNoticeMessage(spinner + ' Loading Editor...');
        var content_item_id = elem.attr('parameter_id');
        var snippet = $('.cu_cid_row_' + content_item_id + ' .snippet').text();
        var source_domain = $('.cu_cid_row_' + content_item_id + ' .le_domain_name').text();
        if(yb_cu_post_vars.sub_headline_on != 1) {
            $('#sub_headline_row').hide();
        }
        $('#dialog-le-quick-editor').attr('title', 'Curate to Post Quick Editor');

        $("#dialog-le-quick-editor").dialog({
            resizable: true,
            width: 800,
            modal: true,
            draggable: true,
            height: "auto",
            position: {my: "center", at: "center", of: window },
            close : function(){
                reset_le_quick_editor();
                wp.editor.remove('cs_le_qe_curated_content_editor');
                $('.cu_qe_icon_' + content_item_id).html('<i class="fa fa-pencil-square-o"></i>');
                $('#cs_le_qe_curated_content_editor').val('');
                $('#dialog-le-quick-editor').dialog("close");
                return;
            },
            buttons: {
                "Publish Post": function() {
                    var do_publish = false;

                    var category_text = $("#cs_le_qe_category_id option:selected").text();
                    if(category_text == '' || category_text == 'Uncategorized') {
                        //window.confirm("Are you sure you don't want to select a category?");
                        if (confirm("Are you sure you don't want to select a category?") == true) {
                            do_publish = true;
                        } else {
                            return;
                        }
                    } else {
                        do_publish = true;
                    }

                    if(do_publish) {
                        $('#dialog-le-quick-editor .ui-dialog-title').html(spinner + ' Publishing post...');
                        $('.ui-dialog-buttonset').html('<h3>' + spinner + ' Publishing post...</h3>');
                        var content_type = 'article';
                        if ($('.cu_cid_row_' + content_item_id).hasClass('cs_video')) {
                            content_type = 'video';
                        }
                        // notice here we take the value from the QE drop down and not the hidden settings drop down.
                        // That settings one is used for the curate to post feature, but the setting set there is defaulted in the QE
                        var quick_post_publish_type = $('#ybi_cs_quick_post_publish_type_qe').val();
                        var ybi_cs_click_draft_video_actions = $('#ybi_cs_click_draft_video_actions').val();
                        var headline = $('#cs_le_qe_title').val();
                        var sub_headline = $('#cs_le_qe_sub_headline').val();
                        var tags = $('#cs_le_qe_tags').val();
                        var cited_text = wp.editor.getContent('cs_le_qe_curated_content_editor');
                        var category_id = $('#cs_le_qe_category_id').val();
                        var attribution_link = $('.cu_cid_row_' + content_item_id + ' h4 a').attr('href');
                        var platform_id = $('#cu_listening_platform_id').val();
                        var cs_le_quick_post_action_type = 'quick_editor';
                        var data = {
                            action: 'ybi_cs_add_draft_post',
                            platform_id: platform_id,
                            content_item_id: content_item_id,
                            headline: headline,
                            sub_headline: sub_headline,
                            tags: tags,
                            image: image,
                            attribution_link: attribution_link,
                            cited_text: cited_text,
                            category_id: category_id,
                            feature_image: feature_image,
                            quick_post_publish_type: quick_post_publish_type,
                            ybi_cs_click_draft_video_actions: ybi_cs_click_draft_video_actions,
                            content_type: content_type,
                            cs_le_quick_post_action_type: cs_le_quick_post_action_type
                        };
                        $.ajax({
                            type: "POST",
                            data: data,
                            dataType: "json",
                            url: ajax_url,
                            success: function (platform_response) {
                                $('#dialog-le-quick-editor').dialog("close");
                                reset_le_quick_editor();
                                contentItemRemoveRefreshDisplay(content_item_id);
                                showNoticeMessage('Post Added');
                            }
                        });
                    }
                },
                Cancel: function () {
                    reset_le_quick_editor();
                    wp.editor.remove('cs_le_qe_curated_content_editor');
                    $('.cu_qe_icon_' + content_item_id).html('<i class="fa fa-pencil-square-o"></i>');
                    $('#cs_le_qe_curated_content_editor').val('');
                    $('#dialog-le-quick-editor').dialog("close");
                    return;
                }
            }
        });

        // we remove the jquery ui classes and then add the standard WP primary button ones
        $('.ui-dialog-buttonset').find('button:contains("Publish Post")').removeClass('ui-state-default').removeClass('ui-button-text-only').removeClass('ui-widget');
        $('.ui-dialog-buttonset').find('button:contains("Publish Post")').addClass('button').addClass('button-primary').addClass('button-large');
        var feature_image = $('#ybi_cs_draft_image_feature').is(':checked');
        var wrap_blockquote_off = $('#ybi_cs_draft_blockquote_off_feature').is(':checked');
        var quick_post_publish_type = $('#ybi_cs_quick_post_publish_type').val();
        var ybi_cs_click_draft_video_actions = $('#ybi_cs_click_draft_video_actions').val();
        var ybi_cs_draft_link_text = $('#ybi_cs_draft_link_text').val();
        var headline = $('.cu_cid_row_' + content_item_id + ' h4 a').html();
        var image = $('.cu_cid_row_' + content_item_id + ' .thumb img').attr('src');
        var attribution_link = $('.cu_cid_row_' + content_item_id + ' h4 a').attr('href');
        source_domain = $('.cu_cid_row_' + content_item_id + ' .le_domain_name').text();

        if(ybi_cs_draft_link_text == '') {
            ybi_cs_draft_link_text = headline;
        }

        var image_html = '';
        if(feature_image) {
            $('#cs_le_qe_image_option').html('<img src="'+ image + '" class="alignleft imagedropshadow" height="75" /> <span class="image_location_text"> Image will be featured</span>');
        } else {
            image_html = '<img src="'+ image + '" class="alignleft" width="150" />';
            $('#cs_le_qe_image_option').html('<span class="image_location_text"> <i>Image re-sized for easy editing.<br />Will be full size on post.</i></span>');
        }

        var wrap_element = 'p';
        var snippet_with_wrap = '';
        if(!wrap_blockquote_off) {
            wrap_element = 'blockquote';
        }
        var full_snippet_html = '<p class="line_break_hack"></p><' + wrap_element + ' class="cs_cc">'
            + image_html + snippet + ' <a href="' + attribution_link + '" target="_blank">' + ybi_cs_draft_link_text + '</a></' + wrap_element + '> <i>thumbnail courtesy of ' + source_domain + '</i>';

        wp.editor.remove('cs_le_qe_curated_content_editor');
        $('#cs_le_qe_title').val(headline);
        // set this to a hidden input so we can access it below
        $('#cs_le_qe_source_domain').val(source_domain);
        $('#cs_le_qe_curated_content_editor').val('');
        $('#cs_le_qe_curated_content_editor').val(full_snippet_html);
        // we only fire the below if the quick editor hasn't been previously fired
        if(!cs_qe_ran) {
            $( document ).on( 'tinymce-editor-setup', function( event, editor ) {
                editor.settings.toolbar1 += ',blockquote,alignleft,aligncenter,alignright,commentaryizer';
                editor.settings.height = 190;
                editor.settings.content_css += ',' + yb_cu_post_vars.custom_qe_css + '?ver=1.0';
                //editor.settings.content_css += ',http://localhost:8080/plugin_dev/wp-content/plugins/curation-suite/css/qu-editor.css';
                editor.addButton( 'commentaryizer', {
                    text: ' Commentaryizer',
                    icon: false,
                    image: yb_cu_post_vars.plugins_url + '/curation-suite/i/commentaryizer2.png',
                    onclick: function () {
                        source_domain = $('#cs_le_qe_source_domain').val();
                       // we remove the element then replace it down below
                        $("#content_ifr").contents().find(".cs_user_commentary").remove();
                        // this is primarily because if the curated content is in block quote if this isn't there then the text added will be wrapped in blockquote. So see above where we add this to the curated content.
                        $("#content_ifr").contents().find(".line_break_hack").remove();
                        tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('.cs_user_commentary'));
                        // build the commentizer text
                        var commnetary_help_text = getStartPhrase(source_domain) + ' ' + getPowerWord() + ' ' + getLeadInPhrase();
                        editor.insertContent('<p class="cs_user_commentary">' + commnetary_help_text + '</p>');
                        tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('.line_break_hack'));
                    }
                });
            });
            // note that the quick editor ran
            cs_qe_ran = true;
        }
        wp.editor.initialize('cs_le_qe_curated_content_editor');
        if(yb_cu_post_vars.sub_headline_on != 1) {
            $('#cs_le_qe_title').focus();
        } else {
            $('#cs_le_qe_sub_headline').focus();
        }
    });


}); // end of doc