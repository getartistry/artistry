<?php

/**
 * Register Shortcodes
 */
class CASE27_Shortcodes {

	protected static $_instance = null;

	private $all = [];

	public static function instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/*
	 * Register shortcodes by providing either the path to the shortcode file,
	 * or an array of paths to bulk register.
	 */
	public function register( $shortcodes )
	{
		if ( is_string( $shortcodes ) ) $shortcodes = [ $shortcodes ];

		foreach ((array) $shortcodes as $shortcode) {
			$this->all[] = require_once $shortcode;
		}
	}


	/*
	 * Get all registered shortcodes.
	 */
	public function all()
	{
		return $this->all;
	}


	/*
	 * Get all registered shortcodes, encoded to be safely used in JavaScript.
	 */
	public function all_encoded()
	{
		$shortcode_data = [];

		foreach ((array) $this->all as $shortcode) {
			$shortcode_data[] = [
				'name'        => $shortcode->name,
				'title'       => $shortcode->title,
				'data'        => isset($shortcode->data) ? $shortcode->data : [],
				'content'     => isset($shortcode->content) ? $shortcode->content : null,
				'attributes'  => isset($shortcode->attributes) ? $shortcode->attributes : [],
				'description' => isset($shortcode->description) ? $shortcode->description : '',
			];
		}

		return htmlspecialchars( json_encode( $shortcode_data ), ENT_QUOTES, 'UTF-8' );
	}
}

// Init Shortcodes.
do_action( 'case27_shortcodes_init', CASE27_Shortcodes::instance() );