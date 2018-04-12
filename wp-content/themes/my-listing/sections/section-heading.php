<?php
	$data = c27()->merge_options([
			'title' => '',
			'subtitle' => '',
			'content' => '',
            'overlay_type' => 'gradient',
            'overlay_gradient' => 'gradient1',
            'overlay_solid_color' => 'rgba(0, 0, 0, .5)',
		], $data);

?>

<section class="i-section <?php echo $data['overlay_type'] == 'gradient' ? esc_attr( $data['overlay_gradient'] ) : '' ?>"
		 style="border: none; <?php echo $data['overlay_type'] == 'solid_color' ? 'background-color: ' . esc_attr( $data['overlay_solid_color'] ) . '; ' : '' ?>">
	<div class="container-fluid">
		<div class="row section-title reveal" <?php echo (!$data['content'] || !($data['title'] && $data['subtitle'])) ? 'style="margin-bottom: 0px;"' : '' ?>>
			<?php if ($data['subtitle']): ?>
				<p><?php echo esc_html( $data['subtitle'] ) ?></p>
			<?php endif ?>

			<?php if ($data['title']): ?>
				<h2 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h2>
			<?php endif ?>
		</div>

		<?php if ($data['content']): ?>
			<div class="row section-body reveal">
				<div class="col-md-12">
					<?php echo do_shortcode($data['content']) ?>
				</div>
			</div>
		<?php endif ?>
	</div>
</section>
