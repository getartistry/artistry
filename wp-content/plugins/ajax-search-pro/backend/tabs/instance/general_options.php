<?php
$_red_opts = array(
    array("option" => "Trigger live search", "value" => "ajax_search"),
    array("option" => "Redirect to: First matching result", "value" => "first_result"),
    array("option" => "Redirect to: Results page", "value" => "results_page"),
    array("option" => "Redirect to: Woocommerce results page", "value" => "woo_results_page"),
    array("option" => "Redirect to: Custom URL", "value" => "custom_url"),
    array("option" => "Do nothing", "value" => "nothing")
);
if ( !class_exists("WooCommerce") ) unset($_red_opts[3]);
?>
<ul id="subtabs"  class='tabs'>
    <li><a tabid="101" class='subtheme current'>Sources</a></li>
    <li><a tabid="105" class='subtheme'>Sources 2</a></li>
	<li><a tabid="109" class='subtheme'>Attachments</a></li>
	<li><a tabid="108" class='subtheme'>User Search</a></li>
    <li><a tabid="102" class='subtheme'>Logic & Behavior</a></li>
    <li><a tabid="110" class='subtheme'>Mobile Behavior</a></li>
    <li><a tabid="103" class='subtheme'>Image Options</a></li>
    <?php if (function_exists('bp_core_get_user_domain')): ?>
    <li><a tabid="104" class='subtheme'>BuddyPress</a></li>
    <?php endif; ?>
    <li><a tabid="111" class='subtheme'>Limits</a></li>
    <li><a tabid="107" class='subtheme'>Ordering</a></li>
</ul>
<div class='tabscontent'>
    <div tabid="101">
        <fieldset>
            <legend>Sources</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/sources.php"); ?>
        </fieldset>
    </div>
    <div tabid="102">
            <?php include(ASP_PATH."backend/tabs/instance/general/behaviour.php"); ?>
    </div>
    <div tabid="110">
        <fieldset>
            <legend>Behavior on Mobile devices</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/mobile_behavior.php"); ?>
        </fieldset>
    </div>
    <div tabid="103">
        <fieldset>
            <legend>Image Options</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/image_options.php"); ?>
        </fieldset>
    </div>
    <div tabid="104">
        <fieldset>
            <legend>BuddyPress Options</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/buddypress_options.php"); ?>
        </fieldset>
    </div>
    <div tabid="108">
        <fieldset>
            <legend>User Search</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/user_search.php"); ?>
        </fieldset>
    </div>
    <div tabid="105">
        <fieldset>
            <legend>Sources 2</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/sources2.php"); ?>
        </fieldset>
    </div>
    <div tabid="111">
        <fieldset>
            <legend>Limits</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/limits.php"); ?>
        </fieldset>
    </div>
    <div tabid="107">
        <fieldset>
            <legend>Ordering</legend>
            <?php include(ASP_PATH."backend/tabs/instance/general/ordering.php"); ?>
        </fieldset>
    </div>
	<div tabid="109">
		<fieldset>
			<legend>Attachment Search</legend>
			<?php include(ASP_PATH."backend/tabs/instance/general/attachment_results.php"); ?>
		</fieldset>
	</div>
</div>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
</div>