/**
 * AutomateWoo Tools
 */

jQuery(function($) {

    AutomateWoo.Tools = {

        init: function(){

           // $('#automatewoo_process_tool_form').on( 'submit', this.confirm_submit );

        },

        confirm_submit: function(e) {
            return confirm('Are you sure? This can not be undone.');

        }

    };


    AutomateWoo.Tools.init();

});