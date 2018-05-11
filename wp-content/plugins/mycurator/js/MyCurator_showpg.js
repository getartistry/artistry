//Jquery for show page insert, insert image
var img_src = '';
jQuery(document).ready(function($) {
        jQuery( ".mct-ai-tabs #tabs" ).tabs();  //Set up tabs display
        
        $("#ai-dialog").dialog({ //Set up dialog box for images
            autoOpen : false, 
            dialogClass  : 'wp-dialog', 
            modal : true, 
            closeOnEscape: true,
            buttons: {
            "Insert": function(){myc_insert();$( this ).dialog( "close" );  },
            "Featured": function() {myc_feature();$( this ).dialog( "close" );},
            Cancel: function() {$( this ).dialog( "close" ); }}
        });
        
        $('.switch-html').click(function(){$('div#ai-showpg-msg').css('display',"none")});//shut off msg
        $('.switch-tmce').click(function(){$('div#ai-showpg-msg').css('display',"")});//turn on msg
        //Hover highlight
        var selector = "div.ui-tabs-panel p, div.ui-tabs-panel ul, div.ui-tabs-panel ol, div.ui-tabs-panel table, div.ui-tabs-panel h1, div.ui-tabs-panel h2, div.ui-tabs-panel h3, div.ui-tabs-panel h4, div.ui-tabs-panel h5, div.ui-tabs-panel h6 ";
        jQuery( selector ).hover(function(){
            if ($("#no-element-copy").prop('checked')) return;
            if ($("textarea#content.wp-editor-area").is(":visible")) return; //Doesn't work in Text tab
            if ($(this).attr('id') == 'idx-entry') return
            $(this).css("background-color","yellow");
            },function(){
            $(this).css("background-color","");
        });
        //click on element
        jQuery( selector ).click(function(){
            if ($("#no-element-copy").prop('checked')) return;
            if ($("textarea#content.wp-editor-area").is(":visible")) return;
            if ($(this).attr('id') == 'idx-entry') return
            $(this).css("background-color","");
            var elem = $(this).clone();
            elem.find('img').remove();
            //if (elem.find('img').length != 0) return false;
            if (elem.html().length == 0) return false;
            //var selection = $('<div/>').append($(this).clone()).html();
           var selection = $('<div/>').append(elem).html();
           tinyMCE.execCommand("mceInsertContent", false, selection);
           return false;
        });
        //click on image
        jQuery( "div.ui-tabs-panel img" ).click(function(e){
            if ($("#no-element-copy").prop('checked')) return;
            if ($("textarea#content.wp-editor-area").is(":visible")) return;
            e.stopPropagation();
            img_src = $(this).attr('src');
           $("#ai-dialog").dialog("open");
           return false;
           //var selection = $('<div/>').append($(this).clone()).html();
           //tinyMCE.execCommand("mceInsertContent", false, selection);
           //return false;
        });
        //click on index article
        $('a#idx-article').click(function() {
            var tab = $(this).attr('href');
            $( ".mct-ai-tabs #tabs" ).tabs( "option", "active", tab );
            return false;
        })
    });
    //insert image into post
    function myc_insert(){
        var pid = jQuery('#ai_post_id').attr('value');
        var nonce = jQuery('#showpg_nonce').attr('value');
        var title = jQuery('#ai_title_alt').attr('value');
        var align = jQuery('input[name=ai_img_align]:checked').val();
        var size = jQuery('input[name=ai_img_size]:checked').val();
        var data = { pid: pid,
              nonce: nonce,
              title: title,
              imgsrc: img_src,
              align: align,
              size: size,
              type: 'insert',
              action: 'mct_ai_showpg_ajax'};
        jQuery('#ai-saving').css('display', 'inline');
        jQuery.post(mct_ai_showpg.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            var img_str = jQuery(data).find('supplemental imgstr').text();
            jQuery('#ai-saving').css('display', 'none');
            if (status == 'Ok') {
              var selection = jQuery('<div/>').append(img_str).html();
              tinyMCE.execCommand("mceInsertContent", false, selection);
            } else {
                alert(status);
            }
        });
        return false;
    };
    //Set as featured image
    function myc_feature(){
        var pid = jQuery('#ai_post_id').attr('value');
        var nonce = jQuery('#showpg_nonce').attr('value');
        var title = jQuery('#ai_title_alt').attr('value');
        var data = { pid: pid,
              nonce: nonce,
              imgsrc: img_src,
              title: title,
              type: 'feature',
              action: 'mct_ai_showpg_ajax'};
        jQuery('#ai-saving').css('display', 'inline');
        jQuery.post(mct_ai_showpg.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            jQuery('#ai-saving').css('display', 'none');
            if (status != 'Ok') {
                alert(status);
            }
            var html_str = jQuery(data).find('supplemental imgstr').text();
            WPSetThumbnailHTML(html_str);
        });
        return false;
    };