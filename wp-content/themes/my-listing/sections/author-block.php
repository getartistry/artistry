<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'wrapper_class' => 'grid-item reveal',
			'author' => '',
			'ref' => '',
		], $data);

	$author = $data['author'];

	if ( ! $author instanceof \MyListing\User || ! $author->exists() ) {
		return false;
	}
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element related-listing-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<div class="event-host">
				<a href="<?php echo esc_url( $author->get_link() ) ?>">
					<div class="avatar">
						<img src="<?php echo esc_url( $author->get_avatar() ) ?>">
					</div>
					<span class="host-name"><?php echo esc_html( $author->get_name() ) ?></span>
				</a>
			</div>
		</div>
	</div>
</div>