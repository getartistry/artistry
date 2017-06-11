
<style>
	 /*INSTALLER: Area*/
    .dup-installer-header-1 {font-weight:bold; padding-bottom:2px; width:100%}
    div.dup-installer-header-2 {font-weight:bold; border-bottom:1px solid #dfdfdf; padding-bottom:2px; width:100%}
	tr.dup-installer-header-2 td:first-child {font-weight:bold;}
	tr.dup-installer-header-2 td {border-bottom:1px solid #dfdfdf; padding-bottom:2px;}
    label.chk-labels {display:inline-block; margin-top:1px}
    table.dup-installer-tbl {width:97%;}
	table.dup-installer-tbl > td:first-child {width:110px}
	table.dup-installer-tbl > td {width:97%}
	div.secure-pass-area {display:none}
	input#secure-pass, input#secure-pass2{width:300px; margin: 3px 0 5px 0}
	label.secure-pass-lbl {display:inline-block; width:125px}
	div#dup-pack-installer-panel div.tabs-panel{min-height:150px}
    div.dpro-panel-optional-txt {color:maroon}
</style>

<!-- ===================
INSTALLER -->
<div class="dup-box">
	<div class="dup-box-title">
		<i class="fa fa-bolt"></i> <?php DUP_PRO_U::_e('Installer') ?>
		<div class="dup-box-arrow"></div>
	</div>		
	<div class="dup-box-panel" id="dup-pack-installer-panel" style="<?php echo $ui_css_installer ?>">
		<div class="dpro-panel-optional-txt">
			<b><?php DUP_PRO_U::_e('All values in this section are'); ?> <u><?php DUP_PRO_U::_e('optional'); ?></u>.</b> <br/>
			<?php DUP_PRO_U::_e("These fields can be pre-filled at install time but are not required here."); ?>
            <i class="fa fa-question-circle"
                data-tooltip-title="<?php DUP_PRO_U::_e("MySQL Server Prefills"); ?>"
                data-tooltip="<?php DUP_PRO_U::_e('The values in this section are NOT required! If you know ahead of time the database input fields the installer will use, '
                    . 'then you can optionally enter them here.  Otherwise you can just enter them in at install time.'); ?>"></i>
		</div>	
		
		<table class="dup-installer-tbl">
			<tr>
				<td>
					<input type="checkbox" name="secure-on" id="secure-on" onclick="DupPro.Pack.ToggleInstallerPassword()" />
					<label for="secure-on"><?php DUP_PRO_U::_e("Enable Password Protection") ?></label>
					<i class="fa fa-question-circle" 
					   data-tooltip-title="<?php DUP_PRO_U::_e("Password Protection:"); ?>" 
					   data-tooltip="<?php DUP_PRO_U::_e('Enabling this option will allow for basic password protection on the installer. Before running the installer the '
							   . 'password below must be entered before proceeding with an install.  This password is a general deterrent and should not be substituted for properly '
							   . 'keeping your files secure.'); ?>"></i>
					<br/>
					<div class="secure-pass-area">
						<label class="secure-pass-lbl" for="secure-pass"><?php DUP_PRO_U::_e("Password") ?>:</label> 
						<input type="password" name="secure-pass" id="secure-pass" maxlength="50" /> <br/>
						<label class="secure-pass-lbl" for="secure-pass"><?php DUP_PRO_U::_e("Confirm") ?>:</label> 
						<input type="password" name="secure-pass2" id="secure-pass2" maxlength="50" />
					</div>
				</td>
			</tr>			
			<!--tr>
				<td>
					<input type="checkbox" name="skipscan" id="skipscan" />
					<label for="skipscan"><?php DUP_PRO_U::_e("Skip System Scan Screen") ?></label>
					<i class="fa fa-question-circle" 
					   data-tooltip-title="<?php DUP_PRO_U::_e("Skip System Scan:"); ?>" 
					   data-tooltip="<?php DUP_PRO_U::_e('By default every time the installer is opened it will run a simple scan on the server environment.  If the scan check '
							   . 'passes then enabling this option automatically take you to step one of the installer and will skip the system scan screen.'); ?>"></i>
				</td>
			</tr-->
		</table><br/>
		
		<!--div class="dup-installer-header-1"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('STEP 1 - INPUTS'); ?></div-->
		
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
				<table class="dup-installer-tbl" id="s1-installer-dbbasic">
					<tr class="dup-installer-header-2">
						<td><?php DUP_PRO_U::_e("MySQL Server") ?></td>
						<td colspan="2" style="text-align: right">
							<a href="javascript:void(0)" onclick="DupPro.Pack.ApplyDataCurrent('s1-installer-dbbasic')">[use current]</a>
						</td>
					</tr>
					<tr>
						<td style="width:130px"><?php DUP_PRO_U::_e("Host") ?></td>
						<td><input type="text" name="dbhost" id="dbhost" maxlength="200" placeholder="<?php DUP_PRO_U::_e("example: localhost (value is optional)") ?>" data-current="<?php echo DB_HOST ?>"/></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Database") ?></td>
						<td><input type="text" name="dbname" id="dbname" maxlength="100" placeholder="<?php DUP_PRO_U::_e("example: DatabaseName (value is optional)") ?>" data-current="<?php echo DB_NAME ?>" /></td>
					</tr>							
					<tr>
						<td><?php DUP_PRO_U::_e("User") ?></td>
						<td><input type="text" name="dbuser" id="dbuser" maxlength="100" placeholder="<?php DUP_PRO_U::_e("example: DatabaseUser (value is optional)") ?>" data-current="<?php echo DB_USER ?>"/></td>
					</tr>
				</table>					
			</div>
			
			<!-- ===================
			TAB2: cPanel -->
			<div>
				<table class="dup-installer-tbl">
					<tr>
						<td colspan="2"><div class="dup-installer-header-2"><?php DUP_PRO_U::_e("cPanel Login") ?></div></td>
					</tr>
					<tr>
						<td style="width:130px"><?php DUP_PRO_U::_e("Automation") ?></td>
						<td>
							<input type="checkbox" name="cpnl-enable" id="cpnl-enable" />
							<label for="cpnl-enable"><?php DUP_PRO_U::_e("Auto Select cPanel") ?></label> 
							<i class="fa fa-question-circle" 
								data-tooltip-title="<?php DUP_PRO_U::_e("Auto Select cPanel:"); ?>" 
								data-tooltip="<?php DUP_PRO_U::_e('Enabling this options will automatically select the cPanel tab when step one of the installer is shown.'); ?>">
							</i>
						</td>
					</tr>						
					<tr>
						<td><?php DUP_PRO_U::_e("Host") ?></td>
						<td><input type="text" name="cpnl-host" id="cpnl-host"  maxlength="200" placeholder="<?php DUP_PRO_U::_e("example: cpanelHost (value is optional)") ?>"/></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("User") ?></td>
						<td><input type="text" name="cpnl-user" id="cpnl-user" maxlength="200" placeholder="<?php DUP_PRO_U::_e("example: cpanelUser (value is optional)") ?>"/></td>
					</tr>					
				</table><br/>
				
				<table class="dup-installer-tbl" id="s1-installer-dbcpanel">
					<tr class="dup-installer-header-2">
						<td><?php DUP_PRO_U::_e("MySQL Server") ?></td>
						<td colspan="2" style="text-align: right">
							<a href="javascript:void(0)" onclick="DupPro.Pack.ApplyDataCurrent('s1-installer-dbcpanel')">[use current]</a>
						</td>
					</tr>
					<tr>
						<td style="width:130px"><?php DUP_PRO_U::_e("Action") ?></td>
						<td>							
							<select name="cpnl-dbaction" id="cpnl-dbaction">
								<option value="create">Create A New Database</option>
								<option value="empty">Connect and Delete Any Existing Data</option>
								<option value="rename">Connect and Backup Any Existing Data</option>
								<option value="manual">Manual SQL Execution (Advanced)</option>
							</select>
						</td>
					</tr>					
					<tr>
						<td style="width:130px"><?php DUP_PRO_U::_e("Host") ?></td>
						<td><input type="text" name="cpnl-dbhost" id="cpnl-dbhost" maxlength="200" placeholder="<?php DUP_PRO_U::_e("example: localhost (value is optional)") ?>" data-current="<?php echo DB_HOST ?>"/></td>
					</tr>
					<tr>
						<td><?php DUP_PRO_U::_e("Database") ?></td>
						<td><input type="text" name="cpnl-dbname" id="cpnl-dbname" data-parsley-pattern="/^[a-zA-Z0-9-_]+$/" maxlength="100" placeholder="<?php DUP_PRO_U::_e("example: DatabaseName (value is optional)") ?>" data-current="<?php echo DB_NAME ?>"/></td>
					</tr>							
					<tr>
						<td><?php DUP_PRO_U::_e("User") ?></td>
						<td><input type="text" name="cpnl-dbuser" id="cpnl-dbuser" data-parsley-pattern="/^[a-zA-Z0-9-_]+$/" maxlength="100" placeholder="<?php DUP_PRO_U::_e("example: DatabaseUserName (value is optional)") ?>" data-current="<?php echo DB_USER ?>" /></td>
					</tr>
				</table>
			
			</div>
		</div><br/>

        <!-- TODO: Remove after 3.3.10 -->
		<!--table class="dup-installer-tbl">
			<tr>
				<td colspan="2"><div class="dup-installer-header-2"><?php DUP_PRO_U::_e("Advanced Options") ?></div></td>
			</tr>						
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td style="width:130px"><?php DUP_PRO_U::_e("SSL") ?></td>
							<td style="padding-right: 20px; white-space: nowrap">
								<input type="checkbox" name="ssl-admin" id="ssl-admin" />
								<label class="chk-labels" for="ssl-admin"><?php DUP_PRO_U::_e("Enforce on Admin") ?></label>
							</td>
							<td>
								<input type="checkbox" name="ssl-login" id="ssl-login" />
								<label class="chk-labels" for="ssl-login"><?php DUP_PRO_U::_e("Enforce on Logins") ?></label>
							</td>
						</tr>
						<tr>
							<td><?php DUP_PRO_U::_e("Cache") ?></td>									
							<td style="padding-right: 20px; white-space: nowrap">
								<input type="checkbox" name="cache-wp" id="cache-wp" />
								<label class="chk-labels" for="cache-wp"><?php DUP_PRO_U::_e("Keep Enabled") ?></label>	
							</td>
							<td>
								<input type="checkbox" name="cache-path" id="cache-path" />
								<label class="chk-labels" for="cache-path"><?php DUP_PRO_U::_e("Keep Home Path") ?></label>			
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><br /><br/-->

        <input type="hidden" name="url-new" id="url-new" value=""/>
        <small><?php DUP_PRO_U::_e("Additional inputs can be entered at install time.") ?></small>
		<!--div class="dup-installer-header-1"><i class="fa fa-caret-square-o-right"></i> <?php DUP_PRO_U::_e('STEP 2 - INPUTS'); ?></div>
		<table class="dup-installer-tbl">
			<tr>
				<td style="width:130px"><?php DUP_PRO_U::_e("New URL") ?></td>
				<td><?php DUP_PRO_U::_e("The New URL") ?>
                   
                </td>
			</tr>
		</table--><br/><br/>
	</div>		
</div><br/>

<script>
jQuery(function($) 
{
	DupPro.Pack.ToggleInstallerPassword = function () 
	{
		if ($('#secure-on').is(':checked')) 
		{
			$('.secure-pass-area').show();
			$('#secure-pass, #secure-pass2').attr('required', 'true');
			$('#secure-pass').attr('data-parsley-equalto', '#secure-pass2');
		} else {
			 $('.secure-pass-area').hide();
			 $('#secure-pass, #secure-pass2').removeAttr('required');
			 $('#secure-pass').removeAttr('data-parsley-equalto');
		}
	};
	
	DupPro.Pack.ApplyDataCurrent = function(id) 
	{
		$('#' + id + ' input').each(function() 
		{
			var attr = $(this).attr('data-current');
			if (typeof attr !== typeof undefined && attr !== false) {
				$(this).attr('value', $(this).attr('data-current'));
			}
		});
	};
});

//INIT
jQuery(document).ready(function ($) 
{
	DupPro.Pack.ToggleInstallerPassword();
});
</script>