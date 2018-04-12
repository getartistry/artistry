<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'wrapper_class' => 'grid-item reveal',
			'ref' => '',
			'terms' => [],
			'categories_item_interface' => 'WP_CATEGORY_OBJECT',
		], $data);

	if (
		! is_array( $data['terms'] ) ||
		! array_filter( $data['terms'] ) ||
		is_wp_error( $data['terms'] ) )
	{
		return false;
	}
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element categories-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<div class="listing-details">
				<ul>

					<?php foreach ((array) $data['terms'] as $term):
						$term = new CASE27\Classes\Term( $term );
						?>

						<li>
							<a href="<?php echo esc_url( $term->get_link() ) ?>">
								<span class="cat-icon" style="background-color: <?php echo esc_attr( $term->get_color() ) ?>;">
                                    <?php echo $term->get_icon([ 'background' => false ]) ?>
								</span>
								<span class="category-name"><?php echo esc_html( $term->get_name() ) ?></span>
							</a>
						</li>

					<?php endforeach ?>

				</ul>
			</div>
		</div>
	</div>
</div>
