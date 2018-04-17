<?php
/**
 * Theme Panel
 *
 * @package Ocean_Extra
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( OE_PATH .'/includes/panel/push-monkey-client.php' );
require_once( OE_PATH .'/includes/panel/push-monkey-woocommerce.php' );

// Start Class
class Ocean_Extra_Theme_Panel {
	static $push_monkey_activate;

	const WOO_COMMERCE_ENABLED = 'oe_push_monkey_woo_enabled';

	static $endpointURL;
	static $apiClient;
	static $woocommerce_is_active;
	static $woo_settings;
	static $woo_enabled;

	/**
	 * Start things up
	 */
	public function __construct() {

		// Add panel menu
		add_action( 'admin_menu', 				array( 'Ocean_Extra_Theme_Panel', 'add_page' ), 0 );

		// Add panel submenu
		add_action( 'admin_menu', 				array( 'Ocean_Extra_Theme_Panel', 'add_menu_subpage' ) );

		// Add custom CSS for the theme panel
		add_action( 'admin_enqueue_scripts', 	array( 'Ocean_Extra_Theme_Panel', 'css' ) );

		// Register panel settings
		add_action( 'admin_init', 				array( 'Ocean_Extra_Theme_Panel', 'register_settings' ) );

		if ( self::get_push_monkey_account_key() ) {

			add_action( 'wp_head', 				array( 'Ocean_Extra_Theme_Panel', 'add_push_monkey_manifest' ) );
			add_action( 'init', 				array( 'Ocean_Extra_Theme_Panel', 'enqueue_push_monkey_scripts' ) );

		}

		add_action( 'admin_init',  				array( 'Ocean_Extra_Theme_Panel', 'handle_action' ) );

	    // Theme panel push monkey disable notice
	    if ( self::get_push_monkey_account_key() ) {
			add_action( 'admin_menu', 			array( 'Ocean_Extra_Theme_Panel', 'add_woo_page' ), 999 );
		    add_action( 'init', 				array( 'Ocean_Extra_Theme_Panel', 'ocean_process_form' ) );
	    }
	    
	    // wc notice hide
	   	if ( isset( $_GET['notice'] ) && ( $_GET['notice'] =="wc_notice" ) ) {
	   		update_option( 'wc_notice_hide', 1 );
	   	}

		if ( is_ssl() ) {
			self::$endpointURL = "https://www.getpushmonkey.com";
		} else {
			self::$endpointURL = "http://www.getpushmonkey.com";
		}

		self::$apiClient = new Ocean_Push_Monkey_Client( self::$endpointURL );

		// WooCommerce
		self::$woocommerce_is_active = false;
		self::$woo_settings = NULL;
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			self::$woocommerce_is_active = true;
			self::$woo_settings = self::$apiClient->get_woo_settings( self::account_key() );
		}
		self::$woo_enabled = get_option( self::WOO_COMMERCE_ENABLED, false );
		
		if ( ( isset( $_GET['page'] ) ) && ( $_GET['page'] == "oceanwp-panel-woocommerce" ) ) {
			add_action( 'admin_enqueue_scripts', array( 'Ocean_Extra_Theme_Panel', 'woo_setting_css' ) );
		}

		// Load addon files
		self::load_addons();

	}

	/**
	 * Checks if an Account Key is stored.
	 * @return boolean
	 * @since 1.4.0
	 */
	public function has_account_key() {
		if( self::account_key() ) {
			return true;
		}
		return false;
	}

	/**
	 * Returns the stored Account Key.
	 * @return string - the Account Key
	 * @since 1.4.0
	 */
	public function account_key() {
		$account_key = self::get_push_monkey_account_key();
		if( ! self::account_key_is_valid( $account_key ) ) {
			return NULL;
		}
		return $account_key;
	}

	/**
	 * Checks if an Account Key is valid.
	 * @param string $account_key - the Account Key checked.
	 * @return boolean
	 * @since 1.4.0
	 */
	public static function account_key_is_valid( $account_key ) {
		if( ! strlen( $account_key ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Central point to process forms.
	 *
	 * @since 1.4.0
	 */
	public static function ocean_process_form() {
		if ( isset( $_POST['push_monkey_woo_settings'] ) ) {
			self::process_woo_settings( $_POST, $_FILES );
		}
	}

	/**
	 * Process the WooCommerce settings form.
	 *
	 * @since 1.4.0
	 */
	public static function process_woo_settings( $post, $files ) {
		$account_key = self::account_key();
		$title = $post['abandoned_cart_title'];
		$message = $post['abandoned_cart_message'];
		$delay = $post['abandoned_cart_delay'];
		$image = NULL;
		$image_path = NULL;
		if ( !empty( $files["abandoned_cart_image"]["name"] ) ) {
			$image_path = $files["abandoned_cart_image"]["tmp_name"];
			$image = $files["abandoned_cart_image"]["name"];
		}
		$woo_enabled_field = false;
		if ( isset( $post['push_monkey_woo_enabled'] ) ) {
			$woo_enabled_field = true;
 		}
 		update_option( self::WOO_COMMERCE_ENABLED, $woo_enabled_field );
		$updated = self::$apiClient->update_woo_settings( $account_key, $delay, $title, $message, $image_path, $image );
		if ( $updated ) {
			add_action( 'admin_notices', array( 'Ocean_Extra_Theme_Panel', 'woo_settings_notice' ) );
			self::$woo_settings = self::$apiClient->get_woo_settings( self::account_key() );
		}
		self::$woo_enabled = get_option( self::WOO_COMMERCE_ENABLED, false );
	}

	/**
	 * Admin notice to confirm that the Woo settings have been saved.
	 *
	 * @since 1.4.0
	 */
	public static function woo_settings_notice() {
		echo sprintf( esc_html__( '%1$s%2$sAbandoned cart settings saved! *woohoo*%3$s%4$sDismiss this notice.%5$s', 'ocean-extra' ), '<div class="notice notice-info is-dismissible">', '<p>', '</p>', '<button type="button" class="notice-dismiss"><span class="screen-reader-text">', '</span></button></div>' );
	}

	/**
	 * Return customizer panels
	 *
	 * @since 1.0.8
	 */
	private static function get_panels() {

		$panels = array(
			'oe_general_panel' => array(
				'label'     => esc_html__( 'General Panel', 'ocean-extra' ),
			),
			'oe_typography_panel' => array(
				'label'     => esc_html__( 'Typography Panel', 'ocean-extra' ),
			),
			'oe_topbar_panel' => array(
				'label'     => esc_html__( 'Top Bar Panel', 'ocean-extra' ),
			),
			'oe_header_panel' => array(
				'label'     => esc_html__( 'Header Panel', 'ocean-extra' ),
			),
			'oe_blog_panel' => array(
				'label'     => esc_html__( 'Blog Panel', 'ocean-extra' ),
			),
			'oe_sidebar_panel' => array(
				'label'     => esc_html__( 'Sidebar Panel', 'ocean-extra' ),
			),
			'oe_footer_widgets_panel' => array(
				'label'     => esc_html__( 'Footer Widgets Panel', 'ocean-extra' ),
			),
			'oe_footer_bottom_panel' => array(
				'label'     => esc_html__( 'Footer Bottom Panel', 'ocean-extra' ),
			),
			'oe_custom_code_panel' => array(
				'label'     => esc_html__( 'Custom CSS/JS Panel', 'ocean-extra' ),
			),
		);

		// Apply filters and return
		return apply_filters( 'oe_theme_panels', $panels );

	}

	/**
	 * Return customizer options
	 *
	 * @since 1.0.8
	 */
	private static function get_options() {

		$options = array(
			'custom_logo' => array(
				'label'    	=> esc_html__( 'Upload your logo', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Add your own logo and retina logo used for retina screens.', 'ocean-extra' ),
			),
			'site_icon' => array(
				'label'    	=> esc_html__( 'Add your favicon', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'The favicon is used as a browser and app icon for your website.', 'ocean-extra' ),
			),
			'ocean_primary_color' => array(
				'label'    	=> esc_html__( 'Choose your primary color', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Replace the default primary and hover color by your own colors.', 'ocean-extra' ),
			),
			'ocean_typography_panel' => array(
				'label'    	=> esc_html__( 'Choose your typography', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Choose your own typography for any parts of your website.', 'ocean-extra' ),
				'panel' 	=> true,
			),
			'ocean_top_bar' => array(
				'label'    	=> esc_html__( 'Top bar options', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Enable/Disable the top bar, add your own paddings and colors.', 'ocean-extra' ),
			),
			'ocean_header_style' => array(
				'label'    	=> esc_html__( 'Header options', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Choose the style, the height and the colors for your site header.', 'ocean-extra' ),
			),
			'ocean_footer_widgets' => array(
				'label'    	=> esc_html__( 'Footer widgets options', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Choose the columns number, paddings and colors for the footer widgets.', 'ocean-extra' ),
			),
			'ocean_footer_bottom' => array(
				'label'    	=> esc_html__( 'Footer bottom options', 'ocean-extra' ),
				'desc'     	=> esc_html__( 'Add your copyright, paddings and colors for the footer bottom.', 'ocean-extra' ),
			),
		);

		// Apply filters and return
		return apply_filters( 'oe_customizer_options', $options );

	}

	/**
	 * Registers a new menu page
	 *
	 * @since 1.0.0
	 */
	public static function add_page() {
	  	add_menu_page(
			esc_html__( 'Theme Panel', 'ocean-extra' ),
			'Theme Panel', // This menu cannot be translated because it's used for the $hook prefix
			'manage_options',
			'oceanwp-panel',
			'',
			'dashicons-admin-generic',
			null
		);
	}

	/**
	* Add sub menu page
	*
	* @since 1.4.0
	*/
	public static function add_woo_page() {
	    add_submenu_page(
	        'oceanwp-panel',
	        esc_html__( 'Woocommerce', 'ocean-extra' ),
	        esc_html__( 'Woocommerce', 'ocean-extra' ),
	        'manage_options',
	        'oceanwp-panel-woocommerce',
	        array( 'Ocean_Extra_Theme_Panel', 'woocommerce_panel_html' )
	    );
	}

	/**
	 * Registers a new submenu page
	 *
	 * @since 1.0.0
	 */
	public static function add_menu_subpage(){
		add_submenu_page(
			'oceanwp-general',
			esc_html__( 'General', 'ocean-extra' ),
			esc_html__( 'General', 'ocean-extra' ),
			'manage_options',
			'oceanwp-panel',
			array( 'Ocean_Extra_Theme_Panel', 'create_admin_page' )
		);
	}

	/**
	 * Register a setting and its sanitization callback.
	 *
	 * @since 1.0.0
	 */
	public static function register_settings() {
		register_setting( 'oe_panels_settings', 'oe_panels_settings', array( 'Ocean_Extra_Theme_Panel', 'validate_panels' ) );
		register_setting( 'oceanwp_options', 'oceanwp_options', array( 'Ocean_Extra_Theme_Panel', 'admin_sanitize_license_options' ) ); 
		register_setting( 'oe_push_monkey_account_key', 'oe_push_monkey_account_key', array( 'Ocean_Extra_Theme_Panel', 'validate_push_monkey_account_key' ) );
	}

	/**
	 * Validate Settings Options
	 * 
	 * @since 1.0.0
	 */
	public static function admin_sanitize_license_options( $input ) {

		// filter to save all settings to database
        $oceanwp_options = get_option( 'oceanwp_options' );
        if ( isset( $input['licenses'] ) && ! empty( $input['licenses'] ) ) {
            foreach ( $input['licenses'] as $key => $value ) {
                if ( $oceanwp_options['licenses'][$key] ) {
                    if ( strpos( $value, "XXX" ) !== FALSE && isset( $oceanwp_options['licenses'][$key] ) ) {
                        $input['licenses'][$key] = $oceanwp_options['licenses'][$key];
                    }
                }
            }
        }

		return $input;
	}

	/**
	 * Main Sanitization callback
	 *
	 * @since 1.2.2
	 */
	public static function validate_panels( $settings ) {

		// Get panels array
		$panels = self::get_panels();

		foreach ( $panels as $key => $val ) {

			$settings[$key] = ! empty( $settings[$key] ) ? true : false;

		}

		// Return the validated/sanitized settings
		return $settings;

	}

	/**
	 * Get settings.
	 *
	 * @since 1.2.2
	 */
	public static function get_setting( $option = '' ) {

		$defaults = self::get_default_settings();

		$settings = wp_parse_args( get_option( 'oe_panels_settings', $defaults ), $defaults );

		return isset( $settings[ $option ] ) ? $settings[ $option ] : false;

	}

	/**
	 * Get default settings value.
	 *
	 * @since 1.2.2
	 */
	public static function get_default_settings() {

		// Get panels array
		$panels = self::get_panels();

		// Add array
		$default = array();

		foreach ( $panels as $key => $val ) {
			$default[$key] = 1;
		}

		// Return
		return apply_filters( 'oe_default_panels', $default );

	}

	/**
	 * Sanitize and activate Push Monkey
	 *
	 * @since 1.4.0
	 */
	public static function validate_push_monkey_account_key( $account_key ) {
		$old_account_key = get_option( 'oe_push_monkey_account_key' );
		$url = 'https://getpushmonkey.com/v2/api/verify';
		$args = array( 'body' => array( 'account_key' => $account_key ) );
		$response = wp_remote_post( $url, $args );
		if ( ! is_wp_error( $response ) ) {
			
			$body = wp_remote_retrieve_body( $response );
			$output = json_decode( $body );
			if ( $output->response == "ok" ) {
				return $account_key;
			}
		}
	    add_settings_error(
			'oe_push_monkey',
			esc_attr( 'settings_updated' ),
			__( 'The Account Key seems to be invalid.', 'ocean-extra' ),
			'error'
		);
		return false;

	}

	/**
	 * Get Push Monkey key
	 *
	 * @since 1.4.0
	 */	
	public static function get_push_monkey_account_key() {
		return get_option( 'oe_push_monkey_account_key', '' );
	}

	/**
	 * Handle the 'action' query parameter
	 *
	 * @since 1.4.0
	 */
	public static function handle_action() {

		if ( ! isset( $_GET['action'] ) ) {
			return;
		} 

		$action = $_GET['action'];
		switch ( $action ) {
			case 'oe_push_monkey_deactivate':
				self::deactivate_push_monkey();
		}

	}

	/**
	 * Deactivate Push Monkey
	 *
	 * @since 1.4.0
	 */
	public static function deactivate_push_monkey() {
		delete_option( 'wc_notice_hide' );
		delete_option( 'oe_push_monkey_account_key' );
		// Push Notifications
		$url = add_query_arg(
			array(
				'page' 	=> 'oceanwp-panel',
				'tab' 	=> 'push-notifications',
			),
			'admin.php'
		);
		wp_redirect( $url );
		exit;
	}

	/**
	* Add a custom <link> tag for the manifest
	*
	* @since 1.4.0
	*/
	public static function add_push_monkey_manifest() {
		echo '<link rel="manifest" href="' . plugins_url( '/assets/js/manifest.json', __FILE__ ) . '">';
	}

	/**
	* Add the JS for Push Notifications
	*
	* @since 1.4.0
	*/
	public static function enqueue_push_monkey_scripts() {

		if ( is_admin() ) return;

		$account_key = self::get_push_monkey_account_key();
		if ( ! $account_key ) return;

		$url = "https://www.getpushmonkey.com/sdk/config-".$account_key.".js?subdomain_forced=1";
		wp_enqueue_script( 'push_monkey_sdk', $url, array( 'jquery' ) );
	}

	/**
	 * Settings page sidebar
	 *
	 * @since 1.4.0
	 */
	public static function admin_page_sidebar() {

		// Images url
		$octify = OE_URL . '/includes/panel/assets/img/octify.png';
		$kinsta = OE_URL . '/includes/panel/assets/img/kinsta.png'; ?>

		<div class="oceanwp-bloc oceanwp-review">
			<h3><?php esc_html_e( 'Are you a helpful person?', 'ocean-extra' ); ?></h3>
			<div class="content-wrap">
				<p class="content"><?php esc_html_e( 'I&rsquo;m grateful that you&rsquo;ve decided to join the OceanWP family. If I could take 2 min of your time, I&rsquo;d really appreciate if you could leave a review. By spreading the love, we can create even greater free stuff in the future!', 'ocean-extra' ); ?></p>
				<a href="https://wordpress.org/support/theme/oceanwp/reviews/#new-post" class="button owp-button" target="_blank"><?php esc_html_e( 'Leave my review', 'ocean-extra' ); ?></a>
				<p class="bottom-text"><?php esc_html_e( 'Thank you very much!', 'ocean-extra' ); ?></p>
			</div>
			<i class="dashicons dashicons-wordpress"></i>
		</div>

		<div class="oceanwp-bloc oceanwp-octify">
			<p class="owp-img">
				<a href="https://goo.gl/CyYJ5C" target="_blank">
					<img src="<?php echo esc_url( $octify ); ?>" alt="Image Compressor" />
				</a>
			</p>
			<div class="content-wrap">
				<p class="content"><?php esc_html_e( 'Octify is the perfect image compressor plugin, a must-have for any site. Gain in speed by reducing your images weight without losing quality.', 'ocean-extra' ); ?></p>
				<a href="https://goo.gl/CyYJ5C" class="button owp-button" target="_blank"><?php esc_html_e( 'Check Octify Now', 'ocean-extra' ); ?></a>
			</div>
			<i class="dashicons dashicons-format-image"></i>
		</div>

		<div class="oceanwp-bloc oceanwp-kinsta">
			<p class="owp-img">
				<a href="https://goo.gl/Xp7XJy" target="_blank">
					<img src="<?php echo esc_url( $kinsta ); ?>" alt="Kinsta Hosting" />
				</a>
			</p>
			<div class="content-wrap">
				<p class="content"><?php echo sprintf( esc_html__( 'A fast theme is even better with a great host!%1$sOceanWP proudly recommends Kinsta to anyone looking for speed, security, and fast support.', 'ocean-extra' ), '<br>' ); ?></p>
				<a href="https://goo.gl/Xp7XJy" class="button owp-button" target="_blank"><?php esc_html_e( 'Check Kinsta Hosting', 'ocean-extra' ); ?></a>
			</div>
			<i class="dashicons dashicons-cloud"></i>
		</div>

		<div class="oceanwp-buttons">
			<a href="https://www.youtube.com/c/OceanWP" class="button owp-button owp-yt-btn" target="_blank"><?php esc_html_e( 'YouTube Videos', 'ocean-extra' ); ?></a>
			<a href="http://docs.oceanwp.org/" class="button owp-button owp-doc-btn" target="_blank"><?php esc_html_e( 'Documentation', 'ocean-extra' ); ?></a>
			<a href="https://oceanwp.org/support/" class="button owp-button owp-support-btn" target="_blank"><?php esc_html_e( 'Open a Support Ticket', 'ocean-extra' ); ?></a>
		</div>

	<?php
	}

	/**
	 * Settings page output
	 *
	 * @since 1.0.0
	 */
	public static function create_admin_page() {

		// Get panels array
		$theme_panels = self::get_panels();

		// Get options array
		$options = self::get_options();

		// Push monkey img url
		$push_monkey = OE_URL . '/includes/panel/assets/img/push-monkey-devices.png'; ?>

		<div class="wrap oceanwp-theme-panel clr">

			<h1><?php esc_attr_e( 'Theme Panel', 'ocean-extra' ); ?></h1>

			<h2 class="nav-tab-wrapper">
				<?php
				//Get current tab
				$curr_tab	= !empty( $_GET['tab'] ) ? $_GET['tab'] : 'features';

				// Feature url
				$feature_url = add_query_arg(
					array(
						'page' 	=> 'oceanwp-panel',
						'tab' 	=> 'features',
					),
					'admin.php'
				);

				// Push Notifications
				$push_notification_url = add_query_arg(
					array(
						'page' 	=> 'oceanwp-panel',
						'tab' 	=> 'push-notifications',
					),
					'admin.php'
				);

				// Deactivate Push Notifications
				$deactivate_push_notification_url = add_query_arg(
					array(
						'page' 	=> 'oceanwp-panel',
						'tab' 	=> 'push-notifications',
						'action' => 'oe_push_monkey_deactivate',
					),
					'admin.php'
				); ?>

				<?php do_action( 'ocean_theme_panel_before_tab' ); ?>

				<a href="<?php echo esc_url( $feature_url ); ?>" class="nav-tab <?php echo $curr_tab == 'features' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Features', 'ocean-extra' ); ?></a>

				<?php do_action( 'ocean_theme_panel_inner_tab' ); ?>

				<a href="<?php echo esc_url( $push_notification_url ); ?>" class="push-monkey-tab nav-tab <?php echo $curr_tab == 'push-notifications' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Push Notifications', 'ocean-extra' ); ?></a>

				<?php do_action( 'ocean_theme_panel_after_tab' ); ?>
			</h2>

			<?php do_action( 'ocean_theme_panel_before_content' ); ?>

			<div class="oceanwp-settings clr" <?php echo $curr_tab == 'features' ? '' : 'style="display:none;"'; ?>>

				<?php
				if ( true != apply_filters( 'oceanwp_theme_panel_sidebar_enabled', false ) ) { ?>

					<div class="oceanwp-sidebar right clr">

						<?php self::admin_page_sidebar(); ?>

						<?php do_action( 'oe_panels_sidebar_after' ); ?>

					</div>

				<?php } ?>

				<div class="left clr">

					<form id="oceanwp-theme-panel-form" method="post" action="options.php">

						<?php settings_fields( 'oe_panels_settings' ); ?>

						<div class="oceanwp-panels clr">

							<h2 class="oceanwp-title"><?php esc_html_e( 'Customizer Sections', 'ocean-extra' ); ?></h2>

							<p class="oceanwp-desc"><?php esc_html_e( 'Disable the Customizer panels that you do not have or need anymore to load it quickly. Your settings are saved, so do not worry.', 'ocean-extra' ); ?></p>

							<?php
							// Loop through theme pars and add checkboxes
							foreach ( $theme_panels as $key => $val ) :

								// Var
								$label  = isset ( $val['label'] ) ? $val['label'] : '';
								$desc  	= isset ( $val['desc'] ) ? $val['desc'] : '';

								// Get settings
								$settings = self::get_setting( $key ); ?>

								<div id="<?php echo esc_attr( $key ); ?>" class="column-wrap clr">

									<label for="oceanwp-switch-[<?php echo esc_attr( $key ); ?>]" class="column-name clr">
										<h3 class="title"><?php echo esc_attr( $label ); ?></h3>
									    <input type="checkbox" name="oe_panels_settings[<?php echo esc_attr( $key ); ?>]" value="true" id="oceanwp-switch-[<?php echo esc_attr( $key ); ?>]" <?php checked( $settings ); ?>>
										<?php if ( $desc ) { ?>
											<div class="desc"><?php echo esc_attr( $desc ); ?></div>
										<?php } ?>
									</label>

								</div>

							<?php endforeach; ?>

							<?php submit_button(); ?>

						</div>

					</form>

					<?php do_action( 'oe_theme_panel_after' ); ?>

					<div class="divider clr"></div>

					<div class="oceanwp-options clr">

						<h2 class="oceanwp-title"><?php esc_html_e( 'Getting started', 'ocean-extra' ); ?></h2>

						<p class="oceanwp-desc"><?php esc_html_e( 'Take a look in the options of the Customizer and see yourself how easy and quick to customize your website as you wish.', 'ocean-extra' ); ?></p>

						<div class="options-inner clr">

							<?php
							// Loop through options
							foreach ( $options as $key => $val ) :

								// Var
								$label  = isset ( $val['label'] ) ? $val['label'] : '';
								$desc  	= isset ( $val['desc'] ) ? $val['desc'] : '';
								$panel  = isset ( $val['panel'] ) ? $val['panel'] : false;
								$id   	= $key;

								if ( true == $panel ) {
									$focus = 'panel';
								} else {
									$focus = 'control';
								} ?>

								<div class="column-wrap">

									<div class="column-inner clr">

										<h3 class="title"><?php echo esc_attr( $label ); ?></h3>
										<?php if ( $desc ) { ?>
											<p class="desc"><?php echo esc_attr( $desc ); ?></p>
										<?php } ?>

										<div class="bottom-column">
											<a class="option-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus['. $focus .']='. $id .'' ) ); ?>" target="_blank"><?php esc_html_e( 'Go to the option', 'ocean-extra' ); ?></a>
										</div>

									</div>

								</div>

							<?php endforeach; ?>

						</div><!-- .options-inner -->

					</div>

				</div>

			</div><!-- .oceanwp-settings -->

			<?php do_action( 'ocean_theme_panel_inner_content' ); ?>

			<div class="oceanwp-settings clr" <?php echo $curr_tab == 'push-notifications' ? '' : 'style="display:none;"'; ?>>

				<?php
				if ( true != apply_filters( 'oceanwp_theme_panel_sidebar_enabled', false ) ) { ?>

					<div class="oceanwp-sidebar right clr">

						<?php self::admin_page_sidebar(); ?>

					</div>

				<?php } ?>

				<div class="left clr">

					<form method="post" action="options.php">

						<?php settings_fields( 'oe_push_monkey_account_key' ); ?>

						<div class="oceanwp-panels push-monkey clr">

							<h2 class="oceanwp-title"><?php esc_html_e( 'Push Notifications', 'ocean-extra' ); ?></h2>

							<div class="right pm-right clr">
								<img src="<?php echo esc_url( $push_monkey ); ?>" alt="Push Monkey" />
							</div>

							<div class="left pm-left clr">

								<p><?php echo sprintf( esc_html__( 'Push notifications allow you to send news or offers directly to your subscriber\'s mobiles or desktops, even when they are not browsing your website! Visit %1$sgetpushmonkey.com%2$s to learn more.%3$sStart increasing sales, visitor retention and even re-engage subscribers.', 'ocean-extra' ), '<a href="https://getpushmonkey.com/af/OceanWP" target="_blank">', '</a>', '<br />' ); ?></p>

								<p><?php echo sprintf( esc_html__( '%1$sBefore%2$s you enable push notifications for your website, you need to create a free account on %3$sgetpushmonkey.com%4$s. %5$sClick here >%6$s to create one now. %7$sNo credit cards required!%8$s', 'ocean-extra' ), '<strong>', '</strong>', '<a href="https://getpushmonkey.com/af/register/OceanWP" target="_blank">', '</a>', '<a href="https://getpushmonkey.com/af/register/OceanWP" class="button" target="_blank">', '</a>', '<strong>', '</strong>' ); ?></p>

								<p><?php echo sprintf( esc_html__( 'After creating an account, copy your %1$sAccount Key%2$s from the Installation page and paste it in the field below. Check this article to see %3$show to get your account key%4$s.%5$sYou can then manage your account and see more options by signing in at %6$sgetpushmonkey.com%7$s.', 'ocean-extra' ), '<strong>', '</strong>', '<a href="http://docs.oceanwp.org/article/493-how-to-get-your-push-monkey-account-key" target="_blank">', '</a>', '<br />', '<a href="https://getpushmonkey.com/af/OceanWP" target="_blank">', '</a>' ); ?></p>
								
								<table class="form-table">
								    <tbody>
								    	<tr>
									        <th scope="row">
												<label for="oe_push_monkey_account_key"><?php esc_html_e( 'Account Key', 'ocean-extra' ); ?></label>
									        </th>
									        <td>
									        	<input name="oe_push_monkey_account_key" type="text" id="oe_push_monkey_account_key" value="<?php echo self::get_push_monkey_account_key(); ?>" class="regular-text">
									        </td>  		
								    	</tr>
								    </tbody>
								</table>

								<?php
								// Label
								$label = esc_html__( 'Activate Your Key', 'ocean-extra' );
								if ( self::get_push_monkey_account_key() ) {
									$label = esc_html__( 'Update Your Key', 'ocean-extra' );
								} ?>

								<p>
									<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $label; ?>">
									<?php if ( self::get_push_monkey_account_key() ) { ?>
										<a class="button" href="<?php echo $deactivate_push_notification_url; ?>"><?php esc_html_e( 'Deactivate Your Key', 'ocean-extra' ); ?></a>
									<?php } ?>
								</p>

							</div>

						</div>

					</form>

				</div>

			</div><!-- .oceanwp-settings -->

			<?php do_action( 'ocean_theme_panel_after_content' ); ?>

		</div>

	<?php
	}

	/**
	 * Woo page output
	 *
	 * @since 1.4.0
	 */
	public static function woocommerce_panel_html() { ?>

		<div class="push-monkey push-monkey-bootstrap">
			<div class="container-fluid">
	    		<div class="panel panel-default">
	    			<div class="panel-heading">
	    				<h3 class="panel-title"><?php esc_attr_e( 'WooCommerce + Push Monkey', 'ocean-extra' ); ?></h3>
	    			</div>
	    			<div class="panel-body">
	    				<?php if ( ! self::$woocommerce_is_active ) { ?>
		    				<h3><?php esc_attr_e( 'Did you know Push Monkey works seamlessly with WooCommerce?', 'ocean-extra' ); ?></h3>
		    				<p>
		    					<?php echo sprintf( esc_html__( 'The %1$sAbandoned Cart%2$s feature reminds your visitor about shopping carts that they did not check out.', 'ocean-extra' ), '<strong>', '</strong>' ); ?>
		    				</p>
		    				<p>
		    					<?php esc_attr_e( 'Install and activate WooCommerce to take full advantage of this feature.', 'ocean-extra' ); ?>
		    				</p>
		    			<?php } else { ?>
		    				<h3><?php esc_attr_e( 'Abandoned Shopping Cart.', 'ocean-extra' ); ?></h3>
		    				<p>
		    					<?php esc_attr_e( 'This will remind your visitors if they did not check out their shopping cart.', 'ocean-extra' ); ?>
		    				</p>     
	    				<?php } ?>
	    			</div>
	    		</div>
	    		<?php if ( self::$woocommerce_is_active ) { ?>
		    		<form class="push_monkey_woo_settings" name="push_monkey_woo_settings" enctype="multipart/form-data" method="post" class="form-horizontal">
		    			<div class="panel panel-success">
		    				<div class="panel-heading">
		    					<h3 class="panel-title"><?php esc_attr_e( 'Abandoned Cart Options', 'ocean-extra' ); ?></h3>
		    				</div>
		    				<div class="panel-body">

		    					<div class="form-group clearfix">
		    						<label class="col-md-3 control-label">
		    							<?php esc_attr_e( 'Enable Abandoned Cart Notifications', 'ocean-extra' ); ?>
		    						</label>
		    						<div class="col-md-3">
		    							<label class="switch">
		    								<input type="checkbox" class="switch" name="push_monkey_woo_enabled" <?php if ( self::$woo_enabled ) { ?> checked="true" <?php } ?>
		    								>
		    								<span></span>
		    							</label>
		    							<span class="help-block"><?php esc_attr_e( 'Enable or disable this feature.', 'ocean-extra' ); ?></span>
		    						</div>
		    					</div>

		    					<div class="form-group clearfix">
		    						<label class="col-md-3 col-xs-12 control-label" for="push-monkey-abandoned-delay">
		    							<?php esc_attr_e( 'Abandoned Cart Delay', 'ocean-extra' ); ?>
		    						</label>
		    						<div class="col-md-4 col-xs-12">
		    							<input type="number" value="<?php echo self::$woo_settings['abandoned_cart_delay']; ?>" name="abandoned_cart_delay" id="push-monkey-abandoned-delay" class="form-control" min="0" step="1" />
		    							<span class="help-block">
			    							<?php echo sprintf( esc_html__( 'The number of %1$sminutes%2$s after which the abandoned cart reminder push notification is sent.', 'ocean-extra' ), '<strong>', '</strong>' ); ?>
		    							</span>
		    						</div>
		    					</div>

		    					<div class="form-group clearfix">
		    						<label class="col-md-3 col-xs-12 control-label" for="push-monkey-abandoned-title">
		    							<?php esc_attr_e( 'Abandoned Cart Title', 'ocean-extra' ); ?>
		    						</label>
		    						<div class="col-md-6 col-xs-12">
		    							<input type="text" value="<?php echo self::$woo_settings['abandoned_cart_title']; ?>" name="abandoned_cart_title" id="push-monkey-abandoned-title" class="form-control" maxlength="30"/>
		    							<span class="help-block">
		    								<?php esc_attr_e( 'The title of the abandoned cart reminder push notifications.', 'ocean-extra' ); ?>
		    							</span>
		    						</div>
		    					</div>

		    					<div class="form-group clearfix">
		    						<label class="col-md-3 col-xs-12 control-label" for="push-monkey-abandoned-message">
		    							<?php esc_attr_e( 'Abandoned Cart Message', 'ocean-extra' ); ?>
		    						</label>
		    						<div class="col-md-6 col-xs-12">
		    							<textarea name="abandoned_cart_message" id="push-monkey-abandoned-message" class="form-control" rows="3" maxlength="120"><?php echo self::$woo_settings['abandoned_cart_message']; ?></textarea>
		    							<span class="help-block">
		    								<?php esc_attr_e( 'The message of the abandoned cart reminder push notifications.', 'ocean-extra' ); ?>
		    							</span>
		    						</div>
		    					</div>

		    					<div class="form-group clearfix">
		    						<label class="col-md-3 col-xs-12 control-label" for="push-monkey-abandoned-image">
		    							<?php esc_attr_e( 'Abandoned Cart Image', 'ocean-extra' ); ?>
		    						</label>
		    						<div class="col-md-6 col-xs-12">
		    							<input type="file" class="fileinput btn-primary"  value="24" name="abandoned_cart_image" id="push-monkey-abandoned-image"/>
		    							<span class="help-block">
		    								<?php esc_attr_e( 'The image of the abandoned cart reminder push notifications. Recommended size 675px x 506px.', 'ocean-extra' ); ?>
		    							</span>
		    							<?php if ( self::$woo_settings['abandoned_cart_image'] ) {?>
		    							<br />
		    							<p><?php esc_attr_e( 'Your current image:', 'ocean-extra' ); ?></p>
		    							<img style="width: 337px; height: 253px" src="https://getpushmonkey.com/<?php echo self::$woo_settings['abandoned_cart_image']; ?>" />
		    							<?php } ?>
		    						</div>
		    					</div>                            

		    				</div>

		    				<div class="panel-footer">
		    					<button type="submit" name="push_monkey_woo_settings" class="btn btn-primary pull-right"><?php esc_attr_e( 'Save', 'ocean-extra' ); ?></button>
		    				</div>      

		    			</div>
		    		</form>
	    		<?php }?>
	    	</div>
	    </div>

	<?php
	}

	public static function woo_setting_css() {
		// Css Add
		wp_enqueue_style( 'oe-woo-styles', plugins_url( '/assets/css/push-monkey.min.css', __FILE__ ) );
	}

	/**
	 * Include addons
	 *
	 * @since 1.0.0
	 */
	private static function load_addons() {

		// Addons directory location
		$dir = OE_PATH .'/includes/panel/';

		if ( is_admin() ) {

			// Import/Export
			require_once( $dir .'import-export.php' );

			// Extensions
			require_once( $dir .'extensions.php' );

			// Licenses
			require_once( $dir .'licenses.php' );

		}

		// Scripts panel - if minimum PHP 5.6
		if ( version_compare( PHP_VERSION, '5.6', '>=' ) ) {
			require_once( $dir .'scripts.php' );
		}

	}

	/**
	 * Theme panel CSS
	 *
	 * @since 1.0.0
	 */
	public static function css( $hook ) {

		// Only load scripts when needed
		if ( 'toplevel_page_oceanwp-panel' != $hook ) {
			return;
		}

		// CSS
		wp_enqueue_style( 'oceanwp-theme-panel', plugins_url( '/assets/css/panel.min.css', __FILE__ ) );

	}

}
new Ocean_Extra_Theme_Panel();