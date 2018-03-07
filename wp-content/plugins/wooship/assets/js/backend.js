/**
 * WooShip Plugin Backend Scripts
 */
jQuery(document).ready(function() {

    /**
     * Define rule editor elements
     */
    var wooship_elements = {
        shipping_method: {
            children: {
                condition: {}
            }
        },
        additional_charge: {
            children: {
                condition: {}
            }
        },
        shipping_zone: {
            children: {
                condition: {}
            }
        }
    };

    /**
     * Iterate over elements and set up view
     */
    if (typeof wooship_config === 'object') {
        jQuery.each(wooship_elements, function(key, children) {
            var config = object_key_check(wooship_config, key + 's') ? wooship_config[key + 's'] : [];
            set_up_parent(key, config);
        });
    }
    else {
        alert('Error: Unable to load WooShip configuration. Please reload this page.');
    }

    /**
     * Set up parent element
     */
    function set_up_parent(key, config)
    {

        jQuery('#wooship_' + key + 's').each(function() {

            // No rows configured yet?
            if (config.length === 0) {
                add_no_rows_notice(jQuery(this), key);
            }
            // At least one row exists
            else {

                // Iterate over list of form fields and add them to form builder
                for (var i in config) {
                    add_row(key, config[i]);
                }

                // Fix field identifiers
                fix_rows(key);

                // Fix field values
                fix_parent_values(key, false);

                // Iterate over rows
                jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper .wooship_row').each(function() {

                    // Initial condition fix
                    jQuery(this).find('.wooship_condition').each(function() {
                        fix_condition(key, jQuery(this));
                    });

                    // Row header fix
                    jQuery(this).find('.wooship_row_title_title').html(jQuery(this).find('.wooship_' + key + 's_field_title').val());
                    jQuery(this).find('.wooship_row_title_note').html(jQuery(this).find('.wooship_' + key + 's_field_note').val());
                });
            }

            // Render add row button
            append(jQuery(this), key, 'add_row')

            // Bind click action
            jQuery('#wooship_' + key + 's_add_row button').click(function() {
                add_row(key, false);
            });
        });
    }

    /**
     * Add "Nothing to display" notice
     */
    function add_no_rows_notice(selector, key)
    {
        prepend(selector, key, 'no_rows');
    }

    /**
     * Remove "Nothing to display" notice
     */
    function remove_no_rows_notice(key)
    {
        jQuery('#wooship_' + key + 's #wooship_' + key + 's_no_rows').remove();
    }

    /**
     * Add wrapper
     */
    function add_wrapper(key)
    {
        // Make sure we don't have one yet before proceeding
        if (jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper').length === 0) {

            // Add wrapper
            prepend('#wooship_' + key + 's', key, 'wrapper', null);

            // Make it sortable accordion
            jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper').accordion({
                header: '> div > div.wooship_accordion_handle',
                icons: false,
                collapsible: true,
                heightStyle: 'content'
            }).sortable({
                handle: '.wooship_row_sort_handle',
                axis:   'y',
                stop: function(event, ui) {

                    // Fix row identifiers
                    fix_rows(key);
                }
            });
        }
    }

    /**
     * Remove wrapper
     */
    function remove_wrapper(key)
    {
        jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper').remove();
    }

    /**
     * Add one row
     */
    function add_row(key, config)
    {
        var selector = '#wooship_' + key + 's #wooship_' + key + 's_wrapper';

        // Add wrapper
        add_wrapper(key);

        // Make sure we don't have the "Nothing to display" notice
        remove_no_rows_notice(key);

        // Add row element
        append(selector, key, 'row', null);

        // Select current row
        var row = jQuery(selector).children().last();
        var row_key = jQuery(selector).children().length - 1;

        // Fix identifiers, values and visibility
        if (config === false) {
            fix_rows(key);
            fix_parent_values(key, true, row, row_key);
        }

        // Set up child elements, e.g. conditions
        jQuery.each(wooship_elements[key].children, function(type) {
            set_up(key, type + 's', row, row_key, config);
        });

        // Refresh accordion
        jQuery(selector).accordion('refresh');
        jQuery(selector).accordion('option', 'active', -1);

        // Handle duplicate action
        jQuery('#wooship_' + key + 's .wooship_row_duplicate_handle').last().click(function(event) {
            event.stopPropagation();
            duplicate_row(key, jQuery(this).closest('.wooship_row'));
        });

        // Handle delete action
        jQuery('#wooship_' + key + 's .wooship_row_remove_handle').last().click(function() {
            remove_row(key, jQuery(this).closest('.wooship_row'));
        });

        // Reflect changes of title and note in header
        jQuery('#wooship_' + key + 's .wooship_' + key + 's_field_title').last().on('keyup change', function() {
            jQuery(this).closest('.wooship_row').find('.wooship_row_title_title').html(jQuery(this).val());
        });
        jQuery('#wooship_' + key + 's .wooship_' + key + 's_field_note').last().on('keyup change', function() {
            jQuery(this).closest('.wooship_row').find('.wooship_row_title_note').html(jQuery(this).val());
        });
    }

    /**
     * Duplicate one row
     */
    function duplicate_row(key, row)
    {
        // Select wrapper
        var wrapper = row.closest('#wooship_' + key + 's_wrapper');

        // Get original row and row key
        var original_row = row;
        var original_row_key = row.index();

        // Get new row key
        var row_key = wrapper.children().length;

        // Start config mockup
        var config = {};
        var multiselect_options = {};

        // Iterate over all form elements and add values to config mockup
        original_row.find('input, select').each(function() {

            // Skip hidden fields
            if (jQuery(this).is(':disabled')) {
                return;
            }

            // Get name parts
            var name_parts = jQuery(this).prop('name').replace('wooship[' + key + 's][' + original_row_key + ']', '').replace('[]', '').slice(1, -1).split('][');

            // Add value to config mockup object
            add_nested_object_value(config, name_parts, jQuery(this).val());

            // Get multiselect field options
            if (is_multiselect(jQuery(this))) {
                var current_options = [];

                jQuery(this).find('option').each(function() {
                    current_options.push({
                        id: jQuery(this).prop('value'),
                        text: jQuery(this).text()
                    });
                });

                if (current_options.length > 0) {
                    add_nested_object_value(multiselect_options, name_parts, current_options);
                }
            }
        });

        // Add new row
        add_row(key, config);

        // Fix field identifiers
        fix_rows(key);

        // Select new row
        var row = wrapper.children().last();

        // Convert config mockup to full config mockup        
        var top_level_config = {};
        add_nested_object_value(top_level_config, [key + 's', row_key], config);
        var top_level_multiselect_options = {};
        add_nested_object_value(top_level_multiselect_options, [key + 's', row_key], multiselect_options);

        // Fix field values
        fix_parent_values(key, false, row, row_key, top_level_config, top_level_multiselect_options);

        // Initial condition fix
        row.find('.wooship_condition').each(function() {
            fix_condition(key, jQuery(this));
        });

        // Row header fix
        row.find('.wooship_row_title_title').html(row.find('.wooship_' + key + 's_field_title').val());
        row.find('.wooship_row_title_note').html(row.find('.wooship_' + key + 's_field_note').val());
    }

    /**
     * Remove one row
     */
    function remove_row(key, row)
    {
        // Last row? Remove the entire wrapper and add "Nothing to display"
        if (row.closest('#wooship_' + key + 's_wrapper').children().length < 2) {
            remove_wrapper(key);
            add_no_rows_notice('#wooship_' + key + 's', key)
        }

        // Remove single row and fix ids
        else {
            row.remove();
            fix_rows(key);
        }
    }

    /**
     * Fix attributes
     */
    function fix_rows(key)
    {
        var i = 0;  // Row identifier
        var j = 0;  // Child element identifier, e.g. conditions within a give row

        // Iterate over rows
        jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper .wooship_row').each(function() {

            var row = jQuery(this);
            var element_wrappers = [];

            // Fix conditions etc
            jQuery.each(wooship_elements[key].children, function(type) {

                var type_plural = type + 's';

                // Check if we have elements of this type for this row and handle them
                row.find('.wooship_row_content_' + type_plural + '_row').each(function() {

                    element_wrappers.push(jQuery(this));

                    // Iterate over elements of this type of current row
                    jQuery(this).find('.wooship_' + type + '_wrapper .wooship_' + type).each(function() {

                        // Iterate over all field elements of current element
                        jQuery(this).find('input, select').each(function() {

                            // Attribute id
                            if (typeof jQuery(this).prop('id') !== 'undefined') {
                                var new_value = jQuery(this).prop('id').replace(/_(\{i\}|\d+)?_/, '_' + i + '_').replace(/(\{j\}|\d+)?$/, j);
                                jQuery(this).prop('id', new_value);
                            }

                            // Attribute name
                            if (typeof jQuery(this).prop('name') !== 'undefined') {
                                var new_value = jQuery(this).prop('name').replace(new RegExp('wooship\\[' + key + 's\\]\\[(\\{i\\}|\\d+)\\]?'), 'wooship[' + key + 's][' + i + ']').replace(new RegExp('\\[' + type_plural + '\\]\\[(\\{j\\}|\\d+)\\]?'), '[' + type_plural + '][' + j + ']');
                                jQuery(this).prop('name', new_value);
                            }
                        });

                        // Increment element identifier
                        j++;
                    });

                    // Reset element identifier
                    j = 0;
                });
            });

            // Iterate over all field elements of this element
            jQuery(this).find('input, select').each(function() {

                var current_form_element = jQuery(this);

                // Do not touch child elements (already sorted above)
                if (element_wrappers.length > 0) {

                    var proceed = true;

                    jQuery.each(element_wrappers, function(index, value) {
                        if (jQuery.contains(value[0], current_form_element[0])) {
                            proceed = false;
                            return true;
                        }
                    });

                    if (!proceed) {
                        return true;
                    }
                }

                // Attribute id
                if (typeof jQuery(this).prop('id') !== 'undefined') {
                    var new_value = jQuery(this).prop('id').replace(/(\{i\}|\d+)?$/, i);
                    jQuery(this).prop('id', new_value);
                }

                // Attribute name
                if (typeof jQuery(this).prop('name') !== 'undefined') {
                    var new_value = jQuery(this).prop('name').replace(new RegExp('wooship\\[' + key + 's\\]\\[(\\{i\\}|\\d+)\\]?'), 'wooship[' + key + 's][' + i + ']');
                    jQuery(this).prop('name', new_value);
                }
            });

            // Iterate over all label elements of this element
            jQuery(this).find('label').each(function() {

                var current_form_element = jQuery(this);

                // Do not touch child elements (already sorted above)
                if (element_wrappers.length > 0) {

                    var proceed = true;

                    jQuery.each(element_wrappers, function(index, value) {
                        if (jQuery.contains(value[0], current_form_element[0])) {
                            proceed = false;
                        }
                    });

                    if (!proceed) {
                        return true;
                    }
                }

                // Attribute for
                if (typeof jQuery(this).prop('for') !== 'undefined' && jQuery(this).prop('for').length) {
                    var new_value = jQuery(this).prop('for').replace(/(\{i\}|\d+)?$/, i);
                    jQuery(this).prop('for', new_value);
                }
            });

            // Increment row identifier
            i++;
        });
    }

    /**
     * Fix parent field values
     */
    function fix_parent_values(key, is_new, row, row_key, config_override, multiselect_options_override)
    {
        // Maybe override configuration values
        var config = typeof config_override !== 'undefined' ? config_override : wooship_config;

        // Row identifier
        var i = typeof row_key !== 'undefined' ? row_key : 0;

        // Get rows to fix values for
        var rows = typeof row !== 'undefined' ? [row] : jQuery('#wooship_' + key + 's #wooship_' + key + 's_wrapper .wooship_row');

        // Iterate over rows
        jQuery.each(rows, function() {

            var row = jQuery(this);

            // Iterate over all field elements of this row
            jQuery(this).find('input, select').each(function() {

                // Do not touch child elements, e.g. conditions
                if (jQuery(this).closest('.wooship_row_content_child_row').length) {
                    return true;
                }

                // Get field key
                var field_key = jQuery(this).prop('id').replace(new RegExp('^wooship_' + key + 's_'), '').replace(/(_\d+)?$/, '');

                // Select options in select fields
                if (jQuery(this).is('select')) {
                    if (!is_new && config !== false && object_key_check(config, key + 's', i, field_key) && config[key + 's'][i][field_key]) {
                        jQuery(this).val(config[key + 's'][i][field_key]);
                    }
                }

                // Add value for text input fields
                else if (typeof jQuery(this).prop('value') !== 'undefined' && jQuery(this).prop('value') === '{value}') {
                    if (!is_new && config !== false && object_key_check(config, key + 's', i, field_key)) {
                        jQuery(this).prop('value', config[key + 's'][i][field_key]);
                    }
                    else {
                        jQuery(this).removeAttr('value');
                    }
                }

                // Initialize select2
                if (jQuery(this).hasClass('wooship_select2') && !jQuery(this).data('select2')) {
                    initialize_select2(key, jQuery(this));
                }
            });

            // Fix child values
            if (!is_new) {
                jQuery.each(wooship_elements[key].children, function(type) {
                    fix_child_values(key, false, type, row, i, null, null, config_override, multiselect_options_override);
                });
            }

            // Increment row identifier
            i++;
        });
    }

    /**
     * Fix child field values
     */
    function fix_child_values(key, is_new, type, row, row_key, child_row, child_row_key, config_override, multiselect_options_override)
    {
        // Maybe override configuration values
        var config = typeof config_override !== 'undefined' ? config_override : wooship_config;
        var multiselect_options = typeof multiselect_options_override !== 'undefined' ? multiselect_options_override : wooship_multiselect_options;

        var type_plural = type + 's';

        // Row identifiers
        var i = row_key;
        var j = typeof child_row_key !== 'undefined' && child_row_key !== null ? child_row_key : 0;

        // Get rows to fix values for
        var rows = typeof child_row !== 'undefined' && child_row !== null ? [child_row] : row.find('.wooship_' + type + '_wrapper .wooship_' + type);

        // Iterate over child rows
        jQuery.each(rows, function() {

            // Iterate over all field elements of current element
            jQuery(this).find('input, select').each(function() {

                // Get field key
                var field_key = jQuery(this).prop('id').replace(new RegExp('^wooship_' + key + 's_' + type_plural + '_'), '').replace(/^(\d+_)?/, '').replace(/(_\d+)?$/, '');

                // Select options in select fields
                if (jQuery(this).is('select')) {
                    if (!is_new && config !== false && object_key_check(config, key + 's', i, type_plural, j, field_key) && config[key + 's'][i][type_plural][j][field_key]) {
                        if (is_multiselect(jQuery(this))) {
                            if (object_key_check(multiselect_options, key + 's', i, type_plural, j) && typeof multiselect_options[key + 's'][i][type_plural][j][field_key] === 'object') {
                                for (var k = 0; k < config[key + 's'][i][type_plural][j][field_key].length; k++) {
                                    var all_options = multiselect_options[key + 's'][i][type_plural][j][field_key];
                                    var current_option_key = config[key + 's'][i][type_plural][j][field_key][k];

                                    for (var l = 0; l < all_options.length; l++) {
                                        if (object_key_check(all_options, l, 'id') && all_options[l]['id'] == current_option_key) {
                                            var current_option_label = all_options[l]['text'];
                                            jQuery(this).append(jQuery('<option></option>').attr('value', current_option_key).prop('selected', true).text(current_option_label));
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            jQuery(this).val(config[key + 's'][i][type_plural][j][field_key]);
                        }
                    }
                }

                // Add value for text input fields
                else if (typeof jQuery(this).prop('value') !== 'undefined' && jQuery(this).prop('value') === '{value}') {
                    if (!is_new && config !== false && object_key_check(config, key + 's', i, type_plural, j, field_key)) {
                        jQuery(this).prop('value', config[key + 's'][i][type_plural][j][field_key]);
                    }
                    else {
                        jQuery(this).removeAttr('value');
                    }
                }

                // Initialize select2
                if (jQuery(this).hasClass('wooship_select2') && !jQuery(this).data('select2')) {
                    initialize_select2(key, jQuery(this));
                }
            });

            // Increment element identifier
            j++;
        });
    }

    /**
     * Initialize select2 on one element
     */
    function initialize_select2(key, element)
    {
        // Currently only multiselect fields are converted
        if (!is_multiselect(element)) {
            return;
        }

        // Make sure our Select2 reference is set
        if (typeof RP_Select2 === 'undefined') {
            return;
        }

        // Initialize Select2
        RP_Select2.call(element, {
            width: '100%',
            minimumInputLength: 1,
            escapeMarkup: function (text) {
                return text;
            },
            ajax: {
                url:        wooship.ajaxurl,
                type:       'POST',
                dataType:   'json',
                delay:      250,
                data: function(params) {
                    return {
                        query:      params.term,
                        action:     'wooship_load_multiselect_items',
                        type:       parse_multiselect_subject(key, element),
                        selected:   element.val()
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data.results
                    };
                }
            }
        });
    }

    /**
     * Parse multiselect field subject
     */
    function parse_multiselect_subject(key, element)
    {
        var subject = '';

        jQuery.each(element.attr('class').split(/\s+/), function(index, item) {
            if (item.indexOf('wooship_' + key + 's_condition_') > -1) {
                subject = item.replace('wooship_' + key + 's_condition_', '');
                return;
            }
        });

        return subject;
    }

    /**
     * Set up child elements, e.g. conditions for one row
     */
    function set_up(key, type, row, row_key, config)
    {
        var type_singular = type.replace(/s$/, '');

        // No existing children of given type
        if (config === false || typeof config !== 'object' || config.length < 1 || typeof config[type] !== 'object' || config[type].length < 1) {
            add_no(key, type, row);
        }

        // Set up existing children of given type
        else {
            for (var j in config[type]) {
                add(key, type_singular, row, row_key, config);
            }
        }

        // Bind click action
        row.find('.wooship_add_' + type_singular + ' button').click(function() {
            add(key, type_singular, row, row_key, false);
        });
    }

    /**
     * Add no child elements (e.g. conditions) notice
     */
    function add_no(key, type, row)
    {
        prepend(row.find('.wooship_row_content_' + type + '_row .wooship_inner_wrapper'), key, 'no_' + type);
    }

    /**
     * Remove no child elements (e.g. conditions) notice
     */
    function remove_no(key, type, row)
    {
        row.find('.wooship_no_' + type).remove();
    }

    /**
     * Add one child element, e.g. condition
     */
    function add(key, type, row, row_key, config)
    {
        // Add wrapper
        add_child_wrapper(key, type, row);

        // Make sure we don't have the no child elements notice
        remove_no(key, type + 's', row);

        // Add element
        append(row.find('.wooship_' + type + '_wrapper'), key, type, null);

        // Select current row
        var child_row = row.find('.wooship_condition').last();
        var child_row_key = row.find('.wooship_condition').length - 1;

        // Fix identifiers, values and visibility on newly added item
        if (config === false) {

            // Fix fields
            fix_rows(key);
            fix_child_values(key, true, type, row, row_key, child_row, child_row_key);

            // Fix elements of current condition
            if (type === 'condition') {
                fix_condition(key, child_row);
            }
        }

        // Handle delete action
        row.find('.wooship_' + type + '_remove_handle').last().click(function() {
            remove(key, type, jQuery(this).closest('.wooship_' + type));
        });
    }

    /**
     * Remove one child element, e.g. condition
     */
    function remove(key, type, element)
    {
        var row = element.closest('.wooship_row');

        // Last element? Remove the entire wrapper and add no child elements notice
        if (row.find('.wooship_' + type + '_wrapper').children().length < 2) {
            remove_child_wrapper(key, type, row);
            add_no(key, type + 's', row);
        }

        // Remove single element and fix ids
        else {
            element.remove();
            fix_rows(key);
        }
    }

    /**
     * Add wrapper for child elements, e.g. conditions
     */
    function add_child_wrapper(key, type, row)
    {
        // Make sure we don't have one yet before proceeding
        if (row.find('.wooship_' + type + '_wrapper').length === 0) {

            // Add wrapper
            prepend(row.find('.wooship_row_content_' + type + 's_row .wooship_inner_wrapper'), key, type + '_wrapper', null);

            // Make it sortable
            row.find('.wooship_' + type + '_wrapper').sortable({
                axis:       'y',
                handle:     '.wooship_' + type + '_sort_handle',
                opacity:    0.7,
                stop: function(event, ui) {

                    // Remove styles added by jQuery UI
                    jQuery(this).find('.wooship_' + type).each(function() {
                        jQuery(this).removeAttr('style');
                    });

                    // Fix ids, names etc
                    fix_rows(key);
                }
            });
        }
    }

    /**
     * Remove child element wrapper
     */
    function remove_child_wrapper(key, type, row)
    {
        row.find('.wooship_' + type + '_wrapper').remove();
    }

    /**
     * Fix condition
     */
    function fix_condition(key, element)
    {
        // Condition type
        element.find('.wooship_' + key + 's_condition_type').change(function() {
            toggle_condition_fields(key, element);
        });
        toggle_condition_fields(key, element);

        // Meta field condition
        element.find('.wooship_' + key + 's_condition_method').change(function() {
            fix_meta_field_condition(key, element);
        });
        fix_meta_field_condition(key, element);
    }

    /**
     * Toggle visibility of condition fields
     */
    function toggle_condition_fields(key, element)
    {
        // Get current condition type
        var current_type = element.find('.wooship_' + key + 's_condition_type').val();

        // Show only fields related to current type
        element.find('.wooship_condition_setting_fields').each(function() {

            // Show or hide fields
            var displayed = jQuery(this).hasClass('wooship_condition_setting_fields_' + current_type);
            jQuery(this).css('display', (displayed ? 'block' : 'none'));

            // Iterate over all form elements
            jQuery(this).find('input, select').each(function() {

                // Enable/disable fields
                jQuery(this).prop('disabled', !displayed);

                // Clear field values
                if (!displayed) {
                    clear_field_value(jQuery(this));
                }
            });
        });
    }

    /**
     * Fix fields of meta field condition
     */
    function fix_meta_field_condition(key, element)
    {
        var condition_type = element.find('.wooship_' + key + 's_condition_type').val();

        // Only proceed if condition type is meta field
        if (condition_type !== 'customer_customer_meta_field') {
            return;
        }

        // Get current method
        var current_method = element.find('.wooship_condition_setting_fields_' + condition_type + ' .wooship_' + key + 's_condition_method').val();

        // Proceed depending on current method
        if (jQuery.inArray(current_method, ['is_empty', 'is_not_empty', 'is_checked', 'is_not_checked']) !== -1) {
            element.find('.wooship_condition_setting_fields_' + condition_type).find('input, select').parent().removeClass('wooship_condition_setting_fields_single').addClass('wooship_condition_setting_fields_double');
            element.find('.wooship_condition_setting_fields_' + condition_type + ' .wooship_' + key + 's_condition_text').parent().css('display', 'none');
            clear_field_value(element.find('.wooship_condition_setting_fields_' + condition_type + ' .wooship_' + key + 's_condition_text'));
        }
        else {
            element.find('.wooship_condition_setting_fields_' + condition_type).find('input, select').parent().removeClass('wooship_condition_setting_fields_double').addClass('wooship_condition_setting_fields_single');
            element.find('.wooship_condition_setting_fields_' + condition_type).find('.wooship_' + key + 's_condition_text').parent().css('display', 'block');
        }
    }











































    /**
     * HELPER
     * Append template with values to selected element's content
     */
    function append(selector, key, template, values)
    {
        var html = get_template(key, template, values);

        if (typeof selector === 'object') {
            selector.append(html);
        }
        else {
            jQuery(selector).append(html);
        }
    }

    /**
     * HELPER
     * Prepend template with values to selected element's content
     */
    function prepend(selector, key, template, values)
    {
        var html = get_template(key, template, values);

        if (typeof selector === 'object') {
            selector.prepend(html);
        }
        else {
            jQuery(selector).prepend(html);
        }
    }

    /**
     * HELPER
     * Get template's html code
     */
    function get_template(key, template, values)
    {
        return populate_template(jQuery('#wooship_' + key + 's_' + template + '_template').html(), values);
    }

    /**
     * HELPER
     * Populate template with values
     */
    function populate_template(template, values)
    {
        for (var key in values) {
            template = replace_macro(template, key, values[key]);
        }

        return template;
    }

    /**
     * HELPER
     * Replace all instances of macro in string
     */
    function replace_macro(string, macro, value)
    {
        var macro = '{' + macro + '}';
        var regex = new RegExp(macro, 'g');
        return string.replace(regex, value);
    }

    /**
     * HELPER
     * Check if HTML element is multiselect field
     */
    function is_multiselect(element)
    {
        return (element.is('select') && typeof element.attr('multiple') !== 'undefined' && element.attr('multiple') !== false);
    }

    /**
     * HELPER
     * Clear field value
     */
    function clear_field_value(field)
    {
        if (field.is('select')) {
            field.prop('selectedIndex', 0);
        }
        else if (field.is(':radio, :checkbox')) {
            field.removeAttr('checked');
        }
        else {
            field.val('');
        }
    }

    /**
     * HELPER
     * Nested object key existence check
     */
    function object_key_check(object /*, key_1, key_2... */)
    {
        var keys = Array.prototype.slice.call(arguments, 1);
        var current = object;

        // Iterate over keys
        for (var i = 0; i < keys.length; i++) {

            // Check if current key exists
            if (typeof current[keys[i]] === 'undefined') {
                return false;
            }

            // Check if all but last keys are for object
            if (i < (keys.length - 1) && typeof current[keys[i]] !== 'object') {
                return false;
            }

            // Go one step down
            current = current[keys[i]];
        }

        // If we reached this point all keys from path
        return true;
    }

    /**
     * HELPER
     * Add nested object value dynamically
     */
    function add_nested_object_value(object, path, value)
    {
        var last_key_index = path.length - 1;

        for (var i = 0; i < last_key_index; ++ i) {
            var key = jQuery.isNumeric(path[i]) ? parseInt(path[i]) : path[i];

            if (jQuery.isNumeric(path[i + 1])) {
                if (typeof object[key] === 'undefined') {
                    object[key] = [];
                }
            }
            else if (!(key in object)) {
                object[key] = {};
            }

            object = object[key];
        }

        object[path[last_key_index]] = value;
    }


});
