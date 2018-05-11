jQuery(document).ready(function($) {

    function remove_all_image_classes()
    {
        $("#content_ifr").contents().find("img").removeClass("size-thumbnail");
        $("#content_ifr").contents().find("img").removeClass("size-medium");
        $("#content_ifr").contents().find("img").removeClass("size-large");
        $("#content_ifr").contents().find("img").removeAttr("width");
        $("#content_ifr").contents().find("img").removeAttr("height");
    }

    tinymce.PluginManager.add('curationsuite_mce_button', function( editor, url ) {
        editor.addButton( 'curationsuite_mce_button', {
            text: 'Shortcuts',
            icon: 'icon gavickpro-own-icon',
            type: 'menubutton',
            menu: [
                {
                    text: '+ Add Image Credit',
                    tooltip: 'Click to add image(s) credit at the end of your post',
                    style: 'font-size: 20px',
                    onclick: function() {
                        //$("#content_ifr").contents().find("img").attr("width","50");
                        var title = $('#title').val();
                        var post_id = $('#post_ID').val();
                        var image_credit_value_one = $('#image_credit_value_one').val();
                        var image_credit_value_two = $('#image_credit_value_two').val();
                        var load_social_media_actions_nonce = $('#load_social_media_actions_nonce').val();
                        var text_return_type = 'simple';
                        //$('#ybi_cu_image_credit_actions').html(spinner);
                        //tb_show("My Caption", "#TB_inline?width=600&height=550&inlineId=cs_modal_popup");

                        //$('#cs_modal_popup').css({"display": "block", "visibility": "visible"});
                        //$('#cs_modal_popup').html(inMessage).show().delay(1000).hide(100);
                        $('#cs_modal_popup').show().delay(1000);
                        $('#cs_modal_text').html('<p><i class="fa fa-spinner fa-spin"></i> Gathering image credit info from post...</p>');
                        // this is grabbing the current text in the editor
                        var original_text = tinyMCE.activeEditor.getContent({format: 'raw'});
                        //$('#ybi_cu_image_credit_actions').html();
                        //var post_content = tinyMCE.get('content');

                        data = {
                            action: 'ybi_curation_suite_image_credit_load',
                            post_content: original_text,
                            title: title,
                            post_id: post_id,
                            image_credit_value_one: image_credit_value_one,
                            image_credit_value_two: image_credit_value_two,
                            text_return_type: text_return_type,
                            load_social_media_actions_nonce: load_social_media_actions_nonce
                        };
                        $.ajax({
                            type: "POST",
                            data: data,
                            dataType: "json",
                            url: ajax_url,
                            success: function (search_response) {

                                //$('#ybi_cu_image_credit_actions').html(search_response.results);
                                //editor.selection.setContent(search_response.results);
                                wrap_element = $("#curation_suite_image_credit_wrap_element").val();
                                if (typeof(wrap_element) === "undefined" || wrap_element == '')
                                    wrap_element = 'none';

                                var wrap_class = '';
                                wrap_class = $("#curation_suite_image_credit_wrap_class").val();
                                if (typeof wrap_class === "undefined" || wrap_class == '')
                                    wrap_class = 'cu_image_credit';

                                if (wrap_element == 'none') {
                                    editor.dom.add(editor.getBody(), 'p', '', search_response.results);
                                }
                                else {
                                    editor.dom.add(editor.getBody(), wrap_element, {'class': wrap_class}, search_response.results);
                                }


                                //editor.dom.add(editor.getBody(), 'p', '', search_response.results);
                                $('#cs_modal_text').html('<p class="cs_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Image credit added at end of post.</p>');
                                $('#cs_modal_popup').hide(3000);
                                //$('#cs_modal_popup').css({"display": "block", "visibility": "visible"});

                            }
                        });

                    }
                },
                {
                    text: 'Image Sizing',
                    tooltip: 'Clicking on one of the sizes below will resize all images in your post.',
                    menu: [
                        {
                            text: '50px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","50");
                            }
                        },
                        {
                            text: '75px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","75");
                            }
                        },
                        {
                            text: '100px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","100");
                            }
                        },
                        {
                            text: '150px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","150");
                            }
                        },
                        {
                            text: '200px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","200");
                            }
                        },
                        {
                            text: '250px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","250");
                            }
                        },
                        {
                            text: '300px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","300");
                            }
                        },
                        {
                            text: '350px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","350");
                            }
                        },
                        {
                            text: '400px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","400");
                            }
                        },
                        {
                            text: '450px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","450");
                            }
                        },
                        {
                            text: '500px',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").attr("width","500");
                            }
                        }
                    ]
                },
                {
                    text: 'Image Alignment',
                    tooltip: 'Clicking on one of the options below will align your images',
                    menu:[
                        {
                            text: 'All Left',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").removeClass("alignleft");
                                $("#content_ifr").contents().find("img").removeClass("alignright");
                                $("#content_ifr").contents().find("img").removeClass("aligncenter");
                                $("#content_ifr").contents().find("img").addClass("alignleft");
                            }
                        },
                        {
                            text: 'All Center',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").removeClass("alignleft");
                                $("#content_ifr").contents().find("img").removeClass("alignright");
                                $("#content_ifr").contents().find("img").removeClass("aligncenter");
                                $("#content_ifr").contents().find("img").addClass("aligncenter");
                            }
                        },
                        {
                            text: 'All Right',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").removeClass("alignleft");
                                $("#content_ifr").contents().find("img").removeClass("alignright");
                                $("#content_ifr").contents().find("img").removeClass("aligncenter");
                                $("#content_ifr").contents().find("img").addClass("alignright");
                            }
                        },
                        {
                            text: 'Alternate Left First',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").removeClass("alignleft");
                                $("#content_ifr").contents().find("img").removeClass("alignright");
                                $("#content_ifr").contents().find("img").removeClass("aligncenter");
                                var current_align = "alignleft";
                                $("#content_ifr").contents().find( "img" ).each(function() {
                                    if(current_align=="alignleft") {
                                        $( this ).addClass( "alignleft" );
                                        current_align="alignright";

                                    } else {
                                        $( this ).addClass( "alignright" );
                                        current_align="alignleft";
                                    }

                                });
                            }
                        },
                        {
                            text: 'Alternate Right First',
                            onclick: function() {
                                $("#content_ifr").contents().find("img").removeClass("alignleft");
                                $("#content_ifr").contents().find("img").removeClass("alignright");
                                $("#content_ifr").contents().find("img").removeClass("aligncenter");
                                var current_align = "alignright";
                                $("#content_ifr").contents().find( "img" ).each(function() {
                                    if(current_align=="alignleft") {
                                        $( this ).addClass( "alignleft" );
                                        current_align="alignright";

                                    } else {
                                        $( this ).addClass( "alignright" );
                                        current_align="alignleft";
                                    }

                                });
                            }
                        }
                    ]
                },
                {
                    text: 'Remove Blockquotes',
                    onclick: function() {
                        $('#cs_modal_popup').show().delay(1000);
                        $('#cs_modal_text').html('<p><i class="fa fa-spinner fa-spin"></i> Removing blockquotes from post...</p>');
                        $("#content_ifr").contents().find("blockquote").contents().wrap('<p></p>');
                        $("#content_ifr").contents().find("blockquote").contents().unwrap();
                        $("#content_ifr").contents().find("a").parent().contents().unwrap();
                        $('#cs_modal_text').html('<p class="cs_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Blockquotes removed.</p>');
                        $('#cs_modal_popup').hide(3000);
                    }
                }


            ]
        });
    });
});