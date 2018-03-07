/**
 * RightPress Live Product Update
 */

(function () {

    /**
     * Delay helper
     */
    var delay = (function(){

        var timers = {};

        return function(callback, ms, unique){
            clearTimeout(timers[unique]);
            timers[unique] = setTimeout(callback, ms);
        };
    })();

    /**
     * Register plugin
     */
    jQuery.fn.rightpress_live_product_update = function(params) {

        var self = this;
        var form = this.closest('.product').find('form.cart');

        // Unique id for each instance
        var unique = Math.random().toString(36).slice(2);

        // On input change
        form.find(':input').on('change keyup', function() {
            queue();
        });

        // On variation select and our custom event
        form.on('found_variation, rightpress_live_product_price_trigger', function() {
            queue();
        });

        // Trigger now
        queue();

        /**
         * Make Ajax call
         */
        function call()
        {
            // Serialize form data
            var form_data = form.serialize();

            // Add product id
            form.find('button[type="submit"][name="add-to-cart"]').each(function() {

                var product_id = jQuery(this).val();

                if (product_id) {
                    form_data += (form_data !== '' ? '&' : '') + 'rightpress_reference_product_id=' + product_id;
                }
            });

            // Compile a list of field names so that even empty fields (checkboxes, file uploads etc) are submitted
            form.find('input, textarea, select').each(function() {
                if (jQuery(this).is(':visible') && typeof jQuery(this).prop('name') !== 'undefined') {
                    form_data += (form_data !== '' ? '&' : '') + 'rightpress_complete_input_list[]=' + jQuery(this).prop('name');
                }
            });

            // Send request
            jQuery.ajax({
                type: 'POST',
                url: params.ajax_url,
                context: self,
                data: {
                    action: params.action,
                    data:   form_data
                },
                dataFilter: jQuery.rightpress.parse_json_response,
                beforeSend: params.before_send,
                success: params.response_handler
            });
        }

        /**
         * Queue call
         * Waits for 500 ms before actually executing, cancels any pending processes
         */
        function queue()
        {
            delay(function() {
                call();
            }, 500, unique);
        }


    };

}());
