<?php
/**
 * Template for bundles
 *
 * @version 4.8.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global $product;

$bundled_items     = $product->get_bundled_items();
$per_items_pricing = $product->per_items_pricing;
do_action( 'yith_wcpb_before_bundle_items_list' );

$display_table = false;
if ( $bundled_items ) {
    foreach ( $bundled_items as $bundled_item ) {
        if ( !$bundled_item->is_hidden() ) {
            $display_table = true;
            break;
        }
    }
}
$style = !$display_table ? "style='display:none'" : '';

if ( $bundled_items ) {
    echo "<table class='yith-wcpb-product-bundled-items' $style>";
    foreach ( $bundled_items as $bundled_item ) {
        /**
         * @var YITH_WC_Bundled_Item $bundled_item
         */
        $bundled_product = $bundled_item->get_product();
        $bundled_post    = get_post( yit_get_base_product_id( $bundled_product ) );
        $quantity        = $bundled_item->get_quantity();    //FREE
        $hide_thumbnail  = $bundled_item->hide_thumbnail;
        $hidden          = $bundled_item->is_hidden();
        $bundled_item_id = $bundled_item->item_id;
        $min_qty         = $bundled_item->min_quantity;
        $max_qty         = $bundled_item->max_quantity;
        $item_id         = $bundled_item->item_id;
        $title           = !empty( $bundled_item->title ) ? $bundled_item->title : $bundled_post->post_title;
        $description     = !empty( $bundled_item->description ) ? $bundled_item->description : $bundled_post->post_excerpt;
        $optional        = $bundled_item->optional;

        if ( $bundled_item->has_variables() ) {
            $my_price_max = yit_get_display_price( $bundled_product, $bundled_product->get_variation_regular_price( 'min' ) );
        } else {
            $my_price_max = yit_get_display_price( $bundled_product, $bundled_product->get_regular_price() );
        }

        $my_price_max = apply_filters( 'yith_wcpb_bundled_item_displayed_price', $my_price_max, $bundled_product, $bundled_item );
        $my_discount  = apply_filters( 'yith_wcpb_bundled_item_calculated_discount', $my_price_max * $bundled_item->discount / 100, $bundled_item->discount, $my_price_max, $bundled_item->get_product_id(), array() );
        $my_price     = $my_price_max - $my_discount;

        if ( $hidden ) {
            if ( $bundled_item->has_variables() ) {
                $default_selection     = $bundled_item->get_selected_product_variation_attributes();
                $default_selection_tmp = array();
                foreach ( $default_selection as $key => $value ) {
                    $default_selection_tmp[ 'attribute_' . $key ] = YITH_WCPB()->compatibility->wpml->get_wpml_term_slug_current_language( $value, $key );
                    $default_selection[ $key ]                    = YITH_WCPB()->compatibility->wpml->get_wpml_term_slug_current_language( $value, $key );
                }
                if ( $bundled_product instanceof WC_Data ) {
                    $data_store   = WC_Data_Store::load( 'product' );
                    $variation_id = $data_store->find_matching_product_variation( $bundled_product, $default_selection_tmp );
                } else {
                    $variation_id = $bundled_product->get_matching_variation( $default_selection_tmp );
                }
                if ( !!$variation_id ) {
                    $input_name = "yith_bundle_variation_id_$bundled_item_id";
                    echo "<input type='hidden' name='$input_name' value='$variation_id' class='variation_id' data-item-id='$bundled_item_id'/>";

                    $b_attributes = $attributes[ $bundled_item_id ];
                    foreach ( $b_attributes as $name => $options ) {
                        $input_name = "yith_bundle_attribute_" . sanitize_title( $name ) . '_' . $bundled_item_id;
                        $value      = isset( $default_selection[ $name ] ) ? $default_selection[ $name ] : 0;

                        echo "<input type='hidden' name='$input_name' value='$value' />";
                    }

                }
            }
            continue;
        }

        // free -> premium
        if ( ( !isset( $min_qty ) && !isset( $max_qty ) ) || $min_qty < 1 || $max_qty < 1 ) {
            $min_qty = $quantity;
            $max_qty = $quantity;
        }


        $quantity_lbl = '';
        if ( $min_qty == $max_qty ) {
            $quantity_lbl = $min_qty . ' x ';
        }

        $item_title = $quantity_lbl . $title;
        $item_title = apply_filters( 'yith_wcpb_bundle_items_list_bundle_title', $item_title, $bundled_item, $min_qty, $max_qty, $title );

        $bundled_item_classes = apply_filters( 'yith_wcpb_bundled_item_classes', array( 'product', 'yith-wcpb-product-bundled-item' ), $bundled_item, $product );
        $bundled_item_classes = implode( ' ', $bundled_item_classes );
        ?>
        <tr class="<?php echo $bundled_item_classes ?>" data-is-purchasable="<?php echo $bundled_product->is_purchasable() ? '1' : '0' ?>">
            <td class="yith-wcpb-product-bundled-item-image">
                <div class="yith-wcpb-product-bundled-item-image-wrapper">
                    <?php
                    if ( !$hide_thumbnail ) {
                        echo $bundled_product->get_image( apply_filters( 'yith_wcpb_bundled_item_thumbnail_size', 'shop_thumbnail', $bundled_item, $product ) );
                    }
                    ?>
                </div>
                <?php
                if ( apply_filters( 'yith_wcpb_show_bundled_items_prices', true, $bundled_item, $product ) ) {
                    if ( !$bundled_item->has_variables() || apply_filters( 'yith_wcpb_bundled_item_show_default_price_for_variables', true ) ) {
                        $my_price_max_html_data = $my_price_max > 0 ? htmlspecialchars( wc_price( $my_price_max ) ) : '';
                        $my_price_html_data     = $my_price > 0 ? htmlspecialchars( wc_price( $my_price ) ) : '';

                        if ( !$per_items_pricing ) {
                            ?>
                            <div class="price" data-default-del="<?php echo $my_price_max_html_data ?>" data-default-ins="">
                                <del><span class="amount"><?php echo wc_price( $my_price_max ) ?></span></del>
                            </div>
                            <?php
                        } else {
                            if ( $my_price_max > $my_price ) {
                                ?>
                                <div class="price" data-default-del="<?php echo $my_price_max_html_data ?>" data-default-ins="<?php echo $my_price_html_data ?>">
                                    <del><span class="amount"><?php echo wc_price( $my_price_max ) ?></span></del>
                                    <ins><span class="amount"><?php echo wc_price( $my_price ) ?></span></ins>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="price" data-default-del="" data-default-ins="<?php echo $my_price_html_data ?>">
                                    <ins><span class="amount"><?php echo wc_price( $my_price ) ?></span></ins>
                                </div>
                                <?php
                            }
                        }
                    }
                }
                ?>

            </td>
            <td class="yith-wcpb-product-bundled-item-data">

                <h3>
                    <?php if ( $bundled_product->is_visible() ): ?>
                        <a href="<?php echo $bundled_product->get_permalink() ?>">
                            <?php echo $item_title ?>
                        </a>
                    <?php else: ?>
                        <?php echo $item_title ?>
                    <?php endif; ?>
                </h3>

                <p><?php echo do_shortcode( $description ); ?></p>

                <?php if ( $optional && !$bundled_item->has_variables() ) : ?>
                    <div class="yith-wcpb-bundled-optional-wrapper">
                        <input type="checkbox" name="yith_bundle_optional_<?php echo $item_id ?>"
                               class="yith-wcpb-bundled-optional" data-item-id="<?php echo $item_id ?>">
                        <?php if ( !$per_items_pricing ) : ?>
                            <label><?php _e( 'Add', 'yith-woocommerce-product-bundles' ); ?></label>
                        <?php else : ?>
                            <label><?php echo sprintf( __( 'Add for %s', 'yith-woocommerce-product-bundles' ), wc_price( $my_price ) ); ?></label>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                if ( $bundled_item->has_variables() ) {

                    $b_attributes = $attributes[ $bundled_item_id ];

                    $bundle_product_id = $bundled_item->get_wpml_product_id_current_language();

                    ?>
                    <div class="bundled_item_cart_content variations_form"
                         data-optional="<?php echo( $bundled_item->is_optional() ? 1 : 0 ); ?>"
                         data-type="<?php echo $bundled_product->get_type(); ?>"
                         data-product_variations="<?php echo esc_attr( json_encode( $available_variations[ $bundled_item_id ] ) ); ?>"
                         data-bundled_item_id="<?php echo $bundled_item->item_id; ?>"
                         data-product_id="<?php echo $product->get_id() . str_replace( '_', '', $bundled_item->item_id ); ?>"
                         data-bundle_id="<?php echo $product->get_id(); ?>">

                        <input name="yith_bundle_variation_id_<?php echo $bundled_item_id ?>" class="variation_id"
                               value="" type="hidden" data-item-id="<?php echo $bundled_item_id ?>">
                        <?php if ( $optional ) { ?>
                            <div class="yith-wcpb-bundled-optional-wrapper">

                                <input type="checkbox" name="yith_bundle_optional_<?php echo $item_id ?>"
                                       class="yith-wcpb-bundled-optional" data-item-id="<?php echo $item_id ?>">
                                <label><?php _e( 'Add', 'yith-woocommerce-product-bundles' ); ?></label>
                            </div>
                        <?php } ?>

                        <table class="variations" cellspacing="0">
                            <tbody>
                            <?php $loop = 0;
                            foreach ( $b_attributes as $name => $options ) : $loop++; ?>
                                <tr>
                                    <td class="label"><label
                                                for="<?php echo sanitize_title( $name ); ?>"><?php echo wc_attribute_label( $name ); ?></label>
                                    </td>

                                    <td class="value"><select data-type="select" class="yith-wcpb-select-for-variables"
                                                              id="<?php echo esc_attr( sanitize_title( $name ) ) . '_' . $bundled_item_id;; ?>"
                                                              name="yith_bundle_attribute_<?php echo sanitize_title( $name ) . '_' . $bundled_item_id; ?>"
                                                              data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
                                            <option
                                                    value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;
                                            </option>
                                            <?php
                                            if ( is_array( $options ) ) {
                                                if ( isset( $_REQUEST[ 'yith_bundle_attribute_' . sanitize_title( $name ) . '_' . $bundled_item_id ] ) ) {
                                                    $selected_value = $_REQUEST[ 'yith_bundle_attribute_' . sanitize_title( $name ) . '_' . $bundled_item_id ];
                                                } elseif ( isset( $selected_attributes[ $bundled_item_id ][ sanitize_title( $name ) ] ) ) {
                                                    $selected_value = $selected_attributes[ $bundled_item_id ][ sanitize_title( $name ) ];
                                                } else {
                                                    $selected_value = '';
                                                }

                                                // Get terms if this is a taxonomy - ordered
                                                if ( taxonomy_exists( $name ) ) {
                                                    if ( !!$selected_value ) {
                                                        $selected_value = YITH_WCPB()->compatibility->wpml->get_wpml_term_slug_current_language( $selected_value, $name );
                                                    }

                                                    $terms = wc_get_product_terms( $bundle_product_id, $name, array( 'fields' => 'all' ) );

                                                    foreach ( $terms as $term ) {

                                                        if ( !in_array( $term->slug, $options ) ) {
                                                            continue;
                                                        }
                                                        echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';

                                                    }

                                                } else {
                                                    foreach ( $options as $option ) {
                                                        $selected = sanitize_title( $selected_value ) === $selected_value ? selected( $selected_value, sanitize_title( $option ), false ) : selected( $selected_value, $option, false );
                                                        echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                                                    }

                                                }
                                            }
                                            ?>
                                        </select> <?php
                                        if ( sizeof( $b_attributes ) === $loop ) {
                                            echo '<a class="reset_variations" href="#reset">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>';
                                        }
                                        ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="single_variation_wrap bundled_item_wrap" style="display:none;">
                            <div class="single_variation bundled_item_cart_details"></div>

                            <div class="quantity">
                                <?php if ( $min_qty < $max_qty ) { ?>
                                    <input step="1" min="<?php echo $min_qty ?>" max="<?php echo $max_qty ?>"
                                           name="yith_bundle_quantity_<?php echo $item_id ?>"
                                           value="<?php echo $min_qty ?>"
                                           data-item-id="<?php echo $item_id ?>"
                                           title="Qty" class="input-text qty text yith-wcpb-bundled-quantity" size="4"
                                           type="number">
                                <?php } else { ?>
                                    <input class="yith-wcpb-bundled-quantity"
                                           name="yith_bundle_quantity_<?php echo $item_id ?>" value="<?php echo $min_qty ?>"
                                           type="hidden"
                                           data-item-id="<?php echo $item_id ?>">
                                <?php } ?>
                            </div>
                            <?php do_action( 'yith_wcpb_after_bundled_item_quantity_input', $bundled_item, $min_qty, $max_qty ); ?>


                        </div>

                    </div>
                    <?php
                }
                ?>

                <?php if ( !$bundled_item->has_variables() ) { ?>
                    <div class="quantity">
                        <?php if ( $min_qty < $max_qty ) { ?>
                            <input step="1" min="<?php echo $min_qty ?>" max="<?php echo $max_qty ?>"
                                   name="yith_bundle_quantity_<?php echo $item_id ?>" value="<?php echo $min_qty ?>"
                                   title="Qty"
                                   data-item-id="<?php echo $item_id ?>"
                                   class="input-text qty text yith-wcpb-bundled-quantity" size="4" type="number">
                        <?php } else { ?>
                            <input name="yith_bundle_quantity_<?php echo $item_id ?>" value="<?php echo $min_qty ?>"
                                   type="hidden" class="yith-wcpb-bundled-quantity"
                                   data-item-id="<?php echo $item_id ?>">
                        <?php } ?>
                    </div>

                    <?php do_action( 'yith_wcpb_after_bundled_item_quantity_input', $bundled_item, $min_qty, $max_qty ); ?>


                    <?php
                    if ( $bundled_product->managing_stock() ) {
                        echo '<div class="yith-wcpb-product-bundled-item-availability not-variation">';

                        $bp_availability      = $bundled_product->get_availability();
                        $bp_availability_html = empty( $bp_availability[ 'availability' ] ) ? '' : '<p class="stock ' . esc_attr( $bp_availability[ 'class' ] ) . '">' . esc_html( $bp_availability[ 'availability' ] ) . '</p>';
                        echo apply_filters( 'woocommerce_stock_html', $bp_availability_html, $bp_availability[ 'availability' ], $bundled_product );

                        echo '</div>';
                    }
                    ?>

                <?php } ?>
            </td>
        </tr>
        <?php
    }
    echo '</table>';
}

do_action( 'yith_wcpb_after_bundle_items_list' );



