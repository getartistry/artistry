(function ($) {

    /* ----------------- Accordion ------------------ */

    var LAE_Accordion = function ($scope) {

        this.accordion = $scope.find('.lae-accordion').eq(0);
        // toggle elems
        this.panels = this.accordion.find('.lae-panel');

        if (this.accordion.data('toggle') == true)
            this.toggle = true;

        if (this.accordion.data('expanded') == true)
            this.expanded = true;

        // init events
        this._init();
    };

    LAE_Accordion.prototype = {

        accordion: null,
        panels: null,
        toggle: false,
        expanded: false,
        current: null,

        _init: function () {

            var self = this;

            if (this.expanded && this.toggle) {

                // Display all panels
                this.panels.each(function () {

                    var $panel = jQuery(this);

                    self._show($panel);

                });
            }

            this.panels.find('.lae-panel-title').click(function (event) {

                event.preventDefault();

                var $panel = jQuery(this).parent();

                // Do not disturb existing location URL if you are going to close an accordion panel that is currently open
                if (!$panel.hasClass('lae-active')) {

                    var target = $panel.attr("id");

                    history.pushState ? history.pushState(null, null, "#" + target) : window.location.hash = "#" + target;

                }
                else {
                    var target = $panel.attr("id");

                    if (window.location.hash == '#' + target)
                        history.pushState ? history.pushState(null, null, '#') : window.location.hash = "#";
                }

                self._show($panel);
            });
        },

        _show: function ($panel) {

            if (this.toggle) {
                if ($panel.hasClass('lae-active')) {
                    this._close($panel);
                }
                else {
                    this._open($panel);
                }
            }
            else {
                // if the $panel is already open, close it else open it after closing existing one
                if ($panel.hasClass('lae-active')) {
                    this._close($panel);
                    this.current = null;
                }
                else {
                    this._close(this.current);
                    this._open($panel);
                    this.current = $panel;
                }
            }

        },

        _open: function ($panel) {

            if ($panel !== null) {
                $panel.children('.lae-panel-content').slideDown(300);
                $panel.addClass('lae-active');
            }

        },

        _close: function ($panel) {

            if ($panel !== null) {
                $panel.children('.lae-panel-content').slideUp(300);
                $panel.removeClass('lae-active');
            }

        },
    };

    /* ------------------------------- Tabs ------------------------------------------- */

    /* Credit for tab styles - http://tympanus.net/codrops/2014/09/02/tab-styles-inspiration/ */

    var LAE_Tabs = function ($scope) {

        this.tabs = $scope.find('.lae-tabs').eq(0);

        this._init();
    };

    LAE_Tabs.prototype = {

        tabs: null,
        tabNavs: null,
        items: null,

        _init: function () {

            // tabs elems
            this.tabNavs = this.tabs.find('.lae-tab');

            // content items
            this.items = this.tabs.find('.lae-tab-pane');

            // show first tab item
            this._show(0);

            // init events
            this._initEvents();

            // make the tab responsive
            this._makeResponsive();

        },

        _show: function (index) {

            // Clear out existing tab
            this.tabNavs.removeClass('lae-active');
            this.items.removeClass('lae-active');

            this.tabNavs.eq(index).addClass('lae-active');
            this.items.eq(index).addClass('lae-active');

            this._triggerResize();

        },

        _initEvents: function ($panel) {

            var self = this;

            this.tabNavs.click(function (event) {

                event.preventDefault();

                var $anchor = jQuery(this).children('a').eq(0);

                var target = $anchor.attr('href').split('#').pop();

                self._show(self.tabNavs.index(jQuery(this)));

                history.pushState ? history.pushState(null, null, "#" + target) : window.location.hash = "#" + target;

            });
        },

        _makeResponsive: function () {

            var self = this;

            /* Trigger mobile layout based on an user chosen browser window resolution */
            var mediaQuery = window.matchMedia('(max-width: ' + self.tabs.data('mobile-width') + 'px)');
            if (mediaQuery.matches) {
                self.tabs.addClass('lae-mobile-layout');
            }
            mediaQuery.addListener(function (mediaQuery) {
                if (mediaQuery.matches)
                    self.tabs.addClass('lae-mobile-layout');
                else
                    self.tabs.removeClass('lae-mobile-layout');
            });

            /* Close/open the mobile menu when a tab is clicked and when menu button is clicked */
            this.tabNavs.click(function (event) {
                event.preventDefault();
                self.tabs.toggleClass('lae-mobile-open');
            });

            this.tabs.find('.lae-tab-mobile-menu').click(function (event) {
                event.preventDefault();
                self.tabs.toggleClass('lae-mobile-open');
            });
        },

        _triggerResize: function () {

            if(typeof(Event) === 'function') {
                // modern browsers
                window.dispatchEvent(new Event('resize'));
            }else{
                // for IE and other old browsers
                // causes deprecation warning on modern browsers
                var evt = window.document.createEvent('UIEvents');
                evt.initUIEvent('resize', true, false, window, 0);
                window.dispatchEvent(evt);
            }
        }
    };

    var WidgetLAEAccordionHandler = function ($scope, $) {

        new LAE_Accordion($scope);

    };

    var WidgetLAETabsHandler = function ($scope, $) {

        new LAE_Tabs($scope);

    };

    var WidgetLAETestimonialsSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.lae-testimonials-slider').eq(0);

        var settings = slider_elem.data('settings');

        slider_elem.flexslider({
            selector: ".lae-slides > .lae-slide",
            animation: settings['slide_animation'],
            direction: settings['direction'],
            slideshowSpeed: settings['slideshow_speed'],
            animationSpeed: settings['animation_speed'],
            namespace: "lae-flex-",
            pauseOnAction: settings['pause_on_action'],
            pauseOnHover: settings['pause_on_hover'],
            controlNav: settings['control_nav'],
            directionNav: settings['direction_nav'],
            prevText: "Previous<span></span>",
            nextText: "Next<span></span>",
            smoothHeight: false,
            animationLoop: true,
            slideshow: true,
            easing: "swing",
            controlsContainer: "lae-testimonials-slider"
        });


    };

    var WidgetLAEStatsBarHandler = function ($scope, $) {

        $scope.find('.lae-stats-bar-content').each(function () {

            var dataperc = $(this).data('perc');

            $(this).animate({"width": dataperc + "%"}, dataperc * 20);

        });

    };

    var WidgetLAEStatsBarHandlerOnScroll = function ($scope, $) {

        $scope.waypoint(function (direction) {

            WidgetLAEStatsBarHandler($(this.element), $);

        }, {
            offset: Waypoint.viewportHeight() - 150,
            triggerOnce: true
        });

    };

    var WidgetLAEPiechartsHandler = function ($scope, $) {

        $scope.find('.lae-piechart .lae-percentage').each(function () {

            var track_color = $(this).data('track-color');
            var bar_color = $(this).data('bar-color');

            $(this).easyPieChart({
                animate: 2000,
                lineWidth: 10,
                barColor: bar_color,
                trackColor: track_color,
                scaleColor: false,
                lineCap: 'square',
                size: 220

            });

        });

    };

    var WidgetLAEPiechartsHandlerOnScroll = function ($scope, $) {

        $scope.waypoint(function (direction) {

            WidgetLAEPiechartsHandler($(this.element), $);

        }, {
            offset: Waypoint.viewportHeight() - 100,
            triggerOnce: true
        });

    };

    var WidgetLAEOdometersHandler = function ($scope, $) {

        $scope.find('.lae-odometer .lae-number').each(function () {

            var odometer = $(this);

            setTimeout(function () {
                var data_stop = odometer.attr('data-stop');
                $(odometer).text(data_stop);

            }, 100);

        });

    };

    var WidgetLAEOdometersHandlerOnScroll = function ($scope, $) {

        $scope.waypoint(function (direction) {

            WidgetLAEOdometersHandler($(this.element), $);

        }, {
            offset: Waypoint.viewportHeight() - 100,
            triggerOnce: true
        });
    };

    var WidgetLAESliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.lae-slider').eq(0);

        var settings = slider_elem.data('settings');

        var $slider = slider_elem.find('.lae-flexslider');

        $slider.flexslider({
            selector: ".lae-slides > .lae-slide",
            animation: settings['slide_animation'],
            direction: settings['direction'],
            slideshowSpeed: settings['slideshow_speed'],
            animationSpeed: settings['animation_speed'],
            namespace: "lae-flex-",
            pauseOnAction: settings['pause_on_action'],
            pauseOnHover: settings['pause_on_hover'],
            controlNav: settings['control_nav'],
            directionNav: settings['direction_nav'],
            prevText: "Previous<span></span>",
            nextText: "Next<span></span>",
            smoothHeight: false,
            animationLoop: true,
            slideshow: settings['slideshow'],
            easing: "swing",
            randomize: settings['randomize'],
            animationLoop: settings['loop'],
            controlsContainer: "lae-slider"
        });


    };

    var WidgetLAEPortfolioHandler = function ($scope, $) {

        var $blockElem = $scope.find('.lae-block');

        if ($blockElem.find('.lae-module').length === 0) {
            return; // no items to display or load and hence don't continue
        }

        var currentBlockObj = laeGrids.getBlockObjById($blockElem.data('block-uid'));

        /* ----------- Init Isotope on Grid  -------------- */

        var layoutMode = currentBlockObj.settings['layout_mode'];

        // layout Isotope after all images have loaded
        var $blockElemInner = $blockElem.find('.lae-block-inner');

        $blockElemInner.isotope({
            itemSelector: '.lae-block-column',
            layoutMode: layoutMode,
            transitionDuration: '0.8s',
        });

        $blockElemInner.imagesLoaded(function () {
            $blockElemInner.isotope('layout');
        });

        /* ----------- Reorganize Filters when device width changes -------------- */

        /* https://stackoverflow.com/questions/24460808/efficient-way-of-using-window-resize-or-other-method-to-fire-jquery-functions */
        var laeResizeTimeout;

        $(window).resize(function () {

            if (!!laeResizeTimeout) {
                clearTimeout(laeResizeTimeout);
            }

            laeResizeTimeout = setTimeout(function () {

                currentBlockObj.organizeFilters();

            }, 200);
        });

        /* -------------- Taxonomy Filter --------------- */

        $scope.find('.lae-taxonomy-filter .lae-filter-item a, .lae-block-filter .lae-block-filter-item a').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handleFilterAction($(this));

            return false;
        });

        /* ------------------- Pagination ---------------------- */

        $scope.find('.lae-pagination a.lae-page-nav').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handlePageNavigation($(this));

        });

        /*---------------- Load More Button --------------------- */

        $scope.find('.lae-pagination a.lae-load-more').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handleLoadMore($(this));

        });

        /* ---------------------- Init Lightbox ---------------------- */

        currentBlockObj.initLightbox($blockElem);

    };


    var WidgetLAEPostsBlockHandler = function ($scope, $) {

        var $blockElem = $scope.find('.lae-block');

        if ($blockElem.find('.lae-module').length === 0) {
            return; // no items to display or load and hence don't continue
        }

        var currentBlockObj = laeBlocks.getBlockObjById($blockElem.data('block-uid'));

        /* ----------- Reorganize Filters when device width changes -------------- */

        /* https://stackoverflow.com/questions/24460808/efficient-way-of-using-window-resize-or-other-method-to-fire-jquery-functions */
        var laeResizeTimeout;

        $(window).resize(function () {

            if (!!laeResizeTimeout) {
                clearTimeout(laeResizeTimeout);
            }

            laeResizeTimeout = setTimeout(function () {

                currentBlockObj.organizeFilters();

            }, 200);
        });

        /* -------------- Taxonomy Filter --------------- */

        $scope.find('.lae-taxonomy-filter .lae-filter-item a, .lae-block-filter .lae-block-filter-item a').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handleFilterAction($(this));

            return false;
        });

        /* ------------------- Pagination ---------------------- */

        $scope.find('.lae-pagination a.lae-page-nav').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handlePageNavigation($(this));

        });

        /*---------------- Load More Button --------------------- */

        $scope.find('.lae-pagination a.lae-load-more').on('click', function (e) {

            e.preventDefault();

            currentBlockObj.handleLoadMore($(this));

        });

        /* ---------------------- Init Lightbox ---------------------- */

        currentBlockObj.initLightbox($scope);

    };

    var WidgetLAEImageSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.lae-image-slider').eq(0);

        var slider_type = slider_elem.data('slider-type');

        var settings = slider_elem.data('settings');

        var animation = settings['slide_animation'] || "slide";

        var direction = settings['direction'] || "horizontal";

        var slideshow_speed = parseInt(settings['slideshow_speed']) || 5000;

        var animation_speed = parseInt(settings['animation_speed']) || 600;

        var pause_on_action = settings['pause_on_action'];

        var pause_on_hover = settings['pause_on_hover'];

        var direction_nav = settings['direction_nav'];

        var control_nav = settings['control_nav'];

        var slideshow = settings['slideshow'];

        var slideshow = settings['slideshow'];

        var thumbnail_nav = settings['thumbnail_nav'];

        var randomize = settings['randomize'];

        var loop = settings['loop'];

        if (slider_type == 'flex') {

            var carousel_id, slider_id;

            var $parent_slider = slider_elem.find('.lae-flexslider');

            if (thumbnail_nav) {

                control_nav = false; // disable control nav if thumbnail slider is desired
                randomize = false; // thumbnail slider does not work right when randomize is enabled

                carousel_id = $parent_slider.attr('data-carousel');
                slider_id = $parent_slider.attr('id');

                jQuery('#' + carousel_id).flexslider({
                    selector: ".lae-slides > .lae-slide",
                    namespace: "lae-flex-",
                    animation: "slide",
                    controlNav: false,
                    animationLoop: true,
                    slideshow: false,
                    itemWidth: 120,
                    itemMargin: 5,
                    asNavFor: ('#' + slider_id)
                });
            }

            $parent_slider.flexslider({
                selector: ".lae-slides > .lae-slide",
                animation: animation,
                direction: direction,
                slideshowSpeed: slideshow_speed,
                animationSpeed: animation_speed,
                namespace: "lae-flex-",
                pauseOnAction: pause_on_action,
                pauseOnHover: pause_on_hover,
                controlNav: control_nav,
                directionNav: direction_nav,
                prevText: "Previous<span></span>",
                nextText: "Next<span></span>",
                smoothHeight: false,
                animationLoop: loop,
                slideshow: slideshow,
                easing: "swing",
                randomize: randomize,
                animationLoop: loop,
                sync: (carousel_id ? '#' + carousel_id : '')
            });
        }
        else if (slider_type == 'nivo') {

            // http://docs.dev7studios.com/article/13-nivo-slider-settings

            slider_elem.find('.nivoSlider').nivoSlider({
                effect: 'random',                 // Specify sets like: 'fold,fade,sliceDown'
                slices: 15,                       // For slice animations
                boxCols: 8,                       // For box animations
                boxRows: 4,                       // For box animations
                animSpeed: animation_speed,       // Slide transition speed
                pauseTime: slideshow_speed,       // How long each slide will show
                startSlide: 0,                    // Set starting Slide (0 index)
                directionNav: direction_nav,      // Next & Prev navigation
                controlNav: control_nav,          // 1,2,3... navigation
                controlNavThumbs: thumbnail_nav,  // Use thumbnails for Control Nav
                pauseOnHover: pause_on_hover,     // Stop animation while hovering
                manualAdvance: !slideshow,        // Force manual transitions
                prevText: 'Prev',                 // Prev directionNav text
                nextText: 'Next',                 // Next directionNav text
                randomStart: false,           // Start on a random slide
                beforeChange: function () {
                },       // Triggers before a slide transition
                afterChange: function () {
                },        // Triggers after a slide transition
                slideshowEnd: function () {
                },       // Triggers after all slides have been shown
                lastSlide: function () {
                },          // Triggers when last slide is shown
                afterLoad: function () {
                }           // Triggers when slider has loaded
            });
        }
        else if (slider_type == 'slick') {

            slider_elem.find('.lae-slickslider').slick({
                autoplay: slideshow, // Should the slider move by itself or only be triggered manually?
                speed: animation_speed, // How fast (in milliseconds) Slick Slider should animate between slides.
                autoplaySpeed: slideshow_speed, // If autoplay is set to true, how many milliseconds should pass between moving the slides?
                dots: control_nav, // Do you want to generate an automatic clickable navigation for each slide in your slider?
                arrows: direction_nav, // Do you want to add left/right arrows to your slider?
                fade: (animation == "fade"), // How should Slick Slider animate each slide?
                adaptiveHeight: false, // Should Slick Slider animate the height of the container to match the current slide's height?
                pauseOnHover: pause_on_hover, // Pause Autoplay on Hover
                slidesPerRow: 1, // With grid mode intialized via the rows option, this sets how many slides are in each grid row. dver
                slidesToShow: 1, // # of slides to show
                slidesToScroll: 1, // # of slides to scroll
                vertical: (direction == "vertical"), // Vertical slide mode
                infinite: loop, // Infinite loop sliding
                useTransform: true // Use CSS3 transforms

            });
        }
        else if (slider_type == 'responsive') {

            // http://responsiveslides.com/

            slider_elem.find('.rslides').responsiveSlides({
                auto: slideshow,             // Boolean: Animate automatically, true or false
                speed: animation_speed,            // Integer: Speed of the transition, in milliseconds
                timeout: slideshow_speed,          // Integer: Time between slide transitions, in milliseconds
                pager: control_nav,           // Boolean: Show pager, true or false
                nav: direction_nav,             // Boolean: Show navigation, true or false
                random: randomize,          // Boolean: Randomize the order of the slides, true or false
                pause: pause_on_hover,           // Boolean: Pause on hover, true or false
                pauseControls: false,    // Boolean: Pause when hovering controls, true or false
                prevText: "Previous",   // String: Text for the "previous" button
                nextText: "Next",       // String: Text for the "next" button
                maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
                navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
                manualControls: "",     // Selector: Declare custom pager navigation
                namespace: "rslides",   // String: Change the default namespace used
                before: function () {
                },   // Function: Before callback
                after: function () {
                }     // Function: After callback
            });
        }


    };

    var WidgetLAEIconListHandler = function ($scope, $) {


        $scope.find('.lae-icon-list-item').powerTip({
            placement: 'n' // north-east tooltip position
        });


    };

    var WidgetLAEGalleryCarouselHandler = function ($scope, $) {

        /* ----------------- Lightbox Support ------------------ */

        $scope.fancybox({
            selector: '.lae-gallery-carousel-item:not(.slick-cloned) a.lae-lightbox-item:not(.elementor-clickable),.lae-gallery-carousel-item:not(.slick-cloned) a.lae-video-lightbox', // the selector for gallery item
            loop: true,
            caption: function (instance, item) {

                var caption = $(this).attr('title') || '';

                var description = $(this).data('description') || '';

                if (description !== '') {
                    caption += '<div class="lae-fancybox-description">' + description + '</div>';
                }

                return caption;
            }
        });

    };


    var WidgetLAEGalleryHandler = function ($scope, $) {

        if ($().isotope === undefined) {
            return;
        }

        var container = $scope.find('.lae-gallery');
        if (container.length === 0) {
            return; // no items to filter or load and hence don't continue
        }

        // layout Isotope after all images have loaded
        var htmlContent = $scope.find('.js-isotope');

        var isotopeOptions = htmlContent.data('isotope-options');

        htmlContent.isotope({
            // options
            itemSelector: isotopeOptions['itemSelector'],
            layoutMode: isotopeOptions['layoutMode'],
            masonry: {
                columnWidth: '.lae-grid-sizer'
            }
        });

        htmlContent.imagesLoaded(function () {
            htmlContent.isotope('layout');
        });


        // Relayout on inline full screen video and back
        $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange',function(e){
            htmlContent.isotope('layout');
        });

        /* -------------- Taxonomy Filter --------------- */

        $scope.find('.lae-taxonomy-filter .lae-filter-item a').on('click', function (e) {
            e.preventDefault();

            var selector = $(this).attr('data-value');
            container.isotope({filter: selector});
            $(this).closest('.lae-taxonomy-filter').children().removeClass('lae-active');
            $(this).closest('.lae-filter-item').addClass('lae-active');
            return false;
        });

        /* ------------------- Pagination ---------------------- */

        $scope.find('.lae-pagination a.lae-page-nav').on('click', function (e) {
            e.preventDefault();

            var $this = $(this),
                $parent = $this.closest('.lae-gallery-wrap'),
                paged = $this.data('page'),
                settings = $parent.data('settings'),
                items = $parent.data('items'),
                maxpages = $parent.data('maxpages'),
                current = $parent.data('current');

            // Do not continue if already processing or if the page is currently being shown
            if ($this.is('.lae-current-page') || $parent.is('.lae-processing'))
                return;

            if (paged == 'prev') {
                if (current <= 1)
                    return;
                paged = current - 1;
            }
            else if (paged == 'next') {
                if (current >= maxpages)
                    return;
                paged = current + 1;
            }

            $parent.addClass('lae-processing');

            var data = {
                'action': 'lae_load_gallery_items',
                'settings': settings,
                'items': items,
                'paged': paged
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(lae_ajax_object.ajax_url, data, function (response) {

                var $grid = $parent.find('.lae-gallery');

                var $existing_items = $grid.children('.lae-gallery-item');

                $grid.isotope('remove', $existing_items);

                var $response = $('<div></div>').html(response);

                $response.imagesLoaded(function () {

                    var $new_items = $response.children('.lae-gallery-item');

                    $grid.isotope('insert', $new_items);
                });

                // Set attributes of DOM elements based on page loaded

                $parent.data('current', paged);

                $this.siblings('.lae-current-page').removeClass('lae-current-page');

                $parent.find('.lae-page-nav[data-page="' + parseInt(paged) + '"]').addClass('lae-current-page');

                $parent.find('.lae-page-nav[data-page="next"]').removeClass('lae-disabled');
                $parent.find('.lae-page-nav[data-page="prev"]').removeClass('lae-disabled');

                if (paged <= 1)
                    $parent.find('.lae-page-nav[data-page="prev"]').addClass('lae-disabled');
                else if (paged >= maxpages)
                    $parent.find('.lae-page-nav[data-page="next"]').addClass('lae-disabled');

                $parent.removeClass('lae-processing');
            });

        });


        /*---------------- Load More Button --------------------- */

        $scope.find('.lae-pagination a.lae-load-more').on('click', function (e) {
            e.preventDefault();

            var $this = $(this),
                $parent = $this.closest('.lae-gallery-wrap'),
                paged = $this.attr('data-page'),
                settings = $parent.data('settings'),
                items = $parent.data('items'),
                maxpages = $parent.data('maxpages'),
                current = $parent.data('current'),
                total = $parent.data('total');

            if (current >= maxpages || $parent.is('.lae-processing'))
                return;

            $parent.addClass('lae-processing');

            paged = ++current;

            var data = {
                'action': 'lae_load_gallery_items',
                'settings': settings,
                'items': items,
                'paged': paged
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(lae_ajax_object.ajax_url, data, function (response) {

                var $grid = $parent.find('.lae-gallery');

                var $response = $('<div></div>').html(response);

                $response.imagesLoaded(function () {

                    var $new_items = $response.children('.lae-gallery-item');

                    $grid.isotope('insert', $new_items);

                });

                $parent.data('current', current);

                // Set remaining posts to be loaded and hide the button if we just loaded the last page
                if (settings['show_remaining']) {
                    if (current == maxpages) {
                        $this.find('span').text(0);
                    }
                    else {
                        var remaining = total - (current * settings['items_per_page']);
                        $this.find('span').text(remaining);
                    }
                }

                if (current == maxpages)
                    $this.addClass('lae-disabled');

                $parent.removeClass('lae-processing');
            });

        });

        /* ----------------- Lightbox Support ------------------ */

        $scope.fancybox({
            selector: 'a.lae-lightbox-item:not(.elementor-clickable),a.lae-video-lightbox', // the selector for gallery item
            loop: true,
            caption: function (instance, item) {

                var caption = $(this).attr('title') || '';

                var description = $(this).data('description') || '';

                if (description !== '') {
                    caption += '<div class="lae-fancybox-description">' + description + '</div>';
                }

                return caption;
            }
        });
        
    };

    var WidgetLAECarouselHandler = function ($scope, $) {

        var carousel_elem = $scope.find('.lae-carousel, .lae-posts-carousel, .lae-gallery-carousel, .lae-services-carousel').eq(0);

        if (carousel_elem.length > 0) {

            var settings = carousel_elem.data('settings');

            var arrows = settings['arrows'];

            var dots = settings['dots'];

            var autoplay = settings['autoplay'];

            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;

            var animation_speed = parseInt(settings['animation_speed']) || 300;

            var fade = settings['fade'];

            var pause_on_hover = settings['pause_on_hover'];

            var display_columns = parseInt(settings['display_columns']) || 4;

            var scroll_columns = parseInt(settings['scroll_columns']) || 4;

            var tablet_width = parseInt(settings['tablet_width']) || 800;

            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;

            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;

            var mobile_width = parseInt(settings['mobile_width']) || 480;

            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;

            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            carousel_elem.slick({
                arrows: arrows,
                dots: dots,
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: false,
                pauseOnHover: pause_on_hover,
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        }

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-accordion.default', WidgetLAEAccordionHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-tabs.default', WidgetLAETabsHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-testimonials-slider.default', WidgetLAETestimonialsSliderHandler);

        if (elementorFrontend.isEditMode()) {

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-stats-bars.default', WidgetLAEStatsBarHandler);

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-piecharts.default', WidgetLAEPiechartsHandler);

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-odometers.default', WidgetLAEOdometersHandler);
        }
        else {

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-stats-bars.default', WidgetLAEStatsBarHandlerOnScroll);

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-piecharts.default', WidgetLAEPiechartsHandlerOnScroll);

            elementorFrontend.hooks.addAction('frontend/element_ready/lae-odometers.default', WidgetLAEOdometersHandlerOnScroll);
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-slider.default', WidgetLAESliderHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-portfolio.default', WidgetLAEPortfolioHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-posts-block.default', WidgetLAEPostsBlockHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-image-slider.default', WidgetLAEImageSliderHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-icon-list.default', WidgetLAEIconListHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-gallery-carousel.default', WidgetLAEGalleryCarouselHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-carousel.default', WidgetLAECarouselHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-services-carousel.default', WidgetLAECarouselHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-gallery-carousel.default', WidgetLAECarouselHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-posts-carousel.default', WidgetLAECarouselHandler);

        elementorFrontend.hooks.addAction('frontend/element_ready/lae-gallery.default', WidgetLAEGalleryHandler);

    });

})(jQuery);