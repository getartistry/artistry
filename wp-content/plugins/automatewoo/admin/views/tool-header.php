<?php

namespace AutomateWoo;

/**
 * @var $tool Tool
 * @var Admin\Controllers\Tools_Controller $controller
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h1><a href="<?php echo Admin::page_url('tools') ?>"><?php echo $controller->get_heading() ?></a> &gt; <?php echo $tool->title ?></h1>

<?php $controller->output_messages(); ?>

<?php echo wpautop( $tool->description ) ?>

<?php if ( $tool->additional_description ): ?>
	<?php echo wpautop( $tool->additional_description ) ?>
<?php endif ?>

