jQuery(function ($) {
    var $success = $("#asp_i_success");
    var $error = $("#asp_i_error");
    var $error_cont = $("#asp_i_error_cont");

    $('#asp_reset, #asp_wipe').on('click', function(e){
        e.preventDefault();
        asp_clear_msg();
        asp_disable_buttons();

        if ( $(this).attr('id') == 'asp_reset' )
            var r = confirm("Are you sure you want to reset Ajax Search Pro to it's default state? All search instances will be deleted!");
        else
            var r = confirm("Are you sure you want to completely remove Ajax Search Pro? (including instances, database content etc..)");
        if (r == true) {
            var data = {
                'action' : 'asp_maintenance_admin_ajax',
                'data' : $(this).closest('form').serialize()
            };
            $.post(ajaxurl, data)
                .done(asp_on_post_success)
                .fail(asp_on_post_failure);
            $('.loading-small', $(this).parent()).removeClass('hiddend');
        }
        return true;
    });

    function asp_on_post_success(response) {
        var res = response.replace(/^\s*[\r\n]/gm, "");
        res = res.match(/!!!ASP_MAINT_START!!!(.*[\s\S]*)!!!ASP_MAINT_STOP!!!/);
        if (res != null && (typeof res[1] != 'undefined')) {
            res = JSON.parse(res[1]);
            if (typeof res.status != "undefined" && res.status == 1 ) {
                if ( res.action == 'redirect' ) {
                    asp_show_success('<strong>SUCCESS: </strong>' + res.msg);
                    setTimeout(function () {
                        location.href = ASP_MNT.admin_url + '/plugins.php';
                    }, 5000);
                } else if ( res.action == 'refresh' ) {
                    asp_show_success('<strong>SUCCESS! </strong>Refreshing this page, please wait..');
                    $('form#asp_empty_redirect input[name=asp_mnt_msg]').val(res.msg);
                    $('form#asp_empty_redirect').submit();
                } else {
                    asp_show_success('<strong>SUCCESS: </strong>' + res.msg);
                }
            } else {
                if (typeof res.status != "undefined" && res.status == 0 ) {
                    asp_show_error('<strong>FAILURE: </strong>' + res.msg);
                } else {
                    asp_show_error('Something went wrong. Response returned: ', response);
                }
                asp_enable_buttons();
            }
        } else { // Failure?
            asp_show_error('Something went wrong. Here is the error message returned: ', response);
            asp_enable_buttons();
        }
    }
    function asp_on_post_failure(response, t) {
        if (t === "timeout") {
            asp_show_error('Timeout error. Please try again!');
        } else {
            asp_show_error('Something went wrong. Here is the error message returned: ', response);
        }
        asp_enable_buttons();
    }

    function asp_show_success(msg) {
        $success.removeClass('hiddend').html(msg);
    }

    function asp_show_error(msg, response) {
        $error.removeClass('hiddend').html(msg);
        if ( typeof response !== 'undefined') {
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
    }

    function asp_disable_buttons() {
        $('#asp_reset, #asp_wipe').addClass('disabled');
    }

    function asp_enable_buttons() {
        $('.loading-small').addClass('hiddend');
        $('#asp_reset, #asp_wipe').removeClass('disabled');
    }

    function asp_clear_msg() {
        $error_cont.addClass('hiddend');
        $error.addClass('hiddend');
        $success.addClass('hiddend');
    }
});
