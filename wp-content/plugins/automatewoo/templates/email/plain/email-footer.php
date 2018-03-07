<?php
/**
 * Plain email footer
 *
 * Override this template by copying it to yourtheme/automatewoo/email/plain/email-footer.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="automatewoo-plain-email-footer">
	<br><span>&ndash;</span><br>
	<?php echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ?>
</div>

</body></html>
