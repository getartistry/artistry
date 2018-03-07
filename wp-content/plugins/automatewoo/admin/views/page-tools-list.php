<?php

namespace AutomateWoo;

/**
 * @var $tools array
 * @var Admin\Controllers\Tools_Controller $controller
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap automatewoo-page automatewoo-page--tools">

	<h1><?php echo $controller->get_heading() ?></h1>

	<?php $controller->output_messages(); ?>

	<div id="poststuff">
		<table class="aw_tools_table wc_status_table widefat" cellspacing="0"><tbody>

			<?php foreach ( $tools as $tool ): ?>
				<tr>
					<td class="">
						<a href="<?php echo $controller->get_route_url( 'view', $tool ) ?>"><?php echo $tool->title; ?></a>
					</td>

					<td class="">
						<span class="description"><?php echo $tool->description; ?></span>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody></table>
	</div>

</div>


