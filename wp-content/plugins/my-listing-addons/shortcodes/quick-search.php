<?php

namespace CASE27\Shortcodes;

/**
 * Quick Search Shortcode.
 */
class QuickSearch {

	public $name = 'quick-search',
		   $title = '',
		   $description = '',
		   $content = null,
		   $attributes = ['placeholder' => '', 'align' => 'center', 'style' => 'light'],
		   $data = ['ref' => 'shortcode'];

	public function __construct()
	{
		$this->title = __( 'Quick Search', 'my-listing' );
		$this->description = __( 'A search form widget with instant query results.', 'my-listing' );
		$this->attributes['placeholder'] = __( 'Search...', 'my-listing' );
		add_shortcode( $this->name, [ $this, 'add_shortcode' ] );
	}

	public function add_shortcode($atts, $content = null)
	{
		ob_start();

		c27()->get_partial('quick-search', shortcode_atts( array_merge( $this->attributes, $this->data ), $atts ));

		return ob_get_clean();
	}

	public function output_options()
	{
		?>
		<div class="form-group">
			<label><?php _e( 'Placeholder', 'my-listing' ) ?></label>
			<input type="text" v-model="shortcode.attributes.placeholder">
		</div>

		<div class="form-group">
			<label><?php _e( 'Align', 'my-listing' ) ?></label>
			<select v-model="shortcode.attributes.align">
				<option value="left"><?php _e( 'Left', 'my-listing' ) ?></option>
				<option value="center"><?php _e( 'Center', 'my-listing' ) ?></option>
				<option value="right"><?php _e( 'Right', 'my-listing' ) ?></option>
			</select>
		</div>

		<div class="form-group">
			<label><?php _e( 'Style', 'my-listing' ) ?></label>
			<select v-model="shortcode.attributes.style">
				<option value="light"><?php _e( 'Light - Suitable for dark backgrounds', 'my-listing' ) ?></option>
				<option value="dark"><?php _e( 'Dark - Suitable for light backgrounds', 'my-listing' ) ?></option>
			</select>
		</div>
		<?php
	}
}

return new QuickSearch;