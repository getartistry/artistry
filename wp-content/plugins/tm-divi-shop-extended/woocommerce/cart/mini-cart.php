<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce;
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>
<div class="tm-mini-cart">
	<div class="cart-toggler">
		<a href="<?php echo wc_get_cart_url(); ?>">
			<span class="mini-cart-link">
				<span class="cart-quantity"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'sirol'), $woocommerce->cart->cart_contents_count);?></span>
				<span class="cart-title"><?php esc_html_e('shopping cart', 'sirol');?> 
					<span class="cart-item"><?php esc_html_e('item', 'sirol');?></span>
				</span>
			</span>
			<span class="cart-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
		</a>
	</div>
	<div class="mini_cart_content">
		<div class="mini_cart_inner">
			<div class="mini_cart_arrow"></div>
			<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>
				<ul class="cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

							$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

							?>
							<li id="mcitem-<?php echo esc_attr($cart_item_key); ?>">
								<a class="product-image" href="<?php echo get_permalink( $product_id ); ?>">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); ?>
								</a>
								<div class="product-details">
									
									<a class="product-name" href="<?php echo esc_url(get_permalink( $product_id )); ?>"><?php echo esc_html($product_name); ?></a>
									<?php echo WC()->cart->get_item_data( $cart_item ); ?>
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s', $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
									<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" onclick="roadMiniCartRemove(\'%s\', \'%s\');return false;" class="remove" title="%s"><i class="fa fa-trash-o"></i></a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), $cart_item_key, esc_html__( 'Remove this item', 'sirol' ) ), $cart_item_key ); ?>
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul><!-- end product list -->
			<?php else: ?>
				<ul class="cart_empty <?php echo esc_attr($args['list_class']); ?>">
					<li><?php esc_html_e( 'You have no items in your shopping cart', 'sirol' ); ?></li>
					<li class="total"><?php esc_html_e( 'Subtotal', 'sirol' ); ?>: <?php echo WC()->cart->get_cart_subtotal(); ?></li>
				</ul>
			<?php endif; ?>

			<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

				<p class="total"><?php esc_html_e( 'Subtotal', 'sirol' ); ?>: <?php echo WC()->cart->get_cart_subtotal(); ?></p>

				<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

				<p class="buttons">
					<a href="<?php echo wc_get_cart_url(); ?>" class="button wc-forward"><?php esc_html_e( 'View Cart', 'sirol' ); ?></a>
					<a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout wc-forward"><?php esc_html_e( 'Checkout', 'sirol' ); ?></a>
				</p>
			<?php endif; ?>
		</div>
		<div class="loading"></div>
	</div>
</div>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>