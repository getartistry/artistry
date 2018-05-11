<?php
/**
 * Add Convertplug V2 Widget.
 *
 * @package convertpro
 */

	?>

<div class="edit-screen-overlay" style="overflow: hidden;background: #FCFCFC;position: fixed;width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999;">
	<div class="cp-absolute-loader" style="visibility: visible;overflow: hidden;">
		<div class="cp-loader">
			<h2 class="cp-loader-text">Loading...</h2>
			<div class="cp-loader-wrap">
				<div class="cp-loader-bar">
					<div class="cp-loader-shadow"></div>
				</div>
			</div>
		</div>
	</div>
</div><!-- .edit-screen-overlay -->
<div class="cp-customizer-wrap clear">
	<?php
	if ( function_exists( 'cp_style_dashboard' ) ) {

		$style_id = isset( $_GET['post'] ) ? esc_attr( $_GET['post'] ) : '';
		$type     = get_post_meta( $style_id, 'cp_module_type', true );

		if ( false == $type || 'undefined' == $style_id || '' == $style_id ) {
			$type = isset( $_GET['type'] ) ? esc_attr( $_GET['type'] ) : 'modal_popup';
		}

		cp_style_dashboard( 'Convert_Plug', $type );
	}
	?>
</div>
