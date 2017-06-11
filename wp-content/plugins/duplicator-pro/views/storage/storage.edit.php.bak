<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/utilities/class.gdrive.u.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.storage.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/lib/DropPHP/DropboxClient.php');

global $wp_version;
global $wpdb;

$nonce_action = 'duppro-storage-edit';
$was_updated = false;
$storage_id = isset($_REQUEST['storage_id']) ? esc_html($_REQUEST['storage_id']) : -1;

if ($storage_id == -1)
{
    $storage = new DUP_PRO_Storage_Entity();
    $edit_create_text = DUP_PRO_U::__('Add New');
}
else
{
    $storage = DUP_PRO_Storage_Entity::get_by_id($storage_id);
    $edit_create_text = DUP_PRO_U::__('Edit') . ' ' . $storage->name;
}

if (isset($_REQUEST['action']))
{
    check_admin_referer($nonce_action);
    if ($_REQUEST['action'] == 'save')
    {        
        $gdrive_error_message = NULL;
        
        if($_REQUEST['storage_type'] == DUP_PRO_Storage_Types::GDrive)
        {
            if($storage->gdrive_authorization_state == DUP_PRO_GDrive_Authorization_States::Unauthorized)
            {
				if(!empty($_REQUEST['gdrive-auth-code']))
				{
					try
					{	
						$google_client_auth_code = $_REQUEST['gdrive-auth-code'];
						$google_client = DUP_PRO_GDrive_U::get_raw_google_client();
						$gdrive_token_pair_string = $google_client->authenticate($google_client_auth_code);
										
						$gdrive_token_pair = json_decode($gdrive_token_pair_string, true);

						DUP_PRO_U::log_object('Token pair from authorization', $gdrive_token_pair);

						if (isset($gdrive_token_pair['refresh_token']))
						{
							$storage->gdrive_refresh_token = $gdrive_token_pair['refresh_token'];
							$storage->gdrive_access_token_set_json =  $google_client->getAccessToken(); //$gdrive_token_pair['access_token'];

							DUP_PRO_U::log("Set refresh token to {$storage->gdrive_refresh_token}");
							DUP_PRO_U::log("Set access token set to {$storage->gdrive_access_token_set_json}");

							$storage->gdrive_authorization_state = DUP_PRO_GDrive_Authorization_States::Authorized;
							$storage->save();
						}
						else
						{
							$gdrive_error_message = DUP_PRO_U::__("Couldn't connect. Google Drive refresh token not found.");
						}
					}
					catch (Exception $ex) 
					{
						$gdrive_error_message = sprintf(DUP_PRO_U::__('Problem retrieving Google refresh and access tokens [%s] Please try again!'), $ex->getMessage());
					}
				}
            }
        }
        
        // Checkboxes don't set post values when off so have to manually set these
		$storage->local_storage_folder = trim(DUP_PRO_U::safe_path(stripslashes($_REQUEST['_local_storage_folder'])));
		$storage->local_filter_protection = isset($_REQUEST['_local_filter_protection']);
		
		$storage->ftp_passive_mode = isset($_REQUEST['_ftp_passive_mode']);
        $storage->ftp_ssl = isset($_REQUEST['_ftp_ssl']);
        $storage->ftp_storage_folder = DUP_PRO_U::safe_path(stripslashes($_REQUEST['_ftp_storage_folder']));
        $storage->dropbox_storage_folder = DUP_PRO_U::safe_path(stripslashes($_REQUEST['_dropbox_storage_folder']));       
        $storage->gdrive_storage_folder = DUP_PRO_U::safe_path(stripslashes($_REQUEST['_gdrive_storage_folder']));
        $storage->s3_storage_folder = DUP_PRO_U::safe_path(stripslashes($_REQUEST['_s3_storage_folder']));
        
        $storage->set_post_variables($_REQUEST);
        $storage->save();
        
        $local_folder_created = false;
        $local_folder_creation_error = false;
        
        if($storage->storage_type == DUP_PRO_Storage_Types::Local)
        {
            if((trim($storage->local_storage_folder) != '') && (file_exists($storage->local_storage_folder) == false))
            {
                if(@mkdir($storage->local_storage_folder, 0755, true))
                {
                    $local_folder_created = true;
                }
                else
                {
                    $local_folder_creation_error = true;            
                }
            }
        }
    
        $was_updated = true;
        $edit_create_text = DUP_PRO_U::__('Edit') . ': ' . $storage->name;
    }
    else if ($_REQUEST['action'] == 'copy-storage')
    {
        $source_id = $_REQUEST['duppro-source-storage-id'];
        if ($source_id != -1)
        {
            $storage->copy_from_source_id($source_id);
            $storage->save();
        }
    } 
    else if ($_REQUEST['action'] == 'gdrive-revoke-access')
    {
        $google_client = DUP_PRO_GDrive_U::get_raw_google_client();
        
        if(!$google_client->revokeToken($storage->gdrive_refresh_token))
        {
            DUP_PRO_U::log("Problem revoking Google Drive refresh token");
        }
        
		$gdrive_access_token = json_decode($storage->gdrive_access_token_set_json)->access_token;
		
        if(!$google_client->revokeToken($gdrive_access_token))
        {
            DUP_PRO_U::log("Problem revoking Google Drive access token");       
        }
        
        $storage->gdrive_access_token_set_json = '';
        $storage->gdrive_refresh_token = '';        
        $storage->gdrive_authorization_state = DUP_PRO_GDrive_Authorization_States::Unauthorized;
        $storage->save();        
    }
}

if ($storage->dropbox_authorization_state == DUP_PRO_Dropbox_Authorization_States::Authorized)
{
    $dropbox = DUP_PRO_Storage_Entity::get_dropbox_client();
    $access_token = $storage->get_dropbox_combined_access_token();
    $dropbox->SetAccessToken($access_token);
    $account_info = $dropbox->GetAccountInfo();
}

if(DUP_PRO_U::PHP53())
{
	if($storage->gdrive_authorization_state == DUP_PRO_GDrive_Authorization_States::Authorized)
	{
		try
		{
			$google_client = $storage->get_full_google_client();
			$gdrive_user_info = DUP_PRO_GDrive_U::get_user_info($google_client);    
		}
		catch (Exception $e)
		{
			// This is an oddball recommendation - don't queue it in system global entity
			$error_text = 'Error retreving Google Client' . $e->getMessage();
			$fix_text = "Delete the Google endpoint and recreate.";
			
			echo DUP_PRO_U::__("$error_text: ** RECOMMENDATION: $fix_text");		
							
			die;
		}
	}
	else
	{
		$google_client = DUP_PRO_GDrive_U::get_raw_google_client();	
	}
}

$storages = DUP_PRO_Storage_Entity::get_all();
$storage_count = count($storages);

?>

