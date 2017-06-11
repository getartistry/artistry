jQuery(function ($) {

    $(document).on('woocommerce_variation_has_changed', function () {

        var variation_id = parseInt($('.single_variation_wrap .variation_id, .single_variation_wrap input[name="variation_id"]').val());

        if (ywqcdg_frontend.active_variations.indexOf(variation_id) == -1) {

            $('.ywqcdg-wrapper').hide();

        } else {

            $('.ywqcdg-wrapper').show();

        }

    });

});
