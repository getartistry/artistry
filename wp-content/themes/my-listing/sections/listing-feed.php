<?php
	$data = c27()->merge_options([
			'template' => 'grid',
			'posts_per_page' => 6,
			'category' => '',
			'include' => '',
			'listing_types' => '',
            'is_edit_mode' => false,
            'columns' => ['lg' => 3, 'md' => 3, 'sm' => 2, 'xs' => 1],
            'order_by' => 'date',
            'order' => 'DESC',
            'behavior' => 'default',
            'show_promoted_badge' => 'yes',
		], $data);

	// Basic args for get_posts().
	$args = [
		'post_type' => 'job_listing',
		'post_status' => 'publish',
		'posts_per_page' => $data['posts_per_page'],
		'ignore_sticky_posts' => false,
		'meta_query' => [],
	];

	// Filter by 'job_listing_category' taxonomy.
	if ($data['category']) {
		$args['tax_query'] = [[
			'taxonomy' => 'job_listing_category',
			'terms' => $data['category'],
			'field' => 'term_id',
		]];
	}

	// Only display the selected listings.
	if ($data['include']) {
		$args['post__in'] = $data['include'];
	}

	// dump($data['include']);

	// Filter by the listing type.
	if ($data['listing_types']) {
		$args['meta_query']['c27_listing_type_clause'] = [
			'key' => '_case27_listing_type',
			'value' => $data['listing_types'],
			'compare' => 'IN',
		];
	}

	if ($data['order_by']) {
		if ($data['order_by'][0] === '_') {
			// Order by meta key.
			$args['meta_query']['c27_orderby_clause'] = [
				'key' => $data['order_by'],
				'compare' => 'EXISTS',
				'type' => 'DECIMAL(10, 2)',
			];

			$args['orderby'] = 'c27_orderby_clause';
		} else {
			$args['orderby'] = $data['order_by'];
		}
	}

	if ( ! in_array( $data['order'], ['ASC', 'DESC'] ) ) {
		$data['order'] = 'DESC';
	}

	$args['order'] = $data['order'];

	if ($data['behavior'] == 'show_promoted_first') {
		$args['meta_query']['c27_promoted_clause'] = CASE27_WP_Job_Manager_Queries::instance()->promoted_first_clause();

		$args['orderby'] = 'c27_promoted_clause_end_date ' . $args['orderby'];
	}

	if ($data['behavior'] == 'show_promoted_only') {
		$args['meta_query']['c27_promoted_only_clause'] = CASE27_WP_Job_Manager_Queries::instance()->promoted_only_clause();
	}

	if ($data['behavior'] == 'hide_promoted') {
		$args['meta_query']['c27_hide_promoted_clause'] = CASE27_WP_Job_Manager_Queries::instance()->hide_promoted_clause();
	}

	// dump($args);

	// dump((new WP_Query($args))->request);

	$listings = get_posts($args);
?>

<?php if (!$data['template'] || in_array( $data['template'], ['grid', 'fluid-grid'] ) ): ?>
	<section class="i-section listing-feed">
		<div class="container-fluid">
			<div class="row section-body">
				<?php foreach ($listings as $listing): $listing->_c27_show_promoted_badge = $data['show_promoted_badge'] == true; ?>
					<?php c27()->get_partial('listing-preview', [
						'listing' => $listing,
						'wrap_in' => sprintf(
										'col-lg-%1$d col-md-%2$d col-sm-%3$d col-xs-%4$d reveal',
										12 / absint( $data['columns']['lg'] ), 12 / absint( $data['columns']['md'] ),
										12 / absint( $data['columns']['sm'] ), 12 / absint( $data['columns']['xs'] )
									),
						]) ?>
				<?php endforeach ?>
			</div>
		</div>
	</section>
<?php endif ?>

<?php if ($data['template'] == 'carousel'): ?>
	<section class="i-section listing-feed-2">
		<div class="container">
			<div class="row section-body">
				<div class="owl-carousel listing-feed-carousel">
					<?php foreach ($listings as $listing): $listing->_c27_show_promoted_badge = $data['show_promoted_badge'] == true; ?>
						<div class="item reveal">
							<?php c27()->get_partial('listing-preview', ['listing' => $listing]) ?>
						</div>
					<?php endforeach ?>

					<?php if (count($listings) <= 3): ?>
						<?php foreach (range(0, absint(count($listings) - 4)) as $i): ?>
							<div class="item reveal c27-blank-slide"></div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>
			<div class="lf-nav <?php echo $data['invert_nav_color'] ? 'lf-nav-light' : '' ?>">
				<ul>
					<li>
						<a href="#" class="listing-feed-prev-btn">
							<i class="material-icons">keyboard_arrow_left</i>
						</a>
					</li>
					<li>
						<a href="#" class="listing-feed-next-btn">
							<i class="material-icons">keyboard_arrow_right</i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>
<?php endif ?>

<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_ready_script(jQuery);</script>
<?php endif ?>
