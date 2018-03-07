/**
 * AutomateWoo Modal
 */
jQuery(function($) {

    AutomateWoo.Modal = {

        init: function(){

            $(document.body).on( 'click', '.js-close-automatewoo-modal', this.close );
            $(document.body).on( 'click', '.automatewoo-modal-overlay', this.close );
            $(document.body).on( 'click', '.js-open-automatewoo-modal', this.handle_link );

            $(window).resize(function(){
                AutomateWoo.Modal.position();
            });

            $(document).keydown(function(e) {
                if ( e.keyCode == 27 ) {
                    AutomateWoo.Modal.close();
                }
            });

        },


        handle_link: function(e){
            e.preventDefault();

            var $a = $(this);
            var type = $a.data( 'automatewoo-modal-type' );
            var size = $a.data( 'automatewoo-modal-size' );

            if ( type === 'ajax' ) {
                AutomateWoo.Modal.open( type, size );
                AutomateWoo.Modal.loading();

                $.post( $a.attr('href'), {}, function( response ){
                    AutomateWoo.Modal.contents( response );
                });
            }
            else if ( type === 'inline' ) {
                var contents = $( $a.data( 'automatewoo-modal-contents' ) ).html();
                AutomateWoo.Modal.open( type, size );
                AutomateWoo.Modal.contents( contents );
            }
        },


        open: function( type, size ) {

            var classes = [ 'automatewoo-modal--type-' + type ];

            if ( size ) {
                classes.push( 'automatewoo-modal--size-' + size );
            }

            $(document.body).addClass('automatewoo-modal-open').append('<div class="automatewoo-modal-overlay"></div>');
            $(document.body).append('<div class="automatewoo-modal ' + classes + '"><div class="automatewoo-modal__contents"><div class="automatewoo-modal__header"></div></div><div class="automatewoo-icon-close js-close-automatewoo-modal"></div></div>');
            this.position();
        },


        loading: function() {
            $(document.body).addClass('automatewoo-modal-loading');
        },


        contents: function ( contents ) {
            $(document.body).removeClass('automatewoo-modal-loading');
            $('.automatewoo-modal__contents').html(contents);

            AW.initTooltips();

            this.position();
        },


        close: function() {
            $(document.body).removeClass('automatewoo-modal-open automatewoo-modal-loading');
            $('.automatewoo-modal, .automatewoo-modal-overlay').remove();
        },


        position: function() {

            var $modal = $('.automatewoo-modal');
            var $modal_body = $('.automatewoo-modal__body');
            var $modal_header = $('.automatewoo-modal__header');

            $modal_body.removeProp('style');

            var modal_header_height = $modal_header.outerHeight();
            var modal_height = $modal.height();
            var modal_width = $modal.width();
            var modal_body_height = $modal_body.outerHeight();
            var modal_contents_height = modal_body_height + modal_header_height;

            $modal.css({
                'margin-left': -modal_width / 2,
                'margin-top': -modal_height / 2
            });

            if ( modal_height < modal_contents_height - 5 ) {
                $modal_body.height( modal_height - modal_header_height );
            }
        }

    };


    AutomateWoo.Modal.init();

});