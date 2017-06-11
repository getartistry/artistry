<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

add_action( 'wp_ajax_ywqcdg_json_search_product_categories', 'ywqcdg_json_search_product_categories', 10 );
add_action( 'wp_ajax_ywqcdg_json_search_product_tags', 'ywqcdg_json_search_product_tags', 10 );
add_action( 'wp_ajax_ywqcdg_json_search_products_and_variations', 'ywqcdg_json_search_products_and_variations', 10 );

if ( !function_exists( 'ywqcdg_json_search_product_categories' ) ) {

    /**
     * Get category name
     *
     * @since   1.0.0
     *
     * @param   $x
     * @param   $taxonomy_types
     *
     * @return  string
     * @author  Alberto Ruggiero
     */
    function ywqcdg_json_search_product_categories( $x = '', $taxonomy_types = array( 'product_cat' ) ) {

        global $wpdb;

        $term = (string) urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );
        $term = '%' . $term . '%';

        $query_cat = $wpdb->prepare( "SELECT {$wpdb->terms}.term_id,{$wpdb->terms}.name, {$wpdb->terms}.slug
                                   FROM {$wpdb->terms} INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id
                                   WHERE {$wpdb->term_taxonomy}.taxonomy IN (%s) AND {$wpdb->terms}.slug LIKE %s", implode( ',', $taxonomy_types ), $term );

        $product_categories = $wpdb->get_results( $query_cat );

        $to_json = array();

        foreach ( $product_categories as $product_category ) {

            $to_json[$product_category->term_id] = sprintf( '#%s &ndash; %s', $product_category->term_id, $product_category->name );

        }

        wp_send_json( $to_json );

    }

}

if ( !function_exists( 'ywqcdg_json_search_product_tags' ) ) {

    /**
     * Get tag name
     *
     * @since   1.0.0
     *
     * @param   $x
     * @param   $taxonomy_types
     *
     * @return  string
     * @author  Alberto Ruggiero
     */
    function ywqcdg_json_search_product_tags( $x = '', $taxonomy_types = array( 'product_tag' ) ) {

        global $wpdb;

        $term = (string) urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );
        $term = '%' . $term . '%';

        $query_cat = $wpdb->prepare( "SELECT {$wpdb->terms}.term_id,{$wpdb->terms}.name, {$wpdb->terms}.slug
                                   FROM {$wpdb->terms} INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id
                                   WHERE {$wpdb->term_taxonomy}.taxonomy IN (%s) AND {$wpdb->terms}.slug LIKE %s", implode( ',', $taxonomy_types ), $term );

        $product_tags = $wpdb->get_results( $query_cat );

        $to_json = array();

        foreach ( $product_tags as $product_tag ) {

            $to_json[$product_tag->term_id] = sprintf( '#%s &ndash; %s', $product_tag->term_id, $product_tag->name );

        }

        wp_send_json( $to_json );

    }

}

if ( !function_exists( 'ywqcdg_json_search_products_and_variations' ) ) {

    /**
     * Get product name
     *
     * @since   1.0.0
     *
     * @param   $x
     * @param   $post_types
     *
     * @return  string
     * @author  Alberto Ruggiero
     */
    function ywqcdg_json_search_products_and_variations( $x = '', $post_types = array( 'product', 'product_variation' ) ) {

        global $wpdb;

        ob_start();

        check_ajax_referer( 'search-products', 'security' );

        $term = (string) wc_clean( stripslashes( $_GET['term'] ) );

        if ( empty( $term ) ) {
            die();
        }

        $like_term = '%' . $wpdb->esc_like( $term ) . '%';

        if ( is_numeric( $term ) ) {
            $query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND (
					posts.post_parent = %s
					OR posts.ID = %s
					OR posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
			", $term, $term, $term, $like_term );
        }
        else {
            $query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND (
					posts.post_title LIKE %s
					or posts.post_content LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
			", $like_term, $like_term, $like_term );
        }

        $query .= " AND posts.post_type IN ('" . implode( "','", array_map( 'esc_sql', $post_types ) ) . "')";

        if ( !empty( $_GET['exclude'] ) ) {
            $query .= " AND posts.ID NOT IN (" . implode( ',', array_map( 'intval', explode( ',', $_GET['exclude'] ) ) ) . ")";
        }

        if ( !empty( $_GET['include'] ) ) {
            $query .= " AND posts.ID IN (" . implode( ',', array_map( 'intval', explode( ',', $_GET['include'] ) ) ) . ")";
        }

        if ( !empty( $_GET['limit'] ) ) {
            $query .= " LIMIT " . intval( $_GET['limit'] );
        }

        $posts          = array_unique( $wpdb->get_col( $query ) );
        $found_products = array();

        if ( !empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $product = wc_get_product( $post );

                if ( !current_user_can( 'read_product', $post ) ) {
                    continue;
                }

                if ( !$product || ( $product->is_type( 'variation' ) && empty( $product->parent ) ) ) {
                    continue;
                }

                if ( ! yit_get_prop( $product, 'virtual' ) && ! yit_get_prop( $product, 'downloadable' )  ) {
                    continue;
                }

                $found_products[$post] = rawurldecode( $product->get_formatted_name() );
            }
        }

        wp_send_json( $found_products );
    }

}
