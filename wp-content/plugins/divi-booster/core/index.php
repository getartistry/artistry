<?php // Core plugin framework

// === Load the core plugin class ===
include(dirname(__FILE__).'/wtfplugin_1_0.class.php');

// === Load the update checker ===
include(dirname(__FILE__).'/updates/plugin-update-checker.php');

// === Load the module options ===
include(dirname(__FILE__).'/module-options.php'); // Load the module options


// === Automatic updates ===
function booster_enable_updates($file) {
	try {
		$MyUpdateChecker = new Divi_Booster_PluginUpdateChecker(BOOSTER_PACKAGE_URL, $file, BOOSTER_PACKAGE_NAME);
	} catch (Exception $e) { echo "Update error: ".$e->getMessage(); exit; }
}

// === Error handling ===

function booster_error($msg, $details="") { 
	update_option(BOOSTER_OPTION_LAST_ERROR, $msg);
	update_option(BOOSTER_OPTION_LAST_ERROR_DESC, $details);
	return false;
}

// === Minification ===

// JavaScript minification
function booster_minify_js($js) {		
	if (!class_exists('JSMin')) { 
		include_once(dirname(__FILE__).'/libs/JSMin.php');
	}
	try {
		return JSMin::minify($js);
	} catch (Exception $e) {
		return $js; // Something went wrong, so fall back to unminified js
	}
} 

// CSS minification - modified from: https://github.com/GaryJones/Simple-PHP-CSS-Minification/blob/master/minify.php
function booster_minify_css($css) { 
	// Normalize whitespace
	$css = preg_replace( '/\s+/', ' ', $css );
	// Remove spaces before and after comment
	$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
	// Remove comment blocks, everything between /* and */, unless preserved with /*! ... */ or /** ... */
	$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
	// Remove ; before }
	$css = preg_replace( '/;(?=\s*})/', '', $css );
	// Remove space after , : ; { } */ >
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
	// Remove space before , ; { } ) >
	$css = preg_replace( '/ (,|;|\{|}|\)|>)/', '$1', $css );
	// Strips leading 0 on decimal values (converts 0.5px into .5px)
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	// Strips units if value is 0 (converts 0px to 0)
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	// Converts all zeros value into short-hand
	$css = preg_replace( '/0 0 0 0/', '0', $css );
	// Shorten 6-character hex color codes to 3-character where possible
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
	return trim($css);
}