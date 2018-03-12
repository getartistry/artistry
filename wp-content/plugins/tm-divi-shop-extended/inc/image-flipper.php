<?php
function tm_image_flipper(){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        /**
        * Image Flipper class
        **/
        if ( ! class_exists( 'WC_pif' ) ) {

            class WC_pif {

                public function __construct() {
                    add_action( 'wp_enqueue_scripts', array( $this, 'pif_scripts' ) );														// Enqueue the styles
                    add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_template_loop_second_product_thumbnail' ), 11 );
                    add_filter( 'post_class', array( $this, 'product_has_gallery' ) );
                }


                /*-----------------------------------------------------------------------------------*/
                /* Class Functions */
                /*-----------------------------------------------------------------------------------*/

                // Setup styles
                function pif_scripts() {
                    if ( apply_filters( 'woocommerce_product_image_flipper_styles', true ) ) {
                        wp_enqueue_style( 'tm_pif-styles', TM_PLUGIN_URI . '/css/tm-image-flipper.css');
                    }
                    wp_enqueue_script( 'tm_pif-script', TM_PLUGIN_URI . '/js/tm-image-flipper.js', array( 'jquery' ) );
                }

                // Add pif-has-gallery class to products that have a gallery
                function product_has_gallery( $classes ) {
                    global $product;

                    $post_type = get_post_type( get_the_ID() );

                    if ( ! is_admin() ) {

                        if ( $post_type == 'product' ) {

                            $attachment_ids = $this->get_gallery_image_ids( $product );

                            if ( $attachment_ids ) {
                                $classes[] = 'pif-has-gallery';
                            }
                        }

                    }

                    return $classes;
                }

                /*-----------------------------------------------------------------------------------*/
                /* Frontend Functions */
                /*-----------------------------------------------------------------------------------*/

                // Display the second thumbnails
                function woocommerce_template_loop_second_product_thumbnail() {
                    global $product, $woocommerce;

                    $attachment_ids = $this->get_gallery_image_ids( $product );

                    if ( $attachment_ids ) {
                        $attachment_ids     = array_values( $attachment_ids );
                        $secondary_image_id = $attachment_ids['0'];
                        echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'secondary-image attachment-shop-catalog wp-post-image' ) );
                    }
                }

                /*-----------------------------------------------------------------------------------*/
                /* WooCommerce Compatibility Functions */
                /*-----------------------------------------------------------------------------------*/

                // Get product gallery image IDs
                function get_gallery_image_ids( $product ) {
                    if ( ! is_a( $product, 'WC_Product' ) ) {
                        return;
                    }

                    if ( is_callable( 'WC_Product::get_gallery_image_ids' ) ) {
                        return $product->get_gallery_image_ids();
                    } else {
                        return $product->get_gallery_attachment_ids();
                    }
                }

            }

            $WC_pif = new WC_pif();
        }
    }
}
?>