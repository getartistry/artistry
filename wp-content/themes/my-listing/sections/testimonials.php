<?php
	$data = c27()->merge_options([
			'testimonials' => [],
            'is_edit_mode' => false,
		], $data);
?>

<section class="i-section testimonials">
	<div class="container-fluid">
		<div class="row section-body">
			<div id="customDots" class="col-md-12 owl-dots reveal">
				<?php $i = 0; ?>
				<?php foreach ((array) $data['testimonials'] as $testimonial): ?>
					<a href="#" class="testimonial-image <?php echo $i == 1 ? 'active' : '' ?>" data-slide-no="<?php echo esc_attr( $i ) ?>">
						<?php if (isset($testimonial['author_image']) && isset($testimonial['author_image']['url'])): ?>
							<img src="<?php echo esc_url( $testimonial['author_image']['url'] ) ?>">
						<?php endif ?>
					</a>
				<?php $i++; endforeach; ?>
			</div>
			<div class="col-md-12 owl-carousel testimonial-carousel reveal">
				<?php $i = 0; ?>
				<?php foreach ((array) $data['testimonials'] as $testimonial): ?>
					<div class="testimonial-content item" data-testimonial="<?php echo esc_attr( $i ) ?>" style="background-image: url('<?php echo esc_url( c27()->image('SVG/testimonial-bg.svg') ) ?>');">
						<?php if (isset($testimonial['content'])): ?>
							<h1 class="case27-accent-text"><?php echo esc_html( $testimonial['content'] ) ?></h1>
						<?php endif ?>

						<?php if (isset($testimonial['author'])): ?>
							<p>
								<?php echo esc_html( $testimonial['author'] ) ?>

								<?php if (isset($testimonial['company'])): ?>
									<span><?php echo esc_html( $testimonial['company'] ) ?></span>
								<?php endif ?>
							</p>
						<?php endif ?>
					</div>
				<?php $i++; endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_ready_script(jQuery);</script>
<?php endif ?>
