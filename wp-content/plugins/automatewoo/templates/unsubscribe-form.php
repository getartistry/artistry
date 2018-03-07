<?php
/**
 * Override this template by copying it to yourtheme/automatewoo/unsubscribe-form.php
 *
 * @var $unsubscribe_confirm_url
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

	<div class="aw-unsubscribe">

		<p><strong><?php echo __( "Don't want to receive any more emails like this?", 'automatewoo' ) ?></strong></p>

		<p><?php echo __( "Please note: You will still receive general store notifications e.g. when you place an order.", 'automatewoo' ) ?></p>

		<p><a href="<?php echo $unsubscribe_confirm_url ?>" class="button"><?php echo __( 'Unsubscribe', 'automatewoo' ) ?></a></p>

	</div>
