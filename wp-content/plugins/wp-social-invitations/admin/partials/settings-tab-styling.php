<?php
/**
 * Styling settings tab
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/admin/partials
 */
?>
<div id="wsi-styling" class="ui-tabs-panel">
	<div class="info-box">
		<p><?php _e('Here you can drag and drop the providers to change the order in the widget and also you will be able to add custom CSS.','wsi');?></p>

		<p><?php echo sprintf(__('If you have any question please carefully <a href="%s">read documentation</a> first opening a ticket','wsi'), 'http://wp.timersys.com/wordpress-social-invitations/docs/styling/');?></p>
	</div>

	<table class="form-table">
		<?php do_action( 'wsi/settings_page/styling_tab_before' ); ?>

		<tr valign="top" class="">
			<th><label><?php _e( 'Widget Order', 'wsi' ); ?></label></th>
			<td colspan="3">
				<div id="sortable-form">
					<ul class="sortable-list">
						<?php
						foreach( $wsi_plugin->get_ordered_providers() as $p => $provider) { ?>
							<li>
								<div id="<?php echo $p;?>-provider" data-li-origin="<?php echo $p;?>" class="divprovider">
									<i class="wsiicon-<?php echo $p;?>"></i>
									<span style="display:none"><?php echo $p;?></span>
		                        </div>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
				<p class="help"><?php _e( 'Drag and drop to order widget providers', 'wsi' ); ?> - <span style="color: red;">Premium Only</span></p>
			</td>
		</tr>
		<tr valign="top" class="">
			<th><label><?php _e( 'Custom CSS', 'wsi' ); ?></label></th>
			<td colspan="3">
				<textarea cols="70" rows="15" name="wsi_settings[custom_css]" id="newcontent" aria-describedby="newcontent-description"><?php  echo $opts['custom_css'];?></textarea>
			</td>
		</tr>
		<tr valign="top" class="">
			<td colspan="4"><p><?php _e('Enter your custom CSS rules. By default WSI widget use the following structure:' ,'wsi');?></p></td>
		</tr>
	</table>
</div><!-- end emails tab-->
