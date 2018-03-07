<?php
/**
 * @var $iframe_url string
 * @var $type string
 * @var $email_subject string
 * @var $template string
 * @var $args array
 */

if ( ! defined( 'ABSPATH' ) ) exit;

AutomateWoo\Admin::register_scripts();

wp_enqueue_style('dashicons');
wp_enqueue_style('wp-admin');
wp_enqueue_style('buttons');

if ( ! $test_emails = get_user_meta( get_current_user_id(), 'automatewoo_email_preview_test_emails', true ) ) {
	$user = wp_get_current_user();
	$test_emails = $user->user_email;
}

?>

<html>

	<head>
		<title><?php _e('AutomateWoo Email Preview', 'automatewoo') ?></title>
		<link rel='stylesheet' id='open-sans-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&#038;subset=latin%2Clatin-ext&#038;ver=4.4.2' type='text/css' media='all' />
		<link rel="stylesheet" href="<?php echo AW()->admin_assets_url( '/css/email-preview.css' ) ?>">

		<script type="text/javascript">
			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>'
		</script>

		<?php print_admin_styles() ?>

		<script type='text/javascript' src='<?php echo includes_url('js/jquery/jquery.js') ?>'></script>
		<script type='text/javascript' src='<?php echo includes_url('js/jquery/jquery-migrate.js') ?>'></script>
		<script src="<?php echo AW()->admin_assets_url( '/js/email-preview.js?ver=' . AW()->version ) ?>"></script>

	</head>

	<body class="wp-core-ui wp-admin">

	<div class="email-preview-container">
		<div class="email-preview-header">

			<div class="email-preview-header-left">
				<div class="from"><strong><?php _e('From', 'automatewoo') ?>:</strong> <?php echo AutomateWoo\Emails::get_from_name( $template ) ?> &lt;<?php echo AutomateWoo\Emails::get_from_address( $template ) ?>&gt;</div>
				<div class="from"><strong><?php _e('Subject', 'automatewoo') ?>:</strong> <?php echo $email_subject ?></div>
			</div>

			<div class="email-preview-header-right">
				<form class="email-preview-send-test-form">
					<input type="text" value="<?php echo $test_emails ?>" name="to_emails" class="email-input" placeholder="<?php _e( 'Comma separate emails...', 'automatewoo') ?>">
					<input type="hidden" name="type" value="<?php echo esc_attr( $type ) ?>">
					<input type="hidden" name="args" value='<?php echo json_encode( $args ) ?>'>

					<button type="submit" class="button-secondary"><?php _e('Send test', 'automatewoo') ?></button>
				</form>
			</div>

		</div>

		<iframe class="email-iframe" src="<?php echo $iframe_url ?>" width="100%" frameborder="0"></iframe>
	</div>

	</body>
</html>

