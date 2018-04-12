<?php

if ( ! class_exists( 'WP_Job_Manager' ) ) {
	return false;
}

/*
 * Explore page options.
 */
$data = c27()->merge_options([
	'title'    		 => '',
	'subtitle' 		 => '',
	'template' 		 => 'explore-default', // explore-default or explore-no-map
    'map_skin' 		 => 'skin1',
	'active_tab'     => 'listing-types',
    'categories'     => [
	    'count'      => 10,
	    'order'      => 'DESC',
	    'order_by'   => 'count',
	    'hide_empty' => true,
    ],
    'is_edit_mode'   => false,
    'scroll_to_results' => false,
    'scroll_wheel' => false,
	'listing-wrap'   => '',
    'listing_types'  => [],
	'finder_columns' => 'finder-one-columns',
	'categories_overlay' => [
		'type' => 'gradient',
		'gradient' => 'gradient1',
		'solid_color' => 'rgba(0, 0, 0, .1)',
	],
], $data);

$GLOBALS['c27-explore'] = new MyListing\Explore( $data );
$explore = &$GLOBALS['c27-explore'];

/*
 * Global variables.
 */
$GLOBALS['c27-facets-vue-object'] = [];


/*
 * The maximum number of columns for explore-2 template is "two". So, if the user sets
 * the option to "three" in Elementor settings, convert it to "two" columns.
 */
if ( $data['template'] == 'explore-2' && $data['finder_columns'] == 'finder-three-columns' ) {
	$data['finder_columns'] = 'finder-two-columns';
}
?>

<?php if (!$data['template'] || $data['template'] == 'explore-1' || $data['template'] == 'explore-2'): ?>
	<?php $data['listing-wrap'] = 'col-md-12'; ?>
	<div class="finder-container fc-type-1 <?php echo esc_attr( $data['finder_columns'] ) ?> <?php echo $data['finder_columns'] == 'finder-three-columns' ? 'fc-type-1-no-map' : '' ?>" id="c27-explore-listings">
		<div class="mobile-explore-head">
			<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
		</div>

		<div class="<?php echo $data['template'] == 'explore-2' ? 'fc-one-column' : '' ?>">
			<div class="finder-search collapse" id="finderSearch">
				<div class="finder-title col-md-12">
					<h1 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h1>
					<p><?php echo esc_html( $data['subtitle'] ) ?></p>
				</div>
				<div class="finder-tabs-wrapper">
					<?php c27()->get_partial( 'advanced-search-form', [
						'explore_instance' => $explore,
						] ) ?>
				</div>
			</div>

			<div class="finder-listings">
				<div class="fl-head">
					<div class="col-xs-4 sort-results showing-filter" v-show="state.activeTab == 'search-form' || state.activeTab == 'listing-types'">
						<?php foreach ( $explore->store['listing_types'] as $type ): ?>
							<?php require locate_template('partials/facets/order.php') ?>
						<?php endforeach ?>
					</div>

					<div class="col-xs-4 text-center">
						<span href="#" class="fl-results-no text-left" :style="state.activeTab == 'search-form' || state.activeTab == 'listing-types' ? '' : 'text-align: left;'">
							<i class="mi remove_red_eye" v-show="state.activeTab != 'search-form' && state.activeTab != 'listing-types'"></i>
							<span></span>
						</span>
					</div>

					<div class="col-xs-4" v-show="state.activeTab != 'search-form' && state.activeTab != 'listing-types'"></div>

					<?php if ( $data['finder_columns'] != 'finder-three-columns' ): ?>
						<div class="col-xs-4 map-toggle-button">
							<a href="#" class=""><?php _e( 'Map view', 'my-listing' ) ?><i class="material-icons sm-icon">map</i></a>
						</div>

						<div class="col-xs-4 column-switch">
							<a href="#" class="col-switch switch-one <?php echo $data['finder_columns'] == 'finder-one-columns' ? 'active' : '' ?>" data-no="finder-one-columns">
								<i class="material-icons">view_stream</i>
							</a>
							<a href="#" class="col-switch switch-two <?php echo $data['finder_columns'] == 'finder-two-columns' ? 'active' : '' ?>" data-no="finder-two-columns">
								<i class="material-icons">view_module</i>
							</a>
							<a href="#" class="col-switch switch-three <?php echo $data['finder_columns'] == 'finder-three-columns' ? 'active' : '' ?>" data-no="finder-three-columns">
								<i class="material-icons">view_comfy</i>
							</a>
						</div>
					<?php endif ?>

					<!-- <div class="btn btn-primary" @click="getListings">Get Listings</div> -->
					<!-- <div class="btn btn-primary" @click="updateMap">Update Map</div> -->
					<!-- <div class="btn btn-primary" @click="clearMapMarkers">Clear Markers</div> -->
				</div>
				<!-- <pre class="text-left">{{ facets[ state.activeListingType ] }}</pre> -->
				<!-- <pre>{{ state }}</pre> -->
				<!-- <pre>{{ facets }}</pre> -->
				<!-- <pre>{{ $data }}</pre> -->
				<!-- <pre>{{ taxonomies }}</pre> -->
				<div class="results-view" v-show="!state.loading"></div>
				<div class="loader-bg" v-show="state.loading">
					<?php c27()->get_partial('spinner', [
						'color' => '#777',
						'classes' => 'center-vh',
						'size' => 28,
						'width' => 3,
						]); ?>
				</div>
				<div class="col-md-12 center-button pagination c27-explore-pagination" v-show="!state.loading"></div>
			</div>
		</div>

		<?php if ( $data['finder_columns'] != 'finder-three-columns' ): ?>
			<div class="finder-map">
				<div class="card-view">
					<a href="#" class="buttons button-1"><i class="material-icons sm-icon">view_module</i><?php _e( 'Card view', 'my-listing' ) ?></a>
				</div>
				<div class="map c27-map" id="<?php echo esc_attr( 'map__' . uniqid() ) ?>" data-options="<?php echo htmlspecialchars(json_encode([
					'skin' => $data['map_skin'],
					'scrollwheel' => $data['scroll_wheel'],
					'zoom' => 10,
				]), ENT_QUOTES, 'UTF-8'); ?>">
				</div>
			</div>
		<?php endif ?>
	</div>