<style>
    table.dpro-edit-toolbar select {float:left}
    #dup-storage-form input[type="text"], input[type="password"] { width: 250px;}
	#dup-storage-form input#name {width:100%; max-width: 500px}
    #dup-storage-form #ftp_timeout {width:100px !important} 
	#dup-storage-form input#_local_storage_folder, input#_ftp_storage_folder {width:100% !important; max-width: 500px}
    .provider { display:none; }
    .stage {display:none; }
	td.dpro-sub-title {padding:0; margin: 0}
	td.dpro-sub-title b{padding:20px 0; margin: 0; display:block; font-size:1.25em;}
	input.dpro-storeage-folder-path {width: 450px !important}
	
	/*Common */
	#s3_max_files, #dropbox_max_files, #ftp_max_files, #local_max_files, #gdrive_max_files {width:50px !important}
	
	/*DropBox*/
    td.dropbox-authorize {line-height:30px; padding-top:0px !important;}
    div#dropbox-account-info label {display: inline-block; width:100px; font-weight: bold} 
	button#dpro-dropbox-connect-btn {margin:10px 0}
	
	/*Google Drive */
	td.gdrive-authorize {line-height:25px}
	div#dpro-gdrive-steps {display:none}
	div#dpro-gdrive-steps div {margin: 0 0 20px 0}
	div#dpro-gdrive-connect-progress {display:none}
</style>

