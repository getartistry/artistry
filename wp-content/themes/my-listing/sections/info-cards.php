<?php
	$data = c27()->merge_options([
			'items' => [],
		], $data);

?>

<section class="i-section services">
	<div class="container-fluid">
		<div class="row section-body">
			<?php foreach ($data['items'] as $item): ?>
				<div class="<?php echo esc_attr( $item['size'] ) ?> reveal">
					<div class="service-item">
						<?php if ($item['icon']): ?>
							<div class="service-item-icon">
								<span class="<?php echo esc_attr( $item['icon'] ) ?>"></span>
							</div>
						<?php endif ?>

						<div class="service-item-info">
							<?php if ($item['title']): ?>
								<h2><?php echo esc_html( $item['title'] ) ?></h2>
							<?php endif ?>

							<?php if ($item['content']): ?>
								<?php echo apply_filters( 'the_content', html_entity_decode( $item['content'] ) ) ?>
							<?php endif ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</section>