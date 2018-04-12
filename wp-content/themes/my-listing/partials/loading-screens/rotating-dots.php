<?php
$loaderColor = c27()->hexToRgb( c27()->get_setting( 'general_loading_overlay_color' ,'#000000' ) );
$secondaryLoaderColor = $loaderColor;
$secondaryLoaderColor['a'] = '0.2';
?>
<div class="loader-bg main-loader box-shadow-color" style="background-color: <?php echo esc_attr( c27()->get_setting( 'general_loading_overlay_background_color' ,'#ffffff' ) ) ?>;">
	<div class="spin-box four-dots" style="
	box-shadow: 10px 10px rgba(<?php echo join(', ', $loaderColor) ?>), -10px 10px rgba(<?php echo join(', ', $secondaryLoaderColor) ?>),
				-10px -10px rgba(<?php echo join(', ', $loaderColor) ?>), 10px -10px rgba(<?php echo join(', ', $secondaryLoaderColor) ?>);
	"></div>
</div>