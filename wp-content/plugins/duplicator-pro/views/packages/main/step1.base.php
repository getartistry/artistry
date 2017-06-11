<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . 'classes/package/class.pack.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . 'classes/entities/class.storage.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . 'classes/entities/class.package.template.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . 'classes/entities/class.global.entity.php');

global $wpdb;

//POST BACK
$action_updated = null;
if (isset($_POST['action']))
{
    switch ($_POST['action'])
    {
        case 'duplicator_pro_package_active' : $action_response = DUP_PRO_U::__('Package settings have been reset.');
            break;
    }
}

DUP_PRO_U::initStorageDirectory();

$manual_template = DUP_PRO_Package_Template_Entity::get_manual_template();

$dup_tests = array();
$dup_tests = DUP_PRO_Server::getRequirments();
$default_name = DUP_PRO_Package::get_default_name();
$default_notes = $manual_template->notes; 

$view_state = DUP_PRO_UI_ViewState::getArray();
$ui_css_storage = (isset($view_state['dup-pack-storage-panel']) && $view_state['dup-pack-storage-panel']) ? 'display:block' : 'display:none';
$ui_css_archive = (isset($view_state['dup-pack-archive-panel']) && $view_state['dup-pack-archive-panel']) ? 'display:block' : 'display:none';
$ui_css_installer = (isset($view_state['dup-pack-installer-panel']) && $view_state['dup-pack-installer-panel']) ? 'display:block' : 'display:none';

$storage_list = DUP_PRO_Storage_Entity::get_all();
$storage_list_count = count($storage_list);
$dup_intaller_files = implode(", ", array_keys(DUP_PRO_Server::getInstallerFiles()));
	
$global = DUP_PRO_Global_Entity::get_instance();
$dbbuild_mode = ($global->package_mysqldump ? 'mysqldump' : 'PHP');

?>

<style>
	/* -----------------------------
    PACKAGE OPTS*/
	form#dup-form-opts {margin-top:10px}
    form#dup-form-opts label {line-height:22px}
    form#dup-form-opts input[type=checkbox] {margin-top:3px}
    form#dup-form-opts textarea, input[type="text"], input[type="password"] {width:100%}
    textarea#package_notes {height:37px;}
	div.dup-notes-add {float:right; margin:0;}
	div#dup-notes-area {display:none;}
	select#template_id {width:100%; margin-bottom:4px}
	div.dpro-general-area {line-height:27px; margin:0 0 5px 0}
	div#dpro-template-specific-area table td:first-child {width:100px; font-weight: bold}
	
	
	/*TABS*/
	ul.add-menu-item-tabs li, ul.category-tabs li {padding:3px 30px 5px}
</style>

<!-- ====================
TOOL-BAR -->
<table class="dpro-edit-toolbar">
	<tr>
		<td>
			<div id="dup-wiz">
				<div id="dup-wiz-steps">
					<div class="active-step"><a>1-<?php DUP_PRO_U::_e('Setup'); ?></a></div>
					<div><a>2-<?php DUP_PRO_U::_e('Scan'); ?> </a></div>
					<div><a>3-<?php DUP_PRO_U::_e('Build'); ?> </a></div>
				</div>
				<div id="dup-wiz-title" style="white-space: nowrap">
					<?php DUP_PRO_U::_e('Step 1: Package Setup'); ?>
				</div> 
			</div>	
		</td>
		<td>
			<a href="<?php echo $packages_tab_url; ?>" class="add-new-h2"><i class="fa fa-archive"></i> <?php DUP_PRO_U::_e('All Packages'); ?></a>
			<span> <?php _e("Create New"); ?></span>
		</td>
	</tr>
</table>
<hr class="dpro-edit-toolbar-divider"/>

<?php if (!empty($action_response)) : ?>
    <div id="message" class="updated below-h2"><p><?php echo $action_response; ?></p></div>
<?php endif; ?>	

<?php require_once('step1.inc-0.reqs.php'); ?>

