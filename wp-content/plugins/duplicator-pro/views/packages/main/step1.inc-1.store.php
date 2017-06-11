<style>
	/*STORAGE: Area*/
    div.storage-filters {display:inline-block; padding: 0 10px 0 10px}
    sup#dpro-storage-title-count {display:inline-block; color: #444; font-weight: normal; margin-top:-3px }
	tr.storage-missing td, tr.storage-missing td a {color: #A62426 !important }
	div#dpro-store-title {padding-top:8px}
	div#dup-pack-storage-panel-area {margin:20px 0 -5px 0}
</style>

<!-- ===================
META-BOX: STORAGE -->
<div class="dup-box" id="dup-pack-storage-panel-area">
<div class="dup-box-title" id="dpro-store-title">
	<i class="fa fa-database"></i> <?php DUP_PRO_U::_e('Storage') ?> <sup id="dpro-storage-title-count"></sup>
	<div class="dup-box-arrow"></div>
</div>			

<div class="dup-box-panel" id="dup-pack-storage-panel" style="<?php echo $ui_css_storage ?>">
	<table class="widefat package-tbl">
		<thead>
			<tr>
				<th style='white-space: nowrap; width:10px;'></th>
				<th style='width:275px'><?php DUP_PRO_U::_e('Name') ?></th>
				<th style='width:100px'><?php DUP_PRO_U::_e('Type') ?></th>
				<th style="white-space: nowrap"><?php DUP_PRO_U::_e('Location') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;

			foreach ($storage_list as $store) :
				$i++;
				$store_type = $store->get_storage_type_string();
				$store_location = $store->get_storage_location_string();
				$is_valid = $store->is_valid();
				$is_checked = in_array($store->id, $global->manual_mode_storage_ids) && $is_valid;				
				$mincheck   = ($i == 1) ?'data-parsley-mincheck="1" data-parsley-required="true"' : '';
				$row_style  = ($i % 2) ? 'alternate' : '';
				$row_style .= ($is_valid) ? '' : ' storage-missing';
				?>
				<tr class="package-row <?php echo $row_style ?>">
					<td>
						<input class="duppro-storage-input" <?php echo DUP_PRO_UI::echoDisabled($is_valid == false); ?> name="_storage_ids[]" onclick="DupPro.Pack.UpdateStorageCount(); return true;" data-parsley-errors-container="#storage_error_container" <?php echo $mincheck; ?> type="checkbox" value="<?php echo $store->id; ?>" <?php DUP_PRO_UI::echoChecked($is_checked); ?> />
						<input name="edit_id" type="hidden" value="<?php echo $i ?>" />
					</td>
					<td>
						<a href="?page=duplicator-pro-storage&tab=storage&inner_page=edit&storage_id=<?php echo $store->id ?>" target="_blank">
							<?php 
								echo ($is_valid == false) 
									? '<i class="fa fa-exclamation-triangle"></i>' 
									: (($store_type == 'Local') 
									? '<i class="fa fa-server"></i>' 
									: '<i class="fa fa-cloud"></i>'); 
								echo " {$store->name}";
							?>
						</a>
					</td>
					<td><?php echo $store_type ?></td>
					<td><?php echo (($store_type == 'Local') || ($store_type == 'Google Drive'))
								? $store_location
								: "<a href='{$store_location}' target='_blank'>" . urldecode($store_location) . "</a>"; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div style="text-align: right; margin:4px 4px -4px 0; padding:0; width: 100%">
		<a href="admin.php?page=duplicator-pro-storage&tab=storage&inner_page=edit" target="_blank">
			[<?php DUP_PRO_U::_e('Add Storage') ?>]
		</a>
	</div>
</div>
</div>

<div id="storage_error_container" class="duplicator-error-container"></div>
<br/>

<script>
jQuery(function($) 
{
	DupPro.Pack.UpdateStorageCount = function () 
		{
		var store_count = $('#dup-pack-storage-panel input[name="_storage_ids[]"]:checked').size();
		$('#dpro-storage-title-count').html('(' + store_count + ')');
		(store_count == 0)
				? $('#dpro-storage-title-count').css({'color': 'red', 'font-weight': 'bold'})
				: $('#dpro-storage-title-count').css({'color': '#444', 'font-weight': 'normal'});
	}
});

//INIT
jQuery(document).ready(function ($) 
{
	DupPro.Pack.UpdateStorageCount();
});
</script>