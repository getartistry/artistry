<?php
/**
 * Admin Bundled Product view
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><div class="wc-bundled-item wc-metabox <?php echo $toggle; ?>" rel="<?php echo $loop; ?>">
	<h3>
		<strong class="item-title"><?php echo $title; ?></strong>
		<?php
			echo '' !== $item_availability ? '<span class="item-availability">' . $item_availability . '</span>' : '';
		?>
		<div class="handle">
			<?php
				echo false !== $item_id ? ( '<small class="item-id" title="' . __( 'Bundled item ID', 'woocommerce-product-bundles' ) . '">' . sprintf( _x( 'ID: %s', 'bundled product identifier', 'woocommerce-product-bundles' ), $item_id ) . '</small>' ) : '';
			?>
			<a href="#" class="remove_row delete"><?php echo __( 'Remove', 'woocommerce' ); ?></a>
			<div class="handlediv" title="<?php echo __( 'Click to toggle', 'woocommerce' ); ?>"></div>
		</div>
	</h3>
	<div class="item-data wc-metabox-content">
		<input type="hidden" name="bundle_data[<?php echo $loop; ?>][menu_order]" class="item_menu_order" value="<?php echo $loop; ?>" /><?php

		if ( false !== $item_id ) {
			?><input type="hidden" name="bundle_data[<?php echo $loop; ?>][item_id]" class="item_id" value="<?php echo $item_id; ?>" /><?php
		}

		?><input type="hidden" name="bundle_data[<?php echo $loop; ?>][product_id]" class="product_id" value="<?php echo $product_id; ?>" />

		<ul class="subsubsub"><?php

			/*--------------------------------*/
			/*  Tab menu items.               */
			/*--------------------------------*/

			$tab_loop = 0;

			foreach ( $tabs as $tab_values ) {

				$tab_id = $tab_values[ 'id' ];

				?><li><a href="#" data-tab="<?php echo $tab_id; ?>" class="<?php echo $tab_loop === 0 ? 'current' : ''; ?>"><?php
					echo $tab_values[ 'title' ];
				?></a></li><?php

				$tab_loop++;
			}

		?></ul><?php

		/*--------------------------------*/
		/*  Tab contents.                 */
		/*--------------------------------*/

		$tab_loop = 0;

		foreach ( $tabs as $tab_values ) {

			$tab_id = $tab_values[ 'id' ];

			?><div class="options_group options_group_<?php echo $tab_id; ?> <?php echo $tab_loop > 0 ? 'options_group_hidden' : ''; ?>"><?php
				/**
				 * 'woocommerce_bundled_product_admin_{$tab_id}_html' action.
				 */
				do_action( 'woocommerce_bundled_product_admin_' . $tab_id . '_html', $loop, $product_id, $item_data, $post_id );
			?></div><?php

			$tab_loop++;
		}

	?></div>
</div>
