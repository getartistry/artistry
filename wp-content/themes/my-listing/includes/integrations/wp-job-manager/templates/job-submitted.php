<div class="container c27-listing-submitted-notice">
	<div class="row">
		<div class="col-md-10 col-md-push-1">

			<div class="element">
				<div class="pf-head">
					<div class="title-style-1">
						<h5>
							<?php
							global $wp_post_types;

							switch ( $job->post_status ) :
								case 'publish' :
									printf( __( '%s listed successfully. To view your listing <a href="%s">click here</a>.', 'my-listing' ), $wp_post_types['job_listing']->labels->singular_name, get_permalink( $job->ID ) );
								break;
								case 'pending' :
									printf( __( '%s submitted successfully. Your listing will be visible once approved.', 'my-listing' ), $wp_post_types['job_listing']->labels->singular_name, get_permalink( $job->ID ) );
								break;
								default :
									do_action( 'job_manager_job_submitted_content_' . str_replace( '-', '_', sanitize_title( $job->post_status ) ), $job );
								break;
							endswitch;
							?>
						</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
do_action( 'job_manager_job_submitted_content_after', sanitize_title( $job->post_status ), $job );
