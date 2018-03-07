<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Chart_Conversions
 */
class Dashboard_Widget_Chart_Conversions extends Dashboard_Widget_Chart {

	public $id = 'chart-conversions';

	public $is_currency = true;

	public $conversion_count = 0;

	public $conversion_total = 0;


	/**
	 * @return array
	 */
	function load_data() {

		$conversions = $this->controller->get_conversions();
		$conversions_clean = [];

		foreach ( $conversions as $order ) {
			$conversions_clean[] = (object) [
				'date' => Compat\Order::get_date_created( $order ),
				'total' => $order->get_total()
			];

			$this->conversion_count++;
			$this->conversion_total += $order->get_total();
		}

		return [ array_values( $this->prepare_chart_data( $conversions_clean, 'date', 'total', $this->get_interval(), 'day' ) ) ];
	}


	function output_content() {

		if ( ! $this->date_to || ! $this->date_from )
			return;

		$this->render_js();

		?>

		<div class="automatewoo-dashboard-chart">

			<div class="automatewoo-dashboard-chart__header">

				<div class="automatewoo-dashboard-chart__header-group">
					<div class="automatewoo-dashboard-chart__header-figure"><?php echo wc_price( $this->conversion_total ) ?></div>
					<div class="automatewoo-dashboard-chart__header-text">
						<span class="automatewoo-dashboard-chart__legend automatewoo-dashboard-chart__legend--blue"></span>
						<?php _e( 'conversion revenue', 'automatewoo' ) ?>
					</div>
				</div>

				<div class="automatewoo-dashboard-chart__header-group">
					<div class="automatewoo-dashboard-chart__header-figure"><?php echo $this->conversion_count ?></div>
					<div class="automatewoo-dashboard-chart__header-text"><?php _e( 'conversions', 'automatewoo' ) ?></div>
				</div>

				<a href="<?php echo $this->get_report_url( 'conversions' ) ?>" class="automatewoo-arrow-link"></a>
			</div>

			<div class="automatewoo-dashboard-chart__tooltip"></div>

			<div id="automatewoo-dashboard-<?php echo $this->get_id() ?>" class="automatewoo-dashboard-chart__flot"></div>

		</div>

		<?php
	}

}

return new Dashboard_Widget_Chart_Conversions();
