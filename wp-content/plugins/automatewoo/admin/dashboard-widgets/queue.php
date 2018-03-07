<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Queue
 */
class Dashboard_Widget_Queue extends Dashboard_Widget {

	public $id = 'queue';

	/**
	 * @return Queued_Event[]
	 */
	function get_logs() {

		$query = new Queue_Query();
		$query->set_limit( 7 );
		$query->set_ordering( 'date', 'ASC' );
		$query->where('failed', 0 );

		return $query->get_results();
	}


	function output_content() {

		$queue = $this->get_logs();

		?>

		<div class="automatewoo-dashboard-list">

			<div class="automatewoo-dashboard-list__header">
				<div class="automatewoo-dashboard-list__heading">
					<?php _e( 'Upcoming queued events', 'automatewoo' ) ?>
				</div>
				<a href="<?php echo Admin::page_url( 'queue' ) ?>" class="automatewoo-arrow-link"></a>
			</div>

			<?php if ( $queue ): ?>

				<div class="automatewoo-dashboard-list__items">

					<?php foreach ( $queue as $event ):

						$workflow = $event->get_workflow();

						?>

						<div class="automatewoo-dashboard-list__item">

							<a href="<?php echo get_edit_post_link( $workflow->get_id() ) ?>" class="automatewoo-dashboard-list__item-title"><?php echo $workflow->get_title(); ?></a>
							<div class="automatewoo-dashboard-list__item-text"><?php echo Format::datetime( $event->get_date_due() ) ?></div>
						</div>

					<?php endforeach; ?>

				</div>

			<?php else: ?>

				<div class="automatewoo-dashboard-list__empty">
					<?php _e( 'There are no events currently queued&hellip;', 'automatewoo' ) ?>
				</div>

			<?php endif; ?>

		</div>

		<?php
	}

}

return new Dashboard_Widget_Queue();
