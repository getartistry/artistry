<?php
/**
 * WP Job Manager Integrations
 *
 * @since 1.0.0
 */
namespace CASE27\Integrations\Paid_Listings;

/**
 * WP Job Manager Integrations.
 *
 * @since 1.0.0
 */
class WP_Job_Manager {

	/**
	 * Use singleton instance.
	 */
	use \CASE27\Traits\Instantiatable;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 */
	public function __construct() {

		// Account is required, because $0 package will skip order flow.
		add_filter( 'pre_option_job_manager_user_requires_account', '__return_true' );

		// Register post status.
		add_action( 'init', array( $this, 'register_post_status' ) );
		add_filter( 'job_manager_valid_submit_job_statuses', array( $this, 'add_valid_listing_status' ) );
		add_filter( 'the_job_status', array( $this, 'set_listing_status_label' ), 10, 2 );
		add_action( 'init', array( $this, 'set_listing_expiry_on_status_update' ), 12 );

		// Submit Job.
		add_filter( 'submit_job_steps', array( $this, 'submit_listing_steps' ), 20 );
		add_action( 'init', array( $this, 'set_submit_form_listing_package' ) );

		// Set package usage.
		add_action( 'init', array( $this, 'set_package_usage_init' ), 99 );

		// Filter listings in user package when requested via URL.
		add_filter( 'parse_query', array( $this, 'filter_listings_by_user_package' ) );

		// Implement field visibility by package.
		add_filter( 'case27\listings\fields', array( $this, 'listing_fields_visibility' ), 30, 2 );
		add_filter( 'case27\listings\fields\admin', array( $this, 'listing_fields_visibility' ), 30, 2 );
	}

	/**
	 * Register Listing Status.
	 *
	 * @since 1.0.0
	 */
	public function register_post_status() {
		register_post_status( 'pending_payment', array(
			'label'                     => esc_html__( 'Pending Payment', 'my-listing' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => false,
			'show_in_admin_status_list' => true,
			// translators: %s is label count.
			'label_count'               => _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'my-listing' ),
		) );
	}

	/**
	 * Set "Pending Payment" as Valid Status. 
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses Valid job manager statuses.
	 * @return array
	 */
	public function add_valid_listing_status( $statuses ) {
		$status[] = 'pending_payment';
		return $statuses;
	}

	/**
	 * Filter job status name/label.
	 *
	 * @since 2.0.0
	 *
	 * @param string $status Listing status.
	 * @param object $job    WP_Post.
	 * @return string
	 */
	public function set_listing_status_label( $status, $job ) {
		if ( 'pending_payment' === $job->post_status ) {
			$status = __( 'Pending Payment', 'my-listing' );
		}
		return $status;
	}

	/**
	 * Set Job Expiry On Status Change
	 *
	 * @since 1.0.0
	 */
	public function set_listing_expiry_on_status_update() {
		global $job_manager;
		add_action( 'pending_payment_to_publish', array( $job_manager->post_types, 'set_expiry' ) );
	}

	/**
	 * Submit Listing Steps
	 *
	 * @since 1.0.0
	 *
	 * @param  array $steps Form Steps.
	 * @return array
	 */
	public function submit_listing_steps( $steps ) {
		$steps['wc-choose-package'] = array(
			'name'     => __( 'Choose a package', 'my-listing' ),
			'view'     => array( $this, 'choose_package' ),
			'handler'  => array( $this, 'choose_package_handler' ),
			'priority' => 5,
		);
		$steps['wc-process-package'] = array(
			'name'     => '',
			'view'     => false,
			'handler'  => array( $this, 'process_package_handler' ),
			'priority' => 25,
		);
		return $steps;
	}

