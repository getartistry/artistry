<?php

namespace CASE27\Shortcodes;

/**
 * Categories Shortcode.
 */
class Categories {

	public $name = '27-categories',
		$title = '',
		$description = '',
	    $content = null,
	    $attributes = [
		    'skin' => 'transparent',
		    'ids' => [],
			'align' => 'center',
	    ];

	public function __construct()
	{
		$this->title = __( 'Listing Categories', 'my-listing' );
		$this->description = __( 'A list of categories widget suited for featured sections.', 'my-listing' );

		add_shortcode($this->name, [$this, 'add_shortcode']);
	}

	public function add_shortcode($atts, $content = null)
	{
		ob_start();

		c27()->get_partial('categories', shortcode_atts( $this->attributes, $atts ));

		return ob_get_clean();
	}

	public function output_options()
	{
		$categories = c27()->get_terms_dropdown_array([
					'taxonomy' => 'job_listing_category',
					'hide_empty' => false,
					]);
		?>

			<div class="form-group">
				<label><?php _e( 'Skin', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.skin">
					<option value="default"><?php _e( 'Default', 'my-listing' ) ?></option>
					<option value="transparent"><?php _e( 'transparent', 'my-listing' ) ?></option>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Categories to show', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.ids" multiple="multiple">
					<?php foreach ($categories as $id => $name): ?>
						<option value="<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $name ) ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Align', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.align">
					<option value="left"><?php _e( 'Left', 'my-listing' ) ?></option>
					<option value="center"><?php _e( 'Center', 'my-listing' ) ?></option>
					<option value="right"><?php _e( 'Right', 'my-listing' ) ?></option>
				</select>
			</div>

		<?php
	}
}

return new Categories;