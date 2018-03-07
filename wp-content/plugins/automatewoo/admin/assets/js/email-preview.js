jQuery(function($) {

    /**
     * Init
     */
    function init() {

        set_iframe_height();

        $(window).resize(function(){
            set_iframe_height();
        });

    }


    function set_iframe_height() {
        $('.email-iframe').height( $(window).height() - $('.email-preview-header').outerHeight() );
    }



    $('form.email-preview-send-test-form').submit(function(e){
        e.preventDefault();

        var $form = $(this);

        $form.addClass('aw-loading');
        $form.find('button').blur();

        var data = {
            action: 'aw_send_test_email',
            type: $form.find('[name="type"]').val(),
            to_emails: $form.find('[name="to_emails"]').val(),
            args: $.parseJSON( $form.find('[name="args"]').val() )
        };

        $.post( ajaxurl, data, function( response ){
            alert( response.data.message );
            $form.removeClass('aw-loading');
        });

        return false;

    });


    init();

});