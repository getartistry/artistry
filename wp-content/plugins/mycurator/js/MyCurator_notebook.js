//Javascript for Training post ajax V1.0.0
jQuery(document).ready(function($) {
    //Notebook add Dialog
    $("#nb-add-dialog").dialog({ //Set up dialog box 
        autoOpen : false, 
        dialogClass  : 'wp-dialog', 
        modal : true, 
        width: 600,
        closeOnEscape: true,
        buttons: {
        "Save": function(){myc_nb_add();$( this ).dialog( "close" );  },
        Cancel: function() {$( this ).dialog( "close" ); }}
    });
    //Notebook delete Dialog
    $("#nb-del-dialog").dialog({ //Set up dialog box 
        autoOpen : false, 
        dialogClass  : 'wp-dialog', 
        modal : true, 
        width: 500,
        closeOnEscape: true,
        buttons: {
        "Delete": function(){myc_nb_del();$( this ).dialog( "close" );  },
        Cancel: function() {$( this ).dialog( "close" ); }}
    });
    //Notebook delete click
    $('.mct-nb-del-notebk').click(function(){
        var link = this;
        var href = $(link).attr('href');
        //Get the ID and nonce
        mct_nb_post_id = $(link).attr('id');
        $("#nb-del-dialog").dialog("open");
        return false;
    });  
    //Note page delete click
    $('.mct-nb-del-notepg').click(function(){
        var link = this;
        var href = $(link).attr('href');
        //Get the ID and nonce
        mct_nb_post_id = $(link).attr('id');
        myc_nb_del();
        return false;
    });  
    //Click on add notebook link
    $(".mct-nb-add-notebk").click(function(){
        mct_nb_post_id = 0;
        $('#mct-nb-title').val('');
        $('#mct-nb-desc').val('');
        $("#nb-add-dialog").dialog("open");
        return false;
    });
    //Click on edit notebook link
    $(".mct-nb-edit-notebk").click(function(){
        var link = this;
        mct_nb_post_id = $(link).attr('id');
        var title = $('#title-'+mct_nb_post_id).text();
        var desc = $('#desc-'+mct_nb_post_id).text();
        $('#mct-nb-title').val(title);
        $('#mct-nb-desc').val(desc);
        $("#nb-add-dialog").dialog("open");
        return false;
    });
    //click on notepg check all box 1
    $("#bulk-all-1, #bulk-all-2").change(function() {
        if (this.checked) {
            $("input:checkbox").prop('checked', true);
        } else {
            $("input:checkbox").prop('checked', false);
        }
    })
    //click on individual checkbox, remove bulk checks
    $("input:checkbox").change(function() {
        if ($(this).attr("id").indexOf('all') == -1 ) {
            $("#bulk-all-1").prop('checked', false);
            $("#bulk-all-2").prop('checked', false);
        }
    })
});
        
    function remove_item(post) {
        var p2 = jQuery('tr.post-'+post);
        p2.css('background-color','#fb6c6c');
        p2.slideUp(500,function() {
             p2.remove();
        });
    }
    
    function myc_nb_add() {
        //Add New Notebook
        //Use notebook ajax
        var type = 'add-nb';
        if (mct_nb_post_id) type = 'edit-nb'
        var title = jQuery('#mct-nb-title').attr('value');
        var desc = jQuery('#mct-nb-desc').attr('value');
        var page = jQuery('#nb-page-display').attr('value');
        var notebk = jQuery('#nb-parent-id').attr('value');
        var nonce = jQuery('input#_wpnonce').attr('value');
        var data = { title: title,
              nonce: nonce,
              desc: desc,
              type: type,
              page: page,
              notebk: notebk,
              postid: mct_nb_post_id,
              action: 'mct_nb_notebk_ajax'};
        jQuery('#saving').css('display', 'inline');
        jQuery.post(mct_nb_notedat.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            if (status == 'Ok') {
              location.reload(true);
            } else {
                jQuery('#saving').css('display', 'none');
                alert(status);
            }
        return false;
        });
    }
    
    function myc_nb_del() {
        var nonce = jQuery('input#_wpnonce').attr('value');
        var page = jQuery('#nb-page-display').attr('value');
        var data = { type: 'delete',
              postid: mct_nb_post_id,
              nonce: nonce,
              page: page,
              action: 'mct_nb_notebk_ajax'};
        jQuery('#saving').css('display', 'inline');
        jQuery.post(mct_nb_notedat.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            var action = jQuery(data).find('supplemental action').text();
            var remove = jQuery(data).find('supplemental remove').text();
            var edit = jQuery(data).find('supplemental edit').text();
            var url = '';
            jQuery('#saving').css('display', 'none');
            if (status == 'Ok') {
                remove_item(remove);
            } else {
                alert(status);
            }
        });
        return false;
    }
   