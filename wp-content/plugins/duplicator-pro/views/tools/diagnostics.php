<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.global.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/class.scan.check.php');

global $wp_version;
global $wpdb;

ob_start();
phpinfo();
$serverinfo = ob_get_contents();
ob_end_clean();

$serverinfo			= preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $serverinfo);
$serverinfo			= preg_replace('%^.*<title>(.*)</title>.*$%ms', '$1', $serverinfo);
$action_response	= null;
$dbvar_maxtime		= DUP_PRO_DB::getVariable('wait_timeout');
$dbvar_maxpacks		= DUP_PRO_DB::getVariable('max_allowed_packet');
$dbvar_maxtime		= is_null($dbvar_maxtime) ? DUP_PRO_U::__("unknow") : $dbvar_maxtime;
$dbvar_maxpacks		= is_null($dbvar_maxpacks) ? DUP_PRO_U::__("unknow") : $dbvar_maxpacks;

$txt_found			= DUP_PRO_U::__("Found");
$txt_not_found		= DUP_PRO_U::__("Removed");

$space				= @disk_total_space(DUPLICATOR_PRO_WPROOTPATH);
$space_free			= @disk_free_space(DUPLICATOR_PRO_WPROOTPATH);
$perc				= @round((100 / $space) * $space_free, 2);
$mysqldumpPath		= DUP_PRO_DB::getMySqlDumpPath();
$mysqlDumpSupport	= ($mysqldumpPath) ? $mysqldumpPath : 'Path Not Found';

$view_state			= DUP_PRO_UI_ViewState::getArray();
$ui_css_srv_panel	= (isset($view_state['dup-settings-diag-srv-panel']) && $view_state['dup-settings-diag-srv-panel']) ? 'display:block' : 'display:none';
$ui_css_opts_panel	= (isset($view_state['dup-settings-diag-opts-panel']) && $view_state['dup-settings-diag-opts-panel']) ? 'display:block' : 'display:none';
$client_ip_address	= DUP_PRO_Server::getClientIP();
$installer_files	= DUP_PRO_Server::getInstallerFiles();
$orphaned_filepaths	= DUP_PRO_Server::getOrphanedPackageFiles();
$scan_run			= (isset($_POST['action']) && $_POST['action'] == 'duplicator_recursion') ? true :false;

//POST BACK
$action_updated = null;
$_REQUEST['action'] = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'display';

if (isset($_REQUEST['action']))
{
    switch ($_REQUEST['action'])
    {
        case 'duplicator_pro_tools' : 
			$action_response = DUP_PRO_U::__('Plugin settings reset.');
            break;
        case 'duplicator_pro_ui_view_state' : 
			$action_response = DUP_PRO_U::__('View state settings reset.');
            break;
        case 'duplicator_pro_package_active' :
			$action_response = DUP_PRO_U::__('Active package settings reset.');
            break;
        case 'installer' :
			$action_response = DUP_PRO_U::__('Installer file cleanup ran!');
			$css_hide_msg = 'div#dpro-global-error-reserved-files {display:none}';
            break;
		case 'purge-orphans':
			$action_response = DUP_PRO_U::__('Cleaned up orphaned package files!');			
			break;		
        case 'tmp-cache':
            DUP_PRO_Package::tmp_cleanup(true);
            $action_response = DUP_PRO_U::__('Build cache removed.');
            break;
    }
}
?>

