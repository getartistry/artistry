(function( $ ) {
    
    /**
     * JavaScript class for working for AB Tests.
     *
     * @since 1.0.0
     */

    var CPROABTest = {
        
        /**
         * Initializes the services logic.
         *
         * @return void
         * @since 1.0.0
         */
        init: function() {
            $( document ).on( 'cpro_after_design_save', this._saveChildConfigurations );
        },

        /**
         * Save Child Configuration.
         *
         * @return void
         * @since 1.0.0
         */
        _saveChildConfigurations: function ( e ) {

            var is_parent = $( '.cp-save' ).data( 'is-abtest' ),
                cp_style_id = $( '#cp_style_id' ).val();

            if( '1' == is_parent && typeof cp_style_id != 'undefined' ) {

                CPROABTest.ajaxCall(
                    {
                        action: 'cpro_update_configuration', 
                        parent_id: cp_style_id,
                    },
                    function( result ) {
                        console.log( result );
                    },
                    function( err ) {
                        console.log( err );
                    }
                );
            }
        },

        /**
         * AJAX call to services.
         *
         * @param {Object} args Arguments to AJAX call.
         * @param func: Callback function name.
         * @return void
         * @since 1.0.0
         */
        ajaxCall: function( args, success_func, error_func ) {

            $.ajax( {
                data: args,
                action: args.action,
                url: ajaxurl,
                success: success_func,
                error: error_func,
                type:'POST',
                dataType:'JSON'
            });
        },

    };

    $( function() {
        CPROABTest.init();
    });

})( jQuery );