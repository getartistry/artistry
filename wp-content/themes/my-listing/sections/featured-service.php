<?php
	$data = c27()->merge_options([
			'image' => '',
			'content' => '',
			'position' => 'left',
		], $data);
?>

<section class="i-section">
	<div class="container-fluid">
		<div class="row section-body">
			<div class="col-md-12 image-service <?php echo esc_attr( "is-{$data['position']}-text" ) ?>">
				<div class="is-image reveal" style="background-image: url('<?php echo esc_attr( $data['image']['url'] ) ?>')">
				</div>
				<div class="is-desc reveal">
					<?php echo do_shortcode( apply_filters( 'case27_featured_service_content', $data['content'] ) ) ?>
				</div>
			</div>
		</div>
	</div>
</section>