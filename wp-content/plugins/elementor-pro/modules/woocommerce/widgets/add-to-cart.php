<?php
namespace ElementorPro\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Add_To_Cart extends Widget_Button {

	public function get_name() {
		return 'wc-add-to-cart';
	}

	public function get_title() {
		return __( 'Woo - Add To Cart', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Product', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product', 'elementor-pro' ),
				'type' => Module::QUERY_CONTROL_ID,
				'post_type' => '',
				'options' => [],
				'label_block' => true,
				'filter_type' => 'by_id',
				'object_type' => [ 'product' ],
			]
		);

		$this->end_controls_section();

		parent::_register_controls();

		$this->update_control(
			'link',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => [
					'url' => '',
				],
			]
		);

		$this->update_control(
			'text',
			[
				'default' => __( 'Add to Cart', 'elementor-pro' ),
				'placeholder' => __( 'Add to Cart', 'elementor-pro' ),
			]
		);

		$this->update_control(
			'icon',
			[
				'default' => 'shopping-cart',
			]
		);
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! empty( $settings['product_id'] ) ) {
			$product_data = get_post( $settings['product_id'] );
		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = __( 'Please set the product', 'elementor-pro' );
		}

		$product = ! empty( $product_data ) && in_array( $product_data->post_type, [ 'product', 'product_variation' ] ) ? wc_setup_product_data( $product_data ) : false;

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_id = $product->get_id();
				$product_type = $product->get_type();
			} else {
				$product_id = $product->id;
				$product_type = $product->product_type;
			}

			$class = implode( ' ', array_filter( [
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			] ) );

			$this->add_render_attribute( 'button', [
					'rel' => 'nofollow',
					'href' => $product->add_to_cart_url(),
					'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product_id,
					'class' => $class,
				]
			);
		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = __( 'Please set a valid product', 'elementor-pro' );
		}

		$this->set_settings( $settings );

		parent::render();
	}
}
