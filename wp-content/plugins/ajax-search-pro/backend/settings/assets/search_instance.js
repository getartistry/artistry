jQuery(function($){

    function submit_mimic(action, method, input) {
        'use strict';
        var form;
        form = $('<form />', {
            action: action,
            method: method,
            style: 'display: none;'
        });
        if (typeof input !== 'undefined' && input !== null) {
            $.each(input, function (name, value) {
                $('<input />', {
                    type: 'hidden',
                    name: name,
                    value: value
                }).appendTo(form);
            });
        }
        form.appendTo('body').submit();
    }

    // --- Safety check on max_input_vars
    if ( $('#asp_options_serialized').length > 0 ) {
        $('form[name="asp_data"]').submit(function(e){
            if ( typeof(Base64) != "undefined" ) {
                // Send the back-up form instead, with 1 variable only
                e.preventDefault();
                $('#asp_options_serialized').val( Base64.encode($('form[name="asp_data"]').serialize()) );
                $('form[name="asp_data_serialized"]').submit();
            }
       });
    }

    // -- Reset search instance to defaults.
    $('#wpdreams input[name^=reset_][type=button].asp_submit.asp_submit_reset').on('click', function(){
        var r = confirm("Are you sure you want to reset the options for this instance to defaults? All changes to this search will be lost!");
        if ( r == true) {
            var name = $(this).attr('name');
            var data = {
                'asp_sett_nonce': $('#asp_sett_nonce').val()
            };
            data[name] = name;
            submit_mimic('', 'post', data);
        }
    });

    // --- SHORTCODES AND GENERATOR ---
    $('.asp_b_shortcodes_menu').click(function(){
        $(this).parent().toggleClass('asp_open');
    });

    function sc_generate() {
        var items = [];
        var ratios = [];
        var sid = $('#wpd_shortcode_modal').attr('sid');

        $('#wpd_shortcode_modal ul li').each(function(){
            if ( !$(this).hasClass('hiddend') ) {
                items.push($(this).attr('item'));
                ratios.push($('input',this).val());
            }
        });

        var elements = items.join(',');
        if ( elements != "" )
            elements = " elements='" + elements + "'";
        var ratio = ratios.join('%,');
        if ( ratio != "" )
            ratio = " ratio='" + ratio + "%'";

        $('#wpd_shortcode_modal textarea').val('[wd_asp' + elements + ratio + " id=" + sid + "]");
    }

    $('#shortcode_generator').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        sc_generate();
        $('#wpd_shortcode_modal').removeClass('hiddend');
        $('#wpd_shortcode_modal_bg').css('display', 'block');
    });
    $('#wpd_shortcode_modal .wpd-modal-close, #wpd_shortcode_modal_bg').on('click', function(){
        $('#wpd_shortcode_modal').addClass('hiddend');
        $('#wpd_shortcode_modal_bg').css('display', 'none');
    });

    $('#wpd_shortcode_modal li a.deleteIcon').on('click', function(){
        $(this).parent().addClass('hiddend');
        $('#wpd_shortcode_modal button[item=' + $(this).parent().attr('item') + ']').removeAttr('disabled');
        sc_generate();
    });
    $('#wpd_shortcode_modal li input').on('change', function(){
        $(this).parent().parent().css('width', $(this).val() + "%");
        sc_generate();
    });
    $('#wpd_shortcode_modal .wpd_generated_shortcode button').on('click', function(){
        $(this).attr('disabled', 'disabled');
        $('#wpd_shortcode_modal li[item=' + $(this).attr('item') + ']').detach().appendTo($("#wpd_shortcode_modal .sortablecontainer .ui-sortable"));
        $('#wpd_shortcode_modal li[item=' + $(this).attr('item') + ']').removeClass('hiddend');
        sc_generate();
    });

    $("#wpd_shortcode_modal .sortablecontainer .ui-sortable").sortable({}, {
        update: function (event, ui) {}
    }).disableSelection();
    $("#wpd_shortcode_modal .sortablecontainer .ui-sortable").on('sortupdate', function(event, ui) {
        sc_generate();
    });

    $('#wpd_shortcode_modal .wpd_generated_shortcode select').on('change', function(){
        var items = ['search', 'settings', 'results'];
        var _val = $(this).val().split('|');
        var elements = _val[0].split(',');
        var ratios = _val[1].split(',');

        $('#wpd_shortcode_modal li a.deleteIcon').click();
        $.each(elements, function(i, v) {
            $('#wpd_shortcode_modal .wpd_generated_shortcode button[item='+ items[v] +']').click();
            $('#wpd_shortcode_modal li[item=' + items[v] + '] input').val(ratios[i]).change();
        });

        sc_generate();
    });
    // --------------------------------

    //var ajaxurl = '<?php bloginfo("url"); ?>' + "/wp-content/plugins/ajax-search-pro/ajax_search.php";
    $('.tabs a[tabid=6]').click(function () {
        $('.tabs a[tabid=601]').click();
    });
    $('.tabs a[tabid=1]').click(function () {
        $('.tabs a[tabid=101]').click();
    });
    $('.tabs a[tabid=4]').click(function () {
        $('.tabs a[tabid=401]').click();
    });
    $('.tabs a[tabid=3]').click(function () {
        $('.tabs a[tabid=301]').click();
    });
    $('.tabs a[tabid=5]').click(function () {
        $('.tabs a[tabid=501]').click();
    });
    $('.tabs a[tabid=7]').click(function () {
        $('.tabs a[tabid=701]').click();
    });
    $('.tabs a[tabid=8]').click(function () {
        $('.tabs a[tabid=801]').click();
    });

    $('.tabs a').on('click', function(){
        $('#sett_tabid').val($(this).attr('tabid'));
        location.hash = $(this).attr('tabid');
    });

    $('select[name="cpt_display_mode"]').change(function(){
        if ($(this).val() == "checkboxes") {
            $('input[name=cpt_cbx_show_select_all]')
                .closest('div').removeClass('disabled');
            $('select[name="cpt_filter_default"]').attr('disabled', 'disabled');
        } else {
            $('input[name=cpt_cbx_show_select_all]').val(0)
                .closest('div').addClass('disabled').find('.triggerer').click();
            $('select[name="cpt_filter_default"]').removeAttr('disabled');
        }
    });
    $('select[name="cpt_display_mode"]').change();

    $('input[name=cpt_cbx_show_select_all]').on('change', function(){
        if ($(this).val() == 0) {
            $('input[name=cpt_cbx_show_select_all_text]').closest('div').addClass('disabled');
        } else {
            $('input[name=cpt_cbx_show_select_all_text]').closest('div').removeClass('disabled');
        }
    });
    $('input[name=cpt_cbx_show_select_all]').trigger('change');

    // ---------------------- General/Sources 1 ------------------------
    $('input[name="search_all_cf"]').change(function(){
        if ($(this).val() == 1)
            $('input[name="customfields"]').parent().addClass('disabled');
        else
            $('input[name="customfields"]').parent().removeClass('disabled');
    });
    $('input[name="search_all_cf"]').change();
    // -----------------------------------------------------------------


    // ---------------------- General/Attachments ------------------------
    $('select[name="attachments_use_index"]').change(function() {
        if ($(this).val() == 'index') {
            $("#wpdreams .item.hide_on_att_index").addClass('hiddend');
        } else {
            $("#wpdreams .item.hide_on_att_index").removeClass('hiddend');
        }
    });
    $('select[name="attachments_use_index"]').change();
    // -----------------------------------------------------------------

    // ---------------------- General/Behavior ------------------------
    $('select[name=click_action], select[name=return_action]').change(function(){
        var redirect = false;
        $('select[name=click_action], select[name=return_action]').each(function(i, v) {
            if ( $(v).val() == 'custom_url' ) {
                redirect = true;
                return false; //break
            }
        });
        if ( redirect ) {
            $('input[name=redirect_url]').parent().parent().removeClass('hiddend');
        } else {
            $('input[name=redirect_url]').parent().parent().addClass('hiddend');

        }

        var $loc = $('select[name*=_action_location]', $(this).closest('.item')).parent();
        if (
            $(this).val() == 'ajax_search' ||
            $(this).val() == 'nothing' ||
            $(this).val() == 'same'
        ) {
            $loc.addClass('hiddend');
        } else{
            $loc.removeClass('hiddend');
        }
    });
    $('select[name=click_action]').change();
    $('select[name=return_action]').change();

    $('select[name=mob_click_action], select[name=mob_return_action]').change(function(){
        var redirect = false;
        $('select[name=mob_click_action], select[name=mob_return_action]').each(function(i, v) {
            if ( $(v).val() == 'custom_url' ) {
                redirect = true;
                return false; //break
            }
        });
        if ( redirect ) {
            $('input[name=mob_redirect_url]').parent().parent().removeClass('hiddend');
        } else {
            $('input[name=mob_redirect_url]').parent().parent().addClass('hiddend');
        }

        var $loc = $('select[name*=_action_location]', $(this).closest('.item')).parent();
        if (
            $(this).val() == 'ajax_search' ||
            $(this).val() == 'nothing' ||
            $(this).val() == 'same'
        ) {
            $loc.addClass('hiddend');
        } else{
            $loc.removeClass('hiddend');
        }
    });
    $('select[name=mob_click_action]').change();
    $('select[name=mob_return_action]').change();

    $('input[name="exactonly"]').change(function(){
        if ($(this).val() == 0 || $('select[name="secondary_kw_logic"]').val() == 'none') {
            $('input[name="exact_m_secondary"]').val(0);
            $('input[name="exact_m_secondary"]').closest('div').find('.triggerer').trigger('click');
            $('input[name="exact_m_secondary"]').parent().addClass('disabled');
        } else {
            $('input[name="exact_m_secondary"]').parent().removeClass('disabled');
        }

        // Disable primary when using Exact matching
        if ( $(this).val() == 1 ) {
            $('select[name="keyword_logic"]').closest('div').addClass('disabled');
        } else {
            $('select[name="keyword_logic"]').closest('div').removeClass('disabled');
        }
    });
    $('select[name="secondary_kw_logic"]').change(function(){
        if ($(this).val() == 'none' || $('input[name="exactonly"]').val() == 0) {
            $('input[name="exact_m_secondary"]').val(0);
            $('input[name="exact_m_secondary"]').closest('div').find('.triggerer').trigger('click');
            $('input[name="exact_m_secondary"]').parent().addClass('disabled');
        } else {
            $('input[name="exact_m_secondary"]').parent().removeClass('disabled');
        }
    });
    $('input[name="exactonly"]').change();
    $('select[name="secondary_kw_logic"]').change();

    $('select[name="orderby_primary"]').change(function(){
        if ($(this).val().indexOf('customf') == -1) {
            $('input[name="orderby_primary_cf"]').parent().addClass('hiddend');
            $('select[name="orderby_primary_cf_type"]').parent().addClass('hiddend');
        } else {
            $('input[name="orderby_primary_cf"]').parent().removeClass('hiddend');
            $('select[name="orderby_primary_cf_type"]').parent().removeClass('hiddend');
        }
    });
    $('select[name="orderby_primary"]').change();
    $('select[name="orderby"]').change(function(){
        if ($(this).val().indexOf('customf') == -1) {
            $('input[name="orderby_secondary_cf"]').parent().addClass('hiddend');
            $('select[name="orderby_secondary_cf_type"]').parent().addClass('hiddend');
        } else {
            $('input[name="orderby_secondary_cf"]').parent().removeClass('hiddend');
            $('select[name="orderby_secondary_cf_type"]').parent().removeClass('hiddend');
        }
    });
    $('select[name="orderby"]').change();

    $('input[name="override_default_results"]').change(function(){
        if ($(this).val() == 0)
            $('input[name="results_per_page"]').parent().addClass('disabled');
        else
            $('input[name="results_per_page"]').parent().removeClass('disabled');
    });
    $('input[name="override_default_results"]').change();


    // -------------------- Generic Filters ----------------------------
    $('select[name=search_engine]').on('change', function() {
        if ( $(this).val() == 'index' ) {
            $('#genericFilterErr').removeClass('hiddend');
        } else {
            $('#genericFilterErr').addClass('hiddend');
        }
    });
    $('select[name=search_engine]').trigger('change');
    // -----------------------------------------------------------------

    // -----------------------------------------------------------------
    $('select[name="term_logic"]').on('change', function() {
        if ( $(this).val() == 'andex' )
            $('#term_logic_MSG').removeClass("hiddend");
        else
            $('#term_logic_MSG').addClass("hiddend");
    });
    $('select[name="term_logic"]').change();

    // ------------------------- Tags stuff ----------------------------
    $('input[name="display_all_tags_option"]').change(function(){
        if ( $(this).val() == 1 )
            $('input[name="all_tags_opt_text"]').removeAttr("disabled");
        else
            $('input[name="all_tags_opt_text"]').attr('disabled', 'disabled');
    });
    $('input[name="display_all_tags_option"]').change();

    $('input[name="display_all_tags_check_opt"]').change(function(){
        if ( $(this).val() == 1 )
            $('input[name="all_tags_check_opt_text"], select[name="all_tags_check_opt_state"]').removeAttr("disabled");
        else
            $('input[name="all_tags_check_opt_text"], select[name="all_tags_check_opt_state"]').attr('disabled', 'disabled');
    });
    $('input[name="display_all_tags_check_opt"]').change();

    $("select.wd_tagDisplayMode", $('input[name="show_frontend_tags"]').parent()).change(function(){
        if ( $(this).val() == 'checkboxes' ) {
            $(".item.wd_tag_mode_checkbox, .item.wd_tag_mode_dropdown, .item.wd_tag_mode_radio").addClass('hiddend');
            $(".item.wd_tag_mode_checkbox").removeClass('hiddend');
        } else {
            $(".item.wd_tag_mode_checkbox, .item.wd_tag_mode_dropdown, .item.wd_tag_mode_radio").addClass('hiddend');
            $(".item.wd_tag_mode_dropdown").removeClass('hiddend');
        }
    });
    $("select.wd_tagDisplayMode", $('input[name="show_frontend_tags"]').parent()).change();

    $('select.wd_tagDisplayMode').change(function(){
        if ( $(this).val() !='multisearch' )
            $('input[name=frontend_tags_placeholder]').closest('.item').addClass('disabled');
        else
            $('input[name=frontend_tags_placeholder]').closest('.item').removeClass('disabled');
        if ( $(this).val() !='checkboxes' )
            $('select[name=frontend_tags_logic]').parent().parent().addClass('disabled');
        else
            $('select[name=frontend_tags_logic]').parent().parent().removeClass('disabled');
    });
    $('select.wd_tagDisplayMode').change();
    // -----------------------------------------------------------------

    $("select[name='frontend_search_settings_position']").change(function(){
        if ( $(this).val() == 'hover' ) {
            $("select[name='fss_hover_columns']").parent().removeClass("hiddend");
            $("select[name='fss_block_columns']").parent().addClass("hiddend");

            $("input[name='fss_hide_on_results']").closest('.item').removeClass('disabled');
        } else {
            $("select[name='fss_hover_columns']").parent().addClass("hiddend");
            $("select[name='fss_block_columns']").parent().removeClass("hiddend");

            $("input[name='fss_hide_on_results']").closest('.item').addClass('disabled');
        }
    });
    $("select[name='frontend_search_settings_position']").change();

    $('input[name="exclude_dates_on"] + .wpdreamsYesNoInner').click(function(){
        if ($(this).prev().val() == 0)
            $('input[name="exclude_dates"]').parent().addClass('disabled');
        else
            $('input[name="exclude_dates"]').parent().removeClass('disabled');
    });
    if ( $('input[name="exclude_dates_on"]').val() == 0 )
        $('input[name="exclude_dates"]').parent().addClass('disabled');
    else
        $('input[name="exclude_dates"]').parent().removeClass('disabled');

    $("select[name='auto_populate']").change(function(){
        if ( $(this).val() == 'phrase' )
            $("input[name='auto_populate_phrase']").parent().css("visibility", "");
        else
            $("input[name='auto_populate_phrase']").parent().css("visibility", "hidden");
    });
    $("select[name='auto_populate']").change();

    $('input[name="use_post_type_order"]').change(function(){
        if ($(this).val() == 0)
            $('input[name="post_type_order"]').parent().parent().addClass('disabled');
        else
            $('input[name="post_type_order"]').parent().parent().removeClass('disabled');
    });
    $('input[name="use_post_type_order"]').change();

    // ---------------------- Layout options ------------------------
    $("select[name='more_results_action']").change(function(){
        if ( $(this).val() == 'redirect' )
            $("input[name='more_redirect_url']").closest('.item').removeClass("hiddend");
        else
            $("input[name='more_redirect_url']").closest('.item').addClass("hiddend");
        if ( $(this).val() != 'ajax' ) {
            $("select[name='more_redirect_location']").closest('div').removeClass("hiddend");
        } else {
            $("select[name='more_redirect_location']").closest('div').addClass("hiddend");
        }
    });
    $("select[name='more_results_action']").change();

    $("select[name='resultstype']").change(function(){
        var val = $(this).val();
        $('.item:not(.item-rlayout)', $('.item-rlayout').parent()).addClass('hiddend');
        $('.item:not(.item-rlayout)', $('.item-rlayout-' + val).parent()).removeClass('hiddend');
        $('.item-rlayout').removeClass('hiddend');
        $('.item-rlayout-' + val).addClass('hiddend');
        $('.item-rlayout p span').html(val);
    });
    $("select[name='resultstype']").change();
    $(".item-rlayout a").on("click", function(){
        var tabid = $(this).attr("tabid");
        $('.tabs a[tabid=' + Math.floor( tabid / 100 ) + ']').click();
        $('.tabs a[tabid=' + tabid + ']').click();
        if ( typeof $(this).data('asp-os-highlight') !== 'undefined' ) {
            $('.asp-os-highlighted').removeClass("asp-os-highlighted");
            $("*[name='"+$(this).data('asp-os-highlight')+"']").closest('.item').addClass("asp-os-highlighted");
        }
    });

    // -------------------------- ADVANCED OPTIONS PANEL --------------------------------
    $("select[name='group_by']").change(function(){
        if ( $(this).val() == 'none' ) {
            $("#wpdreams .item.wd_groupby_op").addClass('hiddend');
            $("#wpdreams .item.wd_groupby").addClass('hiddend');
        } else {
            $("#wpdreams .item.wd_groupby_op").removeClass('hiddend');
            $("#wpdreams .item.wd_groupby").addClass('hiddend');
            $("#wpdreams .item.wd_groupby_" + $(this).val()).removeClass('hiddend');
        }
    });
    $("select[name='group_by']").change();

    $("select[name='group_result_no_group']").change(function(){
        if ( $(this).val() == 'remove' ) {
            $("input[name='group_other_results_head']").parent().parent().css("display", "none");
        } else {
            $("input[name='group_other_results_head']").parent().parent().css("display", "");
        }
    });
    $("select[name='group_result_no_group']").change();

    // Primary and Secondary fields for custom fields
    $.each(['primary_titlefield', 'secondary_titlefield', 'primary_descriptionfield', 'secondary_descriptionfield'],
    function(i, v){
        $("select[name='"+v+"']").change(function(){
            if ( $(this).val() != 'c__f' ) {
                $("input[name='"+v+"_cf']").parent().css("display", "none");
            } else {
                $("input[name='"+v+"_cf']").parent().css("display", "");
            }
        });
        $("select[name='"+v+"']").change();
    });

    // Empty group position
    $('input[name="group_show_empty"]').change(function(){
        if ($(this).val() == 0)
            $('select[name="group_show_empty_position"]').closest('.wpdreamsCustomSelect').addClass('disabled');
        else
            $('select[name="group_show_empty_position"]').closest('.wpdreamsCustomSelect').removeClass('disabled');
    });
    $('input[name="group_show_empty"]').change();
    // -------------------------- ADVANCED OPTIONS PANEL --------------------------------

    // -------------------------------- MODAL MESSAGES ----------------------------------
    var modalItems = [
        {
            'args': {
                'type'   : 'warning', // warning, info
                'header' : 'Are you sure?',
                'headerIcons': true,
                'content': 'Using exact matches and the index table engine at the same time will automatically ignore the Index table engine, are you sure?',
                'buttons': {
                    'cancel': {
                        'text': 'No, please revert this option',
                        'type': 'cancel',
                        'click': function(e, button){}
                    },
                    'okay': {
                        'text': 'Yes, I am sure',
                        'type': 'okay',
                        'click': function(e, button){}
                    }
                }
            }, // Modal args
            'items': [
                ['search_engine', 'index'], // Item name => value
                ['exactonly', '1']
            ]
        }
    ];
    function modal_check(items) {
        var ret = false;
        // If at least one of the values does not match, it is a pass, return true
        $.each(items, function(k, item){
            if ( $('*[name='+item[0]+']').val() != item[1] ) {
                ret = true;
                return false;
            }
        });

        return ret;
    }
    $.each(modalItems, function(k, item){
       $.each(item.items, function(kk, _item){
           $('*[name='+_item[0]+']').data('oldval', $('*[name='+_item[0]+']').val());
           $('*[name='+_item[0]+']').on('change', function() {
                var _this = this;
                if ( !modal_check(item.items) ) {
                    item.args.buttons.cancel.click = function ( e, button ) {
                        if ( $(_this).data('oldval') !== undefined ) {
                            $(_this).val($(_this).data('oldval'));
                            $('.triggerer', $(_this).closest('div')).trigger('click');
                        }
                        $(_this).data('oldval', $(_this).val());
                    };
                    item.args.buttons.okay.click = function ( e, button ) {
                        $(_this).data('oldval', $(_this).val());
                    };
                    WPD_Modal.show(item.args);
                } else {
                    $(_this).data('oldval', $(_this).val());
                }
           });
       });
    });

    // -------------------------------- MODAL MESSAGES ----------------------------------

    // Remove the # from the hash, as different browsers may or may not include it
    var hash = location.hash.replace('#','');

    if(hash != ''){
        hash = parseInt(hash);
        $('.tabs a[tabid=' + Math.floor( hash / 100 ) + ']').click();
        $('.tabs a[tabid=' + hash + ']').click();
    } else {
        $('.tabs a[tabid=1]').click();
    }

    $('#wpdreams .settings').click(function () {
        $("#preview input[name=refresh]").attr('searchid', $(this).attr('searchid'));
    });
    $("select[id^=wpdreamsThemeChooser]").change(function () {
        $("#preview input[name=refresh]").click();
    });
    $("#preview .refresh").click(function (e) {
        e.preventDefault();
        var $this = $(this).parent();
        var id = $('#wpdreams').data('searchid');
        var loading = $('.big-loading', $this);

        // Remove duplicates first
        $('body>div[id^=ajaxsearchpro]').remove();

        $('.data', $this).html("");
        $('.data', $this).addClass('hidden');
        loading.removeClass('hidden');
        var data = {
            action: 'ajaxsearchpro_preview',
            asid: id,
            formdata: $('form[name="asp_data"]').serialize()
        };

        if ( typeof(Base64) != "undefined" ) {
            $("#asp_preview_options").html( Base64.encode($('form[name="asp_data"]').serialize()) );
        }

        $.post(ajaxurl, data, function (response) {
            loading.addClass('hidden');
            $('.data', $this).html(response);
            $('.data', $this).removeClass('hidden');
            ASP.initialize();
            setTimeout(
                function () {
                    if (typeof aspjQuery != 'undefined')
                        aspjQuery(window).resize();
                    else if (typeof jQuery != 'undefined')
                        jQuery(window).resize();
                },
                1000);
        });
    });

    $("#preview .maximise").click(function (e) {
        e.preventDefault();
        $this = $(this.parentNode);
        if ($(this).html() == "Show") {
            $this.animate({
                bottom: "-2px",
                height: "90%"
            });
            $(this).html('Hide');
            $("#preview a.refresh").trigger('click');
        } else {
            $this.animate({
                bottom: "-2px",
                height: "40px"
            });
            $(this).html('Show');
        }
    });

    // Show-Hide the API input fields
    var $_autoc_s = $("input[name='autocomplete_source']");
    $("ul.connectedSortable", $_autoc_s.parent()).on("sortupdate", function(){
        var v = $_autoc_s.val();
        if ( v.indexOf("google_places") > -1 ) {
            $("input[name='autoc_google_places_api']").parent().parent().removeClass("hiddend");
        } else {
            $("input[name='autoc_google_places_api']").parent().parent().addClass("hiddend");
        }

        if ( v == '' || v == 'google_places' || v == 'google' || v == 'google_places|google' || v == 'google|google_places' ) {
            $('select[name=autocomplete_instant]').val('auto').change();
            $('select[name=autocomplete_instant]').attr('disabled', 'disabled');
        } else {
            var sv = $('select[name=autocomplete_instant]').val();
            // sv is an 'object' but it is null in most cases, leave this check like this
            if ( typeof sv == 'undefined' || sv == null || sv == 'auto' ) {
                $('select[name=autocomplete_instant]').val('disabled').change();
            }
            $('select[name=autocomplete_instant]').removeAttr('disabled');
        }
    });
    $("ul.connectedSortable", $_autoc_s.parent()).trigger("sortupdate");


    $("ul.connectedSortable", $("input[name='keyword_suggestion_source']").parent()).on("sortupdate", function(){
        if ( $("input[name='keyword_suggestion_source']").val().indexOf("google_places") > -1 ) {
            $("input[name='kws_google_places_api']").parent().parent().removeClass("hiddend");
        } else {
            $("input[name='kws_google_places_api']").parent().parent().addClass("hiddend");
        }
    });
    $("ul.connectedSortable", $("input[name='keyword_suggestion_source']").parent()).trigger("sortupdate");

    if (typeof ($.fn.spectrum) != 'undefined')
        $("#bgcolorpicker").spectrum({
            showInput: true,
            showPalette: true,
            showSelectionPalette: true,
            change: function (color) {
                $("#preview").css("background", color.toHexString()); // #ff0000
            }
        });

    // Social stuff
    var url = encodeURIComponent('http://bit.ly/buy_asp');
    var fb_share_url = "https://www.facebook.com/share.php?u=";
    var tw_share_url = "https://twitter.com/intent/tweet";

    function winOpen(url) {
        var width = 575, height = 400,
            left = (document.documentElement.clientWidth / 2 - width / 2),
            top = (document.documentElement.clientHeight - height) / 2,
            opts = 'status=1,resizable=yes' +
                ',width=' + width + ',height=' + height +
                ',top=' + top + ',left=' + left,
            win = window.open(url, '', opts);
        win.focus();
        return win;
    }

    $("#asp_tw_share").on("click", function(e){
        var $this = $(this);
        e.preventDefault();
        winOpen(tw_share_url + "?text=" + encodeURIComponent($this.data("text")) + "&url=" + url + "&via=ernest_marcinko");
    });
    $("#asp_fb_share").on("click", function(e){
        e.preventDefault();
        winOpen(fb_share_url + url);
    });
});