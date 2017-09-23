/* YITH WooCommerce Multi Step Checkout */
(function ($) {
    //yith_wcms.dom element are documented in /includes\class.yith-multistep-checkout-frontend-premium.php:187
    var $body = $('body'),
        login = $(yith_wcms.dom.login),
        billing = $(yith_wcms.dom.billing),
        shipping = $(yith_wcms.dom.shipping),
        order = $(yith_wcms.dom.order),
        payment = $(yith_wcms.dom.payment),
        form_actions = $(yith_wcms.dom.form_actions),
        coupon = $(yith_wcms.dom.coupon),
        create_account = $(yith_wcms.dom.create_account),
        create_account_wrapper = $(yith_wcms.dom.create_account_wrapper),
        steps = new Array(login, billing, shipping, order, payment),
        is_user_logged_in = $body.hasClass('logged-in'),
        ship_to_different_address_checkbox = $( yith_wcms.dom.ship_to_different_address ),
        cookie = {
            form: 'yith_wcms_checkout_form',
            step: 'yith_wcms_checkout_current_step'
        };

    $body.on('updated_checkout yith_wcms_myaccount_order_pay', function (e) {
        if (e.type == 'updated_checkout') {
            steps[4] = $(yith_wcms.dom.payment);
        }

        var current_step = form_actions.data('step');
        if (current_step == 4) {
            $(yith_wcms.dom.payment).show();
        }

        $body.trigger('yith_wcms_updated_checkout');
    });

    if ($body.hasClass('woocommerce-order-pay')) {
        $body.trigger('yith_wcms_myaccount_order_pay');
    }

    if( yith_wcms.live_fields_validation == 'yes' ){

        // radio validation
        var checkout_form = $(yith_wcms.dom.checkout_form),
            radio_validation = function() {
            var $this     = $( this ),
                $parent   = $this.closest( '.form-row' ),
                validated = true;

            if ( $parent.is( '.validate-required' ) ) {
                if ('radio' == $this.attr('type')) {
                    var radio_group_name = $this.attr('name');
                    if (!$('input[name=' + radio_group_name + ']').is(':checked')) {
                        $parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-required-field');
                        validated = false;
                    }
                }

                if (validated) {
                    $parent.removeClass('woocommerce-invalid woocommerce-invalid-required-field').addClass('woocommerce-validated');
                }
            }
        };

        $body.on( 'blur', 'input:radio', radio_validation );
    }

    //enable select2
    $body.on('yith_wcms_select2', function (event) {
        if ($().select2) {
            var wc_country_select_select2 = function () {
                $('select.country_select, select.state_select').each(function () {
                    var select2_args = {
                        placeholder      : $(this).attr('placeholder'),
                        placeholderOption: 'first',
                        width            : '100%'
                    };

                    $(this).select2(select2_args);
                });
            };

            wc_country_select_select2();

            $body.bind('country_to_state_changed', function () {
                wc_country_select_select2();
            });
        }
    });

    if (yith_wcms.wc_shipping_multiple != 1) {
        $body.trigger('yith_wcms_select2');
    }

    $('.yith-wcms-pro ' + yith_wcms.dom.checkout_timeline + ' li').on('click', function (e) {

        var t = $(this);

        if (t.hasClass('active')) {
            return false;
        }

        var current_step = $(yith_wcms.dom.checkout_timeline).find(yith_wcms.dom.active_timeline).data('step'),
            next_step = t.data('step'),
            prev_step = t.data('step') > current_step ? next_step - 1 : t.data('step'),
            action = t.data('step') > current_step ? form_actions.find(yith_wcms.dom.button_next) : form_actions.find(yith_wcms.dom.button_prev);

        if( yith_wcms.remove_shipping_step == 'yes' ){
            if( next_step == 2 ){
                next_step = next_step + 1;
            }

            if( prev_step == 2 ){
                prev_step = prev_step - 1;
            }
        }

        if (next_step == 0 && is_user_logged_in ) {
            return false;
        }

        change_step(action, current_step, next_step, prev_step);
    });

    form_actions.find(yith_wcms.dom.button_prev).add(yith_wcms.dom.button_next).on('click', function (e) {
        var t = $(this),
            current_step = form_actions.data('step'),
            next_step = current_step + 1,
            prev_step = current_step - 1;

        if( yith_wcms.remove_shipping_step == 'yes' ){
            if( next_step == 2 ){
                next_step = next_step + 1;
            }

            if( prev_step == 2 ){
                prev_step = prev_step - 1;
            }
        }

        change_step(t, current_step, next_step, prev_step);

    });

    var change_step = function (t, current_step, next_step, prev_step) {

        var timeline = $(yith_wcms.dom.checkout_timeline),
            action = t.data('action'),
            prev = form_actions.find(yith_wcms.dom.button_prev),
            next = form_actions.find(yith_wcms.dom.button_next),
            active_step = timeline.find('.active').data('step'),
            checkout_form = $(yith_wcms.dom.checkout_form);

        if( yith_wcms.is_scroll_top_enabled == 'yes' ){
            var scroll_top_anchor = $(yith_wcms.dom.scroll_top_anchor),
                pos = scroll_top_anchor.offset().top - scroll_top_anchor.outerHeight(true);

            $body.animate({
                scrollTop: pos
            }, 500);

        }

        var show_coupon = function (current_step) {
            // Your order
            if (current_step == 3) {
                coupon.fadeIn(yith_wcms.transition_duration);
            }

            else {
                coupon.fadeOut(yith_wcms.transition_duration);
            }
        };
        
        if( action == 'prev' && current_step == 1 ){
            next.val( yith_wcms.skip_login_label );
        }

        else{
            next.val( yith_wcms.next_label );
        }

        // live fields validation
        if( yith_wcms.live_fields_validation == 'yes' ){
            if ( ( active_step == 0 || active_step == 1 || active_step == 2 ) && action == 'next' ) {
                var checkout_form = $(yith_wcms.dom.checkout_form),
                    invalid_field = 0,
                    shipping_check = $(yith_wcms.dom.shipping_check);

                // Inline validation
                if( active_step == 1 || active_step == 2  ) {
                    checkout_form.find(yith_wcms.dom.required_fields_check).trigger('blur');
                }

                //billing or login step
                if (active_step == 1 || active_step == 0) {

                    //Added Support to YITH WooCommerce Coupon Email System
                    if( yith_wcms.is_coupon_email_system_enabled ){
                        var dob = $( yith_wcms.dom.day_of_birth );
                        if( typeof dob != 'undefined'){
                            var pattern = dob.attr( 'pattern' ),
                                regex = new RegExp( '^' + pattern + '$' , 'g' ),
                                dob_value = dob.val();

                            if( dob_value != '' && regex.test( dob_value ) == false ){
                                dob.parent().removeClass('woocommerce-validated').addClass( 'woocommerce-invalid-required-field' ).addClass('woocommerce-invalid');
                            }
                        }
                    }

                    invalid_field = billing.find(yith_wcms.dom.wc_invalid_required).size();
                    if( create_account.length != 0 && ! create_account.is(':checked') && invalid_field != 0 ) {
                        invalid_field = invalid_field - create_account_wrapper.find('.validate-required').length;
                    }
                }

                //shipping
                else if (active_step == 2 ) {
                    var shipping_check_exists = shipping_check.length;
                    if(
                        ( shipping_check_exists != 0 && shipping_check.is(':checked') )
                        ||
                        ( shipping_check_exists == 0 && shipping.find('input').length )
                    ){
                        invalid_field = shipping.find(yith_wcms.dom.wc_invalid_required).size();
                    }

                    //Added Support to WooCommerce Checkout Add-ons
                    if( yith_wcms.is_wc_checkout_addons_enabled ){
                        var wc_checkout_addons = $(yith_wcms.dom.wc_checkout_addons);
                        invalid_field = invalid_field + wc_checkout_addons.find(yith_wcms.dom.wc_invalid_required).size();
                    }

                    var extra_fields = shipping.find(yith_wcms.dom.additional_fields).find( '>' + yith_wcms.dom.wc_invalid_required).size();
                    invalid_field = invalid_field + extra_fields;
                }

                if (invalid_field != 0) {
                    if( active_step != 0 ){
                        return;
                    }

                    else {
                        next_step = 1;
                    }
                }
            }
        }


        timeline.find('.active').removeClass('active');

        if (action == 'next') {
            form_actions.data('step', next_step);
            steps[current_step].fadeOut(yith_wcms.transition_duration, function () {
                steps[next_step].fadeIn(yith_wcms.transition_duration);
                show_coupon(next_step);
            });

            $(yith_wcms.dom.timeline_id_prefix + next_step).toggleClass('active');
        }

        else if (action == 'prev') {
            form_actions.data('step', prev_step);
            steps[current_step].fadeOut(yith_wcms.transition_duration, function () {
                steps[prev_step].fadeIn(yith_wcms.transition_duration);
            });

            show_coupon(prev_step);
            $(yith_wcms.dom.timeline_id_prefix + prev_step).toggleClass('active');
        }

        current_step = form_actions.data('step');

        if( yith_wcms.use_cookie == true ){
            Cookies.set(cookie.step, current_step, { path: '/' });
        }

        // if current step is billing information and current user logged in or
        // current step is login and current user not logged in
        if (( current_step == 1 && is_user_logged_in == true ) ||
            ( is_user_logged_in == false && ( ( current_step == 0 && yith_wcms.checkout_login_reminder_enabled == 1 ) ||  ( current_step == 1 && yith_wcms.checkout_login_reminder_enabled == 0 ) ) )
        ) {
            prev.fadeOut(yith_wcms.transition_duration);
        }

        else {
            prev.fadeIn(yith_wcms.transition_duration);
        }

        // Last step
        if (current_step == 4) {
            checkout_form.removeClass( 'processing' );
            next.fadeOut(yith_wcms.transition_duration);
            if (yith_wcms.disabled_prev_button == 'yes') {
                prev.fadeOut(yith_wcms.transition_duration);
            }
        }

        else {
            checkout_form.addClass( 'processing' );
            next.fadeIn(yith_wcms.transition_duration);
        }
    };

    var preset_form_value   = Cookies.get(cookie.form),
        preset_current_step = Cookies.get(cookie.step);

    var set_cookie_value = function(){
        var form_temp = $('.checkout.woocommerce-checkout').serialize();
        Cookies.set(cookie.form, form_temp, { path: '/' } );
    };

    var cache_form_value = function () {
        $('#ship-to-different-address input').on('change', function(e){
            set_cookie_value();
        });

        $(yith_wcms.dom.checkout_form).find(yith_wcms.dom.required_fields_check).on('blur change', function (e) {
            set_cookie_value();
        });
    };

    var set_cached_value = function(preset_form_value, preset_current_step){
        if (typeof preset_form_value != 'undefined') {
            var form_temp = preset_form_value.split('&');
            for (var i in form_temp) {

                var elem = form_temp[i];

                if( typeof elem != 'string' ){
                    continue;
                }

                var form_value = elem.split('='),
                    input_field_name = decodeURIComponent('input[name="' + form_value[0] + '"]'),
                    input_field = $(input_field_name);

                if (typeof input_field != 'undefined') {
                    var cached_value = decodeURIComponent(form_value[1]).replace(/\+/g, ' ');
                    //Select2 Cached Value
                    if (yith_wcms.dom.select2_fields.indexOf(form_value[0]) != -1) {
                        var country_field = $('#' + decodeURIComponent(form_value[0]));
                        country_field.add(input_field).val(cached_value);
                        if (country_field.is('select')) {
                            country_field.val(cached_value).trigger('change');
                        }
                        else if (country_field.is('input')) {
                            input_field.val(cached_value);
                        }
                    }
                    else {
                        // skip cached value for nonce fields or other private WordPress fields
                        var skip_current_value = false;

                        if( form_value[0].indexOf( 'payment_method' ) != -1 && yith_wcms.skip_payment_method == true ){
                            skip_current_value = true;
                        }

                        if( form_value[0].indexOf( 'shipping_method' ) != -1 && yith_wcms.skip_shipping_method == true ){
                            skip_current_value = true;
                        }

                        if( form_value[0].indexOf('_wp') == -1 && cached_value && skip_current_value == false ){
                            if( input_field.prop( 'type' ) == 'checkbox' && cached_value == 1 ){
                                input_field.prop( 'checked', 'checked' );
                            }

                            else {

                                input_field.val(cached_value);
                            }
                        }
                    }
                }
            }
        }
    };

    if( yith_wcms.use_cookie == true ){
        cache_form_value();

        set_cached_value( preset_form_value, preset_current_step );

        $body.on('country_to_state_changed', function (e, value, obj) {
            cache_form_value();
        });
    }

    if (typeof Cookies.get(cookie.step) != 'undefined' && yith_wcms.use_cookie == true ) {
        $body.on('updated_checkout yith_wcms_myaccount_order_pay', function(){
            $('.yith-wcms-pro ' + yith_wcms.dom.checkout_timeline + ' li#timeline-' + Cookies.get(cookie.step)).trigger('click');
        });
    }

    //Delete cookie after order complete
    if( yith_wcms.is_order_received_endpoint == 1 && yith_wcms.use_cookie == true ){
        Cookies.remove(cookie.form, { path: '/' });
        Cookies.remove(cookie.step, { path: '/' });
    }
})(jQuery);