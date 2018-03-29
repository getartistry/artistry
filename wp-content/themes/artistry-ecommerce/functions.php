<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function oceanwp_child_enqueue_parent_style() {
	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );
	// Load the stylesheet
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );

}
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );

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
                        'Enable Visual Builder' => 'Build Live',
                        'Woocommerce Status' => 'Sales',
                        'Woocommerce Recent Reviews' => 'Reviews',
                        'Google Analytics Dashboard' => 'Analytics',
	  					          'Edit With Yellow Pencil' => 'Styles',
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

/*- Clean up head -*/

remove_action( 'wp_head', 'wp_generator' );
function artistry_removeHeadLinks() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action( 'init', 'artistry_removeHeadLinks' );
