<?php
    if( !function_exists('tm_add_ajax_cart')){
        function tm_add_ajax_cart(){
            global $woocommerce;
            ?>
            <!--HTML-->
            <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
            <?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?>
            </a>
            <!--/HTML-->
            <?php
        }
    }
    if( !function_exists('woocommerce_mini_cart')){
        function woocommerce_mini_cart( $args = array() ) {

            $defaults = array(
            'list_class' => '',
            );

            $args = wp_parse_args( $args, $defaults );

            wc_get_template( 'cart/mini-cart.php', $args );
        }
    }  
    if( !function_exists('woocommerce_header_add_to_cart_fragment') ){
        function woocommerce_header_add_to_cart_fragment( $fragments ) {
            global $woocommerce;
            ob_start();
            ?>
            <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
            <?php

            $fragments['a.cart-contents'] = ob_get_clean();

            return $fragments;
        }
    }
    if( !function_exists('tm_get_minicart_template') ){
        function tm_get_minicart_template( $located, $template_name, $args, $template_path, $default_path ) {    

            if ( 'cart/mini-cart.php' == $template_name ) {
                $located = TM_PLUGIN_DIR.'/woocommerce/cart/mini-cart.php';
            }
            
            return $located;
        }
    }
    
    function tm_mini_cart_dynamic_css(){
        $et_shop_mini_cart = get_option('et_shop_mini_cart');

        /* Import Fonts */
        $fonts_to_import = array(
            $et_shop_mini_cart['title_font'], $et_shop_mini_cart['price_font'], $et_shop_mini_cart['subtotal_font'],
            $et_shop_mini_cart['buttons_text_font'],
        );
        foreach ($fonts_to_import as $font) {
            if($font != '' && ($font != 'Default')){
                echo '@import url(\'https://fonts.googleapis.com/css?family='.$font.'\');';
            }
        }

        ?>
            <style>
                /* B a c k g r o u n d */
                    .tm-mini-cart .mini_cart_content{
                        background-color: <?php echo $et_shop_mini_cart['bg_color'];?>;
                    }
                /* I c o n */
                    .tm-mini-cart .cart-toggler{
                        width:<?php 
                            $icon_size = intval($et_shop_mini_cart['icon_size']);
                            if( $icon_size ) echo $icon_size+26;?>px;   
                    }
                    .tm-mini-cart .cart-toggler::before{
                        color: <?php echo $et_shop_mini_cart['icon_color'];?>;
                        font-size: <?php echo $et_shop_mini_cart['icon_size'];?>px;
                    }
                    .tm-mini-cart .cart-toggler:hover::before{
                        color: <?php echo $et_shop_mini_cart['icon_hover_color'];?>;
                    }
                    .tm-mini-cart .cart-toggler .mini-cart-link{
                        background-color: <?php echo $et_shop_mini_cart['icon_color'];?>;
                    }
                /* P r o d u c t   t i t l e*/
                    .tm-mini-cart .mini_cart_content ul.cart_list li a.product-name{
                        color: <?php echo $et_shop_mini_cart['title_color'];?> !important;
                        letter-spacing: <?php echo $et_shop_mini_cart['title_letter_spacing'];?>px;
                        font-size: <?php echo $et_shop_mini_cart['title_size'];?>px !important;
                        font-family: <?php echo $et_shop_mini_cart['title_font'];?>;
                        font-weight: <?php if($et_shop_mini_cart['title_font_b']=='true') echo 'bold';?>;
                        font-style: <?php if($et_shop_mini_cart['title_font_i']=='true') echo 'italic';?>;
                        text-transform: <?php if($et_shop_mini_cart['title_font_tt']=='true') echo 'uppercase';?>;
                        text-decoration: <?php if($et_shop_mini_cart['title_font_u']=='true') echo 'underline';?>;
                    }
                    /* Trash icon gets the same color*/
                        .tm-mini-cart div.mini_cart_content div.product-details a.remove i{
                            color: <?php echo $et_shop_mini_cart['title_color'];?> !important;
                        }
                /* P r i c e  f o n t */
                    .tm-mini-cart div.mini_cart_content div.product-details span.quantity span.woocommerce-Price-amount.amount{
                        color: <?php echo $et_shop_mini_cart['price_color'];?> !important;
                        letter-spacing: <?php echo $et_shop_mini_cart['price_letter_spacing'];?>px;
                        font-size: <?php echo $et_shop_mini_cart['price_size'];?>px !important;
                        font-family: <?php echo $et_shop_mini_cart['price_font'];?>;
                        font-weight: <?php if($et_shop_mini_cart['price_font_b']=='true') echo 'bold';?>;
                        font-style: <?php if($et_shop_mini_cart['price_font_i']=='true') echo 'italic';?>;
                        text-transform: <?php if($et_shop_mini_cart['price_font_tt']=='true') echo 'uppercase';?>;
                        text-decoration: <?php if($et_shop_mini_cart['price_font_u']=='true') echo 'underline';?>;
                    }
                /* S u b t o t a l  f o n t*/
                    .tm-mini-cart .mini_cart_content p.total{
                        color: <?php echo $et_shop_mini_cart['subtotal_color'];?> !important;
                        letter-spacing: <?php echo $et_shop_mini_cart['subtotal_letter_spacing'];?>px;
                        font-size: <?php echo $et_shop_mini_cart['subtotal_size'];?>px !important;
                        font-family: <?php echo $et_shop_mini_cart['subtotal_font'];?>;
                        font-weight: <?php if($et_shop_mini_cart['subtotal_font_b']=='true') echo 'bold';?>;
                        font-style: <?php if($et_shop_mini_cart['subtotal_font_i']=='true') echo 'italic';?>;
                        text-transform: <?php if($et_shop_mini_cart['subtotal_font_tt']=='true') echo 'uppercase';?>;
                        text-decoration: <?php if($et_shop_mini_cart['subtotal_font_u']=='true') echo 'underline';?>;
                    }
                /* B u t t o n s*/
                    .tm-mini-cart p.buttons a.button{
                        color: <?php echo $et_shop_mini_cart['buttons_text_color'];?> !important;
                        letter-spacing: <?php echo $et_shop_mini_cart['buttons_text_letter_spacing'];?>px;
                        font-size: <?php echo $et_shop_mini_cart['buttons_text_size'];?>px !important;
                        font-family: <?php echo $et_shop_mini_cart['buttons_text_font'];?>;
                        font-weight: <?php if($et_shop_mini_cart['buttons_text_font_b']=='true') echo 'bold';?>;
                        font-style: <?php if($et_shop_mini_cart['buttons_text_font_i']=='true') echo 'italic';?>;
                        text-transform: <?php if($et_shop_mini_cart['buttons_text_font_tt']=='true') echo 'uppercase';?>;
                        text-decoration: <?php if($et_shop_mini_cart['buttons_text_font_u']=='true') echo 'underline';?>;
                        background-color: <?php echo $et_shop_mini_cart['buttons_bg_color'];?> !important; 
                        border-color: <?php echo $et_shop_mini_cart['buttons_border_color'];?> !important;
                        border-radius: <?php echo $et_shop_mini_cart['buttons_border_radius'];?>px;
                        border-width: <?php echo $et_shop_mini_cart['buttons_border_size'];?>px;
                    }
                    .tm-mini-cart p.buttons a.button:hover{
                        color: <?php echo $et_shop_mini_cart['buttons_text_hover_color'];?> !important;
                        background-color: <?php echo $et_shop_mini_cart['buttons_bg_hover_color'];?> !important; 
                        border-color: <?php echo $et_shop_mini_cart['buttons_border_hover_color'];?> !important;
                    }
                
            </style>
        <?php
    }

    function tm_minicart_enqueue_styles(){
        wp_enqueue_style(
            'tm-minicart-style',
            TM_PLUGIN_URI . '/css/tm-minicart.css', 
            array()
        );
        wp_enqueue_style(
            'tm-custom-scroll-css',
            TM_PLUGIN_URI . '/css/tm-custom-scroll.css', 
            array()
        );
	}
    function tm_minicart_enqueue_scripts(){
        wp_enqueue_script(
            'tm-minicart-js',
            TM_PLUGIN_URI . '/js/tm-minicart.js',
            array(),
            null,
            true
        );
        wp_enqueue_script(
            'tm-custom-scroll-js',
            TM_PLUGIN_URI . '/js/tm-custom-scroll.js',
            array('jquery'),
            null,
            true
        );
        $et_shop_mini_cart = get_option('et_shop_mini_cart');
        if($et_shop_mini_cart && $et_shop_mini_cart['theme']){
            if($et_shop_mini_cart['theme']==='Dark'){
                wp_enqueue_script(
                    'tm-scroll-dark',
                    TM_PLUGIN_URI . '/js/tm-scroll-dark.js',
                    array('jquery'),
                    null,
                    true
                );
            }elseif($et_shop_mini_cart['theme']==='Dotted'){
                wp_enqueue_script(
                    'tm-scroll-dots',
                    TM_PLUGIN_URI . '/js/tm-scroll-dots.js',
                    array('jquery'),
                    null,
                    true
                );
            }else{
                wp_enqueue_script(
                    'tm-scroll-light',
                    TM_PLUGIN_URI . '/js/tm-scroll-light.js',
                    array('jquery'),
                    null,
                    true
                );
            }
        
        }
	}

    // Activate mini cart 
    $et_shop_mini_cart = get_option('et_shop_mini_cart');
    if($et_shop_mini_cart['active'] === 'on'){
        add_action('wp_footer', 'woocommerce_mini_cart');
        add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
        add_filter( 'wc_get_template', 'tm_get_minicart_template', 10, 5 );
        add_action('wp_head','tm_mini_cart_dynamic_css');
        add_action( 'wp_enqueue_scripts', 'tm_minicart_enqueue_styles' );
	    add_action('wp_enqueue_scripts', 'tm_minicart_enqueue_scripts');   
    }


?>