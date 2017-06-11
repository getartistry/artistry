<?php
/**
 * Collector header
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/public/partials
 */
$wsi_hook       = isset( $_GET['wsi_hook'] ) && $_GET['wsi_hook'] == 'anyone' ? 'true' : '';
$widget_id      = !empty( $_GET['widget_id'] ) ? esc_attr( $_GET['widget_id'] ) : '';
$wsi_loker      = !empty( $_GET['wsi_locker'] ) ? esc_attr( $_GET['wsi_locker'] ) : '';
$redirect_url   = !empty( $this->opts['redirect_url'] ) ? esc_attr( $this->opts['redirect_url'] ) : '';
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?php echo apply_filters('wsi/collector/css_file', WSI_PLUGIN_URL . 'public/assets/css/collector.css?v='.$this->version);?>" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo apply_filters('wsi/collector/css_upload_file', WSI_PLUGIN_URL . 'public/assets/css/jquery.fileupload-ui.css?v='.$this->version);?>" type="text/css" media="all">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo get_bloginfo('charset');?>">
	<title><?php _e('Select your Friends', 'wsi');?> - Wordpress Social Invitations</title>

	<script>
		var wsi_hook            = '<?php echo $wsi_hook;?>',
			provider            = '<?php echo $provider->getName();?>',
			provider_label      = '<?php echo ucfirst( $provider->getName() );?>',
			widget_id           = '<?php echo $widget_id;?>',
			wsi_locker          = '<?php echo $wsi_loker;?>',
			redirect_url        = '<?php echo $redirect_url;?>',
			wsi_url             = '<?php echo WSI_PLUGIN_URL;?>'
	</script>
	<script src="<?php echo site_url('wp-includes/js/jquery/jquery.js');?>"></script>
</head>
<body>
<form id="collect_emails" method="post" action="">

	<input type="hidden" name="action" value="add_to_wsi_queue"/>
	<input type="hidden" id="nonce" name="nonce" value="<?php echo wp_create_nonce( 'wsi-ajax-nonce' );?>"/>

