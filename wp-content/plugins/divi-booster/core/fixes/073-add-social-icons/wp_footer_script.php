<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); 

$networks = array(
	'linkedin'=>'LinkedIn',
	'youtube'=>'YouTube',
	'pinterest'=>'Pinterest',
	'tumblr'=>'Tumblr',
	'instagram'=>'Instagram',
	'skype'=>'Skype',
	'flikr'=>'Flickr',
	'myspace'=>'MySpace',
	'vimeo'=>'Vimeo'
);
?>

jQuery(function($){
	<?php 	
	foreach($networks as $k=>$v) {
		if (isset($option[$k]) and !empty($option[$k])) { 
			$url = $option[$k];
			if (!preg_match('#^(http:|https:|skype:|/)#', $url)) { $url = "http://$url"; }
			?>
			$('.et-social-icons').append('<li class="et-social-icon et-social-<?php esc_attr_e($k); ?>"><a href="<?php esc_attr_e($url); ?>" class="icon"><span><?php esc_html_e($v); ?></span></a></li>&nbsp;');
			<?php
		}
	} 
	?>
});