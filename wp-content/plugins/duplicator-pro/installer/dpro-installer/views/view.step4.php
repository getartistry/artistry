<?php
	$subsite_id = $_POST['subsite-id'];
	$url_new_rtrim = rtrim($_POST['url_new'], "/");
?>

<script>
	/** Posts to page to remove install files */
	DUPX.removeInstallerFiles = function(package_name, installer_name)
	{
		var msg = "Delete all installation files now? \n\nThis will remove the page you are now viewing.\nThe page will stay active until you navigate away.";
		if (confirm(msg)) {
			<?php if($GLOBALS['MU_MODE'] == 1) { ?>
				var nurl = '<?php echo $url_new_rtrim; ?>/wp-admin/network/admin.php?page=duplicator-pro-tools&tab=diagnostics&action=installer&package=' + package_name + '&installer_name=' + installer_name;
			<?php } else { ?>
				var nurl = '<?php echo $url_new_rtrim; ?>/wp-admin/admin.php?page=duplicator-pro-tools&tab=diagnostics&action=installer&package=' + package_name + '&installer_name=' + installer_name;
			<?php } ?>
			window.open(nurl, "_blank");
		}
	};
</script>

<!-- =========================================
VIEW: STEP 4- INPUT -->
<form id='s4-input-form' method="post" class="content-form" style="line-height:20px">
	<input type="hidden" name="url_new" id="url_new" value="<?php echo $url_new_rtrim; ?>" />
	<div class="logfile-link"><a href="../installer-log.txt?now=<?php echo $GLOBALS['NOW_TIME']; ?>" target="dpro-installer">installer-log.txt</a></div>

	<div class="hdr-main">
		Step <span class="step">4</span> of 4: Test Site
	</div><br/>

	<div class="hdr-sub3">
		<div class="s4-final-title">Final Steps</div>
	</div>

	<table class="s4-final-step">
		<tr style="display:<?php echo ($subsite_id > 0 ? 'table-row' : 'none')?>">
			<td class='step'><a class='s4-final-btns' href='<?php echo "{$_POST['url_new']}/wp-admin/plugins.php"; ?>' target='_blank'>Enable Plugins</a></td>
			<td>
				<i>
					Some plugins may exhibit quirks when switching from subsite to standalone mode, so all plugins have been disabled. <br><br>Re-activate each plugin one-by-one and test the site after each activation.<br><br> If you experience a plugin failure that prevents you from getting back into the site please see TODO:this FAQ item for how recover.
				</i>
			</td>
		</tr>
		<tr>
			<td class='step'><a class='s4-final-btns' href='<?php echo $url_new_rtrim . '?now=' . $GLOBALS['NOW_TIME']; ?>' target='_blank'>Test Site</a></td>
			<td>
				<i>
					Validate all pages, links, images, plugins and
					<a href='<?php echo $url_new_rtrim; ?>/wp-admin/options-permalink.php' target='_blank'>permalinks</a>
				</i>
			</td>
		</tr>
		<tr>
			<td class='step'><a class='s4-final-btns' href="javascript:void(0)" onclick="DUPX.removeInstallerFiles('<?php echo $GLOBALS['FW_PACKAGE_NAME']; ?>', '<?php echo $GLOBALS['BOOTLOADER_NAME']; ?>')">Security Cleanup</a></td>
			<td><i>Remove all installation files (requires login)</i></td>
		</tr>
		<tr>
			<td class='step'><a class='s4-final-btns' href="javascript:void(0)" onclick="$('#s4-install-report').toggle(400)">Show Report</a></td>
			<td>
				<i id="s4-install-report-count">
					<span data-bind="with: status.step1">Install Results: (<span data-bind="text: query_errs"></span>)</span> &nbsp;
					<span data-bind="with: status.step3">Replace Results: (<span data-bind="text: err_all"></span>)</span> &nbsp; &nbsp;
					<span data-bind="with: status.step3" style="color:#888"><b>General Notices:</b> (<span data-bind="text: warn_all"></span>)</span>
				</i>
			</td>
		</tr>
	</table><br/>

	<div class="s4-btns-msg">Click buttons above to complete process</div>

	<div class="s4-go-back">
		<i style='font-size:11px'>To re-install <a href="main.installer.php?archive=<?php echo $GLOBALS['FW_PACKAGE_NAME']; ?>&bootloader=installer.php">start over at step 1</a>.</i><br/>
		<i style="font-size:11px;">The .htaccess file was reset.  Resave plugins that write to this file.</i>
	</div>


	<!-- ========================
	INSTALL REPORT -->
	<div id="s4-install-report" style='display:none'>
		<table class='s4-report-results' style="width:100%">
			<tr><th colspan="4">Database Results</th></tr>
			<tr style="font-weight:bold">
				<td style="width:150px"></td>
				<td>Tables</td>
				<td>Rows</td>
				<td>Cells</td>
			</tr>
			<tr data-bind="with: status.step1">
				<td>Created</td>
				<td><span data-bind="text: table_count"></span></td>
				<td><span data-bind="text: table_rows"></span></td>
				<td>n/a</td>
			</tr>
			<tr data-bind="with: status.step3">
				<td>Scanned</td>
				<td><span data-bind="text: scan_tables"></span></td>
				<td><span data-bind="text: scan_rows"></span></td>
				<td><span data-bind="text: scan_cells"></span></td>
			</tr>
			<tr data-bind="with: status.step3">
				<td>Updated</td>
				<td><span data-bind="text: updt_tables"></span></td>
				<td><span data-bind="text: updt_rows"></span></td>
				<td><span data-bind="text: updt_cells"></span></td>
			</tr>
		</table>

		<table class='s4-report-errs' style="width:100%; border-top:none">
			<tr><th colspan="4">Report Details <i style="display: block; font-size:10px; font-weight:normal">(click links below to view details)</i></th></tr>
			<tr>
				<td data-bind="with: status.step1">
					<a href="javascript:void(0);" onclick="$('#s4-errs-create').toggle(400)">Step 2: Install Results (<span data-bind="text: query_errs"></span>)</a><br/>
				</td>
				<td data-bind="with: status.step3">
					<a href="javascript:void(0);" onclick="$('#s4-errs-upd').toggle(400)">Step 3: Replace Results (<span data-bind="text: err_all"></span>)</a>
				</td>
				<td data-bind="with: status.step3">
					<a href="#s3-errs-warn-anchor" onclick="$('#s4-warnlist').toggle(400)">General Notices (<span data-bind="text: warn_all"></span>)</a>
				</td>
			</tr>
			<tr><td colspan="4"></td></tr>
		</table>


		<div id="s4-errs-create" class="s4-err-msg">
			<div class="s4-err-title">STEP 1 DEPLOY RESULTS</div>
			<b data-bind="with: status.step1">DEPLOY ERRORS (<span data-bind="text: query_errs"></span>)</b><br/>
			<div class="info-error">
				Queries that error during the deploy step are logged to the <a href="../installer-log.txt" target="dpro-installer">install-log.txt</a> file  and marked '**ERROR**'.
				<br/><br/>

				<b><u>Common Fixes:</u></b>
				<br/>

				<b>Query size limits:</b> Update your MySQL server with the <a href="https://dev.mysql.com/doc/refman/5.5/en/packet-too-large.html" target="_blank">max_allowed_packet</a>
				setting to handle larger payloads. <br/>

				<b>Unknown collation:</b> The MySQL Version is too old see:
				<a href="https://snapcreek.com/duplicator/docs/faqs-tech/#faq-trouble-090-q" target="_blank">What is Compatibility mode & 'Unknown collation' errors?</a>
				<br/>
			</div>
		</div>


		<div id="s4-errs-upd" class="s4-err-msg">
			<div class="s4-err-title">STEP 2 UPDATE RESULTS</div>
			<!-- MYSQL QUERY ERRORS -->
			<b data-bind="with: status.step3">UPDATE ERRORS (<span data-bind="text: errsql_sum"></span>) </b><br/>
			<div class="info-error">
				Update errors that show here are queries that could not be performed because the database server being used has issues running it.  Please validate the query, if
				it looks to be of concern please try to run the query manually.  In many cases if your site performs well without any issues you can ignore the error.
			</div>
			<div class="content">
				<div data-bind="foreach: status.step3.errsql"><div data-bind="text: $data"></div></div>
				<div data-bind="visible: status.step3.errsql.length == 0">No MySQL query errors found</div>
			</div>

			<!-- TABLE KEY ERRORS -->
			<b data-bind="with: status.step3">TABLE KEY NOTICE (<span data-bind="text: errkey_sum"></span>)</b><br/>
			<div class="info-notice">
				Notices should be ignored unless issues are found after you have tested an installed site. This notice indicates that a primary key is required to run the
				update engine. Below is a list of tables and the rows that were not updated.  On some databases you can remove these notices by checking the box 'Enable Full Search'
				under advanced options in step3 of the installer.
				<br/><br/>
				<small>
					<b>Advanced Searching:</b><br/>
					Use the following query to locate the table that was not updated: <br/>
					<i>SELECT @row := @row + 1 as row, t.* FROM some_table t, (SELECT @row := 0) r</i>
				</small>
			</div>
			<div class="content">
				<div data-bind="foreach: status.step3.errkey"><div data-bind="text: $data"></div></div>
				<div data-bind="visible: status.step3.errkey.length == 0">No missing primary key errors</div>
			</div>

			<!-- SERIALIZE ERRORS -->
			<b data-bind="with: status.step3">SERIALIZATION NOTICES  (<span data-bind="text: errser_sum"></span>)</b><br/>
			<div class="info-notice">
				Notices should be ignored unless issues are found after you have tested an installed site.  The SQL below will show data that may have not been
				updated during the serialization process.  Best practices for serialization notices is to just re-save the plugin/post/page in question.
			</div>
			<div class="content">
				<div data-bind="foreach: status.step3.errser"><div data-bind="text: $data"></div></div>
				<div data-bind="visible: status.step3.errser.length == 0">No serialization errors found</div>
			</div>
		</div>


		<!-- WARNINGS-->
		<div id="s4-warnlist" class="s4-err-msg">
			<a href="#" id="s3-errs-warn-anchor"></a>
			<b>GENERAL NOTICES</b><br/>
			<div class="info">
				The following is a list of notices that may need to be fixed in order to finalize your setup.  These values should only be investigated if your running into
				issues with your site. For more details see the <a href="https://codex.wordpress.org/Editing_wp-config.php" target="_blank">WordPress Codex</a>.
			</div>
			<div class="content">
				<div data-bind="foreach: status.step3.warnlist">
					 <div data-bind="text: $data"></div>
				</div>
				<div data-bind="visible: status.step3.warnlist.length == 0">
					No notices found
				</div>
			</div>
		</div><br/>

	</div><br/><br/>

	<div class='s4-connect'>
		<a href='http://snapcreek.com/support/docs/faqs/' target='_blank'>FAQs</a> |
		<a href='https://snapcreek.com' target='_blank'>Support</a>
	</div><br/>
</form>

<script type="text/javascript">
	MyViewModel = function() {
		this.status = <?php echo urldecode($_POST['json']); ?>;
		var errorCount =  this.status.step1.query_errs || 0;
		(errorCount >= 1 )
			? $('#s4-install-report-count').css('color', '#BE2323')
			: $('#s4-install-report-count').css('color', '#197713')
	};
	ko.applyBindings(new MyViewModel());
</script>

