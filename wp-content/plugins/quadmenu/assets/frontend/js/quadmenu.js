;
(function ($, window, document, undefined) {
    'use strict';
    var pluginName = 'quadmenu',
            defaults = {
                responsive: true,
                containerGutter: parseInt(quadmenu.gutter),
                touchEvents: true,
                mouseEvents: true,
                moveThreshold: 100, //distance until tap is cancelled in deference to move/scroll
                intent_delay: 100, //delay before the menu closes
                intent_interval: 150, //polling interval for mouse comparisons
                intent_threshold: 300, //maximum number of pixels mouse can move to be considered intent
            };
    function Plugin(element, options) {

        var plugin = this;
        this.element = element;
        this.$quadmenu = $(this.element);
        this.$ul = this.$quadmenu.find('ul.quadmenu-navbar-nav');
        this.settings = $.extend({}, defaults, options);
        this.touchenabled = ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0);
        this.mobiledevice = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
        this.settings.perfectScrollbar = (typeof $.fn.perfectScrollbar !== 'undefined' && !this.mobiledevice) ? true : false;
        this.$quadmenu.removeClass('no-js').addClass('js');

        if (this.touchenabled) {
            this.$quadmenu.addClass('quadmenu-touch');
        } else {
            this.$quadmenu.addClass('quadmenu-notouch');
        }

        if (window.navigator.pointerEnabled) {
            this.touchStart = 'pointerdown';
            this.touchEnd = 'pointerup';
            this.touchMove = 'pointermove';
        } else if (window.navigator.msPointerEnabled) {
            this.touchStart = 'MSPointerDown';
            this.touchEnd = 'MSPointerUp';
            this.touchMove = 'MSPointerMove';
        } else {
            this.touchStart = 'touchstart';
            this.touchEnd = 'touchend';
            this.touchMove = 'touchmove';
        }

        this.init();
    }

    Plugin.prototype = {
        init: function () {
            this.quadmenuResolveConflics();
            this.quadmenuInitClasses();
            this.quadmenuInitContainer();
            this.quadmenuInitScrollBar();
            //this.quadmenuInitNavbarVertical();
            this.quadmenuInitNavbarOffcanvas();
            this.quadmenuInitNavbarSticky();
            this.quadmenuInitNavbarSlideBar();
            this.quadmenuInitDropdownFloat();
            this.quadmenuInitDropdownTouchEvents();
            this.quadmenuInitDropdownMouseEvents();
            this.quadmenuInitDropdownRetractors();
            this.quadmenuInitDropdownCloseAll();
            this.quadmenuInitItemActive();
            this.quadmenuInitItemMega();
            this.quadmenuInitItemTabs();
            this.quadmenuInitItemCarousel();
            this.quadmenuInitItemLogIn();
            this.quadmenuInitItemSocial();
            this.quadmenuInitItemCart();
            this.quadmenuInitItemWidgets();
        },
        quadmenuResolveConflics: function () {

            if (this.$quadmenu.data('unwrap')) {

                this.$quadmenu.unwrap('nav').find('.quadmenu-item, .quadmenu-dropdown-toggle, .quadmenu-dropdown-menu, .quadmenu-dropdown-submenu').add(this.$quadmenu).removeAttr('style').unbind().off();

            }

        },
        quadmenuInitClasses: function (plugin) {

            plugin = plugin || this;
            plugin.handleClasses();
            $(window).resize($.debounce(300, function () {
                plugin.handleClasses();
            }));
        },
        handleClasses: function () {

            var responsive = this.settings.responsive && (window.innerWidth <= this.$quadmenu.data('breakpoint')) ? true : false;

            if (responsive && this.$quadmenu.hasClass('quadmenu-is-horizontal')) {
                this.$quadmenu.removeClass('quadmenu-is-horizontal').data('removed-class', 'quadmenu-is-horizontal');
            }

            if (responsive && this.$quadmenu.hasClass('quadmenu-is-vertical')) {
                this.$quadmenu.removeClass('quadmenu-is-vertical').data('removed-class', 'quadmenu-is-vertical');
            }

            if (!responsive && this.$quadmenu.data('removed-class')) {
                this.$quadmenu.addClass(this.$quadmenu.data('removed-class'));
            }
        },
        quadmenuInitDropdownTouchEvents: function (plugin) {

            plugin = plugin || this;
            if (!this.settings.touchEvents)
                return;
            this.$ul.on(this.touchStart, '.quadmenu-dropdown > .quadmenu-dropdown-toggle', function (e) {
                plugin.handleTouchEvents(e, this, plugin);
            });
            this.$ul.on('click', '.quadmenu-dropdown > .quadmenu-dropdown-toggle', function (e) {
                plugin.handleClicks(e, this);
            });
        },
        quadmenuInitDropdownMouseEvents: function (plugin) {

            plugin = plugin || this;
            plugin.handleDropdownMouseEvents();
            $(window).resize($.debounce(600, function () {
                plugin.handleDropdownMouseEvents();
            }));
        },
        handleDropdownMouseEvents: function (plugin) {

            plugin = plugin || this;
            this.$ul.find('.quadmenu-item').off('mouseleave.hoverIntent mouseenter.hoverIntent mousemove.hoverIntent');
            this.$ul.find('.quadmenu-item').removeProp('hoverIntent_t');
            this.$ul.find('.quadmenu-item').removeProp('hoverIntent_s');
            if (!this.settings.mouseEvents)
                return;
            this.$ul.on('click', '.quadmenu-item > .quadmenu-dropdown-toggle', function (e) {
                plugin.handleLink(e, this);
            });
            this.$ul.on('click.quadmenu.toggle', '.quadmenu-item > .quadmenu-dropdown-toggle', function (e) {
                plugin.handleMouseClick(e, this, plugin);
            });
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal') || typeof $.fn.hoverIntent === 'undefined')
                return;
            plugin.handleMouseHover(this.$ul.find('.quadmenu-item > .quadmenu-dropdown-toggle.hoverintent'), plugin);
        },
        handleMouseHover: function (target, plugin) {

            var $target = $(target),
                    $li = $target.parent('.quadmenu-item');
            $target.off('click.quadmenu.toggle');

            if (!$li.size())
                return;
            $li.hoverIntent({
                over: function () {

                    var $li = $(this),
                            $target = $li.find('> .quadmenu-dropdown-menu');
                    if ($target.data('quadmenu-killHover'))
                        return;
                    plugin.triggerSubmenu($li, plugin);
                },
                out: function () {

                    var $li = $(this),
                            $target = $li.find('> .quadmenu-dropdown-menu');
                    if ($target.data('quadmenu-killHover'))
                        return;
                    if ($li.hasClass('quadmenu-item-type-tab'))
                        return;
                    plugin.closeSubmenu($li);
                },
                sensitivity: plugin.settings.intent_interval,
                timeout: plugin.settings.intent_delay,
                interval: plugin.settings.intent_threshold
            });
        },
        quadmenuInitDropdownRetractors: function (plugin) {

            plugin = plugin || this;

            this.$ul.on('click.retractor', '.quadmenu-item.quadmenu-dropdown.open > .quadmenu-dropdown-toggle > .quadmenu-item-content > .quadmenu-caret', function (e) {
                plugin.handleDropdownCloseEnd(e, this, plugin);
            });
            // we dont need to off this evet on window resize because touch events hare handled by the browser
            if (!this.settings.touchEvents)
                return;
            this.$ul.on(this.touchStart + '.retractor', '.quadmenu-item.quadmenu-dropdown.open > .quadmenu-dropdown-toggle > .quadmenu-item-content > .quadmenu-caret', function (e) {
                plugin.handleDropdownCloseStart(e, this, plugin);
            });
        },
        handleTouchEvents: function (e, target, plugin) {
            e.stopPropagation();
            var $target = $(target),
                    $li = $target.parent('.quadmenu-item');
            //disable hoverintent on touch devices
            $li.off('mouseleave.hoverIntent');
            $target.on(plugin.touchEnd, function (e) {
                plugin.handleTouchTap(e, this, plugin);
            });
            $target.on(plugin.touchMove, function (e) {
                plugin.preventTapOnScroll(e, this, plugin);
            });
            if (e.originalEvent.touches) {
                $target.data('quadmenu-startX', e.originalEvent.touches[0].clientX);
                $target.data('quadmenu-startY', e.originalEvent.touches[0].clientY);
            }
            //microsoft
            else if (e.originalEvent.clientY) {
                var pos = $target.offset();
                $target.data('quadmenu-startX', e.originalEvent.clientX);
                $target.data('quadmenu-startY', e.originalEvent.clientY);
            }

        },
        preventTapOnScroll: function (e, target, plugin) {

            var $target = $(target);
            //make sure the touch points aren't too close, if so, then remove the event handlers
            if (e.originalEvent.touches) {
                if (Math.abs(e.originalEvent.touches[0].clientX - $target.data('quadmenu-startX')) > plugin.settings.moveThreshold ||
                        Math.abs(e.originalEvent.touches[0].clientY - $target.data('quadmenu-startY')) > plugin.settings.moveThreshold) {

                    plugin.resetHandlers($target);
                }
            } else if (e.originalEvent.clientY) {
                var pos = $target.data(pos);
                if (Math.abs(e.originalEvent.clientX - $target.data('quadmenu-startX')) > plugin.settings.moveThreshold ||
                        Math.abs(e.originalEvent.clientY - $target.data('quadmenu-startY')) > plugin.settings.moveThreshold) {

                    plugin.resetHandlers($target);
                }
            }

        },
        handleTouchTap: function (e, target, plugin) {

            e.preventDefault();
            e.stopPropagation();
            var $target = $(target),
                    $li = $target.parent();
            //prevent clicks
            $target.data('quadmenu-killClick', true);
            //prevent hover
            $target.data('quadmenu-killHover', true);
            setTimeout(function () {
                $target.data('quadmenu-killClick', false).data('quadmenu-killHover', false);
            }, 1000);
            //close other submenus
            if (this.$quadmenu.hasClass('quadmenu-is-horizontal')) {
                plugin.closeSubmenu($li.siblings('.open'));
            }

            if ($li.hasClass('quadmenu-dropdown')) {
                //if submenu is already open then close it
                if ($li.hasClass('open')) {
                    //please don't close tabs
                    if (!$li.hasClass('quadmenu-item-type-tab')) {
                        plugin.closeSubmenu($li);
                    }
                    //allow link to be followed
                    plugin.handleLink(e, target, true);
                }
                //if submenu is closed then open the submenu and prevent link from being followed
                else {
                    plugin.openSubmenu($li);
                }
            }
            //allow links whitout submenus to be followed
            else {
                plugin.handleLink(e, target, true);
            }

            plugin.resetHandlers($target);
        },
        handleLink: function (e, link, follow) {

            follow = follow || false;
            var $link = $(link),
                    href = $link.attr('href');
            if (!$link.is('a'))
                return;
            if (!href) {
                e.preventDefault();
                return;
            }

            if (!follow || !e.isDefaultPrevented())
                return;
            //handle links after preventDefault
            if ($link.attr('target') === '_blank') {
                window.open(href, '_blank');
            } else {
                window.location = href;
            }

        },
        handleMouseClick: function (e, target, plugin) {

            var $target = $(target),
                    $li = $target.parent('.quadmenu-item');
            if ($target.data('quadmenu-killClick') || !$li.size())
                return;
            //disable hoverintent on click, just in case
            //$li.off('mousemove.hoverIntent');
            //$li.off('mouseenter.hoverIntent');
            //$li.off('mouseleave.hoverIntent');
            if ($li.hasClass('open')) {

                if ($target.is('a')) {
                    plugin.handleLink(e, target);
                }

            } else if ($li.hasClass('quadmenu-dropdown')) {
                e.preventDefault();
                //close other submenus
                if (this.$quadmenu.hasClass('quadmenu-is-horizontal')) {
                    plugin.closeSubmenu($li.siblings('.open'));
                }
                plugin.openSubmenu($li);
            }

        },
        handleDropdownCloseStart: function (e, caret, plugin) {
            e.preventDefault();
            e.stopPropagation();
            $(caret).on(plugin.touchEnd, function (e) {
                plugin.handleDropdownCloseEnd(e, this, plugin);
            });
        },
        handleDropdownCloseEnd: function (e, caret, plugin) {
            e.preventDefault();
            e.stopPropagation();

            var $li = $(caret).closest('.quadmenu-dropdown.open');
            plugin.closeSubmenu($li);
            $(caret).off(plugin.touchEnd);
            return false;
        },
        resetHandlers: function ($target) {

            $target.off(this.touchEnd);
            $target.off(this.touchMove);
            var $li = $target.parent();
            $li.off('mousemove.hoverIntent');
            //$li.off('mouseenter.hoverIntent');
            $li.off('mouseleave.hoverIntent');
            $li.removeProp('hoverIntent_t');
            $li.removeProp('hoverIntent_s');
        },
        triggerSubmenu: function ($li, plugin) {
            plugin.closeSubmenu($li.siblings('.open'));
            plugin.openSubmenu($li);
        },
        openSubmenu: function ($li, timeout) {

            if ($li.hasClass('open'))
                return;
            timeout = timeout || 200;
            $li.trigger('show.quadmenu.dropdown');
            $li.addClass('opening');
            setTimeout(function () {
                $li.addClass('open');
                $li.removeClass('opening');
                $li.trigger('shown.quadmenu.dropdown');
            }, 200);
        },
        closeSubmenu: function ($li, timeout) {

            if (!$li.hasClass('open'))
                return;

            timeout = timeout || 400;

            $li.trigger('hide.quadmenu.dropdown');

            $li.addClass('closing');

            setTimeout(function () {
                $li.find('.quadmenu-item').removeClass('open');
                $li.removeClass('open').removeClass('closing');
                $li.trigger('hidden.quadmenu.dropdown');
            }, timeout);

        },
        handleClicks: function (e, target) {

            var $target = $(target);
            if ($target.data('quadmenu-killClick')) {
                e.preventDefault();
            }
        },
        quadmenuInitDropdownCloseAll: function (plugin) {

            plugin = plugin || this;

            $(window).resize($.debounce(300, function () {
                plugin.closeAllSubmenus();
            }));

            $(document).on(this.touchEnd + '.hidden.quadmenu.dropdown.all click.hidden.quadmenu.dropdown.all', function (e) {
                plugin.handleDropdownCloseAll(e, plugin);
            });

        },
        handleDropdownCloseAll: function (e, plugin) {

            if ($(e.target).closest('#quadmenu').length)
                return;

            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;

            plugin.closeAllSubmenus();
        },
        closeAllSubmenus: function () {

            var $li = this.$ul.find('.quadmenu-item.open');
            if (!$li.length)
                return;
            this.closeSubmenu($li, 100);
            //this.trigger('hidden.quadmenu.dropdown.all');

            return;
        },
        quadmenuInitItemActive: function () {
            this.$ul.find('.quadmenu-current-menu-item:not(.quadmenu-item-type-tab)').addClass('active').first().parents('.quadmenu-item:not(.quadmenu-item-type-tab)').addClass('active');
        },
        quadmenuInitNavbarSticky: function () {

            var plugin = this;

            plugin.$sticky = this.$quadmenu.filter('[data-sticky="1"]').first();

            if (!plugin.$sticky.length || typeof $.fn.scrollTop === 'undefined')
                return;

            plugin.is_sticky = false;

            plugin.sticky_height = plugin.$sticky.height();

            var lastScrollTop = 0;

            $(window).on('scroll', function () {

                var ScrollTop = $(this).scrollTop();

                if (!plugin.is_sticky && (ScrollTop < lastScrollTop) && (ScrollTop > plugin.sticky_height)) {
                    plugin.handleSticky();
                }

                if (plugin.is_sticky && (ScrollTop < plugin.sticky_height)) {
                    plugin.handleUnSticky();
                }

                if (plugin.is_sticky && (ScrollTop > lastScrollTop)) {

                    plugin.handleUnSticking();

                    setTimeout(function () {
                        plugin.handleUnSticky();
                    }, 200);
                }

                lastScrollTop = ScrollTop;
            });
        },
        handleSticky: function () {

            var plugin = this;
            plugin.is_sticky = true;
            var placeholder = $('<div />').addClass('quadmenu-sticky-wrapper').css({
                'height': plugin.sticky_height + 'px',
                'position': 'static'
            });
            plugin.$sticky.find('.quadmenu-navbar-collapse.collapse.in').collapse('hide');
            plugin.$sticky.toggleClass('quadmenu-sticky-top').wrap(placeholder);
            plugin.$sticky.trigger('sticking.quadmenu.navbar');
            setTimeout(function () {
                plugin.$sticky.trigger('sticky.quadmenu.navbar');
            }, 200);
        },
        handleUnSticking: function () {

            var plugin = this;
            plugin.is_sticky = false;

            plugin.$sticky.addClass('quadmenu-unsticking-top');

        },
        handleUnSticky: function () {

            var plugin = this;
            plugin.is_sticky = false;

            plugin.$sticky.trigger('unsticking.quadmenu.navbar');
            plugin.$sticky.removeClass('quadmenu-unsticking-top');
            plugin.$sticky.removeClass('quadmenu-sticky-top');
            plugin.$sticky.unwrap();
            plugin.$sticky.trigger('unsticky.quadmenu.navbar');
        },
        quadmenuInitDropdownFloat: function (plugin) {

            plugin = plugin || this;
            plugin.handleDropdownFloat();
            $(window).resize($.debounce(600, function () {
                plugin.handleDropdownFloat();
            }));
        },
        handleDropdownFloat: function (plugin) {

            plugin = plugin || this;
            this.$ul.off('shown.quadmenu.dropdown.float');
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;
            this.$ul.on('shown.quadmenu.dropdown.float', '.quadmenu-item.quadmenu-dropdown:not(.quadmenu-dropdown-full):not(.quadmenu-item-type-tab):not(.quadmenu-item-type-tabs)', function (e) {

                e.stopPropagation();
                var $dropdown = $(this).find('> .quadmenu-dropdown-menu');
                if (!$dropdown.length)
                    return;
                var elH = $dropdown.outerWidth(),
                        W = $(window).innerWidth(),
                        p = $dropdown.offset(),
                        l = p.left,
                        r = W - (l + elH);
                if ($(this).hasClass('quadmenu-dropdown-left') && l < 0) {

                    $dropdown.css({'margin-right': l - plugin.settings.containerGutter + 'px'});
                } else if (r < 0) {

                    $dropdown.css({'margin-left': r - plugin.settings.containerGutter + 'px'});
                }

            });
        },
        quadmenuInitContainer: function (plugin) {

            plugin = plugin || this;
            plugin.handleContainerWidth(plugin.$quadmenu);
            $(window).resize($.debounce(600, function () {
                plugin.handleContainerWidth(plugin.$quadmenu);
            }));
            this.$quadmenu.on('sticking.quadmenu.navbar unsticking.quadmenu.navbar', function () {
                plugin.handleContainerWidth($(this));
            });
        },
        handleContainerWidth: function (navbar) {

            var $navbar = $(navbar),
                    $container = $navbar.find('.quadmenu-container');

            if ($(this.$quadmenu.data('width')).length) {

                var margin_left = parseInt($navbar.css('margin-left'), 10),
                        //margin_right = parseInt($navbar.css('margin-right'), 10),
                        offset = 0 - $navbar.offset().left - margin_left,
                        width = $(window).width();

                $navbar.css({
                    position: 'relative',
                    'box-sizing': 'border-box',
                    left: offset,
                    width: width
                });
            }

            if ($(this.$quadmenu.data('selector')).length) {
                $container.css({'width': $(this.$quadmenu.data('selector')).innerWidth() + 'px'});
            }



        },
        quadmenuInitNavbarVertical: function (plugin) {

            plugin = plugin || this;

            if (!this.$quadmenu.hasClass('quadmenu-is-vertical'))
                return;

            plugin.handleNavbarVertical();

            $(window).resize($.debounce(600, function () {
                plugin.handleNavbarVertical();
            }));

        },
        handleNavbarVertical: function (plugin) {

            plugin = plugin || this;

            if (this.$quadmenu.find('.navbar-offcanvas').hasClass('in'))
                return;

            setTimeout(function () {
                plugin.closeAllSubmenus();
                plugin.$quadmenu.find('[data-quadmenu=offcanvas]').trigger('click');
            }, 500);

        },
        quadmenuInitNavbarOffcanvas: function (plugin) {

            plugin = plugin || this;

            //if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
            // return;

            plugin.$quadmenu.on('show.quadmenu.offcanvas', function (e) {
                $(this).addClass('quadmenu-is-offcanvas');
            });

            plugin.$quadmenu.on('hidden.quadmenu.offcanvas', function (e) {
                $(this).removeClass('quadmenu-is-offcanvas');
            });

            plugin.$quadmenu.on('shown.quadmenu.offcanvas', function (e) {
                $('.quadmenu-navbar-toggle', $(this)).removeClass('collapsed');
            });

            plugin.$quadmenu.on('hidden.quadmenu.offcanvas', function (e) {
                $('.quadmenu-navbar-toggle', $(this)).addClass('collapsed');
            });

        },
        quadmenuInitScrollBar: function (plugin) {

            plugin = plugin || this;
            if (!this.settings.perfectScrollbar)
                return;
            plugin.handleDropdownScrollbar();
            plugin.handleVerticalScrollbar();
            $(window).resize($.debounce(300, function () {
                plugin.handleDropdownScrollbar();
                plugin.handleVerticalScrollbar();
            }));
        },
        handleDropdownScrollbar: function (plugin) {

            plugin = plugin || this;

            this.$ul.off('shown.quadmenu.dropdown.pscrollbar');
            this.$ul.find('.quadmenu-item-type-tab > .quadmenu-dropdown-menu, .quadmenu-item-type-mega > .quadmenu-dropdown-menu, .quadmenu-item-type-login > .quadmenu-dropdown-menu').perfectScrollbar('destroy').data('ps-id', false);

            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;
            this.$ul.on('shown.quadmenu.dropdown.pscrollbar', '.quadmenu-item-type-tab, .quadmenu-item-type-mega, .quadmenu-item-type-login', function (e) {

                e.stopPropagation();
                plugin.$dropdown = $(this).find('> .quadmenu-dropdown-menu');
                //fix for mousehover ----------------------
                plugin.$dropdown.scroll($.debounce(500, true, function () {
                    $(this).data('quadmenu-killHover', true);
                }));
                plugin.$dropdown.scroll($.debounce(500, function () {
                    $(this).removeData('quadmenu-killHover');
                }));
                if (plugin.$dropdown.data('ps-id')) {
                    plugin.$dropdown.perfectScrollbar('update');
                    return;
                }

                plugin.$dropdown.perfectScrollbar({
                    useKeyboard: true,
                    suppressScrollX: true,
                    includePadding: true,
                    scrollYMarginOffset: 1
                });
            });
        },
        handleVerticalScrollbar: function (plugin) {

            plugin = plugin || this;

            plugin.$offcanvas = this.$quadmenu.find('.navbar-offcanvas');

            plugin.$offcanvas.perfectScrollbar('destroy').data('ps-id', false);

            if (this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;

            plugin.$offcanvas.on('shown.quadmenu.dropdown.pscrollbar hidden.quadmenu.dropdown.pscrollbar shown.quadmenu.offcanvas.pscrollbar hidden.quadmenu.offcanvas.pscrollbar', function (e) {

                var $psMenu = $(this);
                $(this).on('mouseup mouseenter', '.ps-scrollbar-y-rail', function () {
                    $psMenu.find('.quadmenu-item > .quadmenu-dropdown-toggle').data('quadmenu-killHover', true);
                });
                $(this).on('mouseleave', '.ps-scrollbar-y-rail', function () {
                    $psMenu.find('.quadmenu-item > .quadmenu-dropdown-toggle').removeData('quadmenu-killHover');
                });
                e.stopPropagation();
                $psMenu.perfectScrollbar('update');
            });
            if (plugin.$offcanvas.data('ps-id')) {
                plugin.$offcanvas.perfectScrollbar('update');
                return;
            }
            plugin.$offcanvas.perfectScrollbar({
                useKeyboard: true,
                suppressScrollX: true,
                includePadding: true,
                scrollYMarginOffset: 1
            });
        },
        quadmenuInitNavbarSlideBar: function (plugin) {

            plugin = plugin || this;
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal') || !this.$quadmenu.hasClass('quadmenu-hover-slidebar'))
                return;
            $(document).ready(function () {
                setTimeout(function () {
                    plugin.$ul.append('<li class="quadmenu-hover-slidebar invisible"><span class="bar"></span></li>');
                    plugin.handleSlideBar(plugin.$ul);
                }, 1000);
            });
        },
        handleSlideBar: function (ul, plugin) {

            plugin = plugin || this;
            var $ul = $(ul),
                    slide_nav = $ul.find('> li.quadmenu-hover-slidebar'),
                    li = '> li.quadmenu-item.quadmenu-item-level-0:not(.quadmenu-item-type-icon):not(.quadmenu-item-type-search):not(.quadmenu-item-type-cart):not(.quadmenu-item-type-login):not(.quadmenu-item-type-social)',
                    $subtitle = $ul.find('> li > a > .quadmenu-item-content > .quadmenu-subtitle'),
                    subtitle = $subtitle.length ? parseInt($subtitle.css('font-size')) : 0,
                    $open = $ul.find('> li.quadmenu-item.quadmenu-item-level-0.open'),
                    $active = $open.length ? $open : $ul.find('> li.quadmenu-item.quadmenu-item-level-0.active'),
                    $current = $active.length ? $active : $ul.find(li).filter(':visible').not('.quadmenu-float-opposite').first();
            var bottom = function ($ul, $current) {

                if (!$ul.hasClass('sl-middle'))
                    return 'auto';
                return Math.round(parseInt($current.find('> a').outerHeight()) * 0.5 - parseInt($current.find('> a').css('font-size')) - subtitle - 5) + 'px';
            }

            function slide_nav_css($current) {
                var width = parseInt($current.find('> a').outerWidth()),
                        //padding = parseInt($current.css('margin-left') + $current.find('> a').find('> .quadmenu-item-content').css('padding-left')),
                        pos_left = parseInt($current.position().left),
                        pos_right = parseInt($current.position().right);
                slide_nav.css({'width': width + 'px', 'left': pos_left + 'px', 'right': pos_right + 'px', 'bottom': bottom($ul, $current)}).removeClass('invisible');
            }

            slide_nav_css($current);
            slide_nav.data('slidebar-style', slide_nav.attr('style'));
            //handle sticky transforms
            this.$ul.on('sticky.quadmenu.navbar.slidebar unsticky.quadmenu.navbar.slidebar', function () {
                slide_nav.addClass('invisible');
                var $ul = $(this).find('.quadmenu-navbar-nav'),
                        $open = $ul.find('> ' + li + '.open'),
                        $active = $open.length ? $open : $ul.find('> li.quadmenu-item.quadmenu-item-level-0.active'),
                        $current = $active.length ? $active : $ul.find(li).not('.quadmenu-float-opposite').first();
                slide_nav_css($current);
                slide_nav.data('slidebar-style', slide_nav.attr('style'));
            });
            //handle mouse events
            $ul.find(li).on('hover.slidebar', function () {
                slide_nav_css($(this));
            });
            if (this.$ul.hasClass('quadmenu-trigger-click')) {
                $ul.find(li).on('hidden.quadmenu.dropdown.slidebar', function () {
                    slide_nav.attr('style', slide_nav.data('slidebar-style')).show();
                });
                return;
            }

            if (typeof $.fn.hoverIntent === 'undefined') {

                $ul.on('mouseleave.slidebar', function () {
                    slide_nav.attr('style', slide_nav.data('slidebar-style')).show();
                });
                return;
            }

            $ul.hoverIntent({
                over: function () {
                },
                out: function () {
                    slide_nav.attr('style', slide_nav.data('slidebar-style')).show();
                },
                sensitivity: plugin.settings.intent_interval,
                timeout: plugin.settings.intent_delay,
                interval: plugin.settings.intent_threshold
            });
        },
        quadmenuInitItemMega: function (plugin) {

            plugin = plugin || this;
            this.$ul.off('shown.quadmenu.dropdown.height');
            this.$ul.find('shown.quadmenu.dropdown.height', '.quadmenu-item-type-mega > .quadmenu-dropdown-menu').removeAttr('style');
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;
            this.$ul.on('shown.quadmenu.dropdown.height', '.quadmenu-item-type-mega', function (e) {
                e.stopPropagation();
                plugin.handleDropdownMaxHeight($(this));
            });
        },
        quadmenuInitItemTabs: function (plugin) {

            plugin = plugin || this;
            plugin.handleTabs();
            $(window).resize($.debounce(600, function () {
                plugin.handleTabs();
            }));
        },
        handleTabs: function (plugin) {

            plugin = plugin || this;

            this.$ul.off('shown.quadmenu.dropdown.tabs', '.quadmenu-item-type-tabs');

            this.$ul.off('shown.quadmenu.dropdown.tabheight', '.quadmenu-item-type-tab');

            this.$ul.find('.quadmenu-item-type-tab > .quadmenu-dropdown-menu').data('quadmenu-maxHeight', false).data('quadmenu-killHover', false);

            this.$ul.find('.quadmenu-item-type-tab > .quadmenu-dropdown-menu > ul').removeAttr('style');

            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;

            this.$ul.on('shown.quadmenu.dropdown.tabs', '.quadmenu-item-type-tabs', function (e) {
                e.stopPropagation();

                var $tab = $(this).find('.quadmenu-item-type-tab');

                if ($tab.filter('.open').length) {
                    $tab.filter('.open').trigger('shown.quadmenu.dropdown.tabheight');
                    return;
                }

                plugin.openSubmenu($tab.first());

            });

            this.$ul.on('shown.quadmenu.dropdown.tabheight', '.quadmenu-item-type-tab', function (e) {
                e.stopPropagation();

                var $tab = $(this),
                        $tabs = $tab.closest('.quadmenu-dropdown-menu > ul');

                plugin.handleDropdownMaxHeight($tab, $tabs.outerHeight());

                plugin.handleTabsHeight($tab);

            });
        },
        handleTabsHeight: function (open) {

            var $open = $(open),
                    $tabs = $open.closest('.quadmenu-dropdown-menu > ul');

            $tabs.css({'min-height': $open.find('> .quadmenu-dropdown-menu').data('quadmenu-maxHeight') + 'px'});
        },
        handleDropdownMaxHeight: function (li, minHeight) {

            if (typeof $.fn.scrollTop === 'undefined')
                return;

            minHeight = minHeight || 200;

            var $li = $(li),
                    $dropdown = $li.find('> .quadmenu-dropdown-menu');

            if (!$dropdown.length)
                return;

            var dropdownHeight = $dropdown.data('quadmenu-dropdownHeight') || $dropdown.outerHeight(),
                    offsetTop = this.getElementOffset($dropdown),
                    scrollTop = $(window).scrollTop(),
                    currentTop = Math.max(0, offsetTop - scrollTop),
                    currentBottom = $(window).height() - currentTop - 15,
                    maxHeight = Math.min(dropdownHeight, currentBottom),
                    Height = parseInt(Math.max(maxHeight, minHeight));

            $dropdown.css({'height': Height + 'px', 'overflow-y': 'auto'}).data('quadmenu-maxHeight', Height).data('quadmenu-dropdownHeight', dropdownHeight);

            return Height;
        },
        getElementOffset: function (element) {

            var $element = $(element);

            if (!$element.length)
                return;

            if (!$element.is(':visible')) {
                $element.data('element-style', $element.attr('style'));
                $element.css({'visibility': 'hidden', 'display': 'block', 'transform': 'none', 'animation': 'none'});
                $element.removeAttr('style').attr('style', $element.data('element-style'));
            }

            return $element.offset().top;
        },
        quadmenuInitItemCarousel: function (plugin) {

            plugin = plugin || this;

            if (typeof $.fn.owlCarousel === 'undefined')
                return;

            this.$ul.on('hide.quadmenu.dropdown.carousel', '.quadmenu-item-type-carousel', function () {

                var $owl = $(this).find('> .quadmenu-dropdown-menu > ul');

                $owl.trigger('stop.owl.autoplay');
                $owl.trigger('stop.owl.video');

            });

            this.$ul.on('mouseenter.hoverIntent', '.quadmenu-item-type-carousel > .quadmenu-dropdown-menu > ul', function () {

                var $owl = $(this);

                $owl.trigger('stop.owl.autoplay');

            });

            this.$ul.on('mouseleave.hoverIntent', '.quadmenu-item-type-carousel > .quadmenu-dropdown-menu > ul', function () {

                var $owl = $(this);

                if ($(this).data('autoplay') !== 'on')
                    return;

                $owl.trigger('play.owl.autoplay');

            });

            this.$ul.on('shown.quadmenu.dropdown.carousel', '.quadmenu-item-type-carousel', function (e) {
                e.stopPropagation();

                var $owl = $(this).find('> .quadmenu-dropdown-menu > ul'),
                        speed = parseInt($(this).data('speed')),
                        autoplay = $(this).data('autoplay') === 'on' ? true : false,
                        pagination = $(this).data('pagination') === 'on' ? true : false,
                        dots = $(this).data('dots') === 'on' ? true : false,
                        autoplay_speed = parseInt($(this).data('autoplay_speed')) + speed;



                if ($owl.hasClass('owl-loaded')) {
                    $owl.trigger('refresh.owl.carousel');
                    return;
                }

                $owl.owlCarousel({
                    itemClass: 'quadmenu-item-type-panel',
                    responsive: false,
                    loop: true,
                    items: 1,
                    navText: false,
                    margin: false,
                    autoplayHoverPause: true,
                    dotsEach: true,
                    dots: dots,
                    nav: pagination,
                    smartSpeed: speed,
                    autoplay: autoplay,
                    autoplayTimeout: autoplay_speed,
                });

            });

        },
        quadmenuInitItemLogIn: function (plugin) {

            plugin = plugin || this;

            this.$ul.on('shown.quadmenu.dropdown.login', '.quadmenu-item-type-login', function (e) {

                var $li = $(this);

                $li.find('[data-toggle=form]').on('click', function () {
                    e.stopPropagation();
                    e.preventDefault();

                    $li.find($(this).data('target')).removeClass('hidden');

                    $li.find($(this).data('current')).addClass('hidden');
                });

                plugin.handleRegister(e, $li);
            });
            this.$ul.off('shown.quadmenu.dropdown.height');
            this.$ul.find('shown.quadmenu.dropdown.height', '.quadmenu-item-type-login > .quadmenu-dropdown-menu').removeAttr('style');
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;
            this.$ul.on('shown.quadmenu.dropdown.height', '.quadmenu-item-type-login', function (e) {
                e.stopPropagation();
                plugin.handleDropdownMaxHeight($(this));
            });
        },
        handleRegister: function (e, li) {

            var $li = $(li),
                    $form = $li.find('form.quadmenu-registration-form'),
                    $button = $form.find('#add-new-user');

            $button.click(function (event) {

                //Prevent default action, so when user clicks button he doesn't navigate away from page
                if (event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }

                var $dropdown = $form.closest('.quadmenu-dropdown-menu'),
                        $message = $form.find('.quadmenu-result-message');

                $message.empty();

                // Collect data from inputs
                $.ajax({
                    type: 'post',
                    url: quadmenu.ajax_url,
                    data: {
                        action: 'quadmenu_register_user',
                        nonce: quadmenu.nonce,
                        user: $form.find('#quadmenu_username').val(),
                        pass: $form.find('#quadmenu_pass').val(),
                        mail: $form.find('#quadmenu_email').val(),
                        name: $form.find('#quadmenu_name').val(),
                        nick: $form.find('#quadmenu_nick').val(),
                    },
                    beforeSend: function () {
                        $dropdown.addClass('quadmenu-dropdown-mask');
                    },
                    complete: function () {

                        setTimeout(function () {
                            $dropdown.removeClass('quadmenu-dropdown-mask');
                        }, 600);
                    },
                    success: function (response) {

                        $message.append(response.data);

                        if (response.success === true) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 200);
                        }
                    },
                });
            });
        },
        quadmenuInitItemSocial: function (plugin) {

            plugin = plugin || this;
            plugin.handleSocial();
            $(window).resize($.debounce(600, function () {
                plugin.handleSocial();
            }));
        },
        handleSocial: function (plugin) {

            plugin = plugin || this;
            this.$ul.off('shown.quadmenu.dropdown.social hidden.quadmenu.dropdown.social');
            if (!this.$quadmenu.hasClass('quadmenu-is-horizontal'))
                return;
            this.$ul.on('shown.quadmenu.dropdown.social hidden.quadmenu.dropdown.social', '.quadmenu-item-type-social', function () {
                plugin.$ul.find('> li.quadmenu-item.quadmenu-item-level-0:not(.quadmenu-item-type-social)').toggleClass('invisible');
                plugin.$ul.find('> li.quadmenu-hover-slidebar').toggleClass('invisible');
            });
        },
        quadmenuInitItemCart: function (plugin) {

            plugin = plugin || this;
            var $cart = plugin.$quadmenu.find('li.quadmenu-item-type-cart'),
                    url = $cart.find('> a').data('cart-url'),
                    qty = $cart.find('> a').data('cart-qty');
            if (!$cart.length)
                return;
            if (qty === 0)
                $cart.removeClass('quadmenu-dropdown');
            $('body').bind('added_to_cart', function () {
                plugin.handleWooCart(plugin, $cart, url);
            });
            $('body').bind('edd_quantity_updated', function () {
                plugin.handleEddCart(plugin, $cart, url);
            });
        },
        handleWooCart: function (plugin, $cart, url) {

            plugin = plugin || this;
            $cart.each(function () {

                var $woo_cart = $cart.find('.widget_shopping_cart');
                if (!$woo_cart.length)
                    return;
                var total = $woo_cart.find('.total .amount').html(),
                        the_quantities = $woo_cart.find('.quantity'),
                        qty = 0,
                        numberPattern = /\d+/g;
                the_quantities.each(function (idx, el) {
                    var qtytext = $(el).html().match(numberPattern);
                    var qtyint = parseInt(qtytext[0]);
                    qty = qty + qtyint;
                });
                plugin.updateCart($(this), total, qty, url);
            });
        },
        handleEddCart: function (plugin, $cart, url) {

            plugin = plugin || this;
            $cart.each(function () {

                var $edd_cart = $cart.find('.widget_edd_cart_widget');
                if (!$edd_cart.length)
                    return;
                var total = $edd_cart.find('.edd_subtotal .subtotal').html(),
                        qty = $edd_cart.find('.edd-cart-quantity').html();
                plugin.updateCart($(this), total, qty, url);
            });
        },
        updateCart: function (cart, total, qty, url) {

            var $cart = $(cart);
            var $total = $cart.find('.quadmenu-cart-total'),
                    $qty = $cart.find('.quadmenu-cart-qty');
            $qty.addClass('animate');
            $total.html(total);
            $qty.html(qty);
            if (qty > 0)
                $cart.addClass('quadmenu-dropdown').find('> a').addClass('quadmenu-dropdown-toggle');
            else if (qty === 0)
                $cart.removeClass('quadmenu-dropdown').find('> a').removeClass('quadmenu-dropdown-toggle');
            if (url)
                $cart.find('> a').attr('href', url)

            setTimeout(function () {
                $qty.removeClass('animate');
            }, 1500);
        },
        quadmenuInitItemWidgets: function (plugin) {

            $(document).on('hidden.quadmenu.dropdown', function (e) {
                $(this).find('.widget_media_video video').each(function () {
                    this.player.pause();
                });
            });

            $(document).on('hidden.quadmenu.dropdown', function (e) {
                $(this).find('.widget_media_audio audio').each(function () {
                    this.player.pause();
                });
            });
        },
    };

    $.fn[ pluginName ] = function (options) {
        var args = arguments;
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, "plugin_" + pluginName)) {
                    $.data(this, "plugin_" + pluginName, new Plugin(this, options));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
            // Cache the method call to make it possible to return a value
            var returns;
            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);
                // Tests that there's already a plugin-instance and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance, and pass it the supplied arguments.
                    returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });
            // If the earlier cached method gives a value back return the value, otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };
})(jQuery, window, document);
(function ($) {

    var quadmenu_initialized = false;
    jQuery(function () {
        quadmenu_init();
    });
    //Backup
    $(window).load(function () {
        quadmenu_init();
    });
    function quadmenu_init() {

        if (quadmenu_initialized)
            return;
        quadmenu_initialized = true;
        //scroll to non-ID "hashes"
        if (window.location.hash.substring(1, 2) == '.') {
            var $scrollTarget = $(window.location.hash.substring(1));
            if ($scrollTarget.size())
                window.scrollTo(0, $scrollTarget.offset().top);
        }
        $('nav#quadmenu').quadmenu();
    }

})(jQuery);
/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function (b, c) {
    var $ = b.jQuery || b.Cowboy || (b.Cowboy = {}), a;
    $.throttle = a = function (e, f, j, i) {
        var h, d = 0;
        if (typeof f !== "boolean") {
            i = j;
            j = f;
            f = c
        }
        function g() {
            var o = this, m = +new Date() - d, n = arguments;
            function l() {
                d = +new Date();
                j.apply(o, n)
            }
            function k() {
                h = c
            }
            if (i && !h) {
                l()
            }
            h && clearTimeout(h);
            if (i === c && m > e) {
                l()
            } else {
                if (f !== true) {
                    h = setTimeout(i ? k : l, i === c ? e - m : e)
                }
            }
        }
        if ($.guid) {
            g.guid = j.guid = j.guid || $.guid++
        }
        return g
    };
    $.debounce = function (d, e, f) {
        return f === c ? a(d, e, false) : a(d, f, e !== false)
    }
})(this);

