<?php

namespace CASE27\Integrations\Claims;

class WPJMClaimListingProvider implements ProviderInterface {

	public function activate() {
		if ( ! defined( '\\WPJMCL_VERSION' ) ) {
			return false;
		}

		if ( ! class_exists( '\\wpjmcl\\wpjm_wc_paid_listing\\Form_Setup' ) ) {
			return false;
		}

		add_filter( 'case27\types\cover_buttons', [ $this, 'add_cover_button_option' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_filter( 'wpjmcl_submit_claim_form_steps', [ $this, 'add_form_step' ] );
		add_action( 'case27\listing\cover\buttons\claim-listing', [ $this, 'display_cover_button' ], 30, 2 );
		add_filter( 'wcpl_get_job_packages_args', [ $this, 'exclude_claim_package' ], 30 );
	}

	public function exclude_claim_package( $args ) {
		$replace_claims_query = false;
		if ( ! empty( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
			foreach ( $args['meta_query'] as $meta_query_key => $meta_query ) {
				if ( ! empty( $meta_query['key'] ) && $meta_query['key'] == '_use_for_claims' ) {
					unset( $args['meta_query'][$meta_query_key] );
					$replace_claims_query = true;
				}
			}
		}

		if ( $replace_claims_query ) {
			$args['meta_query'][] = [
				'relation' => 'OR',
				[
					'key'     => '_use_for_claims',
					'value'   => 'yes',
					'compare' => '!=',
				],
				[
					'key'     => '_use_for_claims',
					'compare' => 'NOT EXISTS',
				]
			];
		}

		return $args;
	}

	public function add_cover_button_option( $buttons ) {
		$buttons['claim-listing'] = [
			'action' => 'claim-listing',
			'label' => 'Claim Listing',
		];

		return $buttons;
	}

	public function body_classes( $classes ) {
        if ( is_singular( 'job_listing' ) ) {
            global $post;

            if ( 1 == get_post_meta( $post->ID, '_claimed', true ) ) {
            	$classes[] = 'c27-verified';
            }
        }

        return $classes;
	}

	public function display_cover_button( $button, $listing ) {
		if ( ! ( $claim_url = \wpjmcl\job_listing\Functions::submit_claim_url( $listing->get_id() ) ) ) {
			return false;
		} ?>
       <li>
           <a class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium" href="<?php echo esc_attr( $claim_url ) ?>">
               <?php echo do_shortcode($button['label']) ?>
           </a>
       </li>
	<?php }

	public function add_form_step( $steps ) {
		/* Register on checkout */
		if ( ! empty( $steps['login_register'] ) ) {
			$steps['login_register']['view'] = [ $this, 'login_register_view' ];
		}

		if ( get_option( 'wpjmcl_register_on_checkout' ) ) {
			unset( $steps['login_register'] );
		}

		/* Change button on claim package */
		$steps['claim_listing']['submit'] = __( 'Choose a package &rarr;', 'wp-job-manager-claim-listing' );

		/* Add select package step. */
		$steps['claim_package'] = [
			'name'     => __( 'Choose a package', 'wp-job-manager-claim-listing' ),
			'view'     => [ $this, 'claim_package_view' ],
			'handler'  => [ \wpjmcl\wpjm_wc_paid_listing\Form_Setup::get_instance(), 'claim_package_handler' ],
			'priority' => 4,
			'submit'   => __( 'Checkout &rarr;', 'wp-job-manager-claim-listing' ),
		];
		return $steps;
	}

	public function login_register_view() {
		do_action( 'case27_wpjmcl_login_register_view_before' );

		echo \wpjmcl\submit_claim\Submit_Claim_Form::instance()->login_register_view();

		do_action( 'case27_wpjmcl_login_register_view_after' );
	}

	public function get_packages( $packages = [] ) {
		add_filter( 'wpjmcl_get_packages_for_claiming', [ $this, 'filter_wpjmcl_get_packages_for_claiming' ] );

		$packages = \wpjmcl\wpjm_wc_paid_listing\Form_Setup::get_packages_for_claiming( $packages );

		remove_filter( 'wpjmcl_get_packages_for_claiming', [ $this, 'filter_wpjmcl_get_packages_for_claiming' ] );

		return $packages;
	}

	public static function filter_wpjmcl_get_packages_for_claiming( $args ) {
			$args['orderby'] = 'post__in';

			return $args;
	}

	public function claim_package_view() {
		$form = \wpjmcl\submit_claim\Submit_Claim_Form::instance();
		$package_ids = [];

		if ( ! $form->listing_obj || ! ( $listing = new \CASE27\Classes\Listing( $form->listing_obj ) ) ) {
			return false;
		}

		if ( $listing->type ) {
			$package_ids = array_column( $listing->type->get_packages(), 'package' );
		}

		$packages = $this->get_packages( $package_ids );
		?>
		<section class="c27-wpjmcl-packages">
			<div class="container">
				<div class="row section-body reveal">
					<div class="col-md-6 col-md-offset-3">
						<div class="element">
							<div class="pf-head round-icon">
								<div class="title-style-1">
									<?php echo c27()->get_icon_markup( 'material-icons://view_list' ) ?>
									<h5><?php _e( 'Choose a package', 'my-listing' ); ?></h5>
								</div>
							</div>
							<div class="pf-body">

								<form id="<?php echo esc_attr( $form->get_form_name() ); ?>" class="job-manager-form wpjmcl_form wpjmcl_form_claim_package" method="post">

									<div class="job_listing_packages">

										<?php if ( $packages ): $checked = 1; ?>

											<ul class="job_packages">

												<li class="package-section"><?php _e( 'Purchase Package:', 'wp-job-manager-claim-listing' ); ?></li>

												<?php foreach ( $packages as $key => $package ):
													$product = wc_get_product( $package );
													/* Skip if not purchase-able. */
													if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
														continue;
													}
													?>

													<li class="job-package">

														<input type="radio" <?php checked( $checked, 1 );
														$checked = 0; ?> name="job_package" value="<?php echo $product->get_id(); ?>" id="package-<?php echo $product->get_id(); ?>" />

														<label for="package-<?php echo $product->get_id(); ?>"><?php echo $product->get_title(); ?></label><br/>

														<?php echo ( ! empty( $product->get_short_description() ) ) ? apply_filters( 'woocommerce_short_description', $product->get_short_description() ) : '' ?>

														<?php echo $product->get_price_html() . ' '; ?>
														<?php echo $product->get_duration() ? sprintf( _n( '(Listed for %s day)', '(Listed for %s days)', $product->get_duration(), 'wp-job-manager-claim-listing' ), $product->get_duration() ) : ''; ?>

													</li>

												<?php endforeach ?>

											</ul><!-- .job_packages-->

										<?php else: // package not available  ?>

											<p><?php _e( 'No packages found', 'wp-job-manager-claim-listing' ); ?></p>

										<?php endif ?>

									</div><!-- .job_listing_packages -->

									<div class="job_listing_packages_title">

										<?php if ( $packages ) { ?>
											<input type="submit" value="<?php echo esc_attr( $form->get_step_submit() ); ?>" class="button" name="submit">
											<input type="hidden" name="claim_id" value="<?php echo esc_attr( $form->claim_id ); ?>" />
											<input type="hidden" name="step" value="<?php echo intval( $form->get_step() ); ?>">
										<?php } ?>

									</div><!-- .job_listing_packages_title -->

								</form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

return new WPJMClaimListingProvider;
