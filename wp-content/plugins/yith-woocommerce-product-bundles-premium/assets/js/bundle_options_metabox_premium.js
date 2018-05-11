jQuery( function ( $ ) {
    var bundled_items_cont             = $( '#yith_bundled_product_data .yith-wcpb-bundled-items' ),
        add_bundled_product_btn        = $( '#yith-wcpb-add-bundled-product' ),
        per_items_pricing              = $( '#_yith_wcpb_per_item_pricing' ),
        non_bundled_shipping           = $( '#_yith_wcpb_non_bundled_shipping' ),
        bundled_product_id             = $( '#yith-wcpb-bundled-product' ),
        remove_bundled_product_btn     = $( '.yith-wcpb-remove-bundled-product-item' ),
        items_count                    = $( '#yith_bundled_product_data .yith-wcpb-bundled-items .yith-wcpb-bundled-item' ).size(),
        bundled_product_data_container = $( '#yith_bundled_product_data' ),
        product_type                   = $( 'select#product-type' ),
        block_params                   = {
            message   : null,
            overlayCSS: {
                background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center',
                opacity   : 0.6
            }
        },
        tiptip_args                    = {
            'attribute': 'data-tip',
            'fadeIn'   : 50,
            'fadeOut'  : 50,
            'delay'    : 200
        },
        addActionToRemoveButtons       = function () {
            remove_bundled_product_btn = $( '.yith-wcpb-remove-bundled-product-item' );
            remove_bundled_product_btn.on( 'click', function () {
                $( this ).parent().parent().remove();
                //items_count--;
            } );
        },
        stopPropagationInLink          = function () {
            $( '.yith-wcpb-bundled-item h3 a' ).on( 'click', function ( event ) {
                event.stopPropagation();
            } );
        },
        isBundle                       = function () {
            return 'yith_bundle' === product_type.val();
        };


    items_count++;
    add_bundled_product_btn.on( 'click', function () {
        if ( !bundled_product_id.val() )
            return;

        var debug = $( this ).data( 'debug' ) || 0;

        bundled_product_data_container.block( block_params );
        var data = {
            action         : 'yith_wcpb_add_product_in_bundle',
            yith_wcpb_debug: debug,
            open_closed    : 'open',
            post_id        : woocommerce_admin_meta_boxes.post_id,
            id             : items_count,
            product_id     : bundled_product_id.val()
        };

        $.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {
            if ( response === 'yith_bundle' ) {
                alert( ajax_object.yith_bundle_product );
                bundled_product_data_container.unblock();
                return;
            }
            bundled_items_cont.append( response );
            bundled_items_cont.find( '.help_tip, .woocommerce-help-tip' ).tipTip( tiptip_args );
            $( 'body' ).trigger( 'wc-enhanced-select-init' );
            addActionToRemoveButtons();
            bundled_product_data_container.unblock();
            bundled_product_id.val( null ).trigger( 'change' );
            items_count++;
            stopPropagationInLink();
        } );
    } );

    addActionToRemoveButtons();

    $( 'body' ).on( 'woocommerce-product-type-change', function ( event, select_val, select ) {

        if ( select_val === 'yith_bundle' ) {
            $( '.show_if_external' ).hide();
            $( '.show_if_simple' ).show();
            $( '.show_if_bundle' ).show();

            $( 'input#_downloadable' ).prop( 'checked', false ).closest( '.show_if_simple' ).hide();
            $( 'input#_virtual' ).removeAttr( 'checked' ).closest( '.show_if_simple' ).hide();

            $( 'input#_manage_stock' ).change();
            per_items_pricing.change();
            non_bundled_shipping.change();

            $( '.product_price_rule' ).hide();
            $( '.hide_if_bundle' ).hide();

            $( '#_nyp' ).change();
        } else {
            $( '.product_price_rule' ).show();
            $( '.show_if_bundle' ).hide();
            $( '.hide_if_bundle' ).show();
        }

    } );

    product_type.change();


    // Per item pricing
    per_items_pricing.on( 'change', function () {
        if ( isBundle() ) {
            var on = $( this ).is( ':checked' );
            if ( on ) {
                // Per Item Pricing
                $( '#_regular_price' ).val( '' );
                $( '#_sale_price' ).val( '' );
                $( '.pricing' ).hide();
            } else {
                // NO -> Per Item Pricing
                $( '.pricing' ).show();

                $( '.product_data_tabs' ).find( 'li.general_options' ).show();
            }
        }
    } );
    per_items_pricing.change();


    // Non-Bundled Shipping
    non_bundled_shipping.on( 'change', function () {
        if ( isBundle() ) {
            var on = $( this ).is( ':checked' );
            if ( on ) {
                // Non-Bundled Shipping
                $( '.show_if_virtual' ).show();
                $( '.hide_if_virtual' ).hide();
                if ( $( '.shipping_tab' ).hasClass( 'active' ) )
                    $( 'ul.product_data_tabs li:visible' ).eq( 0 ).find( 'a' ).click();
            } else {
                // NO -> Non-Bundled Shipping
                $( '.show_if_virtual' ).hide();
                $( '.hide_if_virtual' ).show();
            }
        }
    } );
    non_bundled_shipping.change();


    /**
     * Sorting
     */
    var bundled_items_container = $( '.yith-wcpb-bundled-items' ),
        bundled_items           = bundled_items_container.find( '.yith-wcpb-bundled-item' ).get();

    bundled_items.sort( function ( a, b ) {
        var compA = parseInt( $( a ).attr( 'rel' ) );
        var compB = parseInt( $( b ).attr( 'rel' ) );
        return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
    } );

    $( bundled_items ).each( function ( idx, itm ) {
        bundled_items_container.append( itm );
    } );

    bundled_items_container.sortable( {
                                          items               : '.yith-wcpb-bundled-item',
                                          cursor              : 'move',
                                          axis                : 'y',
                                          handle              : 'h3',
                                          scrollSensitivity   : 40,
                                          forcePlaceholderSize: true,
                                          helper              : 'clone',
                                          opacity             : 0.65,
                                          placeholder         : 'wc-metabox-sortable-placeholder',
                                          start               : function ( event, ui ) {
                                              ui.item.css( 'background-color', '#f6f6f6' );
                                          },
                                          stop                : function ( event, ui ) {
                                              ui.item.removeAttr( 'style' );
                                          }
                                      } );
    stopPropagationInLink();
} );