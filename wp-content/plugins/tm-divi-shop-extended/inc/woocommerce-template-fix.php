<?php

    add_filter( 'wc_get_template', 'cma_get_template', 10, 5 );
    function cma_get_template( $located, $template_name, $args, $template_path, $default_path ) {    

        /*if ( 'single-product.php' == $template_name ) {
            $located = TM_PLUGIN_DIR.'/woocommerce/single-product.php';
        }*/
        if ( 'single-product/meta.php' == $template_name ) {
            $located = TM_PLUGIN_DIR.'/woocommerce/single-product/meta.php';
        }
        if ( 'single-product/price.php' == $template_name ) {
            $located = TM_PLUGIN_DIR.'/woocommerce/single-product/price.php';
        }
        if ( 'single-product/rating.php' == $template_name ) {
            $located = TM_PLUGIN_DIR.'/woocommerce/single-product/rating.php';
        }
        
        return $located;
    }

?>