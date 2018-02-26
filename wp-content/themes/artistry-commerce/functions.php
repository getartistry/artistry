<?php
/**
 * Functions - Child theme custom functions
 */


/************************************************************************************************
***************** CAUTION: do not remove or edit anything within this section ******************/

/**
 * Makes the Divi Children Engine available for the child theme.
 * Do not remove this, your child theme will not work.
 */
require_once('divi-children-engine/divi_children_engine.php');

/***********************************************************************************************/

/*- Style Admin  Pannel -*/

function load_custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

/*- Translate -*/

add_filter(  'gettext',  'wps_translate_words_array'  );
add_filter(  'ngettext',  'wps_translate_words_array'  );
function wps_translate_words_array( $translated ) {
     $words = array(
                        // 'word to translate' = > 'translation'
                        'Permalink' => 'Link',
                        'Related Products' => 'Related',
                        'Use Visual Builder' => 'Build Live',
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

/*- Support Sensei -*/

add_action( 'after_setup_theme', 'divi_sensei_support' );
function divi_sensei_support() {
	add_theme_support( 'sensei' );
}

global $woothemes_sensei;
remove_action( 'sensei_before_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper' ), 10 );
remove_action( 'sensei_after_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper_end' ), 10 );

add_action('sensei_before_main_content', 'divi_sensei_wrapper_start', 10);
add_action('sensei_after_main_content', 'divi_sensei_wrapper_end', 10);

function divi_sensei_wrapper_start() {
	echo '<div id="main-content">
			<div class="container">
				<div id="content-area" class="clearfix">
					<div id="left-area">';
		//<div id="content-area" class="clearfix">'
	//echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
}

function divi_sensei_wrapper_end() {
	echo '			</div> <!-- #left-area -->';
	get_sidebar();
	echo '     </div> <!-- #content-area -->
			</div> <!-- div.container -->
        </div><!-- #main-content -->';
}


?>
