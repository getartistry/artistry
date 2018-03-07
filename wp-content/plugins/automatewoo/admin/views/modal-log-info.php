<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var $log Log
 */

$notes = $log->get_meta('notes');

$data_layer = $log->get_data_layer('object');

$formatted_data = Admin_Data_Layer_Formatter::format( $data_layer );

/** @deprecated filter */
$formatted_data = apply_filters( 'automatewoo/log/human_readable_data_layer', $formatted_data, $log );


?>

	<div class="automatewoo-modal__header">
		<h1><?php printf(__( "Log #%s", 'automatewoo' ), $log->get_id() ) ?></h1>
	</div>

	<div class="automatewoo-modal__body">
		<div class="automatewoo-modal__body-inner">

			<ul>
				<li><strong><?php _e('Workflow', 'automatewoo') ?>:</strong> <a href="<?php echo get_edit_post_link( $log->get_workflow_id() ) ?>"><?php echo get_the_title( $log->get_workflow_id() ) ?></a></li>
				<li><strong><?php _e('Time', 'automatewoo') ?>:</strong> <?php echo Format::datetime( $log->get_date() ) ?></li>

				<?php foreach ( $formatted_data as $item ): ?>
					<li><strong><?php echo $item['title'] ?>:</strong> <?php echo $item['value'] ?></li>
				<?php endforeach; ?>

				<li><strong><?php _e('Tracking enabled', 'automatewoo') ?>:</strong> <?php echo ( $log->is_tracking_enabled() ? __('Yes','automatewoo') : __('No','automatewoo') ) ?></li>
				<li><strong><?php _e('Conversion tracking enabled', 'automatewoo') ?>:</strong> <?php echo ( $log->is_conversion_tracking_enabled() ? __('Yes','automatewoo') : __('No','automatewoo') ) ?></li>

				<?php if ( $log->is_tracking_enabled() ): ?>
					<li><strong><?php _e('Opened', 'automatewoo') ?>:</strong> <?php echo ( $log->has_open_recorded() ? Format::datetime($log->get_date_opened()) : __('No','automatewoo') ) ?></li>
					<li><strong><?php _e('Clicked', 'automatewoo') ?>:</strong> <?php echo ( $log->has_click_recorded() ? Format::datetime($log->get_date_clicked()) : __('No','automatewoo') ) ?></li>
				<?php endif; ?>

			</ul>

			<?php if ( $notes ): ?>
				<hr>

				<strong><?php _e( "Log notes:", 'automatewoo' ) ?></strong><br>
				<?php foreach ($notes as $note ): ?>
					<p><?php echo $note; ?></p>
				<?php endforeach; ?>

			<?php endif; ?>

			<hr>

			<?php $rerun_url = add_query_arg( [ 'action' => 'rerun', 'log_id' => $log->get_id() ], Admin::page_url( 'logs' ) ) ?>
			<a href="<?php echo wp_nonce_url( $rerun_url, 'rerun_log' ) ?>" class="button"><?php _e( 'Re-run workflow (skips validation)', 'automatewoo' ) ?></a>

		</div>
	</div>
