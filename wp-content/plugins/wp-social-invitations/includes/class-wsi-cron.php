<?php

/**
 * Class that handle all cron related tasks
 *
 * @since      2.5.0
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Cron {

	/**
	 * Queue class
	 * @var Wsi_Queue
	 */
	protected $queue;

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5
	 * @var      string    $wsi       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wsi, $version ) {

		$this->wsi = $wsi;
		$this->version = $version;
		$this->queue   = new Wsi_Queue( $wsi, $version );

	}


	/**
	 * Add our own cron schedule method
	 * @param $schedules
	 * @return mixed
	 */
	public function add_cron_schedule( $schedules ) {
		// add a 'every_min' schedule to the existing set
		$schedules['wsi_one_min'] = array(
			'interval' => 180,
			'display' => __( 'Once every 3 minutes','wsi')
		);
		return $schedules;
	}

	/**
	 * Run Cron Job
	 */
	public function run() {
		$this->queue->run();
	}


	/**
	 * Check if manual cron is setup on server instead of Wordpress one and run it
	 * @since 2.5.0
	 * @return void
	 */
	public function server_cron() {
		if( !isset($_REQUEST['wsi_server_cron']) || $_REQUEST['wsi_server_cron'] != WSI_CRON_TOKEN )
			return;

		define('DOING_CRON',true);
		$this->run();
		define('DOING_CRON',false);
		die();

	}
}