<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Implements a custom select in YWQCDG plugin admin tab
 *
 * @class   YWQCDG_Select
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWQCDG_Select {

	/**
	 * Single instance of the class
	 *
	 * @var \YWQCDG_Select
	 * @since 1.0.0
	 */
	protected static $instance;

	/**
	 * Returns single instance of the class
	 *
	 * @return \YWQCDG_Select
	 * @since 1.0.0
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self( $_REQUEST );

		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since   1.0.0
	 * @return  mixed
	 * @author  Alberto Ruggiero
	 */
	public function __construct() {

		add_action( 'woocommerce_admin_field_ywqcdg-select', array( $this, 'output' ) );

	}

	/**
	 * Implements a custom select in YWQCDG plugin admin tab
	 *
	 * @since   1.0.0
	 *
	 * @param   $option
	 *
	 * @return  void
	 * @author  Alberto Ruggiero
	 */
	public function output( $option ) {

		$option_value  = WC_Admin_Settings::get_option( $option['id'], $option['default'] );
		$active_fields = ( $option_value ) ? $option_value : array();

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_html( $option['title'] ); ?></label>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $option['type'] ) ?>">

				<span class="description"><?php echo ( $option['desc'] ) ? $option['desc'] : ''; ?></span>
				<br />
				<select multiple="multiple" id="<?php echo esc_attr( $option['id'] ); ?>" name="<?php echo esc_attr( $option['id'] ); ?>[]" data-placeholder="<?php esc_attr_e( 'Choose fields&hellip;', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?>" title="<?php esc_attr_e( 'Fields', 'yith-woocommerce-quick-checkout-for-digital-goods' ) ?>" class="wc-enhanced-select">

					<?php if ( ! empty( $option['options'] ) ) : ?>

						<?php foreach ( $option['options'] as $key => $field ) : ?>

							<option value="<?php echo esc_attr( $key ) ?>" <?php echo selected( in_array( $key, $active_fields ), true, false ) ?> > <?php echo $field ?> </option>

						<?php endforeach; ?>

					<?php endif; ?>

				</select>
				<br />
				<a class="select_all button" href="#"><?php _e( 'Select all', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></a>
				<a class="select_none button" href="#"><?php _e( 'Deselect all', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></a>

			</td>
		</tr>
		<?php
	}

}

/**
 * Unique access to instance of YWQCDG_Select class
 *
 * @return \YWQCDG_Select
 */
function YWQCDG_Select() {

	return YWQCDG_Select::get_instance();

}

new YWQCDG_Select();