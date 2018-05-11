<?php
$cf_tooltip_msg = 'One item per line. Use the <strong>{get_values}</strong> variable to get custom field values automatically. 
                   For more info see the 
                   <a target="_blank" href="http://wp-dreams.com/demo/wp-ajax-search-pro3/docs/#frontend_search_settings_creating_custom_selectors_from_custom_fields">documentation</a>.';

?>
<script>
    jQuery(function($) {
        var sortableCont = $("#csf_sortable");
        var $deleteIcon = $("<a class='deleteIcon'></a>");
        var $editIcon = $("<a class='editIcon'></a>");
        var resetValues = {};
        var $current = null;

        //$('#asp_edit_field').fadeOut(0);

        // Store defaults
        $('#asp_new_field input, #asp_new_field select, #asp_new_field textarea').each(function(){
           resetValues[$(this).attr('name')] = $(this).val();
        });

        // Fields for checking
        var fields = ['asp_f_title', 'asp_f_field'];

        function checkEmpty(parent) {

            var empty = false;
            $(fields).each(function () {
                if ($(parent + ' *[name="' + this.toString() + '"]').val() == '') {
                    $(parent + ' *[name="' + this.toString() + '"]').addClass('missing');
                    empty = true;
                }
            });
            return empty;
        }
        $('#asp_new_field, #asp_edit_field').click(function(e){
            if ($(e.target).attr('name') == 'add' || $(e.target).attr('name') == 'save') return;
            $(fields).each(function () {
                $('#asp_new_field *[name="' + this.toString() + '"]').removeClass('missing');
                $('#asp_edit_field *[name="' + this.toString() + '"]').removeClass('missing');
            });
        });

        function initDatePickers() {
            if (typeof $('.asp_f_datepicker_value').datepicker != "undefined") {
                $('.asp_f_datepicker_value').datepicker("destroy");
                $('.asp_f_datepicker_value').each(function(){
                    $(this).datepicker({
                        dateFormat : $('.asp_f_datepicker_format', $(this).parent()).val(),
                        changeMonth: true,
                        changeYear: true
                    });
                    if ( $(this).val() == "" )
                        $('.asp_f_datepicker_value').datepicker( "setDate", "+0" );
                });
            } else {
                $('.asp_f_datepicker_value').each(function(){
                    $(this).datepicker({
                        dateFormat : $('.asp_f_datepicker_format', $(this).parent()).val(),
                        changeMonth: true,
                        changeYear: true
                    });
                });
            }
            $('.asp_f_datepicker_defval').each(function(){
                if ( $(this).val() == "current" )
                    $('.asp_f_datepicker_value', $(this).parent() ).attr("disabled", true);
                else
                    $('.asp_f_datepicker_value', $(this).parent() ).removeAttr("disabled");
                if ( $(this).val() == "relative" ) {
                    $('.asp_f_datepicker_from', $(this).parent() ).removeClass("hiddend");
                    $('.asp_f_datepicker_value', $(this).parent() ).addClass("hiddend");
                } else {
                    $('.asp_f_datepicker_from', $(this).parent() ).addClass("hiddend");
                    $('.asp_f_datepicker_value', $(this).parent() ).removeClass("hiddend");
                }
            });
        }


            /* Type change */
        $('select[name="asp_f_type"]').on('change', function(){
            var id = $(this).parent().parent()[0].id;
            $('#' + id + ' .asp_f_type').addClass('hiddend');
            $('#' + id + ' .asp_f_' + $(this).val()).removeClass('hiddend');
            if ($(this).val() == 'slider') {
                $($('#' + id + ' .asp_f_operator optgroup')[1]).addClass('hiddend');
                $('#' + id + ' .asp_f_operator select').val('eq');
            } else {
                $($('#' + id + ' .asp_f_operator optgroup')[1]).removeClass('hiddend');
            }
            if ($(this).val() == 'checkboxes') {
                $('#' + id + ' .asp_f_operator select').val('like');
            }
            if ($(this).val() == 'range' || $(this).val() == 'datepicker') {
                $('#' + id + ' .asp_f_operator').addClass('hiddend');
            } else {
                $('#' + id + ' .asp_f_operator').removeClass('hiddend');
            }
        });
        /* Reset it on page load */
        $('select[name="asp_f_type"]').change();

        /* Sortable */
        sortableCont.sortable({
        }, {
            update: function (event, ui) {
                var parent = $('#asp_new_field').parent();
                 var items = $('#csf_sortable li');
                 var hidden = $('input[name=custom_field_items]', parent);
                 var val = "";
                 items.each(function () {
                    val += "|" + $(this).attr('custom-data');
                 });
                 val = val.substring(1);
                 hidden.val(val);
            }
        }).disableSelection();

        // Add the items to the sortable on initialisation
        var fields_val = $('input[name=custom_field_items]').val();
        if (typeof(fields_val) != 'undefined' && fields_val != '') {
            var items = fields_val.split('|');
            $.each(items, function(key, value){
                vals = JSON.parse(Base64.decode(value));
                var $li = $("<li class='ui-state-default'/>").html(vals.asp_f_title + "<a class='deleteIcon'></a><a class='editIcon'></a>");
                $li.attr("custom-data", value);
                sortableCont.append($li);
            });
            sortableCont.sortable("refresh");
            sortableCont.sortable('option', 'update').call(sortableCont);
        }


        // Add new item
        $('#asp_new_field button[name=add]').click(function(){
            var data = {};

            if (checkEmpty('#asp_new_field') == true) return;

            $('#asp_new_field input, #asp_new_field select, #asp_new_field textarea').each(function(){
                if ($(this).parent().hasClass('hiddend')) return;
                if ($(this).attr('type') == 'checkbox') {
                    if ($(this).prop('checked') == true)
                        data[$(this).attr('name')] = 'asp_checked';
                    else
                        data[$(this).attr('name')] = 'asp_unchecked';
                } else {
                    data[$(this).attr('name')] = $(this).val();
                }
            });

            var $li = $("<li class='ui-state-default'/>")
                .html(data.asp_f_title + "<a class='deleteIcon'></a><a class='editIcon'></a>");
            $li.attr("custom-data", Base64.encode(JSON.stringify(data)));

            sortableCont.append($li);
            sortableCont.sortable("refresh");
            sortableCont.sortable('option', 'update').call(sortableCont);
            initDatePickers();
        });

        // Remove item
        $('#csf_sortable').on('click', 'li a.deleteIcon', function(){
            $(this).parent().remove();
            sortableCont.sortable("refresh");
            sortableCont.sortable('option', 'update').call(sortableCont);
            $('#asp_edit_field button[name=back]').click();
            initDatePickers();
        });

        // Edit item
        $('#csf_sortable').on('click', 'li a.editIcon', function(e){
            $('#asp_new_field').fadeOut(0);
            $('#asp_edit_field').fadeIn();
            $current = $(e.target).parent();
            var data = JSON.parse(Base64.decode($current.attr("custom-data")));
            $('#asp_edit_title').text(data.asp_f_title);

            $.each(data, function(key, val){
                if (val == 'asp_checked') {
                    $('#asp_edit_field *[name=' + key + ']').prop('checked', true);
                } else if (val == 'asp_unchecked') {
                    $('#asp_edit_field *[name=' + key + ']').prop('checked', false);
                } else {
                    $('#asp_edit_field *[name=' + key + ']').val(val);
                }
                if (key == 'asp_f_type')
                    $('#asp_edit_field select[name=asp_f_type]').change();
            });
            $('#asp_edit_field input[name=asp_f_dropdown_search]').change();

            initDatePickers();
        });

        // Back to new
        $('#asp_edit_field button[name=back]').click(function(){
            $('#asp_edit_field').fadeOut(0);
            $('#asp_new_field').fadeIn();
        });

        // Save modifications
        $('#asp_edit_field button[name=save]').click(function(){
            if (checkEmpty('#asp_edit_field') == true) return;

            var data = {};
            $('#asp_edit_field input, #asp_edit_field select, #asp_edit_field textarea').each(function(){
                if ($(this).parent().hasClass('hiddend')) return;

                if ($(this).attr('type') == 'checkbox') {
                    if ($(this).prop('checked') == true)
                        data[$(this).attr('name')] = 'asp_checked';
                    else
                        data[$(this).attr('name')] = 'asp_unchecked';
                } else {
                    data[$(this).attr('name')] = $(this).val();
                }

            });
            $current.attr("custom-data", Base64.encode(JSON.stringify(data)));

            sortableCont.sortable("refresh");
            sortableCont.sortable('option', 'update').call(sortableCont);
            $('#asp_edit_field button[name=back]').click();
        });

        // Reset Values
        $('#asp_new_field button[name=reset]').click(function(){
            $('#asp_new_field input, #asp_new_field select, #asp_new_field textarea').each(function(){
                $(this).val(resetValues[$(this).attr('name')]);
            });
            $('select[name="asp_f_type"]').change();
            initDatePickers();
        });

        initDatePickers();

        $('.asp_f_datepicker_format').on("keyup", function(){
            initDatePickers();
        });
        $('.asp_f_datepicker_defval').on("change", function(){
            initDatePickers();
        });

        $('.asp_f_datepicker_store_format').on("change", function(){
            $(".greenMsg", $(this).parent()).addClass("hiddend");
            $(".greenMsg.msg_" + $(this).val(), $(this).parent()).removeClass("hiddend");
        });
        $('.asp_f_datepicker_store_format').change();

        $('input[name=asp_f_dropdown_search]').change(function(){
            if ( $(this).prop('checked') )
                $('input[name=asp_f_dropdown_search_text]', $(this).parent()).removeAttr('disabled');
            else
                $('input[name=asp_f_dropdown_search_text]', $(this).parent()).attr('disabled', true);
        });
        $('input[name=asp_f_dropdown_search]').change();

    });
