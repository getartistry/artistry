<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');

$profile_url = DUP_PRO_U::getMenuPageURL(DUP_PRO_Constants::$STORAGE_SUBMENU_SLUG, false);
$storage_tab_url = DUP_PRO_U::appendQueryValue($profile_url, 'tab', 'storage');

$edit_storage_url = DUP_PRO_U::appendQueryValue($storage_tab_url, 'inner_page', 'edit');
$edit_default_storage_url = DUP_PRO_U::appendQueryValue($storage_tab_url, 'inner_page', 'edit-default');

$inner_page = isset($_REQUEST['inner_page']) ? esc_html($_REQUEST['inner_page']) : 'storage';

switch ($inner_page)
{
    case 'storage': include('storage.list.php');
        break;

    case 'edit': include('storage.edit.php');
        break;
	
	case 'edit-default': include('storage.edit.default.php');
        break;
}
?>

