<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var Tool $tool
 * @var Admin\Controllers\Tools_Controller $controller
 * @var $content
 */
?>

<div class="wrap automatewoo-page automatewoo-page--tools">

	<?php Admin::get_view( 'tool-header', ['tool' => $tool, 'controller' => $controller ] ); ?>

	<div id="poststuff">

		<form id="automatewoo_process_tool_form" method="post" action="<?php echo $controller->get_route_url( 'validate', $tool ) ?>">

			<div class="automatewoo-metabox postbox">

				<table class="automatewoo-table">

					<?php foreach ( $tool->get_form_fields() as $field ): ?>

						<tr class="automatewoo-table__row">

							<td class="automatewoo-table__col automatewoo-table__col--label">
								<?php if ( $field->get_description() ): ?>
									<?php echo Admin::help_tip( $field->get_description() ) ?>
								<?php endif; ?>

								<label><?php echo $field->get_title(); ?>
									<?php if ( $field->get_required() ): ?>
										<span class="required">*</span>
									<?php endif; ?>
								</label>
							</td>

							<td class="automatewoo-table__col automatewoo-table__col--field">
								<?php

								$value = isset( $_POST['args'][ $field->get_name() ] ) ? $field->esc_value( $_POST['args'][ $field->get_name() ] ) : false;
								$field->render( $value );

								?>
							</td>
						</tr>

					<?php endforeach; ?>

				</table>

				<div class="automatewoo-metabox-footer">
					<button type="submit" class="button button-primary button-large"><?php _e('Next', 'automatewoo') ?></button>
				</div>
			</div>

		</form>


	</div>

</div>
