var $jQ = jQuery.noConflict();
$jQ(document).ready(function(){

    var bodyHeight = $jQ(document).height();
    var adminmenu_height = $jQ('#adminmenuwrap').height();
    if(bodyHeight > adminmenu_height){
        $jQ("#datamain").height(bodyHeight);
    }
    else
    {
        $jQ("#datamain").height(adminmenu_height);
    }


    var normal_attributes = [];

    var category_attributes = [];

    function isValidEmailAddress(emailAddress) {

        var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        return pattern.test(emailAddress);
    }

    function change_field_attr(){
        var attr_val = $jQ('#sib_sel_attribute').val();
        var attr_type, attr_name, attr_text;
        if (attr_val == 'email' || attr_val == 'submit') {
            // get all info of attr
            var hidden_attr = $jQ('#sib_hidden_' + attr_val);
            attr_type = hidden_attr.attr('data-type');
            attr_name = hidden_attr.attr('data-name');
            attr_text = hidden_attr.attr('data-text');
        }
        else {
            $jQ.each(normal_attributes, function(index, value) {
                if (value['name'] == attr_val) {
                    attr_type = value['type'];
                    attr_name = value['name'];
                    attr_text = attr_name;
                }
            });

            $jQ.each(category_attributes, function(index, value) {
                if (value['name'] == attr_val) {
                    attr_type = value['type'];
                    attr_name = value['name'];
                    attr_text = attr_name;
                }
            });
        }

        // generate attribute html
        generate_attribute_html(attr_type, attr_name, attr_text);
    }

    function change_attribute_tag(attr_type, attr_name, attr_text){
        $jQ('#sib_field_label').attr('value', attr_text);
        $jQ('#sib_field_placeholder').attr('value', '');
        $jQ('#sib_field_initial').attr('value', '');
        $jQ('#sib_field_button_text').attr('value', attr_text);
        $jQ('.sib-attr-other').hide();
        $jQ('.sib-attr-normal').hide();
        $jQ('.sib-attr-category').hide();
        $jQ('#sib_field_required').removeAttr('checked');
        var dateformat = $jQ('.sib-dateformat').val();
        switch(attr_type)
        {
            case 'email':
                $jQ('#sib_field_required').attr('checked', 'true');
                dateformat = '';
            case 'date':
                $jQ('#sib_field_placeholder').val(dateformat);
            case 'text':
            case 'float':
                $jQ('.sib-attr-normal').show();
                break;
            case 'category':
                $jQ('.sib-attr-category').show();
                break;
            case 'submit':
                $jQ('.sib-attr-other').show();
                break;
        }
    }

    // generate attribute html
    function generate_attribute_html(attr_type, attr_name, attr_text){
        var field_label = $jQ('#sib_field_label').val();
        var field_placeholder = $jQ('#sib_field_placeholder').val();
        var field_initial = $jQ('#sib_field_initial').val();
        var field_buttontext = $jQ('#sib_field_button_text').val();
        //var field_wrap = $jQ('#sib_field_wrap').is(':checked');
        var field_required = $jQ('#sib_field_required').is(':checked');
        if(field_required == true) field_label += '*';
        var field_type = $jQ('input[name=sib_field_type]:checked').val();
        var dateformat = $jQ('.sib-dateformat').val();
        var field_html = '';

        if(attr_type != 'submit') {
            field_html += '<p class="sib-' + attr_name + '-area"> \n';
        }
        else {
            field_html += '<p> \n';
        }

        if ((field_label != '') && (attr_type == 'category')) {
            if (field_type == 'select') {
                field_html += '    <label class="sib-' + attr_name + '-area">' + field_label + '</label> \n';
            }
            else {
                field_html += '    <div style="display:block;"><label class="sib-' + attr_name + '-area">' + field_label + '</label></div> \n';
            }
        }
        else if((field_label != '') && (attr_type != 'submit')) {
            field_html += '    <label class="sib-' + attr_name + '-area">' + field_label + '</label> \n';
        }


        switch (attr_type)
        {
            case 'email':
                field_html += '    <input type="email" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                field_html += 'placeholder="' + field_placeholder + '" ';
                field_html += 'value="' + field_initial + '" ';
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += '> \n';
                break;
            case 'date':
                field_html += '    <input type="text" class="sib-' + attr_name + '-area sib-date" name="' + attr_name + '" placeholder="' + dateformat + '" data-format="' + dateformat + '">';
                break;
            case 'text':
                field_html += '    <input type="text" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                if(field_placeholder != '') {
                    field_html += 'placeholder="' + field_placeholder + '" ';
                }
                if(field_initial != '') {
                    field_html += 'value="' + field_initial + '" ';
                }
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += '> \n';
                break;
            case 'float':
                field_html += '    <input type="text" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                if(field_placeholder != '') {
                    field_html += 'placeholder="' + field_placeholder + '" ';
                }
                if(field_initial != '') {
                    field_html += 'value="' + field_initial + '" ';
                }
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += 'pattern="[0-9]+([\\.|,][0-9]+)?" > \n';
                break;
            case 'submit':
                field_html += '    <input type="submit" class="sib-default-btn" name="' + attr_name + '" ';
                field_html += 'value="' + field_buttontext + '" ';
                field_html += '> \n';
                break;
            case 'category':
                var enumeration = [];
                $jQ.each(category_attributes, function(index, value) {
                    if (value['name'] == attr_name) {
                        enumeration = value['enumeration'];
                    }
                });

                if (field_type == 'select') {
                    field_html += '    <select class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                    if (field_required == true) {
                        field_html += 'required="required" ';
                    }
                    field_html += '> \n';
                }
                $jQ.each(enumeration, function(index, value) {
                    if (field_type == 'select') {
                        field_html += '      <option value="' + value['value'] + '">' + value['label'] + '</option> \n';
                    }
                    else {
                        field_html += '    <div style="display:block;"><input type="radio" class="sib-' + attr_name + '-area" name="' + attr_name + '" value="' + value['value'] + '" ';
                        if (field_required == true) {
                            field_html += 'required="required" ';
                        }
                        field_html += '>' + value['label'] + '</div> \n';
                    }
                });
                if (field_type == 'select') {
                    field_html += '    </select> \n';
                }
                break;
        }

        field_html += '</p>';

        $jQ('#sib_field_html').html(field_html);
    }

    function set_select_list() {
        var selected_list_id = $jQ('#sib_selected_list_id').val();

        var data = {
            frmid : $jQ('input[name=sib_form_id]').val(),
            action : 'sib_get_lists',
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {
            var select_html = '';
            var selected = respond.selected;

            $jQ.each(respond.lists, function(index, value) {
                if(value['name'] == 'Temp - DOUBLE OPTIN') return true;
                if ( selected.indexOf(value['id'].toString()) != '-1' ) {
                    select_html += '<option value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            $jQ('#sib_select_list').html(select_html).trigger("chosen:updated");

            set_select_attributes();

        });
    }

    function set_select_template() {
        var selected_template_id = $jQ('#sib_selected_template_id').val();
        var selected_do_template_id = $jQ('#sib_selected_do_template_id').val();
        var default_template_name = $jQ('#sib_default_template_name').val();
        var data = {
            action : 'sib_get_templates',
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {
            var select_html = '<select id="sib_template_id" class="col-md-11" name="template_id">';
            if (selected_template_id == '-1') {
                select_html += '<option value="-1" selected>' + default_template_name + '</option>';
            }
            else {
                select_html += '<option value="-1">' + default_template_name + '</option>';
            }
            $jQ.each(respond.templates, function(index, value) {
                if (value['id'] == selected_template_id) {
                    select_html += '<option value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            select_html += '</select>';
            $jQ('#sib_template_id_area').html(select_html);

            // For double optin.
            select_html = '<select class="col-md-11" name="doubleoptin_template_id" id="sib_doubleoptin_template_id">';
            if (selected_do_template_id == '-1') {
                select_html += '<option value="-1" selected>' + default_template_name + '</option>';
            }
            else {
                select_html += '<option value="-1">' + default_template_name + '</option>';
            }
            $jQ.each(respond.templates, function(index, value) {
                if (value['id'] == selected_do_template_id) {
                    select_html += '<option is_shortcode="' + value['is_dopt']  + '" value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option is_shortcode="' + value['is_dopt']  + '" value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            select_html += '</select>';
            $jQ('#sib_doubleoptin_template_id_area').html(select_html);
            // double optin template id
            $jQ('#sib_doubleoptin_template_id').on('change', function() {
                var shortcode_exist = $jQ(this).find(':selected').attr('is_shortcode');
                if (shortcode_exist == 0 && $jQ(this).val() != -1) {
                    $jQ('#sib_form_alert_message').show();
                    $jQ('#sib_disclaim_smtp').hide();
                    $jQ('#sib_disclaim_do_template').show();
                    $jQ(this).val('-1');
                }
                else {
                    $jQ('#sib_form_alert_message').hide();
                }
            });

            $jQ('#sib_setting_signup_spin').addClass('hide');
           
        });
    }

    function set_select_attributes() {
        var data = {
            action : 'sib_get_attributes',
            security: ajax_sib_object.ajax_nonce
        };

        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {

            var iframWidth = $jQ('.form-field').width() - 48;
            $jQ('#sib-preview-form').width(iframWidth);

            normal_attributes = respond.attrs.attributes.normal_attributes;
            category_attributes = respond.attrs.attributes.category_attributes;
            var attr_email_name = $jQ('#sib_hidden_email').attr('data-text');
            var message_1 = $jQ('#sib_hidden_message_1').val();
            var message_2 = $jQ('#sib_hidden_message_2').val();
            var message_3 = $jQ('#sib_hidden_message_3').val();
            var message_4 = $jQ('#sib_hidden_message_4').val();
            var message_5 = $jQ('#sib_hidden_message_5').val();
            var select_html = '<select class="col-md-12" id="sib_sel_attribute">' +
                '<option value="-1" disabled selected>' + message_1 + '</option>' +
                '<optgroup label="' + message_2 + '">';
            select_html += '<option value="email">' + attr_email_name + '*</option>';
            $jQ.each(normal_attributes, function(index, value) {
                select_html += '<option value="' + value['name'] + '">' + value['name'] + '</option>';
            });
            select_html += '</optgroup>';
            select_html += '<optgroup label="' + message_3 + '">';
            $jQ.each(category_attributes, function(index, value) {
                if(value['name'] == 'DOUBLE_OPT-IN') return;
                select_html += '<option value="' + value['name'] + '">' + value['name'] + '</option>';
            });
            select_html += '</optgroup>';
            select_html += '<optgroup label="' + message_4 + '">';
            select_html += '<option value="submit">' + message_5 + '</option>';
            select_html += '</optgroup>';
            select_html += '</select>';

            $jQ('#sib_sel_attribute_area').html(select_html);
            $jQ('#sib_sel_attribute').on('change', function() {
                //
                $jQ('#sib-field-content').show();

                var attr_val = $jQ(this).val();
                var attr_type, attr_name, attr_text;
                if (attr_val == 'email' || attr_val == 'submit') {
                    // get all info of attr
                    var hidden_attr = $jQ('#sib_hidden_' + attr_val);
                    attr_type = hidden_attr.attr('data-type');
                    attr_name = hidden_attr.attr('data-name');
                    attr_text = hidden_attr.attr('data-text');
                }
                else {
                    $jQ.each(normal_attributes, function(index, value) {
                        if (value['name'] == attr_val) {
                            attr_type = value['type'];
                            attr_name = value['name'];
                            attr_text = attr_name;
                        }
                    });

                    $jQ.each(category_attributes, function(index, value) {
                        if (value['name'] == attr_val) {
                            attr_type = value['type'];
                            attr_name = value['name'];
                            attr_text = attr_name;
                        }
                    });
                }
                // change attribute tags
                change_attribute_tag(attr_type, attr_name, attr_text);

                // generate attribute html
                generate_attribute_html(attr_type, attr_name, attr_text);
            });
            $jQ('#sib_setting_form_spin').addClass('hide');
            set_select_template();
        });
    }

    function update_preview(){

        var frmid = $jQ('#sib_form_id').val();
        var formHtml = $jQ('#sibformmarkup').val();
        var formCss = $jQ('#sibcssmarkup').val();
        var isDepend = $jQ('input[name=sib_css_type]:checked').val();
        var gCaptcha = $jQ('input[name=sib_add_captcha]:checked').val();
        var gCaptchaType = $jQ('input[name=sib_recaptcha_type]:checked').val();
        var gCaptchaSite = $jQ('#sib_captcha_site').val();
        var data = {
            action:'sib_update_form_html',
            security: ajax_sib_object.ajax_nonce,
            frmid: frmid,
            frmData: formHtml,
            frmCss: formCss,
            isDepend: isDepend,
            gCaptcha: gCaptcha,
            gCaptchaType: gCaptchaType,
            gCaptchaSite: gCaptchaSite
        };
        $jQ.post(ajax_sib_object.ajax_url, data,function() {
            var preview_form = $jQ('#sib-preview-form');
            preview_form.attr('src', preview_form.attr('src') + '&action=update');
        });
    }
    // get cursor posistion of text area
    function get_cursor_position(node) {
        //node.focus();
        /* without node.focus() IE will returns -1 when focus is not on node */
        if(node.selectionStart) return node.selectionStart;
        else if(!document.selection) return 0;
        var c		= "\001";
        var sel	= document.selection.createRange();
        var dul	= sel.duplicate();
        dul.moveToElementText(node);
        sel.text	= c;
        var len		= (dul.text.indexOf(c));
        sel.moveStart('character',-1);
        sel.text	= "";
        return len;
    }
    // set cursor position at top of text area
    function setSelectionRange(input, selectionStart, selectionEnd) {
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        } else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
    }

    /////////////////////////////////
    /*       home settings         */
    /////////////////////////////////

    // var elements
    var sib_access_key = $jQ('#sib_access_key');
    var sib_validate_btn = $jQ('#sib_validate_btn');

    // validate button click process in welcome page
    sib_validate_btn.on('click', function(){

        var access_key = sib_access_key.val();

        // check validation
        var error_flag = 0;
        if(access_key == '') {
            sib_access_key.addClass('error');
            error_flag =1;
        }

        if(error_flag != 0) {
            return false;
        }

        // ajax process for validate
        var data = {
            action:'sib_validate_process',
            access_key: access_key,
            security: ajax_sib_object.ajax_nonce
        };

        $jQ('.sib_alert').hide();
        $jQ('.sib-spin').show();
        sib_access_key.removeClass('error');
        $jQ(this).attr('disabled', 'true');

        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {
            $jQ('.sib-spin').hide();
            sib_validate_btn.removeAttr('disabled');
            if(respond == 'success') {
                $jQ('#success-alert').show();
                /*var cur_url = $jQ('#cur_refer_url').val();
                window.location.href = cur_url;*/
                window.location.reload();
            }
            else if (respond == 'curl_no_installed') {
                sib_access_key.addClass('error');
                $jQ('#failure-alert').html($jQ('#curl_no_exist_error').val()).show();
            }
            else if (respond == 'curl_error') {
                sib_access_key.addClass('error');
                $jQ('#failure-alert').html($jQ('#curl_error').val()).show();
            }           
            else {
                sib_access_key.addClass('error');
                $jQ('#failure-alert').html($jQ('#general_error').val()).show();
            }
        });
    });

    sib_access_key.on('keypress', function(){
        $jQ(this).removeClass('error');
    });

    // Transactional emails
    $jQ('input[name=activate_email]').on('click', function(){
        var option_val = $jQ(this).val();
        var data = {
            action: 'sib_activate_email_change',
            option_val: option_val,
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {
            if(respond == 'yes')
                $jQ('#email_send_field').show();
            else
                $jQ('#email_send_field').hide();
        });

        return true;
    });

    // change sender detail
    $jQ('#sender_list').on('change',function(){
        var data = {
            action: 'sib_sender_change',
            sender: $jQ(this).val(),
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data, function() {
            $jQ(this).blur();
        });

        return true;
    });

    // validate MA
    $jQ('#validate_ma_btn').on('click',function(){
        var option_val = $jQ('input[name=activate_ma]:checked').val();
        var data = {
            action:'sib_validate_ma',
            option_val: option_val,
            security: ajax_sib_object.ajax_nonce
        };
        var uninstall = false;
        var uninstallMsg = $jQ('#sib-ma-unistall').val();
        if(option_val != 'yes'){
            uninstall = confirm(uninstallMsg);
        }
        if(option_val == 'yes' || uninstall) {
            $jQ(this).find('.sib-spin').show();
            $jQ('.sib-ma-alert').hide();
            $jQ(this).attr('disabled', 'true');
            $jQ.post(ajax_sib_object.ajax_url, data, function (respond) {
                $jQ('.sib-spin').hide();
                $jQ('#validate_ma_btn').removeAttr('disabled');
                if (respond == 'yes') {
                    $jQ('.sib-ma-active').show();
                } else if(respond == 'no') {
                    $jQ('.sib-ma-inactive').show();
                } else if(respond == 'disabled'){
                    $jQ('.sib-ma-disabled').show();
                    $jQ('#activate_ma_radio_no').prop('checked', true);
                }
                setTimeout(function(){
                    if(respond != 'disabled')
                        window.location.reload();
                },2000);

            });
        }
    });

    // send activate email button
    $jQ('#send_email_btn').on('click',function(){
        var activate_email = $jQ('#activate_email');
        var email = activate_email.val();
        if(email == '' || isValidEmailAddress(email) != true) {
            activate_email.removeClass('has-success').addClass('error');
            $jQ('#failure-alert').show();
            return false;
        }
        $jQ(this).attr('disabled', 'true');

        var data = {
            action:'sib_send_email',
            email:email,
            security: ajax_sib_object.ajax_nonce
        };

        $jQ('.sib_alert').hide();
        activate_email.removeClass('error');
        $jQ(this).find('.sib-spin').show();
        $jQ.post(ajax_sib_object.ajax_url, data,function(respond) {
            $jQ('.sib-spin').hide();
            $jQ('#send_email_btn').removeAttr('disabled');
            if(respond != 'success') {
                $jQ('#activate_email').removeClass('has-success').addClass('error');
                $jQ('#failure-alert').show();
            } else {
                $jQ('#success-alert').show();
            }
        });
    });

    ////////////////////////////////
    /*       manage forms         */
    ////////////////////////////////

    $jQ('#sib-field-content').hide();

    // check confirm email
    var is_send_confirm_email = $jQ("input[name=is_confirm_email]:checked").val();

    if(is_send_confirm_email == '1') {
        $jQ('#sib_confirm_template_area').show();
        $jQ('#sib_confirm_sender_area').show();
    } else {
        $jQ('#sib_confirm_template_area').hide();
        $jQ('#sib_confirm_sender_area').hide();
    }

    // check double optin
    var is_double_optin = $jQ("input[name=is_double_optin]:checked").val();

    if(is_double_optin == '1') {
        $jQ('#is_confirm_email_no').prop("checked", true);
        $jQ('#sib_confirm_template_area').hide();
        $jQ('#sib_confirm_sender_area').hide();
        $jQ('#sib_double_sender_area').show();
        $jQ('#sib_doubleoptin_template_area').show();

    } else {
        $jQ('#sib_double_sender_area').hide();
        $jQ('#sib_double_redirect_area').hide();
        $jQ('#sib_doubleoptin_template_area').hide();
    }

    if ($jQ('#sib_setting_signup_body').find('#sib_select_list_area').length > 0 ) {
        set_select_list();
        $jQ('#sib_select_list').chosen({width:"100%"});
    }

    // keep change of fields
    $jQ('.sib_field_changes').on('change',function() {
        change_field_attr();
    });

    // click confirm email
    $jQ("input[name=is_confirm_email]").on('click',function() {
        var confirm_email = $jQ(this).val();
        var is_activated_smtp = parseInt($jQ("#is_smtp_activated").val());

        if(confirm_email == '1') {
            $jQ('#sib_doubleoptin_template_id').val('-1');
            $jQ('#is_double_optin_no').prop("checked", true);
            $jQ('#sib_double_sender_area').hide();
            $jQ('#sib_double_redirect_area').hide();
            $jQ('#sib_confirm_template_area').show();
            $jQ('#sib_confirm_sender_area').show();
            $jQ('#sib_doubleoptin_template_area').hide();
            if (is_activated_smtp == 0) {
                $jQ('#sib_form_alert_message').show();
                $jQ('#sib_disclaim_smtp').show();
                $jQ('#sib_disclaim_do_template').hide();
            }
        } else {
            $jQ('#sib_confirm_template_area').hide();
            $jQ('#sib_confirm_sender_area').hide();
            $jQ('#sib_form_alert_message').hide();
        }
    });

    // click double optin
    $jQ('input[name=is_double_optin]').on('click', function() {
        var double_optin = $jQ(this).val();
        var is_activated_smtp = parseInt($jQ("#is_smtp_activated").val());
        if(double_optin == '1') {
            $jQ('#sib_template_id').val('-1');
            $jQ('#is_confirm_email_no').prop("checked", true);
            $jQ('#sib_confirm_template_area').hide();
            $jQ('#sib_confirm_sender_area').hide();
            $jQ('#sib_double_sender_area').show();
            $jQ('#sib_double_redirect_area').show();
            $jQ('#sib_doubleoptin_template_area').show();
            if (is_activated_smtp == 0) {
                $jQ('#sib_form_alert_message').show();
                $jQ('#sib_disclaim_smtp').show();
                $jQ('#sib_disclaim_do_template').hide();
            }
        } else {
            $jQ('#sib_double_sender_area').hide();
            $jQ('#sib_double_redirect_area').hide();
            $jQ('#sib_doubleoptin_template_area').hide();
            $jQ('#sib_form_alert_message').hide();
        }
    });

    // click redirect url
    $jQ('#is_redirect_url_click_yes').on('click', function () {
        $jQ('#sib_subscrition_redirect_area').show();
    });
    $jQ('#is_redirect_url_click_no').on('click', function () {
        $jQ('#sib_subscrition_redirect_area').hide();
    });

    //// refresh iframe to preview form
    $jQ('#sib-preview-form-refresh').on('click',function(){
        // ajax to update form html
        update_preview();
    });

    //// display popup when delete form
    $jQ('.sib-form-delete').on('click', function(e) {
        return confirm('Are you sure you want to delete this form?');
    });

    //// custom or theme's css
    $jQ('input[name=sib_css_type]').on('change',function() {
        $jQ('#sibcssmarkup').toggle();
        update_preview();
    });

   // remove all transients
    $jQ(window).focus(function() {

        var data = {
            action: 'sib_remove_cache',
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data,function(respond) {

            if(respond == 'success') {
                //
            }
        });
    });

    /* sync wordpress users to sendinblue contact list */
    // sync popup
    $jQ('#sib-sync-btn').on('click', function() {
        var syncModal = $jQ('.sib-sync-modal');
        syncModal.modal();
        $jQ('#sync-failure').hide();

        // add to multilist field
        var list = $jQ('#sib_select_list');
        list[0].selectedIndex = 0;
        list.chosen({width:"100%"});

        syncModal.on('hidden.bs.modal', function () {
            //window.location.reload();
        });
    });

    var attrFieldLine = $jQ('.sync-attr-line').html();
    // sync add attr line filed
    $jQ('.modal-body').on('click', '.sync-attr-plus', function(){
        $jQ('.sync-attr-line').append(attrFieldLine);
        $jQ('.sync-attr-dismiss').show();
    });
    // sync dismiss attr line filed
    $jQ('.modal-body').on('click', '.sync-attr-dismiss', function(){
        $jQ(this).closest('.sync-attr').remove();
        var attrCount = $jQ('.sync-attr').length;
        if(attrCount == 1) $jQ('.sync-attr-dismiss').hide();
    });

    // set attribute matching
    $jQ('.modal-body').on('change', 'select', function () {
        if($jQ(this).attr("class") == 'sync-wp-attr'){
            $jQ(this).closest('.sync-attr').find('.sync-match').val($jQ(this).val());
        }else{
            $jQ(this).closest('.sync-attr').find('.sync-match').attr('name',$jQ(this).val());
        }
    });

    // sync users to sendinblue
    $jQ('#sib_sync_users_btn').on('click', function(){

        $jQ(this).attr('disabled', 'true');
        var postData = $jQ('#sib-sync-form').serializeObject();
        $jQ(this).closest('form').find('input[type=hidden]').each(function (index, value) {
            var attrName = $jQ(this).attr('name');
            if($jQ('input[name='+attrName+']').length > 1){
                // the attribute is duplicated !
                postData['errAttr'] = attrName;
            }
        });

        var data = {
            action:'sib_sync_users',
            data: postData,
            security: ajax_sib_object.ajax_nonce
        };

        $jQ('.sib_alert').hide();
        $jQ(this).find('.sib-spin').show();
        $jQ.post(ajax_sib_object.ajax_url, data,function(respond) {
            $jQ('.sib-spin').hide();
            $jQ('#sib_sync_users_btn').removeAttr('disabled');
            if(respond.code != 'success') {
                $jQ('#sync-failure').show().html(respond.message);
            } else {
                // success to sync wp users
                $jQ('.tb-close-icon').click();
                window.location.reload();
            }
        });

    });
    $jQ('.sib-add-captcha').on('click', function(){
       var add_captcha = $jQ(this).val();
        if(add_captcha == '1')
        {
            $jQ('.sib-captcha-key').show('slow');
        }
        else
        {
            $jQ('.sib-captcha-key').hide('slow');
        }
    });

    $jQ('.popover-help-form').popover({
    });
    $jQ('.sib-spin').hide();
    $jQ('body').on('click', function(e) {
        if(!$jQ(e.target).hasClass('popover-help-form')) {
            $jQ('.popover-help-form').popover('hide');
        }
    });

    $jQ('.sib-add-terms').on('click', function(){
        var add_terms = $jQ(this).val();
        if(add_terms == '1')
        {
            $jQ('.sib-terms-url').show('slow');
        }
        else
        {
            $jQ('.sib-terms-url').hide('slow');
        }
    });

    $jQ('.sib-add-to-form').on('click', function(){
        var btn_id = $jQ(this).attr('id');
        var field_html = '';
        if(btn_id == 'sib_add_to_form_btn')
        {
            field_html = $jQ('#sib_field_html').val();
        }
        else if(btn_id == 'sib_add_captcha_btn')
        {
            var site_key = $jQ('#sib_captcha_site').val();
            var secret_key = $jQ('#sib_captcha_secret').val();
            var gCaptcha_type = $jQ('input[name=sib_recaptcha_type]:checked').val();

            if(gCaptcha_type == '0')
            {
                field_html = '<div id="sib_captcha"></div>';
            }
            
            if(site_key == '')
            {
                $jQ('#sib_form_captcha .alert-danger').html('You should input <strong>Site Key</strong>').show(300);
                return false;
            }
            else if(secret_key == '')
            {
                $jQ('#sib_form_captcha .alert-danger').html('You should input <strong>Secrete Key</strong>').show(300);
                return false;
            }
        }
        else if(btn_id == 'sib_add_termsUrl_btn')
        {
            var terms_url = $jQ('#sib_terms_url').val();
            field_html = '<input type="checkbox" name="terms" required="required">I accept the <a href="' + terms_url + '">terms and conditions</a> ';
            if(terms_url == '')
            {
                $jQ('#sib_form_terms .alert-danger').html('You should input <strong>Terms URL</strong>').show(300);
                return false;
            }
        }

        var formMarkup = $jQ("#sibformmarkup");
        
        var cursorPosition = get_cursor_position(formMarkup[0]);
        var html = formMarkup.val();
        if(html.charCodeAt(cursorPosition) == 10 || html.charCodeAt(cursorPosition) == 13){ // 10 is value of new line
            field_html = "\n" + field_html;
        }else{
            field_html = field_html + "\n";
        }
        var formData = [html.slice(0, cursorPosition), field_html, html.slice(cursorPosition)].join('');
        formMarkup.val(formData);

        // hide field edit after add the field to form
        $jQ('#sib-field-content').hide();
        $jQ("#sib_sel_attribute").val('-1');

        /*/ refresh iframe form /*/
        // ajax to update form html
        update_preview();
        // set cursor position at top
        setSelectionRange(formMarkup[0], 0, 0);
        return false;
    });

    var redirect = '';
    $jQ('.sib-form-redirect').on('click', function(e){
        e.preventDefault();
        redirect = $jQ(this).attr('href');
        $jQ('#sib_modal').modal();
    });

    $jQ('#sib_form_lang').on('change', function(){
        $jQ('#sib_modal').modal();
    });

    $jQ('#sib_modal_cancel').on('click', function(){
        $jQ('#sib_modal').modal('hide');
        $jQ('#sib_form_lang').val("");
    });
    $jQ('#sib_modal_ok').on('click', function(){
        var url = (redirect != '')? redirect :$jQ('#sib_form_lang').val();
        window.location.href = url;
    });

    // duplicate content from origin form in translation
    $jQ('.sib-duplicate-btn').on('click', function(){
        $jQ('.sib-spin').show();
        var pid = $jQ('input[name="pid"]').val();
        var data = {
            action: 'sib_copy_origin_form',
            pid: pid,
            security: ajax_sib_object.ajax_nonce
        };
        $jQ.post(ajax_sib_object.ajax_url, data, function(respond) {
            $jQ('.sib-spin').hide();
            $jQ('#sibformmarkup').val(respond);
        });

    });
});

// get serialized data form sync users form
$jQ.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $jQ.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
