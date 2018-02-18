<?php
/*
Plugin Name: Divi Booster
Plugin URI: 
Description: Bug fixes and enhancements for Elegant Themes' Divi Theme.
Author: Dan Mossop
Version: 2.7.1
Author URI: https://divibooster.com
*/		

// === Configuration === //

$slug = 'wtfdivi';
define('BOOSTER_FILE', __FILE__);
define('BOOSTER_DIR', dirname(BOOSTER_FILE));
define('BOOSTER_CORE', BOOSTER_DIR.'/core');
define('BOOSTER_SLUG', 'divi-booster');
define('BOOSTER_SLUG_OLD', $slug);
define('BOOSTER_VERSION', '2.7.1');
define('BOOSTER_VERSION_OPTION', 'divibooster_version');
define('BOOSTER_SETTINGS_PAGE_SLUG', BOOSTER_SLUG_OLD.'_settings');
define('BOOSTER_NAME', __('Divi Booster', BOOSTER_SLUG));

// Updates
define('BOOSTER_PACKAGE_NAME', 'divi-booster');
define('BOOSTER_PACKAGE_URL', 'https://dansupdates.com/?action=get_metadata&slug='.BOOSTER_PACKAGE_NAME);

// Error Handling
define('BOOSTER_OPTION_LAST_ERROR', 'wtfdivi_last_error');
define('BOOSTER_OPTION_LAST_ERROR_DESC', 'wtfdivi_last_error_details');

// Directories
define('BOOSTER_DIR_FIXES', BOOSTER_CORE.'/fixes/');

// Theme info
$template = get_template();
$theme = wp_get_theme($template);
define('BOOSTER_THEME_NAME', divibooster_get_theme_name());
define('BOOSTER_THEME_VERSION', $theme->Version);

// === Setup ===		
include(BOOSTER_CORE.'/index.php'); // Load the plugin framework
booster_enable_updates(BOOSTER_FILE); // Enable auto-updates for this plugin

include(BOOSTER_CORE.'/update_patches.php'); // Apply update patches

// === Divi-Specific functions ===

// Returns the active theme name
function divibooster_get_theme_name() {
	
	// Check template name
	$template = get_template();
	if (strpos($template, 'Divi')!==false) { return 'Divi'; }
	if (strpos($template, 'Extra')!==false) { return 'Extra'; }
	
	// Check theme name
	$theme = wp_get_theme($template);
	$theme_name = empty($theme->Name)?'':$theme->Name;
	if (strpos(@$theme->Name, 'Divi')!==false) { return true; }
	if (strpos(@$theme->Name, 'Extra')!==false) { return true; }
	
	// Return 
	return $theme->Name;
}

function divibooster_is_divi() { return (BOOSTER_THEME_NAME === 'Divi'); }
function divibooster_is_extra() { return (BOOSTER_THEME_NAME === 'Extra'); }

// Returns true if theme is Divi 2.4 or higher
function is_divi24() { 
	$template = get_template();
	$theme = wp_get_theme($template);
	return (divibooster_is_divi() and version_compare($theme->Version, '2.3.9', '>=')); 
} 

// === Build the plugin ===

