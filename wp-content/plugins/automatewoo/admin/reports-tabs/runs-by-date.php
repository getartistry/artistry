<?php
/**
 * @class 		AW_Reports_Tab_Runs_By_Date
 */

if ( ! defined( 'ABSPATH' ) ) exit;


class AW_Reports_Tab_Runs_By_Date extends AW_Admin_Reports_Tab_Abstract {

	function __construct() {
		$this->id = 'runs-by-date';
		$this->name = __( 'Workflows', 'automatewoo' );
	}


	/**
	 * @return object
	 */
	function get_report_class() {
		include_once AW()->admin_path( '/reports/abstract-graph.php' );
		include_once AW()->admin_path( '/reports/runs-by-date.php' );

		return new AutomateWoo\Report_Runs_By_Date();
	}
}

return new AW_Reports_Tab_Runs_By_Date();
