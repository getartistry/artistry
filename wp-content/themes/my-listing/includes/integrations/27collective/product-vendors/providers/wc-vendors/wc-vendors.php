<?php

namespace CASE27\Integrations\ProductVendors;

class WCVendorsProvider implements ProviderInterface {

	protected $path, $uri;

	public function activate() {
		if ( ! class_exists( '\\WC_Vendors' ) ) {
			return false;
		}

		$this->path = trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/product-vendors/providers/wc-vendors';
		$this->uri = trailingslashit( get_template_directory_uri() ) . 'includes/integrations/27collective/product-vendors/providers/wc-vendors';

		add_filter( 'woocommerce_locate_template', [ $this, 'locate_template' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// CSV Export wrapper.
		add_action( 'case27_woocommerce_before_template_part_csv-export.php', function() { ?> <div class="c27-wc-vendors-export"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_csv-export.php', function() { ?> </div> <?php });

		// Orders wrapper.
		add_action( 'case27_woocommerce_before_template_part_orders.php', function() { ?> <div class="c27-wc-vendors-orders"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_orders.php', function() { ?> </div> <?php });

		// Denied wrapper.
		add_action( 'case27_woocommerce_before_template_part_denied.php', function() { ?> <div class="c27-wc-vendors-denied"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_denied.php', function() { ?> </div> <?php });

		// Apply to becom a vendor.
		add_action( 'template_redirect', array( $this, 'apply_form_dashboard' ), 10 );

		// User Dashboard Pages.
		$this->dashboard_pages();
	}

	public function dashboard_pages() {
		// My Store page.
		\CASE27\Classes\DashboardPages::instance()->add_page([
			'endpoint' => 'my-store',
			'title' => __( 'My Store', 'my-listing' ),
			'template' => trailingslashit( $this->path ) . 'views/my-store.php',
			'show_in_menu' => true,
			'order' => 5,
			]);
	}

	public function locate_template( $template ) {
		$_template_name = explode('/templates/', $template);
		$template_name = array_pop($_template_name);
		$template_path = trailingslashit( $this->path ) . "views/{$template_name}";

		if ( locate_template("includes/integrations/27collective/product-vendors/providers/wc-vendors/views/{$template_name}") && file_exists($template_path) ) {
			return $template_path;
		}

		return $template;
	}

	public function apply_form_dashboard() {
		if ( !isset( $_POST[ 'apply_for_vendor' ] ) ) return false;

		$vendors_signup = new \WCV_Vendor_Signup;

		if ( is_wc_endpoint_url('my-store') ) {
			if ( $vendors_signup->terms_page ) {
				if ( isset( $_POST[ 'agree_to_terms' ] ) ) {
					$vendors_signup->save_pending( get_current_user_id() );
				} else {
					wc_add_notice( apply_filters( 'wcvendors_agree_to_terms_error', __( 'You must accept the terms and conditions to become a vendor.', 'my-listing' ), 'error' ) );
				}
			} else {
				$vendors_signup->save_pending( get_current_user_id() );
			}
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'c27-wcvendors', trailingslashit( $this->uri ) . 'styles/style.css', [], CASE27_THEME_VERSION );
	}
}

return new WCVendorsProvider;
