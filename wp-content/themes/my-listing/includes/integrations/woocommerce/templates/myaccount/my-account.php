<?php
/**
 * My Account page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="i-section">
	<div class="container section-body reveal">
		<div class="row">
			<div class="col-md-8 col-md-push-2 col-sm-12">
				<?php wc_print_notices() ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-2">
				<?php do_action( 'woocommerce_account_navigation' ) ?>
			</div>

			<div class="col-md-8 col-sm-12">
				<div class="woocommerce-MyAccount-content">
					<?php do_action( 'woocommerce_account_content' ) ?>
				</div>
			</div>
		</div>
	</div>
</section>