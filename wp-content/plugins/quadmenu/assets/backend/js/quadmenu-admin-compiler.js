(function ($) {
    "use strict";

    if (!window.console)
        console = {};

    console.log = console.log || function (name, data) {
    };

    console.error = console.error || function (data) {
    };

    function DeferredLess(options) {

        this.options = options;
        this.deferred = $.Deferred();

    }

    DeferredLess.prototype.invoke = function () {

        var self = this;

        less.options.env = quadmenu.debug ? 'development' : 'production';

        less.options.logLevel = 0;

        less.options.useFileCache = true;

        return less.render('@import "' + self.options.lessPath + '";', self.options).then(
                function (output) {
                    $(document).trigger('quadmenu_compiler_save', [output]);
                    self.deferred.resolve();
                },
                function (error) {
                    console.log(error);
                    self.deferred.resolve();
                });


    }

    DeferredLess.prototype.promise = function () {
        return this.deferred.promise();
    };

    $(document).on('quadmenu_compiler_error', function (e, notice) {

        alert(notice);

    });

    $(document).on('quadmenu_compile_success', function (e, notice) {

        var $notification_bar = $(document.getElementById('redux_notification_bar')),
                $alert = $('.quadmenu-admin-compiler-alert'),
                $button = $alert.find('input[name="quadmenu_compiler"]');

        $alert.addClass('hidden');

        $button.attr('disabled', true).removeClass('button-critical').addClass('delete');

        $notification_bar.append(notice).find('.saved_notice').delay(4000).slideUp();

    });

    $(document).on('quadmenu_compiler_save', function (e, output) {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'quadmenu_compiler_save',
                output: output,
                nonce: quadmenu.nonce,
            },
            success: function (response) {
                $(document).delay(4000).trigger('quadmenu_compile_success', [response.notification_bar]);

                console.log('Compiled [' + output.imports[0] + ']');
            },
            error: function (response) {

                $(document).delay(4000).trigger('quadmenu_compiler_error', response.responseText);

                console.log(response.responseText);
            },
            complete: function (xhr, status, error) {
            }
        });
    });

    $(document).on('quadmenu_compiler_files', function (e, files, variables) {

        var $this = $(this),
                $overlay = $(document.getElementById('redux_ajax_overlay')),
                $buttons = $('.redux-action_bar input').removeAttr('disabled'),
                $spinner = $('.redux-action_bar .spinner');

        $spinner.addClass('is-active');
        $overlay.fadeIn();

        console.log('Starting compiler!');

        var startingpoint = $.Deferred();

        startingpoint.resolve();

        var promises = [];

        $.each(files, function (i, file) {

            var da = new DeferredLess({
                modifyVars: variables,
                lessPath: file,
            });

            $.when(startingpoint).then(function () {
                setTimeout(function () {
                    da.invoke();
                }, 200);
            });

            startingpoint = da;

            promises.push(startingpoint)

        });

        $.when.apply($, promises).done(function () {

            $overlay.fadeOut();

            $buttons.removeAttr('disabled');

            $spinner.removeClass('is-active');

            $this.trigger('quadmenu_compiler_end');

            console.log('Ending compiler!');

        });

    });

    $(document).on('ready', function (e) {
        
        if (typeof (quadmenu) == 'undefined')
            return;

        if (quadmenu.compiler != 1)
            return;

        if (!quadmenu.variables)
            return;

        if (!quadmenu.files)
            return;
        
        $(this).trigger('quadmenu_compiler_files', [quadmenu.files, quadmenu.variables]);

    });

    $(document).on('ajaxSuccess.redux_save', function (e, xhr, settings) {

        var $compiler = $('#redux-compiler-hook'),
                compiler = $compiler.val(),
                response = $.parseJSON(xhr.responseText);

        if (compiler != 1)
            return;

        if (!response.variables)
            return;

        try {
            $(this).trigger('quadmenu_compiler_files', [quadmenu.files, response.variables]);
        } catch (error) {
            alert('Not JSON');
        }

    });

})(jQuery);