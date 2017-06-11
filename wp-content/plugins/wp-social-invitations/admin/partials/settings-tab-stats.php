<?php
/**
 * Stats settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
global $wpdb;

$total_invites = $wpdb->get_var("SELECT SUM(quantity) as c FROM {$wpdb->prefix}wsi_stats");
?>
<div id="wsi-stats" class="ui-tabs-panel">

	<h2><?php printf(__('So far %s invitations were sent using WSI!','wsi'),$total_invites);?></h2>
	<table class="widefat ia-stats" style="min-width:1000px">
			<thead><tr>
				<th scope="col" class="in-the-last"><?php _e('In the last...','wsi');?></th>
				<?php

				$providers = $wsi_plugin->get_providers();

				foreach( $providers as $p )
				{
					echo '<th scope="col">'.ucfirst($p).'</th>';
				}
				echo '<th scope="col">Total</th>';
				?>
			</tr></thead>
			<tbody>
			<tr>
				<th scope="row"><?php _e('24 Hours','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{

					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' AND i_datetime >= ( NOW( ) - INTERVAL 1 DAY ) ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE i_datetime >= ( NOW( ) - INTERVAL 1 DAY ) ");
				echo '<td>'.$stat.'</td>';

				?>
			</tr>
			<tr>
				<th scope="row"><?php _e('3 Days','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{
					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' AND i_datetime >= ( NOW( ) - INTERVAL 3 DAY ) ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE i_datetime >= ( NOW( ) - INTERVAL 3 DAY ) ");
				echo '<td>'.$stat.'</td>';
				?>

			</tr>
			<tr>
				<th scope="row"><?php _e('1 Week','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{
					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' AND i_datetime >= ( NOW( ) - INTERVAL 7 DAY ) ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE i_datetime >= ( NOW( ) - INTERVAL 7 DAY ) ");
				echo '<td>'.$stat.'</td>';
				?>

			</tr>
			<tr>
				<th scope="row"><?php _e('1 Month','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{
					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' AND i_datetime >= ( NOW( ) - INTERVAL 1 MONTH ) ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE i_datetime >= ( NOW( ) - INTERVAL 1 MONTH ) ");
				echo '<td>'.$stat.'</td>';
				?>
			</tr>
			<tr>
				<th scope="row"><?php _e('3 Months','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{
					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' AND i_datetime >= ( NOW( ) - INTERVAL 3 MONTH ) ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE i_datetime >= ( NOW( ) - INTERVAL 3 MONTH ) ");
				echo '<td>'.$stat.'</td>';
				?>
			</tr>
			<tr>
				<th scope="row"><?php _e('All Time','wsi');?></th>
				<?php
				foreach( $providers as $p => $p_name)
				{
					$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE provider = '{$p}' ");
					echo '<td>'.$stat.'</td>';
				}
				$stat = $wpdb->get_var("SELECT IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats");
				echo '<td>'.$stat.'</td>';
				?>
			</tr>

			</tbody>
		</table>
		<div class="info-box">
			<h3><?php _e('More stats!','wsi');?></h3>
			<?php
			$total_accepted = $users = $wpdb->get_var("SELECT count(*) as total FROM {$wpdb->prefix}wsi_accepted_invites  GROUP BY user_id ");

			?>
			<p><?php echo sprintf(__('So far %s invitations were accepted in total.','wsi'), $total_accepted);?></p>
			<h4><?php _e('Top 5 users sending invitations:','wsi');?></h4>
			<ol>
				<?php
				$users = $wpdb->get_results("SELECT user_id, display_name, IFNULL(SUM(quantity),0) as c FROM {$wpdb->prefix}wsi_stats WHERE user_id > '0' GROUP BY user_id ORDER BY c DESC LIMIT 5");
				foreach( $users as $user ) {
					echo '<li><a href="'.get_edit_user_link( $user->user_id ).'">';
					if (empty($user->display_name) ) {
						$current_u = get_userdata($user->user_id);
						echo $current_u->display_name;
					} else {
						echo $user->display_name;
					}
					echo ' ('. $user->c .')</a></li>';
				}
				?>
			</ol>
			<h4><?php _e('Top 5 users with accepted invitations:','wsi');?></h4>
			<ol>
				<?php
				$users = $wpdb->get_results("SELECT user_id, count(*) as total, display_name FROM {$wpdb->prefix}wsi_accepted_invites a LEFT JOIN $wpdb->users b ON a.user_id = b.ID GROUP BY user_id ORDER BY total DESC LIMIT 5");
				foreach( $users as $user ) {
					echo '<li><a href="'.get_edit_user_link( $user->user_id ).'">'.$user->display_name.' ('. $user->total .')</a></li>';
				}
				?>
			</ol>
		</div>

</div>