jQuery(function ($) {

    $('#woocommerce-product-data')
        .on('change', '.variable_is_downloadable, .variable_is_virtual', function () {

            var quick_checkout = $(this).parent().parent().find('.ywqcdg_active_checkout'),
                type = $(this).hasClass('variable_is_downloadable') ? '.variable_is_virtual' : '.variable_is_downloadable',
                other = $(this).parent().parent().find(type).is(':checked');

            if ($(this).is(':checked')) {

                quick_checkout.show();

            } else {

                if (!other) {

                    quick_checkout.hide();

                }

            }

        });

    $(document).ready(function () {

        $('#ywqcdg_product_page').change(function () {

            if ($(this).is(':checked')) {

                $('#ywqcdg_product_page_atc').parent().parent().show();

            } else {

                $('#ywqcdg_product_page_atc').parent().parent().hide();

            }

        }).change();

    });

});
