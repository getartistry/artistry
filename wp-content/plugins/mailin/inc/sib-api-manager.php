<?php
/**
 * Manage SendinBlue API
 *
 * Use wp API transient to reduce loading time of API call
 */

if(!class_exists('SIB_API_Manager')) {
    class SIB_API_Manager
    {
        /** transient delay time */
        const delayTime = HOUR_IN_SECONDS;

        function __construct()
        {

        }

        /** Get account info */
        public static function get_account_info()
        {
            // get account's info
            $account_info = get_transient('sib_credit_' . md5(SIB_Manager::$access_key));
            if ($account_info === false || $account_info == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $response = $mailin->get_account();

                if ((is_array($response)) && ($response['code'] == 'success')) {
                    $account_data = $response['data'];
                    $count = count($account_data);
                    $account_email = $account_data[$count - 1]['email'];
                    $account_user_name = $account_data[$count - 1]['first_name'] . ' ' . $account_data[$count - 1]['last_name'];

                    $account_info = array(
                        'account_email' => $account_email,
                        'account_user_name' => $account_user_name,
                        'account_data' => $account_data
                    );
                }
                set_transient('sib_credit_' . md5(SIB_Manager::$access_key), $account_info, self::delayTime);
            }
            return $account_info;
        }

        /** Get campaign stats */
        public static function get_campaign_stats()
        {
            $campaigns = get_transient('sib_campaigns_' . md5(SIB_Manager::$access_key));
            if ($campaigns === false || $campaigns == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $data = array();
                $response = $mailin->get_campaigns_v2($data);

                $ret = array(
                    'classic' => array(
                        'Sent' => 0,
                        'Draft' => 0,
                        'Queued' => 0,
                        'Suspended' => 0,
                        'In_process' => 0,
                        'Archive' => 0,
                        'Sent and Archived' => 0,
                        'Temp_active' => 0,
                        'Temp_inactive' => 0,
                        'Scheduled' => 0
                    ),
                    'sms' => array(
                        'Sent' => 0,
                        'Draft' => 0,
                        'Queued' => 0,
                        'Suspended' => 0,
                        'In_process' => 0,
                        'Archive' => 0,
                        'Sent and Archived' => 0,
                        'Temp_active' => 0,
                        'Temp_inactive' => 0,
                        'Scheduled' => 0
                    ),
                    'trigger' => array(
                        'Sent' => 0,
                        'Draft' => 0,
                        'Queued' => 0,
                        'Suspended' => 0,
                        'In_process' => 0,
                        'Archive' => 0,
                        'Sent and Archived' => 0,
                        'Temp_active' => 0,
                        'Temp_inactive' => 0,
                        'Scheduled' => 0
                    ),
                );

                $campaign_records = ($response['code'] == 'success') ? $response['data']['campaign_records'] : array();

                if (isset($campaign_records) && is_array($campaign_records)) {
                    foreach ($campaign_records as $campaign_record) {
                        if ($campaign_record['type'] == 'template' || $campaign_record['type'] == '')
                            continue;

                        $ret[$campaign_record['type']][$campaign_record['status']]++;
                    }
                }
                $campaigns = $ret;
                set_transient('sib_campaigns_' . md5(SIB_Manager::$access_key), $campaigns, self::delayTime);
            }

            return $campaigns;
        }

        /** get smtp status */
        public static function get_smtp_status()
        {
            $status = get_transient('sib_smtp_status_' . md5(SIB_Manager::$access_key));
            if ($status === false || $status == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $response = $mailin->get_smtp_details();
                $status = 'disabled';
                if ($response['code'] == 'success') {
                    $status = $response['data']['relay_data']['status'];
                    set_transient('sib_smtp_status_' . md5(SIB_Manager::$access_key), $status, self::delayTime);

                    // get Marketing Automation API key
                    if(isset($response['data']['marketing_automation']) && $response['data']['marketing_automation']['enabled'] == '1')
                        $ma_key = $response['data']['marketing_automation']['key'];
                    else
                        $ma_key = '';
                    $general_settings = get_option(SIB_Manager::main_option_name, array());
                    $general_settings['ma_key'] = $ma_key;
                    update_option(SIB_Manager::main_option_name, $general_settings);
                }
            }
            return $status;
        }

        /** get all attributes */
        public static function get_attributes()
        {
            // get attributes
            $attrs = get_transient('sib_attributes_' . md5(SIB_Manager::$access_key));

            if ($attrs === false || $attrs == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $response = $mailin->get_attributes();
                $attributes = $response['data'];

                if (!is_array($attributes)) {
                    $attributes = array(
                        'normal_attributes' => array(),
                        'category_attributes' => array(),
                    );
                }
                $attrs = array('attributes' => $attributes);
                if (sizeof($attributes) > 0) {
                    set_transient('sib_attributes_' . md5(SIB_Manager::$access_key), $attrs, self::delayTime);
                }
            }

            return $attrs;

        }

        /** get all smtp templates */
        public static function get_templates()
        {

            // get templates
            $templates = get_transient('sib_template_' . md5(SIB_Manager::$access_key));

            if ($templates === false || $templates == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $data = array(
                    'type' => 'template',
                    'status' => 'temp_active'
                );
                $templates = $mailin->get_campaigns_v2($data);
                $template_data = array();

                if ($templates['code'] == 'success') {

                    foreach ($templates['data']['campaign_records'] as $template) {
                        $is_dopt = 0;
                        if (strpos($template['html_content'], '[DOUBLEOPTIN]') != false) $is_dopt = 1;
                        $template_data[] = array(
                            'id' => $template['id'],
                            'name' => $template['campaign_name'],
                            'is_dopt' => $is_dopt,
                        );

                    }
                }
                $templates = $template_data;
                if (sizeof($templates) > 0) {
                    set_transient('sib_template_' . md5(SIB_Manager::$access_key), $templates, self::delayTime);
                }
            }

            return $templates;
        }

        /** get default list id after install */
        public static function get_default_list_id()
        {
            $lists = self::get_lists();
            return strval($lists[0]['id']);
        }

        /** get all lists */
        public static function get_lists()
        {
            // get lists
            $lists = get_transient('sib_list_' . md5(SIB_Manager::$access_key));
            if ($lists === false || $lists == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $data = array();
                $list_data = $mailin->get_lists($data);
                $lists = array();
                foreach ($list_data['data'] as $list) {
                    if ($list['name'] == 'Temp - DOUBLE OPTIN') {
                        $tempList = $list['id'];
                        update_option(SIB_Manager::templist_option_name, $tempList);
                        continue;
                    }
                    $lists[] = array(
                        'id' => $list['id'],
                        'name' => $list['name']
                    );
                }
                if (sizeof($lists) > 0) {
                    set_transient('sib_list_' . md5(SIB_Manager::$access_key), $lists, self::delayTime);
                }
            }
            return $lists;
        }

        /** get total users */
        public static function get_totalusers()
        {
            $total_subscribers = get_transient('sib_totalusers_' . md5(SIB_Manager::$access_key));
            if ($total_subscribers === false || $total_subscribers == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $data = array();
                $list_response = $mailin->get_lists($data);
                if ($list_response['code'] != 'success') {
                    $total_subscribers = 0;
                } else {
                    $list_datas = $list_response['data'];
                    $list_ids = array();
                    if (isset($list_datas) && is_array($list_datas)) {
                        foreach ($list_datas as $list_data) {
                            $list_ids[] = $list_data['id'];
                        }
                    }
                    $data = array(
                        "listids" => $list_ids,
                        "page" => 1,
                        "page_limit" => 500
                    );
                    $users_response = $mailin->display_list_users($data);
                    $total_subscribers = intval($users_response['data']['total_list_records']);
                }
                set_transient('sib_totalusers_' . md5(SIB_Manager::$access_key), $total_subscribers, self::delayTime);
            }
            return $total_subscribers;
        }

        /** get all sender of sendinblue */
        public static function get_sender_lists()
        {
            $senders = get_transient('sib_senders_' . md5(SIB_Manager::$access_key));
            if ($senders === false || $senders == false) {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
                $data = array("option" => "");
                $response = $mailin->get_senders($data);
                $senders = array();
                if ($response['code'] == 'success') {
                    // reorder by id
                    foreach ($response['data'] as $sender) {
                        $senders[] = array('id' => $sender['id'], 'from_name' => $sender['from_name'], 'from_email' => $sender['from_email']);
                    }
                }
                if (sizeof($senders) > 0) {
                    set_transient('sib_senders_' . md5(SIB_Manager::$access_key), $senders, self::delayTime);
                }
            }
            return $senders;
        }
        /* remove all transients */
        public static function remove_transients(){
            // remove all transients
            delete_transient('sib_list_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_totalusers_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_credit_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_campaigns_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_smtp_status_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_attributes_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_template_' . md5( SIB_Manager::$access_key ));
            delete_transient('sib_senders_' . md5( SIB_Manager::$access_key ));
        }

        ////////////////
        /** Send Identify User for MA*/
        public static function identify_user($data){
            $general_settings = get_option(SIB_Manager::main_option_name, array());
            $ma_key = $general_settings['ma_key'];
            $event = new Sendinblue($ma_key);
            $event->identify($data);
        }

        /** Send email through SendinBlue */
        public static function send_email($data)
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $result = $mailin->send_email($data);
            return $result;
        }

        /**
         * Validation the email if it exist in contact list
         * @param email
         * @param $list_id - array
         * @return array() - if the email is on list already then return updated listID
         */
        static function validation_email($type = 'simple', $email, $list_id)
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);

            $isDopted = false;

            $temp_dopt_list = get_option(SIB_Manager::templist_option_name);

            $desired_lists = $list_id;

            if ($type == 'double-optin') $list_id = array($temp_dopt_list);

            $data = array("email" => $email);
            $response = $mailin->get_user($data);
            $res = $response['data'];

            // new user
            if ($response['code'] == 'failure') {
                $ret = array(
                    'code' => 'new',
                    'isDopted' => $isDopted,
                    'listid' => $list_id
                );
                return $ret;
            }

            $listid = $res['listid'];

            // udpate user when listid is empty
            if (!isset($listid) || !is_array($listid)) {
                $ret = array(
                    'code' => 'update',
                    'isDopted' => $isDopted,
                    'listid' => $list_id
                );
                return $ret;
            }

            $attrs = $res['attributes'];
            if (isset($attrs['DOUBLE_OPT-IN']) && $attrs['DOUBLE_OPT-IN'] == '1') {
                $isDopted = true;
            }

            // remove dopt temp list from $listid
            if (($key = array_search($temp_dopt_list, $listid)) !== false) {
                unset($listid[$key]);
            }

            $diff = array_diff($desired_lists, $listid);
            if (!empty($diff)) {
                $status = 'update';
                if ($type != 'double-optin') $listid = array_unique(array_merge($listid, $list_id));
                if (($type == 'double-optin' && !$isDopted)) array_push($listid, $temp_dopt_list);
            } else {
                if($res['blacklisted'] == '1')
                {
                    $status = 'update';
                }
                else{
                    $status = 'already_exist';
                }
            }

            $ret = array(
                'code' => $status,
                'isDopted' => $isDopted,
                'listid' => $listid
            );
            return $ret;
        }

        /**
         * signup process
         * @param $email
         * @param $type - simple, confirm, double-optin / subscribe
         * @param $info - user's attributes
         * @param $list_id - array : desired list
         * @return string - success or failure
         */
        public static function create_subscriber($type = 'simple', $email, $list_id, $info, $list_unlink = null)
        {

            $response = self::validation_email($type, $email, $list_id);

            if ($response['code'] == 'already_exist') return 'already_exist';

            if ($type == 'subscribe') {
                $info['DOUBLE_OPT-IN'] = '1'; // Yes
            } else {
                if ($type == 'double-optin') {
                    if (($response['code'] == 'new' && !$response['isDopted']) || ($response['code'] == 'update' && !$response['isDopted'])) {
                        $info['DOUBLE_OPT-IN'] = '2'; // No
                    }
                }
            }
            $listid = $response['listid'];

            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $data = array(
                "email" => $email,
                "attributes" => $info,
                "blacklisted" => 0,
                "listid" => $listid,
                "listid_unlink" => $list_unlink, // remove temp list for dopt subscribe
                "blacklisted_sms" => 0
            );
            $response = $mailin->create_update_user($data);

            return $response['code'];
        }

        /**
         * Send a mail for confirmation through SendinBlue
         * @param $type - confirm or double-optin
         * @param $to_email
         * @param $template_id
         * @param $code
         * @param $list_id
         *
         * @return bool
         */
        public static function send_comfirm_email($type = 'confirm', $to_email, $template_id = '-1', $attributes=null, $code = '')
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);

            // set subject info
            if ($type == 'confirm') {
                $subject = __('Subscription confirmed', 'sib_lang');
            } elseif ($type == "double-optin") {
                $subject = __('Please confirm subscription', 'sib_lang');
            }

            // get sender info
            $home_settings = get_option(SIB_Manager::home_option_name);
            if (isset($home_settings['sender'])) {
                $sender_name = $home_settings['from_name'];
                $sender_email = $home_settings['from_email'];
            } else {
                $sender_email = trim(get_bloginfo('admin_email'));
                $sender_name = trim(get_bloginfo('name'));
            }
            if ($sender_email == '') {
                $sender_email = __('no-reply@sendinblue.com', 'sib_lang');
                $sender_name = __('SendinBlue', 'sib_lang');
            }

            $template_contents = self::get_email_template($type);
            $html_content = $template_contents['html_content'];

            $transactional_tags = 'Wordpress Mailin';
            $attachment = array();

            // get info from SIB template
            if (intval($template_id) > 0) {
                $data = array(
                    'id' => $template_id
                );
                $response = $mailin->get_campaign_v2($data);
                if ($response['code'] == 'success') {
                    $html_content = $response['data'][0]['html_content'];
                    if (trim($response['data'][0]['subject']) != '') {
                        $subject = trim($response['data'][0]['subject']);
                    }
                    if (($response['data'][0]['from_name'] != '[DEFAULT_FROM_NAME]') &&
                        ($response['data'][0]['from_email'] != '[DEFAULT_FROM_EMAIL]') &&
                        ($response['data'][0]['from_email'] != '')
                    ) {
                        $sender_name = $response['data'][0]['from_name'];
                        $sender_email = $response['data'][0]['from_email'];
                    }
                    $transactional_tags = $response['data'][0]['campaign_name'];
                    // pls ask Ekta about attachment of template
                    //$attachment = $response['data'][0]['attachment'];
                }
            }

            // send mail
            $to = array($to_email => '');
            $from = array($sender_email, $sender_name);

            $site_domain = str_replace('https://', '', home_url());
            $site_domain = str_replace('http://', '', $site_domain);

            $html_content = str_replace('{title}', $subject, $html_content);

            $html_content = str_replace('{site_domain}', $site_domain, $html_content);
            $encodedEmail = rtrim(strtr(base64_encode($to_email), '+/', '-_'), '=');
            //$html_content = str_replace('[UNSUBSCRIBE]', add_query_arg(array('sib_action' => 'unsubscribe', 'code' => $encodedEmail), home_url()), $html_content);

            // double optin
            $html_content = str_replace('https://[DOUBLEOPTIN]', '{subscribe_url}', $html_content);
            $html_content = str_replace('http://[DOUBLEOPTIN]', '{subscribe_url}', $html_content);
            $html_content = str_replace('[DOUBLEOPTIN]', '{subscribe_url}', $html_content);
            $html_content = str_replace('{subscribe_url}', add_query_arg(array('sib_action' => 'subscribe', 'code' => $code), home_url()), $html_content);

            $home_settings = get_option(SIB_Manager::home_option_name);
            if ($home_settings['activate_email'] == 'yes') {

                if (intval($template_id) > 0 && is_array($attributes) && ($type == "confirm")) {
                    $attrs = array_merge($attributes, array('EMAIL' => $to_email));
                    $data = array(
                        "id" => intval($template_id),
                        "to" => $to_email,
                        "attr" => $attrs,
                        "attachment_url" => "",
                        "headers" => array("Content-Type"=> "text/html;charset=iso-8859-1", "X-Mailin-tag"=>$transactional_tags )
                    );
                    $res = $mailin->send_transactional_template($data);
                }else {
                    $headers = array("Content-Type" => "text/html;charset=iso-8859-1", "X-Mailin-tag" => $transactional_tags);
                    $data = array(
                        "to" => $to,
                        "from" => $from,
                        "subject" => $subject,
                        "html" => $html_content,
                        "headers" => $headers,
                        "attachment" => $attachment,
                    );
                    $res = $mailin->send_email($data);
                }

            } else {
                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                $headers[] = "From: $sender_name <$sender_email>";
                @wp_mail($to_email, $subject, $html_content, $headers);
            }
        }

        /**
         * get email template by type (test, confirmation, double-optin)
         * return @values : array ( 'html_content' => '...', 'text_content' => '...' );
         */
        static function get_email_template($type = 'test')
        {
            $lang = get_bloginfo('language');
            if ($lang == 'fr-FR')
                $file = 'temp_fr-FR';
            else
                $file = 'temp';

            $file_path = SIB_Manager::$plugin_dir . '/inc/templates/' . $type . '/';
            // get html content
            $html_content = file_get_contents($file_path . $file . '.html');
            // get text content
            $text_content = file_get_contents($file_path . $file . '.txt');
            $templates = array('html_content' => $html_content, 'text_content' => $text_content);
            return $templates;
        }

        /**
         * sync wp users to contact list
         * @param $info - user's attributes
         * @param $list_ids - array : desired list
         * @return string - success or failure
         */
        public static function sync_users($users_info, $list_ids)
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $data = array(
                "body" => $users_info,
                "listids" => $list_ids,
            );
            $res = $mailin->import_users($data);
            return $res;
        }

        /**
         * Subscribe process for double optin subscribers
         */
        public static function subscribe()
        {
            $code = esc_attr($_GET['code']);

            $contact_info = SIB_Model_Users::get_data_by_code($code);

            if ($contact_info != false) {
                $email = $contact_info['email'];
                $info = maybe_unserialize($contact_info['info']);
                $list_id = maybe_unserialize($contact_info['listIDs']);

                // temp dopt list
                $temp_list = get_option(SIB_Manager::templist_option_name);

                self::create_subscriber('subscribe', $email, $list_id, $info, array($temp_list));
                // remove the record
                $id = $contact_info['id'];
                SIB_Model_Users::remove_record($id);
            }

            if ($contact_info['redirectUrl'] != '') {
                wp_redirect($contact_info['redirectUrl']);
                exit;
            }

            $site_domain = str_replace('https://', '', home_url());
            $site_domain = str_replace('http://', '', $site_domain);
            ?>
            <body style="margin:0; padding:0;">
            <table style="background-color:#ffffff" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                <tr style="border-collapse:collapse;">
                    <td style="border-collapse:collapse;" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20">
                                    <div
                                        style="font-family:arial,sans-serif; color:#61a6f3; font-size:20px; font-weight:bold; line-height:28px;">
                                        <?php _e('Thank you for subscribing', 'sib_lang'); ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td align="left">

                                    <div
                                        style="font-family:arial,sans-serif; font-size:14px; margin:0; line-height:24px; color:#555555;">
                                        <br>
                                        <?php echo __('You have just subscribed to the newsletter of ', 'sib_lang') . $site_domain . ' .'; ?>
                                        <br><br>
                                        <?php _e('-SendinBlue', 'sib_lang'); ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            </body>
            <?php
            exit;
        }

        /**
         * Unsubscribe process
         */
        function unsubscribe()
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $code = esc_attr($_GET['code']);
            $list_id = intval($_GET['li']);

            $email = base64_decode(strtr($code, '-_', '+/'));
            $data = array(
                'email' => $email
            );
            $response = $mailin->get_user($data);

            if ($response['code'] == 'success') {
                $attributes = $response['data']['attributes'];

                $listid = $response['data']['listid'];

                $blacklisted = $response['data']['blacklisted'];
                $diff_listid = array_diff($listid, array($list_id));

                if (count($diff_listid) == 0) {
                    $blacklisted = 1;
                    $diff_listid = $listid;
                }
                $data = array(
                    "email" => $email,
                    "attributes" => $attributes,
                    "blacklisted" => $blacklisted,
                    "listid" => $diff_listid,
                    "listid_unlink" => null,
                    "blacklisted_sms" => 0
                );
                $mailin->create_update_user($data);
            }
            ?>
            <body style="margin:0; padding:0;">
            <table style="background-color:#ffffff" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                <tr style="border-collapse:collapse;">
                    <td style="border-collapse:collapse;" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20">
                                    <div
                                        style="font-family:arial,sans-serif; color:#61a6f3; font-size:20px; font-weight:bold; line-height:28px;">
                                        <?php _e('Unsubscribe', 'sib_lang'); ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td align="left">

                                    <div
                                        style="font-family:arial,sans-serif; font-size:14px; margin:0; line-height:24px; color:#555555;">
                                        <br>
                                        <?php _e('Your request has been taken into account.', 'sib_lang'); ?><br>
                                        <br>
                                        <?php _e('The user has been unsubscribed', 'sib_lang'); ?><br>
                                        <br>
                                        -SendinBlue
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" width="540">
                            <tbody>
                            <tr>
                                <td style="line-height:0; font-size:0;" height="20">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            </body>
            <?php
            exit;
        }

        /** update access token */
        public static function update_access_token()
        {
            $access_token_settings = get_option(SIB_Manager::access_token_option_name, array());
            $access_token = isset($access_token_settings['access_token']) ? $access_token_settings['access_token'] : '';

            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $mailin->delete_token($access_token);

            $access_response = $mailin->get_access_tokens();
            if ($access_response['code'] != 'success') {
                $access_response = $mailin->get_access_tokens();
            }
            $access_token = $access_response['data']['access_token'];
            $token_settings = array(
                'access_token' => $access_token
            );

            update_option(SIB_Manager::access_token_option_name, $token_settings);
            return $access_token;
        }

        /** create list and attribute for double optin */
        public static function create_default_dopt()
        {

            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);

            // add list
            $isEmpty = false;
            $data = array();
            $list_data = $mailin->get_lists($data);
            foreach ($list_data['data'] as $list) {
                if ($list['name'] == 'Temp - DOUBLE OPTIN') {
                    $isEmpty = true;
                    continue;
                }
            }
            if (!$isEmpty) {
                $data = array(
                    "list_name" => "Temp - DOUBLE OPTIN",
                    "list_parent" => 1
                );
                $mailin->create_list($data);
            }

            // add attribute
            $isEmpty = false;
            $data = array("type" => "category");
            $ret = $mailin->get_attribute($data);
            foreach ($ret['data'] as $attr) {
                if ($attr['name'] == 'DOUBLE_OPT-IN' && !empty($attr['enumeration'])) {
                    $isEmpty = true;
                }
            }
            if (!$isEmpty) {
                $data = array(
                    "type" => "category",
                    "data" => '[ {"name": "DOUBLE_OPT-IN", "enumeration": [ {"label": "Yes"}, {"label": "No"} ]} ]'
                );
                $mailin->create_attribute($data);
            }
        }

    }
}