<form id="dup-form-opts" method="post" action="?page=duplicator-pro&tab=packages&inner_page=new2" data-parsley-validate data-parsley-ui-enabled="true" >
	<input type="hidden" id="dup-form-opts-action" name="action" value="">
	<div class="dpro-general-area">
		<b>Apply Template:</b>
		<i class="fa fa-question-circle" 
			data-tooltip-title="<?php DUP_PRO_U::_e("Apply Template:"); ?>" 
			data-tooltip="<?php DUP_PRO_U::_e('An optional template configuration that can be applied to this package setup. An [Unassigned] template will retain the settings from the last scan/build.'); ?>"></i>
		<br/>
		<select data-parsley-ui-enabled="false" onchange="DupPro.Pack.EnableTemplate();" name="template_id" id="template_id" >
			<option value="<?php echo $manual_template->id; ?>"><?php echo '[' . DUP_PRO_U::__('Unassigned') . ']' ?></option>
			<?php
				$templates = DUP_PRO_Package_Template_Entity::get_all();
				if (count($templates) == 0)
				{
					$no_templates = __('No Templates');
					echo "<option value='-1'>$no_templates</option>";
				}
				else
				{
					foreach ($templates as $template) {
						echo "<option value='{$template->id}'>{$template->name}</option>";
					}
				}
			?>
		</select>

		<label for="package-name"><b><?php DUP_PRO_U::_e('Name') ?>:</b> </label>
		<a href="javascript:void(0)" onclick="DupPro.Pack.ResetName()" title="<?php DUP_PRO_U::_e('Create a new default name') ?>"><i class="fa fa-undo"></i></a> 
		<div class="dup-notes-add">
			<button class="button button-small" type="button" onclick="jQuery('#dup-notes-area').toggle()"><i class="fa fa-pencil-square-o"></i> <?php DUP_PRO_U::_e('Notes') ?></button>
		</div>
		<input id="package-name"  name="package-name" type="text" maxlength="40"  required="true" data-regexp="^[0-9A-Za-z|_]+$" />
		<div id="dup-notes-area">
			<label><b><?php DUP_PRO_U::_e('Notes') ?>:</b></label><br/>
			<textarea id="package-notes" name="package-notes" maxlength="300" /></textarea>
		</div>
	</div>	

	<?php 
		require_once('step1.inc-1.store.php');
		require_once('step1.inc-2.archive.php');
		require_once('step1.inc-3.install.php'); 
	?>
	
	<div class="dup-button-footer">
		<input type="button" value="<?php DUP_PRO_U::_e("Reset") ?>" class="button button-large" <?php echo ($dup_tests['Success']) ? '' : 'disabled="disabled"'; ?> onclick="DupPro.Pack.ResetSettings()" />
		<input id="button-next" type="submit" value="<?php DUP_PRO_U::_e("Next") ?> &#9654;" class="button button-primary button-large" <?php echo ($dup_tests['Success']) ? '' : 'disabled="disabled"'; ?> />
	</div>
</form>

<script>
	
var DPRO_NAME_DEFAULT;
var DPRO_NAME_LAST;

