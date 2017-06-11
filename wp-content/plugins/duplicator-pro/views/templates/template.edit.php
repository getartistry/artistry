<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.package.template.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . 'classes/entities/class.global.entity.php');

global $wp_version;
global $wpdb;

$nonce_action = 'duppro-template-edit';

$was_updated = false;
$package_template_id = isset($_REQUEST['package_template_id']) ? esc_html($_REQUEST['package_template_id']) : -1;
$package_templates = DUP_PRO_Package_Template_Entity::get_all();
$package_template_count = count($package_templates);

$view_state = DUP_PRO_UI_ViewState::getArray();
$ui_css_archive = (isset($view_state['dup-template-archive-panel']) && $view_state['dup-template-archive-panel']) ? 'display:block' : 'display:none';
$ui_css_install = (isset($view_state['dup-template-install-panel']) && $view_state['dup-template-install-panel']) ? 'display:block' : 'display:none';

/* @var $package_template DUP_PRO_Package_Template_Entity */
if ($package_template_id == -1)
{
    $package_template = new DUP_PRO_Package_Template_Entity();
    $edit_create_text = DUP_PRO_U::__('Add New');
}
else
{
    $package_template = DUP_PRO_Package_Template_Entity::get_by_id($package_template_id);
    DUP_PRO_LOG::traceObject("getting template $package_template_id", $package_template);
    $edit_create_text = DUP_PRO_U::__('Edit') . ' ' . $package_template->name;
}

if (isset($_REQUEST['action']))
{
    check_admin_referer($nonce_action);
    if ($_REQUEST['action'] == 'save')
    {
        if (isset($_REQUEST['_database_filter_tables']))
        {
            $package_template->database_filter_tables = implode(',', $_REQUEST['_database_filter_tables']);
        }
        else
        {
            $package_template->database_filter_tables = '';
        }

        $package_template->archive_filter_dirs = isset($_REQUEST['_archive_filter_dirs']) ? DUP_PRO_Package::parse_directory_filter($_REQUEST['_archive_filter_dirs']) : '';
        $package_template->archive_filter_exts = isset($_REQUEST['_archive_filter_exts']) ? DUP_PRO_Package::parse_extension_filter($_REQUEST['_archive_filter_exts']) : '';
        $package_template->archive_filter_files = isset($_REQUEST['_archive_filter_files']) ? DUP_PRO_Package::parse_file_filter($_REQUEST['_archive_filter_files']) : '';

        DUP_PRO_LOG::traceObject('request', $_REQUEST);

        // Checkboxes don't set post values when off so have to manually set these
        $package_template->set_post_variables($_REQUEST);
        $package_template->save();
        $was_updated = true;
        $edit_create_text = DUP_PRO_U::__('Edit') . ': ' . $package_template->name;
    }
    else if ($_REQUEST['action'] == 'copy-template')
    {
        $source_template_id = $_REQUEST['duppro-source-template-id'];
         
        if($source_template_id != -1)
        {
            $package_template->copy_from_source_id($source_template_id);
            $package_template->save();
        }
    }
}

$installer_pass = (base64_decode($package_template->installer_opts_secure_pass)) ? base64_decode($package_template->installer_opts_secure_pass) : '';

$installer_cpnldbaction = isset($package_template->installer_opts_cpnl_db_action) ? $package_template->installer_opts_cpnl_db_action : 'create';
$uploads = wp_upload_dir();
$upload_dir = DUP_PRO_U::safePath($uploads['basedir']);
?>

