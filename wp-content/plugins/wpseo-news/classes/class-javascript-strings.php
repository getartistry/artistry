<?php

class WPSEO_News_Javascript_Strings {
	private static $strings = null;

	private static function fill() {
		self::$strings = array(
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'choose_image' => __( 'Choose image.', 'wordpress-seo-news' ),
		);
	}

	public static function strings() {
		if ( self::$strings === null ) {
			self::fill();
		}

		return self::$strings;
	}
}
