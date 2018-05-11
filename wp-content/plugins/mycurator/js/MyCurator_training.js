//Javascript for Training post ajax V1.0.4
jQuery(document).ready(function($) {
    //Bulk Drop Down entries
    if (mct_ai_train.page_type == 'admin') {
        $('<option>').val('postlive').text('Make Live').appendTo("select[name='action']");
        $('<option>').val('postlive').text('Make Live').appendTo("select[name='action2']");
        $('<option>').val('postdraft').text('Make Draft').appendTo("select[name='action']");
        $('<option>').val('postdraft').text('Make Draft').appendTo("select[name='action2']");       
        $('<option>').val('traingood').text('Train Good').appendTo("select[name='action']");
        $('<option>').val('traingood').text('Train Good').appendTo("select[name='action2']");  
        $('<option>').val('trainbad').text('Train Bad').appendTo("select[name='action']");
        $('<option>').val('trainbad').text('Train Bad').appendTo("select[name='action2']");  
        $('<option>').val('multi').text('Set as Multi').appendTo("select[name='action']");
        $('<option>').val('multi').text('Set as Multi').appendTo("select[name='action2']");      
        $('<option>').val('author').text('Change Author').appendTo("select[name='action']");
        $('<option>').val('author').text('Change Author').appendTo("select[name='action2']");
        //Remove published from top
        $('ul.subsubsub li.publish').css('display','none');
        //Set up change author fields
        $('#doaction, #doaction2').click(function(e){
            var n = $(this).attr('id').substr(2);
            if ( $('select[name="'+n+'"]').val() == 'author' ) {
                    e.preventDefault();
                    //Add in the elements
                    if ($('tr#mct-ai-chg-author').length >0 ) return;
                    $('tbody#the-list').prepend('<tr id="mct-ai-chg-author"></tr>');
                    $('tr#mct-ai-chg-author').prepend('<td id="mct-ai-colchange" colspan="9" class="colspanchange" ></td>');
                    $('td#mct-ai-colchange').prepend('<h4>Change Author</h4>',$('tr#bulk-edit label.inline-edit-author').last());
                    $('label.inline-edit-author').after($('tr#bulk-edit p.submit').last());
                    $('td#mct-ai-colchange input#bulk_edit').removeClass('alignright');
                    //A little CSS
                    $('td#mct-ai-colchange select').css('margin-left','5px');
                    $('td#mct-ai-colchange input#bulk_edit').css('margin-left','5px');
                    //Cancel on change author
                    $('td#mct-ai-colchange a.cancel').click(function(){
                        //Move elements back
                        $('tr#bulk-edit div.inline-edit-col').prepend($('tr#mct-ai-chg-author label.inline-edit-author'));
                        $('tr#bulk-edit fieldset.inline-edit-col-right').after($('tr#mct-ai-chg-author p.submit'));
                        $('tr#mct-ai-chg-author').remove();
                        return false;
                    });
                    //Scroll to the top
                    $('html, body').animate( { scrollTop: 0 }, 'fast' );
            }
    });
    } else {
        $('#mct-ai-attriblink a:first-child').click(function(){
            //var tlink = $(this).find('a').attr('href');
            window.open(this);
            return false;
        });
    }
    //Training tags click
    $('.mct-ai-link').click(function(){
        var link = this;
        var href = $(link).attr('href');
        //Get the query args
        var qstr = href.replace(/^.*\?(.*)$/, '$1');
        var nonce = href.replace(/^.*_wpnonce=([a-z0-9]+).*$/, '$1');
        //If we are going to edit, pass on the arg to a new window and remove this item
        if (qstr.indexOf('move') != -1 && mct_ai_train.editmove == '1') {
            var post_id = qstr.replace(/^.*move=([0-9]+).*$/, '$1');
            window.open(href);
            remove_item(post_id);
            return false;
        }
        var data = { qargs: qstr,
              nonce: nonce,
              action: 'mct_ai_train_ajax'};
        $(link).siblings('img').css('display', 'inline'); //spinner while we work
        $.post(mct_ai_train.ajaxurl, data, function (data) {
            var status = $(data).find('response_data').text();
            var action = $(data).find('supplemental action').text();
            var remove = $(data).find('supplemental remove').text();
            var edit = $(data).find('supplemental edit').text();
            var url = '';
            $(link).siblings('img').css('display', 'none'); //hide spinner
            if (status == 'Ok') {
                switch (action) {
                    case 'multi':
                        $(link).css('display', 'none');
                        if (mct_ai_train.page_type == 'admin') {
                            var p2 = $(link).parent().parent().parent();
                            $(p2).find('.column-class').text('multi');
                        } else {
                            var p2 = $(link).parent().parent();
                            var ailink = $(p2).find('.ai_class-tags');
                            $(ailink).text('multi');
                            var linkstr = $(ailink).attr('href');
                            linkstr = linkstr.replace(/^(.*ai_class=)(.*)$/,'$1multi');
                            $(ailink).attr('href',linkstr);
                        }
                        break;
                    case 'bad':
                    case 'delete':
                        remove_item(remove,true);
                        break;
                    case 'move':
                    case 'draft':
                    case 'good':
                        if (remove == 0) {
                            $(link).css('display', 'none');
                            //remove good/bad tags
                            var ss = $(link).siblings();
                            $(link).next().css('display', 'none'); 
                            //$(link).next().next().next().css('display', 'none');
                            if (mct_ai_train.page_type == 'admin') {
                                var p2 = $(link).parent().parent().parent();
                                var n1 = $(p2).find('.column-class');
                                $(n1).text('good');
                            } else {
                                var p2 = $(link).parent().parent();
                                var ailink = $(p2).find('.ai_class-tags');
                                $(ailink).text('good');
                                var linkstr = $(ailink).attr('href');
                                linkstr = linkstr.replace(/^(.*ai_class=)(.*)$/,'$1good');
                                $(ailink).attr('href',linkstr);
                            }
                        } else {
                            remove_item(remove);
                            if (edit == 'yes'){
                                var url = mct_ai_train.editurl+'?post='+remove+'&action=edit';
                                //window.location.href = url;
                            }
                        }
                        break;
                }
            } else {
                alert(status);
            }
        })
        .fail(function(data) {
            location.reload(true); //not sure why, so lets just refresh
            return false;    
        });
        
        return false;
    });    //End train tags click
    //Notebook Dialog
    $("#nb-dialog").dialog({ //Set up dialog box for images
        autoOpen : false, 
        dialogClass  : 'wp-dialog', 
        modal : true, 
        width: 500,
        closeOnEscape: true,
        buttons: {
        "Move to Notebook": function(){myc_nb_move();$('#mct-nb-notes').val('');$( this ).dialog( "close" );  },
        Cancel: function() {$('#mct-nb-notes').val('');$( this ).dialog( "close" ); }}
    });
    //Click on notebook link
    $(".mct-ai-notebk").click(function(){
        var link = this;
        var postid = $(link).attr('id');
        var title = $(link).attr('title');
        $('p#nb-title').text(title);
        nb_post_id = postid;
        $("#nb-dialog").dialog("open");
        return false;
    });

});
    //Quick edit
    function quick_post(post, type) {
        var new_title = jQuery('#title-'+post).attr('value');
        var new_excerpt = jQuery('#excerpt-'+post).attr('value');
        var new_note = jQuery('#note-'+post).attr('value');
        var nonce = jQuery('input#_wpnonce').attr('value');
        var qstr = "quick="+post+"&_wpnonce="+nonce;
        var data = { qargs: qstr,
              nonce: nonce,
              title: new_title,
              excerpt: new_excerpt,
              note: new_note,
              type: type,
              action: 'mct_ai_train_ajax'};
        jQuery('#saving-'+post).css('display', 'inline');
        jQuery.post(mct_ai_train.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            if (status == 'Ok') {
              remove_item(post);
             
            } else {
                alert(status);
            }
            tb_remove();
        }).fail(function(data) {
            location.reload(true); //not sure why, so lets just refresh
            return false;    
        });
    }
    
    function remove_item(post,trash) {
        if (mct_ai_train.page_type == 'admin') {
            var p2 = jQuery('tr.post-'+post);
            p2.css('background-color','#fb6c6c');
            p2.slideUp(500,function() {
                 p2.remove();
            });
            //update all
            var cnt = parseInt(jQuery('ul.subsubsub li.all span.count').text().substring(1))-1; //strip off leading ( for parsint
            jQuery('ul.subsubsub li.all span.count').text('('+cnt.toString()+')');
            //update trash if not undefined
            if (trash !== undefined){
                var cnt = parseInt(jQuery('ul.subsubsub li.trash span.count').text().substring(1))+1; //strip off leading ( for parsint
                jQuery('ul.subsubsub li.trash span.count').text('('+cnt.toString()+')');
            }
        } else {
            var p2 = jQuery('div.post-'+post);
            p2.css('background-color','#fb6c6c');
            p2.slideUp(500,function() {
              p2.remove();
            });
        }
    }
    
    function myc_nb_move() {
        //Move training post to Notebook
        //Use training ajax
        //post in nb_post_id
        
        var new_note = jQuery('#mct-nb-notes').attr('value');
        var notebk = jQuery('select#mct-nb-select option:selected').val();
        var newnbstr = jQuery('#mct-nb-newnb').attr('value');
        if (typeof notebk === 'undefined') {
            if ((typeof newnbstr === 'undefined')) return false;
            if ( newnbstr.length == 0 ) return false;
        }
        var nonce = jQuery('input#_wpnonce').attr('value');
        var qstr = "notebk="+nb_post_id+"&_wpnonce="+nonce;
        var data = { qargs: qstr,
              nonce: nonce,
              note: new_note,
              nbook: notebk,
              newnb: newnbstr,
              action: 'mct_ai_train_ajax'};
        jQuery('#saveimg-'+nb_post_id).css('display', 'inline');
        jQuery.post(mct_ai_train.ajaxurl, data, function (data) {
            var status = jQuery(data).find('response_data').text();
            jQuery('#saveimg-'+nb_post_id).css('display', 'none');
            if (status == 'Ok') {
              //var newnbval = jQuery('#mct-nb-newnb').attr('value');
              if ((typeof newnbstr === 'undefined') ) {
                  remove_item(nb_post_id);
                  return false;
              }
              if ( newnbstr.length > 0 ) {
                  location.reload(true);
              } else {
                  remove_item(nb_post_id);
              }
            } else {
                alert(status);
            }
            return false;
        })
        .fail(function(data) {
            location.reload(true); //not sure why, so lets just refresh
            return false;    
        });
    }
   