<style>
    table.dpro-edit-toolbar select {float:left}
	table.form-table td {padding:2px;}
	table.form-table th {padding:5px; font-weight: normal}
    div#dpro-notes-add {float:right; margin:-4px 2px 4px 0;}
    div.dpro-template-general {margin:8px 0 10px 0}
    div.dpro-template-general label {font-weight: bold}
    div.dpro-template-general input, textarea {width:100%}
	b.dpro-hdr {display:block; font-size:14px;  margin:3px 0 3px 0; padding:3px 0 3px 0; border-bottom: 1px solid #dfdfdf}
	form#dpro-template-form textarea, input[type="text"], input[type="password"] {width:100%}

	/*ARCHIVE*/
    textarea#_archive_filter_dirs {width:100%; height:75px}
    textarea#_archive_filter_files {width:100%; height:75px}
    input#_archive_filter_exts {width:100%}
    div.dup-quick-links {font-size:11px; float:right; display:inline-block; margin-bottom:2px; font-style:italic}
	table#dup-dbtables td {padding:2px;vertical-align: top}
	ul#parsley-id-multiple-_database_filter_tables {display:none}
	
	/*INSTALLER */
	div.tabs-panel {max-height:350px !important}
	ul.add-menu-item-tabs li, ul.category-tabs li {padding:3px 30px 5px}
	div.secure-pass-area {display:none}
	input#_installer_opts_secure_pass, input#_installer_opts_secure_pass2{width:300px; margin: 3px 0 5px 0}
	label.secure-pass-lbl {display:inline-block; width:125px}
	div#dup-template-install-panel div.tabs-panel{min-height:150px}
</style>


<form id="dpro-template-form" data-parsley-validate data-parsley-ui-enabled="true" action="<?php echo $edit_template_url; ?>" method="post">
<?php wp_nonce_field($nonce_action); ?>
<input type="hidden" id="dpro-template-form-action" name="action" value="save">
<input type="hidden" name="package_template_id" value="<?php echo $package_template->id; ?>">

<!-- ====================
SUB-TABS -->
<?php if ($was_updated) : ?>
	<div class="updated below-h2"><p><?php DUP_PRO_U::_e('Template Updated'); ?></p></div>
<?php endif; ?>

<!-- ====================
TOOL-BAR -->
<table class="dpro-edit-toolbar">
	<tr>
		<td>
			<?php if ($package_template_count > 0) : ?>
				<select name="duppro-source-template-id">
					<option value="-1" selected="selected"><?php _e("Copy From"); ?></option>
					<?php foreach ($package_templates as $copy_package_template) : 
						if($copy_package_template->id != $package_template->id) : ?>
						<option value="<?php echo $copy_package_template->id ?>"><?php echo $copy_package_template->name; ?></option>
					<?php 
						endif;
						endforeach; 
					?>
				</select>
				<input type="button" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" onclick="DupPro.Template.Copy()">
			<?php else : ?>
				<select disabled="disabled"><option value="-1" selected="selected"><?php _e("Copy From"); ?></option></select>
				<input type="button" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" onclick="DupPro.Template.Copy()"  disabled="disabled">
			<?php endif; ?>
		</td>
		<td>
			<a href="<?php echo $templates_tab_url; ?>" class="add-new-h2"><i class="fa fa-files-o"></i> <?php DUP_PRO_U::_e('All Templates'); ?></a>
			<span><?php echo $edit_create_text; ?></span>
		</td>
	</tr>
</table>
<hr class="dpro-edit-toolbar-divider"/>

<div class="dpro-template-general">
	<label><?php _e("Package Name"); ?>:</label>

	<input type="text" id="template-name" name="name" data-parsley-errors-container="#template_name_error_container" data-parsley-required="true" value="<?php echo $package_template->name; ?>" autocomplete="off">
	<div id="template_name_error_container" class="duplicator-error-container"></div>

	<label><?php _e("Notes"); ?>:</label> <br/>
	<textarea id="template-notes" name="notes" style="height:50px"><?php echo $package_template->notes; ?></textarea>
</div>

<!-- ===============================
ARCHIVE -->
<div class="dup-box">
	<div class="dup-box-title">
		<i class="fa fa-file-archive-o"></i> <?php DUP_PRO_U::_e('Archive') ?>
		<div class="dup-box-arrow"></div>
	</div>			
	<div class="dup-box-panel" id="dup-template-archive-panel" style="<?php echo $ui_css_archive ?>">

		<!-- =================
		FILES -->
		<b class="dpro-hdr"><i class="fa fa-files-o"></i> <?php DUP_PRO_U::_e('FILES'); ?></b>

		<input id="archive_filter_on" type="checkbox" <?php DUP_PRO_UI::echoChecked($package_template->archive_filter_on) ?> name="archive_filter_on" />
		<label for="archive_filter_on"><?php _e("Enable File Filter"); ?></label>
		<br/><br/>

		<label><?php _e("Directories"); ?>:</label>
		<div class='dup-quick-links'>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludePath('<?php echo rtrim(DUPLICATOR_PRO_WPROOTPATH, '/'); ?>')">[<?php DUP_PRO_U::_e("root path") ?>]</a>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludePath('<?php echo rtrim($upload_dir, '/'); ?>')">[<?php DUP_PRO_U::_e("wp-uploads") ?>]</a>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludePath('<?php echo DUP_PRO_U::safePath(WP_CONTENT_DIR); ?>/cache')">[<?php DUP_PRO_U::_e("cache") ?>]</a>
			<a href="javascript:void(0)" onclick="jQuery('#_archive_filter_dirs').val('')"><?php DUP_PRO_U::_e("(clear)") ?></a>
		</div>
		<textarea name="_archive_filter_dirs" id="_archive_filter_dirs" placeholder="/full_path/exclude_path1;/full_path/exclude_path2;">
			<?php echo str_replace(";", ";\n", esc_textarea($package_template->archive_filter_dirs)) ?>
		</textarea>
		<br/>

		<label><?php _e("Extensions"); ?>:</label>
		<div class='dup-quick-links'>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludeExts('avi;mov;mp4;mpeg;mpg;swf;wmv;aac;m3u;mp3;mpa;wav;wma')">[<?php DUP_PRO_U::_e("media") ?>]</a>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludeExts('zip;rar;tar;gz;bz2;7z')">[<?php DUP_PRO_U::_e("archive") ?>]</a>
			<a href="javascript:void(0)" onclick="jQuery('#_archive_filter_exts').val('')"><?php DUP_PRO_U::_e("(clear)") ?></a>
		</div>
		<input type="text" name="_archive_filter_exts" id="_archive_filter_exts" value="<?php echo $package_template->archive_filter_exts; ?>" placeholder="ext1;ext2;ext3">
		<br/>

		<label><?php _e("Files"); ?>:</label>
		<div class='dup-quick-links'>
			<a href="javascript:void(0)" onclick="DupPro.Template.AddExcludeFilePath('<?php echo rtrim(DUPLICATOR_PRO_WPROOTPATH, '/'); ?>')">[<?php DUP_PRO_U::_e("file path") ?>]</a>
			<a href="javascript:void(0)" onclick="jQuery('#_archive_filter_files').val('')"><?php DUP_PRO_U::_e("(clear)") ?></a>
		</div>
		<textarea name="_archive_filter_files" id="_archive_filter_files" placeholder="/full_path/exclude_file_1.ext;/full_path/exclude_file2.ext"><?php echo str_replace(";", ";\n", esc_textarea($package_template->archive_filter_files)) ?></textarea>
		<br/>

		<!-- =================
		DATABASE -->
		<b class="dpro-hdr"><i class="fa fa-table"></i> <?php DUP_PRO_U::_e('DATABASE'); ?></b>
		<input type="checkbox" id="_datbase_filter_on" <?php DUP_PRO_UI::echoChecked($package_template->database_filter_on) ?> name="_database_filter_on" />
		<label for="_datbase_filter_on"><?php DUP_PRO_U::_e("Enable Table Filters"); ?></label>
		<i class="fa fa-question-circle" 
			data-tooltip-title="<?php DUP_PRO_U::_e("Database Table Filters:"); ?>" 
			data-tooltip="<?php DUP_PRO_U::_e('Checked tables will not be added to the database script.  Excluding certain tables can possibly cause your site or plugins to not work correctly after install!'); ?>">
		</i><br/><br/>

		<div id="dup-db-filter-items">
			<a href="javascript:void(0)" id="dball" onclick="jQuery('#dup-dbtables .checkbox').prop('checked', true).trigger('click');">[ <?php DUP_PRO_U::_e('Include All'); ?> ]</a> &nbsp; 
			<a href="javascript:void(0)" id="dbnone" onclick="jQuery('#dup-dbtables .checkbox').prop('checked', false).trigger('click');">[ <?php DUP_PRO_U::_e('Exclude All'); ?> ]</a>
			<div style="font-family: Calibri; white-space: nowrap">
				<?php
				$tables = $wpdb->get_results("SHOW FULL TABLES FROM `" . DB_NAME . "` WHERE Table_Type = 'BASE TABLE' ", ARRAY_N);

				$num_rows = count($tables);
				echo '<table id="dup-dbtables"><tr><td>';
				$next_row = round($num_rows / 3, 0);
				$counter = 0;
				$tableList = explode(',', $package_template->database_filter_tables);
				foreach ($tables as $table)
				{
					if (in_array($table[0], $tableList))
					{
						$checked = 'checked="checked"';
						$css = 'text-decoration:line-through';
					}
					else
					{
						$checked = '';
						$css = '';
					}
					echo "<label for='_database_filter_tables-{$table[0]}' style='{$css}'>" .
					"<input class='checkbox dbtable' $checked type='checkbox' name='_database_filter_tables[]' id='_database_filter_tables-{$table[0]}' value='{$table[0]}' onclick='DupPro.Template.ExcludeTable(this)' />&nbsp;{$table[0]}" .
					"</label><br />";
					$counter++;
					if ($next_row <= $counter)
					{
						echo '</td><td valign="top">';
						$counter = 0;
					}
				}
				echo '</td></tr></table>';
				?>
			</div><br/>
		</div>

		<?php DUP_PRO_U::_e("Compatibility Mode"); ?>
		<i class="fa fa-question-circle" 
			data-tooltip-title="<?php DUP_PRO_U::_e("Legacy Support:"); ?>" 
			data-tooltip="<?php DUP_PRO_U::_e('This option is not available as a template setting.  It can only be used when creating a new package.  Please see the FAQ for a full overview of using this feature.'); ?>">
		</i><br/>
		<i><?php 
				$url = "<a href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-trouble-090-q' target='_blank'>" . DUP_PRO_U::__('FAQ details') . "</a>";
				DUP_PRO_U::_e(sprintf("Not enabled for template settings. Please see the full %s", $url) ); 
			?>
		</i>
	</div>
</div><br />


<!-- ===============================
INSTALLER -->
<div class="dup-box">
	<div class="dup-box-title">
		<i class="fa fa-bolt"></i> <?php DUP_PRO_U::_e('Installer') ?>
		<div class="dup-box-arrow"></div>
	</div>			
	<div class="dup-box-panel" id="dup-template-install-panel" style="<?php echo $ui_css_install ?>">

        <div class="dpro-panel-optional-txt">
			<b><?php DUP_PRO_U::_e('All values in this section are'); ?> <u><?php DUP_PRO_U::_e('optional'); ?></u>.</b> <br/>
			<?php DUP_PRO_U::_e("These fields can be pre-filled at install time but are not required here."); ?>
		</div>	

		<table>
			<tr>
				<td>
					<input type="checkbox" name="_installer_opts_secure_on" id="_installer_opts_secure_on" <?php echo ($package_template->installer_opts_secure_on) ? "checked='checked'" : ""; ?> onclick="DupPro.Template.ToggleInstallerPassword()" />
					<label for="_installer_opts_secure_on"><?php DUP_PRO_U::_e("Enable Password Protection") ?></label>
					<i class="fa fa-question-circle" 
					   data-tooltip-title="<?php DUP_PRO_U::_e("Password Protection:"); ?>" 
					   data-tooltip="<?php DUP_PRO_U::_e('Enabling this option will allow for basic password protection on the installer. Before running the installer the '
							   . 'password below must be entered before proceeding with an install.  This password is a general deterrent and should not be substituted for properly '
							   . 'keeping your files secure.'); ?>"></i>
					<br/>
					<div class="secure-pass-area">
						<label class="secure-pass-lbl"><?php DUP_PRO_U::_e("Password") ?>:</label> 
						<input type="password" name="_installer_opts_secure_pass" id="_installer_opts_secure_pass" value="<?php echo $installer_pass ?>"  maxlength="50" /><br/>
						<label class="secure-pass-lbl"><?php DUP_PRO_U::_e("Confirm") ?>:</label> 
						<input type="password" name="_installer_opts_secure_pass2" id="_installer_opts_secure_pass2" value="<?php echo $installer_pass ?>"  maxlength="50" />
					</div>
				</td>
			</tr>			
			<!--tr>
				<td>
					<input type="checkbox" name="_installer_opts_skip_scan" id="_installer_opts_skip_scan" <?php echo ($package_template->installer_opts_skip_scan) ? "checked='checked'" : ""; ?> />
					<label for="_installer_opts_skip_scan"><?php DUP_PRO_U::_e("Skip System Scan Screen") ?></label>
					<i class="fa fa-question-circle" 
					   data-tooltip-title="<?php DUP_PRO_U::_e("Skip System Scan:"); ?>" 
					   data-tooltip="<?php DUP_PRO_U::_e('By default every time the installer is opened it will run a simple scan on the server environment.  If the scan check '
							   . 'passes then enabling this option automatically take you to step one of the installer and will skip the system scan screen.'); ?>"></i>
				</td>
			</tr-->
		</table>
		<br/>



		<!-- ===================
		STEP1 TABS -->
		<div data-dpro-tabs="true">
			<ul>
				<li><?php DUP_PRO_U::_e('Basic') ?></li>
				<li id="dpro-cpnl-tab-lbl"><?php DUP_PRO_U::_e('cPanel') ?></li>
			</ul>

			<!-- ===================
			TAB1: Basic -->
			<div>
				 <table class="form-table">
					<tr>
						<td colspan="2">
							<b class="dpro-hdr"><?php DUP_PRO_U::_e('MySQL Server'); ?></b>
						</td>
					</tr>
					<tr valign="top">
						<th><?php _e("Host"); ?></th>
						<td><input type="text" placeholder="localhost" name="installer_opts_db_host" value="<?php echo $package_template->installer_opts_db_host; ?>"></td>
					</tr>	                
					<tr valign="top">
						<th><label><?php _e("Database"); ?></label></th>
						<td><input type="text" placeholder="<?php DUP_PRO_U::_e('valid database name'); ?>" name="installer_opts_db_name" value="<?php echo $package_template->installer_opts_db_name; ?>"></td>
					</tr>	
					<tr valign="top">
						<th><label><?php _e("User"); ?></label></th>
						<td><input type="text" placeholder="<?php DUP_PRO_U::_e('valid database user'); ?>" name="installer_opts_db_user" value="<?php echo $package_template->installer_opts_db_user; ?>"></td>
					</tr>	
				</table>
			</div>

			<!-- ===================
			TAB2: cPanel -->
			<div style="height:550px !important">
				<table class="form-table">
					<tr valign="top">
						<td colspan="2"><b class="dpro-hdr"><?php DUP_PRO_U::_e('cPanel Login'); ?></b></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php DUP_PRO_U::_e("Automation"); ?></label></th>
						<td>
							<input type="checkbox" name="installer_opts_cpnl_enable" id="installer_opts_cpnl_enable" <?php DUP_PRO_UI::echoChecked($package_template->installer_opts_cpnl_enable); ?> >
							<label for="installer_opts_cpnl_enable">Auto Select cPanel</label> 
							<i class="fa fa-question-circle" data-tooltip-title="Auto Select cPanel:" data-tooltip="<?php DUP_PRO_U::_e('Enabling this options will automatically select the cPanel tab when step one of the installer is shown.');?>" ></i>
								&nbsp; &nbsp;					
						</td>
					</tr>				
					<tr valign="top">
						<th scope="row"><label><?php DUP_PRO_U::_e("Host"); ?></label></th>
						<td><input type="text" name="installer_opts_cpnl_host" value="<?php echo $package_template->installer_opts_cpnl_host; ?>"  placeholder="<?php DUP_PRO_U::_e('valid cpanel host address'); ?>"></td>
					</tr>					
					<tr valign="top">
						<th scope="row"><label><?php DUP_PRO_U::_e("User"); ?></label></th>
						<td><input type="text" name="installer_opts_cpnl_user" value="<?php echo $package_template->installer_opts_cpnl_user; ?>"  placeholder="<?php DUP_PRO_U::_e('valid cpanel user login'); ?>"></td>
					</tr>					
					<tr>
						<td colspan="2">
							<b class="dpro-hdr"><?php DUP_PRO_U::_e('MySQL Server'); ?></b>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php _e("Action"); ?></label></th>
						<td>
							<select name="installer_opts_cpnl_db_action" id="cpnl-dbaction">
								<option value="create" <?php echo ($installer_cpnldbaction == 'create') ? 'selected' : ''; ?>>Create A New Database</option>
								<option value="empty"  <?php echo ($installer_cpnldbaction == 'empty')  ? 'selected' : ''; ?>>Connect to Existing Database and Remove All Data</option>
								<!--option value="rename">Connect to Existing Database and Rename Existing Tables</option-->
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php _e("Host"); ?></label></th>
						<td><input type="text" name="installer_opts_cpnl_db_host" value="<?php echo $package_template->installer_opts_cpnl_db_host; ?>" placeholder="<?php DUP_PRO_U::_e('localhost'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label><?php _e("Database"); ?></label></th>
						<td><input type="text" name="installer_opts_cpnl_db_name" value="<?php echo $package_template->installer_opts_cpnl_db_name; ?>" placeholder="<?php DUP_PRO_U::_e('valid database name'); ?>" /></td>
					</tr>		
					<tr valign="top">
						<th scope="row"><label><?php _e("User"); ?></label></th>
						<td><input type="text" name="installer_opts_cpnl_db_user" value="<?php echo $package_template->installer_opts_cpnl_db_user; ?>" placeholder="<?php DUP_PRO_U::_e('valid database user'); ?>" /></td>
					</tr>								
				</table>
			</div>
		</div><br/>
        
   


		<!-- ===================
		STEP1 ADVANCED OPTS -->
		<!--table style="margin-left:10px; width: 100%;">
			<tr>
				<td colspan="4"><b class="dpro-hdr"><?php DUP_PRO_U::_e('Advanced Options'); ?></b></td>
			</tr>				
			<tr valign="top">
				<td style="width:130px"><?php DUP_PRO_U::_e("SSL") ?></td>
				<td style="padding-right: 20px; white-space: nowrap; width:150px">
					<input type="checkbox" <?php DUP_PRO_UI::echoChecked($package_template->installer_opts_ssl_admin) ?> name="_installer_opts_ssl_admin"  id="_installer_opts_ssl_admin" />
					<label for="_installer_opts_ssl_admin"><?php _e("Enforce On Admin"); ?></label>

				</td>
				<td>
					<input type="checkbox" <?php DUP_PRO_UI::echoChecked($package_template->installer_opts_ssl_login) ?> name="_installer_opts_ssl_login"  id="_installer_opts_ssl_login" />
					<label for="_installer_opts_ssl_login"><?php _e("Enforce on Logins"); ?></label>
				</td>
			</tr>	
			<tr>
				<td><?php DUP_PRO_U::_e("Cache") ?></td>									
				<td style="padding-right: 20px; white-space: nowrap">
					<input type="checkbox" <?php DUP_PRO_UI::echoChecked($package_template->installer_opts_cache_wp) ?> name="_installer_opts_cache_wp" id="_installer_opts_cache_wp" />
					<label for="_installer_opts_cache_wp"><?php _e("Keep Cache Enabled"); ?></label>
				</td>
				<td>
					<input type="checkbox" <?php DUP_PRO_UI::echoChecked($package_template->installer_opts_cache_path) ?> name="_installer_opts_cache_path" id="_installer_opts_cache_path" />
					<label for="_installer_opts_cache_path"><?php _e("Keep Home Path"); ?></label>
				</td>
			</tr>						
		</table-->
		
        <input type="hidden"  name="installer_opts_url_new" value="">
		<!-- ===================
		STEP2  OPTS 
		<b class="dpro-hdr"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('STEP 2 - INPUTS'); ?></b>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label><?php _e("New URL"); ?></label></th>
				<td><input type="text" placeholder="http://mynewsite.com" name="installer_opts_url_new" value="<?php echo $package_template->installer_opts_url_new; ?>"></td>
			</tr>
		</table>-->

        <small><?php DUP_PRO_U::_e("All other inputs can be entered at install time.") ?></small>
        <br/><br/>

	</div>
