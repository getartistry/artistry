<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'content' => '',
			'wrapper_class' => 'block-element grid-item reveal',
			'ref' => '',
			'escape_html' => true,
			'allow-shortcodes' => false,
			'allow-embeds' => true,
		], $data);

	if ( is_array( $data['content'] ) ) {
		$data['content'] = join( ', ', $data['content'] );
	}

	if ( $data['allow-shortcodes'] ) {
		if ( ! empty( $GLOBALS['wp_embed'] ) ) {
			$data['content'] = $GLOBALS['wp_embed']->autoembed( $data['content'] );
		}

		$data['content'] = do_shortcode( $data['content'] );
	}
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element content-block <?php echo esc_attr( $data['escape_html'] ) ? 'plain-text-content' : 'wp-editor-content' ?>">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<p>
				<?php if ($data['escape_html']): ?>
					<?php echo wp_kses( nl2br( $data['content'] ), ['br' => []] ) ?>
				<?php else: ?>
					<?php echo wpautop( $data['content'] ) ?>
				<?php endif ?>
			</p>
		</div>
	</div>
</div>