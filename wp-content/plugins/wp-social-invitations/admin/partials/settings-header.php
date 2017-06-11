<?php
/**
 * Header and tabs
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
?>
<div class="wrap wsi-settings">
	<h2>Wp Social Invitations <?php echo $this->version;?></h2>

	<h2 class="nav-tab-wrapper">
		<div id="ui-tabs">
			<ul class="ui-tabs-nav">
				<li><a id="wsi-main-tab" href="#wsi-main" class="nav-tab nav-tab-active"><?php _e( 'Main Settings', 'wsi' );?></a></li>
				<li><a id="wsi-messages-tab" href="#wsi-messages" class="nav-tab"><?php _e( 'Default Messages', 'wsi' );?></a></li>
				<li><a id="wsi-emails-tab" href="#wsi-emails" class="nav-tab"><?php _e( 'Emails', 'wsi' );?></a></li>
				<li><a id="wsi-styling-tab" href="#wsi-styling" class="nav-tab"><?php _e( 'Styling', 'wsi' );?></a></li>
				<li><a id="wsi-stats-tab" href="#wsi-stats" class="nav-tab"><?php _e( 'Stats', 'wsi' );?></a></li>
				<li><a id="wsi-debug-tab" href="#wsi-debug" class="nav-tab"><?php _e( 'Debug', 'wsi' );?></a></li>
			</ul>
		</div>
	</h2>

	<form name="wsi-settings" method="post" id="wsi-settings-form" action="<?php echo admin_url('admin.php?page=wsi');?>">