</script>
<style>
    .asp_f_datepicker_from_days,
    .asp_f_datepicker_from_months {
        width: 34px !important;
        margin: 0 1px !important;
    }
    .asp_f_datepicker_value {
        margin-bottom: 10px !important;
    }
    .asp_f_datepicker_from {
        display: inline;
        padding: 5px 10px 50px 0px !important;
        margin: 0 !important;
        position: relative;
    }
    .asp_f_datepicker_from .descMsg {
        position: absolute;
    }
    input[name=asp_f_dropdown_search_text] {
        width: 126px !important;
    }
</style>
<div class="wpd-60-pc customContent">

    <fieldset class="wpd-text-right" id="asp_new_field">
        <legend>Add new item</legend>
        <div class='one-item'>
            <label for='asp_f_title'>Title label</label>
            <input type='text' placeholder="Title here.." name='asp_f_title'/>
        </div>
        <div class='one-item'>
            <label for='asp_f_show_title'>Show the label on the frontend?</label>
            <input type='checkbox' name='asp_f_show_title' value="yes" checked/>
        </div>
        <div class='one-item'>
            <label for='asp_f_field'>Custom Field</label>
            <?php new wd_CFSearchCallBack('asp_f_field', '', array('value'=>'', 'args'=>array('controls_position' => 'left', 'class'=>'wpd-text-right'))); ?>
        </div>
        <div class='one-item'>
            <label for='asp_f_type'>Type</label>
            <select name='asp_f_type'/>
            <option value="radio">Radio</option>
            <option value="dropdown">Dropdown</option>
            <option value="checkboxes">Checkboxes</option>
	        <option value="hidden">Hidden</option>
            <option value="text">Text</option>
            <option value="datepicker">DatePicker</option>
            <option value="slider">Slider</option>
            <option value="range">Range Slider</option>
            </select>
        </div>
        <div class='one-item asp_f_radio asp_f_type'>
            <label for='asp_f_radio_value'>Radio values</label>
            <textarea name='asp_f_radio_value'/>