</div>
<br/>
<button class="button button-primary" type="submit"><?php DUP_PRO_U::_e('Save Template'); ?></button>
</form>


<script>
jQuery(document).ready(function ($) 
{
	$('#_archive_filter_dirs').val($('#_archive_filter_dirs').val().trim());

	DupPro.Template.ExcludeTable = function (check) 
	{
		var $cb = $(check);
		if ($cb.is(":checked")) {
			$cb.closest("label").css('textDecoration', 'line-through');
		} else {
			$cb.closest("label").css('textDecoration', 'none');
		}
	}

	/* METHOD: Formats file directory path name on seperate line of textarea */
	DupPro.Template.AddExcludePath = function (path) 
	{
		var text = $("#_archive_filter_dirs").val() + path + ';\n';
		$("#_archive_filter_dirs").val(text);
	};

	/*	Appends a path to the extention filter  */
	DupPro.Template.AddExcludeExts = function (path) 
	{
		var text = $("#_archive_filter_exts").val() + path + ';';
		$("#_archive_filter_exts").val(text);
	};

	/* METHOD: Formats file path name on seperate line of textarea */
	DupPro.Template.AddExcludeFilePath = function (path) 
	{
		var text = $("#_archive_filter_files").val() + path + '/file.ext;\n';
		$("#_archive_filter_files").val(text);
	};

	DupPro.Template.Copy = function() 
	{

		$("#dpro-template-form-action").val('copy-template');
		$("#dpro-template-form").parsley().destroy();
		$("#dpro-template-form").submit();
	};

	DupPro.Template.ToggleInstallerPassword = function () 
	{
		if ($('#_installer_opts_secure_on').is(':checked')) 
		{
			$('.secure-pass-area').show();
			$('#_installer_opts_secure_pass, #_installer_opts_secure_pass2').attr('required', 'true');
			$('#_installer_opts_secure_pass').attr('data-parsley-equalto', '#_installer_opts_secure_pass2');
		} else {
			 $('.secure-pass-area').hide();
			 $('#_installer_opts_secure_pass, #_installer_opts_secure_pass2').removeAttr('required');
			 $('#_installer_opts_secure_pass').removeAttr('data-parsley-equalto');
		}
	};

	//INIT
	DupPro.Template.ToggleInstallerPassword();
	//Default to cPanel tab if used
	$('#cpnl-enable').is(":checked") ? $('#dpro-cpnl-tab-lbl').trigger("click") : null;

});
</script>