jQuery(function($) 
{	
    var packageTemplates = [];

	<?php
		$counter = 0;
		$templates = DUP_PRO_Package_Template_Entity::get_all(true);
		foreach ($templates as $template)
		{
			$template->installer_opts_secure_pass = base64_decode($template->installer_opts_secure_pass);
			$json = json_encode($template);
			echo "    packageTemplates[$counter] = $json;\n\r\n\r";
			$counter++;
		}
	?>
            
	// Template-specific Functions
	DupPro.Pack.GetTemplateById = function (templateId) 
	{
		for (i = 0; i < packageTemplates.length; i++) {
			var currentTemplate = packageTemplates[i];
			if (currentTemplate.id == templateId) {
				return currentTemplate;
			}
		}
		return null;
	};


	DupPro.Pack.PopulateCurrentTemplate = function () 
	{
		var selectedId = $('#template_id').val();
		var selectedTemplate = DupPro.Pack.GetTemplateById(selectedId);
		if (selectedTemplate != null) 
		{
			var name = selectedTemplate.name;
			
			if(selectedTemplate.is_manual) {
				name = "<?php echo DUP_PRO_Package::get_default_name(); ?>";
			}
			
			$("#package-name").val(name);
			$("#package-notes").val(selectedTemplate.notes);

			$("#filter-on").prop("checked", selectedTemplate.archive_filter_on);
			
			$("#filter-dirs").val(selectedTemplate.archive_filter_dirs.split(";").join(";\n"));
			$("#filter-exts").val(selectedTemplate.archive_filter_exts);			
			$("#filter-files").val(selectedTemplate.archive_filter_files.split(";").join(";\n"));
			$("#dbfilter-on").prop("checked", selectedTemplate.database_filter_on);

			//-- cPanel
			$("#cpnl-enable").prop("checked", selectedTemplate.installer_opts_cpnl_enable);
			$("#cpnl-host").val(selectedTemplate.installer_opts_cpnl_host);
			$("#cpnl-user").val(selectedTemplate.installer_opts_cpnl_user);
			
			$("#secure-on").prop("checked", selectedTemplate.installer_opts_secure_on);
			$("#skipscan").prop("checked", selectedTemplate.installer_opts_skip_scan);
			$("#secure-pass, #secure-pass2").val(selectedTemplate.installer_opts_secure_pass);
									
			$("#cpnl-dbaction").val(selectedTemplate.installer_opts_cpnl_db_action);
			$("#cpnl-dbhost").val(selectedTemplate.installer_opts_cpnl_db_host);
			$("#cpnl-dbname").val(selectedTemplate.installer_opts_cpnl_db_name);
			$("#cpnl-dbuser").val(selectedTemplate.installer_opts_cpnl_db_user);
								
			var databaseFilterTables = selectedTemplate.database_filter_tables.split(",");
			$("#dup-dbtables input").prop("checked", false).css('text-decoration', 'none');

			for (filterTableKey in databaseFilterTables)
			{
				var filterTable = databaseFilterTables[filterTableKey];
				var selector = "#dbtables-" + filterTable;
				$(selector).prop("checked", true).css('text-decoration', 'line-through');
			}

			$("#dbhost").val(selectedTemplate.installer_opts_db_host);
			$("#dbname").val(selectedTemplate.installer_opts_db_name);
			$("#dbuser").val(selectedTemplate.installer_opts_db_user);
			$("#url-new").val(selectedTemplate.installer_opts_url_new);

			$("#ssl-admin").prop("checked", selectedTemplate.installer_opts_ssl_admin);
			$("#ssl-login").prop("checked", selectedTemplate.installer_opts_ssl_login);
			$("#cache-wp").prop("checked", selectedTemplate.installer_opts_cache_wp);
			$("#cache-path").prop("checked", selectedTemplate.installer_opts_cache_path);
		} else {
			console.log("Template ID doesn't exist?? " + selectedId);
		}
		
		//Default to Installer cPanel tab if used
		$('#cpnl-enable').is(":checked") ? $('#dpro-cpnl-tab-lbl').trigger("click") : null;
		
	};

	DupPro.Pack.ResetSettings = function () 
	{
		if (! confirm('<?php DUP_PRO_U::_e("This will clear all of the current package settings.  Would you like to continue?"); ?>'))
			return;
		$('#dup-form-opts')[0].reset();
	};

	DupPro.Pack.ResetName = function () 
	{
		var current = $('#package-name').val();
		$('#package-name').val((current == DPRO_NAME_LAST) ? DPRO_NAME_DEFAULT : DPRO_NAME_LAST)
	};
});


//INIT
jQuery(document).ready(function ($) 
{
	DPRO_NAME_DEFAULT	= '<?php echo $default_name ?>';
	DPRO_NAME_LAST		= $('#package-name').val();
	DPRO_NOTES_DEFAULT	= '<?php echo $default_notes ?>';

	DupPro.Pack.EnableTemplate = function () 
	{
		$("#dup-form-opts-action").val('template-create');
		$('#dpro-template-specific-area').show(0);  
		DupPro.Pack.PopulateCurrentTemplate();
		DupPro.Pack.ToggleInstallerPassword();
		DupPro.Pack.ToggleFileFilters();
		DupPro.Pack.ToggleDBFilters();
	}
	
	DupPro.Pack.EnableTemplate();		
	
});
</script>
