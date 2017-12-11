<?php
/**
Plugin Name: WP Real Media Library
Plugin URI: http://matthias-web.de
Description: Organize your wordpress media library in a nice way.
Author: Matthias Günter
Version: 2.1.2
Author URI: http://matthias-web.de
Licence: GPLv2
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('RML_PATH', dirname ( __FILE__ ));
define('RML_FILE', __FILE__);
define('RML_TD', 'real-media-library');
define('RML_VERSION', '2.1.2');

// Localize the plugin
add_action( 'plugins_loaded', "rml_plugins_laoded" );
function rml_plugins_laoded() {
    load_plugin_textdomain( RML_TD, FALSE, dirname(plugin_basename(__FILE__)).'/languages/' );
}

// Load core
require_once(dirname ( __FILE__ ) . '/RML_Core.class.php');

/*
USE Query with custom attribute rml_folder
$query = new WP_Query(array(
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'rml_folder' => 4
	//'meta_query' => array(array('key' => '_rml_folder',        'value' => 4,         'compare' => '='        ))
));
RML_Core::print_r($query);*/

// Start core
RML_Core::start();
?>