<style>
    <?php echo isset($css_hide_msg) ? $css_hide_msg : ''; ?>
	div#message {margin:0px 0px 10px 0px}
    td.dpro-settings-diag-header {background-color:#D8D8D8; font-weight: bold; border-style: none; color:black}
    table.widefat th {font-weight:bold; }
    table.widefat td {padding:2px 2px 2px 8px; }
    table.widefat td:nth-child(1) {width:10px;}
    table.widefat td:nth-child(2) {padding-left: 20px; width:100% !important}
    textarea.dup-opts-read {width:100%; height:40px; font-size:12px}
	a.dpro-store-fixed-btn {min-width: 155px; text-align: center}
    div.success {color:#4A8254}
    div.failed {color:red}
    table.dpro-reset-opts td:first-child {font-weight: bold}
    table.dpro-reset-opts td {padding:4px}    
	div#dpro-tools-delete-moreinfo {display: none; padding: 5px 0 0 20px; border:1px solid #dfdfdf;  border-radius: 5px; padding:10px; margin:5px; width:98% }
	div#dpro-tools-delete-orphans-moreinfo {display: none; padding: 5px 0 0 20px; border:1px solid #dfdfdf;  border-radius: 5px; padding:10px; margin:5px; width:98% }
	
	/*PHP_INFO*/
	div#dpro-phpinfo {padding:10px 5px;}
    div#dpro-phpinfo table {padding:1px; background:#dfdfdf; -webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px; width:100% !important; box-shadow:0 8px 6px -6px #777;}
    div#dpro-phpinfo td, th {padding:3px; background:#fff; -webkit-border-radius:2px;-moz-border-radius:2px;border-radius:2px;}
    div#dpro-phpinfo tr.h img {display:none;}
    div#dpro-phpinfo tr.h td {background:none;}
    div#dpro-phpinfo tr.h th {text-align:center; background-color:#efefef;}
    div#dpro-phpinfo td.e {font-weight:bold}
</style>

<form id="dup-settings-form" action="<?php echo self_admin_url('admin.php?page=duplicator-pro-tools&tab=diagnostics'); ?>" method="post">
    <?php wp_nonce_field('duplicator_pro_settings_page'); ?>
    <input type="hidden" id="dup-settings-form-action" name="action" value="">
    <br/>

    <?php if (!empty($action_response)) : ?>
        <div id="message" class="updated below-h2"><p><?php echo $action_response; ?></p>
            <?php if ($_REQUEST['action'] != 'display') : ?>
				<?php if ($_REQUEST['action'] == 'installer') : ?>
					<?php
					$html = "";

					foreach($installer_files as $filename => $path) 
					{
						if(is_file($path))
						{
							DUP_PRO_IO::deleteFile($path);	
						}
						else if(is_dir($path))
						{
							DUP_PRO_IO::deleteTree($path);
						}
						
						echo (file_exists($path)) 
							? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$path}  </div>"
							: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$path}	</div>";	
					}
		
					$package_name		 = (isset($_GET['package'])) ? DUPLICATOR_PRO_WPROOTPATH . esc_html($_GET['package']) : '';
                    $long_installer_path = (isset($_GET['installer_name'])) ? DUPLICATOR_PRO_WPROOTPATH . esc_html($_GET['installer_name']) : '';

					//No way to know exact name of archive file except from installer.
					//The only place where the package can be remove is from installer
					//So just show a message if removing from plugin.
					if (!empty($package_name))
					{
						$path_parts = pathinfo($package_name);
						$path_parts = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
						if ($path_parts == "zip" && !is_dir($package_name))
						{
							@unlink($package_name);		
							$html .= (file_exists($package_name)) 
								? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$package_name}  </div>"
								: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$package_name}	</div>";
						}
						else
						{
							$html .= "<div class='failed'>Does not exist or unable to remove archive file.  Please validate that an archive file exists.</div>";
						}
					}
					else
					{
						$html .= '<div><br/>It is recommended to remove your archive file from the root of your WordPress install.  This may need to be removed manually if it exists.</div>';
					}
                    
					//Long Installer Check
                    if (!empty($long_installer_path)  && $long_installer_path != $installer_files['installer.php'])
					{
						$path_parts = pathinfo($long_installer_path);
						$path_parts = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
						if ($path_parts == "php" && ! is_dir($long_installer_path))
						{
							@unlink($long_installer_path);		
							$html .= (file_exists($long_installer_path)) 
								? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$long_installer_path}  </div>"
								: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$long_installer_path}	</div>";
						}
					}

					echo $html;
					?>
					<br/>
					
					<i> 
						<?php DUP_PRO_U::_e('If the installation files did not successfully get removed, then you WILL need to remove them manually') ?>. <br/>
						<?php DUP_PRO_U::_e('Please remove all installation files to avoid leaving open security issues on your server') ?>. <br/><br/>
					</i>
				<?php elseif ($_REQUEST['action'] == 'purge-orphans') :?>
					<?php
					$html = "";

					foreach($orphaned_filepaths as $filepath) 
					{
						@unlink($filepath);		
						echo (file_exists($filepath)) 
							? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$filepath}  </div>"
							: "<div class='success'> <i class='fa fa-check'></i> {$filepath} </div>";	
					}		

					echo $html;
					
					$orphaned_filepaths		= DUP_PRO_Server::getOrphanedPackageFiles();
					?>
					<br/>
					
					<i> 
						<?php DUP_PRO_U::_e('If any orphaned files didn\'t get removed then delete them manually') ?>. <br/><br/>
					</i>
				<?php endif; ?>		
            <?php endif; ?>
        </div>
    <?php endif; ?>	

    <!-- ==============================
    STORED DATA -->
    <div class="dup-box">
        <div class="dup-box-title">
            <i class="fa fa-th-list"></i>
            <?php DUP_PRO_U::_e("Stored Data"); ?>
            <div class="dup-box-arrow"></div>
        </div>
        <div class="dup-box-panel" id="dup-settings-diag-opts-panel" style="<?php echo $ui_css_opts_panel ?>" >
			 <div style="padding:0px 20px 0px 25px">
				 
				<h3 class="title" style="margin-left:-15px"><?php DUP_PRO_U::_e("Data Cleanup") ?> </h3>
                <table class="dpro-reset-opts">
                    <tr valign="top">
                        <td>
							<a class="dpro-store-fixed-btn button button-small" href="?page=duplicator-pro-tools&tab=diagnostics&action=installer">
								<?php DUP_PRO_U::_e("Delete Installation Files"); ?>
							</a>
						</td>
                        <td>
							<?php DUP_PRO_U::_e("Removes all reserved installation files."); ?>
							<a href="javascript:void(0)" onclick="jQuery('#dpro-tools-delete-moreinfo').toggle()">[<?php DUP_PRO_U::_e("more info"); ?>]</a>
							<br/>
							<div id="dpro-tools-delete-moreinfo">
								<?php
									DUP_PRO_U::_e("Clicking on the 'Delete Installation Files' button will remove the following installation files.  These files are typically from a previous Duplicator install. "
											. "If you are unsure of the source, please validate the files.  These files should never be left on production systems for security reasons.  "
											. "Below is a list of all the installation files used by Duplicator.  Please be sure these are removed from your server.");
									echo "<br/><br/>";
									
									foreach($installer_files as $file => $path) 
									{
										echo (file_exists($path)) 
											? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$file}  </div>"
											: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$file}	</div>";		
									}
								?>
							</div>
						</td>
                    </tr>
					<tr valign="top">
                        <td>
							<a class="dpro-store-fixed-btn button button-small" href="?page=duplicator-pro-tools&tab=diagnostics&action=purge-orphans">
								<?php DUP_PRO_U::_e("Delete Package Orphans"); ?>
							</a>
						</td>
                        <td>
							<?php DUP_PRO_U::_e("Removes all package files NOT found in the packages screen."); ?>
							<a href="javascript:void(0)" onclick="jQuery('#dpro-tools-delete-orphans-moreinfo').toggle()">[<?php DUP_PRO_U::_e("more info"); ?>]</a>
							<br/>
							<div id="dpro-tools-delete-orphans-moreinfo">
								<?php
									if(count($orphaned_filepaths) > 0)
									{
										DUP_PRO_U::_e("Clicking on the 'Delete Package Orphans' button will remove the following files.  "
												. "Orphaned files are typically generated from previous installations of Duplicator. They may also exist if they did not get properly removed "
												. "when they were selected from the main packages screen.  The files below are no longer associated with active packages in the main "
												. "Packages screen and should be safe to remove. <b>IMPORTANT: Don't click button if you want to retain any of the following files:</b>");
										echo "<br/><br/>";

										foreach($orphaned_filepaths as $filepath) 
										{
											echo "<div class='failed'><i class='fa fa-exclamation-triangle'></i> $filepath </div>";
										}
									}
									else
									{
										DUP_PRO_U::_e('No orphaned package files found.');
									}
								?>
							</div>
						</td>
                    </tr>
                    <tr>
                        <td>
							<a class="dpro-store-fixed-btn button button-small" href="javascript:void(0)" onclick="DupPro.Tools.ClearBuildCache()">
								<?php DUP_PRO_U::_e("Clear Build Cache"); ?>
							</a>
						</td>
                        <td><?php DUP_PRO_U::_e('Removes all build data from:'); ?> [<?php echo DUPLICATOR_PRO_SSDIR_PATH_TMP ?>].</td>
                    </tr>				
                </table>
				<br/>
           
                <h3 class="title" style="margin-left:-15px"><?php DUP_PRO_U::_e("Options Values") ?> </h3>
                <table class="widefat">	
					<thead>
						<tr>
							<th><?php DUP_PRO_U::_e("Key") ?> <i>duplicator_pro_</i></th>
							<th>&nbsp; <?php DUP_PRO_U::_e("Value") ?></th>
						</tr>	
					</thead>
					<tbody>
						<?php
						$sql = "SELECT * FROM `{$wpdb->prefix}options` WHERE  `option_name` LIKE  '%duplicator_pro_%' ORDER BY option_name";
						/* @var $global DUP_PRO_Global_Entity */
						$global = DUP_PRO_Global_Entity::get_instance();
						
						foreach ($wpdb->get_results("{$sql}") as $key => $row) :  							
										if(($global->license_key_visible) || ($row->option_name != 'duplicator_pro_license_key'))
										{
						?>
							<tr>
								<td>
									<?php
										$key_name = str_replace('duplicator_pro_', '', $row->option_name);									
											
									echo (in_array($row->option_name, $GLOBALS['DUPLICATOR_PRO_OPTS_DELETE'])) 
											? "<a href='javascript:void(0)' onclick='DupPro.Settings.DeleteOption(this)'>{$key_name}</a>" 
											: $key_name;
									?>
								</td>
								<td><textarea class="dup-opts-read" readonly="readonly"><?php echo $row->option_value ?></textarea></td>
							</tr>
										<?php }
						endforeach; ?>							
					</tbody>
                </table>
				<br/>
            </div>
        </div> <!-- end .dup-box-panel -->	
    </div> <!-- end .dup-box -->	
    <br/>

    <!-- ==============================
    SERVER SETTINGS -->	
    <div class="dup-box">
        <div class="dup-box-title">
            <i class="fa fa-tachometer"></i>
            <?php DUP_PRO_U::_e("Server Settings") ?>
            <div class="dup-box-arrow"></div>
        </div>
        <div class="dup-box-panel" id="dup-settings-diag-srv-panel" style="<?php echo $ui_css_srv_panel ?>">
            <table class="widefat" cellspacing="0">		   
                <tr>
                    <td class='dpro-settings-diag-header' colspan="2"><?php DUP_PRO_U::_e("General"); ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Duplicator Version"); ?></td>
                    <td><?php echo DUPLICATOR_PRO_VERSION ?></td>
                </tr>	
                <tr>
                    <td><?php DUP_PRO_U::_e("Operating System"); ?></td>
                    <td><?php echo PHP_OS ?></td>
                </tr>	
                <tr>
                    <td><?php _e("Timezone"); ?></td>
                    <td><?php echo date_default_timezone_get(); ?> &nbsp; <small><i>This is a <a href='options-general.php'>WordPress setting</a></i></small></td>
                </tr>		
                <tr>
                    <td><?php _e("Server Time"); ?></td>
                    <td><?php echo date("Y-m-d H:i:s"); ?></td>
                </tr>			
                <tr>
                    <td><?php DUP_PRO_U::_e("Web Server"); ?></td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
                </tr>					   
                <tr>
                    <td><?php DUP_PRO_U::_e("Root Path"); ?></td>
                    <td><?php echo DUPLICATOR_PRO_WPROOTPATH ?></td>
                </tr>	
                <tr>
                    <td><?php DUP_PRO_U::_e("ABSPATH"); ?></td>
                    <td><?php echo ABSPATH ?></td>
                </tr>			
                <tr>
                    <td><?php DUP_PRO_U::_e("Plugins Path"); ?></td>
                    <td><?php echo DUP_PRO_U::safePath(WP_PLUGIN_DIR) ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Loaded PHP INI"); ?></td>
                    <td><?php echo php_ini_loaded_file(); ?></td>
                </tr>	
                <tr>
                    <td><?php DUP_PRO_U::_e("Server IP"); ?></td>
                    <td><?php echo $_SERVER['SERVER_ADDR']; ?></td>
                </tr>	
                <tr>
                    <td><?php DUP_PRO_U::_e("Client IP"); ?></td>
                    <td><?php echo $client_ip_address; ?></td>
                </tr>
                <tr>
                    <td class='dpro-settings-diag-header' colspan="2">WordPress</td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Version"); ?></td>
                    <td><?php echo $wp_version ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Langugage"); ?></td>
                    <td><?php echo get_bloginfo('language') ?></td>
                </tr>	
                <tr>
                    <td><?php DUP_PRO_U::_e("Charset"); ?></td>
                    <td><?php echo get_bloginfo('charset') ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Memory Limit "); ?></td>
                    <td><?php echo WP_MEMORY_LIMIT ?> (<?php
                        DUP_PRO_U::_e("Max");
                        echo '&nbsp;' . WP_MAX_MEMORY_LIMIT;
                        ?>)</td>
                </tr>
                <tr>
                    <td class='dpro-settings-diag-header' colspan="2">PHP</td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Version"); ?></td>
                    <td><?php echo phpversion() ?></td>
                </tr>	
                <tr>
                    <td>SAPI</td>
                    <td><?php echo PHP_SAPI ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("User"); ?></td>
                    <td><?php echo DUP_PRO_Server::getCurrentUser(); ?></td>
                </tr>
                <tr>
                    <td><a href="http://php.net/manual/en/features.safe-mode.php" target="_blank"><?php DUP_PRO_U::_e("Safe Mode"); ?></a></td>
                    <td>
                        <?php
                        echo (((strtolower(@ini_get('safe_mode')) == 'on') || (strtolower(@ini_get('safe_mode')) == 'yes') ||
                        (strtolower(@ini_get('safe_mode')) == 'true') || (ini_get("safe_mode") == 1 ))) ? DUP_PRO_U::__('On') : DUP_PRO_U::__('Off');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><a href="http://www.php.net/manual/en/ini.core.php#ini.memory-limit" target="_blank"><?php DUP_PRO_U::_e("Memory Limit"); ?></a></td>
                    <td><?php echo @ini_get('memory_limit') ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Memory In Use"); ?></td>
                    <td><?php echo size_format(@memory_get_usage(TRUE), 2) ?></td>
                </tr>
                <tr>
                    <td><a href="http://www.php.net/manual/en/info.configuration.php#ini.max-execution-time" target="_blank"><?php DUP_PRO_U::_e("Max Execution Time"); ?></a></td>
                    <td><?php echo @ini_get('max_execution_time'); ?></td>
                </tr>
                <tr>
                    <td><a href="http://php.net/manual/en/ini.core.php#ini.open-basedir" target="_blank"><?php DUP_PRO_U::_e("open_basedir"); ?></a></td>
                    <td>
                        <?php
						$open_base_set = @ini_get('open_basedir');
                        echo empty($open_base_set) ? DUP_PRO_U::__('Off') : $open_base_set;
                        ?>
                    </td>
                </tr>				
                <tr>
                    <td><a href="http://us3.php.net/shell_exec" target="_blank"><?php DUP_PRO_U::_e("Shell Exec"); ?></a></td>
                    <td><?php echo (DUP_PRO_Shell_U::isShellExecEnabled()) ? DUP_PRO_U::_e("Is Supported") : DUP_PRO_U::_e("Not Supported"); ?></td>
                </tr>
				<tr>
					<td><?php DUP_PRO_U::_e("Shell Exec Zip"); ?></td>
					<td><?php echo (DUP_PRO_Zip_U::getShellExecZipPath() != null) ? DUP_PRO_U::_e("Is Supported") : DUP_PRO_U::_e("Not Supported"); ?></td>
				</tr>
                <tr>
                    <td><a href="https://suhosin.org/stories/index.html" target="_blank"><?php DUP_PRO_U::_e("Suhosin Extension"); ?></a></td>
                    <td><?php echo extension_loaded('suhosin') ? DUP_PRO_U::_e("Enabled") : DUP_PRO_U::_e("Disabled"); ?></td>
                </tr>
                <tr>
                    <td class='dpro-settings-diag-header' colspan="2">MySQL</td>
                </tr>					   
                <tr>
                    <td><?php DUP_PRO_U::_e("Version"); ?></td>
                    <td><?php echo DUP_PRO_DB::getVersion() ?></td>
                </tr>
                <tr>
                    <td><?php DUP_PRO_U::_e("Charset"); ?></td>
                    <td><?php echo DB_CHARSET ?></td>
                </tr>
                <tr>
                    <td><a href="http://dev.mysql.com/doc/refman/5.0/en/server-system-variables.html#sysvar_wait_timeout" target="_blank"><?php DUP_PRO_U::_e("Wait Timeout"); ?></a></td>
                    <td><?php echo $dbvar_maxtime ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap"><a href="http://dev.mysql.com/doc/refman/5.0/en/server-system-variables.html#sysvar_max_allowed_packet" target="_blank"><?php DUP_PRO_U::_e("Max Allowed Packets"); ?></a></td>
                    <td><?php echo $dbvar_maxpacks ?></td>
                </tr>
                <tr>
                    <td><a href="http://dev.mysql.com/doc/refman/5.0/en/mysqldump.html" target="_blank"><?php DUP_PRO_U::_e("msyqldump Path"); ?></a></td>
                    <td><?php echo $mysqlDumpSupport ?></td>
                </tr>
                <tr>
                    <td class='dpro-settings-diag-header' colspan="2"><?php DUP_PRO_U::_e("Server Disk"); ?></td>
                </tr>
                <tr valign="top">
                    <td><?php DUP_PRO_U::_e('Free space', 'hyper-cache'); ?></td>
                    <td><?php echo $perc; ?>% -- <?php echo DUP_PRO_U::byteSize($space_free); ?> from <?php echo DUP_PRO_U::byteSize($space); ?><br/>
                        <small>
                            <?php DUP_PRO_U::_e("Note: This value is the physical servers hard-drive allocation."); ?> <br/>
                            <?php DUP_PRO_U::_e("On shared hosts check your control panel for the 'TRUE' disk space quota value."); ?>
                        </small>
                    </td>
                </tr>	

            </table><br/>

        </div> <!-- end .dup-box-panel -->	
    </div> <!-- end .dup-box -->	
    <br/>

	<!-- ==============================
	SCAN VALIDATOR -->
	<div class="dup-box">
		<div class="dup-box-title">
			<i class="fa fa-check-square-o"></i>
			<?php DUP_PRO_U::_e("Scan Validator"); ?>
			<div class="dup-box-arrow"></div>
		</div>
		<div class="dup-box-panel" style="display: <?php echo $scan_run ? 'block' : 'none';  ?>">	
			<?php 
				DUP_PRO_U::_e("This utility will help to find unreadable files and sys-links in your environment  that can lead to issues during the scan process.  "); 
				DUP_PRO_U::_e("The utility  will also show how many files and directories you have in your system.  This process may take several minutes to run.  "); 
				DUP_PRO_U::_e("If there is a recursive loop on your system then the process has a built in check to stop after a large set of files and directories have been scanned.  "); 
				DUP_PRO_U::_e("A message will show indicated that that a scan depth has been reached. "); 
			?> 
			<br/><br/>
				
			<?php if ($scan_run) : ?>
				<div id="duplicator-scan-results-1">
					<i class="fa fa-circle-o-notch fa-spin fa-lg fa-fw"></i>
					<b style="font-size: 14px"><?php DUP_PRO_U::_e('Scan integrity validation detection is running please wait...'); ?></b>
					<br/><br/>
				</div>
			<?php else :?>
				<button id="scan-run-btn" class="button button-large button-primary" onclick="DupPro.Tools.Recursion()">
					<?php DUP_PRO_U::_e("Run Scan Integrity Validation"); ?>
				</button>
			<?php endif; ?>
				
		</div> 
	</div> 
	<br/>

    <!-- ==============================
    PHP INFORMATION -->
    <div class="dup-box">
        <div class="dup-box-title">
            <i class="fa fa-info-circle"></i>
            <?php DUP_PRO_U::_e("PHP Information"); ?>
            <div class="dup-box-arrow"></div>
        </div>
        <div class="dup-box-panel" style="display:none">	
            <div id="dup-phpinfo" style="width:95%">
                <?php echo "<div id='dpro-phpinfo'>{$serverinfo}</div>"; ?>
            </div><br/>	
        </div> 
    </div> 
    <br/>
	
	<!-- ==============================
    SCAN RESULTS -->
	<div id="duplicator-scan-results-2" style="display:none">
		<?php
			if ($scan_run) 
			{
				$ScanChecker = new DUP_PRO_ScanValidator();
				$Files = $ScanChecker->getDirContents(DUPLICATOR_PRO_WPROOTPATH);
				$MaxFiles = number_format($ScanChecker->MaxFiles);
				$MaxDirs= number_format($ScanChecker->MaxDirs);
				
				if ($ScanChecker->LimitReached) {
					echo "<i style='color:red'>Recursion limit reached of {$MaxFiles} files &amp; {$MaxDirs} directories.</i> <br/>";
				}
				
				echo "Dirs Scanned: " . number_format($ScanChecker->DirCount) . " <br/>";
				echo "Files Scanned: " . number_format($ScanChecker->FileCount) . " <br/>";
				echo "Found Items: <br/>";
				
				if (count($Files)) 
				{
					$count = 0;
					foreach($Files as $file) 
					{
						$count++;
						echo "&nbsp; &nbsp; &nbsp; {$count}. {$file} <br/>";
					}
				} else {
					echo "&nbsp; &nbsp; &nbsp; No items found in scan <br/>";
				}
				
				echo "<br/><a href='admin.php?page=duplicator-pro-tools&tab=diagnostics'>" . DUP_PRO_U::__("Try Scan Again")  . "</a>";
				
			} 
		?>
	</div>