(function ($) {
    'use strict';

    function transitionEnd() {
        var el = document.createElement('quadmenu')

        var transEndEventNames = {
            WebkitTransition: 'webkitTransitionEnd',
            MozTransition: 'transitionend',
            OTransition: 'oTransitionEnd otransitionend',
            transition: 'transitionend'
        }

        for (var name in transEndEventNames) {
            if (el.style[name] !== undefined) {
                return {end: transEndEventNames[name]}
            }
        }

        return false // explicit for ie8 (  ._.)
    }


    $.fn.emulateTransitionEnd = function (duration) {
        var called = false
        var $el = this
        $(this).one('quadmenuTransitionEnd', function () {
            called = true
        })
        var callback = function () {
            if (!called)
                $($el).trigger($.support.transition.end)
        }
        setTimeout(callback, duration)
        return this
    }

    $(function () {
        $.support.transition = transitionEnd()

        if (!$.support.transition)
            return

        $.event.special.quadmenuTransitionEnd = {
            bindType: $.support.transition.end,
            delegateType: $.support.transition.end,
            handle: function (e) {
                if ($(e.target).is(this))
                    return e.handleObj.handler.apply(this, arguments)
            }
        }
    })

    var QuadMenuCollapse = function (element, options) {
        this.$element = $(element)
        this.options = $.extend({}, QuadMenuCollapse.DEFAULTS, options)
        this.$trigger = $('[data-quadmenu="collapse"][href="#' + element.id + '"],' +
                '[data-quadmenu="collapse"][data-target="#' + element.id + '"]')
        this.transitioning = null

        if (this.options.parent) {
            this.$parent = this.getParent()
        } else {
            this.addAriaAndQuadMenuCollapsedClass(this.$element, this.$trigger)
        }

        if (this.options.toggle)
            this.toggle()
    }

    QuadMenuCollapse.TRANSITION_DURATION = 350

    QuadMenuCollapse.DEFAULTS = {
        toggle: true
    }

    QuadMenuCollapse.prototype.dimension = function () {
        var hasWidth = this.$element.hasClass('width')
        return hasWidth ? 'width' : 'height'
    }

    QuadMenuCollapse.prototype.show = function () {
        if (this.transitioning || this.$element.hasClass('in'))
            return

        var activesData
        var actives = this.$parent && this.$parent.children('.panel').children('.in, .collapsing')

        if (actives && actives.length) {
            activesData = actives.data('quadmenu.collapse')
            if (activesData && activesData.transitioning)
                return
        }

        var startEvent = $.Event('show.quadmenu.collapse')
        this.$element.trigger(startEvent)
        if (startEvent.isDefaultPrevented())
            return

        if (actives && actives.length) {
            Plugin.call(actives, 'hide')
            activesData || actives.data('quadmenu.collapse', null)
        }

        var dimension = this.dimension()

        this.$element
                .removeClass('collapse')
                .addClass('collapsing')[dimension](0)
                .attr('aria-expanded', true)

        this.$trigger
                .removeClass('collapsed')
                .attr('aria-expanded', true)

        this.transitioning = 1

        var complete = function () {
            this.$element
                    .removeClass('collapsing')
                    .addClass('collapse in')[dimension]('')
            this.transitioning = 0
            this.$element
                    .trigger('shown.quadmenu.collapse')
        }

        if (!$.support.transition)
            return complete.call(this)

        var scrollSize = $.camelCase(['scroll', dimension].join('-'))

        this.$element
                .one('quadmenuTransitionEnd', $.proxy(complete, this))
                .emulateTransitionEnd(QuadMenuCollapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])
    }

    QuadMenuCollapse.prototype.hide = function () {
        if (this.transitioning || !this.$element.hasClass('in'))
            return

        var startEvent = $.Event('hide.quadmenu.collapse')
        this.$element.trigger(startEvent)
        if (startEvent.isDefaultPrevented())
            return

        var dimension = this.dimension()

        this.$element[dimension](this.$element[dimension]())[0].offsetHeight

        this.$element
                .addClass('collapsing')
                .removeClass('collapse in')
                .attr('aria-expanded', false)

        this.$trigger
                .addClass('collapsed')
                .attr('aria-expanded', false)

        this.transitioning = 1

        var complete = function () {
            this.transitioning = 0
            this.$element
                    .removeClass('collapsing')
                    .addClass('collapse')
                    .trigger('hidden.quadmenu.collapse')
        }

        if (!$.support.transition)
            return complete.call(this)

        this.$element
        [dimension](0)
                .one('quadmenuTransitionEnd', $.proxy(complete, this))
                .emulateTransitionEnd(QuadMenuCollapse.TRANSITION_DURATION)
    }

    QuadMenuCollapse.prototype.toggle = function () {
        this[this.$element.hasClass('in') ? 'hide' : 'show']()
    }

    QuadMenuCollapse.prototype.getParent = function () {
        return $(this.options.parent)
                .find('[data-quadmenu="collapse"][data-parent="' + this.options.parent + '"]')
                .each($.proxy(function (i, element) {
                    var $element = $(element)
                    this.addAriaAndQuadMenuCollapsedClass(getTargetFromTrigger($element), $element)
                }, this))
                .end()
    }

    QuadMenuCollapse.prototype.addAriaAndQuadMenuCollapsedClass = function ($element, $trigger) {
        var isOpen = $element.hasClass('in')

        $element.attr('aria-expanded', isOpen)
        $trigger
                .toggleClass('collapsed', !isOpen)
                .attr('aria-expanded', isOpen)
    }

    function getTargetFromTrigger($trigger) {
        var href
        var target = $trigger.attr('data-target')
                || (href = $trigger.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') // strip for ie7

        return $(target)
    }


    // COLLAPSE PLUGIN DEFINITION
    // ==========================

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('quadmenu.collapse')
            var options = $.extend({}, QuadMenuCollapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if (!data && options.toggle && /show|hide/.test(option))
                options.toggle = false
            if (!data)
                $this.data('quadmenu.collapse', (data = new QuadMenuCollapse(this, options)))
            if (typeof option == 'string')
                data[option]()
        })
    }

    var old = $.fn.collapse

    $.fn.collapse = Plugin
    $.fn.collapse.Constructor = QuadMenuCollapse


    // COLLAPSE NO CONFLICT
    // ====================

    $.fn.collapse.noConflict = function () {
        $.fn.collapse = old
        return this
    }


    // COLLAPSE DATA-API
    // =================

    $(document).on('click.quadmenu.collapse.data-api', '[data-quadmenu="collapse"]', function (e) {
        var $this = $(this)

        if (!$this.attr('data-target'))
            e.preventDefault()

        var $target = getTargetFromTrigger($this)
        var data = $target.data('quadmenu.collapse')
        var option = data ? 'toggle' : $this.data()

        Plugin.call($target, option)
    })

}(window.jQuery));

