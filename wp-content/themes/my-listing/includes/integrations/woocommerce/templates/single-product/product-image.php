<?php
/**
 * Single Product Image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'c27-woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

$gallery_images = $product->get_gallery_image_ids();

if ($post_thumbnail_id) {
	array_unshift($gallery_images, (int) $post_thumbnail_id);
}

$gallery_images = array_filter( array_map(function($id) {
	return $id ? ['item' => ['id' => (int) $id]] : null;
}, $gallery_images));
?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?> <?php echo esc_attr( 'c27-gallery-items-count--' . count($gallery_images) ) ?>">

	<?php if (!$gallery_images): ?>
		<div class="woocommerce-product-gallery__image--placeholder">
			<img src="<?php echo esc_url( wc_placeholder_img_src() ) ?>" alt="<?php _e( 'Awaiting product image', 'my-listing' ) ?>" class="wp-post-image">
		</div>
	<?php else: ?>
		<?php c27()->get_section('gallery-block', [
			'gallery_items' => $gallery_images,
			'items_per_row' => $columns,
			'items_per_row_mobile' => 2,
			'gallery_type' => 'carousel-with-preview',
			]) ?>
	<?php endif ?>

	</figure>
</div>