$sections = array(
	'general'=>'Site-wide Settings',
	'general-icons'=>'Icons',
	'general-layout'=>'Layout',
	'general-links'=>'Links',
	'general-speed'=>'Site Speed',
	/*'general-social'=>'Social Media',*/
	'header'=>'Header',
	'header-top'=>'Top Header',
	'header-main'=>'Main Header',
	'header-mobile'=>'Mobile Header',
	'posts'=>'Posts',
	//'pages'=>'Pages',
	'sidebar'=>'Sidebar',
	'footer'=>'Footer',
	'pagebuilder'=>'Divi Builder',
	'pagebuilder-divi'=>'Standard Builder',
	'pagebuilder-visual'=>'Visual Builder',
	'modules'=>'Modules',
	'modules-accordion'=>'Accordion',
	'modules-blurb'=>'Blurb',
	'modules-countdown'=>'Countdown',
	'modules-gallery'=>'Gallery',
	'modules-headerfullwidth'=>'Header (Full Width)',
	'modules-map'=>'Map',
	'modules-portfolio'=>'Portfolio',
	'modules-portfoliofiltered'=>'Portfolio (Filterable)',
	'modules-portfoliofullwidth'=>'Portfolio (Full Width)',
	'modules-postnav'=>'Post Navigation',
	'modules-postslider'=>'Post Slider',
	'modules-pricing'=>'Pricing Table',
	/*'modules-shop'=>'Shop',*/
	'modules-subscribe'=>'Signup',
	'modules-slider'=>'Slider',
	'modules-text'=>'Text',
	'plugins'=>'Plugins',
	'plugins-edd'=>'Easy Digital Downloads',
	'plugins-woocommerce'=>'WooCommerce',
	'plugins-other'=>'Other',
	'customcss'=>'CSS Manager',
	'developer'=>'Developer Tools',
	'developer-export'=>'Import / Export',
	'developer-css'=>'Generated CSS',
	'developer-js'=>'Generated JS',
	'developer-footer-html'=>'Generated Footer HTML',
	'developer-htaccess'=>'Generated .htaccess Rules',
	'deprecated'=>'Deprecated (now available in Divi)',
	/*'deprecated-divi30'=>'Divi 3.0',*/
	'deprecated-divi24'=>'Divi 2.4',
	'deprecated-divi23'=>'Pre Divi 2.4'
);


// JavaScript dependencies
function divibooster_add_dependencies($dependencies) {
	// Add divi custom.js as a dependency to ensure it loads first
	if (wp_script_is('divi-custom-script', 'enqueued')) { 
		$dependencies[] = 'divi-custom-script';
	} elseif (wp_script_is('divi-custom-script-child', 'enqueued')) { // Munder Difflin pre 2.0
		$dependencies[] = 'divi-custom-script-child';
	}
	return $dependencies;
}
add_filter("$slug-js-dependencies", 'divibooster_add_dependencies');


// === Load the customizer class ===
include(BOOSTER_CORE.'/customizer/customizer_1_0.class.php'); // Load the customizer library
$divibooster_customizer = new booster_customizer_1_0($slug);

// === Main plugin ===

$admin_menu = (is_divi24() or !divibooster_is_divi())?'et_divi_options':'themes.php';
if (divibooster_is_extra()) { 
	$admin_menu = 'et_extra_options';
}


$wtfdivi = new wtfplugin_1_0(
	array(
		'theme'=>$theme,
		'plugin'=>array(
			'name'=>BOOSTER_NAME,
			'shortname'=>BOOSTER_NAME, // menu name
			'slug'=>$slug,
			'package_slug'=>BOOSTER_PACKAGE_NAME,
			'plugin_file'=>BOOSTER_FILE,
			'url'=>'https://divibooster.com/themes/divi/',
			'basename'=>plugin_basename(BOOSTER_FILE), 
			'admin_menu'=>$admin_menu
		),
		'sections'=>$sections
	)
);

// === Load the settings ===
function divibooster_load_settings($wtfdivi) {
	$settings_files = glob(BOOSTER_DIR_FIXES.'*/settings.php');
	if ($settings_files) { 
		foreach($settings_files as $file) { include_once($file); }
	}
}
add_action("$slug-before-settings-page", 'divibooster_load_settings');


// === Add settings page hook ===
function divibooster_settings_page_init() {
	global $pagenow, $plugin_page;
	if ($pagenow == 'admin.php' and $plugin_page == BOOSTER_SETTINGS_PAGE_SLUG) {
		do_action('divibooster_settings_page_init');
	}
}
add_action('admin_init', 'divibooster_settings_page_init');



// Load media library
function db_enqueue_media_loader() { wp_enqueue_media(); }
add_action('admin_enqueue_scripts', 'db_enqueue_media_loader', 11); // Priority > 10 to avoid visualizer plugin conflict

// =========================================================== //
// ==                          FOOTER                       == //
// =========================================================== //

// === Footer ===
function divibooster_footer() { ?>
<p>Spot a problem with this plugin? Want to make another change to the Divi Theme? <a href="https://divibooster.com/contact-form/">Let me know</a>.</p>
<p><i>This plugin is an independent product which is not associated with, endorsed by, or supported by Elegant Themes.</i></p>
<?php
}	
add_action($wtfdivi->slug.'-plugin-footer', 'divibooster_footer');

