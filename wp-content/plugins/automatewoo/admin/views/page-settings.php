<?php
/**
 * @var $current_tab AutomateWoo\Admin_Settings_Tab_Abstract
 */

if ( ! defined( 'ABSPATH' ) ) exit;


?>

<div class="wrap woocommerce automatewoo-page automatewoo-page--settings">

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab ): ?>
			<a href="<?php echo $tab->get_url() ?>" class="nav-tab <?php echo ( $current_tab->id == $tab->id ? 'nav-tab-active' : '' ) ?>"><?php echo $tab->name ?></a>
		<?php endforeach; ?>
	</h2>

	<div class="aw-settings-messages">
		<?php $current_tab->output_messages() ?>
	</div>

	<div class="aw-settings-tab-container">

		<?php if ( $current_tab->show_tab_title ): ?>
			<h3><?php echo $current_tab->name ?></h3>
		<?php endif; ?>

		<?php $current_tab->output(); ?>
	</div>

</div>

