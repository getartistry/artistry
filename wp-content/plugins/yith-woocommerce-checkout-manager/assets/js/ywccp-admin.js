jQuery(document).ready(function ($) {
    "use strict";

    var add_new = $('#add-new'),
        add_new_input = $('#add-new-name'),
        fields_add_edit_form = $("#ywccp_field_add_edit_form"),
        main_table = $('#ywccp_checkout_fields'),
        init_dialog_form = function (form, title, action, row, is_custom ) {

            form.attr('data-row', row);
            form.attr('data-action', action);

            // remove input for type custom
            if( ! is_custom ) {
                var input = form.find('tr.remove_default');
                if( input.length )
                    input.remove();
            }

            form.find('select[name="field_type"]').on('change', function () {
                var input = form.find('tr[data-hide]'),
                    value = $(this).val();

                if( ! input.length ) {
                    return
                }

                $.each( input, function(){
                   var deps = $(this).data('hide').split(',');

                    if( $.inArray( value, deps ) > -1 ){
                        $(this).hide();
                    }
                    else {
                        $(this).show();
                    }
                });
            }).trigger('change');

            form.dialog({
                title: title,
                modal: true,
                width: 500,
                resizable: false,
                autoOpen: false,
                buttons: [{
                    text: "Save",
                    click: function () {
                        if ($.edit_add_field(this)) {
                            $(this).dialog("close");
                        }
                    }
                }],
                close: function (event, ui) {
                    form.dialog("destroy");
                    form.remove();
                }
            });

        },
        format_name = function (name) {
            var prepend = main_table.data('prepend');
            // first replace all space with _
            name = name.replace(' ', '_');
            if (prepend != '' && name.indexOf(prepend) === -1) {
                name = prepend + name;
            }

            return name;
        };

    // OPEN ADD POPUP

    add_new_input.on('focus', function () {
        $(this).removeClass('required field-exists');
    });

    add_new.on('click', function () {

        var exists,
            val = add_new_input.val();

        if (val == '') {
            add_new_input.addClass('required');
            return false;
        }
        else {

            val = format_name(val);

            exists = main_table.find('input.field_name[value="' + val + '"]');
            if (exists.length) {
                add_new_input.addClass('field-exists');
                return false;
            }
            else {
                // clone the form
                var the_form = fields_add_edit_form.clone();
                // init dialog
                init_dialog_form( the_form, ywccp_admin.popup_add_title, 'add', '', true );
                // set name
                the_form.find('input[name="field_name"]').val(val);
                // finally open
                the_form.dialog('open');
            }
        }
    });

    // OPEN EDIT POPUP

    $(document).on('click', 'button.edit_field', function () {
        var tr = $(this).closest('tr'),
            row = tr.data('row'),
            input = tr.find('input[type="hidden"]');

        // clone the form
        var the_form = fields_add_edit_form.clone();

        // then load data
        $.each( input, function ( i, hidden ) {
            var name = $(hidden).data('name'),
                form_input = the_form.find('td *[name="' + name + '"]');

            if ( form_input.attr('type') == 'checkbox' ) {
                var value = $(hidden).val();
                if ( value == 0 ) {
                    form_input.removeAttr('checked');
                }
                else {
                    form_input.attr('checked', 'checked');
                }
            }
            else {
                form_input.val( $(hidden).val() );
            }
        });

        // first init and open dialog
        init_dialog_form( the_form, ywccp_admin.popup_edit_title, 'edit', row, tr.hasClass('is_custom') );

        // then open
        the_form.dialog('open');
    });

    // EDIT ADD FIELD HANDLER

    $.edit_add_field = function (form) {

        // validate fields
        // here the code for validate fields

        var fields = main_table.find('tbody tr'),
            action = $(form).data('action'),
            new_field,
            index;

        if (action == 'edit') {
            index = $(form).data('row');
            new_field = fields.filter('[data-row="' + index + '"]');
        }
        else {
            new_field = fields.filter(':not(.disabled-row)').last().clone();
            index = fields.size();

            // increment row index
            new_field.attr('data-row', index);
            // add class custom
            new_field.addClass('is_custom');
        }

        // change field value
        $.each(new_field.find('input[type="hidden"]'), function (i, hidden) {
            var name = $(hidden).data('name'),
                form_input = $(form).find('td *[name="' + name + '"]'),
                value = '',
                value_td = '';

            if (form_input.length) {
                if (form_input.attr('type') == 'checkbox') {
                    value = form_input.is(':checked') ? 1 : 0;
                    value_td = value == 1 ? ywccp_admin.enabled : '-';
                }
                else {
                    value = form_input.val();
                    if (name == 'field_name') {
                        value = format_name(value);
                    }
                    value_td = value;
                }

                // set new name
                $(hidden).val(value);

                new_field.find('.td_' + name).html(value_td);
            }
        });

        // add new row if add
        if (action == 'add') {
            fields.last().after(new_field);

            // reinit Tooltips
            if( typeof $.fn.tipTip != 'undefined' ) {
                var tiptip_args = {
                    'attribute': 'data-tip',
                    'fadeIn': 50,
                    'fadeOut': 50,
                    'delay': 200
                };
                new_field.find('.tips').tipTip(tiptip_args);
            }
        }

        return true;
    };

    // BULK ACTION

    $('.check-column input').on('change', function () {
        var t = $(this),
            fields_check = $('td.td_select input');

        if ($(this).is(':checked')) {
            fields_check.attr('checked', 'checked');
        }
        else {
            fields_check.removeAttr('checked');
        }
    });

    // DISABLE/ENABLE FIELDS

    $(document).on('click', 'button.enable_field', function () {
        var button = $(this),
            row = button.closest('tr'),
            enable_hidden = row.find('input[data-name="field_enabled"]'),
            button_label;

        row.toggleClass('disabled-row');

        if (enable_hidden.length) {
            enable_hidden.val(row.hasClass('disabled-row') ? '0' : '1');
        }

        // change button label
        button_label = button.html();
        button.html(button.data('label'));
        button.data('label', button_label);

    });

    // REMOVE CUSTOM FIELDS

    var reindex_row = function () {
        var tr = main_table.find('tbody tr');

        tr.each(function (i) {
            $(this).attr('data-row', i);
        });
    };

    $(document).on('click', 'button.remove_field', function () {
        var button = $(this),
            row = button.closest('tr');

        if ( ! row.hasClass( 'is_custom' ) ) {
            return;
        }

        row.fadeOut(400, function () {
            row.addClass('disabled-row').hide();
            row.find('input[data-name="field_deleted"]').val('yes');
        });

    });

    /*=================
     * EDIT ORDER
     ===================*/

    var admin_multiselect = $('.ywccp_multiselect_admin'),
        admin_datepicker = $('.ywccp_datepicker_admin');

    if (admin_multiselect) {
        $.each(admin_multiselect, function () {
            var s = $(this),
                old_value = s.data('value').split(', ');

            s.after('<input type="hidden" name="' + s.attr('name') + '" value>');
            s.on('change', function () {
                var new_value = $(this).val();

                new_value = new_value ? new_value.join(', ') : '';

                $(this).next().val(new_value);

            });

            s.val(old_value).trigger('change');

            if (typeof $.fn.select2 != 'undefined') {
                s.select2();
            }
        });
    }

    if (typeof $.fn.datepicker != 'undefined' && admin_datepicker) {
        $.each(admin_datepicker, function () {
            $(this).datepicker({
                dateFormat: $(this).data('format') || "dd-mm-yy"
            });
        });
    }
});