	/**
	 * Choose Package View
	 *
	 * @since 1.0.0
	 */
	public function choose_package() {
		$form = \WP_Job_Manager_Form_Submit_Job::instance();
		$type = false;
		$package_ids = array();

		// Get IDs of packages that are allowed for this listing type.
		if ( isset( $_GET['listing_type'] ) ) {
			// Post object of listing type entry.
			$listing_type = get_page_by_path( $_GET['listing_type'], OBJECT, 'case27_listing_type' );

			// If listing type is set by URL.
			if ( $listing_type ) {

				// Get type of selected listing.
				$type = new \CASE27\Integrations\ListingTypes\ListingType( $listing_type );

				// Get Product Ids allowed in this type.
				$package_ids = array_column( $type->get_packages(), 'package' );
			}
		}

		// Get the products that are allowed for this listing type.
		$products = self::get_products( $package_ids );

		// Get user bought packages that are allowed for this listing type.
		$user_packages = self::get_user_packages( get_current_user_id(), $package_ids );
		?>
		<section class="i-section c27-packages">
			<div class="container">
				<div class="row section-title reveal reveal_visible">
					<p><?php _e( 'Pricing', 'my-listing' ) ?></p>
					<h2 class="case27-primary-text"><?php _e( 'Choose a Package', 'my-listing' ) ?></h2>
				</div>
				<form method="post" id="job_package_selection">
					<div class="job_listing_packages">
						<?php get_job_manager_template( 'listing-package-selection.php', array(
							'packages'       => $products,
							'packages_count' => count( $products ),
							'user_packages'  => $user_packages,
							'type'           => $type,
						) ); ?>

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

	/**
	 * Set Submit Form Listing Package
	 *
	 * @since 1.0.0
	 */
	public function set_submit_form_listing_package() {
		global $case27_is_user_package; $case27_package_id;

		if ( isset( $_POST['listing_package'] ) && $_POST['listing_package'] ) {
			$post = get_post( $_POST['listing_package'] );
			if ( $post ) {
				$case27_package_id = $post->ID;
				if ( 'product' === $post->post_type ) {
					$case27_is_user_package = false;
				} elseif ( 'case27_user_package' === $post->post_type ) {
					$case27_is_user_package = true;
				}
			}
		}
	}

	/**
	 * Choose Package
	 *
	 * @since 1.0.0
	 */
	public function choose_package_handler() {
		$form = \WP_Job_Manager_Form_Submit_Job::instance();

		// Listing package is required.
		if ( ! isset( $_POST['listing_package'] ) || ! $_POST['listing_package'] ) {
			$form->add_error( esc_html__( 'No Listing Package Selected', 'my-listing' ) );
			$form->set_step( array_search( 'wc-choose-package', array_keys( $form->get_steps() ) ) );
			return false;
		}

		// Validate Package.
		$package_id = $_POST['listing_package'];
		$user_package = false;
		$valid = true;
		$post = get_post( $package_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'product', 'case27_user_package' ) ) ) {
			$valid = false;
		} else {
			if ( 'product' === $post->post_type ) {
				$product = wc_get_product( $post->ID );
				if ( ! $product || ! ( $product->is_type( 'job_package' ) || $product->is_type( 'job_package_subscription' ) ) ) {
					$valid = false;
				}
			} elseif ( is_user_logged_in() && 'case27_user_package' === $post->post_type ) {
				if ( ! $post->_user_id || absint( get_current_user_id() ) !== absint( $post->_user_id ) ) {
					$valid = false;
				} else {
					$user_package = true;
				}
			}
		}
		if ( ! $valid ) {
			$form->add_error( esc_html__( 'Invalid package', 'my-listing' ) );
			$form->set_step( array_search( 'wc-choose-package', array_keys( $form->get_steps() ) ) );
			return false;
		}

		// Store selection in cookie
		wc_setcookie( 'chosen_package_id', absint( $package_id ) );
		wc_setcookie( 'chosen_package_is_user_package', $user_package ? 1 : 0 );

