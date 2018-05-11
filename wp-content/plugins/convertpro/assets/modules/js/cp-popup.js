var cpro_cookies = '';
var cProAdBlockEnabled = false;
var image_on_ready = 'undefined' == typeof cp_ajax.image_on_ready ? 0 : cp_ajax.image_on_ready;

(function( $ ) {

    cpro_cookies = {

        set : function(name, value, days) {
            var expires;

            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            } else {
                expires = "";
            }
            document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
        },

        get: function(name) {
            var nameEQ = encodeURIComponent(name) + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0)
                    return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        },

        remove: function(name) {
            this.set( name, "", -1 );
        }

    }
    
})( jQuery );


global_cp_cookies = 'undefined' !== typeof Cookies ? Cookies.noConflict() : null;
var ConvertProPopup = '';

/* Global Varaibles */
var global_cp_cookies   = null !== global_cp_cookies ? global_cp_cookies : cpro_cookies;
var initConvertPro      = {};
var cppPopupsData       = {};
var cppGmt              = ( new Date().getTime() ) * 1000;
var cppPageStartTime    = Date.now();
var cppInactivityTime   = cp_pro.inactive_time || 60;

/* AB Test */
var ab_test             = typeof cp_v2_ab_tests !== 'undefined' ? cp_v2_ab_tests.cp_v2_ab_tests_object : '';
var t_id                = -1;

