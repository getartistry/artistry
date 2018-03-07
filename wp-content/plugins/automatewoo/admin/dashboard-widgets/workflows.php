<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Workflows
 */
class Dashboard_Widget_Workflows extends Dashboard_Widget {

	function __construct() {
		$this->id = 'workflows';
	}


	/**
	 * @return array
	 */
	function get_featured() {

		$featured = [];

		if ( ! $this->date_to || ! $this->date_from )
			return [];

		$logs = $this->controller->get_logs();
		$conversions = $this->controller->get_conversions();


		$counts = [];

		foreach ( $logs as $log ) {
			$counts[] = $log->get_workflow_id();
		}

		$counts = array_count_values( $counts );
		arsort( $counts, SORT_NUMERIC );
		$workflow = AW()->get_workflow( key( $counts ) );

		if ( $workflow ) {
			$featured[] = [
				'workflow' => $workflow,
				'description' => __( 'most run workflow', 'automatewoo' ),
			];
		}


		if ( $conversions ) {

			$totals = [];

			foreach ( $conversions as $order ) {
				$workflow_id = absint( Compat\Order::get_meta( $order, '_aw_conversion' ) );

				if ( isset( $totals[ $workflow_id ] ) ) {
					$totals[ $workflow_id ] += $order->get_total();
				}
				else {
					$totals[ $workflow_id ] = $order->get_total();
				}
			}

			arsort( $totals, SORT_NUMERIC );
			$workflow = AW()->get_workflow( key( $totals ) );

			if ( $workflow ) {
				$featured[] = [
					'workflow' => $workflow,
					'description' => __( 'highest converting workflow', 'automatewoo' ),
				];
			}
		}

		return $featured;
	}


	function output_content() {

		$features = $this->get_featured();

		?>

		<div class="automatewoo-dashboard__workflows">
			<?php foreach ( $features as $feature ): ?>

				<?php $workflow = $feature['workflow']; /** @var $workflow Workflow  */ ?>

				<a class="automatewoo-dashboard__workflow" href="<?php echo esc_url( get_edit_post_link( $workflow->get_id() ) ) ?>">

					<div class="automatewoo-dashboard__workflow-title"><?php echo esc_attr( $workflow->get_title() ) ?></div>
					<div class="automatewoo-dashboard__workflow-description"><?php echo esc_attr( $feature['description'] ) ?></div>

				</a>

			<?php endforeach; ?>
		</div>

		<?php
	}

}

return new Dashboard_Widget_Workflows();