		// Go to next step.
		$form->next_step();
	}

	/**
	 * Process Package Handler.
	 *
	 * @since 1.0.0
	 */
	public function process_package_handler() {
		$form = \WP_Job_Manager_Form_Submit_Job::instance();

		// Var.
		$listing_id      = $form->get_job_id();
		$package_id      = absint( $_COOKIE['chosen_package_id'] );
		$is_user_package = absint( $_COOKIE['chosen_package_is_user_package'] ) === 1;

		// Process Package.
		if ( 'preview' === get_post_status( $listing_id ) ) {
			// Update job listing
			$update_listing = array(
				'ID'            => $listing_id,
				'post_status'   => 'pending_payment',
				'post_date'     => current_time( 'mysql' ),
				'post_date_gmt' => current_time( 'mysql', 1 ),
				'post_author'   => get_current_user_id(),
			);
			wp_update_post( $update_listing );
		}

		// User Package.
		if ( $is_user_package ) {

			// Implement user package to listing.
			case27_paid_listing_use_user_package_to_listing( $package_id, $listing_id );

		} else { // Product.
			$product = wc_get_product( $package_id );
			if ( absint( $product->get_price() ) ) {
				case27_paid_listing_use_product_to_listing( $product->get_id(), $listing_id );
			} else {
				$user_package_id = case27_paid_listing_add_package( array(
					'user_id'    => get_current_user_id(),
					'product_id' => $product->get_id(),
					'duration'   => $product->get_duration(),
					'limit'      => $product->get_limit(),
					'featured'   => $product->is_listing_featured(),
				) );

				// Use it.
				if ( $user_package_id ) {
					case27_paid_listing_use_user_package_to_listing( $user_package_id, $listing_id );
				}
			}

			// Implement product to listing.
		}

		$form->next_step();
	}

	/**
	 * Get All Listing Products.
	 *
	 * @since 1.0.0
	 *
	 * @param array $package_ids Packages IDs.
	 * @return array
	 */
	public static function get_products( $package_ids = array() ) {
		$product_ids = case27_paid_listing_get_products( array(
			'post__in' => $package_ids,
		) );

		// Use WC Products.
		$products = array();
		if ( $product_ids ) {
			foreach ( $product_ids as $product_id ) {
				$products[ $product_id ] = wc_get_product( $product_id );
			}
		}

		return $products;
	}

	/**
	 * Get User Packages
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 * @param array $product_ids Allowed WC Product IDs,
	 */
	public static function get_user_packages( $user_id, $product_ids = array() ) {
		// Get packages.
		$package_ids = case27_paid_listing_get_user_packages( array(
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => '_user_id',
					'value'   => $user_id,
					'compare' => 'IN',
				),
				array(
					'key'     => '_product_id',
					'value'   => $product_ids,
					'compare' => 'IN',
				),
			),
		) );

		// Set package object.
		$packages = array();
		foreach ( $package_ids as $package_id ) {
			$packages[ $package_id ]= case27_paid_listing_get_package( $package_id );
		}

		return $packages;
	}

	/**
	 * Set Package Usage Init.
	 * Increase/decrease package based on status update.
	 *
	 * @since 1.0.0
	 */
	public function set_package_usage_init() {
		$statuses = get_job_listing_post_statuses();

		// Do not track trash and publish status change.
		unset( $statuses['publish'] );

		// Increase package usage based on listing status update.
		foreach ( $statuses as $status => $label ) {
			add_action( $status . '_to_publish', array( $this, 'increase_package_count' ) );
		}
	}

	/**
	 * Increase Package Usage and Re-Check & Update Status.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post Object.
	 */
	public function increase_package_count( $post ) {
		if ( $post->post_type !== 'job_listing' && ! $post->_user_package_id ) {
			return;
		}

		// Check if package exists.
		$package = get_post( $post->_user_package_id );
		if ( ! $package ) {
			return;
		}

		// Update package count.
		case27_paid_listing_user_package_increase_count( $package->ID );

		// Update package status.
		$status = case27_paid_listing_get_proper_status( $package );
		if ( $status && $package->post_status !== $status ) {
			wp_update_post( array(
				'ID'          => $package->ID,
				'post_status' => $status,
			) );
		}
	}

	/**
	 * Filter Listing By User Package in Admin via URL.
	 *
	 * @since 1.0.0
	 *
	 * @param object $query Current Query Object.
	 * @return object
	 */
	public function filter_listings_by_user_package( $query ) {
		global $typenow;

		if ( 'job_listing' === $typenow ) {
			if ( isset( $_GET['_user_package_id'] ) && $_GET['_user_package_id'] ) {
				$query->query_vars['meta_key']   = '_user_package_id';
				$query->query_vars['meta_value'] = absint( $_GET['_user_package_id'] );

				// Display admin notice to inform user that they are viewing filtered listings.
				add_action( 'admin_notices', function() {
					// Display this notice only once.
					global $_case27_filter_listings_by_user_package;
					if ( isset( $_case27_filter_listings_by_user_package ) ) {
						return;
					}
					$_case27_filter_listings_by_user_package = 1;
					?>
					<div class="notice notice-info">
						<p><?php printf( __( 'You are viewing Listings using Payment Package %s', 'my-listing' ), '<a href="' . esc_url( get_edit_post_link( $_GET['_user_package_id'] ) ) . '">#' . absint( $_GET['_user_package_id'] ) . '</a>' ); ?></p>
					</div>
					<?php
				} );
			}
		}

		return $query;
	}

	/**
	 * Listing Field Visibility.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $fields  Fields data.
	 * @param object $listing Listing.
	 * @return array
	 */
	public function listing_fields_visibility( $fields, $listing ) {

		// Field visibility is too late to be loaded here for "switch package".
		// "Switch Package" Class will handle this using "submit_job_form_fields_get_job_data" filter hook.
		if ( ! is_admin() && isset( $_POST['payment-package'], $_GET['action'], $_GET['job_id'] ) ) {
			return $fields;
		}

		$fields = array_filter( $fields, function( $field ) use ( $listing ) {
			$conditions = new \CASE27\Classes\Conditions\Conditions( $field, $listing );
			return $conditions->passes();
		} );
		return $fields;
	}

}

WP_Job_Manager::instance();
