jQuery(document).ready(function($){
    $('.wall-color').wpColorPicker({change: function(event, ui){
        admin_update_style();
    }});
    $("body").on("click",".item-remove",function(e){
       $(this).closest(".menu-item").find(".item-delete").click();
        return false;
    })
    $(".save-menu-pro").click(function(e){
		var data = $("#update-nav-menu").serializeArray();
        //console.log(data);

        var url = $("#menu-to-edit .menu-item-depth-0 .edit-menu-item-ulr");
        var main_list = [];
        $(".menu-item").removeClass("menu-error");
        var rt = false;
        var i = 0;
        url.each(function( index ) {
            var id = "menu-item-"+$(this).closest(".menu-item").find(".menu-item-data-db-id").val();
            var value = $(this).val();
            var key = functiontofindIndexByKeyValue(main_list,"value",value);
             console.log($(this).html());
            if( key !== -1) {
                console.log(value);
                console.log(key);
                $("#menu-to-edit ."+key).addClass("menu-error");
                $("#menu-to-edit ."+id).addClass("menu-error");
                alert("Main menu double url!");
                rt = true;
                return false;
            }
            main_list.push({id:id,value:value});
            i++;
        });
        if(rt == true ){
            return false;
        }
        var backgound = $("#admin-menu-style-background").val();
        var color = $("#admin-menu-style-color").val();
        var backgound_active = $("#admin-menu-style-background-active").val();
        var color_active = $("#admin-menu-style-color-active").val();

		var data_ajax = {
			'action': 'save_menu_pro',
			'data': data,
            'settings': {backgound:backgound,color:color,backgound_active:backgound_active,color_active:color_active}
		};

        jQuery.post(ajaxurl, data_ajax, function(response) {
            window.onbeforeunload = null;
            console.log(response);
            location.reload();
        });
        return false;
    })

    $(".reset-menu-pro").click(function(e){
        if (confirm('Are you sure you want load default menu?')) {
            var data_ajax = {
    			'action': 'reset_default_menu',
                'role': $("#admin_menu_role_id").val()
    		};

            jQuery.post(ajaxurl, data_ajax, function(response) {
                window.onbeforeunload = null;
                location.reload();
            });
        }
        return false;
    })
    call_back_draggable_menu();
    function call_back_draggable_menu(){
        $( "#admin-menu-new li" ).draggable({
              connectToSortable: "#menu-to-edit",
              helper: "clone",
              stop: function(){
                //console.log("ok");
                var rand = Math.floor((Math.random() * 100000));
                var html =$("#admin-menu-new").html();
                //console.log(html);
                var dep = "item-depth-"+rand;
                var custom = html.replace(/\d+/g, rand).replace(dep,"item-depth-0");
                $("#admin-menu-new").html(custom);
                $('.icon-picker').iconPicker();
                 call_back_draggable_menu();

              }
        });
    }
    $("body").on("change",".edit-menu-item-ulr",function(e){
        //$(this).closest(".menu-item").removeClass("menu-error");
    })
    $("body").on("change",".menu-taget-page-sel",function(e){
        var item = $(this).find('option:selected');
        var name = item.text();
        var url = item.val();
        var capt = item.data("cap");
        var res = name.split(">");
        console.log(res);
        if( res.length > 1 ){
            name = res[1].trim();
        }
        if( url == "#" ) {
            $(this).closest(".menu-item-settings").find(".edit-menu-item-ulr").removeAttr("readonly");
            $(this).closest(".menu-item-settings").find(".edit-menu-item-capability").removeAttr("readonly");
            $(this).closest(".menu-item-settings").find(".menu-taget-page-capt").removeClass("hidden");
            $(this).closest(".menu-item-settings").find(".edit-menu-item-capability").addClass("hidden");
        }else{
           $(this).closest(".menu-item-settings").find(".edit-menu-item-title").val(name);
            $(this).closest(".menu-item-settings").find(".edit-menu-item-ulr").val(url).attr("readonly","readonly");
            $(this).closest(".menu-item-settings").find(".edit-menu-item-capability").val(capt).attr("readonly","readonly");

            $(this).closest(".menu-item-settings").find(".menu-taget-page-capt").addClass("hidden");
            $(this).closest(".menu-item-settings").find(".edit-menu-item-capability").removeClass("hidden");
        }

    })
     $("body").on("click","a.admin_menu_blank",function(e){
        $(this).attr("target","_blank");
    })
    function admin_update_style(){
        var backgound = $("#admin-menu-style-background").val();
        var color = $("#admin-menu-style-color").val();
        var backgound_active = $("#admin-menu-style-background-active").val();
        var color_active = $("#admin-menu-style-color-active").val();

        $("#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap").css("background-color",backgound);
        $("#wpadminbar").css("background",backgound);

        $("#adminmenu a,#wpadminbar .ab-empty-item, #wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon").css("color",color);

        $("#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu").css("background-color",backgound_active);

         $("#adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus").css("color",color_active);
         $("#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu").css("color",color_active);
        //$("#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu").css("background-color",color_active);
        //$("#adminmenu li.menu-top:hover a, #adminmenu li.opensub>a.menu-top a, #adminmenu li>a.menu-top:focus").css("color",color_active);
    }
    $(".admin_menu_new_user").click(function(e){
        var person = prompt("Please enter user ID", "1");
        if (person != null) {
            location.assign($(".admin_menu_new_user a").attr('href')+"&role="+person);
        }
        return false;
    })
    $(".reset-setttings-admin-menu").click(function(e){
        if (confirm('Are you sure you want reset settings?')) {
            var data_ajax = {
    			'action': 'reset_default_menu_settings',
                'role': $("#admin_menu_role_id").val()
    		};

            jQuery.post(ajaxurl, data_ajax, function(response) {
                location.reload();
            });
        }
    })
    function functiontofindIndexByKeyValue(arraytosearch, key, valuetosearch) {
        for (var i = 0; i < arraytosearch.length; i++) {
            if (arraytosearch[i][key] == valuetosearch) {
                return arraytosearch[i]["id"];
            }
        }
        return -1;
    }
})