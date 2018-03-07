<?php

namespace AutomateWoo\Fields;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Product
 */
class Product extends Field {

	protected $name = 'product';

	protected $type = 'product';

	public $allow_variations = false;


	function __construct() {
		parent::__construct();
		$this->set_title( __( 'Product', 'automatewoo' ) );
		$this->classes[] = 'wc-product-search';
	}


	/**
	 * @param $value
	 */
	function render( $value ) {

		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$this->render_legacy( $value );
			return;
		}

		$ajax_action = $this->allow_variations ? 'woocommerce_json_search_products_and_variations' : 'woocommerce_json_search_products';
		$product = $value ? wc_get_product( absint( $value ) ) : false;

		?>

		<select class="<?php echo $this->get_classes() ?>"
		       name="<?php echo $this->get_full_name(); ?>"
		       data-placeholder="<?php _e( 'Search for a product&hellip;', 'automatewoo' ); ?>"
		       data-action="<?php echo $ajax_action ?>">
			<?php
				if ( is_object( $product ) ) {
					echo '<option value="' . esc_attr( Compat\Product::get_id( $product ) ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
				}
			?>
		</select>

		<script type="text/javascript">
			jQuery( 'body' ).trigger( 'wc-enhanced-select-init' );
		</script>

	<?php
	}


	function render_legacy( $value ) {

		$ajax_action = $this->allow_variations ? 'woocommerce_json_search_products_and_variations' : 'woocommerce_json_search_products';

		?>

		<input type="hidden" class="<?php echo $this->get_classes() ?>"
				 name="<?php echo $this->get_full_name(); ?>"
				 data-placeholder="<?php _e( 'Search for a product&hellip;', 'automatewoo' ); ?>"
				 data-action="<?php echo $ajax_action ?>"
				 data-selected="<?php

				 $product_id = absint( $value );
				 if ( $product_id && $product = wc_get_product( $product_id ) ) {
					 echo htmlspecialchars( $product->get_formatted_name() );
				 }

				 ?>"
				 value="<?php echo ( $product_id ? $product_id : '' ); ?>" />

		<script type="text/javascript">
			jQuery( 'body' ).trigger( 'wc-enhanced-select-init' );
		</script>

		<?php
	}

}
