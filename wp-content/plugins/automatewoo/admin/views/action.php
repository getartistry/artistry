<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var $action_number
 * @var Action $action
 * @var $action_select_box_values
 * @var $workflow Workflow
 */

if ( $workflow ) {
	$editing = isset( $_COOKIE['aw_editing_action_' . $workflow->get_id() . '_' . $action_number ] );
}
else {
	$editing = false;
}


?>
	<div class="<?php echo ( $action ? 'automatewoo-action' : 'aw-action-template' ) ?> <?php echo ( $editing ? 'js-open' : '' ) ?>"
	     data-action-number="<?php echo $action ? $action_number : '' ?>">

		<div class="automatewoo-action__header">
			<div class="row-options">
				<a href="#" data-automatewoo-preview><?php echo __( 'Preview', 'automatewoo' ) ?></a>
				<a class="js-edit-action" href="#"><?php echo __( 'Edit', 'automatewoo' ) ?></a>
				<a class="js-delete-action" href="#"><?php echo __( 'Delete', 'automatewoo' ) ?></a>
			</div>

			<h4 class="action-title"><?php echo ( $action ? $action->get_title( true ) : __( 'New Action', 'automatewoo' ) ); ?></h4>
		</div>

		<div class="automatewoo-action__fields">
			<table class="automatewoo-table">

				<tr class="automatewoo-table__row" data-name="action_name" data-type="select" data-required="1">
					<td class="automatewoo-table__col automatewoo-table__col--label">
						<label><?php echo __( 'Action', 'automatewoo' ) ?> <span class="required">*</span></label>
					</td>
					<td class="automatewoo-table__col automatewoo-table__col--field">

						<?php

						$action_field = new Fields\Select();

						if ( $action ) {
							$action_field->set_name_base( "aw_workflow_data[actions][{$action_number}]" );
							$action_field->set_name('action_name');
						}
						else {
							$action_field->set_name('');
						}

						$action_field->set_options( $action_select_box_values );
						$action_field->add_classes('js-action-select');
						$action_field->render( $action ? $action->get_name() : false );

						?>

						<?php if ( $action && $action->get_description() ): ?>
							<div class="js-action-description"><?php echo $action->get_description_html() ?></div>
						<?php else: ?>
							<div class="js-action-description"></div>
						<?php endif; ?>

					</td>

				</tr>

				<?php
					if ( $action )
						Admin::get_view('action-fields', [
							'action' => $action,
							'action_number' => $action_number,
							'workflow' => $workflow,
							'fill_fields' => true
						]);
				?>

			</table>
		</div>

	</div>