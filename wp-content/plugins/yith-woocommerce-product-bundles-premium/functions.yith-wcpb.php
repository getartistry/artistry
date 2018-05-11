<?php
if ( !function_exists( 'yith_wcpb_help_tip' ) ) {
    function yith_wcpb_help_tip( $tip, $allow_html = false ) {
        if ( function_exists( 'wc_help_tip' ) ) {
            return wc_help_tip( $tip, $allow_html );
        } else {
            if ( $allow_html ) {
                $tip = wc_sanitize_tooltip( $tip );
            } else {
                $tip = esc_attr( $tip );
            }
            $image_src = WC()->plugin_url() . '/assets/images/help.png';
            return "<img class='woocommerce-help-tip' heigth='16' width='16' data-tip='$tip' src='$image_src' />";
        }
    }
}