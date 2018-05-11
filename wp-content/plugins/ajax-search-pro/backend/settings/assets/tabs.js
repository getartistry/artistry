jQuery(function($){
    $('.tabs a').click(function (e) {
        e.preventDefault();
        var tid = $(this).attr('tabid');
        var tabsContent = $(this).parent().parent().next();

        tabsContent.children().each(function () {

            // Form nested tabs
            if ($(this).is('form')) {

                // Hackidy-hack. Yea, hide this form, later if this is the active one we show it..
                // .. so the non-hidden content of the form is not present on other tabs
                // .. whatever man, STOP QUESTIONING MY METHODS
                $(this).hide();
                $form = $(this);

                // This is should be done with a recursive call, but meh...
                $(this).children().each(function () {
                    // Only apply to nodes with the tabid attribute
                    if ($(this).is('[tabid]')) {
                        $(this).hide();
                        if ($(this).attr('tabid') == tid) {
                            $form.fadeIn();
                            $(this).fadeIn();
                        }
                    }
                });
                return;
            }

            // Only apply to nodes with the tabid attribute
            if ($(this).is('[tabid]')) {
                $(this).hide();
                if ($(this).attr('tabid') == tid) {
                    $(this).fadeIn();
                }
            }

        });

        $('a', $(this).parent().parent()).removeClass('current');
        $(this).addClass('current');
    });
});