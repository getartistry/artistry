<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); 


$footerhtml = (empty($option['footerhtml']) or !is_string($option['footerhtml']))?'':$option['footerhtml'];
$footerhtml = preg_replace('#</?p(\s[^>]*)?>#i', '', $footerhtml); // Strip paragraph tags as it breaks the formatting 
$footerhtml = str_replace('”', '"', $footerhtml); // Fix bad double quotes

$footerhtml = do_shortcode($footerhtml);
?>

jQuery(function($){
	$('#footer-info').html(<?php echo json_encode($footerhtml); ?>);
});