<?php

namespace CASE27\Shortcodes;

/**
 * Icon Shortcode.
 */
class Icon {

	public $name = '27-icon',
		$title = '',
		$description = '',
	    $attributes = ['icon' => 'icon-add-circle-1'],
	    $data = ['pack' => 'font-awesome'];

	public function __construct()
	{
		$this->title = __( 'Icon', 'my-listing' );
		$this->description = __( 'Select an icon to include in your content.', 'my-listing' );

		add_shortcode($this->name, [$this, 'add_shortcode']);
	}

	public function add_shortcode($atts, $content = null)
	{
		$atts = shortcode_atts( $this->attributes, $atts );

		return c27()->get_icon_markup($atts['icon']);
	}

	public function output_options()
	{
		?>

		<div class="form-group">
			<label><?php _e( 'Select Icon', 'my-listing' ) ?> {{shortcode.attributes.icon}}</label>
			<iconpicker v-model="shortcode.attributes.icon"></iconpicker>
		</div>

		<?php
	}
}

return new Icon;