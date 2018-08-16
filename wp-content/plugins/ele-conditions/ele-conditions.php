<?php
/*
 * Plugin Name: Ele Conditions
 * Version: 1.0.2
 * Description: Elementor conditions for elements and widgets.
 * Plugin URI: https://www.eletemplator.com
 * Author: Liviu Duda
 * Author URI: https://www.leadpro.ro
 * Text Domain: elecond
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'ELECONDITIONS_DIR', plugin_dir_path( __FILE__ ));
require_once ELECONDITIONS_DIR.'inc/controls.php';

// Add custom keywords to the eletheme
add_filter( 'eleconditions_vars', 'elecond_keywords');
function elecond_keywords( $custom_vars ) {
    $custom_vars['now']=date('Y-m-d H:i:s');
    return $custom_vars;
}