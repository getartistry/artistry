/* global yith_wcpb_params, ajax_obj */

jQuery( function ( $ ) {
    $.fn.yith_bundle_form = function () {
        $( this ).each( function () {
            var $form                      = $( this ),
                product_id                 = $form.data( 'product-id' ),
                per_item_pricing           = $form.data( 'per-item-pricing' ),
                ajax_update_price_enabled  = $form.data( 'ajax-update-price' ),
                $bundled_items_prices      = $form.find( '.yith-wcpb-product-bundled-items .price' ),
                $price_handler             = $form.closest( yith_wcpb_params.price_handler_parent ).find( yith_wcpb_params.price_handler ) || $form.closest( yith_wcpb_params.price_handler_parent_alt ).find( yith_wcpb_params.price_handler_alt ),
                $price                     = yith_wcpb_params.price_handler_only_first == 1 ? $price_handler.not( $bundled_items_prices ).first() : $price_handler.not( $bundled_items_prices ),
                $add_to_cart               = $form.closest( 'form' ).find( 'button[type=submit]' ),
                $add_to_quote              = $form.find( '.add-request-quote-button' ),
                add_to_quote_default_color = $add_to_quote.css( 'background-color' ),
                $variation_forms           = $form.find( '.bundled_item_cart_content' ),
                $qty_fields                = $form.find( 'input.yith-wcpb-bundled-quantity' ),
                $opt_fields                = $form.find( '.yith-wcpb-bundled-optional' ),
                $variations                = $form.find( '.variation_id' ),
                $currency                  = $form.find( 'input[name=yith_wcpb_wpml_client_currency]' ),
                ajax_call                  = null,
                check_disable_btn          = function () {
                    var is_disabled = false;
                    $variation_forms.each( function () {
                        var $current_variation_form          = $( this ),
                            optional_checked_or_not_optional = ($current_variation_form.find( '.yith-wcpb-bundled-optional' ).length > 0 ) ? $( this ).find( '.yith-wcpb-bundled-optional' ).is( ':checked' ) : true,
                            my_select                        = $current_variation_form.find( 'select.yith-wcpb-select-for-variables' );

                        if ( optional_checked_or_not_optional ) {

                            my_select.each( function () {
                                var $current_select = $( this );
                                if ( $current_select.val() == undefined || $current_select.val() == '' ) {
                                    is_disabled = true;
                                }

                            } );
                            $current_variation_form.find( '.variations' ).slideDown( 'fast' );
                            $current_variation_form.find( '.single_variation_wrap' ).slideDown( 'fast' );

                            if ( $current_variation_form.find( '.out-of-stock' ).length > 0 ) {
                                is_disabled = true;
                            }
                        } else {

                            if ( $current_variation_form.find( '.yith-wcpb-bundled-optional' ).length > 0 ) {

                                $current_variation_form.find( '.quantity input.qty' ).removeAttr( 'max' );
                                $current_variation_form.find( '.single_variation_wrap' ).slideUp( 'fast' );
                                $current_variation_form.find( '.variations' ).slideUp( 'fast' );
                            }
                        }
                    } );


                    $add_to_cart.prop( 'disabled', is_disabled );

                    // integration with Request a quote
                    if ( is_disabled ) {
                        $add_to_quote.addClass( 'disabled' );
                        $add_to_quote.css( 'background-color', '#bbb' );
                    } else {
                        $add_to_quote.removeClass( 'disabled' );
                        $add_to_quote.css( 'background-color', add_to_quote_default_color );
                    }
                },
                block_params               = {
                    message        : null,
                    overlayCSS     : {
                        background: '#fff',
                        opacity   : 0.6
                    },
                    ignoreIfBlocked: true
                },
                update_price               = function () {
                    if ( ajax_call ) {
                        ajax_call.abort();
                    }

                    if ( ajax_update_price_enabled != 1 ) {
                        return;
                    }

                    $price.block( block_params );

                    var array_qty = [];
                    var array_opt = [];
                    var array_var = [];

                    $qty_fields.each( function () {
                        array_qty[ $( this ).data( 'item-id' ) - 1 ] = $( this ).val();
                    } );

                    $opt_fields.each( function () {
                        array_opt[ $( this ).data( 'item-id' ) - 1 ] = $( this ).is( ':checked' ) ? 1 : 0;
                    } );

                    $variations.each( function () {
                        array_var[ $( this ).data( 'item-id' ) - 1 ] = $( this ).val();
                    } );

                    /* WPML - Multi Currency */
                    var client_currency = $currency.length > 0 ? $currency.val() : '';

                    var post_data = {
                        bundle_id                     : product_id,
                        array_qty                     : array_qty,
                        array_opt                     : array_opt,
                        array_var                     : array_var,
                        yith_wcpb_wpml_client_currency: client_currency,
                        action                        : 'yith_wcpb_get_bundle_total_price'
                    };

                    ajax_call = $.ajax( {
                                            type   : "POST",
                                            data   : post_data,
                                            url    : ajax_obj.ajaxurl,
                                            success: function ( response ) {
                                                var price_to_upload = $price.find( 'ins .amount' );
                                                if ( price_to_upload.length < 1 ) {
                                                    price_to_upload = $price.find( '.amount' );
                                                }

                                                if ( price_to_upload.length < 1 ) {
                                                    price_to_upload = $price;
                                                }

                                                price_to_upload = price_to_upload.first();
                                                price_to_upload.html( response );
                                                $price.html( price_to_upload.html() );

                                                $( document ).trigger( 'yith_wcpb_ajax_update_price_request' );

                                                $price.unblock();
                                            }
                                        } );
                };

            $form.on( 'yith_wcpb_update_price', function () {
                check_disable_btn();
                update_price();
            } ).trigger( 'yith_wcpb_update_price' );

            $qty_fields.on( 'change', function () {
                if ( $( this ).parents( '.bundled_item_cart_content' ).length == 0 )
                    $form.trigger( 'yith_wcpb_update_price' );
            } );

            $opt_fields.on( 'click', function () {
                if ( $( this ).parents( '.bundled_item_cart_content' ).length == 0 )
                    $form.trigger( 'yith_wcpb_update_price' );

            } );

            $variation_forms.on( 'change', function () {
                $form.trigger( 'yith_wcpb_update_price' );
            } );

            $variation_forms.on( 'found_variation', function ( event, variation ) {
                var $current_product = $( this ).closest( '.product' ),
                    $prices          = $current_product.find( '.yith-wcpb-product-bundled-item-image .price' ).first(),
                    $price           = $prices.find( 'ins' ),
                    $real_price      = $prices.find( 'del' ),
                    $image           = $current_product.find( '.yith-wcpb-product-bundled-item-image-wrapper img' ).first(),
                    new_image_src    = '';

                if ( typeof variation.image_srcset !== 'undefined' && variation.image_srcset ) {
                    /* wc 2.6 */
                    new_image_src = variation.image_srcset;
                } else if ( typeof variation.image !== 'undefined' && typeof variation.image.srcset !== 'undefined' && variation.image.srcset ) {
                    /* wc 2.7 */
                    new_image_src = variation.image.srcset;
                }

                if ( new_image_src.length > 0 )
                    $image.attr( 'srcset', new_image_src );

                $price.html( variation.price_html.replace( 'price', 'amount' ) );
                $real_price.html( variation.display_regular_price_html );
            } )
                .on( 'reset_data', function () {
                    var $current_product   = $( this ).closest( '.product' ),
                        $prices            = $current_product.find( '.yith-wcpb-product-bundled-item-image .price' ).first(),
                        $price             = $prices.find( 'ins' ),
                        default_price      = $price.data( 'default-ins' ),
                        $real_price        = $prices.find( 'del' ),
                        default_real_price = $real_price.data( 'default-ins' ),
                        $image             = $current_product.find( '.yith-wcpb-product-bundled-item-image-wrapper img' ).first(),
                        new_image_src      = $image.attr( 'src' );

                    if ( 'undefined' !== typeof new_image_src && new_image_src.length > 0 )
                        $image.attr( 'srcset', new_image_src );

                    $price.html( default_price );
                    $real_price.html( default_real_price );
                } );

            // trigger the check_variation to show the variation prices if a variation is selected by default
            $variation_forms.trigger( 'check_variations' );
            // display only available variations
            $variation_forms.trigger( 'update_variation_values' );
        } );
    };


    $( document ).on( 'yith_wcpb_add_to_cart_init', function () {
        $( '.yith-wcpb-bundle-form' ).yith_bundle_form();

    } ).trigger( 'yith_wcpb_add_to_cart_init' );

    // compatibility with YITH WooCommerce Quick View
    $( document ).on( 'qv_loader_stop', function () {
        $( document ).trigger( 'yith_wcpb_add_to_cart_init' );
    } )
} );