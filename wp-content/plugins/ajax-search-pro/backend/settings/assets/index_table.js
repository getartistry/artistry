jQuery(function ($) {
    var post = null;
    var $buttons = $("#index_buttons input[type='button']");
    var $progress = $(".wd_progress_text, .wd_progress, .wd_progress_stop");
    var $progress_bar = $(".wd_progress span");
    var $progress_text = $(".wd_progress_text");
    var $overlay = $("#asp_it_disable");
    var $success = $("#asp_i_success");
    var $error = $("#asp_i_error");
    var $error_cont = $("#asp_i_error_cont");
    var data = "";
    var keywords_found = 0;
    var remaining_blogs = [];
    var blog = "";
    var initial_action = "";

    function asp_on_post_success(response) {
        var res = response.replace(/^\s*[\r\n]/gm, "");
        res = res.match(/!!!ASP_INDEX_START!!!(.*[\s\S]*)!!!ASP_INDEX_STOP!!!/);
        if (res != null && (typeof res[1] != 'undefined')) {
            res = JSON.parse(res[1]);

            if (
                typeof res.postsIndexed != "undefined" ||
                (typeof res.postsIndexed != "undefined" && remaining_blogs.length > 0)
            ) {
                // New or extend operation
                res.postsIndexed = Number(res.postsIndexed);
                res.postsToIndex = Number(res.postsToIndex);
                res.keywordsFound = Number(res.keywordsFound);
                res.totalKeywords = Number(res.totalKeywords);

                $("#indexed_counter").html(res.postsIndexed);
                $("#not_indexed_counter").html(res.postsToIndex);
                $("#keywords_counter").html(res.totalKeywords);

                if (res.postsToIndex > 0 || remaining_blogs.length > 0) {
                    var percent = (res.postsIndexed / (res.postsToIndex + res.postsIndexed)) * 100;
                    keywords_found += res.keywordsFound;

                    $progress_bar.css('width', percent + "%");

                    if ($('input[name=it_blog_ids]').val() != "")
                        $progress_text.html("Progress: " + percent.toFixed(2) + "% | Keywords found so far: " + keywords_found + " | Processing blog no. " + blog);
                    else
                        $progress_text.html("Progress: " + percent.toFixed(2) + "% | Keywords found so far: " + keywords_found);

                    var the_action = 'extend';
                    // No posts left, try switching the blog
                    if (res.postsToIndex <= 0 && remaining_blogs.length > 0) {
                        blog = remaining_blogs.shift();
                        if (initial_action == 'new')
                            the_action = 'switching_blog';
                    }

                    data = {
                        action: 'asp_indextable_admin_ajax',
                        asp_index_action: the_action,
                        blog_id: blog,
                        data: $('#asp_indextable_settings').serialize()
                    };

                    // Wait a bit to cool off the server
                    setTimeout(function () {
                        post = $.post(ajaxurl, data)
                            .done(asp_on_post_success)
                            .fail(asp_on_post_failure);
                    }, 1500);

                    return;
                }

                keywords_found += res.keywordsFound;
                $success.removeClass('hiddend').html("Success. <strong>" + keywords_found + "</strong> new keywords were added to the database.");
            } else {
                res.postsToIndex = Number(res.postsToIndex);
                res.totalKeywords = Number(res.totalKeywords);

                $("#indexed_counter").html(0);
                $("#not_indexed_counter").html(res.postsToIndex);
                $("#keywords_counter").html(res.totalKeywords);

                $success.removeClass('hiddend').html("Success. The index table was emptied.");
            }
        } else {
            $error.removeClass('hiddend').html('Something went wrong. Here is the error message returned: ');
            $error_cont.removeClass('hiddend').val(response);
        }

        $buttons.removeAttr('disabled');
        $progress.addClass('hiddend');
        $overlay.addClass('hiddend');
    }

    function asp_on_post_failure(response, t) {
        if (t === "timeout") {
            $error.removeClass('hiddend').html('Timeout error. Try lowering the <strong>Post limit per iteration</strong> option below.');
        } else {
            $error.removeClass('hiddend').html('Something went wrong. Here is the error message returned: ');
            console.log(response);
            if (
                typeof response.status != 'undefined' &&
                typeof response.statusText != 'undefined'
            ) {
                $error_cont.removeClass('hiddend').val("Status: " + response.status + "\nCode: " + response.statusText);
            } else {
                $error_cont.removeClass('hiddend').val(response);
            }
        }
        $buttons.removeAttr('disabled');
        $progress.addClass('hiddend');
        $overlay.addClass('hiddend');
    }


    $('#asp_index_new, #asp_index_extend, #asp_index_delete').on('click', function (e) {
        if (!confirm($(this).attr('index_msg'))) {
            return false;
        }

        $('.asp-notice-ri').css("display", "none");

        $('.wd_progress_stop').click();

        var blogids_input_val = $('input[name=it_blog_ids]').val().replace('xxx1', '');

        if ($('input.use-all-blogs').is(':checked')) {
            $(".wpdreamsBlogselect ul.connectedSortable li").each(function () {
                remaining_blogs.push($(this).attr('bid'));
            });
        } else if (blogids_input_val != "") {
            remaining_blogs = blogids_input_val.split('|');
        } else {
            remaining_blogs = ASP_IT.current_blog_id.slice(0);
        }

        // Still nothing
        if (remaining_blogs.length == 0)
            remaining_blogs = ASP_IT.current_blog_id.slice(0); // make a shadow clone, otherwise ASP_IT.curr.. will be altered

        blog = remaining_blogs.shift();
        $buttons.attr('disabled', 'disabled');
        $progress.removeClass('hiddend');
        $overlay.removeClass('hiddend');
        $success.addClass('hiddend');
        $error.addClass('hiddend');
        $error_cont.addClass('hiddend');

        initial_action = $(this).attr('index_action');

        data = {
            action: 'asp_indextable_admin_ajax',
            asp_index_action: $(this).attr('index_action'),
            blog_id: blog,
            data: $('#asp_indextable_settings').serialize()
        };

        // Wait a bit to cool off the server
        setTimeout(function () {
            post = $.post(ajaxurl, data)
                .done(asp_on_post_success)
                .fail(asp_on_post_failure);
        }, 250);
    });

    $('.wd_progress_stop').on('click', function (e) {
        if (post != null) post.abort();
        keywords_found = 0;
        data = "";
        $("#index_buttons input[type='button']").removeAttr('disabled');
        $(".wd_progress_text, .wd_progress, .wd_progress_stop").addClass('hiddend');
        $error.addClass('hiddend');
        $error_cont.addClass('hiddend');
        $progress_bar.css('width', "0%");
        $progress_text.html("Initializing, please wait.");
    });

    $('.tabs a[tabid=1]').click();

    $("ul.connectedSortable", $('input[name=it_post_types]').parent()).on("sortupdate", function(){
        if ( $('input[name=it_post_types]').val().indexOf('attachment') > -1 )
            $('textarea[name=it_attachment_mime_types]').closest('.item').removeClass('hiddend');
        else
            $('textarea[name=it_attachment_mime_types]').closest('.item').addClass('hiddend');
    });
    $("ul.connectedSortable", $('input[name=it_post_types]').parent()).trigger("sortupdate");

    $("input[name=it_pool_size_auto]").on('change', function(){
       if ( $(this).val() == 1 ) {
           $('.it_pool_size.item').addClass('disabled');
       } else {
           $('.it_pool_size.item').removeClass('disabled');
       }
    });
    $("input[name=it_pool_size_auto]").change();
});