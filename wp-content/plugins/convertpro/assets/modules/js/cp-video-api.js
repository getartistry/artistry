var CProVideo = ''; 

(function( $ ) {

    CProVideo = {
        
        /**
         * Initializes the all class variables.
         *
         * @return void
         * @since 0.0.1
         */
        init: function( e, element, event ) {

            // events 
            $( window ).on( "cp_after_popup_open", this._playVideo );
            $( document ).on( "closePopup", this._stopVideo );
        },

        /**
         * Play Video
         *
         * @since 1.0.0
         */
        _playVideo: function( e, data, module_type, style_id ) {

            var video_container = data.find( '.cpro-video-container' );

            video_container.each( function() {

                var $this_field = jQuery(this).closest(".cp-field-html-data");
                var is_autoplay = $this_field.data('autoplay');
                var video_src   = $this_field.data('src');
                var video_frame = $this_field.find("iframe");
                var video_url   = video_frame.attr('src');
                var iframe_id   = video_frame.attr('id');

                switch( video_src ) {

                    case "youtube":
                        if( is_autoplay ) {
                            video_url = video_url.replace( '&autoplay=0', '' );
                            video_url = video_url + "&autoplay=1";
                            video_frame.attr( 'src', video_url );
                        }
                    break;

                    case "vimeo":

                        if( is_autoplay ) {
                            video_url = video_url + "?autoplay=1";
                            video_frame.attr( 'src', video_url );
                        }
                    break;

                    case 'custom_url':
                        if( is_autoplay ) {
                            setTimeout(function() {
                                $this_field.find('.cpro-video-iframe')[0].play();
                            }, 500 );
                        }
                    break;
                }
                
            });
        },  

        /*
         * Stop video on close of popup 
         * @since 1.0.0
         */
        _stopVideo: function( event, modal, id ) {

            var videos = $( ".cp_style_" + id ).find( '.cp-video-wrap' );

            videos.each( function() {

                var $this       = $(this);
                var video_src   = $this.data("src");
                var video_frame = $this.find('iframe');
                var iframe_src  = video_frame.attr("src");

                switch ( video_src ) {

                    case 'youtube':
                    case 'vimeo': 
                        video_frame.prop( 'src', '' ).prop( 'src', iframe_src.replace( '?autoplay=1', '' ) );
                    break;

                    case 'custom_url':
                        $this.find("video")[0].pause();
                    break;
                }
            });
        },
    };

    CProVideo.init();
  

})( jQuery );
