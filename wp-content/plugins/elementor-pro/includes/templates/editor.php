<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script type="text/template" id="tmpl-elementor-pro-template-library-activate-license-button">
	<a href="<?php echo \ElementorPro\License\Admin::get_url(); ?>" target="_blank">
		<button class="elementor-template-library-template-action elementor-button elementor-button-go-pro">
			<i class="fa fa-external-link-square"></i><span class="elementor-button-title"><?php _e( 'Activate License', 'elementor-pro' ); ?></span>
		</button>
	</a>
</script>
