<?php
	$data = c27()->merge_options([
			'layout' => 'wide',
			'align' => 'center',
			'listing_types' => '',
			'tabs_mode' => 'light',
			'box_shadow' => 'no',
			'search_page_id' => '',
		], $data);

	if (!$data['search_page_id']) {
		$data['search_page_id'] = c27()->get_setting( 'general_explore_listings_page' );
	}

	$listing_types = [];
	foreach ((array) explode(',', (string) $data['listing_types']) as $listing_type) {
		$listing_type = trim($listing_type);

		$listing_type = get_posts([
			'name'        => $listing_type,
			'post_type'   => 'case27_listing_type',
			'post_status' => 'publish',
			'numberposts' => 1
			]);

		if ( $listing_type ) {
			$listing_types[] = new CASE27\Integrations\ListingTypes\ListingType( $listing_type[0] );
		}
	}
?>
<div class="<?php echo esc_attr( 'text-' . $data['align'] ) ?> <?php echo esc_attr( $data['tabs_mode'] == 'dark' ? 'featured-light' : $data['tabs_mode'] ) ?>">
	<div class="featured-search reveal <?php echo esc_attr( $data['layout'] ) ?>">
		<div class="fs-tabs">
			<ul class="nav nav-tabs" role="tablist">
				<?php
				$i = 0;
				foreach ($listing_types as $type): $i++; ?>
					<li role="presentation" class="<?php echo $i === 1 ? 'active' : '' ?>">
						<a href="#search-form-tab-<?php echo esc_attr( $type->get_slug() ) ?>" role="tab" class="tab-switch">
							<i class="<?php echo esc_attr( $type->get_setting('icon') ) ?>"></i><?php echo esc_html( $type->get_setting('plural_name') ) ?>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
			<div class="tab-content <?php echo $data['box_shadow'] == 'yes' ? 'add-box-shadow' : '' ?>">
				<?php
				$i = 0;
				foreach ($listing_types as $type): $i++; ?>

					<div role="tabpanel" class="tab-pane fade in <?php echo $i === 1 ? 'active' : '' ?>" id="search-form-tab-<?php echo esc_attr( $type->get_slug() ) ?>">
						<form method="GET" action="<?php echo esc_url( is_numeric( $data['search_page_id'] ) ? get_permalink( absint( $data['search_page_id'] ) ) : $data['search_page_id'] ) ?>">

							<?php foreach ((array) $type->get_search_filters( 'basic' ) as $facet): ?>

								<?php c27()->get_partial("facets/{$facet['type']}", [
									'facet' => $facet,
									'listing_type' => $type->get_slug(),
	            					'is_vue_template' => false,
	            					'type' => $type,
									]) ?>

							<?php endforeach ?>

							<div class="form-group">
								<input type="hidden" name="tab" value="search-form">
								<input type="hidden" name="type" value="<?php echo esc_attr( $type->get_slug() ) ?>">
								<button type="submit" class="buttons button-2 search"><i class="material-icons">search</i><?php _e( 'Search', 'my-listing' ) ?></button>
							</div>
						</form>
					</div>

				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>