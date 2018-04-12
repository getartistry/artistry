<?php

namespace CASE27\Integrations\PaidListings;

use \CASE27\Classes\Conditions\Conditions as Conditions;

class PaidListings {

	public function __construct()
	{

		if ( ! class_exists( '\WC_Paid_Listings' ) ) {
			return;
		}

		add_filter('submit_job_steps', [$this, 'submit_job_steps'], 30);
		add_filter( 'pre_option_job_manager_paid_listings_flow', function() {
			return 'before';
		}, 50);
		add_filter( 'case27\listings\fields', [ $this, 'listing_fields' ], 30, 2 );
		add_filter( 'case27\listings\fields\admin', [ $this, 'listing_fields' ], 30, 2 );

		if (class_exists('\\WC_Paid_Listings_Orders')) {
			remove_action('woocommerce_before_my_account', [\WC_Paid_Listings_Orders::get_instance(), 'my_packages']);
			add_action('case27_woocommerce_after_template_part_myaccount/dashboard.php', [\WC_Paid_Listings_Orders::get_instance(), 'my_packages'], 30);
		}
	}

	public function listing_fields( $fields, $listing ) {
		// $this->job_id = ! empty( $_REQUEST['job_id'] ) ? absint( $_REQUEST[ 'job_id' ] ) : 0;
		$fields = array_filter( $fields, function( $field ) use ( $listing ) {
			$conditions = new Conditions( $field, $listing );
			// dump("Package: {$conditions->get_package_id()}");
			// dump(['field' => $field['slug'], 'logic' => $field['conditional_logic'], 'passes' => $conditions->passes(), 'conditions' => $field['conditions']]);

			return $conditions->passes();
		});

		return $fields;
	}

	public function submit_job_steps($steps)
	{
		if ( isset($steps['wc-choose-package']) ) {
			$steps['wc-choose-package']['view'] = [$this, 'choose_package_view'];
		}

		return $steps;
	}

	public static function filter_wcpl_get_job_packages_args($args) {
			$args['orderby'] = 'post__in';

			return $args;
	}

	public static function get_packages($packages = []) {
		if ( class_exists( '\\WP_Job_Manager_WCPL_Submit_Job_Form' ) ) {
			add_filter( 'wcpl_get_job_packages_args', [ __CLASS__, 'filter_wcpl_get_job_packages_args' ] );

			$packages = \WP_Job_Manager_WCPL_Submit_Job_Form::get_packages( $packages );

			remove_filter( 'wcpl_get_job_packages_args', [ __CLASS__, 'filter_wcpl_get_job_packages_args' ] );
		} elseif ( get_option( 'case27_paid_listings' ) && function_exists( 'case27_paid_listing_get_products' ) ) {
			$packages = case27_paid_listing_get_products( array(
				'fields' => false,
			) );
		}

		return $packages;
	}

	public static function get_user_packages( $user_id, $allowed_packages = [] ) {
		global $wpdb;

		$query = "SELECT * FROM {$wpdb->prefix}wcpl_user_packages WHERE user_id = %d AND package_type = 'job_listing' ";
		if ( count( $allowed_packages ) ) {
			$query .= "AND product_id IN ( '" . implode( "','", $allowed_packages ) . "' ) ";
		}
		$query .= 'AND ( package_count < package_limit OR package_limit = 0 );';

		$packages = $wpdb->get_results( $wpdb->prepare( $query, $user_id ), OBJECT_K );

		// dump($query);

		return $packages;
	}

	public function choose_package_view($atts = [])
	{
		$form = \WP_Job_Manager_Form_Submit_Job::instance();
		$type = false;
		$package_ids = [];

		// Get IDs of packages that are allowed for this listing type.
		if ( ! empty( $_GET['listing_type'] ) && ( $listing_type = ( get_page_by_path( $_GET['listing_type'], OBJECT, 'case27_listing_type' ) ) ) ) {
			$type = new \CASE27\Integrations\ListingTypes\ListingType( $listing_type );
			$package_ids = array_column( $type->get_packages(), 'package' );
		}

		// Get the packages that are allowed for this listing type.
		$packages = self::get_packages( $package_ids );

		// Get user bought packages that are allowed for this listing type.
		$user_packages = self::get_user_packages( get_current_user_id(), $package_ids );

		// dump($package_ids, self::get_user_packages( get_current_user_id(), $package_ids ), $user_packages);
		// dump($type->get_packages());
		?>
		<section class="i-section c27-packages">
			<div class="container">
				<div class="row section-title reveal reveal_visible">
					<p><?php _e( 'Pricing', 'my-listing' ) ?></p>
					<h2 class="case27-primary-text"><?php _e( 'Choose a Package', 'my-listing' ) ?></h2>
				</div>
				<form method="post" id="job_package_selection">
					<div class="job_listing_packages">
						<?php get_job_manager_template( 'package-selection.php', [
							'packages' => $packages,
							'user_packages' => $user_packages,
							'type' => $type,
						], 'wc-paid-listings', JOB_MANAGER_WCPL_PLUGIN_DIR . '/templates/' ); ?>

						<div class="row section-body">
							<br>
							<!-- <button type="submit" name="continue" class="button buttons button-2 button-animated listing-details-button" value="">
								<?php echo apply_filters( 'submit_job_step_choose_package_submit_text', __( 'Listing Details', 'my-listing' ) ); ?>
								<i class="material-icons">keyboard_arrow_right</i>
							</button> -->
							<!-- <input type="submit" name="continue" class="button buttons button-2 button-animated" style="width: auto; float: right;" value="" /> -->
							<input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
							<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
							<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form->form_name ); ?>" />
						</div>
					</div>
				</form>
			</div>
		</section>
		<?php
	}

}

new PaidListings;