<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (ASP_DEMO) $_POST = null;
?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<div id="wpdreams" class='asp_updates_help<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>
	<div class="wpdreams-box" style="float: left;">
		<div class="wpd-half">
            <h3>Version status</h3>
            <div class="item">
                <?php if (wd_asp()->updates->needsUpdate()): ?>
                    <p class="infoMsg">A new version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is available!</p>
                <?php else: ?>
                    <p>You have the latest version installed: <strong><?php echo ASP_CURR_VER_STRING; ?></strong></p>
                <?php endif; ?>
            </div>
            <?php if (wd_asp()->updates->getUpdateNotes(ASP_CURR_VER) != ""): ?>
                <h3>Recent update notes</h3>
                <div class="item asp_update_notes">
                    <?php echo wd_asp()->updates->getUpdateNotes(ASP_CURR_VER); ?>
                </div>
            <?php endif; ?>
            <h3>Support</h3>
            <div class="item">
                <?php if (wd_asp()->updates->getSupport() != ""): ?>
                    <p class="errorMsg">IMPORTANT:<br><?php echo wd_asp()->updates->getSupport(); ?></p>
                <?php endif; ?>
                If you can't find the answer in the documentation or knowledge base, or if you are having other issues,
                feel free to <a href="https://wp-dreams.com/open-support-ticket-step-1/" target="_blank">open a support ticket</a>.
            </div>
			<h3>Documentation</h3>
			<div class="item">
				<ul>
					<li><a target="_blank" href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/" title="HTML documentation">HTML version</a></li>
					<li><a target="_blank" href="https://www.gitbook.com/download/pdf/book/wpdreams/ajax-search-pro-documentation" title="PDF documentation">PDF version (download)</a></li>
					<li><a target="_blank" href="https://wp-dreams.com/knowledgebase/" title="Knowledge Base">Knowledge base</a></li>
				</ul>
			</div>
			<h3>Knowledge Base</h3>
			<div class="item">
				<?php echo wd_asp()->updates->getKnowledgeBase(); ?>
			</div>
		</div>
		<div class="wpd-half-last">
            <?php if (ASP_DEMO == 0): ?>
			<h3>Automatic Updates</h3>
            <div class="item<?php echo WD_ASP_License::isActivated() === false ? "" : " hiddend"; ?>">
                <div class="asp_auto_update">
                    <p>To activate Automatic Updates, please activate your purchase code with this site.</p>
                    <label>Purchase code</label>
                    <input type="text" name="asp_key" id="asp_key">
                    <div class="errorMsg" style="display:none;"></div>
                    <input type="button" id="asp_activate" name="asp_activate" class="submit wd_button_blue" value="Activate for this site">
                    <span class="small-loading" style="display:none; vertical-align: middle;"></span>
                    <p>If you activated the plugin <b>with this site before</b>, and you see this activation form, just enter the purchase code again to re-activate.</p>
                </div>
                <div class="asp_remote_deactivate">
                    <p>If the purchase code is activated with a <b>different site</b>, then you will have to first de-activate it from there, or use the form below if the site does not work anymore:</p>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Site URL</label>
                    <input type="text" name="asp_site_url" id="asp_site_url"><br><br>
                    <label>Purchase code</label>
                    <input type="text" name="asp_keyd" id="asp_keyd"><br>
                    <div class="infoMsg" style="display:none;"></div>
                    <div class="errorMsg" style="display:none;"></div>
                    <input type="button" id="asp_deactivated" name="asp_deactivated" class="submit wd_button_blue" value="Deactivate">
                    <span class="small-loading" style="display:none; vertical-align: middle;"></span>
                    <p class="descMsg" style="text-align: left;margin-top: 10px;"><b>NOTICE:</b> After deactivation there is a <b>30 minute</b> wait time until you can re-activate the same purchase code to prevent malicious activity.</p>
                </div>
            </div>
            <div class="item<?php echo WD_ASP_License::isActivated() === false ? " hiddend" : ""; ?> asp_auto_update">
                <p>Auto updates are activated for this site with purchase code: <br><b><?php echo WD_ASP_License::isActivated(); ?></b></p>
                <div class="errorMsg" style="display:none;"></div>
                <input type="button" class="submit wd_button_blue" id="asp_deactivate" name="asp_deactivate" value="Deactivate">
                <span class="small-loading" style="display:none; vertical-align: middle;"></span>
                <p class="descMsg" style="text-align: left;margin-top: 10px;"><b>NOTICE:</b> After deactivation there is a <b>30 minute</b> wait time until you can re-activate the same purchase code to prevent malicious activity.</p>
            </div>
            <h3>Manual Updates</h3>
            <div class="item">
                <a target="_blank" href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/update_notes.html">How to manual update?</a>
            </div>
            <?php endif; ?>
			<h3>Changelog</h3>
			<div class="item">
				<dl>
					<?php foreach (wd_asp()->updates->getChangeLog() as $version => $log): ?>
						<dt class="changelog_title">v<?php echo $version; ?> - <a href="#">view changelog</a></dt>
						<dd class="hiddend"><pre><?php echo $log; ?></pre></dd>
					<?php endforeach; ?>
				</dl>
			</div>
		</div>
        <div class="clear"></div>
	</div>
    <div id="asp-options-search">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>
<?php
wp_enqueue_script('wpd-backend-updates-help', plugin_dir_url(__FILE__) . 'settings/assets/updates_help.js', array(
    'jquery'
), ASP_CURR_VER_STRING, true);