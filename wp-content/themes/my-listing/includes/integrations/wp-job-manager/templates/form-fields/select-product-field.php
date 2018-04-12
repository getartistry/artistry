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
?>

<select name="<?php echo esc_attr( (isset( $field['name'] ) ? $field['name'] : $key) ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> class="form-control custom-select">
	<?php foreach ((array) $products as $product ) : ?>
		<option
			value="<?php echo esc_attr( $product->ID ); ?>"
			<?php if (isset($field['value']) && $product->ID == $field['value']) echo 'selected="selected"' ?>
		>
		<?php echo esc_html( $product->post_title ); ?>
		</option>
	<?php endforeach; ?>
</select>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>