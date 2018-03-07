<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_WP_All_import.
 *
 * Helper for importing additional images when using WP All Import
 *
 * @class    Iconic_WooThumbs_WP_All_import
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_WP_All_import {

    /*
     * Construct
     */
    function __construct() {

        add_action( 'pmxi_gallery_image', array( $this, 'attach_additional_images' ), 10, 3 );
        add_action( 'pmxi_after_xml_import', array( $this, 'after_import' ), 100, 1 );

    }

    /**
     * Import additional images
     *
     * @param int $pid
     * @param int $attachment_id
     * @param str $image_filepath
     */
    public function attach_additional_images( $post_id, $attachment_id, $image_filepath ) {

        // check the post type
        $post_type = get_post_type( $post_id );

        if( $post_type !== "product" && $post_type !== "product_variation" )
            return;

        // check that this image hasn't already been added as the featured image
        $featured_image_id = get_post_thumbnail_id( $post_id );

        if( $attachment_id == $featured_image_id )
            return;

        // get the current variation gallery
        $variation_image_gallery = get_post_meta( $post_id, 'variation_image_gallery', true);

        // if there is no featured image set currently,
        // then WP All Import is about to set it. Let's
        // clear out our current variation gallery, ready
        // for the next images.
        if( !$featured_image_id ) {
            delete_post_meta( $post_id, 'variation_image_gallery', $variation_image_gallery );
            return;
        }

        // explode the current gallery to an array
        $variation_image_gallery = $variation_image_gallery ? explode(',', $variation_image_gallery) : array();

        // add our new attachment to the gallery
        $variation_image_gallery[] = $attachment_id;

        // update the gallery meta field
        update_post_meta( $post_id, 'variation_image_gallery', implode(',', $variation_image_gallery));

    }

    /**
     * After WP All Import has finished
     *
     * @param int $import_id
     */
    public function after_import( $import_id ) {

        $this->clean_up_meta();

    }

    /**
     * Clean up variation_image_gallery data
     */
    public function clean_up_meta() {

        global $wpdb;

        $meta_ids_query = $wpdb->get_results(
            "
                SELECT $wpdb->postmeta.meta_id FROM $wpdb->posts, $wpdb->postmeta
                WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
                AND $wpdb->postmeta.meta_key = 'variation_image_gallery'
                AND $wpdb->posts.post_type != 'product_variation'
            "
        );

        if( !$meta_ids_query || empty( $meta_ids_query ) )
            return;

        $meta_ids = array();

        foreach( $meta_ids_query as $meta_id_result ) {
            $meta_ids[] = $meta_id_result->meta_id;
        }

        if( empty( $meta_ids ) )
            return;

        $meta_ids = array_map( 'esc_sql', $meta_ids );
        $meta_ids = implode( ", ", $meta_ids );

        $wpdb->query(
            $wpdb->prepare(
                "
                    DELETE FROM $wpdb->postmeta
                    WHERE meta_id IN ( %s )
                    AND meta_key = %s
                ",
                $meta_ids, 'variation_image_gallery'
            )
        );

    }

}