<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.global.entity.php');

global $wp_version;
global $wpdb;

$global = DUP_PRO_Global_Entity::get_instance();
$storage_obj = DUP_PRO_Storage_Entity::get_default_local_storage();

$nonce_action = 'duppro-default-storage-edit';
$was_updated = false;

if (isset($_REQUEST['action']))
{
    check_admin_referer($nonce_action);
    if ($_REQUEST['action'] == 'save')
    {        
        $gdrive_error_message = NULL;
		$global->max_default_store_files = (int)$_REQUEST['max_default_store_files'];
        
        $global->save();
        
        $local_folder_created = false;
        $local_folder_creation_error = false;
        
        $was_updated = true;
        $edit_create_text = DUP_PRO_U::__('Edit Default');
    }
}
?>

<style>
    #dup-storage-form input[type="text"], input[type="password"] { width: 250px;}
	#dup-storage-form input#name {width:100%; max-width: 500px}
	#dup-storage-form input#_local_storage_folder {width:100% !important; max-width: 500px}
	td.dpro-sub-title {padding:0; margin: 0}
	td.dpro-sub-title b{padding:20px 0; margin: 0; display:block; font-size:1.25em;}
	input#max_default_store_files {width:50px !important}
</style>

<?php 
	if ($was_updated) 
	{                  
		$update_message = 'Default Storage Provider Updated';
		echo "<div id='message' class='updated below-h2'><p>$update_message</p></div>";          
	}
?>
 <!-- ====================
TOOL-BAR -->
<table class="dpro-edit-toolbar">
	<tr>
		<td></td>
		<td>
			<a href="<?php echo $storage_tab_url; ?>" class="add-new-h2"> <i class="fa fa-database"></i> <?php DUP_PRO_U::_e('All Storage Providers'); ?></a>
			<span><?php DUP_PRO_U::_e('Edit Default Storage'); ?></span>
		</td>
	</tr>
</table>
<hr class="dpro-edit-toolbar-divider"/>
	 
<form id="dpro-default-storage-form" action="<?php echo $edit_default_storage_url; ?>" method="post" data-parsley-ui-enabled="true">
    <?php wp_nonce_field($nonce_action); ?>
    <input type="hidden" id="dup-storage-form-action" name="action" value="save">
 
    <table class="provider form-table">	
		<tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Name"); ?></label></th>
            <td><?php DUP_PRO_U::_e('Default'); ?></td>
        </tr>	
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Type"); ?></label></th>
            <td><?php DUP_PRO_U::_e('Local Server'); ?></td>
        </tr>	
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Location"); ?></label></th>
            <td><?php echo $storage_obj->local_storage_folder; ?></td>
        </tr>			
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
                <label for="max_default_store_files">
					<input data-parsley-errors-container="#max_default_store_files_error_container" id="max_default_store_files" name="max_default_store_files" type="text" data-parsley-type="number" data-parsley-min="0" data-parsley-required="true" value="<?php echo $global->max_default_store_files; ?>" maxlength="4">&nbsp;
					<?php DUP_PRO_U::_e("Number of packages to keep in folder. "); ?> <br/>
					<i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit."); ?></i>
				</label>
                <div id="max_default_store_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
    </table>

    <br style="clear:both" />
    <button class="button button-primary" type="submit"><?php DUP_PRO_U::_e('Save Provider'); ?></button>
</form>

<script>
    jQuery(document).ready(function ($) 
	{
		$('#dpro-default-storage-form').parsley();  
    });
</script>
