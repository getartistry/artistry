<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db067_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-footer-links-in-divi/'); 
	$plugin->checkbox(__FILE__); ?> Replace footer links with this text / HTML:<br><?php
	$plugin->textboxpicker(__FILE__, 'footerhtml'); ?>
<span style="margin-left:32px; font-size: 9pt; color: #555;">Shortcodes: [copy] =&gt; &copy;, [year] =&gt; <?php echo date('Y'); ?>, [yr] =&gt; <?php echo date('y'); ?></span>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('footer', 'db067_add_setting');	