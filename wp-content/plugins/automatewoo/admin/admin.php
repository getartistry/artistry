<?php

namespace AutomateWoo;

/**
 * @class Admin
 */
class Admin {

	static function init() {
		$self = 'AutomateWoo\Admin'; /** @var $self Admin (for IDE) */

		Admin_Ajax::init();

		add_action( 'current_screen', [ $self, 'includes' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'register_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_scripts' ], 20 );
		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_styles' ], 20 );
		add_action( 'admin_menu', [ $self, 'admin_menu' ] );
		add_action( 'admin_footer', [ $self, 'replace_top_level_menu' ] );
		add_action( 'admin_notices', [ $self, 'license_notices' ] );
		add_action( 'admin_head', [ $self, 'menu_highlight' ] );

		add_filter( 'admin_body_class', [ $self, 'body_class' ] );
		add_filter( 'woocommerce_reports_screen_ids', [ $self, 'inject_woocommerce_reports_screen_ids' ] );
		add_filter( 'editor_stylesheets', [ $self, 'add_editor_styles' ] );

		add_action( 'current_screen', [ $self, 'screen_options' ] );
		add_filter( 'set-screen-option', [ $self, 'save_screen_option' ], 10, 3 );

		if ( aw_request( 'action' ) === 'automatewoo-settings' ) {
			add_action( 'wp_loaded', [ $self, 'save_settings' ] );
		}

		if ( aw_request( 'automatewoo-email-preview-loader' ) ) {
			add_action( 'wp_loaded', [ $self, 'email_preview_loader' ] );
		}
	}


	static function includes() {

		switch ( self::get_screen_id() ) {
			case 'aw_workflow' :
				include 'workflow-edit.php';
				break;

			case 'edit-aw_workflow' :
				include 'workflow-list.php';
				break;

			case 'edit-shop_coupon' :
				include 'coupons-list.php';
				break;
		}
	}


	static function screen_options() {
		switch ( $id = self::get_screen_id() ) {
			case 'logs' :
			case 'carts' :
			case 'guests' :
			case 'queue' :
			case 'unsubscribes' :
				add_screen_option( 'per_page', [
				   'option' => "automatewoo_{$id}_per_page",
				   'default' => 20
				]);
				break;
		}
	}


	/**
	 * @param $status
	 * @param string $option
	 * @param $value
	 * @return string
	 */
	static function save_screen_option( $status, $option, $value ) {
		$options = [
		   'automatewoo_logs_per_page',
		   'automatewoo_carts_per_page',
		   'automatewoo_queue_per_page',
		   'automatewoo_guests_per_page',
		   'automatewoo_unsubscribes_per_page'
		];

		if ( in_array( $option, $options ) ) {
		   return Clean::string( $value );
		}
	}


