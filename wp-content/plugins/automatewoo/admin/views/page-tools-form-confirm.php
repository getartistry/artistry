<?php

namespace AutomateWoo;

/**
 * @var Tool $tool
 * @var Admin\Controllers\Tools_Controller $controller
 * @var $args array
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap automatewoo-page automatewoo-page--tools">

	<?php Admin::get_view('tool-header', [ 'tool' => $tool, 'controller' => $controller ] ); ?>

	<div id="poststuff">

		<form id="automatewoo_process_tool_form" method="post" action="<?php echo $controller->get_route_url( 'confirm', $tool ) ?>">

			<?php wp_nonce_field( $tool->get_id() ) ?>

			<?php foreach ( $args as $key => $value ): ?>
				<input type="hidden" name="args[<?php echo $key ?>]" value="<?php echo $value ?>">
			<?php endforeach ?>

			<div class="automatewoo-metabox postbox">
				<div class="automatewoo-metabox-pad">
					<p><?php $tool->display_confirmation_screen( $args ) ?></p>
				</div>

				<div class="automatewoo-metabox-footer">
					<button type="submit" class="button button-primary button-large"><?php _e( 'Confirm', 'automatewoo' ) ?></button>
				</div>
			</div>

		</form>

	</div>

</div>


