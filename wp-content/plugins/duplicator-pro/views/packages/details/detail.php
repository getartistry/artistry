<?php
$package = DUP_PRO_Package::get_by_id($package_id);
$global = DUP_PRO_Global_Entity::get_instance();

$view_state = DUP_PRO_UI_ViewState::getArray();
$ui_css_general = (isset($view_state['dup-package-dtl-general-panel']) && $view_state['dup-package-dtl-general-panel']) ? 'display:block' : 'display:none';
$ui_css_storage = (isset($view_state['dup-package-dtl-storage-panel']) && $view_state['dup-package-dtl-storage-panel']) ? 'display:block' : 'display:none';
$ui_css_archive = (isset($view_state['dup-package-dtl-archive-panel']) && $view_state['dup-package-dtl-archive-panel']) ? 'display:block' : 'display:none';
$ui_css_install = (isset($view_state['dup-package-dtl-install-panel']) && $view_state['dup-package-dtl-install-panel']) ? 'display:block' : 'display:none';

$link_sql			= "{$package->StoreURL}{$package->NameHash}_database.sql";
$link_archive 		= "{$package->StoreURL}{$package->NameHash}_archive.zip";
$link_installer		= "{$package->StoreURL}{$package->NameHash}_installer.php?get=1&file={$package->NameHash}_installer.php";
$link_log			= "{$package->StoreURL}{$package->NameHash}.log";
$link_scan			= "{$package->StoreURL}{$package->NameHash}_scan.json";

$lang_notset = DUP_PRO_U::__("- not set -");
?>

