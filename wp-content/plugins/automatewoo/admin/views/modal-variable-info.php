<?php
/**
 * @var $variable string
 * @var $variable_obj AutomateWoo\Variable
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

	<div class="automatewoo-modal__header">
		<h1><?php echo esc_attr( $variable ) ?></h1>
	</div>

	<div class="automatewoo-modal__body">
		<div class="automatewoo-modal__body-inner">

			<?php if ( $variable_obj && $variable_obj->get_description() ): ?>
				<p><?php echo $variable_obj->get_description() ?></p>
			<?php endif; ?>


			<table class="automatewoo-table automatewoo-table--bordered aw-workflow-variable-parameters-table">

				<?php if ( $variable_obj && $variable_obj->has_parameters() ) foreach ( $variable_obj->get_parameters() as $parameter_name => $parameter ): ?>

					<tr class="automatewoo-table__row aw-workflow-variables-parameter-row"
						 data-parameter-name="<?php echo $parameter_name ?>"
						<?php if ( isset ( $parameter['show'] ) ): ?>data-parameter-show="<?php echo $parameter['show'] ?>"<?php endif; ?>
						<?php echo ( $parameter['required'] ? 'data-is-required="true"' : '' ) ?>
						>

						<td class="automatewoo-table__col automatewoo-table__col--label">
							<strong><?php echo $parameter_name ?></strong>
							<?php if ( $parameter['required'] ): ?><span class="aw-required-asterisk"></span><?php endif; ?>
							<?php echo AutomateWoo\Admin::help_tip( $parameter['description'] ) ?>
						</td>
						<td class="automatewoo-table__col automatewoo-table__col--field">

							<?php if ( $parameter['type'] === 'text' ): ?>

								<input type="text" name="<?php echo $parameter_name ?>" placeholder="<?php echo $parameter['placeholder'] ?>" class="automatewoo-field automatewoo-field--type-text aw-workflow-variable-parameter">

							<?php elseif ( $parameter['type'] === 'select' ): ?>

								<select name="<?php echo $parameter_name ?>" class="automatewoo-field automatewoo-field--type-select aw-workflow-variable-parameter">
									<?php foreach ( $parameter['options'] as $value => $text ): ?>
										<option value="<?php echo $value ?>"><?php echo $text ?></option>
									<?php endforeach; ?>
								</select>

							<?php endif; ?>

						</td>
					</tr>
				<?php endforeach; ?>

				<?php if ( $variable_obj->use_fallback ): ?>
					<tr class="automatewoo-table__row">
						<td class="automatewoo-table__col automatewoo-table__col--label">
							<strong>fallback</strong>
							<?php echo AutomateWoo\Admin::help_tip( __( 'Displayed when there is no value found.', 'automatewoo') ) ?>
						</td>
						<td class="automatewoo-table__col automatewoo-table__col--field">
							<input type="text" name="fallback" class="automatewoo-field automatewoo-field--type-text aw-workflow-variable-parameter">
						</td>
					</tr>
					<?php endif; ?>

			</table>


			<div class="aw-workflow-variable-clipboard-form">
				<div id="aw_workflow_variable_preview_field" class="aw-workflow-variable-preview-field" data-variable="<?php echo $variable ?>"></div>
				<button class="aw-clipboard-btn button button-primary button-large" data-clipboard-target="#aw_workflow_variable_preview_field"><?php _e( 'Copy to clipboard', 'automatewoo' ) ?></button>
			</div>

		</div>
	</div>
