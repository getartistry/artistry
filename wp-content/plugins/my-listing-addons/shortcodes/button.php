<?php

namespace CASE27\Shortcodes;

/**
 * Button shortcode.
 */
class Button {

	public $name = '27-button',
		$title = '',
		$description = '',
	    $attributes = [
			'href' => '#',
			'style' => 1,
			'width' => '',
			'size' => '',
			'animated' => 'no',
	    ];

	public function __construct()
	{
		$this->title = __( 'Button', 'my-listing' );
		$this->description = __( 'Generate a button.', 'my-listing' );

		add_shortcode($this->name, [$this, 'add_shortcode']);
	}

	public function add_shortcode($atts, $content = null)
	{
		$atts = shortcode_atts( $this->attributes, $atts );

		return do_shortcode( sprintf(
				'<a href="%2$s" class="buttons button-%3$s %4$s %5$s %6$s">%1$s %7$s</a>',
				$content, esc_url( $atts['href'] ), esc_attr( $atts['style'] ), $atts['width'], $atts['size'],
				$atts['animated'] == 'yes' ? 'button-animated' : '', $atts['animated'] == 'yes' ? c27()->get_icon_markup('material-icons://keyboard_arrow_right') : ''
			));
	}

	public function output_options()
	{
		?>
			<div class="form-group">
				<label><?php _e( 'Content', 'my-listing' ) ?></label>
				<textarea v-model="shortcode.content"></textarea>
			</div>

			<div class="form-group">
				<label><?php _e( 'Link to (href)', 'my-listing' ) ?></label>
				<input type="text" v-model="shortcode.attributes.href">
			</div>

			<div class="form-group">
				<label><?php _e( 'Style', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.style">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Width', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.width">
					<option value=""><?php _e( 'Auto', 'my-listing' ) ?></option>
					<option value="full-width"><?php _e( 'Full Width', 'my-listing' ) ?></option>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Size', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.size">
					<option value=""><?php _e( 'Normal', 'my-listing' ) ?></option>
					<option value="medium"><?php _e( 'Medium', 'my-listing' ) ?></option>
					<option value="small"><?php _e( 'Small', 'my-listing' ) ?></option>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Animated', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.animated">
					<option value="yes"><?php _e( 'Yes', 'my-listing' ) ?></option>
					<option value="no"><?php _e( 'No', 'my-listing' ) ?></option>
				</select>
			</div>
		<?php
	}
}

return new Button;
