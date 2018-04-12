<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_icon_picker') ) {


class acf_field_icon_picker extends acf_field {

	function __construct( $settings )
	{
		$this->name = 'icon_picker';
		$this->label = __('Icon Picker', 'my-listing');
		$this->category = 'choice';
		$this->settings = $settings;

    	parent::__construct();
	}

	/*
	*  Render the field.
	*/
	function render_field( $field )
	{
		$randomID = 'icon_picker__' . uniqid(); ?>

		<div id="<?php echo esc_attr( $randomID ) ?>" class="c27-acf-icon-picker-field-wrapper">
			<input type="hidden" name="<?php echo esc_attr($field['name']) ?>" v-model="value">
			<iconpicker v-model="value"></iconpicker>
			<div class="c27-reset-icon-picker" data-id="<?php echo esc_attr( "#{$randomID}" ) ?>" data-value="<?php echo esc_attr( htmlspecialchars(json_encode($field['value']), ENT_QUOTES, 'UTF-8') ) ?>"></div>
		</div>
		<script type="text/javascript">
			setInterval(function() {
				if (jQuery('.c27-icon-picker').length === 0) {
					jQuery('.c27-reset-icon-picker').click();
				}
			}, 2500);
		</script>

		<?php ob_start() ?>
			new Vue({
				el: '#<?php echo esc_attr( $randomID ) ?>',
				data: { value: <?php echo json_encode(esc_attr($field['value'])) ?> }
			});
		<?php
		wp_add_inline_script('theme-script-main', ob_get_clean());
	}
}

new acf_field_icon_picker( $this->settings );

}
