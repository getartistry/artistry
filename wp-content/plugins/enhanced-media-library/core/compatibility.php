<?php

if ( ! defined( 'ABSPATH' ) )
    exit;



/**
 *  wpuxss_eml_elementor_scripts
 *  @TODO: temporary solution
 *
 *  @since    2.5
 *  @created  28/01/18
 */

add_action( 'elementor/editor/after_enqueue_scripts', 'wpuxss_eml_elementor_scripts' );

if ( ! function_exists( 'wpuxss_eml_elementor_scripts' ) ) {

    function wpuxss_eml_elementor_scripts() {

        global $wpuxss_eml_dir;


        wp_enqueue_style( 'common' );
        wp_enqueue_style(
            'wpuxss-eml-elementor-media-style',
            $wpuxss_eml_dir . 'css/eml-admin-media.css'
        );
    }
}
