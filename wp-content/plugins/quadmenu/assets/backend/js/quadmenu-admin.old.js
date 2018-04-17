/* ========================================================================
 * Bootstrap: modal.js v3.3.7
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    // MODAL CLASS DEFINITION
    // ======================

    var QuadMenuModal = function (element, options) {
        this.options = options
        this.$body = $(document.body)
        this.$element = $(element)
        this.$dialog = this.$element.find('.modal-dialog')
        this.$backdrop = null
        this.isShown = null
        this.originalBodyPad = null
        this.scrollbarWidth = 0
        this.ignoreBackdropClick = false

        if (this.options.remote) {
            this.$element
                    .find('.modal-content')
                    .load(this.options.remote, $.proxy(function () {
                        this.$element.trigger('loaded.quadmenu.modal')
                    }, this))
        }
    }

    QuadMenuModal.VERSION = '3.3.7'

    QuadMenuModal.TRANSITION_DURATION = 300
    QuadMenuModal.BACKDROP_TRANSITION_DURATION = 150

    QuadMenuModal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true
    }

    QuadMenuModal.prototype.toggle = function (_relatedTarget) {
        return this.isShown ? this.hide() : this.show(_relatedTarget)
    }

    QuadMenuModal.prototype.show = function (_relatedTarget) {
        var that = this
        var e = $.Event('show.quadmenu.modal', {relatedTarget: _relatedTarget})

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented())
            return

        this.isShown = true

        this.checkScrollbar()
        this.setScrollbar()
        this.$body.addClass('modal-open')

        this.escape()
        this.resize()

        this.$element.on('click.dismiss.quadmenu.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

        this.$dialog.on('mousedown.dismiss.quadmenu.modal', function () {
            that.$element.one('mouseup.dismiss.quadmenu.modal', function (e) {
                if ($(e.target).is(that.$element))
                    that.ignoreBackdropClick = true
            })
        })

        this.backdrop(function () {
            var transition = $.support.transition && that.$element.hasClass('fade')

            if (!that.$element.parent().length) {
                that.$element.appendTo(that.$body) // don't move modals dom position
            }

            that.$element
                    .show()
                    .scrollTop(0)

            that.adjustDialog()

            if (transition) {
                that.$element[0].offsetWidth // force reflow
            }

            that.$element.addClass('in')

            that.enforceFocus()

            var e = $.Event('shown.quadmenu.modal', {relatedTarget: _relatedTarget})

            transition ?
                    that.$dialog // wait for modal to slide in
                    .one('bsTransitionEnd', function () {
                        that.$element.trigger('focus').trigger(e)
                    })
                    .emulateTransitionEnd(QuadMenuModal.TRANSITION_DURATION) :
                    that.$element.trigger('focus').trigger(e)
        })
    }

    QuadMenuModal.prototype.hide = function (e) {
        if (e)
            e.preventDefault()

        e = $.Event('hide.quadmenu.modal')

        this.$element.trigger(e)

        if (!this.isShown || e.isDefaultPrevented())
            return

        this.isShown = false

        this.escape()
        this.resize()

        $(document).off('focusin.quadmenu.modal')

        this.$element
                .removeClass('in')
                .off('click.dismiss.quadmenu.modal')
                .off('mouseup.dismiss.quadmenu.modal')

        this.$dialog.off('mousedown.dismiss.quadmenu.modal')

        $.support.transition && this.$element.hasClass('fade') ?
                this.$element
                .one('bsTransitionEnd', $.proxy(this.hideQuadMenuModal, this))
                .emulateTransitionEnd(QuadMenuModal.TRANSITION_DURATION) :
                this.hideQuadMenuModal()
    }

    QuadMenuModal.prototype.enforceFocus = function () {
        $(document)
                .off('focusin.quadmenu.modal') // guard against infinite focus loop
                .on('focusin.quadmenu.modal', $.proxy(function (e) {
                    if (document !== e.target &&
                            this.$element[0] !== e.target &&
                            !this.$element.has(e.target).length) {
                        this.$element.trigger('focus')
                    }
                }, this))
    }

    QuadMenuModal.prototype.escape = function () {
        if (this.isShown && this.options.keyboard) {
            this.$element.on('keydown.dismiss.quadmenu.modal', $.proxy(function (e) {
                e.which == 27 && this.hide()
            }, this))
        } else if (!this.isShown) {
            this.$element.off('keydown.dismiss.quadmenu.modal')
        }
    }

    QuadMenuModal.prototype.resize = function () {
        if (this.isShown) {
            $(window).on('resize.quadmenu.modal', $.proxy(this.handleUpdate, this))
        } else {
            $(window).off('resize.quadmenu.modal')
        }
    }

    QuadMenuModal.prototype.hideQuadMenuModal = function () {
        var that = this
        this.$element.hide()
        this.backdrop(function () {
            that.$body.removeClass('modal-open')
            that.resetAdjustments()
            that.resetScrollbar()
            that.$element.trigger('hidden.quadmenu.modal')
        })
    }

    QuadMenuModal.prototype.removeBackdrop = function () {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
    }

    QuadMenuModal.prototype.backdrop = function (callback) {
        var that = this
        var animate = this.$element.hasClass('fade') ? 'fade' : ''

        if (this.isShown && this.options.backdrop) {
            var doAnimate = $.support.transition && animate

            this.$backdrop = $(document.createElement('div'))
                    .addClass('modal-backdrop ' + animate)
                    .appendTo(this.$body)

            this.$element.on('click.dismiss.quadmenu.modal', $.proxy(function (e) {
                if (this.ignoreBackdropClick) {
                    this.ignoreBackdropClick = false
                    return
                }
                if (e.target !== e.currentTarget)
                    return
                this.options.backdrop == 'static'
                        ? this.$element[0].focus()
                        : this.hide()
            }, this))

            if (doAnimate)
                this.$backdrop[0].offsetWidth // force reflow

            this.$backdrop.addClass('in')

            if (!callback)
                return

            doAnimate ?
                    this.$backdrop
                    .one('bsTransitionEnd', callback)
                    .emulateTransitionEnd(QuadMenuModal.BACKDROP_TRANSITION_DURATION) :
                    callback()

        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass('in')

            var callbackRemove = function () {
                that.removeBackdrop()
                callback && callback()
            }
            $.support.transition && this.$element.hasClass('fade') ?
                    this.$backdrop
                    .one('bsTransitionEnd', callbackRemove)
                    .emulateTransitionEnd(QuadMenuModal.BACKDROP_TRANSITION_DURATION) :
                    callbackRemove()

        } else if (callback) {
            callback()
        }
    }

    // these following methods are used to handle overflowing modals

    QuadMenuModal.prototype.handleUpdate = function () {
        this.adjustDialog()
    }

    QuadMenuModal.prototype.adjustDialog = function () {
        var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : '',
            paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : ''
        })
    }

    QuadMenuModal.prototype.resetAdjustments = function () {
        this.$element.css({
            paddingLeft: '',
            paddingRight: ''
        })
    }

    QuadMenuModal.prototype.checkScrollbar = function () {
        var fullWindowWidth = window.innerWidth
        if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
            var documentElementRect = document.documentElement.getBoundingClientRect()
            fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
        this.scrollbarWidth = this.measureScrollbar()
    }

    QuadMenuModal.prototype.setScrollbar = function () {
        var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
        this.originalBodyPad = document.body.style.paddingRight || ''
        if (this.bodyIsOverflowing)
            this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
    }

    QuadMenuModal.prototype.resetScrollbar = function () {
        this.$body.css('padding-right', this.originalBodyPad)
    }

    QuadMenuModal.prototype.measureScrollbar = function () { // thx walsh
        var scrollDiv = document.createElement('div')
        scrollDiv.className = 'modal-scrollbar-measure'
        this.$body.append(scrollDiv)
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
        this.$body[0].removeChild(scrollDiv)
        return scrollbarWidth
    }


    // MODAL PLUGIN DEFINITION
    // =======================

    function Plugin(option, _relatedTarget) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('bs.modal')
            var options = $.extend({}, QuadMenuModal.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if (!data)
                $this.data('bs.modal', (data = new QuadMenuModal(this, options)))
            if (typeof option == 'string')
                data[option](_relatedTarget)
            else if (options.show)
                data.show(_relatedTarget)
        })
    }

    var old = $.fn.modal

    $.fn.modal = Plugin
    $.fn.modal.Constructor = QuadMenuModal


    // MODAL NO CONFLICT
    // =================

    $.fn.modal.noConflict = function () {
        $.fn.modal = old
        return this
    }


    // MODAL DATA-API
    // ==============

    $(document).on('click.quadmenu.modal.data-api', '[data-quadmenu="modal"]', function (e) {
        var $this = $(this)
        var href = $this.attr('href')
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
        var option = $target.data('bs.modal') ? 'toggle' : $.extend({remote: !/#/.test(href) && href}, $target.data(), $this.data())

        if ($this.is('a'))
            e.preventDefault()

        $target.one('show.quadmenu.modal', function (showEvent) {
            if (showEvent.isDefaultPrevented())
                return // only register focus restorer if modal will actually get shown
            $target.one('hidden.quadmenu.modal', function () {
                $this.is(':visible') && $this.trigger('focus')
            })
        })
        Plugin.call($target, option, this)
    })

}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.7
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    // TAB CLASS DEFINITION
    // ====================

    var QuadMenuTab = function (element) {
        // jscs:disable requireDollarBeforejQueryAssignment
        this.element = $(element)
        // jscs:enable requireDollarBeforejQueryAssignment
    }

    QuadMenuTab.VERSION = '3.3.7'

    QuadMenuTab.TRANSITION_DURATION = 150

    QuadMenuTab.prototype.show = function () {
        var $this = this.element
        var $ul = $this.closest('ul:not(.dropdown-menu)')
        var selector = $this.data('target')

        if (!selector) {
            selector = $this.attr('href')
            selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
        }

        if ($this.parent('li').hasClass('active'))
            return

        var $previous = $ul.find('.active:last a')
        var hideEvent = $.Event('hide.quadmenu.tab', {
            relatedTarget: $this[0]
        })
        var showEvent = $.Event('show.quadmenu.tab', {
            relatedTarget: $previous[0]
        })

        $previous.trigger(hideEvent)
        $this.trigger(showEvent)

        if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented())
            return

        var $target = $(selector)

        this.activate($this.closest('li'), $ul)
        this.activate($target, $target.parent(), function () {
            $previous.trigger({
                type: 'hidden.quadmenu.tab',
                relatedTarget: $this[0]
            })
            $this.trigger({
                type: 'shown.quadmenu.tab',
                relatedTarget: $previous[0]
            })
        })
    }

    QuadMenuTab.prototype.activate = function (element, container, callback) {
        var $active = container.find('> .active')
        var transition = callback
                && $.support.transition
                && ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length)

        function next() {
            $active
                    .removeClass('active')
                    .find('> .dropdown-menu > .active')
                    .removeClass('active')
                    .end()
                    .find('[data-quadmenu="tab"]')
                    .attr('aria-expanded', false)

            element
                    .addClass('active')
                    .find('[data-quadmenu="tab"]')
                    .attr('aria-expanded', true)

            if (transition) {
                element[0].offsetWidth // reflow for transition
                element.addClass('in')
            } else {
                element.removeClass('fade')
            }

            if (element.parent('.dropdown-menu').length) {
                element
                        .closest('li.dropdown')
                        .addClass('active')
                        .end()
                        .find('[data-quadmenu="tab"]')
                        .attr('aria-expanded', true)
            }

            callback && callback()
        }

        $active.length && transition ?
                $active
                .one('bsTransitionEnd', next)
                .emulateTransitionEnd(QuadMenuTab.TRANSITION_DURATION) :
                next()

        $active.removeClass('in')
    }


    // TAB PLUGIN DEFINITION
    // =====================

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('bs.tab')

            if (!data)
                $this.data('bs.tab', (data = new QuadMenuTab(this)))
            if (typeof option == 'string')
                data[option]()
        })
    }

    var old = $.fn.tab

    $.fn.tab = Plugin
    $.fn.tab.Constructor = QuadMenuTab


    // TAB NO CONFLICT
    // ===============

    $.fn.tab.noConflict = function () {
        $.fn.tab = old
        return this
    }


    // TAB DATA-API
    // ============

    var clickHandler = function (e) {
        e.preventDefault()
        Plugin.call($(this), 'show')
    }

    $(document)
            .on('click.quadmenu.tab.data-api', '[data-quadmenu="tab"]', clickHandler)
            .on('click.quadmenu.tab.data-api', '[data-quadmenu="pill"]', clickHandler)

}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.3.7
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================

    function transitionEnd() {
        var el = document.createElement('bootstrap')

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

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function (duration) {
        var called = false
        var $el = this
        $(this).one('bsTransitionEnd', function () {
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

        $.event.special.bsTransitionEnd = {
            bindType: $.support.transition.end,
            delegateType: $.support.transition.end,
            handle: function (e) {
                if ($(e.target).is(this))
                    return e.handleObj.handler.apply(this, arguments)
            }
        }
    })

}(jQuery);

(function ($) {

    var menuItems = {}, xhr;
    $.fn.serializeArrayAll = function () {
        var rCRLF = /\r?\n/g;
        return this.map(function () {
            return this.elements ? jQuery.makeArray(this.elements) : this;
        }).map(function (i, elem) {
            var val = jQuery(this).val();
            if (val == null) {
                return val == null
                //next 2 lines of code look if it is a checkbox and set the value to blank 
                //if it is unchecked
            } else if (this.type == "checkbox" && this.checked == false) {
                return {name: this.name, value: this.checked ? this.value : ""}
                //next lines are kept from default jQuery implementation and 
                //default to all checkboxes = on
            } else {
                return jQuery.isArray(val) ?
                        jQuery.map(val, function (val, i) {
                            return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                        }) :
                        {name: elem.name, value: val.replace(rCRLF, "\r\n")};
            }
        }).get();
    };
    $.fn.extend({
        quadmenuItemData: function (itemType, id) {
            itemType = itemType || 'menu-item';
            var itemData = {}, i,
                    fields = [
                        'menu-item-db-id',
                        'menu-item-object-id',
                        'menu-item-object',
                        'menu-item-parent-id',
                        'menu-item-position',
                        'menu-item-type',
                        'menu-item-title',
                        'menu-item-url',
                        'menu-item-description',
                        'menu-item-attr-title',
                        'menu-item-target',
                        'menu-item-classes',
                        'menu-item-xfn',
                        'menu-item-quadmenu',
                        'menu-item-quadmenu-widget',
                    ];
            if (!id && itemType == 'menu-item') {
                id = this.find('.menu-item-data-db-id').val();
            }

            if (!id)
                return itemData;
            this.find('input').each(function () {
                var field;
                i = fields.length;
                while (i--) {
                    if (itemType == 'menu-item')
                        field = fields[i] + '[' + id + ']';
                    else if (itemType == 'add-menu-item')
                        field = 'menu-item[' + id + '][' + fields[i] + ']';
                    if (
                            this.name &&
                            field == this.name
                            ) {
                        itemData[fields[i]] = this.value;
                    }
                }
            });
            return itemData;
        }
    });
    var add_submit_events = function (submit) {

        submit.on('click', function (e) {
            e.preventDefault();
            var menuItems = {},
                    $spinner = submit.parent().find('.spinner'),
                    $parent = submit.parent();
            menuItems[ -1] = {
                'menu-item-status': 'publish',
                'menu-item-title': submit.data('menu_item_title').replace(/\%/g, submit.siblings().length),
                'menu-item-url': submit.data('menu_item_url'),
                'menu-item-type': submit.data('menu_item_type'),
                'menu-item-quadmenu': submit.data('menu_item_quadmenu'),
                'menu-item-parent-id': submit.data('menu_item_parent_id'),
            };
            add_nav_menu_item($spinner, $parent, menuItems);
        });
    };
    var add_action_events = function (action) {

        action.on('click', '.edit:first', function (e) {

            e.preventDefault();
            e.stopPropagation();
            action.toggleClass('open');
            $('.quadmenu-column-item').not(action).removeClass('open');
        });
        action.on('click', '.remove:first', function (e) {

            var $form = $('form', action);
            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: $.param($form.serializeArrayAll()) + '&' + $.param({
                    menu_id: $('#menu').val(),
                    menu_item_id: $form.data('menu_item_id'),
                    action: 'quadmenu_remove_nav_menu_item',
                    nonce: quadmenu.nonce}
                ),
                beforeSend: function () {
                    action.addClass('saving');
                },
                complete: function () {
                    action.removeClass('saving').fadeOut().remove();
                },
                success: function (response) {

                    console.log(response.data);
                    action.trigger('remove.quadmenu.item', [action]);
                },
            });
        });
        return action;
    };
    var add_settings_events = function (item) {

        if (item.hasClass('quadmenu-tab-pane-width')) {

            var width = $('.quadmenu-setting-width', item);

            if ($('#menu-item-stretch').val() !== '') {
                width.hide();
            }

            item.on('change', '.menu-item-stretch', function (e) {

                if ($(this).val() == '') {
                    width.fadeIn();
                } else {
                    width.fadeOut();
                }

            });

        }

        item.on('change', '.menu-item-icon', function (e) {

            var id = $(this).closest('form').data('menu_item_id'),
                    icon = $(this).val(),
                    change = $('#quadmenu-title-' + id, item);
            setTimeout(function () {
                change.find('i').removeClass().addClass(icon);
            }, 200);
        });
        item.on('keyup', '.menu-item-title', function (e) {

            var id = $(this).closest('form').data('menu_item_id'),
                    title = $(this).val(),
                    change = $('#quadmenu-title-' + id, item);
            setTimeout(function () {
                change.find('span').text(title);
            }, 200);
        });
    };
    var add_ajax_settings = function (modal) {

        modal.on('show.quadmenu.tab', 'li', function (e) {

            var $tabs = $(this),
                    $target = $($(e.target).attr('href')),
                    $spinner = $target.find('.spinner').first();
            if ($target.data('loading') || $target.data('loaded') || !$tabs.data('menu_item_panel'))
                return;
            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: $.param({
                    menu_id: $('#menu').val(),
                    menu_item_id: $tabs.data('menu_item_id'),
                    menu_item_panel: $tabs.data('menu_item_panel'),
                    action: 'quadmenu_add_nav_menu_item_panel',
                    nonce: quadmenu.nonce
                }),
                beforeSend: function () {
                    $target.data('loading', true);
                    $spinner.addClass('is-active');
                },
                complete: function () {
                    $target.removeData('loading');
                    $spinner.removeClass('is-active');
                },
                success: function (response) {

                    if (response.success !== true) {
                        alert(response.data);
                        return;
                    }

                    var $response = $(response.data);
                    $target.data('loaded', true);
                    $target.append($response);
                    //add_modal_events($target);
                    //add_save_events($target);
                    //add_icon_events($target);
                    //add_settings_events($target);
                    //add_media_events($target);
                    //add_background_events($target);
                    //add_sortable_events($target);
                },
            });
        });
    }

    var add_media_events = function (modal) {

        modal.on('click', '.menu-item-media-upload', function (e) {
            e.preventDefault();
            var media_uploader = null;
            var $wrapper = $(this).closest('.field-wrapper'),
                    $form = $(this).closest('form'),
                    $preview = $(this).siblings('.edit-menu-item-media-thumbnail'),
                    $background_thumbnail_id = $(this).siblings('.edit-menu-item-media-thumbnail-id');
            media_uploader = wp.media({
                button: {
                    text: quadmenu.add_background
                },
                library: {
                    type: 'image'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });
            media_uploader.on('open', function () {

                var selection = media_uploader.state().get('selection');
                var selected = $background_thumbnail_id.val();
                if (selected) {
                    selection.add(wp.media.attachment(selected));
                }

            });
            media_uploader.on('select', function () {

                var json = media_uploader.state().get('selection').first().toJSON(),
                        url = typeof json.sizes.thumbnail !== 'undefined' ? json.sizes.full.url : json.url,
                        src = typeof json.sizes.thumbnail !== 'undefined' ? json.sizes.thumbnail.url : json.url;
                //position = json.position || 'top left',
                //repeat = json.repeat || 'no-repat',
                //attachment = json.attachment || 'fixed',
                //size = json.size || 'contain';

                $wrapper.addClass('active');
                $preview.html('<img src="' + src + '" width="50" height="50" >');
                $background_thumbnail_id.val(json.id).data('src', url).trigger('change');
                //$background_position.val(position);
                //$background_repeat.val(repeat);
                //$background_attachment.val(attachment);
                //$background_size.val(size);
                //$css.html(position + ',' + repeat + ',' + attachment + ',' + size);
                $form.trigger('change');
            });
            media_uploader.open();
        });
        modal.on('click', '.menu-item-media-clear', function (e) {
            e.preventDefault();
            var $wrapper = $(this).closest('.field-wrapper'),
                    $css = $(this).siblings('.edit-menu-item-media-css'),
                    $preview = $(this).siblings('.edit-menu-item-media-thumbnail'),
                    $background_thumbnail_id = $(this).siblings('.edit-menu-item-media-thumbnail-id');
            //$background_position = $(this).siblings('.edit-menu-item-media-position'),
            //$background_repeat = $(this).siblings('.edit-menu-item-media-repeat'),
            //$background_attachment = $(this).siblings('.edit-menu-item-media-attachment'),
            //$background_size = $(this).siblings('.edit-menu-item-media-size');

            $wrapper.removeClass('active');
            $preview.html('');
            $background_thumbnail_id.val('').removeData('src').trigger('change');
            //$background_position.val('');
            //$background_repeat.val('');
            //$background_attachment.val('');
            //$background_size.val('');

            $css.html('');
            return false;
        });
    };
    var add_background_events = function (modal) {

        var background = $('.quadmenu-setting-background .field-background', modal);
        modal.on('change', '.edit-menu-item-background-position', function (e) {
            e.preventDefault();
            background.css('background-position', $(this).val());
        });
        modal.on('change', '.edit-menu-item-background-size', function (e) {
            e.preventDefault();
            background.css('background-size', $(this).val());
        });
        modal.on('change', '.edit-menu-item-background-repeat', function (e) {
            e.preventDefault();
            background.css('background-repeat', $(this).val());
        });
        modal.on('change', '.edit-menu-item-media-thumbnail-id', function (e) {
            e.preventDefault();
            if (!$(this).data('src'))
                return;
            background.css('background-image', 'url(' + $(this).data('src') + ')');
        });
    };
    var add_icon_events = function (item) {

        $('.quadmenu-setting-icon, .quadmenu-setting-login, .quadmenu-setting-logout', item).each(function (e) {

            var $setting = $(this),
                    $button = $setting.find('.button-icon'),
                    $input = $('.menu-item-icon, .menu-item-login, .menu-item-logout', $setting),
                    $icon = $setting.find('.quadmenu-icons-scroll .icon'),
                    $search = $setting.find('input[type="search"]');
            $search.on('keyup', function (e) {
                e.preventDefault();
                setTimeout(function () {
                    var icon_query = $search.val();
                    if (icon_query !== '') {
                        $icon.css({'display': 'none'});
                        $icon.filter('[class*="' + icon_query + '"]').css({'display': 'block'});
                    } else {
                        $icon.removeAttr('style');
                    }
                }, 600);
            });
            $icon.on('click', function (e) {
                e.preventDefault();
                $(this).addClass('selected').siblings().removeClass('selected');
                var icon_class = $(this).find('i').attr('class');
                $input.add($search).val(icon_class).trigger('change');
                $button.find('i').removeClass().addClass(icon_class);
            });
            $button.add($search).on('click', function (e) {
                $search.val('').trigger('keyup');
            });
        });
    };
    var add_column_events = function (column) {

        column.on('change', '.quadmenu-setting-width', function (e) {

            e.preventDefault();
            var width = $(this);
            setTimeout(function () {
                var options = width.find('option:selected');
                var cols = $.map(options, function (option) {

                    if (option.value)
                        return option.value;
                });
                var regex = /(\s)*(col-.*?)(?=\s)/g;
                column[0].className = column[0].className.replace(regex, '');
                column.addClass(cols.join(' '));
            }, 400);
        });
        column.on('click', '.contract:first', function (e) {

            var current = column.find('select#menu-item-columns-width-sm > option:selected');
            if (current.prev().length) {
                current.prev().prop('selected', true).trigger('change');
            }

        });
        column.on('click', '.expand:first', function (e) {

            var current = column.find('select#menu-item-columns-width-sm > option:selected');
            if (current.next().length) {
                current.next().prop('selected', true).trigger('change');
            }

        });
        column.on('click', '.add-quadmenu-column-item', function (e) {

            var current = $(this);

            current.addClass('selected');

            $('.quadmenu-column-item').not($('.quadmenu-column-item', $(this))).removeClass('open');

            $('.add-quadmenu-column-item').not(this).removeClass('selected');

            $('#menu-settings-column li').each(function () {

                var clone = $(this).find('.submit-add-to-quadmenu-column-inside'),
                        button = $(this).find('.submit-add-to-menu, .submit-add-to-quadmenu');

                $('#quadmenu_custom_nav_widgets').addClass('active');

                if (!clone.length) {
                    var clone = button.clone();
                    clone.val(quadmenu.button);
                    clone.removeClass('submit-add-to-menu submit-add-to-quadmenu button-secondary').addClass('submit-add-to-quadmenu-column-inside button-primary');
                    button.before(clone);
                }

                clone.data('menu_item_parent_id', current.data('menu_item_parent_id'));

            });

            $('#menu-settings-column li').addClass('current-column').find('li input:checked').prop('checked', false);
        });
        return column;
    };
    var add_widget_events = function (widget) {

        $('form', widget).off('change.quadmenu.settings');

        widget.on('submit', 'form', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $form = $(this);
            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: $.param($form.serializeArrayAll()) + '&' + $.param({
                    menu_item_id: $form.data('menu_item_id'),
                    action: 'quadmenu_save_widget',
                    nonce: quadmenu.nonce}
                ),
                beforeSend: function () {
                    widget.addClass('saving');
                },
                complete: function () {
                    widget.removeClass('saving');
                },
                success: function (response) {

                    wpNavMenu.menusChanged = false;
                    console.log(response.data);
                },
            });
        });
        widget.on('click', '.edit:first', function (e) {

            var $widget = widget.find('.widget'),
                    $form = widget.find('form');

            if ($widget.hasClass('open') || $widget.data('loaded'))
                return;

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: $.param({
                    widget: widget.data('widget'),
                    widget_id: widget.data('widget_id'),
                    menu_item_id: $form.data('menu_item_id'),
                    action: 'quadmenu_form_widget',
                    nonce: quadmenu.nonce
                }),
                beforeSend: function () {
                    widget.addClass('saving');
                },
                complete: function () {
                    widget.removeClass('saving');
                },
                success: function (response) {

                    if (response.success !== true) {
                        alert(response.data);
                        return;
                    }

                    var $response = $(response.data);
                    $form.html($response);
                    $widget.toggleClass('open').data('loaded', true);
                    setTimeout(function () {
                        $(document).trigger('widget-added', [$widget]);
                    }, 200);
                },
            });
        });
    };
    var add_sortable_events = function (modal) {

        $('.sortable-area', modal).each(function () {

            var $sortable = $(this);
            $sortable.sortable({
                forcePlaceholderSize: true,
                items: $sortable.data('sortable-items') || false,
                handle: $sortable.data('sortable-handle') || false,
                connectWith: $sortable.data('sortable-connect') || false,
                placeholder: $sortable.data('drop-area') || 'drop-area',
                over: function (event, ui) {
                    $(this).closest('.quadmenu-column').addClass('hover');
                },
                out: function (event, ui) {
                    $(this).closest('.quadmenu-column').removeClass('hover');
                },
                update: function (event, ui) {

                    if (this === ui.item.parent()[0]) {

                        if (ui.sender == null) {

                            var c = $(this),
                                    t = $(ui.item);
                            $($sortable.data('sortable-items'), c).each(function (i, el) {
                                menuItems[$(this).data('menu_item_id')] = {
                                    'menu-item-position': i,
                                    'menu-item-parent-id': c.data('menu_item_parent_id')
                                };
                            });
                            $.ajax({
                                type: 'post',
                                url: ajaxurl,
                                data: {
                                    action: 'quadmenu_update_nav_menu_item',
                                    nonce: quadmenu.nonce,
                                    menu_id: $('#menu').val(),
                                    'menu-item': menuItems
                                },
                                beforeSend: function () {
                                    t.addClass('saving');
                                },
                                complete: function () {
                                    t.removeClass('saving');
                                },
                                success: function (response) {
                                    console.log(response.data);
                                }
                            });
                        }
                    }
                },
                receive: function (event, ui) {

                    var c = $(this),
                            t = $(ui.item);
                    c.find('> li').each(function (i, el) {
                        menuItems[$(this).data('menu_item_id')] = {
                            'menu-item-position': i,
                            'menu-item-parent-id': c.data('menu_item_parent_id')
                        };
                    });
                    $.ajax({
                        type: 'post',
                        url: ajaxurl,
                        data: {
                            action: 'quadmenu_update_nav_menu_item',
                            nonce: quadmenu.nonce,
                            menu_id: $('#menu').val(),
                            'menu-item': menuItems
                        },
                        beforeSend: function () {
                            t.addClass('saving');
                        },
                        success: function (response) {
                            t.removeClass('saving');
                        }
                    });
                },
                start: function (event, ui) {
                    ui.item.data('start_pos', ui.item.index());
                    ui.placeholder.css({'height': ui.item.outerHeight() + 'px', 'width': ui.item.width() + 'px'});
                },
                stop: function (event, ui) {
                    // clean up
                    ui.item.removeAttr('style');
                    var start_pos = ui.item.data('start_pos');
                    if (start_pos !== ui.item.index()) {
                        //$columns.trigger('reorder_columns');
                    }
                }
            });
        });
    }

    var add_save_events = function (panel) {

        var $modal = $('#quadmenu-settings');

        panel.on('change.quadmenu.settings', 'form', function (e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: $.param($form.serializeArrayAll()) + '&' + $.param({
                    menu_id: $('#menu').val(),
                    menu_item_id: $form.data('menu_item_id'),
                    action: 'quadmenu_save_nav_menu_item_settings',
                    nonce: quadmenu.nonce}
                ),
                beforeSend: function () {
                    $modal.addClass('saving');
                },
                complete: function () {
                    $modal.removeClass('saving');
                },
                success: function (response) {
                    console.log(response.data);

                    wpNavMenu.menusChanged = false;

                    $form.trigger('save.quadmenu.settings', [$form.data('menu_item_id'), $form.serializeArrayAll()]);
                }
            });
        });
    };
    var add_modal_events = function (modal) {

        var $tabs = $('.quadmenu-tabs', modal),
                $columns = $('.quadmenu-columns', modal);
        
        $('.quadmenu-tab, .quadmenu-panel', $tabs).each(function (i, tab) {
            add_action_events($(tab));
        });
        $('.quadmenu-column', $columns).each(function (i, column) {
            add_action_events($(column));
            add_column_events($(column));
        });
        $('.quadmenu-column-item', $columns).each(function (i, item) {
            add_action_events($(item));
        });
        $('.quadmenu-widget', $columns).each(function (i, widget) {
            add_widget_events($(widget));
        });
        $('.submit-add-to-quadmenu-column, .submit-add-to-quadmenu-tab, .submit-add-to-quadmenu-panel', modal).each(function (i, add) {
            add_submit_events($(add));
        });
    };
    var add_nav_menu_item = function (spinner, div, menuItems) {

        var $spinner = $(spinner),
                $div = $(div);
        if (!$div.length)
            return false;
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'quadmenu_add_nav_menu_item',
                nonce: quadmenu.nonce,
                menu_id: $('#menu').val(),
                'menu-item': menuItems
            },
            beforeSend: function () {
                $spinner.addClass('is-active');
            },
            complete: function () {
                $spinner.removeClass('is-active');
            },
            success: function (response) {

                if (response.success !== true) {
                    alert(response.data);
                    return;
                }

                var $response = $('<ul>' + response.data + '</ul>');
                $('.quadmenu-tab, .quadmenu-panel, .quadmenu-column, .quadmenu-column-item', $response).each(function () {
                    add_action_events($(this));
                    add_icon_events($(this));
                    add_sortable_events($(this));
                    add_save_events($(this));
                    if ($(this).hasClass('quadmenu-widget')) {
                        add_widget_events($(this));
                    }

                    if ($(this).hasClass('quadmenu-column')) {
                        add_column_events($(this));
                    }

                });
                $('.submit-add-to-quadmenu-column, .submit-add-to-quadmenu-tab, .submit-add-to-quadmenu-panel', $response).each(function () {
                    add_submit_events($(this));
                });
                $div.append($response.contents()).trigger('add.quadmenu.item', [$div]);
            },
        });
    };
    // Submit
    // -------------------------------------------------------------------------

    $(document).on('click.quadmenu.submit', '.submit-add-to-quadmenu, .submit-add-to-quadmenu-column-inside', function (e) {
        e.preventDefault();

        var t = $(this),
                menuItems = {},
                $panel = $('#' + e.target.id.replace(/submit-/, '')),
                $spinner = t.parent().find('.spinner'),
                $checked = $panel.find('.tabs-panel-active .categorychecklist li input:checked');

        var menu_item_parent_id = t.data('menu_item_parent_id') || false,
                $div = menu_item_parent_id ? $('#quadmenu-column-items-' + menu_item_parent_id) : $('#menu-to-edit');

        if (!$div.length)
            return false;

        if (e.target.id && 'submit-customlinkdiv' == e.target.id) {

            var url = $('#custom-menu-item-url').val(),
                    label = $('#custom-menu-item-name').val();
            if ('' === url || 'http://' == url) {
                $('#customlinkdiv').addClass('form-invalid');
                return false;
            }

            menuItems[ -1] = {
                'menu-item-status': 'publish',
                'menu-item-type': 'custom',
                'menu-item-url': url,
                'menu-item-title': label,
                'menu-item-parent-id': menu_item_parent_id,
            }


        } else if (e.target.id && -1 != e.target.id.indexOf('submit-') && $checked.length) {

            $checked.each(function () {
                var t = $(this),
                        re = /menu-item\[([^\]]*)/,
                        listItemDBIDMatch = re.exec(t.attr('name')),
                        listItemDBID = 'undefined' == typeof listItemDBIDMatch[1] ? 0 : parseInt(listItemDBIDMatch[1], 10);
                menuItems[listItemDBID] = t.closest('li').quadmenuItemData('add-menu-item', listItemDBID);
                menuItems[listItemDBID]['menu-item-status'] = 'publish';
                menuItems[listItemDBID]['menu-item-parent-id'] = menu_item_parent_id;
            });
        } else {
            return false;
        }

        add_nav_menu_item($spinner, $div, menuItems);
    });
    // Custom Events
    // -------------------------------------------------------------------------

    $(document).on('click', function (e) {

        if (!$(e.target).is('.add-quadmenu-column-item') && !$(e.target).is('.submit-add-to-quadmenu-column-inside') && !$(e.target).closest('#menu-settings-column').length) {
            $('.add-quadmenu-column-item').removeClass('selected');
            $('#menu-settings-column li').removeClass('current-column');
            $('#quadmenu_custom_nav_widgets').removeClass('active').removeClass('open');
        }
    });
    $(document).on('click.quadmenu.dropdown', '.dropdown', function (e) {

        $(this).addClass('open');
        $('.dropdown').not($(this)).removeClass('open');
    });
    $(document).on('shown.quadmenu.modal', '#modal_icons', function (e) {

        add_icon_events($(this));
        var $modal = $(this),
                $save = $modal.find('.save'),
                $item = $(e.relatedTarget);

        $modal.data('target', $item.attr('id'));

        $save.on('click', function (e) {
            e.preventDefault();
            var icon = $('.menu-item-icon', $modal).val();

            if (!$modal.data('target'))
                return;
            var $target = $('input#' + $modal.data('target')),
                    $replace = $target.closest('.redux-field'),
                    $h3 = $replace.find('h3'),
                    $i = $h3.find('i');

            $target.val(icon);

            if ($i.length) {
                $i.remove();
            }

            $h3.prepend('<i class="' + icon + '"></i>');

            $modal.modal('hide');
        });
    });
    $(document).on('add.quadmenu.item', function (e, current) {
        current.find('.open').not(':last').removeClass('open');
    });
    $(document).on('remove.quadmenu.item', function (e, current) {

        e.preventDefault();
        e.stopPropagation();

        if (!current.hasClass('quadmenu-panel') && !current.hasClass('quadmenu-tab'))
            return;

        var tab = current.closest('.dropdown'),
                ul = current.closest('.quadmenu-tabs');

        if (!tab.length)
            return;

        tab.fadeOut().remove();

        setTimeout(function () {
            ul.find('.dropdown').removeClass('open').first().addClass('open');
        }, 200);
    });
    // Settings
    // -------------------------------------------------------------------------

    $(document).on('save.quadmenu.settings', '.quadmenu-tab-pane-default', function (e, id, fields) {
        $(fields).each(function (i, field) {
            $('#edit-' + field.name + '-' + id).val(field.value).trigger('change');
        });
    });
    $(document).on('click.quadmenu.close', '.quadmenu_close', function (e) {

        var $menu = $('#menu-management'),
                $settings = $('#quadmenu-settings');
        $settings.remove();
        $menu.removeClass('open');
    });
    $(document).on('click.quadmenu.open', '.quadmenu_open', function (e) {
        e.preventDefault();
        var $li = $(this).closest('li'),
                $spinner = $(this).parent().find('.spinner'),
                menu_item_id = $li.find('.menu-item-settings input.menu-item-data-db-id').val(),
                menu_item_depth = parseInt($li.prop('class').match(/menu-item-depth-([0-9]+)/)[1]);
        var $menu = $('#menu-management'),
                $modal = $('#post-body', $menu);
        if (xhr && xhr.readyState != 4)
            xhr.abort();
        if ($li.data('openning'))
            return false;
        xhr = $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                menu_id: $('#menu').val(),
                menu_item_id: menu_item_id,
                menu_item_depth: menu_item_depth,
                action: 'quadmenu_add_nav_menu_item_settings',
                nonce: quadmenu.nonce,
            },
            beforeSend: function () {
                $spinner.addClass('is-active');
                $li.addClass('opening').data('openning', true);
            },
            complete: function () {
                $spinner.removeClass('is-active');
                $li.removeClass('openning').removeData('openning');
            },
            success: function (response) {

                if (response.success !== true) {
                    console.log(response);
                    return;
                }

                $menu.addClass('open');
                $modal.prepend(response.data);
                add_ajax_settings($modal);
                add_save_events($modal);
                add_modal_events($modal);
                add_icon_events($modal);
                add_settings_events($modal);
                add_media_events($modal);
                add_background_events($modal);
                add_sortable_events($modal);
                $('html,body').animate({scrollTop: $('#nav-menu-header').offset().top}, 'slow');
            }
        });
    });

    // Themes
    // -------------------------------------------------------------------------

    //$(document).on('change', '#quadmenu_nav_menu_themes input.menu-item-checkbox', function (e) {
    /*$(document).on('change', '#quadmenu_nav_menu_themes', function (e) {
     e.preventDefault();
     
     var $current = $(this);
     
     $.ajax({
     type: 'post',
     url: ajaxurl,
     data: $.param($("[name^='quadmenu_themes']:checked").serializeArrayAll()) + '&' + $.param({
     menu_id: $('#menu').val(),
     action: 'quadmenu_change_nav_menu_theme',
     nonce: quadmenu.nonce
     }),    
     beforeSend: function () {
     },
     complete: function () {
     },
     success: function (response) {
     
     if (response.success !== true) {
     alert(response.data);
     return;
     }
     }
     });
     
     });*/

    $(document).on('click', 'ul.redux-group-menu > li', function (e) {
        e.preventDefault();

        var $current = $(this);

        if (!$current.hasClass('active'))
            return;

        var current_class = $current.attr('class').match(/quadmenu_theme_[\w-]*\b/);

        if (current_class) {

            var current_theme = current_class.toString().replace('quadmenu_theme_', '');

            $('#quadmenu_duplicate_theme').data('theme', current_theme).fadeIn();

            $('#quadmenu_delete_theme').data('theme', current_theme).fadeIn();

            return;
        }

        $('#quadmenu_duplicate_theme').fadeOut();

        $('#quadmenu_delete_theme').fadeOut();

    });

    $(document).on('click', '#quadmenu_add_theme ,#quadmenu_delete_theme', function (e) {
        e.preventDefault();

        var $this = $(this),
                $spinner = $(this).parent().parent().find('.spinner'),
                current_theme = $this.data('theme');

        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: $this.attr('id'),
                nonce: quadmenu.nonce,
                current_theme: current_theme,
            },
            beforeSend: function () {
                $spinner.addClass('is-active');
            },
            complete: function () {
                $spinner.removeClass('is-active');
            },
            success: function (response) {

                if (response.success !== true) {
                    alert(response.data);
                    return;
                }

                window.location.href = response.data;
            }
        });
    });

    // Import
    // -------------------------------------------------------------------------

    $(document).on('click.quadmenu.import', '.quadmenu_import', function (e) {
        e.preventDefault();
        var $this = $(this),
                $box = $this.closest('.theme'),
                $spinner = $box.find('.spinner');
        if ($box.data('importing'))
            return false;
        if (!$box.data('plugin'))
            return false;
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                plugin: $box.data('plugin'),
                action: 'quadmenu_compatibility_import',
                nonce: quadmenu.nonce,
            },
            beforeSend: function () {
                $spinner.addClass('is-active');
                $box.addClass('importing').data('importing', true);
            },
            complete: function () {
                $spinner.removeClass('is-active');
                $box.removeClass('importing').removeData('importing');
            },
            success: function (response) {

                if (response.success !== true) {
                    alert(response.data);
                    return;
                }

                window.location.href = response.data;
            }
        });
    });

    // Redux
    // -------------------------------------------------------------------------
    $(document).on('ready', function (e) {

        if ($('#last_tab').val() !== '') {
            $('ul.redux-group-menu > li.' + $('#last_tab').val() + ' > a').click();
            return;
        }

        $('li.redux-group-tab-link-li.empty_section').remove();

    });

    $('.redux-container-border .redux-color-init .redux-color').data('alpha', true);

})(jQuery);