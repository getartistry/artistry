/**
 * RightPress Javascript Helper Functions
 */

(function () {

    /**
     * Register functions
     */
    jQuery.extend({

        rightpress: {

            /**
             * Safely parse JSON Ajax response
             */
            parse_json_response: function (response, return_raw_data) {

                // Check if we need to return parsed object or potentially fixed raw data
                var return_raw_data = (typeof return_raw_data !== 'undefined') ?  return_raw_data : false;

                try {

                    // Attempt to parse data
                    var parsed = jQuery.parseJSON(response);

                    // Return appropriate value
                    return return_raw_data ? response : parsed;
                }
                catch (e) {

                    // Attempt to fix malformed JSON string
                    var regex = return_raw_data ? /{"result.*"}]}/ : /{"result.*"}/;
                    var valid_response = response.match(regex);

                    // Check if we were able to fix it
                    if (valid_response !== null) {
                        response = valid_response[0];
                    }
                }

                // Second attempt to parse response data
                return return_raw_data ? response : jQuery.parseJSON(response);
            },

            /**
             * Add nested object value
             */
            add_nested_object_value: function (object, path, value) {

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
            },

            /**
             * Nested object key existence check
             */
            object_key_check: function (object /*, key_1, key_2... */) {

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
            },

            /**
             * Clear field value
             */
            clear_field_value: function (field) {

                if (field.is('select')) {
                    field.prop('selectedIndex', 0);
                    if (field.hasClass('rightpress_select2')) {
                        field.val('').change();
                    }
                }
                else if (field.is(':radio, :checkbox')) {
                    field.removeAttr('checked');
                }
                else {
                    field.val('');
                }
            },

            /**
             * Check if field is multiselect
             */
            field_is_multiselect: function (field) {
                return (field.is('select') && typeof field.attr('multiple') !== 'undefined' && field.attr('multiple') !== false);
            }
    }

    });

}());
