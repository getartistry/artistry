wsifbApiInit = false;
window.fbAsyncInit = function() {
    FB.init({
        appId      : WsiMyAjax.appId,
        xfbml      : true,
        version    : 'v2.2'
    });
    wsifbApiInit = true; //init flag
};
(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/"+WsiMyAjax.locale+"/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
(function( $ ) {
    'use strict';

    $(function(){

        $(".service-filters a").on('click', function(e){

            if(!wsifbApiInit && typeof(FB) != 'undefined' && FB != null){
                FB.init({
                    appId      : WsiMyAjax.appId,
                    xfbml      : false,
                    version    : 'v2.0'
                });
            }

            var provider 		= $(this).data("provider"),
                current_url		= WsiMyAjax.current_url,
                dualScreenLeft 	= window.screenLeft != undefined ? window.screenLeft : screen.left,
                dualScreenTop 	= window.screenTop != undefined ? window.screenTop : screen.top,
                left 			= ((screen.width / 2) - (600 / 2)) + dualScreenLeft,
                top 			= ((screen.height / 2) - (640 / 2)) + dualScreenTop,
                widget			= $(this).closest('.service-filter-content'),
                widget_id  		= widget.attr('id'),
                wsi_locker 		= widget.data('locker'), // check if it's a locker widget
                wsi_hook 		= widget.data('hook'); // check if there is any hook

            if( 'facebook' == provider) {
                var	queue_id = '';

                queue_id = WsiMyAjax.user_id;
                var link = WsiMyAjax.site_url + '?wsi_action=accept-invitation&wsi_referral=fb&wsi_invitation=' + queue_id;

                if( 'registration' == WsiMyAjax.fburl ) {
                    link = WsiMyAjax.site_url + 'wp-login.php?action=register&wsi_referral=fb&wsi_action=accept-invitation&wsi_invitation=' + queue_id;
                }
                if( 'current' == WsiMyAjax.fburl ) {
                    if( current_url.indexOf("?") == -1 ) {
                        link = current_url + "?" + 'wsi_action=accept-invitation&wsi_referral=fb&wsi_invitation=' + queue_id;
                    } else {
                        link = current_url + "&" + 'wsi_action=accept-invitation&wsi_referral=fb&wsi_invitation=' + queue_id;
                    }
                }
                if( 'custom_url' == WsiMyAjax.fburl ) {
                    if( WsiMyAjax.fbCustomurl.indexOf("?") == -1 ) {
                        link = WsiMyAjax.fbCustomurl + "?" + 'wsi_action=accept-invitation&wsi_referral=fb&wsi_invitation=' + queue_id;
                    } else {
                        link = WsiMyAjax.fbCustomurl + "&" + 'wsi_action=accept-invitation&wsi_referral=fb&wsi_invitation=' + queue_id;
                    }
                }

                var method = "share";
                // send dialog is not working on mobile
                if( jQuery(window).width() < 768 ) {
                    method = "share";
                }
                FB.ui(
                    {
                        method: method,
                        link: link + '&v=' + Date.now(),
                        href: link + '&v=' + Date.now(),
                    },
                    function(response) {
                        if (response && !response.error_code) {
                            $('#'+widget_id+' #facebook-provider').addClass('completed');
                            $('#'+widget_id+' #wsi_provider').html(provider);
                            $('#'+widget_id+' .wsi_success').fadeIn('slow',function(){
                                if( wsi_locker ) {
                                    setCookie("wsi-lock["+widget_id+"]",1,365);
                                    window.location.reload();
                                }
                                if( WsiMyAjax.redirect_url != '' ) {
                                    window.location.href = WsiMyAjax.redirect_url;
                                }

                            });
                        }
                    }
                );

                //now we add it to queue
                $.ajax({
                    type: "POST",
                    url: WsiMyAjax.admin_url,
                    data: {nonce: WsiMyAjax.nonce, action: 'wsi_fb_link', wsi_obj_id: WsiMyAjax.wsi_obj_id},
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                    }
                });

            } else {
                window.open(
                    WsiMyAjax.site_url+"?action=wsi_collector&wsi_obj_id="+WsiMyAjax.wsi_obj_id+"&redirect_to="+encodeURIComponent(current_url)+"&provider="+provider+"&widget_id="+widget_id+"&wsi_locker="+wsi_locker+"&wsi_hook="+wsi_hook+"&_ts=" + (new Date()).getTime(),
                    "hybridauth_social_sing_on",
                    "directories=no,copyhistory=no,location=0,toolbar=0,location=0,menubar=0,status=0,scrollbars=1,width=600,height=640,top=" + top + ", left=" + left
                );
            }
            e.preventDefault();
        });
    });

})( jQuery );
function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()) + "; path=/";
    document.cookie=c_name + "=" + c_value;
}
