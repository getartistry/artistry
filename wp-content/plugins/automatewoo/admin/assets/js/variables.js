/**
 * AutomateWoo Variables
 */

jQuery(function($) {

    AutomateWoo.Variables = {

        $meta_box: $('#aw_variables_box'),


        init: function(){

            this.init_clipboard();

            $(document.body).on( 'change keyup', '.aw-workflow-variable-parameter', this.update_preview_field );
            $(document.body).on( 'keypress', 'input.aw-workflow-variable-parameter', this.restrict_parameter_chars );

            this.$meta_box.on( 'click', '.aw-workflow-variable', this.open_modal );
        },


        /**
         *
         */
        init_clipboard: function() {

            var clipboard = new Clipboard('.aw-clipboard-btn');

            clipboard.on('success', function(e) {

                $('.aw-clipboard-btn').html('Copied!');

                setTimeout(function(){
                    AutomateWoo.Modal.close();
                }, 500 );
            });

        },


        open_modal: function(){

            AutomateWoo.Modal.open( 'ajax' );
            AutomateWoo.Modal.loading();

            var ajax_data = {
                action: 'aw_modal_variable_info',
                variable: $(this).text()
            };

            $.post( ajaxurl, ajax_data, function( response ){
                AutomateWoo.Modal.contents( response );
                AutomateWoo.Variables.update_preview_field();
            });
        },


        /**
         * Updates the variable preview text field
         */
        update_preview_field: function() {

            var $preview_field = $('#aw_workflow_variable_preview_field');
            var variable = $preview_field.data('variable');
            var parameters = [];

            $('.aw-workflow-variable-parameter').each(function(){

                var $param_row = $(this).parents('.aw-workflow-variables-parameter-row:first');

                // Check 'show' logic
                if ( $param_row.data('parameter-show') ) {

                    var show_logic = $param_row.data('parameter-show').split('=');

                    var $condition_field = $('.aw-workflow-variable-parameter[name="' + show_logic[0] + '"]');

                    if ( $condition_field.length && $condition_field.val() == show_logic[1] ) {
                        $param_row.show();
                    } else {
                        $param_row.hide();
                        return; // don't add parameter to preview
                    }
                }

                var param = {
                    name: $(this).attr('name'),
                    required: $param_row.data('is-required'),
                    value: $(this).val()
                };

                parameters.push( param );
            });

            var string = AutomateWoo.Variables.generate_variable_string( variable, parameters );

            $preview_field.text( string );

            AutomateWoo.Modal.position();
        },


        /**
         *
         * @param variable
         * @param parameters
         */
        generate_variable_string: function( variable, parameters ) {

            var string = '{{ ' + variable;

            if ( parameters.length ) {
                var param_parts = [];

                $.each( parameters, function( i, param ) {

                    if ( param.value ) {
                        param_parts.push( param.name + ": '" + param.value + "'" );
                    }
                    else if ( param.required ) {
                        param_parts.push( param.name + ": '...'" );
                    }
                });


                if ( param_parts.length > 0 ) {
                    string += ' | ';
                    string += param_parts.join( ', ' );
                }
            }

            return string + ' }}';
        },


        /**
         *
         * @param e
         */
        restrict_parameter_chars: function(e) {

            var restricted = [ 39, 123, 124, 125 ];

            if ( $.inArray( e.which, restricted ) !== -1 )
                return false;
        }

    };


    AutomateWoo.Variables.init();

});