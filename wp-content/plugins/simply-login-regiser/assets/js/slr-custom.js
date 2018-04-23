
jQuery('.slr-login-from-wrap button.slr_login_btn').click(function(e){
    e.preventDefault();
    var uname = jQuery('form input[name="slr_user_login"]').val();
    var pwd   = jQuery('form input[name="slr_user_password"]').val();

    if (uname.length == 0 ){
        jQuery('form input[name="slr_user_login"]').addClass('input-error');
    }else{
        jQuery('form input[name="slr_user_login"]').removeClass('input-error');
    }
    if (pwd.length == 0 ){
        jQuery('form input[name="slr_user_password"]').addClass('input-error');
    }else{
        jQuery('form input[name="slr_user_password"]').removeClass('input-error');
    }

    if (uname.length == 0 || pwd.length == 0){
        return false;
    }else{
        user_loginFrm();       
    }
});

jQuery('.slr-register-from-wrap button.slr_reg_btn').click(function(e){
    e.preventDefault();
    user_registerFrm();
});



function user_loginFrm(){
	jQuery('#slr_loader').show();
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: slr_ajax_object.ajax_url,
        data: { 
            'action': 'slr_ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
            'username'  : jQuery('form input[name="slr_user_login"]').val(),
            'password'  : jQuery('form input[name="slr_user_password"]').val(),
            'rememberme': jQuery('form input[name="slr_rememberme"]').val()},            
        success: function(data){
        	jQuery('#slr_loader').hide();
        	if (data.loggedin == true){
        		jQuery('form#slr_login_form .slr_response_msg').show();
                jQuery('form#slr_login_form .slr_response_msg .alert').addClass('alert-success');
                jQuery('form#slr_login_form .slr_response_msg .alert').text(data.message);
        		jQuery('form#slr_login_form .slr_response_msg').delay(1000).fadeOut();
                location.reload();
            }else{
                jQuery('form#slr_login_form .slr_response_msg').show();
                jQuery('form#slr_login_form .slr_response_msg .alert').addClass('alert-error');
                jQuery('form#slr_login_form .slr_response_msg .alert').text(data.message);
                jQuery('form#slr_login_form .slr_response_msg').delay(1000).fadeOut();
			}
        }
    });
	return false;
}

function user_registerFrm(){
    jQuery('#slr_loader').show();
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: slr_ajax_object.ajax_url,
        data: { 
            'action'       : 'slr_ajaxregister',
            'reg_uname'    : jQuery('form input[name="reg_uname"]').val(),
            'reg_email'    : jQuery('form input[name="reg_email"]').val(), 
            'reg_password' : jQuery('form input[name="reg_password"]').val(),
            'reg_website'  : jQuery('form input[name="reg_website"]').val(),
            'reg_fname'    : jQuery('form input[name="reg_fname"]').val(),
            'reg_lname'    : jQuery('form input[name="reg_lname"]').val(),
            'reg_nickname' : jQuery('form input[name="reg_nickname"]').val(),
            'reg_bio'      : jQuery('form textarea[name="reg_bio"]').val()            
        },
        success: function(data){
            jQuery('#slr_loader').hide();
            if (data.loggedin == true){
                jQuery('form#slr_register_form .slr_response_msg').show();
                jQuery('form#slr_register_form .slr_response_msg .alert').addClass('alert-success');
                jQuery('form#slr_register_form .slr_response_msg .alert').text(data.message);
                jQuery('form#slr_register_form .slr_response_msg').delay(5000).fadeOut();                
            }else{
                jQuery('form#slr_register_form .slr_response_msg').show();
                jQuery('form#slr_register_form .slr_response_msg .alert').addClass('alert-error');
                jQuery('form#slr_register_form .slr_response_msg .alert').text(data.message);
                jQuery('form#slr_register_form .slr_response_msg').delay(5000).fadeOut();
            }
        }
    });
    return false;
}

