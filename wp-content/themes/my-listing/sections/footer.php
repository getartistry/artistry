<?php
	$data = c27()->merge_options([
			'footer_text'      => c27()->get_setting('footer_text', ''),
			'show_widgets'     => c27()->get_setting('footer_show_widgets', true),
			'show_footer_menu' => c27()->get_setting('footer_show_menu', true),
		], $data);
?>

<footer class="footer <?php echo esc_attr( ! $data['show_widgets'] ? 'footer-mini' : '' ) ?>">
	<div class="container">
		<?php if ($data['show_widgets']): ?>
			<div class="row">
				<?php dynamic_sidebar('footer') ?>
			</div>
		<?php endif ?>

		<div class="row">
			<div class="col-md-12 reveal">
				<div class="footer-bottom">
					<div class="row">
						<div class="col-md-4 col-sm-12 col-xs-12 copyright">
							<p><?php echo str_replace( '{{year}}', date('Y'), $data['footer_text'] ) ?></p>
						</div>

						<?php if ($data['show_footer_menu']): ?>
							<div class="col-md-8 col-sm-12 col-xs-12 social-links">
								<?php wp_nav_menu([
									'theme_location' => 'footer',
									'container' => false,
									'menu_class' => 'main-menu',
									'items_wrap' => '<ul id="%1$s" class="%2$s social-nav">%3$s</ul>'
									]); ?>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
