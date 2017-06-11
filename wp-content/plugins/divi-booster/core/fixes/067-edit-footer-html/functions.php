<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

/* Old method
function wtfdivi067_set_up_buffer(){
    if ( is_feed() || is_admin() ){ return; }
    ob_start('wtfdivi067_filter_page');
}
add_action('wp', 'wtfdivi067_set_up_buffer', 10, 0);
 
function wtfdivi067_filter_page($content){
	$options = get_option('wtfdivi');
	$footerhtml = $options['fixes']['067-edit-footer-html']['footerhtml'];
	$footerhtml = $newString = preg_replace('#</?p(\s[^>]*)?>#i', '', $footerhtml); // Strip paragraph tags as it breaks the formatting 
	$content = preg_replace('#<p id="footer-info">.*</p>#U','<p id="footer-info">'.do_shortcode($footerhtml).'</p>',$content); 
    return $content;
}
*/

// [year] shortcode => 2012
function wtfdivi067_year_shortcode() { return '<span class="divibooster_year"></span><script>jQuery(function($){$(".divibooster_year").text(new Date().getFullYear());});</script>'; } //date('Y'); }
add_shortcode('year', 'wtfdivi067_year_shortcode');

// [yr] shortcode => 12
function wtfdivi067_yr_shortcode() { return '<span class="divibooster_yr"></span><script>jQuery(function($){$(".divibooster_yr").text(new Date().getFullYear().toString().substr(2,2));});</script>'; } //return date('y'); }
add_shortcode('yr', 'wtfdivi067_yr_shortcode');

// [copy] shortcode => &copy;
function wtfdivi067_copy_shortcode() { return '&copy;'; }
add_shortcode('copy', 'wtfdivi067_copy_shortcode');
?>