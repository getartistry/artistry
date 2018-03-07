<?php
/**
 * @var AutomateWoo\Action $action
 * @var AutomateWoo\Workflow $workflow
 * @var $fill_fields (optional)
 */

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! $action || ! $action_number )
	return;


// default to false
if ( ! isset( $fill_fields ) )
	$fill_fields = false;


if ( $fill_fields ) {
	$action = $workflow->get_action( $action_number );
}

$fields = $action->get_fields();

?>

	<?php $first = true; foreach( $fields as $field ):

		// add action number to name base
		$field->set_name_base( $field->get_name_base() . "[$action_number]" );

		if ( $fill_fields ) {

			// load dynamic options before value is set and field is rendered
			if ( $field->get_type() == 'select' ) {

				/** @var $field AutomateWoo\Fields\Select */
				if ( $field->has_dynamic_options() ) {

					$options = $action->get_dynamic_field_options( $field->get_name() );
					$field->set_options( $options );
				}
			}

			$value = $action->get_option( $field->get_name() );
		}
		else {
			$value = '';
		}


		?>

		<tr class="automatewoo-table__row"
		    data-name="<?php echo $field->get_name(); ?>"
		    data-type="<?php echo $field->get_type(); ?>"
		    data-required="<?php echo (int) $field->get_required() ?> ">

			<td class="automatewoo-table__col automatewoo-table__col--label">

				<?php if ( $first ): ?>
					<?php $action->check_requirements(); ?>
				<?php endif ?>

				<?php if ( $field->get_description() ): ?>
					<?php echo AutomateWoo\Admin::help_tip( $field->get_description() ) ?>
				<?php endif; ?>

				<label><?php echo $field->get_title(); ?>
					<?php if ( $field->get_required() ): ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>

			</td>

			<td class="automatewoo-table__col automatewoo-table__col--field automatewoo-field-wrap">
				<?php $field->render( $value ); ?>
			</td>
		</tr>

	<?php $first = false; endforeach; ?>