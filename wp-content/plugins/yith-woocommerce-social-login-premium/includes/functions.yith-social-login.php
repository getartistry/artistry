<?php
/**
 * General functions
 *
 * @package YITH WooCommerce Social Login
 * @since   1.0.0
 * @author  Yithemes
 */


/**
 * Return the current page
 *
 * @return string
 * @since    1.0.0
 * @author   Emanuela Castorina
 */
function ywsl_curPageURL() {
    $pageURL = 'http';
    if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ( $_SERVER["SERVER_PORT"] != "80" ) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * Sort Provider Array
 *
 * @return array
 * @since    1.0.0
 * @author   Emanuela Castorina
 */
function ywsl_providers_stats_sort( $a, $b ) {
    return $b['data'] - $a['data'];
}
