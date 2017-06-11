<?php

$profile_url = DUP_PRO_U::getMenuPageURL(DUP_PRO_Constants::$TEMPLATES_SUBMENU_SLUG, false);
$templates_tab_url = DUP_PRO_U::appendQueryValue($profile_url, 'tab', 'templates');

$edit_template_url = DUP_PRO_U::appendQueryValue($templates_tab_url, 'inner_page', 'edit');

$inner_page = isset($_REQUEST['inner_page']) ? esc_html($_REQUEST['inner_page']) : 'templates';

switch ($inner_page)
{
    case 'templates': include('template.list.php');
        break;

    case 'edit': include('template.edit.php');
        break;
}
?>