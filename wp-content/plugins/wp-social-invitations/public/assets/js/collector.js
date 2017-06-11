/**
 * fastLiveFilter jQuery plugin 1.0.3
 *
 * Copyright (c) 2011, Anthony Bush
 * License: <http://www.opensource.org/licenses/bsd-license.php>
 * Project Website: http://anthonybush.com/projects/jquery_fast_live_filter/
 **/

jQuery.fn.fastLiveFilter = function(list, options) {
    // Options: input, list, timeout, callback
    options = options || {};
    list = jQuery(list);
    var input = this;
    var lastFilter = '';
    var timeout = options.timeout || 0;
    var callback = options.callback || function() {};

    var keyTimeout;

    // NOTE: because we cache lis & len here, users would need to re-init the plugin
    // if they modify the list in the DOM later.  This doesn't give us that much speed
    // boost, so perhaps it's not worth putting it here.
    var lis = list.children();
    var len = lis.length;
    var oldDisplay = len > 0 ? lis[0].style.display : "block";
    callback(len); // do a one-time callback on initialization to make sure everything's in sync

    input.change(function() {
        // var startTime = new Date().getTime();
        var filter = input.val().toLowerCase();
        var li, innerText;
        var numShown = 0;
        for (var i = 0; i < len; i++) {
            li = lis[i];
            innerText = !options.selector ?
                (li.textContent || li.innerText || "") :
                $(li).find(options.selector).text();

            if (innerText.toLowerCase().indexOf(filter) >= 0) {
                if (li.style.display == "none") {
                    li.style.display = oldDisplay;
                }
                numShown++;
            } else {
                if (li.style.display != "none") {
                    li.style.display = "none";
                }
            }
        }

        // var endTime = new Date().getTime();
        // console.log('Search for ' + filter + ' took: ' + (endTime - startTime) + ' (' + numShown + ' results)');
        return false;
    }).keydown(function() {
        clearTimeout(keyTimeout);
        keyTimeout = setTimeout(function() {
            callback();
        }, timeout);
        if( input.val() === lastFilter ) return;
        lastFilter = input.val();
        input.change();
    });
    return this; // maintain jQuery chainability
}

jQuery(document).ready(function($) {

    $('.unselect-all a').click(function(){

        var link = $(this);
        if( link.hasClass('select'))
        {
            $('#FriendsList').find(':checkbox').prop('checked',true);
            $('#FriendsList').find('tr').addClass('selectedTr');
            $(this).hide();
            $('.unselect-all a.unselect').fadeIn();
        }
        else
        {
            $('#FriendsList').find(':checkbox').prop('checked',false);
            $('#FriendsList').find('tr').removeClass('selectedTr');
            $(this).hide();
            $('.unselect-all a.select').fadeIn();
        }
        return false;
    });

    $('#FriendsList input:checkbox').click(function(){

        if( $(this).prop('checked') )
        {
            $(this).parent('td').parent('tr').addClass('selectedTr');
        }
        else
        {
            $(this).parent('td').parent('tr').removeClass('selectedTr');
        }

    });

    //Fix wp_editor height
    setTimeout(function(){
        $('#message_ifr').css('min-height','100px');
    },500 )
});



(function ($) {

    //ondomready
    $(function () {
      //  listFilter($("#searchinput"), $("#FriendsList"));
        $("#searchinput").fastLiveFilter('#FriendsList tbody', {
            timeout : 500,
            callback: function(total) { $('#FriendsList .lazy:visible').trigger('scroll') }
        });
    });

    if( wsi_hook ) {

        $('#collect_emails').on('submit', function(e){
            e.preventDefault();
            $('body *').hide();
            $('#wsi_loading,#wsi_loading * ').fadeIn();
            var emails = '';
            $(".friends_container input:checkbox:checked").each(function(){
                emails += $(this).val()+'\n';
            });
            $('#invite-anyone-email-addresses',window.opener.document).html(emails);
            $('#'+widget_id+' #'+provider+'-provider',window.opener.document).addClass('completed');
            $('#'+widget_id+' #wsi_provider',window.opener.document).html(provider_label);
            $('#'+widget_id+' .wsi_success',window.opener.document).fadeIn('slow',function(){

                window.self.close();
            });

        });
    } else {
        $('#collect_emails').on('submit', function(e){
            e.preventDefault();
            $('body *').hide();
            $('#wsi_loading,#wsi_loading * ').fadeIn();

            $.post(window.opener.WsiMyAjax.admin_url, $('#collect_emails').serialize(), function(response){
                $('#'+widget_id+' #'+provider+'-provider',window.opener.document).addClass('completed');
                $('#'+widget_id+' #wsi_provider',window.opener.document).html(provider_label);
                $('#'+widget_id+' .wsi_success',window.opener.document).fadeIn('slow',function(){
                   if( wsi_locker ) {
                       setCookie("wsi-lock[" + widget_id + "]", 1, 365);
                       window.opener.location.reload();
                   }

                    window.self.close();
                    if( redirect_url ) {
                        window.opener.location.href = redirect_url;
                    }
                });
            });
            return false;
        });
    }
    //remove everything added from wp_footer
    $('#footer div').not('#credits').remove();

}(jQuery));

function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()) + "; path=/";
    document.cookie=c_name + "=" + c_value;
}
