<div class="loader-bg main-loader" style="background-color: <?php echo esc_attr( c27()->get_setting( 'general_loading_overlay_background_color' ,'#ffffff' ) ) ?>;">
	<?php c27()->get_partial('spinner', [
			'color' => c27()->get_setting( 'general_loading_overlay_color', '#ffffff' ),
			]); ?>
</div>