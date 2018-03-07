<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var $event Queued_Event
 */

$data_layer = $event->get_data_layer();
$formatted_data = Admin_Data_Layer_Formatter::format( $data_layer );

?>

	<div class="automatewoo-modal__header">
		<h1><?php printf(__( "Queued event #%s", 'automatewoo' ), $event->get_id() ) ?></h1>
	</div>

	<div class="automatewoo-modal__body">
		<div class="automatewoo-modal__body-inner">

			<ul>
                <?php if ( $event->is_failed() ): ?>
				    <li><strong><?php _e('Failed', 'automatewoo') ?>:</strong> <?php echo $event->get_failure_message() ?></li>
                <?php endif ?>

				<li><strong><?php _e('Workflow', 'automatewoo') ?>:</strong> <a href="<?php echo get_edit_post_link( $event->get_workflow_id() ) ?>"><?php echo get_the_title( $event->get_workflow_id() ) ?></a></li>
				<li><strong><?php _e('Due to run', 'automatewoo') ?>:</strong> <?php echo Format::datetime( $event->get_date_due(), 0 ) ?></li>
				<li><strong><?php _e('Created', 'automatewoo') ?>:</strong> <?php echo Format::datetime( $event->get_date_created(), 0 ) ?></li>

				<?php foreach ( $formatted_data as $item ): ?>
					<li><strong><?php echo $item['title'] ?>:</strong> <?php echo $item['value'] ?></li>
				<?php endforeach; ?>

			</ul>

		</div>
	</div>