||Any value**
sample_value1||Sample Label 1
sample_value2||Sample Label 2
sample_value3||Sample Label 3</textarea>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
        </div>
        <div class='one-item asp_f_dropdown asp_f_type hiddend'>
            <label for='asp_f_dropdown_multi'>Multiselect?</label>
            <input type='checkbox' name='asp_f_dropdown_multi' value="yes" /><br><br>
            <label for='asp_f_dropdown_search'>Searchable?</label>
            <input type='checkbox' name='asp_f_dropdown_search' value="yes" />
            <label for='asp_f_dropdown_search_text'>placeholder</label>
            <input type='text' name='asp_f_dropdown_search_text' value="Select options.." disabled/><br><br>
            <label for='asp_f_dropdown_value'>Dropdown values</label>
            <textarea name='asp_f_dropdown_value'/>
||Any value**
sample_value1||Sample Label 1
sample_value2||Sample Label 2
sample_value3||Sample Label 3</textarea>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
        </div>
        <div class='one-item asp_f_checkboxes asp_f_type hiddend'>
            <label for='asp_f_checkboxes_value'>Checkbox values</label>
            <textarea name='asp_f_checkboxes_value'/>
sample_value1||Sample Label 1**
sample_value2||Sample Label 2
sample_value3||Sample Label 3**</textarea>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
            <br><br>
            <label for='asp_f_checkboxes_logic'>Checkbox logic</label>
            <select name='asp_f_checkboxes_logic'/>
                <option value="OR">OR</option>
                <option value="AND">AND</option>
            </select>
        </div>
	    <div class='one-item asp_f_hidden asp_f_type'>
		    <label for='asp_f_hidden_value'>Hidden value</label>
		    <textarea name='asp_f_hidden_value'/></textarea>
		    <p class="descMsg">An invisible element. Used for filtering every time without user input.</p>
	    </div>
        <div class='one-item asp_f_text asp_f_type'>
            <label for='asp_f_text_value'>Text input</label>
            <textarea name='asp_f_text_value'/></textarea>
            <p class="descMsg">A text input element.</p>
        </div>
        <div class='one-item asp_f_datepicker asp_f_type'>
            <label for='asp_f_datepicker_store_format'>Date storage format</label>
            <select class="asp_f_datepicker_store_format" name="asp_f_datepicker_store_format">
                <option value="datetime">MySQL DateTime/ACF datetime field</option>
                <option value="acf">ACF date field</option>
                <option value="timestamp">Timestamp</option>
            </select>
            <p class="greenMsg msg_acf">
                NOTICE: The save format must be <strong>yymmdd</strong> <a href="http://i.imgur.com/JrSKoGP.png" target="_blank">on the ACF options.</a>
            </p>
            <p class="greenMsg msg_datetime">
                NOTICE: The MySql datetime format is <strong>Y-m-d H:i:s</strong>, for example: 2001-03-10 17:16:18
            </p>
            <p class="greenMsg msg_timestamp">
                NOTICE: The timestamp is a numeric format, for example <strong>1465111713</strong>. This translates to: 06/05/2016 @ 7:28am (UTC)
            </p>
            <label for='asp_f_datepicker_format'>Display format</label><br>
            <input style="width:120px;" name='asp_f_datepicker_format' class="asp_f_datepicker_format" value="dd/mm/yy"/>
            <p class="descMsg">dd/mm/yy is the most used format, <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">list of accepted params</a></p>

            <label for='asp_f_datepicker_value'>Default Value</label><br>
            <select class="asp_f_datepicker_defval" name="asp_f_datepicker_defval">
                <option value="current">Current date</option>
                <option value="relative">Relative date</option>
                <option value="selected">Select date</option>
            </select>
            <input class="asp_f_datepicker_value" name='asp_f_datepicker_value' value=""/>
            <fieldset class="asp_f_datepicker_from hiddend">
                <input class="asp_f_datepicker_from_days" name='asp_f_datepicker_from_days' value="0"/> days and
                <input class="asp_f_datepicker_from_months" name='asp_f_datepicker_from_months' value="0"/> months from now.
                <p class="descMsg">Use <strong>negative values</strong> to indicate date before the current.</p>
            </fieldset>
            <br>
            <label for='asp_f_datepicker_operator'>Show results..</label>
            <select name='asp_f_datepicker_operator'/>
            <option value="before">..before the date (to date)</option>
            <option value="after">..after the date (from date)</option>
            <option value="match">..matching the date</option>
            <option value="nomatch">..not matching the date</option>
            </select>
        </div>
        <div style='line-height: 33px;' class='one-item asp_f_slider asp_f_type hiddend'>
            <label for='asp_f_slider_from'>Slider range</label>
            <input class="threedigit" type='text' value="1" name='asp_f_slider_from'/> - <input class="threedigit" value="1000" type='text' name='asp_f_slider_to'/><br />
            <p class="descMsg">Leave them empty to get the values automatically.</p>
            <label for='asp_f_slider_step'>Step</label>
            <input class="threedigit" type='text' value="1" name='asp_f_slider_step'/><br />
            <label for='asp_f_slider_prefix'>Prefix</label>
            <input class="threedigit" type='text' value="$" name='asp_f_slider_prefix'/>
            <label for='asp_f_slider_suffix'>Suffix</label>
            <input class="threedigit" type='text' value=",-" name='asp_f_slider_suffix'/><br />
            <label for='asp_f_slider_default'>Default Value</label>
            <input class="threedigit" type='text' value="500" name='asp_f_slider_default'/><br />
            <label for='asp_f_slider_t_separator'>Thousands separator</label>
            <input class="threedigit" type='text' value=" " name='asp_f_slider_t_separator'/>
            <label for='asp_f_slider_decimals'>Decimal places</label>
            <input class="threedigit" type='text' value="0" name='asp_f_slider_decimals'/>
        </div>
        <div style='line-height: 33px;' class='one-item asp_f_range asp_f_type hiddend'>
            <label for='asp_f_range_from'>Slider range</label>
            <input class="threedigit" type='text' value="1" name='asp_f_range_from'/> - <input class="threedigit" value="1000" type='text' name='asp_f_range_to'/><br />
            <p class="descMsg">Leave them empty to get the values automatically.</p>
            <label for='asp_f_slider_step'>Step</label>
            <input class="threedigit" type='text' value="1" name='asp_f_range_step'/><br />
            <label for='asp_f_slider_prefix'>Prefix</label>
            <input class="threedigit" type='text' value="$" name='asp_f_range_prefix'/>
            <label for='asp_f_slider_suffix'>Suffix</label>
            <input class="threedigit" type='text' value=",-" name='asp_f_range_suffix'/><br />
            <label for='asp_f_range_default1'>Track 1 default</label>
            <input class="threedigit" type='text' value="250" name='asp_f_range_default1'/>
            <label for='asp_f_range_default2'>Track 2 default</label>
            <input class="threedigit" type='text' value="750" name='asp_f_range_default2'/>
            <label for='asp_f_range_t_separator'>Thousands separator</label>
            <input class="threedigit" type='text' value=" " name='asp_f_range_t_separator'/>
            <label for='asp_f_range_decimals'>Decimal places</label>
            <input class="threedigit" type='text' value="0" name='asp_f_range_decimals'/>
        </div>
        <div class='one-item asp_f_operator'>
            <label for='asp_f_operator'>Operator</label>
            <select name='asp_f_operator'/>
            <optgroup label="Numeric operators">
                <option value="eq">EQUALS</option>
                <option value="neq">NOT EQUALS</option>
                <option value="lt">LESS THEN</option>
                <option value="let">LESS OR EQUALS THEN</option>
                <option value="gt">MORE THEN</option>
                <option value="get">MORE OR EQUALS THEN</option>
            </optgroup>
            <optgroup label="String operators">
                <option value="elike">EXACTLY LIKE</option>
                <option value="like" selected="selected">LIKE</option>
            </optgroup>
            </select>
            <p class="descMsg">Use the numeric operators for numeric values and string operators for text values.</p>
        </div>
        <div class='one-item'>
            <button type='button' style='margin-right: 20px;' name='reset'>Reset</button>
            <button type='button' name='add'>Add!</button>
        </div>
    </fieldset>

    <fieldset class="wpd-text-right" style="display:none;" id="asp_edit_field">
        <legend>Edit: <strong><span id="asp_edit_title"></span></strong></legend>
        <div class='one-item'>
            <label for='asp_f_title'>Title label</label>
            <input type='text' placeholder="Title here.." name='asp_f_title'/>
        </div>
        <div class='one-item'>
            <label for='asp_f_show_title'>Show the label on the frontend?</label>
            <input type='checkbox' name='asp_f_show_title' value="yes" checked/>
        </div>
        <div class='one-item'>
            <label for='asp_f_field'>Custom Field</label>
            <?php new wd_CFSearchCallBack('asp_f_field', '', array('value'=>'', 'args'=>array('controls_position' => 'left', 'class'=>'wpd-text-right'))); ?>
        </div>
        <div class='one-item'>
            <label for='asp_f_type'>Type</label>
            <select name='asp_f_type'/>
            <option value="radio">Radio</option>
            <option value="dropdown">Dropdown</option>
            <option value="checkboxes">Checkboxes</option>
	        <option value="hidden">Hidden</option>
            <option value="text">Text</option>
            <option value="datepicker">DatePicker</option>
            <option value="slider">Slider</option>
            <option value="range">Range Slider</option>
            </select>
        </div>
        <div class='one-item asp_f_radio asp_f_type'>
            <label for='asp_f_radio_value'>Radio values</label>
            <textarea name='asp_f_radio_value'/></textarea>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
        </div>
        <div class='one-item asp_f_dropdown asp_f_type hiddend'>
            <label for='asp_f_dropdown_multi'>Multiselect?</label>
            <input type='checkbox' name='asp_f_dropdown_multi' value="yes" /><br><br>
            <label for='asp_f_dropdown_search'>Searchable?</label>
            <input type='checkbox' name='asp_f_dropdown_search' value="yes" />
            <label for='asp_f_dropdown_search_text'>placeholder</label>
            <input type='text' name='asp_f_dropdown_search_text' value="Select options.." disabled/><br><br>
            <label for='asp_f_dropdown_value'>Dropdown values</label>
            <textarea name='asp_f_dropdown_value'/></textarea>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
        </div>
        <div class='one-item asp_f_checkboxes asp_f_type hiddend'>
            <label for='asp_f_checkboxes_value'>Checkbox values</label>
            <textarea name='asp_f_checkboxes_value'/></textarea><br><br>
            <p class="descMsg"><?php echo $cf_tooltip_msg; ?></p>
            <label for='asp_f_checkboxes_logic'>Checkbox logic</label>
            <select name='asp_f_checkboxes_logic'/>
                <option value="OR">OR</option>
                <option value="AND">AND</option>
            </select>
        </div>
	    <div class='one-item asp_f_hidden asp_f_type'>
            <label for='asp_f_hidden_value'>Hidden value</label>
            <textarea name='asp_f_hidden_value'/></textarea>
            <p class="descMsg">An invisible element. Used for filtering every time without user input.</p>
        </div>
        <div class='one-item asp_f_text asp_f_type'>
            <label for='asp_f_text_value'>Text input</label>
            <textarea name='asp_f_text_value'/></textarea>
            <p class="descMsg">A text input element.</p>
        </div>
        <div class='one-item asp_f_datepicker asp_f_type'>
            <label for='asp_f_datepicker_store_format'>Date storage format</label>
            <select class="asp_f_datepicker_store_format" name="asp_f_datepicker_store_format">
                <option value="datetime">MySQL DateTime/ACF datetime field</option>
                <option value="acf">ACF date field</option>
                <option value="timestamp">Timestamp</option>
            </select>
            <p class="greenMsg msg_acf">
                NOTICE: The save format must be <strong>yymmdd</strong> <a href="http://i.imgur.com/JrSKoGP.png" target="_blank">on the ACF options.</a>
            </p>
            <p class="greenMsg msg_datetime">
                NOTICE: The MySql datetime format is <strong>Y-m-d H:i:s</strong>, for example: 2001-03-10 17:16:18
            </p>
            <p class="greenMsg msg_timestamp">
                NOTICE: The timestamp is a numeric format, for example <strong>1465111713</strong>. This translates to: 06/05/2016 @ 7:28am (UTC)
            </p>
            <label for='asp_f_datepicker_format'>Display format</label><br>
            <input style="width:120px;" name='asp_f_datepicker_format' class="asp_f_datepicker_format" value="dd/mm/yy"/>
            <p class="descMsg">dd/mm/yy is the most used format, <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">list of accepted params</a></p>

            <label for='asp_f_datepicker_value'>Default Value</label><br>
            <select class="asp_f_datepicker_defval" name="asp_f_datepicker_defval">
                <option value="current">Current date</option>
                <option value="relative">Relative date</option>
                <option value="selected">Select date</option>
            </select>
            <input class="asp_f_datepicker_value" name='asp_f_datepicker_value' value=""/>
            <fieldset class="asp_f_datepicker_from hiddend">
                <input class="asp_f_datepicker_from_days" name='asp_f_datepicker_from_days' value="0"/> days and
                <input class="asp_f_datepicker_from_months" name='asp_f_datepicker_from_months' value="0"/> months from now.
                <p class="descMsg">Use <strong>negative values</strong> to indicate date before the current.</p>
            </fieldset>
            <br>
            <label for='asp_f_datepicker_operator'>Show results..</label>
            <select name='asp_f_datepicker_operator'/>
            <option value="before">..before the date (to date)</option>
            <option value="after">..after the date (from date)</option>
            <option value="match">..matching the date</option>
            <option value="nomatch">..not matching the date</option>
            </select>
        </div>
        <div style='line-height: 33px;' class='one-item asp_f_slider asp_f_type hiddend'>
            <label for='asp_f_slider_from'>Slider range</label>
            <input class="threedigit" type='text' value="" name='asp_f_slider_from'/> - <input class="threedigit" value="" type='text' name='asp_f_slider_to'/><br />
            <p class="descMsg">Leave them empty to get the values automatically.</p>
            <label for='asp_f_slider_step'>Step</label>
            <input class="threedigit" type='text' value="1" name='asp_f_slider_step'/><br />
            <label for='asp_f_slider_prefix'>Prefix</label>
            <input class="threedigit" type='text' value="$" name='asp_f_slider_prefix'/>
            <label for='asp_f_slider_suffix'>Suffix</label>
            <input class="threedigit" type='text' value=",-" name='asp_f_slider_suffix'/><br />
            <label for='asp_f_slider_default'>Default Value</label>
            <input class="threedigit" type='text' value="" name='asp_f_slider_default'/><br />
            <label for='asp_f_slider_t_separator'>Thousands separator</label>
            <input class="threedigit" type='text' value=" " name='asp_f_slider_t_separator'/>
            <label for='asp_f_slider_decimals'>Decimal places</label>
            <input class="threedigit" type='text' value="0" name='asp_f_slider_decimals'/>
        </div>
        <div style='line-height: 33px;' class='one-item asp_f_range asp_f_type hiddend'>
            <label for='asp_f_range_from'>Slider range</label>
            <input class="threedigit" type='text' value="" name='asp_f_range_from'/> - <input class="threedigit" value="" type='text' name='asp_f_range_to'/><br />
            <p class="descMsg">Leave them empty to get the values automatically.</p>
            <label for='asp_f_slider_step'>Step</label>
            <input class="threedigit" type='text' value="1" name='asp_f_range_step'/><br />
            <label for='asp_f_slider_prefix'>Prefix</label>
            <input class="threedigit" type='text' value="$" name='asp_f_range_prefix'/>
            <label for='asp_f_slider_suffix'>Suffix</label>
            <input class="threedigit" type='text' value=",-" name='asp_f_range_suffix'/><br />
            <label for='asp_f_range_default1'>Track 1 default</label>
            <input class="threedigit" type='text' value="" name='asp_f_range_default1'/>
            <label for='asp_f_range_default2'>Track 2 default</label>
            <input class="threedigit" type='text' value="" name='asp_f_range_default2'/>
            <label for='asp_f_range_t_separator'>Thousands separator</label>
            <input class="threedigit" type='text' value=" " name='asp_f_range_t_separator'/>
            <label for='asp_f_range_decimals'>Decimal places</label>
            <input class="threedigit" type='text' value="0" name='asp_f_range_decimals'/>
        </div>
        <div class='one-item asp_f_operator'>
            <label for='asp_f_operator'>Operator</label>
            <select name='asp_f_operator'/>
            <optgroup label="Numeric operators">
                <option value="eq">EQUALS</option>
                <option value="neq">NOT EQUALS</option>
                <option value="lt">LESS THEN</option>
                <option value="let">LESS OR EQUALS THEN</option>
                <option value="gt">MORE THEN</option>
                <option value="get">MORE OR EQUALS THEN</option>
            </optgroup>
            <optgroup label="String operators">
                <option value="elike">EXACTLY LIKE</option>
                <option value="like">LIKE</option>
            </optgroup>
            </select>
            <p class="descMsg">Use the numeric operators for numeric values and string operators for text values.</p>
        </div>
        <div class='one-item'>
            <button type='button' style='margin-right: 20px;' name='back'>Back</button>
            <button type='button' name='save'>Save!</button>
        </div>
    </fieldset>

    <input type="hidden" name="custom_field_items" value="<?php
        if (isset($_POST['custom_field_items']))
            echo $_POST['custom_field_items'];
        else
            echo $sd['custom_field_items'];

    ?>" />
</div>
<div class="wpd-40-pc customFieldsSortable">
    <div class="sortablecontainer">
        <ul id="csf_sortable">

        </ul>
    </div>
</div>