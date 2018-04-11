(function ($) {
    "use strict";
    var ImageHotspotHandler = function ($scope, $) {
        $('.pp-hot-spot-tooptip').each(function () {
            var $position_local         = $(this).data('tooltip-position-local'),
                $position_global        = $(this).data('tooltip-position-global'),
                $width                  = $(this).data('tooltip-width'),
                $size                   = $(this).data('tooltip-size'),
                $animation_in           = $(this).data('tooltip-animation-in'),
                $animation_out          = $(this).data('tooltip-animation-out'),
                $background             = $(this).data('tooltip-background'),
                $text_color             = $(this).data('tooltip-text-color'),
                $arrow                  = ($(this).data('tooltip-arrow') === 'yes') ? true : false,
                $position               = $position_local;

            if (typeof $position_local === 'undefined' || $position_local === 'global') {
                $position = $position_global;
            }
            if (typeof $animation_out === 'undefined' || !$animation_out) {
                $animation_out = $animation_in;
            }
            
            $(this).tipso({
                speed:                  200,
                delay:                  200,
                width:                  $width,
                background:             $background,
                color:                  $text_color,
                size:                   $size,
                position:               $position,
                animationIn:            $animation_in,
                animationOut:           $animation_out,
                showArrow:              $arrow
            });
        });
    };
    
    var ImageComparisonHandler = function ($scope, $) {
        var image_comparison_elem       = $scope.find('.pp-image-comparison').eq(0),
            settings                    = image_comparison_elem.data('settings');
        
        image_comparison_elem.twentytwenty({
            default_offset_pct:         settings.visible_ratio,
            orientation:                settings.orientation,
            before_label:               settings.before_label,
            after_label:                settings.after_label,
            move_slider_on_hover:       settings.slider_on_hover,
            move_with_handle_only:      settings.slider_with_handle,
            click_to_move:              settings.slider_with_click,
            no_overlay:                 settings.no_overlay
        });
    };
    
    var CounterHandler = function ($scope, $) {
        var counter_elem                = $scope.find('.pp-counter').eq(0),
            $target                     = counter_elem.data('target');
        
        $(counter_elem).waypoint(function () {
            $($target).each(function () {
                var v                   = $(this).data("to"),
                    speed               = $(this).data("speed"),
                    od                  = new Odometer({
                        el:             this,
                        value:          0,
                        duration:       speed
                    });
                od.render();
                setInterval(function () {
                    od.update(v);
                });
            });
        },
            {
                offset:             "80%",
                triggerOnce:        true
            });
    };
    
    var LogoCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-logo-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var InfoBoxCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-info-box-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var InstaFeedPopupHandler = function ($scope, $) {
        var instafeed_elem              = $scope.find('.pp-instagram-feed').eq(0),
            settings                    = instafeed_elem.data('settings'),
            pp_widget_id                = settings.target,
            pp_popup                    = settings.popup,
            like_span                   = (settings.likes === '1') ? '<span class="likes"><i class="fa fa-heart"></i> {{likes}}</span>' : '',
            comments_span               = (settings.comments === '1') ? '<span class="comments"><i class="fa fa-comment"></i> {{comments}}</span>' : '',
        
            feed = new Instafeed({
                get:                    'user',
                userId:                 settings.user_id,
                sortBy:                 settings.sort_by,
                accessToken:            settings.access_token,
                limit:                  settings.images_count,
                target:                 pp_widget_id,
                resolution:             settings.resolution,
                orientation:            'portrait',
                template:               function () {
                    if (pp_popup === '1') {
                        if (settings.layout === 'carousel') {
                            return '<div class="pp-feed-item swiper-slide"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
                        } else {
                            return '<div class="pp-feed-item"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
                        }
                    } else {
                        if (settings.layout === 'carousel') {
                            return '<div class="pp-feed-item swiper-slide">' +
                                '<a href="{{link}}">' +
                                    '<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
                                    '<img src="{{image}}" />' +
                                '</a>' +
                                '</div>';
                        } else {
                            return '<div class="pp-feed-item">' +
                                '<a href="{{link}}">' +
                                    '<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
                                    '<img src="{{image}}" />' +
                                '</a>' +
                                '</div>';
                        }
                    }
                }(),
                after: function () {
                    if (settings.layout === 'carousel') {
                        var $carousel                   = $scope.find('.swiper-container').eq(0),
                            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
                            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
                            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
                            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
                            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
                            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
                            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
                            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
                            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
                            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
                            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
                            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
                            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,

                            mySwiper = new Swiper($carousel, {
                                direction:              'horizontal',
                                speed:                  $speed,
                                autoplay:               $autoplay,
                                effect:                 $effect,
                                slidesPerView:          $items,
                                spaceBetween:           $margin,
                                grabCursor:             $grab_cursor,
                                pagination:             '.swiper-pagination',
                                paginationClickable:    true,
                                loop:                   $loop,
                                nextButton:             '.swiper-button-next',
                                prevButton:             '.swiper-button-prev',
                                breakpoints: {
                                    // when window width is <= 480px
                                    480: {
                                        slidesPerView:  $items_mobile,
                                        spaceBetween:   $margin_mobile
                                    },
                                    // when window width is <= 640px
                                    768: {
                                        slidesPerView:  $items_tablet,
                                        spaceBetween:   $margin_tablet
                                    }
                                }
                            });
                    }
                }
            });
        feed.run();
        
        if (pp_popup === '1') {
            $(pp_widget_id).each(function () {
                $(this).magnificPopup({
                    delegate: 'div a', // child items selector, by clicking on it popup will open
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1]
                    },
                    type: 'image'
                });
            });
        }
    };
    
    var TeamMemberCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-tm-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };

    var ModalPopupHandler = function ($scope, $) {
        var popup_elem                  = $scope.find('.pp-modal-popup').eq(0),
            $main_class                 = popup_elem.data('main-class'),
            $popup_layout               = popup_elem.data('popup-layout'),
            $close_button               = (popup_elem.data('close-button') === 'yes') ? true : false,
            $close_button_pos           = popup_elem.data('close-button-pos'),
            $effect                     = popup_elem.data('effect'),
            $type                       = popup_elem.data('type'),
            $iframe_class               = popup_elem.data('iframe-class'),
            $src                        = popup_elem.data('src'),
            $trigger_element            = popup_elem.data('trigger-element'),
            $delay                      = popup_elem.data('delay'),
            $trigger                    = popup_elem.data('trigger'),
            $popup_id                   = popup_elem.data('popup-id'),
            $display_after              = popup_elem.data('display-after'),
            $esc_exit                   = (popup_elem.data('esc') === 'yes') ? true : false,
            $click_exit                 = (popup_elem.data('click') === 'yes') ? true : false;
            $main_class += ' ' + $popup_layout + ' ' + $close_button_pos + ' ' + $effect;
        //console.log($main_class);
        if ($trigger == 'exit-intent') {
            var flag = true,
                mouseY = 0,
                topValue = 0;

            if ( $display_after === 0 ) {
                $.removeCookie($popup_id, { path: '/' });
            }
            window.addEventListener("mouseout", function (e) {
                mouseY = e.clientY;
                if (mouseY < topValue && !$.cookie($popup_id) ) {
                    $.magnificPopup.open({
                        items: {
                            src: $src //ID of inline element
                        },
                        type: $type,
                        showCloseBtn: $close_button,
                        enableEscapeKey: $esc_exit,
                        closeOnBgClick: $click_exit,
                        removalDelay: 500, //Delaying the removal in order to fit in the animation of the popup
                        mainClass: 'mfp-fade mfp-fade-side', //The actual animation
                    });

                    if ( $display_after > 0 ) {
                        $.cookie($popup_id, $display_after, { expires: $display_after, path: '/' });
                    } else {
                        $.removeCookie( $popup_id );
                    }
                }
            },
            false);
        }
        else if ( $trigger == 'page-load') {
            if ( $display_after === 0 ) {
                $.removeCookie($popup_id, { path: '/' });
            }
            if ( !$.cookie($popup_id) ) {
                setTimeout(function() {
                    $.magnificPopup.open({
                        items: {
                            src: $src 
                        },
                        type: $type,
                        showCloseBtn: $close_button,
                        enableEscapeKey: $esc_exit,
                        closeOnBgClick: $click_exit,
                    });

                    if ( $display_after > 0 ) {
                        $.cookie($popup_id, $display_after, { expires: $display_after, path: '/' });
                    } else {
                        $.removeCookie( $popup_id );
                    }
                }, $delay);
            }
        } else {
            if (typeof $trigger_element === 'undefined' || $trigger_element === '') {
                $trigger_element = '.pp-modal-popup-link'
            }
            //console.log($trigger_element);
            $( $trigger_element ).magnificPopup({
                image: {
                    markup: '<div class="' + $iframe_class + '">'+
                            '<div class="modal-popup-window-inner">'+
                            '<div class="mfp-figure">'+
                            '<div class="mfp-close"></div>'+
                            '<div class="mfp-img"></div>'+
                            '<div class="mfp-bottom-bar">'+
                              '<div class="mfp-title"></div>'+
                              '<div class="mfp-counter"></div>'+
                            '</div>'+
                          '</div>'+
                          '</div>'+
                          '</div>',
                },
                iframe: {
                    markup: '<div class="' + $iframe_class + '">'+
                            '<div class="modal-popup-window-inner">'+
                            '<div class="mfp-iframe-scaler">'+
                                '<div class="mfp-close"></div>'+
                                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                            '</div>'+
                            '</div>'+
                            '</div>',
                },
                items: {
                    src: $src,
                    type: $type,
                },
                removalDelay: 500,
                showCloseBtn: $close_button,
                enableEscapeKey: $esc_exit,
                closeOnBgClick: $click_exit,
                mainClass: $main_class,
            });
        }
        $.extend(true, $.magnificPopup.defaults, {
            tClose: 'Close',
        });
    };
    
    var TableHandler = function ($scope, $) {
        var table_elem      = $scope.find('.pp-table').eq(0);
        
        $( document ).trigger( "enhance.tablesaw" );
    };
    
    var MapHandler = function ($scope, $) {
        var map_elem                = $scope.find('.pp-google-map').eq(0),
            target                  = map_elem.data('target'),
            locations               = map_elem.data('locations'),
            zoom                    = (map_elem.data('zoom') != '') ? map_elem.data('zoom') : 4,
            map_type                = (map_elem.data('map-type') != '') ? map_elem.data('map-type') : 'roadmap',
            streeview_control       = (map_elem.data('streeview-control') == 'yes') ? true : false,
            map_type_control        = (map_elem.data('map-type-control') == 'yes') ? true : false,
            zoom_control            = (map_elem.data('zoom-control') == 'yes') ? true : false,
            fullscreen_control      = (map_elem.data('fullscreen-control') == 'yes') ? true : false,
            scroll_zoom             = (map_elem.data('scroll-zoom') == 'yes') ? 'auto' : 'none',
            map_style               = (map_elem.data('custom-style') != '') ? map_elem.data('custom-style') : '',
            mapOptions              = '',
            map                     = '',
            i                       = '';
        
        (function initMap() {
            var latlng = new google.maps.LatLng(locations[0][0], locations[0][1]);
            mapOptions = {
                zoom:               zoom,
                center:             latlng,
                mapTypeId:          map_type,
                mapTypeControl:     map_type_control,
                streetViewControl:  streeview_control,
                zoomControl:        zoom_control,
                fullscreenControl:  fullscreen_control,
                gestureHandling:    scroll_zoom,
                styles:             map_style
            }
            var map = new google.maps.Map(document.getElementById(target), mapOptions);
            
            var infowindow = new google.maps.InfoWindow();

            for (i = 0; i < locations.length; i++) {
                var icon = '',
                    icon_size = '',
                    icon_type = '';
                if ( locations[i][0].length != '' && locations[i][1].length != '' ) {
                    icon_type = locations[i][5];
                    if ( icon_type == 'custom' ) {
                        icon_size = parseInt(locations[i][7]);
                        icon = {
                            url: locations[i][6], // url
                            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                            origin: new google.maps.Point(0,0), // origin
                            anchor: new google.maps.Point(0, 0) // anchor
                        };
                    }

                    var marker = new google.maps.Marker({
                        position:       new google.maps.LatLng(locations[i][0], locations[i][1]),
                        map:            map,
                        title:          locations[i][3],
                        icon:           icon,
                    });
                    
                    if ( locations[i][2] == 'yes' && locations[i][8] == 'iw_open' ) {
                        var contentString = '<div class="pp-infowindow-content">';
                        contentString += '<div class="pp-infowindow-title">'+locations[i][3]+'</div>';
                        if ( locations[i][3].length != '' ) {
                            contentString += '<div class="pp-infowindow-description">'+locations[i][4]+'</div>';
                        }
                        contentString += '</div>';
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString,
                        });
                        infowindow.open(map, marker);
                    }
                    
                    // Event that closes the Info Window with a click on the map
                    google.maps.event.addListener(map, 'click', (function(infowindow) {
                        return function() {
                            infowindow.close();
                        }
                    })(infowindow));

                    if ( locations[i][2] == 'yes' && locations[i][3] != '' ) {
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                var contentString = '<div class="pp-infowindow-content">';
                                    contentString += '<div class="pp-infowindow-title">'+locations[i][3]+'</div>';
                                    if ( locations[i][3].length != '' ) {
                                        contentString += '<div class="pp-infowindow-description">'+locations[i][4]+'</div>';
                                    }
                                    contentString += '</div>';
                                infowindow.setContent(contentString);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                }
            }
        })()
    };
    
    var InfoListHandler = function ($scope, $) {
        var info_list_elem      = $scope.find('.pp-list-items').eq(0);
        
        var item_first = info_list_elem.find('.pp-infp-list-item').first();
        var item_last = info_list_elem.find('.pp-infp-list-item').last();
        var icon_box = item_first.find('.pp-infolist-icon-wrapper');
        var connector = info_list_elem.find('.pp-info-list-connector');
        var item_height = item_first.height();
        var icon_width = icon_box.outerWidth();
        var icon_height = icon_box.outerHeight();
        var connector_width = connector.outerWidth();
        var icon_top = item_height + icon_height;
        console.log(icon_height);
        connector.css({
            'left': icon_width / 2 - connector_width / 2,
            'top': icon_top / 2,
            'bottom': icon_top / 2
        });
        connector.bind('heightChange', function(){
            console.log('icon_height');
        });
    };
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-hotspots.default', ImageHotspotHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-comparison.default', ImageComparisonHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-counter.default', CounterHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-logo-carousel.default', LogoCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-info-box-carousel.default', InfoBoxCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-instafeed.default', InstaFeedPopupHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-team-member-carousel.default', TeamMemberCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-modal-popup.default', ModalPopupHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-table.default', TableHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-google-maps.default', MapHandler);
        //elementorFrontend.hooks.addAction('frontend/element_ready/pp-info-list.default', InfoListHandler);
    });
    
}(jQuery));