<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
<?php add_filter( 'mylisting\auth_modals\show', '__return_false' ) ?>
<?php $show_register_form = get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ?>

<section class="i-section no-modal">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php wc_print_notices(); ?>
				<?php if ( ! empty( $_GET['notice'] ) ): ?>
					<?php if ( $_GET['notice'] == 'login-required' ): ?>
						<?php wc_print_notice( __( 'You must be logged in to perform this action.', 'my-listing' ), 'notice' ); ?>
					<?php endif ?>
				<?php endif ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 <?php echo ! $show_register_form ? 'col-md-push-3' : '' ?>">
				<?php c27()->get_partial('account/login-form') ?>
			</div>

			<?php if ( $show_register_form ): ?>
				<div class="col-md-6">
					<?php c27()->get_partial('account/register-form') ?>
				</div>
			<?php endif ?>
		</div>
	</div>
</section>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