	static function admin_menu() {

		$sub_menu = [];
		$position = '55.6324'; // fix for rare position clash bug

		add_menu_page( __( 'AutomateWoo', 'automatewoo' ), __( 'AutomateWoo', 'automatewoo' ), 'manage_woocommerce', 'automatewoo', [ 'AutomateWoo\Admin', 'load_controller' ], 'none', $position );

		if ( Licenses::is_active() ) {

			$sub_menu['dashboard'] = [
				'title' => __( 'Dashboard', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['workflows'] = [
				'title' => __( 'Workflows', 'automatewoo' ),
				'slug' => 'edit.php?post_type=aw_workflow'
			];

			$sub_menu['logs'] = [
				'title' => __( 'Logs', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['queue'] = [
				'title' => __( 'Queue', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			if ( AW()->options()->abandoned_cart_enabled ) {
				$sub_menu['carts'] = [
					'title' => __( 'Carts', 'automatewoo' ),
					'function' => [ __CLASS__, 'load_controller' ]
				];

				$sub_menu['guests'] = [
					'title' => __( 'Guests', 'automatewoo' ),
					'function' => [ __CLASS__, 'load_controller' ]
				];
			}

			$sub_menu['unsubscribes'] = [
				'title' => __( 'Unsubscribes', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['reports'] = [
				'title' => __( 'Reports', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['tools'] = [
				'title' => __( 'Tools', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['settings'] = [
				'title' => __( 'Settings', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['events'] = [
			   'title' => __( 'Events', 'automatewoo' ),
			   'function' => [ __CLASS__, 'load_controller' ]
			];

		}

		$sub_menu['licenses'] = [
			'title' => _n( 'License', 'Licenses', Addons::has_addons() ? 2 : 1, 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['data-upgrade'] = [
			'title' => __( 'AutomateWoo Data Update', 'automatewoo' ),
			'function' => [ __CLASS__, 'page_data_upgrade' ]
		];

		foreach ( $sub_menu as $key => $item ) {

			if ( empty( $item['function'] ) ) $item['function'] = '';
			if ( empty( $item['capability'] ) ) $item['capability'] = 'manage_woocommerce';
			if ( empty( $item['slug'] ) ) $item['slug'] = 'automatewoo-'.$key;
			if ( empty( $item['page_title'] ) ) $item['page_title'] = $item['title'];

			add_submenu_page( 'automatewoo', $item['page_title'], $item['title'], $item['capability'], $item['slug'], $item['function'] );

			if ( $key === 'workflows' ) {
				do_action( 'automatewoo/admin/submenu_pages', 'automatewoo' );
			}
		}
	}


	/**
	 * Highlight the correct top level admin menu item
	 */
	static function menu_highlight() {
		global $parent_file, $post_type;

		switch ( $post_type ) {
			case 'aw_workflow' :
				$parent_file = 'automatewoo';
				break;
		}
	}


	static function register_scripts() {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$url = AW()->admin_assets_url( '/js' );
			$suffix = '';
		} else {
			$url = AW()->admin_assets_url( '/js/min' );
			$suffix = '.min';
		}

		$vendor_url = AW()->admin_assets_url( '/js/vendor' );

		wp_register_script( 'automatewoo-clipboard', $vendor_url."/clipboard$suffix.js", [], AW()->version );
		wp_register_script( 'jquery-cookie', WC()->plugin_url().'/assets/js/jquery-cookie/jquery.cookie.js', [ 'jquery' ], '1.4.1' );

		wp_register_script( 'automatewoo', $url."/automatewoo$suffix.js", [ 'jquery', 'jquery-ui-datepicker', 'jquery-tiptip', 'backbone', 'underscore' ], AW()->version );
		wp_register_script( 'automatewoo-validate', $url."/validate$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-workflows', $url."/workflows$suffix.js", [ 'automatewoo', 'automatewoo-validate', 'automatewoo-modal', 'wp-util' ], AW()->version );
		wp_register_script( 'automatewoo-variables', $url."/variables$suffix.js", [ 'automatewoo-modal', 'automatewoo-clipboard' ], AW()->version );
		wp_register_script( 'automatewoo-tools', $url."/tools$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-sms-test', $url."/sms-test$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-modal', $url."/modal$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-rules', $url."/rules$suffix.js", [ 'automatewoo', 'automatewoo-workflows' ], AW()->version );
		wp_register_script( 'automatewoo-dashboard', $url."/dashboard$suffix.js", [ 'automatewoo', 'automatewoo-modal', 'jquery-masonry', 'flot', 'flot-resize', 'flot-time', 'flot-pie', 'flot-stack' ], AW()->version );


		global $wp_locale;

		wp_localize_script( 'automatewoo-dashboard', 'automatewooDashboardLocalizeScript', [] );

		wp_localize_script( 'automatewoo-validate', 'automatewooValidateLocalizedErrorMessages', [
			'noVariablesSupport' => __( 'This field does not support variables.', 'automatewoo' ),
			'invalidDataType' => __( "Variable '%s' is not available with the selected trigger. Please only use variables listed in the the variables box.", 'automatewoo' ),
			'invalidVariable' => __( "Variable '%s' is not a valid. Please only use variables listed in the the variables box.", 'automatewoo' )
		] );

		wp_localize_script( 'automatewoo', 'automatewooLocalizeScript', [
			'url' => [
				'admin' => admin_url(),
				'ajax' => admin_url( 'admin-ajax.php' )
			],
			'locale' => [
				'month_abbrev' => array_values( $wp_locale->month_abbrev ),
				'currency_symbol' => get_woocommerce_currency_symbol(),
				'currency_position' => get_option( 'woocommerce_currency_pos' )
			]
		] );
	}


	/**
	 * Enqueue scripts based on screen id
	 */
	static function enqueue_scripts() {

		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		wp_enqueue_script( 'automatewoo' );

		if ( in_array( $screen_id, self::screen_ids() ) ) {
			wp_enqueue_script( 'woocommerce_admin' );
			wp_enqueue_script( 'wc-enhanced-select' );
			wp_enqueue_script( 'jquery-tiptip' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-cookie' );
		}
	}

	static function screen_ids() {

		$ids = [];
		$prefix = 'automatewoo_page_automatewoo';

		$ids[] = "$prefix-logs";
		$ids[] = "$prefix-reports";
		$ids[] = "$prefix-settings";
		$ids[] = "$prefix-tools";
		$ids[] = "$prefix-carts";
		$ids[] = "$prefix-queue";
		$ids[] = "$prefix-guests";
		$ids[] = "$prefix-unsubscribes";
		$ids[] = "$prefix-licenses";
		$ids[] = "$prefix-events";
		$ids[] = 'aw_workflow';

		return apply_filters( 'automatewoo/admin/screen_ids', $ids );
	}

	/**
	 * Load styles earlier than scripts to avoid flash of un-styled workflows UI
	 */
	static function enqueue_styles() {

		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';


		wp_register_style( 'automatewoo-main', AW()->admin_assets_url( '/css/aw-main.css' ), [], AW()->version );
		wp_enqueue_style( 'automatewoo-main' );

		if ( in_array( $screen_id, self::screen_ids() ) ) {
			wp_enqueue_style( 'woocommerce_admin_styles' );
			wp_enqueue_style( 'jquery-ui-style' );
		}
	}

	/**
	 * Dynamic replace top level menu
	 */
	static function replace_top_level_menu() {
		$top_menu_link = Licenses::is_active() ? self::page_url( 'dashboard' ) : self::page_url( 'licenses' );

		?>
	   <script type="text/javascript">
           jQuery('#adminmenu').find('a.toplevel_page_automatewoo').attr('href', '<?php echo $top_menu_link ?>');
	   </script>
		<?php
	}

	/**
	 * @param string $page
	 * @param bool|int $id
	 * @return false|string
	 */
	static function page_url( $page, $id = false ) {

		switch ( $page ) {

			case 'dashboard':
				return admin_url( 'admin.php?page=automatewoo-dashboard' );
				break;

			case 'workflows':
				return admin_url( 'edit.php?post_type=aw_workflow' );
				break;

			case 'settings':
				return admin_url( 'admin.php?page=automatewoo-settings' );
				break;

			case 'licenses':
				return admin_url( 'admin.php?page=automatewoo-licenses' );
				break;

			case 'logs':
				return admin_url( 'admin.php?page=automatewoo-logs' );
				break;

			case 'queue':
				return admin_url( 'admin.php?page=automatewoo-queue' );
				break;

			case 'guests':
				return admin_url( 'admin.php?page=automatewoo-guests' );
				break;

			case 'guest':
				return admin_url( "admin.php?page=automatewoo-guests&action=view&guest_id=$id" );
				break;

			case 'email-tracking':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=email-tracking' );
				break;

			case 'carts':
				return admin_url( 'admin.php?page=automatewoo-carts' );
				break;

			case 'unsubscribes':
				return admin_url( 'admin.php?page=automatewoo-unsubscribes' );
				break;

			case 'conversions':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=conversions' );
				break;

			case 'conversions-list':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=conversions-list' );
				break;

			case 'workflows-report':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=runs-by-date' );
				break;

			case 'tools':
				return admin_url( 'admin.php?page=automatewoo-tools' );
				break;

			case 'status':
				return admin_url( 'admin.php?page=automatewoo-settings&tab=status' );
				break;
		}

		return false;
	}

	static function page_data_upgrade() {
		self::get_view( 'page-data-upgrade' );
	}

	/**
	 * @param $view
	 * @param array $args
	 * @param mixed $path
	 */
	static function get_view( $view, $args = [], $path = false ) {

		if ( $args && is_array( $args ) )
			extract( $args );

		if ( ! $path )
			$path = AW()->admin_path( '/views/' );

		include( $path.$view.'.php' );
	}

	static function load_controller() {
		if ( ! $screen_id = self::get_screen_id() ) {
			return;
		}

		if ( $screen_id == 'toplevel_page_automatewoo' ) {
			$screen_id = 'dashboard';
		}

		if ( $controller = Admin\Controllers::get( $screen_id ) ) {
			$controller->handle();
		}
	}

	/**
	 * @return string|bool
	 */
	static function get_screen_id() {
		if ( ! $screen = get_current_screen() ) {
			return false;
		}

		return str_replace( 'automatewoo_page_automatewoo-', '', $screen->id );
	}

	/**
	 * Save settings on wp_loaded
	 */
	static function save_settings() {
		if ( $controller = Admin\Controllers::get( 'settings' ) ) {
			$controller->save();
		}
	}

	static function license_notices() {

		if ( ! current_user_can( 'manage_woocommerce' ) || self::is_page( 'licenses' ) ) {
			return;
		}

		if ( Licenses::has_expired_products() ) {

			if ( get_transient( 'aw_dismiss_licence_expiry_notice' ) )
				return; // notice has been dismissed

			$strong = __( 'Your AutomateWoo license has expired.', 'automatewoo' );
			$more = sprintf(
				__( '<a href="%s" target="_blank">Renew your license</a> to receive updates and support.', 'automatewoo' ),
				Licenses::get_renewal_url( 'expired-license-notice' ),
				self::page_url( 'licenses' )
			);

			self::notice( 'warning is-dismissible', $strong, $more, 'aw-notice-licence-renew' );
		}

		if ( Licenses::has_unactivated_products() ) {

			if ( Addons::has_addons() ) {
				$strong = __( 'AutomateWoo - You have unactivated products.', 'automatewoo' );
			} else {
				$strong = __( 'AutomateWoo is not activated.', 'automatewoo' );
			}

			$more = sprintf(
				__( 'Please enter your <a href="%s">license here</a>.', 'automatewoo' ),
				self::page_url( 'licenses' )
			);

			self::notice( 'warning', $strong, $more );
		}
	}

	/**
	 * @param $page
	 * @return bool
	 */
	static function is_page( $page ) {

		$current_page = Clean::string( aw_request( 'page' ) );
		$current_tab = Clean::string( aw_request( 'tab' ) );

		switch ( $page ) {
			case 'dashboard':
				return $current_page == 'automatewoo-dashboard';
				break;
			case 'settings':
				return $current_page == 'automatewoo-settings';
				break;
			case 'reports':
				return $current_page == 'automatewoo-reports';
				break;
			case 'licenses':
				return $current_page == 'automatewoo-licenses';
				break;
		}

		return false;
	}

	/**
	 * @param $type (warning,error,success)
	 * @param $strong
	 * @param string $more
	 * @param string $class
	 * @param string $button_text
	 * @param string $button_link
	 * @param string $button_class
	 */
	static function notice( $type, $strong, $more = '', $class = '', $button_text = '', $button_link = '', $button_class = '' ) {
		?>
	   <div class="notice notice-<?php echo $type ?> automatewoo-notice <?php echo $class ?>">
		   <p>
			   <strong><?php echo $strong; ?></strong> <?php echo $more; ?>
		   </p>
			 <?php if ( $button_text ): ?>
			  <p><a href="<?php echo $button_link; ?>"
			        class="button-primary <?php echo $button_class; ?>"><?php echo $button_text; ?></a></p>
			 <?php endif; ?>
	   </div>
		<?php
	}

	/**
	 * @param $ids
	 * @return array
	 */
	static function inject_woocommerce_reports_screen_ids( $ids ) {
		$ids[] = 'automatewoo_page_automatewoo-reports';
		return $ids;
	}

	/**
	 * @param $classes string
	 * @return string
	 */
	static function body_class( $classes ) {
		if ( ! Licenses::is_active() ) {
			$classes .= ' automatewoo-not-active ';
		}

		return $classes;
	}

	/**
	 * @param $stylesheets
	 * @return array
	 */
	static function add_editor_styles( $stylesheets ) {
		$stylesheets[] = AW()->admin_assets_url( '/css/editor.css' );
		return $stylesheets;
	}

	/**
	 * @param $id
	 * @param $title
	 * @param callable $callback
	 * @param null $screen
	 * @param string $context
	 * @param string $priority
	 * @param null $callback_args
	 */
	static function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		$id = 'aw_'.$id;

		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

		add_filter( "postbox_classes_{$screen}_{$id}", [ __CLASS__, 'inject_postbox_class' ] );
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	static function inject_postbox_class( $classes ) {
		$classes[] = 'automatewoo-metabox';
		$classes[] = 'no-drag';
		return $classes;
	}

	/**
	 * @param $vars array
	 */
	static function get_hidden_form_inputs_from_query( $vars ) {
		foreach ( $vars as $var ) {
			if ( empty( $_GET[$var] ) )
				continue;

			echo '<input type="hidden" name="'.esc_attr( $var ).'" value="'.esc_attr( $_GET[$var] ).'">';
		}
	}


	/**
	 * @param $tip
	 * @param bool $pull_right
	 * @param bool $allow_html
	 * @return string
	 */
	static function help_tip( $tip, $pull_right = true, $allow_html = false ) {

		if ( $allow_html ) {
			$tip = wc_sanitize_tooltip( $tip );
		} else {
			$tip = esc_attr( $tip );
		}

		return '<span class="automatewoo-help-tip '.( $pull_right ? 'automatewoo-help-tip--right' : '' ).' woocommerce-help-tip" data-tip="'.$tip.'"></span>';
	}


	/**
	 * @param string $type
	 * @param string $dashicon
	 * @param bool $tip
	 * @return string
	 */
	static function badge( $type, $dashicon, $tip = false ) {
		$html = '<span class="automatewoo-badge automatewoo-badge--' . $type . ' automatewoo-tiptip" data-tip="' . esc_attr( $tip ) . '">';
		$html .= '<span class="dashicons dashicons-' . $dashicon . '"></span>';
		$html .= '</span>';
		return $html;
	}


	/**
	 * @param $url
	 * @param bool $pull_right
	 * @return string
	 */
	static function help_link( $url, $pull_right = true ) {
		return '<a href="'.$url.'" class="automatewoo-help-link '.( $pull_right ? 'automatewoo-help-link--right' : '' ).'" target="_blank"></a>';
	}


	/**
	 * @param string $page
	 * @param string|bool $utm_source
	 * @param string|bool $utm_campaign
	 * @return string
	 */
	static function get_docs_link( $page = '', $utm_source = false, $utm_campaign = false ) {
		return self::get_website_link( "docs/$page", $utm_source, $utm_campaign );
	}

	/**
	 * @param string $page
	 * @param string|bool $utm_source
	 * @param string|bool $utm_campaign
	 * @return string
	 */
	static function get_website_link( $page = '', $utm_source = false, $utm_campaign = false ) {
		$url = 'https://automatewoo.com/'.( $page ? trailingslashit( $page ) : '' );

		if ( $utm_source ) {
			$url = add_query_arg( [
				'utm_source' => $utm_source,
				'utm_medium' => 'plugin',
				'utm_campaign' => $utm_campaign ? $utm_campaign : 'plugin-links'
			], $url );
		}

		return $url;
	}

	/**
	 * Output loader
	 */
	static function email_preview_loader() {
		header( "Expires: ".gmdate( "D, d M Y H:i:s", time() + DAY_IN_SECONDS )." GMT" );
		self::get_view( 'email-preview-loader' );
		exit;
	}

}
