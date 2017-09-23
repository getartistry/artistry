<?php
$parts = explode("wp-content", __FILE__);
if (!empty($parts)) {
	require_once($parts[0]."wp-load.php"); // load WP
}

if (!current_user_can('manage_options')) { wp_die(__('You do not have sufficient permissions to access this page.')); }
		
$options = gzencode(serialize(get_option('wtfdivi')));

// output the page
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="divi-plugin-settings.conf"');
header("Content-Length: " . strlen($options));
echo $options;
exit;
?>