<style>
	/*COMMON*/
	div.tabs-panel {padding: 10px !important}
	div.toggle-box {float:right; margin: 5px 5px 5px 0}
	div.dup-box {margin-top: 15px; font-size:14px; clear: both}
	table.dpro-dtl-data-tbl {width:100%}
	table.dpro-dtl-data-tbl tr {vertical-align: top}
	table.dpro-dtl-data-tbl tr:first-child td {margin:0; padding-top:0 !important;}
	table.dpro-dtl-data-tbl td {padding:0 6px 0 0; padding-top:10px !important;}
	table.dpro-dtl-data-tbl td:first-child {font-weight: bold; width:150px}
	table.dpro-sub-list td:first-child {white-space: nowrap; vertical-align: middle; width: 70px !important;}
	table.dpro-sub-list td {white-space: nowrap; vertical-align:top; padding:0 !important; font-size:12px}
	div.dpro-box-panel-hdr {font-size:14px; display:block; border-bottom: 1px dotted #efefef; margin:5px 0 5px 0; font-weight: bold; padding: 0 0 5px 0}
	table.sub-table {padding:0 0 0 20px}
	tr.sub-item td {line-height:22px; font-size:13px}
	tr.sub-item i.fa {display:inline-block; margin-right:3px; width:15px}
	tr.sub-item td:first-child {padding:0 0 0 30px;}
	tr.sub-item-disabled td {color:silver}
	td.sub-section {border-bottom: 1px solid #efefef}
	
	/*GENERAL*/
	div#dpro-name-info, div#dup-version-info {display: none; font-size:11px; line-height:20px; margin:4px 0 0 0}
	div#dpro-name-info, div#dup-created-info {display: none; font-size:11px; line-height:20px; margin:4px 0 0 0}
	div#dpro-name-info {display: none; font-size:11px; line-height:20px; margin:4px 0 0 0}
	div#dpro-downloads-area {padding: 5px 0 5px 0; }
	div#dpro-downloads-msg {margin-bottom:-5px; font-style: italic}
	
	/*ARCHIVE*/
	div#dpro-filter-dir-userlist,	
	div#dpro-filter-dir-warning,		
	div#dpro-filter-dir-unreadable,	
	div#dpro-filter-file-userlist,	
	div#dpro-filter-file-warning,	
	div#dpro-filter-file-unreadable {display:none; margin-left:20px}
	
	/*INSTALLER*/
	div#dpro-pass-toggle {position: relative; margin: 0 0 0 15px; width:273px}
	input#secure-pass {border-radius:4px 0 0 4px; width:250px; height: 23px; margin:0}
	button.pass-toggle {
		height: 23px; width: 23px; position:absolute; top:0px; right:0px;
		border:1px solid silver;  border-radius:0 4px 4px 0;
		background: no-repeat center #efefef url('<?php echo plugins_url('duplicator-pro/assets/img/lock.png');?>');
	}
</style>

<?php if ($package_id == 0) :?>
	<div class="error below-h2"><p><?php DUP_PRO_U::_e("Invalid Package ID request.  Please try again!"); ?></p></div>
<?php endif; ?>
	
<div class="toggle-box">
	<a href="javascript:void(0)" onclick="DupPro.Pack.OpenAll()">[open all]</a> &nbsp; 
	<a href="javascript:void(0)" onclick="DupPro.Pack.CloseAll()">[close all]</a>
</div>
	
<!-- ===============================
GENERAL -->
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-archive"></i> <?php DUP_PRO_U::_e('General') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-general-panel" style="<?php echo $ui_css_general ?>">
	<table class='dpro-dtl-data-tbl'>
		<tr>
			<td><?php DUP_PRO_U::_e("Name") ?>:</td>
			<td>
				<a href="javascript:void(0);" onclick="jQuery('#dpro-name-info').toggle()"><?php echo $package->Name ?></a> 
				<div id="dpro-name-info">
					<b><?php DUP_PRO_U::_e("ID") ?>:</b> <?php echo $package->ID ?><br/>
					<b><?php DUP_PRO_U::_e("Hash") ?>:</b> <?php echo $package->Hash ?><br/>
					<b><?php DUP_PRO_U::_e("Full Name") ?>:</b> <?php echo $package->NameHash ?><br/>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php DUP_PRO_U::_e("Notes") ?>:</td>
			<td><?php echo strlen($package->Notes) ? $package->Notes : DUP_PRO_U::__("- no notes -") ?></td>
		</tr>
		<tr>
			<td><?php DUP_PRO_U::_e("Created") ?>:</td>
			<td>
				<?php if (strlen($package->Created)) : ?>
					<a href="javascript:void(0);" onclick="jQuery('#dup-created-info').toggle()"><?php echo $package->Created ?></a> 
		
					<div id="dup-created-info">
						<?php if (DUP_PRO_U::PHP53()) : ?>
							<?php
								$datetime1 = new DateTime($package->Created);
								$datetime2 = new DateTime(date("Y-m-d H:i:s"));
								$diff = $datetime1->diff($datetime2);
								$fulldate =  $diff->y . DUP_PRO_U::__(' years, ') . $diff->m . DUP_PRO_U::__(' months, ') . $diff->d . DUP_PRO_U::__(' days old');
								$fulldays =  $diff->format('%a') . DUP_PRO_U::__(' days old');
							?>
							<b><?php DUP_PRO_U::_e("Age"); ?>: </b> <?php echo $fulldate;?> <br/>
							<b><?php DUP_PRO_U::_e("Days");  ?>: </b> <?php echo $fulldays;?> <br/>
						<?php else : ?>
							<?php DUP_PRO_U::_e("Age display only visiable on PHP 5.3 or better"); ?>
						<?php endif; ?>
						
					</div>
				<?php else : ?>
					<?php DUP_PRO_U::_e("- not set in this version -"); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td><?php DUP_PRO_U::_e("Versions") ?>:</td>
			<td>
				<a href="javascript:void(0);" onclick="jQuery('#dup-version-info').toggle()"><?php echo $package->Version ?></a> 
				<div id="dup-version-info">
					<b><?php DUP_PRO_U::_e("WordPress") ?>:</b> <?php echo strlen($package->VersionWP) ? $package->VersionWP : DUP_PRO_U::__("- unknown -") ?><br/>
					<b><?php DUP_PRO_U::_e("PHP") ?>:</b> <?php echo strlen($package->VersionPHP) ? $package->VersionPHP : DUP_PRO_U::__("- unknown -") ?><br/>
					<b><?php DUP_PRO_U::_e("OS") ?>:</b> <?php echo strlen($package->VersionOS) ? $package->VersionOS : DUP_PRO_U::__("- unknown -") ?><br/>
                    <b><?php DUP_PRO_U::_e("Mysql") ?>:</b> 
                    <?php echo strlen($package->VersionDB) ? $package->VersionDB : DUP_PRO_U::__("- unknown -") ?> |
                    <?php echo strlen($package->Database->Comments) ? $package->Database->Comments : DUP_PRO_U::__('- unknown -') ?><br/>
				</div>
			</td>
		</tr>		
		<tr>
			<td><?php DUP_PRO_U::_e("Runtime") ?>:</td>
			<td><?php echo strlen($package->Runtime) ? $package->Runtime : DUP_PRO_U::__("error running"); ?></td>
		</tr>		
		<tr>
			<td><?php DUP_PRO_U::_e("Type") ?>:</td>
			<td><?php echo $package->get_type_string(); ?></td>
		</tr>			
		<tr>
			<td><?php DUP_PRO_U::_e("Files") ?>: </td>
			<td>
				<div id="dpro-downloads-area">
					<?php if ($error_display == 'none') :?>
						<?php if ($package->contains_storage_type(DUP_PRO_Storage_Types::Local)) :?>
							<button class="button" onclick="DupPro.Pack.DownloadPackageFile(0, <?php echo $package->ID ?>);return false;"><i class="fa fa-bolt"></i> Installer</button>						
							<button class="button" onclick="DupPro.Pack.DownloadPackageFile(1, <?php echo $package->ID ?>);return false;"><i class="fa fa-file-archive-o"></i> Archive - <?php echo $package->ZipSize ?></button>
							<button class="button" onclick="DupPro.Pack.DownloadPackageFile(2, <?php echo $package->ID ?>);return false;"><i class="fa fa-table"></i> &nbsp; SQL - <?php echo DUP_PRO_U::byteSize($package->Database->Size)  ?></button>
							
							<button class="button" onclick="DupPro.Pack.ShowLinksDialog(<?php echo "'{$link_sql}','{$link_archive}','{$link_installer}','{$link_log}'" ;?>);" class="thickbox"><i class="fa fa-lock"></i> &nbsp; <?php _e("Share File Links", 'duplicator')?></button>
						<?php else: ?>
							<!-- CLOUD ONLY FILES -->
							<div id="dpro-downloads-msg"><?php DUP_PRO_U::_e("These package files are in remote storage locations.  Please visit the storage provider to download.") ?></div> <br/>
							<button class="button" disabled="true"><i class="fa fa-exclamation-triangle"></i> Installer - <?php echo DUP_PRO_U::byteSize($package->Installer->Size) ?></button>						
							<button class="button" disabled="true"><i class="fa fa-exclamation-triangle"></i> Archive - <?php echo $package->ZipSize ?></button>
							<button class="button" disabled="true"><i class="fa fa-exclamation-triangle"></i> &nbsp; SQL - <?php echo DUP_PRO_U::byteSize($package->Database->Size)  ?></button>
							
						<?php endif; ?>
					<?php else: ?>
							<button class="button" onclick="DupPro.Pack.DownloadPackageFile(3, <?php echo $package->ID ?>);return false;"><i class="fa fa-list-alt"></i> &nbsp; Log </button>
					<?php endif; ?>
				</div>		
				<?php if ($error_display == 'none') :?>
				<table class="dpro-sub-list">
					<tr>
						<td><?php DUP_PRO_U::_e("Archive") ?>: </td>
						<td><a href="<?php echo $link_archive ?>"><?php echo $package->Archive->File ?></a></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Installer") ?>: </td>
						<td><a href="<?php echo $link_installer ?>"><?php echo $package->Installer->File ?></a></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Database") ?>: </td>
						<td><a href="<?php echo $link_sql ?>" target="file_results"><?php echo $package->Database->File ?></a></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Report") ?>: </td>
						<td><a href="<?php echo $link_scan ?>" target="file_results"><?php echo $package->ScanFile ?></a></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Build Log") ?>: </td>
						<td><a href="<?php echo $link_log ?>" target="file_results"><?php echo "{$package->NameHash}.log" ?></a></td>
					</tr>					
				</table>
				<?php endif; ?>
			</td>
		</tr>	
	</table>
</div>
</div>

<!-- ==========================================
DIALOG: SHARE LINKS -->
<?php add_thickbox(); ?>
<div id="dup-dlg-quick-path" title="<?php DUP_PRO_U::_e('Download Links'); ?>" style="display:none">
	<p>
		<i class="fa fa-lock"></i>
		<?php DUP_PRO_U::_e("The following links contain sensitive data.  Please share with caution!");	?>
	</p>
	
	<div style="padding: 0px 15px 15px 15px;">
		<a href="javascript:void(0)" style="display:inline-block; text-align:right" onclick="DupPro.Pack.GetLinksText()">[<?php DUP_PRO_U::_e('Select &amp; Copy'); ?>]</a> <br/>
		<textarea id="dpro-dlg-quick-path-data" style='border:1px solid silver; border-radius:3px; width:99%; height:225px; font-size:11px'></textarea><br/>
		<i style='font-size:11px'><?php DUP_PRO_U::_e("The database SQL script is a quick link to your database backup script.  An exact copy is also stored in the package."); ?></i>
	</div>
</div>

<!-- ===============================
STORAGE -->
<?php 
	$css_file_filter_on = $package->Archive->FilterOn == 1  ? '' : 'sub-item-disabled';
	$css_db_filter_on   = $package->Database->FilterOn == 1 ? '' : 'sub-item-disabled';
?>
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-database"></i> <?php DUP_PRO_U::_e('Storage') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-storage-panel" style="<?php echo $ui_css_storage ?>">
	<table class="widefat package-tbl">
		<thead>
			<tr>
				<th style='width:150px'><?php DUP_PRO_U::_e('Name') ?></th>
				<th style='width:100px'><?php DUP_PRO_U::_e('Type') ?></th>
				<th style="white-space: nowrap"><?php DUP_PRO_U::_e('Location') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 0;
				$latest_upload_infos = $package->get_latest_upload_infos();
				
				foreach ($latest_upload_infos as $upload_info) :
					$modifier_text = null;
					if($upload_info->has_completed(true) == false)
					{
						// For now not displaying any cancelled or failed storages
						continue;
					}

					$i++;
					$store = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);
					$store_type = $store->get_storage_type_string();
					$store_location = $store->get_storage_location_string();
					$row_style  = ($i % 2) ? 'alternate' : '';
					?>
					<tr class="package-row <?php echo $row_style ?>">
						<td>
							<a href="?page=duplicator-pro-storage&tab=storage&inner_page=edit&storage_id=<?php echo $store->id ?>" target="_blank">
								<?php 
									switch ($store->storage_type) {
										case DUP_PRO_Storage_Types::FTP :
										case DUP_PRO_Storage_Types::GDrive :
										case DUP_PRO_Storage_Types::S3 :
										case DUP_PRO_Storage_Types::Dropbox : echo '<i class="fa fa-cloud"></i>'; break;
										case DUP_PRO_Storage_Types::Local : echo '<i class="fa fa-server"></i>'; break;
									}
									echo " {$store->name}";
								?>
							</a>
						</td>
						<td><?php echo $store_type ?></td>
						<td><?php echo (($store_type == 'Local') || ($store_type == 'Google Drive') || ($store_type == 'Amazon S3'))
									? $store_location
									: "<a href='{$store_location}' target='_blank'>" . urldecode($store_location) . "</a>"; ?>
						</td>
					</tr>
				<?php endforeach; ?>	
				<?php if ($i == 0) : ?>
					<tr>
						<td colspan="3" style="text-align: center">
							<?php DUP_PRO_U::_e('- No storage locations associated with this package -'); ?>
						</td>
					</tr>
				<?php endif; ?>
		</tbody>
	</table>
</div>
</div>


<!-- ===============================
ARCHIVE -->
<?php 
	$css_file_filter_on = $package->Archive->FilterOn == 1  ? '' : 'sub-item-disabled';
	$css_db_filter_on   = $package->Database->FilterOn == 1 ? '' : 'sub-item-disabled';
?>
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-file-archive-o"></i> <?php DUP_PRO_U::_e('Archive') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-archive-panel" style="<?php echo $ui_css_archive ?>">

	<!-- FILES -->
	<div class="dpro-box-panel-hdr"><i class="fa fa-files-o"></i> <?php DUP_PRO_U::_e('FILES'); ?></div>
	<table class='dpro-dtl-data-tbl'>
		<tr>
			<td><?php DUP_PRO_U::_e("Build Mode") ?>: </td>
			<td>
				<?php 
					$zip_mode_string = DUP_PRO_U::__('Unknown');
					
					if (isset($package->ZipMode)) 
					{
						if($package->ZipMode == DUP_PRO_Archive_Build_Mode::ZipArchive)
						{
							$zip_mode_string = DUP_PRO_U::__("Zip Archive");
							
							if(isset($package->ziparchive_mode))
							{
								if($package->ziparchive_mode == DUP_PRO_ZipArchive_Mode::SingleThread)
								{
									$zip_mode_string = DUP_PRO_U::__("Zip Archive ST");	
								}
							}
						}
						else
						{
							$zip_mode_string = DUP_PRO_U::__("Shell Exec");
						}
					}
					
					echo $zip_mode_string;
				?>
			</td>
		</tr>			
		<tr>
			<td><?php DUP_PRO_U::_e("Filters") ?>: </td>
			<td><?php echo $package->Archive->FilterOn == 1 ? 'On' : 'Off'; ?></td>
		</tr>
		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_PRO_U::_e("Directories") ?>: </td>
			<td>
				<?php 
					//CUSTOM
					$title = DUP_PRO_U::__("User defined filted directories");
					$count = count($package->Archive->FilterInfo->Dirs->Instance);
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-dir-userlist').toggle(200)\" title='{$title}'><i class='fa fa-filter'></i>" . DUP_PRO_U::__('User Defined') . "</a>  <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-dir-userlist'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-dir-userlist'>" . implode('<br/>', $package->Archive->FilterInfo->Dirs->Instance) . "</div>";		
					
					//UNREADABLE
					$title = DUP_PRO_U::__("These paths are filtered because they are unreadable by the system");
					$count = count($package->Archive->FilterInfo->Dirs->Unreadable);
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-dir-unreadable').toggle(200)\" title='{$title}'><i class='fa fa-filter'></i>" . DUP_PRO_U::__('Unreadable') . "</a> <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-dir-unreadable'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-dir-unreadable'>" . implode('<br/>', $package->Archive->FilterInfo->Dirs->Unreadable) . "</div>";	
					
					//WARNING: This may lead to more questions, hold off on release
					/*$title = DUP_PRO_U::__("These paths are NOT filtered, but could be filtered on some operating systems");
					$count = count($package->Archive->FilterInfo->Dirs->Warning);
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-dir-warning').toggle(200)\" title='{$title}'><i class='fa fa-exclamation-triangle'></i>" . DUP_PRO_U::__('Warnings') . "</a> <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-dir-warning'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-dir-warning'>" . implode('<br/>', $package->Archive->FilterInfo->Dirs->Warning) . "</div>";	
					 */			
				?>
			</td>
		</tr>

		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_PRO_U::_e("Files") ?>: </td>
			<td>
				<?php 
					//CUSTOM
					$title = DUP_PRO_U::__("User defined filted directories");
					$count = count($package->Archive->FilterInfo->Files->Instance);
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-file-userlist').toggle(200)\" title='{$title}'><i class='fa fa-filter'></i>" . DUP_PRO_U::__('User Defined') . "</a> <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-file-userlist'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-file-userlist'>" . implode('<br/>', $package->Archive->FilterInfo->Files->Instance) . "</div>";
					
					//UNREADABLE
					$title = DUP_PRO_U::__("These paths are filtered because they are unreadable by the system");
					$count = count($package->Archive->FilterInfo->Files->Unreadable);
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-file-unreadable').toggle(200)\" title='{$title}'><i class='fa fa-filter'></i>" . DUP_PRO_U::__('Unreadable') . "</a> <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-file-unreadable'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-file-unreadable'>" . implode('<br/>', $package->Archive->FilterInfo->Files->Unreadable) . "</div>";	
					
					//WARNING: This may lead to more questions, hold off on release unless needed
					/*$count = count($package->Archive->FilterInfo->Files->Warning);
					$title = DUP_PRO_U::__("These paths are NOT filtered, but could be filtered on some operating systems");
					echo "<a href='javascript:void(0)' onclick=\"jQuery('#dpro-filter-file-warning').toggle(200)\" title='{$title}'><i class='fa fa-exclamation-triangle'></i>" . DUP_PRO_U::__('Warnings') . "</a> <sup>({$count})</sup><br/>";
					echo ($count == 0) 
						 ? "<div id='dpro-filter-file-warning'>" . DUP_PRO_U::__('- filter type not found -') . "</div>"
						 : "<div id='dpro-filter-file-warning'>" . implode('<br/>', $package->Archive->FilterInfo->Files->Warning) . "</div>";*/
				?>					
			</td>
		</tr>		
		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_PRO_U::_e("Extensions") ?>: </td>
			<td>
				<?php
					echo isset($package->Archive->Extensions) && strlen($package->Archive->Extensions) 
						? $package->Archive->Extensions
						: DUP_PRO_U::__('- no filters -');
				?>
			</td>
		</tr>
	</table><br/>

	<!-- DATABASE -->
	<div class="dpro-box-panel-hdr"><i class="fa fa-table"></i> <?php DUP_PRO_U::_e('DATABASE'); ?></div>
	<table class='dpro-dtl-data-tbl'>
		<tr>
			<td><?php DUP_PRO_U::_e("Type") ?>: </td>
			<td><?php echo $package->Database->Type ?></td>
		</tr>
		<tr>
			<td><?php DUP_PRO_U::_e("Build Mode") ?>: </td>
			<td><?php echo $package->Database->DBMode ?></td>
		</tr>			
		<tr>
			<td><?php DUP_PRO_U::_e("Filters") ?>: </td>
			<td><?php echo $package->Database->FilterOn == 1 ? 'On' : 'Off'; ?></td>
		</tr>
		<tr class="sub-item <?php echo $css_db_filter_on ?>">
			<td><?php DUP_PRO_U::_e("Tables") ?>: </td>
			<td>
				<?php 
					echo isset($package->Archive->FilterTables) && strlen($package->Archive->FilterTables) 
						? str_replace(';', '<br/>', $package->Database->FilterTables)
						: DUP_PRO_U::__('- no filters -');	
				?>
			</td>
		</tr>			
	</table>		
</div>
</div>


<!-- ===============================
INSTALLER -->
<div class="dup-box" style="margin-bottom: 50px">
<div class="dup-box-title">
	<i class="fa fa-bolt"></i> <?php DUP_PRO_U::_e('Installer') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-install-panel" style="<?php echo $ui_css_install ?>">
    <br/>

<!--	<div class="dpro-box-panel-hdr"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('SETUP'); ?></div>-->
	<table class='dpro-dtl-data-tbl'>
		<!--tr>
			<td><?php DUP_PRO_U::_e("Skip Scanner");?>:</td>
			<td><?php echo $package->Installer->OptsSkipScan ? "&nbsp; On" : "&nbsp; Off" ?></td>
		</tr-->
		<tr>
			<td><?php DUP_PRO_U::_e("Password Screen");?>:</td>
			<td><?php echo $package->Installer->OptsSecureOn ? "&nbsp; On" : "&nbsp; Off" ?></td>
		</tr>
		<?php if($package->Installer->OptsSecureOn) :?>
		<tr>
			<td colspan="2">
				<div id="dpro-pass-toggle">
					<input type="password" name="secure-pass" id="secure-pass" required="required" value="<?php echo DUP_PRO_U::installerDecrypt($package->Installer->OptsSecurePass) ?>" />
					<button type="button" class="pass-toggle" onclick="DupPro.togglePassword()" title="Show/Hide the password"></button>
				</div>
			</td>
		</tr>
		<?php endif; ?>

	</table><br/><br/>
	
	
<!--	<div class="dpro-box-panel-hdr"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('STEP 1 - Deploy Files & Database'); ?></div>-->
		<!-- ===================
		STEP1 TABS -->
		<div data-dpro-tabs="true">
			<ul>
				<li>&nbsp; <?php DUP_PRO_U::_e('Basic') ?> &nbsp;</li>
				<li id="dpro-cpnl-tab-lbl"><?php DUP_PRO_U::_e('cPanel') ?></li>
			</ul>

			<!-- ===================
			TAB1: Basic -->
			<div>
				<table class='dpro-dtl-data-tbl'>
					<tr>
						<td><?php DUP_PRO_U::_e("Host") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsDBHost) ? $package->Installer->OptsDBHost : $lang_notset ?></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Database") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsDBName) ? $package->Installer->OptsDBName : $lang_notset ?></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("User") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsDBUser) ? $package->Installer->OptsDBUser : $lang_notset ?></td>
					</tr>	
				</table><br/>
			</div>
			
			<!-- ===================
			TAB2: cPanel -->
			<div style="max-height: 250px">
				<table class='dpro-dtl-data-tbl'>
					<tr>
						<td colspan="2" class="sub-section">&nbsp; <b><?php DUP_PRO_U::_e("cPanel Login") ?></b> &nbsp;</td>
					</tr>
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("Automation") ?>:</td>
						<td><?php echo ($package->Installer->OptsCPNLEnable) ? 'On' : 'Off' ?></td>
					</tr>
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("Host") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsCPNLHost) ? $package->Installer->OptsCPNLHost : $lang_notset ?></td>
					</tr>
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("User") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsCPNLUser) ? $package->Installer->OptsCPNLUser : $lang_notset ?></td>
					</tr>	
					<tr>
						<td colspan="2" class="sub-section"><b><?php DUP_PRO_U::_e("MySQL Server") ?></b></td>
					</tr>		
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("Action") ?>:</td>
						<td><?php echo ($package->Installer->OptsCPNLDBAction == 'create') ? DUP_PRO_U::__("Create A New Database") : DUP_PRO_U::__("Connect to Existing Database and Remove All Data") ?></td>
					</tr>		
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("Host") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsCPNLDBHost) ? $package->Installer->OptsCPNLDBHost : $lang_notset ?></td>
					</tr>
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("Database") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsCPNLDBName) ? $package->Installer->OptsCPNLDBName : $lang_notset ?></td>
					</tr>
					<tr class="sub-item">
						<td><?php DUP_PRO_U::_e("User") ?>:</td>
						<td><?php echo strlen($package->Installer->OptsCPNLDBUser) ? $package->Installer->OptsCPNLDBUser : $lang_notset ?></td>
					</tr>	
				</table><br/>
			
			</div>
		</div><br/>

	<!-- ===================
    ADVANCED SECTION 
	<b><?php DUP_PRO_U::_e('ADVANCED'); ?></b>
	<table class='dpro-dtl-data-tbl sub-table'>
		<tr class="sub-item">
			<td><?php DUP_PRO_U::_e("SSL") ?>:</td>
			<td>
				<?php echo ($package->Installer->OptsSSLAdmin) ? DUP_PRO_U::_e("Admin Enabled") : DUP_PRO_U::_e("Admin Disabled") ?>,
				<?php echo ($package->Installer->OptsSSLLogin) ? DUP_PRO_U::_e("Logins Enabled") : DUP_PRO_U::_e("Logins Disabled") ?>
			</td>
		</tr>
		<tr class="sub-item">
			<td><?php DUP_PRO_U::_e("Cache") ?>:</td>
			<td>
				<?php echo ($package->Installer->OptsCacheWP) ? DUP_PRO_U::_e("Enabled") : DUP_PRO_U::_e("Disabled") ?>,
				<?php echo ($package->Installer->OptsCachePath) ? DUP_PRO_U::_e("Home Path Enabled") : DUP_PRO_U::_e("Home Path Disabled") ?>
			</td>
		</tr>		
	</table><br/><br/>-->
	
