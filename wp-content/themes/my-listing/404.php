<?php get_header() ?>

<section class="i-section">
	<div class="container">
		<div class="row text-center">
            <div class="no-results-wrapper">
                <i class="no-results-icon material-icons">mood_bad</i>
                <li class="no_job_listings_found"><?php _e( 'Error 404: The page your are looking for cannot be found.', 'my-listing' ) ?></li>
                <a href="<?php echo esc_url( home_url('/') ) ?>" class="buttons button-2"><?php _e( 'Back to homepage', 'my-listing' ) ?></a>
            </div>
		</div>
	</div>
</section>

<?php get_footer() ?>