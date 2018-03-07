<?php

namespace AutomateWoo\Admin\Controllers;

use AutomateWoo;
use AutomateWoo\Time_Helper;
use AutomateWoo\Dashboard_Widget;
use AutomateWoo\Clean;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard
 */
class Dashboard extends Base {

	/** @var array */
	private $widgets;

	/** @var array */
	private $logs;

	/** @var array */
	private $carts;

	/** @var array */
	private $guests;

	/** @var array */
	private $unsubscribes;

	/** @var array */
	private $conversions;

	/** @var int */
	private $guests_count;

	/** @var int */
	private $carts_count;

	/** @var int */
	private $queued_count;


	function handle() {

		wp_enqueue_script( 'automatewoo-dashboard' );

		$this->maybe_set_date_cookie();

		$widgets = $this->get_widgets();
		$date_arg = $this->get_date_arg();
		$date_range = $this->get_date_range();
		$date_tabs = [
			'90days' => __( '90 days', 'automatewoo' ),
			'30days' => __( '30 days', 'automatewoo' ),
			'14days' => __( '14 days', 'automatewoo' ),
			'7days' => __( '7 days', 'automatewoo' )
		];

		foreach ( $widgets as $i => $widget ) {
			$widget->set_date_range( $date_range['from'], $date_range['to'] );
			if ( ! $widget->display ) {
				unset( $widgets[$i] );
			}
		}

		$this->output_view( 'page-dashboard', [
			'widgets' => $widgets,
			'date_text' => $date_tabs[$date_arg],
			'date_current' => $this->get_date_arg(),
			'date_tabs' => $date_tabs
		]);
	}


	/**
	 * @return Dashboard_Widget[]
	 */
	function get_widgets() {

		if ( ! isset( $this->widgets ) ) {

			$path = AW()->path( '/admin/dashboard-widgets/' );

			$includes = [];

			$includes[] = $path . 'chart-workflows-run.php';
			$includes[] = $path . 'chart-conversions.php';
			$includes[] = $path . 'chart-email.php';

			$includes = apply_filters( 'automatewoo/dashboard/chart_widgets', $includes );

			$includes[] = $path . 'key-figures.php';
			$includes[] = $path . 'workflows.php';
			$includes[] = $path . 'logs.php';
			$includes[] = $path . 'queue.php';

			$includes = apply_filters( 'automatewoo/dashboard/widgets', $includes );

			include_once $path . 'abstract.php';
			include_once $path . 'chart-abstract.php';
			include_once $path . 'deprecated.php';

			foreach ( $includes as $include ) {
				/** @var Dashboard_Widget $class */
				$class = include_once $include;
				$class->controller = $this;
				$this->widgets[ $class->id ] = $class;
			}
		}

		return $this->widgets;
	}


	/**
	 * @return string
	 */
	function get_date_arg() {

		$cookie_name = 'automatewoo_dashboard_date';

		if ( ! aw_request( 'date' ) && isset( $_COOKIE[ $cookie_name ] ) ) {
			return Clean::string( $_COOKIE[ $cookie_name ] );
		}

		if ( aw_request( 'date' ) ) {
			$date = Clean::string( aw_request( 'date' ) );
			return $date;
		}

		return '30days';
	}


	function maybe_set_date_cookie() {
		if ( aw_request( 'date' ) ) {
			$date = Clean::string( aw_request( 'date' ) );
			if ( ! headers_sent() ) wc_setcookie( 'automatewoo_dashboard_date', $date, time() + MONTH_IN_SECONDS * 2 );
		}
	}


	/**
	 * @return array
	 */
	function get_date_range() {

		$range = $this->get_date_arg();

		$from = new \DateTime();
		$to = new \DateTime();

		switch ( $range ) {
			case '14days':
				$from->modify( "-14 days" );
				break;
			case '7days':
				$from->modify( "-7 days" );
				break;
			case '30days':
				$from->modify( "-30 days" );
				break;
			case '90days':
				$from->modify( "-90 days" );
				break;
		}

		return apply_filters( 'automatewoo/dashboard/date_range', [
			'from' => $from,
			'to' => $to
		]);
	}


