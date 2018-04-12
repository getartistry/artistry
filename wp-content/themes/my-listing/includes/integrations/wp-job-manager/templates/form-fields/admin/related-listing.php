<?php

global $thepostid;

if ( ! isset( $field['value'] ) ) {
	$field['value'] = get_post_meta( $thepostid, $key, true );
}
if ( ! empty( $field['name'] ) ) {
	$name = $field['name'];
} else {
	$name = $key;
}
if ( ! empty( $field['classes'] ) ) {
	$classes = implode( ' ', is_array( $field['classes'] ) ? $field['classes'] : array( $field['classes'] ) );
} else {
	$classes = '';
}

$args = [
	'post_type' => 'job_listing',
	'meta_key' => '_case27_listing_type',
	'meta_value' => $field['listing_type'],
	'posts_per_page' => -1,
];

?>

<p class="form-field">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>

	<select name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> class="custom-select">
		<option value=""><?php _e( 'Select Listing', 'my-listing' ) ?></option>
		<?php foreach ((array) get_posts($args) as $listing ) : ?>
			<option
			value="<?php echo esc_attr( $listing->ID ); ?>"
			<?php if (isset($field['value']) && $listing->ID == $field['value']) echo 'selected="selected"' ?>
			>
			<?php echo esc_html( $listing->post_title ); ?>
			</option>
		<?php endforeach; ?>

	</select>
</p>