<!--	<div class="dpro-box-panel-hdr"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('STEP 2 - INPUTS'); ?></div>
	<table class='dpro-dtl-data-tbl'>
		<tr>
			<td><?php DUP_PRO_U::_e("New URL") ?>:</td>
			<td><?php echo strlen($package->Installer->OptsURLNew) ? $package->Installer->OptsURLNew : $lang_notset ?></td>
		</tr>	
	</table>-->
	
</div>
</div>

<?php if ($global->package_debug) : ?>
	<div style="margin:0">
		<a href="javascript:void(0)" onclick="jQuery(this).parent().find('.dup-pack-debug').toggle()">[<?php DUP_PRO_U::_e("View Package Object") ?>]</a><br/>
		<pre class="dup-pack-debug" style="display:none"><?php @print_r($package); ?> </pre>
	</div>
<?php endif; ?>	


<script type="text/javascript">
jQuery(document).ready(function ($) 
{
	
	/*	Shows the Share 'Download Links' dialog
	 *	@param db		The path to the sql file
	 *	@param install	The path to the install file 
	 *	@param pack		The path to the package file
	 *	@param log		The path to the log file */
	DupPro.Pack.ShowLinksDialog = function(db, install, pack, log) 
	{
		var url = '#TB_inline?width=650&height=350&inlineId=dup-dlg-quick-path';
		tb_show("<?php DUP_PRO_U::_e('Package File Links') ?>", url);
		
		var msg = <?php printf('"%s:\n" + db + "\n\n%s:\n" + install + "\n\n%s:\n" + pack + "\n\n%s:\n" + log;', 
			DUP_PRO_U::__("DATABASE"), 
			DUP_PRO_U::__("PACKAGE"), 
			DUP_PRO_U::__("INSTALLER"),
			DUP_PRO_U::__("LOG")); 
		?>
		$("#dpro-dlg-quick-path-data").val(msg);
		return false;
	}

	/*	Open all Panels  */
	DupPro.Pack.OpenAll = function () {
		$("div.dup-box").each(function() {
			var panel_open = $(this).find('div.dup-box-panel').is(':visible');
			if (! panel_open)
				$( this ).find('div.dup-box-title').trigger("click");
		 });
	};

	/*	Close all Panels */
	DupPro.Pack.CloseAll = function () {
			$("div.dup-box").each(function() {
			var panel_open = $(this).find('div.dup-box-panel').is(':visible');
			if (panel_open)
				$( this ).find('div.dup-box-title').trigger("click");
		 });
	};
	
	/** 
	 * Submits the password for validation
	 */
	DupPro.togglePassword = function() 
	{
		var $input = $('#secure-pass');
		if (($input).attr('type') == 'text') {
			$input.attr('type', 'password');
		} else {
			$input.attr('type', 'text');
		}
	}
	
	/*	Selects all text in share dialog */
	DupPro.Pack.GetLinksText = function() {
			$('#dpro-dlg-quick-path-data').select();
			document.execCommand('copy');
		};	
});
</script>
