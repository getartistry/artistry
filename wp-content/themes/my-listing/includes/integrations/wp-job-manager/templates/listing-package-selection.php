<?php
/**
 * Package Selection Step.
 *
 * @since 1.0.0
 *
 * @var array $packages       Product list. Each item is CASE27\Integrations\Paid_Listings\Product object.
 * @var int   $packages_count Packages `$packages` count.
 * @var array $user_packages  User package list. Each item is CASE27\Integrations\Paid_Listings\Package object.
 * @var mixed $type           Listing Type object. false|CASE27\Integrations\ListingTypes\ListingType.
 */
?>

<?php if ( $packages || $user_packages ) : ?>

	<?php
	$checked = 1;
	$selected = isset( $_GET['selected_package'] ) ? absint( $_GET['selected_package'] ) : null;
	?>

	<?php if ( $packages && is_array( $packages ) ) : ?>

		<div class="row section-body">

			<?php if ( 1 === $packages_count ): ?>
				<div class="col-md-4 col-sm-3 hidden-xs"></div>
			<?php elseif ( 2 === $packages_count ): ?>
				<div class="col-md-2 hidden-sm hidden-xs"></div>
			<?php endif; ?>

			<?php foreach ( $packages as $key => $product ) : ?>

				<?php
				// Skip if not the right product type or not purchaseable.
				if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				}

				$title = $product->get_name();
				$description = $product->get_description();
				$featured = false;

				// If a custom title, description, or other options are set on this product
				// for this specific listing type, then replace the default ones with the custom one.
				if ( $type && ( $_package = $type->get_package( $product->get_id() ) ) ) {
					$title = $_package['label'] ?: $title;
					$featured = $_package['featured'] ?: $featured;

					// Split the description textarea into new lines,
					// so it can later be reconstructed to an html list.
					$description = $_package['description'] ? preg_split( '/\r\n|[\r\n]/', $_package['description'] ) : $description;
				}


				// Set checked item.
				$checked = ( intval( $selected ) === intval( $product->get_id() ) ) ? 1 : 0;
				?>

				<div class="col-md-4 col-sm-6 col-xs-12 reveal">
					<div class="pricing-item c27-pick-package <?php echo $checked ? 'c27-picked' : ''; ?> <?php echo $featured ? 'featured' : '' ?>">
						<h2 class="plan-name"><?php echo $title ?></h2>
						<h2 class="plan-price case27-primary-text"><?php echo $product->get_price_html(); ?></h2>
						<p class="plan-desc"><?php echo $product->get_short_description(); ?></p>
						<div class="plan-features">
							<?php if ( is_array( $description ) ): ?>
								<ul>
									<?php foreach ( $description as $line ): ?>
										<li><?php echo $line ?></li>
									<?php endforeach ?>
								</ul>
							<?php else: ?>
								<?php echo $description ?>
							<?php endif ?>
						</div>
						<a class="select-plan buttons button-1" href="#">
							<i class="material-icons sm-icon">send</i><?php _e( 'Select Plan', 'my-listing' ); ?>
						</a>
						<input <?php checked( $checked, 1 ); $checked = 0; ?> type="radio" name="listing_package" class="c27-job-package-radio-button" value="<?php echo esc_attr( $product->get_id() ); ?>" id="package-<?php echo esc_attr( $product->get_id() ); ?>" />
					</div><!-- .pricing-item -->
				</div><!-- .reveal -->

			<?php endforeach; ?>

		</div><!-- .section-body -->

	<?php endif; ?>

	<?php if ( $user_packages && is_array( $user_packages ) ) : ?>

		<div class="element user-packages">
			<div class="pf-head round-icon">
				<div class="title-style-1">
					<i class="material-icons">shopping_basket</i><h5><?php _e( 'Your Packages', 'my-listing' ) ?> <span class="toggle-my-packages"><i class="mi keyboard_arrow_down"></i></span></h5>
				</div>
			</div>
			<div class="pf-body">
				<ul class="job_packages">
					<?php foreach ( $user_packages as $key => $package ) : ?>
						<li class="user-job-package">
							<div class="md-checkbox">
								<input type="radio" <?php checked( $checked, 1 ); ?> name="listing_package" value="<?php echo esc_attr( $key ); ?>" id="user-package-<?php echo esc_attr( $package->get_id() ); ?>" />
								<label for="user-package-<?php echo esc_attr( $package->get_id() ); ?>"></label>
							</div>
							<label for="user-package-<?php echo esc_attr( $package->get_id() ); ?>"><?php echo esc_attr( $package->get_title() ); ?></label><br/>
							<?php
							if ( $package->get_limit() ) {
								printf( _n( '%s listing posted out of %d', '%s listings posted out of %d', $package->get_count(), 'my-listing' ), $package->get_count(), $package->get_limit() );
							} else {
								printf( _n( '%s listing posted', '%s listings posted', $package->get_count(), 'my-listing' ), $package->get_count() );
							}

							if ( $package->get_duration() ) {
								printf(  ', ' . _n( 'listed for %s day', 'listed for %s days', $package->get_duration(), 'my-listing' ), $package->get_duration() );
							}

							$checked = 0;
							?>
						</li>
					<?php endforeach; ?>
				</ul>

				<button type="submit" name="continue" class="button buttons button-1 listing-details-button" value="">
					<?php echo apply_filters( 'submit_job_step_choose_package_submit_text', __( 'Submit', 'my-listing' ) ); ?>
				</button>
			</div>
		</div>
	<?php endif; ?>

<?php endif; ?>

<style type="text/css">
	.elementor-widget:not(.elementor-widget-case27-add-listing-widget) { display: none !important; }
	.elementor-container { max-width: 100% !important; }
	.elementor-column-wrap { padding: 0 !important; }
</style>
