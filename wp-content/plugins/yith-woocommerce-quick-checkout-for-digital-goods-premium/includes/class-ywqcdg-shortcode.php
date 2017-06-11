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

if ( ! class_exists( 'YWQCDG_Shortcode' ) ) {

	/**
	 * Implements shortcode for YWQCDG
	 *
	 * @class   YWQCDG_Shortcode
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 *
	 */
	class YWQCDG_Shortcode {

		private $page_has_shortcode = false;

		/**
		 * Single instance of the class
		 *
		 * @var \YWQCDG_Shortcode
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWQCDG_Shortcode
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

			add_shortcode( 'ywqcdg_shortcode', array( $this, 'set_quick_checkout_shortcode' ) );
			add_action( 'admin_action_ywqcdg_shortcode_panel', array( $this, 'add_shortcode_panel' ) );
			add_action( 'admin_init', array( $this, 'add_shortcode_button' ) );
			add_action( 'media_buttons_context', array( &$this, 'media_buttons_context' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'template_redirect', array( $this, 'add_to_cart' ), 20 );

		}

		/**
		 * Initializes CSS and javascript
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function admin_scripts() {

			global $pagenow;

			if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) {
				return;
			}

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'ywqcdg-shortcode', YWQCDG_ASSETS_URL . '/js/ywqcdg-shortcode' . $suffix . '.js', array( 'jquery' ), YWQCDG_VERSION );

			global $post_ID, $temp_ID;

			$query_args = array(
				'action'    => 'ywqcdg_shortcode_panel',
				'post_id'   => (int) ( 0 == $post_ID ? $temp_ID : $post_ID ),
				'KeepThis'  => true,
				'TB_iframe' => true
			);

			wp_localize_script( 'ywqcdg-shortcode', 'ywqcdg_shortcode', array(
				'lightbox_url'   => add_query_arg( $query_args, admin_url( 'admin.php' ) ),
				'lightbox_title' => __( 'Add YITH WooCommerce Quick Checkout for Digital Goods shortcode', 'yith-woocommerce-quick-checkout-for-digital-goods' ),

			) );

		}

		/**
		 * If page has a shortcode add the product to cart
		 *
		 * @since   1.0.2
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_to_cart() {

			global $post;

			if ( ! $post ) {
				return;
			}

			if ( has_shortcode( $post->post_content, 'ywqcdg_shortcode' ) ) {

				preg_match( '/\[ywqcdg_shortcode.*id=.(.*).\]/', $post->post_content, $id );
				$product_id = $id[1];

				if ( $product_id ) {

					$cart_items = WC()->cart->get_cart();

					foreach ( $cart_items as $item ) {

						if ( $product_id != $item['product_id'] ) {

							WC()->cart->empty_cart();
							break;

						}

					}

					if ( WC()->cart->is_empty() ) {

						WC()->cart->add_to_cart( $product_id );

					}

				}

			}

		}

		/**
		 * Set quick checkout shortcode
		 *
		 * @since   1.0.0
		 *
		 * @param   $atts
		 *
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function set_quick_checkout_shortcode( $atts ) {

			$quick_checkout = '';

			//check if one shortcode has already been added to the page
			if ( $this->page_has_shortcode != true ) {

				$this->page_has_shortcode = true;

				shortcode_atts( array( 'id' => '', ), $atts );

				add_filter( 'widget_text', 'shortcode_unautop' );
				add_filter( 'widget_text', 'do_shortcode' );
				add_filter( 'woocommerce_is_checkout', '__return_true' );

				if ( isset( $atts['id'] ) ) {

					$ids        = explode( ',', $atts['id'] );
					$product_id = trim( array_shift( $ids ) );

					ob_start();
					YITH_WQCDG()->get_quick_checkout( $product_id );
					$quick_checkout = ob_get_contents();
					ob_end_clean();

				}

			}

			return $quick_checkout;

		}

		/**
		 * Get shortcode panel
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_shortcode_panel() {

			global $name_tab;
			include( YWQCDG_TEMPLATE_PATH . '/admin/lightbox.php' );

		}

		/**
		 * Add shortcode button to TinyMCE editor, adding filter on mce_external_plugins
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_shortcode_button() {

			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {

				return;

			}

			if ( get_user_option( 'rich_editing' ) == 'true' ) {

				add_filter( 'mce_external_plugins', array( &$this, 'add_shortcode_tinymce_plugin' ) );
				add_filter( 'mce_buttons', array( &$this, 'register_shortcode_button' ) );

			}

		}

		/**
		 * Add a script to TinyMCE script list
		 *
		 * @since   1.0.0
		 *
		 * @param   $plugin_array
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function add_shortcode_tinymce_plugin( $plugin_array ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$plugin_array['ywqcdg_shortcode'] = YWQCDG_ASSETS_URL . '/js/ywqcdg-tinymce' . $suffix . '.js';

			return $plugin_array;

		}

		/**
		 * Make TinyMCE know a new button was included in its toolbar
		 *
		 * @since   1.0.0
		 *
		 * @param   $buttons
		 *
		 * @return  array()
		 * @author  Alberto Ruggiero
		 */
		public function register_shortcode_button( $buttons ) {

			array_push( $buttons, "|", "ywqcdg_shortcode" );

			return $buttons;

		}

		/**
		 * The markup of shortcode
		 *
		 * @since   1.0.0
		 *
		 * @param   $context
		 *
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function media_buttons_context( $context ) {

			ob_start();

			?>

			<a id="ywqcdg_shortcode" style="display:none" href="#" class="hide-if-no-js" title="<?php _e( 'Add YITH WooCommerce Quick Checkout for Digital Goods shortcode', 'yith-woocommerce-quick-checkout-for-digital-goods' ) ?>"></a>

			<?php

			$out = ob_get_clean();

			return $context . $out;

		}

	}

	/**
	 * Unique access to instance of YWQCDG_Shortcode class
	 *
	 * @return \YWQCDG_Shortcode
	 */
	function YWQCDG_Shortcode() {
		return YWQCDG_Shortcode::get_instance();
	}

	new YWQCDG_Shortcode();

}