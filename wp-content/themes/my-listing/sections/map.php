<?php
	$data = c27()->merge_options([
			'options' => [
				'items_type' => 'custom-locations',
				'zoom' => 12,
				'skin' => 'skin1',
				'marker_type' => 'basic',
				'locations' => [],
				'listings_query' => ['lat' => false, 'lng' => false, 'radius' => false, 'listing_type' => false, 'count' => 9],
				'cluster_markers' => true,
				'draggable' => true,
			],
			'template' => 'default',
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'show_get_directions' => true,
            'ref' => '',
			'wrapper_class' => 'grid-item reveal',
			'content' => '',
            '_section_id' => 'section__' . uniqid(),
            'is_edit_mode' => false,
		], $data);

	$data['options']['_section_id'] = $data['_section_id'];

	// dump($data);
?>

<?php if (!$data['template'] || $data['template'] == 'default'): ?>
	<section class="contact-map" id="<?php echo esc_attr( $data['_section_id'] ) ?>">
		<div class="c27-map map" data-options="<?php echo htmlspecialchars(json_encode($data['options']), ENT_QUOTES, 'UTF-8'); ?>">
		</div>
		<div class="c27-map-listings hide"></div>
	</section>
<?php endif ?>

<?php if ($data['template'] == 'block'): ?>
	<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>" id="<?php echo esc_attr( $data['_section_id'] ) ?>">
		<div class="element map-block">
			<div class="pf-head">
				<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
					<?php if ($data['icon_style'] != 3): ?>
						<?php echo c27()->get_icon_markup($data['icon']) ?>
					<?php endif ?>
					<h5><?php echo esc_html( $data['title'] ) ?></h5>
				</div>

				<?php if ($data['show_get_directions'] && $data['options']['locations']): ?>
					<?php
					if (isset($data['options']['locations'][0]['address'])) {
						$get_directions_link = 'http://maps.google.com/maps?daddr=' . urlencode($data['options']['locations'][0]['address']);
					} else {
						$get_directions_link = 'http://maps.google.com/maps?daddr=' . $data['options']['locations'][0]['marker_lat'] . ',' . $data['options']['locations'][0]['marker_lng'];
					}
					?>
					<div class="location-address">
						<a
							href="<?php echo esc_url( $get_directions_link ) ?>"
							target="_blank"
							>
							<?php _e( 'Get Directions', 'my-listing' ) ?>
						</a>
					</div>
				<?php endif ?>
			</div>
			<div class="pf-body contact-map">
				<div class="c27-map map" data-options="<?php echo htmlspecialchars(json_encode($data['options']), ENT_QUOTES, 'UTF-8'); ?>">
				</div>
				<div class="c27-map-listings hide"></div>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if ($data['template'] == 'full_width_content'): ?>
	<section class="section-slider" id="<?php echo esc_attr( $data['_section_id'] ) ?>">
		<div class="featured-section featured-light featured-section-type-map featured-map">
			<div class="overlay"></div>
			<div class="featured-caption">
				<div class="row">
					<div class="container">
						<div class="fc-description col-md-12">
							<?php echo do_shortcode($data['content']) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="c27-map map" style="position: absolute; top: 0px; left: 0px;"
				 data-options="<?php echo htmlspecialchars(json_encode($data['options']), ENT_QUOTES, 'UTF-8'); ?>">
			</div>
			<div class="c27-map-listings hide"></div>
		</div>
	</section>
<?php endif ?>

<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_initialize_maps(); case27_ready_script(jQuery);</script>
<?php endif ?>