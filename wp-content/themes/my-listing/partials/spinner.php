<?php
	$data = c27()->merge_options([
			'color' => '#222',
			'classes' => '',
			'size' => 28,
			'width' => 3,
		], $data);
?>

<div class="paper-spinner <?php echo esc_attr( $data['classes'] ) ?>" style="<?php printf( 'width: %dpx; height: %dpx;', $data['size'], $data['size'] ) ?>">
	<div class="spinner-container active">
		<div class="spinner-layer layer-1" style="border-color: <?php echo esc_attr( $data['color'] ) ?>;">
			<div class="circle-clipper left">
				<div class="circle" style="border-width: <?php echo esc_attr( $data['width'] ) ?>px;"></div>
			</div><div class="gap-patch">
				<div class="circle" style="border-width: <?php echo esc_attr( $data['width'] ) ?>px;"></div>
			</div><div class="circle-clipper right">
				<div class="circle" style="border-width: <?php echo esc_attr( $data['width'] ) ?>px;"></div>
			</div>
		</div>
	</div>
</div>