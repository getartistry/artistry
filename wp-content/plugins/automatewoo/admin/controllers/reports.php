<?php

namespace AutomateWoo\Admin\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Reports
 */
class Reports extends Base {

	/** @var array */
	private $reports = [];


	function handle() {
		$this->handle_actions();
		$this->output_list_table();
	}


	function output_list_table() {
		$this->output_view( 'page-reports', [
			'current_tab' => $this->get_current_tab(),
			'tabs' => $this->get_reports_tabs()
		]);
	}


	function handle_actions() {
		$current_tab = $this->get_current_tab();
		$current_tab->handle_actions( $this->get_current_action() );
	}



	/**
	 * @return \AW_Admin_Reports_Tab_Abstract|false
	 */
	function get_current_tab() {

		$tabs = $this->get_reports_tabs();

		$current_tab_id = empty( $_GET['tab'] ) ? current($tabs)->id : sanitize_title( $_GET['tab'] );

		return isset( $tabs[$current_tab_id] ) ? $tabs[$current_tab_id] : false;
	}


	/**
	 * @return array
	 */
	function get_reports_tabs() {

		if ( empty( $this->reports ) ) {
			$path = AW()->path( '/admin/reports-tabs/' );

			$report_includes = [];

			$report_includes[] = $path . 'runs-by-date.php';
			$report_includes[] = $path . 'email-tracking.php';
			$report_includes[] = $path . 'conversions.php';
			$report_includes[] = $path . 'conversions-list.php';

			$report_includes = apply_filters( 'automatewoo/reports/tabs', $report_includes );

			include_once $path . 'abstract.php';

			foreach ( $report_includes as $report_include ) {
				/** @var \AW_Admin_Reports_Tab_Abstract $class */
				$class = include_once $report_include;
				$class->controller = $this;
				$this->reports[$class->id] = $class;
			}
		}

		return $this->reports;
	}

}

return new Reports();
