<?php
/**
 * Plugin Name: Stylish Links
 * Plugin URI: https://wordpress.org/plugins/stylish-links
 * Description: Spruce up your links with subtle CSS3 styles. Discrete control of underline, background, and text colors - including underline thickness and offset.
 * Version: 1.0.3
 * Author: Industrial Themes
 * Author URI: http://www.industrialthemes.com
 * License: GPL2
 * Text Domain: sl
 */

// Grab the ReduxCore framework
require_once (dirname(__FILE__) . '/options/framework.php');

// Grab the plugin settings
require_once (dirname(__FILE__) . '/sl-config.php');

# load admin assets - this is for redux framework modifications
add_action( 'admin_enqueue_scripts', 'sl_enqueued_assets_admin' );
function sl_enqueued_assets_admin() {
	wp_enqueue_script( 'sl-admin-js', plugin_dir_url( __FILE__ ) . 'js/sl-admin.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'sl-admin-css', plugin_dir_url( __FILE__ ) . 'css/sl-admin.css', false, false, 'all');
}

# load front-end assets
add_action( 'wp_enqueue_scripts', 'sl_enqueued_assets' );
function sl_enqueued_assets() {
	wp_enqueue_style( 'sl-css', plugin_dir_url( __FILE__ ) . 'css/sl.css', false, false, 'all');
	wp_enqueue_style( 'sl-icons', plugin_dir_url( __FILE__ ) . 'options/assets/css/vendor/elusive-icons/elusive-icons.css', false, false, 'all');
}

# add the custom css to the footer
add_action('wp_footer','sl_custom_css');
function sl_custom_css() {
	$options = get_option( 'sl_settings' );
	# these options can't be output directly from redux and need to be custom implemented
	$underline_color = $options['color-underline'];
	$underline_color_hover = $options['color-underline-hover'];
	$bg = $options['color-content-background'];
	$thickness = $options['general-underline-thickness'];
	$offset = $options['general-underline-offset'];
	$text_hover = $options['color-text-hover'];
	$bg_hover = $options['color-background-hover'];
	$sl_css = $options['sl-css'];
	$css = '';
	if($underline_color && $bg) $css .= 'a.stylish-link, li.stylish-link > a {background-image: linear-gradient('.$bg.','.$bg.'),linear-gradient('.$bg.','.$bg.'),linear-gradient('.$underline_color.','.$underline_color.')}';
	if($underline_color_hover && $bg) $css .= 'a.stylish-link:hover, li.stylish-link > a:hover {background-image: linear-gradient('.$bg.','.$bg.'),linear-gradient('.$bg.','.$bg.'),linear-gradient('.$underline_color_hover.','.$underline_color_hover.')}';
	if($bg) $css .= 'a.stylish-link, li.stylish-link > a {text-shadow: 0.03em 0 '.$bg.', -0.03em 0 '.$bg.', 0 0.03em '.$bg.', 0 -0.03em '.$bg.', 0.06em 0 '.$bg.', -0.06em 0 '.$bg.', 0.09em 0 '.$bg.', -0.09em 0 '.$bg.', 0.12em 0 '.$bg.', -0.12em 0 '.$bg.', 0.15em 0 '.$bg.', -0.15em 0 '.$bg.';}';
	if($thickness) $css .= 'a.stylish-link, li.stylish-link > a {background-size: .05em 1px,.05em 1px,1px '.$thickness.'px;}';
	if($offset) $css .= 'a.stylish-link, li.stylish-link > a {background-position:0 95%,100% 95%,0 '.$offset.'%;}';
	if($text_hover) $css .= 'a.stylish-link:hover, li.stylish-link > a:hover {color:'.$text_hover.'!important;}';
	# add the custom css from the plugin options last
	$css .= $sl_css;
	if(!empty($css)) echo '<style type="text/css">' . $css . '</style>';
}

# see what places we filter the_content() throughout the theme
add_filter('the_content', 'sl_filter_content', 10, 2);
function sl_filter_content( $content ) {
	$options = get_option( 'sl_settings' );
	
	if(is_page() && $options['general-enable-pages']) {
		$content = sl_style_links($content);
	}
	if(is_single() && $options['general-enable-posts']) {
		$content = sl_style_links($content);
	}
    return $content;
}

# add class to links within passed content
function sl_style_links($content) {
	$dom = new DOMDocument('1.0', 'UTF-8');
    @$dom->loadHTML('<?xml encoding="utf-8"?>' . $content);
    $x = new DOMXPath($dom);
    foreach($x->query('//a') as $node) {  
    	$existing = $node->hasAttribute('class') ? $node->getAttribute('class') . ' ' : '';
        if($existing != 'stylish-link ') $node->setAttribute('class',$existing.'stylish-link');
    }
    $content = $dom->saveHTML($dom->documentElement);
    return $content;
}

# add general options to body class to unclutter anchors in content
add_filter('body_class', 'sl_filter_body_class');
function sl_filter_body_class($classes) {
    $options = get_option( 'sl_settings' );
	if($options['general-hide-outline-hover']) $classes[] = 'sl-hide-outline-hover';

    return $classes;
}

?>