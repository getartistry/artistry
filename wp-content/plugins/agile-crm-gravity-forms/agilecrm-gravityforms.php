<?php

/*
  Plugin Name: Gravity Forms Agile CRM Add-On
  Plugin URI: https://www.agilecrm.com/gravity-forms
  Description: Agile CRM integration plugin for gravity forms. Sync form entries to Agile easily.
  Version: 1.0
  Requires at least: 4.0
  Tested up to: 4.9
  Author: Agile CRM
  Author URI: https://www.agilecrm.com
 */

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

if (class_exists("GFForms") && !class_exists('AgileGFAddon')) {

    class AgileGFAddon
    {

        protected $tag = 'agile-gf-addon';
        private $account_settings_tab = 'account';
        private $form_settings_tab = 'form';
        private $plugin_settings_tabs = array();
        protected $name = 'Gravity Forms Agile CRM Add-On';
        protected $version = '1.0';

        function __construct()
        {
            //register actions or hooks
            add_action('init', array(&$this, 'start_session'));
            add_action('wp_footer', array(&$this, 'set_email'), 98765);

            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));

            add_action('gform_after_submission', array(&$this, 'sync_entries_to_agile'), 10, 2);

            add_action('wp_ajax_agilecrm_gf_load_fields', array(&$this, 'load_form_fields'));
            add_action('wp_ajax_agilecrm_gf_map_fields', array(&$this, 'map_form_fields'));
			
        }

        /**
         * Start PHP session if not started earlier
         */
        public function start_session()
        {
            if (!session_id()) {
                session_start();
            }
        }

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // Set up the settings for this plugin
            $this->init_settings();
            $this->plugin_settings_tabs[$this->account_settings_tab] = 'Account Details';
            $this->plugin_settings_tabs[$this->form_settings_tab] = 'Form Settings';
        }

        /**
         * Initialize some custom settings
         */
        public function init_settings()
        {
            // register the settings for this plugin
            register_setting($this->tag . '-settings-group', 'agilecrm_gf_domain');
            register_setting($this->tag . '-settings-group', 'agilecrm_gf_admin_email');
            register_setting($this->tag . '-settings-group', 'agilecrm_gf_api_key');

            register_setting($this->tag . '-settings-group1', 'agilecrm_gf_form_map');
            register_setting($this->tag . '-settings-group2', 'agilecrm_gf_contact_fields');
            register_setting($this->tag . '-settings-group3', 'agilecrm_gf_mapped_forms');

            add_settings_section($this->tag . '-section-one', '', '', $this->tag);
        }

        /**
         * add a menu
         */
        public function add_menu()
        {
            add_options_page('Settings-' . $this->name, 'Agile Gravity Forms', 'manage_options', $this->tag, array(&$this, 'plugin_settings_page'));
        }

        /**
         * Generate plugin setting tabs
         */
        public function plugin_settings_tabs()
        {
            $current_tab = (isset($_GET['tab']) && isset($this->plugin_settings_tabs[$_GET['tab']])) ? $_GET['tab'] : $this->account_settings_tab;

            echo '<h2 class="nav-tab-wrapper">';
            foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
                $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
                echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->tag . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
            }
            echo '</h2>';
        }

        /**
         * Menu Callback
         */
        public function plugin_settings_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template based on the tab selected
            $current_tab = (isset($_GET['tab']) && isset($this->plugin_settings_tabs[$_GET['tab']])) ? $_GET['tab'] : $this->account_settings_tab;
            include(sprintf("%s/templates/" . $current_tab . "-tab.php", dirname(__FILE__)));
        }

        /**
         * Load form fields related to form id through Ajax
         */
        public function load_form_fields()
        {
            global $wpdb;
            $formId = $_POST['formid'];
            $form = RGFormsModel::get_form_meta($formId);
            $formFieldsOptions = '<option value="" selected="selected"></option>';

            if (is_array($form["fields"])) {
                foreach ($form["fields"] as $field) {
                    if (isset($field["inputs"]) && is_array($field["inputs"])) {
                        foreach ($field["inputs"] as $input) {
                            $formFieldsOptions .= '<option value="' . $input["id"] . '">' . GFCommon::get_label($field, $input["id"]) . '</option>';
                        }
                    } else if (!rgar($field, 'displayOnly')) {
                        $formFieldsOptions .= '<option value="' . $field["id"] . '">' . GFCommon::get_label($field) . '</option>';
                    }
                }
            }

            $agileFields = array(
                'first_name' => array('name' => 'First name', 'is_required' => true, 'type' => 'SYSTEM', 'is_address' => false),
                'last_name' => array('name' => 'Last name', 'is_required' => true, 'type' => 'SYSTEM', 'is_address' => false),
                'company' => array('name' => 'Company', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => false),
                'title' => array('name' => 'Job description', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => false),
                'tags' => array('name' => 'Tag', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => false),
                'email' => array('name' => 'Email', 'is_required' => true, 'type' => 'SYSTEM', 'is_address' => false),
                'phone' => array('name' => 'Phone', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => false),
                'website' => array('name' => 'Website', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => false),
                'address_address' => array('name' => 'Address', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => true),
                'address_city' => array('name' => 'City', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => true),
                'address_state' => array('name' => 'State', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => true),
                'address_zip' => array('name' => 'Zip', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => true),
                'address_country' => array('name' => 'Country', 'is_required' => false, 'type' => 'SYSTEM', 'is_address' => true)
            );

            $agile_domain = get_option('agilecrm_gf_domain');
            $agile_email = get_option('agilecrm_gf_admin_email');
            $agile_api_key = get_option('agilecrm_gf_api_key');

            $agile_url = "https://" .$agile_domain. ".agilecrm.com/dev/api/";
            $headers = array(
                        'Authorization' => 'Basic ' . base64_encode( $agile_email. ':' .$agile_api_key ),
                        'Content-type' => 'application/json',
                        'Accept' => 'application/json'
                        );

            $args = array(
                    'timeout' => 120,
                    'sslverify'   => false,
                    'headers' => $headers
                     );

            $request = wp_remote_get($agile_url.'custom-fields/scope?scope=CONTACT',$args);
            $customFields = wp_remote_retrieve_body( $request );

            //$customFields = $this->agile_http("custom-fields/scope?scope=CONTACT", null, "GET");

            if ($customFields) {
                $customFields = json_decode($customFields, true);
                foreach ($customFields as $customField) {
                    $agileFields[AgileGFAddon::clean($customField['field_label'])] = array(
                        'name' => $customField['field_label'],
                        'is_required' => (boolean) $customField['is_required'],
                        'type' => 'CUSTOM',
                        'is_address' => false
                    );
                }
            }

            update_option("agilecrm_gf_contact_fields", $agileFields);


            $mapFieldsMarkup = '';
            foreach ($agileFields as $fieldKey => $fieldVal) {
                $mapFieldsMarkup .= '<tr valign="top"><th scope="row">' . $fieldVal['name'];
                $required = '';
                if ($fieldVal['is_required']) {
                    $mapFieldsMarkup .= '<span style="color:#FF0000"> *</span>';
                    $required = 'class="required" required';
                }
                $mapFieldsMarkup .= '</th>';
                $mapFieldsMarkup .= '<td><select id="agilecrm_form_field_' . $fieldKey . '" name="agilecrm_gf_form_map[' . $fieldKey . ']"' . $required . '>' . $formFieldsOptions . '</select></td></tr>';
            }

            $agilecrm_gf_form_map = get_option('agilecrm_gf_form_map');

            $responseJson = array(
                'markup' => '',
                'selectedFields' => ($agilecrm_gf_form_map && isset($agilecrm_gf_form_map['form_' . $formId])) ? $agilecrm_gf_form_map['form_' . $formId] : array()
            );

            $responseJson['markup'] .= '<h3 class="title">Map Gravity form fields to Agile CRM contact properties</h3>';

            $responseJson['markup'] .= '<table class="form-table" style="width:33%"><tbody>';
            $responseJson['markup'] .= '<tr valign="top"><th scope="row">Agile property</th><td><strong>Form field</strong></td></tr>';
            $responseJson['markup'] .= $mapFieldsMarkup;
            $responseJson['markup'] .= '</tbody></table>';

            $responseJson['markup'] .= '<h3>Add a tag to all contacts created from this form</h3>';
            $responseJson['markup'] .= '<table class="form-table"><tbody><tr valign="top">'
                    . '<th scope="row" style="width: 136px;">Tag</th>'
                    . '<td><input type="text" name="agilecrm_gf_form_map[hard_tag]" id="agilecrm_form_field_hard_tag"><br>'
                    . '<small>Tag name can not have special characters except space and underscore.</small></td>'
                    . '</tr></tbody></table>';

            echo json_encode($responseJson);
            die();
        }

        /**
         * Save form mapped fields to database via Ajax
         */
        public function map_form_fields()
        {
            global $wpdb;
            $agilecrm_gf_form_map = get_option('agilecrm_gf_form_map');
            $agilecrm_form_sync_id = $_POST['agilecrm_gf_sync_form'];

            //save checked forms ids  
            $agilecrm_gf_mapped_forms = get_option('agilecrm_gf_mapped_forms');
            if (isset($_POST['agilecrm_gf_mapped_forms']) && check_admin_referer( 'agilecrm_gf_form_nonce_action', 'agilecrm_gf_form_nonce_field' )) {
                $syncedForms = $_POST['agilecrm_gf_mapped_forms'];
                if (in_array($agilecrm_form_sync_id, $agilecrm_gf_mapped_forms)) {
                    $syncedForms = array();
                }
                if ($agilecrm_gf_mapped_forms != false) {
                    $syncedForms = array_merge($agilecrm_gf_mapped_forms, $syncedForms);
                }
            } else {
                $syncedForms = $agilecrm_gf_mapped_forms;
                if (($key = array_search($agilecrm_form_sync_id, $syncedForms)) !== false) {
                    unset($syncedForms[$key]);
                }
            }
            $update = update_option('agilecrm_gf_mapped_forms', $syncedForms);

            if (isset($_POST['agilecrm_gf_form_map']) && check_admin_referer( 'agilecrm_gf_form_nonce_action', 'agilecrm_gf_form_nonce_field' )) {
                $formFields['form_' . $agilecrm_form_sync_id] = $_POST['agilecrm_gf_form_map'];
                if ($agilecrm_gf_form_map != false) {
                    $formFields = array_merge($agilecrm_gf_form_map, $formFields);
                }

                if (isset($formFields['form_' . $agilecrm_form_sync_id]['hard_tag']) && $formFields['form_' . $agilecrm_form_sync_id]['hard_tag'] != '') {
                    $formFields['form_' . $agilecrm_form_sync_id]['hard_tag'] = mb_ereg_replace('[^ \w]+', '', $formFields['form_' . $agilecrm_form_sync_id]['hard_tag']);
                    $formFields['form_' . $agilecrm_form_sync_id]['hard_tag'] = preg_replace('!\s+!', ' ', $formFields['form_' . $agilecrm_form_sync_id]['hard_tag']);
                }

                $update = update_option('agilecrm_gf_form_map', $formFields);
            }

            echo ($update) ? '1' : '0';

            die();
        }

        /**
         * Syncs form entries to Agile CRM whenever a mapped form is submited.
         */
        public function sync_entries_to_agile($entry, $form)
        {
            $agilecrm_gf_form_map = get_option('agilecrm_gf_form_map');
            $agilecrm_gf_mapped_forms = get_option('agilecrm_gf_mapped_forms');

            $formId = $entry['form_id'];
            if ($formId) {
                if ($agilecrm_gf_mapped_forms && in_array($formId, $agilecrm_gf_mapped_forms)) {
                    if ($agilecrm_gf_form_map && isset($agilecrm_gf_form_map['form_' . $formId])) {

                        $agileFields = get_option('agilecrm_gf_contact_fields');
                        $mappedFields = $agilecrm_gf_form_map['form_' . $formId];
                        $contactProperties = array();
                        $addressProp = array();

                        foreach ($agileFields as $fieldKey => $fieldVal) {
                            if ($mappedFields[$fieldKey] != '') {
                                if ($fieldVal['type'] == 'CUSTOM') {
                                    if (DateTime::createFromFormat('Y-m-d', $entry[$mappedFields[$fieldKey]]) !== FALSE) {
                                        $date  = $entry[$mappedFields[$fieldKey]];
                                        $epochtime = strtotime($date);
                                        $contactProperties[] = array(
                                            "name" => $fieldVal['name'],
                                            "value" => $epochtime,
                                            "type" => $fieldVal['type']
                                        );                                                                          
                                    }
                                    else{
                                        $contactProperties[] = array(
                                            "name" => $fieldVal['name'],
                                            "value" => $entry[$mappedFields[$fieldKey]],
                                            "type" => $fieldVal['type']
                                        );
                                    }                                    
                                } elseif ($fieldVal['type'] == 'SYSTEM') {
                                    if ($fieldVal['is_address']) {
                                        $addressField = explode("_", $fieldKey);
                                        $addressProp[$addressField[1]] = $entry[$mappedFields[$fieldKey]];
                                    } else {
                                        if ($fieldKey != 'tags') {
                                            $contactProperties[] = array(
                                                "name" => $fieldKey,
                                                "value" => $entry[$mappedFields[$fieldKey]],
                                                "type" => $fieldVal['type']
                                            );
                                        }
                                    }
                                }
                            }
                        }

                        if ($addressProp) {
                            $contactProperties[] = array(
                                "name" => "address",
                                "value" => json_encode($addressProp),
                                "type" => "SYSTEM"
                            );
                        }

                        $finalData = array("properties" => $contactProperties);

                        //tags
                        $finalData['tags'] = array();

                        if ($mappedFields["tags"] != '') {
                            $finalData['tags'][] = preg_replace('!\s+!', ' ', mb_ereg_replace('[^ \w]+', '', $entry[$mappedFields['tags']]));
                        }
                        if ($mappedFields["hard_tag"] != '') {
                            $finalData['tags'][] = $mappedFields['hard_tag'];
                        }						                 					
						//for web tracking
                        if (isset($entry[$mappedFields['email']]) && $entry[$mappedFields['email']] != '') {
                            $_SESSION['agileCRMTrackEmail'] = $entry[$mappedFields['email']];
                        }

                        $agile_domain = get_option('agilecrm_gf_domain');
                        $agile_email = get_option('agilecrm_gf_admin_email');
                        $agile_api_key = get_option('agilecrm_gf_api_key');

                        $agile_url = "https://" .$agile_domain. ".agilecrm.com/dev/api/";
                        $headers = array(
                                    'Authorization' => 'Basic ' . base64_encode( $agile_email. ':' .$agile_api_key ),
                                    'Content-type' => 'application/json',
                                    'Accept' => 'application/json'
                                    );

                        $args_post = array(
                                'method' => 'POST',
                                'timeout' => 120,
                                'sslverify'   => false,
                                'headers' => $headers,
                                'body' => json_encode($finalData)
                                 );

                        wp_remote_post($agile_url.'contacts',$args_post);                    

						//$this->agile_http("contacts", json_encode($finalData), "POST");

                        $args_get = array(
                                        'timeout' => 120,
                                        'sslverify'   => false,
                                        'headers' => $headers
                                        );

                        $request = wp_remote_get($agile_url.'contacts/search/email/'.$entry[$mappedFields['email']],$args_get);
                        //$result = $this->agile_http("contacts/search/email/".$entry[$mappedFields['email']], null, "GET");
                        $result = wp_remote_retrieve_body( $request );                        
                        $result = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);

                        if(count($result)>0)
                            $contact_id = $result->id;

                        $contact_json = array(
                                            "id" => $contact_id, //It is mandatory field. Id of contact
                                           "tags" => $finalData['tags']
                                        );

                        $contact_json = json_encode($contact_json);

                        $args_put = array(
                                'method' => 'PUT',
                                'timeout' => 120,
                                'sslverify'   => false,
                                'headers' => $headers,
                                'body' => $contact_json
                                 );

                        $response = wp_remote_request($agile_url.'contacts/edit/tags',$args_put);
                        //$this->agile_http("contacts/edit/tags", $contact_json, "PUT");

                    }
                }
            }
        }

        /**
         * Set user entered email to track web activities
         */
        public function set_email()
        {	 
            if (isset($_SESSION['agileCRMTrackEmail'])) {
                echo '<script> ';
                echo 'if(typeof _agile != "undefined") { ';
                echo '_agile.set_email("' . $_SESSION['agileCRMTrackEmail'] . '");';
                echo ' }';
                echo ' </script>';
                unset($_SESSION['agileCRMTrackEmail']);
            }
        }

        /**
         * AgileCRM Request Wrapper function
         */
        public function agile_http($endPoint, $data, $requestMethod)
        {
            $agile_domain = get_option('agilecrm_gf_domain');
            $agile_email = get_option('agilecrm_gf_admin_email');
            $agile_api_key = get_option('agilecrm_gf_api_key');

            if ($agile_domain && $agile_email && $agile_api_key) {
                $agile_url = "https://" . $agile_domain . ".agilecrm.com/dev/api/";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);

                switch ($requestMethod) {
                    case "POST":
                        curl_setopt($ch, CURLOPT_URL, $agile_url . $endPoint);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        break;
                    case "GET":
                        curl_setopt($ch, CURLOPT_URL, $agile_url . $endPoint);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                        break;
                    case "PUT":
                        curl_setopt($ch, CURLOPT_URL, $agile_url . $endPoint);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        break;
                    case "DELETE":
                        curl_setopt($ch, CURLOPT_URL, $agile_url . $endPoint);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                        break;
                    default:
                        break;
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type : application/json; charset : UTF-8;', 'Accept: application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERPWD, $agile_email . ':' . $agile_api_key);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);

                $output = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($statusCode == 200) {
                    return $output;
                } elseif ($statusCode == 401) {
                    return false;
                }
            }

            return false;
        }

        /**
         * Sanitize custom field names, return value is used as a key.
         */
        public static function clean($string)
        {
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        }

    }

    //class end

    new AgileGFAddon();
}