<form id="dup-storage-form" action="<?php echo $edit_storage_url; ?>" method="post" data-parsley-ui-enabled="true">
    <?php wp_nonce_field($nonce_action); ?>
    <input type="hidden" id="dup-storage-form-action" name="action" value="save">
    <input type="hidden" name="storage_id" value="<?php echo $storage->id; ?>">
    <input type="hidden" id="dropbox_access_token" name="dropbox_access_token" value="<?php echo $storage->dropbox_access_token ?>">
    <input type="hidden" id="dropbox_access_token_secret" name="dropbox_access_token_secret" value="<?php echo $storage->dropbox_access_token_secret ?>">
    <input type="hidden" id="dropbox_authorization_state" name="dropbox_authorization_state" value="<?php echo $storage->dropbox_authorization_state ?>">

    <!-- ====================
	TOOL-BAR -->
    <table class="dpro-edit-toolbar">
        <tr>
            <td>
                <?php  if ($storage_count > 0) : ?>
                    <select name="duppro-source-storage-id">
                        <option value="-1" selected="selected"><?php _e("Copy From"); ?></option>
                        <?php
                        foreach ($storages as $copy_storage)
                        {
							echo ($copy_storage->id != $storage->id) 
								? "<option value='{$copy_storage->id}'>{$copy_storage->name}</option>"
								: '';
                        }
                        ?>
                    </select>
                    <input type="button" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" onclick="DupPro.Storage.Copy()">
                <?php else :  ?>
                    <select disabled="disabled"><option value="-1" selected="selected"><?php _e("Copy From"); ?></option></select>
                    <input type="button" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" disabled="disabled">
				<?php endif; ?>
            </td>
            <td>
                <a href="<?php echo $storage_tab_url; ?>" class="add-new-h2"> <i class="fa fa-database"></i> <?php DUP_PRO_U::_e('All Providers'); ?></a>
                <span><?php echo $edit_create_text; ?></span>
            </td>
        </tr>
    </table>
    <hr class="dpro-edit-toolbar-divider"/>
    
    <!-- ====================
    SUB-TABS -->
    <?php 
		if ($was_updated) 
        {      
            if($gdrive_error_message != NULL)
            {                   
                echo "<div id='message' class='error below-h2'><p><i class='fa fa-exclamation-triangle'></i> $gdrive_error_message </p></div>";
            }
            else if($local_folder_created)
            {
                $update_message = sprintf(DUP_PRO_U::__('Storage Provider Updated - Folder %1$s was created'), $storage->local_storage_folder);
                echo "<div id='message' class='updated below-h2'><p>$update_message</p></div>";
            }
            else
            {
                if($local_folder_creation_error)
                {
                    $update_message = sprintf(DUP_PRO_U::__('Storage Provider Updated - Unable to create folder %1$s'), $storage->local_storage_folder);
                    echo "<div id='message' class='updated below-h2'><p><i class='fa fa-exclamation-triangle'></i> $update_message </p></div>";
                }
                else
                {
                    $update_message = DUP_PRO_U::__('Storage Provider Updated');
                    echo "<div id='message' class='updated below-h2'><p>$update_message</p></div>";
                }
            }
        }
	?>

    <table class="form-table top-entry">
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Name"); ?></label></th>
            <td>
                <input data-parsley-errors-container="#name_error_container" type="text" id="name" name="name" value="<?php echo $storage->name; ?>" autocomplete="off" />
                <div id="name_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Notes"); ?></label></th>
            <td><textarea id="notes" name="notes" style="width:100%; max-width: 500px"><?php echo $storage->notes; ?></textarea></td>
        </tr>			
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::_e("Type"); ?></label></th>
            <td>
                <select id="change-mode" name="storage_type" onchange="DupPro.Storage.ChangeMode()">
					<?php if(DUP_PRO_U::PHP53()) : ?>
						<option <?php DUP_PRO_U::echo_selected($storage->storage_type == DUP_PRO_Storage_Types::S3); ?> value="<?php echo DUP_PRO_Storage_Types::S3; ?>"><?php DUP_PRO_U::_e("Amazon S3"); ?></option>
					<?php endif;?>					
					
					<option <?php DUP_PRO_U::echo_selected($storage->storage_type == DUP_PRO_Storage_Types::Dropbox); ?> value="<?php echo DUP_PRO_Storage_Types::Dropbox; ?>"><?php DUP_PRO_U::_e("Dropbox"); ?></option>
                    <option <?php DUP_PRO_U::echo_selected($storage->storage_type == DUP_PRO_Storage_Types::FTP); ?> value="<?php echo DUP_PRO_Storage_Types::FTP; ?>"><?php DUP_PRO_U::_e("FTP"); ?></option>
					<?php if(DUP_PRO_U::PHP53()) : ?>
						<option <?php DUP_PRO_U::echo_selected($storage->storage_type == DUP_PRO_Storage_Types::GDrive); ?> value="<?php echo DUP_PRO_Storage_Types::GDrive; ?>"><?php DUP_PRO_U::_e("Google Drive"); ?></option>
					<?php endif;?>
					<option <?php DUP_PRO_U::echo_selected($storage->storage_type == DUP_PRO_Storage_Types::Local); ?> value="<?php echo DUP_PRO_Storage_Types::Local; ?>"><?php DUP_PRO_U::_e("Local Server"); ?></option>                    
                </select>
				<?php
				if(DUP_PRO_U::PHP53() == false)
				{
					echo '<p><i>' . sprintf(DUP_PRO_U::__('Google Drive and Amazon S3 not available for this version of PHP (%s). Must have PHP 5.3.2 or greater.'), PHP_VERSION) .'</i></p>';
				}
				?>
            </td>
        </tr>	
    </table> <hr size="1" />
	
	
	<!-- ===============================
    AMAZON S3 PROVIDER -->
    <table id="provider-<?php echo DUP_PRO_Storage_Types::S3 ?>" class="form-table provider" >
		<tr>
			<td colspan="2" style="padding-left:0">
				<i><?php DUP_PRO_U::_e("Amazon S3 Setup Guide: <a target='_blank' href='https://snapcreek.com/duplicator/docs/https://snapcreek.com/duplicator/docs/amazon-s3-step-by-step/'>Step-by-Step</a> and <a href='https://snapcreek.com/duplicator/docs/amazon-s3-policy-setup/' target='_blank'>User Bucket Policy</a>"); ?></i>
			</td>
		</tr>		
		<tr>
			<td class="dpro-sub-title" colspan="2">
				<b><?php DUP_PRO_U::_e("Credentials"); ?></b>				
			</td>
		</tr>
        <tr>
            <th scope="row"><label for="s3_access_key"><?php DUP_PRO_U::_e("Access Key"); ?></label></th>
            <td>
                <input id="s3_access_key" name="s3_access_key" data-parsley-errors-container="#s3_access_key_error_container" type="text" autocomplete="off" value="<?php echo $storage->s3_access_key ?>">
                <div id="s3_access_key_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
		<tr>
            <th scope="row">
				<label for="s3_secret_key"><?php DUP_PRO_U::_e("Secret Key"); ?></label>				
			</th>
			
            <td>
                <input id="s3_secret_key" name="s3_secret_key" data-parsley-errors-container="#s3_secret_key_error_container" type="password" autocomplete="off" value="<?php echo $storage->s3_secret_key ?>">
                <div id="s3_secret_key_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
		<tr>
			<td class="dpro-sub-title" colspan="2"><b><?php DUP_PRO_U::_e("Settings"); ?></b></td>
		</tr>
		<tr>
            <th scope="row"><label for="s3_bucket"><?php DUP_PRO_U::_e("Bucket"); ?></label></th>
            <td>
                <input id="s3_bucket" name="s3_bucket" type="text" value="<?php echo $storage->s3_bucket; ?>">
                <p><i><?php DUP_PRO_U::_e("S3 Bucket where you want to save the backups."); ?></i></p>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="s3_region"><?php DUP_PRO_U::_e("Region"); ?></label></th>
            <td>		
                <select id="s3_region" name="s3_region">
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'us-east-1'); ?> value="us-east-1"><?php DUP_PRO_U::_e("US East (N. Virginia)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'us-west-2'); ?> value="us-west-2"><?php DUP_PRO_U::_e("US West (Oregon)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'us-west-1'); ?> value="us-west-1"><?php DUP_PRO_U::_e("US West (N. California)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'eu-west-1'); ?> value="eu-west-1"><?php DUP_PRO_U::_e("EU (Ireland)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'eu-central-1'); ?> value="eu-central-1"><?php DUP_PRO_U::_e("EU (Frankfurt)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'ap-southeast-1'); ?> value="ap-southeast-1"><?php DUP_PRO_U::_e("Asia Pacific (Singapore)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'ap-southeast-2'); ?> value="ap-southeast-2"><?php DUP_PRO_U::_e("Asia Pacific (Sydney)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'ap-northeast-1'); ?> value="ap-northeast-1"><?php DUP_PRO_U::_e("Asia Pacific (Tokyo)"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_region == 'sa-east-1'); ?> value="sa-east-1"><?php DUP_PRO_U::_e("South America (Sao Paulo)"); ?></option>					
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="_s3_storage_folder"><?php DUP_PRO_U::_e("Storage Folder"); ?></label></th>
            <td>
                <input id="_s3_storage_folder" name="_s3_storage_folder" type="text" value="<?php echo $storage->s3_storage_folder; ?>">
                <p><i><?php DUP_PRO_U::_e("Folder where packages will be stored. This should be unique for each web-site using Duplicator."); ?></i></p>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="s3_storage_class"><?php DUP_PRO_U::_e("Storage Class"); ?></label></th>
            <td>		
                <select id="s3_storage_class" name="s3_storage_class">
					<option <?php DUP_PRO_U::echo_selected($storage->s3_storage_class == 'REDUCED_REDUNDANCY '); ?> value="REDUCED_REDUNDANCY "><?php DUP_PRO_U::_e("Reduced Redundancy"); ?></option>
					<option <?php DUP_PRO_U::echo_selected($storage->s3_storage_class == 'STANDARD'); ?> value="STANDARD"><?php DUP_PRO_U::_e("Standard"); ?></option>					
					<option <?php DUP_PRO_U::echo_selected($storage->s3_storage_class == 'STANDARD_IA'); ?> value="STANDARD_IA"><?php DUP_PRO_U::_e("Standard IA"); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="s3_max_files"><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
				<label for="s3_max_files">
					<input id="s3_max_files" name="s3_max_files" data-parsley-errors-container="#s3_max_files_error_container" type="text" value="<?php echo $storage->s3_max_files; ?>">
					<?php DUP_PRO_U::_e("Number of packages to keep in folder."); ?><br/>
					<i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit."); ?></i>
				</label>
                <div id="s3_max_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Connection"); ?></label></th>
            <td>
                <button class="button button_s3_test" id="button_s3_send_file_test" type="button" onclick="DupPro.Storage.S3.SendFileTest();">
                   <i class="fa fa-cloud-upload"></i> <?php DUP_PRO_U::_e('Test S3 Connection'); ?>
                </button>
                <p><i><?php DUP_PRO_U::_e("Test connection by sending and receiving a small file to/from the account."); ?></i></p>
            </td>
        </tr>     
    </table>

    <!-- ===============================
    DROP-BOX PROVIDER -->
    <table id="provider-<?php echo DUP_PRO_Storage_Types::Dropbox ?>" class="form-table provider" >
        <tr>
            <th scope="row"><label><?php DUP_PRO_U::_e("Authorization"); ?></label></th>
            <td class="dropbox-authorize">
                <div class='authorization-state' id="state-unauthorized">
                    <!-- CONNECT -->	
                    <button id="dpro-dropbox-connect-btn" type="button" class="button button-large" onclick="DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_REQUEST_TOKEN);">
                        <i class="fa fa-plug"></i> <?php DUP_PRO_U::_e('Connect to Dropbox'); ?> 
						<img src="<?php echo DUPLICATOR_PRO_IMG_URL ?>/dropbox-24.png" style='vertical-align: middle; margin:-2px 0 0 3px; height:18px; width:18px' />
                    </button>
                </div>

                <div class='authorization-state' id="state-waiting-for-request-token">
                    <div style="padding:10px">
                        <i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e('Getting Dropbox request token'); ?>...
                    </div>
                </div>

                <div class='authorization-state' id="state-waiting-for-auth-button-click">      
                    <!-- STEP 2 -->
                    <b><?php DUP_PRO_U::_e("Step 1:"); ?></b>&nbsp; 
                    <?php DUP_PRO_U::_e(' Duplicator needs to authorize at the Dropbox.com website.'); ?> 
                    <br/>
                    <button id="auth-redirect" type="button" class="button button-large" onclick="DupPro.Storage.Dropbox.Authorize()">
                        <i class="fa fa-user"></i> <?php DUP_PRO_U::_e('Authorize Dropbox'); ?>
                    </button>
                    <br/><br/>

                    <!-- STEP 3 -->
                    <b><?php DUP_PRO_U::_e("Step 2:"); ?></b>&nbsp; 
                    <?php DUP_PRO_U::_e('Finalize Dropbox validation by clicking the "Finalize Setup" button.'); ?> 
                    <br/>
                    <button id="auth-validate" type="button" class="button button-large" onclick="DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_ACCESS_TOKEN);">
                        <i class="fa fa-check-square"></i> <?php DUP_PRO_U::_e('Finalize Setup'); ?>
                    </button>
                    <br/><br/>

					<b><?php DUP_PRO_U::_e("Cancel:"); ?></b>&nbsp; 
                    <?php DUP_PRO_U::_e('Close this setup process'); ?>
					 <br/>
                    <button type="button" class="button button-large"  onclick="DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);">
						<?php DUP_PRO_U::_e('Cancel Authorization'); ?>
					</button>                    
                </div>

                <div class='authorization-state' id="state-waiting-for-access-token">
                    <div><i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e('Performing final authorization...Please wait'); ?></div>
                </div>

                <div class='authorization-state' id="state-authorized" style="margin-top:-5px">
                    <?php if ($storage->dropbox_authorization_state == DUP_PRO_Dropbox_Authorization_States::Authorized) : ?>
                        <h3>
							<img src="<?php echo DUPLICATOR_PRO_IMG_URL ?>/dropbox-24.png" style='vertical-align: bottom; margin-bottom: 5px' />
                            <?php DUP_PRO_U::_e('Dropbox Account'); ?><br/>
                            <i class="dpro-edit-info"><?php DUP_PRO_U::_e('Duplicator has been authorized to access this user\'s Dropbox account'); ?></i>
                        </h3>
                        <div id="dropbox-account-info">
                            <label><?php DUP_PRO_U::_e('Name'); ?>:</label>
                            <?php echo $account_info->display_name; ?><br/>

                            <label><?php DUP_PRO_U::_e('Email'); ?>:</label>
                            <?php echo $account_info->email; ?>
                        </div>
                    <?php endif; ?>
                    <br/>

                    <button type="button" class="button button-large" onclick='DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);'>
                        <?php DUP_PRO_U::_e('Cancel Authorization'); ?>
                    </button><br/>
                    <i class="dpro-edit-info"><?php DUP_PRO_U::_e('Disassociates storage provider with the Dropbox account. Will require re-authorization.'); ?> </i>
                </div>
            </td>
        </tr>    
        <tr>
            <th scope="row"><label for="_dropbox_storage_folder"><?php DUP_PRO_U::_e("Storage Folder"); ?></label></th>
            <td>
                <b>//Dropbox/Apps/Duplicator Pro/</b>
                <input id="_dropbox_storage_folder" name="_dropbox_storage_folder" type="text" value="<?php echo $storage->dropbox_storage_folder; ?>" class="dpro-storeage-folder-path" />
                <p><i><?php DUP_PRO_U::_e("Folder where packages will be stored. This should be unique for each web-site using Duplicator."); ?></i></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
                <label for="dropbox_max_files">
					<input data-parsley-errors-container="#dropbox_max_files_error_container" id="dropbox_max_files" name="dropbox_max_files" type="text" value="<?php echo $storage->dropbox_max_files; ?>" maxlength="4">
					<?php DUP_PRO_U::_e("Number of packages to keep in folder."); ?> <br/>
                    <i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit."); ?></i>
				</label>
                <div id="dropbox_max_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Connection"); ?></label></th>
            <td>
                <button class="button button_dropbox_test" id="button_dropbox_send_file_test" type="button" onclick="DupPro.Storage.Dropbox.SendFileTest();">
					<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e('Test Dropbox Connection'); ?>
				</button>
                <p><i><?php DUP_PRO_U::_e("Test connection by sending and receiving a small file to/from the account."); ?></i></p>
            </td>
        </tr>
    </table>	

    <!-- ===============================
    FTP PROVIDER -->
    <table id="provider-<?php echo DUP_PRO_Storage_Types::FTP ?>" class="form-table provider" >
		<tr>
			<td class="dpro-sub-title" colspan="2"><b><?php DUP_PRO_U::_e("Credentials"); ?></b></td>
		</tr>
        <tr>
            <th scope="row"><label for="ftp_server"><?php DUP_PRO_U::_e("Server"); ?></label></th>
            <td>
                <input id="ftp_server" name="ftp_server" data-parsley-errors-container="#ftp_server_error_container" type="text" autocomplete="off" value="<?php echo $storage->ftp_server ?>">
                <label for="ftp_server"><?php DUP_PRO_U::_e("Port"); ?></label> <input name="ftp_port" id="ftp_port" data-parsley-errors-container="#ftp_server_error_container" type="text" style="width:75px"  value="<?php echo $storage->ftp_port ?>">
                <div id="ftp_server_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ftp_username"><?php DUP_PRO_U::_e("Username"); ?></label></th>
            <td><input id="ftp_username" name="ftp_username" type="text" autocomplete="off" value="<?php echo $storage->ftp_username; ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="ftp_password"><?php DUP_PRO_U::_e("Password"); ?></label></th>
            <td>
                <input id="ftp_password" name="ftp_password" type="password" autocomplete="off" value="<?php echo $storage->ftp_password; ?>" >
			</td>
        </tr>   
        <tr>
            <th scope="row"><label for="ftp_password2"><?php DUP_PRO_U::_e("Retype Password"); ?></label></th>
            <td>
				<input id="ftp_password2" name="ftp_password2" type="password"  autocomplete="off" value="<?php echo $storage->ftp_password; ?>" data-parsley-errors-container="#ftp_password2_error_container"  data-parsley-trigger="change" data-parsley-equalto="#ftp_password" data-parsley-equalto-message="<?php DUP_PRO_U::_e("Passwords do not match"); ?>" /><br/>
				<div id="ftp_password2_error_container" class="duplicator-error-container"></div>
			</td>
        </tr>  
		<tr>
			<td class="dpro-sub-title" colspan="2"><b><?php DUP_PRO_U::_e("Settings"); ?></b></td>
		</tr>
        <tr>
            <th scope="row"><label for="_ftp_storage_folder"><?php DUP_PRO_U::_e("Storage Folder"); ?></label></th>
            <td>
                <input id="_ftp_storage_folder" name="_ftp_storage_folder" type="text" value="<?php echo $storage->ftp_storage_folder; ?>">
                <p><i><?php DUP_PRO_U::_e("Folder where packages will be stored. This should be unique for each web-site using Duplicator."); ?></i></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ftp_max_files"><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
                <label for="ftp_max_files">
					<input id="ftp_max_files" name="ftp_max_files" data-parsley-errors-container="#ftp_max_files_error_container" type="text" value="<?php echo $storage->ftp_max_files; ?>">
					<?php DUP_PRO_U::_e("Number of packages to keep in folder."); ?> <br/>         
					<i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit. "); ?></i>
				</label>              
				<div id="ftp_max_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ftp_timeout_in_secs"><?php DUP_PRO_U::_e("Timeout"); ?></label></th>
            <td>
                <input id="ftp_timeout" name="ftp_timeout_in_secs" data-parsley-errors-container="#ftp_timeout_error_container" type="text" value="<?php echo $storage->ftp_timeout_in_secs; ?>"> <label for="ftp_timeout_in_secs"><?php DUP_PRO_U::_e("seconds"); ?></label>
                <div id="ftp_timeout_error_container" class="duplicator-error-container"></div>
            </td>            
        </tr>
        <tr>
            <th scope="row"><label for="ftp_ssl"><?php DUP_PRO_U::_e("SSL-FTP"); ?></label></th>
            <td>
                <input name="_ftp_ssl" <?php DUP_PRO_U::echo_checked($storage->ftp_ssl); ?> class="checkbox" value="1" type="checkbox" id="_ftp_ssl" > 
                <label for="_ftp_ssl"><?php DUP_PRO_U::_e("Use explicit SSL-FTP connection."); ?></label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="_ftp_passive_mode"><?php DUP_PRO_U::_e("Passive Mode"); ?></label></th>
            <td>
                <input <?php DUP_PRO_U::echo_checked($storage->ftp_passive_mode); ?> class="checkbox" value="1" type="checkbox" name="_ftp_passive_mode" id="_ftp_passive_mode">
                <label for="_ftp_passive_mode"><?php DUP_PRO_U::_e("Use FTP Passive Mode."); ?></label>
            </td>
        </tr>        
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Connection"); ?></label></th>
            <td>
                <button class="button button_ftp_test" id="button_ftp_send_file_test" type="button" onclick="DupPro.Storage.FTP.SendFileTest();">
                   <i class="fa fa-cloud-upload"></i> <?php DUP_PRO_U::_e('Test FTP Connection'); ?>
                </button>
                <p><i><?php DUP_PRO_U::_e("Test connection by sending and receiving a small file to/from the account."); ?></i></p>
            </td>
        </tr>     
    </table>	

    <!-- ===============================
    GOOGLE DRIVE PROVIDER -->
    <table id="provider-<?php echo DUP_PRO_Storage_Types::GDrive ?>" class="form-table provider" >
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Authorization"); ?></label></th>
            <td class="gdrive-authorize">       
				<?php if($storage->gdrive_authorization_state == DUP_PRO_GDrive_Authorization_States::Unauthorized) : ?>
                    <div class='gdrive-authorization-state' id="gdrive-state-unauthorized">
                        <!-- CONNECT -->
						<div id="dpro-gdrive-connect-btn-area">
							<button id="dpro-gdrive-connect-btn" type="button" class="button button-large" onclick="DupPro.Storage.GDrive.GoogleGetAuthUrl(); ">
								<i class="fa fa-plug"></i> <?php DUP_PRO_U::_e('Connect to Google Drive'); ?>
								<img src="<?php echo DUPLICATOR_PRO_IMG_URL ?>/gdrive-24.png" style='vertical-align: middle; margin:-2px 0 0 3px; height:18px; width:18px' />
							</button>   
						</div>
						<div class='authorization-state' id="dpro-gdrive-connect-progress">
							<div style="padding:10px">
								<i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e('Getting Google Drive Request Token'); ?>...
							</div>
						</div>
						
						<!-- STEPS -->
						<div id="dpro-gdrive-steps">
							<div>                                               
								<b><?php DUP_PRO_U::_e('Step 1:'); ?></b> <?php DUP_PRO_U::_e("Duplicator needs to authorize Google Drive."); ?>  <br/>
								<button id="gdrive-auth-window-button" class="button" onclick="DupPro.Storage.GDrive.OpenAuthPage(); return false;">
									<i class="fa fa-user"></i> <?php DUP_PRO_U::_e("Authorize Google Drive"); ?>
								</button>                            
							</div>

							<div id="gdrive-auth-code-area">                                               
								<b><?php DUP_PRO_U::_e('Step 2:'); ?></b> <?php DUP_PRO_U::_e("Paste code from Google authorization page."); ?> <br/>
								<input style="width:400px" id="gdrive-auth-code" name="gdrive-auth-code" />
							</div>

							<b><?php DUP_PRO_U::_e('Step 3:'); ?></b> <?php DUP_PRO_U::_e('Finalize Google Drive setup by clicking the "Finalize Setup" button.') ?><br/>
							<button type="button" class="button" onclick="DupPro.Storage.GDrive.FinalizeSetup(); return false;"><i class="fa fa-check-square"></i> <?php DUP_PRO_U::_e('Finalize Setup'); ?></button>
						</div>
                    </div>
                <?php  else : ?>
					<div class='gdrive-authorization-state' id="gdrive-state-authorized" style="margin-top:-5px">

						<?php if($gdrive_user_info != null) : ?>
							<h3>
								<img src="<?php echo DUPLICATOR_PRO_IMG_URL ?>/gdrive-24.png" style='vertical-align: bottom' />
								<?php DUP_PRO_U::_e('Google Drive Account'); ?><br/>
								<i class="dpro-edit-info"><?php DUP_PRO_U::_e('Duplicator has been authorized to access this user\'s Google Drive account'); ?></i>
							</h3>
							<div id="gdrive-account-info">                        
								<label><?php DUP_PRO_U::_e('Name'); ?>:</label>
								<?php echo "$gdrive_user_info->givenName $gdrive_user_info->familyName"; ?><br/>

								<label><?php DUP_PRO_U::_e('Email'); ?>:</label>
								<?php echo $gdrive_user_info->email; ?>
							</div><br/>
						<?php else : ?>
							<div><?php DUP_PRO_U::_e('Error retrieving user information'); ?></div>
						<?php endif ?>

						<button type="button" class="button button-large" onclick='DupPro.Storage.GDrive.CancelAuthorization();'>
							<?php DUP_PRO_U::_e('Cancel Authorization'); ?>
						</button><br/>
						<i class="dpro-edit-info"><?php DUP_PRO_U::_e('Disassociates storage provider with the Google Drive account. Will require re-authorization.'); ?> </i>
					</div>
                <?php endif ?>
            </td>
        </tr>    
        <tr>
            <th scope="row"><label for="_gdrive_storage_folder"><?php DUP_PRO_U::_e("Storage Folder"); ?></label></th>
            <td>
                <b>//Google Drive/</b>
                <input id="_gdrive_storage_folder" name="_gdrive_storage_folder" type="text" value="<?php echo $storage->gdrive_storage_folder; ?>"  class="dpro-storeage-folder-path"/>
                <p>
					<i><?php DUP_PRO_U::_e("Folder where packages will be stored. This should be unique for each web-site using Duplicator."); ?></i>
					<i class="fa fa-question-circle" data-tooltip-title="<?php DUP_PRO_U::_e("Storage Folder Notice"); ?>" data-tooltip="<?php DUP_PRO_U::_e('If the directory path above is already in Google Drive before connecting then a duplicate folder name will be made in the same path. This is because the plugin only has rights to folders it creates.'); ?>"></i>
				
				</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
                <label for="gdrive_max_files">
					<input data-parsley-errors-container="#gdrive_max_files_error_container" id="gdrive_max_files" name="gdrive_max_files" type="text" value="<?php echo $storage->gdrive_max_files; ?>" maxlength="4">&nbsp;
                    <?php DUP_PRO_U::_e("Number of packages to keep in folder."); ?> <br/>
					<i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit."); ?></i>
				</label>
                <div id="gdrive_max_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Connection"); ?></label></th>
            <td>
				<?php
					$gdrive_test_button_disabled = '';
					if($storage->id == -1 || (($storage->storage_type == DUP_PRO_Storage_Types::GDrive) && ($storage->gdrive_access_token_set_json == '')))
					{
						$gdrive_test_button_disabled = 'disabled';
					}
					
				?>
                <button class="button button_gdrive_test" id="button_gdrive_send_file_test" type="button" onclick="DupPro.Storage.GDrive.SendFileTest();" <?php echo $gdrive_test_button_disabled; ?>>
					<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e('Test Google Drive Connection'); ?>
				</button>
                <p><i><?php DUP_PRO_U::_e("Test connection by sending and receiving a small file to/from the account."); ?></i></p>
            </td>
        </tr>
    </table>
	
    <!-- ===============================
    LOCAL PROVIDER -->
    <table id="provider-<?php echo DUP_PRO_Storage_Types::Local ?>" class="provider form-table">
        <tr valign="top">
            <th scope="row">
                <label onclick="jQuery('#_local_storage_folder').val('<?php echo rtrim(DUPLICATOR_PRO_WPROOTPATH, '/'); ?>')">
                    <?php DUP_PRO_U::_e("Storage Folder"); ?>
				</label>
            </th>
            <td>
                <input data-parsley-errors-container="#_local_storage_folder_error_container" data-parsley-required="true"  type="text" id="_local_storage_folder" name="_local_storage_folder" data-parsley-pattern=".*" value="<?php echo $storage->local_storage_folder; ?>"   />
                <p>
					<i><?php DUP_PRO_U::_e("On Linux systems start with '/'. On Windows use drive letters. (e.g. /mypath or E:/mypath)"); ?></i>
				</p>
				<div id="_local_storage_folder_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>	
		<tr>
			<th scope="row"><label for="local_filter_protection"><?php DUP_PRO_U::_e("Filter Protection"); ?></label></th>
            <td>
				<input id="_local_filter_protection" name="_local_filter_protection" type="checkbox" <?php DUP_PRO_U::echo_checked($storage->local_filter_protection); ?> onchange="DupPro.Storage.LocalFilterToggle()">&nbsp;
				<label for="_local_filter_protection">
					<?php DUP_PRO_U::_e("Filter the Storage Folder (recommended)"); ?>	
				</label>
				<div style="padding-top:6px">
					<i><?php DUP_PRO_U::_e("When checked this will exclude the 'Storage Folder' and all of its content and sub-folders from package builds."); ?></i>
					<div id="_local_filter_protection_message" style="display:none; color:maroon">
						<i><?php DUP_PRO_U::_e("Unchecking filter protection is not recommended.  This setting helps to prevents packages from getting bundled in other packages."); ?></i>
					</div>
				</div>
            </td>
        </tr>		
        <tr>
            <th scope="row"><label for=""><?php DUP_PRO_U::_e("Max Packages"); ?></label></th>
            <td>
                <label for="local_max_files">
					<input data-parsley-errors-container="#local_max_files_error_container" id="local_max_files" name="local_max_files" type="text" value="<?php echo $storage->local_max_files; ?>" maxlength="4">&nbsp;
					<?php DUP_PRO_U::_e("Number of packages to keep in folder."); ?><br/>
					<i><?php DUP_PRO_U::_e("When this limit is exceeded, the oldest package will be deleted. Set to 0 for no limit."); ?></i>
				</label>
                <div id="local_max_files_error_container" class="duplicator-error-container"></div>
            </td>
        </tr>
    </table>	
	

    <br style="clear:both" />
    <button class="button button-primary" type="submit"><?php DUP_PRO_U::_e('Save Provider'); ?></button>
