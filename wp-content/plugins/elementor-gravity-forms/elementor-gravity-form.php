<?php
/**
 * Plugin Name: Gravity Forms styler for Elementor
 * Description: Gravity Forms styler for elementor. Design the gravity form visually with elementor.
 * Plugin URI: https://essential-addons.com/elementor/gravity-forms
 * Author: Essential Addons
 * Version: 1.0.0
 * Author URI: https://essential-addons.com/elementor/
 *
 * Text Domain: elementor-gravity-forms
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'EAEL_GRAVITY_FORM_URL', plugins_url( '/', __FILE__ ) );
define( 'EAEL_GRAVITY_FORM_PATH', plugin_dir_path( __FILE__ ) );


require_once EAEL_GRAVITY_FORM_PATH.'includes/elementor-helper.php';
require_once EAEL_GRAVITY_FORM_PATH.'includes/queries.php';
require_once EAEL_GRAVITY_FORM_PATH.'admin/settings.php';


// Upsell
include_once dirname( __FILE__ ) . '/includes/eael-gravity-form-upsell.php';
new Eael_Gravity_Form_Upsell('');
/**
 * Load Elementor Gravity Form
 */
function add_eael_gravity_form() {

  if ( class_exists( 'GFForms' ) ) {
    require_once EAEL_GRAVITY_FORM_PATH.'includes/gravity-form.php';
  }

}
add_action('elementor/widgets/widgets_registered','add_eael_gravity_form');

/**
 * Load Eael Gravity Form CSS
 */
function eael_gravity_form_enqueue() {

   wp_enqueue_style('essential_addons_elementor-gravity-forms-css',EAEL_GRAVITY_FORM_URL.'assets/css/elementor-gravity-forms.css');

}
add_action( 'wp_enqueue_scripts', 'eael_gravity_form_enqueue' );

/**
 * Admin Notices
 */
function eael_gravity_form_admin_notice() {
	if( !class_exists( 'GFForms' ) ) :
	?>
		<div class="error notice is-dismissible">
			<p><strong>Elementor Gravity Form styler</strong> needs <strong>Gravity Forms</strong> plugin to be installed. Please get the plugin now! <a href="https://www.gravityforms.com/" target="_blank" rel="nofollow">Get Now!</a></p>
		</div>
	<?php
	endif;
}
add_action( 'admin_notices', 'eael_gravity_form_admin_notice' );