(function ($) {
    'use strict';

    var QuadMenuOffCanvas = function (element, options) {
        this.$element = $(element)
        this.options = $.extend({}, QuadMenuOffCanvas.DEFAULTS, options)
        this.state = null
        this.placement = null

        if (this.options.recalc) {
            this.calcClone()
            $(window).on('resize', $.proxy(this.recalc, this))
        }

        if (this.options.autohide && !this.options.modal) {
            var eventName = (navigator.userAgent.match(/(iPad|iPhone)/i) === null) ? 'click' : 'touchstart'
            $(document).on('click touchstart', $.proxy(this.autohide, this))
        }

        if (this.options.toggle)
            this.toggle()

        if (this.options.disablescrolling) {
            this.options.disableScrolling = this.options.disablescrolling
            delete this.options.disablescrolling
        }
    }

    QuadMenuOffCanvas.DEFAULTS = {
        toggle: true,
        placement: 'auto',
        autohide: true,
        recalc: true,
        disableScrolling: true,
        modal: false
    }

    QuadMenuOffCanvas.prototype.offset = function () {
        switch (this.placement) {
            case 'left':
            case 'right':
                return this.$element.outerWidth()
            case 'top':
            case 'bottom':
                return this.$element.outerHeight()
        }
    }

    QuadMenuOffCanvas.prototype.calcPlacement = function () {
        if (this.options.placement !== 'auto') {
            this.placement = this.options.placement
            return
        }

        if (!this.$element.hasClass('in')) {
            this.$element.css('visiblity', 'hidden !important').addClass('in')
        }

        var horizontal = $(window).width() / this.$element.width()
        var vertical = $(window).height() / this.$element.height()

        var element = this.$element
        function ab(a, b) {
            if (element.css(b) === 'auto')
                return a
            if (element.css(a) === 'auto')
                return b

            var size_a = parseInt(element.css(a), 10)
            var size_b = parseInt(element.css(b), 10)

            return size_a > size_b ? b : a
        }

        //this.placement = horizontal >= vertical ? ab('left', 'right') : ab('top', 'bottom')

        this.placement = ab('left', 'right');

        if (this.$element.css('visibility') === 'hidden !important') {
            this.$element.removeClass('in').css('visiblity', '')
        }
    }

    QuadMenuOffCanvas.prototype.opposite = function (placement) {
        switch (placement) {
            case 'top':
                return 'bottom'
            case 'left':
                return 'right'
            case 'bottom':
                return 'top'
            case 'right':
                return 'left'
        }
    }

    QuadMenuOffCanvas.prototype.getCanvasElements = function () {
        // Return a set containing the canvas plus all fixed elements
        var canvas = this.options.canvas ? $(this.options.canvas) : this.$element

        var fixed_elements = canvas.find('*').filter(function () {
            return $(this).css('position') === 'fixed'
        }).not(this.options.exclude)

        return canvas.add(fixed_elements)
    }

    QuadMenuOffCanvas.prototype.slide = function (elements, offset, callback) {
        // Use jQuery animation if CSS transitions aren't supported
        if (!$.support.transition) {
            var anim = {}
            anim[this.placement] = "+=" + offset
            return elements.animate(anim, 350, callback)
        }

        var placement = this.placement

        // gazofnaz edit: No longer used. See below
        // var opposite = this.opposite(placement)

        elements.each(function () {
            if ($(this).css(placement) !== 'auto')
                $(this).css(placement, (parseInt($(this).css(placement), 10) || 0) + offset)

            // gazofnaz edit: this broke the slide in animation
            // if ($(this).css(opposite) !== 'auto')
            //     $(this).css(opposite, (parseInt($(this).css(opposite), 10) || 0) - offset)
        })

        this.$element
                .one($.support.transition.end, callback)
                .emulateTransitionEnd(350)
    }

    QuadMenuOffCanvas.prototype.disableScrolling = function () {
        var bodyWidth = $('body').width()
        var prop = 'padding-right'

        if ($('body').data('offcanvas-style') === undefined) {
            $('body').data('offcanvas-style', $('body').attr('style') || '')
        }

        //1326$('body').css('overflow-x', 'hidden')

        if ($('body').width() > bodyWidth) {
            var padding = parseInt($('body').css(prop), 10) + $('body').width() - bodyWidth

            setTimeout(function () {
                $('body').css(prop, padding)
            }, 1)
        }
        //disable scrolling on mobiles (they ignore overflow:hidden)
        $('body').on('touchmove.quadmenu', function (e) {
            if (!$(event.target).closest('.offcanvas').length)
                e.preventDefault();
        });
    }

    QuadMenuOffCanvas.prototype.enableScrolling = function () {
        $('body').off('touchmove.quadmenu');
    }

    QuadMenuOffCanvas.prototype.show = function () {
        if (this.state)
            return

        var startEvent = $.Event('show.quadmenu.offcanvas')
        this.$element.trigger(startEvent)
        if (startEvent.isDefaultPrevented())
            return

        this.state = 'slide-in'
        this.calcPlacement();

        var elements = this.getCanvasElements()
        var placement = this.placement
        var opposite = this.opposite(placement)
        var offset = this.offset()

        if (elements.index(this.$element) !== -1) {
            $(this.$element).data('offcanvas-style', $(this.$element).attr('style') || '')
            this.$element.css(placement, -1 * offset)
            this.$element.css(placement); // Workaround: Need to get the CSS property for it to be applied before the next line of code
        }

        elements.addClass('canvas-sliding').each(function () {
            var $this = $(this)
            if ($this.data('offcanvas-style') === undefined)
                $this.data('offcanvas-style', $this.attr('style') || '')
            if ($this.css('position') === 'static')
                $this.css('position', 'relative')
            if (($this.css(placement) === 'auto' || $this.css(placement) === '0px') &&
                    ($this.css(opposite) === 'auto' || $this.css(opposite) === '0px')) {
                $this.css(placement, 0)
            }
        })

        if (this.options.disableScrolling)
            this.disableScrolling()
        if (this.options.modal)
            this.toggleBackdrop()

        var complete = function () {
            if (this.state != 'slide-in')
                return

            this.state = 'slid'

            elements.removeClass('canvas-sliding').addClass('canvas-slid')
            this.$element.trigger('shown.quadmenu.offcanvas')
        }

        setTimeout($.proxy(function () {
            this.$element.addClass('in')
            this.slide(elements, offset, $.proxy(complete, this))
        }, this), 1)
    }

    QuadMenuOffCanvas.prototype.hide = function (fast) {
        if (this.state !== 'slid')
            return

        var startEvent = $.Event('hide.quadmenu.offcanvas')
        this.$element.trigger(startEvent)
        if (startEvent.isDefaultPrevented())
            return

        this.state = 'slide-out'

        var elements = $('.canvas-slid')
        var placement = this.placement
        var offset = -1 * this.offset()

        var complete = function () {
            if (this.state != 'slide-out')
                return

            this.state = null
            this.placement = null

            this.$element.removeClass('in')

            elements.removeClass('canvas-sliding')
            elements.add(this.$element).add('body').each(function () {
                $(this).attr('style', $(this).data('offcanvas-style')).removeData('offcanvas-style')
            })

            this.$element.trigger('hidden.quadmenu.offcanvas')
        }

        if (this.options.disableScrolling)
            this.enableScrolling()
        if (this.options.modal)
            this.toggleBackdrop()

        elements.removeClass('canvas-slid').addClass('canvas-sliding')

        setTimeout($.proxy(function () {
            this.slide(elements, offset, $.proxy(complete, this))
        }, this), 1)
    }

    QuadMenuOffCanvas.prototype.toggle = function () {
        if (this.state === 'slide-in' || this.state === 'slide-out')
            return
        this[this.state === 'slid' ? 'hide' : 'show']()
    }

    QuadMenuOffCanvas.prototype.toggleBackdrop = function (callback) {
        callback = callback || $.noop;
        if (this.state == 'slide-in') {
            var doAnimate = $.support.transition;

            this.$backdrop = $('<div class="modal-backdrop fade" />')
                    .insertAfter(this.$element);

            if (doAnimate)
                this.$backdrop[0].offsetWidth // force reflow

            this.$backdrop.addClass('in')
            this.$backdrop.on('click.quadmenu', $.proxy(this.autohide, this))

            doAnimate ?
                    this.$backdrop
                    .one($.support.transition.end, callback)
                    .emulateTransitionEnd(150) :
                    callback()
        } else if (this.state == 'slide-out' && this.$backdrop) {
            this.$backdrop.removeClass('in');
            $('body').off('touchmove.quadmenu');
            var self = this;
            if ($.support.transition) {
                this.$backdrop
                        .one($.support.transition.end, function () {
                            self.$backdrop.remove();
                            callback()
                            self.$backdrop = null;
                        })
                        .emulateTransitionEnd(150);
            } else {
                this.$backdrop.remove();
                this.$backdrop = null;
                callback();
            }
        } else if (callback) {
            callback()
        }
    }

    QuadMenuOffCanvas.prototype.calcClone = function () {
        this.$calcClone = this.$element.clone()
                .html('')
                .addClass('offcanvas-clone').removeClass('in')
                .appendTo($('body'))
    }

    QuadMenuOffCanvas.prototype.recalc = function () {
        if (this.$calcClone.css('display') === 'none' || (this.state !== 'slid' && this.state !== 'slide-in'))
            return

        this.state = null
        this.placement = null
        var elements = this.getCanvasElements()

        this.$element.removeClass('in')

        elements.removeClass('canvas-slid')
        elements.add(this.$element).add('body').each(function () {
            $(this).attr('style', $(this).data('offcanvas-style')).removeData('offcanvas-style')
        })
    }

    QuadMenuOffCanvas.prototype.autohide = function (e) {
        if ($(e.target).closest(this.$element).length === 0)
            this.hide()
        var target = $(e.target);
        if (!target.hasClass('dropdown-backdrop') && $(e.target).closest(this.$element).length === 0)
            this.hide()
    }

    // OFFCANVAS PLUGIN DEFINITION
    // ==========================

    var old = $.fn.offcanvas

    $.fn.offcanvas = function (option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('quadmenu.offcanvas')
            var options = $.extend({}, QuadMenuOffCanvas.DEFAULTS, $this.data(), typeof option === 'object' && option)

            if (!data)
                $this.data('quadmenu.offcanvas', (data = new QuadMenuOffCanvas(this, options)))
            if (typeof option === 'string')
                data[option]()
        })
    }

    $.fn.offcanvas.Constructor = QuadMenuOffCanvas


    // OFFCANVAS NO CONFLICT
    // ====================

    $.fn.offcanvas.noConflict = function () {
        $.fn.offcanvas = old
        return this
    }

    // OFFCANVAS DATA-API
    // =================

    $(document).on('click.quadmenu.offcanvas.data-api', '[data-quadmenu=offcanvas]', function (e) {
        var $this = $(this), href
        var target = $this.attr('data-target')
                || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
        var $canvas = $(target)
        var data = $canvas.data('quadmenu.offcanvas')
        var option = data ? 'toggle' : $this.data()

        e.preventDefault();
        e.stopPropagation()

        if (data)
            data.toggle()
        else
            $canvas.offcanvas(option)
    })

}(window.jQuery));
//https://github.com/jasny/bootstrap/issues/253