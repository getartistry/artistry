/* global eh_apple_pay_params, Stripe */
var stripe = Stripe(eh_alipay_params.key);
var alipay = [];
jQuery(function ($) {
    'use strict';

    /**
     * Object to handle Stripe payment forms.
     */
    var eh_alipay_gen = {
        /**
         * Initialize event handlers and UI state.
         */
        init: function () {
            stripe.createSource({
                    type: "alipay",
                    amount: eh_alipay_params.amount,
                    currency: eh_alipay_params.currency,
                    redirect: {
                      return_url: eh_alipay_params.redirect
                    }
                  }).then(function(result) {
                      alipay = result.source;
                  });
        },

        process_alipay: function () {
            window.location = alipay.redirect.url;
        }
    };
    function getCookie(name) {  
        var cookieName = name + "=";
        var cookieArray = document.cookie.split(';'); 

        for (var i = 0; i < cookieArray.length; i++){  
            var cookie = cookieArray[i];  
            while (cookie.charAt(0)==' '){ 
                    cookie = cookie.substring(1,cookie.length);
            }
            if (cookie.indexOf(cookieName) == 0){
                    return cookie.substring(cookieName.length,cookie.length);
            }
            return null;
        }  
    }
    $(document.body).ready(function(){
        if($( '#payment_method_eh_stripe_pay' ).is( ':checked' ) && (getCookie("eh_alipay_payment") === false || getCookie("eh_alipay_payment") === null))
        {
            jQuery(".place-order").append('<span class="button alt" style="text-align: center;" id="eh_alipay_payment">'+eh_alipay_params.button+'</span>');
        }
        else
        {
            jQuery("#eh_alipay_payment").remove();
        }
    });
    $(document.body).on('click',"#eh_alipay_payment", function () {
        eh_alipay_gen.process_alipay();
    });
    $(document.body).on('updated_cart_totals', function () {
        if($( '#payment_method_eh_stripe_pay' ).is( ':checked' ) && (getCookie("eh_alipay_payment") === false || getCookie("eh_alipay_payment") === null))
        {
            if(jQuery("#eh_alipay_payment").length === 0)
            {
                jQuery(".place-order").append('<span class="button alt" style="text-align: center;" id="eh_alipay_payment">'+eh_alipay_params.button+'</span>');
            }
        }
        else
        {
            jQuery("#eh_alipay_payment").remove();
        }
    });
    $(document.body).on('updated_checkout', function () {
        if($( '#payment_method_eh_stripe_pay' ).is( ':checked' ) && (getCookie("eh_alipay_payment") === false || getCookie("eh_alipay_payment") === null))
        {
            if(jQuery("#eh_alipay_payment").length === 0)
            {
                jQuery(".place-order").append('<span class="button alt" style="text-align: center;" id="eh_alipay_payment">'+eh_alipay_params.button+'</span>');
            }
        }
        else
        {
            jQuery("#eh_alipay_payment").remove();
        }
    });
    $(document.body).on('change','input[type=radio][name=payment_method]', function () {
        if($( '#payment_method_eh_stripe_pay' ).is( ':checked' ) && (getCookie("eh_alipay_payment") === false || getCookie("eh_alipay_payment") === null))
        {
            if(jQuery("#eh_alipay_payment").length === 0)
            {
                jQuery(".place-order").append('<span class="button alt" style="text-align: center;" id="eh_alipay_payment">'+eh_alipay_params.button+'</span>');
            }
        }
        else
        {
            jQuery("#eh_alipay_payment").remove();
        }
    });
    if(getCookie("eh_alipay_payment") === false || getCookie("eh_alipay_payment") === null)
    {
        eh_alipay_gen.init();
    }
});
