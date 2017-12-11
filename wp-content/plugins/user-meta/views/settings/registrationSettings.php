<?php
global $userMeta;
// Expected: $registration
// field slug: registration

$html = null;

$html .= "<p><strong>" . __('User registration page', $userMeta->name) . "  </strong></p>";
$html .= wp_dropdown_pages(array(
    'name' => 'registration[user_registration_page]',
    'id' => 'um_registration_user_registration_page',
    'selected' => @$registration['user_registration_page'],
    'echo' => 0,
    'show_option_none' => 'None '
));

$html .= '<p>Registration page should contain shortcode like: [user-meta-registration form="your_form_name"]</p>';

$html .= $userMeta->renderPro("registrationSettingsPro", array(
    'registration' => $registration
), "settings");
