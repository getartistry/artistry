(function($) {
    $(function() {

        // Check if affiliate placed partner's ID instead of affiliate ID
        if ($('#aid').val()[0] === '4') {
            alert(objectL10n.aid_starts_with_four);
            $('#aid').focus();
        }

        // Setup a click handler to initiate the Ajax request and handle the response
        $('#preview_button').click(function() {

            var ajax_loader = '<div id=\"bos_ajax_loader\"><h3>' + objectL10n.updating +
                '</h3>';
            /*ajax_loader = ajax_loader + '<img src=\"' ;
                ajax_loader = ajax_loader + objectL10n.images_js_path ;
                ajax_loader = ajax_loader + '\/ajax-loader.gif">' ;*/
            ajax_loader = ajax_loader + '</div>';
            $('#bos_preview').append(ajax_loader);
            $('#flexi_searchbox').css('opacity', '0.5');

            var data = {

                action: 'bos_preview', // The function for handling the request
                nonce: $('#bos_ajax_nonce').text(), // The security nonce
                aid: $('#aid').val(), // bgcolor
                destination: $('#destination').val(), // destination
                dest_id: $('#dest_id').val(),
                dest_type: $('#dest_type').val(),
                widget_width: $('#widget_width').val(), // widget_width
                calendar: $('#calendar:checked').val(), // calendar
                flexible_dates: $('#flexible_dates:checked').val(), // flexible dates
                month_format: $('.month_format:checked').val(), // logodim
                logodim: $('.logodim:checked').val(), // logodim
                logopos: $('#logopos').val(), // logopos    
                buttonpos: $('#buttonpos').val(), // buttonpos  
                bgcolor: $('#bgcolor').val(), // bgcolor
                textcolor: $('#textcolor').val(), // textcolor
                submit_bgcolor: $('#submit_bgcolor').val(), // submit_bgcolor
                submit_bordercolor: $('#submit_bordercolor').val(), // submit_bordercolor
                submit_textcolor: $('#submit_textcolor').val(), // submit_textcolor
                maintitle: $('#maintitle').val(), // maintitle
                dest_title: $('#dest_title').val(), // destination  
                checkin: $('#checkin').val(), // checkin
                checkout: $('#checkout').val(), // checkout
                submit: $('#submit').val() // submit                

            };

            $.post(ajaxurl, data, function(response) {

                $('#bos_preview').html(response);
                $('#flexi_searchbox').css('opacity', '1');
                $('#bos_ajax_loader').empty();

            });


        }); // $('#preview_button').click( function()


        // Setup a click handler to initiate the reset values button
        $('#reset_default').click(function() {

            //alert( 'values reset' );
            // Set all values to default values

            $('#aid').val(objectL10n.aid);
            $('#destination').val('');
            $('#dest_id').val('');
            $('#dest_type').val(objectL10n.dest_type);
            $('#display_in_custom_post_types').val('');
            $('#widget_width').val('');
            $('#calendar').val(objectL10n.calendar);
            $('.month_format').val(objectL10n.month_format);
            $('#flexible_dates').val(objectL10n.flexible_dates);
            $('.logodim').val(objectL10n.logodim);
            $('#logopos').val(objectL10n.logopos);
            //$( '#prot' ).val( objectL10n.prot ) ;
            $('#buttonpos').val(objectL10n.buttonpos);
            $('#bgcolor').val(objectL10n.bgcolor);
            $('#textcolor').val(objectL10n.textcolor);
            $('#submit_bgcolor').val(objectL10n.submit_bgcolor);
            $('#submit_bordercolor').val(objectL10n.submit_bordercolor);
            $('#submit_textcolor').val(objectL10n.submit_textcolor);
            $('#maintitle').val('');
            $('#dest_title').val('');
            $('#checkin').val('');
            $('#checkout').val('');
            $('#submit').val('');

        }); // $('#reset_default').click( function()*/      


        // colour picker for specific fields    
        $(
            '#bgcolor,#textcolor,#submit_bgcolor,#submit_bordercolor,#submit_textcolor'
        ).wpColorPicker();

        //show/hide
        var item_handle = $('.bos_hide');
        var item_arrow = 'p > span';
        //var item_to_show =  $( '.bos_hide +  table.form-table' );

        item_handle.click(function() {
            $(this).next().toggle('fast');
            $(this).find(item_arrow).toggleClass('bos_open');
        });

    });
})(jQuery);