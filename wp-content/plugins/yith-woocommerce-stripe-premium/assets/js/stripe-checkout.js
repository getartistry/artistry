/* global Stripe, StripeCheckout, yith_stripe_info, woocommerce_params */

(function ( $ ) {

    var stripe_submit = false;

    // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
    $( 'form.checkout' ).on( 'checkout_place_order_yith-stripe', handleStripeCheckout );
    $( '#order_review' ).on( 'submit', handleStripeCheckout );
    $( document ).on( 'updated_checkout', refreshStripeParams );

    // check if stripe could submit or not
    function notStripeCheckout() {

        if ( ! $( '#payment_method_yith-stripe' ).is( ':checked' ) ) {
            return true;
        }

        if ( $( 'input#terms' ).length === 1 && $( 'input#terms:checked' ).lenght === 0 ) {
            return true;
        }

        if ( $( '#createaccount' ).is( ':checked' ) && $( '#account_password' ).length && $( '#account_password' ).val() === '' ) {
            return true;
        }

        // validate required fields
        if ( $( '#ship-to-different-address-checkbox' ).is( ':checked' ) ) {
            $required_inputs = $( '.woocommerce-billing-fields .validate-required:visible, .woocommerce-shipping-fields .validate-required:visible' );
        } else {
            $required_inputs = $( '.woocommerce-billing-fields .validate-required:visible' );
        }

        if ( $required_inputs.length ) {
            var required_error = false;

            $required_inputs.each( function() {
                if ( $( this ).find( 'input.input-text, select' ).not( $( '#account_password, #account_username' ) ).val() === '' ) {
                    required_error = true;
                }
            });

            if ( required_error ) {
                return true;
            }
        }

        return false;
    }

    function handleStripeCheckout() {

        if ( stripe_submit || notStripeCheckout() ) {
            return true; // don't interrupt submittal - allow it to proceed
        }

        // Capture submittal and open stripecheckout
        var $form            = $( 'form.checkout, form#order_review' ),
            token            = $form.find( 'input.stripe_token' );

        $form.addClass( 'processing' );

        var form_data = $form.data();

        if ( 1 !== form_data['blockUI.isBlocked'] ) {
            $form.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        }

        token.val( '' );

        var token_action = function( res ) {
            $form.find( 'input.stripe_token' ).remove();
            $form.append( '<input type="hidden" class="stripe_token" name="stripe_token" value="' + res.id + '"/>' );
            stripe_submit = true;
            $form.submit();
        };

        StripeCheckout.open({
            key:         yith_stripe_info.public_key,
            billingAddress:     false,
            amount:      yith_stripe_info.amount,
            name:        yith_stripe_info.name,
            description: yith_stripe_info.description,
            currency:    yith_stripe_info.currency,
            image:       yith_stripe_info.image,
            bitcoin:     yith_stripe_info.bitcoin == 'true',
            locale:      yith_stripe_info.locale,
            email: 		 $( document.body ).hasClass('woocommerce-order-pay') ? yith_stripe_info.billing_email : $( '#billing_email' ).val(),
            token:       token_action,
            opened:      function() {
                $form.removeClass( 'processing' ).unblock();
            }
        });

        return false;
    }

    function refreshStripeParams(){
        $.ajax( {
            data: {
                action: 'yith_stripe_refresh_details',
                yith_stripe_refresh_details: yith_stripe_info.refresh_details
            },
            dataType: 'json',
            method: 'POST',
            success: function( data ){
                console.log(data);
                yith_stripe_info.amount = data.amount;
                yith_stripe_info.currency = data.currency;
            },
            url: yith_stripe_info.ajaxurl
        } );
    }

}( jQuery ) );