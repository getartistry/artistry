<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'rows' => [],
			'wrapper_class' => 'block-element',
		], $data);
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element table-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<ul class="extra-details">
				<?php foreach ((array) $data['rows'] as $row):
					if ( is_array( $row['content'] ) ) {
						$row['content'] = join( ', ', $row['content'] );
					}

					if ( ! trim( $row['content'] ) ) {
						continue;
					}
					?>
					<li>
						<div class="item-attr"><?php echo $row['title'] ?></div>
						<div class="item-property"><?php echo $row['content'] ?></div>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
