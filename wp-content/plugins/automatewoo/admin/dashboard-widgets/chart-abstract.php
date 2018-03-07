<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Chart
 */
abstract class Dashboard_Widget_Chart extends Dashboard_Widget {

	/** @var bool */
	public $is_currency = false;

	/** @var array */
	private $data;


	/**
	 * @return array
	 */
	abstract function load_data();


	/**
	 * @return array
	 */
	function get_data() {
		if ( ! isset( $this->data ) ) {
			$this->data = $this->load_data();
		}
		return $this->data;
	}


	/**
	 * @return array
	 */
	function get_params() {

		$params = [
			'interval' => $this->get_interval(),
			'is_currency' => $this->is_currency
		];

		return $params;
	}



	function render_js() {
		?>
		<script type="text/javascript">
			jQuery(function(){
				var data = jQuery.parseJSON( '<?php echo json_encode( $this->get_data() ) ?>' );
				var params = jQuery.parseJSON( '<?php echo json_encode( $this->get_params() ) ?>' );
				AW.Dashboard.drawGraph( 'automatewoo-dashboard-<?php echo $this->get_id() ?>', data, params );
			});
		</script>
		<?php
	}


	/**
	 * @param  array $data array of your data
	 * @param  string $date_key key for the 'date' field. e.g. 'post_date'
	 * @param  string $data_key key for the data you are charting
	 * @param  int $interval
	 * @param  string $group_by
	 * @return array
	 */
	function prepare_chart_data( $data, $date_key, $data_key, $interval, $group_by ) {

		$prepared_data = [];
		$start_date = strtotime( get_date_from_gmt( $this->date_from->format( Format::MYSQL ) ) );

		// Ensure all days (or months) have values first in this range
		for ( $i = 0; $i <= $interval; $i ++ ) {
			switch ( $group_by ) {
				case 'day' :
					$time = strtotime( date( 'Ymd', strtotime( "+{$i} DAY", $start_date ) ) ) . '000';
					break;
				case 'month' :
				default :
					$time = strtotime( date( 'Ym', strtotime( "+{$i} MONTH", $start_date ) ) . '01' ) . '000';
					break;
			}

			if ( ! isset( $prepared_data[ $time ] ) ) {
				$prepared_data[ $time ] = array( esc_js( $time ), 0 );
			}
		}

		foreach ( $data as $d ) {
			switch ( $group_by ) {
				case 'day' :
					$time = strtotime( date( 'Ymd', strtotime( $d->$date_key ) ) ) . '000';
					break;
				case 'month' :
				default :
					$time = strtotime( date( 'Ym', strtotime( $d->$date_key ) ) . '01' ) . '000';
					break;
			}

			if ( ! isset( $prepared_data[ $time ] ) ) {
				continue;
			}

			if ( $data_key ) {
				$prepared_data[ $time ][1] += $d->$data_key;
			} else {
				$prepared_data[ $time ][1] ++;
			}
		}

		return $prepared_data;
	}


	/**
	 * @return int
	 */
	function get_interval() {
		return absint( ceil( max( 0, ( $this->date_to->getTimestamp() - $this->date_from->getTimestamp() ) / ( 60 * 60 * 24 ) ) ) );
	}


	/**
	 * @param $page_id
	 * @return string
	 */
	function get_report_url( $page_id ) {
		return add_query_arg([
			'range' => 'custom',
			'start_date' => $this->date_from->format('Y-m-d'),
			'end_date' => $this->date_to->format('Y-m-d')
		], Admin::page_url( $page_id ) );
	}

}
