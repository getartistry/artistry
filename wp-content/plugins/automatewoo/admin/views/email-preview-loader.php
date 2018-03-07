<?php

if ( ! defined( 'ABSPATH' ) ) exit;

AutomateWoo\Admin::register_scripts();

wp_enqueue_style('dashicons');
wp_enqueue_style('wp-admin');
wp_enqueue_style('buttons');

?>

<html>

<head>
	<title><?php _e('AutomateWoo Email Preview', 'automatewoo') ?></title>
	<link rel="stylesheet" href="<?php echo AW()->admin_assets_url( '/css/email-preview.css' ) ?>">

	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>'
	</script>

	<?php print_admin_styles() ?>

	<script type='text/javascript' src='<?php echo includes_url('js/jquery/jquery.js') ?>'></script>
	<script type='text/javascript' src='<?php echo includes_url('js/jquery/jquery-migrate.js') ?>'></script>
	<script src="<?php echo AW()->admin_assets_url( '/js/email-preview.js?ver=' . AW()->version ) ?>"></script>

</head>

<body>
	<div class="aw-email-preview">
		<div class="aw-email-preview__loader-container">
			<div class="aw-email-preview__loader aw-loader"></div>
		</div>
	</div>
</body>
</html>

