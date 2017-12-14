<?php
/**
 * The Template for list saved cards on checkout
 *
 * Override this template by copying it to yourtheme/woocommerce/checkout/stripe-checkout-cards.php
 *
 * @var $gateway YITH_WCStripe_Gateway_Advanced
 * @var $customer array
 * @var $user WP_User
 *
 * @author 		YIThemes
 * @package 	YITH WooCommerce Stripe/Templates
 * @version     1.0.0
 * @deprecated
 */

if ( version_compare( WC()->version, '2.6', '>=' ) ) {
	_deprecated_file( basename( __FILE__ ), '1.2.9', wc_locate_template( 'myaccount/form-add-payment-method.php' ), 'Make sure your theme doesn\'t use the template stripe-add-card.php and also use /payment-methods/ page instead of /saved-cards/ page.' );
	wc_get_template( 'myaccount/form-add-payment-method.php' );
	return;
}

?>
<div class="payment_box">
	<form class="add-card edit-address-form" action method="post" id="add_payment_method">

		<?php $gateway->credit_card_form( array( 'fields_have_names' => false ) ); ?>

		<p class="submit-button form-row">
			<?php wp_nonce_field( 'stripe-add-card' ) ?>

			<label style="display:inline-block;margin-right:15px;">
				<?php _e( 'Set as default', 'yith-woocommerce-stripe' ) ?>
				&nbsp;
				<input type="checkbox" name="set_as_default" value="1" <?php if ( ! isset( $customer['cards'] ) || count( $customer['cards'] ) < 1 ) : ?>checked="checked" disabled="disabled"<?php endif; ?> />
			</label>

			<input type="submit" class="btn btn-ghost-blue" name="add-card" value="<?php _e( 'Add card', 'yith-woocommerce-stripe' ) ?>">
			<input type="hidden" name="stripe-action" value="add-card">
			<input type="hidden" name="billing_email" value="<?php echo $user->billing_email ?>">
			<input type="hidden" name="wc-yith-stripe-payment-token" value="new">
		</p>

	</form>
</
<div>