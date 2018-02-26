/*global redux_change, wp, redux*/

(function ($) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.icons = redux.field_objects.icons || {};


    $(document).ready(
            function () {
                //redux.field_objects.icons.init();
            }
    );

    redux.field_objects.icons.init = function (selector) {

        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-icons:visible');
        }

        $(selector).each(
                function () {
                    var el = $(this);

                    redux.field_objects.media.init(el);

                    el.parent().prev().hide();
                    var parent = el;
                    if (!el.hasClass('redux-field-container')) {
                        parent = el.parents('.redux-field-container:first');
                    }
                    if (parent.is(":hidden")) { // Skip hidden fields
                        return;
                    }

                    if (parent.hasClass('redux-container-icons')) {
                        parent.addClass('redux-field-init');
                    }

                    if (parent.hasClass('redux-field-init')) {
                        parent.removeClass('redux-field-init');
                    } else {
                        return;
                    }

                    el.find('.redux-icons-remove').live(
                            'click', function () {
                                redux_change($(this));

                                $(this).parent().siblings().find('input[type="text"]').val('');
                                $(this).parent().siblings().find('textarea').val('');
                                $(this).parent().siblings().find('input[type="hidden"]').val('');

                                var slideCount = $(this).parents('.redux-container-icons:first').find('.redux-icons-accordion-group').length;

                                if (slideCount > 1) {
                                    $(this).parents('.redux-icons-accordion-group:first').slideUp(
                                            'medium', function () {
                                                $(this).remove();
                                            }
                                    );
                                } else {
                                    var content_new_title = $(this).parent('.redux-icons-accordion').data('new-content-title');

                                    $(this).parents('.redux-icons-accordion-group:first').find('.remove-image').click();
                                    $(this).parents('.redux-container-icons:first').find('.redux-icons-accordion-group:last').find('.redux-icons-header').text(content_new_title);
                                }
                            }
                    );

                    //el.find( '.redux-icons-add' ).click(
                    el.find('.redux-icons-add').off('click').click(
                            function () {
                                var newSlide = $(this).prev().find('.redux-icons-accordion-group:last').clone(true);

                                var slideCount = $(newSlide).find('.slide-title').attr("name").match(/[0-9]+(?!.*[0-9])/);
                                var slideCount1 = slideCount * 1 + 1;

                                $(newSlide).find('input[type="text"], input[type="hidden"], textarea, .redux-icons-add-icon').each(
                                        function () {

                                            $(this).attr(
                                                    "name", jQuery(this).attr("name").replace(/[0-9]+(?!.*[0-9])/, slideCount1)
                                                    ).attr("id", $(this).attr("id").replace(/[0-9]+(?!.*[0-9])/, slideCount1));
                                            $(this).val('');
                                            if ($(this).hasClass('slide-sort')) {
                                                $(this).val(slideCount1);
                                            }
                                        }
                                );

                                var content_new_title = $(this).prev().data('new-content-title');

                                $(newSlide).find('.screenshot').removeAttr('style');
                                $(newSlide).find('.screenshot').addClass('hide');
                                $(newSlide).find('.screenshot a').attr('href', '');
                                $(newSlide).find('.remove-image').addClass('hide');
                                $(newSlide).find('.redux-option-image').attr('src', '').removeAttr('id');
                                $(newSlide).find('h3').text('').append('<span class="redux-icons-header">' + content_new_title + '</span>');
                                $(this).prev().append(newSlide);
                            }
                    );

                    el.find('.slide-title').keyup(
                            function (event) {
                                var newTitle = event.target.value;
                                $(this).parents().eq(3).find('.redux-icons-header').text(newTitle);
                            }
                    );

                }
        );
    };
})(jQuery);