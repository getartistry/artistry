<?php
/**
 * Register Api actions for Ajax calls.
 */

class CASE27_Ajax {

	protected static $_instance = null;

	/**
	 * The list of possible ajax actions.
	 * Each action will require a method with the same name.
	 */
	protected $actions = [
		'get_icon_packs',
	];

	public static function instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public function __construct()
	{
		$this->register_actions();
	}


	public function register_action($action)
	{
		if (!in_array($action, $this->actions)) {
			$this->actions[] = $action;
		}
	}


	/**
	 * Register actions.
	 */
	public function register_actions()
	{
		foreach ($this->actions as $action) {
			add_action( "wp_ajax_$action", array($this, $action) );
			add_action( "wp_ajax_nopriv_$action", array($this, $action) );
		}
	}

	/*
     * Encode given data to JSON and output it.
     */
	public function json($data)
	{
		echo json_encode($data); die;
	}

	/*
 	 * Output given data as HTML.
     */
	public function html($data)
	{
		echo $data; die;
	}

	/*
 	 * THE LIST OF ACTION CALLBACKS.
 	 * EACH ACTION SHOULD BE REGISTERED IN THE 'register_actions' array,
 	 * AND IT SHOULD HAVE A METHOD WITH THE SAME NAME.
	 *
 	 * NAME SHOULD START WITH THE REQUEST METHOD,
 	 * IN THIS CASE EITHER 'get_' OR 'post_'.
     */

	public function get_icon_packs()
	{
		if (!is_user_logged_in()) {
			return;
		}

		$font_awesome_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/font-awesome.php';
		$material_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/material-icons.php';

		return $this->json([
			'font-awesome' => array_map(function($icon) { return "fa {$icon}"; }, array_values($font_awesome_icons)),
			'material-icons' => array_map(function($icon) { return "mi {$icon}"; }, array_values($material_icons)),
			'theme-icons' => array_values(require CASE27_INTEGRATIONS_DIR . '/27collective/icons/theme-icons.php'),
			]);
	}

}

CASE27_Ajax::instance();
