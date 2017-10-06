function frontend_admin_menu_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function frontend_admin_menu_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

jQuery( "#frontend-admin-menu-icon" ).click(function() {
    jQuery( "#frontend-admin-menu-items" ).toggle();
    var divicon = 'frontend-admin-menu-icon';
    var menuwidth = document.getElementById("frontend-admin-menu-menu").offsetWidth;
    var cexpand = frontend_admin_menu_getCookie('frontend-admin-menu');
    var newicon = document.getElementById(divicon);
    if (cexpand === undefined || cexpand === null || cexpand === ''){
        frontend_admin_menu_setCookie("frontend-admin-menu", 'expand-on', 365);
        jQuery( 'body' ).css( {'margin-left':menuwidth+'px'} ); 
        newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-close.png">';
    } else if (cexpand === 'expand-on'){
        frontend_admin_menu_setCookie("frontend-admin-menu", 'expand-off', 365);
        jQuery( 'body' ).css( {'margin-left':'0'} );
        newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-settings.png">';
    } else if (cexpand === 'expand-off'){
        frontend_admin_menu_setCookie("frontend-admin-menu", 'expand-on', 365);
        jQuery( 'body' ).css( {'margin-left':menuwidth+'px'} );
        newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-close.png">';
    }    
});

jQuery( document ).ready( function() {
    var divicon = 'frontend-admin-menu-icon';
    var divmenu = 'frontend-admin-menu-menu';
    jQuery( '#frontend-admin-menu-items .menu-item-has-children > .expand' ).addClass( "frontend-admin-menu-childen-icon-right" );
    jQuery( '#frontend-admin-menu-menu #frontend-admin-menu-items' ).css( {display:'none'} );
    jQuery( '#frontend-admin-menu-items ul ul' ).hide();
    if( jQuery('#'+divicon).length){
        var newicon = document.getElementById(divicon);
    }
    var cexpand = frontend_admin_menu_getCookie('frontend-admin-menu');
    if (cexpand === undefined || cexpand === null || cexpand === ''){
        jQuery( '#frontend-admin-menu-menu #frontend-admin-menu-items' ).css( {display:'none'} );
        if( jQuery('#'+divicon).length){
            newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-settings.png">';
        }
    } else if (cexpand === 'expand-on'){
        jQuery( '#frontend-admin-menu-menu #frontend-admin-menu-items' ).css( {display:'block'} );
        if( jQuery('#'+divicon).length){
            newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-close.png">';
        }
    } else if (cexpand === 'expand-off'){
        jQuery( '#frontend-admin-menu-menu #frontend-admin-menu-items' ).css( {display:'none'} );
        if( jQuery('#'+divicon).length){
            newicon.innerHTML = '<img src="/wp-content/plugins/frontend-admin-menu/images/icon-settings.png">';
        }
    }    
    jQuery( '#frontend-admin-menu-items .menu-item-has-children .expand' ).click(function() {
        jQuery(this).next().next().slideToggle('fast');
        jQuery(this).toggleClass( "frontend-admin-menu-childen-icon-right" );
        jQuery(this).toggleClass( "frontend-admin-menu-childen-icon-down" );
        return false;
    });
    if( jQuery('#'+divmenu).length){
        var menuwidth = document.getElementById(divmenu).offsetWidth;
        if (cexpand !== undefined || cexpand !== null || cexpand !== '' || cexpand !== 'expand-off'){
          jQuery( 'body' ).css( {'margin-left':menuwidth+'px'} );  
        }
    }
});