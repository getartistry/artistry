<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Shortcodes.
 *
 * @class    Iconic_WooThumbs_Shortcodes
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Shortcodes {

    /*
     * Init shortcodes
     */
    public static function init_shortcodes() {

        add_shortcode( 'woothumbs-gallery', array( __CLASS__, 'gallery' ) );

    }

    public static function gallery( $atts ) {

        global $post, $iconic_woothumbs_class;

        $atts = shortcode_atts( array(
            'id' => false
        ), $atts, 'woothumbs-gallery' );

        $atts['id'] = $atts['id'] ? $atts['id'] : $post->ID;

        if( !$atts['id'] )
            return;

        ob_start();

        $post_object = get_post( $atts['id'] );

        if( !$post_object )
            return;

        setup_postdata( $GLOBALS['post'] =& $post_object );

        $iconic_woothumbs_class->show_product_images();

        wp_reset_postdata();

        return ob_get_clean();;

    }

}