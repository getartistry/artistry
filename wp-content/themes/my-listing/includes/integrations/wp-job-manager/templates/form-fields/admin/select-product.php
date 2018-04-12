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
	'tax_query' => $tax_query,
	'author' => get_post_field( 'post_author', $thepostid ),
	]);
?>

<p class="form-field">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>

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
</p>