</form>

<script>
    jQuery(document).ready(function ($) {

        DupPro.Settings.DeleteOption = function (anchor) {
            var key = $(anchor).text();
            var result = confirm('<?php DUP_PRO_U::_e("Delete this option value", "wpduplicator"); ?> [' + key + '] ?');
            if (!result)
                return;

            jQuery('#dup-settings-form-action').val(key);
            jQuery('#dup-settings-form').submit();
        };


        DupPro.Tools.ClearBuildCache = function () {
			<?php
			$msg = DUP_PRO_U::__('This process will remove all build cache files.  Be sure no packages are currently building or else they will be cancelled.');
			?>
            var result = true;
            var result = confirm('<?php echo $msg ?>');
            if (!result)
                return;
            window.location = '?page=duplicator-pro-tools&tab=diagnostics&action=tmp-cache';
        };
		
		
		
		DupPro.Tools.Recursion = function() 
		{
			var result = confirm('<?php DUP_PRO_U::_e('This will run the scan validation check.  This may take several minutes.\nDo you want to Continue?'); ?>');
			if (! result) 	return;

			jQuery('#dup-settings-form-action').val('duplicator_recursion');
			jQuery('#scan-run-btn').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Running Please Wait...');
			jQuery('#dup-settings-form').submit();
		}

		<?php 
			if ($scan_run) {
				echo "$('#duplicator-scan-results-1').html($('#duplicator-scan-results-2').html())";
			}
		?>
		
    });
</script>