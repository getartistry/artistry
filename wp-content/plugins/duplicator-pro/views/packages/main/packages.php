<?php

require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.system.global.entity.php');

if (isset($_REQUEST['create_from_temp']))
{
    $package = DUP_PRO_Package::get_temporary_package(false);
    if ($package != null)
    {
        $package->save();
    }
    unset($_REQUEST['create_from_temp']);
}

/* @var $system_global DUP_PRO_System_Global_Entity */
$system_global = DUP_PRO_System_Global_Entity::get_instance();

if (isset($_REQUEST['action']))
{
    if ($_REQUEST['action'] == 'stop-build')
    {
        $package_id = (int) $_REQUEST['action-parameter'];
        DUP_PRO_LOG::trace("stop build of $package_id");
        $action_package = DUP_PRO_Package::get_by_id($package_id);
        if ($action_package != null)
        {
            DUP_PRO_LOG::trace("set $action_package->ID for cancel");
            $action_package->set_for_cancel();
        }
        else
        {
            DUP_PRO_LOG::trace("could not find package so attempting hard delete. Old files may end up sticking around although chances are there isnt much if we couldnt nicely cancel it.");
            $result = DUP_PRO_Package::force_delete($package_id);
            ($result) ? DUP_PRO_LOG::trace("Hard delete success")
					  : DUP_PRO_LOG::trace("Hard delete failure");
        }
    }
	else if ($_REQUEST['action'] == 'clear-messages')
	{
		$system_global->clear_recommended_fixes();
	}
}

