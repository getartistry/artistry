<?php
/**
 * Static mailer API.
 *
 * Used to display dynamic AW content in email template files.
 *
 * @class AW_Mailer_API
 */
class AW_Mailer_API {

	/** @var AutomateWoo\Mailer */
	static $mailer;

	/** @var AutomateWoo\Workflow*/
	static $workflow;


	/**
	 * @param AutomateWoo\Mailer $mailer
	 * @param AutomateWoo\Workflow $workflow
	 */
	static function setup( $mailer, $workflow ) {
		self::$mailer = $mailer;
		self::$workflow = $workflow;
	}


	static function cleanup() {
		self::$mailer = false;
		self::$workflow = false;
	}


	/**
	 * @return bool|string
	 */
	static function email() {
		if ( ! self::$mailer ) return false;
		return self::$mailer->email;
	}


	/**
	 * @return bool|string
	 */
	static function subject() {
		if ( ! self::$mailer ) return false;
		return self::$mailer->subject;
	}


	/**
	 * @return bool|string
	 */
	static function unsubscribe_url() {
		if ( ! self::$workflow ) return false;
		$customer = AutomateWoo\Customer_Factory::get_by_email( self::email() );
		return AutomateWoo\Emails::generate_unsubscribe_url( self::$workflow->get_id(), $customer );
	}


	/**
	 * @param WC_Product $product
	 * @param string $size
	 * @return array|false|string
	 */
	static function get_product_image( $product, $size = 'shop_catalog' ) {

		if ( $image_id = $product->get_image_id() ) {
			$image_url = wp_get_attachment_image_url( $image_id, $size );

			$image = '<img src="' . esc_url( $image_url ) . '" class="aw-product-image" alt="'. esc_attr( AutomateWoo\Compat\Product::get_name( $product ) ) .'">';
		}
		else {
			$image = wc_placeholder_img( $size );
		}

		return $image;
	}

}


