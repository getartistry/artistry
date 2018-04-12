<?php
	$data = c27()->merge_options([
			'listing_types' => [],
			'title' => '',
            'is_edit_mode' => false,
            'size' => 'medium',
		], $data);

	$listing_type = false;

	if (is_array( $data['listing_types'] ) && count( $data['listing_types'] ) === 1) {
		$listing_type = $data['listing_types'][0]['listing_type'];
		$_GET['listing_type'] = $listing_type;
	}

	if (isset( $_GET['listing_type'] ) && $_GET['listing_type'] && in_array( $_GET['listing_type'], array_column( $data['listing_types'], 'listing_type' ) ) ) {
		// $_COOKIE['wp-job-manager-submitting-job-id'] = false;
		$listing_type = sanitize_text_field( $_GET['listing_type'] );
		$_GET['listing_type'] = $listing_type;
	}

	if ( isset( $_GET['job_id'] ) && $_GET['job_id'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'c27_relist_product' ) ) {
		$listing_type = get_post_meta( absint( $_GET['job_id'] ), '_case27_listing_type', true );
		$_GET['listing_type'] = $listing_type;
	}

	$cardClasses = [
		'small' => 'col-md-3',
		'medium' => 'col-md-4',
		'large' => 'col-md-6',
	];

	$cardClass = in_array( $data['size'], array_keys( $cardClasses ) ) ? $cardClasses[$data['size']] : $cardClasses['medium'];
?>

<?php if ($listing_type): ?>
	<?php add_filter('case27_job_submit_wrap_in_block', '__return_true') ?>
	<?php add_filter('case27_job_submit_title', function() use ($data) { return $data['title']; }) ?>
	<?php do_action('case27_add_listing_form_template_start', $listing_type) ?>

	<?php echo do_shortcode('[submit_job_form]') ?>

	<?php if ($data['is_edit_mode']): ?>
	    <script type="text/javascript">case27_ready_script(jQuery);</script>
	<?php endif ?>
<?php else: ?>
	<section class="i-section">
		<div class="container-fluid">
			<div class="row section-body">
			<?php foreach ($data['listing_types'] as $listing_type): $ltype = $listing_type['listing_type']; ?>

				<?php
					$options = c27()->get_listing_type_options($ltype, ['settings'])['settings'];
					$submission_args = [ 'listing_type' => $ltype, 'new' => true ];

					if ( isset( $_GET['selected_package'] ) && ! empty( $_GET['selected_package'] ) ) {
						$submission_args['selected_package'] = absint( $_GET['selected_package'] );
					}
				?>
				<div class="<?php echo esc_attr( $cardClass ) ?> col-sm-6 col-xs-12 ac-category reveal">
					<div class="cat-card">
						<a href="<?php echo esc_url( add_query_arg( $submission_args, get_permalink() ) ) ?>">
							<div class="ac-front-side face">
								<div class="hovering-c">
									<span class="cat-icon" style="color: #fff; background-color: <?php echo esc_attr( $listing_type['color'] ) ?>">
										<i class="<?php echo esc_attr( $options['icon'] ) ?>"></i>
									</span>
									<span class="category-name"><?php echo esc_attr( $options['singular_name'] ) ?></span>
								</div>
							</div>
							<div class="ac-back-side face" style="background-color: <?php echo esc_attr( $listing_type['color'] ) ?>">
								<div class="hovering-c">
									<p><?php _e( 'Choose type', 'my-listing' ) ?></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			<?php endforeach ?>
			</div>
		</div>
	</section>
<?php endif ?>

