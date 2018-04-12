<?php
/**
 * WP Job Manager.
 */

class CASE27_WooCommerce_Integration {

	public function __construct()
	{
		// Handle Custom Queries.
		require_once CASE27_INTEGRATIONS_DIR . '/woocommerce/woocommerce-queries.php';

		// BLOCK WRAPS.
		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/dashboard.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/dashboard.php',
			'title' => __( 'Dashboard', 'my-listing' ),
			'icon' => 'material-icons://home',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/orders.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/orders.php',
			'title' => __( 'Orders', 'my-listing' ),
			'icon' => 'material-icons://shopping_basket',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/view-order.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/view-order.php',
			'title' => __( 'View Order', 'my-listing' ),
			'icon' => 'material-icons://shopping_basket',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/downloads.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/downloads.php',
			'title' => __( 'Downloads', 'my-listing' ),
			'icon' => 'material-icons://file_download',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/form-edit-address.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/form-edit-address.php',
			'title' => __( 'Addresses', 'my-listing' ),
			'icon' => 'material-icons://map',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/form-edit-account.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/form-edit-account.php',
			'title' => __( 'Account Details', 'my-listing' ),
			'icon' => 'material-icons://account_circle',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_account_products_published_before',
			'end' => 'case27_woocommerce_account_products_published_after',
			'title' => __( 'Published Products', 'my-listing' ),
			'icon' => 'material-icons://view_list',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_account_products_pending_before',
			'end' => 'case27_woocommerce_account_productspending_after',
			'title' => __( 'Pending Products', 'my-listing' ),
			'icon' => 'material-icons://view_list',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_account_add_product_before',
			'end' => 'case27_woocommerce_account_add_product_after',
			'title' => __( 'Add a Product', 'my-listing' ),
			'icon' => 'material-icons://note_add',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_account_listings_before',
			'end' => 'case27_woocommerce_account_listings_after',
			'title' => __( 'My Listings', 'my-listing' ),
			'icon' => 'material-icons://store_mall_directory',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_promoted_listings_before',
			'end' => 'case27_woocommerce_promoted_listings_after',
			'title' => __( 'Promotion Packages', 'my-listing' ),
			'icon' => 'material-icons://vpn_key',
			]);


		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/payment-methods.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/payment-methods.php',
			'title' => __( 'Payment Methods', 'my-listing' ),
			'icon' => 'material-icons://payment',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_bookmarks_before',
			'end' => 'case27_woocommerce_bookmarks_after',
			'title' => __( 'Bookmarked Listings', 'my-listing' ),
			'icon' => 'material-icons://bookmark_border',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_wc_vendors_store_before',
			'end' => 'case27_woocommerce_wc_vendors_store_after',
			'title' => __( 'My Store', 'my-listing' ),
			'icon' => 'material-icons://store',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_wc_vendors_store_settings_before',
			'end' => 'case27_woocommerce_wc_vendors_store_settings_after',
			'title' => __( 'Store Settings', 'my-listing' ),
			'icon' => 'material-icons://settings',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/my-subscriptions.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/my-subscriptions.php',
			'title' => __( 'Subscriptions', 'my-listing' ),
			'icon' => 'material-icons://monetization_on',
			]);

		$this->wrap_page_in_block([
			'start' => 'case27_woocommerce_before_template_part_myaccount/view-subscription.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/view-subscription.php',
			'title' => __( 'Subscriptions', 'my-listing' ),
			'icon' => 'material-icons://monetization_on',
			]);

		// COLUMN WRAPS.
		$this->wrap_page_in_column([
			'start' => 'case27_woocommerce_before_template_part_checkout/form-coupon.php',
			'end' => 'case27_woocommerce_after_template_part_checkout/form-coupon.php',
			'classes' => 'c27-form-coupon-wrapper',
			]);


		// SECTION WRAPS.
		$this->wrap_page_in_section([
			'start' => 'woocommerce_before_cart',
			'end' => 'woocommerce_after_cart',
			'title' => '',
			'icon' => 'icon-shopping-basket-1',
			'columns' => 'col-md-12',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_template_part_cart/cart-empty.php',
			'end' => 'case27_woocommerce_after_template_part_cart/cart-empty.php',
			'title' => '',
			'icon' => 'icon-shopping-basket-1',
			'columns' => 'col-md-12',
			'classes' => 'i-section empty-cart-wrapper',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_template_part_checkout/cart-errors.php',
			'end' => 'case27_woocommerce_after_template_part_checkout/cart-errors.php',
			'title' => '',
			'icon' => 'icon-shopping-basket-1',
			'columns' => 'col-md-12',
			'classes' => 'i-section cart-errors-wrapper',
			]);

		$this->wrap_page_in_section([
			'start' => 'woocommerce_before_checkout_form',
			'end' => 'woocommerce_after_checkout_form',
			'title' => '',
			'icon' => 'icon-shopping-basket-1',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_thankyou_template',
			'end' => 'case27_woocommerce_after_thankyou_template',
			'title' => '',
			'icon' => 'icon-shopping-basket-1',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_template_part_myaccount/form-lost-password.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/form-lost-password.php',
			'title' => __( 'Lost your password?', 'my-listing' ),
			'icon' => 'material-icons://lock_outline',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_template_part_myaccount/form-reset-password.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/form-reset-password.php',
			'title' => __( 'Reset your password', 'my-listing' ),
			'icon' => 'material-icons://lock_outline',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_woocommerce_before_template_part_myaccount/lost-password-confirmation.php',
			'end' => 'case27_woocommerce_after_template_part_myaccount/lost-password-confirmation.php',
			'title' => __( 'Reset your password', 'my-listing' ),
			'icon' => 'material-icons://lock_outline',
			]);

		$this->wrap_page_in_section([
			'start' => 'wpjmcl_submit_claim_form_claim_listing_view_before',
			'end' => 'wpjmcl_submit_claim_form_claim_listing_view_after',
			'title' => __( 'Claim this listing', 'my-listing' ),
			'icon' => 'material-icons://view_list',
			]);

		$this->wrap_page_in_section([
			'start' => 'case27_wpjmcl_login_register_view_before',
			'end' => 'case27_wpjmcl_login_register_view_after',
			'title' => __( 'Claim this listing', 'my-listing' ),
			'icon' => 'material-icons://view_list',
			]);

		// Actions/Filters.
		add_action('woocommerce_before_template_part', [$this, 'before_template_action']);
		add_action('woocommerce_after_template_part', [$this, 'after_template_action']);
	}

	public function before_template_action($template)
	{
		do_action('case27_woocommerce_before_template_part_' . $template);
	}

	public function after_template_action($template)
	{
		do_action('case27_woocommerce_after_template_part_' . $template);
	}

	public function wrap_page_in_block($page) {
		add_action($page['start'], function($args = []) use ($page) {
			if (!is_array($args)) $args = [];
			$page = c27()->merge_options($page, (array) $args);
			?>
			<div class="element">
				<div class="pf-head round-icon">
					<div class="title-style-1">
						<?php echo c27()->get_icon_markup($page['icon']) ?>
						<h5><?php echo esc_html( $page['title'] ) ?></h5>
					</div>
				</div>
				<div class="pf-body">
		<?php });

		add_action($page['end'], function() { ?>
				</div>
			</div>
		<?php });
	}

	public function wrap_page_in_section($page) {
		add_action($page['start'], function($args = []) use ($page) {
			if (!is_array($args)) $args = [];
			$page = c27()->merge_options($page, (array) $args);

			if ( empty( $page['columns'] ) ) {
				$page['columns'] = 'col-md-10 col-md-offset-1';
			}

			if ( empty( $page['classes'] ) ) {
				$page['classes'] = 'i-section';
			}
			?>
			<section class="<?php echo esc_attr( $page['classes'] ) ?>">
				<div class="container">
					<div class="row section-body reveal">
						<div class="<?php echo esc_attr( $page['columns'] ) ?>">
							<div class="element">
								<div class="pf-head round-icon">
									<div class="title-style-1">
										<?php echo c27()->get_icon_markup($page['icon']) ?>
										<h5><?php echo $page['title'] ? esc_html( $page['title'] ) : get_the_title() ?></h5>
									</div>
								</div>
								<div class="pf-body">
		<?php });

		add_action($page['end'], function() { ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php });
	}

	public function wrap_page_in_column($page) {
		add_action($page['start'], function($args = []) use ($page) {
			if (!is_array($args)) $args = [];
			$page = c27()->merge_options($page, (array) $args);
			?>
			<div class="container <?php echo esc_attr( $page['classes'] ) ?>">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
		<?php });

		add_action($page['end'], function() { ?>
					</div>
				</div>
			</div>
		<?php });
	}
}

new CASE27_WooCommerce_Integration;

if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
	function woocommerce_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title case27-secondary-text">' . get_the_title() . '</h2>';
	}
}