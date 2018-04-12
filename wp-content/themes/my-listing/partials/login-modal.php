<?php
/**
 * Display WooCommerce login/register forms as modals.
 *
 * @since 1.0.0
 */

$data = c27()->merge_options([
    'modals' => ['login', 'register'],
    'open' => false,
], $data);

if ( is_user_logged_in() ) {
	return false;
}

if ( apply_filters( 'mylisting\auth_modals\show', true ) === false ) {
	return false;
}
?>

<?php if (in_array('login', (array) $data['modals'])): ?>
	<!-- Modal - SIGN IN-->
	<div id="sign-in-modal" class="modal fade modal-27 <?php echo $data['open'] ? 'c27-open-on-load' : '' ?>" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<?php c27()->get_partial('account/login-form') ?>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if (in_array('register', (array) $data['modals']) && get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes'): ?>
	<!-- Modal - SIGN UP -->
	<div id="sign-up-modal" class="modal fade modal-27" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<?php c27()->get_partial('account/register-form') ?>
			</div>
		</div>
	</div>
<?php endif ?>
