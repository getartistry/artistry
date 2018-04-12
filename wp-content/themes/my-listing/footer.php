	<?php c27()->get_partial('login-modal') ?>
	<?php c27()->get_partial('quick-view-modal') ?>
	<?php c27()->get_partial('photoswipe-template') ?>
	<?php c27()->get_partial('marker-templates') ?>
</div>

<?php
if (isset($GLOBALS['c27_elementor_page']) && $page = $GLOBALS['c27_elementor_page']) {
	if (!$page->get_settings('c27_hide_footer')) {
		$args = [
			'show_widgets'      => $page->get_settings('c27_footer_show_widgets'),
			'show_footer_menu'  => $page->get_settings('c27_footer_show_footer_menu'),
		];

		c27()->get_section('footer', ($page->get_settings('c27_customize_footer') == 'yes' ? $args : []));
	}
} else {
	c27()->get_section('footer');
}
?>

<?php if (c27()->get_setting('footer_show_back_to_top_button', false)): ?>
	<a href="#" class="back-to-top">
		<i class="material-icons">keyboard_arrow_up</i>
	</a>
<?php endif ?>

<?php wp_footer() ?>

<?php do_action( 'case27_footer' ) ?>

</body>
</html>