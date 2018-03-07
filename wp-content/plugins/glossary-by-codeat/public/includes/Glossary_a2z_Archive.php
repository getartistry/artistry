<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */

/**
 * A-Z Archive system
 * Based on https://github.com/NowellVanHoesen/a2z-alphabetical-archive-links/
 */
class Glossary_a2z_Archive {

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_action( 'pre_get_posts', array( $this, 'check_qv' ) );
    }

    /**
     * Add our value
     *
     * @param array $query_vars The query vars.
     *
     * @return array
     */
    public function query_vars( $query_vars ) {
        array_push( $query_vars, 'az' );
        return $query_vars;
    }

    /**
     * Check our value
     *
     * @param object $query The query object.
     *
     * @global object $wp_query The WP_Query.
     *
     * @return void
     */
    public function check_qv( $query ) {
        if ( $query->is_main_query() && $query->is_archive() && isset( $query->query_vars[ 'az' ] ) && !empty( $query->query_vars[ 'az' ] ) ) {
            // If we are on the main query and the query var 'a2z' exists, modify the where/orderby statements
            add_filter( 'posts_where', array( $this, 'modify_query_where' ) );
            add_filter( 'posts_orderby', array( $this, 'modify_query_orderby' ) );
        }
    }

    /**
     * Alter the SQL
     *
     * @param string $where The where part of the query.
     *
     * @global object $wp_query The WP_Query.
     * @global object $wpdb The WPdb object.
     *
     * @return string
     */
    public function modify_query_where( $where ) {
        global $wp_query, $wpdb;
        $where .= " AND substring( TRIM( LEADING 'A ' FROM TRIM( LEADING 'AN ' FROM TRIM( LEADING 'THE ' FROM UPPER( $wpdb->posts.post_title ) ) ) ), 1, 1) = '" . $wp_query->query_vars[ 'az' ] . "'";
        remove_filter( 'posts_where', array( $this, 'modify_query_where' ) );
        return $where;
    }

    /**
     * Alter the SQL
     *
     * @param string $orderby The SQL query for the orderby part.
     *
     * @global object $wpdb The WPdb object.
     *
     * @return string
     */
    public function modify_query_orderby( $orderby ) {
        global $wpdb;
        $orderby = "( TRIM( LEADING 'A ' FROM TRIM( LEADING 'AN ' FROM TRIM( LEADING 'THE ' FROM UPPER( $wpdb->posts.post_title ) ) ) ) )";

        remove_filter( 'posts_orderby', array( $this, 'modify_query_orderby' ) );
        return $orderby;
    }

}

new Glossary_a2z_Archive();
