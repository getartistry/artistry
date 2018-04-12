<?php
	$data = c27()->merge_options([
			'terms' => [],
			'template' => 'template_1',
            'overlay_type' => 'gradient',
            'overlay_gradient' => 'gradient1',
            'overlay_solid_color' => 'rgba(0, 0, 0, .5)',
            'columns' => ['lg' => 3, 'md' => 3, 'sm' => 2, 'xs' => 1],
            'container' => 'container-fluid',
		], $data);

	if ( ! class_exists( 'WP_Job_Manager' ) ) {
		return false;
	}

	$term_ids = array_column((array) $data['terms'], 'category_id');

	$terms = (array) get_terms([
		'taxonomy' => $data['taxonomy'],
		'hide_empty' => false,
		'include' => array_filter( $term_ids ) ? : [-1],
		'orderby' => 'include',
		]);

	if ( is_wp_error( $terms ) ) {
		return false;
	}

	$itemSize = sprintf( 'col-lg-%1$d col-md-%2$d col-sm-%3$d col-xs-%4$d reveal',
						  12 / absint( $data['columns']['lg'] ), 12 / absint( $data['columns']['md'] ),
						  12 / absint( $data['columns']['sm'] ), 12 / absint( $data['columns']['xs'] ) );
?>

<?php if ( ! $data['template'] || $data['template'] == 'template_1' ): ?>

	<section class="i-section discover-places">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<div class="row section-body">

				<?php foreach ($terms as $term):
					$term = new CASE27\Classes\Term( $term );
					$image = $term->get_image();
					?>

					<div class="<?php echo esc_attr( $itemSize ) ?> reveal">
						<div class="listing-cat" >
							<a href="<?php echo esc_url( $term->get_link() ) ?>">
								<div class="overlay <?php echo $data['overlay_type'] == 'gradient' ? esc_attr( $data['overlay_gradient'] ) : '' ?>"
                         			 style="<?php echo $data['overlay_type'] == 'solid_color' ? 'background-color: ' . esc_attr( $data['overlay_solid_color'] ) . '; ' : '' ?>"></div>
								<div class="lc-background" style="<?php echo $image && is_array($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
								</div>
								<div class="lc-info">
									<h4 class="case27-secondary-text"><?php echo esc_html( $term->get_name() ) ?></h4>
									<h6><?php echo esc_html( $term->get_count() ) ?></h6>
								</div>
								<div class="lc-icon">
									<?php echo $term->get_icon([ 'background' => false, 'color' => false ]); ?>
								</div>
							</a>
						</div>
					</div>

				<?php endforeach ?>

			</div>
		</div>
	</section>

<?php endif ?>

<?php if ($data['template'] == 'template_2'): ?>

	<section class="i-section all-categories">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<div class="row section-body">

				<?php foreach ($terms as $term):
					$term = new CASE27\Classes\Term( $term );
					?>

					<div class="<?php echo esc_attr( $itemSize ) ?> ac-category reveal">
						<div class="cat-card" >
							<a href="<?php echo esc_url( $term->get_link() ) ?>">
								<div class="ac-front-side face">
									<div class="hovering-c">
										<span class="cat-icon" style="background-color: <?php echo esc_attr( $term->get_color() ) ?>;">
											<?php echo $term->get_icon([ 'background' => false ]); ?>
										</span>
										<span class="category-name"><?php echo esc_html( $term->get_name() ) ?></span>
									</div>
								</div>
								<div class="ac-back-side face" style="background-color: <?php echo esc_attr( $term->get_color() ) ?>;">
									<div class="hovering-c">
										<p style="color: <?php echo esc_attr( $term->get_text_color() ) ?>;">
											<?php echo esc_html( $term->get_count() ) ?>
										</p>
									</div>
								</div>
							</a>
						</div>
					</div>

				<?php endforeach ?>

			</div>
		</div>
	</section>

<?php endif ?>

<?php if ($data['template'] == 'template_3'): ?>

	<section class="i-section">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<div class="row">

				<?php foreach ($terms as $term):
					$term = new CASE27\Classes\Term( $term );
					$image = $term->get_image();
					?>

					<div class="<?php echo esc_attr( $itemSize ) ?> car-item reveal">
						<a href="<?php echo esc_url( $term->get_link() ) ?>">
							<div class="car-item-container">
								<div class="car-item-img" style="<?php echo $image && is_array($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
								</div>
								<div class="car-item-details">
									<h3><?php echo esc_html( $term->get_name() ) ?></h3>
									<p><?php echo esc_html( $term->get_count() ) ?></p>
								</div>
							</div>
						</a>
					</div>

				<?php endforeach ?>

			</div>
		</div>
	</section>

<?php endif ?>

<?php if ($data['template'] == 'template_4'): ?>

	<section class="i-section">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<div class="regions-featured row">

				<?php foreach ($terms as $term):
					$term = new CASE27\Classes\Term( $term );
					$image = $term->get_image();
					?>

					<div class="<?php echo esc_attr( $itemSize ) ?> one-region reveal">
						<a href="<?php echo esc_url( $term->get_link() ) ?>">
							<div class="region-details">
								<h1 class="case27-secondary-text"><?php echo esc_html( $term->get_name() ) ?></h1>
								<h3><?php echo esc_html( $term->get_count() ) ?></h3>
							</div>
							<div class="region-image-holder">
								<div class="region-image" style="<?php echo $image && is_array($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
									<div class="overlay"></div>
								</div>
							</div>
						</a>
					</div>

				<?php endforeach ?>

			</div>
		</div>
	</section>

<?php endif ?>