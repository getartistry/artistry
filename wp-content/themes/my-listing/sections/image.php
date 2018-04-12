<?php
	$data = c27()->merge_options([
			'image' => '',
			'style' => '1',
		], $data);
?>

<?php if ($data['image'] && isset($data['image']['url'])): ?>
	<img src="<?php echo esc_url( $data['image']['url'] ) ?>" class="img-style-<?php echo esc_attr( $data['style'] ) ?>">
<?php endif ?>