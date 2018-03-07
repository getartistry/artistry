<?php
/**
 * @var $workflow
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

	<table class="automatewoo-table">

		<tr class="automatewoo-table__row">
			<td class="automatewoo-table__col">

				<label class="automatewoo-label"><?php _e( 'Variables', 'automatewoo' ) ?> <?php echo AutomateWoo\Admin::help_tip( __( 'Click on a variable to see more info and copy it to the clipboard. Variables can be used in any action text field to add dynamic content. The available variables are set based on the selected trigger for this workflow.', 'automatewoo' ) ) ?></label>

				<div class="aw-workflow-variables-container">

					<?php foreach( AutomateWoo\Variables::get_list() as $data_type => $vars ):
                              if ( $data_type === 'user' ) {
                                  $data_type = 'customer';
                              }
							  ?>
						<div class="aw-variables-group" data-automatewoo-variable-group="<?php echo $data_type; ?>">
							<?php foreach ( $vars as $variable => $file_path ): ?>
								<div class="aw-workflow-variable-outer"><span class="aw-workflow-variable"><?php echo $data_type.'.'.$variable ?></span></div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>

				</div>

			</td>
		</tr>

	</table>
