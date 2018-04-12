<?php if (!is_user_logged_in()) : ?>
	<br>
	<em><small><?php _e( 'You must be logged in to add related listings.', 'my-listing' ) ?></em></small>
<?php return; endif ?>

<?php
$args = [
	'post_type' => 'job_listing',
	'meta_key' => '_case27_listing_type',
	'meta_value' => ! empty( $field['listing_type'] ) ? $field['listing_type'] : '',
	'posts_per_page' => -1,
	'author' => get_current_user_id(),
];

?>

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
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>