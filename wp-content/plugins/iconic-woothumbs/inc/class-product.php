<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Product.
 *
 * @class    Iconic_WooThumbs_Product
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Product {

    /**
     * Get Product
     *
     * @param in $id
     * @return WC_Product
     */
    public static function get_product( $id ) {

        $post_type = get_post_type( $id );

        if( $post_type !== "product_variation" )
            return wc_get_product( absint( $id ) );

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {

            return wc_get_product( absint( $id ), array( 'product_type' => 'variable' ) );

        } else {

            return new WC_Product_Variation( absint( $id ) );

        }

    }

    /**
     * Get parent ID
     *
     * @param WC_Product $product
     * @return int
     */
    public static function get_parent_id( $product ) {

        return method_exists( $product, 'get_parent_id' ) ? $product->get_parent_id() : $product->id;

    }

    /**
     * Get gallery image IDs
     *
     * @param WC_Product $product
     * @return arr
     */
    public static function get_gallery_image_ids( $product ) {

        return method_exists( $product, 'get_gallery_image_ids' ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();

    }

    /**
     * Find matching product variation
     *
     * @param WC_Product $product
     * @param arr $attributes
     * @return arr
     */
    public static function find_matching_product_variation( $product, $attributes ) {

        if( class_exists('WC_Data_Store') ) {

            $data_store = WC_Data_Store::load( 'product' );
            return $data_store->find_matching_product_variation( $product, wp_unslash( $attributes ) );

        } else {

            return $product->get_matching_variation( wp_unslash( $attributes ) );

        }

    }

}