<?php
$profile_url = DUP_PRO_U::getMenuPageURL(DUP_PRO_Constants::$SCHEDULES_SUBMENU_SLUG, false);
$schedules_tab_url = DUP_PRO_U::appendQueryValue($profile_url, 'tab', 'schedules');
$edit_schedule_url = DUP_PRO_U::appendQueryValue($schedules_tab_url, 'inner_page', 'edit');
$inner_page = isset($_REQUEST['inner_page']) ? esc_html($_REQUEST['inner_page']) : 'schedules';

switch ($inner_page)
{
    case 'schedules': include('schedule.list.php');
        break;
    case 'edit': include('schedule.edit.php');
        break;
}
?>