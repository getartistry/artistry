jQuery(document).ready(function () {
    jQuery(".indexmessage").hide();

    function debugerror(debugdata) {
        jQuery.ajax({
            data: { action: "save_debug",
                    debugdata: debugdata },
            success: function (result) {                
            },
            error: function (result, textStatus, errorThrown) {                
            }            
        });
    }

    
    function step1() {     
        jQuery(".indexmessage").show();
        jQuery(".result-message").html('Step 1/7: indexing attachments and invalid meta');
        jQuery.ajax({
            data: { action: "image_cleanup_step1" },
            success: function (result) {                
                if (result) {                    
                    //step2(); 
                    step1a(0, result.count);
                } else {
                    jQuery(".result-message").append(" <span style='color:red'>[Failed!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }
       
    function step1a(pos, total) {     
        jQuery(".result-message").html('Step 2/7: retrieving metadata from index ['+(pos+1)+'/'+total+']');
        jQuery.ajax({
            data: { action: "image_cleanup_step1a",
                    position: pos,
                    total: total },
            success: function (result) {
                jQuery(".error-message").append(result.errormsg);
                if (!result.halt)
                {
                    if (result["continue"]) {
                        step1a( pos+parseInt(wp_object.step_size), total );
                    } else {
                        step2();
                    }
                }
                else
                {
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }

    function step2() {
        jQuery(".result-message").html('Step 3/7: indexing image files and backup meta');        
        jQuery.ajax({
            data: { action: "image_cleanup_step2" },
            success: function (result) {
                if (result) {
                    step3();
                } else {
                    jQuery(".result-message").append(" <span style='color:red'>[Failed!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }
   
    function step3() {        
        jQuery(".result-message").html('Step 4/7: counting total posts with images');
        jQuery.ajax({
            data: { action: "image_cleanup_step3" },
            success: function (result) {                
                if (result) {                    
                    //step2(); 
                    step3a(0, result.count);
                } else {
                    jQuery(".result-message").append(" <span style='color:red'>[Failed!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }

    function step3a(pos, total) {     
        jQuery(".result-message").html('Step 5/7: indexing images used in posts/pages ['+(pos+1)+'/'+total+']');
        jQuery.ajax({
            data: { action: "image_cleanup_step3a",
                    position: pos,
                    total: total },
            success: function (result) {
                jQuery(".error-message").append(result.errormsg);
                if (!result.halt)
                {
                    if (result["continue"]) {                    
                        step3a( pos+parseInt(wp_object.post_step_size), total );
                    } else {
                        step4();
                    }
                }
                else
                {
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }
   
    function step4() {
        jQuery(".result-message").html('Step 6/7: indexing images used in scripts');        
        jQuery.ajax({
            data: { action: "image_cleanup_step4" },
            success: function (result) {
                if (result) {
                    step5();
                } else {
                    jQuery(".result-message").append(" <span style='color:red'>[Failed!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();
                }
            }
        });
    }

    function step5() {
        jQuery(".result-message").html('Step 7/7: trying to index image meta data');
        jQuery.ajax({
            data: { action: "image_cleanup_step5" },
            success: function (result) {
                if (result) {
                    jQuery(".result-message").html('Images have been indexed!<br>');
                   
                    var viewadd = "";
                    var view = image_cleanup_getURLParameter('view');
                    if (view!=='null')
                    {
                        viewadd = '&view=' + view;
                    }

                    window.location.href = window.location.protocol +'//'+ window.location.host + window.location.pathname + '?page=ImageCleanup' + viewadd; 

                } else {
                    jQuery(".result-message").append(" <span style='color:red'>[Failed!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();                    
                }
            }
        });
    }

    function image_cleanup_getURLParameter(name) {
      return decodeURI( (new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null]) [1] );
    }

    jQuery('a#imagecleanuprun').each(function () {
        jQuery(this).click(function () {            
            jQuery(".indexmessage").show();

            //change ajax setup for coming ajax queries
            jQuery.ajaxSetup({
                type: "POST",
                url: wp_object.ajax_url,
                async: true,
                dataType: "json",
                error: function (result, textStatus, errorThrown) {
                    jQuery(".result-message").append(" <span style='color:red'>[Fatal Error!] <a href='" + wp_object.log_url + "debug.json' target='_blank'>Logfile</a></span>");
                    jQuery(".indexmessage").hide();
                    debugerror(result.responseText);
                }
            });

            step1();
        });
    });
});