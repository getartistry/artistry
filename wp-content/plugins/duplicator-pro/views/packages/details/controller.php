<?php
DUP_PRO_U::hasCapability('manage_options');
global $wpdb;

//COMMON HEADER DISPLAY
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');

$current_tab = isset($_REQUEST['tab']) ? esc_html($_REQUEST['tab']) : 'detail';
$package_id = isset($_REQUEST["id"])   ? esc_html($_REQUEST["id"]) : 0;


$package = DUP_PRO_Package::get_by_id($package_id);
$package_found = is_object($package) ? true : false;

if ($package_found) {
    $enable_transfer_tab = $package->does_default_storage_exist();
    $error_display		= ($package->Status == DUP_PRO_PackageStatus::ERROR) ? 'default' : 'none';
    $err_link_pack		= "DupPro.Pack.DownloadPackageFile(3, {$package->ID});return false;";
    $err_link_log		= "<a target='_blank' href='#' onclick='{$err_link_pack}'>" . DUP_PRO_U::__('Package Log') . '</a>';
    $err_link_faq		= '<a target="_blank" href="https://snapcreek.com/duplicator/docs/faqs-tech/">' . DUP_PRO_U::__('FAQ Pages') . '</a>';
    $err_link_ticket	= '<a target="_blank" href="https://snapcreek.com/ticket/">' . DUP_PRO_U::__('Help Ticket') . '</a>';
}
?>

<style>
    .narrow-input { width: 80px; }
    .wide-input {width: 400px; } 
	 table.form-table tr td { padding-top: 25px; }
	 div.all-packages {float:right; margin-top: -30px; }
	 div.all-packages a.add-new-h2 {font-size: 16px}
	 #dpro-error { display: <?php echo $error_display; ?>;  margin:5px 0; text-align:center; font-style:italic}
</style>

<?php if (! $package_found) : ?>
    <br/><br/>
    <div id='dpro-error' class="error">
        <p>
            <?php echo sprintf(DUP_PRO_U::__("Unable to find package id %d.  The package does not exist or was deleted."), $package_id); ?> <br/>
        </p>
    </div>
<?php
    die();
    endif;
	duplicator_pro_header(DUP_PRO_U::__("Package Details &raquo; {$package->Name}")); 
?>

<h2 class="nav-tab-wrapper">  
	<a href="?page=duplicator-pro&action=detail&tab=detail&id=<?php echo $package_id ?>" class="nav-tab <?php echo ($current_tab == 'detail') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Details'); ?></a> 
	<a <?php if($enable_transfer_tab === false) { echo 'onclick="DupPro.Pack.TransferDisabled(); return false;"';} ?> href="?page=duplicator-pro&action=detail&tab=transfer&id=<?php echo $package_id ?>" class="nav-tab <?php echo ($current_tab == 'transfer') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Transfer'); ?></a> 		
</h2>
<div class="all-packages"><a href="?page=duplicator-pro" class="add-new-h2"><i class="fa fa-archive"></i> <?php DUP_PRO_U::_e('All Packages'); ?></a></div>

<div id='dpro-error' class="error">
	<p>
		<?php echo DUP_PRO_U::__('Error encountered building package. Review ') . $err_link_log . DUP_PRO_U::__(' for details.')  ; ?> <br/>
		<?php echo DUP_PRO_U::__('For more help read ') . $err_link_faq . DUP_PRO_U::__(' or submit a ') . $err_link_ticket; ?> 
	</p>
</div>

<?php
switch ($current_tab) {
	case 'detail': include('detail.php');            
		break;
	case 'transfer': include('transfer.php');
		break; 
}
?>


<script type="text/javascript">
	DupPro.Pack.TransferDisabled = function() {
		alert("<?php DUP_PRO_U::_e('No package in default location so transfer is disabled.');?>")
	}
</script>
