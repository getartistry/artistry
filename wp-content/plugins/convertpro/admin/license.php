<?php
/**
 * License Page.
 *
 * @package ConvertPro
 */

?>

<div class="cp-license-wrap">
	<h3 class="cp-gen-set-title" ><?php _e( 'License', 'convertpro' ); ?></h3>

	<?php
		$bsf_product_id = bsf_extract_product_id( CP_V2_BASE_DIR );
		$args           = array(
			'product_id'                       => $bsf_product_id,
			'button_text_activate'             => 'Activate License',
			'bsf_license_allow_email'          => false,
			'button_text_deactivate'           => 'Deactivate License',
			'submit_button_class'              => 'button-primary cp-pro-button-space',
			'form_class'                       => 'cp-pro-form-wrap',
			'bsf_license_form_heading_class'   => 'cp-pro-heading-message',
			'bsf_license_active_class'         => 'cp-pro-success-message',
			'bsf_license_not_activate_message' => 'cp-pro-license-error',
			'size'                             => 'regular',
		);

		echo bsf_license_activation_form( $args );

	?>

</div> <!-- End Wrapper -->