<?php endif ?>

<?php if ($data['template'] == 'explore-no-map'): ?>
	<?php $data['listing-wrap'] = 'col-md-4 col-sm-6 reveal'; ?>
	<div id="c27-explore-listings">
		<div class="finder-container fc-type-2">
			<div class="mobile-explore-head">
				<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
			</div>
			<div class="finder-search collapse" id="finderSearch">
				<div class="finder-title col-md-12">
					<h3 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h3>
					<p><?php echo esc_html( $data['subtitle'] ) ?></p>
				</div>
				<div class="finder-tabs-wrapper">
					<?php c27()->get_partial('advanced-search-form', [
							'explore_instance' => &$explore,
						]) ?>
				</div>
			</div>
			<div class="finder-overlay"></div>
		</div>

		<section class="i-section explore-type-2">
			<div class="container">
				<div class="fl-head row reveal">
					<div class="col-md-4 col-sm-6 col-xs-6 toggle-search-type-2">
						<a href="#"><i class="mi filter_list"></i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
					</div>

					<div class="col-md-4 col-sm-6 col-xs-6" v-show="state.activeTab != 'search-form' && state.activeTab != 'listing-types'"></div>

					<div class="col-md-4 col-sm-6 col-xs-6">
						<span class="fl-results-no" :style="state.activeTab == 'search-form' || state.activeTab == 'listing-types' ? '' : 'text-align: right;'">
							<i class="mi remove_red_eye" v-show="state.activeTab != 'search-form' && state.activeTab != 'listing-types'"></i>
							<span></span>
						</span>
					</div>

					<div class="col-md-4 col-sm-6 col-xs-6 sort-results" v-show="state.activeTab == 'search-form' || state.activeTab == 'listing-types'">
						<?php foreach ( $explore->store['listing_types'] as $type ): ?>
							<?php require locate_template('partials/facets/order.php') ?>
						<?php endforeach ?>
					</div>
				</div>
				<!-- <pre>{{ state }}</pre> -->
				<div class="row results-view fc-type-2-results" v-show="!state.loading"></div>
				<div class="loader-bg" v-show="state.loading">
					<?php c27()->get_partial('spinner', [
						'color' => '#777',
						'classes' => 'center-vh',
						'size' => 28,
						'width' => 3,
						]); ?>
				</div>
				<div class="row center-button pagination c27-explore-pagination" v-show="!state.loading"></div>
			</div>
		</section>
	</div>
<?php endif ?>

<script type="text/javascript">
	var CASE27_Explore_Settings = {
		Facets: <?php echo json_encode( $GLOBALS['c27-facets-vue-object'] ) ?>,
		ListingWrap: <?php echo json_encode( $data['listing-wrap'] ) ?>,
		ActiveCategory: <?php echo json_encode( $explore->active_category ? $explore->active_category->get_id() : false ) ?>,
		ActiveRegion: <?php echo json_encode( $explore->active_region ? $explore->active_region->get_id() : false ) ?>,
		ActiveTag: <?php echo json_encode( $explore->active_tag ? $explore->active_tag->get_id() : false ) ?>,
		ActiveTab: <?php echo json_encode( $explore->get_active_tab() ) ?>,
		ScrollToResults: <?php echo json_encode( $data['scroll_to_results'] ) ?>,
	};

	<?php if ( $explore->get_active_listing_type() ): ?>
		CASE27_Explore_Settings.ActiveListingType = <?php echo json_encode( [
			'name' => $explore->get_active_listing_type()->get_plural_name(),
			'icon' => $explore->get_active_listing_type()->get_setting( 'icon' ),
			'slug' => $explore->get_active_listing_type()->get_slug(),
		] ) ?>;
	<?php else: ?>
		CASE27_Explore_Settings.ActiveListingType = <?php echo json_encode( [
			'name' => null,
			'icon' => null,
			'slug' => null,
		] ) ?>;
	<?php endif ?>
</script>


<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_ready_script(jQuery); CASE27_Explore_Listings__func(); case27_initialize_maps();</script>
<?php endif ?>