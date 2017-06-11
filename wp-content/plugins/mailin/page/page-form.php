<?php

/**
 * Admin page : dashboard
 * @package SIB_Page_Form
 */

/**
 * Page class that handles backend page <i>dashboard ( for admin )</i> with form generation and processing
 * @package SIB_Page_Form
 */

if(!class_exists('SIB_Page_Form'))
{
    class SIB_Page_Form
    {
        /** Page slug */
        const page_id = 'sib_page_form';

        /** Page hook */
        protected $page_hook;

        /** page tabs */
        protected $tabs;

        /** form id*/
        public $formID;

        /**
         * Constructs new page object and adds entry to Wordpress admin menu
         */
        function __construct()
        {
            $this->page_hook = add_submenu_page(SIB_Page_Home::page_id, __('Forms', 'sib_lang'), __('Forms', 'sib_lang'), 'manage_options', self::page_id, array(&$this, 'generate'));
            add_action( 'admin_print_scripts-' . $this->page_hook, array($this, 'enqueue_scripts'));
            add_action( 'admin_print_styles-' . $this->page_hook, array($this, 'enqueue_styles'));
            add_action('load-'.$this->page_hook, array(&$this, 'init'));
        }

        /**
         * Init Process
         */
        function Init()
        {
            if(SIB_Manager::is_done_validation()) {
                //if(isset($_GET['action']) && $_GET['action'] == 'edit') {
                $this->forms = new SIB_Forms_List();
                $this->forms->prepare_items();
                //}
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
            wp_enqueue_style('thickbox');
        }

        /** generate page script */
        function generate()
        {
            ?>
            <div id="wrap" class="wrap box-border-box container-fluid">
                <h1><img id="logo-img" src="<?php echo SIB_Manager::$plugin_url . '/img/logo.png'; ?>">
                    <?php
                    $return_btn = 'none';
                    if(SIB_Manager::is_done_validation() && isset($_GET['id'])){
                        $return_btn = 'inline-block';
                    }
                    ?>
                <a href="<?php echo add_query_arg('page', self::page_id, admin_url('admin.php')); ?>" class="button" style="margin-top: 6px; display: <?php echo $return_btn; ?>;"><?php _e('Back to form\'s list' ,'sib_lang');?></a>
                </h1>
                <div id="wrap-left" class="box-border-box col-md-9 ">
                    <?php
                    if(SIB_Manager::is_done_validation()) {
                        if((isset($_GET['action']) && $_GET['action'] == 'edit') || (isset($_GET['action']) && $_GET['action'] == 'duplicate')){
                            $this->formID = isset($_GET['id']) ? $_GET['id'] : 'new';
                            $this->generate_form_edit();
                        }else{
                            $this->generate_forms_page();
                        }
                    } else {
                        $this->generate_welcome_page();
                    }
                    ?>
                </div>
                <div id="wrap-right-side" class="box-border-box col-md-3">
                    <?php

                    SIB_Page_Home::generate_side_bar();
                    ?>
                </div>
            </div>
        <?php
        }
        /** generate forms page */
        function generate_forms_page(){
            ?>
            <div id="main-content" class="sib-content">
                <div class="panel panel-default row small-content">
                <div class="page-header"><strong><?php _e('Forms', 'sib_lang'); ?></strong></div>

                    <form method="post" class="sib-forms-wrapper" style="padding:20px;min-height: 500px;">
                        <i style="font-size: 13px;"><?php _e("Note: Forms created in SendinBlue plugin for Wordpress won't be displayed in Forms section in SendinBlue application", "sib_lang"); ?></i>
            <?php
            $this->forms->display();
            ?>
                    </form>
                </div>
            </div>
            <?php
        }
        /** generate form edit page */
        function generate_form_edit()
        {
            $is_activated_smtp = SIB_API_Manager::get_smtp_status() == 'disabled' ? 0 : 1;
            $formData = SIB_Forms::getForm($this->formID);
            if(!empty($formData)) {
                if (isset($_GET['action']) && $_GET['action'] == 'duplicate') {
                    $this->formID = 'new';
                    $formData['title'] = '';
                }
                if(!isset($formData['gCaptcha']))
                {
                    $formData['gCaptcha'] = '0';
                }
                if(!isset($formData['termAccept']))
                {
                    $formData['termAccept'] = '1';
                }
                ?>
                <div id="main-content" class="sib-content">
                    <form action="admin.php" class="" method="post" role="form">
                        <input type="hidden" name="action" value="sib_setting_subscription">
                        <input type="hidden" name="sib_form_id" value="<?php echo $this->formID; ?>">
                        <input type="hidden" id="is_smtp_activated" value="<?php echo $is_activated_smtp; ?>">
                        <?php wp_nonce_field('sib_setting_subscription'); ?>
                        <!-- Subscription form -->
                        <div class="panel panel-default row small-content">
                            <div class="page-header">
                                <strong><?php _e('Subscription form', 'sib_lang'); ?></strong>&nbsp;<i
                                    id="sib_setting_form_spin" class="fa fa-spinner fa-spin fa-fw fa-lg fa-2x"></i>
                            </div>
                            <div id="sib_setting_form_body" class="panel-body">
                                <div class="row <!--small-content-->">
                                    <div class="col-md-6">
                                        <div style="margin: 12px 0 34px 0;">
                                            <b><?php _e('Form Name : ', 'sib_lang'); ?></b>&nbsp; <input type="text"
                                                                                                         name="sib_form_name"
                                                                                                         value="<?php echo $formData['title']; ?>"
                                                                                                         style="width: 60%;"
                                                                                                         required="required"/>
                                        </div>
                                        <?php
                                        if (function_exists('wp_editor')) {
                                            wp_editor($formData['html'], 'sibformmarkup', array('tinymce' => false, 'media_buttons' => true, 'textarea_name' => 'sib_form_html', 'textarea_rows' => 15));
                                        } else {
                                            ?><textarea class="widefat" cols="160" rows="20" id="sibformmarkup"
                                                        name="sib_form_html"><?php echo esc_textarea($formData['html']); ?></textarea><?php
                                        } ?>
                                        <br>

                                        <p>
                                            <?php
                                            _e('Use the shortcode', 'sib_lang');
                                            $id = $this->formID != 'new' ? $this->formID : '';
                                            ?>
                                            <i style="background-color: #eee;padding: 3px;">[sibwp_form
                                                id=<?php echo $id; ?>]</i>
                                            <?php
                                            _e('inside a post, page or text widget to display your sign-up form.', 'sib_lang'); ?>
                                            <b><?php _e('Do not copy and paste the above form mark up, that will not work', 'sib_lang'); ?></b>
                                        </p>
                                        <!-- use css of custom or theme -->
                                        <div class="panel panel-default row form-field">
                                            <div class="row small-content2" style="margin-top: 15px;margin-bottom: 10px;">
                                                <b><?php _e('Form Style', 'sib_lang'); ?>&nbsp;</b>
                                                <?php SIB_Page_Home::get_narration_script(__('Form Style', 'sib_lang'), __('Select the style you favorite. Your custom css will be applied to form only.', 'sib_lang')); ?>
                                            </div>
                                            <div id="sib_form_css_area" class="row small-content2" style="margin-bottom: 15px;">
                                                <label style="font-weight: normal;"><input type="radio" name="sib_css_type" value="1" <?php checked($formData['dependTheme'], '1'); ?>>&nbsp;<?php _e('Current Theme', 'sib_lang'); ?>
                                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="sib_css_type" value="0" <?php checked($formData['dependTheme'], '0'); ?>>&nbsp;<?php _e('Custom style', 'sib_lang'); ?>
                                                </label>
                                                <textarea class="widefat" cols="60" rows="10" id="sibcssmarkup" style="width: 100%; margin-top: 10px; font-size: 13px; display: <?php echo $formData['dependTheme'] == '0' ? 'block' : 'none';?>;"
                                                          name="sib_form_css"><?php echo esc_textarea($formData['css']); ?></textarea>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- hidden fields for attributes -->
                                        <input type="hidden" id="sib_hidden_email" data-type="email" data-name="email"
                                               data-text="<?php _e('Email Address', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_submit" data-type="submit"
                                               data-name="submit" data-text="<?php _e('Subscribe', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_message_1"
                                               value="<?php _e('Select SendinBlue Attribute', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_message_2"
                                               value="<?php _e('SendinBlue merge fields : Normal', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_message_3"
                                               value="<?php _e('SendinBlue merge fields : Category', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_message_4"
                                               value="<?php _e('Other', 'sib_lang'); ?>">
                                        <input type="hidden" id="sib_hidden_message_5"
                                               value="<?php _e('Submit Button', 'sib_lang'); ?>">

                                        <div id="sib-field-form" class="panel panel-default row form-field"
                                             style="padding-bottom: 20px;">
                                            <div class="alert alert-danger" style="margin:5px;display: none;">

                                            </div>
                                            <div class="row small-content2"
                                                 style="margin-top: 15px;margin-bottom: 15px;">
                                                <b><?php _e('Add a new Field', 'sib_lang'); ?></b>&nbsp;
                                                <?php SIB_Page_Home::get_narration_script(__('Add a New Field', 'sib_lang'), __('Choose an attribute and add it to the subscription form of your Website', 'sib_lang')); ?>
                                            </div>
                                            <div id="sib_sel_attribute_area" class="row small-content2"
                                                 style="margin-top: 20px;">
                                            </div>
                                            <div id="sib-field-content">
                                                <div style="margin-top: 30px;">
                                                    <div class="sib-attr-normal sib-attr-category row small-content2"
                                                         style="margin-top: 10px;" id="sib_field_label_area">
                                                        <?php _e('Label', 'sib_lang'); ?>
                                                        <small>(<?php _e('Optional', 'sib_lang'); ?>)</small>
                                                        <input type="text" class="col-md-12 sib_field_changes" id="sib_field_label">
                                                    </div>
                                                    <div class="sib-attr-normal row small-content2"
                                                         style="margin-top: 10px;" id="sib_field_placeholder_area">
                                                        <span><?php _e('Place holder', 'sib_lang'); ?>
                                                            <small>(<?php _e('Optional', 'sib_lang'); ?>)
                                                            </small> </span>
                                                        <input type="text" class="col-md-12 sib_field_changes" id="sib_field_placeholder">
                                                    </div>
                                                    <div class="sib-attr-normal row small-content2"
                                                         style="margin-top: 10px;" id="sib_field_initial_area">
                                                        <span><?php _e('Initial value', 'sib_lang'); ?>
                                                            <small>(<?php _e('Optional', 'sib_lang'); ?>)
                                                            </small> </span>
                                                        <input type="text" class="col-md-12 sib_field_changes" id="sib_field_initial">
                                                    </div>
                                                    <div class="sib-attr-other row small-content2"
                                                         style="margin-top: 10px;" id="sib_field_button_text_area">
                                                        <span><?php _e('Button Text', 'sib_lang'); ?></span>
                                                        <input type="text" class="col-md-12 sib_field_changes" id="sib_field_button_text">
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">

                                                    <div class="sib-attr-normal sib-attr-category row small-content2" style="margin-top: 5px;" id="sib_field_required_area">
                                                        <label style="font-weight: normal;"><input type="checkbox" class="sib_field_changes" id="sib_field_required">&nbsp;&nbsp;<?php _e('Required field ?', 'sib_lang'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="sib-attr-category row small-content2"
                                                         style="margin-top: 5px;" id="sib_field_type_area">
                                                        <label style="font-weight: normal;"><input type="radio" class="sib_field_changes" name="sib_field_type" value="select"
                                                                                                   checked>&nbsp;<?php _e('Drop-down List', 'sib_lang'); ?>
                                                        </label>&nbsp;&nbsp;
                                                        <label style="font-weight: normal;"><input type="radio" class="sib_field_changes" name="sib_field_type"
                                                                                                   value="radio">&nbsp;<?php _e('Radio List', 'sib_lang'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row small-content2" style="margin-top: 20px;"
                                                     id="sib_field_add_area">
                                                    <button type="button" id="sib_add_to_form_btn"
                                                            class="btn btn-default sib-add-to-form"><span
                                                            class="sib-large-icon"><</span> <?php _e('Add to form', 'sib_lang'); ?>
                                                    </button>&nbsp;&nbsp;
                                                    <?php SIB_Page_Home::get_narration_script(__('Add to form', 'sib_lang'), __('Please click where you want to insert the field and click on this button. By default, the new field will be added at top.', 'sib_lang')); ?>
                                                </div>
                                                <div class="row small-content2" style="margin-top: 20px;"
                                                     id="sib_field_html_area">
                                                    <span><?php _e('Generated HTML', 'sib_lang'); ?></span>
                                                    <textarea class="col-md-12" style="height: 140px;"
                                                              id="sib_field_html"></textarea>
                                                </div>
                                            </div>
                                            <div class="row small-content2" style="margin-top: 15px;margin-bottom: 15px;">
                                                <b><?php _e('Add Captcha', 'sib_lang'); ?></b>&nbsp;
                                                <?php SIB_Page_Home::get_narration_script(__('Add Captcha', 'sib_lang'), __('To use Google Captcha on this form, you should input site key and secret key.' , 'sib_lang')); ?>
                                            </div>
                                            <div class="row small-content2" style="margin-top: 0px;">
                                                <input type="radio" name="sib_add_captcha" class="sib-add-captcha" value="1" <?php checked($formData['gCaptcha'], '1'); ?>><label class="sib-radio-label">Yes</label>
                                                <input type="radio" name="sib_add_captcha" class="sib-add-captcha" value="0" <?php checked($formData['gCaptcha'], '0'); ?>><label class="sib-radio-label">No</label>
                                            </div>
                                            <div class="row small-content2 sib-captcha-key" <?php if($formData['gCaptcha'] != '1') echo("style='display: none;'");?>>
                                                <i><?php _e('Site Key', 'sib_lang'); ?></i>&nbsp;
                                                <input type="text" class="col-md-12" id="sib_captcha_site" name="sib_captcha_site" value="<?php if(isset($formData['gCaptcha_secret']) && !empty($formData['gCaptcha_secret']))
                                                    {
                                                        echo $formData['gCaptcha_site'];
                                                    }else
                                                   {
                                                        echo '';
                                                   }
                                                ?>">
                                            </div>
                                            <div class="row small-content2 sib-captcha-key" <?php if($formData['gCaptcha'] != '1') echo("style='display: none;'");?>>
                                                <i><?php _e('Secret Key', 'sib_lang'); ?></i>&nbsp;
                                                <input type="text" class="col-md-12" id="sib_captcha_secret" name="sib_captcha_secret" value="<?php if(isset($formData['gCaptcha_secret']) && !empty($formData['gCaptcha_secret']))
                                                {
                                                    echo $formData['gCaptcha_secret'];
                                                }else
                                                {
                                                    echo '';
                                                }
                                                ?>">
                                            </div>
                                            <div class="row small-content2 sib-captcha-key" style="display: none;">
                                                <button type="button" id="sib_add_captcha_btn"
                                                        class="btn btn-default sib-add-to-form"><span
                                                        class="sib-large-icon"><</span> <?php _e('Add to form', 'sib_lang'); ?>
                                                </button>&nbsp;&nbsp;
                                                <?php SIB_Page_Home::get_narration_script(__('Add Captcha', 'sib_lang'), __('Please click where you want to insert the field and click on this button. By default, the new field will be added at top.', 'sib_lang')); ?>
                                            </div>

                                            <!-- for terms -->
                                            <div class="row small-content2" style="margin-top: 15px;margin-bottom: 15px;">
                                                <b><?php _e('Add a Term acceptance checkbox', 'sib_lang'); ?></b>&nbsp;
                                                <?php SIB_Page_Home::get_narration_script(__('Add a Term acceptance checkbox', 'sib_lang'), __('If the terms and condition checkbox is added to the form, the field will be mandatory for subscription.' , 'sib_lang')); ?>
                                            </div>
                                            <div class="row small-content2" style="margin-top: 0px;">
                                                <input type="radio" name="sib_add_terms" class="sib-add-terms" value="1" <?php checked($formData['termAccept'], '1'); ?>><label class="sib-radio-label">Yes</label>
                                                <input type="radio" name="sib_add_terms" class="sib-add-terms" value="0" <?php checked($formData['termAccept'], '0'); ?>><label class="sib-radio-label">No</label>
                                            </div>
                                            <div class="row small-content2 sib-terms-url" <?php if($formData['termAccept'] != '1') echo("style='display: none;'");?>>
                                                <i><?php _e('URL to terms and conditions', 'sib_lang'); ?></i>&nbsp;
                                                <input type="text" class="col-md-12" id="sib_terms_url" name="sib_terms_url" value="<?php if(isset($formData['termsURL']) && !empty($formData['termsURL']))
                                                {
                                                    echo $formData['termsURL'];
                                                }else
                                                {
                                                    echo '';
                                                }
                                                ?>">
                                            </div>
                                            <div class="row small-content2 sib-terms-url" >
                                                <button type="button" id="sib_add_termsUrl_btn"
                                                        class="btn btn-default sib-add-to-form"><span
                                                        class="sib-large-icon"><</span> <?php _e('Add to form', 'sib_lang'); ?>
                                                </button>&nbsp;&nbsp;
                                                <?php SIB_Page_Home::get_narration_script(__('Add Terms URL', 'sib_lang'), __('Please click where you want to insert the field and click on this button. By default, the new field will be added at top.', 'sib_lang')); ?>
                                            </div>

                                        </div>

                                        <!-- preview field -->

                                        <div class="panel panel-default row form-field">
                                            <div class="row small-content2" style="margin-top: 15px;margin-bottom: 15px;">
                                                <b><?php _e('Preview', 'sib_lang'); ?>&nbsp;
                                                    <span id="sib-preview-form-refresh"
                                                          class="glyphicon glyphicon-refresh"
                                                          style="cursor:pointer"></span></b>
                                            </div>
                                            <iframe id="sib-preview-form"
                                                    src="<?php echo site_url(); ?>/?sib_form=<?php echo $this->formID; ?>"
                                                    width="300px" height="428"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div class="row small-content" style="margin-top: 30px;">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary"><?php _e('Save', 'sib_lang'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Subscription form-->

                        <!-- Sign up Process -->

                        <div class="panel panel-default row small-content">

                            <!-- Adding security through hidden referrer field -->
                            <div class="page-header">
                                <strong><?php _e('Sign up process', 'sib_lang'); ?></strong>&nbsp;<i
                                    id="sib_setting_signup_spin" class="fa fa-spinner fa-spin fa-fw fa-lg fa-2x"></i>
                            </div>
                            <div id="sib_setting_signup_body" class="panel-body">
                                <div id="sib_form_alert_message" class="alert alert-danger alert-dismissable fade in"
                                     role="alert" style="display: none;">
                                    <span id="sib_disclaim_smtp"
                                          style="display: none;"><?php _e('Confirmation emails will be sent through your own email server, but you have no guarantees on their deliverability. <br/> <a href="https://app-smtp.sendinblue.com/" target="_blank">Click here</a> to send your emails through SendinBlue in order to improve your deliverability and get statistics', 'sib_lang'); ?></span>
                                    <span id="sib_disclaim_do_template"
                                          style="display: none;"><?php _e('The template you selected does not include a link [DOUBLEOPTIN] to allow subscribers to confirm their subscription. <br/> Please edit the template to include a link with [DOUBLEOPTIN] as URL.', 'sib_lang'); ?></span>
                                </div>

                                <!-- Linked List -->
                                <div class="row small-content">
                                    <span class="col-md-3">
                                        <?php _e('Linked List', 'sib_lang'); ?>&nbsp;
                                        <?php SIB_Page_Home::get_narration_script(__('Linked List', 'sib_lang'), __('Select the list where you want to add your new subscribers', 'sib_lang')); ?>
                                    </span>
                                    <div id="sib_select_list_area" class="col-md-4">

                                        <input type="hidden" id="sib_selected_list_id" value="">
                                        <select data-placeholder="Please select the list" id="sib_select_list"
                                                class="col-md-12 chosen-select" name="list_id[]" multiple=""
                                                tabindex="-1"></select>
                                    </div>
                                    <div class="col-md-5">
                                        <small
                                            style="font-style: italic;"><?php _e('You can use Marketing Automation to create specific workflow when a user is added to the list.', 'sib_lang'); ?></small>
                                    </div>

                                </div>
                                <!-- confirmation email -->
                                <div class="row small-content">
                                    <span class="col-md-3"><?php _e('Send a confirmation email', 'sib_lang'); ?><?php echo SIB_Page_Home::get_narration_script(__('Confirmation message', 'sib_lang'), __('You can choose to send a confirmation email. You will be able to set up the template that will be sent to your new suscribers', 'sib_lang')); ?></span>

                                    <div class="col-md-4">
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_confirm_email_yes"
                                                                                                    name="is_confirm_email"
                                                                                                    value="1" <?php checked($formData['isOpt'], '1'); ?>>&nbsp;<?php _e('Yes', 'sib_lang'); ?>
                                        </label>
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_confirm_email_no"
                                                                                                    name="is_confirm_email"
                                                                                                    value="0" <?php checked($formData['isOpt'], '0'); ?>>&nbsp;<?php _e('No', 'sib_lang'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <small
                                            style="font-style: italic;"><?php _e('Select "Yes" if you want your subscribers to receive a confirmation email', 'sib_lang'); ?></small>
                                    </div>
                                </div>
                                <!-- select template id for confirmation email -->
                                <div class="row" id="sib_confirm_template_area">
                                    <input type="hidden" id="sib_selected_template_id"
                                           value="<?php echo $formData['templateID']; ?>">
                                    <input type="hidden" id="sib_default_template_name"
                                           value="<?php _e('Default', 'sib_lang'); ?>">

                                    <div class="col-md-3" id="sib_template_id_area">
                                    </div>
                                    <div class="col-md-4">
                                        <a href="https://my.sendinblue.com/camp/listing#temp_active_m" class="col-md-12"
                                           target="_blank"><i
                                                class="fa fa-angle-right"></i> <?php _e('Set up my templates', 'sib_lang'); ?>
                                        </a>
                                    </div>
                                </div>
                                <!-- double optin confirmation email -->
                                <div class="row small-content">
                                    <span
                                        class="col-md-3"><?php _e('Double Opt-In', 'sib_lang'); ?><?php echo SIB_Page_Home::get_narration_script(__('Double Opt-In', 'sib_lang'), __('Your subscribers will receive an email inviting them to confirm their subscription. Be careful, your subscribers are not saved in your list before confirming their subscription.', 'sib_lang')); ?></span>

                                    <div class="col-md-4">
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_double_optin_yes"
                                                                                                    name="is_double_optin"
                                                                                                    value="1" <?php checked($formData['isDopt'], '1'); ?>>&nbsp;<?php _e('Yes', 'sib_lang'); ?>
                                        </label>
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_double_optin_no"
                                                                                                    name="is_double_optin"
                                                                                                    value="0" <?php checked($formData['isDopt'], '0'); ?>>&nbsp;<?php _e('No', 'sib_lang'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <small
                                            style="font-style: italic;"><?php _e('Select "Yes" if you want your subscribers to confirm their email address', 'sib_lang'); ?></small>
                                    </div>
                                </div>
                                <!-- select template id for double optin confirmation email -->
                                <div class="row" id="sib_doubleoptin_template_area">
                                    <input type="hidden" id="sib_selected_do_template_id"
                                           value="<?php echo $formData['templateID']; ?>">

                                    <div class="col-md-3" id="sib_doubleoptin_template_id_area">
                                    </div>
                                    <div class="col-md-4">
                                        <a href="https://my.sendinblue.com/camp/listing?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link#temp_active_m"
                                           class="col-md-12" target="_blank"><i
                                                class="fa fa-angle-right"></i> <?php _e('Set up my templates', 'sib_lang'); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row small-content" id="sib_double_redirect_area">
                                    <span
                                        class="col-md-3"><?php _e('Redirect to this URL after clicking in the email', 'sib_lang'); ?></span>

                                    <div class="col-md-8">
                                        <input type="url" class="col-md-11" name="redirect_url"
                                               value="<?php echo $formData['redirectInEmail']; ?>">
                                    </div>
                                </div>

                                <div class="row small-content">
                                    <span
                                        class="col-md-3"><?php _e('Redirect to this URL after subscription', 'sib_lang'); ?></span>

                                    <div class="col-md-4">
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_redirect_url_click_yes"
                                                                                                    name="is_redirect_url_click"
                                                                                                    value="1" checked>&nbsp;<?php _e('Yes', 'sib_lang'); ?>
                                        </label>
                                        <label class="col-md-6" style="font-weight: normal;"><input type="radio"
                                                                                                    id="is_redirect_url_click_no"
                                                                                                    name="is_redirect_url_click"
                                                                                                    value="0" <?php checked($formData['redirectInForm'], ''); ?>>&nbsp;<?php _e('No', 'sib_lang'); ?>
                                        </label>

                                    </div>
                                    <div class="col-md-5">
                                        <small
                                            style="font-style: italic;"><?php _e('Select "Yes" if you want to redirect your subscribers to a specific page after they fullfill the form', 'sib_lang'); ?></small>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;<?php
                                if ($formData['redirectInForm'] == '') {
                                    echo 'display:none;';
                                }
                                ?>" id="sib_subscrition_redirect_area">
                                    <span class="col-md-3"></span>

                                    <div class="col-md-8">
                                        <input type="url" class="col-md-11" name="redirect_url_click"
                                               value="<?php echo $formData['redirectInForm']; ?>">
                                    </div>
                                </div>

                                <div class="row small-content" style="margin-top: 30px;">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary"><?php _e('Save', 'sib_lang'); ?></button>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sign up process form-->

                        <!-- Confirmation message form -->
                        <div class="panel panel-default row small-content">
                            <div class="page-header">
                                <strong><?php _e('Confirmation message', 'sib_lang'); ?></strong>
                            </div>
                            <div class="panel-body">
                                <div class="row small-content">
                                    <span class="col-md-3"><?php _e('Success message', 'sib_lang'); ?></span>

                                    <div class="col-md-8">
                                        <input type="text" class="col-md-11" name="alert_success_message"
                                               value="<?php echo $formData['successMsg']; ?>" required>&nbsp;
                                        <?php echo SIB_Page_Home::get_narration_script(__('Success message', 'sib_lang'), __('Set up the success message that will appear when one of your visitors surccessfully signs up', 'sib_lang')); ?>
                                    </div>
                                </div>
                                <div class="row small-content">
                                    <span class="col-md-3"><?php _e('General error message', 'sib_lang'); ?></span>

                                    <div class="col-md-8">
                                        <input type="text" class="col-md-11" name="alert_error_message"
                                               value="<?php echo $formData['errorMsg']; ?>" required>&nbsp;
                                        <?php echo SIB_Page_Home::get_narration_script(__('General message error', 'sib_lang'), __('Set up the message that will appear when an error occurs during the subscritpion process', 'sib_lang')); ?>
                                    </div>
                                </div>
                                <div class="row small-content">
                                    <span class="col-md-3"><?php _e('Existing subscribers', 'sib_lang'); ?></span>

                                    <div class="col-md-8">
                                        <input type="text" class="col-md-11" name="alert_exist_subscriber"
                                               value="<?php echo $formData['existMsg']; ?>" required>&nbsp;
                                        <?php echo SIB_Page_Home::get_narration_script(__('Existing Suscribers', 'sib_lang'), __('Set up the message that will appear when a suscriber is already in your database', 'sib_lang')); ?>
                                    </div>
                                </div>
                                <div class="row small-content">
                                    <span class="col-md-3"><?php _e('Invalid email address', 'sib_lang'); ?></span>

                                    <div class="col-md-8">
                                        <input type="text" class="col-md-11" name="alert_invalid_email"
                                               value="<?php echo $formData['invalidMsg']; ?>" required>&nbsp;
                                        <?php echo SIB_Page_Home::get_narration_script(__('Invalid email address', 'sib_lang'), __('Set up the message that will appear when the email address used to sign up is not valid', 'sib_lang')); ?>
                                    </div>
                                </div>
                                <div class="row small-content" style="margin-top: 30px;">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary"><?php _e('Save', 'sib_lang'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Confirmation message form-->
                    </form>
                </div>
                <script>
                    jQuery(document).ready(function () {
                        jQuery('#sib_add_to_form_btn').click(function () {
                            //var field_html = jQuery('#sib_field_html').html();

                            // tinyMCE.activeEditor.selection.setContent(field_html);

                            return false;
                        });
                    });
                </script>
                <?php
            }else{
                // empty
                ?>
                <div id="main-content" class="sib-content">
                    <div class="panel panel-default row small-content">
                        <div class="page-header">
                            <strong><?php _e('Subscription form', 'sib_lang'); ?></strong>
                        </div>
                        <div style="padding: 24px 32px; margin-bottom: 12px;">
                            <?php _e('Sorry, you selected invalid form ID. Please check again if the ID is right', 'sib_lang'); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        /** generate welcome page */
        function generate_welcome_page()
        {
        ?>
            <div id="main-content" class="row">
                <img class="small-content" src="<?php echo SIB_Manager::$plugin_url . '/img/background/setting.png'?>" style="width: 100%;">
            </div>
        <?php
            SIB_Page_Home::print_disable_popup();
        }

        /** save subscription form setting */
        public static function save_setting_subscription()
        {
            // check user role
            if( !current_user_can( 'manage_options' ) )
                wp_die('Not allowed');

            // check secret through hidden referrer field
            check_admin_referer( 'sib_setting_subscription' );

            // subscription form
            $formID = isset($_POST['sib_form_id']) ? $_POST['sib_form_id'] : '';
            $form_name = isset($_POST['sib_form_name']) ? $_POST['sib_form_name'] : '';
            $form_html = isset($_POST['sib_form_html']) ? stripslashes($_POST['sib_form_html']) : '';
            $list_ids = maybe_serialize($_POST['list_id']); // array()
            $dependTheme = isset( $_POST['sib_css_type'] ) ? $_POST['sib_css_type'] : '';
            $customCss = isset($_POST['sib_form_css']) ? $_POST['sib_form_css'] : '';
            $gCaptcha = isset($_POST['sib_add_captcha']) ? $_POST['sib_add_captcha'] : '0';
            $gCaptchaSecret = isset($_POST['sib_captcha_secret']) ? $_POST['sib_captcha_secret'] : '';
            $gCaptchaSite = isset($_POST['sib_captcha_site']) ? $_POST['sib_captcha_site'] : '';
            $termAccept = isset($_POST['sib_add_terms']) ? $_POST['sib_add_terms'] : '0';
            $termURL = isset($_POST['sib_terms_url']) ? $_POST['sib_terms_url'] : '';
            // sign up process
            $templateID = '-1';
            $redirectInForm = '';

            $isOpt = $_POST['is_confirm_email'];
            if($isOpt) $templateID = $_POST['template_id'];
            $isDopt = $_POST['is_double_optin'];
            if($isDopt) $templateID = $_POST['doubleoptin_template_id'];
            $redirectInEmail = $_POST['redirect_url'];
            $isRedirectInForm = $_POST['is_redirect_url_click'];
            if($isRedirectInForm) $redirectInForm = $_POST['redirect_url_click'];

            // get available attributes list
            $attributes = SIB_API_Manager::get_attributes();
            $attributes = array_merge($attributes['attributes']['normal_attributes'],$attributes['attributes']['category_attributes']);
            $available_attrs = array('email');
            if (isset($attributes) && is_array($attributes)) {
                foreach($attributes as $attribute)
                {
                    $pos = strpos($form_html, 'sib-' . $attribute['name'] . '-area');
                    if($pos !== false) {
                        $available_attrs[] = $attribute['name'];
                    }
                }
            }

            $formData = array(
                'title' => $form_name,
                'html' => addslashes($form_html),
                'css' => $customCss,
                'listID' => $list_ids,
                'dependTheme' => $dependTheme,
                'isOpt' => $isOpt,
                'isDopt' => $isDopt,
                'templateID' => $templateID,
                'redirectInEmail' => $redirectInEmail,
                'redirectInForm' => $redirectInForm,
                'successMsg'=> esc_attr($_POST['alert_success_message']),
                'errorMsg' => esc_attr($_POST['alert_error_message']),
                'existMsg' => esc_attr($_POST['alert_exist_subscriber']),
                'invalidMsg' => esc_attr($_POST['alert_invalid_email']),
                'attributes' => implode(',', $available_attrs),
                'gcaptcha'   => $gCaptcha,
                'gcaptcha_secret' => $gCaptchaSecret,
                'gcaptcha_site'   => $gCaptchaSite,
                'termAccept'      => $termAccept,
                'termsURL'        => $termURL
            );
            if($formID == 'new'){
                $formID = SIB_Forms::addForm($formData);
            }else {
                SIB_Forms::updateForm($formID, $formData);
            }

            wp_redirect(add_query_arg(array('page'=>self::page_id, 'action'=>'edit', 'id'=>$formID), admin_url('admin.php')));
            exit;
        }



        /**
         * get template lists of sendinblue
         */
        public static function get_template_lists()
        {
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $data = array(
                'type' => 'template',
                'status' => 'temp_active'
            );
            $response = $mailin->get_campaigns_v2($data);
            if (!isset($response['code']) || ($response['code'] != 'success')) {
                return null;
            }
            return $response['data']['campaign_records'];
        }


      /** ajax process when change template id */
        public static function ajax_change_template()
        {
            $template_id = $_POST['template_id'];
            $mailin = new Mailin(SIB_Manager::sendinblue_api_url, SIB_Manager::$access_key);
            $data = array(
                'id' => $template_id
            );
            $response = $mailin->get_campaign_v2($data);

            $ret_email = '-1';
            if($response['code'] == 'success') {
                $from_email = $response['data'][0]['from_email'];
                if($from_email == '[DEFAULT_FROM_EMAIL]') {
                    $ret_email = '-1';
                } else {
                    $ret_email = $from_email;
                }
            }

            wp_send_json($ret_email);
        }

      /**
       * Ajax module to get all lists.
       */
        public static function ajax_get_lists() {

            $lists = SIB_API_Manager::get_lists();
            $frmID = $_POST['frmid'];
            $formData = SIB_Forms::getForm($frmID);
            $result = array(
                'lists' => $lists,
                'selected' => $formData['listID'],
            );
            wp_send_json($result);
        }

      /**
       * Ajax module to get all templates.
       */
        public static function ajax_get_templates() {

            $templates = SIB_API_Manager::get_templates();
            $result = array('templates' => $templates);
            wp_send_json($result);
        }

      /**
       * Ajax module to get all attributes.
       */
        public static function ajax_get_attributes() {

            $attrs = SIB_API_Manager::get_attributes();
            $result = array(
                'attrs' => $attrs,
            );
            wp_send_json($result);
        }

        /**
         * Ajax module to update form html for preview
         */
        public static function ajax_update_html(){
            //$frmid = $_POST['frmid']; // 'new' or number of id
            $formData = array(
                'html' => $_POST['frmData'],
                'css' => $_POST['frmCss'],
                'dependTheme' => $_POST['isDepend'],
            );

            update_option(SIB_Manager::preview_option_name, $formData);
            die;
        }


    }
}