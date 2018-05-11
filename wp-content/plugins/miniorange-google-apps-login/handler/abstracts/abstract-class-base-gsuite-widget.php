<?php


/**
 * Class Mo_Gsuite_Base_Widget
 */
abstract class Mo_Gsuite_Base_Widget extends WP_Widget{

	/**
	 * @var Name of the widget
	 */
	public $widget_name;
	/**
	 * @var Description for widget
	 */
	public $widget_description;
	/**
	 * @var Widgets id
	 */
	public $widget_id;

	/**
	 * Mo_Gsuite_Base_Widget constructor.
	 * Will call parent constructor and do the formalities with the widget
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'init', array( $this, 'start_widget_session' ) );
		add_action( 'wp_logout', array( $this, 'end_widget_session' ) );

		$widget_ops = array(
			'description' => $this->widget_description,
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

	}

	/**
	 * @return mixed
	 */
	abstract function register_widget_styles();

	/**
	 * This function wil start the session for widget
	 */
	function start_widget_session(){
		Mo_GSuite_Utility::checkSession();
	}

	/**
	 * This function will destroy the widgets session.
	 */
	function end_widget_session(){
		Mo_GSuite_Utility::checkSession();
		session_destroy();
	}

	/**
	 * @param $args
	 * @param $instance
	 */
	public function widget_start($args, $instance){
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) ) {
			echo $args['before_title'] . $wid_title . $args['after_title'];
		}

	}

	/**
	 * @param $args
	 * @param $instance
	 */
	public function widget( $args, $instance ){

	}

	/**
	 * @param $args
	 */
	public function widget_end($args){
		echo $args['after_widget'];
	}

	/**
	 * @param $new_instance
	 * @param $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );

		return $instance;
	}

	/**
	 * Error messages if any
	 * @return mixed
	 */
	abstract public function error_message();
}
