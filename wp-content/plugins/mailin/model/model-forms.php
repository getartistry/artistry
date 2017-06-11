<?php
/**
 * Model class <i>SIB_Forms</i> represents forms
 * @package SIB_Model
 */

if(!class_exists('SIB_Forms')) {
    class SIB_Forms
    {
        /**
         * Tab table name
         */
        const table_name = 'sib_model_forms';

        /** Create Table */
        public static function createTable()
        {
            global $wpdb;
            // create list table
            $creation_query =
                'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix.self::table_name . ' (
                `id` int(20) NOT NULL AUTO_INCREMENT,
                `title` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `html` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `css` longtext,
                `dependTheme` int(1) NOT NULL DEFAULT 1,
                `listID` longtext,
                `templateID` int(20) NOT NULL DEFAULT -1,
                `isDopt` int(1) NOT NULL DEFAULT 0,
                `isOpt` int(1) NOT NULL DEFAULT 0,
                `redirectInEmail` varchar(255),
                `redirectInForm` varchar(255),
                `successMsg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `errorMsg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `existMsg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `invalidMsg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `attributes` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `date` DATE NOT NULL,
                `isDefault` int(1) NOT NULL DEFAULT 0,
                `gCaptcha` int(1) NOT NULL DEFAULT 0,
                `gCaptcha_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `gCaptcha_site` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `termAccept` int(1) NOT NULL DEFAULT 1,
                `termsURL` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                PRIMARY KEY (`id`)
                );';
            require_once ( ABSPATH. 'wp-admin/includes/upgrade.php');
            dbDelta($creation_query);
            // create default form
            self::createDefaultForm();
        }

        /**
         * Remove table
         */
        public static function removeTable()
        {
            global $wpdb;
            $query = 'DROP TABLE IF EXISTS ' . $wpdb->prefix.self::table_name . ';';
            $wpdb->query($query);
        }

        public static function alterTable()
        {
            global $wpdb;

            // add columns -gCaptcha, gCaptcha_secret

            $check_query= "SHOW COLUMNS FROM `"  . $wpdb->prefix.self::table_name . "` LIKE 'gCaptcha';";
            $result = $wpdb->query($check_query);

            if(empty($result))
            {
                $alter_query = "ALTER TABLE "  . $wpdb->prefix.self::table_name . "
                            ADD COLUMN gCaptcha int(1) not NULL DEFAULT 0,
                             ADD COLUMN gCaptcha_secret varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                             ADD COLUMN gCaptcha_site varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci";
                $ret = $wpdb->query($alter_query);
            }

        }


        public static function getForm($frmID = 'new')
        {
            global $wpdb;
            if ($frmID == 'new') {
                // default form
                $formData = self::getDefaultForm();
                $list = maybe_serialize(array(SIB_API_Manager::get_default_list_id()));
                $results = array(
                    'title' => '',
                    'html' => $formData['html'],
                    'css' => $formData['css'],
                    'listID' => $list,
                    'dependTheme' => '1',
                    'templateID' => '-1',
                    'isOpt' => '0',
                    'isDopt' => '0',
                    'redirectInEmail' => '',
                    'redirectInForm' => '',
                    'date' => date('Y-m-d'),
                    'successMsg' => $formData['successMsg'],
                    'errorMsg' => $formData['errorMsg'],
                    'existMsg' => $formData['existMsg'],
                    'invalidMsg' => $formData['invalidMsg'],
                    'attributes' => 'email,NAME',
                );
            } else {
                $query = 'select * from ' . $wpdb->prefix.self::table_name . ' where id=' . $frmID . ';';
                $results = $wpdb->get_row($query, ARRAY_A);
            }

            if (is_array($results) && count($results) > 0) {
                $listIDs = maybe_unserialize($results['listID']);
                $results['listID'] = $listIDs; // array
                return $results;
            }
            return array();
        }

        /**
         * Get all forms
         */
        public static function getForms()
        {
            global $wpdb;

            $query = 'select * from ' . $wpdb->prefix.self::table_name . ';';
            $results = $wpdb->get_results($query, ARRAY_A);

            if (is_array($results) && count($results) > 0) {
                // add list names field to display form table
                foreach ($results as $key => $form) {
                    $listIDs = maybe_unserialize($form['listID']);
                    // get names form id array
                    $lists = SIB_API_Manager::get_lists(); // pair of id and name

                    $listNames = array();
                    foreach ($lists as $list) {
                        if (in_array($list['id'], $listIDs))
                            $listNames[] = $list['name'];
                    }
                    $results[$key]['listName'] = implode(",", $listNames); // string
                    $results[$key]['listID'] = $listIDs; // array
                }
                return $results;
            }
            return array();

        }

        /**
         * Add new form
         */
        public static function addForm($formData)
        {
            global $wpdb;

            $current_date = date('Y-m-d');
            // insert
            global $wpdb;
            $query = 'INSERT INTO ' . $wpdb->prefix.self::table_name . ' ';
            $query .= '(title,html,css,dependTheme,listID,templateID,isOpt,isDopt,redirectInEmail,redirectInForm,successMsg,errorMsg,existMsg,invalidMsg,attributes,date,gCaptcha,gCaptcha_secret,gCaptcha_site,termAccept,termsURL) ';
            $query .= "VALUES ('{$formData['title']}','{$formData['html']}','{$formData['css']}','{$formData['dependTheme']}','{$formData['listID']}',
        '{$formData['templateID']}','{$formData['isOpt']}','{$formData['isDopt']}','{$formData['redirectInEmail']}','{$formData['redirectInForm']}',
        '{$formData['successMsg']}','{$formData['errorMsg']}','{$formData['existMsg']}','{$formData['invalidMsg']}','{$formData['attributes']}','{$current_date}','{$formData['gcaptcha']}','{$formData['gcaptcha_secret']}' ,'{$formData['gcaptcha_site']}','{$formData['termAccept']}','{$formData['termsURL']}')";
            $wpdb->query($query);
            $index = $wpdb->get_var('SELECT LAST_INSERT_ID();');
            return $index;
        }

        /**
         * update form
         */
        public static function updateForm($formID, $formData)
        {
            global $wpdb;

            $current_date = date('Y-m-d');

            // update
            global $wpdb;
            $query = "update " . $wpdb->prefix.self::table_name . " ";
            $query .= "set title='{$formData['title']}',html='{$formData['html']}',css='{$formData['css']}',dependTheme='{$formData['dependTheme']}',listID='{$formData['listID']}',
        isOpt='{$formData['isOpt']}',isDopt='{$formData['isDopt']}',templateID='{$formData['templateID']}',
        redirectInEmail='{$formData['redirectInEmail']}',redirectInForm='{$formData['redirectInForm']}',
        successMsg='{$formData['successMsg']}',errorMsg='{$formData['errorMsg']}',existMsg='{$formData['existMsg']}',invalidMsg='{$formData['invalidMsg']}',date='{$current_date}',attributes='{$formData['attributes']}',gCaptcha='{$formData['gcaptcha']}',gCaptcha_secret='{$formData['gcaptcha_secret']}' ,gCaptcha_site='{$formData['gcaptcha_site']}' ,termAccept='{$formData['termAccept']}',termsURL='{$formData['termsURL']}'";
            $query .= "where id=" . $formID . ";";
            $wpdb->query($query);

            return true;
        }

        /**
         * remove a form
         */
        public static function deleteForm($id)
        {
            global $wpdb;

            $wpdb->delete($wpdb->prefix.self::table_name,
                array('id' => $id)
            );
        }

        /** clear forms data */
        public static function removeAllForms()
        {
            global $wpdb;
            $wpdb->query("TRUNCATE TABLE " . $wpdb->prefix.self::table_name);
            return true;
        }

        /** create default form */
        public static function createDefaultForm()
        {

            $formData = self::getDefaultForm();
            $html = $formData['html'];
            $css = $formData['css'];
            $list = maybe_serialize(array(SIB_API_Manager::get_default_list_id()));
            $current_date = date('Y-m-d');
            $attributes = 'email,NAME';
            // insert
            global $wpdb;
            $query = 'INSERT INTO ' . $wpdb->prefix.self::table_name . ' ';
            $query .= '(title,html,css,listID,dependTheme,successMsg,errorMsg,existMsg,invalidMsg,attributes,date,isDefault) ';
            $query .= "VALUES ('Default Form','{$html}','{$css}','{$list}','1','{$formData['successMsg']}','{$formData['errorMsg']}','{$formData['existMsg']}','{$formData['invalidMsg']}','{$attributes}','{$current_date}','1')";
            $wpdb->query($query);
        }

        /** get default form data */
        public static function getDefaultForm()
        {
            $html = <<<EOD
<p class="sib-email-area">
    <label class="sib-email-area">Email Address*</label>
    <input type="email" class="sib-email-area" name="email" required="required">
</p>
<p class="sib-NAME-area">
    <label class="sib-NAME-area">Name</label>
    <input type="text" class="sib-NAME-area" name="NAME" >
</p>
<p>
    <input type="submit" class="sib-default-btn" value="Subscribe">
</p>
EOD;
            $css = <<<EOD
[form] {
    padding: 5px;
    -moz-box-sizing:border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
[form] input[type=text],[form] input[type=email], [form] select {
    width: 100%;
    border: 1px solid #bbb;
    height: auto;
    margin: 5px 0 0 0;
}
[form] .sib-default-btn {
    margin: 5px 0;
    padding: 6px 12px;
    color:#fff;
    background-color: #333;
    border-color: #2E2E2E;
    font-size: 14px;
    font-weight:400;
    line-height: 1.4285;
    text-align: center;
    cursor: pointer;
    vertical-align: middle;
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
    white-space: normal;
    border:1px solid transparent;
    border-radius: 3px;
}
[form] .sib-default-btn:hover {
    background-color: #444;
}
[form] p{
    margin: 10px 0 0 0;
}
EOD;

            $result = array(
                'html' => $html,
                'css' => $css,
                'successMsg' => esc_attr(__('Thank you, you have successfully registered !', 'sib_lang')),
                'errorMsg' => esc_attr(__('Something wrong occured', 'sib_lang')),
                'existMsg' => esc_attr(__('You have already registered', 'sib_lang')),
                'invalidMsg' => esc_attr(__('Your email address is invalid', 'sib_lang'))
            );
            return $result;
        }

        public static function getDefaultMessageCss()
        {
            $css = <<<EOD
[form] p.sib-alert-message {
    padding: 6px 12px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
[form] p.sib-alert-message-error {
    background-color: #f2dede;
    border-color: #ebccd1;
    color: #a94442;
}
[form] p.sib-alert-message-success {
    background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;
}
[form] p.sib-alert-message-warning {
    background-color: #fcf8e3;
    border-color: #faebcc;
    color: #8a6d3b;
}
EOD;
            return $css;

        }

        /**
         * Get form data of old version
         * We suppose that the clients have got own setting values for form.
         * If the client have default setting only then it will be return error.
         * This function will be removed after next version
         */
        public static function get_old_form(){

            // create form from old version
            $form_settings = get_option('sib_subscription_option');
            $html = $form_settings['sib_form_html'];
            $avail_atts = $form_settings['available_attributes'];

            $signup_settings = get_option('sib_signup_option');
            $is_confirm_email = $signup_settings['is_confirm_email'] == 'yes' ? 1 : 0;
            $is_double_optin = $signup_settings['is_double_optin'] == 'yes' ? 1 : 0;
            $redirect_url = $signup_settings['redirect_url'];
            $redirect_url_click = $signup_settings['redirect_url_click'];
            $template_id = $is_confirm_email == 1 ? $signup_settings['template_id'] : $signup_settings['doubleoptin_template_id'];

            $confirmMsg = get_option('sib_confirm_option');

            $homeSetting = get_option('sib_home_option');
            $sib_list = maybe_serialize(array((string)$homeSetting['list_id']));
            //(title,html,css,dependTheme,listID,templateID,isOpt,isDopt,redirectInEmail,redirectInForm,successMsg,errorMsg,existMsg,invalidMsg,attributes,date)
            $formData = array(
                'title' => 'Old Form',
                'html' => $html,
                'css' => '',
                'dependTheme' => '1',
                'listID' => $sib_list,
                'templateID' => $template_id,
                'isOpt' => $is_confirm_email,
                'isDopt' => $is_double_optin,
                'redirectInEmail' => $redirect_url,
                'redirectInForm' => $redirect_url_click,
                'successMsg' => $confirmMsg['alert_success_message'],
                'errorMsg'  => $confirmMsg['alert_error_message'],
                'existMsg' => $confirmMsg['alert_exist_subscriber'],
                'invalidMsg' => $confirmMsg['alert_invalid_email'],
                'attributes' => 'email,'.implode(',', $avail_atts),
            );

            return $formData;
        }

        /** add prefix to the table */
        public static function add_prefix()
        {
            global $wpdb;
            if($wpdb->get_var("SHOW TABLES LIKE '".self::table_name."'") == self::table_name) {
                $query = "ALTER TABLE ".self::table_name." RENAME TO ".$wpdb->prefix.self::table_name.";";
                $wpdb->query($query);
            }
        }

        /**
         * add new column for terms and condition
        */
        public static function addTermsColumn()
        {
            global $wpdb;
            // add columns -termAccept, termsURL
            $check_query= "SHOW COLUMNS FROM `"  . $wpdb->prefix.self::table_name . "` LIKE 'termAccept';";
            $result = $wpdb->query($check_query);
            if(empty($result))
            {
                $alter_query = "ALTER TABLE "  . $wpdb->prefix.self::table_name . "
                            ADD COLUMN termAccept int(1) not NULL DEFAULT 1,
                             ADD COLUMN termsURL varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci";
                $ret = $wpdb->query($alter_query);
            }

        }

    }
}