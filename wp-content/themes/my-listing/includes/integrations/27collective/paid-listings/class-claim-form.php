<?php
/**
 * Claim Form.
 * Need to make sure WP_Job_Manager_Form loaded before loading this class.
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * Extend WP Job Manager Form to Build Submit Claim Form.
 *
 * @since 1.0.0
 * @extends WP_Job_Manager_Form
 */
class Claim_Form extends \WP_Job_Manager_Form {

	public static $_instance = null;
	public $form_data = array();
	public $step = 0;
	public $listing_id = 0;

	/**
	 * Main Instance.
	 *
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set Listing ID.
		$this->listing_id = absint( $_GET['listing_id'] );

		// Form name.
		$this->form_name = 'case27_paid_listing_submit_claim';

		// Steps.
		$steps  = array(
			'login_register' => array(
				'name'     => __( 'Login / Register', 'my-listing' ),
				'view'     => array( $this, 'login_register_view' ),
				'handler'  => array( $this, 'login_register_handler' ),
				'priority' => 1,
				'submit'   => __( 'Register Account &rarr;', 'my-listing' ),
			),
			'claim_package' => array(
				'name'     => __( 'Choose a package', 'my-listing' ),
				'view'     => array( $this, 'claim_package_view' ),
				'handler'  => array( $this, 'claim_package_handler' ),
				'priority' => 2,
				'submit'   => __( 'Select Package &rarr;', 'my-listing' ),
			),
			'claim_listing' => array(
				'name'     => __( 'Claim Listing', 'my-listing' ),
				'view'     => array( $this, 'claim_listing_view' ),
				'priority' => 4,
				'submit'   => __( 'Submit Claim &rarr;', 'my-listing' ),
			),
		);
		$this->steps = apply_filters( 'case27_paid_listing_claim_form_steps', $steps );
		uasort( $this->steps, array( $this, 'sort_by_priority' ) );

		// Get step.
		if ( isset( $_POST['step'] ) ) {
			$this->step = is_numeric( $_POST['step'] ) ? max( absint( $_POST['step'] ), 0 ) : array_search( $_POST['step'], array_keys( $this->steps ) );
		} elseif ( ! empty( $_GET['step'] ) ) {
			$this->step = is_numeric( $_GET['step'] ) ? max( absint( $_GET['step'] ), 0 ) : array_search( $_GET['step'], array_keys( $this->steps ) );
		}

		// Filter page template.
		add_filter( 'page_template', array( $this, 'set_page_template' ) );
	}

	/**
	 * Setup Page Template
	 *
	 * @since 1.0.0
	 */
	public function set_page_template( $template ) {

		// Only in claim page.
		$page_id = get_option( 'job_manager_claim_listing_page_id' );
		if ( $page_id && is_page( $page_id ) ) {

			// Template to use.
			$default = locate_template( 'page.php' );
			$box = locate_template( 'page-templates/content-no-sidebar.php' );
			if ( ! $default || ! $box ) {
				return $template;
			}

			if ( is_user_logged_in() ) {
				$template = $default;
			} else {
				$template = $box;
			}

			if ( isset( $_GET['_claim_id'] ) ) {
				$template = $box;
			}
		}

		return $template;
	}

	/**
	 * Login Register View
	 *
	 * @since 1.0.0
	 */
	public function login_register_view() {
		$get_step = $this->get_step();
		$listing_id = $this->listing_id;
		?>

		<form id="<?php echo esc_attr( $this->get_form_name() ); ?>" method="post">

			<?php
			// Logout URL: Redirect to Listing ID.
			add_filter( 'submit_job_form_logout_url', function( $url ) use ( $listing_id ) {
				return esc_url( wp_logout_url( get_permalink( $listing_id ) ) );
			} );

			// Login URL: Redirect to claim listing page.
			add_filter( 'submit_job_form_login_url', function( $url ) use ( $listing_id ) {
				$redirect = add_query_arg( 'listing_id', $listing_id, get_permalink() );
				return esc_url( wp_login_url( $redirect ) );
			} );

			// Login Required Message.
			add_filter( 'submit_job_form_login_required_message', function( $url ) {
				return '';
			} );

			// Load WPJM login/register field template.
			get_job_manager_template( 'account-signin.php' );
			?>

			<fieldset class="fieldset">
				<label><?php _e( 'Listing to claim', 'my-listing' ); ?></label>
				<div class="field">
					<a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>"><strong><?php echo get_the_title( $listing_id ); ?></strong></a>
					<input type="hidden" value="<?php echo intval( $listing_id ); ?>" name="listing_id">
				</div>
			</fieldset>

			<p>
				<input type="submit" value="<?php echo esc_attr( $this->get_step_submit() ); ?>" class="button" name="submit">
				<input type="hidden" value="<?php echo intval( $get_step ); ?>" name="step">
				<?php wp_nonce_field( $action = __FILE__, $name = '_register_nonce' ) ?>
			</p>

		</form><!-- .wpjmcl_form -->

		<?php
	}


