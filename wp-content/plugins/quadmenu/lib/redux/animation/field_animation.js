
/*global jQuery, document, redux*/

(function ($) {
    'use strict';

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.animation = redux.field_objects.animation || {};

    $(document).ready(
            function () {
                //redux.field_objects.animation.init();
            }
    );

    redux.field_objects.animation.init = function (selector) {

        if (!selector) {
            selector = $(document).find('.redux-container-animation:visible');
        }
        $(selector).each(
                function () {
                    var el = $(this);
                    var parent = el;
                    if (!el.hasClass('redux-field-container')) {
                        parent = el.parents('.redux-field-container:first');
                    }
                    if (parent.is(':hidden')) { // Skip hidden fields
                        return;
                    }
                    if (parent.hasClass('redux-field-init')) {
                        parent.removeClass('redux-field-init');
                    } else {
                        return;
                    }
                    var default_params = {
                        width: 'resolve',
                        triggerChange: true,
                        allowClear: true
                    };

                    var select2_handle = el.find('.select2_params');

                    if (select2_handle.size() > 0) {
                        var select2_params = select2_handle.val();

                        select2_params = JSON.parse(select2_params);
                        default_params = $.extend({}, default_params, select2_params);
                    }

                    el.find('.redux-animation-options').select2(default_params);

                    el.find('.redux-animation-action').select2(default_params);
                    
                    el.find('.redux-animation-speed').select2(default_params);

                    el.find('.redux-animation-input').on(
                            'change', function () {

                                var options = $(this).parents('.redux-field:first').find('.field-options').val();

                                if ($(this).parents('.redux-field:first').find('.redux-animation-options').length !== 0) {
                                    options = $(this).parents('.redux-field:first').find('.redux-animation-options option:selected').val();
                                }

                                var height = $(this).parents('.redux-field:first').find('.field-action').val();

                                if ($(this).parents('.redux-field:first').find('.redux-animation-action').length !== 0) {
                                    height = $(this).parents('.redux-field:first').find('.redux-animation-action option:selected').val();
                                }

                                var width = $(this).parents('.redux-field:first').find('.field-speed').val();

                                if ($(this).parents('.redux-field:first').find('.redux-animation-speed').length !== 0) {
                                    width = $(this).parents('.redux-field:first').find('.redux-animation-speed option:selected').val();
                                }

                                if (typeof options !== 'undefined') {
                                    el.find('#' + $(this).attr('rel')).val($(this).val() + options);
                                } else {
                                    el.find('#' + $(this).attr('rel')).val($(this).val());
                                }
                            }
                    );

                    el.find('.redux-animation-options').on(
                            'change', function () {
                                $(this).parents('.redux-field:first').find('.redux-animation-input').change();
                            }
                    );

                    el.find('.redux-animation-action').on(
                            'change', function () {
                                $(this).parents('.redux-field:first').find('.redux-animation-input').change();
                            }
                    );

                }
        );


    };
})(jQuery);