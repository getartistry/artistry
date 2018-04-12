<?php
/**
 * ONE Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ONE
 * @since 1
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ONE_VERSION', '1' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'one-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ONE_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/*- Translate -*/

add_filter(  'gettext',  'wps_translate_words_array'  );
add_filter(  'ngettext',  'wps_translate_words_array'  );
function wps_translate_words_array( $translated ) {
     $words = array(
                        // 'word to translate' = > 'translation'
                        'Permalink' => 'Link',
                        'Related Products' => 'Related',
                        'OUT OF STOCK' => 'Sold',
                        'Howdy' => 'Hi',
                        'Woocommerce Status' => 'Sales',
                        'Woocommerce Recent Reviews' => 'Reviews',
                        'Google Analytics Dashboard' => 'Analytics',
                        'Add to cart' => 'Purchase',
                    );
     $translated = str_ireplace(  array_keys($words),  $words,  $translated );
     return $translated;
}

/*- Redirect to checkout -*/

add_filter ('woocommerce_add_to_cart_redirect', 'woo_redirect_to_checkout');
function woo_redirect_to_checkout() {
	$checkout_url = WC()->cart->get_checkout_url();
	return $checkout_url;
}
