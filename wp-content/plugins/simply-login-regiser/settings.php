<?php
if(!class_exists('SLR_Plugin_Settings'))
{
	class SLR_Plugin_Settings
	{
        private $username;
        private $email;
        private $password;
        private $website;
        private $first_name;
        private $last_name;
        private $nickname;
        private $bio;


		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('init', array(&$this, 'localize_plugin'));
        	add_action('admin_menu', array(&$this, 'add_menu'));

            // Add style and script
            add_action('wp_print_styles', array($this, 'slr_styles'));
            add_action('wp_print_scripts', array($this, 'slr_scripts'));

            // Create shortcode
            add_shortcode('slr_login', array($this, 'slr_login_shortcode'));
            add_shortcode('slr_register', array($this, 'slr_register_shortcode'));

            // ajax call
            add_action( 'wp_ajax_slr_ajaxlogin',  array($this, 'slr_custom_ajax_login' ));
            add_action( 'wp_ajax_nopriv_slr_ajaxlogin',  array($this, 'slr_custom_ajax_login' ));

            add_action( 'wp_ajax_slr_ajaxregister',  array($this, 'slr_custom_ajax_registration' ));
            add_action( 'wp_ajax_nopriv_slr_ajaxregister',  array($this, 'slr_custom_ajax_registration' ));

		} // END public function __construct
		
        function localize_plugin(){
            // register styles
            wp_register_style('slr_plugin_css', SLR_URL . 'assets/css/kube.css', null, SLR_VERSION);
            wp_register_style('slr_plugin_css', SLR_URL . 'assets/css/slr-style.css', null, SLR_VERSION);
            
            // register scripts
            wp_register_script('slr_login_js', SLR_URL . 'assets/js/slr-custom.js', array('jquery'), '1.0.0', true);
            wp_localize_script( 'slr_login_js', 'slr_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            //wp_register_script('slr_regiter_js', SLR_URL . 'assets/js/slr-regiter.js', array('jquery'), '1.0.0', true);
        }

        /* Calling Style */
        function slr_styles() {
            wp_enqueue_style('slr_plugin_css');
            wp_enqueue_style('slr_font_css');
        }// END public function slr_styles()

        /* Calling Script*/
        function slr_scripts() {
            wp_enqueue_script('slr_login_js');            
        }// END public function slr_scripts()


        function slr_login_shortcode(){
            if (is_user_logged_in()) {
                return('your are already logged in.');
            }

            $output  = '<div class="slr-login-from-wrap">
                            <form class="forms" method="post" id="slr_login_form">
                                <fieldset>
                                    <legend>Login</legend>
                                    <section>
                                        <label>username</label>
                                        <input type="text" name="slr_user_login" class="width-6"  />
                                    </section>
                                    <section>
                                        <label>Password</label>
                                        <input type="password" name="slr_user_password" class="width-6"  />
                                    </section>
                                    <section>
								        <label class="checkbox">
								        <input type="checkbox" name="slr_rememberme" value="true"> Remember me</label>
								    </section>
                                    <section>
                                        <button type="primary" class="slr_login_btn">Log in</button>
                                        <img src="'.SLR_URL.'assets/img/loading.gif" id="slr_loader" style="display:none;">
                                    </section>
                                    <section style="display:none" class="slr_response_msg">
                                    	<div class="alert"></div>
                                    </section>
                                </fieldset>
                            </form>
                        </div>';
            return $output;
        }


        // User login code.
        function slr_custom_ajax_login(){
            if($_POST) {
                $login_data = array();
                $login_data['user_login']    = trim($_POST['username']);
                $login_data['user_password'] = $_POST['password'];
                
                if($_POST['rememberme']){
                    $login_data['remember'] = "true";
                }else{
                    $login_data['remember'] = "false";
                }

                $user_signon = wp_signon( $login_data, false );
                if ( is_wp_error($user_signon) ){
                    echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
                } else {
                    echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
                }
                die();
            }else{
                echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid login details')));
                die();
            }
        }


        // Registration shortcode function
        function slr_register_shortcode(){
            if (is_user_logged_in()) {
                return('your are already logged in.');
            }

            $output  = '<div class="slr-register-from-wrap">
                            <form class="forms" method="post" id="slr_register_form">
                                <section>
                                    <label for="reg-name">Username <span class="req">*</span> </label>
                                    <input type="text" name="reg_uname" class="width-6" id="reg-name" required/>
                                </section>
                                <section>
                                    <label>Email <span class="req">*</span> </label>
                                    <input type="email" name="reg_email" class="width-6" required/>
                                </section>
                                <section>
                                    <label>Password <span class="req">*</span> </label>
                                    <input type="password" name="reg_password" class="width-6" required />
                                </section>
                                <section>
                                    <label>First Name <span class="req">*</span></label>
                                    <input type="text" name="reg_fname" class="width-6" />
                                </section>
                                <section>
                                    <label>Last Name <span class="req">*</span></label>
                                    <input type="text" name="reg_lname" class="width-6" />
                                </section>
                                <section>
                                    <label>Website</label>
                                    <input type="text" name="reg_website" class="width-6" />
                                </section>
                                <section>
                                    <label>Nickname</label>
                                    <input type="text" name="reg_nickname" class="width-6" />
                                </section>
                                <section>
                                    <label>About / Bio</label>
                                    <textarea name="reg_bio" rows="4"></textarea>
                                </section>
                                <section>
                                    <button type="primary" class="slr_reg_btn">Register</button>
                                    <img src="'.SLR_URL.'assets/img/loading.gif" id="slr_loader" style="display:none;">
                                </section>
                                <section style="display:none" class="slr_response_msg">
                                    <div class="alert"></div>
                                </section>
                            </form>
                        </div>';    
           
            return $output;
        }


        // Register ajax function
        function slr_custom_ajax_registration()
        {   
            if ($_POST) {
                $this->username   = $_POST['reg_uname'];
                $this->email      = $_POST['reg_email'];
                $this->password   = $_POST['reg_password'];
                $this->website    = $_POST['reg_website'];
                $this->first_name = $_POST['reg_fname'];
                $this->last_name  = $_POST['reg_lname'];
                $this->nickname   = $_POST['reg_nickname'];
                $this->bio        = $_POST['reg_bio'];
            }

            $userdata = array(
                            'user_login'  => esc_attr($this->username),
                            'user_email'  => esc_attr($this->email),
                            'user_pass'   => esc_attr($this->password),
                            'user_url'    => esc_attr($this->website),
                            'first_name'  => esc_attr($this->first_name),
                            'last_name'   => esc_attr($this->last_name),
                            'nickname'    => esc_attr($this->nickname),
                            'description' => esc_attr($this->bio)
                        );

            if (is_wp_error($this->validation())) {
                echo json_encode(array('loggedin'=>false, 'message'=> $this->validation()->get_error_message() ));
            } else {
                $register_user = wp_insert_user($userdata);
                if (!is_wp_error($register_user)) {
                    echo json_encode(array('loggedin'=>true, 'message'=> 'Registration completed.' ));
                } else {
                    echo json_encode(array('loggedin'=>false, 'message'=> $register_user->get_error_message() ));                    
                }
            }
            die();
        }

        // Recitation validations
        function validation()
        {

            if (empty($this->username) || empty($this->password) || empty($this->email)) {
                return new WP_Error('field', 'Required form field is missing.');
            }

            if (strlen($this->username) < 4) {
                return new WP_Error('username_length', 'Username too short. At least 4 characters is required.');
            }

            if (strlen($this->password) < 8) {
                return new WP_Error('password', 'Password length must be greater than 8.');
            }

            if (!is_email($this->email)) {
                return new WP_Error('email_invalid', 'Email is not valid');
            }

            if (email_exists($this->email)) {
                return new WP_Error('email', 'Email Already in use');
            }

            if (!empty($website)) {
                if (!filter_var($this->website, FILTER_VALIDATE_URL)) {
                    return new WP_Error('website', 'Website is not a valid URL');
                }
            }

            $details = array(
                            'Username'   => $this->username,
                            'First Name' => $this->first_name,
                            'Last Name'  => $this->last_name,
                        );

            foreach ($details as $field => $detail) {
                if (!validate_username($detail)) {
                    return new WP_Error('name_invalid', 'Sorry, the "' . $field . '" you entered is not valid');
                }
            }
        }


        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'Simply Login Register Settings', 
        	    'SRL Settings', 
        	    'manage_options', 
        	    'slr_plugin_setting', 
        	    array(&$this, 'slr_plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function slr_plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}

            echo "<h2>1. How to add login page?</h2>";
            echo "Ans. : Create a page and put a shortcode <code>[slr_login]</code>. <br/><br/>";

            echo "<h2>2. How to add register page?</h2>";
            echo "Ans. : Create a page and put a shortcode <code>[slr_register]</code>. ";
	
        	// Render the settings template
        	//include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class SLR_Plugin_Settings
} // END if(!class_exists('SLR_Plugin_Settings'))