(function($){
    //Premium Progress Bar Handler
    var PremiumProgressBarWidgetHandler = function ($scope,$){
        var progressbarElement = $scope.find('.premium-progressbar-progress-bar').each(function(){
            
            var settings = $(this).data('settings');
            
            var length  = settings['progress_length'];
            
            $(this).animate({width: length + '%'} , length * 25);
        });
    };
    //Premium Progress Bar on Scroll Handler
    var PremiumProgressBarScrollWidgetHandler = function ($scope,$){
      $scope.waypoint(function (direction) {
            PremiumProgressBarWidgetHandler($(this), $);
        }, {
            offset: $.waypoints('viewportHeight') - 150,
            triggerOnce: true
        });
    };
    //Premium Video Box Handler
    var PremiumVideoBoxWidgetHandler = function($scope,$){
        var videoBoxElement = $scope.find('.premium-video-box-container');
        videoBoxElement.on( "click", function(){
            $( this ).children( ".premium-video-box-video-container" ).css(
                {
                    'opacity': '1',
                    'visibility': 'visible'
                } );
            setTimeout(function(){
            videoBoxElement.find("iframe").attr('src', videoBoxElement.find("iframe").attr('src') + '?autoplay=1');
            },600);
        });
    };
    //Premium Grid Handler
    var PremiumGridWidgetHandler = function($scope,$){    
        if ($().isotope === undefined) {
            return;
        }
        var gridElement = $scope.find('.premium-img-gallery');
        if (gridElement.length === 0) {
            return;
        }
        var htmlContent = $scope.find('.premium-gallery-container');
        var isotopeOptions = htmlContent.data('settings');
        if(isotopeOptions['img_size'] === 'original'){
            htmlContent.isotope({
            // options
                itemSelector: '.premium-gallery-item',
                percentPosition: true,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            htmlContent.imagesLoaded(function () {
                htmlContent.isotope({layoutMode: 'masonry'});
            });
        } else if(isotopeOptions['img_size'] === 'one_size'){
            
            htmlContent.isotope({
            // options
                itemSelector: '.premium-gallery-item',
                percentPosition: true,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            htmlContent.imagesLoaded(function () {
                htmlContent.isotope({layoutMode: 'fitRows'});
            });
        }
        $scope.find('.premium-gallery-cats-container li a').click(function(e){
            e.preventDefault();        
            $scope.find('.premium-gallery-cats-container li .active').removeClass('active');
            $(this).addClass('active');
            var selector = $(this).attr('data-filter');
            htmlContent.isotope({filter: selector});
            return false;
        });
        $(".premium-img-gallery a[data-rel^='prettyPhoto']").prettyPhoto({
            theme: 'pp_default',
            hook: 'data-rel',
            opacity: 0.7,
            show_title: false,
            deeplinking: false,
            overlay_gallery: false,
            custom_markup: '',
            default_width: 900,
            default_height: 506,
            social_tools: ''
        });
    };
    //Premium Counter Handler
    var PremiumCounterHandler = function($scope,$){
        var counterElement = $scope.find('.premium-counter').each(function(){
        var counterSettings = $(this).data('settings');

        var counter_offset = $(this).offset().top;
        var counter = new CountUp(
            'counter-' + counterSettings['id'],
            0,
            counterSettings['value'],
            counterSettings['d_after'],
            counterSettings['speed'],
            {
                useEasing: true,
                separator: counterSettings['separator'],
                decimal: counterSettings['decimal']
            }
        );
        if(counter_offset < $(window).outerHeight() - 150) {
            counter.start();
        }
        function start_counter(){
            if($(window).scrollTop() >  counter_offset - 600 ) {
                counter.start();
            }
        }
        function isScrolledIntoView(elem) {
            var docViewTop = $(window).scrollTop();
            var docViewBottom = docViewTop + $(window).height();
            var elemTop = elem.offset().top;
            var elemBottom = elemTop + elem.height();
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }
        function addAnimation() {
            $('.premium-counter-init').each( function() {
                var $this = $(this),
                parentId = $this.parents('.premium-counter-area').attr('id'),
                iconClass = $('#' + parentId ).find('.icon'),
                animation = iconClass.data('animation');
                if( iconClass.length ) {
                    if( isScrolledIntoView( iconClass ) ) {
                        if( ! iconClass.hasClass('animated') ) {            
                            $('#' + parentId ).find('.icon').addClass('animated ' + animation );
                                }
                            }
                        }
                    });
                }
            addAnimation();
            $(document).ready(function(){
                $(window).on('scroll', function() {
                    addAnimation();
                    start_counter();
                });
            });
        });        
    };
    //Premium Fancy Text Handler
    var PremiumFancyTextHandler = function($scope,$){
        var fancyTextElement = $scope.find('.premium-fancy-text-wrapper');
        var fancyTextSettings = fancyTextElement.data('settings');
        if(fancyTextSettings['effect'] === 'typing'){
            fancyTextElement.find('.premium-fancy-text').typed( {
                strings: fancyTextSettings['strings'],
                typeSpeed: fancyTextSettings['typeSpeed'],
                backSpeed: fancyTextSettings['backSpeed'],
                startDelay:fancyTextSettings['startDelay'],
                backDelay: fancyTextSettings['backDelay'],
                showCursor:fancyTextSettings['showCursor'],
                cursorChar:fancyTextSettings['cursorChar'],
                loop: fancyTextSettings['loop']
                } );
        } else {
            fancyTextElement.find('.premium-fancy-text').vTicker( {
                speed: fancyTextSettings['speed'],
                showItems: fancyTextSettings['showItems'],
                pause: fancyTextSettings['pause'],
                mousePause : fancyTextSettings['mousePause'],
                direction: "up"
            });
        }
    };
    //Premium Countdown Handler
    var PremiumCountDownHandler = function ($scope,$){
        var countDownElement = $scope.find('.premium-countdown').each(function(){
            var countDownSettings = $(this).data('settings');
            var label1 = countDownSettings['label1'],
                label2 = countDownSettings['label2'],
                newLabe1 = label1.split(','),
                newLabe2 = label2.split(',');
                if(countDownSettings['event'] === 'onExpiry'){
                    $(this).find('.premium-countdown-init').pre_countdown({
                        labels 		: newLabe2,
                        labels1 	: newLabe1,
                        until 		: new Date( countDownSettings['until'] ),
                        format 		: countDownSettings['format'],
                        padZeroes	: true,
                        onExpiry    : function() {
                            $(this).html(countDownSettings['text']);
                        },
                        serverSync : function() { return new Date(countDownSettings['serverSync']); }
                    });
                } else if(countDownSettings['event'] === 'expiryUrl') {
                    $(this).find('.premium-countdown-init').pre_countdown({
                        labels 		: newLabe2,
                        labels1 	: newLabe1,
                        until 		: new Date( countDownSettings['until'] ),
                        format 		: countDownSettings['format'],
                        padZeroes	: true,
                        expiryUrl   : countDownSettings['text'],
                        serverSync : function() { return new Date(countDownSettings['serverSync']); }
                    });
                }
                times = $(this).find('.premium-countdown-init').pre_countdown('getTimes');
                function runTimer( el ) {
                    return el == 0;
                    }
                if( times.every( runTimer ) ) {
                    if( countDownSettings['event'] === 'onExpiry' ){
                        $(this).find('.premium-countdown-init').html(countDownSettings['text']);
                    }
                    if( countDownSettings['event'] === 'expiryUrl' ){
                        var editMode = $('body').find('#elementor').length;
                        if( editMode > 0 ) {
                            $(this).find('.premium-countdown-init').html( '<h1>You can not redirect url from elementor Editor!!</h1>' );
                        } else {
                            window.location.href = countDownSettings['text'];
                        }
                    }				
                }
            });
        };
    //Premium Carousel Handler
    var PremiumCarouselHandler = function ($scope,$){
        var carouselElement = $scope.find('.premium-carousel-wrapper').each(function(){
            var carouselSettings = $(this).data('settings');
            function slideToShow( slick ) {
                slidesToShow = slick.options.slidesToShow;
                windowWidth = jQuery( window ).width();
                if ( windowWidth < 1025 ) {
                    slidesToShow = slick.options.responsive[0].settings.slidesToShow;
                }
                if ( windowWidth < 769 ) {
                    slidesToShow = slick.options.responsive[1].settings.slidesToShow;
                }
                if ( windowWidth < 481 ) {
                    slidesToShow = slick.options.responsive[2].settings.slidesToShow;
                }
                return slidesToShow;
            }
            $(this).on('init', function (event, slick ) {
                event.preventDefault();
                $(this).find('item-wrapper.slick-active').each(function (index, el) {
                    $this = $(this);
                    $this.addClass($this.data('animation'));
                });
                $('.slick-track').addClass('translate');
                });
            $(this).find('.premium-carousel-inner').slick({
                vertical        : carouselSettings['vertical'],
                slidesToScroll  : carouselSettings['slidesToScroll'],
                slidesToShow    : carouselSettings['slidesToShow'],
                responsive      : carouselSettings['responsive'],
                infinite        : carouselSettings['infinite'],
                speed           : carouselSettings['speed'],
                autoplay        : carouselSettings['autoplay'],
                autoplaySpeed   : carouselSettings['autoplaySpeed'],
                draggable       : carouselSettings['draggable'],
                touchMove       : carouselSettings['touchMove'],
                rtl             : carouselSettings['rtl'],
                adaptiveHeight  : carouselSettings['adaptiveHeight'],
                pauseOnHover    : carouselSettings['pauseOnHover'],
                centerMode      : carouselSettings['centerMode'],
                centerPadding   : carouselSettings['centerPadding'],
                arrows          : carouselSettings['arrows'],
                nextArrow       : carouselSettings['nextArrow'],
                prevArrow       : carouselSettings['prevArrow'],
                dots            : carouselSettings['dots'],
                customPaging    : function(slider, i) {return '<i class="' + carouselSettings['customPaging'] + '"></i>';}, 
            });
            $(this).on('afterChange', function (event, slick, currentSlide, nextSlide) {
                slidesScrolled = slick.options.slidesToScroll;
                slidesToShow = slideToShow( slick );
                centerMode = slick.options.centerMode;
                $currentParent = slick.$slider[0].parentElement.id;
                slideToAnimate = currentSlide + slidesToShow - 1;
                if (slidesScrolled == 1) {
                    if ( centerMode == true ) {
                        animate = slideToAnimate - 2;
                        $inViewPort = $( '#' + $currentParent + " [data-slick-index='" + animate + "']");
                        $inViewPort.addClass($inViewPort.data('animation'));
                    } else {
                        $inViewPort = $( '#' + $currentParent + " [data-slick-index='" + slideToAnimate + "']");
                        $inViewPort.addClass($inViewPort.data('animation'));
                        }
                    } else {
                        for (var i = slidesScrolled + currentSlide; i >= 0; i--) {
                        $inViewPort = $( '#' + $currentParent + " [data-slick-index='" + i + "']");
                        $inViewPort.addClass($inViewPort.data('animation'));
                    }
                }
            });
            $(this).on('beforeChange', function (event, slick, currentSlide) {
                    $inViewPort = $("[data-slick-index='" + currentSlide + "']");
                    $inViewPort.siblings().removeClass($inViewPort.data('animation'));
            });
            if( carouselSettings['vertical']) {
                var maxHeight = -1;
                    $('.slick-slide').each(function() {
                        if ($(this).height() > maxHeight) {
						    maxHeight = $(this).height();
                        }
                    });
                    $('.slick-slide').each(function() {
                        if ($(this).height() < maxHeight) {
						    $(this).css('margin', Math.ceil((maxHeight-$(this).height())/2) + 'px 0');
                        }
                    });
                }
                var marginFix = {
                    element : $('a.ver-carousel-arrow'),
                    getWidth :  function() {
                        var width = this.element.outerWidth();
                        return width / 2;
                    },
                    setWidth : function( type = 'vertical') {
                        if( type == 'vertical' ) {
                            this.element.css( 'margin-left', '-' + this.getWidth() + 'px' );
                        } else {
                            this.element.css( 'margin-top', '-' + this.getWidth() + 'px' );
                        }
                    }
                }
                marginFix.setWidth();
                marginFix.element = $('a.carousel-arrow');
                marginFix.setWidth('horizontal');
        });   
    };
    //Premium Banner Handler
    var PremiumBannerHandler = function ($scope,$){
        var bannerElement = $scope.find('.premium_banner');
        bannerElement.find('.premium_addons-banner-ib').hover(
            function() {
                $(this).find('.premium_addons-banner-ib-img').addClass('active');
            },
            function() {
                $(this).find('.premium_addons-banner-ib-img').removeClass('active');
            });
    };
    //Premium Modal Box Handler
    var PremiumModalBoxHandler = function ($scope,$){
        var modalBoxElement = $scope.find('.premium-modal-box-container');
        var modalBoxSettings = modalBoxElement.data('settings');
        if(modalBoxSettings['trigger'] === 'pageload'){
            $(document).ready(function($){
                  setTimeout( function(){
                      modalBoxElement.find('.premium-modal-box-modal').modal();
                  }, modalBoxSettings['delay'] * 1000);
                });
            }
        };
    //Premium Maps Handler
    var PremiumMapsHandler = function($scope,$){
        var mapElement = $scope.find('.premium_maps_map_height');
        var mapSettings = mapElement.data('settings');
        var mapStyle = mapElement.data('style');
        premiumMap = newMap(mapElement,mapSettings,mapStyle);
        function newMap(map,settings,mapStyle){
            var scrollwheel = JSON.parse(settings['scrollwheel']);
            var streetViewControl = JSON.parse(settings['streetViewControl']);
            var fullscreenControl = JSON.parse(settings['fullScreen']);
            var zoomControl = JSON.parse(settings['zoomControl']);
            var mapTypeControl = JSON.parse(settings['typeControl']);
            var centerLat = JSON.parse(settings['centerlat']);
            var centerLong = JSON.parse(settings['centerlong']);
            var autoOpen = JSON.parse(settings['automaticOpen']);
            var hoverOpen = JSON.parse(settings['hoverOpen']);
            var hoverClose = JSON.parse(settings['hoverClose']);
            var args = {
                zoom: settings['zoom'],
                mapTypeId: settings['maptype'],
                center: {lat: centerLat, lng: centerLong},
                scrollwheel: scrollwheel,
                streetViewControl: streetViewControl,
                fullscreenControl: fullscreenControl,
                zoomControl: zoomControl,
                mapTypeControl: mapTypeControl,
                styles: mapStyle
            };
            var markers = map.find(".premium-pin");
            var map = new google.maps.Map( map[0], args);
            map.markers = [];
            // add markers
            markers.each(function(){
                add_marker( jQuery(this), map, autoOpen, hoverOpen, hoverClose );
            });
            return map;
        }
        function add_marker( pin, map ,autoOpen, hoverOpen, hoverClose ) {
            var latlng = new google.maps.LatLng( pin.attr('data-lat'), pin.attr('data-lng') );

            icon_img = pin.attr('data-icon');
            if(icon_img != ''){
                var icon = {
                    url : pin.attr('data-icon')
                };
            }

            // create marker
            var marker = new google.maps.Marker({
                position	: latlng,
                map			: map,
                icon        : icon
            });

            // add to array
            map.markers.push( marker );

            // if marker contains HTML, add it to an infoWindow

            if( pin.find('.premium-maps-info-title').html() || pin.find('.premium-maps-info-desc').html() )
            {
                // create info window
                var infowindow = new google.maps.InfoWindow({
                    content		: pin.html()
                });
                if(autoOpen){
                    infowindow.open( map, marker );
                }
                if(hoverOpen){
                    google.maps.event.addListener(marker, 'mouseover', function() {
                        infowindow.open( map, marker );
                    });
                    if(hoverClose){
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close( map, marker );
                        });
                    }
                }
                // show info window when marker is clicked
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open( map, marker );
                });
            }
        }
    };
        
    //Elementor JS Hooks
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-video-box.default',PremiumVideoBoxWidgetHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-img-gallery.default',PremiumGridWidgetHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-fancy-text.default',PremiumFancyTextHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-counter.default',PremiumCounterHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-countdown-timer.default',PremiumCountDownHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-carousel-widget.default',PremiumCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-banner.default',PremiumBannerHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-modal-box.default',PremiumModalBoxHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-maps.default',PremiumMapsHandler);
        if(elementorFrontend.isEditMode()){
            elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-progressbar.default', PremiumProgressBarWidgetHandler);
        } else {
            elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-progressbar.default', PremiumProgressBarScrollWidgetHandler);
        }
    });
})(jQuery);