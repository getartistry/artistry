<?php

function ybi_curation_suite_api_call($route, $data, $param_arr, $cs_call = false)
{
    $site_url = '';
    if(function_exists('get_site_url')) {
        $site_url = get_site_url();
    }

    $data['site_url'] = $site_url;
    $data['source_type'] = 'P'; // set the source type to plugin
    $data['cs_version'] = CURATION_SUITE_VERSION;
    $data['cs_license'] = get_option('curation_suite_license_key');
    $current_user = wp_get_current_user();
    if (($current_user instanceof WP_User)) {
        if ($current_user->user_firstname == "" && $current_user->user_lastname == "") {
            $user_name = $current_user->user_login;
        } else {
            $user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
        }

        $data['user_full_name'] = $user_name;
        $data['user_email'] = $current_user->user_email;
    }
    $api_key = get_option('curation_suite_listening_api_key');  // get the api key
    $data['api_key'] = $api_key;
    // this will be the base route of api
    if ($cs_call) {
        $api_base_url = CS_API_BASE_URL . $route . '/';
    } else {
        if ($route == '')
            $api_base_url = CS_API_BASE_URL . $api_key . '/';
        else {
            $api_base_url = CS_API_BASE_URL . $route . '/' . $api_key . '/';
        }
    }
    $url = $api_base_url . implode('/', $param_arr);

    $JSON = wp_remote_post($url, array('method' => 'POST', 'body' => $data));
    $is_error = false;
    if (!is_wp_error($JSON)) {
        if (200 == $JSON['response']['code']) {
            $data = json_decode($JSON['body'], true);

        } else {
            $is_error = true;
            $data['status'] = 'failure';
            $message = 'No LE Message';
            if (array_key_exists('message', $data)) {
                $message = $data['message'];
            }
            $data['message'] = 'Connection error to Listening Engine, please contact support.' . $message;
        }
    } else {
        $is_error = true;
        $data['status'] = 'failure';
        $data['message'] = $JSON->get_error_message();
    }

    // fallback CURL for issues with WP built in wp_remote_post
    if ($is_error) {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $JSON = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($JSON, true);
    }
    $data['url'] = $url;
    return $data;
}

function cs_action_log($action_type, $action_data)
{
    $send_data = array('action_type' => $action_type, 'action_data' => $action_data);
    $data = ybi_curation_suite_api_call('cs/action/log', $send_data, array(), true);
    if ($data['status'] != 'success') {
        // do nothing
    }
    return true;
}