<?php
/**
 * Images template
 *
 * This template displays both the main images, and the thumbnails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

$default_variation_id = $this->get_default_variation_id();
$initial_product_id = ($default_variation_id) ? $default_variation_id : $product->get_id();
$initial_product_id = $this->get_selected_variation( $initial_product_id );

$images = $this->get_all_image_sizes( $initial_product_id );
$default_images = $this->get_all_image_sizes( $product->get_id() );

$product_settings = (array) get_post_meta( $product->get_id(), '_iconic_woothumbs', true );
$maintain_slide_index = isset( $product_settings['maintain_slide_index'] ) && $product_settings['maintain_slide_index'] != "" ? $product_settings['maintain_slide_index'] : "no";
$has_video = !empty( $product_settings['video_url'] ) ? "yes" : "no";

/**
 * Setup classes for all images wrap
 */

$classes = array(
    'iconic-woothumbs-all-images-wrap',
    sprintf('iconic-woothumbs-all-images-wrap--thumbnails-%s', $this->settings['navigation_thumbnails_position'])
);

if($default_variation_id == "" || $default_variation_id == $product->get_id()) {
    $classes[] = 'iconic-woothumbs-reset';
}

if( $this->settings['display_general_icons_hover'] ) {
    $classes[] = 'iconic-woothumbs-hover-icons';
}

if( $this->settings['display_general_icons_tooltips'] ) {
    $classes[] = 'iconic-woothumbs-tooltips-enabled';
}

if( $this->settings['zoom_general_enable'] ) {
    $classes[] = 'iconic-woothumbs-zoom-enabled';
}

if( is_rtl() ) {
    $classes[] = 'iconic-woothumbs-all-images-wrap--rtl';
}
?>

<?php do_action( 'iconic_woothumbs_before_all_images_wrap' ); ?>

<div class="<?php echo implode(' ', $classes); ?>" data-showing="<?php echo $initial_product_id; ?>" data-parentid="<?php echo $product->get_id(); ?>" data-default="<?php echo esc_attr( json_encode( $default_images ) ); ?>" data-slide-count="<?php echo count($images); ?>" data-maintain-slide-index="<?php echo $maintain_slide_index; ?>"
data-has-video="<?php echo $has_video; ?>" data-product-type="<?php echo $product->get_type(); ?>" dir="<?php echo is_rtl() ? "rtl" : "ltr"; ?>">

    <?php /* ?><div class="iconic-woothumbs-caption"></div><?php */ ?>

	<?php if( $this->settings['navigation_thumbnails_enable'] && ( $this->settings['navigation_thumbnails_position'] === "above" || $this->settings['navigation_thumbnails_position'] === "left" ) ) { include('loop-thumbnails.php'); } ?>

	<?php include('loop-images.php'); ?>

	<?php if( $this->settings['navigation_thumbnails_enable'] && ( $this->settings['navigation_thumbnails_position'] === "below" || $this->settings['navigation_thumbnails_position'] === "right" ) ) { include('loop-thumbnails.php'); } ?>

</div>

<?php do_action( 'iconic_woothumbs_after_all_images_wrap' ); ?>