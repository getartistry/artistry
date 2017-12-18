<?php

/**
 * Artistry Child Theme
 *
 * @category            WordPress_Theme
 * @package             Artistry
 * @subpackage          theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'artistry_VERSION', '1.0' );
define( 'artistry_CDIR', get_stylesheet_directory() ); // if child, will be the file path, with out backslash
define( 'artistry_CURI', get_stylesheet_uri() ); // URL, if child, will be the url to the theme directory, no back slash

add_filter ('woocommerce_add_to_cart_redirect', 'woo_redirect_to_checkout');
function woo_redirect_to_checkout() {
	$checkout_url = WC()->cart->get_checkout_url();
	return $checkout_url;
}

/**
 * Rename Widgets and Modules to Blocks
 **/

add_filter(  'gettext',  'wps_translate_words_array'  );
add_filter(  'ngettext',  'wps_translate_words_array'  );
function wps_translate_words_array( $translated ) {
     $words = array(
                        // 'word to translate' = > 'translation'
                        'Widget' => 'Block',
                        'widget' => 'block',
                        'Widgets' => 'Blocks',
                        'widgets' => 'blocks',
                        'Module' => 'Block',
                        'module' => 'block',
                        'Divi ' => 'Artistry ',
                        'Permalink' => 'Link',
                        'Related Products' => 'Related',
                        'Use Visual Builder' => 'Live Edit',
                        'Howdy' => 'Hi',
                        'Enable Visual Builder' => 'Live Edit',
                        'Woocommerce Status' => 'Sales',
                        'Woocommerce Recent Reviews' => 'Reviews',
                        'Google Analytics Dashboard' => 'Analytics',
			'Add to cart' => 'Purchase',
                        'Duplicate' => 'Duplicate',
			'Out of stock' => 'Sold',
                    );
     $translated = str_ireplace(  array_keys($words),  $words,  $translated );
     return $translated;
}

// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_stylesheet_directory_uri().'/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

/**
 * Declare Sensei support
 *
 * This is needed to hide the Sensei theme compatibility notice your admin dashboard.
 */
add_action( 'after_setup_theme', 'divi_sensei_support' );
function divi_sensei_support() {
	add_theme_support( 'sensei' );
}

/**
 * Remove the default Sensei wrappers
 */
global $woothemes_sensei;
remove_action( 'sensei_before_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper' ), 10 );
remove_action( 'sensei_after_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper_end' ), 10 );

/**
 * Add Divi specific custom  Sensei content  wrappers
 */
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

remove_action('admin_notices', 'woothemes_updater_notice');

/**
 * Clean up Head
 **/

remove_action( 'wp_head', 'wp_generator' );

function artistry_removeHeadLinks() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
}

add_action( 'init', 'artistry_removeHeadLinks' );


/**
 * Load the Parent and Child  Theme CSS.
 * This faster than a css @import
 */
function artistry_theme_enqueue_styles() {
	wp_enqueue_style( 'artistry-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'artistry-child-style', get_stylesheet_uri(), array( 'parent-style' ) );
}

add_action( 'wp_enqueue_scripts', 'artistry_theme_enqueue_styles' );

/**
 * Load a custom.css style sheet, if it exists in a child theme.
 *
 * @return void
 */



/**
 * Setup Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
/*
function artistry_theme_setup() {
	load_child_theme_textdomain( 'rotw12-child-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'artistry_theme_setup' );
*/


/**
 * Register and load font awesome CSS files using a CDN.
 *
 * @link   http://www.bootstrapcdn.com/#fontawesome
 * @author FAT Media
 */
function artistry_enqueue_awesome() {
	wp_enqueue_style( 'artistry-font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.0.3' );
}

add_action( 'wp_enqueue_scripts', 'artistry_enqueue_awesome' );


if ( ! function_exists( 'et_pb_resources_meta_box' ) ) :
	function et_pb_resources_meta_box() {
		global $post;

		?>

		<div class="et_project_meta">
			<strong class="et_project_meta_title"><?php echo esc_html__( 'Resource', 'Artistry' ); ?></strong>

			<p><a href="<?php echo get_metadata( 'post', $post->ID, "rone_resource_link_url", true ); ?>" target="_blank"> <?php echo get_metadata( 'post', $post->ID, "rone_resource_link_url", true ); ?></a></p>

			<strong class="et_project_meta_title"><?php echo esc_html__( 'Category', 'Artistry' ); ?></strong>

			<p><?php echo get_the_term_list( get_the_ID(), 'resource-categories', '', ', ' ); ?></p>

			<strong class="et_project_meta_title"><?php echo esc_html__( 'Posted on', 'Artistry' ); ?></strong>

			<p><?php echo get_the_date(); ?></p>
		</div>
	<?php }
endif;

?>