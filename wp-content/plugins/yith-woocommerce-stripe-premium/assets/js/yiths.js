/* global Stripe, yith_stripe_info, woocommerce_params */

Stripe.setPublishableKey( yith_stripe_info.public_key );

(function ( $ ) {
    var mode = $('input[name="yith-stripe-mode"]').length ? $('input[name="yith-stripe-mode"]').val() : 'card';

    // save card data to prevent update checkout from woocommerce and save the data
    var card_name,
        card_number,
        card_cvc,
        card_expire;

    $('form.checkout, form#add_payment_method')
        .on( 'change', '#wc-yith-stripe-cc-form .wc-credit-card-form-card-name', function(){ card_name = $(this).val(); })
        .on( 'change', '#wc-yith-stripe-cc-form .wc-credit-card-form-card-number', function(){ card_number = $(this).val(); })
        .on( 'change', '#wc-yith-stripe-cc-form .wc-credit-card-form-card-cvc', function(){ card_cvc = $(this).val(); })
        .on( 'change', '#wc-yith-stripe-cc-form .wc-credit-card-form-card-expiry', function(){ card_expire = $(this).val(); })

        // backard compatibility
        .on( 'change', '#yith-stripe-cc-form .wc-credit-card-form-card-name', function(){ card_name = $(this).val(); })
        .on( 'change', '#yith-stripe-cc-form .wc-credit-card-form-card-number', function(){ card_number = $(this).val(); })
        .on( 'change', '#yith-stripe-cc-form .wc-credit-card-form-card-cvc', function(){ card_cvc = $(this).val(); })
        .on( 'change', '#yith-stripe-cc-form .wc-credit-card-form-card-expiry', function(){ card_expire = $(this).val(); });

    var stripeRestoreCardInformation = function(){
        $( '.wc-credit-card-form-card-name').val( card_name );
        $( '.wc-credit-card-form-card-number').val( card_number );
        $( '.wc-credit-card-form-card-cvc').val( card_cvc );
        $( '.wc-credit-card-form-card-expiry').val( card_expire );
    };

    // Form handler
    function stripeFormHandler( event ) {
        var $form = $( 'form.checkout, form#order_review, form#add_payment_method' );

        if ( $form.is('.add-card') || $( 'input#payment_method_yith-stripe' ).is( ':checked' ) && ( ! $( 'input[name="wc-yith-stripe-payment-token"]').length || $( 'input[name="wc-yith-stripe-payment-token"]:checked').val() == 'new' ) ) {

            if ( 0 === $( 'input.stripe-token' ).size() ) {

                $form.block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });

                var name_input   = $( '.wc-credit-card-form-card-name'),
                    name         = name_input.length ? name_input.val() : $('#billing_first_name' ).val() + ' ' + $('#billing_last_name' ).val(),
                    card_input   = $( '.wc-credit-card-form-card-number' ),
                    card         = card_input.val(),
                    cvc_input    = $( '.wc-credit-card-form-card-cvc' ),
                    cvc          = cvc_input.val(),
                    expiry_input = $( '.wc-credit-card-form-card-expiry'),
                    expiry       = $.payment.cardExpiryVal( expiry_input.val() ),
                    billing_country_input = $('#billing_country'),
                    billing_country = billing_country_input.val(),
                    billing_city_input = $('#billing_city:visible'),
                    billing_city = billing_city_input.val(),
                    billing_address_1_input = $('#billing_address_1:visible'),
                    billing_address_1 = billing_address_1_input.val(),
                    billing_address_2_input = $('#billing_address_2:visible'),
                    billing_address_2 = billing_address_2_input.val(),
                    billing_state_input = $('select#billing_state, input#billing_state:visible'),
                    billing_state = billing_state_input.val(),
                    billing_postcode_input = $('#billing_postcode:visible'),
                    billing_postcode = billing_postcode_input.val();

                card = card.replace( /\s/g, '' );

                var error = false,
                    fields = [];

                // Validate the name:
                if ( name == '' ) {
                    error = true;
                    fields.push( 'card.name' );
                    name_input.parents( 'p.form-row' ).addClass( 'error' );
                }

                // Validate the number:
                if ( ! Stripe.validateCardNumber( card ) ) {
                    error = true;
                    fields.push( 'card.number' );
                    card_input.parents( 'p.form-row' ).addClass( 'error' );
                }

                // Validate the CVC:
                if ( ! Stripe.validateCVC( cvc ) ) {
                    error = true;
                    fields.push( 'card.cvc' );
                    cvc_input.parents( 'p.form-row' ).addClass( 'error' );
                }

                // Validate the expiration:
                if ( ! Stripe.validateExpiry( expiry.month, expiry.year ) ) {
                    error = true;
                    fields.push( 'card.expire' );
                    expiry_input.parents( 'p.form-row' ).addClass( 'error' );
                }

                // validate extra fields
                if (
                    billing_country_input.closest('p.form-row.validate-required' ).length      && billing_country_input.length   && billing_country == ''
                    || billing_city_input.closest('p.form-row.validate-required' ).length      && billing_city_input.length      && billing_city == ''
                    || billing_address_1_input.closest('p.form-row.validate-required' ).length && billing_address_1_input.length && billing_address_1 == ''
                    || billing_state_input.closest('p.form-row.validate-required' ).length     && billing_state_input.length     && billing_state == ''
                    || billing_postcode_input.closest('p.form-row.validate-required' ).length  && billing_postcode_input.length  && billing_postcode == ''
                ) {
                    error = true;
                    fields.push( 'billing.fields' );
                    billing_country == ''   && billing_country_input.parents( 'p.form-row' ).addClass( 'error' );
                    billing_city == ''      && billing_city_input.parents( 'p.form-row' ).addClass( 'error' );
                    billing_address_1 == '' && billing_address_1_input.parents( 'p.form-row' ).addClass( 'error' );
                    billing_state == ''     && billing_state_input.parents( 'p.form-row' ).addClass( 'error' );
                    billing_postcode == ''  && billing_postcode_input.parents( 'p.form-row' ).addClass( 'error' );
                }

                if ( error ) {
                    stripeResponseHandler( 200, {
                        error: {
                            code: 'validation',
                            fieldErrors : fields
                        }
                    });

                    $('fieldset#wc-yith-stripe-cc-form input, fieldset#wc-yith-stripe-cc-form select, fieldset#yith-stripe-cc-form input, fieldset#yith-stripe-cc-form select').one( 'keydown', function() {
                        $(this).closest('p.form-row.error').removeClass('error');
                    });

                    $(document).trigger( 'yith-stripe-card-error' );
                }

                // go to payment
                else {
                    // Get the Stripe token:
                    Stripe.createToken({
                        number: card,
                        cvc: cvc,
                        exp_month: expiry.month,
                        exp_year: expiry.year,
                        name: name,
                        address_line1   : billing_address_1,
                        address_line2   : billing_address_2,
                        address_city    : billing_city,
                        address_state   : billing_state,
                        address_zip     : billing_postcode,
                        address_country : billing_country
                    }, stripeResponseHandler );
                }

                // Prevent the form from submitting
                return false;
            }
        }

        return event;
    }

    // Handle Stripe response
    function stripeResponseHandler( status, response ) {
        var $form  = $( 'form.checkout, form#order_review, form#add_payment_method' ),
            ccForm = $( '#wc-yith-stripe-cc-form, #yith-stripe-cc-form' );

        if ( response.error ) {

            // Show the errors on the form
            $( '.woocommerce-error, .stripe-token', ccForm ).remove();
            $form.unblock();

            if ( response.error.message ) {
                ccForm.prepend( '<ul class="woocommerce-error">' + response.error.message + '</ul>' );
            }

            // Show any validation errors
            else if ( 'validation' === response.error.code ) {
                var fieldErrors = response.error.fieldErrors,
                    fieldErrorsLength = fieldErrors.length,
                    errorList = '';

                for ( var i = 0; i < fieldErrorsLength; i++ ) {
                    errorList += '<li>' + yith_stripe_info[ fieldErrors[i] ] + '</li>';
                }

                ccForm.prepend( '<ul class="woocommerce-error">' + errorList + '</ul>' );
            }

        } else {

            // Insert the token into the form so it gets submitted to the server
            ccForm.append( '<input type="hidden" class="stripe-token" name="stripe_token" value="' + response.id + '"/>' );
            $form.submit();
        }
    }

    function populateBitcoinCheckout(status, response) {
        var $form = $( 'form.checkout, form#order_review' );

        $('.yith-stripe-mode-bitcoin .woocommerce-error').remove();

        if (status === 200) {
            document.getElementById("bitcoin_address").innerHTML = response.inbound_address;
        } else {
            $('.yith-stripe-mode-bitcoin').prepend( '<ul class="woocommerce-error"><li>' + response.error.message + '</li></ul>' );
        }

        console.log(response);
    }

    function stripeBitcoinFormInit() {
        var amount = $('input[name="bitcoin-amount"]').val(),
            email = $('input[name="billing_email"]').val(),
            currency = $('input[name="bitcoin-currency"]').val();

        Stripe.bitcoinReceiver.createReceiver({
            amount: amount,
            currency: currency,
            description: 'Socks for ' + currency,
            email: email
        }, populateBitcoinCheckout);
    }

    function stripeBitcoinFormHandler( event ) {

    }

    $(document).ready(function(){

        var change_card = function(){
            var $cards = $( '#payment').find( 'div.cards');
            if ( $cards.length ) {
                $cards.siblings( 'fieldset#wc-yith-stripe-cc-form, fieldset#yith-stripe-cc-form').hide();

                $( 'body' ).bind( 'updated_checkout', function() {
                    $( '#payment').find( 'div.cards').siblings( 'fieldset#wc-yith-stripe-cc-form, fieldset#yith-stripe-cc-form').hide();
                });

                $('form.checkout, form#order_review').on( 'change', '#payment input[name="wc-yith-stripe-payment-token"]', function(){
                    var input = $(this),
                        $cards = $( '#payment').find( 'div.cards');

                    // change selected
                    $cards.find('div.card').removeClass('selected');
                    $cards.find('input[name="wc-yith-stripe-payment-token"]:checked').closest('div.card').addClass('selected');

                    if ( input.val() == 'new' ) {
                        $cards.siblings( 'fieldset#wc-yith-stripe-cc-form, fieldset#yith-stripe-cc-form').show();
                    } else {
                        $cards.siblings( 'fieldset#wc-yith-stripe-cc-form, fieldset#yith-stripe-cc-form').hide();
                    }
                });
            }
        };

        var card_or_bitcoin = function(){
            $('form.checkout, form#order_review').on( 'change', 'input[name="yith-stripe-mode"]', function(){
                $('div[class^="yith-stripe-mode-"]').hide();
                $( '.' + $(this).attr('id')).show();
            });
        };

        /* Checkout Form */
        $( 'form.checkout' ).on( 'checkout_place_order_yith-stripe', function (e) {
            if ( mode == 'bitcoin' ) {
                return stripeBitcoinFormHandler(e);
            } else {
                return stripeFormHandler(e);
            }
        });

        /* Pay Page Form */
        $( 'form#order_review, form#add_payment_method' ).on( 'submit', function (e) {
            if ( mode == 'bitcoin' ) {
                return stripeBitcoinFormHandler(e);
            } else {
                return stripeFormHandler(e);
            }
        });

        /* Both Forms */
        $( 'form.checkout, form#order_review, form#add_payment_method' ).on( 'change', '#wc-yith-stripe-cc-form input, #yith-stripe-cc-form input', function() {
                $( '.stripe-token' ).remove();
            })

            /* Update bitcoin form when email change */
            .on( 'change', 'input[name="billing_email"]', function(){
                if ( $('.yith-stripe-mode-bitcoin').length ) {
                    stripeBitcoinFormInit();
                }
            });

        $( 'body' )

            .on( 'checkout_error', function () {
                $( '.stripe-token' ).remove();
            })

            .bind( 'updated_checkout', function() {
                if ( wc_checkout_params.option_guest_checkout === 'yes' && $( 'p.create-account').length ) {
                    $( 'div.create-account' ).hide();
                    $( 'input#createaccount' ).change();
                }

                if ( $('.yith-stripe-mode-bitcoin').length ) {
                    stripeBitcoinFormInit();
                }

                change_card();
            });

        change_card();
        card_or_bitcoin();

        // select2 country and state on caard form
        $( 'form.checkout, form#order_review' ).on( 'click', 'input[name=payment_method]', function(){
            $( document.body ).trigger( 'country_to_state_changed' );
        });

    });

    // cvv suggest lightbox
    var cvv_lightbox = function(){
        if ( typeof $.fn.prettyPhoto == 'undefined' ) {
            return;
        }

        $('.woocommerce #payment ul.payment_methods li, form#add_payment_method').find( 'a.cvv2-help' ).prettyPhoto({
            hook: 'data-rel',
            social_tools: false,
            theme: 'pp_woocommerce',
            horizontal_padding: 20,
            opacity: 0.8,
            deeplinking: false
        });
    };

    cvv_lightbox();
    $('body').on( 'updated_checkout', cvv_lightbox );
    $('body').on( 'updated_checkout', stripeRestoreCardInformation );

}( jQuery ) );