<?php
/**
 * @var $current_tab AW_Admin_Reports_Tab_Abstract
 * @var $controller AutomateWoo\Admin\Controllers\Reports
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap automatewoo-page automatewoo-page--reports">

	<h1><?php printf( __( '%s Report' ), $current_tab->name ) ?></h1>

	<?php $controller->output_messages(); ?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab ): ?>
			<a href="<?php echo $tab->get_url() ?>" class="nav-tab <?php echo ( $current_tab->id == $tab->id ? 'nav-tab-active' : '' ) ?>"><?php echo $tab->name ?></a>
		<?php endforeach; ?>
	</h2>

	<?php if ( $html = $current_tab->output_before_report() ): ?>
		<div class="aw-before-report-output">
			<?php echo $html; ?>
		</div>
	<?php endif; ?>

	<div class="aw-reports-tab-container">
		<?php $current_tab->output(); ?>
	</div>

</div>

