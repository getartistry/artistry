( function( $ ) {
    
    /**
     * JavaScript class for mobile editor 
     *
     * @since 1.0.0
     */

    var ConvertProMobileEditor = {

    	/**
         * Initializes the all class variables and methods.
         *
         * @return void
         * @since 1.0.0
         */
        init: function( e ) {

            $(document)

                .ready( function() {
                    $('html').addClass('cp-desktop-device');
                })

                .on( 'click', '.cp-regenerate-mobile', function(e){
                    $('.cp-mb-regnerate-mobile-dialog').addClass('cp-md-show');
                })

        	    .on( 'click', '.cp-responsive-device', this._deviceSwitch )
                .on( 'click', '.cp-dialog-regnerate-mobile', this._generateMobile )
                .on( 'click', '.cp-mobile-responsive', this._switchToMobile )
                .on( 'click', '.cp-switch-mobile', this._toMobileView )

                .on( 'click', '.cp-dialog-regnerate-mobile-cancel', function(e) {
                    e.preventDefault();
                    $(".cp-mb-regnerate-mobile-dialog").removeClass("cp-md-show");
                })

                .on( "click", ".cp-shrink-mob-opt", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    jQuery(".cp-mb-view-dialog").removeClass("cp-md-show");
                }); 

            $('.cp-devices-button-wrapper a').on('click', function (e) {
                e.preventDefault();
                $('.cp-devices-button-wrapper').find("a").addClass('cp-device-active');
                $(this).removeClass('cp-device-active');
            });

        },

        /**
         * Switch device
         *
         * @return void
         * @since 1.0.0
         */
        _deviceSwitch: function() {

            $this = $(this);

            if ( !$this.hasClass('cp-ur-disabled') ) {
                return false;
            }
            
            $('.cp-switch-screen-loader').addClass('cp-show');

            setTimeout( function() {
                ConvertProMobileEditor._responsiveDeviceData( $this );
            }, 200 );

            setTimeout( function() {
                $('.cp-switch-screen-loader').removeClass('cp-show');;
            }, 1500 );
        },

        /**
         * Generate mobile view
         *
         * @return void
         * @since 1.0.0
         */
        _generateMobile: function () {

            var $this = $(this);
            var default_text = $this.text();

            $this.text('Generating...!');

            /* Reset All mobile array */
            ConvertProMobileEditor._resetMobileData( true );
            
            /* Generate all mobile data */
            bmodel.applyDeviceData( 'mobile' );

            setTimeout(function() {
                $this.text(default_text);
                $(".cp-mb-regnerate-mobile-dialog").removeClass("cp-md-show");
            }, 3000);

        },

        /**
         * Switch to mobile device
         *
         * @return void
         * @since 1.0.0
         */
        _switchToMobile: function() {

            var $this = $(this);
    
            if( !$this.hasClass('cp-active-link-color') ) {
                if ( $( '#cp_mobile_generated' ).val() == 'no' ) {
                    jQuery(".cp-mb-view-dialog").addClass("cp-md-show");
                } else {
                    ConvertProMobileEditor._switchToMobileView();
                    $this.addClass('cp-active-link-color');
                }
            } else {
                $this.removeClass('cp-active-link-color');
                ConvertProMobileEditor._switchToMobileView();
            }
        },

        /**
         * Generate and switch to mobile device after confirmation from user 
         *
         * @return void
         * @since 1.0.0
         */
        _toMobileView: function() {

            var $this = $(this);
            var old_text = $this.text();
            $this.text('Generating...');

            $(".cp-mobile-responsive").toggleClass('cp-active-link-color');

            if ( $( '#cp_mobile_generated' ).val() == 'no' ) {
                $( '#cp_mobile_generated' ).val( 'yes' );
                $("html").addClass( 'reset_mobile_data' );
            }

            ConvertProMobileEditor._switchToMobileView();
            
            setTimeout(function() {
                jQuery(".cp-mb-view-dialog").removeClass("cp-md-show");
                $this.text( old_text );
            }, 2000);
        },

        /**
         * Switch to already generated mobile device
         *
         * @return void
         * @since 1.0.0
         */
        _switchToMobileView: function () {

            if ( $( '#cp_mobile_responsive' ).val() == 'no' ) {
                $( '#cp_mobile_responsive' ).val( 'yes' );
                $('.cp-responsive-device').removeClass('cp-hidden');
                setTimeout(function() {
                    ConvertProMobileEditor._responsiveDeviceData( $('.cp-responsive-device[data-device="mobile"]') );
                }, 10);
            }else{
                $( '#cp_mobile_responsive' ).val( 'no' );
                $('.cp-responsive-device').addClass('cp-hidden');
                setTimeout(function() {
                    ConvertProMobileEditor._responsiveDeviceData( $('.cp-responsive-device[data-device="desktop"]') );
                }, 10);
            }
        },

        /**
         * Apply responsive device data to fields
         *
         * @return void
         * @since 1.0.0
         */
        _responsiveDeviceData: function( $this ) {

            if ( $this.hasClass( 'cp-ur-disabled' ) ) {
                
                var device_name = $this.data('device');

                $this.siblings('.cp-responsive-device').addClass( 'cp-ur-disabled' );
                $this.removeClass( 'cp-ur-disabled' );

                if ( device_name == 'mobile' ) {
                    
                    if ( $('html').hasClass('reset_mobile_data') ) {
                        /* Reset All mobile array */
                        ConvertProMobileEditor._resetMobileData();
                        $('html').removeClass('reset_mobile_data');
                    }else{
                        ConvertProMobileEditor._resetMobileData( false, true);
                    }
                }

                setTimeout(function() {
                    /* Center Popup Animation */
                    ConvertProMobileEditor._responsiveDeviceCenter( device_name );
                    /* Generate all mobile data */
                    bmodel.applyDeviceData( device_name );
                    
                    setTimeout(function() {
                        ConvertProPanel._setPanelScroll();
                        
                        if ( $( '.cp-section[data-section="Design"]' ).hasClass( 'active' ) ){
                            if ( $this.data('device') == 'mobile' ){
                                $('.cp-vertical-nav a[data-panel="form"]').trigger('click');
                            }else{
                                $('.cp-vertical-nav a[data-panel="elements"]').trigger('click');
                            }
                        }

                        // vertical center design
                        $("#panel-" + ( step_id + 1 ) ).center( '.panel-wrapper', 400 );
                    }, 200);
                }, 200);
            }
        },

        /**
         * Reset mobile device data
         *
         * @return void
         * @since 1.0.0
         */
        _resetMobileData: function( current_panel, new_elements ) {
    
            /* Reset Current panel only */
            current_panel = ( typeof current_panel != 'undefined' ) ? current_panel : false;
            
            /* Reset new elements automatically */
            new_elements = ( typeof new_elements != 'undefined' ) ? new_elements : false;

            if ( $('html').hasClass('cp-desktop-device') ) {
                
                if ( new_elements ) {
                    bmodel.setDevice( 'mobile', false );
                }else{
                    bmodel.setDevice( 'mobile' );
                }
            }
            
            var r_panel_data = $.extend( true, {}, bmodel.get('panel_data') );

            var r_includeArr = [
                    /* Form Fields */
                    'form_field_font_size',

                    /* Panel */
                    'panel_height',
                    'panel_width',
                    'font_size',
                    'btn_title_size',
                    'close_title_size',

                    /* Common */
                    'height',
                    'width',
                    'position',
                    /* Toggle */
                    'toggle_font_size',
                    'toggle_width',
                    'toggle_height',
                    /* Infobar Toggle */
                    'toggle_infobar_font_size',
                    'toggle_infobar_width',
                    'toggle_infobar_height',
                ];

            //mobile / desktop
            $.each( r_panel_data, function(index, val) {
                var current_step    = step_id;
                var temp_current_id = ( parseInt( index ) + 1 );
                var temp_panel_id   = 'panel-'+ temp_current_id;

                if ( current_panel && index != current_step ) {
                    return;
                }

                if( !$.isEmptyObject(r_panel_data) && 'common' !== index ) {

                    var temp_panel_data       = r_panel_data[index][temp_panel_id];
                    var wt_default_value      = $.parseJSON( $('#cp_panel_width').attr('data-default-val') );
                    var temp_panel_width      = parseInt( wt_default_value[1] );
                    var ht_default_value      = $.parseJSON( $('#cp_panel_height').attr('data-default-val') );
                    var temp_panel_height     = parseInt( ht_default_value[1] );
                        
                    var desktop_panel_width   = parseInt( bmodel.getDeviceValue( temp_panel_data.panel_width, 'panel_width', 'desktop') );
                    var desktop_panel_height  = parseInt( bmodel.getDeviceValue( temp_panel_data.panel_height, 'panel_height', 'desktop') );
                    var convert_factor        = temp_panel_width / desktop_panel_width;

                    $.each(r_panel_data[index], function(id, obj){

                        $.each(obj, function(prop, prop_value){

                            if ( jQuery.inArray(prop, r_includeArr) == -1 ) {
                                return;
                            }

                            if ( new_elements ) {

                                if( prop_value.constructor === Array && prop_value[1] != undefined ) {
                                    return;
                                }
                            }

                            var d_prop_value = bmodel.getDeviceValue( prop_value, prop, 'desktop');
                            
                            if ( 'position' == prop ) {

                                
                                var x = Math.round( Number( d_prop_value['x'] * convert_factor ) );
                                var y = Math.round( Number( d_prop_value['y'] * convert_factor ) );

                                var new_positions = {
                                    'x': x,
                                    'y': y,
                                }
                                r_panel_data[index][id][prop] = bmodel.setDeviceValue( r_panel_data[index][id][prop], new_positions, prop );
                            }else if(
                                prop == 'font_size'
                                || prop == 'btn_title_size'
                                || prop == 'close_title_size'
                                || prop == 'toggle_font_size'
                                || prop == 'toggle_infobar_font_size'
                            ){
                                
                                var new_value = Math.round(Number( parseInt( d_prop_value ) * convert_factor )) + 'px';
                                r_panel_data[index][id][prop] = bmodel.setDeviceValue( r_panel_data[index][id][prop], new_value, prop );
                            }else{
                                
                                var new_value = Math.round(Number( parseInt( d_prop_value ) * convert_factor ));
                                r_panel_data[index][id][prop] = bmodel.setDeviceValue( r_panel_data[index][id][prop], new_value, prop );
                            }
                        });
                    });
                }
            });

            bmodel.set( 'panel_data', r_panel_data );
        },

        /**
         * Vertical center panel after switching device
         *
         * @return void
         * @since 1.0.0
         */
        _responsiveDeviceCenter: function( device_name ) {
            var current_step    = step_id + 1;
            var panel_width     = bmodel.getModalValue( 'panel-' + current_step, step_id, 'panel_width', true );
            var panel_height    = bmodel.getModalValue( 'panel-' + current_step, step_id, 'panel_height', true );        
            var parent          = $('.panel-wrapper');
            var popup           = $('#panel-' + current_step);
            var animate_width   = parseInt( panel_width[0] );
            var animate_height  = parseInt( panel_height[0] );

            if ( 'mobile' == device_name ) {
                animate_width   = parseInt( panel_width[1] );
                animate_height  = parseInt( panel_height[1] );
            }
            
            var top_pos     = Math.max( 0, ((parent.height() - animate_height) / 2) + parent.scrollTop()) + 'px';
            var left_pos    = Math.max( 0, ((parent.width() - animate_width) / 2) + parent.scrollLeft()) + 'px';

            popup.css({
               'max-width' : 'none',
            });

            var element_id = 'panel-' + current_step;
            jQuery( "#style-" + element_id ).remove();
            var modal_style = "<style type='text/css' id='style-" + element_id + "' class='cp_modal_style' > #"+ element_id +" { width:"+ animate_width + "px; height:"+ animate_height + "px; left: " + left_pos + "; top: " + top_pos + "; transform: none; } </style>"; 
            jQuery("head").append( modal_style );
        }
    }

    ConvertProMobileEditor.init();

})( jQuery );