	/**
	 * @return AutomateWoo\Log[]
	 */
	function get_logs() {
		if ( ! isset( $this->logs ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Log_Query();
			$query->where( 'date', $date['from'], '>' );
			$query->where( 'date', $date['to'], '<' );

			$this->logs = $query->get_results();
		}

		return $this->logs;
	}


	/**
	 * @return AutomateWoo\Cart[]
	 */
	function get_carts() {
		if ( ! isset( $this->carts ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Cart_Query();
			$query->where( 'created', $date['from'], '>' );
			$query->where( 'created', $date['to'], '<' );

			$this->carts = $query->get_results();
		}

		return $this->carts;
	}


	/**
	 * @return int
	 */
	function get_carts_count() {
		if ( ! isset( $this->carts_count ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Cart_Query();
			$query->where( 'created', $date['from'], '>' );
			$query->where( 'created', $date['to'], '<' );

			$this->carts_count = $query->get_count();
		}

		return $this->carts_count;
	}


	/**
	 * @return AutomateWoo\Guest[]
	 */
	function get_guests() {
		if ( ! isset( $this->guests ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Guest_Query();
			$query->where( 'created', $date['from'], '>' );
			$query->where( 'created', $date['to'], '<' );

			$this->guests = $query->get_results();
		}

		return $this->guests;
	}


	/**
	 * @return int
	 */
	function get_guests_count() {
		if ( ! isset( $this->guests_count ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Guest_Query();
			$query->where( 'created', $date['from'], '>' );
			$query->where( 'created', $date['to'], '<' );

			$this->guests_count = $query->get_count();
		}

		return $this->guests_count;
	}


	/**
	 * @return int
	 */
	function get_queued_count() {
		if ( ! isset( $this->queued_count ) ) {

			$date = $this->get_date_range();

			$query = ( new AutomateWoo\Queue_Query() );
			$query->where( 'created', $date['from'], '>' );
			$query->where( 'created', $date['to'], '<' );

			$this->queued_count = $query->get_count();
		}

		return $this->queued_count;
	}


	/**
	 * @return AutomateWoo\Unsubscribe[]
	 */
	function get_unsubscribed_customers() {
		if ( ! isset( $this->unsubscribes ) ) {

			$date = $this->get_date_range();

			$query = new AutomateWoo\Customer_Query();
			$query->where('unsubscribed', true );
			$query->where( 'unsubscribed_date', $date['from'], '>' );
			$query->where( 'unsubscribed_date', $date['to'], '<' );

			$this->unsubscribes = $query->get_results();
		}

		return $this->unsubscribes;
	}


	/**
	 * @return \WC_Order[]
	 */
	function get_conversions() {
		if ( ! isset( $this->conversions ) ) {

			$date = $this->get_date_range();

			Time_Helper::convert_from_gmt( $date['from'] );
			Time_Helper::convert_from_gmt( $date['to'] );

			$query = new \WP_Query([
				'post_type' => 'shop_order',
				'post_status' => [ 'wc-processing', 'wc-completed' ],
				'posts_per_page' => -1,
				'fields' => 'ids',
				'no_found_rows' => true,
				'meta_query' => [
					[
						'key' => '_aw_conversion',
						'compare' => 'EXISTS',
					]
				],
				'date_query' => [
					[
						'column' => 'post_date',
						'after' => $date['from']->format('Y-m-d H:i:s')
					],
					[
						'column' => 'post_date',
						'before' => $date['to']->format('Y-m-d H:i:s')
					]
				]
			]);

			$this->conversions = array_map( 'wc_get_order', $query->posts );
		}

		return $this->conversions;
	}

}

return new Dashboard();
