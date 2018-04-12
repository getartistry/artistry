<?php
/**
 * Switch Payment Package
 *
 * @since 1.0.0
 *
 * @var string $key   The value is "case27_user_package".
 * @var array  $field Field data.
 */

$package = $field['payment_package'];
$packages = $field['payment_packages'];
$listing = $field['listing'];
if ( ! $packages || ! $listing ) {
	return;
}
?>

<ul class="switch-payment-packages">

	<?php if ( $package ) : // Check, in case package deleted. ?>
		<li>
			<div class="md-checkbox">
				<input type="radio" name="payment-package" value="<?php echo esc_attr( $package->get_id() ) ?>" checked="checked" id="package-<?php echo esc_attr( $package->get_id() ) ?>">
				<label for="package-<?php echo esc_attr( $package->get_id() ) ?>">
					<?php echo esc_html( $package->get_title() ); ?>
					<span><?php esc_html_e( '(Current)', 'my-listing' ); ?></span>
				</label>
			</div>
			<p>
				<?php echo esc_html( sprintf( _n( '%s listing remaining in this package.', '%s listings remaining in this package.', $package->get_remaining_count(), 'my-listing' ), $package->get_limit() ? $package->get_remaining_count() : __( 'Unlimited', 'my-listing' ) ) ); ?>
			</p>
			<p>
				<?php /* @todo:featured */ ?>
				<?php if ( $listing->_job_expires ) : ?>

					<?php /* translators: %1$s is expire date. */ ?>
					<?php printf( __( 'This listing will expire on %1$s.', 'my-listing' ), date_i18n( get_option( 'date_format' ), strtotime( $listing->_job_expires ) ) ); ?>

					<?php /* translators: %1$s is expire date, %2$s is featured info. */ ?>
					<?php //printf( __( 'This listing will expire on %1$s, and is currently %2$s.', 'my-listing' ), date_i18n( get_option( 'date_format' ), strtotime( $listing->_job_expires ) ), $listing->_featured ? __( 'featured', 'my-listing' ) : __( 'not featured', 'my-listing' )  ); ?>

				<?php else : ?>

					<?php _e( 'This listing will never expire.', 'my-listing' ); ?>

					<?php /* translators: %s is featured info */ ?>
					<?php //printf( __( 'This listing will never expire, and is currently %s.', 'my-listing' ), $listing->_featured ? __( 'featured', 'my-listing' ) : __( 'not featured', 'my-listing' )  ); ?>

				<?php endif; ?>
			</p>
		</li>
	<?php endif; ?>

	<?php foreach ( $field['payment_packages'] as $package ): ?>
		<li>
			<div class="md-checkbox">
				<input type="radio" name="payment-package" value="<?php echo esc_attr( $package->get_id() ) ?>" id="package-<?php echo esc_attr( $package->get_id() ) ?>">
				<label for="package-<?php echo esc_attr( $package->get_id() ) ?>">
					<?php echo esc_html( $package->get_title() ); ?>
				</label>
			</div>
			<p>
				<?php echo esc_html( sprintf( _n( '%s listing remaining in this package.', '%s listings remaining in this package.', absint( $package->get_remaining_count() ), 'my-listing' ), $package->get_limit() ? $package->get_remaining_count() : __( 'Unlimited', 'my-listing' ) ) ); ?>
			</p>
			<p>
				<?php /* @todo:featured */ ?>
				<?php if ( $package->get_duration() ) : ?>

					<?php /* translators: %1$s is expire date */ ?>
					<?php printf( __( 'This listing will expire on %1$s.', 'my-listing' ), date( get_option('date_format'), strtotime( "+{$package->get_duration()} days", current_time( 'timestamp' ) ) )  ); ?>

					<?php /* translators: %1$s is expire date, %2$s is featurd info. */ ?>
					<?php //printf( __( 'This listing will expire on %1$s, and will be %2$s.', 'my-listing' ), date( get_option('date_format'), strtotime( "+{$package->get_duration()} days", current_time( 'timestamp' ) ) ), $package->is_featured() ? __( 'featured', 'my-listing' ) : __( 'not featured', 'my-listing' )  ); ?>

				<?php else : ?>

					<?php _e( 'This listing will never expire.', 'my-listing' ); ?>

					<?php /* translators: %s is featured info */ ?>
					<?php //printf( __( 'This listing will never expire, and will be %s.', 'my-listing' ), $package->is_featured() ? __( 'featured', 'my-listing' ) : __( 'not featured', 'my-listing' )  ); ?>

				<?php endif; ?>
			</p>
		</li>
	<?php endforeach; ?>
</ul><!-- .switch-payment-packages -->