$pending_cancelled_package_ids = DUP_PRO_Package::get_pending_cancellations();
$qryResult = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}duplicator_pro_packages` ORDER BY id DESC", ARRAY_A);
$qryStatus = $wpdb->get_results("SELECT status FROM `{$wpdb->prefix}duplicator_pro_packages` WHERE status >= 100", ARRAY_A);
$totalElements = count($qryResult);
$statusCount = count($qryStatus);

/* @var $global DUP_PRO_Global_Entity */
$global = DUP_PRO_Global_Entity::get_instance();
$active_package_present = DUP_PRO_Package::is_active_package_present();

$orphan_info = DUP_PRO_Server::getOrphanedPackageInfo();
$orphan_display_msg = ($orphan_info['count'] > 3   ? 'display: block' : 'display: none');

$recommended_text_fix_present = false;
$package_ui_created = is_numeric($global->package_ui_created) ? $global->package_ui_created : 1;

if(count($system_global->recommended_fixes) > 0) 
{
	foreach($system_global->recommended_fixes as $fix)
	{
		/* @var $fix DUP_PRO_Recommended_Fix */
		if($fix->recommended_fix_type == DUP_PRO_Recommended_Fix_Type::Text)
		{				
			$recommended_text_fix_present = true;
		}
	}
}

if(isset($_GET['dpro_show_error']))
{
	$recommended_text_fix_present = true;
	$system_global->add_recommended_text_fix('Test Error', 'Test fix recommendation');
}
		
?>

<style>
    a.disabled { color:gray; }
    a.disabled:hover { color: gray!important; background:#e0e0e0 !important;}
    input#dpro-chk-all {margin:0;padding:0 0 0 5px;}
    button.dpro-btn-selected {border:1px solid #000 !important; background-color:#dfdfdf !important;}
    div.dpro-build-msg {padding:10px; border:1px solid #e5e5e5; border-radius: 3px; margin:0 0 0 0; text-align: center; font-size: 14px; line-height: 20px;}
    div.dpro-build-msg button {display: block; margin-top:10px !important; font-weight: bold;}
	div.dpro-build-msg div.status-hdr {font-size:18px; font-weight: bold}
	button.dpro-btn-stop {width:150px !important}

	.storage-badge[data-badge]:after {
		content: attr(data-badge); position: absolute; top: -12px; right: -14px; font-size: .6em; background: #DA0C0C; color: white; width: 15px; height: 15px; text-align: center;
		line-height: 15px; border-radius: 50%; box-shadow: 0 0 1px #333;
	 }
	 
	 /*Auto configuration*/
	 ul.dpro-auto-conf {margin-top:0; list-style-type:none}
	 ul.dpro-auto-conf li {margin-left:15px}

    /* Table package details */
    table.dpro-pktbl td.dpro-list-nopackages {text-align: center; padding:50px 0 80px 0; font-size:20px}
    table.dpro-pktbl {word-break:break-all;}
	table.dpro-pktbl tfoot th{font-size:12px}
    table.dpro-pktbl th {white-space:nowrap !important;}
    table.dpro-pktbl td.pack-name {width:100%}
    table.dpro-pktbl input[name="delete_confirm"] {margin-left:15px}
    table.dpro-pktbl td.run {border-left: 4px solid #608E64;}
    table.dpro-pktbl td.fail {border-left: 4px solid #d54e21;}
    table.dpro-pktbl td.pass {border-left: 4px solid #2ea2cc;}
    table.dpro-pktbl div#dpro-progress-bar-area {width:300px; margin:5px auto 0 auto;}
	/* Table package rows */
    tr.dpro-pkinfo td {white-space:nowrap; padding:10px 20px 10px 10px; min-height:20px; vertical-align: middle}
	tr.dpro-pkinfo td div.progress-error {font-size:13px; color:#555;}
    tr.dpro-pkinfo td.get-btns {text-align:center; padding:3px 4px 5px 0 !important; white-space: nowrap;}
	tr.dpro-pkinfo td.get-btns button {width:90px; padding:0; margin:2px 0 0 0}
	button.dpro-store-btn {width:35px !important} 
	div#dpro-error-orphans { <?php echo $orphan_display_msg; ?> }
	

</style>

<div id='dpro-error-orphans' class="error">
		<p>
			<?php 
				$orphan_msg  = DUP_PRO_U::__('There are currently (%1$s) orphaned package files taking up %2$s of space.  These package files are no longer visible in the packages list below and are safe to remove.') . '<br/>';
				$orphan_msg .= DUP_PRO_U::__('Go to: Tools > Diagnostics > Stored Data > look for the "Delete Package Orphans" button for more details.') . '<br/>';
				$orphan_msg .= '<a href=' . self_admin_url('admin.php?page=duplicator-pro-tools&tab=diagnostics') . '>' . DUP_PRO_U::__('Take me there now!') . '</a>';
				printf($orphan_msg,	$orphan_info['count'], DUP_PRO_U::byteSize($orphan_info['size']) );
			?> 
			<br/>
		</p>
</div>

<form id="form-duplicator" method="post">
<input type="hidden" id="action" name="action" />
<input type="hidden" id="action-parameter" name="action-parameter" />

<!-- ====================
TOOL-BAR -->
<table class="dpro-edit-toolbar">
	<tr>
		<td>
			<select id="dup-pack-bulk-actions">
				<option value="-1" selected="selected"><?php DUP_PRO_U::_e("Bulk Actions") ?></option>
				<option value="delete" title="<?php DUP_PRO_U::_e("Delete selected package(s)") ?>"><?php DUP_PRO_U::_e("Delete") ?></option>
			</select>
			<input type="button" id="dup-pack-bulk-apply" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" onclick="DupPro.Pack.ConfirmDelete()">
			<a href="?page=duplicator-pro-tools" id="btn-logs-dialog" class="button"  title="<?php DUP_PRO_U::_e("Logs") ?>"><i class="fa fa-list-alt"></i> </a>
		</td>
		<td>
			<span><i class="fa fa-archive"></i> <?php _e("All Packages"); ?></span>
			<a id="dup-pro-create-new" onclick="if (jQuery('#dup-pro-create-new').hasClass('disabled')) {
						alert('<?php echo DUP_PRO_U::__('A package is being processed. Retry later.'); ?>');
						return false;
					}" href="<?php echo $edit_package_url; ?>" class="add-new-h2 <?php echo ($active_package_present ? 'disabled' : ''); ?>"><?php DUP_PRO_U::_e('Create New'); ?></a>
		</td>
	</tr>
</table>

<div id="dup-pro-fixes" class="error" style="display: <?php echo $recommended_text_fix_present ? 'block' : 'none' ?>">
	<?php 
		if($recommended_text_fix_present)
		{
			echo '<p>';
			echo '<b style="font-size:18px">' . DUP_PRO_U::__('Duplicator Pro') . ' </b><br/>';
			echo '<b>' . DUP_PRO_U::__('Configuration Error(s) Detected:') . ' </b>';
			echo DUP_PRO_U::_e('Please perform the following actions below then build package again.');			
			echo '</p>';
			echo '<ul class="dpro-auto-conf">';
			foreach($system_global->recommended_fixes as $fix)
			{
				if($fix->recommended_fix_type == DUP_PRO_Recommended_Fix_Type::Text)
				{				
					echo "<li><i class='fa fa-question-circle' data-tooltip='{$fix->error_text}'></i>&nbsp; {$fix->parameter1} </li>";
				}
			}
			echo "</ul>";
			echo "<div style='margin-left:3px'><a href='#' onclick='DupPro.Pack.ClearMessages();'>" . DUP_PRO_U::__('Clear') . '</a></div>' ;
		}
	?>
</div>

<!-- ====================
LIST ALL PACKAGES -->
<table class="widefat dpro-pktbl">
	<thead>
		<tr>
			<th><input type="checkbox" id="dpro-chk-all"  title="<?php DUP_PRO_U::_e("Select all packages") ?>" style="margin-left:15px" onclick="DupPro.Pack.SetDeleteAll()" /></th>
			<th style='padding-right:25px'><?php DUP_PRO_U::_e("Origin") ?></th>
			<th style='padding-right:25px'><?php DUP_PRO_U::_e("Created") ?></th>
			<th style='padding-right:25px'><?php DUP_PRO_U::_e("Size") ?></th>
			<th><?php DUP_PRO_U::_e("Name") ?></th>
			<th style="text-align:right;padding-right:100px" colspan="3"><?php DUP_PRO_U::_e("Package") ?></th>
		</tr>
	</thead>

	<?php if ($totalElements == 0) : ?>
		<tr>
			<td colspan="7" class="dpro-list-nopackages">
				<br/>
				<i class="fa fa-archive"></i> 
				<?php DUP_PRO_U::_e("No Packages Found."); ?><br/>
				<?php DUP_PRO_U::_e("Click the 'Create New' button to build a package."); ?> <br/><br/>
			</td>
		</tr>
	<?php endif; ?>	

	<?php
	$rowCount = 0;
	$totalSize = 0;
	$rows = $qryResult;
	foreach ($rows as $row)
	{
		$Package = DUP_PRO_Package::get_from_json($row['package']);
		if (is_object($Package))
		{
			$pack_name = $Package->Name;
			$pack_archive_size = $Package->Archive->Size;
			$pack_namehash = $Package->NameHash;
		}
		else
		{
			$pack_archive_size = 0;
			$pack_name = 'unknown';
			$pack_namehash = 'unknown';
		}

		//Links
		$uniqueid = "{$row['name']}_{$row['hash']}";
		$detail_id = "duplicator-detail-row-{$rowCount}";
		$css_alt = ($rowCount % 2 != 0) ? '' : 'alternate';

		$remote_display   = $Package->contains_non_default_storage();
		$storage_problem  = (($Package->Status == DUP_PRO_PackageStatus::STORAGE_CANCELLED) || ($Package->Status == DUP_PRO_PackageStatus::STORAGE_FAILED));
		$archive_exists   = ($Package->get_local_package_file(DUP_PRO_Package_File_Type::Archive, true) != null);
		$installer_exists = ($Package->get_local_package_file(DUP_PRO_Package_File_Type::Installer, true) != null);
		
		$non_default_count = 0;
		$package_type_style = '';
		$progress_error = '';

		switch($Package->Type)
		{
			case DUP_PRO_PackageType::MANUAL:
				$package_type_string = DUP_PRO_U::__('Manual');
				break;
			case DUP_PRO_PackageType::SCHEDULED:
				$package_type_string = DUP_PRO_U::__('Schedule');
				break;
			case DUP_PRO_PackageType::RUN_NOW:
				$package_type_style = 'style="padding-top:8px"';
				$package_type_string = '<span>' . DUP_PRO_U::__('Schedule') . ' <sup>R</sup><span>';
				break;
			default:
				$package_type_string = DUP_PRO_U::__('Unknown');
				break;
		}
		?>

		
		<?php if (($row['status'] >= 100) || ($storage_problem)) : ?>
			<!-- COMPLETE -->
			<tr class="dpro-pkinfo <?php echo $css_alt ?>" id="duppro-packagerow-<?php echo $row['id']; ?>">
				<td class="pass"><input name="delete_confirm" type="checkbox" id="<?php echo $row['id']; ?>" /></td>
				<td <?php echo $package_type_style; ?>><?php echo $package_type_string; ?></td>
				<td><?php echo DUP_PRO_Package::format_created_date($row['created'], $package_ui_created); ?></td>
				<td><?php echo DUP_PRO_U::byteSize($pack_archive_size); ?></td>
				<td class='pack-name'><?php echo $pack_name; ?></td>
				<td class="get-btns">
					<button <?php DUP_PRO_UI::echoDisabled(!$installer_exists) ?> id="<?php echo "{$uniqueid}_{$global->installer_base_name}" ?>" class="button no-select" onclick="DupPro.Pack.DownloadPackageFile(0, <?php echo $Package->ID; ?>); return false;"  title="<?php if(!$installer_exists){DUP_PRO_U::_e("Download not accessible from here");} ?>">
						<i class="fa <?php echo ($installer_exists ? 'fa-bolt' : 'fa-exclamation-triangle') ?>"></i> <?php DUP_PRO_U::_e("Installer") ?>
					</button> 

					<button <?php DUP_PRO_UI::echoDisabled(!$archive_exists) ?> id="<?php echo "{$uniqueid}_archive.zip" ?>" class="button no-select" onclick="location.href='<?php echo $Package->Archive->get_url();  ?>'; return false;"  title="<?php if(!$archive_exists){DUP_PRO_U::_e("Download not accessible from here");} ?>">
						<i class="fa <?php echo ($archive_exists ? 'fa-file-archive-o' : 'fa-exclamation-triangle') ?>"></i> <?php DUP_PRO_U::_e("Archive") ?>
					</button> 

					<!-- REMOTE STORE BUTTON -->
					<?php if ($storage_problem) : ?>
						<button type="button" id="<?php echo "{$uniqueid}_archive.zip" ?>" class="dpro-store-btn button no-select" onclick="DupPro.Pack.ShowRemote(<?php echo "$Package->ID, '$Package->NameHash'"; ?>);" title="<?php DUP_PRO_U::_e("Storage download not accessible from here") ?>">
							<i class="fa fa-exclamation-triangle" style="color:#A65559"></i> 
						</button>
					<?php elseif ($remote_display) : ?>
						<button type="button" id="<?php echo "{$uniqueid}_archive.zip" ?>" class="dpro-store-btn button no-select" onclick="DupPro.Pack.ShowRemote(<?php echo "$Package->ID, '$Package->Name'"; ?>);" title="<?php DUP_PRO_U::_e("Storage") ?>">
							<i data-badge="<?php echo $non_default_count; ?>" class="fa fa-database <?php echo $non_default_count > 0 ? 'storage-badge' : '' ?>" ></i> 
						</button>	
					<?php else : ?>							
						<button type="button" disabled="disabled"  class="dpro-store-btn button no-select" title="<?php DUP_PRO_U::_e("Saved only to default storage") ?>">
							<i data-badge="<?php echo $non_default_count; ?>" class="fa fa-database <?php echo $non_default_count > 0 ? 'storage-badge' : '' ?>" ></i> 
						</button>
					<?php endif; ?>

					<button type="button" class="dpro-store-btn button no-select" title="<?php DUP_PRO_U::_e("Package Details") ?>" onclick="DupPro.Pack.OpenPackageDetails(<?php echo "$Package->ID"; ?>);">
						<i class="fa fa-archive" ></i> 
					</button>
				</td>					
			</tr>			
		<?php
		// NOT COMPLETE
		else : 

			if($row['status'] < DUP_PRO_PackageStatus::COPIEDPACKAGE)
			{     
				// In the process of building 
				$size = 0;
				$tmpSearch = glob(DUPLICATOR_PRO_SSDIR_PATH_TMP . "/{$pack_namehash}_*");
				
				if (is_array($tmpSearch))
				{
					$result = @array_map('filesize', $tmpSearch);
					$size = array_sum($result);
				}
				$pack_archive_size = $size;				
			}

			// If its in the pending cancels consider it stopped
			$status = $row['status'];
			$id = (int) $row['id'];

			if (in_array($id, $pending_cancelled_package_ids))
			{
				$status = DUP_PRO_PackageStatus::PENDING_CANCEL;
			}
			
			if ($status >= 0)
			{				
				$progress_css = 'run';
				if ($status >= 75)
				{
					$stop_button_text = DUP_PRO_U::__('Stop Transfer');
					$progress_html =  "<i class='fa fa-refresh fa-spin'></i> <span id='status-progress-{$id}'>0</span>%"
									. "<span style='display:none' id='status-{$id}'>{$status}</span>";
				}
				else if($status > 0)
				{
					$stop_button_text = DUP_PRO_U::__('Stop Build');
					$progress_html = "<i class='fa fa-gear fa-spin'></i> <span id='status-{$id}'>{$status}</span>%";
				}
				else
				{
					// In a pending state
					$stop_button_text = DUP_PRO_U::__('Cancel Pending');
					$progress_html = " <span style='display:none' id='status-{$id}'>{$status}</span>";
				}
			}
			else
			{
				/** FAILURES AND CANCELLATIONS **/
				$progress_css = 'fail';
				
				if ($status == DUP_PRO_PackageStatus::ERROR)
				{
					$progress_error = '<div class="progress-error"><i class="fa fa-exclamation-triangle"></i> <a href="#" onclick="DupPro.Pack.OpenPackageDetails(' . $Package->ID . '); return false;">' . DUP_PRO_U::__('Error Processing') . "</a></div><span style='display:none' id='status-$id'>$status</span>";
				}
				else if ($status == DUP_PRO_PackageStatus::BUILD_CANCELLED)
				{
					$progress_error = '<div class="progress-error"><i class="fa fa-exclamation-triangle"></i> ' . DUP_PRO_U::__('Build Cancelled') . "</div><span style='display:none' id='status-$id'>$status</span>";
				}
				else if ($status == DUP_PRO_PackageStatus::PENDING_CANCEL)
				{
					$progress_error = '<div class="progress-error"><i class="fa fa-exclamation-triangle"></i> ' . DUP_PRO_U::__('Cancelling Build') . "</div><span style='display:none' id='status-$id'>$status</span>";
				} 
				else if ($status == DUP_PRO_PackageStatus::REQUIREMENTS_FAILED)
				{
					$progress_error = '<div class="progress-error"><i class="fa fa-exclamation-triangle"></i> ' . DUP_PRO_U::__('Requirements Failed') . "</div><span style='display:none' id='status-$id'>$status</span>";
				}
			}
			?>
			
			<tr class="dpro-pkinfo  <?php echo $css_alt ?>" id="duppro-packagerow-<?php echo $row['id']; ?>">
				<?php if ($status >= 0) : ?>
				   <td class="<?php echo $progress_css ?>"><input name="delete_confirm" type="checkbox" id="<?php echo $row['id']; ?>" /></td>
				<?php else : ?>
					<td class="<?php echo $progress_css ?>"><input name="delete_confirm" type="checkbox" id="<?php echo $row['id']; ?>" /></td>
				<?php endif; ?>
				<td><?php echo (($Package->Type == DUP_PRO_PackageType::MANUAL) ? DUP_PRO_U::__('Manual') : DUP_PRO_U::__('Schedule')); ?></td>
				<td><?php echo DUP_PRO_Package::format_created_date($row['created'], $package_ui_created); ?></td>
				<td><?php echo $Package->get_display_size(); ?></td>
				<td class='pack-name'><?php echo $pack_name; ?></td>
				<td class="get-btns" colspan="3">
					<?php if ($status >= 75) : ?>
						<button id="<?php echo "{$uniqueid}_{$global->installer_base_name}" ?>" <?php DUP_PRO_UI::echoDisabled(!$installer_exists); ?> class="button no-select" onclick="DupPro.Pack.DownloadPackageFile(0, <?php echo $Package->ID; ?>); return false;">
							<i class="fa <?php echo ($installer_exists ? 'fa-bolt' : 'fa-exclamation-triangle') ?>"></i> <?php DUP_PRO_U::_e("Installer") ?>
						</button>
						<button id="<?php echo "{$uniqueid}_archive.zip" ?>" <?php DUP_PRO_UI::echoDisabled(!$archive_exists); ?> class="button no-select"  onclick="location.href = '<?php echo $Package->Archive->get_url(); ?>'; return false;">
							<i class="fa <?php echo ($archive_exists ? 'fa-file-archive-o' : 'fa-exclamation-triangle') ?>"></i> <?php DUP_PRO_U::_e("Archive") ?>
						</button>
						<button type="button" disabled="disabled"  class="dpro-store-btn button no-select" >
							<i class="fa fa-refresh fa-spin" ></i> 
						</button>
						<button type="button" class="dpro-store-btn button no-select" title="<?php DUP_PRO_U::_e("Package Details") ?>" onclick="DupPro.Pack.OpenPackageDetails(<?php echo "$Package->ID"; ?>);">
							<i class="fa fa-archive" ></i> 
						</button>
					<?php else : ?>
						<?php if ($status == 0): ?>                                    
							<button onclick="DupPro.Pack.StopBuild(<?php echo $row['id']; ?>); return false;" class="button button-large dpro-btn-stop">
								<i class="fa fa-close"></i> &nbsp; <?php echo $stop_button_text; ?>
							</button>
						<?php else: ?>
							   <?php echo $progress_error; ?> 
						<?php endif;?>
					<?php endif; ?>
				</td>
			</tr>
			
			<?php if ($status == 0) : ?>
				<!--   NO DISPLAY -->
			<?php elseif ($status > 0) : ?>                    
				<tr>
					<td colspan="8" class="run <?php echo $css_alt ?>">
						<div class="wp-filter dpro-build-msg">
							
							<?php if ($status < 75) : ?>
								<!-- BUILDING PROGRESS-->
								<div id='dpro-progress-status-message-build'>
									<?php 
										echo "<div class='status-hdr'>" . DUP_PRO_U::__("Building Package") . " {$progress_html}</div>"; 
										echo '<small>' .	DUP_PRO_U::__("Please allow it to finish before creating another one.") . '</small>' 
									?> <br/>
								</div> 
							<?php else : ?>
								<!-- TRANSFER PROGRESS -->
								<div id='dpro-progress-status-message-transfer'>
									<?php 
										echo "<div class='status-hdr'>" . DUP_PRO_U::__("Transferring Package") . " {$progress_html}</div>"; 
										echo '<small id="dpro-progress-status-message-transfer-msg">' . DUP_PRO_U::__("Getting Transfer State...") . '</span>' 
									?> <br/>
								</div>									
							<?php endif; ?>
								
							<script>
								jQuery(document).ready(function ($) {
									DupPro.UI.AnimateProgressBar('dpro-progress-bar');
								});
							</script>
							<div id="dpro-progress-bar-area">
								<div id="dpro-progress-bar"></div>
							</div>
							<button onclick="DupPro.Pack.StopBuild(<?php echo $row['id']; ?>); return false;" class="button button-large dpro-btn-stop">
								<i class="fa fa-close"></i> &nbsp; <?php echo $stop_button_text; ?>
							</button>
						</div>
					</td>
				</tr>
			<?php else: ?>
				<!--   NO DISPLAY -->
			<?php endif; ?>

		<?php endif; ?>
		<?php
		$totalSize = $totalSize + $pack_archive_size;
		$rowCount++;
	}
	?>
	<tfoot>
		<tr>
			<th colspan="6"  style='text-align:right;'>						
				<?php 
					echo DUP_PRO_U::__("Packages")		. ': ' . $totalElements .  ' | ';
					echo DUP_PRO_U::__("Size")			. ': ' . DUP_PRO_U::byteSize($totalSize).  ' | ';
					echo DUP_PRO_U::__("Time")			. ': <span id="dpro-clock-container"></span>'; 
				?> 
			</th>
		</tr>
	</tfoot>
</table>
</form>

<!-- ==========================================
THICK-BOX DIALOGS: -->

<?php
	$remoteDlg = new DUP_PRO_UI_Dialog();
	$remoteDlg->width	= 650;
	$remoteDlg->height	= 350;
	$remoteDlg->title	= DUP_PRO_U::__('Remote Storage Locations');
	$remoteDlg->message	= DUP_PRO_U::__('Loading Please Wait...');
	$remoteDlg->initAlert();

	$alert1 = new DUP_PRO_UI_Dialog();
	$alert1->title		= DUP_PRO_U::__('Bulk Action Required');
	$alert1->message	= DUP_PRO_U::__('Please select an action from the "Bulk Actions" drop down menu!');
	$alert1->initAlert();
	
	$alert2 = new DUP_PRO_UI_Dialog();
	$alert2->title		= DUP_PRO_U::__('Selection Required');
	$alert2->message	= DUP_PRO_U::__('Please select at least one package to delete!');
	$alert2->initAlert();
	
	$confirm1 = new DUP_PRO_UI_Dialog();
	$confirm1->title			= DUP_PRO_U::__('Delete Packages?');
	$confirm1->message			= DUP_PRO_U::__('Are you sure, you want to delete the selected package(s)?');
	$confirm1->progressText     = DUP_PRO_U::__('Removing Packages, Please Wait...');
	$confirm1->jsCallback		= 'DupPro.Pack.Delete()';
	$confirm1->initConfirm();
?>

<script type="text/javascript">
jQuery(document).ready(function ($) 
{
	DupPro.Pack.StorageTypes = 
	{
		local: 0,
		dropbox: 1,
		ftp: 2,
		gdrive: 3,
		s3: 4
	}
	
	/*	Creats a comma seperate list of all selected package ids  */
	DupPro.Pack.GetDeleteList = function () 
	{
		var arr = new Array;
		var count = 0;
		$("input[name=delete_confirm]").each(function () {
			if (this.checked) {
				arr[count++] = this.id;
			}
		});
		var list = arr.join(',');
		return list;
	}
	
	
	/*	Provides the correct confirmation items when deleting packages */
	DupPro.Pack.ConfirmDelete = function () 
	{
		$('#dpro-dlg-confirm-delete-btns input').removeAttr('disabled');
		if ($("#dup-pack-bulk-actions").val() != "delete") 
		{
			<?php $alert1->showAlert(); ?>
			return;
		}
		
		var list = DupPro.Pack.GetDeleteList();
		if (list.length == 0) 
		{
			<?php $alert2->showAlert(); ?>
			return;
		}
		<?php $confirm1->showConfirm(); ?>
	}
	
	
	/*	Removes all selected package sets with ajax call  */
	DupPro.Pack.Delete = function () 
	{

		var list = DupPro.Pack.GetDeleteList();

		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			data: {action: 'duplicator_pro_package_delete', duplicator_pro_delid: list},
			success: function (data) {
				DupPro.ReloadWindow(data);
			}
		});
	}
	
	
	/* Toogles the Bulk Action Check boxes */
	DupPro.Pack.SetDeleteAll = function () 
	{
		var state = $('input#dpro-chk-all').is(':checked') ? 1 : 0;
		$("input[name=delete_confirm]").each(function () {
			this.checked = (state) ? true : false;
		});
	}
	
	
	/* Stops the build from running */
	DupPro.Pack.StopBuild = function (packageID) 
	{
		$('#action').val('stop-build');
		$('#action-parameter').val(packageID);
		$('#form-duplicator').submit();
	}
	
	
	/* Clears and auto-detection messages */
	DupPro.Pack.ClearMessages = function() 
	{
		$('#action').val('clear-messages');
		$('#form-duplicator').submit();
	}
	

	/*	Redirects to the packages detail screen */
	DupPro.Pack.OpenPackageDetails = function (package_id) 
	{
		window.location.href = '?page=duplicator-pro&action=detail&tab=detail&id=' + package_id;
	}

	/* Shows remote storage location dialogs */
	DupPro.Pack.ShowRemote = function (package_id, name) 
	{
		<?php 
			$remoteDlg->showAlert();
		?>
		
		var data = {action: 'duplicator_pro_get_storage_details', package_id: package_id}

		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			timeout: 10000000,
			data: data,
			complete: function () {},
			success: function (data) {
				if (data.succeeded)
				{
					var info = '';
					for (storage_provider_key in data.storage_providers) {
						var store = data.storage_providers[storage_provider_key];
						var styling = "margin-bottom:14px";
						
						if(store.failed) 
						{
							failed_string = " (<?php DUP_PRO_U::_e('failed'); ?>)";
							styling += ";color:#A62426";
						}
						else 
						{
							failed_string = "";
						}
						
						if(store.cancelled) 
						{
							cancelled_string = " (<?php DUP_PRO_U::_e('cancelled'); ?>)";
							styling += ";color:#A62426";
						}
						else 
						{
							cancelled_string = "";
						}

						if ((store.storage_type == DupPro.Pack.StorageTypes.local) && (store.id != -2)) 
						{
							info += "<div style='" + styling + "'>";
							info += "<strong>Local Endpoint: '" + store.name + failed_string + cancelled_string + "'</strong><br/>";
							info += "<span>Location: " + store.storage_location_string + "</span><br/>";
							info += "</div>"
						}
						else if (store.storage_type == DupPro.Pack.StorageTypes.ftp) {
							var ftp_url = "<a href='" + encodeURI(store.storage_location_string) + "' target='_blank'>" + store.storage_location_string + "</a>";
							info += "<div style='" + styling + "'>";
							info += "<strong>FTP Endpoint: '" + store.name + failed_string + cancelled_string + "'</strong><br/>";
							info += "<span>Server: " + store.ftp_server + "</span><br/>";
							info += "<span>Location: " + ftp_url + "</span><br/>";
							info += "</div>"
						}
						else if (store.storage_type == DupPro.Pack.StorageTypes.dropbox) {
							var dbox_url = "<a href='" + store.storage_location_string + "' target='_blank'>" + store.storage_location_string + "</a>";
							info += "<div style='" + styling + "'>";
							info += "<strong>Dropbox Endpoint: '" + store.name + failed_string + cancelled_string + "'</strong><br/>";
							info += "<span>Location: " + dbox_url + "</span><br/>";
							info += "</div>"
						}
						else if (store.storage_type == DupPro.Pack.StorageTypes.gdrive) {
							//var gdrive_url = "<a href='" + store.gdrive_storage_url + "' target='_blank'>" + store.storage_location_string + "</a>";
							var gdrive_url = store.storage_location_string;
							info += "<div style='" + styling + "'>";
							info += "<strong>Google Drive Endpoint: '" + store.name + failed_string + cancelled_string + "'</strong><br/>";
							info += "<span>Location: " + gdrive_url + "</span><br/>";
							info += "</div>"
						} else if (store.storage_type == DupPro.Pack.StorageTypes.s3) {
							info += "<div style='" + styling + "'>";
							info += "<strong>Amazon S3 Endpoint: '" + store.name + failed_string + cancelled_string + "'</strong><br/>";
							info += "<span>Location: " + store.storage_location_string + "</span><br/>";
							info += "</div>"
						}
					}
			
					$('#TB_window .dpro-dlg-alert-txt').html(info);
					
				}
				else
				{
					alert("Got an error or a warning: " + data.message);
				}
			},
			error: function (data) {
				alert("Failed to get details.");
				console.log(data);
			}
		});
		return false;
	}
	
	
	/*  Virtual states that UI uses for easier tracking of the three general states a package can be in*/
	DupPro.Pack.ProcessingStats = 
	{
		PendingCancellation: -3,
		Pending: 0,            
		Building: 1,
		Storing: 2,
		Finished: 3,
	}
	

	DupPro.Pack.packageCount = -1;
	DupPro.Pack.setIntervalID = -1;
	
	DupPro.Pack.SetUpdateInterval = function(period) 
	{
		console.log('setting interval to '+ period);
		if(DupPro.Pack.setIntervalID != -1) {
			clearInterval(DupPro.Pack.setIntervalID);
			DupPro.Pack.setIntervalID = -1
		}
		DupPro.Pack.setIntervalID = setInterval(DupPro.Pack.UpdateUnfinishedPackages, period * 1000);
	}


	DupPro.Pack.UpdateUnfinishedPackages = function () 
	{
		var data = {action: 'duplicator_pro_get_package_statii'}

		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			timeout: 10000000,
			data: data,
			complete: function () { },
			success: function (data) {
				var activePackagePresent = false;

				if(DupPro.Pack.packageCount == -1) 
				{
					DupPro.Pack.packageCount = data.length
				} 
				else 
				{
					if(DupPro.Pack.packageCount != data.length) {
						window.location = window.location.href;
					}
				}
				for (package_info_key in data) {
					var package_info = data[package_info_key];
					var statusSelector = '#status-' + package_info.ID;
					var packageRowSelector = '#duppro-packagerow-' + package_info.ID;
					var packageSizeSelector = packageRowSelector + ' td:nth-child(4)';
					var current_value_string = $(statusSelector).text();
					var current_value = parseInt(current_value_string);
					var currentProcessingState;

					if(current_value == -3) {
						currentProcessingState = DupPro.Pack.ProcessingStats.PendingCancellation;
					}
					else if(current_value == 0) {
						currentProcessingState = DupPro.Pack.ProcessingStats.Pending;
					}
					else if ((current_value >= 0) && (current_value < 75)) {
						currentProcessingState = DupPro.Pack.ProcessingStats.Building;
					} 
					else if ((current_value >= 75) && (current_value < 100)) {
						currentProcessingState = DupPro.Pack.ProcessingStats.Storing;
					}
					else
					{
						// Has to be negative(error) or 100 - both mean complete
						currentProcessingState = DupPro.Pack.ProcessingStats.Finished;
					}
					if(currentProcessingState == DupPro.Pack.ProcessingStats.Pending) 
					{
						if(package_info.status != 0)
						{
							window.location = window.location.href;
						}
					}
					else if (currentProcessingState == DupPro.Pack.ProcessingStats.Building) 
					{
						if ((package_info.status >= 75) || (package_info.status < 0))
						{
							// Transitioned to storing so refresh
							window.location = window.location.href;
							break;
						} else {
							<?php if (($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) || ($global->ziparchive_mode == DUP_PRO_ZipArchive_Mode::SingleThread)) : ?>
									package_info.size = "<?php DUP_PRO_U::_e('Building') ?>";
							<?php endif;?>

							activePackagePresent = true;
							$(statusSelector).text(package_info.status);
							$(packageSizeSelector).hide().fadeIn(1000).text(package_info.size);
						}
					} 
					else if (currentProcessingState == DupPro.Pack.ProcessingStats.Storing) 
					{
						if ((package_info.status == 100) || (package_info.status < 0))
						{
							// Transitioned to storing so refresh
							window.location = window.location.href;
							break;
						} else {
							activePackagePresent = true;
							$('#dpro-progress-status-message-transfer-msg').html(package_info.status_progress_text);
							var statusProgressSelector = '#status-progress-' + package_info.ID;
							$(statusProgressSelector).text(package_info.status_progress);
							console.log("status progress: " + package_info.status_progress);
						}
					} 
					else if(currentProcessingState == DupPro.Pack.ProcessingStats.PendingCancellation) 
					{
						if((package_info.status == -2) || (package_info.status == -4)) {
							// refresh when its gone to cancelled
							window.location = window.location.href;
						}                                
					}
					else if(currentProcessingState == DupPro.Pack.ProcessingStats.Finished) 
					{
						// IF something caused the package to come out of finished refresh everything (has to be out of finished or error state)
						if((package_info.status != 100) && (package_info.status > 0))
						{
							window.location = window.location.href;
						}
					}
				}

				if (activePackagePresent) 
				{
					$('#dup-pro-create-new').addClass('disabled');
					DupPro.Pack.SetUpdateInterval(10);
				} else {
					$('#dup-pro-create-new').removeClass('disabled');
					// Kick refresh down to 60 seconds if nothing is being actively worked on
					DupPro.Pack.SetUpdateInterval(60);                        
				}
			},
			error: function (data) {
				DupPro.Pack.SetUpdateInterval(60);
				console.log(data);
			}
		});
	};
	
	//Init
	DupPro.UI.Clock(DupPro._WordPressInitTime);
	DupPro.Pack.UpdateUnfinishedPackages();

});
</script>
