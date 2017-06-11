<?php

/**
 * Admin page : dashboard
 * @package SIB_Page_Home
 */

/**
 * Page class that handles backend page <i>dashboard ( for admin )</i> with form generation and processing
 * @package SIB_Page_Home
 */

if(!class_exists('SIB_Page_Home'))
{
    class SIB_Page_Home
    {
        /**
         * Page slug
         */
        const page_id = 'sib_page_home';

        /**
         * Page hook
         * @var string
         */
        protected $page_hook;

        /**
         * page tabs
         * @var mixed
         */
        protected $tabs;

        /**
         * Constructs new page object and adds entry to Wordpress admin menu
         */
        function __construct()
        {
            add_menu_page(__('SendinBlue', 'sib_lang'), __('SendinBlue', 'sib_lang'), 'manage_options', self::page_id, array(&$this, 'generate'), SIB_Manager::$plugin_url . '/img/favicon.ico');
            $this->page_hook = add_submenu_page(self::page_id, __('Home', 'sib_lang'), __('Home', 'sib_lang'), 'manage_options', self::page_id, array(&$this, 'generate'));
            add_action('load-'.$this->page_hook, array(&$this, 'init'));
            add_action( 'admin_print_scripts-' . $this->page_hook, array($this, 'enqueue_scripts'));
            add_action( 'admin_print_styles-' . $this->page_hook, array($this, 'enqueue_styles'));
        }

        /**
         * Init Process
         */
        function Init()
        {
            if((isset($_GET['sib_action'])) && ($_GET['sib_action'] == 'logout')) {
                $this->logout();
            }
        }

        /**
         * enqueue scripts of plugin
         */
        function enqueue_scripts()
        {
            wp_enqueue_script('sib-admin-js');
            wp_enqueue_script('sib-bootstrap-js');
            wp_enqueue_script('sib-chosen-js');
            wp_localize_script('sib-admin-js', 'ajax_sib_object',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
        }

        /**
         * enqueue style sheets of plugin
         */
        function enqueue_styles()
        {
            wp_enqueue_style('sib-admin-css');
            wp_enqueue_style('sib-bootstrap-css');
            wp_enqueue_style('sib-chosen-css');
            wp_enqueue_style('sib-fontawesome-css');
        }

        /** generate page script */
        function generate()
        {
            ?>
            <div id="wrap" class="box-border-box container-fluid">
                <h2><img id="logo-img" src="<?php echo SIB_Manager::$plugin_url . '/img/logo.png'; ?>"></h2>
                <div id="wrap-left" class="box-border-box col-md-9">
                <?php
                if(SIB_Manager::is_done_validation() == true) {
                    $this->generate_main_content();
                } else {
                    $this->generate_welcome_content();
                }
                ?>
                </div>
                <div id="wrap-right-side" class="box-border-box  col-md-3">
                    <?php
                    self::generate_side_bar();
                    ?>
                </div>
            </div>
            <?php
        }

        /** generate welcome page before validation */
        function generate_welcome_content()
        {
        ?>

            <div id="main-content" class="sib-content">
                <input type="hidden" id="cur_refer_url" value="<?php echo add_query_arg(array('page' => 'sib_page_home'), admin_url('admin.php')); ?>">
                <div class="panel panel-default row small-content">
                    <div class="page-header">
                        <span style="color: #777777;"><?php _e('Step', 'sib_lang'); ?> 1&nbsp;|&nbsp;</span><strong><?php _e('Create a SendinBlue Account', 'sib_lang'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-9 row">
                            <p><?php _e('By creating a free SendinBlue account, you will be able to send confirmation emails and:', 'sib_lang'); ?></p>
                            <ul class="sib-home-feature">
                                <li><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Collect your contacts and upload your lists', 'sib_lang'); ?></li>
                                <li><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Use SendinBlue SMTP to send your transactional emails', 'sib_lang'); ?></li>
                                <li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Email marketing builders', 'sib_lang'); ?></li>
                                <li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Create and schedule your email marketing campaigns', 'sib_lang'); ?></li>
                                <li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Try all of', 'sib_lang'); ?>&nbsp;<a href="https://www.sendinblue.com/features/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><?php _e('SendinBlue\'s features', 'sib_lang'); ?></a></li>
                            </ul>
                            <a href="https://www.sendinblue.com/users/signup?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" class="btn btn-primary" target="_blank" style="margin-top: 10px;"><?php _e('Create an account', 'sib_lang'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default row small-content">
                    <div class="page-header">
                        <span style="color: #777777;"><?php _e('Step', 'sib_lang'); ?> 2&nbsp;|&nbsp;</span><strong><?php _e('Activate your account with your API key', 'sib_lang'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-9 row">
                            <div id="success-alert" class="alert alert-success" role="alert" style="display: none;"><?php _e('You successfully activate your account.', 'sib_lang');?></div>
                            <input type="hidden" id="general_error" value="<?php _e('Please input correct information.', 'sib_lang');?>">
                            <input type="hidden" id="curl_no_exist_error" value="<?php _e('Please install curl on site to use sendinblue plugin.', 'sib_lang');?>">
                            <input type="hidden" id="curl_error" value="<?php _e('Curl error.', 'sib_lang');?>">
                            <div id="failure-alert" class="alert alert-danger" role="alert" style="display: none;"><?php _e('Please input correct information.', 'sib_lang');?></div>
                            <p>
                                <?php _e('Once you have created a SendinBlue account, activate this plugin to send all of your transactional emails via SendinBlue SMTP. SendinBlue optimizes email delivery to ensure emails reach the inbox.', 'sib_lang'); ?><br>
                                <?php _e('To activate your plugin, enter your API Access key.', 'sib_lang'); ?><br>
                            </p>
                            <p>
                                <a href="https://my.sendinblue.com/advanced/apikey/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Get your API key from your account', 'sib_lang'); ?></a>
                            </p>
                            <p>
                                <div class="col-md-7 row">
                                    <p class="col-md-12 row"><input id="sib_access_key" type="text" class="col-md-10" style="margin-top: 10px;" placeholder="<?php _e('Access Key', 'sib_lang'); ?>"></p>
                                    <p class="col-md-12 row"><button type="button" id="sib_validate_btn" class="col-md-4 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php _e('Login', 'sib_lang'); ?></button></p>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        /** generate main home page after validation */
        function generate_main_content()
        {
            $total_subscribers = SIB_API_Manager::get_totalusers();

            // get campaigns
            $campaign_stat = SIB_API_Manager::get_campaign_stats();

            // display account info
            $account_settings = SIB_API_Manager::get_account_info();
            $account_email = isset($account_settings['account_email']) ? $account_settings['account_email'] : '';
            $account_user_name = isset($account_settings['account_user_name']) ? $account_settings['account_user_name'] : '';
            $account_data = isset($account_settings['account_data']) ? $account_settings['account_data'] : '';

            // check smtp available
            $smtp_status = SIB_API_Manager::get_smtp_status();

            $home_settings = get_option(SIB_Manager::home_option_name);
            // for upgrade to 2.6.0 from old version
            if(!isset($home_settings['activate_ma']))
                $home_settings['activate_ma'] = 'no';
            // set default sender info
            $senders = SIB_API_Manager::get_sender_lists();
            if(!isset($home_settings['sender']) && SIB_Manager::is_done_validation() && is_array($senders)){
                $home_settings['sender'] = $senders[0]['id'];
                $home_settings['from_name'] = $senders[0]['from_name'];
                $home_settings['from_email'] = $senders[0]['from_email'];
                update_option(SIB_Manager::home_option_name, $home_settings);
            }
            
            // Users Sync part
            $currentUsers = count_users();
            $isSynced = get_option('sib_sync_users', '0');
            $isEnableSync = '0';
            if ($isSynced != $currentUsers) {
                $isEnableSync = '1';
                $desc = sprintf(__('You have %s existing users. Do you want to add them to SendinBlue?', 'sib_lang'), $currentUsers['total_users'] );
            }else{
                $desc = __('All your users have been added to a SendinBlue list.','sib_lang');
            }
            self::print_sync_popup();
        ?>

            <div id="main-content" class="sib-content">
                <input type="hidden" id="cur_refer_url" value="<?php echo add_query_arg(array('page' => 'sib_page_home'), admin_url('admin.php')); ?>">
                <!-- Account Info -->
                <div class="panel panel-default row small-content">
                    <div class="page-header">
                        <strong><?php _e('My Account', 'sib_lang'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <span class="col-md-12"><b><?php _e('You are currently logged in as : ', 'sib_lang'); ?></b></span>
                        <div class="col-md-8 row" style="margin-bottom: 10px;">
                            <p class="col-md-12" style="margin-top: 5px;">
                                <?php echo $account_user_name; ?>&nbsp;-&nbsp;<?php echo $account_email; ?><br>
                                <?php
                                $count = count($account_data);
                                for($i = 0; $i < $count - 1; $i ++)
                                {
                                    echo $account_data[$i]['plan_type'] . ' - ' . $account_data[$i]['credits'] . ' ' .  __('credits', 'sib_lang') . '<br>';
                                }
                                ?>
                                <a href="<?php echo esc_url(add_query_arg('sib_action', 'logout')); ?>"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Log out', 'sib_lang'); ?></a>
                            </p>
                        </div>

                        <span class="col-md-12"><b><?php _e('Contacts', 'sib_lang'); ?></b></span>
                        <div class="col-md-12 row" style="padding-top: 10px;">
                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <p style="margin-top: 5px;">
                                    <?php echo __('You have', 'sib_lang') .' <span id="sib_total_contacts">'.$total_subscribers.'</span> '. __('contacts.', 'sib_lang'); ?><br>
                                    <a id="sib_list_link" href="https://my.sendinblue.com/users/list/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Access to the list of all my contacts', 'sib_lang'); ?></a>
                                </p>
                            </div>

                            <div class="col-md-6 row" style="margin-bottom: 10px;">
                                <p class="col-md-8" style="margin-top: 5px;">
                                    <b><?php echo __('Users Synchronisation', 'sib_lang'); ?></b><br>
                                    <?php echo $desc; ?><br>
                                </p>
                                <div class="col-md-4">
                                    <a <?php echo $isEnableSync == '1' ? '':'disabled'; ?> id="sib-sync-btn" class="btn btn-primary" style="margin-top: 28px; " name="<?php echo __('Users Synchronisation', 'sib_lang'); ?>" href="#"><?php _e('Sync my users', 'sib_lang'); ?></a>
                                </div>

                            </div>
                        </div>

                        <span class="col-md-12"><b><?php _e('Campaigns', 'sib_lang'); ?></b></span>
                        <div class="col-md-12 row" style="padding-top: 10px;">
                            <div class="col-md-4">
                                <span style="line-height: 200%;">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <?php _e('Email Campaigns', 'sib_lang'); ?>
                                </span>
                                <div class="list-group" id="list-group-email-campaign">
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'sent_c'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['classic']['Sent']; ?></span>
                                        <span class="glyphicon glyphicon-send"></span>
                                        <?php _e('Sent', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'draft_c'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['classic']['Draft']; ?></span>
                                        <span class="glyphicon glyphicon-edit"></span>
                                        <?php _e('Draft', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'submitted_c'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['classic']['Queued']; ?></span>
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        <?php _e('Scheduled', 'sib_lang'); ?>
                                    </a>
                                    <div class="list-group-item">
                                        <a href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'create', 'type' => 'classic'), admin_url('admin.php')); ?>"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Create new email campaign', 'sib_lang'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span style="line-height: 200%;">
                                    <span class="glyphicon glyphicon-phone"></span>
                                    <?php _e('SMS Campaigns', 'sib_lang'); ?>
                                </span>
                                <div class="list-group" id="list-group-email-campaign">
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'sent_s'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['sms']['Sent']; ?></span>
                                        <span class="glyphicon glyphicon-send"></span>
                                        <?php _e('Sent', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'draft_s'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['sms']['Draft']; ?></span>
                                        <span class="glyphicon glyphicon-edit"></span>
                                        <?php _e('Draft', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'submitted_s'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['sms']['Queued']; ?></span>
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        <?php _e('Scheduled', 'sib_lang'); ?>
                                    </a>
                                    <div class="list-group-item">
                                        <a href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'create', 'type' => 'sms'), admin_url('admin.php')); ?>"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Create new sms campaign', 'sib_lang'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span style="line-height: 200%;">
                                    <span class="glyphicon glyphicon-play-circle"></span>
                                    <?php _e('Trigger Marketing', 'sib_lang'); ?>
                                </span>
                                <div class="list-group" id="list-group-email-campaign">
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'sent_t'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['trigger']['Sent']; ?></span>
                                        <span class="glyphicon glyphicon-send"></span>
                                        <?php _e('Sent', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'draft_t'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['trigger']['Draft']; ?></span>
                                        <span class="glyphicon glyphicon-edit"></span>
                                        <?php _e('Draft', 'sib_lang'); ?>
                                    </a>
                                    <a class="list-group-item" href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'submitted_t'), admin_url('admin.php')); ?>">
                                        <span class="badge"><?php echo $campaign_stat['trigger']['Queued']; ?></span>
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        <?php _e('Scheduled', 'sib_lang'); ?>
                                    </a>
                                    <div class="list-group-item">
                                        <a href="<?php echo add_query_arg(array('page' => 'sib_page_campaigns', 'sort' => 'create', 'type' => 'trigger'), admin_url('admin.php')); ?>"><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Create new trigger campaign', 'sib_lang'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Transactional Email -->
                <div class="panel panel-default row small-content">
                    <div class="page-header">
                        <strong><?php _e('Transactional emails', 'sib_lang'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <?php
                        if($smtp_status == 'disabled') :
                            ?>
                            <div id="smtp-failure-alert" class="col-md-12 sib_alert alert alert-danger" role="alert"><?php _e('Unfortunately, your "Transactional emails" are not activated because your SendinBlue SMTP account is not active. Please send an email to contact@sendinblue.com in order to ask for SMTP account activation', 'sib_lang');?></div>
                            <?php
                        endif;
                        ?>
                        <div id="success-alert" class="col-md-12 sib_alert alert alert-success" role="alert" style="display: none;"><?php _e('Mail Sent.', 'sib_lang');?></div>
                        <div id="failure-alert" class="col-md-12 sib_alert alert alert-danger" role="alert" style="display: none;"><?php _e('Please input valid email.', 'sib_lang');?></div>
                        <div class="row">
                            <p class="col-md-4 text-left"><?php _e('Activate email through SendinBlue', 'sib_lang'); ?></p>
                            <div class="col-md-3">
                                <label class="col-md-6"><input type="radio" name="activate_email" id="activate_email_radio_yes" value="yes" <?php checked($home_settings['activate_email'], 'yes');
                                    if($smtp_status == 'disabled') {
                                        echo ' disabled';
                                    }
                                    ?> >&nbsp;Yes</label>
                                <label class="col-md-6"><input type="radio" name="activate_email" id="activate_email_radio_no" value="no" <?php checked($home_settings['activate_email'], 'no'); ?>>&nbsp;No</label>
                            </div>
                            <div class="col-md-5">
                                <small style="font-style: italic;"><?php _e('Choose "Yes" if you want to use SendinBlue SMTP to send transactional emails', 'sib_lang'); ?></small>
                            </div>
                        </div>
                        <div class="row" id="email_send_field" <?php
                        if($home_settings['activate_email'] != 'yes') {
                            echo 'style="display:none;"';
                        }
                        ?>>
                            <div class="row" style="margin-left: 0px;margin-bottom: 10px;">
                                <p class="col-md-4 text-left"><?php _e('Choose your sender', 'sib_lang'); ?></p>
                                <div class="col-md-3">
                                    <select id="sender_list" class="col-md-12">
                                        <?php
                                        $senders = SIB_API_Manager::get_sender_lists();
                                        foreach($senders as $sender){
                                            echo "<option value='".$sender['id']."' ". selected( $home_settings['sender'], $sender['id'] ) .">".$sender['from_name']."&nbsp;&lt;".$sender['from_email']."&gt;</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <a href="https://my.sendinblue.com/users/settings/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" style="font-style: italic;" target="_blank" ><i class="fa fa-angle-right"></i>&nbsp;<?php _e('Create a new sender', 'sib_lang'); ?></a>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 0px;">
                                <p class="col-md-4 text-left"><?php _e('Enter email to send a test', 'sib_lang'); ?></p>
                                <div class="col-md-3">
                                    <input id="activate_email" type="email" class="col-md-12">
                                    <button type="button" id="send_email_btn" class="col-md-12 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php _e('Send email', 'sib_lang'); ?></button>
                                </div>
                                <div class="col-md-5">
                                    <small style="font-style: italic;"><?php _e('Select here the email address you want to send a test email to.', 'sib_lang'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Marketing Automation -->
                <div class="panel panel-default row small-content">
                    <div class="page-header">
                        <strong><?php _e('Automation', 'sib_lang'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <div class="sib-ma-alert sib-ma-active alert alert-success" role="alert" style="display: none;"><?php _e('Your Marketing Automation script is installed correctly.', 'sib_lang');?></div>
                        <div class="sib-ma-alert sib-ma-inactive alert alert-danger" role="alert" style="display: none;"><?php _e('Your Marketing Automation script has been uninstalled', 'sib_lang');?></div>
                        <div class="sib-ma-alert sib-ma-disabled alert alert-danger" role="alert" style="display: none;"><?php _e('To activate Marketing Automation (beta), please go to your SendinBlue\'s account or contact us at contact@sendinblue.com', 'sib_lang');?></div>
                        <input type="hidden" id="sib-ma-unistall" value="<?php _e('Your Marketing Automation script will be uninstalled, you won\'t have access to any Marketing Automation data and workflows', 'sib_lang'); ?>">
                        <div class="row">
                            <p class="col-md-4 text-left"><?php _e('Activate Marketing Automation through SendinBlue', 'sib_lang'); ?></p>
                            <div class="col-md-3">
                                <label class="col-md-6"><input type="radio" name="activate_ma" id="activate_ma_radio_yes" value="yes" <?php checked($home_settings['activate_ma'], 'yes');
                                    ?> >&nbsp;Yes</label>
                                <label class="col-md-6"><input type="radio" name="activate_ma" id="activate_ma_radio_no" value="no" <?php checked($home_settings['activate_ma'], 'no'); ?>>&nbsp;No</label>
                            </div>
                            <div class="col-md-5">
                                <small style="font-style: italic;"><?php _e('Choose "Yes" if you want to use SendinBlue Automation to track your website activity', 'sib_lang'); ?></small>
                            </div>
                        </div>
                        <div class="row" style="">
                            <p class="col-md-4 text-left" style="font-size: 13px; font-style: italic;"><?php printf(__('%s Explore our resource %s to learn more about SendinBlue Automation', 'sib_lang'), '<a href="https://help.sendinblue.com/hc/en-us/articles/208775609" target="_blank">', '</a>'); ?></p>
                            <div class="col-md-3">
                                <button type="button" id="validate_ma_btn" class="col-md-12 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php _e('Activate', 'sib_lang'); ?></button>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        <?php
        }

        public static function generate_side_bar()
        {
        ?>

            <div class="panel panel-default text-left box-border-box  small-content">
                <div class="panel-heading"><strong><?php _e('About SendinBlue', 'sib_lang'); ?></strong></div>
                <div class="panel-body">
                    <p><?php _e('SendinBlue is an online software that helps you build and grow relationships through marketing and transactional emails, marketing automation, and text messages.', 'sib_lang'); ?></p>
                    <ul class="sib-widget-menu">
                        <li>
                            <a href="https://www.sendinblue.com/about/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('Who we are', 'sib_lang'); ?></a>
                        </li>
                        <li>
                            <a href="https://www.sendinblue.com/pricing/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('Pricing', 'sib_lang'); ?></a>
                        </li>
                        <li>
                            <a href="https://www.sendinblue.com/features/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('Features', 'sib_lang'); ?></a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="panel panel-default text-left box-border-box  small-content">
                <div class="panel-heading"><strong><?php _e('Need Help?', 'sib_lang'); ?></strong></div>
                <div class="panel-body">
                    <p><?php _e('Do you have a question or need more information?', 'sib_lang'); ?></p>
                    <ul class="sib-widget-menu">
                        <li><a href="https://help.sendinblue.com/hc/en-us/sections/202171729" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('Tutorials', 'sib_lang'); ?></a></li>
                        <li><a href="https://resources.sendinblue.com/category/faq/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('FAQ', 'sib_lang'); ?></a></li>
                    </ul>
                    <hr>
                </div>
            </div>
            <div class="panel panel-default text-left box-border-box  small-content">
                <div class="panel-heading"><strong><?php _e('Recommend this plugin', 'sib_lang'); ?></strong></div>
                <div class="panel-body">
                    <p><?php _e('Let everyone know you like this plugin through a review!' ,'sib_lang'); ?></p>
                    <ul class="sib-widget-menu">
                        <li><a href="http://wordpress.org/support/view/plugin-reviews/mailin" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php _e('Recommend the SendinBlue plugin', 'sib_lang'); ?></a></li>
                    </ul>
                </div>
            </div>
        <?php
        }

        /** get narration script */
        static function get_narration_script($title, $text)
        {
            ?>
            <i title="<?php echo $title; ?>" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $text; ?>" data-html="true" class="fa fa-question-circle popover-help-form"></i>
            <?php
        }

        /** print disable mode popup */
        static function print_disable_popup() {
        ?>
            <div class="modal fade sib-disable-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="font-size: 22px;">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"><?php _e('SendinBlue','sib_lang'); ?></h4>
                        </div>
                        <div class="modal-body" style="padding: 30px;">
                            <p>
                                <?php _e('You are currently not logged in. Create an account or log in to benefit from all of SendinBlue\'s features an your Wordpress site.', 'sib_lang'); ?>
                            </p>
                            <ul>
                                <li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Collect and manage your contacts', 'sib_lang'); ?></li>
                                <li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Send transactional emails via SMTP or API', 'sib_lang'); ?></li>
                                <li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Real time statistics and email tracking', 'sib_lang'); ?></li>
                                <li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php _e('Edit and send email marketing', 'sib_lang'); ?></li>
                            </ul>
                            <div class="row" style="margin-top: 40px;">
                                <div class="col-md-6">
                                    <a href="https://www.sendinblue.com/users/login/" target="_blank"><i><?php _e('Have an account?', 'sib_lang'); ?></i></a>
                                </div>
                                <div class="col-md-6">
                                    <a href="https://www.sendinblue.com/users/signup/" target="_blank" class="btn btn-default"><i class="fa fa-angle-double-right"></i>&nbsp;<?php _e('Free Subscribe Now', 'sib_lang'); ?>&nbsp;<i class="fa fa-angle-double-left"></i></a>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <button id="sib-disable-popup" class="btn btn-primary" data-toggle="modal" data-target=".sib-disable-modal" style="display: none;">sss</button>
            <script>
                jQuery(document).ready(function() {
                    jQuery('.sib-disable-modal').modal();

                    jQuery('.sib-disable-modal').on('hidden.bs.modal', function() {
                        window.location.href = '<?php echo add_query_arg('page', 'sib_page_home', admin_url('admin.php')); ?>';
                    });
                });

            </script>

        <?php
        }

        /** print user sync popup */
        static function print_sync_popup() {
            ?>
            <div class="modal fade sib-sync-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="font-size: 22px;">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"><?php _e('Customers Synchronisation','sib_lang'); ?></h4>
                        </div>
                        <div class="modal-body sync-modal-body" style="padding: 10px;">
                            <div id="sync-failure" class="sib_alert alert alert-danger" style="margin-bottom: 0px;display: none;"></div>
                            <form id="sib-sync-form">
                            <!-- roles -->
                            <div class="row sync-row" style="margin-top: 0;">
                                <b><p><?php _e('Roles to sync', 'sib_lang'); ?></p></b>
                                <?php foreach (wp_roles()->roles as $role_name => $role_info): ?>
                                <div class="col-md-6">
                                    <span class="" style="display: block;float:left;padding-left: 16px;"><input type="checkbox" id="<?php echo $role_name ?>" value="<?php echo $role_name ?>" name="sync_role" checked><label for="<?php echo $role_name ?>" style="margin: 4px 24px 0 7px;font-weight: normal;"><?php echo $role_info['name'] ?></label></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- lists -->
                            <?php $lists = SIB_API_Manager::get_lists(); ?>
                            <div class="row sync-row">
                                <b><p><?php _e('Sync Lists', 'sib_lang'); ?></p></b>
                                <div class="col-md-6">
                                    <p><?php _e('Choose the Sendinblue list in which you want to add your existing customers:', 'sib_lang'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <select data-placeholder="Please select the list" id="sib_select_list" name="list_id" multiple="true">
                                        <?php foreach ($lists as $list): ?>
                                        <option value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Match Attributes -->
                            <?php
                            // available wordpress attributes
                            $wpAttrs = array(
                                'first_name' => __('First Name','sib_lang'),
                                'last_name' => __('Last Name','sib_lang'),
                                'user_url' => __('Website URL','sib_lang'),
                                'role' => __('User Role','sib_lang'),
                            );
                            // available sendinblue attributes
                            $sibAllAttrs = SIB_API_Manager::get_attributes();
                            $sibAttrs = $sibAllAttrs['attributes']['normal_attributes'];
                            ?>
                            <div class="row sync-row" id="sync-attr-area">
                                <b><p><?php _e('Match Attributes', 'sib_lang'); ?></p></b>
                                <div class="col-md-11" style="padding: 5px;border-bottom: dotted 1px #dedede;">
                                    <div class="col-md-6">
                                        <p><?php _e('WordPress Users Attributes', 'sib_lang'); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php _e('SendinBlue Contact Attributes', 'sib_lang'); ?></p>
                                    </div>
                                </div>

                                <div class="sync-attr-line">
                                    <div class="col-md-11 sync-attr" style="padding: 5px;border-bottom: dotted 1px #dedede;">
                                        <div class="col-md-5">
                                            <select class="sync-wp-attr" name="" style="width: 100%;">
                                                <?php foreach ($wpAttrs as $id=>$label): ?>
                                                    <option value="<?php echo $id; ?>"><?php echo $label; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1" style="padding-left: 10px;padding-top: 3px;"><span class="dashicons dashicons-leftright"></span></div>
                                        <div class="col-md-5">
                                            <select class="sync-sib-attr" name="" style="width: 100%;">
                                                <?php foreach ($sibAttrs as $attr): ?>
                                                    <option value="<?php echo $attr['name']; ?>"><?php echo $attr['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1" style="padding-top: 3px;">
                                            <a href="javascript:void(0)" class="sync-attr-dismiss" style="display: none;"><span class="dashicons dashicons-dismiss"></span></a>
                                        </div>
                                        <input type="hidden" class="sync-match" name="<?php echo $sibAttrs[0]['name']?>" value="first_name">
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding-top: 9px;">
                                    <a href="javascript:void(0)" class="sync-attr-plus"><span class="dashicons dashicons-plus-alt "></span></a>
                                </div>
                            </div>
                            <!-- Apply button -->
                            <div class="row" style="">
                                <a href="javascript:void(0)" id="sib_sync_users_btn" class="btn btn-primary" style="float: right;"><?php _e('Apply', 'sib_lang'); ?></a>
                            </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <button id="sib-sync-popup" class="btn btn-primary" data-toggle="modal" data-target=".sib-sync-modal" style="display: none;">sss</button>
            <script>


            </script>

            <?php
        }

        /** ajax module for validation (Home - welcome) */
        public static function ajax_validation_process()
        {
            $access_key = trim($_POST['access_key']);
			try {
                $mailin = new Mailin(SIB_Manager::sendinblue_api_url, $access_key);
            }catch( Exception $e ){
                if( $e->getMessage() == 'Mailin requires CURL module' ) {
                    wp_send_json('curl_no_installed');
                }else{
                    wp_send_json('curl_error');
                }

            }

            $response = $mailin->get_access_tokens();
            if(is_array($response)) {
                if($response['code'] == 'success') {

                    // store api info
                    $settings = array(
                        'access_key' => $access_key,
                    );
                    update_option(SIB_Manager::main_option_name, $settings);

                    SIB_Manager::$access_key = $access_key;

                    $access_token = $response['data']['access_token'];
                    $token_settings = array(
                        'access_token' => $access_token
                    );
                    update_option(SIB_Manager::access_token_option_name, $token_settings);

                    // get default language at SendinBlue
                    //$config = $mailin->getPluginConfig();

                    $mailin->partnerWordpress();

                    // create tables for users and forms
                    SIB_Model_Users::createTable();
                    SIB_Forms::createTable(); // create default form also
                    // If the client don't have attributes regarding Double OptIn then we will create these.
                    SIB_API_Manager::create_default_dopt();

                    wp_send_json('success');
                }
                else
                    wp_send_json($response['code']);
            } else {
                wp_send_json('fail');
            }
        }

        /** ajax module to change activate marketing automation option */
        public static function ajax_validate_ma()
        {
            $main_settings = get_option(SIB_Manager::main_option_name);
            $home_settings = get_option(SIB_Manager::home_option_name);
            $ma_key = $main_settings['ma_key'];
            if($ma_key != '') {
                $option_val = $_POST['option_val'];
                $home_settings['activate_ma'] = $option_val;
                update_option(SIB_Manager::home_option_name, $home_settings);
                wp_send_json($option_val);
            }else{
                $home_settings['activate_ma'] = 'no';
                update_option(SIB_Manager::home_option_name, $home_settings);
                wp_send_json('disabled');
            }
        }

        /** ajax module to change activate email option */
        public static function ajax_activate_email_change()
        {
            $option_val = $_POST['option_val'];
            $home_settings = get_option(SIB_Manager::home_option_name);
            $home_settings['activate_email'] = $option_val;
            update_option(SIB_Manager::home_option_name, $home_settings);
            wp_send_json($option_val);
        }

        /** ajax module to change sender detail */
        public static function ajax_sender_change(){
            $sender_id = $_POST['sender']; // sender id
            $home_settings = get_option(SIB_Manager::home_option_name);
            $home_settings['sender'] = $sender_id;
            $senders = SIB_API_Manager::get_sender_lists();
            foreach($senders as $sender){
                if($sender['id'] == $sender_id){
                    $home_settings['from_name'] = $sender['from_name'];
                    $home_settings['from_email'] = $sender['from_email'];
                }
            }
            update_option(SIB_Manager::home_option_name, $home_settings);
            wp_send_json('success');
        }

        /** ajax module for send a test email */
        public static function ajax_send_email()
        {
            $to = array($_POST['email'] => '');

            $subject  = __('[SendinBlue SMTP] test email', 'sib_lang');
            // get sender info
            $home_settings = get_option(SIB_Manager::home_option_name);
            if(isset($home_settings['sender'])) {
                $fromname = $home_settings['from_name'];
                $from_email = $home_settings['from_email'];
            }else{
                $from_email = __('no-reply@sendinblue.com', 'sib_lang');
                $fromname = __('SendinBlue', 'sib_lang');
            }

            $from = array($from_email, $fromname);
            $email_templates = SIB_API_Manager::get_email_template('test');

            $html = $email_templates['html_content'];

            $html = str_replace('{title}', $subject, $html);

            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);

            $headers = array("Content-Type"=> "text/html;charset=iso-8859-1", "X-Mailin-tag"=>'Wordpress Mailin Test' );
            $data = array(
                "to" => $to,
                "subject"  => $subject,
                "from" => $from,
                "html" => $html,
                "headers" => $headers
            );
            $mailin->send_email($data);

            wp_send_json('success');
        }

        /** ajax module for remove all transient value */
        public static function ajax_remove_cache(){
            SIB_API_Manager::remove_transients();
            wp_send_json('success');
        }

        /** ajax module for sync wp users to contact list */
        public static function ajax_sync_users(){
            $postData = $_POST['data'];
            if(!isset($postData['sync_role'])) { wp_send_json(array('code'=>'empty_role', 'message'=>__('Please select a user role.','sib_lang')));}
            if(isset($postData['errAttr'])) { wp_send_json(array('code'=>'attr_duplicated', 'message'=>sprintf(__('The attribute %s is duplicated. You can select one at a time.','sib_lang'), '<b>'.$_POST['data']['errAttr'].'</b>')));}

            $roles = (array)$postData['sync_role']; // array or string
            $listIDs = (array)$postData['list_id'];

            unset($postData['sync_role']);
            unset($postData['list_id']);

            $usersData = 'EMAIL';
            foreach($postData as $attrSibName=>$attrWP){
                $usersData.= ";".$attrSibName;
            }

            // sync users to sendinblue
            // create body data like csv
            // NAME;SURNAME;EMAIL\nName1;Surname1;example1@example.net\nName2;Surname2;example2@example.net
            $contentData = '';
            foreach($roles as $role){
                $users = get_users(array('role' => $role));
                if(empty($users)) continue;
                foreach($users as $user){
                    $userId = $user->ID;
                    $user_info = get_userdata($userId);
                    $userData = $user_info->user_email;
                    foreach($postData as $attrSibName=>$attrWP){
                        $userData.= ";".$user_info->$attrWP;
                    }
                    $contentData .= "\n".$userData;
                }
            }
            if($contentData == '') { wp_send_json(array('code'=>'empty_users', 'message'=>__('There is not any user in the roles.','sib_lang')));}

            $usersData .= $contentData;
            $result = SIB_API_Manager::sync_users($usersData, $listIDs);
            $currentUsers = count_users();
            if($result['code'] == 'success') update_option('sib_sync_users', $currentUsers);
            wp_send_json($result);
        }

        /** logout process */
        function logout()
        {
            $setting = array();
            update_option(SIB_Manager::main_option_name, $setting);

            $home_settings = array(
                'activate_email' => 'no',
                'activate_ma' => 'no'
            );
            update_option(SIB_Manager::home_option_name, $home_settings);

            // remove sync users option
            delete_option('sib_sync_users');
            // remove all transients
            SIB_API_Manager::remove_transients();

            // remove all forms
            SIB_Forms::removeAllForms();

            wp_redirect(add_query_arg('page', self::page_id, admin_url('admin.php')));
            exit;
        }



    }


}