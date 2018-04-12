<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'video_url' => '',
			'wrapper_class' => 'block-element grid-item reveal',
			'ref' => '',
		], $data);

	if ( ! ( $video = c27()->getVideoEmbedUrl( $data['video_url'] ) ) ) return;
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element video-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body video-block-body">
			<iframe src="<?php echo esc_attr( $video['url'] ) ?>" frameborder="0" allowfullscreen height="315"></iframe>
		</div>
	</div>
</div>
