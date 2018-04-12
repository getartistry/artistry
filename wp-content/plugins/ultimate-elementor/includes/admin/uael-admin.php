<?php
/**
 * UAEL Admin HTML.
 *
 * @package UAEL
 */

use UltimateElementor\Classes\UAEL_Helper;

$branding     = UAEL_Helper::get_white_labels();
$replace_logo = UAEL_Helper::is_replace_logo();

?>
<div class="uael-menu-page-wrapper">
	<div id="uael-menu-page">
		<div class="uael-menu-page-header <?php echo esc_attr( implode( ' ', $uael_header_wrapper_class ) ); ?>">
			<div class="uael-container uael-flex">
				<div class="uael-title">
					<?php if ( '' !== $branding['plugin']['name'] && $replace_logo ) { ?>
						<span><?php echo $branding['plugin']['name']; ?></span>
					<?php } else { ?>
						<a href="<?php echo esc_url( $uael_visit_site_url ); ?>" target="_blank" rel="noopener" >
						<?php if ( $uael_icon ) { ?>
							<img src="<?php echo esc_url( UAEL_URL . 'admin/assets/images/uael_logo.png' ); ?>" class="uael-header-icon" alt="<?php echo UAEL_PLUGIN_NAME; ?> " >
						<?php } ?>
						<?php do_action( 'uael_header_title' ); ?>
						</a>
					<?php } ?>
				</div>
				<div class="uael-top-links">
					<?php
						esc_attr_e( 'Take Elementor to The Next Level', 'uael' );
					?>
				</div>
			</div>
		</div>

		<?php
		// Settings update message.
		if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
			?>
				<div id="message" class="notice notice-success is-dismissive uael-notice"><p> <?php esc_html_e( 'Settings saved successfully.', 'uael' ); ?> </p></div>
			<?php
		}
		do_action( 'uael_render_admin_content' );
		?>
	</div>
</div>
