<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/utilities/class.u.settings.php');

$nonce = wp_create_nonce('duplicator_pro_import_export_settings');

$view_state			 = DUP_PRO_UI_ViewState::getArray();
$ui_css_export_panel = (isset($view_state['dpro-tools-export-panel']) && $view_state['dpro-tools-export-panel']) ? 'display:block' : 'display:none';
$ui_css_import_panel = (isset($view_state['dpro-tools-import-panel']) && $view_state['dpro-tools-import-panel']) ? 'display:block' : 'display:none';

//POST BACK
$_REQUEST['action'] = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'display';

$error_message = null;
$success_message = null;

if (isset($_REQUEST['action']))
{
	$settings_u = new DUP_PRO_Settings_U();	
    switch ($_REQUEST['action'])
    {
        case 'dpro-export' : 
        case 'dpro-import' : 	
			try
			{
				$settings_u->runImport($_FILES['import-file'], $_POST['import-opts']);
				$success_message = 'Successfully imported.';
			}
			catch (Exception $ex)
			{				
				$error_message = 'Import Error: ' .  $ex->getMessage();	
			}
			break;
    }
}
?>

<style>
    <?php echo isset($css_hide_msg) ? $css_hide_msg : ''; ?>
	div.dup-box {margin-top:20px}
	div#message {margin:0px 0px 10px 0px}
    div.success {color:#4A8254}
    div.failed {color:red}
	table.dpro-check-tbl td {padding:5px 30px 10px 10px}
	div#message {margin-top:10px !important}
	div#TB_ajaxContent p {font-size:14px !important}
</style>

<?php 
	if($error_message !== null)
	{
		echo "<div id='message' class='below-h2 error'><p>{$error_message}</p></div>";;
	}
	else if($success_message !== null)
	{
		echo "<div id='message' class='below-h2 updated'><p>{$success_message}</p></div>";
	}
?>

<!-- ==============================
EXPORT -->
<form id="dup-tools-form-export" action="<?php echo self_admin_url('admin.php?page=duplicator-pro-tools&tab=data'); ?>" method="post">
	<?php wp_nonce_field('dpro_tools_data_export'); ?>	
	<input type="hidden"  name="action" value="dpro-export">
	<div class="dup-box">
		<div class="dup-box-title">
			<i class="fa fa-upload"></i>
			<?php DUP_PRO_U::_e("Export") ?>
			<div class="dup-box-arrow"></div>
		</div>
		<div class="dup-box-panel" id="dpro-tools-export-panel" style="<?php echo $ui_css_export_panel ?>">
			<?php DUP_PRO_U::_e("Exports all schedules, storage locations, templates and settings from this Duplicator Pro instance into a downloadable export file.  "); ?> <br/>
			<?php DUP_PRO_U::_e("The export file can then be used to import data settings from this instance of Duplicator Pro into another plugin instance of Duplicator Pro.  "); ?> 
			
			<br/><br/><br/>

			<input type="button" class="button button-primary" value="<?php DUP_PRO_U::_e("Export Data"); ?>" onclick="return DupPro.Tools.ExportDialog();" />
			<br/><br/>
		</div> 
	</div> 
</form>
	
<!-- ==============================
IMPORT -->
<form enctype="multipart/form-data"  id="dup-tools-form-import" action="<?php echo self_admin_url('admin.php?page=duplicator-pro-tools&tab=data'); ?>" method="post" data-parsley-validate data-parsley-ui-enabled="true" >
	<?php wp_nonce_field('dpro_tools_data_import'); ?>
	<input type="hidden"  name="action" value="dpro-import">
	<div class="dup-box">
		<div class="dup-box-title">
			<i class="fa fa-download"></i>
			<?php DUP_PRO_U::_e("Import"); ?>
			<div class="dup-box-arrow"></div>
		</div>
		<div class="dup-box-panel" id="dpro-tools-import-panel" style="<?php echo $ui_css_import_panel ?>" >
			<?php DUP_PRO_U::_e("Import settings from another Duplicator Pro plugin into this instance of Duplicator Pro."); ?> <br/>
			<?php DUP_PRO_U::_e("Schedule, storage and template data will be appended to current data, while existing settings will be replaced."); ?> <br/>
			<i><?php DUP_PRO_U::_e("Schedules depend on storage and templates so importing schedules will require that storage and templates be checked."); ?></i><br/>
			<br/><br/>

			<label for="import-file"><b><?php DUP_PRO_U::_e("Choose Duplicator Data File"); ?></b> </label><br/>
			<input type="file" accept=".dup" name="import-file" id="import-file" required="true" /> 
			<br/><br/>

			<b><?php DUP_PRO_U::_e("Include in Import"); ?>:</b>
			<table class="dpro-check-tbl">
				<tr>
					<td>
						<input onclick="DupPro.Tools.ChangeImportButtonState();DupPro.Tools.SchedulesClicked();" type="checkbox" name="import-opts[]" id="import-schedules" value="schedules" />
						<label for="import-schedules"><?php DUP_PRO_U::_e("Schedules"); ?></label>
					</td>
					<td>
						<input onclick="DupPro.Tools.ChangeImportButtonState();" type="checkbox" name="import-opts[]" id="import-storages" value="storages" />
						<label for="import-storages"><?php DUP_PRO_U::_e("Storage"); ?></label>
					</td>
					<td>
						<input onclick="DupPro.Tools.ChangeImportButtonState();" type="checkbox" name="import-opts[]" id="import-templates" value="templates" />
						<label for="import-templates"><?php DUP_PRO_U::_e("Templates"); ?></label>
					</td>
				</tr>
				<tr>

					<td colspan="3">
						<input onclick="DupPro.Tools.ChangeImportButtonState();" type="checkbox" name="import-opts[]" id="import-settings" value="settings" />
						<label for="import-settings"><?php DUP_PRO_U::_e("Settings"); ?></label>
					</td>
				</tr>
			</table>
			<br/>

			<input id="import-button" type="button" class="button button-primary" value="<?php DUP_PRO_U::_e("Import Data"); ?>" onclick="return DupPro.Tools.ImportDialog();" disabled/>
			<br/><br/>
		</div> 
	</div>
</form>
<br/><br/>

<?php add_thickbox(); ?>

<!-- EXPORT DIALOG -->
<div id="modal-window-export" style="display:none;">
	<h2><?php DUP_PRO_U::_e("Export Duplicator Pro Data?") ?></h2>
	<p>
		<?php DUP_PRO_U::_e("This process will:") ?><br/><br/>
		<i class="fa fa-check-circle-o"></i> <?php DUP_PRO_U::_e("Export schedules, storage and templates to a file for import into another Duplicator instance."); ?> <br/>
		<span style="color:red"><i class="fa fa-warning"></i> <?php DUP_PRO_U::_e("For security purposes, restrict access to this file and delete after use."); ?></span> <br/>
		<br/>
		<?php DUP_PRO_U::_e("Click the 'Run Export' button to generate and download the export file.") ?><br/><br/>
	</p>
	<div style="position:absolute; right:10px; bottom: 10px">
		<input type="button" class="button" value="<?php DUP_PRO_U::_e("Run Export") ?>" onclick="DupPro.Tools.ExportProcess();setTimeout(function() { tb_remove(); }, 4000);" />
		<input type="button" class="button" value="<?php DUP_PRO_U::_e("Cancel") ?>" onclick="tb_remove();" />
	</div>
</div>

<!-- IMPORT DIALOG -->
<div id="modal-window-import" style="display:none;">
	<h2><?php DUP_PRO_U::_e("Import Duplicator Pro Data?") ?></h2>
	<p>
		<?php DUP_PRO_U::_e("This process will:") ?><br/><br/>
		<i class="fa fa-check-circle-o"></i> <?php DUP_PRO_U::_e("Append schedules, storage and templates if those options are checked."); ?> <br/>		
		<i class="fa fa-check-circle-o"></i> <?php DUP_PRO_U::_e("Overwrite current settings data if the settings option is checked."); ?> <br/>
		<span style="color:red"><i class="fa fa-warning"></i> <?php DUP_PRO_U::_e("Review templates and local storages after import to ensure correct path values."); ?> <br/></span>
		<br/>
		<?php DUP_PRO_U::_e("Click the 'Run Import' button to process the import file.") ?><br/><br/>
	</p>
	<div style="position:absolute; right:10px; bottom: 10px">
		<input type="button" class="button" value="<?php DUP_PRO_U::_e("Run Import") ?>" onclick="DupPro.Tools.ImportProcess();" />
		<input type="button" class="button" value="<?php DUP_PRO_U::_e("Cancel") ?>" onclick="tb_remove();" />
	</div>
</div>


<script>
	DupPro.Tools.ExportProcess = function () 
	{
		var actionLocation = ajaxurl + '?action=duplicator_pro_export_settings' + '&nonce=' + '<?php echo $nonce; ?>';
		location.href = actionLocation;
	}
	
	DupPro.Tools.ExportDialog = function () 
	{
		var url = "#TB_inline?width=610&height=250&inlineId=modal-window-export";
		tb_show("<?php DUP_PRO_U::_e("Export Data") ?>", url);
		return false;
	}	
	
	DupPro.Tools.ImportProcess = function () 
	{
		jQuery('#dup-tools-form-import').submit();
	}
	
	DupPro.Tools.ImportDialog = function () 
	{
		var url = "#TB_inline?width=610&height=300&inlineId=modal-window-import";
		tb_show("<?php DUP_PRO_U::_e("Import Data") ?>", url);
		return false;
	}	
	
	//PAGE INIT
    jQuery(document).ready(function ($) 
	{
		DupPro.Tools.ChangeImportButtonState = function() 
		{
			var filename = $('#import-file').val();
			var disabled = (filename == '');
			
			disabled = disabled || (!document.getElementById('import-templates').checked && !document.getElementById('import-storages').checked && !document.getElementById('import-schedules').checked && !document.getElementById('import-settings').checked);
							
			$('#import-button').prop('disabled', disabled);
		}
		
		DupPro.Tools.SchedulesClicked = function() 
		{
			if(document.getElementById('import-schedules').checked) 
			{
				document.getElementById('import-templates').checked = true;
				document.getElementById('import-storages').checked = true;
				document.getElementById('import-templates').disabled = true;
				document.getElementById('import-storages').disabled = true;
			}
			else {
				document.getElementById('import-templates').disabled = false;
				document.getElementById('import-storages').disabled = false;
			}
		}
	
		$("#dpro-tools-import-panel").on("change", "#import-file", function() { DupPro.Tools.ChangeImportButtonState(); });
    });
</script>
