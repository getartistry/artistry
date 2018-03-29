jQuery(function ($) {

    /**
     * Object to handle Stripe payment forms.
     */
    var eh_stripe_form = {
        /**
         * Initialize e handlers and UI state.
         */
        init: function (form) {
            this.form = form;
            this.stripe_submit = false;
            $(this.form)
                    // We need to bind directly to the click (and not checkout_place_order_stripe) to avoid popup blockers
                    // especially on mobile devices (like on Chrome for iOS) from blocking StripeCheckout.open from opening a tab
                    .on('click', '#place_order', this.onSubmit)

                    // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                    .on('submit checkout_place_order_stripe');
        },
        isStripeChosen: function () {
            return $('#payment_method_eh_stripe_pay').is(':checked');
        },
        isStripeModalNeeded: function (e) {

            if (getCookie('eh_alipay_payment')) {
                return false;
            }
            var token = eh_stripe_form.form.find('input.eh_stripe_pay_token');

            // If this is a stripe submission (after modal) and token exists, allow submit.
            if (eh_stripe_form.stripe_submit && token) {
                return false;
            }

            // Don't affect submission if modal is not needed.
            if (!eh_stripe_form.isStripeChosen()) {
                return false;
            }

            // Don't open modal if required fields are not complete
            if ($('input#terms').length === 1 && $('input#terms:checked').length === 0) {
                return false;
            }

            if ($('#createaccount').is(':checked') && $('#account_password').length && $('#account_password').val() === '') {
                return false;
            }

            // check to see if we need to validate shipping address
            if ($('#ship-to-different-address-checkbox').is(':checked')) {
                $required_inputs = $('.woocommerce-billing-fields .validate-required, .woocommerce-shipping-fields .validate-required');
            } else {
                $required_inputs = $('.woocommerce-billing-fields .validate-required');
            }

            if ($required_inputs.length) {
                var required_error = false;

                $required_inputs.each(function () {
                    if ($(this).find('input.input-text, select').not($('#account_password, #account_username')).val() === '') {
                        required_error = true;
                    }
                });

                if (required_error) {
                    return false;
                }
            }

            return true;
        },
        block: function () {
            eh_stripe_form.form.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        unblock: function () {
            eh_stripe_form.form.unblock();
        },
        onClose: function () {
            eh_stripe_form.unblock();
        },
        onSubmit: function (e) {
            if (eh_stripe_form.isStripeModalNeeded()) {
                e.preventDefault();

                // Capture submittal and open stripecheckout
                var $form = eh_stripe_form.form,
                        $data = $('#eh-stripe-pay-data'),
                        token = $form.find('input.eh_stripe_pay_token'),
                        card_brand = $form.find('input.eh_stripe_card_type'),
                        amount = $form.find('input.eh_stripe_pay_amount'),
                        currency = $form.find('input.eh_stripe_pay_currency');
                token.val('');
                card_brand.val('');
                currency.val('');
                amount.val('');
                var token_action = function (res) {
                    $form.find('input.eh_stripe_pay_token').remove();
                    $form.find('input.eh_stripe_card_type').remove();
                    $form.find('input.eh_stripe_pay_currency').remove();
                    $form.append('<input type="hidden" class="eh_stripe_pay_token" name="eh_stripe_pay_token" value="' + res.id + '"/>');
                    $form.append('<input type="hidden" class="eh_stripe_pay_currency" name="eh_stripe_pay_currency" value="' + $data.data('currency') + '"/>');
                    $form.append('<input type="hidden" class="eh_stripe_pay_amount" name="eh_stripe_pay_amount" value="' + $data.data('amount') + '"/>');
                    if (res.type === "card")
                    {
                        $form.append('<input type="hidden" class="eh_stripe_card_type" name="eh_stripe_card_type" value="' + res.card.brand + '"/>');
                    } else
                    {
                        $form.append('<input type="hidden" class="eh_stripe_card_type" name="eh_stripe_card_type" value="other"/>');
                    }
                    eh_stripe_form.stripe_submit = true;
                    $form.submit();
                };

                StripeCheckout.open({
                    key: eh_stripe_val.key,
                    billingAddress: $data.data('billing-address'),
                    amount: $data.data('amount'),
                    name: $data.data('name'),
                    description: $data.data('description'),
                    currency: $data.data('currency'),
                    image: $data.data('image'),
                    bitcoin: $data.data('bitcoin'),
                    alipay: $data.data('alipay'),
                    locale: $data.data('locale'),
                    zipCode: (eh_stripe_val.show_zip_code) ? true : false,
                    allowRememberMe: $data.data('allow-remember-me'),
                    email: $('#billing_email').val() || $data.data('email'),
                    panelLabel: $data.data('panel-label'),
                    token: token_action,
                    closed: eh_stripe_form.onClose()
                });

                return false;
            }

            return true;
        }
    };

    eh_stripe_form.init($("form.checkout, form#order_review, form#add_payment_method"));
});
function getCookie(name) {
    var cookieName = name + "=";
    var cookieArray = document.cookie.split(';');

    for (var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(cookieName) == 0) {
            return cookie.substring(cookieName.length, cookie.length);
        }
        return null;
    }
}