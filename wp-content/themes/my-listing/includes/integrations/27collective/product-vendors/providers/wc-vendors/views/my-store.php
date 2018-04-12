<?php if ( function_exists( 'wc_print_notices' ) ) wc_print_notices(); else {
	global $woocommerce;
	wc_print_notices();
} ?>

<?php do_action( 'case27_woocommerce_wc_vendors_store_before' ) ?>
	<div class="c27-wc-vendors wc-vendors-dashboard">
		<?php echo do_shortcode( '[wcv_vendor_dashboard]' ) ?>
	</div>
<?php do_action( 'case27_woocommerce_wc_vendors_store_after' ) ?>

<?php do_action( 'case27_woocommerce_wc_vendors_store_settings_before' ) ?>
	<div class="c27-wc-vendors wc-vendors-store-settings">
		<?php echo do_shortcode( '[wcv_shop_settings]' ) ?>
	</div>
<?php do_action( 'case27_woocommerce_wc_vendors_store_settings_after' ) ?>