(function( $ ) {
    /* Extend Jquery Function */
    jQuery.fn.cp_center_modal = function () {

        var $this = $(this);
        var top_pos  = Math.max(0, (($(window).height() - $this.outerHeight()) / 2) ) + "px";
        var left_pos = Math.max(0, (($(window).width() - $this.outerWidth()) / 2) ) + "px"; 

        $this.css( "top", top_pos );
        $this.css( "left", left_pos );
        $this.css( "transform", '' );
        return $this;
    }
    
    var ConvertProHelper = {

        _lazyLoadImages: function() {

            $lazy_images = $('[data-cp-src]');

            if ( $lazy_images.length > 0 ) {
                $lazy_images.each(function( index ) {
                    this_image       = this;
                    j_this_image     = $(this_image),
                    this_module_type = j_this_image.attr('data-module-type');
                    is_valid_json    = true;

                    var lsrc               = j_this_image.attr('data-cp-src');
                    var popup_container    = j_this_image.closest('.cp-popup-container').find('.cp-popup-content');
                    var mobile_responsive  = popup_container.attr('data-mobile-responsive');
                    var mobile_break_pt    = parseInt( popup_container.attr('data-mobile-break-pt') );
                    var window_width       = $( window ).width();

                    if ( 'undefined' == typeof lsrc ) {
                        return;
                    }

                    // check if source is valid json
                    try {
                       var parsed_src = jQuery.parseJSON( lsrc )
                       //must be valid JSON
                    } catch(e) {
                        //must not be valid JSON  
                        is_valid_json = false;   
                    }

                    if( is_valid_json ) {

                        // Image source for desktop 
                        if ( window_width > mobile_break_pt || 'no' == mobile_responsive ) {
                            lsrc = parsed_src[0];
                        } else {
                            lsrc = parsed_src[1];
                        }
                    }

                    if ( 'undefined' == typeof lsrc ) {
                        return;
                    }

                    var src_obj = lsrc.split('|');

                    // Default image
                    if( src_obj[0] == 0 ) {
                        lsrc = cp_ajax.assets_url + src_obj[1];
                    } else {

                        if( 'undefined' != typeof src_obj[1] ) {
                            lsrc = src_obj[1];
                        } else {
                            lsrc = src_obj[0];
                        }
                    }

                    if( !lsrc ) {
                        return;
                    }

                    if ( this_image.tagName === 'IMG' ) {
                        this_image.src = lsrc;
                    } else {

                        if ( 'info_bar' == this_module_type || 'welcome_mat' == this_module_type || 'full_screen' == this_module_type ) {
                            j_this_image.closest('.cp-popup').css( 'background-image', 'url(' + lsrc + ')' );
                        } else {
                            this_image.style.backgroundImage = 'url(' + lsrc + ')';
                        }
                    }
                });
            }
        },

        _shrinkPopup: function () {

            var all_popups = $('.cp-popup-container');

            all_popups.each(function(i) {
                var $this = $(this);
                var el_module_type = $this.attr('data-module-type');
                var content_popups = $this.find('.cp-popup-content');
                
                content_popups.each(function(j) {
                    var active_popup = $(this);

                    // if toggle is enabled
                    if( ! active_popup.hasClass( 'toggle_active' ) ) {

                        // Toogle type is sticky
                        if( active_popup.find( '.cp-toggle-type-sticky' ).length > 0 ) {
                            return true;
                        }
                    }

                    var mobile_responsive = active_popup.attr('data-mobile-responsive');
                    var mobile_break_pt = parseInt( active_popup.attr('data-mobile-break-pt') );
                    var el_width = parseInt( active_popup.attr('data-width') );
                    var el_height = parseInt( active_popup.attr('data-height') );
                    var el_mobile_width = parseInt( active_popup.attr('data-mobile-width') );
                    var el_popup_position = active_popup.attr('data-popup-position');
                    var window_width = $( window ).width();
                    var window_height = $( window ).height();
                    var parent_window_width = window_width;
                    var el_scale = 1;
                    var el_scale_adjust = 0.10;
                    var inline_modules = false;

                    if ( el_module_type == 'before_after' || el_module_type == 'inline' || el_module_type == 'widget' ) {
                        var popup_container = $this.closest('.cp-popup-container');
                        
                        parent_window_width = popup_container.parent().width();
                        el_scale_adjust = 0;

                        inline_modules = true;
                    }

                    if ( mobile_responsive == 'yes' ) {
                         
                        if ( window_width > mobile_break_pt ) {
                            if ( parent_window_width < el_width ) {
                                el_scale =  ( parent_window_width / el_width ) - el_scale_adjust;
                            }
                        }else{
                            if ( parent_window_width < el_mobile_width ) {
                                el_scale =  ( parent_window_width / el_mobile_width ) - el_scale_adjust;
                            }
                        }
                    }else{
                     
                        if ( parent_window_width < el_width ) {
                            el_scale =  ( parent_window_width / el_width ) - el_scale_adjust;
                        }
                    }

                    var el_transform = '';
                    var el_transform_origin = '';
                    var el_top = '';
                    var el_left = '';
                    var el_right = '';
                    var el_bottom = '';
                    var el_scale_height = '';
                    var el_scale_height = '';

                    if ( el_scale != 1 && el_scale > 0 ) {
                        if ( el_module_type == 'modal_popup' || el_module_type == 'welcome_mat' || el_module_type == 'full_screen' ) {

                            el_transform = 'translateX(-50%) translateY(-50%) scale(' + el_scale + ')';
                            el_top = '50%';
                            el_left = '50%';
                            el_right = 'auto';
                            el_bottom = 'auto';
                        } else if ( el_module_type == 'slide_in' ) {
                            if ( el_popup_position == 'top center' || el_popup_position == 'bottom center' ) {
                                el_transform = 'translateX(-50%) scale(' + el_scale + ')';
                            }else if ( el_popup_position == 'center left' || el_popup_position == 'center right' ) {
                                el_transform = 'translateY(-50%) scale(' + el_scale + ')';
                            }else{
                                el_transform = 'scale(' + el_scale + ')';
                            }
                            
                            el_transform_origin = el_popup_position;
                        } else if ( el_module_type == 'info_bar' ) {
                            el_left = '50%';
                            el_transform = 'translateX(-50%) scale(' + el_scale + ')';
                            //el_transform_origin = el_popup_position;
                        }else if ( el_module_type == 'before_after' || el_module_type == 'inline' || el_module_type == 'widget' ) {
                            el_transform = 'translateX(-50%) scale(' + el_scale + ')';
                            el_left = '50%';
                            el_transform_origin = 'center top';
                        }

                        active_popup.css({
                            'transform': el_transform,
                            'transform-origin': el_transform_origin,
                            'right': el_right,
                            'bottom': el_bottom,
                            'top': el_top,
                            'left': el_left
                        });

                    } else {

                        active_popup.css({
                            'transform': el_transform,
                            'transform-origin': el_transform_origin,
                            'right': el_right,
                            'bottom': el_bottom,
                            'top': el_top,
                            'left': el_left
                        });

                        if( 'modal_popup' == el_module_type || 'full_screen' == el_module_type || 'welcome_mat' == el_module_type ) {
                            active_popup.cp_center_modal();
                        }
                    }

                    // This is to avoid extra space below widget after scaling
                    if ( 'widget' == el_module_type ) {
                        if ( el_scale != 1 ) {
                            el_scale_height = ( el_height * el_scale ) + 'px';
                        }
                        active_popup.closest('.cpro-form').css({
                            'height' : el_scale_height
                        });
                    }
                });
            })
        },

        _inactivityTimeEvent : function () {

            if( typeof cppInactivityTime === "undefined" ) {
                return;
            }

            cppInactivityTime = parseInt( cppInactivityTime ) * 1000;

            var timeoutTrigger;

            // WINDOW Events
            window.onload = resetTimer;
            // DOM Events
            document.onmousemove    = resetTimer;
            document.onkeypress     = resetTimer;
            document.onmousemove    = resetTimer;
            document.onmousedown    = resetTimer; // touchscreen presses
            document.ontouchstart   = resetTimer;
            document.onclick        = resetTimer;     // touchpad clicks
            document.onscroll       = resetTimer;    // scrolling with arrow keys
            document.onkeypress     = resetTimer;

            function resetTimer() {
                clearTimeout( timeoutTrigger );
                timeoutTrigger = setTimeout(function() {
                    $( document ).trigger('cpinactivebrowser');
                }, cppInactivityTime);
            }
        },

        _repositionOverlayFields: function( data ) {
                
            // for all respective to overlay fields
            data.find('.cpro-overlay-field').each( function() {

                var field_id = jQuery(this).attr('id');
                var admin_bar_ht = jQuery("#wpadminbar").outerHeight();

                // add margin top ( same as admin bar height ) to fields 
                jQuery("#"+ field_id).css( 'margin-top', admin_bar_ht + 'px' );

            });
        },

        _modelHeight: function() {

            jQuery('.cp-popup-container').each(function(index, element) {
            var t                  = jQuery(element),
                popup_wrap         = t.find('.cp-popup-wrapper'),
                modal_body_height  = t.find('.cp-popup-content').outerHeight(),
                widnow_height      = jQuery(window).height();

                if ( jQuery(this).hasClass('cpro-open') ) {
                    if( ( modal_body_height > widnow_height ) ) {

                        popup_wrap.each(function( i, el ) {
                            if(
                                jQuery(el).closest(".cp-popup-container").hasClass('cpro-open')
                                && ! ( jQuery(el).closest(".cp-popup-container").hasClass('cp-module-before_after') )
                                && ! ( jQuery(el).closest(".cp-popup-container").hasClass('cp-module-inline')
                                && ! ( jQuery(el).closest(".cp-popup-container").hasClass('cp-module-widget') ) )
                            ) {
                                jQuery('html').addClass('cpro-exceed-viewport');
                            }
                            jQuery('html').removeClass('cp-window-viewport');
                        });
                    } else {
                        jQuery('html').removeClass('cpro-exceed-viewport');
                    }
                }
            });
        },

        _rearrangeFormFields: function() {

            $(".cp-popup-content").each( function(e) {

                var sorted_fields = [];
                var $this = $(this);
                var form_container = $this.find( '.cpro-form-container' ); 

                $this.find( ".cp-form-field, .cp-button-field" ).each( function(e) {

                    var $this_form_field = $(this);
                    var top_position     = parseInt( $this_form_field.closest('.cp-field-html-data').css('top') );
                    var field_id         = $this_form_field.closest('.cp-field-html-data').attr('id');

                    sorted_fields.push([field_id, top_position]);

                });

                // sort array of fields according to top position
                sorted_fields.sort(function(a, b) {
                    return a[1] - b[1];
                });

                var fields_length = sorted_fields.length;

                for ( var field_index = 0; field_index < fields_length; field_index++ ) {
                    var field_id = sorted_fields[field_index][0];
                    
                    // append field to form container
                    $this.find( "#" + field_id ).appendTo( form_container );
                }
            });
        },

        /**
         * Sets position for Info Bar
         *
         * @param {Object} data from after popup open event
         * @return void
         * @since 0.0.1
         */
        _cpInfobarPosition: function( data ) {
            var cp_popup = data.find(".cp-popup");

            if( cp_popup.hasClass("cp-top") ) {

                var top_position = 0;
                var is_push_page_enabled = cp_popup.find(".infobar-settings").val();
                var is_toggle_enabled    = cp_popup.find(".infobar-toggle-settings").val();

                if( jQuery("#wpadminbar").length > 0 ) {
                    top_position = top_position + jQuery("#wpadminbar").outerHeight();
                }

                cp_popup.css( 'top', top_position + 'px' );

                if( is_push_page_enabled == '1' && ( is_toggle_enabled != 1 || cp_popup.find('.cp-popup-content').hasClass('infobar_toggle_active') ) ) {
                    
                    var info_bar_ht = cp_popup.find(".cp-popup-content").outerHeight();

                    if( jQuery("#wpadminbar").length > 0 ) {
                        info_bar_ht = info_bar_ht + jQuery("#wpadminbar").outerHeight();
                    }

                    jQuery("html").addClass("cpro-ib-open");
                    var data_style = data.closest(".cp-popup-container").data("style");
                    jQuery(".cp-ib-push-style[data-id='" + data_style + "']").remove();

                    var style = "<style type='text/css' data-id='" + data_style + "' class='cp-ib-push-style'>html.cpro-ib-open { margin-top: "+ info_bar_ht +"px !important; } </style>";
                    jQuery("head").append(style);

                }

            } else {
                if( ! data.hasClass("cp-ifb-scroll") ){
                    cp_popup.css('top','auto');
                } else {
                    var body_ht = jQuery("body").parent("html").outerHeight( true ),
                    cp_height  = cp_popup.find(".cp-popup").outerHeight(),
                    max_height = body_ht - cp_height ;
                }
            }
        },

        _repositionInfoBar: function( data ) {

            var cp_popup_id   = data.data("class-id");
            var popup_element = jQuery(".cp-popup-container[data-style='cp_style_" + cp_popup_id + "']").find(".cp-popup");
            if( popup_element.hasClass( "cp-top" ) ) {

                jQuery("html").css( "transition", "margin 1s ease-in-out" );
                jQuery("html").removeClass("cpro-ib-open");

                setTimeout(function() {
                    jQuery("html").css( "transition", "" );  
                }, 1200 );
            }   
        },

        /**
         * Sets position for Slide In
         *
         * @param {Object} data from after popup open event
         * @return void
         * @since 0.0.1
         */
        _cpSlideInPosition: function( data ) {
            var cp_popup = data.find(".cp-popup .cp-popup-content");

            if( cp_popup.hasClass("cp-top-left") || cp_popup.hasClass("cp-top-center") || cp_popup.hasClass("cp-top-right") ) {
                
                var top_position = 0;
                if( jQuery("#wpadminbar").length > 0 ) {
                    top_position = top_position + jQuery("#wpadminbar").outerHeight();
                }
                cp_popup.css( 'margin-top', top_position + 'px' );

            }
        },

        /* AB Test Helper */
        _refreshABTests: function() {

            jQuery.each( ab_test, function(i, val ) {

                var arr = JSON.stringify( val );

                if( global_cp_cookies.get("cp_v2_ab_test-" + i) != undefined ) {

                    var completedArr    = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_completed-" + i) ),
                        pendingArr      = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_pending-" + i) );

                    if( completedArr.length == 0 || pendingArr.length == 0 ) {

                        global_cp_cookies.set( "cp_v2_ab_test-" + i, arr, { expires : 365 } );
                        global_cp_cookies.set( "cp_v2_ab_test_completed-" + i, new Array(), { expires : 365 } );
                        global_cp_cookies.set( "cp_v2_ab_test_pending-" + i, arr, { expires : 365 } );
                        global_cp_cookies.set( "cp_v2_ab_test_show-" + i, val[0], { expires : 365 } );
                    }else{
                        global_cp_cookies.set( "cp_v2_ab_test_show-" + i, pendingArr[0], { expires : 365 } );
                    }

                } else {

                    global_cp_cookies.set( "cp_v2_ab_test-" + i, arr, { expires : 365 } );
                    global_cp_cookies.set( "cp_v2_ab_test_completed-" + i, new Array(), { expires : 365 } );
                    global_cp_cookies.set( "cp_v2_ab_test_pending-" + i, arr, { expires : 365 } );
                    global_cp_cookies.set( "cp_v2_ab_test_show-" + i, val[0], { expires : 365 } );
                }

                global_cp_cookies.set( "cp_v2_ab_test_display-" + i, false, { expires : 365 } );

            });
        },

        _setLoaderStyle: function() {
            $('.cp-popup-container').each(function(index, element) {
                
                var $this  = $(element),
                modal      = $this.find('.cp-popup-content');

                modal.find(':input').each(function( i, el ) {

                    var $this_btn    = $(this);
                    var $jquery_head = $('head');

                    if( $this_btn.hasClass('cp-button' ) ) {

                        var button_type = $this_btn.closest('.cp-field-html-data').data('type');
                        var button_id   = $this_btn.closest('.cp-field-html-data').attr('id');

                        if( button_type == 'cp_button' ) {

                            var back_color = $this_btn.css("background-color");
                            var text_color = $this_btn.css("color");
                            var border_color = $this_btn.css("border-color");
                            
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp_loader_container {border-left-color:'+ text_color +';}</style>');
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp_loader_container.cp_success_loader_container {border-color:'+ text_color +';}</style>');
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' i.cp-button-loader-style.draw.success-loader-style {color:'+ text_color +';}</style>');
                            
                            if( $this_btn.closest('.cp_loader_container') ) {
                                $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp-target.cp-field-element.cp-button-field.cp-button-loading:hover {background:'+ back_color +';}</style>');
                                $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp-target.cp-field-element.cp-button-field.cp-button-loading:hover {border-color:'+ border_color +';}</style>');
                            }

                        } else if( button_type == 'cp_gradient_button' ) {

                            var back_color = $this_btn.css("background-color");
                            var text_color = $this_btn.css("color");
                            var border_color = $this_btn.css("border-color");
                            
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp_loader_container {border-left-color:'+ text_color +';}</style>');
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' .cp_loader_container.cp_success_loader_container {border-color:'+ text_color +';}</style>');
                            $jquery_head.append('<style type="text/css"> #' + button_id + ' i.cp-button-loader-style.draw.success-loader-style {color:'+ text_color +';}</style>');
                        }
                    }
                });
            });
        }
    };

    ConvertProPopup = function() {
        
        /* Current Popups ID */
        this.currentID = 0,

        /* Popups Data */
        this.popups = {},

        /* Configure Data */
        this.configure = {},

        /* Start Time */
        this.pageStartTime = cppPageStartTime,

        /* Messages to show */
        this.logMessages = [],

        // The init method that loads the object class.
        this.init = function(settings){

            /* Set Settings. */
            this.setInitSettings( settings );

            /* Set Sessions. */
            this.setSessions();

            /* Set Cookies. */
            this.setCookies();

            /* Execute Process */
            this.execute();
        },

        /* Helper Functions Start */
        this.getTimestampNow = function() {
            return Date.now();
        },

        this.isFirstTimeVisitor = function() {
            return global_cp_cookies.get('cppro-ft') !== undefined && global_cp_cookies.get('cppro-ft-style') !== undefined;
        },

        this.isReturningVisitor = function() {
            return global_cp_cookies.get('cppro-ft') !== undefined && global_cp_cookies.get('cppro-ft-style') === undefined;
        },

        this.setSetting = function( key, value ) {
            
            this.popups[key] = value;
            
            return true;
        },

        this.getSetting = function( key ) {
            
            return this.popups[key];
        },

        this.getConfigure = function( key ) {
            var configure_opt = this.getSetting( 'configure' ); 

            return configure_opt[key];
        },
        /* Helper Functions End */
        
        /* Set settings */
        this.setInitSettings = function(settings){

            /* Set popupID */
            this.currentID = settings.popup_id;

            var cookie_name = 'cp_style_' + settings.popup_id;

            this.popups = {
                'id'                : settings.popup_id,
                'type'              : settings.popup_type,
                'wrap'              : settings.popup_wrap,
                'normal_cookie'     : cookie_name,
                'temp_cookie'       : 'temp_' + cookie_name,
                'live'              : false,
            }

            cppPopupsData[settings.popup_id] = {
                'type' : settings.popup_type,
                'live' : false
            }

            if ( false !== settings.popup_wrap  ) {
                this.popups['configure']   = jQuery.parseJSON( settings.popup_wrap.find('.panel-settings').val() );
                this.popups['rulesets']   = jQuery.parseJSON( settings.popup_wrap.find('.panel-rulesets').val() );
            }
        },

        /* Sets the sessions */
        this.setSessions = function(){
            if (typeof localStorage === 'object') {
                
                try {
                    /* Session time. */
                    var is_session = sessionStorage.getItem('cp-pro-session-init');
                    if ( is_session === null ) {
                        sessionStorage.setItem('cp-pro-session-init', this.getTimestampNow());
                    }

                    /* session page views. */
                    var is_page_views = sessionStorage.getItem('cp-pro-page-views');
                    if ( is_page_views === null ) {
                        sessionStorage.setItem('cp-pro-page-views', 1);
                    } else {
                        sessionStorage.setItem('cp-pro-page-views', ++is_page_views);
                    }
                } catch (e) {
                    Storage.prototype._setItem = Storage.prototype.setItem;
                    Storage.prototype.setItem = function() {};
                    console.log('Your web browser does not support storing settings locally. In Safari, the most common cause of this is using "Private Browsing Mode". Some settings may not save or some features may not work properly for you.');
                }
            }
        },

        /* Set cookies */
        this.setCookies = function(){

            /* Set cookies for first time visitors */
            if ( !global_cp_cookies.get( 'cppro-ft' ) ) {

                global_cp_cookies.set( 'cppro-ft', true, { expires: 2601 } );
                global_cp_cookies.set( 'cppro-ft-style', true );

                // set cookie which will expire in 24 hours i.e. 1 Day
                global_cp_cookies.set( 'cppro-ft-style-temp', true, { expires: 1 } );
            }

            // if 24 hours cookie is expired, delete first time visitor cookie also
            if( !global_cp_cookies.get( 'cppro-ft-style-temp' ) ) {
                global_cp_cookies.remove( 'cppro-ft-style' );
            }

            var temp_cookie = this.getSetting('temp_cookie');
            /* Remove Temporary Cookie */
            global_cp_cookies.remove( temp_cookie );
        },

        this.execute = function() {

            var type        = this.getSetting( 'type' );
            
            if ( 'modal_popup' == type || 'info_bar' == type || 'slide_in' == type || 'welcome_mat' == type 
               || 'full_screen' == type ) {
                /* Parse the configure rules */
                this.parseRules();
            }   
            
        },

        /**
         * Function that create exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.createExitEvent = function( hash ) {
            
            var $this   = this,
                slug    = $this.currentID,
                event   = 'mouseleave.' + slug + '.' + hash;

            $(document).off(event).on(event, function(e){
                
                if ( e.clientY > 20 ) {
                    return;
                }

                if ( $this.canShow() ) {
                    $this.invokePopup();
                }
            });
        },

        /**
         * Function that destroy exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.destroyExitEvent = function( hash ) {

            var $this   = this,
                slug    = $this.currentID,
                event   = 'mouseleave.' + slug + '.' + hash;

            $(document).off(event);
        },

        /**
         * Function that create exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.createInactiveEvent = function( hash ) {
            
            var $this   = this,
                slug    = $this.currentID,
                event   = 'cpinactivebrowser.' + slug + '.' + hash;

            $(document).off(event).on(event, function(e){

                if ( $this.canShow() ) {
                    $this.invokePopup();
                }
            });
        },

        /**
         * Function that destroy exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.destroyInactiveEvent = function( hash ) {

            var $this   = this,
                slug    = $this.currentID,
                event   = 'cpinactivebrowser.' + slug + '.' + hash;

            $(document).off(event);
        },
        
        /**
         * Checks viewport of the screen
         *
         * @param {Object}
         * @return Boolean true/false
         * @since 0.0.1
         */
        this._isOnScreen = function( obj ) {
            var win = jQuery(window);

            var viewport = {
                top : win.scrollTop(),
                left : win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();

            var bounds = obj.offset();
            bounds.right = bounds.left + obj.outerWidth();
            bounds.bottom = bounds.top + obj.outerHeight();

            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        },

        /**
         * Function that create exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.createScrollEvent = function( ruleset, hash ) {
            
            var $this       = this,
                slug        = $this.currentID,
                event       = 'scroll.' + slug + '.' + hash,
                $window     = $(window),
                $document   = $(document);

            $(document).off(event).on(event, function(e){
                
                if ( !$this.canShow() ) {
                    return;
                }

                var windowScrollTop     = $window.scrollTop();
                var windowScrollPercent = 100 * windowScrollTop / ($document.height() - $window.height());
                var invoke = false;

                if ( '1' == ruleset.autoload_on_scroll ) {
                    if( windowScrollPercent > ruleset.load_after_scroll ) {
                        invoke = true;
                    }
                } else if( '1' == ruleset.enable_after_post ) {
                        
                    var afterPostDiv = $( ".cp-load-after-post" );

                    if( afterPostDiv.length > 0 ) {                        
                        scrollTill  = afterPostDiv.offset().top - 30;
                        scrollTill  = scrollTill - ( ( $window.height() * 50 ) / 100 );

                        if( windowScrollTop >= scrollTill ) {
                            invoke = true;
                        }
                    }
                } else if ( '1' == ruleset.enable_custom_scroll ) {
                    
                    var scroll_class = ruleset.enable_scroll_class;

                    if( 'undefined' !== typeof scroll_class && '' !== scroll_class ) { 
                        var scroll_element  = $( scroll_class );
                        var position        = scroll_element.position();

                        if( typeof position !== 'undefined' && position !== ' ' ) {
                            invoke = $this._isOnScreen( scroll_element );
                        }
                    }
                }
                
                if ( invoke ) {
                    $this.invokePopup();
                }
            })
        },

        /**
         * Function that destroy exit intent
         *
         * @return void
         * @since 0.0.1
         */
        this.destroyScrollEvent = function( hash ) {

            var $this   = this,
                slug    = $this.currentID,
                event   = 'scroll.' + slug + '.' + hash;

            $(document).off(event);
        },

        /**
         * Function that create custom link event
         *
         * @return void
         * @since 0.0.1
         */
        this.createCustomLinkEvent = function( ruleset, hash ) {
            
            var $this       = this,
                slug        = $this.currentID,
                module_type = this.getSetting('type'),
                event       = 'click.' + slug + '.' + hash,
                $window     = $(window),
                $document   = $(document);


            var manual_class = '.manual_trigger_' + slug;

            if ( '' != ruleset.custom_class ) {
                manual_class = manual_class + ',' + ruleset.custom_class;
            }
            
            // Register the click handler to open the optin.
            $(document).off(event).on(event, manual_class, function(e){
                e.preventDefault();

                var is_ab_test = $this._openABDesignByClick();
                
                if( module_type != 'modal_popup' ) {
                    display = true;
                } else {
                    display = ( $this._isOtherModalOpen( module_type ) );
                }

                if ( !is_ab_test && display ) {
                    $this.invokePopup();
                }
            });
        },

        /**
         * Checks if any other modal popup is open on current page
         *
         * @param string type of the popup.
         * @return Boolean
         * @since 0.0.1
         */
        this._isOtherModalOpen = function( type ) {

            var modal = this.getSetting('wrap');
            if( 'full_screen' == type || 'welcome_mat' == type ) {
                var other_flags = ( jQuery( ".cp-popup-container.cpro-open.cp-module-info_bar" ).length <= 0
                    || jQuery( ".cp-popup-container.cpro-open.cp-module-slide_in" ).length <= 0
                    || jQuery( ".cp-popup-container.cpro-open.cp-module-modal_popup" ).length <= 0 );
                var this_flag = false;
                if( 'full_screen' == type && jQuery( ".cp-popup-container.cpro-open.cp-module-welcome_mat" ).length <= 0 && jQuery( ".cp-popup-container.cpro-open.cp-module-modal_popup" ).length <= 0 ) {
                    this_flag = true;
                }

                if( 'welcome_mat' == type && jQuery( ".cp-popup-container.cpro-open.cp-module-full_screen" ).length <= 0 && jQuery( ".cp-popup-container.cpro-open.cp-module-modal_popup" ).length <= 0 ) {
                    this_flag = true;
                }
                return ( this_flag && other_flags );
            } else {
                return (  ( jQuery( ".cp-module-modal_popup.cpro-open" ).length <= 0 && jQuery( ".cp-module-full_screen.cpro-open" ).length <= 0 && jQuery( ".cp-module-welcome_mat.cpro-open" ).length <= 0 ) && ( !modal.hasClass('cpro-visited-popup') ) );
            }         
        },

        this._stripTrailingSlash = function( url ) {

            if( url.substr(-1) === '/') {
                return url.substr(0, url.length - 1);
            }
            return url;
        },

        /**
         * Get Referrer
         *
         * @return String referrer
         * @since 0.0.1
         */
        this.getReferrer = function() {
            var doc_ref  = document.referrer.toLowerCase();

            doc_ref     = doc_ref.replace( 'http:', '' );
            doc_ref     = doc_ref.replace( 'https:', '' );
            doc_ref     = this._stripTrailingSlash( doc_ref.replace(/.*?:\/\//g, "") );

            // remove double slash 
            doc_ref     = doc_ref.replace(/\/{2,}/g,'');
            doc_ref     = doc_ref.replace("www.","");

            return doc_ref;
        },

        this.referrerDisplayHide = function( referrers_string, type ) {

            var $this       = this;
            var doc_ref     = $this.getReferrer();
            var display     = true;
            var referrers   = referrers_string.split( ",");

            jQuery.each( referrers, function(i, url ){

                url             = $this._stripTrailingSlash( url );
                var dr_arr      = doc_ref.split(".");
                var ucount      = url.match(/./igm).length;
                var dr_domain   = dr_arr[0];

                url             = $this._stripTrailingSlash( url.replace(/.*?:\/\//g, "") );
                url             = url.replace("www.","");
                url             = url.replace( 'http:', '' );
                url             = url.replace( 'https:', '' );
                var url_arr     = url.split("*");

                if( doc_ref.indexOf("t.co") !== -1 ) {
                    doc_ref = 'twitter.com';
                }

                if( doc_ref.indexOf("plus.google.co") !== -1 ){
                    doc_ref = 'plus.google.com';
                } else if( doc_ref.indexOf("google.co") !== -1 ) {
                    doc_ref = 'google.com';
                }

                var _domain = url_arr[0];
                _domain = $this._stripTrailingSlash( _domain );

                if( type == "display" ) {

                    if( url.indexOf('*') !== -1 ) {

                        if( _domain == doc_ref ){
                            display = true;
                            return false;
                        } else if( doc_ref.indexOf( _domain ) !== -1 ){
                            display = true;
                            return false;
                        } else { 
                            display = false;
                            return false;
                        }
                    } else if( url == doc_ref ){

                        display = true;
                        return false;
                    } else {

                        display = false;
                    }
                } else if( type == "hide" ) {
                    if( url.indexOf('*') !== -1 ) {
                        if( _domain == doc_ref ){
                            display = false;
                            return false;
                        } else if( doc_ref.indexOf( _domain ) !== -1 ){
                            display = false;
                            return false;
                        } else {
                            display = true;
                            return false;
                        }
                    } else if( url == doc_ref ){
                        display = false;
                        return false;
                    } else if( doc_ref.indexOf( _domain ) !== -1 ){
                        display = false;
                        return false;
                    } else {
                        display = true;
                    }
                }
            });
            
            return display;
        },

        /**
         * Is Scheduled
         *
         * @return Boolean true/false
         * @since 0.0.1
         */
        this.isScheduled = function( rules ) {
            var popup_container = this.getSetting( 'wrap' );
            var modal           = popup_container.find(".cp-popup-wrapper");
            var style           = this.getSetting( 'id' );
            var module_type     = this.getSetting( 'type' );
            var tzoffset        = modal.data('tz-offset');
            var ltime           = '';
            var date            = new Date();

            // turn date to utc
            var utc = date.getTime() + (date.getTimezoneOffset() * 60000);

            // set new Date object
            var new_date = new Date();

            if( typeof rules.start_date !== "undefined" && typeof rules.end_date !== "undefined" ) {

                var start   = rules.start_date;
                var end     = rules.end_date; 
                start       = Date.parse(start);
                end         = Date.parse(end);

                ltime = Date.parse(new_date);

                if( ltime >= start && ltime <= end ){            
                    return true;
                } else {
                    return false;
                }

            } else {
                return true;
            }
        },
        /**
         * Checks all conditions to show popup
         *
         * @return Boolean true/false
         * @since 0.0.1
         */
        this.canShow = function() {

            var popup_container = this.getSetting('wrap'),
                style           = this.getSetting('id'),
                module_type     = this.getSetting('type'),
                cookies_enabled = this.getConfigure('cookies_enabled'),
                cookie_name     = this.getSetting('normal_cookie'), 
                temp_cookie     = this.getSetting('temp_cookie'),
                normal_cookie   = global_cp_cookies.get( cookie_name ),
                tmp_cookie      = global_cp_cookies.get( temp_cookie ),
                t_id            = this._getCurrentABTest(),
                cookie          = false,
                abTestFlag      = true;

            if ( '1' == cookies_enabled ) {

                if( normal_cookie ){
                    cookie = true;
                }

            }else {
                if( tmp_cookie ) {
                    cookie = true;
                }
            }

            var condition = true;

            if( 'modal_popup' != module_type && 'full_screen' != module_type && 'welcome_mat' != module_type ) {
                display = true;
            } else {
                display = ( this._isOtherModalOpen( module_type ) );
            }

            if( t_id != -1 ) {
                if( global_cp_cookies.get( "cp_v2_ab_test-" + t_id ) != undefined ) {
                    var completedArr = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_completed-" + t_id) );
                    var pendingArr = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_pending-" + t_id) );
                    var show_style = parseInt( global_cp_cookies.get( "cp_v2_ab_test_show-" + t_id ) );

                    if ( show_style == style ) {
                        abTestFlag = ( ( jQuery.inArray( style, completedArr ) >= 0 ) ) ? false : true;
                    }else{
                        abTestFlag = false;
                    }
                }

                if( module_type != 'modal_popup' && module_type != 'full_screen' ) {
                    display = ( this._isOtherModalOpen( module_type ) );
                }
            }

            var showcase_condition = true;
            if( popup_container.parent().hasClass( 'cp_template_html' ) ) {
                showcase_condition = false;
            }

            return ( !cookie && display && abTestFlag && showcase_condition );
        },

        /**
         * Function that invokes popup
         *
         * @return void
         * @since 0.0.1
         */
        this.invokePopup = function() {

            var cls_popup_container = this.getSetting( 'wrap' );
            var $window             = $( window );
            var modal               = cls_popup_container.find(".cp-popup-wrapper");
            var style               = this.getSetting( 'id' );
            var module_type         = this.getSetting( 'type' );
            
            if ( !cls_popup_container.hasClass('cpro-open') ) {

                if( 'welcome_mat' == module_type ) {
                    this._displayWelcomeMat( modal, module_type, style );
                } else {
                    cls_popup_container.addClass('cpro-open');
                }

                modal.removeClass('cp-close');

                /* Set live value that popup is visible now */
                this.setSetting( 'live', true );
                $window.trigger( 'update_test_status', [style] );
                $window.trigger( 'cp_after_popup_open', [modal, module_type,style]);
            }
        },

        this.parseRules = function() {

            var configure_rulesets = this.getSetting( 'rulesets' );

            if( 'undefined' == typeof configure_rulesets || null == configure_rulesets ) {
                return;
            }

            if( 'undefined' == typeof configure_rulesets.length ) {
                configure_rulesets = $.map(configure_rulesets, function (value, key) { return value; });
            }

            if ( configure_rulesets.length < 1 ) {
                return;
            }


            var $this = this;
            var checkingRules;
            var enabled_rulesets    = this.getEnabledRulesets( configure_rulesets );
            var active_rulesets     = this.prepareRulesets( enabled_rulesets );
            var is_popup_live       = this.getSetting( 'live' );

            var ruleset_verify_check = function() {
                
                is_popup_live           = $this.getSetting( 'live' );
                var mapped_rulesets     = $this.mapRulesets( active_rulesets );
                var hasExitEvent        = {};
                var hasScrollEvent      = {};
                var hasCustomLinkEvent  = {};
                var hasInactiveEvent    = {};
                var hasAdBlockEnabled   = {};
                
                var hasExitIntent = mapped_rulesets.map(function(ruleset, i) {
                    hasExitEvent[ruleset.hash] = false;

                    if ( '1' == ruleset.modal_exit_intent ) {
                        if ( ruleset.allPassed ) {
                            $this.createExitEvent( ruleset.hash );
                            hasExitEvent[ruleset.hash] = true;
                        } else if ( ! ruleset.allRulesPassed ) {
                            $this.destroyExitEvent( ruleset.hash );
                            hasExitEvent[ruleset.hash] = false;
                        }
                    }
                    return hasExitEvent;
                }.bind(this));

                var hasScroll = mapped_rulesets.map(function(ruleset, i) {
                    hasScrollEvent[ruleset.hash] = false;
                    
                    if ( '1' == ruleset.autoload_on_scroll || '1' == ruleset.enable_after_post || '1' == ruleset.enable_custom_scroll ) {
                        if ( ruleset.allPassed ) {
                            $this.createScrollEvent( ruleset, ruleset.hash );
                            hasScrollEvent[ruleset.hash] = true;
                        } else if ( ! ruleset.allRulesPassed ) {
                            $this.destroyScrollEvent( ruleset.hash );
                            hasScrollEvent[ruleset.hash] = false;
                        }
                    }
                    return hasScrollEvent;
                }.bind(this));

                var hasInactive = mapped_rulesets.map(function(ruleset, i) {
                    hasInactiveEvent[ruleset.hash] = false;

                    if ( '1' == ruleset.inactivity ) {
                        if ( ruleset.allPassed ) {
                            $this.createInactiveEvent( ruleset.hash );
                            hasInactiveEvent[ruleset.hash] = true;
                        } else if ( ! ruleset.allRulesPassed ) {
                            $this.destroyInactiveEvent( ruleset.hash );
                            hasInactiveEvent[ruleset.hash] = false;
                        }
                    }
                    return hasInactiveEvent;
                }.bind(this));

                var hasCustomLink = mapped_rulesets.map(function(ruleset, i) {
                    hasCustomLinkEvent[ruleset.hash] = false;

                    if ( '1' == ruleset.enable_custom_class ) {

                        $this.createCustomLinkEvent( ruleset, ruleset.hash );
                        hasCustomLinkEvent[ruleset.hash] = true;
                    }
                    return hasCustomLinkEvent;
                }.bind(this));

                checkingRules = mapped_rulesets.filter(function(ruleset) {
                    
                    return ruleset.allPassed 
                            && '1' != ruleset.modal_exit_intent 
                            && '1' != ruleset.autoload_on_scroll
                            && '1' != ruleset.enable_after_post
                            && '1' != ruleset.enable_custom_scroll
                            && '1' != ruleset.enable_custom_class
                            && '1' != ruleset.inactivity
                });

                active_rulesets = mapped_rulesets.filter(function(ruleset) {
                    return ! ruleset.allPassed && ruleset.keepAlive && '1' !== ruleset.enable_custom_class;
                });

                if ( checkingRules.length > 0 && $this.canShow() ) {
                    $this.invokePopup();
                }
                            
                if ( active_rulesets.length === 0 ) {
                    clearInterval( ruleset_verify_check_interval );
                }
            }

            ruleset_verify_check();
            
            var ruleset_verify_check_interval = setInterval( function() {

                if ( is_popup_live || active_rulesets.length < 1 ) {
                    clearInterval( ruleset_verify_check_interval );
                } else {
                    ruleset_verify_check();
                }
            }, 500);

            return;
        },

        this.getEnabledRulesets = function( rulesets ) {

            var filterRulsets = rulesets.filter(function(ruleset) {

                    return '1' == ruleset.autoload_on_duration
                            || '1' == ruleset.autoload_on_scroll
                            || '1' == ruleset.enable_after_post
                            || '1' == ruleset.enable_custom_class
                            || '1' == ruleset.enable_custom_scroll
                            || '1' == ruleset.enable_scroll_class
                            || '1' == ruleset.inactivity
                            || '1' == ruleset.modal_exit_intent
                            || '1' == ruleset.enable_visitors
                            || '1' == ruleset.enable_referrer
                            || '1' == ruleset.enable_adblock_detection

                });

            return filterRulsets;
        },

        this.prepareRulesets = function( rulesets ) {

            return rulesets.map(function(ruleset) {
                
                ruleset.hash        = ( Math.random() + 1 ).toString(36).slice( 2, 12 );
                ruleset.keepAlive   = this.keepRulsetAlive( ruleset );
                return ruleset;
            }.bind(this));
        },

        this.mapRulesets = function( activeRulesets ) {
            /* allPassed */
            return  activeRulesets.map(function(ruleset) {
                
                if ( '1' == ruleset.enable_custom_class ) {
                    ruleset.allPassed = true;
                }else{
                    ruleset.allPassed = this.verifyRules(ruleset);
                }

                return ruleset;
            }.bind(this));
        },

        this.verifyRules = function( rules ) {
        
            var $this                   = this;
            var active_rules            = {};
            var passed_rules            = {};
            var rule_passed             = true;


            $.each(rules, function(rule, data) {
                
                switch(rule) {
                    case 'autoload_on_duration':

                            if ( '1' == data ) {

                                active_rules['autoload_on_duration'] = true;
                                passed_rules['autoload_on_duration'] = false;

                                var delay           = parseInt( rules.load_on_duration ) * 1000 ;
                                var execute_time    = $this.pageStartTime + delay;
                                var time_now        = Date.now();

                                if ( time_now >= execute_time ) {
                                    passed_rules['autoload_on_duration'] = true;
                                }
                            }
                        break;
                    case 'enable_visitors':

                            if ( '1' == data ) {

                                active_rules['enable_visitors'] = true;
                                passed_rules['enable_visitors'] = false;
                                
                                if ( 
                                        ( 'first-time' == rules.visitor_type && $this.isFirstTimeVisitor() ) 
                                        || ( 'returning' == rules.visitor_type && $this.isReturningVisitor() ) 
                                    ) {
                                    passed_rules['enable_visitors'] = true;
                                }
                            }
                        break;
                    case 'enable_referrer':

                        if ( '1' == data ) {
                            
                            active_rules['enable_referrer'] = true;
                            passed_rules['enable_referrer'] = false;
                            
                            if ( 'display-to' === rules.referrer_type && '' !== rules.display_to ) {
                                
                                if ( $this.referrerDisplayHide( rules.display_to, 'display' ) ) {
                                    passed_rules['enable_referrer'] = true;
                                }
                            } else if ( 'hide-from' === rules.referrer_type && '' !== rules.hide_from ) {
                                
                                if ( $this.referrerDisplayHide( rules.hide_from, 'hide' ) ) {
                                    passed_rules['enable_referrer'] = true;
                                }
                            }
                        }

                        break;

                    case 'enable_scheduler':

                        if ( '1' == data ) {
                            
                            active_rules['enable_scheduler'] = true;
                            passed_rules['enable_scheduler'] = false;

                            
                            if ( $this.isScheduled( rules ) ) {
                                passed_rules['enable_scheduler'] = true;
                            }
                        }

                        break;
                    case "enable_adblock_detection":

                        if ( '1' == data ) {
                            
                            active_rules['enable_adblock_detection'] = true;
                            passed_rules['enable_adblock_detection'] = false;

                            if ( cProAdBlockEnabled ) {
                                passed_rules['enable_adblock_detection'] = true;
                            }
                        }

                        break;
                    default:
                        show_popup = false;
                }
            });
            
            /* active rules condition */
            for ( key in active_rules ) {
                if ( passed_rules[key] !== true  ) {
                    rule_passed = false;
                }
            }
        
            return rule_passed;
        },

        this.anyRulesetsPassed = function( mapped_rulesets ) {

            var rulesetPassed = false;

            $.each( mapped_rulesets, function(key, rule) {
                
                if ( true === rule['allPassed'] ) {
                    rulesetPassed = true;
                    return;
                }
            });

            return rulesetPassed;
        },

        this.keepRulsetAlive = function(rules) {
            
            var keepAlive = false;
                
            if ( 
                    ( 
                        '1' == rules.autoload_on_duration
                        || '1' == rules.modal_exit_intent 
                        || '1' == rules.autoload_on_scroll
                        || '1' == rules.enable_after_post
                        || '1' == rules.enable_custom_scroll
                        || '1' == rules.inactivity
                        || '1' == rules.enable_adblock_detection
                    )
            ) {

                keepAlive = true;
            }

            return keepAlive;

        },

        this._setCookie = function( element ) {

            var cp_cookies = global_cp_cookies;
            var cookieName = element.closest( '.cp-popup-container' ).data( 'style' );
            var configure_settings = jQuery.parseJSON( element.closest( '.cp-popup-container' ).find( ".panel-settings[data-section='configure']" ).val() );
            var cookieTime = parseInt( configure_settings.conversion_cookie );
            var cookie = cp_cookies.get(cookieName);

            if( ! cookie ){
                if( cookieTime ) {
                    cp_cookies.set( cookieName, true, { expires: cookieTime } );
                }
            }
        },
        
        /**
         * Close Popup event
         *
         * @return void
         * @since 0.0.1
         */
        this._closepopupEvent = function() {

            var popup_container = this.getSetting('wrap'),
                cp_animate      = popup_container.find('.cpro-animate-container'),
                cp_popup_body   = popup_container.find( '.cp-popup-content' ),  
                entry_anim      = cp_popup_body.data( 'entry-animation' ),
                exit_anim       = cp_popup_body.data( 'exit-animation' ),
                cookies_enabled = this.getConfigure('cookies_enabled'), 
                cookieName      = this.getSetting('normal_cookie'), 
                temp_cookie     = this.getSetting('temp_cookie'),
                cookieTime      = parseInt( this.getConfigure('closed_cookie') ),
                module_type     = this.getSetting('type');

                global_cp_cookies.set( temp_cookie, true, { expires: 1 });
                var cookie      = global_cp_cookies.get( cookieName );

            if( 'welcome_mat' == module_type ) {
                exit_anim = 'cp-slideOutUp';
            }

            if ( '1' == cookies_enabled && ! cookie ) {
                global_cp_cookies.set( cookieName, true, { expires: cookieTime } );
            }

            cp_animate.addClass( entry_anim );
            cp_animate.addClass( exit_anim );
            $('.cpro-wel-mat-open').css( 'padding-top', '' );
            
            setTimeout( function() {
                
                $('html').removeClass('cpro-exceed-viewport cp-modal-popup-open cp-disable-scroll');
                popup_container.removeClass('cpro-open');
                popup_container.find(".cp-popup-wrapper").addClass('cpro-visited-popup');
                cp_animate.removeClass( exit_anim );

                if( 'welcome_mat' == module_type ) {
                    $('body').removeClass( 'cpro-wel-mat-open' );
                    $('html').removeClass( 'cpro-overflow-mat' );
                    $(window).scrollTop(0);
                }

            }, 500 );

            if( 'info_bar' == module_type ) {
                ConvertProHelper._repositionInfoBar( popup_container );
            }
        },

        /* AB Test Functions */
        this._getCurrentABTest = function() {
            
            var style   = this.getSetting('id');
            var ret     = -1;

            jQuery.each( ab_test, function( i, val ) {
                if( jQuery.inArray( style, val ) >= 0 ) {
                    ret = i;
                }
            });
            return ret;
        },

        this._openABDesignByClick = function() {

            var t_id        = this._getCurrentABTest();
            var is_ab_test  = false;
            var invokeFlag  = true;

            if( t_id != -1 ) {

                /* Set A/B test true */
                is_ab_test = true;

                var style           = this.getSetting('id');
                var completedArr    = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_completed-" + t_id) );
                var pendingArr      = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_pending-" + t_id) );
                var show_style      = parseInt( global_cp_cookies.get( "cp_v2_ab_test_show-" + t_id ) );

                
                if ( show_style == style ) {
                    invokeFlag = ( ( jQuery.inArray( style, completedArr ) >= 0 ) ) ? false : true;
                }else{
                    invokeFlag = false;
                }

                if( invokeFlag ) {

                    var popup_container = this.getSetting('wrap'),
                        module_type     = this.getSetting('type')
                        modal           = popup_container.find( ".cp-popup-wrapper" ),
                        showCondition   = true;

                    // Check if any other design is already opened
                    showCondition = ( jQuery( ".cp-popup-container.cpro-open" ).length > 0 ) ? false : true;

                    if( showCondition ) {
                        
                        /* Show Popup */
                        this.invokePopup();
                        
                        /* AB Test Refresh */
                        ConvertProHelper._refreshABTests();
                    }
                }
            }

            return is_ab_test;
        },

        this._displayWelcomeMat = function( modal_obj, module, style_id ) {

            var $this    = this;
            var win_ht   = $(window).height();
            var modal_container = modal_obj.closest(".cp-popup-container");

            $(window).scrollTop(0);
            $('body').addClass( 'cpro-wel-mat-open' );
            $('html').addClass( 'cpro-overflow-mat' );
            $('.cpro-wel-mat-open').css( 'padding-top', win_ht + 'px' );

            modal_container.find(".cp-popup").addClass( 'cp-animated cp-slideInDown' );
            modal_container.addClass( 'cpro-open' );

            $(window).scroll(function(){
                $this._closeWelcomeMat();
            });

        },

        this._closeWelcomeMat = function() {
            
            var open_mat = $(".cp-module-welcome_mat.cpro-open"); 

            if( open_mat.length > 0 ) {

                var popup_element = open_mat.find(".cp-popup");

                if ( ! this._isOnScreen( popup_element ) ) {

                    this._setCookie( popup_element );

                    // enable scroll on popup close
                    $('html').removeClass('cp-disable-scroll cpro-exceed-viewport cp-window-viewport cpro-overflow-mat');
                    $('.cpro-wel-mat-open').css( 'padding-top', '' );
                    open_mat.removeClass('cpro-open');
                    $('body').removeClass( 'cpro-wel-mat-open' );

                    $(window).scrollTop(0);
                }
            }
        }
    };

    /* Ready Event */
    $( document ).ready(function() {

        var fakeAd = document.createElement('div');
        fakeAd.innerHTML = '&nbsp;';
        fakeAd.className = 'adsbox';
        document.body.appendChild(fakeAd);
        window.setTimeout(function() {
            if ( fakeAd.offsetHeight === 0 ) {
                cProAdBlockEnabled = true;
            }

            if( cProAdBlockEnabled ) {
                fakeAd.remove();
            }
        }, 100 );

        setTimeout(function() {
            fakeAd.remove();
        }, 400 );

        cppPageStartTime = Date.now();

        $( ".cp-popup-container" ).each( function(event) {
            
            var $this           = $(this);
            var design_id       = $this.data( "class-id" );
            var module_type     = $this.data("module-type");

            /* Animation Fixed */
            if( module_type == 'widget' || module_type == 'inline' || module_type == 'before_after' ) {
                
                var modal = $this.find('.cp-popup-content');

                $( document ).trigger('cp-load-field-animation', [modal] );
            }else{

                initConvertPro[design_id] = new ConvertProPopup();
                initConvertPro[design_id].init({ popup_id: design_id, popup_type: module_type, popup_wrap: $this });
            }

        });
        
        ConvertProHelper._shrinkPopup();
        ConvertProHelper._rearrangeFormFields();
        ConvertProHelper._setLoaderStyle();

        if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
            $('html').addClass("cpro-ios-device");
        }

        if( '1' == image_on_ready ) {
            /* Start Loading Images */
            ConvertProHelper._lazyLoadImages();
        }

    });

    /* AB Test Refresh */
    ConvertProHelper._refreshABTests();

    $( window ).load( function() {

        if( '1' !== image_on_ready ) {
            /* Start Loading Images */
            ConvertProHelper._lazyLoadImages();
        }
        
        /* Idea popup event */
        ConvertProHelper._inactivityTimeEvent();        
    
    });
    
    /* Resize Event */
    var cpResizeTimer;
    $(window).resize(function(){

        //  Model height
        ConvertProHelper._modelHeight();

        clearTimeout( cpResizeTimer );
        cpResizeTimer = setTimeout( ConvertProHelper._shrinkPopup(), 100);

        if( $( window ).outerWidth() <= 768 ) {
            $( '.cp-popup-container' ).each( function( key ) {
                if( $( this ).hasClass( 'cpro-open' ) && $( this ).hasClass( 'cp-module-modal_popup' ) ) {
                    $('html').addClass('cp-disable-scroll');
                }
            } );
        } else {
            $('html').removeClass('cp-disable-scroll');
        }
    });
    
     /* Toggle Field Click */
    $( document ).on( 'click', '.cp-open-toggle', function() {
        
        var toggle          = $(this),
            toggle_wrap     = toggle.closest(".cp-open-toggle-wrap"),
            modal           = toggle.closest( '.cp-popup-container' ),
            style           = modal.data( "class-id" ),
            popup_content   = modal.find( '.cp-popup-content' ),
            toggle_position = toggle.data('position'),
            toggle_type     = toggle.data('type'),
            modal_transform = '';

        popup_content.toggleClass("toggle_active");

        if ( toggle_type == 'hide_on_click' ) {
            switch( toggle_position ) {
                case "bottom-right":
                case "bottom-left":
                    toggle_wrap.addClass("cp-animated cp-slideOutDown");
                    modal_transform = 'translateY(0)';
                break;
                case "bottom-center":
                    toggle_wrap.addClass("cp-animated cp-slideOutDown");
                    modal_transform = 'translateY(0) translateX(-50%)';
                break;
                case "top-left": 
                case "top-right":
                    toggle_wrap.addClass("cp-animated cp-slideOutUp");
                    modal_transform = 'translateY(0)';
                break;
                case "top-center": 
                    toggle_wrap.addClass("cp-animated cp-slideOutUp");
                    modal_transform = 'translateX(-50%) translateY(0)';
                break;
                case "center-left":
                    toggle_wrap.addClass("cp-animated cp-slideOutLeft");
                    modal_transform = 'translateX(0)  translateY(-50%)';
                break;
                case "center-right":
                    toggle_wrap.addClass("cp-animated cp-slideOutRight");
                    modal_transform = 'translateX(0)  translateY(-50%)';
                break;
            }

            if(
                $( window ).outerWidth() <= 768
                && 'top-center' != toggle_position
                && 'bottom-center' != toggle_position
            ) {
                popup_content.css({
                    'transform': modal_transform + 'translateX(-50%)',
                    "display" : "block"
                });
            } else {
                popup_content.css({
                    'transform': modal_transform,
                    "display" : "block"
                });
            }

            jQuery('.cp-module-slide_in.cp_has_toggle .cp-popup').css( 'display','block' );

            toggle_wrap.removeClass( toggle.attr( 'data-anim-class' ) );
            toggle_wrap.css({ '-webkit-animation-delay':'0s','animation-delay':'0s' }).addClass( toggle.attr( 'data-exit-anim-class' ) )
            
            if( toggle.attr( 'data-exit-anim-class' ) == 'cp-none' ) {
                toggle_wrap.hide();
            }

            modal.addClass('cpro-open');
            jQuery( window ).trigger( 'update_test_status', [style] );
                
        } else {

            modal.addClass('cpro-open');
            modal.data('slide-toggle-type', 'sticky');
            modal.data('slide-toggle-position', toggle_position);

            if( popup_content.hasClass('toggle_active') ) {
                jQuery('.cp-module-slide_in.cp_has_toggle_sticky .cp-popup-content').css(
                    'visibility','visible'
                );
            } else if ( !popup_content.hasClass( 'toggle_active' ) ) {
                setTimeout(function() {
                    jQuery('.cp-module-slide_in.cp_has_toggle_sticky .cp-popup-content').css( 
                        'visibility','hidden' 
                    );  
                }, 700 );
            }

            var mtype = 'slide_in';
            $( window ).trigger( 'cp_after_popup_open', [modal, mtype, style] );
        }
    });

    /* Toggle Field Click */
    $( document ).on( 'click', '.cp-open-infobar-toggle', function() {
        
        var toggle          = $(this),
            toggle_wrap     = toggle.closest(".cp-open-infobar-toggle-wrap"),
            modal           = toggle.closest( '.cp-popup-container' ),
            style           = modal.data( "class-id" ),
            popup_content   = modal.find( '.cp-popup-content' ),
            toggle_position = toggle.data('position'),
            modal_transform = '';

        popup_content.addClass("infobar_toggle_active");

        switch( toggle_position ) {
            case "bottom":
                toggle_wrap.addClass("cp-animated cp-slideOutDown");
                modal_transform = 'translateY(0) translateX(0)';
            break;
            case "top": 
                toggle_wrap.addClass("cp-animated cp-slideOutUp");
                modal_transform = 'translateX(0) translateY(0)';
            break;
        }

        toggle_wrap.removeClass( toggle.attr( 'data-anim-class' ) );
        toggle_wrap.css({ '-webkit-animation-delay':'0s','animation-delay':'0s' }).addClass( toggle.attr( 'data-exit-anim-class' ) )
        
        if( toggle.attr( 'data-exit-anim-class' ) == 'cp-none' ) {
            toggle_wrap.hide();
        }

        modal.addClass('cpro-open');
        
        jQuery('.cp-module-info_bar .cp-popup-wrapper .cp-popup').css({
            'transform': modal_transform,
            "display" : "block"
        });
        
        jQuery( window ).trigger( 'update_test_status', [style] );
        ConvertProHelper._cpInfobarPosition( modal.closest(".cp-popup-container").find('.cp-popup-wrapper') );
    });

    /* Shrink Popup */
    $( document ).on('cp-shrink-popup', function() {
        ConvertProHelper._shrinkPopup();
    });

    /* Lazy Load Images */
    $( document ).on('cp-load-popup-images', function() {
        ConvertProHelper._lazyLoadImages();
    });

    /* Field Animation */
    $( document ).on('cp-load-field-animation', function( e, modal_data ) {
        modal_data.find('.cp-field-html-data').each(function( i, el ) {
            var i_el        = jQuery(this);
            var duration    = i_el.attr('data-anim-duration');
            var delay       = i_el.attr('data-anim-delay');
            var anim_type   = typeof i_el.attr('data-animation') !== 'undefined' ? i_el.attr('data-animation') : '';
            var anim_type   = 'cp-animated ' + anim_type;

            i_el.css({
                '-webkit-animation-delay': delay,
                '-webkit-animation-duration': duration,
                'animation-delay': delay,
                'animation-duration': duration,
            });

            i_el.addClass( anim_type );
        });
    });
    
    /* Close Event */
    $( document ).on( "closePopup", function( event, modal, id) {
        
        var style_id            = modal.data("class-id");
        var popup_container     = jQuery(".cp-popup-container[data-style='cp_style_"+ style_id + "']");
        var module_type         = popup_container.attr('data-module-type');

        if( popup_container.hasClass("cp_has_toggle") && !popup_container.hasClass('cp_has_toggle_sticky') ) {
            var toggle = popup_container.find(".cp-open-toggle");
            var toggle_wrap = toggle.closest(".cp-open-toggle-wrap");
            var toggle_position = toggle.data("position");

            switch( toggle_position ) {

                case "bottom-right":
                case "bottom-left":
                case "bottom-center":
                    var animation_class = ' cp-slideInUp';
                    var removeClass = 'cp-slideOutDown';
                break;
                case "top-left": 
                case "top-right": 
                case "top-center": 
                    var animation_class = ' cp-slideInDown';
                    var removeClass = 'cp-slideOutUp';
                break;
                case "center-left":
                    var removeClass = 'cp-slideOutLeft';
                    var animation_class = ' cp-slideInLeft';
                break;
                case "center-right":
                    var removeClass = 'cp-slideOutRight';
                    var animation_class = ' cp-slideInRight';
                break;
            }

            toggle.css("display", "block");
            popup_container.find('.cp-popup').addClass( removeClass);

            setTimeout( function(){
                popup_container.removeClass('cpro-open');
                popup_container.find('.cp-popup').removeClass(removeClass).addClass(animation_class);
            }, 500);

            toggle_wrap.removeClass(removeClass).addClass( "cp-animated " + animation_class );

        }

        if( module_type == 'slide_in' && popup_container.hasClass("cp_has_toggle_sticky") ) {
            var toggle = popup_container.find(".cp-open-toggle");
            var toggle_wrap = toggle.closest(".cp-open-toggle-wrap");
            var toggle_position = toggle.data("position");

            switch( toggle_position ) {

                case "top-left":
                case "top-center":
                case "top-right":
                    var animation_class = ' cp-slideOutUp';
                break;
                case "bottom-left":
                case "bottom-center":
                case "bottom-right":
                    var animation_class = ' cp-slideOutDown';
                break;
                case "center-left":
                    var animation_class = ' cp-slideOutLeft';
                break;
                case "center-right": 
                    var animation_class = ' cp-slideOutRight';
                break;
            }

            popup_container.find('.cp-popup').removeClass('cp-none').addClass( animation_class );

        }

        if( popup_container.hasClass("cp_has_infobar_toggle") && module_type == 'info_bar' ) {

            var toggle = popup_container.find(".cp-open-infobar-toggle");
            var toggle_wrap = toggle.closest(".cp-open-infobar-toggle-wrap");
            var toggle_position = toggle.data("position");

            switch( toggle_position ) {

                case "bottom":
                    var animation_class = ' cp-slideInUp';
                    var removeClass = 'cp-slideOutDown';
                break;
                case "top": 
                    var animation_class = ' cp-slideInDown';
                    var removeClass = 'cp-slideOutUp';
                break;
            }
            jQuery(this).find('.cp-popup-content').removeClass("infobar_toggle_active");
            toggle.css("display", "block");
            popup_container.removeClass('cpro-open');
            toggle_wrap.removeClass(removeClass).addClass( "cp-animated " + animation_class );
            ConvertProHelper._repositionInfoBar( popup_container );
        }

        if( modal.hasClass( 'cpro-onload' ) && !popup_container.hasClass("cp_has_toggle")  && !popup_container.hasClass("cp_has_infobar_toggle")) {
            initConvertPro[style_id]._closepopupEvent();
        }
    });

     /* Close on Overlay Event */
    $( document ).on( "click", ".cpro-overlay", function(e) {

        var $this   = $( this ),
            id      = $this.closest(".cp-popup-container").find('input[name=style_id]').val(),
            modal   = $( '.cpro-onload[data-class-id=' + id + ']' ),
            content = $( '.cp_style_' + id ).find( '.cp-popup-content' );
            target  = $(e.target);    
    
        if ( $('.cp-popup-content').has(target).length == 0 && !target.hasClass('cp-popup-content') ) {

            if( 'undefined' != typeof content ) {
                if( 1 == content.data( 'overlay-click' ) ) {
                    jQuery( document ).trigger( 'closePopup', [modal,id] );
                }
            }
        }
    });

    /* AB Test after Event */
    $( window ).on( "update_test_status", function( e, style_id ) {
        var completedArr = [];
        var t_id         = initConvertPro[style_id]._getCurrentABTest();

        if( t_id != -1 ) {
            completedArr    = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_completed-" + t_id) ),
            pendingArr      = jQuery.parseJSON( global_cp_cookies.get("cp_v2_ab_test_pending-" + t_id) );
            
            if( jQuery.inArray( style_id, pendingArr ) >= 0 ) {

                pendingArr = jQuery.grep( pendingArr, function( value ) {
                    return value != style_id;
                });

                completedArr.push(style_id);

                global_cp_cookies.set( "cp_v2_ab_test_completed-" + t_id, completedArr, { expires : 365 } );
                global_cp_cookies.set( "cp_v2_ab_test_pending-" + t_id, pendingArr, { expires : 365 } );
            }

            global_cp_cookies.set( "cp_v2_ab_test_display-" + t_id, true, { expires : 365 } );
        }
    });

    /* After Popup Open Event */
    $( window ).on( "cp_after_popup_open", function( e, data, module_type, style_id ) {

        if( $( window ).outerWidth() <= 500 ) {
            if( $( '.cp_style_' + style_id ).hasClass( 'cpro-open' ) && 
                (
                    $( '.cp_style_' + style_id ).hasClass( 'cp-module-modal_popup' ) ||
                    $( '.cp_style_' + style_id ).hasClass( 'cp-module-full_screen' ) 
                ) ) {
                $('html').addClass('cp-disable-scroll');
            }
        }

        if( 'full_screen' == module_type ) {
            $('html').addClass('cpro-exceed-viewport');
        } else if( 'welcome_mat' != module_type ) {
            ConvertProHelper._modelHeight();
        }
             
        if( 'modal_popup' == module_type ) {
            jQuery("html").addClass("cp-modal-popup-open");
        }

        // when module is info bar and admin bar is visible
        if( 'info_bar' == module_type && jQuery("body").hasClass('admin-bar') ) {

            // only when info bar position is top
            if( data.find('.cp-popup').hasClass('cp-top') ) {
                ConvertProHelper._repositionOverlayFields( data );
            }
        }

        var close_btn_delay = data.data("close-btnonload-delay");

        // convert delay time from seconds to miliseconds
        close_btn_delay   = Math.round(close_btn_delay * 1000);

        if( close_btn_delay ){
            setTimeout( function(){
                data.find( '.cp-close-container' ).removeClass( 'cp-hide-close' );
            }, close_btn_delay);
        }

        var cp_popup_content = data.find('.cp-popup-content');
        var cp_animate  = data.find(".cpro-animate-container");
        var animationclass = cp_popup_content.data('entry-animation');
        var animatedwidth = cp_animate.data('disable-animationwidth');
        var popup_container = cp_animate.parents(".cp-popup-wrapper").hasClass('cp-popup-inline');

        if ( !data.closest(".cp-popup-container").hasClass( 'cp_has_infobar_toggle' ) || !data.closest(".cp-popup-container").hasClass( 'cp_has_toggle' ) ) {
            var vw = $(window).width();
            if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                if( typeof animationclass !== 'undefined' ) {
                    $(cp_animate).addClass("cp-animated "+ animationclass);
                }
            }

        }
        if ( data.closest(".cp-popup-container").hasClass( 'cp_has_infobar_toggle' ) ) {

            var toggle_position = data.closest(".cp-popup-container").find('.cp-open-infobar-toggle').attr( 'data-position' );
            var toggle_transform = 'translateY(0)';
            
            if ( !cp_popup_content.hasClass('toggle_active') ) {
                switch( toggle_position ) {
                    case "bottom":
                        toggle_transform = 'translateX(-50%) translateY(100%)';
                    break;
                    case "top": 
                        toggle_transform = 'translateX(-50%) translateY(-100%)';
                    break;
                }

            } else {
                switch( toggle_position ) {
                    case "bottom":
                    case "top": 
                        toggle_transform = 'translateX(-50%) translateY(0)';
                    break;
                }
            }

            // if wordpress admin bar is present and togggle position
            if( typeof(toggle_position) != "undefined" && toggle_position !== null ) {
                if( jQuery("#wpadminbar").length > 0 && toggle_position.includes("top") ) {

                    var admin_bar_ht = jQuery("#wpadminbar").height();
                    var infobar_toggle_button = data.closest(".cp-popup-container").find('.cp-open-infobar-toggle');
                    data.closest(".cp-popup-container").find('.cp-open-infobar-toggle').css( "margin-top", admin_bar_ht );
                    data.closest(".cp-popup-container").find('.cp-open-infobar-toggle-wrap.cp-top').addClass('cp-animated').addClass('cp-slideInDown');
                } else {
                    if( toggle_position.includes("top") ) {
                        data.closest(".cp-popup-container").find('.cp-open-infobar-toggle-wrap.cp-top').addClass('cp-animated').addClass('cp-slideInDown');
                    } else {
                        data.closest(".cp-popup-container").find('.cp-open-infobar-toggle-wrap.cp-bottom').addClass('cp-animated').addClass('cp-slideInUp');
                    }
                }
            }

            jQuery('.cp-module-info_bar .cp-popup-wrapper .cp-popup').css({
                'transform': toggle_transform
            });
            
        } else if ( data.closest(".cp-popup-container").hasClass( 'cp_has_toggle' ) ) {

            var toggle_position = data.closest(".cp-popup-container").find('.cp-open-toggle').attr( 'data-position' );
            var toggle_transform = '';
            
            if ( !cp_popup_content.hasClass('toggle_active') ) {
                switch( toggle_position ) {
                    case "bottom-right":
                    case "bottom-left":
                        toggle_transform = 'translateY(100%)';
                    break;
                    case "bottom-center":
                        toggle_transform = 'translateX(-50%) translateY(100%)';
                    break;
                    case "top-left": 
                    case "top-right": 
                        toggle_transform = 'translateY(-100%)';
                    break;
                    case "top-center": 
                        toggle_transform = 'translateX(-50%) translateY(-100%)';
                    break;
                    case "center-left":
                        toggle_transform = 'translateY(-50%) translateX(-100%)';
                    break;
                    case "center-right":
                        toggle_transform = 'translateY(-50%) translateX(100%)';
                    break;
                }

            } else {

                switch( toggle_position ) {
                    case "bottom-right":
                    case "bottom-left":
                    case "top-left": 
                    case "top-right": 
                        toggle_transform = 'translateY(0)';
                    break;
                    case "bottom-center":
                    case "top-center": 
                        toggle_transform = 'translateX(-50%) translateY(0)';
                    break;
                    case "center-left":
                        toggle_transform = 'translateX(0%) translateY(-50%)';
                    break;
                    case "center-right":
                        toggle_transform = 'translateY(-50%) translateX(0%)';
                    break;
                }
            }

            // if wordpress admin bar is present and togggle position
            if( typeof(toggle_position) != "undefined" && toggle_position !== null ) {

                var position = data.closest(".cp-popup-container").find('.cp-open-toggle').data('position');
                if( jQuery("#wpadminbar").length > 0 && toggle_position.includes("top") ) {
                    var admin_bar_ht = jQuery("#wpadminbar").height();
                    var toggle_button = data.closest(".cp-popup-container").find('.cp-open-toggle');
                    if( toggle_button.data("type") == 'sticky' ) {
                        data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInDown');
                    }
                    if( toggle_button.data("type") == 'hide_on_click' ) {
                        if( position == 'top-left' || position == 'top-right' || position == 'top-center' ) {
                            data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInDown');
                        }
                        data.closest(".cp-popup-container").find('.cp-open-toggle').css( "margin-top", admin_bar_ht );
                        data.closest(".cp-popup-container").find('.cp-open-toggle-wrap').addClass('cp-animated').addClass('cp-slideInDown'); 
                    }
                } else {
                    switch( position ) {
                        case "bottom-right":
                        case "bottom-left":
                        case "bottom-center":
                            data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInUp');
                        break;
                        case "top-left": 
                        case "top-right":
                        case "top-center":
                            data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInDown');
                        break;
                        case "center-left":
                            data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInLeft');
                        break;
                        case "center-right":
                            data.closest(".cp-popup-container").find('.cp-open-toggle-wrap, .cp-popup').addClass('cp-animated').addClass('cp-slideInRight');
                        break;
                    }
                }
            }


            jQuery('.cp-open-toggle-wrap.cp-toggle-type-sticky .cp-open-toggle').css(
                'visibility','visible'
            );

            if(
                $( window ).outerWidth() >= 768
                && 'top-center' != toggle_position
                && 'bottom-center' != toggle_position
            ) {
                cp_popup_content.css({
                    'transform': toggle_transform
                });
            } else {
                cp_popup_content.css({
                    'transform': toggle_transform + 'translateX(-50%)'
                });
            }
        }
        
        if( 'info_bar' == module_type ) {
            ConvertProHelper._cpInfobarPosition( data );
        }

        if( 'slide_in' == module_type ) {
            ConvertProHelper._cpSlideInPosition( data );
        }

        setTimeout(function() {

            $( document ).trigger('cp-load-field-animation', [data] );

        }, 1000 );
    });

    // Invoke popup by manual trigger 
    jQuery( document ).on( 'cp-trigger-design', function( event, style_id ) {

        if( 'undefined' !== typeof initConvertPro[style_id] ) {
            initConvertPro[style_id].invokePopup();
        }

    });

    
})( jQuery );