<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var Workflow $workflow
 * @var Trigger $current_trigger
 */

// Group triggers
$trigger_list = [];

foreach ( Triggers::get_all() as $trigger ) {
	$trigger_list[$trigger->get_group()][] = $trigger;
}

$trigger_list = aw_array_move_to_end( $trigger_list, __( 'DEPRECATED', 'automatewoo' ) );

?>

	<table class="automatewoo-table">

		<tr class="automatewoo-table__row" data-name="trigger_name" data-type="select" data-required="1">
			<td class="automatewoo-table__col automatewoo-table__col--label">
				<label><?php _e( 'Trigger', 'automatewoo' ) ?> <span class="required">*</span></label>
			</td>
			<td class="automatewoo-table__col automatewoo-table__col--field">

				<select name="aw_workflow_data[trigger_name]" class="automatewoo-field js-trigger-select">
					<option value=""><?php _e( '[Select]', 'automatewoo' ) ?></option>
					<?php foreach ($trigger_list as $trigger_group => $triggers ): ?>
						<optgroup label="<?php echo $trigger_group; ?>">
							<?php foreach ($triggers as $_trigger ): /** @var Trigger $_trigger */ ?>
								<option value="<?php echo $_trigger->get_name(); ?>" <?php echo ( $current_trigger && $current_trigger->get_name() == $_trigger->get_name() ? 'selected="selected"' : '' ); ?>><?php echo $_trigger->get_title(); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>

				<?php if ( $current_trigger && $current_trigger->get_description() ): ?>
					<div class="js-trigger-description"><?php echo $current_trigger->get_description_html() ?></div>
				<?php else: ?>
					<div class="js-trigger-description"></div>
				<?php endif; ?>

			</td>
		</tr>

		<?php

		if ( $workflow ) {
			Admin::get_view( 'trigger-fields', [
				'trigger' => $current_trigger,
				'workflow' => $workflow,
				'fill_fields' => true
			]);
		}

		?>

	</table>
