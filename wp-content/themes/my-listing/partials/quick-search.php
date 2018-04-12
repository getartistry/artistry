<?php
$data = c27()->merge_options([
	'instance-id' => 'quick-search--' . uniqid(),
	'placeholder' => __( 'Search...', 'my-listing' ),
	'ref' => '',
	'align' => 'center',
	'style' => 'light',
], $data);

$featured_categories = c27()->get_setting('header_search_form_featured_categories', []);
?>
<div class="quick-search-instance <?php echo esc_attr( 'text-' . $data['align'] ) ?>" id="<?php echo esc_attr( $data['instance-id'] ) ?>">
	<form action="<?php echo esc_url( c27()->get_setting('general_explore_listings_page') ) ?>" method="GET">
		<div class="dark-forms header-search <?php echo $data['ref'] == 'shortcode' ? 'search-shortcode' : '' ?> search-shortcode-<?php echo esc_attr( $data['style'] ) ?>">
			<i class="material-icons">search</i>
			<input type="search" placeholder="<?php echo esc_attr( $data['placeholder'] ) ?>" v-model="search_query" name="search_keywords" autocomplete="off">
			<div class="instant-results" v-if="search_query_valid">
				<ul class="instant-results-list ajax-results"></ul>
				<button type="submit" class="buttons full-width button-5 search view-all-results" v-show="has_found_posts && !loading">
					<i class="material-icons">search</i><?php _e( 'View all results', 'my-listing' ) ?>
				</button>
				<button type="submit" class="buttons full-width button-5 search view-all-results" v-show="!has_found_posts && !loading">
					<i class="material-icons">close</i><?php _e( 'No results', 'my-listing' ) ?>
				</button>
				<div class="loader-bg" v-show="loading">
					<?php c27()->get_partial('spinner', [
						'color' => '#777',
						'classes' => 'center-vh',
						'size' => 24,
						'width' => 2.5,
						]); ?>
				</div>
			</div>

			<?php if ( ! is_wp_error( $featured_categories ) && is_array( $featured_categories ) ): ?>

				<div class="instant-results" v-if="!search_query_valid">
					<ul class="instant-results-list">
        				<li class="ir-cat"><?php _e( 'Featured', 'my-listing' ) ?></li>

						<?php foreach ($featured_categories as $category):
							$term = new CASE27\Classes\Term( $category );
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

			<?php endif ?>
		</div>
	</form>
</div>