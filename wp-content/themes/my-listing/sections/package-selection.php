<?php
	if (!class_exists('WooCommerce')) return;

	$data = c27()->merge_options([
			'packages' => [],
			'submit_page' => '',
		], $data);

	$submit_to = get_permalink($data['submit_page']);
?>
<section class="i-section">
	<div class="container-fluid">
		<div class="row section-body">
			<?php foreach ((array) $data['packages'] as $package): $product = wc_get_product( $package['package'] ); if (!$product) continue; ?>
				<div class="col-md-4 col-sm-6 col-xs-12 reveal">
					<div class="pricing-item <?php echo $package['featured'] ? 'featured' : '' ?>">
						<h2 class="plan-name"><?php echo $product->get_title() ?></h2>
						<h2 class="plan-price case27-primary-text"><?php echo $product->get_price_html() ?></h2>
						<p class="plan-desc"><?php echo $product->get_short_description() ?></p>
						<div class="plan-features"><?php echo $product->get_description() ?></div>
						<a class="select-plan buttons button-1" href="<?php echo esc_url( add_query_arg('selected_package', $product->get_id(), $submit_to) ) ?>">
							<i class="material-icons sm-icon">send</i><?php _e( 'Select Plan', 'my-listing' ) ?>
						</a>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</section>
