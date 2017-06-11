<?php
/**
 * Only icons for socials
 *
 * @package YITH WooCommerce Social Login
 * @since   1.0.0
 * @author  Yithemes
 */

foreach ( $socials as $key => $value ) {
    $enabled = get_option( 'ywsl_' . $key . '_enable' );

    if ( $enabled == 'yes' ) {

        $args = array(
            'value'     => $value,
            'url'       => esc_url( add_query_arg( array(
                'ywsl_social' => $key,
                'redirect'    => urlencode( ywsl_curPageURL() )
            ), site_url( 'wp-login.php' ) ) ),
            'image_url' => apply_filters( 'ywsl_custom_icon_' . $key, YITH_YWSL_ASSETS_URL . '/images/' . $key . '.png', $key ),
            'class'     => 'ywsl-social ywsl-' . $key
        );

        $image  = sprintf( '<img src="%s" alt="%s"/>', $args['image_url'], isset( $value['label'] ) ? $value['label'] : $value );
        $social = sprintf( '<a class="%s" href="%s">%s</a>', $args['class'], $args['url'], $image );

        echo apply_filters( 'yith_wc_social_login_icon', $social, $key, $args );

    }
}