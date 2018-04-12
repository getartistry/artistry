<?php if ( $packages || $user_packages ) :
	$checked = 1;
	?>

	<?php if ( $packages ) : $i = 0; $package_count = count( $packages ); ?>
		<div class="row section-body">

			<?php if ( $package_count == 1 ): ?>
				<div class="col-md-4 col-sm-3 hidden-xs"></div>
			<?php elseif ( $package_count == 2 ): ?>
				<div class="col-md-2 hidden-sm hidden-xs"></div>
			<?php endif ?>

			<?php foreach ( $packages as $key => $package ) : $i++;
				$product = wc_get_product( $package );
				if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				}

				$title = $product->get_title();
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

				$selected = isset( $_GET['selected_package'] ) ? absint($_GET['selected_package']) : null;
				$checked = $selected == $product->get_id();
				?>

				<div class="col-md-4 col-sm-6 col-xs-12 reveal">
					<div class="pricing-item c27-pick-package <?php echo $checked ? 'c27-picked' : '' ?> <?php echo $featured ? 'featured' : '' ?>">
						<h2 class="plan-name"><?php echo $title ?></h2>
						<h2 class="plan-price case27-primary-text"><?php echo $product->get_price_html() ?></h2>
						<p class="plan-desc"><?php echo $product->get_short_description() ?></p>
						<div class="plan-features">
							<?php if ( is_array( $description ) ): ?>
								<ul>
									<?php foreach ($description as $line): ?>
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
						<input type="radio" <?php checked( $checked, 1 ); $checked = 0; ?> name="job_package" class="c27-job-package-radio-button" value="<?php echo esc_attr( $product->get_id() ); ?>" id="package-<?php echo esc_attr( $product->get_id() ); ?>" />
					</div>
				</div>

				<?php if ( $i % 3 == 0 ): ?>
					</div><div class="row">
				<?php endif ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $user_packages ) : ?>
		<div class="element user-packages">
			<div class="pf-head round-icon">
				<div class="title-style-1">
					<i class="material-icons">shopping_basket</i><h5><?php _e( 'Your Packages', 'my-listing' ) ?> <span class="toggle-my-packages"><i class="mi keyboard_arrow_down"></i></span></h5>
				</div>
			</div>
			<div class="pf-body">
				<ul class="job_packages">
					<?php foreach ( $user_packages as $key => $package ) :
						$package = wc_paid_listings_get_package( $package );
						?>
						<li class="user-job-package">
							<div class="md-checkbox">
								<input type="radio" <?php checked( $checked, 1 ); ?> name="job_package" value="user-<?php echo esc_attr( $key ); ?>" id="user-package-<?php echo esc_attr( $package->get_id() ); ?>" />
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

<?php else : ?>

	<p><?php _e( 'No packages found.', 'my-listing' ); ?></p>

<?php endif; ?>

<style type="text/css">
    .elementor-widget:not(.elementor-widget-case27-add-listing-widget) { display: none !important; }
    .elementor-container { max-width: 100% !important; }
    .elementor-column-wrap { padding: 0 !important; }
</style>