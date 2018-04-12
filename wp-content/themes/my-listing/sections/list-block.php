<?php
$data = c27()->merge_options([
		'icon' => '',
		'icon_style' => 1,
		'title' => '',
		'items' => [],
		'item_interface' => 'ELEMENTOR_LINK_ARRAY',
        'ref' => '',
		'wrapper_class' => 'block-element grid-item reveal',
	], $data);

$items = $data['items'];

if ($data['item_interface'] == 'WP_TERM') {
	$items = [];

	foreach ($data['items'] as $item) {
		$term = new CASE27\Classes\Term( $item );

		$items[] = [
			'_id' => uniqid() . '__list_item',
			'title' => $term->get_name(),
			'icon_markup' => $term->get_icon([ 'background' => false, 'color' => false ]),
			'type' => 'link',
			'link_hover_color' => $term->get_color(),
			'text_hover_color' => $term->get_text_color(),
			'escape' => true,
			'link' => [
				'url' => $term->get_link(),
				'is_external' => false,
			],
		];
	}
}

if ($data['item_interface'] == 'CASE27_DETAILS_ARRAY') {
	$items = [];

	foreach ($data['items'] as $item) {
		if ( is_array( $item['content'] ) ) {
			$item['content'] = join( ', ', $item['content'] );
		}

		$items[] = [
			'_id' => uniqid() . '__list_item',
			'title' => $item['content'],
			'icon' => $item['icon'],
			'type' => 'plain_text',
			'escape' => false,
		];
	}
}

if ($data['item_interface'] == 'CASE27_LINK_ARRAY') {
	$items = [];

	foreach ( $data['items'] as $item ) {
		if ( empty( $item['name'] ) || empty( $item['link'] ) ) {
			continue;
		}

		if ( empty( $item['icon'] ) ) {
			$item['icon'] = 'fa fa-link';
		}

		if ( empty( $item['color'] ) ) {
			$item['color'] = '#70ada5';
		}

		$items[] = [
			'_id' => uniqid() . '__list_item',
			'title' => $item['name'],
			'icon' => $item['icon'],
			'type' => 'link',
			'escape' => true,
			'link_hover_color' => $item['color'],
			'link' => [
				'url' => $item['link'],
				'is_external' => true,
			],
		];
	}
}

if ( ! $items ) {
	return false;
}

?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element list-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<ul class="details-list social-nav">
				<?php foreach ((array) $items as $item): ?>
					<li class="<?php echo esc_attr( "item_{$item['_id']}" ) ?>">
						<?php if ($item['type'] == 'link'):
							$url = $item['link']['url'];
							$target = $item['link']['is_external'] ? 'target="_blank"' : '';

							if (!isset($GLOBALS['case27_custom_styles'])) $GLOBALS['case27_custom_styles'] = '';

							$GLOBALS['case27_custom_styles'] .= '.details-list .item_' . $item['_id'] . ' a:hover i {';
							$GLOBALS['case27_custom_styles'] .= 'background-color: ' . $item['link_hover_color'] . ' !important;';
							$GLOBALS['case27_custom_styles'] .= 'border-color: ' . $item['link_hover_color'] . ' !important;';

							if ( ! empty( $item['text_hover_color'] ) ) {
								$GLOBALS['case27_custom_styles'] .= 'color: ' . $item['text_hover_color'] . ';';
							}

							$GLOBALS['case27_custom_styles'] .= '}';
							?>

							<a href="<?php echo esc_url( $url ) ?>" <?php echo esc_attr( $target ) ?>>
						<?php endif ?>

						<?php if ( ! empty( $item['icon_markup'] ) ): ?>
							<?php echo $item['icon_markup'] ?>
						<?php elseif ( $item['icon'] ): ?>
							<?php echo c27()->get_icon_markup($item['icon']) ?>
						<?php endif ?>

						<?php if ( $item['escape'] ): ?>
							<span><?php echo esc_html( $item['title'] ) ?></span>
						<?php else: ?>
							<span><?php echo $item['title'] ?></span>
						<?php endif ?>


						<?php if ($item['type'] == 'link'): ?>
							</a>
						<?php endif ?>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
