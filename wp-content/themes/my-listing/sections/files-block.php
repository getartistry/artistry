<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'wrapper_class' => 'block-element',
			'ref' => '',
            'is_edit_mode' => false,
            'items' => [],
		], $data);

	if ( ! is_array( $data['items'] ) || count( array_filter( $data['items'] ) ) < 1 ) {
		return false;
	}

	$icons = [
		'pdf'  => 'fa fa-file-pdf-o',
		'jpg'  => 'fa fa-file-image-o',
		'jpeg' => 'fa fa-file-image-o',
		'png'  => 'fa fa-file-image-o',
		'gif'  => 'fa fa-file-image-o',
		'flv'  => 'fa fa-file-video-o',
		'mp4'  => 'fa fa-file-video-o',
		'm3u8' => 'fa fa-file-video-o',
		'ts'   => 'fa fa-file-video-o',
		'3gp'  => 'fa fa-file-video-o',
		'mov'  => 'fa fa-file-video-o',
		'avi'  => 'fa fa-file-video-o',
		'wmv'  => 'fa fa-file-video-o',
	];
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element files-block <?php echo 'files-count-' . count( $data['items'] ) ?>">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>

		<div class="pf-body">

			<ul class="file-list">

				<?php foreach ( $data['items'] as $file ):
					if ( ! ( $basename = pathinfo( $file, PATHINFO_BASENAME ) ) || ! ( $extension = pathinfo( $file, PATHINFO_EXTENSION ) ) ) {
						continue;
					}
					?>

					<a href="<?php echo esc_url( $file ) ?>" target="_blank">
						<li class="file">
							<span class="file-icon"><i class="<?php echo esc_attr( ! empty( $icons[ $extension ] ) ? $icons[ $extension ] : 'fa fa-file-o' ) ?>"></i></span>
							<span class="file-name"><?php echo esc_html( $basename ) ?></span>
							<span class="file-link"><?php _e( 'View', 'my-listing' ) ?><i class="mi open_in_new"></i></span>
						</li>
					</a>

				<?php endforeach ?>

			</ul>

		</div>
	</div>
</div>
