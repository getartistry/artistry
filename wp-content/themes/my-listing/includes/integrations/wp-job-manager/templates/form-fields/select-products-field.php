<?php if (!is_user_logged_in()) : ?>
	<br>
	<em><small><?php _e( 'You must be logged in to add products.', 'my-listing' ) ?></small></em>
<?php return; endif ?>

<?php
$tax_query = [];
if ( ! empty( $field['product-type'] ) ) {
	$tax_query[] = [
           'taxonomy' => 'product_type',
           'field' => 'slug',
           'terms' => $field['product-type'],
           'operator' => 'IN',
    ];
}

$products = get_posts([
	'post_type' => 'product',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'author' => get_current_user_id(),
	'tax_query' => $tax_query,
	]);

// FEATURE: Move selected products to the top so they appear in correct order on select2 multiselect.
// Similar to what was done in the term-multiselect fields.
$listing_id = ! empty( $_REQUEST[ 'job_id' ] ) ? absint( $_REQUEST[ 'job_id' ] ) : 0;

$used_products = [];
if ( $listing_id ) {
	$used_products = array_filter( (array) array_map('absint', (array) get_post_meta($listing_id, '_select_products', true) ) );
}
?>
<input type="hidden" name="c27_<?php echo esc_attr( (isset( $field['name'] ) ? $field['name'] : $key) ) ?>_values" value="<?php echo htmlspecialchars(json_encode($used_products), ENT_QUOTES, 'UTF-8') ?>">

<select name="<?php echo esc_attr( (isset( $field['name'] ) ? $field['name'] : $key) . '[]' ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> multiple="multiple" class="form-control custom-select">
	<?php foreach ((array) $products as $product ) : ?>
		<option
		value="<?php echo esc_attr( $product->ID ); ?>"
		<?php if (isset($field['value']) && in_array($product->ID, (array) $field['value'])) echo 'selected="selected"' ?>
		>
		<?php echo esc_html( $product->post_title ); ?>
		</option>
	<?php endforeach; ?>
</select>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>