</form>

<script>
    jQuery(document).ready(function ($) {

        var counter = 0;

        DupPro.Storage.Modes = {
            LOCAL: 0,
            DROPBOX: 1,
            FTP: 2,
			GDRIVE: 3,
			S3: 4
        };

        DupPro.Storage.BindParsley = function (mode) {

            if(counter++ > 0)
            {
                $('#dup-storage-form').parsley().destroy();
            }

            $('#dup-storage-form input').removeAttr('data-parsley-required');
            $('#dup-storage-form input').removeAttr('data-parsley-type');
            $('#dup-storage-form input').removeAttr('data-parsley-range');
            $('#dup-storage-form input').removeAttr('data-parsley-min');

            // Now add the appropriate attributes
            $('#name').attr('data-parsley-required', 'true');
            
            switch (parseInt(mode)) {

                case DupPro.Storage.Modes.LOCAL:
					$('#_local_storage_folder').attr('data-parsley-required', 'true');
					
					$('#local_max_files').attr('data-parsley-required', 'true');
                    $('#local_max_files').attr('data-parsley-type', 'number');
                    $('#local_max_files').attr('data-parsley-min', '0');    
                    break;

                case DupPro.Storage.Modes.DROPBOX:
                    $('#dropbox_max_files').attr('data-parsley-required', 'true');
                    $('#dropbox_max_files').attr('data-parsley-type', 'number');
                    $('#dropbox_max_files').attr('data-parsley-min', '0');                    
                    break;
                    
                case DupPro.Storage.Modes.FTP:
                    $('#ftp_server').attr('data-parsley-required', 'true');
                    $('#ftp_port').attr('data-parsley-required', 'true');
					
					$('#ftp_password, #ftp_password2').attr('data-parsley-required', 'true');
                    $('#ftp_max_files').attr('data-parsley-required', 'true');
                    $('#ftp_timeout').attr('data-parsley-required', 'true');

                    $('#ftp_port').attr('data-parsley-type', 'number');
                    $('#ftp_max_files').attr('data-parsley-type', 'number');
                    $('#ftp_timeout').attr('data-parsley-type', 'number');

                    $('#ftp_port').attr('data-parsley-range', '[1,65535]');

                    $('#ftp_max_files').attr('data-parsley-min', '0');
                    $('#ftp_timeout').attr('data-parsley-min', '10');
                    break;
					
				case DupPro.Storage.Modes.GDRIVE:
                    $('#gdrive_max_files').attr('data-parsley-required', 'true');
                    $('#gdrive_max_files').attr('data-parsley-type', 'number');
                    $('#gdrive_max_files').attr('data-parsley-min', '0');                    
                    break;
					
				case DupPro.Storage.Modes.S3:									
                    $('#s3_max_files').attr('data-parsley-required', 'true');
                    $('#s3_access_key').attr('data-parsley-required', 'true');
					$('#s3_secret_key').attr('data-parsley-required', 'true');
					$('#s3_bucket').attr('data-parsley-required', 'true');
                    break;
					
            };
            $('#dup-storage-form').parsley();      
        
        };

        // GENERAL STORAGE LOGIC
        DupPro.Storage.ChangeMode = function (animateOverride) {
            var mode = $("#change-mode option:selected").val();
            var animate = 400;

            if (arguments.length == 1)
            {
                animate = animateOverride;
            }

            $('.provider').hide();
            $('#provider-' + mode).show(animate);
            DupPro.Storage.BindParsley(mode);
        }

        DupPro.Storage.ChangeMode(0);

        // DROPBOX RELATED METHODS
        DupPro.Storage.Dropbox.AuthorizationStates = {
            UNAUTHORIZED: 0,
            WAITING_FOR_REQUEST_TOKEN: 1,
            WAITING_FOR_AUTH_BUTTON_CLICK: 2,
            WAITING_FOR_ACCESS_TOKEN: 3,
            AUTHORIZED: 4
        }

        DupPro.Storage.Dropbox.authorizationState = <?php echo $storage->dropbox_authorization_state; ?>;

        DupPro.Storage.Dropbox.TransitionAuthorizationState = function (newState) {

            jQuery('.authorization-state').hide();
            jQuery('.dropbox_access_type').prop('disabled', true);
            jQuery('.button_dropbox_test').prop('disabled', true);

            switch (newState)
            {
                case DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED:

                    jQuery('.dropbox_access_type').prop('disabled', false);
                    $("#dropbox_authorization_state").val(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);

                    DupPro.Storage.Dropbox.requestToken = null;
                    DupPro.Storage.Dropbox.authUrl = null;
                    jQuery("#state-unauthorized").show();
                    break;

                case DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_REQUEST_TOKEN:
                    DupPro.Storage.Dropbox.GetRequestToken();
                    jQuery("#state-waiting-for-request-token").show();
                    break;

                case DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_AUTH_BUTTON_CLICK:
                    // Nothing to do here other than show the button and wait
                    jQuery("#state-waiting-for-auth-button-click").show();
                    break;

                case DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_ACCESS_TOKEN:

                    jQuery("#state-waiting-for-access-token").show();
                    if (DupPro.Storage.Dropbox.requestToken != null)
                    {
                        DupPro.Storage.Dropbox.GetAccessToken();
                    }
                    else
                    {
                        alert("<?php DUP_PRO_U::_e('Tried transitioning to auth button click but don\'t have the request token!'); ?>");
                        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);
                    }

                    break;

                case DupPro.Storage.Dropbox.AuthorizationStates.AUTHORIZED:

                    var token = $("#dropbox_access_token").val();
                    var token_secret = $("#dropbox_access_token_secret").val();

                    DupPro.Storage.Dropbox.accessToken = {};
                    DupPro.Storage.Dropbox.accessToken.t = token;
                    DupPro.Storage.Dropbox.accessToken.s = token_secret;

                    jQuery("#state-authorized").show();
                    jQuery('.button_dropbox_test').prop('disabled', false);
                    break;
            }

            DupPro.Storage.Dropbox.authorizationState = newState;
        }

        DupPro.Storage.Dropbox.GetRequestToken = function () {

            var fullAccess = $('#dropbox_accesstype_full').is(":checked");
            var data = {action: 'duplicator_pro_dropbox_get_request_token', full_access: fullAccess};

            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {

                    if (typeof (data.error) === 'undefined')
                    {
                        console.log(data);
                        DupPro.Storage.Dropbox.requestToken = data.request_token;
                        DupPro.Storage.Dropbox.authUrl = data.auth_url;
                        // Once it's back open a new
                        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.WAITING_FOR_AUTH_BUTTON_CLICK);
                    }
                    else
                    {
                        alert("Error. See local console log on browser for details.")
                        console.log(data);
                        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);
                    }
                },
                error: function (data) {
                    alert("error")
                    console.log(data);
                    DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);
                }
            });
        }

        DupPro.Storage.Dropbox.GetAccessToken = function () {

            var fullAccess = $('#dropbox_accesstype_full').is(":checked");
            var data = {action: 'duplicator_pro_dropbox_get_access_token', request_token: DupPro.Storage.Dropbox.requestToken, full_access: fullAccess};

            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {

                    if (typeof (data.error) === 'undefined')
                    {
                        console.log(data);
                        // Finally store it in the hidden fields so when it's saved it will be stored in the storage entity
                        $("#dropbox_access_token").val(data.access_token.t);
                        $("#dropbox_access_token_secret").val(data.access_token.s);
                        $("#dropbox_authorization_state").val(DupPro.Storage.Dropbox.AuthorizationStates.AUTHORIZED);

                        // Forcible submit the form so they don't accidentally toss out the authorization
                        $("#dup-storage-form").submit();
                        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.AUTHORIZED); // should never get here since the submit is in there
                    }
                    else
                    {
                        alert("<?php DUP_PRO_U::_e('Couldn\'t retrieve Dropbox access token!') ?>");
                        console.log(data);
                        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);
                    }
                },
                error: function (data) {
                    alert("<?php DUP_PRO_U::_e('Couldn\'t retrieve Dropbox access token!') ?>");
                    console.log(data);
                    DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.AuthorizationStates.UNAUTHORIZED);
                }
            });
        }

        DupPro.Storage.Dropbox.SendFileTest = function () {

            var fullAccess = $('#dropbox_accesstype_full').is(":checked");
            var current_storage_folder = $('#_dropbox_storage_folder').val();
            var data = {action: 'duplicator_pro_dropbox_send_file_test', access_token: DupPro.Storage.Dropbox.accessToken, storage_folder: current_storage_folder, full_access: fullAccess};
			var $test_button = $('#button_dropbox_send_file_test');
			
			$test_button.html('<i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e("Attempting Connection Please Wait..."); ?>');
			
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {
					
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test Dropbox Connection"); ?>');
                    if (typeof (data.success) !== 'undefined')
                    {
                        alert(data.success)
                    }
                    else
                    {
                        alert("<?php DUP_PRO_U::_e('Send Dropbox file test failed.') ?>");
                        console.log(data);
                    }
                },
                error: function (data) {
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test Dropbox Connection"); ?>');
                    alert("<?php DUP_PRO_U::_e('Send Dropbox file test failed.') ?>");
                    console.log(data);
                }
            });
        }

        DupPro.Storage.Dropbox.Authorize = function () {
            window.open(DupPro.Storage.Dropbox.authUrl, '_blank');
            $('button#auth-validate').prop('disabled', false);
        }

        DupPro.Storage.Dropbox.TransitionAuthorizationState(DupPro.Storage.Dropbox.authorizationState);
        $('button#auth-validate').prop('disabled', true);              

        // GOOGLE DRIVE RELATED METHODS
		DupPro.Storage.GDrive.OpenAuthPage = function() {
			window.open(DupPro.Storage.GDrive.AuthUrl,  '_blank');
		}
		
		DupPro.Storage.GDrive.FinalizeSetup = function() 
		{
			 if ($('#gdrive-auth-code').val().length > 5) {
				 $("#dup-storage-form").submit();
			 } else {
				  alert("<?php DUP_PRO_U::_e('Please enter your Google authorization code!') ?>");
			 }
		}
		
        DupPro.Storage.GDrive.GoogleGetAuthUrl = function() 
		{
			$('#dpro-gdrive-connect-btn-area').hide();
			$('#dpro-gdrive-connect-progress').show();
			
            var data = {action: 'duplicator_pro_gdrive_get_auth_url' };
			
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {
					// Success
					if(data['status'] == 0) 
					{						
						DupPro.Storage.GDrive.AuthUrl = data['gdrive_auth_url'];
						$('#dpro-gdrive-connect-btn-area').hide();
						$('#dpro-gdrive-steps').show();
					}
					else if(data['status'] == -2) 
					{
						alert("<?php DUP_PRO_U::_e('Google Drive not supported on systems running PHP version < 5.3.2.') ?>");
						$('#dpro-gdrive-connect-btn-area').show();
					}
					else 
					{
						alert("<?php DUP_PRO_U::_e('Error getting Google Drive authentication URL. Please try again later.') ?>");
						$('#dpro-gdrive-connect-btn-area').show();
					}
                },
                error: function (data) {
                    alert("<?php DUP_PRO_U::_e('Unable to get Google Drive authentication URL.') ?>");                   
                },
				complete: function (data) {
					 $('#dpro-gdrive-connect-progress').hide();
                }
            });			            
        }
        
        DupPro.Storage.GDrive.CancelAuthorization = function() {
            
            $("#dup-storage-form-action").val('gdrive-revoke-access');
            $("#dup-storage-form").submit();
        }
                
        DupPro.Storage.GDrive.SendFileTest = function() {
            var current_storage_folder = $('#_gdrive_storage_folder').val();
            var data = {action: 'duplicator_pro_gdrive_send_file_test', storage_folder: current_storage_folder, storage_id: <?php echo $storage->id; ?> };
			var $test_button = $('#button_gdrive_send_file_test');
			
			$test_button.html('<i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e("Attempting Connection Please Wait..."); ?>');
			
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test Google Drive Connection"); ?>');
                    if (typeof (data.success) !== 'undefined')
                    {
                        alert(data.success)
                    }
                    else
                    {
                        alert("<?php DUP_PRO_U::_e('Send Google Drive file test failed.') ?>");
                        console.log(data);
                    }
                },
                error: function (data) {
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test Google Drive Connection"); ?>');
                    alert("<?php DUP_PRO_U::_e('Send Google Drive file test failed.') ?>");
                    console.log(data);
                }
            });
        } 
        
        // FTP RELATED METHODS
        DupPro.Storage.FTP.SendFileTest = function () {

            var current_storage_folder = $('#_ftp_storage_folder').val();
            var server = $('#ftp_server').val();
            var port = $('#ftp_port').val();
            var username = $('#ftp_username').val();
            var password = $('#ftp_password').val();
            var ssl = $('#_ftp_ssl').prop('checked') ? 1 : 0;
            var passive_mode = $('#_ftp_passive_mode').prop('checked') ? 1 : 0;
            var $test_button = $('#button_ftp_send_file_test');

            var data = {action: 'duplicator_pro_ftp_send_file_test', storage_folder: current_storage_folder, server: server,
                port: port, username: username, password: password, ssl: ssl, passive_mode: passive_mode};

            $test_button.html('<i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e('Attempting Connection Please Wait...'); ?>');

            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {
                    if (typeof (data.success) !== 'undefined') {
                        alert(data.success)
                    } else {
                        alert("<?php DUP_PRO_U::_e('Send FTP file test failed. Be sure the full storage path exists.') ?>");
                        console.log(data);
                    }
                    $test_button.html('<i class="fa fa-cloud-upload"></i> <?php DUP_PRO_U::_e('Test FTP Connection'); ?>');
                },
                error: function (data) {
                    $test_button.html('<i class="fa fa-cloud-upload"></i> <?php DUP_PRO_U::_e('Test FTP Connection'); ?>');
                    alert("<?php DUP_PRO_U::_e('Send FTP file test failed. Be sure the full storage path exists.') ?>");
                    console.log(data);
                }
            });
        }
		
		// FTP RELATED METHODS
        DupPro.Storage.S3.SendFileTest = function () {
			var current_storage_folder = $('#_s3_storage_folder').val();
			var current_bucket = $('#s3_bucket').val();
			var current_region = $('#s3_region').val();
			var current_storage_class = $('#s3_storage_class').val();
			var current_access_key = $('#s3_access_key').val();
			var current_secret_key = $('#s3_secret_key').val();
			
            var data = {
							action: 'duplicator_pro_s3_send_file_test', 
							storage_folder: current_storage_folder, 
							bucket: current_bucket, 
							storage_class: current_storage_class, 
							region: current_region,
							access_key: current_access_key,
							secret_key: current_secret_key
						}
					
			var $test_button = $('#button_s3_send_file_test');
			
			$test_button.html('<i class="fa fa-circle-o-notch fa-spin"></i> <?php DUP_PRO_U::_e("Attempting Connection Please Wait..."); ?>');
			
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                data: data,
                success: function (data) {
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test S3 Connection"); ?>');
                    if (typeof (data.success) !== 'undefined')
                    {
                        alert(data.success)
                    }
                    else
                    {
                        alert("<?php DUP_PRO_U::_e('Test failed. Check configuration.') ?>");
                        console.log(data);
                    }
                },
                error: function (data) {
					$test_button.html('<i class="fa fa-cloud-upload"></i>	<?php DUP_PRO_U::_e("Test S3 Connection"); ?>');
                    alert("<?php DUP_PRO_U::_e('Test failed. Check configuration.') ?>");
                    console.log(data);
                }
            });
		}
			        
        // COMMON STORAGE RELATED METHODS
        DupPro.Storage.Copy = function () {

            $("#dup-storage-form-action").val('copy-storage');
            $("#dup-storage-form").parsley().destroy();
            $("#dup-storage-form").submit();
        };
		
		DupPro.Storage.LocalFilterToggle = function () 
		{
            $("#_local_filter_protection").is(":checked")
				? $("#_local_filter_protection_message").hide(400)
				: $("#_local_filter_protection_message").show(400);
            
        };
		
		//Init
		DupPro.Storage.LocalFilterToggle();
		
    });
</script>