	/**
	 * Login Register Handler. Also handle all request.
	 *
	 * @since 1.0.0
	 */
	public function login_register_handler() {
		global $current_user;

		// Current Claim URL:
		$claim_url = add_query_arg( array(
			'listing_id' => $this->listing_id,
		), get_permalink() );

		// User already logged in.
		if ( is_user_logged_in() ) {

			// User visiting claim page to claim a listing, check if they already submit claim.
			if ( ! isset( $_GET['_claim_id'] ) && is_user_logged_in() ) {
				$claims = case27_paid_listing_claim_get_claims( array(
					'listing_id' => absint( $this->listing_id ),
					'user_id'    => absint( get_current_user_id() ),
				) );

				// Claim found. Bail. Use it.
				if ( $claims && isset( $claims[0] ) ) {
					wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claims[0] ), $claim_url ) ) );
					exit;
				}
			}

			// Go to next step.
			$this->next_step();

			// If claim already set, move to next step.
			if ( isset( $_GET['_claim_id'] ) || case27_paid_listing_is_claimed( $this->listing_id ) ) {
				$this->next_step();
				return;
			}

			// Product Selected.
			if ( isset( $_GET['_product_id'] ) && $_GET['_product_id'] ) {
				$product = wc_get_product( $_GET['_product_id'] );

				if ( $product && $product->is_type( array( 'job_package', 'job_package_subscription' ) ) && get_post_meta( $product->get_id(), '_use_for_claims', true ) ) {

					if ( absint( $product->get_price() ) ) {
						case27_paid_listing_use_product_to_listing( $product->get_id(), $this->listing_id, $is_claim = true );
					} else {
						$user_package_id = case27_paid_listing_add_package( array(
							'product_id'     => $product->get_id(),
							'duration'       => $product->get_duration(),
							'limit'          => $product->get_limit(),
							'featured'       => $product->is_listing_featured(),
							'use_for_claims' => $product->is_use_for_claims(),
						) );

						// Use it.
						if ( $user_package_id ) {
							$claim_id = case27_paid_listing_claim_create_claim( array(
								'listing_id'      => $this->listing_id,
								'user_package_id' => $user_package_id,
							) );

							// Refresh, use claim ID in URL.
							wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claim_id ), $claim_url ) ) );
							exit;
						}
					}

					$this->add_error( esc_html__( 'Fail to create claim.', 'my-listing' ) );

				} else {
					$this->add_error( esc_html__( 'Invalid product selected.', 'my-listing' ) );
				}

			} elseif ( isset( $_GET['_package_id'] ) && $_GET['_package_id'] ) { // User Package Selected.
				$package = case27_paid_listing_get_package( $_GET['_package_id'] );

				if ( $package->has_package() && 'publish' === $package->get_status() && $package->is_use_for_claims() ) {

					// Create claim.
					$claim_id = case27_paid_listing_claim_create_claim( array(
						'listing_id'      => $this->listing_id,
						'user_package_id' => $package->get_id(),
					) );

					if ( $claim_id ) {
						wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claim_id ), $claim_url ) ) );
						exit;
					} else {
						$this->add_error( esc_html__( 'Fail to create claim. Please try again.', 'my-listing' ) );
					}

				} else {
					$this->add_error( esc_html__( 'Invalid package selected.', 'my-listing' ) );
				}
			}

		} else { // User not logged-in. Register.

			// Register Form Submitted.
			if ( isset( $_POST['submit'] ) && isset( $_POST['_register_nonce'] ) ) {
				// Register args.
				$args = array(
					'username' => '',
					'email'    => '',
					'password' => false,
					'role'     => get_option( 'job_manager_enable_registration', '1' ) ? get_option( 'job_manager_registration_role', 'employer' ) : get_option( 'default_role' ),
				);

				// Verify Nonce.
				if ( ! wp_verify_nonce( $_POST['_register_nonce'], __FILE__ ) ) {
					$this->add_error( __( 'Security nonce not valid.', 'my-listing' ) );
					return;
				}

				// Email field: always required.
				if ( ! isset( $_POST['create_account_email'] ) ) {
					$this->add_error( __( 'Email field is required.', 'my-listing' ) );
					return;
				} else {
					$args['email'] = sanitize_email( $_POST['create_account_email'] );
				}

				// Username field: if not set, it's using email.
				if ( isset( $_POST['create_account_username'] ) ) {
					$args['username'] = sanitize_user( $_POST['create_account_username'] );
				}

				// Password field: if not set, it's disabled.
				if ( isset( $_POST['create_account_password'], $_POST['create_account_password_verify'] ) ) {

					// Password.
					$pass1 = $_POST['create_account_password'];
					$pass2 = $_POST['create_account_password_verify'];
					$password_hint = wpjm_get_password_rules_hint();

					// Validate Password.
					if ( ! wpjm_validate_new_password( $pass1 ) ) {
						$this->add_error( sprintf( __( 'Invalid Password: %s', 'my-listing' ), $password_hint ) );
						return;
					}

					// Check verify password field.
					if ( $pass1 !== $pass2 ) {
						$this->add_error( __( 'Passwords must match.', 'my-listing' ) );
						return;
					}

					// Set Password:
					$args['password'] = $pass1;
				}

				// Register User.
				$create_account = wp_job_manager_create_account( $args );

				/* Error in registering user */
				if ( is_wp_error( $create_account ) ) {
					$this->add_error( $create_account->get_error_message() );
				} else {
					wp_safe_redirect( esc_url_raw( $claim_url ) );
					die();
				}

			} else { // Initial View.
				$this->add_error( sprintf( __( 'You must <a href="%s">sign in</a> to claim a listing.', 'my-listing' ), esc_url( wp_login_url( $claim_url ) ) ) );
			}
		}
	}

	/**
	 * Select Claim Package View.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function claim_package_view() {
		$products = self::get_products();
		$user_packages = self::get_user_packages();
		$listing = get_post( $this->listing_id );
		$type = false;
		if ( $listing && absint( get_current_user_id() ) === absint( $listing->post_author ) ) {
			echo '<div class="job-manager-error">' . __( 'Invalid request. You cannot claim your own listing.', 'my-listing' ) . '</div>';
			return;
		}

		if ( $listing && isset( $listing->_case27_listing_type ) ) {
			// Post object of listing type entry.
			$listing_type = get_page_by_path( $listing->_case27_listing_type, OBJECT, 'case27_listing_type' );

			// If listing type is set by URL.
			if ( $listing_type ) {

				// Get type of selected listing.
				$type = new \CASE27\Integrations\ListingTypes\ListingType( $listing_type );
			}
		}
		?>
		<section class="i-section c27-packages">
			<div class="container">
				<div class="row section-title reveal reveal_visible">
					<p><?php _e( 'Pricing', 'my-listing' ) ?></p>
					<h2 class="case27-primary-text"><?php _e( 'Choose a Package', 'my-listing' ) ?></h2>
				</div>
				<div class="job_listing_packages">
					<?php get_job_manager_template( 'claim-package-selection.php', array(
						'listing_id'     => $this->listing_id,
						'packages'       => $products,
						'packages_count' => count( $products ),
						'user_packages'  => $user_packages,
						'type'           => $type,
					) ); ?>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Claim Listing View. Display claim info.
	 *
	 * @since 1.0.0
	 */
	public function claim_listing_view() {
		case27_paid_listing_claim_info( isset( $_GET['_claim_id'] ) ? $_GET['_claim_id'] : false, $this->listing_id );
	}

	/**
	 * Claim Listing Handler
	 * This function only loaded when use submit the form.
	 *
	 * @since 3.0.0
	 */
	public function claim_listing_handler() {
		// Claim already created.
		if ( isset( $_GET['_claim_id'] ) && $_GET['_claim_id'] ) {
			return;
		}
	}

	/* === UTILITY === */

	/**
	 * Get Step Title/Name.
	 *
	 * @since 1.0.0
	 */
	public function get_step_title() {
		$step_key = $this->get_step_key( $this->step );
		return isset( $this->steps[ $step_key ]['name'] ) ? $this->steps[ $step_key ]['name'] : '';
	}

	/**
	 * Get Step Submit Text.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_step_submit() {
		$step_key = $this->get_step_key( $this->step );
		return isset( $this->steps[ $step_key ]['submit'] ) ? $this->steps[ $step_key ]['submit'] : '';
	}

	/**
	 * Get Products
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_products() {
		$product_ids = case27_paid_listing_claim_get_products( isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : false );
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
	 * @return array
	 */
	public static function get_user_packages() {
		$packages_ids = case27_paid_listing_claim_get_user_packages( isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : false );
		$packages = array();
		foreach ( $packages_ids as $package_id ) {
			$packages[ $package_id ]= case27_paid_listing_get_package( $package_id );
		}
		return $packages;
	}
}
