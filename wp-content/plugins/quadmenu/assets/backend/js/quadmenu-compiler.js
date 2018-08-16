(function ($) {
    "use strict";

    if (!window.console)
        console = {};

    console.log = console.log || function (name, data) {
    };

    console.error = console.error || function (data) {
    };

    function eachLessFile(files, variables, action) {

        var startingpoint = $.Deferred();

        startingpoint.resolve();

        var promises = [];

        $.each(files, function (i, file) {

            var da = new DeferredLess({
                modifyVars: variables,
                lessPath: file,
                action: action,
                id: i,
            });

            $.when(startingpoint).then(function () {
                setTimeout(function () {
                    da.invoke();
                }, 200);
            });

            startingpoint = da;

            promises.push(startingpoint)

        });

        return promises;

    }

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
                    self.trigger(output, self.options.action, self.options.id);
                    self.deferred.resolve();
                },
                function (error) {
                    console.log(error);
                    self.deferred.resolve();
                });


    }

    DeferredLess.prototype.trigger = function (output, action, id) {

        if (typeof (action) == 'undefined')
            console.log('Undefined action');

        $(document).trigger('quadmenu_compiler_' + action, [output, id]);

    };

    DeferredLess.prototype.promise = function () {
        return this.deferred.promise();
    };

    $(document).on('quadmenu_compiler_end', function (e, notice) {

        var $compiler = $('#redux-compiler-hook');

        if (!$compiler.length)
            return;

        $('#redux-compiler-hook').val(0);

    });

    $(document).on('quadmenu_compiler_error', function (e, notice) {
        alert(notice);
    });

    $(document).on('quadmenu_compiler_success', function (e, notice) {

        var $notification_bar = $(document.getElementById('redux_notification_bar')),
                $alert = $('.quadmenu-admin-compiler-alert'),
                $button = $alert.find('input[name="quadmenu_compiler"]');

        $alert.addClass('hidden');

        $button.attr('disabled', true).removeClass('button-critical').addClass('delete');

        $notification_bar.append(notice).find('.saved_notice').delay(4000).slideUp();

    });

    $(document).on('quadmenu_compiler_change', function (e, output, id) {

        $('style#quadmenu_customizer_' + id, 'head').remove();

        $('head').append('<style id="quadmenu_customizer_' + id + '">' + output.css + '</style>');
        console.log('Append CSS');

    });

    $(document).on('quadmenu_compiler_save', function (e, output, id) {

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
                $(document).delay(4000).trigger('quadmenu_compiler_success', [response.notification_bar]);

                console.log('Compiled [' + output.imports[0] + ']');
            },
            error: function (response) {

                console.log(response);

                $(document).delay(4000).trigger('quadmenu_compiler_error', response.responseText);
            },
            complete: function (xhr, status, error) {
            }
        });
    });

    $(document).on('quadmenu_compiler_files', function (e, files, variables, action) {

        var $this = $(this),
                $overlay = $('#redux_ajax_overlay'),
                $buttons = $('.redux-action_bar input'),
                $spinner = $('.redux-action_bar .spinner');

        if ($overlay.length)
            $overlay.fadeIn();

        if ($spinner.length)
            $spinner.addClass('is-active');

        console.log('Starting compiler!');

        var promises = eachLessFile(files, variables, action);

        $.when.apply($, promises).done(function () {

            if ($overlay.length)
                $overlay.fadeOut();

            if ($spinner.length)
                $spinner.removeClass('is-active');

            if ($buttons.length)
                $buttons.removeAttr('disabled');

            $this.trigger('quadmenu_compiler_end');

            console.log('Ending compiler!');

        });

    });

    $(document).on('ajaxSuccess.redux_save', function (e, xhr, settings) {

        var $compiler = $('#redux-compiler-hook');

        if (!$compiler.length)
            return;

        var compiler = $compiler.val(),
                response = $.parseJSON(xhr.responseText);

        if (compiler != 1)
            return;

        if (!response.variables)
            return;

        try {
            $(this).trigger('quadmenu_compiler_files', [quadmenu.files, response.variables, 'save']);
        } catch (error) {
            alert('Not JSON');
        }

    });

    $(window).on('load', function () {

        if (typeof (quadmenu) == 'undefined')
            return;

        if (quadmenu.compiler != 1)
            return;

        if (!quadmenu.variables)
            return;

        if (!quadmenu.files)
            return;

        console.log(quadmenu);

        $(document).trigger('quadmenu_compiler_files', [quadmenu.files, quadmenu.variables, 'save']);
    });

    $(document).on('ready', function (e) {

    });

    $(document).on('quadmenu_customizer_save', function (e) {

        if (typeof (quadmenu) == 'undefined')
            return;

        if (!quadmenu.variables)
            return;

        if (!quadmenu.files)
            return;

        $(this).trigger('quadmenu_compiler_files', [quadmenu.files, quadmenu.variables, 'save']);

    });

})(jQuery);