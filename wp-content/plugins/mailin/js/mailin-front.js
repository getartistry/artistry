var captchaRes = '';
var sibVerifyCallback = function(response){
    captchaRes = response;
    if(captchaRes)
    {
        jQuery('.sib_signup_form').trigger('submit');
    }
};

jQuery(document).ready(function(){
    // run MA script identify() when submit on any forms with email field
    jQuery(document).on('submit', 'form', function(e){
        if(!jQuery(this).hasClass('sib_signup_form')) {
            var email = jQuery(this).find('input[type=email]').val();
            var emailPattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (typeof sendinblue != 'undefined' && email != null && emailPattern.test(email)) {
                var postData = jQuery(this).serializeObject();
                sendinblue.identify(email, postData);
            }
        }
        else
        {
            e.preventDefault();
            var form = jQuery(this).closest('form');
            /**
             * For safari
             * Not support required attribute
             */

            var required_fileds = [];
            var err_index = 0;
            jQuery.each(form.find('input[required="required"]'), function(){
                if(jQuery(this).val() == '')
                {
                    required_fileds[err_index] = jQuery(this).attr('name');
                    err_index++;
                }
            });
            if(err_index > 0)
            {
                form.find('.sib_msg_disp').html('<p class="sib-alert-message sib-alert-message-warning ">' + sibErrMsg.requiredField + '</p>').show();
                return;
            }
            err_index=0;
            jQuery.each(form.find('input[type="email"]'), function(){
                var Email = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (!Email.test(jQuery(this).val()))
                {
                    err_index++;
                }
            });
            if(err_index > 0)
            {
                form.find('.sib_msg_disp').html('<p class="sib-alert-message sib-alert-message-warning ">' + sibErrMsg.invalidMail + '</p>').show();
                return;
            }

            /**
             * check dateformat
             */
            err_index = 0;
            jQuery.each(form.find('.sib-date') , function(){
                var dateFormat = jQuery(this).data('format');
                var date = jQuery(this).val();
                var filter = '';
                if(dateFormat == 'dd/mm/yyyy')
                {
                    filter = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
                }

                if(dateFormat == 'dd-mm-yyyy')
                {
                    filter = /^(((0[1-9]|[12]\d|3[01])-(0[13578]|1[02])-((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)-(0[13456789]|1[012])-((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])-02-((19|[2-9]\d)\d{2}))|(29-02-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
                }
                else if(dateFormat == 'mm-dd-yyyy')
                {
                    filter = /^(((0[13578]|1[02])-(0[1-9]|[12]\d|3[01])-((19|[2-9]\d)\d{2}))|((0[13456789]|1[012])-(0[1-9]|[12]\d|30)-((19|[2-9]\d)\d{2}))|(02-(0[1-9]|1\d|2[0-8])-((19|[2-9]\d)\d{2}))|(02-29-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
                }

                if (filter == '' || !filter.test(date))
                {
                    err_index++;
                }
            });

            if(err_index > 0)
            {
                form.find('.sib_msg_disp').html('<p class="sib-alert-message sib-alert-message-warning ">' + sibErrMsg.invalidDateFormat + '</p>').show();
                return;
            }
            /**
             * End
             */

            form.find('.sib_loader').show();
            jQuery('.sib_msg_disp').hide();
            var postData = form.serializeArray();
            if( captchaRes != '')
            {
                postData.push({"name": "g-recaptcha-response", "value": captchaRes});
            }
            var formURL = form.attr("action");
            form.addClass('sib_processing');

            postData.push({ "name": "security", "value": sib_ajax_nonce});
            jQuery.ajax(
                {
                    url: formURL,
                    type: "POST",
                    dataType: "json",
                    data: postData,
                    success: function (data, textStatus, jqXHR) {
                        var form = jQuery('.sib_processing');
                        jQuery('.sib_loader').hide();
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.status === 'success' || data.status === 'update') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-success ">' + data.msg.successMsg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        } else if (data.status === 'failure') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-error ">' + data.msg.errorMsg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        } else if (data.status === 'already_exist') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-warning ">' + data.msg.existMsg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        } else if (data.status === 'invalid') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-error ">' + data.msg.invalidMsg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        } else if (data.status === 'gcaptchaEmpty') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-error ">' + data.msg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        } else if (data.status === 'gcaptchaFail') {
                            var cdata = '<p class="sib-alert-message sib-alert-message-error ">' + data.msg + '</p>';
                            form.find('.sib_msg_disp').html(cdata).show();
                        }
                        // run MA script identify() when subscribe on SIB forms
                        if (typeof sendinblue != 'undefined') {
                            var email = form.find('input[name=email]').val();
                            var postData = form.serializeObject();
                            if (data.status === 'success' || data.status === 'update' || data.status === 'already_exist') {
                                sendinblue.identify(email, postData);
                            }
                        }
                        jQuery(".sib-alert-message").delay(2000).hide('slow');
                        form.removeClass('sib_processing');
                        grecaptcha.reset(gCaptchaSibWidget);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        form.find('.sib_msg_disp').html(jqXHR).show();
                        grecaptcha.reset(gCaptchaSibWidget);
                    }
                });
        }
    });

});
// get serialized data form subscribe form
jQuery.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
        if(this.name == 'sib_form_action' || this.name == 'sib_form_id' || this.name == 'email')
            return true; // continue
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
