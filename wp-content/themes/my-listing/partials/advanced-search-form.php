<?php
$data = c27()->merge_options([
	'explore_instance' => false,
], $data);

if ( ! ( $explore = $data['explore_instance'] ) ) {
	return false;
}

if ( empty( $explore->store['listing_types'] ) || ! $explore->get_active_listing_type() ) {
	return false;
}
?>

<div class="finder-tabs col-md-12 <?php echo count( $explore->store['listing_types'] ) > 1 ? 'with-listing-types' : 'without-listing-types' ?>">
	<ul class="nav nav-tabs tabs-menu" role="tablist">

		<?php if ( count( $explore->store['listing_types'] ) > 1 ): ?>

			<li :class="state.activeTab == 'listing-types' ? 'active' : ''">
				<a href="#listing-type" role="tab" class="tab-switch" @click="state.activeTab = 'listing-types'">
					<div v-show="!state.activeListingType">
						<i class="mi search"></i><p><?php _e( 'Type', 'my-listing' ) ?></p>
					</div>
					<div v-show="state.activeListingType">
						<i :class="state.activeListingTypeData.icon"></i>
						<p>{{ state.activeListingTypeData.name }}</p>
					</div>
				</a>
			</li>

		<?php endif ?>

		<li :class="state.activeTab == 'search-form' ? 'active' : ''" v-show="state.activeListingType">
			<a href="#search-form" role="tab" class="tab-switch" @click="state.activeTab = 'search-form'">
				<i class="mi filter_list"></i><p><?php _e( 'Filters', 'my-listing' ) ?></p>
			</a>
		</li>

		<li :class="state.activeTab == 'categories' ? 'active' : ''">
			<a href="#categories" role="tab" class="tab-switch" @click="state.activeTab = 'categories'">
				<i class="material-icons">bookmark_border</i><p><?php _e( 'Categories', 'my-listing' ) ?></p>
			</a>
		</li>
	</ul>

	<div class="tab-content">
		<div id="select-listing-type" class="listing-type-select-tab c27-explore-categories search-tab tab-pane fade" :class="state.activeTab == 'listing-types' ? 'in active' : ''">

			<?php foreach ( $explore->store['listing_types'] as $listing_type ): ?>

				<div class="listing-cat type-<?php echo esc_attr( $listing_type->get_slug() ) ?>"
					 :class="state.activeListingType == '<?php echo esc_attr( $listing_type->get_slug() ) ?>'  ? 'active' : ''">
					<a @click.prevent="state.activeListingType = '<?php echo esc_attr( $listing_type->get_slug() ) ?>'; state.activeListingTypeData.name = '<?php echo esc_attr( $listing_type->get_plural_name() ) ?>'; state.activeListingTypeData.icon = '<?php echo esc_attr( $listing_type->get_setting( 'icon' ) ) ?>'; state.activeTab = 'search-form'; _getListings();">
						<div class="overlay <?php echo $explore->get_data( 'categories_overlay' )['type'] == 'gradient' ? esc_attr( $explore->get_data( 'categories_overlay' )['gradient'] ) : '' ?>"
							style="<?php echo $explore->get_data( 'categories_overlay' )['type'] == 'solid_color' ? 'background-color: ' . esc_attr( $explore->get_data( 'categories_overlay' )['solid_color'] ) . '; ' : '' ?>"></div>
						<div class="lc-background" style="background-image: url('<?php echo esc_url( $listing_type->get_image() ) ?>');">
						</div>
						<div class="lc-info">
							<h4><?php echo esc_html( $listing_type->get_plural_name() ) ?></h4>
							<!-- <h6><?php echo esc_html( $listing_type->get_count() ) ?></h6> -->
						</div>
						<div class="lc-icon">
							<i class="<?php echo esc_attr( $listing_type->get_setting( 'icon' ) ) ?>"></i>
						</div>
					</a>
				</div>

			<?php endforeach ?>

		</div>

		<div id="search-form" class="listing-type-filters search-tab tab-pane fade" :class="state.activeTab == 'search-form' ? 'in active' : ''">

			<?php foreach ($explore->store['listing_types'] as $listing_type): ?>

				<?php $GLOBALS['c27-facets-vue-object'][ $listing_type->get_slug() ] = ['page' => 0]; ?>

				<div v-show="state.activeListingType == '<?php echo esc_attr( $listing_type->get_slug() ) ?>'" class="search-filters type-<?php echo esc_attr( $listing_type->get_slug() ) ?>">
					<form class="light-forms filter-wrapper">

						<?php foreach ((array) $listing_type->get_search_filters() as $facet): ?>

							<?php if ( $facet['type'] == 'order' ): ?>
								<?php continue; ?>
							<?php endif ?>

							<?php c27()->get_partial("facets/{$facet['type']}", [
								'facet' => $facet,
								'listing_type' => $listing_type->get_slug(),
								'type' => $listing_type,
								]) ?>

						<?php endforeach ?>

					</form>
					<div class="form-group fc-search">
						<a href="#" class="buttons button-2 full-width button-animated c27-explore-search-button" @click.prevent="getListings">
							<?php _e( 'Search', 'my-listing' ) ?><i class="material-icons">keyboard_arrow_right</i>
						</a>
					</div>
				</div>

			<?php endforeach ?>

		</div>

		<div id="categories" class="listing-cat-tab tab-pane fade c27-explore-categories" :class="state.activeTab == 'categories' ? 'in active' : ''">

			<?php foreach ((array) $explore->store['category-items'] as $term_type => $term_group): ?>

				<div v-show="<?php echo "'" . esc_attr( $term_type ) . "' == state.activeListingType" ?>">

					<?php foreach ($term_group as $term):
						$image = $term->get_image();
						// dump($term->get_data('listing_type'));
						?>

						<div class="listing-cat" :class="<?php echo esc_attr( $term->get_id() ) ?> == taxonomies.categories.term ? 'active' : ''">
							<a @click.prevent="taxonomies.categories.term = '<?php echo esc_attr( $term->get_id() ) ?>'; taxonomies.categories.page = 0; getListingsByTaxonomy();">
								<div class="overlay <?php echo $explore->get_data('categories_overlay')['type'] == 'gradient' ? esc_attr( $explore->get_data('categories_overlay')['gradient'] ) : '' ?>"
									style="<?php echo $explore->get_data('categories_overlay')['type'] == 'solid_color' ? 'background-color: ' . esc_attr( $explore->get_data('categories_overlay')['solid_color'] ) . '; ' : '' ?>"></div>
								<div class="lc-background" style="<?php echo is_array($image) && !empty($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
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

					<?php endforeach ?>

				</div>

			<?php endforeach ?>

		</div>

		<div class="listing-regions-tab tab-pabe fade c27-explore-regions" :class="state.activeTab == 'regions' ? 'in active' : ''">
			<?php if ( $explore->active_region ): ?>
				<div class="searching-for">
					<?php echo $explore->active_region->get_icon([ 'background' => false, 'color' => false ]); ?>
					<p class="searching-for-text"><?php printf( __( 'Searching for listings in <span class="filter-label">%s</span>', 'my-listing' ), $explore->active_region->get_name() ) ?></p>
				</div>
			<?php endif ?>
		</div>

		<div class="listing-tags-tab tab-pabe fade c27-explore-tags" :class="state.activeTab == 'tags' ? 'in active' : ''">
			<?php if ( $explore->active_tag ): ?>
				<div class="searching-for">
					<?php echo $explore->active_tag->get_icon([ 'background' => false, 'color' => false ]); ?>
					<p class="searching-for-text"><?php printf( __( 'Searching listings for <span class="filter-label">%s</span>', 'my-listing' ), $explore->active_tag->get_name() ) ?></p>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>
