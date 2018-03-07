<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Chart_Workflows_Run
 */
class Dashboard_Widget_Chart_Workflows_Run extends Dashboard_Widget_Chart {

	public $id = 'chart-workflows-run';

	function load_data() {

		$logs = $this->controller->get_logs();
		$logs_data = [];

		foreach ( $logs as $log ) {

			if ( ! $date = $log->get_date() ) {
				continue;
			}

			Time_Helper::convert_from_gmt( $date );

			$logs_data[] = (object) [
				'date' => $date->format( Format::MYSQL )
			];
		}

		return [ array_values( $this->prepare_chart_data( $logs_data, 'date', false, $this->get_interval(), 'day' ) ) ];
	}


	function output_content() {

		if ( ! $this->date_to || ! $this->date_from )
			return;

		$logs = $this->controller->get_logs();
		$this->render_js();

		?>

		<div class="automatewoo-dashboard-chart">

			<div class="automatewoo-dashboard-chart__header">

				<div class="automatewoo-dashboard-chart__header-group">
					<div class="automatewoo-dashboard-chart__header-figure"><?php echo count( $logs ) ?></div>
					<div class="automatewoo-dashboard-chart__header-text">
						<span class="automatewoo-dashboard-chart__legend automatewoo-dashboard-chart__legend--blue"></span>
						<?php _e( 'workflows run', 'automatewoo' ) ?>
					</div>
				</div>

				<a href="<?php echo $this->get_report_url( 'workflows-report' ) ?>" class="automatewoo-arrow-link"></a>
			</div>

			<div class="automatewoo-dashboard-chart__tooltip"></div>

			<div id="automatewoo-dashboard-<?php echo $this->get_id() ?>" class="automatewoo-dashboard-chart__flot"></div>

		</div>

		<?php
	}

}

return new Dashboard_Widget_Chart_Workflows_Run();
